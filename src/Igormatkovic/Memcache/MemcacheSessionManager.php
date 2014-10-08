<?php namespace Igormatkovic\Memcache;

class MemcacheSessionManager extends \Illuminate\Support\Manager {

    protected $handler;

    public function __construct(MemcacheHandler $handler)
    {
     	$this->handler = $handler;
    }

    /**
     * Create an instance of the database session driver.
     *
     * @return \Illuminate\Session\SessionHandlerInterface
     */
    protected function createMemcacheDriver()
    {
     	return $this->handler;
    }

    /**
     * Get the default session driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
     	return 'memcache';
    }

}
