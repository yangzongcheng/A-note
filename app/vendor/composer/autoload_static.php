<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb9449b6dc9c512c1b9eb4da31bf3cb5d
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PhpAmqpLib\\' => 11,
        ),
        'E' => 
        array (
            'Elastic\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PhpAmqpLib\\' => 
        array (
            0 => __DIR__ . '/..' . '/php-amqplib/php-amqplib/PhpAmqpLib',
        ),
        'Elastic\\' => 
        array (
            0 => __DIR__ . '/../..' . '/appli/elastic',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitb9449b6dc9c512c1b9eb4da31bf3cb5d::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitb9449b6dc9c512c1b9eb4da31bf3cb5d::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}