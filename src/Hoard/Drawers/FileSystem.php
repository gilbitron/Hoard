<?php namespace Hoard\Drawers;

use Hoard\Interfaces\Drawer;

class FileSystem extends Drawer {

	/**
	 * Array of FileSystem specific options.
	 */
	protected $options = [
		'cache_dir' => ''
	];

	/**
	 * Constructor for the FileSystem drawer.
	 *
	 * @param array $options
	 */
	public function __construct($options = [])
	{
		parent::__construct($options);

		if (!is_dir($this->options['cache_dir'])){
			$this->options['cache_dir'] = $this->getDefaultDirectory();
		}
		$this->options['cache_dir'] = rtrim($this->options['cache_dir'], '/') .'/';
	}

	/**
	 * Determine if an item exists in the cache.
	 *
	 * @param string $key
	 * @return bool
	 */
	public function has($key)
	{
		if (file_exists($this->options['cache_dir'] . $key)) {
			$data = file_get_contents($this->options['cache_dir'] . $key);
			list($expiry, $serialized_data) = explode('::', $data, 2);
			if(time() < $expiry){
				return true;
			} else {
				unlink($this->options['cache_dir'] . $key);
			}
		}

		return false;
	}

	/**
	 * Retrieve an item from the cache by key.
	 *
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function get($key, $default = null)
	{
		if (file_exists($this->options['cache_dir'] . $key)) {
			$data = file_get_contents($this->options['cache_dir'] . $key);
			list($expiry, $serialized_data) = explode('::', $data, 2);
			if(time() < $expiry){
				return unserialize($serialized_data);
			} else {
				unlink($this->options['cache_dir'] . $key);
			}
		}

		return $default;
	}

	/**
	 * Retrieve an item from the cache and delete it.
	 *
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function pull($key, $default = null)
	{
		if (file_exists($this->options['cache_dir'] . $key)) {
			$data = file_get_contents($this->options['cache_dir'] . $key);
			list($expiry, $serialized_data) = explode('::', $data, 2);
			unlink($this->options['cache_dir'] . $key);
			if(time() < $expiry){
				return unserialize($serialized_data);
			}
		}

		return $default;
	}

	/**
	 * Store an item in the cache.
	 *
	 * @param string $key
	 * @param mixed $value
	 * @param DateTime|int $minutes
	 */
	public function put($key, $value, $minutes)
	{
		if ($minutes instanceof \DateTime) {
			$expires = $minutes;
		} else {
			$expires = new \DateTime();
			$expires->add(new \DateInterval('PT' . $minutes . 'M'));
		}

		if($value){
			$data = $expires->getTimestamp() .'::'. serialize($value);
			file_put_contents($this->options['cache_dir'] . $key, $data);
		}
	}

	/**
	 * Store an item in the cache if the key does not exist.
	 *
	 * @param string $key
	 * @param mixed $value
	 * @param DateTime|int $minutes
	 * @return bool true if added to cache, false otherwise
	 */
	public function add($key, $value, $minutes)
	{
		if(!$this->has($key)){
			$this->put($key, $value, $minutes);
			return true;
		}

		return false;
	}

	/**
	 * Store an item in the cache indefinitely.
	 *
	 * @param string $key
	 * @param mixed $value
	 */
	public function forever($key, $value)
	{
		$this->put($key, $value, 9999999999);
	}

	/**
	 * Remove an item from the cache.
	 *
	 * @param string $key
	 */
	public function forget($key)
	{
		if (file_exists($this->options['cache_dir'] . $key)) {
			unlink($this->options['cache_dir'] . $key);
		}
	}

	/**
	 * Remove all items from the cache.
	 */
	public function flush()
	{
		$files = glob($this->options['cache_dir'] . '*');
		foreach ($files as $file) {
			if (is_file($file)) {
				unlink($file);
			}
		}
	}

	/**
	 * Get default cache directory
	 * (uses system tmp dir)
	 *
	 * @return string
	 */
	protected function getDefaultDirectory()
	{
		$tmp = rtrim(sys_get_temp_dir(), '/\\') . '/';
		$baseDir = $tmp . 'hoard/';
		if (!is_dir($baseDir)) {
			mkdir($baseDir, 0770, true);
		}
		return $baseDir;
	}

}