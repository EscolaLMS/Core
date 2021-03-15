# Core

[![codecov](https://codecov.io/gh/EscolaLMS/Core/branch/main/graph/badge.svg?token=SBAWWVF5QX)](https://codecov.io/gh/EscolaLMS/Core) 
[![phpunit](https://github.com/EscolaLMS/Core/actions/workflows/test.yml/badge.svg)](https://github.com/EscolaLMS/Core/actions/workflows/test.yml)

[![swagger](https://validator.swagger.io/validator?url=https%3A%2F%2Fescolalms.github.io%2FCore%2Fapis%2Fopenapi.json)](https://escolalms.github.io/Core/)


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
