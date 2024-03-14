<?php

// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
	exit;
}




trait StillBE_IQC_Setting_Section_Test {


	// 
	protected function add_section_test() {

		// Add some Setting Sections
		add_action( 'admin_init', function() {

			// * Image Quality Test Section
			add_settings_section(
				STILLBE_IQ_PREFIX. 'ss-test-quality',   // Section ID (Slug)
				esc_html__( 'Test Quality Level', 'still-be-image-quality-control' ),   // Section Title
				array( $this, 'render_sd_test' ),   // Rederer
				STILLBE_IQ_PREFIX. 'setting-page'   // Rendering Page
			);

		}, 11 );

	}


	// Render Test Image Qulality
	public function render_sd_test() {

		// Is Enabled Lossless Compression?
		$is_lossless_options = $this->is_lossless_options;

		// Is Enabled Near-Lossless Compression?
		$is_near_lossless    = $this->is_near_lossless;

		// Toggle Options
		//   key: 'hook name' => value: 'label'
		$options = array(
			'stillbe_image_quality_control_enable_interlace'             => esc_html__( 'Enable Interlace/Progressive', 'still-be-image-quality-control' ),
			'stillbe_image_quality_control_enable_png_index_color'       => esc_html__( 'Enable Index Color (PNG8)', 'still-be-image-quality-control' ),
			'stillbe_image_quality_control_enable_png_index_color_force' => esc_html__( 'Force Index Color (PNG8)', 'still-be-image-quality-control' ),
		);
		if( stillbe_iqc_is_extended() ) {
			$options['stillbe_image_quality_control_enable_cwebp_lib']                 = esc_html__( 'Use cwebp Utility', 'still-be-image-quality-control' );
			$options['stillbe_image_quality_control_enable_webp_lossless_for_png_gif'] = esc_html__( 'Enable WebP Lossless Compression', 'still-be-image-quality-control' );
			$options['stillbe_image_quality_control_enable_webp_near_lossless']        = esc_html__( 'Enable WebP Near-Lossless Compression', 'still-be-image-quality-control' );
		}

		echo '<p>'. esc_html__( 'Test the quality level of the image.', 'still-be-image-quality-control' ). '<br>';
		echo esc_html__( 'Find the optimum quality level by considering the image quality, file size and prosessing time.', 'still-be-image-quality-control' ). '</p>';

		echo '<figure class="upload-image-thumb">';
		echo   '<img src="data:image/gif;base64,R0lGODlhAQABAGAAACH5BAEKAP8ALAAAAAABAAEAAAgEAP8FBAA7" class="sb-thumb" id="sb_thumb">';
		echo '</figure>';

		echo '<div class="upload_image_button_wrapper">';
		echo   '<button type="button" id="sb_select_img" data-title="'. esc_html__( 'Select Image', 'still-be-image-quality-control' ). '"';
		echo          ' data-submit="'. esc_html__( 'Select', 'still-be-image-quality-control' ). '">'. esc_html__( 'Select Image', 'still-be-image-quality-control' ). '</button>';
		echo   '<button type="button" id="sb_delete_img">'. esc_html__( 'Clear Image', 'still-be-image-quality-control' ). '</button>';
		echo   '<span id="sb_iqc_filename"></span>';
		echo '</div>';

		echo '<div class="sb-image-size-select">';
		echo   '<label for="sb_image_sizes">'. esc_html__( 'Select Size', 'still-be-image-quality-control' ). '</label>';
		echo   '<select id="sb_image_sizes"><option>'. esc_html__( '--- Please Select an Image ', 'still-be-image-quality-control' ). '</option></select>';
		echo '</div>';

		echo '<div class="sb-test-image-settings">';

		// Left Side
		echo   '<div class="left-side">';
		echo     '<b class="side-label left">'. esc_html__( 'Left Side', 'still-be-image-quality-control' ). '</b>';
		echo     '<label>'. esc_html__( 'Image Type', 'still-be-image-quality-control' );
		echo       '<select id="sb_ti_left_mime">';
		echo         '<option value="null">'. esc_html__( 'Unchanged', 'still-be-image-quality-control' ). '</option>';
		echo         '<option value="jpeg">JPEG</option>';
		echo         '<option value="png" >PNG</option>';
		echo         '<option value="webp">WebP</option>';
		echo       '</select>';
		echo     '</label>';
		echo     '<label>'. esc_html__( 'Quality Level', 'still-be-image-quality-control' );
		echo       '<input type="number" id="sb_ti_left_quality" size="6">';
		echo     '</label>';
		echo     '<p><small class="note">'. esc_html__( 'Invalid value is ignored and the current setting is used.', 'still-be-image-quality-control' ). '</small></p>';
		if( $is_lossless_options && ! $is_near_lossless ) {
			echo '<p><small class="note">'. esc_html__( 'WebP lossless compression uses PNG quality level.', 'still-be-image-quality-control' );
			echo '<br>'. esc_html__( 'For lossless compression, set a quality level in 1-9.', 'still-be-image-quality-control' ). '</small></p>';
		}
		// @since 1.1.0
		//   Toggle Options
		echo     '<div class="toggle-options-wrapper">';
		echo       '<input type="checkbox" id="toggle_options_display_left" class="display-none toggle-options-display">';
		echo       '<label for="toggle_options_display_left" class="show-toggle-options">';
		echo          esc_html__( 'Show Toggle Options.', 'still-be-image-quality-control' );
		echo       '</label>';
		echo       '<ul class="toggle-options">';
		foreach( $options as $hook_name => $label ) {
			echo     '<li>';
			echo       '<span>'. esc_html( $label ). '</span>';
			echo       '<div>';
			echo         '<label>';
			echo           '<input type="radio" name="'. esc_attr( "left.{$hook_name}" ). '" value="1" class="toggle-option-radio left">';
			echo           '<span>'. esc_html__( 'Enable', 'still-be-image-quality-control' ). '</span>';
			echo         '</label>';
			echo         '<label>';
			echo           '<input type="radio" name="'. esc_attr( "left.{$hook_name}" ). '" value="0" class="toggle-option-radio left">';
			echo           '<span>'. esc_html__( 'Disable', 'still-be-image-quality-control' ). '</span>';
			echo         '</label>';
			echo         '<label>';
			echo           '<input type="radio" name="'. esc_attr( "left.{$hook_name}" ). '" value="-" class="toggle-option-radio left" checked>';
			echo           '<span>'. esc_html__( '(No Change)', 'still-be-image-quality-control' ). '</span>';
			echo         '</label>';
			echo       '</div>';
			echo     '</li>';
		}
		echo       '</ul>';
		echo     '</div>';
		//   End of Toggle Options
		echo     '<table class="sb-ti-setting-table">';
		echo       '<tr>';
		echo         '<th>'. esc_html__( 'Quality Level', 'still-be-image-quality-control' ). '</th>';
		echo         '<td class="sb-ti-info sb-ti-quality left">-</td>';
		echo       '</tr>';
		// @since 0.9.0
		if( $is_lossless_options ) {
			echo   '<tr>';
			echo     '<th>'. esc_html__( 'Compression Mode', 'still-be-image-quality-control' ). '</th>';
			echo     '<td class="sb-ti-info sb-ti-compression-mode left">-</td>';
			echo   '</tr>';
			echo   '<tr>';
			echo     '<th>'. esc_html__( 'Loss-Less Compression Level', 'still-be-image-quality-control' ). '</th>';
			echo     '<td class="sb-ti-info sb-ti-lossless-level left">-</td>';
			echo   '</tr>';
		}
		echo       '<tr>';
		echo         '<th>'. esc_html__( 'Image Type', 'still-be-image-quality-control' ). '</th>';
		echo         '<td class="sb-ti-info sb-ti-mime left">-</td>';
		echo       '</tr>';
		echo       '<tr>';
		echo         '<th>'. esc_html__( 'File Size', 'still-be-image-quality-control' ). '</th>';
		echo         '<td class="sb-ti-info sb-ti-size left">-</td>';
		echo       '</tr>';
		echo       '<tr>';
		echo         '<th>'. esc_html__( 'Processing Time', 'still-be-image-quality-control' ). '</th>';
		echo         '<td class="sb-ti-info sb-ti-time left">-</td>';
		echo       '</tr>';
		echo       '<tr>';
		echo         '<th>'. esc_html__( 'Peak Memory', 'still-be-image-quality-control' ). '</th>';
		echo         '<td class="sb-ti-info sb-ti-memory left">-</td>';
		echo       '</tr>';
		echo     '</table>';
		echo   '</div>';

		// Right Side
		echo   '<div class="right-side">';
		echo     '<b class="side-label right">'. esc_html__( 'Right Side', 'still-be-image-quality-control' ). '</b>';
		echo     '<label>'. esc_html__( 'Image Type', 'still-be-image-quality-control' );
		echo       '<select id="sb_ti_right_mime">';
		echo         '<option value="null">'. esc_html__( 'Unchanged', 'still-be-image-quality-control' ). '</option>';
		echo         '<option value="jpeg">JPEG</option>';
		echo         '<option value="png" >PNG</option>';
		echo         '<option value="webp" selected>WebP</option>';
		echo       '</select>';
		echo     '</label>';
		echo     '<label>'. esc_html__( 'Quality Level', 'still-be-image-quality-control' );
		echo       '<input type="number" id="sb_ti_right_quality" size="6">';
		echo     '</label>';
		echo     '<p><small class="note">'. esc_html__( 'Invalid value is ignored and the current setting is used.', 'still-be-image-quality-control' ). '</small></p>';
		if( $is_lossless_options && ! $is_near_lossless ) {
			echo '<p><small class="note">'. esc_html__( 'WebP lossless compression uses PNG quality level.', 'still-be-image-quality-control' );
			echo '<br>'. esc_html__( 'For lossless compression, set a quality level in 1-9.', 'still-be-image-quality-control' ). '</small></p>';
		}
		// @since 1.1.0
		//   Toggle Options
		echo     '<div class="toggle-options-wrapper">';
		echo       '<input type="checkbox" id="toggle_options_display_right" class="display-none toggle-options-display">';
		echo       '<label for="toggle_options_display_right" class="show-toggle-options">';
		echo          esc_html__( 'Show Toggle Options.', 'still-be-image-quality-control' );
		echo       '</label>';
		echo       '<ul class="toggle-options">';
		foreach( $options as $hook_name => $label ) {
			echo     '<li>';
			echo       '<span>'. esc_html( $label ). '</span>';
			echo       '<div>';
			echo         '<label>';
			echo           '<input type="radio" name="'. esc_attr( "right.{$hook_name}" ). '" value="1" class="toggle-option-radio right">';
			echo           '<span>'. esc_html__( 'Enable', 'still-be-image-quality-control' ). '</span>';
			echo         '</label>';
			echo         '<label>';
			echo           '<input type="radio" name="'. esc_attr( "right.{$hook_name}" ). '" value="0" class="toggle-option-radio right">';
			echo           '<span>'. esc_html__( 'Disable', 'still-be-image-quality-control' ). '</span>';
			echo         '</label>';
			echo         '<label>';
			echo           '<input type="radio" name="'. esc_attr( "right.{$hook_name}" ). '" value="-" class="toggle-option-radio right" checked>';
			echo           '<span>'. esc_html__( '(No Change)', 'still-be-image-quality-control' ). '</span>';
			echo         '</label>';
			echo       '</div>';
			echo     '</li>';
		}
		echo       '</ul>';
		echo     '</div>';
		//   End of Toggle Options
		echo     '<table class="sb-ti-setting-table">';
		echo       '<tr>';
		echo         '<th>'. esc_html__( 'Quality Level', 'still-be-image-quality-control' ). '</th>';
		echo         '<td class="sb-ti-info sb-ti-quality right">-</td>';
		echo       '</tr>';
		// @since 0.9.0
		if( $is_lossless_options ) {
			echo   '<tr>';
			echo     '<th>'. esc_html__( 'Compression Mode', 'still-be-image-quality-control' ). '</th>';
			echo     '<td class="sb-ti-info sb-ti-mode right">-</td>';
			echo   '</tr>';
			echo   '<tr>';
			echo     '<th>'. esc_html__( 'Loss-Less Compression Level', 'still-be-image-quality-control' ). '</th>';
			echo     '<td class="sb-ti-info sb-ti-comp-level right">-</td>';
			echo   '</tr>';
		}
		echo       '<tr>';
		echo         '<th>'. esc_html__( 'Image Type', 'still-be-image-quality-control' ). '</th>';
		echo         '<td class="sb-ti-info sb-ti-mime right">-</td>';
		echo       '</tr>';
		echo       '<tr>';
		echo         '<th>'. esc_html__( 'File Size', 'still-be-image-quality-control' ). '</th>';
		echo         '<td class="sb-ti-info sb-ti-size right">-</td>';
		echo       '</tr>';
		echo       '<tr>';
		echo         '<th>'. esc_html__( 'Processing Time', 'still-be-image-quality-control' ). '</th>';
		echo         '<td class="sb-ti-info sb-ti-time right">-</td>';
		echo       '</tr>';
		echo       '<tr>';
		echo         '<th>'. esc_html__( 'Peak Memory', 'still-be-image-quality-control' ). '</th>';
		echo         '<td class="sb-ti-info sb-ti-memory right">-</td>';
		echo       '</tr>';
		echo     '</table>';
		echo   '</div>';

		echo '</div>';

		echo '<div><div id="sb_compare_image"></div></div>';

		$nonces = array(
			'generate_test_image' => wp_create_nonce( 'sb-iqc-generate-test-img' ),
			'get_attachment_meta' => wp_create_nonce( 'sb-iqc-get-attachment-meta' ),
		);

		echo '<script type="text/javascript">';
		echo   'window.$stillbe.admin.testImage = (window.$stillbe.admin.testImage || {});';
		echo   'window.$stillbe.admin.testImage.ajaxUrl = window.$stillbe.admin.ajaxUrl;';
		echo   'window.$stillbe.admin.testImage.nonces  = '. json_encode( $nonces ). ';';
		echo   'window.$stillbe.admin.testImage.sizes   = window.$stillbe.admin.wpImageSizes;';
		echo '</script>';

	}


}