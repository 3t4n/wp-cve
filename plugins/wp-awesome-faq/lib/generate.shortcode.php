<?php 

function jltmf_option_element( $name, $attr_option, $type, $shortcode ){
    
    $option_element = null;

    if( !isset($attr_option['value']) ) $attr_option['value']='';
    
    (isset($attr_option['desc']) && !empty($attr_option['desc'])) ? $desc = '<p class="description">'.$attr_option['desc'].'</p>' : $desc = '';

    switch( $attr_option['type'] ){
        
        case 'select':
        
        $option_element .= '
        <div class="label"><label for="'.$name.'"><strong>'.$attr_option['title'].': </strong></label></div>
        
        <div class="content"><select id="'.$name.'">';
        $values = $attr_option['values'];
        foreach( $values as $index=>$value ){
            $option_element .= '<option value="'.$index.'">'.$value.'</option>';
        }
        $option_element .= '</select>' . $desc . '</div>';
        
        break;  

  
        
        case 'textarea':
        $option_element .= '
        <div class="label"><label for="shortcode-option-'.$name.'"><strong>'.$attr_option['title'].': </strong></label></div>
        <div class="content"><textarea data-attrname="'.$name.'">'.$attr_option['value'].'</textarea> ' . $desc . '</div>';
        break;

        case 'color':
        $option_element .= '
        <div class="label"><label for="shortcode-option-'.$name.'"><strong>'.$attr_option['title'].': </strong></label></div>
        <div class="content"><input class="attr" type="color" data-attrname="'.$name.'" value="'.$attr_option['value'].'" />' . $desc . '</div>';
        break;

        case 'text':
        default:
        $option_element .= '
        <div class="label"><label for="shortcode-option-'.$name.'"><strong>'.$attr_option['title'].': </strong></label></div>
        <div class="content"><input class="attr" type="text" data-attrname="'.$name.'" value="'.$attr_option['value'].'" />' . $desc . '</div>';
        break;
    }
    
    $option_element .= '<div class="clear"></div>';

    return $option_element;
}

