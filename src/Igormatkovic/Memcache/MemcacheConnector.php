<?php namespace Igormatkovic\Memcache;

use Memcache;

class MemcacheConnector {

	/**
	 * Create a new Memcache connection.
	 *
	 * @param array  $servers
	 * @return \Memcache
	 *
	 * @throws \RuntimeException
	 */
	public function connect(array $servers)
	{
		$memcached = $this->getMemcache();

		// For each server in the array, we'll just extract the configuration and add
		// the server to the Memcached connection. Once we have added all of these
		// servers we'll verify the connection is successful and return it back.
		foreach ($servers as $server)
		{
			$memcached->addServer(
				$server['host'], $server['port'], $server['weight']
			);
		}

		if ($memcached->getVersion() === false)
		{
			throw new \RuntimeException("Could not establish Memcache connection.");
		}

		return $memcached;
	}

	/**
	 * Get a new Memcached instance.
	 *
	 * @return \Memcached
	 */
	protected function getMemcache()
	{
		return new Memcache;
	}

}
