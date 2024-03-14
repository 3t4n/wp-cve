<?php

namespace TotalContestVendors\TotalCore\Helpers;

/**
 * Class Sql
 * @package TotalContestVendors\TotalCore\Helpers
 */
class Sql {

	/**
	 * Generate where clause.
	 *
	 * @param array  $conditions
	 * @param string $relation
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public static function generateWhereClause( $conditions, $relation = 'AND' ) {
		$database   = \TotalContestVendors\TotalCore\Application::get( 'database' );
		$conditions = (array) $conditions;
		$relation   = (string) $relation;
		// Where clauses
		$whereClauses = [];
		// Construct the where clause
		$hasPrevious = false;
		foreach ( $conditions as $field => $fieldConditions ):
			// Escape user entries
			if ( $fieldConditions === 'OR' || $fieldConditions === 'AND' ):
				$hasPrevious    = false;
				$whereClauses[] = $fieldConditions;
				continue;
			elseif ( $hasPrevious ):
				$whereClauses[] = $relation;
			endif;

			if ( is_array( $fieldConditions ) && empty( $fieldConditions ) ):
				array_pop( $whereClauses );
				continue;
			endif;

			if ( null === $fieldConditions ):
				continue;
			endif;

			$field          = $database->_escape( $field );
			$firstCondition = true;
			foreach ( (array) $fieldConditions as $value ):
				// Dead simple where clause
				$whereClause = "`{$field}` = %s";

				// Relation
				if ( ! $firstCondition && ( $value === 'OR' || $value === 'AND' ) ):
					$whereClauses[] = $value;
					continue;
				endif;

				// Array? Alright, let's use WHERE IN
				if ( is_array( $value ) ):
					if ( isset( $value['operator'] ) && isset( $value['value'] ) ):
						$whereClause = str_replace( '= %s', "{$value['operator']} (%s)", $whereClause );
						$value       = $database->_escape( (string) $value['value'] );
					else:
						// Create placeholders for prepare method, just like (%s, %s ....)
						$valuesPlaceholders = implode( ', ', array_fill( 0, count( $value ), '%s' ) );
						// Apply changes to where clause
						$whereClause = str_replace( '= %s', "IN ({$valuesPlaceholders})", $whereClause );
					endif;
				endif;

				// Add relation
				if ( ! $firstCondition ):
					$whereClauses[] = $relation;
				endif;

				// Generate the prepared where clause
				$whereClauses[] = call_user_func_array(
					[ $database, 'prepare' ],
					array_merge( [ $whereClause ], (array) $value )
				);

				$firstCondition = false;
			endforeach;
			$hasPrevious = true;
		endforeach;

		return empty( $whereClauses ) ? '' : 'WHERE ' . implode( ' ', $whereClauses );
	}

	/**
	 * Generate order clause.
	 *
	 * @param $field
	 * @param $direction
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public static function generateOrderClause( $field, $direction ) {
		// Field
		$field = (string) $field;
		// Direction
		$direction = $direction === 'desc' || $direction === 'asc' ? strtoupper( $direction ) : 'DESC';

		return "ORDER BY {$field} {$direction}";
	}

	/**
	 * Generate limit clause.
	 *
	 * @param $page
	 * @param $perPage
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public static function generateLimitClause( $page, $perPage ) {
		// Page
		$page = (int) $page;
		// Per page
		$perPage = (int) $perPage;
		// Calculate offset
		$offset = absint( ( $page - 1 ) * $perPage );

		return "LIMIT {$offset}, {$perPage}";
	}
}