<?php

namespace App\Modules\User\Repositories\Interfaces;

use App\Modules\User\Models\User;
use App\Repositories\Interfaces\RepositoryInterface;
use DateTime;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Response;

interface UserInterface extends RepositoryInterface
{
    /**
     * @param User|Authenticatable $user
     * @param string $password
     * @return bool
     */
    public function changePassword(User|Authenticatable $user, string $password): bool;

    /**
     * @param string $email
     * @return array|null
     */
    public function findUserAndSendMail(string $email): ?array;

    /**
     * @param string $token
     * @param string $newPassword
     * @return array
     */
    public function resetPasswordWithToken(string $token, string $newPassword): array;

    /**
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param string $type
     * @return mixed
     */
    public function getCustomerStatistics(DateTime $startDate, DateTime $endDate, string $type): mixed;
}
