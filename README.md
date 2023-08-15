

This package is a custom Wia driver for Laravel Socialite.

## Installation

You can install the package via composer:

```bash
composer require shahzada-saeed/laravel-socialize-wia
```

## Usage
Once you install the package, add the next config values in you `config/services.php` configuration file:

```php
'cognito' => [
    'base_uri' => env('WIA_URI'),
    'client_id' => env('WIA_CLIENT_ID'),
    'client_secret' => env('WIA_CLIENT_SECRET'),
    'redirect' => env('WIA_REDIRECT_URI'),
],
```

Then, you can use the driver as you would use it in the Laravel Socialite's official [documentation](https://laravel.com/docs/8.x/socialite). Use `cognito` keyword when you want to instantiate the driver:

```php
$user = Socialite::driver('wia')->user();
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
