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
    public function signUpForCustomer(CreateUserRequest $request): JsonResponse {
        try {
            DB::beginTransaction();
            $user = [
                'name' => $request->name,
                'email' => $request->email,
                'role_type' => RoleTypeEnum::CUSTOMER,
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

}
