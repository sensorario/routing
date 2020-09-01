<?php

require __DIR__ . '/../vendor/autoload.php';

// Router
$router = new Routing\Router();
$router->addStaticRoute('/credits', Action\Found\StaticContent::class);
$router->addRouteClass('/:resource', Action\Found\Resources::class);
$router->addRouteClass('/:resource/:id', Action\Found\Resource::class);
$router->addRouteClass('/:resource/:id/:related', Action\found\Related::class);
$routerResponse = $router->match($_SERVER['REQUEST_URI']);
$routeName = $routerResponse->routeName();

// Kernel
if (!$routerResponse->classExists()) {
    http_response_code(500);
    die(json_encode([
        'code' => 500,
        'message' => 'Class '. $routeName.' not found',
    ]));
}

// Controller
$className = $routerResponse->className();
$controller = new $className($routerResponse);

// http request ...
$response = $controller->{strtolower($_SERVER['REQUEST_METHOD'])}();

// send complete event
$controller->complete();

// Render
http_response_code(200);
die(json_encode($response));
