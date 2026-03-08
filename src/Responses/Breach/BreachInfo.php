<?php

declare(strict_types=1);

namespace Bright\Hibp\Responses\Breach;

use Bright\Hibp\Responses\ResponseData;

/**
 * Represents a breach record returned from the HIBP API.
 *
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
 * @property-read array<int, string>|null $DataClasses
 * @property-read bool|null $IsVerified
 * @property-read bool|null $IsFabricated
 * @property-read bool|null $IsSensitive
 * @property-read bool|null $IsRetired
 * @property-read bool|null $IsSpamList
 * @property-read bool|null $IsMalware
 * @property-read bool|null $IsSubscriptionFree
 * @property-read bool|null $IsStealerLog
 */
class BreachInfo extends ResponseData {}
