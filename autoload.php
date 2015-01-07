<?php

date_default_timezone_set('Europe/London');

spl_autoload_register(function($class) {
	$base = '/src/';
	$file = __DIR__ . $base . strtr($class, '\\', '/') . '.php';
	if (file_exists($file)) {
		require $file;
		return true;
	}
});