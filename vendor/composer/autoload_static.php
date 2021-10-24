<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit42681d9df647b5640d7fb067a34efd60
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Stripe\\' => 7,
        ),
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Stripe\\' => 
        array (
            0 => __DIR__ . '/..' . '/stripe/stripe-php/lib',
        ),
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit42681d9df647b5640d7fb067a34efd60::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit42681d9df647b5640d7fb067a34efd60::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit42681d9df647b5640d7fb067a34efd60::$classMap;

        }, null, ClassLoader::class);
    }
}