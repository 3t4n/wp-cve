<?php
/**
 * Its used for object join.
 *
 * @since: 21/09/2021
 * @author: Sarwar Hasan
 * @version 1.0.0
 * @package Appsbd\V1\Core
 */

namespace Appsbd_Lite\V1\Core;

if ( ! class_exists( __NAMESPACE__ . '\ObjectJoin' ) ) {


	/**
	 * Its class object join.
	 *
	 * @package Appsbd\V1\Core
	 */
	class ObjectJoin {
		/**
		 * Its property left.
		 *
		 * @var string Its string.
		 */
		const LEFT = 'LEFT';
		/**
		 * Its property right.
		 *
		 * @var string Its string.
		 */
		const RIGHT = 'RIGHT';
		/**
		 * Its property outer.
		 *
		 * @var string Its string.
		 */
		const OUTER = 'OUTER';
		/**
		 * Its property inner.
		 *
		 * @var string Its string.
		 */
		const INNER = 'INNER';
		/**
		 * Its property join obj property.
		 *
		 * @var string Its string.
		 */
		public $join_obj_property;
		/**
		 * Its property main obj property
		 *
		 * @var string
		 */
		public $main_obj_property;
		/**
		 * Its property join obj
		 *
		 * @var string
		 */
		public $join_obj;
		/**
		 * Its property type
		 *
		 * @var string
		 */
		public $type;
	}
}
