<?php



class CM_ajax_widget extends WP_Widget {


	/**
	 * Constructor
	 */
	function __construct() {

		$widget_ops = array(
			'classname'   => 'widget_cm_ajax',
			'description' => __( 'Ajax signup form for Campaign Monitor lists', 'cm_ajax' )
		) ;
		parent::__construct( 'widget_cm_ajax', __( 'Newsletter Signup', 'cm_ajax' ), $widget_ops );
		add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
		add_action( 'init', array( $this, 'ajax_receiver' ) );
		add_action( 'init', array( $this, 'init_actions' ) );
	}

	/**
	 * Init actions
	 *
	 */
	function init_actions() {
		if ( ! is_admin() ) {
			wp_enqueue_script('jquery');
		}
	}

	/**
	 * Handle Ajax requests
	 *
	 */
	function ajax_receiver() {
		if ( ! isset ( $_POST['cm_ajax_action'] ) )
			return;
		switch ( $_POST['cm_ajax_action'] ) {
			case 'subscribe':
				$this->subscribe();
				break;
			default:
				break;
		}
		if( isset ( $_POST['cm_ajax_response'] ) && $_POST['cm_ajax_response'] == 'ajax' ) {
			die();
		}
	}

	/**
	 * Subscribe someone to a list
	 *
	 */
	function subscribe() {

		$settings = get_option ( $this->option_name );
		if ( isset ( $settings[$_POST['cm_ajax_widget_id']] ) ) {
			$settings = $settings[$_POST['cm_ajax_widget_id']];
		} else {
			return 'FAILED\nNo Settings';
		}
		$cm = new CS_REST_Subscribers($settings['list_api_key'], $settings['account_api_key']);
		$record = Array (
			'EmailAddress' => $_POST['cm-ajax-email'],
			'Resubscribe' => true
		);
		if ($settings['show_name_field']) {
			$record['Name'] = $_POST['cm-ajax-name'];
		}
		$result = $cm->add ( $record );
		if( isset ( $_POST['cm_ajax_response'] ) && $_POST['cm_ajax_response'] == 'ajax' ) {
			if ($result->was_successful()) {
				echo 'SUCCESS';
			} else {
				echo 'FAILED\n';
				echo ($result->response->Code).': ';
				echo ($result->response->Message);
			}
		} else {
			$this->result = $result->was_successful();
		}
		return;
	}

	/**
	 * Output the widget content
	 *
	 * @param unknown $args
	 * @param unknown $instance
	 */
	function widget($args, $instance) {

		$cache = wp_cache_get('widget_cm_ajax', 'widget');
		if ( !is_array($cache) )
			$cache = array();
		if ( isset($cache[$args['widget_id']]) ) {
			echo $cache[$args['widget_id']];
			return;
		}
		ob_start();
		extract($args);
		echo $before_widget;
		$title = apply_filters('widget_title', empty ( $instance['title'] ) ? __('Newsletter Signup', 'cm_ajax') : $instance['title'], $instance, $this->id_base);
		echo $before_title . $title . $after_title;
		if ( isset ( $instance['pretext'] ) ) {
			echo $instance['pretext'];
		}
		if (isset($this->result)) {
			if ($this->result) {
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
		<form method="POST" class="cm_ajax_widget_form" id="cm_ajax_form_<?php echo $this->number; ?>">
		<input type="hidden" name="cm_ajax_action" value="subscribe">
		<input type="hidden" name="cm_ajax_widget_id" value="<?php echo $this->number; ?>">
		<?php if (!isset($instance['show_name_field']) || $instance['show_name_field']) :  ?>
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
				jQuery('form#cm_ajax_form_<?php echo $this->number; ?> input:submit').click(function() {

					jQuery('form#cm_ajax_form_<?php echo $this->number; ?> input:submit').hide();
					jQuery('form#cm_ajax_form_<?php echo $this->number; ?> .cm_ajax_success').hide();
					jQuery('form#cm_ajax_form_<?php echo $this->number; ?> .cm_ajax_failed').hide();
					jQuery('form#cm_ajax_form_<?php echo $this->number; ?> .cm_ajax_loading').show();
					jQuery.ajax(
						{ type: 'POST',
						  data: jQuery('form#cm_ajax_form_<?php echo $this->number; ?>').serialize()+'&cm_ajax_response=ajax',
						  success: function(data) {
										jQuery('form#cm_ajax_form_<?php echo $this->number; ?> .cm_ajax_loading').hide();
										if (data == 'SUCCESS') {
											jQuery('form#cm_ajax_form_<?php echo $this->number; ?> .cm_ajax_success').show();
										} else {
											jQuery('form#cm_ajax_form_<?php echo $this->number; ?> input:submit').show();
											jQuery('form#cm_ajax_form_<?php echo $this->number; ?> .cm_ajax_failed').show();
										}
									}
						}
					);
					return false;
					});
				});
		</script>

		<?php
		echo $after_widget;

		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set('widget_cm_ajax', $cache, 'widget');
	}

	/**
	 * Save the widget settings
	 *
	 * @param unknown $new_instance
	 * @param unknown $old_instance
	 * @return unknown
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['pretext'] = $new_instance['pretext'];
		$instance['account_api_key'] = strip_tags($new_instance['account_api_key']);
		$instance['list_api_key'] = strip_tags($new_instance['list_api_key']);
		$instance['show_name_field'] = (bool) $new_instance['show_name_field'];
		$this->flush_widget_cache();
		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['widget_cm_ajax']) )
			delete_option('widget_cm_ajax');
		return $instance;
	}

	/**
	 * Empty the widget cache
	 */
	function flush_widget_cache() {
		wp_cache_delete('widget_cm_ajax', 'widget');
	}

	/**
	 * Widget configuration form
	 *
	 * @param unknown $instance
	 */
	function form( $instance ) {
		$title           = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$pretext         = isset($instance['pretext']) ? esc_attr($instance['pretext']) : '';
		$account_api_key = isset($instance['account_api_key']) ? esc_attr($instance['account_api_key']) : '';
		$list_api_key    = isset($instance['list_api_key']) ? esc_attr($instance['list_api_key']) : '';
		$show_name_field = isset($instance['show_name_field']) ? esc_attr($instance['show_name_field']) : TRUE;
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'cm_ajax'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
		<p><label for="<?php echo $this->get_field_id('pretext'); ?>"><?php _e('Text before form:', 'cm_ajax'); ?></label>
		<textarea class="widefat" id="<?php echo $this->get_field_id('pretext'); ?>" name="<?php echo $this->get_field_name('pretext'); ?>"><?php echo $pretext; ?></textarea></p>
		<p><label for="<?php echo $this->get_field_id('account_api_key'); ?>"><?php _e('Account API Key:', 'cm_ajax'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('account_api_key'); ?>" name="<?php echo $this->get_field_name('account_api_key'); ?>" type="text" value="<?php echo $account_api_key; ?>" /></p>
		<p><label for="<?php echo $this->get_field_id('list_api_key'); ?>"><?php _e('List API Key', 'cm_ajax'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('list_api_key'); ?>" name="<?php echo $this->get_field_name('list_api_key'); ?>" type="text" value="<?php echo $list_api_key; ?>" /></p>
		<p><label for="<?php echo $this->get_field_id('show_name_field'); ?>"><?php _e('Show name field', 'cm_ajax'); ?></label>
		<input id="<?php echo $this->get_field_id('show_name_field'); ?>" name="<?php echo $this->get_field_name('show_name_field'); ?>" type="checkbox" <?php echo $show_name_field ? 'checked=checked' : ''; ?> /></p>
		<?php
	}
}

/**
 * Register the widget
 */
function register_cm_ajax_widget() {
	register_widget( 'CM_ajax_widget' );
}
add_action( 'widgets_init', 'register_cm_ajax_widget', 1 );
