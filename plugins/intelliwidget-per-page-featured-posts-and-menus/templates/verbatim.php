<?php
if ( !defined( 'ABSPATH' ) ) exit;
/**
 * verbatim.php - Echos post content verbatim - use for "CMS-style" content blocks
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
    the_intelliwidget_content(); 
endwhile; endif;
?>