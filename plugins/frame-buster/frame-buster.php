<?php
/*
Plugin Name: Frame Buster
Plugin URI:  http://warfareplugins.com/frame-buster/
Description: Stop those content pirates from hijacking your site once and for all!
Version:     1.1.2
Author:      Nicholas Z. Cardot - Dustin W. Stout - Jason T. Wiser
Author URI:  http://warfareplugins.com
*/


function frameBuster() {
	echo '<script type="text/javascript">function parentIsEvil() { var html = null; try { var doc = top.location.pathname; } catch(err){ }; if(typeof doc === "undefined") { return true } else { return false }; }; if (parentIsEvil()) { top.location = self.location.href; };var url = "'.get_permalink().'";if(url.indexOf("stfi.re") != -1) { var canonical = ""; var links = document.getElementsByTagName("link"); for (var i = 0; i < links.length; i ++) { if (links[i].getAttribute("rel") === "canonical") { canonical = links[i].getAttribute("href")}}; canonical = canonical.replace("?sfr=1", "");top.location = canonical; console.log(canonical);};</script>';
}
add_action('wp_head','frameBuster',1);
