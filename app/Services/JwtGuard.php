<?php

namespace App\Services;

use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class JwtGuard implements Guard
{
    use GuardHelpers;

    protected $request;

    public function __construct(UserProvider $provider, Request $request)
    {
        $this->provider = $provider;
        $this->request = $request;
    }

    public function validate(array $credentials = [])
    {

        if (!$credentials) {
            return false;
        }

        if ($this->provider->retrieveByCredentials($credentials)) {
            return true;
        }

        return false;
    }

    public function user()
    {

        if (!is_null($this->user)) {
            return $this->user;
        }

        $decoded = $this->getValidatedToken();

        if (!$decoded) {
            return null;
        }

        $user = $this->provider->retrieveById($decoded->claims()->get('user_uuid'));

        return $this->user = $user;
    }

    protected function getBearerToken()
    {

        return $this->request->bearerToken();
    }

    protected function getValidatedToken()
    {

        $jwtService = new JwtService();

        $jwt = $this->getBearerToken();

        if (empty($jwt)) {
            return null;
        }

        try {

            if (!$jwtService->validateToken($jwt)) {
                return null;
            }

            $decoded = $jwtService->parseToken($jwt);

            return $decoded;
        } catch (\Throwable $th) {
            return null;
        }
    }
}
