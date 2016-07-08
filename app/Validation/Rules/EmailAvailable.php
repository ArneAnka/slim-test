<?php

namespace App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;

class EmailAvailable extends AbstractRule
{
    public function validate($input)
    {
        return $this->UserModel->doesEmailAlreadyExist($input);
    }
}