<?php
/**
 * Admin ajax functionality.
 *
 * @link       http://codexin.com
 * @since      1.0.0
 *
 * @package    Codexin\ImageMetadataSettings
 * @subpackage Codexin\ImageMetadataSettings/admin
 */

namespace Codexin\ImageMetadataSettings\Admin;

require_once ABSPATH . 'wp-admin/includes/class-wp-media-list-table.php';
/**
 * List table customization
 */
class Extended_Media_List_Table extends \WP_Media_List_Table {
	/**
	 * Add the export bulk action
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		// Get the original bulk actions.
		$actions = parent::get_bulk_actions();
		$delete  = $actions['delete'];
		unset( $actions['delete'] );
		$actions['edit']   = __( 'Edit', 'media-library-helper' );
		$actions['delete'] = $delete;
		return $actions;
	}

	/**
	 * Outputs the hidden row displayed when inline editing
	 *
	 * @since 3.1.0
	 *
	 * @global string $mode List table view mode.
	 */
	public function inline_edit() {

		global $mode;

		$screen = $this->screen;

		$post             = get_default_post_to_edit( $screen->post_type );
		$post_type_object = get_post_type_object( $screen->post_type );

		$m            = ( isset( $mode ) && 'excerpt' === $mode ) ? 'excerpt' : 'list';
		$can_publish  = current_user_can( $post_type_object->cap->publish_posts );
		$core_columns = array(
			'cb'          => true,
			'date'        => true,
			'title'       => true,
			'alt'         => true,
			'description' => true,
			'caption'     => true,
			'author'      => true,
			'parent'      => true,
			'comments'    => true,
		);

		?>

		<form method="get">
		<table style="display: none"><tbody id="inlineedit">
		<?php
		$hclass              = 'page';
		$inline_edit_classes = "inline-edit-row inline-edit-row-$hclass";
		$bulk_edit_classes   = "bulk-edit-row bulk-edit-row-$hclass bulk-edit-{$screen->post_type}";
		$quick_edit_classes  = "quick-edit-row quick-edit-row-$hclass inline-edit-{$screen->post_type}";

		$bulk = 0;
		while ( $bulk < 2 ) :
			$classes  = $inline_edit_classes . ' ';
			$classes .= $bulk ? $bulk_edit_classes : $quick_edit_classes;
			?>
			<tr id="<?php echo $bulk ? esc_attr( 'bulk-edit' ) : esc_attr( 'inline-edit' ); ?>" class="<?php echo esc_attr( $classes ); ?>" style="display: none">
			<td colspan="<?php echo esc_attr( $this->get_column_count() ); ?>" class="colspanchange bulk-edit-media">

			<fieldset class="inline-edit-col-left ">
				<legend class="inline-edit-legend"><?php echo $bulk ? esc_html__( 'Bulk Edit', 'media-library-helper' ) : esc_html__( 'Quick Edit', 'media-library-helper' ); ?></legend>
				<div class="inline-edit-col">
				<?php if ( post_type_supports( $screen->post_type, 'title' ) ) : ?>
					<?php if ( $bulk ) : ?>
						<div id="bulk-title-div">
							<div id="bulk-titles"></div>
						</div>
					<?php endif; ?>
				<?php endif; ?>
				</div>
			</fieldset>

			<fieldset class="inline-edit-col-right">
				<div class="inline-edit-col">
					<?php if ( 'attachment' === $screen->post_type && $can_publish && current_user_can( $post_type_object->cap->edit_others_posts ) ) : ?>
						<?php if ( $bulk ) : ?>
							<div class="field-wrap full-width">
								<label class="alignleft">
									<span class="title"><?php esc_html_e( 'Alt', 'media-library-helper' ); ?></span>
									<input type="text" name="alt" value="">
								</label>
							</div>
							<div class="field-wrap full-width">
								<label class="alignleft">
									<span class="title"><?php esc_html_e( 'Caption', 'media-library-helper' ); ?></span>
									<input type="text" name="caption" value="">
								</label>
							</div>
							<div class="field-wrap full-width">
								<label class="alignleft">
									<span class="title"><?php esc_html_e( 'Description', 'media-library-helper' ); ?></span>
									<input type="text" name="description" value="">
								</label>
							</div>
							<div class="field-wrap full-width">
								<label class="alignleft">
									<span class="title"><?php esc_html_e( 'Title', 'media-library-helper' ); ?></span>
									<input type="text" name="title" value="">
								</label>
							</div>
						<?php endif; // $bulk ?>
					<?php endif; ?>
				</div>
			</fieldset>

			<?php
			list( $columns ) = $this->get_column_info();

			foreach ( $columns as $column_name => $column_display_name ) {

				if ( isset( $core_columns[ $column_name ] ) ) {
					continue;
				}

				if ( $bulk ) {
					/**
					 * Fires once for each column in Bulk Edit mode.
					 *
					 * @since 2.7.0
					 *
					 * @param string $column_name Name of the column to edit.
					 * @param string $post_type   The post type slug.
					 */
					do_action( 'bulk_edit_custom_box', $column_name, $screen->post_type );
				} else {

					/**
					 * Fires once for each column in Quick Edit mode.
					 *
					 * @since 2.7.0
					 *
					 * @param string $column_name Name of the column to edit.
					 * @param string $post_type   The post type slug, or current screen name if this is a taxonomy list table.
					 * @param string $taxonomy    The taxonomy name, if any.
					 */
					do_action( 'quick_edit_custom_box', $column_name, $screen->post_type, '' );
				}
			}
			?>

			<div class="submit inline-edit-save">
				<button type="button" onclick="cancel_bulk_edit()" class="button cancel alignleft"><?php esc_html_e( 'Cancel', 'media-library-helper' ); ?></button>

				<?php if ( ! $bulk ) : ?>
					<?php wp_nonce_field( 'inlineeditnonce', '_inline_edit', false ); ?>
					<button type="button" class="button button-primary save alignright"><?php esc_html_e( 'Update', 'media-library-helper' ); ?></button>
					<span class="spinner"></span>
					<?php
				else :

					wp_nonce_field( 'cdxn_mlh_bulk_edit', 'cdxn_mlh_bulk_edit' );
					submit_button( __( 'Update', 'media-library-helper' ), 'primary alignright', 'bulk_edit', false );

				endif;
				?>

				<input type="hidden" name="post_view" value="<?php echo esc_attr( $m ); ?>" />
				<input type="hidden" name="screen" value="<?php echo esc_attr( $screen->id ); ?>" />
				<?php if ( ! $bulk && ! post_type_supports( $screen->post_type, 'author' ) ) : ?>
					<input type="hidden" name="post_author" value="<?php echo esc_attr( $post->post_author ); ?>" />
				<?php endif; ?>
				<br class="clear" />

				<div class="notice notice-error notice-alt inline hidden">
					<p class="error"></p>
				</div>
			</div>
			</td></tr>
			<?php
			$bulk++;
		endwhile;
		?>
		</tbody></table>
		</form>
		<?php
	}
	/**
	 * Undocumented function
	 *
	 * @param [type] $which mode.
	 * @return void
	 */
	protected function display_tablenav( $which ) {
		if ( 'top' === $which ) {
			wp_nonce_field( 'bulk-' . $this->_args['plural'] );
		}
		?>
		<div class="tablenav <?php echo esc_attr( $which ); ?>">
			<?php if ( $this->has_items() ) : ?>
			<div class="alignleft actions bulkactions media-bulk-action">
				<?php $this->bulk_actions( $which ); ?>
				<button class="button action table-editable" data-editable="false">
					<span class="text-mode-lock"> <?php echo esc_html__( 'Edit mode is locked', 'media-library-helper' ); ?><span class="dashicons dashicons-lock"></span> </span>
					<span class="text-mode-unlock"> <?php echo esc_html__( 'Edit mode is unlocked', 'media-library-helper' ); ?> <span class="dashicons dashicons-unlock"></span> </span>
				</button>
			</div>
				<?php
			endif;
			$this->extra_tablenav( $which );
			$this->pagination( $which );
			?>

			<br class="clear" />
		</div>
		<?php
	}

	/**
	 * Override parent views so we can use the filter bar display.
	 *
	 * @global string $mode List table view mode.
	 */
	public function views() {
		global $mode;

		$views = $this->get_views();

		$this->screen->render_screen_reader_content( 'heading_views' );
		?>
		<div class="wp-filter">
			<div class="filter-items media-filter-items">
				<?php $this->view_switcher( $mode ); ?>
					<div class="media-filter-group">
						<div class="search-form">
						<label for="media-search-input" class="media-search-input-label"><?php esc_html_e( 'Search', 'media-library-helper' ); ?></label>
						<input type="search" id="media-search-input" class="search" name="s" value="<?php _admin_search_query(); ?>"></div>

						<label for="attachment-filter" class="screen-reader-text"><?php esc_html_e( 'Filter by type', 'media-library-helper' ); ?></label>
						<select class="attachment-filters" name="attachment-filter" id="attachment-filter">
							<?php
							if ( ! empty( $views ) ) {
								foreach ( $views as $class => $view ) {
									echo "\t$view\n";
								}
							}
							?>
						</select>

					<?php
					$this->extra_tablenav( 'bar' );

					/** This filter is documented in wp-admin/inclues/class-wp-list-table.php */
					$views = apply_filters( "views_{$this->screen->id}", array() );
					// Back compat for pre-4.0 view links.
					if ( ! empty( $views ) ) {
						echo '<ul class="filter-links">';
						foreach ( $views as $class => $view ) {
							echo "<li class='" . esc_attr( $class ) . "'>" . wp_kses_post( $view ) . '</li>';
						}
						echo '</ul>';
					}
					?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Handles the title column output.
	 *
	 * @since 4.3.0
	 *
	 * @global string $mode List table view mode.
	 *
	 * @param WP_Post $post The current WP_Post object.
	 */
	public function column_title( $post ) {
		list( $mime ) = explode( '/', $post->post_mime_type );

		$attachment_id = $post->ID;

		if ( has_post_thumbnail( $post ) ) {
			$thumbnail_id = get_post_thumbnail_id( $post );

			if ( ! empty( $thumbnail_id ) ) {
				$attachment_id = $thumbnail_id;
			}
		}

		$title      = _draft_or_post_title();
		$title_wraper_start = sprintf(
			'<div class="edit-column-content" data-content-type="title" data-image-id="%s" contenteditable="false">',
			$attachment_id
		);

		$title_wraper_end = '</div>';
		$thumb      = wp_get_attachment_image( $attachment_id, array( 60, 60 ), true, array( 'alt' => '' ) );
		$link_start = '';
		$link_end   = '';

		if ( current_user_can( 'edit_post', $post->ID ) && ! $this->is_trash ) {
			$link_start = sprintf(
				'<a href="%s" aria-label="%s" class="cdxn-title-link">',
				get_edit_post_link( $post->ID ),
				/* translators: %s: Attachment title. */
				esc_attr( sprintf( __( '&#8220;%s&#8221; (Edit)' ), $title ) )
			);
			$link_end = '</div></a>';
		}

		$class = $thumb ? ' class="has-media-icon"' : '';
		?>
		<strong<?php echo $class; ?>>
			<?php
			echo $link_start;

			if ( $thumb ) :
				?>
				<span class="media-icon <?php echo sanitize_html_class( $mime . '-icon' ); ?>"><?php echo $thumb; ?></span>
				<?php
			endif;

			echo $title_wraper_start . $title . $title_wraper_end . $link_end;

			_media_states( $post );
			?>
		</strong>
		<p class="filename">
			<span class="screen-reader-text">
				<?php
				/* translators: Hidden accessibility text. */
				_e( 'File name:' );
				?>
			</span>
			<?php
			$file = get_attached_file( $post->ID );
			echo esc_html( wp_basename( $file ) );
			?>
		</p>
		<?php
		get_inline_data( $post );
	}
}
