<?php
// plugin activator

if(!defined('WPINC')){ die; }

if(!class_exists('THFAQF_Activator')):

class THFAQF_Activator{
    public static function activate(){
        register_post_type( 'faq', ['public' => 'true', 'publicly_queryable'  => false,] );
        flush_rewrite_rules();
    }
}

endif;


