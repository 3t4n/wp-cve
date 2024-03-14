<?php

global $wpdb;
$tablename_comments = $wpdb->prefix . "contest_gal1ery_comments";
$tablename = $wpdb->prefix . "contest_gal1ery";
$tablenameWpUsers = $wpdb->prefix . "users";
$tablenameOptions = $wpdb->prefix . "contest_gal1ery_options";
$tablename_pro_options = $wpdb->prefix . "contest_gal1ery_pro_options";

$table_posts = $wpdb->prefix."posts";
$table_wp_users = $wpdb->base_prefix."users";

$galeryNR=$_GET['option_id'];
$pid=0;

if(!empty($_GET['id'])){
    $pid=$_GET['id'];
}

$GalleryID = $galeryNR;

$cgOptions = $wpdb->get_row("SELECT GalleryName, Version FROM $tablenameOptions WHERE id = '$galeryNR'");
$GalleryName = $cgOptions->GalleryName;
$Version = $cgOptions->Version;

$proOptions = $wpdb->get_row("SELECT * FROM $tablename_pro_options WHERE GalleryID = '$GalleryID'");
$IsModernFiveStar = (!empty($proOptions->IsModernFiveStar)) ? true : false;

if(empty($GalleryName)){
    $GalleryName = 'Contest Gallery';
}
include(__DIR__."/../nav-menu.php");
include(__DIR__.'/../../../vars/general/emojis.php');

$wp_upload_dir = wp_upload_dir();
$dirImageComments = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galeryNR.'/json/image-comments/ids/'.$pid;

// SQL zum Ermitteln von allen Komments mit gesendeter picture id
// DATEN Löschen und exit

	if (!empty($_POST['delete-comment']) || !empty($_POST['activate-comment']) || !empty($_POST['deactivate-comment'])) {

			//$deleteQuery = 'DELETE FROM ' . $tablename_comments . ' WHERE';

            $fileImageCommentsDir = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galeryNR.'/json/image-comments/ids/'.$pid;
            $jsonFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galeryNR.'/json/image-comments/image-comments-'.$pid.'.json';
            $fp = fopen($jsonFile, 'r');
            $commentsArray = json_decode(fread($fp, filesize($jsonFile)),true);
            fclose($fp);

            $countCtoReview = 0;

            // save comments for future repair eventually
            if(is_dir($dirImageComments)){
                $dirImageCommentsFiles = glob($dirImageComments.'/*.json');
                foreach ($dirImageCommentsFiles as $dirImageCommentsFile){
                    $dirImageCommentsFileData = json_decode(file_get_contents($dirImageCommentsFile),true);
                    if(!empty($dirImageCommentsFileData[key($dirImageCommentsFileData)]['Active']) && $dirImageCommentsFileData[key($dirImageCommentsFileData)]['Active']==2 && empty($dirImageCommentsFileData[key($dirImageCommentsFileData)]['ReviewTstamp'])){
                        $countCtoReview++;
                    }
                }
            }

            if (!empty($_POST['delete-comment'])) {
                foreach($_POST['delete-comment'] as $key => $commentId){
                        // if old comment then still might be in the database, this why delete
                        $deleteQuery = 'DELETE FROM ' . $tablename_comments . ' WHERE';
                        $deleteQuery .= ' id = %d';

                        $deleteParameters = '';
                        $deleteParameters .= $commentId;
                        $wpdb->query( $wpdb->prepare(
                            "
                                $deleteQuery
                            ",
                                $deleteParameters
                        ));

                        unset($commentsArray[$commentId]);
                        $fileImageComment = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galeryNR.'/json/image-comments/ids/'.$pid.'/'.$commentId.'.json';
                        if(file_exists($fileImageComment)){
                            unlink($fileImageComment);
                        }
                    }
            }

            $hasNewReviewed = false;

            $unix = time();

            if (!empty($_POST['activate-comment'])) {// @toDo CountCtoReview noch updaten

                foreach($_POST['activate-comment'] as $key => $commentId){
                    // if old comment then still might be in the database, this why update
                    $wpdb->update(
                        "$tablename_comments",
                        array('Active' => 1),
                        array('id' => $commentId),
                        array('%d'),
                        array('%d')
                    );

                    $commentsArray[$commentId]['Active'] = 1;
                    $fileImageComment = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galeryNR.'/json/image-comments/ids/'.$pid.'/'.$commentId.'.json';
                    if(file_exists($fileImageComment)){
                        $fileImageCommentArray = json_decode(file_get_contents($fileImageComment),true);

                        // then first time Review and activation and user can be informed
                        if(empty($fileImageCommentArray[key($fileImageCommentArray)]['ReviewTstamp']) AND isset($fileImageCommentArray[key($fileImageCommentArray)]['Active'])  AND $fileImageCommentArray[key($fileImageCommentArray)]['Active']==2){
                            $hasNewReviewed = true;
                        }

                        $fileImageCommentArray[key($fileImageCommentArray)]['Active'] = 1;
                        if(empty($fileImageCommentArray[key($fileImageCommentArray)]['ReviewTstamp'])){

                            $fileImageCommentArray[key($fileImageCommentArray)]['ReviewTstamp'] = $unix;
                            $commentsArray[$commentId]['ReviewTstamp'] = $unix;
                            $countCtoReview--;
                        }

                        file_put_contents($fileImageComment,json_encode($fileImageCommentArray));

                    }
                }

            }

            if (!empty($_POST['deactivate-comment'])) {// @toDo CountCtoReview noch updaten
                foreach($_POST['deactivate-comment'] as $key => $commentId){
                    $wpdb->update(
                        "$tablename_comments",
                        array('Active' => 2),
                        array('id' => $commentId),
                        array('%d'),
                        array('%d')
                    );
                    $commentsArray[$commentId]['Active'] = 2;
                    $fileImageComment = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galeryNR.'/json/image-comments/ids/'.$pid.'/'.$commentId.'.json';
                    if(file_exists($fileImageComment)){
                        $fileImageCommentArray = json_decode(file_get_contents($fileImageComment),true);
                        if(empty($fileImageCommentArray[key($fileImageCommentArray)]['ReviewTstamp'])){// so never appear again as for review!
                            $fileImageCommentArray[key($fileImageCommentArray)]['ReviewTstamp'] = time();
                        }
                        $fileImageCommentArray[key($fileImageCommentArray)]['Active'] = 2;
                        file_put_contents($fileImageComment,json_encode($fileImageCommentArray));
                    }
                }
            }

            // set image data, das ganze gesammelte
            $jsonFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galeryNR.'/json/image-comments/image-comments-'.$pid.'.json';
            $fp = fopen($jsonFile, 'w');
            fwrite($fp, json_encode($commentsArray));
            fclose($fp);

            // has to be done after image-comments...json is ready
            if($hasNewReviewed){
                $options = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galeryNR.'/json/'.$galeryNR.'-options.json';
                $fp = fopen($options, 'r');
                $options =json_decode(fread($fp,filesize($options)),true);
                if(!empty($options[$galeryNR])){
                    $options = $options[$galeryNR];
                }
                fclose($fp);
                contest_gal1ery_user_comment_mail_prepare($options,$pid,$galeryNR,$wp_upload_dir,time());
            }

            // check if there were some database entries of before version 16
            $countCommentsSQL = $wpdb->get_var( $wpdb->prepare(
            "
                SELECT COUNT(1)
                FROM $tablename_comments 
                WHERE pid = %d
            ",
            $pid
            ) );

            $fileImageCommentsDirCount = 0;

            if(is_dir($fileImageCommentsDir)){
                $fileImageCommentsDirCount = count(glob($fileImageCommentsDir.'/*.json'));
            }

            $countCommentsTotal = $countCommentsSQL+$fileImageCommentsDirCount;

            // the rest will be done in cg_actualize_all_images_data_sort_values_file
            $wpdb->update(
            "$tablename",
                array('CountC' => $countCommentsTotal, 'CountCtoReview' => $countCtoReview),
            array('id' => $pid),
            array('%d'),
            array('%d')
            );

        // process rating comments data file

        $dataFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galeryNR.'/json/image-data/image-data-'.$pid.'.json';
        $fp = fopen($dataFile, 'r');
        $ratingCommentsData =json_decode(fread($fp,filesize($dataFile)),true);
        fclose($fp);

        $ratingCommentsData['CountC'] = $countCommentsSQL;

        $fp = fopen($dataFile, 'w');
        fwrite($fp,json_encode($ratingCommentsData));
        fclose($fp);

        $dataFile = $wp_upload_dir['basedir'] . '/contest-gallery/gallery-id-' . $galeryNR . '/json/' . $galeryNR . '-images-sort-values.json';
        $fp = fopen($dataFile, 'r');
        $ratingCommentsDataAllImages =json_decode(fread($fp,filesize($dataFile)),true);
        $ratingCommentsDataAllImages[$pid]['CountC'] = $countCommentsSQL;

        $dataFile = $wp_upload_dir['basedir'] . '/contest-gallery/gallery-id-' . $galeryNR . '/json/' . $galeryNR . '-images-sort-values.json';
        $fp = fopen($dataFile, 'w');
        fwrite($fp,json_encode($ratingCommentsDataAllImages));
        fclose($fp);

        $tstampFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galeryNR.'/json/'.$galeryNR.'-gallery-sort-values-tstamp.json';
        $fp = fopen($tstampFile, 'w');
        fwrite($fp, json_encode(time()));
        fclose($fp);

        // process rating comments data file --- ENDE

        cg_actualize_all_images_data_sort_values_file($GalleryID,true,$IsModernFiveStar);

     //   echo "<p id='cg_changes_saved' style='font-size:18px;'><strong>Changes saved</strong></p>";

    }

    $countCtoReviewArray = [];
    if(is_dir($dirImageComments)){
        $dirImageCommentsFiles = glob($dirImageComments.'/*.json');
        foreach ($dirImageCommentsFiles as $dirImageCommentsFile){
            $dirImageCommentsFileData = json_decode(file_get_contents($dirImageCommentsFile),true);
            if(!empty($dirImageCommentsFileData[key($dirImageCommentsFileData)]['Active']) && $dirImageCommentsFileData[key($dirImageCommentsFileData)]['Active']==2 && empty($dirImageCommentsFileData[key($dirImageCommentsFileData)]['ReviewTstamp'])){
                $countCtoReviewArray[key($dirImageCommentsFileData)] = true;
            }
        }
    }


// DATEN Löschen und exit ENDE	

        $imageData = $wpdb->get_row("SELECT * FROM $tablename WHERE id = '$pid'");
        $ImgType = $imageData->ImgType;
        $WpUserId = $imageData->WpUserId;
        $WpUpload = $imageData->WpUpload;
        $widthOriginalImg = $imageData->Width;
        $heightOriginalImg = $imageData->Height;
        $rThumb = $imageData->rThumb;

        if(!empty($imageData->MultipleFiles) && $imageData->MultipleFiles!='""'){
            $MultipleFilesUnserialized = unserialize($imageData->MultipleFiles);
            if(!empty($MultipleFilesUnserialized)){//check for sure if really exists and unserialize went right, because might happen that "" was in database from earlier versions
                foreach($MultipleFilesUnserialized as $order => $MultipleFile){
                    if($order==1 && empty($MultipleFile['isRealIdSource'])){
                        $ImgType = (!empty($MultipleFile['ImgType'])) ? $MultipleFile['ImgType'] : 0;
                        $widthOriginalImg = (!empty($MultipleFile['Width'])) ? $MultipleFile['Width'] : 0;
                        $heightOriginalImg = (!empty($MultipleFile['Height'])) ? $MultipleFile['Height'] : 0;
                        $rThumb = (!empty($MultipleFile['rThumb'])) ? $MultipleFile['rThumb'] : '';
                        $WpUpload = (!empty($MultipleFile['WpUpload'])) ? $MultipleFile['WpUpload'] : 0;
                        break;
                    }
                }
            }
       }

        $user_login = $wpdb->get_var("SELECT user_login  FROM $table_wp_users WHERE ID = $WpUserId ORDER BY ID ASC");

        if(!empty($imageData->IP)){
            $userIP = $imageData->IP;
        }else{
            $userIP = 'User IP when uploading will be tracked since plugin version 10.9.3.7';
        }

        if(!empty($imageData->CookieId)){
            $CookieId = $imageData->CookieId;
        }else{
            $CookieId = '';
        }


        if($ImgType=='con'){
            $image_url = '';
            $post_title = '';
            $post_description = '';
            $post_excerpt = '';
            $post_type = '';
            $wp_image_id = '';
            $sourceOriginalImgShow = $image_url;
        }else{
            $wp_image_info = $wpdb->get_row("SELECT * FROM $table_posts WHERE ID = $WpUpload");
            $image_url = $wp_image_info->guid;
            $post_title = $wp_image_info->post_title;
            $post_description = $wp_image_info->post_content;
            $post_excerpt = $wp_image_info->post_excerpt;
            $post_type = $wp_image_info->post_mime_type;
            $wp_image_id = $wp_image_info->ID;
            $sourceOriginalImgShow = $image_url;
        }

        if(cg_is_is_image($ImgType)){
        $imageThumb = wp_get_attachment_image_src($WpUpload, 'large');
        $imageThumb = $imageThumb[0];

        $WidthThumb = 300;
        $HeightThumb = 200;

        // Ermittlung der Höhe nach Skalierung. Falls unter der eingestellten Höhe, dann nächstgrößeres Bild nehmen.
        $heightScaledThumb = $WidthThumb*$heightOriginalImg/$widthOriginalImg;

        // Falls unter der eingestellten Höhe, dann größeres Bild nehmen (normales Bild oder panorama Bild, kein Vertikalbild)
        if ($heightScaledThumb <= $HeightThumb) {

            $imageThumb = wp_get_attachment_image_src($WpUpload, 'large');
            $imageThumb = $imageThumb[0];

            // Bestimmung von Breite des Bildes
            $WidthThumbPic = $HeightThumb*$widthOriginalImg/$heightOriginalImg;

            // Bestimmung wie viel links und rechts abgeschnitten werden soll
            $paddingLeftRight = ($WidthThumbPic-$WidthThumb)/2;
            $paddingLeftRight = $paddingLeftRight.'px';

            $padding = "left: -$paddingLeftRight;right: -$paddingLeftRight";

            $WidthThumbPic = $WidthThumbPic.'px';

        }

        // Falls über der eingestellten Höhe, dann kleineres Bild nehmen (kein Vertikalbild)

        if ($heightScaledThumb > $HeightThumb) {

            $imageThumb = wp_get_attachment_image_src($WpUpload, 'large');
            $imageThumb = $imageThumb[0];

            // Bestimmung von Breite des Bildes
            $WidthThumbPic = $WidthThumb.'px';

            // Bestimmung wie viel oben und unten abgeschnitten werden soll
            $heightImageThumb = $WidthThumb*$heightOriginalImg/$widthOriginalImg;
            $paddingTopBottom = ($heightImageThumb-$HeightThumb)/2;
            $paddingTopBottom = $paddingTopBottom.'px';

            $padding = "top: -$paddingTopBottom;bottom: -$paddingTopBottom";

        }

        }

        echo "<div id='cgShowCommentsPicture' >";
            echo "<div id='cgVotesImageVisual' >";

                if(cg_is_alternative_file_type_file($ImgType)){
                    echo '<a href="'.$sourceOriginalImgShow.'" target="_blank" title="Show full size">';
                        echo '<div id="cgVotesImageVisualContent">';
                            echo '<div class="cg-votes-image-visual-content-file-type-'.$ImgType.'">';
                            echo "</div>";
                        echo "</div>";
                    echo '</a>';
               }else if(cg_is_alternative_file_type_video($ImgType)){
                    echo '<a href="'.$sourceOriginalImgShow.'" target="_blank" title="Show file" alt="Show file">';
                        echo '<video width="300" height="200"  >';
                            echo '<source src="'.$sourceOriginalImgShow.'" type="video/mp4">';
                            echo '<source src="'.$sourceOriginalImgShow.'" type="video/'.$ImgType.'">';
                        echo '</video>';
                    echo '</a>';
               }else if($ImgType=='con'){
                    echo '<div id="cgVotesImageVisualContent">';
                    echo "</div>";
                }else{
                echo '<div id="cgVotesImageVisualContent">';
                echo '<a href="'.$sourceOriginalImgShow.'" target="_blank" title="Show full size"><img class="cg'.$rThumb.'degree" src="'.$imageThumb.'" style="'.$padding.';position: absolute !important;max-width:none !important;" width="'.$WidthThumbPic.'"></a>';
                //echo '<a href="'.$sourceOriginalImgShow.'" target="_blank" title="Show full size" alt="Show full size"><img src="'.$WPdestination.$value->Timestamp.'_'.$value->NamePic.'-300width.'.$value->ImgType.'" style="'.$padding.';position: absolute !important;max-width:none !important;" width="'.$WidthThumbPic.'"></a>';
                echo "</div>";
                }

                echo '<div id="cgVotesImageVisualId">';

                echo "<strong>Entry ID:</strong> $imageData->id";
                echo "<br>";
                echo "<strong>IP:</strong><span style='font-size:12px;'>$userIP</span>";
                if($proOptions->RegUserUploadOnly==2){
                    echo "<br>";
                    echo "<strong>Cookie ID:</strong><br><span style='font-size:12px;'>$CookieId</span>";
                }

                if($WpUserId>0){

                    echo "<br>";
                    echo "<div class='cg_backend_info_user_link_container'>";
                    echo "<span style='display:table;'><strong>Added by:</strong></span><a style=\"display:flex;margin-top:5px;\" class=\"cg_image_action_href cg_load_backend_link\" href='?page=".cg_get_version()."/index.php&users_management=true&option_id=$GalleryID&wp_user_id=".$WpUserId."'><span class=\"cg_image_action_span\" >".$user_login."</span></a>";
                    echo '</div>';

                }

                echo '</div>';
            echo "</div>";
        echo "</div>";

    $wp_upload_dir = wp_upload_dir();
    $select_comments = [];
    // this file should contain always all comments of pid, if not then repair has to be done
    $jsonFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galeryNR.'/json/image-comments/image-comments-'.$pid.'.json';
    if(file_exists($jsonFile)){
        $select_comments = json_decode(file_get_contents($jsonFile),true);
    }
//var_dump($select_comments);
 //       if(count($select_comments)){

      if(!empty($select_comments)){

          $select_comments_array = [];

          $collectWpUserIds = '';
          $collectWpUserIdsArray = [];

          foreach($select_comments as $key => $value){
              // add id in array
              $select_comments_array[$value['timestamp']] = array();
              $select_comments_array[$value['timestamp']] = $value;
              $select_comments_array[$value['timestamp']]['id'] = $key;
              $select_comments_array[$value['timestamp']]['Active'] = (empty($value['Active']) || $value['Active']==1) ? 1 : 2;
              if(!empty($value['WpUserId'])){
                  if(!in_array($value['WpUserId'],$collectWpUserIdsArray)){
                      if(empty($collectWpUserIds)){
                          $collectWpUserIds .= "ID = ".$value['WpUserId'];
                      }else{
                          $collectWpUserIds .= " OR ID = ".$value['WpUserId'];
                      }
                      $collectWpUserIdsArray[] = $value['WpUserId'];
                  }
              }
          }
          $wpNickNamesArray = [];

          $wpUsers = $wpdb->get_results( "SELECT ID, user_login, user_nicename FROM $tablenameWpUsers WHERE ($collectWpUserIds) ");

          foreach ($wpUsers as $wpUser){
              $wpNickname = get_user_meta( $WpUserId, 'nickname');
              if(is_array($wpNickname)){
                  $wpNickname = $wpNickname[0];
                  $wpNickNamesArray[$wpUser->ID] = $wpNickname;
              }else{
                  if(!empty($wpUser->user_nicename)){
              $wpNickNamesArray[$wpUser->ID] = $wpUser->user_nicename;
                  }else{
                      $wpNickNamesArray[$wpUser->ID] = $wpUser->user_login;
                  }
              }
          }
          function cgSortArray($a1, $a2){
              if ($a1['timestamp'] == $a2['timestamp']) return 0;
              return ($a1['timestamp'] > $a2['timestamp']) ? -1 : 1;
          }

          usort($select_comments_array, "cgSortArray");

         echo "<form style='width:100%;' action='?page=".cg_get_version()."/index.php&option_id=$galeryNR&show_comments=true&id=$pid'  data-cg-submit-message='Changes Saved'  method='POST' class='cg_load_backend_submit'>";

          echo '<input type="hidden" name="cg_picture_id" id="cg_picture_id" value="'.$pid.'">';

        echo "<div id='cgShowComments' class='cg_border_top_none' >";

		foreach($select_comments_array as $key => $value){
	//	$id = $value->id;
		$id = $value['id'];
	//	$pid = $value->pid;

	//	$id = $value->id;
	//	$pid = $value->pid;
		//$name = htmlspecialchars($value->Name);
        $name = contest_gal1ery_convert_for_html_output_without_nl2br($value['name']);
        //$name = 'asdfas&#x1f525&#x2744 dfasdf&#x1f355&#x1f525&#x1f30d';
        //var_dump($name);
        //$name = str_ireplace("/&amp;amp;#x/g","&#x",$name);
     //       $result = preg_replace('/abc/', 'def', $string);   # Replace all 'abc' with 'def'
        $name = preg_replace("/&amp;amp;#x/","&#x",$name);// do both to go sure
        $name = preg_replace("/&amp;#x/","&#x",$name);// do both to go sure
        //$name = preg_replace("/amp;/","",$name);// do both to go sure

        foreach($emojis as $emoji){
            $name = preg_replace("/$emoji/i","$emoji ",$name);// do both to go sure
        }

		$date = htmlspecialchars($value['date']);
        $commentTime = cg_get_time_based_on_wp_timezone_conf($value['timestamp'],'d-M-Y H:i:s');
         $comment1 = contest_gal1ery_convert_for_html_output($value['comment']);
        $comment1 = preg_replace("/&amp;amp;#x/","&#x",$comment1);// do both to go sure
        $comment1 = preg_replace("/&amp;#x/","&#x",$comment1);// do both to go sure

        foreach($emojis as $emoji){
            $comment1 = preg_replace("/$emoji/i","$emoji ",$comment1);// do both to go sure
        }

            echo "<hr>";

            echo "<div style='margin-bottom:20px;margin-top:20px;display:flex;' class='cg_comment' >";
        echo "<div style='width: 70%;'>";
		if(!empty($value['Active']) && $value['Active']==2){
            if(!empty($countCtoReviewArray[$id])){
                echo "<span style='color:orange;font-weight: bold;'>Not Active - Not Reviewed</span>";
            }else{
                echo "<span style='color:orange;font-weight: bold;'>Not Active</span>";
            }
        }else{
            echo "<span style='color:green;font-weight: bold;'>Active</span>";
        }
        if(!empty($value['userIP'])){
            $userIP = contest_gal1ery_convert_for_html_output($value['userIP']);
            echo "<br><div id='cg-user-ip' style='display:inline;'>User IP: $userIP</div>";
        }
		echo "<br>Date: <div id='cg-comment-$id' style='display:inline;'>$commentTime</div>";
        echo "<div style='display:inline;'>";

        //if(!empty($name)){
        if(!empty($name)){
            echo "<br>Name: <b>".$name."</b>";
        }else{
            if(!empty($value['WpUserId']) && !empty($wpNickNamesArray[$value['WpUserId']])){
                echo "<br>Name (Registered user id ".$value['WpUserId']."): <b>".$wpNickNamesArray[$value['WpUserId']]."</b>";
            }
            if(!empty($value['WpUserId']) && empty($wpNickNamesArray[$value['WpUserId']])){
                echo "<br>Registered user id ".$value['WpUserId']."): user nickname could not be determined";
            }
        }

        echo "<p>Comment:<br>".$comment1."</p>";
        echo "<br/>";
        echo "</div>";
        echo "</div>";

            echo "<div>";
		echo "<div style='display:inline;float:right;'>Delete: <input  class='cg_comment_delete'  type='checkbox' name='delete-comment[]' value='$id'></div>";
		echo "<div style='display:inline;float:right;margin-right:20px;'>Activate: <input class='cg_comment_activate' ".((!empty($value['Active']) && $value['Active']==2) ? "" : "disabled")." type='checkbox' name='activate-comment[]' value='$id'></div>";
		echo "<div style='display:inline;float:right;margin-right:20px;'>Deactivate: <input class='cg_comment_deactivate' ".((!empty($value['Active']) && $value['Active']==2) ? "disabled" : "")." type='checkbox' name='deactivate-comment[]' value='$id'></div>";
            echo "</div>";

		echo "</div>";
		

			}


echo "</div>";

								echo "<div id='cgShowCommentsDeleteSubmit'>";
		echo '<input class="cg_backend_button_gallery_action" type="submit" value="Save changes" id="submit" style="text-align:center;margin-left:auto;">';
		//echo '<input type="hidden" value="delete-comment" name="delete-comment">';

		echo "</div>";
            echo '</form>';

        }

		else{
		echo "<div style='box-shadow: 2px 4px 12px rgba(0,0,0,.08);border-radius: 8px;box-sizing:border-box;width:100%;padding:20px;background-color:#fff;margin-bottom:0px !important;margin-bottom:0;text-align:center;'>";
		echo "<p style=\"font-size: 16px;\"><b>All file comments are deleted</b></p>";
		echo "</div>";
			
		}

?>