<?php
namespace um_ext\um_online\core;

use um\core\Member_Directory_Meta;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Online_Member_Directory
 *
 * @package um_ext\um_online\core
 */
class Online_Member_Directory {

	/**
	 * Online_Member_Directory constructor.
	 */
	public function __construct() {
		add_action( 'um_pre_directory_shortcode', array( &$this, 'enqueue_scripts' ), 10, 1 );
		add_filter( 'um_admin_extend_directory_options_profile', array( &$this, 'member_directory_options_profile' ), 10, 1 );

		add_filter( 'um_members_directory_filter_fields', array( $this, 'directory_filter_dropdown_options' ), 10, 1 );
		add_filter( 'um_members_directory_filter_types', array( $this, 'directory_filter_types' ), 10, 1 );
		add_filter( 'um_search_fields', array( $this, 'online_dropdown' ), 10, 1 );

		add_filter( 'um_query_args_online_status__filter', array( $this, 'online_status_filter' ), 10, 4 );

		//UM metadata
		add_filter( 'um_query_args_online_status__filter_meta', array( $this, 'online_status_filter_meta' ), 10, 6 );

		add_filter( 'um_ajax_get_members_data', array( &$this, 'get_members_data' ), 50, 2 );

		add_action( 'um_members_in_profile_photo_tmpl', array( &$this, 'extend_js_template' ), 10, 1 );
		add_action( 'um_members_list_in_profile_photo_tmpl', array( &$this, 'extend_js_template' ), 10, 1 );
	}

	/**
	 *
	 */
	public function enqueue_scripts() {
		UM()->Online()->enqueue_scripts();
	}

	/**
	 * @param array $fields
	 *
	 * @return array
	 */
	public function member_directory_options_profile( $fields ) {
		if ( ! UM()->options()->get( 'online_show_stats' ) ) {
			return $fields;
		}

		$fields = array_merge(
			array_slice( $fields, 0, 3 ),
			array(
				array(
					'id'    => '_um_online_hide_stats',
					'type'  => 'checkbox',
					'label' => __( 'Hide online stats', 'um-online' ),
					'value' => UM()->query()->get_meta_value( '_um_online_hide_stats', null, 'na' ),
				),
			),
			array_slice( $fields, 3, count( $fields ) - 1 )
		);

		return $fields;
	}

	/**
	 * Add Member Directory filter
	 *
	 * @param array $options
	 *
	 * @return array
	 */
	public function directory_filter_dropdown_options( $options ) {
		$options['online_status'] = __( 'Online Status', 'um-online' );
		return $options;
	}

	/**
	 * Set online_status filter type
	 *
	 * @param array $types
	 *
	 * @return array
	 */
	public function directory_filter_types( $types ) {
		$types['online_status'] = 'select';
		return $types;
	}

	/**
	 * Build Select box for Online Status filter
	 * @param array $attrs
	 *
	 * @return array
	 */
	public function online_dropdown( $attrs ) {
		if ( isset( $attrs['metakey'] ) && 'online_status' === $attrs['metakey'] ) {
			$attrs['type'] = 'select';

			$attrs['options'] = array(
				0 => __( 'Offline', 'um-online' ),
				1 => __( 'Online', 'um-online' ),
			);
		}
		return $attrs;
	}

	/**
	 * Filter users by Online status
	 *
	 * @param $query
	 * @param $field
	 * @param $value
	 * @param $filter_type
	 *
	 * @return bool
	 */
	public function online_status_filter( $query, $field, $value, $filter_type ) {
		if ( ! is_array( $value ) ) {
			$value = array( $value );
		}

		if ( ! ( in_array( 1, $value ) && in_array( 0, $value ) ) ) {
			$online_users_array = UM()->Online()->common()->get_online_users();

			foreach ( $value as $val ) {
				if ( $val == '0' ) {
					if ( ! empty( $online_users_array ) ) {
						UM()->member_directory()->query_args['exclude'] = $online_users_array;
					}
				} elseif ( $val == '1' ) {
					if ( ! empty( $online_users_array ) ) {
						UM()->member_directory()->query_args['include'] = $online_users_array;
					}
				}
			}
		}

		UM()->member_directory()->custom_filters_in_query[ $field ] = $value;

		return true;
	}

	/**
	 * Filter users by Online status
	 *
	 * @param $skip
	 * @param Member_Directory_Meta $query
	 * @param $field
	 * @param $value
	 * @param $filter_type
	 * @param bool $is_default
	 *
	 * @return bool
	 */
	public function online_status_filter_meta( $skip, $query, $field, $value, $filter_type, $is_default ) {
		if ( ! is_array( $value ) ) {
			$value = array( $value );
		}

		if ( ! ( in_array( 1, $value ) && in_array( 0, $value ) ) ) {
			$online_users_array = UM()->Online()->common()->get_online_users();

			foreach ( $value as $val ) {
				if ( $val == '0' ) {
					if ( ! empty( $online_users_array ) ) {
						$online_users_array     = array_map( 'absint', $online_users_array );
						$query->where_clauses[] = "u.ID NOT IN ('" . implode( "','", $online_users_array ) . "')";
					}
				} elseif ( $val == '1' ) {
					if ( ! empty( $online_users_array ) ) {
						$online_users_array     = array_map( 'absint', $online_users_array );
						$query->where_clauses[] = "u.ID IN ('" . implode( "','", $online_users_array ) . "')";
					}
				}
			}
		}

		if ( ! $is_default ) {
			$query->custom_filters_in_query[ $field ] = $value;
		}

		return true;
	}

	/**
	 * Expand AJAX member directory data
	 *
	 * @param $data_array
	 * @param $user_id
	 *
	 * @return mixed
	 */
	public function get_members_data( $data_array, $user_id ) {
		$data_array['is_online'] = false;
		if ( ! UM()->Online()->common()->is_hidden_status( $user_id ) ) {
			$data_array['is_online'] = UM()->Online()->is_online( $user_id );
		}

		return $data_array;
	}

	/**
	 * @param $args
	 */
	public function extend_js_template( $args ) {
		$hide_online_show_stats = ! empty( $args['online_hide_stats'] ) ? $args['online_hide_stats'] : ! UM()->options()->get( 'online_show_stats' );

		if ( empty( $hide_online_show_stats ) ) {
			?>
			<# if ( user.is_online ) { #>
				<span class="um-online-status online um-tip-n" title="<?php esc_attr_e( 'Online', 'um-online' ); ?>">
					<i class="um-faicon-circle"></i>
				</span>
			<# } #>
			<?php
		}
	}
}
