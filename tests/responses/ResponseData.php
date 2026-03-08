<?php

declare(strict_types=1);

use Bright\Hibp\Exceptions\HibpException;
use Bright\Hibp\Http\Response;
use Bright\Hibp\Responses\ResponseData;
use GuzzleHttp\Psr7\Response as GuzzleResponse;

class TestResponseData extends ResponseData {}

it('exposes data via magic accessors and array conversion', function () {
    $data = [
        'Name'     => 'Adobe',
        'PwnCount' => 100,
    ];

    $response = new TestResponseData($data);

    expect($response->Name)->toBe('Adobe');
    expect($response->PwnCount)->toBe(100);
    expect($response->Unknown)->toBeNull();

    expect(isset($response->Name))->toBeTrue();
    expect(isset($response->Unknown))->toBeFalse();

    expect($response->toArray())->toBe($data);
    expect($response->jsonSerialize())->toBe($data);
});

it('supports array access and remains immutable', function () {
    $response = new TestResponseData([
        'Name' => 'Adobe',
    ]);

    expect(isset($response['Name']))->toBeTrue();
    expect(isset($response['Unknown']))->toBeFalse();
    expect($response['Name'])->toBe('Adobe');
    expect($response['Unknown'])->toBeNull();

    expect(fn () => $response['Name'] = 'Other')->toThrow(LogicException::class);
    expect(function () use ($response) {
        unset($response['Name']);
    })->toThrow(LogicException::class);
});

it('can be created from array input via make', function () {
    $data = [
        'Name'     => 'Dropbox',
        'PwnCount' => 200,
    ];

    $response = TestResponseData::make($data);

    expect($response)->toBeInstanceOf(TestResponseData::class);
    expect($response->toArray())->toBe($data);
});

it('can be created from response input via make', function () {
    $guzzle = new GuzzleResponse(200, [], json_encode([
        'Name'     => 'Breach',
        'PwnCount' => 300,
    ]));
    $response = new Response($guzzle);

    $data = TestResponseData::make($response);

    expect($data)->toBeInstanceOf(TestResponseData::class);
    expect($data->toArray())->toBe([
        'Name'     => 'Breach',
        'PwnCount' => 300,
    ]);
});

it('throws when make receives a failed response', function () {
    $guzzle   = new GuzzleResponse(500, [], 'Server error');
    $response = new Response($guzzle);

    expect(fn () => TestResponseData::make($response))->toThrow(HibpException::class);
});
