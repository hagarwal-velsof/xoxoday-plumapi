**Plum Package**

## What is Plum API?

This package takes care of calling the Plum APIs like Create Order (Paytm cashback).

## Installation

Require this package with composer. 

```sh
$ composer require xoxoday/plumapi
```

## Publish package

Create config/xoplum.php and Jobs/PlumOrder.php files using the following artisan command:

```sh
$ php artisan vendor:publish  --tag="Plum_files"
```

## Complete configuration

### Set your credentials

Open config/xoplum.php configuration file and set your credentials:

```php

return [
    'xoplum_client_id' => env('xoplum_client_id', 'Set your Client ID'),
    'xoplum_client_secret' => env('xoplum_client_secret', 'Set your Client Secret'),
    'xoplum_env' => env('xoplum_env', 'sandbox'),   //change sandbox to production in case of production site 
    'xoplum_sandbox_url' => env('xoplum_sandbox_url', 'https://stagingaccount.xoxoday.com/chef/v1/'),
    'xoplum_production_url' => env('xoplum_production_url', 'https://accounts.xoxoday.com/chef/v1/'),
    'xoplum_product_id' => env('xoplum_product_id', 'Set Product ID'),
];


```

## Database table migration

Create xoplum_api_credentials and xoplum_orders tables in your database.

```sh

$ php artisan migrate

```

### Set your reference key

Check for xoplum_api_credentials table in your database. Set your credentials

|      id         |               key             |        value           |       created_at        |        updated_at       |
| --------------- | ----------------------------- | ---------------------- | ----------------------- | ----------------------- | 
|       1         |           refresh_token       | set your refresh token |                         |                         |
|       2         |           access_token        |                        |                         |                         |



## How to use

Refer code from the sample.php file and use the functionality of the package.