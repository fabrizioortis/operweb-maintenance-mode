<?php

namespace YahnisElsts\PluginUpdateChecker\v5p7;

class PucFactory {
    protected static $versions = array();

    public static function addVersion( $generalClass, $versionedClass, $version ) {
        self::$versions[ $generalClass ] = $versionedClass;
    }

    public static function buildUpdateChecker( $metadataUrl, $pluginFile, $slug = '' ) {
        $checker = new class {
            public function setBranch( $branch ) {
                return $this;
            }

            public function setAuthentication( $credentials ) {
                return $this;
            }
        };

        return $checker;
    }
}
