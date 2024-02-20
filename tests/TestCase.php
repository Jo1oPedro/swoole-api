<?php

namespace Tests;
use Cascata\Framework\database\Migration\Migration;
use Cascata\Framework\database\Seed\Seed;
use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    public function getApp()
    {
        Migration::handle(true);
        Seed::handle();
    }
}