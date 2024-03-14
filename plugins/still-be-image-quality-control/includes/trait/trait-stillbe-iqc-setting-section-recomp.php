<?php

// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
	exit;
}




trait StillBE_IQC_Setting_Section_ReComp {


	// 
	protected function add_section_recomp() {

		// Add some Setting Sections
		add_action( 'admin_init', function() {

			// * Recomp Settings Section
			add_settings_section(
				STILLBE_IQ_PREFIX. 'ss-recomp',   // Section ID (Slug)
				esc_html__( 'Re-compression', 'still-be-image-quality-control' ),   // Section Title
				array( $this, 'render_sd_recomp' ),   // Rederer
				STILLBE_IQ_PREFIX. 'setting-page'   // Rendering Page
			);

		}, 11 );

		//////////////////////////
		// Add some Setting Fields
		// * Recomp Settings Section
		add_action( 'admin_init', function() {

			// Run Re-Compression
			add_settings_field(
				STILLBE_IQ_PREFIX. 'sf-recompression',   // Field ID (Slug)
				esc_html__( 'Run Re-Compression', 'still-be-image-quality-control' ),   // Field Label
				array( $this, 'render_recompression' ),   // Rederer
				STILLBE_IQ_PREFIX. 'setting-page',   // Rendering Page
				STILLBE_IQ_PREFIX. 'ss-recomp',   // Section
				array()   // Arguments for Renderer Function
			);

			// Auto Regenerate using WP-Cron
			add_settings_field(
				STILLBE_IQ_PREFIX. 'sf-auto-regen-wpcron',   // Field ID (Slug)
				esc_html__( 'Auto Re-compression (WP-Cron)', 'still-be-image-quality-control' ),   // Field Label
				array( $this, 'render_auto_regen_wpcron' ),   // Rederer
				STILLBE_IQ_PREFIX. 'setting-page',   // Rendering Page
				STILLBE_IQ_PREFIX. 'ss-recomp',   // Section
				array()   // Arguments for Renderer Function
			);

		}, 16 );

	}


	// Section Description; Advanced Setting
	public function render_sd_recomp() {

		echo '<p>'. esc_html__( 'Re-compress the image with the currently saved settings.', 'still-be-image-quality-control' ). '</p>';

	}


	// Render Re-Compression
	public function render_recompression() {

		// Get IDs Cache
		$ids     = get_option( '_sb-iqc-image-ids' )  ?: stillbe_iqc_get_attachment_ids();
		$current = get_option( '_sb-iqc-current-id' ) ?: 0;
		$cache   = array(
			'ids'     => $ids,
			'current' => $current,
		);

		// Get Target Conditions
		$target = get_option( '_sb-iqc-recomp-target-condition', array() );

		// Get the Registered Image Sizes
		$site_icon = new WP_Site_Icon;
		$sizes     = $site_icon->additional_sizes( $this->get_all_sizes() );

		// Render HTML

		// Get the Targets of Image
		if( empty( $ids ) ){
			echo '<button type="button" id="get_attachment_id">'. esc_html__( 'Get the Re-Compression Targets', 'still-be-image-quality-control' ). '</button>';
		} elseif( empty( $current ) ) {
			echo '<button type="button" id="get_attachment_id">'. esc_html__( 'Get the Re-Compression Targets Again', 'still-be-image-quality-control' ). '</button>';
		} else {
			echo '<button type="button" id="get_attachment_id">'. esc_html__( 'Get the Re-Compression Targets Again and Reset Progress', 'still-be-image-quality-control' ). '</button>';
		}

		// Target Image Conditions
		echo '<div class="target-conditions-setting-wrapper">';
		echo   '<input type="checkbox" id="target_conditions_display" class="display-none">';
		echo   '<label for="target_conditions_display" class="show-target-conditions">';
		echo      esc_html__( 'Show Target Image Conditions', 'still-be-image-quality-control' );
		echo   '</label>';
		echo   '<div class="target-conditions-setting-container">';
		echo     '<p>'.  esc_html__( 'Please specify the conditions to be re-compressed.', 'still-be-image-quality-control' );
		echo     '<br>'. esc_html__( 'Before starting the batch re-compression, please specify the condition and get the re-compression targets.', 'still-be-image-quality-control' ). '</p>';
		echo     '<p>'.  esc_html__( 'The re-compression of the single image below or from WP media library page will be for all sizes and types regardless of this conditions.', 'still-be-image-quality-control' ). '</p>';
		echo     '<div class="target-conditions-wrapper">';
		echo       '<span class="item-name">'. esc_html__( 'Date', 'still-be-image-quality-control' ). '</span>';
		echo       '<div class="conditions">';
		echo         '<label>';
		echo           '<span>'. esc_html__( 'Start', 'still-be-image-quality-control' ). '</span>';
		echo           '<input type="date" data-target-condition="date" data-key="start" value="'. esc_attr( empty( $target['date']['start'] ) ? '' : $target['date']['start'] ). '">';
		echo         '</label>';
		echo         '<label>';
		echo           '<span>'. esc_html__( 'End', 'still-be-image-quality-control' ). '</span>';
		echo           '<input type="date" data-target-condition="date" data-key="end" value="'. esc_attr( empty( $target['date']['end'] ) ? '' : $target['date']['end'] ). '">';
		echo         '</label>';
		echo       '</div>';
		echo     '</div>';
		echo     '<div class="target-conditions-wrapper">';
		echo       '<span class="item-name">'. esc_html__( 'Image Type', 'still-be-image-quality-control' ). '</span>';
		echo       '<div class="conditions">';
		echo         '<label>';
		echo           '<input type="checkbox" data-target-condition="type" data-key="jpeg"'. esc_attr( isset( $target['type']['jpeg'] ) && ! $target['type']['jpeg'] ? '' : ' checked' ). '>';
		echo           '<span>JPEG</span>';
		echo         '</label>';
		echo         '<label>';
		echo           '<input type="checkbox" data-target-condition="type" data-key="png"'. esc_attr( isset( $target['type']['png'] ) && ! $target['type']['png'] ? '' : ' checked' ). '>';
		echo           '<span>PNG</span>';
		echo         '</label>';
		echo         '<label>';
		echo           '<input type="checkbox" data-target-condition="type" data-key="gif"'. esc_attr( isset( $target['type']['gif'] ) && ! $target['type']['gif'] ? '' : ' checked' ). '>';
		echo           '<span>GIF</span>';
		echo         '</label>';
		echo         '<label>';
		echo           '<input type="checkbox" data-target-condition="type" data-key="webp"'. esc_attr( isset( $target['type']['webp'] ) && ! $target['type']['webp'] ? '' : ' checked' ). '>';
		echo           '<span>WebP</span>';
		echo         '</label>';
		echo         '<label>';
		echo           '<input type="checkbox" data-target-condition="type" data-key="auto-webp"'. esc_attr( isset( $target['type']['auto-webp'] ) && ! $target['type']['auto-webp'] ? '' : ' checked' ). '>';
		echo           '<span>Auto Generated WebP</span>';
		echo         '</label>';
		echo       '</div>';
		echo     '</div>';
		echo     '<div class="target-conditions-wrapper">';
		echo       '<span class="item-name">'. esc_html__( 'Size Name', 'still-be-image-quality-control' ). '</span>';
		echo       '<div class="conditions">';
		foreach( $sizes as $name => $size ) {
			$width = isset( $size['width'] ) ? $size['width'] : $size['width '];
			echo         '<label>';
			echo           '<input type="checkbox" data-target-condition="size" data-key="'. esc_attr( $name ). '"'. esc_attr( isset( $target['size'][ $name ] ) && ! $target['size'][ $name ] ? '' : ' checked' ). '>';
			echo           '<span>'. esc_html( "{$name} ({$width}x{$size['height']})" ). '</span>';
			echo         '</label>';
		}
		echo       '</div>';
		echo     '</div>';
		echo   '</div>';
		echo '</div>';

		// Run Re-comp the Targets
		echo '<p style="margin-bottom: 2em; color: #C00;">'. esc_html__( 'Be sure to make a backup before recompressing.', 'still-be-image-quality-control' ). '</p>';
	//	echo '<div id="ids_list"><div class="scroll-wrapper">'. esc_html__( 'Get the list of image IDs to be regenerated.', 'still-be-image-quality-control' ). '</div></div>';
		echo '<button type="button" id="regenerate_images">'. esc_html__( 'Regenerate resized images', 'still-be-image-quality-control' ). '</button>';
		echo '<button type="button" id="suspend_regenerate" disabled>'. esc_html__( 'Suspend regeneration', 'still-be-image-quality-control' ). '</button>';
		echo '<p>'. esc_html__( 'Even if you leave the page in the middle of regeneration or suspend, you can continue from remains to regenerate.', 'still-be-image-quality-control' ). '</p>';

		// Run Re-comp a Specified Image
		echo '<p>'. esc_html__( 'If you want to recompress only one specific image, enter the Attachment ID below.', 'still-be-image-quality-control' ). '<br>';
		echo        esc_html__( 'You can check the recompression result with the developer tools.', 'still-be-image-quality-control' ). '</p>';
		echo '<div style="margin: 8px;">';
		echo   '<label style="margin: 8px;">Attachment ID = <input id="one_attachment_id" type="number"></label>';
		echo   '<button type="button" id="conv_only_one_image_button">'. esc_html__( 'Regenerate', 'still-be-image-quality-control' ). '</button>';
		echo   '<span id="conv_only_one_image_result" style="display: inline-block; margin: 0 8px; color: #707;"></span>';
		echo '</div>';

		// Nonce
		$nonces = array(
			'getIds'   => wp_create_nonce( 'sb-iqc-get-attachments' ),
			'reGenImg' => wp_create_nonce( 'sb-iqc-regenerate-images' ),
		);

		echo '<script type="text/javascript">';
		echo   'window.$stillbe.admin.reComp = {};';
		echo   'window.$stillbe.admin.reComp.ajaxUrl = window.$stillbe.admin.ajaxUrl;';
		echo   'window.$stillbe.admin.reComp.nonce   = '. json_encode( $nonces ). ';';
		echo   'window.$stillbe.admin.reComp.cache   = '. json_encode( $cache ). ';';
		echo '</script>';

	}


	// Auto Regenerate using WP-Cron
	public function render_auto_regen_wpcron( $args ) {

		// Setting
		$number   = isset( $this->current['auto-regen-wpcron'] ) ? absint( $this->current['auto-regen-wpcron']['number']   ) : 0;
		$interval = isset( $this->current['auto-regen-wpcron'] ) ? absint( $this->current['auto-regen-wpcron']['interval'] ) : 60;

		// WP-Cron Avilable
		$wpcron_enable_chk = '<small class="your-server-status">'. __( 'WP-Cron enablement on your WordPress:', 'still-be-image-quality-control' ).
			(
				defined( 'DISABLE_WP_CRON' ) && DISABLE_WP_CRON ?
					'<em class="unavailable">'. __( 'Unavailable', 'still-be-image-quality-control' ) :
					'<em class="available">'. __( 'Available', 'still-be-image-quality-control' )
			).
		'</em></small>';

		// Render HTML
		echo '<p>'.  esc_html__( 'Images are automatically recompressed using WP-Cron.', 'still-be-image-quality-control' );
		echo '<br>'. esc_html__( 'The job will stop when the processes are complete.', 'still-be-image-quality-control' ). '</p>';
		echo '<p>'.  wp_kses( $wpcron_enable_chk, $this->allowed_tags_for_note ). '</p>';
		echo '<div class="field-line" style="margin-top: 1.25em;">';
		echo   '<label for="auto_regen_wpcron_num" style="font-weight: bolder;">'. esc_html__( 'Number to process at one time', 'still-be-image-quality-control' ). '</label>';
		echo   '<input type="number" id="auto_regen_wpcron_num" name="'. esc_attr( self::SETTING_NAME. '[auto-regen-wpcron][number]' ). '" value="'. esc_attr( $number ). '">';
		echo '</div>';
		echo '<p>'.  esc_html__( 'Set the number of images to be processed each time WP-Cron is executed.', 'still-be-image-quality-control' );
		echo '<br>'. esc_html__( 'If you set the number to 0, WP-Cron will not be used. (only manually)', 'still-be-image-quality-control' ). '</p>';
		echo '<p>'.  esc_html__( 'Image processing has a high load on your server, so normally set it to 1.', 'still-be-image-quality-control' );
		echo '<br>'. esc_html__( 'If you want to set a value more than 1, we recommend setting a long interval time.', 'still-be-image-quality-control' ). '</p>';
		echo '<div class="field-line" style="margin-top: 1.25em;">';
		echo   '<label for="auto_regen_wpcron_interval" style="font-weight: bolder;">'. esc_html__( 'Wait time until the next process', 'still-be-image-quality-control' ). '</label>';
		echo   '<input type="number" id="auto_regen_wpcron_interval" name="'. esc_attr( self::SETTING_NAME. '[auto-regen-wpcron][interval]' ). '" value="'. esc_attr( $interval ). '">';
		echo   '<span class="unit-sec">'. esc_html__( 'sec', 'still-be-image-quality-control' ). '</span>';
		echo '</div>';
		echo '<p>'.  esc_html__( 'Shortening the wait time will increase the load on the server, but the recompression will be completed sooner.', 'still-be-image-quality-control' );
		echo '<br>'. esc_html__( 'It does not run more often than visiting the site.', 'still-be-image-quality-control' ). '</p>';
		echo '<p>'.  esc_html__( 'This time is the waiting time after the processes are completed.', 'still-be-image-quality-control' ). '</p>';

	}


}