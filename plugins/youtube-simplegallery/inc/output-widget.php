<?php

function widget_youtubegallery( $youtubelinks = null, $atts = array() ) {

// CLEAN UP OEMBED
if (stripos($youtubelinks, "<p>") !== false) {
	$embededmess = explode('<p>', $youtubelinks);

	foreach($embededmess as $line):
		if (stripos($line, "</iframe>") !== false) {
			$removeoembed = getYTSGAttribute('src', $line);
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

?>