<?php

namespace Codemanas\Typesense\Main;

class TemplateHooks {
	public static ?TemplateHooks $instance = null;

	public static function get_instance(): ?TemplateHooks {
		return is_null( self::$instance ) ? self::$instance = new self() : self::$instance;
	}

	public function __construct() {
		/*Default*/
		add_action( 'cm_typesense_instant_search_results_output', [ $this, 'search_bar' ], 5, 4 );
		add_action( 'cm_typesense_instant_search_results_output', [ $this, 'filter_panel' ], 15, 4 );
		//also always required
		add_action( 'cm_typesense_instant_search_results_output', [ $this, 'main_panel' ], 20, 4 );

		// Sub header
		add_action( 'cm_typesense_instant_search_sub_header', [ $this, 'index_switcher' ], 5, 4 );
		add_action( 'cm_typesense_instant_search_sub_header', [ $this, 'sort_by' ], 10, 4 );

		//Stats Widget
		add_action( 'cm_typesense_instant_search_stats', [ $this, 'stats' ], 10, 4 );

		//Refinements
		add_action( 'cm_typesense_instant_search_refinements', [ $this, 'show_current_refinements' ], 5, 4 );
		add_action( 'cm_typesense_instant_search_refinements', [ $this, 'show_clear_refinements' ], 10, 4 );

		/*Main Panel Body*/
		add_action( 'cm_typesense_instant_search_results_main_panel_body', [ $this, 'main_panel_result_body' ], 5, 2 );
		add_action( 'cm_typesense_instant_search_results_main_panel_body', [ $this, 'pagination' ], 5, 2 );
	}

	/**
	 * Add Search Bar
	 *
	 * @return void
	 */
	public function search_bar( $args, $config, $facet, $schema ) {
		cm_swt_get_template( 'instant-search/search-header.php', [
			'passed_args' => $args,
			'config'      => $config,
			'facet'       => $facet,
			'schema'      => $schema,
		] );
	}

	/**
	 * Add Filter Panel
	 *
	 * @param $args
	 * @param $config
	 * @param $facet
	 * @param $schema
	 *
	 * @return void
	 */
	public function filter_panel( $args, $config, $facet, $schema ) {
		cm_swt_get_template( 'instant-search/filter-panel.php', [
			'passed_args' => $args,
			'config'      => $config,
			'facet'       => $facet,
			'schema'      => $schema,
		] );
	}

	/**
	 * Add Main Panel
	 *
	 * @param $args
	 * @param $config
	 * @param $facet
	 * @param $schema
	 *
	 * @return void
	 */
	public function main_panel( $args, $config, $facet, $schema ) {
		cm_swt_get_template( 'instant-search/main-panel.php', [
			'passed_args' => $args,
			'config'      => $config,
			'facet'       => $facet,
			'schema'      => $schema,
		] );
	}

	/**
	 * Index switcher tabs
	 *
	 * @param $args
	 * @param $config
	 * @param $facet
	 * @param $schema
	 *
	 * @return void
	 */
	public function index_switcher( $args, $config, $facet, $schema ) {
		cm_swt_get_template( 'instant-search/partials/index-switcher.php', [
			'passed_args' => $args,
			'config'      => $config,
			'facet'       => $facet,
			'schema'      => $schema,
		] );
	}

	/**
	 * Sort By - Search Results
	 *
	 * @param $args
	 * @param $config
	 * @param $facet
	 * @param $schema
	 *
	 * @return void
	 */
	public function sort_by( $args, $config, $facet, $schema ) {
		cm_swt_get_template( 'instant-search/partials/sort-by.php', [
			'passed_args' => $args,
			'config'      => $config,
			'facet'       => $facet,
			'schema'      => $schema,
		] );
	}


	/**
	 * @param $args
	 * @param $config
	 * @param $facet
	 * @param $schema
	 *
	 * @return void
	 */
	public function stats( $args, $config, $facet, $schema ) {
		if ( $args['stats'] != 'show' ) {
			return;
		}
		cm_swt_get_template( 'instant-search/partials/stats.php', [
			'passed_args' => $args,
			'config'      => $config,
			'facet'       => $facet,
			'schema'      => $schema,
		] );
	}

	/**
	 * @param $args
	 * @param $config
	 * @param $facet
	 * @param $schema
	 *
	 * @return void
	 */
	public function show_current_refinements( $args, $config, $facet, $schema ) {
		if ( $args['selected_filters'] != 'show' ) {
			return;
		}
		cm_swt_get_template( 'instant-search/partials/current-refinements.php', [
			'passed_args' => $args,
			'config'      => $config,
			'facet'       => $facet,
			'schema'      => $schema,
		] );
	}

	/**
	 * @param $args
	 * @param $config
	 * @param $facet
	 * @param $schema
	 *
	 * @return void
	 */
	public function show_clear_refinements( $args, $config, $facet, $schema ) {
		if ( $args['selected_filters'] != 'show' ) {
			return;
		}
		cm_swt_get_template( 'instant-search/partials/clear-refinements.php', [
			'passed_args' => $args,
			'config'      => $config,
			'facet'       => $facet,
			'schema'      => $schema,
		] );
	}

	/**
	 * Result Body / Items
	 *
	 * @param $config
	 * @param $post_type
	 *
	 * @return void
	 */
	public function main_panel_result_body( $config, $post_type ) {
		cm_swt_get_template( 'instant-search/partials/item.php', [ 'config' => $config, 'post_type' => $post_type ] );
	}

	/**
	 * Add Pagination to Main Panel
	 *
	 * @param $config
	 * @param $post_type
	 *
	 * @return void
	 */
	public function pagination( $config, $post_type ) {
		cm_swt_get_template( 'instant-search/partials/pagination.php', [
			'config'    => $config,
			'post_type' => $post_type
		] );
	}

}