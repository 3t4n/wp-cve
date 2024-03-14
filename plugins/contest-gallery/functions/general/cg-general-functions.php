<?php

if(!function_exists('cg_check_nonce')){
    function cg_check_nonce(){
        $cg_nonce = '';
        if(isset($_POST['cg_nonce'])){
            $cg_nonce = $_POST['cg_nonce'];
        }else if(isset($_GET['cg_nonce'])){
            $cg_nonce = $_GET['cg_nonce'];
        }
        if(empty($cg_nonce) || !wp_verify_nonce($cg_nonce, 'cg_nonce')){
            echo '###cg_version###'.cg_get_version().'###cg_version###';
            echo '###cg_nonce_invalid###';
            die;
        }
    }
}

if(!function_exists('cg_create_nonce')){
    function cg_create_nonce(){
        $nonce = wp_create_nonce('cg_nonce');
        echo "<input type='hidden' id='cg_nonce' value='$nonce' class='cg_do_not_remove_when_ajax_load cg_do_not_remove_when_main_empty' />";
    }
}

if(!function_exists('cg_create_version_input')){
    function cg_create_version_input(){
        echo "<input type='hidden' id='cgGetVersionForUrlJs' value='".cg_get_version()."'  class='cg_do_not_remove_when_ajax_load cg_do_not_remove_when_main_empty' />";
    }
}


if(!function_exists('cg_copy_table_row')){
    function cg_copy_table_row($tableNameStringPart,$oldID, $valueCollect = [], $cgCopyType = '', $columns = null){

        //
        //$nextGalleryID=0,$cgCopyType='',$newPid = 0
         //$f_input_id

        global $wpdb;
        $tableName = $wpdb->prefix . $tableNameStringPart;

        // if cg_copy_table_row in a loop then does not need to get $columns again and again
        if(empty($columns)){
        $columns = $wpdb->get_results( "SHOW COLUMNS FROM $tableName" );
        }

        $columnsString = 'NULL';

        $ratingFieldsArray = ['CountC','CountR','CountS','Rating','addCountS','addCountR1','addCountR2','addCountR3','addCountR4','addCountR5','addCountR6','addCountR7','addCountR8','addCountR9','addCountR10','CountR1','CountR2','CountR3','CountR4','CountR5','CountR6','CountR7','CountR8','CountR9','CountR10'
        ];

        foreach ($columns as $rowObject){
            if($rowObject->Field=='id'){continue;}// should be always the first one
            if($cgCopyType=='cg_copy_type_options_and_images'  && $tableNameStringPart=='contest_gal1ery' && in_array($rowObject->Field,$ratingFieldsArray)){// all rating has to be set on 0
                // has to be simply in quotes then ""
                $columnsString .= ', "0"';
            } else if(!empty($valueCollect[$tableNameStringPart]) && !empty($valueCollect[$tableNameStringPart][$rowObject->Field])){
                // has to be simply in quotes then ""#
                if(is_serialized($valueCollect[$tableNameStringPart][$rowObject->Field])){
                    $columnsString .= ', \''.$valueCollect[$tableNameStringPart][$rowObject->Field].'\'';
                }else{
                    $columnsString .= ', "'.$valueCollect[$tableNameStringPart][$rowObject->Field].'"';
                }
            }else{
            $columnsString .= ', '.$rowObject->Field;
        }
        }

        if($tableNameStringPart=='contest_gal1ery_options'){
            $query = "INSERT INTO $tableName 
    SELECT $columnsString 
    FROM $tableName
    WHERE id = $oldID";
            $wpdb->query($query);
            $nextId = $wpdb->insert_id;
            return $nextId;
        }else if(
                $tableNameStringPart=='contest_gal1ery' ||
                $tableNameStringPart=='contest_gal1ery_f_input' ||
                $tableNameStringPart=='contest_gal1ery_f_output' ||
                $tableNameStringPart=='contest_gal1ery_categories' ||
                $tableNameStringPart=='contest_gal1ery_comments_notification_options' ||
                $tableNameStringPart=='contest_gal1ery_entries'
        ){
            $query = "INSERT INTO $tableName 
    SELECT $columnsString 
    FROM $tableName
    WHERE id = $oldID";

            /*if($tableNameStringPart=='contest_gal1ery_f_input'){
                var_dump($tableName);
                echo "<br>";
                var_dump($oldID);
                echo "<br>";
                var_dump($columnsString);
                echo "<br>";
                var_dump($query);
                echo "<br>";
            }*/

            $wpdb->query($query);
            $nextId = $wpdb->insert_id;


            return $nextId;
        }else{
            $query = "INSERT INTO $tableName 
    SELECT $columnsString 
    FROM $tableName
    WHERE GalleryID = $oldID";
            $wpdb->query($query);
            $nextId = $wpdb->insert_id;
            return $nextId;
        }
    }
}

if(!function_exists('cg_check_headers_sent')){
    function cg_check_headers_sent(){
        if(headers_sent()){
            ?>
            <script data-cg-processing="true">
                cgJsClass.gallery.function.message.show(undefined,'Some other plugin have already sent headers. Login not possible.');
            </script>
            <?php
            return true;
        }else{
            return false;
        }
    }
}

if(!function_exists('cg_check_if_database_tables_ok')){
    function cg_check_if_database_tables_ok(){

        global $wpdb;
        $tablename = $wpdb->prefix . "contest_gal1ery";
        $tablenameOptions = $wpdb->prefix . "contest_gal1ery_options";
        $tablename_pro_options = $wpdb->prefix . "contest_gal1ery_pro_options";

        if(
            ($wpdb->get_var("SHOW TABLES LIKE '$tablename'") != $tablename) ||
            ($wpdb->get_var("SHOW TABLES LIKE '$tablenameOptions'") != $tablenameOptions) ||
            ($wpdb->get_var("SHOW TABLES LIKE '$tablename_pro_options'") != $tablename_pro_options)
        )
        {
            if (is_multisite()) {
                $i=get_current_blog_id();
                if($i==1){
                    $i="";
                    $lastError = contest_gal1ery_create_table($i,true);
                }else {
                    $lastError = contest_gal1ery_create_table($i."_",true);
                }
            }else{
                $i='';
                $lastError = contest_gal1ery_create_table($i,true);
            }
            if(!empty($lastError)){
                // message normal and message PRO here!
                $plugin_dir_path = plugin_dir_path(__FILE__);
                echo "<b>There were errors when trying to create database tables.<br>";
                echo "If the errors are not understandable and the reason unclear<br>";
                echo "please contact following email, copy paste and send the errors information above to:</b><br>";
                if(cg_get_version()=='contest-gallery-pro'){
                    echo '<a href="mailto:support-pro@contest-gallery.com">support-pro@contest-gallery.com</a>';
                }else{
                    echo '<a href="mailto:support@contest-gallery.com">support@contest-gallery.com</a>';
                }
                echo "<br><br><br><br><br><br><br><br><br>";
                die;
            }
        }
    }
}

if(!function_exists('cg_check_if_upload_folder_permissions_ok')){
    function cg_check_if_upload_folder_permissions_ok(){

        $wp_upload_dir = wp_upload_dir();
        $isWritable = true;
        if(!is_writable($wp_upload_dir['basedir'])){
            $isWritable = false;
        }
        if($isWritable){// test file creation
            $cgTestFileName = 'contest-gal1ery-test-file.txt';
            try {
                file_put_contents($wp_upload_dir['basedir'].'/'.$cgTestFileName,'test');
            }catch (Exception $e){
                // no exception printing so far required
            }
            if(file_exists($wp_upload_dir['basedir'].'/'.$cgTestFileName)){
                unlink($wp_upload_dir['basedir'].'/'.$cgTestFileName);
            }else{
                $isWritable = false;
            }
        }
        if(!$isWritable){
            echo "<b>No files and folders can be created created in your main upload folder:</b><br>";
            echo $wp_upload_dir['basedir'];
            echo "<br><b>Recommended WordPress permissions for a wp-content/uploads folder are: 755</b><br>";
            die;
        }
    }
}

if(!function_exists('cg_echo_last_sql_error')){
    function cg_echo_last_sql_error($isShowError,& $lastError = ''){
        if($isShowError){
            global $wpdb;
            $wpdb->show_errors(true);
            $wpdb->suppress_errors(false);//<<< set for sure, but somehow always surpressed by WordPress
            if($wpdb->last_error!=$lastError){
                $lastError = $wpdb->last_error;
                echo "<div>";
                echo "<br><b>Query:</b><br> ";
                echo $wpdb->last_query;
                echo "<br><b>Error:</b><br> ";
                echo $lastError;// error somehow always surpressed by WordPress
                echo "<br><br>";
                echo "</div>";
                return $lastError;
            };
        }
        return $lastError;
    }
}

if(!function_exists('cg_get_time_based_on_wp_timezone_conf')){
    function cg_get_time_based_on_wp_timezone_conf($unixtstmp,$format){// unixtime always gmt+0/utc+0

        $dt = DateTime::createFromFormat('U', $unixtstmp);// this always creates gmt+0/utc+0
        $minutes = get_option('gmt_offset')*60;
        $dt->modify("$minutes minutes");
        return $dt->format($format);

    }
}

if(!function_exists('cg_get_date_time_object_based_on_wp_timezone_conf')){
    function cg_get_date_time_object_based_on_wp_timezone_conf($unixtstmp){// unixtime always gmt+0/utc+0
        $dt = DateTime::createFromFormat('U', $unixtstmp);// this always creates gmt+0/utc+0
        $minutes = get_option('gmt_offset')*60;
        $dt->modify("$minutes minutes");
        return $dt;
    }
}

if(!function_exists('cg_set_json_data_of_row_objects')){
    function cg_set_json_data_of_row_objects($picsSQL,$galeryID,$wp_upload_dir,$thumbSizesWp,$ExifDataByRealIds = []){

        $imagesArray = [];

        foreach($picsSQL as $object){

            if(empty($imagesArray)){
                $imagesArray = cg_create_json_files_when_activating($galeryID,$object,$thumbSizesWp,$wp_upload_dir,null,0,[],$ExifDataByRealIds);
                /*                        echo "<pre>";
                                        print_r($imagesArray);
                                        echo "</pre>";*/
            }else{
                /*                        echo "<pre>";
                                        print_r($imagesArray);
                                        echo "</pre>";*/
                $imagesArray = cg_create_json_files_when_activating($galeryID,$object,$thumbSizesWp,$wp_upload_dir,$imagesArray,0,[],$ExifDataByRealIds);
                /*                        echo "<pre>";
                                        print_r($imagesArray);
                                        echo "</pre>";*/
            }

            if(!is_dir($wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galeryID.'/json/frontend-added-or-removed-images')){
                mkdir($wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galeryID.'/json/frontend-added-or-removed-images',0755);
            }

            // simply create empty file for later check
            $jsonFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galeryID.'/json/frontend-added-or-removed-images/'.$object->id.'.txt';
            $fp = fopen($jsonFile, 'w');
            fwrite($fp, '');
            fclose($fp);

        }

        //cg_set_data_in_images_files_with_all_data($galeryID,$imagesArray,true);

        cg_create_no_script_html($imagesArray,$galeryID);

        //   die;

        return $imagesArray;

    }
}

if(!function_exists('cg_set_cookie')){
    function cg_set_cookie($galeryID,$type){
        $cookieValue = md5(uniqid('cg',true)).time();
        setcookie('contest-gal1ery-'.$galeryID.'-'.$type, $cookieValue, time() + (20 * 365 * 24 * 60 * 60), "/");
        return $cookieValue;
    }
}

if(!function_exists('cg_create_contest_gallery_plugin_tag')){
    function cg_create_contest_gallery_plugin_tag(){

        $tag = wp_insert_term(
            'Contest Gallery Plugin Tag', // the term
            'post_tag', // the taxonomy
            array(
                'description'=> 'Do not remove. Will be recreated if required as long Contest Gallery plugin is activated. Tag for categorizing Contest Gallery Plugin entry pages. Helps to sort out entry pages in backend "Pages" area.  Will be removed when Contest Gallery plugin will be deleted.',
                'slug' => 'contest-gallery-plugin-tag'
            )
        );

     //   wp_set_post_tags()

        return $tag;
    }
}

if(!function_exists('cg_check_if_development')){
    function cg_check_if_development(){
        return false;
        if(is_dir(plugin_dir_path( __FILE__  ).'../../../contest-gallery-js-and-css')){
            return true;
        }else{
            return false;
        }

    }
}

?>