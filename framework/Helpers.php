<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Cascata\Framework\Http\Request;

function getAuthorizationToken(Request $request): string
{
    return str_replace('Bearer ', '', $request->header['authorization'] ?? "");
}

function getAuthenticatedUserData(Request $request): stdClass
{
    return JWT::decode(
        getAuthorizationToken($request),
        new Key(
            $_ENV['JWT_KEY'],
            'HS256'
        )
    );
}

function getStringBetween(string $string, string $start, string $end)
{
    $firstPosition = strpos($string, $start) + 1;
    $lastPosition = strrpos($string, $end) + 1;
    return substr($string, $firstPosition, (strlen($string) - $lastPosition));
}