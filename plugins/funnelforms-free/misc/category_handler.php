<?php

function fnsf_get_all_categories_vanilla() {
    require_once FNSF_AF2_MISC_FUNCTIONS_PATH;

    $categories_id = intval(get_option('af2_categories'));
    $post = get_post($categories_id);
    
    return fnsf_af2_get_post_content($post);
}

function fnsf_get_all_categories() {
    $categories = fnsf_get_all_categories_vanilla();

    $all_cats = array();
    foreach($categories as $category){
        array_push($all_cats, array('value'=>$category['id'], 'label'=>$category['label']));
    }

    return $all_cats;
}

function fnsf_get_category_id_of_element($elementid) {
    $categories = fnsf_get_all_categories_vanilla();

    foreach($categories as $category) {
        if(in_array($elementid, $category['elements'])) return $category['id'];
    }

    return 'empty';
}