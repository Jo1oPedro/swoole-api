<?php

namespace Cascata\Framework\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateMigration extends Command
{
    protected static string $name = "create-migration";

    protected static string $defaultDescription = "Create a new migration file";

    protected function configure(): void
    {
        $this
            ->setHelp(self::$defaultDescription)
            ->setDescription(self::$defaultDescription)
            ->setName(self::$name)
            ->setDefinition(
                new InputDefinition([
                    new InputOption(
                        'name',
                        null,
                        InputOption::VALUE_REQUIRED,
                        'Create a new migration'
                    )
                ])
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getOption('name');
        $migrationTable = getStringBetween(
            $name,
            "_",
            "_"
        );

        $migrationTemplateContent = file_get_contents(__DIR__ . "/../database/Migration/migration_template.txt");
        $migrationContent = str_replace(
            "{table-name}",
            $migrationTable,
            $migrationTemplateContent
        );

        $currentTimestamp = date('Y_m_d_His', time());
        file_put_contents(
            BASE_PATH . "/database/migrations/{$currentTimestamp}_{$name}.php",
            $migrationContent
        );
        return Command::SUCCESS;
    }
}