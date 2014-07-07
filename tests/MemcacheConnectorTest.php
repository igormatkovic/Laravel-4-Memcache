<?php

use Mockery as m;

class MemcacheConnectorTest extends PHPUnit_Framework_TestCase {

    public function tearDown()
    {
        m::close();
    }


    public function testServersAreAddedCorrectly()
    {
        $connector = $this->getMock('Igormatkovic\Memcache\MemcacheConnector', array('getMemcache'));
        $memcache = m::mock('stdClass');
        $memcache->shouldReceive('addServer')->once()->with('localhost', 11211, 100);
        $memcache->shouldReceive('getVersion')->once()->andReturn(true);
        $connector->expects($this->once())->method('getMemcache')->will($this->returnValue($memcache));
        $result = $connector->connect(array(array('host' => 'localhost', 'port' => 11211, 'weight' => 100)));

        $this->assertTrue($result === $memcache);
    }


    /**
     * @expectedException RuntimeException
     */
    public function testExceptionThrownOnBadConnection()
    {
        $connector = $this->getMock('Igormatkovic\Memcache\MemcacheConnector', array('getMemcache'));
        $memcache = m::mock('stdClass');
        $memcache->shouldReceive('addServer')->once()->with('localhost', 11211, 100);
        $memcache->shouldReceive('getVersion')->once()->andReturn(false);
        $connector->expects($this->once())->method('getMemcache')->will($this->returnValue($memcache));
        $result = $connector->connect(array(array('host' => 'localhost', 'port' => 11211, 'weight' => 100)));
    }

}