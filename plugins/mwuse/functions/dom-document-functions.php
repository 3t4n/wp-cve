<?php
function mw_dom_change_node_tagName($node, $name) 
{
    $newnode = $node->ownerDocument->createElement($name);
    foreach ($node->attributes as $attrName => $attrNode) {
    	$newnode->setAttribute( $attrNode->name, $attrNode->nodeValue);    	
    }
    $node->parentNode->replaceChild($newnode, $node);
    $html = mw_DOMinnerHTML($node);
    mw_DOM_appendHTML( $newnode, $html );
    return $newnode;
}

function mw_dom_remove_elements_by_selectors($dom, $exclude)
{
	$exclude = trim( preg_replace("#\s|(&nbsp;)#", "", $exclude) );
	$excludes = explode(",", $exclude);
	foreach ($excludes as $key => $exclude) 
	{
		if( strpos($exclude, '.') === 0 )
		{
			//class
			$elements = mw_dom_getElementsByClass($dom, substr($exclude, 1) );
			foreach ($elements as $key => $element) 
			{
				$element->parentNode->removeChild( $element );
			}
		}	
		elseif( strpos($exclude, '#') === 0 )
		{
			//id
			$element = $dom->getElementById( substr($exclude, 1) );
			$element->parentNode->removeChild( $element );
		}
		else
		{
			$elements = $dom->getElementsByTagName( $exclude );
			foreach ($elements as $key => $element) 
			{
				$element->parentNode->removeChild( $element );
			}
		}	
	}
}


function mw_dom_get_elements_by_selectors($dom, $get, $index = 0)
{
	$get = trim( preg_replace("#\s|(&nbsp;)#", "", $get) );
	$gets = explode(",", $get);
	foreach ($gets as $key => $get) 
	{
		if( strpos($get, '.') === 0 )
		{
			//class
			$element = mw_dom_getElementsByClass( $dom, substr($get, 1) )->item( $index );
		}	
		elseif( strpos($get, '#') === 0 )
		{
			//id
			$element = $dom->getElementById( substr($get, 1) );
		}
		else
		{
			$element = $dom->getElementsByTagName( $get )->item( $index );
		}
	}
	return $element;
}


function mw_DOMinnerHTML(DOMNode $element) 
{ 
    $innerHTML = ""; 
    $children  = $element->childNodes;

    if( $children )
    {

	    foreach ($children as $child) 
	    { 
	        $innerHTML .= $element->ownerDocument->saveHTML($child);
	    }

	    return $innerHTML; 

	}
	else
	{
		return '';
	}
}

function mw_DOM_appendHTML(DOMNode $parent, $source) {
    $tmpDoc = new DOMDocument();
    $tmpDoc->loadHTML( '<meta http-equiv="content-type" content="text/html; charset=' . get_bloginfo( 'charset' ) . '">' . $source);
    $nodes = $tmpDoc->getElementsByTagName('body')->item(0)->childNodes;
    if( !empty( $nodes ) )
    {
	    foreach ( $nodes as $node) {
	        $node = $parent->ownerDocument->importNode($node, true);
	        $parent->appendChild($node);
	    }
	}
}

function mtw_exclude_get($url)
{
	$url = preg_replace("#\?.*#", "", $url);
	return $url;
}

function mw_dom_cloneNode($node,$doc){

    $nd=$doc->createElement($node->nodeName);
           
    foreach($node->attributes as $value)
        $nd->setAttribute($value->nodeName,$value->value);
           
    if(!$node->childNodes)
        return $nd;
               
    foreach($node->childNodes as $child) {
        if($child->nodeName=="#text")
            $nd->appendChild($doc->createTextNode($child->nodeValue));
        else
            $nd->appendChild(mw_dom_cloneNode($child,$doc));
    }      
    return $nd;
}

function mw_dom_add_class($item, $classname)
{
	$class = $item->getAttribute('class');
	$class.= ' '.$classname;
	$item->setAttribute('class', $class);
	$class = $item->getAttribute('data-mwclass');
	$class.= ' '.$classname;
	$item->setAttribute('data-mwclass', $class);
}
function mw_data_class()
{
	?>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$(window).on('mtw_muse_bp_change', function(event) {
				$("[data-mwclass]").each(function(index, el) {
					$(el).addClass( $(el).attr('data-mwclass') );
				});
			});
		});
	</script>
	<?php
}
add_action( 'wp_footer', 'mw_data_class' );

function mw_dom_getElementsByClass($dom, $classname, $item = NULL)
{
	$finder = new DomXPath($dom);
	if($item == NULL){
		return $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");
	}else{
		return $finder->query(".//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]", $item);
	}
}

global $script_matches;
$script_matches = array();

function mw_exclude_html_dom_bug($content)
{
	$content = str_replace("<!--[if lt IE 9]>", "<!--[if lt IE 9]-->", $content);
	$content = str_replace("<!--[if lt IE 8]>", "<!--[if lt IE 8]-->", $content);
	$content = str_replace("<![endif]-->", "<!--[endif]-->", $content);

	global $script_matches;
	preg_match_all('#<script(.*?)>(.*?)</script>#is', $content, $matches);
	$script_matches = array_merge($script_matches, $matches[2]);

	$exclude_scripts = array( "#scripts/4.0/jquery-1.8.3.min.js#" , "#scripts/jquery-1.8.3.min.js#" );
	
	foreach ($script_matches as $key => $value) {
		if( trim( strip_tags( $value ) ) != ''  )
		{
			$matches_2 = false;
			foreach ($exclude_scripts as $pattern)
			{
				if ( preg_match_all($pattern, $value, $matches_2_result) )
				{
					$matches_2 = true;
				} 
			}

			if( $matches_2 )
			{
				$content = str_replace( $value, "", $content);	
			}
			else
			{
				$content = str_replace( $value, "{mtw_script_".$key."}", $content);	
			}
			
		}
	}
	return $content;
}

function mw_restore_html_dom_bug($content)
{
	$content = str_replace("<!--[if lt IE 9]-->", "<!--[if lt IE 9]>", $content);
	$content = str_replace("<!--[if lt IE 8]-->", "<!--[if lt IE 8]>", $content);
	$content = str_replace("<!--[endif]-->", "<![endif]-->", $content);
	

	global $script_matches;
	foreach ($script_matches as $key => $value) {
		$value = apply_filters( 'mw_muse_scripts_inline_filter', $value );
		$content = str_replace( "{mtw_script_$key}", $value, $content);
	}
	return $content;
}


function mw_repaire_link_script($html)
{
	global $folderName;
	global $deviceType;

	$link_script = array(
		'link' => array('href'),
		'script' => array('src', 'data-main'),
		'form' => array('action'),
		'img' => array('src', 'data-src', 'data-orig-src', 'data-mu-svgfallback', 'data-hidpi-src', 'data-muse-src'),
		'iframe' => array('src'),
		'object' => array('data'),
		'meta' => array('content', 'data-src'),
		'source' => array( 'src' ),
		'div' => array( 'data-preloadimg' )
		);

	foreach ($link_script as $tag => $types) 
	{
		$links = $html->getElementsByTagName( $tag );
		foreach ($links as $link) 
		{
			foreach ($types as $type) 
			{				

				if( $link->nodeName == "meta" && !file_exists( TTR_MW_TEMPLATES_PATH . $folderName . "/" . $link->getAttribute($type) ) )
				{
					continue;
				}

				if( strpos( $link->getAttribute( $type ) , "//" ) === false && /*strpos( $link->getAttribute( $type ) , "assets" ) === false  &&*/ $link->getAttribute( $type ) )
				{
					$newLink =  TTR_MW_TEMPLATES_URL . $folderName . "/" . $link->getAttribute($type);
					$newLink = str_replace( $deviceType."/../", '', $newLink);
					$link->setAttribute( $type , $newLink  );
				}
				
				if( strpos( $link->getAttribute( $type ), 'http://muse-to-wordpress.net/mtw-script' ) === 0 ){
					$newLink = str_replace( 'http://muse-to-wordpress.net/mtw-script' , 'https://muse-to-wordpress.net/mtw-script' , $link->getAttribute( $type ) );
					$link->setAttribute( $type, $newLink);
				}

				if( strpos( $link->getAttribute( $type ), 'https://muse-to-wordpress.net/mtw-script' ) === 0 ){
					$newLink = str_replace( 'https://muse-to-wordpress.net/mtw-script' , TTR_MW_PLUGIN_URL.'mtw-script' , $link->getAttribute( $type ) );
					$link->setAttribute( $type, $newLink);
				}
			}
		}
	}
}

function mw_repaire_responsive($html)
{

	global $folderName;
	global $deviceType;
	global $wp_filesystem;
	WP_Filesystem();

	$links = $html->getElementsByTagName( 'link' );
	//needed for remove tag after foreach, dont work directly in first foreach
	$remove_only_screen = array();

	//find responsive muse methode
	foreach ($links as $link) 
	{
		if( strpos( strtolower( $link->getAttribute( 'media' ) ) , "only screen" ) !== false 
			&& strpos( strtolower( $link->getAttribute( 'href' ) ) , "/" . $deviceType . "/" ) !== false  )
		{
			$responsive = explode("/" . $deviceType . "/", $link->getAttribute( 'href' ) ) ;
			$responsive = TTR_MW_TEMPLATES_URL . $folderName . "/" . $deviceType . "/" . $responsive[1];
			if( $content = $wp_filesystem->get_contents( $responsive ) )
			{
				$folderName = $folderName . "/" . $deviceType;

				$content = mw_exclude_html_dom_bug( $content );
				$html->loadHTML( $content );
				
			}
			
		}
		elseif( strpos( strtolower( $link->getAttribute( 'media' ) ) , "only screen" ) !== false  )
		{
			$remove_only_screen[] = $link;
		}
	}
	
	//remove tag
	foreach($remove_only_screen as $remove)
	{
		$remove->parentNode->removeChild($remove); 
	}


}

function mtw_array_encode($string)
{
	if ( preg_match( "#\[.*\]#" , $string ) )
	{
		return explode( "," , str_replace( " " , "" , preg_replace( "#\[|\]#" , "" , $string ) ) );
	}
	elseif ( preg_match( "#,#" , $string ) ) 
	{
		return str_replace( " " , "" , $string );
	}
	else
	{
		return $string;
	}
}

function mtw_dynamic_item($item)
{
	global $tempDOMDocument;
	global $html;

	$function = $item->getAttribute( 'data-function' );
	$filter = $item->getAttribute( 'data-filter' );
	
	if($function){
		
		$function = do_action( $function );
		if( preg_match('#\w#', do_shortcode( $function ) ) )
		{
			$item->nodeValue = "";

			@$tempDOMDocument->loadHTML( do_shortcode( $function ) );
			$childNodes = $html->importNode( $tempDOMDocument->getElementsByTagName('body')->item(0) , true);
			$item->appendChild( $childNodes );	
		}

			
	}
	if($filter){
		apply_filters( $filter , $item , get_post() );
	}
}

function mtw_post_list_query($html)
{
	global $mtwQuery;

	$metas = $html->getElementsByTagName( 'meta' );
	$queries = array();
	$groups = array();

	//list mtw query
	foreach ($metas as $meta) 
	{
		if( $meta->getAttribute( 'name' ) == "mtw-query" )
		{
			$group = '';
			$query = array();
			foreach ($meta->attributes as $attribute) 
			{
				if( $attribute->name != "name" and trim( $attribute->value ) )
				{
					if($attribute->name == "group"){
						$group = $attribute->value;
						$groups[] = $group;
					}else{

						$paramValue = trim( $attribute->value );

						//ckeck & return array if mtw array structure [val,val]
						$paramValue = mtw_array_encode($paramValue);
						if( ( is_array( $paramValue ) && !empty( $paramValue[0] ) ) || ( is_string( $paramValue ) && $paramValue != "" ) )
						{
						//define query line
						$query[$attribute->name] = $paramValue;
						}
						
					}
					
				}
			}
			$queries[] = array(
				'elem' 	=> $meta,
				'group' => $group,
				'query' => $query,
				);
			
		}
	}


	foreach ($queries as $query) 
	{
		
		//remove meta tag mtw-query
		$query['elem']->parentNode->removeChild($query['elem']);
		//clean array
		unset($query['elem']);

		//get item by query
		$items = array();
		$noIndexed = array();
		$spans = $html->getElementsByTagName( 'span' );
		foreach ($spans as $span) 
		{
			if( $span->getAttribute( 'data-group' ) == $query['group'] 
				&& $span->hasAttribute('class') 
				&& $span->hasAttribute('data-index') 
				&& strpos($span->getAttribute('class'), 'mtw-dynamic-item') !== false )
			{
				//get index
				$index = $span->getAttribute( 'data-index' ) - 1;
				//construct item call order based on muse index
				$items[$index][] = $span;
			}
			elseif ( $span->getAttribute( 'data-group' ) == $query['group'] 
				&& $span->hasAttribute('class') 
				&& !$span->hasAttribute('data-index') 
				&& strpos($span->getAttribute('class'), 'mtw-dynamic-item') !== false )
			{
				$noIndexed[] =  $span;
			}
		}
		
		//clean array
		unset($query['group']);
		$query = $query["query"];
		$query['posts_per_page'] = count( $items );

		//$query = apply_filters('mtw_query_filter', $query);
		$mtwQuery = $query;

		foreach ($noIndexed as $item) {
			mtw_dynamic_item($item);
		}

		$query = $mtwQuery;

		$result = new WP_Query($query);
		$count = 0;
		//use wordpress loop and replace nodevalue

		if ( $result->have_posts() ) :
		while ( $result->have_posts() ) : $result->the_post();
			
			foreach ($items[$count] as $key => $item) 
			{
				//if a tag get function to parent node and get permalink
				mtw_dynamic_item($item);
				
			}
			$count++;
		endwhile;
		endif;

		wp_reset_postdata();

		//clean if more listed post in muse that in wordpress
		if( $query['posts_per_page'] > $count )
		{
			
			foreach ($items as $key => $subitems) {
				if($key >= $count)
				{
					foreach ($subitems as $key => $item) 
					{
						$item->parentNode->removeChild($item);
					}
				}
			}
		}		
	}
}

function mtw_orphan_query($html){

	global $wp_query;


	if ( $wp_query->have_posts() ) :
	while ( $wp_query->have_posts() ) : $wp_query->the_post();

		$spans = $html->getElementsByTagName( 'span' );
		foreach ($spans as $span) 
		{
			if( ( trim( $span->getAttribute( 'data-group' ) ) == "" || !$span->hasAttribute('data-group') )
				&& $span->hasAttribute('class') 
				&& $span->hasAttribute('data-index') 
				&& strpos($span->getAttribute('class'), 'mtw-dynamic-item') !== false )
			{
				mtw_dynamic_item($span);
			}
		}

	endwhile;
	endif;
	
	wp_reset_postdata();
}

function mtw_inner_url_replace($html)
{
	global $folderName;
	global $projectName;

	$options = get_option( "muse_linker_replace_".strtolower($projectName));
	if($options)
	{
		$museLinkers = json_decode( $options , true );
		$museLinkersFlip = array_flip( $museLinkers );

		$as = $html->getElementsByTagName( 'a' );
		foreach ($as as $a) 
		{
			if( in_array($a->getAttribute( 'href' ), $museLinkersFlip) )
			{
				$museLinkerId = $museLinkers[$a->getAttribute( 'href' )];
				$a->setAttribute( "href" , get_permalink( $museLinkerId ) );		
			}
		}
	}
}

function mw_dom_merge_child_nodes($receiver, $tagReceiver, $from, $tagFrom, $index )
{
	$childNodes = $from->getElementsByTagName( $tagFrom )->item($index)->childNodes;

	foreach ( $childNodes as $childNode) {

		$childNode = $receiver->importNode($childNode, true);
		$receiver->getElementsByTagName( $tagReceiver )->item($index)->appendChild( $childNode );
	}
}

function mtw_post_list_thumbnail($item, $post)
{
	$value = $item->childNodes->item(1)->nodeValue;
	preg_match_all("#background-image[ ]*[:][ ]*url\([ ]*[\'|\"](.*)[\'|\"][ ]*\)[ ]*;#", $value, $matches);
	$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), "thumbnail" );
	if(!$thumb)
	{
		$thumb = array();
		$thumb[0] = TTR_MW_PLUGIN_URL."images/no-image.png";
	}
	$value = str_replace($matches[1][0], $thumb[0], $value);
	$item->childNodes->item(1)->nodeValue = $value;
}
add_filter('mtw_post_list_thumbnail', 'mtw_post_list_thumbnail',10,3);


function mtw_post_list_nav($item, $post)
{
	global $mtwQuery;
	global $tempDOMDocument;
	global $html;
	$pageQuery = new WP_Query($mtwQuery);


	$mtwQuery['paged'] = max( 1, get_query_var('paged') );

	$big = "99";

	$mid_size = 6 - max( 1, get_query_var('paged') );
	if($mid_size < 1) $mid_size = 1;


	$pagination = paginate_links( array(
		'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
		'format' => '?paged=%#%',
		'current' => max( 1, get_query_var('paged') ),
		'total' => $pageQuery->max_num_pages,
		'mid_size' => $mid_size,
		'end_size' => 3
	) );
	if($pagination)
	{
		$item->nodeValue = "";
		$tempDOMDocument->loadHTML( $pagination );
		$childNodes = $html->importNode( $tempDOMDocument->getElementsByTagName('body')->item(0) , true);
		$item->appendChild( $childNodes );
	}else{
		$item->nodeValue = "no more result";
	}
}

add_filter('mtw_post_list_nav', 'mtw_post_list_nav',10,3);

function mtw_replace_inline($html)
{
	global $folderName;
	global $mtw_page;

	$replace = array();
	$by = array();

	$replace[] = "#\.\./images/#";
	$by[] = TTR_MW_TEMPLATES_URL . $folderName. '/images/';

	/*$replace[] = "#url\(assets/#";
	$by[] = 'url('.TTR_MW_TEMPLATES_URL . $folderName. '/assets/';*/

	/*$replace[] = "#\'assets/#";
	$by[] = '\''.TTR_MW_TEMPLATES_URL . $folderName. '/assets/';	

	$replace[] = "#\"assets/#";
	$by[] = '\"'.TTR_MW_TEMPLATES_URL . $folderName. '/assets/';*/

	$replace[] = "#assets/#";
	$by[] = TTR_MW_TEMPLATES_URL . $folderName. '/assets/';

	$replace[] = "#tablet/".$mtw_page->base_name."#";
	$by[] = '?devicelock=tablet';

	$replace[] = "#phone/".$mtw_page->base_name."#";
	$by[] = '?devicelock=phone';


	$html = preg_replace( $replace , $by, $html);

	return $html;
}
add_filter( "mw_muse_scripts_inline_filter", 'mtw_replace_inline' );
add_filter( "mw_muse_styles_inline_filter", 'mtw_replace_inline' );
add_filter( "mw_muse_item_styles_inline_filter", 'mtw_replace_inline' );

//add_filter( "body_html_filter", 'mtw_replace_inline' );
//add_filter( "head_html_filter", 'mtw_replace_inline' );
//add_filter( "muse_footer_html_filter", 'mtw_replace_inline' );

function mtw_replace_inline_dom_each( $dom )
{

	$elements = $dom->getElementsByTagName( 'style' );
	foreach ($elements as $element) 
	{
		$element->nodeValue = apply_filters( 'mw_muse_styles_inline_filter', $element->nodeValue );
	}
}
add_action( 'DOMDocument_mtw_head_load', 'mtw_replace_inline_dom_each' );
add_action( 'DOMDocument_body_load', 'mtw_replace_inline_dom_each' );


?>