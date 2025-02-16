<?php

namespace App\Util\Classes;

use App\util\Attributes\ArrayType;
use App\util\Attributes\OptionalProperty;

class JsonGenerator
{
    public static function generateFromInstance(object $instance): object
    {
        $reflectionClass = new \ReflectionClass($instance);
        $jsonObject = new \stdClass();

        foreach ($reflectionClass->getProperties() as $property) {
            $property->setAccessible(true); // Allow access to private/protected properties
            $propertyName = $property->getName();
            $value = $property->getValue($instance);

            // Check for array element type via attributes
            $attributes = $property->getAttributes(ArrayType::class);

            if (is_null($value)) {
                $jsonObject->$propertyName = null; // Null value
                continue;
            }

            if (is_array($value) && count($attributes) > 0) {
                // Handle arrays with specific element types
                $attributeInstance = $attributes[0]->newInstance();
                $jsonObject->$propertyName = array_map(
                    fn($item) => self::generateFromInstance($item),
                    $value
                );
            } elseif (is_object($value)) {
                // If the value is an object, recursively process it
                $jsonObject->$propertyName = self::generateFromInstance($value);
            } else {
                // For scalar values, directly assign them
                $jsonObject->$propertyName = $value;
            }
        }

        return $jsonObject;
    }

    public static function generateFromClass(string $className): object
    {
        $reflectionClass = new \ReflectionClass($className);
        $jsonObject = new \stdClass();

        foreach ($reflectionClass->getProperties() as $property) {
            // Get the property's type
            $type = $property->getType();
            $propertyName = $property->getName();

            // Check for array element type via attributes
            $attributes = $property->getAttributes(ArrayType::class);

            if ($type === null) {
                $jsonObject->$propertyName = null; // No type declared
                continue;
            }

            $typeName = $type->getName();

            if ($type->isBuiltin()) {
                if ($typeName === 'array' && count($attributes) > 0) {
                    // Handle arrays with specific element types
                    $attributeInstance = $attributes[0]->newInstance();
                    $jsonObject->$propertyName = [self::generateFromClass($attributeInstance->className)];
                } elseif ($typeName === 'array') {
                    $jsonObject->$propertyName = [];
                } else {
                    // Handle scalar types (string, int, float, bool)
                    $jsonObject->$propertyName = self::getDefaultForType($typeName);
                }
            } else {
                // If the type is a class, recursively process it
                $jsonObject->$propertyName = self::generateFromClass($typeName);
            }
        }

        return $jsonObject;
    }

    public static function validateJsonAgainstClass(string $className, object $jsonObject): bool
    {
        $reflectionClass = new \ReflectionClass($className);

        foreach ($reflectionClass->getProperties() as $property) {
            $propertyName = $property->getName();

            if (!property_exists($jsonObject, $propertyName)) {
                // Check for optional properties
                $attributes = $property->getAttributes(OptionalProperty::class);
                if (count($attributes) === 0) {
                    return false; // Required property missing
                }
                continue;
            }

            $type = $property->getType();
            if ($type === null) {
                continue; // No type to validate against
            }

            $value = $jsonObject->$propertyName;
            $typeName = $type->getName();

            if ($type->isBuiltin()) {
                // Validate built-in types
                if (gettype($value) !== $typeName) {
                    return false;
                }
            } elseif (is_array($value)) {
                // Validate arrays with specific element types
                $attributes = $property->getAttributes(ArrayType::class);
                if (count($attributes) > 0) {
                    $attributeInstance = $attributes[0]->newInstance();
                    foreach ($value as $item) {
                        if (!self::validateJsonAgainstClass($attributeInstance->className, $item)) {
                            return false;
                        }
                    }
                }
            } else {
                // Validate nested objects
                if (!self::validateJsonAgainstClass($typeName, $value)) {
                    return false;
                }
            }
        }

        return true;
    }

    private static function getDefaultForType(string $typeName)
    {
        return match ($typeName) {
            'string' => '',
            'int' => 0,
            'float' => 0.0,
            'bool' => false,
            default => null, // Unknown types
        };
    }
}
