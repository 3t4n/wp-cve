<?php
if (!defined('ABSPATH')) {
    die('Do not open this file directly.');
}

//iterative function that gets the children for a given category.
//It will add the category to the array and return it
//It will call itself...
function jemxp_get_category_children($catArray, $category, $parentName)
{

    //first add the category to the array...

    $tmpArray = array();
    $tmpArray['title'] = $parentName . $category->name;
    $tmpArray['id'] = $category->term_id;

    $catArray[] = $tmpArray;

    //add the extra -> on the name
    //$category->name .= '->';

    $args2 = array(
        'taxonomy'     => 'product_cat',
        'child_of'     => 0,
        'parent'       => $category->term_id,
        'orderby'      => 'name',
        'show_count'   => 0,
        'pad_counts'   => 0,
        'hierarchical' => 1,
        'title_li'     => '',
        'hide_empty'   => 0
    );

    //get the sub categories for this category
    $sub_cats = get_categories($args2);

    if ($sub_cats) {
        foreach ($sub_cats as $sub_category) {
            //now iterative call for each sub-cat

            $newParent = $parentName . $category->name . '-->';
            $catArray = jemxp_get_category_children($catArray, $sub_category, $newParent);
        }
    }

    return $catArray;
}

/**
 * This loads all the products. We only load the first 500 - we will load them later if needed
 * @return array - all products
 */
function jemexp_get_all_products()
{

    $partial = false;
    $pdcts = array();
    $page = 1;

    $args = array(
        'post_type'     => array('product', 'product_variation'),
        'orderby'      => 'name',
        'posts_per_page' => 500,
        'paged'            => $page
    );

    $pdct_query = new WP_query($args);

    //are there more products than we retrieved? If so set the partial flag
    if ($pdct_query->found_posts > $args['posts_per_page']) {
        $partial = true;
    }

    $pdcts = $pdct_query->posts;

    return array($pdcts, $partial);
}
