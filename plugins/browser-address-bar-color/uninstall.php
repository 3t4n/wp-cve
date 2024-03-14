<?php

if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ){
    die;
}
  
if(get_option('babc_pages_list')){
    // delete the stored color from DB
    delete_option('babc_pages_list');
}
