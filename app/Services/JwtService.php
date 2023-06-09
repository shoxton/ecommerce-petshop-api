<?php

namespace App\Services;

use DateTimeImmutable;
use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\Validation\Constraint;

class JwtService implements JwtContract
{
    private Configuration $config;

    public function __construct()
    {
        $this->config = Configuration::forAsymmetricSigner(
            new \Lcobucci\JWT\Signer\Rsa\Sha256(),
            Key\InMemory::plainText($this->getPrivateKey()),
            Key\InMemory::plainText($this->getPublicKey())
        );
    }

    public function createToken(string $uuid): Token
    {
        $now = new DateTimeImmutable();

        $token = $this->config->builder()
            ->issuedBy(config('app.url'))
            ->permittedFor(config('app.url'))
            ->identifiedBy($uuid)
            ->withClaim('user_uuid', $uuid)
            ->issuedAt($now)
            ->expiresAt($now->modify('+1 day'))
            ->canOnlyBeUsedAfter($now)
            ->getToken($this->config->signer(), $this->config->signingKey());

        return $token;
    }

    public function getToken(string $uuid): string
    {
        $token = $this->createToken($uuid);
        return $token->toString();
    }

    public function parseToken(string $jwt): Token
    {
        return $this->config->parser()->parse($jwt);
    }

    public function validateToken(string $jwt): bool
    {
        $token = $this->parseToken($jwt);

        $this->config->setValidationConstraints(
            new Constraint\StrictValidAt(SystemClock::fromUTC()),
            new Constraint\IssuedBy(config('app.url')),
            new Constraint\PermittedFor(config('app.url'))
        );

        $constraints = $this->config->validationConstraints();

        return $this->config->validator()->validate($token, ...$constraints);
    }

    public function getTokenClaims(string $jwt): array
    {
        $token = $this->parseToken($jwt);
        return $token->claims()->all();
    }

    protected function getPrivateKey(): string
    {
        return file_get_contents(config('jwt.private_key'));
    }

    protected function getPublicKey(): string
    {
        return file_get_contents(config('jwt.public_key'));
    }
}
