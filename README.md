# Hippo Email Validator

This library provides a simple wrapper around the [Email Hippo](https://www.emailhippo.com/) API for validating email addresses. It allows you to verify that an address exists and meets a minimum reputation score before you accept it in your application.

## Installation

Install the package via [Composer](https://getcomposer.org/):

```bash
composer require drinks-it/hippo-email-validator
```

## Basic Usage

```php
use Nrgone\EmailHippo\Validator\Config;
use Nrgone\EmailHippo\Validator\Validator;
use Symfony\Component\HttpClient\HttpClient;
use Psr\Log\NullLogger;

$config = new Config(
    true,                              // enable validation
    'https://api.emailhippo.com/v3',   // API URL
    'your-api-key',                    // API key
    0.7,                               // minimum hippo trust score
    false                              // disable request logging
);

$validator = new Validator($config, HttpClient::create(), new NullLogger());

if ($validator->isValid('test@example.com')) {
    echo 'Email is valid';
} else {
    echo 'Invalid email';
}
```
