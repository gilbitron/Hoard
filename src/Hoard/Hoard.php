<?php namespace Hoard;

class Hoard {

	protected $drawers = [
		'file' => '\Hoard\Drawers\FileSystem'
	];

	protected $default_args = [
		'encrypt_keys' => true,
		'encryption_function' => 'md5',
	];

	private $openDrawer;
	private $args;

	public function __construct($drawer = 'file', $options = [], $args = [])
	{
		if (array_key_exists($drawer, $this->drawers)) {
			$this->openDrawer = new $this->drawers[$drawer]($options);
		} else {
			throw new \Exception('Invalid drawer specified');
		}

		if (is_array($args)) {
			$this->args = array_merge($this->default_args, $args);
		} else {
			$this->args = $this->default_args;
		}
	}

	public function has($key)
	{
		$key = $this->maybeEncryptKey($key);
		return $this->openDrawer->has($key);
	}

	public function get($key, $default = null)
	{
		$key = $this->maybeEncryptKey($key);
		return $this->openDrawer->get($key, $default);
	}

	public function pull($key, $default = null)
	{
		$key = $this->maybeEncryptKey($key);
		return $this->openDrawer->pull($key, $default);
	}

	public function put($key, $value, $minutes)
	{
		$key = $this->maybeEncryptKey($key);
		return $this->openDrawer->put($key, $value, $minutes);
	}

	public function add($key, $value, $minutes)
	{
		$key = $this->maybeEncryptKey($key);
		return $this->openDrawer->add($key, $value, $minutes);
	}

	public function forever($key, $value)
	{
		$key = $this->maybeEncryptKey($key);
		return $this->openDrawer->forever($key, $value);
	}

	public function forget($key)
	{
		$key = $this->maybeEncryptKey($key);
		return $this->openDrawer->forget($key);
	}

	public function flush()
	{
		return $this->openDrawer->flush();
	}

	protected function maybeEncryptKey($key)
	{
		if ($this->args['encrypt_keys']) {
			if ($this->args['encryption_function'] == 'md5') {
				$key = md5($key);
			}
			if ($this->args['encryption_function'] == 'sha1') {
				$key = sha1($key);
			}
		}

		if(!$key){
			throw new \Exception('Invalid key');
		}

		return $key;
	}

}