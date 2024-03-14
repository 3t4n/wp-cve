<?php

if(!function_exists('cg_get_blog_option')){
    function cg_get_blog_option ($i,$optionName) {

        if(is_multisite()){
            return get_blog_option($i,$optionName);
        }else{
            return get_option($optionName);
        }

    }
}
