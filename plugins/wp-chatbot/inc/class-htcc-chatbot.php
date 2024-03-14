<?php
/**
 * check condtions to display messenger or not
 * get app id
 * get page id
 * and add it to script, div
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'HTCC_Chatbot' ) ) :

class HTCC_Chatbot {

    // if sdk is not added then dont add - customer chat html content
    public $sdk_added = 'no';
    public $sdk_added_for_shortcode = 'no';
    public $htcc_js_options;

    public $api;

    public function __construct()
    {
        $this->api = new MobileMonkeyApi();
        $this->htcc_js_options = get_option('htcc_fb_js_src');
    }

    /**
     * load Customer Chat SDK at header
     *  if shortcode is used load sdk - even if hided based on other way.
     */
    public function chatbot() {

    $htcc_options = ht_cc()->variables->get_option;
    $shortcode_name = isset($htcc_options['shortcode'])?esc_attr( $htcc_options['shortcode'] ):'';

    $is_mobile = ht_cc()->device_type->is_mobile;

    /**
     * shortocode can add or have to work only on singular pages ..
     *
     * so check for shortocode in singular post ..
     * if shortocode exists - load sdk - and dont load cc code ..
     *
     * and for not singular post .. or is shortcode not exist ..
     * then check for other conditions ..
     * and load sdk, cc code ..
     *
     */

    //  check shortcode exists only on singular post .. if yes load sdk .. and dont load cc code
    if ( is_singular() ) {

        global $post;
        if ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, $shortcode_name ) ) {
            // If shortcode is added in this page - add sdk.

            // this will be useful at $this->customer_chat()
            $this->sdk_added_for_shortcode = 'yes';
        }
    }


    /**
     * if shortcode is not added .. ( in singular post ) - then check for other conditons ..
     * if in a singular post - shortcode is added - then no need to check other conditons
     * as sdk is need for shortcode ..
     */
     if ( 'yes' !== $this->sdk_added_for_shortcode  ) {
        // check for conditions that any thing is hiding
        // - other then place where shortcode added in singular posts

        // in pro version .. hide base on device will be handle by js
        // not equal to true
        if ( 'true' !== HTCC_PRO ) {
            // Hide based on Devices - Mobile, Desktop
            if ( 'yes' == $is_mobile ) {
                if ( isset( $htcc_options['fb_hide_mobile'] ) ) {
                    return;
                }
            } else {
                if ( isset( $htcc_options['fb_hide_desktop'] ) ) {
                    return;
                }
            }
        }


        // single post
        if ( is_single() && isset( $htcc_options['hideon_posts'] ) ) {
            return;
        }

        // single page - but not on home page, front page
        if ( is_page() && isset( $htcc_options['hideon_page'] ) ) {
            if ( ( !is_home() ) && ( !is_front_page() ) ) {
                return;
            }
        }

        if ( is_home() && isset( $htcc_options['hideon_homepage'] ) ) {
            return;
        }

        if ( is_front_page() && isset( $htcc_options['hideon_frontpage'] ) ) {
            return;
        }

        if ( is_category() && isset( $htcc_options['hideon_category'] ) ) {
            return;
        }

        if ( is_archive() && isset( $htcc_options['hideon_archive'] ) ) {
            return;
        }

        if ( is_404() && isset( $htcc_options['hideon_404'] ) ) {
            return;
        }


        $this_page_id = get_the_ID();
        $pages_list_tohide = isset($htcc_options['list_hideon_pages'])?esc_attr( $htcc_options['list_hideon_pages'] ):'';
        $pages_list_tohide_array = explode(',', $pages_list_tohide);

        if( ( is_single() || is_page() ) && in_array( $this_page_id, $pages_list_tohide_array ) ) {
            return;
        }

        // Hide styles on this catergorys - list
        $list_hideon_cat = isset($htcc_options['list_hideon_cat'])?esc_attr( $htcc_options['list_hideon_cat'] ):'';;


		 $activePage = $this->api->getActivePage();
		 if (!$activePage) {
			 delete_option('htcc_fb_js_src');
			 $this->htcc_js_options = '';
         }
        if( $list_hideon_cat ) {
            //  Get current post Categorys list and create an array for that..
            $current_categorys_array = array();
            $current_categorys = get_the_category();
            foreach ( $current_categorys as $category ) {
                $current_categorys_array[] = strtolower($category->name);
            }

            $list_hideon_cat_array = explode(',', $list_hideon_cat);

            foreach ( $list_hideon_cat_array as $category ) {
                $category_trim = trim($category);
                if ( in_array( strtolower($category_trim), $current_categorys_array ) ) {
                    return;
                }
            }
        }
    }
    ?>
        <script async="async" src='<?php echo $this->htcc_js_options?>'></script>
    <?php

    // After sdk is added
    // for customer_chat(), var htcc_values
    $this->sdk_added = 'yes';

    }



    // cc code - customer chat code
    public function customer_chat() {
        // instead of adding the cc code in header added like this ..
        // can check more conditions as calling from footer .. woocommerce or so ...

        /**
         * update values for woocommerce selected pages
         *
         * placeholders .. these are final values that place in cc code ..
         * localize this values if need to use in js - Actions ..
         *
         */
        if ( 'true' == HTCC_PRO ) {
            include_once HTCC_PLUGIN_DIR . 'inc/pro/htcc-pro-woo.php';
            include_once HTCC_PLUGIN_DIR . 'inc/pro/htcc-pro-values.php';
        }

        // custom image - call this if sdk only..
        // call this before - checking sdk_added_for_shortcode
        if ( 'true' == HTCC_PRO ) {
            include_once HTCC_PLUGIN_DIR . 'inc/pro/htcc-pro-custom-image.php';
        }


        // make this check only after included - htcc-pro-values.php - using if statement

        // if sdk added for shortcode then this cc code not needed to add .. so return
        // cc code will added by shortocodes ..
        if ( 'yes' == $this->sdk_added_for_shortcode ) {
            return;
        }
        // if sdk added then only load this
        if ( 'no' == $this->sdk_added ) {
            return;
        }
		$activePage = $this->api->getActivePage();
            if (!$activePage) {
                delete_option('htcc_fb_js_src');
                $this->htcc_js_options = '';
            }

        if ( 'true' == HTCC_PRO ) {
            ?>
            <!-- Add Messenger - wp-chatbot pro - HoliThemes - https://www.holithemes.com/wp-chatbot -->
            <div id="htcc-messenger" class="htcc-messenger">

            </div>
            <!-- / Add Messenger - wp-chatbot pro - HoliThemes -->

            <?php
        } else {
            ?>
            <!-- Add Messenger - wp-chatbot - HoliThemes - https://www.holithemes.com/wp-chatbot -->
            <script async="async" src='<?php echo $this->htcc_js_options?>'></script>
            <!-- / Add Messenger - wp-chatbot - HoliThemes -->
            <?php
        }

    }


}

$chatbot = new HTCC_Chatbot();
add_action( 'wp_head', array( $chatbot, 'chatbot' ), 1 );


endif; // END class_exists check