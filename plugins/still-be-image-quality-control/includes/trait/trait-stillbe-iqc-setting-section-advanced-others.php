<?php

// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
	exit;
}




trait StillBE_IQC_Setting_Section_Advanced_Others {


	// 
	protected function add_section_advanced_others() {

		// Add some Setting Sections
		add_action( 'admin_init', function() {

			// * Advanced Settings Section
			add_settings_section(
				STILLBE_IQ_PREFIX. 'ss-advanced-others',   // Section ID (Slug)
				esc_html( __( 'Advanced Settings', 'still-be-image-quality-control' ). ' 2' ),   // Section Title
				array( $this, 'render_sd_advanced_others' ),   // Rederer
				STILLBE_IQ_PREFIX. 'setting-page'   // Rendering Page
			);

		}, 11 );

		//////////////////////////
		// Add some Setting Fields
		// * Advanced Settings Section
		add_action( 'admin_init', function() {

			// Change the Big Image Size
			add_settings_field(
				STILLBE_IQ_PREFIX. 'sf-big-threshold',   // Field ID (Slug)
				esc_html__( 'Big Image Threshold', 'still-be-image-quality-control' ),   // Field Label
				array( $this, 'render_big_threshold' ),   // Rederer
				STILLBE_IQ_PREFIX. 'setting-page',   // Rendering Page
				STILLBE_IQ_PREFIX. 'ss-advanced-others',   // Section
				array()   // Arguments for Renderer Function
			);

			// Quality Level for Site Icon
			add_settings_field(
				STILLBE_IQ_PREFIX. 'sf-quality-level-site-icon',   // Field ID (Slug)
				esc_html__( 'Quality Level of Compression for Site Icon', 'still-be-image-quality-control' ),   // Field Label
				array( $this, 'render_quality_level_table_site_icon' ),   // Rederer
				STILLBE_IQ_PREFIX. 'setting-page',   // Rendering Page
				STILLBE_IQ_PREFIX. 'ss-advanced-others',   // Section
				array()   // Arguments for Renderer Function
			);

			// Reset Settings
			add_settings_field(
				STILLBE_IQ_PREFIX. 'sf-reset-settings',   // Field ID (Slug)
				esc_html__( 'Reset Settings', 'still-be-image-quality-control' ),   // Field Label
				array( $this, 'render_reset_settings' ),   // Rederer
				STILLBE_IQ_PREFIX. 'setting-page',   // Rendering Page
				STILLBE_IQ_PREFIX. 'ss-advanced-others',   // Section
				array()   // Arguments for Renderer Function
			);

			// Show Settings
			add_settings_field(
				STILLBE_IQ_PREFIX. 'sf-show-settings',   // Field ID (Slug)
				esc_html__( 'Show Settings', 'still-be-image-quality-control' ),   // Field Label
				array( $this, 'render_show_settings' ),   // Rederer
				STILLBE_IQ_PREFIX. 'setting-page',   // Rendering Page
				STILLBE_IQ_PREFIX. 'ss-advanced-others',   // Section
				array()   // Arguments for Renderer Function
			);

		}, 16 );

	}


	// Section Description; Advanced Setting
	public function render_sd_advanced_others() {

		echo '<p>'. esc_html__( 'Other advanced settings.', 'still-be-image-quality-control' ). '<br>';
		echo esc_html__( 'If you are not sure, do not change it unnecessarily.', 'still-be-image-quality-control' ). '</p>';

	}


	// Big Image Threshold
	public function render_big_threshold( $args ) {

		// Setting
		$threshold = isset( $this->current['big-threshold'] ) ? absint( $this->current['big-threshold'] ) : null;

		// Render HTML
		echo '<div class="field-line">';
		echo   '<input type="number" name="'. esc_attr( self::SETTING_NAME. '[big-threshold]' ). '" value="'. esc_attr( $threshold ). '">';
		echo   '<span class="unit-px">px</span>';
		echo '</div>';
		echo '<p>'.  esc_html__( 'Larger images are automatically scaled down when you upload the image to WP.', 'still-be-image-quality-control' );
		echo '<br>'. esc_html__( 'Change this threshold. The default is 2560px.', 'still-be-image-quality-control' ). '</p>';
		echo '<p>'.  esc_html__( 'Set to 0 to remove the limit.', 'still-be-image-quality-control' ). '</p>';
		echo '<p>'.  esc_html__( '* If not set, use the default value.', 'still-be-image-quality-control' ). '</p>';

	}


	// Render Quality Level Table for Site Icon
	public function render_quality_level_table_site_icon( $args ) {

		// Site Icon Class
		require_once( ABSPATH. 'wp-admin/includes/class-wp-site-icon.php' );
		$site_icon  = new WP_Site_Icon;
		$icon_sizes = apply_filters( 'intermediate_image_sizes_advanced', $site_icon->additional_sizes(), array(), 0 );
		uasort( $icon_sizes, function( $a, $b ) {
			if( $a['width'] !== $b['width'] ) {
				return $a['width'] - $b['width'];
			}
			if( $a['height'] !== $b['height'] ) {
				return $a['height'] - $b['height'];
			}
			return (int) $a['crop'] - (int) $b['crop'];
		} );

		// Current Setting
		$qualities = empty( $this->current['quality'] ) ? array() : $this->current['quality'];

		// Checked Mark SVG
		$checked = '<img src="'. ( STILLBE_IQ_BASE_URL. 'asset/icon-checked.svg' ). '" style="width: auto; height: 1.2em;">';
		$allowed_img_tag = array(
			'img' => array(
				'src'   => array(),
				'style' => array(),
			),
		);

		// Render HTML
		echo '<p>'.  esc_html__( 'Set the quality level for the site icon.', 'still-be-image-quality-control' );
		echo '<br>'. esc_html__( 'If not set, use the "Default Quality Level" on the "General Settings" tab.', 'still-be-image-quality-control' ). '</p>';
		echo '<div class="scroll-table-wrapper">';
		echo   '<table class="quality-level-table">';
		echo     '<thead>';
		echo       '<tr>';
		echo         '<th>'. esc_html__( 'Size Name', 'still-be-image-quality-control' ). '</th>';
		echo         '<th>'. esc_html__( 'Max Width', 'still-be-image-quality-control' ). '</th>';
		echo         '<th>'. esc_html__( 'Max Height', 'still-be-image-quality-control' ). '</th>';
		echo         '<th>'. esc_html__( 'Cropping', 'still-be-image-quality-control' ). '</th>';
		echo         '<th>JPEG</th>';
		echo         '<th>PNG</th>';
		echo         '<th>WebP</th>';
		echo       '</tr>';
		echo     '</thead>';
		echo     '<tbody id="quality_level_table_body">';
		foreach( $icon_sizes as $name => $size ) {
			$q_jpeg = empty( $qualities[( $name. '_jpeg' )] ) ? '' : $qualities[( $name. '_jpeg' )];
			$q_png  = empty( $qualities[( $name. '_png'  )] ) ? '' : $qualities[( $name. '_png'  )];
			$q_webp = empty( $qualities[( $name. '_webp' )] ) ? '' : $qualities[( $name. '_webp' )];
			$_class = 'width-'. intval( $size['width'] ). ' height-'. intval( $size['height'] );
			echo   '<tr class="'. esc_attr( $_class ). '">';
			echo     '<th class="embed-image-size-name">'. esc_html( $name ). '</th>';
			echo     '<th>'. esc_html( ( empty( $size['width']  ) ? __( '(No Limit)', 'still-be-image-quality-control' ) : $size['width'].  'px' ) ). '</th>';
			echo     '<th>'. esc_html( ( empty( $size['height'] ) ? __( '(No Limit)', 'still-be-image-quality-control' ) : $size['height']. 'px' ) ). '</th>';
			echo     '<th>'. ( empty( $size['crop'] ) ? '-' : wp_kses( $checked, $allowed_img_tag ) ). '</th>';
			echo     '<td><input type="number" name="'. esc_attr( self::SETTING_NAME. '[quality]['. $name. '][jpeg]' ). '" value="'. esc_attr( $q_jpeg ). '"></td>';
			echo     '<td><input type="number" name="'. esc_attr( self::SETTING_NAME. '[quality]['. $name. '][png]'  ). '" value="'. esc_attr( $q_png  ). '"></td>';
			echo     '<td><input type="number" name="'. esc_attr( self::SETTING_NAME. '[quality]['. $name. '][webp]' ). '" value="'. esc_attr( $q_webp ). '"></td>';
			echo   '</tr>';
		}
		echo     '</tbody>';
		echo   '</table>';
		echo '</div>';

	}


	// Reset Settings
	public function render_reset_settings( $args ) {

		// Note
		echo '<p>'. esc_html__( 'Reset all settings to their default values.', 'still-be-image-quality-control' ). '</p>';

		// Render HTML
		echo '<button type="button" id="reset_settings" style="margin-top: 8px;">'. esc_html__( 'Restore to default value', 'still-be-image-quality-control' ). '</button>';

		// Nonce
		echo '<script>window.$stillbe.admin.reset = { nonce: "'. wp_create_nonce( 'sb-iqc-reset-settings' ). '" };</script>';

	}


	// Show Settings
	public function render_show_settings( $args ) {

		// Note
		echo '<p>'. esc_html__( 'Display the JSON of the settings. It cannot be edited directlly.', 'still-be-image-quality-control' ). '</p>';

		// Render HTML
		echo '<textarea readonly style="margin-top: 1em; width: 100%; height: 160px; font-size: 0.8em;" onclick="this.select()">';
		echo   esc_html( json_encode( $this->current, JSON_PRETTY_PRINT ) );
		echo '</textarea>';

	}


}