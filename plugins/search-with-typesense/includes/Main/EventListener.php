<?php

namespace Codemanas\Typesense\Main;

use Codemanas\Typesense\Backend\Admin;

class EventListener {
	public static ?EventListener $instance = null;
	/**
	 * @var false|mixed|void
	 */
	private $searchConfigSettings;
	/**
	 * @var false|mixed|void|null
	 */
	private $enablePostTypes;
	private ?TypesenseAPI $typeSenseAPIInterface;

	public static function getInstance(): ?EventListener {
		return is_null( self::$instance ) ? self::$instance = new self() : self::$instance;
	}

	public function __construct() {
		//Set up the required stuff
		$this->typeSenseAPIInterface = TypesenseAPI::getInstance();
		add_action( 'init', [ $this, 'initSettings' ] );
		// When posts added
		add_action( 'wp_after_insert_post', [ $this, 'postCreatedUpdatedHandler' ], 10, 3 );
		add_action( 'delete_post', [ $this, 'postDeletedHandler' ], 10, 2 );
		add_action( 'wp_trash_post', [ $this, 'postDeletedHandler' ], 10, 2 );

		// Run when category changed / updated
		add_action( 'saved_term', [ $this, 'onTermUpdated' ], 10, 4 );
	}

	public function initSettings() {
		$this->searchConfigSettings = Admin::get_search_config_settings();
		$this->enablePostTypes      = $this->searchConfigSettings['enabled_post_types'] ?? null;
	}

	private function checkIfPostTypeEnabled( $post_type ): bool {
		if ( ! is_array( $this->enablePostTypes ) ) {
			return false;
		}

		return in_array( $post_type, $this->enablePostTypes );
	}

	public function postDeletedHandler( int $post_id ) {
		$post = get_post( $post_id );
		if ( ! $this->checkIfPostTypeEnabled( $post->post_type ) ) {
			return;
		}

		TypesenseAPI::getInstance()->deleteDocumentByID( $post->post_type, $post_id );
	}

	/**
	 * @param int $post_ID
	 * @param \WP_Post $post
	 * @param bool $update
	 */
	public function postCreatedUpdatedHandler( int $post_ID, \WP_Post $post, bool $update ) {
		if ( ! $this->checkIfPostTypeEnabled( $post->post_type ) ) {
			return;
		}

		$approvedPostStatus = apply_filters( 'cm_typesense_post_status', [ 'publish' ] );
		$removePostStatus   = apply_filters( 'cm_typesense_post_remove_status', [ 'draft', 'pending' ] );

		if (
			is_array( $removePostStatus ) && in_array( $post->post_status, $removePostStatus )
			|| apply_filters( 'cm_typesense_force_remove_post_on_update', false, $post )
		) {
			//you can use cm_typesense_post_force_remove_post_on_update to check for post meta values and remove it | if needed
			$this->typeSenseAPIInterface->deleteDocumentByID( $post->post_type, $post->ID );

			return;
		} elseif (
			is_array( $approvedPostStatus ) && ! in_array( $post->post_status, $approvedPostStatus )
		) {
			//can use cm_typesense_post_force_add_post_on_update to add posts via meta values
			return;
		}

		$supportedPostTypes = apply_filters( 'codemanas_typesense_indexed_post_types', $this->enablePostTypes );
		if ( in_array( $post->post_type, $supportedPostTypes ) ) {
			$document = $this->typeSenseAPIInterface->formatDocumentForEntry( $post, $post_ID, $post->post_type );
			$result   = $this->typeSenseAPIInterface->upsertDocument( $post->post_type, $document );
			if ( is_wp_error( $result ) && $result->get_error_code() == 404 ) {
				$schema             = $this->typeSenseAPIInterface->getSchema( $post->post_type );
				$schemaMaybeCreated = $this->typeSenseAPIInterface->createCollection( $schema );
				if ( is_object( $schemaMaybeCreated ) && $schemaMaybeCreated->name == $post->post_type ) {
					$this->typeSenseAPIInterface->upsertDocument( $post->post_type, $document );
				}

			}
		}

		//For Updating Categories
		foreach ( $this->enablePostTypes as $enabledIndex ) {
			$enabledIndexData = $this->searchConfigSettings['available_post_types'][ $enabledIndex ];
			if ( isset( $enabledIndexData['type'] ) && $enabledIndexData['type'] == 'taxonomy' ) {
				$terms = get_the_terms( $post, $enabledIndexData['value'] );
				if ( ! empty( $terms ) ) {
					$documents = [];

					foreach ( $terms as $term ) {
						$documents[] = $this->typeSenseAPIInterface->formatDocumentForEntry( $term, $term->term_id, $enabledIndexData['value'] );
					}

					if ( ! empty( $documents ) ) {
						$this->typeSenseAPIInterface->bulkUpsertDocuments( $enabledIndexData['value'], $documents );
					}
				}
			}
		}
	}


	/**
	 * @param int $term_id Term ID
	 * @param int $tt_id Term Taxonomy ID
	 * @param string $taxonomy Taxonomy Slug
	 * @param bool $update
	 *
	 * @throws \JsonException
	 */
	public function onTermUpdated( $term_id, $tt_id, $taxonomy, $update ) {
		if ( ! $update ) {
			return false;
		}

		//By default, i've added category as category is the default one required for taxonomy
		$enabled_taxonomies = apply_filters( 'cm_typesense_enabled_taxonomy_for_post_type', [ 'category' ] );
		if ( ! in_array( $taxonomy, $enabled_taxonomies ) ) {
			return false;
		}

		// get the post types attached to the taxonomy
		$tax_post_types = $this->getTaxonomyPostTypes( $taxonomy );
		if ( ! empty( $tax_post_types ) ) {
			//check if the attached post types are enabled
			$enabled_tax_post_types = array_intersect( $this->enablePostTypes, $tax_post_types );
			if ( ! empty( $enabled_tax_post_types ) ) {

				foreach ( $enabled_tax_post_types as $enabled_tax_post_type ) {
					$args      = [
						'post_type'      => $enabled_tax_post_type,
						'posts_per_page' => - 1,
						// this may cause timeout issues, but I don't see a work around right now
						'tax_query'      => [
							[
								'taxonomy' => $taxonomy,
								'field'    => 'term_id',
								'terms'    => $term_id,
							],
						],
					];
					$documents = [];
					$posts     = get_posts( $args );
					if ( empty( $posts ) ) {
						return;
					}

					foreach ( $posts as $post ) {
						$documents[] = TypesenseAPI::getInstance()->formatDocumentForEntry( $post, $post->ID, $post->post_type );
					}
					TypesenseAPI::getInstance()->bulkUpsertDocuments( $enabled_tax_post_type, $documents );

				}
			}
		}

		//For Updating Categories
		foreach ( $this->enablePostTypes as $enabledIndex ) {
			$enabledIndexData = $this->searchConfigSettings['available_post_types'][ $enabledIndex ];
			if ( isset( $enabledIndexData['type'] ) && $enabledIndexData['type'] == 'taxonomy' ) {
				$term     = get_term( $term_id, $taxonomy );
				$document = $this->typeSenseAPIInterface->formatDocumentForEntry( $term, $term_id, $taxonomy );
				$this->typeSenseAPIInterface->upsertDocument( $taxonomy, $document );
			}
		}
	}

	/**
	 * @param string $tax taxonomy slug
	 */
	private function getTaxonomyPostTypes( string $tax = 'category' ) {
		global $wp_taxonomies;

		return ( isset( $wp_taxonomies[ $tax ] ) ) ? $wp_taxonomies[ $tax ]->object_type : [];
	}

}