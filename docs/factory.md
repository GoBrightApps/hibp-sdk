# 📘 Factory Class Documentation

The **Factory** class is the core request builder of the HIBP PHP SDK.  
It provides a fluent, testable, middleware-friendly wrapper around Guzzle and is responsible for:

- configuring API credentials  
- managing request headers  
- setting base URI  
- adding middleware  
- building HTTP options  
- overriding handlers  
- providing fake responses for testing  
- issuing HTTP requests and returning `Response` objects  

---

## ▶️ Creating a Factory Instance

```php
use Bright\Hibp\Factory;

$factory = new Factory();
```

You may optionally pass Guzzle options:

```php
$factory = new Factory([
    'verify' => false,
]);
```

---

## ⚙️ Configuration Methods

Each configuration method returns the current Factory instance, supporting full fluency.

### **Set API Key**

```php
$factory->withApiKey('your-hibp-key');
```

This API key is automatically added to headers using:

```
Hibp::HEADER_AUTH_NAME
```

---

### **Set Base URI**

Default:

```
https://haveibeenpwned.com/api/v3
```

Override:

```php
$factory->withBaseUri('https://example.com/custom-api');
```

All relative paths are joined using `Support::joinUri`.

---

### **Set User Agent**

```php
$factory->withUserAgent('Your-Client/1.0');
```

Default:

```
Hibp-php-api/1.0
```

---

### **Add Headers**

```php
$factory->withHeaders([
    'X-Debug' => 'true',
]);
```

Headers merge across multiple calls.

---

### **Add Query Parameters**

```php
$factory->withQueryParameters([
    'truncateResponse' => 'true',
]);
```

Merged with per-request query parameters.

---

### **Add Guzzle Options**

```php
$factory->withOptions([
    'connect_timeout' => 5,
]);
```

Merged into every request.

---

### **Set Timeout**

```php
$factory->withTimeout(5.0);
```

Sets the value for:

```
RequestOptions::TIMEOUT
```

---

### **Set HTTP Client**

```php
$factory->withHttpClient(new GuzzleHttp\Client());
```

Provides full control over Guzzle configuration.

---

### **Add Middleware**

```php
$factory->withMiddleware(function ($handler) {
    return function ($request, array $options) use ($handler) {
        // modify request/options
        return $handler($request, $options);
    };
});
```

Middleware is appended to an internally managed `HandlerStack`.

---

### **Override Handler Stack**

Useful for low-level Guzzle testing:

```php
$mock = new MockHandler([...]);

$factory->withHandler($mock);
```

---

## 🌐 Sending Requests

Factory currently supports the GET verb:

```php
$response = $factory->get('/breachedaccount/example@example.com');
```

Internally this uses:

```
send('GET', $url)
```

---

## 📡 Response Handling

Each request returns `Bright\Hibp\Http\Response`:

```php
$response->status();   // int
$response->json();     // array|null
$response->body();     // string
$response->headers();  // array
```

---

## 🧪 Testing With Fakes

Factory includes a fake system inspired by Laravel’s HTTP fake.

### **Fake by Path**

```php
Factory::fake('breachedaccount', ['pwned' => true]);
```

Any request containing `"breachedaccount"` returns the fake.

### **Fake Any Request**

```php
Factory::fake('*', ['status' => 'ok']);
```

Useful for tests that shouldn’t hit the network.

### **Clear Fakes**

```php
Factory::clearFakes();
```

---

## 🧩 Creating Client Instances

Factory can create the high-level HIBP client:

```php
$client = $factory->make();
```

This exposes user-friendly API methods like:

- check email  
- check password hash  
- check paste data  

---

## 🔧 Error Handling

Guzzle exceptions are transformed into safe `Response` objects:

```php
try {
    $res = $factory->get('/test');
} catch (...) {
    // never thrown
}
```

Returned Response contains:

- HTTP status (default `500`)  
- exception message in body  

---

## 📄 Summary

| Feature                     | Supported |
|-----------------------------|----------|
| API key injection           | ✔️ |
| Base URI configuration      | ✔️ |
| Global headers              | ✔️ |
| Per-request headers         | ✔️ |
| Query merging               | ✔️ |
| Guzzle options              | ✔️ |
| Timeout                     | ✔️ |
| Custom HTTP client          | ✔️ |
| Middleware stack            | ✔️ |
| Custom handler              | ✔️ |
| URL joining                 | ✔️ |
| Exception-safe Response     | ✔️ |
| Fake responses              | ✔️ |
| Factory → Client bridge     | ✔️ |

---

**End of Factory Documentation**
