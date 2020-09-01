<?php

namespace Logger;

trait Logger
{
    public static function debug($message)
    {
        $filePath = __DIR__ . '/../../debug.log';

        $line = $message == ''
            ? "\n"
            : static::class . ' >>> ' . $message . "\n";

        file_put_contents($filePath, $line);
    }
}
