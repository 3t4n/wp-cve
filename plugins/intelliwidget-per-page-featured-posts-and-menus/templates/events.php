<?php
if ( !defined( 'ABSPATH' ) ) exit;
/**
 * events.php - Template for showing posts by date (calendar)
 *
 * This can be copied to a folder named 'intelliwidget' in your theme
 * to customize the output.
 *
 * @package IntelliWidget
 * @subpackage templates
 * @author Jason C Fleming
 * @copyright 2014-2015 Lilaea Media LLC
 * @access public
 */
if ( $selected->have_posts() ) : while ( $selected->have_posts() ) : $selected->the_post();
?>

<div id="intelliwidget_<?php the_intelliwidget_ID(); ?>" class="intelliwidget-event clearfix">
  <div class="intelliwidget-item">
    <?php if ( has_intelliwidget_image() && 'none' != $instance[ 'image_size' ] ) : ?>
    <div class="intelliwidget-image-container intelliwidget-image-container-<?php echo $instance[ 'image_size' ];?> intelliwidget-align-<?php echo $instance[ 'imagealign' ]; ?>">
      <?php the_intelliwidget_image( empty( $instance[ 'no_img_links' ] ), $instance[ 'image_size' ] ); ?>
    </div>
    <?php endif; ?>
    <div class="intelliwidget-date"> <?php printf( __( '%1$s %2$s, %3$s', 'intelliwidget' ),
            get_the_intelliwidget_date( 'M' ),
            get_the_intelliwidget_date( 'j' ),
            get_the_intelliwidget_date( 'Y' ) ); ?> </div>
    <h3 id="intelliwidget_title_<?php the_intelliwidget_ID(); ?>" class="intelliwidget-title">
      <?php the_intelliwidget_post_link( NULL, empty( $instance[ 'keep_title' ] ) ); ?>
    </h3>
    <div id="intelliwidget_excerpt_<?php the_intelliwidget_ID(); ?>" class="intelliwidget-excerpt">
      <?php the_intelliwidget_excerpt( $instance[ 'length' ], $instance[ 'allowed_tags' ] ); ?>
      <span id="intelliwidget_more_link_<?php the_intelliwidget_ID(); ?>" class="intelliwidget-more-link">
      <?php the_intelliwidget_post_link( $instance[ 'link_text' ] ); ?>
      </span></div>
    <div style="clear:both"></div>
  </div>
</div>
<?php endwhile; endif; ?>
