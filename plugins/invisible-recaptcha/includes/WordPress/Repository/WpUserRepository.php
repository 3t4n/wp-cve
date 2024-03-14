<?php
/**
 * Copyright (c) 2016 Ultra Community (http://www.ultracommunity.com)
 */

namespace InvisibleReCaptcha\MchLib\WordPress\Repository;


final class WpUserRepository
{
	/**
	 * @param        $userId
	 * @param string $metaKey
	 * @param bool   $single
	 *
	 * @return mixed
	 */
	public static function getUserMeta($userId, $metaKey = '', $single = true)
	{
		$arrUserMeta =  \get_user_meta($userId, $metaKey, $single);

		if(empty($arrUserMeta)){
			return null;
		}

		if(empty($metaKey))
		{
			$arrUserMeta =  array_map(function($arrMetaInfo){return isset($arrMetaInfo[0]) ? $arrMetaInfo[0] : $arrMetaInfo;}, $arrUserMeta);
		}

		return $arrUserMeta;

	}

	/**
	 * @param $userId
	 *
	 * @return false|null|\WP_User
	 */
	public static function getUserById($userId)
	{
		return false !== ( $user = self::getUserBy('id', $userId) ) ? $user : null;
	}

	public static function getUserByEmail($email)
	{
		return false !== ( $user = self::getUserBy('email', $email) ) ? $user : null;
	}

	public static function getUserByUserName($userName)
	{
		return false !== ( $user = self::getUserBy('login', $userName) ) ? $user : null;
	}

	public static function getUserByNiceName($userName)
	{
		return false !== ( $user = self::getUserBy('slug', $userName) ) ? $user : null;
	}

	/**
	 * @param $field
	 * @param $value
	 *
	 * @return false|\WP_User
	 */
	private static function getUserBy($field, $value)
	{
		return \get_user_by( $field, $value );
	}

	/**
	 * @param \WP_User $wpUser
	 *
	 * @return int|\WP_Error
	 */
	public static function saveUser(\WP_User $wpUser)
	{
		return wp_insert_user($wpUser); // does an update if ID is passed
	}

	/**
	 * @param $userId
	 * @param $metaKey
	 * @param $metaValue
	 *
	 * @return bool|int
	 */
	public static function saveUserMeta($userId, $metaKey, $metaValue)
	{
		return update_user_meta( $userId, $metaKey, $metaValue ); // returns Meta ID if the key didn't exist; true on successful update; false on failure or if $meta_value is the same as the existing meta value in the database.
	}

	/**
	 * @param        $userId
	 * @param        $metaKey
	 * @param string $metaValue
	 *
	 * @return bool
	 */
	public static function deleteUserMeta($userId, $metaKey, $metaValue = '')
	{
		return delete_user_meta($userId, $metaKey, $metaValue);
	}

	/**
	 * @param $userName
	 *
	 * @return bool
	 */
	public static function userNameExists($userName)
	{
		return (bool)username_exists($userName);
	}

	/**
	 * @param $userEmailAddress
	 *
	 * @return bool
	 */
	public static function userEmailExists($userEmailAddress)
	{
		return (bool)email_exists($userEmailAddress);
	}

}

