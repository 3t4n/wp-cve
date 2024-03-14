<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Lion_Badge_Option_Tabs extends Lion_Badge_Option {

	/** @var string Name of the tab */
	private $name;

	/** @var string Label of the tab */
	private $label;

	private $group;

	public function __construct( $group ) {
		$this->group = $group;
	}

	public function add_tab( $name, $label ) {
		$this->name      = sanitize_title( $name );
		$this->label     = $label;

		$this->tabs[ $this->name ] = $this->label;
	}

	public function get_tab_view( $slug ) {
		return LION_BADGES_PATH . '/admin/views/tabs/' . $this->group . '/' . $slug . '.php';
	}

	public function generate() {
		echo '<div class="lion-badges-options-container">';
		echo '<ul class="tabs">';

		$i = 0;
		foreach ( $this->tabs as $slug => $label ) {
			$current = ( $i == 0 ) ? 'current' : '';

			echo '<li class="tab-link ' . $current . '" data-tab="' . $slug . '">' . $label . '</li>';

			$i++;
		}
		echo '</ul>';

		echo '<div class="tabs-content-wrap">';

		$i = 0;
		foreach ( $this->tabs as $slug => $label ) {
			$current = ( $i == 0 ) ? 'current' : '';

			echo '<div id="' . $slug . '" class="tab-content ' . $current . '">';

			$tab_view = $this->get_tab_view( $slug );

			if ( is_file( $tab_view ) ) {
				require_once $tab_view;
			}

			echo '</div>';

			$i++;
		}
		echo '</div>';
		echo '</div>';
	}
}
