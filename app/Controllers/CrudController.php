<?php
namespace App\Controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator as v;

class CrudController extends Controller
{
    /**
    * @return view
    */
    public function index(Request $request, Response $response){
		$query = $this->pdo->from('notes')->where('soft_delete', null)->orderBy('created_at DESC');

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
	    * Check if the fields from form/post are valid.
	    * Pass custom error message as a third argument to validate()
	    */
	    $validation = $this->validator->validate($request, [
	        'namn' => v::notEmpty()::alpha()->setName('Name'),
	       	'text' => v::notEmpty()->setName('Textfield')],
	         ['alpha' => '{{name}} must contain only letters (a-z)',
	         'notEmpty' => '{{name}} cannot be empty']);
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
	    $this->flash->addMessage('success', 'Success! Post inserted!');
	    unset($_SESSION['old']);

		return $response->withRedirect($this->router->pathFor('crud'));
    }

    /**
    * Save a edit post to the DB
    * @return bool
    */
    public function putEditCrud(Request $request, Response $response){
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

	        return $response->withRedirect($this->router->pathFor('view', ['id' => $data['note_id']]));
	    }

	    /**
	    * Check if the post actually has been alterd
	    */
	    if($this->pdo->from('notes')->where('note_id', $data['note_id'])->fetch('text') == $data['text']){
	    	$this->flash->addMessage('warning', 'You dident alter anything...');

	    	return $response->withRedirect($this->router->pathFor('view', ['id' => $data['note_id']]));
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
    	$this->flash->addMessage('info', 'Note deleted');
    	
    	// Dont use soft delete? Uncomment line under, and comment line under.
		// $query = $this->pdo->deleteFrom('notes')->where('note_id', $args['id'])->execute();
		$query = $this->pdo->update('notes')->set('soft_delete', 1)->where('note_id', $args['id'])->execute();

		return $response->withRedirect($this->router->pathFor('crud'));
    }
}