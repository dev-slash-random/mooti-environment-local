<?php
namespace Mooti\Platform\Command\Project;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Mooti\Framework\Framework;
use Mooti\Platform\Config\PlatformConfig;
use Mooti\Platform\Config\MootiConfig;
use Mooti\Platform\Util\FileSystem;
use Mooti\Platform\Util\Git;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Process\Process;

class UpdateAllRepositoriesCommand extends Command
{
    use Framework;

    protected function configure()
    {
        $this->setName('repository:update-all');
        $this->setDescription('Update all git repositories');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $platformConfig = $this->createNew(PlatformConfig::class);
        $platformConfig->open();

        $fileSystem = $this->createNew(FileSystem::class);
        $curDir = $fileSystem->getCurrentWorkingDirectory();
        $repositoriesPath = $curDir.'/synced-folder/repositories';

        
        $git = $this->createNew(Git::class);

        $config = $platformConfig->getConfig();

        $mootiConfig = $this->createNew(MootiConfig::class);

        $templateDir = __DIR__.'/../../../templates';
        $allowedServerTypes = ['php-standard'];

        foreach ($config['repositories'] as $repository) {
            $repositoryPath = $repositoriesPath.'/'.$repository['name'];
            if (!$fileSystem->fileExists($repositoryPath)) {
                $git->cloneRepo($repository['url'], $repositoryPath);
            }
            $fileSystem->changeDirectory($repositoryPath);
            $git->pull();

            $mootiConfig->setDirPath($repositoryPath);
            $mootiConfig->open();
            $mootiConfigArray = $mootiConfig->getConfig();

            if (isset($mootiConfigArray['server'])) {
                $serverType = $mootiConfigArray['server']['type'];
                if (in_array($serverType, $allowedServerTypes, true) == false) {
                    throw new DataValidationException($serverType.' is not a valid server type');
                }

                $templatePath = $templateDir.'/apache/'.$serverType.'.tpl';
                $templateContents = $fileSystem->fileGetContents($templatePath);
                $severName = $mootiConfigArray['name'].'.'.$config['config']['domain'];
                $data = [
                    '{{server_name}}'     => $severName,
                    '{{repository_path}}' => $repository['name'],
                    '{{document_root}}'   => $mootiConfigArray['server']['document_root']
                ];
                $apacheConfigContents = str_replace(array_keys($data), array_values($data), $templateContents);
                $apacheConfigPath = $curDir.'/synced-folder/apache/sites-available/'.$severName.'.conf';
                $fileSystem->filePutContents($apacheConfigPath, $apacheConfigContents);

                $scriptsToRun = [];
                if (isset($mootiConfigArray['scripts'])) {
                    $scriptsToRun = $mootiConfigArray['scripts'];
                }

                foreach ($scriptsToRun as $script) {
                    $vagrantCommand = 'cd /mooti/repositories/'.$repository['name'].' && '.$script;
                    $this->runVagrantCommand($curDir, $vagrantCommand, $output);
                }

                $vagrantCommand = 'sudo rm -f /etc/apache2/sites-available/'.$severName.'.conf && sudo ln -s /mooti/apache/sites-available/'.$severName.'.conf /etc/apache2/sites-available/'.$severName.'.conf';
                $this->runVagrantCommand($curDir, $vagrantCommand, $output);

                $vagrantCommand = 'sudo a2ensite '.$severName;
                $this->runVagrantCommand($curDir, $vagrantCommand, $output);

                $vagrantCommand = ' sudo service apache2 restart';
                $this->runVagrantCommand($curDir, $vagrantCommand, $output);
            }
        }
        $fileSystem->changeDirectory($curDir);

        $output->writeln('done');
    }

    public function runVagrantCommand($curDir, $vagrantCommand, OutputInterface $output)
    {
        $curDir = realpath(dirname($curDir.'/'.$_SERVER['argv'][0]));
        $command = $curDir.'/vagrant ssh -c "'.$vagrantCommand.'"';
        $output->writeln('Run command on devbox: '.$vagrantCommand);
        $process = $this->createNew(Process::class, $command);
        $process->setTimeout(3600);
        $process->mustRun(function ($type, $buffer) use ($output) {
            $output->writeln(trim($buffer));
        });
    }
}
