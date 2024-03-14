<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Lion_Badge_Option_Tabs_Horizontal extends Lion_Badge_Option {

	/** @var string Name of the tab */
	private $name;

	/** @var string Label of the tab */
	private $label;

	private $group;

	public function __construct( $group, $tab ) {
		$this->group = $group;
		$this->tab = $tab;
	}

	public function add_tab( $name, $label ) {
		$this->name      = sanitize_title( $name );
		$this->label     = $label;

		$this->tabs[ $this->name ] = $this->label;
	}

	public function get_tab_view( $slug ) {
		return LION_BADGES_PATH . '/admin/views/tabs/' . $this->group . '/' . $this->tab . '/' . $slug . '.php';
	}

	public function generate() {
		echo '<ul class="horizontal-tabs">';

		$i = 0;
		foreach ( $this->tabs as $slug => $label ) {
			$current = ( $i == 0 ) ? 'current' : '';

			echo '<li class="tab-link ' . $current . '" data-tab="horizontal-tab-' . $slug . '">' . $label . '</li>';

			$i++;
		}
		echo '</ul>';

		echo '<div class="horizontal-tabs-content">';

		$i = 0;
		foreach ( $this->tabs as $slug => $label ) {
			$current = ( $i == 0 ) ? 'current' : '';

			echo '<div id="horizontal-tab-' . $slug . '" class="h-tab-content ' . $current . '">';

			$tab_view = $this->get_tab_view( $slug );

			if ( is_file( $tab_view ) ) {
				require_once $tab_view;
			}

			echo '</div>';

			$i++;
		}

		echo '</div>';
	}
}
