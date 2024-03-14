<?php
namespace Includes\Libraries;

/**
 *  Usermigration class for migrate users data.
 */
class Usermigration {

	/**
	 * Is_online
	 *
	 * (default value: false )
	 *
	 * @var boolean
	 * @access private
	 */
	private static $is_online = false;
	/**
	 * Notification
	 *
	 * (default value: true )
	 *
	 * @var boolean
	 * @access private
	 */
	private static $notification = true;
	/**
	 * Visibility
	 *
	 * (default value: true )
	 *
	 * @var boolean
	 * @access private
	 */
	private static $visibility = true;
	/**
	 * Language
	 *
	 * (default value: 'en' )
	 *
	 * @var string
	 * @access private
	 */
	private static $language = 'en';
	/**
	 * Is_deleted
	 *
	 * (default value: false )
	 *
	 * @var boolean
	 * @access private
	 */
	private static $is_deleted = false;
	/**
	 * Is_active
	 *
	 * (default value: true )
	 *
	 * @var boolean
	 * @access private
	 */
	private static $is_active = true;
	/**
	 * Role
	 *
	 * (default value: 'user' )
	 *
	 * @var string
	 * @access private
	 */
	private static $role = 'user';
	/**
	 * Channelize_migration_data migrate user's data
	 *
	 * @param array $user_data associate array.
	 *
	 * @return $migation_json_encode_data
	 */
	public function channelize_migration_data( $user_data = array() ) {
		$migration_data_temp = array();
		$user_count_data     = count( $user_data );
		$data_str            = (string) '$date';
		$metadata_object     = (object) array();
		for ( $i = 0; $i < $user_count_data; $i++ ) {
			$migration_data_temp[] = array(
				'_id'             => $user_data[ $i ]['id'],
				'email'           => $user_data[ $i ]['email'],
				'displayName'     => $user_data[ $i ]['displayName'],
				'profileImageUrl' => $user_data[ $i ]['profileImageUrl'],
				'profileUrl'      => $user_data[ $i ]['profileUrl'],
				'isOnline'        => self::$is_online,
				'notification'    => self::$notification,
				'visibility'      => self::$visibility,
				'language'        => self::$language,
				'lastSeen'        => array( $data_str => gmdate( 'c' ) ),
				'createdAt'       => array( $data_str => $user_data[ $i ]['createdAt'] ),
				'updatedAt'       => array( $data_str => gmdate( 'c' ) ),
				'friends'         => array(),
				'blocks'          => array(),
				'isDeleted'       => self::$is_deleted,
				'isActive'        => self::$is_active,
				'role'            => self::$role,
				'metaData'        => $metadata_object,
			);
		}

		$migation_json_encode_data = wp_json_encode( $migration_data_temp, JSON_PRETTY_PRINT );

		return $migation_json_encode_data;

	}



}
