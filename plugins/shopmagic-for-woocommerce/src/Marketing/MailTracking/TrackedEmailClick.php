<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Marketing\MailTracking;

class TrackedEmailClick {

	/** @var int|null */
	private $id;

	/** @var TrackedEmail */
	private $tracked_email;

	/** @var string */
	private $original_uri;

	/** @var \DateTimeInterface */
	private $clicked_at;

	public function mark_click( string $uri ): void {
		$this->original_uri = $uri;
		$this->clicked_at   = new \DateTime( 'now', wp_timezone() );
	}

	public function get_clicked_at(): \DateTimeInterface {
		return $this->clicked_at;
	}

	public function set_clicked_at( \DateTimeInterface $clicked_at ): void {
		$this->clicked_at = $clicked_at;
	}

	public function set_last_inserted_id( int $id ): void {
		$this->id = $id;
	}

	public function get_id(): ?int {
		return $this->id;
	}

	public function get_tracked_email(): TrackedEmail {
		return $this->tracked_email;
	}

	public function set_tracked_email( TrackedEmail $tracked_email ): void {
		$this->tracked_email = $tracked_email;
	}

	public function get_original_uri(): string {
		return $this->original_uri;
	}

	public function set_original_uri( string $uri ): void {
		$this->original_uri = $uri;
	}
}
