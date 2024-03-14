<?php

add_action('cg_check_if_new_registry_logic_explanation_note_required','cg_check_if_new_registry_logic_explanation_note_required');
if(!function_exists('cg_check_if_new_registry_logic_explanation_note_required')){
    function cg_check_if_new_registry_logic_explanation_note_required($galleryDbVersion){

        global $wpdb;

        $tablename_create_user_form = $wpdb->prefix . "contest_gal1ery_create_user_form";

        $earlierRegistryFormField = $wpdb->get_var( "SELECT id FROM $tablename_create_user_form WHERE GalleryID >= 1 LIMIT 1");

        if(!empty($earlierRegistryFormField) && intval($galleryDbVersion)>=14){
            return true;// then explanation note for user required
        }else{
            return false;
        }

    }
}

add_filter( 'logout_url', 'cg_modified_logout_url' );
if(!function_exists('cg_modified_logout_url')){
    function cg_modified_logout_url( $default )
    {
        $user = wp_get_current_user();

        if(in_array('contest_gallery_user_since_v14',(array)$user->roles) OR in_array('contest_gallery_user',(array)$user->roles)){
            $default .= '&cgLogoutLink=true';// modifies url to check if cg logout has to be done in cg_redirect_when_logout function
        }

        return $default;

    }
}

add_action('wp_logout','cg_redirect_when_logout');
if(!function_exists('cg_redirect_when_logout')){
    function cg_redirect_when_logout(){
        if(!empty($_GET['cgLogoutLink'])){
            global $wpdb;
            $tablename_registry_and_login_options = $wpdb->prefix . "contest_gal1ery_registry_and_login_options";
            $LogoutLink = contest_gal1ery_convert_for_html_output_without_nl2br($wpdb->get_var( "SELECT LogoutLink FROM $tablename_registry_and_login_options WHERE GeneralID = '1'" ));
            if(!empty($LogoutLink)){// do this check for sure
                wp_safe_redirect( $LogoutLink);
                exit;
            }
        }
    }
}

add_action( 'wp_before_admin_bar_render', 'cg_remove_admin_bar_links' );
if(!function_exists('cg_remove_admin_bar_links')){
    function cg_remove_admin_bar_links() {
        global $wp_admin_bar, $current_user;

        if(in_array("contest_gallery_user",$current_user->roles)==true){

            $wp_admin_bar->remove_menu('wp-logo');          // Remove the WordPress logo
            $wp_admin_bar->remove_menu('about');            // Remove the about WordPress link
            $wp_admin_bar->remove_menu('wporg');            // Remove the WordPress.org link
            $wp_admin_bar->remove_menu('documentation');    // Remove the WordPress documentation link
            $wp_admin_bar->remove_menu('support-forums');   // Remove the support forums link
            $wp_admin_bar->remove_menu('feedback');         // Remove the feedback link
            $wp_admin_bar->remove_menu('site-name');        // Remove the site name menu
            $wp_admin_bar->remove_menu('view-site');        // Remove the view site link
            $wp_admin_bar->remove_menu('updates');          // Remove the updates link
            $wp_admin_bar->remove_menu('comments');         // Remove the comments link
            $wp_admin_bar->remove_menu('new-content');      // Remove the content link
            $wp_admin_bar->remove_menu('w3tc');             // If you use w3 total cache remove the performance link
            $wp_admin_bar->remove_menu('my-account');       // Remove the user details tab
            $wp_admin_bar->remove_menu('search');       // Remove the user details tab

            $AccountTitle = __("Account","contest-gallery");
            $LogoutTitle = __("Logout?","contest-gallery");

            $args = array(
                'id'    => 'contest_gallery_user_bar',
                'title' => "$AccountTitle: $current_user->user_login",
            );
            $wp_admin_bar->add_node($args);

            $args = array(
                'id'    => 'contest_gallery_user_bar_logout',
                'parent'    => 'contest_gallery_user_bar',
                'title' => $LogoutTitle,
                'href' => wp_logout_url(get_permalink())
            );
            $wp_admin_bar->add_node($args);       // Remove the user details tab
        }
    }
}

add_action( 'wp_before_admin_bar_render', 'cg_modified_admin_bar_for_contest_gallery_user_v14' );
if(!function_exists('cg_modified_admin_bar_for_contest_gallery_user_v14')){
    function cg_modified_admin_bar_for_contest_gallery_user_v14() {

        global $pagenow;
        global $wpdb;
        global $wp_admin_bar, $current_user;

        $user = $current_user;

        if(in_array('contest_gallery_user_since_v14',(array)$user->roles)){

            $tablename_registry_and_login_options = $wpdb->prefix . "contest_gal1ery_registry_and_login_options";

            $is_frontend = true;
            include(__DIR__ . "/../../../check-language-general.php");
            wp_update_user( array( 'ID' => $user->ID, 'role' => 'contest_gallery_user_since_v14' ) );// so always current capabilities will be used!

            $isHideGeneralAdminMenuPoints = true;

            if (
                is_super_admin($user->ID) ||
                in_array( 'administrator', (array) $user->roles ) ||
                in_array( 'editor', (array) $user->roles ) ||
                in_array( 'author', (array) $user->roles )
            ) {
                $isHideGeneralAdminMenuPoints = false;
            }

            if($isHideGeneralAdminMenuPoints){// then general admin menu points will be hidden through css

                $wp_admin_bar->remove_menu('wp-logo');          // Remove the WordPress logo
                $wp_admin_bar->remove_menu('about');            // Remove the about WordPress link
                $wp_admin_bar->remove_menu('wporg');            // Remove the WordPress.org link
                $wp_admin_bar->remove_menu('documentation');    // Remove the WordPress documentation link
                $wp_admin_bar->remove_menu('support-forums');   // Remove the support forums link
                $wp_admin_bar->remove_menu('feedback');         // Remove the feedback link
                $wp_admin_bar->remove_menu('site-name');        // Remove the site name menu
                $wp_admin_bar->remove_menu('view-site');        // Remove the view site link
                $wp_admin_bar->remove_menu('updates');          // Remove the updates link
                $wp_admin_bar->remove_menu('comments');         // Remove the comments link
                $wp_admin_bar->remove_menu('new-content');      // Remove the content link
                $wp_admin_bar->remove_menu('w3tc');             // If you use w3 total cache remove the performance link
                //$wp_admin_bar->remove_menu('my-account');       // Remove the user details tab
                $wp_admin_bar->remove_menu('search');       // Remove the user details tab
                $wp_admin_bar->remove_menu('user-info');       // Remove the user details tab


                // Modify WordPress nodes here
                $args = array(
                    'id'    => 'logout',//wp-admin-bar-logout << full id name in frontend
                    'parent'    => 'user-actions',//wp-admin-bar-user-actions <<< full id name in frontend
                    //'href' => wp_logout_url(get_permalink()) // EXAMPLE!!! only example how to modify node!!!! Logout url is already modified by recognizer in sthis state in cg_modified_logout_url
                    'title' => $language_LogOut
                );
                // Logout url is already modified by recognizer in sthis state in cg_modified_logout_url
                $wp_admin_bar->add_node($args);

                $args = array(
                    'id'    => 'my-account',//wp-admin-my-account << full id name in frontend
                    'parent'    => 'top-secondary',//wp-admin-bar-top-secondary <<< full id name in frontend
                    'title' => $language_Account.": ".$current_user->user_login
                );
                $wp_admin_bar->add_node($args);

                $args = array(
                    'id'    => 'edit-profile',//wp-admin-edit-profile << full id name in frontend
                    'parent'    => 'user-actions',//wp-admin-bar-user-actions <<< full id name in frontend
                    'title' => $language_EditProfile
                );
                $wp_admin_bar->add_node($args);

                if($pagenow=='profile.php'){
                    // Add own nodes
                    $BackToGalleryLink = contest_gal1ery_convert_for_html_output_without_nl2br($wpdb->get_var( "SELECT BackToGalleryLink FROM $tablename_registry_and_login_options WHERE GeneralID = '1'" ));
                    if(!empty($BackToGalleryLink)){
                        $args = array(
                            'id'    => 'contest_gallery_user_bar',
                            'title' => $language_BackToGallery,
                            'href' => $BackToGalleryLink
                        );
                    }
                    $wp_admin_bar->add_node($args);
                    wp_enqueue_script( 'cg_contest_gallery_edit_profile_js', plugins_url('/../../../v10/v10-js/admin/profile/edit-profile.js', __FILE__), array('jquery'), cg_get_version_for_scripts());
                }

            }

            /*            $wp_admin_bar->add_menu( array(
                            'id' => 'cgModifiedAdminBarForContestGalleryUserV14',
                            'parent' => 'top-secondary',
                            'title' => '<a href="'.get_edit_profile_url().'" class="ab-item">Profile</a>'
                        ));*/
        }else{
            if(is_admin()){
                if($pagenow=='profile.php'){
                    $user = wp_get_current_user();

                    $hasUserGroupAllowedToEdit = cgHasUserGroupAllowedToEdit($user);

                    if($hasUserGroupAllowedToEdit){
                        wp_enqueue_script( 'cg_contest_gallery_edit_profile_js', plugins_url('/../../../v10/v10-js/admin/profile/edit-profile.js', __FILE__), array('jquery'), cg_get_version_for_scripts());
                    }
                }
            }
        }

    }
}

// wp_enqueue_style is better to be done in admin_bar_menu instead of wp_before_admin_bar_render, then it will be rendered in more cases
add_action( 'wp_enqueue_scripts', 'cg_modified_admin_bar_for_contest_gallery_user_v14_wp_enqueue_style' );
if(!function_exists('cg_modified_admin_bar_for_contest_gallery_user_v14_wp_enqueue_style')){
    function cg_modified_admin_bar_for_contest_gallery_user_v14_wp_enqueue_style($wp_admin_bar) {

        $is_user_logged_in = is_user_logged_in();

        $user = wp_get_current_user();
        if($is_user_logged_in && in_array('contest_gallery_user_since_v14',(array)$user->roles)){
            wp_enqueue_style( 'cg_contest_gallery_user_profile_css', plugins_url('/../../../v10/v10-css/backend/cg_contest_gallery_user_profile.css', __FILE__), false, cg_get_version_for_scripts() );
            wp_enqueue_style( 'cg_contest_gallery_user_profile_general_css', plugins_url('/../../../v10/v10-css/backend/cg_contest_gallery_user_profile_general.css', __FILE__), false, cg_get_version_for_scripts());
        }

    }
}

// wp_enqueue_style is better to be done in admin_bar_menu instead of wp_before_admin_bar_render, then it will be rendered in more cases
add_action( 'admin_bar_menu', 'cg_modified_admin_bar_for_eventually_contest_gallery_profile_fields_enqueue_style' );
if(!function_exists('cg_modified_admin_bar_for_eventually_contest_gallery_profile_fields_enqueue_style')){
    function cg_modified_admin_bar_for_eventually_contest_gallery_profile_fields_enqueue_style($wp_admin_bar) {

        $is_user_logged_in = is_user_logged_in();

        $user = wp_get_current_user();
        if($is_user_logged_in && in_array('contest_gallery_user_since_v14',(array)$user->roles)){
            wp_enqueue_style( 'cg_contest_gallery_user_profile_css', plugins_url('/../../../v10/v10-css/backend/cg_contest_gallery_user_profile.css', __FILE__), false, cg_get_version_for_scripts() );
        }
        if($is_user_logged_in){
            if(is_admin()){
                if(cgHasUserGroupAllowedToEdit(wp_get_current_user())){
                    wp_enqueue_style( 'cg_contest_gallery_user_profile_general_css', plugins_url('/../../../v10/v10-css/backend/cg_contest_gallery_user_profile_general.css', __FILE__), false, cg_get_version_for_scripts());
                    wp_enqueue_style( 'cg_contest_gallery_user_profile_image_css', plugins_url('/../../../v10/v10-css/backend/cg_contest_gallery_user_profile_image.css', __FILE__), false, cg_get_version_for_scripts());
                }
            }
        }
    }
}

if(!function_exists('cgHasUserGroupAllowedToEdit')){
    function cgHasUserGroupAllowedToEdit( $user ) {

        global $wpdb;
        $tablename_registry_and_login_options = $wpdb->prefix . "contest_gal1ery_registry_and_login_options";

        $registryAndLoginOptions = $wpdb->get_row( "SELECT * FROM $tablename_registry_and_login_options WHERE GeneralID = '1'" );
        $EditProfileGroups = (!empty($registryAndLoginOptions->EditProfileGroups)) ? unserialize($registryAndLoginOptions->EditProfileGroups) : [];

        $hasUserGroupAllowedToEdit = false;

        foreach ($EditProfileGroups as $RoleGroupKey => $value){
            if(in_array('contest_gallery_user_since_v14',(array)$user->roles) OR in_array($RoleGroupKey,(array)$user->roles)){
                $hasUserGroupAllowedToEdit = true;
                break;
            }
        }

        return $hasUserGroupAllowedToEdit;
    }
}


add_action( 'show_user_profile', 'cg_edit_additional_registry_form_user_fields' );
add_action( 'edit_user_profile', 'cg_edit_additional_registry_form_user_fields' );
if(!function_exists('cg_edit_additional_registry_form_user_fields')){
    function cg_edit_additional_registry_form_user_fields(  ) {

        global $pagenow;

        if(is_admin()){

            $user = wp_get_current_user();
            $userId = $user->ID;

            if($pagenow=='profile.php'){

                global $wpdb;

                $tablenameCreateUserForm = $wpdb->prefix . "contest_gal1ery_create_user_form";
                $tablename = $wpdb->base_prefix . "contest_gal1ery";

                $is_frontend = true;
                include(__DIR__ . "/../../../check-language-general.php");

                //$cgImagesCheck = false;

                // preparing implementation logic
               //$cgImagesCheck = $wpdb->get_var("SELECT COUNT(*) FROM $tablename WHERE WpUserId = $userId LIMIT 1");

                $hasUserGroupAllowedToEdit = cgHasUserGroupAllowedToEdit($user);

                if($hasUserGroupAllowedToEdit){

                 echo "<input type='hidden' name='cg_user_data_available' value='true' >";
                 echo "<input type='hidden' id='cg_language_ThisNicknameAlreadyExists' value='$language_ThisNicknameAlreadyExistsGeneral'>";
                 echo "<input type='hidden' id='cg_language_ThisFileTypeIsNotAllowed' value='$language_ThisFileTypeIsNotAllowed'>";
                 echo "<input type='hidden' id='cg_language_TheFileYouChoosedIsToBigMaxAllowedSize' value='$language_TheFileYouChoosedIsToBigMaxAllowedSize'>";
                 echo "<input type='hidden' id='cg_language_ChooseYourImage' value='$language_ChooseYourImage'>";
                 echo "<input type='hidden' id='cg_language_required' value='$language_required'>";

                 echo "<input type='hidden' name='action' value='post_cg_check_nickname_edit_profile' id='post_cg_check_nickname_edit_profile'>";
                 echo "<input type='hidden' name='cg_user_id' value='".$user->ID."' >";

                $selectUserForm = $wpdb->get_results("SELECT * FROM $tablenameCreateUserForm WHERE GeneralID = '1' && 
                (Field_Type = 'user-text-field' OR Field_Type = 'user-comment-field' OR Field_Type = 'user-select-field')
        ORDER BY Field_Order ASC");

                $selectProfileImage = $wpdb->get_row("SELECT * FROM $tablenameCreateUserForm WHERE GeneralID = '1' && 
                (Field_Type = 'profile-image')");

                if(count($selectUserForm) OR !empty($selectProfileImage)){

                    echo "<h2 id='cg_language_CGProfileInformation' >$language_CGProfileInformation</h2>";

                    echo '<table class="form-table">';
                    echo "<input type='hidden' name='action' value='post_cg_backend_image_upload' id='post_cg_backend_image_upload' >";

                    if(!empty($selectProfileImage)){
                        if($selectProfileImage->Required==1){
                            echo "<input type='hidden' id='cg_input_image_upload_file_required' value='true'>";
                        }
                        $WpUpload = $wpdb->get_var("SELECT WpUpload FROM $tablename WHERE WpUserId = $userId AND IsProfileImage = 1");

                        $required = '';

                        if($selectProfileImage->Required==1){
                            $required = " ($language_required)";
                        }

                        if(!empty($WpUpload)){
                            echo "<input type='hidden' name='cg_input_image_upload_file_to_delete_wp_id' value='$WpUpload' id='cg_input_image_upload_file_to_delete_wp_id' disabled />";
                            $imgSrcLarge=wp_get_attachment_image_src($WpUpload, 'large');
                            echo '<tr>';
                            echo '<th><label for="cg_input_image_upload_file">'.$selectProfileImage->Field_Name.$required.'</label></th>';
                            echo '<td>';
                            echo "<input type='file' name='cg_input_image_upload_file[]' id='cg_input_image_upload_file' /><div id='cgShowExistingProfileImage' class='cg_hide'></div>";
                            if(!empty($imgSrcLarge)){
                                $imgSrcLarge=$imgSrcLarge[0];
                                echo "<div id='cg_input_image_upload_file_preview' >";
                                echo "<div class='cg_input_image_upload_file_preview_img cg_input_image_upload_file_preview_img_existing' style='background: url($imgSrcLarge) no-repeat center center;'>";
                                echo "</div>";
                                echo "</div>";
                           //     if($selectProfileImage->Required!=1){
                                    echo "<input type='button' id='cg_input_image_upload_file_to_delete_button' class='button cg-button-remove-profile-image' value='$language_RemoveProfileImage'>";
                               // }
                            }else{
                                echo "<div id='cg_input_image_upload_file_preview' class='cg_hide'>";
                                echo "</div>";
                               // if($selectProfileImage->Required!=1){
                                    echo "<input type='button' id='cg_input_image_upload_file_to_delete_button' class='button cg-button-remove-profile-image cg_hide' value='$language_RemoveProfileImage'>";
                              //  }
                            }
                            echo '</td>';
                            echo '</tr>';
                        }else{
                            echo '<tr>';
                            echo '<th><label for="cg_input_image_upload_file">'.$selectProfileImage->Field_Name.$required.'</label></th>';
                            echo '<td>';
                            echo "<input type='file' name='cg_input_image_upload_file[]' id='cg_input_image_upload_file' />";
                            echo "<div id='cg_input_image_upload_file_preview' class='cg_hide'>";
                            echo "</div>";
                      //      if($selectProfileImage->Required!=1){
                                echo "<input type='button' id='cg_input_image_upload_file_to_delete_button' class='button cg-button-remove-profile-image cg_hide' value='$language_RemoveProfileImage'>";
                       //     }
                            echo '</td>';
                            echo '</tr>';
                        }
                    }

                    foreach ($selectUserForm as $formField){

                            $fieldId = contest_gal1ery_convert_for_html_output($formField->id);
                            $fieldTitle = contest_gal1ery_convert_for_html_output($formField->Field_Name);
                            $fieldName = "cg_custom_field_id_".$fieldId;
                            $userValue = contest_gal1ery_convert_for_html_output(get_the_author_meta( $fieldName, $user->ID ) );

                            $fieldContent = html_entity_decode(stripslashes($formField->Field_Content));

                            $required = '';
                            $cg_input_field_required = '';

                            if($formField->Required==1){
                                $required = " ($language_required)";
                                $cg_input_field_required = "cg_input_field_required";
                            }

                            if ($formField->Field_Type == 'user-text-field') {

                                echo '<tr>
                                    <th><label for="'.$fieldName.'">'.$fieldTitle.$required.'</label></th>
                                    <td>
                                        <input type="text" name="'.$fieldName.'" id="'.$fieldName.'" value="'.$userValue.'" class="regular-text cg_input_field '.$cg_input_field_required.'" />
                                    </td>
                                </tr>';

                            }

                            if ($formField->Field_Type == 'user-comment-field') {

                                echo '<tr>
                                    <th><label for="'.$fieldName.'">'.$fieldTitle.$required.'</label></th>
                                    <td>
                                        <textarea name="'.$fieldName.'" id="'.$fieldName.'" rows="5" class="cg_input_field '.$cg_input_field_required.'" >'.$userValue.'</textarea>
                                    </td>
                                </tr>';

                            }

                            if ($formField->Field_Type == 'user-select-field') {

                                $textAr = explode("\n", $fieldContent);

                                echo '<tr>
                                    <th><label for="'.$fieldName.'">'.$fieldTitle.$required.'</label></th>
                                    <td>
                                        <select name="'.$fieldName.'" id="'.$fieldName.'" class="cg_input_field '.$cg_input_field_required.'"   >';
                                echo "<option value='' >......</option>";
                                foreach ($textAr as $optionKey => $optionValue) {
                                    $optionValue = preg_replace( "/\r|\n/", "", $optionValue );
                                    $selected = '';
                                    if($optionValue==$userValue){
                                        $selected = 'selected';
                                    }
                                    echo "<option value='$optionValue' $selected>$optionValue</option>";
                                }
                                echo '</select>
                                    </td>
                                </tr>';

                            }

                        }

                    echo '</table>';

                    ?>

                    <!--EXAMPLE WITH TRANSLATIONS-->

                    <!--<h3><?php /*_e("Extra profile information", "blank"); */?></h3>

                <table class="form-table">
                    <tr>
                        <th><label for="address"><?php /*_e("Address"); */?></label></th>
                        <td>
                            <input type="text" name="address" id="address" value="<?php /*echo esc_attr( get_the_author_meta( 'address', $user->ID ) ); */?>" class="regular-text" /><br />
                            <span class="description"><?php /*_e("Please enter your address."); */?></span>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="city"><?php /*_e("City"); */?></label></th>
                        <td>
                            <input type="text" name="city" id="city" value="<?php /*echo esc_attr( get_the_author_meta( 'city', $user->ID ) ); */?>" class="regular-text" /><br />
                            <span class="description"><?php /*_e("Please enter your city."); */?></span>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="postalcode"><?php /*_e("Postal Code"); */?></label></th>
                        <td>
                            <input type="text" name="postalcode" id="postalcode" value="<?php /*echo esc_attr( get_the_author_meta( 'postalcode', $user->ID ) ); */?>" class="regular-text" /><br />
                            <span class="description"><?php /*_e("Please enter your postal code."); */?></span>
                        </td>
                    </tr>
                </table>-->
                    <?php

                }
            }

            }

        }

    }
}

if(!function_exists('cg_get_registry_and_login_options_v14')){
    function cg_get_registry_and_login_options_v14( ) {

        global $wpdb;

        $tablename_pro_options = $wpdb->prefix . "contest_gal1ery_pro_options";
        $tablename_options_visual = $wpdb->prefix . "contest_gal1ery_options_visual";

        $optionsVisual = $wpdb->get_row( "SELECT * FROM $tablename_options_visual WHERE GeneralID = '1'" );
        $optionsPRO = $wpdb->get_row( "SELECT * FROM $tablename_pro_options WHERE GeneralID = '1'" );

        $optionsForGeneralIDsinceV14 = [];

        $optionsForGeneralIDsinceV14['visual'] = [];
        $optionsForGeneralIDsinceV14['visual']['BorderRadiusRegistry'] = $optionsVisual->BorderRadiusRegistry;
        $optionsForGeneralIDsinceV14['visual']['FeControlsStyleRegistry'] = $optionsVisual->FeControlsStyleRegistry;
        $optionsForGeneralIDsinceV14['visual']['BorderRadiusLogin'] = $optionsVisual->BorderRadiusLogin;
        $optionsForGeneralIDsinceV14['visual']['FeControlsStyleLogin'] = $optionsVisual->FeControlsStyleLogin;

        $optionsForGeneralIDsinceV14['pro'] = [];
        $optionsForGeneralIDsinceV14['pro']['ForwardAfterLoginUrlCheck'] = $optionsPRO->ForwardAfterLoginUrlCheck;
        $optionsForGeneralIDsinceV14['pro']['ForwardAfterLoginUrl'] = $optionsPRO->ForwardAfterLoginUrl;
        $optionsForGeneralIDsinceV14['pro']['ForwardAfterLoginTextCheck'] = $optionsPRO->ForwardAfterLoginTextCheck;
        $optionsForGeneralIDsinceV14['pro']['ForwardAfterLoginText'] = $optionsPRO->ForwardAfterLoginText;
        $optionsForGeneralIDsinceV14['pro']['RegMailOptional'] = $optionsPRO->RegMailOptional;
        $optionsForGeneralIDsinceV14['pro']['ForwardAfterRegText'] = $optionsPRO->ForwardAfterRegText;
        $optionsForGeneralIDsinceV14['pro']['TextAfterEmailConfirmation'] = $optionsPRO->TextAfterEmailConfirmation;
        $optionsForGeneralIDsinceV14['pro']['HideRegFormAfterLogin'] = $optionsPRO->HideRegFormAfterLogin;
        $optionsForGeneralIDsinceV14['pro']['HideRegFormAfterLoginShowTextInstead'] = $optionsPRO->HideRegFormAfterLoginShowTextInstead;
        $optionsForGeneralIDsinceV14['pro']['HideRegFormAfterLoginTextToShow'] = $optionsPRO->HideRegFormAfterLoginTextToShow;
        $optionsForGeneralIDsinceV14['pro']['RegMailAddressor'] = $optionsPRO->RegMailAddressor;
        $optionsForGeneralIDsinceV14['pro']['RegMailReply'] = $optionsPRO->RegMailReply;
        $optionsForGeneralIDsinceV14['pro']['RegMailSubject'] = $optionsPRO->RegMailSubject;
        $optionsForGeneralIDsinceV14['pro']['TextEmailConfirmation'] = $optionsPRO->TextEmailConfirmation;

        return $optionsForGeneralIDsinceV14;

    }
}



?>