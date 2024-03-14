<?php
/**
 * The template for displaying a single video in archive pages.
 */
?>

<div class="media <?php if( has_post_thumbnail() ):?>with-image<?php endif;?> vimeotheque-video" id="vimeotheque-video-<?php the_ID();?>">
	<a href="<?php the_permalink();?>" title="<?php echo esc_attr( get_the_title() );?>">
        <?php
            vimeotheque_the_post_thumbnail([ 720, 404], [ 'class' => 'post-thumbnail']);
        ?>
    </a>

	<?php
		the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
	?>

	<div class="video-meta">
        <a class="author-link" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
			<?php the_author();?>
        </a>

        <span class="post-date">
		    <?php the_date( '', ' &bull; ' ); ?>
        </span>
	</div>
	<!-- .meta -->
</div><!-- #vimeotheque-video-<?php the_ID();?> -->