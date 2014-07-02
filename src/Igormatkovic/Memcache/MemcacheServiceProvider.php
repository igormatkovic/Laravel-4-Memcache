<?php namespace Igormatkovic\Memcache;

use Illuminate\Support\ServiceProvider;

use Illuminate\Cache\Repository;
use Igormatkovic\Memcache\MemcacheStore;
use Igormatkovic\Memcache\MemcacheConnector;

class MemcacheServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;


	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->extendCacheDriver();
	}
	
	private function extendCacheDriver()
    {
        $cache = $this->app['cache'];
 
        $cache->extend('memcache', function($app) {
        	$servers = $app['config']->get('cache.memcached'); 
			  $prefix = $app['config']->get('cache.prefix'); 
			  
			  $memcache = (new MemcacheConnector())->connect($servers);
		 
		      return new Repository(new MemcacheStore($memcache, $prefix));
        });
    }

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('memcache');
	}

}
