<?php

class Wadm_Feed_Paged extends Wadm_Feed_Abstract
{
	/**
	 * @var int $_perPage Default artworks shown per page.
	 */
	protected $_perPage = 12;

	/**
	 * @var int $_page Current page
	 */
	protected $_page = 1;

	/**
	 * @var int $_columns Artworks per column
	 */
	protected $_columns = 3;

	/**
	 * @var string $_pageName Name of query parameter used for paging
	 */
	protected $_pageName = 'wadmPage';

	/**
	 * @var string $_order
	 */
	protected $_order = null;


	/**
	 * Wadm_Feed_Paged constructor. Sets some defaults
	 */
	public function __construct()
	{
		$this->setPerPage(); // Set default page limit
		$this->setPage();
	}

	/**
	 * Set amount of artworks to show per page
	 *
	 * @param $perPage
	 */
	public function setPerPage($perPage = null)
	{
		if ($perPage)
			$this->_perPage = (int)$perPage;

		$this->addUrlParameter('perPage', $this->_perPage);
	}

	/**
	 * Set the current page. Defaults to 1
	 */
	public function setPage()
	{
		if (isset($_GET[$this->_pageName]))
			$this->_page = (int)$_GET[$this->_pageName];

		$this->addUrlParameter('page', $this->_page);
	}

	public function setPageName($pageName)
	{
		$this->_pageName = $pageName;
	}

	/**
	 * Set no. of columns
	 *
	 * @param $columns
	 */
	public function setColumns($columns)
	{
		$this->_columns = (int)$columns;
	}

	/**
	 * Validate and set artwork order
	 *
	 * @param $order
	 * @return bool
	 */
	public function setOrder($order)
	{
		$allowed = array(
			'date_asc',
			'date_desc',
			'title_asc',
			'title_desc',
			'rating',
		);

		if (!in_array($order, $allowed))
			return false;

		$this->_order = $order;
		$this->addUrlParameter('order', $this->_order);
	}

	/**
	 * Use feed data to generate HTML for artlist
	 *
	 * @param bool $html
	 * @return bool|string
	 */
	public function getHtml($html = false)
	{
		$data = $this->getData();

		if (!$data)
			return false;

		$output = '<ul class="wadm-artworks per-column-' . $this->_columns . '">';

		foreach ($data->artworks as $i => $artwork)
		{
			if ($i % $this->_columns === 0 && $i !== 0)
				$output .= '</ul><ul class="wadm-artworks per-column-' . $this->_columns . '">';

			$output .= $this->_artworkHtml($artwork);
		}

		$output .= '</ul>';

		return parent::getHtml($output);
	}

	/**
	 * Helper function to generate HTML per artwork
	 *
	 * @param $artwork
	 * @return string
	 */
	protected function _artworkHtml($artwork)
	{
		return '
			<li class="wadm-artwork">
				<div class="wadm-artwork-art">
					<a href="' . $artwork->link . '" class="wadm-artwork-link" title="' . htmlentities($artwork->title) . '" target="_blank" rel="nofollow">
						<img src="' . $this->getImageUrl($artwork, '500x500') . '" alt="' . htmlentities($artwork->title) . '" />
					</a>
				</div>

				<div class="wadm-artwork-meta">
					<div class="wadm-artwork-title">
						<a href="' . $artwork->link . '" class="wadm-artwork-link" title="' . htmlentities($artwork->title) . '" target="_blank" rel="nofollow">
							<span class="primary-item">' . $artwork->title . '</span>
							' . (isset($artwork->artist) ? '<span class="secondary-item">' . $artwork->artist . '</span>' : '') . '
						</a>
					</div>
					<p class="wadm-artwork-price">
						<a href="' . $artwork->link . '" class="wadm-artwork-link" title="' . htmlentities($artwork->title) . '" target="_blank" rel="nofollow">
							<span>' . $artwork->pricing[0] . '</span> ' . $artwork->medium . ' ' . $artwork->dimensions . '
						</a>
					</p>
				</div>
			</li>
		';
	}

	/**
	 * Use Wordpress' built in pagination helper to create pagination
	 */
	public function getPagination()
	{
		$data = $this->getData();

		if (!$data || !isset($data->stats))
			return false;

		$options = array(
			'base' => add_query_arg($this->_pageName, '%#%'),
			'format' => '?' . $this->_pageName . '=%#%',
			'total' => $data->stats->totalPages,
			'current' => $data->stats->currentPage,
		);

		$pagination = paginate_links($options);

		if (!$pagination)
			return false;

		return sprintf('<div class="wadm-pagination">%s</div>', $pagination);
	}
}