<?php

namespace Cascata\Framework\database\Faker;

use Faker\Factory;
use Faker\Generator;

abstract class Faker
{
    protected Generator $factory;
    private int $rows = 1;

    public function __construct()
    {
        $this->factory = Factory::create();
    }

    private function count(int $rows): Faker
    {
        $this->rows = $rows;
        return $this;
    }

    public function create(string $path = "")
    {
        $calledClass = class_basename(get_called_class());
        $entity = str_replace("Factory", "", $calledClass);
        for($rows = 0; $rows < $this->rows; $rows++) {
            $reflection = new \ReflectionClass("App\Models\\{$path}" . $entity);
            $model = $reflection->newInstance();
            $model->fill($this->definition());
            $model->save();
        }

        $this->rows = 1;
    }

    public abstract function definition();

    public static function __callStatic(string $name, array $arguments)
    {
        return (new static())->$name(...$arguments);
    }
}