<?php

namespace YahnisElsts\PluginUpdateChecker\v5p7;

use YahnisElsts\PluginUpdateChecker\v5\PucFactory as MajorFactory;
use YahnisElsts\PluginUpdateChecker\v5p7\PucFactory as MinorFactory;

require __DIR__ . '/Puc/v5p7/Autoloader.php';
require __DIR__ . '/Puc/v5/PucFactory.php';
require __DIR__ . '/Puc/v5p7/PucFactory.php';

foreach (
    array(
        'Plugin\\UpdateChecker' => Plugin\UpdateChecker::class,
        'Theme\\UpdateChecker'  => Theme\UpdateChecker::class,
        'Vcs\\PluginUpdateChecker' => Vcs\PluginUpdateChecker::class,
        'Vcs\\ThemeUpdateChecker'  => Vcs\ThemeUpdateChecker::class,
        'GitHubApi'    => Vcs\GitHubApi::class,
        'BitBucketApi' => Vcs\BitBucketApi::class,
        'GitLabApi'    => Vcs\GitLabApi::class,
    ) as $generalClass => $versionedClass
) {
    MajorFactory::addVersion( $generalClass, $versionedClass, '5.7' );
    MinorFactory::addVersion( $generalClass, $versionedClass, '5.7' );
}
