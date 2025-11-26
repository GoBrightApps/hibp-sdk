<?php

use Bright\Hibp\Responses\Breach;
use Bright\Hibp\Responses\Breaches;

it('handles get, array access, iteration', function () {

    $items = [
        [
            'Name' => 'Adobe',
            'PwnCount' => 100,
        ],
        [
            'Name' => 'Dropbox',
            'PwnCount' => 200,
        ],
    ];

    $breaches = new Breaches($items);

    // get()
    expect($breaches->get(0))->toBeInstanceOf(Breach::class);
    expect($breaches->get(0)->Name)->toBe('Adobe');
    expect($breaches->get(1)->PwnCount)->toBe(200);

    // toArray()
    expect($breaches->toArray())->toBe($items);

    // jsonSerialize()
    expect($breaches->jsonSerialize())->toBe($items);
    expect(json_encode($breaches))->toBe(json_encode($items));

    // ArrayAccess: exists + get
    expect(isset($breaches[0]))->toBeTrue();
    expect(isset($breaches[5]))->toBeFalse();

    expect($breaches[0])->toBeInstanceOf(Breach::class);
    expect($breaches[0]->Name)->toBe('Adobe');

    // IteratorAggregate
    $collected = [];
    foreach ($breaches as $key => $value) {
        $collected[$key] = $value;
    }
    expect($collected)->toBe($items);

    // Countable
    expect(count($breaches))->toBe(2);
});
