<?php
/**
 * Tokenizer class
 *
 * @author  YITH
 * @package YITH/Search/DataIndex
 * @version 2.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Recover the data from database
 *
 * @since 2.0.0
 */
class YITH_WCAS_Data_Index_Tokenizer {

	/**
	 * Tokenize the object.
	 *
	 * @param   int    $data_index_id  Data index id.
	 * @param   array  $data           Content to index.
	 * @param   string $data_type      Data type.
	 *
	 * @return void
	 */
	public static function insert( $data_index_id, $data, $data_type ) {
		$lang        = $data['lang'];
		$data_insert = self::clear_data( $data );
		$tokens      = array();

		foreach ( $data_insert as $key => $content ) {
			$content        = self::clear_content( $content );
			$tokens[ $key ] = self::tokenize( $content );
		}

		$occurrences = array();
		if ( is_array( $tokens ) ) {
			foreach ( $tokens as $token_list ) {
				$saved_tokens = YITH_WCAS_Data_Index_Token::get_instance()->get_tokens( $token_list, $lang );
				foreach ( $token_list as $current_token ) {
					if ( in_array( $current_token, $occurrences, true ) ) {
						continue;
					}
					$saved_token     = array();
					$saved_token_key = array_search( $current_token, array_column( $saved_tokens, 'token' ), true );
					if ( false !== $saved_token_key ) {
						$saved_token = $saved_tokens[ $saved_token_key ];
					}
					$new_token = array(
						'token'         => $current_token,
						'frequency'     => isset( $saved_token['frequency'] ) ? $saved_token['frequency'] + 1 : 1,
						'doc_frequency' => isset( $saved_token['doc_frequency'] ) ?? 0,
						'lang'          => $lang,
					);

					$frequency                  = self::calculate_frequency_on_document( $current_token, $tokens );
					$new_token['doc_frequency'] = $new_token['doc_frequency'] + $frequency['frequency'];

					if ( $saved_token ) {
						$token_id = $saved_token['token_id'];
						unset( $new_token['token'] );

						YITH_WCAS_Data_Index_Token::get_instance()->update( $token_id, $new_token );
					} else {
						$token_id = YITH_WCAS_Data_Index_Token::get_instance()->insert( $new_token );
					}

					// Relationship.
					$relationship = array(
						'token_id'    => $token_id,
						'post_id'     => $data['post_id'],
						'frequency'   => $frequency['frequency'],
						'source_type' => $data_type,
						'position'    => implode( ',', $frequency['position'] ),
					);

					YITH_WCAS_Data_Index_Relationship::get_instance()->insert( $relationship );
					$occurrences[] = $current_token;
				}
			}
		}
	}

	/**
	 * Clear the content from shortcodes, html tags
	 *
	 * @param   string $content  Content to clean.
	 *
	 * @return string
	 */
	public static function clear_content( $content ) {
		$clean_content = strip_shortcodes( $content );
		$clean_content = wp_strip_all_tags( $clean_content );

		return apply_filters( 'yith_wcas_clear_content', $clean_content, $content );
	}

	/**
	 * Remove no useful arguments
	 *
	 * @param   array $data  Data.
	 *
	 * @return array
	 */
	protected static function clear_data( $data ) {
		$index_arguments = ywcas_get_index_arguments();
		$index_arguments = array_flip( $index_arguments );

		return array_intersect_key( $data, $index_arguments );
	}


	/**
	 * Tokenize the content
	 *
	 * @param   string $content  Content.
	 * @param   string $context  Context.
	 *
	 * @return array
	 * @since 2.0.0
	 */
	public static function tokenize( $content, $context = 'index' ) {
		return self::split_content( $content );
	}

	/**
	 * Split content
	 *
	 * @param   string $content  Content to split.
	 *
	 * @return array|false|string[]
	 */
	public static function split_content( string $content ) {
		$content = ywcas_strtolower( $content );
		$content = trim( preg_replace( '/\s\s+/', ' ', $content ) );

		$regex         = '/[^\p{L}\p{N}]+/u';
		$split_content = preg_split( $regex, $content, - 1, PREG_SPLIT_NO_EMPTY );
		$split_content = self::split_for_dots( $split_content );
		$split_content = array_map( array( __CLASS__, 'clear_term' ), $split_content );

		$single_words = explode( ' ', $content );
		$single_words = array_map( array( __CLASS__, 'clear_term' ), $single_words );
		if ( $single_words ) {
			$single_words  = array_diff( $single_words, $split_content );
			$split_content = array_merge( $split_content, $single_words );
		}

		$split_content = array_filter( $split_content );
		$split_content = array_unique( $split_content );

		return array_diff( $split_content, self::get_stop_words() );
	}


	/**
	 * Split a single content by dots
	 *
	 * @param   array $content  Content.
	 *
	 * @return array
	 */
	public static function split_for_dots( $content ) {
		$dots = apply_filters( 'ywcas_dot_list', array( '.', ';', '_', ',' ) );
		foreach ( $content as $term ) {
			$new_terms = array();
			foreach ( $dots as $dot ) {
				strpos( $term, $dot ) !== false && array_push( $new_terms, str_replace( $dot, '', $term ) );
			}
			$content = array_merge( $content, array_unique( $new_terms ) );
		}

		return $content;
	}

	/**
	 * Remove dots at start and end of the screen
	 *
	 * @param   string $term  Term to process.
	 *
	 * @return string
	 */
	public static function clear_term( $term ) {
		$term = preg_replace( '/\.$|\;$|\,$|\:$|\_$/', '', $term );
		$term = preg_replace( '/^\.|^\;|^\,|^\:|^\_/', '', $term );
		$term = html_entity_decode( $term );
		$term = wp_strip_all_tags( $term );
		$term = strlen( $term ) > 1 ? $term : '';

		return $term;
	}


	/**
	 * Return the stop words list
	 *
	 * @return array
	 */
	public static function get_stop_words() {
		return apply_filters( 'yith_wcas_stop_words', array() );
	}

	/**
	 * Calculate the frequency of token inside the document.
	 *
	 * @param   string $token     Token.
	 * @param   array  $haystack  Array of terms on documents.
	 *
	 * @return array
	 * @since 2.0.0
	 */
	public static function calculate_frequency_on_document( $token, $haystack ) {
		$result = array();
		foreach ( $haystack as $key => $list ) {
			$keys = array_keys( $list, $token, true );
			if ( false !== $keys ) {
				$result[ $key ] = count( $keys );
			}
		}

		return array(
			'frequency' => array_sum( $result ),
			'position'  => array_keys(
				array_filter(
					$result,
					function ( $var ) {
						return $var > 0;
					}
				)
			),
		);
	}

	/**
	 * Add to the content the synonymous as string.
	 *
	 * @param   string $content  Content.
	 *
	 * @return string
	 */
	public static function get_synonymous( $content ) {
		$syn_list           = ywcas()->settings->get_synonymous();
		$content            = mb_strtolower( $content );
		$additional_content = $content;
		if ( is_array( $syn_list ) ) {
			foreach ( $syn_list as $list ) {
				$synoymous = mb_strtolower( $list );
				$synoymous = preg_replace_callback(
					"/([!@#$&()\-\[\]{}\\`.+,\/\"\\'])/",
					function ( $matches ) {
						return '\\' . $matches[0];
					},
					$synoymous
				);
				if ( ! empty( $synoymous ) && preg_match( "/([^a-zA-Z0-9]|^)$content([^a-zA-Z0-9}]|$)/i", $synoymous ) ) {
					$additional_content .= ' ' . $list;
					break;
				}
			}
		}

		return $additional_content;
	}
}
