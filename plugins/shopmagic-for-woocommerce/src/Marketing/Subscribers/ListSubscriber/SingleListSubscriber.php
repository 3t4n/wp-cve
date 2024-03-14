<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Marketing\Subscribers\ListSubscriber;

class SingleListSubscriber {

	/** @var int|null */
	private $id;

	/** @var int */
	private $list_id;

	/** @var string */
	private $email;

	/** @var bool */
	private $type;

	/** @var bool */
	private $active = true;

	/** @var \DateTimeImmutable */
	private $updated;

	/** @var \DateTimeImmutable */
	private $created;

	/** @var bool */
	private $changed = false;

	public function __construct() {
		$this->created = new \DateTimeImmutable();
		$this->updated = new \DateTimeImmutable();
	}

	public function get_id(): ?int {
		return $this->id;
	}

	public function set_id( ?int $id ): void {
		$this->id = $id;
	}

	public function get_email(): string {
		return $this->email;
	}

	public function set_email( string $email ): void {
		$this->email = $email;
	}

	public function get_type(): bool {
		return $this->type;
	}

	public function set_type( bool $type ): void {
		$this->type = $type;
	}

	public function is_active(): bool {
		return $this->active;
	}

	public function set_active( bool $active ): void {
		$this->changed = $this->active !== $active;
		$this->updated = new \DateTimeImmutable( 'now', wp_timezone() );
		$this->active  = $active;
	}

	public function get_list_id(): int {
		return $this->list_id;
	}

	public function set_list_id( int $list_id ): void {
		$this->list_id = $list_id;
	}

	public function get_updated(): \DateTimeImmutable {
		return $this->updated;
	}

	public function set_updated( $updated ): void {
		$this->updated = $updated;
	}

	public function get_created(): \DateTimeImmutable {
		return $this->created;
	}

	public function set_created( $created ): void {
		$this->created = $created;
	}

	/** @return mixed[] */
	public function get_changed_fields(): array {
		return [];
	}

	public function has_changed(): bool {
		return $this->id === 0 || $this->changed;
	}

	public function set_last_inserted_id( int $id ): void {
		$this->id = $id;
	}

}
