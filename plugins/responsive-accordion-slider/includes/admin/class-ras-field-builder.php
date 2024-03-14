<?php

/**
 * 
 */
class Resp_Accordion_Slider_Field_Builder{

	function __construct() {

		/* Add templates for our plugin */
		add_action( 'admin_footer', array( $this, 'print_accordion_slider_templates' ) );

	}

	/**
	 * Get an instance of the field builder
	 */
	public static function get_instance() {
		static $inst;
		if ( ! $inst ) {
			$inst = new Resp_Accordion_Slider_Field_Builder();
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
     * @since 1.0.1
     *
     * @global int $id        The current post ID.
     * @global object $post   The current post object.
     * @param string $key     The setting key to retrieve.
     * @param string $default A default value to use.
     * @return string         Key value on success, empty string on failure.
     */
    public function get_setting( $key, $default = false ) {

        // Get config
        $settings = get_post_meta( $this->get_id(), 'ras-accordion-slider-settings', true );

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
			case 'review':
				$this->_render_review_metabox();
				break;
			case 'shortcode':
				$this->_render_shortcode_metabox( $post );
				break;
			default:
				do_action( "accordion_slider_metabox_fields_{$metabox}" );
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

		echo '<div class="container-fluid ras-uploader-container">';

			echo '<div id="ras-uploader-container" class="row ras-uploader-inline">';
			echo '<div class="ras-error-container"></div>';
			echo '<div class="col-sm-12 ras-uploader-inline-content">';
				echo '<h2 class="ras-upload-message"><span class="dashicons dashicons-upload"></span>' . esc_html__( 'Drag & Drop files here!', 'responsive-accordion-slider' ) . '</h2>';
				echo '<div id="ras-slider-grid" style="display:none"></div>';
			echo '</div>';
			echo '<div id="ras-dropzone-container"><div class="ras-uploader-window-content"><h1>' . esc_html__( 'Drop files to upload', 'responsive-accordion-slider' ) . '</h1></div></div>';
		echo '</div>';
		
		echo '<div class="row ras-upload-actions">';
		echo '<div class="col-sm-12 upload-info-container">';
		echo '<div class="upload-info">';
		echo sprintf( __( '<b><h2>Drag and drop</b> files here </h2></b>', 'responsive-accordion-slider' ), esc_html( size_format( $max_upload_size ) ) );
		echo '</div>';
		echo '<div class="upload-progress">';
		echo '<p class="ras-upload-numbers">' . esc_html__( 'Uploading image', 'responsive-accordion-slider' ) . ' <span class="ras-current"></span> ' . esc_html__( 'of', 'responsive-accordion-slider' ) . ' <span class="ras-total"></span>';
		echo '<div class="ras-progress-bar"><div class="ras-progress-bar-inner"></div></div>';
		echo '</div>';
		echo '</div>';
		echo '<div class="col-sm-12">';
		echo '<div class="col-md-6" style="float:left;"><a href="#" id="ras-uploader-browser"  class="btn btn-md btn-secondary btn-block">' . esc_html__( 'Upload Image Files', 'responsive-accordion-slider' ) . '</a></div><div class="col-md-6" style="float:left;"><a href="#" id="ras-gallery"  class="btn btn-md btn-primary btn-block">' . esc_html__( 'Select Image from Library', 'responsive-accordion-slider' ) . '</a></div>';
		echo '</div>';
		echo '</div>';

	

		echo '</div>';
	}

	/* Create HMTL for settings metabox */
	private function _render_settings_metabox() {
		$tabs = RESP_ACCORDION_SLIDER_CPT_Fields_Helper::resp_accordion_slider_get_tabs();

		// Sort tabs based on priority.
		uasort( $tabs, array( 'resp_accordion_slider_helper', 'sort_data_by_priority' ) );

		$tabs_html = '';
		$tabs_content_html = '';
		$first = true;

		// Generate HTML for each tab.
		foreach ( $tabs as $tab_id => $tab ) {
			$tab['id'] = $tab_id;
			$tabs_html .= $this->_render_tab( $tab, $first );

			$fields = RESP_ACCORDION_SLIDER_CPT_Fields_Helper::resp_accordion_slider_get_fields( $tab_id );
			// Sort fields based on priority.
			uasort( $fields, array( 'resp_accordion_slider_helper', 'sort_data_by_priority' ) );

			$current_tab_content = '<div id="accordion-slider-' . esc_attr( $tab['id'] ) . '" class="accordion-slider-tab-content' . ( $first ? ' active-tab' : '' ) . '">';

			// Check if our tab have title & description
			if ( isset( $tab['title'] ) || isset( $tab['description'] ) ) {
				$current_tab_content .= '<div class="tab-content-header">';
				$current_tab_content .= '<div class="tab-content-header-title">';
				if ( isset( $tab['title'] ) && '' != $tab['title'] ) {
					$current_tab_content .= '<h2>' . esc_html( $tab['title'] ) . '</h2>';
				}
				if ( isset( $tab['description'] ) && '' != $tab['description'] ) {
					$current_tab_content .= '<div class="tab-header-tooltip-container ras-slider-tooltip"><span><i class="fas fa-exclamation-circle"></i></span>';
					$current_tab_content .= '<div class="tab-header-description ras-slider-tooltip-content">' . wp_kses_post( $tab['description'] ) . '</div>';
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
			$current_tab_content .= apply_filters( 'accordion_slider_' . $tab_id . '_tab_content', '' );
			$current_tab_content .= '</div>';
			$current_tab_content .= '</div>';
			$tabs_content_html .= $current_tab_content;

			if ( $first ) {
				$first = false;
			}

		}

		$html = '<div class="ras-settings-container"><div class="ras-tabs">%s</div><div class="ras-tabs-content">%s</div>';
		printf( $html, $tabs_html, $tabs_content_html );

		global $id, $post;
		$post_id = isset( $post->ID ) ? $post->ID : (int) $id;

		if( !empty(  get_post_meta( $post_id, 'ras-accordion-slider-settings' )  ) ) {
		    // print_r();
		    $settings = get_post_meta( $post_id, 'ras-accordion-slider-settings', true );

		    $autoplay=$settings['slider-autoplay'];
		    $hide_title=$settings['hide-title'];
		    $hide_description=$settings['hide-description'];
		    $hide_button=$settings['hide-button'];

		} else {  
		    $autoplay=0;
		    $hide_title=0;
		    $hide_description=0;
		    $hide_button=0;
		}

		if($autoplay==0){
			?>
				<style>#slider-delay,#slider-direction{ display:none; }</style>
			<?php
		}
		else if($autoplay==1){
			?>
				<style>#slider-delay,#slider-direction{ display:table-row; }</style>
			<?php
		}

		if($hide_title==1){
			?>
			<style>#titleColor,#titleBgColor,#titleFontSize{ display:none; }</style>
			<?php
		} else if($hide_title==0){
			?>
			<style>#titleColor,#titleBgColor,#titleFontSize{ display:table-row; }</style>
			<?php
		}

		if($hide_description==1){
			?>
			<style>#captionColor,#captionBgColor,#captionFontSize{ display:none; }</style>
			<?php
		} else if($hide_description==0){
			?>
			<style>#captionColor,#captionBgColor,#captionFontSize{ display:table-row; }</style>
			<?php
		}

		if($hide_button==1){
			?>
			<style>#buttonFontSize,#buttonBorder,#buttonTextColor,#buttonBgColor{ display:none; }</style>
			<?php
		} else if($hide_button==0){
			?>
			<style>#buttonFontSize,#buttonBorder,#buttonTextColor,#buttonBgColor{ display:table-row; }</style>
			<?php
		}
	}

	private function _render_upgrade_to_pro_metabox(){
		?>
		<style>
			.ras-alert {
			    padding: 10px;
			    background: #EDF8FC;
			    color: #3c434a;
			    position: relative;
			    border: 1px solid #DDD;
			}
			.ras-alert ul {
			    padding-left: 30px;
			}
			.ras-alert ul li {
			    position: relative;
			    list-style-type: none !important;
			    text-align: left;
			}
			.ras-alert ul li::before {
			    font-family: Dashicons;
			    content: "\f345";
			    position: absolute;
			    left: -30px;
			    font-size: 14px;
			    color: green;
			    background-color: #FFF;
			    padding: 0 3px;
			    border-radius: 50%;
			    border: 1px solid #DDD;
			    top: -1px;
			}
			.ras-alert > *:last-child {
			    text-align: center;
			    margin-bottom: 0 !important;
			}
		</style>
		<div class="ras-alert">
			<!-- <h2><?php // esc_html_e( 'Automatically pull in & display new reviews as your customers leave their feedback on external platforms', 'responsive-accordion-slider' ) ?></h2> -->
			<p><?php esc_html_e( 'Upgrade today and get the ability to import testimonials from:', 'responsive-accordion-slider' ) ?></p>
			<ul>
				<li><?php esc_html_e( '12+ Responsive Layouts', 'responsive-accordion-slider' ) ?></li>
				<li><?php esc_html_e( 'Lightbox on Title', 'responsive-accordion-slider' ) ?></li>
				<li><?php esc_html_e( '5 Overlay Effects', 'responsive-accordion-slider' ) ?></li>
				<li><?php esc_html_e( 'Background Opacity', 'responsive-accordion-slider' ) ?></li>
				<li><?php esc_html_e( 'Mouse Delay', 'responsive-accordion-slider' ) ?></li>
				<li><?php esc_html_e( 'Unlimited Images', 'responsive-accordion-slider' ) ?></li>
				<li><?php esc_html_e( 'Button Settings', 'responsive-accordion-slider' ) ?></li>
				<li><?php esc_html_e( 'Responsive Mode', 'responsive-accordion-slider' ) ?></li>
				<li><?php esc_html_e( 'Open Panel Duration', 'responsive-accordion-slider' ) ?></li>
				<li><?php esc_html_e( 'Close Panel Duration', 'responsive-accordion-slider' ) ?></li>
				<li><?php esc_html_e( 'Navigation Settings', 'responsive-accordion-slider' ) ?></li>
				<li><?php esc_html_e( 'Image Shadow Settings', 'responsive-accordion-slider' ) ?></li>
				<li><?php esc_html_e( 'Image Border Settings', 'responsive-accordion-slider' ) ?></li>
				<li><?php esc_html_e( 'Duplicate Post Option', 'responsive-accordion-slider' ) ?></li>
				<li><?php esc_html_e( 'Title Hover Color', 'responsive-accordion-slider' ) ?></li>
				<li><?php esc_html_e( 'Multi-Site Supported', 'responsive-accordion-slider' ) ?></li>
				<li><?php esc_html_e( 'Highly Customizable', 'responsive-accordion-slider' ) ?></li>
				<li><?php esc_html_e( 'Developer Friendly', 'responsive-accordion-slider' ) ?></li>
				<li><?php esc_html_e( '700+ Google Fonts', 'responsive-accordion-slider' ) ?></li>
			</ul>
			<p>
				<a class="button button-primary" target="_blank" href="<?php echo esc_url( 'https://techknowprime.com/product/accordion-slider-pro/' ); ?>"><?php esc_html_e( 'Upgrade Now', 'responsive-accordion-slider' ); ?></a>
			</p>
		</div>
		<?php
	}

	private function _render_review_metabox(){ ?>
		<div style="text-align:center">
			<p>If you like our plugin then please <b>Rate us</b> on WordPress</p>
		</div>
		<div style="text-align:center">
			<span class="dashicons dashicons-star-filled"></span>
			<span class="dashicons dashicons-star-filled"></span>
			<span class="dashicons dashicons-star-filled"></span>
			<span class="dashicons dashicons-star-filled"></span>
			<span class="dashicons dashicons-star-filled"></span>
		</div>
		<br>
		<div style="text-align:center">
			<a href="https://wordpress.org/support/plugin/responsive-accordion-slider/reviews/?filter=5" target="_blank" class="button button-primary button-large"><span class="dashicons dashicons-heart" style="line-height:1.5;" ></span> Please Rate Us</a>
		</div>	
		<?php
	}

	/* Create HMTL for shortcode metabox */
	private function _render_shortcode_metabox( $post ) {
		$shortcode = '[resp-slider id="' . $post->ID . '"]';
		echo '<input type="text" style="width:100%;" value="' . esc_attr( $shortcode ) . '"  onclick="select()" readonly>';
		// Add Copy Shortcode button
        echo '<a href="#" class="copy-ras-shortcode button button-primary">'.esc_html__('Copy Shortcode','responsive-accordion-slider').'</a><span></span>';
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
		return '<div class="ras-tab' . ( $first ? ' active-tab' : '' ) . ' accordion-slider-' . esc_attr( $tab['id'] ) . '" data-tab="accordion-slider-' . esc_attr( $tab['id'] ) . '">' . $icon . wp_kses_post( $tab['label'] ) . $badge . '</div>';
	}

	/* Create HMTL for a row */
	private function _render_row( $field ) {
		$format = '<tr data-container="' . esc_attr( $field['id'] ) . '" class="' . esc_attr( $field['id'] ) . '"><th scope="row"><label>%s</label>%s</th><td>%s</td></tr>';

		if ( 'textarea' == $field['type'] || 'custom-code' == $field['type'] ) {
			$format = '<tr data-container="' . esc_attr( $field['id'] ) . '"><td colspan="2"><label class="th-label">%s</label>%s<div>%s</div></td></tr>';
		}

		$format = apply_filters( "accordion_slider_field_type_{$field['type']}_format", $format, $field );

		$default = '';

		// Check if our field have a default value
		if ( isset( $field['default'] ) ) {
			$default = $field['default'];
		}

		// Generate tooltip
		$tooltip = '';
		if ( isset( $field['description'] ) && '' != $field['description'] ) {
			$tooltip .= '<div class="ras-slider-tooltip"><span><i class="fas fa-exclamation-circle"></i></span>';
			$tooltip .= '<div class="ras-slider-tooltip-content">' . wp_kses_post( $field['description'] ) . '</div>';
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
			case "design-layout":
				
				$this->design_reg_function();

				$html = '<div name="ras-accordion-slider-settings[' . esc_attr( $field['id'] ) . ']" data-setting="' . esc_attr( $field['id'] ) . '"  class="">';


				$html .= '<div id="bdpgeneral" class="postbox-with-fw-options">';
				$html .= '<ul class="ras-layout-settings">';
				$html .= ' <li>';
				$html .= '<h3 class="ras-select-layout-title" style="margin-bottom:20px;">Select Accordion Slider Design</h3>';
				
				$html .= '<div class="ras-left">';
								
				global $id, $post;
       			$post_id = isset( $post->ID ) ? $post->ID : (int) $id;
				$settings = get_post_meta( $post_id, 'ras-accordion-slider-settings', true );
				
				ob_start(); ?>
                
				<p class="ras-margin-bottom" style="margin-bottom:20px;font-size:16px;"><b><?php _e('Selected Template:', 'responsive-accordion-slider'); ?></b> &nbsp;&nbsp;
                <span class="ras-template-name" id="ras-id">
                    <?php
                    if (isset($settings['designName'])) {
                        echo str_replace('_', '-', $settings['designName']) . ' ';
                        _e('Template', 'responsive-accordion-slider');
                    }else{
                    	echo "design-1 Template";
                    }
                    ?>
                </span></p>


				 <?php $html .= ob_get_clean();

                $html .=   '</span> 
						 </p>';
				
				$html .= '<input type="button" class="ras_select_template" data-option="' . esc_attr( $field['id'] ) . '" value="Select Design">';



				$html .= '<input type="hidden" id="' . esc_attr( $field['id'] ) . '" class="col-sm-4 regular-text ras_select_template_value" name="ras-accordion-slider-settings[' . esc_attr( $field['id'] ) . ']" data-setting="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $value ) . '">';
				

			

				$html .= '</div>';

				$html .= '</li>';
				$html .= '</ul>';
				$html .= '</div>';

				$html .= '</div>';
				break;

			case 'text':
				$html = '<input type="text" class="col-sm-4 regular-text" name="ras-accordion-slider-settings[' . esc_attr( $field['id'] ) . ']" data-setting="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $value ) . '">';
				break;

			case 'text-short':
				global $id, $post;
       			$post_id = isset( $post->ID ) ? $post->ID : (int) $id;

				$shortcode = '[resp-slider id="' . $post_id . '"]';
				
				$html = '<input type="text" class="col-sm-10 regular-text" style="width:100%;padding:15px;" onclick="select()" name="ras-accordion-slider-settings[' . esc_attr( $field['id'] ) . ']" data-setting="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $shortcode ) . '" readonly>';
				break;
			case 'text-php':

				
				global $id, $post;
       			$post_id = isset( $post->ID ) ? $post->ID : (int) $id;

				$shortcode = '[resp-slider id="' . $post_id . '"]';
				$value = "<?php echo do_shortcode('$shortcode'); ?>";

				$html = '<input type="text" class="col-sm-10 regular-text" style="width:100%;padding:15px;" onclick="select()" name="ras-accordion-slider-settings[' . esc_attr( $field['id'] ) . ']" data-setting="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $value ) . '" readonly>';
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
				$html .= '<div class="col-sm-6 ras-slider-container img_ui_slider-container">';
					$html .= '<div id="slider_' . esc_attr( $field['id'] ) . '" class="ss-slider img_ui_slider"></div>';
					$html .= '<input readonly="readonly" data-setting="' . esc_attr( $field['id'] ) . '"  name="ras-accordion-slider-settings[' . esc_attr( $field['id'] ) . ']" type="text" class="col-sm-4 rl-slider img_ui_slider-input" id="input_' . esc_attr( $field['id'] ) . '" value="' . $value . '" ' . $attributes . '/>';
				$html .= '</div>';
				break;
			case 'color' :
				$html .= '<div class="accordion-slider-colorpickers">';
				$html .= '<input id="' . esc_attr( $field['id'] ) . '" class="col-sm-4 ras-color" data-setting="' . esc_attr( $field['id'] ) . '" name="ras-accordion-slider-settings[' . esc_attr( $field['id'] ) . ']" value="' . esc_attr( $value ) . '">';
				$html .= '</div>';
				break;
			case "toggle":
				$html .= '<div class="ras-slider-toggle">';
					$html .= '<input class="ras-slider-toggle__input" type="checkbox" data-setting="' . esc_attr( $field['id'] ) . '" id="' . esc_attr( $field['id'] ) . '" name="ras-accordion-slider-settings[' . esc_attr( $field['id'] ) . ']" value="1" ' . checked( 1, $value, false ) . '>';
					$html .= '<div class="ras-slider-toggle__items">';
						$html .= '<span class="ras-slider-toggle__track"></span>';
						$html .= '<span class="ras-slider-toggle__thumb"></span>';
						$html .= '<svg class="ras-slider-toggle__off" width="6" height="6" aria-hidden="true" role="img" focusable="false" viewBox="0 0 6 6"><path d="M3 1.5c.8 0 1.5.7 1.5 1.5S3.8 4.5 3 4.5 1.5 3.8 1.5 3 2.2 1.5 3 1.5M3 0C1.3 0 0 1.3 0 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3z"></path></svg>';
						$html .= '<svg class="ras-slider-toggle__on" width="2" height="6" aria-hidden="true" role="img" focusable="false" viewBox="0 0 2 6"><path d="M0 0h2v6H0z"></path></svg>';
					$html .= '</div>';
				$html .= '</div>';
				break;
			case "custom-code":
				$html = '<div class="accordion-slider-code-editor" data-syntax="' . esc_attr( $field['syntax'] ) . '">';
				$html .= '<textarea data-setting="' . esc_attr( $field['id'] ) . '" name="ras-accordion-slider-settings[' . esc_attr( $field['id'] ) . ']" id="accordion-slider-' . esc_attr( $field['id'] ) . '" class="large-text code accordion-slider-custom-editor-field"  rows="10" cols="50">' . wp_kses_post($value) . '</textarea>';
				$html .= '</div>';
				break;

			case 'select' :
				$html = '<select name="ras-accordion-slider-settings[' . esc_attr( $field['id'] ) . ']" data-setting="' . esc_attr( $field['id'] ) . '" style="max-width:33%;" class="col-sm-4 regular-text">';
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
			
			default:
				/* Filter for render custom field types */
				$html = apply_filters( "accordion_slider_wp_render_{$field['type']}_field_type", $html, $field, $value );
				break;
		}

		return $html;

	}

	public function design_reg_function( ) { ?>
		<!-- layout selector -->
		<div id="ras_design_popup" class="ras-template-popup" style="display: none;"> <?php
            $tempate_list = $this->design_template_list();
            foreach ($tempate_list as $key => $value) {
                $classes = explode(' ', $value['class']);
                foreach ($classes as $class) {
                    $all_class[] = $class;
                }
            }
            $count = array_count_values($all_class); ?>
            
            <?php echo '<div class="ras-template-cover">';
	            foreach ($tempate_list as $key => $value) {
	                if ($key == 'design-1' || $key == 'design-2') {
	                    $class = 'ras-lite'; 
	                } ?>
	                <div class="ras-template-thumbnail <?php echo $value['class'] . ' ' . $class; ?>">
	                    <div class="ras-template-thumbnail-inner">
	                        <img src="<?php echo RESP_ACCORDION_SLIDER_IMAGES_PATH . $value['image_name']; ?>" data-value="<?php echo $key; ?>" alt="<?php echo $value['template_name']; ?>" title="<?php echo $value['template_name']; ?>">
	      						<?php// if ($class == 'ras-lite') { ?>
		                                <?php if($key == 'design-1' || $key == 'design-2') {?>
	                            	<div class="ras-hover_overlay">
		                                <div class="ras-popup-template-name">
		                                    <div class="ras-popum-select"><a href="#"><?php _e('Select Template', 'responsive-accordion-slider'); ?></a></div>
		                                    <div class="ras-popup-view"><a href="<?php echo esc_attr( $value['demo_link'] ); ?>" target="_blank"><?php esc_html_e( 'View Demo', 'responsive-accordion-slider' ); ?></a></div>
		                                </div>
	                            	</div>
	                            	
	       						<?php }else{ ?>
	       							<div class="ras_overlay"></div>
									<div class="ras-img-hover_overlay">
										<img src="<?php echo esc_url( RESP_ACCORDION_SLIDER_IMAGES_PATH ) . 'pro-tag.png'; ?>" alt="Available in Pro" />
									</div>
	       							
	       							<div class="ras-hover_overlay">
		                                <div class="ras-popup-template-name">
		                                    <div class="ras-popup-view"><a href="<?php echo esc_attr( $value['demo_link'] ); ?>" target="_blank"><?php esc_html_e( 'View Demo', 'responsive-accordion-slider' ); ?></a></div>
		                                </div>
	                            	</div>

	       						<?php } ?>
	                    </div>
	                    <span class="ras-span-template-name"><?php echo $value['template_name']; ?></span>
	                </div>
                <?php }
            echo '</div>';
            echo '<h3 class="no-template" style="display: none;">' . __('No template found. Please try again', 'responsive-accordion-slider') . '</h3>'; ?>

		</div>
		<?php 
	}

	public function design_template_list() {
   $tempate_list = array(
        'design-1' => array(
            'template_name' => __('Design 1', 'responsive-accordion-slider'),
            'class' => 'grid free',
            'image_name' => 'design-1.jpg',
            'demo_link' => esc_url('https://demo.techknowprime.com/responsive-accordion-slider/'),
        ),
        'design-2' => array(
            'template_name' => __('Design 2', 'responsive-accordion-slider'),
            'class' => 'full-width free',
            'image_name' => 'design-1.jpg',
            'demo_link' => esc_url('https://demo.techknowprime.com/responsive-accordion-slider/design-2/'),
        ),
        'design-3' => array(
            'template_name' => __('Design 3', 'responsive-accordion-slider'),
            'class' => 'grid free',
            'image_name' => 'design-1.jpg',
            'demo_link' => esc_url('https://demo.techknowprime.com/accordion-slider-pro/design-3/'),
        ),
        'design-4' => array(
            'template_name' => __('Design 4', 'responsive-accordion-slider'),
            'class' => 'grid free',
            'image_name' => 'design-1.jpg',
            'demo_link' => esc_url('https://demo.techknowprime.com/accordion-slider-pro/design-4/'),
        ),
        'design-5' => array(
            'template_name' => __('Design 5', 'responsive-accordion-slider'),
            'class' => 'grid free',
            'image_name' => 'design-1.jpg',
            'demo_link' => esc_url('https://demo.techknowprime.com/accordion-slider-pro/design-5/'),
        ),
        'design-6' => array(
            'template_name' => __('Design 6', 'responsive-accordion-slider'),
            'class' => 'grid free',
            'image_name' => 'design-1.jpg',
            'demo_link' => esc_url('https://demo.techknowprime.com/accordion-slider-pro/design-6/'),
        ),
        'design-7' => array(
            'template_name' => __('Design 7', 'responsive-accordion-slider'),
            'class' => 'grid free',
            'image_name' => 'design-1.jpg',
            'demo_link' => esc_url('https://demo.techknowprime.com/accordion-slider-pro/design-7/'),
        ),
        'design-8' => array(
            'template_name' => __('Design 8', 'responsive-accordion-slider'),
            'class' => 'grid free',
            'image_name' => 'design-1.jpg',
            'demo_link' => esc_url('https://demo.techknowprime.com/accordion-slider-pro/design-8/'),
        ),
        'design-9' => array(
            'template_name' => __('Design 9', 'responsive-accordion-slider'),
            'class' => 'grid free',
            'image_name' => 'design-1.jpg',
            'demo_link' => esc_url('https://demo.techknowprime.com/accordion-slider-pro/design-9/'),
        ),
        'design-10' => array(
            'template_name' => __('Design 10', 'responsive-accordion-slider'),
            'class' => 'grid free',
            'image_name' => 'design-1.jpg',
            'demo_link' => esc_url('https://demo.techknowprime.com/accordion-slider-pro/design-10/'),
        ),
        'design-11' => array(
            'template_name' => __('Design 11', 'responsive-accordion-slider'),
            'class' => 'grid free',
            'image_name' => 'design-1.jpg',
            'demo_link' => esc_url('https://demo.techknowprime.com/accordion-slider-pro/design-11/'),
        ),
        'design-12' => array(
            'template_name' => __('Design 12', 'responsive-accordion-slider'),
            'class' => 'grid free',
            'image_name' => 'design-1.jpg',
            'demo_link' => esc_url('https://demo.techknowprime.com/accordion-slider-pro/design-12/'),
        )
    );
	    //ksort($tempate_list);
	    return $tempate_list;
	}

	public function print_accordion_slider_templates() { 
		include 'ras-js-templates.php';
	}
}

?>