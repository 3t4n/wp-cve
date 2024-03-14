<?php

    function caropro_get_excerpt($excerpt_lenght){
        $excerpt = get_the_content();
        $excerpt = preg_replace(" ([.*?])",'',$excerpt);
        $excerpt = strip_shortcodes($excerpt);
        $excerpt = strip_tags($excerpt);
        $excerpt = substr($excerpt, 0, $excerpt_lenght);
        $excerpt = substr($excerpt, 0, strripos($excerpt, " "));
        $excerpt = trim(preg_replace( '/s+/', ' ', $excerpt));
        return $excerpt;
    }
