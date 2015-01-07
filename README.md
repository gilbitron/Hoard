# Hoard
Hoard is a simple, extensible PHP caching library.

## Install

Install via [composer](https://getcomposer.org):

```javascript
{
    "require": {
        "gilbitron/hoard": "~0.1"
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

`$options` is an array of options passed to the chosen drawer.

`$args`:
* `encrypt_keys` - Use encrypted keys (default: `true`)
* `encryption_function` - Function to use for encrypting keys `md5`/`sha1` (default: `md5`)

```php
$cache->has($key);
```

```php
$cache->get($key, $default = null);
```

```php
$cache->pull($key, $default = null);
```

```php
$cache->put($key, $value, $minutes);
```

```php
$cache->add($key, $value, $minutes);
```

```php
$cache->forever($key, $value);
```

```php
$cache->forget($key);
```

```php
$cache->flush();
```

## Drawers

### `file`
Basic FileSystem caching. Options:

* `cache_dir` - Path to cache dir. Uses the system tmp dir if none provided.

## Credits

Hoard was created by [Gilbert Pellegrom](http://gilbert.pellegrom.me) from [Dev7studios](http://dev7studios.com). Released under the MIT license.