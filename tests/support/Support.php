<?php

use Bright\Hibp\Support\Support;

it('converts strings to studly case', function () {

    expect(Support::studly('HelloWorld'))->toBe('HelloWorld');
    expect(Support::studly('hello_world'))->toBe('HelloWorld');
    expect(Support::studly('hello-world'))->toBe('HelloWorld');
    expect(Support::studly('hello world'))->toBe('HelloWorld');
    expect(Support::studly('hello world s'))->toBe('HelloWorldS');
    expect(Support::studly('bright\hibp'))->toBe('Bright\hibp');
    expect(Support::studly(null))->toBe('');
    expect(Support::studly(false))->toBe('');
    expect(Support::studly(1))->toBe('1');
    expect(Support::studly(''))->toBe('');

});

test('studly throws for mixed value', function () {
    Support::studly(function () {});
    Support::studly(new stdClass);
    Support::studly(fn () => '');
    Support::studly([]);
})->throws(TypeError::class);

it('joins url segments', function () {
    // simple join
    expect(Support::joinUri('https://example.com', 'api', 'v1'))->toBe('https://example.com/api/v1');

    // multiple slashes
    expect(Support::joinUri('https://example.com/', '/api/', '/v1/'))->toBe('https://example.com/api/v1');

    // mix of backslashes and forward slashes
    expect(Support::joinUri('https://example.com\\', '\\api\\', 'v1'))->toBe('https://example.com/api/v1');

    // protocol preserved
    expect(Support::joinUri('http://example.com', '/api//'))->toBe('http://example.com/api');

    // empty segments ignored
    expect(Support::joinUri('https://example.com', '', 'v1'))->toBe('https://example.com/v1');

    // ftp protocol
    expect(Support::joinUri('ftp://example.com', 'files', '2025'))->toBe('ftp://example.com/files/2025');
});
