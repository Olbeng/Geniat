<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit136ec891e30575361d2243b5f9368aff
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit136ec891e30575361d2243b5f9368aff::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit136ec891e30575361d2243b5f9368aff::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit136ec891e30575361d2243b5f9368aff::$classMap;

        }, null, ClassLoader::class);
    }
}
