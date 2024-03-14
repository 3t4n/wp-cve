<?php
/*
Plugin Name: XML Image Sitemap
Plugin URI: http://www.ericnagel.com/2010/10/image-sitemap-for-wordpress.html
Description: Creates an image sitemap to submit to the search engines for better image rankings
Author: Eric Nagel
Version: 1.04
Author URI: http://www.ericnagel.com
License: GPL2

Copyright 2011  Eric Nagel  (email : eric@ericnagel.com)
	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
add_action ('admin_menu', 'xml_image_sitemap_generate_page');

function xml_image_sitemap_generate_page () {
	if (function_exists ('add_submenu_page'))
		add_submenu_page ('tools.php', __('XML Image Sitemap'), __('XML Image Sitemap'),
			'manage_options', 'xml-image-sitemap-generate-page', 'xml_image_sitemap_page');
}

add_action('template_redirect', 'xml_image_sitemap');

function xml_image_sitemap_page() {
	?>
	<div class="wrap">
	<h2>XML Image Sitemap</h2>

	<p>Your image sitemap is now ready at <a href="<?= get_bloginfo('url') . "/sitemap-image.xml" ?>" target="_blank"><?= get_bloginfo('url') . "/sitemap-image.xml" ?></a>.</p>

	<p>Submit your sitemap to:
		<ul>
			<li><a href="http://www.google.com/webmasters/tools/" target="_blank">Google</a></li>
			<li><a href="http://www.bing.com/toolbox/webmasters/" target="_blank">Bing</a> [<a href="http://www.bing.com/webmaster/ping.aspx?sitemap=<?= str_replace('http://', '', get_bloginfo('url')) . "/sitemap-image.xml" ?>" target="_blank">SUBMIT TO BING</a>]</li>
			<li><a href="https://siteexplorer.search.yahoo.com/submit" target="_blank">Yahoo</a></li>
		</ul></p>

	<h3>Suggestions?</h3>

	<p>E-mail: <a href="mailto:eric@ericnagel.com">eric@ericnagel.com</a><br />
		Twitter: <a href="http://www.twitter.com/esnagel">@esnagel</a></p>


	</div>
	<?php
} // ends function xml_image_sitemap_page()

function xml_image_sitemap() {

	if (!preg_match("/sitemap\-image\.xml$/", $_SERVER['REQUEST_URI'])) {
		return;
	} // ends

	global $wpdb;
	$posts = $wpdb->get_results("SELECT p1.* FROM $wpdb->posts p1
							LEFT JOIN $wpdb->posts p2 on p2.ID=p1.post_parent
							WHERE p1.post_type = 'attachment' and p2.post_status = 'publish'
							AND p1.post_mime_type like 'image%'
							ORDER BY p1.post_date desc");

	header("HTTP/1.1 200 OK");
	header("Content-Type: text/xml");
	$xml   = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
	$xml  .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";
	$xml  .= '<!-- XML Image Sitemap Version 1.04 http://www.ericnagel.com/2010/10/image-sitemap-for-wordpress.html -->' . "\n";

	$thisPostID = 0;

	foreach ($posts as $post) {
		if ($post->post_parent != 0) {
			$permalink = get_permalink($post->post_parent);
			if (strstr($permalink, '/?attachment_id=') === false) {
				if ($thisPostID != $post->post_parent) {

					if ($thisPostID != 0) {
						// Close the previous one
						$xml .= "</url>\n";
					} // ends if ($thisPostID != 0)

					$xml .= "<url>\n";
					$xml .= "\t<loc>$permalink</loc>\n";

					$thisPostID = $post->post_parent;
				} // ends if ($thisPostID != $post->post_parent)

				$xml .= "\t<image:image>\n";
				$xml .= " \t\t<image:loc>" . $post->guid . "</image:loc>\n";
				if (!empty($post->post_excerpt)) {
					$xml .= " \t\t<image:caption>" . htmlspecialchars($post->post_excerpt, ENT_COMPAT, 'UTF-8') . "</image:caption>\n";
				} // ends if (!empty($post->post_excerpt))
				elseif (!empty($post->post_title)) {
					$xml .= " \t\t<image:caption>" . htmlspecialchars($post->post_title, ENT_COMPAT, 'UTF-8') . "</image:caption>\n";
				} // ends if (!empty($post->post_title))
				$xml .= "\t</image:image>\n";
			} // ends if (!$permalink)
		} // ends if ($post->post_parent != 0)
	} // ends foreach ($posts as $post)

	$xml .= "</url>\n";
	$xml .= "\n</urlset>";
	echo("$xml");
	exit();
} // ends function xml_image_sitemap()
?>