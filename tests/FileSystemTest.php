<?php

class FileSystemTest extends PHPUnit_Framework_TestCase {

	private $cache_folder;
	private $cache;

	public function setup()
	{
		$this->cache_folder = __DIR__ .'/cache';
		$this->cache = new Hoard\Hoard('file', [
			'cache_dir' => $this->cache_folder
		]);

		// Empty cache folder
		$files = glob($this->cache_folder .'/*');
		foreach ($files as $file) {
			if (is_file($file)) {
				unlink($file);
			}
		}
	}

	public function testHas()
	{
		$this->cache->put('testput', 'example', 10);
		$result1 = $this->cache->has('testput');
		$result2 = $this->cache->has('nothing');

		$this->assertTrue($result1, 'cache file exists');
		$this->assertFalse($result2, 'cache file does not exist');
	}

	public function testGet()
	{
		$this->cache->put('testput', 'example', 10);
		$result = $this->cache->get('testput');

		$this->assertEquals('example', $result, 'get returns correct content');
	}

	public function testPull()
	{
		$this->cache->put('testput', 'example', 10);
		$result = $this->cache->pull('testput');

		$this->assertEquals('example', $result, 'pull returns correct content');
		$this->assertFileNotExists($this->cache_folder .'/'. md5('testput'), 'cache file does not exist');
	}

	public function testPut()
	{
		$this->cache->put('testput', 'example', 10);

		$this->assertFileExists($this->cache_folder .'/'. md5('testput'), 'cache file exists');
	}

	public function testAdd()
	{
		$this->cache->add('testadd', 'example', 10);
		$this->assertFileExists($this->cache_folder .'/'. md5('testadd'), 'cache file exists');

		$this->cache->add('testadd', 'another example', 10);
		$result = $this->cache->get('testadd');
		$this->assertEquals('example', $result, 'get returns correct content');
	}

	public function testForever()
	{
		$this->cache->forever('testforever', 'example');

		$this->assertFileExists($this->cache_folder .'/'. md5('testforever'), 'cache file exists');
	}

	public function testForget()
	{
		$this->cache->put('testput', 'example', 10);
		$this->cache->forget('testput');

		$this->assertFileNotExists($this->cache_folder .'/'. md5('testput'), 'cache file does not exist');
	}

	public function testFlush()
	{
		$this->cache->put('test1', 'example', 10);
		$this->cache->put('test2', 'example', 10);
		$this->cache->put('test3', 'example', 10);
		$this->cache->flush();

		$this->assertFileNotExists($this->cache_folder .'/'. md5('test1'), 'cache file (test1) does not exist');
		$this->assertFileNotExists($this->cache_folder .'/'. md5('test2'), 'cache file (test2) does not exist');
		$this->assertFileNotExists($this->cache_folder .'/'. md5('test3'), 'cache file (test3) does not exist');
	}

}
