<?php

class Explode
{
    public static function fromPath(string $path)
    {
        $explosion = explode('/', $path);
        unset($explosion[0]);
        return $explosion;
    }
}
