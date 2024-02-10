<?php

namespace Cascata\Framework\Http;

class Response
{
    public static function ok(mixed $data): array
    {
        return [
            200,
            ['Content-type', 'application/json'],
            json_encode($data)
        ];
    }

    public static function error(int $code, string $reason): array
    {
        return [
            $code,
            ['Content-type' => 'application/json'],
            json_encode(['message' => $reason])
        ];
    }

    public static function internalServerError(string $reason): array
    {
        return [
            500,
            ['Content-type' => 'application/json'],
            json_encode(['message' => $reason])
        ];
    }

    public static function notFound(): array
    {
        return [404, [], ''];
    }

    public static function noContent(): array
    {
        return [204, [], ''];
    }

    public static function badRequest(mixed $errors): array
    {
        return [
            400,
            ['Content-type' => 'application/json'],
            json_encode(['errors' => $errors])
        ];
    }

    public static function created($data): array
    {
        return [201, [], json_encode($data)];
    }

    public static function unauthorized(): array
    {
        return [401, [], ''];
    }
}