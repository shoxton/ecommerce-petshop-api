<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserWithTokenResource;
use App\Models\User;
use App\Services\JWTService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->input());

        return new UserWithTokenResource($user);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $user = $request->attributes->get('user');

        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request)
    {
        $user = $request->attributes->get('user');

        $user->update($request->input());

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $user = $request->attributes->get('user');

        $user->delete();

        return response()->json()->status(200);
    }

    public function login(Request $request, JWTService $jwtService)
    {

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {

            return response()->json(['error' => 'Invalid credentials.']);
        }

        $user = Auth::user();
        $token = $jwtService->getToken($user->uuid);

        return response()->json(compact('token'));
    }
}
