<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2020-2024 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoWcmdSearch' ) ) {

	class WpssoWcmdSearch {

		private $p;	// Wpsso class object.
		private $a;	// WpssoWcmd class object.

		/*
		 * Instantiated by WpssoWcmd->init_objects().
		 */
		public function __construct( &$plugin, &$addon ) {

			$this->p =& $plugin;
			$this->a =& $addon;

			add_action( 'pre_get_posts', array( $this, 'action_pre_get_posts' ), 10000, 1 );

			add_filter( 'posts_where', array( $this, 'filter_posts_where' ), 10000, 2 );
			add_filter( 'posts_request', array( $this, 'filter_posts_request' ), 10000, 1 );
		}

		public function action_pre_get_posts( $wp_query ) {

			if ( ! $wp_query->is_main_query() ) {

				return;
			}

			/*
			 * Save the WordPress front-end and admin search query.
			 */
			if ( ! empty( $wp_query->is_search ) ) {

				$wp_query->saved_search_s = array(
					's' => isset( $wp_query->query[ 's' ] ) ? $wp_query->query[ 's' ] : '',
				);

				if ( $this->p->debug->enabled ) {

					$this->p->debug->log_arr( 'query is search', $wp_query->saved_search_s );
				}

			/*
			 * Save the WooCommerce admin product search (ie. Products > All Products page > Search products button).
			 */
			} elseif ( ! empty( $wp_query->query[ 'product_search' ] ) ) {

				$wp_query->saved_search_s = array(
					's' => isset( $_GET[ 's' ] ) ? sanitize_text_field( $_GET[ 's' ] ) : '',
				);

				if ( $this->p->debug->enabled ) {

					$this->p->debug->log_arr( 'query is product search', $wp_query->saved_search_s );
				}
			}
		}

		/*
		 * Changed the filter hook from 'posts_search' to 'posts_where' in WPSSO WCMD v1.13.2.
		 */
		public function filter_posts_where( $search, $wp_query ) {

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			if ( ! $wp_query->is_main_query() ) {

				return $search;

			} elseif ( empty( $wp_query->saved_search_s[ 's' ] ) ) {

				return $search;
			}

			$product_ids = $this->get_search_product_ids( $wp_query->saved_search_s[ 's' ] );	// Returns an array.

			if ( $this->p->debug->enabled ) {

				$this->p->debug->log_arr( 'product_ids', $product_ids );
			}

			if ( empty( $product_ids ) ) {

				return $search;
			}

			global $wpdb;

			$post_id_query = $wpdb->posts . '.ID IN (' . implode( $glue = ', ', $product_ids ) . ')';

			if ( $this->p->debug->enabled ) {

				$this->p->debug->log( 'input search = ' . $search );
			}

			$search = preg_replace( '/[\s\n\r]+/s', ' ', $search );	// Put everything on one line.

			if ( empty( $search ) ) {

				$search = ' AND ' . $post_id_query . ' ';

			} elseif ( preg_match( '/^ *AND  *(.*)(  *AND  *.*)?$/U', $search, $matches ) ) {

				$search = ' AND (' . $post_id_query . ' OR ' . $matches[ 1 ] . ')' . $matches[ 2 ];
			}

			if ( $this->p->debug->enabled ) {

				$this->p->debug->log( 'returned search = ' . $search );
			}

			return $search;
		}

		/*
		 * Since WPSSO WCMD v1.13.2.
		 */
		public function filter_posts_request( $request ) {

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();

				$this->p->debug->log_arr( 'request', $request );
			}

			return $request;
		}

		/*
		 * Always return an array.
		 */
		private function get_search_product_ids( $s ) {

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			$post_ids    = $this->get_search_post_ids( $s );	// Returns an array.
			$product_ids = array();

			foreach ( $post_ids as $post_id ) {

				$post_obj = get_post( $post_id );

				if ( $post_obj->post_type === 'product_variation') {

					$product_ids[] = $post_obj->post_parent;

				} else {

					$product_ids[] = $post_obj->ID;
				}
			}

			if ( $this->p->debug->enabled ) {

				$this->p->debug->log_arr( 'product_ids', $product_ids );
			}

			return $product_ids;

		}

		/*
		 * Always return an array.
		 */
		private function get_search_post_ids( $s ) {

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			global $wpdb;

			$sql_meta_keys = $this->get_sql_meta_keys();

			if ( empty( $sql_meta_keys ) ) {

				return array();
			}

			$s = stripslashes( trim( $s  ) );

			if ( empty( $s ) ) {

				return array();
			}

			if ( preg_match_all( '/".*?("|$)|((?<=[\t ",+])|^)[^\t ",+]+/', $s, $matches ) ) {

				$search_terms = $this->get_parsed_search_terms( $matches[ 0 ] );

			} else {

				$search_terms = array( $s );
			}

			if ( empty( $search_terms ) ) {

				return array();
			}

			$db_query = 'SELECT post_id FROM ' . $wpdb->postmeta . ' WHERE meta_key IN (' . implode( ',', $sql_meta_keys ) . ') AND (';

			foreach ( $search_terms as $num => $term ) {

				$db_query .= $num > 0 ? ' OR ' : '';

				$db_query .= 'meta_value=\'' . esc_sql( $term ) . '\'';
			}

			$db_query .= ');';

			if ( $this->p->debug->enabled ) {

				$this->p->debug->log( 'db query = ' . $db_query );
			}

			$post_ids = $wpdb->get_col( $db_query );

			return $post_ids;
		}

		private function get_sql_meta_keys() {

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			$md_config = WpssoWcmdConfig::get_md_config();

			$sql_meta_keys = array();

			foreach ( $md_config as $md_suffix => $cfg ) {

				if ( ! empty( $cfg[ 'searchable' ] ) ) {

					if ( $md_key = $this->a->wc->get_edit_metadata_key( $md_suffix ) ) {	// Always returns a string.

						$sql_meta_keys[] = '\'' . esc_sql( $md_key ) . '\'';
					}
				}
			}

			if ( $this->p->debug->enabled ) {

				$this->p->debug->log_arr( 'sql_meta_keys', $sql_meta_keys );
			}

			return $sql_meta_keys;
		}

		private function get_parsed_search_terms( $terms ) {

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			$search_terms = array();

			foreach ( $terms as $term ) {

				/*
				 * Keep spaces when term is for exact match.
				 */
				if ( preg_match( '/^".+"$/', $term ) ) {

				 	$term = trim( $term, "\"'" );

				} else {

					$term = trim( $term, "\"' " );
				}

				/*
				 * Avoid single a-z and single dashes.
				 */
				if ( ! $term || ( 1 === strlen( $term ) && preg_match( '/^[a-z\-]$/i', $term ) ) ) {

					continue;
				}

				$search_terms[] = $term;
			}

			if ( $this->p->debug->enabled ) {

				$this->p->debug->log_arr( 'search_terms', $search_terms );
			}

			return $search_terms;
		}
	}
}
