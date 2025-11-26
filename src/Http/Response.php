<?php

declare(strict_types=1);

namespace Bright\Hibp\Http;

use GuzzleHttp\Psr7\Response as GuzzleResponse;

/**
 * Laravel-like HTTP response wrapper.
 */
class Response
{
    protected GuzzleResponse $response;

    /**
     * Create a new Response instance.
     */
    public function __construct(GuzzleResponse $response)
    {
        $this->response = $response;
    }

    /**
     * Get the HTTP status code.
     */
    public function status(): int
    {
        return $this->response->getStatusCode();
    }

    /**
     * Get the response body as a string.
     */
    public function body(): string
    {
        return (string) $this->response->getBody();
    }

    /**
     * Decode the JSON response body into an array.
     *
     * @return array<mixed>
     */
    public function json(): array
    {
        $body = $this->body();

        if (empty($body)) {
            return [];
        }

        /** @var array<mixed> */
        return json_decode($body, true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Determine if the response status code is successful (2xx).
     */
    public function successful(): bool
    {
        $status = $this->status();

        return $status >= 200 && $status < 300;
    }

    /**
     * Determine if the response failed (non 2xx).
     */
    public function failed(): bool
    {
        return ! $this->successful();
    }

    /**
     * Get a specific header value.
     *
     * @param  mixed  $default
     * @return mixed
     */
    public function header(string $key, $default = null)
    {
        $values = $this->response->getHeader($key);

        return $values[0] ?? $default;
    }
}
