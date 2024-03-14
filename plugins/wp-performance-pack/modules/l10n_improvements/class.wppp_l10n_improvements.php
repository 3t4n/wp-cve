<?php

class WPPP_L10n_Improvements extends WPPP_Module {

	public static $jit_versions = array(
		'4.7.4'	=> '4.7.4',
		'4.7.5'	=> '4.7.4',
		'4.8'	=> '4.8',
		'4.8.1'	=> '4.8.1',
		'5.3.2' => '5.3.2',
	);

	public function load_renderer () {
		return new WPPP_L10n_Improvements_Advanced( $this->wppp );
	}

	public function admin_init () {
		if ( $this->wppp->options['disable_backend_translation'] && $this->wppp->options['dbt_allow_user_override'] ) {
			add_action( 'profile_personal_options', array( $this, 'wppp_extra_profile_fields' ) );
			add_action( 'personal_options_update', array ( $this, 'save_wppp_user_settings' ) );
			add_action( 'edit_user_profile_update', array ( $this, 'save_wppp_user_settings' ) );
		}
	}

	/*
	 * User override of disable  backend translation
	 */

	function save_wppp_user_settings ( $user_id ) {
		if ( !current_user_can( 'edit_user', $user_id ) ) { return false; }

		if ( isset( $_POST['wppp_translate_backend'] ) && $_POST['wppp_translate_backend'] === 'true' ) {
			update_user_option( $user_id, 'wppp_translate_backend', 'true' );
		} else {
			update_user_option( $user_id, 'wppp_translate_backend', 'false' );
		}
	}

	function wppp_extra_profile_fields( $user ) {
		$user_setting = get_user_option( 'wppp_translate_backend', $user->ID );
		$user_override = $user_setting === 'true' || ( $this->wppp->options['dbt_user_default_translated'] && $user_setting === false );
		?>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><?php _e( 'Translate back end', 'wp-performance-pack' ); ?></th>
					<td>
						<label for="wppp-translate-backend-enabled"><input type="checkbox" name="wppp_translate_backend" id="wppp-translate-backend-enabled" value="true" <?php echo  $user_override ? 'checked="true"' : ''; ?> /><?php _e( 'Enable back end translation', 'wp-performance-pack' ); ?></label><br/>
						<span class="description"><?php _e( 'Enable or disable back end translation. When disabled, back end will be displayed in english, else it will be translated to the blog language.', 'wp-performance-pack' ); ?></span>
					</td>
				</tr>
			</table>
		<?php
	}

	function is_jit_available () {
		global $wp_version;
		return isset( static::$jit_versions[$wp_version] );
	}

	public function early_init () {
		if ( $this->wppp->options['use_mo_dynamic'] 
			|| $this->wppp->options['use_native_gettext']
			|| $this->wppp->options['disable_backend_translation'] ) {

			global $l10n;
			$l10n['WPPP_NOOP'] = new NOOP_Translations;
			add_filter( 'override_load_textdomain', array( $this, 'wppp_load_textdomain_override' ), 0, 3 );
		}

		if ( $this->is_jit_available() && $this->wppp->options['use_jit_localize'] ) {
			global $wp_scripts;
			if ( !isset( $wp_scripts ) && !defined( 'IFRAME_REQUEST' ) ) {
				global $wp_version;
				include( sprintf( "%s/jit-by-version/wp" . static::$jit_versions[$wp_version] . ".php", dirname( __FILE__ ) ) );
				remove_action( 'wp_default_scripts', 'wp_default_scripts' );
				add_action( 'wp_default_scripts', 'wp_jit_default_scripts' );
				$wp_scripts = new WPPP_Scripts_Override();
			}
		}
	}
	
	function wppp_load_textdomain_override( $retval, $domain, $mofile ) {
		global $l10n;
		$result = false;
		$mo = NULL;

		if ( $this->wppp->options['disable_backend_translation'] 
			&& is_admin() 
			&& !( defined( 'DOING_AJAX' ) && DOING_AJAX && false === strpos( wp_get_referer(), '/wp-admin/' ) ) ) {
			if ( $this->wppp->options['dbt_allow_user_override'] ) {
				global $current_user;
				if ( !function_exists('wp_get_current_user')) {
					require_once(ABSPATH . "wp-includes/pluggable.php");
				}
				wp_cookie_constants();
				$current_user = wp_get_current_user();

				$user_setting = get_user_option ( 'wppp_translate_backend', $current_user->user_ID );
				$user_override = $user_setting === 'true' || ( $this->wppp->options['dbt_user_default_translated'] && $user_setting === false );
				if ( !$user_override ) {
					$mo = $l10n['WPPP_NOOP'];
					$result = true;
				}
			} else {
				$mo = $l10n['WPPP_NOOP'];
				$result = true;
			}
		}

		if ( $mo === NULL ) {
			do_action( 'load_textdomain', $domain, $mofile );
			$mofile = apply_filters( 'load_textdomain_mofile', $mofile, $domain );

			if ( isset( $l10n[$domain] ) ) {
				if ( $l10n[$domain] instanceof WPPP_MO_dynamic && $l10n[$domain]->MO_file_loaded( $mofile ) ) {
					return true;
				}
			}

			if ( $this->wppp->options['debug'] ) {
				$callers=debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
				$this->wppp->dbg_textdomains[$domain]['mofiles'][] = $mofile;
				$this->wppp->dbg_textdomains[$domain]['callers'][] = $callers;
			}

			if ( !is_readable( $mofile ) ) {
				if ( $this->wppp->options['debug'] ) {
					$this->wppp->dbg_textdomains[$domain]['mofileexists'][] = 'no';
				}
				return false; // return false is important so load_plugin_textdomain/load_theme_textdomain/... can call load_textdomain for different locations
			} elseif ( $this->wppp->options['debug'] ) {
				$this->wppp->dbg_textdomains[$domain]['mofileexists'][] = 'yes';
			}
		} else {
			if ( $this->wppp->options['debug'] ) {
				$callers=debug_backtrace();
				$this->wppp->dbg_textdomains[$domain]['mofiles'][] = $mofile;
				$this->wppp->dbg_textdomains[$domain]['mofileexists'][] = '-';
				$this->wppp->dbg_textdomains[$domain]['callers'][] = $callers;
			}
		}


		if ( $mo === NULL && $this->wppp->options['use_native_gettext'] && extension_loaded( 'gettext' ) ) {
			$mo = new WPPP_Native_Gettext ();
			if ( $mo->import_from_file( $mofile ) ) { 
				$result = true;
			} else {
				$mo = NULL;
			}
		}
	
		if ( $mo === NULL && $this->wppp->options['use_mo_dynamic'] ) {
			if ( $this->wppp->options['debug'] ) {
				$mo = new WPPP_MO_dynamic_Debug ( $domain, $this->wppp->options['mo_caching'], WP_Performance_Pack::cache_group );
			} else {
				$mo = new WPPP_MO_dynamic ( $domain, $this->wppp->options['mo_caching'], WP_Performance_Pack::cache_group );
			}
			if ( $mo->import_from_file( $mofile ) ) { 
				$result = true;
			} else {
				$mo->unhook_and_close();
				$mo = NULL;
			}
		}

		if ( $mo !== NULL ) {
			if ( isset( $l10n[$domain] ) ) {
				$mo->merge_with( $l10n[$domain] );
				if ( $l10n[$domain] instanceof WPPP_MO_dynamic ) {
					$l10n[$domain]->unhook_and_close();
				}
			}
			$l10n[$domain] = $mo;
		}

		return $result;
	}
}