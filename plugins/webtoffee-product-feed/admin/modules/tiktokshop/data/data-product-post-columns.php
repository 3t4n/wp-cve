<?php

if (!defined('WPINC')) {
    exit;
}


$post_columns = array(
                
            'google_product_category'      => 'Category',
            'brand'                        => 'Brand', 
            'title'                        => 'Product Name',
            'description'                  => 'Product Description',
            'availability'                 => 'Product Status',     
            'image_link'                   => 'Main Product Image',
            'wtimages_1'                   => 'Product Image 2',
            'wtimages_2'                   => 'Product Image 3',
            'wtimages_3'                   => 'Product Image 4',
            'wtimages_4'                   => 'Product Image 5',
            'wtimages_5'                   => 'Product Image 6',
            'wtimages_6'                   => 'Product Image 7',
            'wtimages_7'                   => 'Product Image 8',
            'wtimages_8'                   => 'Product Image 9',            
);

return apply_filters('wt_pf_product_post_columns',$post_columns);


