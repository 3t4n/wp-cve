<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

// Credit Matthias Kittsteiner
// Source https://epiph.yt/blog/2018/einfache-javascript-tabs-im-wordpress-backend/
$wadaJavascript_Tabs_onload = "
    document.addEventListener( 'DOMContentLoaded', function() {
        var tabs = document.querySelectorAll( '.nav-tab' );
        var tab_contents = document.querySelectorAll( '.nav-tab-content' );
    
        for ( var _i = 0; _i < tabs.length; _i++ ) {
            tabs[ _i ].addEventListener( 'click', function( event ) {
                event.preventDefault();
    
                var current_target = event.currentTarget;
                var slug = current_target.getAttribute( 'data-slug' );
                                
                if(typeof beforeTabChange === 'function'){ // call anyone interested
                    beforeTabChange();
                }
    
                // remove active class from any other nav tab
                for ( var _n = 0; _n < tabs.length; _n++ ) {
                    tabs[ _n ].classList.remove( 'nav-tab-active' );
                }
    
                // add active class to the nav tab
                current_target.classList.add( 'nav-tab-active' );
    
                // remove active class from any other nav tab content
                for ( var _a = 0; _a < tab_contents.length; _a++ ) {
                    tab_contents[ _a ].classList.remove( 'nav-tab-content-active' );
                }
    
                // add active class to the content of the tab
                document.getElementById( 'nav-tab-content-' + slug ).classList.add( 'nav-tab-content-active' );
                // set url in browser
                history.pushState( null, null, current_target.href );                               
                                
                if(typeof afterTabChange === 'function'){ // call anyone interested
                    afterTabChange();
                }
                
            } );
        }
    } );
";

wp_register_script('wada_tabs_onload', '');
wp_enqueue_script('wada_tabs_onload');
wp_add_inline_script('wada_tabs_onload', $wadaJavascript_Tabs_onload);