<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Admin;

/**
 * Abstraction for Vue-generated manifest file.
 */
class Manifest {

	/** @var array */
	private $entries;

	/** @var string */
	private $assets_root;

	public static function from_file( string $file_path, string $uri_path ): self {
		$file_get_contents = file_get_contents( $file_path );
		$manifest_array    = json_decode( $file_get_contents, true );

		return new Manifest( $manifest_array, $uri_path );
	}


	public function __construct( array $entries, string $content_root = '' ) {
		$this->entries     = $entries;
		$this->assets_root = $content_root;
	}

	public function get( string $entry ): string {
		return $this->assets_root . $this->entries[ $entry ]['file'];
	}

	public function get_css_for( string $js_entry ): string {
		return $this->assets_root . $this->entries[ $js_entry ]['css'][0];
	}

	public function has( string $entry ): bool {
		return isset( $this->entries[ $entry ] );
	}

}
