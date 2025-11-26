<?php

declare(strict_types=1);

namespace Bright\Hibp\Responses;

use ArrayAccess;
use ArrayIterator;
use Bright\Hibp\Contracts\Arrayable;
use Bright\Hibp\Support\Support;
use IteratorAggregate;
use JsonSerializable;

/**
 * Class Response
 *
 * Represents a single HIBP breach record.
 *
 * @property-read string|null $name
 * @property-read string|null $title
 * @property-read string|null $domain
 * @property-read string|null $breach_date
 * @property-read string|null $added_date
 * @property-read string|null $modified_date
 * @property-read int|null $pwn_count
 * @property-read string|null $description
 * @property-read string|null $logo_path
 * @property-read string|null $attribution
 * @property-read string|null $disclosure_url
 * @property-read array|null $data_classes
 * @property-read bool|null $is_verified
 * @property-read bool|null $is_fabricated
 * @property-read bool|null $is_sensitive
 * @property-read bool|null $is_retired
 * @property-read bool|null $is_spam_list
 * @property-read bool|null $is_malware
 * @property-read bool|null $is_subscription_free
 * @property-read bool|null $is_stealer_log
 *
 * Start with PascalCase/Studly key
 * @property-read string|null $Name
 * @property-read string|null $Title
 * @property-read string|null $Domain
 * @property-read string|null $BreachDate
 * @property-read string|null $AddedDate
 * @property-read string|null $ModifiedDate
 * @property-read int|null $PwnCount
 * @property-read string|null $Description
 * @property-read string|null $LogoPath
 * @property-read string|null $Attribution
 * @property-read string|null $DisclosureUrl
 * @property-read array|null $DataClasses
 * @property-read bool|null $IsVerified
 * @property-read bool|null $IsFabricated
 * @property-read bool|null $IsSensitive
 * @property-read bool|null $IsRetired
 * @property-read bool|null $IsSpamList
 * @property-read bool|null $IsMalware
 * @property-read bool|null $IsSubscriptionFree
 * @property-read bool|null $IsStealerLog
 *
 * @implements \ArrayAccess<string, mixed>
 * @implements \IteratorAggregate<string, mixed>
 * @implements Arrayable<string, mixed>
 */
class Breach implements Arrayable, ArrayAccess, IteratorAggregate, JsonSerializable
{
    /**
     * The original api data
     *
     * @var array<string, mixed>
     */
    private array $item = [];

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
        return $this->item[$name] ?? $this->item[Support::studly($name)] ?? null;
    }

    /**
     * Check if property exists
     */
    public function __isset(string $name): bool
    {
        return isset($this->item[$name]);
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
        return isset($this->item[$offset]);
    }

    public function offsetGet($offset): mixed
    {
        return $this->item[$offset] ?? null;
    }

    // Immutable; to modify, use with() method
    public function offsetSet($offset, $value): void
    {
        throw new \LogicException('Response object is immutable.');
    }

    public function offsetUnset($offset): void
    {
        throw new \LogicException('Response object is immutable.');
    }

    /**
     * @return \ArrayIterator<string, mixed>
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->toArray());
    }
}
