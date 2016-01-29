<?php
namespace Mooti\Console\Command\System;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

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
        $formatter = $this->getHelper('formatter');
        $helper = $this->getHelper('question');

        $errorMessages = array('Warning! This action will reset you mooti ecosystem', 'Do you want to continue?', 'N/y');
        $formattedBlock = $formatter->formatBlock($errorMessages, 'comment');
        $question = new ConfirmationQuestion($formattedBlock, false);

        if (!$helper->ask($input, $output, $question)) {
            return;
        }

        $currentDirectory = getcwd();
        $mootiVersionFile = $currentDirectory.'/mooti.version';
        $question = new Question('Please enter your domain (example.com): ', 'example.com');
        $domain = $helper->ask($input, $output, $question);

        $mootiConfig = [
            'domain' => '0.0.1',
            'repositories' => [
                'services' => [[
                        'name' => 'account',
                        'url'  => 'git@github.com:mooti/mooti-service-account.git'
                    ]
                ],
                'apps' => [[
                        'name' => 'status',
                        'url'  => 'git@github.com:mooti/mooti-app-status.git'
                    ]
                ],
                'infrastructure' => [
                    'url' => 'git@github.com:mooti/mooti-infrastructure.git'
                ]
            ]
        ];

        file_put_contents($mootiVersionFile, json_encode($mootiConfig, JSON_PRETTY_PRINT));
        //$output->writeln('test '.$domain.' - '.$version->getVersion());
    }
}
