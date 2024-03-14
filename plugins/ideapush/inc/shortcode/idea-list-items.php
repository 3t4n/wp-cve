<?php

    function idea_push_list_items($boardId,$order,$status,$tag,$customFieldFilter){
        
        //get all posts
        global $post;
        
        //get options
        $options = get_option('idea_push_settings');

        global $ideapush_is_pro;

        $individualBoardSetting = idea_push_get_board_settings($boardId);
        $multiIp = $individualBoardSetting[27];


        $formSettings = $individualBoardSetting[23];
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

        if($ideapush_is_pro == "YES"){
            $individual_custom_fields = explode('||',$customFields);

            foreach($individual_custom_fields as $customField){
                $further_custom_field_info = explode('|',$customField);

                //only continue if there's a field name
                if(array_key_exists(1,$further_custom_field_info)){
                    $field_type = $further_custom_field_info[0];
                    $field_name = $further_custom_field_info[1];
                    //i dont think we need to get any further info for now
                    $custom_field_array[$field_name] = $field_type;
                }
            }
        }


        if(!isset($multiIp)){
            $multiIp = 'No';
        }
    
        //get current user
        $currentUser = idea_push_check_if_non_logged_in_user_is_guest($multiIp);
        
        //get board settings
        $boardSettings = idea_push_get_board_settings($boardId);
        
        $paginationNumber = $boardSettings[15];
        
        //this is used for pagination
        $ideaNumber = 1;
        
        //check if pagination required
        if(isset($paginationNumber) && $paginationNumber>0){
            $paginationEnabled = true;
        } else {
            $paginationEnabled = false;    
        }


        //create html holding variable that is returned at the end
        $html = '';


        //so we have the variables order, status and tag that we need to contend with, the board id is set in stone
        //so lets start building our query

        //this is the base query as it should be common for all
        $args = array(
            'suppress_filters' => 0,
            'post_type' => 'idea',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'tax_query' => array("relation" => "AND",
            array(
                'taxonomy' => 'boards',
                'field'    => 'term_id',
                'terms'    => array($boardId)
            )),
        );

        // var_dump($args);


        // echo '<strong>order is:'.$order.' status is:'.$status.' tag is:'.$tag.'</strong>';

        //lets start with the status component of the query
        //if the status is not show all statuses then lets add the status argument to the query
        if($status !== 'all-statuses'){

            //get original value
            $originalValue = $args['tax_query'];

            //length of array
            $lengthOfArray = count($originalValue);

            $originalValue[$lengthOfArray-1] = array(
                'taxonomy' => 'status',
                'field'    => 'slug',
                'terms'    => array($status)
            );

            $args['tax_query'] = $originalValue;
        }



        //do custom field filters if necessary
        if(strlen($customFieldFilter)>0){
            //this looks like colour|red||flavour|chocolate
            //do each custom field filter
            $meta_query_array = array('relation'=>'AND');

            //explode custom fields
            $customFieldFilters = explode('||',$customFieldFilter);

            foreach($customFieldFilters as $customFieldFilter){

                $customFieldFilterOptions = explode('|',$customFieldFilter);
                $custom_filter_name = $customFieldFilterOptions[0]; 
                //note meta is actually like: ideapush-custom-field-Colour
                $custom_filter_name = 'ideapush-custom-field-'.$custom_filter_name;

                $custom_filter_value = $customFieldFilterOptions[1];

                //if the value is all, don't do anything
                if($custom_filter_value != 'all'){
                    array_push($meta_query_array,array('key'=>$custom_filter_name,'value'=>$custom_filter_value));
                }

                

            }

            //add to main query
            $args['meta_query'] = $meta_query_array;

        }



        //now lets deal with the tags
        if($tag !== 'all'){
            
            //get original value
            $originalValue = $args['tax_query'];

            //length of array
            $lengthOfArray = count($originalValue);

            //now lets add our array to the end
            $originalValue[$lengthOfArray-1] = array(
                'taxonomy' => 'tags',
                'field'    => 'term_id',
                'terms'    => array($tag)
            );

            $args['tax_query'] = $originalValue;
        }




        //now we have to deal with the order
        //lets deal with this as a switch statement
        switch ($order) {

            case "popular":
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'DESC';
                $args['meta_key'] = 'votes';
                break;

            case "recent":
                $args['orderby'] = 'publish_date';
                $args['order'] = 'DESC';
                $args['meta_key'] = '';
                break;

            case "trending":
            
                //we need to get all posts and loop through them, and for each we need to get the date published and work out the amount of days between them and now and divide this by the votes and then update the post meta
                
                //we can use our existing argument array even though it's not fully finished at this point.
                $ideaPostsTrending = get_posts($args);
                
                $todaysDate = new DateTime('now');
                
                foreach($ideaPostsTrending as $ideaPostTrending){
                    
                    $postDate = new DateTime($ideaPostTrending->post_date);
                    
                    $dateDifference = $postDate->diff($todaysDate);
                    
                    //we add plus one do division isn't done by 0 if the post was published today
                    $daysDifference = $dateDifference->format('%a')+1;
                    
                    //get amount of votes
                    $amountOfVotes = get_post_meta($ideaPostTrending->ID, 'votes', true );
                    
                    if(empty($amountOfVotes)){
                        $amountOfVotes = 0;
                        
                        //for good measure lets create a votes score - maybe if someone was to manually delete it from the post or something - but it really show be created when first creating the post via the form
                        update_post_meta($post_id = $ideaPostTrending->ID, $key = 'votes', $value = 0);
                        
                    }
                    
                    if($amountOfVotes == 0){
                        $trendScore = 0;
                    } else {
                        $trendScore = $amountOfVotes/$daysDifference;   
                    }
                    
                    //lets now update the meta
                    update_post_meta( $post_id = $ideaPostTrending->ID, $key = 'trendscore', $value = $trendScore);
                    
                }

                $args['orderby'] = 'meta_value';
                $args['order'] = 'DESC';
                $args['meta_key'] = 'trendscore';
                break;

            case "my-voted":
                $args['orderby'] = 'publish_date';
                $args['order'] = 'DESC';
                $args['meta_key'] = '';
                break;    
            
            //its "my" order query
            default:
                $args['orderby'] = 'publish_date';
                $args['order'] = 'DESC';
                $args['meta_key'] = '';
                $args['author'] = $order;

        }

        //lets print the array for testing
        // var_dump($args);
        // print_r($args);

        //run the query
        $ideaPosts = get_posts($args);
        
        //if there are no posts show an error
        if(empty($ideaPosts)){
            return '<li class="idea-item no-ideas-message">'.__('No ideas found matching this criteria','ideapush').'</li>';    
        }
        
        

        /**
        * 
        *
        *
        * Start looping through each idea
        */
        foreach($ideaPosts as $ideaPost){
        
             
            //if the order is set to my-voted then check to see if current user is a positive voter of the id    
            if($order=='my-voted'){

                //get positive voters of idea
                $positiveVoters = get_post_meta($ideaPost->ID,'up-voters',true);

                $positiveVotersArray = array();

                foreach($positiveVoters as $voter){
                    array_push($positiveVotersArray,$voter[0]);
                }


                //get current user id
                $currentUserId = idea_push_check_if_non_logged_in_user_is_guest($multiIp);

                if(!in_array($currentUserId,$positiveVotersArray)){

                    continue;    
                } 
            }    
            
            //we need to rename the status variable to the status of the post as oppose to the status sent to the function as we are now enabling all ideas
            $status = get_post_meta($ideaPost->ID,'current-status',true);

       
            if($paginationEnabled){
                $html .= '<li data-page="'.ceil($ideaNumber/$paginationNumber).'" class="idea-item idea-item-'.$ideaPost->ID.' idea-status-'.$status.' hidden-idea">';    
            } else {
                $html .= '<li class="idea-item idea-item-'.$ideaPost->ID.' idea-status-'.$status.'">';    
            }    
            
            

                


                if(!isset($status) || strlen($status)<1){

                    //get current status
                    $currentStatus = get_the_terms( $ideaPost->ID, 'status' );

                    $status = $currentStatus[0]->slug;

                    //update the post meta for ideas created in the backend
                    update_post_meta($ideaPost->ID, 'current-status', $status );

                }

                // //do action here for before item
                // $html .= do_action( 'idea_push_before_idea',$ideaPost->ID);

                //left side
                $html .= '<div class="idea-item-left">';

                    //do votes
                    $html .= idea_push_vote_part($ideaPost->ID,$status,$boardSettings);

                $html .= '</div>'; //end left side
                
            
                //right side
                $html .= '<div class="idea-item-right">';
                
                    $html .= '<div class="idea-item-right-inner">';
                        
            
                        
                        //do before title filter
                        $html .= apply_filters( 'idea_push_before_idea_title', '', $ideaPost->ID );
                    
                        //title
                        //if pro option is set
                        if(!isset($options['idea_push_disable_single_idea_page']) || $options['idea_push_disable_single_idea_page'] !== "1"){
                            $html .= '<a href="'.esc_url(get_post_permalink($ideaPost->ID)).'" class="idea-title">';
                        } else {
                             $html .= '<a class="idea-title">';    
                        }
            
                        $html .= esc_html($ideaPost->post_title);
                        $html .= '</a>'; 
                        
                        //do before title filter
                        $html .= apply_filters( 'idea_push_after_idea_title', '', $ideaPost->ID );


                        //get the meta
                        $html .= idea_push_get_idea_meta($ideaPost,$status,$boardSettings);
                        
                        
                        
                        //get the content
                        $html .= '<div class="idea-item-content-container">';

                            //show feature image now if pro
                            if($ideapush_is_pro == "YES" && isset($options['idea_push_show_image_inline'])){
                                if(get_the_post_thumbnail_url($ideaPost->ID) !== false){
                                    $html .= '<img class="idea-item-attachment-inline" src="'.get_the_post_thumbnail_url($ideaPost->ID,'full').'">';
                                }    
                            }

                            //show additional images here


                            //show video and images if pro
                            if($ideapush_is_pro == "YES" && isset($options['idea_push_show_custom_fields'])){

                                $postMeta = get_post_meta($ideaPost->ID,'',true);
                
                                foreach($postMeta as $key => $value){
                
                                    //check if key has our special prefix
                                    if(strpos($key, 'ideapush-custom-field-') !== false) {
                
                                        //dont show if youtube or vimeo link as we want to show that inline instead
                                        if(strpos(strtolower($value[0]), 'youtube.com') !== false){
                                            //do youtube
                                            $parts = parse_url($value[0]);
                                            parse_str($parts['query'], $query);
                                            $video_id = $query['v'];
                                            $video_id_exploded = explode(' ',$video_id);
                                            $video_id = $video_id_exploded[0];

                                            $html .= '<div class="idea-item-attachment-inline-youtube"><iframe width="853" height="480" src="https://www.youtube-nocookie.com/embed/'.$video_id.'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>';
                                        }

                                        if(strpos(strtolower($value[0]), 'vimeo.com') !== false){
                                            //do vimeo
                                            $positionOfId = strpos ( $value[0] ,'vimeo.com/' );
                                            $video_id = substr ($value[0] ,$positionOfId+10 );
                                            $video_id_exploded = explode(' ',$video_id);
                                            $video_id = $video_id_exploded[0];

                                            $html .= '<div class="idea-item-attachment-inline-vimeo" style="padding:56.25% 0 0 0;position:relative;"><iframe src="https://player.vimeo.com/video/'.$video_id.'" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div><script src="https://player.vimeo.com/api/player.js"></script>';
                                        }

                                        //do images
                                        $actual_field_name = str_replace('ideapush-custom-field-','',$key);

                                        if(array_key_exists($actual_field_name,$custom_field_array)){

                                            if($custom_field_array[$actual_field_name] == 'image'){

                                                if($value[0]){
                                                    $image_url = wp_get_attachment_image_src( $value[0], 'full',false );

                                                    if(!$image_url){
                                                        $image_url = wp_get_attachment_url( $value[0] ); 

                                                        $html .= '<a class="idea-item-attachment-inline-file" target="_blank" href="'.$image_url.'">'.__('Download file','ideapush').'</a>';

                                                    } else {
                                                        $image_url = $image_url[0];
                                                        $html .= '<img class="idea-item-attachment-inline-image" src="'.$image_url.'">'; 
                                                    }
                                                    
                                                }
                                                


                                            }
                                        }

                
                                    }    
                                } 
                            }

                            //strip html or not
                            if( isset($options['idea_push_allow_html_input']) ){

                                if( isset($options['idea_push_render_html']) ){
                                    $idea_description = $ideaPost->post_content;
                                } else {
                                    $idea_description = htmlentities($ideaPost->post_content);
                                }
                                
                            } else {
                                $idea_description = strip_tags($ideaPost->post_content);
                            }

                            if(!isset($options['idea_push_disable_single_idea_page']) || $options['idea_push_disable_single_idea_page'] !== "1"){
                                
                                if(strlen($ideaPost->post_content)>200){
                                    $readMore = '... <a class="idea-read-more" href="'.esc_url(get_post_permalink($ideaPost->ID)).'">'.__('Read more','ideapush').'...</a>';
                                } else {
                                    $readMore = '';   
                                }
        
                                $html .= '<span class="idea-item-content">'.mb_substr($idea_description,0,200).'</span>'.$readMore;     
                            } else {
                                
                                $html .= '<div id="ideaReadMoreText" style="display: none;">'.__('Read more','ideapush').'</div>';
                                
                                $html .= '<div id="ideaReadLessText" style="display: none;">'.__('Read less','ideapush').'</div>';
                                


                                $html .= '<span class="idea-item-content idea-item-content-read-more">'.$idea_description.'</span>';    
                                
                            }
                        
            
                        $html .= '</div>';
    
                
                        //get attachment and tags
                        $html .=  idea_push_get_tags_and_attachments($ideaPost->ID,$custom_field_array,$boardId);
                    
                    $html .= '</div>';
                $html .= '</div>';
                
                //do action here for after item
                $html .= apply_filters('idea_push_after_idea', '',$ideaPost->ID);


            $html .= '</li>';  
            
            
            //add one to the idea number
            $ideaNumber++;
            
        } //end loop for each post idea
        



        /**
        * 
        *
        *
        * Do pagination
        */
        if($paginationEnabled){
            
            $amountOfPages = ceil(($ideaNumber-1)/$paginationNumber);
            
            $html .= '<li class="idea-pagination">';
            
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
            
        }
  
        return $html;  
    }

    


    function idea_push_fetch_new_list_items(){

        $boardNumber = idea_push_sanitization_validation($_POST['boardNumber'],'id');


        if($boardNumber == false){
            wp_die();      
        }
        
        $sortFilter = $_POST['sortFilter'];
        $statusFilter = $_POST['statusFilter'];
        $tagFilter = $_POST['tagFilter'];
        $customFieldFilter = $_POST['customFieldFilter'];

        echo idea_push_list_items($boardNumber,$sortFilter,$statusFilter,$tagFilter,$customFieldFilter);
        
        wp_die(); 

    } //end function
    add_action( 'wp_ajax_get_new_ideas', 'idea_push_fetch_new_list_items' );
    add_action( 'wp_ajax_nopriv_get_new_ideas', 'idea_push_fetch_new_list_items' );

    

    function idea_push_get_tags_and_attachments($postId,$custom_field_array,$boardId){
        
        //get options
        $options = get_option('idea_push_settings');
        global $ideapush_is_pro;

        


        $html = '';
        $html .= '<div class="idea-item-additional-meta">';

            //only show attachments if inline image option not set
            if(!isset($options['idea_push_show_image_inline'])){

                //do attachments
                if(get_the_post_thumbnail_url($postId) !== false){

                    $html .= '<span class="idea-item-attachment"><i class="ideapush-icon-Image"></i>';

                    $html .= '<a target="_blank" href="'.esc_url(get_the_post_thumbnail_url($postId)).'" class="idea-item-file">'.__( 'Attachment','ideapush' ).'</a>';   

                    $html .= '</span>';    

                }
            }


            //do files
            if( strlen(get_post_meta($postId,'idea-attachment',true))>0 ){

                $ideaAttachment =  get_post_meta($postId,'idea-attachment',true);

                $html .= '<span class="idea-item-attachment-file"><i class="ideapush-icon-File"></i>';

                $html .= '<a target="_blank" href="'.esc_url($ideaAttachment).'" class="idea-item-file-download">'.__( 'File','ideapush' ).'</a>';   

                $html .= '</span>';    

            }



            //do tags
            $ideaTags = get_the_terms($postId,'tags');
            //check to see if tags exist
            if(!empty($ideaTags)){    

                $html .= '<span class="idea-item-tags"><i class="ideapush-icon-Tag"></i> ';

                foreach($ideaTags as $ideaTag){

                    if(strpos($ideaTag->name, 'BoardTag-') !== false){

                        $positionOfSecondHyphen = strpos($ideaTag->name, '-', strpos($ideaTag->name, '-') + 1);

                        $tagName = substr($ideaTag->name,$positionOfSecondHyphen+1,strlen($ideaTag->name)-$positionOfSecondHyphen);

                    } else {
                        $tagName = $ideaTag->name;
                    }
                    
                    $html .= '<a href="'.esc_url(get_tag_link($ideaTag->term_id)).'" class="idea-item-tag">'.esc_html($tagName).'</a>';    
                }    

                $html .= '</span>';
            }


            //do custom fields
            //only do if pro and it is enabled in the settings do it
            

            if($ideapush_is_pro == "YES" && isset($options['idea_push_show_custom_fields'])){

                $individualBoardSetting = idea_push_get_board_settings($boardId);
                




                $postMeta = get_post_meta($postId,'',true);

                $customFieldBuilder = '';

                foreach($postMeta as $key => $value){

                    // var_dump($key);
                    // var_dump($value);

                    //check if key has our special prefix
                    if(strpos($key, 'ideapush-custom-field-') !== false) {

                        //we need to get the actual field name and get the type from our settings rather then checking for the value
                        $actual_field_name = str_replace('ideapush-custom-field-','',$key);

                        // if($custom_field_array[$actual_field_name] != 'video' || $custom_field_array[$actual_field_name] != 'image'){

                        // }
                        // var_dump($actual_field_name);

                        //dont show if youtube or vimeo link as we want to show that inline instead
                        if(is_array($custom_field_array) && array_key_exists($actual_field_name,$custom_field_array)){
                            if(
                                strpos(strtolower($value[0]), 'youtube.com') === false && 
                                strpos(strtolower($value[0]), 'vimeo.com') === false &&
                                $custom_field_array[$actual_field_name] != 'image'
                            ){

                                $separator = apply_filters('idea_push_custom_field_separator', ', ');

                                $actual_field_name_as_class = str_replace(' ','-',$actual_field_name);
                                $actual_field_name_as_class = strtolower($actual_field_name_as_class);

                                $customFieldBuilder .= '<span class="ideapush-custom-field-'.$actual_field_name_as_class.'"><strong>'.$actual_field_name.':</strong> ';

                                //we are going to check if date or website
                                if(substr( $value[0], 0, 4 ) === "http"){
                                    $customFieldBuilder .= '<a href="'.$value[0].'" target="_blank">'.$value[0].'</a>'.$separator;
                                } elseif(preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$value[0])){
                                    $customFieldBuilder .= date_i18n(get_option( 'date_format' ),strtotime($value[0])).$separator;   
                                } else {
                                    $customFieldBuilder .= $value[0].$separator;
                                }

                                $customFieldBuilder .= '</span>';

                            }
                        }


                    }    

                } 

                if(strlen($customFieldBuilder) > 0){
                    $html .= '<span class="idea-item-custom-fields"><i class="ideapush-icon-List"></i> ';
                }

                    $customFieldBuilder = rtrim($customFieldBuilder,', ');

                    $html .= '<span class="custom-field-content">'.$customFieldBuilder.'</span>';

                $html .= '</span>';


            }



        
        $html .= '</div>';
        return $html;

    }





    function idea_push_get_idea_meta($ideaPost,$status,$boardSettings){
        
        global $ideapush_is_pro;

        //get options
        $options = get_option('idea_push_settings');
        
        $html = '';
        
        $html .= '<div class="idea-item-meta">';
                        
        //date published
        $date_of_idea = apply_filters('idea_push_time_ago_prefix','').sprintf( __( '%s', 'ideapush' ), human_time_diff( strtotime($ideaPost->post_date), current_time( 'timestamp' ))).' '.__('ago','ideapush');

        $html .= '<span class="idea-item-date">'.esc_html($date_of_idea).'</span>';

        //author
        $html .= '<div class="idea-author-container">';
            $html .= '<img class="idea-author-profile-image" src="'.esc_url(idea_push_get_user_avatar(get_the_author_meta('ID',$ideaPost->post_author))).'" />';
        
            //do admin star            
            if(idea_push_is_user_admin(get_the_author_meta('ID',$ideaPost->post_author))){
                $html .= '<span class="admin-star-outer"><i class="ideapush-icon-Star"></i></span>';    
            }

            $html .= '<a href="'.esc_url(idea_push_get_user_author_page(get_the_author_meta('ID',$ideaPost->post_author))).'" class="idea-author">'.esc_html(idea_push_get_user_name(get_the_author_meta('ID',$ideaPost->post_author))).'</a>';
        $html .= '</div>';

        //status
        //here we need to change the status to the translated status, a little painful but yeah
        
        $html .= '<span class="status-container">'.idea_push_render_status($status,'ONLY').'</span>';


        //comments
        //we only want to show comments if comments are enabled for this board                    
        $showComments = $boardSettings[4];

        if($showComments == 'Yes'){

            //change class if no comments
            if($ideaPost->comment_count == 0){
                $commentClass = 'no-comments-idea';
            } else {
                $commentClass = '';    
            }

            //comment link
            global $wp;
            $currentPageUrl = home_url( $wp->request ).'/';

            if($currentPageUrl == get_post_permalink($ideaPost->ID)){
                $commentLink = '#comments';       
            } else {
                $commentLink = esc_url(get_post_permalink($ideaPost->ID)).'#comments';   
            }
            

            //check if pro
            //if pro show inline comments
            //otherwise check if single idea page is disabled

            if($ideapush_is_pro == 'YES' && !isset($options['idea_push_disable_inline_comments'])){
                $html .= '<a data="'.$ideaPost->ID.'" class="idea-item-comments-inline idea-item-comments '.esc_html($commentClass).'" >';    
            } else {
                if(!isset($options['idea_push_disable_single_idea_page']) || $options['idea_push_disable_single_idea_page'] !== "1"){
                    $html .= '<a class="idea-item-comments '.esc_html($commentClass).'" href="'.esc_url($commentLink).'">';  
                } else {
                    $html .= '<a class="idea-item-comments '.esc_html($commentClass).'" >';     
                }
            }
            
            
            
            
            $html .= esc_html($ideaPost->comment_count).'<i class="ideapush-icon-Comment comments-icon"></i>';
            
            
            $html .= '</a>';    
           
            
        }

        

        if($ideapush_is_pro == 'YES'){
            //do user edit/delete if enabled
            if(isset($boardSettings[25]) && $boardSettings[25] == 'Yes'){

                //only show if the user has permission to do the action
                if(get_the_author_meta('ID',$ideaPost->post_author) == get_current_user_id()){
                    $html .= idea_push_user_edit_delete($ideaPost->ID);
                }

            }

            //if the user is admin also enable duplicate feature
            if( current_user_can('editor') || current_user_can('administrator') ||  current_user_can('idea_push_manager')) {
                $html .= idea_push_admin_duplicate($ideaPost->ID);
            }
            

        }

        


        $html .= '</div>';

        return $html;
        
        
    }



function idea_push_vote_part($postId,$status,$boardSettings){
    
    if(!isset($boardSettings[27])){
        $multiIp = 'No';
    } else {
        $multiIp = $boardSettings[27];    
    }

    //get current user
    $currentUser = idea_push_check_if_non_logged_in_user_is_guest($multiIp);
    
    $html = '';
    
    $html .= '<div class="idea-vote-container" data="'.esc_attr($postId).'">';

        $voteAmount = get_post_meta($postId,'votes',true);

        if(!isset($voteAmount) || strlen($voteAmount) == 0){
            $voteAmount = 0;
        }

    
        $html .= '<span class="idea-vote-number">'.esc_html($voteAmount).'</span>';

        $statuses_to_allow_voting = apply_filters('idea_push_voting_statuses', array('open'));

        //we only want to show voting for open statuses
        if( in_array($status, $statuses_to_allow_voting) ){    
            

            //now we need to check whether guest voting is enabled
            if( $boardSettings[9] == "Yes" || $currentUser !== false ){
                
                global $ideapush_is_pro;
                
                //only get the following settings if pro
                if($ideapush_is_pro == 'YES'){
                    $enableChallenge = $boardSettings[16];
                    $challengeDate = $boardSettings[18];
                    $challengeTime = $boardSettings[19];
                }

                //do challenge check
                if(($ideapush_is_pro == 'NO') || (!isset($enableChallenge)) || ($ideapush_is_pro == 'YES' && $enableChallenge !== 'Yes') || ($ideapush_is_pro == 'YES' && $enableChallenge == 'Yes' && strtotime(current_time('mysql')) < strtotime($challengeDate.' '.$challengeTime.':00'))){
               
                    $downVotersArray = idea_push_get_voter_array($postId,'down-voters');
                    $upVotersArray = idea_push_get_voter_array($postId,'up-voters');

                    if(idea_push_check_voter_history($upVotersArray,$currentUser) && $currentUser !== false){
                        $html .= '<i class="ideapush-icon-Up-Vote-Solid vote-up-unvoted"></i>';  
                    } else {
                        $html .= '<i class="ideapush-icon-Up-Vote vote-up-unvoted"></i>';     
                    }


                    $downVoting = $boardSettings[10];

                    if($downVoting == 'Yes'){



                        if(idea_push_check_voter_history($downVotersArray,$currentUser) && $currentUser !== false){
                            $html .= '<i class="ideapush-icon-Down-Vote-Solid vote-down-unvoted"></i>';   

                        } else {
                            $html .= '<i class="ideapush-icon-Down-Vote vote-down-unvoted"></i>';
                        }

                    }  
                
                
                } //end challenge check
                
                
                

            } //end guest voting check

        } //end open check

    

    $html .= '</div>'; //end vote container
    
    return $html;
    
}



function idea_push_update_vote_counter(){
    
    $ideaId = idea_push_sanitization_validation($_POST['ideaId'],'id');
    $status = sanitize_text_field($_POST['status']);
    
    if($ideaId == false){
        wp_die();    
    }
    
    
    
    $boardSettings = idea_push_get_board_settings(idea_push_get_board_id_from_post_id($ideaId));
    
    
    echo idea_push_vote_part($ideaId,$status,$boardSettings);
   
    wp_die();    
}

add_action( 'wp_ajax_update_vote_counter', 'idea_push_update_vote_counter' );
add_action( 'wp_ajax_nopriv_update_vote_counter', 'idea_push_update_vote_counter' );



?>