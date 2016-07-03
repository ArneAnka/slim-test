<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator as v;

/**
 * throw new \Slim\Exception\NotFoundException($request, $response);
 */

$app->get('/', function (Request $request, Response $response) {
    return $this->view->render($response, 'home.twig');
})->setName('home');

$app->get('/test', function (Request $request, Response $response) {
	$query = $this->pdo->from('info');

	return $this->view->render($response, 'test.twig', ['querys' => $query]);
})->setName('test');

$app->post('/test', function (Request $request, Response $response){
	$data = $request->getParsedBody();

    /**
    * Check if the fields are valied
    */
    $validation = $this->validator->validate($request, [
        'text' => v::notEmpty(),
        'namn' => v::notEmpty()
                ]);

    /**
    * If the fields fail, then redirect back to test
    */
    if ($validation->failed()) 
    {
        $this->flash->addMessage('warning', 'Please insert some text.');
        return $response->withRedirect($this->router->pathFor('test'));
    }

	$query = $this->pdo->insertInto('info')->values($data)->execute();

	// Set flash message for next request
    $this->flash->addMessage('success', 'Success!');

	return $response->withRedirect($this->router->pathFor('test'));
});

$app->get('/test/{id:[0-9]+}', function (Request $request, Response $response, $args) {
	$query = $this->pdo->deleteFrom('info')->where('id', $args['id'])->execute();

	return $response->withRedirect($this->router->pathFor('test'));
})->setName('delete.post');