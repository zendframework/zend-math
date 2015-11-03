# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

## 3.0.0 - TBD

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
  moved into the internals of `Zend\Math\BigInteger\BigInteger`.

### Fixed

- Nothing.
