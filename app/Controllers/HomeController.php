<?php
namespace App\Controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator as v;


class HomeController extends Controller
{
	/**
	* @return view
	*/
    public function index(Request $request, Response $response)
    {
        return $this->view->render($response, 'home.twig');
    }

    /**
    * @return view
    */
    public function test(Request $request, Response $response){
		$query = $this->pdo->from('info');

		return $this->view->render($response, 'test.twig', ['querys' => $query]);
    }

    /**
    * Save a post to the DB
    * @return bool
    */
    public function postTest(Request $request, Response $response){
		$data = $request->getParsedBody();
		$values = ['namn' => $data['namn'],
				   'text' => $data['text'],
				   'ip_adress' => $request->getAttribute('ip_address')];

	    /**
	    * Check if the fields are valied
	    */
	    $validation = $this->validator->validate($request, [
	        'text' => v::notEmpty(),
	        'namn' => v::notEmpty()::noWhitespace()::alpha()
	                ]);

	    /**
	    * If the fields fail, then redirect back to test
	    */
	    if ($validation->failed()) 
	    {
	        $this->flash->addMessage('warning', 'Please fill all fields.');
	        return $response->withRedirect($this->router->pathFor('test'));
	    }

		$query = $this->pdo->insertInto('info')->values($values)->execute();

		// Set flash message fon success
	    $this->flash->addMessage('success', 'Success!');
	    unset($_SESSION['old']);

		return $response->withRedirect($this->router->pathFor('test'));
    }

    /**
    * Delete a post
    * @param $args['id']
    *
    * @return bool
    */
    public function deleteTest(Request $request, Response $response, $args){
		$query = $this->pdo->deleteFrom('info')->where('id', $args['id'])->execute();
		return $response->withRedirect($this->router->pathFor('test'));
    }
}