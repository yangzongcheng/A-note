<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb9449b6dc9c512c1b9eb4da31bf3cb5d
{
    public static $files = array (
        'ad155f8f1cf0d418fe49e248db8c661b' => __DIR__ . '/..' . '/react/promise/src/functions_include.php',
        '8592c7b0947d8a0965a9e8c3d16f9c24' => __DIR__ . '/..' . '/elasticsearch/elasticsearch/src/autoload.php',
    );

    public static $prefixLengthsPsr4 = array (
        'R' => 
        array (
            'React\\Promise\\' => 14,
        ),
        'P' => 
        array (
            'Psr\\Log\\' => 8,
        ),
        'L' => 
        array (
            'Library\\' => 8,
        ),
        'G' => 
        array (
            'GuzzleHttp\\Stream\\' => 18,
            'GuzzleHttp\\Ring\\' => 16,
        ),
        'E' => 
        array (
            'Elasticsearch\\' => 14,
            'Elastic\\' => 8,
        ),
        'D' => 
        array (
            'Datto\\JsonRpc\\Http\\Examples\\' => 28,
            'Datto\\JsonRpc\\Http\\' => 19,
            'Datto\\JsonRpc\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'React\\Promise\\' => 
        array (
            0 => __DIR__ . '/..' . '/react/promise/src',
        ),
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/Psr/Log',
        ),
        'Library\\' => 
        array (
            0 => __DIR__ . '/../..' . '/Library',
        ),
        'GuzzleHttp\\Stream\\' => 
        array (
            0 => __DIR__ . '/..' . '/ezimuel/guzzlestreams/src',
        ),
        'GuzzleHttp\\Ring\\' => 
        array (
            0 => __DIR__ . '/..' . '/ezimuel/ringphp/src',
        ),
        'Elasticsearch\\' => 
        array (
            0 => __DIR__ . '/..' . '/elasticsearch/elasticsearch/src/Elasticsearch',
        ),
        'Elastic\\' => 
        array (
            0 => __DIR__ . '/../..' . '/appli/elastic',
        ),
        'Datto\\JsonRpc\\Http\\Examples\\' => 
        array (
            0 => __DIR__ . '/..' . '/datto/json-rpc-http/examples/src',
        ),
        'Datto\\JsonRpc\\Http\\' => 
        array (
            0 => __DIR__ . '/..' . '/datto/json-rpc-http/src',
        ),
        'Datto\\JsonRpc\\' => 
        array (
            0 => __DIR__ . '/..' . '/datto/json-rpc/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'F' => 
        array (
            'FuseSource' => 
            array (
                0 => __DIR__ . '/..' . '/fusesource/stomp-php/src',
            ),
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitb9449b6dc9c512c1b9eb4da31bf3cb5d::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitb9449b6dc9c512c1b9eb4da31bf3cb5d::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInitb9449b6dc9c512c1b9eb4da31bf3cb5d::$prefixesPsr0;
            $loader->classMap = ComposerStaticInitb9449b6dc9c512c1b9eb4da31bf3cb5d::$classMap;

        }, null, ClassLoader::class);
    }
}
