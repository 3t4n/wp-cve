<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class INS_PROMO_NOTICE {

    // private $api_url = 'http://ins-api.test/';
    private $api_url = 'https://api.themefic.com/';
    private $args = array();
    private $responsed = false; 
    private $ins_promo_option = false; 
    private $error_message = ''; 

    private $months = ['January', 'June', 'November', 'December'];
    private $plugins_existes = ['uacf7', 'tf', 'beaf', 'ebef'];

    public function __construct() {

        if(in_array(date('F'), $this->months) && !class_exists('WOOINS')){  
            add_filter('cron_schedules', array($this, 'ins_custom_cron_interval'));
             
            if (!wp_next_scheduled('ins_promo__schudle')) {
                wp_schedule_event(time(), 'ins_every_day', 'ins_promo__schudle');
            }
            
            add_action('ins_promo__schudle', array($this, 'ins_promo__schudle_callback'));
          

            if(get_option( 'ins_promo__schudle_option' )){
                $this->ins_promo_option = get_option( 'ins_promo__schudle_option' );
            }
              
           
            // Admin Notice 
            $tf_existes = get_option( 'tf_promo_notice_exists' );
            if( ! in_array($tf_existes, $this->plugins_existes) && is_array($this->ins_promo_option) && strtotime($this->ins_promo_option['end_date']) > time() && strtotime($this->ins_promo_option['start_date']) < time()){
                add_action( 'admin_notices', array( $this, 'tf_black_friday_2023_admin_notice' ) );
                add_action( 'wp_ajax_tf_black_friday_notice_dismiss_callback', array($this, 'tf_black_friday_notice_dismiss_callback') );
            }

            // side Notice Woo Product Meta Box Notice 
            $tf_woo_existes = get_option( 'tf_promo_notice_woo_exists' );
             if( is_array($this->ins_promo_option) && strtotime($this->ins_promo_option['end_date']) > time() && strtotime($this->ins_promo_option['start_date']) < time()){   
                add_action( 'add_meta_boxes', array($this, 'tf_black_friday_2023_woo_product') );
                
	            add_filter( 'get_user_option_meta-box-order_product', array($this, 'metabox_order') );
                add_action( 'wp_ajax_ins_black_friday_notice_ins_dismiss_callback', array($this, 'ins_black_friday_notice_ins_dismiss_callback') ); 
           
            } 
            
			
            register_deactivation_hook( INS_PATH . 'instantio.php', array($this, 'ins_promo_notice_deactivation_hook') );
             
            
        }

        
       
    }

    public function ins_get_api_response(){
        $query_params = array(
            'plugin' => 'ins', 
        );
        $response = wp_remote_post($this->api_url, array(
            'body'    => json_encode($query_params),
            'headers' => array('Content-Type' => 'application/json'),
        )); 
        if (is_wp_error($response)) {
            // Handle API request error
            $this->responsed = false;
            $this->error_message = esc_html($response->get_error_message());
 
        } else {
            // API request successful, handle the response content
            $data = wp_remote_retrieve_body($response);
           
            $this->responsed = json_decode($data, true); 

            $ins_promo__schudle_option = get_option( 'ins_promo__schudle_option' ); 
            if(!empty($ins_promo__schudle_option) && $ins_promo__schudle_option['notice_name'] != $this->responsed['notice_name']){ 
                // Unset the cookie variable in the current script
                update_option( 'ins_dismiss_admin_notice', 1);
                update_option( 'ins_dismiss_post_woo_notice', 1); 
            }
            update_option( 'ins_promo__schudle_option', $this->responsed);
            
        } 
    }

    // Define the custom interval
    public function ins_custom_cron_interval($schedules) {
        $schedules['ins_every_day'] = array(
            'interval' => 86400, // Every 24 hours
            // 'interval' => 5, // Every 24 hours
            'display' => __('Every 24 hours')
        );
        return $schedules;
    }

    public function ins_promo__schudle_callback() {  

        $this->ins_get_api_response();

    }
 

    /**
     * Black Friday Deals 2023
     */
    
    public function tf_black_friday_2023_admin_notice(){ 
        
        $image_url = isset($this->ins_promo_option['dasboard_url']) ? esc_url($this->ins_promo_option['dasboard_url']) : '';
        $deal_link = isset($this->ins_promo_option['promo_url']) ? esc_url($this->ins_promo_option['promo_url']) : ''; 

        $tf_dismiss_admin_notice = get_option( 'tf_dismiss_admin_notice' );
        $get_current_screen = get_current_screen();  
        if(($tf_dismiss_admin_notice == 1  || time() >  $tf_dismiss_admin_notice ) && $get_current_screen->base == 'dashboard'   ){ 
            // if very fist time then set the dismiss for our other plugins
            update_option( 'tf_promo_notice_exists', 'ins' );
            ?>
            <style> 
                .tf_black_friday_20222_admin_notice a:focus {
                    box-shadow: none;
                } 
                .tf_black_friday_20222_admin_notice {
                    padding: 7px;
                    position: relative;
                    z-index: 10;
                    max-width: 825px;
                } 
                .tf_black_friday_20222_admin_notice button:before {
                    color: #fff !important;
                }
                .tf_black_friday_20222_admin_notice button:hover::before {
                    color: #d63638 !important;
                }
            </style>
            <div class="notice notice-success tf_black_friday_20222_admin_notice"> 
                <a href="<?php echo esc_attr( $deal_link ); ?>" style="display: block; line-height: 0;" target="_blank" >
                    <img  style="width: 100%;" src="<?php echo esc_attr($image_url) ?>" alt="">
                </a> 
                <?php if( isset($this->ins_promo_option['dasboard_dismiss']) && $this->ins_promo_option['dasboard_dismiss'] == true): ?>
                <button type="button" class="notice-dismiss tf_black_friday_notice_dismiss"><span class="screen-reader-text"><?php echo __('Dismiss this notice.', 'ultimate-addons-cf7' ) ?></span></button>
                <?php  endif; ?>
            </div>
            <script>
                jQuery(document).ready(function($) {
                    $(document).on('click', '.tf_black_friday_notice_dismiss', function( event ) {
                        jQuery('.tf_black_friday_20222_admin_notice').css('display', 'none')
                        data = {
                            action : 'tf_black_friday_notice_dismiss_callback',
                        };

                        $.ajax({
                            url: ajaxurl,
                            type: 'post',
                            data: data,
                            success: function (data) { ;
                            },
                            error: function (data) { 
                            }
                        });
                    });
                });
            </script>
        
        <?php 
        }
        
    } 


    public function tf_black_friday_notice_dismiss_callback() {  

        $ins_promo_option = get_option( 'ins_promo__schudle_option' );
        $restart = isset($ins_promo_option['dasboard_restart']) && $ins_promo_option['dasboard_restart'] != false ? $ins_promo_option['dasboard_restart'] : false; 
        if($restart == false){
            update_option( 'tf_dismiss_admin_notice', strtotime($ins_promo_option['end_date']) ); 
        }else{
            update_option( 'tf_dismiss_admin_notice', time() + (86400 * $restart) );  
        } 
		wp_die();
	}


    /**
     * Black Friday Deals 2023 woo product
     */ 

    public function tf_black_friday_2023_woo_product() { 
        $ins_dismiss_post_woo_notice = get_option( 'ins_dismiss_post_woo_notice' ); 
        if($ins_dismiss_post_woo_notice == 1  || time() >  $ins_dismiss_post_woo_notice ): 
            add_meta_box( 'tf_black_friday_annous', __( ' ', 'instantio' ), array($this, 'tf_black_friday_2023_callback_woo_product'), 'product', 'side', 'high' );
        endif;
   
    }
    public function tf_black_friday_2023_callback_woo_product() {
        $image_url = isset($this->ins_promo_option['side_url']) ? esc_url($this->ins_promo_option['side_url']) : '';
        $deal_link = isset($this->ins_promo_option['promo_url']) ? esc_url($this->ins_promo_option['promo_url']) : ''; 
      ?>
        <style>
            #tf_black_friday_annous{
                border: 0px solid;
                box-shadow: none;
                background: transparent;
            }
            .back_friday_2023_preview a:focus {
                box-shadow: none;
            }

            .back_friday_2023_preview a {
                display: inline-block;
            }

            #tf_black_friday_annous .inside {
                padding: 0;
                margin-top: 0;
            }

            #tf_black_friday_annous .postbox-header {
                display: none;
                visibility: hidden;
            }
        </style> 
     
        <div class="back_friday_2023_preview ins-bf-preview" style="text-align: center; overflow: hidden;">
            <a href="<?php echo esc_attr($deal_link); ?>" target="_blank" >
                <img  style="width: 100%;" src="<?php echo esc_attr($image_url); ?>" alt="">
            </a>  
            <?php if( isset($this->ins_promo_option['side_dismiss']) && $this->ins_promo_option['side_dismiss'] == true): ?>
                <button type="button" class="notice-dismiss ins_friday_notice_dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
            <?php  endif; ?>
          
        </div>
        <script> 
            jQuery(document).ready(function($) {
                $(document).on('click', '.ins_friday_notice_dismiss', function( event ) { 
                    jQuery('.ins-bf-preview').css('display', 'none');
                    data = {
                        action : 'ins_black_friday_notice_ins_dismiss_callback', 
                    };

                    $.ajax({
                        url: ajaxurl,
                        type: 'post',
                        data: data,
                        success: function (data) { ;
                        },
                        error: function (data) { 
                        }
                    });
                });
            });
        </script>
        <?php 
    }

    public function metabox_order( $order ) {
		return array(
			'side' => join( 
				",", 
				array(       // vvv  Arrange here as you desire
					'submitdiv',
					'tf_black_friday_annous',
				)
			),
		);
	}

    public  function ins_black_friday_notice_ins_dismiss_callback() {   
        $ins_promo_option = get_option( 'ins_promo__schudle_option' );
        $start_date = isset($ins_promo_option['start_date']) ? strtotime($ins_promo_option['start_date']) : time();
        $restart = isset($ins_promo_option['side_restart']) && $ins_promo_option['side_restart'] != false ? $ins_promo_option['side_restart'] : 5;
        update_option( 'ins_dismiss_post_woo_notice', time() + (86400 * $restart) );  
        wp_die();
    }

    // Deactivation Hook
    public function ins_promo_notice_deactivation_hook() {
        wp_clear_scheduled_hook('ins_promo__schudle'); 

        delete_option('ins_promo__schudle_option');
        delete_option('ins_dismiss_post_woo_notice');
        delete_option('tf_promo_notice_exists');
    }
 
}

new INS_PROMO_NOTICE();
