<?php
/**
 * Post Like Metabox
 *
 * @class       TP_Education_Like_Metabox
 * @since       1.0
 * @package     TP Education
 * @category    Class
 * @author      Theme Palace
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class TP_Education_Like_Metabox {

    public function __construct()
    {
        add_action( 'wp_ajax_tp_education_like', array( $this, 'tp_education_like' ) );
        add_action( 'wp_ajax_nopriv_tp_education_like', array( $this, 'tp_education_like' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'tp_education_like_script_enqueuer' ) );
    }

    public function tp_education_like( $post_type )
    {
        /**
         * Add meta box
         */
        
        if ( !wp_verify_nonce( $_REQUEST['nonce'], "tp_education_nonce" ) ) {
            exit("No naughty business please");
        }   
        $visitor_ip = $_SERVER['REMOTE_ADDR'];
        $like_count = get_post_meta($_REQUEST["post_id"], "likes", true);
        $like_count = ($like_count == '') ? 0 : $like_count;
        $new_like_count = $like_count+1;

        $get_ip = get_post_meta( $_REQUEST["post_id"], 'vistors_ip_value', $single = true );
        

        if ( $get_ip != $visitor_ip ) :
            update_post_meta( $_REQUEST["post_id"], 'vistors_ip_value', $visitor_ip );
            $like = update_post_meta($_REQUEST["post_id"], "likes", $new_like_count);
        else :
            $like = false;
        endif;

        if($like === false) {
            $result['type'] = "error";
            $result['like_count'] = $like_count;
        }
        else {
            $result['type'] = "success";
            $result['like_count'] = $new_like_count;
        }

        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $result = json_encode($result);
            echo $result;
        }
        else {
            header("Location: ".$_SERVER["HTTP_REFERER"]);
        }

        die();

    }

    public function tp_education_like_script_enqueuer() {
        /**
         * Enqueue scripts
         */
        
        wp_register_script( "tp_education_script", TP_EDUCATION_URL_PATH . 'assets/js/tp-education-like.min.js', array('jquery'), true );
        wp_localize_script( 'tp_education_script', 'tp_education_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );        
        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'tp_education_script' );

    }

    public function tp_education_custom_like( $post_id ) {
        /**
         * Meta box layout
         */
        
        $nonce = wp_create_nonce( 'tp_education_nonce' );
        $likes = get_post_meta( $post_id, "likes", true);
        $likes = ( $likes == null ) ? 0 : $likes;
        $link = admin_url( 'admin-ajax.php?action=tp_education_like&post_id=' . absint( $post_id ) );

        if ( is_single() ) {
            $output = '<a class="user_like btn" data-nonce="' . $nonce . '" data-post_id="' . absint( $post_id ) . '" href="' . esc_url( $link ) . '"><i class="fa fa-heart"></i></a><span id="like_counter" class="likes-number">' . absint( $likes ) . '</span>';
        } else {
            $output = '<i class="fa fa-heart"></i><span id="like_counter" class="likes-number">' . absint( $likes ) . '</span>';
        }
 
        return $output;
    }

}

new TP_Education_Like_Metabox();

