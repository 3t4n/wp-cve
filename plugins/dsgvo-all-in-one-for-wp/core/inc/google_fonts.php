<?php

$priority = 9;

add_action('wp_loaded', function(){
	
	ob_start('dsgvoaio_googlefonts_func');
	
}, $priority);


add_action('shutdown', function(){
	
	if (ob_get_length()) {
		
		ob_end_flush();
		
	}
	
}, -1 * $priority);

function dsgvoaio_googlefonts_func($content_of_the_buffer){

	$html = $content_of_the_buffer;
		
	global $wpdb;
	
	$now = new DateTime();
	
	$last_run = get_option("dsdvo_last_run");
	
	$x = 0;
	
	$x2 = 0;	

	if (empty($last_run) or $now->getTimestamp() > $last_run + 900) {
		
		$html = preg_replace_callback('#(<link[^>]*(stylesheet|as=.?style)[^>]*>)#is', function($sheets){

			if(isset($sheets[0])){
						
			preg_match('#href=["\']?([^"\'>]+)["\']?#is', $sheets[0], $url);
					
				if (!empty($url[1])) {
					
					if (stripos($url[1], 'fonts.googleapis.com') !== false) {
									
						if ($url[1] != "") {

							update_option('dsgvoaio_gfonts_stylesheet_url_'. sanitize_url(urldecode($url[1])), sanitize_url(urldecode($url[1])), false);							
											
							return $sheets[0];
								
						} else {
										
							return $sheets[0];
										
						}
								
					} else {
									
						return $sheets[0];
									
					}
								
				} else  {
								
					return $sheets[0];	
							
				}

			} else {
					
				return $sheets[0];
					
			}
				
		}, $html);	


		$changed = false;

		preg_match_all('#@import\s+url\( ([\"\']?|\s+) ([^"\'\s]*) (?:\\1|\s+) \)[^;]*;#six', $html, $imports);

		$all_imports = array();

		$count1 = 0;
		
		$count2 = 0;
		
		if (isset($imports[0][0])) {
				
			foreach ($imports[2] as $key => $import) {
			
				if (stripos($import, 'fonts.googleapis.com/css') === false) { continue; }
					
					update_option('dsgvoaio_gfonts_import_'.$count1++, sanitize_url($import));

					update_option('dsgvoaio_gfonts_import_count', $count2++);
					
					$changed = true;

			} 
			
		} else {
				
			$count = get_option('dsgvoaio_gfonts_import_count');
				
			foreach (range(0, $count) as $i) {
					
				delete_option('dsgvoaio_gfonts_import_'.$i);
					
			}
			
		}


		preg_match_all('#<script[^>]*>[^>]*WebFont.load[^>]*</script>#Usi', $html, $scripts);

		if (isset($scripts[0][0])) {
			
			$c = "";
			
			$f = "";
			
			foreach ($scripts[0] as $script) {
				
				preg_match('#families[^>]*[[^>]*]#is', $script, $webfonts);
			
				$fonts = str_replace('\'','"', $webfonts[0]);
				
				$fonts = str_replace('families:','', $fonts);
				
				$fonts = str_replace(', ',',', $fonts);
				
				$fonts = str_replace(' ','+', $fonts);
				
				$c .= $fonts;

				if ($fonts != "") {
					update_option('dsgvoaio_gfonts_webfontloader', sanitize_text_field($fonts));
				
				}
				
			}
		
		} else {
			
			update_option('dsgvoaio_gfonts_webfontloader', '');
			
			delete_option('dsgvoaio_gfonts_webfontloader');
			
		}

		$c = "";
		
		$f = "";
		
		if (isset($scripts[0][0])) {
			
			foreach ($scripts[0] as $script) {
				
				preg_match('#families[^>]*[[^>]*]#is', $script, $webfonts);
				
				$scripts = explode('}', $webfonts[0]);
				
				$fonts = str_replace('\'','', $scripts[0]);
				
				$fonts = str_replace('[','', $fonts);
				
				$fonts = str_replace(']','', $fonts);
				
				$fonts = str_replace(' ','', $fonts);
				
				$fonts = str_replace('++','+', $fonts);
				
				$fonts = str_replace('families','', $fonts);

				$c .= $fonts;
				
				update_option('dsgvoaio_gfonts_webfontloader_2', sanitize_text_field($fonts));
				
			}

		} else {
			
			delete_option('dsgvoaio_gfonts_webfontloader_2');
			
		}


		$all_gfonts = array();

		$webfonts = get_option('dsgvoaio_gfonts_webfontloader');

		$webfonts2 = get_option('dsgvoaio_gfonts_webfontloader_2');

		$imports_count = get_option('dsgvoaio_gfonts_import_count');

		$stylesheet_count = get_option('dsgvoaio_gfonts_stylesheet_count');

		$stylesheet_urls = $wpdb->get_results( "SELECT * FROM $wpdb->options WHERE (`option_name` LIKE  '%dsgvoaio_gfonts_stylesheet_url_%')" );

		$infilefontcount = get_option('dsgvoaio_gfonts_infilecount');

		if (isset($infilefontcount)) {
			
			foreach(range(0, $infilefontcount) as $number) {
				
				$fonturi = get_option('dsgvoaio_gfonts_infile_'.$number);
				
				if (isset($fonturi)) {
				
					$all_gfonts[] = $fonturi;
					
				}
				
			}
			
		}

		if (isset($webfonts)) {
			
			$webfonts_json = json_decode(ltrim($webfonts, '+'));
			
			if (isset($webfonts_json) && $webfonts_json != "") {
				
				foreach ($webfonts_json as $webfont) {
					
					if ($webfont != "") {
						
						$all_gfonts[] = 'https://fonts.googleapis.com/css?family='.ltrim($webfont, '+');
						
					}
					
				}
				
			}
			
		}

		if (isset($webfonts2)) {
			
			$webfonts2 = substr($webfonts2, 1);
			
			$webfonts2 = explode('","', $webfonts2);
			
			$end_fonts = "";
			
			foreach ($webfonts2 as $webfont2) {
				
				$webfont2 = str_replace('"', '', $webfont2);
				
				$webfont2 = str_replace('regular,regular', 'regular', $webfont2);
				
				$webfont2 = str_replace('regular,', '', $webfont2);
				
			}

			$end_fonts = $webfont2;
			
			if (isset($end_fonts) && $end_fonts != "") {
				
				$all_gfonts[] = 'https://fonts.googleapis.com/css?family='.str_replace(',', '|', $end_fonts);
				
			}
			
		}

		if (isset($imports_count)) {
			
			foreach (range(0, $imports_count) as $i) {
				
				$import = get_option('dsgvoaio_gfonts_import_'.$i);
				
				if (isset($import) && $import != "") {
					
					if(strpos($import, "http://") !== false or strpos($import, "https://") !== false) {
						
						$all_gfonts[] = $import;
						
					} else {
						
					$all_gfonts[] = 'https:'.$import;
					
					}
					
				}
				
			}
			
		}

		if (isset($stylesheet_urls)) {
			
			foreach ($stylesheet_urls as $stylesheet_url) {
				
				if (isset($stylesheet_url->option_value)) {
					
					if(strpos($stylesheet_url->option_value, "http://") !== false or strpos($stylesheet_url->option_value, "https://") !== false) {
						
						$all_gfonts[] = $stylesheet_url->option_value;
						
					} else {
						
					$all_gfonts[] = 'https:'.$stylesheet_url->option_value;	
					
					}
					
				}
				
			}
			
		}

		if (isset($all_gfonts[0])) {

			$storeddata =serialize(get_option('dsgvoaio_gfonts_all_fonts_new'));

			if ($storeddata !== serialize($all_gfonts)) {
						
				update_option('dsgvoaio_gfonts_all_fonts_new', $all_gfonts);
						
			} else {
						
				update_option('dsgvoaio_gfonts_all_fonts', $all_gfonts);
						
			}

			$gfonts_new =serialize(get_option('dsgvoaio_gfonts_all_fonts_new'));

			$gfonts_old =serialize(get_option('dsgvoaio_gfonts_all_fonts'));

			if ($gfonts_new !== $gfonts_old or isset($_GET['dsgvoaio_scan_googlefonts']) && $_GET['dsgvoaio_scan_googlefonts']  == 'true' && isset($_GET['status']) && $_GET['status'] == 'success') {
						
				update_option('dsgvoaio_gfonts_all_fonts', $all_gfonts);
						
				$wpdb->get_results( "DELETE FROM $wpdb->options WHERE (`option_name` LIKE '%dsgvoaio_gfonts_stylesheet_url_%')" );
						
				delete_option('dsgvoaio_gfonts_webfontloader_2');
						
				update_option('dsgvoaio_gfonts_webfontloader', '');
						
				delete_option('dsgvoaio_gfonts_webfontloader');

			}

		}	
			
		update_option("dsdvo_last_run", $now->getTimestamp());		

	}

	return $html;
		
}
	
?>