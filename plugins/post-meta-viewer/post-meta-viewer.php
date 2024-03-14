<?php
/**
 * Plugin Name: Post Meta Viewer
 * Plugin URI: https://vinceredigital.com/wp/post-meta-viewer
 * Description: View all post meta that saved in a post, page or custom post type. No settings needed just plug and play.
 * Version: 2.0
 * Author: Yongki Agustinus
 * Author URI: https://vinceredigital.com
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.en.html
 * Domain Path: /languages
 * Text Domain: post-meta-viewer
 *
 * @package PostMetaViewer
 */

/*
	Copyright (C) 2023 Yongki Agustinus

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Forbidden' ); }

define( 'POSTMETAVIEWER_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

/**
 * PostMetaViewer class
 */
class PostMetaViewer {

	/**
	 * Constructor
	 */
	public function __construct() {
		if ( is_admin() ) {
			add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
			add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

			$taxonomies = get_taxonomies( array(), 'objects' );
			foreach ( $taxonomies as $taxonomy ) {
				add_action( $taxonomy->name . '_edit_form', array( __CLASS__, 'term_meta_box_content' ), 90, 2 );
			}
		}
	}

	/**
	 * Term meta box content
	 *
	 * @param object $term Term object.
	 */
	public static function term_meta_box_content( $term ) {
		$term_id    = $term->term_id;
		$term_metas = get_term_meta( $term_id );
		ksort( $term_metas );
		?>
		<div class="postbox pmv-postbox">
			<div class="postbox-header">
				<h2 class="hndle ui-sortable-handle"><?php echo esc_html__( 'Term Meta Viewer', 'post-meta-viewer' ); ?></h2>
			</div>
			<div class="inside">
				<div style="font-weight: bold; padding: 10px 0;">
					<?php
					// translators: %s: number of post meta.
					echo esc_html( sprintf( _n( '%s Term meta found', '%s Term metas found', count( $term_metas ), 'post-meta-viewer' ), number_format_i18n( count( $term_metas ) ) ) );
					?>
				</div>
				<?php if ( $term_metas ) { ?>
					<table class="postmetaviewer-table widefat fixed striped">
						<tbody>
							<?php foreach ( $term_metas as $key => $value ) { ?>
								<?php
								$val = self::is_json( $value[0] ) ? array( json_decode( $value[0] ) ) : array( maybe_unserialize( $value[0] ) );
								?>
								<tr>
									<th width="30%"><?php echo esc_html( $key ); ?></th>
									<td>
										<pre><?php echo esc_html( var_export( $val, true ) ); // phpcs:ignore; ?></pre>
									</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				<?php } ?>
			</div>
		</div>
		<?php
		self::styling();
	}

	/**
	 * Load plugin text domain
	 */
	public static function load_textdomain() {
		load_plugin_textdomain( 'post-meta-viewer', false, basename( POSTMETAVIEWER_PLUGIN_DIR ) . '/languages' );
	}

	/**
	 * Add meta box
	 */
	public static function add_meta_boxes() {
		$post_type = get_post_type();
		add_meta_box( 'post-meta-viewer', __( 'Post Meta Viewer', 'post-meta-viewer' ), array( 'PostMetaViewer', 'meta_box_content' ), $post_type, 'normal', 'low' );
	}

	/**
	 * Meta box content
	 *
	 * @param object $post Post object.
	 */
	public static function meta_box_content( $post ) {

		if ( ! isset( $post->ID ) ) {
			return;
		}

		$post_metas = get_post_meta( $post->ID );
		ksort( $post_metas );
		?>
			<div style="font-weight: bold; padding: 10px 0;">
				<?php
				// translators: %s: number of post meta.
				echo esc_html( sprintf( _n( '%s Post meta found', '%s Post metas found', count( $post_metas ), 'post-meta-viewer' ), number_format_i18n( count( $post_metas ) ) ) );
				?>
			</div>
			<table class="postmetaviewer-table widefat fixed striped">
				<tbody>
					<?php foreach ( $post_metas as $key => $value ) { ?>
						<?php
						$val = self::is_json( $value[0] ) ? array( json_decode( $value[0] ) ) : array( maybe_unserialize( $value[0] ) );
						?>
						<tr>
							<th width="30%"><?php echo esc_html( $key ); ?></th>
							<td>
								<pre><?php echo esc_html( var_export( $val, true ) ); // phpcs:ignore; ?></pre>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
			<?php self::styling(); ?>
		<?php
	}

	/**
	 * Styling
	 */
	public static function styling() {
		?>
		<style>
			.postmetaviewer-table tr:hover {
				background: #d6f4ff;
			}
			.postmetaviewer-table th {
				border-right: 1px dotted #ccd0d4;
				word-break: break-all;
			}
			.postmetaviewer-table pre {
				white-space: pre-wrap;
				tab-size: 4;
				word-break: break-all;
			}

			/* Term meta box */
			.pmv-postbox{
				margin-top: 20px;
			}
			.pmv-postbox h2 {
				font-size: 14px;
				padding: 8px 12px;
				margin: 0;
				line-height: 1.4;
			}
		</style>
		<?php
	}

	/**
	 * Check if json
	 *
	 * @param string $string String to check.
	 */
	public static function is_json( $string ) {
		json_decode( $string );
		return ( json_last_error() === JSON_ERROR_NONE );
	}
}

$post_meta_viewer = new PostMetaViewer();
