<?php

namespace Cascata\Framework\events;

use Swoole\Timer;

class Events
{
    protected array $listeners = [];

    private static ?Events $instance = null;

    private function __construct() {}

    public static function getInstance(): Events
    {
        if(is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function addListener(string $event, callable $callback): void
    {
        if(!isset($this->listeners[$event])) {
            $this->listeners[$event] = [];
        }
        $this->listeners[$event][] = $callback;
    }

    public function dispatch(string $event, ...$parameters): void
    {
        $listeners = [];
        if(isset($this->listeners[$event])) {
            $listeners = $this->listeners[$event];
        }

        foreach ($listeners as $listener) {
            Timer::after(1, $listener, ...$parameters);
        }
    }
}