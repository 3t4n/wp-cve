<?php
/*
Widget Admin Form
Plugin: Recent Posts Widget Advanced
Since: 0.3
Author: KGM Servizi
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$title                 = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
$post_type             = isset( $instance['post_type'] ) ? esc_attr( $instance['post_type'] ) : 'post';
$post_format           = isset( $instance['post_format'] ) ? esc_attr( $instance['post_format'] ) : '';
$exclude_post_format   = isset( $instance['exclude_post_format'] ) ? (bool) $instance['exclude_post_format'] : false;
$category              = isset( $instance['category'] ) ? esc_attr( $instance['category'] ) : '';
$exclude               = isset( $instance['exclude'] ) ? (bool) $instance['exclude'] : false;
$tag                   = isset( $instance['tag'] ) ? esc_attr( $instance['tag'] ) : '';
$exclude_tag           = isset( $instance['exclude_tag'] ) ? (bool) $instance['exclude_tag'] : false;
$author                = isset( $instance['author'] ) ? esc_attr( $instance['author'] ) : '';
$exclude_author        = isset( $instance['exclude_author'] ) ? (bool) $instance['exclude_author'] : false;
$show_sticky_posts     = isset( $instance['show_sticky_posts'] ) ? (bool) $instance['show_sticky_posts'] : false;
$show_thumb            = isset( $instance['show_thumb'] ) ? (bool) $instance['show_thumb'] : false;
$show_date             = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
$show_author           = isset( $instance['show_author'] ) ? (bool) $instance['show_author'] : false;
$hide_on_same_cpt_page = isset( $instance['hide_on_same_cpt_page'] ) ? (bool) $instance['hide_on_same_cpt_page'] : false;
$number                = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
$offset                = isset( $instance['offset'] ) ? absint( $instance['offset'] ) : 0;
?>
<p><label><b>You can find post, page, category (taxonomy) ID in admin list.</b></label>

<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><b><?php _e( 'Title:' ); ?></b></label>
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p><br />

<p><label for="<?php echo $this->get_field_id( 'post_type' ); ?>"><b><?php _e( 'Post Type:' ); ?></b></label>
<input class="widefat" id="<?php echo $this->get_field_id( 'post_type' ); ?>" name="<?php echo $this->get_field_name( 'post_type' ); ?>" type="text" value="<?php echo $post_type; ?>" /><?php _e( 'Post Type separated by commas. If you use custom Post Type, categories and tags not work. Default: post' ); ?></p>

<p><b>List of post type:</b> <?php show_post_type(); ?></p>

<p style="margin-top: 40px"><label for="<?php echo $this->get_field_id( 'post_format' ); ?>"><b><?php _e( 'Post Format:' ); ?></b></label>
<input class="widefat" id="<?php echo $this->get_field_id( 'post_format' ); ?>" name="<?php echo $this->get_field_name( 'post_format' ); ?>" type="text" value="<?php echo $post_format; ?>" /><?php _e( 'Post Format separated by commas.' ); ?></p>

<p><b>List of used post format:</b> <?php show_list_taxonomy_slug('post_format') ?></p>

<p><input class="checkbox" type="checkbox"<?php checked( $exclude_post_format ); ?> id="<?php echo $this->get_field_id( 'exclude_post_format' ); ?>" name="<?php echo $this->get_field_name( 'exclude_post_format' ); ?>" />
<label for="<?php echo $this->get_field_id( 'exclude_post_format' ); ?>"><?php _e( 'Exclude posts of this format?' ); ?></label></p><br />

<p><label for="<?php echo $this->get_field_id( 'category' ); ?>"><b><?php _e( 'Category:' ); ?></b></label>
<input class="widefat" id="<?php echo $this->get_field_id( 'category' ); ?>" name="<?php echo $this->get_field_name( 'category' ); ?>" type="text" value="<?php echo $category; ?>" /><?php _e( 'Categories ID separated by commas.' ); ?></p>

<p><input class="checkbox" type="checkbox"<?php checked( $exclude ); ?> id="<?php echo $this->get_field_id( 'exclude' ); ?>" name="<?php echo $this->get_field_name( 'exclude' ); ?>" />
<label for="<?php echo $this->get_field_id( 'exclude' ); ?>"><?php _e( 'Exclude posts in this category?' ); ?></label></p><br />

<p><label for="<?php echo $this->get_field_id( 'tag' ); ?>"><b><?php _e( 'Tag:' ); ?></b></label>
<input class="widefat" id="<?php echo $this->get_field_id( 'tag' ); ?>" name="<?php echo $this->get_field_name( 'tag' ); ?>" type="text" value="<?php echo $tag; ?>" /><?php _e( 'Tags ID separated by commas.' ); ?></p>

<p><input class="checkbox" type="checkbox"<?php checked( $exclude_tag ); ?> id="<?php echo $this->get_field_id( 'exclude_tag' ); ?>" name="<?php echo $this->get_field_name( 'exclude_tag' ); ?>" />
<label for="<?php echo $this->get_field_id( 'exclude_tag' ); ?>"><?php _e( 'Exclude posts in this tags?' ); ?></label></p><br />

<p><label for="<?php echo $this->get_field_id( 'author' ); ?>"><b><?php _e( 'Author:' ); ?></b></label>
<input class="widefat" id="<?php echo $this->get_field_id( 'author' ); ?>" name="<?php echo $this->get_field_name( 'author' ); ?>" type="text" value="<?php echo $author; ?>" /><?php _e( 'Authors ID separated by commas.' ); ?></p>

<p><input class="checkbox" type="checkbox"<?php checked( $exclude_author ); ?> id="<?php echo $this->get_field_id( 'exclude_author' ); ?>" name="<?php echo $this->get_field_name( 'exclude_author' ); ?>" />
<label for="<?php echo $this->get_field_id( 'exclude_author' ); ?>"><?php _e( 'Exclude posts of this authors?' ); ?></label></p><br />

<p><input class="checkbox" type="checkbox"<?php checked( $show_sticky_posts ); ?> id="<?php echo $this->get_field_id( 'show_sticky_posts' ); ?>" name="<?php echo $this->get_field_name( 'show_sticky_posts' ); ?>" />
<label for="<?php echo $this->get_field_id( 'show_sticky_posts' ); ?>"><?php _e( 'Display sticky posts?' ); ?></label></p>

<p><input class="checkbox" type="checkbox"<?php checked( $show_thumb ); ?> id="<?php echo $this->get_field_id( 'show_thumb' ); ?>" name="<?php echo $this->get_field_name( 'show_thumb' ); ?>" />
<label for="<?php echo $this->get_field_id( 'show_thumb' ); ?>"><?php _e( 'Display post thumbnail?' ); ?></label></p>

<p><input class="checkbox" type="checkbox"<?php checked( $show_date ); ?> id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>" />
<label for="<?php echo $this->get_field_id( 'show_date' ); ?>"><?php _e( 'Display post date?' ); ?></label></p>

<p><input class="checkbox" type="checkbox"<?php checked( $show_author ); ?> id="<?php echo $this->get_field_id( 'show_author' ); ?>" name="<?php echo $this->get_field_name( 'show_author' ); ?>" />
<label for="<?php echo $this->get_field_id( 'show_author' ); ?>"><?php _e( 'Display post author?' ); ?></label></p>

<p><input class="checkbox" type="checkbox"<?php checked( $hide_on_same_cpt_page ); ?> id="<?php echo $this->get_field_id( 'hide_on_same_cpt_page' ); ?>" name="<?php echo $this->get_field_name( 'hide_on_same_cpt_page' ); ?>" />
<label for="<?php echo $this->get_field_id( 'hide_on_same_cpt_page' ); ?>"><?php _e( 'Hide on the same cpt page?' ); ?></label></p><br />

<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts in widget:' ); ?></label>
<input class="tiny-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" /></p>

<p><label for="<?php echo $this->get_field_id( 'offset' ); ?>"><?php _e( 'Offset:' ); ?></label>
<input class="tiny-text" id="<?php echo $this->get_field_id( 'offset' ); ?>" name="<?php echo $this->get_field_name( 'offset' ); ?>" type="number" step="1" min="0" value="<?php echo $offset; ?>" size="3" /></p>
