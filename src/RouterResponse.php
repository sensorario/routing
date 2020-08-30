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
