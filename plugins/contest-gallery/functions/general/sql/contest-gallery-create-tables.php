<?php

if(!function_exists('contest_gal1ery_create_table')){
    function contest_gal1ery_create_table($i,$isShowError = false){

        global $wpdb;
        $lastError = '';

        $tablename = $wpdb->base_prefix . "$i"."contest_gal1ery";
        $tablename_ip = $wpdb->base_prefix . "$i"."contest_gal1ery_ip";
        $tablename_comments = $wpdb->base_prefix . "$i"."contest_gal1ery_comments";
        $tablename_comments_notification_options = $wpdb->base_prefix . "$i"."contest_gal1ery_comments_notification_options";
        $tablename_options = $wpdb->base_prefix . "$i"."contest_gal1ery_options";
        $tablename_options_input = $wpdb->base_prefix . "$i"."contest_gal1ery_options_input";
        $tablename_options_visual = $wpdb->base_prefix . "$i"."contest_gal1ery_options_visual";
        $tablename_email = $wpdb->base_prefix . "$i"."contest_gal1ery_mail";
        $tablename_email_admin = $wpdb->base_prefix . "$i"."contest_gal1ery_mail_admin";
        $tablename_mail_user_upload = $wpdb->base_prefix . "$i"."contest_gal1ery_mail_user_upload";
        $tablename_mail_user_comment = $wpdb->base_prefix . "$i"."contest_gal1ery_mail_user_comment";
        $tablename_mail_user_vote = $wpdb->base_prefix . "$i"."contest_gal1ery_mail_user_vote";
        $tablename_user_comment_mails = $wpdb->base_prefix . "$i"."contest_gal1ery_user_comment_mails";
        $tablename_user_vote_mails = $wpdb->base_prefix . "$i"."contest_gal1ery_user_vote_mails";
        $tablename_entries = $wpdb->base_prefix . "$i"."contest_gal1ery_entries";
        $tablename_create_user_entries = $wpdb->base_prefix . "$i"."contest_gal1ery_create_user_entries";
        $tablename_pro_options = $wpdb->base_prefix . "$i"."contest_gal1ery_pro_options";
        $tablename_create_user_form = $wpdb->base_prefix . "$i"."contest_gal1ery_create_user_form";
        $tablename_form_input = $wpdb->base_prefix . "$i"."contest_gal1ery_f_input";
        $tablename_form_output = $wpdb->base_prefix . "$i"."contest_gal1ery_f_output";
        //  $tablename_mail_gallery = $wpdb->base_prefix . "$i"."contest_gal1ery_mail_gallery";
        //  $tablename_mail_gallery_users_history = $wpdb->base_prefix . "$i"."contest_gal1ery_mail_gallery_users_history";
        $tablename_mails_collected = $wpdb->base_prefix . "$i"."contest_gal1ery_mails_collected";
        $tablename_mail_confirmation = $wpdb->base_prefix . "$i"."contest_gal1ery_mail_confirmation";
        $tablename_categories = $wpdb->base_prefix . "$i"."contest_gal1ery_categories";
//    $tablename_mails_users_relations = $wpdb->base_prefix . "$i"."contest_gal1ery_mails_users_realations";
        $tablename_registry_and_login_options = $wpdb->base_prefix . "$i"."contest_gal1ery_registry_and_login_options";
        $tablename_google_options = $wpdb->base_prefix . "$i"."contest_gal1ery_google_options";
        $tablename_google_users = $wpdb->base_prefix . "$i"."contest_gal1ery_google_users";
        $tablename_wp_pages = $wpdb->base_prefix . "$i"."contest_gal1ery_wp_pages";
      //  $tablename_general = $wpdb->base_prefix . "$i"."contest_gal1ery_general";

        $charset_collate = $wpdb->get_charset_collate();

        if($wpdb->get_var("SHOW TABLES LIKE '$tablename_categories'") != $tablename_categories){
            $sql = "CREATE TABLE $tablename_categories (
		id BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		GalleryID INT (20),
	    Name VARCHAR(1000),
	    Field_Order INT(3),
        Active TINYINT
		) $charset_collate;"; // WordPress $charset_collate was added in 21.0.1
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            cg_echo_last_sql_error($isShowError,$lastError);
        }

        // WpUpload DEFAULT 0 was added 13.09.2020
        // WpUserId DEFAULT 0 was added 13.09.2020
        // IP VARCHAR 256 instead of 99 since 21.3.6
        if($wpdb->get_var("SHOW TABLES LIKE '$tablename'") != $tablename){
            $sql = "CREATE TABLE $tablename (
		id BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		rowid INT(20),
		Timestamp INT(20),
		NamePic VARCHAR(512),
		ImgType VARCHAR(5),
		CountC INT(11) DEFAULT 0,
		CountR INT(11) DEFAULT 0,
		CountS INT(11) DEFAULT 0,
		Rating INT(17)  DEFAULT 0,
		GalleryID INT(20),
		Active INT(1) DEFAULT 0,
		Informed INT(1) DEFAULT 0,
		WpUpload INT(11) DEFAULT 0,
		Width INT (11),
		Height INT (11),
		WpUserId INT (11) DEFAULT 0,
		rSource INT(11),
		rThumb INT(11),
		addCountS INT(11) DEFAULT 0,
		addCountR1 INT(11) DEFAULT 0,
		addCountR2 INT(11) DEFAULT 0,
		addCountR3 INT(11) DEFAULT 0,
		addCountR4 INT(11) DEFAULT 0,
		addCountR5 INT(11) DEFAULT 0,
		addCountR6 INT(11) DEFAULT 0,
		addCountR7 INT(11) DEFAULT 0,
		addCountR8 INT(11) DEFAULT 0,
		addCountR9 INT(11) DEFAULT 0,
		addCountR10 INT(11) DEFAULT 0,
		Category INT(20) DEFAULT 0,
		Exif TEXT DEFAULT '',
		IP VARCHAR(256),
		CountR1 INT(11) DEFAULT 0,
		CountR2 INT(11) DEFAULT 0,
		CountR3 INT(11) DEFAULT 0,
		CountR4 INT(11) DEFAULT 0,
		CountR5 INT(11) DEFAULT 0,
		CountR6 INT(11) DEFAULT 0,
		CountR7 INT(11) DEFAULT 0,
		CountR8 INT(11) DEFAULT 0,
		CountR9 INT(11) DEFAULT 0,
		CountR10 INT(11) DEFAULT 0,
		Version VARCHAR(30),
		CheckSet VARCHAR(30),
		CookieId VARCHAR (99),
		Winner TINYINT DEFAULT 0,
		IsProfileImage TINYINT DEFAULT 0,
		MultipleFiles TEXT DEFAULT '',
		CountCtoReview TINYINT DEFAULT 0,
		PositionNumber TINYINT DEFAULT 0,
		WpPage BIGINT(20) DEFAULT 0,
		WpPageUser BIGINT(20) DEFAULT 0,
		WpPageNoVoting BIGINT(20) DEFAULT 0,
		WpPageWinner BIGINT(20) DEFAULT 0
		) $charset_collate;"; // WordPress $charset_collate was added in 21.0.1
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            cg_echo_last_sql_error($isShowError,$lastError);
        }

        else{

            $sql = "ALTER TABLE $tablename MODIFY COLUMN NamePic VARCHAR(1000) NOT NULL";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            cg_echo_last_sql_error($isShowError,$lastError);
        }

        if($wpdb->get_var("SHOW TABLES LIKE '$tablename_ip'") != $tablename_ip){
            $sql = "CREATE TABLE $tablename_ip (
		id BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		pid INT(20),
		IP VARCHAR (99),
		GalleryID INT(20),
		Rating INT (1),
		RatingS INT (1),
		WpUserId INT (11),
		VoteDate VARCHAR (30),
		Tstamp INT (11),
        OptionSet VARCHAR (30),
        CookieId VARCHAR (99),
        Category INT (11) DEFAULT 0,
        CategoriesOn TINYINT DEFAULT 0
		) $charset_collate;"; // WordPress $charset_collate was added in 21.0.1
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }

        if($wpdb->get_var("SHOW TABLES LIKE '$tablename_comments'") != $tablename_comments){
            $sql = "CREATE TABLE $tablename_comments (
		id BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		pid INT(20) DEFAULT 0,
		GalleryID INT (6) DEFAULT 0,
		Name VARCHAR(1000) DEFAULT '',
		Date VARCHAR(50) DEFAULT '',
		Comment TEXT DEFAULT '',
		Timestamp VARCHAR(20) DEFAULT '',
		IP VARCHAR (200) DEFAULT '',
		WpUserId INT (11) DEFAULT 0,
		ReviewTstamp INT(11) DEFAULT 0,
		Active TINYINT DEFAULT 0
		) $charset_collate;"; // WordPress $charset_collate was added in 21.0.1
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            cg_echo_last_sql_error($isShowError,$lastError);
        }

        //URL VARCHAR(2000) erst ab Version 3.06 vorhanden
        if($wpdb->get_var("SHOW TABLES LIKE '$tablename_email'") != $tablename_email){
            $sql = "CREATE TABLE $tablename_email (
		id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		GalleryID INT(20),
		Admin VARCHAR(200),
		Header VARCHAR(200),
		Reply VARCHAR(200),
		CC VARCHAR(200),
		BCC VARCHAR(200),
		URL VARCHAR(2000),
		Content TEXT
		) $charset_collate;"; // WordPress $charset_collate was added in 21.0.1
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            cg_echo_last_sql_error($isShowError,$lastError);
        }

        $tableOptionsHasToBeCreated = false;
        if($wpdb->get_var("SHOW TABLES LIKE '$tablename_options'") != $tablename_options){
            $sql = "CREATE TABLE $tablename_options(
		id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		GalleryName VARCHAR(200) DEFAULT '',
		PicsPerSite INT (3) DEFAULT 0,
		WidthThumb INT (5) DEFAULT 0,
		HeightThumb INT (5) DEFAULT 0,
		WidthGallery INT (5) DEFAULT 0,
		HeightGallery INT (5) DEFAULT 0,
		DistancePics INT (5) DEFAULT 0,
		DistancePicsV INT (5) DEFAULT 0,
        MinResJPGon INT(1) DEFAULT 0,
		MinResPNGon INT(1) DEFAULT 0,
		MinResGIFon INT(1) DEFAULT 0,
        MinResJPGwidth INT(20) DEFAULT 0,
		MinResJPGheight INT(20) DEFAULT 0,
		MinResPNGwidth INT(20) DEFAULT 0,
		MinResPNGheight INT(20) DEFAULT 0,
		MinResGIFwidth INT(20) DEFAULT 0,
		MinResGIFheight INT(20) DEFAULT 0,
		MaxResJPGon INT(1) DEFAULT 0,
		MaxResPNGon INT(1) DEFAULT 0,
		MaxResGIFon INT(1) DEFAULT 0,
		MaxResJPG INT(20) DEFAULT 0,
		MaxResJPGwidth INT(20) DEFAULT 0,
		MaxResJPGheight INT(20) DEFAULT 0,
		MaxResPNG INT(20) DEFAULT 0,
		MaxResPNGwidth INT(20) DEFAULT 0,
		MaxResPNGheight INT(20) DEFAULT 0,
		MaxResGIF INT(20) DEFAULT 0,
		MaxResGIFwidth INT(20) DEFAULT 0,
		MaxResGIFheight INT(20) DEFAULT 0,
		OnlyGalleryView TINYINT DEFAULT 0,
		SinglePicView TINYINT DEFAULT 0,
		ScaleOnly TINYINT DEFAULT 0,
		ScaleAndCut TINYINT DEFAULT 0,
		FullSize TINYINT DEFAULT 0,
		FullSizeGallery TINYINT DEFAULT 0,
        FullSizeSlideOutStart TINYINT DEFAULT 0,
		AllowSort TINYINT DEFAULT 0,
		RandomSort TINYINT DEFAULT 0,
		RandomSortButton TINYINT DEFAULT 0,
		AllowComments TINYINT DEFAULT 0,
		CommentsOutGallery TINYINT DEFAULT 0,
		AllowRating TINYINT DEFAULT 0,
		VotesPerUser INT(5) DEFAULT 0,
		RatingOutGallery TINYINT DEFAULT 0,
		ShowAlways TINYINT DEFAULT 1,
		ShowAlwaysInfoSlider TINYINT DEFAULT 0,
		IpBlock TINYINT DEFAULT 0,
		CheckLogin TINYINT DEFAULT 0,
		FbLike TINYINT DEFAULT 0,
		FbLikeGallery TINYINT DEFAULT 0,
		FbLikeGalleryVote TINYINT DEFAULT 0,
		AllowGalleryScript TINYINT DEFAULT 0,
		InfiniteScroll TINYINT DEFAULT 0,
		FullSizeImageOutGallery TINYINT DEFAULT 0,
		FullSizeImageOutGalleryNewTab TINYINT DEFAULT 0,
		Inform TINYINT DEFAULT 0,
		InformAdmin TINYINT DEFAULT 0,
		TimestampPicDownload VARCHAR(20) DEFAULT 0,
		ThumbLook TINYINT DEFAULT 0,
		AdjustThumbLook TINYINT DEFAULT 0,
		HeightLook TINYINT DEFAULT 0,
		RowLook TINYINT DEFAULT 0,
		ThumbLookOrder TINYINT DEFAULT 0,
		HeightLookOrder TINYINT DEFAULT 0,
		RowLookOrder TINYINT DEFAULT 0,
		HeightLookHeight INT(3) DEFAULT 0,
		ThumbsInRow TINYINT DEFAULT 0,
		PicsInRow TINYINT DEFAULT 0,
		LastRow TINYINT DEFAULT 0,
		HideUntilVote TINYINT DEFAULT 0,
		HideInfo TINYINT DEFAULT 0,
		ActivateUpload TINYINT DEFAULT 0,
		ContestEnd TINYINT DEFAULT 0,
		ContestEndTime VARCHAR(100) DEFAULT '',
		ForwardToURL TINYINT DEFAULT 0,
		ForwardFrom TINYINT DEFAULT 0,
		ForwardType TINYINT DEFAULT 0,
		ActivatePostMaxMB TINYINT DEFAULT 0,
		PostMaxMB INT(20) DEFAULT 0,
		ActivateBulkUpload TINYINT DEFAULT 0,
		BulkUploadQuantity INT(20) DEFAULT 0,
		BulkUploadMinQuantity INT(20) DEFAULT 0,
		ShowOnlyUsersVotes TINYINT DEFAULT 0,
		FbLikeGoToGalleryLink VARCHAR(1000) DEFAULT '',
		Version VARCHAR(20) DEFAULT 0,
		CheckIp TINYINT DEFAULT 1,
		CheckCookie TINYINT DEFAULT 0,
		CheckCookieAlertMessage VARCHAR(1000) DEFAULT '',
		SliderLook TINYINT DEFAULT 0,
		SliderLookOrder TINYINT DEFAULT 0,
		RegistryUserRole VARCHAR(1000) DEFAULT '',
		ContestStart TINYINT DEFAULT 0,
		ContestStartTime VARCHAR(100) DEFAULT '',
		MaxResICOon INT(1) DEFAULT 0,
		MaxResICOwidth INT(20) DEFAULT 0,
		MaxResICOheight INT(20) DEFAULT 0,
		ActivatePostMaxMBfile TINYINT DEFAULT 0,
		PostMaxMBfile INT(20) DEFAULT 0,
        VersionDecimal DECIMAL(65,2) DEFAULT 0,
		WpPageParent BIGINT(20) DEFAULT 0,
		WpPageParentUser BIGINT(20) DEFAULT 0,
		WpPageParentNoVoting BIGINT(20) DEFAULT 0,
		WpPageParentWinner BIGINT(20) DEFAULT 0
		) $charset_collate;"; // WordPress $charset_collate was added in 21.0.1
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            cg_echo_last_sql_error($isShowError,$lastError);
            $tableOptionsHasToBeCreated=true;
        }

        if($tableOptionsHasToBeCreated==false){

            // Anlegen der absolut notwendigen User Form Feldern (Username, E-Mail, Password und Confirm Password)

            $selectIDs = $wpdb->get_results( "SELECT id FROM $tablename_options" );

            $collectIDs = array();

            foreach ($selectIDs as $key => $value) {

                foreach ($value as $key => $value1) {
                    $collectIDs[]= $value1;
                }
            }
        }


        //URL VARCHAR(2000) erst ab Version 3.06 vorhanden
        if($wpdb->get_var("SHOW TABLES LIKE '$tablename_email_admin'") != $tablename_email_admin){
            $sql = "CREATE TABLE $tablename_email_admin (
		id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		GalleryID INT(20),
		Admin VARCHAR(200),
		AdminMail VARCHAR(200),
		Header VARCHAR(200),
		Reply VARCHAR(200),
		CC VARCHAR(200),
		BCC VARCHAR(200),
		URL TEXT,
		Content TEXT
		) $charset_collate;"; // WordPress $charset_collate was added in 21.0.1
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            cg_echo_last_sql_error($isShowError,$lastError);

            if($tableOptionsHasToBeCreated==false){

                // Determine email of blog admin and variables for email table
                $from = get_option('blogname');
                $reply = get_option('admin_email');
                $AdminMail = get_option('admin_email');
                $Header = 'A new file was published';
                $ContentAdminMail = 'Dear Admin<br/><br/>A new file was published<br/><br/>$info$';

                foreach ($collectIDs as $key => $value) {

                    $wpdb->query($wpdb->prepare(
                        "
                                INSERT INTO $tablename_email_admin
                                ( id, GalleryID, Admin, AdminMail,
                                Header,Reply,CC,
                                BCC,Url,Content)
                                VALUES ( %s,%d,%s,%s,
                                %s,%s,%s,
                                %s,%s,%s)
                            ",
                        '',$value,$from,$AdminMail,
                        $Header,'','',
                        '','',$ContentAdminMail
                    ));

                }

            }
        }

        if($wpdb->get_var("SHOW TABLES LIKE '$tablename_mail_user_upload'") != $tablename_mail_user_upload){
            $sql = "CREATE TABLE $tablename_mail_user_upload (
		id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		GalleryID INT (11) DEFAULT 0,
		InformUserUpload TINYINT DEFAULT 0,
		Header TEXT DEFAULT '',
		Subject TEXT DEFAULT '',
		Reply TEXT DEFAULT '',
		CC TEXT DEFAULT '',
		BCC TEXT DEFAULT '',
		Content TEXT DEFAULT '',
		ContentInfoWithoutFileSource TINYINT DEFAULT 0
		) $charset_collate;"; // WordPress $charset_collate was added in 21.0.1
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            cg_echo_last_sql_error($isShowError,$lastError);

            if($tableOptionsHasToBeCreated==false){

                // Determine email of blog admin and variables for email table
                $from = get_option('blogname');
                $reply = get_option('admin_email');
                $Subject = 'Your entry was successful';
                $ContentAdminMail = 'Dear Sir or Madam<br/><br/>Your entry was published<br/><br/>$info$';

                foreach ($collectIDs as $key => $value) {

                    $wpdb->query($wpdb->prepare(
                        "
                                INSERT INTO $tablename_mail_user_upload
                                ( id, GalleryID, InformUserUpload, Header, 
                                Subject,Reply,CC,
                                BCC,Content,ContentInfoWithoutFileSource)
                                VALUES ( %s,%d,%d,%s,
                                %s,%s,%s,
                                %s,%s,%d)
                            ",
                        '',$value,0,$from,
                        $Subject,$reply,'',
                        '',$ContentAdminMail,0
                    ));

                }

            }
        }

        if($wpdb->get_var("SHOW TABLES LIKE '$tablename_mail_user_comment'") != $tablename_mail_user_comment){
            $sql = "CREATE TABLE $tablename_mail_user_comment (
		id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		GalleryID INT (11) DEFAULT 0,
		InformUserComment TINYINT DEFAULT 0,
		Header TEXT DEFAULT '',
		Subject TEXT DEFAULT '',
		Reply TEXT DEFAULT '',
		CC TEXT DEFAULT '',
		BCC TEXT DEFAULT '',
		Content TEXT DEFAULT '',
		URL TEXT DEFAULT '',
		MailInterval VARCHAR(30) DEFAULT ''
		) $charset_collate;"; // WordPress $charset_collate was added in 21.0.1
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            cg_echo_last_sql_error($isShowError,$lastError);

            if($tableOptionsHasToBeCreated==false){

                // Determine email of blog admin and variables for email table
                $from = get_option('blogname');
                $reply = get_option('admin_email');
                $Subject = 'Your file(s) were commented';
                $Content = 'Dear Sir or Madam<br/><br/>Your file(s) were commented<br/><br/>$info$';

                foreach ($collectIDs as $key => $value) {

                    $wpdb->query($wpdb->prepare(
                        "
                                INSERT INTO $tablename_mail_user_comment
                                ( id, GalleryID, InformUserComment, Header, 
                                Subject,Reply,CC,
                                BCC,Content,URL,MailInterval)
                                VALUES ( %s,%d,%s,%s,
                                %s,%s,%s,
                                %s,%s,%s,%s)
                            ",
                        '',$value,0,$from,
                        $Subject,$reply,'',
                        '',$Content,'','24h'
                    ));

                }

            }
        }

        if($wpdb->get_var("SHOW TABLES LIKE '$tablename_mail_user_vote'") != $tablename_mail_user_vote){
            $sql = "CREATE TABLE $tablename_mail_user_vote (
		id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		GalleryID INT (11) DEFAULT 0,
		InformUserVote TINYINT DEFAULT 0,
		Header TEXT DEFAULT '',
		Subject TEXT DEFAULT '',
		Reply TEXT DEFAULT '',
		CC TEXT DEFAULT '',
		BCC TEXT DEFAULT '',
		Content TEXT DEFAULT '',
		URL TEXT DEFAULT '',
		MailInterval VARCHAR(30) DEFAULT ''
		) $charset_collate;"; // WordPress $charset_collate was added in 21.0.1
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            cg_echo_last_sql_error($isShowError,$lastError);

            if($tableOptionsHasToBeCreated==false){

                // Determine email of blog admin and variables for email table
                $from = get_option('blogname');
                $reply = get_option('admin_email');
                $Subject = 'Your file(s) were voted';
                $Content = 'Dear Sir or Madam<br/><br/>Your file(s) were voted<br/><br/><br/>$info$';

                foreach ($collectIDs as $key => $value) {

                    $wpdb->query($wpdb->prepare(
                        "
                                INSERT INTO $tablename_mail_user_vote
                                ( id, GalleryID, InformUserVote, Header, 
                                Subject,Reply,CC,
                                BCC,Content,URL,MailInterval)
                                VALUES ( %s,%d,%d,%s,
                                %s,%s,%s,
                                %s,%s,%s,%s)
                            ",
                        '',$value,0,$from,
                        $Subject,$reply,'',
                        '',$Content,'','24h'
                    ));

                }

            }
        }

        if($wpdb->get_var("SHOW TABLES LIKE '$tablename_user_comment_mails'") != $tablename_user_comment_mails){
            $sql = "CREATE TABLE $tablename_user_comment_mails (
		id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		GalleryID INT (11) DEFAULT 0,
		Tstamp INT (11) DEFAULT 0,
		WpUserId INT (11) DEFAULT 0,
		Content TEXT DEFAULT ''
		) $charset_collate;"; // WordPress $charset_collate was added in 21.0.1
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            cg_echo_last_sql_error($isShowError,$lastError);
        }

        if($wpdb->get_var("SHOW TABLES LIKE '$tablename_user_vote_mails'") != $tablename_user_vote_mails){
            $sql = "CREATE TABLE $tablename_user_vote_mails (
		id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		GalleryID INT (11) DEFAULT 0,
		Tstamp INT (11) DEFAULT 0,
		WpUserId INT (11) DEFAULT 0,
		Content TEXT DEFAULT ''
		) $charset_collate;"; // WordPress $charset_collate was added in 21.0.1
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            cg_echo_last_sql_error($isShowError,$lastError);
        }

        // created 14.08.2021
        // VARCHAR(1000) fields are max-length="200" in frontend because other fields of this type are VARCHAR(200) with max-length:200 also
        // Might be required VARCHAR(1000) with max-length="1000" in future everywhere
        if($wpdb->get_var("SHOW TABLES LIKE '$tablename_comments_notification_options'") != $tablename_comments_notification_options){
            $sql = "CREATE TABLE $tablename_comments_notification_options (
		id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		GalleryID INT (11),
		CommNoteAddressor TEXT,
		CommNoteAdminMail TEXT,
		CommNoteCC TEXT,
		CommNoteBCC TEXT,
		CommNoteReply TEXT,
		CommNoteSubject TEXT,
		CommNoteContent TEXT
		) $charset_collate;"; // WordPress $charset_collate was added in 21.0.1
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            cg_echo_last_sql_error($isShowError,$lastError);

            if($tableOptionsHasToBeCreated==false){
                // will be created in edit option options if required
            }

        }


        if($wpdb->get_var("SHOW TABLES LIKE '$tablename_options_visual'") != $tablename_options_visual){
            //IF(SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = "$tablename_options_visual" LIMIT 1){
            $sql = "CREATE TABLE $tablename_options_visual(
		id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		GalleryID INT(20),
		CommentsAlignGallery VARCHAR(20) DEFAULT '',
		RatingAlignGallery VARCHAR(20) DEFAULT '',
		Field1IdGalleryView INT(20),
		Field1AlignGalleryView VARCHAR(20) DEFAULT '',
		Field2IdGalleryView INT(20),
		Field2AlignGalleryView VARCHAR(20) DEFAULT '',
		Field3IdGalleryView INT(20),
		Field3AlignGalleryView VARCHAR(20) DEFAULT '',
		ThumbViewBorderWidth INT(20),
		ThumbViewBorderRadius INT(20),		
		ThumbViewBorderColor VARCHAR(20) DEFAULT '',
		ThumbViewBorderOpacity VARCHAR(20) DEFAULT '',
		HeightViewBorderWidth INT(20),
		HeightViewBorderRadius INT(20),
		HeightViewBorderColor VARCHAR(20) DEFAULT '',
		HeightViewBorderOpacity VARCHAR(20) DEFAULT '',
		HeightViewSpaceWidth INT(20),
		HeightViewSpaceHeight INT(20),
		RowViewBorderWidth INT(20),
		RowViewBorderRadius INT(20),
		RowViewBorderColor VARCHAR(20) DEFAULT '',
		RowViewBorderOpacity VARCHAR(20) DEFAULT '',
		RowViewSpaceWidth INT(20),
		RowViewSpaceHeight INT(20),
		TitlePositionGallery TINYINT,
		RatingPositionGallery TINYINT,
		CommentPositionGallery TINYINT,
		ActivateGalleryBackgroundColor TINYINT,
		GalleryBackgroundColor VARCHAR(20) DEFAULT '',
		GalleryBackgroundOpacity VARCHAR(20) DEFAULT '',
		FormRoundBorder INT(11),
		FormBorderColor VARCHAR(256) DEFAULT '',
		FormButtonColor VARCHAR(256) DEFAULT '',
		FormButtonWidth INT(11),
		FormInputWidth INT(11),
        OriginalSourceLinkInSlider TINYINT,
        PreviewInSlider TINYINT,
        FeControlsStyle VARCHAR(20) DEFAULT '',
        AllowSortOptions VARCHAR(256) DEFAULT '',
        GalleryStyle VARCHAR(256) DEFAULT '',
		BlogLook TINYINT DEFAULT 0,
		BlogLookOrder TINYINT DEFAULT 0,
		BlogLookFullWindow TINYINT DEFAULT 0,
		ImageViewFullWindow TINYINT DEFAULT 0,
		ImageViewFullScreen TINYINT DEFAULT 0,
		SliderThumbNav TINYINT DEFAULT 0,
		BorderRadius TINYINT DEFAULT 0,
		CopyImageLink TINYINT DEFAULT 0,
		CommentsDateFormat VARCHAR(20) DEFAULT '',
		FeControlsStyleUpload VARCHAR(20) DEFAULT '',
		FeControlsStyleRegistry VARCHAR(20) DEFAULT '',
		FeControlsStyleLogin VARCHAR(20) DEFAULT '',
		BorderRadiusUpload TINYINT DEFAULT 0,
		BorderRadiusRegistry TINYINT DEFAULT 0,
		BorderRadiusLogin TINYINT DEFAULT 0,
		ThankVote TINYINT DEFAULT 0,
		GeneralID TINYINT DEFAULT 0,
		CopyOriginalFileLink TINYINT DEFAULT 0,
		ForwardOriginalFile TINYINT DEFAULT 0,
        ShareButtons TEXT DEFAULT '',
        TextBeforeWpPageEntry TEXT DEFAULT '',
        TextAfterWpPageEntry TEXT DEFAULT '',
        ForwardToWpPageEntry TINYINT DEFAULT 0,
        ForwardToWpPageEntryInNewTab TINYINT DEFAULT 0,
        ShowBackToGalleryButton TINYINT DEFAULT 0,
        BackToGalleryButtonText TEXT DEFAULT '',
        TextDeactivatedEntry TEXT DEFAULT ''
		) $charset_collate;"; // WordPress $charset_collate was added in 21.0.1


            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            cg_echo_last_sql_error($isShowError,$lastError);

            if($tableOptionsHasToBeCreated==false){

                foreach ($collectIDs as $key => $value) {

                    $wpdb->query( $wpdb->prepare(
                        "
							INSERT INTO $tablename_options_visual
								( id, GalleryID, CommentsAlignGallery, RatingAlignGallery,
								Field1IdGalleryView,Field1AlignGalleryView,Field2IdGalleryView,Field2AlignGalleryView,Field3IdGalleryView,Field3AlignGalleryView,
								ThumbViewBorderWidth,ThumbViewBorderRadius,ThumbViewBorderColor,ThumbViewBorderOpacity,HeightViewBorderWidth,HeightViewBorderRadius,HeightViewBorderColor,HeightViewBorderOpacity,HeightViewSpaceWidth,HeightViewSpaceHeight,
								RowViewBorderWidth,RowViewBorderRadius,RowViewBorderColor,RowViewBorderOpacity,RowViewSpaceWidth,RowViewSpaceHeight,TitlePositionGallery,RatingPositionGallery,CommentPositionGallery,
								ActivateGalleryBackgroundColor,GalleryBackgroundColor,GalleryBackgroundOpacity,OriginalSourceLinkInSlider,PreviewInSlider,
								FeControlsStyle,AllowSortOptions,GalleryStyle,
								BlogLook,BlogLookOrder,BlogLookFullWindow,
								ImageViewFullWindow,ImageViewFullScreen,
								SliderThumbNav,BorderRadius,
								CopyImageLink,CommentsDateFormat,
								FeControlsStyleUpload,FeControlsStyleRegistry,FeControlsStyleLogin,
								 BorderRadiusUpload,BorderRadiusRegistry,BorderRadiusLogin,ThankVote,GeneralID,
								 CopyOriginalFileLink,ForwardOriginalFile,ShareButtons,
								 TextBeforeWpPageEntry,TextAfterWpPageEntry,ForwardToWpPageEntry,ForwardToWpPageEntryInNewTab,
								 ShowBackToGalleryButton,BackToGalleryButtonText,TextDeactivatedEntry
								 )
								VALUES ( %s,%d,%s,%s,
								%s,%s,%s,%s,%s,%s,
								%d,%d,%s,%d,%d,%d,%s,%d,%d,%d,
								%d,%d,%s,%d,%d,%d,%d,%d,%d,%d,%s,%d,%d,%d,
								%s,%s,%s,
								%d,%d,%d,
								%d,%d,
								%d,%d,
								%d,%s,
								%s,%s,%s,
								%d,%d,%d,%d,%d,
								%d,%d,%s,
								%s,%s,%d,%d,
							    %d,%s,%s
								)
							",
                        '',$value,'left','left',
                        '','left','','left','','left',
                        0,0,'#000000',1,0,0,'#000000',1,0,0,
                        0,0,'#000000',1,0,0,1,1,1,0,'#000000',1,1,1,
                        'white','date-desc,date-asc,rate-desc,rate-asc,rate-average-desc,rate-average-asc,comment-desc,comment-asc,random','center-white',
                        0,0,0,
                        1,1,
                        1,1,
                        1,'YYYY-MM-DD',
                        'white','white','white',
                        1,1,1,1,0,
                        1,1,'',
                        '','',0,0,
                        1,'',''
                    ) );

                }

            }

        }

        //if($wpdb->get_var('SHOW TABLES LIKE ' . $tablename_options_visual) == $tablename_options_visual){}

        if($wpdb->get_var("SHOW TABLES LIKE '$tablename_options_input'") != $tablename_options_input){
            $sql = "CREATE TABLE $tablename_options_input(
		id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		GalleryID INT(20),
		Forward TINYINT,
		Forward_URL VARCHAR(999),
		Confirmation_Text TEXT,
		ShowFormAfterUpload TINYINT DEFAULT 0
		) $charset_collate;"; // WordPress $charset_collate was added in 21.0.1
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            cg_echo_last_sql_error($isShowError,$lastError);
        }


        if($wpdb->get_var("SHOW TABLES LIKE '$tablename_entries'") != $tablename_entries){
            $sql = "CREATE TABLE $tablename_entries (
		id BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		pid INT(20),
		f_input_id INT(20),
		GalleryID INT(20),
		Field_Type VARCHAR(10),
		Field_Order INT(3),
		Short_Text VARCHAR(999),
		Long_Text TEXT,
		ConfMailId INT(20) DEFAULT 0,
		Checked TINYINT DEFAULT 0,
		InputDate DateTime DEFAULT '0000-00-00 00:00:00',
		Tstamp INT(11) DEFAULT 0
		) $charset_collate;"; // WordPress $charset_collate was added in 21.0.1
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            cg_echo_last_sql_error($isShowError,$lastError);
        }

        cg_create_contest_gallery_user_role();

            if($wpdb->get_var("SHOW TABLES LIKE '$tablename_pro_options'") != $tablename_pro_options){
                $sql = "CREATE TABLE $tablename_pro_options (
			id BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			GalleryID INT(20),
			ForwardAfterRegUrl VARCHAR(999),
			ForwardAfterRegText TEXT,
			ForwardAfterLoginUrlCheck TINYINT,
			ForwardAfterLoginUrl VARCHAR(999),
			ForwardAfterLoginTextCheck TINYINT,
			ForwardAfterLoginText TEXT,
			TextEmailConfirmation TEXT,
			TextAfterEmailConfirmation TEXT,
			RegMailAddressor VARCHAR(200),
			RegMailReply VARCHAR(200),
			RegMailSubject VARCHAR(200),
			RegUserUploadOnly TINYINT,
			RegUserUploadOnlyText TEXT,
			Manipulate TINYINT DEFAULT 1,
			ShowOther TINYINT DEFAULT 1,
			CatWidget TINYINT DEFAULT 1,
			Search TINYINT DEFAULT 1,
			GalleryUpload TINYINT DEFAULT 0,
			GalleryUploadTextBefore TEXT,
			GalleryUploadTextAfter TEXT,
			GalleryUploadConfirmationText TEXT,
			ShowNickname TINYINT DEFAULT 0,
			MinusVote TINYINT DEFAULT 0,
			SlideTransition VARCHAR(20) DEFAULT 'translateX',
			VotesInTime TINYINT DEFAULT 0,
			VotesInTimeQuantity INT(11) DEFAULT 1,
			VotesInTimeIntervalReadable VARCHAR(40) DEFAULT '24:00',
			VotesInTimeIntervalSeconds INT(20) DEFAULT 86400,
			VotesInTimeIntervalAlertMessage VARCHAR(200)  DEFAULT '',
			ShowExif TINYINT DEFAULT 0,
			SliderFullWindow TINYINT DEFAULT 0,
            HideRegFormAfterLogin TINYINT,
            HideRegFormAfterLoginShowTextInstead TINYINT,
            HideRegFormAfterLoginTextToShow VARCHAR(1000) DEFAULT '',
            RegUserGalleryOnly TINYINT,
			RegUserGalleryOnlyText TEXT,
			RegUserMaxUpload INT(11) DEFAULT 0,
			RegUserMaxUploadPerCategory INT(11) DEFAULT 0,
			IsModernFiveStar TINYINT DEFAULT 0,
			GalleryUploadOnlyUser TINYINT DEFAULT 0,
			FbLikeNoShare TINYINT DEFAULT 0,
			FbLikeOnlyShare TINYINT DEFAULT 0,
			VoteNotOwnImage TINYINT DEFAULT 0,
			PreselectSort VARCHAR(30) DEFAULT '',
			UploadRequiresCookieMessage VARCHAR(1000) DEFAULT '',
			ShowCatsUnchecked TINYINT DEFAULT 0,
			ShowCatsUnfolded TINYINT DEFAULT 0,
			RegMailOptional TINYINT DEFAULT 0,
			CustomImageName TINYINT DEFAULT 0,
			CustomImageNamePath VARCHAR(200) DEFAULT '',
			DeleteFromStorageIfDeletedInFrontend TINYINT DEFAULT 0,
			VotePerCategory TINYINT DEFAULT 0,
			VotesPerCategory INT(11) DEFAULT 0,
			VoteMessageSuccessActive TINYINT DEFAULT 0,
			VoteMessageWarningActive TINYINT DEFAULT 0,
			VoteMessageSuccessText TEXT,
			VoteMessageWarningText TEXT,
			CommNoteActive TINYINT DEFAULT 0,
			GeneralID TINYINT DEFAULT 0,
			ShowProfileImage TINYINT DEFAULT 0,
			AllowUploadJPG TINYINT DEFAULT 0,
			AllowUploadPNG TINYINT DEFAULT 0,
			AllowUploadGIF TINYINT DEFAULT 0,
			AllowUploadICO TINYINT DEFAULT 0,
			AdditionalFiles TINYINT DEFAULT 0,
			AdditionalFilesCount TINYINT DEFAULT 0,
			ReviewComm TINYINT DEFAULT 0,
            BackToGalleryButtonURL TEXT DEFAULT '',
            WpPageParentRedirectURL TEXT DEFAULT '',
            RedirectURLdeletedEntry TEXT DEFAULT '',
            InformAdminAllowActivateDeactivate TINYINT DEFAULT 0,
            InformAdminActivationURL TEXT DEFAULT ''
			) $charset_collate;"; // WordPress $charset_collate was added in 21.0.1
                require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                dbDelta($sql);
                cg_echo_last_sql_error($isShowError,$lastError);


                if($tableOptionsHasToBeCreated==false){


                    $ForwardAfterRegText = <<<HEREDOC
Thank you for your registration<br/>Check your email account to confirm your email and complete the registration. If you don't see any message then plz check also the spam folder.
HEREDOC;
                    $ForwardAfterLoginText = 'You are now logged in. Have fun with contest.';
                    $TextEmailConfirmation = 'Thank you for your registration by clicking on the link below: <br/><br/> $regurl$';
                    $TextAfterEmailConfirmation = 'Thank you for your registration. You are now able to login and to take part on the contest.';
                    $RegUserUploadOnlyText = 'You have to be registered and logged in to to add an entry.';
                    $RegUserGalleryOnly = 0;
                    $RegUserGalleryOnlyText = 'You have to be registered and logged in to see the gallery.';

                    // Determine email of blog admin and variables for email table
                    $RegMailAddressor = trim(get_option('blogname'));
                    $RegMailReply = trim(get_option('admin_email'));
                    $RegMailSubject = 'Please confirm your registration';

                    $CatWidget = 0;
                    $Search = 1;
                    $GalleryUpload = 1;
                    $ShowExif = 0;
                    $RegUserMaxUpload = 0;
                    $IsModernFiveStar = 0;

                    // input options
                    $GalleryUploadTextBefore = "<h2>Welcome to the contest</h2><p>Do your entry to be a part of the contest</p>";
                    $GalleryUploadTextBefore = htmlentities($GalleryUploadTextBefore, ENT_QUOTES);
                    $GalleryUploadTextAfter = '';

                    $GalleryUploadConfirmationText = "<p>Your entry was successful.<br/>We will activate your file soon.<br/>Your file has to be approved.</p>";
                    $GalleryUploadConfirmationText = htmlentities($GalleryUploadConfirmationText, ENT_QUOTES);

                    $ShowNickname = 0;
                    $MinusVote = 0;
                    $SlideTransition = 'translateX';

                    $VotesInTime = 0;
                    $VotesInTimeQuantity = 1;
                    $VotesInTimeIntervalReadable = '24:00';
                    $VotesInTimeIntervalSeconds = 86400;
                    $VotesInTimeIntervalAlertMessage = "You can vote only 1 time per day";

                    $HideRegFormAfterLogin = 0;
                    $HideRegFormAfterLoginShowTextInstead = 0;
                    $HideRegFormAfterLoginTextToShow = '';
                    $GalleryUploadOnlyUser = 0;
                    $FbLikeNoShare = 0;
                    $FbLikeOnlyShare = 0;
                    $VoteNotOwnImage = 0;
                    $PreselectSort = '';
                    $UploadRequiresCookieMessage = 'Please allow cookies to upload';
                    $ShowCatsUnchecked = 1;
                    $ShowCatsUnfolded = 1;
                    $RegMailOptional = 0;

                    $CustomImageName = 0;
                    $CustomImageNamePath = '';

                    $DeleteFromStorageIfDeletedInFrontend = 0;

                    $VotePerCategory = 0;
                    $VotesPerCategory = 0;

                    $VoteMessageSuccessActive  = 0;
                    $VoteMessageWarningActive  = 0;

                    $VoteMessageSuccessText = '';
                    $VoteMessageWarningText = '';
                    $CommNoteActive = 0;
                    $GeneralID = 0;
                    $ShowProfileImage = 0;
/*                    $BulkUploadType = 1;
                    $UploadFormAppearance = 0;*/

                    // can be generally set to 1. PNG and GIF are simply deactivated in PRO
                    $AllowUploadJPG = 1;
                    $AllowUploadPNG = 1;
                    $AllowUploadGIF = 1;
                    $AllowUploadICO = 1;

                    $AdditionalFiles = 0;
                    $AdditionalFilesCount = 1;
                    $BackToGalleryButtonURL = '';
                    $WpPageParentRedirectURL = '';
                    $RedirectURLdeletedEntry = '';

                    foreach ($collectIDs as $key => $value) {
                        $wpdb->query( $wpdb->prepare(
                            "
                                INSERT INTO $tablename_pro_options
                                ( id, GalleryID, ForwardAfterRegUrl, ForwardAfterRegText,
                                ForwardAfterLoginUrlCheck,ForwardAfterLoginUrl,
                                ForwardAfterLoginTextCheck,ForwardAfterLoginText,
                                TextEmailConfirmation,TextAfterEmailConfirmation,
                                RegMailAddressor,RegMailReply,RegMailSubject,RegUserUploadOnly,RegUserUploadOnlyText,
                                Manipulate,ShowOther,CatWidget,Search,
                                GalleryUpload,GalleryUploadTextBefore,GalleryUploadTextAfter,GalleryUploadConfirmationText,ShowNickname,MinusVote,SlideTransition,
                                VotesInTime,VotesInTimeQuantity,VotesInTimeIntervalReadable,VotesInTimeIntervalSeconds,VotesInTimeIntervalAlertMessage,ShowExif,
								HideRegFormAfterLogin,HideRegFormAfterLoginShowTextInstead,HideRegFormAfterLoginTextToShow,
								RegUserGalleryOnly,RegUserGalleryOnlyText,RegUserMaxUpload,IsModernFiveStar,GalleryUploadOnlyUser,FbLikeNoShare,VoteNotOwnImage,PreselectSort,
								UploadRequiresCookieMessage,ShowCatsUnchecked,ShowCatsUnfolded,RegMailOptional,
								CustomImageName,CustomImageNamePath,FbLikeOnlyShare,DeleteFromStorageIfDeletedInFrontend,VotePerCategory,VotesPerCategory,
                                VoteMessageSuccessActive,VoteMessageWarningActive,VoteMessageSuccessText,VoteMessageWarningText,
                                CommNoteActive,GeneralID,ShowProfileImage,
                                AllowUploadJPG,AllowUploadPNG,AllowUploadGIF,AllowUploadICO,
                                AdditionalFiles,AdditionalFilesCount,BackToGalleryButtonURL,WpPageParentRedirectURL,RedirectURLdeletedEntry,
                                 InformAdminAllowActivateDeactivate, InformAdminActivationURL
                                )
                                VALUES (%s,%d,%s,%s,
                                %d,%s,
                                %d,%s,
                                %s,%s,
                                %s,%s,%s,%d,%s,
                                %d,%d,%d,%d,
                                %d,%s,%s,%s,%d,%d,%s,
                                %d,%d,%s,%d,%s,%d,
                                %d,%d,%s,
                                %d,%s,%d,%d,
                                %d,%d,%d,%s,
                                %s,%d,%d,
                                %d,%s,%d,%d,%d,%d,%d,
                                %d,%d,%s,%s,
                                %d,%d,%d,
                                %d,%d,%d,%d,
                                %d,%d,%s,%s,%s,
                                %d,%s
                                )
                            ",
                            '',$value,'',$ForwardAfterRegText,
                            0,'',
                            0,$ForwardAfterLoginText,
                            $TextEmailConfirmation,$TextAfterEmailConfirmation,
                            $RegMailAddressor,$RegMailReply,$RegMailSubject,0,$RegUserUploadOnlyText,0,1,$CatWidget,$Search,
                            $GalleryUpload,$GalleryUploadTextBefore,$GalleryUploadTextAfter,$GalleryUploadConfirmationText,$ShowNickname,$MinusVote,$SlideTransition,
                            $VotesInTime,$VotesInTimeQuantity,$VotesInTimeIntervalReadable,$VotesInTimeIntervalSeconds,$VotesInTimeIntervalAlertMessage,$ShowExif,
                            $HideRegFormAfterLogin,$HideRegFormAfterLoginShowTextInstead,$HideRegFormAfterLoginTextToShow,
                            $RegUserGalleryOnly,$RegUserGalleryOnlyText,$RegUserMaxUpload,$IsModernFiveStar,
                            $GalleryUploadOnlyUser,$FbLikeNoShare,$VoteNotOwnImage,$PreselectSort,
                            $UploadRequiresCookieMessage,$ShowCatsUnchecked,$ShowCatsUnfolded,$RegMailOptional,
                            $CustomImageName,$CustomImageNamePath,$FbLikeOnlyShare,$DeleteFromStorageIfDeletedInFrontend,$VotePerCategory,$VotesPerCategory,
                            $VoteMessageSuccessActive,$VoteMessageWarningActive,$VoteMessageSuccessText,$VoteMessageWarningText,
                            $CommNoteActive,$GeneralID,$ShowProfileImage,
                            $AllowUploadJPG,$AllowUploadPNG,$AllowUploadGIF,$AllowUploadICO,
                            $AdditionalFiles,$AdditionalFilesCount,$BackToGalleryButtonURL,$WpPageParentRedirectURL,'',
                            0,''
                        ) );
                    }
                }
            }

            if($wpdb->get_var("SHOW TABLES LIKE '$tablename_create_user_entries'") != $tablename_create_user_entries){
                $sql = "CREATE TABLE $tablename_create_user_entries (
			id BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			GalleryID INT(20) DEFAULT 0,
			wp_user_id INT(20) DEFAULT 0,
			f_input_id INT(20) DEFAULT 0,
			Field_Type VARCHAR(100) DEFAULT '',
			Field_Content TEXT DEFAULT '',
			activation_key VARCHAR(200) DEFAULT '',
			Checked TINYINT DEFAULT 0,
			Version VARCHAR(20) DEFAULT '',
			GeneralID TINYINT DEFAULT 0,
            Tstamp INT (11) DEFAULT 0
			) $charset_collate;"; // WordPress $charset_collate was added in 21.0.1
                require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                dbDelta($sql);
                cg_echo_last_sql_error($isShowError,$lastError);
            }

            if($wpdb->get_var("SHOW TABLES LIKE '$tablename_create_user_form'") != $tablename_create_user_form){
                $sql = "CREATE TABLE $tablename_create_user_form (
			id BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			GalleryID INT(20),
			Field_Type VARCHAR(100),
			Field_Order INT(3),
			Field_Name VARCHAR(200),
			Field_Content TEXT,
			Min_Char VARCHAR(200),
			Max_Char VARCHAR(200),
			Required TINYINT,
			Active TINYINT DEFAULT 1,
            ReCaKey VARCHAR(200) DEFAULT '',
            ReCaLang VARCHAR(20) DEFAULT '',
            GeneralID TINYINT DEFAULT 0
			) $charset_collate;"; // WordPress $charset_collate was added in 21.0.1
                require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                dbDelta($sql);
                cg_echo_last_sql_error($isShowError,$lastError);

                if($tableOptionsHasToBeCreated==false){

                    foreach ($collectIDs as $key => $value) {

                        $wpdb->query( $wpdb->prepare(
                            "
                                INSERT INTO $tablename_create_user_form
                                ( id, GalleryID, Field_Type, Field_Order,
                                Field_Name,Field_Content,Min_Char,Max_Char,
                                Required,Active)
                                VALUES ( %s,%d,%s,%s,
                                %s,%s,%d,%d,
                                %d,%d)
                            ",
                            '',$value,'main-user-name','1',
                            'Username','',3,100,
                            1,1
                        ) );

                        $wpdb->query( $wpdb->prepare(
                            "
                                INSERT INTO $tablename_create_user_form
                                ( id, GalleryID, Field_Type, Field_Order,
                                Field_Name,Field_Content,Min_Char,Max_Char,
                                Required,Active)
                                VALUES ( %s,%d,%s,%s,
                                %s,%s,%d,%d,
                                %d,%d)
                            ",
                            '',$value,'main-mail','2',
                            'E-mail','','','',
                            1,1
                        ) );

                        $wpdb->query( $wpdb->prepare(
                            "
                                INSERT INTO $tablename_create_user_form
                                ( id, GalleryID, Field_Type, Field_Order,
                                Field_Name,Field_Content,Min_Char,Max_Char,
                                Required,Active)
                                VALUES ( %s,%d,%s,%s,
                                %s,%s,%d,%d,
                                %d,%d)
                            ",
                            '',$value,'password','3',
                            'Password','',6,100,
                            1,1
                        ) );

                        $wpdb->query( $wpdb->prepare(
                            "
                                INSERT INTO $tablename_create_user_form
                                ( id, GalleryID, Field_Type, Field_Order,
                                Field_Name,Field_Content,Min_Char,Max_Char,
                                Required,Active)
                                VALUES ( %s,%d,%s,%s,
                                %s,%s,%d,%d,
                                %d,%d)
                            ",
                            '',$value,'password-confirm','4',
                            'Confirm Password','',6,100,
                            1,1
                        ) );


                    }

                    // Anlegen der absolut notwendigen User Form Feldern (Username, E-Mail, Password und Confirm Password) --- ENDE


                }
            }



        if($wpdb->get_var("SHOW TABLES LIKE '$tablename_form_input'") != $tablename_form_input){
            $sql = "CREATE TABLE $tablename_form_input (
		id BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		GalleryID INT(20),
		Field_Type VARCHAR(10),
		Field_Order INT(3),
		Field_Content TEXT,
		Show_Slider TINYINT DEFAULT 0,
		Use_as_URL TINYINT DEFAULT 0,
		Active TINYINT DEFAULT 1,
		ReCaKey VARCHAR(200) DEFAULT '',
		ReCaLang VARCHAR(20) DEFAULT '',
		Version VARCHAR(20) DEFAULT '',
		WatermarkPosition VARCHAR(99) DEFAULT '',
		IsForWpPageTitle TINYINT DEFAULT 0,
		IsForWpPageDescription TINYINT DEFAULT 0,
		SubTitle TINYINT DEFAULT 0,
		ThirdTitle TINYINT DEFAULT 0,
        WpAttachmentDetailsType VARCHAR(20) DEFAULT ''
		) $charset_collate;"; // WordPress $charset_collate was added in 21.0.1
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            cg_echo_last_sql_error($isShowError,$lastError);
        }


        if($wpdb->get_var("SHOW TABLES LIKE '$tablename_form_output'") != $tablename_form_output){
            $sql = "CREATE TABLE $tablename_form_output (
		id BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		f_input_id INT(20),
		GalleryID INT(20),
		Field_Type VARCHAR(10),
		Field_Order INT(3),
		Field_Content TEXT
		) $charset_collate;"; // WordPress $charset_collate was added in 21.0.1
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            cg_echo_last_sql_error($isShowError,$lastError);
        }


        if($wpdb->get_var("SHOW TABLES LIKE '$tablename_mail_confirmation'") != $tablename_mail_confirmation){
            $sql = "CREATE TABLE $tablename_mail_confirmation (
		id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		GalleryID INT(20),
		Admin VARCHAR(200),
		Header VARCHAR(200),
		Reply VARCHAR(200),
		CC VARCHAR(200),
		BCC VARCHAR(200),
		Content TEXT,
		SendConfirm TINYINT,
		ConfirmationText TEXT,
		URL VARCHAR(999)
		) $charset_collate;"; // WordPress $charset_collate was added in 21.0.1
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            cg_echo_last_sql_error($isShowError,$lastError);

            if($tableOptionsHasToBeCreated==false){

                // Determine email of blog admin and variables for email table
                $from = get_option('blogname');
                $reply = get_option('admin_email');
                $HeaderConfirmationMail = 'Please confirm your e-mail address';
                $ContentConfirmationMail = 'Dear Sir or Madam<br/>Please confirm your e-mail address to take part on photo contest<br/><br/><b>$url$</b>';
                $ConfirmationTextConfirmationMail = 'Thank you for confirming your e-mail address.';

                foreach ($collectIDs as $key => $value) {

                    $wpdb->query($wpdb->prepare(
                        "
                        INSERT INTO $tablename_mail_confirmation
                        ( id, GalleryID, Admin,
                        Header,Reply,CC,
                        BCC,Content,SendConfirm,
                        ConfirmationText,URL)
                        VALUES ( %s,%d,%s,
                        %s,%s,%s,
                        %s,%s,%d,
                        %s,%s)
                    ",
                        '',$value,$from,
                        $HeaderConfirmationMail,$reply,$reply,
                        $reply,$ContentConfirmationMail,0,
                        $ConfirmationTextConfirmationMail,''
                    ));

                }
            }



        }

        /*
                if($wpdb->get_var("SHOW TABLES LIKE '$tablename_mail_gallery'") != $tablename_mail_gallery){
                    $sql = "CREATE TABLE $tablename_mail_gallery (
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                GalleryID INT (11),
                Admin VARCHAR(200),
                Header VARCHAR(200),
                Reply VARCHAR(200),
                CC VARCHAR(200),
                BCC VARCHAR(200),
                Content TEXT,
                Blacklist TEXT,
                SendToImageOff TINYINT,
                SendToNotConfirmedUsers TINYINT NULL DEFAULT 1
                ) $charset_collate;"; // WordPress $charset_collate was added in 21.0.1
                    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                    dbDelta($sql);

                    if($tableOptionsHasToBeCreated==false){

                        // Determine email of blog admin and variables for email table
                        $from = get_option('blogname');
                        $reply = get_option('admin_email');

                        foreach ($collectIDs as $key => $value) {

                            $wpdb->query($wpdb->prepare(
                                "
                                    INSERT INTO $tablename_mail_gallery
                                    ( id, GalleryID, Admin,
                                    Header,Reply,CC,
                                    BCC,Content,
                                    Blacklist,SendToImageOff,
                                    SendToNotConfirmedUsers)
                                    VALUES ( %s,%d,%s,
                                    %s,%s,%s,
                                    %s,%s,
                                    %s,%d,
                                    %d)
                                ",
                                '',$value,$from,
                                '',$reply,$reply,
                                $reply,'',
                                '',0,
                                1
                            ));

                        }
                    }

                }



                if($wpdb->get_var("SHOW TABLES LIKE '$tablename_mail_gallery_users_history'") != $tablename_mail_gallery_users_history){
                    $sql = "CREATE TABLE $tablename_mail_gallery_users_history (
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                GalleryID INT (11),
                Timestamp INT (11),
                Date VARCHAR(100),
                Content TEXT
                ) $charset_collate;"; // WordPress $charset_collate was added in 21.0.1
                    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                    dbDelta($sql);
                }

                if($wpdb->get_var("SHOW TABLES LIKE '$tablename_mails_users_relations'") != $tablename_mails_users_relations){
                    $sql = "CREATE TABLE $tablename_mails_users_relations (
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                WpMailUserID INT (11),
                CgMailUserID INT (11),
                CgSendedMailID INT(11)
                ) $charset_collate;"; // WordPress $charset_collate was added in 21.0.1
                    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                    dbDelta($sql);
                }

        */

        if($wpdb->get_var("SHOW TABLES LIKE '$tablename_mails_collected'") != $tablename_mails_collected){
            $sql = "CREATE TABLE $tablename_mails_collected (
		id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		GalleryID INT(20),
		Mail VARCHAR (200),
		Hash VARCHAR (100),
		Confirmed TINYINT,
		Timestamp INT(11),		
		Link VARCHAR(1000)
		) $charset_collate;"; // WordPress $charset_collate was added in 21.0.1
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            cg_echo_last_sql_error($isShowError,$lastError);
        }

        if($wpdb->get_var("SHOW TABLES LIKE '$tablename_registry_and_login_options'") != $tablename_registry_and_login_options){
            $sql = "CREATE TABLE $tablename_registry_and_login_options (
		id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		GalleryID INT(20) DEFAULT 0,
		GeneralID TINYINT DEFAULT 0,
        LogoutLink VARCHAR(1000) DEFAULT '',
        BackToGalleryLink VARCHAR(1000) DEFAULT '',
        RegistryUserRole VARCHAR(1000) DEFAULT '',
        LostPasswordMailActive TINYINT DEFAULT 0,
        LostPasswordMailAddressor VARCHAR(200) DEFAULT '',
        LostPasswordMailReply VARCHAR(200) DEFAULT '',
        LostPasswordMailSubject VARCHAR(200) DEFAULT '',
        LostPasswordMailConfirmation TEXT DEFAULT '',
        TextBeforeLoginForm TEXT DEFAULT '',
        EditProfileGroups TEXT DEFAULT '',
        TextBeforeRegFormBeforeLoggedIn TEXT DEFAULT '',
        PermanentTextWhenLoggedIn TEXT DEFAULT ''
		) $charset_collate;"; // WordPress $charset_collate was added in 21.0.1
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            cg_echo_last_sql_error($isShowError,$lastError);
        }

        if($wpdb->get_var("SHOW TABLES LIKE '$tablename_google_options'") != $tablename_google_options){
            $sql = "CREATE TABLE $tablename_google_options (
    		id BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            GeneralID TINYINT DEFAULT 0,
            ClientId VARCHAR(1000) DEFAULT '',
            ButtonTextOnLoad VARCHAR(1000) DEFAULT '',
            ButtonStyle VARCHAR(100) DEFAULT '',
            BorderRadius TINYINT DEFAULT 0,
		    FeControlsStyle VARCHAR(20) DEFAULT '',
		    TextBeforeGoogleSignInButton TEXT DEFAULT ''
            ) $charset_collate;"; // WordPress $charset_collate was added in 21.0.1
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            cg_echo_last_sql_error($isShowError,$lastError);
        }

        // best way to do it right here, when first time a gallery will be created or db version will be checked
        cg_create_google_options($i);

        if($wpdb->get_var("SHOW TABLES LIKE '$tablename_google_users'") != $tablename_google_users){
            $sql = "CREATE TABLE $tablename_google_users (
            id BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            GoogleId VARCHAR(1000) DEFAULT '',
            Email VARCHAR(1000) DEFAULT '',
            NickName VARCHAR(1000) DEFAULT '',
            GivenName VARCHAR(1000) DEFAULT '',
            FamilyName VARCHAR(1000) DEFAULT '',
            ImageUrl VARCHAR(1000) DEFAULT '',
            WpUserId INT (11) DEFAULT 0
            ) $charset_collate;"; // WordPress $charset_collate was added in 21.0.1
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            cg_echo_last_sql_error($isShowError,$lastError);
        }

        // in all found examples index name is another then colum name
        if($wpdb->get_var("SHOW TABLES LIKE '$tablename_wp_pages'") != $tablename_wp_pages){
            $sql = "CREATE TABLE $tablename_wp_pages (
            id BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            WpPage BIGINT(20) DEFAULT 0,
            INDEX WpPage_index (WpPage)
            ) $charset_collate;"; // WordPress $charset_collate was added in 21.0.1
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            cg_echo_last_sql_error($isShowError,$lastError);
        }

        // was never created, only for remembering
        // wp_term_taxonomy_id is the main table of wordpress for saving tag posts relations, there is also term_id saved for wp_terms
        // and most important the term_taxonomy_id is saved in wp_term_relationships where also wp_post ids are saved which can be  filtered
        // WordPress Pages area via get_query_var( 'tag__not_in' )
        /*if($wpdb->get_var("SHOW TABLES LIKE '$tablename_general'") != $tablename_general){
            $sql = "CREATE TABLE $tablename_general (
            id BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            GeneralID BIGINT(20) DEFAULT 0,
            CgpTagTermTaxonomyIds TEXT DEFAULT ''
            ) $charset_collate;"; // WordPress $charset_collate was added in 21.0.1
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            cg_echo_last_sql_error($isShowError,$lastError);
        }*/

        //ADD first first Gallery

        $uploads = wp_upload_dir();
        $checkUploads = $uploads['basedir'].'/contest-gallery';

        if(!is_dir($checkUploads)){
            mkdir($checkUploads,0755);
        }

        if(!is_dir($uploads['basedir'].'/contest-gallery/gallery-general')){
            mkdir($uploads['basedir'].'/contest-gallery/gallery-general',0755,true);
        }

        return $lastError;

    }
}
