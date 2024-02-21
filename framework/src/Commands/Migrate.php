<?php

namespace Cascata\Framework\Commands;

use Cascata\Framework\Container\Container;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Migrate extends Command
{
    protected static string $name = "migrate";

    protected static string $defaultDescription = "Run all the migrations";

    protected function configure(): void
    {
        $this
            ->setHelp(self::$defaultDescription)
            ->setDescription(self::$defaultDescription)
            ->setName(self::$name)
            ->setDefinition(
                new InputDefinition([
                    new InputOption(
                        'fresh',
                        null,
                        InputOption::VALUE_NONE,
                        'Set the migration to remove existent tables and recreate them.'
                    )
                ])
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $db = Container::getInstance()->get('db')->schema();
        $migrationDir = scandir(BASE_PATH . "/database/migrations");
        $migrationDir = array_slice($migrationDir, 2);
        ProgressBar::setFormatDefinition('custom', ' %current%/%max% -- %message%');
        $progressBar = new ProgressBar($output, count($migrationDir));
        $progressBar->setFormat('custom');
        foreach ($migrationDir as $migration) {
            $progressBar->setMessage("Running migration: {$migration}" . PHP_EOL);
            $migrationClass = require_once BASE_PATH . "/database/migrations/{$migration}";
            if($input->getOption('fresh')) {
                $migrationClass->down($db);
            }
            $migrationClass->up($db);
            $progressBar->advance();
        }
        return Command::SUCCESS;
    }
}