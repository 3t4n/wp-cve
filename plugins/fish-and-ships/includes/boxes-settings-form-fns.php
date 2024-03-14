<?php
/**
 * Selector: Boxes used previously by packer algorythm (pro)
 * Special Action: Add boxes conditionally for packer (pro beta)
 *
 * Moved here the "Shipping boxes" special action (packer) stuff on 1.4.13
 *
 * @package Fish and Ships
 * @since 1.4.13
 */

defined( 'ABSPATH' ) || exit;

// use DVDoug\FnsBoxPacker\Fish_n_Ships_Packer;

if ( !class_exists( 'Fish_n_Ships_Boxes' ) ) 
{
	class Fish_n_Ships_Boxes
	{	
		private $box_selectors = null;
				
		/**
		 * Constructor.
		 *
		 * @since 1.4.13
		 */
		public function __construct()
		{			
			// Boxes (packer) and Add boxes special actions
			add_filter('wc_fns_get_actions',             array ( $this, 'wc_fns_get_actions_fn' ), 10, 1);
		}

		/**
		 * Filter to get all actions
		 *
		 * @since 1.4.13
		 *
		 * @param $actions (array) maybe incomming  a pair action-id / only-pro, scope (optional), action-name array
		 *
		 * @return $actions (array) a pair action-id / action-name array
		 *
		 */

		function wc_fns_get_actions_fn($actions = array()) {

			if (!is_array($actions)) $actions = array();
			
			$scope_all     = array ('normal', 'extra');
			$scope_normal  = array ('normal');
			$scope_extra   = array ('extra');

			$actions['boxes']           = array('onlypro' => true,  'scope' => $scope_normal, 'label' => _x('Shipping boxes', 'shorted, action name', 'fish-and-ships'));
			// $actions['add-boxes']       = array('onlypro' => true,  'scope' => $scope_normal, 'label' => _x('Add conditional boxes', 'shorted, action name', 'fish-and-ships'));

			return $actions;
		}




	}

	global $Fish_n_Ships_Boxes;
	$Fish_n_Ships_Boxes = new Fish_n_Ships_Boxes();
}

