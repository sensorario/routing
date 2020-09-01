<?php

namespace Routing;

class RouterResponse
{
    /** @var array<string> $resource */
    private array $resource;

    private bool $found = true;

    private string $name;

    private string $routeClass;

    private string $className;

    /** @param array<string> $resource */
    private function __construct(
        array $resource,
        string $name,
        string $routeClass,
        string $className
    ) {
        $this->resource = $resource;
        $this->name = $name;
        $this->routeClass = $routeClass;
        $this->className = $className;
    }

    public static function notFound(): self
    {
        return new self(
            [],
            'unknown',
            '',
            \Action\NotFound::class
        );
    }

    /** @param array<string> $url */
    public static function found(
        array $url,
        string $name,
        string $routeClass,
        string $className
    ): self {
        return new self(
            $url,
            $name,
            $routeClass,
            $className
        );
    }

    public function routeFound(): bool
    {
        return count($this->resource) != 0;
    }

    public function get(string $name): string
    {
        return $this->resource[$name];
    }

    public function routeName(): string
    {
        return $this->name;
    }

    public function isStatic(): bool
    {
        return isset($this->resource['static']);
    }

    public function routeClass(): string
    {
        return $this->routeClass;
    }

    public function classExists(): bool
    {
        return null !== $this->className;
    }

    public function className(): string
    {
        return $this->className;
    }
}
