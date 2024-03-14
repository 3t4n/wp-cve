<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2017-2024 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoRarFilters' ) ) {

	class WpssoRarFilters {

		private $p;	// Wpsso class object.
		private $a;	// WpssoRar class object.
		private $msgs;	// WpssoRarFiltersMessages class object.
		private $opts;	// WpssoRarFiltersOptions class object.

		/*
		 * Instantiated by WpssoRar->init_objects().
		 */
		public function __construct( &$plugin, &$addon ) {

			static $do_once = null;

			if ( true === $do_once ) {

				return;	// Stop here.
			}

			$do_once = true;

			$this->p =& $plugin;
			$this->a =& $addon;

			require_once WPSSORAR_PLUGINDIR . 'lib/filters-options.php';

			$this->opts = new WpssoRarFiltersOptions( $plugin, $addon );

			$this->p->util->add_plugin_filters( $this, array(
				'get_sortable_columns' => 1,
				'og'                   => 2,
			), $prio = 1000 );

			if ( is_admin() ) {

				require_once WPSSORAR_PLUGINDIR . 'lib/filters-messages.php';

				$this->msgs = new WpssoRarFiltersMessages( $plugin, $addon );
			}
		}

		public function filter_get_sortable_columns( $columns ) {

			return array_merge( array(
				'avg_rating' => array(
					'header'         => 'Rating',
					'meta_key'       => WPSSORAR_META_AVERAGE_RATING,
					'post_callbacks' => array(	// An array of callback functions / methods.
						array( $this, 'post_callback_rating_enabled' ),
					),
					'orderby' => 'meta_value',
					'width'   => '75px',
					'height'  => 'auto',
				)
			), $columns );
		}

		public function post_callback_rating_enabled( $value, $post_id ) {

			$rating_enabled = WpssoRarComment::is_rating_enabled( $post_id );

			$value = apply_filters( 'wpssorar_post_column_rating_value', $value, $post_id, $rating_enabled );

			$input_hidden = '<input name="rar_allow_ratings" type="hidden" value="' . $rating_enabled . '" readonly="readonly" />';

			return $value . "\n" . $input_hidden;
		}

		public function filter_og( array $mt_og, array $mod ) {

			if ( empty( $mod[ 'is_post' ] ) || empty( $mod[ 'id' ] ) ) {	// Make sure we have a valid post ID.

				return $mt_og;
			}

			if ( ! WpssoRarComment::is_rating_enabled( $mod[ 'id' ] ) ) {

				if ( $this->p->debug->enabled ) {

					$this->p->debug->log( 'exiting early: post ID ' . $mod[ 'id' ] . ' ratings disabled' );
				}

				return $mt_og;
			}

			if ( empty( $mt_og[ 'og:type' ] ) ) {	// Just in case.

				if ( $this->p->debug->enabled ) {

					$this->p->debug->log( 'exiting early: open graph type is empty' );
				}

				return $mt_og;
			}

			$og_type      = $mt_og[ 'og:type' ];
			$worst_rating = 1;
			$best_rating  = 5;
			$have_schema  = $this->p->avail[ 'p' ][ 'schema' ] ? true : false;

			/*
			 * Add rating meta tags.
			 */
			if ( apply_filters( 'wpsso_og_add_mt_rating', true, $mod ) ) {	// Enabled by default.

				if ( $this->p->debug->enabled ) {

					$this->p->debug->log( 'add rating meta tags is true' );
				}

				$average_rating = (float) WpssoRarComment::get_average_rating( $mod[ 'id' ] );
				$rating_count   = (int) WpssoRarComment::get_rating_count( $mod[ 'id' ] );
				$review_count   = (int) WpssoRarComment::get_review_count( $mod[ 'id' ] );

				if ( $this->p->debug->enabled ) {

					$this->p->debug->log( 'average rating = ' . $average_rating );
					$this->p->debug->log( 'rating count = ' . $rating_count );
					$this->p->debug->log( 'review count = ' . $review_count );
				}

				/*
				 * An average rating value must be greater than 0.
				 */
				if ( $average_rating > 0 ) {

					/*
					 * At least one rating or review is required.
					 */
					if ( $rating_count > 0 || $review_count > 0 ) {

						if ( $this->p->debug->enabled ) {

							$this->p->debug->log( 'adding rating meta tags for ' . $mod[ 'name' ] . ' id ' . $mod[ 'id' ] );
						}

						$mt_og[ $og_type . ':rating:average' ] = $average_rating;
						$mt_og[ $og_type . ':rating:count' ]   = $rating_count;
						$mt_og[ $og_type . ':rating:worst' ]   = $worst_rating;
						$mt_og[ $og_type . ':rating:best' ]    = $best_rating;
						$mt_og[ $og_type . ':review:count' ]   = $review_count;

					} elseif ( $this->p->debug->enabled ) {

						$this->p->debug->log( 'rating and review count is invalid (must be greater than 0)' );
					}

				} elseif ( $this->p->debug->enabled ) {

					$this->p->debug->log( 'average rating is invalid (must be greater than 0)' );
				}

			} elseif ( $this->p->debug->enabled ) {

				$this->p->debug->log( 'add rating meta tags is false' );
			}

			/*
			 * Add reviews meta tags.
			 */
			if ( apply_filters( 'wpsso_og_add_mt_reviews', $have_schema, $mod ) ) {

				if ( $this->p->debug->enabled ) {

					$this->p->debug->log( 'add review meta tags is true' );
				}

				$mt_og[ $og_type . ':reviews' ] = $mod[ 'obj' ]->get_mt_reviews( $mod[ 'id' ], WPSSO_META_RATING_NAME, $worst_rating, $best_rating );

			} elseif ( $this->p->debug->enabled ) {

				$this->p->debug->log( 'add review meta tags is false' );
			}

			return $mt_og;
		}
	}
}
