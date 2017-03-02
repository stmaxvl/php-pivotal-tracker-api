<?php

namespace PivotalTracker\HttpClient\Plugin;

use PivotalTracker\Client;
use PivotalTracker\Exception\RuntimeException;
use Http\Client\Common\Plugin;
use Psr\Http\Message\RequestInterface;

/**
 * Add authentication to the request.
 *
 */
class Authentication implements Plugin
{
    private $tokenOrLogin;
    private $password;
    private $method;

    public function __construct($tokenOrLogin, $password = null, $method)
    {
        $this->tokenOrLogin = $tokenOrLogin;
        $this->password = $password;
        $this->method = $method;
    }

    /**
     * {@inheritdoc}
     */
    public function handleRequest(RequestInterface $request, callable $next, callable $first)
    {
        switch ($this->method) {

            case Client::AUTH_API_TOKEN:
                $request = $request->withHeader('X-TrackerToken', sprintf('%s', $this->tokenOrLogin));
                break;

            default:
                throw new RuntimeException(sprintf('%s not yet implemented', $this->method));
                break;
        }

        return $next($request);
    }
}
