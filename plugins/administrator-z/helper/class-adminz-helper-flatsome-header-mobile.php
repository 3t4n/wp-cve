<?php 
namespace Adminz\Helper;
// use WP_Query;

class ADMINZ_Helper_Flatsome_Header_Mobile{
	function __construct() {
		$this->register_option();
		$this->do_option();
	}

	function do_option(){
		add_action('wp_footer',function(){
			if($maxwidth = get_theme_mod('adminz_logo_mobile_max_width')){
				?>
				<style type="text/css">
					@media only screen and (max-width: 48em) {
						#logo{
							max-width: <?php echo esc_attr($maxwidth) ?>px;
						}
					}
				</style>
				<?php
			}
		});
	}

	function register_option(){
		/*1.add zalo top header top*/
		add_action('customize_register',function ( $wp_customize ) {
			$wp_customize->add_setting(
		      	'adminz_logo_mobile_max_width', array('default' => '')
		  	);
		    $wp_customize->add_control('adminz_logo_mobile_max_width', array(
		        'label'      => __('Logo max width (px)', 'administrator-z'),
		        'section'    => 'header_mobile', 
		    ));
		});
	}
}