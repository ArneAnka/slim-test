<?php
/* Basic */
$app->get('/', 'HomeController:index')->setName('home');
$app->get('/crud', 'HomeController:crud')->setName('crud');
$app->get('/delete/{id:[0-9]+}', 'HomeController:deleteCrud')->setName('delete');
$app->get('/edit/{id:[0-9]+}', 'HomeController:getEditCrud')->setName('view');
$app->post('/new', 'HomeController:postNewCrud')->setName('new');
$app->put('/edit', 'HomeController:postEditCrud')->setName('edit');


/* Extras */
$app->get('/signin', 'AuthController:getSignin')->setName('signin');
$app->get('/signup', 'AuthController:getSignup')->setName('signup');
$app->post('/signup', 'AuthController:postSignup')->setName('signup.post');
$app->post('/signin', 'AuthController:postSignin')->setName('signin.post');

// $app->get('/u/{username}', function ($request, $response) {
// //
// });