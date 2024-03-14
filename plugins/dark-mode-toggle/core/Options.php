<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Options service class.
 *
 * @package Dark_Mode_Toggle
 */

namespace DarkModeToggle;

defined( 'ABSPATH' ) || die();

/**
 * Register and enqueue front styles and scripts.
 */
class Options extends Base implements Service {
	use EmptyServiceInit;

	/**
	 * Option name for main options.
	 *
	 * @var string
	 */
	protected $key_main;

	/**
	 * Default values for main options.
	 *
	 * @var array
	 */
	protected $default_main;

	/**
	 * Values for main options.
	 *
	 * @var array
	 */
	protected $options_main;

	/**
	 * Initialize properties.
	 */
	public function __construct() {
		$this->key_main = apply_filters( 'darkmodetg_option_name_main', 'darkmodetg' );
	}

	/**
	 * Get option value based on an option name.
	 *
	 * @param string $option Name of the option to retrieve.
	 * @param mixed  $default Default value to return if the option does not exist.
	 *
	 * @return mixed
	 */
	public function get_option( $option, $default = false ) {
		return \get_option( $option, $default );
	}

	/**
	 * Update option value.
	 *
	 * @param string $option Name of the option to update.
	 * @param mixed  $value Option value.
	 */
	public function save( $option, $value ) {
		return \update_option( $option, $value );
	}

	/**
	 * Delete option by name.
	 *
	 * @param string $option Name of the option to delete.
	 *
	 * @return bool
	 */
	public function delete( $option ) {
		return \delete_option( $option );
	}

	/**
	 * Get capability to update main options.
	 *
	 * @return string
	 */
	public function cap_main() {
		return 'edit_theme_options';
	}

	/**
	 * Get option name for main options.
	 *
	 * @return string
	 */
	public function get_key_main() {
		return $this->key_main;
	}

	/**
	 * Get default values for main options.
	 *
	 * @param string $key Key name.
	 *
	 * @return array
	 */
	public function get_default_main( $key = '' ) {
		$this->set_default_main();

		if ( $key ) {
			return $this->default_main[ $key ];
		}

		return $this->default_main;
	}

	/**
	 * Set default values for main options.
	 */
	public function set_default_main() {
		if ( ! isset( $this->default_main ) ) {
			$this->default_main = apply_filters(
				'darkmodetg_default_main_options',
				array(
					'front'    => array(
						'enable'         => true,
						'pos'            => 'bl',
						'pos_b'          => 32,
						'pos_t'          => 32,
						'pos_l'          => 32,
						'pos_r'          => 32,
						'width'          => 44,
						'height'         => 44,
						'border_r'       => 44,
						'in_head'        => false,
						'fix_flick'      => true,
						'flick_bg_color' => '#000000',
						'skip_bg_img'    => true,
					),
					'advanced' => array(
						'time'   => 0,
						'hide_m' => false,
						'save'   => true,
						'front'  => array(
							'override' => false,
						),
					),
				)
			);
		}
	}

	/**
	 * Get main options.
	 *
	 * @param string $key Key name.
	 *
	 * @return array
	 */
	public function get_main( $key = '' ) {
		$this->set_main( $key );

		if ( $key ) {
			return $this->options_main[ $key ];
		}

		return $this->options_main;
	}

	/**
	 * Set values for main options.
	 *
	 * @param string $key Key name.
	 */
	public function set_main( $key ) {
		if ( ! isset( $this->options_main ) ) {
			$this->options_main = $this->get_option( $this->key_main );

			if ( ! is_array( $this->options_main ) ) {
				$this->options_main = array();
			}

			$this->options_main = darkmodetg( 'utility' )->parse_args_r( $this->options_main, $this->get_default_main() );

			if ( $key ) {
				$method = ( 'set_main_' . $key );
				if ( \method_exists( $this, $method ) ) {
					$this->$method();
				}
			} else {
				$this->set_main_front();
				$this->set_main_advanced();
			}
		}
	}

	/**
	 * Set values for main options front key.
	 */
	public function set_main_front() {
		$key = 'front';

		$this->options_main[ $key ]['enable']         = (bool) $this->options_main[ $key ]['enable'];
		$this->options_main[ $key ]['pos']            = $this->sanitize_pos( $this->options_main[ $key ]['pos'] );
		$this->options_main[ $key ]['pos_b']          = (int) ( $this->options_main[ $key ]['pos_b'] );
		$this->options_main[ $key ]['pos_l']          = (int) ( $this->options_main[ $key ]['pos_l'] );
		$this->options_main[ $key ]['pos_t']          = (int) ( $this->options_main[ $key ]['pos_t'] );
		$this->options_main[ $key ]['pos_r']          = (int) ( $this->options_main[ $key ]['pos_r'] );
		$this->options_main[ $key ]['width']          = absint( $this->options_main[ $key ]['width'] );
		$this->options_main[ $key ]['height']         = absint( $this->options_main[ $key ]['height'] );
		$this->options_main[ $key ]['border_r']       = absint( $this->options_main[ $key ]['border_r'] );
		$this->options_main[ $key ]['in_head']        = (bool) $this->options_main[ $key ]['in_head'];
		$this->options_main[ $key ]['fix_flick']      = (bool) $this->options_main[ $key ]['fix_flick'];
		$this->options_main[ $key ]['flick_bg_color'] = sanitize_hex_color( $this->options_main[ $key ]['flick_bg_color'] );
		$this->options_main[ $key ]['skip_bg_img']    = (bool) $this->options_main[ $key ]['skip_bg_img'];
	}

	/**
	 * Set values for main options advanced key.
	 */
	public function set_main_advanced() {
		$key = 'advanced';

		$this->options_main[ $key ]['time']   = abs( (float) $this->options_main[ $key ]['time'] );
		$this->options_main[ $key ]['hide_m'] = (bool) $this->options_main[ $key ]['hide_m'];
		$this->options_main[ $key ]['save']   = (bool) $this->options_main[ $key ]['save'];

		$this->options_main[ $key ]['front']['override'] = (bool) $this->options_main[ $key ]['front']['override'];
	}

	/**
	 * Update main options.
	 *
	 * @param string $key Key name.
	 * @param mixed  $value Option value.
	 */
	public function save_main( $key, $value ) {
		$values         = $this->get_main();
		$values[ $key ] = $value;

		$this->save( $this->key_main, $values );
	}

	/**
	 * Sanitize position value.
	 *
	 * @param string $pos Position value.
	 *
	 * @return string
	 */
	public function sanitize_pos( $pos ) {
		if ( \in_array( $pos, array( 'bl', 'br', 'tl', 'tr' ), true ) ) {
			return $pos;
		}

		return $this->get_default_main( 'front' )['pos'];
	}
}
