<?php

namespace Codemanas\Typesense\Main;

use Codemanas\Typesense\Backend\Admin;
use Codemanas\Typesense\Helpers\Logger;

class TypesenseAPI {
	private $admin_api_key;
	private string $node;
	public static ?TypesenseAPI $instance = null;
	/**
	 * @var false|mixed
	 */
	private $debug_enabled;

	public static function getInstance(): ?TypesenseAPI {
		return is_null( self::$instance ) ? self::$instance = new self() : self::$instance;
	}

	private array $schemas
		= [
			//will act like default schema if post type doesn't have custom schema registered
			'post'     => [
				'name'                  => 'post',
				'fields'                => [
					[ 'name' => 'post_content', 'type' => 'string' ],
					[ 'name' => 'post_title', 'type' => 'string' ],
					[ 'name' => 'post_type', 'type' => 'string' ],
					[ 'name' => 'post_author', 'type' => 'string' ],
					[ 'name' => 'comment_count', 'type' => 'int64' ],
					[ 'name' => 'is_sticky', 'type' => 'int32' ],
					[ 'name' => 'post_excerpt', 'type' => 'string' ],
					[ 'name' => 'post_date', 'type' => 'string' ],
					[ 'name' => 'sort_by_date', 'type' => 'int64' ],
					[ 'name' => 'post_id', 'type' => 'string' ],
					[ 'name' => 'post_modified', 'type' => 'string' ],
					[ 'name' => 'id', 'type' => 'string' ],
					[ 'name' => 'permalink', 'type' => 'string' ],
					[ 'name' => 'post_thumbnail', 'type' => 'string', 'optional' => true, 'index' => false ],
					[ 'name' => 'post_thumbnail_html', 'type' => 'string', 'optional' => true, 'index' => false ],
					[ 'name' => 'category', 'type' => 'string[]', 'optional' => true, 'facet' => true ],
					[ 'name' => 'cat_link', 'type' => 'string[]', 'optional' => true, 'index' => false ],
				],
				'default_sorting_field' => 'sort_by_date',
			],
			'page'     => [
				'name'                  => 'post',
				'fields'                => [
					[ 'name' => 'post_content', 'type' => 'string' ],
					[ 'name' => 'post_title', 'type' => 'string' ],
					[ 'name' => 'post_type', 'type' => 'string' ],
					[ 'name' => 'post_author', 'type' => 'string' ],
					[ 'name' => 'comment_count', 'type' => 'int64' ],
					[ 'name' => 'is_sticky', 'type' => 'int32' ],
					[ 'name' => 'post_excerpt', 'type' => 'string' ],
					[ 'name' => 'post_date', 'type' => 'string' ],
					[ 'name' => 'sort_by_date', 'type' => 'int64' ],
					[ 'name' => 'post_id', 'type' => 'string' ],
					[ 'name' => 'post_modified', 'type' => 'string' ],
					[ 'name' => 'id', 'type' => 'string' ],
					[ 'name' => 'permalink', 'type' => 'string' ],
					[ 'name' => 'post_thumbnail', 'type' => 'string', 'optional' => true, 'index' => false ],
					[ 'name' => 'post_thumbnail_html', 'type' => 'string', 'optional' => true, 'index' => false ],
				],
				'default_sorting_field' => 'sort_by_date',
			],
			'category' => [
				'name'                  => 'category',
				'fields'                => [
					[ 'name' => 'term_id', 'type' => 'string' ],
					[ 'name' => 'id', 'type' => 'string' ],
					[ 'name' => 'taxonomy', 'type' => 'string' ],
					[ 'name' => 'post_title', 'type' => 'string' ],
					[ 'name' => 'post_content', 'type' => 'string' ],
					[ 'name' => 'slug', 'type' => 'string' ],
					[ 'name' => 'posts_count', 'type' => 'int64' ],
					[ 'name' => 'permalink', 'type' => 'string' ],
				],
				'default_sorting_field' => 'posts_count',
			],
		];


	public function setupClientCredentials(): void {
		$settings            = Admin::get_default_settings();
		$this->node          = ( $settings['protocol'] . $settings['node'] . ':' . $settings['port'] . '/' ?? '' );
		$this->admin_api_key = $settings['admin_api_key'] ?? '';
		$this->debug_enabled = $settings['debug_log'] ?? false;
	}

	public function getSchema( $name ) {
		$schema         = $this->schemas[ $name ] ?? $this->schemas['post'];
		$schema['name'] = $name;

		return apply_filters( 'cm_typesense_schema', $schema, $name );
	}

	public function getCollectionNameFromSchema( $name ) {
		//name is the schema index
		$schema = $this->getSchema( $name );

		return $schema['name'];
	}

	/**
	 * @param        $raw_data
	 * @param int $object_id
	 * @param string $schema_name
	 */
	public function formatDocumentForEntry( $raw_data, int $object_id, string $schema_name ) {
		//most time $raw_data will be \WP_POST object
		//formatted data must match schema or else will result in error
		//$this->searchConfigSettings['available_post_types']
		$search_config_settings = Admin::get_search_config_settings();
		$available_post_types   = $search_config_settings['available_post_types'];

		$formatted_data = [];

		//For post types - it will go through the default options first and post types can simply add additional data with hook for formatDocumentForEntry
		if ( $schema_name == 'post' || ! isset( $available_post_types[ $schema_name ]['type'] )
		     || 'post' == $available_post_types[ $schema_name ]['type']
		     || ( 'taxonomy' != $available_post_types[ $schema_name ]['type'] )
		) {
			$schema   = $this->getSchema( $schema_name );
			$raw_data = ( $raw_data instanceof \WP_Post ) ? $raw_data : get_post( $object_id );
			$fields   = $schema['fields'];
			foreach ( $fields as $field ) {
				switch ( $field['name'] ) {
					case 'post_id':
						$formatted_data['post_id'] = (string) $raw_data->ID;
						break;
					case 'post_content':
						$formatted_data['post_content'] = wp_strip_all_tags( $raw_data->post_content );
						break;
					case 'post_title':
						$formatted_data['post_title'] = $raw_data->post_title;
						break;
					case 'post_type':
						$formatted_data['post_type'] = $raw_data->post_type;
						break;
					case 'comment_count':
						$formatted_data['comment_count'] = (int) $raw_data->comment_count;
						break;
					case 'is_sticky':
						$formatted_data['is_sticky'] = ( int ) is_sticky( $raw_data->ID );
						break;
					case 'post_excerpt':
						$formatted_data['post_excerpt'] = $raw_data->post_excerpt;
						break;
					case 'post_date':
						$formatted_data['post_date'] = get_the_date( '', $raw_data );
						break;
					case 'post_author':
						$formatted_data['post_author'] = get_the_author_meta( 'display_name', $raw_data->post_author );
						break;
					case 'post_modified':
						$formatted_data['post_modified'] = $raw_data->post_modified;
						break;
					case 'sort_by_date':
						$formatted_data['sort_by_date'] = strtotime( $raw_data->post_date );
						break;
					case 'permalink':
						$formatted_data['permalink'] = get_permalink( $raw_data );
						break;
					case 'post_thumbnail':
						$post_thumbnail                   = get_the_post_thumbnail_url( $raw_data );
						$formatted_data['post_thumbnail'] = ! empty( $post_thumbnail ) ? $post_thumbnail : '';
						break;
					case 'post_thumbnail_html':
						$formatted_data['post_thumbnail_html'] = has_post_thumbnail( $raw_data ) ? wp_get_attachment_image( get_post_thumbnail_id( $raw_data ), apply_filters( 'cm_typesense_html_image_size', 'medium' ),false,[
							'class' => 'ais-Hit-itemImage'
						]) : '';
						break;
					case 'category':
						$categories     = get_the_category( $raw_data->ID );
						$category_names = [];
						$category_links = [];
						if ( ! empty( $categories ) ) {
							foreach ( $categories as $category ) {
								//@todo: for adding sub categories
								//$term_ids = get_ancestors( $category->term_id, 'category', 'taxonomy' );
								//array_unshift( $term_ids, $category->term_id );
								//foreach ( $term_ids as $term_id ) {
								//$term             = get_term( $term_id );
								//$category_links[] = get_term_link( $term );
								//$category_names[] = $term->name;
								//}
								$category_links[] = get_term_link( $category );
								$category_names[] = html_entity_decode( $category->name );
							}
						}
						$formatted_data['cat_link'] = is_array( $category_links ) && ! empty( $category_links ) ? array_values( array_unique( $category_links ) ) : [];
						$formatted_data['category'] = is_array( $category_names ) && ! empty( $category_names ) ? array_values( array_unique( $category_names ) ) : [];
						break;
					default:
						break;
				}
			}
		} elseif ( 'taxonomy' == $available_post_types[ $schema_name ]['type'] ) {
			$schema   = $this->getSchema( $schema_name );
			$raw_data = ( $raw_data instanceof \WP_Term_Query ) ? $raw_data : get_term( $object_id );
			$fields   = $schema['fields'];

			foreach ( $fields as $field ) {
				switch ( $field['name'] ) {
					case 'term_id':
						$formatted_data['term_id'] = (string) $raw_data->term_id;
						break;
					case 'id':
						$formatted_data['id'] = (string) $raw_data->term_id;
						break;
					case 'post_content':
						$formatted_data['post_content'] = wp_strip_all_tags( $raw_data->description );
						break;
					case 'post_title':
						$formatted_data['post_title'] = $raw_data->name;
						break;
					case 'taxonomy':
						$formatted_data['taxonomy'] = $raw_data->taxonomy;
						break;
					case 'slug':
						$formatted_data['slug'] = $raw_data->slug;
						break;
					case 'posts_count':
						$formatted_data['posts_count'] = ( int ) $raw_data->count;
						break;
					case 'permalink':
						$formatted_data['permalink'] = get_term_link( $raw_data );
						break;
					default:
						break;
				}
			}
		}

		$formatted_data['id'] = (string) $object_id;

		return apply_filters( 'cm_typesense_data_before_entry', $formatted_data, $raw_data, $object_id, $schema_name );

	}

	/**
	 * @param        $endpoint
	 * @param string $method
	 * @param null $data
	 *
	 */
	private function makeRequest( $endpoint, string $method = 'GET', $data = null ) {
		$this->setupClientCredentials();
		$args = [
			'method'  => $method,
			'headers' => [
				'X-TYPESENSE-API-KEY' => $this->admin_api_key,
				'Content-Type'        => 'application/json',
			],
		];

		if ( ! empty( $data ) ) {
			$args['body'] = is_string( $data ) ? $data : json_encode( $data );
		}
		$result = wp_remote_request( $this->node . $endpoint, $args );

		if ( is_wp_error( $result ) ) {
			return $result;
		} else {
			$responseCode = wp_remote_retrieve_response_code( $result );
			$responseBody = wp_remote_retrieve_body( $result );
			$responseBody = $this->isJson( $responseBody ) ? json_decode( $responseBody ) : $responseBody;


			if ( $responseCode == '405' ) {
				return $this->formatErrorMessage( $responseCode, $responseBody, $result );
			} elseif ( $responseCode == '404' ) {
				//condition if node is wrong.
				return $this->formatErrorMessage( $responseCode, $responseBody, $result );
			} elseif ( $responseCode == '400' ) {
				//Bad Request
				//Node is correct but request is not
				return $this->formatErrorMessage( $responseCode, $responseBody, $result );
			} elseif ( $responseCode == '401' ) {
				//invalid API keys or not enough access
				return $this->formatErrorMessage( $responseCode, $responseBody, $result );
			} elseif ( $responseCode == '409' ) {
				//i should really see the error codes
				return $this->formatErrorMessage( $responseCode, $responseBody, $result );
			}
		}


		if ( isset( $responseBody->code ) ) {
			$responseBody = new \WP_Error( $responseBody->code, $responseBody->error );
		}

		//debug log
		if ( $this->debug_enabled ) {
			$logger = new Logger();
			$logger->logDebugData( $responseBody );
		}

		return $responseBody;


	}

	/**
	 * @param $string
	 *
	 * @return bool
	 */
	public function isJson( $string ): bool {
		json_decode( $string );

		return json_last_error() === JSON_ERROR_NONE;
	}

	/**
	 * @param $responseCode
	 * @param $responseBody
	 * @param $result
	 *
	 * @return \WP_Error
	 */
	private function formatErrorMessage( $responseCode, $responseBody, $result ): \WP_Error {
		$message = wp_remote_retrieve_response_message( $result );
		$message .= ! is_null( $responseBody ) && isset( $responseBody->message ) ? ' : ' . $responseBody->message : '';

		$error  = new \WP_Error( $responseCode, $message );
		$logger = new Logger();
		$logger->logError( $error );

		return $error;
	}


	public function listAllCollections() {
		return $this->makeRequest( 'collections' );
	}

	public function getCollectionInfo( $collection_name ) {
		return $this->makeRequest( 'collections/' . $collection_name );
	}

	public function createCollection( $schema = [] ) {
		//when creating schema you should always keep in mind that id is reserved by typesense
		// this will not update the schema once it's created.
		return $this->makeRequest( 'collections', 'POST', $schema );
	}

	public function dropCollection( $schemaIndex ) {
		$collectionName = $this->getCollectionNameFromSchema( $schemaIndex );

		return $this->makeRequest( 'collections/' . $collectionName, 'DELETE' );
	}

	/**
	 * @param       $schemaIndex
	 * @param array $documents
	 *
	 * @return string[]
	 * @throws \JsonException
	 */
	public function bulkUpsertDocuments( $schemaIndex, array $documents ) {

		$collectionName = $this->getCollectionNameFromSchema( $schemaIndex );

		if ( is_array( $documents ) ) {
			$documentsInJSONLFormat = implode(
				"\n",
				array_map(
				/**
				 * @throws \JsonException
				 */ static fn( array $document ) => json_encode( $document, JSON_THROW_ON_ERROR ),
					$documents
				)
			);
		} else {
			$documentsInJSONLFormat = $documents;
		}

		//{"success": true}
		//{"success": false, "error": "Bad JSON.", "document": "[bad doc]"}
		//the calling function will need to search for and then do some error logging
		$resultsInJSONLFormat = $this->makeRequest( 'collections/' . $collectionName . '/documents/import?action=upsert', 'POST', $documentsInJSONLFormat );

		//check if resultsInJSONLFormat is an object
		//happens if you only send one item for bulk udpate
		if ( is_wp_error( $resultsInJSONLFormat ) ) {
			return $resultsInJSONLFormat;
		} elseif ( is_array( $documents ) && ! is_object( $resultsInJSONLFormat ) ) {
			try {

				return array_map( static function ( $item ) {
					return json_decode( $item, true, 512, JSON_THROW_ON_ERROR );
				}, explode( "\n", $resultsInJSONLFormat ) );
			} catch ( \Exception $e ) {
				return $e->getMessage();
			}

		} else {
			return $resultsInJSONLFormat;
		}

	}

	public function upsertDocument( $schemaIndex, $document ) {
		$collectionName = $this->getCollectionNameFromSchema( $schemaIndex );

		return $this->makeRequest( 'collections/' . $collectionName . '/documents?action=upsert', 'POST', $document );
	}

	public function getDocumentByID( $schemaIndex, $docID ) {
		$collectionName = $this->getCollectionNameFromSchema( $schemaIndex );

		return $this->makeRequest( 'collections/' . $collectionName . '/documents/' . $docID, 'GET' );
	}

	/**
	 * @param $collectionName
	 * @param $docID
	 */
	public function deleteDocumentByID( $schemaIndex, $docID ) {
		$collectionName = $this->getCollectionNameFromSchema( $schemaIndex );
		return $this->makeRequest( 'collections/' . $collectionName . '/documents/' . $docID, 'DELETE' );
	}

	public function getDebugInfo() {
		return $this->makeRequest( 'debug' );
	}

	public function getServerHealth() {
		return $this->makeRequest( 'health' );
	}
}
