<?php


namespace Mrpvision\Gluu\Tests;

use Mrpvision\Gluu\Exception\SessionException;
use PHPUnit\Framework\TestCase;
use Mrpvision\Gluu\Session;

class SessionTest extends TestCase
{
    /** @var array */

    private $data = [];
	private $session;
	
	public function __construct() {
		if (!isset($_SESSION)) {
            session_start();
        }
        parent::__construct();
		$this->session = new Session();
    }
    /**
     * Get value, delete key.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function take($key)
    {
        $value = $this->session->take($key);

        return $value;
    }

    /**
     * Set key to value.
     *
     * @param string $key
     * @param mixed  $value
     */
    public function set($key, $value)
    {
        $this->session->set($key,$value);
    }
	 
	public function testSet()
    {
        $this->set('test','Test Value');
		 $this->assertTrue(true);
    }
	/**
     * @depends testSet
     */
	 
	 public function testTake()
    {
		$this->assertEquals($this->take('test'), 'Test Value');
    }
}
