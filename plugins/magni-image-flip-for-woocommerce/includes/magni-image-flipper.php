<?php

class Woo_Magni_Image {

  /**
  * Bootstraps the class and hooks required actions & filters.
  *
  */

  private $options;

  public function __construct() {

    $this->options = get_option( 'woomi' );

      //Check if woocommerce plugin is installed.
      add_action( 'admin_notices', array( $this, 'check_required_plugins' ) );

      //Add setting link for the admin settings
      add_filter( "plugin_action_links_".WOOMI_BASE, array( $this, 'woomi_settings_link' ) );

      //Add backend settings
      add_filter( 'woocommerce_get_settings_pages', array( $this, 'woomi_settings_class' ) );

      //return if woomi is not enabled
      if($this->options):
        if( $this->options['enabled'] != 'yes' ) return;
      endif;  

      //Add css and js files for the tabs
      add_action( 'wp_enqueue_scripts',  array( $this, 'woomi_enque_scripts' ) );

      //Get the product images
      add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'woocommerce_template_product_thumbnails' ), 11 );

      //product loop image for bottom pager position
      add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );

      //Check if the product has a gallery
      add_filter( 'post_class', array( $this, 'product_has_gallery' ) );

      //Add js and css for select2 in admin
      add_action('admin_enqueue_scripts', array( $this, 'admin_option_select2') );

    }

    
  /**
  *
  * Add necessary js and css files for the magni image effects
  *
  */
  public function woomi_enque_scripts() {
    $settings = get_option( 'woomi' );
    wp_enqueue_style( 'magniimage-css', plugins_url( '/assets/css/magniimage.css', WOOMI_FILE ) );

    wp_register_script( 'magni-image-js', plugins_url( '/assets/js/magniimage.js', WOOMI_FILE ), array( 'jquery' ), '1.0.0', true );
    wp_enqueue_script( 'magni-image-js' );

    $settings_array = array(
      'dotvalue'        => $settings['dots'],
      'imgeffect'       => $settings['imgeffect'],
      'activedotcolor'  => $settings['activedotcolor'],
      'inactivedotcolor' => $settings['inactivedotcolor'],
      'speed' => $settings['speed'],
      'dotposition' => $settings['dotposition']
    );

    wp_localize_script( 'magni-image-js', 'magniimage_vars', $settings_array  );


    // Add custom color and place to image pager dots
    $activecolor = $settings_array['activedotcolor'];
    $inactivecolor = $settings_array['inactivedotcolor'];

    $custom_css = '';
    $custom_css .= ".imgsliderdots span.cycle-pager-active { color: $activecolor;}
                    .imgflipdots span.cycle-pager-active { color: $activecolor;}
                    .imgfadedots span.cycle-pager-active { color: $activecolor;}
                    .imgsliderdots span { color: $inactivecolor;}
                    .imgflipdots span { color: $inactivecolor;}
                    .imgfadedots span { color: $inactivecolor;}";

    $dotposition = $settings_array['dotposition'];
    if($dotposition == 'topleft') {
      $custom_css .= ".imgsliderdots { text-align: left; left: 5px; top: -15px;}
                      .imgflipdots { text-align: left; left: 5px; top: -15px;}
                      .imgfadedots { text-align: left; left: 5px; top: -15px;}";
    }
    elseif ($dotposition == 'topright') {
      $custom_css .= ".imgsliderdots { text-align: right; left:-5px; top:-15px;}
                      .imgflipdots { text-align: right; left:-5px; top: -15px;}
                      .imgfadedots { text-align: right; left:-5px; top: -15px;}";
    }
    elseif ($dotposition == 'bottomleft') {
      $custom_css .= ".imgsliderdots { text-align: left; left: 5px; top: unset; bottom:5px;}
                      .imgflipdots { text-align: left; left: 5px; top: unset; bottom:5px;}
                      .imgfadedots { text-align: left; left: 5px; top: unset; bottom:5px;}";
    }
    elseif ($dotposition == 'bottomright') {
      $custom_css .= ".imgsliderdots { text-align: right; left:-5px; top: unset; bottom:5px;}
                      .imgflipdots { text-align: right; left:-5px; top: unset; bottom:5px;}
                      .imgfadedots { text-align: right; left:-5px; top: unset; bottom:5px;}";
    }

    wp_add_inline_style( 'magniimage-css', $custom_css );
    wp_enqueue_script( 'wcmi-jquery-cycle2', plugins_url( '/assets/js/jquery.cycle2.js', WOOMI_FILE ), array( 'jquery' ), '1.0.0', true  );
    wp_enqueue_script( 'woomi-jquery-cycle2-scrollvert', plugins_url( '/assets/js/jquery.cycle2.scrollVert.js', WOOMI_FILE ), array( 'jquery' ), '1.0.0', true  );
    wp_enqueue_script( 'woomi-jquery-cycle2-flipvert', plugins_url( '/assets/js/jquery.cycle2.flip.js', WOOMI_FILE ), array( 'jquery' ), '1.0.0', true  );
  }

  public function admin_option_select2(){
    wp_enqueue_script( 'admin_select2_js', plugins_url( '/assets/js/select2.js', WOOMI_FILE) );
    wp_enqueue_style( 'admin_select2_css', plugins_url( '/assets/css/select2.css', WOOMI_FILE ) );
    wp_enqueue_script( 'magniimage-custom', plugins_url( '/assets/js/custom.js', WOOMI_FILE ), array( 'jquery' ), '1.0.0', true );
  }

  /**
  *
  * Check if woocommerce is installed and activated and if not
  * activated then deactivate magni image flip for woocommerce.
  *
  */
  public function check_required_plugins() {

    //Check if woocommerce is installed and activated
    if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) { ?>
      <div id="message" class="error">
        <p>Magni Image Flip for WooCommerce requires <a href="http://www.woothemes.com/woocommerce/" target="_blank">WooCommerce</a> to be activated in order to work. Please install and activate <a href="<?php echo admin_url('/plugin-install.php?tab=search&amp;type=term&amp;s=WooCommerce'); ?>" target="">WooCommerce</a> first.</p>
      </div>
      <?php
      deactivate_plugins( '/magni-image-flip-for-woocommerce/magni-image.php' );
    }
  }

  /**
  * Add new link for the settings under plugin links
  *
  * @param array   $links an array of existing links.
  * @return array of links  along with woomi settings link.
  *
  */
  public function woomi_settings_link($links) {
    $settings_link = '<a href="'.admin_url('admin.php?page=wc-settings&tab=woomi').'">Settings</a>';
    array_unshift( $links, $settings_link );
    return $links;
  }

  /**
  * Add new admin setting page for woomi settings.
  *
  * @param array   $settings an array of existing setting pages.
  * @return array of setting pages along with woomi settings page.
  *
  */
  public function woomi_settings_class( $settings ) {
    $settings[] = include 'magni-image-flipper-settings.php';
    return $settings;
  }


  // Add wcmi-has-gallery class to products that have a gallery
  function product_has_gallery( $classes ) {
    global $product;

    $post_type = get_post_type( get_the_ID() );
    if ( ! is_admin() ) {
      if ( $post_type == 'product' ) {
        $productimg_ids = $this->get_gallery_image_ids( $product );
        if ( $productimg_ids ) {
          $classes[] = 'wcmi-has-gallery';
        }
      }
    }
  return $classes;
  }

  // Functions to show Magni Image Effects on frontend
  function woocommerce_template_product_thumbnails() {
    global $product;
    $productimg_ids = $this->get_gallery_image_ids( $product );
    $pid = $product->get_id();
    
    if ( $productimg_ids ) {
      $productimg_ids = array_values( $productimg_ids );
      $productimg = woocommerce_get_product_thumbnail();
      $prodgalleryimg = wp_get_attachment_image($productimg_ids['0'], 'shop_catalog', '', $attr = array( 'class' => 'attachment-shop-catalog wp-post-image' ) );

      $settings = get_option( 'woomi' );


      $html = '';

      if($settings['imgeffect'] == "flip") {
        $html .='<div class="flip-pager'.$pid.' imgflipdots" style="overflow: hidden; display: none;"></div><div class="cycle-slideshow productimgflip magni-box" data-cycle-fx=flipHorz data-cycle-timeout=0 data-cycle-pager=".flip-pager'.$pid.'" style="overflow: hidden; display: none;"
          >'.$productimg.$prodgalleryimg.'</div></div>';
      }
      else if($settings['imgeffect'] == "fade") {
        $html .='<div class="fade-pager'.$pid.' imgfadedots" style="overflow: hidden; display: none;"></div><div class="cycle-slideshow productimgfade magni-box" data-cycle-fx=fade data-cycle-timeout=0 data-cycle-pager=".fade-pager'.$pid.'" style="overflow: hidden; display: none;">'.$productimg.$prodgalleryimg.'</div></div>';
      }
      else if($settings['imgeffect'] == "slider") {
        $html .='<div class="slider-pager'.$pid.' imgsliderdots" style="overflow: hidden; display: none;"></div><div class="cycle-slideshow productimgslider magni-box" data-cycle-fx=scrollHorz data-cycle-timeout=0
          data-cycle-pager=".slider-pager'.$pid.'" style="overflow: hidden; display: none;">'.$productimg.$prodgalleryimg.'</div></div>';
      }
      echo $html;
    }
  }

  // Get gallery attachment ids of product
  function get_gallery_image_ids( $product ) {
    if ( ! is_a( $product, 'WC_Product' ) )
      return;

    if ( is_callable( 'WC_Product::get_gallery_image_ids' ) )
        return $product->get_gallery_image_ids();
     else
        return $product->get_gallery_image_ids();
  }

}

if ( ! function_exists( 'woocommerce_template_loop_product_thumbnail' ) ) {

  /**
   * Get the product thumbnail for the loop.
   */
  function woocommerce_template_loop_product_thumbnail() {
    $settings = get_option( 'woomi' );
    if( $settings['enabled'] == "yes" ) {
    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    echo "<div class='flip-bottom'>" . woocommerce_get_product_thumbnail();
    } else {
      echo woocommerce_get_product_thumbnail();
    }

  }
}
