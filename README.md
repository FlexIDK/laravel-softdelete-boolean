# Laravel 9 SoftDeletes Boolean

This package is mostly for high load apps. It will speed up queries with soft deletes.
Boolean field is much better for indexing instead of unique timestamps.

## Install

Via Composer

``` bash
$ composer require one23/laravel-softdeletes-boolean
```

Add `One23\LaravelSoftDeletesBoolean\SoftDeletesBoolean` trait to models with soft deletes.

Then create and run migration to add soft delete boolean field
```php
Schema::table('users', function (Blueprint $table) {
    $table->boolean('is_deleted')->default(0)->index();
});
```

If you want to use this package for existing project you can use built-in command
```dotenv
php artisan softdeletes-boolean:migrate
```

Also you can change default column name `is_deleted` to any other by setting static property `IS_DELETED`of certain model

## Versions compatibility

``` bash
For Laravel 9 - laravel-softdeletes-boolean v1.*
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Security

If you discover any security related issues, please email eugene@krivoruchko.info instead of using the issue tracker.

## Credits

- [Eugene Krivoruchko][link-author]
- [Ivan Kolodiy][link-fork]


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[link-packagist]: https://packagist.org/packages/tenantcloud/laravel-boolean-softdeletes
[link-downloads]: https://packagist.org/packages/tenantcloud/laravel-boolean-softdeletes
[link-author]: https://github.com/FlexIDK/
[link-fork]: https://github.com/ivankolodii
