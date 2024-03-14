<?php

/**
 * This class is used to manage page protection
 *
 */

class MemberSpace_Validator {

	public $sanitized_url_path;
	public $all_rules;
	public $exact_rules;
	public $wildcard_rules;

	public function __construct() {
		$this->sanitized_url_path = $this->get_sanitized_url_path();
		$this->all_rules = get_option( 'memberspace_rules', array() );
		$this->exact_rules = array();
		$this->wildcard_rules = array();
		$this->sort_rules();
	}

	public function is_current_path_protected() {
		$rule = $this->get_active_rule();
		return isset( $rule );
	}

	private function get_active_rule() {
		// See if we have a match for an exact rule
		foreach ( $this->exact_rules as $exact_rule ) {
			if ( $this->sanitized_url_path === $exact_rule->sanitized_path ) {
				return $exact_rule;
			}
		}

		// If we didn't find an exact rule, is there an applicable wildcard rule?
		$matching_rules = array_filter( $this->wildcard_rules, function( $rule ) {
			// Does the current path start with the wildcard rule's path?
			if ( strpos( $this->sanitized_url_path, $rule->sanitized_path ) === 0 ) return $rule;
		} );

		// If we found some matching rules, we want the most-specific one to apply
		if ( ! empty( $matching_rules ) ) {
			// Sort the $matching_rules by the length of the path
			usort( $matching_rules, function( $a, $b ) {
				return strlen( $b->sanitized_path ) <=> strlen( $a->sanitized_path );
			} );

			// Take the first, most-specific, rule
			return $matching_rules[0];
		}

		return;
	}

	private function get_sanitized_url_path() {
		$url = home_url( sanitize_url( $_SERVER['REQUEST_URI'] ) );
		$url_path = strtolower( parse_url( $url, PHP_URL_PATH ) );

		// Remove trailing slash if present
		$sanitized_url_path = rtrim( $url_path, "/" );
		return $sanitized_url_path;
	}

	private function sort_rules() {
		// Make sure we have some rules before we get started
		if ( empty( $this->all_rules ) ) return array();

		// Sanitize all the paths and sort into what type of rules they represent
		foreach ( $this->all_rules as $rule ) {
			$path = strtolower( $rule->path );

			// If the path ends with an asterisk, it's a wildcard rule
			if ( substr( $rule->path, -1 ) == '*' ) {
				$rule->sanitized_path = rtrim( $path, "*" );
				$this->wildcard_rules[] = $rule;
			} else {
				// Remove trailing slash
				$rule->sanitized_path = rtrim( $path, "/" );
				$this->exact_rules[] = $rule;
			}
		}
	}
}
