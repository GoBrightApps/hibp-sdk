<?php

declare(strict_types=1);

use Bright\Hibp\Responses\Breach\BreachInfo;

it('returns property values values via magic', function () {

    $breach = new BreachInfo([
        'Name'        => 'Adobe',
        'PwnCount'    => 10,
        'IsVerified'  => true,
        'Description' => 'Adobe breach description',
        'LogoPath'    => 'logo/adobe.png',
        'DataClasses' => ['Emails', 'Passwords'],
    ]);

    // studly-cased
    expect($breach->Name)->toBe('Adobe');
    expect($breach->PwnCount)->toBe(10);
    expect($breach->IsVerified)->toBeTrue();
    expect($breach->LogoPath)->toBe('logo/adobe.png');
    expect($breach->DataClasses)->toBe(['Emails', 'Passwords']);
    expect($breach->Uknown)->toBeNull();

    // snake_case (not supported)
    expect($breach->name)->toBeNull();
    expect($breach->pwn_count)->toBeNull();
    expect($breach->is_verified)->toBeNull();
    expect($breach->logo_path)->toBeNull();
    expect($breach->data_classes)->toBeNull();
    expect($breach->uknown)->toBeNull();
});

test('data conversions with types casting', function () {

    $data = [
        'Name'       => 'Adobe',
        'PwnCount'   => 100,
        'IsVerified' => true,
    ];

    $breach = new BreachInfo($data);

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

});
