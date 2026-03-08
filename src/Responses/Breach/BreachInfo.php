<?php

declare(strict_types=1);

namespace Bright\Hibp\Responses\Breach;

use Bright\Hibp\Responses\ResponseData;

/**
 * Represents a breach record returned from the HIBP API.
 *
 * @property-read string|null $name
 * @property-read string|null $title
 * @property-read string|null $domain
 * @property-read string|null $breachDate
 * @property-read string|null $addedDate
 * @property-read string|null $modifiedDate
 * @property-read int|null $pwnCount
 * @property-read string|null $description
 * @property-read string|null $logoPath
 * @property-read string|null $attribution
 * @property-read string|null $disclosureUrl
 * @property-read array|null $dataClasses
 * @property-read bool|null $isVerified
 * @property-read bool|null $isFabricated
 * @property-read bool|null $isSensitive
 * @property-read bool|null $isRetired
 * @property-read bool|null $isSpamList
 * @property-read bool|null $isMalware
 * @property-read bool|null $isSubscriptionFree
 * @property-read bool|null $isStealerLog
 */
class BreachInfo extends ResponseData {}
