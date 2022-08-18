# Changelog

All notable changes to this project will be documented in this file. See [standard-version](https://github.com/conventional-changelog/standard-version) for commit guidelines.

## [2.0.0](https://github.com/jetimob/http-php-laravel/compare/v1.4.0...v2.0.0) (2022-08-18)


### ⚠ BREAKING CHANGES

* bump to php 8

### Bug Fixes

* throw RuntimeException when the request expects authz ([5d9e304](https://github.com/jetimob/http-php-laravel/commit/5d9e3045df422a0cc6f8b96683fd79cf0f7b2fbd))


### deps

* bump to php 8 ([da4dadd](https://github.com/jetimob/http-php-laravel/commit/da4dadd042219b2934ca1b4156069637d59f8e81))

## [1.4.0](https://github.com/jetimob/http-php-laravel/compare/v1.3.3...v1.4.0) (2022-04-01)


### Features

* add method to mock guzzle client responses ([2eef615](https://github.com/jetimob/http-php-laravel/commit/2eef6151afb1439a1a660cc381f1f588887385af))
* expose http on AbstractApi ([1eeb90e](https://github.com/jetimob/http-php-laravel/commit/1eeb90e69d499bdcee1a7e1d3e70641452f40f5c))
* modifica a hidratação de array por propriedade ([a67f44b](https://github.com/jetimob/http-php-laravel/commit/a67f44bda4b49f29fb08019242cbed915328c28b))

### [1.3.3](https://github.com/jetimob/http-php-laravel/compare/v1.3.2...v1.3.3) (2021-11-30)


### Bug Fixes

* add default value to deserialized scope property ([a22de0f](https://github.com/jetimob/http-php-laravel/commit/a22de0ffd21969d682b8facc2c8ac95562e4b847))

### [1.3.2](https://github.com/jetimob/http-php-laravel/compare/v1.3.1...v1.3.2) (2021-07-30)


### Bug Fixes

* add OAuth scope when making an access token request ([8c9bac3](https://github.com/jetimob/http-php-laravel/commit/8c9bac3ee1c4b1ecf93c5a55d3fa21fbc0f22490))

### [1.3.1](https://github.com/jetimob/http-php-laravel/compare/v1.3.0...v1.3.1) (2021-07-23)


### Bug Fixes

* add array item that doesn't require serialization into final array ([0633f0c](https://github.com/jetimob/http-php-laravel/commit/0633f0ce4f87025eb9976ebb22a53a27a022b2cd))
* use `toArray` instead of array casting ([ccb2658](https://github.com/jetimob/http-php-laravel/commit/ccb2658c4cd3bdfbdef6bb500fc4910628774899))

## [1.3.0](https://github.com/jetimob/http-php-laravel/compare/v1.2.0...v1.3.0) (2021-07-20)


### Features

* update OAuthTokenResolver to be in accord with RFC 6749 ([333bf36](https://github.com/jetimob/http-php-laravel/commit/333bf36dc44f6e4c5c364e0a40b38f765b8d2157))

## [1.2.0](https://github.com/jetimob/http-php-laravel/compare/v1.1.1...v1.2.0) (2021-07-14)


### Features

* add AbstractApi helper ([9e5c1ea](https://github.com/jetimob/http-php-laravel/commit/9e5c1eacac86722345e510a962d2f7678f71af97))

### [1.1.1](https://github.com/jetimob/http-php-laravel/compare/v1.1.0...v1.1.1) (2021-07-12)


### Bug Fixes

* automatically obtain a new access token if there is no refresh token ([98989c5](https://github.com/jetimob/http-php-laravel/commit/98989c56ff13b6c6b86a2d3e65c9aa563931040b))

## [1.1.0](https://github.com/jetimob/http-php-laravel/compare/v0.2.0...v1.1.0) (2021-07-12)


### Features

* add option to "authorization_header_bearer_token" resolve token by class ([61b43d9](https://github.com/jetimob/http-php-laravel/commit/61b43d9ab600e851efc8e48743f48e6cd995eb94))
* add response serialization ([81d2241](https://github.com/jetimob/http-php-laravel/commit/81d2241fa3cb765e488387b41a22314b8d093a42))


### Bug Fixes

* remove exception typing ([15e28a4](https://github.com/jetimob/http-php-laravel/commit/15e28a4cfc3795484866ef8e132647b44f100c29))
* remove unintended return from within foreach ([d7666c9](https://github.com/jetimob/http-php-laravel/commit/d7666c9df6f767c482c463bff76e3770c7637dbf))
