<?php
/**
 * Class ShippingNotice
 */

namespace Octolize\Shipping\Notices\Model;

/**
 * Shipping Notice Model.
 */
class ShippingNotice {

	/**
	 * @var int
	 */
	private $id;

	/**
	 * @var bool
	 */
	private $enabled;

	/**
	 * @var string
	 */
	private $title;

	/**
	 * @var Region[]
	 */
	private $regions;

	/**
	 * @var string
	 */
	private $message;

	/**
	 * @var string[]
	 */
	private $locations;

	/**
	 * @var string[]
	 */
	private $post_codes;

	/**
	 * @param int      $id         .
	 * @param bool     $enabled    .
	 * @param string   $title      .
	 * @param Region[] $regions    .
	 * @param string   $message    .
	 * @param string[] $locations  .
	 * @param string[] $post_codes .
	 */
	public function __construct(
		int $id,
		bool $enabled,
		string $title,
		array $regions,
		string $message,
		array $locations,
		array $post_codes
	) {
		$this->id         = $id;
		$this->enabled    = $enabled;
		$this->title      = $title;
		$this->regions    = $regions;
		$this->message    = $message;
		$this->locations  = $locations;
		$this->post_codes = $post_codes;
	}

	/**
	 * @return int
	 */
	public function get_id(): int {
		return $this->id;
	}

	/**
	 * @return bool
	 */
	public function is_enabled(): bool {
		return $this->enabled;
	}

	/**
	 * @return string
	 */
	public function get_title(): string {
		return $this->title;
	}

	/**
	 * @return Region[]
	 */
	public function get_regions(): array {
		return $this->regions;
	}

	/**
	 * @return string
	 */
	public function get_message(): string {
		return $this->message;
	}

	/**
	 * @return string[]
	 */
	public function get_locations(): array {
		return $this->locations;
	}

	/**
	 * @return string[]
	 */
	public function get_post_codes(): array {
		return $this->post_codes;
	}
}
