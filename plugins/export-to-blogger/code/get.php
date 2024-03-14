<?php
if ( !current_user_can('export') ){
	wp_die( __('Sorry, you are not allowed to export the content of this site.', 'export-to-blogger') );
}
$e = new WP_Error();
$updated = array();
$entry = '';
$xml = '';
$downloaded = '';

if(isset($_GET["ew2bc_download"]) && ctype_xdigit($_GET["ew2bc_download"]) && is_admin() && isset($_GET["ew2bc_nonce"])){
	check_admin_referer( 'ew2bc_get_download', 'ew2bc_nonce' );
	//ctype_xdigit checked already
	$downloaded = sanitize_text_field( $_GET["ew2bc_download"] );

	$typeline = '';
	if( $_GET["type"] == "post" ){
		$args['post_type'] = 'post';
		$typeline = '<ns0:category scheme="http://schemas.google.com/g/2005#kind" term="http://schemas.google.com/blogger/2008/kind#post" />';

		if(isset($_GET["category"])){
			$category = explode( ",", sanitize_text_field( $_GET["category"] ) );
		}else{
			$category[] = "error";
		}
		//category id has to be all numeric
		if (in_array(false, array_map('is_numeric', $category)) || empty($category)) {
			$e->add( 'error', __('Selected categories are invalid.', 'export-to-blogger') );
		}else{
			$args['category__in'] = $category;
		}
	}else if( $_GET["type"] == "page" ){
		$args['post_type'] = 'page';
		$typeline = '<ns0:category scheme="http://schemas.google.com/g/2005#kind" term="http://schemas.google.com/blogger/2008/kind#page" />';
	}else{
		$args['post_type'] = sanitize_text_field( $_GET["type"] );
		if(isset($_GET["taxonomy"])){
			$taxonomies = explode( ",", sanitize_text_field( $_GET["taxonomy"] ) );
						
			//category id has to be all numeric
			if(empty($taxonomies)){
				//get all
			}else{
				$taxonomy_n = 0;
				$tax =  array('relation' => 'OR');
				foreach( $taxonomies as $taxonomy ){
					if(isset($_GET["taxonomy_".$taxonomy])){
						$category = "";
						$category = explode( ",", sanitize_text_field( $_GET["taxonomy_".$taxonomy] ) );
						//category id has to be all numeric
						if (in_array(false, array_map('is_numeric', $category)) || empty($category)) {
							//
							//$e->add( 'error', __('Selected categories are invalid.', 'export-to-blogger') );
						}else{
							$tax[] = array(
								'taxonomy' => $taxonomy,
								'field' => 'term_id',
								'terms' => $category,
								'operator' => 'IN'
							);
							$taxonomy_n ++;
						}
						

					}
				}
				if($taxonomy_n == 0){
					
				}if($taxonomy_n > 1){
					$args['tax_query'] = $tax;
				}else{
					unset($tax['relation']);
					$args['tax_query'] = $tax;
				}
				

			}
		}else{
			//all
		}
	}
	
	if( $_GET["status"] == "any" ){
		$args['post_status'] = 'any';
	}else if( $_GET["status"] == "publish" ){
		$args['post_status'] = 'publish';
	}else if( $_GET["status"] == "pending" ){
		$args['post_status'] = 'pending';
	}else if( $_GET["status"] == "draft" ){
		$args['post_status'] = 'draft';
	}else if( $_GET["status"] == "future" ){
		$args['post_status'] = 'future';
	}else if( $_GET["status"] == "private" ){
		$args['post_status'] = 'private';
	}else if( $_GET["status"] == "trash" ){
		$args['post_status'] = 'trash';
	}else{
		$e->add( 'error', __('Selected status is invalid.', 'export-to-blogger') );
	}

	$args['numberposts'] = -1;
	$args['orderby'] = 'date';
	$args['order'] = 'DESC';

	//export only when there is no error messages
	if(empty($e->get_error_messages('error'))){
		$export_posts = get_posts( $args );
		if($export_posts){
			foreach( $export_posts as $post ){
				setup_postdata( $post );
				//get ID
				$thisid = (int)$post->ID;
				
				//get title
				$title = ew2bc_esc_html($post->post_title);
				
				//get content	
				$content = ew2bc_esc_html( apply_filters( 'the_content', get_post_field( 'post_content', $thisid ) ) );
				
				
				//EOL check
				$content = str_replace( array( "\r\n", "\r", "\n" ), "", $content );
				$content = str_replace(PHP_EOL, "", $content);
				
				//create blogspot ID
				$id = 'tag:blogger.com,1999:blog-0.'.ew2bc_esc_html($post->post_type).'-'.$thisid;
				
				//get post date
				if($post->post_status == 'publish'){
					$get_post_date = get_post_time('Y-m-d', true, $thisid);
					$get_post_time = get_post_time('H:i:s', true, $thisid);
					$draft = '';
				}else if($post->post_status == 'future'){
					$get_post_date = get_post_time('Y-m-d', true, $thisid);
					$get_post_time = get_post_time('H:i:s', true, $thisid);
					$draft = '<app:control xmlns:app=\'http://purl.org/atom/app#\'><app:scheduled>yes</app:scheduled></app:control>';
				}else{
					$modified_gmt = strtotime($post->post_modified_gmt);
					$get_post_date = date( 'Y-m-d', $modified_gmt);
					$get_post_time = date( 'H:i:s', $modified_gmt);
					$draft = '<app:control xmlns:app=\'http://purl.org/atom/app#\'><app:draft>yes</app:draft></app:control>';
				}
				$date = $get_post_date."T".$get_post_time."Z";


				//create label data
				$tagline = '';
				$catline = '';
				$labelcount = '';
				if($post->post_type == "post"){
					if( isset($_GET["tag2label"]) ){
						$tags = get_the_tags($thisid);
						if(!empty($tags)){
							foreach ( $tags as $tag ) {
								if(!empty($tag->name)){
									$tagline .= '<ns0:category scheme="http://www.blogger.com/atom/ns#" term="'.ew2bc_esc_html_label($tag->name).'" />';
									$labelcount .= ew2bc_esc_html_label($tag->name).",";
								}
							}
						}
					}
					if( isset($_GET["cat2label"]) ){
						$categories = get_the_category($thisid);
						if(!empty($categories)){
							foreach ( $categories as $cat ) {
								if(!empty($cat->name)){
									$catline .= '<ns0:category scheme="http://www.blogger.com/atom/ns#" term="'.ew2bc_esc_html_label($cat->name).'" />';
									$labelcount .= ew2bc_esc_html_label($cat->name).",";
								}
							}
						}
					}
					
					//check combined length of all the labels.
					if( strlen( $labelcount ) > 199 ){
						$tagline = '';
						$catline = '';
						$e->add( 'error', __('Post ', 'export-to-blogger').'ID: '.$thisid.' - '.__('The combined length of all the labels must be under 200 characters. Please reduce categories or tags for this post. (now:', 'export-to-blogger').strlen( $labelcount ).')' );
					}
					
					
				}else if($post->post_type != "page"){
					
					//recreate blogspot ID
					$id = 'tag:blogger.com,1999:blog-0.post-'.$thisid;
					
					//check all taxonomy
					$tagline = '';
					$catline = '';
					$labelcount = '';
					
					$taxonomy_object  = get_object_taxonomies( $post->post_type, 'objects' );
					foreach ($taxonomy_object as $taxonomy_slug  => $value){
						if($value->hierarchical == 1 && isset($_GET["cat2label"])){
							//cat taxonomy to label
						    $terms = get_the_terms( $thisid, $taxonomy_slug );
						    if ( !empty( $terms ) ) {
						      foreach ( $terms as $term ) {
								$catline .= '<ns0:category scheme="http://www.blogger.com/atom/ns#" term="'.ew2bc_esc_html_label($term->name).'" />';
								$labelcount .= ew2bc_esc_html_label($term->name).",";
						      }
						    }

						}else if($value->hierarchical != 1 && isset($_GET["tag2label"])){
							//tag taxonomy to label
							$terms = get_the_terms( $thisid, $taxonomy_slug );
						    if ( !empty( $terms ) ) {
						      foreach ( $terms as $term ) {
							$tagline .= '<ns0:category scheme="http://www.blogger.com/atom/ns#" term="'.ew2bc_esc_html_label($term->name).'" />';
							$labelcount .= ew2bc_esc_html_label($term->name).",";
							  }
						    }							
						}else{

					    }

					}
					//check combined length of all the labels.
					if( strlen( $labelcount ) > 199 ){
						$tagline = '';
						$catline = '';
						$e->add( 'error', __('Post ', 'export-to-blogger').'ID: '.$thisid.' - '.__('The combined length of all the labels must be under 200 characters. Please reduce categories or tags for this post. (now:', 'export-to-blogger').strlen( $labelcount ).')' );
					}
					
				}
				
				//get author's name
				$author_name = 'author';
				
				//set up each entry
				$lines = '';
				$lines .= $typeline;
				$lines .= $catline;
				$lines .= $tagline;
				$entry .= '<ns0:entry>'.$lines.'<ns0:id>'.$id.'</ns0:id><ns0:author><ns0:name>'.$author_name.'</ns0:name></ns0:author><ns0:content type="html">'.$content.'</ns0:content><ns0:updated>'.$date.'</ns0:updated><ns0:published>'.$date.'</ns0:published>'.$draft.'<ns0:title type="html">'.$title.'</ns0:title><ns0:link href="http://www.blogger.com/" rel="self" type="application/atom+xml" /><ns0:link href="http://www.blogger.com/" rel="alternate" type="text/html" /></ns0:entry>';
			}
			//end of foreach
			wp_reset_postdata();
			//set up xml
			$xml = '<?xml version="1.0" encoding="UTF-8"?>
<ns0:feed xmlns:ns0="http://www.w3.org/2005/Atom"><ns0:link href="http://www.blogger.com/" rel="self" type="application/atom+xml" /><ns0:title>MovableType blog</ns0:title><ns0:updated>2019-01-01T00:00:00Z</ns0:updated><ns0:generator>Blogger</ns0:generator><ns0:link href="http://www.blogger.com/" rel="alternate" type="text/html" />'.$entry.'</ns0:feed>';
			//export success message if there is no error messages
			if(empty($e->get_error_messages('error'))){
				$updated[] = __('Success to exoprt XML file.', 'export-to-blogger');
			}
		}else{
			//can't find any post error
			$e->add( 'error', __('Cannot find any data to export.', 'export-to-blogger') );
		}
	}
	//set download file name. this is same as standard wordpress export function.
	$sitename = sanitize_key( get_bloginfo( 'name' ) );
	if ( ! empty( $sitename ) ) {
		$sitename .= '.';
	}
	$date = current_time( 'Y-m-d' );
	$wp_filename = $sitename . 'WordPress.' . $date . '.xml';
	$filename = apply_filters( 'export_wp_filename', $wp_filename, $sitename, $date );
	
	//set header information
	header( 'Content-Description: File Transfer' );
	header( 'Content-Disposition: attachment; filename=' . $filename );
	//header( 'Content-Disposition: inline;' ); //for debug only
	header( 'Content-Type: text/xml; charset=UTF-8', true );

	//set error or export success messages
	set_transient( 'ew2bc-errors', $e->get_error_messages(), 10 );
	set_transient( 'ew2bc-updated', $updated, 10 );
	
	//set export success cookie
	setcookie('ew2bc_downloaded', esc_html( $downloaded ), 0, '/');
	
	//print all xml data now
	echo $xml;

}else{
	//ctype_xdigit check or nonce check failed
	wp_die( __('Sorry, you are not allowed to export the content of this site.', 'export-to-blogger') );
}


function ew2bc_esc_html( $text ) {
	//encode & in named entities and numbered entities
	$text = str_replace('&', '&amp;', $text);
	//encode & <> ' "
	$text = htmlspecialchars($text, ENT_QUOTES | ENT_HTML5, 'UTF-8', false);
	return $text;
}
function ew2bc_esc_html_label( $text ) {
	//blogger doesn't recognize named entities and numbered entities in labels so decode them
	$text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
	//encode & <> ' "
	$text = htmlspecialchars($text, ENT_QUOTES | ENT_HTML5, 'UTF-8', false);
	return $text;
}
function base64url_encode($data) {
  return str_replace(array('+', '/', '='), array('&#043;', '&#047;', '&#61;'), base64_encode($data));
} 
?>