<?php
if(!defined('WPINC')){	die; }

if(!class_exists('THFAQF_Public')):

class THFAQF_Public{
    public function __construct(){
    	add_shortcode("FAQ", array($this, 'faq_shortcode'));
    	add_shortcode("faq", array($this, 'faq_shortcode'));
    	add_action( 'wp_head', array($this, 'get_additional_css_for_faqs'),999);
    	add_shortcode("thfaq_group", array($this, 'faq_layout_shortcode'));
    }   

    public function enqueue_styles_and_scripts(){
		wp_register_style('thfaqf-public-style', THFAQF_ASSETS_URL_PUBLIC.'css/thfaqf-public.css');
	    wp_enqueue_style('FontAwesome','https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css');
	    wp_enqueue_script('font-icon-picker-js',THFAQF_ASSETS_URL_PUBLIC.'js/fontawesome.min.js',array('jquery'),THFAQF_VERSION,true);
	    wp_enqueue_style('thfaqf-public-style');
	    wp_enqueue_style('styleicon');

	    wp_register_script('thfaqf-public-script', THFAQF_ASSETS_URL_PUBLIC.'js/thfaqf-public.js', array('jquery'), THFAQF_VERSION, true);
		wp_enqueue_script('thfaqf-public-script');

		$open_multiple_faqs = THFAQF_Utils::get_settings('open_multiple_faqs');
		$faqf_var = array(
			'open_multiple_faqs' => ($open_multiple_faqs || $open_multiple_faqs === 'yes') ? 'yes' : 'no',
			'admin_url' => admin_url(),
            'ajax_url'  => admin_url( 'admin-ajax.php'),
        );    
		wp_localize_script('thfaqf-public-script', 'thfaqf_public_var', $faqf_var);
	}	

	public function faq_shortcode($atts){    
        $args = shortcode_atts( array(
            'id' => '',
        ), $atts );

        $invalid_ids = array();
        $faq_post_ids = explode(',', $args['id']);

        ob_start();
        foreach($faq_post_ids as  $key => $faq_post_id){
            $post_status = get_post_status($faq_post_id);
            $post_type = get_post_type($faq_post_id);

            if($post_type == 'faq' && $post_status == 'publish'){
                $this->faq_list($faq_post_id,'faq');
            }else{
                array_push($invalid_ids, $faq_post_id);
            }
        }

        $invalid_ids = implode(',', $invalid_ids);
        echo $invalid_faq_html = !empty($invalid_ids) ? '[FAQ id="'.$invalid_ids.'"]' : '';
        return ob_get_clean();
    }


    public function faq_layout_shortcode($atts){
    	ob_start();
    	$sh_args = shortcode_atts( array(
            'category' => '',
            'limit' => '',
        ), $atts );

	    $faq_category = $sh_args['category'];
	    $limit = $sh_args['limit'];
	    $category_array = explode (",", $faq_category); 
	    $pst_args = array(  
	        'post_type' => 'faq',
	        'post_status' => 'publish',
	        'posts_per_page' => $limit, 
	        'tax_query' => array(
	            array(
	                'taxonomy' => 'faq_category',
	                'field' => 'slug',
	                'terms' => $category_array,
	            ),
	        ),
	        'order' => 'ASC', 
	        'orderby' => 'title',
	    );
	    $loop = new WP_Query( $pst_args );  
	    $this->prepare_faq_layout($loop);
	    wp_reset_postdata(); 
	    return ob_get_clean();
    }

    public function prepare_faq_layout($loop){
    	$theme_wrapper_class = $this->get_theme_wrapper_class();
        ?> 
    	<div class="thfaqf-layout-wrapper thfaqf-faq-list <?php echo $theme_wrapper_class; ?>">
    		<?php 
    		$faq_index1 = 0;
    		$global_settings = THFAQF_Utils::get_faq_settings();
			$enable_search_option_faq_layout = isset($global_settings['enable_search_option_faq_layout']) ? $global_settings['enable_search_option_faq_layout'] : false;
			$show_updated_date = isset($global_settings['show_updated_date']) ? $global_settings['show_updated_date'] : false;
    		while($loop->have_posts()) : $loop->the_post();
    			$faq_index1++;
    			?>
             	<div class="<?php echo 'thfaqf-tab-id_'.get_the_ID(); ?> thfaqf-tabcontent-wrapper  <?php echo $faq_index1 != 1 ? 'thfaqf-hide' : ''; ?>">
				  	<?php
				  	echo $enable_search_option_faq_layout ? $this->faq_search_option($show_updated_date) : '';
				  	$last_updated = get_the_modified_date(get_option('date_format'), get_the_ID()); 
				  	echo $show_updated_date ? '<p class="thfaqf-faq-updated-date">'.esc_html($last_updated).'</p>' :  '';
				  	?>
				</div>
    		    <?php 
    		endwhile; 
    		?>
	    	<div class="thfaqf-tab">
	    		<?php 
	    		$tab_index = 0;
	    		while( $loop->have_posts()) : $loop->the_post(); 
	    			$tab_index++;
	    			$tab_title = get_the_title(get_the_ID());
	    			$tab_title =  $tab_title ?  $tab_title : 'Title '.$tab_index;
	    			?>
	    			<h3 class="thfaqf-tablinks thfaqf-tablinks-<?php echo get_the_ID(); ?>  <?php echo $tab_index == 1 ? 'active' : ''; ?>" onclick="FaqTabOnClick(this, 'thfaqf-tab-id_<?php echo get_the_ID(); ?>')"><?php echo $tab_title; ?></h3>
				<?php endwhile; ?>
			</div> 
	        <?php 
	        $faq_index = 0;
	        while( $loop->have_posts()) : $loop->the_post(); 
	        	$faq_index++;
	        	?>
				<div class="<?php echo 'thfaqf-tab-id_'.get_the_ID(); ?> thfaqf-tabcontent-wrapper thfaqf-tabcontent  <?php echo $faq_index != 1 ? 'thfaqf-hide' : ''; ?>">
				  	<?php $this->faq_list(get_the_ID(),'layout'); ?>
				</div>
			<?php endwhile; ?>
		</div>
    	<?php
    }

	public function faq_list($post_id,$type){
		$faqs = get_post_meta($post_id, THFAQF_Utils::OPTION_KEY_FAQ_ITEMS, true);
		if(empty($faqs)){
			return;
		}
        global $current_user;
		$global_settings = THFAQF_Utils::get_faq_settings();
		$local_settings = get_post_meta($post_id, THFAQF_Utils::OPTION_KEY_FAQ_SETTINGS_POST, true);
		$individual_settings = get_post_meta($post_id, THFAQF_Utils::OPTION_KEY_FAQ_INDIVIDUAL_POST,true);
		$settings = $this->prepare_faq_settings($global_settings, $local_settings);
		$border_radius = isset($settings['faq_border_radius']) ? $settings['faq_border_radius'] : 0;
		$faq_title_icon = isset($settings['icon_picker']) ? $settings['icon_picker'] : 0;
		$enable_icon_options = isset($settings['enable_disable_title_icons']) ?$settings['enable_disable_title_icons']: false;
		$border_radius = is_numeric($border_radius) ? $border_radius : 0;
		$title_color = isset($settings['title_color']) ? $settings['title_color'] : false;
		$title_bg = isset($settings['title_bg_color']) ? $settings['title_bg_color'] : false;
		$content_color = isset($settings['content_color']) ? $settings['content_color'] : false;
		$content_bg = isset($settings['content_bg_color']) ? $settings['content_bg_color'] : false;
		$expnd_icon_color = isset($settings['expnd_icon_color']) ? $settings['expnd_icon_color'] : false;
		$title_active_color = isset($settings['title_active_color']) ? $settings['title_active_color'] : false;
        $enable_search_option = isset($individual_settings['enable_disable_search_option']) ? $individual_settings['enable_disable_search_option'] : false;
        $faq_count = isset($settings['thfaq_count']) ? $settings['thfaq_count'] : false; 
		$show_updated_date = THFAQF_Utils::get_setting_value($settings, 'show_updated_date');
		$accordion_display_mode = THFAQF_Utils::get_setting_value($settings, 'accordion_display_mode');
		$accordion_display_mode = $accordion_display_mode ? $accordion_display_mode : 'open_first';
		$fist_item_active_class = $accordion_display_mode === 'open_first' ? ' thfaqf-active' : '';
		$theme_wrapper_class = $this->get_theme_wrapper_class();
		$item_wrapper_style  = '';
		$item_title_style    = 'border-width: 1px; border-style: solid; margin-bottom: -1px;';
		$item_content_style  = 'border-width: 1px; border-style: solid;';
		$title_text_style    = '';
		$item_expnd_icon_style = '';
		$user_id = $current_user->ID;
		$tottal_faq_count = is_array($faqs) ? count($faqs) : 0;
		$visible_faq_count =!empty((int)$faq_count) ? (int)$faq_count : $tottal_faq_count;
	    $load_button_display_none = $visible_faq_count >= $tottal_faq_count ? 'hide-faqf-group' : '';

		if($border_radius){
			$item_wrapper_style .= 'border-radius: '.esc_attr($border_radius).'px;';
		}

		if($title_bg){
			$item_title_style .= ' background-color: '.esc_attr($title_bg).'; border-color: '.esc_attr($title_bg).';';
		}
		if($title_color){
			$title_text_style .= ' color: '.esc_attr($title_color).';';
		}

		if($content_bg){
			$item_content_style .= ' background-color: '.esc_attr($content_bg).'; border-color: '.esc_attr($content_bg).';';
		}
		
		if($content_color){
			$item_content_style .= ' color: '.esc_attr($content_color).';';
		}
		if($expnd_icon_color){
			$item_expnd_icon_style = ' color: '.esc_attr($expnd_icon_color).';';
		}

		$this->get_additional_css_for_faqs($post_id);
		?>
		<div class="thfaqf-faq-list <?php echo $theme_wrapper_class ?>">
            <?php 
			echo $type == 'faq' ? '<h3 class="thfaqf-faq-list-title">'.get_the_title($post_id).'</h3>' : '';
			echo ($enable_search_option === 'yes' || $enable_search_option === true) && $type != 'layout'  ? $this->faq_search_option($show_updated_date ) : '';
	        if(($show_updated_date === "yes" || $show_updated_date == 1) && $type != 'layout'){ 
	        	$last_updated = get_the_modified_date(get_option('date_format'), $post_id);
	        	echo '<p class="thfaqf-faq-last-updated">'. esc_html($last_updated).'</p>';
			}

			$index = 0;
			echo '<div class="faq-item-wrapper">';
	        foreach($faqs as $key => $faq_item){
	        	$item_wrapper_class = $index === 0 ? $fist_item_active_class : '';
	        	$like_user_ids = !empty($faq_item['like_user_ids']) ? $faq_item['like_user_ids'] : '';
	        	$dislike_user_ids = !empty ($faq_item['dislike_user_ids']) ? $faq_item['dislike_user_ids'] : '';
				$display_none = $key < $visible_faq_count ? '' : 'thfaqf-div-none';
	            $this->display_faq_item($faq_item, $item_wrapper_class, $item_wrapper_style, $item_title_style, $item_content_style, $title_text_style,$item_expnd_icon_style,$post_id,$key,$like_user_ids,$dislike_user_ids,$faq_title_icon,$enable_icon_options,$user_id,$display_none,$title_active_color);
	        	$index++;
	        }
            echo '</div>';

	        $this->display_social_share($post_id, $settings);
	        $total_count = isset($faqs) ? count($faqs) : 0;
	        $count = intdiv($total_count, $visible_faq_count);
	        $mod = fmod($total_count, $visible_faq_count); 
	        $page_count = (($mod < $visible_faq_count) and ($mod > 0) ) ? $count+1 : $count;
	        $pagination_panel = fmod($page_count,4)>0 ? $count = intdiv($page_count,4)+1 : intdiv($page_count,4); 

	        ?>
	        <p class="thfaqf-display-faq-setngs <?php echo esc_attr($load_button_display_none);?>">
				<span class="thfaqf-pagination">
					<span onclick="ThfaqPagination(this,'prev_page')" ><a class="thfaqf-prev-page <?php echo $pagination_panel == 1 ? 'thfaqf-div-none': ''; ?>"  href="#"><<</a></span>	
					<?php  for ($i=1; $i <= $page_count; $i++) { 
						$active = $i == 1 ? 'thfaq-ft current' : '';
						$display_none = $i>4? 'thfaqf-div-none' : '';
						?>
			            <span class="thfaqf-page-no <?php echo $display_none; ?>" data-number="<?php echo $i; ?>">
			           	<a class="thfaqf-pnumber <?php echo $active; ?>"href="#" onclick="ThfaqEachPage(this)" ><?php echo $i; ?></a>
			            </span>
			            <?php
			            if($i == 3){
			            	?><span style="pointer-events: none;"class="thfaqf-hidden-no thfaqf-div-none" ><a class="thfaqf-pnumber">...</a></span><?php
			            }
		        	} 
		        	?> 
		        	<span onclick="ThfaqPagination(this,'next_page')" data-page_count="<?php echo $page_count; ?>"><a class="thfaqf-next-page <?php echo $pagination_panel == 1 ? 'thfaqf-div-none': ''; ?>" href="#">>></a></span>	
				</span>
				<input type="hidden" class="thfaqf-count-faq-number"name="count_faq" value="<?php echo esc_attr($visible_faq_count);?>"/>
			</p><br>
            <?php $this->display_edit_link($post_id); ?>
	    </div>
	    <?php 
	}

	public function display_faq_item($faq_item, $item_wrapper_class, $item_wrapper_style, $item_title_style, $item_content_style, $title_text_style,$item_expnd_icon_style,$post_id,$key,$like_user_ids,$dislike_user_ids,$faq_title_icon,$enable_icon_options,$user_id,$display_none,$title_active_color){
		$faq_title   = isset($faq_item['faq_title']) ? $faq_item['faq_title'] : '';
        $faq_content = isset($faq_item['faq_content']) ? $faq_item['faq_content'] : '';
		$faq_title = htmlspecialchars_decode($faq_title);
		$faq_content = htmlspecialchars_decode($faq_content);
		$enable_like_dislike = THFAQF_Utils::get_faq_settings('','like_and_dislike_option');
		$enable_comment_box = THFAQF_Utils::get_faq_settings('','enable_disable_comment');
		$expand_style = THFAQF_Utils::get_faq_settings('','expand_style','thfaq-marker');
		$faq_comment_id = isset ($faq_item['faq_comment']) ? $faq_item['faq_comment'] :'';
		$count_comments = $this->rate_comment($faq_comment_id);
		$c_color = $count_comments>0? 'color:black;': '';

		?>
		<div id="thfaqf-faq-item-<?php echo $post_id.'_'.$key; ?>" class="thfaqf-faq-item  thfaqf-faq-item-<?php echo $post_id; ?>  <?php echo esc_attr($display_none).' '.esc_attr($item_wrapper_class).' thfaqf-post-id-'.esc_attr($post_id); ?> thfaqf-count-dsply-setngs" style="<?php echo esc_attr($item_wrapper_style); ?>" >
			<div data-active_color="<?php echo $title_active_color; ?>" class="thfaqf-faq-item-title" style="<?php echo esc_attr($item_title_style); ?>">
 				<h4>
 				<?php 
	 				if($enable_icon_options) {?>
	 					<span class="thfaqf-title-icon"><i class="<?php echo esc_attr($faq_title_icon);?>"></i></span>
	 				<?php } ?>
	 				<span class="<?php echo esc_attr($expand_style);?> thfaqf-toggle-icon" style="<?php echo esc_attr($item_expnd_icon_style); ?>"></span>
 					<span class="thfaqf-title-text " style="<?php echo esc_attr($title_text_style); ?>" ><?php echo $faq_title; ?></span>
 				</h4>	
			</div>
			<div class="thfaqf-faq-item-content" style="<?php echo esc_attr($item_content_style); ?>" >
				<?php 
				echo wpautop($faq_content);
				echo $enable_like_dislike == true ?  $this->like_option($post_id,$key,$like_user_ids,$dislike_user_ids,$user_id) : '';
			
				if($enable_comment_box == true){ 
					?>
					<span class="thfaq-user-comment-wrapper">
						<span class="trigger-comment" onclick="clickFaqComment(this)" ><i style="<?php echo esc_attr($c_color); ?>" class="thfaq-icomoon icon-comments" ></i></span>
						<span class="thfaq-rate-comments"><?php echo $count_comments ? esc_html($count_comments) : 0; ?></span></span>
					</span>
					<?php
				}
				?>
				<div class="thfaq-enable-comment-box thfaqf-hide"> <p><?php $this->display_comment_box($post_id,$key);?> </p></div>
			</div>
			<div class="log"></div>
		</div>
 		<?php 
	}

	public function display_comment_box($faq_id,$faq_index){
		?>
		<div class="thfaqf-comment-wrapper">
			<form form method="post" class="thfaqf-post-comment">
				<p><input class="thfaqf-comment-box thfaqf-uname" type="text" placeholder="Name..."name="user_name"/></p>
				<p class="threq-name"></p>
				<input type="hidden" name="action" value="thfaqf_comment">
				<input type="hidden" name="faq_id" value="<?php echo esc_attr($faq_id);?>">
				<input type="hidden" name="faq_index"value="<?php echo esc_attr($faq_index); ?>">
				<p><textarea placeholder="Add a Comment..."name="user_msg" class="thfaqf-comment-box thfaqf-ucomment"></textarea></p>
				<p class="threq-comment"></p>
				<input type="hidden" name="wp_thfaqc_nonce" value="<?php echo wp_create_nonce('thfaqc_nonce'); ?>"/>
		    	<p><button type="submit" name="thfaqf_comment_submt" class="thfaqf-submt-cmmt button primary is-xsmall" onclick="submitFaqfComment(this)">Send</button></p><p class="thfaqf-comment-validetion"></p>
		    </form>	

		    <?php
		    $faq_data = get_post_meta($faq_id, THFAQF_Utils::OPTION_KEY_FAQ_ITEMS, true);
		    $faq_comment_id = isset($faq_data[$faq_index]['faq_comment']) ? $faq_data[$faq_index]['faq_comment'] : '';
		    $faq_comments_array = isset($faq_comment_id) ? explode(',', $faq_comment_id) : array();

		    if($faq_comments_array) {
				$arr =array();
				foreach($faq_comments_array as $key =>$faq_single_comment_ids){
					$post_status = get_post_status($faq_single_comment_ids);
					$post_type = get_post_type($faq_single_comment_ids);
				    $post_content = get_post($faq_single_comment_ids);

                    if($post_status == 'publish' and $post_type == 'user-comment'){
                    	$content = $post_content->post_content;
                    	$content = wpautop(make_clickable(wp_kses_post($content)));
                        $faq_cmmted_user = get_the_title($faq_single_comment_ids);
                    	?>

						<div class="thfaq-cmmt-box-setngs">
							<div class="thfaq-cmmted-user">
							 	<span class="thfaq-cmmt-user-name"><i class="fas fa-user"></i><spam style="margin-left:7px;"><?php echo esc_html($faq_cmmted_user).' ';?><?php echo !empty($faq_cmmted_user) ? 'Commented' : ''?></spam></span> 
							</div>
							<br><div class="thfaq-cmmted-data"><?php echo  do_shortcode( $content );?></div>
						</div>
						<?php
				    }
			    }
		    }
		    ?>
		</div>
		<?php
	}

	public  function rate_comment($faq_comment_id){
		$comment_arr = array();
		$faq_comment_id = explode(',', $faq_comment_id);
		foreach($faq_comment_id as $key =>$faq_single_comment_ids){
			$post_status = get_post_status($faq_single_comment_ids);
			$post_type = get_post_type($faq_single_comment_ids);
            if($post_status == 'publish' and $post_type == 'user-comment'){
            	array_push($comment_arr,$faq_single_comment_ids);
		    }
		}
		return isset($comment_arr) ? count($comment_arr) : 0;
	} 

	public  function thfaqf_comment(){
		if (! isset( $_POST['wp_thfaqc_nonce'] ) || ! wp_verify_nonce( $_POST['wp_thfaqc_nonce'],'thfaqc_nonce')){
	        $message =array( 'verify_nonce' => '<span class="thfaqf-error-submt">Sorry, your nonce did not verify.</span>');
	        wp_send_json($message);
	    }else{
		    $submit_faq_comment = array();
			$post_type = 'user-comment';
			$user_name = isset($_REQUEST['user_name']) ? trim($_REQUEST['user_name']) : false;
			$user_msg = isset($_REQUEST['user_msg']) ? trim($_REQUEST['user_msg']) : false;
			$faq_id = isset($_REQUEST['faq_id']) ? trim($_REQUEST['faq_id']) : false;
			$faq_index = isset($_REQUEST['faq_index']) ? trim($_REQUEST['faq_index']) : false;
	        $post_status = get_post_status($faq_id);

			$user_name = sanitize_text_field(stripslashes($user_name));
			$user_msg = wp_filter_post_kses(stripslashes($user_msg));
		   	$faq_id  = sanitize_text_field($faq_id);
		   	$faq_index  = sanitize_text_field($faq_index);
		   	$message = array();

			if(empty($user_name)){
				$message['name'] = "<span class='thfaqf-error-submt'><b>Name is a required field.</b></span>";		
			}

			if(empty($user_msg)){
				$message['comment'] = "<span class='thfaqf-error-submt'><b>Comment is a required field.</b></span>";
			}
			
			if(!empty($message)){
	            wp_send_json($message);
			}else{	

	    		$submit_faq_comment = array(
			        'post_title'   => $user_name,
			        'post_content' => $user_msg,
			        'post_type'    => $post_type,
			        'post_status'  => 'draft',
			    );

	    		$post_id = wp_insert_post( $submit_faq_comment, $wp_error = false );
	            $faq_data = get_post_meta($faq_id, THFAQF_Utils::OPTION_KEY_FAQ_ITEMS, true);
	 			$faq_comment_string = isset($faq_data[$faq_index]['faq_comment']) ? $faq_data[$faq_index]['faq_comment'] : '';
	    		if($faq_comment_string){
	    			$faq_comments_array = explode(',', $faq_comment_string);
	    			if(is_array($faq_comments_array)){
	    				array_push($faq_comments_array,$post_id);
	    			}
	    			
	    		}else{
		    		$faq_comments_array = array($post_id);
		    	}

		    	$faq_comments = implode(',', $faq_comments_array);  
		    	$faq_data[$faq_index]['faq_comment'] = $faq_comments;

	            if($post_status == 'publish'){
	            	$update_data1 = update_post_meta($faq_id, THFAQF_Utils::OPTION_KEY_FAQ_ITEMS,$faq_data);
	            }
	    		$message = '<span class="thfaqf-success-submt">Comment posted successfully.<span>'; 
	    	}
	    }
        wp_send_json($message);
	}

	public function display_social_share($post_id, $settings){
		$enabled_share_button = THFAQF_Utils::get_setting_value($settings, 'enable_share_button');

		if($enabled_share_button === 'yes' || $enabled_share_button == 1){
			$post_url = get_post_permalink($post_id);
	   		$social_share_icons = THFAQF_Utils::get_setting_value($settings, 'social_share_options');
	   		$social_share_icons = $social_share_icons ? explode(',', $social_share_icons) : false;

	   		$social_share_html = '';
	   		if(is_array($social_share_icons)){
	   			foreach($social_share_icons as $key){
	   				$link = '';

	   				if($key === 'twitter'){
	   					$url  = 'https://twitter.com/home?status='.esc_url($post_url);
						$link = '<a title="Twitter" class="thfaqf-share-icon" data-url="'.esc_url($url).'"><i class="thfaq-share-btn thfaqf-twitter fab fa-twitter"></i></a>';

	   				}else if($key === 'facebook'){
	   					$url  = 'https://www.facebook.com/sharer/sharer.php?u='.esc_url($post_url);
						$link = '<a title="Facebook" class="thfaqf-share-icon" data-url="'.esc_url($url).'"><i class="thfaq-share-btn thfaqf-facebook fab fa-facebook-f"></i></a>';

	   				}else if($key === 'email'){
	   					$url  = 'mailto:info@example.com?&subject=&body='.esc_url($post_url);
						$link = '<a title="Email" class="thfaqf-share-icon" data-url="'.esc_url($url).'"><i class="thfaq-share-btn thfaqf-envelope fas fa-envelope"></i></a>';

	   				}else if($key === 'linkedin'){
	   					$url  = 'https://www.linkedin.com/shareArticle?mini=true&url='.esc_url($post_url);
						$link = '<a title="Linkedin" class="thfaqf-share-icon" data-url="'.esc_url($url).'"><i class="thfaq-share-btn thfaqf-linkedin fab fa-linkedin-in"></i></a>';

	   				}else if($key === 'pinterest'){
	   					$url = 'https://pinterest.com/pin/create/button/?url='.esc_url($post_url) ;
	   					$link = '<a title="Pinterest" class="thfaqf-share-icon" data-url="'.esc_url($url).'"><i class="thfaq-share-btn thfaqf-pinterest fab fa-pinterest-p"></i></a>';

	   				}else if($key === 'whatsapp'){
	   					$url = 'https://web.whatsapp.com/send?text='. esc_url($post_url);
	   					$link = '<a title="Whatsapp" class="thfaqf-share-icon" data-url="'.esc_url($url).'"><i class="thfaq-share-btn thfaqf-whatsapp fab fa-whatsapp"></i></a>';
	   				}

	   				$social_share_html .= $link;
	   			}
	   		}

	   		if($social_share_html){
	   			$social_share_title = THFAQF_Utils::get_setting_value($settings, 'social_share_title');	   			
	   			?>
	   			<p class='thfaqf-social-share-wrapper'>
	   				<span class='thfaqf-social-share-title'>
	   					<?php echo !empty($social_share_title) ? esc_html($social_share_title).': ' : ''; ?>
	   				</span>
	   				<?php echo wp_kses_post($social_share_html); ?>
	   			</p>
	   			<?php
	   		}
		}
	}

	public function display_edit_link($post_id){
	    if((current_user_can('administrator') and !is_singular('faq')) or (current_user_can('manage_woocommerce') and !is_singular('faq'))) {
	    	$edit_url = get_edit_post_link($post_id);
	    	?>
	    	<p class="thfaqf-edit-faq-link"><a href="<?php echo esc_attr($edit_url); ?>" >Edit FAQ</a></p>
	    	<?php	 
	    }
	}

	private function prepare_faq_settings($global_settings, $local_settings){
		if(is_array($local_settings)){
			$override_settings = isset($local_settings['override_global_settings']) ? $local_settings['override_global_settings']: false;
			if($override_settings === 'yes' || $override_settings == true){
				foreach($local_settings as $key => $value) {
					if($value){
						$global_settings[$key] = $value;
					}
				}
			}
		}
		return $global_settings;
	}
    
    public function get_theme_wrapper_class(){
    	$current_theme = wp_get_theme();
    	$current_theme_name = isset($current_theme['Template']) ? $current_theme['Template'] : '';

    	$wrapper_class = '';
    	if($current_theme_name){
    		$wrapper_class = str_replace(' ', '-', strtolower($current_theme_name));
    		$wrapper_class = 'thfaqf-theme-wrapper-'.$wrapper_class;
    	}
       	return $wrapper_class;
    }

	public function prepare_colour_settings($faq_cleaned_color, $general_settings){
        $settings_fields = THFAQF_Utils::get_settings_fields();
		$use_post_settings =  isset($faq_cleaned_color['use_post_settings']) ? $faq_cleaned_color['use_post_settings'] : '';
		if(($use_post_settings == 'yes') and (!empty($faq_cleaned_color))){
            $title_color       = isset($faq_cleaned_color['title_color'])?$faq_cleaned_color['title_color']:'';
			$title_bg_color    = isset($faq_cleaned_color['title_bg_color'])?$faq_cleaned_color['title_bg_color']:'';
			$content_color     = isset($faq_cleaned_color['content_color'])?$faq_cleaned_color['content_color']:'';
			$content_bg_color  = isset($faq_cleaned_color['content_bg_color'])?$faq_cleaned_color['content_bg_color']:'';
		}else{
			$title_color       = isset($general_settings['title_color'])?$general_settings['title_color']:$settings_fields['title_color']['value'];
			$title_bg_color    = isset($general_settings['title_bg_color'])?$general_settings['title_bg_color']:$settings_fields['title_bg_color']['value'];
			$content_color     = isset($general_settings['content_color'])?$general_settings['content_color']:$settings_fields['content_color']['value'];
			$content_bg_color  = isset($general_settings['content_bg_color'])?$general_settings['content_bg_color']:$settings_fields['content_bg_color']['value'];   
		}
		$data = array(
			'title_color'      => $title_color,
			'title_bg_color'   => $title_bg_color,
			'content_color'    => $content_color,
			'content_bg_color' => $content_bg_color,
		);
		return $data;
	}

	public function like_option($post_id,$key,$like_user_ids,$dislike_user_ids,$user_id){ 
		$liked_user = !empty($like_user_ids) ? explode(',', $like_user_ids): array();
		$dislikeliked_user = !empty($dislike_user_ids) ? explode(',', $dislike_user_ids): array();
		$like_count = is_array($liked_user) ? count($liked_user) : 0;
		$dislike_count = is_array($dislikeliked_user) ? count($dislikeliked_user) : 0;
		$l_color = in_array($user_id, $liked_user) ? 'color:black' : '';
		$d_color = in_array($user_id, $dislikeliked_user) ? 'color:black' : '';
		$user_login = $user_id<=0 ? wp_login_url( get_permalink() ) : '#';
		?>

		<span class="th-like-wrapper">		
			<a href="<?php echo esc_attr($user_login); ?>" onclick="likeDislikeOption(this)" data-user_id="<?php echo esc_attr($user_id); ?>" class="thfaq-thums-up" data-_wp_thfaqld_nonce="<?php echo wp_create_nonce('thfaqld_nonce');?>" data-post_id="<?php echo esc_attr($post_id);?>" data-uid="<?php echo esc_attr($key);?>" data-value="like" data-action="like_dislike_option"><i style="<?php echo esc_attr($l_color); ?>" class="thfaq-icomoon icon-thumb_up_alt"></i></a>
			<span class="thfaq-like-count"><?php echo esc_html($like_count);?></span>  
			<a href="<?php echo esc_attr($user_login); ?>" onclick="likeDislikeOption(this)" data-user_id="<?php echo esc_attr($user_id); ?>" class="thfaq-thums-down" data-post_id="<?php echo esc_attr($post_id);?>" data-_wp_thfaqld_nonce="<?php echo wp_create_nonce('thfaqld_nonce');?>" data-uid="<?php echo esc_attr($key);?>" data-value="dislike"  data-action="like_dislike_option"><span class="th-dislike-img"><i style="<?php echo esc_attr($d_color); ?>" class="thfaq-icomoon icon-thumb_down"></i></span></a>
			<span class="thfaq-dislike-count"><?php echo esc_html($dislike_count);?></span>
		</span>
		<?php
	}

	public function faq_search_option($show_updated_date){
		?>
		<div class="thfaqf-main thfaqf-faq-last-updated" style="<?php echo !$show_updated_date ? 'margin: 1.5rem 0!important' : ''; ?>">
			<div class="thfaqf-form-group thfaqf-has-search">
				<span class="faq-search-area">
			    	<span class="fas fa-search thfaqf-form-control-faq"></span>
					<input type="search" class="faq-search" id="faq_search" name="faq_search" placeholder="<?php echo esc_attr(apply_filters('thfaq_search_placeholder', 'Search FAQs'));?>" onkeyup="faq_search_option(this)">
				</span>
			</div>  
		</div>
		<?php
	}

	public function like_dislike_option(){
		if (! isset( $_REQUEST['_wp_thfaqld_nonce'] ) || ! wp_verify_nonce( $_REQUEST['_wp_thfaqld_nonce'], 'thfaqld_nonce')) {
	    	$responce = array( 'verify_nonce' => '<span class="thfaqf-error-submt">Sorry, your nonce did not verify.</span>');
	        wp_send_json($responce);
	    }else{
			global $current_user;
			$user_id = $current_user->ID;
	       	$value = isset($_REQUEST['value']) ? trim($_REQUEST['value']) : false;
	       	$faq_uid = isset($_REQUEST['uid']) ? trim($_REQUEST['uid']) : false;
	       	$post_id = isset($_REQUEST['post_id']) ? trim($_REQUEST['post_id']) : false;
	       	$faq_data = get_post_meta($post_id, THFAQF_Utils::OPTION_KEY_FAQ_ITEMS, true);
		   	$value = sanitize_text_field($value);
		   	$faq_uid  = sanitize_text_field($faq_uid);
		   	$post_id  = sanitize_text_field($post_id);
		   	$result = array();

	        if ($user_id >0){
	        	$faq_liked_user_string = !empty($faq_data[$faq_uid]['like_user_ids']) ? trim($faq_data[$faq_uid]['like_user_ids']) : false;
        		$faq_disliked_user_string = !empty($faq_data[$faq_uid]['dislike_user_ids']) ? trim($faq_data[$faq_uid]['dislike_user_ids']) : false;
        		$faq_liked_user_array = explode(',', $faq_liked_user_string);
        		$faq_disliked_user_array = explode(',', $faq_disliked_user_string);

	        	if($value =='like'){
                    if($faq_disliked_user_string){
	        		    foreach ($faq_disliked_user_array as $key => $id){
		                	if($user_id == $id){
		                		unset($faq_disliked_user_array[$key]);
		                	}
		                }
		            }

	                if($faq_liked_user_string){
		        		if(!in_array($user_id, $faq_liked_user_array)){
							array_push($faq_liked_user_array,$user_id);
						}else{
			                foreach ($faq_liked_user_array as $key => $id){
			                	if($user_id == $id){
			                		unset($faq_liked_user_array[$key]);
			                	}
			                }
						}
					}else{
						$faq_liked_user_array = array($user_id);
			    	}
					
				}else{
					if($faq_liked_user_string){
					    foreach ($faq_liked_user_array as $key => $id){
		                	if($user_id == $id){
		                		unset($faq_liked_user_array[$key]);
		                	}
		                }
		            }

	                if($faq_disliked_user_string){
						if(!in_array($user_id, $faq_disliked_user_array)){
							array_push($faq_disliked_user_array,$user_id);
						}else{
			                foreach ($faq_disliked_user_array as $key => $id){
			                	if($user_id == $id){
			                		unset($faq_disliked_user_array[$key]);
			                	}
			                }
						}
					}else{
						$faq_disliked_user_array = array($user_id);
			    	}
				}
                
                $faq_liked_user_array = array_filter($faq_liked_user_array);
                $faq_disliked_user_array = array_filter($faq_disliked_user_array);
				$user_like = implode(',', $faq_liked_user_array); 
				$user_dislike = implode(',', $faq_disliked_user_array); 
				$faq_data[$faq_uid]['like_user_ids'] = $user_like;
				$faq_data[$faq_uid]['dislike_user_ids'] = $user_dislike;
				$update = update_post_meta($post_id, THFAQF_Utils::OPTION_KEY_FAQ_ITEMS,$faq_data);
				$result = array('like_user_ids' => $faq_liked_user_array,'dislike_user_ids' => $faq_disliked_user_array,'current_user_id' => $user_id);

				if($update)
					wp_send_json($result);
	        } 
	    }
	}

	public function get_additional_css_for_faqs($id){
	    $global_settings = THFAQF_Utils::get_faq_settings();
		$css = isset($global_settings['thfaq_custom_css']) ? $global_settings['thfaq_custom_css'] : '';
		$enable = isset($global_settings['enable_thfaq_custom_css']) ? $global_settings['enable_thfaq_custom_css'] : '';
		$local_settings = get_post_meta($id, THFAQF_Utils::OPTION_KEY_FAQ_SETTINGS_POST, true);
		$settings = $this->prepare_faq_settings($global_settings, $local_settings);
		$title_active_color = isset($settings['title_active_color']) ? $settings['title_active_color'] : '#cc2753';
		$tab_active_color = isset($settings['tab_active_color']) ? $settings['tab_active_color'] : '#cc2753';
    	$tab_bg_color = isset($settings['tab_bg_color']) ? $settings['tab_bg_color'] : '#f5f5f5';
        ?>
        <style type="text/css">
        	<?php echo $enable ? esc_attr($css) : ''; ?>
        	.thfaqf-tab h3.thfaqf-tablinks-<?=$id?>.active {
			    background-color: <?=$tab_bg_color?>!important;
			    color: <?=$tab_active_color?>!important;
			}
			.thfaqf-tab h3.thfaqf-tablinks-<?=$id?>:hover {
			  	background-color: <?=$tab_bg_color?>!important;
			}
		    .thfaqf-faq-item-<?=$id?>.thfaqf-active .thfaqf-title-text{
				color: <?=$title_active_color?>!important;
			}
          
        </style>
       <?php
    }

	public function thfaq_add_body_class($classes){
	    $classes[] = 'thfaq-wrapper-body';
	    return $classes;   
	}

}//end class

endif;

