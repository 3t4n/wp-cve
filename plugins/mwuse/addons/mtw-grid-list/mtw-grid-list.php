<?php


function mtw_grid_trim_array(&$value)
{
    $value = trim($value);
}

function mtw_get_custom_query_dom( $dom, $parent )
{
	$custom_query_dom = mw_dom_getElementsByClass( $dom , "mtw-grid-custom-query", $parent );
	if( $custom_query_dom->length == 1 )
	{
		$custom_query_json = trim( $custom_query_dom->item(0)->nodeValue );
		if( $custom_query_json != "none" && !empty( $custom_query_json ) )
		{
			if( $custom_query = json_decode( $custom_query_json, true ) )
			{
				return $custom_query;
			}
			else
			{
				return array();
			}
		}
		else
		{
			return array();
		}
	}
	else
	{
		return array();
	}	
}

function mtw_pre_get_post_grid( $wp_query )
{

	
	if ( $wp_query->is_main_query() ) {

		$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		
		$wp_query->set( 'paged', $paged );

		$query_no_paged = $wp_query->query;
		unset( $query_no_paged['paged']) ;

		$title_report = 'mtw_pre_get_post_grid_' . sanitize_title( json_encode( $query_no_paged ) );

		$transient = get_transient( $title_report );

		if( is_array($transient) )
		{
			$new_query = $transient['report_query'];

			foreach ($transient['report_filters'] as $filter) 
			{
				if( has_filter($filter) )
				{
					$new_query = apply_filters( $filter, $new_query );
				}
			}

			foreach ($new_query as $key => $value) 
			{
				$wp_query->set( $key, $value );
			}
		}

	}
}
add_action( 'pre_get_posts', 'mtw_pre_get_post_grid', 0 );

function grid_list_creator($dom){


	global $wp_query;
	global $museUrl;
	global $post;
	global $grid_list;
	global $wp;

	$primary_wp_query = $wp_query;
	$primary_post = $post;
	$primary_wp = $wp;


	do_action( 'mtw_before_grid_lists' );

	$vars = array();

	$grid_lists = mw_dom_getElementsByClass( $dom , "grid-list" );
	

	foreach ($grid_lists as $key => $grid_list) {

		do_action( 'mtw_grid_list_loop_start' );

		$grid_query = apply_filters( 'mtw_grid_list_query', $wp_query, $key );

		$object = $grid_list->getElementsByTagName('iframe')->item(0);
		$col = $object->parentNode->parentNode->parentNode;
		
		$data = $object->getAttribute('data');

		$dataQuery = $grid_list->getAttribute('data-query');
		if( $dataQuery == "custom" )
		{
			$post_type = $grid_list->getAttribute('data-typename');
			$posts_per_page = $grid_list->getAttribute('data-max');
			$query_base = array(
					'post_type' => $post_type ,
					'posts_per_page' => $posts_per_page
					);

			$custom_query = mtw_get_custom_query_dom( $dom, $grid_list->parentNode );
			
			$query_base = array_merge( $query_base , $custom_query);

			$query_filter_name = explode("," , $grid_list->getAttribute('data-filtername') );
			array_walk( $query_filter_name , 'mtw_grid_trim_array');
			if( empty( $query_filter_name[0] ) )
			{
				$query_filter_name = array();
			}
			foreach ($query_filter_name as $filter) 
			{
				$query_base = apply_filters( $filter, $query_base );
			}
			
			
			$grid_query = new WP_Query( $query_base );
	
			$wp_query = $grid_query;
		}
		elseif( in_array( $dataQuery, array( 'category', 'tag', 'taxonomy' ) ) )
		{
			switch ($dataQuery) {
				case 'category':
					$taxonomies = 'category';
					$wp_query->is_category = 1;
					break;
				case 'tag':
					$taxonomies = 'post_tag';
					$wp_query->is_tag = 1;
					break;
				case 'taxonomy':
					$taxonomies = explode(',', $grid_list->getAttribute('data-typename') );
					$wp_query->is_tax = 1;
					break;
			}

			$args = array();

			$terms_per_page = $grid_list->getAttribute('data-max');
			if( $terms_per_page == '-1' ){ $terms_per_page = 0 ; }
			$args['number'] = $terms_per_page;

			$custom_query_dom = mw_dom_getElementsByClass( $dom , "mtw-grid-custom-query", $grid_list->parentNode );
			if( $custom_query_dom->length == 1 )
			{
				$custom_query_json = trim( $custom_query_dom->item(0)->nodeValue );
				if( $custom_query_json != "none" && !empty( $custom_query_json ) )
				{
					if( $custom_query = json_decode( $custom_query_json, true ) )
					{
						$args = array_merge( $args , $custom_query);
					}
				}
			}

			$query_filter_name = explode("," , $grid_list->getAttribute('data-filtername') );
			array_walk( $query_filter_name , 'mtw_grid_trim_array');
			if( empty( $query_filter_name[0] ) )
			{
				$query_filter_name = array();
			}
			foreach ($query_filter_name as $filter) 
			{
				$args = apply_filters( $filter, $args );
			}

			$terms = array_values( get_terms( $taxonomies, $args ) );
			$wp_query->queried_object = $terms[0];
			$wp_query->is_mtw_term_item = 1;
		}
		elseif ( $dataQuery == "array" ) 
		{
			
			
			$custom_array = array();
			$query_filter_name = explode("," , $grid_list->getAttribute('data-filtername') );
			array_walk( $query_filter_name , 'mtw_grid_trim_array');
			if( empty( $query_filter_name[0] ) )
			{
				$query_filter_name = array();
			}
			foreach ($query_filter_name as $filter) 
			{
				$custom_array = apply_filters( $filter, $custom_array );
			}
			
		}

		if( $dataQuery == "auto" && ( is_home() || is_archive() ) )
		{

			$report_query['posts_per_page'] = $grid_list->getAttribute('data-max');
			$report_query = array_merge( $report_query , mtw_get_custom_query_dom( $dom, $grid_list->parentNode ) );

			$report_filters = explode("," , $grid_list->getAttribute('data-filtername') );
			array_walk( $report_filters , 'mtw_grid_trim_array');
			if( empty( $report_filters[0] ) )
			{
				$report_filters = array();
			}

			$query_no_paged = $wp_query->query;
			unset( $query_no_paged['paged']) ;

			$title_report = 'mtw_pre_get_post_grid_' . sanitize_title( json_encode( $query_no_paged ) );
			$report = array(
				'report_query' => $report_query,
				'report_filters' => $report_filters 
				);
			 
			$transient = get_transient( $title_report );
			if(!$transient) { $transient = array(); };

			if(  serialize( $report ) != serialize( $transient ) )
			{
				set_transient( $title_report, $report );
				$reload = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
				?>
				<script type="text/javascript">location.reload();</script>
				<?php
			}

		}

		$wp_query->is_mtw_item = 1;


		ttr_template_filter();
		$item_path = $museUrl;

		
		/*if( basename( $item_path ) == "index.html" && $dataQuery != "sequence")
		{
			continue;
		}*/

		$data_customItem = ( $grid_list->getAttribute('data-customitem') );
		if( $data_customItem != 'none' && !empty( $data_customItem ) && $dataQuery != "sequence" )
		{
			$item_basename = basename( $item_path );
			$new_path = str_replace($item_basename, $data_customItem, $item_path);
			if( file_exists($new_path) )
			{
				$item_path = $new_path;
			}
		}


		$col_repeat = count( $grid_query->posts ) - 1;

		if( $dataQuery == "sequence" )
		{
			$col_repeat = $grid_list->getAttribute('data-max') - 1;
			$prefix =  $grid_list->getAttribute('data-typename');
		}
		elseif ( in_array( $dataQuery, array( 'category', 'tag', 'taxonomy' ) ) ) 
		{
			$col_repeat = count( $terms ) - 1;
		}
		elseif(  $dataQuery == "array" )
		{
			if( $grid_list->getAttribute('data-max') != -1 )
			{
				$col_repeat = $grid_list->getAttribute('data-max') - 1;
			}
			else
			{
				$col_repeat = count( $custom_array ) -1;
			}		
		}

		
		for ($i=0; $i < $col_repeat; $i++) 
		{
			$clone = mw_dom_cloneNode( $col, $dom );
			$new = $grid_list->appendChild($clone);
		}

		$objects = $grid_list->parentNode->getElementsByTagName('iframe');
		$to_transforms = array();
		$to_deletes = array();

		foreach ($objects as $key => $object) 
		{
			@$isset = $grid_query->posts[$key];

			if($isset || $dataQuery == "sequence" || @$terms || @$custom_array)
			{
				$to_transforms[] = $object;
			}
			else
			{
				$to_deletes[] = $object->parentNode->parentNode;
			}
		}

		/*
		// transform object to html
		*/
		$key = 0;
		$object;

		if( in_array( $dataQuery, array( 'auto', 'custom' ) ) )
		{
			if ( $grid_query->have_posts() ) {
			    while ( $grid_query->have_posts() ) 
			    {
			        $grid_query->the_post(); 

			        $object = $to_transforms[$key];
			        $container = $object->parentNode;
			        
			        //get item
			        mtw_import_item( $dom, $container, $item_path );


			        $object->parentNode->removeChild($object);

			        $key++;
			    }
			}
			wp_reset_postdata();
		}
		elseif( in_array( $dataQuery, array( 'category', 'tag', 'taxonomy' ) ) )
		{

			foreach ( $terms as $key => $term ) 
			{
				$wp_query->queried_object = $term;
				$object = $to_transforms[$key];
			    $container = $object->parentNode;

				mtw_import_item( $dom, $container, $item_path );

				$object->parentNode->removeChild($object);
			}
		}
		elseif ( $dataQuery == "array" ) 
		{
			global $mtw_custom_array;
			foreach ( $custom_array as $key => $value ) 
			{
				$mtw_custom_array = $value;
				$object = $to_transforms[$key];
			    $container = $object->parentNode;

				mtw_import_item( $dom, $container, $item_path );

				$object->parentNode->removeChild($object);
			}
			$mtw_custom_array = false;
		}
		elseif( $dataQuery == "sequence" )
		{
			foreach ( $to_transforms as $key => $object ) 
			{
				$container = $object->parentNode;
				$item_path = str_replace( basename( $museUrl ),  $prefix.'-'.($key+1).'.html', $museUrl );
				
				//get item
				mtw_import_item( $dom, $container, $item_path );

				$object->parentNode->removeChild($object);
			}
		}

		// delete object if no post
		foreach ($to_deletes as $key => $to_delete) 
		{
			$to_delete->parentNode->removeChild($to_delete);
		}
		
		global $mtw_item_links;

		$wp_query->is_mtw_item = 0;
		$wp_query->is_mtw_term_item = 0;
		$wp_query = $primary_wp_query;
		$post = $primary_post;
		$wp = $primary_wp;

		do_action( 'mtw_grid_list_loop_start' );		
	}

	do_action( 'mtw_after_grid_list' );

}
add_action( 'DOMDocument_body_loaded', 'grid_list_creator', 10, 1 );


function grid_list_js_visibility()
{
	?>
	<style type="text/css">
	
	@media (min-width: 1200px) {
		.grid_bootstrap > .grid-list.left > .col-lg-12:nth-child(1n+1),
		.grid_bootstrap > .grid-list.left > .col-lg-6:nth-child(2n+1),
		.grid_bootstrap > .grid-list.left > .col-lg-4:nth-child(3n+1),
		.grid_bootstrap > .grid-list.left > .col-lg-3:nth-child(4n+1),
		.grid_bootstrap > .grid-list.left > .col-lg-2:nth-child(6n+1)
		{
			clear: left;
		}
		.grid_bootstrap > .grid-list.right > .col-lg-12:nth-child(1n+1),
		.grid_bootstrap > .grid-list.right > .col-lg-6:nth-child(2n+1),
		.grid_bootstrap > .grid-list.right > .col-lg-4:nth-child(3n+1),
		.grid_bootstrap > .grid-list.right > .col-lg-3:nth-child(4n+1),
		.grid_bootstrap > .grid-list.right > .col-lg-2:nth-child(6n+1)
		{
			clear: right;
		}
	}
	@media (min-width: 992px) and (max-width: 1199px) { 
		.grid_bootstrap > .grid-list.left > .col-md-12:nth-child(1n+1),
		.grid_bootstrap > .grid-list.left > .col-md-6:nth-child(2n+1),
		.grid_bootstrap > .grid-list.left > .col-md-4:nth-child(3n+1),
		.grid_bootstrap > .grid-list.left > .col-md-3:nth-child(4n+1),
		.grid_bootstrap > .grid-list.left > .col-md-2:nth-child(6n+1)
		{
			clear: left;
		}
		.grid_bootstrap > .grid-list.right > .col-md-12:nth-child(1n+1),
		.grid_bootstrap > .grid-list.right > .col-md-6:nth-child(2n+1),
		.grid_bootstrap > .grid-list.right > .col-md-4:nth-child(3n+1),
		.grid_bootstrap > .grid-list.right > .col-md-3:nth-child(4n+1),
		.grid_bootstrap > .grid-list.right > .col-md-2:nth-child(6n+1)
		{
			clear: right;
		}
	}
	@media (min-width: 768px) and (max-width: 991px) { 
		.grid_bootstrap > .grid-list.left > .col-sm-12:nth-child(1n+1),
		.grid_bootstrap > .grid-list.left > .col-sm-6:nth-child(2n+1),
		.grid_bootstrap > .grid-list.left > .col-sm-4:nth-child(3n+1),
		.grid_bootstrap > .grid-list.left > .col-sm-3:nth-child(4n+1),
		.grid_bootstrap > .grid-list.left > .col-sm-2:nth-child(6n+1)
		{
			clear: left;
		}
		.grid_bootstrap > .grid-list.right > .col-sm-12:nth-child(1n+1),
		.grid_bootstrap > .grid-list.right > .col-sm-6:nth-child(2n+1),
		.grid_bootstrap > .grid-list.right > .col-sm-4:nth-child(3n+1),
		.grid_bootstrap > .grid-list.right > .col-sm-3:nth-child(4n+1),
		.grid_bootstrap > .grid-list.right > .col-sm-2:nth-child(6n+1)
		{
			clear: right;
		}
	}
	@media (max-width: 767px) {
		.grid_bootstrap > .grid-list.left > .col-xs-12:nth-child(1n+1),
		.grid_bootstrap > .grid-list.left > .col-xs-6:nth-child(2n+1),
		.grid_bootstrap > .grid-list.left > .col-xs-4:nth-child(3n+1),
		.grid_bootstrap > .grid-list.left > .col-xs-3:nth-child(4n+1),
		.grid_bootstrap > .grid-list.left > .col-xs-2:nth-child(6n+1)
		{
			clear: left;
		}
		.grid_bootstrap > .grid-list.right > .col-xs-12:nth-child(1n+1),
		.grid_bootstrap > .grid-list.right > .col-xs-6:nth-child(2n+1),
		.grid_bootstrap > .grid-list.right > .col-xs-4:nth-child(3n+1),
		.grid_bootstrap > .grid-list.right > .col-xs-3:nth-child(4n+1),
		.grid_bootstrap > .grid-list.right > .col-xs-2:nth-child(6n+1)
		{
			clear: right;
		}
	}
	
	.single-item .position_content
	{
		min-width: 0px !important;
	}
	</style>
	<script type="text/javascript">		

		jQuery(document).ready(function($) {	

			$('.grid-list .position_content').css('visibility', 'visible');
			$('.grid-list .single-item').css({
				"min-height": 'auto'
			});

			$(window).on('mtw_muse_bp_change resize mtw_size_influence', function(event) {

				$('.grid_breackpoints').each(function(index, el) {
					var flexsize = $(el).attr('data-flexsize').replace( / /g, "" ).split(",");
                    var flexcols = $(el).attr('data-flexcols').replace( / /g, "" ).split(",");
                    var col_number = 0;
                    $.each(flexsize, function(index, val) {
                        var size = parseInt( val );
                        var next_size = parseInt( flexsize[index+1] );
                        var win_width = $(window).width();
                        if( !next_size )
                        {
                            next_size = 0;
                        }
                        if( win_width <= size && win_width > next_size )
                        {
                            if( flexcols[index] )
                            {
                            	col_number = flexcols[index];
                            }
                        }
                    });
                    if( col_number == 0 )
                    {
                    	col_number = flexcols[0];
                    }
                    $(el).find('.row > div').css('clear', 'none');
	                $(el).find('.row > div:nth-child('+col_number+'n+1)').css('clear', 'left');
	                $(el).attr( 'data-current-col-number', col_number );
	                
				});
				
				$('.grid_bootstrap').each(function(index, el) {					
					$(el).attr( 'data-current-col-number', Math.round( $(el).width() / $(el).find('.row > div').first().width() ) );
				});

				$('.item-active .single-item').css('height', 'auto');
				$('.mtw-flex-cols').each(function(index, el) {
					

					col_number = $(el).attr('data-current-col-number');
					count = 1
					row = 0;
					row_heights = {};
					$(el).find('.row > div').each(function(index, el2) {

						if( count == 1 )
						{
							new_height = 0;
						}

						if( $(el2).find('.item-breakpoint').length > 0 )
						{
							item_height = $(el2).find('.item-active .single-item').height();
						}
						else
						{
							item_height = $(el2).find('.single-item').outerHeight();
							 $(el2).find('.single-item').parent().addClass('item-active');
						}

						if( item_height > new_height )
						{
							new_height = item_height;
							row_heights[row] = new_height;
						}
						$(el2).attr('data-current-row', row);
						if( count == col_number )
						{
							row++;
							count = 1;
						}
						else
						{
							count++;
						}
					});

					if( $(el).attr('data-masonry') != "true" || typeof mtw_masonry_my_grid !== "function" )
					{
						$.each(row_heights, function(index, val) {
							$(el).find('[data-current-row='+index+'] .item-active .single-item').css('height', val);
						});
					}
					else
					{
						mtw_masonry_my_grid( $(el), row_heights );
					}
					
				});
			});
		});
	</script>
	<?php
}

add_action( 'wp_footer', 'grid_list_js_visibility' );

function mtw_pagination( $atts ) {
	
	global $wp_query;

	$atts = shortcode_atts( array(
		'prev' => '&lt;',
		'next' => '&gt;',
	), $atts );
	

	$prev = $atts['prev'] ;
	$next = $atts['next'] ;


	ob_start();

	$big = 999999999; // need an unlikely integer

	$args = array(
		'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
		'format' => '?paged=%#%',
		'current' => max( 1, get_query_var('paged') ),
		'total' => $wp_query->max_num_pages,
		'type' => 'plain',
		'prev_text' => $prev,
		'next_text' => $next
	);

	echo(  paginate_links( $args )  );

	return ob_get_clean();

}
add_shortcode( 'mtw_pagination','mtw_pagination' );


function mtw_exclude_current_posts_function( $wp_query )
{
	global $post;
	$wp_query['post__not_in'] = array( $post->ID );	
	return $wp_query;
}
add_filter( 'mtw_exclude_current_post', 'mtw_exclude_current_posts_function' );

function mtw_custom_array_shortcode( $atts ) {

	global $mtw_custom_array;

	$atts = shortcode_atts( array(
		'key' => ''
	), $atts );

	return $mtw_custom_array[ $atts['key'] ];
}
add_shortcode( 'mtw_custom_array','mtw_custom_array_shortcode' );
?>