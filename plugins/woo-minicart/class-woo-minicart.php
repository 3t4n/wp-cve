<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class WMC_Main_Class{

    public $wmc_options;
    public $plugin_version;

    public function __construct(){
      if ( defined( 'WOO_MINICART_VERSION' ) ) {
          $this->plugin_version = WOO_MINICART_VERSION;
      } else {
          $this->plugin_version = '1.0';
      }
      $this->wmc_options = get_option( 'wmc_options' );
      add_action( 'admin_menu', array( $this, 'wmc_admin_menu' ) );
      add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
      if( $this->wmc_options['enable-minicart'] == 1 ) {
          add_action( 'wp_footer', array( $this, 'templates' ) );
          add_action( 'wp_head', array( $this, 'dynamic_css' ) );
      }
      add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'wmc_fragments' ), 30, 1);
      add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
      add_shortcode( 'woo-minicart', array( $this, 'woo_minicart_shortcode' ) );
  }

  public function scripts(){
      wp_enqueue_style( 'wmc-template1', plugins_url( '/assets/css/wmc-default-template.css', __FILE__ ), '', $this->plugin_version );
      wp_enqueue_script( 'wmc-js', plugins_url('/assets/js/woo-minicart.js', __FILE__), 'jQuery', $this->plugin_version, true );
  }

  public function admin_scripts($hook){
    if($hook != 'toplevel_page_woo-minicart') {
       return;
   }
   wp_enqueue_style( 'wmc-admin-css', plugins_url('/assets/css/admin.css', __FILE__), '', $this->plugin_version );
   wp_enqueue_script( 'wmc-admin-js', plugins_url('/assets/js/admin.js', __FILE__), 'jQuery', $this->plugin_version, true );
}

public function templates(){
  if( !is_cart() && !is_checkout() ){
    require plugin_dir_path( __FILE__ ) . '/frontend/wmc-default-template.php';
}
}


public function wmc_fragments( $fragments ){
  ob_start();
  require plugin_dir_path( __FILE__ ) . '/frontend/wmc-content.php';
  $fragments['div.wmc-content'] = ob_get_clean();
  $fragments['span.wmc-count'] = '<span class="wmc-count">'. WC()->cart->get_cart_contents_count() .'</span>';
  return $fragments;
}

public function woo_minicart_shortcode(){
  if( !is_cart() && !is_checkout() ){
    ob_start();
    require plugin_dir_path( __FILE__ ) . '/frontend/wmc-shortcode-template.php';
    $shortcode = ob_get_clean();
    return $shortcode;
}
}

public function dynamic_css(){
    $minicart_position = $this->wmc_options['minicart-position']; 
    $wmc_offset        = $this->wmc_options['wmc-offset'];
    ?>
    <style type="text/css">
        <?php if( $minicart_position == 'wmc-top-left' ) : ?>
            .wmc-cart-wrapper{
                left: 50px;
                top: <?php echo esc_html($wmc_offset); ?>px;
            }
            .wmc-cart{
                left: 10px;
            }
            <?php elseif( $minicart_position == 'wmc-top-right' ) : ?>
                .wmc-cart-wrapper{
                    right: 50px;
                    top: <?php echo esc_html($wmc_offset); ?>px;
                }
                .wmc-cart{
                    right: 10px;
                }
                <?php elseif( $minicart_position == 'wmc-bottom-left' ) : ?>
                    .wmc-cart-wrapper{
                        left: 50px;
                        bottom: 100px;
                    }
                    .wmc-cart{
                        left: 10px;
                    }
                    .wmc-content{
                        position: fixed;
                        bottom: 100px;
                        top: unset;
                        right: unset;
                    }
                    <?php elseif( $minicart_position == 'wmc-bottom-right' ) : ?>
                        .wmc-cart-wrapper{
                            right: 50px;
                            bottom: 100px;
                        }
                        .wmc-cart{
                            right: 10px;
                        }
                        .wmc-content{
                            position: fixed;
                            bottom: 100px;
                            top: unset;
                        }
                    <?php endif; ?>
                </style>
            <?php }

            public function wmc_admin_menu(){
             add_menu_page(
                __( 'Minicart Options', 'woo-minicart' ),
                __( 'Woo Minicart', 'woo-minicart' ),
                'manage_options',
                'woo-minicart',
                array( $this, 'wmc_admin_menu_callback' ),
                'dashicons-admin-generic',
                59
            );
         }

         public function wmc_admin_menu_callback(){
             include plugin_dir_path( __FILE__ ) . '/admin/wmc-admin.php';
         }
     }