<?php

namespace WPDesk\FlexibleWishlist\Model;

/**
 * Object of wishlist user, their owner.
 */
class User {

	/**
	 * @var int|null
	 */
	private $id;

	/**
	 * @var string
	 */
	private $user_token;

	/**
	 * @var int|null
	 */
	private $user_id;

	/**
	 * @var \DateTime
	 */
	private $created_at;

	/**
	 * @var \DateTime
	 */
	private $updated_at;

	/**
	 * @var Wishlist[]
	 */
	private $items = [];

	/**
	 * @param int|null       $id         .
	 * @param string         $user_token .
	 * @param int|null       $user_id    .
	 * @param \DateTime      $created_at .
	 * @param \DateTime|null $updated_at .
	 * @param Wishlist[]     $items      .
	 */
	public function __construct(
		int $id = null,
		string $user_token,
		int $user_id = null,
		\DateTime $created_at,
		\DateTime $updated_at = null,
		array $items = []
	) {
		$this->id         = $id;
		$this->user_token = $user_token;
		$this->user_id    = $user_id;
		$this->created_at = $created_at;
		$this->updated_at = $updated_at ?: $created_at;
		$this->items      = $items;
	}

	/**
	 * @return int|null
	 */
	public function get_id() {
		return $this->id;
	}

	public function get_user_token(): string {
		return $this->user_token;
	}

	public function set_user_token( string $user_token ): self {
		$this->user_token = $user_token;
		return $this;
	}

	/**
	 * @return int|null
	 */
	public function get_user_id() {
		return $this->user_id;
	}

	public function set_user_id( int $user_id = null ): self {
		$this->user_id = $user_id;
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

	/**
	 * @return Wishlist[]
	 */
	public function get_wishlists(): array {
		return $this->items;
	}

	public function add_wishlist( Wishlist $wishlist ): self {
		$this->items[] = $wishlist;
		return $this;
	}
}
