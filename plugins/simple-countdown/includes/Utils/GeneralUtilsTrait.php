<?php
namespace GPLSCore\GPLS_PLUGIN_WPSCTR\Utils;

/**
 * General Functions Utils Trait.
 */
trait GeneralUtilsTrait {

	/**
	 * Loader HTML Code.
	 *
	 * @return void
	 */
	public static function loader_html( $prefix = null ) {
		?>
		<div style="width:100%;height:100%;position:absolute;left:0;top:0;z-index:1000;" class="d-none loader <?php echo esc_attr( ! empty( $prefix ) ? $prefix . '-loader' : '' ); ?>">
			<div style="color:#FFF;text-align:center;position:absolute;display:flex;width:100%;height:100%;justify-content:center;align-items:center;">
				<img src="<?php echo esc_url_raw( admin_url( 'images/spinner-2x.gif' ) ); ?>"  />
			</div>
			<div style="position:absolute;display:block;opacity:0.5;width:100%;height:100%;background-color:#EEE;" class="overlay position-absolute d-block w-100 h-100 bg-light opacity-50"></div>
		</div>
		<?php
	}

	/**
	 * Copy to Clipboard Icon.
	 *
	 * @param string $target
	 * @return void
	 */
	public static function clipboard_icon( $target ) {
		?>
		<span class="gpls-general-clipboard-icon">
			<button style="display:flex;justify-content:center;align-items:center;padding:9px 9px;margin:0px 5px;" data-target="<?php echo esc_attr( $target ); ?>" type="button" class="btn btn-secondary gpls-general-clipboard-icon-btn" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="<?php esc_html_e( 'Copied' ); ?>" >
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard-data" viewBox="0 0 16 16"> <path d="M4 11a1 1 0 1 1 2 0v1a1 1 0 1 1-2 0v-1zm6-4a1 1 0 1 1 2 0v5a1 1 0 1 1-2 0V7zM7 9a1 1 0 0 1 2 0v3a1 1 0 1 1-2 0V9z"/> <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z"/> <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z"/> </svg>
			</button>
		</span>
		<?php
	}

	/**
	 * Loader HTML Code.
	 *
	 * @return void
	 */
	public static function loader_icon( $icon_size = 'small', $additional_classes = '', $additional_styles = '' ) {
		?>
		<img <?php echo ( ! empty( $additional_classes ) ? 'class="' . esc_attr( $additional_classes ) . '"' : '' ); ?> style="<?php echo esc_attr( $additional_styles ); ?>"  src="<?php echo esc_url_raw( admin_url( 'images/spinner' . ( ( 'big' === $icon_size ) ? '-2x' : '' ) . '.gif' ) ); ?>"  />
		<?php
	}

	/**
	 * Check if current Page.
	 *
	 * @param string $page_slug Page Slug.
	 *
	 * @return boolean
	 */
	public function is_current_page( $page_slug = null ) {

		if ( wp_doing_ajax() && ! is_null( $this->action ) && ! empty( $_POST['pageAction'] ) && ( sanitize_text_field( wp_unslash( $_POST['pageAction'] ) ) === $this->action ) ) {
			return true;
		}

		if ( ! is_null( $page_slug ) ) {
			return is_page( $page_slug );
		}
		if ( property_exists( $this, 'page_slug' ) && ! is_null( $this->page_slug ) ) {
			return is_page( $this->page_slug );
		}
		return false;
	}

	/**
	 * Parse Arguments recursevly.
	 *
	 * @param array $args
	 * @param array $defaults
	 * @return array
	 */
	private static function rec_parse_args( $args, $defaults ) {
		$new_args = (array) $defaults;
		foreach ( $args as $key => $value ) {
			if ( is_array( $value ) && isset( $new_args[ $key ] ) ) {
				$new_args[ $key ] = self::rec_parse_args( $value, $new_args[ $key ] );
			} else {
				$new_args[ $key ] = $value;
			}
		}
		return $new_args;
	}

	/**
	 * Map sanitize function name to field type.
	 *
	 * @param string $field_type
	 * @return string
	 */
	protected static function sanitize_functions_mapping( $field_type ) {
		$fields_sanitize_mapping = array(
			'text'     => 'sanitize_text_field',
			'textarea' => 'sanitize_textarea',
			'url'      => 'esc_url_raw',
			'email'    => 'sanitize_email',
		);
		return ( ! empty( $fields_sanitize_mapping[ $field_type ] ) ? $fields_sanitize_mapping[ $field_type ] : 'sanitize_text_field' );
	}

	/**
	 * Apply function on array.
	 *
	 * @param array        $arr
	 * @param string|array $func_name
	 * @param string       $casting
	 * @return array
	 */
	protected static function mapping_func( $arr, $func_name, $casting = null ) {
		foreach ( $arr as $key => $value ) {
			$func_name = (array) $func_name;
			foreach ( $func_name as $func ) {
				$value = call_user_func( $func, $value );
				if ( ! is_null( $casting ) ) {
					settype( $value, $casting );
				}
			}
			$arr[ $key ] = $value;
		}
		return $arr;
	}

	/**
	 * Check if WooCommerce is active.
	 *
	 * @return boolean
	 */
	public static function is_woocommerce_active() {
		require_once \ABSPATH . 'wp-admin/includes/plugin.php';
		return is_plugin_active( 'woocommerce/woocommerce.php' ) && class_exists( '\WooCommerce' );
	}

	/**
	 * Check if current user is admin.
	 *
	 * @return boolean
	 */
	protected static function is_admin_user() {
		return current_user_can( 'administrator' );
	}

	/**
	 * Handle Enqueue JS and CSS Assets.
	 *
	 * @param array $assets
	 * @return void
	 */
	private function handle_enqueue_assets( $assets ) {
		foreach ( $assets as $asset_file ) {
			// Conditional Tab.
			if ( ! empty( $asset_file['conditional'] ) ) {
				foreach ( $asset_file['conditional'] as $key => $value ) {
					if ( empty( $_GET[ $key ] ) || $value !== sanitize_text_field( wp_unslash( $_GET[ $key ] ) ) ) {
						continue;
					}
				}
			}

			// CSS.
			if ( 'css' === $asset_file['type'] ) {
				// Registered or other Asset.
				if ( empty( $asset_file['url'] ) ) {
					if ( ! wp_script_is( $asset_file['handle'] ) ) {
						wp_enqueue_style( $asset_file['handle'] );
					}
				} else {
					wp_enqueue_style( $asset_file['handle'], $asset_file['url'], ! empty( $asset_file['dependency'] ) ? $asset_file['dependency'] : array(), self::$plugin_info['version'], ! empty( $asset_file['media'] ) ? $asset_file['media'] : 'all' );
				}
			}

			// JS.
			if ( 'js' === $asset_file['type'] ) {
				if ( empty( $asset_file['url'] ) ) {
					if ( ! wp_script_is( $asset_file['handle'] ) ) {
						wp_enqueue_script( $asset_file['handle'] );
					}
				} else {
					wp_enqueue_script( $asset_file['handle'], $asset_file['url'], ! empty( $asset_file['dependency'] ) ? $asset_file['dependency'] : array(), self::$plugin_info['version'], isset( $asset_file['in_footer'] ) ? $asset_file['in_footer'] : true );
				}
				if ( ! empty( $asset_file['localized'] ) ) {
					wp_localize_script( $asset_file['handle'], isset( $asset_file['localized']['name'] ) ? $asset_file['localized']['name'] : str_replace( '-', '_', self::$plugin_info['name'] ), $asset_file['localized']['data'] );
				}
			}
		}
	}

	/**
	 * Install Status Icon.
	 *
	 * @param string $status
	 * @param string $version
	 * @return void
	 */
	public static function install_and_version_icon( $status = 'green', $version = '' ) {
		if ( 'red' === $status ) {
			?>
			<div class="req-status text-end">
				<span class="install-status-icon led-red mx-2 align-middle" style="margin: 0 auto;width: 24px;height: 24px;background-color: #F00;border-radius: 50%;box-shadow: rgb(0 0 0 / 20%) 0 -1px 7px 1px, inset #441313 0 -1px 9px, rgb(255 0 0 / 50%) 0 2px 12px;display: inline-block;"></span>
				<span class="align-middle"><?php echo esc_html( empty( $version ) ? esc_html__( 'Not installed' ) : $version ); ?></span>
			</div>
			<?php
		} elseif ( 'green' === $status ) {
			?>
			<div class="req-status text-end">
				<span class="install-status-icon led-green mx-2 align-middle" style="margin: 0 auto;width: 24px;height: 24px;background-color: #abff00;border-radius: 50%;box-shadow: rgb(0 0 0 / 20%) 0 -1px 7px 1px, inset #304701 0 -1px 9px, #89ff00 0 2px 12px;display: inline-block;"></span>
				<span class="align-middle"><?php echo esc_attr( $version ); ?></span>
			</div>
			<?php
		} elseif ( 'yellow' === $status ) {
			?>
			<div class="req-status text-end">
				<span class="install-status-icon led-green mx-2 align-middle" style="margin: 0 auto;width: 24px;height: 24px;background-color: #fff476;border-radius: 50%;box-shadow: rgb(0 0 0 / 20%) 0 -1px 7px 1px, inset #c0d510 0 -1px 9px, #d0db27 0 2px 12px;display: inline-block;"></span>
				<span class="align-middle"><?php echo esc_attr( $version ); ?></span>
			</div>
			<?php
		}
	}

	/**
	 * Deep Sanitize Field.
	 *
	 * @param array $field
	 * @return array
	 */
	protected static function deep_sanitize_field( $field ) {
		foreach ( $field as $key => $val ) {
			$key = sanitize_text_field( $key );
			if ( is_array( $val ) ) {
				$field[ $key ] = self::deep_sanitize_field( $field[ $key ] );
			} else {
				$field[ $key ] = sanitize_text_field( $val );
			}
		}
		return $field;
	}
}
