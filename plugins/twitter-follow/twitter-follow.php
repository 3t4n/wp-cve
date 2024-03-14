<?php
/*
Plugin Name:  Twitter Follow Button
Plugin URI:   http://pleer.co.uk/wordpress/plugins/twitter-follow-button/
Description:  Quickly adds the Twitter follow button. Can be easily implemented into your page, post or theme template
Version:      0.1
Author:       Alex Moss
Author URI:   http://alex-moss.co.uk/
Contributors: pleer

Copyright (C) 2010-2010, Alex Moss
All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
Neither the name of Alex Moss or pleer nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.
THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

*/

function twitterfollowbutton($atts) {
    extract(shortcode_atts(array(
		"username" => 'alexmoss',
		"scheme" => 'light',
		"count" => 'yes',
		"lang" => 'en',
    ), $atts));
	if ($scheme == "dark") { $tfscheme = " data-button=\"grey\" data-text-color=\"#FFFFFF\" data-link-color=\"#00AEFF\""; }
	if ($count == "no") { $tfcount = " data-show-count=\"false\""; }
	if ($lang != "en") { $tflang = "  data-lang=\"".$lang."\""; }
	$followbutton = "<!-- WordPress Follow Button Shortcode for WordPress: http://pleer.co.uk/wordpress/plugins/twitter-follow-button/ -->\n
	<a href=\"http://twitter.com/".$username."\" class=\"twitter-follow-button\" rel=\"external nofollow\"".$tfscheme.$tfcount.$tflang.">Follow @".$username."</a>\n
	<script src=\"http://platform.twitter.com/widgets.js\" type=\"text/javascript\"></script>";
		return $followbutton;
	}
add_filter('widget_text', 'do_shortcode');
add_shortcode('twitter-follow', 'twitterfollowbutton');
?>