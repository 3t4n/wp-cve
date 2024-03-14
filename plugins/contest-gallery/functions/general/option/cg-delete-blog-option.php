<?php

if(!function_exists('cg_delete_blog_option')){
    function cg_delete_blog_option ($i,$optionName) {

        if(is_multisite()){
            delete_blog_option($i,$optionName);
        }else{
            delete_option($optionName);
        }

    }
}
