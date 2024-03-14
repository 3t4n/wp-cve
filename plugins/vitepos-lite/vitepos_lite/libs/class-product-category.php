<?php
/**
 * Its pos product-category model
 *
 * @since: 21/09/2021
 * @author: Sarwar Hasan
 * @version 1.0.0
 * @package VitePos_Lite\Libs
 */

namespace VitePos_Lite\Libs;

/**
 * Class Product Category
 *
 * @package VitePos_Lite\Libs
 */
class Product_Category {

	/**
	 * Its property id
	 *
	 * @var int
	 */
	public $id;
	/**
	 * Its property name
	 *
	 * @var string
	 */
	public $name;
	/**
	 * Its property slug
	 *
	 * @var string
	 */
	public $slug;
	/**
	 * Its property image
	 *
	 * @var string
	 */
	public $image;
	/**
	 * Its property parent_id
	 *
	 * @var int
	 */
	public $parent_id;  /**
						 * Its property term_taxonomy_id
						 *
						 * @var int
						 */
	public $term_taxonomy_id;
	/**
	 * Its property taxonomy
	 *
	 * @var string
	 */
	public $taxonomy;
	/**
	 * Its property product_count
	 *
	 * @var int
	 */
	public $product_count;
}
