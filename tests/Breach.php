<?php

use Bright\Hibp\Responses\Breach;

it('returns property values values via magic', function () {

    $breach = new Breach([
        'Name' => 'Adobe',
        'PwnCount' => 10,
        'IsVerified' => true,
        'Description' => 'Adobe breach description',
        'LogoPath' => 'logo/adobe.png',
        'DataClasses' => ['Emails', 'Passwords'],
    ]);

    expect($breach->name)->toBe('Adobe');
    expect($breach->pwn_count)->toBe(10);
    expect($breach->is_verified)->toBeTrue();
    expect($breach->logo_path)->toBe('logo/adobe.png');
    expect($breach->data_classes)->toBe(['Emails', 'Passwords']);
    expect($breach->uknown)->toBeNull();

    // studly-cased
    expect($breach->Name)->toBe('Adobe');
    expect($breach->PwnCount)->toBe(10);
    expect($breach->IsVerified)->toBeTrue();
    expect($breach->LogoPath)->toBe('logo/adobe.png');
    expect($breach->DataClasses)->toBe(['Emails', 'Passwords']);
    expect($breach->Uknown)->toBeNull();
});

test('data conversions with types casting', function () {

    $data = [
        'Name' => 'Adobe',
        'PwnCount' => 100,
        'IsVerified' => true,
    ];

    $breach = new Breach($data);

    // __isset
    expect(isset($breach->Name))->toBeTrue();
    expect(isset($breach->Unknown))->toBeFalse();

    // toArray
    expect(gettype($breach->toArray()))->toBe('array');
    expect($breach->toArray())->toBe($data);

    // jsonSerialize
    expect($breach->jsonSerialize())->toBe($data);
    expect(gettype($breach->jsonSerialize()))->toBe('array');

    // ArrayAccess: exists + get
    expect(isset($breach['Name']))->toBeTrue();
    expect(isset($breach['Unknown']))->toBeFalse();
    expect($breach['Name'])->toBe('Adobe');
    expect($breach['Unknown'])->toBeNull();

    // IteratorAggregate
    $collected = [];
    foreach ($breach as $key => $value) {
        $collected[$key] = $value;
    }
    expect($collected)->toBe($data);
});
