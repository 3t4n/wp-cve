<?php 

namespace app\init;

/* Exit if accessed directly. */
if (!defined('ABSPATH')) {
	exit;
}

class Sitemap {	
	/**
	 * Returns permalink of post id
	 *
	 * @since  2.0.14
	 */
	protected function getPermalinkFromId($id) {
		$post_status = get_post_status($id);
		$post_type = get_post_type_object(get_post_type($id));

		// Don't link if item is private and user does't have capability to read it.
		if ($post_status === 'private' && $post_type !== null && !current_user_can($post_type->cap->read_private_posts)) {
			return '';
		}

		$url = get_permalink($id);
		if ($url === false) {
			return '';
		}

		return $url;
	}
	
	/**
	 * Gets posts/pages and builds a basic sitemap. 
	 *
	 * @since 2.0.14
	 */
	public function buildSitemap() {
		global $wpdb;
		
		$xmlString = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';
		
		$postTypesQrStr = null;
		$postTypes = get_option('sseo_sitemap_post_types');
		if (is_array($postTypes)) {
			foreach($postTypes as $postType) {
				$postTypesQrStr .= " OR p.post_type='".$postType."'";
			}
		}
		
		$qs = "SELECT
			p.ID,
			p.post_author,
			p.post_status,
			p.post_name,
			p.post_parent,
			p.post_type,
			p.post_date,
			p.post_date_gmt,
			p.post_modified,
			p.post_modified_gmt,
			p.comment_count 
		FROM
			{$wpdb->posts} p
		WHERE
			p.post_password = ''
			AND p.post_status = 'publish'";
		
		if (!empty($postTypesQrStr)) {
			$qs .= " AND (".substr($postTypesQrStr, 4).")";
		}

		$qs .= " ORDER BY p.post_date_gmt DESC";

		$posts = $wpdb->get_results($qs);
		
		foreach($posts as $post) {
			$permalink = $this->getPermalinkFromId($post->ID);
			if (empty($permalink)) {
				continue;
			}
			$xmlString .= '<url>
			<loc>'.htmlspecialchars($permalink).'</loc>
			<lastmod>'.date('c', strtotime($post->post_date_gmt)).'</lastmod>
			<priority>0.80</priority>
			</url>';
		}
		
		$xmlString .= '</urlset>';

		@unlink(ABSPATH.'sitemap.xml');
		$file = fopen(ABSPATH."sitemap.xml", "w");
		fwrite($file, $xmlString);
		fclose($file);		
	}
}

?>