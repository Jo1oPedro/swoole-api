<?php

namespace Cascata\Framework\database\Migration;

use App\Models\User;
use Cascata\Framework\Container\Container;
use Illuminate\Database\Schema\Blueprint;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;

class Migration
{
    public static function handle(InputInterface $input)
    {
        $consoleOutput = new ConsoleOutput();
        $db = Container::getInstance()->get('db')->schema();
        $migrationDir = scandir(BASE_PATH . "/database/Migration");
        $migrationDir = array_slice($migrationDir, 2);
        foreach ($migrationDir as $migration) {
            $consoleOutput->writeln("Running migration: {$migration}");
            $migrationClass = require_once BASE_PATH . "/database/Migration/{$migration}";
            $migrationClass->up($db);
        }
    }

    public static function create(InputInterface $input)
    {
        $migrationTable = getStringBetween(
            $input->getOption('name'),
            "_",
            "_"
        );

        $migrationTemplateContent = file_get_contents(__DIR__ . "/migration_template.txt");
        $migrationContent = str_replace(
            "{table-name}",
            $migrationTable,
            $migrationTemplateContent
        );

        $currentTimestamp = date('Y_m_d_His', time());
        file_put_contents(
            BASE_PATH . "/database/Migration/{$currentTimestamp}_{$input->getOption('name')}.php",
            $migrationContent
        );
    }
}