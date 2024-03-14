<?php

function mtw_maybe_add_html_extension( $page_name )
{
	if( substr($page_name, -5) != '.html' && substr($page_name, -4) != '.htm' )
	{
		return urlencode( $page_name ). '.html';
	}
	else
	{
		return $page_name;
	}
}

function mtw_logic_template_redirect($html, $fileurl)
{
	global $post;
	global $redirect_file;

	$post_id = get_the_id();
	$folder = explode("/", $fileurl);
	$folder = $folder[0];

	$metas = $html->getElementsByTagName( 'meta' );
	
	$logic = array();
	$count = 0;
	$to_delete = array();

	foreach ($metas as $meta) {
		if( $meta->getAttribute('type') == 'logic' || $meta->getAttribute('type') == 'redirect' )
		{
			$logic[$count]['subject'] = $meta->getAttribute('subject');
			$logic[$count]['key'] = $meta->getAttribute('key');
			$logic[$count]['value'] = $meta->getAttribute('value');
			$logic[$count]['page'] = $meta->getAttribute('page');

			$count++;
			$to_delete[] = $meta;
		}
	}	

	foreach ($to_delete as $delete) {
		$delete->parentNode->removeChild( $delete );
	}

	$redirect = false;
	foreach ($logic as $value) {

		if( $value['subject'] == 'meta')
		{
			if( get_post_meta( $post_id, $value['key'] , true ) == $value['value'] )
			{
				$redirect = $value['page'];
			}
		}

		if( $value['subject'] == 'taxonomy' )
		{
			//Key = tax slug
			//Value = term slug
			if( has_term( $value['value'], $value['key'] ) )
			{
				$redirect = $value['page'];
			}
		}

		if( $value['subject'] == 'function')
		{
			add_filter( 'mtw_logic_redirect_function_'.$value['key'] , $value['key'] ); 
			$function = apply_filters( 'mtw_logic_redirect_function_'.$value['key'] , false );
			remove_filter( 'mtw_logic_redirect_function_'.$value['key'] , $value['key'] );

			if( $function )
			{	
				$redirect = $value['page'];
			}
		}

		if( $value['subject'] == '_get' && $_GET[$value['key']] == $value['value'])
		{
			$redirect = $value['page'];
		}
	}




	global $force_redirect;

	if( $force_redirect )
	{
		$redirect = $force_redirect;
		$force_redirect = false;
	}

	global $deviceType;
	
	if( $redirect )
	{	
		$file = TTR_MW_TEMPLATES_PATH . $folder . '/' . mtw_maybe_add_html_extension( $redirect );
		if( $deviceType != 'computer' )
		{
			$file_mobile = TTR_MW_TEMPLATES_PATH . $folder . '/' . $deviceType . '/' . $redirect;
			if( file_exists( $file_mobile ) )
			{
				$file = $file_mobile;
			}
		}
		$redirect_file = $redirect;

		$html->loadHTMLFile( $file );
		do_action( 'DOMDocument_logic_loaded', $html, $file );
		do_action( 'mtw_DOMDocument_logic_loaded', $html, $file );
	}
	else
	{
		$redirect_file = false;
	}
}
add_action('DOMDocument_loaded', 'mtw_logic_template_redirect', 10, 2 );

?>