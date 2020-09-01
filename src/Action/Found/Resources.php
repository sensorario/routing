<?php

namespace Action\Found;

use Routing\RouterResponse;

class Resources extends Found
{
    public function get()
    {
        return [
            'path' => $this->response->routeClass(),
            'resource' => $this->response->get(':resource'),
        ];
    }
}
