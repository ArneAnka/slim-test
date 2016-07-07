<?php
use App\Middleware\GuestMiddleware;
use App\Middleware\AuthMiddleware;

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

/* Bara coppy paste */
$app->group('', function () {
    $this->get('/signout', 'AuthController:getSignOut')->setName('auth.signout');

    $this->get('/dashboard', 'AuthController:dashboard')->setName('dashboard');

    $this->get('/password/change', 'PasswordController:getChangePassword')->setName('auth.password.change');
    $this->post('/password/change', 'PasswordController:postChangePassword');

    $this->get('/notes', 'NoteController:index')->setName('notes');
    $this->post('/notes', 'NoteController:newNote')->setName('new.note');
    
    $this->get('/notes/{note_id:[0-9]+}', 'NoteController:getEditNote');
    $this->put('/notes/{note_id:[0-9]+}', 'NoteController:postEditNote')->setName('edit.note');
    $this->get('/notes/deleteNote/{note_id:[0-9]+}', 'NoteController:deleteNote')->setName('delete.note');
})->add(new AuthMiddleware($container));

// $app->get('/u/{username}', function ($request, $response) {
// 	// Have a look at another users profile.
// });