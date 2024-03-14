<?php
/**
 * Represents the view for the administration dashboard.
 *
 * @package   wp-editor-comments-plus
 * @author    Neo Snc <neosnc1@gmail.com>
 * @license   GPL-2.0+
 * @link      https://wordpress.org/plugins/wp-editor-comments-plus/
 * @copyright 3-22-2015 Neo Snc
 */
?>
<div class="wrap">

	<h2><?php echo $this->plugin_name ?> Settings</h2>

	<div class="wpecp-settings">
		<div class="wpecp-option">
			<fieldset class="comment-editing">
				<?php
					$nonce = wp_create_nonce( wpecp_ajax_editing_enabled );
					$editing_option = get_option( wpecp_ajax_editing_enabled );
					$editing_option = ( $editing_option === 'off' ) ? 'off' : 'on';
				?>
				<legend><span class="dashicons dashicons-welcome-write-blog"></span> Comment Editing</legend>
				<p>Edit comments for logged in users</p>
				<div class="editing-control">
					<label for="editing"><?php if ( $editing_option == 'on' ) { ?>Enabled<?php } else { ?>Disabled<?php } ?></label>
					<input name="editing" type="checkbox" <?php if ( $editing_option == 'on' ) { ?>checked="checked"<?php } ?> data-wpecp-nc="<?php echo esc_attr( $nonce ) ?>" />
				</div>
			</fieldset>
			<fieldset class="comment-expiration">
				<?php
					$nonce = wp_create_nonce( wpecp_ajax_editing_expiration );
					$expiration_option = get_option( wpecp_ajax_editing_expiration );
					$expiration_option = ( intval( $expiration_option ) > 0 ) ? $expiration_option : 0;
					$dtF = new DateTime( "@0" );
    			$dtT = new DateTime( "@$expiration_option" );
					$expirations = $dtF->diff( $dtT );
				?>
				<legend><span class="dashicons dashicons-clock"></span> Comment Editing Period</legend>
				<div class="confirmed">
					<span class="dashicons dashicons-yes"></span>
					<span class="message"></span>
				</div>
				<p>Duration to allow comments to be edited. Leave all fields at 0 to always allow editing.</p>
				<div class="expiration-control" data-wpecp-nc="<?php echo $nonce ?>">
					<label for="days" class="days">Days
						<input name="days" value="<?php echo $expirations->format('%a'); ?>">
					</label>
					<label for="hours" class="hours">Hours
						<input name="hours" value="<?php echo $expirations->format('%h'); ?>">
					</label>
					<label for="minutes" class="minutes">Minutes
						<input name="minutes" value="<?php echo $expirations->format('%i'); ?>">
					</label>
					<label for="seconds" class="seconds">Seconds
						<input name="seconds" value="<?php echo $expirations->format('%s'); ?>">
					</label>
				</div>
			</fieldset>
		</div>
		<div class="wpecp-option">
			<fieldset class="custom-toolbars">
				<?php
					$nonce = wp_create_nonce( wpecp_ajax_custom_toolbars );
					$wpecp_toolbar1 = get_option( wpecp_ajax_custom_toolbars .'_toolbar1' );
					$wpecp_toolbar2 = get_option( wpecp_ajax_custom_toolbars .'_toolbar2' );
					$wpecp_toolbar3 = get_option( wpecp_ajax_custom_toolbars .'_toolbar3' );
					$wpecp_toolbar4 = get_option( wpecp_ajax_custom_toolbars .'_toolbar4' );
				?>
				<legend><span class="dashicons dashicons-editor-kitchensink"></span> Customize TinyMCE Toolbar Buttons</legend>
				<p>Configure toolbar row buttons in TinyMCE for comments. Leave blank for default layout. Type none to hide any toolbar.</p>

				<div class="box" data-wpecp-nc="<?php echo $nonce ?>">
					<div class="confirmed">
						<span class="dashicons"></span>
						<span class="message"></span>
					</div>
					<label><span>Toolbar row 1</span> <input type="text" placeholder="bold italic strikethrough bullist numlist blockquote hr alignleft aligncenter alignright image link unlink wp_more spellchecker wp_adv" value="<?php echo esc_attr( $wpecp_toolbar1 ) ?>" data-wpecp-field="_toolbar1"></label>
					<label><span>Toolbar row 2</span> <input type="text" placeholder="formatselect underline alignjustify forecolor pastetext removeformat charmap outdent indent undo redo wp_help" value="<?php echo esc_attr( $wpecp_toolbar2 ) ?>" data-wpecp-field="_toolbar2"></label>
					<label><span>Toolbar row 3</span> <input type="text" placeholder="fontselect fontsizeselect" value="<?php echo esc_attr( $wpecp_toolbar3 ) ?>" data-wpecp-field="_toolbar3"></label>
					<label><span>Toolbar row 4</span> <input type="text" placeholder="" value="<?php echo esc_attr( $wpecp_toolbar4 ) ?>" data-wpecp-field="_toolbar4"></label>
				</div>
			</fieldset>
		</div>
		<div class="wpecp-option">
			<fieldset class="custom-classes">
				<?php
					$nonce = wp_create_nonce( wpecp_ajax_custom_classes );
					$wpecp_classes_all = get_option( wpecp_ajax_custom_classes . '_all' );
					$wpecp_classes_reply = get_option( wpecp_ajax_custom_classes . '_reply' );
					$wpecp_classes_edit = get_option( wpecp_ajax_custom_classes . '_edit' );
					$wpecp_classes_submit = get_option( wpecp_ajax_custom_classes . '_submit' );
					$wpecp_classes_cancel = get_option( wpecp_ajax_custom_classes . '_cancel' );
				?>
				<legend><span class="dashicons dashicons-media-code"></span> Custom CSS</legend>
				<p>Add custom CSS classes to TinyMCE Comments Plus buttons</p>

				<div class="box" data-wpecp-nc="<?php echo $nonce ?>">
					<div class="confirmed">
						<span class="dashicons dashicons-yes"></span>
						<span class="message"></span>
					</div>
					<label><span>All Buttons</span> <input type="text" placeholder="wpecp-button" value="<?php echo esc_attr( $wpecp_classes_all ); ?>" data-wpecp-field="_all"></label>
					<label><span>WordPress Reply Button</span> <input type="text" placeholder="wpecp-reply-comment" value="<?php echo esc_attr( $wpecp_classes_reply ); ?>" data-wpecp-field="_reply"></label>
					<label><span>Edit Button</span> <input type="text" placeholder="wpecp-edit" value="<?php echo esc_attr( $wpecp_classes_edit ); ?>" data-wpecp-field="_edit"></label>
					<label><span>Submit Edit Button</span> <input type="text" placeholder="wpecp-submit-edit" value="<?php echo esc_attr( $wpecp_classes_submit ); ?>" data-wpecp-field="_submit"></label>
					<label><span>Cancel Edit Button</span> <input type="text" placeholder="wpecp-cancel-edit" value="<?php echo esc_attr( $wpecp_classes_cancel ); ?>" data-wpecp-field="_cancel"></label>
				</div>

			</fieldset>
			<fieldset class="wordpress-ids">
				<?php
					$nonce = wp_create_nonce( wpecp_ajax_wordpress_ids );
					$wp_id_comments = get_option( wpecp_ajax_wordpress_ids .'_comments' );
					$wp_id_respond = get_option( wpecp_ajax_wordpress_ids .'_respond' );
					$wp_id_comment_form = get_option( wpecp_ajax_wordpress_ids .'_comment_form' );
					$wp_id_comment_textarea = get_option( wpecp_ajax_wordpress_ids .'_comment_textarea' );
					$wp_id_reply = get_option( wpecp_ajax_wordpress_ids .'_reply' );
					$wp_id_cancel = get_option( wpecp_ajax_wordpress_ids .'_cancel' );
					$wp_id_submit = get_option( wpecp_ajax_wordpress_ids .'_submit' );
				?>
				<legend><span class="dashicons dashicons-media-code"></span> WordPress IDs &amp; Classes</legend>
				<p>Some themes may use different element IDs or classes in comments. Leave blank for WordPress defaults.                                                                                                                                              </p>

				<div class="box" data-wpecp-nc="<?php echo $nonce ?>">
					<div class="confirmed">
						<span class="dashicons"></span>
						<span class="message"></span>
					</div>
					<label><span>Comments List</span> <input type="text" placeholder="#comments" value="<?php echo esc_attr( $wp_id_comments ); ?>" data-wpecp-field="_comments"></label>
					<!-- <label><span>Comment ID Prefix</span> <input type="text" placeholder="" /></label>
					<label><span>Comment</span> <input type="text" placeholder="" /></label> -->
					<label><span>Respond</span> <input type="text" placeholder="#respond" value="<?php echo esc_attr( $wp_id_respond ); ?>" data-wpecp-field="_respond"></label>
					<label><span>Comment Form</span> <input type="text" placeholder="#commentform" value="<?php echo esc_attr( $wp_id_comment_form ); ?>" data-wpecp-field="_comment_form"></label>
					<label><span>Comment Textarea</span> <input type="text" placeholder="#comment" value="<?php echo esc_attr( $wp_id_comment_textarea ); ?>" data-wpecp-field="_comment_textarea"></label>
					<label><span>Comment Reply Link</span> <input type="text" placeholder=".comment-reply-link" value="<?php echo esc_attr( $wp_id_reply ); ?>" data-wpecp-field="_reply"></label>
					<label><span>Cancel Comment Reply Link</span> <input type="text" placeholder="#cancel-comment-reply-link" value="<?php echo esc_attr( $wp_id_cancel ); ?>" data-wpecp-field="_cancel"></label>
					<label><span>Submit Comment</span> <input type="text" placeholder="#submit" value="<?php echo esc_attr( $wp_id_submit ); ?>" data-wpecp-field="_submit"></label>
				</div>
			</fieldset>
		</div>
	</div>

</div>
