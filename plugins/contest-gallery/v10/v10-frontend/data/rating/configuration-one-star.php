<?php

?>
<pre>
    <script  data-cg-processing="true">

        var index = <?php echo json_encode($galeryIDuserForJs) ?>;
        cgJsData[index].cgJsCountSuser = {};
        cgJsData[index].lastVotedUserImageId = 0;

    </script>
    </pre>
<?php

if(empty($isOnlyGalleryNoVoting) && empty($isOnlyGalleryWinner)) {
    // registered users check
    // if(($options['general']['ShowOnlyUsersVotes']==1 or $options['general']['HideUntilVote']==1 or $options['pro']['MinusVote']==1) and $options['general']['CheckLogin']==1){ old logic before 10.07.2022
    if($options['general']['CheckLogin']==1){
        if(is_user_logged_in()){

            $countSuserId = $wpdb->get_results( $wpdb->prepare(
                "
                            SELECT pid
                            FROM $tablenameIP
                            WHERE GalleryID = %d and WpUserId = %s and RatingS = %d
                        ",
                $galeryID,$WpUserId,1
            ) );

            if(!empty($countSuserId)){
                if(count($countSuserId)){

                    $i = 1;

                    ?>
<pre>
                    <script  data-cg-processing="true">

                    <?php

                    foreach($countSuserId as $object){
                        ?>

                        var index = <?php echo json_encode($galeryIDuserForJs) ?>;
                        var pid = <?php echo json_encode($object->pid);?>;
                        // wenn es bishierher gekommen ist, dann hat der user bereits das bild bewertet
                        // sollte es wieder eine id sein die der user schon mal bewertet hat, ann wir dieser eine 1 hinzugefügt
                        // cgJsData[index].cgJsCountSuser[pid] die der user nicht bewertet hat sind undefined
                        if(typeof cgJsData[index].cgJsCountSuser[pid] != 'undefined'){
                            var countS = parseInt(cgJsData[index].cgJsCountSuser[pid]);
                            countS = countS+1;
                            cgJsData[index].cgJsCountSuser[pid] = countS;
                        }
                        else{
                            cgJsData[index].cgJsCountSuser[pid] = 1;
                        }
                        cgJsData[index].lastVotedUserImageId = pid;

                    <?php

                        $i++;

                    }

                      ?>

                    </script>
</pre>
                    <?php

                }
            }

        }

    }
    // cookie users check
    //else if (($options['general']['ShowOnlyUsersVotes']==1 or $options['general']['HideUntilVote']==1 or $options['pro']['MinusVote']==1) and $options['general']['CheckCookie']==1 and $options['general']['CheckIp']!=1){//old logic before 10.07.2022
    else if ($options['general']['CheckCookie']==1 and $options['general']['CheckIp']!=1){

        if(isset($_COOKIE['contest-gal1ery-'.$galeryID.'-voting'])) {

            $countSuserCookie = $wpdb->get_results( $wpdb->prepare(
                "
                        SELECT pid
                        FROM $tablenameIP
                        WHERE GalleryID = %d and CookieId = %s and RatingS = %d
                    ",
                $galeryID,$_COOKIE['contest-gal1ery-'.$galeryID.'-voting'],1
            ) );

            if(!empty($countSuserCookie)){
                if(count($countSuserCookie)){

                    ?>
<pre>
                    <script  data-cg-processing="true">

                        <?php

                        foreach($countSuserCookie as $object){
                            ?>
                                var index = <?php echo json_encode($galeryIDuserForJs) ?>;
                                var pid = <?php echo json_encode($object->pid);?>;
                                // wenn es bishierher gekommen ist, dann hat der user bereits das bild bewertet
                                // sollte es wieder eine id sein die der user schon mal bewertet hat, ann wir dieser eine 1 hinzugefügt
                                // cgJsCountSuser[pid] die der user nicht bewertet hat sind undefined
                                if(typeof cgJsData[index].cgJsCountSuser[pid] != 'undefined'){
                                    var countS = parseInt(cgJsData[index].cgJsCountSuser[pid]);
                                    countS = countS+1;
                                    cgJsData[index].cgJsCountSuser[pid] = countS;
                                }
                                else{
                                    cgJsData[index].cgJsCountSuser[pid] = 1;
                                }
                                cgJsData[index].lastVotedUserImageId = pid;
                            <?php

                        }

                    ?>

                    </script>
</pre>
                    <?php

                }
            }

        }

    }
     else if ($options['general']['CheckIp']==1 and $options['general']['CheckCookie']==1){
        // then select with coookie id
        if(isset($_COOKIE['contest-gal1ery-'.$galeryID.'-voting'])) {// then both combined cookie with IP, same also for voting check then, will be proved with OR, otherwise no check
            $countSuserIp = $wpdb->get_results( $wpdb->prepare(
                "
                    SELECT pid
                    FROM $tablenameIP
                    WHERE (GalleryID = %d and IP = %s and RatingS = %d) OR (GalleryID = %d and CookieId = %s and RatingS = %d)
                ",
                $galeryID,$userIP,1,$galeryID,$_COOKIE['contest-gal1ery-'.$galeryID.'-voting'],1
            ) );
        } else{
            $countSuserIp = 0;
        }

        if(!empty($countSuserIp)){
            if(count($countSuserIp)){

                ?>
                    <pre>
                <script  data-cg-processing="true">
                    <?php
                foreach($countSuserIp as $object){
                    ?>
                        var index = <?php echo json_encode($galeryIDuserForJs) ?>;
                        var pid = <?php echo json_encode($object->pid);?>;
                        // wenn es bishierher gekommen ist, dann hat der user bereits das bild bewertet
                        // sollte es wieder eine id sein die der user schon mal bewertet hat, ann wir dieser eine 1 hinzugefügt
                        // cgJsCountSuser[pid] die der user nicht bewertet hat sind undefined
                        if(typeof cgJsData[index].cgJsCountSuser[pid] != 'undefined'){
                            var countS = parseInt(cgJsData[index].cgJsCountSuser[pid]);
                            countS = countS+1;
                            cgJsData[index].cgJsCountSuser[pid] = countS;
                        }
                        else{
                            cgJsData[index].cgJsCountSuser[pid] = 1;
                        }
                        cgJsData[index].lastVotedUserImageId = pid;
                    <?php
                }
                ?>
                </script>
</pre>
                <?php

            }
        }

    }
     //else if (($options['general']['ShowOnlyUsersVotes']==1 or $options['general']['HideUntilVote']==1 or $options['pro']['MinusVote']==1) and $options['general']['CheckIp']==1 and $options['general']['CheckCookie']!=1){//old logic before 10.07.2022
     else if ($options['general']['CheckIp']==1 and $options['general']['CheckCookie']!=1){// IP check then

            $countSuserIp = $wpdb->get_results( $wpdb->prepare(
                "
                    SELECT pid
                    FROM $tablenameIP
                    WHERE GalleryID = %d and IP = %s and RatingS = %d
                ",
                $galeryID,$userIP,1
            ) );

        if(!empty($countSuserIp)){
            if(count($countSuserIp)){

                ?>
                <pre>
                <script  data-cg-processing="true">
                    <?php
                foreach($countSuserIp as $object){
                    ?>
                        var index = <?php echo json_encode($galeryIDuserForJs) ?>;
                        var pid = <?php echo json_encode($object->pid);?>;
                        // wenn es bishierher gekommen ist, dann hat der user bereits das bild bewertet
                        // sollte es wieder eine id sein die der user schon mal bewertet hat, ann wir dieser eine 1 hinzugefügt
                        // cgJsCountSuser[pid] die der user nicht bewertet hat sind undefined
                        if(typeof cgJsData[index].cgJsCountSuser[pid] != 'undefined'){
                            var countS = parseInt(cgJsData[index].cgJsCountSuser[pid]);
                            countS = countS+1;
                            cgJsData[index].cgJsCountSuser[pid] = countS;
                        }
                        else{
                            cgJsData[index].cgJsCountSuser[pid] = 1;
                        }
                        cgJsData[index].lastVotedUserImageId = pid;
                    <?php
                }
                ?>
                </script>
</pre>
                <?php

            }
        }
    }
}
