<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit19a70971ac4b638824efe44d9d744834
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Stripe\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Stripe\\' => 
        array (
            0 => __DIR__ . '/..' . '/stripe/stripe-php/lib',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit19a70971ac4b638824efe44d9d744834::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit19a70971ac4b638824efe44d9d744834::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit19a70971ac4b638824efe44d9d744834::$classMap;

        }, null, ClassLoader::class);
    }
}
