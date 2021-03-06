<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitaee6447eaf9eee902f649564bf4eee99
{
    public static $prefixLengthsPsr4 = array (
        'J' => 
        array (
            'Jyotish\\Draw\\' => 13,
            'Jyotish\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Jyotish\\Draw\\' => 
        array (
            0 => __DIR__ . '/..' . '/jyotish-draw/src',
        ),
        'Jyotish\\' => 
        array (
            0 => __DIR__ . '/..' . '/jyotish/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitaee6447eaf9eee902f649564bf4eee99::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitaee6447eaf9eee902f649564bf4eee99::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitaee6447eaf9eee902f649564bf4eee99::$classMap;

        }, null, ClassLoader::class);
    }
}
