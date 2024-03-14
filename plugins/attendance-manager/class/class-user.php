<?php
/** 
 *	User
 */

class ATTMGR_User {

	public $data = array();
	public $loggedin = null;

	/** 
	 *	CONSTRUCT
	 */
	public function __construct( $user_id = null ) {
		global $current_user;

		$currentuser = false;
		if ( ! empty( $current_user->ID ) ) {
			$currentuser = self::get_user( $current_user->ID );
		}

		if ( empty( $user_id ) ) {
			// current user
			if ( ! empty( $currentuser ) ) {
				$this->data = $currentuser;
				$this->loggedin = true;
			}
		} else {
			if ( ! empty( $currentuser ) && $user_id == $currentuser['ID'] ) {
				$this->data = $currentuser;
				$this->loggedin = true;
			} else {
				$this->data = self::get_user( $user_id );
				if ( ! empty( $this->data ) ) {
					$this->loggedin = false;
				}
			}
		}
	}

	/**
	 *	Load
	 */
	public static function load() {
		add_filter( ATTMGR::PLUGIN_ID.'_get_user_data', array( 'ATTMGR_User', 'get_user_data' ), 10, 2 );
		add_filter( ATTMGR::PLUGIN_ID.'_is_usertype_admin', array( 'ATTMGR_User', 'is_admin_filter' ), 10, 2 );
		add_filter( ATTMGR::PLUGIN_ID.'_is_usertype_staff', array( 'ATTMGR_User', 'is_staff_filter' ), 10, 2 );
		add_filter( ATTMGR::PLUGIN_ID.'_can_edit_admin_scheduler', array( 'ATTMGR_User', 'can_edit_admin_scheduler_filter' ), 10, 2 );
	}

	/**
	 *	Initialize
	 */
	public static function init() {
		add_action( 'user_new_form', array( 'ATTMGR_User', 'extra_fields' ) );
		add_action( 'show_user_profile', array( 'ATTMGR_User', 'extra_fields' ) );
		add_action( 'edit_user_profile', array( 'ATTMGR_User', 'extra_fields' ) );
		add_action( 'user_register', array( 'ATTMGR_User', 'save_extra_fields' ) );
		add_action( 'edit_user_profile_update', array( 'ATTMGR_User', 'save_extra_fields' ) );

		add_filter( ATTMGR::PLUGIN_ID.'_extra_profile', array( 'ATTMGR_User', 'extra_profile' ), 10, 2 );

		add_action( 'publish_post', array( 'ATTMGR_User', 'save_staff_url' ) );
		add_action( 'publish_page', array( 'ATTMGR_User', 'save_staff_url' ) );
		add_action( 'trash_post', array( 'ATTMGR_User', 'delete_staff_url' ) );
		add_action( 'trash_page', array( 'ATTMGR_User', 'delete_staff_url' ) );

		add_action( 'manage_users_columns', array( 'ATTMGR_User', 'add_columns' ) );
		add_action( 'manage_users_custom_column', array( 'ATTMGR_User', 'custom_column' ), 10, 3 );
	}

	/** 
	 *	Log-in inspection
	 */
	public function is_loggedin() {
		if ( $this->loggedin ) {
			return true;
		}
		return false;
	}

	/** 
	 *	User type inspection 
	 */
	public function is_type( $type ) {
		// Types are 'admin', 'staff', 'member'..
		$result = false;
		$result = apply_filters( ATTMGR::PLUGIN_ID.'_is_usertype_'.$type, $result, $this );
		return $result;
	}

	/** 
	 *	User type 'admin' inspection 
	 */
	public function is_admin() {
		return $this->is_type( 'admin' );
	}
	public static function is_admin_filter( $result, $user ) {
		if ( in_array( 'administrator', $user->data['roles'] ) ) {
			$result = true;
		}
		return $result;
	}

	/** 
	 *	User type 'staff' inspection 
	 */
	public function is_staff() {
		return $this->is_type( 'staff' );
	}
	public static function is_staff_filter( $result, $user ) {
		$staff_attr = ATTMGR::PLUGIN_ID.'_ex_attr_staff';
		if ( get_user_meta( $user->data['ID'], $staff_attr, true ) ) {
			$result = true;
		}
		return $result;
	}

	/** 
	 *	User who works today
	 */
	public function is_work( $date ) {
		global $wpdb;

		if ( $this->is_staff() ) {
			$table = '';
			$table = $wpdb->prefix.ATTMGR::TABLEPREFIX.'schedule';
			$table = apply_filters( 'attmgr_schedule_table_name', $table );
			$query = "SELECT * FROM $table "
					."WHERE `staff_id`=%d "
					."AND `date`=%s "
					."AND `starttime` IS NOT NULL "
					."AND `endtime` IS NOT NULL ";
			$ret = $wpdb->get_row( $wpdb->prepare( $query, array( $this->data['ID'], $date ) ), ARRAY_A );
			return $ret;
		}
		return false;
	}

	/** 
	 *	User working from yesterday
	 */
	public function is_work_from_yesterday( $date ) {
		global $attmgr, $wpdb;

		if ( $this->is_staff() ) {
			$table = '';
			$table = apply_filters( 'attmgr_schedule_table_name', $table );

			$now = current_time('timestamp');
			$now_time = date( 'H:i', $now );
			$today = date( 'Y-m-d', $now );
			// e.g. 19:00 ~ 28:00 -> 19:00 ~ 04:00
			$endtime = ATTMGR_Form::time_calc( $attmgr->option['general']['endtime'], 0, false );
			if ( $date == $today && $attmgr->option['general']['starttime'] > $endtime ) {
				// e.g. now 02:15 (end 04:00) 
				if ( $now_time < $endtime ) {
					$date = date( 'Y-m-d', $now - ( 60 * 60 * 24 ) );
					$query = "SELECT * FROM $table "
							."WHERE `staff_id`=%d "
							."AND `date`=%s "
							."AND `starttime` IS NOT NULL "
							."AND `endtime` IS NOT NULL "
							."AND `endtime` >= '".ATTMGR_Form::time_calc( $now_time, 60 * 24 )."' ";
					$ret = $wpdb->get_row( $wpdb->prepare( $query, array( $this->data['ID'], $date ) ), ARRAY_A );
					return $ret;
				}
			}
		}
		return false;
	}

	/** 
	 *	Admin acting user inspection 
	 */
	public function is_acting() {
		if ( $this->is_loggedin() && $this->is_admin() && isset( $_SESSION['acting_as'] ) ) {
			return $_SESSION['acting_as'];
		}
		return false;
	}

	/**
	 *	Act as staff
	 */
	public function acting( $query_vars ) {
		// Query string
		$qs = array();
		if ( ! empty( $_SERVER['QUERY_STRING'] ) ) {
			parse_str( $_SERVER['QUERY_STRING'], $qs );
		}
		if ( $this->is_loggedin() && $this->is_admin() ) {
			if ( isset( $qs['staff_id'] ) ) {
				$user = new ATTMGR_User( $qs['staff_id'] );
				if ( $user->is_staff() ) {
					$_SESSION['acting_as'] = $user;
				}
				else {
					unset( $_SESSION['acting_as'] );
				}
			}
		}
	}

	/** 
	 *	Can edit admin scheduler
	 */
	public function can_edit_admin_scheduler() {
		$result = false;
		$result = apply_filters( ATTMGR::PLUGIN_ID.'_can_edit_admin_scheduler', $result, $this );
		return $result;
	}
	public static function can_edit_admin_scheduler_filter( $result, $user ) {
		if ( $user->is_admin() ) {
			$result = true;
		}
		return $result;
	}

	/**  
	 *	Get user info
	 */
	public static function get_user( $user_id ) {
		$args = array(
			'include' => array( $user_id ),
			);
		$user_query = new WP_User_Query( $args );
		list( $user ) = $user_query->results;
		/*
		WP_User Object(
			[data] => stdClass Object(
					[ID] => 2
					[user_login] => user02
					[user_pass] => **********************************
					[user_nicename] => user02
					[user_email] => hoge@example.com
					[user_url] => 
					[user_registered] => 2013-01-01 00:00:00
					[user_activation_key] => 
					[user_status] => 0
					[display_name] => user02
				)
			[ID] => 2
			[caps] => Array(
					[subscriber] => 1
				)
			[cap_key] => a_wp_capabilities
			[roles] => Array(
					[0] => subscriber
				)
			[allcaps] => Array(
					[read] => 1
					[level_0] => 1
					[subscriber] => 1
				)
			[filter] => 
		)
		*/
		$userdata = array();
		if ( ! empty( $user->ID ) ) {
			$userdata = apply_filters( ATTMGR::PLUGIN_ID.'_get_user_data', $userdata, $user );
//			$userdata = self::get_user_data( $userdata, $user );
		}
		return $userdata;
	}
 
	/** 
	 *	Get user data
	 */
	public static function get_user_data( $userdata, $user ) {
		$staff_attr = ATTMGR::PLUGIN_ID.'_ex_attr_staff';
		$staff_mypage_id = ATTMGR::PLUGIN_ID.'_mypage_id';
		$org = (array) $user;
		$data = (array) $org['data'];
		unset( $org['data'] );
		$userdata = array_merge( $data, $org );
		$userdata['first_name'] = get_user_meta( $user->ID, 'first_name', true );
		$userdata['last_name'] = get_user_meta( $user->ID, 'last_name', true );
		$userdata['nickname'] = get_user_meta( $user->ID, 'nickname', true );
		$userdata[ $staff_attr ] = get_user_meta( $user->ID, $staff_attr, true );
		$userdata[ $staff_mypage_id ] = get_user_meta( $user->ID, $staff_mypage_id, true );
		/*
		ATTMGR_User Object (
			[data] => Array
				(
					[ID] => 1
					[user_login] => hoge
					[user_pass] => ***
					[user_nicename] => hoge
					[user_email] => hoge@localhost.localdomain
					[user_url] => 
					[user_registered] => 20XX-XX-XX 00:00:00
					[user_activation_key] => 
					[user_status] => 0
					[display_name] => hoge
					[caps] => Array(
							[administrator] => 1
						)
					[cap_key] => ***_wp_capabilities
					[roles] => Array(
							[0] => administrator
						)
					[allcaps] => Array(
							...
						)
					[filter] => 
					[attmgr_ex_attr_staff] => 1
				)

			[loggedin] => 1
		)
		*/
		unset( $userdata['user_pass'] );
		return $userdata;
	}

	/**  
	 *	Get all staff
	 */
	public static function get_all_staff( $args = null ) {
		$meta = array(
		    'meta_query' => array(
		        array(
		            'key' => ATTMGR::PLUGIN_ID.'_ex_attr_staff',
		            'value' => 1,
		            'compare' => '='
		        ),
			)
		);
		$user_query = new WP_User_Query( $meta );
		$users = $user_query->results;
		$staff = array();
		if ( ! empty( $users ) ) {
			foreach ( $users as $u ) {
				if ( ! empty( $u->ID ) ) {
					$user = new ATTMGR_User( $u->ID );
					//if ( $user->is_staff() ) {
						$staff[] = $user;
					//}
				}
			}
		}
		return $staff;
	}

	/**  
	 *	Get working staff
	 */
	public static function get_working_staff( $date, $yesterday = false ) {
		$meta = array(
		    'meta_query' => array(
		        array(
		            'key' => ATTMGR::PLUGIN_ID.'_ex_attr_staff',
		            'value' => 1,
		            'compare' => '='
		        ),
			)
		);
		$user_query = new WP_User_Query( $meta );
		$users = $user_query->results;
		$staff = array();
		$attendance = array();
		if ( ! empty( $users ) ) {
			foreach ( $users as $u ) {
				if ( ! empty( $u->ID ) ) {
					$user = new ATTMGR_User( $u->ID );
					if ( $yesterday == false ) {
						if ( $ret = $user->is_work( $date ) ) {
							$staff[] = $user;
							$attendance[$u->ID] = $ret;
						}
					} else {
						if ( $ret = $user->is_work_from_yesterday( $date ) ) {
							$staff[] = $user;
							$attendance[$u->ID] = $ret;
						}
					}
				}
			}
		}
		$result = array(
			'staff' => $staff,
			'attendance' => $attendance
		);
		return $result;
	}

	/**
	 *	User extra fields
	 */
	public static function extra_fields( $user ) {
		$html = '';
		$html = apply_filters( ATTMGR::PLUGIN_ID.'_extra_profile', $html, $user );
		echo $html;
	}

	/**
	 *	User extra profile
	 */
	public static function extra_profile( $html, $user ) {
		global $pagenow;

		$staff_attr = ATTMGR::PLUGIN_ID.'_ex_attr_staff';
		$checked = $readonly = '';
		if ( in_array( $pagenow, array( 'profile.php', 'user-edit.php' ) )
			&& get_user_meta( $user->ID, $staff_attr, true ) ) {
			$checked = 'checked';
		}
		if ( in_array( $pagenow, array( 'profile.php' ) ) ) {
			$readonly = 'readonly onclick="return false;"';
		}

		$extra_fields = <<<EOD
<h3 id="%TITLE_ID%" class="title">%TITLE%</h3>
<table id="%TABLE_ID%" class="form-table %TITLE_ID%">
	<tr>
		<th scope="row">%STAFF_LABEL%</th>
		<td><label for="%STAFF_ATTR%"><input type="checkbox" name="%STAFF_ATTR%" id="%STAFF_ATTR%" value="1" %CHECKED% %READONLY% /> %STAFF_DESCRIPTION%</label></td>
	</tr>
</table>
EOD;
		$search = array(
			'%TITLE_ID%',
			'%TITLE%',
			'%TABLE_ID%',
			'%STAFF_LABEL%',
			'%STAFF_ATTR%',
			'%STAFF_DESCRIPTION%',
			'%CHECKED%',
			'%READONLY%'
		);
		$replace = array(
			ATTMGR::PLUGIN_ID.'_ex_fields_title',
			__( 'Extra fields for Attendance Manager', ATTMGR::TEXTDOMAIN ),
			ATTMGR::PLUGIN_ID.'_ex_fields',
			__( 'Staff', ATTMGR::TEXTDOMAIN ),
			$staff_attr,
			__( 'This user is staff.', ATTMGR::TEXTDOMAIN ),
			$checked,
			$readonly
		);
		$extra_fields = str_replace( $search, $replace, $extra_fields );
		return $html . $extra_fields;
	}

	/**
	 *	Save user extra fields
	 */
	public static function save_extra_fields( $user_id ) {

		$staff_attr = ATTMGR::PLUGIN_ID.'_ex_attr_staff';

		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return false;
		}
		if ( isset( $_POST[ $staff_attr ] ) ) {
			update_user_meta( $user_id, $staff_attr, true );
		} else {
			update_user_meta( $user_id, $staff_attr, false );
		}
	}

	/** 
	 *	Save url of staff's page
	 */
	public static function save_staff_url( $post_id ) {
		$post = get_post( $post_id );
		$staff = ATTMGR_User::parse_sfaff_id( $post->post_content );
		if ( $staff ) {
			wp_update_user( array( 'ID'=>$staff->data['ID'], 'user_url'=>get_permalink( $post_id ) ) );
			update_user_meta( $staff->data['ID'], ATTMGR::PLUGIN_ID.'_mypage_id', $post_id );
		}
	}

	/** 
	 *	Delete url of staff's page
	 */
	public static function delete_staff_url( $post_id ) {
		$post = get_post( $post_id );
		$staff = ATTMGR_User::parse_sfaff_id( $post->post_content );
		if ( $staff ) {
			wp_update_user( array( 'ID'=>$staff->data['ID'], 'user_url'=>'' ) );
			delete_user_meta( $staff->data['ID'], ATTMGR::PLUGIN_ID.'_mypage_id' );
		}
	}

	/**
	 *	Get staff-id form shortcode
	 */
	public static function parse_sfaff_id( $content ) {
		$content = str_replace( "\"", '', stripslashes( $content ) );
		if ( ! empty( $content ) ) {
			$match = preg_match( '/\[attmgr_weekly\s.+\]/', $content, $matches );
			if ( $match ) {
				$id_match = preg_match( '/id=[0-9]+/', $matches[0], $id_part );
				if ( $id_match ) {
					$id = preg_replace( '/[^0-9]/', '', $id_part[0] );
					$staff = new ATTMGR_User( $id );
					if ( ! empty( $staff->data['ID'] ) && $staff->is_staff() ) {
						return $staff;
					}
				}
			}
		}
		return false;
	}

	/** 
	 *	Add user list column
	 */
	public static function add_columns( $column_headers ) {
		$column_headers[ATTMGR::PLUGIN_ID.'_ex_attr_staff'] = __( 'Staff', ATTMGR::TEXTDOMAIN );
		$column_headers['ID'] = __('ID', ATTMGR::TEXTDOMAIN );
		return $column_headers;
	}

	/** 
	 *	Customize user list
	 */
	public static function custom_column( $custom_column, $column_name, $user_id ) {
	
		$user_info = get_userdata( $user_id );
	
		if ( $column_name == ATTMGR::PLUGIN_ID.'_ex_attr_staff' ) {
			${ $column_name } = ( $user_info->$column_name == 1 ) ? __( 'Staff', ATTMGR::TEXTDOMAIN ) : '';
		}
		else {
			${ $column_name } = $user_info->$column_name;
		}
		$custom_column = "\t".${ $column_name }."\n";
	
		return $custom_column;
	}
}
?>
