<?php

namespace Action\Found;

use Routing\RouterResponse;

class Resource extends Found
{
    public function get()
    {
        return [
            'path' => $this->response->routeClass(),
            'resource' => $this->response->get(':resource'),
            'id' => $this->response->get(':id'),
        ];
    }
}
