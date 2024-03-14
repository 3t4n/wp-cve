<?php
/**
 * Pricing table action button
 */

$this->add_render_attribute( 'button', array(
	'class' => array(
		'elementor-button',
		'elementor-size-md',
		'lakit-pricing-table-button',
		'button-' . $this->get_settings_for_display( 'button_size' ) . '-size',
	)
) );
$this->add_link_attributes('button', $this->get_settings_for_display('button_url'));

$icon_html = '';

$position       = $this->get_settings_for_display( 'button_icon_position' );
$allow_icon     = $this->get_settings_for_display( 'add_button_icon' );

if($allow_icon){
    if($position == 'left'){
	    $icon_html = $this->_get_icon('button_icon', '<span class="elementor-button-icon elementor-align-icon-left">%s</span>');
    }
    elseif ($position == 'right'){
	    $icon_html = $this->_get_icon('button_icon', '<span class="elementor-button-icon elementor-align-icon-right">%s</span>');
    }
}
$button_text = $this->_get_html( 'button_text', '<span class="elementor-button-text">%s</span>' );

if(empty($button_text) && empty($icon_html)){
    return;
}

?>
<a <?php echo $this->get_render_attribute_string( 'button' ); ?>><span class="elementor-button-content-wrapper"><?php
    if( $position == 'left' ){
	    echo $icon_html;
    }
    echo $button_text;
    if( $position == 'right' ){
        echo $icon_html;
    }
?></span></a>
