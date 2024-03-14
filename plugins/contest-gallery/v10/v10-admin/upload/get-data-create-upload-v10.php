<?php

// 1. Delete Felder in Entries, F_Input, F_Output
// 2. Swap Field_Order in Entries, F_Input, F_Output (bei post "done-upload" wird alles mitgegeben
// 3. Neue Felder hinzuf�gen in F_Input, Entries
// 4. // Auswahl zum Anzeigen gespeicherter Felder

// Empfangen von Galerie OptiOns ID

$GalleryID = absint($_GET['option_id']);

global $wpdb;

// Tabellennamen bestimmen

$tablename = $wpdb->prefix . "contest_gal1ery";
$tablenameoptions = $wpdb->prefix . "contest_gal1ery_options";
$tablenameentries = $wpdb->prefix . "contest_gal1ery_entries";
$tablename_form_input = $wpdb->prefix . "contest_gal1ery_f_input";
$tablename_form_output = $wpdb->prefix . "contest_gal1ery_f_output";
$tablename_options_visual = $wpdb->prefix . "contest_gal1ery_options_visual";
$tablename_categories = $wpdb->prefix . "contest_gal1ery_categories";
$tablename_pro_options = $wpdb->prefix . "contest_gal1ery_pro_options";

$optionsSql = $wpdb->get_row($wpdb->prepare( "SELECT GalleryName, FbLike, Version FROM $tablenameoptions WHERE id = %d",[$GalleryID]));

$GalleryName = $optionsSql->GalleryName;
$FbLike = $optionsSql->FbLike;
$dbGalleryVersion = $optionsSql->Version;

$Version = cg_get_version_for_scripts();

if(!isset($_POST['deleteFieldnumber'])){
    $_POST['deleteFieldnumber'] = false;
}

// Pr�fen ob es ein Feld gibt welches als Images URL genutzt werden soll
// wird nicht merh verwendet!!!
//$Use_as_URL = $wpdb->get_var($wpdb->prepare( "SELECT Use_as_URL FROM $tablename_form_input WHERE GalleryID = %d AND Use_as_URL = '1'",[$GalleryID]));

//$Use_as_URL_id = $wpdb->get_var($wpdb->prepare( "SELECT id FROM $tablename_form_input WHERE GalleryID = %d AND Use_as_URL = '1'",[$GalleryID]));

$WatermarkPosition = '';
$WatermarkPositionForVisualOptions = '';

$WpAttachmentDetailsType = '';

$IsForWpPageTitleID = 0;
$IsForWpPageDescriptionID = 0;

$SubTitle = 0;
$SubTitleToSet = 0;
$ThirdTitle = 0;
$ThirdTitleToSet = 0;

if(!empty($_POST['upload'])){

    check_admin_referer( 'cg_admin');

    $wp_upload_dir = wp_upload_dir();

    $checkDataFormOutput = $wpdb->get_results($wpdb->prepare( "SELECT * FROM $tablename_form_input WHERE GalleryID = %d and (Field_Type = 'comment-f' or Field_Type = 'text-f' or Field_Type = 'email-f')",[$GalleryID]));

    //print_r($checkDataFormOutput);

    $rowVisualOptions = $wpdb->get_row($wpdb->prepare( "SELECT * FROM $tablename_options_visual WHERE GalleryID = %d",[$GalleryID]));

    $Field1IdGalleryView = $rowVisualOptions->Field1IdGalleryView;

    // make json file
    $optionsFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-options.json';
    $fp = fopen($optionsFile, 'r');
    $optionsFileData =json_decode(fread($fp,filesize($optionsFile)),true);
    fclose($fp);


    $infoInSliderId = null;
    $infoInGalleryId = null;
    $alternativeFileTypeNameId = null;
    $tagInGalleryId = null;
    $tagInGalleryIdIsForCategories = false;

    // Check if certain fieldnumber should be deleted

    // L�schen Ddaten in Tablename entries
    // L�schen Ddaten in Tablename f_input
    // L�schen Ddaten in Tablename f_output


    if(!empty($_POST['deleteFieldnumber'])){

        if(is_array($_POST['deleteFieldnumber'])){

            if(!empty($_POST['deleteFieldnumber']['deleteCategoryFields'])){

                $deleteFieldnumber = intval(reset($_POST['deleteFieldnumber']));

                $wpdb->query( $wpdb->prepare(
                    "
                        DELETE FROM $tablename_categories WHERE GalleryID = %d
                    ",
                    $GalleryID
                ));

                $wpdb->update(
                    "$tablename",
                    array('Category' => 0),
                    array('GalleryID' => $GalleryID),
                    array('%d'),
                    array('%d')
                );

            }

        }
        else{
            $deleteFieldnumber = intval($_POST['deleteFieldnumber']);
        }

        $wpdb->query( $wpdb->prepare(
            "
                DELETE FROM $tablename_form_input WHERE GalleryID = %d AND id = %d
             ",
            $GalleryID, $deleteFieldnumber
        ));

        $wpdb->query( $wpdb->prepare(
            "
                DELETE FROM $tablename_form_output WHERE GalleryID = %d AND f_input_id = %d
             ",
            $GalleryID, $deleteFieldnumber
        ));


        $wpdb->query( $wpdb->prepare(
            "
                DELETE FROM $tablenameentries WHERE GalleryID = %d AND f_input_id = %d
             ",
            $GalleryID, $deleteFieldnumber
        ));

    }

    // Check if certain fieldnumber should be deleted --- ENDE


    // insert delete Categories


    if(!empty($_POST['deleteCategory'])){

        $deleteCategory = intval($_POST['deleteCategory']);

        $wpdb->query( $wpdb->prepare(
            "
                DELETE FROM $tablename_categories WHERE id = %d
             ",
            $deleteCategory
        ));

        // wenn es die Kategorie gibt wird diese mit 0 upgedatet, wenn nicht dann nicht
        $wpdb->update(
            "$tablename",
            array('Category' => 0),
            array('Category' => $deleteCategory),
            array('%d'),
            array('%d')
        );

    }


    if(!empty($_POST['cg_category'])){

        $order = 1;

        $categoriesCount = $wpdb->get_var($wpdb->prepare( "SELECT COUNT(*) AS NumberOfRows FROM $tablename_categories WHERE GalleryID = %d ORDER BY Field_Order",[$GalleryID]));

        if(empty($categoriesCount)){ // then CatWidget option has to be set to 1 and show other also to 1 again

            $wpdb->update(
                "$tablename_pro_options",
                array('ShowOther' => 1, 'CatWidget' => 1),
                array('GalleryID' => $GalleryID),
                array('%d','%d'),
                array('%s')
            );


            if(!empty($optionsFileData[$GalleryID])){
                $optionsFileData[$GalleryID]['pro']['ShowOther'] = 1;
                $optionsFileData[$GalleryID]['pro']['CatWidget'] = 1;
                $optionsFileData[$GalleryID.'-u']['pro']['ShowOther'] = 1;
                $optionsFileData[$GalleryID.'-u']['pro']['CatWidget'] = 1;
                $optionsFileData[$GalleryID.'-nv']['pro']['ShowOther'] = 1;
                $optionsFileData[$GalleryID.'-nv']['pro']['CatWidget'] = 1;
                $optionsFileData[$GalleryID.'-w']['pro']['ShowOther'] = 1;
                $optionsFileData[$GalleryID.'-w']['pro']['CatWidget'] = 1;
            }else{
                $optionsFileData['pro']['ShowOther'] = 1;
                $optionsFileData['pro']['CatWidget'] = 1;
            }


            // make json file
            $optionsFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-options.json';
            $fp = fopen($optionsFile, 'w');
            fwrite($fp, json_encode($optionsFileData));
            fclose($fp);

        }

        /*
         * Forwarding cg_category example
         * [14] => Array
        (
            [2758] => Category14 <<< such looks
        )
            [15] => brax <<< such looks new
         *
         * */

        foreach($_POST['cg_category'] as $key => $value){

            if(is_array($value)){

                foreach($value as $id => $name){
                    $name = contest_gal1ery_htmlentities_and_preg_replace($name);
                    $wpdb->update(
                        "$tablename_categories",
                        array('Name' => $name,'Field_Order' => $order),
                        array('id' => $id),
                        array('%s'),
                        array('%d')
                    );
                    $order++;

                }

            }
            else{

                $value = contest_gal1ery_htmlentities_and_preg_replace($value);

                $wpdb->query( $wpdb->prepare(
                    "
                      INSERT INTO $tablename_categories
                      ( id, GalleryID, Name, Field_Order, Active)
                      VALUES ( %s,%s,%s,%s,%d )
                   ",
                    '',$GalleryID,$value,$order,1
                ) );

                $order++;

            }


        }

    }

    // insert delete Categories end

    /*    echo "<pre>";
        print_r($_POST['upload']);
        echo "</pre>";*/

    if(!empty($_POST['upload'])){

        foreach($_POST['upload'] as $id => $field){

            if($id=='new-0'){
                continue;
            }

            if(strpos($id,'new-')!==false){
                $id = 'new';
                $field['new'] = 'true';
            }else{
                $id = absint($id);
            }

            if($field['type']=='bh'){

                $bhFieldsArray = array();

                if(!empty($field['required'])){
                    if($field['required']=='on'){
                        $onOff = 'on';
                    }else{
                        $onOff = 'off';
                    }
                }else{
                    $onOff = 'off';
                }

                if(empty($field['hide'])){
                    $active = 1;
                }else{
                    $active = 2;
                }

                $bhFieldsArray['mandatory']=sanitize_text_field($onOff);

                $bhFieldsArray['titel']= sanitize_text_field(htmlentities($field['title'], ENT_QUOTES));
                $bhFieldsArray['file-type-img']= sanitize_text_field(htmlentities(!empty($field['file-type-img']) ? $field['file-type-img'] : '' , ENT_QUOTES));
                $bhFieldsArray['alternative-file-title']= sanitize_text_field(htmlentities(!empty($field['alternative-file-title']) ? $field['alternative-file-title'] : '' , ENT_QUOTES));
                $bhFieldsArray['alternative-file-type-pdf'] = sanitize_text_field(htmlentities(!empty($field['alternative-file-type-pdf']) ? $field['alternative-file-type-pdf'] : '' , ENT_QUOTES));
                $bhFieldsArray['alternative-file-type-zip'] = sanitize_text_field(htmlentities(!empty($field['alternative-file-type-zip']) ? $field['alternative-file-type-zip'] : '' , ENT_QUOTES));
                $bhFieldsArray['alternative-file-type-txt'] = sanitize_text_field(htmlentities(!empty($field['alternative-file-type-txt']) ? $field['alternative-file-type-txt'] : '' , ENT_QUOTES));
                $bhFieldsArray['alternative-file-type-doc'] = sanitize_text_field(htmlentities(!empty($field['alternative-file-type-doc']) ? $field['alternative-file-type-doc'] : '' , ENT_QUOTES));
                $bhFieldsArray['alternative-file-type-docx'] = sanitize_text_field(htmlentities(!empty($field['alternative-file-type-docx']) ? $field['alternative-file-type-docx'] : '' , ENT_QUOTES));
                $bhFieldsArray['alternative-file-type-xls'] = sanitize_text_field(htmlentities(!empty($field['alternative-file-type-xls']) ? $field['alternative-file-type-xls'] : '' , ENT_QUOTES));
                $bhFieldsArray['alternative-file-type-xlsx'] = sanitize_text_field(htmlentities(!empty($field['alternative-file-type-xlsx']) ? $field['alternative-file-type-xlsx'] : '' , ENT_QUOTES));
                $bhFieldsArray['alternative-file-type-csv'] = sanitize_text_field(htmlentities(!empty($field['alternative-file-type-csv']) ? $field['alternative-file-type-csv'] : '' , ENT_QUOTES));
                $bhFieldsArray['alternative-file-type-mp3'] = sanitize_text_field(htmlentities(!empty($field['alternative-file-type-mp3']) ? $field['alternative-file-type-mp3'] : '' , ENT_QUOTES));
                $bhFieldsArray['alternative-file-type-m4a'] = sanitize_text_field(htmlentities(!empty($field['alternative-file-type-m4a']) ? $field['alternative-file-type-m4a'] : '' , ENT_QUOTES));
                $bhFieldsArray['alternative-file-type-ogg'] = sanitize_text_field(htmlentities(!empty($field['alternative-file-type-ogg']) ? $field['alternative-file-type-ogg'] : '' , ENT_QUOTES));
                $bhFieldsArray['alternative-file-type-wav'] = sanitize_text_field(htmlentities(!empty($field['alternative-file-type-wav']) ? $field['alternative-file-type-wav'] : '' , ENT_QUOTES));
                $bhFieldsArray['alternative-file-type-mp4'] = sanitize_text_field(htmlentities(!empty($field['alternative-file-type-mp4']) ? $field['alternative-file-type-mp4'] : '' , ENT_QUOTES));
                // $bhFieldsArray['alternative-file-type-avi'] = sanitize_text_field(htmlentities(!empty($field['alternative-file-type-avi']) ? $field['alternative-file-type-avi'] : '' , ENT_QUOTES));
                $bhFieldsArray['alternative-file-type-mov'] = sanitize_text_field(htmlentities(!empty($field['alternative-file-type-mov']) ? $field['alternative-file-type-mov'] : '' , ENT_QUOTES));
                $bhFieldsArray['alternative-file-type-webm'] = sanitize_text_field(htmlentities(!empty($field['alternative-file-type-webm']) ? $field['alternative-file-type-webm'] : '' , ENT_QUOTES));
                $bhFieldsArray['alternative-file-type-ppt'] = sanitize_text_field(htmlentities(!empty($field['alternative-file-type-ppt']) ? $field['alternative-file-type-ppt'] : '' , ENT_QUOTES));
                $bhFieldsArray['alternative-file-type-pptx'] = sanitize_text_field(htmlentities(!empty($field['alternative-file-type-pptx']) ? $field['alternative-file-type-pptx'] : '' , ENT_QUOTES));
                //$bhFieldsArray['alternative-file-type-wmv'] = sanitize_text_field(htmlentities(!empty($field['alternative-file-type-wmv']) ? $field['alternative-file-type-wmv'] : '' , ENT_QUOTES));

                if(!empty($cgProFalse)){
                    $bhFieldsArray['alternative-file-type-pdf'] = '';
                    $bhFieldsArray['alternative-file-type-zip'] = '';
                    // $bhFieldsArray['alternative-file-type-txt'] = '';
                    //$bhFieldsArray['alternative-file-type-doc'] = '';
                    $bhFieldsArray['alternative-file-type-docx'] = '';
                    //$bhFieldsArray['alternative-file-type-xls'] = '';
                    $bhFieldsArray['alternative-file-type-xlsx'] = '';
                    //$bhFieldsArray['alternative-file-type-csv'] = '';
                    $bhFieldsArray['alternative-file-type-mp3'] = '';
                    $bhFieldsArray['alternative-file-type-m4a'] = '';
                    $bhFieldsArray['alternative-file-type-ogg'] = '';
                    $bhFieldsArray['alternative-file-type-wav'] = '';
                    $bhFieldsArray['alternative-file-type-mp4'] = '';
                    $bhFieldsArray['alternative-file-type-mov'] = '';
                    //$bhFieldsArray['alternative-file-type-avi'] = '';
                    $bhFieldsArray['alternative-file-type-webm'] = '';
                    //$bhFieldsArray['alternative-file-type-wmv'] = '';
                    //$bhFieldsArray['alternative-file-type-ppt'] = '';
                    $bhFieldsArray['alternative-file-type-pptx'] = '';
                }

                $bhFieldsArray['alternative-file-preview-required']= sanitize_text_field(htmlentities(!empty($field['alternative-file-preview-required']) ? $field['alternative-file-preview-required'] : '' , ENT_QUOTES));
                $bhFieldsArray['alternative-file-preview-hide']= sanitize_text_field(htmlentities(!empty($field['alternative-file-preview-hide']) ? $field['alternative-file-preview-hide'] : '' , ENT_QUOTES));

                $bhFieldsArray = serialize($bhFieldsArray);
                $order = $field['order'];


                if(empty($field['new'])){

                    $wpdb->update(
                        "$tablename_form_input",
                        array('GalleryID' => $GalleryID,'Field_Type' => 'image-f','Field_Order' => $order,'Field_Content' => $bhFieldsArray,'Active' => $active,'Show_Slider' => 0),
                        array('id' => $id),
                        array('%d','%s','%s','%s','%s'),
                        array('%d')
                    );

                }
                else{

                    $wpdb->query( $wpdb->prepare(
                        "
                      INSERT INTO $tablename_form_input
                      ( id, GalleryID, Field_Type, Field_Order, Field_Content, Show_Slider,Active)
                      VALUES ( %s,%d,%s,%d,%s,%d,%d )
                   ",
                        '',$GalleryID,'image-f',$order,$bhFieldsArray,0,$active
                    ) );

                }

            }

            if($field['type']=='cb' && $cgProVersion){// CHECK AGREEMENT!!!!!!!

                $cbFieldsArray = array();
                $cbFieldsArray['titel']=contest_gal1ery_htmlentities_and_preg_replace($field['title']);
                $cbFieldsArray['content'] = contest_gal1ery_htmlentities_and_preg_replace($field['content']);

                if(!empty($field['infoInSlider'])){
                    $Show_Slider = 1;
                }else{
                    $Show_Slider = 0;
                }

                $onOff = 'on';// check agreement always required

                if(empty($field['hide'])){
                    $active = 1;
                }else{
                    $active = 0;
                }

                $order = $field['order'];

                $cbFieldsArray['mandatory']=sanitize_text_field($onOff);

                /*                echo "<pre>";
                                print_r($cbFieldsArray);
                                echo "</pre>";*/

                $cbFieldsArray = serialize($cbFieldsArray);

                if(empty($field['new'])){
                    $wpdb->update(
                        "$tablename_form_input",
                        array('GalleryID' => $GalleryID,'Field_Type' => 'check-f','Field_Order' => $order,'Field_Content' => $cbFieldsArray,'Active' => $active,'Show_Slider' => $Show_Slider, 'Version' => $Version),
                        array('id' => $id),
                        array('%d','%s','%s','%s','%s','%s','%s'),
                        array('%d')
                    );
                    if(!empty($field['infoInGallery'])){$infoInGalleryId=$id;}
                    if(!empty($field['tagInGallery'])){$tagInGalleryId=$id;}

                }
                else{
                    $wpdb->query( $wpdb->prepare(
                        "
                                INSERT INTO $tablename_form_input
                      ( id, GalleryID, Field_Type, Field_Order, Field_Content, Show_Slider, Active, Version)
                      VALUES ( %s,%d,%s,%d,%s,%d,%d,%s )
                            ",
                        '',$GalleryID,'check-f',$order,$cbFieldsArray,$Show_Slider, $active, $Version
                    ) );

                    if(!empty($field['infoInGallery'])){$infoInGalleryId = $wpdb->get_var("SELECT id FROM $tablename_form_input ORDER BY id DESC LIMIT 1");}
                    if(!empty($field['tagInGallery'])){$tagInGalleryId = $wpdb->get_var("SELECT id FROM $tablename_form_input ORDER BY id DESC LIMIT 1");}

                }
            }

            if($field['type']=='nf' OR $field['type']=='fbt'){// TEXT FIELD!!!!!

                $nfFieldsArray = array();
                $nfFieldsArray['titel']=contest_gal1ery_htmlentities_and_preg_replace($field['title']);
                $nfFieldsArray['content'] = contest_gal1ery_htmlentities_and_preg_replace($field['content']);
                $nfFieldsArray['min-char'] = contest_gal1ery_htmlentities_and_preg_replace($field['min-char']);
                $nfFieldsArray['max-char'] = contest_gal1ery_htmlentities_and_preg_replace($field['max-char']);

                if(!empty($field['watermarkChecked'])){
                    $WatermarkPosition = $field['watermarkPosition'];
                    $WatermarkPositionForVisualOptions = $field['watermarkPosition'];
                }else{
                    $WatermarkPosition = '';
                }

                if(!empty($field['WpAttachmentDetailsType'])){
                    $WpAttachmentDetailsType = $field['WpAttachmentDetailsType'];
                    if(!$cgProVersion){
                        $WpAttachmentDetailsType = '';
                    }
                }else{
                    $WpAttachmentDetailsType = '';
                }

                if(!empty($field['infoInSlider'])){
                    $Show_Slider = 1;
                }else{
                    $Show_Slider = 0;
                }

                if(!empty($field['IsForWpPageTitle'])){
                    $IsForWpPageTitle = 1;
                }else{
                    $IsForWpPageTitle = 0;
                }

                if(!empty($field['IsForWpPageDescription'])){
                    $IsForWpPageDescription = 1;
                }else{
                    $IsForWpPageDescription = 0;
                }

                if(!empty($field['SubTitle'])){
                    $SubTitle = 1;
                }else{
                    $SubTitle = 0;
                }

                if(strpos($Version,'-PRO')===false){
                    $SubTitle = 0;
                }

                if(!empty($field['ThirdTitle'])){
                    $ThirdTitle = 1;
                }else{
                    $ThirdTitle = 0;
                }

                if(strpos($Version,'-PRO')===false){
                    $ThirdTitle = 0;
                }

                if(!empty($field['required'])){
                    if($field['required']=='on'){
                        $onOff = 'on';
                    }else{
                        $onOff = 'off';
                    }
                }else{
                    $onOff = 'off';
                }

                if(empty($field['hide'])){
                    $active = 1;
                }else{
                    $active = 0;
                }

                $nfFieldsArray['mandatory']=sanitize_text_field($onOff);

                /*
                echo "<pre>";
                print_r($nfFieldsArray);
                echo "</pre>";*/

                $nfFieldsArray = serialize($nfFieldsArray);
                $order = $field['order'];

                $fieldType = ($field['type']=='nf') ? 'text-f' : 'fbt-f';

                //var_dump('$WpAttachmentDetailsType333');
                //var_dump($WpAttachmentDetailsType);

                if(empty($field['new'])){
                    //var_dump('update');
                    //var_dump($id);
                    //var_dump($tablename_form_input);
                    $wpdb->update(
                        "$tablename_form_input",
                        array('GalleryID' => $GalleryID,'Field_Type' => $fieldType,'Field_Order' => $order,'Field_Content' => $nfFieldsArray,
                            'Active' => $active,'Show_Slider' => $Show_Slider,'WatermarkPosition' => $WatermarkPosition,'IsForWpPageTitle' => $IsForWpPageTitle,'IsForWpPageDescription' => $IsForWpPageDescription,'SubTitle' => $SubTitle,'ThirdTitle' => $ThirdTitle,'WpAttachmentDetailsType' => $WpAttachmentDetailsType),
                        array('id' => $id),
                        array('%d','%s','%s','%s','%s','%s','%s','%d','%d','%d','%d','%s'),
                        array('%d')
                    );
                    if(!empty($SubTitle)){$SubTitleToSet=$id;}
                    if(!empty($ThirdTitle)){$ThirdTitleToSet=$id;}
                    if(!empty($IsForWpPageTitle)){$IsForWpPageTitleID=$id;}
                    if(!empty($IsForWpPageDescription)){$IsForWpPageDescriptionID=$id;}
                    if(!empty($field['alternativeFileTypeName'])){$alternativeFileTypeNameId=$id;}
                    if(!empty($field['infoInGallery'])){$infoInGalleryId=$id;}
                    if(!empty($field['tagInGallery'])){$tagInGalleryId=$id;}
                }
                else{

                    $wpdb->query( $wpdb->prepare(
                        "
                                INSERT INTO $tablename_form_input
                      ( id, GalleryID, Field_Type, Field_Order, Field_Content, Show_Slider,Active,WatermarkPosition,IsForWpPageTitle,IsForWpPageDescription,SubTitle,ThirdTitle,WpAttachmentDetailsType)
                      VALUES ( %s,%d,%s,%d,%s,%d,%d,%s,%d,%d,%d,%d,%s )
                            ",
                        '',$GalleryID,$fieldType,$order,$nfFieldsArray,$Show_Slider,$active,$WatermarkPosition,$IsForWpPageTitle,$IsForWpPageDescription,$SubTitle,$ThirdTitle,$WpAttachmentDetailsType
                    ) );
                    if(!empty($SubTitle)){$SubTitleToSet=$wpdb->insert_id;}
                    if(!empty($ThirdTitle)){$ThirdTitleToSet=$wpdb->insert_id;}
                    if(!empty($IsForWpPageTitle)){$IsForWpPageTitleID=$wpdb->insert_id;}
                    if(!empty($IsForWpPageDescription)){$IsForWpPageDescriptionID=$wpdb->insert_id;}
                    if(!empty($field['alternativeFileTypeName'])){$alternativeFileTypeNameId = $wpdb->get_var("SELECT id FROM $tablename_form_input ORDER BY id DESC LIMIT 1");}
                    if(!empty($field['infoInGallery'])){$infoInGalleryId = $wpdb->get_var("SELECT id FROM $tablename_form_input ORDER BY id DESC LIMIT 1");}
                    if(!empty($field['tagInGallery'])){$tagInGalleryId = $wpdb->get_var("SELECT id FROM $tablename_form_input ORDER BY id DESC LIMIT 1");}

                }
            }

            if($field['type']=='dt'){// TEXT FIELD!!!!!

                $dtFieldsArray = array();
                $dtFieldsArray['titel']=contest_gal1ery_htmlentities_and_preg_replace($field['title']);
                $dtFieldsArray['format'] = contest_gal1ery_htmlentities_and_preg_replace($field['format']);


                if(!empty($field['infoInSlider'])){
                    $Show_Slider = 1;
                }else{
                    $Show_Slider = 0;
                }

                if(!empty($field['required'])){
                    if($field['required']=='on'){
                        $onOff = 'on';
                    }else{
                        $onOff = 'off';
                    }
                }else{
                    $onOff = 'off';
                }

                if(empty($field['hide'])){
                    $active = 1;
                }else{
                    $active = 0;
                }

                $dtFieldsArray['mandatory']=sanitize_text_field($onOff);

                /*
                echo "<pre>";
                print_r($dtFieldsArray);
                echo "</pre>";*/

                $dtFieldsArray = serialize($dtFieldsArray);
                $order = $field['order'];

                $fieldType = 'date-f';

                if(!empty($field['SubTitle'])){
                    $SubTitle = 1;
                }else{
                    $SubTitle = 0;
                }
                if(strpos($Version,'-PRO')===false){
                    $SubTitle = 0;
                }

                if(!empty($field['ThirdTitle'])){
                    $ThirdTitle = 1;
                }else{
                    $ThirdTitle = 0;
                }
                if(strpos($Version,'-PRO')===false){
                    $ThirdTitle = 0;
                }
                if(empty($field['new'])){
                    $wpdb->update(
                        "$tablename_form_input",
                        array('GalleryID' => $GalleryID,'Field_Type' => $fieldType,'Field_Order' => $order,'Field_Content' => $dtFieldsArray,'Active' => $active,'Show_Slider' => $Show_Slider,'SubTitle' => $SubTitle,'ThirdTitle' => $ThirdTitle),
                        array('id' => $id),
                        array('%d','%s','%s','%s','%s','%s','%d','%d'),
                        array('%d')
                    );
                    if(!empty($SubTitle)){$SubTitleToSet=$id;}
                    if(!empty($ThirdTitle)){$ThirdTitleToSet=$id;}
                    if(!empty($field['infoInGallery'])){$infoInGalleryId=$id;}
                    if(!empty($field['tagInGallery'])){$tagInGalleryId=$id;}

                }
                else{

                    $wpdb->query( $wpdb->prepare(
                        "
                                INSERT INTO $tablename_form_input
                      ( id, GalleryID, Field_Type, Field_Order, Field_Content, Show_Slider,Active,SubTitle,ThirdTitle)
                      VALUES ( %s,%d,%s,%d,%s,%d,%d,%d,%d )
                            ",
                        '',$GalleryID,$fieldType,$order,$dtFieldsArray,$Show_Slider,$active,$SubTitle,$ThirdTitle
                    ) );

                    if(!empty($SubTitle)){$SubTitleToSet=$wpdb->insert_id;}
                    if(!empty($ThirdTitle)){$ThirdTitleToSet=$wpdb->insert_id;}
                    if(!empty($field['infoInGallery'])){$infoInGalleryId = $wpdb->get_var("SELECT id FROM $tablename_form_input ORDER BY id DESC LIMIT 1");}
                    if(!empty($field['tagInGallery'])){$tagInGalleryId = $wpdb->get_var("SELECT id FROM $tablename_form_input ORDER BY id DESC LIMIT 1");}


                }
            }

            if($field['type']=='url'){

                $urlFieldsArray = array();
                $urlFieldsArray['titel']=contest_gal1ery_htmlentities_and_preg_replace($field['title']);
                $urlFieldsArray['content'] = contest_gal1ery_htmlentities_and_preg_replace($field['content']);

                if(!empty($field['infoInSlider'])){
                    $Show_Slider = 1;
                }else{
                    $Show_Slider = 0;
                }

                if(!empty($field['required'])){
                    if($field['required']=='on'){
                        $onOff = 'on';
                    }else{
                        $onOff = 'off';
                    }
                }else{
                    $onOff = 'off';
                }

                if(empty($field['hide'])){
                    $active = 1;
                }else{
                    $active = 0;
                }

                $urlFieldsArray['mandatory']=sanitize_text_field($onOff);
                $urlFieldsArray = serialize($urlFieldsArray);
                $order = $field['order'];

                if(empty($field['new'])){
                    $wpdb->update(
                        "$tablename_form_input",
                        array('GalleryID' => $GalleryID,'Field_Type' => 'url-f','Field_Order' => $order,'Field_Content' => $urlFieldsArray,'Active' => $active,'Show_Slider' => $Show_Slider),
                        array('id' => $id),
                        array('%d','%s','%s','%s','%s','%d','%s'),
                        array('%d')
                    );

                    if(!empty($field['infoInGallery'])){$infoInGalleryId=$id;}
                    if(!empty($field['tagInGallery'])){$tagInGalleryId=$id;}

                }
                else{

                    $wpdb->query( $wpdb->prepare(
                        "
                                INSERT INTO $tablename_form_input
                      ( id, GalleryID, Field_Type, Field_Order, Field_Content, Show_Slider,Active)
                      VALUES ( %s,%d,%s,%d,%s,%d,%d )
                            ",
                        '',$GalleryID,'url-f',$order,$urlFieldsArray,$Show_Slider,$active
                    ) );

                    if(!empty($field['infoInGallery'])){$infoInGalleryId = $wpdb->get_var("SELECT id FROM $tablename_form_input ORDER BY id DESC LIMIT 1");}
                    if(!empty($field['tagInGallery'])){$tagInGalleryId = $wpdb->get_var("SELECT id FROM $tablename_form_input ORDER BY id DESC LIMIT 1");}


                }
            }

            if($field['type']=='ef' && $cgProVersion){

                $efFieldsArray = array();
                $efFieldsArray['titel']=contest_gal1ery_htmlentities_and_preg_replace($field['title']);
                $efFieldsArray['content'] = contest_gal1ery_htmlentities_and_preg_replace($field['content']);

                if(!empty($field['infoInSlider'])){
                    $Show_Slider = 1;
                }else{
                    $Show_Slider = 0;
                }

                if(!empty($field['required'])){
                    if($field['required']=='on'){
                        $onOff = 'on';
                    }else{
                        $onOff = 'off';
                    }
                }else{
                    $onOff = 'off';
                }

                if(empty($field['hide'])){
                    $active = 1;
                }else{
                    $active = 0;
                }

                $efFieldsArray['mandatory']=sanitize_text_field($onOff);
                $efFieldsArray = serialize($efFieldsArray);
                $order = $field['order'];

                if(empty($field['new'])){
                    $wpdb->update(
                        "$tablename_form_input",
                        array('GalleryID' => $GalleryID,'Field_Type' => 'email-f','Field_Order' => $order,'Field_Content' => $efFieldsArray,'Active' => $active,'Show_Slider' => $Show_Slider),
                        array('id' => $id),
                        array('%d','%s','%s','%s','%s','%s'),
                        array('%d')
                    );

                }
                else{

                    $wpdb->query( $wpdb->prepare(
                        "
                                INSERT INTO $tablename_form_input
                      ( id, GalleryID, Field_Type, Field_Order, Field_Content, Show_Slider,Active)
                      VALUES ( %s,%d,%s,%d,%s,%d,%d )
                            ",
                        '',$GalleryID,'email-f',$order,$efFieldsArray,$Show_Slider,$active
                    ) );


                }
            }

            if($field['type']=='kf' OR $field['type']=='fbd'){

                if(!empty($field['WpAttachmentDetailsType'])){
                    $WpAttachmentDetailsType = $field['WpAttachmentDetailsType'];
                    if(!$cgProVersion){
                        $WpAttachmentDetailsType = '';
                    }
                }else{
                    $WpAttachmentDetailsType = '';
                }

                if(!empty($field['infoInSlider'])){
                    $Show_Slider = 1;
                }else{
                    $Show_Slider = 0;
                }

                $kfFieldsArray = array();
                $kfFieldsArray['titel']=contest_gal1ery_htmlentities_and_preg_replace($field['title']);
                $kfFieldsArray['content'] = contest_gal1ery_htmlentities_and_preg_replace_textarea($field['content']);
                $kfFieldsArray['min-char'] = contest_gal1ery_htmlentities_and_preg_replace($field['min-char']);
                $kfFieldsArray['max-char'] = contest_gal1ery_htmlentities_and_preg_replace($field['max-char']);

                if(!empty($field['required'])){
                    if($field['required']=='on'){
                        $onOff = 'on';
                    }else{
                        $onOff = 'off';
                    }
                }else{
                    $onOff = 'off';
                }

                if(empty($field['hide'])){
                    $active = 1;
                }else{
                    $active = 0;
                }

                $kfFieldsArray['mandatory']=sanitize_text_field($onOff);
                $kfFieldsArray = serialize($kfFieldsArray);
                $order = $field['order'];

                $fieldType = ($field['type']=='kf') ? 'comment-f' : 'fbd-f';

                if(!empty($field['IsForWpPageDescription'])){
                    $IsForWpPageDescription = 1;
                }else{
                    $IsForWpPageDescription = 0;
                }

                if(!empty($field['SubTitle'])){
                    $SubTitle = 1;
                }else{
                    $SubTitle = 0;
                }
                if(strpos($Version,'-PRO')===false){
                    $SubTitle = 0;
                }
                if(!empty($field['ThirdTitle'])){
                    $ThirdTitle = 1;
                }else{
                    $ThirdTitle = 0;
                }


                if(strpos($Version,'-PRO')===false){
                    $ThirdTitle = 0;
                }
                if(empty($field['new'])){
                    $wpdb->update(
                        "$tablename_form_input",
                        array('GalleryID' => $GalleryID,'Field_Type' => $fieldType,'Field_Order' => $order,'Field_Content' => $kfFieldsArray,
                            'Active' => $active,'Show_Slider' => $Show_Slider,'IsForWpPageDescription' => $IsForWpPageDescription,'SubTitle' => $SubTitle,'ThirdTitle' => $ThirdTitle,'WpAttachmentDetailsType' => $WpAttachmentDetailsType),
                        array('id' => $id),
                        array('%d','%s','%s','%s','%s','%s','%d','%d','%d','%s'),
                        array('%d')
                    );
                    if(!empty($SubTitle)){$SubTitleToSet=$id;}
                    if(!empty($ThirdTitle)){$ThirdTitleToSet=$id;}
                    if(!empty($IsForWpPageDescription)){$IsForWpPageDescriptionID=$id;}
                    if(!empty($field['infoInGallery'])){$infoInGalleryId=$id;}
                    if(!empty($field['tagInGallery'])){$tagInGalleryId=$id;}

                }
                else{

                    $wpdb->query( $wpdb->prepare(
                        "
                                INSERT INTO $tablename_form_input
                      ( id, GalleryID, Field_Type, Field_Order, Field_Content, Show_Slider,Active,IsForWpPageDescription,SubTitle,ThirdTitle,WpAttachmentDetailsType)
                      VALUES ( %s,%d,%s,%d,%s,%d,%d,%d,%d,%d,%s )
                            ",
                        '',$GalleryID,$fieldType,$order,$kfFieldsArray,$Show_Slider,$active,$IsForWpPageDescription,$SubTitle,$ThirdTitle,$WpAttachmentDetailsType
                    ) );
                    if(!empty($SubTitle)){$SubTitleToSet=$wpdb->insert_id;}
                    if(!empty($ThirdTitle)){$ThirdTitleToSet=$wpdb->insert_id;}
                    if(!empty($IsForWpPageDescription)){$IsForWpPageDescriptionID=$wpdb->insert_id;}
                    if(!empty($field['infoInGallery'])){$infoInGalleryId = $wpdb->get_var("SELECT id FROM $tablename_form_input ORDER BY id DESC LIMIT 1");}
                    if(!empty($field['tagInGallery'])){$tagInGalleryId = $wpdb->get_var("SELECT id FROM $tablename_form_input ORDER BY id DESC LIMIT 1");}


                }
            }

            if($field['type']=='ht' && $cgProVersion){

                if(!empty($field['infoInSlider'])){
                    $Show_Slider = 1;
                }else{
                    $Show_Slider = 0;
                }

                $htFieldsArray = array();
                $htFieldsArray['titel']=contest_gal1ery_htmlentities_and_preg_replace($field['title']);
                //$htFieldsArray['content'] = sanitize_text_field(htmlentities($field['content'], ENT_QUOTES));
                $htFieldsArray['content'] = contest_gal1ery_htmlentities_and_preg_replace($field['content']);

                // no need for html field
                /*            if(!empty($field['required'])){
                                $onOff = 'on';
                            }else{
                                $onOff = 'off';
                            }
                            $htFieldsArray['mandatory']=sanitize_text_field($onOff);*/

                if(empty($field['hide'])){
                    $active = 1;
                }else{
                    $active = 0;
                }

                $htFieldsArray = serialize($htFieldsArray);
                $order = $field['order'];

                if(empty($field['new'])){
                    $wpdb->update(
                        "$tablename_form_input",
                        array('GalleryID' => $GalleryID,'Field_Type' => 'html-f','Field_Order' => $order,'Field_Content' => $htFieldsArray,'Active' => $active),
                        array('id' => $id),
                        array('%d','%s','%s','%s','%s'),
                        array('%d')
                    );
                    if(!empty($field['infoInGallery'])){$infoInGalleryId=$id;}
                    if(!empty($field['tagInGallerytagInGallery'])){$tagInGalleryId=$id;}

                }
                else{

                    $wpdb->query( $wpdb->prepare(
                        "
                                INSERT INTO $tablename_form_input
                      ( id, GalleryID, Field_Type, Field_Order, Field_Content, Show_Slider,Active)
                      VALUES ( %s,%d,%s,%d,%s,%d,%d )
                            ",
                        '',$GalleryID,'html-f',$order,$htFieldsArray,$Show_Slider,$active
                    ) );

                    if(!empty($field['infoInGallery'])){$infoInGalleryId = $wpdb->get_var("SELECT id FROM $tablename_form_input ORDER BY id DESC LIMIT 1");}
                    if(!empty($field['tagInGallery'])){$tagInGalleryId = $wpdb->get_var("SELECT id FROM $tablename_form_input ORDER BY id DESC LIMIT 1");}


                }
            }

            if($field['type']=='caRo'){

                $caFieldsArray = array();
                $caFieldsArray['titel']=contest_gal1ery_htmlentities_and_preg_replace($field['title']);

                if(!empty($field['infoInSlider'])){
                    $Show_Slider = 1;
                }else{
                    $Show_Slider = 0;
                }

                $onOff = 'off';// I am not robot captcha always required

                if(empty($field['hide'])){
                    $active = 1;
                }else{
                    $active = 0;
                }

                $caFieldsArray['mandatory']=sanitize_text_field($onOff);
                $caFieldsArray = serialize($caFieldsArray);
                $order = $field['order'];

                if(empty($field['new'])){
                    $wpdb->update(
                        "$tablename_form_input",
                        array('GalleryID' => $GalleryID,'Field_Type' => 'caRo-f','Field_Order' => $order,'Field_Content' => $caFieldsArray,'Active' => $active,'Show_Slider' => $Show_Slider),
                        array('id' => $id),
                        array('%d','%s','%s','%s','%s','%s'),
                        array('%d')
                    );
                    if(!empty($field['infoInGallery'])){$infoInGalleryId=$id;}
                    if(!empty($field['tagInGallery'])){$tagInGalleryId=$id;}

                }
                else{

                    $wpdb->query( $wpdb->prepare(
                        "
                                INSERT INTO $tablename_form_input
                      ( id, GalleryID, Field_Type, Field_Order, Field_Content, Show_Slider,Active)
                      VALUES ( %s,%d,%s,%d,%s,%d,%d )
                            ",
                        '',$GalleryID,'caRo-f',$order,$caFieldsArray,$Show_Slider,$active
                    ) );

                    if(!empty($field['infoInGallery'])){$infoInGalleryId = $wpdb->get_var("SELECT id FROM $tablename_form_input ORDER BY id DESC LIMIT 1");}
                    if(!empty($field['tagInGallery'])){$tagInGalleryId = $wpdb->get_var("SELECT id FROM $tablename_form_input ORDER BY id DESC LIMIT 1");}


                }
            }

            if($field['type']=='caRoRe'){

                $caFieldsArray = array();
                if(!empty($field['title'])){
                    $caFieldsArray['titel']=contest_gal1ery_htmlentities_and_preg_replace($field['title']);
                }else{
                    $caFieldsArray['titel']='';
                }

                if(!empty($field['infoInSlider'])){
                    $Show_Slider = 1;
                }else{
                    $Show_Slider = 0;
                }

                $onOff = 'on';

                if(empty($field['hide'])){
                    $active = 1;
                }else{
                    $active = 0;
                }

                $caFieldsArray['mandatory']=sanitize_text_field($onOff);
                $caFieldsArray = serialize($caFieldsArray);
                $order = $field['order'];
                $ReCaKey = $field['ReCaKey'];
                $ReCaLang = $field['ReCaLang'];

                if(empty($field['new'])){
                    $wpdb->update(
                        "$tablename_form_input",
                        array('GalleryID' => $GalleryID,'Field_Type' => 'caRoRe-f','Field_Order' => $order,'Field_Content' => $caFieldsArray,'Active' => $active,'Show_Slider' => $Show_Slider,'ReCaKey' => $ReCaKey,'ReCaLang' => $ReCaLang),
                        array('id' => $id),
                        array('%d','%s','%s','%s','%s','%s','%s','%s'),
                        array('%d')
                    );
                    if(!empty($field['infoInGallery'])){$infoInGalleryId=$id;}
                    if(!empty($field['tagInGallery'])){$tagInGalleryId=$id;}

                }
                else{

                    $wpdb->query( $wpdb->prepare(
                        "
                                INSERT INTO $tablename_form_input
                      ( id, GalleryID, Field_Type, Field_Order, Field_Content, Show_Slider,Active, ReCaKey, ReCaLang)
                      VALUES ( %s,%d,%s,%d,%s,%d,%d,%s,%s )
                            ",
                        '',$GalleryID,'caRoRe-f',$order,$caFieldsArray,$Show_Slider,$active,$ReCaKey,$ReCaLang
                    ) );

                    if(!empty($field['infoInGallery'])){$infoInGalleryId = $wpdb->get_var("SELECT id FROM $tablename_form_input ORDER BY id DESC LIMIT 1");}
                    if(!empty($field['tagInGallery'])){$tagInGalleryId = $wpdb->get_var("SELECT id FROM $tablename_form_input ORDER BY id DESC LIMIT 1");}


                }
            }

            if($field['type']=='se'){

                $seFieldsArray = array();
                $seFieldsArray['titel']=contest_gal1ery_htmlentities_and_preg_replace($field['title']);
                $seFieldsArray['content'] = contest_gal1ery_htmlentities_and_preg_replace($field['content']);

                if(!empty($field['watermarkChecked'])){
                    $WatermarkPosition = $field['watermarkPosition'];
                    $WatermarkPositionForVisualOptions = $field['watermarkPosition'];
                }else{
                    $WatermarkPosition = '';
                }

                if(!empty($field['infoInSlider'])){
                    $Show_Slider = 1;
                }else{
                    $Show_Slider = 0;
                }

                if(!empty($field['required'])){
                    if($field['required']=='on'){
                        $onOff = 'on';
                    }else{
                        $onOff = 'off';
                    }
                }else{
                    $onOff = 'off';
                }

                if(empty($field['hide'])){
                    $active = 1;
                }else{
                    $active = 0;
                }

                $seFieldsArray['mandatory']=sanitize_text_field($onOff);
                $seFieldsArray = serialize($seFieldsArray);

                $order = $field['order'];

                if(!empty($field['SubTitle'])){
                    $SubTitle = 1;
                }else{
                    $SubTitle = 0;
                }
                if(strpos($Version,'-PRO')===false){
                    $SubTitle = 0;
                }
                if(!empty($field['ThirdTitle'])){
                    $ThirdTitle = 1;
                }else{
                    $ThirdTitle = 0;
                }
                if(strpos($Version,'-PRO')===false){
                    $ThirdTitle = 0;
                }
                if(empty($field['new'])){
                    $wpdb->update(
                        "$tablename_form_input",
                        array('GalleryID' => $GalleryID,'Field_Type' => 'select-f','Field_Order' => $order,'Field_Content' => $seFieldsArray,'Active' => $active,'Show_Slider' => $Show_Slider,'WatermarkPosition' => $WatermarkPosition,'SubTitle' => $SubTitle,'ThirdTitle' => $ThirdTitle),
                        array('id' => $id),
                        array('%d','%s','%s','%s','%s','%s','%s','%d','%d'),
                        array('%d')
                    );
                    if(!empty($SubTitle)){$SubTitleToSet=$id;}
                    if(!empty($ThirdTitle)){$ThirdTitleToSet=$id;}
                    if(!empty($field['infoInGallery'])){$infoInGalleryId=$id;}
                    if(!empty($field['tagInGallery'])){$tagInGalleryId=$id;}

                }
                else{

                    $wpdb->query( $wpdb->prepare(
                        "
                                INSERT INTO $tablename_form_input
                      ( id, GalleryID, Field_Type, Field_Order, Field_Content, Show_Slider,Active,WatermarkPosition,SubTitle,ThirdTitle)
                      VALUES ( %s,%d,%s,%d,%s,%d,%d,%s,%d,%d )
                            ",
                        '',$GalleryID,'select-f',$order,$seFieldsArray,$Show_Slider,$active,$WatermarkPosition,$SubTitle,$ThirdTitle
                    ) );

                    if(!empty($SubTitle)){$SubTitleToSet=$wpdb->insert_id;}
                    if(!empty($ThirdTitle)){$ThirdTitleToSet=$wpdb->insert_id;}
                    if(!empty($field['infoInGallery'])){$infoInGalleryId = $wpdb->get_var("SELECT id FROM $tablename_form_input ORDER BY id DESC LIMIT 1");}
                    if(!empty($field['tagInGallery'])){$tagInGalleryId = $wpdb->get_var("SELECT id FROM $tablename_form_input ORDER BY id DESC LIMIT 1");}


                }
            }

            if($field['type']=='sec'){

                $secFieldsArray = array();
                $secFieldsArray['titel']=contest_gal1ery_htmlentities_and_preg_replace($field['title']);

                if(!empty($field['watermarkChecked'])){
                    $WatermarkPosition = $field['watermarkPosition'];
                    $WatermarkPositionForVisualOptions = $field['watermarkPosition'];
                }else{
                    $WatermarkPosition = '';
                }

                if(!empty($field['infoInSlider'])){
                    $Show_Slider = 1;
                }else{
                    $Show_Slider = 0;
                }

                if(!empty($field['required'])){
                    if($field['required']=='on'){
                        $onOff = 'on';
                    }else{
                        $onOff = 'off';
                    }
                }else{
                    $onOff = 'off';
                }

                if(empty($field['hide'])){
                    $active = 1;
                }else{
                    $active = 0;
                }

                $secFieldsArray['mandatory']=sanitize_text_field($onOff);
                $secFieldsArray = serialize($secFieldsArray);

                $order = $field['order'];

                if(!empty($field['SubTitle'])){
                    $SubTitle = 1;
                }else{
                    $SubTitle = 0;
                }
                if(strpos($Version,'-PRO')===false){
                    $SubTitle = 0;
                }
                if(!empty($field['ThirdTitle'])){
                    $ThirdTitle = 1;
                }else{
                    $ThirdTitle = 0;
                }
                if(strpos($Version,'-PRO')===false){
                    $ThirdTitle = 0;
                }
                if(empty($field['new'])){
                    $wpdb->update(
                        "$tablename_form_input",
                        array('GalleryID' => $GalleryID,'Field_Type' => 'selectc-f','Field_Order' => $order,'Field_Content' => $secFieldsArray,'Active' => $active,'Show_Slider' => $Show_Slider,'WatermarkPosition' => $WatermarkPosition,'SubTitle' => $SubTitle,'ThirdTitle' => $ThirdTitle),
                        array('id' => $id),
                        array('%d','%s','%s','%s','%s','%s','%s','%d','%d'),
                        array('%d')
                    );

                    if(!empty($SubTitle)){$SubTitleToSet=$id;}
                    if(!empty($ThirdTitle)){$ThirdTitleToSet=$id;}
                    if(!empty($field['infoInGallery'])){$infoInGalleryId=$id;}
                    if(!empty($field['tagInGallery'])){
                        $tagInGalleryIdIsForCategories = true;
                        $tagInGalleryId=$id;
                    }

                }
                else{

                    $wpdb->query( $wpdb->prepare(
                        "
                                INSERT INTO $tablename_form_input
                      ( id, GalleryID, Field_Type, Field_Order, Field_Content, Show_Slider,Active,WatermarkPosition,SubTitle,ThirdTitle)
                      VALUES ( %s,%d,%s,%d,%s,%d,%d,%s,%d,%d)
                            ",
                        '',$GalleryID,'selectc-f',$order,$secFieldsArray,$Show_Slider,$active,$WatermarkPosition,$SubTitle,$ThirdTitle
                    ) );

                    if(!empty($SubTitle)){$SubTitleToSet=$wpdb->insert_id;}
                    if(!empty($ThirdTitle)){$ThirdTitleToSet=$wpdb->insert_id;}

                    $wpdb->update(
                        "$tablename_pro_options",
                        array('ShowOther' => 1, 'CatWidget' => 1),
                        array('GalleryID' => $GalleryID),
                        array('%d','%d'),
                        array('%s')
                    );

                    if(!empty($field['infoInGallery'])){$infoInGalleryId = $wpdb->get_var("SELECT id FROM $tablename_form_input ORDER BY id DESC LIMIT 1");}
                    if(!empty($field['tagInGallery'])){
                        $tagInGalleryIdIsForCategories = true;
                        $tagInGalleryId = $wpdb->get_var("SELECT id FROM $tablename_form_input ORDER BY id DESC LIMIT 1");
                    }

                }
            }

        }

    }

    $isResaveOptionsJson = false;

    // update watermark position
    // only a JSON option, will be not saved in table
    if(!empty($optionsFileData[$GalleryID])){
        $optionsFileData[$GalleryID]['visual']['WatermarkPosition'] = $WatermarkPositionForVisualOptions;
        $optionsFileData[$GalleryID.'-u']['visual']['WatermarkPosition'] = $WatermarkPositionForVisualOptions;
        $optionsFileData[$GalleryID.'-nv']['visual']['WatermarkPosition'] = $WatermarkPositionForVisualOptions;
        $optionsFileData[$GalleryID.'-w']['visual']['WatermarkPosition'] = $WatermarkPositionForVisualOptions;
    }else{
        $optionsFileData['visual']['WatermarkPosition'] = $WatermarkPositionForVisualOptions;
    }

    if(!empty($optionsFileData[$GalleryID])){
        $optionsFileData[$GalleryID]['visual']['SubTitle'] = 0;
        $optionsFileData[$GalleryID.'-u']['visual']['SubTitle'] = 0;
        $optionsFileData[$GalleryID.'-nv']['visual']['SubTitle'] = 0;
        $optionsFileData[$GalleryID.'-w']['visual']['SubTitle'] = 0;
    }else{
        $optionsFileData['visual']['SubTitle'] = 0;
    }

    if(!empty($optionsFileData[$GalleryID])){
        $optionsFileData[$GalleryID]['visual']['ThirdTitle'] = 0;
        $optionsFileData[$GalleryID.'-u']['visual']['ThirdTitle'] = 0;
        $optionsFileData[$GalleryID.'-nv']['visual']['ThirdTitle'] = 0;
        $optionsFileData[$GalleryID.'-w']['visual']['ThirdTitle'] = 0;
    }else{
        $optionsFileData['visual']['ThirdTitle'] = 0;
    }

    if(!empty($optionsFileData[$GalleryID])){
        $optionsFileData[$GalleryID]['visual']['IsForWpPageTitleID'] = 0;
        $optionsFileData[$GalleryID.'-u']['visual']['IsForWpPageTitleID'] = 0;
        $optionsFileData[$GalleryID.'-nv']['visual']['IsForWpPageTitleID'] = 0;
        $optionsFileData[$GalleryID.'-w']['visual']['IsForWpPageTitleID'] = 0;
    }else{
        $optionsFileData['visual']['IsForWpPageTitleID'] = 0;
    }

    if(!empty($optionsFileData[$GalleryID])){
        $optionsFileData[$GalleryID]['visual']['IsForWpPageDescriptionID'] = 0;
        $optionsFileData[$GalleryID.'-u']['visual']['IsForWpPageDescriptionID'] = 0;
        $optionsFileData[$GalleryID.'-nv']['visual']['IsForWpPageDescriptionID'] = 0;
        $optionsFileData[$GalleryID.'-w']['visual']['IsForWpPageDescriptionID'] = 0;
    }else{
        $optionsFileData['visual']['IsForWpPageDescriptionID'] = 0;
    }

    if(!empty($optionsFileData[$GalleryID])){
        $optionsFileData[$GalleryID]['visual']['Field1IdGalleryView'] = 0;
        $optionsFileData[$GalleryID.'-u']['visual']['Field1IdGalleryView'] = 0;
        $optionsFileData[$GalleryID.'-nv']['visual']['Field1IdGalleryView'] = 0;
        $optionsFileData[$GalleryID.'-w']['visual']['Field1IdGalleryView'] = 0;
    }else{
        $optionsFileData['visual']['Field1IdGalleryView'] = 0;
    }

    if(!empty($optionsFileData[$GalleryID])){
        $optionsFileData[$GalleryID]['visual']['Field2IdGalleryView'] = 0;
        $optionsFileData[$GalleryID.'-u']['visual']['Field2IdGalleryView'] = 0;
        $optionsFileData[$GalleryID.'-nv']['visual']['Field2IdGalleryView'] = 0;
        $optionsFileData[$GalleryID.'-w']['visual']['Field2IdGalleryView'] = 0;
    }else{
        $optionsFileData['visual']['Field2IdGalleryView'] = 0;
    }

    if(!empty($optionsFileData[$GalleryID])){
        $optionsFileData[$GalleryID]['visual']['Field3IdGalleryView'] = 0;
        $optionsFileData[$GalleryID.'-u']['visual']['Field3IdGalleryView'] = 0;
        $optionsFileData[$GalleryID.'-nv']['visual']['Field3IdGalleryView'] = 0;
        $optionsFileData[$GalleryID.'-w']['visual']['Field3IdGalleryView'] = 0;
    }else{
        $optionsFileData['visual']['Field3IdGalleryView'] = 0;
    }

    if(!empty($SubTitleToSet)){
        if(!empty($optionsFileData[$GalleryID])){
            $optionsFileData[$GalleryID]['visual']['SubTitle'] = $SubTitleToSet;
            $optionsFileData[$GalleryID.'-u']['visual']['SubTitle'] = $SubTitleToSet;
            $optionsFileData[$GalleryID.'-nv']['visual']['SubTitle'] = $SubTitleToSet;
            $optionsFileData[$GalleryID.'-w']['visual']['SubTitle'] = $SubTitleToSet;
        }else{
            $optionsFileData['visual']['SubTitle'] = $SubTitleToSet;
        }
    }

    if(!empty($ThirdTitleToSet)){
        if(!empty($optionsFileData[$GalleryID])){
            $optionsFileData[$GalleryID]['visual']['ThirdTitle'] = $ThirdTitleToSet;
            $optionsFileData[$GalleryID.'-u']['visual']['ThirdTitle'] = $ThirdTitleToSet;
            $optionsFileData[$GalleryID.'-nv']['visual']['ThirdTitle'] = $ThirdTitleToSet;
            $optionsFileData[$GalleryID.'-w']['visual']['ThirdTitle'] = $ThirdTitleToSet;
        }else{
            $optionsFileData['visual']['ThirdTitle'] = $ThirdTitleToSet;
        }
    }

    if(!empty($IsForWpPageTitleID)){
        if(!empty($optionsFileData[$GalleryID])){
            $optionsFileData[$GalleryID]['visual']['IsForWpPageTitleID'] = $IsForWpPageTitleID;
            $optionsFileData[$GalleryID.'-u']['visual']['IsForWpPageTitleID'] = $IsForWpPageTitleID;
            $optionsFileData[$GalleryID.'-nv']['visual']['IsForWpPageTitleID'] = $IsForWpPageTitleID;
            $optionsFileData[$GalleryID.'-w']['visual']['IsForWpPageTitleID'] = $IsForWpPageTitleID;
        }else{
            $optionsFileData['visual']['IsForWpPageTitleID'] = $IsForWpPageTitleID;
        }
    }

    if(!empty($IsForWpPageDescriptionID)){

        if(!empty($optionsFileData[$GalleryID])){
            $optionsFileData[$GalleryID]['visual']['IsForWpPageDescriptionID'] = $IsForWpPageDescriptionID;
            $optionsFileData[$GalleryID.'-u']['visual']['IsForWpPageDescriptionID'] = $IsForWpPageDescriptionID;
            $optionsFileData[$GalleryID.'-nv']['visual']['IsForWpPageDescriptionID'] = $IsForWpPageDescriptionID;
            $optionsFileData[$GalleryID.'-w']['visual']['IsForWpPageDescriptionID'] = $IsForWpPageDescriptionID;
        }else{
            $optionsFileData['visual']['IsForWpPageDescriptionID'] = $IsForWpPageDescriptionID;
        }
    }

    // falls Show info in gallery gesetzt wurde dann inserten
    if(!empty($infoInGalleryId)){

        $wpdb->update(
            "$tablename_options_visual",
            array('Field1IdGalleryView' => $infoInGalleryId),
            array('GalleryID' => $GalleryID),
            array('%d'),
            array('%d')
        );

        if(!empty($optionsFileData[$GalleryID])){
            $optionsFileData[$GalleryID]['visual']['Field1IdGalleryView'] = $infoInGalleryId;
            $optionsFileData[$GalleryID.'-u']['visual']['Field1IdGalleryView'] = $infoInGalleryId;
            $optionsFileData[$GalleryID.'-nv']['visual']['Field1IdGalleryView'] = $infoInGalleryId;
            $optionsFileData[$GalleryID.'-w']['visual']['Field1IdGalleryView'] = $infoInGalleryId;
        }else{
            $optionsFileData['visual']['Field1IdGalleryView'] = $infoInGalleryId;
        }

    }else{
        $wpdb->update(
            "$tablename_options_visual",
            array('Field1IdGalleryView' => 0),
            array('GalleryID' => $GalleryID),
            array('%d'),
            array('%d')
        );
    }

    // falls Show info in gallery gesetzt wurde dann inserten
    if(!empty($tagInGalleryId)){

        $wpdb->update(
            "$tablename_options_visual",
            array('Field2IdGalleryView' => $tagInGalleryId),
            array('GalleryID' => $GalleryID),
            array('%d'),
            array('%d')
        );

        if(!empty($optionsFileData[$GalleryID])){
            $optionsFileData[$GalleryID]['visual']['Field2IdGalleryView'] = $tagInGalleryId;
            $optionsFileData[$GalleryID.'-u']['visual']['Field2IdGalleryView'] = $tagInGalleryId;
            $optionsFileData[$GalleryID.'-nv']['visual']['Field2IdGalleryView'] = $tagInGalleryId;
            $optionsFileData[$GalleryID.'-w']['visual']['Field2IdGalleryView'] = $tagInGalleryId;
        }else{
            $optionsFileData['visual']['Field2IdGalleryView'] = $tagInGalleryId;
        }

    }else{
        $wpdb->update(
            "$tablename_options_visual",
            array('Field2IdGalleryView' => 0),
            array('GalleryID' => $GalleryID),
            array('%d'),
            array('%d')
        );
    }

    // falls Use as file name in single view gesetzt wurde dann inserten
    if(!empty($alternativeFileTypeNameId)){

        $wpdb->update(
            "$tablename_options_visual",
            array('Field3IdGalleryView' => $alternativeFileTypeNameId),
            array('GalleryID' => $GalleryID),
            array('%d'),
            array('%d')
        );

        if(!empty($optionsFileData[$GalleryID])){
            $optionsFileData[$GalleryID]['visual']['Field3IdGalleryView'] = $alternativeFileTypeNameId;
            $optionsFileData[$GalleryID.'-u']['visual']['Field3IdGalleryView'] = $alternativeFileTypeNameId;
            $optionsFileData[$GalleryID.'-nv']['visual']['Field3IdGalleryView'] = $alternativeFileTypeNameId;
            $optionsFileData[$GalleryID.'-w']['visual']['Field3IdGalleryView'] = $alternativeFileTypeNameId;
        }else{
            $optionsFileData['visual']['Field3IdGalleryView'] = $alternativeFileTypeNameId;
        }

    }else{

        $wpdb->update(
            "$tablename_options_visual",
            array('Field3IdGalleryView' => 0),
            array('GalleryID' => $GalleryID),
            array('%d'),
            array('%d')
        );

    }

    $optionsFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-options.json';
    file_put_contents($optionsFile,json_encode($optionsFileData));

    if(!empty($_POST['cg_category'])) {

        // make json file

        $categories = $wpdb->get_results($wpdb->prepare( "SELECT * FROM $tablename_categories WHERE GalleryID = %d ORDER BY Field_Order",[$GalleryID]));

        $categoriesFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-categories.json';

        $categoriesArray = array();

        foreach($categories as $category){

            if($tagInGalleryIdIsForCategories){
                $category->isShowTagInGallery = true;
            }

            $categoriesArray[$category->id] = $category;

        }

        $fp = fopen($categoriesFile, 'w');
        fwrite($fp, json_encode($categoriesArray));
        fclose($fp);

    }else{

        $categoriesFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-categories.json';

        $fp = fopen($categoriesFile, 'w');
        fwrite($fp, json_encode(array()));
        fclose($fp);

    }

    do_action('cg_json_upload_form',$GalleryID);
        //do_action('cg_json_upload_form_info_data_files',$GalleryID,null);
        cg_json_upload_form_info_data_files_new($GalleryID);
    do_action('cg_json_single_view_order',$GalleryID);

    $tstampFile = $wp_upload_dir["basedir"]."/contest-gallery/gallery-id-$GalleryID/json/$GalleryID-gallery-tstamp.json";
    $fp = fopen($tstampFile, 'w');
    fwrite($fp, json_encode(time()));
    fclose($fp);


}


// input felder holen zur ausgabe
$selectFormInput = $wpdb->get_results($wpdb->prepare( "SELECT * FROM $tablename_form_input WHERE GalleryID = %d ORDER BY Field_Order ASC",[$GalleryID]));

$rowVisualOptions = $wpdb->get_row($wpdb->prepare( "SELECT * FROM $tablename_options_visual WHERE GalleryID = %d",[$GalleryID]));

$Field1IdGalleryView = $rowVisualOptions->Field1IdGalleryView;
$Field2IdGalleryView = $rowVisualOptions->Field2IdGalleryView;
$Field3IdGalleryView = $rowVisualOptions->Field3IdGalleryView;

?>