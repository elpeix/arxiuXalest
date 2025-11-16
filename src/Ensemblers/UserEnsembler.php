<?php
namespace App\Ensemblers;

use App\Models\User;
use App\Payloads\UserPayload;


class UserEnsembler {

    public function ensemble(User $user): UserPayload {
        $payload = new UserPayload();
        $payload->id = $user->getId();
        $payload->firstName = $user->getValue('firstName');
        $payload->lastName = $user->getValue('lastName');
        $payload->email = $user->getValue('email');
        $payload->isStaff = $user->isStaff();
        $payload->level = $user->getValue('level');
        return $payload;
    }

}