<?php
namespace Mooti\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitialiseSystemCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('system:init')
            ->setDescription('Initialise the system');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln($text);
    }
}
