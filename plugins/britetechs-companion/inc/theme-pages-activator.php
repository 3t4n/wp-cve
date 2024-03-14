<?php 
class BC_Theme_Pages_Activator {

    public static function activate() {

        $home_and_blog_activate = get_option( 'home_and_blog_activate' );
        
        if ( (bool) $home_and_blog_activate === false ) {

                    $pages = array( 
                        esc_html__( 'Home', 'britetechs-companion' ), 
                        esc_html__( 'Blog', 'britetechs-companion' ) 
                    );

                    foreach ($pages as $page){ 

                        $post_data = array( 
                            'post_author' => 1, 
                            'post_name' => $page,  
                            'post_status' => 'publish' , 
                            'post_title' => $page, 
                            'post_type' => 'page', 
                        );  
                        
                        if($page== 'Home'): 
                            $page_option = 'page_on_front';

                            $themedata = wp_get_theme();
                            $mytheme = $themedata->name;
                            $mytheme = strtolower( $mytheme );
                            $mytheme = str_replace( ' ','-', $mytheme );
                            if(
                                $mytheme=='spawp' || 
                                $mytheme=='bizcor'
                            ){
                                $template = 'templates/template-homepage.php';
                            }else{
                                $template = 'template-homepage.php';
                            }
                        else:   
                            $page_option = 'page_for_posts';
                            $template = 'page.php';
                        endif;

                        $post_data = wp_insert_post( $post_data, false );

                            if ( $post_data ){
                                update_post_meta( $post_data, '_wp_page_template', $template );
                                $page = get_page_by_title($page);
                                update_option( 'show_on_front', 'page' );
                                update_option( $page_option, $page->ID );
                            }
                    }
                    
            update_option( 'home_and_blog_activate', true );                   
        }   
    }
}