<?php

use Bright\Hibp\Client;
use Bright\Hibp\Factory;
use Bright\Hibp\Hibp;
use Bright\Hibp\Http\Response;
use GuzzleHttp\Psr7\Response as GuzzleResponse;

it('header constants', function () {
    expect(Hibp::HEADER_AUTH_NAME)->toBe('hibp-api-key');
    expect(Hibp::HEADER_USER_AGENT)->toBe('User-Agent');
});

it('creates a new factory', function () {
    $factory = Hibp::factory();
    expect($factory)->toBeInstanceOf(Factory::class);
});

it('creates a new client', function () {
    $client = Hibp::make('test-key');
    expect($client)->toBeInstanceOf(Client::class);
});

it('creates a Response instance', function () {
    $data = ['foo' => 'bar'];
    $response = Hibp::response($data, 201, ['X-Test' => 'ok']);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->status())->toBe(201);
    expect($response->json())->toBe($data);
    expect($response->header('X-Test'))->toBe('ok');

    $body = 'hello';
    $response = Hibp::response($body, 202);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->status())->toBe(202);
    expect($response->body())->toBe('hello');

    $guzzle = new GuzzleResponse(203, ['X-Foo' => 'bar'], 'content');
    $response = Hibp::response($guzzle);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->status())->toBe(203);
    expect($response->body())->toBe('content');
    expect($response->header('X-Foo'))->toBe('bar');
});

it('creates fake response', function () {

    $client = Hibp::factory();

    Hibp::fake('*', ['path' => '*']);
    expect($client->get('test')->json())->toBe(['path' => '*']);

    Hibp::fake('test', ['path' => 'test']);
    expect($client->get('test')->json())->toBe(['path' => 'test']);

    Hibp::fake('test', ['name' => 'php']);
    expect($client->get('test')->json())->toBe(['name' => 'php']);
});
