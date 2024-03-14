<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Marketing\MailTracking;

use ShopMagicVendor\Ramsey\Uuid\Uuid;
use WPDesk\ShopMagic\Components\Collections\ArrayCollection;
use WPDesk\ShopMagic\Components\Collections\Collection;
use WPDesk\ShopMagic\Customer\Customer;

class TrackedEmail {

	/** @var int|null */
	private $id;

	/** @var string|null */
	private $message_id;

	/** @var int */
	private $automation_id;

	/** @var Customer|null */
	private $customer;

	/** @var string */
	private $recipient_email;

	/** @var \DateTimeInterface */
	private $dispatched_at;

	/** @var \DateTimeInterface|null */
	private $opened_at;

	/** @var \DateTimeInterface|null */
	private $clicked_at;

	/** @var \WPDesk\ShopMagic\Components\Collections\Collection<string, TrackedEmailClick> */
	private $clicks;

	public function __construct() {
		$this->clicks = new ArrayCollection();
	}

	public function get_customer(): ?Customer {
		return $this->customer;
	}

	public function set_customer( ?Customer $customer ): void {
		$this->customer = $customer;
	}

	public function mark_opened(): self {
		$this->opened_at = new \DateTime( 'now', wp_timezone() );

		return $this;
	}

	public function mark_dispatched(): self {
		$this->dispatched_at = new \DateTime( 'now', wp_timezone() );

		return $this;
	}

	public function get_id(): ?int {
		return $this->id;
	}

	public function set_id( ?int $id ): void {
		$this->id = $id;
	}

	public function append_click( TrackedEmailClick $click ): void {
		if ( count( $this->clicks ) === 0 ) {
			$this->clicked_at = $click->get_clicked_at();
		}
		$this->clicks[] = $click;
	}

	public function get_clicked_at(): ?\DateTimeInterface {
		return $this->clicked_at;
	}

	public function set_clicked_at( \DateTimeInterface $clicked_at ): void {
		$this->clicked_at = $clicked_at;
	}

	public function get_clicks(): Collection {
		return $this->clicks;
	}

	public function set_clicks( $clicks ): void {
		$this->clicks = $clicks;
	}

	public function is_opened(): bool {
		return ! is_null( $this->opened_at );
	}

	public function with_fresh_uuid(): self {
		$self             = clone $this;
		$self->message_id = Uuid::uuid4()->toString();

		return $self;
	}

	public function get_message_id(): ?string {
		return $this->message_id;
	}

	public function set_message_id( ?string $message_id ): void {
		$this->message_id = $message_id;
	}

	public function get_automation_id(): int {
		return $this->automation_id;
	}

	public function set_automation_id( int $automation_id ): void {
		$this->automation_id = $automation_id;
	}

	public function get_recipient_email(): string {
		return $this->recipient_email;
	}

	public function set_recipient_email( string $recipient_email ): void {
		$this->recipient_email = $recipient_email;
	}

	public function get_dispatched_at(): \DateTimeInterface {
		return $this->dispatched_at;
	}

	public function set_dispatched_at( \DateTimeInterface $dispatched_at ): void {
		$this->dispatched_at = $dispatched_at;
	}

	public function get_opened_at(): ?\DateTimeInterface {
		return $this->opened_at;
	}

	public function set_opened_at( \DateTimeInterface $opened_at ): void {
		$this->opened_at = $opened_at;
	}
}
