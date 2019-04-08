<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitd148befe644475c97c815c9521e43caf
{
    public static $prefixLengthsPsr4 = array (
        't' => 
        array (
            'tiaozhan\\api\\client\\' => 20,
            'think\\composer\\' => 15,
        ),
        'a' => 
        array (
            'app\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'tiaozhan\\api\\client\\' => 
        array (
            0 => __DIR__ . '/..' . '/dinghaoran/tz-api-client/src',
        ),
        'think\\composer\\' => 
        array (
            0 => __DIR__ . '/..' . '/topthink/think-installer/src',
        ),
        'app\\' => 
        array (
            0 => __DIR__ . '/../..' . '/application',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitd148befe644475c97c815c9521e43caf::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitd148befe644475c97c815c9521e43caf::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
