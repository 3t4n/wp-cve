<?php
/**
 * Organize Media Folder
 *
 * @package    Organize Media Folder
 * @subpackage OrganizeMediaFolder Main function
/*  Copyright (c) 2020- Katsushi Kawamori (email : dodesyoswift312@gmail.com)
	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; version 2 of the License.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

$organizemediafolder = new OrganizeMediaFolder();

/** ==================================================
 * Class Main function
 *
 * @since 1.00
 */
class OrganizeMediaFolder {

	/** ==================================================
	 * Path
	 *
	 * @var $upload_dir  upload_dir.
	 */
	private $upload_dir;

	/** ==================================================
	 * Path
	 *
	 * @var $upload_url  upload_url.
	 */
	private $upload_url;

	/** ==================================================
	 * Path
	 *
	 * @var $upload_path  upload_path.
	 */
	private $upload_path;

	/** ==================================================
	 * Construct
	 *
	 * @since 1.00
	 */
	public function __construct() {

		/* Register Taxonomy */
		add_action( 'init', array( $this, 'omf_folders_taxonomies' ), 10 );

		/* Media Library folder filter */
		add_action( 'restrict_manage_posts', array( $this, 'add_folder_filter' ), 13 );
		add_action( 'wp_enqueue_media', array( $this, 'insert_media_custom_filter' ) );

		/* Media Library Column filter */
		add_filter( 'manage_media_columns', array( $this, 'muc_column' ) );
		add_action( 'manage_media_custom_column', array( $this, 'muc_value' ), 10, 2 );

		/* For upload */
		add_filter( 'upload_dir', array( $this, 'per_user_upload_dir' ), 10, 1 );
		add_action( 'wp_generate_attachment_metadata', array( $this, 'generate_metadata' ), 10, 2 );

		/* Folder change ajax */
		$action = 'omf_folder';
		add_action( 'wp_ajax_' . $action, array( $this, 'omf_fc_callback' ) );

		/* Original hook */
		add_action( 'omf_dirs_tree', array( $this, 'omf_dirs' ), 10, 1 );
		add_action( 'omf_folders_term_create', array( $this, 'omf_folders_term' ) );
		add_action( 'omf_folders_term_new', array( $this, 'omf_folders_new' ), 10, 1 );
		add_action( 'omf_folders_term_change', array( $this, 'omf_folders_change' ), 10, 2 );
		add_action( 'omf_folders_term_update', array( $this, 'omf_folders_update' ), 10, 2 );
		add_action( 'omf_term_filter_update', array( $this, 'term_filter_update' ) );
		add_action( 'omf_filter_form', array( $this, 'filter_form' ), 10, 1 );
		add_action( 'omf_upload_folder_select', array( $this, 'upload_folder_select' ) );
		add_action( 'omf_bulk_select', array( $this, 'bulk_select' ) );
		add_action( 'omf_per_page_set', array( $this, 'per_page_set' ), 10, 1 );
		add_filter( 'omf_folder_move_regist', array( $this, 'regist' ), 10, 2 );
		add_filter( 'omf_mb_utf8', array( $this, 'mb_utf8' ), 10, 1 );
		add_filter( 'omf_dir_selectbox', array( $this, 'dir_selectbox' ), 10, 1 );
		add_filter( 'omf_dir_selectbox_admin_bar', array( $this, 'dir_selectbox_admin_bar' ), 10, 1 );
		add_action( 'omf_admin_upload_folders_select', array( $this, 'dir_select_folders_admin_bar' ) );

		/* Upload paths */
		$wp_uploads = wp_upload_dir();
		$upload_dir = wp_normalize_path( $wp_uploads['basedir'] );
		$upload_url = $wp_uploads['baseurl'];
		if ( is_ssl() ) {
			$upload_url = str_replace( 'http:', 'https:', $upload_url );
		}
		$upload_path = str_replace( site_url( '/' ), '', $upload_url );
		$this->upload_dir  = untrailingslashit( $upload_dir );
		$this->upload_url  = untrailingslashit( $upload_url );
		$this->upload_path = untrailingslashit( $upload_path );
	}

	/** ==================================================
	 * Per user upload dirctory
	 *
	 * @param array $original  original.
	 * @return array $modified
	 * @since 1.00
	 */
	public function per_user_upload_dir( $original ) {

		$omf_admin_settings = get_option( 'omf_admin' );
		if ( empty( $omf_admin_settings['subdir'] ) ) {
			$omf_admin_settings['subdir'] = '/';
		}

		$original['subdir'] = $omf_admin_settings['subdir'];
		$original['path'] = $original['basedir'] . $original['subdir'];
		$original['url'] = $original['baseurl'] . $original['subdir'];
		$modified = $original;

		return $modified;
	}

	/** ==================================================
	 * Generate metadata
	 *
	 * @param array $metadata  metadata.
	 * @param int   $attach_id  ID.
	 * @return array $metadata  metadata.
	 * @since 1.00
	 */
	public function generate_metadata( $metadata, $attach_id ) {

		$omf_admin_settings = get_option( 'omf_admin' );

		/* Date Time Regist */
		$dateset = $omf_admin_settings['dateset'];
		if ( function_exists( 'wp_date' ) ) {
			$postdategmt = wp_date( 'Y-m-d H:i:s', null, new DateTimeZone( 'UTC' ) );
		} else {
			$postdategmt = date_i18n( 'Y-m-d H:i:s', false, true );
		}
		if ( 'exif' === $dateset ) {
			$datetime = apply_filters( 'omf_exif_date', $attach_id );
			if ( $datetime ) {
				$postdategmt = get_gmt_from_date( $datetime );
			}
		}
		if ( 'new' <> $dateset ) {
			if ( 'fixed' === $dateset ) {
				$postdategmt = get_gmt_from_date( $omf_admin_settings['datefixed'] );
			}
			$postdate = get_date_from_gmt( $postdategmt );
			$up_post = array(
				'ID' => $attach_id,
				'post_date' => $postdate,
				'post_date_gmt' => $postdategmt,
				'post_modified' => $postdate,
				'post_modified_gmt' => $postdategmt,
			);
			wp_update_post( $up_post );
		}

		/* for Add on exif caption */
		do_action( 'omf_exifcaption_regist', $metadata, $attach_id );

		/* for Media Library folders term */
		do_action( 'omf_folders_term_update', $metadata, $attach_id );

		/* for Term filter update */
		do_action( 'omf_term_filter_update' );

		return $metadata;
	}

	/** ==================================================
	 * Media Library Search Filter for folders
	 *
	 * @since 1.00
	 */
	public function add_folder_filter() {

		global $wp_list_table;

		if ( empty( $wp_list_table->screen->post_type ) &&
			isset( $wp_list_table->screen->parent_file ) &&
			'upload.php' == $wp_list_table->screen->parent_file ) {
			$wp_list_table->screen->post_type = 'attachment';
		}

		if ( is_object_in_taxonomy( $wp_list_table->screen->post_type, 'omf_folders' ) ) {
			$nonce = null;
			$get_media_folder = null;
			if ( isset( $_REQUEST['medialibrary_folders_filter'] ) && ! empty( $_REQUEST['medialibrary_folders_filter'] ) ) {
				$nonce = sanitize_text_field( wp_unslash( $_REQUEST['medialibrary_folders_filter'] ) );
			}
			if ( wp_verify_nonce( $nonce, 'omf_media_folders_filter' ) ) {
				if ( isset( $_REQUEST['omf_folders'] ) && ! empty( $_REQUEST['omf_folders'] ) ) {
					$get_media_folder = sanitize_text_field( wp_unslash( $_REQUEST['omf_folders'] ) );
				}
			}
			wp_nonce_field( 'omf_media_folders_filter', 'medialibrary_folders_filter' );
			?>
			<select name="omf_folders">
				<option value="" 
				<?php
				if ( empty( $get_media_folder ) ) {
					echo 'selected="selected"';}
				?>
				><?php esc_html_e( 'All Folders', 'organize-media-folder' ); ?></option>
				<?php
				$args = array(
					'taxonomy'   => 'omf_folders',
					'hide_empty' => true,
				);
				$terms = get_terms( $args );
				foreach ( $terms as $term ) {
					?>
					<option value="<?php echo esc_attr( $term->slug ); ?>" 
						<?php
						if ( $get_media_folder == $term->slug ) {
							echo 'selected="selected"';
						}
						?>
					>
					<?php echo esc_html( $term->name ); ?>
					</option>
					<?php
				}
				?>
			</select>
			<?php
		}
	}

	/** ==================================================
	 * Insert Media Custom Filter enqueue
	 *
	 * @since 1.00
	 */
	public function insert_media_custom_filter() {

		wp_enqueue_script( 'media-library-omf-taxonomy-filter', plugin_dir_url( __DIR__ ) . 'js/collection-filter.js', array( 'media-editor', 'media-views' ), '1.00', false );
		$dirs = array();
		$args = array(
			'taxonomy'   => 'omf_folders',
			'hide_empty' => true,
		);
		$terms = get_terms( $args );
		foreach ( $terms as $term ) {
			$dirs['terms'][ $term->slug ] = array(
				'name' => $term->name,
				'slug' => $term->slug,
			);
		}
		wp_localize_script(
			'media-library-omf-taxonomy-filter',
			'MediaLibraryOmfTaxonomyFilterData',
			$dirs
		);
		wp_localize_script(
			'media-library-omf-taxonomy-filter',
			'MediaLibraryOmfTaxonomyFilterDataText',
			array(
				'all_folders' => __( 'All Folders', 'organize-media-folder' ),
			)
		);

		add_action( 'admin_footer', array( $this, 'insert_media_custom_filter_styling' ) );
	}

	/** ==================================================
	 * Insert Media Custom Filter style
	 *
	 * @since 1.00
	 */
	public function insert_media_custom_filter_styling() {

		?>
		<style>
		.media-modal-content .media-frame select.attachment-filters {
			max-width: -webkit-calc(33% - 12px);
			max-width: calc(33% - 12px);
		}
		select#media-library-omf-taxonomy-filter {
			max-width: -webkit-calc(33% - 12px);
			max-width: calc(33% - 12px);
		}
		</style>
		<?php
	}

	/** ==================================================
	 * Media Library Column
	 *
	 * @param array $cols  cols.
	 * @return array $cols
	 * @since 1.07
	 */
	public function muc_column( $cols ) {

		global $pagenow;
		if ( 'upload.php' == $pagenow ) {
			$def_cols = $cols;
			unset( $def_cols['cb'] );
			unset( $def_cols['title'] );
			$new_cols['cb'] = $cols['cb'];
			$new_cols['title'] = $cols['title'];
			$new_cols['omf_folder_list'] = __( 'Folder', 'organize-media-folder' );
			$cols = array_merge( $new_cols, $def_cols );
		}

		return $cols;
	}

	/** ==================================================
	 * Media Library Column
	 *
	 * @param string $column_name  column_name.
	 * @param int    $id  id.
	 * @since 1.07
	 */
	public function muc_value( $column_name, $id ) {

		if ( 'omf_folder_list' == $column_name ) {
			$folder_name = wp_get_object_terms( $id, 'omf_folders', array( 'fields' => 'names' ) );
			if ( ! empty( $folder_name ) ) {
				?>
				<div><?php echo esc_html( $folder_name[0] ); ?></div>
				<?php
			}
		}
	}

	/** ==================================================
	 * Filter form
	 *
	 * @param int $uid  current user id.
	 * @since 1.00
	 */
	public function filter_form( $uid ) {

		$scriptname = admin_url( 'upload.php?page=organizemediafolder' );

		?>
		<div style="margin: 0px 0px 0px 60px; padding: 5px;">
		<form method="post" action="<?php echo esc_url( $scriptname ); ?>">
		<?php
		wp_nonce_field( 'omf_filter', 'organize_media_folder_filter' );

		if ( current_user_can( 'manage_options' ) ) {
			$users = get_users(
				array(
					'orderby' => 'nicename',
					'order' => 'ASC',
				)
			);

			$user_filter = get_user_option( 'omf_filter_user', $uid );
			?>
			<select name="user_id">
			<?php
			$selected_user = false;
			foreach ( $users as $user ) {
				if ( user_can( $user->ID, 'upload_files' ) ) {
					if ( $user_filter == $user->ID ) {
						?>
						<option value="<?php echo esc_attr( $user->ID ); ?>" selected><?php echo esc_html( $user->display_name ); ?></option>
						<?php
						$selected_user = true;
					} else {
						?>
						<option value="<?php echo esc_attr( $user->ID ); ?>"><?php echo esc_html( $user->display_name ); ?></option>
						<?php
					}
				}
			}
			if ( ! $selected_user ) {
				?>
				<option value="" selected><?php esc_html_e( 'All users', 'organize-media-folder' ); ?></option>
				<?php
			} else {
				?>
				<option value=""><?php esc_html_e( 'All users', 'organize-media-folder' ); ?></option>
				<?php
			}
			?>
			</select>
			<?php
		}

		$ext_mime = array();
		$mimes = get_allowed_mime_types( $uid );
		foreach ( $mimes as $type => $mime ) {
			$types = explode( '|', $type );
			foreach ( $types as $value ) {
				$ext_mime[ $value ] = $mime;
			}
		}
		$type_mime = array();
		$type_text = array();
		$type_exts_arr = wp_get_ext_types();
		foreach ( $type_exts_arr as $type => $exts ) {
			$ext_mimes = array();
			foreach ( $exts as $value ) {
				if ( array_key_exists( $value, $ext_mime ) ) {
					$ext_mimes[] = $ext_mime[ $value ];
				}
			}
			$ext_mimes = array_filter( $ext_mimes );
			$ext_mimes_csv = implode( ',', $ext_mimes );
			if ( '' == $type ) {
				$type = 'other';
			}
			$type_mime[ $type ] = $ext_mimes_csv;
			switch ( $type ) {
				case 'image':
					$type_text[ $type ] = __( 'Image' );
					break;
				case 'audio':
					$type_text[ $type ] = __( 'Audio' );
					break;
				case 'video':
					$type_text[ $type ] = __( 'Video' );
					break;
				case 'document':
					$type_text[ $type ] = __( 'Document', 'organize-media-folder' );
					break;
				case 'spreadsheet':
					$type_text[ $type ] = __( 'Spreadsheet', 'organize-media-folder' );
					break;
				case 'interactive':
					$type_text[ $type ] = __( 'Interactive', 'organize-media-folder' );
					break;
				case 'text':
					$type_text[ $type ] = __( 'Text' );
					break;
				case 'archive':
					$type_text[ $type ] = __( 'Archive', 'organize-media-folder' );
					break;
				case 'code':
					$type_text[ $type ] = __( 'Code' );
					break;
				case 'other':
					$type_text[ $type ] = __( 'Other', 'organize-media-folder' );
					break;
				default:
					$type_text[ $type ] = $type;
					break;
			}
		}

		$mime_filter = get_user_option( 'omf_filter_mime_type', get_current_user_id() );
		?>
		<select name="mime_type">
		<?php
		$selected_mime_type = false;
		foreach ( $type_mime as $type => $mime ) {
			if ( $mime_filter === $mime ) {
				?>
				<option value="<?php echo esc_attr( $mime ); ?>" selected><?php echo esc_html( $type_text[ $type ] ); ?></option>
				<?php
				$selected_mime_type = true;
			} else {
				?>
				<option value="<?php echo esc_attr( $mime ); ?>"><?php echo esc_html( $type_text[ $type ] ); ?></option>
				<?php
			}
		}
		if ( ! $selected_mime_type ) {
			?>
			<option value="" selected><?php esc_html_e( 'All media items' ); ?></option>
			<?php
		} else {
			?>
			<option value=""><?php esc_html_e( 'All media items' ); ?></option>
			<?php
		}
		?>
		</select>
		<?php

		global $wpdb;
		$attachments = $wpdb->get_col(
			"
				SELECT	ID
				FROM	{$wpdb->prefix}posts
				WHERE	post_type = 'attachment'
				ORDER BY post_date DESC
			"
		);

		if ( ! empty( $attachments ) ) {
			foreach ( $attachments as $pid ) {
				$year = get_the_time( 'Y', $pid );
				$month = get_the_time( 'F', $pid );
				/* translators: month year for media archive */
				$year_month = sprintf( __( '%1$s %2$s', 'organize-media-folder' ), $month, $year );
				$archive_list[ $year_month ][] = $pid;
			}
			$monthly_filter = get_user_option( 'omf_filter_monthly', get_current_user_id() );
			?>
			<select name="monthly">
			<?php
			$selected_monthly = false;
			foreach ( $archive_list as $key => $value ) {
				$pid_csv = implode( ',', $value );
				if ( $value == $monthly_filter ) {
					?>
					<option value="<?php echo esc_attr( $pid_csv ); ?>" selected><?php echo esc_html( $key ); ?></option>
					<?php
					$selected_monthly = true;
				} else {
					?>
					<option value="<?php echo esc_attr( $pid_csv ); ?>"><?php echo esc_html( $key ); ?></option>
					<?php
				}
			}
			if ( ! $selected_monthly ) {
				?>
				<option value="" selected><?php esc_html_e( 'All dates' ); ?></option>
				<?php
			} else {
				?>
				<option value=""><?php esc_html_e( 'All dates' ); ?></option>
				<?php
			}
			?>
			</select>
			<?php
		}

		$dir_terms = wp_get_object_terms( $attachments, 'omf_folders', array() );
		$term_filter = get_user_option( 'omf_filter_term', get_current_user_id() );
		?>
		<select name="term_type">
		<?php
		$selected_term = false;
		$all_slug_arr = array();
		foreach ( $dir_terms as $key => $value ) {
			if ( 1 == count( $term_filter ) ) {
				if ( $term_filter[0] === $value->slug ) {
					?>
					<option value="<?php echo esc_attr( $value->slug ); ?>" selected><?php echo esc_html( $value->name ); ?></option>
					<?php
					$selected_term = true;
				} else {
					?>
					<option value="<?php echo esc_attr( $value->slug ); ?>"><?php echo esc_html( $value->name ); ?></option>
					<?php
				}
			} else {
				?>
				<option value="<?php echo esc_attr( $value->slug ); ?>"><?php echo esc_html( $value->name ); ?></option>
				<?php
			}
			$all_slug_arr[] = $value->slug;
		}
		$all_slug_csv = implode( ',', $all_slug_arr );
		if ( ! $selected_term ) {
			?>
			<option value="<?php echo esc_attr( $all_slug_csv ); ?>" selected><?php esc_html_e( 'All Folders', 'organize-media-folder' ); ?></option>
			<?php
		} else {
			?>
			<option value="<?php echo esc_attr( $all_slug_csv ); ?>"><?php esc_html_e( 'All Folders', 'organize-media-folder' ); ?></option>
			<?php
		}
		?>
		</select>

		<?php
		$search_text = get_user_option( 'omf_search_text', $uid );
		if ( ! $search_text ) {
			?>
			<input style="vertical-align: middle;" name="search_text" type="text" value="" placeholder="<?php esc_attr_e( 'Search' ); ?>">
			<?php
		} else {
			?>
			<input style="vertical-align: middle;" name="search_text" type="text" value="<?php echo esc_attr( $search_text ); ?>">
			<?php
		}

		submit_button( __( 'Filter' ), 'large', 'organize-media-folder-filter', false );
		?>
		</form>
		</div>
		<?php
	}

	/** ==================================================
	 * Upload folder select form
	 *
	 * @since 1.00
	 */
	public function upload_folder_select() {

		$omf_admin_settings = get_option( 'omf_admin' );

		$html = '<select name="subdir" style="font-size: small; text-align: left;">';
		$html .= '<option value="">' . __( 'Select' ) . '</option>';
		$html .= apply_filters( 'omf_dir_selectbox', $omf_admin_settings['subdir'] );
		$html .= '</select>';

		$allowed_html = array(
			'select'  => array(
				'name'  => array(),
				'style'  => array(),
			),
			'option'  => array(
				'value'  => array(),
				'select'  => array(),
				'selected'  => array(),
			),
		);

		echo wp_kses( $html, $allowed_html );
	}

	/** ==================================================
	 * Bulk input form
	 *
	 * @since 1.00
	 */
	public function bulk_select() {

		$allowed_html = array(
			'select'  => array(
				'name'  => array(),
				'style'  => array(),
			),
			'option'  => array(
				'value'  => array(),
				'select'  => array(),
				'selected'  => array(),
			),
		);

		?>
		<div style="margin: 0px; text-align: right;">
		<?php esc_html_e( 'Bulk Folder Select', 'organize-media-folder' ); ?> : 
		<select name="bulk_folder" style="font-size: small; text-align: left;">
		<option value=""><?php esc_html_e( 'Select' ); ?></option>
		<?php echo wp_kses( apply_filters( 'omf_dir_selectbox', null ), $allowed_html ); ?>
		</select>
		<?php submit_button( __( 'Change' ), 'large', 'all_change', false ); ?>
		</div>
		<?php
	}

	/** ==================================================
	 * Per page input form
	 *
	 * @param int $uid  user ID.
	 * @since 1.00
	 */
	public function per_page_set( $uid ) {

		?>
		<div style="margin: 0px; text-align: right;">
			<?php esc_html_e( 'Number of items per page:' ); ?><input type="number" step="1" min="1" max="9999" style="width: 80px;" name="per_page" value="<?php echo esc_attr( get_user_option( 'omf_per_page', $uid ) ); ?>" form="organizemediafolder_settings" />
			<?php submit_button( __( 'Change' ), 'large', 'per_page_change', false, array( 'form' => 'organizemediafolder_settings' ) ); ?>
		</div>
		<?php
	}

	/** ==================================================
	 * Regist
	 *
	 * @param int    $re_id_attache  re_id_attache.
	 * @param string $target_folder  target_folder.
	 * @since 1.00
	 */
	public function regist( $re_id_attache, $target_folder ) {

		if ( '/' <> $target_folder ) {
			$target_folder = trailingslashit( $target_folder );
		}
		$file = get_post_meta( $re_id_attache, '_wp_attached_file', true );
		$filename = wp_basename( $file );
		$current_folder = '/' . str_replace( $filename, '', $file );
		$exts = explode( '.', $filename );
		$ext = end( $exts );

		if ( $target_folder === $current_folder ) {
			$message = array(
				'result' => 'error',
				'ID' => $re_id_attache,
				'filename' => $filename,
				'current' => $current_folder,
				'target' => $target_folder,
				/* translators: Error message */
				'error' => sprintf( __( '%1$s cannot be moved. The folder name is the same.', 'organize-media-folder' ), $filename ),
			);
			return $message;
		}

		$re_attache = get_post( $re_id_attache );
		$new_attach_title = $re_attache->post_title;

		$current_file = $this->upload_dir . $current_folder . $filename;
		$target_file = $this->upload_dir . $target_folder . $filename;

		if ( file_exists( $target_file ) ) {
			$message = array(
				'result' => 'error',
				'ID' => $re_id_attache,
				'filename' => $filename,
				'current' => $current_folder,
				'target' => $target_folder,
				/* translators: Error message */
				'error' => sprintf( __( '%1$s cannot be moved. The file name is the same.', 'organize-media-folder' ), $filename ),
			);
			return $message;
		}

		if ( file_exists( $current_file ) ) {
			$err_copy1 = @copy( $current_file, $target_file );
			if ( ! $err_copy1 ) {
				$message = array(
					'result' => 'error',
					'ID' => $re_id_attache,
					'filename' => $filename,
					'current' => $current_folder,
					'target' => $target_folder,
					/* translators: Error message */
					'error' => sprintf( __( '%1$s: Failed a copy from %2$s to %3$s.', 'organize-media-folder' ), $new_attach_title, wp_normalize_path( $this->mb_utf8( $current_file ) ), wp_normalize_path( $this->mb_utf8( $target_file ) ) ),
				);
				return $message;
			}
			wp_delete_file( $current_file );
		}

		update_post_meta( $re_id_attache, '_wp_attached_file', ltrim( $target_folder . $filename, '/' ) );

		if ( 'image' === wp_ext2type( $ext ) || 'pdf' === strtolower( $ext ) ) {

			$metadata = wp_get_attachment_metadata( $re_id_attache );

			if ( ! empty( $metadata ) ) {
				foreach ( (array) $metadata as $key1 => $key2 ) {
					if ( 'sizes' === $key1 ) {
						foreach ( $metadata[ $key1 ] as $key2 => $key3 ) {
							$current_thumb_file = $this->upload_dir . $current_folder . $metadata['sizes'][ $key2 ]['file'];
							$target_thumb_file = $this->upload_dir . $target_folder . $metadata['sizes'][ $key2 ]['file'];
							if ( file_exists( $current_thumb_file ) ) {
								$err_copy2 = @copy( $current_thumb_file, $target_thumb_file );
								if ( ! $err_copy2 ) {
									$message = array(
										'result' => 'error',
										'ID' => $re_id_attache,
										'filename' => $filename,
										'current' => $current_folder,
										'target' => $target_folder,
										/* translators: Error message */
										'error' => sprintf( __( '%1$s: Failed a copy from %2$s to %3$s.', 'organize-media-folder' ), $new_attach_title, wp_normalize_path( $this->mb_utf8( $current_thumb_file ) ), wp_normalize_path( $this->mb_utf8( $target_thumb_file ) ) ),
									);
									return $message;
								}
								wp_delete_file( $current_thumb_file );
							}
						}
					}
				}
				$metadata['file'] = ltrim( $target_folder . $filename, '/' );
				update_post_meta( $re_id_attache, '_wp_attachment_metadata', $metadata );
				if ( ! empty( $metadata['original_image'] ) ) {
					$current_org_file = $this->upload_dir . $current_folder . $metadata['original_image'];
					$target_org_file = $this->upload_dir . $target_folder . $metadata['original_image'];
					if ( file_exists( $current_org_file ) ) {
						$err_copy3 = @copy( $current_org_file, $target_org_file );
						if ( ! $err_copy3 ) {
							$message = array(
								'result' => 'error',
								'ID' => $re_id_attache,
								'filename' => $filename,
								'current' => $current_folder,
								'target' => $target_folder,
								/* translators: Error message */
								'error' => sprintf( __( '%1$s: Failed a copy from %2$s to %3$s.', 'organize-media-folder' ), $new_attach_title, wp_normalize_path( $this->mb_utf8( $current_org_file ) ), wp_normalize_path( $this->mb_utf8( $target_org_file ) ) ),
							);
							return $message;
						}
						wp_delete_file( $current_org_file );
						$filename = $metadata['original_image'];
					}
				}
			}
		}

		$url_attach = $this->upload_url . $current_folder . $filename;
		$new_url_attach = $this->upload_url . $target_folder . $filename;

		global $wpdb;
		/* Change DB contents */
		$search_url = str_replace( '.' . $ext, '', $url_attach );
		$replace_url = str_replace( '.' . $ext, '', $new_url_attach );

		/* Replace */
		$wpdb->query(
			$wpdb->prepare(
				"
				UPDATE {$wpdb->prefix}posts
				SET post_content = replace( post_content, %s, %s )
				",
				$search_url,
				$replace_url
			)
		);

		/* Change DB Attachement post guid */
		$update_array = array(
			'guid' => $new_url_attach,
		);
		$id_array = array(
			'ID' => $re_id_attache,
		);
		$wpdb->show_errors();
		$wpdb->update( $wpdb->prefix . 'posts', $update_array, $id_array, array( '%s' ), array( '%d' ) );
		$message = array(
			'result' => 'success',
			'ID' => $re_id_attache,
			'filename' => $filename,
			'current' => $current_folder,
			'target' => $target_folder,
		);
		if ( '' !== $wpdb->last_error ) {
			$wpdb->print_error();
			$message['result'] = 'error';
			$message['error'] = __( 'WordPress database error:' );
		}

		return $message;
	}

	/** ==================================================
	 * Scan directory
	 *
	 * @param string $dir  dir.
	 * @return array $dirlist
	 * @since 1.00
	 */
	private function scan_dir( $dir ) {

		$omf_admin_settings = get_option( 'omf_admin' );

		$excludedir = '/^(?!.*(media-from-ftp-tmp|bulk-media-register-tmp'; /* tmp dir for Media from FTP and Bulk Media Register */
		global $blog_id;
		if ( is_multisite() && is_main_site( $blog_id ) ) {
			$excludedir .= '|\/sites\/';
		}
		if ( ! empty( $omf_admin_settings['exclude_folders'] ) ) {
			$excludes = explode( '|', $omf_admin_settings['exclude_folders'] );
			foreach ( $excludes as $value ) {
				$excludedir .= '|' . str_replace( '/', '\/', $value );
			}
		}
		$excludedir .= ')).*$/';

		$iterator = new RecursiveDirectoryIterator(
			$dir,
			FilesystemIterator::CURRENT_AS_FILEINFO |
			FilesystemIterator::KEY_AS_PATHNAME |
			FilesystemIterator::SKIP_DOTS
		);
		$iterator = new RecursiveIteratorIterator(
			$iterator,
			RecursiveIteratorIterator::SELF_FIRST
		);

		$iterator = new RegexIterator( $iterator, $excludedir, RecursiveRegexIterator::MATCH );

		$wordpress_path = wp_normalize_path( ABSPATH );
		$list = array();
		if ( ! empty( $iterator ) ) {
			$count = 0;
			foreach ( $iterator as $fileinfo ) {
				if ( $fileinfo->isDir() ) {
					$dir = $fileinfo->getPathname();
					if ( strstr( $dir, $wordpress_path ) ) {
						$direnc = $this->mb_utf8( str_replace( $wordpress_path, '', $dir ) );
						$direnc = str_replace( $this->upload_path, '', $direnc );
					} else {
						$direnc = $this->mb_utf8( str_replace( $this->upload_dir, '', $dir ) );
					}
					++$count;
					$slug = 'omf-' . $count;
					$list[ $slug ] = array(
						'name' => $direnc,
					);
				}
			}
		}

		asort( $list );
		$last_list['terms'] = $list;

		return $last_list;
	}

	/** ==================================================
	 * Directory set
	 *
	 * @param bool $forced  forced.
	 * @since 1.00
	 */
	public function omf_dirs( $forced ) {

		if ( $forced ) {
			$dirs = wp_json_encode( $this->scan_dir( $this->upload_dir ), JSON_UNESCAPED_UNICODE );
			update_option( 'omf_dirs', $dirs );
		} elseif ( ! get_option( 'omf_dirs' ) ) {
				$dirs = wp_json_encode( $this->scan_dir( $this->upload_dir ), JSON_UNESCAPED_UNICODE );
				update_option( 'omf_dirs', $dirs );
		}
	}

	/** ==================================================
	 * Register Taxonomy
	 *
	 * @since 1.00
	 */
	public function omf_folders_taxonomies() {

		$args = array(
			'hierarchical'          => false,
			'label'                 => __( 'Folder', 'organize-media-folder' ),
			'show_ui'               => false,
			'show_admin_column'     => false,
			'update_count_callback' => '_update_generic_term_count',
			'query_var'             => true,
			'rewrite'               => true,
		);

		register_taxonomy( 'omf_folders', 'attachment', $args );
	}

	/** ==================================================
	 * Register Media Folder Term for media upload
	 *
	 * @param array $metadata  metadata.
	 * @param int   $attach_id  ID.
	 * @since 1.00
	 */
	public function omf_folders_update( $metadata, $attach_id ) {

		if ( $metadata && array_key_exists( 'file', $metadata ) ) {
			$file = $metadata['file'];
		} else {
			$file = get_post_meta( $attach_id, '_wp_attached_file', true );
		}
		/* for XAMPP [ get_attached_file( $attach_id ): Unable to get correct value ] */
		$file = str_replace( $this->upload_dir . '/', '', $file );

		$filename = wp_basename( $file );
		$folder_name = '/' . untrailingslashit( str_replace( $filename, '', wp_normalize_path( $file ) ) );

		/* term update */
		$term = get_term_by( 'name', $folder_name, 'omf_folders' );

		if ( 0 < $term->term_id ) {
			$term_taxonomy_ids = wp_set_object_terms( $attach_id, $term->term_id, 'omf_folders' );
			if ( is_wp_error( $term_taxonomy_ids ) ) {
				$error = $foldername;
			}
		}
	}

	/** ==================================================
	 * Register Media Folder Term for create new folder
	 *
	 * @param string $folder_name  folder_name.
	 * @since 1.00
	 */
	public function omf_folders_new( $folder_name ) {

		$dirs = get_option( 'omf_dirs' );
		$omf_dirs = json_decode( $dirs, true );
		if ( ! $omf_dirs ) {
			return;
		}

		$slug = 'omf-' . strval( count( $omf_dirs['terms'] ) + 1 );
		$insert_term_args = array(
			'slug' => $slug,
		);
		wp_insert_term( $folder_name, 'omf_folders', $insert_term_args );
	}

	/** ==================================================
	 * Register Media Folder Term for folder change
	 *
	 * @param string $folder_name  folder_name.
	 * @param int    $attach_id  ID.
	 * @since 1.00
	 */
	public function omf_folders_change( $folder_name, $attach_id ) {

		/* term update */
		$term = get_term_by( 'name', $folder_name, 'omf_folders' );

		if ( 0 < $term->term_id ) {
			$term_taxonomy_ids = wp_set_object_terms( $attach_id, $term->term_id, 'omf_folders' );
			/* refresh count */
			wp_update_term_count( $term->term_id, 'omf_folders', true );
			if ( is_wp_error( $term_taxonomy_ids ) ) {
				$error = $foldername;
			}
		}
	}

	/** ==================================================
	 * Register Media Folder Term
	 *
	 * @since 1.00
	 */
	public function omf_folders_term() {

		$dirs = get_option( 'omf_dirs' );
		$omf_dirs = json_decode( $dirs, true );
		if ( ! $omf_dirs ) {
			return;
		}

		/* term insert or update for under directories */
		foreach ( $omf_dirs['terms'] as $key => $value ) {
			$insert_term_args = array(
				'slug' => $key,
			);
			$term = get_term_by( 'slug', $key, 'omf_folders' );
			$folder_name = wp_normalize_path( $value['name'] );
			if ( ! term_exists( $key, 'omf_folders' ) ) {
				$insert_term_args = array(
					'slug' => $key,
				);
				wp_insert_term( $folder_name, 'omf_folders', $insert_term_args );
			} else {
				$update_term_args = array(
					'slug' => $key,
					'name' => $folder_name,
				);
				wp_update_term( $term->term_id, 'omf_folders', $update_term_args );
			}
		}

		/* term insert or update for top directory */
		$term = get_term_by( 'slug', 'omf', 'omf_folders' );
		if ( ! term_exists( 'omf', 'omf_folders' ) ) {
			$insert_term_args = array(
				'slug' => 'omf',
			);
			wp_insert_term( wp_normalize_path( '/' ), 'omf_folders', $insert_term_args );
		} else {
			$update_term_args = array(
				'slug' => 'omf',
				'name' => wp_normalize_path( '/' ),
			);
			wp_update_term( $term->term_id, 'omf_folders', $update_term_args );
		}

		/* Delete terms that are not related to folders for under directories */
		$args = array(
			'taxonomy'   => 'omf_folders',
			'hide_empty' => false,
			'search'     => 'omf-',
		);
		$terms = get_terms( $args );
		if ( ! empty( $terms ) ) {
			foreach ( $terms as $term ) {
				if ( ! array_key_exists( $term->slug, $omf_dirs['terms'] ) ) {
					wp_delete_term( $term->term_id, 'omf_folders' );
				}
			}
		}

		/* term relationships for post id */
		global $wpdb;
		$attachments_meta = $wpdb->get_results(
			"
				SELECT	post_id, meta_value
				FROM	{$wpdb->prefix}postmeta
				WHERE	meta_key = '_wp_attached_file'
			"
		);
		foreach ( $attachments_meta as $attachment ) {
			$filename = wp_basename( $attachment->meta_value );
			$foldername = '/' . untrailingslashit( str_replace( $filename, '', $attachment->meta_value ) );
			$terms = get_term_by( 'name', $foldername, 'omf_folders' );
			if ( $terms ) {
				$term_taxonomy_ids = wp_set_object_terms( $attachment->post_id, $terms->term_id, 'omf_folders' );
				if ( is_wp_error( $term_taxonomy_ids ) ) {
					$error = $attachment->meta_value;
				}
			} else {
				wp_delete_object_term_relationships( $attachment->post_id, 'omf_folders' );
			}
		}

		/* term relationships all clean */
		$term_ids = $wpdb->get_col(
			"
				SELECT term_id
				FROM {$wpdb->prefix}term_taxonomy
				WHERE taxonomy = 'omf_folders'
			"
		);
		$object_ids = get_objects_in_term( $term_ids, 'omf_folders' );
		$attachments = $wpdb->get_col(
			"
				SELECT	ID
				FROM	{$wpdb->prefix}posts
				WHERE	post_type = 'attachment'
			"
		);
		$diff_ids = array_diff( $object_ids, $attachments );
		foreach ( $diff_ids as $diff_id ) {
			wp_delete_object_term_relationships( $diff_id, 'omf_folders' );
		}
	}

	/** ==================================================
	 * Term filter update
	 *
	 * @since 1.00
	 */
	public function term_filter_update() {

		global $wpdb;
		$attachments = $wpdb->get_col(
			"
				SELECT	ID
				FROM	{$wpdb->prefix}posts
				WHERE	post_type = 'attachment'
			"
		);
		$dir_terms = wp_get_object_terms( $attachments, 'omf_folders', array() );
		$all_slug_arr = array();
		foreach ( $dir_terms as $key => $value ) {
			$all_slug_arr[] = $value->slug;
		}

		$users = get_users(
			array(
				'orderby' => 'nicename',
				'order' => 'ASC',
			)
		);
		foreach ( $users as $user ) {
			if ( user_can( $user->ID, 'upload_files' ) ) {
				update_user_option( $user->ID, 'omf_filter_term', $all_slug_arr );
			}
		}
	}

	/** ==================================================
	 * Directory select box
	 *
	 * @param string $searchdir  searchdir.
	 * @return string $linkselectbox
	 * @since 1.00
	 */
	public function dir_selectbox( $searchdir ) {

		$dirs = array();
		$args = array(
			'taxonomy'   => 'omf_folders',
			'hide_empty' => false,
		);
		$terms = get_terms( $args );
		foreach ( $terms as $term ) {
			$dirs[ $term->slug ] = $term->name;
		}
		$linkselectbox = null;
		foreach ( $dirs as $key => $value ) {
			if ( $searchdir === $value ) {
				$linkdirs = '<option value="' . $key . '" selected>' . $value . '</option>';
			} else {
				$linkdirs = '<option value="' . $key . '">' . $value . '</option>';
			}
			$linkselectbox = $linkselectbox . $linkdirs;
		}

		return $linkselectbox;
	}

	/** ==================================================
	 * Directory select box for admin bar
	 *
	 * @param array $linkdirs  linkdirs.
	 * @return array $linkdirs
	 * @since 1.20
	 */
	public function dir_selectbox_admin_bar( $linkdirs = array() ) {

		$omf_admin_settings = get_option( 'omf_admin' );

		$dirs = array();
		$args = array(
			'taxonomy'   => 'omf_folders',
			'hide_empty' => false,
		);
		$terms = get_terms( $args );
		foreach ( $terms as $term ) {
			if ( in_array( $term->slug, $omf_admin_settings['admin_bar_folders'] ) ) {
				$dirs[ $term->slug ] = $term->name;
			}
		}

		$linkdirs = array();
		foreach ( $dirs as $key => $value ) {
			$linkdirs[] = array(
				'id'     => $key,
				'parent' => 'omf-folder-switch',
				'title'  => $value,
				'href'   => '#',
				'meta'   => array(
					'class' => 'omf-folders',
					'title' => $value,
				),
			);
		}

		return $linkdirs;
	}

	/** ==================================================
	 * Select folders for admin bar
	 *
	 * @since 1.23
	 */
	public function dir_select_folders_admin_bar() {

		$omf_admin_settings = get_option( 'omf_admin' );

		$dirs = array();
		$args = array(
			'taxonomy'   => 'omf_folders',
			'hide_empty' => false,
		);
		$terms = get_terms( $args );
		$html = null;
		foreach ( $terms as $term ) {
			if ( in_array( $term->slug, $omf_admin_settings['admin_bar_folders'] ) ) {
				$checked = 'checked="checked"';
			} else {
				$checked = null;
			}
			$html .= '<div style="display: block; padding: 5px 5px;"><input type="checkbox" name="admin_bar_folders[' . $term->slug . ']" ' . $checked . '>' . $term->name . '</div>';
		}

		$allowed_html = array(
			'input'  => array(
				'type'    => array(),
				'name'    => array(),
				'checked' => array(),
			),
			'div'  => array(
				'style'  => array(),
			),
		);

		echo wp_kses( $html, $allowed_html );
	}

	/** ==================================================
	 * Folder change Callback
	 *
	 * @since 1.20
	 */
	public function omf_fc_callback() {

		$action = 'omf_folder';
		if ( check_ajax_referer( $action, 'nonce', false ) ) {
			if ( ! empty( $_POST['folder_slug'] ) ) {
				$folder_slug = sanitize_text_field( wp_unslash( $_POST['folder_slug'] ) );
				$folder = get_term_by( 'slug', $folder_slug, 'omf_folders' )->name;
				$omf_admin_settings = get_option( 'omf_admin' );
				$omf_admin_settings['subdir'] = $folder;
				update_option( 'omf_admin', $omf_admin_settings );
			}
		}

		wp_die();
	}

	/** ==================================================
	 * Multibyte UTF-8
	 *
	 * @param string $str  str.
	 * @return string $str
	 * @since 1.00
	 */
	public function mb_utf8( $str ) {

		if ( function_exists( 'mb_convert_encoding' ) ) {
			$encoding = implode( ',', mb_list_encodings() );
			$str = mb_convert_encoding( $str, 'UTF-8', $encoding );
		}

		return $str;
	}
}


