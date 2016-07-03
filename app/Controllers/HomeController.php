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
    public function crud(Request $request, Response $response){
		$query = $this->pdo->from('notes')->orderBy('created_at DESC');

		return $this->view->render($response, 'crud.twig', ['querys' => $query]);
    }

    /**
    * @return view with post
    */
    public function getEditCrud(Request $request, Response $response, $args){
    	$query = $this->pdo->from('notes')->where('note_id', $args['id'])->fetch();
    	return $this->view->render($response, 'edit-crud.twig', ['query' => $query]);
    }

    /**
    * Save a post to the DB
    * @return bool
    */
    public function postNewCrud(Request $request, Response $response){
		$data = $request->getParsedBody();
		$values = ['user_namn' => $data['namn'],
				   'text' => $data['text'],
				   'created_at' => (new \DateTime())->format('Y-m-d H:i:s'),
				   'ip_adress' => $request->getAttribute('ip_address')];

	    /**
	    * Check if the fields from form/post are valid
	    */
	    $validation = $this->validator->validate($request, [
	        'text' => v::notEmpty(),
	        'namn' => v::notEmpty()::alpha()
	                ]);

	    /**
	    * If the fields fail, then redirect back to crud
	    */
	    if ($validation->failed()) 
	    {
	        $this->flash->addMessage('warning', 'Please fill all fields.');
	        return $response->withRedirect($this->router->pathFor('crud'));
	    }

	    /**
	    * If validation success, then insert $values ($data) to DB
	    */
		$query = $this->pdo->insertInto('notes')->values($values)->execute();

		// Set flash message for success, and unset the input fields
	    $this->flash->addMessage('success', 'Success!');
	    unset($_SESSION['old']);

		return $response->withRedirect($this->router->pathFor('crud'));
    }

    /**
    * Save a edit post to the DB
    * @return bool
    */
    public function postEditCrud(Request $request, Response $response){
		$data = $request->getParsedBody();
		$values = ['text' => $data['text'],
				   'updated_at' => (new \DateTime())->format('Y-m-d H:i:s'),
				   'ip_adress' => $request->getAttribute('ip_address')];

	    /**
	    * Check if the fields from form/post are valid
	    */
	    $validation = $this->validator->validate($request, [
	        'text' => v::notEmpty()
	                ]);

	    /**
	    * If the fields fail, then redirect back to crud
	    */
	    if ($validation->failed()) 
	    {
	        $this->flash->addMessage('warning', 'Please fill all fields.');
	        return $response->withRedirect($this->router->pathFor('crud'));
	    }

	    /**
	    * If validation success, then insert $values ($data) to DB
	    */
		$query = $this->pdo->update('notes')->set($values)->where('note_id', $data['note_id'])->execute();

		// Set flash message for success, and unset the input fields
	    $this->flash->addMessage('success', 'Note edited!');
	    unset($_SESSION['old']);

		return $response->withRedirect($this->router->pathFor('crud'));
    }

    /**
    * Delete a post
    * @param $args['id']
    *
    * @return bool
    */
    public function deleteCrud(Request $request, Response $response, $args){
		$query = $this->pdo->deleteFrom('notes')->where('note_id', $args['id'])->execute();
		return $response->withRedirect($this->router->pathFor('crud'));
    }
}