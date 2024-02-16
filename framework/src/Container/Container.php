<?php

namespace Cascata\Framework\Container;

use DI\Definition\Source\MutableDefinitionSource;
use DI\Proxy\ProxyFactory;
use Psr\Container\ContainerInterface;

class Container extends \DI\Container
{
    public static ?Container $instance = null;
    private function __construct(MutableDefinitionSource|array $definitions = [], ProxyFactory $proxyFactory = null, ContainerInterface $wrapperContainer = null)
    {
        parent::__construct($definitions, $proxyFactory, $wrapperContainer);
    }

    public static function getInstance(): Container
    {
        if(is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}