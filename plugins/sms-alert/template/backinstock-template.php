<?php
/**
 * Backin stock template.
* PHP version 5
 *
 * @category Template
 * @package  SMSAlert
 * @author   SMS Alert <support@cozyvision.com>
 * @license  URI: http://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://www.smsalert.co.in/
 */
	use Elementor\Frontend;
	$post = get_page_by_path( 'notifyme_style', OBJECT, 'sms-alert' );
	if ( is_plugin_active('elementor/elementor.php') && !empty($post)) {  
	 $post_id= $post->ID;	
	 $frontent = new Frontend();
	 $content =  $frontent->get_builder_content($post_id);	
	} else {
	 $content = SAPopup::getNotifyMeStyle();		
	}		
	echo $content;
?>
 <input type="hidden" id="sa-product-id" name="sa-product-id" value="<?php echo esc_attr($product_id); ?>"/>
 <input type="hidden" id="sa-variation-id" name="sa-variation-id" value="<?php echo esc_attr($variation_id); ?>"/>
<div style="clear:both;"></div>
