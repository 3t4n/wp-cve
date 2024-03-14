<?php

// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
	exit;
}




trait StillBE_IQC_Setting_Section_General {


	// 
	protected function add_section_general() {

		// Add Setting Section of General
		add_action( 'admin_init', function() {

			// * Genral Settings Section
			add_settings_section(
				STILLBE_IQ_PREFIX. 'ss-general',   // Section ID (Slug)
				esc_html__( 'General Settings', 'still-be-image-quality-control' ),   // Section Title
				array( $this, 'render_sd_general' ),   // Rederer
				STILLBE_IQ_PREFIX. 'setting-page'   // Rendering Page
			);

		}, 11 );

		//////////////////////////
		// Add some Setting Fields
		// * General Settings Section
		add_action( 'admin_init', function() {

			// Quality Level
			add_settings_field(
				STILLBE_IQ_PREFIX. 'sf-quality-level',   // Field ID (Slug)
				esc_html__( 'Quality Level of Compression', 'still-be-image-quality-control' ),   // Field Label
				array( $this, 'render_quality_level_table' ),   // Rederer
				STILLBE_IQ_PREFIX. 'setting-page',   // Rendering Page
				STILLBE_IQ_PREFIX. 'ss-general',   // Section
				array()   // Arguments for Renderer Function
			);

			// Default Quality Level
			add_settings_field(
				STILLBE_IQ_PREFIX. 'sf-default-quality-level',   // Field ID (Slug)
				esc_html__( 'Default Quality Level', 'still-be-image-quality-control' ),   // Field Label
				array( $this, 'render_quality_level_default' ),   // Rederer
				STILLBE_IQ_PREFIX. 'setting-page',   // Rendering Page
				STILLBE_IQ_PREFIX. 'ss-general',   // Section
				array()   // Arguments for Renderer Function
			);

			// Quality Level for WebP of the Original Image
			add_settings_field(
				STILLBE_IQ_PREFIX. 'sf-original-quality-level',   // Field ID (Slug)
				esc_html__( 'Quality for webp of the original image', 'still-be-image-quality-control' ),   // Field Label
				array( $this, 'render_quality_level_original' ),   // Rederer
				STILLBE_IQ_PREFIX. 'setting-page',   // Rendering Page
				STILLBE_IQ_PREFIX. 'ss-general',   // Section
				array()   // Arguments for Renderer Function
			);

		}, 12 );

	}


	// Section Description; General Settings
	public function render_sd_general() {

		// Translate
		$this->set_js_translate( 'An error has occurred.... Please try again.' );
		$this->set_js_translate( 'All the regeneration is done!' );
		$this->set_js_translate( 'It was interrupted! Even if you close the page, you can restart the conversion from the continuation.' );
		$this->set_js_translate( 'You can use only alphanumeric and underscore.' );
		$this->set_js_translate( 'You cannot use duplicate name.' );
		$this->set_js_translate( 'Processed by WP-Cron' );
		$this->set_js_translate( 'All settings will revert to their default values. This change is irreversible.' );

		echo '<p>'. esc_html__( 'Set the quality level of image compression that are automatically resized when you upload to WP media library for each size.', 'still-be-image-quality-control' ). '</p>';

		// Debug
	//	echo '<p>'. json_encode( $this->current ). '</p>';

		echo '<script type="text/javascript">';
		echo   'window.$stillbe = (window.$stillbe || {});';
		echo   'window.$stillbe.admin = (window.$stillbe.admin || {});';
		echo   'window.$stillbe.admin.ajaxUrl      = "'. esc_url( admin_url( 'admin-ajax.php' ) ). '";';
		echo   'window.$stillbe.admin.translate    =  '. json_encode( $this->js_translate ). ';';
		echo   'window.$stillbe.admin.wpImageSizes =  '. json_encode( $this->get_all_sizes() ). ';';
		echo '</script>';

	}


	// Render Quality Level Table
	public function render_quality_level_table( $args ) {

		// Current Setting
		$qualities = empty( $this->current['quality'] )    ? array() : $this->current['quality'];
		$add_sizes = empty( $this->current['image-size'] ) ? array() : $this->current['image-size'];
		$add_size_names = array_column( $add_sizes, 'name' );

		// Image Sizes
		$sizes = $this->get_all_sizes();

		// Checked Mark SVG
		$checked = '<img src="'. ( STILLBE_IQ_BASE_URL. 'asset/icon-checked.svg' ). '" style="width: auto; height: 1.2em;">';
		$allowed_img_tag = array(
			'img' => array(
				'src'   => array(),
				'style' => array(),
			),
		);

		// Render HTML
		echo '<p>'. esc_html__( 'Set the quality level for each size when WordPress resize.', 'still-be-image-quality-control' );
		echo      '<br>'. esc_html__( 'Blanks and 0 or less are not set.', 'still-be-image-quality-control' ). '</p>';
		echo '<p>'. esc_html__( 'Enter a number from 1-100 for JPEG and WebP, and a number from 1-9 for PNG.', 'still-be-image-quality-control' ). '</p>';
		echo '<p>';
		echo   esc_html__( 'For JPEG / WebP, about 80-85 is a value with a good balance between file size and image quality.', 'still-be-image-quality-control' ). ' ';
		echo   esc_html__( 'The lower quality level, the smaller the file size, but it lacks the image quality.', 'still-be-image-quality-control' );
		echo '</p>';
		echo '<p>';
		echo   esc_html__( 'For PNG, 9 is the best choice because it has the highest compression ratio.', 'still-be-image-quality-control' ). ' ';
		echo   esc_html__( 'Lowering the quality level does not change the image quality, but increases the file size.', 'still-be-image-quality-control' ). '<br>';
		echo   esc_html__( 'Try changing the level to 5-6 only if the server load for PNG resizing is high.', 'still-be-image-quality-control' ). '<br>';
		if( $this->is_lossless_options && ! $this->is_near_lossless ) {
			echo esc_html__( 'Especially, large PNG images take a long time to compress into lossless WebP.', 'still-be-image-quality-control' ). ' ';
			echo esc_html__( '(WebP lossless compression uses PNG quality level)', 'still-be-image-quality-control' );
		}
		echo '</p>';

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
		$_i = 1;
		foreach( $sizes as $name => $size ) {
			$q_jpeg = empty( $qualities[( $name. '_jpeg' )] ) ? '' : $qualities[( $name. '_jpeg' )];
			$q_png  = empty( $qualities[( $name. '_png'  )] ) ? '' : $qualities[( $name. '_png'  )];
			$q_webp = empty( $qualities[( $name. '_webp' )] ) ? '' : $qualities[( $name. '_webp' )];
			$_class = 'width-'. intval( $size['width'] ). ' height-'. intval( $size['height'] );
			if( in_array( $name, $add_size_names ) ){
				$_class .= ' add-image-size-wrapper';
				echo '<tr class="'. esc_attr( $_class ). '">';
				echo   '<th><input type="text" name="'.     esc_attr( self::SETTING_NAME. '[image-size]['. $_i. '][name]' ).   '" value="'. esc_attr( $name ). '" class="add-image-size-name"></th>';
				echo   '<th><input type="number" name="'.   esc_attr( self::SETTING_NAME. '[image-size]['. $_i. '][width]' ).  '" value="'. esc_attr( $size['width'] ). '"></th>';
				echo   '<th><input type="number" name="'.   esc_attr( self::SETTING_NAME. '[image-size]['. $_i. '][height]' ). '" value="'. esc_attr( $size['height'] ). '"></th>';
				echo   '<th><input type="checkbox" name="'. esc_attr( self::SETTING_NAME. '[image-size]['. $_i. '][crop]' ).   '" value="1"'. ( empty( $size['crop'] ) ? '' : ' checked' ). '></th>';
				echo   '<td><input type="number" name="'.   esc_attr( self::SETTING_NAME. '[image-size]['. $_i. '][jpeg]' ).   '" value="'. esc_attr( $q_jpeg ). '"></td>';
				echo   '<td><input type="number" name="'.   esc_attr( self::SETTING_NAME. '[image-size]['. $_i. '][png]'  ).   '" value="'. esc_attr( $q_png  ). '"></td>';
				echo   '<td><input type="number" name="'.   esc_attr( self::SETTING_NAME. '[image-size]['. $_i. '][webp]' ).   '" value="'. esc_attr( $q_webp ). '"></td>';
				echo '</tr>';
				$_i++;
			} else {
				echo '<tr class="'. esc_attr( $_class ). '">';
				echo   '<th class="embed-image-size-name">'. esc_html( $name ). '</th>';
				echo   '<th>'. esc_html( ( empty( $size['width']  ) ? __( '(No Limit)', 'still-be-image-quality-control' ) : $size['width'].  'px' ) ). '</th>';
				echo   '<th>'. esc_html( ( empty( $size['height'] ) ? __( '(No Limit)', 'still-be-image-quality-control' ) : $size['height']. 'px' ) ). '</th>';
				echo   '<th>'. ( empty( $size['crop'] ) ? '-' : wp_kses( $checked, $allowed_img_tag ) ). '</th>';
				echo   '<td><input type="number" name="'. esc_attr( self::SETTING_NAME. '[quality]['. $name. '][jpeg]' ). '" value="'. esc_attr( $q_jpeg ). '"></td>';
				echo   '<td><input type="number" name="'. esc_attr( self::SETTING_NAME. '[quality]['. $name. '][png]'  ). '" value="'. esc_attr( $q_png  ). '"></td>';
				echo   '<td><input type="number" name="'. esc_attr( self::SETTING_NAME. '[quality]['. $name. '][webp]' ). '" value="'. esc_attr( $q_webp ). '"></td>';
				echo '</tr>';
			}
		}
		echo     '</tbody>';
		echo   '</table>';
		echo '</div>';

		echo '<div class="add-image-size-wrapper">';
		echo   '<button type="button" id="add_image_size_button" class="add-fields-button">'. esc_html__( 'Add a new image size', 'still-be-image-quality-control' ). '</button>';
		echo   '<template id="temp_quality_level_fields">';
		echo     '<tr class="added-image-size">';
		echo       '<th><input type="text" name="'.     esc_attr( self::SETTING_NAME. '[image-size][{{i}}][name]' ).   '" class="add-image-size-name"></th>';
		echo       '<th><input type="number" name="'.   esc_attr( self::SETTING_NAME. '[image-size][{{i}}][width]' ).  '"></th>';
		echo       '<th><input type="number" name="'.   esc_attr( self::SETTING_NAME. '[image-size][{{i}}][height]' ). '"></th>';
		echo       '<th><input type="checkbox" name="'. esc_attr( self::SETTING_NAME. '[image-size][{{i}}][crop]' ).   '" value="1"></th>';
		echo       '<th><input type="number" name="'.   esc_attr( self::SETTING_NAME. '[image-size][{{i}}][jpeg]' ).   '"></th>';
		echo       '<th><input type="number" name="'.   esc_attr( self::SETTING_NAME. '[image-size][{{i}}][png]' ).    '"></th>';
		echo       '<th><input type="number" name="'.   esc_attr( self::SETTING_NAME. '[image-size][{{i}}][webp]' ).   '"></th>';
		echo     '</tr>';
		echo   '</template>';
		echo '</div>';

		echo '<p><small>'. esc_html__( '* If you want to delete the added image size, leave the size name blank.', 'still-be-image-quality-control' ). '</small></p>';

	}


	// Render Default Quality Level
	public function render_quality_level_default( $args ) {

		// Current Setting
		$qualities = empty( $this->current['quality'] )    ? array() : $this->current['quality'];

		// Default Quality
		$def_jpeg = empty( $qualities['default_jpeg'] ) ? '' : $qualities['default_jpeg'];
		$def_png  = empty( $qualities['default_png']  ) ? '' : $qualities['default_png'] ;
		$def_webp = empty( $qualities['default_webp'] ) ? '' : $qualities['default_webp'];

		// Render HTML
		echo '<p>'. esc_html__( 'This quality levels are used when it is not set in the above table.', 'still-be-image-quality-control' ). '</p>';
		echo '<div class="scroll-table-wrapper">';
		echo   '<table class="quality-level-table">';
		echo     '<thead>';
		echo       '<tr>';
		echo         '<th>JPEG</th>';
		echo         '<th>PNG</th>';
		echo         '<th>WebP</th>';
		echo       '</tr>';
		echo     '</thead>';
		echo     '<tbody>';
		echo       '<tr>';
		echo         '<td><input type="number" name="'. esc_attr( self::SETTING_NAME. '[quality][default][jpeg]' ). '" value="'. esc_attr( $def_jpeg ). '"></td>';
		echo         '<td><input type="number" name="'. esc_attr( self::SETTING_NAME. '[quality][default][png]'  ). '" value="'. esc_attr( $def_png  ). '"></td>';
		echo         '<td><input type="number" name="'. esc_attr( self::SETTING_NAME. '[quality][default][webp]' ). '" value="'. esc_attr( $def_webp ). '"></td>';
		echo       '</tr>';
		echo     '</tbody>';
		echo   '</table>';
		echo '</div>';

	}


	// Render Quality Level for WebP of the Original Image
	public function render_quality_level_original( $args ) {

		// Current Setting
		$qualities = empty( $this->current['quality'] ) ? array() : $this->current['quality'];

		// Quality
		if( isset( $this->current['original-webp'] ) ) {
			$original_webp = $this->current['original-webp'];
		} else {
			$original_webp = empty( $qualities['original_webp'] ) ? array() : $qualities['original_webp'];
		}
		if( ! is_array( $original_webp ) ) {
			$_defaults = _stillbe_get_quality_level_array();
			$original_webp = self::chk_num_type( $original_webp, 'webp' ) ?: $_default['original_webp'];
			$original_webp = array(
				array(
					'lossy'    => $original_webp,
					'lossless' => 9,
				),
			);
		}

		// Is Enabled Lossless Option?
		$is_lossless_options = $this->is_lossless_options;

		// Max Size
		$big_threashold = apply_filters( 'big_image_size_threshold', 2560 );
		$big_threashold = $big_threashold ? (int) $big_threashold : $big_threashold;

		// Infinity Mark SVG
		$infinity = '<img src="'. ( STILLBE_IQ_BASE_URL. 'asset/icon-infinity.svg' ). '" style="width: auto; height: 1.2em;">';
		$allowed_img_tag = array(
			'img' => array(
				'src'   => array(),
				'style' => array(),
			),
		);

		// Render HTML
		echo '<p>'.  esc_html__( 'Set the quality level for WebP that is the same size as the uploaded original image.', 'still-be-image-quality-control' ). '</p>';
		echo '<p>'.  esc_html__( 'Uses a quality level that fits within the maximum size you specify.', 'still-be-image-quality-control' );
		echo '<br>'. esc_html__( '0 represents unlimited. Can only be used for either width or height.', 'still-be-image-quality-control' );
		echo '<div class="scroll-table-wrapper">';
		echo   '<table class="quality-level-table">';
		echo     '<thead>';
		echo       '<tr>';
		echo         '<th rowspan="2">'. esc_html__( 'Max Width',  'still-be-image-quality-control' ). '</th>';
		echo         '<th rowspan="2">'. esc_html__( 'Max Height', 'still-be-image-quality-control' ). '</th>';
		echo         '<th colspan="2" style="'. ( $is_lossless_options ? 'border-width: 1px; border-style: solid;' : '' ). '">'. esc_html__( 'Quality Level', 'still-be-image-quality-control' ). '</th>';
		echo       '</tr>';
		echo       '<tr style="'. ( $is_lossless_options ? '' : 'display: none;' ). '">';
		echo         '<th>'. esc_html__( 'Lossy', 'still-be-image-quality-control' ). '</th>';
		echo         '<th>'. esc_html__( 'Lossless', 'still-be-image-quality-control' ). '</th>';
		echo       '</tr>';
		echo     '</thead>';
		echo     '<tbody id="original_quality_table_body">';
		$i = -1;
		foreach( $original_webp as $threshold ) {
			if( 1 > ++$i || empty( $threshold ) || ! is_array( $threshold ) || ( 1 > $threshold['width'] && 1 > $threshold['height'] ) ) {
				continue;
			}
			echo   '<tr class="threashold">';
			echo     '<td>';
			echo       '<input type="number" name="'. esc_attr( self::SETTING_NAME. '[quality][original][webp]['. $i. '][width]'  ). '" value="'. esc_attr( $threshold['width'] ). '">';
			echo     '</td>';
			echo     '<td>';
			echo       '<input type="number" name="'. esc_attr( self::SETTING_NAME. '[quality][original][webp]['. $i. '][height]' ). '" value="'. esc_attr( $threshold['height'] ). '">';
			echo     '</td>';
			echo     '<td>';
			echo       '<input type="number" name="'. esc_attr( self::SETTING_NAME. '[quality][original][webp]['. $i. '][lossy]'    ). '" value="'. esc_attr( $threshold['lossy'] ). '">';
			echo     '</td>';
			echo     '<td style="'. ( $is_lossless_options ? '' : 'display: none;' ). '">';
			echo       '<input type="number" name="'. esc_attr( self::SETTING_NAME. '[quality][original][webp]['. $i. '][lossless]' ). '" value="'. esc_attr( $threshold['lossless'] ). '">';
			echo     '</td>';
			echo   '</tr>';
		}
		echo       '<tr id="orginal_quality_max">';
		echo         '<td>';
		echo           '<span>'. ( $big_threashold ? esc_html( $big_threashold. 'px' ) : wp_kses( $infinity, $allowed_img_tag ) ). '</span>';
	//	echo           '<input type="hidden" name="'. esc_attr( self::SETTING_NAME. '[quality][original][webp][0][width]'  ). '" value="'. esc_attr( $big_threashold ). '">';
		echo         '</td>';
		echo         '<td>';
		echo           '<span>'. ( $big_threashold ? esc_html( $big_threashold. 'px' ) : wp_kses( $infinity, $allowed_img_tag ) ). '</span>';
	//	echo           '<input type="hidden" name="'. esc_attr( self::SETTING_NAME. '[quality][original][webp][0][height]' ). '" value="'. esc_attr( $big_threashold ). '">';
		echo         '</td>';
		echo         '<td>';
		echo           '<input type="number" name="'. esc_attr( self::SETTING_NAME. '[quality][original][webp][0][lossy]'    ). '" value="'. esc_attr( $original_webp[0]['lossy']    ). '">';
		echo         '</td>';
		echo         '<td style="'. ( $is_lossless_options ? '' : 'display: none;' ). '">';
		echo           '<input type="number" name="'. esc_attr( self::SETTING_NAME. '[quality][original][webp][0][lossless]' ). '" value="'. esc_attr( $original_webp[0]['lossless'] ). '">';
		echo         '</td>';
		echo       '</tr>';
		echo     '</tbody>';
		echo   '</table>';
//		echo   '<label>';
//		echo     '<input type="number" name="'. esc_attr( self::SETTING_NAME. '[quality][original][webp]' ). '" value="'. esc_attr( $original_webp ). '">';
//		echo   '</label>';
		echo '</div>';

		echo '<div class="add-original-quality">';
		echo   '<button type="button" id="add_original_quality_button" class="add-fields-button">'. esc_html__( 'Add a new threshold', 'still-be-image-quality-control' ). '</button>';
		echo   '<template id="temp_original_quality_fields">';
		echo     '<tr class="threashold">';
		echo       '<td>';
		echo         '<input type="number" name="'. esc_attr( self::SETTING_NAME. '[quality][original][webp][{{i}}][width]'  ). '">';
		echo       '</td>';
		echo       '<td>';
		echo         '<input type="number" name="'. esc_attr( self::SETTING_NAME. '[quality][original][webp][{{i}}][height]' ). '">';
		echo       '</td>';
		echo       '<td>';
		echo         '<input type="number" name="'. esc_attr( self::SETTING_NAME. '[quality][original][webp][{{i}}][lossy]'    ). '">';
		echo       '</td>';
		echo       '<td style="'. ( $is_lossless_options ? '' : 'display: none;' ). '">';
		echo         '<input type="number" name="'. esc_attr( self::SETTING_NAME. '[quality][original][webp][{{i}}][lossless]' ). '">';
		echo       '</td>';
		echo     '</tr>';
		echo   '</template>';
		echo '</div>';

	}


}