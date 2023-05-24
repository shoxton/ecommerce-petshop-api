<?php

namespace App\Services;

interface JwtContract
{
    public function createToken(string $identifier);
    public function getToken(string $uuid): string;
    public function parseToken(string $jwt);
    public function getTokenClaims(string $jwt): array;

    public function validateToken(string $jwt): bool;
}
