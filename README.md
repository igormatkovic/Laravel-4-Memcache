## Laravel 4 Memcache support

========



### SetUp Application

**Composer**
```php
"igormatkovic/memcache": "dev-master"
```




**Add this to your app/start/global.php**

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


**Update your driver app/config/cache.php**

```php
	'driver' => 'memcache',
```


**Notice: This memcache driver used the same config as Memcached**


This addon was build because of the Webtatic repo lacking Memcached Support :/