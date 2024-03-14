<?php 

if(!class_exists( 'CFB_Functions' )){

    /**
     * Class CFB_Functions
     */
    class CFB_Functions {
    
        /**
         * Function to enqueue admin assets
         */
        public static function cfb_admin_assets(){
            // Get the post ID
            $id = get_the_ID();
            // Check if the post type is 'flipboxes' and the post status is 'publish'
            if( get_post_type($id) === 'flipboxes' && get_post_status($id) === 'publish' ){
                $prefix ='_cfb_';
                // Enqueue styles if enabled
                if (get_post_meta( $id, $prefix . 'bootstrap', true ) === 'enable') {
                    wp_enqueue_style( 'cfb-flexboxgrid-style');
                }
                if (get_post_meta( $id, $prefix . 'font', true ) === 'enable') {
                    wp_enqueue_style( 'cfb-fontawesome');
                }
                // Enqueue scripts
                CFB_Functions::cfb_enqueue_scripts();
            }
        }
    
        /**
         * Function to enqueue scripts
         */
        public static function cfb_enqueue_scripts(){    
            wp_enqueue_style( 'cfb-styles');
            wp_enqueue_script( 'cfb-jquery-flip');
            wp_enqueue_script( 'cfb-imagesloader');  
            wp_enqueue_script( 'cfb-custom-js');   
        }
    
        /**
         * Function to display live preview
         * @return string
         */ 
        public static function cfb_display_live_preview(){
            $output = '';
            // Check if post ID is set and not an array
            if( isset($_REQUEST['post']) && !is_array($_REQUEST['post'])){
                $id = $_REQUEST['post'];
                $output = do_shortcode("[flipboxes id='".$id."']") . '<br><br><p><strong class="micon-info-circled"></strong>Backend preview may be a little bit different from frontend / actual view. Add this shortcode on any page for frontend view - <code>[flipboxes id='.$id.']</code></p>';
            } else {
                $output = '<h4><strong class="micon-info-circled"></strong> Publish to preview the Flip Boxes.</h4>';
            }
            return $output;
        }
    
        /**
         * Function to check admin side post type page
         * @return string|null
         */
        public static function cfb_get_post_type_page() {
            global $post, $typenow, $current_screen;
            // we have defined a priority for determining the post type
            if ( $post && $post->post_type ){
                return $post->post_type;
            } elseif( $typenow ){
                return $typenow;
            } elseif( $current_screen && $current_screen->post_type ){
                return $current_screen->post_type;
            } elseif( isset( $_REQUEST['post_type'] ) ){
                return sanitize_key( $_REQUEST['post_type'] );
            } elseif ( isset( $_REQUEST['post'] ) ) {
                return get_post_type( sanitize_key( $_REQUEST['post'] ) );
            }
            // return null if no post type found
            return null;
        }
    }
}