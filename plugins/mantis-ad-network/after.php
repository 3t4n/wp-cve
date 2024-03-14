<?php

function mantis_after_html()
{
    ob_start();

    dynamic_sidebar('mantis_after_content');

    $html = ob_get_contents();

    ob_end_clean();

    return $html;
}

function mantis_after_content($content)
{
    return $content . mantis_after_html();
}

function mantis_after_render()
{
    echo mantis_after_html();
}

function mantis_after_init()
{
    $location = get_option('mantis_after');

    if($location){
        register_sidebar(array(
            'name' => 'MANTIS After Content',
            'id' => 'mantis_after_content',
            'before_widget' => '',
            'after_widget' => '',
            'before_title' => '',
            'after_title' => ''
        ));

        if($location == 'after_content'){
            add_filter('the_content', 'mantis_after_content', 1);
        }
    }
}

add_action('init', 'mantis_after_init');