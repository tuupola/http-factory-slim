<?php

namespace Http\Factory\Slim;

use Interop\Http\Factory\ServerRequestFactoryInterface;
use Psr\Http\Message\UriInterface;
use Slim\Http\Environment;
use Slim\Http\Headers;
use Slim\Http\Request as ServerRequest;

class ServerRequestFactory implements ServerRequestFactoryInterface
{
    public function createServerRequest($method, $uri)
    {
        if (!$uri instanceof UriInterface) {
            $uri = (new UriFactory())->createUri($uri);
        }

        $headers = new Headers([]);
        $cookies = [];
        $serverParams = [];
        $body = (new StreamFactory())->createStream();

        return new ServerRequest($method, $uri, $headers, $cookies, $serverParams, $body);
    }

    public function createServerRequestFromArray(array $server)
    {
        if (!isset($server['REQUEST_METHOD'])) {
            throw new \InvalidArgumentException('Cannot determine HTTP method');
        }

        // Prevent the factory from reading the global POST
        $post = $_POST;
        $_POST = [];

        $environment = new Environment($server);
        $request = ServerRequest::createFromEnvironment($environment);

        // Restore POST
        $_POST = $post;
        unset($post);

        return $request;
    }
}
