<?php
global $post;
global $wpdb;
$tablename = $wpdb->prefix . "contest_gal1ery";
$tablename_options = $wpdb->prefix . "contest_gal1ery_options";
$tablename_wp_pages = $wpdb->prefix . "contest_gal1ery_wp_pages";
$tablename_form_input = $wpdb->prefix . "contest_gal1ery_f_input";
$tablenameentries = $wpdb->prefix . "contest_gal1ery_entries";
global $isCgParentPage;
global $cgWpPageParent;
global $cgId;
global $cgOptionsArray;
global $cgGalleryIDuser;
$blogname = get_option('blogname');
$postId = $post->ID;
$permalink = get_permalink($postId);
/*
    $isParentPage = false;
    if(empty($postParent)){
        $isParentPage = true;
        $postParent = $post->ID;
        $WpPageParent = $wpdb->get_var( "SELECT id FROM $tablename_wp_pages WHERE WpPage = $postParent LIMIT 1" );
    }else{
        $WpPageParent = $wpdb->get_var( "SELECT id FROM $tablename_wp_pages WHERE WpPage = $postParent LIMIT 1" );
    }*/
if(!empty($cgWpPageParent)){
    $options = $wpdb->get_row( "SELECT id,WpPageParent,WpPageParentUser,WpPageParentNoVoting,WpPageParentWinner FROM $tablename_options WHERE WpPageParent = $cgWpPageParent OR WpPageParentUser = $cgWpPageParent OR WpPageParentNoVoting = $cgWpPageParent OR WpPageParentWinner = $cgWpPageParent LIMIT 1" );
    $cgGalleryID = $options->id;
    $shortCodeType = 'cg_gallery';
    if($options->WpPageParentUser==$cgWpPageParent){
        $shortCodeType = 'cg_gallery_user';
    }else if($options->WpPageParentNoVoting==$cgWpPageParent){
        $shortCodeType = 'cg_gallery_no_voting';
    }else if($options->WpPageParentWinner==$cgWpPageParent){
        $shortCodeType = 'cg_gallery_winner';
    }
    if(!empty($isCgParentPage)){
        global $cgGalleryID;
        $cgGalleryID = $options->id;
        global $cgShortCodeType;
        $cgShortCodeType = $shortCodeType;
    }else{
        if(!empty($cgGalleryID) && !empty($cgId)){
            $rowObject = $wpdb->get_row( "SELECT * FROM $tablename WHERE id = $cgId" );
            global $cgGalleryID;
            $cgGalleryID = $rowObject->GalleryID;
            global $cgEntryId;
            $cgEntryId = $cgId;
            global $cgShortCodeType;
            $cgShortCodeType = $shortCodeType;

            $post_title = $post->post_title;
            $post_excerpt = $post->post_excerpt;
            $ImgType = $rowObject->ImgType;

            $WpPageTitle = '';
            $IsForWpPageTitleInputId = $wpdb->get_var("SELECT id FROM $tablename_form_input WHERE GalleryID = '$cgGalleryID' AND IsForWpPageTitle=1");
            if(!empty($IsForWpPageTitleInputId)){
                $ShortText = $wpdb->get_var("SELECT Short_Text FROM $tablenameentries WHERE pid = '$cgId' AND f_input_id=$IsForWpPageTitleInputId");
                if(!empty($ShortText)){
                    $WpPageTitle = contest_gal1ery_convert_for_html_output_without_nl2br($ShortText);
                }
            }

            $WpPageDescription = '';
            $IsForWpPageDescriptionInputId = $wpdb->get_var("SELECT id FROM $tablename_form_input WHERE GalleryID = '$cgGalleryID' AND IsForWpPageDescription=1");
            if(!empty($IsForWpPageDescriptionInputId)){
                $entry = $wpdb->get_row("SELECT Long_Text, Short_Text, Field_Type FROM $tablenameentries WHERE pid = '$cgId' AND f_input_id=$IsForWpPageDescriptionInputId");
                if(!empty($entry) && $entry->Field_Type=='text-f'){
                    $WpPageDescription = contest_gal1ery_convert_for_html_output_without_nl2br($entry->Short_Text);
                }else if(!empty($entry) && $entry->Field_Type=='comment-f'){
                    $WpPageDescription = contest_gal1ery_convert_for_html_output($entry->Long_Text);
                }
            }

            $IsForWpPageDescription = $wpdb->get_var("SELECT IsForWpPageDescription FROM $tablename_form_input WHERE id = '$cgId'");

            if($IsForWpPageDescription==1){$checkedIsForWpPageDescription='checked';}
            else{$checkedIsForWpPageDescription='';}

        }
    }

}

//echo "<pre>";
//print_r($cgOptionsArray);
//echo "</pre>";
//die;

$options = (!empty($cgOptionsArray[$cgGalleryIDuser])) ? $cgOptionsArray[$cgGalleryIDuser] : $cgOptionsArray;

$additionalCss = '';
if(!empty($cgEntryId)){
    if(isset($options['visual']['AdditionalCssEntryLandingPage'])){// only json option, might be not set if options were never saved before
        $additionalCss = $options['visual']['AdditionalCssEntryLandingPage'];
    }
}else{
    if(isset($options['visual']['AdditionalCssGalleryPage'])){// only json option, might be not set if options were never saved before
        $additionalCss = $options['visual']['AdditionalCssGalleryPage'];
    }
}

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="UTF-8">
    <meta name="title" content="<?php
    if(!empty($WpPageTitle)){
        echo strip_tags($WpPageTitle);
    }else{
        echo strip_tags(get_option('blogname'));
    }
    ?>">
    <meta name="description" content="<?php
    if(!empty($WpPageDescription)){
        echo strip_tags ($WpPageDescription);
    }else{
        echo strip_tags (get_option('blogdescription'));
    }
    ?>">
    <?php

    if($shortCodeType == 'cg_gallery_user'){
        echo '<meta name="robots" content="noindex, nofollow">'."\r\n";
    }

    if(class_exists( 'QM_Plugin' )){
        ?>
        <script type='text/javascript' src='<?php echo get_bloginfo('wpurl'); ?>/wp-includes/js/jquery/jquery.min.js?ver=3.6.1' id='jquery-core-js'></script>
        <script type='text/javascript' src='<?php echo get_bloginfo('wpurl'); ?>/wp-includes/js/jquery/jquery-migrate.min.js?ver=3.3.2' id='jquery-migrate-js'></script>
        <link rel='stylesheet' id='query-monitor-css' href='<?php echo get_bloginfo('wpurl'); ?>/wp-content/plugins/query-monitor/assets/query-monitor.css?ver=1673467028' type='text/css' media='all' />
        <script type='text/javascript' src='<?php echo get_bloginfo('wpurl'); ?>/wp-content/plugins/query-monitor/assets/query-monitor.js?ver=1673467028' id='query-monitor-js'></script>
        <?php
    }
    ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    wp_enqueue_script('jquery');// will appear in footer so or so, because of wp_footer
    wp_enqueue_style( 'cg_entry_landing_page_style', plugins_url('/../v10/v10-css/cg_entry_landing_page_style.css', __FILE__), false, cg_get_version_for_scripts() );

    echo '<meta property="og:url" content="'.$permalink.'">'."\r\n";
    echo '<meta property="og:site_name" content="'.$blogname.'">'."\r\n";

    if(!empty($WpPageTitle)){
        echo '<meta property="og:title" content="'.strip_tags($WpPageTitle).'">'."\r\n";
    }else{
        echo '<meta property="og:title" content="'.strip_tags($post->post_title).'">'."\r\n";
    }

    if(!empty($WpPageDescription)){
        echo '<meta property="og:description" content="'.strip_tags($WpPageDescription).'">'."\r\n";
    }

    if(!empty($cgEntryId) AND $ImgType!='con' && !empty($rowObject) && $rowObject->Active==1){
	    $realId = $rowObject->id;
	    $WpUpload = $rowObject->WpUpload;

        if(cg_is_is_image($ImgType)){
            $imgSrcLarge=wp_get_attachment_image_src($rowObject->WpUpload, 'large');
            echo '<meta property="og:image" content="'.$imgSrcLarge[0].'">'."\r\n";
        }else if(cg_is_alternative_file_type_video($ImgType)){
            $fileData = wp_get_attachment_metadata($rowObject->WpUpload);
            $videoHeight = (!empty($fileData['height'])) ? $fileData['height'] : 0;
            $videoWidth = (!empty($fileData['width'])) ? $fileData['width'] : 0;
            if(!empty($rowObject->guid)){
            $guid = $rowObject->guid;
            echo '<meta property="og:video" content="'.$guid.'?t=0.001">'."\r\n";
            echo '<meta property="og:video:type" content="'.$fileData['mime_type'].'">'."\r\n";
	            echo '<meta property="og:width" content="'.$videoWidth.'">'."\r\n";
	            echo '<meta property="og:height" content="'.$videoHeight.'">'."\r\n";
            }
        } else {
            $imgToShow = '';
            include (__DIR__.'/../base64-file-types-data.php');
            if($ImgType=='pdf'){$imgToShow = $pdf; }
            else if($ImgType=='zip'){$imgToShow = $zip;}
            else if($ImgType=='txt'){$imgToShow = $txt;}
            else if($ImgType=='doc'){$imgToShow = $doc;}
            else if($ImgType=='docx'){$imgToShow = $docx;}
            else if($ImgType=='xls'){$imgToShow = $xls;}
            else if($ImgType=='xlsx'){$imgToShow = $xlsx;}
            else if($ImgType=='csv'){$imgToShow = $csv;}
            else if($ImgType=='mp3'){$imgToShow = $mp3;}
            else if($ImgType=='m4a'){$imgToShow = $m4a;}
            else if($ImgType=='ogg'){$imgToShow = $ogg;}
            else if($ImgType=='wav'){$imgToShow = $wav;}
            else if($ImgType=='ppt'){$imgToShow = $ppt;}
            else if($ImgType=='pptx'){$imgToShow = $pptx;}
            echo '<meta property="og:image" content="'.$imgToShow.'">'."\r\n";
        }
    }

    if(!empty($additionalCss)){
        echo "<style>";
            echo cg_stripslashes_recursively(str_replace("&nbsp;", '', htmlentities($additionalCss)));
        echo "</style>";
    }

    echo "<style>";
    echo "#wp-admin-bar-site-editor {display: none !important;}";
    echo "</style>";

    $is_loggedn_in = 'false';
    if(is_user_logged_in()){
        $is_loggedn_in = 'true';
    }

    ?>
</head>
<body id="body" data-cg-is-logged-in=<?php echo json_encode($is_loggedn_in); ?> >
<div id="mainCGdivEntryPageContainer">
    <?php
    //the_title(); // for later integration
    the_content();
    wp_footer();
    ?>
</div>
</body>
</html>