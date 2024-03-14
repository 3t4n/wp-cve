<?php
/*
    Plugin helpers
*/

class UFW_Helpers{

    public static function sidebar_template(){
        
        global $wp_registered_sidebars;
        
        $vals = array(
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h2 class="widget-title">',
            'after_title' => '</h2>'
        );
        
        foreach( $wp_registered_sidebars as $id => $sprops ){
            if( strpos( $id, 'ufw_' ) === false ){
                
                if( array_key_exists( 'before_widget', $sprops ) ){
                    $vals[ 'before_widget' ] = $sprops[ 'before_widget' ];
                }
                
                if( array_key_exists( 'after_widget', $sprops ) ){
                    $vals[ 'after_widget' ] = $sprops[ 'after_widget' ];
                }
                
                if( array_key_exists( 'before_title', $sprops ) ){
                    $vals[ 'before_title' ] = $sprops[ 'before_title' ];
                }
                
                if( array_key_exists( 'after_title', $sprops ) ){
                    $vals[ 'after_title' ] = $sprops[ 'after_title' ];
                }
                
                break;
                
            }
        }
        
        return $vals;
        
    }
    
    public static function attrs( $attrs = array() ){
        
        $attrs_txt = '';
        
        foreach( $attrs as $prop => $val ){
            $attrs_txt .= ' ' . $prop . '="' . esc_attr( trim( $val ) ) . '"';
        }
        
        return $attrs_txt;
        
    }

}

?>