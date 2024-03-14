<?php
defined( 'ABSPATH' ) or exit;
class wmamc_reviewimport {
	
	public function __construct() {
		
	}
	public static function wmamc_review_importer($temp_name){	

		$csv = array_map('str_getcsv', file($temp_name));
		
		array_walk($csv, function(&$a) use ($csv) {
			
			$temporary = array_intersect_key($csv[0], $a );
			$a = array_combine( array_values($temporary), array_values($a) );
			
		});
		array_shift($csv);	
		
		return wmamc_reviewimport::wmamc_import_reviews($csv);	
	}
		
	public function wmamc_import_reviews($reviewData){
		
		global $wpdb, $wc;	
		
		$commnet_issues = array();
		$commnet_metaissues = array();
		
		foreach($reviewData as $reviewitem){
			
			$reviewRecord = array();
			$reviewMetaRecord = array();

			foreach($reviewitem as $attr=>$s_item){				
				$type= explode(':',$attr);
				
				if($type[0] == 'data'){
					$reviewRecord[$type['1']] = $s_item;
				}else{
					$reviewMetaRecord[$type['1']] = $s_item;
				}

			}
			
			/* check if product exist */
			
			$post = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts WHERE $wpdb->posts.post_type = 'product' AND $wpdb->posts.ID = $reviewRecord[comment_post_ID] " );
			if($post > 0){ 
			
			/*insert review data*/
			$data = array(
				'comment_post_ID' => $reviewRecord['comment_post_ID'],
				'comment_author' =>$reviewRecord['comment_author'],
				'comment_author_email' => $reviewRecord['comment_author_email'],				
				'comment_content' => $reviewRecord['comment_content'],
				'comment_type' => $reviewRecord['comment_type'],
				'comment_parent' => $reviewRecord['comment_parent'],
				'user_id' => $reviewRecord['user_id'],
				'comment_author_IP' => $reviewRecord['comment_author_IP'],
				'comment_agent' => $reviewRecord['comment_agent'],
				'comment_date' => $reviewRecord['comment_date'],
				'comment_approved' => $reviewRecord['comment_approved'],
			);

			$new_commnet_id = wp_insert_comment($data);
			
			/*insert review meta data*/
			if($new_commnet_id){
				foreach($reviewMetaRecord as $meta_key=>$meta_value){
					
					$response = add_comment_meta( $new_commnet_id, $meta_key, $meta_value );
					if($response == false){
						$commnet_metaissues[] = array($new_commnet_id, $meta_key, $meta_value);
					}
				}
			}	
			
		   }else{
			   $commnet_issues[] = array($reviewRecord['comment_post_ID'],'Skipped... Product ID not found. ');
		   }
		}
		
		if(!empty($commnet_metaissues) || !empty($commnet_issues)){
			
			$return['status'] = 'error';
			$return['data'] = array('review'=>$commnet_issues,'meta'=>$commnet_metaissues);
			
		}else{
			$return['status'] = 'success';
		
		}
		return json_encode($return);
		
		exit;
		
	}

}
 ?>