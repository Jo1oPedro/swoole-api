<?php

namespace Cascata\Framework\events;

use Cascata\Framework\Container\Container;
use Swoole\Table;

class Events
{
    protected array $events = [];

    private static ?Events $instance = null;

    private function __construct() {}

    public static function getInstance(): Events
    {
        if(is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function addEvent(string $key, callable $callback): void
    {
        if(!isset($this->events[$key])) {
            $this->events[$key] = [];
        }
        $this->events[$key][] = $callback;
    }

    public function getEvents(): array
    {
        return $this->events;
    }

    public function dispatch(string $key, string $data): void
    {
        $container = Container::getInstance();

        /** @var Table $eventsTable */
        $eventsTable = $container->get('events-table');
        $eventsTable->set(count($eventsTable), [
            'event_key' => $key,
            'event_data' => $data
        ]);
    }
}