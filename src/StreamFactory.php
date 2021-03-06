<?php

namespace Http\Factory\Slim;

use Interop\Http\Factory\StreamFactoryInterface;
use Slim\Http\Stream;

class StreamFactory implements StreamFactoryInterface
{
    public function createStream($content = '')
    {
        $resource = fopen('php://temp', 'r+');
        fwrite($resource, $content);
        rewind($resource);

        return $this->createStreamFromResource($resource);
    }

    public function createStreamFromFile($file, $mode = 'r')
    {
        $resource = fopen($file, $mode);

        return $this->createStreamFromResource($resource);
    }

    public function createStreamFromResource($resource)
    {
        return new Stream($resource);
    }
}
