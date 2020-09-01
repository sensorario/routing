<?php

namespace Action\Found;

class Related extends Found
{
    public function get()
    {
        return [
            'path' => $this->response->routeClass(),
            'resource' => $this->response->get(':resource'),
            'id' => $this->response->get(':id'),
            'related' => $this->response->get(':related'),
        ];
    }
}
