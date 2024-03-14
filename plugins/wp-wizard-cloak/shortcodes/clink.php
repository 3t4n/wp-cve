<?php 
/**
 * Cloaked link shortcode
 * @author Pavel Kulbakin <p.kulbakin@gmail.com>
 */
class PMLC_Clink extends PMLC_Controller {
	
	public function index($args = array(), $content = '') {
		$link = new PMLC_Link_Record();
		
		if ( ! empty($args['id'])) {
			$ids = preg_split('%[|& ]+%', $args['id'], -1, PREG_SPLIT_NO_EMPTY);
			if ($ids) {
				do { // find 1st existing link to use as main one
					$id = array_shift($ids);
				} while (($link->getById($id)->isEmpty() and $link->getBySlug($id)->isEmpty() or '' != $link->preset) and $ids);
				
				if ( ! $link->isEmpty() and '' == $link->preset) { // main link found
					$content or $content = $link->name; // use link name as anchor by default
					$sub_id = NULL;
					if (isset($args['subid'])) {
						$sub_id = $args['subid'];
					}
					echo '<a href="' . esc_url($link->getUrl($sub_id)) . '"';
					if ($ids) { // there are additional links left
						echo ' onclick="';
						$addonLink = new PMLC_Link_Record();
						foreach ($ids as $id) { // output additional links
							if ( ! $addonLink->getById($id)->isEmpty() or ! $addonLink->getBySlug($id)->isEmpty())
							echo "window.open('" . addslashes($addonLink->getUrl($sub_id)) . "');";
						}
						echo '"';
					}
					unset($args['id'], $args['subid']); // output the rest of paramters as attributes of <a> tag except id and subid
					foreach ($args as $attr => $val) {
						echo ' ' . $attr . '="' . esc_attr($val) . '"';
					}
					echo '>' . $content . '</a>';
				}
			}
		}
	}
}