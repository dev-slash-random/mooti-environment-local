<?php
namespace Mooti\Platform\Command\Project;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Mooti\Framework\Framework;
use Mooti\Platform\Config\PlatformConfig;
use Mooti\Platform\Util\FileSystem;

class InitProjectCommand extends Command
{
    use Framework;

    protected function configure()
    {
        $this->setName('project:init');
        $this->setDescription('Update repositories. This will read your platform json and update your local repos');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion('Warning! This might wipe any existing project files in this directory. Would you like to continue? (yes/no) ', false);

        if (!$helper->ask($input, $output, $question)) {
            return;
        }

        $platformConfig = $this->createNew(PlatformConfig::class);
        $platformConfig->init();
        $platformConfig->save();

        $fileSystem = $this->createNew(FileSystem::class);
        $curDir = $fileSystem->getCurrentWorkingDirectory();
        $fileSystem->createDirectory($curDir.'/synced-folder');
        $fileSystem->createDirectory($curDir.'/synced-folder/repositories');
        $fileSystem->createDirectory($curDir.'/synced-folder/apache');
        $fileSystem->createDirectory($curDir.'/synced-folder/apache/sites-available');

        $output->writeln('done');
    }
}
