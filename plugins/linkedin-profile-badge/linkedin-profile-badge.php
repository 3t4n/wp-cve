<?php
/*
Plugin Name:  Linkedin Profile Badge
Plugin URI:   http://3doordigital.com/wordpress/plugins/linkedin-profile-badge/?utm_source=WordPress&utm_medium=Admin&utm_campaign=Linkedin%2BProfile%2BBadge
Description:  This plugin lets you easily add the Linkedin Profile badge to your WordPress blog via a shortcode. Simply install the plugin and insert the shortcode where you want. You can also hardcode the shortcode into your theme templates.
Version:      1.0
Author: Alex Moss
Author URI: http://alex-moss.co.uk/
License: GPL v3

Copyright (C) 2010-2010, Alex Moss - alex@3doordigital.com
All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
Neither the name of Alex Moss or pleer nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.
THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

*/
if ( is_admin() && ( !defined( 'DOING_AJAX' ) || !DOING_AJAX ) )
	require 'class-admin.php';
else
	require 'class-frontend.php';

// Add settings link on plugin page
function libadge_link($links) {
  $settings_link = '<a href="options-general.php?page=linkedinbadge">Settings</a>';
  array_unshift($links, $settings_link);
  return $links;
}

$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'libadge_link' );
?>