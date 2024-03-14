<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Automation;

use WPDesk\ShopMagic\Workflow\Action\Action;
use WPDesk\ShopMagic\Workflow\Event\Event;
use WPDesk\ShopMagic\Workflow\Filter\FilterLogic;

class Automation implements \JsonSerializable {

	public const STATUS_DRAFT   = 'draft';
	public const STATUS_PUBLISH = 'publish';
	public const STATUS_TRASH   = 'trash';

	/** @var null|int */
	private $id;

	/** @var int|null */
	private $parent;

	/** @var Event */
	private $event;

	/** @var array */
	private $actions = [];

	/** @var AutomationFiltersGroup */
	private $filters;

	/** @var string|null */
	private $name;

	/** @var string|null */
	private $description;

	/** @var 'draft'|'publish'|'trash' */
	private $status = self::STATUS_DRAFT;

	/** @var string|null */
	private $language;

	/** @var bool */
	private $recipe = false;

	public function __construct( ?int $id = null ) {
		$this->id = $id;
	}

	/** @phpstan-assert-if-true !null $this->get_id() */
	public function exists(): bool {
		return ! empty( $this->id );
	}

	public function get_id(): ?int {
		return $this->id;
	}

	public function set_id( ?int $id ): void {
		$this->id = $id;
	}

	public function set_parent( ?int $parent ): void {
		if ( $this->id === $parent ) {
			throw new \LogicException(
				esc_html__(
					'Trying to set parent automation with the same ID as current automation. Automation cannot be its own parent!',
					'shopmagic-for-woocommerce'
				)
			);
		}
		$this->parent = $parent;
	}

	public function get_parent(): ?int {
		return $this->parent;
	}

	public function has_parent(): bool {
		return $this->parent !== null;
	}

	public function set_description( string $description ): void {
		$this->description = $description;
	}

	public function get_description(): ?string {
		return $this->description;
	}

	public function set_event( Event $event ): void {
		$this->event = $event;
	}

	public function get_event(): Event {
		return $this->event;
	}

	public function set_actions( array $actions ): void {
		$this->actions = $actions;
	}

	public function get_actions(): array {
		return $this->actions;
	}

	public function set_filters_group( AutomationFiltersGroup $filters ): void {
		$this->filters = $filters;
	}

	/**
	 * @deprecated 3.0.9 Use get_filters_group() instead.
	 */
	public function get_filters(): FilterLogic {
		return $this->filters;
	}

	public function get_filters_group(): AutomationFiltersGroup {
		return $this->filters;
	}

	public function get_name(): string {
		return $this->name ?? '';
	}

	public function set_name( string $name ): void {
		$this->name = $name;
	}

	public function has_action( int $index ): bool {
		return isset( $this->actions[ $index ] );
	}

	public function get_action( int $index ): Action {
		return $this->actions[ $index ];
	}

	public function get_status(): string {
		return $this->status;
	}

	public function set_status( string $status ): void {
		$this->status = $status;
	}

	public function get_language(): ?string {
		return $this->language;
	}

	/** @phpstan-assert-if-true !null $this->get_language() */
	public function has_language(): bool {
		return isset( $this->language );
	}

	public function set_language( string $language ): void {
		$this->language = $language;
	}

	public function is_recipe(): bool {
		return $this->recipe;
	}

	public function set_recipe( bool $recipe ): void {
		$this->recipe = $recipe;
	}

	public function jsonSerialize(): array {
		return [
			'id' => $this->get_id(),
		];
	}
}
