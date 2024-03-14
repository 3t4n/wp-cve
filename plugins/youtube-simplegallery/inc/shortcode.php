<?php

// SHORTCODE: YOUTUBEGALLERY / VIMEOGALLERY
function show_youtubegallery( $atts = array(), $youtubelinks = null ) {

	// CLEAN UP OEMBED
	if (stripos($youtubelinks, "</iframe>") !== false) {
		$embededmess = explode('<p>', $youtubelinks);

		foreach($embededmess as $line):
			if (stripos($line, "</iframe>") !== false) {
				$removeoembed = yotube_gallery_getAttribute('src', $line);
				$newarray[] = str_replace('http://player.vimeo.com/video', 'http://vimeo.com', $removeoembed);
			}
			else {
				$newarray[] = trim($line);
			}
		endforeach;

		$youtubelinks = array_filter($newarray, 'strlen');

	}
	else {
		$youtubelinks = explode("\n", $youtubelinks);
	}
	
	return global_output_youtubegallery( $atts, $youtubelinks );
}

// SHORTCODE YOUTUBEUSERFEED / VIMEOUSERFEED 
function show_youtubeuserfeed(  $atts = array() ) {

	if( isset($atts['user']) && isset($atts['service']) ){

	if( isset($atts['maxitems']) && $atts['maxitems']>0 )
		$maxitems = $atts['maxitems'];
	else
		$maxitems = null;

			// GET FEED FROM YOUTUBE
		 	if( $atts['service'] == 'youtube' ) {
				include_once( ABSPATH . WPINC . '/feed.php' );

				$youtubeuserfeed = fetch_feed('http://gdata.youtube.com/feeds/base/users/'.$atts['user'].'/uploads?alt=rss&v=2&orderby=published&client=ytapi-youtube-profile');
				if ( !is_wp_error( $youtubeuserfeed ) ): 
					$limit = $youtubeuserfeed->get_item_quantity($maxitems);
					$rss_items = $youtubeuserfeed->get_items( 0, $limit );

					foreach ( $rss_items as $item ) :
						$youtubelinks[] = $item->get_title() . "|" . $item->get_permalink();
					endforeach;
				else:
					return '<p>ERROR: No feed found for user <em>'.$atts['user'].'</em> on service <em>'.$atts['service'].'</em> …</p>';
					exit;
				endif;
			}
			// GET FEED FROM VIMEO
		 	elseif( $atts['service'] == 'vimeo' ) {
				$vimeouserfeed = unserialize(wp_remote_fopen('http://vimeo.com/api/v2/'.$atts['user'].'/videos.php'));
				if ( is_array($vimeouserfeed) ): 
					if(!is_null($maxitems)) $x = 0;
					foreach ( $vimeouserfeed as $item ) :
						$youtubelinks[] = $item['title'] . "|" . $item['url'];
						if(!is_null($maxitems)):
							$x++;
							if($x==$maxitems) break;
						endif;
					endforeach;
				else:
					return '<p>ERROR: No feed found for user <em>'.$atts['user'].'</em> on service <em>'.$atts['service'].'</em> …</p>';
					exit;
				endif;
			}

		return global_output_youtubegallery( $atts, $youtubelinks );
	}
	else {
		// DISPLAY ERROR IF USER OR SERVICE IS MISSING
		$error = '<p>ERROR: No ';
		if( !isset($atts['user']) && !isset($atts['service']) ) $error .= 'username or service';
		elseif ( !isset($atts['user']) && isset($atts['service']) ) $error .= 'username';
		elseif ( !isset($atts['service']) && isset($atts['user']) ) $error .= 'service';
		$error .= ' defined for feed!</p>';
		return $error;
	}

}

// OUTPUT GALLERY (GLOBAL FOR ALL SHORTCODES + WIDGETS)
function global_output_youtubegallery( $atts = array(), $youtubelinks = null ) {

	$youtubeoptions = get_option('youtube_gallery_option');

	$cols = $youtubeoptions['cols'];
	if(isset($atts['cols'])) $cols = $atts['cols'];

	$autotitles = $youtubeoptions['autotitles'];
	if(isset($atts['autotitles'])) $autotitles = $atts['autotitles'];

	global $youtube_gallery_count, $youtube_gallery_ID;
	$x = $youtube_gallery_count;
	$youtube_gallery_ID++;

	// GET LINKS AND CAPTIONS
	$thisgallery = array();
	$nol = 0; /* Counter for array */
	foreach ( $youtubelinks as $l ):

		$captions = 0;

		// Check if has caption
		if(strstr($l, '|')) { 
			$thumb = explode('|', $l); 
			$captions = 1;
		}
		if($captions) {
			$thisgallery[$nol]['caption'] = stripslashes($thumb[0]);
			$thisgallery[$nol]['url'] = strip_tags($thumb[1]);
			if(strstr($thumb[1], 'youtube.com')) $thisgallery[$nol]['source'] = 'youtube';
			if(strstr($thumb[1], 'vimeo.com')) $thisgallery[$nol]['source'] = 'vimeo';
		}
		else {
			$thisgallery[$nol]['url'] = strip_tags($l);
			if(strstr($l, 'youtube.com')) $thisgallery[$nol]['source'] = 'youtube';
			if(strstr($l, 'vimeo.com')) $thisgallery[$nol]['source'] = 'vimeo';
		}

		// Clean item if item is empty
		if(!strstr($thisgallery[$nol]['url'], 'http://')) unset($thisgallery[$nol]);	
		// Else increase counter
		else $nol++; 
	endforeach;

	$thisgallerycount = 0;
	$showgallery = ('<div id="youtube_gallery_'.$youtube_gallery_ID.'" class="youtube_gallery"><div class="youtube_gallery_center">'."\n");
	foreach ( $thisgallery as $link ):
		$x++;
		$thisgallerycount++;

			// get options
			if($youtubeoptions['hd']=='usehd') $ytsghd = 'hd=1'; else $ytsg = 'hd=0';
			if($youtubeoptions['start']=='autoplay') $ytsgstart = 'autoplay=1'; else $ytsgstart = 'autoplay=0';
			if($youtubeoptions['related']=='dontshow') $ytsgrel = 'rel=0'; else $ytsgrel = 'rel=1';

			/* This link is YouTube */
			if($link['source']=='youtube') { 
				$videoID = yotube_gallery_getYouTubeIdFromURL($link['url']);
				
				if(!isset($youtubeoptions['api']))
				$videodata = yotube_gallery_getYouTubeDataFromID($videoID);

				if( isset($videodata) && $videodata != 'error' ) {
					$autotitle = $videodata->title;
				}

				$videoembedlink = 'http://www.youtube.com/embed/'.$videoID.'?'.$ytsgstart.'&'.$ytsghd.'&'.$ytsgrel;
				if($youtubeoptions['timthumb']=='off')
					$videothumb = 'http://img.youtube.com/vi/'.$videoID.'/0.jpg';
				else
					$videothumb = get_bloginfo('url').'/wp-content/plugins/youtube-simplegallery/scripts/timthumb.php?src=http://img.youtube.com/vi/'.$videoID.'/0.jpg&w=480&h=270&zc=1';
			}

			/* This link is Vimeo */
			elseif($link['source']=='vimeo') { 
				$videoID = str_replace('http://vimeo.com/', '', $link['url']);
				$videodata = yotube_gallery_getVimeoDataFromID($videoID);
				if( isset($videodata) && $videodata != 'error' ) {
					$videoembedlink = 'http://player.vimeo.com/video/'.$videoID.'?'.$ytsgstart;
					if($youtubeoptions['timthumb']=='off')
						$videothumb = trim($videodata['thumbnail_large']);
					else
						$videothumb = get_bloginfo('url').'/wp-content/plugins/youtube-simplegallery/scripts/timthumb.php?src='.trim($videodata['thumbnail_large']).'&w=480&h=270&zc=1';
					$autotitle = $videodata['title'];
				}
			}

			// IF ERROR; OUTPUT ERROR MESSAGE
			if($videodata == 'error') {
				if( !isset($youtubeoptions['error']) ) {
					$showgallery .= '<div class="youtube_gallery_item"><div class="youtube_gallery_error"><p><strong>ERROR!</strong> <a href="'.$link['url'].'" target="_blank">'.$link['url'].'</a> does not seem to be a valid video. Please verify the URL.</p></div></div>'."\r\n\r\n";
					if(isset($cols)) // if cols, output break
						if($thisgallerycount%$cols==0) $showgallery .= '<br clear="all" style="clear: both;" />';
				}
				else {
					$thisgallerycount--;
					$x--;
				}
				continue;
			}

			// get caption if exists
			if( isset($autotitle) && $autotitles == 'fetch' ) $caption = $autotitle;
			if( $autotitles != 'fetch' ) $caption = null;
			if(isset($link['caption'])) $caption = strip_tags($link['caption']);
			
				// START OUTPUT
				$showgallery .= '<div id="youtube_gallery_item_'.$x.'" class="youtube_gallery_item">'."\n";

				// if title above
				if($youtubeoptions['title'] == 'above' && $caption ) $showgallery .= ('<div class="youtube_gallery_caption">'.strip_tags($caption).'</div>');

				$showgallery .= '<div class="youtube_gallery_player">';

				// if use shadowbox
				if($youtubeoptions['thickbox'] == 'shadowbox') 
					$showgallery .= '<a rel="shadowbox[Mixed];width='.$youtubeoptions['width'].';height='.$youtubeoptions['height'].';" href="'.$videoembedlink.'" title="'.strip_tags($caption).'">';

				// if use fancybox
				elseif($youtubeoptions['thickbox'] == 'fancybox') 
					$showgallery .= '<a class="fancybox iframe" href="'.$videoembedlink.'" title="'.strip_tags($caption).'">';

				// if use thickbox
				elseif($youtubeoptions['thickbox'] == 'thickbox') 
					$showgallery .= '<a class="thickbox" href="'.$videoembedlink.'&KeepThis=true&TB_iframe=true&height='.$youtubeoptions['height'].'&width='.$youtubeoptions['width'].'?'.$ytsgstart.'&'.$ytsghd.'&'.$ytsgrel.'" title="'.strip_tags($caption).'">';

				// if go to youtube.com
				elseif($youtubeoptions['thickbox'] == 'none') {
					$showgallery .= '<a href="http://www.youtube.com/watch?v='.str_replace('<br />', '', $videoID).'"';
					if($youtubeoptions['openlinks']) $showgallery .= ' target="_blank"';
					$showgallery .= '>';
				}

				// if add play btn
				if($youtubeoptions['pb'] == 'usepb') $showgallery .= '<img src="'.get_bloginfo('wpurl').'/wp-content/plugins/youtube-simplegallery/img/play.png" alt=" " class="ytsg_play" border="0" />';

				// output thumb
				$showgallery .= '<img src="'.$videothumb.'" border="0"></a><br />';

				// if title below
				if($youtubeoptions['title'] == 'below' && isset($caption) ) $showgallery .= ('<div class="youtube_gallery_caption">'.strip_tags($caption).'</div>');

				// close divs
				$showgallery .= '</div>';
				$showgallery .='</div>'."\r\n\r\n";


			if(isset($cols)) // if cols, output break
				if($thisgallerycount%$cols==0) $showgallery .= '<br clear="all" style="clear: both;" />';
				
			$caption = null;

	endforeach;
	$showgallery .= '<div class="youtube_gallery_divider"></div>'."\r\n";
	$showgallery .= ('</div></div>'."\r\n\r\n");
	$showgallery .= '<div class="youtube_gallery_divider"></div>'."\r\n\r\n";

	if($youtubeoptions['css'] == 'usecss') {

		$thumbwidth = $youtubeoptions['thumbwidth'];
		if(isset($atts['thumbwidth'])) $thumbwidth = $atts['thumbwidth'];

		if($youtubeoptions['jq']=='usejq') $align = 'display: inline-block';
		else $align = 'display: block';

		// COMPUTE AND OUTPUT GALLERY SPECIFIC CSS 
		$playwidth = round(($thumbwidth/2.5),0);
		$thumbheight = round(((($thumbwidth)*.5625)),0);
	//	$thumbheight--;
		$playleft = round((($thumbwidth-$playwidth)/2),0);
		$playtop = round((($thumbheight-$playwidth)/2),0);

			if(!isset($_SESSION['youtube_gallery_'.$youtube_gallery_ID])) {
				$_SESSION['youtube_gallery_'.$youtube_gallery_ID] = '#youtube_gallery_'.$youtube_gallery_ID.' .youtube_gallery_center { '.$align.'; } '."\r\n";
				$_SESSION['youtube_gallery_'.$youtube_gallery_ID] .= '#youtube_gallery_'.$youtube_gallery_ID.' .youtube_gallery_item { width: '.$thumbwidth.'px !important; } '."\r\n"; 
				$_SESSION['youtube_gallery_'.$youtube_gallery_ID] .= '#youtube_gallery_'.$youtube_gallery_ID.' .youtube_gallery_caption { '.str_replace("\n", ' ', $youtubeoptions['titlecss']).' } '."\r\n";
				$_SESSION['youtube_gallery_'.$youtube_gallery_ID] .= '#youtube_gallery_'.$youtube_gallery_ID.' .youtube_gallery_caption { width: '.($thumbwidth-20).'px; padding: 0 10px; } '."\r\n";
				$_SESSION['youtube_gallery_'.$youtube_gallery_ID] .= '#youtube_gallery_'.$youtube_gallery_ID.' .youtube_gallery_item .ytsg_play { width: '.$playwidth.'px; height: '.$playwidth.'px; left: '.$playleft.'px; top: '.$playtop.'px; }'."\r\n";
			}
	}

	$youtube_gallery_count = $x;
	return $showgallery;
}


add_shortcode('youtubegallery', 'show_youtubegallery');
add_shortcode('youtubeuserfeed', 'show_youtubeuserfeed');
add_shortcode('vimeogallery', 'show_youtubegallery');
add_shortcode('vimeouserfeed', 'show_youtubeuserfeed');
