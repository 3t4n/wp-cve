<?php

class WC_MailChimp_Newsletter_Discount{

  protected $options = '';

  /**
  * Construct for the class
  *
  * @param empty
  * @return mixed
  *
  */
	public function __construct() {

    $options = get_option('wcmnd_options');

    //Add setting link for the admin settings
    add_filter( "plugin_action_links_".WCMND_BASE, array( $this, 'wcmnd_settings_link' ) );

    $this->options = get_option( 'wcmnd_options' );


    if( $this->option('enabled') == 'yes' ) {
      //Add css and js files for the popup
      add_action( 'wp_enqueue_scripts',  array( $this, 'wcmnd_enque_scripts' ) );

      //Add shortcode support on the widgets
      add_filter( 'widget_text', 'do_shortcode' );

      //Add shortcode for mailchimp discount.
      add_shortcode( 'wc_mailchimp_subscribe_discount', array( $this, 'wc_mailchimp_subscribe_discount_shortcode' ) );

      add_action( 'wp_ajax_woocommerce_newsletter_subscribe', array($this, 'woocommerce_newsletter_subscribe') );
      add_action( 'wp_ajax_nopriv_woocommerce_newsletter_subscribe', array($this, 'woocommerce_newsletter_subscribe') );

      add_action( 'admin_notices', array( $this, 'show_extra_field_notices' ) );
    }

    //action for getting mailchimp lists in admin
    add_action( 'wp_ajax_get_mailchimp_lists', array($this, 'wcmnd_get_mailchimp_lists') );

    add_action( 'wp_loaded', array( $this, 'wcmnd_install_analytics') );
    add_action('init' , array( $this,'wc_mailchimp_subscribe_discount_guttenberg_blocks'));


	}


  /**
  * @uses Shows admin notice for extra field plugin
  *
  * @param empty
  * @return html
  *
  */
  public function show_extra_field_notices() {
    $active_plugins = get_option('active_plugins');
    if( is_array($active_plugins) ) {
      if( !in_array( 'mailchimp-newsletter-discount-extra-fields/mailchimp-newsletter-discount-extra-fields.php', $active_plugins ) ) {
        $class = 'notice notice-warning is-dismissible';
        $message = __( 'Glad to know that you are already using our '.WCMND_PLUGIN_NAME.'. But do you want to show your own custom fields in the plugin subscription form and sync them to MailChimp as well?. Then please use <a href="https://zetamatic.com/shop/?utm_src='.WCMND_PLUGIN_NAME.'" target="_blank">Mailchimp NewsLetter Discount - Extra Fields</a> for custom fields.', 'wc_mailchimp_newsletter_discounts' );
          printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), $message  );
      }
    }
  }


  /**
  * @uses Adds a new link to the plugin settings
  *
  * @param $links array
  * @return $links outputs array of links
  *
  */
  public function wcmnd_settings_link($links) {
    $new_links = array();
    $pro_link = 'https://zetamatic.com/downloads/woocommerce-mailchimp-newsletter-discount/?utm_src=woo-mailchimp-newsletter-discount/';
    $settings_link = esc_url( add_query_arg( array(
                            'page' => 'mailchimp-subscribe-discount',
                            ), admin_url( 'admin.php' ) ) );
    $new_links[ 'settings' ] = sprintf( '<a href="%1$s" title="%2$s">%2$s</a>', $settings_link, esc_attr__( 'Settings', 'wsac' ) );
    $new_links[ 'go-pro' ] = sprintf( '<a target="_blank" style="color: #45b450; font-weight: bold;" href="%1$s" title="%2$s">%2$s</a>', $pro_link, esc_attr__( 'Get Pro Version', 'wsac' ) );

    return array_merge( $links, $new_links );
  }

  /**
  * @uses Saves the option fields
  *
  * @param array
  * @return mixed
  *
  */
  public function option( $option ) {
    if( isset( $this->options[$option] ) && $this->options[$option] != '' )
      return $this->options[$option];
    else
    return '';
  }


  /**
  * @uses Adds necessary css and js for our plugin
  *
  * @param empty
  * @return mixed
  *
  */
  public function wcmnd_enque_scripts() {

    $mailchimp_list_id = $this->option('mailchimp_list_id');
    $ajax_nonce = wp_create_nonce( "woocommerce_mailchimp_discounts_nonce" );

    if( $this->option( 'double_optin') == 'yes'
      && isset( $_POST['type'] )
      && $_POST['type'] == 'subscribe'
      && $_POST['data']['list_id'] == $mailchimp_list_id
      && isset($_POST['data']['email']) )
      {
        $email = sanitize_email($_POST['data']['email']);
        $this->wc_newsletter_send_coupons( $email );
      }

    $success_color = !empty($this->option('success_text_color')) ? $this->option('success_text_color') : '#019031';

    $error_color = !empty($this->option('error_text_color')) ? $this->option('error_text_color') : '#FF3E4D';
    $button_color = !empty($this->option('subscribe_button_color')) ? $this->option('subscribe_button_color') : '#1f5478';
    $button_hover_color = !empty($this->option('subscribe_button_hover_color')) ? $this->option('subscribe_button_hover_color') : '#0b2e46';

    $button_text_color = !empty($this->option('subscribe_button_text_color')) ? $this->option('subscribe_button_text_color') : '#FFF';

    $button_text_hover_color = !empty($this->option('subscribe_button_text_hover_color')) ? $this->option('subscribe_button_text_hover_color') : '#FFF';

    //Button Colors and Text Color
    $inline_css = '.wc-mailchimp-subscribe-form  .newsletter-discount-submit-button{ color: '. $button_text_color . '; background-color: '. $button_color . '}';
    $inline_css .= '.wc-mailchimp-subscribe-form  .newsletter-discount-submit-button:hover{ color: '. $button_text_hover_color . '; background-color: '. $button_hover_color . '}';

    //Error Color
    $inline_css .= '.wc-mailchimp-subscribe-form .newsletter-discount-validation.error{ color: '. $error_color . '}';

    //Success Color
    $inline_css .= '.wc-mailchimp-subscribe-form .newsletter-discount-validation.success{ color: '. $success_color . '}';

    //Add custom css
    wp_enqueue_style( 'wcmnd-stylesheet', plugins_url( 'assets/css/woocommerce-mailchimp-newsletter.css', __FILE__ ), [], WCMND_PLUGIN_VERSION);

    //Add our customized css
    wp_add_inline_style( 'wcmnd-stylesheet', $inline_css );

    wp_enqueue_script('wcmnd-custom-script', plugins_url( 'assets/js/wcmnd-custom.js', __FILE__ ), array( 'jquery'), WCMND_PLUGIN_VERSION, true );

    $invalid_email_error = $this->option('wcmnd_invalid_email_error');
    $optin = $this->option('double_optin') == 'yes' ? 'yes' : '';
    $enable_redirect = 'no';
    $redirect_url = ''; // $this->option('redirect_url');
    $redirect_time = !empty($this->option('redirect_timeout')) ? $this->option('redirect_timeout') : '1';
    $success_message = $this->option('wcmnd_success_msg') !== '' ? $this->option('wcmnd_success_msg') : 'Thank you for subscribing! Check your mail for coupon code!';

    wp_localize_script('wcmnd-custom-script', 'wcmnd', array(
      'ajax_url'            => admin_url( 'admin-ajax.php' ),
      'invalid_email_error' => $invalid_email_error,
      'success_message'     => $success_message,
      'double_optin'        => $optin,
      'enable_redirect'     => $enable_redirect,
      'redirect_url'        => $redirect_url,
      'userExists'          => !empty( $this->option('wcmnd_already_subscribed') ) ? $this->option('wcmnd_already_subscribed') : __('This email address already has been subscribed', 'wc_mailchimp_newsletter_discount'),
      'redirect_timeout'    =>  $redirect_time,
      'please_wait'         => __('Please Wait!', 'wc_mailchimp_newsletter_discount'),
      'subscribe_button_label' => $this->option('button_text'),
      'nonce'               => $ajax_nonce,
      'req_nonce' => wp_create_nonce('wp_rest'),
    ));
  }

  /**
  * @uses Mailchimp integration
  *
  * @param $email | email address
  * @param $fname | first name
  * @return mixed
  *
  */
  private function mailchimp_integration($email, $fname, $lname, $extra_merge_tags) {
    // Get MailChimp Configuration Here
    $mailchimp_api_key     = !empty($this->option( 'mailchimp_key' )) ? $this->option( 'mailchimp_key' ) : '';
    $mailchimp_list_id     = !empty($this->option( 'mailchimp_list_id' )) ? $this->option( 'mailchimp_list_id' ) : '';

    $optin = $this->option( 'double_optin' ) == 'yes' ? 'pending' : 'subscribed';
    $merge_fields = Array('FNAME' => $fname, 'LNAME' => $lname );

    if( is_array($extra_merge_tags) && !empty($extra_merge_tags) ) {
      $merge_fields = array_merge($merge_fields, $extra_merge_tags);
    }


    if( !empty($mailchimp_api_key) && !empty($mailchimp_list_id) ) {

      $mailchimp = new WCMNDMailChimp($mailchimp_api_key);
      $subscriber_Hash = $mailchimp->subscriberHash($email);

      //First check whether user is a member or not
      $check_member = $mailchimp->get("/lists/{$mailchimp_list_id}/members/{$subscriber_Hash}");

      if( is_array($check_member) && isset($check_member['status']) ) {

        switch ($check_member['status']) {
          case '404':
            $result = $mailchimp->post("lists/{$mailchimp_list_id}/members", [
              'email_address' => $email,
              'status'        => $optin,
              'merge_fields'  => $merge_fields,
            ]);

            if( $result['status'] == '400' ) {
              $result['status'] = 'deleted';
            }
            else {
              $result['status'] = 'success';
            }

          break;

          case 'unsubscribed':
            $result['status'] = 'unsubscribed';
          break;

          case 'subscribed':
            $result['status'] = 'subscribed';
          break;

          case 'pending':
            $result['status'] = 'pending';
          break;

          default:
            $result['status'] = 'error';
          break;
        }

        if( $result['status'] == 'success'
          && isset($result['title'])
          && $result['title'] == 'Invalid Resource' ) {
          $result['status'] = 'error';
          $result['message'] = $result['detail'];
        }

        if( $result['status'] == '400'
          && isset($result['title'])
          && $result['title'] == 'Forgotten Email Not Subscribed' ) {
          $result['status'] = 'error';
          $result['message'] = ' '.$email.' was permanently deleted and cannot be re-imported';
        }

        if( $result['status'] == 'deleted' ) {
          $result['message'] = ' '.$email.' was permanently deleted and cannot be re-imported';
        }
      }
      return $result;
    }
  }

  /**
  * @uses Check email for testing purposes
  *
  * @param email
  * @return array
  *
  */
  public function check_email_is_for_testing($email) {
    //Check whether this email is for testing
    $testing_email = $this->option( 'test_email' );
    $response = array();

    if( $testing_email !== '' && $testing_email == $email ) {
      $response['status'] = 'success';
    }
  }


  /**
  * @uses Subscribe to the newsletter
  *
  * @param empty
  * @return mixed
  *
  */
  public function woocommerce_newsletter_subscribe() {
    $email  = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
    $fname  = isset( $_POST['fname'] ) ? sanitize_text_field( $_POST['fname'] ) : '';
    $lname  = isset( $_POST['lname'] ) ? sanitize_text_field( $_POST['lname'] ) : '';

    if ( ! wp_verify_nonce( $_POST['nonce'], 'woocommerce_mailchimp_discounts_nonce' ) ) {
      wp_die( 'Security check' );
    }

    if( empty($email) )
      return;


    $extra_fields = array();

    if( isset($_POST['extraFields']) && !empty($_POST['extraFields']) ) {
      $extra_fields = apply_filters('wcmnd_extra_fields_refer_data', $_POST['extraFields']);
    }

    $testing_email = $this->option( 'test_email' );

    if( $testing_email !== '' &&  $testing_email == $email ) {
      $response['status'] = 'success';
    }

    if( $testing_email !== $email ) {
      $response = $this->mailchimp_integration($email, $fname, $lname, $extra_fields);
    }

    if( is_array($response) ) {
      if( isset($response['status'])
        && $response['status'] == 'success'
        && $response['status'] !== 'error' ) {

        //Lets Create Coupon For This Email User
        $coupon_response = $this->wc_newsletter_send_coupons( $email );

        if( isset($coupon_response['email_response']) ) {
          if( $coupon_response['email_response'] == 'success' ) {
            $result['coupon_code'] = $coupon_response['coupon_code'];
            $result['email_response'] = 'success';
          }
          else {
            $result['email_response'] = 'error';
          }
        }
      }
      echo json_encode($response);
    }
    else {
      echo json_encode( array( 'status' => 'error', 'error' => __( 'Please setup your MailChimp Key and List ID properly.', 'wc_mailchimp_newsletter_discount' ) ) );
    }
    wp_die();
  }


  /**
  * @uses Send coupon to the user
  *
  * @param user email address
  * @return mixed
  *
  */
  public function wc_newsletter_send_coupons($email) {

    //Return if no email found
    if( empty($email) )
      return;

    global $woocommerce;

    $discount_type = $this->option( 'dis_type' );
    $coupon_amount = $this->option( 'coupon_amount' );
    $coupon_code = strtoupper( substr( str_shuffle( md5( time() ) ), 0, 10 ) );;

    $allowed_products = '';
    $excluded_products = '';

    $product_ids = $this->option( 'products' );
    if ( is_array( $product_ids ) ) {
      foreach ( $product_ids as $product_id ) {
        $product = wc_get_product( $product_id );
        $allowed_products .= '<a href="'.$product->get_permalink().'">'.$product->get_title().'</a>,';
      }
      $allowed_products = rtrim( $allowed_products, ',' );
      $product_ids = implode( ',', $product_ids );
    }

    $exclude_product_ids = $this->option( 'exclude_products' );
    if ( is_array( $exclude_product_ids ) ) {
      foreach ( $exclude_product_ids as $product_id ) {
        $product = wc_get_product( $product_id );
        $excluded_products .= '<a href="'.$product->get_permalink().'">'.$product->get_title().'</a>,';
      }

      $excluded_products = rtrim( $excluded_products, ',' );
      $exclude_product_ids = implode( ',', $exclude_product_ids );
    }

    $allowed_cats = '';
    $excluded_cats = '';

    $product_categories = $this->option( 'categories' );

    if ( is_array( $product_categories ) ) {
      foreach ( $product_categories as $cat_id ) {
        $cat = get_term_by( 'id', $cat_id, 'product_cat' );
        $allowed_cats .= '<a href="'.get_term_link( $cat->slug, 'product_cat' ).'">'.$cat->name.'</a>,';
      }
      $allowed_cats = rtrim( $allowed_cats, ',' );
    }
    else
      $product_categories = array();


   $exclude_product_categories = array();

   // $days = $this->option('coupon_valid_days') !== '' ? $this->option('coupon_valid_days') : '10';
    $days = '10';
    $date = '';
    $expire = '';
    $format = $this->option( 'date_format' ) == '' ? 'jS F Y' : $this->option( 'date_format' );

    if ( $days ) {
      $date = date( 'Y-m-d', strtotime( '+'.$days.' days' ) );
      $expire = date_i18n( $format, strtotime( '+'.$days.' days' ) );
    }

    $free_shipping = $this->option( 'free_shipping' ) == '1' ? 'yes' : '';
    // $exclude_sale_items = $this->option( 'exclude_sale_items' ) == '1' ? 'yes' : '';
    $exclude_sale_items = '';
    $minimum_amount = $this->option( 'min_purchase' );
    $maximum_amount = $this->option( 'max_purchase' );

    $customer_email = $this->option( 'email_restrict' ) == '1' ? $email : '';

    //Lets add a new coupon for this email
    $coupon = array(
      'post_title'    => $coupon_code,
      'post_content'  => '',
      'post_status'   => 'publish',
      'post_author'   => 1,
      'post_type'     => 'shop_coupon'
    );

    $coupon_id = wp_insert_post( $coupon );

    //Add coupon meta data
    update_post_meta( $coupon_id, 'discount_type', $discount_type );
    update_post_meta( $coupon_id, 'coupon_amount', $coupon_amount );
    // update_post_meta( $coupon_id, 'individual_use', 'yes' );
    update_post_meta( $coupon_id, 'product_ids', $product_ids );
    update_post_meta( $coupon_id, 'exclude_product_ids', $exclude_product_ids );
    update_post_meta( $coupon_id, 'usage_limit', '1' );
    update_post_meta( $coupon_id, 'usage_limit_per_user', '1' );
    update_post_meta( $coupon_id, 'limit_usage_to_x_items', '' );
    update_post_meta( $coupon_id, 'expiry_date', $date );
    update_post_meta( $coupon_id, 'apply_before_tax', 'no' );
    update_post_meta( $coupon_id, 'free_shipping', $free_shipping );
    update_post_meta( $coupon_id, 'exclude_sale_items', $exclude_sale_items );
    update_post_meta( $coupon_id, 'product_categories', $product_categories );
    update_post_meta( $coupon_id, 'exclude_product_categories', $exclude_product_categories );
    update_post_meta( $coupon_id, 'minimum_amount', $minimum_amount );
    update_post_meta( $coupon_id, 'maximum_amount', $maximum_amount );
    update_post_meta( $coupon_id, 'customer_email', $customer_email );

    $search = array( '{COUPONCODE}', '{COUPONEXPIRY}', '{ALLOWEDCATEGORIES}', '{EXCLUDEDCATEGORIES}', '{ALLOWEDPRODUCTS}', '{EXCLUDEDPRODUCTS}' );
    $replace = array( $coupon_code, $expire, $allowed_cats, $excluded_cats, $allowed_products, $excluded_products );
    $subject = str_replace( $search, $replace, $this->option( 'wcmnd_email_subject' ) );
    $subject = do_shortcode( $subject );
    $body = str_replace( $search, $replace, $this->option( 'wcmnd_email_message' ) );
    $body = stripslashes( $body );
    $body = do_shortcode( $body );

    // Change sender name
    add_filter( 'woocommerce_email_from_name', function( $from_name, $wc_email ) {
      if( $wc_email->id == '' ) {
        $from_name = $this->option( 'wcmnd_mail_from_name' );
      }
      return $from_name;
    }, 10, 2 );

    // Change sender adress
    add_filter( 'woocommerce_email_from_address', function( $from_email, $wc_email ){
      if( $from_email->id == '' ) {
        $from_email = $this->option( 'wcmnd_from_email' );
      }
      return $from_email;
    }, 10, 2 );

    $headers = array('Content-Type: text/html; charset=UTF-8');

    $mailer = WC()->mailer();
    $mailResult = $mailer->send( $email, $subject, $mailer->wrap_message( $subject, $body ), $headers, '' );

    if( ! $mailResult  ) {
      wp_mail( $email, $subject, wpautop( $body ), $headers );
    }

    if( ! $mailResult ) {
      //Try normal php mail function to send email
      $mail_headers = "MIME-Version: 1.0" . "\r\n";
      $mail_headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

      // More headers
      $mail_headers .= 'From: <"'.$this->option( 'wcmnd_from_email' ).'">'.$this->option( 'wcmnd_mail_from_name' ).'' . "\r\n";
      $body = htmlspecialchars_decode($body);
      $mailResult = mail($email, $subject, $body, $mail_headers);
    }

    if( $mailResult )
        $mail_response = 'success';

    return array(
      'coupon_code' => $coupon_code,
      'email_response' => $mail_response
    );
  }

  /**
  * @uses Shortcode for showing the form
  *
  * @param $atts array of params
  * @return mixed
  *
  */

  public  function wc_mailchimp_subscribe_discount_guttenberg_blocks()
  {
    wp_register_script('custom-cta-js' , PLUGIN_URL_SHORT. '/woo-mailchimp-newsletter-discount/build/index.js', array('wp-blocks'));

    register_block_type('wc-mailchimp/custom-cta' , array(
      'editor_script' => 'custom-cta-js')
    );
  }
  

  public function wc_mailchimp_subscribe_discount_shortcode($atts) {
    $options = shortcode_atts( array(
      'width'           => '400px',
      'align'           => '',
      'float_align'     => '',
      'btn_width'       => 'auto',
      'btn_align'       => 'center',
      'top_text'        => '',
      'top_text_align'  => 'center',
      'top_text_color'  => '#000',
      'layout'          => 'vertical',
      'flex_direction' => 'column'
        ), $atts );
    extract( $options );

    $addon_extra_field_id = 'wcmnd_option';
    $addon_extra_field_options = $this->option('wcmnd_addon_fields');

    $fields = $this->option('display_fields');
    $button_text = $this->option('button_text');

    $form = '<form class="wc-mailchimp-subscribe-form wcmnd_' . $fields . '" style=" max-width: '.$options['width'].'; text-align: '.$options['align'].'; float: '.$options['float_align'].' ">';

    $atts = shortcode_atts(
      array(
        "first_name"=> "0",
        "email" => "0",
        "last_name" => "0"
      ),$atts ,"wc_mailchimp_subscribe_discount"
    );

    if($atts['email'] == "yes" ){
      $fields = 'email';
    }

    if($atts['email'] == "yes" && $atts['first_name'] == "yes" && $atts['last_name']){
      $fields = 'firstname_lastname_email';
    }
    
    if($atts['first_name'] == "yes"){         
      $fields ='firstname_email';
    }
    
    if($atts['first_name'] == "yes" && $atts['last_name'] == "yes"){          
      $fields ='firstname_lastname_email';
    }

    if( $top_text !== '' )
      $form .= '<div class="newsletter-subscribe-title" style="color:' . $options['top_text_color'] . '; text-align: '.$options['top_text_align'].' ">' . $options['top_text'] . '</div>';

    $form .= '<div class="wcmnd-fields" style: "display:flex; flex-direction: '.$options['flex_direction'].'">';

    if( $fields == 'firstname_email' || $fields == 'firstname_lastname_email' )
      $form .= '<input type="text" placeholder="'. __('Enter first name', 'wc_mailchimp_newsletter_discount' ) .'" name="wcmnd_fname" class="wcmnd_fname">';

    if( $fields == 'firstname_lastname_email' )
      $form .= '<input type="text" placeholder="'. __('Enter last name', 'wc_mailchimp_newsletter_discount' ) .'" name="wcmnd_lname" class="wcmnd_lname">';

    $form .='<input type="text" placeholder="'. __('Enter your email', 'wc_mailchimp_newsletter_discount' ) .'" name="wcmnd_email" class="wcmnd_email">';
    $form .= '<div class="wcmnd-clear"></div>';

    if( class_exists('WooCommerce_MailChimp_Newsletter_Extra_Fields') ) :
      $form .= apply_filters('wcmnd_addon_fields_hook', $addon_extra_field_id, $addon_extra_field_options);
      $form .= '<div class="wcmnd-clear"></div>';
    endif;

    $form .= '</div>';

    $form .= '<div class="wcmnd-clear"></div>';

    if( $options['btn_align'] == 'center' )
    $options['btn_align'] = 'margin:0 auto;';
    else if( $options['btn_align'] == 'left' || $options['btn_align'] == 'right' )
    $options['btn_align'] = 'float:' . $options['btn_align'] . ';';

    $form .= '<div class="wcmnd-btn-cont"  style = " '. $options['btn_align'].'"><button class="wcmnd-btn newsletter-discount-submit-button" style = "width: '.$options['btn_width'].'; ">' . $button_text . '</button>';
    $form .= '</div>';
    $form .= '<div class="wcmnd-clear"></div>';
    $form .= '<div class="newsletter-discount-validation"></div>';
    $form .= '</form>';
    $form .= '<div class="wcmnd-clear"></div>';

    return $form;

  }



  /**
  * @uses Get mailchimp lists in the admin
  *
  * @param empty
  * @return json object
  *
  */
  public function wcmnd_get_mailchimp_lists() {
    $apiKey = isset($_POST['mailchimp_api_key']) ? sanitize_key($_POST['mailchimp_api_key']) : '';
    if( $apiKey !== '' ) {
      $mc = new WCMNDMailChimp( $apiKey );
      $mailchimp_lists = $mc->get("/lists");

      if( isset($mailchimp_lists['lists']) ) {
        $list_array = array();
        foreach( $mailchimp_lists['lists'] as $key => $list_data ) {
          $list_array[$key]['list_name'] = $list_data['name'];
          $list_array[$key]['list_id'] = $list_data['id'];
        }
      }
    }

    if( !empty($list_array) ) {
      delete_option('wcmnd_mailchimp_list');
      update_option('wcmnd_mailchimp_list', $list_array);
      echo json_encode($list_array);
    }
    wp_die();
  }


  /**
  * @uses Add table for subscribe analytics
  *
  * @param empty
  * @return bool
  *
  */
  public function wcmnd_install_analytics(){
    $check_analytics_table_exists = get_option('wcmnd_analytics_table_exists');

    if( $check_analytics_table_exists !== 'yes' ) {
      global $wpdb;
      $table_name = $wpdb->prefix . 'wcmnd_analytics';

      $charset_collate = $wpdb->get_charset_collate();

      $sql = "CREATE TABLE $table_name (
        id int(11) NOT NULL AUTO_INCREMENT,
        email varchar(80) NOT NULL,
        subscribed_date date DEFAULT '0000-00-00' NOT NULL,
        PRIMARY KEY  (id)
      ) $charset_collate;";

      require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
      dbDelta( $sql );

      update_option('wcmnd_analytics_table_exists', 'yes');
    }
  }

}
