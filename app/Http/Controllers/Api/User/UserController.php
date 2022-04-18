<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ForgotUserPasswordRequest;
use App\Http\Requests\User\LoginUserRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Services\UserManager\UserManagerInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    private UserManagerInterface $userManager;

    public final function __construct(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @param StoreUserRequest $userRequest
     * @return JsonResponse
     *
     * @OA\Post(
     *     path="/user",
     *     summary="Create new user",
     *     tags={"User"},
     *     @OA\RequestBody(
     *         request="User",
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="name",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                  property="email",
     *                  type="string"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized user"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Create new user",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     )
     * )
     */
    public final function create(StoreUserRequest $userRequest): JsonResponse
    {
        if ($this->userManager->register($userRequest->all())) {
            return response()->json(['message' => 'User created successfully'], Response::HTTP_CREATED);
        } else {
            return response()->json(['message' => 'Failed to create user'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param UpdateUserRequest $userRequest
     * @return JsonResponse
     *
     * @OA\Put(
     *     path="/user",
     *     summary="Update user",
     *     tags={"User"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         request="User",
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="name",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                  property="email",
     *                  type="string"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized user"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Update user",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     )
     * )
     */
    public final function update(UpdateUserRequest $userRequest):JsonResponse
    {
        $result = $this->userManager->update($userRequest->all());
        if ($result) {
            return response()->json(['message' => 'User updated'], Response::HTTP_ACCEPTED);
        } else {
            return response()->json(['message' => 'Failed to update user'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param LoginUserRequest $userRequest
     * @param UserManagerInterface $userManager
     * @return JsonResponse
     *
     * @OA\Put(
     *     path="/user/login",
     *     summary="Authenticate user",
     *     tags={"User"},
     *     @OA\RequestBody(
     *         request="User",
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="email",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                  property="password",
     *                  type="string"
     *             ),
     *             @OA\Property(
     *                  property="confirm_password",
     *                  type="string"
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized user"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful login",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="token",
     *                 type="string"
     *             )
     *         )
     *     )
     * )
     */
    public final function login(LoginUserRequest $userRequest, UserManagerInterface $userManager): JsonResponse
    {
        $token = $userManager->login($userRequest->only(['email', 'password', 'confirm_password']));
        if ($token)
            return response()->json(['token' => $token], Response::HTTP_OK);
        else
            return response()->json(['message' => 'Authentication failed'], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @OA\Get(
     *     path="/user",
     *     summary="Show user details",
     *     tags={"User"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *            mediaType="application/json"
     *         )
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized user"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Show user details",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     )
     * )
     *
     * @return JsonResponse
     */
    public final function details(): JsonResponse
    {
        return response()->json($this->userManager->userDetails());
    }

    /**
     * @param ForgotUserPasswordRequest $request
     * @return JsonResponse
     *
     * @OA\Post(
     *     path="/user/forgot-password",
     *     summary="Send reset password link for user",
     *     tags={"User"},
     *     @OA\RequestBody(
     *         request="User",
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="email",
     *                 type="string"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Reset password link sent"
     *     )
     * )
     */
    public final function forgot(ForgotUserPasswordRequest $request): JsonResponse
    {
        $email = $request->json('email');
        if ($this->userManager->resetPassword($email)) {
            return response()->json(['message' => 'Password has been reset'], Response::HTTP_ACCEPTED);
        } else {
            return response()->json(['message' => 'Failed to reset password'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param string $token
     * @param ForgotUserPasswordRequest $request
     * @return JsonResponse
     *
     * @OA\Post(
     *     path="/user/reset-password/{token}?email={email}",
     *     summary="Set new password for user",
     *     tags={"User"},
     *     @OA\Parameter(
     *          name="token",
     *          in="query",
     *          required=false,
     *          explode=false,
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="email",
     *          in="query",
     *          required=false,
     *          explode=false,
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\RequestBody(
     *         request="User",
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="password",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                  property="confirm_password",
     *                  type="string"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Password updated"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable entity"
     *     )
     * )
     */
    public final function reset(string $token, ForgotUserPasswordRequest $request): JsonResponse
    {
        if ($this->userManager->updatePassword($token, $request->only(['email', 'password', 'confirm_password']))) {
            return response()->json(['message' => 'Successfully update password'], Response::HTTP_ACCEPTED);
        } else {
            return response()->json(['message' => 'Failed to update password'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
