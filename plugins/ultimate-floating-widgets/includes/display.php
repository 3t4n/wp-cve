<?php
/*
    Displays the widget box in the front
*/

class UFW_Display{

    public static function init(){

        add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );

        add_action( 'wp_footer', array( __CLASS__, 'add_widget_box' ) );

    }

    public static function enqueue_scripts(){
        
        wp_enqueue_style( 'ufw-style', UFW_URL . 'public/css/style.css', array(), UFW_VERSION );
        wp_enqueue_style( 'ufw-anim', UFW_URL . 'public/css/animate.min.css' );
        wp_enqueue_script( 'ufw-script', UFW_URL . 'public/js/script.js', array( 'jquery' ), UFW_VERSION );
        
        wp_enqueue_style( 'fontawesome-css', 'https://use.fontawesome.com/releases/v5.15.1/css/all.css' );
        
    }

    public static function add_widget_box(){
        
        $widget_boxes = Ultimate_Floating_Widgets::list_all();
        
        foreach( $widget_boxes as $id => $opts ){
            
            $opts = apply_filters( 'ufw_mod_options', $opts, $id );
            $opts = wp_parse_args( $opts, Ultimate_Floating_Widgets::defaults() );
            
            if( $opts[ 'status' ] == 'disabled' ){
                continue;
            }
            
            if( !self::check_location_rules_basic( $opts[ 'loc_rules_basic' ] ) ){
                continue;
            }
            
            self::widget_box_html( $id, $opts );
            
        }
        
    }
    
    public static function widget_box_html( $id, $opts = array() ){
        
        extract( $opts, EXTR_SKIP );
        
        // Prepare wrap classes
        $wrap_classes = array();
        
        if( $type == 'popup' ){
            array_push( $wrap_classes, 'ufw_pp' );
            $position = $pp_position;
            $anim_open = $pp_anim_open;
            $anim_close = $pp_anim_close;
        }
        
        if( $type == 'flyout' ){
            array_push( $wrap_classes, 'ufw_fo' );
            $position = $fo_position;
            $anim_open = $fo_anim_open;
            $anim_close = $fo_anim_close;
        }
        
        array_push( $wrap_classes, 'ufw_p_' . $position );
        array_push( $wrap_classes, 'ufw_wb_closed' );
        array_push( $wrap_classes, 'ufw_wb_hidden' );

        if( $trigger == 'auto' ){
            array_push( $wrap_classes, 'ufw_no_btn' );
        }

        if( $wb_close_btn == 'yes' ){
            array_push( $wrap_classes, 'ufw_has_close_btn' );
        }

        // Prepare wrap attributes
        $wrap_attrs = array(
            'data-open-anim' => $anim_open,
            'data-close-anim' => $anim_close,
            'data-size' => $wb_width . '*' . $wb_height
        );
        
        if( $trigger != 'button' && $auto_trigger != '' ){
            $wrap_attrs[ 'data-auto-trigger' ] = $auto_trigger;
        }
        
        if( $auto_close != '' ){
            $wrap_attrs['data-auto-close'] = $auto_close;
        }

        if( $auto_close_time != '' ){
            $wrap_attrs['data-auto-close-time'] = $auto_close_time;
        }

        if( $trigger != 'auto' && $btn_reveal != '0' ){
            $wrap_attrs[ 'data-btn-reveal' ] = $btn_reveal;
        }

        if( $save_state == 'yes' && $save_state_duration != '' ){
            $wrap_attrs[ 'data-save' ] = $save_state_duration;
        }

        $wrap_attrs[ 'data-init-d' ] = $init_state;
        $wrap_attrs[ 'data-init-m' ] = $init_state_m;
        $wrap_attrs[ 'data-devices' ] = $opts[ 'loc_rules' ][ 'devices' ];

        $wrap_attrs = UFW_Helpers::attrs( $wrap_attrs );
        
        echo '<div id="ufw_' . esc_attr( $id ) . '" class="ufw_wrap ' . esc_attr( implode( ' ', $wrap_classes ) ) . '" ' . $wrap_attrs . '>';
        
        if( $type == 'popup' && strpos( $position, 't' ) !== false ){
            self::widget_box_btn( $opts );
        }
        
        // Prepare widget box classes
        $wb_classes = array();

        echo '<div class="ufw_wb ' . esc_attr( implode( ' ', $wb_classes ) ) . '">';

        if( $wb_close_btn == 'yes' ){
            echo '<a href="#" class="ufw_close_btn" title="Close"><i class="' . esc_attr( $wb_close_icon ) . '"></i></a>';
        }

        if( $title != '' ){
            echo '<h4 class="ufw_title">' . wp_kses_post( $title ) . '</h4>';
        }

        echo '<div class="ufw_wb_inner">';

        dynamic_sidebar( 'ufw_' . $id );

        echo '</div>'; // Widget box inner
        echo '</div>'; // Widget box
        
        if( $type == 'popup' && strpos( $position, 'b' ) !== false ){
            self::widget_box_btn( $opts );
        }else if( $type == 'flyout' ){
            self::widget_box_btn( $opts );
        }
        
        echo '</div>';
        
        self::widget_box_styles( $id, $opts );
        
    }
    
    public static function widget_box_btn( $opts = array() ){
        
        if( is_admin() ){
            return;
        }

        extract( $opts, EXTR_SKIP );
        
        $do_icon = ( $btn_type == 'icon' || $btn_type == 'icon_text' ) ? true : false;
        $do_text = ( $btn_type == 'text' || $btn_type == 'icon_text' ) ? true : false;
        
        $btn_classes = array();
        
        array_push( $btn_classes, 'ufw_btn' );
        array_push( $btn_classes, 'ufw_btn_' . $btn_size . 'px' );
        array_push( $btn_classes, 'ufw_btn_type_' . $btn_type );

        if( $do_text ){
            array_push( $btn_classes, 'ufw_btn_text' );
        }
        
        if( $type == 'flyout' ){
            array_push( $btn_classes, 'ufw_btn_p_' . $fo_btn_position );
        }

        echo '<div class="ufw_btn_wrap">';
        
        if( $trigger == 'button' || $trigger == 'button_auto' ){

            $btn_text = do_shortcode( $btn_text );
            $btn_close_text = do_shortcode( $btn_close_text );

            echo '<a href="#" class="' . esc_attr( implode( ' ', $btn_classes ) ) . '">';

            echo '<div class="ufw_btn_oinfo" title="' . esc_attr( strip_tags( $btn_text ) ) . '">';
                if( $do_icon ){
                    $btn_icon = trim( $btn_icon );
                    if( substr( $btn_icon, 0, 4 ) == 'http' ){
                        echo '<span class="ufw_b_image"><img src="' . esc_url( $btn_icon ) . '" alt="Icon" /></span>';
                    }else{
                        if( strpos( $btn_icon, ' ' ) === false ){
                            $btn_icon = 'fas ' . $btn_icon;
                        }
                        echo '<i class="' . esc_attr( $btn_icon ) . '"></i>';
                    }
                }
                if( $do_text ){
                    echo '<div class="ufw_b_text">' . wp_kses_post( $btn_text ) . '</div>';
                }
            echo '</div>';

            echo '<div class="ufw_btn_cinfo" title="' . esc_attr( strip_tags( $btn_close_text ) ) . '">';
                if( $do_icon ){
                    echo '<i class="' . esc_attr( $btn_close_icon ) . '"></i>';
                }
                if( $do_text ){
                    echo '<div class="ufw_b_text">' . wp_kses_post( $btn_close_text ) . '</div>';
                }
            echo '</div>';

            echo '</a>';
            
        }
        
        echo '</div>';
        
    }
    
    public static function widget_box_styles( $id, $opts = array() ){
        
        $opts = self::clean_css_values( $opts );
        extract( $opts, EXTR_SKIP );

        $btn_bg_color = str_replace( ';', '', $btn_bg_color );
        $is_transparent_btn = ( $btn_bg_color == 'transparent' );

        echo "<style>
#ufw_{$id} .ufw_wb{
    width: 100%;
    height: {$wb_height};
    background-color: {$wb_bg_color};
    border-width: {$wb_bdr_size}px;
    border-color: {$wb_bdr_color};
    border-radius: {$wb_bdr_radius}px;
    animation-duration: {$anim_duration}s;
    -webkit-animation-duration: {$anim_duration}s;
}
#ufw_{$id} .ufw_btn{
    background: {$btn_bg_color};
    border-color: {$btn_bdr_color};
    border-width: {$btn_bdr_size}px;
    color: {$btn_text_color};
    border-radius: {$btn_radius}px;
    " . ( $is_transparent_btn ? 'box-shadow: none' : '' ) . "
}
#ufw_{$id} .ufw_title, #ufw_{$id} .ufw_col{
    border-color: {$wb_bdr_color};
}
";

if( $wb_text_color != '' ){
    echo "
#ufw_{$id} .ufw_wb, #ufw_{$id} .ufw_wb a, #ufw_{$id} h1, #ufw_{$id} h2, #ufw_{$id} h3, #ufw_{$id} h4, #ufw_{$id} h5{
    color: {$wb_text_color} !important;
}
";
}

// Hide the button on page load when it is revealed only on scroll
if( $trigger != 'auto' && !empty( $btn_reveal ) ){
    echo "
#ufw_{$id}{
    animation: ufw_hide 0s 1s forwards;
    visibility: hidden;
}
";
}

echo "
{$additional_css}
</style>";
        
    }

    public static function check_location_rules_basic( $rules ){

        if( in_array( 'hide_all', $rules ) ){
            return false;
        }

        if( in_array( 'hide_home', $rules ) && is_home() ){
            return false;
        }

        if( in_array( 'hide_posts', $rules ) && is_single() ){
            return false;
        }

        if( in_array( 'hide_pages', $rules ) && is_page() && !is_front_page() ){
            return false;
        }

        if( in_array( 'hide_front_page', $rules ) && is_front_page() ){
            return false;
        }

        return true;

    }

    public static function clean_css_values( $options ){
        foreach( $options as $key => $val ){
            if( in_array( $key, array( 'additional_css' ) ) ){
                continue;
            }
            if( is_string( $val ) ){
                $options[ $key ] = str_replace( array( ':', ';', '{', '}', '@' ), '', $val );
            }
        }
        return $options;
    }

}

UFW_Display::init();

?>