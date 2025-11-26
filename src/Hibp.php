<?php

namespace Bright\Hibp;

use Bright\Hibp\Http\Response;

class Hibp
{
    public const HEADER_AUTH_NAME = 'hibp-api-key';

    public const HEADER_USER_AGENT = 'User-Agent';

    /**
     * Create a new http client factory
     */
    public static function factory(): Factory
    {
        return new Factory;
    }

    /**
     * Create a new http client factory + hibp client
     */
    public static function make(string $apiKey): Client
    {
        return (new Factory)->withApiKey($apiKey)->make();
    }

    /**
     * Create a Response instance.
     *
     * @param  mixed  $body
     * @param  (string|string[])[]  $headers
     */
    public static function response($body = null, int $status = 200, array $headers = []): Response
    {
        if (! ($body instanceof \GuzzleHttp\Psr7\Response)) {
            $body = is_array($body) ? json_encode($body) : $body;
            // @phpstan-ignore-next-line
            $body = new \GuzzleHttp\Psr7\Response($status, $headers, $body);
        }

        /** @var \GuzzleHttp\Psr7\Response $body */
        return new Response($body);
    }

    /**
     * Create fake response.
     *
     * @param  mixed  $body
     */
    public static function fake(string $path = '*', $body = []): void
    {
        Factory::clearFakes();
        Factory::fake($path, $body);
    }
}
