<?php

class RouterTest extends PHPUnit\Framework\TestCase
{
    /** @test */
    public function shouldTranslateRouteInRouterResponeObject()
    {
        $routeClass = '/:resource';

        $router = new Router();
        $router->addRouteClass($routeClass);

        $route = '/onlyResource';
        $response = $router->match($route);

        $this->assertEquals(true, $response->routeFound());
        $this->assertEquals('onlyResource', $response->get(':resource'));
    }

    /** @test */
    public function shouldNotTranslateRouteThatDoesntRouterRespone()
    {
        $routeClass = '/:resource';

        $router = new Router();
        $router->addRouteClass($routeClass);

        $route = '/onlyResource/42';
        $response = $router->match($route);

        $this->assertEquals(false, $response->routeFound());
    }

    /** @test */
    public function shouldTranslateEachItems()
    {
        $routeClass = '/:resource/:id/:foo';

        $router = new Router();
        $router->addRouteClass($routeClass);

        $route = '/person/42/something';
        $response = $router->match($route);

        $this->assertEquals('something', $response->get(':foo'));
        $this->assertEquals('42', $response->get(':id'));
        $this->assertEquals('resource-id-foo', $response->routeName());
    }

    /** @test */
    public function shouldCollectAllRoutes()
    {
        $router = new Router();
        $router->addRouteClass('/:resource');
        $router->addRouteClass('/:resource/:id');
        $router->addStaticRoute('/ciao/mondo');
        $this->assertEquals(3, $router->numberOfRoutes());
    }

    /** @test */
    public function shouldCheckEachRouteInRouter()
    {
        $router = new Router();
        $router->addRouteClass('/:resource');
        $router->addRouteClass('/:resource/:id');

        $route = '/onlyResource/42';
        $response = $router->match($route);

        $this->assertEquals(true, $response->routeFound());
    }

    /** @test */
    public function shouldAssignNameToEachRoute()
    {
        $router = new Router();
        $router->addRouteClass('/:resource');
        $router->addRouteClass('/:resource/:id');

        $route = '/onlyResource/42';
        $response = $router->match($route);

        $this->assertEquals('resource-id', $response->routeName());
        $this->assertEquals(false, $response->isStatic());
    }

    /** @test */
    public function shouldAcceptAlsoStaticRoute()
    {
        $router = new Router();
        $router->addStaticRoute('/foo/bar');

        $route = '/foo/bar';
        $response = $router->match($route);

        $this->assertEquals(true, $response->routeFound());
        $this->assertEquals(true, $response->isStatic());
    }
}
