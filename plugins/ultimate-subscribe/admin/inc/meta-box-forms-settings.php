<?php
/**
 * Form Details
 *
 * Display the subscribe Form details meta box.
 *
 * @author      ThemeFarmer
 * @category    Admin
 * @package     UltimateSubscribe/Admin/Meta Boxes
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Ultimate_Subscribe_Forms_Settings Class.
 */
class Ultimate_Subscribe_Forms_Settings {

	/**
	 * Output the metabox.
	 *
	 * @param WP_Post $post
	 */
	public static function output( $post ) {

		$form_settings			= get_post_meta($post->ID, 'ultimate_subscribe_form_settings', true);
		$form_text   			= get_post_meta($post->ID, 'ultimate_subscribe_form_text', true);
		$list_storege 			= isset($form_settings['list_storege'])?$form_settings['list_storege']:'database';
		$list_id 				= isset($form_settings['list_id'])?$form_settings['list_id']:'';
		$lists 					= ultimate_subscribe_get_lists($list_storege);
		$heading   				= isset($form_text['heading'])?$form_text['heading']:__('Subscribe To Our Newsletter', 'ultimate-subscribe');
		$sub_heading   			= isset($form_text['sub_heading'])?$form_text['sub_heading']:__('subscribe to our newsletter to get latest offers & updates into your email inbox', 'ultimate-subscribe');
		$descricption   		= isset($form_text['descricption'])?$form_text['descricption']:__('MONTHLY NEWSLETTER', 'ultimate-subscribe');;
		$after_subcribe_text  	= isset($form_text['after_subcribe_text'])?$form_text['after_subcribe_text']:__("don\'t worry we hate spam as much as you do!", 'ultimate-subscribe');
		$button_label       	= isset($form_text['button_label'])?$form_text['button_label']:__('Subscribe', 'ultimate-subscribe');
		$options                = get_option('ultimate_subscribe_options');
		$mailchimp_api_key      = isset($options['mailchimp_api'])?$options['mailchimp_api']:'';
		$disabled 				='';
		$mailchimp_disable 		='';
		if (empty($mailchimp_api_key)) {
			$disabled = 'disabled';
			$mailchimp_disable = '( '.__('Add API Key in Settings', 'ultimate-subscribe').' )';
		}
		wp_nonce_field('ultimate_subscribe_save_meta_data_nonce', 'ultimate_subscribe_nonce');
		?>
		<div id="form-settings-container" class="ultimate-subscribe-meta-fields">
			<div class="meta-heading" style="text-align:center;"><?php _e('Form Settings', 'ultimate-subscribe');?></div>
			<div class="meta-settings">
				<div class="field-row">
					<div class="field-label"> <?php _e('Subcriber Store In', 'ultimate-subscribe'); ?> </div>
					<div class="field-data">
						<script type="text/javascript">
							jQuery(document).ready(function($) {
								$(document).on('change', '#ultimate-subscribe-list-store', function(event) {
									var list_storege = $(this).val();
									event.preventDefault();
									$.ajax({
										url: ajaxurl,
										type: 'POST',
										dataType: 'html',
										data: {action:'ultimate_subscribe_ajax_get_lists', list_storege: list_storege},
										success: function(lists_html){
											$('#ultimate-subscribe-lists').html(lists_html);
										},
										error: function(){

										},
										complete: function(){

										},
									});
								});
							});
						</script>
						<select name="list_storege" id="ultimate-subscribe-list-store">
							<option value="database" <?php selected($list_storege, 'database', true) ?>>Database</option>
							<option value="mailchimp" <?php selected($list_storege, 'mailchimp', true) ?> <?php echo esc_attr($disabled); ?>>MailChimp <?php echo esc_html($mailchimp_disable); ?></option>
						</select>
					</div>
				</div>
				<div class="field-row">
					<div class="field-label"> <?php _e('Select List', 'ultimate-subscribe'); ?> </div>
					<div class="field-data">
						<select name="list_id" id="ultimate-subscribe-lists">
							<?php foreach ($lists as $key => $list): ?>
							<option value="<?php echo esc_attr($list['id']); ?>" <?php selected($list_id, $list['id'], true) ?>><?php echo esc_html($list['name']); ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
			</div>
			<div class="meta-heading" style="text-align:center;"><?php _e('Form Text', 'ultimate-subscribe');?></div>
			<div class="meta-settings">
				<div class="field-row">
					<div class="field-label"> <?php _e('Heading', 'ultimate-subscribe'); ?> </div>
					<div class="field-data">
						<input type="text" value="<?php echo esc_attr($heading); ?>" id="heading" name="heading" class="us-input"/>
					</div>
				</div>
				<div class="field-row">
					<div class="field-label"> <?php _e('Sub Heading', 'ultimate-subscribe'); ?> </div>
					<div class="field-data">
						<textarea id="sub_heading" name="sub_heading" class="us-input"/><?php echo esc_textarea($sub_heading); ?></textarea> 
					</div>
				</div>
				<div class="field-row">
					<div class="field-label"> <?php _e('descricption', 'ultimate-subscribe'); ?> </div>
					<div class="field-data">
						<textarea id="descricption" name="descricption" class="us-input"/><?php echo esc_textarea($descricption); ?></textarea> 
					</div>
				</div>
				<div class="field-row">
					<div class="field-label"> <?php _e('After Subcribe Button Text', 'ultimate-subscribe'); ?> </div>
					<div class="field-data">
						<textarea id="after_subcribe_text" name="after_subcribe_text" class="us-input"/><?php echo esc_textarea($after_subcribe_text); ?></textarea> 
					</div>
				</div>
				<div class="field-row">
					<div class="field-label"> <?php _e('Button Text', 'ultimate-subscribe'); ?> </div>
					<div class="field-data">
						<input type="text" value="<?php echo esc_attr($button_label); ?>" id="button_label" name="button_label" class="us-input"/>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Save meta box data.
	 *
	 * @param int $post_id
	 * @param WP_Post $post
	 */
	public static function save( $post_id, $post ) {
		$settings['list_storege']			= sanitize_text_field($_POST['list_storege']);
		$settings['list_id']				= sanitize_text_field($_POST['list_id']);
		$form_text['heading']				= sanitize_text_field($_POST['heading']);
		$form_text['sub_heading']			= sanitize_text_field($_POST['sub_heading']);
		$form_text['descricption']			= sanitize_text_field($_POST['descricption']);
		$form_text['after_subcribe_text']	= sanitize_text_field($_POST['after_subcribe_text']);
		$form_text['button_label']			= sanitize_text_field($_POST['button_label']);
		
		update_post_meta( $post_id, 'ultimate_subscribe_form_settings', $settings);
		update_post_meta( $post_id, 'ultimate_subscribe_form_text', $form_text);
	}
}
