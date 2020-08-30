<?php

class RouterResponse
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

    public static function notFound(): self
    {
        return new self([], 'unknown');
    }

    public static function found(
        array $url,
        string $name
    ): self {
        return new self(
            $url,
            $name
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
}
