<?php

namespace XCurrency\App\Providers;

use XCurrency\WpMVC\Contracts\Provider;

class ProVersionUpdateServiceProvider implements Provider {
    public string $update_url = 'https://doatkolom.com/wp-json/subscription-plus/update-info/x-currency-pro';

    public string $plugin_slug = 'x-currency-pro/x-currency-pro.php';

    public function boot() {
        if ( ! defined( 'X_CURRENCY_NEW_PRO' ) ) {
            add_filter( 'pre_set_site_transient_update_plugins', [$this, 'check_for_updates'] );
            add_filter( 'plugins_api', [ $this, 'filter_plugins_api' ], 10, 3 );
        }
    }

    /**
     * Filters the response for the current WordPress.org Plugin Installation API request.
     *
     * @param false|object|array $result The result object or array. Default false.
     * @param string             $action The type of information being requested from the Plugin Installation API.
     * @param object             $args   Plugin API arguments.
     * @return false|object|array The result object or array. Default false.
     */
    public function filter_plugins_api( $result, string $action, object $args ) {
        if ( 'plugin_information' !== $action ) {
            return $result;
        }

        if ( $this->plugin_slug !== $args->slug ) {
            return $result;
        }

        $response = wp_remote_get( $this->update_url );

        if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
            return $result;
        }

        $plugin_info = plugins_api(
            'plugin_information', [
                'slug' => 'x-currency'
            ]
        );

        if ( is_wp_error( $plugin_info ) ) {
            return $result;
        }

        $remote_data = json_decode( wp_remote_retrieve_body( $response ) );
        $remote_data = $remote_data->update_info;

        $data = (object) [
            "name"          => $remote_data->name, 
            "slug"          => $this->plugin_slug, 
            "plugin"        => $this->plugin_slug,
            "version"       => $remote_data->version,
            "url"           => $remote_data->url,
            "download_link" => $remote_data->download_url,
            "requires"      => $remote_data->requires, 
            "tested"        => $remote_data->tested, 
            "requires_php"  => $remote_data->requires_php, 
            "last_updated"  => $remote_data->last_updated 
        ]; 

        unset( $plugin_info->sections['changelog'] );

        $plugin_info->sections = array_merge( $plugin_info->sections, (array) $remote_data->sections );

        $data->author                   = $plugin_info->author;
        $data->author_profile           = $plugin_info->author_profile;
        $data->contributors             = $plugin_info->contributors;
        $data->rating                   = $plugin_info->rating;
        $data->ratings                  = $plugin_info->ratings;
        $data->num_ratings              = $plugin_info->num_ratings;
        $data->support_threads          = $plugin_info->support_threads;
        $data->support_threads_resolved = $plugin_info->support_threads_resolved;
        $data->active_installs          = $plugin_info->active_installs;
        $data->sections                 = $plugin_info->sections;
        $data->screenshots              = $plugin_info->screenshots;
        $data->tags                     = $plugin_info->tags;
        $data->donate_link              = $plugin_info->donate_link;
        $data->banners                  = $plugin_info->banners;

        return $data;
    }

    public function check_for_updates( $transient ) {
        if ( empty( $transient->checked ) ) {
            return $transient;
        }

        $response = wp_remote_get( $this->update_url );

        if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
            return $transient;
        }

        $remote_data = json_decode( wp_remote_retrieve_body( $response ) );
        $remote_data = $remote_data->update_info;

        if ( version_compare( $remote_data->version, $transient->checked[$this->plugin_slug], '>' ) ) {
            $new_plugin = [
                'slug'        => $this->plugin_slug,
                'new_version' => $remote_data->version,
                'url'         => $remote_data->url,
                'package'     => $remote_data->download_url,
            ];

            $transient->response[$this->plugin_slug] = (object) $new_plugin;
        }

        return $transient;
    }
}
