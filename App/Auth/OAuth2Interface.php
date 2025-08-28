<?php

namespace App\Auth;

interface OAuth2Interface
{
    /**
     * Redirects user to the authorization URL.
     *
     * @return string
     */
    public function getAuthorizationUrl(): string;

    /**
     * Exchanges authorization code for access token.
     *
     * @param string $code
     * @return array
     */
    public function getAccessToken(string $code): array;

    /**
     * Retrieves a stored access token.
     *
     * @return array|null
     */
    public function getStoredAccessToken(): ?array;
}