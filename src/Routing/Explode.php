<?php

namespace Routing;

use Logger\Logger;

class Explode
{
    use Logger;

    /** @return array<string> */
    public static function fromPath(string $fullPath): array
    {
        $path = explode('?', $fullPath);
        $explosion = explode('/', current($path));
        unset($explosion[0]);
        return $explosion;
    }
}
