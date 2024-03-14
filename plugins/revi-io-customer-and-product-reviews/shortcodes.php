<?php
// GENERAL WIDGETS
add_shortcode("revi_widget_vertical", "revi_shortcode_widget_vertical");
add_shortcode("revi_widget_wide", "revi_shortcode_widget_wide");
add_shortcode("revi_widget_floating", "revi_shortcode_widget_floating");
add_shortcode("revi_widget_small", "revi_shortcode_widget_small");
add_shortcode("revi_widget_general", "revi_shortcode_widget_general");


// PRODUCT WIDGETS
add_shortcode("revi_product_right", "revi_load_widget_product_small"); //DEPRECATED
add_shortcode("revi_widget_product_small", "revi_shortcode_widget_product_small");
add_shortcode("revi_widget_product", "revi_shortcode_widget_product");

// PRODUCT LIST
add_shortcode("revi_widget_product_list", "revi_shortcode_product_list");


// GENERAL WIDGETS
function revi_shortcode_widget_vertical()
{
    $reviwidgets = new reviwidgets();
    return $reviwidgets->loadReviWidget("vertical", array());
}

function revi_shortcode_widget_wide()
{
    $reviwidgets = new reviwidgets();
    return $reviwidgets->loadReviWidget("wide", array());
}

function revi_shortcode_widget_floating()
{
    $reviwidgets = new reviwidgets();
    return $reviwidgets->loadReviWidget("floating", array());
}

function revi_shortcode_widget_small()
{
    $reviwidgets = new reviwidgets();
    return $reviwidgets->loadReviWidget("small", array());
}

function revi_shortcode_widget_general()
{
    $reviwidgets = new reviwidgets();
    return $reviwidgets->loadReviWidget("general", array());
}

function revi_shortcode_widget_product_small()
{
    global $post;
    $id_product = $post->ID;
    $reviwidgets = new reviwidgets();
    return $reviwidgets->loadReviWidget("product_small", array(), $id_product);
}

function revi_shortcode_widget_product()
{
    global $post;
    $id_product = $post->ID;

    $reviwidgets = new reviwidgets();
    return $reviwidgets->loadReviWidget("product", array(), $id_product);
}

function revi_shortcode_product_list()
{
    global $post;
    $id_product = $post->ID;

    $reviwidgets = new reviwidgets();
    return $reviwidgets->loadReviWidget("product_list", array(), $id_product);
}
