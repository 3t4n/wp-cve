<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

//define all the settings in the plugin
function wp_google_my_business_auto_publish_settings_init() { 
    
    //start connect section
	register_setting( 'googleConnect', 'wp_google_my_business_auto_publish_settings' );
    
	add_settings_section(
		'wp_google_my_business_auto_publish_connect','', 
		'wp_google_my_business_auto_publish_settings_connect_callback', 
		'googleConnect'
	);

    add_settings_field( 
		'wp_google_my_business_auto_publish_tab_memory','', 
		'wp_google_my_business_auto_publish_tab_memory_render', 
		'googleConnect', 
		'wp_google_my_business_auto_publish_connect' 
	);
    
    add_settings_field( 
		'wp_google_my_business_auto_publish_dismiss_welcome_message','', 
		'wp_google_my_business_auto_publish_dismiss_welcome_message_render', 
		'googleConnect', 
		'wp_google_my_business_auto_publish_connect' 
	);
    
    
    //start account select section
	register_setting( 'googleAccountSelect', 'wp_google_my_business_auto_publish_settings' );
    
	add_settings_section(
		'wp_google_my_business_auto_publish_account_select','', 
		'wp_google_my_business_auto_publish_account_select_callback', 
		'googleAccountSelect'
	);

	add_settings_field( 
		'wp_google_my_business_auto_publish_account_selection','', 
		'wp_google_my_business_auto_publish_account_selection_render', 
		'googleAccountSelect', 
		'wp_google_my_business_auto_publish_account_select' 
	);
    
    
    //start location select section
	register_setting( 'googleLocationSelect', 'wp_google_my_business_auto_publish_settings' );
    
	add_settings_section(
		'wp_google_my_business_auto_publish_location_select','', 
		'wp_google_my_business_auto_publish_location_select_callback', 
		'googleLocationSelect'
	);

	add_settings_field( 
		'wp_google_my_business_auto_publish_location_selection','', 
		'wp_google_my_business_auto_publish_location_selection_render', 
		'googleLocationSelect', 
		'wp_google_my_business_auto_publish_location_select' 
	);
    
    
     //start sharing options section
	register_setting( 'googleBusinessSharingOptionsPage', 'wp_google_my_business_auto_publish_settings' );
    
	add_settings_section(
		'wp_google_my_business_auto_publish_sharing_options','', 
		'wp_google_my_business_auto_publish_sharing_options_callback', 
		'googleBusinessSharingOptionsPage'
	);

	add_settings_field( 
		'wp_google_my_business_auto_publish_default_share_message','', 
		'wp_google_my_business_auto_publish_default_share_message_render', 
		'googleBusinessSharingOptionsPage', 
		'wp_google_my_business_auto_publish_sharing_options' 
	);
    
    add_settings_field( 
		'wp_google_my_business_auto_publish_default_action_type','', 
		'wp_google_my_business_auto_publish_default_action_type_render', 
		'googleBusinessSharingOptionsPage', 
		'wp_google_my_business_auto_publish_sharing_options' 
	);
    
    add_settings_field( 
		'wp_google_my_business_auto_publish_default_locations','', 
		'wp_google_my_business_auto_publish_default_locations_render', 
		'googleBusinessSharingOptionsPage', 
		'wp_google_my_business_auto_publish_sharing_options' 
	);
    
    add_settings_field( 
		'wp_google_my_business_auto_publish_dont_share_categories','', 
		'wp_google_my_business_auto_publish_dont_share_categories_render', 
		'googleBusinessSharingOptionsPage', 
		'wp_google_my_business_auto_publish_sharing_options' 
	);
    
    add_settings_field( 
		'wp_google_my_business_auto_publish_dont_share_types','', 
		'wp_google_my_business_auto_publish_dont_share_types_render', 
		'googleBusinessSharingOptionsPage', 
		'wp_google_my_business_auto_publish_sharing_options' 
	);
    
    
    //start additional options section
	register_setting( 'googleBusinessAdditionalOptionsPage', 'wp_google_my_business_auto_publish_settings' );
    
	add_settings_section(
		'wp_google_my_business_auto_publish_additional_options','', 
		'wp_google_my_business_auto_publish_additional_options_callback', 
		'googleBusinessAdditionalOptionsPage'
	);

	add_settings_field( 
		'wp_google_my_business_auto_publish_hide_posts_column','', 
		'wp_google_my_business_auto_publish_hide_posts_column_render', 
		'googleBusinessAdditionalOptionsPage', 
		'wp_google_my_business_auto_publish_additional_options' 
	);
    
    add_settings_field( 
		'wp_google_my_business_auto_publish_default_share','', 
		'wp_google_my_business_auto_publish_default_share_render', 
		'googleBusinessAdditionalOptionsPage', 
		'wp_google_my_business_auto_publish_additional_options' 
    );

    // add_settings_field( 
	// 	'wp_google_my_business_auto_publish_disable_frontend','', 
	// 	'wp_google_my_business_auto_publish_disable_frontend_render', 
	// 	'googleBusinessAdditionalOptionsPage', 
	// 	'wp_google_my_business_auto_publish_additional_options' 
    // );
    
    //start reviews options section
	register_setting( 'googleBusinessReviews', 'wp_google_my_business_auto_publish_settings' );
    
	add_settings_section(
		'wp_google_my_business_auto_publish_review_options','', 
		'wp_google_my_business_auto_publish_review_options_callback', 
		'googleBusinessReviews'
	);

	add_settings_field( 
		'wp_google_my_business_auto_publish_hide_reviews','', 
		'wp_google_my_business_auto_publish_hide_reviews_render', 
		'googleBusinessReviews', 
		'wp_google_my_business_auto_publish_review_options' 
	);
    
}

/**
* 
*
*
* The following functions output the callback of the sections
*/
function wp_google_my_business_auto_publish_settings_connect_callback(){

    $adminUrl = urlencode (get_admin_url()); 
    
    echo '<tr class="google_business_settings_row" valign="top">
        <td scope="row" colspan="2">';
    
    echo '<a style="margin-top:20px;" href="https://accounts.google.com/o/oauth2/v2/auth?scope=https://www.googleapis.com/auth/plus.business.manage&access_type=offline&include_granted_scopes=true&state='.esc_html($adminUrl).'&redirect_uri=https://northernbeacheswebsites.com.au/redirectgoogle/&response_type=code&client_id=979275334189-mqphf6kpvpji9km7i6pm0sq5ddvfoa60.apps.googleusercontent.com&prompt=consent" id="gmb-authentication" class="button-secondary"><i style="color: #4a8af4;" class="fa fa-google" aria-hidden="true"></i> '.__('Connect with Google My Business', 'wp-google-my-business-auto-publish' ).'</a>';
    
    echo '</td></tr>';
    
}


function wp_google_my_business_auto_publish_account_select_callback(){
    
    if( get_option('wp_google_my_business_auto_publish_auth_settings') ){

        $pluginSettings = get_option('wp_google_my_business_auto_publish_auth_settings');
        $accessToken = $pluginSettings['access_token'];
    
        if(!isset($accessToken)){
        
        ?>
        <tr class="google_business_settings_row" valign="top">
            <td scope="row" colspan="2">
                <div class="inside">
                    
                    
                    <div style="font-weight: 600;" class="notice notice-info inline">
                        <p><?php _e( 'If you just authenticated for the first time you may not see your accounts here, if so please refresh the page and they should appear.', 'wp-google-my-business-auto-publish' ); ?></p>
                    </div>
                </div>
            </td>
        </tr>
        <?php
            
        }
    }
    
}

function wp_google_my_business_auto_publish_location_select_callback(){
    
    if( get_option('wp_google_my_business_auto_publish_settings') ){

        $pluginSettings = get_option('wp_google_my_business_auto_publish_settings');
        $accountName = $pluginSettings['wp_google_my_business_auto_publish_account_selection'];
        
        if(!isset($accountName)){
        
            ?>
            <tr class="google_business_settings_row" valign="top">
                <td scope="row" colspan="2">
                    <div class="inside">
                        
                        
                        <div style="font-weight: 600;" class="notice notice-info inline">
                            <p><?php _e( 'Please make sure you select an account first.', 'wp-google-my-business-auto-publish' ); ?></p>
                        </div>
                    </div>
                </td>
            </tr>
            <?php
            
        }
    }
        
}


function wp_google_my_business_auto_publish_review_options_callback(){

    ?>
    <tr class="google_business_settings_row" valign="top">
        <td style="vertical-align: top;" scope="row">
            <div class="inside shortcode-builder">
            <div style="font-weight: 600;" class="notice notice-info inline">
                    <p><?php  _e('Select the options for the shortcode', 'wp-google_business-auto_publish' ); ?></p>
            </div>    

            <?php

                //types boolean, select, number, checkbox
                function wp_google_my_business_auto_publish_shortcode_builder_select($class, $default, $values){
                    
                    $html = '<select class="'.esc_attr($class).'">';

                        foreach($values as $key => $value){
                            
                            if($key == $default){
                                $selected = 'selected="selected"';       
                            } else {
                                $selected = '';    
                            }
                            $html .= '<option value="'.esc_attr($key).'" '.$selected.'>'.esc_html($value).'</option>';
                        }

                    $html .= '</select>';

                    return $html; 

                }

                function wp_google_my_business_auto_publish_shortcode_builder_number($class,$default,$min,$max){
                    
                    $html = '<input class="'.esc_attr($class).'" type="number" min="'.intval($min).'" max="'.intval($max).'" value="'.esc_attr($default).'">';

                    return $html; 

                }

                function wp_google_my_business_auto_publish_shortcode_builder_checkbox($class,$default){
                    
                    if($default=='checked'){
                        $checked = 'checked';
                    } else {
                        $checked = '';    
                    }

                    $html = '<input class="'.esc_attr($class).'" type="checkbox" '.$checked .'>';
                    return $html; 

                }

                echo '<table>';

                    // 'location' => '', 
                    $options = get_option('wp_google_my_business_auto_publish_settings');
                    $enabledLocations = $options['wp_google_my_business_auto_publish_location_selection'];
                    $locationNames = wp_google_my_business_auto_publish_get_specific_location();

                    // var_dump($locationNames);
                    
                    echo '<tr>';

                        echo '<td class="label">';
                            echo '<label>'.__('Select Location', 'wp-google_business-auto_publish' ).'</label>';
                        echo '</td>';

                        echo '<td class="options">';
                        
                        // var_dump($enabledLocations);

                        if(isset($enabledLocations)){
                            $locationValues = array();
                            $enabledLocationsAsArray = explode(",",$enabledLocations);
                            foreach($enabledLocationsAsArray as $location){
                                
                                $locationValues[$location] = $locationNames[$location];

                            } 

                            echo wp_google_my_business_auto_publish_shortcode_builder_select('location','',$locationValues); 

                        }  
                        
                        echo '</td>';

                    echo '</tr>';

                    

                    
                    

                    // 'type' => 'slider', //also accepts grid 
                    echo '<tr>';

                        echo '<td class="label">';
                            echo '<label>'.__('Type', 'wp-google_business-auto_publish' ).'</label>';
                        echo '</td>';

                        echo '<td class="options">';
                            echo wp_google_my_business_auto_publish_shortcode_builder_select('type','slider',array('slider'=>'Slider','grid'=>'Grid')); 
                        echo '</td>';

                    echo '</tr>';


                    
                    

                    // 'minimum-stars' => 5,
                    echo '<tr>';

                        echo '<td class="label">';
                            echo '<label>'.__('Minimum Stars', 'wp-google_business-auto_publish' ).'</label>';
                        echo '</td>';

                        echo '<td class="options">';
                            echo wp_google_my_business_auto_publish_shortcode_builder_number('minimum-stars','5','1','5');
                        echo '</td>';

                    echo '</tr>';

                    
                    

                    // 'sort-by' => 'date', //also accepts random and stars
                    echo '<tr>';

                        echo '<td class="label">';
                         echo '<label>'.__('Sort By', 'wp-google_business-auto_publish' ).'</label>';
                        echo '</td>';

                        echo '<td class="options">';
                            echo wp_google_my_business_auto_publish_shortcode_builder_select('sort-by','date',array('date'=>'Date','random'=>'Random','stars'=>'Stars')); 
                        echo '</td>';

                    echo '</tr>';

                    
                    

                    // 'sort-order' => 'desc', //also accepts asc
                    echo '<tr>';

                        echo '<td class="label">';
                            echo '<label>'.__('Sort Order', 'wp-google_business-auto_publish' ).'</label>';
                        echo '</td>';

                        echo '<td class="options">';
                            echo wp_google_my_business_auto_publish_shortcode_builder_select('sort-order','desc',array('desc'=>'Descending','asc'=>'Ascending'));
                        echo '</td>';

                    echo '</tr>';


                    
                    

                    // 'review-amount' => 200,
                    echo '<tr>';

                        echo '<td class="label">';
                            echo '<label>'.__('Amount of Reviews', 'wp-google_business-auto_publish' ).'</label>';
                        echo '</td>';

                        echo '<td class="options">';
                            echo wp_google_my_business_auto_publish_shortcode_builder_number('review-amount','200','1','200');
                        echo '</td>';

                    echo '</tr>';

                    
                    

                    // 'slides-page' => 1, 
                    echo '<tr>';

                        echo '<td class="label">';
                            echo '<label>'.__('Visible Slides', 'wp-google_business-auto_publish' ).'</label>';
                        echo '</td>';

                        echo '<td class="options">';
                            echo wp_google_my_business_auto_publish_shortcode_builder_number('slides-page','1','1','12');
                        echo '</td>';

                    echo '</tr>';

                    
                    

                    // 'slides-scroll' => 1, 
                    echo '<tr>';

                        echo '<td class="label">';
                            echo '<label>'.__('Slides to Scroll', 'wp-google_business-auto_publish' ).'</label>';
                        echo '</td>';

                        echo '<td class="options">';
                            echo wp_google_my_business_auto_publish_shortcode_builder_number('slides-scroll','1','1','12');
                        echo '</td>';

                    echo '</tr>';

                    
                    


                    // 'autoplay' => 'false', //also accepts true 
                    echo '<tr>';

                        echo '<td class="label">';
                            echo '<label>'.__('Autoplay', 'wp-google_business-auto_publish' ).'</label>';
                        echo '</td>';

                        echo '<td class="options">';
                            echo wp_google_my_business_auto_publish_shortcode_builder_checkbox('autoplay','');
                        echo '</td>';

                    echo '</tr>';

                    
                    
                    
                    // 'speed' => 5000,
                    echo '<tr>';

                        echo '<td class="label">';
                            echo '<label>'.__('Slide Autoplay Speed (Milliseconds)', 'wp-google_business-auto_publish' ).'</label>';
                        echo '</td>';

                        echo '<td class="options">';
                            echo wp_google_my_business_auto_publish_shortcode_builder_number('speed','5000','1','60000');
                        echo '</td>';

                    echo '</tr>';

                    
                    

                    // 'transition' => 'slide', //also accepts fade
                    echo '<tr>';

                        echo '<td class="label">';
                            echo '<label>'.__('Slide Transition', 'wp-google_business-auto_publish' ).'</label>';
                        echo '</td>';

                        echo '<td class="options">';
                            echo wp_google_my_business_auto_publish_shortcode_builder_select('transition','slide',array('slide'=>'Slide','fade'=>'Fade'));
                        echo '</td>';

                    echo '</tr>';


                    
                    

                    // 'read-more' => 'true', //also accepts false 
                    echo '<tr>';

                        echo '<td class="label">';
                            echo '<label>'.__('Read More', 'wp-google_business-auto_publish' ).'</label>';
                        echo '</td>';

                        echo '<td class="options">';
                            echo wp_google_my_business_auto_publish_shortcode_builder_checkbox('read-more','');
                        echo '</td>';

                    echo '</tr>';

                    
                    

                    // 'show-stars' => 'true', //also accepts false 
                    echo '<tr>';

                        echo '<td class="label">';
                            echo '<label>'.__('Show Stars', 'wp-google_business-auto_publish' ).'</label>';
                        echo '</td>';

                        echo '<td class="options">';
                            echo wp_google_my_business_auto_publish_shortcode_builder_checkbox('show-stars','checked');
                        echo '</td>';

                    echo '</tr>';

                    
                    

                    // 'show-date' => 'true', //also accepts false 
                    echo '<tr>';

                        echo '<td class="label">';
                            echo '<label>'.__('Show Date', 'wp-google_business-auto_publish' ).'</label>';
                        echo '</td>';

                        echo '<td class="options">';
                            echo wp_google_my_business_auto_publish_shortcode_builder_checkbox('show-date','checked');
                        echo '</td>';

                    echo '</tr>';


                    
                    

                    // 'show-quotes' => 'true', //also accepts false 
                    echo '<tr>';

                        echo '<td class="label">';
                            echo '<label>'.__('Show Quote Symbols', 'wp-google_business-auto_publish' ).'</label>';
                        echo '</td>';

                        echo '<td class="options">';
                            echo wp_google_my_business_auto_publish_shortcode_builder_checkbox('show-quotes','checked');
                        echo '</td>';

                    echo '</tr>';

                echo '</table>';
                

            
            ?>
            
                
            
               

            </div>
        </td>

        <td style="vertical-align: top;">

            <div style="font-weight: 600;" class="notice notice-info inline">
                    <p><?php  _e('Put the following shortcode on any post, page or widget!', 'wp-google_business-auto_publish' ); ?></p>
            </div>
            <input id="shortcode-input" class="shortcode-input" type="text" value=""><button style="margin-left: 10px;" type="button" id="copy-shortcode" class="button-secondary"><i class="fa fa-clipboard" aria-hidden="true"></i> Copy Shortcode</button>
            
            <em style="display:block;margin-top:45px;text-align: center;font-size: smaller; opacity: .5;"><?php  _e('Note this preview may not represent the frontend implementation of the shortcode due to different theme/plugin styles that may be present.', 'wp-google_business-auto_publish' ); ?></em>
            <div style="margin-top: 20px;" id="shortcode-preview" data-nonce="<?php echo wp_create_nonce( 'update_shortcode_preview' ); ?>"></div>


        </td>
    </tr>
    <?php
        

    
}




function wp_google_my_business_auto_publish_additional_options_callback(){}
function wp_google_my_business_auto_publish_sharing_options_callback(){}

function wp_google_my_business_auto_publish_tab_memory_render() {    
    wp_google_my_business_auto_publish_settings_code_generator('wp_google_my_business_auto_publish_tab_memory','Tab Memory','Remembers the last settings tab','text','','','','hidden-row');   
}

function wp_google_my_business_auto_publish_dismiss_welcome_message_render() {    
    wp_google_my_business_auto_publish_settings_code_generator('wp_google_my_business_auto_publish_dismiss_welcome_message','Dismiss Welcome Message','','text','','','','hidden-row');   
}



//the following functions output the option html
function wp_google_my_business_auto_publish_account_selection_render() { 
        
    //create an empty array
    $values = array();
    
    $getAccounts = wp_google_my_business_auto_publish_get_accounts();
    
    if($getAccounts !== "ERROR"){
        foreach($getAccounts as $account){
            $values[$account['name']] = $account['accountName'];
        }        
        
    }

    //$values = array('Business 1' => 'Business 1','Business 2' => 'Business 2','Business 3' => 'Business 3');
    wp_google_my_business_auto_publish_settings_code_generator('wp_google_my_business_auto_publish_account_selection','Select Account','','select','',$values,'','');  
    
}



//the following functions output the option html
function wp_google_my_business_auto_publish_location_selection_render() { 

    
    $getLocations = wp_google_my_business_auto_publish_get_locations();
    // $getLocationImages = wp_google_my_business_auto_publish_get_location_images();
    
    
    if( get_option('wp_google_my_business_auto_publish_settings') ){
        //get existing settings
        $pluginSettings = get_option('wp_google_my_business_auto_publish_settings');
        $existingSetting = $pluginSettings['wp_google_my_business_auto_publish_location_selection'];
        $existing_account = $pluginSettings['wp_google_my_business_auto_publish_account_selection'];

        

        if(isset($existingSetting)){
            $settingToArray = explode(",",$existingSetting);     
        } else {
            $settingToArray = array();    
        }
        
        if($getLocations !== "ERROR"){
            
            echo '<tr class="google_business_settings_row" valign="top"><td scope="row" colspan="2"><div class="inside">';
            
            echo '<h3 style="margin-bottom: 20px;">'.__('Select the locations you want to use with the plugin', 'wp-google_business-auto_publish' ).'</h3>';
            
            echo '<ul class="google-locations">';
            
            foreach($getLocations as $location){
                
                //lets check if the api is enabled
                // if(isset($location['locationState']['isLocalPostApiDisabled']) && $location['locationState']['isLocalPostApiDisabled'] == true){
                //     //do nothing
                // } else {

                    //we need to add the account to this as well

                    $locationId = $existing_account.'/'.$location['name']; 
                    $locationName = $location['title'];
                    
                    // var_dump($locationId);

                    //check if list item is in setting
                    if(in_array($locationId, $settingToArray)){
                        $listClass = 'selected';
                        $iconClass = 'fa-check-circle-o';
                    } else {
                        $listClass = ''; 
                        $iconClass = 'fa-times-circle-o';
                    }


                    // if( array_key_exists('storefrontAddress',$location) ){
                    //     $locationAddressLines = $location['storefrontAddress']['addressLines'];
                    
                    //     $locationStreet = '';
                    //     foreach($locationAddressLines as $addressLine){
                    //         $locationStreet .= $addressLine.', ';    
                    //     }
                            
                    //     $locationAddress = $locationStreet.$location['storefrontAddress']['locality'].', '.$location['storefrontAddress']['administrativeArea'].', '.$location['storefrontAddress']['postalCode'].', '.$location['storefrontAddress']['regionCode'];
                    // } else {
                    //     $locationAddress = '';
                    // }


                    if( array_key_exists('profile',$location) ){
                        $description = $location['profile']['description'];
                    } else {
                        $description = '';
                    }
    
                    
                    echo '<li class="location-list-item '.esc_attr($listClass).'" data="'.esc_attr($locationId).'">';
                    
                        //image 
                        //only do image if image exists

                        // if(is_array($getLocationImages)){
                        //     if(array_key_exists($locationId,$getLocationImages)){
                        //         $locationImage = $getLocationImages[$locationId];
                        //         $html .= '<img src="'.$locationImage.'" class="location-image" height="42" width="42">';
                        //     }
                        // }

                        //location information
                        echo '<div class="location-information">';
                        
                            //name
                            echo '<span class="location-name">'.esc_html($locationName).'</span>';

                            //address
                            // $html .= '<span class="location-address">'.$locationAddress.'</span>';

                            //description
                            echo '<span class="location-address">'.esc_html($description).'</span>';

                            echo '</div>';
                    
                        //render appropriate icon
                        echo '<i class="location-selected-icon fa '.esc_attr($iconClass).'" aria-hidden="true"></i>';
                        
                    
                        echo '</li>';
                // }
                
            }  
            
            echo '</ul>';
            echo '</div></td></tr>';
                        
        }    

    }
    
    wp_google_my_business_auto_publish_settings_code_generator('wp_google_my_business_auto_publish_location_selection','Select Location(s)','','text','','','','hidden-row');  
    
}





//the following functions output the option html
function wp_google_my_business_auto_publish_hide_reviews_render() { 

    
    $options = get_option('wp_google_my_business_auto_publish_settings');
    
    $enabledLocations = $options['wp_google_my_business_auto_publish_location_selection'];

    $existingSetting = $options['wp_google_my_business_auto_publish_hide_reviews'];

    if(isset($existingSetting)){
        $settingToArray = explode(",",$existingSetting);     
    } else {
        $settingToArray = array();    
    }

    $enabledLocationsAsArray = explode(",",$enabledLocations);          

    $locationData = wp_google_my_business_auto_publish_get_specific_location();

    $ratingTranslated = array('FIVE'=>5,'FOUR'=>4,'THREE'=>3,'TWO'=>2,'ONE'=>1);
    
    //start output
    echo '<tr class="google_business_settings_row" valign="top"><td scope="row" colspan="2"><div class="inside">';
    

    echo '<div style="font-weight: 600;" class="notice notice-info inline">
                    <p>'.__('Select the reviews you want to manually exclude from the display', 'wp-google_business-auto_publish' ).'</p>
            </div>';

    foreach($enabledLocationsAsArray as $location){
        echo '<h3 style="margin-top:30px;">'.esc_html($locationData[$location]).'</h3>';

        $reviews = wp_google_my_business_auto_publish_get_reviews($location);

        if($reviews !== 'ERROR' && !empty($reviews)){
            //start printing review rows
            
            echo '<ul class="google-reviews">';

            foreach($reviews as $review){
                //establish the right class
                if(in_array($review['name'], $settingToArray)){
                    $listClass = '';
                    $iconClass = 'fa-eye-slash';
                } else {
                    $listClass = 'selected'; 
                    $iconClass = 'fa-eye';
                }

                echo '<li class="review-list-item '.esc_attr($listClass).'" data="'.esc_attr($review['name']).'">';

                    echo '<div class="review-information">';
                
                    //name
                    echo '<span class="review-reviewer">'.esc_html($review['reviewer']['displayName']).'</span>';

                    //date
                    $niceDate = strtotime($review['createTime']);
                    $niceDate = date(get_option('date_format'),$niceDate);
                    echo '<span class="review-date">'.esc_html($niceDate).'</span>';

                    //stars

                    $amountOfStars = $ratingTranslated[$review['starRating']];
                    $stars = '';

                    for ($i = 0 ; $i < $amountOfStars; $i++){ 
                        $stars .= '<i class="fa fa-star" aria-hidden="true"></i>'; 
                    }

                    echo '<span class="review-rating">'.$stars.'</span>'; //does not need to be escaped because static html

                    //comment
                    if( array_key_exists('comment', $review) ){
                        echo '<span class="review-comment">'.esc_html($review['comment']).'</span>';
                    }
                    
            
                    echo '</div>';
            
                    //render appropriate icon
                    echo '<i class="review-selected-icon fa '.esc_attr($iconClass).'" aria-hidden="true"></i>';


                    echo '</li>';

            }

            echo '</ul>';     

        }    

    }    
                        
    
    echo '</div></td></tr>';

    wp_google_my_business_auto_publish_settings_code_generator('wp_google_my_business_auto_publish_hide_reviews','Hide Reviews','','text','','','','hidden-row');  
    
}




//the following functions output the option html
function wp_google_my_business_auto_publish_hide_posts_column_render() { 
    
    wp_google_my_business_auto_publish_settings_code_generator('wp_google_my_business_auto_publish_hide_posts_column','Hide Posts Column','On your <a href="'. wp_google_my_business_auto_publish_posts_page_url().'">posts</a> page by default there\'s a handy new column which shows which posts have been shared and which ones haven\'t. You can use this setting to hide this column.','checkbox','','','','');  
    
}

//the following functions output the option html
function wp_google_my_business_auto_publish_default_share_render() { 
    
    wp_google_my_business_auto_publish_settings_code_generator('wp_google_my_business_auto_publish_default_share','By default don\'t share posts on my Google My Business Page','','checkbox','','','','');  
    
}

// //the following functions output the option html
// function wp_google_my_business_auto_publish_disable_frontend_render() { 
//     wp_google_my_business_auto_publish_settings_code_generator('wp_google_my_business_auto_publish_disable_frontend','Disable frontend styles and scripts','This is used for the reviews part of the plugin.','checkbox','','','','');  
// }

//the following functions output the option html
function wp_google_my_business_auto_publish_default_share_message_render() { 

    $options = get_option( 'wp_google_my_business_auto_publish_settings' );
	?>
    <tr valign="top">
        <td scope="row">
            <label for="wp_google_my_business_auto_publish_default_share_message"><?php _e('Default Share Message', 'wp-google-my-business-auto-publish' ); ?></label>
            </br>
            <a style="margin-top: 5px;" value="[POST_TITLE]" class="button-secondary google_business_append_buttons">[POST_TITLE]</a>
<!--            <a style="margin-top: 5px;" value="[POST_LINK]" class="button-secondary google_business_append_buttons">[POST_LINK]</a>-->
            <a style="margin-top: 5px;" value="[POST_EXCERPT]" class="button-secondary google_business_append_buttons">[POST_EXCERPT]</a>
            <a style="margin-top: 5px;" value="[POST_CONTENT]" class="button-secondary google_business_append_buttons">[POST_CONTENT]</a>
            <a style="margin-top: 5px;" value="[POST_AUTHOR]" class="button-secondary google_business_append_buttons">[POST_AUTHOR]</a>
            <a style="margin-top: 5px;" value="[WEBSITE_TITLE]" class="button-secondary google_business_append_buttons">[WEBSITE_TITLE]</a>
        </td>
        <td>   
            <textarea cols="46" rows="14" name="wp_google_my_business_auto_publish_settings[wp_google_my_business_auto_publish_default_share_message]" id="wp_google_my_business_auto_publish_default_share_message"><?php if(isset($options['wp_google_my_business_auto_publish_default_share_message'])) { echo esc_attr($options['wp_google_my_business_auto_publish_default_share_message']); } else {echo 'New Post: [POST_TITLE] - [POST_CONTENT]';} ?></textarea>
        </td>
    </tr>
	<?php
    
}


//the following functions output the option html
function wp_google_my_business_auto_publish_default_action_type_render() { 
    
    $values = array('LEARN_MORE' => 'Learn More','BOOK' => 'Book','ORDER' => 'Order','SHOP' => 'Shop','SIGN_UP' => 'Sign Up');
    wp_google_my_business_auto_publish_settings_code_generator('wp_google_my_business_auto_publish_default_action_type','Default Action Type','Each post on Google My Business has a call to action button, set the default action here.','select','',$values,'','');  
 
    
}


//the following functions output the option html
function wp_google_my_business_auto_publish_default_locations_render() { 
 $options = get_option( 'wp_google_my_business_auto_publish_settings' );
	?>
    <tr valign="top">
        <td scope="row">
            <label for="wp_google_my_business_auto_publish_default_locations"><?php _e('The default locations you want to share with', 'wp-google-my-business-auto-publish' ); ?></label>
        </td>
        <td>   
            <?php
                                        
            echo '<ul id="default-locations-list">';                                             
            
                //select items
                if(isset($options['wp_google_my_business_auto_publish_default_locations'])){
                    $selectedItems = explode(",",$options['wp_google_my_business_auto_publish_default_locations']);    
                } else {
                    $selectedItems = array();    
                }
                
                echo wp_google_my_business_auto_publish_render_location_list_items($selectedItems);        
    
            echo '</ul>';                                              
            ?>
            
            
            

            <input style="display:none;" name="wp_google_my_business_auto_publish_settings[wp_google_my_business_auto_publish_default_locations]" id="wp_google_my_business_auto_publish_default_locations" type="text" value="<?php if(isset($options['wp_google_my_business_auto_publish_default_locations'])) { echo esc_attr($options['wp_google_my_business_auto_publish_default_locations']); } ?>" class="regular-text" />    
            
        </td>
    </tr>
	<?php   
    
}






//the following functions output the option html
function wp_google_my_business_auto_publish_dont_share_categories_render() { 
 $options = get_option( 'wp_google_my_business_auto_publish_settings' );
	?>
    <tr valign="top">
        <td scope="row">
            <label for="wp_google_my_business_auto_publish_dont_share_categories"><?php _e('Don\'t Share Select Post Categories to my Google Business page', 'wp-google-my-business-auto-publish' ); ?></label>
        </td>
        <td>   
            <?php
                                            
            $categories = get_categories( array(
            'hide_empty'   => 0,
            ));

            echo '<ul id="category-listing">';                                                
            foreach ($categories as $category) {
                    echo '<li><input class="dont-share-checkbox" type="checkbox" id="'.esc_attr($category->name).'">' . esc_attr($category->name). '</li>';
            }
            echo '</ul>';                                              
            ?>

            <input style="display:none;" name="wp_google_my_business_auto_publish_settings[wp_google_my_business_auto_publish_dont_share_categories]" id="wp_google_my_business_auto_publish_dont_share_categories" type="text" value="<?php if(isset($options['wp_google_my_business_auto_publish_dont_share_categories'])) { echo esc_attr($options['wp_google_my_business_auto_publish_dont_share_categories']); } ?>" class="regular-text" />    
            
        </td>
    </tr>
	<?php   
    
}

//the following functions output the option html
function wp_google_my_business_auto_publish_dont_share_types_render() { 
    $options = get_option( 'wp_google_my_business_auto_publish_settings' );
	?>
    <tr valign="top">
        <td scope="row">
            <label for="wp_google_my_business_auto_publish_dont_share_types"><?php _e('Share the following: Posts, Pages and Custom Post Types', 'wp-google-my-business-auto-publish' ); ?></label>
        </td>
        <td>   
            <?php
            
            $args = array(
            //    'public'   => true,
               '_builtin' => false
            );

            $output = 'names'; // 'names' or 'objects' (default: 'names')
            $operator = 'and'; // 'and' or 'or' (default: 'and')

            $post_types = get_post_types( $args, $output, $operator );
    
            echo '<ul id="category-listing">';
            echo '<li><input class="post-type-checkbox" type="checkbox" id="Post">Post</li>';
            echo '<li><input class="post-type-checkbox" type="checkbox" id="Page">Page</li>'; 
            foreach ($post_types as $item) {
                $item = ucwords($item);
                echo '<li><input class="post-type-checkbox" type="checkbox" id="'.esc_attr($item).'">' . esc_attr($item). '</li>';    
            }
            echo '</ul>';                                              
            ?>

            <input style="display:none;" name="wp_google_my_business_auto_publish_settings[wp_google_my_business_auto_publish_dont_share_types]" id="wp_google_my_business_auto_publish_dont_share_types" type="text" value="<?php if(isset($options['wp_google_my_business_auto_publish_dont_share_types'])) { echo esc_attr($options['wp_google_my_business_auto_publish_dont_share_types']); } else {echo ",Post";} ?>" class="regular-text" />    
            
        </td>
    </tr>
	<?php
    
}




//function to generate settings rows
function wp_google_my_business_auto_publish_settings_code_generator($id,$label,$description,$type,$default,$parameter,$importantNote,$rowClass) {
    
    //get options
    $options = get_option('wp_google_my_business_auto_publish_settings');
    
    //value
    if(isset($options[$id])){  
        $value = $options[$id];    
    } elseif(strlen($default)>0) {
        $value = $default;   
    } else {
        $value = '';
    }
    
    
    //the label
    echo '<tr class="google_business_settings_row '.esc_attr($rowClass).'" valign="top">';
    echo '<td scope="row">';
    echo '<label for="'.esc_attr($id).'">'.__($label, 'wp-google_business-auto_publish' );
    if(strlen($description)>0){
        echo ' <i class="fa fa-info-circle" aria-hidden="true"></i>';
        echo '<p class="hidden"><em>'.esc_html($description).'</em></p>';
    }
    if(strlen($importantNote)>0){
        echo '</br><span style="color: #CC0000;">';
        echo wp_kses($importantNote);
        echo '</span>';
    } 
    echo '</label>';
    
    
    
    if($type == 'shortcode') {
        echo '</br>';
        
        foreach($parameter as $shortcodevalue){
            echo '<a value="['.esc_html($shortcodevalue).']" class="google_business_append_buttons">['.esc_html($shortcodevalue).']</a>';
        }       
    }
    
    if($type == 'textarea-advanced') {
        echo '</br>';
        
        foreach($parameter as $shortcodevalue){
            echo '<a value="['.esc_html($shortcodevalue).']" data="'.esc_attr($id).'" class="google_business_append_buttons_advanced">['.esc_html($shortcodevalue).']</a>';
        }       
    }
    
    
    if($type == 'shortcode-advanced') {
        echo '</br>';
        
        foreach($parameter as $shortcodevalue){
            echo '<a value="'.esc_html($shortcodevalue[1]).'" class="google_business_append_buttons">'.esc_html($shortcodevalue[0]).'</a>';
        }       
    }
    
    

    //the setting    
    echo '</td><td>';
    
    //text
    if($type == "text"){
        echo '<input type="text" class="regular-text" name="wp_google_my_business_auto_publish_settings['.esc_attr($id).']" id="'.esc_attr($id).'" value="'.esc_attr($value).'">';     
    }
    
    //select
    if($type == "select"){
        echo '<select name="wp_google_my_business_auto_publish_settings['.esc_attr($id).']" id="'.esc_attr($id).'">';
        
        foreach($parameter as $x => $xvalue){
            echo '<option value="'.esc_attr($x).'" ';
            if($x == $value) {
                echo 'selected="selected"';    
            }
            echo '>'.esc_html($xvalue).'</option>';
        }
        echo '</select>';
    }
    
    
    //checkbox
    if($type == "checkbox"){
        echo '<label class="switch">';
        echo '<input type="checkbox" id="'.esc_attr($id).'" name="wp_google_my_business_auto_publish_settings['.esc_attr($id).']" ';
        echo checked($value,1,false);
        echo 'value="1">';
        echo '<span class="slider round"></span></label>';
    }
        
    //color
    if($type == "color"){ 
        echo '<input name="wp_google_my_business_auto_publish_settings['.esc_attr($id).']" id="'.esc_attr($id).'" type="text" value="'.esc_attr($value).'" class="my-color-field" data-default-color="'.esc_attr($default).'"/>';    
    }
    
    //page
    if($type == "page"){
        $args = array(
            'echo' => 0,
            'selected' => $value,
            'name' => 'wp_google_my_business_auto_publish_settings['.$id.']',
            'id' => $id,
            'show_option_none' => $default,
            'option_none_value' => "default",
            'sort_column'  => 'post_title',
            );
        
            echo wp_dropdown_pages($args);     
    }
    
    //textarea
    if($type == "textarea" || $type == "shortcode" || $type == "shortcode-advanced"){
        echo '<textarea cols="46" rows="3" name="wp_google_my_business_auto_publish_settings['.esc_attr($id).']" id="'.esc_attr($id).'">'.esc_textarea($value).'</textarea>';
    }
    
    
    //textarea-advanced
//    if($type == "textarea-advanced"){
//        wp_editor(html_entity_decode(stripslashes($value)), $id, $settings = array(
//            'textarea_name' => 'idea_push_settings['.$id.']',
//            'drag_drop_upload' => true,
//            'textarea_rows' => 7,  
//            )
//        );
//    }  
    
    
    if($type == "textarea-advanced"){
        if(isset($value)){    
            wp_editor(html_entity_decode(stripslashes($value)), $id, $settings = array(
            'wpautop' => false,    
            'textarea_name' => 'wp_google_my_business_auto_publish_settings['.esc_attr($id).']',
            'drag_drop_upload' => true,
            'textarea_rows' => 7, 
            ));    
        } else {
            wp_editor("", $id, $settings = array(
            'wpautop' => false,    
            'textarea_name' => 'wp_google_my_business_auto_publish_settings['.esc_attr($id).']',
            'drag_drop_upload' => true,
            'textarea_rows' => 7,
            ));         
        }
    }
    
    //number
    if($type == "number"){
        echo '<input type="number" class="regular-text" name="wp_google_my_business_auto_publish_settings['.esc_attr($id).']" id="'.esc_attr($id).'" value="'.esc_attr($value).'">';     
    }

    echo '</td></tr>';

}









?>