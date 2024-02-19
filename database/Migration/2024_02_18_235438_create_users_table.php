<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\MySqlBuilder;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(MySqlBuilder $builder): void
    {
        $builder->create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 40);
            $table->string('email', 80)->unique();
            $table->string('password', 150);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(MySqlBuilder $builder): void
    {
        $builder->dropIfExists('users');
    }
};