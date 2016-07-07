<?php

namespace App\Models;

class UserModel
{
    public function doesEmailAlreadyExist($email){
        return $this->pdo->from('users')->where('user_email', $email)->fetch();
    }
}