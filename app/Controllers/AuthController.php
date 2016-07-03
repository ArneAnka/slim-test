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
    public function getSignup(Request $request, Response $response)
    {
        return $this->view->render($response, 'auth/signup.twig');
    }

	/**
	* @return Sign-in view
	*/
    public function getSignin(Request $request, Response $response)
    {
        return $this->view->render($response, 'auth/signin.twig');
    }

    /**
    * Sign the user in with the provided credentials.
    *
    * @param string $user_email
    * @param string $user_password
    * @param string reg
    *
    * @return bool
    */
    public function postSignIn($request, $response)
    {
        /**
        * Check if the fields are valied. op is a hidden field. To prevent bots
        */
        $validation = $this->validator->validate($request, [
            'user_email' => v::noWhitespace()->notEmpty()->email(),
            'user_password' => v::noWhitespace()->notEmpty(),
            'op' => v::equals('reg'),
        ]);

        /**
        * If the fields fail, then redirect back to signup
        */
        if ($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('signin'));
        }

        $auth = $this->auth->attempt(
            $request->getParam('user_email'),
            $request->getParam('user_password')
        );

        if (!$auth) {
            $this->flash->addMessage('error', 'Could not sign you in with those details.');
            return $response->withRedirect($this->router->pathFor('signin'));
        }

        // If Auth successfull, then redirect to choosen location
        return $response->withRedirect($this->router->pathFor('dashboard'));
    }

    /**
    * Register a new user
    *
    * @param string $user_name
    * @param string $user_email
    * @param string $user_password
    * @param string reg
    *
    * @return bool
    */
    public function postSignUp($request, $response)
    {
        $data = $request->getParsedBody();
        /**
        * Check if the fields are valied. op is a hidden field, to prevent bots
        */
        $validation = $this->validator->validate($request, [
            'user_name' => v::noWhitespace()->notEmpty()->alpha(),
            'user_email' => v::noWhitespace()->notEmpty()->email(),
            'user_password' => v::noWhitespace()->notEmpty(),
            'op' => v::equals('reg'),
        ]);

        if($this->doesEmailAlreadyExist($data['user_email'])){
            return false;
        }

        /**
        * If the fields fail, then redirect back to signup
        */
        if ($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('signup'));
        }

        /**
        * If validation is OK, then continue with registration.
        */
        $user = [
            'user_email' => $data['user_email'],
            'user_name' => $data['user_name'],
            'user_password_hash' => password_hash($data['user_password'], PASSWORD_DEFAULT),
        ];

        $query = $this->pdo->insertInto('users')->values($user)->execute();

        if($this->auth->attempt($user->user_email, $request->getParam('user_password'))){
            /** Add a flas message that everything went ok **/
            $this->flash->addMessage('success', 'You have been signed up!');

            /** On success registration, redirect to dashboard */
            return $response->withRedirect($this->router->pathFor('home'));
        }
        return false;
    }

    /**
    * Does the email already exist?
    * @param $user_email
    *
    * @return bool
    */
    public function doesEmailAlreadyExist($user_email){
        $query = $this->pdo->from('users')->where('user_email', $user_email);
        if($query->rowCount() == 1){
            return false;
        }
        return true;
        }

    /**
    * @return Log out
    */
        public function getSignOut($request, $response)
    {
        $this->auth->logout();

        return $response->withRedirect($this->router->pathFor('home'));
    }
   
}