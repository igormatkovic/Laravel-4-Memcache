<?php namespace Igormatkovic\Memcache;


use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\Repository;
use Illuminate\Cache\CacheManager;

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
     	$servers = $this->app['config']['cache.memcached'];
        $prefix = $this->app['config']['cache.prefix'];
        $minutes = $this->app['config']['session.lifetime'];

        $memcache = new MemcacheConnector();
        $memcache = $memcache->connect($servers);
        $repo = new Repository(new MemcacheStore($memcache, $prefix));
        $handler = new MemcacheHandler($repo, $minutes);

        $this->app->resolving('cache', function($cache) use ($repo)
        {
            $cache->extend('memcache', function($app) use ($repo)
            {
             	return $repo;
            });
        });

        $this->app->resolving('session', function($session) use ($handler)
    	{
            $session->extend('memcache', function($app) use ($handler)
            {
                $manager = new MemcacheSessionManager($handler);

                return $manager->driver('memcache');
            });
        });
    }
}
