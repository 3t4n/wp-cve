<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Outcome;

use DateTimeImmutable;
use WPDesk\ShopMagic\Customer\Customer;
use WPDesk\ShopMagic\Customer\NullCustomer;
use WPDesk\ShopMagic\Workflow\Outcome\Meta\OutcomeMeta;

/**
 * Representation of singular Outcome.
 */
final class Outcome {
	/**
	 * @var string
	 */
	public const SLUG = 'shopmagic_single_outcome';

	/** @var int|null */
	private $id;

	/** @var string */
	private $execution_id;

	/** @var DateTimeImmutable */
	private $updated;

	/** @var bool */
	private $success = false;

	/** @var int */
	private $automation_id;

	/** @var int */
	private $action_index;

	/** @var string */
	private $action_name;

	/** @var Customer */
	private $customer;

	/** @var string */
	private $customer_email = '';

	/** @var bool */
	private $finished = false;

	/** @var DateTimeImmutable */
	private $created;

	/** @var string */
	private $automation_name;

	/** @var OutcomeMeta */
	private $note;

	public function __construct() {
		$this->created = new DateTimeImmutable();
		$this->updated = new DateTimeImmutable();
	}

	public function get_note(): ?OutcomeMeta {
		return $this->note;
	}

	public function set_note( OutcomeMeta $note ): void {
		$this->note = $note;
	}

	/**
	 * @param int|null $id
	 */
	public function set_id( ?int $id ): void {
		$this->id = $id;
	}

	/**
	 * @param string $execution_id
	 */
	public function set_execution_id( string $execution_id ): void {
		$this->execution_id = $execution_id;
	}

	/**
	 * @param DateTimeImmutable $updated
	 */
	public function set_updated( DateTimeImmutable $updated ): void {
		$this->updated = $updated;
	}

	/**
	 * @param bool $success
	 */
	public function set_success( bool $success ): void {
		$this->success = $success;
	}

	/**
	 * @param int $automation_id
	 */
	public function set_automation_id( int $automation_id ): void {
		$this->automation_id = $automation_id;
	}

	/**
	 * @param int $action_index
	 */
	public function set_action_index( int $action_index ): void {
		$this->action_index = $action_index;
	}

	/**
	 * @param string $action_name
	 */
	public function set_action_name( string $action_name ): void {
		$this->action_name = $action_name;
	}

	/**
	 * @param Customer $customer
	 */
	public function set_customer( Customer $customer ): void {
		$this->customer       = $customer;
		$this->customer_email = $customer->get_email();
	}

	/**
	 * @param bool $finished
	 */
	public function set_finished( bool $finished ): void {
		$this->finished = $finished;
	}

	/**
	 * @param DateTimeImmutable $created
	 */
	public function set_created( DateTimeImmutable $created ): void {
		$this->created = $created;
	}

	public function set_automation_name( string $automation_name ): void {
		$this->automation_name = $automation_name;
	}

	public function get_action_index(): int {
		return $this->action_index;
	}

	public function get_action_name(): string {
		return $this->action_name;
	}

	public function get_automation_id(): int {
		return $this->automation_id;
	}

	public function get_automation_name(): string {
		return $this->automation_name;
	}

	public function get_created(): DateTimeImmutable {
		return $this->created;
	}

	public function get_customer_email(): string {
		return $this->customer_email;
	}

	public function get_customer(): Customer {
		return $this->customer ?? new NullCustomer();
	}

	public function is_finished(): bool {
		return $this->finished;
	}

	public function get_execution_id(): string {
		return $this->execution_id;
	}

	public function get_success(): bool {
		return $this->success;
	}

	public function get_status(): string {
		if ( $this->success ) {
			return 'completed';
		}

		if ( $this->finished ) {
			return 'failed';
		}

		return 'unknown';
	}

	public function get_updated(): DateTimeImmutable {
		return $this->updated;
	}

	public function get_id(): ?int {
		return $this->id;
	}
}
