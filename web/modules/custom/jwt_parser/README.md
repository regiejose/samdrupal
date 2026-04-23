# JWT Parser Module

## Overview

The **JWT Parser** module provides basic functionality to parse and decode JSON Web Tokens (JWT) within Drupal. It also supports verification when used with an external JWT library.

---

## Requirements

This module requires the external PHP library:

```
firebase/php-jwt
```

---

## Installation

### 1. Install the module

Place the module in your Drupal installation:

```
modules/custom/jwt_parser
```

---

### 2. Install dependency via Composer

You must manually install the required JWT library using Composer:

```
composer require firebase/php-jwt:^7.0
```

> This step is required because Drupal modules do not automatically install Composer dependencies at runtime.

---

### 3. Enable the module

```
drush en jwt_parser -y
```

or enable it via the Drupal admin UI.

---

## Configuration

Default configuration is stored in:

```
jwt_parser.settings
```

---

## Usage Example

```php
$parser = \Drupal::service('jwt_parser.service');

// Decode only (no verification)
$decoded = $parser->decode($jwt);

// Verify token (requires firebase/php-jwt)
$verified = $parser->verify($jwt);
```

---

## Uninstall

To uninstall the module:

```
drush pm:uninstall jwt_parser
```

If you also want to remove the JWT library (only if no other project depends on it):

```
composer remove firebase/php-jwt
```

---

## Notes

* The module can parse JWTs without external libraries.
* Signature verification requires `firebase/php-jwt`.
* Composer dependency management must be handled at the project level, not during module install/uninstall.

---

## License

GPL-2.0-or-later
