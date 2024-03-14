<?php
/**
 * UpStream_Report
 *
 * WordPress Coding Standart (WCS) note:
 * Some camelCase methods and object properties on this file are not converted to snake_case,
 * because it being used (heavily) on "upstream-reporting" add-on plugin.
 *
 * @package UpStream
 */

if ( ! defined( 'ABSPATH' ) || class_exists( 'UpStream_Report' ) ) {
	return;
}

/**
 * Class UpStream_Report
 */
class UpStream_Report {

	/**
	 * Title
	 *
	 * @var string
	 */
	public $title = '';

	/**
	 * Id
	 *
	 * @var string
	 */
	public $id = '';

	/**
	 * UpStream_Report constructor.
	 */
	public function __construct() {     }

	/**
	 * Get Display Options
	 */
	public function getDisplayOptions() { // phpcs:ignore
		return array(
			'visualization_type' => 'Table',
		);
	}

	/**
	 * Gets all of the parameter options to show when someone sets up a report. This is
	 * a form {
	 *   ID : { type : project, task, etc,
	 *          field1 : ...,
	 *          field2 : ... }
	 * }
	 *
	 * @return array of all options to be used for the report parameters page
	 */
	public function getAllFieldOptions() { // phpcs:ignore
		return array();
	}

	/**
	 * Get Field Option
	 *
	 * @param  mixed $section Section.
	 * @param  mixed $key Key.
	 */
	public function getFieldOption( $section, $key = null ) { // phpcs:ignore
		$fo = $this->getAllFieldOptions();

		if ( ! isset( $fo[ $section ] ) ) {
			return null;
		} else {
			if ( $key ) {
				return isset( $fo[ $section ][ $key ] ) ? $fo[ $section ][ $key ] : null;
			} else {
				return $fo[ $section ];
			}
		}
	}

	/**
	 * Array In
	 *
	 * @param  mixed $needles Needles.
	 * @param  mixed $haystack Haystack.
	 * @param  mixed $comparator Comparator.
	 */
	private function arrayIn( $needles, $haystack, $comparator = null ) { // phpcs:ignore
		if ( ! $comparator ) {
			$comparator = function( $a, $b ) {
				return $a == $b;
			};
		}

		if ( ! is_array( $haystack ) ) {
			$haystack = array( $haystack );
		}
		if ( ! is_array( $needles ) ) {
			$needles = array( $needles );
		}

		$haystack_count = count( $haystack );
		for ( $j = 0; $j < $haystack_count; $j++ ) {
			$needles_count = count( $needles );
			for ( $i = 0; $i < $needles_count; $i++ ) {
				if ( $comparator( $needles[ $i ], $haystack[ $j ] ) ) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Combine Array
	 *
	 * @param  mixed $arr Arr.
	 */
	protected static function combineArray( $arr ) { // phpcs:ignore
		if ( ! is_array( $arr ) ) {
			return $arr;
		} elseif ( count( $arr ) == 1 ) {
			if ( ! isset( $arr[0] ) ) {
				$arr[0] = null;
			}
			return $arr[0];
		} else {
			return implode( ', ', $arr );
		}
	}

	/**
	 * Parse Project Params
	 *
	 * @param  mixed $params Params.
	 * @param  mixed $section_id Section Id.
	 */
	protected function parseProjectParams( $params, $section_id ) { // phpcs:ignore
		$prefix        = $section_id . '_';
		$field_options = $this->getAllFieldOptions();

		$ids = $params[ $prefix . 'id' ];

		$item_additional_check_callback = function( $item ) use ( $ids ) {

			if ( ! $item instanceof UpStream_Model_Project ) {
				return false;
			}

			if ( ! upstream_user_can_access_project( get_current_user_id(), $item->id ) ) {
				return false;
			}

			return ( in_array( $item->id, $ids ) );
		};

		$options_info = $field_options[ $section_id ];
		$items        = $this->parseFields( $params, $prefix, $item_additional_check_callback );

		return $items;
	}

	/**
	 * Parse Task Params
	 *
	 * @param  mixed $params Params.
	 * @param  mixed $section_id Section Id.
	 */
	protected function parseTaskParams( $params, $section_id ) { // phpcs:ignore
		$prefix        = $section_id . '_';
		$field_options = $this->getAllFieldOptions();

		$ids = $params[ $prefix . 'id' ];

		$item_additional_check_callback = function( $item ) use ( $ids ) {

			if ( ! $item instanceof UpStream_Model_Task ) {
				return false;
			}

			if ( ! upstream_override_access_object( true, UPSTREAM_ITEM_TYPE_TASK, $item->id, UPSTREAM_ITEM_TYPE_PROJECT, $item->parent->id, UPSTREAM_PERMISSIONS_ACTION_VIEW ) ) {
				return false;
			}

			return ( in_array( $item->id, $ids ) );

		};

		$options_info = $field_options[ $section_id ];
		$items       = $this->parseFields( $params, $prefix, $item_additional_check_callback );

		return $items;
	}

	/**
	 * Parse Bug Params
	 *
	 * @param  mixed $params Params.
	 * @param  mixed $section_id Section Id.
	 */
	protected function parseBugParams( $params, $section_id ) { // phpcs:ignore
		$prefix        = $section_id . '_';
		$field_options = $this->getAllFieldOptions();

		$ids = $params[ $prefix . 'id' ];

		$item_additional_check_callback = function( $item ) use ( $ids ) {

			if ( ! $item instanceof UpStream_Model_Bug ) {
				return false;
			}

			if ( ! upstream_override_access_object( true, UPSTREAM_ITEM_TYPE_BUG, $item->id, UPSTREAM_ITEM_TYPE_PROJECT, $item->parent->id, UPSTREAM_PERMISSIONS_ACTION_VIEW ) ) {
				return false;
			}

			return ( in_array( $item->id, $ids ) );

		};

		$options_info = $field_options[ $section_id ];
		$items       = $this->parseFields( $params, $prefix, $item_additional_check_callback );

		return $items;
	}

	/**
	 * Parse File Params
	 *
	 * @param  mixed $params Params.
	 * @param  mixed $section_id Section Id.
	 */
	protected function parseFileParams( $params, $section_id ) { // phpcs:ignore
		$prefix        = $section_id . '_';
		$field_options = $this->getAllFieldOptions();
		$ids           = $params[ $prefix . 'id' ];

		$item_additional_check_callback = function( $item ) use ( $ids ) {
			if ( ! $item instanceof UpStream_Model_File ) {
				return false;
			}

			if ( ! upstream_override_access_object( true, UPSTREAM_ITEM_TYPE_FILE, $item->id, UPSTREAM_ITEM_TYPE_PROJECT, $item->parent->id, UPSTREAM_PERMISSIONS_ACTION_VIEW ) ) {
				return false;
			}

			return ( in_array( $item->id, $ids ) );
		};

		$options_info = $field_options[ $section_id ];
		$items       = $this->parseFields( $params, $prefix, $item_additional_check_callback );

		return $items;
	}

	/**
	 * Parse Milestone Params
	 *
	 * @param  mixed $params Params.
	 * @param  mixed $section_id Section Id.
	 */
	protected function parseMilestoneParams( $params, $section_id ) { // phpcs:ignore
		$prefix        = $section_id . '_';
		$field_options = $this->getAllFieldOptions();

		$ids = $params[ $prefix . 'id' ];

		$item_additional_check_callback = function( $item ) use ( $ids ) {

			if ( ! $item instanceof UpStream_Model_Milestone ) {
				return false;
			}

			if ( ! upstream_override_access_object( true, UPSTREAM_ITEM_TYPE_MILESTONE, $item->id, UPSTREAM_ITEM_TYPE_PROJECT, $item->parentId, UPSTREAM_PERMISSIONS_ACTION_VIEW ) ) { // phpcs:ignore
				return false;
			}

			return ( in_array( $item->id, $ids ) );

		};

		$options_info = $field_options[ $section_id ];
		$items       = $this->parseFields( $params, $prefix, $item_additional_check_callback );

		return $items;
	}

	/**
	 * Date Between
	 *
	 * @param  mixed $lower_bound Lower Bound.
	 * @param  mixed $upper_bound Upper Bound.
	 * @param  mixed $val Val.
	 */
	private static function dateBetween( $lower_bound, $upper_bound, $val ) { // phpcs:ignore
		if ( empty( $upper_bound ) && empty( $lower_bound ) ) {
			return true;
		}
		if ( ! $val ) {
			return false;
		}

		try {
			if ( empty( $lower_bound ) ) {
				$lower_bound = new DateTime( '1970-01-01' );
			} else {
				$lower_bound = new DateTime( $lower_bound );
			}
			if ( empty( $upper_bound ) ) {
				$upper_bound = new DateTime( '9999-01-01' );
			} else {
				$upper_bound = new DateTime( $upper_bound );
			}

			$val = new DateTime( $val );
			$d1  = $lower_bound->diff( $val )->format( '%R%a' );
			$d2  = $val->diff( $upper_bound )->format( '%R%a' );

			if ( $d1 < 0 ) {
				return false;
			}
			if ( $d2 < 0 ) {
				return false;
			}
			return true;
		} catch ( \Exception $e ) {
			return true;
		}
	}

	/**
	 * Number Between
	 *
	 * @param  mixed $lower_bound Lower Bound.
	 * @param  mixed $upper_bound Upper Bound.
	 * @param  mixed $val Val.
	 */
	private static function numberBetween( $lower_bound, $upper_bound, $val ) { // phpcs:ignore
		if ( ( empty( $upper_bound ) && 0 != $upper_bound ) && ( empty( $lower_bound ) && 0 != $lower_bound ) ) {
			return true;
		}
		if ( empty( $val ) ) {
			$val = 0;
		}

		try {
			if ( empty( $lower_bound ) || ! is_numeric( $lower_bound ) ) {
				$lower_bound = -999999;
			}
			if ( empty( $upper_bound ) || ! is_numeric( $upper_bound ) ) {
				$upper_bound = 999999;
			}

			if ( $val < $lower_bound ) {
				return false;
			}
			if ( $val > $upper_bound ) {
				return false;
			}
			return true;
		} catch ( \Exception $e ) {
			return true;
		}
	}

	/**
	 * Check Item
	 *
	 * @param  mixed $item Item.
	 * @param  mixed $params Params.
	 * @param  mixed $prefix Prefix.
	 * @param  mixed $item_additional_check_callback Item Additional Check Callback.
	 */
	protected function checkItem( $item, $params, $prefix, $item_additional_check_callback ) { // phpcs:ignore
		$fields = $item->fields();
		if ( $item_additional_check_callback && $item_additional_check_callback( $item ) == false ) {
			return false;
		}

		foreach ( $fields as $field_name => $field ) {
			if ( ! $field['search'] ) {
				continue;
			}

			if ( ! isset( $params[ $prefix . $field_name ] ) || empty( $params[ $prefix . $field_name ] ) ) {
				continue;
			}

			$value = $params[ $prefix . $field_name ];

			if ( is_array( $value ) ) {
				if ( 'user_id' === $field['type'] || 'select' === $field['type'] ) {
					if ( ! $this->arrayIn( $value, $item->{$field_name} ) ) {
						return false;
					}
				}
			} else {
				if ( 'string' === $field['type'] || 'text' === $field['type'] ) {
					if ( ! stristr( $item->{$field_name}, $value ) ) {
						return false;
					}
				} elseif ( 'color' === $field['type'] ) {
					if ( ! stristr( $item->{$field_name}, $value ) ) {
						return false;
					}
				} elseif ( 'date' === $field['type'] ) {
					$value_start = $params[ $prefix . $field_name . '_start' ];
					$value_end   = $params[ $prefix . $field_name . '_end' ];
					if ( ! self::dateBetween( $value_start, $value_end, $item->{$field_name} ) ) {
						return false;
					}
				} elseif ( 'number' === $field['type'] ) {
					$value_start = $params[ $prefix . $field_name . '_lower' ];
					$value_end   = $params[ $prefix . $field_name . '_upper' ];
					if ( ! self::numberBetween( $value_start, $value_end, $item->{$field_name} ) ) {
						return false;
					}
				}
				// elseif ( $item->{$field_name} != $value ) {
				// return false;
				// }.
			}
		}

		return true;
	}

	/**
	 * Parse Fields
	 *
	 * @param  mixed $params Params.
	 * @param  mixed $prefix Prefix.
	 * @param  mixed $item_additional_check_callback Item Additional Check Callback.
	 */
	protected function parseFields( $params, $prefix, $item_additional_check_callback ) { // phpcs:ignore
		$mm = UpStream_Model_Manager::get_instance();

		$items = $mm->findAllByCallback(
			function( $item ) use ( $params, $prefix, $item_additional_check_callback ) {
				return $this->checkItem( $item, $params, $prefix, $item_additional_check_callback );
			}
		);

		return $items;
	}

	/**
	 * Make Cell
	 *
	 * @param  mixed $val Val.
	 * @param  mixed $field Field.
	 * @param  mixed $users Users.
	 * @param  mixed $override_f Override F.
	 */
	public function makeCell( $val, &$field, &$users, $override_f = null ) { // phpcs:ignore
		$f = null;

		if ( ! is_array( $val ) ) {
			$val = array( $val );
		}
		$val_count = count( $val );

		for ( $j = 0; $j < $val_count; $j++ ) {
			if ( ! empty( $field['options_cb'] ) ) {
				$options = call_user_func( $field['options_cb'] );
				if ( isset( $val[ $j ] ) && isset( $options[ $val[ $j ] ] ) ) {
					$val[ $j ] = $options[ $val[ $j ] ];
				}
			} elseif ( isset( $val[ $j ] ) && 'user_id' === $field['type'] ) {
				if ( isset( $users[ $val[ $j ] ] ) ) {
					$val[ $j ] = $users[ $val[ $j ] ];
				}
			} elseif ( isset( $val[ $j ] ) && 'file' === $field['type'] ) {
				if ( upstream_filesytem_enabled() ) {
					$file = upstream_upfs_info( $val[ $j ] );
					if ( $file ) {
						$val[ $j ] = $file->orig_filename;
					}
				} else {
					if ( $val[ $j ] ) {
						$file      = get_attached_file( $val[ $j ] );
						$val[ $j ] = $file ? basename( $file ) : '';
					} else {
						$val[ $j ] = '';
					}
				}
			} elseif ( isset( $val[ $j ] ) && 'date' === $field['type'] ) {
				if ( $val[ $j ] ) {
					$dp        = explode( '-', $val[ $j ] );
					$val[ $j ] = 'Date(' . $dp[0] . ',' . sprintf( '%02d', $dp[1] - 1 ) . ',' . $dp[2] . ')';
					$f         = null;
				} else {
					$val[ $j ] = '';
					$f         = '(empty)';
				}
			}
		} // end for

		if ( $override_f ) {
			$f = $override_f;
		}

		if ( 'number' === $field['type'] ) {
			$val[0] = (float) $val[0];
		}

		return $this->makeItem( $val, $f );
	}

	/**
	 * Execute Report
	 *
	 * @param  mixed $params Params.
	 * @param  mixed $row_callback Row Callback.
	 */
	public function executeReport( $params, $row_callback = null ) { // phpcs:ignore
		$display_fields = $params['display_fields'];
		$items          = $this->getIncludedItems( $params );
		$data           = array();
		$users_info     = upstream_get_viewable_users();
		$users          = $users_info['by_uid'];
		$columns        = array();
		$items_count    = count( $items );

		for ( $i = 0; $i < $items_count; $i++ ) {

			$item   = $items[ $i ];
			$fields = $items[ $i ]->fields();
			$row    = array();

			foreach ( $display_fields as $field_name ) {
				if ( isset( $fields[ $field_name ] ) && $fields[ $field_name ]['display'] ) {

					$field                  = $fields[ $field_name ];
					$columns[ $field_name ] = $field;

					$parent_type = null;
					$parent_id   = 0;

					if ( $item instanceof UpStream_Model_Meta_Object ) {
						$parent_type = $item->parent->type;
						$parent_id   = $item->parent->id;
					} elseif ( $item instanceof UpStream_Model_Milestone ) {
						$parent_type = UPSTREAM_ITEM_TYPE_PROJECT;
						$parent_id   = $item->parentId; // phpcs:ignore
					}

					if ( upstream_override_access_field( true, $item->type, $item->id, $parent_type, $parent_id, $field_name, UPSTREAM_PERMISSIONS_ACTION_VIEW ) ) {
						$val   = $item->{$field_name};
						$row[] = $this->makeCell( $val, $field, $users );
					} else {
						$val   = null;
						$row[] = $this->makeCell( $val, $field, $users, '(hidden)' );
					}
				}
			} // end foreach

			if ( null != $row_callback ) {
				$row = call_user_func( $row_callback, $row );
			}

			if ( null != $row ) {
				$data[] = array( 'c' => $row );
			}
		}

		$column_info = $this->makeColumnInfo( $columns );

		return array(
			'cols' => $column_info,
			'rows' => $data,
		);
	}

	/**
	 * Make Item
	 *
	 * @param  mixed $val Val.
	 * @param  mixed $f F.
	 */
	protected function makeItem( $val, $f = null ) { // phpcs:ignore
		$r = array( 'v' => self::combineArray( $val ) );
		if ( $f ) {
			$r['f'] = $f;
		}
		return $r;
	}

	/**
	 * Get Included Items
	 *
	 * @param  mixed $params Params.
	 */
	public function getIncludedItems( $params ) { // phpcs:ignore
		return array();
	}

	/**
	 * Make Column Info
	 *
	 * @param  mixed $columns Columns.
	 */
	protected function makeColumnInfo( &$columns ) { // phpcs:ignore
		$column_info = array();

		foreach ( $columns as $cid => $column ) {
			$ci          = array();
			$ci['id']    = $cid;
			$ci['label'] = $column['title'];

			switch ( $column['type'] ) {

				case 'date':
					$ci['type'] = 'date';
					break;

				case 'number':
					$ci['type'] = 'number';
					break;

				default:
					$ci['type'] = 'string';
			}
			$column_info[] = $ci;
		}

		return $column_info;
	}

}

/**
 * Class UpStream_Report_Projects
 */
class UpStream_Report_Projects extends UpStream_Report {

	/**
	 * Id
	 *
	 * @var string
	 */
	public $id = 'projects';

	/**
	 * Construct
	 *
	 * @return void
	 */
	public function __construct() {
		$this->title = __( 'Project Table', 'upstream-reporting' );
	}

	/**
	 * Get Display Options
	 */
	public function getDisplayOptions() {
		return array(
			'show_display_fields_box' => true,
			'visualization_type'      => 'Table',
		);
	}

	/**
	 * Get All Field Options
	 */
	public function getAllFieldOptions() {
		return array( 'projects' => array( 'type' => 'project' ) );
	}

	/**
	 * Get Included Items
	 *
	 * @param  mixed $params Params.
	 */
	public function getIncludedItems( $params ) {
		$items = self::parseProjectParams( $params, 'projects' );

		return $items;
	}
}
