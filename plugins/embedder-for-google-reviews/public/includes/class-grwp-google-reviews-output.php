<?php

/**
 * Define methods for public html output via shortcodes etc.
 */
class GRWP_Google_Reviews_Output
{
    /**
     * Whether to show dummy content
     * @var bool
     */
    protected  $showdummy = false ;
    /**
     * Allowed HTML and HTML attributes
     * @var array
     */
    protected  $allowed_html ;
    /**
     * Gobal reviews data
     * @var array|null
     */
    protected  $reviews ;
    /**
     * Flag if reviews are erroneous
     * @var bool
     */
    protected  $reviews_have_error = false ;
    protected  $rating_rounded = 0 ;
    protected  $rating_formatted = 5.0 ;
    protected  $total_reviews = 0 ;
    protected  $place_title = '' ;
    /**
     * Constructor method
     */
    public function __construct()
    {
        require_once __DIR__ . '/allowed-html.php';
        global  $allowed_html ;
        $this->allowed_html = $allowed_html;
        $this->options = get_option( 'google_reviews_option_name' );
        $this->showdummy = isset( $this->options['show_dummy_content'] );
        $this->reviews = $this->get_review_data();
        $this->set_place_info();
        // check for errors and set flag
        if ( is_wp_error( $this->reviews ) || $this->reviews == '' || $this->reviews == null || !is_array( $this->reviews ) ) {
            $this->reviews_have_error = true;
        }
    }
    
    /**
     * Get place info
     */
    protected function set_place_info()
    {
        $all_options = get_option( 'google_reviews_option_name' );
        $data_id = $all_options['serp_data_id'];
        $place_raw = get_option( 'grwp_place_info' );
        $place_info = ( isset( $place_raw[$data_id] ) ? $place_raw[$data_id] : null );
        
        if ( $place_info ) {
            $place_info_arr = json_decode( $place_info, true );
            $this->rating_rounded = intval( round( $place_info_arr['rating'] ) );
            $this->rating_formatted = number_format_i18n( $place_info_arr['rating'], 1 );
            $this->total_reviews = number_format_i18n( $place_info_arr['reviews'] );
            $this->place_title = $place_info_arr['title'];
        }
    
    }
    
    /**
     * Get total star rating html
     */
    protected function get_total_stars()
    {
        $path = esc_attr( GR_PLUGIN_DIR_URL );
        $star = sprintf( '<img src="%sdist/images/svg-star.svg" alt="" />', $path );
        $star_output = '<span class="grwp_stars-wrapper">';
        if ( $this->rating_rounded === 0 ) {
            $this->rating_rounded = 5;
        }
        for ( $i = 1 ;  $i <= $this->rating_rounded ;  $i++ ) {
            $star_output .= $star;
        }
        $star_output .= '</span>';
        return $star_output;
    }
    
    /**
     * Prepare time string for reviews
     * @param $datetime
     * @param $full
     * @return string|null
     * @throws Exception
     */
    protected function time_elapsed_string( $datetime, $full = false )
    {
        $now = new DateTime();
        $ago = new DateTime( $datetime );
        $diff = $now->diff( $ago );
        $diff->w = floor( $diff->d / 7 );
        $diff->d -= $diff->w * 7;
        $string = array(
            'y' => array( __( 'year', 'grwp' ), __( 'years', 'grwp' ) ),
            'm' => array( __( 'month', 'grwp' ), __( 'months', 'grwp' ) ),
            'w' => array( __( 'week', 'grwp' ), __( 'weeks', 'grwp' ) ),
            'd' => array( __( 'day', 'grwp' ), __( 'days', 'grwp' ) ),
            'h' => array( __( 'hour', 'grwp' ), __( 'hours', 'grwp' ) ),
            'i' => array( __( 'minute', 'grwp' ), __( 'minutes', 'grwp' ) ),
            's' => array( __( 'second', 'grwp' ), __( 'seconds', 'grwp' ) ),
        );
        foreach ( $string as $k => &$v ) {
            
            if ( $diff->{$k} ) {
                $v = $diff->{$k} . ' ' . (( $diff->{$k} > 1 ? $v[1] : $v[0] ));
            } else {
                unset( $string[$k] );
            }
        
        }
        if ( !$full ) {
            $string = array_slice( $string, 0, 1 );
        }
        // standard time string for English
        $time_string = ( $string ? implode( ', ', $string ) . __( ' ago', 'grwp' ) : __( 'just now', 'grwp' ) );
        // reverse string arrangement for non English sites
        $language_code = get_locale();
        if ( substr( $language_code, 0, 3 ) !== 'en_' ) {
            $time_string = ( $string ? __( ' ago', 'grwp' ) . implode( ', ', $string ) : __( 'just now', 'grwp' ) );
        }
        // allow filtering for edge cases
        $time_string = apply_filters(
            'grwp_filter_time_string',
            $time_string,
            $string,
            $diff
        );
        return $time_string;
    }
    
    /**
     * Count and prepare stars
     * @param $review
     * @return string
     */
    protected function get_star_output( $review )
    {
        $path = esc_attr( GR_PLUGIN_DIR_URL );
        $star = sprintf( '<img src="%sdist/images/svg-star.svg" alt="" />', $path );
        $star_output = '<span class="stars-wrapper">';
        for ( $i = 1 ;  $i <= $review['rating'] ;  $i++ ) {
            $star_output .= $star;
        }
        $star_output .= '</span>';
        $star_output .= sprintf( '<span class="time">%s</span>', $review['time'] );
        return $star_output;
    }
    
    /**
     * Get dummy review content
     * @return array[]
     */
    protected function get_dummy_content()
    {
        $reviews = array( array(
            'author_name'               => __( 'Lorem Ipsum', 'grwp' ),
            'author_url'                => '#',
            'language'                  => 'en',
            'profile_photo_url'         => GR_PLUGIN_DIR_URL . 'dist/images/sample-photo.png',
            'rating'                    => 5,
            'relative_time_description' => __( 'three months ago', 'grwp' ),
            'text'                      => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', 'grwp' ),
            'time'                      => '1643630205',
        ) );
        for ( $i = 0 ;  $i <= 4 ;  $i++ ) {
            $reviews[] = $reviews[0];
        }
        return $reviews;
    }
    
    /**
     * Check if review should be left out, due to global settings
     * @param $rating
     * @param $text
     * @return bool
     */
    protected function step_over_review( $rating, $text )
    {
        if ( !grwp_fs()->can_use_premium_code() ) {
            return false;
        }
        // step over rating if minimum stars are not met
        
        if ( isset( $this->options['filter_below_5_stars'] ) && $this->options['filter_below_5_stars'] ) {
            $min_rating = intval( $this->options['filter_below_5_stars'] );
            if ( $rating < $min_rating ) {
                return true;
            }
        }
        
        // step over rating if review has no text
        if ( isset( $this->options['exclude_reviews_without_text'] ) && $this->options['exclude_reviews_without_text'] ) {
            if ( $text == '' ) {
                return true;
            }
        }
        // step over rating if review contains certain words
        
        if ( isset( $this->options['filter_words'] ) && $this->options['filter_words'] !== '' ) {
            $words_str = rtrim( $this->options['filter_words'], ',' );
            $words_arr = explode( ',', $words_str );
            foreach ( $words_arr as $word ) {
                $word = trim( $word );
                if ( str_contains( $text, $word ) ) {
                    return true;
                }
            }
        }
        
        return false;
    }
    
    /**
     * Prepare and map review data
     * @param $reviews_raw
     * @return array
     */
    protected function map_review_data( $reviews_raw, $use_new_api_results )
    {
        // map reviews from different raw data to universal format
        $reviews = [];
        foreach ( $reviews_raw as $review ) {
            // assign dummy content
            
            if ( $this->showdummy ) {
                $name = $review['author_name'];
                $author_url = $review['author_url'];
                $profile_photo_url = $review['profile_photo_url'];
                $rating = $review['rating'];
                $text = $review['text'];
                $time = $this->time_elapsed_string( date( 'Y-m-d h:i:s', $review['time'] ) );
            } else {
                // use different array keys for pro version results
                
                if ( grwp_fs()->is__premium_only() || $use_new_api_results ) {
                    $name = ( isset( $review['user']['name'] ) ? $review['user']['name'] : '' );
                    $author_url = ( isset( $review['user']['link'] ) ? $review['user']['link'] : '' );
                    $profile_photo_url = ( isset( $review['user']['thumbnail'] ) ? $review['user']['thumbnail'] : '' );
                    $rating = ( isset( $review['rating'] ) ? $review['rating'] : 5 );
                    $text = ( isset( $review['snippet'] ) ? $review['snippet'] : '' );
                    $time = ( isset( $review['date'] ) ? $review['date'] : '' );
                } else {
                    $name = $review['author_name'];
                    $author_url = $review['author_url'];
                    $profile_photo_url = $review['profile_photo_url'];
                    $rating = $review['rating'];
                    $text = $review['text'];
                    $time = $this->time_elapsed_string( date( 'Y-m-d h:i:s', $review['time'] ) );
                }
            
            }
            
            // check if rating should be left out, due to global settings
            if ( $this->step_over_review( $rating, $text ) ) {
                continue;
            }
            $reviews[] = [
                'name'              => $name,
                'author_url'        => $author_url,
                'profile_photo_url' => $profile_photo_url,
                'rating'            => $rating,
                'text'              => $text,
                'time'              => $time,
            ];
        }
        return $reviews;
    }
    
    /**
     * Get raw review data from database
     * @return void|array
     */
    protected function get_review_data()
    {
        $use_new_api_results = false;
        // if dummy setting is active, get dummy content
        
        if ( $this->showdummy ) {
            $reviews_raw = $this->get_dummy_content();
        } else {
            // get reviews data from prior versions, if possible
            $reviews_raw = GRWP_Free_API_Service::parse_free_review_json();
            // if no old reviews data, get new reviews data
            
            if ( count( $reviews_raw ) === 0 ) {
                $reviews_raw = GRWP_Pro_API_Service::parse_pro_review_json();
                $use_new_api_results = true;
            }
        
        }
        
        return $this->map_review_data( $reviews_raw, $use_new_api_results );
    }

}