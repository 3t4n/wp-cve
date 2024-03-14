<?php
	add_action('init', 'iwp_init_thickbox');
	function iwp_init_thickbox() {
		add_thickbox();
	}

	// Anula la función que texturiza diferentes caracteres como pasar las comillas a comillas españolas, etc.
	add_filter( 'run_wptexturize', '__return_false' );