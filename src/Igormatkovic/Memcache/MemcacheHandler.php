<?php namespace Igormatkovic\Memcache;

use Illuminate\Cache\Repository;

class MemcacheHandler implements \SessionHandlerInterface {

    /**
     * Create a new cache driven handler instance.
     *
     * @param  \Illuminate\Cache\Repository  $cache
     * @param  int  $minutes
     * @return void
     */
    public function __construct(Repository $cache, $minutes)
    {
        $this->cache = $cache;
        $this->minutes = $minutes;
    }

    public function open($savePath, $sessionName)
    {
        return true;
    }
    
    public function close()
    {
        return true;
    }
    
    public function read($sessionId)
    {
        return $this->cache->get($sessionId) ?: '';
    }
    
    public function write($sessionId, $data)
    {
        return $this->cache->put($sessionId, $data, $this->minutes);
    }
    
    public function destroy($sessionId)
    {
        return $this->cache->forget($sessionId);
    }
    
    public function gc($lifetime)
    {
        return true;
    }
    
    /**
     * Get the underlying cache repository.
     *
     * @return \Illuminate\Cache\Repository
     */
    public function getCache()
    { 
        return $this->cache;
    }

}
