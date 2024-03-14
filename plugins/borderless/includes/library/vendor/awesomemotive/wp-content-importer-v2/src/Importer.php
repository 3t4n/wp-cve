<?php

namespace AwesomeMotive\WPContentImporter2;

use XMLReader;

class Importer extends WXRImporter {

	private $start_time;

	public function __construct( $options = array(), $logger = null ) {
		parent::__construct( $options );

		$this->set_logger( $logger );

		add_filter( 'wxr_importer.pre_process.post', array( $this, 'new_ajax_request_maybe' ) );

		if ( class_exists( 'WooCommerce' ) ) {
			add_filter( 'wxr_importer.pre_process.term', array( $this, 'woocommerce_product_attributes_registration' ), 10, 1 );
		}
	}

	protected function get_reader( $file ) {

		if ( ! class_exists( 'XMLReader' ) ) {
			$this->logger->critical( __( 'The XMLReader class is missing! Please install the XMLReader PHP extension on your server', 'wordpress-importer' ) );

			return false;
		}

		$reader = new XMLReader();
		$status = $reader->open( $file );

		if ( ! $status ) {
			$this->logger->error( __( 'Could not open the XML file for parsing!', 'wordpress-importer' ) );

			return false;
		}

		return $reader;
	}

	public function get_basic_import_content_data( $file ) {
		$data = array(
			'users'      => false,
			'categories' => false,
			'tags'       => false,
			'terms'      => false,
			'posts'      => false,
		);

		$reader = $this->get_reader( $file );

		if ( empty( $reader ) ) {
			return false;
		}

		while ( $reader->read() ) {
			if ( $reader->nodeType !== XMLReader::ELEMENT ) {
				continue;
			}

			switch ( $reader->name ) {
				case 'wp:author':
					if ( $data['users'] ) {
						$reader->next();
						break;
					}

					$node   = $reader->expand();
					$parsed = $this->parse_author_node( $node );

					if ( is_wp_error( $parsed ) ) {
						$reader->next();
						break;
					}

					$data['users'] = true;

					$reader->next();
					break;

				case 'item':
					if ( $data['posts'] ) {
						$reader->next();
						break;
					}

					$node   = $reader->expand();
					$parsed = $this->parse_post_node( $node );

					if ( is_wp_error( $parsed ) ) {
						$reader->next();
						break;
					}

					$data['posts'] = true;

					$reader->next();
					break;

				case 'wp:category':
					$data['categories'] = true;

					$reader->next();
					break;
				case 'wp:tag':
					$data['tags'] = true;

					$reader->next();
					break;
				case 'wp:term':
					$data['terms'] = true;

					$reader->next();
					break;
			}
		}

		return $data;
	}


	public function get_number_of_posts_to_import( $file ) {
		$reader  = $this->get_reader( $file );
		$counter = 0;

		if ( empty( $reader ) ) {
			return $counter;
		}

		while ( $reader->read() ) {
			if ( $reader->nodeType !== XMLReader::ELEMENT ) {
				continue;
			}

			if ( 'item' == $reader->name ) {
				$node   = $reader->expand();
				$parsed = $this->parse_post_node( $node );

				if ( is_wp_error( $parsed ) ) {
					$reader->next();
					continue;
				}

				$counter++;
			}
		}

		return $counter;
	}

	public function import( $file, $options = array() ) {
		add_filter( 'import_post_meta_key', array( $this, 'is_valid_meta_key' ) );
		add_filter( 'http_request_timeout', array( &$this, 'bump_request_timeout' ) );

		$this->start_time = microtime( true );

		$this->restore_import_data_transient();

		if ( empty( $options ) ) {
			$options = array(
				'users'      => false,
				'categories' => true,
				'tags'       => true,
				'terms'      => true,
				'posts'      => true,
			);
		}

		$result = $this->import_start( $file );

		if ( is_wp_error( $result ) ) {
			$this->logger->error( __( 'Content import start error: ', 'wordpress-importer' ) . $result->get_error_message() );

			return false;
		}

		$reader = $this->get_reader( $file );

		if ( empty( $reader ) ) {
			return false;
		}

		$this->version = '1.0';

		$this->base_url = '';

		while ( $reader->read() ) {
			if ( $reader->nodeType !== XMLReader::ELEMENT ) {
				continue;
			}

			switch ( $reader->name ) {
				case 'wp:wxr_version':
					$this->version = $reader->readString();

					if ( version_compare( $this->version, self::MAX_WXR_VERSION, '>' ) ) {
						$this->logger->warning( sprintf(
							__( 'This WXR file (version %s) is newer than the importer (version %s) and may not be supported. Please consider updating.', 'wordpress-importer' ),
							$this->version,
							self::MAX_WXR_VERSION
						) );
					}

					$reader->next();
					break;

				case 'wp:base_site_url':
					$this->base_url = $reader->readString();

					$reader->next();
					break;

				case 'item':
					if ( empty( $options['posts'] ) ) {
						$reader->next();
						break;
					}

					$node   = $reader->expand();
					$parsed = $this->parse_post_node( $node );

					if ( is_wp_error( $parsed ) ) {
						$this->log_error( $parsed );

						$reader->next();
						break;
					}

					$this->process_post( $parsed['data'], $parsed['meta'], $parsed['comments'], $parsed['terms'] );

					$reader->next();
					break;

				case 'wp:author':
					if ( empty( $options['users'] ) ) {
						$reader->next();
						break;
					}

					$node   = $reader->expand();
					$parsed = $this->parse_author_node( $node );

					if ( is_wp_error( $parsed ) ) {
						$this->log_error( $parsed );

						$reader->next();
						break;
					}

					$status = $this->process_author( $parsed['data'], $parsed['meta'] );

					if ( is_wp_error( $status ) ) {
						$this->log_error( $status );
					}

					$reader->next();
					break;

				case 'wp:category':
					if ( empty( $options['categories'] ) ) {
						$reader->next();
						break;
					}

					$node   = $reader->expand();
					$parsed = $this->parse_term_node( $node, 'category' );

					if ( is_wp_error( $parsed ) ) {
						$this->log_error( $parsed );

						$reader->next();
						break;
					}

					$status = $this->process_term( $parsed['data'], $parsed['meta'] );

					$reader->next();
					break;

				case 'wp:tag':
					if ( empty( $options['tags'] ) ) {
						$reader->next();
						break;
					}

					$node   = $reader->expand();
					$parsed = $this->parse_term_node( $node, 'tag' );

					if ( is_wp_error( $parsed ) ) {
						$this->log_error( $parsed );

						$reader->next();
						break;
					}

					$status = $this->process_term( $parsed['data'], $parsed['meta'] );

					$reader->next();
					break;

				case 'wp:term':
					if ( empty( $options['terms'] ) ) {
						$reader->next();
						break;
					}

					$node   = $reader->expand();
					$parsed = $this->parse_term_node( $node );

					if ( is_wp_error( $parsed ) ) {
						$this->log_error( $parsed );

						$reader->next();
						break;
					}

					$status = $this->process_term( $parsed['data'], $parsed['meta'] );

					$reader->next();
					break;

				default:
					break;
			}
		}

		$this->post_process();

		if ( $this->options['aggressive_url_search'] ) {
			$this->replace_attachment_urls_in_content();
		}

		$this->remap_featured_images();

		$this->import_end();

		$this->set_current_importer_data();

		return true;
	}

	public function import_users( $file ) {
		return $this->import( $file, array( 'users' => true ) );
	}

	public function import_categories( $file ) {
		return $this->import( $file, array( 'categories' => true ) );
	}

	public function import_tags( $file ) {
		return $this->import( $file, array( 'tags' => true ) );
	}

	public function import_terms( $file ) {
		return $this->import( $file, array( 'terms' => true ) );
	}

	public function import_posts( $file ) {
		return $this->import( $file, array( 'posts' => true ) );
	}

	public function new_ajax_request_maybe( $data ) {
		$time = microtime( true ) - $this->start_time;

		if ( $time > apply_filters( 'pt-importer/time_for_one_ajax_call', 20 ) ) {
			$response = apply_filters( 'pt-importer/new_ajax_request_response_data', array(
				'status'                => 'newAJAX',
				'log'                   => 'Time for new AJAX request!: ' . $time,
				'num_of_imported_posts' => count( $this->mapping['post'] ),
			) );

			$this->logger->info( __( 'New AJAX call!', 'wordpress-importer' ) );

			$this->set_current_importer_data();

			wp_send_json( $response );
		}

		$current_user_obj    = wp_get_current_user();
		$data['post_author'] = $current_user_obj->user_login;

		return $data;
	}

	public function set_current_importer_data() {
		$data = apply_filters( 'pt-importer/set_current_importer_data', array(
			'options'            => $this->options,
			'mapping'            => $this->mapping,
			'requires_remapping' => $this->requires_remapping,
			'exists'             => $this->exists,
			'user_slug_override' => $this->user_slug_override,
			'url_remap'          => $this->url_remap,
			'featured_images'    => $this->featured_images,
		) );

		$this->save_current_import_data_transient( $data );
	}

	public function save_current_import_data_transient( $data ) {
		set_transient( 'pt_importer_data', $data, MINUTE_IN_SECONDS );
	}

	public function restore_import_data_transient() {
		if ( $data = get_transient( 'pt_importer_data' ) ) {
			$this->options            = empty( $data['options'] ) ? array() : $data['options'];
			$this->mapping            = empty( $data['mapping'] ) ? array() : $data['mapping'];
			$this->requires_remapping = empty( $data['requires_remapping'] ) ? array() : $data['requires_remapping'];
			$this->exists             = empty( $data['exists'] ) ? array() : $data['exists'];
			$this->user_slug_override = empty( $data['user_slug_override'] ) ? array() : $data['user_slug_override'];
			$this->url_remap          = empty( $data['url_remap'] ) ? array() : $data['url_remap'];
			$this->featured_images    = empty( $data['featured_images'] ) ? array() : $data['featured_images'];

			do_action( 'pt-importer/restore_import_data_transient' );

			return true;
		}

		return false;
	}

	public function get_mapping() {
		return $this->mapping;
	}

	public function woocommerce_product_attributes_registration( $data ) {
		global $wpdb;

		if ( strstr( $data['taxonomy'], 'pa_' ) ) {
			if ( ! taxonomy_exists( $data['taxonomy'] ) ) {
				$attribute_name = wc_sanitize_taxonomy_name( str_replace( 'pa_', '', $data['taxonomy'] ) );

				if ( ! in_array( $attribute_name, wc_get_attribute_taxonomies() ) ) {
					$attribute = array(
						'attribute_label'   => $attribute_name,
						'attribute_name'    => $attribute_name,
						'attribute_type'    => 'select',
						'attribute_orderby' => 'menu_order',
						'attribute_public'  => 0
					);
					$wpdb->insert( $wpdb->prefix . 'woocommerce_attribute_taxonomies', $attribute );
					delete_transient( 'wc_attribute_taxonomies' );
				}

				register_taxonomy(
					$data['taxonomy'],
					apply_filters( 'woocommerce_taxonomy_objects_' . $data['taxonomy'], array( 'product' ) ),
					apply_filters( 'woocommerce_taxonomy_args_' . $data['taxonomy'], array(
						'hierarchical' => true,
						'show_ui'      => false,
						'query_var'    => true,
						'rewrite'      => false,
					) )
				);
			}
		}

		return $data;
	}
}
