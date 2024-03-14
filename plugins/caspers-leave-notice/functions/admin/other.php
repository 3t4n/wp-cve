<?php
function cpln_other_settings_desc() {
    echo '';
}

function cpln_redirect_timer_bool_output($args) {
	$options = get_option('cpln_other_settings');
	$label = 'When this is left unchecked, your visitors will be prompted to acknowledge they are leaving your site.';
	
	$html = '<input type="checkbox" id="cpln_redirect_timer_bool" name="cpln_other_settings[cpln_redirect_timer_bool]" value="1"';
	if(isset($options['cpln_redirect_timer_bool'])){
		$html .= 'checked="checked"';
		$label = 'When this is checked, your visitors will automatically redirect to their intended destination.';
	}
	$html .= '/>';
	$html .= '<label for="cpln_redirect_timer_bool">';
	$html .= $label;
	$html .= '</label>';
	echo $html;
}

function cpln_redirect_time_output($args) {
	$options = get_option('cpln_other_settings');
	$value = isset($options['cpln_redirect_time']) ? $options['cpln_redirect_time'] : 3;
	$html = '<input type="number" id="cpln_redirect_time"';
	$html .= 'name="cpln_other_settings[cpln_redirect_time]"';
	$html .= 'style="max-width: 4rem; text-align: right;"';
	$html .= 'min="1" max="10"';
	$html .= 'value="'.$value.'"';
	$html .= '/>';
	$html .= '<label for="cpln_redirect_time"> seconds</label>';
	echo $html;
}