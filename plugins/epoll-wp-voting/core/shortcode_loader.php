<?php 
/***************
 * Author: Rahul Negi
 * Team: InfoTheme
 * Date: 1-7-2022
 * Desc: Custom Post Type Single Page shortcode to show with their code , Loading as per requirement or request
 * Happy Coding.....
 **************/
if(!function_exists('it_epoll_load_theme_shortcode')){
  add_action('init','it_epoll_load_theme_shortcode');
  function it_epoll_load_theme_shortcode(){
    do_action('it_epoll_init_poll_shortcode');
  }
}
?>