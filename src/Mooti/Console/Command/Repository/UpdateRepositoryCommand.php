<?php
namespace Mooti\Console\Command\Repository;

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

        $mootiConfig = json_decode($contents);

        mkdir($curDir.'/repositories/services', 0775, true);
        mkdir($curDir.'/repositories/apps', 0775, true);
        mkdir($curDir.'/repositories/infrastructure', 0775, true);

        $output->writeln('hello');

    }
}
