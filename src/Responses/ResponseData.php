<?php

declare(strict_types=1);

namespace Bright\Hibp\Responses;

use ArrayAccess;
use Bright\Hibp\Contracts\Arrayable;
use Bright\Hibp\Exceptions\HibpException;
use Bright\Hibp\Http\Response;
use JsonSerializable;
use LogicException;

/**
 * Create response object.
 *
 * @implements ArrayAccess<string, mixed>
 * @implements Arrayable<string, mixed>
 *
 * @phpstan-consistent-constructor
 */
abstract class ResponseData implements Arrayable, ArrayAccess, JsonSerializable
{
    /**
     * The original api data
     *
     * @var array<string, mixed>
     */
    protected array $item = [];

    /**
     * Create a new response object
     *
     * @param  array<string, mixed>  $item
     */
    public function __construct(array $item)
    {
        $this->item = $item;
    }

    /**
     * Get property value dynamically
     */
    public function __get(string $name): mixed
    {
        return $this->item[$name] ?? null;
    }

    /**
     * Check if property exists
     */
    public function __isset(string $name): bool
    {
        return isset($this->item[$name]);
    }

    /**
     * Convert api response to this object.
     *
     * @param  array<string, mixed>|Response  $items
     */
    public static function make(array|Response $items): static
    {
        HibpException::throwIfFailed($items);

        if ($items instanceof Response) {
            $items = $items->json();
        }

        HibpException::throwIfInvalidItems($items);

        /** @var array<string, mixed> $items */
        return new static($items);
    }

    /**
     * Get the instance as an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->item;
    }

    /**
     * JSON serialization
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    // ---- ArrayAccess ----
    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->item);
    }

    public function offsetGet($offset): mixed
    {
        return $this->item[$offset] ?? null;
    }

    // Immutable; to modify, use with() method
    public function offsetSet($offset, $value): void
    {
        throw new LogicException('Response object is immutable.');
    }

    public function offsetUnset($offset): void
    {
        throw new LogicException('Response object is immutable.');
    }
}
