<?php
/**
 * GFPE: Featured Page Extras Widget - admin options form.
 *
 * NOTE: See important notes on widget class PHP Doc block!
 *
 * @package    Genesis Featured Page Extras
 * @subpackage Widgets
 * @author     David Decker - DECKERWEB
 * @copyright  Copyright (c) 2014, David Decker - DECKERWEB
 * @license    http://www.opensource.org/licenses/gpl-license.php GPL-2.0+
 * @link       http://genesisthemes.de/en/wp-plugins/genesis-featured-page-extras/
 * @link       http://deckerweb.de/twitter
 *
 * @since      1.0.0
 */

/**
 * Prevent direct access to this file.
 *
 * @since 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Sorry, you are not allowed to access this file directly.' );
}


/** Begin form code */
?>

<p>
	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><strong><?php _e( 'Widget Title', 'genesis-featured-page-extras' ); ?>:</strong></label>
	<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance[ 'title' ] ); ?>" class="widefat" />
</p>

<p>
	<input type="checkbox" value="1" <?php checked( '1', $instance[ 'title_url_page' ] ); ?> id="<?php echo $this->get_field_id( 'title_url_page' ); ?>" name="<?php echo $this->get_field_name( 'title_url_page' ); ?>" />
	<label for="<?php echo $this->get_field_id( 'title_url_page' ); ?>"><?php echo __( 'Use Page Link?', 'genesis-featured-page-extras' ) . ' <em>&ndash; ' . __( 'OR', 'genesis-featured-page-extras' ) . ' &ndash;</em> '; ?></label> <label for="<?php echo $this->get_field_id( 'title_url' ); ?>"><?php echo $optional_content_string . __( 'Title URL', 'genesis-featured-page-extras' ) . $full_path_url; ?>:</label>
	<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title_url' ); ?>" name="<?php echo $this->get_field_name( 'title_url' ); ?>" value="<?php echo esc_url( $instance[ 'title_url' ] ); ?>" />
</p>

<p>
	<input type="checkbox" value="1" <?php checked( '1', $instance[ 'title_url_target' ] ); ?> id="<?php echo $this->get_field_id( 'title_url_target' ); ?>" name="<?php echo $this->get_field_name( 'title_url_target' ); ?>" />
	<label for="<?php echo $this->get_field_id( 'title_url_target' ); ?>"><?php echo $optional_content_string; _e( 'Open the URL in a new window/ tab?', 'genesis-featured-page-extras' ); ?></label>
</p>

<p>
	<input type="checkbox" value="1" <?php checked( '1', $instance[ 'title_hide' ] ); ?> id="<?php echo $this->get_field_id( 'title_hide' ); ?>" name="<?php echo $this->get_field_name( 'title_hide' ); ?>" />
	<label for="<?php echo $this->get_field_id( 'title_hide' ); ?>">
		<?php echo $optional_content_string; _e( 'Do not display the Title?' , 'genesis-featured-page-extras' ); ?>
	</label>
</p>

<hr <?php echo $gfpe_hr_style; ?> />

<p>
	<label for="<?php echo $this->get_field_id( 'page_id' ); ?>"><strong><?php _e( 'Select Page to display', 'genesis-featured-page-extras' ); ?>:</strong></label><br />
<?php wp_dropdown_pages( array( 'name' => $this->get_field_name( 'page_id' ), 'selected' => $instance[ 'page_id' ] ) ); ?>
</p>

<hr <?php echo $gfpe_hr_style; ?> />

<p>
	<input id="<?php echo $this->get_field_id( 'image_show' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'image_show' ); ?>" value="1"<?php checked( $instance[ 'image_show' ] ); ?> />
	<label for="<?php echo $this->get_field_id( 'image_show' ); ?>"><?php _e( 'Show Featured Image', 'genesis-featured-page-extras' ); ?></label>
</p>

<div <?php echo $div_intent; ?>>
	<p>
		<label for="<?php echo $this->get_field_id( 'image_url' ); ?>"><?php echo $optional_content_string . __( 'Image URL', 'genesis-featured-page-extras' ) . $full_path_url; ?>:</label>
		<input type="text" id="<?php echo $this->get_field_id( 'image_url' ); ?>" name="<?php echo $this->get_field_name( 'image_url' ); ?>" value="<?php echo esc_attr( $instance[ 'image_url' ] ); ?>" class="widefat tgm-new-media-image" /><input type="submit" class="button button-highlighted alignright" name="<?php echo $this->get_field_name( 'gfpe_uploader_button' ); ?>" id="<?php echo $this->get_field_id( 'gfpe_uploader_button' ); ?>" title="<?php esc_attr__( 'Click Here to Upload an Image via the Media Manager', 'genesis-featured-page-extras' ); ?>" value="<?php _e( 'Upload Image via Media Manager', 'genesis-featured-page-extras' ); ?>" onclick="gfpe_uploader.uploader( '<?php echo $this->id; ?>', '<?php echo $widget_id_prefix; ?>' ); return false;" /><div class="clear"></div>
	</p>

	<p>
		<label for="<?php echo $this->get_field_id( 'image_size' ); ?>"><?php _e( 'Image Size', 'genesis-featured-page-extras' ); ?>:</label>
		<select id="<?php echo $this->get_field_id( 'image_size' ); ?>" name="<?php echo $this->get_field_name( 'image_size' ); ?>">
			<?php
				$sizes = genesis_get_additional_image_sizes();
				foreach ( (array) $sizes as $name => $size ) {
					echo '<option value="' . $name . '" ' . selected( $name, $instance[ 'image_size' ], FALSE ) . '>' . _x( 'Custom', 'in drop-down select for image sizes', 'genesis-featured-page-extras' ) . ': ' . $name . ' (' . absint( $size[ 'width' ] ) . ' &#x000D7; ' . absint( $size[ 'height' ] ) . ')</option>';
				}  // end foreach
			?>
			<option value="thumbnail" <?php selected( 'thumbnail', $instance[ 'image_size' ] ); ?>><?php echo $wp_string; ?> thumbnail (<?php echo absint( get_option( 'thumbnail_size_w' ) ); ?> &#x000D7; <?php echo absint( get_option( 'thumbnail_size_h' ) ); ?>)</option>
			<option value="medium" <?php selected( 'medium', $instance[ 'image_size' ] ); ?>><?php echo $wp_string; ?> medium (<?php echo absint( get_option( 'medium_size_w' ) ); ?> &#x000D7; <?php echo absint( get_option( 'medium_size_h' ) ); ?>)</option>
			<option value="large" <?php selected( 'large', $instance[ 'image_size' ] ); ?>><?php echo $wp_string; ?> large (<?php echo absint( get_option( 'large_size_w' ) ); ?> &#x000D7; <?php echo absint( get_option( 'large_size_h' ) ); ?>)</option>
			<option value="full" <?php selected( 'full', $instance[ 'image_size' ] ); ?>><?php echo $wp_string; ?> full (<?php _e( 'Original size, with caution!', 'genesis-featured-page-extras' ); ?>)</option>
		</select>
	</p>

	<p>
		<label for="<?php echo $this->get_field_id( 'image_alignment' ); ?>"><?php _e( 'Image Alignment', 'genesis-featured-page-extras' ); ?>:</label>
		<select id="<?php echo $this->get_field_id( 'image_alignment' ); ?>" name="<?php echo $this->get_field_name( 'image_alignment' ); ?>">
			<option value="alignnone">- <?php _ex( 'None', 'Image alignment', 'genesis-featured-page-extras' ); ?> -</option>
			<option value="alignleft" <?php selected( 'alignleft', $instance[ 'image_alignment' ] ); ?>><?php _e( 'Left', 'genesis-featured-page-extras' ); ?></option>
			<option value="alignright" <?php selected( 'alignright', $instance[ 'image_alignment' ] ); ?>><?php _e( 'Right', 'genesis-featured-page-extras' ); ?></option>
			<option value="aligncenter" <?php selected( 'aligncenter', $instance[ 'image_alignment' ] ); ?>><?php _e( 'Center', 'genesis-featured-page-extras' ); ?></option>
		</select>
	</p>

	<p>
		<input type="checkbox" value="1" <?php checked( '1', $instance[ 'image_link' ] ); ?> id="<?php echo $this->get_field_id( 'image_link' ); ?>" name="<?php echo $this->get_field_name( 'image_link' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'image_link' ); ?>"><?php _e( 'Link to Page Permalink?' , 'genesis-featured-page-extras' ); ?></label>
	</p>
</div>

<hr <?php echo $gfpe_hr_style; ?> />

<p>
	<input id="<?php echo $this->get_field_id( 'page_title_show' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'page_title_show' ); ?>" value="1"<?php checked( $instance[ 'page_title_show' ] ); ?> />
	<label for="<?php echo $this->get_field_id( 'page_title_show' ); ?>"><?php _e( 'Show Page Title', 'genesis-featured-page-extras' ); ?></label>
</p>

<div <?php echo $div_intent; ?>>
	<p>
		<label for="<?php echo $this->get_field_id( 'page_title_limit' ); ?>"><?php _e( 'Limit page title to', 'genesis-featured-page-extras' ); ?>&nbsp;<input type="number" id="<?php echo $this->get_field_id( 'page_title_limit' ); ?>" name="<?php echo $this->get_field_name( 'page_title_limit' ); ?>" value="<?php echo absint( $instance[ 'page_title_limit' ] ); ?>" class="small-text" />&nbsp;<?php _e( 'characters', 'genesis-featured-page-extras' ); ?></label>
	</p>

	<p>
		<label for="<?php echo $this->get_field_id( 'page_title_cutoff' ); ?>"><?php _e( 'Page title cutoff symbol', 'genesis-featured-page-extras' ); ?>:</label>
		<input type="text" id="<?php echo $this->get_field_id( 'page_title_cutoff' ); ?>" name="<?php echo $this->get_field_name( 'page_title_cutoff' ); ?>" value="<?php echo esc_attr( $instance[ 'page_title_cutoff' ] ); ?>" class="small-text" />
	</p>

	<p>
		<input type="checkbox" value="1" <?php checked( '1', $instance[ 'page_title_link' ] ); ?> id="<?php echo $this->get_field_id( 'page_title_link' ); ?>" name="<?php echo $this->get_field_name( 'page_title_link' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'page_title_link' ); ?>">
			<?php _e( 'Link to Page Permalink?' , 'genesis-featured-page-extras' ); ?>
		</label>
	</p>
</div>

<p>
	<input id="<?php echo $this->get_field_id( 'byline_show' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'byline_show' ); ?>" value="1"<?php checked( $instance[ 'byline_show' ] ); ?> />
	<label for="<?php echo $this->get_field_id( 'byline_show' ); ?>"><?php _e( 'Show Page Info (Byline)', 'genesis-featured-page-extras' ); ?></label>
	<input type="text" id="<?php echo $this->get_field_id( 'page_post_info' ); ?>" name="<?php echo $this->get_field_name( 'page_post_info' ); ?>" value="<?php echo esc_attr( $instance['page_post_info'] ); ?>" class="widefat" />
</p>

<hr <?php echo $gfpe_hr_style; ?> />

<fieldset>
	<legend><?php _e( 'Select which type of content you would like to use as the page teaser', 'genesis-featured-page-extras' ); ?>:</legend>

	<p>
		<input type="radio" name="<?php echo $this->get_field_name( 'content_type' ); ?>" id="<?php echo $this->get_field_id( 'content_type' ); ?>_val1" value="page_content" <?php checked( $instance[ 'content_type' ], 'page_content' ); ?> />
		<label for="<?php echo $this->get_field_id( 'content_type' ); ?>_val1"><strong><?php _e( 'Page Content', 'genesis-featured-page-extras' ); ?></strong></label>

			<div <?php echo $div_intent; ?>>
				<p>
					<label for="<?php echo $this->get_field_id( 'content_limit' ); ?>"><?php echo $optional_content_string; _e( 'Content Character Limit', 'genesis-featured-page-extras' ); ?>:</label>&nbsp;<input type="number" id="<?php echo $this->get_field_id( 'content_limit' ); ?>" name="<?php echo $this->get_field_name( 'content_limit' ); ?>" value="<?php echo absint( $instance[ 'content_limit' ] ); ?>" class="small-text" />&nbsp;<?php _e( 'characters', 'genesis-featured-page-extras' ); ?></label>
				</p>

				<p>
					<input type="checkbox" id="<?php echo $this->get_field_id( 'page_keep_wpautop' ); ?>" name="<?php echo $this->get_field_name( 'page_keep_wpautop' ); ?>" value="1"<?php checked( $instance[ 'page_keep_wpautop' ] ); ?> />&nbsp;<label for="<?php echo $this->get_field_id( 'page_keep_wpautop' ); ?>"><?php _e( 'Keep original paragraphs?', 'genesis-featured-page-extras' ); ?> <small>(<?php echo __( 'via', 'genesis-featured-page-extras' ) . ' <code ' . $code_font_size . '>wpautop</code>'; ?>)</small></label>
				</p>
			</div>
	</p>

	<p>
		<input type="radio" name="<?php echo $this->get_field_name( 'content_type' ); ?>" id="<?php echo $this->get_field_id( 'content_type' ); ?>_val2" value="page_excerpt" <?php checked( $instance[ 'content_type' ], 'page_excerpt' ); ?> />
		<label for="<?php echo $this->get_field_id( 'content_type' ); ?>_val2"><strong><?php _e( 'Page Excerpt', 'genesis-featured-page-extras' ); ?></strong></label>
	</p>

	<p>
		<input type="radio" name="<?php echo $this->get_field_name( 'content_type' ); ?>" id="<?php echo $this->get_field_id( 'content_type' ); ?>_val3" value="custom_user_content" <?php checked( $instance[ 'content_type' ], 'custom_user_content' ); ?>/>
		<label for="<?php echo $this->get_field_id( 'content_type' ); ?>_val3"><strong><?php _e( 'Custom Content (below)', 'genesis-featured-page-extras' ); ?></strong></label>
	</p>

	<p>
		<input type="radio" name="<?php echo $this->get_field_name( 'content_type' ); ?>" id="<?php echo $this->get_field_id( 'content_type' ); ?>_val4" value="no_content" <?php checked( $instance[ 'content_type' ], 'no_content' ); ?>/>
		<label for="<?php echo $this->get_field_id( 'content_type' ); ?>_val4"><strong><?php _e( 'No Content At All', 'genesis-featured-page-extras' ); ?></strong></label>
	</p>

	<p>
		<label for="<?php echo $this->get_field_id( 'custom_content' ); ?>"><?php echo $optional_content_string; _e( 'Custom Text Content', 'genesis-featured-page-extras' ); ?>:</label>
		<textarea name="<?php echo $this->get_field_name( 'custom_content' ); ?>" id="<?php echo $this->get_field_id( 'custom_content' ); ?>" rows="7" class="widefat"><?php echo htmlspecialchars( $instance[ 'custom_content' ] ); ?></textarea>

			<input type="checkbox" id="<?php echo $this->get_field_id( 'custom_wpautop' ); ?>" name="<?php echo $this->get_field_name( 'custom_wpautop' ); ?>" <?php checked( isset( $instance[ 'custom_wpautop' ] ) ? $instance[ 'custom_wpautop' ] : 0 ); ?> />&nbsp;<label for="<?php echo $this->get_field_id( 'custom_wpautop' ); ?>"><?php _e( 'Automatically add paragraphs?', 'genesis-featured-page-extras' ); ?></label>
	</p>
</fieldset>

<hr <?php echo $gfpe_hr_style; ?> />

<p>
	<label for="<?php echo $this->get_field_id( 'more_link_text' ); ?>"><?php echo sprintf( __( '%s Link Text', 'genesis-featured-page-extras' ), '<em>' . __( 'More', 'genesis-featured-page-extras' ) . '</em>' ); ?>:</label>
	<input type="text" id="<?php echo $this->get_field_id( 'more_link_text' ); ?>" name="<?php echo $this->get_field_name( 'more_link_text' ); ?>" value="<?php echo esc_attr( $instance[ 'more_link_text' ] ); ?>" class="widefat" />
</p>

<p>
	<label for="<?php echo $this->get_field_id( 'more_link_url' ); ?>"><?php echo $optional_content_string . sprintf( __( '%s Link URL', 'genesis-featured-page-extras' ), '<em>' . __( 'More', 'genesis-featured-page-extras' ) . '</em>' ) . $full_path_url; ?>:</label>
	<input type="text" id="<?php echo $this->get_field_id( 'more_link_url' ); ?>" name="<?php echo $this->get_field_name( 'more_link_url' ); ?>" value="<?php echo esc_url( $instance[ 'more_link_url' ] ); ?>" class="widefat" />
</p>

<p>
	<label for="<?php echo $this->get_field_id( 'more_link_target' ); ?>"><?php echo $optional_content_string . sprintf( __( '%s Link Target (for custom URLs)', 'genesis-featured-page-extras' ), '<em>' . __( 'More', 'genesis-featured-page-extras' ) . '</em>' ); ?>:</label>
	<select id="<?php echo $this->get_field_id( 'more_link_target' ); ?>" name="<?php echo $this->get_field_name( 'more_link_target' ); ?>">
		<option value="none">- <?php _ex( 'None', 'More link target', 'genesis-featured-page-extras' ); ?> -</option>
		<option value="_self" <?php selected( '_self', $instance[ 'more_link_target' ] ); ?>><?php _e( 'Current window/ tab (_self)', 'genesis-featured-page-extras' ); ?></option>
		<option value="_new" <?php selected( '_new', $instance[ 'more_link_target' ] ); ?>><?php _e( 'New window/ tab (_new)', 'genesis-featured-page-extras' ); ?></option>
		<option value="_blank" <?php selected( '_blank', $instance[ 'more_link_target' ] ); ?>><?php _e( 'New window/ tab (_blank)', 'genesis-featured-page-extras' ); ?></option>
	</select>
</p>

<p>
	<label for="<?php echo $this->get_field_id( 'more_link_class' ); ?>"><?php echo $optional_content_string . sprintf( __( 'Extra CSS Class for Custom %s Link', 'genesis-featured-page-extras' ), '<em>' . __( 'More', 'genesis-featured-page-extras' ) . '</em>' ); ?>:</label>
	<input type="text" id="<?php echo $this->get_field_id( 'more_link_class' ); ?>" name="<?php echo $this->get_field_name( 'more_link_class' ); ?>" value="<?php echo esc_attr( $instance[ 'more_link_class' ] ); ?>"  />
</p>

<p>
	<input type="checkbox" id="<?php echo $this->get_field_id( 'more_link_show' ); ?>" name="<?php echo $this->get_field_name( 'more_link_show' ); ?>" value="1"<?php checked( $instance[ 'more_link_show' ] ); ?> />&nbsp;<label for="<?php echo $this->get_field_id( 'more_link_show' ); ?>"><?php echo sprintf(
			__( 'Show %s Link also on %s mode?', 'genesis-featured-page-extras' ),
			'<em>' . __( 'More', 'genesis-featured-page-extras' ) . '</em>',
			'<em>' . __( 'Page Excerpt', 'genesis-featured-page-extras' ) . '</em>'
		); ?></label>
</p>

<hr <?php echo $gfpe_hr_style; ?> />

<p>
	<label for="<?php echo $this->get_field_id( 'widget_display' ); ?>">
		<?php _e( 'Where to display this widget?', 'genesis-featured-page-extras' ); ?>:
		<select id="<?php echo $this->get_field_id( 'widget_display' ); ?>" name="<?php echo $this->get_field_name( 'widget_display' ); ?>">        
			<?php
				printf( '<option value="global" %s>%s</option>', selected( 'global', $instance[ 'widget_display' ], 0 ), __( 'Global (default)', 'genesis-featured-page-extras' ) );
				
				echo $gfpe_select_divider;

				printf( '<option value="single_posts" %s>%s</option>', selected( 'single_posts', $instance[ 'widget_display' ], 0 ), sprintf(
					__( 'For Single %s', 'genesis-featured-page-extras' ),
					__( 'Posts', 'genesis-featured-page-extras' )
				) );

				printf( '<option value="single_pages" %s>%s</option>', selected( 'single_pages', $instance[ 'widget_display' ], 0 ), sprintf(
					__( 'For Single %s', 'genesis-featured-page-extras' ),
					__( 'Pages', 'genesis-featured-page-extras' )
				) );

				printf( '<option value="single_posts_pages" %s>%s</option>', selected( 'single_posts_pages', $instance[ 'widget_display' ], 0 ), __( 'For Both, Single Posts &amp; Pages', 'genesis-featured-page-extras' ) );
			?>
		</select>
	</label>
</p>

<p>
	<input type="checkbox" id="<?php echo $this->get_field_id( 'not_in_public' ); ?>" name="<?php echo $this->get_field_name( 'not_in_public' ); ?>" value="1" <?php checked( '1', $instance[ 'not_in_public' ] ); ?> />&nbsp;<label for="<?php echo $this->get_field_id( 'not_in_public' ); ?>"><?php _e( 'Only displaying for logged in users?' , 'genesis-featured-page-extras' ); ?></label>
</p>

<hr <?php echo $gfpe_hr_style; ?> />

<p>
	<label for="<?php /** Optional intro text */ echo $this->get_field_id( 'intro_text' ); ?>"><?php _e( 'Optional intro text:', 'genesis-featured-page-extras' ); ?>
		<small><?php echo sprintf( __( 'Add some additional %s info. NOTE: Just leave blank to not use at all.', 'genesis-featured-page-extras' ), __( 'Page', 'genesis-featured-page-extras' ) ); ?></small>
		<textarea name="<?php echo $this->get_field_name( 'intro_text' ); ?>" id="<?php echo $this->get_field_id( 'intro_text' ); ?>" rows="2" class="widefat"><?php echo htmlspecialchars( $instance[ 'intro_text' ] ); ?></textarea>
	</label>
</p>

<p>
	<label for="<?php /** Optional outro text */ echo $this->get_field_id( 'outro_text' ); ?>"><?php _e( 'Optional outro text:', 'genesis-featured-page-extras' ); ?>
		<small><?php echo sprintf( __( 'Add some additional %s info. NOTE: Just leave blank to not use at all.', 'genesis-featured-page-extras' ), __( 'Page', 'genesis-featured-page-extras' ) ); ?></small>
		<textarea name="<?php echo $this->get_field_name( 'outro_text' ); ?>" id="<?php echo $this->get_field_id( 'outro_text' ); ?>" rows="2" class="widefat"><?php echo htmlspecialchars( $instance[ 'outro_text' ] ); ?></textarea>
	</label>
</p>

<?php
/** ^End form code */