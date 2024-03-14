<?php
/**
 * Template part for calendar archive of comics
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Toocheke
 */

$templates = new Toocheke_Companion_Template_Loader;
if(is_archive()){
      get_header();
}
?>
     <div id="archive-page-calendar-wrapper">
     <header class="page-header">
            <?php
the_archive_title('<h1 class="page-title">', '</h1>');

?>
           <?php
if (is_day() ):

      $templates->get_template_part('content', 'comicarchivetext');
      ?>
      <a href="/comic">Back to Calendar</a>
      <?php
else:
?>


 

      <?php  toocheke_universal_get_calendar();?>    

      <?php
      endif;
      ?>
      </div>
      <?php

if(is_archive()){
      get_footer();
}
