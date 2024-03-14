<?php



class CM_ajax_shortcode {



	/**
	 * Constructor
	 */
	function __construct() {

			add_action( 'init', array( $this, 'init' ), 1 );
			add_action( 'init', array( $this, 'ajax_receiver' ), 2 );

	}



	/**
     * Actions to be run on WordPress' init() hook
     */
	function init() {

		add_shortcode( 'cm_ajax_subscribe', array( $this, 'cm_ajax_subscribe' ) );

		if ( current_user_can( 'edit_posts' ) && get_user_option( 'rich_editing' ) ) {
			add_filter( 'mce_buttons', array( $this, 'register_button' ) );
			add_filter( 'mce_external_plugins', array($this, 'add_plugin' ) );
		}

	}



	/**
	 * Add tinymce button to toolbar
	 */
	function register_button( $buttons ) {

		array_push( $buttons, 'cm_ajax_shortcode' );
		return $buttons;

	}



	/**
	 * Register tinymce plugin
	 */
	function add_plugin( $plugin_array ) {
		$plugin_array['cm_ajax_shortcode'] = plugins_url( '/js/cm_ajax_shortcode.js', __FILE__ );
		return $plugin_array;
	}



	/**
	 * Handle Ajax requests
	 * Used to render the tinymce popup, handle the ajax shortcode ID creation from the popup,
	 * and actually the frontend ajax form POSTs
	 *
	 */
	function ajax_receiver() {

		if ( ! isset ( $_REQUEST['cm_ajax_shortcode'] ) )
			return;

		switch ( $_REQUEST['cm_ajax_shortcode_action'] ) {
			case 'renderpopup':
				$this->render_tinymce_popup();
				break;
			case 'generateshortcode':
				$this->generate_shortcode();
				break;
			case 'subscribe':
				$this->subscribe();
				break;

			default:
				break;
		}

		if ( isset( $_REQUEST['cm_ajax_response'] ) && $_REQUEST['cm_ajax_response'] == 'ajax' ) {
			die();
		}

	}



	/**
	 * Render the tinymce popup
	 *
	 */
	function render_tinymce_popup() {

		if ( ! current_user_can( 'edit_posts' ) )
			return;

		include_once('tinymce_popup.php');

	}



	/**
	 * Generate a shortcode linked to the chosen API settings
	 *
	 */
	function generate_shortcode() {

		if ( ! current_user_can( 'edit_posts' ) )
			return;

		if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'cm_ajax_generate_shortcode' ) )
			return;

		$current_shortcodes = get_option( 'cm_ajax_shortcodes' );

		$matched_shortcode = FALSE;

		if ( is_array( $current_shortcodes ) && count( $current_shortcodes ) ) {
			// Try and find it in the existing shortcodes
			foreach ( $current_shortcodes as $shortcode_id => $shortcode ) {
				if ( $shortcode['account_api_key'] == $_POST['cm_ajax_tinymce_subscriber_account_api_key']
				  && $shortcode['list_api_key'] == $_POST['cm_ajax_tinymce_subscriber_list_api_key']
				  && $shortcode['show_name_field'] == $_POST['cm_ajax_tinymce_subscriber_show_name_field'] ) {
					// Support for adding list names to the array if the shortcode was created
					// with plugin version < 0.5
					if ( ! isset ( $shortcode['list_name'] ) || $shortcode['list_name'] == 'Unknown List' || empty($shortcode['list_name']) ) {
						$current_shortcodes[$shortcode_id]['list_name'] = $this->get_list_name( $shortcode['account_api_key'], $shortcode['list_api_key'] );
						update_option( 'cm_ajax_shortcodes', $current_shortcodes );
					}
					// Found an existing shortcode - return it
					echo $shortcode_id;
					return;
				}
			}
		}

		// Create a new shortcode id

		$current_shortcodes[] = array(
			'account_api_key' => $_POST['cm_ajax_tinymce_subscriber_account_api_key'],
			'list_api_key' => $_POST['cm_ajax_tinymce_subscriber_list_api_key'],
			'list_name' => $this->get_list_name( $_POST['cm_ajax_tinymce_subscriber_account_api_key'], $_POST['cm_ajax_tinymce_subscriber_list_api_key'] )
		);

		$matched_shortcode = count( $current_shortcodes ) - 1 ;

		if ( isset ( $_POST['cm_ajax_tinymce_subscriber_show_name_field']) ) {
			$current_shortcodes[$matched_shortcode]['show_name_field'] = $_POST['cm_ajax_tinymce_subscriber_show_name_field'] ;
		}


		update_option( 'cm_ajax_shortcodes', $current_shortcodes );

		echo $matched_shortcode;

		return;

	}



	/**
	 * Parse the shortcode, and render the form
	 *
	 */
	function cm_ajax_subscribe( $atts, $content=null, $code="" ) {
		$args = shortcode_atts( array( 'id' => '' ), $atts );
		$shortcode_id = $args['id'];
		$shortcode_options = get_option( 'cm_ajax_shortcodes' );

		if ( ! isset ( $shortcode_options[$shortcode_id] ) )
			return ' ';

		$settings = $shortcode_options[$shortcode_id];

		ob_start();

		if ( isset( $this->result ) ) {
			if ( $this->result ) {
				$success_style = '';
				$failed_style = 'style="display: none;"';
				$submit_style = 'style="display: none;"';
			} else {
				$success_style = 'style="display: none;"';
				$failed_style = '';
				$submit_style = '';
			}
		} else {
				$success_style = 'style="display: none;"';
				$failed_style = 'style="display: none;"';
				$submit_style = '';
		}

		// Main signup form
		?>
		<form method="POST" class="cm_ajax_shortcode_form" id="cm_ajax_shortcode_<?php echo $shortcode_id; ?>">
		<input type="hidden" name="cm_ajax_shortcode_action" value="subscribe">
		<input type="hidden" name="cm_ajax_shortcode" value="<?php echo $shortcode_id; ?>">
		<?php if ( $settings['show_name_field'] != '' &&
				   $settings['show_name_field'] != null ) :  ?>
				<p><label for="cm-ajax-name"><?php _e('Name:', 'cm_ajax'); ?></label>
				<input class="widefat" id="cm-ajax-name" name="cm-ajax-name" type="text" /></p>
		<?php endif; ?>

		<p><label for="cm-ajax-email"><?php _e('Email:', 'cm_ajax'); ?></label>
		<input class="widefat" id="cm-ajax-email" name="cm-ajax-email" type="text" /></p>

		<p style="width: 100%; text-align: center;">
		<span <?php echo $success_style; ?> class="cm_ajax_success"><?php _e("Great news, we've signed you up.", 'cm_ajax'); ?></span>
		<span <?php echo $failed_style; ?> class="cm_ajax_failed"><?php _e("Sorry, we weren't able to sign you up. Please check your details, and try again.", 'cm_ajax'); ?><br/><br/></span>
		<span style="display:none;" class="cm_ajax_loading"><img alt="Loading..." src="<?php echo plugins_url( '/ajax-loading.gif', __FILE__ ); ?>"></span>
		<input <?php echo $submit_style; ?> type="submit" name="cm-ajax-submit" value="<?php _e('Register', 'cm_ajax'); ?>">
		</p>
		</form>
		<script type="text/javascript">
			jQuery(document).ready(function() {
				jQuery('form#cm_ajax_shortcode_<?php echo $shortcode_id; ?> input:submit').click(function() {

					jQuery('form#cm_ajax_shortcode_<?php echo $shortcode_id; ?> input:submit').hide();
					jQuery('form#cm_ajax_shortcode_<?php echo $shortcode_id; ?> .cm_ajax_success').hide();
					jQuery('form#cm_ajax_shortcode_<?php echo $shortcode_id; ?> .cm_ajax_failed').hide();
					jQuery('form#cm_ajax_shortcode_<?php echo $shortcode_id; ?> .cm_ajax_loading').show();
					jQuery.ajax(
						{ type: 'POST',
						  data: jQuery('form#cm_ajax_shortcode_<?php echo $shortcode_id; ?>').serialize()+'&cm_ajax_response=ajax',
						  success: function(data) {
										jQuery('form#cm_ajax_shortcode_<?php echo $shortcode_id; ?> .cm_ajax_loading').hide();
										if (data == 'SUCCESS') {
											jQuery('form#cm_ajax_shortcode_<?php echo $shortcode_id; ?> .cm_ajax_success').show();
										} else {
											jQuery('form#cm_ajax_shortcode_<?php echo $shortcode_id; ?> input:submit').show();
											jQuery('form#cm_ajax_shortcode_<?php echo $shortcode_id; ?> .cm_ajax_failed').show();
										}
									}
						}
					);
					return false;
					});
				});
		</script>

		<?php

		$contents = ob_get_contents();
		ob_end_clean();

		return $contents;
	}




	/**
	 * Subscribe someone to a list
	 *
	 */
	function subscribe() {

		if ( ! isset ( $_REQUEST['cm_ajax_shortcode'] ) )
			return 'FAILED';

		$shortcode_id = $_REQUEST['cm_ajax_shortcode'];
		$shortcode_options = get_option( 'cm_ajax_shortcodes' ) ;

		if ( ! isset( $shortcode_options[$shortcode_id] ) ) {
			return 'FAILED\nNo Settings';
		} else {
			$settings = $shortcode_options[$shortcode_id];
		}

		$cm = new CS_REST_Subscribers( $settings['list_api_key'], $settings['account_api_key'] );

		$record = array(
			'EmailAddress' => $_POST['cm-ajax-email'],
			'Resubscribe' => true,
		);

		if ( $settings['show_name_field'] ) {
			$record['Name'] = $_POST['cm-ajax-name'];
		}

		$result = $cm->add( $record );

		if ( isset( $_POST['cm_ajax_response'] ) && $_POST['cm_ajax_response'] == 'ajax' ) {
			if ( $result->was_successful() ) {
				echo 'SUCCESS';
			} else {
				echo 'FAILED';
				echo ( $result->response->Code ) . ': ';
				echo ( $result->response->Message );
			}
		} else {
			$this->result = $result->was_successful();
		}
		return;
	}



	function get_list_name( $account_api_key, $list_api_key ) {

		$cm = new CS_REST_Lists( $list_api_key, $account_api_key );

		$result = $cm->get();

		if ( $result->was_successful() ) {
			return $result->response->Title;
		} else {
			return __( 'Unknown List', 'cm_ajax' );
		}

	}



}

$CM_ajax_shortcode = new CM_ajax_shortcode();
