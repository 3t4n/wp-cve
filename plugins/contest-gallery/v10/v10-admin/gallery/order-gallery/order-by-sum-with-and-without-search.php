<?php




$sumCountRaddManipTotalSum = ',';
$sumCountRaddManipTotalCount = '';
$CountRtotalSumCalculated = '';
$countFields = 'CountRtotalCount, CountRtotalSum ';

if($order=='rating_desc_sum_with_manip' || $order=='rating_asc_sum_with_manip'){

    /*$sumCountRaddManipTotalSum = ", SUM(
      CASE WHEN $tablenameIP.pid > 0 AND $tablenameIP.Rating = '1' AND $tablename.id = $tablenameIP.pid AND $tablename.addCountR5 > 0 THEN
       0
       ELSE 0 END +
      CASE WHEN $tablenameIP.pid > 0 AND $tablenameIP.Rating = '2' AND $tablename.id = $tablenameIP.pid AND $tablename.addCountR5 > 0 THEN
       0
       ELSE 0 END +
      CASE WHEN $tablenameIP.pid > 0 AND $tablenameIP.Rating = '3' AND $tablename.id = $tablenameIP.pid AND $tablename.addCountR5 > 0 THEN
       0
       ELSE 0 END +
      CASE WHEN $tablenameIP.pid > 0 AND $tablenameIP.Rating = '4' AND $tablename.id = $tablenameIP.pid AND $tablename.addCountR5 > 0 THEN
       0
       ELSE 0 END +
      CASE WHEN $tablename.GalleryID = 218 AND $tablename.addCountR5 > 0 THEN
       $tablename.addCountR5 * 5
       ELSE 0 END
      ) AS CountRtotalSumAdd,";*/

    $sumCountRaddManipTotalSum = ", (";

    for($iR=1;$iR<=$AllowRating-10;$iR++){
        if($iR==1){
            $sumCountRaddManipTotalSum .= "$tablename.addCountR$iR*$iR";
        }else{
            $sumCountRaddManipTotalSum .= "+$tablename.addCountR$iR*$iR";
        }
    }

    $sumCountRaddManipTotalSum .= ") AS CountRtotalSumAdd,";

    /*    $sumCountRaddManipTotalSum = ", ($tablename.addCountR1*1+$tablename.addCountR2*2+$tablename.addCountR3*3+$tablename.addCountR4*4+$tablename.addCountR5*5) AS CountRtotalSumAdd,";*/

    /* $sumCountRaddManipTotalCount = ", SUM(
       CASE WHEN $tablenameIP.pid > 0 AND $tablenameIP.Rating = '1' AND $tablename.id = $tablenameIP.pid AND $tablename.addCountR1 > 0 THEN $tablename.addCountR1 ELSE 0 END +
       CASE WHEN $tablenameIP.pid > 0 AND $tablenameIP.Rating = '2' AND $tablename.id = $tablenameIP.pid AND $tablename.addCountR2 > 0 THEN $tablename.addCountR2 ELSE 0 END +
       CASE WHEN $tablenameIP.pid > 0 AND $tablenameIP.Rating = '3' AND $tablename.id = $tablenameIP.pid AND $tablename.addCountR3 > 0 THEN $tablename.addCountR3 ELSE 0 END +
       CASE WHEN $tablenameIP.pid > 0 AND $tablenameIP.Rating = '4' AND $tablename.id = $tablenameIP.pid AND $tablename.addCountR4 > 0 THEN $tablename.addCountR4 ELSE 0 END +
       CASE WHEN $tablenameIP.pid > 0 AND $tablenameIP.Rating = '5' AND $tablename.id = $tablenameIP.pid AND $tablename.addCountR5 > 0 THEN $tablename.addCountR5 ELSE 0 END
       ) AS CountRtotalCountAdd";*/

    $sumCountRaddManipTotalCount = ", (";

    for($iR=1;$iR<=$AllowRating-10;$iR++){
        if($iR==1){
            $sumCountRaddManipTotalCount .= "$tablename.addCountR$iR";
        }else{
            $sumCountRaddManipTotalCount .= "+$tablename.addCountR$iR";
        }
    }

    $sumCountRaddManipTotalCount .= ") AS CountRtotalCountAdd";


    //$sumCountRaddManipTotalCount = ", ($tablename.addCountR1+$tablename.addCountR2+$tablename.addCountR3+$tablename.addCountR4+$tablename.addCountR5) AS CountRtotalCountAdd";

    $CountRtotalSumCalculated = '(CountRtotalCount+CountRtotalCountAdd) as CountRtotalCountWithManip, (CountRtotalSum+CountRtotalSumAdd) as CountRtotalSumWithManip,';

    $countFields = 'CountRtotalCount, CountRtotalCountAdd, CountRtotalSum, CountRtotalSumAdd ';

}

// var_dump($sumCountRaddManipTotalSum);

$sumCountR = "SUM(";

for($iR=1;$iR<=$AllowRating-10;$iR++){
    if($iR==1){
        $sumCountR .= "CASE WHEN $tablenameIP.pid > 0 AND $tablenameIP.Rating = '$iR' AND $tablename.id = $tablenameIP.pid THEN $iR ELSE 0 END";
    }else{
        $sumCountR .= " + CASE WHEN $tablenameIP.pid > 0 AND $tablenameIP.Rating = '$iR' AND $tablename.id = $tablenameIP.pid THEN $iR ELSE 0 END";
    }
}

$sumCountR .= ") AS CountRtotalSum$sumCountRaddManipTotalSum SUM(";

for($iR=1;$iR<=$AllowRating-10;$iR++){
    if($iR==1){
        $sumCountR .= "CASE WHEN $tablenameIP.pid > 0 AND $tablenameIP.Rating = '$iR' AND $tablename.id = $tablenameIP.pid THEN 1 ELSE 0 END";
    }else{
        $sumCountR .= " + CASE WHEN $tablenameIP.pid > 0 AND $tablenameIP.Rating = '$iR' AND $tablename.id = $tablenameIP.pid THEN 1 ELSE 0 END";
    }
}

$sumCountR .= ") AS CountRtotalCount$sumCountRaddManipTotalCount";

/*$sumCountR = "SUM(
                  CASE WHEN $tablenameIP.pid > 0 AND $tablenameIP.Rating = '1' AND $tablename.id = $tablenameIP.pid THEN 1 ELSE 0 END +
                  CASE WHEN $tablenameIP.pid > 0 AND $tablenameIP.Rating = '2' AND $tablename.id = $tablenameIP.pid THEN 2 ELSE 0 END +
                  CASE WHEN $tablenameIP.pid > 0 AND $tablenameIP.Rating = '3' AND $tablename.id = $tablenameIP.pid THEN 3 ELSE 0 END +
                  CASE WHEN $tablenameIP.pid > 0 AND $tablenameIP.Rating = '4' AND $tablename.id = $tablenameIP.pid THEN 4 ELSE 0 END +
                  CASE WHEN $tablenameIP.pid > 0 AND $tablenameIP.Rating = '5' AND $tablename.id = $tablenameIP.pid THEN 5 ELSE 0 END
                  ) AS CountRtotalSum$sumCountRaddManipTotalSum SUM(
                  CASE WHEN $tablenameIP.pid > 0 AND $tablenameIP.Rating = '1' AND $tablename.id = $tablenameIP.pid THEN 1 ELSE 0 END +
                  CASE WHEN $tablenameIP.pid > 0 AND $tablenameIP.Rating = '2' AND $tablename.id = $tablenameIP.pid THEN 1 ELSE 0 END +
                  CASE WHEN $tablenameIP.pid > 0 AND $tablenameIP.Rating = '3' AND $tablename.id = $tablenameIP.pid THEN 1 ELSE 0 END +
                  CASE WHEN $tablenameIP.pid > 0 AND $tablenameIP.Rating = '4' AND $tablename.id = $tablenameIP.pid THEN 1 ELSE 0 END +
                  CASE WHEN $tablenameIP.pid > 0 AND $tablenameIP.Rating = '5' AND $tablename.id = $tablenameIP.pid THEN 1 ELSE 0 END
                  ) AS CountRtotalCount$sumCountRaddManipTotalCount";*/

if($order=='rating_desc_sum'){
    $orderBy = "ORDER BY CountRtotalSum DESC, CountRtotalCount DESC, rowid DESC LIMIT $start, $step";
}

if($order=='rating_desc_sum_with_manip'){
    $orderBy = "ORDER BY CountRtotalSumWithManip DESC, CountRtotalCountWithManip DESC, rowid DESC LIMIT $start, $step";
}

if($order=='rating_asc_sum'){
    $orderBy = "ORDER BY CountRtotalSum ASC, CountRtotalCount ASC, rowid ASC LIMIT $start, $step";
}
if($order=='rating_asc_sum_with_manip'){
    $orderBy = "ORDER BY CountRtotalSumWithManip ASC, CountRtotalCountWithManip ASC, rowid ASC LIMIT $start, $step";
}


$selectWinnersOnly = 'AND';
if(!empty($_POST['cg_show_only_winners'])){
    $selectWinnersOnly = "AND $tablename.Winner = 1 ";
}

$selectActiveOnly = '';

if(!empty($_POST['cg_show_only_active'])){
    if(empty($_POST['cg_show_only_winners'])){
        $selectWinnersOnly = "";
    }
    $selectActiveOnly = " AND $tablename.Active = 1 ";
}

$selectInactiveOnly = '';

if(!empty($_POST['cg_show_only_inactive'])){
    if(empty($_POST['cg_show_only_winners'])){
        $selectWinnersOnly = "";
    }
    $selectInactiveOnly = " AND $tablename.Active = 0 ";
}

if(empty($_POST['cg_show_only_winners']) AND empty($_POST['cg_show_only_active']) AND empty($_POST['cg_show_only_inactive'])){
    $selectWinnersOnly = "";
}

if($search===''){

    $selectSQLQuery ="SELECT $countFields, $CountRtotalSumCalculated $fieldsToSelectString  from (SELECT DISTINCT $tablename.*, $tablenameIP.pid, $sumCountR,
                  (
                   CASE WHEN NOT EXISTS(SELECT NULL FROM $tablenameIP WHERE $tablename.id = $tablenameIP.pid)
                      THEN 1
                      ELSE 0
                   END 
                  ) AS CountRnotExists
                        FROM $tablenameIP, $tablename WHERE 
                        ($tablename.id = $tablenameIP.pid AND $tablename.GalleryID = %d AND $tablenameIP.Rating > 0 $selectWinnersOnly$selectActiveOnly$selectInactiveOnly) OR 
                        ($tablename.id != $tablenameIP.pid AND $tablename.GalleryID = %d $selectWinnersOnly$selectActiveOnly$selectInactiveOnly)
                        GROUP BY $tablename.id) AS CountRtotalAverage 
                        GROUP BY id $orderBy
                        ";

    $selectSQL = $wpdb->get_results($wpdb->prepare($selectSQLQuery,[
        $GalleryID,$GalleryID
    ]));

}else{


    //if(!empty($_POST['rating_desc_average']) OR !empty($_POST['rating_asc_average'])){

    // partial connect with max two tables at same time, otherwise load to long!!!
    $countSearchSQLQuery = "
                    SELECT COUNT(*) AS NumberOfRows FROM (SELECT $countFields, $CountRtotalSumCalculated $fieldsToSelectString  from (SELECT DISTINCT $tablename.*, $tablenameIP.pid, $sumCountR,
                  (
                   CASE WHEN NOT EXISTS(SELECT NULL FROM $tablenameIP WHERE $tablename.id = $tablenameIP.pid)
                      THEN 1
                      ELSE 0
                   END 
                  ) AS CountRnotExists
                        FROM $tablenameIP, $tablename, $tablenameentries WHERE 
                        (($tablename.id = $tablenameIP.pid AND $tablename.GalleryID = %d AND $tablenameIP.Rating > 0) OR 
                        ($tablename.id != $tablenameIP.pid AND $tablename.GalleryID = %d))
                         AND 
                        (
                        $tablename.GalleryID = %d  
                        $selectWinnersOnly$selectActiveOnly$selectInactiveOnly AND 
                       $tablenameentries.GalleryID = %d AND 
                       $tablename.id = $tablenameentries.pid AND 
                       ($tablenameentries.Short_Text like %s OR $tablenameentries.Long_Text like %s OR $tablename.id like %s OR $tablename.Exif like %s) AND 
                       $tablenameentries.f_input_id >= 1
                         )
                        GROUP BY $tablename.id) AS CountRtotalSum
                        
                        UNION
                
                SELECT $countFields, $CountRtotalSumCalculated $fieldsToSelectString  from (SELECT DISTINCT $tablename.*, $tablenameIP.pid, $sumCountR,
                  (
                   CASE WHEN NOT EXISTS(SELECT NULL FROM $tablenameIP WHERE $tablename.id = $tablenameIP.pid)
                      THEN 1
                      ELSE 0
                   END 
                  ) AS CountRnotExists
                        FROM $tablenameIP, $tablename, $tablename_categories WHERE 
                        (($tablename.id = $tablenameIP.pid AND $tablename.GalleryID = %d AND $tablenameIP.Rating > 0) OR 
                        ($tablename.id != $tablenameIP.pid AND $tablename.GalleryID = %d))
                         AND 
                      ($tablename.GalleryID = %d  
                      $selectWinnersOnly$selectActiveOnly$selectInactiveOnly AND  
                      $tablename_categories.GalleryID = %d AND 
                      $tablename.Category = $tablename_categories.id AND 
                      ($tablename_categories.GalleryID = %d AND $tablename_categories.Name LIKE %s))
                        GROUP BY $tablename.id) AS CountRtotalSum
                        
                        UNION
                
                SELECT $countFields, $CountRtotalSumCalculated $fieldsToSelectString  from (SELECT DISTINCT $tablename.*, $tablenameIP.pid, $sumCountR,
                  (
                   CASE WHEN NOT EXISTS(SELECT NULL FROM $tablenameIP WHERE $tablename.id = $tablenameIP.pid)
                      THEN 1
                      ELSE 0
                   END 
                  ) AS CountRnotExists
                        FROM $tablenameIP, $tablename, $table_posts WHERE 
                        (($tablename.id = $tablenameIP.pid AND $tablename.GalleryID = %d AND $tablenameIP.Rating > 0) OR 
                        ($tablename.id != $tablenameIP.pid AND $tablename.GalleryID = %d))
                         AND 
                      ( $tablename.GalleryID = %d  
                      $selectWinnersOnly$selectActiveOnly$selectInactiveOnly AND 
                      $tablename.WpUpload = $table_posts.ID AND 
                      ($table_posts.post_content LIKE %s OR $table_posts.post_title LIKE %s OR $table_posts.post_name LIKE %s))
                        GROUP BY $tablename.id) AS CountRtotalSum
                        
                        UNION
                
                SELECT $countFields, $CountRtotalSumCalculated $fieldsToSelectString  from (SELECT DISTINCT $tablename.*, $tablenameIP.pid, $sumCountR,
                  (
                   CASE WHEN NOT EXISTS(SELECT NULL FROM $tablenameIP WHERE $tablename.id = $tablenameIP.pid)
                      THEN 1
                      ELSE 0
                   END 
                  ) AS CountRnotExists
                        FROM $tablenameIP, $tablename, $wpUsers WHERE 
                        (($tablename.id = $tablenameIP.pid AND $tablename.GalleryID = %d AND $tablenameIP.Rating > 0) OR 
                        ($tablename.id != $tablenameIP.pid AND $tablename.GalleryID = %d))
                         AND 
                      ( $tablename.GalleryID = %d  
                      $selectWinnersOnly$selectActiveOnly$selectInactiveOnly AND  
                      $tablename.WpUserId = $wpUsers.ID AND 
                      ($wpUsers.user_login LIKE %s OR $wpUsers.user_nicename LIKE %s OR $wpUsers.user_email LIKE %s OR $wpUsers.display_name LIKE %s))
                        GROUP BY $tablename.id) AS CountRtotalSum                        
                        ) A
                        ";

    $countSearchSQL = $wpdb->get_var($wpdb->prepare($countSearchSQLQuery,[
        $GalleryID,$GalleryID,$GalleryID,$GalleryID,
        '%'.$search.'%','%'.$search.'%','%'.$search.'%','%'.$search.'%',
        $GalleryID,$GalleryID,$GalleryID,$GalleryID,$GalleryID,'%'.$search.'%',
        $GalleryID,$GalleryID,$GalleryID,'%'.$search.'%','%'.$search.'%','%'.$search.'%',
        $GalleryID,$GalleryID,$GalleryID,'%'.$search.'%','%'.$search.'%','%'.$search.'%','%'.$search.'%',
    ]));

    // partial connect with max two tables at same time, otherwise load to long!!!
    $selectSQLQuery = "
                    SELECT * FROM (SELECT $countFields, $CountRtotalSumCalculated $fieldsToSelectString  from (SELECT DISTINCT $tablename.*, $tablenameIP.pid, $sumCountR,
                  (
                   CASE WHEN NOT EXISTS(SELECT NULL FROM $tablenameIP WHERE $tablename.id = $tablenameIP.pid)
                      THEN 1
                      ELSE 0
                   END 
                  ) AS CountRnotExists
                        FROM $tablenameIP, $tablename, $tablenameentries WHERE 
                        (($tablename.id = $tablenameIP.pid AND $tablename.GalleryID = %d AND $tablenameIP.Rating > 0) OR 
                        ($tablename.id != $tablenameIP.pid AND $tablename.GalleryID = %d))
                         AND 
                        (
                        $tablename.GalleryID = %d  
                        $selectWinnersOnly$selectActiveOnly$selectInactiveOnly AND  
                       $tablenameentries.GalleryID = %d AND 
                       $tablename.id = $tablenameentries.pid AND 
                       ($tablenameentries.Short_Text like %s OR $tablenameentries.Long_Text like %s OR $tablename.id like %s OR $tablename.Exif like %s) AND 
                       $tablenameentries.f_input_id >= 1
                         )
                        GROUP BY $tablename.id) AS CountRtotalSum
                        
                        UNION
                
                SELECT $countFields, $CountRtotalSumCalculated $fieldsToSelectString  from (SELECT DISTINCT $tablename.*, $tablenameIP.pid, $sumCountR,
                  (
                   CASE WHEN NOT EXISTS(SELECT NULL FROM $tablenameIP WHERE $tablename.id = $tablenameIP.pid)
                      THEN 1
                      ELSE 0
                   END 
                  ) AS CountRnotExists
                        FROM $tablenameIP, $tablename, $tablename_categories WHERE 
                        (($tablename.id = $tablenameIP.pid AND $tablename.GalleryID = %d AND $tablenameIP.Rating > 0) OR 
                        ($tablename.id != $tablenameIP.pid AND $tablename.GalleryID = %d))
                         AND 
                      ($tablename.GalleryID = %d  
                      $selectWinnersOnly$selectActiveOnly$selectInactiveOnly AND  
                      $tablename_categories.GalleryID = %d AND 
                      $tablename.Category = $tablename_categories.id AND 
                      ($tablename_categories.GalleryID = %d AND $tablename_categories.Name LIKE %s))
                        GROUP BY $tablename.id) AS CountRtotalSum
                        
                        UNION
                
                SELECT $countFields, $CountRtotalSumCalculated $fieldsToSelectString  from (SELECT DISTINCT $tablename.*, $tablenameIP.pid, $sumCountR,
                  (
                   CASE WHEN NOT EXISTS(SELECT NULL FROM $tablenameIP WHERE $tablename.id = $tablenameIP.pid)
                      THEN 1
                      ELSE 0
                   END 
                  ) AS CountRnotExists
                        FROM $tablenameIP, $tablename, $table_posts WHERE 
                        (($tablename.id = $tablenameIP.pid AND $tablename.GalleryID = %d AND $tablenameIP.Rating > 0) OR 
                        ($tablename.id != $tablenameIP.pid AND $tablename.GalleryID = %d))
                         AND 
                      ( $tablename.GalleryID = %d  
                      $selectWinnersOnly$selectActiveOnly$selectInactiveOnly AND 
                      $tablename.WpUpload = $table_posts.ID AND 
                      ($table_posts.post_content LIKE %s OR $table_posts.post_title LIKE %s OR $table_posts.post_name LIKE %s))
                        GROUP BY $tablename.id) AS CountRtotalSum
                        
                        UNION
                
                SELECT $countFields, $CountRtotalSumCalculated $fieldsToSelectString  from (SELECT DISTINCT $tablename.*, $tablenameIP.pid, $sumCountR,
                  (
                   CASE WHEN NOT EXISTS(SELECT NULL FROM $tablenameIP WHERE $tablename.id = $tablenameIP.pid)
                      THEN 1
                      ELSE 0
                   END 
                  ) AS CountRnotExists
                        FROM $tablenameIP, $tablename, $wpUsers WHERE 
                        (($tablename.id = $tablenameIP.pid AND $tablename.GalleryID = %d AND $tablenameIP.Rating > 0) OR 
                        ($tablename.id != $tablenameIP.pid AND $tablename.GalleryID = %d))
                         AND 
                      ( $tablename.GalleryID = %d  
                      $selectWinnersOnly$selectActiveOnly$selectInactiveOnly AND  
                      $tablename.WpUserId = $wpUsers.ID AND 
                      ($wpUsers.user_login LIKE %s OR $wpUsers.user_nicename LIKE %s OR $wpUsers.user_email LIKE %s OR $wpUsers.display_name LIKE %s))
                        GROUP BY $tablename.id) AS CountRtotalSum                        
                        ) A
                        group by id $orderBy
                        ";

    $selectSQL = $wpdb->get_results($wpdb->prepare($selectSQLQuery,[
        $GalleryID,$GalleryID,$GalleryID,$GalleryID,
        '%'.$search.'%','%'.$search.'%','%'.$search.'%','%'.$search.'%',
        $GalleryID,$GalleryID,$GalleryID,$GalleryID,$GalleryID,'%'.$search.'%',
        $GalleryID,$GalleryID,$GalleryID,'%'.$search.'%','%'.$search.'%','%'.$search.'%',
        $GalleryID,$GalleryID,$GalleryID,'%'.$search.'%','%'.$search.'%','%'.$search.'%','%'.$search.'%',
    ]));

    // var_dump("");

    //  }
    /*var_dump('test');
    var_dump($orderBy);

        echo "<pre>";
        print_r($selectSQL);
        echo "</pre>";*/


}



?>