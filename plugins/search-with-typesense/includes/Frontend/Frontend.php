<?php

namespace Codemanas\Typesense\Frontend;

use Codemanas\Typesense\Backend\Admin;
use Codemanas\Typesense\Helpers\Templates;
use Codemanas\Typesense\Bootstrap;

class Frontend {
	public static ?Frontend $instance = null;

	public static function getInstance(): ?Frontend {
		return is_null( self::$instance ) ? self::$instance = new self() : self::$instance;
	}

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts' ) );
		add_action( 'wp_footer', array( $this, 'load_php_templates' ) );

		$search_config_settings = Admin::get_search_config_settings();

		if ( isset( $search_config_settings['hijack_wp_search'] ) && $search_config_settings['hijack_wp_search']
		     && isset( $search_config_settings['hijack_wp_search__type'] )
		     && $search_config_settings['hijack_wp_search__type'] == 'autocomplete'
		) {
			add_filter( 'get_search_form', array( $this, 'replace_search_form' ) );
			add_filter( 'render_block', array( $this, 'replace_block_editor_search_form' ), 10, 2 );
		} elseif ( isset( $search_config_settings['hijack_wp_search'] ) && $search_config_settings['hijack_wp_search']
		           && isset( $search_config_settings['hijack_wp_search__type'] )
		           && $search_config_settings['hijack_wp_search__type'] == 'instant_search'
		) {
			add_action( 'wp_footer', [ $this, 'load_popup' ] );
		}
	}

	/**
	 * @param $block_content
	 * @param $block
	 *
	 * @return mixed|string
	 */
	public function replace_block_editor_search_form( $block_content, $block ) {
		if ( $block['blockName'] == 'core/search' ) {
			ob_start();
			echo do_shortcode( '[cm_typesense_autocomplete]' );

			return ob_get_clean();
		}

		return $block_content;
	}

	public function load_scripts() {
		$admin_settings         = Admin::get_default_settings();
		$search_config_settings = Admin::get_search_config_settings();


		wp_register_script( 'cm-typesense-instant-search', CODEMANAS_TYPESENSE_ROOT_URI_PATH . 'assets/frontend/instant-search.js', [ 'wp-util' ], CODEMANAS_TYPESENSE_VERSION, true );
		wp_localize_script( 'cm-typesense-instant-search', 'cm_typesense_instant_search_default_settings', [
			'debug'                => SCRIPT_DEBUG,
			'search_api_key'       => $admin_settings['search_api_key'],
			'port'                 => $admin_settings['port'],
			'node'                 => $admin_settings['node'],
			'protocol'             => $admin_settings['protocol'],
			'enabled_post_types'   => $search_config_settings['enabled_post_types'],
			'available_post_types' => $search_config_settings['available_post_types'],
			'search_config'        => $search_config_settings['config'],
			'date_format'          => apply_filters( 'cm_typesense_date_format', get_option( 'date_format' ) ),
			'localized_strings'    => [
				'load_more' => __( 'Load More', 'search-with-typesense' ),
				'show_less' => __( 'Show less', 'search-with-typesense' ),
				'show_more' => __( 'Show more', 'search-with-typesense' ),
			],
			'elementor_edit_mode'  => ( Bootstrap::getInstance()->is_plugin_active( 'elementor/elementor.php' ) && \Elementor\Plugin::$instance->preview->is_preview_mode() ) ? 'true' : 'false',
		] );

		wp_register_script( 'cm-typesense-autocomplete', CODEMANAS_TYPESENSE_ROOT_URI_PATH . 'assets/frontend/autocomplete.js', [ 'wp-util' ], CODEMANAS_TYPESENSE_VERSION, true );
		wp_localize_script( 'cm-typesense-autocomplete', 'cm_typesense_autocomplete_default_settings', [
			'debug'                      => SCRIPT_DEBUG,
			'search_api_key'             => $admin_settings['search_api_key'],
			'port'                       => $admin_settings['port'],
			'node'                       => $admin_settings['node'],
			'protocol'                   => $admin_settings['protocol'],
			'enabled_post_types'         => $search_config_settings['enabled_post_types'],
			'available_post_types'       => $search_config_settings['available_post_types'],
			'search_config'              => $search_config_settings['config'],
			'hijack_wp_search__type'     => $search_config_settings['hijack_wp_search__type'],
			'autocomplete_input_delay'   => $search_config_settings['autocomplete_input_delay'],
			'autocomplete_submit_action' => $search_config_settings['autocomplete_submit_action'],
			'elementor_edit_mode'        => ( Bootstrap::getInstance()->is_plugin_active( 'elementor/elementor.php' ) && \Elementor\Plugin::$instance->preview->is_preview_mode() ) ? 'true' : 'false',
		] );


		wp_register_script( 'cm-typesense-popup', CODEMANAS_TYPESENSE_ROOT_URI_PATH . 'assets/frontend/popup.js', array( 'wp-util' ), CODEMANAS_TYPESENSE_VERSION, true );
		wp_localize_script( 'cm-typesense-popup', 'cm_typesense_popup_default_settings', [
			'hijack_wp_search__type' => $search_config_settings['hijack_wp_search__type'],
		] );


		wp_register_style( 'algolia-satellite', CODEMANAS_TYPESENSE_ROOT_URI_PATH . 'assets/vendor/css/algolia-satellite.min.css', false, CODEMANAS_TYPESENSE_VERSION, false );
		wp_register_style( 'cm-typesense-frontend-style', CODEMANAS_TYPESENSE_ROOT_URI_PATH . 'assets/frontend/style.css', [ 'algolia-satellite' ], CODEMANAS_TYPESENSE_VERSION, false );
		wp_enqueue_style( 'cm-typesense-frontend-style' );


	}

	public function load_php_templates() {
		Templates::getInstance()->include_file( 'instant-search-results.php' );
		Templates::getInstance()->include_file( 'instant-search-no-results.php' );
		//Autocomplete
		Templates::getInstance()->include_file( 'autocomplete/item.php' );
		Templates::getInstance()->include_file( 'autocomplete/header.php' );
		Templates::getInstance()->include_file( 'autocomplete/no-results.php' );
	}

	public function load_popup() {
		//@todo will need to be replaced with a getter function
		$defaults   = [
			'search_placeholder'   => __( 'Search for...', 'search-with-typesense' ),
			'available_post_types' => '',
			'color'                => '#ffc168',
			'filter'               => 'show',
			'no_of_cols'           => '1',
			'results_per_page'     => '4',
			'pagination'           => 'infinite',
			'sort_by'              => 'show',
			'sticky_first'         => 'no',
		];
		$options    = get_option( 'typesense_customizer_instant_search' );
		$options    = apply_filters( 'cm_typesense_popup_shortcode_params', is_array( $options ) ? wp_parse_args( $options, $defaults ) : $defaults );
		$post_types = 'post';
		if ( ! empty( $options['available_post_types'] ) && is_array( $options['available_post_types'] ) ) {
			//before select 2 was introduced data was saved as array
			$post_types = implode( ',', $options['available_post_types'] );
		} elseif ( ! empty( $options['available_post_types'] ) && is_string( $options['available_post_types'] ) ) {
			//after select 2 was introduced data is saved as string
			$post_types = $options['available_post_types'];
		}
		?>
        <style>
            .ais-Highlight-highlighted, .ais-Snippet-highlighted, .hit-description mark {
                background-color: <?php esc_attr_e($options['color']); ?>
            }
        </style>
        <div class="cmswt-InstantSearchPopup">
            <div class="cmswt-InstantSearchPopup--results">
                <a href="#" class="cmswt-InstantSearchPopup--closeIcon" title="close">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </a>
				<?php

				$shortcode_params = isset( $options['search_placeholder'] ) && ! empty( $options['search_placeholder'] ) ? 'placeholder="' . $options['search_placeholder'] . '" ' : '';
				$shortcode_params .= isset( $options['no_of_cols'] ) && ! empty( $options['no_of_cols'] ) ? 'columns="' . $options['no_of_cols'] . '" ' : '';
				$shortcode_params .= ! empty( $post_types ) ? 'post_types="' . $post_types . '" ' : '';
				$shortcode_params .= isset( $options['filter'] ) && ! empty( $options['filter'] ) ? 'filter="' . $options['filter'] . '" ' : '';
				$shortcode_params .= isset( $options['results_per_page'] ) && ! empty( $options['results_per_page'] ) ? 'per_page="' . $options['results_per_page'] . '" ' : '';
				$shortcode_params .= isset( $options['sort_by'] ) && ! empty( $options['sort_by'] ) ? 'sortby="' . $options['sort_by'] . '" ' : '';
				$shortcode_params .= isset( $options['pagination'] ) && ! empty( $options['pagination'] ) ? 'pagination="' . $options['pagination'] . '" ' : '';
				$shortcode_params .= isset( $options['sticky_first'] ) && ! empty( $options['sticky_first'] ) ? 'sticky_first="' . $options['sticky_first'] . '" ' : '';
				echo do_shortcode( '[cm_typesense_search query_by="post_title,post_content" ' . $shortcode_params . ' ]' );
				?>
            </div>
        </div>
		<?php
		wp_enqueue_script( 'cm-typesense-popup' );
	}

	public function replace_search_form() {
		ob_start();
		echo do_shortcode( '[cm_typesense_autocomplete]' );

		return ob_get_clean();
	}
}