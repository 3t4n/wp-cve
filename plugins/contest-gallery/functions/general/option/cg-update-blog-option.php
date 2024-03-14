<?php

if(!function_exists('cg_update_blog_option')){
    function cg_update_blog_option ($i,$optionName,$value) {

        if(is_multisite()){
            update_blog_option($i,$optionName,$value);
        }else{
            update_option($optionName,$value);
        }

    }
}
