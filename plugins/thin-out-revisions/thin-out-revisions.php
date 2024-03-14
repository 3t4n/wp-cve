<?php
/*
Plugin Name: Thin Out Revisions
Plugin URI: http://en.hetarena.com/thin-out-revisions
Description: A plugin for better revision management. Enables flexible management for you.
Version: 1.8.3
Author: Hirokazu Matsui (blogger323)
Author URI: http://en.hetarena.com/
Text Domain: thin-out-revisions
Domain Path: /languages
License: GPLv2
*/

if ( ! class_exists( 'SimplePie' ) ) :
  require ABSPATH . WPINC . '/class-simplepie.php';
endif;

class HM_TOR_Plugin_Loader {
	const VERSION        = '1.8.3';
	const OPTION_VERSION = '1.7';
	const OPTION_KEY     = 'hm_tor_options';
	const I18N_DOMAIN    = 'thin-out-revisions';
	const PREFIX         = 'hm_tor_';

	public $page = ''; // 'revision.php' or 'post.php'

	static $instance = false;

	function __construct() {
		register_activation_hook( __FILE__, array( &$this, 'plugin_activation' ) );
		register_deactivation_hook( __FILE__, array( &$this, 'plugin_deactivation' ) );
		add_action( 'init',                   array( &$this, 'init' ) );
		add_action( 'plugins_loaded',         array( &$this, 'plugins_loaded' ) );
		add_action( 'admin_enqueue_scripts',  array( &$this, 'admin_enqueue_scripts' ), 20 );
		add_action( 'wp_ajax_hm_tor_do_ajax', array( &$this, 'hm_tor_do_ajax' ) );
		add_action( 'wp_ajax_hm_tor_do_ajax_start_delete_old_revisions', array( &$this, 'do_ajax_start_delete_old_revisions' ) );
		add_action( 'post_updated',           array( &$this, 'post_updated' ), 20, 3 );
		add_action( 'transition_post_status', array( &$this, 'transition_post_status' ), 10, 3 );

		add_action( 'hm_tor_cron_hook', array( &$this, 'cron_hook' ) );

		add_action( 'admin_init', array( &$this, 'admin_init' ) );
		add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
		add_action( 'admin_head', array( &$this, 'admin_head' ), 20 );

        if ( self::get_hm_tor_option('history_note') === 'on' ) {
            add_filter( 'the_content', array( &$this, 'the_content' ), intval( self::get_hm_tor_option( 'history_note_priority' ) ) );
        }

	}

	public static function getInstance() {
		if ( !self::$instance )
			self::$instance = new self;
		return self::$instance;
	}

	function init() {
	}

	function plugins_loaded() {
		load_plugin_textdomain( self::I18N_DOMAIN, false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	public static function plugin_activation( $network_wide ) {
		if ( version_compare( get_bloginfo( 'version' ), '3.6', '<' ) ) {
			deactivate_plugins( basename( __FILE__ ) ); // Deactivate this plugin
			return;
		}

		$option = self::get_hm_tor_option();
		if ( $option['schedule_enabled'] == 'enabled' && preg_match( '/\A([0-9]{1,2}):([0-9]{2})\z/', $option['del_at'], $matches )
				&& filter_var( $option['del_older_than'], FILTER_VALIDATE_INT ) !== FALSE ) {
			wp_schedule_event( self::get_timestamp_for_cron( $matches[1], $matches[2] ), 'daily', 'hm_tor_cron_hook', array( intval( $option['del_older_than'] ) ) );
		}
	}

	public static function plugin_deactivation() {
		$prev =self::get_hm_tor_option();
		$timestamp = wp_next_scheduled( 'hm_tor_cron_hook',  array( intval( $prev['del_older_than'] ) ) );
		if ( $timestamp !== false ) {
			wp_unschedule_event( $timestamp, 'hm_tor_cron_hook', array( intval( $prev['del_older_than'] ) ) );
		}

		// TODO: change to call wp_clear_scheduled_hook
	}

	function admin_enqueue_scripts() {
		// 'admin_enqueue_scripts'
		//trigger_error('enqueueing script');
		global $post, $pagenow;
		$latest_revision = 0;

		if ( $post && $post->ID ) {
			$revisions = wp_get_post_revisions( $post->ID );

			// COPY FROM CORE
			if ( ! empty( $revisions ) ) {
				// grab the last revision, but not an autosave (from wp_save_post_revision in WP 3.6)
				foreach ( $revisions as $revision ) {
					if ( false !== strpos( $revision->post_name, "{$revision->post_parent}-revision" ) ) {
						$latest_revision = $revision->ID;
						break;
					}
				}
			}
		}


		$params = array(
			'nonce'                    => wp_create_nonce( self::PREFIX . "nonce" ),
			'ajaxurl'                  => admin_url( 'admin-ajax.php' ),
			'latest_revision'          => $latest_revision,

			'msg_thinout_comfirmation' => esc_attr( __( 'Do you really want to remove this?', self::I18N_DOMAIN ) ),
			'msg_remove_completed'     => esc_attr( __( 'The revision(s) removed.', self::I18N_DOMAIN ) ),
			'msg_ajax_error'           => esc_attr( __( 'Error in communication with server', self::I18N_DOMAIN ) ),
			'msg_nothing_to_remove'    => esc_attr( __( 'Nothing to remove.', self::I18N_DOMAIN ) ),
			'msg_thin_out'             => esc_attr( __( 'Remove revisions between two revisions above', self::I18N_DOMAIN ) ),
			'msg_processing'           => esc_attr( __( 'Processing...', self::I18N_DOMAIN ) ),
			'msg_include_from'         => esc_attr( __( "Include the 'From' revision", self::I18N_DOMAIN ) ),
			'msg_delete'               => esc_attr( __( 'Delete' ) ),
			'msg_deleted'              => esc_attr( __( 'Deleted' ) )
		);

		if ( $pagenow === 'revision.php' || $pagenow === 'post.php' ) {
			// loading in footer
			wp_enqueue_script( 'thin-out-revisions', plugins_url( '/js/thin-out-revisions.js', __FILE__ ),
                $pagenow === 'revision.php' ? array('jquery-ui-position', 'revisions') : array('jquery-ui-position'), false, true );
			wp_localize_script( 'thin-out-revisions', self::PREFIX . 'params', $params );
		}

	}


	function hm_tor_do_ajax() {

		$posts = explode( "-", $_REQUEST['posts'] );

		if ( check_ajax_referer( self::PREFIX . "nonce", 'security', false ) ) {
			$deleted = array();
			foreach ( $posts as $revid ) {
				// Without the 'get_post' check, WP makes warnings.
				$post = get_post( $revid );

				if ( $post ) {
					$post_type = get_post_type( $post->post_parent );
					$post_type_object = get_post_type_object( $post_type );

					if ( ($post_type_object->capability_type == 'post' && current_user_can( 'edit_post', $revid ) )
							|| ( $post_type_object->capability_type == 'page' && current_user_can( 'edit_page', $revid ) )
					) {
						if ( wp_delete_post_revision( $revid ) ) {
							array_push( $deleted, $revid );
						}
					}
				}
			} // foreach
			echo json_encode( array(
				"result"  => "success",
				"msg"     => sprintf( _n( '%s revision removed.', '%s revisions removed.', count( $deleted ), self::I18N_DOMAIN ), count( $deleted ) ),
				"deleted" => $deleted
			) );
		}
		else {
			echo json_encode( array(
				"result" => "error",
				"msg"    => __( "Wrong session. Unable to process.", self::I18N_DOMAIN )
			) );
		}

		die();
	}

	function has_copy_revision() {
		return true;
	}

	function post_updated( $post_id, $post, $post_before ) {
		// delete_revisions_on_1st_publishment
		if ( $this->get_hm_tor_option( 'del_on_publish' ) == 'on' &&
				$post->post_status == 'publish' &&
				get_post_meta( $post_id, '_hm_tor_status', true ) != 'published'
		) {

			// do nothing if previous status is other than 'draft'
			if ( $post_before->post_status == 'draft' ) {

				$revisions = wp_get_post_revisions( $post_id );

				// don't remove the latest.
				$latest = $this->has_copy_revision();

				if ( ! empty( $revisions ) ) {

					foreach ( $revisions as $rev ) {
						if ( false !== strpos( $rev->post_name, "{$rev->post_parent}-revision" ) ) {
							if ($latest) {
								$latest = false;
							}
							else {
								wp_delete_post_revision( $rev->ID );
							}
						}
					}
				}

			}
		}

		if ( $post->post_status == 'publish' ) {
			add_post_meta( $post_id, '_hm_tor_status', 'published', true );
		}
	}

	function transition_post_status( $new_status, $old_status, $post ) {
		// This function is called before post_updated in wp_insert_post.
		// So I can't mark the _hm_tor_status depending on $new_status.
		// All I can do is to mark it for future update when the status is changed from 'publish' to something.

		if ( $old_status == 'publish' ) {
			add_post_meta( $post->ID, '_hm_tor_status', 'published', true );
		}
	}

	function admin_init() {
		add_settings_section( 'hm_tor_main', 'Thin Out Revisions', array( &$this, 'main_section_text' ), 'hm_tor_option_page' );

		add_settings_field( 'hm_tor_del_on_publish', __( 'Delete all revisions on initial publication', self::I18N_DOMAIN ),
			array( &$this, 'settings_field_del_on_publish' ), 'hm_tor_option_page', 'hm_tor_main' );

		add_settings_field( 'hm_tor_delete_old_revisions', __( 'Delete revisions as old as or older than', self::I18N_DOMAIN ),
		  array( &$this, 'settings_field_delete_old_revisions' ), 'hm_tor_option_page', 'hm_tor_main' );

        add_settings_field( 'hm_tor_history_note', __( 'Show memos on posts', self::I18N_DOMAIN ),
          array( &$this, 'settings_field_history_note' ), 'hm_tor_option_page', 'hm_tor_main' );

		register_setting( 'hm_tor_option_group', 'hm_tor_options', array( &$this, 'validate_options' ) );
	}

	function admin_head() {
		// STYLE tag only for 3.6 or later
?>
<style>
.comparing-two-revisions .revisions-controls {
	height: 164px;
}
.comparing-two-revisions.pinned .revisions-controls {
	height: 148px;
}
.comparing-two-revisions .revisions-tooltip {
	bottom: 169px;
}
</style>
<?php

	}

    function the_content($text) {
        global $wpdb, $post;


        if ( (! is_single()) || is_front_page() ) {
            return $text;
        }

        $show_history = get_post_meta($post->ID, "_hm_tor_show_history", true);
        if ( $show_history == 'hide' || (self::get_hm_tor_option('default_action') == 'hide' && $show_history === '')) {
            return $text;
        }

        $foot = self::get_hm_tor_option( 'history_head' ) .
            '<dl class="hm-tor-memo-list">';


        $revisions = $wpdb->get_results($wpdb->prepare(
"SELECT tpm.meta_value, tp.post_date, tu.display_name
 FROM $wpdb->posts tp, $wpdb->postmeta tpm, $wpdb->users tu
 WHERE post_type = 'revision'
 AND tp.ID = tpm.post_id
 AND tp.post_author = tu.ID
 AND post_parent = %d
 AND meta_key = %s
 ORDER BY post_date DESC",  $post->ID , '_hm_tor_memo'
        ) );
        foreach ( $revisions as $revision ) {
            if (trim($revision->meta_value) !== '' && substr($revision->meta_value, 0, 1) !== '#') {
                $foot .= '<dt>' . mysql2date( get_option( 'date_format' ), $revision->post_date) . ' - ' . $revision->display_name . '</dt><dd>' . $revision->meta_value . "</dd>\n";
            }
        }
        $foot .= '</dl>';

        return $text . $foot;
    }

	function admin_notices() {
		global $post;
		$rev = wp_get_post_revisions( $post->ID );
		if ( post_type_supports( $post->post_type, 'revisions' ) && empty( $rev ) ) {
			echo "<div class='updated' style='padding: 0.6em 0.6em'>" .
					__( 'You should press update button without modification to make a copy revision. Or you will lose current content after update.', self::I18N_DOMAIN ) .
					" <a href='" . __("http://wordpress.org/plugins/thin-out-revisions/faq/", self::I18N_DOMAIN) . "' target='_blank'>" .
					__( "See detail...", self::I18N_DOMAIN ) . "</a>" .
					"</div>\n";
		}
	}

	public static function get_hm_tor_option( $key = NULL ) {
		$default_hm_tor_option = array(
			'version'        => self::OPTION_VERSION,
			'quick_edit'     => "off", // deprecated
			'bulk_edit'      => "off", // deprecated
			'del_on_publish' => "off",
			'del_older_than' => "90",
			'schedule_enabled' => 'disabled',
			'del_at'         => "3:00",

            'history_note' => 'off',
            'history_head' => __( '<hr><h3>History</h3>' , self::I18N_DOMAIN ),
            'history_note_priority' => '20',
            'default_action' => 'show',
		);

		// The get_option doesn't seem to merge retrieved values and default values.
		$options = array_merge( $default_hm_tor_option, (array) get_option( 'hm_tor_options', array() ) );
		return $key ? $options[$key] : $options;
	}

	function main_section_text() {
		// do nothing
	}

	function settings_field( $key, $text ) {
		$val = $this->get_hm_tor_option( $key );
		echo "<fieldset><legend class='screen-reader-text'><span>" . esc_html($text) . "</span></legend>\n";
		echo "<label title='enable'><input type='radio' name='hm_tor_options[" . esc_attr($key) . "]' value='on' " .
				( $val == "on" ? "checked='checked'" : "" ) .
				"/><span>On</span></label><br />\n";
		echo "<label title='disable'><input type='radio' name='hm_tor_options[" . esc_attr($key) . "]' value='off' " .
				( $val == "off" ? "checked='checked'" : "" ) .
				"/><span>Off</span></label><br />\n";
		echo "</fieldset>\n";
	}

	function settings_field_del_on_publish() {
		$this->settings_field( 'del_on_publish', __( 'Delete all revisions on initial publication', self::I18N_DOMAIN ) );
	}

    function settings_field_history_note() {

?>
        <fieldset>
            <legend class="screen-reader-text"><span><?php echo "Show notes"; ?></span></legend>
            <p>
                <label title='enable'><input type='radio' name='hm_tor_options[history_note]' value='on' <?php
                    echo ( $this->get_hm_tor_option( 'history_note' ) == "on" ? "checked='checked'" : "" );
                    ?>/><span>On</span></label>
                <label title='disable'><input type='radio' name='hm_tor_options[history_note]' value='off' style="margin-left: 10px" <?php
                    echo ( $this->get_hm_tor_option( 'history_note' ) == "off" ? "checked='checked'" : "" );
                    ?>/><span>Off</span></label>
            </p>
            <p>
                <?php echo __( 'Hook Priority', self::I18N_DOMAIN ); ?>
                <input class='small-text' id='hm_tor_history_note_priority' name='hm_tor_options[history_note_priority]' type='text' style="margin-left: 20px" value='<?php
                echo esc_attr( $this->get_hm_tor_option( 'history_note_priority' ) );
                ?>' />
            </p>
            <p>
                <?php echo __( 'Default Action', self::I18N_DOMAIN ) ?>
                <label title='show'><input type='radio' name='hm_tor_options[default_action]' value='show' style="margin-left: 20px" <?php
                    echo ( $this->get_hm_tor_option( 'default_action' ) == "show" ? "checked='checked'" : "" );
                    ?>/><span><?php echo __( 'Show', self::I18N_DOMAIN ); ?></span></label>
                <label title='hide'><input type='radio' name='hm_tor_options[default_action]' value='hide' style="margin-left: 10px" <?php
                    echo ( $this->get_hm_tor_option( 'default_action' ) == "hide" ? "checked='checked'" : "" );
                    ?>/><span><?php echo __( 'Hide', self::I18N_DOMAIN ); ?></span></label>
            </p>
            <p>
                <label for="history_head">
                    <?php echo __( 'Header for Notes', self::I18N_DOMAIN ); ?>
                </label>
            </p>
            <p>
                <textarea name="hm_tor_options[history_head]" rows="10" cols="50" id="history_head" class="large-text code"><?php
                    echo esc_html( $this->get_hm_tor_option( 'history_head' ) );
                ?></textarea>
            </p>
        </fieldset>
<?php

    }

	function settings_field_delete_old_revisions() {

		echo "<p><input class='small-text' id='hm_tor_del_older_than' name='hm_tor_options[del_older_than]' type='text' value='" . esc_attr( $this->get_hm_tor_option( 'del_older_than' ) ) . "' /> " . __( 'days', self::I18N_DOMAIN )  . "\n";

		echo '<input id="hm_tor_rm_now_button" class="button button-primary" style="margin: 0 10px 0 50px;" type="submit" value="' . __( 'Remove NOW', self::I18N_DOMAIN )  . '" /><span id="hm_tor_rm_now_msg"></span></p>';

		echo '<fieldset><legend class="screen-reader-text"><span>' . __('Run as scheduled task', self::I18N_DOMAIN) . '</span></legend>' .
	        '<label for="hm_tor_schedule_enabled"><input name="hm_tor_options[schedule_enabled]" type="checkbox" id="hm_tor_schedule_enabled" value="enabled" ' .
				  checked( $this->get_hm_tor_option('schedule_enabled'), 'enabled', false ) . '/> ' .
	        __('Run as daily task. Run every day at', self::I18N_DOMAIN) . "</label>\n";

		echo  " <input class='small-text' id='hm_tor_del_at' name='hm_tor_options[del_at]' type='text' value='" . esc_attr( $this->get_hm_tor_option( 'del_at' ) ) . "' />";

		// for debug
		if ( $this->get_hm_tor_option( 'schedule_enabled' ) == 'enabled' ) {
			$next = wp_next_scheduled("hm_tor_cron_hook", array( intval( $this->get_hm_tor_option( 'del_older_than' ) ) ) );
			$t = time();
			$diff = intval(($next - $t) / 60);
			$msg = sprintf( __( "The task will begin after %d min.", self::I18N_DOMAIN ), $diff );
			echo "<div>" .  $msg . " (gmt_offset = " . get_option( 'gmt_offset' ) . ")</div>";

		}
		echo "</fieldset>\n";
	}

	public static function get_timestamp_for_cron( $hour, $min ) {
		$now = time();
		$t = ceil( $now / 86400 ) * 86400 + ($hour - get_option( 'gmt_offset') )  * 3600 + $min * 60;

		while ( $now < $t - 86400) {
			$t -= 86400;
		}

		while ( $now > $t ) {
			$t += 86400;
		}
		return $t;
	}



	function validate_options( $input ) {
		$valid = array();
		$prev  = $this->get_hm_tor_option();
		$valid_conf_for_cron = true;

		// reset schedule
		$timestamp = wp_next_scheduled( 'hm_tor_cron_hook',  array( intval( $prev['del_older_than'] ) ) );
		if ( $timestamp !== false ) {
			wp_unschedule_event( $timestamp, 'hm_tor_cron_hook', array( intval( $prev['del_older_than'] ) ) );
		}

		if ( filter_var( $input['del_older_than'], FILTER_VALIDATE_INT ) === FALSE ) {
			add_settings_error( 'hm_tor_delete_old_revisions', 'hm-tor-del-older-than-error', __( 'The day has to be an integer.', self::I18N_DOMAIN ) );
			$valid['del_older_than'] = $prev['del_older_than'];
			$valid_conf_for_cron = false;
		}
		else {
			$valid['del_older_than'] = $input['del_older_than'];
		}

        if ( filter_var( $input['history_note_priority'], FILTER_VALIDATE_INT ) === FALSE ) {
            add_settings_error( 'hm_tor_history_note_priority', 'hm-tor-history-note-priority-error', __( 'The priority has to be an integer.', self::I18N_DOMAIN ) );
            $valid['history_note_priority'] = $prev['history_note_priority'];
            $valid_conf_for_cron = false;
        }
        else {
            $valid['history_note_priority'] = $input['history_note_priority'];
        }

		$valid['schedule_enabled'] = 'disabled';
		$valid['del_at'] = $prev['del_at'];
		if ( isset($input['schedule_enabled']) && $input['schedule_enabled'] == 'enabled' ) {
			$hour = $min = 0;
			if ( ! preg_match( '/\A([0-9]{1,2}):([0-9]{2})\z/', $input['del_at'], $matches ) ) {
				add_settings_error( 'hm_tor_delete_old_revisions', 'hm-tor-del-at-error', __( 'Wrong time format.', self::I18N_DOMAIN ) );
				$valid_conf_for_cron = false;
			}
			else {
				$valid['del_at'] = $input['del_at'];
				$hour = $matches[1];
				$min  = $matches[2];
			}

			if ( $valid_conf_for_cron  ) {
				$valid['schedule_enabled'] = 'enabled';
				wp_schedule_event( self::get_timestamp_for_cron( $hour, $min ), 'daily', 'hm_tor_cron_hook', array( intval( $valid['del_older_than'] ) ) );
			}
		}

		$valid['quick_edit']     = ( ( isset($input['quick_edit']) && $input['quick_edit'] == "on" ) ? "on" : "off" );
		$valid['bulk_edit']      = ( ( isset($input['bulk_edit']) && $input['bulk_edit'] == "on" ) ? "on" : "off" );
		$valid['del_on_publish'] = ( ( isset($input['del_on_publish']) && $input['del_on_publish'] == "on" ) ? "on" : "off" );
        $valid['history_note'] = ( ( isset($input['history_note']) && $input['history_note'] == "on" ) ? "on" : "off" );
        $valid['default_action'] = ( ( isset($input['default_action']) && $input['default_action'] == "hide" ) ? "hide" : "show" );

        // header for the history
        $sps = new SimplePie_Sanitize();
        $sps->strip_attributes( array('bgsound', 'expr', 'style', 'onclick', 'onerror', 'onfinish', 'onmouseover', 'onmouseout', 'onfocus', 'onblur', 'lowsrc', 'dynsrc') );
        $valid['history_head'] = $sps->sanitize($input['history_head'], SIMPLEPIE_CONSTRUCT_HTML);

		return $valid;
	}

	function admin_menu() {
		add_options_page( 'Thin Out Revisions', 'Thin Out Revisions', 'manage_options',
			'hm_tor_option_page', array( &$this, 'admin_page' ) );
	}

	function admin_page() {
		?>
		<script type="text/javascript">
			(function($, window, document) {

				$(document).ready(function() {

					$('#hm_tor_rm_now_button').click(function() {
						if (! /^[0-9]+$/.test( $('#hm_tor_del_older_than').val())) {
							alert('<?php echo __( 'The day has to be an integer.', self::I18N_DOMAIN ); ?>');
							return false;
						}
						if (!confirm('<?php echo __( "Do you really want to remove this?", self::I18N_DOMAIN ); ?>' + ' (' + $('#hm_tor_del_older_than').val() + ' ' +
						              '<?php echo __( 'days', self::I18N_DOMAIN ); ?>' + ')')) {
							return false;
						}
						$('#hm_tor_rm_now_msg').html('<?php echo __( 'Processing...', self::I18N_DOMAIN ); ?>');
						$.ajax({
							url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
							dataType: 'json',
							data: {
								action: 'hm_tor_do_ajax_start_delete_old_revisions',
								days: $('#hm_tor_del_older_than').val(),
								security: '<?php echo wp_create_nonce( self::PREFIX . "nonce" ); ?>'
							}
						})
						.success (function(response) {
							$('#hm_tor_rm_now_msg').html(response.msg);
						})
						.error (function() {
							$('#hm_tor_rm_now_msg').html('<?php  echo __( 'Error in communication with server', self::I18N_DOMAIN ); ?>');
						});

						return false;
					});

				});
			})(jQuery, window, document);
		</script>

		<div class="wrap">
			<?php screen_icon(); ?>
			<h2>Thin Out Revisions</h2>

			<form action="options.php" method="post">
				<?php settings_fields( 'hm_tor_option_group' ); ?>
				<?php do_settings_sections( 'hm_tor_option_page' ); ?>
				<p class="submit">
					<input class="button-primary" name="Submit" type="submit" value="<?php echo __( 'Save Changes' ); ?>" /></p>
			</form>
		</div>
	<?php
	}

	function delete_old_revisions( $days ) {
		global $wpdb;

		$revisions = $wpdb->get_results($wpdb->prepare(
			"SELECT ID, post_parent, post_name
      FROM $wpdb->posts
      WHERE post_type = 'revision'
			AND DATE_SUB(CURDATE(), INTERVAL %d DAY) >= post_date
			ORDER BY post_parent, post_date DESC", ( $days - 1 )
		) ); // Both CURDATE and post_date are local time.

		// COPY FROM Core
		// refer wp_save_post_revision
		$parent = 0;
		foreach ( $revisions as $revision ) {

            // TODO: Better operation to avoid copy revisions which have same contents as current posts.
			if ( $this->has_copy_revision() && $parent != $revision->post_parent &&
					false !== strpos( $revision->post_name, "{$revision->post_parent}-revision" ) ) {
				// got the latest revision which is not an autosave

				$parent = $revision->post_parent;
			}
			else {
				// delete revisions
				wp_delete_post_revision( $revision->ID );
			}
		}

	} // end of delete_old_revisions

	function do_ajax_start_delete_old_revisions() {
		if ( check_ajax_referer( self::PREFIX . "nonce", 'security', false ) ) {

			wp_schedule_single_event( time(), 'hm_tor_cron_hook', array( intval($_REQUEST['days'] ) ) );
			echo json_encode( array(
				"result" => "success",
				"msg"    => __( "The task is successfully started.", self::I18N_DOMAIN )
			) );
		}
		else {
			echo json_encode( array(
				"result" => "error",
				"msg"    => __( "Wrong session. Unable to process.", self::I18N_DOMAIN )
			) );
		}
		die();
	}

	function cron_hook( $days ) {
		$this->delete_old_revisions( $days );
	}

} // end of class HM_TOR_Plugin_Loader


/*
  class to show memo for revisions
 */
class HM_TOR_RevisionMemo_Loader {
	const I18N_DOMAIN = 'thin-out-revisions';
	const PREFIX      = 'hm_tor_';

	private $no_new_revision  = false;
	private $last_revision_id = 0;

	static $instance = false;

	// Constructor
	function __construct() {

		// Build user interface
		add_action( 'add_meta_boxes', array( &$this, 'add_meta_box' ) );

		// Add metadata to a revisions to be saved
		add_action( 'save_post', array( &$this, 'save_post' ), 10, 3);

		// Showing text input area for memo in revision.php.
		add_action( 'admin_head', array( &$this, 'admin_head' ) );

		// from WP3.6
		add_filter( 'wp_save_post_revision_check_for_changes', array( &$this, 'wp_save_post_revision_check_for_changes' ), 200, 3 );

		// load related modules
		add_action( 'admin_enqueue_scripts',  array( &$this, 'admin_enqueue_scripts' ), 20 );

		// ajax for memo editing
		add_action( 'wp_ajax_hm_tor_do_ajax_update_memo', array( &$this, 'do_ajax_update_memo' ) );
	}

	public static function getInstance() {
		if ( !self::$instance )
			self::$instance = new self;
		return self::$instance;
	}

	function admin_head() {
		global $left, $right, $post, $wpdb, $pagenow;

		$revision_php = false;
		if ( $pagenow === 'revision.php' ) {
			$revision_php = true;
		}
		else if ( $pagenow === 'post.php' ) {
		}
		else {
			return;
		}
		if ( !$post || !$post->ID ) {
			return;
		}

		$memos = $wpdb->get_results(
			"
      SELECT post_id, meta_value
      FROM $wpdb->posts, $wpdb->postmeta
      WHERE post_parent = $post->ID
      AND $wpdb->postmeta.post_id = $wpdb->posts.ID
      AND meta_key = '_hm_tor_memo'
      ORDER BY post_date DESC
      "
		);

		$postmemo = get_post_meta( $post->ID, "_hm_tor_memo", true ); // keep this line for pre 3.6 posts

		$latest_revision = $this->get_latest_revision( $post->ID );

		if ( ! $postmemo && $latest_revision != 0 ) {
			$postmemo = get_post_meta( $latest_revision, "_hm_tor_memo", true );
		}

		?>
		<script type='text/javascript'>
			var hm_tor_memos = {
				<?php
				    $has_latest = false;
						foreach ($memos as $m) {
						  if ($m->post_id == $latest_revision) {
						    $has_latest = true;
						  }
							echo "'$m->post_id': '" . esc_js($m->meta_value) . "',\n";
						}
						if ( (! $has_latest ) && $latest_revision != 0 ) {
						  echo "'$latest_revision': '" . esc_js($postmemo) . "',\n";
						}
						echo "'$post->ID': '" . esc_js($postmemo) . "'\n";
				?>
			};
			jQuery(document).ready(function () {
				jQuery('.post-revisions a').each(function () {
					var parse_url = /(post|revision)=([0-9]+)/;
					var result = parse_url.exec(jQuery(this).attr('href')); // will be found only in post.php
					if (result) {
						var memo = (typeof hm_tor_memos[result[2]] === 'undefined' ? '' : hm_tor_memos[result[2]]);
						jQuery(this).after(' <span class="hm-tor-old-memo" id="hm-tor-memo-' + result[2] + '">[' + memo + ']</span>');
					}
				});

				jQuery('#hm-tor-memo-current').html(' <?php if ($postmemo) { echo "[" . esc_js($postmemo) . "]"; } ?>');
			});
		</script>
<style>
#hm-tor-memo-editor {
	position: absolute;
	top: 0;
	left: 0;
	background-color: #f4f4f4;
	z-index: 999;
	border: 1px solid #dadada;
	display: none;
}

#hm-tor-memo-editor input {
	margin: 7px;
}

#hm-tor-memo-input {
	width: 300px;
}

.hm-tor-modal-background {
	position: fixed;
	top: 0; left: 0; 	bottom: 0; right: 0;
	background: none repeat scroll 0% 0% rgba(0, 0, 0, 0.10);
	z-index: 998;
	display: none;
}

.hm-tor-old-memo:hover {
	color: #aeaeae;
}
</style>
	<?php
	} // end of 'admin_head'

	// This function should be overrided for 3.5
	function get_latest_revision( $post_id ) {

		// COPY FROM CORE
		$latest_revision = 0;
		$revisions = wp_get_post_revisions( $post_id );

		if ( ! empty( $revisions ) ) {
			// grab the last revision, but not an autosave (from wp_save_post_revision in WP 3.6)
			foreach ( $revisions as $revision ) {
				if ( false !== strpos( $revision->post_name, "{$revision->post_parent}-revision" ) ) {
					$latest_revision = $revision->ID;
					break;
				}
			}
		}
	  return $latest_revision;
	}

	function add_meta_box() {
		global $post;
		if ( $post && post_type_supports( $post->post_type, 'revisions' ) ) {
			// add_meta_box( 'hm-he-revision', __('Revisions'), 'post_revisions_meta_box', null, 'normal', 'core' );
			add_meta_box( 'hm-he-memo', __( 'Revision Memo', self::I18N_DOMAIN ), array( &$this, 'hm_tor_mbfunction' ), null, 'normal', 'core' );
	  }
	}

	function hm_tor_mbfunction( $post ) {
		wp_nonce_field( plugin_basename( __FILE__ ), 'hm_tor_nonce' );

        $show_history = get_post_meta( $post->ID, '_hm_tor_show_history', true );
        if (! $show_history ) {
            $show_history = HM_TOR_Plugin_Loader::get_hm_tor_option('default_action');
        }

		$memo = ''; // always empty
		echo __( "Memo: ", self::I18N_DOMAIN );
	?>
        <div>
		<input type="text" name="hm_tor_memo" id="hm-tor-memo" value="<?php echo esc_attr( $memo ); ?>" style="width: 300px;" />
		<span id="hm-tor-memo-current"></span>
		<input id="hm-tor-copy-memo" type="button" class="button" value="<?php echo __( "Copy" ); ?>" style="margin: 0 10px">
        </div>

    <?php
        if ( HM_TOR_Plugin_Loader::get_hm_tor_option('history_note') === 'on') {
    ?>
        <div style="margin: 10px 0">
            <fieldset>
                <legend class='screen-reader-text'><span><?php echo __( "Show memos on the post: ", self::I18N_DOMAIN ); ?></span></legend>
                <?php echo __( "Show memos on the post: ", self::I18N_DOMAIN ); ?>
                <label title="show" style="margin: 0 10px;">
                    <input type='radio' name='hm_tor_show_history' id='hm-tor-show-history-show' value='show'
                        <?php if ($show_history == 'show') { echo "checked='checked'"; } ?> />
                    <span><?php echo __( "Show", self::I18N_DOMAIN );?></span>
                </label>
                <label title="hide">
                    <input type='radio' name='hm_tor_show_history' id='hm-tor-show-history-hide' value='hide'
                        <?php if ($show_history == 'hide') { echo "checked='checked'"; } ?> />
                    <span><?php echo __( "Hide", self::I18N_DOMAIN );?></span>
                </label>
            </fieldset>
        </div>
	<?php
        } // end of 'if ('

	}

	function save_post( $post_id, $post, $update ) {
		// save_post handler:
		// - add or update a memo for posts/revisions
		global $wpdb;

		$parent = wp_is_post_revision( $post_id );
		$post_type_object = get_post_type_object( $parent ? get_post_type($parent) : (empty($_POST['post_type']) ? $post->post_type : $_POST['post_type'] ) );
		if ( isset( $_POST['hm_tor_nonce'] ) && wp_verify_nonce( $_POST['hm_tor_nonce'], plugin_basename( __FILE__ ) ) &&
				( ( $post_type_object->capability_type == 'post' && current_user_can( 'edit_post', $post_id ) )
						|| ( $post_type_object->capability_type == 'page' && current_user_can( 'edit_page', $post_id ) ) )
		) {
            if ( isset( $_POST['hm_tor_memo'] ) ) { // revision memo
                if ( $parent ) {
                    // saving a revision

                    if ( $_POST['hm_tor_memo'] !== '' ) {
                        // We cannot use update_post_meta for revisions because it will add metadata to the parent.
                        update_metadata( 'post', $post_id, '_hm_tor_memo', sanitize_text_field( $_POST['hm_tor_memo'] ) );
                    }
                }
                else {
                    // saving a post

                    if ($this->last_revision_id != 0) {

                        // for compatibility for WP3.5 and older.
                        $postmemo = get_post_meta( $post_id, '_hm_tor_memo', true);
                        if ( $postmemo ){
                            update_metadata( 'post', $this->last_revision_id, '_hm_tor_memo', $postmemo );
                            delete_post_meta( $post_id, '_hm_tor_memo' );
                        }

                        // If we have a new memo value, update the memo even no new revision is created.
                        if ( $this->no_new_revision && $_POST['hm_tor_memo'] !== '' ) {
                            // Attach the new memo to the latest revision
                            update_metadata( 'post', $this->last_revision_id, '_hm_tor_memo', sanitize_text_field( $_POST['hm_tor_memo'] ) );
                        }
                    }
                }
            }

            if ( isset( $_POST['hm_tor_show_history'] ) && ( ! $parent ) ) {
				// saving a post
                update_post_meta( $post_id, '_hm_tor_show_history', $_POST['hm_tor_show_history'] == 'show' ? 'show' : 'hide' );
            }

		} // if ( isset ...
	}

	function wp_save_post_revision_check_for_changes( $val, $last_revision, $post) {
		// code from revision.php
		$post_has_changed = false;

		// COPY FROM CORE
		// from wp_save_post_revision
		foreach ( array_keys( _wp_post_revision_fields() ) as $field ) {
			if ( normalize_whitespace( $post->$field ) != normalize_whitespace( $last_revision->$field ) ) {
				$post_has_changed = true;
				break;
			}
		}

		$this->no_new_revision  = ( ( ! $post_has_changed ) && $val );
		$this->last_revision_id = $last_revision->ID;

		return $val;
	}

	function admin_enqueue_scripts() {
		wp_enqueue_script( 'jquery-ui-position' );

		// using parameters from HM_TOR_Plugin_Loader
	}

	function do_ajax_update_memo() {
		if ( check_ajax_referer( self::PREFIX . "nonce", 'security', false ) ) {

			if (filter_var($_REQUEST['revision'], FILTER_VALIDATE_INT) === false) {
				echo json_encode( array(
						"result" => "error",
						"msg"    => __( "Invalid revision number.", self::I18N_DOMAIN )
				) );
				die;
			}

			$parent = wp_is_post_revision($_REQUEST['revision']);
			if ($parent) {
				$post_type = get_post_type($parent);
				$post_type_object = get_post_type_object($post_type);
			}

			if ($parent === false) {
				echo json_encode( array(
						"result" => "error",
						"msg"    => __( "Wrong revision ID.", self::I18N_DOMAIN )
				) );
			}
			else if ( ( $post_type_object->capability_type == 'post' && !current_user_can( 'edit_post', $parent ) ) ||
				       ( $post_type_object->capability_type == 'page' && !current_user_can( 'edit_page', $parent ) ) ) {
				echo json_encode( array(
						"result" => "error",
						"msg"    => __( "You seem not to have a permission to update revisions.", self::I18N_DOMAIN )
				) );
			}
			else if (update_metadata( 'post', $_REQUEST['revision'], '_hm_tor_memo', sanitize_text_field($_REQUEST['memo'])) !== false) {
				echo json_encode( array(
					"result" => "success",
					"msg"    => __( "The memo is successfully updated.", self::I18N_DOMAIN )
				) );
			}
			else {
                if (get_metadata( 'post', $_REQUEST['revision'], '_hm_tor_memo', true ) == sanitize_text_field($_REQUEST['memo'])) {
                    echo json_encode( array(
                        "result" => "success",
                        "msg"    => __( "No difference between old and new memos.", self::I18N_DOMAIN )
                    ) );
                }
                else {
                    echo json_encode( array(
                        "result" => "error",
                        "msg"    => __( "Failed to update the memo.", self::I18N_DOMAIN )
                    ) );
                }
			}
		}
		else {
			echo json_encode( array(
				"result" => "error",
				"msg"    => __( "Wrong session. Unable to process.", self::I18N_DOMAIN )
			) );
		}

		die();
	}

} // end of 'HM_TOR_RevisionMemo_Loader


// for unit tests
global $hm_tor_plugin_loader, $hm_tor_revisionmemo_loader;

// Load HM_TOR_Plugin_Loader first.
$hm_tor_plugin_loader = HM_TOR_Plugin_Loader::getInstance();
$hm_tor_revisionmemo_loader = HM_TOR_RevisionMemo_Loader::getInstance();

