<?php

if (!function_exists('contest_gal1ery_user_comment_mail_prepare'))   {
    function contest_gal1ery_user_comment_mail_prepare($options,$pictureID,$galeryID,$wp_upload_dir,$unix) {

        global $wpdb;

        $tablename = $wpdb->prefix . "contest_gal1ery";
        $tablename_mail_user_comment = $wpdb->prefix . "contest_gal1ery_mail_user_comment";
        $tablename_user_comment_mails = $wpdb->prefix . "contest_gal1ery_user_comment_mails";
        $wp_users = $wpdb->prefix . "users";

        $InformUserCommentMailInterval = $options['pro']['InformUserCommentMailInterval'];
        $rowObject = $wpdb->get_row("SELECT * FROM $tablename WHERE id = '$pictureID'  ORDER BY id DESC LIMIT 1");
        $wpUserIdOfCommentedFile = $rowObject->WpUserId;
        $WpPage = $rowObject->WpPage;

        $lastTstampFor = $wpdb->get_var("SELECT Tstamp FROM $tablename_user_comment_mails WHERE WpUserId = $wpUserIdOfCommentedFile  AND GalleryID = $galeryID ORDER BY id DESC LIMIT 1");
        $tstampToCompare=1*60*60;
        if($InformUserCommentMailInterval=='1m'){$tstampToCompare=1*60;}// for testing
        else if($InformUserCommentMailInterval=='2m'){$tstampToCompare=1*120;}// for testing
        else if($InformUserCommentMailInterval=='1h'){$tstampToCompare=1*60*60;}
        else if($InformUserCommentMailInterval=='2h'){$tstampToCompare=1*60*60*2;}
        else if($InformUserCommentMailInterval=='4h'){$tstampToCompare=1*60*60*4;}
        else if($InformUserCommentMailInterval=='6h'){$tstampToCompare=1*60*60*6;}
        else if($InformUserCommentMailInterval=='12h'){$tstampToCompare=1*60*60*12;}
        else if($InformUserCommentMailInterval=='24h'){$tstampToCompare=1*60*60*24;}
        else if($InformUserCommentMailInterval=='48h'){$tstampToCompare=1*60*60*48;}
        else if($InformUserCommentMailInterval=='1week'){$tstampToCompare=1*60*60*168;}
        else if($InformUserCommentMailInterval=='2weeks'){$tstampToCompare=1*60*60*336;}
        else if($InformUserCommentMailInterval=='4weeks'){$tstampToCompare=1*60*60*672;}
        if(empty($lastTstampFor) OR (time()-$tstampToCompare)>$lastTstampFor){
            if(empty($lastTstampFor)){$lastTstampFor = time()-$tstampToCompare;}

            $selectSQLemailUserComment = $wpdb->get_row( "SELECT * FROM $tablename_mail_user_comment WHERE GalleryID = '$galeryID'" );
            $InformUserContent = contest_gal1ery_convert_for_html_output($selectSQLemailUserComment->Content);

            // insert first to reduce chance of multiple processing
            $wpdb->query( $wpdb->prepare(
                "
					INSERT INTO $tablename_user_comment_mails 
					( id, GalleryID, Tstamp, WpUserId)
					VALUES ( %s,%d,%d,%d)
				",
                '',$galeryID,$unix,$wpUserIdOfCommentedFile
            ) );
            $insert_id = $wpdb->insert_id;

            $posUserInfo = "\$info\$";

            $filesFromUserOfCommentedFile = $wpdb->get_results( "SELECT id, NamePic FROM $tablename WHERE WpUserId = $wpUserIdOfCommentedFile AND Active = 1 AND GalleryID = $galeryID");
            $filesAndCommentsCounterSinceTstampArray = [];

            if(count($filesFromUserOfCommentedFile)){
                foreach($filesFromUserOfCommentedFile as $file){
                    $commentsFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galeryID.'/json/image-comments/image-comments-'.$file->id.'.json';
                    if(is_file($commentsFile)){
                        $comments = json_decode(file_get_contents($commentsFile),true);
                        $counter = 0;
                        foreach ($comments as $comment){
                            if(isset($comment['timestamp']) && $comment['timestamp'] > $lastTstampFor && isset($comment['Active']) && $comment['Active'] != 2){
                                if(empty($filesAndCommentsCounterSinceTstampArray[$file->id])){$filesAndCommentsCounterSinceTstampArray[$file->id] = [];}
                                $counter++;
                                $filesAndCommentsCounterSinceTstampArray[$file->id] = ['counter' => $counter,'NamePic' => $file->NamePic,'id' => $file->id];
                            }else if(!empty($comment['ReviewTstamp']) && $comment['ReviewTstamp'] > $lastTstampFor && isset($comment['Active']) && $comment['Active'] != 2){// if was reviewd later
                                if(empty($filesAndCommentsCounterSinceTstampArray[$file->id])){$filesAndCommentsCounterSinceTstampArray[$file->id] = [];}
                                $counter++;
                                $filesAndCommentsCounterSinceTstampArray[$file->id] = ['counter' => $counter,'NamePic' => $file->NamePic,'id' => $file->id];
                            }
                        }
                    }
                }

                if(!empty($filesAndCommentsCounterSinceTstampArray)){

                    usort($filesAndCommentsCounterSinceTstampArray, function($a, $b) {
                        return $a['counter'] - $b['counter'];
                    });

                    $filesAndCommentsCounterSinceTstampArray = array_reverse($filesAndCommentsCounterSinceTstampArray);
                    $to = $wpdb->get_var("SELECT user_email FROM $wp_users WHERE ID = $wpUserIdOfCommentedFile");

                    if(stripos($InformUserContent,$posUserInfo)!==false){

                        $UserEntries = '';

                        $counter = 0;
                        foreach ($filesAndCommentsCounterSinceTstampArray as $fileId => $fileCommentCounterArray){
                            $UserEntries .= '(<b>+'.$fileCommentCounterArray['counter'].'</b>) '.$fileCommentCounterArray['NamePic'].'<br/>';
                            if(!empty($WpPage)){
                                $WpPagePermalink = get_permalink($WpPage);
                                $UserEntries .= '<a href="' . $WpPagePermalink . '" target="_blank">' . $WpPagePermalink . '</a><br/><br/>';
                            }else{
                                if(!empty($selectSQLemailUserComment->URL)){
                                    $UserEntries .= '<a href="'.$selectSQLemailUserComment->URL."#!gallery/$galeryID/file/".$fileCommentCounterArray['id']."/".$fileCommentCounterArray['NamePic'].'" target="_blank">'.$selectSQLemailUserComment->URL."#!gallery/$galeryID/file/".$fileCommentCounterArray['id']."/".$fileCommentCounterArray['NamePic'].'</a><br/><br/>';
                                }else{
                                    $UserEntries .=  "Missing URL in options to provide full gallery link ...#!gallery/$galeryID/file/".$fileCommentCounterArray['id']."/".$fileCommentCounterArray['NamePic'].'<br/><br/>';
                                }
                            }
                            $counter++;
                            //max 10 urls should be displayed
                            if($counter==10){break;}
                        }

                        $Msg = str_ireplace($posUserInfo, $UserEntries, $InformUserContent);
                        contest_gal1ery_user_comment_mail($selectSQLemailUserComment,$Msg,$galeryID,$to);

                    } else{
                        $Msg = $InformUserContent;
                        contest_gal1ery_user_comment_mail($selectSQLemailUserComment,$Msg,$galeryID,$to);
                    }

                    // update main table
                    $wpdb->update(
                        "$tablename_user_comment_mails",
                        array('Content' => contest_gal1ery_htmlentities_and_preg_replace($Msg)),
                        array('id' => $insert_id),
                        array('%s'),
                        array('%d')
                    );

                }

            }
        }

    }
}
