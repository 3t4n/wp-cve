<?php

function mtw_strtolower($return)
{
	if( function_exists('mb_strtolower'))
	{	
		$return = mb_strtolower( $return );
	}
	else
	{
		$return = strtolower( $return );
	}
	return $return;
}
function mtw_check_hierarchy( $template_hierarchy , $singleTemplates ){
		
	global $museUrl;
	global $wp_query;
	global $mtw_option;

	foreach ($template_hierarchy as $key => $value) 
	{	
		if( !is_int( $key ) && $wp_query->$key )
		{
			if( isset( $value[0] ) )
			{
				foreach ($value as $key2 => $value2) {

					if( @$mtw_option['mtw_index_exclude'] == 'checked' && $value2 == 'index' )
					{
						continue;
					}

					$search =  mtw_strtolower( $value2 . '.html' );

					if( in_array( $search , $singleTemplates ) )
					{
						$museUrl = TTR_MW_TEMPLATES_PATH . array_search( $value2 . '.html' , $singleTemplates ) ;
					}
					
					$search = mtw_strtolower( $value2 . '-' . get_locale() . '.html' );

					if( in_array( $search , $singleTemplates ) )
					{
						$museUrl = TTR_MW_TEMPLATES_PATH . array_search( $search , $singleTemplates ) ;
					}
				}
			}
			else
			{
				mtw_check_hierarchy( $value, $singleTemplates );
			}
		}
	}
}

function ttr_template_filter( $template = null , $pass2 = false ) 
{

	global $museUrl;
	global $wp_query;
	global $post;
	global $term;

	global $deviceType;

	if( @$_GET['mtw_item'] ) 
	{	
		$wp_query->is_mtw_item = 1;	
	}

	@$post_type = $post->post_type;
	if( @$wp_query->query['post_type'] )
	{
		$post_type = $wp_query->query['post_type'] ;
	}
	

	if ( $wp_query->is_tax || $wp_query->is_category || $wp_query->is_tag ) 
	{
		$tax = $wp_query->queried_object->taxonomy;
		$term = $wp_query->queried_object->slug;
		
		$wp_query->is_archive = 1;

	}

	if( $wp_query->is_mtw_item && $wp_query->is_mtw_term_item  )
	{
		$wp_query->is_archive = 0;
		$wp_query->is_page = 0;
	}
	

	$template_hierarchy = apply_filters( 'mtw_template_hierarchy', array(
		'is_home' => array(
			0 => 'index'
			),
		'is_posts_page' => array(
			0 => 'archive',
			1 => 'blog'
			),
		'is_page' => array(
			0 => 'index',
			1 => 'page'
			),
		'is_single' => array(
			0 => 'index',
			1 => 'single',
			2 => 'single-' . $post_type,
			3 => 'single-' . $post_type . '-' . @$post->post_name
			),
		'is_archive' => array(
			'is_post_type_archive' => array(
				0 => 'index',
				1 => 'archive',
				2 => 'archive-' . $post_type
				),
			'is_category' => array(
				0 => 'index',
				1 => 'archive',
				2 => 'category',
				3 => 'category-' . $term,
				),
			'is_tax' => array(
				0 => 'index',
				1 => 'archive',
				2 => 'taxonomy',
				3 => 'taxonomy-' . @$tax,
				4 => 'taxonomy-' . @$tax . '-' . $term
				),
			'is_tag' => array(
				0 => 'index',
				1 => 'archive',
				2 => 'tag',
				3 => 'tag-' . $term,
				),
			),
		'is_404' => array(
			0 => '404'
			),
		'is_search' => array(
			0 => 'search'
			),
		'is_mtw_item' => array(
			0 => 'item',
			1 => 'item-' . $post_type,
			2 => 'item-' . @$tax,
			3 => 'item-' . @$tax . '-' . $term
			),
		'is_mtw_term_item' => array(
			0 => 'term',
			1 => 'term-' . @$tax,
			2 => 'term-' . @$tax . '-' . $term
			)
		) );
	
	$isMuseTheme = strpos( $template , TTR_MW_TEMPLATES_PATH );

	$singleTemplates =  array();
	
	$musesProjectsPages = ttr_get_muse_html_array();
	if( $musesProjectsPages ){
		foreach ( ttr_get_muse_html_array() as $key => $value ) 
		{			
			$explode = explode("/", $key);
			$file = $explode[1];
			$singleTemplates[$key] = $file;	
		}
	}
	if( !$wp_query->is_page )
	{
		mtw_check_hierarchy( $template_hierarchy , $singleTemplates );
	}
	if( basename( $template ) == "woocommerce.php" && $pass2 == false )
	{
		return $template;
	}	
	if( $isMuseTheme !== false || !is_null($museUrl) )
	{
		if ( is_null( $museUrl ) ) $museUrl = $template;
		return TTR_MW_PLUGIN_DIR . 'default-template-5.php';
	}
	else
	{
		return $template;
	}
}
?>