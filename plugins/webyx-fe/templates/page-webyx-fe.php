<?php
/**
 * @link       https://webyx.it/wfe-guide
 * @since      1.0.0
 * @package    webyx-fe
 * @subpackage webyx-fe/templates
 */
  if ( ! defined( 'WPINC' ) ) {
    die; 
  }
  include_once 'header-webyx.php'; 
  if ( have_posts() ) :
    while ( have_posts() ) : the_post();
      the_content();
    endwhile;
  endif; 
  include_once 'footer-webyx.php';