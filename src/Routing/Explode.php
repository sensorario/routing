<?php

namespace Routing;

class Explode
{
    /** @return array<string> */
    public static function fromPath(string $path): array
    {
        $explosion = explode('/', $path);
        unset($explosion[0]);
        return $explosion;
    }
}
