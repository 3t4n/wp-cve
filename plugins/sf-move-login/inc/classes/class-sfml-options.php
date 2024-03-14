<?php
defined( 'ABSPATH' ) || die( 'Cheatin\' uh?' );

/**
 * Singleton class.
 *
 * @package Move Login
 */
class SFML_Options extends SFML_Singleton {

	const VERSION      = '1.3';
	const OPTION_NAME  = 'sfml';
	const OPTION_GROUP = 'sfml_settings';
	const OPTION_PAGE  = 'move-login';

	/**
	 * All options.
	 *
	 * @var (array)
	 */
	protected $options;

	/**
	 * All default options.
	 *
	 * @var (array)
	 */
	protected $options_default;

	/**
	 * Slugs.
	 *
	 * @var (array)
	 */
	protected $slugs;

	/**
	 * Slug setting labels.
	 *
	 * @var (array)
	 * @since 2.5.2 Renamed from $labels to $slug_labels.
	 */
	protected $slug_labels;


	/**
	 * Init.
	 */
	public function _init() {
		if ( defined( 'WP_UNINSTALL_PLUGIN' ) ) {
			return;
		}

		// Register option and sanitization method.
		$this->register_setting();
	}


	/**
	 * An improved version of `register_setting()`, that always exists and that works for network options.
	 */
	protected function register_setting() {
		global $new_whitelist_options;

		$sanitize_callback = array( $this, 'sanitize_options' );

		if ( ! is_multisite() ) {
			if ( function_exists( 'register_setting' ) ) {
				register_setting( static::OPTION_GROUP, static::OPTION_NAME, $sanitize_callback );
				return;
			}

			$new_whitelist_options = isset( $new_whitelist_options ) && is_array( $new_whitelist_options ) ? $new_whitelist_options : array(); // WPCS: override ok.
			$new_whitelist_options[ static::OPTION_GROUP ]   = isset( $new_whitelist_options[ static::OPTION_GROUP ] ) && is_array( $new_whitelist_options[ static::OPTION_GROUP ] ) ? $new_whitelist_options[ static::OPTION_GROUP ] : array(); // WPCS: override ok.
			$new_whitelist_options[ static::OPTION_GROUP ][] = static::OPTION_NAME; // WPCS: override ok.
		} elseif ( is_admin() ) {
			$whitelist = sfml_cache_data( 'new_whitelist_network_options' );
			$whitelist = is_array( $whitelist ) ? $whitelist : array();
			$whitelist[ static::OPTION_GROUP ]   = isset( $whitelist[ static::OPTION_GROUP ] ) ? $whitelist[ static::OPTION_GROUP ] : array();
			$whitelist[ static::OPTION_GROUP ][] = static::OPTION_NAME;
			sfml_cache_data( 'new_whitelist_network_options', $whitelist );
		}

		if ( $sanitize_callback ) {
			add_filter( 'sanitize_option_' . static::OPTION_NAME, $sanitize_callback );
		}
	}


	/*--------------------------------------------------------------------------------------------*/
	/* !DEFAULT OPTIONS ========================================================================= */
	/*--------------------------------------------------------------------------------------------*/

	/**
	 * Get default options.
	 *
	 * @return (array)
	 */
	public function get_default_options() {
		$this->maybe_clear_options_cache();

		if ( isset( $this->options_default ) ) {
			return $this->options_default;
		}

		// Default slugs.
		$this->options_default = array(
			'slugs.logout'       => 'logout',
			'slugs.lostpassword' => 'lostpassword',
			'slugs.resetpass'    => 'resetpass',
			'slugs.register'     => 'register',
			'slugs.login'        => 'login',
		);

		// Plugins can add their own actions.
		$additional_slugs = static::get_additional_labels();

		if ( $additional_slugs && is_array( $additional_slugs ) ) {
			foreach ( $additional_slugs as $slug_key => $slug_label ) {
				$slug_key = sanitize_title( $slug_key, '', 'display' );

				if ( ! empty( $slug_key ) && ! isset( $this->options_default[ 'slug.' . $slug_key ] ) ) {
					$this->options_default[ 'slugs.' . $slug_key ] = $slug_key;
				}
			}
		}

		// Other options.
		$this->options_default = array_merge( $this->options_default, $this->get_other_default_options() );

		return $this->options_default;
	}


	/**
	 * Get "other" default options (the radio groups).
	 *
	 * @since 2.5.2
	 *
	 * @return (array)
	 */
	public function get_other_default_options() {
		return array(
			'deny_wp_login_access' => 1,
			'deny_admin_access'    => 0,
		);
	}


	/*--------------------------------------------------------------------------------------------*/
	/* !GET OPTIONS ============================================================================= */
	/*--------------------------------------------------------------------------------------------*/

	/**
	 * Get all options.
	 *
	 * @return (array)
	 */
	public function get_options() {
		$this->maybe_clear_options_cache();

		if ( isset( $this->options ) ) {
			return $this->options;
		}

		$this->options   = array();
		$raw_options     = get_site_option( static::OPTION_NAME );
		$default_options = $this->get_default_options();

		if ( is_array( $raw_options ) ) {
			// Add and escape slugs.
			$default_slugs = static::get_sub_options( 'slugs', $default_options );

			foreach ( $default_slugs as $slug_key => $default_slug ) {
				$this->options[ 'slugs.' . $slug_key ] = ! empty( $raw_options[ 'slugs.' . $slug_key ] ) ? sanitize_title( $raw_options[ 'slugs.' . $slug_key ], $default_slug, 'display' ) : $default_slug;
			}

			// Add and escape other options.
			$default_options = $this->get_other_default_options();

			foreach ( $default_options as $option_name => $default_value ) {
				if ( ! isset( $raw_options[ $option_name ] ) ) {
					$this->options[ $option_name ] = $default_value;
					continue;
				}

				$choices                       = $this->get_field_labels( $option_name );
				$this->options[ $option_name ] = (int) $raw_options[ $option_name ];

				if ( ! isset( $choices[ $this->options[ $option_name ] ] ) ) {
					$this->options[ $option_name ] = $default_value;
				}
			}
		} else {
			$this->options = $default_options;
		}

		$filtered_options = $this->options;

		/**
		 * Filter the plugin options before retrieving them.
		 *
		 * @param (array) $filtered_options The plugin options.
		 */
		$filtered_options = apply_filters( 'sfml_options', $filtered_options );

		// Make sure no keys have been added or removed.
		$this->options    = array_intersect_key( array_merge( $this->options, $filtered_options ), $this->options );

		return $this->options;
	}


	/**
	 * Get an option.
	 *
	 * @since 2.4
	 *
	 * @param (string) $option_name Name of the option.
	 *
	 * @return (mixed) Return null if the option dosn't exist.
	 */
	public function get_option( $option_name ) {
		$options = $this->get_options();
		return $option_name && isset( $options[ $option_name ] ) ? $options[ $option_name ] : null;
	}


	/**
	 * Get the slugs that will be rewritten.
	 *
	 * @return (array)
	 */
	public function get_slugs() {
		$this->maybe_clear_options_cache();

		if ( isset( $this->slugs ) ) {
			return $this->slugs;
		}

		$this->slugs = array_merge(
			$this->get_non_customizable_actions(),
			static::get_sub_options( 'slugs', $this->get_options() )
		);

		return $this->slugs;
	}


	/*--------------------------------------------------------------------------------------------*/
	/* !FIELD LABELS ============================================================================ */
	/*--------------------------------------------------------------------------------------------*/

	/**
	 * Get the possible choices for a specific option.
	 *
	 * @since 2.5.2
	 *
	 * @param (string) $option  The option name.
	 *
	 * @return (array)
	 */
	public function get_field_labels( $option ) {
		if ( 'slugs' === $option ) {
			return $this->get_slug_field_labels();
		}

		$choices = array(
			'deny_wp_login_access' => array(
				1 => __( 'Display an error message', 'sf-move-login' ),
				4 => __( 'Trigger a &laquo;Page not found&raquo; error', 'sf-move-login' ),
				2 => __( 'Redirect to a "WordPress" &laquo;Page not found&raquo; error page', 'sf-move-login' ),
				3 => __( 'Redirect to the home page', 'sf-move-login' ),
			),
			'deny_admin_access'    => array(
				0 => __( 'Do nothing, redirect to the new login page (not recommended)', 'sf-move-login' ),
				1 => __( 'Display an error message', 'sf-move-login' ),
				4 => __( 'Trigger a &laquo;Page not found&raquo; error', 'sf-move-login' ),
				2 => __( 'Redirect to a "WordPress" &laquo;Page not found&raquo; error page', 'sf-move-login' ),
				3 => __( 'Redirect to the home page', 'sf-move-login' ),
			),
		);

		$choices = isset( $choices[ $option ] ) ? $choices[ $option ] : array();

		/**
		 * Filter the possible choices for a specific option.
		 *
		 * @param (array)  $choices The possible choices.
		 * @param (string) $option  The option name.
		 */
		return apply_filters( 'sfml_option_choices', $choices, $option );
	}


	/**
	 * Setting field labels for the slugs.
	 *
	 * @return (array)
	 */
	public function get_slug_field_labels() {
		$this->maybe_clear_options_cache();

		if ( isset( $this->slug_labels ) ) {
			return $this->slug_labels;
		}

		$this->slug_labels = array(
			'login'        => __( 'Log in' ),
			'logout'       => __( 'Log out' ),
			'register'     => __( 'Register' ),
			'lostpassword' => __( 'Lost Password' ),
			'resetpass'    => __( 'Password Reset' ),
		);

		$new_actions = static::get_additional_labels();

		if ( $new_actions ) {
			$new_actions       = array_diff_key( $new_actions, $this->slug_labels );
			$this->slug_labels = array_merge( $this->slug_labels, $new_actions );
		}

		return $this->slug_labels;
	}


	/**
	 * Get custom labels (added by other plugins).
	 *
	 * @since 2.4
	 *
	 * @return (array)
	 */
	public static function get_additional_labels() {
		$new_actions = array();
		/**
		 * Plugins can add their own actions.
		 *
		 * @param (array) $new_actions Custom actions.
		 */
		return apply_filters( 'sfml_additional_slugs', $new_actions );
	}


	/*--------------------------------------------------------------------------------------------*/
	/* !SANITIZATION ============================================================================ */
	/*--------------------------------------------------------------------------------------------*/

	/**
	 * Sanitize options on save.
	 *
	 * @param (array) $options Options to sanitize.
	 *
	 * @return (array)
	 */
	public function sanitize_options( $options = array() ) {
		$old_options = get_site_option( static::OPTION_NAME );

		// Add and sanitize slugs.
		$sanitized_options = $this->sanitize_slugs( $options );
		$errors            = $sanitized_options['errors'];
		$sanitized_options = $sanitized_options['slugs'];

		// Add and sanitize other options.
		$default_options = $this->get_other_default_options();

		foreach ( $default_options as $option_name => $default_value ) {
			if ( isset( $options[ $option_name ] ) ) {
				// Yay!
				$sanitized_options[ $option_name ] = (int) $options[ $option_name ];
			} elseif ( isset( $old_options[ $option_name ] ) ) {
				// Old value.
				$sanitized_options[ $option_name ] = (int) $old_options[ $option_name ];
			} else {
				// Default value.
				$sanitized_options[ $option_name ] = $default_value;
				continue;
			}

			$choices = $this->get_field_labels( $option_name );

			if ( ! isset( $choices[ $sanitized_options[ $option_name ] ] ) ) {
				// Oh no.
				$sanitized_options[ $option_name ] = $default_value;
			}
		}

		$filtered_options = $sanitized_options;

		/**
		 * Filter the options after being sanitized.
		 *
		 * @param (array) $filtered_options The new options, sanitized.
		 * @param (array) $options          The submitted options.
		 */
		$filtered_options  = apply_filters( 'sfml_sanitize_options', $filtered_options, $options );

		// Make sure no keys have been removed.
		$sanitized_options = array_merge( $sanitized_options, $filtered_options );

		// Clear options cache.
		$this->maybe_clear_options_cache( true );

		// Add the rewrite rules to the `.htaccess`/`web.config` file.
		$old_slugs = static::get_sub_options( 'slugs', $old_options );
		$new_slugs = static::get_sub_options( 'slugs', $sanitized_options );

		if ( $old_slugs !== $new_slugs ) {
			sfml_include_rewrite_file();
			sfml_write_rules( sfml_rules( $new_slugs ) );
		}

		// Trigger errors.
		if ( is_admin() ) {
			$errors['forbidden']  = array_unique( $errors['forbidden'] );
			$errors['duplicates'] = array_unique( $errors['duplicates'] );

			if ( $nbr_forbidden = count( $errors['forbidden'] ) ) {
				/* translators: %s is an URL slug name. */
				add_settings_error( 'sfml_settings', 'forbidden-slugs', sprintf( _n( 'The slug %s is forbidden.', 'The slugs %s are forbidden.', $nbr_forbidden, 'sf-move-login' ), wp_sprintf( '<code>%l</code>', $errors['forbidden'] ) ) );
			}
			if ( ! empty( $errors['duplicates'] ) ) {
				add_settings_error( 'sfml_settings', 'duplicates-slugs', __( 'The links can\'t have the same slugs.', 'sf-move-login' ) );
			}
		}

		return $sanitized_options;
	}


	/**
	 * Sanitize slugs.
	 *
	 * @param (array) $raw_slugs Slugs to sanitize.
	 *
	 * @return (array) An array containing the sanitized slugs and possible errors.
	 */
	public function sanitize_slugs( $raw_slugs = array() ) {
		$default_slugs = static::get_sub_options( 'slugs', $this->get_default_options() );
		$old_slugs     = get_site_option( static::OPTION_NAME );
		$old_slugs     = is_array( $old_slugs ) && $old_slugs ? static::get_sub_options( 'slugs', $old_slugs ) : array();
		$old_slugs     = array_merge( $default_slugs, $old_slugs );
		$exclude       = $this->get_other_actions();
		$raw_slugs     = $raw_slugs && is_array( $raw_slugs ) ? array_map( 'trim', $raw_slugs ) : array();
		$output        = array(
			'slugs'  => array(),
			'errors' => array(
				'forbidden'  => array(),
				'duplicates' => array(),
			),
		);

		// First, sanitize the old slugs.
		foreach ( $old_slugs as $action => $old_slug ) {
			$old_slug             = sanitize_title( $old_slug );
			$old_slugs[ $action ] = $old_slug ? $old_slug : $default_slugs[ $action ];
		}

		// Then, make sure there are no duplicates within the old slugs.
		$slugs_count        = count( $old_slugs );
		$unique_slugs_count = count( array_unique( $old_slugs ) );

		while ( $unique_slugs_count < $slugs_count ) {
			$tmp_old_slugs = $old_slugs;

			foreach ( $old_slugs as $action => $old_slug ) {
				$other_slugs = $old_slugs;
				unset( $other_slugs[ $action ] );

				if ( ! in_array( $slug, $other_slugs, true ) ) {
					// Not a duplicate.
					$tmp_old_slugs[ $action ] = $old_slug;
				} else {
					// Use the default slug (we know it is unique within the default slugs).
					$tmp_old_slugs[ $action ] = $default_slugs[ $action ];
				}
			}

			$old_slugs          = $tmp_old_slugs;
			$slugs_count        = count( $old_slugs );
			$unique_slugs_count = count( array_unique( $old_slugs ) );
		}

		// Sanitize the new slugs and make sure they are not forbidden.
		foreach ( $default_slugs as $action => $default_slug ) {
			$input_name = 'slugs.' . $action;

			// First, determinate a fallback.
			if ( empty( $raw_slugs[ $input_name ] ) ) {
				// If the field was left empty, fallback to the default slug.
				$fallback = $default_slug;
			} else {
				// Use the previous slug (or the default one).
				$fallback = $old_slugs[ $action ];
			}

			$new_slug = sanitize_title( $raw_slugs[ 'slugs.' . $action ], $fallback );

			$output['slugs'][ 'slugs.' . $action ] = $new_slug ? $new_slug : $fallback;

			// 'postpass', 'retrievepassword' and 'rp' are forbidden by default.
			if ( isset( $exclude[ $new_slug ] ) ) {
				$output['errors']['forbidden'][ $action ] = $new_slug;
				$output['slugs'][ 'slugs.' . $action ]    = $fallback;
			}
		}

		// Look for duplicates.
		$slugs_count        = count( $output['slugs'] );
		$unique_slugs_count = count( array_unique( $output['slugs'] ) );

		while ( $unique_slugs_count < $slugs_count ) {
			$new_slugs = $output['slugs'];

			foreach ( $output['slugs'] as $input_name => $slug ) {
				$other_slugs = $output['slugs'];
				unset( $other_slugs[ $input_name ] );

				if ( ! in_array( $slug, $other_slugs, true ) ) {
					// Not a duplicate.
					$new_slugs[ $input_name ] = $slug;
					continue;
				}

				$action = str_replace( 'slugs.', '', $input_name );

				// Use the previous slug (we know it is unique within the old slugs).
				$new_slugs[ $input_name ]                  = $old_slugs[ $action ];
				$output['errors']['duplicates'][ $action ] = $old_slugs[ $action ];
			}

			$output['slugs']    = $new_slugs;
			$slugs_count        = count( $output['slugs'] );
			$unique_slugs_count = count( array_unique( $output['slugs'] ) );
		}

		return $output;
	}


	/*--------------------------------------------------------------------------------------------*/
	/* !VARIOUS ================================================================================= */
	/*--------------------------------------------------------------------------------------------*/

	/**
	 * Get the original login actions that are not listed in the settings page.
	 * Plugins can add them to the settings page though.
	 *
	 * @return (array)
	 */
	public function get_other_actions() {
		return array_diff_key( array(
			'postpass'         => 'postpass',         // Not customizable.
			'retrievepassword' => 'retrievepassword', // Alias for lostpassword, not used by WP.
			'rp'               => 'rp',               // Alias for resetpass, not used by WP.
		), $this->get_slug_field_labels() );
	}


	/**
	 * Get the original login actions that will be rewritten but are not listed in the settings page.
	 * Those actions don't redirect to other login actions (and the visitors don't see them), so there's no need to bother the user with a useless setting.
	 * Plugins can add them to the settings page though.
	 *
	 * @since 2.5.3
	 *
	 * @return (array)
	 */
	public function get_non_customizable_actions() {
		return array_diff_key( array(
			'postpass' => 'postpass',
		), $this->get_slug_field_labels() );
	}


	/**
	 * Clear options cache.
	 *
	 * @param (bool) $force Clear the cache manually.
	 */
	public function maybe_clear_options_cache( $force = false ) {
		$clear = false;
		/**
		 * Clear options cache.
		 *
		 * @param (bool) $clear Return true if you want to clear the cache.
		 */
		if ( $force || apply_filters( static::OPTION_NAME . '_clear_options_cache', $clear ) ) {
			$this->options         = null;
			$this->options_default = null;
			$this->slugs           = null;
			$this->slug_labels     = null;
			remove_all_filters( static::OPTION_NAME . '_clear_options_cache' );
		}
	}


	/**
	 * Get sub-options.
	 *
	 * For example:
	 * static::get_sub_options( 'foo', array(
	 *     'option1'     => 'value1',
	 *     'foo.option2' => 'value2',
	 *     'foo.option3' => 'value3',
	 * ) );
	 * Will return:
	 * array(
	 *     'option2' => 'value2',
	 *     'option3' => 'value3',
	 * )
	 *
	 * @param (string) $name    The sub-option name.
	 * @param (array)  $options Array of options.
	 *
	 * @return (array)
	 */
	public static function get_sub_options( $name, $options ) {
		if ( ! $options || ! $name ) {
			return array();
		}

		$options = (array) $options;

		if ( isset( $options[ $name ] ) ) {
			return $options[ $name ];
		}

		$group = array();
		$name  = rtrim( $name, '.' ) . '.';

		foreach ( $options as $k => $v ) {
			if ( 0 === strpos( $k, $name ) ) {
				$group[ substr( $k, strlen( $name ) ) ] = $v;
			}
		}

		return ! empty( $group ) ? $group : null;
	}
}
