<?php

/**
 * 
 */
class Photo_Gallery_Field_Builder {

	function __construct() {

		/* Add templates for our plugin */
		add_action( 'admin_footer', array( $this, 'print_photo_gallery_templates' ) );

	}

	/**
	 * Get an instance of the field builder
	 */
	public static function get_instance() {
		static $inst;
		if ( ! $inst ) {
			$inst = new Photo_Gallery_Field_Builder();
		}
		return $inst;
	}

	public function get_id(){
		global $id, $post;

        // Get the current post ID. If ajax, grab it from the $_POST variable.
        if ( defined( 'DOING_AJAX' ) && DOING_AJAX && array_key_exists( 'post_id', $_POST ) ) {
            $post_id = absint( $_POST['post_id'] );
        } else {
            $post_id = isset( $post->ID ) ? $post->ID : (int) $id;
        }

        return $post_id;
	}

	/**
     * Helper method for retrieving settings values.
     *
     * @since 1.0.0
     *
     * @global int $id        The current post ID.
     * @global object $post   The current post object.
     * @param string $key     The setting key to retrieve.
     * @param string $default A default value to use.
     * @return string         Key value on success, empty string on failure.
     */
    public function get_setting( $key, $default = false ) {

        // Get config
        $settings = get_post_meta( $this->get_id(), 'photo-gallery-settings', true );

        // Check config key exists
        if ( isset( $settings[ $key ] ) ) {
            return $settings[ $key ];
        } else {
            return $default ? $default : '';
        }

    }

	public function render( $metabox, $post = false ) {

		switch ( $metabox ) {
			case 'gallery':
				$this->_render_gallery_metabox();
				break;
			case 'settings':
				$this->_render_settings_metabox();
				break;
			case 'photo-gallery-upgrade-to-pro':
				$this->_render_photo_gallery_upgrade_to_pro_metabox();
				break;
			case 'shortcode':
				$this->_render_shortcode_metabox( $post );
				break;
			default:
				do_action( "photo_gallery_wp_metabox_fields_{$metabox}" );
				break;
		}

	}

	private function _render_photo_gallery_upgrade_to_pro_metabox() {

		?>
		<style>
			#photo-gallery-upgrade-to-pro .hndle{background-color:#0073AA; color:#fff; text-align: center; justify-content: center;}
			#photo-gallery-upgrade-to-pro .postbox-header .handle-actions{
				display: none;
			}
		#photo-gallery-upgrade-to-pro{background:#dbf0fa none repeat scroll 0 0; border:1px solid #0073aa; color:#191e23;}
		.postbox-container .pgbp-list li:before{font-family: dashicons; content: "\f139"; font-size:20px; color: #0073aa; vertical-align: middle;}
		.timline-wp-wrap .pgbp-button-full{display:block; text-align:center; box-shadow:none; border-radius:0;}
		.timline-wp-shortcode-preview{background-color: #e7e7e7; font-weight: bold; padding: 2px 5px; display: inline-block; margin:0 0 2px 0;}
		.photo-gallery-upgrade-to-pro{font-size:18px; text-align:center; margin-bottom:15px;}
		.pgbp-copy-clipboard{-webkit-touch-callout: all; -webkit-user-select: all; -khtml-user-select: all; -moz-user-select: all; -ms-user-select: all; user-select: all;}
		.pgbp-new-feature{ font-size: 10px; margin-left:2px; color: #fff; font-weight: bold; background-color: #03aa29; padding:1px 4px; font-style: normal; }
		.button-orange{background: #ff2700 !important;border-color: #ff2700 !important; font-weight: 600; width: 100%; text-align: center;}
		</style>

								<ul class="pgbp-list">
									<li><?php _e( '30+ cool designs','pg_builder' ); ?></li>
									<li><?php _e( 'Create unlimited Photo Gallery inside your WordPress website.','pg_builder' ); ?></li>
									
									<li><?php _e( 'Hexagone and Diamond Design Photo Gallery','pg_builder' ); ?></li>
									<li><?php _e( 'Grid and Masonry Design Photo Gallery','pg_builder' ); ?></li>
									<li><?php _e( 'Slider Design Photo Gallery','pg_builder' ); ?></li>
									<li><?php _e( 'Filterable Design Photo Gallery','pg_builder' ); ?></li>
									
									<li><?php _e( 'Photo Gallery Filter Category Management – Add photo gallery in specific category.','pg_builder' ); ?></li>
								
									
									<li><?php _e( 'Mobile Compatibility View','pg_builder' ); ?></li>
									
									<li><?php _e( 'Elementor, Beaver and SiteOrigin Page Builder Support.','pg_builder'); ?> <span class="pgbp-new-feature">New</span></li>
									<li><?php _e( 'Divi Page Builder Native Support.','pg_builder'); ?> <span class="pgbp-new-feature">New</span></li>
									
									<li><?php _e( 'WP Templating Features','pg_builder' ); ?></li>
									<li><?php _e( 'Custom CSS','pg_builder' ); ?></li>
									<li><?php _e( 'Fully responsive','pg_builder' ); ?></li>
									<li><?php _e( '500+ Font family','pg_builder' ); ?></li>
									
								</ul>
								<div class="photo-gallery-upgrade-to-pro"><?php echo sprintf( __( 'Gain access to <strong>Photo Gallery Builder Pro </strong>', 'photo-gallery-builder' ) ); ?></div>
								<a class="button button-primary pgbp-button-full button-orange" href="<?php echo PHOTO_GALLERY_BUILDER_UPGRADE; ?>" target="_blank"><?php _e('Buy Now', 'photo-gallery-builder'); ?></a>
			
		<?php

	}

	/* Create HMTL for gallery metabox */
	private function _render_gallery_metabox() {

		$images = get_post_meta( $this->get_id(), 'photo-gallery-images', true );

		$max_upload_size = wp_max_upload_size();
	    if ( ! $max_upload_size ) {
	        $max_upload_size = 0;
	    }

		echo '<div class="container-fluid photo-gallery-uploader-container">';
		echo '<div class="row photo-gallery-upload-actions">';
		echo '<div class="col-lg-7 upload-info-container">';
		echo '<div class="upload-info">';
		echo sprintf( __( '<b><h2>Drag and drop</b> files here (max %s per file), or <b>drag images around to change their order</h2></b>', 'photo-gallery-builder' ), esc_html( size_format( $max_upload_size ) ) );
		echo '</div>';
		echo '<div class="upload-progress">';
		echo '<p class="photo-gallery-upload-numbers">' . esc_html__( 'Uploading image', 'photo-gallery-builder' ) . ' <span class="photo-gallery-current"></span> ' . esc_html__( 'of', 'photo-gallery-builder' ) . ' <span class="photo-gallery-total"></span>';
		echo '<div class="photo-gallery-progress-bar"><div class="photo-gallery-progress-bar-inner"></div></div>';
		echo '</div>';
		echo '</div>';
		echo '<div class="col-lg-5">';
		echo '<a href="#" id="photo-gallery-uploader-browser"  class="btn btn-secondary btn-block">' . esc_html__( 'Upload Image Files', 'photo-gallery-builder' ) . '</a><a href="#" id="photo-wp-gallery"  class="btn btn-primary btn-block">' . esc_html__( 'Select Image from Library', 'photo-gallery-builder' ) . '</a>';
		echo '</div>';
		echo '</div>';
		echo '<div id="photo-gallery-uploader-container" class="row photo-gallery-uploader-inline">';
			echo '<div class="photo-gallery-error-container"></div>';
			echo '<div class="col-sm-12 photo-gallery-uploader-inline-content">';
				echo '<h2 class="photo-gallery-upload-message"><span class="dashicons dashicons-upload"></span>' . esc_html__( 'Drag & Drop files here!', 'photo-gallery-builder' ) . '</h2>';
				echo '<div id="photo-gallery-grid" style="display:none"></div>';
			echo '</div>';
			echo '<div id="photo-gallary-dropzone-container"><div class="photo-gallary-uploader-window-content"><h1>' . esc_html__( 'Drop files to upload', 'photo-gallery-builder' ) . '</h1></div></div>';
		echo '</div>';

		echo '</div>';
	}

	/* Create HMTL for settings metabox */
	private function _render_settings_metabox() {
		$tabs = Photo_Gallery_CPT_Fields_Helper::get_tabs();

		// Sort tabs based on priority.
		uasort( $tabs, array( 'Photo_Gallery_Helper', 'sort_data_by_priority' ) );

		$tabs_html = '';
		$tabs_content_html = '';
		$first = true;

		// Generate HTML for each tab.
		foreach ( $tabs as $tab_id => $tab ) {
			$tab['id'] = $tab_id;
			$tabs_html .= $this->_render_tab( $tab, $first );

			$fields = Photo_Gallery_CPT_Fields_Helper::get_fields( $tab_id );
			// Sort fields based on priority.
			uasort( $fields, array( 'Photo_Gallery_Helper', 'sort_data_by_priority' ) );

			$current_tab_content = '<div id="photo-gallery-wp-' . esc_attr( $tab['id'] ) . '" class="' . ( $first ? 'active-tab' : '' ) . '">';

			// Check if our tab have title & description
			if ( isset( $tab['title'] ) || isset( $tab['description'] ) ) {
				$current_tab_content .= '<div class="tab-content-header">';
				$current_tab_content .= '<div class="tab-content-header-title">';
				if ( isset( $tab['title'] ) && '' != $tab['title'] ) {
					$current_tab_content .= '<h2>' . esc_html( $tab['title'] ) . '</h2>';
				}
				if ( isset( $tab['description'] ) && '' != $tab['description'] ) {
					$current_tab_content .= '<div class="tab-header-tooltip-container photo-gallery-tooltip"><span><i class="fa fa-lightbulb"></i></span>';
					$current_tab_content .= '<div class="tab-header-description photo-gallery-tooltip-content">' . wp_kses_post( $tab['description'] ) . '</div>';
					$current_tab_content .= '</div>';
				}
				$current_tab_content .= '</div>';

				$current_tab_content .= '</div>';
			}

			// Generate all fields for current tab
			$current_tab_content .= '<div class="form-table-wrapper">';
			$current_tab_content .= '<table class="form-table"><tbody>';
			foreach ( $fields as $field_id => $field ) {
				$field['id'] = $field_id;
				$current_tab_content .= $this->_render_row( $field );
			}
			$current_tab_content .= '</tbody></table>';
			// Filter to add extra content to a specific tab
			$current_tab_content .= apply_filters( 'photo_gallery_' . $tab_id . '_tab_content', '' );
			$current_tab_content .= '</div>';
			$current_tab_content .= '</div>';
			$tabs_content_html .= $current_tab_content;

			if ( $first ) {
				$first = false;
			}

		}

		$html = '<div class="photo-gallery-settings-container"><div class="photo-gallery-tabs">%s</div><div class="photo-gallery-tabs-content">%s</div>';
		printf( $html, $tabs_html, $tabs_content_html );
	}

	/* Create HMTL for shortcode metabox */
	private function _render_shortcode_metabox( $post ) {
		$shortcode = '[photo-gallery id="' . $post->ID . '"]';
		echo '<input type="text" style="width:100%;" value="' . esc_attr( $shortcode ) . '"  onclick="select()" readonly>';
		// Add Copy Shortcode button
        echo '<a href="#" class="copy-photo-gallery-shortcode button button-primary" style="margin-top:10px;">'.esc_html__('Copy Shortcode','photo-gallery-builder').'</a><span style="margin-left:15px;"></span>';
	}

	/* Create HMTL for a tab */
	private function _render_tab( $tab, $first = false ) {
		$icon = '';
		$badge = '';

		if ( isset( $tab['icon'] ) ) {
			$icon = '<i class="' . esc_attr( $tab['icon'] ) . '"></i>';
		}

		if ( isset( $tab['badge'] ) ) {
			$badge = '<sup>' . esc_html( $tab['badge'] ) . '</sup>';
		}
		return '<div class="photo-gallery-tab' . ( $first ? ' active-tab' : '' ) . ' photo-gallery-wp-' . esc_attr( $tab['id'] ) . '" data-tab="photo-gallery-wp-' . esc_attr( $tab['id'] ) . '">' . $icon . wp_kses_post( $tab['label'] ) . $badge . '</div>';
	}

	/* Create HMTL for a row */
	private function _render_row( $field ) {
		$format = '<tr data-container="' . esc_attr( $field['id'] ) . '"><th scope="row"><label>%s</label>%s</th><td>%s</td></tr>';

		if ( 'textarea' == $field['type'] || 'custom_code' == $field['type'] ) {
			$format = '<tr data-container="' . esc_attr( $field['id'] ) . '"><td colspan="2"><label class="th-label">%s</label>%s<div>%s</div></td></tr>';
		}

		$format = apply_filters( "photo_gallery_field_type_{$field['type']}_format", $format, $field );

		$default = '';

		// Check if our field have a default value
		if ( isset( $field['default'] ) ) {
			$default = $field['default'];
		}

		// Generate tooltip
		$tooltip = '';
		if ( isset( $field['description'] ) && '' != $field['description'] ) {
			$tooltip .= '<div class="photo-gallery-tooltip"><span><i class="fa fa-lightbulb"></i></span>';
			$tooltip .= '<div class="photo-gallery-tooltip-content">' . wp_kses_post( $field['description'] ) . '</div>';
			$tooltip .= '</div>';
		}

		// Get the current value of the field
		$value = $this->get_setting( $field['id'], $default );
		return sprintf( $format, wp_kses_post( $field['name'] ), $tooltip, $this->_render_field( $field, $value ) );
	}

	/* Create HMTL for a field */
	private function _render_field( $field, $value = '' ) {
		$html = '';

		switch ( $field['type'] ) {
			case 'text':
				$html = '<input type="text" class="col-sm-4 regular-text" name="photo-gallery-settings[' . esc_attr( $field['id'] ) . ']" data-setting="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $value ) . '">';
				break;
			case 'select' :
				$html = '<select name="photo-gallery-settings[' . esc_attr( $field['id'] ) . ']" data-setting="' . esc_attr( $field['id'] ) . '" style="max-width:33%;" class="col-sm-4 regular-text">';
				foreach ( $field['values'] as $key => $option ) {
					if ( is_array( $option ) ) {
						$html .= '<optgroup label="' . esc_attr( $key ) . '">';
						foreach ( $option as $key_subvalue => $subvalue ) {
							$html .= '<option value="' . esc_attr( $key_subvalue ) . '" ' . selected( $key_subvalue, $value, false ) . '>' . esc_html( $subvalue ) . '</option>';
						}
						$html .= '</optgroup>';
					}else{
						$html .= '<option value="' . esc_attr( $key ) . '" ' . selected( $key, $value, false ) . '>' . esc_html( $option ) . '</option>';
					}
				}
				if ( isset( $field['disabled'] ) && is_array( $field['disabled'] ) ) {
					$html .= '<optgroup label="' . esc_attr( $field['disabled']['title'] ) . '">';
					foreach ( $field['disabled']['values'] as $key => $disabled ) {
						$html .= '<option value="' . esc_attr( $key ) . '" disabled >' . esc_html( $disabled ) . '</option>';
					}
					$html .= '</optgroup>';
				}
				$html .= '</select>';
				break;

			case 'text_short':
				global $id, $post;
       			$post_id = isset( $post->ID ) ? $post->ID : (int) $id;

				$shortcode = '[photo-gallery id="' . $post_id . '"]';
				
				$html = '<input type="text" class="col-sm-10 regular-text" style="width:100%;padding:15px;background-color: #f5e293;" onclick="select()" name="accordion-slider-settings[' . esc_attr( $field['id'] ) . ']" data-setting="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $shortcode ) . '" readonly>';
				break;

			case 'text_php':

				
				global $id, $post;
       			$post_id = isset( $post->ID ) ? $post->ID : (int) $id;

				$shortcode = '[photo-gallery id="' . $post_id . '"]';
				$value = "<?php echo do_shortcode('$shortcode'); ?>";

				$html = '<input type="text" class="col-sm-10 regular-text" style="width:100%;padding:15px;background-color: #f5e293;" onclick="select()" name="accordion-slider-settings[' . esc_attr( $field['id'] ) . ']" data-setting="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $value ) . '" readonly>';
				break;

			case 'ui-slider':
				$min  = isset( $field['min'] ) ? $field['min'] : 0;
				$max  = isset( $field['max'] ) ? $field['max'] : 100;
				$step = isset( $field['step'] ) ? $field['step'] : 1;
				if ( '' === $value ) {
					if ( isset( $field['default'] ) ) {
						$value = $field['default'];
					}else{
						$value = $min;
					}
				}
				$attributes = 'data-min="' . esc_attr( $min ) . '" data-max="' . esc_attr( $max ) . '" data-step="' . esc_attr( $step ) . '"';
				$html .= '<div class="col-sm-4 slider-container photo-gallery-ui-slider-container">';
					$html .= '<div id="slider_' . esc_attr( $field['id'] ) . '" class="ss-slider photo-gallery-ui-slider"></div>';
					$html .= '<input readonly="readonly" data-setting="' . esc_attr( $field['id'] ) . '"  name="photo-gallery-settings[' . esc_attr( $field['id'] ) . ']" type="text" class="col-sm-4 rl-slider photo-gallery-ui-slider-input" id="input_' . esc_attr( $field['id'] ) . '" value="' . $value . '" ' . $attributes . '/>';
				$html .= '</div>';
				break;
			case 'color' :
				$html .= '<div class="photo-gallery-colorpickers">';
				$html .= '<input id="' . esc_attr( $field['id'] ) . '" class="col-sm-4 photo-gallery-color" data-setting="' . esc_attr( $field['id'] ) . '" name="photo-gallery-settings[' . esc_attr( $field['id'] ) . ']" value="' . esc_attr( $value ) . '">';
				$html .= '</div>';
				break;
			case "toggle":
				$html .= '<div class="photo-gallery-toggle">';
					$html .= '<input class="photo-gallery-toggle__input" type="checkbox" data-setting="' . esc_attr( $field['id'] ) . '" id="' . esc_attr( $field['id'] ) . '" name="photo-gallery-settings[' . esc_attr( $field['id'] ) . ']" value="1" ' . checked( 1, $value, false ) . '>';
					$html .= '<div class="photo-gallery-toggle__items">';
						$html .= '<span class="photo-gallery-toggle__track"></span>';
						$html .= '<span class="photo-gallery-toggle__thumb"></span>';
						$html .= '<svg class="photo-gallery-toggle__off" width="6" height="6" aria-hidden="true" role="img" focusable="false" viewBox="0 0 6 6"><path d="M3 1.5c.8 0 1.5.7 1.5 1.5S3.8 4.5 3 4.5 1.5 3.8 1.5 3 2.2 1.5 3 1.5M3 0C1.3 0 0 1.3 0 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3z"></path></svg>';
						$html .= '<svg class="photo-gallery-toggle__on" width="2" height="6" aria-hidden="true" role="img" focusable="false" viewBox="0 0 2 6"><path d="M0 0h2v6H0z"></path></svg>';
					$html .= '</div>';
				$html .= '</div>';
				break;
			case "custom_code":
				$html = '<div class="photo-gallery-code-editor" data-syntax="' . esc_attr( $field['syntax'] ) . '">';
				$html .= '<textarea data-setting="' . esc_attr( $field['id'] ) . '" name="photo-gallery-settings[' . esc_attr( $field['id'] ) . ']" id="photo-gallery-wp-' . esc_attr( $field['id'] ) . '" class="large-text code"  rows="10" cols="50">' . wp_kses_post($value) . '</textarea>';
				$html .= '</div>';
				break;
			
			default:
				/* Filter for render custom field types */
				$html = apply_filters( "photo_gallery_render_{$field['type']}_field_type", $html, $field, $value );
				break;
		}

		return $html;

	}

	public function print_photo_gallery_templates() { 
		include 'photo-gallery-js-templates.php';
	}

}
?>
