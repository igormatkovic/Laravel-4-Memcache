## Laravel 4 Memcache support

========



### SetUp Application

Add the package to your composer.json and run composer update.
```php
"igormatkovic/memcache": "dev-master"
```

Add the memcache service provider in app/config/app.php:

```php
'Igormatkovic\Memcache\MemcacheServiceProvider',
```

You may now update your app/config/session.php config file to use memcache

```php
	'driver' => 'memcache',
```

**OR...**

Add this to your app/start/global.php (Cache only)

```php
use Illuminate\Cache\Repository;
use Igormatkovic\Memcache\MemcacheStore;
use Igormatkovic\Memcache\MemcacheConnector;

Cache::extend('memcache', function($app) {
	$servers = Config::get('cache.memcached'); 
	$prefix = Config::get('cache.prefix'); 
				  
	$memcache = (new MemcacheConnector())->connect($servers);
			 
	return new Repository(new MemcacheStore($memcache, $prefix));
});
```


Update your driver app/config/cache.php

```php
	'driver' => 'memcache',
```
Unit test view phpunit

```php
	phpunit
```

**Notice: This memcache driver uses the same config as Memcached**


This addon was build because of the Webtatic repo lacking Memcache Support :/
