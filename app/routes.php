<?php
/**
 * throw new \Slim\Exception\NotFoundException($request, $response);
 * $this->get('/signout', 'AuthController:getSignOut')->setName('auth.signout');
 */

$app->get('/', 'HomeController:index')->setName('home');
$app->get('/crud', 'HomeController:crud')->setName('crud');
$app->post('/crud', 'HomeController:postCrud');
$app->get('/crud/{id:[0-9]+}', 'HomeController:deleteCrud')->setName('delete.post');

// $app->get('/u/{username}', function ($request, $response) {
// //
// });

// $app->get('/r/{section}', function ($request, $response) {
// //
// });

$app->get('/signin', 'AuthController:signin')->setName('signin');
$app->get('/signup', 'AuthController:signup')->setName('signup');