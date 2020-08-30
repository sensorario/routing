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
