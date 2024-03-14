<?php

namespace Modular\Connector\Helper;

use Modular\ConnectorDependencies\Illuminate\Support\Collection;
use Modular\SDK\ModularClient;

class OauthClient
{
    /**
     * @return Collection
     */
    public static function getClients(): Collection
    {
        $value = get_option('_modular_connection_clients');

        // The "Docket Cache" plugin sets up a cache that sometimes makes the "get_option" directly an array.
        if (!is_array($value)) {
            $value = $value ? unserialize($value) : [];
        }

        return Collection::make($value)
            ->map(fn($client) => static::mapClient($client));
    }

    /**
     * Generate Modular Key Connection
     *
     * @return ModularClient
     */
    public static function getClient(): ModularClient
    {
        return static::getClients()->first() ?: static::mapClient([]);
    }

    /**
     * Retrieves the URL for a given site where the front end is accessible.
     *
     * Returns the 'home' option with the appropriate protocol. The protocol will be 'https'
     * if is_ssl() evaluates to true; otherwise, it will be the same as the 'home' option.
     * If `$scheme` is 'http' or 'https', is_ssl() is overridden.
     *
     * @param string $path Optional. Path relative to the home URL. Default empty.
     * @param string|null $scheme Optional. Scheme to give the home URL context. Accepts
     *                             'http', 'https', 'relative', 'rest', or null. Default null.
     * @return string Home URL link with optional path appended.
     * @since 3.0.0
     */
    public static function getHomeUrl($path = '', $scheme = null)
    {
        remove_filter('home_url', 'add_language_to_home_url', 1);

        return home_url($path, $scheme);
    }

    /**
     * @param array $client
     * @return ModularClient
     */
    public static function mapClient(array $client)
    {
        $client = [
            'client_id' => $client['client_id'] ?? null,
            'client_secret' => $client['client_secret'] ?? null,
            'access_token' => $client['access_token'] ?? null,
            'refresh_token' => $client['refresh_token'] ?? null,
            'connected_at' => $client['connected_at'] ?? null,
            'used_at' => $client['used_at'] ?? null,
            'expires_in' => $client['expires_in'] ?? null
        ];

        return new ModularClient([
            'env' => defined('MODULAR_CONNECTOR_ENV') ? MODULAR_CONNECTOR_ENV : null,
            'oauth_client' => [
                'client_id' => @$client['client_id'],
                'client_secret' => @$client['client_secret'],
                'connected_at' => @$client['connected_at'],
                'used_at' => @$client['used_at'],
                'redirect_uri' => self::getHomeUrl('/'),
            ],
            'oauth_token' => [
                'expires_in' => @$client['expires_in'],
                'access_token' => @$client['access_token'],
                'refresh_token' => @$client['refresh_token'],
            ],
        ]);
    }
}
