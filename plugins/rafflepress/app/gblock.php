<?php

	add_action( 'init', 'rafflepress_register_block' );
	add_action( 'enqueue_block_editor_assets', 'rafflepress_enqueue_block_editor_assets' );

if ( ! function_exists( 'rafflepress_register_block' ) ) {
	function rafflepress_register_block() {
		wp_register_style(
			'rafflepress-gutenberg-giveaway-selector',
			RAFFLEPRESS_PLUGIN_URL . 'public/css/gutenburg.css',
			array( 'wp-edit-blocks' ),
			RAFFLEPRESS_VERSION
		);

		register_block_type(
			'rafflepress/giveaway-selector',
			array(
				'attributes'      => array(
					'giveawayId' => array(
						'type' => 'string',
					),
				),
				'editor_style'    => 'rafflepress-gutenberg-giveaway-selector',
				'render_callback' => 'rafflepress_get_form_html',
			)
		);
	}
}

if ( ! function_exists( 'rafflepress_enqueue_block_editor_assets' ) ) {
	function rafflepress_enqueue_block_editor_assets() {
		$i18n = array(
			'title'             => esc_html__( 'RafflePress', 'rafflepress' ),
			'description'       => esc_html__( 'Select and display one of your giveaways', 'rafflepress' ),
			'giveaway_keyword'  => esc_html__( 'giveaway', 'rafflepress' ),
			'giveaway_select'   => esc_html__( 'Select a Giveaway', 'rafflepress' ),
			'giveaway_settings' => esc_html__( 'Giveaway Settings', 'rafflepress' ),
			'giveaway_selected' => esc_html__( 'Giveaway', 'rafflepress' ),
		);

		wp_enqueue_script(
			'rafflepress-gutenberg-giveaway-selector',
			RAFFLEPRESS_PLUGIN_URL . 'public/js/gblock.js',
			array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-server-side-render' ),
			RAFFLEPRESS_VERSION,
			true
		);

		global $wpdb;
		$tablename = $wpdb->prefix . 'rafflepress_giveaways';
		$sql       = "SELECT * FROM $tablename";
		$sql      .= ' WHERE deleted_at is null';
		$giveaways = $wpdb->get_results( $sql );

		wp_localize_script(
			'rafflepress-gutenberg-giveaway-selector',
			'rafflepress_gutenberg_giveaway_selector',
			array(
				'logo_url'  => RAFFLEPRESS_PLUGIN_URL . 'public/img/logo.png',
				'wpnonce'   => wp_create_nonce( 'rafflepress-gutenberg-giveaway-selector' ),
				'giveaways' => ! empty( $giveaways ) ? $giveaways : array(),
				'i18n'      => $i18n,
			)
		);
	}
}

if ( ! function_exists( 'rafflepress_get_form_html' ) ) {
	function rafflepress_get_form_html( $attr ) {
		$id = ! empty( $attr['giveawayId'] ) ? absint( $attr['giveawayId'] ) : 0;

		if ( empty( $id ) ) {
			return '';
		}

		$is_gb_editor = defined( 'REST_REQUEST' ) && REST_REQUEST && ! empty( $_REQUEST['context'] ) && 'edit' === $_REQUEST['context'];

		$preview_txt = __( 'RafflePress Giveaway Preview', 'rafflepress' );
		ob_start();
		if ( $is_gb_editor ) {
			echo "
            <style>
            .overlay {
                position: relative;
            }
            .overlay::before {
                background-image: linear-gradient( top, 
                        rgba( 255, 255, 255, 0 ) 0%, rgba( 255, 255, 255, 1 ) 100% );
                    background-image: -moz-linear-gradient( top, 
                        rgba( 255, 255, 255, 0 ) 0%, rgba( 255, 255, 255, 1 ) 100% );
                    background-image: -ms-linear-gradient( top, 
                        rgba( 255, 255, 255, 0 ) 0%, rgba( 255, 255, 255, 1 ) 100% );
                    background-image: -o-linear-gradient( top, 
                        rgba( 255, 255, 255, 0 ) 0%, rgba( 255, 255, 255, 1 ) 100% );
                    background-image: -webkit-linear-gradient( top, 
                        rgba( 255, 255, 255, 0 ) 0%, rgba( 255, 255, 255, 1 ) 100% );
                content: '$preview_txt';
                height: 100%;
                position: absolute;
                width: 100%;
                font-size:14px;
                text-align:center;

            }
            .rafflepress-preview-button{
                position: absolute;
                width: 207px;
                text-align: center;
                left: 0;
                right: 0;
				color: #fff !important;
                margin-left: auto !important;
                margin-right: auto !important;
                top: 610px;
            }
            </style>

            ";
			echo '<div class="overlay">';
		}

		echo do_shortcode( "[rafflepress_gutenberg id='$id' min_height='200px' giframe='true']" );
		if ( $is_gb_editor ) {
			echo '<a href="' . home_url() . '?rafflepress_page=rafflepress_render&rafflepress_id=' . $id . '&rafflepress-preview=1" target="_blank" class="button-primary rafflepress-preview-button">' . __( 'Live Preview', 'rafflepress' ) . '</a>';
			echo '</div>';
		}

		return ob_get_clean();
	}
}
