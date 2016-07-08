<?php
namespace App\Models;

class UserModel
{
	private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function doesEmailAlreadyExist($email)
    {
    	return $this->pdo->from('user')->where('user_email', $email)->fetch('user_email');
    }

}