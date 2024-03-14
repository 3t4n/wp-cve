<?php

function aal_addlink_shortcode( $atts = array(), $content = null ) {
	$a = shortcode_atts( array(
		'id' => '',
), $atts );
	$id = intval( $a['id'] );
if ( ! $id ) {
  return $content;
}

	global $wpdb;
	$table_name = $wpdb->prefix . "automated_links";
	$myrows = $wpdb->get_results( "SELECT id,link,keywords,meta FROM ". $table_name ." WHERE id = '". $id ."' AND ( stats <> 'disabled' OR stats IS NULL ) LIMIT 1 " );
	
	if (count($myrows) <= 0){
    return $content;

	}
	
	$autolink = $myrows[0];
	
	
	if(!isset($autolink->link)) return $content;
	
	$link = $autolink->link;
	
	$title = '';
	
	if(isset($autolink->meta)) {
	$meta = json_decode($autolink->meta);
	
	
		if((is_array($meta) || is_object($meta)) && isset($meta->title ))  $title = $meta->title;
	
	}
	
	
		$ownhost = aal_get_host_from_parse(get_site_url());
		$linkhost = aal_get_host_from_parse($link);
		
		if($ownhost == $linkhost) { 
			$disclosure = get_option('aal_il_disclosure');
			$cssclass = get_option('aal_ilcssclass');		
			$targeto = get_option('aal_il_target');
			$relationo = get_option('aal_il_relation');
		
		}
		else {
			
			$disclosure = get_option('aal_disclosure');
			$cssclass = get_option('aal_cssclass');
			$targeto = get_option('aal_target');
			$relationo = get_option('aal_relation');
		
		}
		
		
		if($cssclass) $lclass = $cssclass . " aalshortcode";
		else $lclass = 'aalshortcode';
	

	
		
		if($relationo=='nofollow') $relo = ' rel="nofollow" ';
		elseif($relationo == 'sponsored') $relo = ' rel="sponsored" ';
		else $relo = '';
		
		
		
		
		if($targeto == '_blank' ) $taro = " target=\"_blank\" "; else $taro = '';
		
		
		

	

	$content = '<a href="'. $link .'" title="'. $title .'" class="'. $lclass .'" '. $relo .' '. $taro .'>'. $content .'</a>'. $disclosure;


    return $content;
}
add_shortcode( 'autolink', 'aal_addlink_shortcode' );