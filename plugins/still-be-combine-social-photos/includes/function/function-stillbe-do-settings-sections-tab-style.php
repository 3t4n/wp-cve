<?php

// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
	exit;
}




// 
if( ! function_exists( 'stillbe_do_settings_sections_tab_style' ) ) {

	function stillbe_do_settings_sections_tab_style( $page ) {

		global $wp_settings_sections, $wp_settings_fields;

		if ( ! isset( $wp_settings_sections[ $page ] ) ) {
			return;
		}

		// Get Initialized Tab
		$tab_init = preg_replace( '/^tab_/', '', ( filter_input( INPUT_GET, 'tab' ) ?: '' ) );
		$tab_ids  = array_column( (array) $wp_settings_sections[ $page ], 'id' );
		if( ! in_array( $tab_init, $tab_ids, true ) ) {
			$tab_init = null;
		}

		// Tabs Wrapper
		echo '<div class="settings-tabs-wrapper">';

		// Tabs
		$i = 0;
		foreach ( (array) $wp_settings_sections[ $page ] as $section ) {

			++$i;
			$id     = 'tab_'. ( empty( $section['id'] ) ? $i : $section['id'] );
			$tab    = empty( $section['title'] ) ? sprintf( __( 'Option %d' ), $i ) : $section['title'];
			$active = $tab_init ? 
			            ( $tab_init === $section['id'] ? 'active' : '' ) :
			            ( $i < 2 ? 'active' : '' );

			echo '<label for="'. esc_attr( $id ). '" class="'. esc_attr( $active ). '">'. esc_html( $tab ). '</label>';

		}

		// End of Tabs
		echo '</div>';

		// Sections Wrapper
		echo '<div class="setting-sections-wrapper">';

		// Sections
		$i = 0;
		foreach ( (array) $wp_settings_sections[ $page ] as $section ) {

			++$i;
			$id = 'tab_'. ( empty( $section['id'] ) ? $i : $section['id'] );
			$checked = $tab_init ? 
			             ( $tab_init === $section['id'] ? 'checked' : '' ) :
			             ( $i < 2 ? 'checked' : '' );

			// Selector
			echo '<input type="radio" name="setting-section-selector" id="'. esc_attr( $id ). '"';
			echo       ' class="setting-section-selector" '. esc_attr( $checked ). '>';

			// Section
			echo '<section class="setting-section">';

			// Renderer
			if( $section['callback'] ) {
				call_user_func( $section['callback'], $section );
			}

			// No Setting Field
			if( ! isset( $wp_settings_fields ) || ! isset( $wp_settings_fields[ $page ] ) ||
			      ! isset( $wp_settings_fields[ $page ][ $section['id'] ] ) ) {
				echo '</section>';
				continue;
			}

			// Setting Fields
			echo '<table class="form-table" role="presentation">';
			do_settings_fields( $page, $section['id'] );
			echo '</table>';

			// End of Section
			echo '</section>';

		}

		// End of Sections
		echo '</div>';

	}

}





// END

?>