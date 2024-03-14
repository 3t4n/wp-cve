<?php
class Woo_Accordions {

  /**
  * Bootstraps the class and hooks required actions & filters.
  */

  private $options;

  public function __construct() {

    $this->options = get_option( 'wooatm' );

    //Check if woocommerce plugin is installed.
    add_action( 'admin_notices', array( $this, 'check_required_plugins' ) );

    //Add setting link for the admin settings
    add_filter( "plugin_action_links_".WOOATM_BASE, array( $this, 'wooatm_settings_link' ) );

    //Add backend settings
    add_filter( 'woocommerce_get_settings_pages', array( $this, 'wooatm_settings_class' ) );

    //return if wooatm is not enabled
    if( $this->options ):
      if( $this->options['enabled'] != 'yes' ) return;
    endif; 
      //Add css and js files for the tabs
      add_action( 'wp_enqueue_scripts',  array( $this, 'wooatm_enque_scripts' ) );

      //Replace default tabs with woocommerce tabs
      add_action( 'plugins_loaded', array( $this, 'add_remove_tabs' ), 10 );
    }

    /**
    *
    * Add necessary js and css files for the popup
    *
    */
    public function wooatm_enque_scripts() {
      //Add the js and css files only on the product single page.
      if( !is_product() ) return; 
      $css   = "body .accordion-header h1{ color:{$this->options['title_color']};}";
      $css  .= "body .accordion-item-active .accordion-header h1{ color:{$this->options['title_active']};}";
      $css  .= "body .accordion-header{ background:{$this->options['bg']}; }";
      $css  .= "body .accordion-item-active .accordion-header{ background:{$this->options['active_bg']}; }";
      $css  .= ".accordion-header-icon{ color:{$this->options['arrow_color']}; }";
      $css  .= ".accordion-header-icon.accordion-header-icon-active{ color:{$this->options['arrow_active']};}";

      //Add responsive tabs css 
      wp_enqueue_style( 'accordions-css', plugins_url( 'assets/css/woco-accordion.css', WOOATM_FILE ) );
      wp_enqueue_script( 'accordions-js', plugins_url( 'assets/js/woco.accordion.js', WOOATM_FILE ) , array( 'jquery' ), '1.0.0', true);
      wp_enqueue_script( 'accordions-custom', plugins_url( 'assets/js/custom.js', WOOATM_FILE ), array( 'jquery', 'accordions-js' ), '1.0.0', true );
      wp_add_inline_style( 'accordions-css', $css );

      wp_localize_script( 'accordions-custom', 'wooatm', array(
        'first_child_expand'  => $this->options['first_child_expand'],
        'multiple_expand'     => $this->options['multiple_expand']
      ));
    }

  /**
  *
  * Check if woocommerce is installed and activated and if not
  * activated then deactivate woocommerce mailchimp discount.
  *
  */
  public function check_required_plugins() {

    //Check if woocommerce is installed and activated
    if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) { ?>
      <div id="message" class="error">
        <p>WooCommerce Accordions requires <a href="http://www.woothemes.com/woocommerce/" target="_blank">WooCommerce</a> to be activated in order to work. Please install and activate <a href="<?php echo admin_url('/plugin-install.php?tab=search&amp;type=term&amp;s=WooCommerce'); ?>" target="">WooCommerce</a> first.</p>
      </div>
      <?php
      deactivate_plugins( '/woo-accordions/wooatm.php' );
    }
  }

  /**
  * Add new link for the settings under plugin links
  *
  * @param array $links an array of existing links.
  * @return array of links  along with wooatm settings link.
  *
  */
  public function wooatm_settings_link($links) {
    $new_links = array();
    if( is_plugin_active( 'woocommerce/woocommerce.php' ) ):
      $pro_link = 'https://www.magnigenie.com/downloads/wooatm-woocommerce-accordions-tab-manager/';
      $settings_link = esc_url( add_query_arg( array(
                              'page' => 'wc-settings',
                              'tab'  => 'wooatm'
                              ), admin_url( 'admin.php' ) ) );
      $new_links[ 'settings' ] = sprintf( '<a href="%1$s" title="%2$s">%2$s</a>', $settings_link, esc_attr__( 'Settings', 'wooatm' ) );
      $new_links[ 'go-pro' ] = sprintf( '<a target="_blank" style="color: #45b450; font-weight: bold;" href="%1$s" title="%2$s">%2$s</a>', $pro_link, esc_attr__( 'Go Pro', 'wooatm' ) );
    endif;
    return array_merge( $links, $new_links );
  }

  /**
  * Add new admin setting page for wooatm settings.
  *
  * @param array   $settings an array of existing setting pages.
  * @return array of setting pages along with wooatm settings page.
  *
  */
  public function wooatm_settings_class( $settings ) {
    $settings[] = include 'class-wc-settings-wooatm.php';
    return $settings;
  }

  /**
  * Replace default woocommerce tabs with woo accordions.
  *
  * @param void
  *
  */
  public function add_remove_tabs(){
    remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
    add_action( 'woocommerce_after_single_product_summary', array( $this, 'wooatm_output_tabs'), 10 );
  }

  /**
  * Output woo accordions.
  *
  * @param void
  *
  */
  public function wooatm_output_tabs(){
    include_once( WOOATM_PATH . '/includes/accordion-tabs.php' );
  }
}