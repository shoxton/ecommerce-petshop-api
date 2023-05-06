<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="UserResource",
 *     title="User Resource",
 *     description="User resource schema",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="User ID",
 *         example=1,
 *     ),
 *     @OA\Property(
 *         property="first_name",
 *         type="string",
 *         description="First name",
 *         example="John",
 *     ),
 *     @OA\Property(
 *         property="last_name",
 *         type="string",
 *         description="Last name",
 *         example="Doe",
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         description="Email",
 *         example="johndoe@example.com",
 *     ),
 *     @OA\Property(
 *         property="avatar",
 *         type="string",
 *         description="Avatar URL",
 *         example="https://example.com/avatar.jpg",
 *     ),
 *     @OA\Property(
 *         property="address",
 *         type="string",
 *         description="Address",
 *         example="123 Main St, Anytown USA",
 *     ),
 *     @OA\Property(
 *         property="phone_number",
 *         type="string",
 *         description="Phone number",
 *         example="555-123-4567",
 *     ),
 *     @OA\Property(
 *         property="is_marketing",
 *         type="boolean",
 *         description="Whether the user has opted into marketing emails",
 *         example=true,
 *     ),
 * )
 */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
