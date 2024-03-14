<?php		

echo '<h1>' . __( 'Beam me up Scotty' , 'beam-me-up-scotty' ) . '</h1>' . "\n";

// Show page tabs
if ( is_array( $this->tabs ) && 1 < count( $this->tabs ) ) {

	echo '<h2 class="nav-tab-wrapper">' . "\n";

	$c = 0;
	foreach ( $this->tabs as $section => $data ) {
		$classes = array();
		
		// Set tab class
		$classes[] = 'nav-tab';
		if ( ! isset( $_GET['tab'] ) ) {
			if ( 0 == $c ) {
				$classes[] = 'nav-tab-active';
			}
		} else {
			if ( isset( $_GET['tab'] ) && $section == $_GET['tab'] ) {
				$classes[] = 'nav-tab-active';
			}
		}

		// Set tab link
		$tab_link = add_query_arg( array( 'tab' => $section ) );
		if ( isset( $_GET['settings-updated'] ) ) {
			$tab_link = remove_query_arg( 'settings-updated', $tab_link );
		}

		// Output tab
		echo '<a href="' . $tab_link . '" class="' . esc_attr( implode( ' ', $classes ) ) . '">' . esc_html( $data['title'] );
		
		if ( isset( $data['highlighted'] ) && $data['highlighted'] ) {
			echo '<span class="new-badge">1</span>';
		}
		
		echo '</a>' . "\n";

		++$c;
	}

	echo '</h2>' . "\n";
}
