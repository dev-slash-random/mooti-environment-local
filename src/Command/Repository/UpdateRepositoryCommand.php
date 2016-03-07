<?php
namespace Mooti\Base\Core\Command\Repository;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Mooti\Xizlr\Core\Xizlr;
use Mooti\Base\Core\FileSystem;
use Mooti\Base\Core\Git;

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
        $fileSystem = $this->createNew(FileSystem::class);
        $git        = $this->createNew(Git::class);

        $curDir = $fileSystem->getCurrentWorkingDirectory();

        $mootiFilePath = $curDir.'/mooti.json';

        $contents = $fileSystem->fileGetContents($mootiFilePath);

        $mootiConfig = json_decode($contents, true);

        if (!$mootiConfig) {
            throw new MalformedDataException('The contents of '.$mootiFilePath.' are not valid json');
        }

        $servicesPath       = $curDir.'/repositories/services';
        $appsPath           = $curDir.'/repositories/apps';

        if (!$fileSystem->fileExists($servicesPath)) {
            $fileSystem->createDirectory($servicesPath);
        }
        if (!$fileSystem->fileExists($appsPath)) {
            $fileSystem->createDirectory($appsPath);
        }

        foreach ($mootiConfig['repositories']['services'] as $service) {
            $repoPath = $servicesPath.'/'.$service['name'].'.service.dev.mooti.local';
            if (!$fileSystem->fileExists($repoPath)) {
                $git->cloneRepo($service['url'], $repoPath);
            }
            $fileSystem->changeDirectory($repoPath);
            $git->pull();
        }
        $fileSystem->changeDirectory($curDir);

        foreach ($mootiConfig['repositories']['apps'] as $app) {
            $repoPath = $appsPath.'/'.$app['name'];
            if (!file_exists($repoPath)) {
                $git->cloneRepo($app['url'], $repoPath);
            }
            $fileSystem->changeDirectory($repoPath);
            $git->pull();
        }
        $fileSystem->changeDirectory($curDir);

        $output->writeln('done');
    }
}
