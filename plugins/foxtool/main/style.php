<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
// style foxtool
global $foxtool_options;
if(isset($foxtool_options['foxtool5']) && $foxtool_options['foxtool5'] == 'WordPress'){
	echo '<style>:root{--color:#2c3338 !important;--gradient:linear-gradient(19deg, rgb(34 113 177) 0%, rgb(48 138 210) 100%) !important;--logo:#2271b1 !important;--icon:#9ca2a7 !important;--nutbor:5px solid #1766a6 !important;--nutbor2:4px solid #111111 !important;--mailbg:#2271b1 !important;--note:#2271b129 !important;}</style>';
}
if(isset($foxtool_options['foxtool5']) && $foxtool_options['foxtool5'] == 'Bright'){
	echo '<style>:root{--color:#fbabab !important;--gradient:linear-gradient(19deg, rgb(251 171 171) 0%, rgb(216 127 127) 100%) !important;--logo:#dd7373 !important;--icon:#fff !important;--nutbor:5px solid #df8e8e !important;--nutbor2:4px solid #df8e8e !important;--mailbg:#fbabab !important;--note:#ff040714 !important;}</style>';
}
if(isset($foxtool_options['foxtool5']) && $foxtool_options['foxtool5'] == 'Girly'){
	echo '<style>:root{--color:#a18cb9 !important;--gradient:linear-gradient(19deg, rgb(119 90 159) 0%, rgb(182 157 211) 100%) !important;--logo:#775a9f !important;--icon:#fff !important;--nutbor:5px solid #695282 !important;--nutbor2:4px solid #695282 !important;--mailbg:#a18cb9 !important;--note:#9968bf3b !important;}</style>';
}
if(isset($foxtool_options['foxtool5']) && $foxtool_options['foxtool5'] == 'Black'){
	echo '<style>:root{--color:#2f2f2f !important;--gradient:linear-gradient(19deg, rgb(51 51 51) 0%, rgb(87 87 87) 100%) !important;--logo:#515151 !important;--icon:#fff !important;--nutbor:5px solid #000000 !important;--nutbor2:4px solid #000000 !important;--mailbg:#2f2f2f !important;--note:#5959593b !important;}</style>';
}