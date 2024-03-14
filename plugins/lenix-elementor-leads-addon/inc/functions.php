<?php
function lenix_array_to_csv($array){
   if (count($array) == 0) {
     return null;
   }
	// create a file pointer connected to the output stream
	$output = fopen('php://output', 'w');

	// UTF-8 BOM
	fwrite($output, "\xEF\xBB\xBF");

	// output the column headings
	foreach($array as $fields){
		fputcsv($output, $fields);
	}
	
	return ob_get_clean();
}

function lenix_download_send_headers($filename) {
    // disable caching
    $now = gmdate("D, d M Y H:i:s");
    header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
    header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
    header("Last-Modified: {$now} GMT");

    // force download  
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");

    // disposition / encoding on response body
    header("Content-Disposition: attachment;filename={$filename}");
    header("Content-Transfer-Encoding: binary");
	
}

//hide add new leads in admin bar
function lenix_elementor_leads_css_to_footer(){
	?>
<style>
	#wp-admin-bar-new-elementor_lead {
		display: none;
	}
	body.post-type-elementor_lead .wrap a.page-title-action {
		display: none;
	}
</style>
<?php }
add_action( 'admin_footer', 'lenix_elementor_leads_css_to_footer' );
add_action( 'wp_footer', 'lenix_elementor_leads_css_to_footer' );


function recursive_get_forms_slugs($arr,$id){
	
	global $slugs;
	
	if(!is_array($arr)){
		return $slugs;
	}
	
	foreach($arr as $data){
		if(isset($data['elements']) && !empty($data['elements'])){
			return recursive_get_forms_slugs($data['elements'],$id);
		}

		if(isset($data['templateID']) && $data['templateID'] == $id){
			$slugs[$data['id']] = $data['id'];
			return $slugs;
		}
	}
}

function lenix_get_query_field($field){
	
	if(!isset($_GET[$field])){
		return false;
	}
	
	return sanitize_text_field($_GET[$field]);
	
}