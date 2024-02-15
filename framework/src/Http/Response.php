<?php

namespace Cascata\Framework\Http;

class Response
{
    private function __construct(
        private int $statusCode = 200,
        private array $headers = [],
        private string $content = '',
    ) {}

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function toArray(): array
    {
        return [
            $this->getStatusCode(),
            $this->getHeaders(),
            $this->getContent()
        ];
    }

    public static function response(int $code = 200, array $headers = [], mixed $data = ''): self
    {
        return new self(
            $code,
            $headers,
            json_encode($data)
        );
    }

    public static function ok(mixed $data): self
    {
        return new self(
            200,
            ['Content-type' => 'application/json'],
            json_encode($data)
        );
    }

    public static function error(int $code, string $reason): self
    {
        return new self(
            $code,
            ['Content-type' => 'application/json'],
            json_encode(['message' => $reason])
        );
    }

    public static function internalServerError(string $reason): self
    {
        return new self(
            500,
            ['Content-type' => 'application/json'],
            json_encode(['message' => $reason])
        );
    }

    public static function notFound(array $headers = [], string $data = ""): self
    {
        return new self(
            404,
            $headers,
            json_encode($data)
        );
    }

    public static function noContent(): self
    {
        return new self(
            204,
            [],
            ''
        );
    }

    public static function badRequest(mixed $errors): self
    {
        return new self(
            400,
            ['Content-type' => 'application/json'],
            json_encode(['errors' => $errors])
        );
    }

    public static function created($data): self
    {
        return new self(
            201,
            [],
            json_encode($data)
        );
    }

    public static function unauthorized(string $data = ''): self
    {
        return new self(
            401,
            [],
            $data
        );
    }

    public static function methodNotAllowed(array $headers = [], string $data = ''): self
    {
        return new self(
            401,
            $headers,
            json_encode($data)
        );
    }
}