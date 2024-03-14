<?php

function mpsp_setActivationDate() {
 
    $now = strtotime( "now" );
    add_option( 'mpsp_activation_date', $now );
}
register_activation_hook( __FILE__, 'mpsp_setActivationDate' );

function mpsp_check_installation_date() {
 
    $nobug = "";
    $nobug = get_option('mpsp_no_bugs');
 
    if (!$nobug) {
 
        $install_date = get_option( 'mpsp_activation_date' );
        $past_date = strtotime( '-1 days' );
 
        if ( $past_date >= $install_date ) {
 
            add_action( 'admin_notices', 'mpsp_display_admin_notice' );
 
        }
 
    }
 
}
add_action( 'admin_init', 'mpsp_check_installation_date' );
 
function mpsp_display_admin_notice() {
 
    $reviewurl = 'https://wordpress.org/support/plugin/posts-slider/reviews/?rate=5#new-post';
 
    $nobugurl = get_admin_url() . '?mpspdontbug=1';

    $install_date = get_option( 'mpsp_activation_date' );
 
    echo '<div class="updated" style="padding:10px 5px; font-size:16px;">'; 
 
    echo( __( "You have been using our Posts Slider for more than a week now, do you like it? If so, please leave us a review with your feedback! <a href=".$reviewurl." target='_blank' class='button button-primary' style='margin:0 20px;'>Leave A Review</a> <a href=".$nobugurl." style='font-size:9px;'>Leave Me Alone</a>" ) ); 
 
    echo "</div>";
}

function mpsp_set_no_bug() {
 
    $nobug = "";
 
    if ( isset( $_GET['mpspdontbug'] ) ) {
        $nobug = esc_attr( $_GET['mpspdontbug'] );
    }
 
    if ( 1 == $nobug ) {
 
        add_option( 'mpsp_no_bugs', TRUE );
 
    }
 
} add_action( 'admin_init', 'mpsp_set_no_bug', 5 );

?>