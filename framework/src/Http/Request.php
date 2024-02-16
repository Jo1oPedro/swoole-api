<?php

namespace Cascata\Framework\Http;
use stdClass;
use Swoole\Http\Request as SwooleRequest;

class Request
{
    private array $validatedFields = [];

    private ?stdClass $authenticatedUserInfo = null;

    public function __construct(
        private SwooleRequest $request
    ) {}

    public function __get(string $name): mixed
    {
        return $this->request->$name;
    }

    public function __set(string $name, mixed $value): void
    {
        $this->request->$name = $value;
    }

    public function __call(string $name, mixed $arguments): mixed
    {
        return $this->request->$name($arguments);
    }

    public function setAuthenticatedUserInfo(stdClass $authenticatedUserInfo): void
    {
        $this->authenticatedUserInfo = $authenticatedUserInfo;
    }

    public function getAuthenticatedUserInfo(): ?stdClass
    {
        return $this->authenticatedUserInfo;
    }

    public function getSendFields(): array
    {
        return array_merge($this->request->post ?? [], $this->request->files ?? []);
    }

    public function setValidatedFields(string $field): void
    {
        $this->validatedFields[] = $field;
    }

    public function getValidatedFields(): array
    {
        $validatedFields = [];

        foreach($this->getSendFields() as $key => $field) {
            if(in_array($key, $this->validatedFields)) {
                $validatedFields[$key] = $field;
            }
        }
        return $validatedFields;
    }
}