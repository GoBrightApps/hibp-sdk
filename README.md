<p align="center">
    <a href="https://github.com/GoBrightApps/hibp-sdk/actions"><img alt="GitHub Workflow Status (master)" src="https://img.shields.io/github/actions/workflow/status/GoBrightApps/hibp-sdk/tests.yml?branch=main&label=tests&style=round-square"></a>
    <a href="https://packagist.org/packages/bright/hibp-sdk"><img alt="Total Downloads" src="https://img.shields.io/packagist/dt/bright/hibp-sdk"></a>
    <a href="https://packagist.org/packages/bright/hibp-sdk"><img alt="Latest Version" src="https://img.shields.io/packagist/v/bright/hibp-sdk"></a>
    <a href="https://packagist.org/packages/bright/hibp-sdk"><img alt="License" src="https://img.shields.io/github/license/GoBrightApps/hibp-sdk"></a>
</p>

The Hibp sdk provides an easy-to-use interface for interacting with Have I Been Pwned - [HIBP API](https://haveibeenpwned.com/API/v3).  
It wraps API responses in class response objects and supports fakes for testing purposes.

## Table of Contents

-   [installation](#installation)
-   [Usages](#usages)
    -   [Quick usages](#quick-usages)
    -   [Client Factory](#client-factory)
    -   [Account Breaches](#account-breaches)
    -   [Domain Breaches](#domain-breaches)
    -   [All breaches](#all-breaches)
    -   [Single Breach](#single-breach)
    -   [Latest Breach](#latest-breach)
    -   [Data Classes](#data-classes)
    -   [Pastes](#pastes)
    -   [Subscription Status](#subscription-status)
    -   [Pwned Password Range](#pwned-password-range)
-   [Troubleshooting](#troubleshooting)
-   [Testing](#testing)
-   [Contributing](#contributing)
-   [License](#license)

### Installation

> **Requires [PHP 8.2+](https://www.php.net/releases/)**

Install via the [Composer](https://getcomposer.org/) package manager:

```bash
composer require bright/hibp-sdk
```

## Usages

### Quick usages

The `Hibp::make` will create client factory and ready to making requests

```php
use Bright\Hibp\Hibp;

$apiKey = 'your-hibp-api-key';

Hibp::make($apiKey)->breaches(); //Breaches object
Hibp::make($apiKey)->breaches()->toArray(); //array of breach

Hibp::make($apiKey)->breaches()[0]->name // get the name
Hibp::make($apiKey)->breaches()[0]->Name // get the name

// Get a single breach
Hibp::make($apiKey)->breach('Adobe');


//Get account breached
Hibp::make($apiKey)->breachedaccount('youremail@example.com'); //Breaches object
Hibp::make($apiKey)->breachedaccount('youremail@example.com')->toArray();

```

### Client factory

Create a new client using factory to advance configuration for http request

```php
Hibp::factory()
    ->withApiKey('your-api-key')
    ->withHeaders(['CustomHeader' => 'value'])
    ->withUserAgent('MyApp')
    ->withTimeout(30)
    ->make() // create client
    ->breaches();
```

All available method for chain with the client factory

```php
Hibp::factory()
    ->withApiKey('your-api-key')
    ->withQueryParameters(['foo' => 'bar'])
    ->withHeaders([])
    ->withTimeout(30)
    ->withBaseUri('https://haveibeenpwned.com/api/v3')
    ->withUserAgent('MyApp')
    ->withHttpClient(new \GuzzleHttp\Client)
    ->withOptions(['referer' => false]) // https://docs.guzzlephp.org/en/stable/request-options.html
    ->withHandler('...')  // guzzle handler
    ->withMiddleware('') // https://docs.guzzlephp.org/en/stable/handlers-and-middleware.html
    ->make() // create client
    ->breaches(); // Get breaches
```

### Account Breaches

```php
$breaches = $client->breachedaccount('user@example.com');
$breaches[0]->name // breached name
$breaches[0]->name // breached name
$breaches->toArray() // All breached array items

$breaches[0]->toArray() // The breach array
```

-   Returns a `Breaches` collection.
-   Handles 404 gracefully (returns empty collection).

### Domain Breaches

```php
$breaches = $client->breacheddomain('example.com');
```

-   Returns an array of breached emails for the verified domain.

### All Breaches

```php
$allBreaches = $client->breaches();
```

-   Returns all breaches as a `Breaches` collection.

### Single Breach

```php
$breach = $client->breach('Adobe');
```

-   Returns a `Breach` object for the specified breach name.

### Latest Breach

```php
$latest = $client->latestbreach();
```

-   Returns the most recently added breach.

### Data Classes

```php
$dataClasses = $client->dataclasses();
```

-   Returns all data classes in the system as an array.

### Pastes

```php
$pastes = $client->pasteaccount('user@example.com');
```

-   Returns an array of pastes associated with the account.

### Subscription Status

```php
$status = $client->subscriptionStatus();
```

-   Returns the subscription status of your API key.

### Pwned Password Range

```php
$result = $client->range('5BAA6');
```

-   Uses k-anonymity API to check if a password has been pwned.
-   Returns a JSON array of suffixes and counts.

## Testing

Create fake response using `Hibp::fake` helper:

```php
use Bright\Hibp\Hibp;

Hibp::fake('/breachedaccount/user@example.com', [
    ['Name' => 'Adobe', 'PwnCount' => 12345]
]);

$client = Hibp::make('fake-api-key');
$breaches = $client->breachedaccount('user@example.com');

print_r($breaches->toArray());

//for endpoints fake
$data = [['Name' => 'Adobe']];

Hibp::fake('*', $data);
Hibp::fake('*', Hibp::response($data, 500, ['fake-header' => 'fake-value']));

```

-   Fakes can be specific to endpoints or use `'*'` for a catch-all.
-   `Factory::clearFakes()` clears all fake responses.

## Troubleshooting

-   **404 for no breaches:** HIBP returns 404 if no breaches exist; client returns empty collection.
-   **Rate limits:** Ensure API key allows the requested number of queries per minute.
-   **Invalid API key:** Returns 401 Unauthorized. Check `Hibp::make($apiKey)`.
-   **Network errors:** Wrapped as a Response object with status code and message.

## Contributing

-   Fork the repository
-   Make your changes
-   Submit a pull request with a clear description

## License

MIT License © 2025 [Bright](https://bright.it)
