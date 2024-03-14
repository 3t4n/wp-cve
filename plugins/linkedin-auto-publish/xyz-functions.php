<?php
if( !defined('ABSPATH') ){ exit();}

if(!function_exists('xyz_trim_deep'))
{

	function xyz_trim_deep($value) {
		if ( is_array($value) ) {
			$value = array_map('xyz_trim_deep', $value);
		} elseif ( is_object($value) ) {
			$vars = get_object_vars( $value );
			foreach ($vars as $key=>$data) {
				$value->{$key} = xyz_trim_deep( $data );
			}
		} else {
			$value = trim($value);
		}

		return $value;
	}

}

if(!function_exists('esc_textarea'))
{
	function esc_textarea($text)
	{
		$safe_text = htmlspecialchars( $text, ENT_QUOTES );
		return $safe_text;
	}
}

if(!function_exists('xyz_lnap_plugin_get_version'))
{
	function xyz_lnap_plugin_get_version()
	{
		if ( ! function_exists( 'get_plugins' ) )
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		$plugin_folder = get_plugins( '/' . plugin_basename( dirname( XYZ_LNAP_PLUGIN_FILE ) ) );
		// 		print_r($plugin_folder);
		return $plugin_folder['linkedin-auto-publish.php']['Version'];
	}
}


if(!function_exists('xyz_lnap_links')){
	function xyz_lnap_links($links, $file) {
		$base = plugin_basename(XYZ_LNAP_PLUGIN_FILE);
		if ($file == $base) {

			$links[] = '<a href="http://help.xyzscripts.com/docs/linkedin-auto-publish/faq/"  title="FAQ">FAQ</a>';
			$links[] = '<a href="http://help.xyzscripts.com/docs/linkedin-auto-publish/"  title="Read Me">README</a>';
			$links[] = '<a href="https://xyzscripts.com/support/" class="xyz_support" title="Support"></a>';
			$links[] = '<a href="http://twitter.com/xyzscripts" class="xyz_twitt" title="Follow us on twitter"></a>';
			$links[] = '<a href="https://www.facebook.com/xyzscripts" class="xyz_fbook" title="Facebook"></a>';
// 			$links[] = '<a href="https://plus.google.com/+Xyzscripts" class="xyz_gplus" title="+1"></a>';
			$links[] = '<a href="http://www.linkedin.com/company/xyzscripts" class="xyz_linkdin" title="Follow us on linkedIn"></a>';
		}
		return $links;
	}
}

if(!function_exists('xyz_lnap_string_limit')){
function xyz_lnap_string_limit($string, $limit) {

	$space=" ";$appendstr=" ...";
	if (function_exists('mb_strlen')) {
	if(mb_strlen($string) <= $limit) return $string;
	if(mb_strlen($appendstr) >= $limit) return '';
	$string = mb_substr($string, 0, $limit-mb_strlen($appendstr));
	$rpos = mb_strripos($string, $space);
	if ($rpos===false)
		return $string.$appendstr;
	else
		return mb_substr($string, 0, $rpos).$appendstr;
}
	else {
			if(strlen($string) <= $limit) return $string;
			if(strlen($appendstr) >= $limit) return '';
			$string = substr($string, 0, $limit-strlen($appendstr));
			$rpos = strripos($string, $space);
			if ($rpos===false)
				return $string.$appendstr;
				else
					return substr($string, 0, $rpos).$appendstr;
		}
}
}

if(!function_exists('xyz_lnap_getimage')){
function xyz_lnap_getimage($post_ID,$description_org)
{
	$attachmenturl="";
	$post_thumbnail_id = get_post_thumbnail_id( $post_ID );
	if(!empty($post_thumbnail_id))
		$attachmenturl=wp_get_attachment_url($post_thumbnail_id);

		else
		{
        $matches=array();
        $img_content = apply_filters('the_content', $description_org);
        preg_match_all( '/< *img[^>]*src *= *["\']?([^"\']*)/is', $img_content, $matches );
			if(isset($matches[1][0]))
				$attachmenturl = $matches[1][0];
        else
            $attachmenturl=xyz_lnap_get_post_gallery_images_with_info($description_org,1);
		}
	return $attachmenturl;
}

	}
if(!function_exists('xyz_lnap_get_post_gallery_images_with_info'))
{
    function xyz_lnap_get_post_gallery_images_with_info($post_content,$single=1) {
        $ids=$images_id=array();
        preg_match('/\[gallery.*ids=.(.*).\]/', $post_content, $ids);
        if (isset($ids[1]))
            $images_id = explode(",", $ids[1]);
            $image_gallery_with_info = array();
            foreach ($images_id as $image_id) {
            $attachment = get_post($image_id);
            $img_src=$attachment->guid;
            if($single==1)
                return $img_src;
                else
                    $image_gallery_with_info[]=$img_src;
}
            return $image_gallery_with_info;
    }
}

/* Local time formating */
if(!function_exists('xyz_lnap_local_date_time')){
	function xyz_lnap_local_date_time($format,$timestamp){
		return date($format, $timestamp + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ));
	}
}

add_filter( 'plugin_row_meta','xyz_lnap_links',10,2);

if (!function_exists("xyz_lnap_is_session_started")) {
function xyz_lnap_is_session_started()
{
   
        if ( version_compare(phpversion(), '5.4.0', '>=') ) {
            return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
        } else {
            return session_id() === '' ? FALSE : TRUE;
        }
    
    return FALSE;
}
}

	if(!function_exists('xyz_lnap_post_to_smap_api'))
	{		
		function xyz_lnap_post_to_smap_api($post_details,$url,$xyzscripts_hash_val='') {
			if (function_exists('curl_init'))
			{
				$post_parameters['post_params'] = serialize($post_details);
				$post_parameters['request_hash'] = md5($post_parameters['post_params'].$xyzscripts_hash_val);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_parameters);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER,(get_option('xyz_lnap_peer_verification')=='1') ? true : false);
				$content = curl_exec($ch);
				curl_close($ch);
				if (empty($content))
				{
					if ($url==XYZ_SMAP_SOLUTION_LN_PUBLISH_URL.'api/publish.php')
						$response=array('status'=>0,'ln_api_count'=>0,'msg'=>'Error:unable to connect');
						$content=json_encode($response);
				}
				return $content;
			}
		}
	}
?>