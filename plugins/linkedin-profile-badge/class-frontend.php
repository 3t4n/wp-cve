<?php

//ADD JS
function liJS() {
	$options = get_option('linkedinbadge');
if (!isset($options['JS'])) {$options['JS'] = "";}
if ($options['JS'] == 'on') {
?><script src="//platform.linkedin.com/in.js" type="text/javascript"></script><?php
}
}
add_action('wp_head', 'liJS');

function linkedinbadgeshortcode($liatts) {
    extract(shortcode_atts(array(
		"linkedinbadge" => get_option('linkedinbadge'),
    ), $liatts));
        foreach ($liatts as $key => $option) {
            $linkedinbadge[$key] = $option;
	}
	if ($linkedinbadge[connections] != 'on') { $connections = ' data-related="false"'; }
	if ($linkedinbadge[mode] != 'inline' && $linkedinbadge[liname] != '') { $name = ' data-text="'.$linkedinbadge[liname].'"';}
	$libadge ='<!-- Linkedin Profile Badge: http://3doordigital.com/wordpress/plugins/linkedin-profile-badge/ -->
<script type="IN/MemberProfile" data-id="'.$linkedinbadge[url].'" data-format="'.$linkedinbadge[behavior].'" '.$name.$connections.'></script>';

  return $libadge;
}
add_filter('widget_text', 'do_shortcode');
add_shortcode('linkedinbadge', 'linkedinbadgeshortcode');
?>