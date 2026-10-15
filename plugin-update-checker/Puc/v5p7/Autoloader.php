<?php

namespace YahnisElsts\PluginUpdateChecker\v5p7;

class Autoloader {
    public function __construct() {
        spl_autoload_register( array( $this, 'autoload' ) );
    }

    public function autoload( $class ) {
        $prefix = 'YahnisElsts\\PluginUpdateChecker\\v5p7\\';
        if ( 0 !== strpos( $class, $prefix ) ) {
            return;
        }

        $relative_class = substr( $class, strlen( $prefix ) );
        $path = __DIR__ . '/' . str_replace( '\\', '/', $relative_class ) . '.php';
        if ( file_exists( $path ) ) {
            require $path;
        }
    }
}
new Autoloader();
