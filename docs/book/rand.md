# Random Number Generation

`Zend\Math\Rand` implements a random number generator that is able to generate
random numbers for general purpose usage and for cryptographic scopes. To
generate good random numbers, this component uses different approaches. If PHP 7
is running, we use the cryptographically secure pseudo-random functions
[random_bytes()](http://php.net/random-bytes) and
[random_int()](http://php.net/random-int).

For PHP 5 versions, we use [paragonie/random_compat](https://github.com/paragonie/random_compat),
which delegates to the [Mcrypt](http://php.net/mcrypt) extension or a
`/dev/urandom` or similar source.  If you don't have a secure random source in
your environment, the functionality will raise an exception, providing hints
regarding extensions it can use.

## Methods available

The `Zend\Math\Rand` class offers the following methods to generate random values:

- `getBytes($length)` to generate a random set of `$length` bytes;
- `getBoolean()` to generate a random boolean value (true or false);
- `getInteger($min, $max)` to generate a random integer between `$min` and `$max`;
- `getFloat()` to generate a random float number between 0 and 1;
- `getString($length, $charlist = null)` to generate a random string of $length
  characters using the alphabet `$charlist`; if not provided, the default alphabet is the
  [Base64](http://en.wikipedia.org/wiki/Base64) character set.

## Examples

The example below demonstrates generating random data using `Zend\Math\Rand`:

```php
use Zend\Math\Rand;

$bytes = Rand::getBytes(32);
printf("Random bytes (in Base64): %s\n", base64_encode($bytes));

$boolean = Rand::getBoolean();
printf("Random boolean: %s\n", $boolean ? 'true' : 'false');

$integer = Rand::getInteger(0, 1000);
printf("Random integer in [0-1000]: %d\n", $integer);

$float = Rand::getFloat();
printf("Random float in [0-1): %f\n", $float);

$string = Rand::getString(32, 'abcdefghijklmnopqrstuvwxyz');
printf("Random string in latin alphabet: %s\n", $string);
```
