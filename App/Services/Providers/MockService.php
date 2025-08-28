<?php

namespace App\Services\Providers;

use App\Services\Interfaces\ServiceInterface;

class MockService implements ServiceInterface
{
    public function __construct()
    {
        // Initialization code here
    }

    public function getAuthorizationUrl(): string
    {
        // Mock implementation, return a dummy URL
        return 'https://example.com/authorize';
    }
    
    public function getAccessToken(string $code): array
    {
        // Mock implementation, return a dummy token
        return [];
    }

    public function getStoredAccessToken(): ?array
    {
        // Mock implementation, return a dummy stored token or null
        return [
            'access_token' => "mock_token"
        ];
    }

    
}