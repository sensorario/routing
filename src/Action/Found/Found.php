<?php

namespace Action\Found;

abstract class Found
{
    use \Logger\Logger;

    protected \Routing\RouterResponse $response;

    public function __construct(
        \Routing\RouterResponse $response
    ) {
        static::debug(static::class);
        $this->response = $response;
    }

    public function complete()
    {
        static::debug('request complete event');
    }
}
