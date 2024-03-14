const wp_bottom_menu_class = document.querySelector('.wp-bottom-menu');
const wp_bottom_menu_sfw =  document.querySelector('.wp-bottom-menu-search-form-wrapper');
const wp_bottom_menu_sft = document.querySelector(".wp-bottom-menu-search-form-trigger");
const lqd_sticky = document.querySelector('.lqd-sticky-atc');
const lqd_sticky_toggle = document.querySelector('.lqd-sticky-atc-mobile-toggle');
var wpbmsf = false;

if ( wp_bottom_menu_sft !== null ){
    wp_bottom_menu_sft.addEventListener("click" ,function(){
        wp_bottom_menu_sfw.classList.toggle("sf-active");
        wp_bottom_menu_class.classList.toggle("sf-active");
        if (!wpbmsf){
            wp_bottom_menu_sfw.style.bottom = wp_bottom_menu_class.clientHeight + "px";
            lqd_sticky_style(false);
            wpbmsf = true;
        } else {
            wp_bottom_menu_sfw.style.bottom = "0px";
            lqd_sticky_style(true);
            wpbmsf = false;
        }
    });
}

window.addEventListener('DOMContentLoaded', (event) => {
    document.body.style.paddingBottom = wp_bottom_menu_class.clientHeight + "px";
    lqd_sticky_style(true);
});

// Nav trigger
const wp_bottom_menu_nav_wrapper = document.querySelector(".wp-bottom-menu-nav-wrapper");
const wp_bottom_menu_nav_trigger = document.querySelector(".wp-bottom-menu-nav-trigger");
const wp_bottom_menu_nav_close = document.querySelector(".wpbm-nav-close");
var wpbm_nav = false;

if ( wp_bottom_menu_nav_trigger !== null ){
    wp_bottom_menu_nav_trigger.addEventListener("click" ,function(){
        wp_bottom_menu_nav_wrapper.classList.toggle("active");
    });
}

if ( wp_bottom_menu_nav_close !== null ){
    wp_bottom_menu_nav_close.addEventListener("click" ,function(){
        wp_bottom_menu_nav_wrapper.classList.toggle("active");
    });
}

// Page Back
const wp_bottom_menu_page_back = document.querySelector(".wpbm-page-back");
if ( wp_bottom_menu_page_back !== null ){
    wp_bottom_menu_page_back.addEventListener('click', function handleClick(event) {
        if ( history.length > 2 ) {
            history.back();
        } else {
            window.location.href = WPBM.siteurl;
        }
    });
}

function lqd_sticky_style( status ) {

    if ( lqd_sticky !== null ) {
        if ( status === true ) {
            lqd_sticky.style.setProperty( 'bottom', (wp_bottom_menu_class.clientHeight + lqd_sticky_toggle.clientHeight) + "px", 'important' ); 
            lqd_sticky_toggle.style.bottom = (wp_bottom_menu_class.clientHeight + 20) + "px";
        } else {
            lqd_sticky.style.bottom = "0px";
            lqd_sticky_toggle.style.bottom = "0px";
        }
    }

}