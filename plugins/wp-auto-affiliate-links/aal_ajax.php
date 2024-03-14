<?php



//Delete link button (called by ajax)
function aalDeleteLink(){
	
			if ( ! current_user_can( 'publish_pages' ) ) {
				wp_die();
			}
	
	
	
		  if ( ! wp_verify_nonce( $_POST['aal_nonce'], 'aal-ajax-nonce' ) ) {
         die ( 'no privileges');
     }
    
            if(isset($_POST['id'])){
                global $wpdb;
                $table_name = $wpdb->prefix . "automated_links";
                
                //Security check and input sanitize
		$id = intval(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_SPECIAL_CHARS)); // $_GET['id'];
		
		//Add to database and redirect to the plugin default page
		$wpdb->query("DELETE FROM ". $table_name ." WHERE id = '". $id ."' LIMIT 1");
                
                die();
            }
}


//Update link button (called by ajax)
function aalUpdateLink(){


		if ( ! current_user_can( 'publish_pages' ) ) {
				wp_die();
			}
	
	
	  if ( ! wp_verify_nonce( $_POST['aal_nonce'], 'aal-ajax-nonce' ) ) {
         die ( 'no privileges');
     }
    
            if(isset($_POST['id'])){
                global $wpdb;
                $table_name = $wpdb->prefix . "automated_links";
                
                //Security check and input sanitize
		$id = intval(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_SPECIAL_CHARS)); // $_GET['id'];
		$link = filter_input(INPUT_POST, 'aal_link', FILTER_SANITIZE_SPECIAL_CHARS); // $_POST['link'];
		$keywords = filter_input(INPUT_POST, 'aal_keywords', FILTER_SANITIZE_SPECIAL_CHARS); // $_POST['keywords'];
		$title = filter_input(INPUT_POST, 'aal_title', FILTER_SANITIZE_SPECIAL_CHARS); // $_POST['title'];
		$samelinkmeta = filter_input(INPUT_POST, 'aal_samelinkmeta', FILTER_SANITIZE_SPECIAL_CHARS); // $_POST['title'];
		$disclosureoff = filter_input(INPUT_POST, 'aal_disclosureoff', FILTER_SANITIZE_SPECIAL_CHARS); // $_POST['title'];
		$disabled = filter_input(INPUT_POST, 'aal_disabled', FILTER_SANITIZE_SPECIAL_CHARS); // $_POST['title'];
		
		$stats = '';
		if($disabled) { $stats = 'disabled'; }
		
		$meta = new StdClass();
		$meta->title = $title;
		$meta->samelink = $samelinkmeta;
		$meta->disclosureoff = $disclosureoff;
		
		$linkval = array( 'link' => $link, 'keywords' => $keywords, 'meta' => json_encode($meta), 'stats' => $stats );
		print_r($linkval);
		
		$check = $wpdb->get_results( "SELECT * FROM ". $table_name ." WHERE id = '". $id ."' " );		
		
		// Add to database 
		if($check) { 
				$wpdb->update( $table_name, $linkval , array( 'id' => $id ));
				//$aal_delete_id=$check[0]->id;
			}
			else {
				echo 'something went wrong';
			}
		

                
                die();
            }
}



//Add link form (called by ajax)
function aalAddLink(){
	
			if ( ! current_user_can( 'publish_pages' ) ) {
				wp_die();
			}
	
		
	  if ( ! wp_verify_nonce( $_POST['aal_nonce'], 'aal-ajax-nonce' ) ) {
         die ( 'no privileges');
     }
    
            	global $wpdb;
                $table_name = $wpdb->prefix . "automated_links";



     	
		// Security check and sanitize	
		$aal_link = filter_input(INPUT_POST, 'aal_link', FILTER_SANITIZE_SPECIAL_CHARS); // $_POST['link'];
		$aal_keywords = filter_input(INPUT_POST, 'aal_keywords', FILTER_SANITIZE_SPECIAL_CHARS); // $_POST['keywords'];
		$aal_title = filter_input(INPUT_POST, 'aal_title', FILTER_SANITIZE_SPECIAL_CHARS); // $_POST['title'];
		
		$aal_link = aal_add_http($aal_link);		
		
		$meta = new StdClass();
		$meta->title = $aal_title;
		$jmeta = json_encode($meta);
		
		$check = $wpdb->get_results( "SELECT * FROM ". $table_name ." WHERE link = '". $aal_link ."' " );		
		
		// Add to database 
		if($check) { 
				$wpdb->update( $table_name, array( 'keywords' => $check[0]->keywords .','. $aal_keywords), array( 'link' => $aal_link ) );
				$aal_delete_id=$check[0]->id;
			}
		else {
			$rows_affected = $wpdb->insert( $table_name, array( 'link' => $aal_link, 'keywords' => $aal_keywords, 'meta' => $jmeta ) );
			$aal_delete_id=$wpdb->insert_id;
		} 
		
        
                
                
                $aal_json=array( 'aal_delete_id' => $aal_delete_id, 'aal_new_url' => $aal_link );
                
                echo json_encode($aal_json);
                
                die();
}






?>