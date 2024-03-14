<?php

if ( ! defined( 'ABSPATH' ) ){
    exit;
}

class WP_Optionizer{
    
    public $version = 1.0;
    
    public $url;

    function __construct(){
        
        $this->url = plugin_dir_url( __FILE__ );
        
    }
    
    function enqueue_resources(){
        
        wp_enqueue_style( 'wp-optionizer', $this->url . 'css/style.css', array(), $this->version );
        wp_enqueue_script( 'wp-optionizer', $this->url . 'js/script.js', array( 'jquery' ), $this->version );
        
    }
    
    function section( $options ){
        
        $opts = $this->set_defaults( $options, array(
            'heading' => '',
            'description' => '',
            'content' => '',
            'mini' => false,
            'class' => '',
            'attrs' => '',
            'closed' => false
        ));
        
        extract( $opts, EXTR_SKIP );
        
        if( $closed ){
            $class .= ' sec-closed ';
        }
        
        if( $mini ){
            echo '<section class="wp-optzr ' . esc_attr( $class ). '" ' . $attrs . '>';
            if( !empty( $heading ) ) echo '<h2 class="title">' . esc_html( $heading ) . '</h2>';
            if( !empty( $description ) ) echo '<p>' . wp_kses_post( $description ) . '</p>';
        }else{
            echo '<div class="wp-optzr postbox ' . esc_attr( $class ) . '" ' . $attrs . '>';
            if( !empty( $heading ) ) echo '<h3 class="hndle">' . esc_html( $heading ) . '</h3>';
            echo '<div class="inside">';
            if( ! empty( $description ) ) echo '<p class="description">' . wp_kses_post( $description ) . '</p>';
        }
        
        if( is_array( $content ) ){
            $this->table( $content, true );
        }else{
            echo $content;
        }
        
        if( $mini ){
            echo '</section>';
        }else{
            echo '</div>';
            echo '</div>';
        }
        
    }
    
    function table( $rows = array(), $print = false, $class = '' ){
        
        $html = '<table class="form-table ' . esc_attr( $class ) . '">';
        
        foreach( $rows as $row ){
            $html .= '<tr ' . ( isset( $row[2] ) ? $row[2] : '' ) . '>';
                $html .= '<th>' . ( isset( $row[0] ) ? $row[0] : '' ) . '</th>';
                $html .= '<td>' . ( isset( $row[1] ) ? $row[1] : '' ) . '</td>';
            $html .= '</tr>';
        }
        
        $html .= '</table>';
        
        if( $print ){
            echo $html;
        }else{
            return $html;
        }
        
    }
    
    function field( $field_type, $field_props = array() ){
        
        $fields = array( 'text', 'select', 'image_select', 'radio', 'textarea', 'range', 'checkboxes' );

        $default_props = array(
            'id' => '',
            'name' => '',
            'class' => '',
            'value' => '',
            'list' => array(),
            'type' => '',
            'required' => '',
            'placeholder' => '',
            'rows' => '',
            'cols' => '',
            'helper' => '',
            'tooltip' => '',
            'before_text' => '',
            'after_text' => '',
            'custom' => ''
        );

        if( !in_array( $field_type, $fields ) ){
            return '';
        }

        $props = $this->set_defaults( $field_props, $default_props );
        $props = $this->clean_attr( $props );
        $field_html = '';

        extract( $props, EXTR_SKIP );

        $id_attr = empty( $id ) ? '' : 'id="' . $id . '"';
        $class_attr = empty( $class ) ? '' : 'class="' . $class . '"';

        if( $field_type == 'text' ){
            $type = empty( $type ) ? 'text' : $type;
            $field_html .= "<input type='$type' $class_attr $id_attr name='$name' value='$value' placeholder='$placeholder'" . ( $required ? " required='$required'" : "" ) . " $custom />";
        }

        if( $field_type == 'select' ){
            $field_html .= "<select name='$name' $class_attr $id_attr $custom>";
            foreach( $list as $k => $v ){
                $disabled = strpos( $k, 'disabled' ) ? 'disabled' : '';
                $field_html .= "<option value='$k' " . selected( $value, $k, false ) . " $disabled>$v</option>";
            }
            $field_html .= "</select>";
        }

        if( $field_type == 'image_select' ){
            $field_html .= "<select name='$name' class='$class img_select' $id_attr $custom>";
            foreach( $list as $k => $v ){
                $opt_name = ( count( $v ) >= 2 ) ? $v[0] : $v;
                $field_html .= "<option value='$k' " . selected( $value, $k, false ) . ">$opt_name</option>";
            }
            $field_html .= "</select>";
            $field_html .= "<ul class='img_select_list clearfix'>";
            foreach( $list as $k => $v ){
                $is_selected = ( $value == $k ) ? 'img_opt_selected' : '';
                $img = 'default_image.png';
                $opt_name = '';
                if( count( $v ) >= 2 ){
                    $opt_name = $v[0];
                    $img = $v[1];
                }else{
                    $opt_name = $v;
                }
                $img = ( substr( $img, 0, 4 ) !== 'http' ) ? ( UFW_ADMIN_URL . 'images/select_images/' . $img ) : $img;
                $width = ( is_array( $v ) && isset( $v[2] ) ) ? "style='width:" . $v[2] . "'" : "";
                $field_html .= "<li data-value='$k' data-init='false' class='" . $is_selected . "' $width><img src='" . $img . "' /><span>" . $opt_name . "</span></li>";
            }
            $field_html .= "</ul>";
        }

        if( $field_type == 'radio' ){
            $field_html .= '<div class="radios_wrap">';
            foreach( $list as $k => $v ){
                $field_html .= "<label $custom><input type='radio' name='$name' $class_attr value='$k' $id_attr " . checked( $value, $k, false ) . " />&nbsp;$v </label>";
            }
            $field_html .= '</div>';
        }

        if( $field_type == 'checkboxes' ){
            $field_html .= '<div class="checkboxes_wrap">';
            $value = isset( $value ) ? $value : array();
            foreach( $list as $k => $v ){
                $checked = in_array( $k, $value ) ? ' checked="checked"' : '';
                $field_html .= "<label $custom><input type='checkbox' name='$name' $class_attr value='$k' $id_attr " . $checked . " />&nbsp;$v </label>";
            }
            $field_html .= "<input type='hidden' name='$name' value='' />";
            $field_html .= '</div>';
        }

        if( $field_type == 'range' ){
            $field_html .= "<span class='wp-optzr-range'>";
            $field_html .= "<input type='range' name='$name' $class_attr $id_attr min='$min' max='$max' step='$step' data-unit='$unit' value='$value'/>";
            $field_html .= "</span>";
        }

        if( $field_type == 'textarea' ){
            $field_html .= "<textarea $id_attr name='$name' $class_attr placeholder='$placeholder' rows='$rows' cols='$cols' $custom>$value</textarea>";
        }

        if( !empty( $unit ) && $field_type != 'range' ){
            $field_html .= "<span class='wp-optzr-unit'>$unit</span>";
        }
        
        if( !empty( $tooltip ) ){
            $field_html .= "<div class='wp-optzr-tt'><span class='dashicons dashicons-editor-help'></span><span class='wp-optzr-tt-text'>$tooltip</span></div>";
        }

        if( !empty( $helper ) )
            $field_html .= "<p class='description'>$helper</p>";

        return $field_html;
        
    }
    
    function set_defaults( $a, $b ){
        
        $a = (array) $a;
        $b = (array) $b;
        $result = $b;
        
        foreach ( $a as $k => &$v ) {
            if ( is_array( $v ) && isset( $result[ $k ] ) ) {
                $result[ $k ] = $this->set_defaults( $v, $result[ $k ] );
            } else {
                $result[ $k ] = $v;
            }
        }
        return $result;
    }

    function clean_attr( $a ){
        
        foreach( $a as $k=>$v ){
            if( is_array( $v ) ){
                $a[ $k ] = $this->clean_attr( $v );
            }else{
                
                if( in_array( $k, array( 'custom', 'tooltip', 'helper', 'before_text', 'after_text' ) ) ){
                    $a[ $k ] = wp_kses_post( $v );
                }else{
                    $a[ $k ] = esc_attr( $v );
                }
            }
        }
        
        return $a;
    }
    
}

?>