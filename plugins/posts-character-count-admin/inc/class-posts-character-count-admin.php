<?php

class Posts_Character_Count_Admin {
	public static function init() {
		$plugin = new self();
		add_action( 'admin_init', array( $plugin, 'admin_init' ) );
	}

	public function admin_init() {
		// Only run our customization on the 'edit.php' page in the admin
		add_action( 'load-edit.php', array( $this, 'admin_edit_screens' ) );

		// Only run our customization on the 'post.php' page in the admin
		add_action( 'load-post.php', array( $this, 'admin_post_screens' ) );
	}

	// Add the sortable character count column to all manage posts screens including pages
	public function admin_edit_screens() {
		// Add the character count column to all manage posts screens except pages
		add_filter( 'manage_posts_columns', array( $this, 'admin_edit_columns' ) );
		add_action( 'manage_posts_custom_column', array( $this, 'admin_edit_column_values' ), 10, 2 );

		// Add the character count column to the manage pages screen
		add_filter( 'manage_pages_columns', array( $this, 'admin_edit_columns' ) );
		add_action( 'manage_pages_custom_column', array( $this, 'admin_edit_column_values' ), 10, 2 );
	}

	// Add the character count to the edit screens of all post types including pages
	public function admin_post_screens() {
		add_action( 'admin_footer', array( $this, 'post_edit_screen_admin_footer' ) );
	}

	/* Methods and Filters for the column in the Manage Posts/Pages SubPanel */

	public function admin_edit_columns( $columns ) {
		$columns['count'] = __( 'Character Count', 'posts-character-count-admin' );

		return $columns;
	}

	public function admin_edit_column_values( $column, $post_id ) {
		global $post;
		if ( 'count' == $column ) {
			$stat = new Posts_Character_Count( $post->post_content );
			echo $stat->count_characters() . ' ' . __( ' characters', 'posts-character-count-admin' );
		}
	}

	/* Methods and Action for the characters count in the Edit Posts/Pages SubPanel */

	public function post_edit_screen_admin_footer() {
		global $post;

		if ( ! empty( $post ) && isset( $post->post_content ) ) {
			$stat = new Posts_Character_Count( $post->post_content );

			$template = __(
					'Character count:',
					'posts-character-count-admin'
				) . ' %d ' . __(
					'characters (incl. spaces)',
					'posts-character-count-admin'
				);

			printf(
				'<script type="text/javascript">
					jQuery(document).ready(function ($) {
						var $div = $("#post-status-info");
						if ($div.length > 0) {
							$div.append("<span class=\"inside\" style=\"margin-left: 10px;\">%s</span>");
						}
					});
				</script>', sprintf( $template, $stat->count_characters() )
			);
		}
	}
} // End class