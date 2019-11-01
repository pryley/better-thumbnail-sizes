<?php

defined('WPINC') || die;

/*
 * PSR-4 autoloader
 */
spl_autoload_register(function ($className) {
    $classMap = [
        'WP_Image_Editor_GD' => ABSPATH.WPINC.'/class-wp-image-editor-gd.php',
        'WP_Image_Editor_Imagick' => ABSPATH.WPINC.'/class-wp-image-editor-imagick',
    ];
    if (array_key_exists($className, $classMap) && file_exists($classMap[$className])) {
        require_once $classMap[$className];
    }
    $namespaces = [
        'GeminiLabs\\BetterThumbnailSizes\\' => __DIR__.'/plugin/',
    ];
    foreach ($namespaces as $prefix => $baseDir) {
        $len = strlen($prefix);
        if (0 !== strncmp($prefix, $className, $len)) {
            continue;
        }
        $file = $baseDir.str_replace('\\', '/', substr($className, $len)).'.php';
        if (!file_exists($file)) {
            continue;
        }
        require $file;
        break;
    }
});
