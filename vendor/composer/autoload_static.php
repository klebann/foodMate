<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitf6c61e2b0515510e77f42ef113cab634
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Symfony\\Flex\\' => 13,
        ),
        'A' => 
        array (
            'App\\Tests\\' => 10,
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Symfony\\Flex\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/flex/src',
        ),
        'App\\Tests\\' => 
        array (
            0 => __DIR__ . '/../..' . '/tests',
        ),
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitf6c61e2b0515510e77f42ef113cab634::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitf6c61e2b0515510e77f42ef113cab634::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitf6c61e2b0515510e77f42ef113cab634::$classMap;

        }, null, ClassLoader::class);
    }
}
