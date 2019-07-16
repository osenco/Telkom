# Sample Controller For Laravel
## The Telkom Controller
We will need a controller to handle MPesa Transactions and save them to a database table of your choice. See [this example](examples/TelkomController.php) for sample code.

```bash
php artisan make:controller TelkomController
```
or create a file called `TelkomController.php` in the `app/Http/Controllers` and copy the contents of the [sample controller](examples/TelkomController.php) into the newl created file.

### Import Class With Namespace
Put this code at the top of the controller to make the M-PESA class available for use.

```php
use Osen\Telkom\STK;
```

### Instantiating The Class
In your controller's constructor, instantiate the Telkom API class you want to use by passing configuration options like below: 

```php
STK::init(
  array(
    'env'               => 'sandbox',
    'type'              => 4,
    'shortcode'         => '174379',
    'headoffice'        => '174379',
    'key'               => 'Your Consumer Key',
    'secret'            => 'Your Consumer Secret',
    'passkey'           => 'Your Online Passkey',
    'validation_url'    => url('telkom/validate'),
    'confirmation_url'  => url('telkom/confirm'),
    'callback_url'      => url('telkom/reconcile'),
    'results_url'       => url('telkom/results'),
    'timeout_url'       => url('telkom/timeout'),
  )
);
```

## Routing and Endpoints

You can set your Laravel routes so as to create endpoints for interaction between Telkom and your Laravel installation. Remember to call the respective actions (Telkom methods) inside your controller methods.

```php
Route::prefix('telkom')->group(function ()
{
  Route::any('pay', 'TelkomController@pay');
  Route::any('validate', 'TelkomController@validation');
  Route::any('confirm', 'TelkomController@confirmation');
  Route::any('results', 'TelkomController@results');
  Route::any('register', 'TelkomController@register');
  Route::any('timeout', 'TelkomController@timeout');
  Route::any('reconcile', 'TelkomController@reconcile');
});
```

### CSRF verification
Remember to add `telkom/*` to the `$except` array in `app/Http/Middleware/VerifyCsrfToken.php` to whitelist your endpoints so they can receive data from Telkom.


See [the README](README.md) for making and processing payment requests.
