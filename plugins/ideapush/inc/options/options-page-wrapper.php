<?php 
if ( ! defined( 'ABSPATH' ) ) exit;


$options = get_option('idea_push_settings');

?>

    <!-- start wrap -->
    <div class="wrap">
    <div id="poststuff">
        
    <!-- heading -->
    <img style="width:200px;" class="style-svg" alt="IdeaPush Logo" src="<?php echo plugins_url( '../images/IdeaPush-Logo.svg', __FILE__ ); ?>"><h1><?php _e('| Settings', 'ideapush' ); ?></h1>
        
    
        
        
        
    <!-- welcome and pro note -->         
    <?php    
       
    
        
    if(!isset($options['idea_push_hide_admin_notice'])){
        echo '<div style="margin-top:20px;" class="notice notice-warning is-dismissible ideapush-welcome inline">
            <h3>'.__( 'Thanks for trying out IdeaPush!', 'ideapush' ) .'</h3>
            '.__( '<p>To get started simply go to the <a class="open-tab" href="#boards">Boards</a> tab and create your first board and then using the "Copy Shortcode" button, copy your shortcode and place it on any page. It is recommended to put this on a page which has a full width design (i.e. no sidebar).</p>
            
            <p>The plugin is brand spanking new so please be a little patient with things. I am committed to improving the plugin and taking on board ideas - no pun intended. If you do experience an issue please check out the <a class="open-tab" href="#ideapush-support">Support</a> tab and we\'ll try and resolve things. I would also be grateful if you could rate the plugin 5 stars, it\'s a nice way to say thank you! <a target="_blank" href="https://wordpress.org/support/plugin/ideapush/reviews/?rate=5#new-post"><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i></a> Please don\'t give us a negative review unless you have created a support topic first at least that way we have the opportunity to fix your issue and improve the plugin!</p> 
            ', 'ideapush' ) .' 
        </div>';      
    }    
        
     
        
    global $ideapush_is_pro;
        
    if($ideapush_is_pro == 'NO'){    
    echo '<div style="margin-top:20px; padding-bottom: 15px;" class="notice notice-success is-dismissible ideapush-welcome inline">
            <h3>'.__( 'Upgrade to pro to get awesome features and unlimited boards', 'ideapush' ) .'</h3>
            '.__( '<p>Upgrade to pro today for a special of just $50! With the pro version of the plugin you can make as many boards as your heart desires. With IdeaPush Pro when users type in a new idea they will get live suggestions of ideas already submitted that relate to their new idea to minimise duplication - the same goes for tags. On the single idea page it also shows related ideas.</p><p>Easily see all the history of an idea as well as add internal notes and send emails to voters and the idea author!</p> 

            <p>We have also just launched custom fields with multipe form settings so you can create multiple form settings and assign them to each board. Each form setting has the ability create custom fields including: text, text area, select, radio and checkbox fields.</p> 
            
            <a target="_blank" href="https://northernbeacheswebsites.com.au/ideapush-pro/" class="button button-primary pro-button">GO PRO NOW</a>
            
            ', 'ideapush' ) .' 
        </div>';     
    }
    
    ?>    
        
                
        

    <?php
        
        //function to transform titles
        
        function ideapush_change_title($name){
            
            $nameToLowerCase = strtolower($name);
            $replaceSpaces = str_replace(' ', '_', $nameToLowerCase);    
            
            return $replaceSpaces;
            
        }
        
        
        //function to output tab titles
        function ideapush_output_tab_titles($name,$proFeature) {
            
            global $ideapush_is_pro;
            
            if ($ideapush_is_pro == "YES" && $proFeature == "YES"){ 
                $iconOutput = '<i id="is-pro-check" class="fa fa-unlock" aria-hidden="true"></i>';    
            } elseif ($proFeature == "YES") {
                $iconOutput = '<i id="is-pro-check" class="fa fa-lock" aria-hidden="true"></i>'; 
            } else {
                $iconOutput = '';   
            }
         
            
            echo '<li><a class="nav-tab" href="#'.ideapush_change_title($name).'">'.$name.' '.$iconOutput.'</a></li>'; 
        }
        
        
        
        
        //function to output tab content
        function ideapush_tab_content($tabName) {
            
            $transformedTitle = ideapush_change_title($tabName);
            
            ?>
            <div class="tab-content" id="<?php echo $transformedTitle; ?>">
                <div class="meta-box-sortables ui-sortable">
                    <div class="postbox">
                        <div class="inside">
                                <table class="form-table">
                                    <?php
                                    global $ideapush_is_pro;
                                    global $ideapush_pro_features;
            
                                    if($ideapush_is_pro != "YES" && $ideapush_pro_features[$tabName][0] == "YES") {
                                        
                                        settings_fields('ip_licence');
                                        do_settings_sections('ip_licence');     
                                        
                                    } else {
                                        
                                        settings_fields('ip_'.$transformedTitle);
                                        do_settings_sections('ip_'.$transformedTitle);  
                                        
                                            
                                        if($ideapush_pro_features[$tabName][1] == "YES"){
                                        ?>
                                        
                                        <table>
                                            <tr class="ideapush_settings_row">
                                                <td>
                                                    <button type="submit" name="submit" id="submit" class="button button-primary ideapush-save-all-settings-button"><?php _e('Save All Settings', 'ideapush' ); ?></button>
                                                </td>
                                            </tr>    
                                        </table>    
                                        <?php    
                                        }
      
                                    }
                                    ?>
                                </table>
                             </div> <!-- .inside -->
                    </div> <!-- .postbox -->                      
                </div> <!-- .meta-box-sortables --> 
            </div> <!-- .tab-content -->  
            <?php
            
            
        }
    ?>    
    
 
        
        
        

    <!--start form-->   
    <?php
        //set tab memory if exists
        //get transient
        if(!get_transient('ideapush-tab-memory')){
            $tabMemory = '#boards';   
        } else {
            $tabMemory = get_transient('ideapush-tab-memory');
        }


    ?>

    <form id="ideapush_settings_form" data-tab-memory="<?php echo $tabMemory; ?>" action="options.php" method="post" novalidate>
       
        <div id="tabs" class="nav-tab-wrapper"> 
            <ul class="tab-titles">
                <?php 

                //declare pro and non pro options into an associative array
                global $ideapush_pro_features;

                foreach($ideapush_pro_features as $item => $value){

                    ideapush_output_tab_titles($item,$value[0]);
                }

                ?>

            </ul>

            <!--add tab content pages-->
            <?php

            global $ideapush_pro_features;

            foreach($ideapush_pro_features as $item => $value){
                ideapush_tab_content($item);     
            }
            ?>

        </div> <!--end tabs div-->         
    </form>
        
        
        
    </div> <!--end post stuff-->    
    
    <div style="display: none;" id="dialog-delete-board-confirmation" data="<?php _e( 'Are you sure you want to delete this board?', 'ideapush' ); ?>"></div>    

    <div style="display: none;" id="dialog-delete-form-setting-confirmation" data="<?php _e( 'Are you sure you want to delete this form setting?', 'ideapush' ); ?>"></div>  
    
    <div style="display: none;" id="dialog-duplicate-form-setting-found" data="<?php _e( 'There are form settings with the same name. Please change the form settings names so they are all unique.', 'ideapush' ); ?>"></div> 


    <?php

    if ( ! function_exists( 'northernbeacheswebsites_information' ) ) {
        require('nbw.php');  
    }

    echo northernbeacheswebsites_information();

    ?>

        
        
    </div> <!-- .wrap -->