<?php

declare(strict_types=1);

use Bright\Hibp\Exceptions\HibpException;
use Bright\Hibp\Http\Response;
use Bright\Hibp\Responses\ResponseCollection;
use Bright\Hibp\Responses\ResponseData;
use GuzzleHttp\Psr7\Response as GuzzleResponse;

class TestCollectionResponseData extends ResponseData {}

class TestResponseCollection extends ResponseCollection
{
    public function get(int $index): TestCollectionResponseData
    {
        return TestCollectionResponseData::make($this->items[$index] ?? []);
    }
}

it('handles get, array access, iteration, and count', function () {
    $items = [
        [
            'Name'     => 'Adobe',
            'PwnCount' => 100,
        ],
        [
            'Name'     => 'Dropbox',
            'PwnCount' => 200,
        ],
    ];

    $collection = new TestResponseCollection($items);

    expect($collection->get(0))->toBeInstanceOf(TestCollectionResponseData::class);
    expect($collection->get(0)->Name)->toBe('Adobe');
    expect($collection->get(1)->PwnCount)->toBe(200);

    expect($collection->toArray())->toBe($items);
    expect($collection->jsonSerialize())->toBe($items);
    expect(json_encode($collection))->toBe(json_encode($items));

    expect(isset($collection[0]))->toBeTrue();
    expect(isset($collection[5]))->toBeFalse();
    expect($collection[0])->toBeInstanceOf(TestCollectionResponseData::class);
    expect($collection[0]->Name)->toBe('Adobe');

    $collected = [];
    foreach ($collection as $key => $value) {
        $collected[$key] = $value;
    }
    expect($collected[0])->toBeInstanceOf(TestCollectionResponseData::class);
    expect($collected[1])->toBeInstanceOf(TestCollectionResponseData::class);
    expect($collected[0]->toArray())->toBe($items[0]);
    expect($collected[1]->toArray())->toBe($items[1]);

    expect(count($collection))->toBe(2);
});

it('prevents mutation via array access', function () {
    $collection = new TestResponseCollection([
        ['Name' => 'Adobe'],
    ]);

    expect(fn () => $collection[0] = ['Name' => 'Other'])->toThrow(BadMethodCallException::class);
    expect(function () use ($collection) {
        unset($collection[0]);
    })->toThrow(BadMethodCallException::class);
});

it('can be created from array input via make', function () {
    $items = [
        ['Name' => 'Adobe'],
        ['Name' => 'Dropbox'],
    ];

    $collection = TestResponseCollection::make($items);

    expect($collection)->toBeInstanceOf(TestResponseCollection::class);
    expect($collection->toArray())->toBe($items);
});

it('can be created from response input via make', function () {
    $guzzle = new GuzzleResponse(200, [], json_encode([
        ['Name' => 'Adobe'],
        ['Name' => 'Dropbox'],
    ]));
    $response = new Response($guzzle);

    $collection = TestResponseCollection::make($response);

    expect($collection)->toBeInstanceOf(TestResponseCollection::class);
    expect($collection->toArray())->toBe([
        ['Name' => 'Adobe'],
        ['Name' => 'Dropbox'],
    ]);
});

it('throws when make receives a failed response', function () {
    $guzzle   = new GuzzleResponse(500, [], 'Server error');
    $response = new Response($guzzle);

    expect(fn () => TestResponseCollection::make($response))->toThrow(HibpException::class);
});
