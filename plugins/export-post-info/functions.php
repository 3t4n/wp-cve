<?php

function apa_epi_f_generatepseudorandomstring($length = 6) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function apa_escape_csv_data_array($data_array) {
	$esc_data_arr = array('=', '+', '-', '@');
	//$dataescape = "";
	foreach($data_array as $index => $dataescape){
		if (in_array(mb_substr($dataescape, 0, 1), $esc_data_arr, true)) {
			$dataescape = "'" . $dataescape;
			$data_array[$index] = $dataescape;
		}
	}
	return $data_array;
}

/**
 * @param $selected_post_type
 * @param $export_type
 * @param $additional_data
 */
function apa_epi_f_generate_output(){

	$html = array();
	$counter = 0;
	$str = "";
	
	$line_break = "";

	$posts_query = new WP_Query( array(
		'post_type' => 'post',
		'posts_per_page' => '-1',
		//'post_status' => 'publish',
		//'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash'),
		'post_status' => array('publish', 'future', 'private'),
		'orderby' => 'date',
		'order'   => 'ASC'
	) );

	while ( $posts_query->have_posts() ):
		$posts_query->the_post();
		if (isset ($html['year'][$counter])) {
			$html['year'][$counter] .= get_the_date('Y') . $line_break; // test
		} else {
			$html['year'][$counter] = get_the_date('Y') . $line_break; // this one is ok
		}
		$counter++;
	endwhile;
	$counter = 0;
	while ( $posts_query->have_posts() ):
		$posts_query->the_post();
		$html['date'][$counter] = get_the_date('d-m-Y') .$line_break;
		$counter++;
	endwhile;
	$counter = 0;
	while ( $posts_query->have_posts() ):
		$posts_query->the_post();
		$html['title'][$counter] = get_the_title().$line_break;
		$counter++;
	endwhile;
	$counter = 0;
	while ( $posts_query->have_posts() ):
		$posts_query->the_post();
		$html['url'][$counter] = get_permalink().$line_break;
		$counter++;
	endwhile;
	$counter = 0;
	
	while ( $posts_query->have_posts() ):
		$posts_query->the_post();
		$html['status'][$counter] = get_post_status().$line_break;
		$counter++;
	endwhile;
	$counter = 0;	
	
	while ( $posts_query->have_posts() ):
		$categories = '';
		$posts_query->the_post();
		if ( class_exists('WPSEO_Primary_Term') )
		{
			// Show the post's 'Primary' category, if this Yoast feature is available, & one is set
			$wpseo_primary_term = new WPSEO_Primary_Term( 'category', get_the_id() );
			$wpseo_primary_term = $wpseo_primary_term->get_primary_term();
			$term = get_term( $wpseo_primary_term );
			if (is_wp_error($term)) { 
				$cats = get_the_category();
				$categories = $cats[0]->name;
			} else { 
				// Yoast Primary category
				$category_display = $term->name;
				$categories = $category_display;
			}
		} 
		else {
			$cats = get_the_category();
			$categories = $cats[0]->name;
		}
		$html['category'][$counter] = $categories.$line_break;
		$counter++;	
	endwhile;
	$counter = 0;
	global $more; // Declare global $more (before the loop).
	while ( $posts_query->have_posts() ):
		$posts_query->the_post();
		$more = 1; // Set (inside the loop) to display all content, including text below more.
			//Variable: Additional characters which will be considered as a 'word'
			$char_list = ''; /** MODIFY IF YOU LIKE.  Add characters inside the single quotes. **/
			//$char_list = '0123456789'; /** If you want to count numbers as 'words' **/
			//$char_list = '&@'; /** If you want count certain symbols as 'words' **/
		//$html['words'][$counter] .= str_word_count(strip_tags(get_the_content()), 0, $char_list).$line_break; // Counts wrong words with UTF-8 characters
		$html['words'][$counter] = count(preg_split('~[^\p{L}\p{N}\']+~u',strip_tags(get_the_content()))).$line_break;
		count(preg_split('~[^\p{L}\p{N}\']+~u',$str));
		$counter++;
	endwhile;
	$counter = 0;

	$file_path = wp_upload_dir();
	$file_path = $file_path['basedir'];
	$upload_dir = $file_path;	

	$count = 0;
	foreach($html as $item){
		$count = count($item);
	}

	$file_name = 'export-post-info-' . get_option( 'epi_random_string_filename' ) . '.csv';
	$data = '';
	$headers = array();
	
	$file = $upload_dir . '/' . $file_name;

	$myfile = fopen($file, "w") or die("Unable to create a file on your server! @ " . $file);
	fprintf( $myfile, "\xEF\xBB\xBF");
	
	if ($count > 0) {
			$headers[] = 'Year';
			$headers[] = 'Date';
			$headers[] = 'Title';
			$headers[] = 'Words';
			$headers[] = 'Status';
			$headers[] = 'URLs';
			$headers[] = 'Categories';

			fputcsv($myfile, $headers);

			for( $i = 0; $i < $count; $i++ ){
				$data = array(
					($html['year']) ? esc_html( $html['year'][$i] ) : "",
					($html['date']) ? esc_html( $html['date'][$i] ) : "",
					($html['title']) ? esc_html( $html['title'][$i] ) : "",
					($html['words']) ? esc_html( $html['words'][$i] ) : "",
					($html['status']) ? esc_html( $html['status'][$i] ) : "",
					($html['url']) ? esc_html( $html['url'][$i] ) : "",
					($html['category']) ? esc_html( $html['category'][$i] ) : ""
				);

				$data = apa_escape_csv_data_array($data);
				fputcsv($myfile, $data);
			}

			fclose($myfile);
?>
	<div class='updated'><strong><?php echo $count . ' </strong>'; _e( 'posts exported successfully!', 'export-post-info' ); ?> · <a href="<?php $upload_dir = wp_upload_dir(); echo $upload_dir['baseurl'] . '/export-post-info-' . esc_html( get_option( 'epi_random_string_filename' ) ) . '.csv'; ?>"><strong><?php _e( 'Click here', 'export-post-info' ); ?></strong></a> <?php _e( 'to download the file', 'export-post-info' ); ?>.</div>
<?php
	} else {
	?>
	<div class='updated'><span style="color:red"><strong><?php _e( 'No data to export available!', 'export-post-info' ); ?></strong></span> <?php _e( 'Your WordPress has', 'export-post-info' ); ?> <?php $count_posts = wp_count_posts(); $published_posts = $count_posts->publish; echo $published_posts ?> <?php _e( 'published posts', 'export-post-info' ); ?>. <?php _e( 'Check your posts', 'export-post-info' ); ?> <a href="edit.php"> <?php _e( 'here', 'export-post-info' ); ?></a>.</div>
    
	<?php 
	}
	wp_reset_postdata();
}