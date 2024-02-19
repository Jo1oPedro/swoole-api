<?php

namespace Database\factories;

use Cascata\Framework\database\Faker\Faker;

class UserFactory extends Faker
{

    public function definition(): array
    {
        return [
            'name' => $this->factory->name('male'),
            'email' => $this->factory->email(),
            'password' => $this->factory->password()
        ];
    }
}