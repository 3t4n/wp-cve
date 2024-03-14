<?php
class WPFingerprint_Hash{

	public function check_file_checksum( $path, $checksums ) {
			$sha256 = $this->get_sha256( $this->get_absolute_path( $path ) );
			return in_array( $sha256, (array) $checksums['sha256'], true );
	}

	public function get_sha256( $filepath ) {
		return hash_file( 'sha256', $filepath );
	}

	public function get_sha256_fragment( $fragment ) {
		return hash('sha256', $fragment );
	}

	public function get_absolute_path( $path ) {
		return WP_PLUGIN_DIR . '/' . $path;
	}

}
