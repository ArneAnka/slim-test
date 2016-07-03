<?php
namespace App\Controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator as v;

class AuthController extends Controller
{
	/**
	* @return Sign-up view
	*/
    public function signup(Request $request, Response $response)
    {
        return $this->view->render($response, 'auth/signup.twig');
    }

	/**
	* @return Sign-in view
	*/
    public function signin(Request $request, Response $response)
    {
        return $this->view->render($response, 'auth/signin.twig');
    }
   
}