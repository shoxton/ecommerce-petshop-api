<?php

namespace App\Services;

interface JWTContract
{
    public function createToken(string $identifier);
    public function getToken(string $uuid): string;
    public function parseToken(string $jwt);
    public function validateToken(string $jwt): bool;
    public function getTokenClaims(string $jwt): array;
}
