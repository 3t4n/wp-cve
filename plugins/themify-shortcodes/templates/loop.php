<?php if(!is_single()){ global $more; $more = 0; } //enable more link ?>

<?php 
/** Themify Default Variables
 *  @var object */
global $themify;
$post_class = "post tf_clearfix ";
$post_image_class = 'post-image ' . $themify->image_align;
$post_content_class = 'post-content';
if( in_array( $themify->post_layout, array( 'grid2', 'grid2-thumb', 'grid3', 'grid4' ) ) ) {
	$themify->grid_counter = isset( $themify->grid_counter ) ? $themify->grid_counter : 0;
	$classes = array(
		'grid2' => array( 'col2-1 first', 'col2-1' ),
		'grid2-thumb' => array( 'col2-1 first', 'col2-1' ),
		'grid3' => array( 'col3-1 first', 'col3-1', 'col3-1' ),
		'grid4' => array( 'col4-1 first', 'col4-1', 'col4-1', 'col4-1' ),
	);
	$post_class .= ' shortcode ' . $classes[$themify->post_layout][$themify->grid_counter];
}
if( in_array( $themify->post_layout, array( 'grid2-thumb', 'list-thumb-image' ) ) ) {
	$post_image_class .= ' shortcode col4-1 first';
	$post_content_class .= ' shortcode col4-3';
}
?>

<article id="post-<?php the_id(); ?>" <?php post_class( $post_class ); ?>>
	<?php
	if ( has_post_thumbnail() && ( $post_image = themify_shortcodes_do_img( (int) get_post_thumbnail_id(), $themify->width, $themify->height ) ) ) {
		if ( $themify->hide_image != 'yes' ) : ?>

			<figure class="<?php echo $post_image_class; ?>">
				<?php if( 'yes' != $themify->unlink_image ) : ?><a href="<?php the_permalink(); ?>"><?php endif; ?>
					<img src="<?php echo esc_url( $post_image['url'] ); ?>" width="<?php echo esc_attr( $post_image['width'] ); ?>" height="<?php echo esc_attr( $post_image['height'] ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" />
				<?php if( 'yes' != $themify->unlink_image ) : ?></a><?php endif; ?>
			</figure>
			
		<?php endif; //post image
	} ?>

	<div class="<?php echo $post_content_class; ?>">

		<?php if ( $themify->hide_date != 'yes' ) : ?>
			<time datetime="<?php the_time( 'o-m-d' ) ?>" class="post-date entry-date updated" itemprop="datePublished"><?php echo get_the_date( apply_filters( 'themify_loop_date', '' ) ) ?></time>
		<?php endif; //post date ?>

		<?php if ( $themify->hide_title != 'yes' ) : ?>
			<?php if ( $themify->unlink_title == 'yes' ) : ?>
				<h1 class="post-title entry-title" itemprop="name"><?php the_title(); ?></h1>
			<?php else: ?>
				<h1 class="post-title entry-title" itemprop="name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
			<?php endif; //unlink post title ?>
		<?php endif; //post title ?>    

		<?php if( $themify->hide_meta != 'yes' ) : ?>
			<p class="post-meta entry-meta"> 
				<span class="post-author"><?php echo themify_shortcodes_get_author_link() ?></span>
				<span class="post-category"><?php the_category(', ') ?></span>
				<?php the_tags(' <span class="post-tag">', ', ', '</span>'); ?>
				<?php  if ( comments_open() ) : ?>
					<span class="post-comment"><?php comments_popup_link( __( '0 Comments', 'themify-shortcodes' ), __( '1 Comment', 'themify-shortcodes' ), __( '% Comments', 'themify-shortcodes' ) ); ?></span>
				<?php endif; //post comment ?>
			</p>
		<?php endif; //post meta ?>    
		
		<div class="entry-content" itemprop="articleBody">

		<?php if ( 'excerpt' == $themify->display_content && ! is_attachment() ) : ?>
	
			<?php the_excerpt(); ?>

			<?php if ( apply_filters( 'themify_shortcodes_excerpt_read_more', true ) ) : ?>
				<p><a href="<?php the_permalink(); ?>" class="more-link"><?php _e( 'More &rarr;', 'themify-shortcodes' ) ?></a></p>
			<?php endif; ?>
	
		<?php elseif ( 'none' == $themify->display_content && ! is_attachment() ) : ?>

		<?php else: ?>

			<?php the_content(); ?>

		<?php endif; //display content ?>

		</div><!-- /.entry-content -->

		<?php edit_post_link(__('Edit', 'themify-shortcodes'), '<span class="edit-button">[', ']</span>'); ?>

	</div><!-- /.post-content -->
</article><!-- /.post -->

<?php
if( in_array( $themify->post_layout, array( 'grid2', 'grid2-thumb', 'grid3', 'grid4' ) ) ) {
	$themify->grid_counter++;
	if(
		( ( $themify->post_layout == 'grid2' || $themify->post_layout == 'grid2-thumb' ) && $themify->grid_counter == 2 )
		||( $themify->post_layout == 'grid3' && $themify->grid_counter == 3 )
		||( $themify->post_layout == 'grid4' && $themify->grid_counter == 4 )
	) {
		$themify->grid_counter = 0;
	}
}