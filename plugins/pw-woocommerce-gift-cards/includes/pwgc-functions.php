<?php

defined( 'ABSPATH' ) or exit;

if ( ! function_exists( 'pwgc_get_designs' ) ) {
    function pwgc_get_designs() {
        global $pw_gift_cards;

        $designs = maybe_unserialize( get_option( 'pw_gift_card_designs_free', array() ) );
        if ( empty( $designs ) ) {
            return $pw_gift_cards->default_designs;
        }

        return $designs;
    }
}

if ( ! function_exists( 'pwgc_redeem_url' ) ) {
    function pwgc_redeem_url( $item_data ) {
        $shop_url = get_permalink( wc_get_page_id( 'shop' ) );
        if ( empty( $shop_url ) ) {
            $shop_url = site_url();
        }
        $redeem_url = add_query_arg( 'pw_gift_card_number', urlencode( $item_data->gift_card_number ), $shop_url );

        return apply_filters( 'pwgc_redeem_url', $redeem_url, $item_data );
    }
}

if ( ! function_exists( 'pwgc_color_picker_field' ) ) {
    function pwgc_color_picker_field( $design, $key, $label ) {
        global $pw_gift_cards;

        if ( !empty( $design[ $key ] ) ) {
            $color = $design[ $key ];
        } else {
            $color = get_option( 'woocommerce_email_text_color', '#3c3c3c' );
        }
        $id = 'pwgc-designer-' . str_replace( '_', '-', $key );

        $preview_element = $pw_gift_cards->design_colors[ $key ][0];
        $preview_element_css = $pw_gift_cards->design_colors[ $key ][1];

        ?>
        <p class="form-field">
            <label class="pwgc-designer-label"><?php echo $label; ?></label>
            <input type="text" name="<?php echo $key; ?>" id="<?php echo $id; ?>" value="<?php echo $color; ?>" style="color: <?php echo $color; ?>; background-color: <?php echo $color; ?>; max-width: 75px;">
        </p>
        <script>
            jQuery(function() {
                pwgcAssignColorPicker('#<?php echo $id; ?>', '<?php echo $preview_element; ?>', '<?php echo $preview_element_css; ?>');
            });
        </script>
        <?php
    }
}

if ( ! function_exists( 'pwgc_dashboard_helper' ) ) {
    // Optionally set the selected CSS for the appropriate section.
    function pwgc_dashboard_helper( $item, $output = 'pwgc-dashboard-item-selected' ) {
        $selected = false;
        if ( isset( $_REQUEST['section'] ) ) {
            $selected = ( $_REQUEST['section'] == $item );
        } else if ( $item == 'balances' ) {
            $selected = true;
        }

        echo ( $selected ) ? $output : '';
    }
}

if ( ! function_exists( 'pwgc_paypal_ipn_pdt_bug_exists' ) ) {
    function pwgc_paypal_ipn_pdt_bug_exists() {
        $bug_exists = false;
        $ipn_enabled = false;
        $pdt_enabled = false;
        $woocommerce_paypal_settings = get_option( 'woocommerce_paypal_settings' );

        if ( empty( $woocommerce_paypal_settings['ipn_notification'] ) || 'no' !== $woocommerce_paypal_settings['ipn_notification'] ) {
            $ipn_enabled = true;
        }

        if ( ! empty( $woocommerce_paypal_settings['identity_token'] ) ) {
            $pdt_enabled = true;
        }

        if ( $ipn_enabled && $pdt_enabled ) {
            $bug_exists = true;
        }

        return apply_filters( 'pwgc_paypal_ipn_pdt_bug_exists', $bug_exists );
    }
}

if ( ! function_exists( 'pwgc_get_gift_card_product' ) ) {
    function pwgc_get_gift_card_product() {
        $query = new WC_Product_Query( array(
            'type' => PWGC_PRODUCT_TYPE_SLUG,
            'limit' => 1,
            'orderby' => 'date',
            'order' => 'DESC',
            'status' => 'publish',
        ) );
        $products = $query->get_products();

        if ( !empty( $products ) ) {
            return $products[0];
        } else {
            return null;
        }
    }
}

if ( ! function_exists( 'pwgc_admin_page' ) ) {
    function pwgc_admin_page() {
        // If the Pimwick Plugins menu is hidden, we need to redirect to the WooCommerce menu instead.
        if ( get_option( 'hide_pimwick_menu', 'no' ) == 'no' ) {
            $page = 'pw-gift-cards';
        } else {
            $page = 'wc-pw-gift-cards';
        }

        return apply_filters( 'pwgc_admin_page', $page );
    }
}

if ( ! function_exists( 'pwgc_admin_url' ) ) {
    function pwgc_admin_url( $section = '', $args = false ) {

        $admin_url = admin_url( 'admin.php' );
        $admin_url = add_query_arg( 'page', pwgc_admin_page(), $admin_url );

        if ( !empty( $section ) ) {
            $admin_url = add_query_arg( 'section', $section, $admin_url );
        }

        if ( !empty( $args ) && is_array( $args ) ) {
            foreach ( $args as $key => $value ) {
                $admin_url = add_query_arg( $key, $value, $admin_url );
            }
        }

        return apply_filters( 'pwgc_admin_url', $admin_url, $section, $args );
    }
}

if ( !function_exists( 'pwgc_strtotime' ) ) {
    // Source: https://mediarealm.com.au/articles/wordpress-timezones-strtotime-date-functions/
    function pwgc_strtotime($str, $gmt = false) {
      // This function behaves a bit like PHP's StrToTime() function, but taking into account the Wordpress site's timezone
      // CAUTION: It will throw an exception when it receives invalid input - please catch it accordingly
      // From https://mediarealm.com.au/

      $tz_string = get_option('timezone_string');
      $tz_offset = get_option('gmt_offset', 0);

      if (function_exists('wp_timezone_string')) {
        $timezone = wp_timezone_string();
        if( !empty($timeZoneString)){
            $timezone = $timeZoneString;
        }else{
            $timezone = 'UTC';
        }
      } else {
          if (!empty($tz_string)) {
              // If site timezone option string exists, use it
              $timezone = $tz_string;

          } elseif ($tz_offset == 0) {
              // get UTC offset, if it isn’t set then return UTC
              $timezone = 'UTC';

          } else {
              $timezone = $tz_offset;

              if(substr($tz_offset, 0, 1) != "-" && substr($tz_offset, 0, 1) != "+" && substr($tz_offset, 0, 1) != "U") {
                  $timezone = "+" . $tz_offset;
              }
          }
      }

      $datetime = new DateTime($str);
      try {
          $datetime->setTimezone(new DateTimeZone($timezone));
      } catch ( Exception $ex ) {
          // set timezone to UTC in case current timezone throws an exception
          $timezone = 'UTC';
          $datetime->setTimezone(new DateTimeZone($timezone));
      }

      if ( $gmt ) {
          return ( $datetime->getTimestamp() + $datetime->getOffset() );
      } else {
          return $datetime->format('U');
      }
    }
}
