<?php
/**
* Access Font Squirrel API to fetch fonts list and download font kits
* TODO : cache !
*/
class FontSquirrelAPI {
	private $apiurl = 'http://www.fontsquirrel.com/api/';

	/**
	* The local directory to store downloaded fonts
	*/
	private $root_font_dir;

	public function __construct( $root ){
		$this->root_font_dir = $root;
	}

	/**
	* Get fonts classifications from Font Squirrel API
	*/
	public function list_classifications($views){
		$response = wp_remote_get( $this->apiurl . 'classifications' );
		if (!is_wp_error($response)){
			$classifications = json_decode($response['body']);
			foreach($classifications as $classification) {
				$class = (isset($_REQUEST['classification']) && $_REQUEST['classification'] == $classification->name) ? 'class="current"' : '';
				$views[$classification->name] = "<a href='edit.php?post_type=font&page=search-fonts&classification={$classification->name}' $class>".str_replace('%20', ' ', $classification->name)." <span class='count'>({$classification->count})</span></a>";
			}
		}
		return $views;
	}

	/**
	* Get font families form Font Squirrel API
	*/
	public function list_families($name = 'all') {
		$r = wp_remote_get( $this->apiurl . "fontlist/$name" );
		if ( is_wp_error($r) ) {
			return array();
		} else {
			return json_decode($r['body']);
		}
	}

	/**
	* Get font family preview
	*/
	public function get_preview_image($family_urlname){
		$r = wp_remote_get( $this->apiurl .  "familyinfo/$family_urlname" );
		if (!is_wp_error($r)){
			$details = json_decode($r['body']);
			return $details[0]->listing_image;
		}
	}

	public function install_font( $post_title, $post ){
		if( $post->post_type == 'font' && isset($_REQUEST['family']) ){
			$family = $_REQUEST['family'];
			$r = wp_remote_get( $this->apiurl . "familyinfo/$family" );
			if (!is_wp_error($r)){
				$details = json_decode($r['body']);
				$post_title = $details[0]->family_name;
				//$dir = WP_CONTENT_DIR . "/fonts/$family";
				$dir = $this->root_font_dir . "/$family";
				//$subdir_name = strtolower(str_replace($details[0]->style_name, '', $details[0]->fontface_name));

				// save family
				add_post_meta($post->ID, 'font-family', $family);

				if( !is_dir( $dir ) ) {
					mkdir( $dir );
					// download font-face kit
					wp_remote_get("http://www.fontsquirrel.com/fontfacekit/$family", array(
						'stream' => true,
						'filename' => $dir . "/font-face-kit.zip",
					));

					// look at font files
					$zip = new ZipArchive;
					$res = $zip->open( $dir . "/font-face-kit.zip" );
					if ($res === TRUE) {
						// get the stylesheet
						$stylesheet = $zip->getFromIndex($zip->locateName('stylesheet.css', ZipArchive::FL_NODIR));
						$fp = fopen("$dir/stylesheet.css", 'w');
						fwrite($fp, $stylesheet);
						fclose($fp);

						// get the font files
						preg_match_all("/([^']+webfont\.(?:eot|woff|ttf|svg))/", $stylesheet, $matches);
						$fontfiles = array();
						foreach(array_unique($matches[1]) as $fontfile){
							$i = $zip->locateName($fontfile, ZipArchive::FL_NODIR);
							$fp = fopen("$dir/$fontfile", 'w');
							fwrite($fp, $zip->getFromIndex($i));
							fclose($fp);
						}

						// get the font name
						preg_match("/font-family: '([^']+)';/", $stylesheet, $matches);
						add_post_meta($post->ID, 'font-name', $matches[1]);

						$zip->close();
					}

					// download samples
					wp_remote_get($details[0]->listing_image, array(
						'stream' => true,
						'filename' => $dir . "/listing_image.png",
					));
					wp_remote_get($details[0]->sample_image, array(
						'stream' => true,
						'filename' => $dir . "/sample_image.png",
					));
					wp_remote_get(str_replace('sp-720', 'sa-720x300', $details[0]->sample_image), array(
						'stream' => true,
						'filename' => $dir . "/sample_alphabet.png",
					));
					wp_remote_get(str_replace('sp-720', 'para-128x200-9', $details[0]->sample_image), array(
						'stream' => true,
						'filename' => $dir . "/sample_paragraph_9.png",
					));
					wp_remote_get(str_replace('sp-720', 'para-128x200-10', $details[0]->sample_image), array(
						'stream' => true,
						'filename' => $dir . "/sample_paragraph_10.png",
					));
					wp_remote_get(str_replace('sp-720', 'para-202x200-12', $details[0]->sample_image), array(
						'stream' => true,
						'filename' => $dir . "/sample_paragraph_12.png",
					));
					wp_remote_get(str_replace('sp-720', 'para-202x200-16', $details[0]->sample_image), array(
						'stream' => true,
						'filename' => $dir . "/sample_paragraph_16.png",
					));
				}

			}
		}
		return $post_title;
	}

	public function remove_font( $post_id ){
		$post = get_post( $post_id );
		if( $post->post_type == 'font' && $family = get_post_meta($post_id, 'font-family', true) ) {
			//$dir = WP_CONTENT_DIR . "/fonts/$family";
			$dir = $this->root_font_dir . "/$family";
			array_map('unlink', glob("$dir/*"));
			rmdir($dir);
		}
	}
}
