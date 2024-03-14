<?php 
/***************
 * Author: Rahul Negi
 * Team: InfoTheme
 * Date: 30-6-2022
 * Desc: Frontend voting actions will be here... for post type it_epoll_poll
 * Happy Coding.....
 **************/
if(!function_exists('ajax_it_epoll_vote')){
add_action( 'wp_ajax_it_epoll_vote', 'ajax_it_epoll_vote' );
add_action( 'wp_ajax_nopriv_it_epoll_vote', 'ajax_it_epoll_vote' );

function ajax_it_epoll_vote() {
	
	if(isset($_POST['action']) and $_POST['action'] == 'it_epoll_vote')
	{
		$wp_nonce ='';
		if(isset($_POST['wp_nonce'])){
			$wp_nonce = sanitize_text_field($_POST['wp_nonce']);
		}
		//Wp Nonce Security Check
		if ( ! wp_verify_nonce( $wp_nonce, 'it_epoll_poll' ) ){
			die(wp_json_encode(array("voting_status"=>"error","msg"=>"Security Check Failed: Please Refresh The Page!")));
		}
	
		it_epoll_init_unique_vote_session();

		if(isset($_POST['poll_id'])){
		$poll_id = intval(sanitize_text_field($_POST['poll_id']));
		}

		if(isset($_POST['option_id'])){
		$option_id = sanitize_text_field($_POST['option_id']);
		}
		
		$fingerprint ='';
		if(isset($_POST['fingerprint'])){
			$fingerprint = sanitize_text_field($_POST['fingerprint']);
		}
		
		//Validate Poll ID
		if ( ! $poll_id ) {
		  $poll_id = '';
		  it_epoll_generate_unique_vote_session('it_epoll_session',$poll_id);
		  die(wp_json_encode(array("voting_status"=>"error","msg"=>"Fields are required")));
		}

		

		//Validate Option ID
		if ( ! $option_id ) {
		  $option_id = '';
		  it_epoll_generate_unique_vote_session('it_epoll_session',$poll_id);
		 die(wp_json_encode(array("voting_status"=>"error","msg"=>"Fields are required")));
		}

		if(!it_epoll_check_for_unique_voting($poll_id,$option_id)){
			//voting action here
			do_action('it_epoll_make_contest_voting_action', array('poll_id'=>$poll_id, 'option_id'=>$option_id,'data'=>array(),'fingerprint'=>$fingerprint));
		}else{
			it_epoll_generate_unique_vote_session('it_epoll_session',$poll_id);
			die(wp_json_encode(array("voting_status"=>"error","msg"=>__('You Already Voted For This Candidate!','it_epoll'))));
		}

	}else{
		$outputdata['voting_status'] = "error";
		$outputdata['msg'] = __('Something Went Wrong','it_epoll');
		it_epoll_generate_unique_vote_session('it_epoll_session');
		print_r(wp_json_encode($outputdata));
	}
	die();
}
}

	//Adding Vote action to opinion single voting
if(!function_exists('it_epoll_addon_default_contest_vote_action')){
	add_action('it_epoll_make_contest_voting_action','it_epoll_addon_default_contest_vote_action');
	function it_epoll_addon_default_contest_vote_action($args){
		
		$poll_id = $args['poll_id'];
		$option_id = $args['option_id'];
		$data = $args['data'];
		$fingerprint = $args['fingerprint'];

		$oldest_vote = 0;
		$oldest_total_vote = 0;
		if(get_post_meta($poll_id, 'it_epoll_vote_count_'.$option_id,true)){
			$oldest_vote = get_post_meta($poll_id, 'it_epoll_vote_count_'.$option_id,true);	
		}
		if(get_post_meta($poll_id, 'it_epoll_vote_total_count')){
			$oldest_total_vote = get_post_meta($poll_id, 'it_epoll_vote_total_count',true);	
		}

		$new_total_vote = intval($oldest_total_vote) + 1;
		$new_vote = (int)$oldest_vote + 1;
		update_post_meta($poll_id, 'it_epoll_vote_count_'.$option_id,$new_vote);
		update_post_meta($poll_id, 'it_epoll_vote_total_count',$new_total_vote);

		$outputdata = array();

		$it_epoll_poll_option_ids = get_post_meta( $poll_id, 'it_epoll_poll_option_id', true );
		$it_epoll_poll_vote_total_count = (int)get_post_meta($poll_id, 'it_epoll_vote_total_count',true);
			$i=0;
			$optArray = array();
			if($it_epoll_poll_option_ids){

				foreach($it_epoll_poll_option_ids as $it_epoll_poll_option_id){
					$it_epoll_poll_vote_count = (int) get_post_meta($poll_id, 'it_epoll_vote_count_'.$it_epoll_poll_option_id,true);
				
					$it_epoll_poll_vote_percentage =0;
					if($it_epoll_poll_vote_count == 0){
					$it_epoll_poll_vote_percentage =0;
					$it_epoll_poll_vote_count_text = __("No Vote",'it_epoll'); 
					}elseif($it_epoll_poll_vote_count == 1){
						$it_epoll_poll_vote_count_text = sprintf(it_epoll_poll_get_ttext('it_epoll_settings_vote_number_text'),$it_epoll_poll_vote_count);
						$it_epoll_poll_vote_percentage = (int)$it_epoll_poll_vote_count*100/$it_epoll_poll_vote_total_count; 
						
					}else{
						$it_epoll_poll_vote_percentage = (int)$it_epoll_poll_vote_count*100/$it_epoll_poll_vote_total_count; 
						$it_epoll_poll_vote_count_text = sprintf(it_epoll_poll_get_ttext('it_epoll_settings_vote_numbers_text'),$it_epoll_poll_vote_count);
					}
					$it_epoll_poll_vote_percentage = (int)$it_epoll_poll_vote_percentage;
					
					array_push($optArray,array('option_id'=>$it_epoll_poll_option_id,'vote_count'=>$it_epoll_poll_vote_count,'vote_percentage'=> $it_epoll_poll_vote_percentage.'%'));
					
				}

			}


		$outputdata['options'] = $optArray;	
		$outputdata['total_vote'] = $new_total_vote;
		$outputdata['total_opt_vote_count'] = $new_vote;
		$outputdata['option_id'] = $option_id;
		$outputdata['voting_status'] = "done";
		$outputdataPercentage = ($new_vote*100)/$new_total_vote;
		$outputdata['total_vote_percentage'] = (int)$outputdataPercentage;
		
		if(get_post_meta($poll_id,'it_epoll_poll_multichoice',true)){
			it_epoll_generate_unique_vote_session('it_epoll_session_'.$option_id,$poll_id);
		}else{
			it_epoll_generate_unique_vote_session('it_epoll_session_'.$poll_id,$poll_id);
		}


		$args['poll_id'] = $poll_id;
		$args['option_id'] = $option_id;
		$args['voter_ip'] = $fingerprint;
		$args['status'] = 0;
		it_epoll_saveIPBasedData($args);
		
		print_r(wp_json_encode($outputdata));
		exit;
	}
}