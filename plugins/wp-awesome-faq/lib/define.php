<?php
#-----------------------------------------------------------------
# Columns
#-----------------------------------------------------------------

$jw_faq_shortcode = array();


$cats = array(__('All Categories', MAF_TD));
foreach(get_terms('faq_cat', 'orderby=count&hide_empty=0&post_type=faq') as $term ){
    $cats[$term->slug] = $term->name;
}

$tags = array(__('All Tags', MAF_TD));
foreach(get_terms('faq_tags', 'orderby=count&hide_empty=0') as $term ){
    $tags[$term->slug] = $term->name;
}

// Custom FAQ
$jw_faq_shortcode['faq'] = array( 
    'type'=>'radios', 
    'title'=>__('FAQ Shortcode', MAF_TD),
    'attr'=>array(

        'cat'=>array(
            'type'=>'select', 
            'title'=> __('Category', MAF_TD), 
            'values'=> $cats
        ),        
        'tag'=>array(
            'type'=>'select', 
            'title'=> __('Tags', MAF_TD), 
            'values'=> $tags
        ),
        'items'=>array(
            'type'=>'text', 
            'title'=> __('Number Of Posts', MAF_TD), 
            'value'=> '-1'
        ),        
        'order'=>array(
            'type'=>'select', 
            'title'=> __('Order', MAF_TD), 
            'values'=>array(
                'DESC'=>'Descending',
                'ASC'=>'Ascending',
                )
            )
        )
);