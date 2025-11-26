<?php

use Bright\Hibp\Http\Response;
use GuzzleHttp\Psr7\Response as GuzzleResponse;

it('fully tests the HTTP response wrapper', function () {

    $guzzle = new GuzzleResponse(
        200,
        ['Content-Type' => 'application/json', 'X-Test' => 'ABC'],
        json_encode(['name' => 'Saeed', 'value' => 123])
    );

    $res = new Response($guzzle);

    // status()
    expect($res->status())->toBe(200);

    // body()
    expect($res->body())->toBe('{"name":"Saeed","value":123}');

    // json()
    expect($res->json())->toBe(['name' => 'Saeed', 'value' => 123]);

    // successful / failed
    expect($res->successful())->toBeTrue();
    expect($res->failed())->toBeFalse();

    // header()
    expect($res->header('Content-Type'))->toBe('application/json');
    expect($res->header('X-Test'))->toBe('ABC');
    expect($res->header('Unknown', 'default'))->toBe('default');
});

it('returns empty array for empty body json', function () {
    $guzzle = new GuzzleResponse(200, [], '');
    $res = new Response($guzzle);

    expect($res->json())->toBe([]);
});

it('throws json exception for invalid json', function () {
    $guzzle = new GuzzleResponse(200, [], '{invalid json');
    $res = new Response($guzzle);

    expect(fn () => $res->json())->toThrow(JsonException::class);
});

it('detects failed responses', function () {
    $guzzle = new GuzzleResponse(404, [], 'Not Found');
    $res = new Response($guzzle);

    expect($res->successful())->toBeFalse();
    expect($res->failed())->toBeTrue();
});
