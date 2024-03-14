<?php
/**
 * Copyright (c) 2016 Ultra Community (http://www.ultracommunity.com)
 */


namespace InvisibleReCaptcha\MchLib\Notices;

use InvisibleReCaptcha\MchLib\Utils\MchUtils;

abstract class MchAdminNotice
{
	CONST NOTICE_TYPE_SUCCESS = 1;
	CONST NOTICE_TYPE_INFO    = 2;
	CONST NOTICE_TYPE_WARNING = 3;
	CONST NOTICE_TYPE_DANGER  = 4;


	private $noticeType = 4;
	private $noticeKey  = null;
	private $noticeMessage = null;
	private $isDismissible = true;

	public function __construct($noticeKey, $noticeType, $noticeMessage = null)
	{
		$this->noticeKey     = $noticeKey;
		$this->noticeType    = $noticeType;
		$this->noticeMessage = $noticeMessage;

		$this->isDismissible = true;


		$arrAllNotices = $this->getAllNotices();
		if(empty($arrAllNotices)){
			update_site_option($this->getNoticesOptionKey(), array());
		}

	}

	private function getAllNotices()
	{
		return (array)get_site_option($this->getNoticesOptionKey(), array());
	}

	private function getNoticesOptionKey()
	{
		return strtolower( MchUtils::replaceNonAlphaNumericCharacters(get_class($this)) );
	}


	public function deleteAllNotices()
	{
		return delete_site_option($this->getNoticesOptionKey());
	}

	public function dismiss()
	{
		$arrAllNotices = $this->getAllNotices();
		$arrAllNotices[$this->getNoticeKey()] = true;

		update_site_option($this->getNoticesOptionKey(), $arrAllNotices);

		wp_send_json_success(array('key'=>$this->getNoticeKey()));
	}

	public function reEnable()
	{
		$arrAllNotices = $this->getAllNotices();
		unset($arrAllNotices[$this->getNoticeKey()]);

		update_site_option($this->getNoticesOptionKey(), $arrAllNotices);

		wp_send_json_success(array('key'=>$this->getNoticeKey()));
	}


	public function isDismissed()
	{
		$arrAllNotices = $this->getAllNotices();
		return isset($arrAllNotices[$this->getNoticeKey()]);
	}

	public function getFormattedNoticeKey()
	{
		return strtolower( MchUtils::replaceNonAlphaNumericCharacters($this->noticeKey) );
	}

	public function getNoticeKey()
	{
		return $this->noticeKey;
	}

	public function setIsDismissible($isDismissible)
	{
		$this->isDismissible = (!!$isDismissible);
	}


	public function isDismissible()
	{
		return $this->isDismissible;
	}


	public function setMessage($noticeMessage)
	{
		$this->noticeMessage = $noticeMessage;
	}

	public function showNotice()
	{

		if(empty($this->noticeMessage))
			return;

		$holderClass = 'mch-admin-notice update notice';

		$holderClass .= $this->isDismissible() ? ' is-dismissible' : '';

		$htmlCode  = '<div id="' . $this->getFormattedNoticeKey() . '" class="' . $holderClass . '" ' . 'style="border-left-color:' . $this->getHolderBorderColor() . ';' . '" >';
		//$htmlCode .= '<p>';
		$htmlCode .= $this->noticeMessage;
		//$htmlCode .= '</p>';
		$htmlCode .= '</div>';

		echo $htmlCode;

	}


	private function getHolderBorderColor()
	{
		switch((int)$this->noticeType)
		{
			case self::NOTICE_TYPE_SUCCESS : return '#7ad03a';
			case self::NOTICE_TYPE_INFO    : return '#428bca';
			case self::NOTICE_TYPE_WARNING : return '#ffba00';
			case self::NOTICE_TYPE_DANGER  : return '#ce4844';
		}

		return '#777';
	}

}