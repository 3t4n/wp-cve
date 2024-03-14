<?php

Class GRWP_Free_API_Service {

    public function __construct() {

        // Pull reviews ajax handler
        add_action('wp_ajax_get_reviews_free_api', [$this, 'get_reviews_free_api']);
        add_action('wp_ajax_nopriv_get_reviews_free_api', [$this, 'get_reviews_free_api']);

    }

    /**
     * Get reviews from Free API
     * @return void
     */
    public static function get_reviews_free_api( $is_cron = false ) {

        if ( ! $is_cron ) {
            $place_id = isset($_GET['place_id']) ? sanitize_text_field($_GET['place_id']) : '';
            $language = isset($_GET['language']) ? sanitize_text_field($_GET['language']) : 'en';
        }
        else {
            $google_reviews_options = get_option( 'google_reviews_option_name' );
            $place_id = $google_reviews_options['gmb_id_1'];
            $language = $google_reviews_options['reviews_language_3'] ?? 'en';
        }

        $site = urlencode(get_site_url());
        $admin_email = urlencode(get_option('admin_email'));

        $url = sprintf(
            'https://api.reviewsembedder.com/free-api.php?gmb=%s&language=%s&site=%s&mail=%s',
            $place_id,
            $language,
            $site,
            $admin_email
        );

        $result = wp_remote_get($url);

        $get_results = json_decode( wp_remote_retrieve_body( $result ) );

        if ( isset( $get_results->status) && $get_results->status == 'INVALID_REQUEST' ) {

            if ( ! $is_cron ) {

                wp_send_json_error(new WP_Error($get_results->status), 404);

            }

            die();

        }

        else if ( isset( $get_results->result ) && ! $is_cron ) {

            update_option('gr_latest_results_free', json_encode($get_results->result));

            wp_send_json_success( array(
                'html' => $get_results->result
            ) );

            die();

        }

        // only run if $is_cron == true and no errors detected
        else {
            update_option('gr_latest_results_free', json_encode($get_results->result));
        }

        die();

    }

    /**
     * Parse json results of Free API and check for errors
     * @return mixed|WP_Error
     */
    public static function parse_free_review_json() {

        $raw =  get_option('gr_latest_results_free');
        $reviewArr = json_decode($raw, true);
        $result = isset($reviewArr['reviews']) ? $reviewArr['reviews'] : [];

        return $result;

    }

}
