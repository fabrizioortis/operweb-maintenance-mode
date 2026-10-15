<?php
namespace YahnisElsts\PluginUpdateChecker\v5p7;

require __DIR__ . '/v5p7/Autoloader.php';

spl_autoload_register(function ($class) {
	$prefix = 'YahnisElsts\\PluginUpdateChecker\\v5p7\\';
	$base_dir = __DIR__ . '/';
	$len = strlen($prefix);
	if (strncmp($prefix, $class, $len) !== 0) {
		return;
	}
	$relative_class = substr($class, $len);
	$file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
	if (file_exists($file)) {
		require $file;
	}
});
