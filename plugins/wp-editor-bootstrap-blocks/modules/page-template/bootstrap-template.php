<?php 
/**
 * Bootstrap Blocks for WP Editor Template.
 *
 * @version 1.0.4
 *
 * @package Bootstrap Blocks for WP Editor
 * @author  Virgial Berveling
 * @updated 2019-03-21
 * 
 */

global $bootstrap_template_loaded;
global $post;

$gtb_hide_title = get_post_meta($post->ID, 'gtb_hide_title', true);

$bootstrap_template_loaded = true;



if (function_exists('get_header')):
get_header(); 
endif;
?>
<div class="gtb-sp">
<?php
      while ( have_posts() ) : the_post();
?>
      <div id="page-<?php the_ID(); ?>" <?php post_class('gtb-fw'); ?>>
<?php
	if (empty($gtb_hide_title)) : 
      echo '<h1 class="entry-title">'. gtb_wrap_the_title( get_the_title()) . '</h1>';
      endif;
      
      the_content();
?>
      </div><!-- #post-## -->
<?php
      endwhile; // End of the loop.
?>
</div>
<?php
if (function_exists('get_footer')):
get_footer();
endif;