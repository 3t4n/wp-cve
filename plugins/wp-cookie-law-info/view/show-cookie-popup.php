<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/* This is the output */
add_action('wp_footer', 'wcl_show_cookie_law_popup_callback');
function wcl_show_cookie_law_popup_callback() {
	// Get Popup box setting
	$wcl_option 	= get_option( 'wcl_settings' );
	$cookie_enable 	= $wcl_option['_enable'];
	if ($cookie_enable == '1'){
		$position 		= $wcl_option['_position'];
		$theme 			= $wcl_option['_theme'];
		$popup_bgcolor 	= $wcl_option['_popup_bgcolor'];
		$popup_txtcolor = $wcl_option['_popup_txtcolor'];
		$popup_message 	= $wcl_option['_popup_message'];
		$btn_bgcolor 	= $wcl_option['_btn_bgcolor'];
		$btn_txtcolor	= $wcl_option['_btn_txtcolor'];
		$btn_lable 		= $wcl_option['_btn_lable'];
		$policy_lable 	= $wcl_option['_policy_lable'];
		$policy_url 	= $wcl_option['_policy_url'];
		?>
		<script>
			window.addEventListener("load", function(){
			window.cookieconsent.initialise({
			  	"palette": {
			    	"popup": {
			    		"background": "<?php echo $popup_bgcolor; ?>",
			    		"text": "<?php echo $popup_txtcolor; ?>"
			    	},
		    		"button": {
			      		"background": "<?php echo ( !empty($theme) && $theme == 'wire')? 'transparent': $btn_bgcolor; ?>",
			      		"text": "<?php echo $btn_txtcolor; ?>",
			      		<?php if(!empty($theme) && $theme == 'wire'): ?>
			      		"border": "<?php echo $btn_txtcolor; ?>"
			      		<?php endif;	?>
			    	}
			  	},
			  	"theme" : "<?php echo $theme; ?>",
			  	"position": "<?php echo ( !empty($position) && $position == 'top-pushdown')? 'top': $position; ?>",
			  	<?php if(!empty($position) && $position == 'top-pushdown'): ?>
			  	"static": true,
				<?php endif; ?>
			  	"content": {
			    	"message": "<?php echo $popup_message; ?>",
			    	"dismiss": "<?php echo $btn_lable; ?>",
			    	"link": "<?php echo $policy_lable; ?>",
			    	"href": "<?php echo $policy_url; ?>"
			  	}
			})});
		</script>
		<?php
	}//end if() cookie_enable.
}