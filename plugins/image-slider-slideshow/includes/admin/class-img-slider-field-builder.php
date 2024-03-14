<?php

/**
 * 
 */
class Img_Slider_Field_Builder {

	function __construct() {

		/* Add templates for our plugin */
		add_action( 'admin_footer', array( $this, 'print_img_slider_templates' ) );

	}

	/**
	 * Get an instance of the field builder
	 */
	public static function get_instance() {
		static $inst;
		if ( ! $inst ) {
			$inst = new Img_Slider_Field_Builder();
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
        $settings = get_post_meta( $this->get_id(), 'img-slider-settings', true );

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
			case 'upgrade-to-pro':
				$this->_render_upgrade_to_pro_metabox();
			break;
			case 'shortcode':
				$this->_render_shortcode_metabox( $post );
				break;
			default:
				do_action( "portfolio_wp_metabox_fields_{$metabox}" );
				break;
		}

	}

	/* Create HMTL for gallery metabox */
	private function _render_gallery_metabox() {

		$images = get_post_meta( $this->get_id(), 'slider-images', true );
		//$helper_guidelines = $this->get_setting( 'helpergrid' );

		$max_upload_size = wp_max_upload_size();
	    if ( ! $max_upload_size ) {
	        $max_upload_size = 0;
	    }

		echo '<div class="container-fluid img-slider-uploader-container">';
		echo '<div class="row img-slider-upload-actions">';
		echo '<div class="col-lg-7 upload-info-container">';
		echo '<div class="upload-info">';
		echo sprintf( __( '<b><h2>Drag and drop</b> files here (max %s per file), or <b>drag images around to change their order</h2></b>', 'img-slider' ), esc_html( size_format( $max_upload_size ) ) );
		echo '</div>';
		echo '<div class="upload-progress">';
		echo '<p class="img-slider-upload-numbers">' . esc_html__( 'Uploading image', 'img-slider' ) . ' <span class="img-slider-current"></span> ' . esc_html__( 'of', 'img-slider' ) . ' <span class="img-slider-total"></span>';
		echo '<div class="img-slider-progress-bar"><div class="img-slider-progress-bar-inner"></div></div>';
		echo '</div>';
		echo '</div>';
		echo '<div class="col-lg-5">';
		echo '<a href="#" id="img-slider-uploader-browser"  class="btn btn-md btn-secondary btn-block">' . esc_html__( 'Upload Image Files', 'img-slider' ) . '</a><a href="#" id="img-slider-gallery"  class="btn btn-md btn-primary btn-block">' . esc_html__( 'Select Image from Library', 'img-slider' ) . '</a>';
		echo '</div>';
		echo '</div>';
		echo '<div id="img-slider-uploader-container" class="row img-slider-uploader-inline">';
			echo '<div class="img-slider-error-container"></div>';
			echo '<div class="col-sm-12 img-slider-uploader-inline-content">';
				echo '<h2 class="img-slider-upload-message"><span class="dashicons dashicons-upload"></span>' . esc_html__( 'Drag & Drop files here!', 'img-slider' ) . '</h2>';
				echo '<div id="img-slider-grid" style="display:none"></div>';
			echo '</div>';
			echo '<div id="img-slider-dropzone-container"><div class="img-slider-uploader-window-content"><h1>' . esc_html__( 'Drop files to upload', 'img-slider' ) . '</h1></div></div>';
		echo '</div>';

		echo '</div>';
	}


	private function _render_upgrade_to_pro_metabox() {

		?>
		<style>
			#img-slider-upgrade-to-pro .hndle{background-color:#007bff; color:#fff; text-align: center; justify-content: center;}
			#img-slider-upgrade-to-pro .postbox-header .handle-actions{
				display: none;
			}
			#img-slider-upgrade-to-pro{background:#007bff0d none repeat scroll 0 0; border:1px solid #0073aa !important; color:#191e23;}
			.postbox-container .asg-list li:before{font-family: dashicons; content: "\f139"; font-size:20px; color: #0073aa; vertical-align: middle;}
			.upgrade-to-pro{font-size:18px; text-align:center; margin-bottom:15px;}
			.asg-copy-clipboard{-webkit-touch-callout: all; -webkit-user-select: all; -khtml-user-select: all; -moz-user-select: all; -ms-user-select: all; user-select: all;}
			.asg-new-feature{ font-size: 10px; margin-left:2px; color: #fff; font-weight: bold; background-color: #03aa29; padding:1px 4px; font-style: normal; }
			.button-orange{background: #ff2700 !important;border-color: #ff2700 !important; font-weight: 600; width: 100%; text-align: center;}
		</style>

			<ul class="asg-list">
				<li><?php _e( '21+ amazing Layouts', 'accordion-slider' ); ?></li>
				<li><?php _e( 'Create a beautiful Slider layout inside your WordPress website.', 'accordion-slider' ); ?></li>
				<li><?php _e( 'Wheel and Carousal Layouts are available', 'accordion-slider' ); ?></li>
				<li><?php _e( 'Have Custom Height and Width settings in Slider', 'accordion-slider' ); ?></li>
				<li><?php _e( 'And also Slider thumbnail, navigation arrow and dots setting', 'accordion-slider' ); ?></li>
				<li><?php _e( 'Each Layout has its own different setting', 'accordion-slider' ); ?></li>
				<li><?php _e( 'You can create video slider too', 'accordion-slider' ); ?></li>
				<li><?php _e( 'Use video settings for video slider', 'accordion-slider' ); ?></li>
				<li><?php _e( 'Text settings and fancy box settings', 'accordion-slider' ); ?></li>

				<li><?php _e( 'Panel and page setting available', 'accordion-slider' ); ?></li>
				<li><?php _e( 'Have Autoplay settings too', 'accordion-slider' ); ?></li>
				<li><?php _e( 'Mobile Compatibility View', 'accordion-slider'); ?></li>
				
				<li><?php _e( 'You can add images with media', 'accordion-slider'); ?> <span class="asg-new-feature">New</span></li>
				<li><?php _e( 'Elementor, Beaver, and SiteOrigin Page Builder Support.', 'accordion-slider'); ?> <span class="asg-new-feature">New</span></li>
				<li><?php _e( 'Divi Page Builder Native Support.', 'accordion-slider'); ?> <span class="asg-new-feature">New</span></li>
				<li><?php _e( 'WP Templating Features', 'accordion-slider'); ?> <span class="asg-new-feature">New</span></li>
				
				<li><?php _e( 'Custom CSS', 'accordion-slider' ); ?></li>
				<li><?php _e( 'Fully Responsive', 'accordion-slider' ); ?></li>
				
			</ul>
			<div class="upgrade-to-pro"><?php echo sprintf( __( 'Gain access to <strong>Slider Slideshow Pro</strong>', 'accordion-slider' ) ); ?></div>
			<a class="button button-primary asg-button-full button-orange" href="<?php echo SLIDER_SLIDESHOW_PLUGIN_UPGRADE; ?>" target="_blank"><?php _e('Buy Now', 'accordion-slider'); ?></a>
			
		<?php

	}



	/* Create HMTL for settings metabox */
	private function _render_settings_metabox() {
		$tabs = Img_Slider_WP_CPT_Fields_Helper::get_tabs();

		// Sort tabs based on priority.
		uasort( $tabs, array( 'img_slider_helper', 'sort_data_by_priority' ) );

		$tabs_html = '';
		$tabs_content_html = '';
		$first = true;

		// Generate HTML for each tab.
		foreach ( $tabs as $tab_id => $tab ) {
			$tab['id'] = $tab_id;
			$tabs_html .= $this->_render_tab( $tab, $first );

			$fields = Img_Slider_WP_CPT_Fields_Helper::get_fields( $tab_id );
			// Sort fields based on priority.
			uasort( $fields, array( 'img_slider_helper', 'sort_data_by_priority' ) );

			$current_tab_content = '<div id="portfolio-wp-' . esc_attr( $tab['id'] ) . '" class="' . ( $first ? 'active-tab' : '' ) . '">';

			// Check if our tab have title & description
			if ( isset( $tab['title'] ) || isset( $tab['description'] ) ) {
				$current_tab_content .= '<div class="tab-content-header">';
				$current_tab_content .= '<div class="tab-content-header-title">';
				if ( isset( $tab['title'] ) && '' != $tab['title'] ) {
					$current_tab_content .= '<h2>' . esc_html( $tab['title'] ) . '</h2>';
				}
				if ( isset( $tab['description'] ) && '' != $tab['description'] ) {
					$current_tab_content .= '<div class="tab-header-tooltip-container img-slider-tooltip"><span><i class="fa fa-lightbulb"></i></span>';
					$current_tab_content .= '<div class="tab-header-description img-slider-tooltip-content">' . wp_kses_post( $tab['description'] ) . '</div>';
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
			$current_tab_content .= apply_filters( 'portfolio_wp_' . $tab_id . '_tab_content', '' );
			$current_tab_content .= '</div>';
			$current_tab_content .= '</div>';
			$tabs_content_html .= $current_tab_content;

			if ( $first ) {
				$first = false;
			}

		}

		$html = '<div class="img-slider-settings-container"><div class="img-slider-tabs">%s</div><div class="img-slider-tabs-content">%s</div>';
		printf( $html, $tabs_html, $tabs_content_html );
	}

	/* Create HMTL for shortcode metabox */
	private function _render_shortcode_metabox( $post ) {
		$shortcode = '[img-slider id="' . $post->ID . '"]';
		echo '<input type="text" style="width:100%;" value="' . esc_attr( $shortcode ) . '"  onclick="select()" readonly>';
		// Add Copy Shortcode button
        echo '<a href="#" class="copy-img-slider-shortcode button button-primary" style="margin-top:10px;">'.esc_html__('Copy Shortcode','img-slider').'</a><span style="margin-left:15px;"></span>';
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
		return '<div class="img-slider-tab' . ( $first ? ' active-tab' : '' ) . ' portfolio-wp-' . esc_attr( $tab['id'] ) . '" data-tab="portfolio-wp-' . esc_attr( $tab['id'] ) . '">' . $icon . wp_kses_post( $tab['label'] ) . $badge . '</div>';
	}

	/* Create HMTL for a row */
	private function _render_row( $field ) {
		$format = '<tr data-container="' . esc_attr( $field['id'] ) . '"><th scope="row"><label>%s</label>%s</th><td>%s</td></tr>';

		if ( 'textarea' == $field['type'] || 'custom_code' == $field['type'] ) {
			$format = '<tr data-container="' . esc_attr( $field['id'] ) . '"><td colspan="2"><label class="th-label">%s</label>%s<div>%s</div></td></tr>';
		}

		$format = apply_filters( "portfolio_wp_field_type_{$field['type']}_format", $format, $field );

		$default = '';

		// Check if our field have a default value
		if ( isset( $field['default'] ) ) {
			$default = $field['default'];
		}

		// Generate tooltip
		$tooltip = '';
		if ( isset( $field['description'] ) && '' != $field['description'] ) {
			$tooltip .= '<div class="img-slider-tooltip"><span><i class="fa fa-lightbulb"></i></span>';
			$tooltip .= '<div class="img-slider-tooltip-content">' . wp_kses_post( $field['description'] ) . '</div>';
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
				$html = '<input type="text" class="col-sm-4 regular-text" name="img-slider-settings[' . esc_attr( $field['id'] ) . ']" data-setting="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $value ) . '">';
				break;

			case 'text_short':
				global $id, $post;
       			$post_id = isset( $post->ID ) ? $post->ID : (int) $id;

				$shortcode = '[img-slider id="' . $post_id . '"]';
				
				$html = '<input type="text" class="col-sm-10 regular-text" style="width:100%;padding:15px;background-color: #f5e293;" onclick="select()" name="img-slider-settings[' . esc_attr( $field['id'] ) . ']" data-setting="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $shortcode ) . '" readonly>';
				break;
			case 'text_php':

				
				global $id, $post;
       			$post_id = isset( $post->ID ) ? $post->ID : (int) $id;

				$shortcode = '[img-slider id="' . $post_id . '"]';
				$value = "<?php echo do_shortcode('$shortcode'); ?>";

				$html = '<input type="text" class="col-sm-10 regular-text" style="width:100%;padding:15px;background-color: #f5e293;" onclick="select()" name="img-slider-settings[' . esc_attr( $field['id'] ) . ']" data-setting="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $value ) . '" readonly>';
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
				$html .= '<div class="col-sm-4 slider-container img_ui_slider-container">';
					$html .= '<div id="slider_' . esc_attr( $field['id'] ) . '" class="ss-slider img_ui_slider"></div>';
					$html .= '<input readonly="readonly" data-setting="' . esc_attr( $field['id'] ) . '"  name="img-slider-settings[' . esc_attr( $field['id'] ) . ']" type="text" class="col-sm-4 rl-slider img_ui_slider-input" id="input_' . esc_attr( $field['id'] ) . '" value="' . $value . '" ' . $attributes . '/>';
				$html .= '</div>';
				break;
				
			case 'color' :
				$html .= '<div class="img-slider-colorpickers">';
				$html .= '<input id="' . esc_attr( $field['id'] ) . '" class="col-sm-4 img-slider-color" data-setting="' . esc_attr( $field['id'] ) . '" name="img-slider-settings[' . esc_attr( $field['id'] ) . ']" value="' . esc_attr( $value ) . '">';
				$html .= '</div>';
				break;
			case "toggle":
				$html .= '<div class="img-slider-toggle">';
					$html .= '<input class="img-slider-toggle__input" type="checkbox" data-setting="' . esc_attr( $field['id'] ) . '" id="' . esc_attr( $field['id'] ) . '" name="img-slider-settings[' . esc_attr( $field['id'] ) . ']" value="1" ' . checked( 1, $value, false ) . '>';
					$html .= '<div class="img-slider-toggle__items">';
						$html .= '<span class="img-slider-toggle__track"></span>';
						$html .= '<span class="img-slider-toggle__thumb"></span>';
						$html .= '<svg class="img-slider-toggle__off" width="6" height="6" aria-hidden="true" role="img" focusable="false" viewBox="0 0 6 6"><path d="M3 1.5c.8 0 1.5.7 1.5 1.5S3.8 4.5 3 4.5 1.5 3.8 1.5 3 2.2 1.5 3 1.5M3 0C1.3 0 0 1.3 0 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3z"></path></svg>';
						$html .= '<svg class="img-slider-toggle__on" width="2" height="6" aria-hidden="true" role="img" focusable="false" viewBox="0 0 2 6"><path d="M0 0h2v6H0z"></path></svg>';
					$html .= '</div>';
				$html .= '</div>';
				break;
			case "custom_code":
				$html = '<div class="img-slider-code-editor" data-syntax="' . esc_attr( $field['syntax'] ) . '">';
				$html .= '<textarea data-setting="' . esc_attr( $field['id'] ) . '" name="img-slider-settings[' . esc_attr( $field['id'] ) . ']" id="portfolio-wp-' . esc_attr( $field['id'] ) . '" class="large-text code"  rows="10" cols="50">' . wp_kses_post($value) . '</textarea>';
				$html .= '</div>';
				break;

			case 'select' :
				$html = '<select name="img-slider-settings[' . esc_attr( $field['id'] ) . ']" data-setting="' . esc_attr( $field['id'] ) . '" style="max-width:33%;" class="col-sm-4 regular-text">';
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

			case "custom_text":
				
				$this->pbsm_reg_function();

				$html = '<div name="img-slider-settings[' . esc_attr( $field['id'] ) . ']" data-setting="' . esc_attr( $field['id'] ) . '"  class="">';


				$html .= '<div id="bdpgeneral" class="postbox postbox-with-fw-options">';
				$html .= '<ul class="pbsm-settings">';
				$html .= ' <li>';
				$html .= '<h3 class="pbsm-table-title" style="margin-bottom:20px;">Select Slider Layout</h3>';
				
				$html .= '<div class="pbsm-left">';
				$html .= '<p class="pbsm-margin-bottom-50" style="margin-bottom:20px; font-size:16px;">Select your favorite Slider layout from 9 free layouts. </p>';

				
				global $id, $post;
       			$post_id = isset( $post->ID ) ? $post->ID : (int) $id;
				$settings = get_post_meta( $post_id, 'img-slider-settings', true );
				
				ob_start(); ?>
                
				<p class="pbsm-margin-bottom-30" style="margin-bottom:20px;font-size:16px;"><b><?php _e('Current Template:', 'img-slider'); ?></b> &nbsp;&nbsp;
                <span class="pbsm-template-name" id="pbsm-id">
                    <?php
                    if (isset($settings['designName'])) {
                        echo str_replace('_', '-', $settings['designName']) . ' ';
                        _e('Template', 'img-slider');
                    }
                    ?>
                </span></p>


				 <?php $html .= ob_get_clean();

                $html .=   '</span> 
						 </p>';
				
				$html .= '<input type="button" class="pbsm_select_template" data-option="' . esc_attr( $field['id'] ) . '" value="Select Other Slider Design">';



				$html .= '<input type="hidden" id="' . esc_attr( $field['id'] ) . '" class="col-sm-4 regular-text pbsm_select_template_value" name="img-slider-settings[' . esc_attr( $field['id'] ) . ']" data-setting="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $value ) . '">';
				
				$html .= '<a href="https://testerwp.com/product/image-slider-slideshow-pro/" target="_blank" class="premium-red">Get More Designs</a>';
			

				$html .= '</div>';

				$html .= '</li>';
				$html .= '</ul>';
				$html .= '</div>';

				$html .= '</div>';
			
			default:
				/* Filter for render custom field types */
				$html = apply_filters( "portfolio_wp_render_{$field['type']}_field_type", $html, $field, $value );
				break;
		}

		return $html;

	}

	public function print_img_slider_templates() { 
		include 'img-slider-js-templates.php';
	}

	public function pbsm_reg_function( ) { ?>
		<!-- layout selector -->
		<div id="pbsm_popupdiv" class="pbsm-template-popupdiv" style="display: none;"> <?php
            $tempate_list = $this->pbsm_template_list();
            foreach ($tempate_list as $key => $value) {
                $classes = explode(' ', $value['class']);
                foreach ($classes as $class) {
                    $all_class[] = $class;
                }
            }
            $count = array_count_values($all_class); ?>
            
            <?php echo '<div class="pbsm-template-cover">';
	            foreach ($tempate_list as $key => $value) {
	                if ($key == 'Boxed_Slider' || $key == 'Content_Slider' || $key == 'Caption_Slider' || $key == 'Effect_Coverflow_Slider' || $key == 'Thumbnail_Slider' || $key == 'Pagination_Slider' || $key == 'Fullwidth_Slider' || $key == 'Owl_Slider') {
	                    $class = 'pbsm-lite'; 
	                } ?>
	                <div class="pbsm-template-thumbnail <?php echo $value['class'] . ' ' . $class; ?>">
	                    <div class="pbsm-template-thumbnail-inner">
	                        <img src="<?php echo IMG_SLIDER_IMAGES . 'layouts/' . $value['image_name']; ?>" data-value="<?php echo $key; ?>" alt="<?php echo $value['template_name']; ?>" title="<?php echo $value['template_name']; ?>">
	      						<?php if ($class == 'pbsm-lite') { ?>
	                            	<div class="pbsm-hover_overlay">
		                                <div class="pbsm-popup-template-name">
		                                    <div class="pbsm-popum-select"><a href="#"><?php _e('Select Template', 'img-slider'); ?></a></div>
		                                </div>
	                            	</div>
	       						<?php } ?>
	                    </div>
	                    <span class="pbsm-span-template-name"><?php echo $value['template_name']; ?></span>
	                </div>
                <?php }
            echo '</div>';
            echo '<h3 class="no-template" style="display: none;">' . __('No template found. Please try again', 'img-slider') . '</h3>'; ?>

		</div>
		<?php 
	}

	public function pbsm_template_list() {
   $tempate_list = array(
        'Boxed_Slider' => array(
            'template_name' => __('Boxed Slider', 'img-slider'),
            'class' => 'grid free',
            'image_name' => 'boxed_slider.jpg',
            'demo_link' => esc_url('#'),
        ),
        'Content_Slider' => array(
            'template_name' => __('Content Slider', 'img-slider'),
            'class' => 'full-width free',
            'image_name' => 'content_slider.jpg',
            'demo_link' => esc_url('#'),
        ),
        'Caption_Slider' => array(
            'template_name' => __('Caption Slider', 'img-slider'),
            'class' => 'slider free',
            'image_name' => 'caption_slider.jpg',
            'demo_link' => esc_url('#/')
        ),
        'Effect_Coverflow_Slider' => array(
            'template_name' => __('Effect-Coverflow Slider', 'img-slider'),
            'class' => 'full-width free',
            'image_name' => 'effect_coverflow.jpg',
            'demo_link' => esc_url('#'),
        ),
        'Thumbnail_Slider' => array(
            'template_name' => __('Thumbnail Slider', 'img-slider'),
            'class' => 'magazine free',
            'image_name' => 'thumbnail_slider.jpg',
            'demo_link' => esc_url('#'),
        ),
        'Pagination_Slider' => array(
            'template_name' => __('Pagination Slider', 'img-slider'),
            'class' => 'timeline free',
            'image_name' => 'pagination_slider.jpg',
            'demo_link' => esc_url('#'),
        ),
        'Fullwidth_Slider' => array(
            'template_name' => __('Full-Width Slider', 'img-slider'),
            'class' => 'slider free',
            'image_name' => 'fullwidth_slider.jpg',
            'demo_link' => esc_url('#'),
        ),
        'Carousel_Slider' => array(
            'template_name' => __('Carousel Slider', 'img-slider'),
            'class' => 'slider free',
            'image_name' => 'carousel_slider.jpg',
            'demo_link' => esc_url('#'),
        ),
        'Owl_Slider' => array(
            'template_name' => __('Owl Slider', 'img-slider'),
            'class' => 'slider free',
            'image_name' => 'owl_slider.jpg',
            'demo_link' => esc_url('#'),
        ),
    );
    ksort($tempate_list);
    return $tempate_list;
}

}
?>
