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
        $isCacheDriver = $this->app['config']['cache.driver'] == 'memcache';
        $servers = $this->app['config']['cache.memcached'];
        $prefix = $this->app['config']['cache.prefix'];
        $isSessionDriver = $this->app['config']['session.driver'] == 'memcache';
        $minutes = $this->app['config']['session.lifetime'];
        $memcache = $repo = $handler = $manager = $driver = null;

        if($isCacheDriver) {
            $memcache = new MemcacheConnector();
            $memcache = $memcache->connect($servers);
            $repo = new Repository(new MemcacheStore($memcache, $prefix));

            $this->app->resolving('cache', function($cache) use ($repo)
            {
                $cache->extend('memcache', function($app) use ($repo)
                {
                     return $repo;
                });
            });

            if($isSessionDriver) {
                $handler = new MemcacheHandler($repo, $minutes);
                $manager = new MemcacheSessionManager($handler);
                $driver = $manager->driver('memcache');

                $this->app->resolving('session', function($session) use ($driver)
                {
                    $session->extend('memcache', function($app) use ($driver)
                    {
                        return $driver;
                    });
                });
            }
        }
    }
}
