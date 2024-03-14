<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Profilegrid_Woocommerce
 * @subpackage Profilegrid_Woocommerce/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Profilegrid_Woocommerce
 * @subpackage Profilegrid_Woocommerce/public
 * @author     Your Name <email@example.com>
 */
class Profilegrid_Woocommerce_Public {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $profilegrid_woocommerce    The ID of this plugin.
     */
    private $profilegrid_woocommerce;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $profilegrid_woocommerce       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $profilegrid_woocommerce, $version ) {

            $this->profilegrid_woocommerce = $profilegrid_woocommerce;
            $this->version = $version;

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {

            /**
             * This function is provided for demonstration purposes only.
             *
             * An instance of this class should be passed to the run() function
             * defined in Profilegrid_Woocommerce_Loader as all of the hooks are defined
             * in that particular class.
             *
             * The Profilegrid_Woocommerce_Loader will then create the relationship
             * between the defined hooks and the functions defined in this
             * class.
             */

            wp_enqueue_style( $this->profilegrid_woocommerce, plugin_dir_url( __FILE__ ) . 'css/profilegrid-woocommerce-public.css', array(), $this->version, 'all' );

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {

            /**
             * This function is provided for demonstration purposes only.
             *
             * An instance of this class should be passed to the run() function
             * defined in Profilegrid_Woocommerce_Loader as all of the hooks are defined
             * in that particular class.
             *
             * The Profilegrid_Woocommerce_Loader will then create the relationship
             * between the defined hooks and the functions defined in this
             * class.
             */
	    wp_enqueue_script('jquery');
	    //wp_enqueue_script('jquery-ui-tabs');
            wp_enqueue_script( $this->profilegrid_woocommerce, plugin_dir_url( __FILE__ ) . 'js/profilegrid-woocommerce-public.js', array( 'jquery' ), $this->version, true );

    }



    public function pg_purchases_tab($id,$newtab,$uid,$gid)
    {
        if($id=='pg-woocommerce_purchases' && isset($newtab) && $newtab['status']=='1'):
           $this->enqueue_scripts();
           $this->enqueue_styles();
           $dbhandler = new PM_DBhandler;
           $pmrequests = new PM_request;
           $options = maybe_unserialize($dbhandler->get_value('GROUPS','group_options',$gid,'id'));
           $option_tab = (isset($options['woocommerce_purchases_tab']))?$options['woocommerce_purchases_tab']:$dbhandler->get_global_option_value('pm_woocommerce_purchases_tab','0');
           if($dbhandler->get_global_option_value('pm_enable_woocommerce','1')==1 && $option_tab==1)
           {
               echo '<li class="pm-profile-tab pm-pad10"><a class="pm-dbfl" href="#pg-woocommerce_purchases">'. esc_html($newtab['title']).'</a></li>';
           }
        endif;
    }

    public function pg_cart_tab($id,$newtab,$uid,$gid)
    {
        if($id=='pg-woocommerce_cart' && isset($newtab) && $newtab['status']=='1'):
            $this->enqueue_scripts();
            $this->enqueue_styles();
            $dbhandler = new PM_DBhandler;
            
            $current_uid = get_current_user_id();
            $options = maybe_unserialize($dbhandler->get_value('GROUPS','group_options',$gid,'id'));
            $option_tab = (isset($options['woocommerce_cart_tab']))?$options['woocommerce_cart_tab']:$dbhandler->get_global_option_value('pm_enable_cart_tab','0');
            if($dbhandler->get_global_option_value('pm_enable_woocommerce','1')==1 && $option_tab==1 && $current_uid == $uid)
            {
                 echo '<li class="pm-profile-tab pm-pad10"><a class="pm-dbfl" href="#pg-woocommerce_cart">'. esc_html($newtab['title']).'</a></li>';
            }
        endif;
    }
    
    public function pg_show_cart_tab_content($id,$newtab,$uid,$gid,$primary_gid)
    {
        if($id=='pg-woocommerce_cart' && isset($newtab) && $newtab['status']=='1'):
            $this->enqueue_scripts();
            $this->enqueue_styles();
           $dbhandler = new PM_DBhandler;

           $current_uid = get_current_user_id();
           $options = maybe_unserialize($dbhandler->get_value('GROUPS','group_options',$primary_gid,'id'));
            $option_tab = (isset($options['woocommerce_cart_tab']) && !empty($options['woocommerce_cart_tab']))?$options['woocommerce_cart_tab']:$dbhandler->get_global_option_value('pm_enable_cart_tab','0');
            if($dbhandler->get_global_option_value('pm_enable_woocommerce','1')==1 && $option_tab==1 && $current_uid == $uid)
            {
              echo '<div id="pg-woocommerce_cart" class="pm-dbfl pg-profile-tab-content">';
              echo do_shortcode('[woocommerce_cart]');
              echo '</div>';
           }
        endif;
    }
    
    public function pg_show_purchases_tab_content($id,$newtab,$uid,$gid,$primary_gid)
    {
        if($id=='pg-woocommerce_purchases' && isset($newtab) && $newtab['status']=='1'):
            $this->enqueue_scripts();
             $this->enqueue_styles();
            $dbhandler = new PM_DBhandler;
            $pmrequests = new PM_request;
             $useremail = $pmrequests->profile_magic_get_user_field_value($uid,'user_email');
            $options = maybe_unserialize($dbhandler->get_value('GROUPS','group_options',$primary_gid,'id'));
            $option_tab = (isset($options['woocommerce_purchases_tab']))?$options['woocommerce_purchases_tab']:$dbhandler->get_global_option_value('pm_woocommerce_purchases_tab','0');
            if($dbhandler->get_global_option_value('pm_enable_woocommerce','1')==1 && $option_tab==1)
            {
            
                //echo $options['woocommerce_max_product'];die;
                $max_product = (!empty($options) && isset($options['woocommerce_max_product']))?$options['woocommerce_max_product']:$dbhandler->get_global_option_value('pm_woocommerce_max_product','10');

                if($max_product!='')
                {
                  $numberofposts = intval($max_product); 
                }
                else
                {
                    $numberofposts = 10;
                }
                $productids = $this->pg_get_recently_purchased_products($numberofposts,$uid);
                //print_r($productids);
                if(empty($productids))
                {
                    $productids[] = '0';
                }
                echo '<div id="pg-woocommerce_purchases" class="pm-dbfl">';
                $args = array( 'post_type' => 'product', 'posts_per_page' => -1, 'post__in' =>$productids );
                $loop = new WP_Query( $args );

                if ( $loop->found_posts ) {

                        include 'partials/pg-purchases-content.php';

                } else {

                ?>

                <div class="pg-alert-warning pg-alert-info"><span><?php echo ( $uid == get_current_user_id() ) ? __('You did not purchase any product yet.','profilegrid-woocommerce') : sprintf(__('%s did not purchase any product yet.','profilegrid-woocommerce'),$pmrequests->pm_get_display_name($uid)); ?></span></div>

                <?php

                }

                echo '</div>';
            }
        
        endif;
    }


    public function pg_product_review_tab($id,$newtab,$uid,$gid)
    {
        if($id=='pg-woocommerce_reviews' && isset($newtab) && $newtab['status']=='1'):
            $this->enqueue_scripts();
            $this->enqueue_styles();
            $dbhandler = new PM_DBhandler;
            $pmrequests = new PM_request;
            $options = maybe_unserialize($dbhandler->get_value('GROUPS','group_options',$gid,'id'));
            $option_tab = (isset($options['woocommerce_reviews_tab']))?$options['woocommerce_reviews_tab']:$dbhandler->get_global_option_value('pm_woocommerce_reviews_tab','0');
            if($dbhandler->get_global_option_value('pm_enable_woocommerce','1')==1 && $option_tab==1)
            {
                echo '<li class="pm-profile-tab pm-pad10"><a class="pm-dbfl" href="#pg-woocommerce_reviews">'. esc_html($newtab['title']).'</a></li>';
            }
        endif;
    }

    public function pg_show_product_review_tab_content($id,$newtab,$uid,$gid,$primary_gid)
    {
        if($id=='pg-woocommerce_reviews' && isset($newtab) && $newtab['status']=='1'):
            $this->enqueue_scripts();
             $this->enqueue_styles();
            $dbhandler = new PM_DBhandler;
            $pmrequests = new PM_request;

            $options = maybe_unserialize($dbhandler->get_value('GROUPS','group_options',$primary_gid,'id'));
            $option_tab = (isset($options['woocommerce_reviews_tab']))?$options['woocommerce_reviews_tab']:$dbhandler->get_global_option_value('pm_woocommerce_reviews_tab','0');
            if($dbhandler->get_global_option_value('pm_enable_woocommerce','1')==1 && $option_tab==1)
            {
                echo '<div id="pg-woocommerce_reviews" class="pm-dbfl">';
                $args = array ('post_type' => 'product', 'user_id' => $uid,'status'=>'approve');
                $comments = get_comments( $args );
                if ( $comments ) 
                {
                    include 'partials/pg-reviews-content.php';
                } 
                else 
                {
                    ?>
                    <div class="pg-alert-warning pg-alert-info"><span><?php echo ($uid == get_current_user_id() ) ? __('You did not review any products yet.','profilegrid-woocommerce') : sprintf(__('%s did not review any product yet.','profilegrid-woocommerce'),$pmrequests->pm_get_display_name($uid));?></span></div>
                    <?php
                }
                echo '</div>';
            }
        endif;
    }

    public function pg_my_orders_tab($uid,$gid)
    {

        $this->enqueue_scripts();
         $this->enqueue_styles();
        $dbhandler = new PM_DBhandler;
        $pmrequests = new PM_request;
        $options = maybe_unserialize($dbhandler->get_value('GROUPS','group_options',$gid,'id'));
        $option_tab = (isset($options['woocommerce_orders_in_account']))?$options['woocommerce_orders_in_account']:$dbhandler->get_global_option_value('pm_woocommerce_orders_in_account','0');
        if($dbhandler->get_global_option_value('pm_enable_woocommerce','1')==1 && $option_tab==1)
        {        
            echo '<li class="pm-dbfl pm-border-bt pm-pad10"><a class="pm-dbfl" href="#pg-my-orders">'. __('My Orders','profilegrid-woocommerce').'</a></li>';
        }

    }
    public function pg_my_orders_tab_content($uid,$gid)
    {
        $this->enqueue_scripts();
         $this->enqueue_styles();
        $dbhandler = new PM_DBhandler;
        $pmrequests = new PM_request;
        $path =  plugin_dir_url(__FILE__);
        $options = maybe_unserialize($dbhandler->get_value('GROUPS','group_options',$gid,'id'));
        $option_tab = (isset($options['woocommerce_orders_in_account']))?$options['woocommerce_orders_in_account']:$dbhandler->get_global_option_value('pm_woocommerce_orders_in_account','0');
        if($dbhandler->get_global_option_value('pm_enable_woocommerce','1')==1 && $option_tab==1)
        { 
            echo '<div id="pg-my-orders" class="pm-blog-desc-wrap pm-difl pm-section-content pg-my-orders">';
            include 'partials/pg-my-orders.php';
            echo '</div>';
        }
    }

    public function pg_my_billing_address_tab($uid,$gid)
    {
        $this->enqueue_scripts();
         $this->enqueue_styles();
        $dbhandler = new PM_DBhandler;
        $pmrequests = new PM_request;
        $options = maybe_unserialize($dbhandler->get_value('GROUPS','group_options',$gid,'id'));
        $option_tab = (isset($options['woocommerce_billing_address_in_account']))?$options['woocommerce_billing_address_in_account']:$dbhandler->get_global_option_value('pm_woocommerce_billing_address_in_account','0');
        if($dbhandler->get_global_option_value('pm_enable_woocommerce','1')==1 && $option_tab==1)
        { 
        
            echo '<li class="pm-dbfl pm-border-bt pm-pad10"><a class="pm-dbfl" href="#pg-billing-address">'. __('Billing Address','profilegrid-woocommerce').'</a></li>';
        }

    }
    public function pg_my_billing_address_tab_content($uid,$gid)
    {
        $this->enqueue_scripts();
         $this->enqueue_styles();
        $dbhandler = new PM_DBhandler;
        $pmrequests = new PM_request;
        $options = maybe_unserialize($dbhandler->get_value('GROUPS','group_options',$gid,'id'));
        $option_tab = (isset($options['woocommerce_billing_address_in_account']))?$options['woocommerce_billing_address_in_account']:$dbhandler->get_global_option_value('pm_woocommerce_billing_address_in_account','0');
        if($dbhandler->get_global_option_value('pm_enable_woocommerce','1')==1 && $option_tab==1)
        {
            echo '<div id="pg-billing-address" class="pm-blog-desc-wrap pm-difl pm-section-content pg-billing-address">';
            include 'partials/pg-billing-address-content.php';
            echo '</div>';
        }
    }

    public function pg_my_shipping_address_tab($uid,$gid)
    {
        $this->enqueue_scripts();
         $this->enqueue_styles();
        $dbhandler = new PM_DBhandler;
        $pmrequests = new PM_request;
        $options = maybe_unserialize($dbhandler->get_value('GROUPS','group_options',$gid,'id'));
        $option_tab = (isset($options['woocommerce_shipping_address_in_account']))?$options['woocommerce_shipping_address_in_account']:$dbhandler->get_global_option_value('pm_woocommerce_shipping_address_in_account','0');
        if($dbhandler->get_global_option_value('pm_enable_woocommerce','1')==1 && $option_tab==1)
        {
            echo '<li class="pm-dbfl pm-border-bt pm-pad10"><a class="pm-dbfl" href="#pg-shipping-address">'. __('Shipping Address','profilegrid-woocommerce').'</a></li>';
        }
    }
    public function pg_my_shipping_address_tab_content($uid,$gid)
    {
        $this->enqueue_scripts();
         $this->enqueue_styles();
        $dbhandler = new PM_DBhandler;
        $pmrequests = new PM_request;
        $options = maybe_unserialize($dbhandler->get_value('GROUPS','group_options',$gid,'id'));
        $option_tab = (isset($options['woocommerce_shipping_address_in_account']))?$options['woocommerce_shipping_address_in_account']:$dbhandler->get_global_option_value('pm_woocommerce_shipping_address_in_account','0');
        if($dbhandler->get_global_option_value('pm_enable_woocommerce','1')==1 && $option_tab==1)
        {
            echo '<div id="pg-shipping-address" class="pm-blog-desc-wrap pm-difl pm-section-content pg-shipping-address">';
            include 'partials/pg-shipping-address-content.php';
            echo '</div>';
        }
    }
        
    public function pg_woocommerce_get_order()
    {
        $this->enqueue_scripts();
         $this->enqueue_styles();
        $path =  plugin_dir_url(__FILE__);
        $dbhandler = new PM_DBhandler;
        $pmrequests = new PM_request;
        $order_id = filter_input(INPUT_POST,'order_id');
        if ( !isset($order_id ) || !is_user_logged_in() ) die(0);
        ob_start();
        $order      =  wc_get_order( $order_id );
        $order_user_id = $order->get_customer_id();
        echo $order_id;
        // Get the current user's ID
        $current_user_id = get_current_user_id();
        // Check if the current user's ID matches the order's user ID
        if ($order_user_id !== $current_user_id) {
            die(0);
        }
        ?>
      
                    
            <div class="pg-woocommerce-order-header">
                <div class="pg-woocommerce-customer">
                   <?php echo get_avatar( get_current_user_id(), 34 ); ?>
                    <span><?php echo $pmrequests->pm_get_display_name(get_current_user_id());?></span>
                </div>

               <div class="pg-woocommerce-orderid">
                   <?php printf(__('Order# %s','profilegrid-woocommerce'), $order_id ); ?>
               </div>
            </div>

            <div class="pg-woocommerce-order-content">
                <?php wc_print_notices(); ?>
                <p class="pg-order-info"><?php printf( __( 'Order #<mark class="pg-order-number">%s</mark> was placed on <mark class="pg-order-date">%s</mark> and is currently <mark class="pg-order-status">%s</mark>.', 'profilegrid-woocommerce' ), $order->get_order_number(), date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) ), wc_get_order_status_name( $order->get_status() ) ); ?></p>
                <?php if ( $notes = $order->get_customer_order_notes() ) : ?>

                    <h2><?php _e( 'Order Updates', 'woocommerce' ); ?></h2>
                    <ol class="pg-commentlist-notes">
                            <?php foreach ( $notes as $note ) : ?>
                            <li class="pg-comment-note">
                                    <div class="pg-comment-container">
                                            <div class="pg-comment-text">
                                                    <p class="meta"><?php echo date_i18n( __( 'l jS \o\f F Y, h:ia', 'woocommerce' ), strtotime( $note->comment_date ) ); ?></p>
                                                    <div class="description">
                                                            <?php echo wpautop( wptexturize( $note->comment_content ) ); ?>
                                                    </div>
                                                    <div class="clear"></div>
                                            </div>
                                            <div class="clear"></div>
                                    </div>
                            </li>
                            <?php endforeach; ?>
                    </ol>
                <?php
                endif;
                do_action( 'woocommerce_view_order', $order_id );
                ?>
            </div>

        <?php
        $output = ob_get_contents();
        ob_end_clean();
        die( do_shortcode( $output ) );
    }
    
    public function pg_edit_woocommerce_address( $address )
    {
        $this->enqueue_scripts();
         $this->enqueue_styles();
        global $woocommerce;
        $current_user = wp_get_current_user();
        $load_address = $address;
        $load_address = sanitize_key( $load_address );
        $address = WC()->countries->get_address_fields( get_user_meta( get_current_user_id(), $load_address . '_country', true ), $load_address . '_' );
        // Enqueue scripts
        wp_enqueue_script( 'wc-country-select' );
        wp_enqueue_script( 'wc-address-i18n' );
        // Prepare values
        foreach ( $address as $key => $field ) 
        {
            $value = get_user_meta( get_current_user_id(), $key, true );
            if ( ! $value ) 
            {
                switch( $key ) {
                        case 'billing_email' :
                        case 'shipping_email' :
                                $value = $current_user->user_email;
                        break;
                        case 'billing_country' :
                        case 'shipping_country' :
                                $value = WC()->countries->get_base_country();
                        break;
                        case 'billing_state' :
                        case 'shipping_state' :
                                $value = WC()->countries->get_base_state();
                        break;
                }
            }
            $address[ $key ]['value'] = apply_filters( 'woocommerce_my_account_edit_address_field_value', $value, $key, $load_address );
            $arr_field[ $key ] = array('metakey' => $key);
            //apply_filters('um_account_secure_fields', $arr_field, $load_address );
        }
        ?>
        <h3><?php echo apply_filters( 'woocommerce_my_account_edit_address_title', $load_address ); ?></h3>
        <?php do_action( "woocommerce_before_edit_address_form_{$load_address}" ); ?>
        <?php foreach ( $address as $key => $field ) : ?>
            <?php
                    $field['return'] = true;
                    $postkey = filter_input(INPUT_POST,$key);
                    $field = woocommerce_form_field( $key, $field, ! empty($postkey) ? wc_clean($postkey) : $field['value'] );
                    echo $field;
            ?>
        <?php endforeach; ?>
        <?php do_action( "woocommerce_after_edit_address_form_{$load_address}" ); 

    }
    
    public function profile_magic_update_billing_address($post,$uid)
    {
        $this->enqueue_scripts();
         $this->enqueue_styles();
        $pmrequests = new PM_request;
        if(isset($post['pg_saved_billing_address']))
        {
            foreach($post as $key => $value) 
            {
                if(preg_match('/^billing_/', $key))
                {
                    $value = sanitize_text_field($value );
                    update_user_meta($uid,$key,$value);
                }
            }
            $redirect_url = $pmrequests->profile_magic_get_frontend_url('pm_user_profile_page',site_url('/wp-login.php'));
            wp_redirect( $redirect_url );
            exit;
        }
    }
    
    public function profile_magic_update_shipping_address($post,$uid)
    {
        $this->enqueue_scripts();
         $this->enqueue_styles();
        $pmrequests = new PM_request;
        if(isset($post['pg_saved_shipping_address']))
        {
            foreach($post as $key => $value) 
            {
                if(preg_match('/^shipping/', $key))
                {
                    $value = sanitize_text_field($value );
                    update_user_meta($uid,$key,$value);
                }
            }
            $redirect_url = $pmrequests->profile_magic_get_frontend_url('pm_user_profile_page',site_url('/wp-login.php'));
            wp_redirect( $redirect_url );
            exit;
        }
    }
    
    public function pg_user__woocommerce_order_count($user_id) 
    {
        $this->enqueue_scripts();
         $this->enqueue_styles();
        $output = '';
        global $wpdb;
        $count = $wpdb->get_var( "SELECT COUNT(*)
                FROM $wpdb->posts as posts 
                LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id 
                WHERE   meta.meta_key       = '_customer_user' 
                AND     posts.post_type     IN ('" . implode( "','", wc_get_order_types( 'order-count' ) ) . "') 
                AND     posts.post_status   IN ('" . implode( "','", array('wc-completed') )  . "') 
                AND     meta_value          = $user_id" );

        $count = absint($count);
        if ( $count == 1 ) {
                $output = sprintf(__('%s purchase','profilegrid-woocommerce'), ($count) );
        } else {
                $output = sprintf(__('%s purchases','profilegrid-woocommerce'), ($count) );
        }

        return $output;
    }
    
    public function pg_user__woocommerce_total_spent($uid) 
    {
        $output = '';
        $output = get_woocommerce_currency_symbol() . number_format( wc_get_customer_total_spent($uid ) );
        return $output;
    }
    
    public function profile_magic_show_total_spent_on_profile_html($uid)
    {
        $this->enqueue_scripts();
         $this->enqueue_styles();
        ?>
        <div class="pm-user-woocommerce-field pm-dbfl pm-clip">
            <?php echo $this->pg_user__woocommerce_order_count($uid). ' . '.$this->pg_user__woocommerce_total_spent($uid); ?>
        </div>
        <?php
    }
    
    public function profile_magic_show_total_spend_and_total_orders($uid)
    {
        $this->enqueue_scripts();
         $this->enqueue_styles();
        if($this->profile_magic_check_woocommerce_show_total_spent($uid))
        {
            $this->profile_magic_show_total_spent_on_profile_html($uid);
        }
    }
    
    public function profile_magic_check_woocommerce_show_total_spent($profile_id)
    {
        $this->enqueue_scripts();
         $this->enqueue_styles();
        $dbhandler = new PM_DBhandler;
        $pmfriends = new PM_Friends_Functions;
        $pmrequests = new PM_request;
        $current_user_id = get_current_user_id();
        $profile_user_groups = $pmrequests->profile_magic_get_user_field_value($profile_id,'pm_group');
        $profile_user_group = $pmrequests->pg_get_primary_group_id($profile_user_groups);
        $row = $dbhandler->get_row('GROUPS',$profile_user_group);
       // if($row->is_group_leader!=0)$group_leader = $pmrequests->pg_get_group_leaders($profile_user_group);
        $is_group_leader = $pmrequests->pg_check_in_single_group_is_user_group_leader($current_user_id, $profile_user_group);
        if (isset($row->group_options))  $group_options = maybe_unserialize($row->group_options);
        $access = false;
        $option_tab = (isset($group_options['woocommerce_show_total_spent']))?$group_options['woocommerce_show_total_spent']:$dbhandler->get_global_option_value('pm_woocommerce_show_total_spent','0');
        if($dbhandler->get_global_option_value('pm_enable_woocommerce','1')==1 && $option_tab==1)
        {    
            $access_level = (!empty($group_options) && isset($group_options['woocommerce_show_total_spent_permission']))?$group_options['woocommerce_show_total_spent_permission']:$dbhandler->get_global_option_value('pm_woocommerce_show_total_spent_permission','1');

            $current_user_groups = $pmrequests->profile_magic_get_user_field_value($current_user_id,'pm_group');
            $current_user_group = $pmrequests->pg_get_primary_group_id($current_user_groups);
            $is_my_friend = $pmfriends->profile_magic_is_my_friends($profile_id,$current_user_id);
            if(is_array($current_user_groups) && is_array($profile_user_groups))
            {
                $is_group_member = array_intersect($profile_user_groups, $current_user_groups);
            }
            
            switch($access_level)
            {
                case '1':
                    $access = true;
                    break;
                case '2':
                    
                    if(is_user_logged_in() && $is_group_leader==true) 
                    {
                        $access = true;
                    }
                    break;
                case '3':
                    if(is_user_logged_in() && isset($is_group_member) && !empty($is_group_member))
                    {
                        $access = true;
                    }
                    break;
                case '4':
                    if ((is_user_logged_in() && isset($is_my_friend) && !empty($is_my_friend)) || $current_user_id == $profile_id ) 
                    {
                        $access = true;
                    }
                    break;
                case '5':
                    if($current_user_id == $profile_id)
                    {
                        $access = true;
                    }
                    break;
            }
        }
        
        return $access;
    }
    
    public function pg_get_recently_purchased_products($num_products=4,$uid='')
    {
	$this->enqueue_scripts();
         $this->enqueue_styles();
	$num_products = (int)$num_products;
        if($uid==''){$uid = get_current_user_id();}
	if($num_products=='0'){$num_products=4;}
        $args = array(
            'status' => array('wc-completed'),
             'orderby' => 'date',
            'order' => 'DESC',
            'limit' => -1,
            'customer' => $uid,
        );
	$orders = wc_get_orders($args);
	$product_ids = array();
	if(!empty($orders)){
		
		foreach( $orders as $order ) {				
			$order_id = $order->get_id();
			$order = new WC_Order($order_id);
			$products = $order->get_items();
			
			foreach($products as $product){
                                $product_id = $product->get_product_id();
                                $product_ids[] = $product_id;
			}
		}

	}
	if ( sizeof($product_ids ) == 0  || $product_ids =='') { return false; }
	
	$product_ids = array_unique($product_ids);
	$product_ids = array_slice($product_ids, 0, $num_products);
	
	return $product_ids;
	
    }
    
    public function profile_magic_profile_tab_link_fun($id,$newtab,$uid,$gid,$primary_gid)
    {
        if(isset($newtab) && $newtab['status']=='1'):
            switch($id)
            {
                case 'pg-woocommerce_purchases':
                    $this->pg_purchases_tab($id,$newtab,$uid,$primary_gid);
                    break;
                case 'pg-woocommerce_cart':
                    $this->pg_cart_tab($id,$newtab,$uid,$primary_gid);
                    break;
                case 'pg-woocommerce_reviews':
                    $this->pg_product_review_tab($id,$newtab,$uid,$primary_gid);
                    break;
            }
        endif;
    }
    
    public function profile_magic_profile_tab_extension_content_fun($id,$newtab,$uid,$gid,$primary_gid)
    {
        if(isset($newtab) && $newtab['status']=='1'):
            switch($id)
            {
                case 'pg-woocommerce_purchases':
                    $this->pg_show_purchases_tab_content($id,$newtab,$uid,$gid,$primary_gid);
                    break;
                case 'pg-woocommerce_cart':
                    $this->pg_show_cart_tab_content($id,$newtab,$uid,$gid,$primary_gid);
                    break;
                case 'pg-woocommerce_reviews':
                    $this->pg_show_product_review_tab_content($id,$newtab,$uid,$gid,$primary_gid);
                    break;
            }
        endif;
    }
    
}
