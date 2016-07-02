<?php
// Start PHP session
session_start();

require 'vendor/autoload.php';

/**
* configuration
*/
$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$config['db']['host']   = "localhost";
$config['db']['user']   = "user";
$config['db']['pass']   = "password";
// $config['db']['database'] = "exampleapp"; //For mariaDB or mySQL: dbname. For sqlite: file path
$config['db']['database'] = __DIR__ . '/../resources/db.db';

/**
* Create the Slim object
*/
$app = new \Slim\App(["settings" => $config]);

$container = $app->getContainer();

/**
* FluentPDO Database DI
* For MariaDB or mysql, you need username and password
*/
$container['pdo'] = function ($container) {
    $settings = $container->get('settings');
    $dsn = 'sqlite:' . $settings['db']['database'];
    $pdo = new PDO($dsn);
    $fpdo = new FluentPDO($pdo);
    return $fpdo;
};

// Register provider
$container['flash'] = function ($c) {
    return new \Slim\Flash\Messages();
};

$container['validator'] = function ($c) {
    return new \App\Validation\Validator;
};
$app->add(new App\Middleware\OldInputMiddleware($container));
$app->add(new App\Middleware\ValidationErrorsMiddleware($container));


/**
* Twig View DI
*/
$container['view'] = function ($c) {
    $view = new \Slim\Views\Twig(__DIR__ . '/../resources/views', [
        'cache' => 'cache',
        'debug' => true,
        'auto_reload' => true
    ]);

    // Instantiate and add Slim specific extension
    $view->addExtension(new \Slim\Views\TwigExtension(
        $c->router,
        $c->request->getUri(),
        $c->flash
    ));
    $view->addExtension(new Twig_Extension_Debug());
    $view['flash'] = $c->flash;

    return $view;
};

// Register routes
require __DIR__ . '/../app/routes.php';

$app->run();