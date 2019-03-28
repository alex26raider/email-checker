# Validate Email for Laravel

### Features

This package supports:

*   Validate with SMTP
*   Support for Disposable Email


**Notice** -  That extracts the MX records from the email address and connect with the mail server to make sure the mail address accurately exist. So it may be slow loading time in local and some co-operate MX records take a long time .  


### Quick Installation

```
composer require tintnaingwin/email-checker
```

For laravel >=5.5 that's all. This package supports Laravel new [Package Discovery](https://laravel.com/docs/5.5/packages#package-discovery).

If you are using Laravel < 5.5, you also need to add the service provider class to your project's `config/app.php` file:

##### Service Provider
```php
Alexpriftuli\EmailChecker\EmailCheckerServiceProvider::class,
```

##### Facade
```php
'EmailChecker' => Alexpriftuli\EmailChecker\Facades\EmailChecker::class,
```

#### Example
To add 'email_checker' at email rule
```php
  // [your site path]/app/Http/Requests/RegisterRequest.php
 public function rules()
     {
         return [
             'name'  => 'required|max:255',
             'email' => 'bail|required|email|max:255|unique:users|email_checker',
             'password' => 'bail|required|min:6|confirmed',
         ];
     }
```

#### Example Usage With Facade
 
 Return 1 if success, otherwise returns the error code of the server answered 
 (it returns 0 if it's a generic error)
 ```php
 // reture int
 EmailChecker::check('me@example.com');
```

## Testing

You can run the tests with:

```bash
vendor/bin/phpunit
```

### Credit
  - Disposable Email List
    * [Ilya Volodarsky](https://github.com/ivolo/disposable-email-domains/blob/master/index.json)

### License

The MIT License (MIT). Please see [License File](https://github.com/tintnaingwinn/email-checker/blob/master/LICENSE.txt) for more information.


[ico-version]: https://img.shields.io/packagist/v/tintnaingwin/email-checker.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/tintnaingwin/email-checker.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/tintnaingwin/email-checker
[link-downloads]: https://packagist.org/packages/tintnaingwin/email-checker
[link-author]: https://github.com/tintnaingwinn
