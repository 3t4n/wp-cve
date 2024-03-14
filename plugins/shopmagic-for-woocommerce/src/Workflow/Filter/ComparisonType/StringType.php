<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Workflow\Filter\ComparisonType;

final class StringType extends AbstractType {
	/**
	 * @var string
	 */
	private const IS = 'is';
	/**
	 * @var string
	 */
	private const CONTAINS = 'contains';

	/**
	 * @inheritDoc
	 */
	public function passed( $expected_value, string $compare_type, $actual_value ): bool {
		$actual_value   = (string) $actual_value;
		$expected_value = (string) $expected_value;

		// most comparisons are case insensitive.
		$actual_value_lowercase   = strtolower( $actual_value );
		$expected_value_lowercase = strtolower( $expected_value );

		switch ( $compare_type ) {
			case self::IS:
				return $actual_value_lowercase === $expected_value_lowercase;

			case 'is_not':
				return $actual_value_lowercase !== $expected_value_lowercase;

			case self::CONTAINS:
				return strpos( $actual_value_lowercase, $expected_value_lowercase ) !== false;

			case 'not_contains':
				return strpos( $actual_value_lowercase, $expected_value_lowercase ) === false;

			case 'starts_with':
				return $this->str_starts_with( $actual_value_lowercase, $expected_value_lowercase );

			case 'ends_with':
				return $this->str_ends_with( $actual_value_lowercase, $expected_value_lowercase );

			case 'blank':
				return empty( $actual_value );

			case 'not_blank':
				return ! empty( $actual_value );

			case 'regex':
				// Regex validation must not use case insensitive values.
				return $this->validate_string_regex( $actual_value, $expected_value );
		}

		return false;
	}

	/**
	 * Determine if a string starts with another string.
	 */
	private function str_starts_with( string $haystack, string $needle ): bool {
		return substr( $haystack, 0, \strlen( $needle ) ) === $needle;
	}

	/**
	 * Determine if a string ends with another string.
	 */
	private function str_ends_with( string $haystack, string $needle ): bool {
		$length = \strlen( $needle );

		if ( $length === 0 ) {
			return true;
		}

		return substr( $haystack, - $length ) === $needle;
	}

	/**
	 * Validates string regex rule.
	 */
	private function validate_string_regex( string $string, string $regex ): bool {
		$regex = $this->remove_global_regex_modifier( trim( $regex ) );

		return (bool) @preg_match( $regex, $string );
	}

	/**
	 * Remove the global regex modifier as it is not supported by PHP.
	 */
	private function remove_global_regex_modifier( string $regex ): string {
		return preg_replace_callback(
			'#(\/[a-z]+)$#',
			static function ( $modifiers ): string {
				return str_replace( 'g', '', $modifiers[0] );
			},
			$regex
		);
	}

	/**
	 * @return string[]
	 */
	public function get_conditions(): array {
		return [
			self::CONTAINS => esc_html__( self::CONTAINS, 'shopmagic-for-woocommerce' ),
			'not_contains' => esc_html__( 'does not contain', 'shopmagic-for-woocommerce' ),
			self::IS       => esc_html__( self::IS, 'shopmagic-for-woocommerce' ),
			'is_not'       => esc_html__( 'is not', 'shopmagic-for-woocommerce' ),
			'starts_with'  => esc_html__( 'starts with', 'shopmagic-for-woocommerce' ),
			'ends_with'    => esc_html__( 'ends with', 'shopmagic-for-woocommerce' ),
			'regex'        => esc_html__( 'matches regex', 'shopmagic-for-woocommerce' ),
		];
	}
}
