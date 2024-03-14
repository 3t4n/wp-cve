<?php
defined('ABSPATH') or die("No direct script access!");

add_action('wp_head', 'qcilist_ajax_ajaxurl');

if(!function_exists('qcilist_ajax_ajaxurl')){
	function qcilist_ajax_ajaxurl() {

	   echo '<script type="text/javascript">
	           	var ajaxurl = "' . admin_url('admin-ajax.php') . '";
	         	var qc_ilist_get_ajax_nonce = "'.wp_create_nonce( 'qc-opd').'";
	         </script>';
	}
}

//Doing ajax action stuff
if(!function_exists('ilist_upvote_ajax_action_stuff')){
	function ilist_upvote_ajax_action_stuff(){

		check_ajax_referer( 'qc-opd', 'security');

		//Get posted items
		$action 	= isset($_POST['action']) 		? trim(sanitize_text_field($_POST['action'])) 		: '';
		$post_id 	= isset($_POST['post_id']) 		? absint(sanitize_text_field($_POST['post_id'])) 	: '';
		$meta_title = isset($_POST['meta_title']) 	? trim(sanitize_text_field($_POST['meta_title'])) 	: '';
		$meta_link 	= isset($_POST['meta_link']) 	? trim(sanitize_text_field($_POST['meta_link'])) 	: '';
		$meta_desc 	= isset($_POST['meta_desc']) 	? trim(sanitize_text_field($_POST['meta_desc'])) 	: '';
		$li_id 		= isset($_POST['li_id']) 		? trim(sanitize_text_field($_POST['li_id'])) 		: '';
		
		//Check wpdb directly, for all matching meta items
		global $wpdb;

		$results = $wpdb->get_results( "SELECT * FROM $wpdb->postmeta WHERE post_id = $post_id AND meta_key = 'qcld_text_group'" );

		//Defaults
		$votes = 0;

		$data['votes'] = 0;
		$data['vote_status'] = 'failed';

		//var_dump( $_COOKIE['voted_li'] );
		//var_dump( $li_id );
		//wp_die();
		
		$exists = ( isset( $_COOKIE['voted_li'] ) && is_array( $_COOKIE['voted_li'] ) )  ? in_array("$li_id", $_COOKIE['voted_li']) : '';
		
		//If li-id not exists in the cookie, then prceed to vote
		if( !$exists ){

			if(!empty($results)){
					
				foreach( $results as $ke => $value ){
					
					$item = $value;

					$meta_id = $value->meta_id;
					
					
					//Unserializing meta value
					$unserializedarray = unserialize($value->meta_value);
					//creating new array for update value
					$new_sl_array = array();
					
					foreach($unserializedarray as $k=>$unserialized){ 
						
						if($meta_desc==''){
							$meta_desc = 'no data';
						}
						
						//Matching for meta value key , what need to be update.
						if( trim($unserialized['qcld_text_title']) == stripslashes(trim($meta_title)) || ($unserialized['qcld_text_desc']) == stripslashes($meta_desc) )
						{
							
							
							$metaId = $meta_id;

							//Defaults for current iteration
							$upvote_count = 0;
							$new_array = array();
							$flag = 0;

							//Check if there already a set value (previous)
							if (array_key_exists('sl_thumbs_up', $unserialized))
							{
								$upvote_count = (int)$unserialized['sl_thumbs_up'];
								$flag = 1;
							}
							$tflag=0;
							if (!array_key_exists('sl_thumbs_up_user', $unserialized)){
								$unserialized['sl_thumbs_up_user']= '';
								$tflag = 1;
							}
							//User information saving array.
							$userinfo = array();
							foreach( $unserialized as $skey => $svalue )
							{
								
								if($flag)
								{
									if( $skey == 'sl_thumbs_up')
									{
										$new_array[$skey] =  $upvote_count + 1;
									}
									else
									{
										$new_array[$skey] = $svalue;
									}
									
								}	// End of Flag
								else
								{
									$new_array[$skey] = $svalue;	
								}
							}	//End of Foreach Loop

							if( !$flag )
							{
								$new_array['sl_thumbs_up'] = $upvote_count + 1;	
							}
							//Collection user info
							$userinfo[] = array('ip'=>$_SERVER['REMOTE_ADDR'],'user_agent'=>$_SERVER['HTTP_USER_AGENT'],'time'=>date('Y-m-d H:i:s'));
							if($tflag==1){
								$getnewvalue = $userinfo;
							}else{
								$getthumbsupusers = unserialize($unserialized['sl_thumbs_up_user']);

								if($getthumbsupusers)
									$getnewvalue = array_merge($getthumbsupusers,$userinfo);
								
							}
							//User info assign to meta value key.
							$new_array['sl_thumbs_up_user'] = serialize($getnewvalue);
							
							
							
							
							$votes = (int)$new_array['sl_thumbs_up'];
							$new_sl_array[$k] = $new_array;

						}else{
							$new_sl_array[$k] = $unserialized;
							
						}
					}	//End of Foreach Loop
						
							
							//New Updated value in Array
							$updated_value = serialize($new_sl_array);
							//wp update query
							$wpdb->update( 
								$wpdb->postmeta, 
								array( 
									'meta_value' => $updated_value,
								), 
								array( 'meta_id' => $meta_id )
							);
							
							$voted_li = array("$li_id");
							
							$total = 0;
							$total = ( isset( $_COOKIE['voted_li'] ) && !empty( $_COOKIE['voted_li'] ) ) ? count($_COOKIE['voted_li']) : 0;
							$total = $total+1;
							//Creating cookie..
							setcookie("voted_li[$total]",$li_id, time() + (86400 * 30), "/");

							$data['vote_status'] = 'success';
							$data['votes'] = $votes;

				}	//End of Foreach Loop

			}	//End if


		}	//End of cookie checking.
		if( isset($_COOKIE['voted_li']) ){
			$data['cookies'] = $_COOKIE['voted_li'];
		}else{
			$data['cookies'] = '';
		}
		
		echo json_encode($data);


		die(); // stop executing script
	}
}


//Implementing the ajax action for frontend users
add_action( 'wp_ajax_qcld_upvote_action', 'ilist_upvote_ajax_action_stuff' ); // ajax for logged in users
add_action( 'wp_ajax_nopriv_qcld_upvote_action', 'ilist_upvote_ajax_action_stuff' ); // ajax for not logged in users

//all template
if(!function_exists('ilist_show_ilist_templates')){
	function ilist_show_ilist_templates(){

		check_ajax_referer( 'qcld-ilist', 'security');

		$list_type = isset( $_POST['list_type'] ) ? sanitize_text_field(trim($_POST['list_type'])):'';
		$dir = dirname(__FILE__).'/views';
		$templatearray = array();

		if($list_type=='textlist'){
			//creating templates array
			$templatearray['simple'] = array(
					'simple-list-one'          		=> esc_html('Simple List Template One'),
					'simple-list-two'          		=> esc_html('Simple List Template Two'),
					'simple-list-three'          	=> esc_html('Simple List Template Three'),
					'simple-list-four'          	=> esc_html('Simple List Template Four'),
					'infographic-template-five'   	=> esc_html('Simple List Template Five'),
					'simple-list-six'   			=> esc_html('Simple List Template Six'),
			);
			$templatearray['elegant'] = array(
					'premium-info-01' 		=> esc_html( 'Premium Info 01' ),
					'premium-info-02'   	=> esc_html( 'Premium Info 02' ),
					'premium-info-03'   	=> esc_html( 'Premium Info 03' ),
					'premium-info-04'   	=> esc_html( 'Premium Info 04' ),
					'premium-info-05'   	=> esc_html( 'Premium Info 05' ),
					'premium-info-06'   	=> esc_html( 'Premium Info 06' ),
					'premium-info-07'   	=> esc_html( 'Premium Info 07' ),
			);
		}elseif($list_type=='graphiclist'){
			//for graphic list//
			$templatearray['simple'] = array(
					'image-template-one' 		=> esc_html( 'Image Template One' ),
					'image-template-two'   		=> esc_html( 'Image Template Two' ),
					'image-template-three'   	=> esc_html( 'Image Template Three' ),
					'image-template-four'   	=> esc_html( 'Image Template Four' ),
					'image-template-five'   	=> esc_html( 'Image Template Five' ),
			);
			$templatearray['elegant'] = array(
					'premium-graphic-style-01' => esc_html( 'Premium Style 01' ),
					'premium-graphic-style-02' => esc_html( 'Premium Style 01' ),
			);


		}else{
			$templatearray['simple'] = array(
				'infographic-template-one'   		=> esc_html('Infographic Template One'),
				'infographic-template-two'       	=> esc_html('Infographic Template Two'),
				'infographic-template-three'     	=> esc_html('Infographic Template Three'),
				'infographic-template-four'   		=> esc_html('Infographic Template Four'),
				'infographic-template-five'   		=> esc_html('Infographic Template Five'),
				'infographic-template-six'   		=> esc_html('Infographic Template Six'),
				'infographic-template-seven'   		=> esc_html('Infographic Template Seven'),
				'infographic-template-eight'   		=> esc_html('Infographic Template Eight'),
				'infographic-template-nine'   		=> esc_html('Infographic Template Nine'),
				'infographic-template-ten'   		=> esc_html('Infographic Template Ten'),
				'infographic-template-eleven'   	=> esc_html('Infographic Template Eleven'),
				'infographic-template-twelve'   	=> esc_html('Infographic Template Twelve'),
				'infographic-template-thirteen'  	=> esc_html('Infographic Template Thirteen'),
				'infographic-template-fourteen'  	=> esc_html('Infographic Template Fourteen'),
				'origami-style-10'   				=> esc_html('Origami style 10'),
			);
			$templatearray['elegant'] = array(
				'chocolate-style-01' => esc_html( 'Chocolate Style 01' ),
				'chocolate-style-02' => esc_html( 'Chocolate Style 02' ),
				'origami-style-04'   => esc_html( 'Origami Style 04' ),
				
				'origami-style-06'   => esc_html( 'Origami Style 06' ),
				'origami-style-08'   => esc_html( 'Origami Style 08' ),
				'origami-style-09'   => esc_html( 'Origami Style 09' ),
				
				'premium-style-01'   => esc_html( 'Premium Style 01' ),
				'premium-style-02'   => esc_html( 'Premium Style 02' ),
				'premium-style-04'   => esc_html( 'Premium Style 04' ),
				'premium-style-05'   => esc_html( 'Premium Style 05' ),
				'premium-style-06'   => esc_html( 'Premium Style 06' ),
				'premium-style-07'   => esc_html( 'Premium Style 07' ),
				'premium-style-08'   => esc_html( 'Premium Style 08' ),
				'premium-style-09'   => esc_html( 'Premium Style 09' ),
				'premium-style-10'   => esc_html( 'Premium Style 10' ),
				'premium-style-11'   => esc_html( 'Premium Style 11' ),
				'premium-style-12'   => esc_html( 'Premium Style 12' ),
				'premium-style-13'   => esc_html( 'Premium Style 13' ),
				'premium-style-14'   => esc_html( 'Premium Style 14' ),
				'premium-style-15'   => esc_html( 'Premium Style 15' ),
				'premium-style-16'   => esc_html( 'Premium Style 16' ),
				'premium-style-17'   => esc_html( 'Premium Style 17' ),
				'premium-style-18'   => esc_html( 'Premium Style 18' ),
				'premium-style-19'   => esc_html( 'Premium Style 19' ),
				'premium-style-20'   => esc_html( 'Premium Style 20' ),
				'premium-style-21'   => esc_html( 'Premium Style 21' ),
				'premium-style-22'   => esc_html( 'Premium Style 22' ),
				'premium-style-23'   => esc_html( 'Premium Style 23' ),
				'premium-style-25'   => esc_html( 'Premium Style 25' ),
				'premium-style-26'   => esc_html( 'Premium Style 26' ),
				'premium-style-27'   => esc_html( 'Premium Style 27' ),
				'premium-style-28'   => esc_html( 'Premium Style 28' ),
				'premium-style-29'   => esc_html( 'Premium Style 29' ),
				'premium-style-30'   => esc_html( 'Premium Style 30' ),
				'premium-style-31'   => esc_html( 'Premium Style 31' ),
				'premium-style-32'   => esc_html( 'Premium Style 32' ),
				'premium-style-33'   => esc_html( 'Premium Style 33' ),
				'premium-style-34'   => esc_html( 'Premium Style 34' ),
				'premium-style-35'   => esc_html( 'Premium Style 35' ),
				'premium-style-36'   => esc_html( 'Premium Style 36' ),
				'premium-style-37'   => esc_html( 'Premium Style 37' ),
				'premium-style-38'   => esc_html( 'Premium Style 38' ),
				'premium-style-39'   => esc_html( 'Premium Style 39' ),
			);
					
		}


		//var_dump( $templatearray );
		//wp_die();


	?>
		<div id="ilist-modal" class="ilistmodaltemplate">

			<!-- Modal content -->
			<div class="modal-content-template" data="<?php echo esc_attr($list_type); ?>">
				<span class="close">×</span>
				<h3><?php esc_html_e( 'Free Templates' , 'iList' ); ?></h3>
				<hr/>
				<div class="qcld_ilist_template_selection">
				<?php foreach($templatearray as $key=>$val) : ?>
					
					<?php 
						if($key != 'simple'){
							echo '<h2 style="margin: 2em 0;"><span style="color:red">Pro Templates (Need Pro version)</span></h2><hr />';
						}
					?>
					
					
					<?php if($key=='simple') : ?>
						<div class="ilist_masonry">
						<?php foreach($val as $k=>$v) : ?>
							
								<div class="ilist_list_elem" data="<?php echo esc_attr($k); ?>"  title="<?php echo esc_attr($k); ?>" >
									<img style="width:150px" src="<?php echo plugins_url( 'screenshots/'.$k.'.jpg', __FILE__ ); ?>" />
								</div>
							
						<?php endforeach ?>
						</div>
					<?php else : ?>
						<div class="ilist_masonry">
						<?php foreach($val as $k=>$v) : ?>
							
								<div class="ilist_list_elem_pro" data="<?php echo esc_attr($k); ?>"  title="<?php echo esc_attr($k); ?>">
									<a href="<?php echo esc_url( 'https://www.quantumcloud.com/products/infographic-maker-ilist/' , 'iList' ); ?>" target="_blank"><img style="width:150px" src="<?php echo plugins_url( 'alltemplate/'.$k.'/screenshot.jpg', __FILE__ ); ?>" />
								</div>
							
						<?php endforeach ?>
						</div>
					<?php endif ?>
						
				<?php endforeach ?>
					
				</div>
			</div>

		</div>
	<?php
		die();
	}
}

add_action( 'wp_ajax_show_ilist_templates', 'ilist_show_ilist_templates' ); // ajax for logged in users
add_action( 'wp_ajax_nopriv_show_ilist_templates', 'ilist_show_ilist_templates' ); // 

if(!function_exists('render_shortcode_modal')){
function render_shortcode_modal() {

	//check_ajax_referer( 'qcld-ilist', 'security');

	?>

	<div id="ilist-modal" class="ilist_shortcode_modal">

		<!-- Modal content -->
		<div class="ilist-modal-content">
			<span class="close">×</span>
			<h3><img src="<?php echo plugins_url( 'assets/images/1.png', __FILE__ ) ?>" alt="<?php echo esc_attr( 'iList - Free' , 'iList' ); ?>"> <?php _e( ' - Shortcode Maker' , 'iList' ); ?></h3>
			<hr/>
			<div class="sm_shortcode_list">

				<?php
				echo '<div class="ilist_single_field_shortcode">';
				echo '<label style="width: 200px;display: inline-block;">'.esc_html( 'Select Post' , 'iList' ).'</label><select style="width: 225px;" id="ilist_post_select_shortcode"><option value="">'.esc_html( 'Please Select One' , 'iList' ).'</option>';
				$ilist = new WP_Query( array( 'post_type' => 'ilist', 'posts_per_page' => -1, 'order' => 'ASC') );
				if( $ilist->have_posts()){
					while( $ilist->have_posts() ){
						$ilist->the_post();
						echo '<option value="'.get_the_ID().'">' . get_the_title() . '</option>';
					}
				}
				echo '</select>';
				echo '</div>';
				?>

				<div class="ilist_single_field_shortcode">
					<label style="width: 200px;display: inline-block;"><?php echo esc_html( 'Column' , 'iList' ); ?></label>
						<select style="width: 225px;" id="ilist_column_shortcode">
							<option value="1"><?php echo esc_html( 'Column 1' , 'iList' ); ?></option>
							<option value="2"><?php echo esc_html( 'Column 2' , 'iList' ); ?></option>
						</select>
				</div>
				<div class="ilist_single_field_shortcode">
					<label style="width: 200px;display: inline-block;">Order By</label><select style="width: 225px;" id="ilist_item_orderby">
						<option value=""><?php echo esc_html( 'None' , 'iList' ); ?></option>
						<option value="upvote"><?php echo esc_html( 'Upvotes' , 'iList' ); ?></option>
					</select>
				</div>
				<div class="ilist_single_field_shortcode">
					<label style="width: 200px;display: inline-block;margin-top: 22px;"><?php echo esc_html( 'Upvote' , 'iList' ); ?></label>
					<div class="switchilist demo3">
						<input class="upvote_switcher" name="ckbox" value="on" type="checkbox">
						<label><i></i></label>
					</div>
					<div style="clear:both"></div>
				</div>
				<div class="ilist_single_field_shortcode">
					<label style="width: 200px;display: inline-block;margin-top: 22px;"><?php echo esc_html( 'Disable Lightbox' , 'iList' ); ?></label>
					<div class="switchilist demo3">
						<input class="disable_popup_switcher" name="ckbox" value="1" type="checkbox">
						<label><i></i></label>
					</div>
					<div style="clear:both"></div>
				</div>

				<div class="ilist_single_field_shortcode">
					<label style="width: 200px;display: inline-block;"></label><input type="button" id="add_shortcode" value="<?php echo esc_html( 'Add Shortcode' , 'iList' ); ?>" />
				</div>
			</div>
			
			<div class="ilist_shortcode_container" style="display:none;">
			<div class="qcpd_single_field_shortcode">
                <textarea style="width:100%;height:200px" id="ilist_shortcode_container"></textarea>
				<p><b><?php echo esc_html( 'Copy' , 'iList' ); ?></b> <?php echo esc_html( 'the shortcode & use it any text block.' , 'iList' ); ?> <button class="ilist_copy_close button button-primary button-small" style="float:right"><?php echo esc_html( 'Copy & Close' , 'iList' ); ?></button></p>
            </div>
		</div>
			
		</div>

	</div>
	<?php
	exit;
}
}
add_action( 'wp_ajax_show_shortcodes', 'render_shortcode_modal');


if(!function_exists('qcld_openai_title_generate_desc')){
function qcld_openai_title_generate_desc() {

	check_ajax_referer( 'qcld-ilist', 'security');

    $title  		= isset( $_POST['title'] ) 		? sanitize_text_field($_POST['title']) 		: '';
    $number  		= isset( $_POST['number'] ) 	? sanitize_text_field($_POST['number']) 	: '';
    $post_id  		= isset( $_POST['post_id'] ) 	? sanitize_text_field($_POST['post_id']) 	: '';

    $OPENAI_API_KEY = get_option('sl_openai_api_key');
    $ai_engines     = get_option('sl_openai_engines');
    $max_token      = get_option('sl_openai_max_token');
    $temperature    = get_option('sl_openai_temperature');
    $ppenalty       = get_option('sl_openai_presence_penalty');
    $fpenalty       = get_option('sl_openai_frequency_penalty');

    $result_data 	= '';

    if(!empty($title)){

    	$post_update = array(
            'ID'         => $post_id,
            'post_title' => $title
        );

        wp_update_post( $post_update );

        //$prompt         = "rewrite this paragraph for a unique artical:\n\n" . $title;
        $prompt         = $number ." blog topics about:\n\n" . $title;

        $request_body 	= [
            "prompt"            => $prompt,
            "model"             =>  $ai_engines,
            "max_tokens"        => (int)$max_token,
            "temperature"       => (float)$temperature,
            "presence_penalty"  => (float)$ppenalty,
            "frequency_penalty" => (float)$fpenalty,
        ];
        $data    = json_encode($request_body);
        $url     = "https://api.openai.com/v1/completions";
        $apt_key = "Authorization: Bearer ". $OPENAI_API_KEY;

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $headers    = array(
           "Content-Type: application/json",
           $apt_key ,
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        $result     = curl_exec($curl);
        curl_close($curl);
        $results    	= json_decode($result);

        $result_data 	= isset( $results->choices[0]->text ) ? trim( $results->choices[0]->text ) : '';

        $qcld_response 	= preg_replace('/\n$/', '', preg_replace('/^\n/', '', preg_replace('/[\r\n]+/', "\n", $result_data)));
        $qcld_response 	= preg_split("/\r\n|\n|\r/", $qcld_response);
        $qcld_response 	= preg_replace('/^\\d+\\.\\s/', '', $qcld_response);
        $qcld_response 	= preg_replace('/\\.$/', '', $qcld_response);
        $qcld_response 	= array_splice($qcld_response, 0, strval($number));


        global $wpdb;

		$results = $wpdb->get_results( "SELECT * FROM $wpdb->postmeta WHERE post_id = $post_id AND meta_key = 'qcld_text_group'" );
		
		//If li-id not exists in the cookie, then prceed to vote

			if(!empty($results)){

			
				foreach( $results as $ke => $value ){
					
					$item = $value;
					$meta_id = $value->meta_id;
					
					$new_sl_array = array();

					//creating new array for update value
					foreach( $qcld_response as $title_value ){

						$title_value = preg_replace( "/['\"]+/", "", $title_value );

						$prompt         = "rewrite this paragraph for a unique artical:\n\n" . $title_value;

				        $request_body = [
				            "prompt"            => $prompt,
				            "model"             =>  $ai_engines,
				            "max_tokens"        => (int)$max_token,
				            "temperature"       => (float)$temperature,
				            "presence_penalty"  => (float)$ppenalty,
				            "frequency_penalty" => (float)$fpenalty,
				        ];
				        $data    = json_encode($request_body);
				        $url     = "https://api.openai.com/v1/completions";
				        $apt_key = "Authorization: Bearer ". $OPENAI_API_KEY;

				        $curl = curl_init($url);
				        curl_setopt($curl, CURLOPT_URL, $url);
				        curl_setopt($curl, CURLOPT_POST, true);
				        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				        $headers    = array(
				           "Content-Type: application/json",
				           $apt_key ,
				        );
				        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
				        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
				        $result     = curl_exec($curl);
				        curl_close($curl);
				        $results    	= json_decode($result);

				        $result_data 	= isset( $results->choices[0]->text ) ? trim( $results->choices[0]->text ) : '';

						$new_sl_array[] = array(
											'qcld_text_title' 		=> $title_value,	
											'qcld_text_desc'  		=> $result_data,
											'qcld_text_long_desc'  	=> $result_data
										);	

					}
					
					//New Updated value in Array
					$updated_value = serialize($new_sl_array);
					//wp update query
					$wpdb->update( 
						$wpdb->postmeta, 
						array( 
							'meta_value' => $updated_value,
						), 
						array( 'meta_id' => $meta_id )
					);
							

				}	// End of Foreach Loop
			}	// End of if

    }

    
	$response = array(
       'html' => $result_data,
    );
    echo wp_send_json($response);
    wp_die();

}
}
add_action( 'wp_ajax_qcld_openai_title_generate_desc', 'qcld_openai_title_generate_desc');