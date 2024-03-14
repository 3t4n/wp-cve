<?php

if(!function_exists('cg_add_blog_option')){
    function cg_add_blog_option ($i,$optionName,$value) {

        if(is_multisite()){
            add_blog_option($i,$optionName,$value);
        }else{
            add_option($optionName,$value);
        }

    }
}
