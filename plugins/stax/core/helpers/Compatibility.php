<?php

namespace Stax;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Compatibility {
    /**
     * @var null
     */
    public static $instance = null;
    /**
     * @var string
     */
    public $theme;
    /**
     * @var bool
     */
    public $compatible = false;
    /**
     * @var string
     */
    public $tag = '';

    private $compatible_themes = [];

    /**
     * Compatibility constructor.
     */
    public function __construct() {

        $this->theme             = $this->get_current_theme();
        $this->compatible_themes = [
            'buddyapp'        => [
                'header' => [
                    'tag'           => '#header',
                    'front_actions' => function () {
                        remove_action( 'kleo_header', 'kleo_show_header', 12 );
                        add_action( 'kleo_header', function () {
                            echo '<div id="header" class="stax-loaded">';
                            Plugin::instance()->the_zone_html( 'header' );
                            echo '</div>';
                        }, 12 );

                    }
                ],
            ],
            'kleo'            => [
                'header' => [
                    'tag'           => '#header',
                    'front_actions' => function () {
                        remove_action( 'kleo_header', 'kleo_show_header' );
                        add_action( 'kleo_header', function () {
                            echo '<div id="header" class="stax-loaded">';
                            Plugin::instance()->the_zone_html( 'header' );
                            echo '</div>';
                        } );

                    }
                ],
            ],
            'sweetdate'       => [
                'header' => [
                    'tag'           => '.kleo-page header',
                    'front_actions' => function () {
                        remove_action( 'sweetdate_header', 'sweetdate_show_header' );
                        add_action( 'sweetdate_header', function () {
                            echo '<div id="header" class="stax-loaded">';
                            Plugin::instance()->the_zone_html( 'header' );
                            echo '</div>';
                        } );
                    }
                ],
            ],
            'twentyseventeen' => [
                'header' => [
                    'tag' => '#masthead'
                ]
            ],
            'x'               => [
                'header' => [
                    'tag' => 'header.masthead'
                ]
            ],
            'divi'            => [
                'header' => [
                    'tag' => '#main-header'
                ]
            ],
            'jupiter'         => [
                'header' => [
                    'tag'           => '.l-header',
                    'front_actions' => function () {
                        add_filter( 'get_header_style', function ( $style ) {
                            $style = 'custom';

                            return $style;
                        }, 999 );

                        add_action( 'wp_head', function () {
                            remove_all_actions( 'hb_grid_markup' );
                            add_action( 'hb_grid_markup', function () {
                                echo '<div class="l-header stax-loaded">';
                                Plugin::instance()->the_zone_html( 'header' );
                                echo '</div>';
                            } );
                        } );
                    }
                ],
            ],
            'impreza'         => [
                'header' => [
                    'tag'           => '.l-header',
                    'front_actions' => function () {
                        add_action( 'us_before_header', function () {
                            if ( class_exists( 'US_Layout' ) ) {
                                $us_layout              = \US_Layout::instance();
                                $us_layout->header_show = 'never';
                            }
                        } );
                        add_action( 'us_after_header', function () {
                            echo '<div class="l-header stax-loaded">';
                            Plugin::instance()->the_zone_html( 'header' );
                            echo '</div>';
                        } );
                    }
                ],
            ],
            'enfold'          => [
                'header' => [
                    'tag'           => '#header',
                    'front_actions' => function () {
                        add_filter( 'avf_header_setting_filter', function ( $header ) {
                            $header['disabled'] = true;

                            return $header;
                        } );

                        global $stax_header_rendered;
                        $stax_header_rendered = false;

                        add_action( 'get_template_part_includes/helper', function ( $slug, $name ) {
                            if ( 'main-menu' === $name ) {
                                global $stax_header_rendered;
                                if ( false === $stax_header_rendered ) {
                                    $stax_header_rendered = true;
                                    echo '<div id="header" class="stax-loaded">';
                                    Plugin::instance()->the_zone_html( 'header' );
                                    echo '</div>';
                                }
                            }
                        }, 10, 2 );
                    }
                ],
            ],
            'avada'           => [
                'header' => [
                    'tag'           => '.fusion-header-wrapper',
                    'front_actions' => function () {
                        remove_all_actions( 'avada_header', 20 );
                        add_action( 'avada_header', function () {
                            echo '<div class="fusion-header-wrapper stax-loaded">';
                            Plugin::instance()->the_zone_html( 'header' );
                            echo '</div>';
                        } );
                    }
                ],
            ],
        ];
    }

    public function get_compatible_themes() {
        return apply_filters( 'stax_compatible_themes', $this->compatible_themes );
    }

    /**
     * Get active theme name
     *
     * @return string
     */
    public function get_current_theme() {
        $slug = wp_get_theme( get_template() )->display( 'TextDomain' );
        if ( $slug ) {
            return $slug;
        }

        return strtolower( wp_get_theme( get_template() )->display( 'Name' ) );
    }

    /**
     * @return null|Compatibility
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * Get current theme CSS selector tag saved in database
     *
     * @param $zone
     *
     * @return string
     */
    public function get_tag_from_db( $zone = 'header' ) {

        $the_zone      = Model_Zones::instance()->get_by_slug( $zone );
        $current_theme = $this->get_current_theme();

        if ( $the_zone && isset( $the_zone['selector']->{$current_theme} ) ) {
            if ( isset( $the_zone['selector']->{$current_theme}->path ) && $the_zone['selector']->{$current_theme}->path !== '' ) {
                return apply_filters( 'stax_theme_tag', $the_zone['selector']->{$current_theme}->path, $this->get_current_theme(), 'db' );
            }
        }

        return false;

    }

    /**
     * Get current theme CSS selector tag from compatibility
     *
     * @param $zone
     *
     * @return string
     */
    public function get_tag_from_compatibility( $zone = 'header' ) {

        $themes = $this->get_compatible_themes();
        if ( isset( $themes[ $this->get_current_theme() ] ) ) {

            $theme = $themes[ $this->get_current_theme() ];
            if ( isset( $theme[ $zone ] ) && isset( $theme[ $zone ]['tag'] ) ) {
                return apply_filters( 'stax_theme_tag', $theme[ $zone ]['tag'], $this->get_current_theme(), 'compatibility' );
            }
        }

        return false;
    }


    /**
     * Get current theme CSS selector tag
     *
     * @param $zone
     *
     * @return string
     */
    public function get_tag( $zone = 'header' ) {

        /* Take it from database */
        if ( $db_tag = $this->get_tag_from_db( $zone ) ) {
            return $db_tag;
        }

        /* Take it from the compatibility */
        if ( $compat_tag = $this->get_tag_from_compatibility( $zone ) ) {
            return $compat_tag;
        }

        return '';
    }

    /**
     * If we can replace the content in front-end
     *
     * @param $zone
     *
     * @return boolean|array
     */
    public function get_front_actions( $zone = 'header' ) {

        $themes = $this->get_compatible_themes();
        if ( isset( $themes[ $this->get_current_theme() ] ) ) {

            $theme = $themes[ $this->get_current_theme() ];
            if ( isset( $theme[ $zone ] ) && isset( $theme[ $zone ]['front_actions'] ) ) {
                return $theme[ $zone ]['front_actions'];
            }
        }

        return false;
    }

    /**
     *
     */
    public function register() {

        // Get tag
        if ( $this->get_tag_from_db() && $this->get_tag_from_db() !== $this->get_tag_from_compatibility() ) {
            return;
        }

        $this->tag = $this->get_tag();

        if ( ! is_admin() && RenderStatus::instance()->getStatus() && $this->get_front_actions() !== false ) {

            /* If we are in front area and not editing */
            if ( Plugin::instance()->is_front() ) {
                call_user_func( $this->get_front_actions() );
            }
        }

    }
}
