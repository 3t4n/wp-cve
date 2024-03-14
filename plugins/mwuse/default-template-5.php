<?php

global $mtw_head;
global $wp_head;
global $body_loaded;
global $muse_footer;
global $mtw_page;
global $load_header_mtw;
global $load_footer_mtw;
global $mtw_version;



$page = new MusePage;
$page->init( str_replace( TTR_MW_TEMPLATES_PATH , "", $museUrl ) );
$mtw_page = $page;



$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');


$file = str_replace( TTR_MW_PLUGIN_DIR , TTR_MW_PLUGIN_URL , $museUrl );


$projectName = $folderName;

//MUSE CONTENT
$html = $page->DOMDocument;


$html_class = $html->getElementsByTagName('html')->item(0)->getAttribute('class');

if ( ! session_id() ) 
{
	@session_start();
}
$_SESSION['current_muse_theme'] = $projectName;



?>
<!DOCTYPE html>
<html class="<?php echo $html_class; ?>" lang="<?php echo get_locale(); ?>" >
<head>
	<meta name="generator" content="Muse to WordPress <?php echo $mtw_version; ?>">
	<?php

	//Responsive with mobile detect library 
	//mw_repaire_responsive($html);

	//Change url for script and link tag
	mw_repaire_link_script($html);

	//MTW query
	//mtw_post_list_query($html);

	//MTW ophan query
	//mtw_orphan_query($html);

	?>

	<?php
	$mtw_head = new DOMDocument;
	$mtw_head->loadHTML('<head></head>');
	mw_dom_merge_child_nodes($mtw_head, "head", $html, "head", 0 );

	do_action( "DOMDocument_mtw_head_load", $mtw_head );

	$str_mtw_head = preg_replace(array("/^\<\!DOCTYPE.*?<html><head>/si",
                                      "!</head></html>$!si"),
                                "",
                                mw_restore_html_dom_bug( $mtw_head->saveHTML() ) );
	
	if( $load_header_mtw )
	{
		echo apply_filters( 'head_html_filter', $str_mtw_head);
	}
	
	//get wp_head in a var
	ob_start(); wp_head(); $wp_head = ob_get_clean();

	//mw_exclude_html_dom_bug on wp_head html
	$wp_head = mw_exclude_html_dom_bug($wp_head);

	//start DOMDocument filters on wp head HTML
	$head = new DOMDocument;
	$head->loadHTML( '<meta http-equiv="content-type" content="text/html; charset=' . get_bloginfo( 'charset' ) . '">' . $wp_head );

	//exclude all css and javascript from asigned template
	exclude_template_link_and_script($head);
	
	$wp_head = preg_replace(array("/^\<\!DOCTYPE.*?<html><head>/si",
                                      "!</head></html>$!si"),
                                "",
                                mw_restore_html_dom_bug( $head->saveHTML() ) );

	if( $load_header )
	{
		$wp_head = str_replace('</head>', '', $wp_head) ;
		$wp_head = str_replace('<body>', '', $wp_head) ;
		$wp_head = str_replace('</body>', '', $wp_head) ;
		$wp_head = str_replace('</html>', '', $wp_head) ;
		echo $wp_head;
	}
	?>
	<style type="text/css">
	html
	{
		margin-top: 0px !important;
	}
	</style>
	<script type="text/javascript">
    var $ = jQuery;
    </script>
</head>
<body <?php body_class(); ?> data-project="<?php echo $projectName; ?>">

	<?php

	$mtw_body = new DOMDocument;
	$mtw_body->loadHTML('<body></body>');
	mw_dom_merge_child_nodes($mtw_body, "body", $html, "body", 0 );

	$to_deletes = array();

	$muse_footer = new DOMDocument();
	$muse_footer->loadHTML('<body></body>');
	$muse_footer_body = $muse_footer->getElementsByTagName('body')->item(0);

	//move muse footer
	$sript_for_footer = $mtw_body->getElementsByTagName('script');
	

	foreach ($sript_for_footer as $key => $script) {
		$to_deletes[] = $script;
	}
	foreach ($to_deletes as $key => $to_delete) 
	{
		$nodeImported = $muse_footer->importNode($to_delete, true);
		$muse_footer_body->appendChild($nodeImported);
		
		$to_delete->parentNode->removeChild($to_delete);
	}

	do_action( "DOMDocument_body_load", $mtw_body );

	$str_mtw_body = preg_replace(array("/^\<\!DOCTYPE.*?<html><body>/si",
                                      "!</body></html>$!si"),
                                "",
                                mw_restore_html_dom_bug( $mtw_body->saveHTML() ) );
	
	if( $do_shortcode )
	{
		$str_mtw_body = do_shortcode( $str_mtw_body );
	}

	$str_mtw_body = mw_exclude_html_dom_bug( $str_mtw_body );

	$body_loaded = new DOMDocument;
	$body_loaded->loadHTML( '<meta http-equiv="content-type" content="text/html; charset=' . get_bloginfo( 'charset' ) . '">' . $str_mtw_body );

	do_action( "DOMDocument_body_loaded", $body_loaded );

	$str_mtw_body = preg_replace(array(
									"/^\<\!DOCTYPE.*?<body>/si",
                                    "!</body></html>$!si"),
                                	"",
                                mw_restore_html_dom_bug( $body_loaded->saveHTML() ) );

	
	$str_mtw_body = apply_filters( 'body_html_filter', $str_mtw_body);
	echo $str_mtw_body;
	
	if( $load_footer )
	{
		wp_footer();
	}

	?>
	<script type="text/javascript">
	var $ = jQuery;
	</script>
	<?php
	
	if( $load_footer_mtw )
	{
		echo apply_filters( 'muse_footer_html_filter',
			preg_replace(array(
						"/^\<\!DOCTYPE.*?<body>/si",
	                    "!</body></html>$!si"),
	                	"",
	                 mw_restore_html_dom_bug ( $muse_footer->saveHTML() ) ) );
	}

	do_action( 'mw_after_muse_footer' );
	?>
</body>
</html>