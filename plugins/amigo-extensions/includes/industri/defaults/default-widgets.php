<?php
$theme_logo = esc_url(AMIGO_PLUGIN_DIR_URL .'includes/industri/assets/images/logo-white.png');

$text = '<a class="navbar-brand" href="#"> <img src="'.$theme_logo.'" class="img-fluid logo-img" alt="" /></a>
<div class="textwidget">
<p>Lorem Ipsum is simply dummy text of the printing. Lorem Ipsum has been unknown. Lorem ipsum dolor sit amet, labore et aliqua.</p>
<div class="social-media">
<a href="#" data-bs-toggle="tooltip" title="Twitter" data-placement="bottom"> <i class="fa fa-twitter"></i> </a>
<a href="#" data-bs-toggle="tooltip" title="Facebook" data-placement="bottom"> <i class="fa fa-facebook-f"></i> </a>
<a href="#" data-bs-toggle="tooltip" title="Instagram" data-placement="bottom"> <i class="fa fa-instagram"></i> </a>
<a href="#" data-bs-toggle="tooltip" title="Google" data-placement="bottom"> <i class="fa fa-google-plus"></i> </a>
</div>
</div>';
update_option('widget_text', array(
  1 => array('title' => '', 'text'=> $text ),     
  2 => array('title' => 'Categories'), 
  3 => array('title' => 'Latest News'), 
  4 => array('title' => 'Newsletter'), 
));

update_option('widget_categories', array(
   1 => array('title' => 'Categories'), 
   2 => array('title' => 'Categories'), 
));

update_option('widget_archives', array(
   1 => array('title' => 'Archives'), 
   2 => array('title' => 'Archives'),

));

update_option('widget_recent_posts', array(
   1 => array('title' => 'Latest News'), 
   2 => array('title' => 'Latest News'),
)); 


update_option('widget_tag_cloud', array(
   1 => array('title' => 'Tags'), 
   2 => array('title' => 'Tags'),
)); 


$widgets = array(    
  'industri-footer-widget' => array(
    'text-1',
    'categories-1',
     'tag_cloud-1',
    'widget_recent_posts',    
 ),
  'industri-primary-widget' => array(
    'search-1',        
    'archives-1',    
    'widget_recent_posts',
    'tag_cloud-1',
 )
);
update_option('sidebars_widgets',  $widgets);
