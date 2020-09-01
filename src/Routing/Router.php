<?php

namespace Routing;

class Router
{
    use \Logger\Logger;

    /** @var array<string> $statics */
    private array $statics = [];

    /** @var array<string> $dynamics */
    private array $dynamics = [];

    public function __construct()
    {
        static::debug('');
    }

    public function addRouteClass(string $class, string $className): void
    {
        $this->dynamics[$class] = $className;
    }

    public function addStaticRoute(string $route): void
    {
        $this->statics[] = $route;
    }

    public function match(string $route): RouterResponse
    {
        if ($route === '/') {
            return RouterResponse::found(
                [ 'static' => $route ],
                'home',
                '',
                \Action\Found\Home::class,
            );
        }

        static::debug('Try match with static routes');
        if (in_array($route, $this->statics)) {
            return RouterResponse::found(
                [ 'static' => $route ],
                'route',
                '',
                \Action\Found\StaticContent::class
            );
        }

        static::debug('Try match with dynamic routes');
        $needle = Explode::fromPath($route);

        foreach($this->dynamics as $routeName => $className) {
            $haystack = Explode::fromPath($routeName);
            if (count($haystack) === count($needle)) {

                static::debug('Match verified');
                $combine = array_combine($haystack, $needle);
                $slug = Slug::from($combine);

                static::debug('Querystring: ' . json_encode($_GET));

                static::debug('Build positive response');
                return RouterResponse::found(
                    $combine,
                    $slug,
                    $routeName,
                    $className
                );
            }

            static::debug('Match fialed');
        }

        static::debug('Build negative response');
        return RouterResponse::notFound();
    }

    public function numberOfRoutes(): int
    {
        return count($this->statics)
            + count($this->dynamics);
    }
}
