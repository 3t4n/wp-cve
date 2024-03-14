<?php




$sumCountRaddManipTotalSum = ',';
$sumCountRaddManipTotalCount = '';
$CountRtotalAverageCalculated = 'CountRtotalSum/CountRtotalCount as CountRtotalAverageCalculated';
$countFields = 'CountRtotalCount, CountRtotalSum ';

if($order=='rating_desc_average_with_manip' || $order=='rating_asc_average_with_manip'){

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

    $sumCountRaddManipTotalSum = ", ($tablename.addCountR1*1+$tablename.addCountR2*2+$tablename.addCountR3*3+$tablename.addCountR4*4+$tablename.addCountR5*5) AS CountRtotalSumAdd,";

    /* $sumCountRaddManipTotalCount = ", SUM(
       CASE WHEN $tablenameIP.pid > 0 AND $tablenameIP.Rating = '1' AND $tablename.id = $tablenameIP.pid AND $tablename.addCountR1 > 0 THEN $tablename.addCountR1 ELSE 0 END +
       CASE WHEN $tablenameIP.pid > 0 AND $tablenameIP.Rating = '2' AND $tablename.id = $tablenameIP.pid AND $tablename.addCountR2 > 0 THEN $tablename.addCountR2 ELSE 0 END +
       CASE WHEN $tablenameIP.pid > 0 AND $tablenameIP.Rating = '3' AND $tablename.id = $tablenameIP.pid AND $tablename.addCountR3 > 0 THEN $tablename.addCountR3 ELSE 0 END +
       CASE WHEN $tablenameIP.pid > 0 AND $tablenameIP.Rating = '4' AND $tablename.id = $tablenameIP.pid AND $tablename.addCountR4 > 0 THEN $tablename.addCountR4 ELSE 0 END +
       CASE WHEN $tablenameIP.pid > 0 AND $tablenameIP.Rating = '5' AND $tablename.id = $tablenameIP.pid AND $tablename.addCountR5 > 0 THEN $tablename.addCountR5 ELSE 0 END
       ) AS CountRtotalCountAdd";*/

    $sumCountRaddManipTotalCount = ", ($tablename.addCountR1+$tablename.addCountR2+$tablename.addCountR3+$tablename.addCountR4+$tablename.addCountR5) AS CountRtotalCountAdd";

    $CountRtotalAverageCalculated = '(CountRtotalCount+CountRtotalCountAdd) as CountRtotalCountSum, (CountRtotalSum+CountRtotalSumAdd)/(CountRtotalCount+CountRtotalCountAdd) as CountRtotalAverageCalculated';

    $countFields = 'CountRtotalCount, CountRtotalCountAdd, CountRtotalSum, CountRtotalSumAdd ';

}

// var_dump($sumCountRaddManipTotalSum);

$sumCountR = "SUM(
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
                  ) AS CountRtotalCount$sumCountRaddManipTotalCount";

if($order=='rating_desc_average'){
    $orderBy = "ORDER BY CountRtotalAverageCalculated DESC, CountRtotalCount DESC, rowid DESC LIMIT $start, $step";
}

if($order=='rating_desc_average_with_manip'){
    $orderBy = "ORDER BY CountRtotalAverageCalculated DESC, CountRtotalCountSum DESC, rowid DESC LIMIT $start, $step";
}

if($order=='rating_asc_average'){
    $orderBy = "ORDER BY CountRtotalAverageCalculated ASC, CountRtotalCount ASC, rowid ASC LIMIT $start, $step";
}
if($order=='rating_asc_average_with_manip'){
    $orderBy = "ORDER BY CountRtotalAverageCalculated ASC, CountRtotalCountSum ASC, rowid ASC LIMIT $start, $step";
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

    $selectSQL = $wpdb->get_results( "SELECT $countFields, $CountRtotalAverageCalculated, $fieldsToSelectString  from (SELECT DISTINCT $tablename.*, $tablenameIP.pid, $sumCountR,
                  (
                   CASE WHEN NOT EXISTS(SELECT NULL FROM $tablenameIP WHERE $tablename.id = $tablenameIP.pid)
                      THEN 1
                      ELSE 0
                   END 
                  ) AS CountRnotExists
                        FROM $tablenameIP, $tablename WHERE 
                        ($tablename.id = $tablenameIP.pid AND $tablename.GalleryID = $GalleryID AND $tablenameIP.Rating > 0 $selectWinnersOnly$selectActiveOnly$selectInactiveOnly) OR 
                        ($tablename.id != $tablenameIP.pid AND $tablename.GalleryID = $GalleryID $selectWinnersOnly$selectActiveOnly$selectInactiveOnly)
                        GROUP BY $tablename.id) AS CountRtotalAverage 
                        GROUP BY id $orderBy
                        " );

    /*var_dump("SELECT $countFields, $CountRtotalAverageCalculated, $fieldsToSelectString  from (SELECT DISTINCT $tablename.*, $tablenameIP.pid, $sumCountR,
                        (
                   CASE WHEN NOT EXISTS(SELECT NULL FROM $tablenameIP WHERE $tablename.id = $tablenameIP.pid)
                      THEN 1
                      ELSE 0
                   END
                  ) AS CountRnotExists
                        FROM $tablenameIP, $tablename WHERE
                        ($tablename.id = $tablenameIP.pid AND $tablename.GalleryID = $GalleryID AND $tablenameIP.Rating > 0 $selectWinnersOnly$selectActiveOnly$selectInactiveOnly) OR
                        ($tablename.id != $tablenameIP.pid AND $tablename.GalleryID = $GalleryID $selectWinnersOnly$selectActiveOnly$selectInactiveOnly)
                        GROUP BY $tablename.id) AS CountRtotalAverage
                        GROUP BY id $orderBy
                        ");*/

    /*

                    WHERE
                                                      $tablename.GalleryID = $GalleryID AND
                                                      $selectWinnersOnly
                                                      $tablenameentries.GalleryID = $GalleryID AND
                                                      $tablename.id = $tablenameentries.pid AND
                                                      ($tablenameentries.Short_Text like '%$search%' OR $tablenameentries.Long_Text like '%$search%' OR $tablename.id like '%$search%' OR $tablename.Exif like '%$search%') AND
                                                      $tablenameentries.f_input_id >= 1

                                                      */

}else{


    //if(!empty($_POST['rating_desc_average']) OR !empty($_POST['rating_asc_average'])){

    // partial connect with max two tables at same time, otherwise load to long!!!
    $countSearchSQL = $wpdb->get_var( "
                    SELECT COUNT(*) AS NumberOfRows FROM (SELECT $countFields, $CountRtotalAverageCalculated, $fieldsToSelectString  from (SELECT DISTINCT $tablename.*, $tablenameIP.pid, $sumCountR,
                  (
                   CASE WHEN NOT EXISTS(SELECT NULL FROM $tablenameIP WHERE $tablename.id = $tablenameIP.pid)
                      THEN 1
                      ELSE 0
                   END 
                  ) AS CountRnotExists
                        FROM $tablenameIP, $tablename, $tablenameentries WHERE 
                        (($tablename.id = $tablenameIP.pid AND $tablename.GalleryID = $GalleryID AND $tablenameIP.Rating > 0) OR 
                        ($tablename.id != $tablenameIP.pid AND $tablename.GalleryID = $GalleryID))
                         AND 
                        (
                        $tablename.GalleryID = $GalleryID  
                        $selectWinnersOnly$selectActiveOnly$selectInactiveOnly AND 
                       $tablenameentries.GalleryID = $GalleryID AND 
                       $tablename.id = $tablenameentries.pid AND 
                       ($tablenameentries.Short_Text like '%$search%' OR $tablenameentries.Long_Text like '%$search%' OR $tablename.id like '%$search%' OR $tablename.Exif like '%$search%') AND 
                       $tablenameentries.f_input_id >= 1
                         )
                        GROUP BY $tablename.id) AS CountRtotalAverage
                        
                        UNION
                
                SELECT $countFields, $CountRtotalAverageCalculated, $fieldsToSelectString  from (SELECT DISTINCT $tablename.*, $tablenameIP.pid, $sumCountR,
                  (
                   CASE WHEN NOT EXISTS(SELECT NULL FROM $tablenameIP WHERE $tablename.id = $tablenameIP.pid)
                      THEN 1
                      ELSE 0
                   END 
                  ) AS CountRnotExists
                        FROM $tablenameIP, $tablename, $tablename_categories WHERE 
                        (($tablename.id = $tablenameIP.pid AND $tablename.GalleryID = $GalleryID AND $tablenameIP.Rating > 0) OR 
                        ($tablename.id != $tablenameIP.pid AND $tablename.GalleryID = $GalleryID))
                         AND 
                      ($tablename.GalleryID = $GalleryID  
                      $selectWinnersOnly$selectActiveOnly$selectInactiveOnly AND  
                      $tablename_categories.GalleryID = $GalleryID AND 
                      $tablename.Category = $tablename_categories.id AND 
                      ($tablename_categories.GalleryID = $GalleryID AND $tablename_categories.Name LIKE '%$search%'))
                        GROUP BY $tablename.id) AS CountRtotalAverage
                        
                        UNION
                
                SELECT $countFields, $CountRtotalAverageCalculated, $fieldsToSelectString  from (SELECT DISTINCT $tablename.*, $tablenameIP.pid, $sumCountR,
                  (
                   CASE WHEN NOT EXISTS(SELECT NULL FROM $tablenameIP WHERE $tablename.id = $tablenameIP.pid)
                      THEN 1
                      ELSE 0
                   END 
                  ) AS CountRnotExists
                        FROM $tablenameIP, $tablename, $table_posts WHERE 
                        (($tablename.id = $tablenameIP.pid AND $tablename.GalleryID = $GalleryID AND $tablenameIP.Rating > 0) OR 
                        ($tablename.id != $tablenameIP.pid AND $tablename.GalleryID = $GalleryID))
                         AND 
                      ( $tablename.GalleryID = $GalleryID  
                      $selectWinnersOnly$selectActiveOnly$selectInactiveOnly AND 
                      $tablename.WpUpload = $table_posts.ID AND 
                      ($table_posts.post_content LIKE '%$search%' OR $table_posts.post_title LIKE '%$search%' OR $table_posts.post_name LIKE '%$search%'))
                        GROUP BY $tablename.id) AS CountRtotalAverage
                        
                        UNION
                
                SELECT $countFields, $CountRtotalAverageCalculated, $fieldsToSelectString  from (SELECT DISTINCT $tablename.*, $tablenameIP.pid, $sumCountR,
                  (
                   CASE WHEN NOT EXISTS(SELECT NULL FROM $tablenameIP WHERE $tablename.id = $tablenameIP.pid)
                      THEN 1
                      ELSE 0
                   END 
                  ) AS CountRnotExists
                        FROM $tablenameIP, $tablename, $wpUsers WHERE 
                        (($tablename.id = $tablenameIP.pid AND $tablename.GalleryID = $GalleryID AND $tablenameIP.Rating > 0) OR 
                        ($tablename.id != $tablenameIP.pid AND $tablename.GalleryID = $GalleryID))
                         AND 
                      ( $tablename.GalleryID = $GalleryID  
                      $selectWinnersOnly$selectActiveOnly$selectInactiveOnly AND  
                      $tablename.WpUserId = $wpUsers.ID AND 
                      ($wpUsers.user_login LIKE '%$search%' OR $wpUsers.user_nicename LIKE '%$search%' OR $wpUsers.user_email LIKE '%$search%' OR $wpUsers.display_name LIKE '%$search%'))
                        GROUP BY $tablename.id) AS CountRtotalAverage                        
                        ) A
                        " );

    // partial connect with max two tables at same time, otherwise load to long!!!
    $selectSQL = $wpdb->get_results( "
                    SELECT * FROM (SELECT $countFields, $CountRtotalAverageCalculated, $fieldsToSelectString  from (SELECT DISTINCT $tablename.*, $tablenameIP.pid, $sumCountR,
                  (
                   CASE WHEN NOT EXISTS(SELECT NULL FROM $tablenameIP WHERE $tablename.id = $tablenameIP.pid)
                      THEN 1
                      ELSE 0
                   END 
                  ) AS CountRnotExists
                        FROM $tablenameIP, $tablename, $tablenameentries WHERE 
                        (($tablename.id = $tablenameIP.pid AND $tablename.GalleryID = $GalleryID AND $tablenameIP.Rating > 0) OR 
                        ($tablename.id != $tablenameIP.pid AND $tablename.GalleryID = $GalleryID))
                         AND 
                        (
                        $tablename.GalleryID = $GalleryID  
                        $selectWinnersOnly$selectActiveOnly$selectInactiveOnly AND  
                       $tablenameentries.GalleryID = $GalleryID AND 
                       $tablename.id = $tablenameentries.pid AND 
                       ($tablenameentries.Short_Text like '%$search%' OR $tablenameentries.Long_Text like '%$search%' OR $tablename.id like '%$search%' OR $tablename.Exif like '%$search%') AND 
                       $tablenameentries.f_input_id >= 1
                         )
                        GROUP BY $tablename.id) AS CountRtotalAverage
                        
                        UNION
                
                SELECT $countFields, $CountRtotalAverageCalculated, $fieldsToSelectString  from (SELECT DISTINCT $tablename.*, $tablenameIP.pid, $sumCountR,
                  (
                   CASE WHEN NOT EXISTS(SELECT NULL FROM $tablenameIP WHERE $tablename.id = $tablenameIP.pid)
                      THEN 1
                      ELSE 0
                   END 
                  ) AS CountRnotExists
                        FROM $tablenameIP, $tablename, $tablename_categories WHERE 
                        (($tablename.id = $tablenameIP.pid AND $tablename.GalleryID = $GalleryID AND $tablenameIP.Rating > 0) OR 
                        ($tablename.id != $tablenameIP.pid AND $tablename.GalleryID = $GalleryID))
                         AND 
                      ($tablename.GalleryID = $GalleryID  
                      $selectWinnersOnly$selectActiveOnly$selectInactiveOnly AND  
                      $tablename_categories.GalleryID = $GalleryID AND 
                      $tablename.Category = $tablename_categories.id AND 
                      ($tablename_categories.GalleryID = $GalleryID AND $tablename_categories.Name LIKE '%$search%'))
                        GROUP BY $tablename.id) AS CountRtotalAverage
                        
                        UNION
                
                SELECT $countFields, $CountRtotalAverageCalculated, $fieldsToSelectString  from (SELECT DISTINCT $tablename.*, $tablenameIP.pid, $sumCountR,
                  (
                   CASE WHEN NOT EXISTS(SELECT NULL FROM $tablenameIP WHERE $tablename.id = $tablenameIP.pid)
                      THEN 1
                      ELSE 0
                   END 
                  ) AS CountRnotExists
                        FROM $tablenameIP, $tablename, $table_posts WHERE 
                        (($tablename.id = $tablenameIP.pid AND $tablename.GalleryID = $GalleryID AND $tablenameIP.Rating > 0) OR 
                        ($tablename.id != $tablenameIP.pid AND $tablename.GalleryID = $GalleryID))
                         AND 
                      ( $tablename.GalleryID = $GalleryID  
                      $selectWinnersOnly$selectActiveOnly$selectInactiveOnly AND 
                      $tablename.WpUpload = $table_posts.ID AND 
                      ($table_posts.post_content LIKE '%$search%' OR $table_posts.post_title LIKE '%$search%' OR $table_posts.post_name LIKE '%$search%'))
                        GROUP BY $tablename.id) AS CountRtotalAverage
                        
                        UNION
                
                SELECT $countFields, $CountRtotalAverageCalculated, $fieldsToSelectString  from (SELECT DISTINCT $tablename.*, $tablenameIP.pid, $sumCountR,
                  (
                   CASE WHEN NOT EXISTS(SELECT NULL FROM $tablenameIP WHERE $tablename.id = $tablenameIP.pid)
                      THEN 1
                      ELSE 0
                   END 
                  ) AS CountRnotExists
                        FROM $tablenameIP, $tablename, $wpUsers WHERE 
                        (($tablename.id = $tablenameIP.pid AND $tablename.GalleryID = $GalleryID AND $tablenameIP.Rating > 0) OR 
                        ($tablename.id != $tablenameIP.pid AND $tablename.GalleryID = $GalleryID))
                         AND 
                      ( $tablename.GalleryID = $GalleryID  
                      $selectWinnersOnly$selectActiveOnly$selectInactiveOnly AND  
                      $tablename.WpUserId = $wpUsers.ID AND 
                      ($wpUsers.user_login LIKE '%$search%' OR $wpUsers.user_nicename LIKE '%$search%' OR $wpUsers.user_email LIKE '%$search%' OR $wpUsers.display_name LIKE '%$search%'))
                        GROUP BY $tablename.id) AS CountRtotalAverage                        
                        ) A
                        group by id $orderBy
                        " );

    // var_dump("");

    //  }



}



?>