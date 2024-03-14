<?php

global $wpdb;

$tablename = $wpdb->prefix . "contest_gal1ery";
$tablenameCategories = $wpdb->prefix . "contest_gal1ery_categories";
$tablenameOptions = $wpdb->prefix . "contest_gal1ery_options";
$tablename_options_input = $wpdb->prefix . "contest_gal1ery_options_input";
$tablename_options_visual = $wpdb->prefix . "contest_gal1ery_options_visual";
$tablenameMail = $wpdb->prefix . "contest_gal1ery_mail";
$tablename_mail_admin = $wpdb->prefix . "contest_gal1ery_mail_admin";
$tablename_mail_user_upload = $wpdb->prefix . "contest_gal1ery_mail_user_upload";
$tablename_mail_user_comment = $wpdb->prefix . "contest_gal1ery_mail_user_comment";
$tablename_mail_user_vote = $wpdb->prefix . "contest_gal1ery_mail_user_vote";
$tablename_form_input = $wpdb->prefix . "contest_gal1ery_f_input";
$tablename_form_output = $wpdb->prefix . "contest_gal1ery_f_output";
$tablename_create_user_form = $wpdb->prefix . "contest_gal1ery_create_user_form";
$tablename_pro_options = $wpdb->prefix . "contest_gal1ery_pro_options";
// $tablename_mail_gallery = $wpdb->prefix . "contest_gal1ery_mail_gallery";
$tablename_mail_confirmation = $wpdb->prefix . "contest_gal1ery_mail_confirmation";
$tablename_comments_notification_options = $wpdb->prefix . "contest_gal1ery_comments_notification_options";
$tablename_entries = $wpdb->prefix . "contest_gal1ery_entries";
$tablenameGoogleOptions = $wpdb->prefix . "contest_gal1ery_google_options";
$tablename_wp_pages = $wpdb->prefix . "contest_gal1ery_wp_pages";
$table_posts = $wpdb->prefix . "posts";

cg_check_if_database_tables_ok();
cg_check_if_upload_folder_permissions_ok();

$thumbSizesWp = array();
$thumbSizesWp['thumbnail_size_w'] = get_option("thumbnail_size_w");
$thumbSizesWp['medium_size_w'] = get_option("medium_size_w");
$thumbSizesWp['large_size_w'] = get_option("large_size_w");

$dbVersion = intval(get_option( "p_cgal1ery_db_version" ));// just for some old processing before 21.0.0
if(empty($dbVersion)){
    $dbVersion = cg_get_db_version();// just for some old processing before 21.0.0
}
$VersionForScripts = cg_get_version_for_scripts();// was cg_get_db_version before version 21.0.0
$VersionDecimal = floatval(cg_get_db_version());// new logic since 21.0.0

/* $wpdb->insert( $tablenameOptions, array( 'id' => '', 'GalleryName' => '', 'PicsPerSite' => 20, 'WidthThumb' => 200, 'HeightThumb' => 150, 'WidthGallery' => 600,
 'HeightGallery' => 400, 'DistancePics' => 100, 'DistancePicsV' => 50, 'MaxResJPGon' => 0, 'MaxResPNGon' => 0, 'MaxResGIFon' => 0,
 'MaxResJPG' => 25000000, 'MaxResPNG' => 25000000, 'MaxResGIF' => 25000000, 'ScaleOnly' => 1, 'ScaleAndCut' => 0, 'FullSize' => 1,
 'AllowSort' => 1, 'AllowComments' => 1, 'AllowRating' => 1, 'IpBlock' => 1, 'FbLike' => 1, 'AllowGalleryScript' => 0, 'Inform' => 0,
 'ThumbLook'=> 1, 'HeightLook'=> 1, 'RowLook'=> 1,
 'ThumbLookOrder'=> 1, 'HeightLookOrder'=> 2, 'RowLookOrder'=> 3,
 'HeightLookHeight'=> 300, 'ThumbsInRow'=> 4, 'PicsInRow'=> 4, 'LastRow'=> 0 ));*/

if(!empty($_POST['cg_create'])){

    $dbVersion = get_option( "p_cgal1ery_db_version" );// just for some old processing before 21.0.0
    $VersionForScripts = cg_get_version_for_scripts();// was cg_get_db_version before version 21.0.0
    $VersionDecimal = floatval(cg_get_db_version());// new logic since 21.0.0

    // input options
    $GalleryUploadTextBefore = "<h2>Welcome to the contest</h2><p>Do your entry to be a part of the contest</p>";
    $GalleryUploadTextBefore = htmlentities($GalleryUploadTextBefore, ENT_QUOTES);

    // input options
    $confirmation_text = '<p>Your entry was successful<br><br><br><b>Note for first time Contest Gallery user:</b>
<br/><br/>This text can be configurated in "Edit options" > "Contact options" > "Upload form shortcode configuration"<br/><br/>
"Automatically activate users files after frontend upload" can be activated/deactivated in "Edit options" >>> "Contact options"
</p>';
    $confirmation_text = htmlentities($confirmation_text, ENT_QUOTES);

    // NICHT LÖSCHEN!!!! $GalleryUploadConfirmationText wird in create-options nicht neu kreiert
    $GalleryUploadConfirmationText = '<p>Your entry was successful<br><br><br><b>Note for first time Contest Gallery user:</b>
<br/><br/>This text can be configurated in "Edit options" > "Contact options" > "In gallery contact form configuration"<br/><br/>
"Automatically activate users entries after successful frontend contact" can be activated/deactivated in "Edit options" >>> "Contact options"
</p>';
    $GalleryUploadConfirmationText = htmlentities($GalleryUploadConfirmationText, ENT_QUOTES);

    // pro options

    $ForwardAfterRegText = 'Thank you for your registration<br/>Check your email account to confirm your email and complete the registration. If you don\'t see any message then plz check also the spam folder.';
    $ForwardAfterLoginText = 'You are now logged in. Have fun with contest.';
    $TextEmailConfirmation = 'Complete your registration by clicking on the link below: <br/><br/> $regurl$';
    $TextAfterEmailConfirmation = 'Thank you for your registration. You are now able to login and to take part on the contest.';
    $RegUserUploadOnlyText = 'You have to be registered and logged in to add an entry.';
    // Determine email of blog admin and variables for email table
    $RegMailAddressor = trim(get_option('blogname'));
    $RegMailReply = get_option('admin_email');
    $RegMailSubject = 'Please complete your registration';

    include('json-values.php');

    include('create-options.php');

    //$nextIDgallery = $wpdb->get_var("SELECT MAX(id) FROM $tablenameOptions");
    $nextIDgallery = $wpdb->insert_id;

    // cg_gallery shortcode
    $array = [
        'post_title'=>'Contest Gallery ID '.$nextIDgallery,
        'post_type'=>'contest-gallery',
        'post_content'=>"<!-- wp:shortcode -->"."\r\n".
            "<!--This is a comment: cg_galley... shortcode is required to display Contest Gallery on a Contest Gallery Custom Post Type page. You can place your own content before and after this shortcode, whatever you like. You can place cg_gallery... shortcode also on any other of your pages.-->"."\r\n".
            "[cg_gallery id=\"$nextIDgallery\"]"."\r\n".
            "<!-- /wp:shortcode -->",
        'post_mime_type'=>'contest-gallery-plugin-page',
        'post_status'=>'publish',
    ];
    $WpPageParent = wp_insert_post($array);

    // cg_gallery_user shortcode
    $array = [
        'post_title'=>'Contest Gallery ID '.$nextIDgallery.' user',
        'post_type'=>'contest-gallery',
        'post_content'=>"<!-- wp:shortcode -->"."\r\n".
            "<!--This is a comment: cg_galley... shortcode is required to display Contest Gallery on a Contest Gallery Custom Post Type page. You can place your own content before and after this shortcode, whatever you like. You can place cg_gallery... shortcode also on any other of your pages.-->"."\r\n".
            "[cg_gallery_user id=\"$nextIDgallery\"]"."\r\n".
            "<!-- /wp:shortcode -->",
        'post_mime_type'=>'contest-gallery-plugin-page',
        'post_status'=>'publish',
    ];

    $WpPageParentUser = wp_insert_post($array);

    // cg_gallery_no_voting shortcode
    $array = [
        'post_title'=>'Contest Gallery ID '.$nextIDgallery.' no voting',
        'post_type'=>'contest-gallery',
        'post_content'=>"<!-- wp:shortcode -->"."\r\n".
            "<!--This is a comment: cg_galley... shortcode is required to display Contest Gallery on a Contest Gallery Custom Post Type page. You can place your own content before and after this shortcode, whatever you like. You can place cg_gallery... shortcode also on any other of your pages.-->"."\r\n".
            "[cg_gallery_no_voting id=\"$nextIDgallery\"]"."\r\n".
            "<!-- /wp:shortcode -->",
        'post_mime_type'=>'contest-gallery-plugin-page',
        'post_status'=>'publish',
    ];

    $WpPageParentNoVoting = wp_insert_post($array);

    // cg_gallery_winner shortcode
    $array = [
        'post_title'=>'Contest Gallery ID '.$nextIDgallery.' winner',
        'post_type'=>'contest-gallery',
        'post_content'=>"<!-- wp:shortcode -->"."\r\n".
            "<!--This is a comment: cg_galley... shortcode is required to display Contest Gallery on a Contest Gallery Custom Post Type page. You can place your own content before and after this shortcode, whatever you like. You can place cg_gallery... shortcode also on any other of your pages.-->"."\r\n".
            "[cg_gallery_winner id=\"$nextIDgallery\"]"."\r\n".
            "<!-- /wp:shortcode -->",
        'post_mime_type'=>'contest-gallery-plugin-page',
        'post_status'=>'publish',
    ];

    $WpPageParentWinner = wp_insert_post($array);

    $wpdb->update(
        "$tablenameOptions",
        array('WpPageParent' => $WpPageParent,'WpPageParentUser' => $WpPageParentUser,'WpPageParentNoVoting' => $WpPageParentNoVoting,'WpPageParentWinner' => $WpPageParentWinner),
        array('id' => $nextIDgallery),
        array('%d','%d','%d','%d'),
        array('%d')
    );

    $wpdb->query( $wpdb->prepare(
        "
				INSERT INTO $tablename_wp_pages
					( id,WpPage
					 )
					VALUES ( %s,%d
					)
				",
        '',$WpPageParent
    ) );

    $wpdb->query( $wpdb->prepare(
        "
				INSERT INTO $tablename_wp_pages
					( id,WpPage
					 )
					VALUES ( %s,%d
					)
				",
        '',$WpPageParentUser
    ) );

    $wpdb->query( $wpdb->prepare(
        "
				INSERT INTO $tablename_wp_pages
					( id,WpPage
					 )
					VALUES ( %s,%d
					)
				",
        '',$WpPageParentNoVoting
    ) );

    $wpdb->query( $wpdb->prepare(
        "
				INSERT INTO $tablename_wp_pages
					( id,WpPage
					 )
					VALUES ( %s,%d
					)
				",
        '',$WpPageParentWinner
    ) );

    $tag = get_term_by('slug', ' contest-gallery-plugin-tag','post_tag');
    if(empty($tag)){
        $tag = cg_create_contest_gallery_plugin_tag();
        $term_id = $tag['term_id'];
    }else{
        $term_id = $tag->term_id;
    }

    // Erschaffen eines Galerieordners

    $uploadFolder = wp_upload_dir();
    $galleryUpload = $uploadFolder['basedir'].'/contest-gallery/gallery-id-'.$nextIDgallery.'';
    $galleryJsonFolder = $uploadFolder['basedir'].'/contest-gallery/gallery-id-'.$nextIDgallery.'/json';
    $galleryJsonImagesFolder = $uploadFolder['basedir'].'/contest-gallery/gallery-id-'.$nextIDgallery.'/json/image-data';
    $galleryJsonInfoDir = $uploadFolder['basedir'].'/contest-gallery/gallery-id-'.$nextIDgallery.'/json/image-info';
    $galleryJsonCommentsDir = $uploadFolder['basedir'].'/contest-gallery/gallery-id-'.$nextIDgallery.'/json/image-comments';

    if(!is_dir($galleryUpload)){
        mkdir($galleryUpload,0755,true);
    }

    if(!is_dir($galleryJsonFolder)){
        mkdir($galleryJsonFolder,0755,true);
    }

    if(!is_dir($galleryJsonImagesFolder)){
        mkdir($galleryJsonImagesFolder,0755);
    }

    if(!is_dir($galleryJsonInfoDir)){
        mkdir($galleryJsonInfoDir,0755);
    }

    if(!is_dir($galleryJsonCommentsDir)){
        mkdir($galleryJsonCommentsDir,0755);
    }

    $galleryJsonFolderReadMeFile = $galleryJsonFolder.'/do not remove json files manually.txt';

    $fp = fopen($galleryJsonFolderReadMeFile,'w');
    fwrite($fp,'Removing json files manually will break functionality of your gallery');
    fclose($fp);

    $fp = fopen($galleryUpload.'/json/'.$nextIDgallery.'-categories.json', 'w');
    fwrite($fp, json_encode(array()));
    fclose($fp);

    $fp = fopen($galleryUpload.'/json/'.$nextIDgallery.'-form-upload.json', 'w');
    fwrite($fp, json_encode(array()));
    fclose($fp);

    $fp = fopen($galleryUpload.'/json/'.$nextIDgallery.'-images.json', 'w');
    fwrite($fp, json_encode(array()));
    fclose($fp);

    $fp = fopen($galleryUpload.'/json/'.$nextIDgallery.'-single-view-order.json', 'w');
    fwrite($fp, json_encode(array()));
    fclose($fp);

    $createdGallery = "true";

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
					BorderRadiusUpload,BorderRadiusRegistry,BorderRadiusLogin, ThankVote,
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
					%d,%d,%d,%d,
					%d,%d,%s,
					%s,%s,%d,%d,
					%d,%s,%s
					)
				",
        '',$nextIDgallery,$CommentsAlignGallery,$RatingAlignGallery,
        $Field1IdGalleryView,$Field1AlignGalleryView,$Field2IdGalleryView,$Field2AlignGalleryView,$Field3IdGalleryView,$Field3AlignGalleryView,
        $ThumbViewBorderWidth,$ThumbViewBorderRadius,$ThumbViewBorderColor,$ThumbViewBorderOpacity,$HeightViewBorderWidth,$HeightViewBorderRadius,'#000000',$HeightViewBorderOpacity,$HeightViewSpaceWidth,$HeightViewSpaceHeight,
        $RowViewBorderWidth,$RowViewBorderRadius,$RowViewBorderColor,$RowViewBorderOpacity,$RowViewSpaceWidth,$RowViewSpaceHeight,$TitlePositionGallery,$RatingPositionGallery,$CommentPositionGallery,$ActivateGalleryBackgroundColor,$GalleryBackgroundColor,$GalleryBackgroundOpacity,$OriginalSourceLinkInSlider,$PreviewInSlider,
        $FeControlsStyle,$AllowSortOptions,$GalleryStyle,
        $BlogLook,$BlogLookOrder,$BlogLookFullWindow,
        $ImageViewFullWindow,$ImageViewFullScreen,
        $SliderThumbNav,$BorderRadius,
        $CopyImageLink,$CommentsDateFormat,
        $FeControlsStyleUpload,$FeControlsStyleRegistry,$FeControlsStyleLogin,
        $BorderRadiusUpload,$BorderRadiusRegistry,$BorderRadiusLogin,$ThankVote,
        $CopyOriginalFileLink,$ForwardOriginalFile,$ShareButtons,
        $TextBeforeWpPageEntry,$TextAfterWpPageEntry,$ForwardToWpPageEntry,$ForwardToWpPageEntryInNewTab,
        $ShowBackToGalleryButton,$BackToGalleryButtonText,$TextDeactivatedEntry
    ) );

    // $wpdb->insert( $tablename_options_input, array( 'id' => '', 'Forward' => 0, 'Forward_URL' => '', 'Confirmation_Text' => "$confirmationText" ));

    $wpdb->query( $wpdb->prepare(
        "
				INSERT INTO $tablename_options_input
				( id, GalleryID, Forward, Forward_URL, Confirmation_Text)
				VALUES ( %s,%d,%d,
				%s,%s )
			",
        '',$nextIDgallery,$Forward,
        $Forward_URL,$confirmation_text
    ) );



    // Determine email of blog admin and variables for email table
    $from = get_option('blogname');
    $reply = get_option('admin_email');
    $AdminMail = get_option('admin_email');
    $Header = 'Your entry was published';
    $SubjectUserUpload = 'Your entry was successful';
    $HeaderActivationMail = 'Your entry was published';
    $HeaderAdminMail = 'New frontend entry';
    $Content = 'Dear Sir or Madam<br/>Your entry was published<br/><br/><b>$url$</b>';
    $ContentAdminMail = 'Dear Admin<br/><br/>there is a new frontend entry<br/><br/>$info$';
    $ContentUserMail = 'Dear Sir or Madam<br/><br/>Your entry was successful<br/><br/>$info$';

    /*$wpdb->insert( $tablenameMail, array( 'id' => '', 'GalleryID' => $nextIDgallery, 'Admin' => "$from",
        'Header' => "$Header", 'Reply' => "$reply", 'cc' => "$reply",
        'bcc' => "$reply", 'Url' => '', 'Content' => "$Content"));*/

    $wpdb->query($wpdb->prepare(
        "
				INSERT INTO $tablenameMail
				( id, GalleryID, Admin,
				Header,Reply,CC,
				BCC,Url,Content)
				VALUES ( %s,%d,%s,
				%s,%s,%s,
				%s,%s,%s)
			",
        '',$nextIDgallery,$from,
        $HeaderActivationMail,$reply,'',
        '','',$Content
    ));

    $wpdb->query($wpdb->prepare(
        "
				INSERT INTO $tablename_mail_admin
				( id, GalleryID, Admin, AdminMail,
				Header,Reply,CC,
				BCC,Url,Content)
				VALUES ( %s,%d,%s,%s,
				%s,%s,%s,
				%s,%s,%s)
			",
        '',$nextIDgallery,$from,$AdminMail,
        $HeaderAdminMail,'','',
        '','',$ContentAdminMail
    ));

    $wpdb->query($wpdb->prepare(
        "
				INSERT INTO $tablename_mail_user_upload
				( id, GalleryID, InformUserUpload, Header, 
				Subject,Reply,CC,
				BCC,Content,ContentInfoWithoutFileSource)
				VALUES ( %s,%d,%d,%s,
				%s,%s,%s,
				%s,%s,%s)
			",
        '',$nextIDgallery, 0, $from,
        $SubjectUserUpload,$reply,'',
        '',$ContentUserMail,0
    ));

    $SubjectCommentMail = 'Your file(s) were commented';
    $ContentCommentMail = 'Dear Sir or Madam<br/><br/>Your file(s) were commented<br/><br/>$info$';

    $wpdb->query($wpdb->prepare(
        "
				INSERT INTO $tablename_mail_user_comment
				( id, GalleryID, InformUserComment, Header, 
				Subject,Reply,CC,
				BCC,Content,URL,MailInterval)
				VALUES ( %s,%d,%d,%s,
				%s,%s,%s,
				%s,%s,%s,%s)
			",
        '',$nextIDgallery, 0, $from,
        $SubjectCommentMail,$reply,'',
        '',$ContentCommentMail,'','24h'
    ));

    $SubjectVoteMail = 'Your file(s) were voted';
    $ContentVoteMail = 'Dear Sir or Madam<br/><br/>Your file(s) were voted<br/><br/>$info$';

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
        '',$nextIDgallery, 0, $from,
        $SubjectVoteMail,$reply,'',
        '',$ContentVoteMail,'','24h'
    ));

    $HeaderConfirmationMail = 'Please confirm your e-mail address';
    $ContentConfirmationMail = 'Dear Sir or Madam<br/>Please confirm your e-mail address to take part on photo contest<br/><br/><b>$url$</b>';
    $ConfirmationTextConfirmationMail = 'Thank you for confirming your e-mail address.';

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
        '',$nextIDgallery,$from,
        $HeaderConfirmationMail,$reply,'',
        '',$ContentConfirmationMail,0,
        $ConfirmationTextConfirmationMail,''
    ));

    // create $tablename_comments_notification_options
    include(__DIR__ ."/../../update/update-entries-check/update-entries-comments-notification-options.php");

    // create $tablename_contact_options
    //include(__DIR__ ."/../../update/update-contact-options-check/update-contact-options-check.php");

    $fieldContent['titel']="File upload";

    $imageF = 'image-f';

    //$wpdb->insert( $tablename_form_input, array( 'id' => '', 'GalleryID' => $nextIDgallery, 'Field_Type' => 'image-f', "Field_Order" => 2, "Field_Content" => $fieldContent ) );

    $fieldContent['file-type-img'] = 'img';

    /**###NORMAL###**/
    $fieldContent['alternative-file-type-pdf'] = '';
    $fieldContent['alternative-file-type-zip'] = '';
    $fieldContent['alternative-file-type-txt'] = 'txt';
    $fieldContent['alternative-file-type-doc'] = 'doc';
    $fieldContent['alternative-file-type-docx'] = '';
    $fieldContent['alternative-file-type-xls'] = 'xls';
    $fieldContent['alternative-file-type-xlsx'] = '';
    $fieldContent['alternative-file-type-csv'] = 'csv';
    $fieldContent['alternative-file-type-mp3'] = '';
    $fieldContent['alternative-file-type-m4a'] = '';
    $fieldContent['alternative-file-type-ogg'] = '';
    $fieldContent['alternative-file-type-wav'] = '';
    $fieldContent['alternative-file-type-mp4'] = '';
    $fieldContent['alternative-file-type-mov'] = '';
    //$fieldContent['alternative-file-type-avi'] = '';
    $fieldContent['alternative-file-type-webm'] = '';
    //$fieldContent['alternative-file-type-wmv'] = '';
    $fieldContent['alternative-file-type-ppt'] = 'ppt';
    $fieldContent['alternative-file-type-pptx'] = '';
    /**###NORMAL-END###**/

    $fieldContent = serialize($fieldContent);

    $wpdb->query($wpdb->prepare(
        "
						INSERT INTO $tablename_form_input
						(id, GalleryID, Field_Type,
						Field_Order,Field_Content,Show_Slider,Use_as_URL,Active,ReCaKey,ReCaLang)
						VALUES ( %s,%d,%s,
						%d,%s,%d,%d,%d,%s,%s)
					",
        '',$nextIDgallery,$imageF,
        4,$fieldContent,0,0,1,'',''
    ));


    $imageIdFormInput = $wpdb->get_var( "SELECT id FROM $tablename_form_input WHERE GalleryID='$nextIDgallery' AND Field_Type='image-f' ");


    // comment field first, Form_Output will be also created later bottom

    $kfFieldsArray = array();
    // 1. Feldtitel
    $kfFieldsArray['titel']="Description";
    // 2. Feldinhalt
    $kfFieldsArray['content']='';
    // 3. Feldkriterium 1
    $kfFieldsArray['min-char']=3;
    // 4. Feldkriterium 2
    $kfFieldsArray['max-char']=1000;
    // 5. Felderfordernis + Eingabe in die Datenbank
    $kfFieldsArray['mandatory']="off";

    $kfFieldsArray = serialize($kfFieldsArray);

    // Zuerst Form Input kreiren
    $wpdb->query( $wpdb->prepare(
        "
							INSERT INTO $tablename_form_input
							( id, GalleryID, Field_Type, Field_Order, Field_Content,Show_Slider,Use_as_URL,Active,IsForWpPageDescription)
							VALUES ( %s,%d,%s,%d,%s,%d,%d,%d,%d )
						",
        '',$nextIDgallery,'comment-f',3,$kfFieldsArray,1,0,1,1
    ) );

    $commentIdFormInput = $wpdb->insert_id;

    // add input comment field then, Form_Output will be also created later bottom

    $nfFieldsArray = array();
    // 1. Feldtitel
    $nfFieldsArray['titel']="Title";
    // 2. Feldinhalt
    $nfFieldsArray['content']='';
    // 3. Feldkriterium 1
    $nfFieldsArray['min-char']=3;
    // 4. Feldkriterium 2
    $nfFieldsArray['max-char']=100;
    // 5. Felderfordernis + Eingabe in die Datenbank
    $nfFieldsArray['mandatory']="on";

    $nfFieldsArray = serialize($nfFieldsArray);

    // Zuerst Form Input kreiren
    $wpdb->query( $wpdb->prepare(
        "
							INSERT INTO $tablename_form_input
							( id, GalleryID, Field_Type, Field_Order, Field_Content,Show_Slider,Use_as_URL,Active,IsForWpPageTitle)
							VALUES ( %s,%d,%s,%d,%s,%d,%d,%d,%d )
						",
        '',$nextIDgallery,'text-f',2,$nfFieldsArray,1,0,1,1
    ) );

    $textIdFormInput = $wpdb->get_var( "SELECT id FROM $tablename_form_input WHERE GalleryID='$nextIDgallery' AND Field_Type='text-f' ");

    // Dann next ID hier einfügen zum anezgein in der Gallerie das Feld!!!!
    $wpdb->update(
        "$tablename_options_visual",
        array('Field1IdGalleryView' => $textIdFormInput),
        array('GalleryID' => $nextIDgallery),
        array('%d'),
        array('%d')
    );

    // Dann next ID hier einfügen zum anezgein in der Gallerie das Feld!!!!
    // since 21.2.4 not used as tagInGallery anymore
    /*$wpdb->update(
        "$tablename_options_visual",
        array('Field2IdGalleryView' => $textIdFormInput),
        array('GalleryID' => $nextIDgallery),
        array('%d'),
        array('%d')
    );*/

    // Dann next ID hier einfügen zum anezgein des alternative file type names in single view!!!!
    $wpdb->update(
        "$tablename_options_visual",
        array('Field3IdGalleryView' => $textIdFormInput),
        array('GalleryID' => $nextIDgallery),
        array('%d'),
        array('%d')
    );

    include('create-gallery-create-categories.php');

    do_action('cg_json_upload_form',$nextIDgallery);
    do_action('cg_json_single_view_order',$nextIDgallery);

    // Erschaffen von Form_Input --- ENDE


    // Erschaffen von Form_Output single pic

    $wpdb->query($wpdb->prepare(
        "
						INSERT INTO $tablename_form_output
						(id, f_input_id, GalleryID,
						Field_Type,Field_Order,Field_Content)
						VALUES ( %s,%d,%d,
						%s,%d,%s)
					",
        '',$textIdFormInput,$nextIDgallery,
        'text-f',1,'Title'
    ));

    $wpdb->query($wpdb->prepare(
        "
						INSERT INTO $tablename_form_output
						(id, f_input_id, GalleryID,
						Field_Type,Field_Order,Field_Content)
						VALUES ( %s,%d,%d,
						%s,%d,%s)
					",
        '',$commentIdFormInput,$nextIDgallery,
        'text-f',2,'Comment'
    ));

    $wpdb->query($wpdb->prepare(
        "
						INSERT INTO $tablename_form_output
						(id, f_input_id, GalleryID,
						Field_Type,Field_Order,Field_Content)
						VALUES ( %s,%d,%d,
						%s,%d,%s)
					",
        '',$imageIdFormInput,$nextIDgallery,
        'image-f',3,'File upload'
    ));

    // Erschaffen von Form_Output single pic --- ENDE


    $backToGalleryFile = $uploadFolder["basedir"]."/contest-gallery/gallery-id-$nextIDgallery/backtogalleryurl.js";
    $FbLikeGoToGalleryLink = 'backToGalleryUrl="";';
    $fp = fopen($backToGalleryFile, 'w');
    fwrite($fp, $FbLikeGoToGalleryLink);
    fclose($fp);

        // Kreieren PRO options

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
                    VotesInTime,VotesInTimeQuantity,VotesInTimeIntervalReadable,VotesInTimeIntervalSeconds,VotesInTimeIntervalAlertMessage,ShowExif,SliderFullWindow,
                    HideRegFormAfterLogin,HideRegFormAfterLoginShowTextInstead,HideRegFormAfterLoginTextToShow,
					RegUserGalleryOnly,RegUserGalleryOnlyText,RegUserMaxUpload,IsModernFiveStar,
					GalleryUploadOnlyUser,FbLikeNoShare,VoteNotOwnImage,PreselectSort,
					UploadRequiresCookieMessage,ShowCatsUnchecked,ShowCatsUnfolded,RegMailOptional,FbLikeOnlyShare,
					DeleteFromStorageIfDeletedInFrontend,VotePerCategory,VotesPerCategory,
					VoteMessageSuccessActive,VoteMessageWarningActive,VoteMessageSuccessText,VoteMessageWarningText,
					CommNoteActive,ShowProfileImage,
					AllowUploadJPG,AllowUploadPNG,AllowUploadGIF,AllowUploadICO,
					AdditionalFiles,AdditionalFilesCount,ReviewComm,BackToGalleryButtonURL,WpPageParentRedirectURL,RedirectURLdeletedEntry,RegUserMaxUploadPerCategory
					)
					VALUES (%s,%d,%s,%s,
					%d,%s,
					%d,%s,
					%s,%s,
					%s,%s,%s,%d,%s,
					%d,%d,%d,%d,
					%d,%s,%s,%s,%d,%d,%s,
                    %d,%d,%s,%d,%s,%d,%d,
                    %d,%d,%s,
                    %d,%s,%d,%d,
                    %d,%d,%d,%s,
                    %s,%d,%d,%d,%d,
                    %d,%d,%d,
                    %d,%d,%s,%s,
                    %d,%d,
                    %d,%d,%d,%d,
                    %d,%d,%d,%s,%s,%s,%s
					)
				",
            '',$nextIDgallery,$ForwardAfterRegUrl,$ForwardAfterRegText,
            $ForwardAfterLoginUrlCheck,$ForwardAfterLoginUrl,
            $ForwardAfterLoginTextCheck,$ForwardAfterLoginText,
            $TextEmailConfirmation,$TextAfterEmailConfirmation,
            $RegMailAddressor,$RegMailReply,$RegMailSubject,$RegUserUploadOnly,$RegUserUploadOnlyText,
            $Manipulate,$ShowOther,$CatWidget,$Search,
            $GalleryUpload,$GalleryUploadTextBefore,$GalleryUploadTextAfter,$GalleryUploadConfirmationText,$ShowNickname,$MinusVote,$SlideTransition,
            $VotesInTime,$VotesInTimeQuantity,$VotesInTimeIntervalReadable,$VotesInTimeIntervalSeconds,$VotesInTimeIntervalAlertMessage,$ShowExif,$SliderFullWindow,
            $HideRegFormAfterLogin,$HideRegFormAfterLoginShowTextInstead,$HideRegFormAfterLoginTextToShow,
            $RegUserGalleryOnly,$RegUserGalleryOnlyText,$RegUserMaxUpload,$IsModernFiveStar,
            $GalleryUploadOnlyUser,$FbLikeNoShare,$VoteNotOwnImage,$PreselectSort,
            $UploadRequiresCookieMessage,$ShowCatsUnchecked,$ShowCatsUnfolded,$RegMailOptional,$FbLikeOnlyShare,
            $DeleteFromStorageIfDeletedInFrontend,$VotePerCategory,$VotesPerCategory,
            $VoteMessageSuccessActive,$VoteMessageWarningActive,$VoteMessageSuccessText,$VoteMessageWarningText,
            $CommNoteActive,$ShowProfileImage,
            $AllowUploadJPG,$AllowUploadPNG,$AllowUploadGIF,$AllowUploadICO,
            $AdditionalFiles,$AdditionalFilesCount,$ReviewComm,$BackToGalleryButtonURL,$WpPageParentRedirectURL,$RedirectURLdeletedEntry,$RegUserMaxUploadPerCategory
        ) );

        // Create a registry form and options for all galleries since 14.0.0

        cg_create_general_registration_form_v14();
        cg_create_registry_and_login_options_v14();

        // Create a registry form and options  for all galleries since 14.0.0 --- END

        $isNewGallery = true;

        include('options/json-options.php');

        $jsonOptions['visual']['Field1IdGalleryView'] = $textIdFormInput;
        $jsonOptions['visual']['Field2IdGalleryView'] = 0;
        $jsonOptions['visual']['Field3IdGalleryView'] = $textIdFormInput;
        $jsonOptions['visual']['IsForWpPageTitleID'] = $textIdFormInput;
        $jsonOptions['visual']['IsForWpPageDescriptionID'] = $commentIdFormInput;

        $fp = fopen($galleryUpload.'/json/'.$nextIDgallery.'-images.json', 'w');
        fwrite($fp, json_encode([]));// so at least a ressource exists for later
        fclose($fp);

        $fp = fopen($galleryUpload.'/json/'.$nextIDgallery.'-options.json', 'w');
        fwrite($fp, json_encode($jsonOptions));
        fclose($fp);

        $tstampJson = array();
        $fp = fopen($galleryUpload.'/json/'.$nextIDgallery.'-gallery-tstamp.json', 'w');
        fwrite($fp, json_encode(time()));
        fclose($fp);

        $tstampJson = array();
        $fp = fopen($galleryUpload.'/json/'.$nextIDgallery.'-gallery-sort-values-tstamp.json', 'w');
        fwrite($fp, json_encode(time()));
        fclose($fp);

        $tstampJson = array();
        $fp = fopen($galleryUpload.'/json/'.$nextIDgallery.'-images-sort-values.json', 'w');
        fwrite($fp, json_encode(array()));
        fclose($fp);// !important otherwise gallery will not load if there are no images

        $tstampJson = array();
        $fp = fopen($galleryUpload.'/json/'.$nextIDgallery.'-gallery-image-info-tstamp.json', 'w');
        fwrite($fp, json_encode(time()));
        fclose($fp);

        $tstampJson = array();
        $fp = fopen($galleryUpload.'/json/'.$nextIDgallery.'-images-info-values.json', 'w');
        fwrite($fp, json_encode(array()));
        fclose($fp);// !important otherwise gallery will not load if there are no images

        // empty translations file
        $translations = array();
        $fp = fopen($galleryUpload.'/json/'.$nextIDgallery.'-translations.json', 'w');
        fwrite($fp, json_encode($translations));
        fclose($fp);

        // Kreieren PRO options --- ENDE

    // create cg-switched file for sure

    // do not remove p_cgal1ery_pro_version_main_key, has to be done for each gallery
    $p_cgal1ery_pro_version_main_key = cg_get_blog_option( 1,"p_cgal1ery_pro_version_main_key");
    if(!empty($p_cgal1ery_pro_version_main_key)){
        if(strpos($p_cgal1ery_pro_version_main_key, 'v2')===0){
            $fp = fopen($galleryUpload.'/json/cg-switched.txt', 'w');
            fwrite($fp, time());
            fclose($fp);
         }
      }

    $isNewGalleryCreated = true;

    echo "<br class='cg-created-new-gallery-br'>";
    echo "<div id='cgCreatedNewGallery' class='cg-created-new-gallery'>";
    echo "<h2>You created a new gallery</h2>";
    echo "</div>";
    echo "<br class='cg-created-new-gallery-br'>";

}

if(isset($_POST['cg_copy'])){

    include('copy-gallery.php');

}

?>