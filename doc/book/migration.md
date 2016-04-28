# Migration Guide

In v3 we changed the random number generator strategy of `Zend\Math\Rand` using
the `random_int()` ad `random_bytes()` functions of PHP 7. For PHP 5.5+ we used
the library [random_compact](https://github.com/paragonie/random_compat) that is
a polyfill for the previous PHP 7 functions.


## Mbstring extension required

In v3 we required the [mbstring](http://php.net/manual/en/book.mbstring.php)
extension in composer.json. We added this requirement because we want to be sure
that the string manipulations inside zend-math are binary safe.

We basically removed all the `strlen()` and `substr()` functions with the
equivalent `mb_strlen()` and `mb_substr()` functions using the `8bit` encoding
string.

## We removed the $strong optional parameter

In `Zend\Math\Rand` we removed the usage of the `$strong` optional paramter for the
random numbers generator. By default, all the random numbers of v3 will use
a secure pseudo-random number generator ([CSPRNG](https://en.wikipedia.org/wiki/Cryptographically_secure_pseudorandom_number_generator)).

These are the new function interfaces were we removed the parameter:

- `Rand::getBytes($length)`
- `Rand::getBoolean()`
- `Rand::getInteger($min, $max)`
- `Rand::getFloat()`
- `Rand::getString($length, $charlist = null)`

This a BC break for v2 code and you need to remove the usage of the `$strong`
parameter in your code.

## We changed the errors management in Rand

We removed the return `false` in `getBytes($length)` if `$length <= 0`.
Now the code throws an `Zend\Math\Exception\DomainException exception.

We added the `Zend\Math\Exception\InvalidArgumentException` in `getBytes($length)`
if the `$length` parameter is not valid.

We added the `Zend\Math\Exception\InvalidArgumentException` in `getInteger($min, $max)`
if `$min` or `$max` parameter is not valid.

In the case where you are not using PHP 7 and your PHP environment does not
provide a secure random source we throw a `Zend\Math\Exception\RuntimeException`
with the following message:

> This PHP environment doesn't support secure random number generation.
> Please consider upgrading to PHP 7

This message should appear if your are using PHP < 7 on a Windows machine
without one of the following extension/library installed:

- [Mcrypt](http://php.net/manual/en/book.mcrypt.php);
- [libsodium](https://pecl.php.net/package/libsodium);
- [CAPICOM](https://en.wikipedia.org/wiki/CAPICOM);
- [OpenSSL](http://php.net/manual/en/book.openssl.php).

You must be awared of these Exception changes to migrate your code from v2 to v3.
