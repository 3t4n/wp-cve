<?php 
class WDO_Addons_VC {

	function __construct() {
		add_action( 'init', array($this, 'wdo_ultimate_modules' ));
		add_action( "admin_enqueue_scripts", array( $this, "custom_param_styles" ) );
		add_action( 'init', array( $this, 'check_if_vc_is_install' ) );
		
		define( 'ULT_URL', plugins_url('/', __FILE__ ) );
		
	}

		
	function wdo_ultimate_modules() {

		include 'includes/ult-shortcodes/ult-banner.php';
		include 'includes/ult-shortcodes/ult-3d-buttons.php';
		include 'includes/ult-shortcodes/ult-buttons.php';
		include 'includes/ult-shortcodes/ult-image-over-image.php';
		include 'includes/ult-shortcodes/ult-blockquote.php';
		include 'includes/ult-shortcodes/ult-tabs.php';
		include 'includes/ult-shortcodes/ult-text-over-image.php';
		include 'includes/ult-shortcodes/ult-marquee.php';
		include 'includes/ult-shortcodes/ult-image-caption.php';
		include 'includes/ult-shortcodes/ult-pricing-tables.php';
		include 'includes/ult-shortcodes/ult-team.php';
		include 'includes/ult-shortcodes/ult-video.php';
		include 'includes/ult-shortcodes/ult-image-slider.php';
		include 'includes/ult-shortcodes/ult-icon-seperator.php';
		include 'includes/ult-shortcodes/ult-ordered-list.php';
		include 'includes/ult-shortcodes/ult-unordered-list.php';
		include 'includes/ult-shortcodes/ult-animation-block.php';
		include 'includes/ult-shortcodes/ult-cards.php';
		include 'includes/ult-shortcodes/ult-social-cards.php';
		include 'includes/ult-shortcodes/ult-image-overlay.php';
		include 'includes/ult-shortcodes/ult-team-flip.php';
		include 'includes/ult-shortcodes/ult-flip-box.php';
	}

	function custom_param_styles() {
		echo '<style type="text/css">
				.wdo_items_to_show.vc_shortcode-param {
					background: #E6E6E6;
					padding-bottom: 10px;
				}
				.wdo_items_to_show.wdo_margin_bottom{
					margin-bottom: 15px;
				}
				.wdo_items_to_show.wdo_margin_top{
					margin-top: 15px;
				}
			</style>';
	}


	function check_if_vc_is_install(){
        if ( ! defined( 'WPB_VC_VERSION' ) ) {
            // Display notice that Visual Compser is required
            add_action('admin_notices', array( $this, 'showVcVersionNotice' ));
            return;
        }			
	}

	function showVcVersionNotice() { 
	    $plugin_name = 'All in One Visual Composer Addons';
        echo '
        <div class="updated">
          <p>'.sprintf(__('<strong>%s</strong> requires <strong><a href="http://codecanyon.net/item/visual-composer-page-builder-for-wordpress/242431?ref=labibahmed" target="_blank">Visual Composer</a></strong> plugin to be installed and activated on your site.', 'wdo-carousel'), $plugin_name).'</p>
        </div>';
	}

}
?>