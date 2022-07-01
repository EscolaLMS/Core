# Core

[![codecov](https://codecov.io/gh/EscolaLMS/Core/branch/main/graph/badge.svg?token=SBAWWVF5QX)](https://codecov.io/gh/EscolaLMS/Core) 
[![phpunit](https://github.com/EscolaLMS/Core/actions/workflows/test.yml/badge.svg)](https://github.com/EscolaLMS/Core/actions/workflows/test.yml)
[![downloads](https://img.shields.io/packagist/dt/escolalms/core)](https://packagist.org/packages/escolalms/core)
[![downloads](https://img.shields.io/packagist/v/escolalms/core)](https://packagist.org/packages/escolalms/core)
[![downloads](https://img.shields.io/packagist/l/escolalms/core)](https://packagist.org/packages/escolalms/core)
[![Maintainability](https://api.codeclimate.com/v1/badges/382375bb6a8ee96d9875/maintainability)](https://codeclimate.com/github/EscolaLMS/Core/maintainability)
[![swagger](https://img.shields.io/badge/documentation-swagger-green)](https://escolalms.github.io/Core/)
[![Mutation testing badge](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2FEscolaLMS%2FCore%2Fmain)](https://dashboard.stryker-mutator.io/reports/github.com/EscolaLMS/Core/main)

Escola LMS Core Package

Package contains all basic classes and features used in Escola LMS, and it's obligatory to work with any other LMS package.

## Repositories

Package contains `EscolaLms\Core\Repositories\BaseRepository` class, that should extend all other repositories.

For searching and filtering data you may use Criteria array, basic criteria are also provided in this package.

```php
// TODO: Example of Criteria usage
```

## Users

User model and migrations provided by this package are the simplest user model that may be used in LMS.
In the real app, you should setup your own guard, that may extend `EscolaSoft\EscolaLms\Models\User` and use user traits from other packages.
