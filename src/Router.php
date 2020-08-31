<?php

class Router
{
    /** @var array<string> $statics */
    private array $statics = [];

    /** @var array<string> $dynamics */
    private array $dynamics = [];

    public function addRouteClass(string $class): void
    {
        $this->dynamics[] = $class;
    }

    public function addStaticRoute(string $route): void
    {
        $this->statics[] = $route;
    }

    public function match(string $route): RouterResponse
    {
        // static routes
        if (in_array($route, $this->statics)) {
            return RouterResponse::found([ 'static' => $route ], 'route');
        }

        // dynamic routes
        $needle = Explode::fromPath($route);
        foreach($this->dynamics as $route) {
            $haystack = Explode::fromPath($route);
            if (count($haystack) === count($needle)) {
                $combine = array_combine($haystack, $needle);
                $slug = Slug::from($combine);
                return RouterResponse::found($combine, $slug);
            }
        }

        return RouterResponse::notFound();
    }

    public function numberOfRoutes(): int
    {
        return count($this->statics)
            + count($this->dynamics);
    }
}
