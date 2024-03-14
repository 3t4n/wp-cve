<?php

if (!function_exists('contest_gal1ery_user_vote_mail_prepare')) {
    function contest_gal1ery_user_vote_mail_prepare($options, $pictureID, $galeryID, $isMultipleStars = false)
    {

        global $wpdb;

        $tablename = $wpdb->prefix . "contest_gal1ery";
        $tablename_mail_user_vote = $wpdb->prefix . "contest_gal1ery_mail_user_vote";
        $tablename_user_vote_mails = $wpdb->prefix . "contest_gal1ery_user_vote_mails";
        $tablename_mail_user_comment = $wpdb->prefix . "contest_gal1ery_mail_user_comment";
        $tablename_user_comment_mails = $wpdb->prefix . "contest_gal1ery_user_comment_mails";
        $wp_users = $wpdb->prefix . "users";
        $tablenameIP = $wpdb->prefix . "contest_gal1ery_ip";

        if (!empty($options['pro']['InformUserVote'])) {
            $InformUserVoteMailInterval = $options['pro']['InformUserVoteMailInterval'];

            $rowObject = $wpdb->get_row("SELECT * FROM $tablename WHERE id = '$pictureID'  ORDER BY id DESC LIMIT 1");
            $wpUserIdOfVotedImage = $rowObject->WpUserId;
            $WpPage = $rowObject->WpPage;

            if (!empty($wpUserIdOfVotedImage)) {

                $lastTstampFor = $wpdb->get_var("SELECT Tstamp FROM $tablename_user_vote_mails WHERE WpUserId = $wpUserIdOfVotedImage  AND GalleryID = $galeryID ORDER BY id DESC LIMIT 1");
                $tstampToCompare = 1 * 60 * 60;
                if ($InformUserVoteMailInterval == '1m') {
                    $tstampToCompare = 1 * 60;
                } else if ($InformUserVoteMailInterval == '2m') {
                    $tstampToCompare = 1 * 120;
                } else if ($InformUserVoteMailInterval == '1h') {
                    $tstampToCompare = 1 * 60 * 60;
                } else if ($InformUserVoteMailInterval == '2h') {
                    $tstampToCompare = 1 * 60 * 60 * 2;
                } else if ($InformUserVoteMailInterval == '4h') {
                    $tstampToCompare = 1 * 60 * 60 * 4;
                } else if ($InformUserVoteMailInterval == '6h') {
                    $tstampToCompare = 1 * 60 * 60 * 6;
                } else if ($InformUserVoteMailInterval == '12h') {
                    $tstampToCompare = 1 * 60 * 60 * 12;
                } else if ($InformUserVoteMailInterval == '24h') {
                    $tstampToCompare = 1 * 60 * 60 * 24;
                } else if ($InformUserVoteMailInterval == '48h') {
                    $tstampToCompare = 1 * 60 * 60 * 48;
                } else if ($InformUserVoteMailInterval == '1week') {
                    $tstampToCompare = 1 * 60 * 60 * 168;
                } else if ($InformUserVoteMailInterval == '2weeks') {
                    $tstampToCompare = 1 * 60 * 60 * 336;
                } else if ($InformUserVoteMailInterval == '4weeks') {
                    $tstampToCompare = 1 * 60 * 60 * 672;
                }
                if (empty($lastTstampFor) or (time() - $tstampToCompare) > $lastTstampFor) {
                    if (empty($lastTstampFor)) {
                        $lastTstampFor = time() - $tstampToCompare;
                    }
                    $selectSQLemailUserVote = $wpdb->get_row("SELECT * FROM $tablename_mail_user_vote WHERE GalleryID = '$galeryID'");
                    $InformUserContent = contest_gal1ery_convert_for_html_output($selectSQLemailUserVote->Content);

                    // insert first to reduce chance of multiple processing
                    $wpdb->query($wpdb->prepare(
                        "
                        INSERT INTO $tablename_user_vote_mails 
                        ( id, GalleryID, Tstamp, WpUserId)
                        VALUES ( %s,%d,%d,%d)
                    ",
                        '', $galeryID, time(),$wpUserIdOfVotedImage
                    ));
                    //var_dump(123123);
                    //$wpdb->show_errors(); //setting the Show or Display errors option to true
                    //$wpdb->print_error();
                    $insert_id = $wpdb->insert_id;

                    $posUserInfo = "\$info\$";

                    if ($isMultipleStars) {
                        $sumCountR = "SUM(";

                        for ($iR = 1; $iR <= 15 - 10; $iR++) {
                            if ($iR == 1) {
                                $sumCountR .= "CASE WHEN $tablenameIP.pid > 0 AND $tablenameIP.Rating = '$iR' AND $tablename.id = $tablenameIP.pid THEN $iR ELSE 0 END";
                            } else {
                                $sumCountR .= " + CASE WHEN $tablenameIP.pid > 0 AND $tablenameIP.Rating = '$iR' AND $tablename.id = $tablenameIP.pid THEN $iR ELSE 0 END";
                            }
                        }

                        $sumCountR .= ") AS CountRtotalSum";

                        $userVotes = $wpdb->get_results("SELECT NamePic, CountRtotalSum, id  from (SELECT DISTINCT $tablename.*, $tablenameIP.pid, $sumCountR,
                  (
                   CASE WHEN NOT EXISTS(SELECT NULL FROM $tablenameIP WHERE $tablename.id = $tablenameIP.pid)
                      THEN 1
                      ELSE 0
                   END 
                  ) AS CountRnotExists
                        FROM $tablenameIP, $tablename WHERE 
                        ($tablename.id = $tablenameIP.pid AND $tablename.GalleryID = $galeryID AND $tablename.Active = 1 AND $tablenameIP.Rating > 0 AND $tablenameIP.Tstamp > $lastTstampFor)
                        GROUP BY $tablename.id) AS CountRtotalDataCollect 
                    GROUP BY id ORDER BY CountRtotalSum DESC LIMIT 10
                        ");

                    } else {

                        $sumCountS = "SUM( CASE WHEN $tablenameIP.pid > 0 AND $tablenameIP.RatingS = '1' AND $tablename.id = $tablenameIP.pid THEN 1 ELSE 0 END) AS CountStotalCount";

                        $userVotes = $wpdb->get_results("SELECT NamePic, CountStotalCount, id  from (SELECT DISTINCT $tablename.id, $tablenameIP.pid, $tablename.NamePic, $sumCountS,
                              (
                               CASE WHEN NOT EXISTS(SELECT NULL FROM $tablenameIP WHERE $tablename.id = $tablenameIP.pid)
                                  THEN 1
                                  ELSE 0
                               END 
                              ) AS CountSnotExists
                                    FROM $tablenameIP, $tablename WHERE 
                                    ($tablename.id = $tablenameIP.pid AND $tablename.GalleryID = $galeryID  AND $tablename.Active = 1 AND $tablenameIP.RatingS = 1 AND $tablenameIP.Tstamp > $lastTstampFor)
                                    GROUP BY $tablename.id) AS CountStotalDataCollect 
                                    GROUP BY id ORDER BY CountStotalCount DESC LIMIT 10
                                    ");

                    }

                    $to = $wpdb->get_var("SELECT user_email FROM $wp_users WHERE ID = $wpUserIdOfVotedImage");

                    if (stripos($InformUserContent, $posUserInfo) !== false) {

                        include(__DIR__ . "/../../../check-language.php");

                        $UserEntries = '';
                        foreach ($userVotes as $userVotesData) {
                            if ($isMultipleStars) {
                                $UserEntries .= '(<b>+' . $userVotesData->CountRtotalSum . '</b>) '.$userVotesData->NamePic . '<br/>';
                            }else{
                                $UserEntries .= '(<b>+' . $userVotesData->CountStotalCount . '</b>) '.$userVotesData->NamePic . '<br/>';
                            }

                            if(!empty($WpPage)){
                                $WpPagePermalink = get_permalink($WpPage);
                                $UserEntries .= '<a href="' . $WpPagePermalink . '" target="_blank">' . $WpPagePermalink . '</a><br/><br/>';
                            }else{
                                if (!empty($selectSQLemailUserVote->URL)) {
                                    $UserEntries .= '<a href="' . $selectSQLemailUserVote->URL . "#!gallery/$galeryID/file/" . $userVotesData->id . "/" . $userVotesData->NamePic . '" target="_blank">' . $selectSQLemailUserVote->URL . "#!gallery/$galeryID/file/" . $userVotesData->id . "/" . $userVotesData->NamePic . '</a><br/><br/>';
                                } else {
                                    $UserEntries .= "Missing URL in options to provide full gallery link ...#!gallery/$galeryID/file/" . $userVotesData->id . "/" . $userVotesData->NamePic . '<br/><br/>';
                                }
                            }

                        }

                        $Msg = str_ireplace($posUserInfo, $UserEntries, $InformUserContent);

                        contest_gal1ery_user_vote_mail($selectSQLemailUserVote, $Msg, $galeryID, $to);
                    } else {
                        $Msg = $InformUserContent;
                        contest_gal1ery_user_vote_mail($selectSQLemailUserVote, $Msg, $galeryID, $to);
                    }

                    $Msg = contest_gal1ery_htmlentities_and_preg_replace($Msg);

                    // update main table
                    $wpdb->update(
                        "$tablename_user_vote_mails",
                        array('Content' => $Msg),
                        array('id' => $insert_id),
                        array('%s'),
                        array('%d')
                    );


                }
            }

        }


    }
}
