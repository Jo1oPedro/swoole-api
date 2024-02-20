<?php

namespace Tests;

use Cascata\Framework\database\Migration\Migration;
use Cascata\Framework\database\Seed\Seed;
use GuzzleHttp\Client;

class ApiUserTest extends TestCase
{
    protected function setUp(): void
    {
        $this->getApp();
    }

    public function teste_can_get_users(): void
    {
        $client = new Client();
        $response = $client->request('GET', 'http://localhost:9999/products');
        $this->assertEquals(200, $response->getStatusCode());
    }
}