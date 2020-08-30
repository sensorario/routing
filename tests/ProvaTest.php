<?php

class Slug
{
    public static function from(array $combine): string
    {
        $keys = array_keys($combine);
        $filteredKeys = array_map(function ($item) {
            return str_replace(':', '', $item);
        }, $keys);
        return join('-', $filteredKeys);
    }
}

class Explode
{
    public static function fromPath(string $path)
    {
        $explosion = explode('/', $path);
        unset($explosion[0]);
        return $explosion;
    }
}

class RouterRespone
{
    private array $resource;

    private bool $found = true;

    private string $name;

    private function __construct(
        array $resource,
        string $name
    ) {
        $this->resource = $resource;
        $this->name = $name;
    }

    public static function notFound()
    {
        return new self([], 'unknown');
    }

    public static function found(
        array $url,
        string $name
    ) {
        return new self(
            $url,
            $name
        );
    }

    public function routeFound()
    {
        return count($this->resource) != 0;
    }

    public function get(string $name)
    {
        return $this->resource[$name];
    }

    public function routeName()
    {
        return $this->name;
    }

    public function isStatic(): bool
    {
        return isset($this->resource['static']);
    }
}

class Router
{
    private $statics = [];

    private $dynamics = [];

    public function addRouteClass(string $class)
    {
        $this->dynamics[] = $class;
    }

    public function addStaticRoute(string $route)
    {
        $this->statics[] = $route;
    }

    public function match(string $route): RouterRespone
    {
        // static routes
        if (in_array($route, $this->statics)) {
            return RouterRespone::found([ 'static' => $route ], 'route');
        }

        // dynamic routes
        $needle = Explode::fromPath($route);
        foreach($this->dynamics as $route) {
            $haystack = Explode::fromPath($route);
            if (count($haystack) === count($needle)) {
                $combine = array_combine($haystack, $needle);
                $slug = Slug::from($combine);
                return RouterRespone::found($combine, $slug);
            }
        }

        return RouterRespone::notFound();
    }

    public function numberOfRoutes()
    {
        return count($this->statics)
            + count($this->dynamics);
    }
}

class ProvaTest extends PHPUnit\Framework\TestCase
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
