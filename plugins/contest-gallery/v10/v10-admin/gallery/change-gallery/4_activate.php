<?php

    $collect = "";
    $collectArray = [];

        $imageRatingArray = array();

        // erst mal alle aktivieren, die aktiviert gehören!!!
        if(!empty($_POST['cg_activate'])){

            $querySETrowActive = 'UPDATE ' . $tablename . ' SET Active = CASE';
            $querySETaddRowActive = ' ELSE Active END WHERE (id) IN (';
            $queryArgsArray = [];
            $queryAddArgsArray = [];
            $queryArgsCounter = 0;

            foreach($activate as $key => $value){

                $value = absint(sanitize_text_field($value));

                $querySETrowActive .= " WHEN (id = %d) THEN 1";
                $querySETaddRowActive .= "(%d), ";
                $queryArgsArray[] = $value;
                $queryAddArgsArray[] = $value;
                $queryArgsCounter++;

            }

            // ic = i counter
            for ($ic = 0;$ic<$queryArgsCounter;$ic++){
                $queryArgsArray[] =$queryAddArgsArray[$ic];
            }

            $querySETaddRowActive = substr($querySETaddRowActive,0,-2);
            $querySETaddRowActive .= ")";

            $querySETrowActive .= $querySETaddRowActive;

            $wpdb->query($wpdb->prepare($querySETrowActive,$queryArgsArray));

        }

        // Dann die bearbeiten, die geschickt wurden und nicht DEAKTIVIERT wurden!!!
        if(!empty($_POST['cg_activate'])){

            $ids = $_POST['cg_activate'];

            foreach($ids as $id => $value){
                $id = absint($id);
                if($collect==''){
                    $collect .= "$tablename.id = $id";
                }else{
                    $collect .= " OR $tablename.id = $id";
                }
                $collectArray[] = $id;
            }

        }

        if(!empty($_POST['cg_winner'])){

            $ids = $_POST['cg_winner'];

            foreach($ids as $id => $value){
                $id = absint($id);
                if(in_array($id,$collectArray)!==false){
                    continue;
                }

                if($collect==''){
                    $collect .= "$tablename.id = $id";
                }else{
                    $collect .= " OR $tablename.id = $id";
                }
                $collectArray[] = $id;

            }

        }
        if(!empty($_POST['cg_winner_not'])){

            $ids = $_POST['cg_winner_not'];

            foreach($ids as $id => $value){
                $id = absint($id);
                if(in_array($id,$collectArray)!==false){
                    continue;
                }

                if($collect==''){
                    $collect .= "$tablename.id = $id";
                }else{
                    $collect .= " OR $tablename.id = $id";
                }
                $collectArray[] = $id;

            }

        }

        if(!empty($_POST['cg_row'])){

            $ids = $_POST['cg_row'];

            foreach($ids as $id => $rowid){
                $id = absint($id);
                if(in_array($id,$collectArray)!==false){
                    continue;
                }

                if($collect==''){
                    $collect .= "$tablename.id = $id";
                }else{
                    $collect .= " OR $tablename.id = $id";
                }
                $collectArray[] = $id;

            }

        }

        if(!empty($_POST['addCountChange'])){

            $ids = $_POST['addCountChange'];

            foreach($ids as $id => $value){
                $id = absint($id);
                if(in_array($id,$collectArray)!==false){
                    continue;
                }

                if($collect==''){
                    $collect .= "$tablename.id = $id";
                }else{
                    $collect .= " OR $tablename.id = $id";
                }
                $collectArray[] = $id;

            }

        }

        if(!empty($_POST['imageCategory'])){

            $ids = $_POST['imageCategory'];

            foreach($ids as $id => $value){
                $id = absint($id);
                if(in_array($id,$collectArray)!==false){
                    continue;
                }

                if($collect==''){
                    $collect .= "$tablename.id = $id";
                }else{
                    $collect .= " OR $tablename.id = $id";
                }
                $collectArray[] = $id;

            }

        }

    if(!empty($_POST['cg_rThumb'])){

            $ids = $_POST['cg_rThumb'];

            foreach($ids as $id => $value){
                $id = absint($id);

                if(in_array($id,$collectArray)!==false){
                    continue;
                }

                if($collect==''){
                    $collect .= "$tablename.id = $id";
                }else{
                    $collect .= " OR $tablename.id = $id";
                }
                $collectArray[] = $id;

            }

/*            var_dump('$collect');
            var_dump($collect);
            die;*/
        }

    if(!empty($_POST['cg_multiple_files_for_post'])){

            $ids = $_POST['cg_multiple_files_for_post'];

            foreach($ids as $id => $value){
                $id = absint($id);
                if(in_array($id,$collectArray)!==false){
                    continue;
                }

                if($collect==''){
                    $collect .= "$tablename.id = $id";
                }else{
                    $collect .= " OR $tablename.id = $id";
                }
                $collectArray[] = $id;

            }

        }

        if(!empty($collect)){

            $picsSQL = $wpdb->get_results( "SELECT DISTINCT $table_posts.*, $tablename.* FROM $table_posts, $tablename WHERE 
                                              (($collect) AND $tablename.GalleryID='$GalleryID' AND $tablename.Active='1' and $table_posts.ID = $tablename.WpUpload) 
                                              OR 
                                              (($collect) AND $tablename.GalleryID='$GalleryID' AND $tablename.Active='1' AND $tablename.WpUpload = 0) 
                                          GROUP BY $tablename.id  ORDER BY $tablename.id DESC");

            // Gr��e der Bilder bei ThumbAnsicht (gew�hnliche Ansicht mit Bewertung)
            $uploadFolder = wp_upload_dir();
            $urlSource = site_url();

            $blog_title = get_bloginfo('name');
            $blog_description = get_bloginfo('description');

            $RatingOverviewArray = cg_get_correct_rating_overview($GalleryID);

            // add all json files and generate images array
            foreach($picsSQL as $object){

                $imageArray = cg_create_json_files_when_activating($GalleryID,$object,$thumbSizesWp,$uploadFolder,$imageArray,$galeryrow->Version,$RatingOverviewArray);

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
                else if($object->post_mime_type=="video/avi"){$isAlternativeFile=true;}
                else if($object->post_mime_type=="video/x-ms-wmv"){$isAlternativeFile=true;}
                else if($object->post_mime_type=="video/quicktime"){$isAlternativeFile=true;}
                else if($object->post_mime_type=="video/webm"){$isAlternativeFile=true;}

                if(!$isAlternativeFile && intval($galeryrow->Version)<17){
                    include('4_2_fb-creation.php');
                }

            }


        }

















