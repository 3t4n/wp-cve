<?php

if(!function_exists('cg_create_contest_gallery_user_role')){
    function cg_create_contest_gallery_user_role() {
        add_role(
            'contest_gallery_user_since_v14',
            __( 'Contest Gallery User since v14' ),
            array(
                'read' => true
            )
        );
    }
}

add_action('cg_create_general_registration_form_v14','cg_create_general_registration_form_v14');
if(!function_exists('cg_create_general_registration_form_v14')){
    function cg_create_general_registration_form_v14(){

        global $wpdb;

        $tablename_create_user_form = $wpdb->prefix . "contest_gal1ery_create_user_form";

        $count_tablename_create_user_form_with_GeneralID = $wpdb->get_var( "SELECT id FROM $tablename_create_user_form WHERE GeneralID = 1 LIMIT 1");

        if(empty($count_tablename_create_user_form_with_GeneralID)){

            $wpdb->query( $wpdb->prepare(
                "
						INSERT INTO $tablename_create_user_form
						( id, GalleryID, Field_Type, Field_Order,
						Field_Name,Field_Content,Min_Char,Max_Char,
						Required,Active,GeneralID)
						VALUES ( %s,%d,%s,%s,
						%s,%s,%d,%d,
						%d,%d,%d)
					",
                '',0,'main-user-name',1,
                'Username (for login)','',6,100,
                1,1,1
            ) );

            $wpdb->query( $wpdb->prepare(
                "
						INSERT INTO $tablename_create_user_form
						( id, GalleryID, Field_Type, Field_Order,
						Field_Name,Field_Content,Min_Char,Max_Char,
						Required,Active,GeneralID)
						VALUES ( %s,%d,%s,%s,
						%s,%s,%d,%d,
						%d,%d,%d)
					",
                '',0,'main-nick-name',1,
                'Nickname (to display in frontend)','',6,100,
                1,1,1
            ) );

            $wpdb->query( $wpdb->prepare(
                "
						INSERT INTO $tablename_create_user_form
						( id, GalleryID, Field_Type, Field_Order,
						Field_Name,Field_Content,Min_Char,Max_Char,
						Required,Active,GeneralID)
						VALUES ( %s,%d,%s,%s,
						%s,%s,%d,%d,
						%d,%d,%d)
					",
                '',0,'main-mail',2,
                'E-mail (for login)','',0,0,
                1,1,1
            ) );

            $wpdb->query( $wpdb->prepare(
                "
						INSERT INTO $tablename_create_user_form
						( id, GalleryID, Field_Type, Field_Order,
						Field_Name,Field_Content,Min_Char,Max_Char,
						Required,Active,GeneralID)
						VALUES ( %s,%d,%s,%s,
						%s,%s,%d,%d,
						%d,%d,%d)
					",
                '',0,'password',3,
                'Password','',6,100,
                1,1,1
            ) );

            $wpdb->query( $wpdb->prepare(
                "
						INSERT INTO $tablename_create_user_form
						( id, GalleryID, Field_Type, Field_Order,
						Field_Name,Field_Content,Min_Char,Max_Char,
						Required,Active,GeneralID)
						VALUES ( %s,%d,%s,%s,
						%s,%s,%d,%d,
						%d,%d,%d)
					",
                '',0,'password-confirm',4,
                'Password confirm','',6,100,
                1,1,1
            ) );

        }

    }
}


if(!function_exists('cg_create_registry_and_login_options_v14')){
    function cg_create_registry_and_login_options_v14( ) {

        global $wpdb;

        $tablename_options = $wpdb->prefix . "contest_gal1ery_options";
        $tablename_pro_options = $wpdb->prefix . "contest_gal1ery_pro_options";
        $tablename_options_visual = $wpdb->prefix . "contest_gal1ery_options_visual";
        $tablename_registry_and_login_options = $wpdb->prefix . "contest_gal1ery_registry_and_login_options";

        $count_tablename_registry_and_login_options = $wpdb->get_var( "SELECT id FROM $tablename_registry_and_login_options WHERE GeneralID = 1 LIMIT 1");
        if(empty($count_tablename_registry_and_login_options)){
            cg_create_registry_and_login_options();
        }

        $count_pro_options_table_with_GeneralID = $wpdb->get_var( "SELECT id FROM $tablename_pro_options WHERE GeneralID = 1 LIMIT 1");

        if(empty($count_pro_options_table_with_GeneralID)){

            include(__DIR__.'/../../../../v10/v10-admin/json-values.php');
            $RegMailAddressor = trim(get_option('blogname'));
            $RegMailReply = get_option('admin_email');
            $RegMailSubject = 'Please complete your registration';
            $TextEmailConfirmation = 'Complete your registration by clicking on the link below: <br/><br/> $regurl$';
            $ForwardAfterRegText = 'Thank you for your registration<br/>Check your email account to confirm your email and complete the registration. If you don\'t see any message then plz check also the spam folder.';
            $TextAfterEmailConfirmation = 'Thank you for your registration. You are now able to login and to take part on the photo contest.';
            $ForwardAfterLoginText = 'You are now logged in. Have fun with photo contest.';
            $ForwardAfterLoginTextCheck = 1;
            $ForwardAfterLoginUrlCheck = 0;
            $ForwardAfterLoginUrl = '';
            $RegMailOptional = 0;
            $HideRegFormAfterLogin = 0;
            $HideRegFormAfterLoginShowTextInstead = 0;
            $HideRegFormAfterLoginTextToShow = '';

            $wpdb->query( $wpdb->prepare(
                "
				INSERT INTO $tablename_pro_options
				(
				 id, GalleryID, GeneralID, 
				RegMailOptional, ForwardAfterRegText, TextAfterEmailConfirmation,
				HideRegFormAfterLogin, HideRegFormAfterLoginShowTextInstead,HideRegFormAfterLoginTextToShow,
				RegMailAddressor, RegMailReply, RegMailSubject, TextEmailConfirmation,
				ForwardAfterLoginUrlCheck,ForwardAfterLoginUrl, ForwardAfterLoginTextCheck, ForwardAfterLoginText
				)
				VALUES (
				%s,%d,%d,
				%d,%s,%s,
				%d,%d,%s,
				%s,%s,%s,%s,
				%d,%s,%d,%s
				)
			",
                '',0,1,
                $RegMailOptional, $ForwardAfterRegText, $TextAfterEmailConfirmation,
                $HideRegFormAfterLogin, $HideRegFormAfterLoginShowTextInstead, $HideRegFormAfterLoginTextToShow,
                $RegMailAddressor, $RegMailReply, $RegMailSubject, $TextEmailConfirmation,
                $ForwardAfterLoginUrlCheck,$ForwardAfterLoginUrl,$ForwardAfterLoginTextCheck,$ForwardAfterLoginText
            ) );

            $wpdb->query( $wpdb->prepare(
                "
				INSERT INTO $tablename_options_visual
				( id, GalleryID, GeneralID,BorderRadiusRegistry,BorderRadiusLogin,FeControlsStyleRegistry,FeControlsStyleLogin)
				VALUES (%s,%d,%d,%d,%d,%s,%s)
			",
                '',0,1,1,1,'white','white'
            ) );

        }


    }
}

if(!function_exists('cg_create_registry_and_login_options')){
    function cg_create_registry_and_login_options($GalleryID = 0) {// gallery id will be send for gallery created before 14, creating of this options in edit-options

        $GeneralID = 1;
        $RegistryUserRole = 'contest_gallery_user_since_v14';
        if(!empty($GalleryID)){
            $GeneralID = 0;
            $RegistryUserRole = 'contest_gallery_user';
        }

        global $wpdb;

        $tablename_registry_and_login_options = $wpdb->prefix . "contest_gal1ery_registry_and_login_options";

        $LostPasswordMailAddressor = trim(get_option('blogname'));
        $LostPasswordMailReply = get_option('admin_email');
        $LostPasswordMailSubject = 'Reset your password';
        $LostPasswordMailConfirmation = 'Reset your password by using link below: <br/><br/> $resetpasswordurl$';
        $TextBeforeLoginForm = '';
        $TextBeforeRegFormBeforeLoggedIn = '';
        $PermanentTextWhenLoggedIn = '';

        $wpdb->query( $wpdb->prepare(
            "
						INSERT INTO $tablename_registry_and_login_options
						( id, GalleryID, GeneralID, LogoutLink, BackToGalleryLink,RegistryUserRole,
						 LostPasswordMailAddressor, LostPasswordMailReply,
						 LostPasswordMailSubject, LostPasswordMailConfirmation,
						 TextBeforeLoginForm,TextBeforeRegFormBeforeLoggedIn,PermanentTextWhenLoggedIn
						 )
						VALUES ( %s,%d,%d,%s,%s,%s,
						        %s,%s,
						        %s,%s,
						        %s,%s,%s
						        )
					",
            '',$GalleryID,$GeneralID,'', '',$RegistryUserRole,
            $LostPasswordMailAddressor,$LostPasswordMailReply,
            $LostPasswordMailSubject,$LostPasswordMailConfirmation,
            $TextBeforeLoginForm,$TextBeforeRegFormBeforeLoggedIn,$PermanentTextWhenLoggedIn
        ) );

    }
}

?>