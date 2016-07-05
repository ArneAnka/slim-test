<?php
namespace App\Controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class HomeController extends Controller
{
	/**
	* @return view
	*/
    public function index(Request $request, Response $response)
    {
        return $this->view->render($response, 'home.twig');
    }

}