<?php
if ( !defined( 'ABSPATH' ) ) exit;
/**
 * menu.php - Template for Custom Page Menus
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
global $post;
$iw_current_post_id    = is_object( $post )?$post->ID:NULL;//have_posts() ? get_the_ID() : NULL;
$iw_current_ancestors  = isset( $iw_current_post_id ) ? get_post_ancestors( $iw_current_post_id ) : array();
$iw_current_parent     = current( $iw_current_ancestors );
 ?>

<ul class="intelliwidget-menu">
  <?php if ( $selected->have_posts() ) : while ( $selected->have_posts() ) : $selected->the_post(); 
    $intelliwidget_post_id    = get_the_intelliwidget_ID();
    ?>
  <li id="intelliwidget_post_<?php echo $intelliwidget_post_id; ?>" class="intelliwidget-menu-item<?php echo ( $iw_current_post_id == $intelliwidget_post_id ? ' intelliwidget-current-menu-item' : '' ) . ( in_array( $intelliwidget_post_id, $iw_current_ancestors ) ? ' intelliwidget-current-menu-ancestor' : '' ) . ( $intelliwidget_post_id == $iw_current_parent ? ' intelliwidget-current-menu-parent' : '' ); ?>">
    <?php if ( has_intelliwidget_image() && 'none' != $instance[ 'image_size' ] ) : ?>
    <div class="intelliwidget-image-container intelliwidget-image-container-<?php echo $instance[ 'image_size' ];?> intelliwidget-align-<?php echo $instance[ 'imagealign' ]; ?>">
      <?php the_intelliwidget_image( empty( $instance[ 'no_img_links' ] ), $instance[ 'image_size' ] ); ?>
    </div>
    <?php endif; ?>
    <?php the_intelliwidget_post_link( NULL, empty( $instance[ 'keep_title' ] ) ); ?>
    <div style="clear:both"></div>
  </li>
  <?php endwhile; endif; ?>
</ul>
