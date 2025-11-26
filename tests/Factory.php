<?php

use Bright\Hibp\Factory;
use Bright\Hibp\Http\Response;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response as GuzzleResponse;

beforeEach(function () {
    Factory::clearFakes();
});

it('sets headers', function () {
    $factory = new Factory;

    $factory->withHeaders(['X-Test' => '123']);

    $ref = new ReflectionProperty(Factory::class, 'headers');
    $ref->setAccessible(true);

    expect($ref->getValue($factory))->toEqual(['X-Test' => '123']);
});

it('sets api key and merges into request headers', function () {

    $mock = new MockHandler([
        new GuzzleResponse(200, [], 'ok'),
    ]);

    $handler = HandlerStack::create($mock);

    $factory = (new Factory)
        ->withApiKey('secret-xxx')
        ->withHandler($handler)
        ->withHttpClient(new GuzzleClient);

    $res = $factory->get('/test');

    expect($res->body())->toBe('ok');
});

it('changes base uri and builds full url', function () {
    $mock = new MockHandler([
        new GuzzleResponse(200, [], 'works'),
    ]);

    $handler = HandlerStack::create($mock);

    $factory = (new Factory)
        ->withBaseUri('https://api.test.com/v1')
        ->withHandler($handler)
        ->withHttpClient(new GuzzleClient);

    $response = $factory->get('users');

    expect($response->body())->toBe('works');
});

it('supports fake response for a specific path', function () {

    Factory::fake('breachedaccount', 'fake-body');

    $factory = new Factory;

    $res = $factory->get('breachedaccount/test@example.com');

    expect($res->body())->toBe('fake-body');
});

it('supports fake response for wildcard *', function () {
    Factory::fake('*', 'any-response');

    $factory = new Factory;

    $res = $factory->get('anything/abc');

    expect($res->body())->toBe('any-response');
});

it('uses middleware stack', function () {
    $triggered = false;

    $mw = function (callable $handler) use (&$triggered) {
        return function ($request, array $options) use ($handler, &$triggered) {
            $triggered = true;

            return $handler($request, $options);
        };
    };

    $mock = new MockHandler([
        new GuzzleResponse(200, [], 'middleware-test'),
    ]);

    $handler = HandlerStack::create($mock);

    $factory = (new Factory)
        ->withMiddleware($mw)
        ->withHandler($handler)
        ->withHttpClient(new GuzzleClient);

    $res = $factory->get('/middleware');

    expect($res->body())->toBe('middleware-test');
    expect($triggered)->toBeTrue();
});

it('applies timeout option', function () {
    $factory = (new Factory)->withTimeout(3.5);

    $ref = new ReflectionProperty(Factory::class, 'options');
    $ref->setAccessible(true);
    $options = $ref->getValue($factory);

    expect($options)->toHaveKey('timeout', 3.5);
});

it('wraps exceptions into Response', function () {
    // Mock that always throws
    $mock = new MockHandler([
        function () {
            throw new \GuzzleHttp\Exception\ConnectException(
                'Connection failed',
                new \GuzzleHttp\Psr7\Request('GET', 'test')
            );
        },
    ]);

    $handler = HandlerStack::create($mock);

    $factory = (new Factory)
        ->withHandler($handler)
        ->withHttpClient(new GuzzleClient);

    $res = $factory->get('/error');

    expect($res)->toBeInstanceOf(Response::class);
    expect($res->body())->toContain('Connection failed');
});
