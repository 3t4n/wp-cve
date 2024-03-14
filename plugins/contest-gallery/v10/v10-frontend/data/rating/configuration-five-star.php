<?php

?>
<pre>
    <script  data-cg-processing="true">

        var index = <?php echo json_encode($galeryIDuserForJs) ?>;

        cgJsData[index].cgJsCountRuser = {};

        var AllowRatingDynamic = <?php echo json_encode($options['general']['AllowRating']);?>;
        AllowRatingDynamic = AllowRatingDynamic-10;

        for(var rCount=1;rCount<=AllowRatingDynamic;rCount++){
            eval("cgJsData[index].cgJsCountR"+rCount+"user =  {};");
        }
/*        cgJsData[index].cgJsCountR1user = {};
        cgJsData[index].cgJsCountR2user = {};
        cgJsData[index].cgJsCountR3user = {};
        cgJsData[index].cgJsCountR4user = {};
        cgJsData[index].cgJsCountR5user = {};*/

        cgJsData[index].cgJsRatingUser = {};
        cgJsData[index].lastVotedUserImageId = 0;

    </script>
    </pre>
<?php

$AllowRatingMax = $options['general']['AllowRating']-10;

if(empty($isOnlyGalleryNoVoting) && empty($isOnlyGalleryWinner)) {

// registered users check
    //if(($options['general']['ShowOnlyUsersVotes']==1 or $options['general']['HideUntilVote']==1 or $options['pro']['MinusVote']==1) and $options['general']['CheckLogin']==1){//old logic before 10.07.2022
        if($options['general']['CheckLogin']==1){
        if (is_user_logged_in()) {

            $countRuserId = $wpdb->get_results($wpdb->prepare(
                "
                                SELECT pid, Rating
                                FROM $tablenameIP
                                WHERE GalleryID = %d and WpUserId = %s and Rating >= %d and Rating <= %d
                            ",
                $galeryID, $WpUserId, 1, $AllowRatingMax
            ));

            if (!empty($countRuserId)) {

                                    ?>
<pre>
                    <script  data-cg-processing="true">

                        var AllowRatingDynamic = <?php echo json_encode($options['general']['AllowRating']);?>;
                        AllowRatingDynamic = AllowRatingDynamic-10;

                        <?php

                foreach ($countRuserId as $object) {
                    ?>
                        var index = <?php echo json_encode($galeryIDuserForJs) ?>;
                        var pid = <?php echo json_encode($object->pid);?>;
                        var rating = <?php echo json_encode($object->Rating);?>;
                        var ratingRatingOriginal = <?php echo json_encode($object->Rating);?>;
                        // wenn es bishierher gekommen ist, dann hat der user bereits das bild bewertet
                        // sollte es wieder eine id sein die der user schon mal bewertet hat, ann wir dieser eine 1 hinzugefügt
                        // cgJsCountSuserIp[pid] die der user nicht bewertet hat sind undefined
                        if (typeof cgJsData[index].cgJsCountRuser[pid] != 'undefined') {
                            var countR = parseInt(cgJsData[index].cgJsCountRuser[pid])+1;
                            var rating = parseInt(cgJsData[index].cgJsRatingUser[pid])+parseInt(rating);

                            for(var rCount=1;rCount<=AllowRatingDynamic;rCount++){
                                eval("cgJsData[index].cgJsCountR"+rCount+"user[pid] = cgJsData[index].cgJsCountR"+rCount+"user[pid] || 0;");
                            }

/*                            cgJsData[index].cgJsCountR1user[pid] = cgJsData[index].cgJsCountR1user[pid] || 0;
                            cgJsData[index].cgJsCountR2user[pid] = cgJsData[index].cgJsCountR2user[pid] || 0;
                            cgJsData[index].cgJsCountR3user[pid] = cgJsData[index].cgJsCountR3user[pid] || 0;
                            cgJsData[index].cgJsCountR4user[pid] = cgJsData[index].cgJsCountR4user[pid] || 0;
                            cgJsData[index].cgJsCountR5user[pid] = cgJsData[index].cgJsCountR5user[pid] || 0;*/

                            for(var rCount=1;rCount<=AllowRatingDynamic;rCount++){
                                eval("if(ratingRatingOriginal=="+rCount+"){cgJsData[index].cgJsCountR"+rCount+"user[pid] = cgJsData[index].cgJsCountR"+rCount+"user[pid]+1;}");
                            }

/*                            if(ratingRatingOriginal==1){
                                cgJsData[index].cgJsCountR1user[pid] = cgJsData[index].cgJsCountR1user[pid]+1;
                            }
                            if(ratingRatingOriginal==2){
                                cgJsData[index].cgJsCountR2user[pid] = cgJsData[index].cgJsCountR2user[pid]+1;
                            }
                            if(ratingRatingOriginal==3){
                                cgJsData[index].cgJsCountR3user[pid] = cgJsData[index].cgJsCountR3user[pid]+1;
                            }
                            if(ratingRatingOriginal==4){
                                cgJsData[index].cgJsCountR4user[pid] = cgJsData[index].cgJsCountR4user[pid]+1;
                            }
                            if(ratingRatingOriginal==5){
                                cgJsData[index].cgJsCountR5user[pid] = cgJsData[index].cgJsCountR5user[pid]+1;
                            }*/
                            cgJsData[index].cgJsCountRuser[pid] = countR;
                            cgJsData[index].cgJsRatingUser[pid] = rating;

                        }
                        else {
                            cgJsData[index].cgJsCountRuser[pid] = 1;
                            cgJsData[index].cgJsRatingUser[pid] = parseInt(rating);

                            for(var rCount=1;rCount<=AllowRatingDynamic;rCount++){
                                eval("if(ratingRatingOriginal=="+rCount+"){cgJsData[index].cgJsCountR"+rCount+"user[pid] = 1;}");
                            }

                            /*if(ratingRatingOriginal==1){
                                cgJsData[index].cgJsCountR1user[pid] = 1;
                            }
                            if(ratingRatingOriginal==2){
                                cgJsData[index].cgJsCountR2user[pid] = 1;
                            }
                            if(ratingRatingOriginal==3){
                                cgJsData[index].cgJsCountR3user[pid] = 1;
                            }
                            if(ratingRatingOriginal==4){
                                cgJsData[index].cgJsCountR4user[pid] = 1;
                            }
                            if(ratingRatingOriginal==5){
                                cgJsData[index].cgJsCountR5user[pid] = 1;
                            }*/
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
// cookie users check
    //else if (($options['general']['ShowOnlyUsersVotes']==1 or $options['general']['HideUntilVote']==1 or $options['pro']['MinusVote']==1) and $options['general']['CheckCookie']==1){//old logic before 10.07.2022
    else if ($options['general']['CheckCookie']==1 and $options['general']['CheckIp']!=1){

        if(isset($_COOKIE['contest-gal1ery-'.$galeryID.'-voting'])) {

            $countSuserCookie = $wpdb->get_results( $wpdb->prepare(
                "
                            SELECT pid, Rating
                            FROM $tablenameIP
                            WHERE GalleryID = %d and CookieId = %s and Rating >= %d and Rating <= %d
                        ",
                $galeryID,$_COOKIE['contest-gal1ery-'.$galeryID.'-voting'],1, $AllowRatingMax
            ) );

            if(!empty($countSuserCookie)){

                                    ?>
                <pre>
                    <script  data-cg-processing="true">

                        var AllowRatingDynamic = <?php echo json_encode($options['general']['AllowRating']);?>;
                        AllowRatingDynamic = AllowRatingDynamic-10;

                        <?php

                foreach($countSuserCookie as $object){

                    ?>
                        var index = <?php echo json_encode($galeryIDuserForJs) ?>;
                        var pid = <?php echo json_encode($object->pid);?>;
                        var rating = <?php echo json_encode($object->Rating);?>;
                        var ratingRatingOriginal = <?php echo json_encode($object->Rating);?>;
                        // wenn es bishierher gekommen ist, dann hat der user bereits das bild bewertet
                        // sollte es wieder eine id sein die der user schon mal bewertet hat, ann wir dieser eine 1 hinzugefügt
                        // cgJsCountSuserIp[pid] die der user nicht bewertet hat sind undefined
                        if (typeof cgJsData[index].cgJsCountRuser[pid] != 'undefined') {
                            var countR = parseInt(cgJsData[index].cgJsCountRuser[pid])+1;
                            var rating = parseInt(cgJsData[index].cgJsRatingUser[pid])+parseInt(rating);

                            cgJsData[index].cgJsCountRuser[pid] = countR;
                            cgJsData[index].cgJsRatingUser[pid] = rating;

                            for(var rCount=1;rCount<=AllowRatingDynamic;rCount++){
                                eval("cgJsData[index].cgJsCountR"+rCount+"user[pid] = cgJsData[index].cgJsCountR"+rCount+"user[pid] || 0;");
                            }

/*                            cgJsData[index].cgJsCountR1user[pid] = cgJsData[index].cgJsCountR1user[pid] || 0;
                            cgJsData[index].cgJsCountR2user[pid] = cgJsData[index].cgJsCountR2user[pid] || 0;
                            cgJsData[index].cgJsCountR3user[pid] = cgJsData[index].cgJsCountR3user[pid] || 0;
                            cgJsData[index].cgJsCountR4user[pid] = cgJsData[index].cgJsCountR4user[pid] || 0;
                            cgJsData[index].cgJsCountR5user[pid] = cgJsData[index].cgJsCountR5user[pid] || 0;*/

                            for(var rCount=1;rCount<=AllowRatingDynamic;rCount++){
                                eval("if(ratingRatingOriginal=="+rCount+"){cgJsData[index].cgJsCountR"+rCount+"user[pid] = cgJsData[index].cgJsCountR"+rCount+"user[pid]+1;}");
                            }

/*                            if(ratingRatingOriginal==1){
                                cgJsData[index].cgJsCountR1user[pid] = cgJsData[index].cgJsCountR1user[pid]+1;
                            }
                            if(ratingRatingOriginal==2){
                                cgJsData[index].cgJsCountR2user[pid] = cgJsData[index].cgJsCountR2user[pid]+1;
                            }
                            if(ratingRatingOriginal==3){
                                cgJsData[index].cgJsCountR3user[pid] = cgJsData[index].cgJsCountR3user[pid]+1;
                            }
                            if(ratingRatingOriginal==4){
                                cgJsData[index].cgJsCountR4user[pid] = cgJsData[index].cgJsCountR4user[pid]+1;
                            }
                            if(ratingRatingOriginal==5){
                                cgJsData[index].cgJsCountR5user[pid] = cgJsData[index].cgJsCountR5user[pid]+1;
                            }*/

                        }
                        else {
                            cgJsData[index].cgJsCountRuser[pid] = 1;
                            cgJsData[index].cgJsRatingUser[pid] = parseInt(rating);

                            for(var rCount=1;rCount<=AllowRatingDynamic;rCount++){
                                eval("if(ratingRatingOriginal=="+rCount+"){cgJsData[index].cgJsCountR"+rCount+"user[pid] = 1;}");
                            }

/*                            if(ratingRatingOriginal==1){
                                cgJsData[index].cgJsCountR1user[pid] = 1;
                            }
                            if(ratingRatingOriginal==2){
                                cgJsData[index].cgJsCountR2user[pid] = 1;
                            }
                            if(ratingRatingOriginal==3){
                                cgJsData[index].cgJsCountR3user[pid] = 1;
                            }
                            if(ratingRatingOriginal==4){
                                cgJsData[index].cgJsCountR4user[pid] = 1;
                            }
                            if(ratingRatingOriginal==5){
                                cgJsData[index].cgJsCountR5user[pid] = 1;
                            }*/

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

    }else if($options['general']['CheckIp']==1 and $options['general']['CheckCookie']==1){

        if(isset($_COOKIE['contest-gal1ery-'.$galeryID.'-voting'])) {// then both combined cookie with IP, same also for voting check then, will be proved with OR, otherwise no check

            $countSuserCookie = $wpdb->get_results( $wpdb->prepare(
                "
                            SELECT pid, Rating
                            FROM $tablenameIP
                            WHERE (GalleryID = %d and CookieId = %s and Rating >= %d and Rating <= %d) or (GalleryID = %d and IP = %s and Rating >= %d and Rating <= %d)
                        ",
                $galeryID,$_COOKIE['contest-gal1ery-'.$galeryID.'-voting'],1, $AllowRatingMax,$galeryID,$userIP,1, $AllowRatingMax
            ) );

            if(!empty($countSuserCookie)){

                ?>
<pre>
                <script  data-cg-processing="true">

                    var AllowRatingDynamic = <?php echo json_encode($options['general']['AllowRating']);?>;
                    AllowRatingDynamic = AllowRatingDynamic-10;

                    <?php

                    foreach($countSuserCookie as $object){

                    ?>
                    var index = <?php echo json_encode($galeryIDuserForJs) ?>;
                    var pid = <?php echo json_encode($object->pid);?>;
                    var rating = <?php echo json_encode($object->Rating);?>;
                    var ratingRatingOriginal = <?php echo json_encode($object->Rating);?>;
                    // wenn es bishierher gekommen ist, dann hat der user bereits das bild bewertet
                    // sollte es wieder eine id sein die der user schon mal bewertet hat, ann wir dieser eine 1 hinzugefügt
                    // cgJsCountSuserIp[pid] die der user nicht bewertet hat sind undefined
                    if (typeof cgJsData[index].cgJsCountRuser[pid] != 'undefined') {
                        var countR = parseInt(cgJsData[index].cgJsCountRuser[pid])+1;
                        var rating = parseInt(cgJsData[index].cgJsRatingUser[pid])+parseInt(rating);

                        cgJsData[index].cgJsCountRuser[pid] = countR;
                        cgJsData[index].cgJsRatingUser[pid] = rating;

                        for(var rCount=1;rCount<=AllowRatingDynamic;rCount++){
                            eval("cgJsData[index].cgJsCountR"+rCount+"user[pid] = cgJsData[index].cgJsCountR"+rCount+"user[pid] || 0;");
    }

                        /*                            cgJsData[index].cgJsCountR1user[pid] = cgJsData[index].cgJsCountR1user[pid] || 0;
                                                    cgJsData[index].cgJsCountR2user[pid] = cgJsData[index].cgJsCountR2user[pid] || 0;
                                                    cgJsData[index].cgJsCountR3user[pid] = cgJsData[index].cgJsCountR3user[pid] || 0;
                                                    cgJsData[index].cgJsCountR4user[pid] = cgJsData[index].cgJsCountR4user[pid] || 0;
                                                    cgJsData[index].cgJsCountR5user[pid] = cgJsData[index].cgJsCountR5user[pid] || 0;*/

                        for(var rCount=1;rCount<=AllowRatingDynamic;rCount++){
                            eval("if(ratingRatingOriginal=="+rCount+"){cgJsData[index].cgJsCountR"+rCount+"user[pid] = cgJsData[index].cgJsCountR"+rCount+"user[pid]+1;}");
                        }

                        /*                            if(ratingRatingOriginal==1){
                                                        cgJsData[index].cgJsCountR1user[pid] = cgJsData[index].cgJsCountR1user[pid]+1;
                                                    }
                                                    if(ratingRatingOriginal==2){
                                                        cgJsData[index].cgJsCountR2user[pid] = cgJsData[index].cgJsCountR2user[pid]+1;
                                                    }
                                                    if(ratingRatingOriginal==3){
                                                        cgJsData[index].cgJsCountR3user[pid] = cgJsData[index].cgJsCountR3user[pid]+1;
                                                    }
                                                    if(ratingRatingOriginal==4){
                                                        cgJsData[index].cgJsCountR4user[pid] = cgJsData[index].cgJsCountR4user[pid]+1;
                                                    }
                                                    if(ratingRatingOriginal==5){
                                                        cgJsData[index].cgJsCountR5user[pid] = cgJsData[index].cgJsCountR5user[pid]+1;
                                                    }*/

                    }
                    else {
                        cgJsData[index].cgJsCountRuser[pid] = 1;
                        cgJsData[index].cgJsRatingUser[pid] = parseInt(rating);

                        for(var rCount=1;rCount<=AllowRatingDynamic;rCount++){
                            eval("if(ratingRatingOriginal=="+rCount+"){cgJsData[index].cgJsCountR"+rCount+"user[pid] = 1;}");
                        }

                        /*                            if(ratingRatingOriginal==1){
                                                        cgJsData[index].cgJsCountR1user[pid] = 1;
                                                    }
                                                    if(ratingRatingOriginal==2){
                                                        cgJsData[index].cgJsCountR2user[pid] = 1;
                                                    }
                                                    if(ratingRatingOriginal==3){
                                                        cgJsData[index].cgJsCountR3user[pid] = 1;
                                                    }
                                                    if(ratingRatingOriginal==4){
                                                        cgJsData[index].cgJsCountR4user[pid] = 1;
                                                    }
                                                    if(ratingRatingOriginal==5){
                                                        cgJsData[index].cgJsCountR5user[pid] = 1;
                                                    }*/

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
    else if ($options['general']['CheckIp']==1 and $options['general']['CheckCookie']!=1){// IP check then

     //   if ($options['general']['ShowOnlyUsersVotes']==1 or $options['general']['HideUntilVote']==1 or $options['pro']['MinusVote']==1){//old logic before 10.07.2022

            $countRuserIp = $wpdb->get_results($wpdb->prepare(
                "
                            SELECT pid, Rating
                            FROM $tablenameIP
                            WHERE GalleryID = %d and IP = %s and Rating >= %d and Rating <= %d
                        ",
                $galeryID, $userIP, 1, $AllowRatingMax
            ));

            if (!empty($countRuserIp)) {

                ?>
                <pre>
                <script  data-cg-processing="true">

                    var AllowRatingDynamic = <?php echo json_encode($options['general']['AllowRating']);?>;
                    AllowRatingDynamic = AllowRatingDynamic-10;

                    <?php

                foreach ($countRuserIp as $object) {
                    ?>
                        var index = <?php echo json_encode($galeryIDuserForJs) ?>;
                        var pid = <?php echo json_encode($object->pid);?>;
                        var rating = <?php echo json_encode($object->Rating);?>;
                        var ratingRatingOriginal = <?php echo json_encode($object->Rating);?>;
                        // wenn es bishierher gekommen ist, dann hat der user bereits das bild bewertet
                        // sollte es wieder eine id sein die der user schon mal bewertet hat, ann wir dieser eine 1 hinzugefügt
                        // cgJsCountSuserIp[pid] die der user nicht bewertet hat sind undefined
                        if (typeof cgJsData[index].cgJsCountRuser[pid] != 'undefined') {
                            var countR = parseInt(cgJsData[index].cgJsCountRuser[pid])+1;
                            var rating = parseInt(cgJsData[index].cgJsRatingUser[pid])+parseInt(rating);

                            cgJsData[index].cgJsCountRuser[pid] = countR;
                            cgJsData[index].cgJsRatingUser[pid] = rating;

                            for(var rCount=1;rCount<=AllowRatingDynamic;rCount++){
                                eval("cgJsData[index].cgJsCountR"+rCount+"user[pid] = cgJsData[index].cgJsCountR"+rCount+"user[pid] || 0;");
                            }

/*                            cgJsData[index].cgJsCountR1user[pid] = cgJsData[index].cgJsCountR1user[pid] || 0;
                            cgJsData[index].cgJsCountR2user[pid] = cgJsData[index].cgJsCountR2user[pid] || 0;
                            cgJsData[index].cgJsCountR3user[pid] = cgJsData[index].cgJsCountR3user[pid] || 0;
                            cgJsData[index].cgJsCountR4user[pid] = cgJsData[index].cgJsCountR4user[pid] || 0;
                            cgJsData[index].cgJsCountR5user[pid] = cgJsData[index].cgJsCountR5user[pid] || 0;*/

                            for(var rCount=1;rCount<=AllowRatingDynamic;rCount++){
                                eval("if(ratingRatingOriginal=="+rCount+"){cgJsData[index].cgJsCountR"+rCount+"user[pid] = cgJsData[index].cgJsCountR"+rCount+"user[pid]+1;}");
                            }

/*                            if(ratingRatingOriginal==1){
                                cgJsData[index].cgJsCountR1user[pid] = cgJsData[index].cgJsCountR1user[pid]+1;
                            }
                            if(ratingRatingOriginal==2){
                                cgJsData[index].cgJsCountR2user[pid] = cgJsData[index].cgJsCountR2user[pid]+1;
                            }
                            if(ratingRatingOriginal==3){
                                cgJsData[index].cgJsCountR3user[pid] = cgJsData[index].cgJsCountR3user[pid]+1;
                            }
                            if(ratingRatingOriginal==4){
                                cgJsData[index].cgJsCountR4user[pid] = cgJsData[index].cgJsCountR4user[pid]+1;
                            }
                            if(ratingRatingOriginal==5){
                                cgJsData[index].cgJsCountR5user[pid] = cgJsData[index].cgJsCountR5user[pid]+1;
                            }*/

                        }
                        else {

                            cgJsData[index].cgJsCountRuser[pid] = 1;
                            cgJsData[index].cgJsRatingUser[pid] = parseInt(rating);

                            for(var rCount=1;rCount<=AllowRatingDynamic;rCount++){
                                eval("if(ratingRatingOriginal=="+rCount+"){cgJsData[index].cgJsCountR"+rCount+"user[pid] = 1;}");
                            }

/*
                            if(ratingRatingOriginal==1){
                                cgJsData[index].cgJsCountR1user[pid] = 1;
                            }
                            if(ratingRatingOriginal==2){
                                cgJsData[index].cgJsCountR2user[pid] = 1;
                            }
                            if(ratingRatingOriginal==3){
                                cgJsData[index].cgJsCountR3user[pid] = 1;
                            }
                            if(ratingRatingOriginal==4){
                                cgJsData[index].cgJsCountR4user[pid] = 1;
                            }
                            if(ratingRatingOriginal==5){
                                cgJsData[index].cgJsCountR5user[pid] = 1;
                            }
*/

                        }
                        cgJsData[index].lastVotedUserImageId = pid;
                    <?php
                }


                    ?>

                    </script>
</pre>
                <?php

            }

     //   }
    }

}


