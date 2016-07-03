<?php
/**
 * throw new \Slim\Exception\NotFoundException($request, $response);
 * $this->get('/signout', 'AuthController:getSignOut')->setName('auth.signout');
 */

$app->get('/', 'HomeController:index')->setName('home');
$app->get('/test', 'HomeController:test')->setName('test');
$app->post('/test', 'HomeController:postTest');
$app->get('/test/{id:[0-9]+}', 'HomeController:deleteTest')->setName('delete.post');