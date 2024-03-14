<?php

$selectWinnersOnly = '';
if (!empty($_POST['cg_show_only_winners'])) {
    $selectWinnersOnly = " AND Winner = 1 ";
}

$selectActiveOnly = '';

if (!empty($_POST['cg_show_only_active'])) {
    $selectActiveOnly = " AND Active = 1 ";
}

$selectInactiveOnly = '';

if (!empty($_POST['cg_show_only_inactive'])) {
    $selectInactiveOnly = " AND Active = 0 ";
}

$customOrder = '';

if ($order=='custom') {
    $selectSQLQuery = "SELECT * FROM $tablename WHERE GalleryID = %d $selectWinnersOnly$selectActiveOnly$selectInactiveOnly ORDER BY PositionNumber ASC, id DESC LIMIT %d, %d";
    $selectSQL = $wpdb->get_results($wpdb->prepare($selectSQLQuery,[$GalleryID,$start,$step]));
}

if ($order == 'date_desc') {
    $selectSQLQuery = "SELECT * FROM $tablename WHERE GalleryID = %d $selectWinnersOnly$selectActiveOnly$selectInactiveOnly ORDER BY id DESC LIMIT %d, %d";
    $selectSQL = $wpdb->get_results($wpdb->prepare($selectSQLQuery,[$GalleryID,$start,$step]));

}

if ($order == 'date_asc') {
    $selectSQLQuery = "SELECT * FROM $tablename WHERE GalleryID = %d $selectWinnersOnly$selectActiveOnly$selectInactiveOnly ORDER BY id ASC LIMIT %d, %d";
    $selectSQL = $wpdb->get_results($wpdb->prepare($selectSQLQuery,[$GalleryID,$start,$step]));
}

if ($order == 'rating_desc') {
    if ($AllowRating>=12 && $AllowRating<=20) {

        $sumCountR = "SUM( ";

        for($iR=1;$iR<=$AllowRating-10;$iR++){
            if($iR==1){
                $sumCountR .= "CASE WHEN $tablenameIP.pid > 0 AND $tablenameIP.Rating = '$iR' AND $tablename.id = $tablenameIP.pid THEN 1 ELSE 0 END";
            }else{
                $sumCountR .= " + CASE WHEN $tablenameIP.pid > 0 AND $tablenameIP.Rating = '$iR' AND $tablename.id = $tablenameIP.pid THEN 1 ELSE 0 END";
            }
        }

        $sumCountR .= " ) AS CountRtotalCount";

        $selectSQLQuery = "SELECT CountRtotalCount, $fieldsToSelectString  from (SELECT DISTINCT $tablename.*, $tablenameIP.pid, $sumCountR,
                  (
                   CASE WHEN NOT EXISTS(SELECT NULL FROM $tablenameIP WHERE $tablename.id = $tablenameIP.pid)
                      THEN 1
                      ELSE 0
                   END 
                  ) AS CountRnotExists
                        FROM $tablenameIP, $tablename WHERE 
                        ($tablename.id = $tablenameIP.pid AND $tablename.GalleryID = %d AND $tablenameIP.Rating > 0 $selectWinnersOnly$selectActiveOnly$selectInactiveOnly) OR 
                        ($tablename.id != $tablenameIP.pid AND $tablename.GalleryID = %d $selectWinnersOnly$selectActiveOnly$selectInactiveOnly)
                        GROUP BY $tablename.id) AS CountRtotalDataCollect 
                        GROUP BY id ORDER BY CountRtotalCount DESC, id DESC LIMIT %d, %d";

        $selectSQL = $wpdb->get_results($wpdb->prepare($selectSQLQuery,[$GalleryID,$GalleryID,$start,$step]));

    }
    if ($AllowRating == 2) {
        $sumCountS = "SUM( CASE WHEN $tablenameIP.pid > 0 AND $tablenameIP.RatingS = '1' AND $tablename.id = $tablenameIP.pid THEN 1 ELSE 0 END) AS CountStotalCount";

        $selectSQLQuery ="SELECT CountStotalCount, $fieldsToSelectString  from (SELECT DISTINCT $tablename.*, $tablenameIP.pid, $sumCountS,
                  (
                   CASE WHEN NOT EXISTS(SELECT NULL FROM $tablenameIP WHERE $tablename.id = $tablenameIP.pid)
                      THEN 1
                      ELSE 0
                   END
                  ) AS CountSnotExists
                        FROM $tablenameIP, $tablename WHERE
                        ($tablename.id = $tablenameIP.pid AND $tablename.GalleryID = %d AND $tablenameIP.RatingS > 0 $selectWinnersOnly$selectActiveOnly$selectInactiveOnly) OR 
                        ($tablename.id != $tablenameIP.pid AND $tablename.GalleryID = %d $selectWinnersOnly$selectActiveOnly$selectInactiveOnly)
                        GROUP BY $tablename.id) AS CountStotalDataCollect
                        GROUP BY id ORDER BY CountStotalCount DESC, id DESC LIMIT %d, %d
                        ";

        $selectSQL = $wpdb->get_results($wpdb->prepare($selectSQLQuery,[$GalleryID,$GalleryID,$start,$step]));

    }
}


if ($order == 'rating_asc') {
    if ($AllowRating>=12 && $AllowRating<=20) {

        $sumCountR = "SUM( ";

        for($iR=1;$iR<=$AllowRating-10;$iR++){
            if($iR==1){
                $sumCountR .= "CASE WHEN $tablenameIP.pid > 0 AND $tablenameIP.Rating = '$iR' AND $tablename.id = $tablenameIP.pid THEN 1 ELSE 0 END";
            }else{
                $sumCountR .= " + CASE WHEN $tablenameIP.pid > 0 AND $tablenameIP.Rating = '$iR' AND $tablename.id = $tablenameIP.pid THEN 1 ELSE 0 END";
            }
        }

        $sumCountR .= " ) AS CountRtotalCount";

        $selectSQLQuery = "SELECT CountRtotalCount, $fieldsToSelectString  from (SELECT DISTINCT $tablename.*, $tablenameIP.pid, $sumCountR,
                  (
                   CASE WHEN NOT EXISTS(SELECT NULL FROM $tablenameIP WHERE $tablename.id = $tablenameIP.pid)
                      THEN 1
                      ELSE 0
                   END 
                  ) AS CountRnotExists
                        FROM $tablenameIP, $tablename WHERE 
                        ($tablename.id = $tablenameIP.pid AND $tablename.GalleryID = %d AND $tablenameIP.Rating > 0 $selectWinnersOnly$selectActiveOnly$selectInactiveOnly) OR 
                        ($tablename.id != $tablenameIP.pid AND $tablename.GalleryID = %d $selectWinnersOnly$selectActiveOnly$selectInactiveOnly)
                        GROUP BY $tablename.id) AS CountRtotalDataCollect 
                        GROUP BY id ORDER BY CountRtotalCount ASC, id ASC LIMIT %d, %d
         ";

        $selectSQL = $wpdb->get_results($wpdb->prepare($selectSQLQuery,[$GalleryID,$GalleryID,$start,$step]));

    }
    if ($AllowRating == 2) {
/*        $selectSQL = $wpdb->get_results("SELECT * FROM $tablename WHERE GalleryID = '$GalleryID' $selectWinnersOnly$selectActiveOnly$selectInactiveOnly ORDER BY CountS ASC, rowid DESC LIMIT $start, $step ");*/

    $sumCountS = "SUM( CASE WHEN $tablenameIP.pid > 0 AND $tablenameIP.RatingS = '1' AND $tablename.id = $tablenameIP.pid THEN 1 ELSE 0 END) AS CountStotalCount";

        $selectSQLQuery = "SELECT CountStotalCount, $fieldsToSelectString  from (SELECT DISTINCT $tablename.*, $tablenameIP.pid, $sumCountS,
              (
               CASE WHEN NOT EXISTS(SELECT NULL FROM $tablenameIP WHERE $tablename.id = $tablenameIP.pid)
                  THEN 1
                  ELSE 0
               END 
              ) AS CountSnotExists
                    FROM $tablenameIP, $tablename WHERE 
                    ($tablename.id = $tablenameIP.pid AND $tablename.GalleryID = %d AND $tablenameIP.RatingS > 0 $selectWinnersOnly$selectActiveOnly$selectInactiveOnly) OR 
                    ($tablename.id != $tablenameIP.pid AND $tablename.GalleryID = %d $selectWinnersOnly$selectActiveOnly$selectInactiveOnly)
                    GROUP BY $tablename.id) AS CountStotalDataCollect 
                    GROUP BY id ORDER BY CountStotalCount ASC, id ASC LIMIT %d, %d";

        $selectSQL = $wpdb->get_results($wpdb->prepare($selectSQLQuery,[$GalleryID,$GalleryID,$start,$step]));

    }
}

if ($order == 'rating_desc_with_manip') {
    if ($AllowRating>=12 && $AllowRating<=20) {

        $sumCountR = "SUM( ";

        for($iR=1;$iR<=$AllowRating-10;$iR++){
            if($iR==1){
                $sumCountR .= "CASE WHEN $tablenameIP.pid > 0 AND $tablenameIP.Rating = '$iR' AND $tablename.id = $tablenameIP.pid THEN 1 ELSE 0 END";
            }else{
                $sumCountR .= " + CASE WHEN $tablenameIP.pid > 0 AND $tablenameIP.Rating = '$iR' AND $tablename.id = $tablenameIP.pid THEN 1 ELSE 0 END";
            }
        }

        $sumCountR .= " ) AS CountRtotalCount, ( ";

        for($iR=1;$iR<=$AllowRating-10;$iR++){
            if($iR==1){
                $sumCountR .= "$tablename.addCountR$iR";
            }else{
                $sumCountR .= " + $tablename.addCountR$iR";
            }
        }

        $sumCountR .= " ) AS CountRtotalCountAdd";

        $selectSQLQuery = "SELECT (CountRtotalCount+CountRtotalCountAdd) AS CountRtotalCountWithManip, $fieldsToSelectString  from (SELECT DISTINCT $tablename.*, $tablenameIP.pid, $sumCountR,
                  (
                   CASE WHEN NOT EXISTS(SELECT NULL FROM $tablenameIP WHERE $tablename.id = $tablenameIP.pid)
                      THEN 1
                      ELSE 0
                   END 
                  ) AS CountRnotExists
                        FROM $tablenameIP, $tablename WHERE 
                        ($tablename.id = $tablenameIP.pid AND $tablename.GalleryID = %d AND $tablenameIP.Rating > 0 $selectWinnersOnly$selectActiveOnly$selectInactiveOnly) OR 
                        ($tablename.id != $tablenameIP.pid AND $tablename.GalleryID = %d $selectWinnersOnly$selectActiveOnly$selectInactiveOnly)
                        GROUP BY $tablename.id) AS CountRtotalDataCollectWithManip
                        GROUP BY id ORDER BY CountRtotalCountWithManip DESC, id DESC LIMIT %d, %d";

        $selectSQL = $wpdb->get_results($wpdb->prepare($selectSQLQuery,[$GalleryID,$GalleryID,$start,$step]));

    }
    if ($AllowRating == 2) {
        $sumCountS = "SUM( CASE WHEN $tablenameIP.pid > 0 AND $tablenameIP.RatingS = '1' AND $tablename.id = $tablenameIP.pid THEN 1 ELSE 0 END) AS CountStotalCount, ($tablename.addCountS*1) AS CountStotalCountAdd";

        $selectSQLQuery = "SELECT (CountStotalCount+CountStotalCountAdd) AS CountStotalCountWithManip, $fieldsToSelectString  from (SELECT DISTINCT $tablename.*, $tablenameIP.pid, $sumCountS,
                  (
                   CASE WHEN NOT EXISTS(SELECT NULL FROM $tablenameIP WHERE $tablename.id = $tablenameIP.pid)
                      THEN 1
                      ELSE 0
                   END 
                  ) AS CountSnotExists
                        FROM $tablenameIP, $tablename WHERE 
                        ($tablename.id = $tablenameIP.pid AND $tablename.GalleryID = %d AND $tablenameIP.RatingS > 0 $selectWinnersOnly$selectActiveOnly$selectInactiveOnly) OR 
                        ($tablename.id != $tablenameIP.pid AND $tablename.GalleryID = %d $selectWinnersOnly$selectActiveOnly$selectInactiveOnly)
                        GROUP BY $tablename.id) AS CountStotalDataCollectWithManip 
                        GROUP BY id ORDER BY CountStotalCountWithManip DESC, id DESC LIMIT %d, %d";

        $selectSQL = $wpdb->get_results($wpdb->prepare($selectSQLQuery,[$GalleryID,$GalleryID,$start,$step]));

    }
}

if ($order == 'rating_asc_with_manip') {
    if ($AllowRating>=12 && $AllowRating<=20) {
        $sumCountR = "SUM( ";

        for($iR=1;$iR<=$AllowRating-10;$iR++){
            if($iR==1){
                $sumCountR .= "CASE WHEN $tablenameIP.pid > 0 AND $tablenameIP.Rating = '$iR' AND $tablename.id = $tablenameIP.pid THEN 1 ELSE 0 END";
            }else{
                $sumCountR .= " + CASE WHEN $tablenameIP.pid > 0 AND $tablenameIP.Rating = '$iR' AND $tablename.id = $tablenameIP.pid THEN 1 ELSE 0 END";
            }
        }

        $sumCountR .= " ) AS CountRtotalCount, ( ";

        for($iR=1;$iR<=$AllowRating-10;$iR++){
            if($iR==1){
                $sumCountR .= "$tablename.addCountR$iR";
            }else{
                $sumCountR .= " + $tablename.addCountR$iR";
            }
        }

        $sumCountR .= " ) AS CountRtotalCountAdd";

        $selectSQLQuery = "SELECT (CountRtotalCount+CountRtotalCountAdd) AS CountRtotalCountWithManip, $fieldsToSelectString  from (SELECT DISTINCT $tablename.*, $tablenameIP.pid, $sumCountR,
                  (
                   CASE WHEN NOT EXISTS(SELECT NULL FROM $tablenameIP WHERE $tablename.id = $tablenameIP.pid)
                      THEN 1
                      ELSE 0
                   END 
                  ) AS CountRnotExists
                        FROM $tablenameIP, $tablename WHERE 
                        ($tablename.id = $tablenameIP.pid AND $tablename.GalleryID = %d AND $tablenameIP.Rating > 0 $selectWinnersOnly$selectActiveOnly$selectInactiveOnly) OR 
                        ($tablename.id != $tablenameIP.pid AND $tablename.GalleryID = %d $selectWinnersOnly$selectActiveOnly$selectInactiveOnly)
                        GROUP BY $tablename.id) AS CountRtotalDataCollectWithManip
                        GROUP BY id ORDER BY CountRtotalCountWithManip ASC, id ASC LIMIT %d, %d";

        $selectSQL = $wpdb->get_results($wpdb->prepare($selectSQLQuery,[$GalleryID,$GalleryID,$start,$step]));

    }
    if ($AllowRating == 2) {
        $sumCountS = "SUM( CASE WHEN $tablenameIP.pid > 0 AND $tablenameIP.RatingS = '1' AND $tablename.id = $tablenameIP.pid THEN 1 ELSE 0 END) AS CountStotalCount, ($tablename.addCountS*1) AS CountStotalCountAdd";

        $selectSQLQuery = "SELECT (CountStotalCount+CountStotalCountAdd) AS CountStotalCountWithManip, $fieldsToSelectString  from (SELECT DISTINCT $tablename.*, $tablenameIP.pid, $sumCountS,
                  (
                   CASE WHEN NOT EXISTS(SELECT NULL FROM $tablenameIP WHERE $tablename.id = $tablenameIP.pid)
                      THEN 1
                      ELSE 0
                   END 
                  ) AS CountSnotExists
                        FROM $tablenameIP, $tablename WHERE 
                        ($tablename.id = $tablenameIP.pid AND $tablename.GalleryID = %d AND $tablenameIP.RatingS > 0 $selectWinnersOnly$selectActiveOnly$selectInactiveOnly) OR 
                        ($tablename.id != $tablenameIP.pid AND $tablename.GalleryID = %d $selectWinnersOnly$selectActiveOnly$selectInactiveOnly)
                        GROUP BY $tablename.id) AS CountStotalDataCollectWithManip 
                        GROUP BY id ORDER BY CountStotalCountWithManip ASC, id ASC LIMIT %d, %d";

        $selectSQL = $wpdb->get_results($wpdb->prepare($selectSQLQuery,[$GalleryID,$GalleryID,$start,$step]));

    }
}

if ($order == 'comments_desc') {
    $selectSQLQuery = "SELECT * FROM $tablename WHERE GalleryID = %d $selectWinnersOnly$selectActiveOnly$selectInactiveOnly ORDER BY CountC DESC, id DESC LIMIT %d, %d ";
    $selectSQL = $wpdb->get_results($wpdb->prepare($selectSQLQuery,[$GalleryID,$start,$step]));
}

if ($order == 'comments_asc') {
    $selectSQLQuery = "SELECT * FROM $tablename WHERE GalleryID = %d $selectWinnersOnly$selectActiveOnly$selectInactiveOnly ORDER BY CountC ASC, id DESC LIMIT %d, %d";
    $selectSQL = $wpdb->get_results($wpdb->prepare($selectSQLQuery,[$GalleryID,$start,$step]));
}

?>