<?php

declare(strict_types=1);

namespace Bright\Hibp\Responses;

use ArrayAccess;
use ArrayIterator;
use BadMethodCallException;
use Bright\Hibp\Contracts\Arrayable;
use Bright\Hibp\Exceptions\HibpException;
use Bright\Hibp\Http\Response;
use Countable;
use IteratorAggregate;
use JsonSerializable;

/**
 * Create response collection.
 *
 *
 * @template TResponse
 *
 * @implements ArrayAccess<int, TResponse>
 * @implements Arrayable<int, array<string, mixed>>
 * @implements IteratorAggregate<int, TResponse>
 *
 * @phpstan-consistent-constructor
 */
abstract class ResponseCollection implements Arrayable, ArrayAccess, Countable, IteratorAggregate, JsonSerializable
{
    /**
     * Raw API responses array.
     *
     * @var array<int, array<string, mixed>>
     */
    protected array $items;

    /**
     * Create a new breaches resource from api response.
     *
     * @param  array<int, array<string, mixed>>  $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }

    /**
     * Get Response object at index.
     *
     * @return TResponse
     */
    abstract public function get(int $index): mixed;

    /**
     * Convert api response to raw array item into this object.
     *
     * @param  array<int, array<string, mixed>>|Response  $items
     *
     * @phpstan-return static<TResponse>
     */
    public static function make(array|Response $items): static
    {
        HibpException::throwIfFailed($items);

        if ($items instanceof Response) {
            $items = $items->json();
        }

        HibpException::throwIfInvalidItems($items);

        /** @var array<int, array<string, mixed>> $items */
        $instance = new static($items);

        /** @var static<TResponse> $instance */
        return $instance;
    }

    /**
     * Convert entire collection to array.
     *
     * @return array<int, array<string, mixed>>
     */
    public function toArray(): array
    {
        return $this->items;
    }

    /**
     * JSON serialization.
     *
     * @return array<int, array<string, mixed>>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    // ---- ArrayAccess ----
    /**
     * @return TResponse
     */
    public function offsetGet($offset): mixed
    {
        return $this->get((int) $offset);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, $this->toArray());
    }

    /**
     * {@inheritDoc}
     */
    public function offsetSet(mixed $offset, mixed $value): never
    {
        throw new BadMethodCallException('Cannot set response attributes.');
    }

    /**
     * {@inheritDoc}
     */
    public function offsetUnset(mixed $offset): never
    {
        throw new BadMethodCallException('Cannot unset response attributes.');
    }

    /**
     * @return ArrayIterator<int, TResponse>
     */
    public function getIterator(): ArrayIterator
    {
        $responses = [];

        foreach (array_keys($this->items) as $index) {
            $responses[$index] = $this->get($index);
        }

        return new ArrayIterator($responses);
    }

    // ---- Countable ----
    public function count(): int
    {
        return count($this->items);
    }
}
