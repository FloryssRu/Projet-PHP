<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit9be48be3e44b783e5b51182e6cc63a0d
{
    public static $files = array (
        '320cde22f66dd4f5d3fd621d3e88b98f' => __DIR__ . '/..' . '/symfony/polyfill-ctype/bootstrap.php',
        '6e3fae29631ef280660b3cdad06f25a8' => __DIR__ . '/..' . '/symfony/deprecation-contracts/function.php',
        '0e6d7bf4a5811bfa5cf40c5ccd6fae6a' => __DIR__ . '/..' . '/symfony/polyfill-mbstring/bootstrap.php',
    );

    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'Twig\\' => 5,
        ),
        'S' => 
        array (
            'Symfony\\Polyfill\\Mbstring\\' => 26,
            'Symfony\\Polyfill\\Ctype\\' => 23,
            'Symfony\\Component\\Yaml\\' => 23,
        ),
        'A' => 
        array (
            'App\\' => 4,
            'App/Services\\' => 13,
            'App/Repository\\' => 15,
            'App/Entity\\' => 11,
            'App/Entity/Manager\\' => 19,
            'App/Core\\' => 9,
            'App/Core/Exceptions\\' => 20,
            'App/Controller\\' => 15,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Twig\\' => 
        array (
            0 => __DIR__ . '/..' . '/twig/twig/src',
        ),
        'Symfony\\Polyfill\\Mbstring\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-mbstring',
        ),
        'Symfony\\Polyfill\\Ctype\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-ctype',
        ),
        'Symfony\\Component\\Yaml\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/yaml',
        ),
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
        'App/Services\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/Services',
        ),
        'App/Repository\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/Repository',
        ),
        'App/Entity\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/Entity',
        ),
        'App/Entity/Manager\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/Entity/Manager',
        ),
        'App/Core\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/Core',
        ),
        'App/Core/Exceptions\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/Core/Exceptions',
        ),
        'App/Controller\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/Controller',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit9be48be3e44b783e5b51182e6cc63a0d::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit9be48be3e44b783e5b51182e6cc63a0d::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit9be48be3e44b783e5b51182e6cc63a0d::$classMap;

        }, null, ClassLoader::class);
    }
}
