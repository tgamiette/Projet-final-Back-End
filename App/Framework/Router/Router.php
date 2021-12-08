<?php

namespace App\Framework\Router;

use App\Controller\ErrorController;

class Router
{
    public function getController()
    {
        $xml = new \DOMDocument();
        $xml->load(__DIR__.'/routes.xml');
        $routes = $xml->getElementsByTagName('route');

        if (isset($_GET['p'])) {
            $path = htmlspecialchars($_GET['p']);
        } else {
            $path = "/";
        }

        foreach ($routes as $route) {
            if ($path === $route->getAttribute('p')) {
                $controllerClass = 'App\\Controller\\'.$route->getAttribute('controller');
                $action = $route->getAttribute('action');
                $params = [];

                if ($route->hasAttribute('params')) {
                    $keys = explode(',', $route->getAttribute('params'));
                    foreach ($keys as $key) {
                        $params[$key] = $_GET['params'];
                    }
                }
                return new $controllerClass($action, $params);
            }
        }
        $error = new ErrorController();
        $error->render('404Error.php', [], '404 Error');
    }
}