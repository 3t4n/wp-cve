<?php

/**
 * The admin-specific functionality of the plugin.
 * @link       https://tranzly.io
 * @since      1.0.0
 * @package    Tranzly
 * @subpackage Tranzly/admin
 */
class Tranzly_Admin
{
    /**
     * The ID of this plugin.
     */
    private  $plugin_name ;
    /**
     * The version of this plugin.
     */
    private  $version ;
    /**
     * Initialize the class and set its properties.
     */
    public function __construct( $plugin_name, $version )
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }
    
    /**
     * Register the stylesheets for the admin area.
     */
    public function enqueue_styles( $hook )
    {
        /**
         * This function is provided for demonstration purposes only.
         */
        wp_enqueue_style(
            $this->plugin_name,
            plugin_dir_url( __FILE__ ) . 'css/tranzly-admin.css',
            array(),
            $this->version,
            'all'
        );
        wp_enqueue_style(
            'tranzly-select2-css',
            plugin_dir_url( __FILE__ ) . '/vendors/select2/css/select2.min.css',
            array(),
            $this->version,
            'all'
        );
        if ( 'toplevel_page_tranzly' === $hook ) {
            wp_enqueue_script( 'tranzly-select2-css' );
        }
    }
    
    /**
     * Register the JavaScript for the admin area.
     */
    public function enqueue_scripts( $hook )
    {
        /**
         * This function is provided for demonstration purposes only.
         */
        wp_enqueue_script(
            $this->plugin_name,
            plugin_dir_url( __FILE__ ) . 'js/tranzly-admin.js',
            array( 'jquery' ),
            $this->version,
            true
        );
        wp_enqueue_script(
            'tranzly-select2-js',
            plugin_dir_url( __FILE__ ) . 'vendors/select2/js/select2.min.js',
            array( 'jquery' ),
            $this->version,
            true
        );
        wp_enqueue_script(
            'tranzly-params',
            plugin_dir_url( __FILE__ ),
            array( 'jquery' ),
            $this->version,
            true
        );
        wp_localize_script( 'tranzly-params', 'tranzlyParams', array(
            'ajaxurl'                      => admin_url( 'admin-ajax.php' ),
            'admin_post_url'               => admin_url( 'post.php' ),
            'translationSuccessMessage'    => esc_html__( 'Translation completed successfully!', 'tranzly' ),
            'total_translated_placeholder' => tranzly_get_placeholder_markup_for_total_translated_posts(),
        ) );
        if ( 'toplevel_page_tranzly' === $hook ) {
            wp_enqueue_script( 'tranzly-select2-js' );
        }
    }
    
    function tranzly_project_updated( $post_id )
    {
        $deepl_translated = get_post_meta( $post_id, 'deepl_translated', true );
        update_post_meta( $post_id, 'deepl_translated', 1 );
    }
    
    public function tranzly_register_custom_widget()
    {
        register_widget( 'Tranzly_Custom_Widget' );
    }

}
class Tranzly_Custom_Widget extends WP_Widget
{
    public function __construct()
    {
        if ( !isset( $options ) ) {
            $options = '';
        }
        parent::__construct( 'tranzly_language_switcher', 'Tranzly Language Switcher', $options );
    }
    
    public function form( $instance )
    {
        
        if ( isset( $instance['title'] ) ) {
            $title = $instance['title'];
        } else {
            $title = __( 'New title' );
        }
        
        ?>
    <p>
        <label for="<?php 
        echo  esc_attr($this->get_field_id( 'title' ))  ;
        ?>"><?php 
        esc_html_e( 'Title:', 'tranzly' );
        ?></label> 
        <input class="widefat" id="<?php 
        echo  esc_attr($this->get_field_id( 'title' )) ;
        ?>" name="<?php 
        echo  esc_attr($this->get_field_name( 'title' )) ;
        ?>" type="text" value="<?php 
        echo  esc_attr( $title ) ;
        ?>" />
        </p>
    <?php 
    }
    
    public function update( $new_instance, $old_instance )
    {
        $instance = array();
        $instance['title'] = ( !empty($new_instance['title']) ? strip_tags( $new_instance['title'] ) : '' );
        return $instance;
    }
    
    public function widget( $args, $instance )
    {
        global  $wp ;
        
        $ddllink     = empty( $ddllink ) ? sanitize_text_field('') : '';
        $ddllink2      = empty( $ddllink2 ) ? sanitize_text_field('') : '';
        $link      = empty( $link ) ? sanitize_text_field('') : '';
        $link2      = empty($link2) ? sanitize_text_field('') : '';
        $watermark2      = empty( $watermark2) ? sanitize_text_field('') : '';
        
      
        
        $instance['title'] = ( ! empty( $instance['title'] ) ) ? sanitize_text_field( strip_tags( $instance['title'] ) ) : '';
        
        echo  $args['before_widget'];
        echo  $args['before_title'] . apply_filters( 'widget_title', $title ) . $args['after_title'];
        
        if ( get_post_type_archive_link( 'post' ) == home_url( $wp->request ) . '/' ) {
            ?>
            <div class="tranzly_select">
                <div class="tranzly_select_ul">
                    <?php 
            
            if ( $cnlang == '' ) {
                ?>
                    <div tranzly_tag="selected" class="tranzly_select_li tranzly_active"><?php 
                esc_html_e( 'Please Select', 'tranzly' );
                ?></div>    
                    <?php 
            }
            
            ?>
                <?php 
            foreach ( tranzly_supported_languages() as $code => $name ) {
                ?>
                    <div class="tranzly_select_li <?php 
                if ( $cnlang == esc_attr( $code ) ) {
                    echo  'tranzly_active' ;
                }
                ?>" tranzly_tag="<?php 
                if ( $cnlang == esc_attr( $code ) ) {
                    echo  'selected' ;
                }
                ?>" tranzly_value="<?php 
                echo  esc_attr( $code ) ;
                ?>">
                        <img class="icon_img" src="<?php 
                echo  esc_url(TRANZLY_PLUGIN_URI) . 'assets/img/' . esc_html( $name ) ;
                ?>.png" style="margin: 2px 10px 0 0;float: left;"> <?php 
                echo  esc_html( $name ) ;
                ?>
                    </div>
                <?php 
            }
            ?>
                </div>
                <input type="hidden" name="tranzly_url" id="tranzly_url" class="tranzly_url" value="<?php 
            echo  esc_url(home_url( $wp->request )) ;
            ?>">
            </div>
            <input type="hidden" name="tranzly_page_id" id="tranzly_page_id" value="<?php 
            echo  get_the_ID() ;
            ?>">
            <?php 
        } else {
            $post_id = get_the_ID();
            $tranzly_post_translated_to = get_post_meta( $post_id, 'tranzly_post_translated_to', true );
            $tranzly_post_translated_to_from = get_post_meta( $post_id, 'tranzly_post_translated_to_from', true );
            if ( $tranzly_post_translated_to_from ) {
                
                if ( $tranzly_post_translated_to_from['0']['tranzly_parent_post_id'] ) {
                    $tranzly_parent_post_id = $tranzly_post_translated_to_from['0']['tranzly_parent_post_id'];
                    $tranzly_post_translated_to = get_post_meta( $tranzly_parent_post_id, 'tranzly_post_translated_to', true );
                }
            
            }
            if ( $tranzly_post_translated_to ) {
                foreach ( $tranzly_post_translated_to as $translated_to ) {
                    $tranzly_child_post_id = $translated_to['tranzly_child_post_id'];
                    foreach ( tranzly_supported_languages() as $code => $name ) {
                        
                        if ( $code == $translated_to['translated_to'] ) {
                            $tranzly_child_post = get_post( $tranzly_child_post_id );
                            
                            if ( $tranzly_child_post->post_status == 'publish' ) {
                                $tranzly_child_post->guid;
                                $cnname[] = $name;
                                
                                if ( $tranzly_child_post_id != $post_id ) {
                                    $ddllink .= '<option value="' . get_site_url() . '/' . $tranzly_child_post->post_name . '?lang=' . $code . '">' . esc_html( $name ) . '</option>';
                                    
                                    if ( $cnlang == esc_attr( $code ) ) {
                                        $link .= '<div class="tranzly_select_li_page tranzly_active" tranzly_tag="selected" tranzly_value="' . get_site_url() . '/' . $tranzly_child_post->post_name . '?lang=' . $code . '"><img class="icon_img" src="' . TRANZLY_PLUGIN_URI . 'assets/img/' . esc_html( $name ) . '.png" style="margin: 2px 10px 0 0;float: left;">' . esc_html( $name ) . '</div>';
                                    } else {
                                        $link .= '<div class="tranzly_select_li_page" tranzly_tag="" tranzly_value="' . get_site_url() . '/' . $tranzly_child_post->post_name . '?lang=' . $code . '"><img class="icon_img" src="' . TRANZLY_PLUGIN_URI . 'assets/img/' . esc_html( $name ) . '.png" style="margin: 2px 10px 0 0;float: left;">' . esc_html( $name ) . '</div>';
                                    }
                                
                                }
                            
                            }
                        
                        }
                        
                        ?>
                <?php 
                    }
                }
            }
            if ( $tranzly_post_translated_to_from ) {
                foreach ( $tranzly_post_translated_to_from as $translated_to_from ) {
                    
                    if ( $translated_to_from['translated_from'] ) {
                        $tranzly_parent_post_id = $translated_to_from['tranzly_parent_post_id'];
                        foreach ( tranzly_supported_languages() as $code => $name ) {
                            
                            if ( $code == $translated_to_from['translated_from'] ) {
                                $tranzly_parent_post = get_post( $tranzly_parent_post_id );
                                
                                if ( $tranzly_parent_post->post_status == 'publish' ) {
                                    
                                    if ( $cnlang == esc_attr( $code ) ) {
                                        $link2 .= '<div class="tranzly_select_li_page tranzly_active" tranzly_tag="selected" tranzly_value="' . get_site_url() . '/' . $tranzly_parent_post->post_name . '?lang=' . $code . '"><img class="icon_img" src="' . TRANZLY_PLUGIN_URI . 'assets/img/' . esc_html( $name ) . '.png" style="margin: 2px 10px 0 0;float: left;">' . esc_html( $name ) . '</div>';
                                    } else {
                                        $link2 .= '<div class="tranzly_select_li_page" tranzly_tag="" tranzly_value="' . get_site_url() . '/' . $tranzly_parent_post->post_name . '?lang=' . $code . '"><img class="icon_img" src="' . TRANZLY_PLUGIN_URI . 'assets/img/' . esc_html( $name ) . '.png" style="margin: 2px 10px 0 0;float: left;">' . esc_html( $name ) . '</div>';
                                    }
                                    
                                    $ddllink2 .= '<option value="' . get_site_url() . '/' . $tranzly_parent_post->post_name . '?lang=' . $code . '">' . esc_html( $name ) . '</option>';
                                }
                            
                            }
                        
                        }
                    }
                
                }
            }
            
            if ( $link != '' or $link2 != '' ) {
                $cnlink = $link . $link2;
                //$allddl=$ddllink+$ddllink2;
            }
            
            ?>
             <div class="tranzly_select">
                <div class="tranzly_select_ul">
                <?php 
            
            if ( $cnlang ) {
                foreach ( tranzly_supported_languages() as $code => $name ) {
                    
                    if ( $cnlang == $code ) {
                        ?>
                    <div class="cnopen">
                        <img class="icon_img" src="<?php 
                        echo  esc_url(TRANZLY_PLUGIN_URI). 'assets/img/' . esc_html( $name ) ;
                        ?>.png" style="margin: 2px 10px 0 0;float: left;"> <?php 
                        echo  esc_html( $name ) ;
                        ?>
                    </div>
                    <?php 
                    }
                    
                    ?>
                <?php 
                }
            } else {
                ?>
                    <div class="cnopen">
                        <?php 
                esc_html_e( 'Please Select', 'tranzly' );
                ?>
                    </div>
                <?php 
            }
            
            ?>
                <?php 
            echo $cnlink;
            ?>
                </div>
                <input type="hidden" name="tranzly_url" id="tranzly_url" class="tranzly_url" value="<?php 
            echo  esc_url(home_url( $wp->request )) ;
            ?>">
            </div>
            <input type="hidden" name="tranzly_page_id" id="tranzly_page_id" value="<?php 
            echo  esc_attr(get_the_ID()) ;
            ?>">
            <?php 
        }
        
        echo $args['after_widget'];
    }

}
// Register the widget