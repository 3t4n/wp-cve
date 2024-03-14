<?php

add_action(
	'init',
	function () {

		function ep_register_block_categories( $categories ) {
			return array_merge(
				array(
					array(
						'slug'  => 'ep-editorplus-blocks',
						'title' => 'Editor Plus Blocks',
					),
				),
				$categories
			);
		}
		// "block_categories" filter is deprecated for WP version above 5.8
		if ( version_compare( get_bloginfo( 'version' ), '5.8', '>=' ) ) {
			add_filter( 'block_categories_all', 'ep_register_block_categories' );
		} else {
			add_filter( 'block_categories', 'ep_register_block_categories' );
		}

	}
);

\add_action(
	'init',
	function() {

		wp_register_script(
			'editorplus-progressbar-script',
			plugins_url( '/assets/scripts/progressbar.js', dirname( __FILE__ ) ),
			array(),
			'new',
			true
		);

		wp_register_script(
			'editorplus-toggles-script',
			plugins_url( '/assets/scripts/toggles.js', dirname( __FILE__ ) ),
			array(),
			'new',
			true
		);

		wp_register_script(
			'editorplus-counter-script',
			plugins_url( '/assets/scripts/counter.js', dirname( __FILE__ ) ),
			array(),
			'initail',
			true
		);

		wp_register_script(
			'editorplus-tabs-script',
			plugins_url( '/assets/scripts/tabs.js', dirname( __FILE__ ) ),
			array(),
			'new',
			true
		);

		wp_register_script(
			'editorplus-countdown-script',
			plugins_url( '/assets/scripts/countdown.js', dirname( __FILE__ ) ),
			array( 'jquery' ),
			'new',
			true
		);

		wp_register_script(
			'editorplus-lottie-player-script',
			plugins_url( '/assets/scripts/lottie-player.js', dirname( __FILE__ ) ),
			array( 'jquery' ),
			'latest',
			true
		);

		register_block_type(
			'ep/progress-bar',
			array(
				'script' => ! is_admin() ? 'editorplus-progressbar-script' : '',
			)
		);

		register_block_type(
			'ep/toggles',
			array(
				'script' => ! is_admin() ? 'editorplus-toggles-script' : '',
			)
		);

		register_block_type(
			'ep/counter',
			array(
				'script' => ! is_admin() ? 'editorplus-counter-script' : '',
			)
		);

		register_block_type(
			'ep/tabs',
			array(
				'script' => ! is_admin() ? 'editorplus-tabs-script' : '',
			)
		);

		register_block_type(
			'ep/countdown',
			array(
				'script' => ! is_admin() ? 'editorplus-countdown-script' : '',
			)
		);

		register_block_type(
			'ep/lottie',
			array(
				'script' => ! is_admin() ? 'editorplus-lottie-player-script' : '',
			)
		);
	}
);
