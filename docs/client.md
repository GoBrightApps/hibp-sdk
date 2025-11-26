# Bright\Hibp\Client Documentation

The **Client** class provides a Laravel-like wrapper for interacting with the **Have I Been Pwned (HIBP) API**.  
It handles breaches, domains, pastes, subscriptions, and pwned password checks.  
The client relies on a `Factory` instance to send HTTP requests and supports fake responses for testing.

---

## Constructor

```php
public function __construct(?Factory $factory = null)
```

**Parameters:**

- `$factory` (optional): Instance of `Factory`. If not provided, a new `Factory` instance will be created.

**Description:**  
Initializes the client with a factory responsible for HTTP requests.

---

## Methods

### **withFactory**

```php
public function withFactory(Factory $factory): self
```

**Parameters:** `$factory` — A `Factory` instance.  
**Returns:** `Client`  

**Description:** Replace the current factory with a new one. Useful for injecting a fake factory for testing.

---

### **breachedaccount**

```php
public function breachedaccount(string $account): Breaches
```

**Parameters:** `$account` — Email, username, or phone number.  
**Returns:** `Breaches` — Collection of breach records.  

**Notes:** Returns an empty `Breaches` collection if the account has no breaches (HTTP 404).  
**API:** `GET /breachedaccount/{account}`

---

### **breacheddomain**

```php
public function breacheddomain(string $domain): array
```

**Parameters:** `$domain` — Verified domain name.  
**Returns:** `array` — List of breached emails under that domain.  
**API:** `GET /breacheddomain/{domain}`

---

### **subscribeddomains**

```php
public function subscribeddomains(): array
```

**Returns:** `array` — Domains subscribed under your API key.  
**API:** `GET /subscribeddomains`

---

### **breaches**

```php
public function breaches(?string $domain = null, ?bool $isSpamList = null): Breaches
```

**Parameters:**

- `$domain` (optional): Filter breaches for a specific domain.  
- `$isSpamList` (optional): Filter for spam list breaches.  

**Returns:** `Breaches` — All breaches in the system.  
**API:** `GET /breaches`

---

### **breach**

```php
public function breach(string $name): Breach
```

**Parameters:** `$name` — Breach name.  
**Returns:** `Breach` — Single breach record.  
**API:** `GET /breach/{name}`

---

### **latestbreach**

```php
public function latestbreach(): Breach
```

**Returns:** `Breach` — Most recently added breach.  
**API:** `GET /latestbreach`

---

### **dataclasses**

```php
public function dataclasses(): array
```

**Returns:** `array` — All available data classes (e.g., Emails, Passwords).  
**API:** `GET /dataclasses`

---

### **pasteaccount**

```php
public function pasteaccount(string $account): array
```

**Parameters:** `$account` — Email, username, or phone number.  
**Returns:** `array` — Pastes associated with the account.  
**API:** `GET /pasteaccount/{account}`

---

### **subscriptionStatus**

```php
public function subscriptionStatus(): array
```

**Returns:** `array` — Current subscription status of the API key.  
**API:** `GET /subscription/status`

---

### **range**

```php
public function range(string $hashPrefix): mixed
```

**Parameters:** `$hashPrefix` — First 5 characters of the SHA-1 hash of the password.  
**Returns:** `mixed` — Pwned password hash suffixes and counts (k-anonymity method).  
**API:** `GET /range/{first5Hash}`

---

## Example Usage

```php
use Bright\Hibp\Client;
use Bright\Hibp\Factory;

// Create client with optional factory
$client = new Client(new Factory());

// Get all breaches for an email
$breaches = $client->breachedaccount('test@example.com');

// Get a single breach
$breach = $client->breach('Adobe');

// Check pwned passwords
$rangeData = $client->range('ABCDE');
```

---

## Notes

- **Testing:** Use `Factory::fake()` to mock API responses.  
- **Immutable Responses:** Methods return new objects or arrays. Client methods do not modify state.  
- **Error Handling:** HTTP errors are handled inside `Factory`. For example, 404 returns empty collections for breaches.

---

**End of Client Documentation**
