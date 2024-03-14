<?php

namespace TotalContestVendors\TotalCore\Helpers;

/**
 * Class Strings
 * @package TotalContestVendors\TotalCore\Helpers
 */
class Strings {
	/**
	 * Create a text from template and values.
	 *
	 * @param        $text
	 * @param        $values
	 * @param string $default
	 *
	 * @return string
	 */
	public static function template( $text, $values, $default = '' ) {
		return preg_replace_callback(
			'/\{\{(?P<expressions>(.*?))\}\}/sim',
			function ( $matches ) use ( $values, $default ) {
				$expressions         = array_map( 'trim', preg_split( '/\s*\|\|\s*/', $matches['expressions'] ) );
				$lastExpressionIndex = count( $expressions ) - 1;
				foreach ( $expressions as $expressionIndex => $expression ):
					$expression = str_replace( [ '"', "'" ], '', $expression );
					$item       = Arrays::getDotNotation( (array) $values, $expression, $expressionIndex === $lastExpressionIndex ? $expression : null );

					if ( is_array( $item ) ):
						return Arrays::stringify($item);
					elseif ( $item !== '' || $expressionIndex === $lastExpressionIndex ):
						return (string) Misc::value( $item );
					endif;
				endforeach;

				return $default;
			},
			empty($text) ? '' : $text
		);
	}
}
