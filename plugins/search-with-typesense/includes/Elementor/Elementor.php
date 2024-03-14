<?php

namespace Codemanas\Typesense\Elementor;

use Codemanas\Typesense\Elementor\Widgets\InstantSearch;
use Codemanas\Typesense\Elementor\Widgets\AutoComplete;

class Elementor {
	public static ?Elementor $instance = null;

	public static function getInstance(): ?Elementor {
		return is_null( self::$instance ) ? self::$instance = new self() : self::$instance;
	}

	public function __construct() {
		add_action( 'elementor/elements/categories_registered', [ $this, 'add_elementor_widget_categories' ] );
		add_action( 'elementor/widgets/register', [ $this, 'register_widget' ] );
	}

	public function register_widget( $widget_manager ) {
		$widget_manager->register( new InstantSearch() );
		$widget_manager->register( new AutoComplete() );
	}

	public function add_elementor_widget_categories( $elements_manager ) {
		$elements_manager->add_category(
			'cm-typesense',
			[
				'title' => esc_html__( 'Typesense', 'search-with-typesense' ),
				'icon'  => 'fa fa-plug',
			]
		);
	}
}