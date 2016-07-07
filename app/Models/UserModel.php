<?php
namespace App\Models;

class UserModel
{
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function doesEmailAlreadyExist($input){
        return $this->pdo->from('users')->where('user_email', $input)->fetch();
    }
}