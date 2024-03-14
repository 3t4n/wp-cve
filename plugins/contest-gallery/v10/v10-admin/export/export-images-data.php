<?php
if(!function_exists('cg_images_data_csv_export')){

    function cg_images_data_csv_export(){

        if(!current_user_can('manage_options')){
            echo "Logged in user have to be able to manage_options to export images.";die;
        }

        global $wpdb;

        $tablename = $wpdb->prefix . "contest_gal1ery";
        $tablename_ip = $wpdb->prefix . "contest_gal1ery_ip";
        $tablename_pro_options = $wpdb->prefix . "contest_gal1ery_pro_options";
        $tablename_f_input = $wpdb->prefix . "contest_gal1ery_f_input";
        $table_posts = $wpdb->prefix."posts";
        $wpUsers = $wpdb->base_prefix . "users";
        $tablenameentries = $wpdb->prefix . "contest_gal1ery_entries";
        $contest_gal1ery_categories = $wpdb->prefix . "contest_gal1ery_categories";

        $GalleryID = absint($_GET['option_id']);

        $content_url = wp_upload_dir();
        $content_url = $content_url['baseurl']; // Pfad zum Bilderordner angeben

        $getFormFieldNames = 0;
        $emailFieldCsvNumber = '';
        $emailFieldId = '';
        $categoryFieldCsvNumber = '';
        $categoryTitle = '';

        $categories = $wpdb->get_results($wpdb->prepare("SELECT * FROM $contest_gal1ery_categories WHERE GalleryID = %d ORDER BY Field_Order DESC",[$GalleryID]));

        $proOptions = $wpdb->get_row($wpdb->prepare( "SELECT * FROM $tablename_pro_options WHERE GalleryID = %d",[$GalleryID]));

        $IsModernFiveStar = $proOptions->IsModernFiveStar;
        $Manipulate = $proOptions->Manipulate;
        $selectSQLall = $wpdb->get_results($wpdb->prepare( "SELECT * FROM $tablename WHERE GalleryID = %d ORDER BY id DESC",[$GalleryID]));

        $selectFormInput = $wpdb->get_results($wpdb->prepare(  "SELECT id, Field_Type, Field_Order, Field_Content FROM $tablename_f_input WHERE GalleryID = %d AND (Field_Type = 'fbt-f' OR Field_Type = 'fbd-f' OR Field_Type = 'url-f' OR Field_Type = 'check-f' OR Field_Type = 'text-f' OR Field_Type = 'comment-f' OR Field_Type ='email-f' OR Field_Type ='select-f' OR Field_Type ='selectc-f' OR Field_Type ='url-f' OR Field_Type ='date-f') ORDER BY Field_Order ASC" ,[$GalleryID]));

        $selectAllGalleryVotes = $wpdb->get_results($wpdb->prepare( "
                SELECT pid, Rating, RatingS
                 FROM  $tablename_ip
                 WHERE GalleryID = %d 
				ORDER BY pid DESC
        ",[$GalleryID]));

/*        $selectAllGalleryVotesSqlArray = [];

        foreach ($selectAllGalleryVotes as $row){
            $selectAllGalleryVotesSqlArray[$row->id] = [];
            $selectAllGalleryVotesSqlArray[$row->id]['pid'] = $row->id;
            $selectAllGalleryVotesSqlArray[$row->id]['Rating'] = $row->Rating;
            $selectAllGalleryVotesSqlArray[$row->id]['RatingS'] = $row->RatingS;
        }*/
/*
        $addVotesArray = [];

        foreach ($selectSQLall as $row){
            $addVotesArray[$row->id] = [];
            $addVotesArray[$row->id]['addCountS'] = $row->addCountS;
            $addVotesArray[$row->id]['addCountR1'] = $row->addCountR1;
            $addVotesArray[$row->id]['addCountR2'] = $row->addCountR2;
            $addVotesArray[$row->id]['addCountR3'] = $row->addCountR3;
            $addVotesArray[$row->id]['addCountR4'] = $row->addCountR4;
            $addVotesArray[$row->id]['addCountR5'] = $row->addCountR5;
            $addVotesArray[$row->id]['addCountR6'] = $row->addCountR6;
            $addVotesArray[$row->id]['addCountR7'] = $row->addCountR7;
            $addVotesArray[$row->id]['addCountR8'] = $row->addCountR8;
            $addVotesArray[$row->id]['addCountR9'] = $row->addCountR9;
            $addVotesArray[$row->id]['addCountR10'] = $row->addCountR10;
        }*/

        include('export-images-data-voting-calculation.php');

        include(__DIR__ ."/../../../check-language.php");

        if(count($categories)){
            $categoriesUidsNames = array();
            $categoriesUidsNames[0] = $language_Other;
            foreach ($categories as $category) {
                $categoriesUidsNames[$category->id] = $category->Name;
            }
        }

        if(count($categories)){

            $categoriesUidsNames = array();
            $categoriesUidsNames[0] = $language_Other;
            foreach ($categories as $category) {

                $categoriesUidsNames[$category->id] = $category->Name;

            }

        }

        $selectContentFieldArray = array();
        $selectFormIdArrayAndRow = array();
        $inputDateFieldIdsAndFormatArray = array();

     //   echo "<pre>";
    //        print_r($GalleryID);
    //    echo "</pre>";

     //   echo "<pre>";
     //       print_r($selectFormInput);
    //    echo "</pre>";

        foreach ($selectFormInput as $value) {

            // 1. Feld Typ
            // 2. ID des Feldes in F_INPUT
            // 3. Feld Reihenfolge
            // 4. Feld Content

            $selectFieldType = 	$value->Field_Type;
            $id = $value->id;// prim�re unique id der form wird auch gespeichert und sp�ter in entries inserted zur erkennung des verwendeten formular feldes
            $fieldOrder = $value->Field_Order;// Die originale Field order in f_input Tabelle. Zwecks Vereinheitlichung.
            if($selectFieldType!='selectc-f'){
                $selectContentFieldArray[] = $selectFieldType;
                $selectContentFieldArray[] = $id;
                $selectContentFieldArray[] = $fieldOrder;
                $selectContentField = unserialize($value->Field_Content);
                $selectContentFieldArray[] = $selectContentField["titel"];

                if($value->Field_Type=='date-f'){
                    $inputDateFieldIdsAndFormatArray[$id] = $selectContentField["format"];
                }

            }else{
                $selectContentField = unserialize($value->Field_Content);
                $categoryTitle = $selectContentField["titel"];
            }

        }

/*        echo "<pre>";

        print_r($selectContentFieldArray);

        echo "</pre>";

        die;*/
        $csvData = array();

        $i=0;
        $r=0;
        $n=0;

        $GalleryID1="CG gallery id";
        $id1="CG file id";//ACHTUNG! Darf nicht Anfangen mit ID(Großgeschrieben I oder D am Anfang) in einer csv Datei, ansonsten ungültige SYLK Datei!
        $UploadDate1="CG upload date (UTC+0)";
        $NamePic1="CG name";
        $DownloadURL1="DownloadURL";
        $RecognitionMethod="RecognitionMethod if frontend upload";
        $UserIp="UserIp";
        $CookieId="CookieId";
        $WordPressUserId="WpUserId";
        $WordPressUserName="WpUserName";
        $WordPressUserEmail="WpUserEmail";
        /*$CountComments1="CountComments";
        $CountRatingOneStar ="CountRatingOneStar";
        $OneStarAddedByAdmin ="OneStarAddedByAdmin ";
        $MultipleStarsRatingCountOneStars = "MultipleStarsRatingCountOneStars";
        $MultipleStarsRatingCountTwoStars = "MultipleStarsRatingCountTwoStars";
        $MultipleStarsRatingCountThreeStars = "MultipleStarsRatingCountThreeStars";
        $MultipleStarsRatingCountFourStars = "MultipleStarsRatingCountFourStars";
        $MultipleStarsRatingCountFiveStars = "MultipleStarsRatingCountFiveStars";
        $MultipleStarsRatingCountSixStars = "MultipleStarsRatingCountSixStars";
        $MultipleStarsRatingCountSevenStars = "MultipleStarsRatingCountSevenStars";
        $MultipleStarsRatingCountEightStars = "MultipleStarsRatingCountEightStars";
        $MultipleStarsRatingCountNineStars = "MultipleStarsRatingCountNineStars";
        $MultipleStarsRatingCountTenStars = "MultipleStarsRatingCountTenStars";
        $MultipleStarsRatingCountOneStarsAddedByAdmin = "MultipleStarsRatingCountOneStarsAddedByAdmin";
        $MultipleStarsRatingCountTwoStarsAddedByAdmin = "MultipleStarsRatingCountTwoStarsAddedByAdmin";
        $MultipleStarsRatingCountThreeStarsAddedByAdmin = "MultipleStarsRatingCountThreeStarsAddedByAdmin";
        $MultipleStarsRatingCountFourStarsAddedByAdmin = "MultipleStarsRatingCountFourStarsAddedByAdmin";
        $MultipleStarsRatingCountFiveStarsAddedByAdmin = "MultipleStarsRatingCountFiveStarsAddedByAdmin";
        $MultipleStarsRatingCountSixStarsAddedByAdmin = "MultipleStarsRatingCountSixStarsAddedByAdmin";
        $MultipleStarsRatingCountSevenStarsAddedByAdmin = "MultipleStarsRatingCountSevenStarsAddedByAdmin";
        $MultipleStarsRatingCountEightStarsAddedByAdmin = "MultipleStarsRatingCountEightStarsAddedByAdmin";
        $MultipleStarsRatingCountNineStarsAddedByAdmin = "MultipleStarsRatingCountNineStarsAddedByAdmin";
        $MultipleStarsRatingCountTenStarsAddedByAdmin = "MultipleStarsRatingCountTenStarsAddedByAdmin";*/

        $csvData[$i][$r]='note: manually added rating by administrator will be exported now';
        $i++;

        $csvData[$i][$r]=$GalleryID1;
        $r++;
        $csvData[$i][$r]=$id1;
        $r++;
        $csvData[$i][$r]=$UploadDate1;
        $r++;
        $csvData[$i][$r]=$NamePic1;
        $r++;
        $csvData[$i][$r]=$DownloadURL1;
        $r++;
        $csvData[$i][$r]=$RecognitionMethod;
        $r++;
        $csvData[$i][$r]=$UserIp;
        $r++;
        $csvData[$i][$r]=$CookieId;
        $r++;
        $csvData[$i][$r]=$WordPressUserId;
        $r++;
        $csvData[$i][$r]=$WordPressUserName;
        $r++;
        $csvData[$i][$r]=$WordPressUserEmail;
        $r++;
        $csvData[$i][$r]='WpFileTitle';
        $r++;
        $csvData[$i][$r]='WpFileDescription';
        $r++;
        $csvData[$i][$r]='wp_posts_table_ID (Media Library ID)';
        $r++;
        /*$csvData[$i][$r]=$CountComments1;
        $r++;
        $csvData[$i][$r]=$CountRatingOneStar;
        $r++;
        $csvData[$i][$r]=$OneStarAddedByAdmin;
        $r++;
        $csvData[$i][$r]=$MultipleStarsRatingCountOneStars;
        $r++;
        $csvData[$i][$r]=$MultipleStarsRatingCountTwoStars;
        $r++;
        $csvData[$i][$r]=$MultipleStarsRatingCountThreeStars;
        $r++;
        $csvData[$i][$r]=$MultipleStarsRatingCountFourStars;
        $r++;
        $csvData[$i][$r]=$MultipleStarsRatingCountFiveStars;
        $r++;
        $csvData[$i][$r]=$MultipleStarsRatingCountSixStars;
        $r++;
        $csvData[$i][$r]=$MultipleStarsRatingCountSevenStars;
        $r++;
        $csvData[$i][$r]=$MultipleStarsRatingCountEightStars;
        $r++;
        $csvData[$i][$r]=$MultipleStarsRatingCountNineStars;
        $r++;
        $csvData[$i][$r]=$MultipleStarsRatingCountTenStars;
        $r++;
        $csvData[$i][$r]=$MultipleStarsRatingCountOneStarsAddedByAdmin;
        $r++;
        $csvData[$i][$r]=$MultipleStarsRatingCountTwoStarsAddedByAdmin;
        $r++;
        $csvData[$i][$r]=$MultipleStarsRatingCountThreeStarsAddedByAdmin;
        $r++;
        $csvData[$i][$r]=$MultipleStarsRatingCountFourStarsAddedByAdmin;
        $r++;
        $csvData[$i][$r]=$MultipleStarsRatingCountFiveStarsAddedByAdmin;
        $r++;
        $csvData[$i][$r]=$MultipleStarsRatingCountSixStarsAddedByAdmin;
        $r++;
        $csvData[$i][$r]=$MultipleStarsRatingCountSevenStarsAddedByAdmin;
        $r++;
        $csvData[$i][$r]=$MultipleStarsRatingCountEightStarsAddedByAdmin;
        $r++;
        $csvData[$i][$r]=$MultipleStarsRatingCountNineStarsAddedByAdmin;
        $r++;
        $csvData[$i][$r]=$MultipleStarsRatingCountTenStarsAddedByAdmin;
        $r++;*/
        $csvData[$i][$r]='Activated';
        $r++;
        $csvData[$i][$r]='Winner';
        $r++;
        $csvData[$i][$r]='Informed';
        $r++;
        $csvData[$i][$r]='EXIF: DateTimeOriginal';
        $r++;
        $csvData[$i][$r]='EXIF: MakeAndModel';
        $r++;
        $csvData[$i][$r]='EXIF: ApertureFNumber';
        $r++;
        $csvData[$i][$r]='EXIF: ExposureTime';
        $r++;
        $csvData[$i][$r]='EXIF: ISOSpeedRatings';
        $r++;
        $csvData[$i][$r]='EXIF: FocalLength';
        $r++;

        //Bestimmung der Spalten Namen
        if($n==0){

            foreach($selectContentFieldArray as $key => $formvalue){

                //	echo "<br>$i<br>";

                // 1. Feld Typ
                // 2. ID des Feldes in F_INPUT
                // 3. Feld Reihenfolge
                // 4. Feld Content


                if(@$formvalue=='check-f'){$fieldtype="cb"; $n=1; continue;}// Check Agreement
                if(@$fieldtype=="cb" AND $n==1){$formFieldId=$formvalue; $n=2; continue;}
                if(@$fieldtype=="cb" AND $n==2){$fieldOrder=$formvalue; $n=3; continue;}
                if (@$fieldtype=="cb" AND $n==3) {
                    $csvData[$i][$r]="$formvalue";
                    $selectFormIdArrayAndRow[$formFieldId] = $r;
                    $r++;
                    $n=0;
                }

                if(@$formvalue=='date-f'){$fieldtype="dt"; $n=1; continue;}
                if(@$fieldtype=="dt" AND $n==1){$formFieldId=$formvalue; $n=2; continue;}
                if(@$fieldtype=="dt" AND $n==2){$fieldOrder=$formvalue; $n=3; continue;}
                if (@$fieldtype=="dt" AND $n==3) {
                    $csvData[$i][$r]="$formvalue";
                    $selectFormIdArrayAndRow[$formFieldId] = $r;
                    $r++;

                    $n=0;

                }

                if(@$formvalue=='text-f'){$fieldtype="nf"; $n=1; continue;}
                if(@$fieldtype=="nf" AND $n==1){$formFieldId=$formvalue; $n=2; continue;}
                if(@$fieldtype=="nf" AND $n==2){$fieldOrder=$formvalue; $n=3; continue;}
                if (@$fieldtype=="nf" AND $n==3) {
                    $csvData[$i][$r]="$formvalue";
                    $selectFormIdArrayAndRow[$formFieldId] = $r;
                    $r++;

                    $n=0;

                }

                if(@$formvalue=='email-f'){$fieldtype="ef";  $n=1; continue;}
                if(@$fieldtype=="ef" AND $n==1){$formFieldId=$formvalue; $n=2; continue;}
                if(@$fieldtype=="ef" AND $n==2){$fieldOrder=$formvalue; $n=3; continue;}
                if (@$fieldtype=='ef' AND $n==3) {
                    $emailFieldId = $formFieldId;
                    $csvData[$i][$r]="$formvalue";
                    $selectFormIdArrayAndRow[$formFieldId] = $r;
                    $emailFieldCsvNumber = $r;

                    $r++;

                    $n=0;
                }

                if(@$formvalue=='comment-f'){$fieldtype="kf"; $n=1; continue;}
                if(@$fieldtype=="kf" AND $n==1){$formFieldId=$formvalue; $n=2; continue;}
                if(@$fieldtype=="kf" AND $n==2){$fieldOrder=$formvalue; $n=3; continue;}
                if (@$fieldtype=='kf' AND $n==3) {

                    $csvData[$i][$r]="$formvalue";
                    $selectFormIdArrayAndRow[$formFieldId] = $r;
                    $r++;

                    $n=0;
                }

                if(@$formvalue=='select-f'){$fieldtype="se"; $n=1; continue;}
                if(@$fieldtype=="se" AND $n==1){$formFieldId=$formvalue; $n=2; continue;}
                if(@$fieldtype=="se" AND $n==2){$fieldOrder=$formvalue; $n=3; continue;}
                if (@$fieldtype=='se' AND $n==3) {

                    $csvData[$i][$r]="$formvalue";
                    $selectFormIdArrayAndRow[$formFieldId] = $r;
                    $r++;

                    $n=0;
                }


                if(@$formvalue=='url-f'){$fieldtype="url"; $n=1; continue;}
                if(@$fieldtype=="url" AND $n==1){$formFieldId=$formvalue; $n=2; continue;}
                if(@$fieldtype=="url" AND $n==2){$fieldOrder=$formvalue; $n=3; continue;}
                if (@$fieldtype=='url' AND $n==3) {

                    $csvData[$i][$r]="$formvalue";
                    $selectFormIdArrayAndRow[$formFieldId] = $r;
                    $r++;

                    $n=0;
                }

                if(@$formvalue=='fbt-f'){$fieldtype="fbt"; $n=1; continue;}
                if(@$fieldtype=="fbt" AND $n==1){$formFieldId=$formvalue; $n=2; continue;}
                if(@$fieldtype=="fbt" AND $n==2){$fieldOrder=$formvalue; $n=3; continue;}
                if (@$fieldtype=='fbt' AND $n==3) {

                    $csvData[$i][$r]="$formvalue";
                    $selectFormIdArrayAndRow[$formFieldId] = $r;
                    $r++;

                    $n=0;
                }


                if(@$formvalue=='fbd-f'){$fieldtype="fbd"; $n=1; continue;}
                if(@$fieldtype=="fbd" AND $n==1){$formFieldId=$formvalue; $n=2; continue;}
                if(@$fieldtype=="fbd" AND $n==2){$fieldOrder=$formvalue; $n=3; continue;}
                if (@$fieldtype=='fbd' AND $n==3) {

                    $csvData[$i][$r]="$formvalue";
                    $selectFormIdArrayAndRow[$formFieldId] = $r;
                    $r++;
                    $n=0;

                }


/*                if(@$formvalue=='selectc-f'){$fieldtype="sec"; $n=1; continue;}
                if(@$fieldtype=="sec" AND $n==1){$formFieldId=$formvalue; $n=2; continue;}
                if(@$fieldtype=="sec" AND $n==2){$fieldOrder=$formvalue; $n=3; continue;}
                if (@$fieldtype=='sec' AND $n==3) {
                    $selectFormIdArrayAndRow[$formFieldId] = $r;
                    $categoryTitle ="$formvalue";
                    $r++;
                    $n=0;
                }*/


            }

        }

        // Category Select always as last!!!!!!
        if(count($categories)){
            $csvData[$i][$r] = $categoryTitle;
            $categoryFieldCsvNumber = $r;// Keine Ahnung warum -1 :)
        }
        $r++;
        $csvData[$i][$r]="OneStarCount";
        $r++;
        $csvData[$i][$r]="MultipleStarsOneStarCount";
        $r++;
        $csvData[$i][$r]="MultipleStarsTwoStarsCount";
        $r++;
        $csvData[$i][$r]="MultipleStarsThreeStarsCount";
        $r++;
        $csvData[$i][$r]="MultipleStarsFourStarsCount";
        $r++;
        $csvData[$i][$r]="MultipleStarsFiveStarsCount";
        $r++;
        $csvData[$i][$r]="MultipleStarsSixStarsCount";
        $r++;
        $csvData[$i][$r]="MultipleStarsSevenStarsCount";
        $r++;
        $csvData[$i][$r]="MultipleStarsEightStarsCount";
        $r++;
        $csvData[$i][$r]="MultipleStarsNineStarsCount";
        $r++;
        $csvData[$i][$r]="MultipleStarsTenStarsCount";
        $r++;
        $csvData[$i][$r]="MultipleStarsOneStarSum";
        $r++;
        $csvData[$i][$r]="MultipleStarsTwoStarsSum";
        $r++;
        $csvData[$i][$r]="MultipleStarsThreeStarsSum";
        $r++;
        $csvData[$i][$r]="MultipleStarsFourStarsSum";
        $r++;
        $csvData[$i][$r]="MultipleStarsFiveStarsSum";
        $r++;
        $csvData[$i][$r]="MultipleStarsSixStarsSum";
        $r++;
        $csvData[$i][$r]="MultipleStarsSevenStarsSum";
        $r++;
        $csvData[$i][$r]="MultipleStarsEightStarsSum";
        $r++;
        $csvData[$i][$r]="MultipleStarsNineStarsSum";
        $r++;
        $csvData[$i][$r]="MultipleStarsTenStarsSum";
        $r++;
        $csvData[$i][$r]="MultipleStarsCountInCaseTwoStarsVotingIsActivated";
        $r++;
        $csvData[$i][$r]="MultipleStarsCountInCaseThreeStarsVotingIsActivated";
        $r++;
        $csvData[$i][$r]="MultipleStarsCountInCaseFourStarsVotingIsActivated";
        $r++;
        $csvData[$i][$r]="MultipleStarsCountInCaseFiveStarsVotingIsActivated";
        $r++;
        $csvData[$i][$r]="MultipleStarsCountInCaseSixStarsVotingIsActivated";
        $r++;
        $csvData[$i][$r]="MultipleStarsCountInCaseSevenStarsVotingIsActivated";
        $r++;
        $csvData[$i][$r]="MultipleStarsCountInCaseEightStarsVotingIsActivated";
        $r++;
        $csvData[$i][$r]="MultipleStarsCountInCaseNineStarsVotingIsActivated";
        $r++;
        $csvData[$i][$r]="MultipleStarsCountInCaseTenStarsVotingIsActivated";
        $r++;
        $csvData[$i][$r]="MultipleStarsSumInCaseTwoStarsVotingIsActivated";
        $r++;
        $csvData[$i][$r]="MultipleStarsSumInCaseThreeStarsVotingIsActivated";
        $r++;
        $csvData[$i][$r]="MultipleStarsSumInCaseFourStarsVotingIsActivated";
        $r++;
        $csvData[$i][$r]="MultipleStarsSumInCaseFiveStarsVotingIsActivated";
        $r++;
        $csvData[$i][$r]="MultipleStarsSumInCaseSixStarsVotingIsActivated";
        $r++;
        $csvData[$i][$r]="MultipleStarsSumInCaseSevenStarsVotingIsActivated";
        $r++;
        $csvData[$i][$r]="MultipleStarsSumInCaseEightStarsVotingIsActivated";
        $r++;
        $csvData[$i][$r]="MultipleStarsSumInCaseNineStarsVotingIsActivated";
        $r++;
        $csvData[$i][$r]="MultipleStarsSumInCaseTenStarsVotingIsActivated";
        $r++;
        $csvData[$i][$r]="MultipleStarsAverageInCaseTwoStarsVotingIsActivated";
        $r++;
        $csvData[$i][$r]="MultipleStarsAverageInCaseThreeStarsVotingIsActivated";
        $r++;
        $csvData[$i][$r]="MultipleStarsAverageInCaseFourStarsVotingIsActivated";
        $r++;
        $csvData[$i][$r]="MultipleStarsAverageInCaseFiveStarsVotingIsActivated";
        $r++;
        $csvData[$i][$r]="MultipleStarsAverageInCaseSixStarsVotingIsActivated";
        $r++;
        $csvData[$i][$r]="MultipleStarsAverageInCaseSevenStarsVotingIsActivated";
        $r++;
        $csvData[$i][$r]="MultipleStarsAverageInCaseEightStarsVotingIsActivated";
        $r++;
        $csvData[$i][$r]="MultipleStarsAverageInCaseNineStarsVotingIsActivated";
        $r++;
        $csvData[$i][$r]="MultipleStarsAverageInCaseTenStarsVotingIsActivated";
        $r++;

        // Setting titles ended now starting setting values
        $getFormFieldNames++;
        // Bestimmung der Feld-Inhalte
        $r = 0;
        $i++;
        foreach($selectSQLall as $value){

            $csvData[$i][$r]=$value->GalleryID;
            $r++;
            $csvData[$i][$r]=$value->id;
            $pidCSV=$value->id;
            $r++;
            $uploadTime = date('m.d.Y H:i', $value->Timestamp);
            $csvData[$i][$r]=$uploadTime;
            $r++;
            $csvData[$i][$r]=$value->NamePic;
            $r++;
            $WpUserId = $value->WpUserId;
            $WpPostsID = '';
            $guid = '';
            $post_title = '';
            $post_content = '';
            if($value->WpUpload!=NULL && $value->WpUpload>0){
                $WpPostsData = $wpdb->get_row("SELECT ID, post_title, post_content, guid FROM $table_posts WHERE ID = '".$value->WpUpload."'");
                $WpPostsID = $WpPostsData->ID;
                $guid = $WpPostsData->guid;
                $post_title = $WpPostsData->post_title;
                $post_content = $WpPostsData->post_content;
                $csvData[$i][$r]=$guid;
            }
            else{
                $csvData[$i][$r]='';
            }
            $r++;
            $CheckSetElse = (intval(str_replace('.','',$value->Version)) < 109830) ? '' : 'Admin backend upload';
            $csvData[$i][$r]= (!empty($value->CheckSet)) ? $value->CheckSet : $CheckSetElse;//plugin "some version" will be compared, CheckSet mightn ot be set before this version if RegUserUploadOnly==0.
            $r++;
            $csvData[$i][$r]= (!empty($value->IP)) ? $value->IP : 'will be tracked since plugin version 10.9.3.7';
            $r++;
            $csvData[$i][$r]= (!empty($value->CookieId)) ? $value->CookieId : '';
            $r++;
            if($value->WpUserId!=NULL && $value->WpUserId>0){
                $wpUserData=$wpdb->get_row("SELECT * FROM $wpUsers WHERE ID = '".$value->WpUserId."'");
                $csvData[$i][$r]=$wpUserData->ID;
            }else{
                $csvData[$i][$r]='';
            }
            $r++;
            if($value->WpUserId!=NULL && $value->WpUserId>0){
                $csvData[$i][$r]=$wpUserData->user_nicename;
            }else{
                $csvData[$i][$r]='';
            }
            $r++;
            if($value->WpUserId!=NULL && $value->WpUserId>0){
                $csvData[$i][$r]=$wpUserData->user_email;
            }else{
                $csvData[$i][$r]='';
            }
            $r++;
            $csvData[$i][$r]=$post_title;
            $r++;
            $csvData[$i][$r]=$post_content;
            $r++;
            $csvData[$i][$r]=$WpPostsID;
            $r++;
            /*$csvData[$i][$r]=$value->CountC;
            $r++;
            $csvData[$i][$r]=$value->CountS;
            $r++;
            $csvData[$i][$r]=$value->addCountS;
            $r++;
            $csvData[$i][$r]=($IsModernFiveStar==1) ? $value->CountR1 : 'Convert to modern multiple stars rating in "Edit options" >>> "Corrections and Improvements" to see this count';
            $r++;
            $csvData[$i][$r]=($IsModernFiveStar==1) ? $value->CountR2 : 'Convert to modern multiple stars rating in "Edit options" >>> "Corrections and Improvements" to see this count' ;
            $r++;
            $csvData[$i][$r]=($IsModernFiveStar==1) ? $value->CountR3 : 'Convert to modern multiple stars rating in "Edit options" >>> "Corrections and Improvements" to see this count';
            $r++;
            $csvData[$i][$r]=($IsModernFiveStar==1) ? $value->CountR4 : 'Convert to modern multiple stars rating in "Edit options" >>> "Corrections and Improvements" to see this count';
            $r++;
            $csvData[$i][$r]=($IsModernFiveStar==1) ? $value->CountR5 : 'Convert to modern multiple stars rating in "Edit options" >>> "Corrections and Improvements" to see this count';
            $r++;
            $csvData[$i][$r]=($IsModernFiveStar==1) ? $value->CountR6 : 'Convert to modern multiple stars rating in "Edit options" >>> "Corrections and Improvements" to see this count';
            $r++;
            $csvData[$i][$r]=($IsModernFiveStar==1) ? $value->CountR7 : 'Convert to modern multiple stars rating in "Edit options" >>> "Corrections and Improvements" to see this count';
            $r++;
            $csvData[$i][$r]=($IsModernFiveStar==1) ? $value->CountR8 : 'Convert to modern multiple stars rating in "Edit options" >>> "Corrections and Improvements" to see this count';
            $r++;
            $csvData[$i][$r]=($IsModernFiveStar==1) ? $value->CountR9 : 'Convert to modern multiple stars rating in "Edit options" >>> "Corrections and Improvements" to see this count';
            $r++;
            $csvData[$i][$r]=($IsModernFiveStar==1) ? $value->CountR10 : 'Convert to modern multiple stars rating in "Edit options" >>> "Corrections and Improvements" to see this count';
            $r++;
            $csvData[$i][$r]=$value->addCountR1;
            $r++;
            $csvData[$i][$r]=$value->addCountR2;
            $r++;
            $csvData[$i][$r]=$value->addCountR3;
            $r++;
            $csvData[$i][$r]=$value->addCountR4;
            $r++;
            $csvData[$i][$r]=$value->addCountR5;
            $r++;
            $csvData[$i][$r]=$value->addCountR6;
            $r++;
            $csvData[$i][$r]=$value->addCountR7;
            $r++;
            $csvData[$i][$r]=$value->addCountR8;
            $r++;
            $csvData[$i][$r]=$value->addCountR9;
            $r++;
            $csvData[$i][$r]=$value->addCountR10;
            $r++;*/
            $csvData[$i][$r]=$value->Active;
            $r++;
            $csvData[$i][$r]=(!empty($value->Winner)) ? '1' : '0';
            $r++;
            $csvData[$i][$r]=$value->Informed;
            $r++;
            $DateTimeOriginal = '';
            $MakeAndModel = '';
            $ApertureFNumber = '';
            $ExposureTime = '';
            $ISOSpeedRatings = '';
            $FocalLength = '';
            if(!empty($value->Exif)){
                $ExifData = unserialize($value->Exif);
                if(is_array($ExifData)){
                    if(!empty($ExifData['DateTimeOriginal'])){
                        $DateTimeOriginal = $ExifData['DateTimeOriginal'];
                        $DateTimeOriginal = explode(' ',$DateTimeOriginal);
                        $DateTimeOriginal = $DateTimeOriginal[0];
                        $DateTimeOriginal = str_replace(':','-',$DateTimeOriginal);
                    }
                    if(!empty($ExifData['MakeAndModel'])){
                        $MakeAndModel = $ExifData['MakeAndModel'];
                    }else if($ExifData['Model']){
                        $MakeAndModel = $ExifData['Model'];
                    }
                    if($ExifData['ApertureFNumber']){
                        $ApertureFNumber = $ExifData['ApertureFNumber'];
                    }
                    if($ExifData['ExposureTime']){
                        $ExposureTime = $ExifData['ExposureTime'];
                    }
                    if($ExifData['ISOSpeedRatings']){
                        $ISOSpeedRatings = $ExifData['ISOSpeedRatings'];
                    }
                    if($ExifData['FocalLength']){
                        $FocalLength = $ExifData['FocalLength'];
                    }
                }
            }
            $csvData[$i][$r]=$DateTimeOriginal;
            $r++;
            $csvData[$i][$r]=$MakeAndModel;
            $r++;
            $csvData[$i][$r]=$ApertureFNumber;
            $r++;
            $csvData[$i][$r]=$ExposureTime;
            $r++;
            $csvData[$i][$r]=$ISOSpeedRatings;
            $r++;
            $csvData[$i][$r]=$FocalLength;
            $r++;

            //       var_dump($r);

            $selectSQLentries = $wpdb->get_results( "SELECT * FROM $tablenameentries WHERE pid = '$pidCSV' ORDER BY Field_Order ASC");

            // ACHTUNG!!!! Leere Felder müssen gefüllt werden ansonsten erscheint der inhalt einfacher in der nächsten spalte und nicht in der richtigen
            // Schon ma vorab füllen!!!!
            foreach ($selectFormInput as $container) {

                //    var_dump($r);
                $csvData[$i][$r] = '';
                $r++;

            }

            if(!empty($selectSQLentries)){
                $mailInserted = false;
                foreach($selectSQLentries as $value_entries){

                    $fieldType = $value_entries->Field_Type;
                    //	echo $value_entries->Short_Text;

                    // $emailField = false;

                    if($fieldType=="email-f" && empty($WpUserId)){

                        //  $emailField = true;
                        //  var_dump('mailInsertedBefore');

                        //    var_dump($WpUserId);
                        $mailInserted= true;
                     //   var_dump('emailFieldCsvNumber');
                      //  var_dump($emailFieldCsvNumber);
                    //    var_dump($value_entries);
                        $csvData[$i][$emailFieldCsvNumber]=$value_entries->Short_Text;
                    }
                    else if($fieldType=="comment-f"){$csvData[$i][$selectFormIdArrayAndRow[$value_entries->f_input_id]]=$value_entries->Long_Text;}
                    else if($fieldType=="check-f"){$csvData[$i][$selectFormIdArrayAndRow[$value_entries->f_input_id]]=($value_entries->Checked==1) ? 'checked' : 'not checked';}
                    else if($fieldType=="date-f"){

                        $newDateTimeString = '';

                        if(!empty($value_entries->InputDate) && $value_entries->InputDate!='0000-00-00 00:00:00'){
                            $dtFormat = $inputDateFieldIdsAndFormatArray[$value_entries->f_input_id];
                            $dtFormat = str_replace('YYYY','Y',$dtFormat);
                            $dtFormat = str_replace('MM','m',$dtFormat);
                            $dtFormat = str_replace('DD','d',$dtFormat);

                            $newDateTimeObject = DateTime::createFromFormat("Y-m-d H:i:s",$value_entries->InputDate);

                            if(is_object($newDateTimeObject)){
                                $newDateTimeString = $newDateTimeObject->format($dtFormat);
                            }
                        }


/*                        echo "<br>";
                        echo "$dtFormat";
                        echo "<br>";
                        echo "$value_entries->InputDate";*/
                      //  echo "<br>";
                      //  echo $newDateTimeString;


                        $csvData[$i][$selectFormIdArrayAndRow[$value_entries->f_input_id]] = " $newDateTimeString";



                    }
                    else{
                      //  var_dump(3333);
                    //   var_dump($selectFormIdArrayAndRow[$value_entries->f_input_id]);
                        if($fieldType!="email-f"){
                            $csvData[$i][$selectFormIdArrayAndRow[$value_entries->f_input_id]]=$value_entries->Short_Text;
                        }

                    }


                }

            }

/*            if(!empty($emailFieldCsvNumber) && !empty($WpUserId)){
                var_dump('11111');

                $csvData[$i][$emailFieldCsvNumber]=$wpdb->get_var("SELECT user_email FROM $wpUsers WHERE ID = $WpUserId");

            }*/


            if(count($categories)){
               // $r++;
               $csvData[$i][$categoryFieldCsvNumber] = $categoriesUidsNames[$value->Category];
            }
            $r++;
            $csvData[$i][$r] = (!empty($allGalleryVotesArray[$value->id])) ? $allGalleryVotesArray[$value->id]['OneStarCount'] : 0;
            $r++;
            $csvData[$i][$r] = (!empty($allGalleryVotesArray[$value->id])) ? $allGalleryVotesArray[$value->id]['MultipleStarsOneStarCount'] : 0;
            $r++;
            $csvData[$i][$r] = (!empty($allGalleryVotesArray[$value->id])) ? $allGalleryVotesArray[$value->id]['MultipleStarsTwoStarsCount'] : 0;
            $r++;
            $csvData[$i][$r] = (!empty($allGalleryVotesArray[$value->id])) ? $allGalleryVotesArray[$value->id]['MultipleStarsThreeStarsCount'] : 0;
            $r++;
            $csvData[$i][$r] = (!empty($allGalleryVotesArray[$value->id])) ? $allGalleryVotesArray[$value->id]['MultipleStarsFourStarsCount'] : 0;
            $r++;
            $csvData[$i][$r] = (!empty($allGalleryVotesArray[$value->id])) ? $allGalleryVotesArray[$value->id]['MultipleStarsFiveStarsCount'] : 0;
            $r++;
            $csvData[$i][$r] = (!empty($allGalleryVotesArray[$value->id])) ? $allGalleryVotesArray[$value->id]['MultipleStarsSixStarsCount'] : 0;
            $r++;
            $csvData[$i][$r] = (!empty($allGalleryVotesArray[$value->id])) ? $allGalleryVotesArray[$value->id]['MultipleStarsSevenStarsCount'] : 0;
            $r++;
            $csvData[$i][$r] = (!empty($allGalleryVotesArray[$value->id])) ? $allGalleryVotesArray[$value->id]['MultipleStarsEightStarsCount'] : 0;
            $r++;
            $csvData[$i][$r] = (!empty($allGalleryVotesArray[$value->id])) ? $allGalleryVotesArray[$value->id]['MultipleStarsNineStarsCount'] : 0;
            $r++;
            $csvData[$i][$r] = (!empty($allGalleryVotesArray[$value->id])) ? $allGalleryVotesArray[$value->id]['MultipleStarsTenStarsCount'] : 0;
            $r++;
            $csvData[$i][$r] = (!empty($allGalleryVotesArray[$value->id])) ? $allGalleryVotesArray[$value->id]['MultipleStarsOneStarSum'] : 0;
            $r++;
            $csvData[$i][$r] = (!empty($allGalleryVotesArray[$value->id])) ? $allGalleryVotesArray[$value->id]['MultipleStarsTwoStarsSum'] : 0;
            $r++;
            $csvData[$i][$r] = (!empty($allGalleryVotesArray[$value->id])) ? $allGalleryVotesArray[$value->id]['MultipleStarsThreeStarsSum'] : 0;
            $r++;
            $csvData[$i][$r] = (!empty($allGalleryVotesArray[$value->id])) ? $allGalleryVotesArray[$value->id]['MultipleStarsFourStarsSum'] : 0;
            $r++;
            $csvData[$i][$r] = (!empty($allGalleryVotesArray[$value->id])) ? $allGalleryVotesArray[$value->id]['MultipleStarsFiveStarsSum'] : 0;
            $r++;
            $csvData[$i][$r] = (!empty($allGalleryVotesArray[$value->id])) ? $allGalleryVotesArray[$value->id]['MultipleStarsSixStarsSum'] : 0;
            $r++;
            $csvData[$i][$r] = (!empty($allGalleryVotesArray[$value->id])) ? $allGalleryVotesArray[$value->id]['MultipleStarsSevenStarsSum'] : 0;
            $r++;
            $csvData[$i][$r] = (!empty($allGalleryVotesArray[$value->id])) ? $allGalleryVotesArray[$value->id]['MultipleStarsEightStarsSum'] : 0;
            $r++;
            $csvData[$i][$r] = (!empty($allGalleryVotesArray[$value->id])) ? $allGalleryVotesArray[$value->id]['MultipleStarsNineStarsSum'] : 0;
            $r++;
            $csvData[$i][$r] = (!empty($allGalleryVotesArray[$value->id])) ? $allGalleryVotesArray[$value->id]['MultipleStarsTenStarsSum'] : 0;
            $r++;
            $csvData[$i][$r] = (!empty($allGalleryVotesArray[$value->id])) ? $allGalleryVotesArray[$value->id]['MultipleStarsCountInCaseTwoStarsVotingIsActivated'] : 0;
            $r++;
            $csvData[$i][$r] = (!empty($allGalleryVotesArray[$value->id])) ? $allGalleryVotesArray[$value->id]['MultipleStarsCountInCaseThreeStarsVotingIsActivated'] : 0;
            $r++;
            $csvData[$i][$r] = (!empty($allGalleryVotesArray[$value->id])) ? $allGalleryVotesArray[$value->id]['MultipleStarsCountInCaseFourStarsVotingIsActivated'] : 0;
            $r++;
            $csvData[$i][$r] = (!empty($allGalleryVotesArray[$value->id])) ? $allGalleryVotesArray[$value->id]['MultipleStarsCountInCaseFiveStarsVotingIsActivated'] : 0;
            $r++;
            $csvData[$i][$r] = (!empty($allGalleryVotesArray[$value->id])) ? $allGalleryVotesArray[$value->id]['MultipleStarsCountInCaseSixStarsVotingIsActivated'] : 0;
            $r++;
            $csvData[$i][$r] = (!empty($allGalleryVotesArray[$value->id])) ? $allGalleryVotesArray[$value->id]['MultipleStarsCountInCaseSevenStarsVotingIsActivated'] : 0;
            $r++;
            $csvData[$i][$r] = (!empty($allGalleryVotesArray[$value->id])) ? $allGalleryVotesArray[$value->id]['MultipleStarsCountInCaseEightStarsVotingIsActivated'] : 0;
            $r++;
            $csvData[$i][$r] = (!empty($allGalleryVotesArray[$value->id])) ? $allGalleryVotesArray[$value->id]['MultipleStarsCountInCaseNineStarsVotingIsActivated'] : 0;
            $r++;
            $csvData[$i][$r] = (!empty($allGalleryVotesArray[$value->id])) ? $allGalleryVotesArray[$value->id]['MultipleStarsCountInCaseTenStarsVotingIsActivated'] : 0;
            $r++;
            $csvData[$i][$r] = (!empty($allGalleryVotesArray[$value->id])) ? $allGalleryVotesArray[$value->id]['MultipleStarsSumInCaseTwoStarsVotingIsActivated'] : 0;
            $r++;
            $csvData[$i][$r] = (!empty($allGalleryVotesArray[$value->id])) ? $allGalleryVotesArray[$value->id]['MultipleStarsSumInCaseThreeStarsVotingIsActivated'] : 0;
            $r++;
            $csvData[$i][$r] = (!empty($allGalleryVotesArray[$value->id])) ? $allGalleryVotesArray[$value->id]['MultipleStarsSumInCaseFourStarsVotingIsActivated'] : 0;
            $r++;
            $csvData[$i][$r] = (!empty($allGalleryVotesArray[$value->id])) ? $allGalleryVotesArray[$value->id]['MultipleStarsSumInCaseFiveStarsVotingIsActivated'] : 0;
            $r++;
            $csvData[$i][$r] = (!empty($allGalleryVotesArray[$value->id])) ? $allGalleryVotesArray[$value->id]['MultipleStarsSumInCaseSixStarsVotingIsActivated'] : 0;
            $r++;
            $csvData[$i][$r] = (!empty($allGalleryVotesArray[$value->id])) ? $allGalleryVotesArray[$value->id]['MultipleStarsSumInCaseSevenStarsVotingIsActivated'] : 0;
            $r++;
            $csvData[$i][$r] = (!empty($allGalleryVotesArray[$value->id])) ? $allGalleryVotesArray[$value->id]['MultipleStarsSumInCaseEightStarsVotingIsActivated'] : 0;
            $r++;
            $csvData[$i][$r] = (!empty($allGalleryVotesArray[$value->id])) ? $allGalleryVotesArray[$value->id]['MultipleStarsSumInCaseNineStarsVotingIsActivated'] : 0;
            $r++;
            $csvData[$i][$r] = (!empty($allGalleryVotesArray[$value->id])) ? $allGalleryVotesArray[$value->id]['MultipleStarsSumInCaseTenStarsVotingIsActivated'] : 0;
            $r++;
            $csvData[$i][$r] = (!empty($allGalleryVotesArray[$value->id])) ? str_replace('.',',',$allGalleryVotesArray[$value->id]['MultipleStarsAverageInCaseTwoStarsVotingIsActivated']) : 0;
            $r++;
            $csvData[$i][$r] = (!empty($allGalleryVotesArray[$value->id])) ? str_replace('.',',',$allGalleryVotesArray[$value->id]['MultipleStarsAverageInCaseThreeStarsVotingIsActivated']): 0;
            $r++;
            $csvData[$i][$r] = (!empty($allGalleryVotesArray[$value->id])) ? str_replace('.',',',$allGalleryVotesArray[$value->id]['MultipleStarsAverageInCaseFourStarsVotingIsActivated']) : 0;
            $r++;
            $csvData[$i][$r] = (!empty($allGalleryVotesArray[$value->id])) ? str_replace('.',',',$allGalleryVotesArray[$value->id]['MultipleStarsAverageInCaseFiveStarsVotingIsActivated']) : 0;
            $r++;
            $csvData[$i][$r] = (!empty($allGalleryVotesArray[$value->id])) ? str_replace('.',',',$allGalleryVotesArray[$value->id]['MultipleStarsAverageInCaseSixStarsVotingIsActivated']) : 0;
            $r++;
            $csvData[$i][$r] = (!empty($allGalleryVotesArray[$value->id])) ? str_replace('.',',',$allGalleryVotesArray[$value->id]['MultipleStarsAverageInCaseSevenStarsVotingIsActivated']) : 0;
            $r++;
            $csvData[$i][$r] = (!empty($allGalleryVotesArray[$value->id])) ? str_replace('.',',',$allGalleryVotesArray[$value->id]['MultipleStarsAverageInCaseEightStarsVotingIsActivated']) : 0;
            $r++;
            $csvData[$i][$r] = (!empty($allGalleryVotesArray[$value->id])) ? str_replace('.',',',$allGalleryVotesArray[$value->id]['MultipleStarsAverageInCaseNineStarsVotingIsActivated']) : 0;
            $r++;
            $csvData[$i][$r] = (!empty($allGalleryVotesArray[$value->id])) ? str_replace('.',',',$allGalleryVotesArray[$value->id]['MultipleStarsAverageInCaseTenStarsVotingIsActivated']) : 0;

            ksort($csvData[$i]);
         //   var_dump($csvData);
      //      die;
            $i++;
            $r=0;
        }

        //	print_r($csvData);

        /*	$list = array (
        array('aaa', 'bbb', 'ccc'),
        array('123', '456', '789')

    );*/

        // old logic as example. Do not remove
/*        $admin_email = get_option('admin_email');
        $adminHashedPass = $wpdb->get_var("SELECT user_pass FROM $wpUsers WHERE user_email = '$admin_email'");

        $code = $wpdb->base_prefix; // database prefix
        $code = md5($code.$adminHashedPass);

        $filename = $code."_userdata.csv";*/


        $filename = "cg-images-data-gallery-id-$GalleryID.csv";

        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=$filename");

        ob_start();

        $fp = fopen("php://output", 'w');
        fputs($fp, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
        foreach ($csvData as $fields) {
            fputcsv($fp, $fields, ";");

        }
        fclose($fp);
        $masterReturn = ob_get_clean();
        echo $masterReturn;
        die();
    }
}

?>