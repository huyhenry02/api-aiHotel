<?php

namespace App\Http\Controllers\Api\User;

use App\Enums\RoleTypeEnum;
use App\Http\Controllers\ApiController;
use App\Modules\File\Repositories\Interfaces\FileInterface;
use App\Modules\User\Models\User;
use App\Modules\User\Repositories\Interfaces\UserInterface;
use App\Modules\User\Requests\CreateUserRequest;
use App\Modules\User\Requests\GetUserByUserIdRequest;
use App\Modules\User\Requests\ListUserRequest;
use App\Modules\User\Requests\UpdateUserRequest;
use App\Modules\User\Transformers\UserTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class UserController extends ApiController
{
    protected UserInterface $userRepo;
    protected FileInterface $fileRepo;

    public function __construct(UserInterface $user, FileInterface $fileRepo)
    {
        $this->userRepo = $user;
        $this->fileRepo = $fileRepo;
    }

    public function createUser(CreateUserRequest $request): JsonResponse
    {
        $currentUser = Auth::user();
        if ($currentUser->role_type !== RoleTypeEnum::ADMIN->value) {
            return $this->respondError(__('messages.access_denied'));
        }
        $postData = $request->validated();
        try {
            DB::beginTransaction();
            $user = $this->userRepo->create($postData);
            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $filePath = $this->fileRepo->uploadFile($file, User::class, $user->id, 'avatar');
                $postData['avatar'] = $filePath;
            }
            $data = fractal($user, new UserTransformer())->parseIncludes(['files'])->toArray();
            $response = $this->respondSuccess($data);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $response = $this->respondError($e->getMessage(), 400);
        }
        return $response;
    }

    public function signUpForCustomer(CreateUserRequest $request): JsonResponse
    {
        $postData = $request->validated();
        try {
            DB::beginTransaction();
            $postData['role_type'] = RoleTypeEnum::CUSTOMER->value;
            $user = $this->userRepo->create($postData);
            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $filePath = $this->fileRepo->uploadFile($file, User::class, $user->id, 'avatar');
                $postData['avatar'] = $filePath;
            }
            $data = fractal($user, new UserTransformer())->parseIncludes(['files'])->toArray();
            DB::commit();
            if (Auth::attempt(['email' => $postData['email'], 'password' => $postData['password']])) {
                $auth_user = Auth::user();
                $respData = [
                    "message" => 'Login successfully',
                    'access_token' => $auth_user->createToken('Ai-Hotel')->accessToken,
                    'customer' => $data,
                ];
                return $this->respondSuccess($respData);
            } else {
                return $this->respondFailedLogin();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $response = $this->respondError($e->getMessage(), 400);
        }
        return $response;
    }

    public function getMyInfo(): JsonResponse
    {
        $user = Auth::user();
        $data = fractal($user, new UserTransformer())->parseIncludes(['files'])->toArray();
        return $this->respondSuccess($data);
    }

    public function getUserInfo(GetUserByUserIdRequest $request): JsonResponse
    {
        $currentUser = Auth::user();
        if ($currentUser->role_type !== RoleTypeEnum::ADMIN->value) {
            return $this->respondError(__('messages.access_denied'));
        }
        try {
            $user = $this->fileRepo->findWithFile(modelType: User::class, modelId: $request->user_id);
            $data = fractal($user, new UserTransformer())->parseIncludes(['files'])->toArray();
            $response = $this->respondSuccess($data);
        } catch (\Exception $e) {
            $response = $this->respondError($e->getMessage(), 400);
        }
        return $response;
    }

    public function getListUser(ListUserRequest $request): JsonResponse
    {
        $currentUser = Auth::user();
        if ($currentUser->role_type !== RoleTypeEnum::ADMIN->value) {
            return $this->respondError(__('messages.access_denied'));
        }
        $perPage = $request->validated('per_page', 15);
        $type = $request->validated('type');
        $conditions = [];
        if ($type) {
            $conditions['role_type'] = $request->validated('type');
        }
        $users = $this->userRepo->getData(['*'], $conditions, ['created_at' => 'desc'], $perPage);
        $data = fractal($users, new UserTransformer())->parseIncludes(['files'])->toArray();
        return $this->respondSuccess($data);
    }

    public function updateUser(UpdateUserRequest $request): JsonResponse
    {
        $currentUser = Auth::user();
        if ($currentUser->role_type !== RoleTypeEnum::ADMIN->value) {
            return $this->respondError(__('messages.access_denied'));
        }
        DB::beginTransaction();
        try {
            $user = $this->userRepo->find($request->user_id);
            if (!$user) {
                return $this->respondError(__('messages.not_found'));
            }
            $postData = $request->validated();
            if (!empty($postData['password'])) {
                $postData['password'] = bcrypt($postData['password']);
            }
            $user->fill($postData);
            $user->save();
            DB::commit();
            $user = fractal($user, new UserTransformer())->parseIncludes(['files'])->toArray();
            $resp = $this->respondSuccess($user);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new RuntimeException($e->getMessage());
        }
        return $resp;
    }

    public function deleteUser(GetUserByUserIdRequest $request): JsonResponse
    {
        $currentUser = Auth::user();
        if ($currentUser->role_type !== RoleTypeEnum::ADMIN->value) {
            return $this->respondError(__('messages.access_denied'));
        }
        $user = $this->userRepo->find($request->user_id);
        if ($user) {
            $user->delete();
            $user->files()->delete();
            return $this->respondSuccess(__('messages.delete_successfully'));
        }
        return $this->respondError(__('messages.not_found'));
    }
}
