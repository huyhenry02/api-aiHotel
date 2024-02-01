<?php

namespace App\Http\Controllers\Api\User;

use App\Enums\RoleTypeEnum;
use App\Http\Controllers\ApiController;
use App\Modules\User\Repositories\Interfaces\UserInterface;
use App\Modules\User\Requests\CreateUserRequest;
use App\Modules\User\Requests\LoginRequest;
use App\Modules\User\Transformers\UserTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends ApiController
{
    protected UserInterface $userRepo;
    public function __construct(UserInterface $user)
    {
        $this->userRepo = $user;

    }
    /**
     * @OA\Post(
     *     path="/api/auth/create-user",
     *     operationId="createUser",
     *     tags={"AUTH"},
     *     summary="Adds a new user",
     *     description="Adds a new user",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     oneOf={
     *                     	   @OA\Schema(type="string"),
     *                     	   @OA\Schema(type="email"),
     *                     }
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                  @OA\Property(
     *                      property="role_type",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="address",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="phone",
     *                      type="string"
     *                  ),
     *                   @OA\Property(
     *                      property="identification",
     *                      type="string"
     *                  ),
     *                   @OA\Property(
     *                      property="age",
     *                      type="integer"
     *                  ),
     *                 example={"name": "Huy", "email": "test@gmail.com", "password": "12345678", "role_type": "customer", "address": "Ha Noi", "phone": "0123456789", "identification": "123456789", "age": "20"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="signup successfully"
     *     ),
     *      @OA\Response(
     *          response=422,
     *          description="validate failed",
     *      ),
     * )
     */
    public function createUser(CreateUserRequest $request): JsonResponse
    {
        $currentUser = Auth::user();
        if ($request->role_type === RoleTypeEnum::EMPLOYEE) {
            if ($currentUser->role_type !== RoleTypeEnum::ADMIN) {
                return $this->respondError('Only admins can create employees', 403);
            }
        }
        try {
            DB::beginTransaction();
            $user = [
                'name' => $request->name,
                'email' => $request->email,
                'role_type' => $request->role_type,
                'address' => $request->address,
                'phone' => $request->phone,
                'identification' => $request->identification,
                'age' => $request->age,
                'password' => bcrypt($request->password),
            ];
            $user = $this->userRepo->create($user);
            $data = fractal($user, new UserTransformer())->toArray();
            $response = $this->respondSuccess($data);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $response = $this->respondError($e->getMessage(), 400);
        }
        return $response;
    }

    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     operationId="login",
     *     tags={"AUTH"},
     *     summary="user login",
     *     description="user login",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                     @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 example={"username": "admin@gmail.com", "password": "Admin@123"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successfully"
     *     ),
     *      @OA\Response(
     *          response=422,
     *          description="login failed",
     *      ),
     * )
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $user = Auth::user();
                $respData = [
                    "message" => 'Login successfully',
                    'access_token' => $user->createToken('Ai-Hotel')->accessToken,
                ];
                $resp = $this->respondSuccess($respData);
            } else {
                $resp = $this->respondFailedLogin();
            }
        } catch (\Exception $e) {
            $resp = $this->respondError($e->getMessage(), $e->getCode());
        }
        return $resp;
    }
    /**
     * @OA\Post(
     *     path="/api/auth/logout",
     *     operationId="logout",
     *     tags={"AUTH"},
     *     summary="user logout",
     *     security={{"bearerAuth": {}}},
     *     description="user logout",
     *     @OA\RequestBody(
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Logout successfully"
     *     ),
     *      @OA\Response(
     *          response=401,
     *          description="Logout failed",
     *      ),
     * )
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $request->user()->token()->revoke();
            $response =  $this->respondSuccessWithoutData();
        } catch (\Exception $e) {
            $response = $this->respondError($e->getMessage());
        }
        return $response;
    }
    public function getUserInfo() {
        $user = Auth::user();
        dd($user);
    }
}
