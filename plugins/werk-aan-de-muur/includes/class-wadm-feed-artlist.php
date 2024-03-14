<?php

class Wadm_Feed_Artlist extends Wadm_Feed_Paged
{
	/**
	 * Wadm_Feed_Artlist constructor. Make sure to add url parameters in the right order,
	 * call parent constructor after adding action and userId parameters.
	 *
	 * @param $userId
	 */
	public function __construct($userId)
	{
		$this->setPageName('wadmArtlistPage');

		$this->addUrlParameter('action', 'artlist');
		$this->addUrlParameter('userId', $userId);

		parent::__construct();
	}
}