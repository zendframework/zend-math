# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

## 3.1.1 - 2018-07-10

### Added

- Nothing.

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [#34](https://github.com/zendframework/zend-math/pull/34) fixes the docblock for `Rand::getFloat` to indicate that the bottom boundary
  can include 0.

- [#36](https://github.com/zendframework/zend-math/pull/36) removes all references to ircmaxell/random-lib from the component. While
  it was no longer used internally, references still existed that caused confusion for
  some users.

## 3.1.0 - 2018-04-26

### Added

- [#31](https://github.com/zendframework/zend-math/pull/31) adds support for PHP 7.1 and 7.2.

### Changed

- [#33](https://github.com/zendframework/zend-math/pull/33) modifies the `Bcmath` BigInteger class to no longer change the global
  `bcscale`, but instead send the `0` scale value explicitly to each bcmath operation. This prevents
  side effects when using bcmath in other scenarios.

- [#29](https://github.com/zendframework/zend-math/pull/29) modifies how caught exceptions are re-thrown; all such cases now provide
  the original exception as the previous exception.

### Deprecated

- Nothing.

### Removed

- [#31](https://github.com/zendframework/zend-math/pull/31) removes support for PHP 5.5.

- [#31](https://github.com/zendframework/zend-math/pull/31) removes support for HHVM.

### Fixed

- Nothing.

## 3.0.0 - 2016-04-28

This version contains a number of changes to required dependencies, error
handling, and internals; please read [the migration document](docs/book/migration.md)
for full details.

### Added

- [#18](https://github.com/zendframework/zend-math/pull/18) adds a requirement
  on `ext/mbstring`.
- [#18](https://github.com/zendframework/zend-math/pull/18) adds a requirement
  on `paragonie/random_compat` for polyfilling PHP 7's `random_bytes()` and
  `random_int()` functionality.
- [#20](https://github.com/zendframework/zend-math/pull/20) prepares and
  publishes documentation to https://zendframework.github.io/zend-math/

### Deprecated

- Nothing.

### Removed

- [#18](https://github.com/zendframework/zend-math/pull/18) removes the
  `$strong` optional parameter from the following methods, as the component now
  ensures a cryptographically secure pseudo-random number generator is always
  used:
  - `Rand::getBytes($length)`
  - `Rand::getBoolean()`
  - `Rand::getInteger($min, $max)`
  - `Rand::getFloat()`
  - `Rand::getString($length, $charlist = null)`
- [#18](https://github.com/zendframework/zend-math/pull/18) removes the
  requirement on ircmaxell/random-lib, in favor of paragonie/random_compat (as
  noted above); this also resulted in the removal of:
  - direct usage of mcrypt (this is delegated to paragonie/random_compat)
  - direct usage of `/dev/urandom` or `COM` (this is delegated to
    `random_bytes()` and/or paragonie/random_compat)
  - `Zend\Math\Source\HashTiming`, as it was used only with `RandomLib`.

### Fixed

- [#18](https://github.com/zendframework/zend-math/pull/18) updates the code to
  replace usage of `substr()` and `strlen()` with `mb_substr()` and
  `mb_strlen()`; these ensure that all string manipulations within the component
  remain binary safe.

## 2.7.0 - 2016-04-07

### Added

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [#16](https://github.com/zendframework/zend-math/pull/16) updates
  `Zend\Math\Rand` to use PHP 7's `random_bytes()` and `random_int()` or mcrypt
  when detected, and fallback to `ircmaxell/RandomLib` otherwise, instead of using
  openssl. This provides more cryptographically secure pseudo-random generation.


## 2.6.0 - 2016-02-02

### Added

- Nothing.

### Deprecated

- Nothing.

### Removed

- [#5](https://github.com/zendframework/zend-math/pull/5) removes
  `Zend\Math\BigInteger\AdapterPluginManager`, and thus the zend-servicemanager
  dependency. Essentially, no other possible plugins are likely to ever be
  needed outside of those shipped with the component, so using a plugin manager
  was overkill. The functionality for loading the two shipped adapters has been

### Fixed

- Nothing.

## 2.5.2 - 2015-12-17

### Added

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [#7](https://github.com/zendframework/zend-math/pull/7) fixes how base
  conversions are accomplished within the bcmath adapter, ensuring PHP's native
  `base_convert()` is used for base36 and below, while continuing to use the
  base62 alphabet for anything above.
