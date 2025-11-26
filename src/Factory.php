<?php

declare(strict_types=1);

namespace Bright\Hibp;

use Bright\Hibp\Http\Response;
use Bright\Hibp\Support\Support;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\RequestOptions;

class Factory
{
    /**
     * Hibp api base uri
     */
    protected ?string $apiKey = null;

    /**
     * Hibp api base uri
     */
    protected string $baseUri = 'https://haveibeenpwned.com/api/v3';

    /**
     * Get the user agent to sent to tha request
     */
    protected string $userAgent = 'Hibp-php-api/1.0';

    /**
     * The underlying Guzzle HTTP client.
     */
    protected GuzzleClient $client;

    /**
     * Request headers.
     *
     * @var array<string, mixed>
     */
    protected array $headers = [];

    /**
     * Extra Guzzle options.
     *
     * @var array<string, mixed>
     */
    protected array $options = [];

    /**
     * Query parameters.
     *
     * @var array<string, mixed>
     */
    protected array $query = [];

    /**
     * Middleware stack.
     *
     * @var array<int, callable>
     */
    protected array $middleware = [];

    /**
     * Custom handler override (optional).
     *
     * @var callable|null
     */
    protected $handler = null;

    /**
     * Stored fake responses for testing.
     *
     * @var array<string, Response>
     */
    protected static array $fakes = [];

    /**
     * Create a new HTTP client instance.
     *
     * @param  array  $options  Guzzle client options
     */
    public function __construct(array $options = [])
    {
        $this->client = new GuzzleClient($options);
    }

    /**
     * Add request headers.
     *
     * @param  array<string, mixed>  $headers
     */
    public function withHeaders(array $headers): self
    {
        $this->headers = array_merge($this->headers, $headers);

        return $this;
    }

    /**
     * Set api key for the request.
     */
    public function withApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Set api key for the request.
     */
    public function withBaseUri(string $uri): self
    {
        $this->baseUri = $uri;

        return $this;
    }

    /**
     * Set api key for the request.
     */
    public function withUserAgent(string $userAgent): self
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    /**
     * Add a new http client.
     */
    public function withHttpClient(GuzzleClient $client): self
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Add Guzzle options to the request.
     *
     * @see https://docs.guzzlephp.org/en/stable/request-options.html
     *
     * @param  array<string, mixed>  $options
     */
    public function withOptions(array $options): self
    {
        $this->options = array_merge($this->options, $options);

        return $this;
    }

    /**
     * Add query parameters.
     *
     * @param  array<string, mixed>  $query
     */
    public function withQueryParameters(array $query): self
    {
        $this->query = array_merge($this->query, $query);

        return $this;
    }

    /**
     * Set the requst connect open timeout.
     */
    public function withTimeout(float $seconds): self
    {
        $this->options[RequestOptions::TIMEOUT] = $seconds;

        return $this;
    }

    /**
     * Add a middleware to the stack.
     */
    public function withMiddleware(callable $middleware): self
    {
        $this->middleware[] = $middleware;

        return $this;
    }

    /**
     * Override the Guzzle handler.
     */
    public function withHandler(?callable $handler): self
    {
        $this->handler = $handler;

        return $this;
    }

    /**
     * Build the final handler stack.
     */
    protected function buildHandlerStack(): HandlerStack
    {
        $stack = $this->handler ? HandlerStack::create($this->handler) : HandlerStack::create();

        foreach ($this->middleware as $mw) {
            $stack->push($mw);
        }

        return $stack;
    }

    /**
     * Perform a GET request.
     */
    public function get(string $url, array $query = []): Response
    {
        return $this->send('GET', $url, ['query' => array_merge($this->query, $query)]);
    }

    /**
     * Send the HTTP request.
     */
    protected function send(string $method, string $url, array $options = []): Response
    {

        // Fake response support
        foreach (static::$fakes as $fakeUrl => $fakeResponse) {
            if (str_contains($url, $fakeUrl) || $fakeUrl === '*') {
                return $fakeResponse;
            }
        }

        // Merge global options + request-specific options
        $options = array_merge($this->options, $options);

        $options['headers'] = array_merge(
            [
                Hibp::HEADER_AUTH_NAME => $this->apiKey,
                Hibp::HEADER_USER_AGENT => $this->userAgent,
            ], $this->headers);

        // Apply middleware handler stack
        $options['handler'] = $this->buildHandlerStack();

        try {
            $uri = str_contains($url, 'http') ? $url : Support::joinUri($this->baseUri, $url);

            /** @var \GuzzleHttp\Psr7\Response $res */
            $res = $this->client->request($method, $uri, $options);

            return new Response($res);
            //
        } catch (GuzzleException $e) {

            $status = $e->getCode() ?: 500;
            $body = $e->getMessage();

            return new Response(new \GuzzleHttp\Psr7\Response($status, [], $body));
        }
    }

    /**
     * Create a new hibp client
     */
    public function make(): Client
    {
        return new Client($this);
    }

    /**
     * Define fake responses.
     *
     * @param  mixed  $data
     */
    public static function fake(string $path, $data = null): void
    {

        $response = $data instanceof Response ? $data : self::response($data);

        static::$fakes[$path] = $response;
    }

    /**
     * Clear fakes.
     */
    public static function clearFakes(): void
    {
        static::$fakes = [];
    }

    /**
     * Create a Response instance.
     *
     * @param  mixed  $body
     * @param  (string|string[])[]  $headers
     */
    public static function response($body = '', int $status = 200, array $headers = []): Response
    {
        if ($body instanceof \GuzzleHttp\Psr7\Response) {
            return new Response($body);
        }

        // @phpstan-ignore-next-line
        $body = is_array($body) ? json_encode($body) : (string) $body;

        // @phpstan-ignore-next-line
        $response = new \GuzzleHttp\Psr7\Response($status, $headers, $body);

        return new Response($response);
    }
}
