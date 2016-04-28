# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

## 3.0.0 - 2016-04-28

This version contains a number of changes to required dependencies, error
handling, and internals; please read [the migration document](doc/book/migration.md)
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
