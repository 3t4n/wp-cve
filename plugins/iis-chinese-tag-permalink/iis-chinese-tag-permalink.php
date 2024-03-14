<?php
/*
Plugin Name: IIS Chinese Tag Permalink
Plugin URI: http://blog.bossma.cn/php/in-iis-wordpress-chinese-tag-permalinks-plugin/
Description: 使IIS下的WordPress可以在友好的固定链接（Pretty Permalink）中使用中文。
Author: BOSSMA.CN
Author URI: http://blog.bossma.cn/
Version: 1.4
*/
add_action('init', 'bossma_get_urf8_pathandurl');
add_filter('get_pagenum_link','bossma_set_utf8_pagenumlink');
add_action('parse_query','bossma_chinese_tag_encode');

// iis encode the url with GBK,but wordpress with utf-8
function bossma_get_urf8_pathandurl() {
	$_SERVER['PATH_INFO']=iconv("GBK","UTF-8",$_SERVER['PATH_INFO']);
	$_SERVER['REQUEST_URI']=iconv("GBK","UTF-8",$_SERVER['REQUEST_URI']);
}

// urlencode for chinese tag
// sometimes preg_match can not match the chinese correctly 
function bossma_chinese_tag_encode($query){
	if(isset($is_tag)&&$is_tag){
		if($query->query_vars['tag']!=''){
			$query->query_vars['tag']=urlencode($query->query_vars['tag']);
		}
	}
}

//for generate tag pagenum link
//use utf8 encode
function bossma_set_utf8_pagenumlink($result){
 $tag_index=stripos($result,"/tag/");
 $link_len=strlen($result);
 if($tag_index!==false){
  $link_base=substr($result,0,$tag_index);
  $page_index=stripos($result,"/page/");
  if($page_index!==false){
   $tag_str=substr($result,$tag_index+5,$page_index-$tag_index-5);
   $result=$link_base."/tag/".rawurlencode($tag_str).substr($result,$page_index);
  }else{
   $tag_str=substr($result,$tag_index+5);
   $result=$link_base."/tag/".rawurlencode($tag_str)."/";
  }
 }
 return $result;
}
?>
