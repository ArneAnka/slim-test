<?php

namespace App\Validation\Rules;

use App\Models\UserModel as user;
use Respect\Validation\Rules\AbstractRule;

class EmailAvailable extends AbstractRule
{
    public function validate($input)
    {
    	$user = new user;
        return $user->doesEmailAlreadyExist($input);
    }
}
