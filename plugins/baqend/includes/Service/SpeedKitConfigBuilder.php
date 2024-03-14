<?php

namespace Baqend\WordPress\Service;

use Baqend\SDK\Model\AssetFilter;
use Baqend\WordPress\Model\RegExp;
use Baqend\WordPress\OptionEnums;
use Baqend\WordPress\Options;

/**
 * Class SpeedKitConfigBuilder created on 2019-12-17.
 *
 * @author Kevin Twesten
 * @package Baqend\WordPress\Service
 */
class SpeedKitConfigBuilder {
    /**
     * @var Options
     */
    private $options;

    /**
     * @var WooCommerceService
     */
    private $woo_commerce_service;

    /**
     * @var string
     */
    private $sw_scope;

    /**
     * @var string
     */
    private $sw_url;

    /**
     * SpeedKitConfigBuilder constructor.
     *
     * @param Options $options
     * @param WooCommerceService $woo_commerce_service
     * @param string $sw_scope
     * @param string $sw_url
     */
    public function __construct( Options $options, WooCommerceService $woo_commerce_service, $sw_scope, $sw_url ) {
        $this->options = $options;
        $this->woo_commerce_service = $woo_commerce_service;
        $this->sw_scope = $sw_scope;
        $this->sw_url = $sw_url;
    }

    public function build_config() {
        // Find default whitelisted URLs
        $whitelisted_urls = [ home_url(), site_url( 'wp-content' ), site_url( 'wp-includes/js' ) ];
        $whitelisted_urls = strip_protocol( $whitelisted_urls );

        // Find default blacklisted URLs
        $blacklisted_urls = [
            site_url( 'wp-json' ),
            site_url( 'wp-login' ),
            site_url( 'login' ),
            site_url( 'wp-content/plugins/baqend' ),
        ];
        $blacklisted_urls = strip_protocol( $blacklisted_urls );

        // Find default stripped query parameters
        $query_params = [
            'utm_',
            'msclkid',
            'gclsrc',
            'fbclid',
            'dclid',
            'cjid',
            'gclid'
        ];

        $sw_url = $this->sw_url;
        $app_name = $this->options->get( OptionEnums::APP_NAME );
        $metrics_enabled = $this->options->get( OptionEnums::METRICS_ENABLED ) === true;
        $user_agent_detection_enabled = $this->options->get( OptionEnums::USER_AGENT_DETECTION ) === true;

        $config = [
            'appName'            => $app_name,
            'sw'                 => $sw_url,
            'scope'              => $this->sw_scope,
            'rumTracking'        => $metrics_enabled,
            'disabled'           => is_user_logged_in() || ! $this->options->get( OptionEnums::SPEED_KIT_ENABLED ),
            'userAgentDetection' => $user_agent_detection_enabled,
            'enabledSites'       => [ [ 'pathname' => [ new RegExp('^((?!\/wp-admin\/).)*$') ] ] ],
            'whitelist'          => [ [ 'url' => $whitelisted_urls ] ],
            'blacklist'          => [
                [ 'url' => $blacklisted_urls ],
                [ 'contentType'  => ['video'] ],
                [ 'pathname ' => new RegExp( '[?&](_=1\d{12})' ) ], // blacklist jquery code execution callbacks
            ],
            'stripQueryParams' => [ [ 'params' => $query_params ] ],
        ];

        $image_optimization_option = $this->options->get( OptionEnums::IMAGE_OPTIMIZATION );
        if ( ! empty( $image_optimization_option ) && ! isset( $image_optimization_option['disabled'] ) || ! $image_optimization_option['disabled'] ) {
            $config['image'] = [
                'quality' => (int) $image_optimization_option['quality'],
                'webp'    => ! ! $image_optimization_option['webp'],
                'pjpeg'   => ! ! $image_optimization_option['pjpeg'],
            ];
        } else {
            $config['image'] = [
                'quality' => false,
                'webp'    => false,
                'pjpeg'   => false,
            ];
        }

        $enabled_sites_option = $this->get_enabled_sites_option();
        if ( ! empty( $enabled_sites_option ) ) {
            $config['enabledSites'][0]['pathname'] = array_merge(
                $config['enabledSites'][0]['pathname'],
                $enabled_sites_option
            );
        }

        $whitelist_option = $this->options->get( OptionEnums::SPEED_KIT_WHITELIST );
        if ( ! empty( $whitelist_option ) ) {
            $config['whitelist'][0]['url'] = array_merge(
                $config['whitelist'][0]['url'],
                $this->strip_comments( $whitelist_option )
            );
        }

        $config['whitelist'][0]['url'] = $this->simplify_rules( $config['whitelist'][0]['url'] );

        $content_types_option = $this->options->get( OptionEnums::SPEED_KIT_CONTENT_TYPE );
        if ( ! empty( $content_types_option ) ) {
            $config['whitelist'][0]['contentType'] = $content_types_option;
        }

        $blacklist_option = $this->options->get( OptionEnums::SPEED_KIT_BLACKLIST );
        if ( ! empty( $blacklist_option ) ) {
            $config['blacklist'][0]['url'] = array_merge(
                $config['blacklist'][0]['url'],
                $this->strip_comments( $blacklist_option )
            );
        }

        $config['blacklist'][0]['url'] = $this->simplify_rules( $config['blacklist'][0]['url'] );
        // Blacklist PDF files on default
        array_push( $config['blacklist'][0]['url'], new RegExp( '\.pdf' ) );
        // Blacklist GIF files with query parameters on default
        array_push( $config['blacklist'][0]['url'], new RegExp( '\.gif[?&]' ) );

        $woo_commerce_blacklist = $this->get_woo_commerce_blacklist();
        if ( ! empty( $woo_commerce_blacklist ) ) {
            if ( ! isset( $config['blacklist'] ) ) {
                $config['blacklist'] = [];
            }

            $config['blacklist'][] = $woo_commerce_blacklist;
        }

        $cookies_option = $this->options->get( OptionEnums::SPEED_KIT_COOKIES );
        if ( ! empty( $cookies_option ) ) {
            if ( ! isset( $config['blacklist'] ) ) {
                $config['blacklist'] = [];
            }

            $config['blacklist'][] = [
                'cookie'      => $this->strip_comments( $cookies_option ),
                'contentType' => [ AssetFilter::DOCUMENT ],
            ];
        }

        $strip_query_params = $this->options->get( OptionEnums::STRIP_QUERY_PARAMS );
        if ( is_array( $strip_query_params ) && ! empty( $strip_query_params ) ) {
            $config['stripQueryParams'][0]['params'] = array_merge(
                $config['stripQueryParams'][0]['params'],
                $this->strip_comments( $strip_query_params )
            );
        }

        $max_staleness_option = $this->options->get( OptionEnums::SPEED_KIT_MAX_STALENESS );
        if ( 60000 !== (int) $max_staleness_option ) {
            $config['maxStaleness'] = (int) $max_staleness_option;
        }

        $app_domain_option = $this->options->get( OptionEnums::SPEED_KIT_APP_DOMAIN );
        if ( ! empty( $app_domain_option ) ) {
            $config['appDomain'] = $app_domain_option;
        }

        $fetch_origin_interval_option = (int) $this->options->get( OptionEnums::FETCH_ORIGIN_INTERVAL );
        if ( - 1 !== $fetch_origin_interval_option ) {
            // Retrieve setting from PHP settings
            if ( - 2 === $fetch_origin_interval_option ) {
                $session_max_lifetime = ini_get( 'session.gc_maxlifetime' );
                if ( is_numeric( $session_max_lifetime ) ) {
                    $fetch_origin_interval_option = (int) $session_max_lifetime;
                } else {
                    $fetch_origin_interval_option = 1200;
                }
            }

            $config['fetchOriginInterval'] = $fetch_origin_interval_option;
        }

        // Parse custom config
        $custom_config = trim( $this->options->get( OptionEnums::CUSTOM_CONFIG, '' ) );
        if ( ! empty( $custom_config ) && $custom_config !== '{}' ) {
            $custom_config = $this->decode( $custom_config );
            if ( is_array( $custom_config ) ) {
                $config = array_merge( $config, $custom_config );
            }
        }

        return $this->encode( $config );
    }

    /**
     * Generates the "enabledSites" option for the Speed Kit config.
     *
     * @return array
     */
    private function get_enabled_sites_option() {
        $pathnames = [];

        $enabled_paths = $this->options->get( OptionEnums::ENABLED_PATHS );
        if ( is_array( $enabled_paths ) && ! empty( $enabled_paths ) ) {
            $pathnames = array_merge( $pathnames, $this->strip_comments( $enabled_paths ) );
        }

        $enabled_pages = $this->options->get( OptionEnums::ENABLED_PAGES );
        if ( is_array( $enabled_pages ) && ! empty( $enabled_pages ) ) {
            foreach ( $enabled_pages as $page ) {
                switch ( $page['type'] ) {
                    case 'Page':
                        $pathnames[] = $this->to_pathname_rule( get_permalink( $page['id'] ) );
                        break;
                    case 'Categories':
                        $posts       = get_posts( [ 'category' => $page['id'] ] );
                        $pathnames[] = $this->to_pathname_rule( get_category_link( $page['id'] ) );
                        foreach ( $posts as $post ) {
                            $pathnames[] = $this->to_pathname_rule( get_permalink( $post ) );
                        }
                        break;
                }
            }
        }

        if ( empty( $pathnames ) ) {
            return [];
        }

        return $pathnames;
    }

    /**
     * @param string[] $rules
     *
     * @return string[]
     */
    private function simplify_rules( array $rules ) {
        $keep = [];
        foreach ( $rules as $rule ) {
            foreach ( $keep as $kept_rule ) {
                if ( strpos( $rule, $kept_rule ) === 0 ) {
                    continue 2;
                }
            }

            foreach ( $keep as $index => $kept_rule ) {
                if ( strpos( $kept_rule, $rule ) === 0 ) {
                    unset( $keep[ $index ] );
                }
            }

            $keep[] = $rule;
        }

        return array_values( $keep );
    }

    /**
     * @param string $url
     *
     * @return RegExp
     */
    private function to_pathname_rule( $url ) {
        return new RegExp( '^' . RegExp::escape( $this->strip_origin( $url ) ) . '$' );
    }

    private function strip_origin( $url ) {
        return str_replace( home_url(), '', $url );
    }

    /**
     * @param string[] $options
     *
     * @return string[]
     */
    private function strip_comments( array $options ) {
        return array_values( array_filter(
            array_map(
                function ( $option ) {
                    $index = strpos( $option, ';' );
                    if ( $index !== false ) {
                        $option = substr( $option, 0, $index );
                    }

                    return trim( $option );
                },
                $options
            ),
            function ( $option ) {
                return ! empty( $option );
            }
        ) );
    }

    /**
     * @return array
     */
    private function get_woo_commerce_blacklist() {
        if ( ! $this->woo_commerce_service->is_shop_active() ) {
            return [];
        }

        $rules = $this->woo_commerce_service->load_woo_commerce_rules();
        $url = array_map(function( $rule ) {
            return new RegExp( '^' . RegExp::escape( $rule ) );
        }, $rules);

        if ( empty( $url ) ) {
            return [];
        }

        return [
            'url'         => $url,
            'contentType' => [ 'document' ],
        ];
    }

    /**
     * Encodes value into JS code.
     *
     * @param mixed $value The value to encode.
     *
     * @return string Valid JavaScript code.
     */
    private function encode( $value ) {
        if ( is_scalar( $value ) || $value === null ) {
            return json_encode( $value, JSON_UNESCAPED_SLASHES );
        }

        if ( is_array( $value ) ) {
            // Encode associative arrays as objects
            if ( is_assoc( $value ) ) {
                $return = '{';
                $first  = true;

                foreach ( $value as $key => $item ) {
                    if ( ! $first ) {
                        $return .= ',';
                    }
                    $return .= $key;
                    $return .= ':';
                    $return .= $this->encode( $item );
                    $first  = false;
                }

                $return .= '}';

                return $return;
            }

            // Encode others as an array
            $return = '[';
            $first  = true;

            foreach ( $value as $item ) {
                if ( ! $first ) {
                    $return .= ',';
                }
                $return .= $this->encode( $item );
                $first  = false;
            }

            $return .= ']';

            return $return;
        }

        // RegExp know how to encode themselves
        if ( $value instanceof RegExp ) {
            return $value->__toString();
        }

        throw new \InvalidArgumentException( 'Cannot encode value of ' . get_class( $value ) );
    }

    /**
     * Decodes a value of JSON code.
     *
     * @param string $string The value to decode.
     *
     * @return mixed Data being encoded.
     */
    private function decode( $string ) {
        $regex_replace = preg_replace( '#(/[^/]+/\w*)(\s*[},\]])#', '"regexp:$1"$2', $string );
        $escaped_replace = str_replace( '\\', '\\\\', $regex_replace );
        return $this->decode_postprocess( json_decode( $escaped_replace, true, 512, JSON_UNESCAPED_SLASHES ) );
    }

    private function decode_postprocess( $data ) {
        if ( is_string( $data ) ) {
            if ( preg_match( '#^regexp:/([^/]+)/(\w*)$#', $data, $matches ) === 1 ) {
                return new RegExp( $matches[1], $matches[2] );
            }

            return $data;
        }

        if ( is_array( $data ) ) {
            $result = [];
            foreach ( $data as $key => $value ) {
                $result[ $key ] = $this->decode_postprocess( $value );
            }

            return $result;
        }

        return $data;
    }
}
