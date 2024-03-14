<?php
namespace CatFolders\Integrations;

defined( 'ABSPATH' ) || exit;

use CatFolders\Models\FolderModel;

class MediaLibraryAssistant {
	public function __construct() {
		add_filter( 'catf_post_type_validator', array( $this, 'post_type_validator' ), 10, 1 );
		add_filter( 'mla_list_table_extranav_actions', array( $this, 'table_extranav_actions' ), 10, 2 );
		add_action( 'mla_list_table_extranav_custom_action', array( $this, 'extranav_custom_action' ), 10, 2 );
		add_filter( 'mla_list_table_submenu_arguments', array( $this, 'mla_list_table_submenu_arguments' ), 10, 2 );
	}

	public function post_type_validator( $post_types ) {
		$post_types[] = 'mladisabletaxjoin';
		return $post_types;
	}

	public function table_extranav_actions( $actions, $which ) {
		$actions[] = 'catf';
		return $actions;
	}

	public function mla_list_table_submenu_arguments( $submenu_arguments, $include_filters ) {
		if ( isset( $_REQUEST['catf'] ) ) {
			$submenu_arguments['catf'] = intval( $_REQUEST['catf'] );
		}

		return $submenu_arguments;
	}

	public function extranav_custom_action( $action, $which ) {
		if ( 'catf' === $action && 'top' === $which ) {
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
}
