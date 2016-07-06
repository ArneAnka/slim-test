<?php
/**
* To help the built-in PHP dev server, check if the request was actually for
* something which should probably be served as a static file
**/
if(PHP_SAPI === 'cli-server' && $_SERVER['SCRIPT_FILENAME'] !== __FILE__) {
    return false;
}

// Start PHP session
session_start();

require 'vendor/autoload.php';

/**
* configuration
*/
$config['determineRouteBeforeAppMiddleware'] = false;
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
$container['csrf'] = function ($c) {
    return new \Slim\Csrf\Guard;
};

/**
* Middleware
*/
$app->add(new App\Middleware\OldInputMiddleware($container));
$app->add(new App\Middleware\ValidationErrorsMiddleware($container));
$checkProxyHeaders = true; // Note: Never trust the IP address for security processes!
$trustedProxies = ['10.0.0.1', '10.0.0.2']; // Note: Never trust the IP address for security processes!
$app->add(new RKA\Middleware\IpAddress($checkProxyHeaders, $trustedProxies));
$app->add(new App\Middleware\CsrfViewMiddleware($container));
$app->add($container->get('csrf'));


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
    $view->addExtension(new App\TwigExtension\diffForHumans());
    $view->addExtension(new \Slim\Views\TwigExtension(
        $c->router,
        $c->request->getUri(),
        $c->flash
    ));
    $view->addExtension(new Twig_Extension_Debug());
    $view['flash'] = $c->flash;

    return $view;
};

/**
* Attach controllers to $container
*/
$container['HomeController'] = function ($container) {
    return new \App\Controllers\HomeController($container);
};
$container['CrudController'] = function ($container) {
    return new \App\Controllers\CrudController($container);
};
$container['AuthController'] = function ($container) {
    return new \App\Controllers\AuthController($container);
};

// Register routes
require __DIR__ . '/../app/routes.php';

$app->run();