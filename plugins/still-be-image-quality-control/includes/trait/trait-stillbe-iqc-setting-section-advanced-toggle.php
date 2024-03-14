<?php

// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
	exit;
}




trait StillBE_IQC_Setting_Section_Advanced_Toggle {


	// 
	protected function add_section_advanced_toggle() {

		// Add some Setting Sections
		add_action( 'admin_init', function() {

			// * Advanced Settings Section
			add_settings_section(
				STILLBE_IQ_PREFIX. 'ss-advanced-toggle',   // Section ID (Slug)
				esc_html( __( 'Advanced Settings', 'still-be-image-quality-control' ). ' 1' ),   // Section Title
				array( $this, 'render_sd_advanced_toggle' ),   // Rederer
				STILLBE_IQ_PREFIX. 'setting-page'   // Rendering Page
			);

		}, 11 );

		//////////////////////////
		// Add some Setting Fields
		// * Advanced Settings Section
		add_action( 'admin_init', function() {

			// Guarantee a Secure Filename
			add_settings_field(
				STILLBE_IQ_PREFIX. 'sf-safe-name',   // Field ID (Slug)
				esc_html__( 'Guarantee a Secure Filename', 'still-be-image-quality-control' ),   // Field Label
				array( $this, 'render_toggle_options' ),   // Rederer
				STILLBE_IQ_PREFIX. 'setting-page',   // Rendering Page
				STILLBE_IQ_PREFIX. 'ss-advanced-toggle',   // Section
				array(   // Arguments for Renderer Function
					'field'       => 'safe-name',
					'description' => esc_html__( 'Convert the filename to a safe name for URL when its name contains anything other than half-width alphanumeric characters and -_.', 'still-be-image-quality-control' ),
					'default'     => true,
				)
			);

			// Strip Exif Data
			add_settings_field(
				STILLBE_IQ_PREFIX. 'sf-strip-exif',   // Field ID (Slug)
				esc_html__( 'Strip Exif Data', 'still-be-image-quality-control' ),   // Field Label
				array( $this, 'render_toggle_options' ),   // Rederer
				STILLBE_IQ_PREFIX. 'setting-page',   // Rendering Page
				STILLBE_IQ_PREFIX. 'ss-advanced-toggle',   // Section
				array(   // Arguments for Renderer Function
					'field'       => 'strip-exif',
					'description' => array(
						esc_html__( 'Strip EXIF data from images.', 'still-be-image-quality-control' ),
						esc_html__( 'All GPS location, camera, lens, exposure informations, etc. will be deleted.', 'still-be-image-quality-control' ). '<br>'.
						esc_html__( 'This leads to secure uploading of images taken with location-enabled smartphones and compatible cameras.', 'still-be-image-quality-control' ),
					),
					'default'     => STILLBE_IQ_ENABLE_STRIP_EXIF,
				)
			);

			// Autoset Alt from Exif
			add_settings_field(
				STILLBE_IQ_PREFIX. 'sf-autoset-alt',   // Field ID (Slug)
				esc_html__( 'Autoset Alt from Exif', 'still-be-image-quality-control' ),   // Field Label
				array( $this, 'render_toggle_options' ),   // Rederer
				STILLBE_IQ_PREFIX. 'setting-page',   // Rendering Page
				STILLBE_IQ_PREFIX. 'ss-advanced-toggle',   // Section
				array(   // Arguments for Renderer Function
					'field'       => 'autoset-alt',
					'description' => esc_html__( 'Alt can be set automatically from the EXIF of the image when uploading.', 'still-be-image-quality-control' ),
					'default'     => STILLBE_IQ_AUTOSET_ALT_FROM_EXIF,
				)
			);

			// Optimize "srcset" Attribute
			add_settings_field(
				STILLBE_IQ_PREFIX. 'sf-optimize-srcset',   // Field ID (Slug)
				esc_html__( 'Optimize "srcset" Attribute', 'still-be-image-quality-control' ),   // Field Label
				array( $this, 'render_toggle_options' ),   // Rederer
				STILLBE_IQ_PREFIX. 'setting-page',   // Rendering Page
				STILLBE_IQ_PREFIX. 'ss-advanced-toggle',   // Section
				array(   // Arguments for Renderer Function
					'field'       => 'optimize-srcset',
					'description' => esc_html__( 'Remove images larger than the image size of the "src" attribute from the "srcset".', 'still-be-image-quality-control' ),
					'default'     => STILLBE_IQ_OPTIMIZE_SRCSET,
				)
			);

			// Force Adding the Query String for Image Cache Clear
			add_settings_field(
				STILLBE_IQ_PREFIX. 'sf-force-cache-clear',   // Field ID (Slug)
				esc_html__( 'Force Adding the Query String for Image Cache Clear', 'still-be-image-quality-control' ),   // Field Label
				array( $this, 'render_toggle_options' ),   // Rederer
				STILLBE_IQ_PREFIX. 'setting-page',   // Rendering Page
				STILLBE_IQ_PREFIX. 'ss-advanced-toggle',   // Section
				array(   // Arguments for Renderer Function
					'field'       => 'force-cache-clear',
					'description' => array(
						esc_html__( 'Force adding a query string to clear the image cache.', 'still-be-image-quality-control' ). '<br>'.
						esc_html__( 'It will be granted automatically if the image is recompressed using this plugin (Only Version 1.2.0+).', 'still-be-image-quality-control' ),
						esc_html__( 'Leave it enabled if images will be replaced by other plugins or manually.', 'still-be-image-quality-control' ). '<br>'.
						esc_html__( 'If recompressed with this plugin, disabling it eliminates the need to retrieve the file modification each time.', 'still-be-image-quality-control' ),
					),
					'default'     => STILLBE_IQ_ENABLE_FORCE_CACHE_CLEAR,
				)
			);

			// Add a Suffix to Indicate Quality Level
			add_settings_field(
				STILLBE_IQ_PREFIX. 'sf-level-suffix',   // Field ID (Slug)
				esc_html__( 'Add a Quality Level Suffix', 'still-be-image-quality-control' ),   // Field Label
				array( $this, 'render_toggle_options' ),   // Rederer
				STILLBE_IQ_PREFIX. 'setting-page',   // Rendering Page
				STILLBE_IQ_PREFIX. 'ss-advanced-toggle',   // Section
				array(   // Arguments for Renderer Function
					'field'       => 'level-suffix',
					'description' => array(
						esc_html__( 'You can add a suffix to the resized file name to indicate the compression quality.', 'still-be-image-quality-control' ),
						esc_html__( 'Please care that the file name will be changed.', 'still-be-image-quality-control' ),
					),
					'default'     => STILLBE_IQ_ENABLE_QUALITY_VALUE_SUFFIX,
				)
			);

			// Enable Interlace for JPEG
			add_settings_field(
				STILLBE_IQ_PREFIX. 'sf-enable-progressive-jpeg',   // Field ID (Slug)
				esc_html__( 'Enable Progressive JPEG', 'still-be-image-quality-control' ),   // Field Label
				array( $this, 'render_toggle_options' ),   // Rederer
				STILLBE_IQ_PREFIX. 'setting-page',   // Rendering Page
				STILLBE_IQ_PREFIX. 'ss-advanced-toggle',   // Section
				array(   // Arguments for Renderer Function
					'field'       => 'enable-progressive-jpeg',
					'description' => array(
						esc_html__( 'Enable progressive JPEG flag.', 'still-be-image-quality-control' ),
						esc_html__( 'If user&#39s network is unstable, the image will be displayed gradually from the low resolution.', 'still-be-image-quality-control' ),
						esc_html__( 'However, decoding requires the client to be about three times as heavy as disabling it.', 'still-be-image-quality-control' ). '<br>'.
						esc_html__( 'You may need to be careful when your site has an extremely large number of images.', 'still-be-image-quality-control' ),
						esc_html__( 'WebP does not offer a progressive or interlaced decoding refresh in the JPEG or PNG sense.', 'still-be-image-quality-control' ). '<br>'.
						sprintf( __( 'Refer: <a href="%1$s">%1$s</a>', 'still-be-image-quality-control' ), esc_url( 'https://developers.google.com/speed/webp/faq#does_webp_support_progressive_or_interlaced_display' ) ),
					),
					'default'     => STILLBE_IQ_ENABLE_INTERLACE_JPEG,
				)
			);

			// Enable Interlace for PNG
			add_settings_field(
				STILLBE_IQ_PREFIX. 'sf-enable-interlace-png',   // Field ID (Slug)
				esc_html__( 'Enable Interlace PNG', 'still-be-image-quality-control' ),   // Field Label
				array( $this, 'render_toggle_options' ),   // Rederer
				STILLBE_IQ_PREFIX. 'setting-page',   // Rendering Page
				STILLBE_IQ_PREFIX. 'ss-advanced-toggle',   // Section
				array(   // Arguments for Renderer Function
					'field'       => 'enable-interlace-png',
					'description' => array(
						esc_html__( 'Enable interlace PNG flag.', 'still-be-image-quality-control' ),
						esc_html__( 'If user&#39s network is unstable, the image will be displayed gradually from the low resolution.', 'still-be-image-quality-control' ),
						esc_html__( 'However, decoding requires the client to be about three times as heavy as disabling it.', 'still-be-image-quality-control' ). '<br>'.
						esc_html__( 'You may need to be careful when your site has an extremely large number of images.', 'still-be-image-quality-control' ),
						esc_html__( 'WebP does not offer a progressive or interlaced decoding refresh in the JPEG or PNG sense.', 'still-be-image-quality-control' ). '<br>'.
						sprintf( __( 'Refer: <a href="%1$s">%1$s</a>', 'still-be-image-quality-control' ), esc_url( 'https://developers.google.com/speed/webp/faq#does_webp_support_progressive_or_interlaced_display' ) ),
					),
					'default'     => STILLBE_IQ_ENABLE_INTERLACE_PNG,
				)
			);

			// Enable Indexed Color when the Original Image is Indexed Color
			add_settings_field(
				STILLBE_IQ_PREFIX. 'sf-enable-png8',   // Field ID (Slug)
				esc_html__( 'Enable Index Color (PNG8)', 'still-be-image-quality-control' ),   // Field Label
				array( $this, 'render_toggle_options' ),   // Rederer
				STILLBE_IQ_PREFIX. 'setting-page',   // Rendering Page
				STILLBE_IQ_PREFIX. 'ss-advanced-toggle',   // Section
				array(   // Arguments for Renderer Function
					'field'       => 'enable-png8',
					'description' => array(
						esc_html__( 'If the original PNG image is indexed color (PNG8), the resized PNG image is also converted to indexed color.', 'still-be-image-quality-control' ). '<br>'.
						esc_html__( 'Antialiasing may be noticeable because the number of colors is limited to a maximum of 256 colors.', 'still-be-image-quality-control' ),
						'StillBE_WP_Image_Editor_Imagick' === $this->_editor ? '' :
							esc_html__( 'Due to the use of the GD library, PNG8 may lose transparency. We recommend an environment where Imagick can be used.', 'still-be-image-quality-control' ),
					),
					'default'     => STILLBE_IQ_ENABLE_INDEX_COLOR,
				)
			);

			// Forced to Convert to Indexed Color
			add_settings_field(
				STILLBE_IQ_PREFIX. 'sf-enable-png8-force',   // Field ID (Slug)
				esc_html__( 'Force Index Color (PNG8)', 'still-be-image-quality-control' ),   // Field Label
				array( $this, 'render_toggle_options' ),   // Rederer
				STILLBE_IQ_PREFIX. 'setting-page',   // Rendering Page
				STILLBE_IQ_PREFIX. 'ss-advanced-toggle',   // Section
				array(   // Arguments for Renderer Function
					'field'       => 'enable-png8-force',
					'description' => array(
						esc_html__( 'Forces the resized PNG image to be converted to indexed color.', 'still-be-image-quality-control' ),
						esc_html__( 'Full-Color (True Color) PNGs are likely to have strong banding, so enable them only on a site that only use PNGs with a limited number of colors.', 'still-be-image-quality-control' ),
					),
					'default'     => STILLBE_IQ_ENABLE_INDEX_COLOR_FORCE,
				)
			);

			// Enable WebP
			add_settings_field(
				STILLBE_IQ_PREFIX. 'sf-enable-webp',   // Field ID (Slug)
				esc_html__( 'Enable Automatically Generating WebP', 'still-be-image-quality-control' ),   // Field Label
				array( $this, 'render_toggle_options' ),   // Rederer
				STILLBE_IQ_PREFIX. 'setting-page',   // Rendering Page
				STILLBE_IQ_PREFIX. 'ss-advanced-toggle',   // Section
				array(   // Arguments for Renderer Function
					'field'       => 'enable-webp',
					'description' => array(
						esc_html__( 'Automatically generate WebP so that it can be delivered instead of JPEG or PNG.', 'still-be-image-quality-control' ),
						esc_html__( 'If user&#39s browser supports WebP format, WebP will be delivered preferentially.', 'still-be-image-quality-control' ),
						'<small class="your-server-status">'. __( 'WebP enablement on your server:', 'still-be-image-quality-control' ).
							(
								$this->_editor && $this->_editor::supports_mime_type( 'image/webp' ) ?
									'<em class="available">'. esc_html__( 'Available', 'still-be-image-quality-control' ) :
									'<em class="unavailable">'. esc_html__( 'Unavailable', 'still-be-image-quality-control' )
							).
						'</em></small>',
					),
					'default'     => STILLBE_IQ_ENABLE_WEBP,
				)
			);

			// @since 0.9.0
			//   Enable Conversion in "cwebp" if the Extension Plugin is Installed
			if( stillbe_iqc_is_extended() ) {

				// Enable cwep Liberary
				add_settings_field(
					STILLBE_IQ_PREFIX. 'sf-enable-cwebp',   // Field ID (Slug)
					__( 'Use cwebp Utility', 'still-be-image-quality-control' ),   // Field Label
					array( $this, 'render_toggle_options' ),   // Rederer
					STILLBE_IQ_PREFIX. 'setting-page',   // Rendering Page
					STILLBE_IQ_PREFIX. 'ss-advanced-toggle',   // Section
					array(   // Arguments for Renderer Function
						'field'       => 'enable-cwebp',
						'description' => array(
							esc_html__( 'Use the &quot;cwebp&quot; utility when generating WebP.', 'still-be-image-quality-control' ). '<br>'.
							esc_html__( '(You need to have cwebp installed on your server. If not, it will not be used)', 'still-be-image-quality-control' ),
							esc_html__( 'Enabling cwebp gives you more options than generating WebP with Imagick or GD.', 'still-be-image-quality-control' ),
							'<small class="your-server-status">'. __( '&quot;cwebp&quot; enablement on your server:', 'still-be-image-quality-control' ).
								(
									stillbe_iqc_is_enabled_cwebp() ?
										'<em class="available">'. esc_html__( 'Available', 'still-be-image-quality-control' ) :
										'<em class="unavailable">'. esc_html__( 'Unavailable', 'still-be-image-quality-control' )
								).
							'</em></small>',
						),
						'default'     => STILLBE_IQ_ENABLE_CWEBP_LIBRARY,
					)
				);

				// Enable Lossless Compression
				add_settings_field(
					STILLBE_IQ_PREFIX. 'sf-enable-webp-lossless',   // Field ID (Slug)
					__( 'Enable WebP Lossless Compression', 'still-be-image-quality-control' ),   // Field Label
					array( $this, 'render_toggle_options' ),   // Rederer
					STILLBE_IQ_PREFIX. 'setting-page',   // Rendering Page
					STILLBE_IQ_PREFIX. 'ss-advanced-toggle',   // Section
					array(   // Arguments for Renderer Function
						'field'       => 'enable-webp-lossless',
						'description' => array(
							esc_html__( 'You can enable lossless compression when compressing PNG.', 'still-be-image-quality-control' ),
							esc_html__( 'The compression level for lossless compression uses the PNG settings.', 'still-be-image-quality-control' ). '<br>'.
							esc_html__( 'If you feel that PNG compression is slow, lower the quality level of PNG or disable this option.', 'still-be-image-quality-control' ),
							esc_html__( 'This option is only valid if &quot;cwebp&quot; is enabled.', 'still-be-image-quality-control' ),
							'<small class="your-server-status">'. __( 'Lossless compression enablement on your server:', 'still-be-image-quality-control' ).
								(
									stillbe_iqc_is_enabled_cwebp() ?
										'<em class="available">'. esc_html__( 'Available', 'still-be-image-quality-control' ) :
										'<em class="unavailable">'. esc_html__( 'Unavailable', 'still-be-image-quality-control' )
								).
							'</em></small>',
						),
						'default'     => STILLBE_IQ_ENABLE_WEBP_LOSSLESS,
					)
				);

				// Enable Near Lossless Compression
				add_settings_field(
					STILLBE_IQ_PREFIX. 'sf-enable-webp-near-lossless',   // Field ID (Slug)
					__( 'Enable WebP Near-Lossless Compression', 'still-be-image-quality-control' ),   // Field Label
					array( $this, 'render_toggle_options' ),   // Rederer
					STILLBE_IQ_PREFIX. 'setting-page',   // Rendering Page
					STILLBE_IQ_PREFIX. 'ss-advanced-toggle',   // Section
					array(   // Arguments for Renderer Function
						'field'       => 'enable-webp-near-lossless',
						'description' => array(
							esc_html__( 'You can enable near-lossless compression when compressing PNG.', 'still-be-image-quality-control' ). '<br>'.
							esc_html__( 'Near-Lossless allows for less size while maintaining quality comparable to lossless compression. (Lossy compression)', 'still-be-image-quality-control' ),
							esc_html__( 'The compression level for near-lossless uses the WebP settings.', 'still-be-image-quality-control' ),
							esc_html__( 'This option is only valid if &quot;cwebp&quot; and &quot;Lossless&quot; option above is enabled.', 'still-be-image-quality-control' ). '<br>'.
							esc_html__( 'The supported cwebp version is 0.5.0 or higher.', 'still-be-image-quality-control' ),
							'<small class="your-server-status">'. __( 'Near-Lossless compression enablement on your server:', 'still-be-image-quality-control' ).
								(
									stillbe_iqc_extends_chk_near_lossless() ?
										'<em class="available">'. esc_html__( 'Available', 'still-be-image-quality-control' ) :
										'<em class="unavailable">'. esc_html__( 'Unavailable', 'still-be-image-quality-control' )
								).
							'</em><i>ver. '. stillbe_iqc_extends_get_cwebp_ver(). '</i></small>',
						),
						'default'     => STILLBE_IQ_ENABLE_WEBP_NEAR_LOSSLESS,
					)
				);

			}

			// Enable decimal values timeout in WP-Cron
			add_settings_field(
				STILLBE_IQ_PREFIX. 'sf-enable-decimal-timeout-wpcron',   // Field ID (Slug)
				esc_html__( 'Optimize Delay by WP-Cron', 'still-be-image-quality-control' ),   // Field Label
				array( $this, 'render_toggle_options' ),   // Rederer
				STILLBE_IQ_PREFIX. 'setting-page',   // Rendering Page
				STILLBE_IQ_PREFIX. 'ss-advanced-toggle',   // Section
				array(   // Arguments for Renderer Function
					'field'       => 'enable-decimal-timeout-wpcron',
					'description' => array(
						esc_html__( 'Optimizes the delay in generating page when images are re-compressed by WP-Cron.', 'still-be-image-quality-control' ). '<br>'.
						esc_html__( 'Resolves a problem with WP-Cron timeout not being handled correctly when cURL is available and that version is not 7.32.0 or later.', 'still-be-image-quality-control' ),
						esc_html__( 'for developers: change to use fsockopen because decimal values cannot be used for timeout in versions older than 7.32.0.', 'still-be-image-quality-control' ),
					),
					'default'     => STILLBE_IQ_ENABLE_DECIMAL_TIMEOUT_WPCRON,
				)
			);

		}, 16 );

	}


	// Section Description; Advanced Setting
	public function render_sd_advanced_toggle() {

		echo '<p>'. esc_html__( 'Other advanced settings.', 'still-be-image-quality-control' ). '<br>';
		echo esc_html__( 'If you are not sure, do not change it unnecessarily.', 'still-be-image-quality-control' ). '</p>';

		echo '<p>'. esc_html__( '(*) : Default Setting', 'still-be-image-quality-control' ). '</p>';

	}


	// Advanced Toggle Options
	public function render_toggle_options( $args = array() ) {

		if( empty( $args['field'] ) ) {
			return;
		}

		// Field
		$field = $args['field'];

		// Current Setting
		$toggles = $this->current['toggle'] ?: array();
		if( isset( $toggles[ $field ] ) ) {
			$toggle = ! empty( $toggles[ $field ] );
		} else {
			$toggle = isset( $args['default'] ) ? $args['default'] : false;
		}

		// Input Name
		$name = self::SETTING_NAME. '[toggle]['. $field. ']';

		// Render HTML
		echo '<div class="field-line">';
		echo   '<label>';
		echo     '<input type="radio" name="'. esc_attr( $name ). '" value="true"'.  ( $toggle ? esc_attr( ' checked' ) : '' ). '>';
		echo     '<span>'. esc_html__( 'Enable', 'still-be-image-quality-control' ). '</span>';
		if( $args['default'] ) {
			echo '<span class="default-setting">*</span>';
		}
		echo   '</label>';
		echo   '<label>';
		echo     '<input type="radio" name="'. esc_attr( $name ). '" value="false"'. ( $toggle ? '' : esc_attr( ' checked' ) ). '>';
		echo     '<span>'. esc_html__( 'Disable', 'still-be-image-quality-control' ). '</span>';
		if( ! $args['default'] ) {
			echo '<span class="default-setting">*</span>';
		}
		echo   '</label>';
		echo '</div>';

		// Echo Descriptions
		if( ! empty( $args['description'] ) ) {
			foreach( (array) $args['description'] as $d ) {
				if( empty( trim( $d ) ) ) {
					continue;
				}
				echo '<p>'. wp_kses( $d, $this->allowed_tags_for_note ). '</p>';
			}
		}

	}


}