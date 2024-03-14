<?php

 


##############################################################

function woo_fattureincloud_notice() {

    $user_id = get_current_user_id();

    if ( !get_user_meta( $user_id, 'woo_fattureincloud_notice_dismissed' ) && ( get_option('count_load_time_woo_fattureincloud') > get_user_meta( $user_id, 'woo_fattureincloud_notice_maybe_delay', true)   ) ) 
    

        echo '<div class="notice updated">
               
                <p>
                    
                    <span style="padding: 62px; ">

                    '. get_user_meta( $user_id, 'woo_fattureincloud_notice_dismissed', true ) .' 
        
                    '. __( "Enjoying the WooCommerce Fattureincloud Plugin and you would like to leave a five star review?", "woo-fattureincloud" ) .'
                                                   
                        
                                <a href="https://wordpress.org/support/plugin/woo-fattureincloud/reviews/?filter=5" target="_blank" ><span class="dashicons dashicons-external" style="text-decoration: none"></span>   '. __( "Sure!", "woo-fattureincloud" ).'</a>
                        
                                <span> ||| </span>

                                <a href="'.admin_url().'?woo_fattureincloud_maybe"> <span class="dashicons dashicons-clock" style="text-decoration: none"></span> '. __( " Maybe later", "woo-fattureincloud" ) .'</a>

                                <span> ||| </span>

                                <a href="'.admin_url().'?woo_fattureincloud_dismissed" > <span class="dashicons dashicons-yes-alt" style="text-decoration: none"></span> '. __( "I've already done it! ", "woo-fattureincloud" ).'</a>

                                <span> ||| </span>
                               
                                <a href="'.admin_url().'?woo_fattureincloud_dismissed" > <span class="dashicons dashicons-dismiss" style="text-decoration: none"></span> '. __( "No, thanks ", "woo-fattureincloud" ).'</a>
                    
                    </span>
            
                </p>
            
            </div>';

}





##########################################################
   
      
   
    function woo_fattureincloud_notice_maybe() {
    
        $user_id = get_current_user_id();
    
        if ( isset( $_GET['woo_fattureincloud_maybe'] ) )

            if (get_user_meta( $user_id, 'woo_fattureincloud_notice_maybe_delay') > 0) {

                $count = get_option('count_load_time_woo_fattureincloud');

                $delay = $count + 200;

                update_user_meta( $user_id, 'woo_fattureincloud_notice_maybe_delay', $delay );

            }

            
    }

    add_action( 'admin_init', 'woo_fattureincloud_notice_maybe' );

    
#####################################################

   
    function woo_fattureincloud_notice_dismissed() {

        $user_id = get_current_user_id();
        
        if ( isset( $_GET['woo_fattureincloud_dismissed'] ) ) {
        
            add_user_meta( $user_id, 'woo_fattureincloud_notice_dismissed', 'true', true );

            delete_user_meta( $user_id, 'woo_fattureincloud_notice_maybe_delay');

            delete_option('count_load_time_woo_fattureincloud');

       }

    }
    
    add_action( 'admin_init', 'woo_fattureincloud_notice_dismissed' );


#####################################################


    add_action( 'admin_init', 'count_woo_fattureincloud_function');



    function count_woo_fattureincloud_function() {

        $user_id = get_current_user_id();
       
       
            if (!get_option('count_load_time_woo_fattureincloud') && !get_user_meta( $user_id, 'woo_fattureincloud_notice_dismissed' ) ) {

                update_option('count_load_time_woo_fattureincloud', 1);

            } elseif (get_option('count_load_time_woo_fattureincloud') > 0 && !get_user_meta( $user_id, 'woo_fattureincloud_notice_dismissed' ) ) {

                $count = get_option('count_load_time_woo_fattureincloud');

                update_option('count_load_time_woo_fattureincloud', $count +1);

            }

            if (get_option('count_load_time_woo_fattureincloud') > 200) {

            add_action( 'admin_notices', 'woo_fattureincloud_notice' );

            }

    }
    


