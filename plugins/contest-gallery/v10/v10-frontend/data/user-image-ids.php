<?php

if($is_user_logged_in){
    $wpUserImageIds = $wpdb->get_results( $wpdb->prepare(
        "
							SELECT id
							FROM $tablename
							WHERE GalleryID = %d and WpUserId = %d ORDER BY id DESC
						",
        $galeryID,$WpUserId
    ) );
}


?>
<pre>
    <script data-cg-processing="true">

        var index = <?php echo json_encode($galeryIDuserForJs) ?>;
        cgJsData[index].onlyLoggedInUserImages = true;
        cgJsData[index].wpUserImageIds = [];

    </script>
    </pre>
<?php

if(!empty($wpUserImageIds)){
    if(count($wpUserImageIds)){
        foreach($wpUserImageIds as $row){
            ?>
            <pre>
            <script data-cg-processing="true">

                var index = <?php echo json_encode($galeryIDuserForJs) ?>;
                cgJsData[index].wpUserImageIds.push(<?php echo json_encode(intval($row->id)) ?>);// intval so later indexOf check goes right

            </script>
            </pre>
            <?php
        }
    }
}

