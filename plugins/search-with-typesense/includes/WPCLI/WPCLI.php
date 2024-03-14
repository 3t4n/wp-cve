<?php

namespace Codemanas\Typesense\WPCLI;

use Codemanas\Typesense\Main\TypesenseAPI;
use Codemanas\Typesense\Backend\Admin;

class WPCLI {
	public static ?WPCLI $instance = null;
	private ?TypesenseAPI $typeSenseAPIInterface;

	public static $posts_per_page = 40;

	public static function getInstance(): ?WPCLI {
		return is_null( self::$instance ) ? self::$instance = new self() : self::$instance;
	}

	public function __construct() {
		add_action( 'cli_init', [ $this, 'register_commands' ] );
		$this->typeSenseAPIInterface = TypesenseAPI::getInstance();
	}

	public function index( $args, $assoc_args ) {
		if ( ! isset( $args[0] ) ) {
			\WP_CLI::error( __( 'Please specify the post type which you want to index.', 'search-with-typesense' ) );
		}

		$post_type = $args[0];
		if ( isset( $assoc_args['ids'] ) ) {
			$post_ids_arr = explode( ',', $assoc_args['ids'] );

			foreach ( $post_ids_arr as $post_id ) {
				$post = get_post( $post_id );

				$document = $this->typeSenseAPIInterface->formatDocumentForEntry( $post, $post_id, $post_type );
				$response = $this->typeSenseAPIInterface->upsertDocument( $post_type, $document );

				if ( is_wp_error( $response ) ) {
					\WP_CLI::error( $response );
				} else {
					\WP_CLI::success( $post_type . ' ID: ' . $post_id . ' indexed successfully!!!' );
				}
			}
		} else {
			$request_body = file_get_contents( 'php://input' );
			$posted_data  = json_decode( $request_body, true );

			$posted_data['post_type'] = $post_type;

            if( isset( $assoc_args['posts_per_page'] ) && absint( $assoc_args['posts_per_page'] ) > 0  ) {
                self::$posts_per_page = absint( $assoc_args['posts_per_page'] );
            }

			add_filter( 'cm_typesense_bulk_posts_per_page', [ __CLASS__, 'modify_posts_per_page' ] );
			$posted_data['offset'] = 0;
			$adminInstance         = Admin::getInstance();
			$response              = $adminInstance->bulkImportPosts( $posted_data, false );
			$count = 0;
			while ( $response['status'] == 'success' && $posted_data['offset'] <= $response['addInfo']['offset'] ) {
				$indexed_posts = ($response['addInfo']['offset'] >= $response['addInfo']['total_posts']) ? $response['addInfo']['total_posts'] : $response['addInfo']['offset'];
				echo $indexed_posts.' of '. $response['addInfo']['total_posts'] .' total documents for index '.$post_type.' have been indexed'.PHP_EOL;
				$posted_data['offset'] = $response['addInfo']['offset'];
				$response              = $adminInstance->bulkImportPosts( $posted_data, false );
			}
		}

	}

	public static function modify_posts_per_page() {
		return self::$posts_per_page;
	}

	public function delete( $args, $assoc_args ) {
		if ( ! isset( $args[0] ) ) {
			\WP_CLI::error( __( 'Please specify the post type which you want to delete.', 'search-with-typesense' ) );
		}

		$post_type    = $args[0];
		$post_ids_arr = explode( ',', $assoc_args['ids'] );

		foreach ( $post_ids_arr as $post_id ) {
			$post = get_post( $post_id );

			$response = $this->typeSenseAPIInterface->deleteDocumentByID( $post_type, $post_id );

			if ( is_wp_error( $response ) ) {
				\WP_CLI::error( $response );
			} else {
				\WP_CLI::success( $post_type . ' ID: ' . $post_id . ' deleted!!!' );
			}
		}
	}

	public function health() {
		$health = $this->typeSenseAPIInterface->getServerHealth();
		if ( is_wp_error( $health ) ) {
			\WP_CLI::error( $health );
		} else {
			\WP_CLI::success( 'Health: okay!!!' );
		}
	}

	/**
	 * Registers our command when cli get's initialized.
	 *
	 * @since  1.0.0
	 * @author Scott Anderson
	 */
	function register_commands() {
		\WP_CLI::add_command( 'typesense index', [ $this, 'index' ] );
		\WP_CLI::add_command( 'typesense health', [ $this, 'health' ] );
		\WP_CLI::add_command( 'typesense delete', [ $this, 'delete' ] );
	}
}
