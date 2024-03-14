<?php
    // Wpfilm options menu
    if ( ! function_exists( 'wpfilm_add_adminbar_menu' ) ) {
        function wpfilm_add_adminbar_menu() {
            $menu = 'add_menu_' . 'page';
            $menu( 
                'wpfilm_panel', 
                esc_html__( 'WpFilm', 'wpfilm-studio' ), 
                'read', 
                'wpfilm', 
                NULL, 
                'dashicons-format-video', 
                40 
            );
        }
    }
    add_action( 'admin_menu', 'wpfilm_add_adminbar_menu' );

if(!function_exists('wpfilm_movie_pagination')){
    function wpfilm_movie_pagination(){
        ?>
        <div class="movie-pagination"> <?php
            the_posts_pagination(array(
                'prev_text'          => '<i class="fa fa-angle-left"></i>',
                'next_text'          => '<i class="fa fa-angle-right"></i>',
                'type'               => 'list'
            )); ?>
        </div>
        <?php
    }
}

if( !function_exists('wpfilm_post_count_on_archive') ){

    function wpfilm_post_count_on_archive( $query ) {
        if(!is_admin() && is_archive()){
            $per_page = (int)wpfilm_get_option( 'wpfilm_posts_per_page', 'settings' );
            if ( $query->is_archive( 'wpfilm_movie' ) ) {
                $query->set( 'posts_per_page', $per_page); /*set this your preferred count*/
            }
        }
    }
    add_action( 'pre_get_posts', 'wpfilm_post_count_on_archive' );
}



?>