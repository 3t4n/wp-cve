<?php
if ( absint($instance['option']['layout']) ) {
	if (function_exists('siteorigin_panels_render')) {
		echo siteorigin_panels_render( absint($instance['option']['layout']) );
	}
}
?>
