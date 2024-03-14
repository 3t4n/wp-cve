<?php


$text = '<p> Lorem Ipsum is simply dummy text of the printing. Lorem Ipsum has been unknown.</p>

<ul class="nav social-media social-bg">
<li class="nav-item">
<a href="#" class="nav-link" data-bs-toggle="tooltip" data-placement="top" title="facebook"><i class="fab fa-facebook-f"></i></a>
</li>
<li class="nav-item">
<a href="#" class="nav-link" data-bs-toggle="tooltip" data-placement="top" title="Twitter"><i class="fab fa-twitter"></i></a>
</li>
<li class="nav-item">
<a href="#" class="nav-link" data-bs-toggle="tooltip" data-placement="top" title="Pinterest"> <i class="fab fa-pinterest-p"></i></a>
</li>
<li class="nav-item">
<a href="#" class="nav-link" data-bs-toggle="tooltip" data-placement="top" title="Linkedin"> <i class="fab fa-linkedin-in"></i></a>
</li>
</ul>
';
update_option('widget_text', array(
    1 => array('title' => 'Aqwa', 'text'=> $text ),     
    2 => array('title' => 'Categories'), 
));

update_option('widget_categories', array(
 1 => array('title' => 'Categories'), 
 2 => array('title' => 'Categories')
));

update_option('widget_archives', array(
 1 => array('title' => 'Archives'), 
 2 => array('title' => 'Archives')
));

update_option('widget_search', array(
 1 => array('title' => 'Search'), 
 2 => array('title' => 'Search')
));	

update_option('widget_recent_posts', array(
 1 => array('title' => 'Latest News'), 
 2 => array('title' => 'Latest News')
)); 


$widgets = array(    
    'footer-1' => array(
        'text-1',
        'categories-1',
        'archives-1',
        'search-1',
    ),
    'sidebar-1' => array(
        'search-1',        
        'archives-1',
    )
);
update_option('sidebars_widgets',  $widgets);
