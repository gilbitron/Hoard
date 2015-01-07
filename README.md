# Hoard
[![Build Status](https://travis-ci.org/gilbitron/Hoard.svg?branch=master)](https://travis-ci.org/gilbitron/Hoard)

Hoard is a simple, extensible PHP caching library.

## Install

Install via [composer](https://getcomposer.org):

```javascript
{
    "require": {
        "gilbitron/hoard": "~0.1.0"
    }
}
```

Run `composer install` then use as normal:

```php
require 'vendor/autoload.php';
$cache = new Hoard\Hoard();
```

## API

Hoard uses the conecpt of "drawers" (as in a chest of drawers) as drivers for caching.

```php
$cache = new Hoard\Hoard($drawer = 'file', $options = [], $args = []);
```

Possible drawers (more to come):

* `file`

`$options` is an array of options passed to the chosen drawer. See [Drawers](#drawers) section below.

`$args`:
* `encrypt_keys` - Use encrypted keys (default: `true`)
* `encryption_function` - Function to use for encrypting keys `md5`/`sha1` (default: `md5`)

---

Determine if an item exists in the cache
```php
$cache->has($key);
```
---

Retrieve an item from the cache by key
```php
$cache->get($key, $default = null);
```
---

Retrieve an item from the cache and delete it
```php
$cache->pull($key, $default = null);
```
---

Store an item in the cache. `$minutes` can be an int or DateTime
```php
$cache->put($key, $value, $minutes);
```
---

Store an item in the cache if the key does not exist. `$minutes` can be an int or DateTime
```php
$cache->add($key, $value, $minutes);
```
---

Store an item in the cache indefinitely
```php
$cache->forever($key, $value);
```
---

Remove an item from the cache
```php
$cache->forget($key);
```
---

Remove all items from the cache
```php
$cache->flush();
```

## Drawers

#### `file`
Basic FileSystem caching. 

Options:

* `cache_dir` - Path to cache directory. Uses the system tmp dir if none provided.

## Credits

Hoard was created by [Gilbert Pellegrom](http://gilbert.pellegrom.me) from [Dev7studios](http://dev7studios.com). Released under the MIT license.
