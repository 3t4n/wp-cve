<?php

namespace CatFolders\Internals;

use CatFolders\Models\FolderModel;
use CatFolders\Classes\Helpers;

use CatFolders\Core\Base;

class WPMedia extends Base {
	public function __construct() {
		parent::initialize();

		$this->loadModules(
			array(
				'MediaMeta',
			)
		);

		add_filter( 'media_library_infinite_scrolling', '__return_true' );
		add_filter( 'ajax_query_attachments_args', array( $this, 'ajaxQueryAttachmentsArgs' ), 20 );
		add_filter( 'mla_media_modal_query_final_terms', array( $this, 'ajaxQueryAttachmentsArgs' ), 20 );
		add_filter( 'restrict_manage_posts', array( $this, 'restrictManagePosts' ) );
		add_filter( 'posts_clauses', array( $this, 'postsClauses' ), 10, 2 );

		add_action( 'add_attachment', array( $this, 'addAttachment' ) );
		add_action( 'delete_attachment', array( $this, 'deleteAttachment' ) );
		add_action( 'pre-upload-ui', array( $this, 'preUploadUi' ) );
	}

	public function ajaxQueryAttachmentsArgs( $query ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_REQUEST['query']['catf'] ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$query['catf'] = Helpers::sanitize_intval_array( $_REQUEST['query']['catf'] );
		}
		return $query;
	}

	public function loadModules( $modules ) {
		foreach ( $modules as $module ) {
			$module_class = __NAMESPACE__ . "\\Modules\\{$module}";
			$module_obj   = $module_class::instance();
		}
	}

	public function preUploadUi() {
		?>
<div class="catf-upload-inline">
	<label for="catf"><?php esc_html_e( 'Choose folder: ', 'catfolders' ); ?></label>
	<div id="catf-folder-selector" class="catf-folder-selector" name="catf"></div>
</div>
		<?php
	}

	public function postsClauses( $clauses, $query ) {
		global $wpdb;

		$postTypeValidator = apply_filters( 'catf_post_type_validator', array( 'attachment' ) );

		if ( ! in_array( $query->get( 'post_type' ), $postTypeValidator ) ) {
			return $clauses;
		}

		$catf = null;
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$catf_GET = isset( $_GET['catf'] ) ? intval( $_GET['catf'] ) : null;

		if ( '' !== $query->get( 'catf' ) ) {
			$catf = intval( $query->get( 'catf' ) );
		}

		if ( Helpers::isListMode() ) {
			$defaultFolder   = $this->userSettings['startupFolder'];
			$defaultSortFile = $this->userSettings['sortFile'];
			if ( $defaultSortFile ) {
				if ( '' === $query->get( 'orderby' ) ) {
					$clauses['orderby'] = Helpers::AutoOrderInListMode( $defaultSortFile );
				}
			}
			if ( ! \is_null( $catf_GET ) ) {
				$catf = $catf_GET;
			} else {
				$catf = $defaultFolder;
			}
		}

		if ( ! \is_null( $catf ) ) {
			if ( -1 === $catf ) {
				return $clauses;
			} elseif ( 0 === $catf ) {
				$clauses = FolderModel::getRelationsWithFolderUser( $clauses );
			} else {
				$clauses['join']  .= $wpdb->prepare( " LEFT JOIN {$wpdb->prefix}catfolders_posts AS catf_af ON catf_af.post_id = {$wpdb->posts}.ID AND catf_af.folder_id = %d ", $catf );
				$clauses['where'] .= ' AND catf_af.folder_id IS NOT NULL';
			}
		}
		return $clauses;
	}

	public function restrictManagePosts() {
		$screen = get_current_screen();

		if ( 'upload' === $screen->id ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$catf    = ( ( isset( $_GET['catf'] ) ) ? intval( $_GET['catf'] ) : -1 );
			$folders = FolderModel::get_all( null, true );
			$folders = $folders['tree'];

			array_unshift(
				$folders,
				(object) array(
					'id'    => -1,
					'title' => __( 'All Folders', 'catfolders' ),
				),
				(object) array(
					'id'    => 0,
					'title' => __( 'Uncategorized', 'catfolders' ),
				)
			);

			echo '<select name="catf" id="filter-by-catf" class="catf-filter attachment-filters catf">';
			foreach ( $folders as $k => $folder ) {
				echo sprintf( '<option value="%1$d" %3$s>%2$s</option>', esc_attr( $folder->id ), esc_html( $folder->title ), selected( $folder->id, $catf, false ) );
			}
			echo '</select>';
		}
	}

	public function addAttachment( $post_id ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$catf = ( ( isset( $_REQUEST['catf'] ) ) ? Helpers::sanitize_array( $_REQUEST['catf'] ) : '' );
		if ( '' !== $catf ) {
			if ( is_numeric( $catf ) ) {
				$parent = $catf;
			} else {
				$catf   = explode( '/', ltrim( rtrim( $catf, '/' ), '/' ) );
				$parent = (int) $catf[0];
				if ( $parent < 0 ) {
					$parent = 0; //important
				}
				unset( $catf[0] );
				foreach ( $catf as $k => $v ) {
					$parent = FolderModel::create_or_get( $v, $parent );
				}
			}
			FolderModel::set_attachments( $parent, array( $post_id ), false );
		}
	}

	public function deleteAttachment( $post_id ) {
		FolderModel::unset_attachment( $post_id );
	}
}
