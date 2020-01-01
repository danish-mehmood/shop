<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita063376f4d17dc396956cef549add3fd
{
    public static $prefixLengthsPsr4 = array (
        'D' => 
        array (
            'Defuse\\Crypto\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Defuse\\Crypto\\' => 
        array (
            0 => __DIR__ . '/..' . '/defuse/php-encryption/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita063376f4d17dc396956cef549add3fd::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita063376f4d17dc396956cef549add3fd::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
