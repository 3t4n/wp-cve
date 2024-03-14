<?php
// =============================  [weaver_vimeo] ==============================

function wvr_compat_do_vimeo($args = '') {
    $share = '';
    if ( isset ( $args[0] ) )
        $share = trim($args[0]);

    extract(shortcode_atts(array(
        'id' => '',
        'sd' => false,
        'color' => '',
        'autoplay' => false,
        'loop' => false,
        'portrait' => true,
        'title' => true,
        'byline' => true,
        'percent' => 100,
        'center' => '1'
        ), $args));

    if (!$share && !$id) return __('<strong>No share or id values provided for vimeo shortcode.</strong>','weaver-xtreme' /*adm*/);
	if (!isset($GLOBALS['wvrc_videos_count']) )
		$GLOBALS['wvrc_videos_count'] = 1;
	else
		$GLOBALS['wvrc_videos_count']++;


    if ($share)	{	// let the share override any id
        $share = str_replace('http://vimeo.com/','',$share);
        if ($share) $id = $share;
    }

    $opts = $id . '##';

    $opts = wvr_compat_v_add_url_opt($opts, $autoplay, 'autoplay=1');
    $opts = wvr_compat_v_add_url_opt($opts, $loop, 'loop=1');
    $opts = wvr_compat_v_add_url_opt($opts, $color, 'color=' . $color);
    $opts = wvr_compat_v_add_url_opt($opts, !$portrait, 'portrait=0');
    $opts = wvr_compat_v_add_url_opt($opts, !$title, 'title=0');
    $opts = wvr_compat_v_add_url_opt($opts, !$byline, 'byline=0');

    $url = '//player.vimeo.com/video/';

    $opts = str_replace('##+','##?', $opts);
    $opts = str_replace('##','', $opts);
    $opts = str_replace('+','&amp;', $opts);

    $url .= $opts;

    if (function_exists('weaverii_use_mobile'))
        if (weaverii_use_mobile('mobile')) $percent = 100;


    $cntr1 = $center ? "<div class=\"wvr-video wvr-fitvids wvr-vimeo\" style=\"margin-left:auto;margin-right:auto;max-width:{$percent}%;\">" :
                       "<div class=\"wvr-video wvr-fitvids wvr-vimeo\" style=\"max-width:{$percent}%;\">";
    $cntr2 = '</div>';
    $h = 9; $w = 16;
    if ( $sd ) {
        $h = 3; $w = 4;
    }

    $ret = "\n" . $cntr1 . '<iframe src="' . $url
     . '" width="' . $w . '" height="' . $h . '" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>'
     . $cntr2 . "\n";

    return $ret;
}

// ===== video utils =====

function wvr_compat_v_add_url_opt($opts, $add, $add_val) {
    if ($add) {
	$opts = $opts . '+' . $add_val;
    }
    return $opts;
}
?>
