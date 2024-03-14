<?php
/**
 * Base class to store podcast feed data.
 *
 * @link       https://easypodcastpro.com
 * @since      1.0.0
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/Helper
 */

namespace Podcast_Player\Helper\Store;

use Podcast_Player\Helper\Functions\Getters as Get_Fn;
use Podcast_Player\Helper\Functions\Date_Parser;

/**
 * Provide base functionality to store podcast feed data.
 *
 * @package Podcast_Player
 */
class StoreBase {

    /**
     * Create initial state of the object
     *
     * @since 1.0.0
     */
    public function __construct() {
    }

    /**
     * Escape String
     *
     * @since 1.0.0
     *
     * @param string $val
     * @param string $context
     */
    protected function string( $val, $context ) {
        if ( 'sanitize' === $context ) {
            return sanitize_text_field( $val );
        } else {
            return esc_html( $val );
        }
    }

    /**
     * Escape Episode ID
     *
     * @since 1.0.0
     *
     * @param string $val
     * @param string $context
     */
    protected function episodeid( $val, $context ) {
        return sanitize_text_field( $val );
    }

    /**
     * Escape Attributes
     *
     * @since 1.0.0
     *
     * @param string $val
     * @param string $context
     */
    protected function attr( $val, $context ) {
        if ( 'sanitize' === $context ) {
            return sanitize_text_field( $val );
        } else {
            return esc_attr( $val );
        }
    }

    /**
     * Escape URL
     *
     * @since 1.0.0
     *
     * @param string $val
     * @param string $context
     */
    protected function url( $val, $context ) {
        if ( 'sanitize' === $context ) {
            return esc_url_raw( $val );
        } else {
            return esc_attr( esc_url( $val ) );
        }
    }

    /**
     * HTML Title
     *
     * @since 1.0.0
     *
     * @param string $val
     * @param string $context
     */
    protected function title( $val, $context ) {
        if ( 'sanitize' === $context ) {
            return wp_kses_post( wp_check_invalid_utf8( htmlspecialchars_decode( $val ) ) );
        } else {
            return trim( convert_chars( wptexturize( str_replace( '&quot;', '&#8221;', $val ) ) ) );
        }
    }

    /**
     * HTML Content
     *
     * @since 1.0.0
     *
     * @param string $val
     * @param string $context
     */
    protected function desc( $val, $context ) {
        if ( 'sanitize' === $context ) {
            return wp_kses_post( wp_check_invalid_utf8( $val ) );
        } else {
            return wpautop( wptexturize( str_replace( '&quot;', '&#8221;', trim( $val ) ) ) );
        }
    }

    /**
     * Integer
     *
     * @since 1.0.0
     *
     * @param integer $val
     * @param string $context
     */
    protected function int( $val, $context ) {
        return absint( $val );
    }

    /**
     * Array of strings
     *
     * @since 1.0.0
     *
     * @param array $val
     * @param string $context
     */
    protected function arrString( $val, $context ) {
        if ( 'sanitize' === $context ) {
            return array_map( 'sanitize_text_field', $val );
        } else {
            return array_map( 'esc_html', $val );
        }
    }

    /**
     * Array of URLs
     *
     * @since 1.0.0
     *
     * @param array $val
     * @param string $context
     */
    protected function arrUrl($val, $context) {
        if ('sanitize' === $context) {
            return array_map('esc_url_raw', $val);
        } else {
            if (! $val) {
                return $val;
            }
            return array_map('esc_url', $val);
        }
    }

    /**
     * Properly format audio time.
     *
     * @since 1.0.0
     *
     * @param string $val
     * @param string $context
     */
    protected function dur( $val, $context ) {
        if ( 'sanitize' === $context ) {
            if ( ! $val ) {
                return false;
            }
            $time = sanitize_text_field( $val );
            $sec  = 0;
            $ta   = array_reverse( explode( ':', $time ) );
            foreach ( $ta as $key => $value ) {
                $sec += absint( $value ) * pow( 60, $key );
            }
            return $sec;
        } else {
            $duration = absint($val);
            if ( ! $duration ) {
                return '00:00';
            }
            $dur   = array();
            $hours = floor($duration / 3600);
            if ( $hours ) {
                $dur[] = sprintf('%02d', $hours);
            }
            $dur[] = sprintf('%02d', floor(($duration / 60)) % 60);
            $dur[] = sprintf('%02d', $duration % 60);
            return implode(':', $dur);
        }
    }

    /**
	 * Escape podcast categories.
	 *
	 * @since  1.0.0
	 *
	 * @param array $cats Podcast Categories to be escaped and prepared.
	 */
	protected function podcats( $val, $context ) {
        if ( ! isset( $val ) || ! is_array( $val ) ) {
            return array();
        }
		if ( 'sanitize' === $context ) {
            foreach ( $val as $key => $cat ) {
                if ( ! isset( $cat['label'] ) ) {
                    continue;
                }
                $new_arr      = array(
                    'label'   => sanitize_text_field( $cat['label'] ),
                    'subcats' => array_map( 'sanitize_text_field', $cat['subcats'] ),
                );
                $val[ $key ] = $new_arr;
            }
        } else {
            foreach ( $val as $key => $cat ) {
                if ( ! isset( $cat['label'] ) ) {
                    continue;
                }
                $new_arr      = array(
                    'label'   => esc_html( $cat['label'] ),
                    'subcats' => array_map( 'esc_html', $cat['subcats'] ),
                );
                $val[ $key ] = $new_arr;
            }
        }
		return $val;
	}

    /**
	 * Escape podcast owner.
	 *
	 * @since  1.0.0
	 *
	 * @param array $cats Podcast Categories to be escaped and prepared.
	 */
	protected function owner( $val, $context ) {
        if ( ! isset( $val ) || ! is_array( $val ) ) {
            return array();
        }

        if ( 'sanitize' === $context ) {
            return array(
                'name'  => sanitize_text_field($val['name']),
                'email' => sanitize_email($val['email']),
            );
        } else {
            return array(
                'name'  => esc_html($val['name']),
                'email' => sanitize_email($val['email']),
            );
        }
	}

    /**
     * Properly format episode date.
     *
     * @since 1.0.0
     *
     * @param string $val
     * @param string $context
     */
    protected function date( $val, $context ) {
        if ( 'sanitize' === $context ) {
            // Date in GMT/UTC timezone.
            $date_parser = new Date_Parser();
            $timestamp = $date_parser->parse( $val );

            // Calculate Timezone Offset.
            $offset = $val ? date_create( $val ) : '';
            $offset = $offset ? date_format( $offset, 'Z' ) : '';
            $offset = $offset ? $offset : '';

            // Return Proper date.
            if ( $timestamp ) {
                return array(
                    'date'   => absint( $timestamp ),
                    'offset' => intval( $offset ),
                );
            } else {
                return array(
                    'date'   => 0,
                    'offset' => 0,
                );
            }
        } else {
            return $val;
        }
    }

    /**
     * Properly format emails.
     *
     * @since 1.0.0
     *
     * @param string $val
     * @param string $context
     */
    protected function email( $val, $context ) {
        return sanitize_email( $val );
    }

    /**
     * No change.
     *
     * @since 1.0.0
     *
     * @param string $val
     * @param string $context
     */
    protected function none( $val, $context ) {
        return $val;
    }

    /**
     * Get keys of all object properties.
     *
     * @since 6.5.0
     */
    public function getVars() {
        return get_object_vars( $this );
    }

    /**
     * Actual set method to update object properties.
     *
     * @since 1.0.0
     *
     * @param string|array $name
     * @param mixed  $value
     * @param string $context
     */
    public function set( $name, $value = false, $context = 'sanitize' ) {
        if ( ! is_array( $name ) ) {
            if ( property_exists( $this, $name ) ) {
                if ( 'none' === $context ) {
                    $this->$name = $value;
                    return true;
                }
                $sanitize_arr = $this->typeDeclaration();
                $sanitize = isset( $sanitize_arr[$name]) ? $sanitize_arr[$name] : 'string';
                if ( method_exists( $this, $sanitize ) ) {
                    $this->$name = $this->$sanitize( $value, $context );
                } else {
                    return false;
                }
                return true;
            }
            return false;
        }
        foreach( $name as $k => $v ) {
            $this->set( $k, $v, $context );
        }
    }

    /**
     * Get method to access a single object property.
     *
     * @since 1.0.0
     *
     * @param string|array $name
     * @param string $context echo or db.
     */
    public function get( $name, $context = 'echo' ) {
        if ( ! is_array( $name ) ) {
            if ( property_exists( $this, $name ) ) {
                $esc_arr = $this->typeDeclaration();
                $esc = isset( $esc_arr[$name] ) ? $esc_arr[$name] : 'string';
                return $this->$esc( $this->$name, $context );
            }
            return '';
        }
        $return = array();
        foreach ( $name as $key ) {
            $return[$key] = $this->get( $key, $context );
        }
        return $return;
    }
}
