<?php

namespace Database\seeders;

use Cascata\Framework\database\Seed\SeederInterface;
use Database\factories\UserFactory;

class Seeder implements SeederInterface
{
    public function run()
    {
        UserFactory::count(3)->create();
    }
}