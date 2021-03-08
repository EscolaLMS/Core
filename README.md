# Core
Escola LMS Core Package

Package contains all basic classes and features used in Escola LMS, and it's obligatory to work with any other LMS package.

## Users

User model and migrations provided by this package are the simplest user model that may be used in LMS.
In the real app, you should setup your own guard, that may extend `EscolaSoft\EscolaLms\Models\User` and use user traits from other packages.