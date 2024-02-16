<?php

namespace Cascata\Framework\Http\requestValidation;

use Cascata\Framework\Http\Request;
use Respect\Validation\Validator;

abstract class FormRequest
{
    public function __construct(
        protected Request $request
    ) {}

    public function __call(string $name, array $arguments): mixed
    {
        return $this->request->$name($arguments);
    }

    public function __get(string $name): mixed
    {
        return $this->request->$name;
    }

    protected function validateRequest(): void
    {
        $validations = [];
        foreach($this->rules() as $key => $individualRule) {
            $rules = explode('|', $individualRule);
            $validationRules = [];
            foreach($rules as $rule) {
                $rule = explode(":", $rule);
                $this->request->setValidatedFields($key);
                $ruleArgs = [];
                if(count($rule) > 1) {
                    $args = explode(",", $rule[1]);
                    foreach($args as $arg) {
                        $ruleArgs[] = $arg;
                    }
                }
                if(count($ruleArgs)) {
                    $validationRules[] = Validator::{$rule[0]}(...$ruleArgs);
                    continue;
                }
                $validationRules[] = Validator::{$rule[0]}();
            }
            $validations[] = Validator::key(
                $key,
                Validator::allOf(
                    ...$validationRules
                )
            )->setName($key);
        }

        Validator::allOf(...$validations)->assert($this->request->post);
    }

    public abstract function rules(): array;

    public function getRequest(): Request
    {
        return $this->request;
    }
}