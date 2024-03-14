<?php
/*
Plugin Name: Complete Image Sitemap
Plugin URI: http://remark.no/complete-image-sitemap
Description: The Complete Image Sitemap plugin will generate an XML Sitemap for all images, including Woocommerce products. Open the <a href="tools.php?page=complete-image-sitemap">Settings</a> page to create your image sitemap.
Author: Herbert van-Vliet
Version: 1.1.1
Tested up to: 4.8
Author URI: http://remark.no
*/

add_action('admin_menu', 'remark_image_sitemap_page');

$remark_licenses = array(
	'None' => '',
	'Public domain' => 'https://creativecommons.org/publicdomain/zero/1.0/',
	'Attribution alone' => 'https://creativecommons.org/licenses/by/4.0',
	'Attribution + ShareAlike' => 'https://creativecommons.org/licenses/by-sa/4.0',
	'Attribution + Noncommercial' => 'https://creativecommons.org/licenses/by-nd/4.0',
	'Attribution + NoDerivatives' => 'https://creativecommons.org/licenses/by-nc/4.0',
	'Attribution + Noncommercial + ShareAlike' => 'https://creativecommons.org/licenses/by-nc-sa/4.0',
	'Attribution + Noncommercial + NoDerivatives' => 'https://creativecommons.org/licenses/by-nc-nd/4.0',
);

function remark_image_sitemap_page() {
	if(function_exists('add_submenu_page')) {
		add_submenu_page(
			'tools.php',
			__('Complete Image Sitemap'),
			__('Complete Image Sitemap'),
			'manage_options',
			'complete-image-sitemap',
			'remark_image_sitemap_generate'
		);
	}
}

function remark_is_file_writable($filename) {
	if(!is_writable($filename)) {
		if(!@chmod($filename, 0666)) {
			$dirname = dirname($filename);
			if(!is_writable($dirname)) {
				if(!@chmod($dirname, 0666)) {
					return false;
				}
			}
		}
	}
	return true;
}

function remark_escape_xml_entities($xml) {
	return str_replace(
		array('&','<','>','\'','"'),
		array('&amp;','&lt;','&gt;','&apos;','&quot;'),
		$xml
	);
}

function remark_image_sitemap_generate() {
	echo '<div class="wrap">';

	echo '<h2>Complete Image Sitemap</h2>';
	echo '<p>' .__('Image sitemaps can be used to inform search engines about the images on your website.') .'</p>';
	echo '<p>' .__('You can create or re-create the sitemap file by clicking the following button.') .'</p>';
	echo '<p>' .__('If you want you can select and apply a (system wide) license to all images. Read more on:') .' <a href="https://creativecommons.org/" target="_blank" rel="nofollow">Creative Commons</a>.</p>';

	echo '<form method="post" action="">';
	echo __('License for all images: ') .'<br>';
	echo '<select name="license">';
	global $remark_licenses;
	foreach( $remark_licenses as $key=>$value ) {
		$selected = ($_REQUEST['license'] == $key ? ' selected="selected"':'');
		echo '<option value="' .esc_html($key) .'"' .$selected .'>' .esc_html($key) .'</option>';
	}
	echo '</select><br>';
	echo '<input type="submit" name="submit" value="' .__('Generate a Complete Image Sitemap') .'" />';
	wp_nonce_field( 'remark_image_sitemap_generate', 'nonce' );
	echo '</form>';

	echo '<p>' .__('After the sitemap has been created, you can submit it using <a href="https://www.google.com/webmasters/tools/" target="_blank">Google Webmaster Tools</a>.') .'</p>';
	echo '</div>';

	if(isset($_POST['submit'])) {
		if( wp_verify_nonce( $_POST['nonce'], 'remark_image_sitemap_generate' )) {
			switch( remark_image_sitemap_create()) {
			case -1:
				// An error occurred
				echo '<div class="error"><h2>' .__('Error') .'</h2>';
				echo '<p>' .__('The image sitemap could not be saved to your web root folder.') .'</p>';
				echo '<p>' .__('Please make sure the folder has the appropriate permissions.') .'</p>';
				echo '<p>' .__('You can use chmod or use an FTP application to change the permission of the folder to 0666.') .'</p>';
				echo '<p>' .__('If you are not able to change those permissions, contact your web host.') .'</p>';
				echo '</div>';
				break;

			case 0:
				// Nothing to do
				echo '<div class="error"><h2>' .__('Nothing to do') .'</h2>';
				echo '<p>' .__('No image sitemap was saved because no images could be found.') .'</p>';
				echo '<p>' .__('Please not this plugin does not automatically include any attachments just because they were uploaded to the site.') .'</p>';
				echo '<p>' .__('If you believe this is in error, please send an email to <a href="mailto:info@remark.no">info@remark.no</a>') .'</p>';
				echo '</div>';
				break;

			default:
				// A bunch of images were written to the sitemap file
				// 20170620 - HVV: Changed acc to https://wordpress.org/support/topic/bug-found-and-used-editor-to-fix/
				$url = parse_url(get_bloginfo('url'));
				$url = $url['scheme'].'://'.$url['host'].'/sitemap-images.xml';
				echo '<div>';
				echo '<p>' .__('The image sitemap was generated successfully.') .'</p>';
				echo '<p>' .__('You can find the file here:') .' <a href="' .$url .'" target="_blank">' .$url .'</a></p>';
				echo '<p>' .__('You can let Google know about the updated sitemap:') .' <a href="http://www.google.com/webmasters/sitemaps/ping?sitemap=' .urlencode($url) .'" target="_blank">' .__('Ping Google') .'</a></p>';
				echo '</div>';
			}
			exit;
		}
	}
}

function remark_image_sitemap_create() {
	global $wpdb;
	$posts = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_type<>'revision' AND post_status IN ('publish','inherit')");
	$thumbs = $wpdb->get_results("
		SELECT p.ID,i.guid FROM $wpdb->posts p
		INNER JOIN $wpdb->postmeta pm ON p.id=pm.post_id
		INNER JOIN $wpdb->posts i ON pm.meta_value=i.id
		WHERE p.post_type<>'revision' AND p.post_status='publish' AND pm.meta_key='_thumbnail_id'
	");

	if(empty($posts) && empty($thumbs)) {
		// Nothing much to do
		return 0;
	} else {
		$images = array();
		foreach($posts as $post) {
			if($post->post_type == 'attachment') {
				if($post->post_parent != 0) {
					$images[$post->post_parent][$post->guid] = 1;
				}
			} elseif(preg_match_all('/img src=("|\')([^"\']+)("|\')/ui',$post->post_content,$matches,PREG_SET_ORDER)) {
				foreach($matches as $match) {
					$imgurl = $match[2];
					if(strtolower(substr($imgurl,0,4)) != 'http') {
						$imgurl = get_site_url() .$imgurl;
					}
					$images[$post->ID][$imgurl] = 1;
				}
			}
		}
		foreach($thumbs as $post) {
			$images[$post->ID][$post->guid] = 1;
		}

		if( count($images) == 0 ) {
			// Looked promising but no cigar after all
			return 0;
		} else {
			$xml  = '<?xml version="1.0" encoding="UTF-8"?>' ."\n";
			$xml .= '<!-- Created by (http://remark.no/complete-image-sitemap/) on ' .date("F j, Y, g:i a") .'" -->' ."\n";
			$xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' ."\n";

			global $remark_licenses;
			$license = $remark_licenses[$_REQUEST['license']];
			if( $license != '' ) {
				$license = "<image:license>" .$license ."</image:license>";
			}

			foreach($images as $k=>$v) {
				unset($imgurls);
				foreach(array_keys($v) as $imgurl) {
					if(is_ssl()) {
						$imgurl = str_replace('http://','https://',$imgurl);
					} else {
						$imgurl = str_replace('https://','http://',$imgurl);
					}
					$imgurls[$imgurl] = 1;
				}
				$permalink = get_permalink($k);
				if(!empty($permalink)) {
					// 20170620: HVV - Changed acc to https://wordpress.org/support/topic/huge-but-simple-bug-affects-structure-error/
					$img = '';
					foreach( array_keys($imgurls) as $imgurl ) {
						$img .=
							"<image:image>" .
							"<image:loc>" .$imgurl ."</image:loc>" .
							$license .
							"</image:image>";
					}
					$xml .= "<url><loc>" .remark_escape_xml_entities($permalink) ."</loc>" .$img ."</url>\n";
				}
			}
			$xml .= "</urlset>";
		}
	}

	$image_sitemap_url = $_SERVER["DOCUMENT_ROOT"].'/sitemap-images.xml';
	if(remark_is_file_writable($_SERVER["DOCUMENT_ROOT"]) || remark_is_file_writable($image_sitemap_url)) {
		if(file_put_contents($image_sitemap_url, $xml)) {
			return count($images);
		}
	}

	// Return an error
	return -1;
}
?>
