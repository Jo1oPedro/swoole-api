<?php

namespace Cascata\Framework\Bootstrap;

use Cascata\Framework\database\Migration\Migration;
use Cascata\Framework\database\Seed\Seed;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\ConsoleOutput;

class Command
{
    public static function processCommands(): bool
    {
        $input = self::getConsoleInput();
        switch ($input->getArgument('action')) {
            case 'migrate':
                Migration::handle($input->getOption('fresh'));
                return true;
            case 'create-migration':
                Migration::create($input->getOption('name'));
                (new ConsoleOutput())->writeln('Migration created');
                return true;
            case 'db:seed':
                (new ConsoleOutput())->writeln('Running seed');
                Seed::handle($input);
                return true;
        }
        return false;
    }

    private static function getConsoleInput(): InputInterface
    {
        global $argv;

        $output = new ConsoleOutput();

        $definition = new InputDefinition([
            new InputArgument('action', InputArgument::OPTIONAL, 'Action to be taken.'),
            new InputOption('fresh', null, InputOption::VALUE_NONE, 'Make migration running fresh', null),
            new InputOption('name', null, InputOption::VALUE_REQUIRED, 'Name the new model', null)
        ]);

        try {
            return new ArgvInput($argv, $definition);
        } catch (\Exception $exception) {
            $output->writeln('');
            $output->writeln(
                '<error>There was an error while starting application:' . $exception->getMessage() . '</error>');
            $output->writeln('');
            exit(1);
        }
    }
}