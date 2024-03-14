<?php
/**
 * The template for displaying ip-tag archive pages
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<?php
					the_archive_title( '<h1 class="page-title">', '</h1>' );
					the_archive_description( '<div class="taxonomy-description">', '</div>' );
				?>
			</header><!-- .page-header -->
            
            
            
            

			<?php
            

            //enqueue scripts and styles
            wp_enqueue_style(array('custom-frontend-style-ideapush','ideapush-font'));
            wp_enqueue_script(array('alertify','custom-frontend-script-ideapush','scroll-reveal','read-more','custom-frontend-script-ideapush-pro'));

            //get options
            $options = get_option('idea_push_settings');            
            
            $primaryColor = esc_html($options['idea_push_primary_link_colour']);
            
            //lets output some css
            echo '<style>
            
                .ideapush-container i, .ideapush-container a, .ideapush-container .idea-item-tag:hover, .ideapush-container .idea-item-file:hover, .ideapush-dialog .close-button, .ideapush-dialog-image .close-button, .idea-page-number.active,.idea-page-number:hover
                {color: '.$primaryColor.';}


                .submit-new-idea,.submit-new-idea:hover,.submit-new-idea:focus, .ideapush-dialog .ui-button,.ideapush-dialog .ui-button:hover,.ideapush-dialog .ui-button:focus, .admin-star-outer,.admin-star-outer-large
                {background: '.$primaryColor.';}
                    
                .ideapush-container .idea-item-tag:hover, .ideapush-container .idea-item-file:hover, .ideapush-dialog .ui-button,.ideapush-dialog .ui-button:hover,.ideapush-dialog .ui-button:focus, .ideapush-dialog .close-button, .ideapush-dialog-image .close-button, .idea-page-number.active,.idea-page-number:hover
                {border-color: '.$primaryColor.';}

                .alertify .cancel
                {color: '.$primaryColor.' !important;}

                .alertify .ok, .alertify .cancel
                {border-color: '.$primaryColor.' !important;}

                .alertify .ok
                {background-color: '.$primaryColor.' !important;}

            </style>';
 
            // $multiIp = $options['idea_push_tag_multiple_ips'];

            // if(!isset($multiIp)){
            //     $multiIp = 'No';
            // }

            $currentUserId = idea_push_check_if_non_logged_in_user_is_guest('No');

            if($currentUserId == false){
                $currentUserRole = array();
            } else {
                $currentUserObject = get_user_by( 'id', $currentUserId);
                $currentUserRole = $currentUserObject->roles;
            }
            

            // var_dump($boardSettings);
            
            $paginationNumber = $options['idea_push_tag_pagination_number'];

            if(!isset($paginationNumber)){
                $paginationNumber = 0;
            }
        
            //this is used for pagination
            $ideaNumber = 1;

            //check if pagination required
            if($paginationNumber > 0){
                $paginationEnabled = true;
            } else {
                $paginationEnabled = false;    
            }

            
            
            echo '<div class="ideapush-container">';
//            echo '<div class="ideapush-container-ideas">';
            echo '<ul class="dynamic-idea-listing">';
            
			// Start the Loop.
			while ( have_posts() ) : the_post();

                $ideaPost = get_post();

    
                $getTerms = get_the_terms($ideaPost->ID, 'status' );  
          
                foreach($getTerms as $term) {
                    $status = $term->name;    
                } 
                $status = strtolower(str_replace(" ","-",$status));
                
 
                $boardId = idea_push_get_board_id_from_post_id($ideaPost->ID);
                $boardSettings = idea_push_get_board_settings($boardId);
                $showBoardTo = $boardSettings[14];
            
                
                if(in_array($showBoardTo, $currentUserRole) || $showBoardTo == "Everyone"){ 
            
            
                    if($paginationEnabled){
                        $html = '<li data-page="'.ceil($ideaNumber/$paginationNumber).'" class="idea-item idea-item-'.$ideaPost->ID.'" hidden-idea">';    
                    } else {
                        $html = '<li class="idea-item idea-item-'.$ideaPost->ID.'"">';    
                    }   

                    //left side
                    $html .= '<div class="idea-item-left">';

                        $html .= idea_push_vote_part($ideaPost->ID,$status,$boardSettings);

                    $html .= '</div>'; //end left side


                    //right side
                    $html .= '<div class="idea-item-right">';

                        $html .= '<div class="idea-item-right-inner">';

                            //title
                            //if pro option is set
                            if(!isset($options['idea_push_disable_single_idea_page']) || $options['idea_push_disable_single_idea_page'] !== "1"){
                                $html .= '<a href="'.esc_url(get_post_permalink($ideaPost->ID)).'" class="idea-title">';
                            } else {
                                 $html .= '<a class="idea-title">';    
                            }

                            $html .= esc_html($ideaPost->post_title);
                            $html .= '</a>'; 


                            //get the meta
                            $html .= idea_push_get_idea_meta($ideaPost,$status,$boardSettings);



                            //get the content
                            $html .= '<div class="idea-item-content-container">';

                            if(!isset($options['idea_push_disable_single_idea_page']) || $options['idea_push_disable_single_idea_page'] !== "1"){

                                if(strlen($ideaPost->post_content)>200){
                                    $readMore = '... <a class="idea-read-more" href="'.esc_url(get_post_permalink($ideaPost->ID)).'">'.__('Read more','ideapush').'...</a>';
                                } else {
                                    $readMore = '';   
                                }

                                $html .= '<span class="idea-item-content">'.substr(strip_tags($ideaPost->post_content),0,200).'</span>'.$readMore;     
                            } else {

                                $html .= '<div id="ideaReadMoreText" style="display: none;">'.__('Read more','ideapush').'</div>';

                                $html .= '<div id="ideaReadLessText" style="display: none;">'.__('Read less','ideapush').'</div>';

                                $html .= '<span class="idea-item-content idea-item-content-read-more">'.strip_tags($ideaPost->post_content).'</span>';    

                            }


                            $html .= '</div>';




                            $formSettings = $boardSettings[23];
                            $currentOption = $options['idea_push_form_settings'];
                            // var_dump($currentOption);
                            if(strlen($currentOption) < 1){
                                $currentOption = '^^^^Default||| || || || || || || ||| ';
                            }
                            $explodedOptions = explode('^^^^',$currentOption);

                            foreach($explodedOptions as $formSetting){

                                $explodedSubOptions = explode('|||',$formSetting);
                        
                                $settingName = $explodedSubOptions[0];
                        
                                if($settingName == $formSettings){
                                    $customFields = $explodedSubOptions[2];
                                }
                        
                            }  

                            $custom_field_array = array();
                            $individual_custom_fields = explode('||',$customFields);

                            foreach($individual_custom_fields as $customField){
                                $further_custom_field_info = explode('|',$customField);
                                $field_type = $further_custom_field_info[0];

                                if(array_key_exists(1,$further_custom_field_info)){
                                    $field_name = $further_custom_field_info[1];
                                    //i dont think we need to get any further info for now
                                    $custom_field_array[$field_name] = $field_type;
                                }

                            }

                            //get attachment and tags
                            $html .=  idea_push_get_tags_and_attachments($ideaPost->ID,$custom_field_array,$boardId);

                        $html .= '</div>';
                    $html .= '</div>';

                $html .= '</li>'; 
                
                $ideaNumber++;    
                    
                echo $html;    
            }
            
            
            
            
            
            
            
			// End the loop.
			endwhile;
            
            
            //do pagination
            if($paginationEnabled){

                $amountOfPages = ceil(($ideaNumber-1)/$paginationNumber);

                $html = '<li class="idea-pagination">';

                    $html .= '<ul class="idea-pagination-listing">';

                        for($i=1;$i<=$amountOfPages;$i++){

                            if($i == 1){

                                $html .= '<li class="idea-page-number active idea-page-'.$i.'">'.$i.'</li>';    
                            } else {

                                $html .= '<li class="idea-page-number idea-page-'.$i.'">'.$i.'</li>';    
                            }



                        }

                    $html .= '</ul>';

                $html .= '</li>'; 

                echo $html;    

            }
            
            
            
            echo '</ul></div>';

            //edit idea popup heading
            echo '<div style="display: none;" id="dialog-edit-idea-heading" data="'.__( 'Edit Idea', 'ideapush' ).'"></div>';

            //edit idea popup heading
            echo '<div style="display: none;" id="dialog-idea-edited" data="'.__( 'The idea has been updated.', 'ideapush' ).'"></div>';

            //user delete idea dialog
            echo '<div style="display: none;" id="dialog-idea-delete" data="'.__( 'Are you sure you want to delete this idea?', 'ideapush' ).'"></div>';

            //user idea deleted dialog
            echo '<div style="display: none;" id="dialog-idea-deleted" data="'.__( 'The idea has been deleted.', 'ideapush' ).'"></div>';

            //duplicate idea
            echo '<div style="display: none;" id="dialog-idea-duplicate" data="'.__( 'Please search for and select the original idea. Once you click submit the duplicate idea will be given a duplicate status and no more votes will be allowed for this idea and votes given to this idea will be transferred to the original idea where appropriate. The author of this duplicate idea will be notified of this status change if notifications are enabled in the plugin settings.', 'ideapush' ).'"></div>';

            //duplicate idea placeholder
            echo '<div style="display: none;" id="duplicate-idea-placeholder" data="'.__( 'Enter original idea title here', 'ideapush' ).'"></div>';

            //buttons
            echo '<div style="display: none;" id="ok-cancel-buttons" data-submit="'.__( 'Submit', 'ideapush' ).'" data-cancel="'.__( 'Cancel', 'ideapush' ).'" data-yes="'.__( 'Yes', 'ideapush' ).'" data-no="'.__( 'No', 'ideapush' ).'"></div>';

            //duplicate idea success
            echo '<div style="display: none;" id="dialog-idea-duplicate-success" data="'.__( 'Idea successfully marked as duplicate', 'ideapush' ).'"></div>';
            
            //output duplicate idea
            global $ideapush_is_pro;

            //add suggested idea if pro
            if($ideapush_is_pro == 'YES'){

                //output suggested tags
                //temporarily lets make the ideascope 'Board'
               
                $ideaScope = 'Global';
        
                //we want to output this anyway even if suggested ideas are disabled because we can use this for the duplicate idea feature
                echo idea_push_output_suggested_ideas($ideaScope,$boardId);
                
        
            }


			// Previous/next page navigation.
			the_posts_pagination( array(
				'prev_text'          => __( 'Previous page', 'ideapush' ),
				'next_text'          => __( 'Next page', 'ideapush' ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'ideapush' ) . ' </span>',
			) );

		// If no content, include the "No posts found" template.
		else :
			echo '<div>'.__( 'There are no ideas found for this tag.', 'ideapush' ).'</div>';
		endif;
		?>

		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
