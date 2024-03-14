<?php

global $wpdb;


$tablename = $wpdb->base_prefix . "$i"."contest_gal1ery";
$tablename_ip = $wpdb->base_prefix . "$i"."contest_gal1ery_ip";
$tablename_comments = $wpdb->base_prefix . "$i"."contest_gal1ery_comments";
$tablename_options = $wpdb->base_prefix . "$i"."contest_gal1ery_options";
$tablename_options_input = $wpdb->base_prefix . "$i"."contest_gal1ery_options_input";
$tablename_options_visual = $wpdb->base_prefix . "$i"."contest_gal1ery_options_visual";
$tablename_email = $wpdb->base_prefix . "$i"."contest_gal1ery_mail";
$tablename_email_admin = $wpdb->base_prefix . "$i"."contest_gal1ery_mail_admin";
$tablename_entries = $wpdb->base_prefix . "$i"."contest_gal1ery_entries";
$tablename_create_user_entries = $wpdb->base_prefix . "$i"."contest_gal1ery_create_user_entries";
$tablename_pro_options = $wpdb->base_prefix . "$i"."contest_gal1ery_pro_options";
$tablename_create_user_form = $wpdb->base_prefix . "$i"."contest_gal1ery_create_user_form";
$tablename_form_input = $wpdb->base_prefix . "$i"."contest_gal1ery_f_input";
$tablename_form_output = $wpdb->base_prefix . "$i"."contest_gal1ery_f_output";
//  $tablename_mail_gallery = $wpdb->base_prefix . "$i"."contest_gal1ery_mail_gallery";
//  $tablename_mail_gallery_users_history = $wpdb->base_prefix . "$i"."contest_gal1ery_mail_gallery_users_history";
$tablename_mails_collected = $wpdb->base_prefix . "$i"."contest_gal1ery_mails_collected";
$tablename_mail_confirmation = $wpdb->base_prefix . "$i"."contest_gal1ery_mail_confirmation";
$tablename_categories = $wpdb->base_prefix . "$i"."contest_gal1ery_categories";
$tablename_registry_and_login_options = $wpdb->base_prefix . "$i"."contest_gal1ery_registry_and_login_options";
$tablename_google_options = $wpdb->base_prefix . "$i"."contest_gal1ery_google_options";
$tablename_google_users = $wpdb->base_prefix . "$i"."contest_gal1ery_google_users";

$columnsToRepairArray = array();

$updateArray = include('update-conf.php');

if(!isset($errorsArray)){
    $errorsArray = array();
}

// add collate where is required and might be missing

// Collation check for input entries was added in 21.0.1
$tablenameEntries = $wpdb->prefix . "contest_gal1ery_entries";
$tableStatus =  $wpdb->get_results("SHOW TABLE STATUS where name like '$tablenameEntries'");
if(isset($tableStatus[0]) && isset($tableStatus[0]->Collation) && $tableStatus[0]->Collation!='utf8mb4_unicode_520_ci'){
    //if(!$wpdb->query("ALTER TABLE $tablenameEntries CONVERT TO CHARACTER SET 'utf8mb4_unicode_520_ci' ")){
    if(!$wpdb->query("ALTER TABLE $tablenameEntries CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci")){
        $wpdb->show_errors();
        ob_start();
        $wpdb->print_error();
        $errorsArray[$tablenameEntries] = ob_get_clean();
    }
}

// check here required because might be done twice because of corrections-and-improvements logic
$databaseTablesAndColumnsArrayOfObjects = array();
$dtacaoo = $databaseTablesAndColumnsArrayOfObjects;

$dtacaoo[$tablename_email] = $wpdb->get_results( "SHOW COLUMNS FROM $tablename_email" );
$dtacaoo[$tablename_email_admin] = $wpdb->get_results( "SHOW COLUMNS FROM $tablename_email_admin" );
$dtacaoo[$tablename_options_input] = $wpdb->get_results( "SHOW COLUMNS FROM $tablename_options_input" );
$dtacaoo[$tablename_comments] = $wpdb->get_results( "SHOW COLUMNS FROM $tablename_comments" );
$dtacaoo[$tablename_entries] = $wpdb->get_results( "SHOW COLUMNS FROM $tablename_entries" );
$dtacaoo[$tablename] = $wpdb->get_results( "SHOW COLUMNS FROM $tablename" );
$dtacaoo[$tablename_ip] = $wpdb->get_results( "SHOW COLUMNS FROM $tablename_ip" );
$dtacaoo[$tablename_options] = $wpdb->get_results( "SHOW COLUMNS FROM $tablename_options" );
$dtacaoo[$tablename_options_visual] = $wpdb->get_results( "SHOW COLUMNS FROM $tablename_options_visual" );
$dtacaoo[$tablename_form_input] = $wpdb->get_results( "SHOW COLUMNS FROM $tablename_form_input" );
$dtacaoo[$tablename_form_output] = $wpdb->get_results( "SHOW COLUMNS FROM $tablename_form_output" );
$dtacaoo[$tablename_pro_options] = $wpdb->get_results( "SHOW COLUMNS FROM $tablename_pro_options" );
$dtacaoo[$tablename_create_user_entries] = $wpdb->get_results( "SHOW COLUMNS FROM $tablename_create_user_entries" );
$dtacaoo[$tablename_create_user_form] = $wpdb->get_results( "SHOW COLUMNS FROM $tablename_create_user_form" );
$dtacaoo[$tablename_mail_confirmation] = $wpdb->get_results( "SHOW COLUMNS FROM $tablename_mail_confirmation" );
$dtacaoo[$tablename_registry_and_login_options] = $wpdb->get_results( "SHOW COLUMNS FROM $tablename_registry_and_login_options" );
$dtacaoo[$tablename_google_options] = $wpdb->get_results( "SHOW COLUMNS FROM $tablename_google_options" );
$dtacaoo[$tablename_google_users] = $wpdb->get_results( "SHOW COLUMNS FROM $tablename_google_users" );


foreach($updateArray as $tableName => $tableData){

    foreach($tableData as $columnName => $columnData){

        $isColumnAvailable = false;
        $availableTableObjectsData = false;

        foreach($dtacaoo as $tableNameToCompare => $tableObjectsData){

            if($tableName==$tableNameToCompare){

                foreach($tableObjectsData as $tableObject){

                    if($columnName==$tableObject->Field){
                        $isColumnAvailable = true;
                        $availableTableObjectsData = $tableObject;
                    }

                }

            }

        }

        // if not avialable then alter (create) column!
        if(!$isColumnAvailable){

            if(!empty($isJustCheck)){
                if(empty($columnsToRepairArray[$tableName])){$columnsToRepairArray[$tableName] = array();}
                if(empty($columnsToRepairArray['hasColumnsToImprove'])){$columnsToRepairArray['hasColumnsToImprove'] = true;}
                $columnsToRepairArray[$tableName][] = array('ColumnName' => $columnName, 'IsNoColumn' => true);
            }else{
                $columnType = trim(strtolower($columnData['COLUMN_TYPE']));

                $DEFAULT = "DEFAULT ".$columnData['DEFAULT'];
                $query = "ALTER TABLE $tableName ADD COLUMN $columnName $columnType $DEFAULT";

                if(!$wpdb->query($query)){
                    $wpdb->show_errors();
                    ob_start();
                    $wpdb->print_error();
                    $errorsArray[$columnName] = ob_get_clean();
                }

            }

        }else{
            $columnType = trim(strtolower($columnData['COLUMN_TYPE']));
            $columnTypeToCompare = trim(strtolower($availableTableObjectsData->Type));

            $isBothTinyInt = false;

            if(strpos($columnType,'tinyint') !== false && strpos($columnTypeToCompare,'tinyint') !== false){
                $isBothTinyInt = true;
            }


            // if both tinyint then no changes needed
            if($columnType!=$columnTypeToCompare && !$isBothTinyInt){
                if(!empty($isJustCheck)){
                    if($columnTypeToCompare=='int' && strpos($columnType,'int')!==false){// for new mysql 8 always simply int will be created not int(11) for example

                    }else{
                        if(empty($columnsToRepairArray[$tableName])){$columnsToRepairArray[$tableName] = array();}
                        if(empty($columnsToRepairArray['hasColumnsToImprove'])){$columnsToRepairArray['hasColumnsToImprove'] = true;}
                        $columnsToRepairArray[$tableName][] = array('ColumnName' => $columnName, 'IsColumnCouldNotBeModified' => true, 'ColumnTypeCurrent' => $columnTypeToCompare, 'ColumnTypeRequired' => $columnType);
                    }
                }else{
                    // check if type is same
                    // if not then modify
                    if($columnType != trim(strtolower($availableTableObjectsData->Type))){
                        $DEFAULT = "DEFAULT ".$columnData['DEFAULT'];
                        $query = "ALTER TABLE $tableName MODIFY COLUMN $columnName $columnType $DEFAULT";
                        if(!$wpdb->query($query)){
                            $wpdb->show_errors();
                            ob_start();
                            $wpdb->print_error();
                            $errorsArray[$columnName] = ob_get_clean();
                        }
                    }
                }

            }

        }

    }

}



?>