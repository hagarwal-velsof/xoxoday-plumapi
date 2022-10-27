<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita278a0d26d8b92720df6ded829f2f413
{
    public static $prefixLengthsPsr4 = array (
        'X' => 
        array (
            'Xoxoday\\Plumapi\\' => 16,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Xoxoday\\Plumapi\\' => 
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
            $loader->prefixLengthsPsr4 = ComposerStaticInita278a0d26d8b92720df6ded829f2f413::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita278a0d26d8b92720df6ded829f2f413::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInita278a0d26d8b92720df6ded829f2f413::$classMap;

        }, null, ClassLoader::class);
    }
}