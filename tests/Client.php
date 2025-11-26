<?php

use Bright\Hibp\Client;
use Bright\Hibp\Factory;
use Bright\Hibp\Http\Response;
use Bright\Hibp\Responses\Breach;
use Bright\Hibp\Responses\Breaches;

it('can chain withFactory', function () {
    $client = new Client;
    $factory = new Factory;
    $client = $client->withFactory($factory);
    expect($client)->toBeInstanceOf(Client::class);
});

test('breachedaccount', function () {
    $factory = new Factory;
    Factory::fake('/breachedaccount/test@example.com', [['Name' => 'Adobe']]);

    $client = new Client($factory);
    $breaches = $client->breachedaccount('test@example.com');

    expect($breaches)->toBeInstanceOf(Breaches::class);
    expect($breaches->toArray()[0]['Name'])->toBe('Adobe');

    Factory::clearFakes();
});

test('breachedaccount - empty when breachedaccount 404', function () {

    $factory = new Factory;

    Factory::fake('/breachedaccount/test@example.com', new Response(new \GuzzleHttp\Psr7\Response(404)));

    $client = new Client($factory);
    $breaches = $client->breachedaccount('test@example.com');

    expect($breaches)->toBeInstanceOf(Breaches::class);
    expect($breaches->toArray())->toBeEmpty();

    Factory::clearFakes();
});

test('breacheddomain', function () {
    $factory = new Factory;
    Factory::fake('/breacheddomain/example.com', ['test@example.com']);

    $client = new Client($factory);
    expect($client->breacheddomain('example.com'))->toBe(['test@example.com']);

    Factory::clearFakes();
});

test('subscribeddomains', function () {
    $factory = new Factory;
    Factory::fake('/subscribeddomains', ['example.com']);

    $client = new Client($factory);
    expect($client->subscribeddomains())->toBe(['example.com']);

    Factory::clearFakes();
});

test('breaches', function () {
    $factory = new Factory;
    Factory::fake('/breaches', [['Name' => 'Adobe']]);

    $client = new Client($factory);
    $breaches = $client->breaches();

    expect($breaches)->toBeInstanceOf(Breaches::class);
    expect($breaches->toArray()[0]['Name'])->toBe('Adobe');

    Factory::clearFakes();
});

test('breach', function () {
    $factory = new Factory;
    Factory::fake('/breach/Adobe', ['Name' => 'Adobe']);

    $client = new Client($factory);
    $breach = $client->breach('Adobe');

    expect($breach)->toBeInstanceOf(Breach::class);
    expect($breach->Name)->toBe('Adobe');

    Factory::clearFakes();
});

test('latestbreach', function () {
    $factory = new Factory;
    Factory::fake('/latestbreach', ['Name' => 'Adobe']);

    $client = new Client($factory);
    $breach = $client->latestbreach();

    expect($breach)->toBeInstanceOf(Breach::class);
    expect($breach->Name)->toBe('Adobe');

    Factory::clearFakes();
});

test('dataclasses', function () {
    $factory = new Factory;
    Factory::fake('/dataclasses', ['Emails', 'Passwords']);

    $client = new Client($factory);
    expect($client->dataclasses())->toBe(['Emails', 'Passwords']);

    Factory::clearFakes();
});

test('pasteaccount', function () {
    $factory = new Factory;
    Factory::fake('/pasteaccount/test@example.com', [['Id' => '123']]);

    $client = new Client($factory);
    expect($client->pasteaccount('test@example.com'))->toBe([['Id' => '123']]);

    Factory::clearFakes();
});

test('subscriptionStatus', function () {
    $factory = new Factory;
    Factory::fake('/subscription/status', ['Active' => true]);

    $client = new Client($factory);
    expect($client->subscriptionStatus())->toBe(['Active' => true]);

    Factory::clearFakes();
});

test('range', function () {
    $factory = new Factory;
    Factory::fake('/range/ABCDE', ['ABCDE' => 5]);

    $client = new Client($factory);
    expect($client->range('ABCDE'))->toBe(['ABCDE' => 5]);

    Factory::clearFakes();
});
