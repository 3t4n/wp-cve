<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Components\Mailer;

use WPDesk\ShopMagic\Admin\Settings\GeneralSettings;

/**
 * Email message composed as object.
 */
class Email {

	/** @var string */
	public $from;

	/** @var string */
	public $from_name;

	/** @var string */
	public $to;

	/** @var string */
	public $subject;

	/** @var string */
	public $message;

	/** @var string[] */
	public $attachments = [];

	/** @var string[] */
	private $headers = [];

	public function __construct() {
		$this->from      = GeneralSettings::get_option( 'shopmagic_email_from_address' ) ?: get_option( 'woocommerce_email_from_address' );
		$this->from_name = GeneralSettings::get_option( 'shopmagic_email_from_name' ) ?: get_option( 'woocommerce_email_from_name' );
	}

	public function to( string $to ): self {
		$self     = clone $this;
		$self->to = $to;

		return $self;
	}

	public function bcc( string $bcc ): self {
		return $this->set_header( 'Bcc', $bcc );
	}

	public function set_header( string $header, string $value ): self {
		$self                     = clone $this;
		$self->headers[ $header ] = $value;

		return $self;
	}

	/** @return string[] */
	public function get_headers(): array {
		$result = [];
		foreach ( $this->headers as $header => $value ) {
			$result[] = "$header: $value";
		}

		return $result;
	}

	public function subject( string $subject ): self {
		$self          = clone $this;
		$self->subject = $subject;

		return $self;
	}

	public function message( string $message ): self {
		$self          = clone $this;
		$self->message = $message;

		return $self;
	}

	public function content_type( string $content_type ): self {
		return $this->set_header( 'Content-Type', $content_type );
	}

	/**
	 * @param string[]|string $attachment
	 */
	public function attach( $attachment ): self {
		if ( is_array( $attachment ) ) {
			foreach ( $attachment as $item ) {
				$this->attach( $item );
			}
		} else {
			$this->attachments[] = $attachment;
		}

		return $this;
	}

	public function is_html(): bool {
		return $this->get_header( 'Content-Type' ) === 'text/html';
	}

	public function get_header( string $header ): string {
		return $this->headers[ $header ] ?? '';
	}

}
