<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Workflow\Outcome\Meta;

/**
 * Representation of Outcome Logs single entry.
 */
final class OutcomeMeta {

	/** @var int */
	private $id;

	/** @var \DateTimeInterface */
	private $created_date;

	/** @var string */
	private $note;

	/** @var string[] */
	private $context = [];

	/** @var string */
	private $execution_id;

	/**
	 * @param string[]           $context
	 */
	public function __construct(
		string $note = '',
		array $context = []
	) {
		$this->note         = $note;
		$this->context      = $context;
		$this->created_date = new \DateTimeImmutable();
	}

	public function get_execution_id(): string {
		return $this->execution_id;
	}

	public function set_execution_id( string $execution_id ): void {
		$this->execution_id = $execution_id;
	}

	public function has_changed(): bool {
		return false;
	}

	public function set_last_inserted_id( int $id ): void {
		$this->id = $id;
	}

	public function get_id(): ?int {
		return $this->id;
	}

	public function set_id( ?int $id ): void {
		$this->id = $id;
	}

	public function get_created_date(): \DateTimeInterface {
		return $this->created_date;
	}

	public function set_created_date( \DateTimeInterface $created_date ): void {
		$this->created_date = $created_date;
	}

	public function get_note(): string {
		return $this->note;
	}

	public function set_note( string $note ): void {
		$this->note = $note;
	}

	/** @return string[] */
	public function get_context(): array {
		return $this->context;
	}

	/**
	 * @param string[] $context
	 */
	public function set_context( array $context ): void {
		$this->context = $context;
	}

}
