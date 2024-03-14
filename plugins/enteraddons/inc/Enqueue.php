<?php
namespace Enteraddons\Inc;

/**
 * Enteraddons admin class
 *
 * @package     Enteraddons
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */

if( !defined( 'WPINC' ) ) {
    die;
}

if( !class_exists('Enqueue') ) {

    class Enqueue extends \Enteraddons\Core\Base\Enqueue_Base {

        public function assets_dir_url() {
            $this->assetsUrl = ENTERADDONS_DIR_ASSETS_URL;
        }

        public function set_scripts() {

            $dir_url = $this->assetsUrl;
            $getGoogleAPIKey = get_option(ENTERADDONS_OPTION_KEY);
            $getGoogleAPIKey = !empty( $getGoogleAPIKey['integration']['google_api_key'] ) ? $getGoogleAPIKey['integration']['google_api_key'] : '';

            // Style Flag
            $this->style = [

                [
                    'handle'        => 'fontawesome',
                    'url'           => $dir_url.'css/all.fontawesome.min.css',
                    'version'       => '2.3.4',
                    'register'      => true
                ],
                [
                    'handle'        => 'owl-carousel',
                    'url'           => $dir_url.'vandor/OwlCarousel/owl.carousel.min.css',
                    'version'       => '2.3.4',
                    'register'      => true
                ],
                [
                    'handle'        => 'swiper-slider',
                    'url'           => $dir_url.'vandor/swiper/swiper.min.css',
                    'version'       => '1.0.0',
                    'register'      => true
                ],
                [
                    'handle'        => 'magnific-popup',
                    'url'           => $dir_url.'vandor/magnific-popup/magnific-popup.css',
                    'version'       => '1.0.0',
                    'register'      => true
                ],
                [
                    'handle'        => 'twentytwenty',
                    'url'           => $dir_url.'vandor/twentytwenty/twentytwenty.css',
                    'version'       => '1.0.0',
                    'register'      => true
                ],
                [
                    'handle'        => 'enteraddons-global-style',
                    'url'           => $dir_url.'css/global.css',
                    'register'      => true
                ]
            ];

            // Scripts Flag
            $getScripts = [

                [
                    'handle'        => 'maps-googleapis',
                    'url'           => 'https://maps.googleapis.com/maps/api/js?key='.esc_attr( $getGoogleAPIKey ),
                    'version'       => '1.0.0',
                    'in_footer'     => false,
                    'register'      => true
                ],
                [
                    'handle'        => 'isotope-pkgd',
                    'url'           => $dir_url.'vandor/isotope/isotope.pkgd.min.js',
                    'version'       => '3.0.6',
                    'in_footer'     => true,
                    'register'      => true
                ],
                [
                    'handle'        => 'packery-mode-pkgd',
                    'url'           => $dir_url.'vandor/isotope/packery-mode.pkgd.min.js',
                    'version'       => '2.0.1',
                    'in_footer'     => true,
                    'register'      => true
                ],
                [
                    'handle'        => 'owl-carousel',
                    'url'           => $dir_url.'vandor/OwlCarousel/owl.carousel.min.js',
                    'version'       => '2.3.4',
                    'in_footer'     => true,
                    'register'      => true
                ],
                [
                    'handle'        => 'owlcarousel2-filter',
                    'url'           => $dir_url.'vandor/OwlCarousel/owlcarousel2-filter.min.js',
                    'version'       => '2.3.4',
                    'in_footer'     => true,
                    'register'      => true
                ],
                [
                    'handle'        => 'swiper-slider',
                    'url'           => $dir_url.'vandor/swiper/swiper.min.js',
                    'version'       => '1.0.0',
                    'in_footer'     => true,
                    'register'      => true
                ],
                [
                    'handle'        => 'perfect-scrollbar',
                    'url'           => $dir_url.'vandor/perfect-scrollbar/perfect-scrollbar.js',
                    'version'       => '1.4.0',
                    'in_footer'     => true,
                    'register'      => true
                ],
                [
                    'handle'        => 'anime',
                    'url'           => $dir_url.'vandor/anime/anime.min.js',
                    'version'       => '1.4.0',
                    'in_footer'     => true,
                    'register'      => true
                ],
                [
                    'handle'        => 'lottie-player',
                    'url'           => $dir_url.'vandor/lottie-player/lottie-player.js',
                    'version'       => '1.2.0',
                    'in_footer'     => true,
                    'register'      => true
                ],
                [
                    'handle'        => 'twentytwenty',
                    'url'           => $dir_url.'vandor/twentytwenty/jquery.twentytwenty.js',
                    'version'       => '1.2.0',
                    'in_footer'     => true,
                    'register'      => true
                ],
                [
                    'handle'        => 'event-move',
                    'url'           => $dir_url.'vandor/twentytwenty/jquery.event.move.js',
                    'version'       => '1.2.0',
                    'in_footer'     => true,
                    'register'      => true
                ],
                [
                    'handle'        => 'acme-ticker',
                    'url'           => $dir_url.'vandor/acmeticker/acmeticker.min.js',
                    'version'       => '1.0.1',
                    'dependency'    => array('jquery'),
                    'in_footer'     => true,
                    'register'      => true
                ],
                [
                    'handle'        => 'magnific-popup',
                    'url'           => $dir_url.'vandor/magnific-popup/jquery.magnific-popup.min.js',
                    'version'       => '1.0.0',
                    'in_footer'     => true,
                    'register'      => true
                ],
                [
                    'handle'        => 'image-hotspot',
                    'url'           => $dir_url.'vandor/hotspot/jquery.hotspot.js',
                    'version'       => '1.0.0',
                    'in_footer'     => true,
                    'register'      => true
                ],
                [
                    'handle'        => 'countdown',
                    'url'           => $dir_url.'vandor/countdown/countdown.min.js',
                    'version'       => '1.0.0',
                    'dependency'    => array( 'jquery'),
                    'in_footer'     => true,
                    'register'      => true
                ],
                [
                    'handle'        => 'waypoints',
                    'url'           => $dir_url.'vandor/waypoints/waypoints.min.js',
                    'version'       => '1.6.2',
                    'dependency'    => array( 'jquery'),
                    'in_footer'     => true,
                    'register'      => true
                ],
                [
                    'handle'        => 'image-zoom-magnifier',
                    'url'           => $dir_url.'vandor/image-magnifier/BUP.js',
                    'version'       => '1.0.0',
                    'dependency'    => array( 'jquery' ),
                    'in_footer'     => true,
                    'register'      => true
                ],
                [
                    'handle'        => 'counterup',
                    'url'           => $dir_url.'vandor/counterup/jquery.counterup.min.js',
                    'version'       => '1.0.0',
                    'dependency'    => array( 'jquery'),
                    'in_footer'     => true,
                    'register'      => true
                ],
                [
                    'handle'        => 'typed',
                    'url'           => $dir_url.'vandor/typed/typed.min.js',
                    'version'       => '1.0.0',
                    'dependency'    => array( 'jquery'),
                    'in_footer'     => true,
                    'register'      => true
                ],
                [
                    'handle'        => 'enteraddons-main',
                    'url'           => $dir_url.'js/enteraddons.js',
                    'dependency'    => array( 'jquery' ),
                    'in_footer'     => true,
                    'register'      => true
                ]

            ];
            
            $this->script = apply_filters( 'enteraddons_js_scripts', $getScripts );
            //
            $this->localize = [
                [
                    'handle'        => 'enteraddons-main',
                    'object_name'   => 'enteraddonsMainObject',
                    'data'          => array('ajax_url' => admin_url('admin-ajax.php'))

                ]
            ];

        }



    } // End Class

} // End Condition 
