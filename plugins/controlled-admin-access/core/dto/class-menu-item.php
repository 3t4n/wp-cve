<?php

namespace WPRuby_CAA\Core\Dto;


class Menu_Item {

	const IGNORED_SLUGS = [
			'controlled-admin-access',
	];

	/** @var string */
	private $title;
	/** @var string */
	private $slug;
	/** @var string */
	private $menu_id;
	/** @var array<Menu_Item> */
	private $sub_items = [];


	/**
	 * @param $menu_id
	 * @param $title
	 * @param $slug
	 *
	 * @return Menu_Item
	 */
	public static function make($menu_id, $title, $slug)
	{
		$menuItem = new self();

		return $menuItem
			->setMenuId($menu_id)
		    ->setTitle($title)
			->setSlug($slug);
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param string $title
	 *
	 * @return Menu_Item
	 */
	public function setTitle( $title ) {
		$this->title = $this->prepare_title($title);

		return $this;
	}

	/**
	 * @return string
	 */
	public function getSlug() {
		return $this->slug;
	}

	/**
	 * @param string $slug
	 *
	 * @return Menu_Item
	 */
	public function setSlug( $slug ) {
		$this->slug = $slug;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getMenuId() {
		return $this->menu_id;
	}

	/**
	 * @param string $menu_id
	 *
	 * @return Menu_Item
	 */
	public function setMenuId( $menu_id ) {
		$this->menu_id = $menu_id;

		return $this;
	}

	/**
	 * @return Menu_Item[]
	 */
	public function getSubItems() {
		return $this->sub_items;
	}

	/**
	 * @param Menu_Item $sub_item
	 *
	 * @return Menu_Item
	 */
	public function addSubItem( $sub_item )
	{
		$this->sub_items[] = $sub_item;

		return $this;
	}

	/**
	 * @param string $title
	 *
	 * @return string
	 */
	private function prepare_title( $title )
	{
        if ($title === null) {
            return '';
        }

		$title = strip_tags($title);
		$title = explode(' ', $title);
		if (sizeof($title) == 1) {
			return $title[0];
		}

		$prepared_title = '';

		foreach($title as $part){
			if(!is_numeric($part)){
				$prepared_title .= $part.' ';
			}
		}

		// exceptions
		$prepared_title = str_replace('Comments Comments in moderation', 'Comments', $prepared_title); # check later if site language matters.

		return $prepared_title;
	}

	public function shouldBeIgnored()
	{
		return in_array($this->getSlug(), self::IGNORED_SLUGS);
	}

	public function toArray()
	{
		$array = [
			'menu_id' => $this->getMenuId(),
			'title' => $this->getTitle(),
			'slug' => $this->getSlug(),
		];

		if (count($this->getSubItems()) > 0) {
			$array['sub_items'] = array_map(function ($subItem) {return $subItem->toArray();}, $this->getSubItems());
		}

		return $array;
	}

}
