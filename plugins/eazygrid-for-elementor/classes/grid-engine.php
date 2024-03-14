<?php

namespace EazyGrid\Elementor\Classes;

class Grid_Engine {

	private function get_grid_definitions() {
		$grid_classes = [];
		//$grid_class['name'] = 'container pattern_name h-flip v-flip grid-item-number'; //h-flip=yes/no
		$grid_classes['lily']        = 'ezg-ele-grid--pattern4x2 ezg-ele-grid--layout-lily yes no 6';
		$grid_classes['daffodil']    = 'ezg-ele-grid--pattern4x2 ezg-ele-grid--layout-daffodil yes no 5';
		$grid_classes['lavender']    = 'ezg-ele-grid--pattern4x2 ezg-ele-grid--layout-lavender no no 5';
		$grid_classes['orchid']      = 'ezg-ele-grid--pattern5x2 ezg-ele-grid--layout-orchid no no 4';
		$grid_classes['wild-orchid'] = 'ezg-ele-grid--pattern5x2 ezg-ele-grid--layout-wild-orchid yes no 4';
		$grid_classes['poppy']       = 'ezg-ele-grid--pattern5x2 ezg-ele-grid--layout-poppy no no 7';
		$grid_classes['rachel']      = 'ezg-ele-grid--pattern3x2 ezg-ele-grid--layout-rachel yes no 3';
		$grid_classes['pippin']      = 'ezg-ele-grid--pattern4x1 ezg-ele-grid--layout-pippin yes no 3';
		$grid_classes['windy']       = 'ezg-ele-grid--pattern4x4 ezg-ele-grid--layout-windy yes no 8';
		$grid_classes['breezy']      = 'ezg-ele-grid--pattern4x4 ezg-ele-grid--layout-breezy no yes 8';
		$grid_classes['cathreen']    = 'ezg-ele-grid--pattern3x3 ezg-ele-grid--layout-cathreen no yes 5';
		$grid_classes['capricorn']   = 'ezg-ele-grid--pattern10x4 ezg-ele-grid--layout-capricorn yes yes 8';
		$grid_classes['europa']      = 'ezg-ele-grid--pattern10x3 ezg-ele-grid--layout-europa yes yes 6';
		$grid_classes['rondeletia']  = 'ezg-ele-grid--pattern4x2 ezg-ele-grid--layout-rondeletia yes yes 4';
		$grid_classes['bletilla']    = 'ezg-ele-grid--pattern4x2 ezg-ele-grid--layout-bletilla yes yes 5';
		$grid_classes['crepuscular'] = 'ezg-ele-grid--pattern4x2 ezg-ele-grid--layout-crepuscular yes yes 6';
		$grid_classes['clianthus']   = 'ezg-ele-grid--pattern3x2 ezg-ele-grid--layout-clianthus yes yes 3';
		$grid_classes['dandelion']   = 'ezg-ele-grid--pattern8x3 ezg-ele-grid--layout-dandelion yes yes 4';
		$grid_classes['lupin']       = 'ezg-ele-grid--pattern4x3 ezg-ele-grid--layout-lupin yes yes 8';

		$grid_classes = apply_filters( 'eazygridElementor/grid/definitions', $grid_classes );

		return $grid_classes;
	}

	public function grids_can_be_h_flipped() {
		$h_flips      = [];
		$grid_classes = $this->get_grid_definitions();
		foreach ( $grid_classes as $grid => $gc ) {
			$parts = explode( ' ', $gc );
			if ( isset( $parts[2] ) && $parts[2] == 'yes' ) {
				$h_flips[] = $grid;
			}
		}

		return $h_flips;
	}

	public function grids_can_be_v_flipped() {
		$v_flips      = [];
		$grid_classes = $this->get_grid_definitions();
		foreach ( $grid_classes as $grid => $gc ) {
			$parts = explode( ' ', $gc );
			if ( isset( $parts[3] ) && $parts[3] == 'yes' ) {
				$v_flips[] = $grid;
			}
		}

		return $v_flips;
	}

	public function get_grid_class_name( $layout, $settings ) {
		$grid_classes      = $this->get_grid_definitions();
		$grid_class_parts  = explode( ' ', $grid_classes[ $layout ] );
		$grid_class        = $grid_class_parts[0] . ' ' . $grid_class_parts[1];
		$v_flippable_grids = $this->grids_can_be_v_flipped();
		$h_flippable_grids = $this->grids_can_be_h_flipped();
		$grid_class        = apply_filters( 'eazygridElementor/class/name.', $grid_class, $settings, $layout, $h_flippable_grids, $v_flippable_grids );

		return $grid_class;
	}

	public function get_grid_element_count( $layout ) {
		$grid_classes     = $this->get_grid_definitions();
		$grid_class_parts = explode( ' ', $grid_classes[ $layout ] );

		return $grid_class_parts[4];
	}
}
