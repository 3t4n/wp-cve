<?php

if (!defined('WPINC')) {
    exit;
}

if (function_exists('wc_get_filename_from_url')) {
    $file_path_header = 'downloadable_files';
} else {
    $file_path_header = 'file_paths';
}


$post_columns = array(
                
            'id'                           => 'Product Id[id]',
            'title'                        => 'Product Title[title]',
            'description'                  => 'Product Description[description]',
            'link'                         => 'Product URL[link]',
            'mobile_link'                  => 'Product URL[mobile_link]',
            'product_type'                 => 'Product Categories[product_type] ',
            'image_link'                   => 'Main Image[image_link]',
            'additional_image_link'        => 'Additional Images [additional_image_link]',
            'condition'                    => 'Condition[condition]',
);

return apply_filters('wt_pf_product_post_columns',$post_columns);