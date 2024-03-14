<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Marketing\Subscribers\AudienceList;

class AudienceList {
	public const TYPE_OPTOUT = 'opt_out';
	public const TYPE_OPTIN  = 'opt_in';

	public const STATUS_PUBLISH = 'publish';
	public const STATUS_DRAFT   = 'draft';

	/** @var int|null */
	private $id;

	/** @var string */
	private $type = self::TYPE_OPTIN;

	/** @var string */
	private $status = self::STATUS_DRAFT;

	/** @var string|null */
	private $name;

	/** @var NewsletterForm */
	private $newsletter_form;

	/** @var bool */
	private $checkout_available = true;

	/** @var string */
	private $checkout_label;

	/** @var string */
	private $checkout_description;

	/** @var string */
	private $language;

	public function __construct( int $id = null ) {
		$this->id              = $id;
		$this->newsletter_form = new NewsletterForm();
	}

	/**
	 * @deprecated 3.0.0
	 * @see        get_checkout_label
	 * @codeCoverageIgnore
	 */
	public function get_checkbox_label() {
		return $this->get_checkout_label();
	}

	public function get_checkout_label(): string {
		return $this->checkout_label;
	}

	public function set_checkout_label( string $checkout_label ): void {
		$this->checkout_label = $checkout_label;
	}

	/**
	 * @deprecated 3.0.0
	 * @see        get_checkout_description
	 * @codeCoverageIgnore
	 */
	public function get_checkbox_description() {
		return $this->get_checkout_description();
	}

	public function get_checkout_description(): string {
		return $this->checkout_description;
	}

	public function set_checkout_description( string $checkout_description ): void {
		$this->checkout_description = $checkout_description;
	}

	public function get_id(): ?int {
		return $this->id;
	}

	/** @phpstan-assert-if-true !null $this->get_id() */
	public function exists(): bool {
		return ! empty( $this->id );
	}

	public function set_id( ?int $id ): void {
		$this->id = $id;
	}

	public function get_status(): string {
		return $this->status;
	}

	public function set_status( string $status ): void {
		$this->status = $status;
	}

	public function get_name(): ?string {
		return $this->name;
	}

	public function set_name( string $name ): void {
		$this->name = $name;
	}

	public function get_newsletter_form(): NewsletterForm {
		return $this->newsletter_form;
	}

	public function set_newsletter_form( NewsletterForm $newsletter_form ): void {
		$this->newsletter_form = $newsletter_form;
	}

	public function is_checkout_available(): bool {
		return $this->checkout_available;
	}

	public function set_checkout_available( bool $checkout_available ): void {
		$this->checkout_available = $checkout_available;
	}

	public function get_type(): string {
		return $this->type;
	}

	public function set_type( string $type ): void {
		if ( empty( $type ) ) {
			$type = self::TYPE_OPTIN;
		}

		if ( ! in_array( $type, [ self::TYPE_OPTIN, self::TYPE_OPTOUT ], true ) ) {
			throw new \InvalidArgumentException(
				sprintf(
					'List must be either `%s` or `%s`. Type `%s` is not allowed',
					self::TYPE_OPTIN,
					self::TYPE_OPTOUT,
					$type
				)
			);
		}
		$this->type = $type;
	}

	public function get_language(): string {
		return $this->language ?? get_bloginfo( 'language' );
	}

	public function set_language( string $language ): void {
		$this->language = $language;
	}

}
