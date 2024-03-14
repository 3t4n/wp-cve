<?php

namespace WPRuby_CAA\Core\Dto;

use WPRuby_CAA\Core\Constants;

class User {

	/** @var int */
	private $id;
	/** @var string */
	private $email;
	/** @var string */
	private $expiringIn;
	/** @var int */
	private $lastLogin;
	/** @var int */
	private $createdAt;
	/** @var boolean */
	private $isDeactivated;
	/** @var string  */
	private $dateTimeFormat;
	/** @var bool  */
	private $hideAdminBar;
	/** @var bool  */
	private $isCaaAccount;
	private $wp_user = null;
	/** @var array **/
	private $restricted_menu;

	public function __construct($id)
	{
		$this->id = intval($id);
		$this->dateTimeFormat = sprintf('%s %s', get_option('date_format'), get_option('time_format'));
	}

	/**
	 * @return \WP_User
	 */
	public function wp_user()
	{
		if ($this->wp_user === null) {
			$this->wp_user = get_user_by('id', $this->getId());
		}

		return $this->wp_user;
	}

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param int $id
	 *
	 * @return User
	 */
	public function setId( $id ) {
		$this->id = $id;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getEmail() {
		return $this->wp_user()->user_email;
	}

	/**
	 * @param string $email
	 *
	 * @return User
	 */
	public function setEmail( $email ) {
		$this->email = $email;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getExpiringIn()
	{
		$this->expiringIn = get_user_meta(intval($this->id), Constants::USER_EXPIRING_IN, true);
		if (!$this->expiringIn) {
			$this->expiringIn = -1;
		}
		return $this->expiringIn;
	}

	/**
	 * @param string $expiringIn
	 *
	 */
	public function setExpiringIn( $expiringIn ) {
		update_user_meta($this->id, Constants::USER_EXPIRING_IN, $expiringIn);
		$this->expiringIn =$expiringIn;
	}

	public function getExpiringInHuman()
	{
		$expiringIn = intval( $this->getExpiringIn());

		if ($expiringIn === 0) {
			return 'Non Expiring';
		}

		if (!is_numeric($expiringIn)) {
			return 'Non Expiring';
		}

		if (intval($expiringIn)  === -1) {
			return 'Non Expiring';
		}


		$expiringInDate = strtotime(sprintf('+%s hours', $expiringIn), $this->getCreatedAt());

		if (time() > $expiringInDate) {
			return 'Expired';
		}

		return human_time_diff(time(), $expiringInDate);
	}

	/**
	 * @return int
	 */
	public function getLastLogin() {
		$this->lastLogin = get_user_meta(intval($this->id), Constants::USER_LAST_LOGIN, true);
		return $this->lastLogin;
	}

	/**
	 * @param \DateTime $lastLogin
	 *
	 */
	public function setLastLogin( $lastLogin ) {
		update_user_meta($this->id, Constants::USER_LAST_LOGIN, $lastLogin);
		$this->lastLogin = $lastLogin;
	}

	/**
	 * @return int
	 */
	public function getCreatedAt()
	{
		$this->createdAt = get_user_meta(intval($this->id), Constants::USER_CREATED_AT, true);
		if (!$this->createdAt) {
			$this->createdAt = time();
		}
		return $this->createdAt;
	}

	/**
	 * @param int $createdAt
	 *
	 */
	public function setCreatedAt( $createdAt )
	{
		update_user_meta($this->id, Constants::USER_CREATED_AT, $createdAt);
		$this->createdAt = $createdAt;
	}

	/**
	 * @return bool
	 */
	public function isDeactivated() {
		$this->isDeactivated = boolval(get_user_meta(intval($this->id), Constants::USER_DEACTIVATED, true));
		return $this->isDeactivated;
	}

	/**
	 * @param bool $isDeactivated
	 *
	 */
	public function setIsDeactivated( $isDeactivated ) {
		update_user_meta($this->id, Constants::USER_DEACTIVATED, boolval($isDeactivated));
		$this->isDeactivated = $isDeactivated;
	}

	/**
	 * @return bool
	 */
	public function isAdminBarHidden() {
		$this->hideAdminBar = boolval(get_user_meta(intval($this->id), Constants::USER_HIDE_ADMIN_BAR, true));
		return $this->hideAdminBar;
	}

	/**
	 * @param bool $hideAdminBar
	 *
	 */
	public function setIsAdminBarHidden( $hideAdminBar ) {
		update_user_meta($this->id, Constants::USER_HIDE_ADMIN_BAR, boolval($hideAdminBar));
		$this->hideAdminBar = $hideAdminBar;
	}

	/**
	 * @param bool $isCaaAccount
	 *
	 */
	public function setIsCaaAccount( $isCaaAccount ) {
		update_user_meta($this->id, Constants::USER_CAA_ACCOUNT, boolval($isCaaAccount));
		$this->isCaaAccount = $isCaaAccount;
	}

	/**
	 * @return bool
	 */
	public function isCaaAccount() {
		$this->isCaaAccount = boolval(get_user_meta(intval($this->id), Constants::USER_CAA_ACCOUNT, true));
		return $this->isCaaAccount;
	}

	public function setRestrictedMenu($restrictedMenu)
	{
		update_user_meta($this->id, Constants::USER_RESTRICTED_MENU_ITEMS, $restrictedMenu);
		$this->restricted_menu = $restrictedMenu;
	}

	/**
	 * @return array
	 */
	public function getRestrictedMenu()
	{
		$this->restricted_menu  = get_user_meta($this->id, Constants::USER_RESTRICTED_MENU_ITEMS, true);
		if (!$this->restricted_menu) {
			$this->restricted_menu = [];
		}
		return $this->restricted_menu;
	}

	public function isExpired()
	{
		if (intval($this->getExpiringIn()) === -1) {
			return false;
		}

		$expiringInDate = strtotime(sprintf('+%s hours', $this->getExpiringIn()), $this->getCreatedAt());

		return time() > $expiringInDate;
	}

	/**
	 * @return bool
	 */
	public function isLoggedIn()
	{
		return boolval(get_user_meta($this->getId(), Constants::USER_IS_LOGGED_IN, true));
	}

	public function setIsLoggedIn($value)
	{
		return update_user_meta($this->getId(), Constants::USER_IS_LOGGED_IN, $value);
	}

	public function getCreatedBy()
	{
		return intval(get_user_meta($this->getId(), Constants::USER_CREATED_BY, true));
	}

	public function setCreatedBy($user_id)
	{
		update_user_meta($this->getId(), Constants::USER_CREATED_BY, $user_id);
	}

	public function reset_user()
	{

		delete_user_meta($this->getId(), Constants::USER_CAA_ACCOUNT);
		delete_user_meta($this->getId(), Constants::USER_CREATED_AT);
		delete_user_meta($this->getId(), Constants::USER_CREATED_BY);
		delete_user_meta($this->getId(), Constants::USER_EXPIRING_IN);
		delete_user_meta($this->getId(), Constants::USER_LAST_LOGIN);
		delete_user_meta($this->getId(), Constants::USER_DEACTIVATED);
		delete_user_meta($this->getId(), Constants::USER_HIDE_ADMIN_BAR);
		delete_user_meta($this->getId(), Constants::USER_RESTRICTED_MENU_ITEMS);
		delete_user_meta($this->getId(), Constants::USER_IS_LOGGED_IN);

	}

	/**
	 * @return array
	 */
	public function toArray()
	{
		return [
			'id' => $this->getId(),
			'email' => $this->getEmail(),
			'password' => '',
			'expiring_in' => $this->getExpiringIn(),
			'expiring_in_human' => $this->getExpiringInHuman(),
			'last_login' => ( $this->getLastLogin() == '')? '-': date($this->dateTimeFormat, $this->getLastLogin()),
			'last_login_human' => ($this->getLastLogin() == '')? '-': human_time_diff($this->getLastLogin()),
			'deactivated' => $this->isDeactivated(),
			'restricted_menu_items' => $this->getRestrictedMenu(),
			'is_logged_in_now' => $this->isLoggedIn(),
			'hide_admin_bar' => $this->isAdminBarHidden(),
			'avatar_url' => sprintf('https://www.gravatar.com/avatar/%s?s=40&d=mp', md5($this->getEmail()))
		];
	}

}
