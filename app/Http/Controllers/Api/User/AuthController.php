<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\ApiController;
use App\Jobs\SendEmailJob;
use App\Mail\CommonMail;
use App\Modules\User\Repositories\Interfaces\UserInterface;
use App\Modules\User\Requests\LoginRequest;
use App\Modules\User\Requests\ResetPassRequest;
use App\Modules\User\Requests\SendEmailRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends ApiController
{
    protected UserInterface $userRepo;
    /**
     * @param UserInterface $user
     */
    public function __construct(
        UserInterface $user,
    )
    {
        $this->userRepo = $user;
    }
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
    public function sendResetPasswordEmail(SendEmailRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $email = $request->validated('email');
            $result = $this->userRepo->findUserAndSendMail($email);
            $resetLink = 'http://localhost:3000' . '/reset-password?token=' . $result['token'];
            $data = [
                'resetLink' => $resetLink,
            ];
            $mailable = new CommonMail(data: $data, subject: 'Reset Password', view: 'mails.resetPassword');
            $email = [$result['user']->email];
            $cc = [];
            $bcc = [];
            dispatch(new SendEmailJob(mailable: $mailable, email: $email, cc: $cc, bcc: $bcc));
            DB::commit();
            $respData = [
                "data" => [
                    "message" => 'Send Email successfully',
                    'resetLink' => $resetLink,
                ]
            ];
            $resp = $this->respondSuccess($respData);
        } catch (Exception $e) {
            DB::rollBack();
            $resp = $this->respondError($e->getMessage());
        }
        return $resp;
    }
    public function resetPassword(ResetPassRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $postData = $request->validated();
            $result = $this->userRepo->resetPasswordWithToken($postData['token'], $postData['new_password']);
            $result['resetToken']->delete();
            $resp = $this->respondSuccessWithoutData(__('messages.reset_successfully'));
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $resp = $this->respondError($e->getMessage());
        }
        return $resp;
    }
}
