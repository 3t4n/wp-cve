<?php

/**
 * Fired during plugin activation
 *
 * @link       test
 * @since      1.0.0
 *
 * @package    Google_Reviews
 * @subpackage Google_Reviews/includes
 */
/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Google_Reviews
 * @subpackage Google_Reviews/includes
 * @author     David Maucher <hallo@maucher-online.com>
 */
class GRWP_Google_Reviews_Activator
{
    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate()
    {
        // add dummy content setting as default
        
        if ( !get_option( 'google_reviews_option_name' ) ) {
            $google_reviews_options = [];
            $google_reviews_options['show_dummy_content'] = '1';
            $google_reviews_options['serp_business_name'] = '';
            $google_reviews_options['serp_data_id'] = '';
            $google_reviews_options['style_2'] = 'Slider';
            $google_reviews_options['layout_style'] = 'layout_style-7';
            $google_reviews_options['filter_below_5_stars'] = '1';
            $google_reviews_options['filter_words'] = '';
            $google_reviews_options['reviews_language_3'] = 'en';
            add_option( 'google_reviews_option_name', $google_reviews_options );
        }
        
        // add place info field
        if ( !get_option( 'grwp_place_info' ) ) {
            add_option( 'grwp_place_info', '' );
        }
        // add pro version results field
        if ( !get_option( 'gr_latest_results' ) ) {
            add_option( 'gr_latest_results', '' );
        }
        // add free version results field
        if ( !get_option( 'gr_latest_results_free' ) ) {
            add_option( 'gr_latest_results_free', '' );
        }
    }

}