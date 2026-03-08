<?php

declare(strict_types=1);

namespace Bright\Hibp\Exceptions;

use Bright\Hibp\Http\Response;
use RuntimeException;

class HibpException extends RuntimeException
{
    use ConditionableException;

    public static function throwIfFailed(mixed $response): void
    {
        if ($response instanceof Response && ! $response->successful()) {

            throw new self($response->body(), $response->status());
        }
    }

    public static function throwIfInvalidItems(mixed $items): void
    {
        if (! is_array($items)) {
            throw new self(
                'The response data is invalid or not an array'
            );
        }
    }

    public static function failedOauthToken(): self
    {
        return new self('Failed to retrieve NinjaOne OAuth token');
    }

    public static function invalidTokenResponse(): self
    {
        return new self('Invalid OAuth token response');
    }

    public static function missingToken(): self
    {
        return new self('NinjaOne access token is not configured.');
    }

    public static function fromResponse(Response $response): self
    {
        $message = sprintf(
            'NinjaOne API request failed with status %d%s',
            $response->status(),
            $response->body() ? sprintf(': %s', $response->body()) : ''
        );

        return new self($message, $response->status());
    }
}
