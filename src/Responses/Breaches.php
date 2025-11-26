<?php

declare(strict_types=1);

namespace Bright\Hibp\Responses;

use ArrayAccess;
use ArrayIterator;
use Bright\Hibp\Contracts\Arrayable;
use Countable;
use IteratorAggregate;
use JsonSerializable;

/**
 * @implements Arrayable<int, array<string, mixed>>
 * @implements ArrayAccess<int, Breach>
 * @implements IteratorAggregate<int, Breach>
 */
class Breaches implements Arrayable, ArrayAccess, Countable, IteratorAggregate, JsonSerializable
{
    /**
     * Raw API responses array.
     *
     * @var array<int, array<string, mixed>>
     */
    private array $items;

    /**
     * Create a new breaches resource from api response.
     *
     * @param  array<mixed>  $items
     */
    public function __construct(array $items)
    {
        /** @var array<int, array<string, mixed>> $items */
        $this->items = $items;
    }

    /**
     * Get Response object at index.
     */
    public function get(int $index): Breach
    {
        return new Breach($this->items[$index] ?? []);
    }

    /**
     * Convert entire collection to array.
     *
     * @return array<int, array<mixed>>
     */
    public function toArray(): array
    {
        return $this->items;
    }

    /**
     * JSON serialization.
     *
     * @return array<int, array<mixed>>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    // ---- ArrayAccess ----
    public function offsetExists($offset): bool
    {
        return isset($this->items[$offset]);
    }

    public function offsetGet($offset): Breach
    {
        return $this->get((int) $offset);
    }

    public function offsetSet($offset, $value): void
    {
        throw new \LogicException('ResponseCollection is immutable.');
    }

    public function offsetUnset($offset): void
    {
        throw new \LogicException('ResponseCollection is immutable.');
    }

    /**
     * @return \ArrayIterator<int, Breach>
     */
    public function getIterator(): ArrayIterator
    {
        // @phpstan-ignore-next-line
        return new ArrayIterator($this->toArray());
    }

    // ---- Countable ----
    public function count(): int
    {
        return count($this->items);
    }
}
