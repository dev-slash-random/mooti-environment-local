<?php
namespace Mooti\Base\Core\Command\Repository;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Mooti\Xizlr\Core\Xizlr;

class UpdateRepositoryCommand extends Command
{
    use Xizlr;

    protected function configure()
    {
        $this->setName('repository:update');
        $this->setDescription('Update repositories. This will read your mooti json and update your local repos');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $curDir = getcwd();
        $mootiFilePath = $curDir.'/mooti.json';

        if (file_exists($mootiFilePath) == false) {
            throw new \Exception("Error Processing Request", 1);
        }

        $contents = file_get_contents($mootiFilePath);

        $mootiConfig = json_decode($contents, true);

        $servicesPath       = $curDir.'/repositories/services';
        $appsPath           = $curDir.'/repositories/apps';

        if (!file_exists($servicesPath)) {
            mkdir($servicesPath, 0775, true);
        }
        if (!file_exists($appsPath)) {
            mkdir($appsPath, 0775, true);
        }

        foreach ($mootiConfig['repositories']['services'] as $service) {
            $repoPath = $servicesPath.'/'.$service['name'].'.service.dev.mooti.local';;
            if (!file_exists($repoPath)) {
                shell_exec('git clone '.$service['url'].' '.$repoPath);
            }
            chdir($repoPath);
            shell_exec('git pull');
        }
        chdir($curDir);

        foreach ($mootiConfig['repositories']['apps'] as $app) {
            $repoPath = $appsPath.'/'.$app['name'];
            if (!file_exists($repoPath)) {
                shell_exec('git clone '.$app['url'].' '.$repoPath);
            }
            chdir($repoPath);
            shell_exec('git pull');
        }
        chdir($curDir);

        $output->writeln('done');
    }
}
