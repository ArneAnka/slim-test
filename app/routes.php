<?php
use App\Middleware\GuestMiddleware;

/* Home */
$app->get('/', 'HomeController:index')->setName('home');

/* Basic */
$app->group('', function () {
	$this->get('/signin', 'AuthController:getSignin')->setName('signin');
	$this->post('/signin', 'AuthController:postSignin')->setName('signin.post');

	$this->get('/signup', 'AuthController:getSignup')->setName('signup');
	$this->post('/signup', 'AuthController:postSignup')->setName('signup.post');
})->add(new GuestMiddleware($container));

/* CRUD */
$app->get('/crud', 'CrudController:index')->setName('crud');
$app->post('/new', 'CrudController:postNewCrud')->setName('new');
$app->get('/edit/{id:[0-9]+}', 'CrudController:getEditCrud')->setName('view');
$app->put('/edit', 'CrudController:putEditCrud')->setName('edit');
$app->get('/delete/{id:[0-9]+}', 'CrudController:deleteCrud')->setName('delete');

// $app->get('/u/{username}', function ($request, $response) {
// //
// });