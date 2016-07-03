<?php

namespace App\Middleware;

class OldInputMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
    		/**
    		* To prevent `Notice: Undefined index: old in app/Middleware/OldInputMiddleware.php on line 16`
    		*/
	    	if(empty($_SESSION['old'])){
	    		$_SESSION['old'] = true;
	    	}

            /**
            * Is this a good way of doing things?
            * Insted of the previous solution.
            */
            if(null !== $request->getParams()){
                $this->container->view['old'] = $_SESSION['old'];
                $_SESSION['old'] = $request->getParams();
            }


        $response = $next($request, $response);
        return $response;
    }
}
