<?php

/**
 * 
 */
class Timline_WP_Field_Builder {

	function __construct() { }

	/**
	 * Get an instance of the field builder
	 */
	public static function get_instance() {
		static $inst;
		if ( ! $inst ) {
			$inst = new Timline_WP_Field_Builder();
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
        $settings = get_post_meta( $this->get_id(), 'timeline-wp-settings', true );

        // Check config key exists
        if ( isset( $settings[ $key ] ) ) {
            return $settings[ $key ];
        } else {
            return $default ? $default : '';
        }

    }

	public function render( $metabox, $post = false ) {

		switch ( $metabox ) {
			case 'builder':
				$this->_render_builder_metabox();
				break;
			case 'settings':
				$this->_render_settings_metabox();
				break;
			case 'upgrade-to-pro':
				$this->_render_upgrade_to_pro_metabox();
				break;
			case 'shortcode':
				$this->_render_shortcode_metabox( $post );
				break;
			default:
				do_action( "timeline_wp_metabox_fields_{$metabox}" );
				break;
		}

	}

	/* Create HMTL for builder metabox */
	private function _render_builder_metabox() {
		
		$images = get_post_meta( $this->get_id(), 'timeline-wp-data', true );
		$helper_guidelines = $this->get_setting( 'helpergrid' );
		
		$max_upload_size = wp_max_upload_size();
	    if ( ! $max_upload_size ) {
			$max_upload_size = 0;
		}
		require dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'timeline-wp-build.php';
				
        return;
		
		echo '<div class="timeline-wp-uploader-container">';
		echo '<div class="timeline-wp-upload-actions">';
		echo '<div class="upload-info-container">';
		echo '<div class="upload-info">';
		echo sprintf( __( '<b>Drag and drop</b> files here (max %s per file), or <b>drag images around to change their order</b>', timeline_wp ), esc_html( size_format( $max_upload_size ) ) );
		echo '</div>';
		echo '<div class="upload-progress">';
		echo '<p class="timeline-wp-upload-numbers">' . esc_html__( 'Uploading image', timeline_wp ) . ' <span class="timeline-wp-current"></span> ' . esc_html__( 'of', timeline_wp ) . ' <span class="timeline-wp-total"></span>';
		echo '<div class="timeline-wp-progress-bar"><div class="timeline-wp-progress-bar-inner"></div></div>';
		echo '</div>';
		echo '</div>';
		echo '<div class="buttons">';
		echo '<a href="#" id="timeline-wp-uploader-browser" class="button">' . esc_html__( 'Upload image files', timeline_wp ) . '</a><a href="#" id="timeline-wp-wp-gallery" class="button button-primary button-timeline">' . esc_html__( 'Select from Library', timeline_wp ) . '</a>';
		echo '</div>';
		echo '</div>';
		echo '<div id="timeline-wp-uploader-container" class="timeline-wp-uploader-inline">';
			echo '<div class="timeline-wp-error-container"></div>';
			echo '<div class="timeline-wp-uploader-inline-content">';
				echo '<h2 class="timeline-wp-upload-message"><span class="dashicons dashicons-upload"></span>' . esc_html__( 'Drag & Drop files here!', timeline_wp ) . '</h2>';
				echo '<div id="timeline-wp-grid" style="display:none"></div>';
			echo '</div>';
			echo '<div id="timeline-wp-dropzone-container"><div class="timeline-wp-uploader-window-content"><h1>' . esc_html__( 'Drop files to upload', timeline_wp ) . '</h1></div></div>';
		echo '</div>';

		// Helper Guildelines Toggle
		echo '<div class="timeline-wp-helper-guidelines-container">';

			do_action( 'timeline_wp_before_helper_grid' );

			echo '<div class="timeline-wp-toggle timeline-wp-helper-guidelines-wrapper">';
				echo '<input class="timeline-wp-toggle__input" type="checkbox" id="timeline-wp-helper-guidelines" name="timeline-wp-settings[helpergrid]" data-setting="timeline-wp-helper-guidelines" value="1" ' . checked( 1, $helper_guidelines, false ) . '>';
				echo '<div class="timeline-wp-toggle__items">';
					echo '<span class="timeline-wp-toggle__track"></span>';
					echo '<span class="timeline-wp-toggle__thumb"></span>';
					echo '<svg class="timeline-wp-toggle__off" width="6" height="6" aria-hidden="true" role="img" focusable="false" viewBox="0 0 6 6"><path d="M3 1.5c.8 0 1.5.7 1.5 1.5S3.8 4.5 3 4.5 1.5 3.8 1.5 3 2.2 1.5 3 1.5M3 0C1.3 0 0 1.3 0 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3z"></path></svg>';
					echo '<svg class="timeline-wp-toggle__on" width="2" height="6" aria-hidden="true" role="img" focusable="false" viewBox="0 0 2 6"><path d="M0 0h2v6H0z"></path></svg>';
				echo '</div>';
				echo '<strong class="timeline-wp-helper-guidelines-label">' . esc_html__( 'Disable Helper Grid', timeline_wp ) . '</strong>';
			echo '</div>';
		
		do_action( 'timeline_wp_after_helper_grid' );

		echo '</div>';

		echo '</div>';
	}

	/* Create HMTL for settings metabox */

	private function _render_upgrade_to_pro_metabox() {

		?>
		<style>
			#timeline-wp-upgrade-to-pro .hndle{background-color:#0073AA; color:#fff; text-align: center; justify-content: center;}
			#timeline-wp-upgrade-to-pro .postbox-header .handle-actions{
				display: none;
			}
		#timeline-wp-upgrade-to-pro{background:#dbf0fa none repeat scroll 0 0; border:1px solid #0073aa; color:#191e23;}
		.postbox-container .teh-list li:before{font-family: dashicons; content: "\f139"; font-size:20px; color: #0073aa; vertical-align: middle;}
		.timline-wp-wrap .teh-button-full{display:block; text-align:center; box-shadow:none; border-radius:0;}
		.timline-wp-shortcode-preview{background-color: #e7e7e7; font-weight: bold; padding: 2px 5px; display: inline-block; margin:0 0 2px 0;}
		.upgrade-to-pro{font-size:18px; text-align:center; margin-bottom:15px;}
		.teh-copy-clipboard{-webkit-touch-callout: all; -webkit-user-select: all; -khtml-user-select: all; -moz-user-select: all; -ms-user-select: all; user-select: all;}
		.teh-new-feature{ font-size: 10px; margin-left:2px; color: #fff; font-weight: bold; background-color: #03aa29; padding:1px 4px; font-style: normal; }
		.button-orange{background: #ff2700 !important;border-color: #ff2700 !important; font-weight: 600; width: 100%; text-align: center;}
		</style>

								<ul class="teh-list">
									<li><?php _e( '17+ cool designs', timeline_wp ); ?></li>
									<li><?php _e( 'Create unlimited timeline stories inside your WordPress website or blog.', timeline_wp ); ?></li>
									<li><?php _e( 'Use via 1 Shortcode and adding 17+ Designs', timeline_wp ); ?></li>
									<li><?php _e( 'Vertical and Horizontal Timeline', timeline_wp ); ?></li>
									<li><?php _e( 'Also work with WordPress POST', timeline_wp ); ?></li>
									<li><?php _e( 'Timeline Category Management – Add stories in specific category.', timeline_wp ); ?></li>
									<li><?php _e( 'Timeline Stories Content Format – Add font awesome icon to display timeline stories format.', timeline_wp ); ?></li>
									<li><?php _e( 'Timeline Scrolling Navigation – Quickly and easily navigate your timeline with a beautiful scrolling navigation inside your timeline.', timeline_wp ); ?></li>
									<li><?php _e( 'Mobile Compatibility View', timeline_wp ); ?></li>
									<li><?php _e( 'Gutenberg Block Supports.', timeline_wp); ?></li>
									
									<li><?php _e( 'Elementor, Beaver and SiteOrigin Page Builder Support.', timeline_wp); ?> <span class="teh-new-feature">New</span></li>
									<li><?php _e( 'Divi Page Builder Native Support.', timeline_wp); ?> <span class="teh-new-feature">New</span></li>
									
									<li><?php _e( 'WP Templating Features', timeline_wp ); ?></li>
									<li><?php _e( 'Custom CSS', timeline_wp ); ?></li>
									<li><?php _e( 'Fully responsive', timeline_wp ); ?></li>
									
								</ul>
								<div class="upgrade-to-pro"><?php echo sprintf( __( 'Gain access to <strong>Timeline Event History Pro </strong>', timeline_wp ) ); ?></div>
								<a class="button button-primary teh-button-full button-orange" href="<?php echo TIMELINE_WP_PLUGIN_UPGRADE; ?>" target="_blank"><?php _e('Buy Now', timeline_wp); ?></a>
			
		<?php

	}

	private function _render_settings_metabox() {
		$tabs = Timline_WP_CPT_Fields_Helper::get_tabs();

		// Sort tabs based on priority.
		uasort( $tabs, array( 'Timline_WP_Helper', 'sort_data_by_priority' ) );

		$tabs_html = '';
		$tabs_content_html = '';
		$first = true;

		// Generate HTML for each tab.
		foreach ( $tabs as $tab_id => $tab ) {
			$tab['id'] = $tab_id;
			$tabs_html .= $this->_render_tab( $tab, $first );

			$fields = Timline_WP_CPT_Fields_Helper::get_fields( $tab_id );
			// Sort fields based on priority.
			uasort( $fields, array( 'Timline_WP_Helper', 'sort_data_by_priority' ) );

			$current_tab_content = '<div id="timeline-wp-' . esc_attr( $tab['id'] ) . '" class="' . ( $first ? 'active-tab' : '' ) . '">';

			// Check if our tab have title & description
			if ( isset( $tab['title'] ) || isset( $tab['description'] ) ) {
				$current_tab_content .= '<div class="tab-content-header">';
				$current_tab_content .= '<div class="tab-content-header-title">';
				if ( isset( $tab['title'] ) && '' != $tab['title'] ) {
					$current_tab_content .= '<h2>' . esc_html( $tab['title'] ) . '</h2>';
				}
				if ( isset( $tab['description'] ) && '' != $tab['description'] ) {
					$current_tab_content .= '<div class="tab-header-tooltip-container timeline-wp-tooltip"><span>[?]</span>';
					$current_tab_content .= '<div class="tab-header-description timeline-wp-tooltip-content">' . wp_kses_post( $tab['description'] ) . '</div>';
					$current_tab_content .= '</div>';
				}
				$current_tab_content .= '</div>';

				$current_tab_content .= '<div class="tab-content-header-actions">';
				$current_tab_content .= '<a href="https://blogwpthemes.com/docs/timeline-event-history-plugin-documentation/
" target="_blank" class="">' . esc_html__( 'Documentation', timeline_wp ) . '</a>';
				$current_tab_content .= '<span> - or - </span>';
				$current_tab_content .= '<a href="https://blogwpthemes.com/contact/" target="_blank" class="">' . esc_html__( 'Get in touch', timeline_wp ) . '</a>';
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
			$current_tab_content .= apply_filters( 'timeline_wp_' . $tab_id . '_tab_content', '' );
			$current_tab_content .= '</div>';
			$current_tab_content .= '</div>';
			$tabs_content_html .= $current_tab_content;

			if ( $first ) {
				$first = false;
			}

		}

		$html = '<div class="timeline-wp-settings-container"><div class="timeline-wp-tabs">%s</div><div class="timeline-wp-tabs-content">%s</div>';
		printf( $html, $tabs_html, $tabs_content_html );
	}

	/* Create HMTL for shortcode metabox */
	private function _render_shortcode_metabox( $post ) {
		$shortcode = '[timeline-wp id="' . $post->ID . '"]';
		echo '<input type="text" style="width:100%;" value="' . esc_attr( $shortcode ) . '"  onclick="select()" readonly>';
		// Add Copy Shortcode button
        echo '<a href="#" id="copy-timeline-wp-shortcode" class="button button-primary button-timeline">'.esc_html__('Copy shortcode',timeline_wp).'</a><span style="margin-left:15px;"></span>';
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
		return '<div class="timeline-wp-tab' . ( $first ? ' active-tab' : '' ) . ' timeline-wp-' . esc_attr( $tab['id'] ) . '" data-tab="timeline-wp-' . esc_attr( $tab['id'] ) . '">' . $icon . wp_kses_post( $tab['label'] ) . $badge . '</div>';
	}

	/* Create HMTL for a row */
	private function _render_row( $field ) {
		$format = '<tr data-container="' . esc_attr( $field['id'] ) . '"><th scope="row"><label>%s</label>%s</th><td>%s</td></tr>';

		if ( 'textarea' == $field['type'] || 'custom_code' == $field['type'] ) {
			$format = '<tr data-container="' . esc_attr( $field['id'] ) . '"><td colspan="2"><label class="th-label">%s</label>%s<div>%s</div></td></tr>';
		}

		$format = apply_filters( "timeline_wp_field_type_{$field['type']}_format", $format, $field );

		$default = '';

		// Check if our field have a default value
		if ( isset( $field['default'] ) ) {
			$default = $field['default'];
		}

		// Generate tooltip
		$tooltip = '';
		if ( isset( $field['description'] ) && '' != $field['description'] ) {
			$tooltip .= '<div class="timeline-wp-tooltip"><span>[?]</span>';
			$tooltip .= '<div class="timeline-wp-tooltip-content">' . wp_kses_post( $field['description'] ) . '</div>';
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
				$html = '<input type="text" class="regular-text" name="timeline-wp-settings[' . esc_attr( $field['id'] ) . ']" data-setting="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $value ) . '">';
				break;
			case 'select' :
				$html = '<select name="timeline-wp-settings[' . esc_attr( $field['id'] ) . ']" data-setting="' . esc_attr( $field['id'] ) . '" class="regular-text">';
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
				$html .= '<div class="slider-container timeline-wp-ui-slider-container">';
					$html .= '<input readonly="readonly" data-setting="' . esc_attr( $field['id'] ) . '"  name="timeline-wp-settings[' . esc_attr( $field['id'] ) . ']" type="text" class="rl-slider timeline-wp-ui-slider-input" id="input_' . esc_attr( $field['id'] ) . '" value="' . $value . '" ' . $attributes . '/>';
					$html .= '<div id="slider_' . esc_attr( $field['id'] ) . '" class="ss-slider timeline-wp-ui-slider"></div>';
				$html .= '</div>';
				break;
			case 'color' :
				$html .= '<div class="timeline-wp-colorpickers">';
				$html .= '<input id="' . esc_attr( $field['id'] ) . '" class="timeline-wp-color" data-setting="' . esc_attr( $field['id'] ) . '" name="timeline-wp-settings[' . esc_attr( $field['id'] ) . ']" value="' . esc_attr( $value ) . '">';
				$html .= '</div>';
				break;
			case 'text_short':
			global $id, $post;
   				$post_id = isset( $post->ID ) ? $post->ID : (int) $id;

				$shortcode = '[timeline_wp id="' . $post_id . '"]';
			
				$html = '<input type="text" class="col-sm-10 regular-text" style="width:100%;padding:15px;background-color: #f5e293;" onclick="select()" name="timeline-wp-settings[' . esc_attr( $field['id'] ) . ']" data-setting="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $shortcode ) . '" readonly>';
			break;
			case "toggle":
				$html .= '<div class="timeline-wp-toggle">';
					$html .= '<input class="timeline-wp-toggle__input" type="checkbox" data-setting="' . esc_attr( $field['id'] ) . '" id="' . esc_attr( $field['id'] ) . '" name="timeline-wp-settings[' . esc_attr( $field['id'] ) . ']" value="1" ' . checked( 1, $value, false ) . '>';
					$html .= '<div class="timeline-wp-toggle__items">';
						$html .= '<span class="timeline-wp-toggle__track"></span>';
						$html .= '<span class="timeline-wp-toggle__thumb"></span>';
						$html .= '<svg class="timeline-wp-toggle__off" width="6" height="6" aria-hidden="true" role="img" focusable="false" viewBox="0 0 6 6"><path d="M3 1.5c.8 0 1.5.7 1.5 1.5S3.8 4.5 3 4.5 1.5 3.8 1.5 3 2.2 1.5 3 1.5M3 0C1.3 0 0 1.3 0 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3z"></path></svg>';
						$html .= '<svg class="timeline-wp-toggle__on" width="2" height="6" aria-hidden="true" role="img" focusable="false" viewBox="0 0 2 6"><path d="M0 0h2v6H0z"></path></svg>';
					$html .= '</div>';
				$html .= '</div>';
				break;
			case "custom_code":
				$html = '<div class="timeline-wp-code-editor" data-syntax="' . esc_attr( $field['syntax'] ) . '">';
				$html .= '<textarea data-setting="' . esc_attr( $field['id'] ) . '" name="timeline-wp-settings[' . esc_attr( $field['id'] ) . ']" id="timeline-wp-' . esc_attr( $field['id'] ) . '" class="large-text code"  rows="10" cols="50">' . wp_kses_post($value) . '</textarea>';
				$html .= '</div>';
				break;
			case "hover-effect":
				$hovers = apply_filters( 'timeline_wp_available_hover_effects', array( 
					'none'    => esc_html__( 'None', timeline_wp ),
					'pufrobo' => esc_html__( 'Pufrobo', timeline_wp ),
				) );
				$pro_hovers = apply_filters( 'timeline_wp_pro_hover_effects', array(
					'fluid-up'     => esc_html__( 'Fluid Up', timeline_wp ),
					'hide'         => esc_html__( 'Hide', timeline_wp ),
					'quiet'        => esc_html__( 'Quiet', timeline_wp ),
					'catinelle'    => esc_html__( 'Catinelle', timeline_wp ),
					'reflex'       => esc_html__( 'Reflex', timeline_wp ),
					'curtain'      => esc_html__( 'Curtain', timeline_wp ),
					'lens'         => esc_html__( 'Lens', timeline_wp ),
					'appear'       => esc_html__( 'Appear', timeline_wp ),
					'crafty'       => esc_html__( 'Crafty', timeline_wp ),
					'seemo'        => esc_html__( 'Seemo', timeline_wp ),
					'comodo'       => esc_html__( 'Comodo', timeline_wp ),
					'lily'         => esc_html__( 'Lily', timeline_wp ),
					'sadie'        => esc_html__( 'Sadie', timeline_wp ),
					'honey'        => esc_html__( 'Honey', timeline_wp ),
					'layla'        => esc_html__( 'Layla', timeline_wp ),
					'zoe'          => esc_html__( 'Zoe', timeline_wp ),
					'oscar'        => esc_html__( 'Oscar', timeline_wp ),
					'marley'       => esc_html__( 'Marley', timeline_wp ),
					'ruby'         => esc_html__( 'Ruby', timeline_wp ),
					'roxy'         => esc_html__( 'Roxy', timeline_wp ),
					'bubba'        => esc_html__( 'Bubba', timeline_wp ),
					'romeo'        => esc_html__( 'Romeo', timeline_wp ),
					'dexter'       => esc_html__( 'Dexter', timeline_wp ),
					'sarah'        => esc_html__( 'Sarah', timeline_wp ),
					'chico'        => esc_html__( 'Chico', timeline_wp ),
					'milo'         => esc_html__( 'Milo', timeline_wp ),
					'julia'        => esc_html__( 'Julia', timeline_wp ),
					'goliath'      => esc_html__( 'Goliath', timeline_wp ),
					'hera'         => esc_html__( 'Hera', timeline_wp ),
					'winston'      => esc_html__( 'Winston', timeline_wp ),
					'selena'       => esc_html__( 'Selena', timeline_wp ),
					'terry'        => esc_html__( 'Terry', timeline_wp ),
					'phoebe'       => esc_html__( 'Phoebe', timeline_wp ),
					'apollo'       => esc_html__( 'Apollo', timeline_wp ),
					'kira'         => esc_html__( 'Kira', timeline_wp ),
					'steve'        => esc_html__( 'Steve', timeline_wp ),
					'moses'        => esc_html__( 'Moses', timeline_wp ),
					'jazz'         => esc_html__( 'Jazz', timeline_wp ),
					'ming'         => esc_html__( 'Ming', timeline_wp ),
					'lexi'         => esc_html__( 'Lexi', timeline_wp ),
					'duke'         => esc_html__( 'Duke', timeline_wp ),
					'caption_1'    => esc_html__( 'Caption Effect 1', timeline_wp ),
					'caption_2'    => esc_html__( 'Caption Effect 2', timeline_wp ),
					'caption_3'    => esc_html__( 'Caption Effect 3', timeline_wp ),
					'caption_4'    => esc_html__( 'Caption Effect 4', timeline_wp ),
					'caption_5'    => esc_html__( 'Caption Effect 5', timeline_wp ),
					'caption_6'    => esc_html__( 'Caption Effect 6', timeline_wp ),
					'caption_7'    => esc_html__( 'Caption Effect 7', timeline_wp ),
					'tilt_1'       => esc_html__( 'Tilt Effect 1', timeline_wp ),
					'tilt_2'       => esc_html__( 'Tilt Effect 2', timeline_wp ),
					'tilt_3'       => esc_html__( 'Tilt Effect 3', timeline_wp ),
					'tilt_4'       => esc_html__( 'Tilt Effect 4', timeline_wp ),
					'tilt_5'       => esc_html__( 'Tilt Effect 5', timeline_wp ),
					'tilt_6'       => esc_html__( 'Tilt Effect 6', timeline_wp ),
					'tilt_7'       => esc_html__( 'Tilt Effect 7', timeline_wp ),
					'tilt_8'       => esc_html__( 'Tilt Effect 8', timeline_wp )
				) );
				$html .= '<select name="timeline-wp-settings[' . esc_attr( $field['id'] ) . ']" data-setting="' . esc_attr( $field['id'] ) . '" class="regular-text">';
				foreach ( $hovers as $key => $option ) {
					$html .= '<option value="' . esc_attr( $key ) . '" ' . selected( $key, $value, false ) . '>' . esc_html( $option ) . '</option>';
				}

				if ( ! empty( $pro_hovers ) ) {
					$html .= '<optgroup label="' . esc_html__( 'Hover Effects with PRO license', timeline_wp ) . '">';
					foreach ( $pro_hovers as $key => $option ) {
						$html .= '<option value="' . esc_attr( $key ) . '" disabled>' . esc_html( $option ) . '</option>';
					}
					$html .= '</optgroup>';
				}
				

				$html .= '</select>';
				$html .= '<p class="description">' . esc_html__( 'Select an hover effect', timeline_wp ) . '</p>';

				// Creates effects preview
				$html .= '<div class="timeline-wp-effects-preview timeline-wp">';

				foreach ( $hovers as $key => $name ) {
					$effect = '';

					if ( 'none' == $key ) {
						$effect .= '<div class="panel panel-' . esc_attr( $key ) . ' timeline-wp-items clearfix"></div>';
					}elseif ( 'pufrobo' == $key ) {
						// Pufrobo Effect
						$effect .= '<div class="panel panel-pufrobo timeline-wp-items clearfix">';
						$effect .= '<div class="timeline-wp-item effect-pufrobo"><img src="' . TIMELINE_WP_IMAGES . 'images/effect.jpg" class="pic"><div class="figc"><div class="figc-inner"><h2>Lorem ipsum</h2><p class="description">Quisque diam erat, mollisvitae enim eget</p><div class="jtg-social"><a class="fa fa-twitter" href="#">' . Timline_WP_Helper::get_icon( 'twitter' ) . '</a><a class="fa fa-facebook" href="#">' . Timline_WP_Helper::get_icon( 'facebook' ) . '</a><a class="fa fa-google-plus" href="#">' . Timline_WP_Helper::get_icon( 'google' ) . '</a><a class="fa fa-pinterest" href="#">' . Timline_WP_Helper::get_icon( 'pinterest' ) . '</a></div></div></div></div>';
						$effect .= '<div class="effect-compatibility">';
						$effect .= '<p class="description">' . esc_html__( 'This effect is compatible with:', timeline_wp );
						$effect .= '<span><strong> ' . esc_html__( 'Title', timeline_wp ) . '</strong></span>,';
						$effect .= '<span><strong> ' . esc_html__( 'Social Icons', timeline_wp ) . '</strong></span></p>';
						$effect .= '</div>';
						$effect .= '</div>';
					}else{
						$effect = apply_filters( 'timeline_wp_hover_effect_preview', '', $key );
					}

					$html .= $effect;
				}
				
				$html .= '</div>';
				// Hook to change how hover effects field is rendered
				$html = apply_filters( "timeline_wp_render_hover_effect_field_type", $html, $field );
				break;
			default:
				/* Filter for render custom field types */
				$html = apply_filters( "timeline_wp_render_{$field['type']}_field_type", $html, $field, $value );
				break;
		}

		return $html;

	}


}










add_action("wp_ajax_add_timeline_block", "add_timeline_block");
function add_timeline_block( ) { 

    if (empty($_POST['data'])) { return ; }
	extract($_POST['data']) ;
	$i = $id ; 	

	$timeline 
		=   
		array( 
			'twp_index' 		=> $i ,
			'twp_title' 		=> esc_attr( $name ) ,
			'twp_date' 			=> date('Y') ,
			'icon_type' 		=> 'icon' ,
			'twp_icon' 			=> 'fas fa-home' ,
			'twp_image' 		=> 0 ,                
			'twp_image_url' 	=> TIMELINE_WP_IMAGES . 'timeline-default.png',
			'twp_description' 	=> 'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ab repellendus, eius ad veniam facilis obcaecati optio porro explicabo. Dolorem doloremque neque ad omnis nesciunt esse earum minus illum atque iusto.' ,
		) ;

	ob_start();	?>
	<div id="new-content-block">      
		<div class="tab-pane twp-content-item fade <?php echo ( $i === 0 ) ? ' show active' : ''  ; ?>" data-id="<?php echo $i ; ?>" id="timeline-<?php echo $i ; ?>" role="tabpanel" aria-labelledby="timeline-<?php echo $i ; ?>-tab">
			<div class="row">
				<div class="col-12 col-sm-4 section-one">
					<div class="form-group">
						<label for="twp-title">Title : </label>
						<input type="text" class="form-control twp-title" id="twp-title-<?php echo $i ; ?>" name="twp_title[]" placeholder="Timeline Title" onkeyup="setTitle(this)" value="<?php echo esc_attr( $timeline['twp_title'] ); ?>">
					</div>
					<div class="form-group">
						<label for="twp-date">Date : </label>
						<input type="text" class="form-control" id="twp-date-<?php echo $i ; ?>" name="twp_date[]" placeholder="Timeline Date" value="<?php echo esc_attr( $timeline['twp_date'] ); ?>" >
					</div>
					<div class="form-group twp-type">
						<label for="iconType">Icon Type : </label>
						<select class="form-control selIconType" id="iconType-<?php echo $i ; ?>" name="icon_type[]" onchange="selIconType(this)">
							<option value="icon" <?php echo esc_attr( $timeline['icon_type'] === 'icon' ? 'selected' : '' ); ?> >Icon</option>
							<option value="image" <?php echo esc_attr( $timeline['icon_type'] === 'image' ? 'selected' : '' ); ?> >Image</option>
						</select>
					</div>
					<div class="form-group iconTypeBlock twp-type-icon">
						<label for="twp-icon">Select Icon : </label>
						<button class="btn bt-bg-transparent iconBtn iconpicker dropdown-toggle" id="twp-icon-<?php echo $i ; ?>" name="twp_icon[]" data-footer="false" data-iconset="fontawesome5" data-icon="<?php echo esc_attr( $timeline['twp_icon'] ); ?>" data-cols="6" data-rows="6" role="iconpicker" data-original-title="" title="" aria-describedby="popover709221"><i class="<?php echo esc_attr( $timeline['twp_icon'] ); ?>"></i><input type="hidden" name="twp_icon[]" value="<?php echo esc_attr( $timeline['twp_icon'] ); ?>"><span class="caret"></span></button>
					</div>
					<div class="form-group iconTypeBlock twp-type-image">
						<label for="twp-image">Select Image : </label>
						<div class="image-preview">
							<img data-img-id="<?php echo $i ; ?>" class="imgBtn" src="<?php echo esc_url( $timeline['twp_image_url'] ); ?>" alt="Default">
							<input type="hidden" class="form-control" id="twp-image-<?php echo $i ; ?>" name="twp_image[]" value="<?php echo esc_attr( $timeline['twp_image'] ); ?>" placeholder="Timeline Image">
						</div>
					</div>
				</div>
				<div class="col-12 col-sm-8 section-two">
					<div class="form-group twpDescription">                                    
						<div class="form-group twpDescription">
							<label for="twpDescription">Timeline Description : </label>
							<textarea name="twp_description[]" class="form-control twpDescription-control" id="twp-description-<?php echo $i ; ?>" cols="30" rows="10"><?php echo  $timeline['twp_description']; ?></textarea>
							<?php
								// $editor_settings = array('wpautop' => false,'textarea_name'=>'twp_description[]','editor_class' => 'form-control twpDescription-control' );
								// $editor_id = 'twp-description-'.$i;
								// wp_editor( 'Timeline DescriptionContent', $editor_id,$editor_settings);
							?>
						</div>
					</div>
				</div>

			</div>
		</div>  
	</div>
	<div id="new-list-block">
		<li id="timeline-tab-<?php echo $i ; ?>" class="nav-item twp-list-item" data-order="<?php echo $i ; ?>">
			<input type="hidden" name="order[]" class="itemOrder" value="<?php echo $i ?>" >                            
			<a class="nav-link  <?php echo ( $i === 0 ) ? ' active' : ''  ; ?>"  data-toggle="pill" href="#timeline-<?php echo $i ; ?>" role="tab" aria-controls="timeline-<?php echo $i ; ?>" aria-selected="<?php echo ( $i === 0 ) ? 'true' : ''  ; ?>">
				<div class="drag-item">
					<i class="fas fa-bars"></i>
				</div>
				<div class="heading">
					<?php echo esc_attr( $timeline['twp_title'] ); ?>
				</div>
				<div class="del-item" data-nav="#timeline-tab-<?php echo $i ; ?>" data-content="#timeline-<?php echo $i ; ?>" onclick="del_item(this)">
					<i class="fas fa-trash-alt"></i>
				</div>
			</a>
		</li>
	</div>
	<?php 
	$output_string = ob_get_contents();
	ob_end_clean();
	echo !empty( $output_string ) ? $output_string : '';
	die; 
}
