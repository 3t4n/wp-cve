<?php
/* wppa-setting-functions.php
* Package: wp-photo-album-plus
*
* manage all options
* Version 8.6.02.003
*
*/

function wppa_input( $xslug, $width = '300px', $minwidth = '', $text = '', $onchange = '', $placeholder = '' ) {
global $wppa_opt;

	$slug 	= substr( $xslug, 5 );
	$tit 	= __( 'Slug =', 'wp-photo-album-plus' ) . ' ' . $xslug;
	$title 	= wppa_switch( 'enable_shortcode_wppa_set' ) ? esc_attr( $tit ) : '';
	$val 	= wppa_get_option( $xslug );
	$html 	= '
		<input
			id="' . $slug . '"
			title="' . $title . '"
			style="float:left;width:' . $width . ';height:20px;padding:0 0 0 5px;min-width:' . $minwidth . ';font-size: 11px;margin: 0px"
			type="text"
			onchange="' .
				esc_attr( $onchange ) . ';
				wppaAjaxUpdateOptionValue(\'' . $slug . '\', this);"
			placeholder="' . $placeholder . '"
			value="' . esc_attr( $val ) . '"
		/>
		<img
			id="img_' . $slug . '"
			src="' . wppa_get_imgdir() . 'star.ico"
			title="' . __( 'Setting unmodified', 'wp-photo-album-plus' ) . '"
			style="padding:0 4px;float:left;height:16px;width:16px;"
		/>
		<span
			style="float:left">' .
			$text . '
		</span>';

	$html .= wppa_setting_star( $onchange );

	return $html;
}

function wppa_number($xslug, $min, $max, $text = '', $onchange = '') {
	global $wppa_opt;

	$slug = substr( $xslug, 5 );
	$tit = __('Slug =', 'wp-photo-album-plus' ).' '.$xslug;
	$title = wppa_switch( 'enable_shortcode_wppa_set' ) ? ' title="'.esc_attr( $tit ).'"' : '';
	$val = isset ( $wppa_opt[ $xslug ] ) ? esc_attr( $wppa_opt[ $xslug ] ) : wppa_get_option( $xslug, '' );
	$html = '<input'.$title.' style="float:left;height:20px;width:50px;padding:0 0 0 5px;';
	$html .= 'font-size:11px;margin:0px;" type="number" id="'.$slug.'"';
	if ($onchange != '') $html .= ' onchange="'. esc_attr( $onchange.';wppaAjaxUpdateOptionValue(\''.$slug.'\', this);').'"';
	else $html .= ' onchange="wppaAjaxUpdateOptionValue(\''.$slug.'\', this)"';
	$html .= ' value="'.$val.'" min="'.$min.'" max="'.$max.'" />';
	$html .= '<img id="img_'.$slug.'" src="'.wppa_get_imgdir().'star.ico" title="'.__('Setting unmodified', 'wp-photo-album-plus' ).'" style="padding:0 4px; float:left; height:16px; width:16px;" />';
	$html .= '<span style="float:left">'.$text.'</span>';

	$html .= wppa_setting_star( $onchange );

	return $html;
}

function wppa_input_color($xslug, $width, $minwidth = '', $text = '', $onchange = '', $placeholder = '') {
global $wppa_opt;

	$slug = substr( $xslug, 5 );
	$tit = __('Slug =', 'wp-photo-album-plus' ).' '.$xslug;
	$title = wppa_switch( 'enable_shortcode_wppa_set' ) ? ' title="'.esc_attr( $tit ).'"' : '';
	$val = isset ( $wppa_opt[ $xslug ] ) ? esc_attr( $wppa_opt[ $xslug ] ) : wppa_get_option( $xslug, '' );
	$html = '<input'.$title.' type="color" style="float:left;width:'.$width.';height:20px;padding:0;';
	if ($minwidth != '') $html .= 'min-width:'.$minwidth.';';
	$html .= 'font-size:11px;margin:0px" type="text" id="'.$slug.'"';
	if ($onchange != '') $html .= ' onchange="'.esc_attr($onchange.';wppaAjaxUpdateOptionValue(\''.$slug.'\', this);').'"';
	else $html .= ' onchange="wppaAjaxUpdateOptionValue(\''.$slug.'\', this)"';
	if ( $placeholder ) $html .= ' placeholder="'.$placeholder.'"';
	$html .= ' value="'.$val.'" />';
	$html .= '<img id="img_'.$slug.'" src="'.wppa_get_imgdir().'star.ico" title="'.__('Setting unmodified', 'wp-photo-album-plus' ).'" style="padding:0 4px;float:left;height:16px;width:16px" />';
	$html .= '<span style="float:left">'.$text.'</span>';

	$html .= wppa_setting_star( $onchange );

	return $html;
}

function wppa_edit( $xslug, $value, $width = '90%', $minwidth = '', $text = '', $onch = '' ) {

	// Slug
	$slug 	= substr( $xslug, 5 );

	// Title
	$tit 	= __( 'Slug =', 'wp-photo-album-plus' ) . ' ' . $xslug;
	$title 	= wppa_switch( 'enable_shortcode_wppa_set' ) ? esc_attr( $tit ) : '';

	// Style
	$style 	= 'float:left;width:' . $width . ';height:20px;';
	$style .= 'font-size:11px;margin:0;padding:0 0 0 5px';
	if ( $minwidth != '' ) {
		$style .= ';min-width:' . $minwidth;
	}

	// Onchange
	$onchange = ltrim( $onch . ';wppaAjaxUpdateOptionValue(\'' . $slug . '\',this)', ';' );

	// Compose the html
	$html = '
	<input
		id="' . esc_attr( $slug ) . '"
		title="' . esc_attr( $title ) . '"
		type="text"
		style="' . esc_attr( $style ) . '"
		value="' . esc_attr( $value ) . '"
		onchange="' . esc_attr( $onchange ) . '"
	/>
	<img
		id="' . esc_attr( 'img_' . str_replace( '#', 'H', $slug ) ) . '"
		src="' . esc_url( wppa_get_imgdir() . 'star.ico' ) . '"
		title="' . esc_attr( __( 'Setting unmodified', 'wp-photo-album-plus' ) ) . '"
		style="padding:0 4px;float:left;height:16px;width:16px"
	/>' .
	$text;

	$html .= wppa_setting_star( $onchange );

	return $html;
}

function wppa_textarea($xslug, $buttonlabel = '', $repair_php = false ) {

	$slug = substr( $xslug, 5 );

	$tit = __('Slug =', 'wp-photo-album-plus' ).' '.$xslug;
	$title = wppa_switch( 'enable_shortcode_wppa_set' ) ? ' title="'.esc_attr( $tit ).'"' : '';

	$html = '<textarea id="'.esc_attr($slug).'"'.$title.' style="float:left;width:300px" onchange="wppaAjaxUpdateOptionValue(\''.$slug.'\', this)" >';
	if ( $repair_php ) {
		$html .= str_replace( ['&lt;?php', '&lt;?PHP', '<?php'], '', wppa_opt( $slug ) ) ;//esc_textarea( stripslashes( wppa_opt( $slug ) ) ) );
	}
	else {
		$html .= wppa_opt( $slug ); // esc_textarea( stripslashes( wppa_opt( $slug )));
	}
	$html .= '</textarea>';

	$html .= '<img id="img_'.$slug.'" src="'.wppa_get_imgdir().'star.ico" title="'.__('Setting unmodified', 'wp-photo-album-plus' ).'" style="padding:0 4px;float:left;height:16px;width:16px" />';

	return $html;
}

function wppa_checkbox($xslug, $onchange = '', $class = '') {
global $wppa_defaults;
global $wppa_opt;
global $wppa_hide_this;

	$slug = substr( $xslug, 5 );
	$html = '<div id="' . $xslug . '" ' . ( $wppa_hide_this ? 'style="display:none;"' : '' ) . ' class="'.$slug.'">';
	$slug = substr( $xslug, 5 );
	$tit = __('Slug =', 'wp-photo-album-plus' ).' '.$xslug."\n".__('Values = yes, no', 'wp-photo-album-plus' );
	$title = wppa_switch( 'enable_shortcode_wppa_set' ) ? ' title="'.esc_attr( $tit ).'"' : '';
	$html .= '<input style="float:left;height:15px;margin:0px;padding:0px" type="checkbox" id="'.$slug.'"'.$title;
	if ( wppa_switch( $slug ) ) $html .= ' checked';
	if ($onchange != '') $html .= ' onchange="'.esc_attr($onchange.';wppaAjaxUpdateOptionCheckBox(\''.$slug.'\', this);').'"';
	else $html .= ' onchange="wppaAjaxUpdateOptionCheckBox(\''.$slug.'\', this)"';

	if ($class != '') $html .= ' class="'.$class.'"';
	$html .= ' /><img id="img_'.$slug.'" src="'.wppa_get_imgdir().'star.ico" title="'.__('Setting unmodified', 'wp-photo-album-plus' ).'" style="padding-left:4px;float:left;height:16px;width:16px"';
	if ($class != '') $html .= ' class="'.$class.'"';
	$html .= ' />';

	$html .= wppa_setting_star( $onchange );
	$html .= '</div>';

	return $html;
}

function wppa_checkbox_e($xslug, $curval, $onchange = '', $class = '', $enabled = true) {

	$slug = substr( $xslug, 5 );
	$html = '<input style="float:left;height:15px;margin:0px;padding:0px" type="checkbox" id="'.$slug.'"';
	if ($curval) $html .= ' checked';
	if ( ! $enabled ) $html .= ' disabled';
	if ($onchange != '') $html .= ' onchange="'.esc_attr( $onchange.';wppaAjaxUpdateOptionCheckBox(\''.$xslug.'\', this);').'"';
	else $html .= ' onchange="wppaAjaxUpdateOptionCheckBox(\''.$xslug.'\', this)"';

	if ($class != '') $html .= ' class="'.$class.'"';
	$html .= '/><img id="img_'.$xslug.'" src="'.wppa_get_imgdir().'star.ico" title="'.__('Setting unmodified', 'wp-photo-album-plus' ).'" style="padding-left:4px;float:left;height:16px;width:16px"';
	if ($class != '') $html .= ' class="'.$class.'"';
	$html .= '/>';

	return $html;
}

function wppa_select($xslug, $opts, $vals, $onchange = '', $class = '', $first_disable = false, $postaction = '', $max_width = '300' ) {
global $wppa_opt;
global $wppa_defaults;
global $wppa_hide_this;

	$html = '<div id="' . $xslug . '" ' . ( $wppa_hide_this ? 'style="display:none;"' : '' ) . '>';

	$slug = substr( $xslug, 5 );

	if ( ! is_array( $opts ) ) {
		$html .= __('There is nothing to select.', 'wp-photo-album-plus' );
		return $html.'</div>';
	}

	$tit = __('Slug =', 'wp-photo-album-plus' ).' '.$xslug."\n".__('Values = ', 'wp-photo-album-plus' );
	foreach( $vals as $val ) $tit.= $val.', ';
	$tit = trim( $tit, ', ');
	$title = wppa_switch( 'enable_shortcode_wppa_set' ) ? $tit : '';

	$dflt = isset( $wppa_defaults[$xslug] ) ? $wppa_defaults[$xslug] : null;
	$val = wppa_get_option( $xslug );

	$html .= '
	<select
		id="' . $slug . '"
		style="
			float:left;
			font-size:11px;
			height:20px;
			margin:0px;
			padding:0px;
			max-width:' . $max_width . 'px;
			font-style:' . ( $dflt === $val ? 'italic':'normal' ) . ';"
		title="' . $title . '"
		class="' . $class . '"
		onchange="' . esc_attr( $onchange . ';
								wppaAjaxUpdateOptionValue(\'' . $slug . '\', this);' .
								$postaction . ';
								if (jQuery(this).val() == \'' . $dflt . '\') {jQuery(this).css({fontStyle:\'italic\'})} else {jQuery(this).css({fontStyle:\'normal\'})};' ) . '"
	>';

	$idx = 0;
	$cnt = count($opts);
	while ($idx < $cnt) {
		$html .= "\n";
		$html .= '
		<option
			id="' . $xslug . '-' . esc_attr( $vals[$idx] ) . '"
			value="'.esc_attr($vals[$idx]).'" ';
		$dis = false;
		if ($idx == 0 && $first_disable) $dis = true;
		$opt = trim($opts[$idx], '|');
		if ($opt != $opts[$idx]) $dis = true;
		$ital = $dflt !== null && $vals[$idx] == $dflt;
		$html .= ' style="font-style:'.($ital?'italic':'normal').'"';
		if ($val == $vals[$idx]) $html .= ' selected';
		if ($dis) $html .= ' disabled';
		$html .= '>'.$opt.'</option>';
		$idx++;
	}
	$html .= '</select>';
	$html .= '<img id="img_'.str_replace( '#', 'H', $slug ).'" class="'.$class.'" src="'.wppa_get_imgdir().'star.ico" title="'.__('Setting unmodified', 'wp-photo-album-plus' ).'" style="padding:0 4px;float:left;height:16px;width:16px" />';

	$html .= wppa_setting_star( $onchange );
	$html .= wppa_setting_star( $postaction );

	$html .= '</div>';

	return $html;
}

function wppa_select_m($xslug, $opts, $vals, $onchange = '', $class = '', $first_disable = false, $postaction = '', $max_width = '220' ) {
global $wppa_opt;

	$slug = substr( $xslug, 5 );

	if ( ! is_array( $opts ) ) {
		$html = __('There is nothing to select.', 'wp-photo-album-plus' );
		return $html;
	}

	$size 	= min( 10, count( $opts ) );
	$title 	= wppa_is_mobile() ? '' : __( 'Hold CTRL key to add/remove items from the selection, click outside the selectionbox for immediate update', 'wp-photo-album-plus' );
	$html 	= '
	<select
		style="float:left;font-size:11px;margin:0px;padding:0px;max-width:' . $max_width . 'px;height:auto !important;"
		id="' . $slug . '"
		multiple
		size="' . $size . '"
		onblur="' . esc_attr( $onchange . ';wppaAjaxUpdateOptionValue(\'' . $slug . '\', this, true);' . $postaction . ';' ) . '"
		class="' . $class . '"
		title="' . esc_attr( $title ) . '"
		>';

	$val = wppa_opt( $slug );
	$idx = 0;
	$cnt = count( $opts );

	$pages = wppa_expand_enum( wppa_opt( $slug ) );
	$pages = explode( '.', $pages );

	while ( $idx < $cnt ) {

		$dis = false;
		if ( $idx == 0 && $first_disable ) $dis = true;
		$opt = trim( $opts[$idx], '|' );
		if ( $opt != $opts[$idx] ) $dis = true;

		$sel = false;
		if ( in_array( $vals[$idx], $pages ) ) $sel = true;

		$html .= 	'<option' .
						' id="' . $xslug . '-' . esc_attr( $vals[$idx] ) . '"' .
						' class="' . $slug . '"' .
						' value="' . esc_attr( $vals[$idx] ) . '" ' .
						( $sel ? ' selected' : '' ) .
						( $dis ? ' disabled' : '' ) .
						'>' .
						$opt .
					'</option>';
		$idx++;
	}
	$html .= '</select>';
	$html .= '<img id="img_'.$slug.'" class="'.$class.'" src="'.wppa_get_imgdir().'star.ico" title="'.__('Setting unmodified', 'wp-photo-album-plus' ).'" style="padding:0 4px;float:left;height:16px;width:16px" />';

	$html .= wppa_setting_star( $onchange );

	return $html;
}

function wppa_select_e( $xslug, $curval, $opts, $vals, $onchange = '', $class = '' ) {

	$slug = substr( $xslug, 5 );

	if ( ! is_array( $opts ) ) {
		$html = __('There is nothing to select.', 'wp-photo-album-plus' );
		return $html;
	}

	$html = '<select style="float:left;font-size:11px;height:20px;margin:0px;padding:0px" id="'.$slug.'"';
	if ($onchange != '') $html .= ' onchange="'.esc_attr($onchange.';wppaAjaxUpdateOptionValue(\''.$slug.'\', this);').'"';
	else $html .= ' onchange="wppaAjaxUpdateOptionValue(\''.$slug.'\', this)"';

	if ($class != '') $html .= ' class="'.$class.'"';
	$html .= '>';

	$val = $curval;
	$idx = 0;
	$cnt = count($opts);
	while ($idx < $cnt) {
		$html .= "\n";
		$html .= '<option value="'.esc_attr($vals[$idx]).'" ';
		if ($val == $vals[$idx]) $html .= ' selected';
		$html .= '>'.$opts[$idx].'</option>';
		$idx++;
	}
	$html .= '</select>';
	$html .= '<img id="img_'.str_replace( '#', 'H', $slug ).'" class="'.$class.'" src="'.wppa_get_imgdir().'star.ico" title="'.__('Setting unmodified', 'wp-photo-album-plus' ).'" style="padding-left:4px;float:left;height:16px;width:16px" />';

	return $html;
}

function wppa_dflt($slug) {
global $wppa_defaults;
global $no_default;

	if ( $slug == '' ) return '';
	if ( $no_default ) return '';

	$dflt = isset( $wppa_defaults[$slug] ) ? $wppa_defaults[$slug] : '';

	$dft = $dflt;
	switch ($dflt) {
		case 'yes': 	$dft = __('Checked', 'wp-photo-album-plus' ); break;
		case 'no': 		$dft = __('Unchecked', 'wp-photo-album-plus' ); break;
/*		case 'none': 	$dft .= ': '.__('no link at all.', 'wp-photo-album-plus' ); break;
		case 'file': 	$dft .= ': '.__('the plain photo (file).', 'wp-photo-album-plus' ); break;
		case 'photo': 	$dft .= ': '.__('the full size photo in a slideshow.', 'wp-photo-album-plus' ); break;
		case 'single': 	$dft .= ': '.__('the fullsize photo on its own.', 'wp-photo-album-plus' ); break;
		case 'indiv': 	$dft .= ': '.__('the photo specific link.', 'wp-photo-album-plus' ); break;
		case 'album': 	$dft .= ': '.__('the content of the album.', 'wp-photo-album-plus' ); break;
		case 'widget': 	$dft .= ': '.__('defined at widget activation.', 'wp-photo-album-plus' ); break;
		case 'custom': 	$dft .= ': '.__('defined on widget admin page.', 'wp-photo-album-plus' ); break;
		case 'same': 	$dft .= ': '.__('same as title.', 'wp-photo-album-plus' ); break;
*/
		default:
	}

	return $dft;
}

function wppa_color_box( $xslug ) {

	$slug = substr( $xslug, 5 );

	return '
	<div
		id="colorbox-' . $slug . '"
		class="wppa-colorbox"
		style="width:100px;height:16px;float:left;background-color:' . wppa_opt( $slug ) . ';border:1px solid #dfdfdf"
	>
	</div>';

}

function wppa_moveup_button( $slug, $i ) {
global $wppa_cur_tab;

	$label 		= __('Move up', 'wp-photo-album-plus' );
	$onclick 	= 'jQuery(\'#wppa-admin-spinner\').show();' .
				  'document.getElementById(\'wppa-key\').value=\'wppa_moveup\';' .
				  'document.getElementById(\'wppa-sub\').value=' . $i . ';' .
				  'if ( confirm(\'Are you sure?\')) return true; else return false;';
	$tab 		= $wppa_cur_tab;

	$result = '
		<form
			enctype="multipart/form-data"
			action="' . get_admin_url() . 'admin.php?page=wppa_options&wppa-tab=' . $tab . '&wppa-nonce=' . wp_create_nonce( 'wppa-nonce' ) . '"
			method="post"
			>
			<input
				type="hidden"
				name="wppa-key"
				id="wppa-key-' . $slug . '"
				value="' . $slug . '"
			/>
			<input
				type="hidden"
				name="wppa-sub"
				id="wppa-sub-' . $i . '"
				value="' . $i . '"
			/>
			<input
				type="submit"
				class="wppa-doit-button"
				name="wppa-settings-submit"
				value="' . $label . '"
			/>
		</form>';

	return $result;
}

function wppa_upload_form( $slug, $tab, $accept = 'image/*' ) {

	$label 		= __('Upload now!', 'wp-photo-album-plus' );
	$onclick 	= 'jQuery(\'#wppa-admin-spinner\').show();';

	$result = '
	<form
		enctype="multipart/form-data"
		action="' . get_admin_url() . 'admin.php?page=wppa_options&wppa-tab=' . $tab . '&wppa-nonce=' . wp_create_nonce( 'wppa-nonce' ) . '"
		method="post"
		>
		<input
			id="my_file_element"
			type="file"
			accept="' . $accept . '"
			name="file_1"
			style="float:left;font-size:11px;height:fit-content !important;"
		/>
		<input
			type="hidden"
			name="wppa-key"
			id="wppa-key-' . $slug . '"
			value="' . $slug . '"
		/>
		<input
			type="submit"
			class="wppa-doit-button"
			style="height:30px;"
			name="wppa-settings-submit"
			value="' . $label . '"
			onclick="' . $onclick . '"
		/>
	</form>';

	return $result;
}

function wppa_doit_button_new( $slug, $height = '18' ) {

	$result = '
	<input
		type="button"
		class="wppa-doit-button"
		style="height: ' . $height . 'px;"
		name="wppa-settings-submit" value="' . __( 'Do it!', 'wp-photo-album-plus' ) . '"
		onclick="if ( confirm(\'' . __( 'Are you sure?', 'wp-photo-album-plus' ) . '\') ) {
			jQuery(\'#wppa-admin-spinner\').show();document.location.href=wppaReturnUrl(\''.$slug.'\');
		} else return false;"
	/>';

	return $result;
}

function wppa_popup_button( $slug, $height = '18' ) {

	$label 	= __('Show!', 'wp-photo-album-plus' );
	$result = '
	<input
		type="button"
		class="wppa-doit-button"
		style="height: ' . $height . 'px;"
		value="' . esc_attr($label) . '"
		onclick="wppaAjaxPopupWindow(\''.$slug.'\')"
	/>';

	return $result;
}

function wppa_ajax_button( $label, $slug, $elmid = '0', $no_confirm = false ) {
	if ( $label == '' ) $label = __('Do it!', 'wp-photo-album-plus' );

	$result = '
	<input
		type="button"
		class="wppa-doit-button"
		style="height:18px"
		value="' . esc_attr( $label ) . '"';

	$result .= ' onclick="';
	if ( ! $no_confirm ) $result .= 'if (confirm(\''.__('Are you sure?', 'wp-photo-album-plus' ).'\')) ';
	if ( $elmid ) {
		$result .= 'wppaAjaxUpdateOptionValue(\''.$slug.'\', document.getElementById(\''.$elmid.'\'))" />';
	}
	else {
		$result .= 'wppaAjaxUpdateOptionValue(\''.$slug.'\', 0)" />';
	}

	$result .= '<img id="img_'.$slug.'" src="'.wppa_get_imgdir().'star.ico" title="'.__('Not done yet', 'wp-photo-album-plus' ).'" style="padding:0 4px;float:left;height:16px;width:16px" />';

	return $result;
}

function wppa_cronjob_button( $slug ) {

	$label 	= __( 'Start as cron job', 'wp-photo-album-plus' );
	$me 	= wppa_get_user();
	$user 	= wppa_get_option( $slug.'_user', $me );

	if ( $user && $user != $me ) {
		$label = __( 'Locked!', 'wp-photo-album-plus' );
		$locked = true;
	}
	else {
		$locked = false;
	}

	// Check for apparently crashed cron job
	$crashed = wppa_is_maintenance_cron_job_crashed( $slug );
	if ( $crashed ) {
		$label = __( 'Crashed!', 'wp-photo-album-plus' );
	}

	// Make the html
	$result = 	'
	<input
		id="' . $slug . '_cron_button"
		type="button"
		class="wppa-doit-button"
		style="height:18px' . ( $crashed ? ';color:red': '' ) . '"
		value="' . esc_attr( $label ) . '"';

	if ( ! $locked ) {
		$result .= ' onclick="if ( jQuery(\'#'.$slug.'_status\').html() != \'\' || confirm(\''.__('Are you sure?', 'wp-photo-album-plus' ).'\') ) wppaMaintenanceProc(\''.$slug.'\', false, true);" />';
	}
	else {
		if ( $crashed ) {
			$result .= ' title="' . esc_attr( __( 'Click me to resume', 'wp-photo-album-plus' ) ) . '"';
		}
		$result .= ' onclick="if ( confirm(\''.__('Are you sure you want to unlock and resume cron job?', 'wp-photo-album-plus' ).'\') ) wppaMaintenanceProc(\''.$slug.'\', false, true); " />';
	}

	return $result;
}

function wppa_maintenance_button( $slug, $height = '18' ) {

	$label 	= __( 'Start!', 'wp-photo-album-plus' );
	$me 	= wppa_get_user();
	$user 	= wppa_get_option( $slug . '_user', $me );

	if ( $user && $user != $me ) {
		$label 		= __('Locked!', 'wp-photo-album-plus' );
		$locked 	= true;
		$onclick 	= 'alert(\'Is currently being executed by '.$user.'.\')';
	}
	else {
		$locked 	= false;
		$onclick 	= 'if ( jQuery(\'#' . $slug . '_status\').html() != \'\' ||
							confirm(\'' . __( 'Are you sure?', 'wp-photo-album-plus' ) . '\') ) {
							wppaMaintenanceProc(\'' . $slug . '\', false);
							if ( jQuery(\'#' . $slug . '_status\').html() == \'\' ) {
								setTimeout(function(){wppaAjaxUpdateTogo(\'' . $slug . '\')}, 1000);
							}
						}';
	}

	$result = '
	<input id="' . $slug . '_button"
		type="button"
		class="wppa-doit-button"
		style="height:' . $height . 'px;"
		value="' . esc_attr( $label ) . '"
		onclick="' . $onclick . '"
	/>';

	$result .= '<input id="'.$slug.'_continue" type="hidden" value="no" />';

	return $result;
}

function wppa_status_field( $slug ) {
	$status = wppa_get_option( $slug.'_status', '' );
	$result = '<span id="'.$slug.'_status" >'.$status.'</span>';
	if ( $status == __( 'Ready', 'wp-photo-album-plus' ) ) {
		delete_option( $slug.'_status' );
	}
	return $result;
}

function wppa_togo_field( $slug ) {
	$togo  = wppa_get_option($slug.'_togo', '' );
	$is_cron = wppa_get_option($slug.'_user', '' ) == 'cron-job';
	$result = '<span id="'.$slug.'_togo" >' . $togo . '</span>';
	if ( $togo || $is_cron ) {
		$the_js = 'jQuery(document).ready(function(){setTimeout(function(){wppaAjaxUpdateTogo(\''.$slug.'\')}, 1000)});';
		wppa_add_inline_script( 'wppa-admin', $the_js, false );
	}
	return $result;
}

// See if a given page exist, if vanished, set the option to 0
function wppa_verify_page( $xslug ) {
global $wpdb;
global $wppa_opt;

	// Does slug exist?
	if ( ! isset( $wppa_opt[$xslug] ) ) {
		wppa_error_message('Unexpected error in wppa_verify_page()', 'red', 'force');
		return;
	}

	// A page number 0 or -1 is allowed ( same post/page )
	if ( $wppa_opt[$xslug] == '0' || $wppa_opt[$xslug] == '-1' ) {
		return;
	}

	$slug = substr( $xslug, 5 );

	// If page vanished, update to 0
	$iret = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->posts
											 WHERE post_type = 'page'
											 AND post_status = 'publish'
											 AND ID = %s", wppa_opt( $slug )));
	if ( ! $iret ) {
		wppa_update_option($slug, '0');
	}
}

// Find imageMagick external cmmands
function wppa_search_magick( $root = '' ) {
	static $level;
	if ( ! $level ) $level = 1;

	static $result;
	if ( ! $result ) $result = array();

	$paths = glob( $root . '/*', GLOB_ONLYDIR );
	foreach( $paths as $path ) if ( $path != '..' && $path != '.' ) {

		$file = basename( $path );
		if ( ! in_array( $file, array( 'wp-admin', 'wp-content', 'wp-include' ) ) ) {

			if ( is_readable( $path ) ) {

				if ( is_file( $path . '/convert' ) ) {
					exec( escapeshellcmd( $path . '/convert -version' ), $out, $err );
					if ( ! $err ) {
						$ver = strpos( $out[0], 'ImageMagick' );
						if ( $ver != false ) {
							$result[] = array( $path, $out[0] );
						}
					}
					unset( $out );
				}
				elseif ( $level < 4 ) {
					$level++;
					wppa_search_magick( $path );
					$level--;
				}
			}
		}
	}
	return $result;
}

// New style settings page master tab
function wppa_master_tab( $slug, $target, $caption, $active = false ) {

	wppa_echo( '
	<li
		id="wppa-master-tab-' . $slug . '"
		class="' . ( $active ? 'active ' : '' ) . 'mtabli ' . $slug . '"
		onclick="wppaSettingTab(\'' . $target . '\');"
		style="background-color:' . ( $active ? '#ffffff' : '#eeeeee' ) . '">
		' . $caption . '
	</li>' );
}

// New style settings page selection tab html
function wppa_setting_tab( $slug, $caption, $show = true ) {
global $wppa_cur_tab;

	$active = $wppa_cur_tab == $slug;

	wppa_echo( '
	<li
		id="wppa-setting-tab-' . $slug . '"
		class="' . ( $active ? 'active ' : '' ) . 'tabli ' . $slug . '"
		onclick="wppaSettingTab(\'' . $slug . '\');"
		style="' . ( $show ? '' : 'display:none;' ) . 'background-color:' . ( $active ? '#ffffff' : '#eeeeee' ) . '">
		' . $caption . '
	</li>' );
}

// New style setting
function wppa_setting_new( $slug, $xnum, $name, $desc, $html, $help = '', $show = true, $cls = '' ) {
global $wppa_defaults;
global $no_default;
global $wppa_opt;
global $wppa_requested_subtab;
global $wppa_requested_items;
global $wppa_cur_subtab_id;
global $wppa_cur_tab;
global $wppa_setting_error;
global $wppa_hide_this;

	// Unique item id
	$item_id = $wppa_cur_tab . '-' . $wppa_cur_subtab_id . '-' . str_replace( array( ' ', '.' ), '-', $xnum );

	// Is this item requested?
	$is_requested = false;
	if ( $wppa_requested_subtab == $wppa_cur_subtab_id ) {
		if ( $wppa_requested_items !== false && in_array( $xnum, $wppa_requested_items ) ) {
			$is_requested = true;
		}
	}

	// Bg color
	$error = wppa_get( 'error' );
	if ( $is_requested ) {
		if ( $error ) {
			$bgcolor = '#ffeeee';
		}
		else {
			$bgcolor = '#eeffee';
		}
	}
	else {
		$bgcolor = '#ffffff';
	}
	if ( $wppa_setting_error ) {
		$bgcolor = '#ffeeee';
	}

	// Default helptext
	if ( ! $help ) {
		$help = __( 'No helptext available', 'wp-photo-album-plus' );
	}

	// Convert single slug to array with 1 elm
	if ( is_array( $slug ) ) $slugs = $slug;
	else {
		$slugs = array();
		if ( $slug ) $slugs[] = $slug;
		else $slugs[0] = '';
	}

	if ( is_array( $html ) ) $htmls = $html;
	else {
		$htmls = array();
		if ( $html ) $htmls[0] = $html;
	}

	if ( strpos( $xnum, ',' ) !== false ) {
		$nums = explode( ',', $xnum );
		$nums[0] = substr( $nums[0], 1 );
	}
	else {
		$nums = array();
		if ( $xnum ) $nums[] = $xnum;
	}

	// Make the helptext
	if ( $help ) {
		$is_dflt = true;

		$helptext = strip_tags( wp_check_invalid_utf8( $help ), ["<br>", "<a>", "<b>", "<i>"] );

		if ( ! $no_default ) {
			if ( $slugs && wppa_dflt($slugs[0]) ) {
				if ( count($slugs) == 1) {
					$helptext .= '<br>' . __( 'The default for this setting is:', 'wp-photo-album-plus' );
					if ( $slugs[0] != '' ) {
						$helptext .= ' '.htmlspecialchars(wppa_dflt($slugs[0]));
						if ( $wppa_opt[$slugs[0]] != $wppa_defaults[$slugs[0]] ) {
							$is_dflt = false;
						}
					}
				}
				else {
					$helptext .= '<br>' . __( 'The defaults for this setting are', 'wp-photo-album-plus' );
					$first = true;
					foreach ( array_keys($slugs) as $slugidx ) {
						if ( $slugs[$slugidx] ) $helptext .= ( $first ? ': ' : ', ' ).htmlspecialchars(wppa_dflt($slugs[$slugidx]));
						$first = false;
						if ( $slugs[$slugidx] && isset($wppa_opt[$slugs[$slugidx]]) && $wppa_opt[$slugs[$slugidx]] != $wppa_defaults[$slugs[$slugidx]] ) {
							$is_dflt = false;
						}
					}
				}
			}
		}
	}
	else {
		$helptext = '';
	}

	// Start the item
	$result =
	'<tr
		id="wppa-setting-item-' . $item_id . '"
		class="wppa-setting-new ' . $item_id . ' ' . $slugs[0] . ' ' . $cls . '"
		style="color:#333;background-color:' . $bgcolor .
			( $show === false ? ';display:none' : '' ) .
		'">' .

		// The item number
		'<td>' . esc_html( $xnum ) . '</td>' .

		// The Item name
		'<td>' . strip_tags( wp_check_invalid_utf8( $name ), ["<br>", "<a>", "<b>", "<i>", "<span>"] ) . '</td>' .

		// The item description
		'<td><small>' . strip_tags( wp_check_invalid_utf8( $desc ), ["<br>", "<a>", "<b>", "<i>", "<span>", "<input>"] ) . '</small></td>';

		// The html
		if ( $htmls ) foreach ( $htmls as $html ) {
			$result .= '<td>' . $html . '</td>';
		}
		else {
			$result .= '<td></td>';
		}

		// The helpbutton
		$result .= '
		<td>
			<input
				type="button"
				style="font-size:11px;height:20px;padding:0;cursor:pointer"
				class=""
				title="' . esc_attr( __( 'Click for help', 'wp-photo-album-plus' ) ) . '"
				onclick="
					if ( jQuery( \'#help-' . $item_id . '\' ).css(\'display\') == \'none\' ) {
						jQuery( \'#help-' . $item_id . '\' ).css(\'display\',\'\');
					}
					else {
						jQuery( \'#help-' . $item_id . '\' ).css(\'display\',\'none\');
					}"
				value="&nbsp;?&nbsp;" />
		</td>' .

	// Close item
	'</tr>';

	// The Helptext
	if ( $help ) {
		$result .= '
		<tr
			id="help-' . $item_id . '"
			class="wppa-setting-new"
			style="display:none">
			<td></td>
			<td></td>
			<td
				style="color:#000077"
				colspan="' . ( count( $htmls ) ) . '">
				<small>
					<i>' .
						$helptext . '
					</i>
				</small>
			</td>
			<td></td>
			<td></td>
		</tr>';
	}

	// Reset $wppa_hide_this
	$wppa_hide_this = false;
	wppa_echo( $result );
}

// The tab description
function wppa_setting_tab_description( $desc ) {
global $wppa_cur_subtab;
global $wppa_cur_subtab_id;

	$wppa_cur_subtab = md5( $desc );
	wppa_bump_subtab_id();

	$greek = array('0', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X');
	$the_js = '
	jQuery(document).ready(function(){
		var cookie = wppa_getCookie(\'' . $wppa_cur_subtab . '\');
		if ( cookie == "on" ) {
			jQuery(".wppa-tabdesc-' . $wppa_cur_subtab . '").css("background-color","#ffffff");
			jQuery(".wppa-tabdesc-' . $wppa_cur_subtab . '").attr("data-inactive","0");
		}
		else {
			jQuery(".wppa-tabdesc-' . $wppa_cur_subtab . '").css("background-color","#eeeeee");
			jQuery(".wppa-tabdesc-' . $wppa_cur_subtab . '").attr("data-inactive","1");
		}
	});';
	wppa_add_inline_script( 'wppa-admin', $the_js, true );

	wppa_echo( '
	<div
		class="wppa-tabdesc wppa-tabdesc-' . $wppa_cur_subtab . ' ' . $wppa_cur_subtab_id . '"
		style="text-align:center;border-bottom:1px solid darkgrey;cursor:pointer"
		onclick="wppaToggleSubtab(\'' . $wppa_cur_subtab . '\');">
		<h3>' .
			$greek[$wppa_cur_subtab_id] .
		': ' .
			esc_html( $desc ) .
		'</h3>
		<span
			id="' . $wppa_cur_subtab . '-cm"
			style="color:red;display:none">' .
			__( 'Click to toggle open / close', 'wp-photo-album-plus' ) . '
		</span>
	</div>' );
}

// Increment the subtab id (a.o. for the greek number)
function wppa_bump_subtab_id() {
global $wppa_cur_subtab_id;

	if ( ! $wppa_cur_subtab_id ) {
		$wppa_cur_subtab_id = 0;
	}
	$wppa_cur_subtab_id++;
}

// The new style setting box header
function wppa_setting_box_header_new( $tab, $cols = false ) {
global $wppa_cur_tab;
global $wppa_cur_subtab;

	if ( ! $cols ) {
		$cols = array(
			__( '#', 'wp-photo-album-plus' ) => '24px',
			__( 'Name', 'wp-photo-album-plus' ) => '15%',
			__( 'Description', 'wp-photo-album-plus' ) => '30%',
			__( 'Setting', 'wp-photo-album-plus' ) => 'auto',
			__( 'Help', 'wp-photo-album-plus' ) => '24px',
		);
	}

	$the_js = '
	jQuery(document).ready(function(){
		if ( \'' . $wppa_cur_tab . '\' == \'general\' ||
			 \'' . $wppa_cur_tab . '\' == \'generaladv\' ) {
			wppa_setCookie(\'' . $wppa_cur_subtab . '\', \'on\', 30);
		}
		var cookie = wppa_getCookie(\'' . $wppa_cur_subtab . '\');
		jQuery(\'#' . $wppa_cur_subtab . '-cm\').hide();
		if ( cookie == \'on\' ) {
			jQuery(\'#' . $wppa_cur_subtab . '\').show();
		}
		else if ( cookie == \'\' ) {
			jQuery(\'#' . $wppa_cur_subtab . '-cm\').show();
		}
	});';
	wppa_add_inline_script( 'wppa-admin', $the_js, true );

	$result = '
	<div
		id="' . $wppa_cur_subtab . '"
		class="wppa-setting-content ' . $wppa_cur_subtab . '"
		style="display:none">
		<table class="widefat wppa-table wppa-setting-table striped">

			<colgroup>';
				foreach( $cols as $size ) {
					$result .= '<col style="width:' . $size . '">';
				}
			$result .= '
			</colgroup>

			<thead style="font-weight:bold">
				<tr>';
					foreach( array_keys( $cols ) as $caption ) {
						$result .= '<td>' . $caption . '</td>';
					}
				$result .= '
				</tr>
			</thead>

			<tbody class="wppa_table_' . $tab . '">';

	wppa_echo( $result );

}

// The new style setting box footer
function wppa_setting_box_footer_new() {
	wppa_echo( '</tbody></table></div>' );
}

function wppa_need_page( $slug ) {
global $wppa_opt;

	$value = $wppa_opt[$slug];

	if ( in_array( $value, array( 	'none',
									'file',
									'widget',
									'custom',
									'same',
									'fullpopup',
									'lightbox',
								) ) ) {
		$result = false;
	}
	else {
		$result = true;
	}

	return $result;
}

// Star indcator page will be reloaded after changing the setting
function wppa_setting_star( $onch ) {

	if ( strpos( $onch, 'wppaRefreshAfter()' ) !== false ) {
		$html = '
		<span
			style="float:left;color:red;font-size:28px;line-height:8px;cursor:pointer"
			title="' . __( 'After changing this setting the page will be reloaded', 'wp-photo-album-plus' ) . '"><sup>*</sup>
		</span>';
		return $html;
	}
	else {
		return '';
	}
}

// Get the htmlfor the potd preview
function wppa_get_potd_preview_html( $photo ) {

	if ( $photo ) {
		if ( wppa_is_video( $photo['id'] ) ) {
			$html = '
			<div id="potdpreview" style="display:inline-block;width:25%;text-align:center;float:left">' .
				wppa_get_video_html( array( 'id' => $photo['id'], 'width' => '180' ) ) . '
			</div>';
		}
		else {
			$html = '
			<div id="potdpreview" style="display:inline-block;width:25%;text-align:center;float:left">
				<img
					src="' . esc_url( wppa_get_thumb_url( $photo['id'] ) ) . '"
					style="width: 180px;"
				/>
			</div>';
		}
		$html .= '
			<div style="display:inline-block;width:75%;text-align:center;vertical-align:middle">' .
				__( 'Album', 'wp-photo-album-plus' ) . ': ' . htmlspecialchars( wppa_get_album_name( $photo['album'] ) ) . '
				<br>' .
				__('Uploader', 'wp-photo-album-plus' ) . ': ' . htmlspecialchars( $photo['owner'] ) . '
			</div>';
	}
	else {
		$html = __( 'Not found.', 'wp-photo-album-plus' );
	}

	$result = wppa_compress_html( $html );
	return $result;
}

// Get teh html for the potd pool
function wppa_get_potd_pool_html() {

	$html = '
	<table
		id="potd-pool-table"
		class="potd-pool widefat wppa-table wppa-setting-table"
		style="' . ( wppa_switch( 'potd_preview' ) ? '' : 'display:none' ) . '">
		<thead>
			<tr>
				<td>' .
					__( 'Photos in the current selection', 'wp-photo-album-plus' ) . '
				</td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>';

					// Get the photos
					$alb 	= wppa_opt( 'potd_album' );
					$opt 	= wppa_is_int( $alb ) ? ' ' . wppa_get_photo_order( $alb ) . ' ' : '';
					$cnt 	= wppa_get_widgetphotos( $alb, 'count' );
					if ( $cnt < 101 ) {
						$photos = wppa_get_widgetphotos( $alb, $opt );
					}
					else {
						$photos = array();
					}

					// See if we do this
					if ( $cnt == '0' ) {
						$html .= __( 'No photos in the selection', 'wp-photo-album-plus' );
					}
					elseif ( $cnt > '100' ) {
						$html .= sprintf( __( 'There are too many photos in the selection to show a preview ( %d )', 'wp-photo-album-plus' ), $cnt );
					}
					else {

						// Yes, display the pool
						foreach ( $photos as $photo ) {
							$id = $photo['id'];

							// Open container div
							$html .= '
							<div
								class="photoselect"
								style="width:180px;height:200px;overflow:hidden"
								>';

								// Open image container div
								$html .= '
								<div
									style="width:180px;height:135px;overflow:hidden;text-align:center;"
									>';

									// The image if a video
									if ( wppa_is_video( $id ) ) {
										$html .= wppa_get_video_html( array( 	'id' 		=> $id,
																				'style' 	=> 'width:180px;'
																	));
									}

									// The image if a photo
									else {
										$html .= '
										<img
											src="' . esc_url( wppa_get_thumb_url( $id ) ) . '"
											style="max-width:180px;max-height:135px;margin:auto;"
											alt="' . esc_attr( wppa_get_photo_name( $id ) ) . '"
											/>';

										// Audio ?
										if ( wppa_has_audio( $id ) ) {
											$html .= wppa_get_audio_html( array( 	'id' 		=> 	$id,
																					'style' 	=> 	'width:180px;' .
																									'position:relative;' .
																									'bottom:' . ( wppa_get_audio_control_height() + 4 ) .'px;'
																		));
										}
									}

								// Close image container div
								$html .= '</div>';

								// The order# and select radio box
								$html .= '
								<div
									style="clear:both;width:100%;margin:3px 0;position:relative;top:5px;"
									>
									<div
										style="font-size:9px; line-height:10px;float:left"
										>
										(#' . strval( intval( $photo['p_order'] ) ) . ')
									</div>';

									if ( wppa_get_option( 'wppa_potd_method' ) == '1' ) { 	// Only if fixed photo
										$html .= '
										<input
											style="float:right;"
											type="radio"
											name="wppa-widget-photo"
											id="wppa-widget-photo-' . strval( intval( $id ) ) . '"
											value="' . esc_attr( $id ) . '"' .
											( $id == $curid  ? 'checked="checked"' : '' ) . '
											onchange="wppaSetFixed(' . strval( intval( $id ) ) . ');"
										/>';
									}

								$html .= '
								</div>';

								// The name/desc box
								$html .= '
								<div
									style="clear:both;overflow:hidden;height:150px;position:relative;top:10px;"
									>
									<div
										style="font-size:11px;overflow:hidden"
										>' .
										wppa_get_photo_name( $id ) . '
									</div>
									<div
										style="font-size:9px;line-height:10px;"
										>' .
										wppa_get_photo_desc( $id ) . '
									</div>
								</div>';

							// Close container
							$html .= '</div>';
						}
						$html .= '<div class="clear"></div>';
					}

				// Close the table
				$html .= '
				</td>
			</tr>
			</tbody>
		</table>';

	$result = wppa_compress_html( $html );
	return $result;
}