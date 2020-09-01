<?php

namespace Action\Found;

class StaticContent extends Found
{
    public function get()
    {
        return [
            'static' => 'content',
            'success' => 'true',
        ];
    }
}
