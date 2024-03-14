<?php
function sseo_twitter() {
echo '
<meta name="twitter:card" content="summary" />'."\n";
$sseo_twittertitle = sseo_title();
if(!empty($sseo_twittertitle)){
echo '
<meta name="twitter:title" content="'.esc_attr($sseo_twittertitle).'" />'."\n";
}
}

if(esc_attr(get_option('sseo_activate_twittercard'))){
add_filter( 'wp_head', 'sseo_twitter', 1 );
}

?>