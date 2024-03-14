<?php

namespace WPDesk\FlexibleWishlist\Model;

/**
 * Product or idea on the wishlist.
 */
class WishlistItem {

	/**
	 * @var int|null
	 */
	private $id;

	/**
	 * @var int
	 */
	private $list_id;

	/**
	 * @var int|null
	 */
	private $product_id;

	/**
	 * @var string|null
	 */
	private $product_desc;

	/**
	 * @var int
	 */
	private $quantity;

	/**
	 * @var \DateTime
	 */
	private $created_at;

	/**
	 * @var \DateTime
	 */
	private $updated_at;

	/**
	 * @param int|null       $id           .
	 * @param int            $list_id      .
	 * @param int|null       $product_id   .
	 * @param string|null    $product_desc .
	 * @param int            $quantity     .
	 * @param \DateTime      $created_at   .
	 * @param \DateTime|null $updated_at   .
	 */
	public function __construct(
		int $id = null,
		int $list_id,
		int $product_id = null,
		string $product_desc = null,
		int $quantity,
		\DateTime $created_at,
		\DateTime $updated_at = null
	) {
		$this->id           = $id;
		$this->list_id      = $list_id;
		$this->product_id   = $product_id;
		$this->product_desc = $product_desc;
		$this->quantity     = $quantity;
		$this->created_at   = $created_at;
		$this->updated_at   = $updated_at ?: $created_at;
	}

	/**
	 * @return int|null
	 */
	public function get_id() {
		return $this->id;
	}

	public function set_id( int $id ): self {
		$this->id = $id;
		return $this;
	}

	public function get_list_id(): int {
		return $this->list_id;
	}

	/**
	 * @return int|null
	 */
	public function get_product_id() {
		return $this->product_id;
	}

	/**
	 * @return string|null
	 */
	public function get_product_desc() {
		return $this->product_desc;
	}

	public function get_quantity(): int {
		return $this->quantity;
	}

	public function set_quantity( int $quantity ): self {
		$this->quantity = $quantity;
		return $this;
	}

	public function get_created_at(): \DateTime {
		return $this->created_at;
	}

	public function get_updated_at(): \DateTime {
		return $this->updated_at;
	}

	public function set_updated_at( \DateTime $date ): self {
		$this->updated_at = $date;
		return $this;
	}
}
