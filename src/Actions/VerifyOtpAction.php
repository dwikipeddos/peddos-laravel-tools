<?php

namespace Dwikipeddos\PeddosLaravelTools\Actions;

use App\Models\User;

class VerifyOtpAction
{

    public function execute(User $user, string $otp, bool $delete = true): bool
    {
        if ($user->otp->code == $otp) {
            if ($delete)
                $user->otp()->delete();
            return true;
        } else {
            return false;
        }
    }
}
