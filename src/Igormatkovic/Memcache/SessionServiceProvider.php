<?php namespace Igormatkovic\Memcache;


use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\Repository;
use Igormatkovic\Memcache\MemcacheStore;
use Igormatkovic\Memcache\MemcacheConnector;

class SessionServiceProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
    {
        $this->app->resolving('cache', function($cache)
        {
            $servers = Config::get('cache.memcached');
            $prefix = Config::get('cache.prefix');
            $memcache = new MemcacheConnector();
            $memcache->connect($servers);
            return new Repository(new MemcacheStore($memcache, $prefix));
        /*
            $cache->extend('memcache', function($app)
            {
                $manager = new CachenManager($app);

                return $manager->driver('memcache');
            });
        */
        });
        
        $this->app->resolving('session', function($session)
        {
            $session->extend('memcache', function($app)
            {
                $manager = new \SessionManager($app);

                return $manager->driver('memcache');
            });
        });
    }

}
