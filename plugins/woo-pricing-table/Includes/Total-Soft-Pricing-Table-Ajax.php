<?php
//Admin Menu
add_action('wp_ajax_Total_Soft_PTable_Del', 'Total_Soft_PTable_Del_Callback');
add_action('wp_ajax_nopriv_Total_Soft_PTable_Del', 'Total_Soft_PTable_Del_Callback');
function Total_Soft_PTable_Del_Callback()
{
    $PTable_ID = sanitize_text_field($_POST['foobar']);
    global $wpdb;
     $table_name2 = $wpdb->prefix . "totalsoft_ptable_id";
    $table_name3 = $wpdb->prefix . "totalsoft_ptable_manager";
    $table_name4 = $wpdb->prefix . "totalsoft_ptable_cols";
    $table_name5 = $wpdb->prefix . "totalsoft_ptable_sets";
    $New_PTable = $wpdb->get_results($wpdb->prepare("SELECT Defoult FROM $table_name3"));
    $value = json_decode(json_encode($New_PTable), TRUE);
    $values =json_decode($value[0]['Defoult'], TRUE);
    $result_count = count($values);
    $result = '';

  for ($i = 0; $i < $result_count; $i++) {
         if ($values[$i]['id'] == $PTable_ID) {
             unset($values[$i]);
        }
    }

   $result = (json_encode(array_values($values)));
   $wpdb->query($wpdb->prepare("UPDATE $table_name3 set Defoult = %s WHERE id = 1", $result));
        $wpdb->query($wpdb->prepare("DELETE FROM $table_name2 WHERE PTable_ID = %s", $PTable_ID));
    $PTable = $wpdb->get_results($wpdb->prepare("SELECT PTable FROM $table_name4"));
    $valuel = json_decode(json_encode($PTable), TRUE);
    $values = json_decode($valuel[0]['PTable'], TRUE);
    $results_count = count($values);
    $results = '';

    for ($i = 0; $i < $results_count; $i++) {
         if ($values[$i]['PTable_ID'] == $PTable_ID) {
            unset($values[$i]);
        }
    }

     $results = (json_encode(array_values($values)));
    $wpdb->query($wpdb->prepare("UPDATE $table_name4 set PTable = %s WHERE id = 1", $results));

    $PTableSet = $wpdb->get_results($wpdb->prepare("SELECT Price FROM $table_name5"));
    $valueSet = json_decode(json_encode($PTableSet), TRUE);
    $valuess = json_decode($valueSet[0]['Price'], TRUE);
    $resultSet_count = count($valuess);
    $resultSet = '';
    
     for ($i = 0; $i < $resultSet_count; $i++) {
        if ($valuess[$i]['PTable_ID'] == $PTable_ID) {
                unset($valuess[$i]);
        }
    }


       $resultSet = (json_encode(array_values($valuess)));

      $wpdb->query($wpdb->prepare("UPDATE $table_name5 set Price = %s WHERE id = 1", $resultSet));
    die();
}

add_action('wp_ajax_Total_Soft_PTable_Sort_Index', 'Total_Soft_PTable_Sort_Index_Callback');
add_action('wp_ajax_nopriv_Total_Soft_PTable_Sort_Index', 'Total_Soft_PTable_Sort_Index_Callback');
function Total_Soft_PTable_Sort_Index_Callback()
{
    global $wpdb;
    $table_name4 = $wpdb->prefix . "totalsoft_ptable_cols";

    $PTable = $wpdb->get_results($wpdb->prepare("SELECT PTable FROM $table_name4"));
    $valuel = json_decode(json_encode($PTable), TRUE);
    $valueArray = json_decode($valuel[0]['PTable'], TRUE);
    $valueArray_max =end($valueArray)['id'];
     usort($valueArray,
    function ($a, $b) {
            if ($a['index']==$b['index']) return 0;
            return ($a['index']<$b['index'])?-1:1;
        }
     );
     if (end($valueArray)['index']> $valueArray_max) $valueArray_max=end($valueArray)['index'];
 print_r((json_encode($valueArray_max)) );
    die();
}


add_action('wp_ajax_Total_Soft_PTable_Clone', 'Total_Soft_PTable_Clone_Callback');
add_action('wp_ajax_nopriv_Total_Soft_PTable_Clone', 'Total_Soft_PTable_Clone_Callback');
function Total_Soft_PTable_Clone_Callback()
{
    $PTable_ID = sanitize_text_field($_POST['foobar']);
    global $wpdb;
    $table_name2 = $wpdb->prefix . "totalsoft_ptable_id";
    $table_name3 = $wpdb->prefix . "totalsoft_ptable_manager";
    $table_name4 = $wpdb->prefix . "totalsoft_ptable_cols";
    $table_name5 = $wpdb->prefix . "totalsoft_ptable_sets";
    $TS_PTable_Man = $wpdb->get_results($wpdb->prepare("SELECT Defoult FROM $table_name3"));
    $value = json_decode(json_encode($TS_PTable_Man), TRUE);
    $valueArray = json_decode($value[0]['Defoult'], TRUE);
    $valueSortSet=[];
    $valueSortCol=[];

    $resultDecoded = [];

    for ($i = 0; $i < count($valueArray); $i++) {
        $id = $valueArray[count($valueArray) - 1]['id'] + 1;
        if ($valueArray[$i]['id'] == $PTable_ID) {
            $num = explode(' ', $valueArray[count($valueArray) - 1]['Total_Soft_PTable_Title']);
            $num = end($num) + 1;
            array_push($valueArray, ['id' => '' . $id . '', 'Total_Soft_PTable_Title' => 'Table ' . $num, 'Total_Soft_PTable_Them' => $valueArray[$i]['Total_Soft_PTable_Them'], 'Total_Soft_PTable_Cols_Count' => $valueArray[$i]['Total_Soft_PTable_Cols_Count'], 'Total_Soft_PTable_M_01' => $valueArray[$i]['Total_Soft_PTable_M_01'], 'Total_Soft_PTable_M_02' => $valueArray[$i]['Total_Soft_PTable_M_02'], 'Total_Soft_PTable_M_03' => $valueArray[$i]['Total_Soft_PTable_M_03']]);
        }

    }
    $result_data=end($valueArray);

    $result = (json_encode($valueArray));
     $wpdb->query($wpdb->prepare("UPDATE $table_name3 set Defoult = %s WHERE id = 1", $result));
     $wpdb->query($wpdb->prepare("INSERT INTO $table_name2 (id, PTable_ID) VALUES (%d, %d)", '', $id - 1));

    $TS_PTable_Col = $wpdb->get_results($wpdb->prepare("SELECT PTable FROM $table_name4"));
    $values = json_decode(json_encode($TS_PTable_Col), TRUE);
    $valueArrayCol = json_decode($values[0]['PTable'], TRUE);

    $id = $id - 1;
    for ($i = 0; $i < count($valueArrayCol); $i++) {
        $keys = array_keys($valueArrayCol);
        $lastId = $valueArrayCol[$keys[count($keys) - 1]]['id'] + 1;
        $lastIndex = $valueArrayCol[$keys[count($keys) - 1]]['index'] + 1;
        if ($valueArrayCol[$i]['PTable_ID'] == $PTable_ID) {


            array_push($valueSortCol, ['id' => '' . $lastId . '', 'index' => '' . $valueArrayCol[$i]['index'] . '', 'TS_PTable_TType' => $valueArrayCol[$i]['TS_PTable_TType'], 'PTable_ID' => '' . $id . '', 'TS_PTable_TSetting' => $valueArrayCol[$i]['TS_PTable_TSetting'], 'TS_PTable_TText' => $valueArrayCol[$i]['TS_PTable_TText'], 'TS_PTable_TIcon' => $valueArrayCol[$i]['TS_PTable_TIcon'], 'TS_PTable_PCur' => $valueArrayCol[$i]['TS_PTable_PCur'], 'TS_PTable_PVal' => $valueArrayCol[$i]['TS_PTable_PVal'], 'TS_PTable_PPlan' => $valueArrayCol[$i]['TS_PTable_PPlan'], 'TS_PTable_FCount' => $valueArrayCol[$i]['TS_PTable_FCount'], 'TS_PTable_BText' => $valueArrayCol[$i]['TS_PTable_BText'], 'TS_PTable_BIcon' => $valueArrayCol[$i]['TS_PTable_BIcon'], 'TS_PTable_BLink' => $valueArrayCol[$i]['TS_PTable_BLink'], 'TS_PTable_FIcon' => $valueArrayCol[$i]['TS_PTable_FIcon'], 'TS_PTable_FText' => $valueArrayCol[$i]['TS_PTable_FText'], 'TS_PTable_C_01' => $valueArrayCol[$i]['TS_PTable_C_01'],]);
        }

    }
    usort($valueSortCol,
    function ($a, $b) {
           if ($a['index']==$b['index']) return 0;
           return ($a['index']<$b['index'])?-1:1;
        }
     );

    for ($i = 0; $i < count($valueSortCol); $i++) {
         $valueSortCol[$i]['id'] = $valueSortCol[$i]['id']+$i;
        $valueSortCol[$i]['index'] = $valueSortCol[$i]['id']-1;
         array_push($valueArrayCol,$valueSortCol[$i]);
    }

    $result = (json_encode($valueArrayCol));
     $wpdb->query($wpdb->prepare("UPDATE $table_name4 set PTable = %s WHERE id = 1", $result));


    $TS_PTable_Set = $wpdb->get_results($wpdb->prepare("SELECT Price FROM $table_name5"));
    $val = json_decode(json_encode($TS_PTable_Set), TRUE);
    $valueArraySet = json_decode($val[0]['Price'], TRUE);
    for ($i = 0; $i < count($valueArraySet); $i++) {
        $key = array_keys($valueArraySet);
        $lastId = $valueArrayCol[$keys[count($key) - 1]]['id'] + 1;
        $lastIndex = $valueArrayCol[$keys[count($key) - 1]]['index'] + 1;
        if ($valueArraySet[$i]['PTable_ID'] == $PTable_ID) {


            array_push($valueSortSet, ['id' => '' . $lastId . '', 'index' => '' .$valueArraySet[$i]['index'] . '', 'TS_PTable_TType' => $valueArraySet[$i]['TS_PTable_TType'], 'PTable_ID' => '' . $id . '', 'TS_PTable_ST_00' => 'Theme_' . $lastId, 'TS_PTable_ST_01' => $valueArraySet[$i]['TS_PTable_ST_01'], 'TS_PTable_ST_02' => $valueArraySet[$i]['TS_PTable_ST_02'], 'TS_PTable_ST_03' => $valueArraySet[$i]['TS_PTable_ST_03'], 'TS_PTable_ST_04' => $valueArraySet[$i]['TS_PTable_ST_04'], 'TS_PTable_ST_05' => $valueArraySet[$i]['TS_PTable_ST_05'], 'TS_PTable_ST_06' => $valueArraySet[$i]['TS_PTable_ST_06'], 'TS_PTable_ST_07' => $valueArraySet[$i]['TS_PTable_ST_07'], 'TS_PTable_ST_08' => $valueArraySet[$i]['TS_PTable_ST_08'], 'TS_PTable_ST_09' => $valueArraySet[$i]['TS_PTable_ST_09'], 'TS_PTable_ST_10' => $valueArraySet[$i]['TS_PTable_ST_10'], 'TS_PTable_ST_11' => $valueArraySet[$i]['TS_PTable_ST_11'], 'TS_PTable_ST_12' => $valueArraySet[$i]['TS_PTable_ST_12'], 'TS_PTable_ST_13' => $valueArraySet[$i]['TS_PTable_ST_13'], 'TS_PTable_ST_14' => $valueArraySet[$i]['TS_PTable_ST_14'], 'TS_PTable_ST_15' => $valueArraySet[$i]['TS_PTable_ST_15'], 'TS_PTable_ST_16' => $valueArraySet[$i]['TS_PTable_ST_16'], 'TS_PTable_ST_17' => $valueArraySet[$i]['TS_PTable_ST_17'], 'TS_PTable_ST_18' => $valueArraySet[$i]['TS_PTable_ST_18'], 'TS_PTable_ST_19' => $valueArraySet[$i]['TS_PTable_ST_19'], 'TS_PTable_ST_20' => $valueArraySet[$i]['TS_PTable_ST_20'], 'TS_PTable_ST_21' => $valueArraySet[$i]['TS_PTable_ST_21'], 'TS_PTable_ST_21_1' => $valueArraySet[$i]['TS_PTable_ST_21_1'], 'TS_PTable_ST_22' => $valueArraySet[$i]['TS_PTable_ST_22'], 'TS_PTable_ST_23' => $valueArraySet[$i]['TS_PTable_ST_23'], 'TS_PTable_ST_24' => $valueArraySet[$i]['TS_PTable_ST_24'], 'TS_PTable_ST_25' => $valueArraySet[$i]['TS_PTable_ST_25'], 'TS_PTable_ST_26' => $valueArraySet[$i]['TS_PTable_ST_26'], 'TS_PTable_ST_27' => $valueArraySet[$i]['TS_PTable_ST_27'], 'TS_PTable_ST_28' => $valueArraySet[$i]['TS_PTable_ST_28'], 'TS_PTable_ST_29' => $valueArraySet[$i]['TS_PTable_ST_29'], 'TS_PTable_ST_30' => $valueArraySet[$i]['TS_PTable_ST_30'], 'TS_PTable_ST_31' => $valueArraySet[$i]['TS_PTable_ST_31'], 'TS_PTable_ST_32' => $valueArraySet[$i]['TS_PTable_ST_32'], 'TS_PTable_ST_33' => $valueArraySet[$i]['TS_PTable_ST_33'], 'TS_PTable_ST_34' => $valueArraySet[$i]['TS_PTable_ST_34'], 'TS_PTable_ST_35' => $valueArraySet[$i]['TS_PTable_ST_35'], 'TS_PTable_ST_36' => $valueArraySet[$i]['TS_PTable_ST_36'], 'TS_PTable_ST_37' => $valueArraySet[$i]['TS_PTable_ST_37'], 'TS_PTable_ST_38' => $valueArraySet[$i]['TS_PTable_ST_38'], 'TS_PTable_ST_39' => $valueArraySet[$i]['TS_PTable_ST_39'], 'TS_PTable_ST_40' => $valueArraySet[$i]['TS_PTable_ST_40']]);
        }

    }
   usort($valueSortSet,
    function ($a, $b) {
            if ($a['index']==$b['index']) return 0;
            return ($a['index']<$b['index'])?-1:1;
        }
     );
    for ($i = 0; $i < count($valueSortSet); $i++) {
        $valueSortSet[$i]['id'] = $valueSortSet[$i]['id']+$i;
        $valueSortSet[$i]['index'] = $valueSortSet[$i]['id']-1;
         array_push($valueArraySet,$valueSortSet[$i]);
    }

    $result = (json_encode($valueArraySet));
    $wpdb->query($wpdb->prepare("UPDATE $table_name5 set Price = %s WHERE id = 1", $result));
    print_r( (json_encode($result_data)));
    die();
}


add_action('wp_ajax_Total_Soft_PTable_Sort', 'Total_Soft_PTable_Sort_Callback');
add_action('wp_ajax_nopriv_Total_Soft_PTable_Sort', 'Total_Soft_PTable_Sort_Callback');
function Total_Soft_PTable_Sort_Callback()
{
    $newIndexes = json_decode(stripslashes(sanitize_text_field($_POST['foobar'])));
    $PTable_Type = sanitize_text_field($_POST['foobars']);
    global $wpdb;
    $table_name4 = $wpdb->prefix . "totalsoft_ptable_cols";
    $table_name5 = $wpdb->prefix . "totalsoft_ptable_sets";
    $TS_PTable_Col = $wpdb->get_results($wpdb->prepare("SELECT PTable FROM $table_name4"));
    $TS_PTable_Set = $wpdb->get_results($wpdb->prepare("SELECT Price FROM $table_name5"));
    $default_Col = json_decode($TS_PTable_Col[0]->PTable);
    $default_Set = json_decode($TS_PTable_Set[0]->Price);
    foreach ($newIndexes as $index => $id) {
        for ($i = 0; $i < count($default_Col); $i++) {
            if ($id == $default_Col[$i]->id && $default_Col[$i]->TS_PTable_TType == $PTable_Type) {
                $default_Col[$i]->index = $index;
                $default_Set[$i]->index = $index;
            }
        }
    }
    $result_Col = (json_encode(array_values($default_Col)));
    $results_Set = (json_encode(array_values($default_Set)));
    $wpdb->query($wpdb->prepare("UPDATE $table_name4 set PTable = %s WHERE id = 1", $result_Col));
    $wpdb->query($wpdb->prepare("UPDATE $table_name5 set Price = %s WHERE id = 1", $results_Set));
    die();
}

add_action('wp_ajax_Total_Soft_PTable_Edit', 'Total_Soft_PTable_Edit_Callback');
add_action('wp_ajax_nopriv_Total_Soft_PTable_Edit', 'Total_Soft_PTable_Edit_Callback');
function Total_Soft_PTable_Edit_Callback()
{
    global $wpdb;
    $table_name3 = $wpdb->prefix . "totalsoft_ptable_manager";
    $TS_PTable_Man = $wpdb->get_results($wpdb->prepare("SELECT Defoult FROM $table_name3 "));
    $TS_PTable_Man_Res = json_decode($TS_PTable_Man[0]->Defoult, true);
    print json_encode($TS_PTable_Man_Res);
    die();
}

add_action('wp_ajax_Total_Soft_PTable_Edit1', 'Total_Soft_PTable_Edit1_Callback');
add_action('wp_ajax_nopriv_Total_Soft_PTable_Edit1', 'Total_Soft_PTable_Edit1_Callback');
function Total_Soft_PTable_Edit1_Callback()
{
    $PTable_ID = sanitize_text_field($_POST['foobarUpdate_Id']);
    $PTable_Type = sanitize_text_field($_POST['foobarType']);
    global $wpdb;
    $table_name4 = $wpdb->prefix . "totalsoft_ptable_cols";
    $table_name5 = $wpdb->prefix . "totalsoft_ptable_sets";
    $table_name3 = $wpdb->prefix . "totalsoft_ptable_manager";
    $TS_PTable_Man = $wpdb->get_results($wpdb->prepare("SELECT Defoult FROM $table_name3 "));
    $TS_PTable_Col = $wpdb->get_results($wpdb->prepare("SELECT PTable FROM $table_name4 "));
    $TS_PTable_Set = $wpdb->get_results($wpdb->prepare("SELECT Price FROM $table_name5 "));

    $TS_PTable_Man_Res = json_decode($TS_PTable_Man[0]->Defoult, true);
    $defoults = json_decode($TS_PTable_Col[0]->PTable, true);
    $default_Set = json_decode($TS_PTable_Set[0]->Price, true);
    $a = [];
    $b = [];
    $c = [];
    $x = [];

    for ($i = 0; $i < count($TS_PTable_Man_Res); $i++) {
        if ($TS_PTable_Man_Res[$i]['id'] == $PTable_ID) {
            array_push($c, $TS_PTable_Man_Res[$i]);
        }
    }

    for ($i = 0; $i < count($defoults); $i++) {
        $defoults[$i]['TS_PTable_TText'] = html_entity_decode($defoults[$i]['TS_PTable_TText']);
        $defoults[$i]['TS_PTable_BText'] = html_entity_decode($defoults[$i]['TS_PTable_BText']);
        $defoults[$i]['TS_PTable_FText'] = html_entity_decode($defoults[$i]['TS_PTable_FText']);
        if ($defoults[$i]['PTable_ID'] == $PTable_ID && $defoults[$i]['TS_PTable_TType'] == $PTable_Type) {
            array_push($b, $defoults[$i]);
        }
    }

    array_push($x, $b);

    for ($i = 0; $i < count($default_Set); $i++) {
        if ($default_Set[$i]['PTable_ID'] == $PTable_ID && $default_Set[$i]['TS_PTable_TType'] == $PTable_Type) {
            array_push($a, $default_Set[$i]);
        }
    }


    array_push($x, $a);
    array_push($x, $c);
    print_r(json_encode($x));
    //  print_r(json_encode($defoults));
    die();
}

add_action('wp_ajax_Total_Soft_PTable_Edit2', 'Total_Soft_PTable_Edit2_Callback');
add_action('wp_ajax_nopriv_Total_Soft_PTable_Edit2', 'Total_Soft_PTable_Edit2_Callback');
function Total_Soft_PTable_Edit2_Callback()
{
    $PTable_ID = sanitize_text_field($_POST['foobar']);
    global $wpdb;
    $table_name04 = $wpdb->prefix . "totalsoft_ptable_cols_def";
    $TS_PTable_Col = $wpdb->get_results($wpdb->prepare("SELECT PTable FROM $table_name04 "));
    $deff = json_decode($TS_PTable_Col[0]->PTable, true);
    $b = [];
    foreach ($deff as $values) {
        $defoults = (json_decode($values, TRUE));
        for ($i = 0; $i < count($defoults); $i++) {
            $defoults[$i]['TS_PTable_TText'] = html_entity_decode($defoults[$i]['TS_PTable_TText']);
            $defoults[$i]['TS_PTable_BText'] = html_entity_decode($defoults[$i]['TS_PTable_BText']);
            $defoults[$i]['TS_PTable_FText'] = html_entity_decode($defoults[$i]['TS_PTable_FText']);
            if ($defoults[$i]['id'] == $PTable_ID) {
                array_push($b, $defoults[$i]);
            }
        }
    }
    print json_encode($b);
    die();
}

add_action('wp_ajax_Total_Soft_PTable_Edit_Theme', 'Total_Soft_PTable_Edit_Theme_Callback');
add_action('wp_ajax_nopriv_Total_Soft_PTable_Edit_Theme', 'Total_Soft_PTable_Edit_Theme_Callback');
function Total_Soft_PTable_Edit_Theme_Callback()
{
    global $wpdb;
    $table_name5 = $wpdb->prefix . "totalsoft_ptable_sets";
    $TS_PTable_Set = $wpdb->get_results($wpdb->prepare("SELECT Price FROM $table_name5"));
    $valueArraySet = json_decode($TS_PTable_Set[0]->Price, true);
    print json_encode($valueArraySet);
    die();
}

add_action('wp_ajax_Total_Soft_PTable_Edit_New_Theme', 'Total_Soft_PTable_Edit_New_Theme_Callback');
add_action('wp_ajax_nopriv_Total_Soft_PTable_Edit_New_Theme', 'Total_Soft_PTable_Edit_New_Theme_Callback');
function Total_Soft_PTable_Edit_New_Theme_Callback()
{
    global $wpdb;
    $table_name7 = $wpdb->prefix . "totalsoft_ptable_sets_def";
    $TS_PTable_Set = $wpdb->get_results($wpdb->prepare("SELECT Price FROM $table_name7 "));
    $valueArraySet = json_decode($TS_PTable_Set[0]->Price, true);
    print json_encode($valueArraySet);
    die();
}

add_action('wp_ajax_Total_Soft_PTable_Select_Defoult_Theme', 'Total_Soft_PTable_Select_Defoult_Theme_Callback');
add_action('wp_ajax_nopriv_Total_Soft_PTable_Select_Defoult_Theme', 'Total_Soft_PTable_Select_Defoult_Theme_Callback');
function Total_Soft_PTable_Select_Defoult_Theme_Callback()
{
    global $wpdb;
    $PTable_Update_ID = sanitize_text_field($_POST['foobarUpdate_Id']);
    $PTable_Type = sanitize_text_field($_POST['foobarType']);
    $table_name04 = $wpdb->prefix . "totalsoft_ptable_cols_def";
    $table_name05 = $wpdb->prefix . "totalsoft_ptable_sets_def";

    $TS_PTable_Col = $wpdb->get_results($wpdb->prepare("SELECT PTable FROM $table_name04 "));
    $TS_PTable_Set = $wpdb->get_results($wpdb->prepare("SELECT Price FROM $table_name05 "));
    $deffCol = json_decode($TS_PTable_Col[0]->PTable, true);
    $deffSet = json_decode($TS_PTable_Set[0]->Price, true);
    $keys = array_keys($deffCol);
    $lastId = $deffCol[$keys[count($keys) - 1]]['id'] + 1;
    $lastIndex = $deffCol[$keys[count($keys) - 1]]['id'];
    $PTableVal_def_Array = [];
//    $PTableVal_def = '{"id": "' . $lastId . '", "index":"' . $lastIndex . '", "TS_PTable_TType":"' . $PTable_Type . '", "PTable_ID": "' . $PTable_Update_ID . '", "TS_PTable_TSetting": "' . $lastId . '", "TS_PTable_TText": "Personal", "TS_PTable_TIcon": "cart-arrow-down", "TS_PTable_PCur": "$", "TS_PTable_PVal": "0", "TS_PTable_PPlan": "/ month", "TS_PTable_FCount": "5", "TS_PTable_BText": "Buy", "TS_PTable_BIcon": "money", "TS_PTable_BLink": "#", "TS_PTable_FIcon": "angle-leftTSPTFIangle-leftTSPTFIangle-leftTSPTFIangle-leftTSPTFIangle-left", "TS_PTable_FText": "10 GB Disk SpaceTSPTFT5 Email AddressesTSPTFT3 SubdomainsTSPTFT1 MySQL DatabasesTSPTFT15 Domains", "TS_PTable_C_01": "1TSPTFCTSPTFC3TSPTFCTSPTFC5"}';
//    $Def_val = '{"id": "' . $lastId . '", "index":"' . $lastIndex . '", "PTable_ID": "' . $PTable_Update_ID . '", "TS_PTable_TType": "' . $PTable_Type . '", "TS_PTable_ST_00": "Theme_' . $lastId . '", "TS_PTable_ST_01": "33", "TS_PTable_ST_02": "", "TS_PTable_ST_03": "#ffffff", "TS_PTable_ST_04": "#cccccc", "TS_PTable_ST_05": "1", "TS_PTable_ST_06": "shadow02", "TS_PTable_ST_07": "#cccccc", "TS_PTable_ST_08": "21", "TS_PTable_ST_09": "Andalus", "TS_PTable_ST_10": "#dd0000", "TS_PTable_ST_11": "#000000", "TS_PTable_ST_12": "24", "TS_PTable_ST_13": "above", "TS_PTable_ST_14": "24", "TS_PTable_ST_15": "Calibri", "TS_PTable_ST_16": "#000001", "TS_PTable_ST_17": "14", "TS_PTable_ST_18": "#dd0000", "TS_PTable_ST_19": "#ffffff", "TS_PTable_ST_20": "#cccccc", "TS_PTable_ST_21": "#595959", "TS_PTable_ST_21_1": "#ffffff", "TS_PTable_ST_22": "16", "TS_PTable_ST_23": "Calibri", "TS_PTable_ST_24": "#cccccc", "TS_PTable_ST_25": "#ffffff", "TS_PTable_ST_26": "20", "TS_PTable_ST_27": "after", "TS_PTable_ST_28": "#dd0000", "TS_PTable_ST_29": "#ffffff", "TS_PTable_ST_30": "16", "TS_PTable_ST_31": "Calibri", "TS_PTable_ST_32": "16", "TS_PTable_ST_33": "#ffffff", "TS_PTable_ST_34": "after", "TS_PTable_ST_35": "", "TS_PTable_ST_36": "", "TS_PTable_ST_37": "", "TS_PTable_ST_38": "", "TS_PTable_ST_39": "", "TS_PTable_ST_40": ""}';
//    $encoded_PTableVal_def = json_decode($PTableVal_def);
//    $encoded_Def_val = json_decode($Def_val);

    array_push($PTableVal_def_Array, $deffCol);
    array_push($PTableVal_def_Array, $deffSet);

//    array_push($deffCol, $encoded_PTableVal_def);
//    array_push($deffSet, $encoded_Def_val);
//    $result_Col = (json_encode(array_values($deffCol)));
//    $result_Set = (json_encode(array_values($deffSet)));
//    $wpdb->query($wpdb->prepare("UPDATE $table_name4 set PTable = %s WHERE id = 1", $result_Col));
//    $wpdb->query($wpdb->prepare("UPDATE $table_name5 set Price = %s WHERE id = 1", $result_Set));
    print_r(json_encode($PTableVal_def_Array));
    die();

}

//add_action('wp_ajax_Total_Soft_PTable_Duplicate_Theam', 'Total_Soft_PTable_Duplicate_Theam_Callback');
//add_action('wp_ajax_nopriv_Total_Soft_PTable_Duplicate_Theam', 'Total_Soft_PTable_Duplicate_Theam_Callback');
//function Total_Soft_PTable_Duplicate_Theam_Callback()
//{
//  $PTable_ID = sanitize_text_field($_POST['foobar']);
//  global $wpdb;
//  $table_name5 = $wpdb->prefix . "totalsoft_ptable_sets";
//  $TS_PTable_Set = $wpdb->get_results($wpdb->prepare("SELECT Price FROM $table_name5"));
//  $default_Set = json_decode($TS_PTable_Set[0]->Price);
//  for ($i = 0; $i < count($default_Set); $i++) {
//    if ($default_Set[$i]->id == $PTable_ID) {
//      print json_encode($default_Set[$i]);
//    }
//  }
//  die();
//}
//add_action('wp_ajax_Total_Soft_PTable_Duplicate_Col', 'Total_Soft_PTable_Duplicate_Col_Callback');
//add_action('wp_ajax_nopriv_Total_Soft_PTable_Duplicate_Col_Duplicate', 'Total_Soft_PTable_Duplicate_Col_Callback');
//function Total_Soft_PTable_Duplicate_Col_Callback()
//{
//    $PTable_ID = sanitize_text_field($_POST['foobar']);
//    $PTable_Last_ID = sanitize_text_field($_POST['foobarLast']);
//    $PTable_Last_Index = sanitize_text_field($_POST['foobarLastIndex']);
//    global $wpdb;
//    $table_name4 = $wpdb->prefix . "totalsoft_ptable_cols";
//    $table_name5 = $wpdb->prefix . "totalsoft_ptable_sets";
//    $TS_PTable_Col = $wpdb->get_results($wpdb->prepare("SELECT PTable FROM $table_name4"));
//    $TS_PTable_Set = $wpdb->get_results($wpdb->prepare("SELECT Price FROM $table_name5"));
//    $default_Col = json_decode($TS_PTable_Col[0]->PTable);
//    $default_Set = json_decode($TS_PTable_Set[0]->Price);
//    $PTableVal_dup_Array = [];
//    $PTableVal_dup_Set = [];
//    $PTableVal_dup_All_Value = [];
//    for ($i = 0; $i < count($default_Col); $i++) {
//        if ($default_Col[$i]->id == $PTable_ID) {
//            array_push($PTableVal_dup_Array, $default_Col[$i]);
//        }
//    }
//    for ($i = 0; $i < count($default_Set); $i++) {
//        if ($default_Set[$i]->id == $PTable_ID) {
//            array_push($PTableVal_dup_Set, $default_Set[$i]);
//        }
//    }
//    array_push($default_Col, $PTableVal_dup_Array[0]);
//    array_push($default_Set, $PTableVal_dup_Set[0]);
//    $result_Col = (json_encode($default_Col));
//    $result_Set = (json_encode($default_Set));
//    $wpdb->query($wpdb->prepare("UPDATE $table_name4 set PTable = %s WHERE id = 1", $result_Col));
//    $wpdb->query($wpdb->prepare("UPDATE $table_name5 set Price = %s WHERE id = 1", $result_Set));
//
//
//    $TS_PTable_Col_New = $wpdb->get_results($wpdb->prepare("SELECT PTable FROM $table_name4 "));
//    $TS_PTable_Set_New = $wpdb->get_results($wpdb->prepare("SELECT Price FROM $table_name5"));
//    $default_Col_New = json_decode($TS_PTable_Col_New[0]->PTable);
//    $default_Set_New = json_decode($TS_PTable_Set_New[0]->Price);
//
//    for ($i = count($default_Col_New); $i >= 0; $i--) {
//        if ($default_Col_New[$i]->id == $PTable_ID) {
//            $default_Col_New[$i]->id = $PTable_Last_ID;
//            $default_Col_New[$i]->index = $PTable_Last_Index;
//            $default_Col_New[$i]->TS_PTable_TSetting = $PTable_Last_ID;
//            array_push($PTableVal_dup_All_Value, $default_Col_New[$i]);
//            break;
//        }
//    }
//    for ($i = count($default_Set_New); $i >= 0; $i--) {
//        if ($default_Set_New[$i]->id == $PTable_ID) {
//            $default_Set_New[$i]->id = $PTable_Last_ID;
//            $default_Set_New[$i]->index = $PTable_Last_Index;
//            $default_Set_New[$i]->TS_PTable_ST_00 = "Theme_" . $PTable_Last_ID;
//            array_push($PTableVal_dup_All_Value, $default_Set_New[$i]);
//            break;
//        }
//    }
//
//    $result_Col_Now = (json_encode($default_Col_New));
//    $result_Set_New = (json_encode($default_Set_New));
//    $wpdb->query($wpdb->prepare("UPDATE $table_name4 set PTable = %s WHERE id = 1", $result_Col_Now));
//    $wpdb->query($wpdb->prepare("UPDATE $table_name5 set Price = %s WHERE id = 1", $result_Set_New));
//
//
//    print_r(json_encode($PTableVal_dup_All_Value));
//    die();
//}

//add_action('wp_ajax_Total_Soft_PTable_Duplicate_Set', 'Total_Soft_PTable_Duplicate_Set_Callback');
//add_action('wp_ajax_nopriv_Total_Soft_PTable_Duplicate_Set', 'Total_Soft_PTable_Duplicate_Set_Callback');
//function Total_Soft_PTable_Duplicate_Set_Callback()
//{
//  $PTable_ID = sanitize_text_field($_POST['foobar']);
//  $PTable_Last_ID = sanitize_text_field($_POST['foobarLast']);
//  $PTable_Last_Index = sanitize_text_field($_POST['foobarLastIndex']);
//  global $wpdb;
//  $table_name5 = $wpdb->prefix . "totalsoft_ptable_sets";
//  $TS_PTable_Set = $wpdb->get_results($wpdb->prepare("SELECT Price FROM $table_name5"));
//  $PTableVal_dup_Array = [];
//  $default_Set = json_decode($TS_PTable_Set[0]->Price);
//    for ($i = 0; $i < count($default_Set); $i++) {
//        if ($default_Set[$i]->id == $PTable_ID) {
//            array_push($default_Set,$default_Set[$i]);
////            $default_Set[$i]->id = $PTable_Last_ID;
////            $default_Set[$i]->index = $PTable_Last_Index;
////            $default_Set[$i]->TS_PTable_ST_00 = "Theme_".$PTable_Last_ID;
//
//
//
//            array_push($PTableVal_dup_Array,$default_Set[$i]);
//        }
//    }
//
//    $result_Set = (json_encode($default_Set));
//
//    $wpdb->query($wpdb->prepare("UPDATE $table_name5 set Price = %s WHERE id = 1", $result_Set));
//  print_r(json_encode($PTableVal_dup_Array));
//  die();
//}

// add_action('wp_ajax_Total_Soft_PTable_Cancle_Col', 'Total_Soft_PTable_Cancle_Col_Callback');
// add_action('wp_ajax_nopriv_Total_Soft_PTable_Cancle_Col', 'Total_Soft_PTable_Cancle_Col_Callback');
// function Total_Soft_PTable_Cancle_Col_Callback()
// {
//     $PTable_ID = sanitize_text_field($_POST['foobar']);
//     $PTable_Type = sanitize_text_field($_POST['foobarType']);

//     global $wpdb;
//     $table_name5 = $wpdb->prefix . "totalsoft_ptable_sets";
//     $table_name4 = $wpdb->prefix . "totalsoft_ptable_cols";
//     $TS_PTable_Col = $wpdb->get_results($wpdb->prepare("SELECT PTable FROM $table_name4"));
//     $TS_PTable_Set = $wpdb->get_results($wpdb->prepare("SELECT Price FROM $table_name5"));
//     $default_Col = json_decode($TS_PTable_Col[0]->PTable);
//     $default_Set = json_decode($TS_PTable_Set[0]->Price);
//     $default_Set_Id = json_decode($PTable_ID);
//     for ($i = count($default_Col); $i >= 0; $i--) {
//         for ($j = 0; $j < count($default_Set_Id); $j++) {
//             if ($default_Col[$i]->id == $default_Set_Id[$j] && $default_Col[$i]->TS_PTable_TType == $PTable_Type) {
//                 unset($default_Col[$i]);
//                 unset($default_Set[$i]);
//                 break;
//             }
//         }
//     }
//     $results = (json_encode(array_values($default_Col)));
//     $resultsSet = (json_encode(array_values($default_Set)));
//     $wpdb->query($wpdb->prepare("UPDATE $table_name4 set PTable = %s WHERE id = 1", $results));
//     $wpdb->query($wpdb->prepare("UPDATE $table_name5 set Price = %s WHERE id = 1", $resultsSet));
//     die();
// }

add_action('wp_ajax_Total_Soft_PTable_Select_New_Cols', 'Total_Soft_PTable_Select_New_Cols_Callback');
add_action('wp_ajax_nopriv_Total_Soft_PTable_Select_New_Cols', 'Total_Soft_PTable_Select_New_Cols_Callback');
function Total_Soft_PTable_Select_New_Cols_Callback()
{
    $PTable_ID = sanitize_text_field($_POST['foobarUpdate_Id']);
    $PTable_Type = sanitize_text_field($_POST['foobarType']);
    $PTable_Last_ID = sanitize_text_field($_POST['foobarLast']);
    $PTable_Last_Index = sanitize_text_field($_POST['foobarLastIndex']);
    global $wpdb;
    $table_name03 = $wpdb->prefix . "totalsoft_ptable_manager_new";
    $TS_PTable_Man = $wpdb->get_results($wpdb->prepare("SELECT Defoult_New FROM $table_name03 "));
    $table_name04 = $wpdb->prefix . "totalsoft_ptable_cols_def";
    $table_name05 = $wpdb->prefix . "totalsoft_ptable_sets_def";
    $table_name4 = $wpdb->prefix . "totalsoft_ptable_cols";

    $TS_PTable_Col = $wpdb->get_results($wpdb->prepare("SELECT PTable FROM $table_name04 "));
    $TS_PTable_Set = $wpdb->get_results($wpdb->prepare("SELECT Price FROM $table_name05 "));
    $PTable = $wpdb->get_results($wpdb->prepare("SELECT PTable FROM $table_name4"));

    $TS_PTable_Man_Res = json_decode($TS_PTable_Man[0]->Defoult_New, true);
    $default_Col = json_decode($TS_PTable_Col[0]->PTable, true);
    $default_Set = json_decode($TS_PTable_Set[0]->Price, true);

    $valuel = json_decode(json_encode($PTable), TRUE);
    $valueArray = json_decode($valuel[0]['PTable'], TRUE);
    $valueArray_max =end($valueArray)['id'];

    $TS_PTable_Array = [];
    $x = [];
    $c = [];
    array_push($c, $TS_PTable_Man_Res[0]);

    for ($i = 0; $i < count($default_Col); $i++) {
        if ($default_Col[$i]['TS_PTable_TType'] == $PTable_Type) {
            array_push($TS_PTable_Array, $default_Col[$i]);
        }
    }
    array_push($x, $TS_PTable_Array);

    $TS_PTable_Arrays = [];
    for ($i = 0; $i < count($default_Set); $i++) {
        if ($default_Set[$i]['TS_PTable_TType'] == $PTable_Type) {
            array_push($TS_PTable_Arrays, $default_Set[$i]);
        }
    }
    array_push($x, $TS_PTable_Arrays);

    array_push($x, $c);
    
    
     usort($valueArray,
    function ($a, $b) {
            if ($a['index']==$b['index']) return 0;
            return ($a['index']<$b['index'])?-1:1;
        }
     );
     
    if (end($valueArray)['index']> $valueArray_max) $valueArray_max=end($valueArray)['index'];

    array_push($x, $valueArray_max);
    
    print_r(json_encode($x));
    die();
}

add_action('wp_ajax_TotalSoftPTable_Edit', 'TotalSoftPTable_Edit_Callback');
add_action('wp_ajax_nopriv_TotalSoftPTable_Edit', 'TotalSoftPTable_Edit_Cols_Callback');
function TotalSoftPTable_Edit_Callback()
{
    $PTable_ID = sanitize_text_field($_POST['foobarUpdate_Id']);
    $PTable_Type = sanitize_text_field($_POST['foobarType']);
    $PTable_Last_ID = sanitize_text_field($_POST['foobarLast']);
    $PTable_Last_Index = sanitize_text_field($_POST['foobarLastIndex']);
    global $wpdb;
    $table_name6 = $wpdb->prefix . "totalsoft_ptable_cols_new";
    $table_name7 = $wpdb->prefix . "totalsoft_ptable_sets_new";
    $TS_PTable_Col_New = $wpdb->get_results($wpdb->prepare("SELECT PTable_New FROM $table_name6"));
    $TS_PTable_Set_New = $wpdb->get_results($wpdb->prepare("SELECT Price_New FROM $table_name7"));


    $default_Col = json_decode($TS_PTable_Col_New[0]->PTable_New);
    $default_Set = json_decode($TS_PTable_Set_New[0]->Price_New);
    $TS_PTable_Array = [];
    for ($i = 0; $i < count($default_Col); $i++) {
        if ($default_Col[$i]->TS_PTable_TType == $PTable_Type) {
            array_push($TS_PTable_Array, $default_Col[$i]);
        }
    }
    for ($i = 0; $i < count($default_Set); $i++) {
        if ($default_Set[$i]->TS_PTable_TType == $PTable_Type) {
            array_push($TS_PTable_Array, $default_Set[$i]);
        }
    }
    print_r(json_encode($TS_PTable_Array));
    die();
}


add_action('wp_ajax_Total_Soft_PTable_Select_Manager', 'Total_Soft_PTable_Select_Manager_Callback');
add_action('wp_ajax_nopriv_Total_Soft_PTable_Select_Manager', 'Total_Soft_PTable_Select_Manager_Callback');
function Total_Soft_PTable_Select_Manager_Callback()
{
    $PTable_Type = sanitize_text_field($_POST['foobarType']);

    global $wpdb;
    $table_name3 = $wpdb->prefix . "totalsoft_ptable_manager";
    $TS_PTable_Man = $wpdb->get_results($wpdb->prepare("SELECT Defoult FROM $table_name3 "));
    $default_Col_Man = json_decode($TS_PTable_Man[0]->Defoult);
    $TS_PTable_Array = [];
     $TS_PTable_Arr = [];
    for ($i = 0; $i < count($default_Col_Man); $i++) {
        if ($default_Col_Man[$i]->Total_Soft_PTable_Them == $PTable_Type) {
            array_push($TS_PTable_Array, $default_Col_Man[$i]);
        }
    }
    $num_man=
     array_push($TS_PTable_Arr, $TS_PTable_Array);
      array_push($TS_PTable_Arr, end($default_Col_Man)->id );
//    echo $PTable_Type;
    print_r(json_encode($TS_PTable_Arr));
    die();
}


add_action('wp_ajax_Total_Soft_PTable_Save', 'Total_Soft_PTable_Save_Callback');
add_action('wp_ajax_nopriv_Total_Soft_PTable_Save', 'Total_Soft_PTable_Save_Callback');
function Total_Soft_PTable_Save_Callback()
{

     global $wpdb;
    $table_name3 = $wpdb->prefix . "totalsoft_ptable_manager";
    $table_name4 = $wpdb->prefix . "totalsoft_ptable_cols";
    $table_name5 = $wpdb->prefix . "totalsoft_ptable_sets";
 //        START SELECT TABLE MANAGER


    //        START SELECT TABLE MANAGER
        $New_PTable = $wpdb->get_results($wpdb->prepare("SELECT Defoult FROM $table_name3"));
        $value = (json_decode(json_encode($New_PTable), TRUE));
        $result = '';
        $valueArray = json_decode($value[0]['Defoult'], TRUE);
        //        END SELECT TABLE MANAGER

        //        START SELECT TABLE COLS
        $PTable = $wpdb->get_results($wpdb->prepare("SELECT PTable FROM $table_name4"));
        $valuel = json_decode(json_encode($PTable), TRUE);
        $valueArrayCol = json_decode($valuel[0]['PTable'], TRUE);
        //        END SELECT TABLE COLS

        //        START SELECT TABLE SETTINGS
        $PTSets = $wpdb->get_results($wpdb->prepare("SELECT Price FROM $table_name5"));
        $valuels = json_decode(json_encode($PTSets), TRUE);
        $valueArrays = json_decode($valuels[0]['Price'], TRUE);

        //$id_set = $valueArrayCol[count($valueArrayCol) - 1]['id'];

        //$id = 0;

          $obj = json_decode(json_encode($_POST['foobarArr']), TRUE);
            $foobarArr=[];
            foreach($obj as $key => $value) {
                array_push($foobarArr, $value);
            }

           // $id = $valueArray[count($valueArray) - 1]['id'] + 1;
            $type_Var = 1;
            if ($Total_Soft_PTable_Col_Type == 'type2') {
                $type_Var = 2;
            }
            if ($Total_Soft_PTable_Col_Type == 'type3') {
                $type_Var = 3;
            }
            if ($Total_Soft_PTable_Col_Type == 'type4') {
                $type_Var = 4;
            }
            if ($Total_Soft_PTable_Col_Type == 'type5') {
                $type_Var = 5;
            }
             $Total_Soft_PTable_Col_Type = $foobarArr[2][0];
            $Total_Soft_PTable_Col_Val_Id = $foobarArr[2][9];
            $Total_Soft_PTable_Theme_Type = $foobarArr[2][10];
            $Total_Soft_PTable_Col_Count = $foobarArr[2][8];
            $Total_SoftPTable_Update= $foobarArr[2][7];
             $Total_Soft_PTable_M_01= $foobarArr[2][2];
             $Total_Soft_PTable_M_02= $foobarArr[2][3];
             $Total_Soft_PTable_M_03= $foobarArr[2][4];
             $Total_Soft_PTable_Add_Set= $foobarArr[2][5];
             $Total_Soft_PTable_Col_Del= $foobarArr[2][6];
             $Total_Soft_PTable_Col_Sel_Count=$foobarArr[2][1];

            usort($foobarArr[0],
            function ($a, $b) {
                    if ($a['id']==$b['id']) return 0;
                    return ($a['id']<$b['id'])?-1:1;
                }
             );
            usort($foobarArr[1],
            function ($a, $b) {
                    if ($a['id']==$b['id']) return 0;
                    return ($a['id']<$b['id'])?-1:1;
                }
             );

             //MANAGER START
            array_push($valueArray, ['id' => $Total_SoftPTable_Update, 'Total_Soft_PTable_Title' => 'Table ' . $Total_SoftPTable_Update, 'Total_Soft_PTable_Them' => $Total_Soft_PTable_Col_Type, 'Total_Soft_PTable_Cols_Count' => $Total_Soft_PTable_Col_Sel_Count, 'Total_Soft_PTable_M_01' => $Total_Soft_PTable_M_01, 'Total_Soft_PTable_M_02' => $Total_Soft_PTable_M_02, 'Total_Soft_PTable_M_03' => $Total_Soft_PTable_M_03]);


//            echo "<pre>";
//            print_r($valueArray);
//            echo "</pre>";

            $result = json_encode($valueArray);
            $wpdb->query($wpdb->prepare("UPDATE $table_name3 set Defoult = %s WHERE id = 1", $result));

            //MANAGER END
        

             for ($i = 0; $i < $Total_Soft_PTable_Col_Sel_Count; $i++) {

                  //        ADD COLUMNS
                     array_push($valueArrayCol, ['id' => $foobarArr[1][$i]['id'], 'index' => $foobarArr[1][$i]['index'], 'TS_PTable_TType' => $foobarArr[1][$i]['TS_PTable_TType'], 'PTable_ID' => $foobarArr[1][$i]['PTable_ID'], 'TS_PTable_TSetting' => $foobarArr[1][$i]['TS_PTable_TSetting'], 'TS_PTable_TText' => html_entity_decode (  str_replace("\&", "&", esc_html($foobarArr[1][$i]['TS_PTable_TText'])) ), 'TS_PTable_TIcon' =>  $foobarArr[1][$i]['TS_PTable_TIcon'], 'TS_PTable_PCur' => html_entity_decode ( str_replace("\&", "&", esc_html($foobarArr[1][$i]['TS_PTable_PCur'])) ), 'TS_PTable_PVal' => $foobarArr[1][$i]['TS_PTable_PVal'], 'TS_PTable_PPlan' => html_entity_decode (  str_replace("\&", "&", esc_html($foobarArr[1][$i]['TS_PTable_PPlan'])) ), 'TS_PTable_FCount' =>  $foobarArr[1][$i]['TS_PTable_FCount'], 'TS_PTable_FIcon' => $foobarArr[1][$i]['TS_PTable_FIcon'], 'TS_PTable_FText' =>  html_entity_decode ( str_replace("\&", "&", esc_html($foobarArr[1][$i]['TS_PTable_FText'])) ), 'TS_PTable_C_01' =>  $foobarArr[1][$i]['TS_PTable_C_01'], 'TS_PTable_BText' =>  html_entity_decode ( str_replace("\&", "&", esc_html($foobarArr[1][$i]['TS_PTable_BText']))), 'TS_PTable_BIcon' =>  $foobarArr[1][$i]['TS_PTable_BIcon'], 'TS_PTable_BLink' =>  $foobarArr[1][$i]['TS_PTable_BLink']]);

                     //        ADD SETTINGS
                    array_push($valueArrays, ['id' =>  $foobarArr[0][$i]['id'], 
                        'index' =>  $foobarArr[0][$i]['index'], 
                        'TS_PTable_TType' => $foobarArr[0][$i]['TS_PTable_TType'],
                        'PTable_ID' =>  $foobarArr[0][$i]['PTable_ID'],
                        'TS_PTable_ST_00' =>  $foobarArr[0][$i]['TS_PTable_ST_00'],
                        'TS_PTable_ST_01' =>  $foobarArr[0][$i]['TS_PTable_ST_01'],
                        'TS_PTable_ST_02' =>  $foobarArr[0][$i]['TS_PTable_ST_02'], 
                        'TS_PTable_ST_03' =>  $foobarArr[0][$i]['TS_PTable_ST_03'], 
                        'TS_PTable_ST_04' =>  $foobarArr[0][$i]['TS_PTable_ST_04'],
                        'TS_PTable_ST_05' =>  $foobarArr[0][$i]['TS_PTable_ST_05'], 
                        'TS_PTable_ST_06' =>  $foobarArr[0][$i]['TS_PTable_ST_06'], 
                        'TS_PTable_ST_07' =>  $foobarArr[0][$i]['TS_PTable_ST_07'], 
                        'TS_PTable_ST_08' =>  $foobarArr[0][$i]['TS_PTable_ST_08'], 
                        'TS_PTable_ST_09' =>  $foobarArr[0][$i]['TS_PTable_ST_09'], 
                        'TS_PTable_ST_10' =>  $foobarArr[0][$i]['TS_PTable_ST_10'],
                        'TS_PTable_ST_11' =>  $foobarArr[0][$i]['TS_PTable_ST_11'],
                        'TS_PTable_ST_12' =>  $foobarArr[0][$i]['TS_PTable_ST_12'],
                        'TS_PTable_ST_13' =>  $foobarArr[0][$i]['TS_PTable_ST_13'],
                        'TS_PTable_ST_14' =>  $foobarArr[0][$i]['TS_PTable_ST_14'], 
                        'TS_PTable_ST_15' =>  $foobarArr[0][$i]['TS_PTable_ST_15'],
                        'TS_PTable_ST_16' =>  $foobarArr[0][$i]['TS_PTable_ST_16'],
                        'TS_PTable_ST_17' =>  $foobarArr[0][$i]['TS_PTable_ST_17'], 
                        'TS_PTable_ST_18' =>  $foobarArr[0][$i]['TS_PTable_ST_18'],
                        'TS_PTable_ST_19' =>  $foobarArr[0][$i]['TS_PTable_ST_19'],
                        'TS_PTable_ST_20' =>  $foobarArr[0][$i]['TS_PTable_ST_20'], 
                        'TS_PTable_ST_21' =>  $foobarArr[0][$i]['TS_PTable_ST_21'],
                        'TS_PTable_ST_21_1' =>  $foobarArr[0][$i]['TS_PTable_ST_21_1'], 
                        'TS_PTable_ST_22' =>  $foobarArr[0][$i]['TS_PTable_ST_22'], 
                        'TS_PTable_ST_23' =>  $foobarArr[0][$i]['TS_PTable_ST_23'],
                        'TS_PTable_ST_24' =>  $foobarArr[0][$i]['TS_PTable_ST_24'],
                        'TS_PTable_ST_25' =>  $foobarArr[0][$i]['TS_PTable_ST_25'],
                        'TS_PTable_ST_26' =>  $foobarArr[0][$i]['TS_PTable_ST_26'],
                        'TS_PTable_ST_27' =>  $foobarArr[0][$i]['TS_PTable_ST_27'],
                        'TS_PTable_ST_28' =>  $foobarArr[0][$i]['TS_PTable_ST_28'],
                        'TS_PTable_ST_29' =>  $foobarArr[0][$i]['TS_PTable_ST_29'],
                        'TS_PTable_ST_30' =>  $foobarArr[0][$i]['TS_PTable_ST_30'],
                        'TS_PTable_ST_31' =>  $foobarArr[0][$i]['TS_PTable_ST_31'], 
                        'TS_PTable_ST_32' =>  $foobarArr[0][$i]['TS_PTable_ST_32'], 
                        'TS_PTable_ST_33' =>  $foobarArr[0][$i]['TS_PTable_ST_33'], 
                        'TS_PTable_ST_34' =>  $foobarArr[0][$i]['TS_PTable_ST_34'], 
                        'TS_PTable_ST_35' =>  $foobarArr[0][$i]['TS_PTable_ST_35'],
                        'TS_PTable_ST_36' =>  $foobarArr[0][$i]['TS_PTable_ST_36'], 
                        'TS_PTable_ST_37' =>  $foobarArr[0][$i]['TS_PTable_ST_37'], 
                        'TS_PTable_ST_38' =>  $foobarArr[0][$i]['TS_PTable_ST_38'], 
                        'TS_PTable_ST_39' =>  $foobarArr[0][$i]['TS_PTable_ST_39'],
                        'TS_PTable_ST_40' =>  $foobarArr[0][$i]['TS_PTable_ST_40']]);
                        }

                 $results = (json_encode(array_values($valueArrayCol)));
            $wpdb->query($wpdb->prepare("UPDATE $table_name4 set PTable = %s WHERE id = 1", $results));
            $resultSet = (json_encode(array_values($valueArrays)));
            $wpdb->query($wpdb->prepare("UPDATE $table_name5 set Price = %s WHERE id = 1", $resultSet));
 print_r((json_encode( end($valueArray))));
    die();
}

add_action('wp_ajax_Total_Soft_PTable_Update', 'Total_Soft_PTable_Update_Callback');
add_action('wp_ajax_nopriv_Total_Soft_PTable_Update', 'Total_Soft_PTable_Update_Callback');
function Total_Soft_PTable_Update_Callback()
{
    global $wpdb;
    $table_name3 = $wpdb->prefix . "totalsoft_ptable_manager";
    $table_name4 = $wpdb->prefix . "totalsoft_ptable_cols";
    $table_name5 = $wpdb->prefix . "totalsoft_ptable_sets";
 //        START SELECT TABLE MANAGER



       $New_PTable = $wpdb->get_results($wpdb->prepare("SELECT Defoult FROM $table_name3"));
        $value = (json_decode(json_encode($New_PTable), TRUE));
        $result = '';
        $valueArray = json_decode($value[0]['Defoult'], TRUE);
        //        END SELECT TABLE MANAGER

            //        START SELECT TABLE COLS
        $PTable = $wpdb->get_results($wpdb->prepare("SELECT PTable FROM $table_name4"));
        $valuel = json_decode(json_encode($PTable), TRUE);
        $valueArrayCol = json_decode($valuel[0]['PTable'], TRUE);
        //        END SELECT TABLE COLS

        //        START SELECT TABLE SETTINGS
        $PTSets = $wpdb->get_results($wpdb->prepare("SELECT Price FROM $table_name5"));
        $valuels = json_decode(json_encode($PTSets), TRUE);
        $valueArrays = json_decode($valuels[0]['Price'], TRUE);
        //        END SELECT TABLE SETTINGS


     $obj = json_decode(json_encode($_POST['foobarArr']), TRUE);
     $foobarArr=[];
     foreach($obj as $key => $value) {
        array_push($foobarArr, $value);
    }

    
            $Total_Soft_PTable_Col_Type = $foobarArr[2][0];
            $Total_Soft_PTable_Col_Val_Id = $foobarArr[2][9];
            $Total_Soft_PTable_Theme_Type = $foobarArr[2][10];
            $Total_Soft_PTable_Col_Count = $foobarArr[2][8];
            $Total_SoftPTable_Update= $foobarArr[2][7];
             $Total_Soft_PTable_M_01= $foobarArr[2][2];
             $Total_Soft_PTable_M_02= $foobarArr[2][3];
             $Total_Soft_PTable_M_03= $foobarArr[2][4];
             $Total_Soft_PTable_Add_Set= $foobarArr[2][5];
             $Total_Soft_PTable_Col_Del= $foobarArr[2][6];
             $Total_Soft_PTable_Col_Sel_Count=$foobarArr[2][1];
            //MANAGER START
            foreach ($valueArray as $key => $res) {
                if ($res['id'] == $Total_SoftPTable_Update) {
                    $valueArray[$key]['Total_Soft_PTable_Cols_Count'] = $Total_Soft_PTable_Col_Sel_Count;
                    $valueArray[$key]['Total_Soft_PTable_M_01'] = $Total_Soft_PTable_M_01;
                    $valueArray[$key]['Total_Soft_PTable_M_02'] = $Total_Soft_PTable_M_02;
                    $valueArray[$key]['Total_Soft_PTable_M_03'] = $Total_Soft_PTable_M_03;
                    break;
                }
            }
         $result = json_encode($valueArray);
            $wpdb->query($wpdb->prepare("UPDATE $table_name3 set Defoult = %s WHERE id = 1", $result));
            //MANAGER END
 $CI = 0;         
if ( $foobarArr[1][$CI]['TS_PTable_TType']!="type0" ) {
    
   usort($foobarArr[0],
            function ($a, $b) {
                    if ($a['id']==$b['id']) return 0;
                    return ($a['id']<$b['id'])?-1:1;
                }
             );
            usort($foobarArr[1],
            function ($a, $b) {
                    if ($a['id']==$b['id']) return 0;
                    return ($a['id']<$b['id'])?-1:1;
                }
             );
        }
            // $New_Col_Count = (int)$Total_Soft_PTable_Col_Sel_Count - (int)$Total_Soft_PTable_Add_Set;
             $Total_Soft_PTable_Col_Del_arr = explode(',', $Total_Soft_PTable_Col_Del);
             sort($Total_Soft_PTable_Col_Del_arr);
            
             $arr_id = 0;
             $valueArrayCol_count=count($valueArrayCol);
             $results = '';
for ($i = 1; $i <= $valueArrayCol_count; $i++) {
                if ($valueArrayCol[$i - 1]['TS_PTable_TType'] == $Total_Soft_PTable_Col_Type) {
                   
                     if (   $foobarArr[1][$CI]['TS_PTable_TType']!="type0" && ($valueArrayCol[$i - 1]['PTable_ID'] == $Total_SoftPTable_Update)   ||  $foobarArr[1][$CI]['TS_PTable_TType']!="type0" && ($valueArrayCol[$i - 1]['id'] == $foobarArr[1][$CI]['id'])) {
                     
                        if (  $foobarArr[1][$CI]['index'] != '') {
                            $valueArrayCol[$i - 1]['index'] = $foobarArr[1][$CI]['index'];
                        }
                        if ( $foobarArr[1][$CI]['TS_PTable_TText'] != '') {
                            $valueArrayCol[$i - 1]['TS_PTable_TText'] = html_entity_decode (  str_replace("\&", "&", esc_html($foobarArr[1][$CI]['TS_PTable_TText'])) );
                        } else {
                            $valueArrayCol[$i - 1]['TS_PTable_TText'];
                        }
                        if ($foobarArr[1][$CI]['TS_PTable_TIcon'] != "") {
                            $valueArrayCol[$i - 1]['TS_PTable_TIcon'] = $foobarArr[1][$CI]['TS_PTable_TIcon'];
                        } else {
                            $valueArrayCol[$i - 1]['TS_PTable_TIcon'];
                        }
                        if ($foobarArr[1][$CI]['TS_PTable_PCur']!= "") {
                            $valueArrayCol[$i - 1]['TS_PTable_PCur'] = html_entity_decode ( str_replace("\&", "&", esc_html($foobarArr[1][$CI]['TS_PTable_PCur']) ));
                        } else {
                            $valueArrayCol[$i - 1]['TS_PTable_PCur'];
                        }
                        if ($foobarArr[1][$CI]['TS_PTable_PVal'] != "") {
                            $valueArrayCol[$i - 1]['TS_PTable_PVal'] = $foobarArr[1][$CI]['TS_PTable_PVal'];
                        } else {
                            $valueArrayCol[$i - 1]['TS_PTable_PVal'];
                        }
                        if ($foobarArr[1][$CI]['TS_PTable_PPlan'] != "") {
                            $valueArrayCol[$i - 1]['TS_PTable_PPlan'] = html_entity_decode ( str_replace("\&", "&", esc_html($foobarArr[1][$CI]['TS_PTable_PPlan'])) );
                        } else {
                            $valueArrayCol[$i - 1]['TS_PTable_PPlan'];
                        }
                        if ($foobarArr[1][$CI]['TS_PTable_BText'] != '') {
                            $valueArrayCol[$i - 1]['TS_PTable_BText'] =html_entity_decode ( str_replace("\&", "&", esc_html($foobarArr[1][$CI]['TS_PTable_BText'])) );
                        } else {
                            $valueArrayCol[$i - 1]['TS_PTable_BText'];
                        }
                        if ($foobarArr[1][$CI]['TS_PTable_BIcon'] != "") {
                            $valueArrayCol[$i - 1]['TS_PTable_BIcon'] = $foobarArr[1][$CI]['TS_PTable_BIcon'];
                        } else {
                            $valueArrayCol[$i - 1]['TS_PTable_BIcon'];
                        }
                        if ($foobarArr[1][$CI]['TS_PTable_BLink'] != "") {
                            $valueArrayCol[$i - 1]['TS_PTable_BLink'] = html_entity_decode ( $foobarArr[1][$CI]['TS_PTable_BLink']);
                        } else {
                            $valueArrayCol[$i - 1]['TS_PTable_BLink'];
                        }
                        if ( $foobarArr[1][$CI]['TS_PTable_FCount'] != "") {
                            $valueArrayCol[$i - 1]['TS_PTable_FCount'] = $foobarArr[1][$CI]['TS_PTable_FCount'];
                        } else {
                            $valueArrayCol[$i - 1]['TS_PTable_FCount'];
                        }
                        if ($foobarArr[1][$CI]['TS_PTable_FIcon'] != "") {
                            $valueArrayCol[$i - 1]['TS_PTable_FIcon'] = $foobarArr[1][$CI]['TS_PTable_FIcon'];
                        } else {
                            $valueArrayCol[$i - 1]['TS_PTable_FIcon'];
                        }
                        if ( $foobarArr[1][$CI]['TS_PTable_FText'] != "") {
                            $valueArrayCol[$i - 1]['TS_PTable_FText'] = html_entity_decode ( str_replace("\&", "&", esc_html($foobarArr[1][$CI]['TS_PTable_FText']) ));
                        } else {
                            $valueArrayCol[$i - 1]['TS_PTable_FText'];
                        }
                        if ($foobarArr[1][$CI]['TS_PTable_C_01'] != "") {
                            $valueArrayCol[$i - 1]['TS_PTable_C_01'] = $foobarArr[1][$CI]['TS_PTable_C_01'];
                        } else {
                            $valueArrayCol[$i - 1]['TS_PTable_C_01'];
                        }
                        $CI++;
                     }
                    if ($valueArrayCol[$i - 1]['id'] == $Total_Soft_PTable_Col_Del_arr[$arr_id]) {
                        unset($valueArrayCol[$i - 1]);
                        array_values($valueArrayCol);
                         $arr_id++;
                    }

                }
               
            }

         

              $arr_id = 0;
              $si=0;
                $valueArrays_count=count($valueArrays);
            
                for ($i = 0; $i < $valueArrays_count; $i++) {

                    if ($foobarArr[0][$si]['TS_PTable_TType']!="type0" && $valueArrays[$i]['id'] == $foobarArr[0][$si]['id'] ) {
                        $valueArrays[$i]['TS_PTable_ST_00'] = $foobarArr[0][$si]['TS_PTable_ST_00'];
                        $valueArrays[$i]['TS_PTable_ST_01'] = $foobarArr[0][$si]['TS_PTable_ST_01'];
                        $valueArrays[$i]['index'] = $foobarArr[0][$si]['index'];
                        $valueArrays[$i]['TS_PTable_ST_02'] = $foobarArr[0][$si]['TS_PTable_ST_02'];
                        $valueArrays[$i]['TS_PTable_ST_03'] = $foobarArr[0][$si]['TS_PTable_ST_03'];
                        $valueArrays[$i]['TS_PTable_ST_04'] = $foobarArr[0][$si]['TS_PTable_ST_04'];
                        $valueArrays[$i]['TS_PTable_ST_05'] = $foobarArr[0][$si]['TS_PTable_ST_05'];
                        $valueArrays[$i]['TS_PTable_ST_06'] = $foobarArr[0][$si]['TS_PTable_ST_06'];
                        $valueArrays[$i]['TS_PTable_ST_07'] = $foobarArr[0][$si]['TS_PTable_ST_07'];
                        $valueArrays[$i]['TS_PTable_ST_08'] = $foobarArr[0][$si]['TS_PTable_ST_08'];
                        $valueArrays[$i]['TS_PTable_ST_09'] = $foobarArr[0][$si]['TS_PTable_ST_09'];
                        $valueArrays[$i]['TS_PTable_ST_10'] = $foobarArr[0][$si]['TS_PTable_ST_10'];
                        $valueArrays[$i]['TS_PTable_ST_11'] = $foobarArr[0][$si]['TS_PTable_ST_11'];
                        $valueArrays[$i]['TS_PTable_ST_12'] = $foobarArr[0][$si]['TS_PTable_ST_12'];
                        $valueArrays[$i]['TS_PTable_ST_13'] = $foobarArr[0][$si]['TS_PTable_ST_13'];
                        $valueArrays[$i]['TS_PTable_ST_14'] = $foobarArr[0][$si]['TS_PTable_ST_14'];
                        $valueArrays[$i]['TS_PTable_ST_15'] = $foobarArr[0][$si]['TS_PTable_ST_15'];
                        $valueArrays[$i]['TS_PTable_ST_16'] = $foobarArr[0][$si]['TS_PTable_ST_16'];
                        $valueArrays[$i]['TS_PTable_ST_17'] = $foobarArr[0][$si]['TS_PTable_ST_17'];
                        $valueArrays[$i]['TS_PTable_ST_18'] = $foobarArr[0][$si]['TS_PTable_ST_18'];
                        $valueArrays[$i]['TS_PTable_ST_19'] = $foobarArr[0][$si]['TS_PTable_ST_19'];
                        $valueArrays[$i]['TS_PTable_ST_20'] = $foobarArr[0][$si]['TS_PTable_ST_20'];
                        $valueArrays[$i]['TS_PTable_ST_21'] = $foobarArr[0][$si]['TS_PTable_ST_21'];
                        $valueArrays[$i]['TS_PTable_ST_21_1'] = $foobarArr[0][$si]['TS_PTable_ST_21_1'];
                        $valueArrays[$i]['TS_PTable_ST_22'] = $foobarArr[0][$si]['TS_PTable_ST_22'];
                        $valueArrays[$i]['TS_PTable_ST_23'] = $foobarArr[0][$si]['TS_PTable_ST_23'];
                        $valueArrays[$i]['TS_PTable_ST_24'] = $foobarArr[0][$si]['TS_PTable_ST_24'];
                        $valueArrays[$i]['TS_PTable_ST_25'] = $foobarArr[0][$si]['TS_PTable_ST_25'];
                        $valueArrays[$i]['TS_PTable_ST_26'] = $foobarArr[0][$si]['TS_PTable_ST_26'];
                        $valueArrays[$i]['TS_PTable_ST_27'] = $foobarArr[0][$si]['TS_PTable_ST_27'];
                        $valueArrays[$i]['TS_PTable_ST_28'] = $foobarArr[0][$si]['TS_PTable_ST_28'];
                        $valueArrays[$i]['TS_PTable_ST_29'] = $foobarArr[0][$si]['TS_PTable_ST_29'];
                        $valueArrays[$i]['TS_PTable_ST_30'] = $foobarArr[0][$si]['TS_PTable_ST_30'];
                        $valueArrays[$i]['TS_PTable_ST_31'] = $foobarArr[0][$si]['TS_PTable_ST_31'];
                        $valueArrays[$i]['TS_PTable_ST_32'] = $foobarArr[0][$si]['TS_PTable_ST_32'];
                        $valueArrays[$i]['TS_PTable_ST_33'] = $foobarArr[0][$si]['TS_PTable_ST_33'];
                        $valueArrays[$i]['TS_PTable_ST_34'] = $foobarArr[0][$si]['TS_PTable_ST_34'];
                        $valueArrays[$i]['TS_PTable_ST_35'] = $foobarArr[0][$si]['TS_PTable_ST_35'];
                        $valueArrays[$i]['TS_PTable_ST_36'] = $foobarArr[0][$si]['TS_PTable_ST_36'];
                        $valueArrays[$i]['TS_PTable_ST_37'] = $foobarArr[0][$si]['TS_PTable_ST_37'];
                        $valueArrays[$i]['TS_PTable_ST_38'] = $foobarArr[0][$si]['TS_PTable_ST_38'];
                        $valueArrays[$i]['TS_PTable_ST_39'] = $foobarArr[0][$si]['TS_PTable_ST_39'];
                        $valueArrays[$i]['TS_PTable_ST_40'] = $foobarArr[0][$si]['TS_PTable_ST_40'];
                        $si++;
                      
                    }

                        if ($valueArrays[$i]['id'] == $Total_Soft_PTable_Col_Del_arr[$arr_id]) {
                            unset($valueArrays[$i]);
                            array_values($valueArrays);
                            $arr_id++;
                        }
                    
                   
                }


                 if ($Total_Soft_PTable_Add_Set > 0) {
                 usort($foobarArr[3],
                    function ($a, $b) {
                            if ($a['id']==$b['id']) return 0;
                            return ($a['id']<$b['id'])?-1:1;
                        }
                     );
                usort($foobarArr[4],
                function ($a, $b) {
                        if ($a['id']==$b['id']) return 0;
                        return ($a['id']<$b['id'])?-1:1;
                    }
                 );
                 for ($i = 0; $i < $Total_Soft_PTable_Add_Set; $i++) {
                   // $s++;
                //        ADD COLUMNS
                     array_push($valueArrayCol, ['id' => $foobarArr[3][$i]['id'], 'index' => $foobarArr[3][$i]['index'], 'TS_PTable_TType' => $foobarArr[3][$i]['TS_PTable_TType'], 'PTable_ID' => $foobarArr[3][$i]['PTable_ID'], 'TS_PTable_TSetting' => $foobarArr[3][$i]['TS_PTable_TSetting'], 'TS_PTable_TText' =>  html_entity_decode ( str_replace("\&", "&", esc_html($foobarArr[3][$i]['TS_PTable_TText']))), 'TS_PTable_TIcon' =>  $foobarArr[3][$i]['TS_PTable_TIcon'], 'TS_PTable_PCur' => html_entity_decode ( str_replace("\&", "&", esc_html( $foobarArr[3][$i]['TS_PTable_PCur'])) ), 'TS_PTable_PVal' => $foobarArr[3][$i]['TS_PTable_PVal'], 'TS_PTable_PPlan' =>  html_entity_decode ( str_replace("\&", "&", esc_html($foobarArr[3][$i]['TS_PTable_PPlan'])) ), 'TS_PTable_FCount' =>  $foobarArr[3][$i]['TS_PTable_FCount'], 'TS_PTable_FIcon' => $foobarArr[3][$i]['TS_PTable_FIcon'], 'TS_PTable_FText' =>  html_entity_decode ( str_replace("\&", "&", esc_html($foobarArr[3][$i]['TS_PTable_FText']))), 'TS_PTable_C_01' =>  $foobarArr[3][$i]['TS_PTable_C_01'], 'TS_PTable_BText' =>  html_entity_decode ( str_replace("\&", "&", esc_html($foobarArr[3][$i]['TS_PTable_BText']))), 'TS_PTable_BIcon' =>  $foobarArr[3][$i]['TS_PTable_BIcon'], 'TS_PTable_BLink' =>  html_entity_decode ( $foobarArr[3][$i]['TS_PTable_BLink'])]);

                     //        ADD SETTINGS
                    array_push($valueArrays, ['id' =>  $foobarArr[4][$i]['id'], 
                        'index' =>  $foobarArr[4][$i]['index'], 
                        'TS_PTable_TType' => $foobarArr[4][$i]['TS_PTable_TType'],
                        'PTable_ID' =>  $foobarArr[4][$i]['PTable_ID'],
                        'TS_PTable_ST_00' =>  $foobarArr[4][$i]['TS_PTable_ST_00'],
                        'TS_PTable_ST_01' =>  $foobarArr[4][$i]['TS_PTable_ST_01'],
                        'TS_PTable_ST_02' =>  $foobarArr[4][$i]['TS_PTable_ST_02'], 
                        'TS_PTable_ST_03' =>  $foobarArr[4][$i]['TS_PTable_ST_03'], 
                        'TS_PTable_ST_04' =>  $foobarArr[4][$i]['TS_PTable_ST_04'],
                        'TS_PTable_ST_05' =>  $foobarArr[4][$i]['TS_PTable_ST_05'], 
                        'TS_PTable_ST_06' =>  $foobarArr[4][$i]['TS_PTable_ST_06'], 
                        'TS_PTable_ST_07' =>  $foobarArr[4][$i]['TS_PTable_ST_07'], 
                        'TS_PTable_ST_08' =>  $foobarArr[4][$i]['TS_PTable_ST_08'], 
                        'TS_PTable_ST_09' =>  $foobarArr[4][$i]['TS_PTable_ST_09'], 
                        'TS_PTable_ST_10' =>  $foobarArr[4][$i]['TS_PTable_ST_10'],
                        'TS_PTable_ST_11' =>  $foobarArr[4][$i]['TS_PTable_ST_11'],
                        'TS_PTable_ST_12' =>  $foobarArr[4][$i]['TS_PTable_ST_12'],
                        'TS_PTable_ST_13' =>  $foobarArr[4][$i]['TS_PTable_ST_13'],
                        'TS_PTable_ST_14' =>  $foobarArr[4][$i]['TS_PTable_ST_14'], 
                        'TS_PTable_ST_15' =>  $foobarArr[4][$i]['TS_PTable_ST_15'],
                        'TS_PTable_ST_16' =>  $foobarArr[4][$i]['TS_PTable_ST_16'],
                        'TS_PTable_ST_17' =>  $foobarArr[4][$i]['TS_PTable_ST_17'], 
                        'TS_PTable_ST_18' =>  $foobarArr[4][$i]['TS_PTable_ST_18'],
                        'TS_PTable_ST_19' =>  $foobarArr[4][$i]['TS_PTable_ST_19'],
                        'TS_PTable_ST_20' =>  $foobarArr[4][$i]['TS_PTable_ST_20'], 
                        'TS_PTable_ST_21' =>  $foobarArr[4][$i]['TS_PTable_ST_21'],
                        'TS_PTable_ST_21_1' =>  $foobarArr[4][$i]['TS_PTable_ST_21_1'], 
                        'TS_PTable_ST_22' =>  $foobarArr[4][$i]['TS_PTable_ST_22'], 
                        'TS_PTable_ST_23' =>  $foobarArr[4][$i]['TS_PTable_ST_23'],
                        'TS_PTable_ST_24' =>  $foobarArr[4][$i]['TS_PTable_ST_24'],
                        'TS_PTable_ST_25' =>  $foobarArr[4][$i]['TS_PTable_ST_25'],
                        'TS_PTable_ST_26' =>  $foobarArr[4][$i]['TS_PTable_ST_26'],
                        'TS_PTable_ST_27' =>  $foobarArr[4][$i]['TS_PTable_ST_27'],
                        'TS_PTable_ST_28' =>  $foobarArr[4][$i]['TS_PTable_ST_28'],
                        'TS_PTable_ST_29' =>  $foobarArr[4][$i]['TS_PTable_ST_29'],
                        'TS_PTable_ST_30' =>  $foobarArr[4][$i]['TS_PTable_ST_30'],
                        'TS_PTable_ST_31' =>  $foobarArr[4][$i]['TS_PTable_ST_31'], 
                        'TS_PTable_ST_32' =>  $foobarArr[4][$i]['TS_PTable_ST_32'], 
                        'TS_PTable_ST_33' =>  $foobarArr[4][$i]['TS_PTable_ST_33'], 
                        'TS_PTable_ST_34' =>  $foobarArr[4][$i]['TS_PTable_ST_34'], 
                        'TS_PTable_ST_35' =>  $foobarArr[4][$i]['TS_PTable_ST_35'],
                        'TS_PTable_ST_36' =>  $foobarArr[4][$i]['TS_PTable_ST_36'], 
                        'TS_PTable_ST_37' =>  $foobarArr[4][$i]['TS_PTable_ST_37'], 
                        'TS_PTable_ST_38' =>  $foobarArr[4][$i]['TS_PTable_ST_38'], 
                        'TS_PTable_ST_39' =>  $foobarArr[4][$i]['TS_PTable_ST_39'],
                        'TS_PTable_ST_40' =>  $foobarArr[4][$i]['TS_PTable_ST_40']]);
                        }
                    }


                    $results = (json_encode(array_values($valueArrayCol)));
            $wpdb->query($wpdb->prepare("UPDATE $table_name4 set PTable = %s WHERE id = 1", $results));


                 $resultSet = (json_encode(array_values($valueArrays)));
                $wpdb->query($wpdb->prepare("UPDATE $table_name5 set Price = %s WHERE id = 1", $resultSet));
   

     print_r((json_encode($valueArrayCol)));
    die();
}
?>