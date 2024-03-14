<?php

class EasyOptInsLayout {
	public $layout_number;
	public $layout_type;
	public $layout_class;
	public $layout_id;

	private $plugin_dir;
	private $plugin_url;

	public static function uses_new_css() {
		return true;
	}

	public function __construct( $layout_id ) {
		$this->plugin_dir = FCA_EOI_PLUGIN_DIR;
		$this->plugin_url = FCA_EOI_PLUGIN_URL . '/';

		list( $layout_type, $layout_number ) = explode( '_', $layout_id );

		$this->layout_number = (int) $layout_number;
		$this->layout_type   = $layout_type == 'layout' ? 'widget' : $layout_type;
		$this->layout_class  = self::generate_layout_class( $this->layout_type );
		$this->layout_id     = $layout_id;
	}

	public function layout_name( $id = '') {
		
		if ( empty ( $id ) ) {
			$id = $this->layout_id;
		}
		
		$layout_names = array(
			0 => __('No CSS', 'easy-opt-ins'),
			1 => __('Classic', 'easy-opt-ins'),
			2 => __('Ribbon', 'easy-opt-ins'),
			3 => __('Chevron', 'easy-opt-ins'),
			4 => __('Modern', 'easy-opt-ins'),
			5 => __('Light', 'easy-opt-ins'),
			6 => __('Dark', 'easy-opt-ins'),
			7 => __('Natural', 'easy-opt-ins'),
			8 => __('Elegant', 'easy-opt-ins'),
			9 => __('Bubble', 'easy-opt-ins'),
			10 => __( 'Optin Bar Blue', 'easy-opt-ins'),
			11 => __( 'Optin Bar Orange', 'easy-opt-ins'),
			12 => __( 'Optin Bar White', 'easy-opt-ins'),
			13 => __( 'Slide In Light', 'easy-opt-ins'),
			14 => __( 'Slide In Dark', 'easy-opt-ins'),
			15 => __( 'Rounded', 'easy-opt-ins'),
			16 => __( 'Flat', 'easy-opt-ins'),
			17 => __( 'Content Upgrade', 'easy-opt-ins'),
			18 => __( 'Image', 'easy-opt-ins'),
			19 => __( 'Padded Image', 'easy-opt-ins'),
			20 => __( 'Wide Image', 'easy-opt-ins'),
			21 => __( 'Content Upgrade - Image', 'easy-opt-ins'),
			22 => __( 'Content Upgrade - Wide Image', 'easy-opt-ins'),
		);
		
		return $layout_names[ $this->layout_number( $id ) ];
	}
	
	public function layout_number( $id = '') {
		
		if ( empty ( $id ) ) {
			$id = $this->layout_id;
		}
		
		return preg_replace( '/[^0-9]/', '', $id );
		
	}
	
	public function screenshot_src( $id = '') {
		
		if ( empty ( $id ) ) {
			$id = $this->layout_id;
		}
		
		if ( $this->layout_type === 'widget' ) {
			$id = str_replace( 'widget', 'layout', $id );
		}
		
		if ( file_exists( FCA_EOI_PLUGIN_DIR . "/layouts/screenshots/$id.png" ) ) {
			return FCA_EOI_PLUGIN_URL . "/layouts/screenshots/$id.png";
		}

		return FCA_EOI_PLUGIN_URL . "/assets/admin/no_image.png";
	}	
	
	public function layout_enabled() {
		
		$path = FCA_EOI_PLUGIN_DIR . '/layouts/' . $this->layout_type . '/' . $this->layout_id;
		
		if ( $this->layout_type === 'widget' ) {
			$path = FCA_EOI_PLUGIN_DIR . '/layouts/' . $this->layout_type . '/layout_' . $this->layout_number;
		}
		
		return file_exists( $path );
		
	}
	
	public function layout_order( $id = '') {
		
		if ( empty ( $id ) ) {
			$id = $this->layout_id;
		}
		
		//LAYOUT ID => LAYOUT ORDER
		$layout_order = array(
			16, // __( 'Flat', 'easy-opt-ins'),
			15, // __( 'Rounded', 'easy-opt-ins'),
			2, // __('Ribbon', 'easy-opt-ins'),
			5, // __('Light', 'easy-opt-ins'),
			1, // __('Classic', 'easy-opt-ins'),
			9, // __('Bubble', 'easy-opt-ins'),
			18, // __( 'Image', 'easy-opt-ins'),
			19, // __( 'Padded Image', 'easy-opt-ins'),
			20, // __( 'Wide Image', 'easy-opt-ins'),
			17, // __( 'Content Upgrade', 'easy-opt-ins'),
			21, // __( 'Content Upgrade - Image', 'easy-opt-ins'),
			22, // __( 'Content Upgrade - Wide Image', 'easy-opt-ins'),
			3, // __('Chevron', 'easy-opt-ins'),
			4, // __('Modern', 'easy-opt-ins'),
			6, // __('Dark', 'easy-opt-ins'),
			7, // __('Natural', 'easy-opt-ins'),
			8, // __('Elegant', 'easy-opt-ins'),
			10, // __( 'Optin Bar Blue', 'easy-opt-ins'),
			11, // __( 'Optin Bar Orange', 'easy-opt-ins'),
			12, // __( 'Optin Bar White', 'easy-opt-ins'),
			13, // __( 'Slide In Light', 'easy-opt-ins'),
			14, // __( 'Slide In Dark', 'easy-opt-ins'),
			0, //__('No CSS', 'easy-opt-ins'),
		);
		
		return array_search ( $this->layout_number( $id ), $layout_order );
		
	}
	
	public function path_to_html_wrapper() {
		return $this->plugin_dir . $this->common_path() . $this->layout_type . '.html';
	}

	public function path_to_resource( $resource_name, $resource_type ) {
		return $this->plugin_dir . $this->subpath_to_resource( $resource_name, $resource_type );
	}

	public function url_to_resource( $resource_name, $resource_type ) {
		return $this->plugin_url . $this->subpath_to_resource( $resource_name, $resource_type );
	}

	private static function generate_layout_class( $layout_type ) {
		if ( $layout_type == 'lightbox' ) {
			return 'fca_eoi_layout_popup';
		} elseif ( $layout_type == 'postbox' ) {
			return 'fca_eoi_layout_postbox';
		} elseif ( $layout_type == 'widget' ) {
			return 'fca_eoi_layout_widget';
		} elseif ( $layout_type == 'banner' ) {
			return 'fca_eoi_layout_banner';
		} elseif ( $layout_type == 'overlay' ) {
			return 'fca_eoi_layout_overlay';
		}
		return '';
	}

	private function subpath_to_resource( $resource_name, $resource_type ) {
		if ( self::uses_new_css() ) {
			if ( $resource_name == 'layout' && $resource_type == 'html' ) {
				$new_path =
					$this->common_path() .
					'layout_' . $this->layout_number . '/' .
					$resource_name . '.' . $resource_type;

				if ( file_exists( $this->plugin_dir . $new_path ) ) {
					return $new_path;
				}
			}

			$new_path = $this->subpath() . $resource_name . '-new.' . $resource_type;
			if ( file_exists( $this->plugin_dir . $new_path ) ) {
				return $new_path;
			}
		}

		$path = $this->subpath();

		if ( $resource_type == 'scss' ) {
			$resource_type = 'css';
		}

		return $path . $resource_name . '.' . $resource_type;
	}

	private function subpath() {
		return 'layouts/' . $this->layout_type . '/' . $this->layout_id . '/';
	}

	private function common_path() {
		return 'layouts/common/';
	}
}
