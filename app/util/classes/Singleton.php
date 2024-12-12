<?php

namespace App\Util\Classes;

trait Singleton
{
    private static ?self $instance = null;

    // Protected constructor to prevent direct instantiation
    protected function __construct() {}

    // Disable cloning
    protected function __clone() {}

    // Static method to get the single instance
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new static(); // Late static binding
        }

        return self::$instance;
    }

    // Optional: Reset the instance for testing or other use-cases
    public static function resetInstance(): void
    {
        self::$instance = null;
    }
}