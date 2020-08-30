<?php

class Explode
{
    public static function fromPath(string $path): array
    {
        $explosion = explode('/', $path);
        unset($explosion[0]);
        return $explosion;
    }
}
