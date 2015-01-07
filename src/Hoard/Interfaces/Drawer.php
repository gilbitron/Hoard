<?php namespace Hoard\Interfaces;

abstract class Drawer {

	/**
	 * Array of optional drawer specific options.
	 */
	protected $options = [];

	/**
	 * Constructor allows you to pass in an optional array of options.
	 *
	 * @param array $options
	 */
	public function __construct($options = [])
	{
		if (is_array($options)) {
			$this->options = array_merge($this->options, $options);
		}
	}

	/**
	 * Determine if an item exists in the cache.
	 *
	 * @param string $key
	 * @return bool
	 */
	abstract public function has($key);

	/**
	 * Retrieve an item from the cache by key.
	 *
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	abstract public function get($key, $default = null);

	/**
	 * Retrieve an item from the cache and delete it.
	 *
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	abstract public function pull($key, $default = null);

	/**
	 * Store an item in the cache.
	 *
	 * @param string $key
	 * @param mixed $value
	 * @param DateTime|int $minutes
	 */
	abstract public function put($key, $value, $minutes);

	/**
	 * Store an item in the cache if the key does not exist.
	 *
	 * @param string $key
	 * @param mixed $value
	 * @param DateTime|int $minutes
	 * @return bool true if added to cache, false otherwise
	 */
	abstract public function add($key, $value, $minutes);

	/**
	 * Store an item in the cache indefinitely.
	 *
	 * @param string $key
	 * @param mixed $value
	 */
	abstract public function forever($key, $value);

	/**
	 * Remove an item from the cache.
	 *
	 * @param string $key
	 */
	abstract public function forget($key);

	/**
	 * Remove all items from the cache.
	 */
	abstract public function flush();

}