<?php
defined( 'ABSPATH' ) || exit;

function yahman_addons_blog_card($the_content,$option) {

	




	
	$pattern = '{<p><a(.*?)href=[\'"]([^\'"]+)[\'"](.*?)>([^\'"]+)</a></p>}';

	
	if(!preg_match_all($pattern,$the_content,$match_url)){
		return $the_content;
	}

	$blog_local = isset($option['blogcard']['internal']) ? true: false;
	$blog_external = isset($option['blogcard']['external']) ? true: false;

	$blogcard['width'] = 3;
	$blogcard['height'] = 2;
	if(!YAHMAN_ADDONS_TEMPLATE){
		add_action( 'wp_footer', 'yahman_addons_enqueue_style_blog_card');
	}else{
		$blogcard['template'] = get_template();
		if( $blogcard['template'] === 'laid-back'){
			$blogcard['width'] = 512;
			$blogcard['height'] = 268;
		}

	}

	
	require_once YAHMAN_ADDONS_DIR . 'inc/get_thumbnail.php';

	$no_image = !empty($option['other']['no_image']) ? $option['other']['no_image'] : YAHMAN_ADDONS_URI . 'assets/images/no_image.png';
	
	$match_count = count($match_url[1]);
	$i = 0;


	
	
	
	
	

	
	while($i < $match_count){

		
		if($match_url[2][$i] !== $match_url[4][$i]){
			$i++;
			continue;
		}

		$bc = array();

		$bc['read_more'] = '';
		$bc['justify_content'] = 'fe';
		$bc['cache_favicon'] = '';
		$bc['wordpress'] = array();
		$bc['wordpress_dom'] = '';
		$bc['no_image'] = $no_image;


		
		if(strpos($match_url[2][$i],home_url()) !== false){
			
			if ( !$blog_local ) {
				
				$i++;continue;
			}

			$bc['post_id'] = url_to_postid($match_url[2][$i]);

			if($bc['post_id'] == 0){
				
				$i++;continue;
			}

			
			$bc['link_option'] = $match_url[1][$i].' '.$match_url[3][$i];
			if($bc['link_option'] == ' ')$bc['link_option'] = '';

			$bc = yahman_addons_blog_card_local($match_url,$bc);

			

		}else{

			if ( !$blog_external ) {
				
				$i++;continue;
			}



			
			$external_cache = $tags = array();
			$old_time = $diff_time = null;

			
			$has_cache = false;


			

			
			$cache_name = str_replace(array('http://','https://','www.','.','-','/'),array('','','','_','_','_'),$match_url[2][$i]);

			$external_cache = get_option('yahman_addons_external_cache');

			
			if(isset($external_cache[$cache_name]['update_time'])){
				$old_time = $external_cache[$cache_name]['update_time'];

				

				$diff_time = ( time() - $old_time) / ( 60 * 60 * 24);

				$has_cache = ( $diff_time > 7 ? false : true ) ;
			}

			
			if ( $has_cache ){

				$bc['title'] = esc_html($external_cache[$cache_name]['title']);

				$bc['description'] = esc_html($external_cache[$cache_name]['description']);
				//$bc['link_url'] = esc_url($external_cache[$cache_name]['link_url']);

				$bc['cache_favicon'] = esc_url($external_cache[$cache_name]['favicon']);

				$bc['img_source'] = esc_url($external_cache[$cache_name]['cache_image']);
				if($bc['img_source'] === ''){
					$bc['img_source'] = esc_url($bc['no_image']);
				}

				if( isset($external_cache[$cache_name]['wordpress']) )
					$bc['wordpress'] = $external_cache[$cache_name]['wordpress'];


			}else{

				

				//require_once ABSPATH . 'wp-load.php';

				$external_data = wp_remote_get( $match_url[2][$i] , array( 'timeout' => 30 , 'user-agent'  => 'Googlebot'  ) );//

				
				if ( !is_array($external_data) || (is_wp_error( $external_data ) && $external_data['response']['code'] !== 200) ) {
					$external_data = wp_remote_get( $match_url[2][$i] , array( 'timeout' => 30 , 'user-agent'  => ''  ) );//
					if ( !is_array($external_data) || (is_wp_error( $external_data ) && $external_data['response']['code'] !== 200) ) {
						$i++;continue;
					}
				}

				$bc = yahman_addons_blog_card_external($match_url[2][$i],$bc,$external_data,$cache_name);

			}

			$bc['link_url'] = $match_url[2][$i];
			$bc['link_option'] = $match_url[1][$i].' '.$match_url[3][$i];

			if($bc['link_option'] === ' ')$bc['link_option'] = '';

			if($bc['cache_favicon'] !== '')
				$bc['cache_favicon'] = '<img src="'.esc_url( $bc['cache_favicon'] ).'" alt="'.$bc['title'].'" height="16" width="16" />';

		}

		if( isset($bc['wordpress']['download'] ) ){

			$bc['wordpress_dom'] = '<div class="bc_wp mb_M">';

			if($bc['wordpress']['rating_count'] !== 0){

				$star_icon = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="20" height="20" viewBox="0 0 20 20"><polygon points="13.1,6.6 20,7.5 15.1,12.4 16.5,19.3 10.2,16.2 4.1,19.6 5.1,12.7 0,7.9 6.9,6.8 9.8,0.4"/></svg>';
				$star_icon_gray = str_replace('polygon' , 'polygon fill="#999"' ,$star_icon);
				$star_icon_yellow = str_replace('polygon' , 'polygon fill="#f90"' ,$star_icon);
				$star_rating = ( (float)$bc['wordpress']['rating_value'] / 5 ) * 100;

				$bc['wordpress_dom'] .= '<div class="bc_rating f_box ai_c mb_M"><div class="bc_rating_wrap relative" style="width:120px;height:20px;">
				<div class="bc_rating_top absolute of_h" style="width:'.$star_rating.'%;white-space:nowrap;">'.$star_icon_yellow.'&thinsp;'.$star_icon_yellow.'&thinsp;'.$star_icon_yellow.'&thinsp;'.$star_icon_yellow.'&thinsp;'.$star_icon_yellow.'</div>
				<div class="bc_rating_bottom">'.$star_icon_gray.'&thinsp;'.$star_icon_gray.'&thinsp;'.$star_icon_gray.'&thinsp;'.$star_icon_gray.'&thinsp;'.$star_icon_gray.'</div></div><span class="fsS">('.$bc['wordpress']['rating_count'].')<span></div>';


			}

			//$bc['wordpress_dom'] .= '<div class="f_box jc_sb"><div class="fsS">Download:'.number_format($bc['wordpress']['download']).'</div><div class="fsS">Version:'.$bc['wordpress']['version'].'</div></div>';

			$bc['wordpress_dom'] .= '</div>';

		}

		$replacement = '<a href="'.esc_url($bc['link_url']).'"'.$bc['link_option'].' class="blog_card f_box ai_c mb_L h_box w100 shadow_box flow_box non_hover">
		<div class="bc_entry flex_70"><span class="bc_title line_clamp lc2 of_h fsM fw8 mb_S">'.esc_html($bc['title']).'</span><span class="bc_summary line_clamp lc2 of_h fsS mb_M">'.esc_html(strip_tags($bc['description'])).'</span>'.$bc['wordpress_dom'].'<div class="f_box ai_c jc_'.$bc['justify_content'].'">'.$bc['read_more'].'<div class="bc_info f_box ai_c fs10">'.$bc['cache_favicon'].'<span class="bc_domain">&nbsp;'.parse_url(esc_url($bc['link_url']), PHP_URL_HOST).'</span></div></div></div><div class="bc_thum flex_30 fit_box_img_wrap m0"><img class="scale_13 trans_10 tap_no" src="'.$bc['img_source'].'" alt="'.$bc['title'].'" width="'.$blogcard['width'].'" height="'.$blogcard['height'].'" /></div></a>';

		$the_content = str_replace($match_url[0][$i], $replacement, $the_content);

		$i++;
	}

	
	

	return $the_content;
}


function yahman_addons_blog_card_local($match_url,$bc) {

	$bc['read_more'] = '<div class="bc_read_more fsS">'.esc_html__( 'Read more', 'yahman-add-ons' ).'</div>';
	$bc['justify_content'] = 'sb';



	$bc['link_url'] = get_permalink( $bc['post_id'] );

	$thumurl = yahman_addons_get_thumbnail( $bc['post_id'] , 'medium' );

	if(!empty($thumurl)){
		$bc['img_source'] = esc_url( $thumurl[0] );
	}else{
		$bc['img_source'] = esc_url($bc['no_image']);
	}

	$bc['title'] = get_post( $bc['post_id'] )->post_title;

	
	
	$bc['description'] = mb_strimwidth( wp_strip_all_tags( preg_replace('{\[[^\]]+\]}s', '', get_post($bc['post_id'])->post_content) , true), 0 , 180, '&hellip;' );

	if(has_site_icon()){
		$bc['cache_favicon'] = '<img src="'.esc_url( get_site_icon_url() ).'" height="16" width="16" />';
	}else{
		$bc['cache_favicon'] = '<svg class="svg-icon" aria-hidden="true" role="img" focusable="false" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"><path class="svg_color" fill="#777" d="M20.6,13.5v7.1c0,0.5-0.4,1-1,1h-5.7v-5.7h-3.8v5.7H4.4c-0.5,0-1-0.4-1-1v-7.1c0,0,0-0.1,0-0.1L12,6.3L20.6,13.5 C20.6,13.4,20.6,13.4,20.6,13.5z M23.9,12.4L23,13.5c-0.1,0.1-0.2,0.1-0.3,0.2h0c-0.1,0-0.2,0-0.3-0.1L12,5L1.7,13.6 c-0.1,0.1-0.2,0.1-0.4,0.1c-0.1,0-0.2-0.1-0.3-0.2l-0.9-1.1c-0.2-0.2-0.1-0.5,0.1-0.7l10.7-8.9c0.6-0.5,1.6-0.5,2.3,0l3.6,3V3 c0-0.3,0.2-0.5,0.5-0.5h2.9c0.3,0,0.5,0.2,0.5,0.5V9l3.3,2.7C24,11.9,24.1,12.2,23.9,12.4z"></path></svg>';
	}

	return $bc;

}

function yahman_addons_blog_card_external($match_url,$bc,$external_data,$cache_name) {

	$external = array();
	$match = array();

	$external['html'] = mb_convert_encoding($external_data['body'], 'UTF-8', 'ASCII,JIS,UTF-7,EUC-JP,SJIS,UTF-8');



	if ( preg_match('/wordpress.org\/(themes||plugins)\/./', $match_url , $wordpress) ){

		$bc = yahman_addons_blog_card_get_wordpress_info($bc,$external['html']);

	}elseif (preg_match('/<head.*?>(.*?)<\/head>/is', $external['html'], $match_head)) {

		
		$external['head'] = str_replace( "xc2xa0", " ", $match_head[1] );

		
		
		preg_match_all('/<meta(?:(?!>).)*?property=[\'"]og:([^\'"]+)[\'"](?:(?!>).)*?>/is', $external['head'], $match['og']);
		if( !empty($match['og'][1]) ){
			foreach ($match['og'][1] as $key => $value) {
				if( !isset($tags['og:'.$value]) ){
					
					preg_match('/content=[\'"]([^\'"]+)[\'"]/', $match['og'][0][$key] , $result);
					//var_dump($result);
					if( isset($result[1]) )
						$tags['og:'.$value] = esc_attr($result[1]);
				}

			}
		}

		
		preg_match_all('/<meta(?:(?!>).)*?name=[\'"]twitter:([^\'"]+)[\'"](?:(?!>).)*?>/is', $external['head'], $match['twitter']);
		if( !empty($match['twitter'][1]) ){
			foreach ($match['twitter'][1] as $key => $value) {
				if( !isset($tags['twitter:'.$value]) ){
					
					preg_match('/content=[\'"]([^\'"]+)[\'"]/', $match['twitter'][0][$key] , $result);
					//var_dump($result);
					if( isset($result[1]) )
						$tags['twitter:'.$value] = esc_attr($result[1]);
				}

			}
		}

		
		if (isset($tags['og:title'])) {
			$bc['title'] = $tags['og:title'];
		}else{
			if( isset($tags['twitter:title']) ){
				
				$bc['title'] = $tags['twitter:title'];
			}else{
				
				$pattern = 'title';
				if (preg_match('/<'.$pattern.'.*?>(.*)<\/'.$pattern.'>/is', $external['head'], $match_title)) {
					$bc['title'] = esc_html($match_title[1]);
				}else{
					$bc['title'] = '';
				}
			}
		}

		
		if ( isset($tags['og:description']) ) {
			$bc['description'] = esc_html($tags['og:description']);
		}else{

			if( isset($tags['twitter:description']) ){
				
				$bc['description'] = $tags['twitter:description'];
			}else{
				if(preg_match('/<meta.*?name=[\'"]description[\'"].*?content=[\'"](.*?)[\'"]/is', $external['html'], $bc['description'])){
					$bc['description'] = esc_html(strip_tags($bc['description'][1]));
				}elseif(preg_match('/<p.*?>(.*?)<\/p>/is', $external['html'], $bc['description'])){
					$bc['description'] = esc_html(strip_tags($bc['description'][1]));
				}else{
					$bc['description'] = '';
				}
			}
		}

	}else{
		$filetype = $external_data['headers']['content-type'];
		if($filetype!=''){
			$filetype = substr(strrchr($filetype,"/"),1);
		}else{
			$filetype = '';
		}
		$bc['description'] = $filetype.' '. esc_html__( 'file', 'yahman-add-ons' );
		$bc['title'] = substr(strrchr($match_url,"/"),1);
	}

	
	require_once ABSPATH . 'wp-admin/includes/file.php';
	global $wp_filesystem;
	$bc['cache_favicon'] = '';

	$cache_image = '';


	if ( WP_Filesystem() ) {
		$upload_dir = wp_upload_dir();
		$dir = $upload_dir['basedir'].'/yahman_addons_cache/';
        //$dir = WP_CONTENT_DIR.'/uploads/yahman_addons_cache/';
		if ( !$wp_filesystem->is_dir($dir) ) {
			$wp_filesystem->mkdir($dir, 0777);
			$wp_filesystem->chmod($dir, 0777);
		}
		$host_url = parse_url(esc_url($match_url), PHP_URL_HOST);

        //$favicon_image = $wp_filesystem->get_contents('https://www.google.com/s2/favicons?domain='.$host_url);

		$favicon_image = wp_remote_get('https://www.google.com/s2/favicons?domain='.$host_url , array( 'timeout' => 50 ) );



		if ( ! is_wp_error( $favicon_image ) && $favicon_image['response']['code'] === 200 ) {
			$favicon_image = $favicon_image['body'];
			$bc['cache_favicon'] = $dir.$host_url.'.png';
			$wp_filesystem->put_contents($bc['cache_favicon'], $favicon_image, FS_CHMOD_FILE);
			$bc['cache_favicon'] = str_replace(WP_CONTENT_DIR, content_url(), $bc['cache_favicon']);
		}

		if ( !isset($tags['og:image']) ) {

			if( isset($tags['twitter:image']) ){
				
				$tags['og:image'] = $tags['twitter:image'];
			}else{
				
				$tags['og:image'] = yahman_addons_blog_card_get_json_image($external['html']);
			}

		}


		
		if ( isset($tags['og:image']) ) {

			$cache_image = str_replace(array('http://','https://','www.','-','/'),array('','','','_','_'),$tags['og:image']);

			$cache_image = preg_replace('/\?.*$/i', '', $dir.$cache_image);

			$path_parts = pathinfo($cache_image);

			if(!$path_parts){
				$cache_image = $cache_image.'.png';
				$extension = 'png';
			}else{
				$extension = $path_parts['extension'];
			}



            //$file_image = $wp_filesystem->get_contents($tags['og:image']);


			$file_image = wp_remote_get($tags['og:image'] , array( 'timeout' => 50 ) );

			if( is_wp_error($file_image) ){
				$file_image = wp_remote_get($tags['og:image'] , array( 'timeout' => 50 , 'sslverify' => FALSE ) );
			}

			if ( ! is_wp_error( $file_image ) && $file_image['response']['code'] === 200 ) {

				
				if( !in_array(strtolower($extension), array('jpg','png','gif','jpeg','svg','webp'), true)){
					preg_match('/jpe?g|png|gif|svg|webp/', $tags['og:image'], $matches);
					if(isset($matches[0])){
						$extension = $matches[0];
						$cache_image = $dir.$path_parts['filename'].'.'.$extension;
					}
				}

				$file_image = $file_image['body'];

				$wp_filesystem->put_contents($cache_image, $file_image, FS_CHMOD_FILE);
				$cache_image_edit = wp_get_image_editor($cache_image);
				if ( !is_wp_error($cache_image_edit) ) {
					$cache_image_edit->resize('600', '600');
					$cache_image_edit->save( $cache_image );
				}
				$cache_image = str_replace(WP_CONTENT_DIR, content_url(), $cache_image);
			}else{
				$cache_image = '';
			}

		}
	}
	

	if($cache_image !== ''){
		$bc['img_source'] = esc_url( $cache_image );
	}else{
		$bc['img_source'] = esc_url($bc['no_image']);
	}

	$external_cache = get_option('yahman_addons_external_cache');

	
	if(!is_array($external_cache)) $external_cache = array();

	$external_cache[$cache_name] = array(
		'title' => esc_html($bc['title']),
		'cache_image' => esc_url($cache_image),
		'description' => esc_html(strip_tags($bc['description'])),
		'link_url' => esc_url($match_url),
		//'link_url_option' => $bc['link_option'],
		'favicon' => esc_url($bc['cache_favicon']),
		'wordpress' => $bc['wordpress'],
		'update_time' => time(),
	);

	update_option('yahman_addons_external_cache', $external_cache);

	return $bc;

}

function yahman_addons_blog_card_get_json_image($html) {

	$dom  = new DOMDocument();
	libxml_use_internal_errors( 1 );
	$dom->loadHTML( $html );
	$xpath = new DOMXpath( $dom );
	$jsonScripts = $xpath->query( '//script[@type="application/ld+json"]' );

	$image_url = null;

	if($jsonScripts['length'] !== 0){

		foreach( $jsonScripts as $node ){
			$json = json_decode( $node->nodeValue ,true );

			
			if( isset($json[0]) ){
				$json = $json[0];
			}

			
			if( isset($json['image']) && is_array($json['image']) ){

				foreach ($json['image'] as $key => $value) {

					if( yahman_addons_blog_card_image_extension($value) ){

						$image_url = $value;
						break;
					}


				}

			}elseif( isset($json['image']) ){

				if( yahman_addons_blog_card_image_extension($json['image']) ){

					$image_url = $json['image'];
				}

			}

			if( isset($image_url) ) break;

		}
	}

	return $image_url;

}

function yahman_addons_blog_card_image_extension($url) {

	if (strpos($url, '.png') !== false) return true;
	if (strpos($url, '.jpg') !== false) return true;
	if (strpos($url, '.gif') !== false) return true;

	return false;

}


function yahman_addons_blog_card_get_wordpress_info($bc,$html) {

	$dom  = new DOMDocument();
	libxml_use_internal_errors( 1 );
	$dom->loadHTML( $html );
	$xpath = new DOMXpath( $dom );
	$jsonScripts = $xpath->query( '//script[@type="application/ld+json"]' );


	if($jsonScripts['length'] !== 0){

		foreach( $jsonScripts as $node ){
			$json = json_decode( $node->nodeValue ,true );

			
			if( isset($json[0]) ){
				$json = $json[0];
			}

			$bc['wordpress']['rating_value'] = 0;
			$bc['wordpress']['rating_count'] = 0;

			$bc['title'] = esc_html($json['name']);
			$bc['description'] = esc_html($json['description']);
			$bc['wordpress']['download'] =  esc_attr($json['interactionStatistic']['userInteractionCount']);
			if(isset($json['aggregateRating']['ratingValue']))
				$bc['wordpress']['rating_value'] = esc_attr($json['aggregateRating']['ratingValue']);
			if(isset($json['aggregateRating']['ratingCount']))
				$bc['wordpress']['rating_count'] = esc_attr($json['aggregateRating']['ratingCount']);
			$bc['wordpress']['version'] = esc_attr($json['softwareVersion']);

		}
	}

	return $bc;

}
