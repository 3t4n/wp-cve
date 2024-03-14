<?php
// =============================  [weaver_youtube] ==============================

function wvr_compat_do_youtube($args = '') {
    $share = '';
    if ( isset ( $args[0] ) )
	$share = trim($args[0]);

    // http://code.google.com/apis/youtube/player_parameters.html
    // not including: enablejsapi, fs,playerapiid,

    extract(shortcode_atts(array(
        'id' => '',
        'sd' => false,
        'related' => '0',
        'privacy' => false,
        'ratio' => false,
        'center' => '1',
        'autohide' => '~!',
        'autoplay' => '0',
        'border' => '0',
        'color' => false,
        'color1' => false,
        'color2' => false,
        'controls' => '1',
        'disablekb' => '0',
        'egm' => '0',
        'fs' => '1',
        'fullscreen' => 1,
        'hd' => '0',
        'iv_load_policy' => '1',
        'loop' => '0',
        'modestbranding' => '0',
        'origin' => false,
        'percent' => 100,
        'playlist' => false,
        'rel' => '0',
        'showinfo' => '1',
        'showsearch' => '1',
        'start' => false,
        'theme' => 'dark',
        'wmode' => 'transparent'

    ), $args));

    if (!$share && !$id)
        return __('<strong>No share or id values provided for youtube shortcode.</strong>','weaver-xtreme' /*adm*/);
	if (!isset($GLOBALS['wvrc_videos_count']) )
		$GLOBALS['wvrc_videos_count'] = 1;
	else
		$GLOBALS['wvrc_videos_count']++;

    if ($share)	{	// let the share override any id
        $share = str_replace('youtu.be/','',$share);
        if (strpos($share,'youtube.com/watch') !== false) {
            $share = str_replace('www.youtube.com/watch?v=', '', $share);
            $share = str_replace('&amp;','+',$share);
            $share = str_replace('&','+',$share);
        }
        $share = str_replace('http://','',$share);
        $share = str_replace('https://','',$share);
        if ($share)
            $id = $share;
    }

    $opts = $id . '%%';

    $opts = wvr_compat_y_add_url_opt($opts, $hd != '0', 'hd=1');
    $opts = wvr_compat_y_add_url_opt($opts, $autohide != '~!', 'autohide='.$autohide);
    $opts = wvr_compat_y_add_url_opt($opts, $autoplay != '0', 'autoplay=1');
    $opts = wvr_compat_y_add_url_opt($opts, $border != '0', 'border=1');
    $opts = wvr_compat_y_add_url_opt($opts, $color, 'color='.$color);
    $opts = wvr_compat_y_add_url_opt($opts, $color1, 'color1='.$color1);
    $opts = wvr_compat_y_add_url_opt($opts, $color2, 'color2='.$color2);
    $opts = wvr_compat_y_add_url_opt($opts, $controls != '1', 'controls=0');
    $opts = wvr_compat_y_add_url_opt($opts, $disablekb != '0', 'disablekb=1');
    $opts = wvr_compat_y_add_url_opt($opts, $egm != '0', 'egm=1');
    $opts = wvr_compat_y_add_url_opt($opts, true, 'fs='.$fs);
    $opts = wvr_compat_y_add_url_opt($opts, true, 'iv_load_policy='.$iv_load_policy);
    $opts = wvr_compat_y_add_url_opt($opts, $loop != '0', 'loop=1');
    $opts = wvr_compat_y_add_url_opt($opts, $modestbranding != '0', 'modestbranding=1');
    $opts = wvr_compat_y_add_url_opt($opts, $origin, 'origin='.$origin);
    $opts = wvr_compat_y_add_url_opt($opts, $playlist, 'playlist='.$playlist);
    $opts = wvr_compat_y_add_url_opt($opts, true, 'rel='.$rel);
    $opts = wvr_compat_y_add_url_opt($opts, true, 'showinfo=' . $showinfo);
    $opts = wvr_compat_y_add_url_opt($opts, $showsearch != '1', 'showsearch=0');
    $opts = wvr_compat_y_add_url_opt($opts, $start, 'start='.$start);
    $opts = wvr_compat_y_add_url_opt($opts, $theme != 'dark', 'theme=light');
    $opts = wvr_compat_y_add_url_opt($opts, $wmode, 'wmode='.$wmode);

    $url = '//';

    if ($privacy) $url .= 'www.youtube-nocookie.com';
    else $url .= 'www.youtube.com';

    $opts = str_replace('%%+','%%?', $opts);
    $opts = str_replace('%%','', $opts);
    $opts = str_replace('+','&amp;', $opts);

    $url .= '/embed/' . $opts;


    $allowfull = $fullscreen ? ' allowfullscreen="allowfullscreen"' : '';

    $cntr1 = $center ? "<div class=\"wvr-video wvr-fitvids wvr-youtube\" style=\"margin-left:auto;margin-right:auto;max-width:{$percent}%;\">" :
                       "<div class=\"wvr-video wvr-fitvids wvr-youtube\" style=\"max-width:{$percent}%;\">";
    $cntr2 = '</div>';
    $h = 9; $w = 16;
    if ( $sd ) {
        $h = 3; $w = 4;
    }

	$ret ="\n" . $cntr1 . '<iframe src="' . $url
     . '" frameborder="0" width="'.$w.'" height="' . $h . '" frameborder="0" ' . $allowfull . '></iframe>'
     . $cntr2 . "\n";

    return $ret;
}
// ===== video utils =====

function wvr_compat_y_add_url_opt($opts, $add, $add_val) {
    if ($add) {
	$opts = $opts . '+' . $add_val;
    }
    return $opts;
}
?>
