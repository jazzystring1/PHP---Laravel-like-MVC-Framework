<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit8ae86acb17705c9bc2fad441a884cd1e
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/Manager',
        ),
    );

    public static $classMap = array (
        'App\\Input' => __DIR__ . '/../..' . '/Manager/Input/Input.php',
        'App\\Manager' => __DIR__ . '/../..' . '/Manager/manager.php',
        'App\\ManagerValidator' => __DIR__ . '/../..' . '/Manager/ManagerValidator.php',
        'App\\ManagerValidatorInterface' => __DIR__ . '/../..' . '/Manager/ManagerValidatorInterface.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit8ae86acb17705c9bc2fad441a884cd1e::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit8ae86acb17705c9bc2fad441a884cd1e::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit8ae86acb17705c9bc2fad441a884cd1e::$classMap;

        }, null, ClassLoader::class);
    }
}