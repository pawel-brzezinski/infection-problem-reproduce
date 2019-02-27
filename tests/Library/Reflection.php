<?php

declare (strict_types = 1);

namespace App\Tests\Library;

final class Reflection
{
    public static function callMethod($object, string $name, array $args)
    {
        $method = static::getReflectionMethod(get_class($object), $name);
        $method->setAccessible(true);

        return $method->invoke($object, ...$args);
    }

    public static function callStaticMethod(string $class, string $name, array $args)
    {
        $method = static::getReflectionMethod($class, $name);
        $method->setAccessible(true);

        return $method->invoke(null, ...$args);
    }

    public static function getPropertyValue($object, string $name)
    {
        $property = static::getReflectionProperty(get_class($object), $name);
        $property->setAccessible(true);

        return $property->getValue($object);
    }

    public static function setPropertyValue($object, string $name, $value): void
    {
        $property = static::getReflectionProperty(get_class($object), $name);
        $property->setAccessible(true);

        $property->setValue($object, $value);
    }

    public static function getReflectionClass(string $class): \ReflectionClass
    {
        return new \ReflectionClass($class);
    }

    public static function getReflectionMethod(string $class, string $name): \ReflectionMethod
    {
        return static::getReflectionClass($class)->getMethod($name);
    }

    public static function getReflectionProperty(string $class, string $name): \ReflectionProperty
    {
        return static::getReflectionClass($class)->getProperty($name);
    }
}
