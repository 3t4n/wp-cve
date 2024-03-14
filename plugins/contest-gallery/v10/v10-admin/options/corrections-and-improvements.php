<!--<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>-->
<?php

// Path to jquery Lightbox Script 

global $wpdb;

if(isset($_GET['option_id'])){
    $GalleryID = absint($_GET['option_id']);
}else if(isset($_POST['option_id'])){
    $GalleryID = absint($_POST['option_id']);
}

$tablename = $wpdb->prefix . "contest_gal1ery";
$tablename_ip = $wpdb->prefix . "contest_gal1ery_ip";
$tablenameOptions = $wpdb->prefix . "contest_gal1ery_options";
$tablename_options_input = $wpdb->prefix . "contest_gal1ery_options_input";
$tablename_options_visual = $wpdb->prefix . "contest_gal1ery_options_visual";
$tablename_form_input = $wpdb->prefix . "contest_gal1ery_f_input";
$tablename_mail_admin = $wpdb->prefix . "contest_gal1ery_mail_admin";
$tablenameemail = $wpdb->prefix . "contest_gal1ery_mail";
$tablename_pro_options = $wpdb->prefix . "contest_gal1ery_pro_options";
//$tablename_mail_gallery = $wpdb->prefix . "contest_gal1ery_mail_gallery";
$tablename_mail_confirmation = $wpdb->prefix . "contest_gal1ery_mail_confirmation";
$table_usermeta = $wpdb->base_prefix . "usermeta";
$table_posts = $wpdb->prefix."posts";
$table_users = $wpdb->base_prefix."users";
$tablename_wp_pages = $wpdb->base_prefix."contest_gal1ery_wp_pages";
$tablename_entries = $wpdb->base_prefix."contest_gal1ery_entries";

require_once(dirname(__FILE__) . "/../nav-menu.php");

$upload_dir = wp_upload_dir();
$uploadFolder = wp_upload_dir();

//$options = $wpdb->get_results( "SELECT * FROM $tablename WHERE GalleryID = '$GalleryID'" );
$proOptions = $wpdb->get_row( "SELECT * FROM $tablename_pro_options WHERE GalleryID = '$GalleryID'" );
$options = $wpdb->get_row( "SELECT * FROM $tablenameOptions WHERE id = '$GalleryID'" );

$DataShare = ($proOptions->FbLikeNoShare==1) ? 'false' : 'true';
$DataClass = ($proOptions->FbLikeOnlyShare==1) ? 'fb-share-button' : 'fb-like';
$DataLayout = ($proOptions->FbLikeOnlyShare==1) ? 'button' : 'button_count';

// Correct 1 from HERE

$correctStatusText1 = 'Correct';
$correctStatusClass1 = '';

$thumbSizesWp = array();
$thumbSizesWp['thumbnail_size_w'] = get_option("thumbnail_size_w");
$thumbSizesWp['medium_size_w'] = get_option("medium_size_w");
$thumbSizesWp['large_size_w'] = get_option("large_size_w");

if(isset($_POST['action_correct_deleted_for_frontend'])){

    $selectSQL = $wpdb->get_results( "SELECT id FROM $tablename WHERE GalleryID = '$GalleryID' AND Active = '1'" );

    if(!empty($selectSQL)){

        $idsArray = array();

        foreach ($selectSQL as $rowObject){

            $idsArray[] = $rowObject->id;

        }

        $jsonFile = $upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/json/".$GalleryID."-images.json";
        $fp = fopen($jsonFile, 'r');
        $imageArray = json_decode(fread($fp, filesize($jsonFile)),true);
        fclose($fp);

        foreach ($imageArray as $imageId => $imageDataArray){

            if(!in_array($imageId,$idsArray)){

                if(file_exists($upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/".$imageDataArray['Timestamp']."_".$imageDataArray['NamePic']."413.html")){
                    unlink($upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/".$imageDataArray['Timestamp']."_".$imageDataArray['NamePic']."413.html");
                }
                if(file_exists($upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/json/image-data/image-data-".$imageId.".json")){
                    unlink($upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/json/image-data/image-data-".$imageId.".json");
                }
                if(file_exists($upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/json/image-comments/image-comments-".$imageId.".json")){
                    unlink($upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/json/image-comments/image-comments-".$imageId.".json");
                }
                if(file_exists($upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/json/image-info/image-info-".$imageId.".json")){
                    unlink($upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/json/image-info/image-info-".$imageId.".json");
                }

                if(!empty($imageArray[$imageId])){
                    unset($imageArray[$imageId]);
                }

            }

        }

        // set image data, das ganze gesammelte
        $jsonFile = $upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-images.json';
        $fp = fopen($jsonFile, 'w');
        fwrite($fp, json_encode($imageArray));
        fclose($fp);


        $tstampFile = $upload_dir["basedir"]."/contest-gallery/gallery-id-$GalleryID/json/$GalleryID-gallery-tstamp.json";

        $fp = fopen($tstampFile, 'w');
        fwrite($fp, time());
        fclose($fp);

        $correctStatusText1 = 'Corrected';
        $correctStatusClass1 = 'cg_corrected';

    }

}

$correctStatusText2 = 'Correct';
$correctStatusClass2 = '';

if(isset($_POST['action_correct_information_for_frontend'])){

    //do_action('cg_json_upload_form_info_data_files',$GalleryID,null);
    cg_json_upload_form_info_data_files_new($GalleryID);
    $correctStatusText2 = 'Corrected';
    $correctStatusClass2 = 'cg_corrected';

}
$correctStatusText3 = 'Correct';
$correctStatusClass3 = '';

if(isset($_POST['action_correct_not_shown_for_frontend'])){

    $picsSQL = $wpdb->get_results( "SELECT DISTINCT $table_posts.*, $tablename.* FROM $table_posts, $tablename WHERE 
                                              ($tablename.GalleryID='$GalleryID' AND $tablename.Active='1' and $table_posts.ID = $tablename.WpUpload) OR 
                                              ($tablename.GalleryID='$GalleryID' AND $tablename.Active='1' AND $tablename.WpUpload = 0) 
                                          GROUP BY $tablename.id ORDER BY $tablename.id DESC");

    $imageArray = array();
    $RatingOverviewArray = cg_get_correct_rating_overview($GalleryID);

    // add all json files and generate images array
    foreach($picsSQL as $object){

        $imageArray = cg_create_json_files_when_activating($GalleryID,$object,$thumbSizesWp,$upload_dir,$imageArray,$options->Version,$RatingOverviewArray);

    }

    //cg_set_data_in_images_files_with_all_data($GalleryID,$imageArray);
	cg_json_upload_form_info_data_files_new($GalleryID);

    $correctStatusText3 = 'Corrected';
    $correctStatusClass3 = 'cg_corrected';

}//>>> SEEE DIV FOR IT HERE!!!!!
// $correctStatusText3 DIV FOR IT HERE
// $correctStatusClass3 DIV FOR IT HERE
/*    <div class="cg_corrections_container">
        <div class="cg_corrections_explanation">
            <div class="cg_corrections_title">Seeing not all images in frontend?</div>
            <div class="cg_corrections_description">If you missing images in frontend you can correct it here.</div>
        </div>
        <div class="cg_corrections_action $correctStatusClass3">
            <form method="POST" action="?page=".cg_get_version()."/index.php&amp;corrections_and_improvements=true&amp;option_id=$GalleryID">
                <input type="hidden" name="action_correct_not_shown_for_frontend" value="true">
                <input type="hidden" name="option_id" value="$GalleryID">
                <span class="cg_corrections_action_submit">$correctStatusText3</span>
            </form>
        </div>
    </div>    */

// Correct 4 from HERE


$correctStatusText4 = 'Nothing to repair';
$correctStatusClass4 = '';
$correctStatusTextFull4 = 'All required columns available!';


try {
    if(isset($_POST['action_check_and_correct_database'])){
        $i="";

        // try all updates here!
        include(__DIR__."/../../../update/update-check-new.php");
        $isJustCheck = true;
        include(__DIR__."/../../../update/update-check-new.php");

        if(!empty($columnsToRepairArray['hasColumnsToImprove'])){

            // unset here so processing does not stop
            unset($columnsToRepairArray['hasColumnsToImprove']);

            $correctStatusTextFull4 = '<span class="cg_database_improve_title">Please contact <a href="mailto:support@contest-gallery.com">support@contest-gallery.com</a><br>Copy and send following data with MySQL version:</span>';

            if ( function_exists( 'mysqli_connect' ) ) {
                $server_info = mysqli_get_server_info( $wpdb->dbh );
            }else{
                $server_info = mysql_get_server_info( $wpdb->dbh );
            }

            $correctStatusTextFull4 .= '<span class="cg_database_improve_mysql_version">MySQL version '.$wpdb->db_version().' - '.$server_info.'</span>';

            foreach($columnsToRepairArray as $tableName => $tableData){
                $correctStatusTextFull4 .= "<span class=\"cg_database_improve_table_name\">Table: $tableName</span><br>";
                $correctStatusTextFull4 .= "<table><tbody>";
                $correctStatusTextFull4 .= "<tr><th>Column</th><th>Status</th></tr>";
                foreach($tableData as $columnData){
                    $statusText = '';
                    if(isset($columnData['IsNoColumn'])){
                        $statusText = $errorsArray[$columnData['ColumnName']];
                    }
                    if(isset($columnData['IsColumnCouldNotBeModified'])){
                        $statusText = $errorsArray[$columnData['ColumnName']];
                    }
                    $correctStatusTextFull4 .= "<tr><td>".$columnData['ColumnName']."</td><td>$statusText</td></tr>";
                }
                $correctStatusTextFull4 .= "</table></tbody>";
            }

            $correctStatusText4 = 'Repair';
            $correctStatusClass4 = '';

        }else{
            $correctStatusText4 = 'Repaired';
            $correctStatusClass4 = 'cg_corrected';
        }

    }else{
        $i="";

        $isJustCheck = true;
        include(__DIR__."/../../../update/update-check-new.php");

        if(!empty($columnsToRepairArray['hasColumnsToImprove'])){

            // unset here so processing does not stop
            unset($columnsToRepairArray['hasColumnsToImprove']);

            $correctStatusTextFull4 = '<span class="cg_database_improve_title">Table data needs to be repaired</span>';

            foreach($columnsToRepairArray as $tableName => $tableData){
                $correctStatusTextFull4 .= "<span class=\"cg_database_improve_table_name\">Table: $tableName</span><br>";
                $correctStatusTextFull4 .= "<table><tbody>";
                $correctStatusTextFull4 .= "<tr><th>Column</th><th>Status</th></tr>";
                foreach($tableData as $columnData){
                    $statusText = '';
                    if(isset($columnData['IsNoColumn'])){
                        $statusText = 'Not created';
                    }
                    if(isset($columnData['IsColumnCouldNotBeModified'])){
                        $statusText = 'Modify: from '.$columnData['ColumnTypeCurrent'].' to '.$columnData['ColumnTypeRequired'].'';
                    }
                    $correctStatusTextFull4 .= "<tr><td>".$columnData['ColumnName']."</td><td>$statusText</td></tr>";
                }
                $correctStatusTextFull4 .= "</table></tbody>";
            }

            $correctStatusText4 = 'Repair';
            $correctStatusClass4 = '';

        }else{

            $correctStatusText4 = 'Nothing to repair';
            $correctStatusClass4 = 'cg_corrected';

        }

    }

}catch (Exception $e) {
    $correctStatusTextFull4 = '<span class="cg_database_improve_title">Please contact <a href="mailto:support@contest-gallery.com">support@contest-gallery.com</a><br>Copy and send following data:</span>';
    $correctStatusTextFull4 .= '<span class="cg_database_error_message">'.$e->getMessage().'</span>';

    $correctStatusText4 = 'Repair';
    $correctStatusClass4 = '';
}

// Correct 5 from HERE
$correctStatusText5 = 'Correct';
$correctStatusClass5 = '';
$correctContent5 = '';

if($proOptions->IsModernFiveStar == 0 AND ($options->AllowRating == 1 OR ($options->AllowRating>=12 && $options->AllowRating<=20))){

    // this is only in case five star has to be corrected, old logic, almoust irrelevant, if it has to be corrected then old FIVE star was activated, no other option was possible
    if(isset($_POST['action_correct_to_modern_five_star'])){

        $allRatingsPerPid = $wpdb->get_results( "SELECT pid, SUM(CASE 
             WHEN $tablename_ip.Rating = '1' THEN 1
             ELSE 0
           END) AS CountR1,
       SUM(CASE 
             WHEN $tablename_ip.Rating='2' THEN 1
             ELSE 0
           END) AS CountR2,
       SUM(CASE 
             WHEN $tablename_ip.Rating='3' THEN 1
             ELSE 0
           END) AS CountR3,
       SUM(CASE 
             WHEN $tablename_ip.Rating='4' THEN 1
             ELSE 0
           END) AS CountR4,
       SUM(CASE 
             WHEN $tablename_ip.Rating='5' THEN 1
             ELSE 0
           END) AS CountR5
            FROM $tablename_ip WHERE GalleryID = '$GalleryID' AND Rating > 0 GROUP BY pid ORDER BY pid DESC, rating DESC" );

        if(!empty($allRatingsPerPid)){

            $querySETrowCountR1 = 'UPDATE ' . $tablename . ' SET CountR1 = CASE';
            $querySETaddRowCountR1 = ' ELSE CountR1 END WHERE (id) IN (';

            $querySETrowCountR2 = 'UPDATE ' . $tablename . ' SET CountR2 = CASE';
            $querySETaddRowCountR2 = ' ELSE CountR1 END WHERE (id) IN (';

            $querySETrowCountR3 = 'UPDATE ' . $tablename . ' SET CountR3 = CASE';
            $querySETaddRowCountR3 = ' ELSE CountR1 END WHERE (id) IN (';

            $querySETrowCountR4 = 'UPDATE ' . $tablename . ' SET CountR4 = CASE';
            $querySETaddRowCountR4 = ' ELSE CountR1 END WHERE (id) IN (';

            $querySETrowCountR5 = 'UPDATE ' . $tablename . ' SET CountR5 = CASE';
            $querySETaddRowCountR5 = ' ELSE CountR1 END WHERE (id) IN (';

            $jsonFile = $upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/json/".$GalleryID."-images-sort-values.json";
            $fp = fopen($jsonFile, 'r');
            $sortValuesArray = json_decode(fread($fp, filesize($jsonFile)),true);
            fclose($fp);

            foreach($allRatingsPerPid as $object){

                $jsonFile = $upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/json/image-data/image-data-".$object->pid.".json";

                // check only active!!!!
                if(file_exists($jsonFile)){
                    $fp = fopen($jsonFile, 'r');
                    $imageArray = json_decode(fread($fp, filesize($jsonFile)),true);
                    fclose($fp);

                    $imageArray['CountR1'] = intval($object->CountR1);
                    $sortValuesArray[$object->pid]['CountR1'] = intval($object->CountR1);
                    $querySETrowCountR1 .= " WHEN (id = $object->pid) THEN '".$object->CountR1."'";
                    $querySETaddRowCountR1 .= "($object->pid), ";

                    $imageArray['CountR2'] = intval($object->CountR2);
                    $sortValuesArray[$object->pid]['CountR2'] = intval($object->CountR2);
                    $querySETrowCountR2 .= " WHEN (id = $object->pid) THEN '".$object->CountR2."'";
                    $querySETaddRowCountR2 .= "($object->pid), ";

                    $imageArray['CountR3'] = intval($object->CountR3);
                    $sortValuesArray[$object->pid]['CountR3'] = intval($object->CountR3);
                    $querySETrowCountR3 .= " WHEN (id = $object->pid) THEN '".$object->CountR3."'";
                    $querySETaddRowCountR3 .= "($object->pid), ";

                    $imageArray['CountR4'] = intval($object->CountR4);
                    $sortValuesArray[$object->pid]['CountR4'] = intval($object->CountR4);
                    $querySETrowCountR4 .= " WHEN (id = $object->pid) THEN '".$object->CountR4."'";
                    $querySETaddRowCountR4 .= "($object->pid), ";

                    $imageArray['CountR5'] = intval($object->CountR5);
                    $sortValuesArray[$object->pid]['CountR5'] = intval($object->CountR5);
                    $querySETrowCountR5 .= " WHEN (id = $object->pid) THEN '".$object->CountR5."'";
                    $querySETaddRowCountR5 .= "($object->pid), ";

                    $jsonFile = $upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/json/image-data/image-data-".$object->pid.".json";
                    $fp = fopen($jsonFile, 'w');
                    fwrite($fp, json_encode($imageArray));
                    fclose($fp);
                }

            }

            // add 0 to all which had no rating
            foreach($sortValuesArray as $id => $sortValues){
                if(empty($sortValues['CountR1'])){$sortValuesArray[$id]['CountR1'] = 0;}
                if(empty($sortValues['CountR2'])){$sortValuesArray[$id]['CountR2'] = 0;}
                if(empty($sortValues['CountR3'])){$sortValuesArray[$id]['CountR3'] = 0;}
                if(empty($sortValues['CountR4'])){$sortValuesArray[$id]['CountR4'] = 0;}
                if(empty($sortValues['CountR5'])){$sortValuesArray[$id]['CountR5'] = 0;}
            }

            $jsonFile = $upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/json/".$GalleryID."-images-sort-values.json";
            $fp = fopen($jsonFile, 'w');
            fwrite($fp, json_encode($sortValuesArray));
            fclose($fp);

            $querySETaddRowCountR1 = substr($querySETaddRowCountR1,0,-2);
            $querySETaddRowCountR1 .= ")";
            $querySETrowCountR1 .= $querySETaddRowCountR1;
            $wpdb->query($querySETrowCountR1);

            $querySETaddRowCountR2 = substr($querySETaddRowCountR2,0,-2);
            $querySETaddRowCountR2 .= ")";
            $querySETrowCountR2 .= $querySETaddRowCountR2;
            $wpdb->query($querySETrowCountR2);

            $querySETaddRowCountR3 = substr($querySETaddRowCountR3,0,-2);
            $querySETaddRowCountR3 .= ")";
            $querySETrowCountR3 .= $querySETaddRowCountR3;
            $wpdb->query($querySETrowCountR3);

            $querySETaddRowCountR4 = substr($querySETaddRowCountR4,0,-2);
            $querySETaddRowCountR4 .= ")";
            $querySETrowCountR4 .= $querySETaddRowCountR4;
            $wpdb->query($querySETrowCountR4);

            $querySETaddRowCountR5 = substr($querySETaddRowCountR5,0,-2);
            $querySETaddRowCountR5 .= ")";
            $querySETrowCountR5 .= $querySETaddRowCountR5;
            $wpdb->query($querySETrowCountR5);

        }

        $wpdb->update(
            "$tablename_pro_options",
            array('IsModernFiveStar' => '1'),
            array('GalleryID' => $GalleryID),
            array('%d'),
            array('%d')
        );

        $jsonFile = $upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/json/".$GalleryID."-options.json";
        $optionsArray = json_decode(file_get_contents($jsonFile),true);

        $optionsArray['pro']['IsModernFiveStar'] = 1;
        if(!empty($optionsArray[$GalleryID])){$optionsArray[$GalleryID]['pro']['IsModernFiveStar'] = 1;}
        if(!empty($optionsArray[$GalleryID.'-u'])){$optionsArray[$GalleryID.'-u']['pro']['IsModernFiveStar'] = 1;}
        if(!empty($optionsArray[$GalleryID.'-nv'])){$optionsArray[$GalleryID.'-nv']['pro']['IsModernFiveStar'] = 1;}
        if(!empty($optionsArray[$GalleryID.'-w'])){$optionsArray[$GalleryID.'-w']['pro']['IsModernFiveStar'] = 1;}

        $jsonFile = $upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/json/".$GalleryID."-options.json";
        file_put_contents($jsonFile,json_encode($optionsArray));

        // !IMPORTANT otherwise indexed db will be not reloaded
        $jsonFile = $upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/json/".$GalleryID."-gallery-tstamp.json";
        file_put_contents($jsonFile, json_encode(time()));

        $correctStatusText5 = 'Corrected<br>You need to reload the gallery in frontend<br>Check translation files or options for average sorting translation';
        $correctStatusClass5 = 'cg_corrected';

    }

    $correctContent5 = '<div class="cg_corrections_container">
        <div class="cg_corrections_explanation">
            <div class="cg_corrections_title">Your gallery uses old 5 stars look</div>
            <div class="cg_corrections_description">Correct it to see percentage for each star by hovering rating and to be able to sort by average rating in frontend.</div>
        </div>
        <div class="cg_corrections_action '.$correctStatusClass5.'">                
            <form method="POST" action="?page='.cg_get_version().'/index.php&amp;corrections_and_improvements=true&amp;option_id='.$GalleryID.'" data-cg-submit-message="Corrected"   class="cg_load_backend_submit">
                <input type="hidden" name="action_correct_to_modern_five_star" value="true">
                <input type="hidden" name="option_id" value="'.$GalleryID.'">
                <span class="cg_corrections_action_submit">'.$correctStatusText5.'</span>
            </form>
        </div>
        </div>';

}


$correctStatusText6 = 'Repair';
$correctStatusClass6 = '';
if(isset($_POST['action_correct_not_visible_for_frontend'])){

    // check if parent pages exists and required before
    if(floatval($options->Version)>=21){

        $jsonFile = $upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/json/".$GalleryID."-options.json";
        $optionsArray = json_decode(file_get_contents($jsonFile),true);

        $WpPageParent = get_post( $options->WpPageParent );
        $WpPageParentUser = get_post( $options->WpPageParentUser );
        $WpPageParentNoVoting = get_post( $options->WpPageParentNoVoting );
        $WpPageParentWinner = get_post( $options->WpPageParentWinner );

        $tag = get_term_by('slug', ' contest-gallery-plugin-tag','post_tag');
        if(empty($tag)){
            $tag = cg_create_contest_gallery_plugin_tag();
            $term_id = $tag['term_id'];
        }else{
            $term_id = $tag->term_id;
        }

        $hasParentPageRepaired = false;
        if(empty($WpPageParent)){
            $hasParentPageRepaired = true;
            // cg_gallery shortcode
            $array = [
                'post_title'=>'Contest Gallery ID '.$options->id,
                'post_type'=>'contest-gallery',
                'post_content'=>"<!-- wp:shortcode -->"."\r\n".
                    "<!--This is a comment: cg_galley... shortcode is required to display Contest Gallery on a Contest Gallery Custom Post Type page. You can place your own content before and after this shortcode, whatever you like. You can place cg_gallery... shortcode also on any other of your pages.-->"."\r\n".
                    "[cg_gallery id=\"$GalleryID\"]"."\r\n".
                    "<!-- /wp:shortcode -->",
                'post_mime_type'=>'contest-gallery-plugin-page',
                'post_status'=>'publish',
            ];
            $WpPageParent = wp_insert_post($array);
            $options->WpPageParent = $WpPageParent;
            $wpdb->update(
                "$tablenameOptions",
                array('WpPageParent' => $WpPageParent),
                array('id' => $options->id),
                array('%d'),
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

            $picsSQL = $wpdb->get_results( "SELECT DISTINCT id, WpPage FROM $tablename WHERE GalleryID = '$GalleryID'");

            $querySETrowForPostIds = 'UPDATE ' . $table_posts . ' SET post_parent = CASE ID ';
            $querySETaddRowForPostIds = ' ELSE post_parent END WHERE ID IN (';
            $queryArgsArray = [];
            $queryAddArgsArray = [];
            $queryArgsCounter = 0;

            foreach ($picsSQL as $rowObject) {
                $querySETrowForPostIds .= " WHEN %d THEN %d";
                $querySETaddRowForPostIds .= "%d,";
                $queryArgsArray[] = $rowObject->WpPage;
                $queryArgsArray[] = $WpPageParent;
                $queryAddArgsArray[] = $rowObject->WpPage;
                $queryArgsCounter++;
            }

            // ic = i counter
            for ($ic = 0;$ic<$queryArgsCounter;$ic++){
                $queryArgsArray[] =$queryAddArgsArray[$ic];
            }

            $querySETaddRowForPostIds = substr($querySETaddRowForPostIds,0,-1);
            $querySETaddRowForPostIds .= ")";

            $querySETrowForPostIds .= $querySETaddRowForPostIds;
            $wpdb->query($wpdb->prepare($querySETrowForPostIds,$queryArgsArray));

            $optionsArray['general']['WpPageParent'] = $WpPageParent;
            if(!empty($optionsArray[$GalleryID])){$optionsArray[$GalleryID]['general']['WpPageParent'] = $WpPageParent;}
            if(!empty($optionsArray[$GalleryID.'-u'])){$optionsArray[$GalleryID.'-u']['general']['WpPageParent'] = $WpPageParent;}
            if(!empty($optionsArray[$GalleryID.'-nv'])){$optionsArray[$GalleryID.'-nv']['general']['WpPageParent'] = $WpPageParent;}
            if(!empty($optionsArray[$GalleryID.'-w'])){$optionsArray[$GalleryID.'-w']['general']['WpPageParent'] = $WpPageParent;}

        }

        // cg_gallery_user shortcode
        if(empty($WpPageParentUser)){
            $hasParentPageRepaired = true;
            $array = [
                'post_title'=>'Contest Gallery ID '.$options->id.' user',
                'post_type'=>'contest-gallery',
                'post_content'=>"<!-- wp:shortcode -->"."\r\n".
                    "<!--This is a comment: cg_galley... shortcode is required to display Contest Gallery on a Contest Gallery Custom Post Type page. You can place your own content before and after this shortcode, whatever you like. You can place cg_gallery... shortcode also on any other of your pages.-->"."\r\n".
                    "[cg_gallery_user id=\"$GalleryID\"]"."\r\n".
                    "<!-- /wp:shortcode -->",
                'post_mime_type'=>'contest-gallery-plugin-page',
                'post_status'=>'publish',
            ];

            $WpPageParentUser = wp_insert_post($array);
            $options->WpPageParentUser = $WpPageParentUser;
            $wpdb->update(
                "$tablenameOptions",
                array('WpPageParentUser' => $WpPageParentUser),
                array('id' => $options->id),
                array('%d'),
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
                '',$WpPageParentUser
            ) );

            $picsSQL = $wpdb->get_results( "SELECT DISTINCT id, WpPageUser FROM $tablename WHERE GalleryID = '$GalleryID'");

            $querySETrowForPostIds = 'UPDATE ' . $table_posts . ' SET post_parent = CASE ID ';
            $querySETaddRowForPostIds = ' ELSE post_parent END WHERE ID IN (';
            $queryArgsArray = [];
            $queryAddArgsArray = [];
            $queryArgsCounter = 0;

            foreach ($picsSQL as $rowObject) {
                $querySETrowForPostIds .= " WHEN %d THEN %d";
                $querySETaddRowForPostIds .= "%d,";
                $queryArgsArray[] = $rowObject->WpPageUser;
                $queryArgsArray[] = $WpPageParentUser;
                $queryAddArgsArray[] = $rowObject->WpPageUser;
                $queryArgsCounter++;
            }

            // ic = i counter
            for ($ic = 0;$ic<$queryArgsCounter;$ic++){
                $queryArgsArray[] =$queryAddArgsArray[$ic];
            }

            $querySETaddRowForPostIds = substr($querySETaddRowForPostIds,0,-1);
            $querySETaddRowForPostIds .= ")";

            $querySETrowForPostIds .= $querySETaddRowForPostIds;
            $wpdb->query($wpdb->prepare($querySETrowForPostIds,$queryArgsArray));

            $optionsArray['general']['WpPageParentUser'] = $WpPageParentUser;
            if(!empty($optionsArray[$GalleryID])){$optionsArray[$GalleryID]['general']['WpPageParentUser'] = $WpPageParentUser;}
            if(!empty($optionsArray[$GalleryID.'-u'])){$optionsArray[$GalleryID.'-u']['general']['WpPageParentUser'] = $WpPageParentUser;}
            if(!empty($optionsArray[$GalleryID.'-nv'])){$optionsArray[$GalleryID.'-nv']['general']['WpPageParentUser'] = $WpPageParentUser;}
            if(!empty($optionsArray[$GalleryID.'-w'])){$optionsArray[$GalleryID.'-w']['general']['WpPageParentUser'] = $WpPageParentUser;}

        }

        // cg_gallery_no_voting shortcode
        if(empty($WpPageParentNoVoting)){
            $hasParentPageRepaired = true;
            $array = [
                'post_title'=>'Contest Gallery ID '.$options->id.' no voting',
                'post_type'=>'contest-gallery',
                'post_content'=>"<!-- wp:shortcode -->"."\r\n".
                    "<!--This is a comment: cg_galley... shortcode is required to display Contest Gallery on a Contest Gallery Custom Post Type page. You can place your own content before and after this shortcode, whatever you like. You can place cg_gallery... shortcode also on any other of your pages.-->"."\r\n".
                    "[cg_gallery_no_voting id=\"$GalleryID\"]"."\r\n".
                    "<!-- /wp:shortcode -->",
                'post_mime_type'=>'contest-gallery-plugin-page',
                'post_status'=>'publish',
            ];

            $WpPageParentNoVoting = wp_insert_post($array);
            $options->WpPageParentNoVoting = $WpPageParentNoVoting;
            $wpdb->update(
                "$tablenameOptions",
                array('WpPageParentNoVoting' => $WpPageParentNoVoting),
                array('id' => $options->id),
                array('%d'),
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
                '',$WpPageParentNoVoting
            ) );

            $picsSQL = $wpdb->get_results( "SELECT DISTINCT id, WpPageNoVoting FROM $tablename WHERE GalleryID = '$GalleryID'");

            $querySETrowForPostIds = 'UPDATE ' . $table_posts . ' SET post_parent = CASE ID ';
            $querySETaddRowForPostIds = ' ELSE post_parent END WHERE ID IN (';
            $queryArgsArray = [];
            $queryAddArgsArray = [];
            $queryArgsCounter = 0;

            foreach ($picsSQL as $rowObject) {
                $querySETrowForPostIds .= " WHEN %d THEN %d";
                $querySETaddRowForPostIds .= "%d,";
                $queryArgsArray[] = $rowObject->WpPageNoVoting;
                $queryArgsArray[] = $WpPageParentNoVoting;
                $queryAddArgsArray[] = $rowObject->WpPageNoVoting;
                $queryArgsCounter++;
            }

            // ic = i counter
            for ($ic = 0;$ic<$queryArgsCounter;$ic++){
                $queryArgsArray[] =$queryAddArgsArray[$ic];
            }

            $querySETaddRowForPostIds = substr($querySETaddRowForPostIds,0,-1);
            $querySETaddRowForPostIds .= ")";

            $querySETrowForPostIds .= $querySETaddRowForPostIds;
            $wpdb->query($wpdb->prepare($querySETrowForPostIds,$queryArgsArray));

            $optionsArray['general']['WpPageParentNoVoting'] = $WpPageParentNoVoting;
            if(!empty($optionsArray[$GalleryID])){$optionsArray[$GalleryID]['general']['WpPageParentNoVoting'] = $WpPageParentNoVoting;}
            if(!empty($optionsArray[$GalleryID.'-u'])){$optionsArray[$GalleryID.'-u']['general']['WpPageParentNoVoting'] = $WpPageParentNoVoting;}
            if(!empty($optionsArray[$GalleryID.'-nv'])){$optionsArray[$GalleryID.'-nv']['general']['WpPageParentNoVoting'] = $WpPageParentNoVoting;}
            if(!empty($optionsArray[$GalleryID.'-w'])){$optionsArray[$GalleryID.'-w']['general']['WpPageParentNoVoting'] = $WpPageParentNoVoting;}

        }

        // cg_gallery_winner shortcode
        if(empty($WpPageParentWinner)){
            $hasParentPageRepaired = true;
            $array = [
                'post_title'=>'Contest Gallery ID '.$options->id.' winner',
                'post_type'=>'contest-gallery',
                'post_content'=>"<!-- wp:shortcode -->"."\r\n".
                    "<!--This is a comment: cg_galley... shortcode is required to display Contest Gallery on a Contest Gallery Custom Post Type page. You can place your own content before and after this shortcode, whatever you like. You can place cg_gallery... shortcode also on any other of your pages.-->"."\r\n".
                    "[cg_gallery_winner id=\"$GalleryID\"]"."\r\n".
                    "<!-- /wp:shortcode -->",
                'post_mime_type'=>'contest-gallery-plugin-page',
                'post_status'=>'publish',
            ];

            $WpPageParentWinner = wp_insert_post($array);
            $options->WpPageParentWinner = $WpPageParentWinner;
            $wpdb->update(
                "$tablenameOptions",
                array('WpPageParentWinner' => $WpPageParentWinner),
                array('id' => $options->id),
                array('%d'),
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
                '',$WpPageParentWinner
            ) );

            $picsSQL = $wpdb->get_results( "SELECT DISTINCT id, WpPageWinner FROM $tablename WHERE GalleryID = '$GalleryID'");

            $querySETrowForPostIds = 'UPDATE ' . $table_posts . ' SET post_parent = CASE ID ';
            $querySETaddRowForPostIds = ' ELSE post_parent END WHERE ID IN (';
            $queryArgsArray = [];
            $queryAddArgsArray = [];
            $queryArgsCounter = 0;

            foreach ($picsSQL as $rowObject) {
                $querySETrowForPostIds .= " WHEN %d THEN %d";
                $querySETaddRowForPostIds .= "%d,";
                $queryArgsArray[] = $rowObject->WpPageWinner;
                $queryArgsArray[] = $WpPageParentWinner;
                $queryAddArgsArray[] = $rowObject->WpPageWinner;
                $queryArgsCounter++;
            }

            // ic = i counter
            for ($ic = 0;$ic<$queryArgsCounter;$ic++){
                $queryArgsArray[] =$queryAddArgsArray[$ic];
            }

            $querySETaddRowForPostIds = substr($querySETaddRowForPostIds,0,-1);
            $querySETaddRowForPostIds .= ")";

            $querySETrowForPostIds .= $querySETaddRowForPostIds;
            $wpdb->query($wpdb->prepare($querySETrowForPostIds,$queryArgsArray));

            $optionsArray['general']['WpPageParentWinner'] = $WpPageParentWinner;
            if(!empty($optionsArray[$GalleryID])){$optionsArray[$GalleryID]['general']['WpPageParentWinner'] = $WpPageParentWinner;}
            if(!empty($optionsArray[$GalleryID.'-u'])){$optionsArray[$GalleryID.'-u']['general']['WpPageParentWinner'] = $WpPageParentWinner;}
            if(!empty($optionsArray[$GalleryID.'-nv'])){$optionsArray[$GalleryID.'-nv']['general']['WpPageParentWinner'] = $WpPageParentWinner;}
            if(!empty($optionsArray[$GalleryID.'-w'])){$optionsArray[$GalleryID.'-w']['general']['WpPageParentWinner'] = $WpPageParentWinner;}
        }

        if($hasParentPageRepaired){
            $jsonFile = $upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/json/".$GalleryID."-options.json";
            file_put_contents($jsonFile,json_encode($optionsArray));
        }

        // check if all WpPages existing, if not then recreate
        $picsSQL = $wpdb->get_results( "SELECT DISTINCT id, WpPage, WpPageUser, WpPageNoVoting, WpPageWinner, NamePic, WpUpload FROM $tablename WHERE GalleryID='$GalleryID' GROUP BY id ORDER BY id DESC");

        $IsForWpPageTitleInputId = $wpdb->get_var("SELECT id FROM $tablename_form_input WHERE GalleryID = '$GalleryID' AND IsForWpPageTitle=1");

        $collect = '';
        $collectForPostTitle = '';
        $WpPagesArray = [];
        $WpUploadsArray = [];
        $WpPageTitlesArrayFromInputForm = [];
        foreach ($picsSQL as $rowObject){
            $id = $rowObject->id;
            $WpPage = $rowObject->WpPage;
            $WpPageUser = $rowObject->WpPageUser;
            $WpPageNoVoting = $rowObject->WpPageNoVoting;
            $WpPageWinner = $rowObject->WpPageWinner;
            $WpUpload = $rowObject->WpUpload;
            $WpUploadsArray[$rowObject->id] = $WpUpload;
            if($collect == ''){
                $collect .= "ID = $WpPage OR ID = $WpPageUser OR ID = $WpPageNoVoting OR ID = $WpPageWinner";
            }else{
                $collect .= " OR ID = $WpPage OR ID = $WpPageUser OR ID = $WpPageNoVoting OR ID = $WpPageWinner";
            }
            if($collectForPostTitle == ''){
                $collectForPostTitle .= "ID = $WpUpload";
            }else{
                $collectForPostTitle .= " OR ID = $WpUpload";
            }
            if(!empty($WpPage)){$WpPagesArray[$WpPage] = ['WpPage' => $rowObject->id];}
            if(!empty($WpPageUser)){$WpPagesArray[$WpPageUser] = ['WpPageUser' => $rowObject->id];}
            if(!empty($WpPageNoVoting)){$WpPagesArray[$WpPageNoVoting] = ['WpPageNoVoting' => $rowObject->id];}
            if(!empty($WpPageWinner)){$WpPagesArray[$WpPageWinner] = ['WpPageWinner' => $rowObject->id];}

            if(!empty($IsForWpPageTitleInputId)){
                $ShortText = $wpdb->get_var("SELECT Short_Text FROM $tablename_entries WHERE pid = '$id' AND f_input_id=$IsForWpPageTitleInputId");
                if(!empty($ShortText)){
                    $WpPageTitlesArrayFromInputForm[$id] = $ShortText;
                }
            }
        }
        $post_titles_array = [];
        $WpPostTitles = $wpdb->get_results( "SELECT DISTINCT ID, post_title FROM $table_posts WHERE ($collectForPostTitle)");
        foreach ($WpPostTitles as $WpPostTitle){
            $post_titles_array[$WpPostTitle->ID] = $WpPostTitle->post_title;
        }

        $WpPagesIDs = $wpdb->get_results( "SELECT DISTINCT ID FROM $table_posts WHERE ($collect)");
        $WpPagesIDsArray = [];
        foreach ($WpPagesIDs as $WpPageRow){
            $WpPagesIDsArray[] = $WpPageRow->ID;
        }
        $WpPagesToRecreateArray = [];
        foreach ($WpPagesArray as $WpPage => $WpPageArray){
            if(in_array($WpPage,$WpPagesIDsArray)===false){
                $rowObjectID = $WpPageArray[key($WpPageArray)];
                if(!isset($WpPagesToRecreateArray[$rowObjectID])){
                    $WpPagesToRecreateArray[$rowObjectID] = [];
                }
                $WpPagesToRecreateArray[$rowObjectID][] = key($WpPageArray);
            }
        }

        foreach ($WpPagesToRecreateArray as $rowObjectID => $WpPageTypeNamesArray){
            if(!empty($WpPageTitlesArrayFromInputForm[$rowObjectID])){
                $post_title = $WpPageTitlesArrayFromInputForm[$rowObjectID];
            }else{
                $post_title = $post_titles_array[$WpUploadsArray[$rowObjectID]];
            }
            if(empty($post_title)){$post_title='entry';}// if both variants above not available then simply the word "entry" will be taken
            // cg_gallery shortcode
            if(in_array('WpPage',$WpPageTypeNamesArray)!==false){
                // cg_gallery shortcode
                $array = [
                    'post_title'=> $post_title,
                    'post_type'=>'contest-gallery',
                    'post_content'=>"<!-- wp:shortcode -->"."\r\n".
                        "<!--This is a comment: cg_galley... shortcode with entry id is required to display Contest Gallery entry on a Contest Gallery Custom Post Type entry page. You can place your own content before and after this shortcode, whatever you like. You can place cg_gallery... shortcode with entry_id also on any other of your pages. -->"."\r\n".
                        "[cg_gallery id=\"$GalleryID\" entry_id=\"$rowObjectID\"]"."\r\n".
                        "<!-- /wp:shortcode -->",
                    'post_mime_type'=>'contest-gallery-plugin-page',
                    'post_status'=>'publish',
                    'post_parent'=>$options->WpPageParent
                ];
                $WpPage = wp_insert_post($array);
                $wpdb->update(
                    "$tablename",
                    array('WpPage' => $WpPage),
                    array('id' => $rowObjectID),
                    array('%d'),
                    array('%d')
                );
            }
            // cg_gallery_user shortcode
            if(in_array('WpPageUser',$WpPageTypeNamesArray)!==false){
                // cg_gallery shortcode
                $array = [
                    'post_title'=> $post_title,
                    'post_type'=>'contest-gallery',
                    'post_content'=>"<!-- wp:shortcode -->"."\r\n".
                        "<!--This is a comment: cg_galley... shortcode with entry id is required to display Contest Gallery entry on a Contest Gallery Custom Post Type entry page. You can place your own content before and after this shortcode, whatever you like. You can place cg_gallery... shortcode with entry_id also on any other of your pages. -->"."\r\n".
                        "[cg_gallery_user id=\"$GalleryID\" entry_id=\"$rowObjectID\"]"."\r\n".
                        "<!-- /wp:shortcode -->",
                    'post_mime_type'=>'contest-gallery-plugin-page',
                    'post_status'=>'publish',
                    'post_parent'=>$options->WpPageParentUser
                ];

                $WpPageUser = wp_insert_post($array);
                $wpdb->update(
                    "$tablename",
                    array('WpPageUser' => $WpPageUser),
                    array('id' => $rowObjectID),
                    array('%d'),
                    array('%d')
                );
            }
            // cg_gallery_no_voting shortcode
            if(in_array('WpPageNoVoting',$WpPageTypeNamesArray)!==false){
                // cg_gallery shortcode
                $array = [
                    'post_title'=> $post_title,
                    'post_type'=>'contest-gallery',
                    'post_content'=>"<!-- wp:shortcode -->"."\r\n".
                        "<!--This is a comment: cg_galley... shortcode with entry id is required to display Contest Gallery entry on a Contest Gallery Custom Post Type entry page. You can place your own content before and after this shortcode, whatever you like. You can place cg_gallery... shortcode with entry_id also on any other of your pages. -->"."\r\n".
                        "[cg_gallery_no_voting id=\"$GalleryID\" entry_id=\"$rowObjectID\"]"."\r\n".
                        "<!-- /wp:shortcode -->",
                    'post_mime_type'=>'contest-gallery-plugin-page',
                    'post_status'=>'publish',
                    'post_parent'=>$options->WpPageParentNoVoting
                ];
                $WpPageNoVoting = wp_insert_post($array);
                $wpdb->update(
                    "$tablename",
                    array('WpPageNoVoting' => $WpPageNoVoting),
                    array('id' => $rowObjectID),
                    array('%d'),
                    array('%d')
                );
            }
            // cg_gallery_winner shortcode
            if(in_array('WpPageWinner',$WpPageTypeNamesArray)!==false){
                // cg_gallery shortcode
                $array = [
                    'post_title'=> $post_title,
                    'post_type'=>'contest-gallery',
                    'post_content'=>"<!-- wp:shortcode -->"."\r\n".
                        "<!--This is a comment: cg_galley... shortcode with entry id is required to display Contest Gallery entry on a Contest Gallery Custom Post Type entry page. You can place your own content before and after this shortcode, whatever you like. You can place cg_gallery... shortcode with entry_id also on any other of your pages. -->"."\r\n".
                        "[cg_gallery_winner id=\"$GalleryID\" entry_id=\"$rowObjectID\"]"."\r\n".
                        "<!-- /wp:shortcode -->",
                    'post_mime_type'=>'contest-gallery-plugin-page',
                    'post_status'=>'publish',
                    'post_parent'=>$options->WpPageParentWinner
                ];
                $WpPageWinner = wp_insert_post($array);
                $wpdb->update(
                    "$tablename",
                    array('WpPageWinner' => $WpPageWinner),
                    array('id' => $rowObjectID),
                    array('%d'),
                    array('%d')
                );
            }
        }
    }

   $picsSQL = $wpdb->get_results( "SELECT DISTINCT $table_posts.*, $tablename.* FROM $table_posts, $tablename WHERE 
                                              ($tablename.GalleryID='$GalleryID' AND $tablename.Active='1' and $table_posts.ID = $tablename.WpUpload) OR 
                                              ($tablename.GalleryID='$GalleryID' AND $tablename.Active='1' AND $tablename.WpUpload = 0) 
                                          GROUP BY $tablename.id ORDER BY $tablename.id DESC");

    $imageArray = [];

    $jsonUploadImageDataDir = $uploadFolder['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/image-data';
    $jsonUploadImageInfoDir = $uploadFolder['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/image-info';
    $jsonUploadImageCommentsDir = $uploadFolder['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/image-comments';

    // delete folders first for clean data and removed images really removed
    do_action('cg_delete_files_and_folder', $jsonUploadImageDataDir);
    do_action('cg_delete_files_and_folder', $jsonUploadImageInfoDir);

    // no more comments folder delete before since version 16.0.0, because contains backup comments
    //do_action('cg_delete_files_and_folder', $jsonUploadImageCommentsDir);

    // delete all files first for clean data and removed images really removed
    if(file_exists($uploadFolder['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-images.json')){
        unlink($uploadFolder['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-images.json');
    }
    if(file_exists($uploadFolder['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-images-info-values.json')){
        unlink($uploadFolder['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-images-info-values.json');
    }
    if(file_exists($uploadFolder['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-images-sort-values.json')){
        unlink($uploadFolder['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-images-sort-values.json');
    }

    // recreate folders then for clean data and removed images really removed
    if(!is_dir($jsonUploadImageDataDir)){
        mkdir($jsonUploadImageDataDir,0755,true);
    }

    if(!is_dir($jsonUploadImageInfoDir)){
        mkdir($jsonUploadImageInfoDir,0755,true);
    }

    if(!is_dir($jsonUploadImageCommentsDir)){
        mkdir($jsonUploadImageCommentsDir,0755,true);
    }

    $RatingOverviewArray = cg_get_correct_rating_overview($GalleryID);

    // add all json files and generate images array
    foreach($picsSQL as $object){

        $imageArray = cg_create_json_files_when_activating($GalleryID,$object,$thumbSizesWp,$uploadFolder,$imageArray,0,$RatingOverviewArray);

        $isCorrectAndImprove = true;

        $isAlternativeFile=false;

        if($object->post_mime_type=="application/pdf"){$isAlternativeFile=true;}
        else if($object->post_mime_type=="application/zip"){$isAlternativeFile=true;}
        else if($object->post_mime_type=="text/plain"){$isAlternativeFile=true;}
        else if($object->post_mime_type=="application/msword"){$isAlternativeFile=true;}
        else if($object->post_mime_type=="application/vnd.openxmlformats-officedocument.wordprocessingml.document"){$isAlternativeFile=true;}
        else if($object->post_mime_type=="application/vnd.ms-excel"){$isAlternativeFile=true;}
        else if($object->post_mime_type=="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"){$isAlternativeFile=true;}
        else if($object->post_mime_type=="text/csv"){$isAlternativeFile=true;}
        else if($object->post_mime_type=="audio/mpeg"){$isAlternativeFile=true;}
        else if($object->post_mime_type=="audio/wav"){$isAlternativeFile=true;}
        else if($object->post_mime_type=="audio/ogg"){$isAlternativeFile=true;}
        else if($object->post_mime_type=="video/mp4"){$isAlternativeFile=true;}
        else if($object->post_mime_type=="video/x-ms-wmv"){$isAlternativeFile=true;}
        else if($object->post_mime_type=="video/quicktime"){$isAlternativeFile=true;}
        else if($object->post_mime_type=="video/avi"){$isAlternativeFile=true;}
        else if($object->post_mime_type=="video/webm"){$isAlternativeFile=true;}

        if(!$isAlternativeFile && intval($options->Version)<17){
        include(__DIR__.'/../../../v10/v10-admin/gallery/change-gallery/4_2_fb-creation.php');
        }

    }

    if(empty($imageArray) || !is_array($imageArray)){// then data was corrected without having activated images
        $imageArray = [];
    }

    // take care of order!
    //cg_set_data_in_images_files_with_all_data($GalleryID,$imageArray);
    //cg_json_upload_form_info_data_files($GalleryID,null);
    cg_json_upload_form_info_data_files_new($GalleryID);


    $correctStatusText6 = 'Repaired';
    $correctStatusClass6 = 'cg_corrected';

}

$correctStatusTextFull7 = 'No mail exceptions so far';
$correctStatusText7 = 'Everything correct';
$correctStatusClass7 = 'cg_corrected';
$correctStatusDownloadClass7 = 'cg_corrected';
$correctStatusExceptions7 = '';
$correctStatusExceptionsReturn7 = '';
$cg_file_name_mail_log = '';

$fileName = md5(wp_salt( 'auth').'---cnglog---'.$GalleryID);
$file = $uploadFolder['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/logs/errors/mail-'.$fileName.'.log';

if(file_exists($file)){
    if(!empty($_POST['cg_remove_mail_exceptions_log'])){
        unlink($file);
    }else{
        $cg_file_name_mail_log = $fileName;
        $correctStatusTextFull7 = 'Some mail exceptions happend';
        $correctStatusText7 = 'Show exceptions';
        $correctStatusClass7 = 'cg_corrections_container_exception';
        $correctStatusDownloadClass7 = '';
        $fp = fopen($file, 'r');
        $correctStatusExceptions7 = fread($fp, 30000);
        fclose($fp);

        ob_start();
        echo "<pre>";
        print_r($correctStatusExceptions7);
        echo "</pre>";
        $correctStatusExceptionsReturn7 = ob_get_clean();
    }

}

$cgGetVersion = cg_get_version();
$pagesRepairText = '';
if(intval($options->Version)>=21){
    $pagesRepairText = '<br><b>If Contest Gallery Custom Type Pages were deleted then will be recreated.</b>';
}

echo <<<HEREDOC
<div id="cgCorrections">
    <div class="cg_corrections_container">
        <div class="cg_corrections_explanation">
            <div class="cg_corrections_title">Repair frontend?</div>
            <div class="cg_corrections_description">Many of the data is cached in json files.<br>If there were some changes done in database manually or json files were deleted then json files can be renewed here again.$pagesRepairText</div>
        </div>
        <div class="cg_corrections_action $correctStatusClass6">                
            <form method="POST" action="?page=$cgGetVersion/index.php&amp;corrections_and_improvements=true&amp;option_id=$GalleryID" data-cg-submit-message="Frontend repaired"  class="cg_load_backend_submit">
                <input type="hidden" name="action_correct_not_visible_for_frontend" value="true">
                <input type="hidden" name="option_id" value="$GalleryID">
                <span class="cg_corrections_action_submit">$correctStatusText6</span>
            </form>
        </div>
    </div>
    <div class="cg_corrections_container">
        <div class="cg_corrections_explanation">
            <div class="cg_corrections_title">Database status</div>
            <div class="cg_corrections_description">$correctStatusTextFull4</div>
        </div>
        <div class="cg_corrections_action $correctStatusClass4">                
            <form method="POST" action="?page=$cgGetVersion/index.php&amp;corrections_and_improvements=true&amp;option_id=$GalleryID" class="cg_load_backend_submit">
                <input type="hidden" name="action_check_and_correct_database" value="true">
                <input type="hidden" name="option_id" value="$GalleryID">
                <span class="cg_corrections_action_submit">$correctStatusText4</span>
            </form>
        </div>
    </div>
    $correctContent5
    <div class="cg_corrections_container">
        <div class="cg_corrections_explanation">
            <div class="cg_corrections_title">Mailing status</div>
            <div class="cg_corrections_description">$correctStatusTextFull7</div>
        </div>
        <div class="cg_corrections_action $correctStatusClass7" id="cgCorrect7showExceptionsButton" style="width:40%;text-align:center;">
            $correctStatusText7         
        </div>
        <div id="cgCorrect7exceptions" class="cg_hide" style="width:100%;">
            <div>$correctStatusExceptionsReturn7</div>
            <div class="cg_corrections_action $correctStatusDownloadClass7" style="width:40%;text-align:center;">
                 <form method="POST" action="?page=$cgGetVersion/index.php&amp;corrections_and_improvements=true&amp;option_id=$GalleryID">
                    <input type="hidden" name="cg_action_check_and_download_mail_log_for_gallery" value="true">
                    <input type="hidden" name="cg_file_name_mail_log" value="$cg_file_name_mail_log">
                    <input type="hidden" name="option_id" value="$GalleryID">
                    <span class="cg_corrections_action_submit">Download full log</span>
                </form>
            </div>
            <div class="cg_corrections_action $correctStatusClass7" style="width:40%;text-align:center;margin-top:30px;">
                <form method="POST" action="?page=$cgGetVersion/index.php&amp;corrections_and_improvements=true&amp;option_id=$GalleryID" class="cg_load_backend_submit">
                    <input type="hidden" name="cg_remove_mail_exceptions_log" value="true">
                    <input type="hidden" name="option_id" value="$GalleryID">
                    <span class="cg_corrections_action_submit">Remove exceptions log</span>
                </form>
            </div>
        </div>
    </div>
</div>
HEREDOC;




?>