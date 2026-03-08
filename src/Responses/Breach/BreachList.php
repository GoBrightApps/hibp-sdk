<?php

declare(strict_types=1);

namespace Bright\Hibp\Responses\Breach;

use Bright\Hibp\Responses\ResponseCollection;

/**
 * @extends ResponseCollection<BreachInfo>
 */
class BreachList extends ResponseCollection
{
    /**
     * Get Response object at index.
     */
    public function get(int $index): BreachInfo
    {
        return BreachInfo::make($this->items[$index] ?? []);
    }
}
