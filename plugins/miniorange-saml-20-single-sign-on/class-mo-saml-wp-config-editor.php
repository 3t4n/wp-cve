<?php
/**
 * This file is responsible for writing content to the wp-config file.
 *
 * @package miniorange-saml-20-single-sign-on
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Contains methods required to operate on the wp-config.php file.
 */
class Mo_SAML_WP_Config_Editor {

	/**
	 * Path to wp-config.php file.
	 *
	 * @var string
	 */
	private $wp_config_path;

	/**
	 * Contents of wp-config.php file.
	 *
	 * @var mixed
	 */
	private $wp_config_src;

	/**
	 * Array to temporarly store config.
	 *
	 * @var mixed
	 */
	private $wp_configs;

	/**
	 * Initializes the path and wp-config.php file contents to appropriate variables.
	 *
	 * @param string $wp_config_path Path of the wp-config.php file.
	 */
	public function __construct( $wp_config_path ) {
		$basename             = basename( $wp_config_path );
		$this->wp_config_path = $wp_config_path;
		if ( ! file_exists( $wp_config_path ) ) {
			update_option( 'mo_saml_message', $basename . __( 'File doesn\'t exist.', 'miniorange-saml-20-single-sign-on' ) );
			Mo_SAML_Utilities::mo_saml_show_error_message();
		}
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Reading the wp-config.php.
		$wp_config_src       = file_get_contents( $this->wp_config_path );
		$this->wp_config_src = str_replace( array( "\n\r", "\r" ), "\n", $wp_config_src );

	}

	/**
	 * Save the contents in the wp-config file.
	 *
	 * @param string $contents Data to be written to the file.
	 * @return bool
	 */
	private function mo_saml_wp_config_save( $contents ) {
		if ( ! trim( $contents ) || $contents === $this->wp_config_src ) {
			update_option( 'mo_saml_message', __( 'Failed to update the WP config file. Please enable the debug-logs manually', 'miniorange-saml-20-single-sign-on' ) );
			Mo_SAML_Utilities::mo_saml_show_error_message();
			return false;
		}
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_file_put_contents -- Writing config to the wp-config.php.
		$result = file_put_contents( $this->wp_config_path, $contents, LOCK_EX );
		if ( false === $result ) {
			update_option( 'mo_saml_message', __( 'Failed to update the WP config file. Please enable the debug-logs manually', 'miniorange-saml-20-single-sign-on' ) );
			Mo_SAML_Utilities::mo_saml_show_error_message();
			return false;
		}
		update_option( 'mo_saml_message', __( 'Configuration Saved Successfully', 'miniorange-saml-20-single-sign-on' ) );
		Mo_SAML_Utilities::mo_saml_show_success_message();
		return true;
	}

	/**
	 * Adds a config to the wp-config.php file.
	 *
	 * @param string $name Config name.
	 * @param string $value Config value.
	 *
	 * @return bool
	 */
	private function mo_saml_wp_config_add( $name, $value ) {
		if ( ! is_string( $value ) ) {
			update_option( 'mo_saml_message', __( 'Config value must be a string.', 'miniorange-saml-20-single-sign-on' ) );
			Mo_SAML_Utilities::mo_saml_show_error_message();
			return false;
		}

		if ( $this->mo_saml_wp_config_exists( $name ) ) {
			return false;
		}

		$anchor    = "/* That's all, stop editing!";
		$separator = PHP_EOL;

		if ( 'EOF' === $anchor ) {
			$contents = $this->wp_config_src . $this->mo_saml_wp_config_normalize( $name, $value );
		} else {
			if ( false === strpos( $this->wp_config_src, $anchor ) ) {
				update_option( 'mo_saml_message', __( 'Unable to locate placement anchor.', 'miniorange-saml-20-single-sign-on' ) );
				Mo_SAML_Utilities::mo_saml_show_error_message();
				return false;
			}

			$new_src  = $this->mo_saml_wp_config_normalize( $name, $value );
			$new_src  = $new_src . $separator . $anchor;
			$contents = str_replace( $anchor, $new_src, $this->wp_config_src );
		}

		return $this->mo_saml_wp_config_save( $contents );
	}

	/**
	 * Updates an existing config in the wp-config.php file.
	 *
	 * @param string $name Config name.
	 * @param string $value Config value.
	 *
	 * @return bool
	 */
	public function mo_saml_wp_config_update( $name, $value ) {
		if ( ! is_string( $value ) ) {
			update_option( 'mo_saml_message', __( 'Config value must be a string.', 'miniorange-saml-20-single-sign-on' ) );
			Mo_SAML_Utilities::mo_saml_show_error_message();
			return false;
		}

		if ( ! $this->mo_saml_wp_config_exists( $name ) ) {
			return $this->mo_saml_wp_config_add( $name, $value );
		}

		$old_src   = $this->wp_configs[ $name ]['src'];
		$old_value = $this->wp_configs[ $name ]['value'];
		$new_value = $value;

		$new_parts    = $this->wp_configs[ $name ]['parts'];
		$new_parts[1] = str_replace( $old_value, $new_value, $new_parts[1] );
		$new_src      = implode( '', $new_parts );

		$contents = preg_replace(
			sprintf( '/(?<=^|;|<\?php\s|<\?\s)(\s*?)%s/m', preg_quote( trim( $old_src ), '/' ) ),
			'$1' . str_replace( '$', '\$', trim( $new_src ) ),
			$this->wp_config_src
		);

		return $this->mo_saml_wp_config_save( $contents );
	}

	/**
	 * Normalizes the source output for a name/value pair.
	 *
	 * @param string $name  Config name.
	 * @param mixed  $value Config value.
	 *
	 * @return string
	 */
	protected function mo_saml_wp_config_normalize( $name, $value ) {
		$placeholder = "define( '%s', %s );";
		return sprintf( $placeholder, $name, $value );
	}

	/**
	 * Parses the source of a wp-config.php file.
	 *
	 * @param string $src Config file source.
	 *
	 * @return array
	 */
	protected function mo_saml_wp_config_parse( $src ) {
		$configs = array();

		// Strip comments.
		foreach ( token_get_all( $src ) as $token ) {
			if ( in_array( $token[0], array( T_COMMENT, T_DOC_COMMENT ), true ) ) {
				$src = str_replace( $token[1], '', $src );
			}
		}

		preg_match_all( '/(?<=^|;|<\?php\s|<\?\s)(\h*define\s*\(\s*[\'"](\w*?)[\'"]\s*)(,\s*(\'\'|""|\'.*?[^\\\\]\'|".*?[^\\\\]"|.*?)\s*)((?:,\s*(?:true|false)\s*)?\)\s*;)/ims', $src, $constants );

		if ( ! empty( $constants[0] ) && ! empty( $constants[1] ) && ! empty( $constants[2] ) && ! empty( $constants[3] ) && ! empty( $constants[4] ) && ! empty( $constants[5] ) ) {
			foreach ( $constants[2] as $index => $name ) {
				$configs[ $name ] = array(
					'src'   => $constants[0][ $index ],
					'value' => $constants[4][ $index ],
					'parts' => array(
						$constants[1][ $index ],
						$constants[3][ $index ],
						$constants[5][ $index ],
					),
				);
			}
		}

		return $configs;
	}

	/**
	 * Checks if a config exists in the wp-config.php file.
	 *
	 * @param string $name Config name.
	 *
	 * @return bool
	 */
	private function mo_saml_wp_config_exists( $name ) {
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Reading the wp-config.php.
		$wp_config_src = file_get_contents( $this->wp_config_path );

		if ( ! trim( $wp_config_src ) ) {
			update_option( 'mo_saml_message', __( '<code>Wp-config.php</code> file is empty.', 'miniorange-saml-20-single-sign-on' ) );
			Mo_SAML_Utilities::mo_saml_show_error_message();
			return false;
		}
		// Normalize the newline to prevent an issue coming from OSX.
		$this->wp_config_src = str_replace( array( "\n\r", "\r" ), "\n", $wp_config_src );
		$this->wp_configs    = $this->mo_saml_wp_config_parse( $this->wp_config_src );

		if ( ! isset( $this->wp_configs[ $name ] ) ) {
			return false;
		}
		return isset( $this->wp_configs[ $name ] );
	}

}
