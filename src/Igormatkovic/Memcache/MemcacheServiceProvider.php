<?php namespace Igormatkovic\Memcache;


use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\Repository;

class MemcacheServiceProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->resolving('cache', function($cache)
        {
            $cache->extend('memcache', function($app)
            {
                $servers = $app['config']['cache.memcached'];
                $prefix = $app['config']['cache.prefix'];
                $memcache = new MemcacheConnector();
                $memcache->connect($servers);
                return new Repository(new MemcacheStore($memcache, $prefix));
            });
        });
        
        $this->app->resolving('session', function($session)
        {
            $session->extend('memcache', function($app)
            {
                // $manager = new SessionManager($app);
                
                $minutes = $app['config']['session.lifetime'];
                return new MemcacheHandler($app['cache']->driver('memcache'), $minutes);
            });
        });
    }

}
