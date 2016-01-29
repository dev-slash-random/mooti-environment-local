<?php
namespace Mooti\Console\Command\Repository;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddRepositoryCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('repository:add')
            ->setDescription('Add a repository')
            ->addArgument(
                'type',
                InputArgument::REQUIRED,
                'Either "module", "application" or "infrastrature"'
            )
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'the name of the repository'
            )
            ->addArgument(
                'url',
                InputArgument::REQUIRED,
                'The git url for the repository'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        if ($name) {
            $text = 'Hello '.$name;
        } else {
            $text = 'Hello';
        }

        $output->writeln($text);
    }
}
