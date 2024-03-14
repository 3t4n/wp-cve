<?php


global $mtw_items_called;
global $mtw_items_scripts;
$mtw_items_scripts = array();
$mtw_items_called = array();

function mtw_import_item( $target_dom, $target_container, $item_path )
{
	global $mtw_item_links;
	global $mtw_items_called;
	global $wp_filesystem;
	WP_Filesystem();

	$mtw_item_links = array();
	

	$page = new MusePage;
	$page->init( str_replace( TTR_MW_TEMPLATES_PATH , "", $item_path ) );
	$itemDom = $page->DOMDocument;

	mw_repaire_link_script($itemDom);

	global $post;

	$container_class = str_replace( ".", "-", $page->base_name );
	$temp_ct_class = $target_container->getAttribute("class");
	$target_container->setAttribute( 'class', $temp_ct_class . " " . $container_class . ' item-post-'.$post->ID );


	foreach ($itemDom->getElementsByTagName('link') as $value) {
		$uniqueKey = $value->getAttribute('href');
		$mtw_item_links[$uniqueKey] = $value;
	}
	foreach ($itemDom->getElementsByTagName('style') as $value) {
		$uniqueKey = $value->nodeValue;
		$mtw_item_links[$uniqueKey] = $value;
	}
	foreach ($itemDom->getElementsByTagName('script') as $value) {
		$uniqueKey = $value->getAttribute('src');
		if( !$uniqueKey )
		{
			$uniqueKey = $value->nodeValue;
		}
		$mtw_item_links[$uniqueKey] = $value;
	}

	// id to class
	$allItems = $itemDom->getElementsByTagName('*');
	foreach ($allItems as $item) {
		$id = $item->getAttribute('id');
		if( $id )
		{
			$class =  $item->getAttribute('class');

			if( $id == 'page' )
			{
				$item->removeAttribute('id');
				$id = 'single-item';				
				$item->setAttribute( 'class' , $class . ' ' . $id  );
			}
			elseif ( $id == 'page_position_content' ) 
			{
				$item->removeAttribute('id');	
			}
		}
		$data_orig_id = $item->getAttribute('data-orig-id');
		if( $data_orig_id )
		{
			if( $data_orig_id == 'page' )
			{
				$item->removeAttribute( 'data-orig-id' );
				$item->setAttribute( 'data-orig-id-item' , 'single-item' );
			}
			else
			{
				$item->removeAttribute( 'data-orig-id' );
				$item->setAttribute( 'data-orig-id-item' , $data_orig_id );
			}
		}
	}

	$import = new DOMDocument();
	$import->loadHTML( do_shortcode( $itemDom->saveHTML() ) );

	$placeholders = mw_dom_getElementsByClass( $import , "placeholder" );
	foreach ( $placeholders as $placeholder) 
	{
		$placeholder_class =  $placeholder->getAttribute('class');
		$placeholder->setAttribute('class', str_replace('placeholder', 'item-placeholder', $placeholder_class) );
	}


	$nodesToImport = array();

	$firstClass = $itemDom->getElementsByTagName('div')->item(0)->getAttribute('class');
	if( strpos($firstClass, 'breakpoint') === false )
	{
		$nodesToImport[0] = mw_dom_getElementsByClass( $import , "single-item" )->item(0);
	}
	else
	{
		foreach (mw_dom_getElementsByClass( $import , "breakpoint" ) as $key => $breakpoint) 
		{
			//$nodesToImport[0] = $import->getElementsByTagName('div')->item(0);
			$breakpoint_class =  $breakpoint->getAttribute('class');
			$breakpoint_class = str_replace('breakpoint', 'item-breakpoint', $breakpoint_class);
			$breakpoint_class = str_replace('active', 'item-active', $breakpoint_class);
			$nodesToImport[$key] = $breakpoint;
			$nodesToImport[$key]->setAttribute('class', $breakpoint_class );
			$nodesToImport[$key]->setAttribute('class', $breakpoint_class );
		}
	}

	foreach (mw_dom_getElementsByClass( $import , "size_browser_width" ) as $key => $size_browser_width) 
	{
		/*$size_browser_width_class =  $size_browser_width->getAttribute('class');
		$size_browser_width_class = str_replace('size_browser_width', 'item-size_browser_width size_fluid_width colelem', $size_browser_width_class);
		$size_browser_width->setAttribute('class', $size_browser_width_class );*/
	}



	$verticalspacers = mw_dom_getElementsByClass( $import , "verticalspacer" );
	foreach ( $verticalspacers as $verticalspacer) 
	{
		$verticalspacer->parentNode->removeChild($verticalspacer);
	}

	do_action( 'DOMDocument_item_loaded', $import, $item_path );

	foreach ($nodesToImport as $key => $nodeToImport) 
	{
		$nodeImported = $target_dom->importNode($nodeToImport, true);

		$nodeImported->removeAttribute('id');

		$target_container->appendChild( $nodeImported );
	}
	
	


	// exclude script link if in primary dom
	global $redirect_file;
	if( $redirect_file )
	{
		$container_class = urlencode( $redirect_file );
	}

	if( !in_array($container_class, $mtw_items_called) )
	{
		$mtw_items_called[] = $container_class;
		global $mtw_head;
		global $muse_footer;
		$doms = array( $target_dom, $mtw_head, $muse_footer );


		foreach ($doms as $value) {
			
			$scripts =  $value->getElementsByTagName('script') ;
			$links =  $value->getElementsByTagName('link') ;

			foreach ($mtw_item_links as $key => $value2) {
				
				$delete = false ;

				if($value2->tagName == 'link')
				{
					foreach ($links as $link) {
						if( mtw_exclude_get( $link->getAttribute('href') ) ==  mtw_exclude_get( $value2->getAttribute('href') ) )
						{
							$delete = true;
						}
					}
				}

				if($value2->tagName == 'script' && trim( $value2->getAttribute('src') ) != "" )
				{
					foreach ($scripts as $script) {
						if( mtw_exclude_get( $script->getAttribute('src') ) == mtw_exclude_get( $value2->getAttribute('src') ) )
						{
							$delete = true;
						}
					}
				}				

				if($value2->tagName == 'script' && !empty( $value2->nodeValue ) && $scripts->length > 0 )
				{
					foreach ($scripts as $script) {
						if( $script->nodeValue === $value2->nodeValue || preg_match("#(window\.Muse\.assets)|(musecdn2\.businesscatalyst)|(jquery-1\.8\.3\.min\.js)#", $value2->nodeValue)  )
						{
							$delete = true;
						}
					}
				}

				if( $delete ){
					unset($mtw_item_links[$key]);
				}
				unset($finded);
			}
		}

		$mtw_item_links = array_values( $mtw_item_links );


		global $mtw_items_scripts;	
		
		$cssContent = "";
		foreach ($mtw_item_links as $key => $value) {
			if( $value->tagName == "link" )
			{
				$link_url = mtw_exclude_get( str_replace( TTR_MW_TEMPLATES_URL, TTR_MW_TEMPLATES_PATH, $value->getAttribute('href') ) );
				if( preg_match("#.css$#", $link_url) && !preg_match("#/nomq#", $link_url) )
				{
					$cssContent.= $wp_filesystem->get_contents( $link_url );
				}
			}
			if( $value->tagName == "style" )
			{
				$cssContent.=$value->nodeValue;
			}
			if( $value->tagName == "script" )
			{
				
				if( trim( $value->getAttribute('src') ) != "" )
				{
					$mtw_items_scripts[$value->getAttribute('src')] = ( '<script type="text/javascript" src="' . $value->getAttribute('src') . '"></script>' );
				}
				else
				{
					$mtw_items_scripts[$value->nodeValue] = ( '<script type="text/javascript">' . $value->nodeValue . '</script>' );
				}
				
			}
		}
		
		// join unique css
		$parent_CSS_class = $container_class;


		$pattern = array(
			'#\.html|body#',
			'#\#page_position_content#',
			'#\#page#',
			'#\#muse_css_mq#'
			);
		$replacement = array(
			'.'.$parent_CSS_class . ' .position_content',
			'.'.$parent_CSS_class . ' .position_content',
			'.'.$parent_CSS_class . ' .single-item',
			'.NULL'
			);

		$cssContent = preg_replace( $pattern, $replacement, $cssContent );

		$cssContent = apply_filters( 'mw_muse_item_styles_inline_filter', $cssContent );
		
		$media_intro = "\@media(?:(?:(?!{).)+{)";
		$css_rule = "(?:(?!{).)+{(?:(?!}).)+}";
		preg_match_all( "#".$media_intro."(?:(?!})".$css_rule.")+}#", $cssContent, $preg_match_all_media );
		foreach ( $preg_match_all_media[0] as $key => $media_query )
		{


			$original_media_query = $media_query;

			$new_media_query = preg_replace( "#". $media_intro . "#", "", $media_query );
			$new_media_query = substr($new_media_query, 0, -1);

			preg_match_all( "#".$css_rule."#", $new_media_query, $css_rules_to_replaces );
			$reg_1 = "((?:(?!\)).)+)";
			preg_match_all( "#\@media\s?\(".$reg_1."(?:(?:(?!{).)+{)#" ,$original_media_query, $data_info );
			$data_text = preg_replace(
				array(
					"# #",
					"#\:#",
					"#px#"
					), 
				array(
					"",
					"=\"",
					"\""
					), 
				$data_info[1][0]);

			foreach ( $css_rules_to_replaces[0] as $key_index => $css_rules_to_replace ) 
			{
				$new_media_query = str_replace($css_rules_to_replace, '[data-'.$data_text.'] '.$css_rules_to_replace, $new_media_query);
				
				
			}
			$new_media_query = str_replace(",#", ',[data-'.$data_text.'] #', $new_media_query);
			$new_media_query = str_replace(",.", ',[data-'.$data_text.'] .', $new_media_query);
			
			$cssContent = str_replace($original_media_query, $new_media_query, $cssContent);
		}
		$cssDOM = new DOMDocument();
		$cssDOM->loadHTML( '<div class="item-style" ><style type="text/css">/* item css */' . $cssContent . '</style></div>' );


		$styleDom = $cssDOM->getElementsByTagName('div')->item(0);
		$cssImported = $target_dom->importNode($styleDom, true);


		$target_style = $target_dom->getElementsByTagName("body")->item(0)->childNodes->item(0);

		$target_style->parentNode->insertBefore($cssImported, $target_style);
		
	}
}

function mtw_style_breakpoint_item()
{
	?>
	<style type="text/css">
	.item-breakpoint
	{
		display: none;
	}
	.item-breakpoint.item-active
	{
		display: block;
	}
	</style>
	<?php
}
add_action( 'wp_head', 'mtw_style_breakpoint_item' );

/*function mtw_add_items_scripts()
{
	global $mtw_items_scripts;
	foreach ( $mtw_items_scripts as $script ) 
	{
		print_r( $script );
	}
}
add_action( 'MTW_after_muse_footer', 'mtw_add_items_scripts' );*/

function mtw_responsive_item()
{
	?>
	<script type="text/javascript">
	jQuery(document).ready(function($) {
		var mtw_check_items_breakpoints_timer;
		function mtw_check_items_breakpoints()
		{
			//$('.item-breakpoint').find('.browser_width').removeClass('browser_width').addClass('size_fluid_width').css('width', '100%');		
			mtw_bp_item_change = false;

			$('.item-breakpoint.item-active').each(function(index, el) {

				$(el).find('.browser_width').each(function(index_browser_width, el_browser_width) {
					if( $(el_browser_width).children().length == 1 )
					{
						new_browser_width_height = $(el_browser_width).children().height();
						
						if( new_browser_width_height != $(el_browser_width).height() )
						{
							$(el_browser_width).height(new_browser_width_height);
							$(document).trigger('mtw_size_influence');
						}
					}	
				});
				
				

				item_max_width = $(document).width();
				item_width = $(el).width();
				$(el).parent().find('.item-breakpoint').each(function(index2, el2) {
					item_data_max = $(el2).attr('data-max-width');
					item_data_min = $(el2).attr('data-min-width');
					if( !item_data_max ) {item_data_max = item_max_width;}
					if( !item_data_min ) {item_data_min = 0;}

					if( item_width <= item_data_max && item_width >= item_data_min && item_width != 0 )
					{
						mtw_bp_item_change = true;

						$(el2).find('[data-placeholder-for]').each(function(index3, el3) {
							

							bp_item_guid = $(el3).attr('data-placeholder-for');
							bp_item_origin = $(el).parent().find('[data-content-guid='+bp_item_guid+']');
							bp_item_id = bp_item_origin.attr('id');
							bp_item_content = bp_item_origin.html();
							bp_item_tagName = bp_item_origin.prop("tagName");
							
							
							if( bp_item_tagName == undefined || bp_item_tagName == false )
							{
								return;
							}

							var replaced;

							$(el3).replaceWith(function() {
								replaced = $("<"+bp_item_tagName+">",{	'class':this.className,
							    					'data-content-guid' : bp_item_guid,
							    					'id': bp_item_id
							                       }).html( bp_item_content );
								return replaced;
							});

							
							if( bp_item_origin.length >= 1 )
							{
								$.each(bp_item_origin[0].attributes, function() {
									if(this.specified && this.name != 'class' && this.name != 'data-content-guid' && this.name != 'id' && this.name != 'style' ) 
									{								
										replaced.attr(this.name, this.value);
									}
								});
							}


							bp_item_origin.replaceWith(function() {
							    return $("<span>",{	'class':this.className,
							    					'data-placeholder-for' : bp_item_guid,
							    					'id': ''
							                       }).html( '' );
							});

							$(el).removeClass('item-active');
							$(el2).addClass('item-active');

							$(el).parent().find('[data-orig-id-item]').each(function(index, el4) {

								$(el4).removeClass('temp_no_id');
								orig_id = $(el4).attr('data-orig-id-item');
								if( orig_id ==  'single-item' )
								{
									$(el4).addClass('single-item');
								}
								else
								{
									$("#"+orig_id).data('orig-id-item', orig_id);
									$(el4).attr('id', orig_id);
								}								
							});
							
							
							
							/*change_height = 0;
							$(el2).find('.browser_width > *').each(function(index4, el4) {
								if( $(el4).height() > change_height )
								{
									change_height = $(el4).height();
								}
							});
							if (change_height != 0) 
							{
								//$(el2).find('.browser_width').height(change_height);
								//
							};	*/						
						});
					}

					if (item_width == 0 ) 
					{
						mtw_check_items_breakpoints_timer = setTimeout( mtw_check_items_breakpoints, 50 );
					};

				});
			});
			

			if( mtw_bp_item_change )
			{
				$(document).trigger('mtw_bp_item_change');
			}
			
		}

		$(window).on('load mtw_size_influence mtw_muse_bp_change', function(event) {
			event.preventDefault();
			mtw_check_items_breakpoints();			
		});	
	});	
	</script>
	<?php
}
add_action( 'wp_footer', 'mtw_responsive_item' );
?>