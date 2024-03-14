<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2021-2024 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoAfsFilters' ) ) {

	class WpssoAfsFilters {

		private $p;		// Wpsso class object.
		private $a;		// WpssoAfs class object.

		/*
		 * Instantiated by WpssoAfs->init_objects().
		 */
		public function __construct( &$plugin, &$addon ) {

			static $do_once = null;

			if ( true === $do_once ) {

				return;	// Stop here.
			}

			$do_once = true;

			$this->p =& $plugin;
			$this->a =& $addon;

			$this->p->util->add_plugin_filters( $this, array(
				'json_data_https_schema_org_thing' => 5,
			), PHP_INT_MAX );
		}

		/*
		 * The Schema standard provides 'aggregateRating' and 'review' properties for these types:
		 *
		 * 	Brand
		 * 	CreativeWork
		 * 	Event
		 * 	Offer
		 * 	Organization
		 * 	Place
		 * 	Product
		 * 	Service
		 *
		 * Unfortunately Google allows the 'aggregateRating' property only for these types:
		 *
		 *	Book
		 *	Course
		 *	Event
		 *	HowTo (includes Recipe)
		 *	LocalBusiness
		 *	Movie
		 *	Product
		 *	SoftwareApplication
		 *
		 * And the 'review' property only for these types:
		 *
		 *	Book
		 *	Course
		 *	CreativeWorkSeason
		 *	CreativeWorkSeries
		 *	Episode
		 *	Event
		 *	Game
		 *	HowTo (includes Recipe)
		 *	LocalBusiness
		 *	MediaObject
		 *	Movie
		 *	MusicPlaylist
		 * 	MusicRecording
		 *	Organization
		 *	Product
		 *	SoftwareApplication
		 */
		public function filter_json_data_https_schema_org_thing( $json_data, $mod, $mt_og, $page_type_id, $is_main ) {

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			if ( $is_main ) {

				if ( empty( $json_data[ 'aggregateRating' ] ) && empty( $json_data[ 'aggregateRating' ] ) ) {

					if ( $this->p->schema->allow_aggregate_rating( $page_type_id ) ) {

						if ( ! $this->p->schema->is_schema_type_child( $page_type_id, 'review' ) ) {

							$json_data[ 'aggregateRating' ] = WpssoSchema::get_schema_type_context( 'https://schema.org/AggregateRating', array(
								'ratingValue' => 5,
								'ratingCount' => 1,
								'worstRating' => 1,
								'bestRating'  => 5,
							) );

							$json_data[ 'review' ][] = WpssoSchema::get_schema_type_context( 'https://schema.org/Review', array(
								'author' => WpssoSchema::get_schema_type_context( 'https://schema.org/Organization', array(
									'name' => SucomUtilWP::get_site_name( $this->p->options, $mod ),
								) ),
								'reviewRating' => WpssoSchema::get_schema_type_context( 'https://schema.org/Rating', array(
									'ratingValue' => 5,
									'worstRating' => 1,
									'bestRating'  => 5,
								) ),
							) );
						}
					}
				}
			}

			return $json_data;
		}
	}
}
