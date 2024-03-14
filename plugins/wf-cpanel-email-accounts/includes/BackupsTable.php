<?php
declare( strict_types=1 );
namespace WebFacing\cPanel\Email;
use WebFacing\cPanel\UAPI;

/**
 * Exit if accessed directly
 */
\class_exists( 'WP' ) || exit;

class BackupsTable extends \WP_List_Table {

	private         int  $total_size = 0;

	public          bool $processing = false;

	/**
	 * Prepare the items for the table to process
	 *
	 * @return never
	 */
	public function prepare_items() {

		$this->process_bulk_action();

		$columns  = $this->get_columns();
		$hidden   = $this->get_hidden_columns();
		$sortable = $this->get_sortable_columns();
		$data	  = $this->table_data();
		$cur_page = $this->get_pagenum();
		$per_page = 10;

		\usort( $data, 'self::sort_data' );
		$total_items  = \count( $data );

		$this->set_pagination_args( [
			'total_items' => $total_items,
			'per_page'    => $per_page
		] );

		$data = \array_slice( $data, ( $cur_page - 1 ) * $per_page, $per_page );

		$this->_column_headers = [ $columns, $hidden, $sortable ];
		$this->items = $data;
	}

	/**
	 * Override the parent columns method. Defines the columns to use in your listing table
	 */
	public function get_columns(): array {
		$columns = [
			'cb'       => '<input type="checkbox"/>',
			'type'     => _x( 'Type',       'Column Header' ),
			'name'     => _x( 'File Name',  'Column Header' ),
			'size'     => _x( 'Size',       'Column Header' ),
			'num_size' => _x( 'Size',       'Column Header' ),
			'started'  => _x( 'Started',    'Column Header' ),
			'mtime'    => _x( 'Started',    'Column Header' ),
			'finished' => _x( 'Finished',   'Column Header' ),
			'ctime'    => _x( 'Finished',   'Column Header' ),
		];
		return $columns;
	}

	/**
	 * Define which columns are hidden
	 *
	 * @return array
	 */
	public function get_hidden_columns(): array {
		return Main::$is_debug ? [] : [ 'type', 'num_size', 'mtime', 'ctime' ];
	}

	/**
	 * Define the sortable columns
	 *
	 * @return array
	 */
	public function x_get_sortable_columns(): array {
		return [
			'name'      => [ 'name'     => false ],
			'size'      => [ 'num_size' => false ],
//			'num_size'  => [ 'num_size' => false ],
			'started'   => [ 'mtime'    => true  ],
//			'mtime'     => [ 'mtime'    => true  ],
			'finished'  => [ 'ctime'    => true  ],
//			'ctime'     => [ 'ctime'    => true  ],
		];
	}

	/**
	 * Get the table data
	 */
	private function table_data(): array {
		global $wp_filesystem;
		$data = [];

		foreach ( UAPI::restored_backups() as $file ) {
			$size = \get_dirsize( \trailingslashit( \trailingslashit( $wp_filesystem->wp_content_dir() ) . 'cpanel' ) . $file->file );
			$data[] = [
				'type'       => \time() - $file->mtime < \MINUTE_IN_SECONDS ? 'proc' : $file->type,
				'name'       => $file->file,
				'size'       => \size_format( $size, 1 ),
				'num_size'   => $size,
				'started'    => self::time_from_file( $file )->format( 'U' ),
				'mtime'      => $file->mtime,
				'finished'   => $file->mtime,
				'ctime'      => $file->ctime,
			];
			$this->processing = $this->processing || $data[ \count( $data ) - 1]['type'] === 'proc';
		}

		foreach ( UAPI::backups( $this->_args['creds'] ) as $file ) {
			$data[] = [
				'type'       => $file->type === 'dir' ? $file->type : ( ( $file->processing ?? false ) ? 'proc' : $file->type ),
				'name'       => $file->file,
				'size'       => \str_replace ( '.', _x( '.', 'Decimal Point' ), $file->humansize ),
				'num_size'   => $file->size,
				'started'    => self::time_from_file( $file )->format( 'U' ),
				'mtime'      => $file->mtime,
				'finished'   => $file->mtime,
				'ctime'      => $file->ctime,
			];
			$this->processing = $this->processing || $data[ \count( $data ) - 1]['type'] === 'proc';
		}
		return $data;
	}

	/**
	 * Render the bulk edit checkbox
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	public function column_cb( $item ) {
		return $item['type'] === 'file' ?
			\sprintf(
				'<input type="checkbox" name="name[]" value="%1$s"/>',
				\esc_attr( $item['name'] ),
			)
		: '' ;
	}

	/**
	 * Render the bulk edit checkbox
	 */
	public function column_name( array $item ): string {
		$cpanel = 'cpanel';
		$page = \sanitize_text_field( $_REQUEST['page'] );
		$filename_parts = \explode( Main::$cpanel_user . '-', $item['name'] );
		$filename = '';
		$suffix    = '.tar.gz';

		for ( $i = 0; $i <= \count( $filename_parts ) - 2; $i++ ) {
			$filename .= $filename_parts[ $i ];
		}
		$filename .= Main::$cpanel_user . $suffix;
		$download =  $cpanel . '-' . $filename;
		$actions = [
			'row-download' => '<a href="' . \content_url( $cpanel ) . '/' . $item['name'] . '" download="' . $download . '">' . _x( 'Download', 'List Table Row Action' ) . '</a>',
			'row-delete'   => '<a href="' . \add_query_arg( [
				'page'     => \esc_attr( $page ),
				'name'     => $item['name'],
				'type'     => \substr( $item['type'], 0, 1 ),
				'action'   => 'row-delete',
				'_wpnonce' => \wp_create_nonce( 'row-delete' ),
			], \admin_url( 'admin.php' ) ) . '">' . _x( 'Delete', 'List Table Row Action' ),
			'row-restore'   => '<a href="' . \add_query_arg( [
				'page'     => \esc_attr( $page ),
				'name'   => $item['name'],
				'action'   => 'row-restore',
				'_wpnonce' => \wp_create_nonce( 'row-restore' ),
			], \admin_url( 'admin.php' ) ) . '">' . _x( 'Restore', 'List Table Row Action' ),
		];

		if ( $item['type'] !== 'file' ) {
			unset (
				$actions['row-download'],
				$actions['row-restore'],
			);
		}

		return $item['type'] !== 'file' ? $item['name'] . ( $item['type'] === 'dir' ? $this->row_actions( $actions ) : '' ) : '<a href="' . \content_url( $cpanel ) . '/' . $item['name'] . '" download="' . $download . '">' . $filename . '</a><br/>' . $this->row_actions( $actions );
	}

	/**
	 * Define what data to show on each column of the table
	 *
	 * @param  array  $item	       - Data
	 * @param  string $column_name - Current column name
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {
		$format = \get_option( 'time_format' );
		$dot    = \substr( $format, 1, 1 );
		$s      = \str_ends_with( $format, $dot . 's' ) ? '' : $dot . 's';
		$format = 'l ' . \get_option( 'date_format' ) . ' ' . _x( '\a\t', 'Time divider' ) . ' ' . $format . $s;

		switch ( $column_name ) {
			case 'type':
			case 'name':
			case 'num_size':
			case 'ctime':
			case 'mtime':
				return $item[ $column_name ];
			case 'size':
				$this->total_size = $this->total_size + $item['num_size'];
				return $item['type'] !== 'proc' ?  $item[ $column_name ] : '';
			case 'started':
				return \ucfirst( \date_i18n( $format, $item[ $column_name ] ) );
			case 'finished':
				return $item['type'] === 'proc' ? _x( 'Processing', 'Backup Status' ) : '<time datetime="' . \date_i18n( 'Y-m-d H:i:s', $item[ $column_name ] ) . '" title="' . \wp_date( $format, $item[ $column_name ] ) . '">' . \human_time_diff( $item[ $column_name ] ) . ' ' . _x( 'ago', 'Human Time Diff suffix' ) . '</time>';
			default:
				return \print_r( $item, true ) ;
		}
	}

	/**
	 * Allows you to sort the data by the variables set in the $_GET
	 */
	private static function sort_data( array $a, array $b ): int {
		// Set defaults
		$orderby  = 'finished';
		$order    = 'desc';

		// If orderby is set, use this as the sort column
		$orderby  = \sanitize_text_field( $_GET['orderby'] ?? $orderby );

		// If order is set use this as the order
		$order    = \sanitize_text_field( $_GET['order'] ?? $order );

		$result = $a[ $orderby ] <=> $b[ $orderby ];

		return $order === 'asc' ? $result : -$result;
	}

	/**
	 * Returns an associative array containing the bulk action
	 *
	 * @return array
	 */
	public function get_bulk_actions(): array {
		$actions = [
			'bulk-delete' => _x( 'Delete Backups', 'Bulk Action' ),
		];
		return $actions;
	}

	/**
	 * Handle bulk actions.
	 *
	 * Optional. You can handle your bulk actions anywhere or anyhow you prefer.
	 *
	 * @see $this->prepare_items()
	 */
	public function process_bulk_action(): void {
		global $wp_filesystem;
		\WP_Filesystem();
		$cpanel = 'cpanel';
		$method = \strtolower( $_SERVER['REQUEST_METHOD'] );
		$action = $this->current_action();

		if ( $method === 'post' ) {

			if ( $action === 'bulk-delete' ) {

				if ( \current_user_can( 'install_plugins' ) ) {

					if ( \wp_verify_nonce( $_POST['_wpnonce'], 'bulk-' . $this->_args['plural'] ) ) {
						$backups = \array_map( 'sanitize_file_name', (array) $_POST['name'] );
						$deleted = 0;

						foreach ( $backups as $backup ) {
							$delete = $wp_filesystem->delete( \trailingslashit( $wp_filesystem->wp_content_dir() . $cpanel ) . $backup, false, 'f' );

							if ( $delete ) {
								$deleted++;
							}
						}
						if ( $deleted ) {
							UAPI::delete_transients();
						}
?>
						<div class="notice notice-success is-dismissible">
							<p><?php \printf(
								_nx(
									'%1$d backup deleted.',
									'%1$d backups deleted.',
									\intval( $deleted ),
									'Action result notice, %1$d = number of backups deleted'
								),
								\intval( $deleted ),
							); ?></p>
						</div>
<?php
					}
				}
			}
		} else {

			if ( $action === 'new-backup' ) {

				if ( \current_user_can( 'install_plugins' ) ) {

					if ( \wp_verify_nonce( $_GET['_wpnonce'], 'new-backup' ) ) {
						UAPI::delete_transients();
						UAPI::create_backup( $this->_args['email'] );
?>
							<div class="notice notice-success is-dismissible">
								<p><?php \printf(
									_x( 'Backup process started. When finished, email notification will be sent to %1$s, but you don\'t need the links provided in it. Then refresh this page, and the file will then be moved, and ready to be downloaded from here only.', 'Notice success' ),
									Main::email_to_utf8( $this->_args['email'] ),
								); \sleep( 4 ); ?></p>
							</div>
<?php
					} else {
						\wp_die( _x( 'Expired link or invalid origin. Please go back, refresh and try again.', 'Die message' ) );
					}
				} else {
					\wp_die( _x( 'You are not allowed to create backups.', 'Die message' ) );
				}
			} elseif ( $action === 'row-delete' ) {

				if ( \current_user_can( 'install_plugins' ) ) {

					if ( \wp_verify_nonce( $_GET['_wpnonce'], 'row-delete' ) ) {
						$backup = \sanitize_file_name(  $_GET['name' ] ?? '' );
						$type   = \sanitize_text_field( $_GET['type' ] ?? 'f' );
						$filepath = \trailingslashit( $wp_filesystem->wp_content_dir() . $cpanel ) . $backup;
						$wp_filesystem->chmod( $filepath, \FS_CHMOD_DIR, true );
						$delete = $wp_filesystem->delete( $filepath, $type === 'd', $type );
						$type = $type === 'f' ? __( 'file' ) : ( $type === 'd' ? __( 'folder' ) : false );

						if ( $delete ) {
//							UAPI::delete_transients();
?>
							<div class="notice notice-success is-dismissible">
								<p><?php \printf(
									/* translators: 1: file or dir; 2: file or dir name */
									_x( 'Backup %1$s %2$s deleted.', 'Notice success' ),
									$type,
									'<code>' . $backup . '</code>',
								); ?></p>
							</div>
<?php
						} else { ?>
							<div class="notice notice-error is-dismissible">
								<p><?php \printf(
									/* translators: 1: file or dir; 2: file or dir name */
									_x( 'Backup %1$s %2$s could not be deleted. Please use FTP or cPanel® File Manager.', 'Notice error' ),
									$type,
									'<code>' . $backup . '</code>',
								); ?></p>
							</div>
<?php
						}
					} else {
						\wp_die( _x( 'Expired link or invalid origin. Please go back, refresh and try again.', 'Die message' ) );
					}
				} else {
					\wp_die( _x( 'You are not allowed to delete backups.', 'Die message' ) );
				}
			} elseif ( $action === 'row-restore' ) {

				if ( \current_user_can( 'install_plugins' ) ) {

					if ( \wp_verify_nonce( $_GET['_wpnonce'], 'row-restore' ) ) {
						$backup = \sanitize_file_name( $_GET['name' ] ?? '' );
						$dir    = \trailingslashit( $wp_filesystem->wp_content_dir() . $cpanel );
						$result = UAPI::restore_backup( $dir . $backup, $dir );

						if ( \is_wp_error( $result ) && $result->has_errors() ) { ?>
							<div class="notice notice-error is-dismissible">
								<p><?php \esc_attr_e( $result->get_error_message() ) ;?></p>
								<p><?php \printf(
									/* translators: 1: file name */
									_x( 'Backup %1$s could not be restored.', 'Notice error' ),
									'<code>' . $backup . '</code>',
								); ?></p>
							</div>
<?php
						} else {
							$name_parts = \explode( Main::$cpanel_user . '-', $backup );
							$dirname = '';
							$suffix    = '.tar.gz';

							for ( $i = 0; $i <= \count( $name_parts ) - 2; $i++ ) {
								$dirname .= $name_parts[ $i ];
							}
							$dirname .= Main::$cpanel_user;
							@$wp_filesystem->copy( \trailingslashit( $wp_filesystem->wp_content_dir() ) . 'index.php', \trailingslashit( $dir ) . $dirname . '/index.php', false );
							$delete = $wp_filesystem->delete( \trailingslashit( $wp_filesystem->wp_content_dir() . $cpanel ) . $backup, false,'f' );
							UAPI::delete_transients();
							$messages = (array) $result->messages; ?>
							<div class="notice notice-success is-dismissible">
<?php
								if ( \count( $messages ) ) { ?>
									<p>
<?php								foreach ( $messages as $message ) {
										echo \esc_html( $message ), '<br/>';
									} ?>
									</p>
<?php
								} ?>
								<p><?php
									/* translators: 1: file name, 2: folder name */
									\printf(
										_x( 'Backup %1$s restoreed to %2$s. You can access it through cPanel® File Manager or FTP.', 'Notice success' ),
										'<code>' . $backup . '</code>',
										'<code>' . \str_replace( \trailingslashit( UAPI::home_path() ), '', \trailingslashit( $dir ) . $dirname ) . '</code>',
									); ?>
								</p>
								<p><?php
									\printf(
										$delete ?
											/* translators: 1: file name */
											_x( 'Backup %1$s deleted.', 'Notice success' ) :
											/* translators: 1: file name */
											_x( 'Backup %1$s not deleted.', 'Notice error' ),
										'<code>' . $backup . '</code>',
									); ?>
								</p>
							</div>
<?php
						}
					} else {
						\wp_die( _x( 'Expired link or invalid origin. Please go back, refresh and try again.', 'Die message' ) );
					}
				} else {
					\wp_die( _x( 'You are not allowed to delete backups.', 'Die message' ) );
				}
			}
		}
	}

	public function extra_tablenav( $whcich ) {

		if ( $whcich === 'bottom' && $this->total_size > 16 ) {
			echo '<p>', __( 'Backups total:'), ' ', \size_format( $this->total_size, 0 ), ' ', __( 'and the account limit is' ), ' ', \size_format( Main::$byte_limit, 0 ), ' (', \size_format( Main::$byte_limit - Main::$bytes_used, 0 ), ' ' . __( 'left' ). ').</p>';
		}
	}

	protected static function time_from_file( \stdClass $file ): \DateTimeImmutable {
		return \DateTimeImmutable::createFromFormat( '\b\a\c\k\u\p-m.d.Y\_H-i-s+', $file->file );
	}
}
