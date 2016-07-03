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
				   'created_at' => (new \DateTime())->format('Y-m-d H:i:s'),
				   'ip_adress' => $request->getAttribute('ip_address')];

	    /**
	    * Check if the fields are valied
	    */
	    $validation = $this->validator->validate($request, [
	        'text' => v::notEmpty(),
	        'namn' => v::notEmpty()::alpha()
	                ]);

	    /**
	    * If the fields fail, then redirect back to test
	    */
	    if ($validation->failed()) 
	    {
	        $this->flash->addMessage('warning', 'Please fill all fields.');
	        return $response->withRedirect($this->router->pathFor('test'));
	    }

	    /**
	    * If validation success, then insert $values ($data) to DB
	    */
		$query = $this->pdo->insertInto('info')->values($values)->execute();

		// Set flash message for success, and unset the input fields
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