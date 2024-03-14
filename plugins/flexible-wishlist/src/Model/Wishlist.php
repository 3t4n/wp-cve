<?php

namespace WPDesk\FlexibleWishlist\Model;

/**
 * A single wishlist object.
 */
class Wishlist {

	/**
	 * @var int|null
	 */
	private $id;

	/**
	 * @var int|null
	 */
	private $user_id;

	/**
	 * @var int|null
	 */
	private $wp_user_id;

	/**
	 * @var string
	 */
	private $list_token;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var bool
	 */
	private $is_default;

	/**
	 * @var \DateTime
	 */
	private $created_at;

	/**
	 * @var \DateTime
	 */
	private $updated_at;

	/**
	 * @var WishlistItem[]
	 */
	private $items = [];

	/**
	 * @param int|null       $id         .
	 * @param int            $user_id    .
	 * @param int|null       $wp_user_id .
	 * @param string         $list_token .
	 * @param string         $name       .
	 * @param bool           $is_default .
	 * @param \DateTime      $created_at .
	 * @param \DateTime|null $updated_at .
	 * @param WishlistItem[] $items      .
	 */
	public function __construct(
		int $id = null,
		int $user_id = null,
		int $wp_user_id = null,
		string $list_token,
		string $name,
		bool $is_default,
		\DateTime $created_at,
		\DateTime $updated_at = null,
		array $items = []
	) {
		$this->id         = $id;
		$this->user_id    = $user_id;
		$this->wp_user_id = $wp_user_id;
		$this->list_token = $list_token;
		$this->name       = $name;
		$this->is_default = $is_default;
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

	public function get_list_token(): string {
		return $this->list_token;
	}

	/**
	 * @return int|null
	 */
	public function get_user_id() {
		return $this->user_id;
	}

	public function set_user_id( int $user_id ): self {
		$this->user_id = $user_id;
		return $this;
	}

	/**
	 * @return int|null
	 */
	public function get_wp_user_id() {
		return $this->wp_user_id;
	}

	public function get_name(): string {
		return $this->name;
	}

	public function set_name( string $name ): self {
		$this->name = $name;
		return $this;
	}

	public function get_default_status(): bool {
		return $this->is_default;
	}

	public function set_default_status( bool $status ): self {
		$this->is_default = $status;
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
	 * @return WishlistItem[]
	 */
	public function get_items(): array {
		return $this->items;
	}

	/**
	 * @param WishlistItem[] $items .
	 *
	 * @return self
	 */
	public function set_items( array $items ): self {
		$this->items = $items;
		return $this;
	}
}
