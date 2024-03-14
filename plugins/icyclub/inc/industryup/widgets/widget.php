<?php
$activate = array(
		'footer_widget_area' => array('text-1','search-1','archives-1',),
    );
    /* the default titles will appear */
        update_option('widget_text', array(
        1 => array('title' => 'Quick contact info',
        'text'=>'<p><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Lorem ipsum dolor sit amet, the administration of justice, I may hear, finally, be expanded on, say, a certain pro cu neglegentur. </font><font style="vertical-align: inherit;">Mazim.Unusual or something.</font></font></p>
		'),        
		2 => array('title' => 'Recent Posts'),
		3 => array('title' => 'Categories'), 
        ));
		update_option('widget_archives', array(
			1 => array('title' => 'Archives'), 
			2 => array('title' => 'Archives')));
			
		update_option('widget_search', array(
			1 => array('title' => 'Search'), 
			2 => array('title' => 'Search')));	
		
		update_option('sidebars_widgets',  $activate);
?>