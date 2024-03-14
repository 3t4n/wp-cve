<?php
// If check class exists or not.
if ( ! class_exists( 'PTT_Quick_Edit' ) ) {

	class PTT_Quick_Edit extends Post_Type_Transfer {

		/**
		 * Calling class construct.
		 */
		public function __construct() {
			// Add column for quick-edit support
			add_action( 'manage_posts_columns', array( $this, 'ptt_add_column' ) );
			add_action( 'manage_pages_columns', array( $this, 'ptt_add_column' ) );
			add_action( 'manage_posts_custom_column', array( $this, 'ptt_manage_column' ), 10, 2 );
			add_action( 'manage_pages_custom_column', array( $this, 'ptt_manage_column' ), 10, 2 );
			// Quick Edit.
			add_action( 'quick_edit_custom_box', array( $this, 'ptt_quick_edit' ) );
			add_action(	'admin_enqueue_scripts', array( $this, 'ptt_quick_edit_script' ) );
		}

		/**
		 * Adds quickedit button for bulk-editing post types.
		 */
		public function ptt_quick_edit( $column_name = '' ) {

			// Prevent multiple dropdowns in each column.
			if ( 'post_type' !== $column_name ) {
				return;
			} ?>

			<div id="pts_quick_edit" class="inline-edit-group wp-clearfix">
				<label class="alignleft">
					<span class="title"><?php esc_html_e( 'Post Type', 'post-type-transfer' ); ?></span>
					<?php
						wp_nonce_field( 'transfer-post-type', 'ptt-post-types' );
					$this->ptt_select_box();
					?></label>
				</div>
				<?php
			}

		/**
		 * Adds the post type column.
		 */
		public function ptt_add_column( $columns ) {
			return array_merge( $columns, array( 'post_type' => esc_html__( 'Type', 'post-type-transfer' ) ) );
		}

		/**
		 * Manages the post type column.
		 */
		public function ptt_manage_column( $column, $post_id ) {
			switch( $column ) {
				case 'post_type' :
				$post_type = get_post_type_object( get_post_type( $post_id ) ); ?>
				<span data-post-type="<?php echo esc_attr( $post_type->name ); ?>"><?php echo esc_html( $post_type->labels->singular_name ); ?></span>
				<?php
				break;
			}
		}

		/**
		 * Adds quickedit script for getting values into quickedit box.
		 */
		public function ptt_quick_edit_script( $hook = '' ) {
			// if not edit.php admin page
			if ( 'edit.php' !== $hook ) {
				return;
			}
			// Enqueue quick edit JS
			wp_enqueue_script( 'ptt-quick-edit', plugin_dir_url( __FILE__ ) . 'assets/js/post-type-transfer-quickedit.js', array( 'jquery' ), '', true );
			wp_enqueue_style( 'ptt-quick-edit', plugin_dir_url( __FILE__ ) . 'assets/css/post-type-transfer-quickedit.css' );
		}

		/**
		 * Output a post-type dropdown.
		 */
		public function ptt_select_box( $bulk = false ) {
			$selected   = '';
			// Get current post type.
			$post_type = get_post_type();
			// Get all post type objects.
			$get_all_post_types = $this->ptt_get_all_post_types();
			// Exclude post data.
			$exclude_post_data = $this->ptt_exclude_post_type( $get_all_post_types );
			// Start an output buffer
			// Output
			ob_start();
			?>
			<select name="post_type_transfer_types" id="post_type_transfer_types">
				<?php
				// Maybe include "No Change" option for bulk
				if ( true === $bulk ) :
					?><option value="-1"><?php esc_html_e( '&mdash; No Change &mdash;', 'post-type-transfer' ); ?></option><?php
				endif;

				// Loop through post types
				foreach ( $exclude_post_data as $post_type_key => $post_types ) :
					// Skip if user cannot publish this type of post
					if ( ! current_user_can( $post_types->cap->publish_posts ) ) :
						continue;
					endif;
					// Only select if not bulk
					if ( false === $bulk ) :
						$selected = selected( $post_type, $post_type_key );
					endif;
					// Output option
					?>
					<option value="<?php echo esc_attr( $post_types->name ); ?>" <?php echo $selected; // Do not escape ?>>	<?php echo esc_html( $post_types->labels->singular_name ); ?>
					</option>
					<?php
						endforeach;
					?>
				</select>
				<?php
				// Output the current buffer
				echo ob_get_clean();
		}
	}
}
