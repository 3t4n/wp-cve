<?php

class Wadm_Feed_Album extends Wadm_Feed_Paged
{
	/**
	 * Wadm_Feed_Albums constructor. Make sure to add url parameters in the right order,
	 * call parent constructor after adding action and albumId parameters.
	 *
	 * @param $albumId
	 */
	public function __construct($albumId)
	{
		$this->setPageName('wadmAlbumPage' . $albumId);

		$this->addUrlParameter('action', 'album');
		$this->addUrlParameter('userId', $albumId);

		parent::__construct();
	}
}