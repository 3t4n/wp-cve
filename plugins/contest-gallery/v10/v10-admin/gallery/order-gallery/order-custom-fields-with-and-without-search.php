<?php
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

// partial connect with max two tables at same time, otherwise load to long!!!
$searchStringUnion = "
                    SELECT * FROM (   
                           SELECT DISTINCT $tablename.id
                           FROM $tablename,
                            $tablenameentries
                           WHERE $tablename.GalleryID = %d 
                           $selectWinnersOnly$selectActiveOnly$selectInactiveOnly   AND                                       
                           $tablenameentries.GalleryID = %d 
                           AND $tablename.id = $tablenameentries.pid
                           AND 
                           ($tablenameentries.Short_Text like %s OR $tablenameentries.Long_Text like %s OR $tablename.id like %s OR $tablename.Exif like %s)
                           UNION
                             SELECT 
                            DISTINCT $tablename.id
                            FROM 
                              $tablename,
                              $tablename_categories
                            WHERE 
                              $tablename.GalleryID = %d  
                              $selectWinnersOnly$selectActiveOnly$selectInactiveOnly AND                                         
                              $tablename_categories.GalleryID = %d AND 
                              $tablename.Category = $tablename_categories.id AND 
                              ($tablename_categories.GalleryID = %d AND $tablename_categories.Name LIKE %s)
                              UNION
                             SELECT 
                            DISTINCT $tablename.id
                            FROM 
                              $tablename,
                              $table_posts
                            WHERE 
                              $tablename.GalleryID = %d  
                              $selectWinnersOnly$selectActiveOnly$selectInactiveOnly AND  
                              $tablename.WpUpload = $table_posts.ID AND 
                              ($table_posts.post_content LIKE %s OR $table_posts.post_title LIKE %s OR $table_posts.post_name LIKE %s)
                              UNION
                             SELECT 
                            DISTINCT $tablename.id
                            FROM 
                              $tablename,
                              $wpUsers
                            WHERE 
                              $tablename.GalleryID = %d  
                              $selectWinnersOnly$selectActiveOnly$selectInactiveOnly AND  
                              $tablename.WpUserId = $wpUsers.ID AND 
                              ($wpUsers.user_login LIKE %s OR $wpUsers.user_nicename LIKE %s OR $wpUsers.user_email LIKE %s OR $wpUsers.display_name LIKE %s)
                              UNION
                             SELECT 
                            DISTINCT $tablename.id
                            FROM 
                              $tablename
                            WHERE 
                              $tablename.GalleryID = %d  
                              $selectWinnersOnly$selectActiveOnly$selectInactiveOnly AND  
                              $tablename.id LIKE %s  
    ";

$searchStringUnionPrepareArray = [
    $GalleryID,$GalleryID,'%'.$search.'%','%'.$search.'%','%'.$search.'%','%'.$search.'%',
    $GalleryID,$GalleryID,$GalleryID,'%'.$search.'%',
    $GalleryID,'%'.$search.'%','%'.$search.'%','%'.$search.'%',
    $GalleryID,'%'.$search.'%','%'.$search.'%','%'.$search.'%','%'.$search.'%',
    $GalleryID,'%'.$search.'%'
];

if (strpos($order, 'cg_input_') !== false or strpos($order, 'cg_textarea_') !== false or strpos($order, 'cg_select_') !== false or strpos($order, 'cg_date_') !== false or strpos($order, 'cg_email_unregistered_users') !== false) {

    $textType = 'Short_Text';

    $f_input_id = explode('_for_id_',$order);
    $f_input_id = $f_input_id[1];
    $f_input_id = explode('_id_',$f_input_id);
    $f_input_id = $f_input_id[0];
    $f_input_id = absint($f_input_id);

    if (strpos($order, 'cg_textarea_') !== false) {
        $textType = 'Long_Text';
    }

    if (strpos($order, 'cg_date_') !== false) {
        $textType = 'InputDate';
    }

    $orderDirection = explode('_',$order);
    if($orderDirection[count($orderDirection)-1]=='asc'){
        $orderDirection = 'ASC';
    }else{
        $orderDirection = 'DESC';
    }

    if($search==''){

        $selectSQLQuery = " SELECT * FROM (   
                                               SELECT $tablenameentries.$textType, $tablename.*
                                               FROM $tablename,
                                                   $tablenameentries
                                               WHERE $tablename.GalleryID = %d 
                                               AND $tablenameentries.GalleryID = %d 
                                               AND $tablename.id = $tablenameentries.pid
                                               AND $tablenameentries.f_input_id = %d 
                                               $selectWinnersOnly$selectActiveOnly$selectInactiveOnly 
                                            UNION
                                                SELECT
                                                  (
                                                      CASE
                                                          WHEN NOT EXISTS(SELECT NULL
                                                                          FROM $tablenameentries
                                                                          WHERE $tablename.id = $tablenameentries.pid)
                                                              THEN ''
                                                          ELSE $tablenameentries.$textType
                                                          END
                                                      ) AS $textType, $tablename.*
                                               FROM $tablename,
                                                   $tablenameentries
                                               WHERE $tablename.id != $tablenameentries.pid
                                                   AND $tablename.GalleryID = %d 
                                                   $selectWinnersOnly$selectActiveOnly$selectInactiveOnly                                     
                                             ) A
                                        group by id
                                        order by $textType $orderDirection, rowid $orderDirection
                                        LIMIT %d, %d ";

        $selectSQL = $wpdb->get_results($wpdb->prepare($selectSQLQuery,[
            $GalleryID,$GalleryID,$f_input_id,$GalleryID,$start,$step
        ]));

    }else{

         $countSearchSQLQuery = "SELECT COUNT(*) AS NumberOfRows
                                            FROM
                                                $tablename
                                            WHERE
                                                $tablename.id IN
                                         ($searchStringUnion) A
                                                group by id
                                            ";

        $countSearchSQL = $wpdb->get_var($wpdb->prepare($countSearchSQLQuery,$searchStringUnionPrepareArray));

        $selectSQLQuery = "SELECT DISTINCT $tablenameentries.$textType, $tablename.*
                                            FROM
                                                $tablename
                                            LEFT JOIN $tablenameentries
                                            ON $tablename.id = $tablenameentries.pid AND $tablenameentries.f_input_id = %d 
                                            WHERE
                                                $tablename.id IN 
                                         (
                                         $searchStringUnion
                                         ) A
                                        group by id) group by id
                                        ORDER BY $textType $orderDirection, rowid $orderDirection
                                        LIMIT %d, %d 
                                        ";

        array_unshift($searchStringUnionPrepareArray,$f_input_id);
        $searchStringUnionPrepareArray[] = $start;
        $searchStringUnionPrepareArray[] = $step;
        $selectSQL = $wpdb->get_results($wpdb->prepare($selectSQLQuery,$searchStringUnionPrepareArray));
        echo $wpdb->last_query;

    }

}

if (strpos($order, 'cg_categories_') !== false) {

    $textType = 'Name';

    $f_input_id = explode('_for_id_',$order);
    $f_input_id = $f_input_id[1];
    $f_input_id = explode('_id_',$f_input_id);
    $f_input_id = $f_input_id[0];

    $orderDirection = explode('_',$order);
    if($orderDirection[count($orderDirection)-1]=='asc'){
        $orderDirection = 'ASC';
    }else{
        $orderDirection = 'DESC';
    }

    if($search==''){

        $selectSQLQuery = "SELECT *
                                        FROM (      
                                           SELECT DISTINCT (CASE
                                            WHEN NOT EXISTS(SELECT NULL
                                           FROM $tablename_categories
                                           WHERE $tablename.Category = $tablename_categories.id) THEN ''
                                           ELSE $tablename_categories.Name END) AS Name, $tablename.*
                                          FROM $tablename,
                                               $tablename_categories
                                          WHERE ($tablename.GalleryID = %d 
                                            AND $tablename_categories.GalleryID = %d
                                            $selectWinnersOnly$selectActiveOnly$selectInactiveOnly 
                                            AND $tablename.Category = $tablename_categories.id)
                                            OR
                                                  ($tablename.GalleryID = %d AND $tablename.Category = 0
                                                  $selectWinnersOnly$selectActiveOnly$selectInactiveOnly
                                                  )  
                                             ) A
                                        group by id
                                        order by Name $orderDirection, rowid $orderDirection
                                        LIMIT %d, %d
                                        ";

        $selectSQL = $wpdb->get_results($wpdb->prepare($selectSQLQuery,[
            $GalleryID,$GalleryID,$GalleryID,$start,$step
        ]));

    }else{

        $countSearchSQLQuery = "SELECT COUNT(*) AS NumberOfRows
                                            FROM
                                                $tablename
                                            WHERE
                                                $tablename.id IN
                                         ($searchStringUnion) A
                                                group by id
                                            ";

        $countSearchSQL = $wpdb->get_var($wpdb->prepare($countSearchSQLQuery, $searchStringUnionPrepareArray));

        $selectSQLQuery = "SELECT DISTINCT $tablename_categories.Name, $tablename.*
                                            FROM
                                                $tablename
                                            LEFT JOIN $tablename_categories
                                            ON $tablename.Category = $tablename_categories.id 
                                            WHERE
                                                $tablename.id IN 
                                         (
                                         $searchStringUnion
                                             ) A
                                        group by id) group by id
                                        order by Name $orderDirection, rowid $orderDirection
                                        LIMIT %d, %d
                                        ";

        $searchStringUnionPrepareArray[] = $start;
        $searchStringUnionPrepareArray[] = $step;

        $selectSQL = $wpdb->get_results($wpdb->prepare($selectSQLQuery,$searchStringUnionPrepareArray));

    }

}


if (strpos($order, 'cg_email_registered_users') !== false) {

    $textType = 'Name';

    $f_input_id = explode('_for_id_',$order);
    $f_input_id = $f_input_id[1];
    $f_input_id = explode('_id_',$f_input_id);
    $f_input_id = $f_input_id[0];

    $orderDirection = explode('_',$order);
    if($orderDirection[count($orderDirection)-1]=='asc'){
        $orderDirection = 'ASC';
    }else{
        $orderDirection = 'DESC';
    }

    if($search==''){

        $selectSQLQuery = "
                                        SELECT *
                                        FROM (      
                                           SELECT DISTINCT (CASE
                                            WHEN NOT EXISTS(SELECT NULL
                                           FROM $wpUsers
                                           WHERE $tablename.WpUserId = $wpUsers.ID) THEN ''
                                           ELSE $wpUsers.user_email END) AS user_email, $tablename.*
                                          FROM $tablename,
                                               $wpUsers
                                          WHERE ($tablename.GalleryID = %d 
                                            AND $tablename.WpUserId = $wpUsers.ID
                                            $selectWinnersOnly$selectActiveOnly$selectInactiveOnly
                                            )
                                            OR
                                                  ($tablename.GalleryID = %d AND $tablename.WpUserId = 0 
                                                  $selectWinnersOnly$selectActiveOnly$selectInactiveOnly)  
                                             ) A
                                        group by id
                                        order by user_email $orderDirection, rowid $orderDirection
                                        LIMIT %d, %d
                                        ";

        $selectSQL = $wpdb->get_results($wpdb->prepare($selectSQLQuery,[
            $GalleryID,$GalleryID,$start,$step
        ]));

    }else{

        $countSearchSQLQuery = "SELECT COUNT(*) AS NumberOfRows
                                            FROM
                                                $tablename
                                            WHERE
                                                $tablename.id IN
                                         ($searchStringUnion) A
                                                group by id)
                                            ";

        $countSearchSQL = $wpdb->get_var($wpdb->prepare($countSearchSQLQuery,$searchStringUnionPrepareArray));

        $selectSQLQuery = "
                                        SELECT DISTINCT $wpUsers.user_email, $tablename.*
                                            FROM
                                                $tablename
                                            LEFT JOIN $wpUsers
                                            ON $tablename.WpUserId = $wpUsers.ID 
                                            WHERE
                                                $tablename.id IN 
                                         ($searchStringUnion) A
                                        group by id) group by id
                                        ORDER BY user_email $orderDirection, rowid $orderDirection
                                        LIMIT %d, %d";

        $searchStringUnionPrepareArray[] = $start;
        $searchStringUnionPrepareArray[] = $step;

        $selectSQL = $wpdb->get_results($wpdb->prepare($selectSQLQuery,$searchStringUnionPrepareArray));


    }

}


if (strpos($order, 'wp_username') !== false) {

    $textType = 'Name';

    $orderDirection = explode('cg_for_id_wp_username_',$order);
    if($orderDirection[1]=='asc'){
        $orderDirection = 'ASC';
    }else{
        $orderDirection = 'DESC';
    }

    if($search==''){

        $selectSQLQuery = "
                                        SELECT *
                                        FROM (      
                                           SELECT DISTINCT (CASE
                                            WHEN NOT EXISTS(SELECT NULL
                                           FROM $wpUsers
                                           WHERE $tablename.WpUserId = $wpUsers.ID) THEN ''
                                           ELSE $wpUsers.user_login END) AS user_login, $tablename.*
                                          FROM $tablename,
                                               $wpUsers
                                          WHERE ($tablename.GalleryID = %d
                                            AND $tablename.WpUserId = $wpUsers.ID
                                            $selectWinnersOnly$selectActiveOnly$selectInactiveOnly
                                            )
                                            OR
                                                  ($tablename.GalleryID = %d AND $tablename.WpUserId = 0 
                                                  $selectWinnersOnly$selectActiveOnly$selectInactiveOnly)  
                                             ) A
                                        group by id
                                        order by user_login $orderDirection, rowid $orderDirection 
                                        LIMIT %d, %d
                                        ";

        $selectSQL = $wpdb->get_results($wpdb->prepare($selectSQLQuery,[
            $GalleryID,$GalleryID,$start,$step
        ]));

    }else{

        $countSearchSQLQuery = "SELECT COUNT(*) AS NumberOfRows
                                            FROM
                                                $tablename
                                            WHERE
                                                $tablename.id IN
                                         ($searchStringUnion) A
                                                group by id
                                            ";

        $countSearchSQL = $wpdb->get_var($wpdb->prepare($countSearchSQLQuery,$searchStringUnionPrepareArray));

        $selectSQLQuery = "SELECT DISTINCT $wpUsers.user_login, $tablename.*
                                            FROM
                                                $tablename
                                            LEFT JOIN $wpUsers
                                            ON $tablename.WpUserId = $wpUsers.ID 
                                            WHERE
                                                $tablename.id IN 
                                         ($searchStringUnion) A
                                        group by id) group by id
                                        ORDER BY user_login $orderDirection, rowid $orderDirection 
                                        LIMIT $start, $step";

        $searchStringUnionPrepareArray[] = $start;
        $searchStringUnionPrepareArray[] = $step;

        $selectSQL = $wpdb->get_results($wpdb->prepare($selectSQLQuery,$searchStringUnionPrepareArray));

    }

}


?>