<?php

/**###NORMAL###**/

if(!function_exists('cg_reset_to_normal_version_options_if_required')){
    function cg_reset_to_normal_version_options_if_required ($GalleryID = 0,$wp_upload_dir = null) {

        if(empty($GalleryID)){
            if(!empty($_GET['option_id'])){// will be always checked in backend
                $GalleryID = $_GET['option_id'];
            }
        }

        if(empty($GalleryID)){
            return;
        }

        if(empty($wp_upload_dir)){
            $wp_upload_dir = wp_upload_dir();
        }

        // !IMPORTANT when cg_create new gallery then no execution!!!!!
        if(!empty($_POST['cg_create']) || !empty($_GET['cg_create'])){
            return;
        }

        $cgOptionsDir = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json';
        $cgSwitchedFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/cg-switched.txt';
        if(!file_exists($cgSwitchedFile)){
            // do not remove p_cgal1ery_pro_version_main_key, has to be done for each gallery
            $p_cgal1ery_pro_version_main_key = cg_get_blog_option( 1,"p_cgal1ery_pro_version_main_key");
            if(!empty($p_cgal1ery_pro_version_main_key)){
                if(strpos($p_cgal1ery_pro_version_main_key, 'v2')===0){// will be only executed if key is v2 version and cg-switched.txt file not available in the gallery

                    $cgOptionsFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-options.json';
                    if(!file_exists($cgOptionsFile)===0){// will be done for sure this condition and logic in it
                        if(!is_dir($cgOptionsDir)){
                            mkdir($cgOptionsDir,0755,true);
                        }
                        $cgSwitchedFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/cg-switched.txt';
                        file_put_contents($cgSwitchedFile,time());
                        return;
                    }

                    global $wpdb;
                    $tablenameOptions = $wpdb->prefix . "contest_gal1ery_options";
                    $tablename_pro_options = $wpdb->prefix . "contest_gal1ery_pro_options";
                    $tablename_form_input = $wpdb->prefix . "contest_gal1ery_f_input";
                    $tablename_create_user_form = $wpdb->prefix . "contest_gal1ery_create_user_form";
                    $tablename_mail_confirmation = $wpdb->prefix . "contest_gal1ery_mail_confirmation";
                    $tablename_registry_and_login_options = $wpdb->base_prefix . "contest_gal1ery_registry_and_login_options";

                    // reset options

                    $wpdb->update(
                        "$tablename_registry_and_login_options",
                        array('LostPasswordMailActive' => 0),
                        array('GeneralID' => 1),
                        array('%d'),
                        array('%d')
                    );

                    $wpdb->update(
                        "$tablename_mail_confirmation",
                        array('SendConfirm' => 0),
                        array('GalleryID' => $GalleryID),
                        array('%d'),
                        array('%d')
                    );

                    $options = array('ContestEnd' => 0, 'ContestStart' => 0, 'CheckLogin' => 0, 'HideUntilVote' => 0,
                        'VotesPerUser' => 0, 'ShowOnlyUsersVotes' => 0, 'InformAdmin' => 0, 'Inform' => 0, 'IpBlock' => 0,
                        'CheckIp' => 1,  'CheckCookie' => 0);
                    $optionsParameters = array('%d', '%d', '%d', '%d',
                        '%d', '%d', '%d', '%d', '%d',
                        '%d', '%d'
                        );

                    $wpdb->update(
                        "$tablenameOptions",
                        $options,
                        array('id' => $GalleryID),
                        $optionsParameters,
                        array('%d')
                    );
                    $options['ShowExifDateTimeOriginal'] = 0; // add this additionally, only json option

                    $optionsPro = array('VotesPerCategory' => 0, 'RegUserMaxUpload' => 0, 'RegUserGalleryOnly' => 0, 'ShowNickname' => 0,
                        'VoteMessageSuccessActive' => 0, 'VoteMessageWarningActive' => 0, 'MinusVote' => 0, 'ForwardAfterLoginUrlCheck' => 0, 'ForwardAfterLoginTextCheck' => 0, 'VotesInTime' => 0,
                        'HideRegFormAfterLogin' => 0, 'HideRegFormAfterLoginShowTextInstead' => 0, 'FbLikeNoShare' => 0, 'FbLikeOnlyShare' => 0,'VoteNotOwnImage' => 0,
                        'RegMailOptional' => 0, 'CustomImageName' => 0, 'CommNoteActive' => 0, 'ShowProfileImage' => 0,
                        'AllowUploadPNG' => 0, 'AllowUploadGIF' => 0,'AdditionalFilesCount' => 2, 'ReviewComm' => 0,'InformAdminAllowActivateDeactivate' => 0
                        );

                    $optionsProParameters = array('%d', '%d', '%d', '%d',
                        '%d', '%d', '%d', '%d', '%d','%d',
                        '%d', '%d', '%d', '%d', '%d',
                        '%d', '%d', '%d', '%d',
                        '%d', '%d', '%d', '%d', '%d');

                    $wpdb->update(
                        "$tablename_pro_options",
                        $optionsPro,
                        array('GalleryID' => $GalleryID),
                        $optionsProParameters,
                        array('%d')
                    );

                    $optionsSummerized = [];
                    $optionsSummerized[] = $options;
                    $optionsSummerized[] = $optionsPro;

                    $cgOptionsFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-options.json';
                    $cgOptionsFileArray = json_decode(file_get_contents($cgOptionsFile),true);

                    foreach ($optionsSummerized as $optionsToLoop) {
                        foreach ($optionsToLoop as $key => $value) {
                            if(isset($cgOptionsFileArray['general'])){
                                if(isset($cgOptionsFileArray['general'][$key])){$cgOptionsFileArray['general'][$key] = $value;}
                            }
                            if(isset($cgOptionsFileArray['pro'])){
                                if(isset($cgOptionsFileArray['pro'][$key])){$cgOptionsFileArray['pro'][$key] = $value;}
                            }
                            if(isset($cgOptionsFileArray[$GalleryID])){
                                if(isset($cgOptionsFileArray[$GalleryID]['general'])){
                                    if(isset($cgOptionsFileArray[$GalleryID]['general'][$key])){$cgOptionsFileArray[$GalleryID]['general'][$key] = $value;}
                                }
                            }
                            if(isset($cgOptionsFileArray[$GalleryID])){
                                if(isset($cgOptionsFileArray[$GalleryID]['pro'])){
                                    if(isset($cgOptionsFileArray[$GalleryID]['pro'][$key])){$cgOptionsFileArray[$GalleryID]['pro'][$key] = $value;}
                                }
                            }
                            if(isset($cgOptionsFileArray[$GalleryID.'-u'])){
                                if(isset($cgOptionsFileArray[$GalleryID.'-u']['general'])){
                                    if(isset($cgOptionsFileArray[$GalleryID.'-u']['general'][$key])){$cgOptionsFileArray[$GalleryID.'-u']['general'][$key] = $value;}
                                }
                            }
                            if(isset($cgOptionsFileArray[$GalleryID.'-u'])){
                                if(isset($cgOptionsFileArray[$GalleryID.'-u']['pro'])){
                                    if(isset($cgOptionsFileArray[$GalleryID.'-u']['pro'][$key])){$cgOptionsFileArray[$GalleryID.'-u']['pro'][$key] = $value;}
                                }
                            }
                            if(isset($cgOptionsFileArray[$GalleryID.'-w'])){
                                if(isset($cgOptionsFileArray[$GalleryID.'-w']['general'])){
                                    if(isset($cgOptionsFileArray[$GalleryID.'-w']['general'][$key])){$cgOptionsFileArray[$GalleryID.'-w']['general'][$key] = $value;}
                                }
                            }
                            if(isset($cgOptionsFileArray[$GalleryID.'-w'])){
                                if(isset($cgOptionsFileArray[$GalleryID.'-w']['pro'])){
                                    if(isset($cgOptionsFileArray[$GalleryID.'-w']['pro'][$key])){$cgOptionsFileArray[$GalleryID.'-w']['pro'][$key] = $value;}
                                }
                            }
                            if(isset($cgOptionsFileArray[$GalleryID.'-nv'])){
                                if(isset($cgOptionsFileArray[$GalleryID.'-nv']['general'])){
                                    if(isset($cgOptionsFileArray[$GalleryID.'-nv']['general'][$key])){$cgOptionsFileArray[$GalleryID.'-nv']['general'][$key] = $value;}
                                }
                            }
                            if(isset($cgOptionsFileArray[$GalleryID.'-nv'])){
                                if(isset($cgOptionsFileArray[$GalleryID.'-nv']['pro'])){
                                    if(isset($cgOptionsFileArray[$GalleryID.'-nv']['pro'][$key])){$cgOptionsFileArray[$GalleryID.'-nv']['pro'][$key] = $value;}
                                }
                            }
                        }
                    }

                    file_put_contents($cgOptionsFile,json_encode($cgOptionsFileArray));

                    //  update registry fields, active 0 and make them hide
                    $hideActiveNotProFieldsQuery = "UPDATE $tablename_create_user_form SET Active='0' WHERE GalleryID = $GalleryID AND (Field_Type = 'user-check-agreement-field' OR Field_Type = 'user-html-field')";
                    $wpdb->query($hideActiveNotProFieldsQuery);

                    //  update input fields, active 0 and make them hide
                    $hideActiveNotProFieldsQuery = "UPDATE $tablename_form_input SET Active='0' WHERE GalleryID = $GalleryID AND (Field_Type = 'date-f' OR Field_Type = 'email-f'  OR Field_Type = 'check-f'   OR Field_Type = 'html-f')";
                    $wpdb->query($hideActiveNotProFieldsQuery);

                    do_action('cg_json_upload_form',$GalleryID);
                    do_action('cg_json_upload_form_info_data_files',$GalleryID,null);
                    do_action('cg_json_single_view_order',$GalleryID);


                    // correct fb html pages eventually
                    $searchDataShare = 'data-share="false"';
                    $replaceDataShare = 'data-share="true"';
                    $searchClass = 'class="fb-share-button"';
                    $replaceClass = 'class="fb-like"';
                    $searchDataLayout = 'data-layout="button"';
                    $replaceDataLayout = 'data-layout="button_count"';

                    $htmlFiles = glob($wp_upload_dir['basedir'] . '/contest-gallery/gallery-id-' . $GalleryID . '/*.html');

                    foreach ($htmlFiles as $htmlFile) {
                        $fp = fopen($htmlFile, 'r');
                        $htmlFileData = fread($fp, filesize($htmlFile));
                        fclose($fp);

                        $htmlFileData = str_replace($searchDataShare, $replaceDataShare, $htmlFileData);
                        $htmlFileData = str_replace($searchClass, $replaceClass, $htmlFileData);
                        $htmlFileData = str_replace($searchDataLayout, $replaceDataLayout, $htmlFileData);

                        $fp = fopen($htmlFile, 'w');
                        fwrite($fp, $htmlFileData);
                        fclose($fp);
                    }

                }
            }

            if(!is_dir($cgOptionsDir)){
                mkdir($cgOptionsDir,0755,true);
            }

            // will be set in normal version generally, removed in pro
             file_put_contents($cgSwitchedFile,time());

        }

    }
}
###NORMAL---END###