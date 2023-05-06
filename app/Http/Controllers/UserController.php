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

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Ecommerce Petshop API OpenApi Demo Documentation",
 *      description="L5 Swagger OpenApi description",
 *      @OA\License(
 *          name="Apache 2.0",
 *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *      )
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="Demo API Server"
 * )
 *
 * @OA\SecurityScheme(
 *     type="http",
 *     scheme="bearer",
 *     name="Authorization",
 *     in="header",
 *     securityScheme="bearerAuth"
 * )
 */
class UserController extends Controller
{

    /**
     * @OA\Post(
     *     path="/v1/user/create",
     *     tags={"User"},
     *     summary="Create a new user",
     *     description="Create a new user resource and return the created resource with a JWT token",
     *     operationId="user.store",
     *     security={},
     *     @OA\RequestBody(
     *         description="Credentials of the user",
     *         required=true,
     *         @OA\JsonContent(
     *             required={"first_name", "last_name", "email", "password", "password_confirmation", "phone_number", "address"},
     *             @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *             @OA\Property(property="password", type="string", example="secret"),
     *             @OA\Property(property="password_confirmation", type="string", example="secret"),
     *             @OA\Property(property="first_name", type="string", example="John"),
     *             @OA\Property(property="last_name", type="string", example="Doe"),
     *             @OA\Property(property="address", type="string", example="123 Street 2nd Avenue"),
     *             @OA\Property(property="phone_number", type="string", example="(+123) 123 456 789"),
     *         )
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="User created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/UserWithTokenResource")
     *     ),
     *      @OA\Response(
     *          response="422",
     *          description="Unprocessable Entity",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="The email has already been taken."),
     *              @OA\Property(
     *                  property="errors",
     *                  type="object",
     *                  @OA\AdditionalProperties(
     *                      type="array",
     *                      @OA\Items(type="string", example="The email has already been taken.")
     *                  )
     *              )
     *          )
     *      ),
     *     @OA\Response(
     *         response="500",
     *         description="Server error",
     *     )
     * )
     */

    public function store(StoreUserRequest $request)
    {
        $request->merge([
            'password' => bcrypt($request->input('password'))
        ]);

        $user = User::create($request->input());

        return new UserWithTokenResource($user);
    }

    /**
     * Display the specified user resource.
     *
     * @OA\Get(
     *     path="/v1/user",
     *     operationId="user.show",
     *     tags={"User"},
     *     security={{ "bearerAuth": {} }},
     *     summary="Get a user",
     *     description="Get a user by UUID",
     *     @OA\Response(
     *         response="200",
     *         description="User updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/UserResource")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Unauthenticated"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="User not found"
     *             )
     *         )
     *     ),
     * )
     */
    public function show(Request $request)
    {
        $user = $request->attributes->get('user');

        return new UserResource($user);
    }

    /**
     * @OA\Put(
     *     path="/v1/user/edit",
     *     summary="Update a user",
     *     description="Update a user record.",
     *     tags={"User"},
     *     operationId="user.update",
     *     tags={"User"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\RequestBody(
     *         description="User data to be updated",
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="first_name", type="string", format="string", example="John"),
     *             @OA\Property(property="last_name", type="string", format="string", example="Doe"),
     *             @OA\Property(property="address", type="string", format="string", example="123 Street 2nd Avenue"),
     *             @OA\Property(property="phone_number", type="string", format="string", example="(+123) 123 456 789"),
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="User updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/UserResource")
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Validation error",
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Server error",
     *     )
     * )
     */
    public function update(UpdateUserRequest $request)
    {
        $user = $request->attributes->get('user');

        $user->update($request->input());

        return new UserResource($user);
    }

    /**
     * @OA\Delete(
     *     path="/v1/user",
     *     tags={"User"},
     *     summary="Delete the authenticated user",
     *     description="Deletes the authenticated user from the system.",
     *     operationId="user.destroy",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response="200",
     *         description="User deleted successfully"
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="User not found"
     *     )
     * )
     */
    public function destroy(Request $request)
    {
        $user = $request->attributes->get('user');

        $user->delete();

        return response()->json()->status(200);
    }

    /**
     * Log in a user.
     *
     * @OA\Post(
     *     path="/v1/user/login",
     *     tags={"User"},
     *     summary="Log in a user",
     *     operationId="user.login",
     *     @OA\RequestBody(
     *         description="Credentials of the user",
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="secret")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Token of the user",
     *         @OA\JsonContent(
     *             required={"token"},
     *             @OA\Property(property="token", type="string", example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials"
     *     )
     * )
     */
    public function login(Request $request, JWTService $jwtService)
    {

        $user = User::where('email', $request->input('email'))->first();

        if (!$user || !Hash::check($request->input('password'), $user->password)) {

            return response()->json(['error' => 'Invalid credentials.']);
        }

        $token = $jwtService->getToken($user->uuid);

        return response()->json(compact('token'));
    }
}
