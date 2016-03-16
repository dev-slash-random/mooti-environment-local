<?php
namespace Mooti\Base\Core\Command\Repository;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Mooti\Xizlr\Core\Xizlr;

class UpdateRepositoriesCommand extends Command
{
    use Xizlr;

    protected function configure()
    {
        $this->setName('repository:update-repositories');
        $this->setDescription('Update repositories. This will read your mooti.json and update your local repos');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('updating repositories');

        $fileSystem = $this->createNew(FileSystem::class);

        $curDir = $fileSystem->getCurrentWorkingDirectory();
        $mootiFilePath = $curDir.'/mooti.json';

        $mootiConfig = $this->createNew(MootiConfig::class);
        $mootiConfig->setFilePath($mootiFilePath);
        $mootiConfig->open();

        foreach ($mootiConfig['repositories'] as $repository) {
            $output->writeln('update '.$repository['name']);
            $repo = $this->createNew(Repository::class, $repository['name'], $repository['url'], $repository['type']);
            $configData = [
                'apache_domain' => $mootiConfig['config']['apache_domain'],
                'alias_domains' => (isset($repository['alias_domains'])?$repository['alias_domains']:[])
            ];
            $repo->setup($curDir.'/synced-folder', $configData);
        }

        $output->writeln('done');
    }


}
