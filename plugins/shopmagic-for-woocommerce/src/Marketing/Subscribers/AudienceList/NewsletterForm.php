<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Marketing\Subscribers\AudienceList;

/**
 * Embedded entity for AudienceList class. Represents shortcode form configuration.
 *
 * @see AudienceList
 */
class NewsletterForm {

	/** @var bool */
	private $double_opt_in = false;

	/** @var bool */
	private $show_name = true;

	/** @var bool */
	private $show_labels = true;

	/** @var string */
	private $agreement = '';

	public function to_array(): array {
		return [
			'name'         => $this->is_show_name(),
			'labels'       => $this->is_show_labels(),
			'double_optin' => $this->is_double_opt_in(),
			'agreement'    => $this->get_agreement(),
		];
	}

	public function is_show_name(): bool {
		return $this->show_name;
	}

	public function set_show_name( bool $show_name ): void {
		$this->show_name = $show_name;
	}

	public function is_show_labels(): bool {
		return $this->show_labels;
	}

	public function set_show_labels( bool $show_labels ): void {
		$this->show_labels = $show_labels;
	}

	public function is_double_opt_in(): bool {
		return $this->double_opt_in;
	}

	public function set_double_opt_in( bool $double_opt_in ): void {
		$this->double_opt_in = $double_opt_in;
	}

	public function get_agreement(): string {
		return $this->agreement;
	}

	public function set_agreement( string $agreement ): void {
		$this->agreement = $agreement;
	}

}
