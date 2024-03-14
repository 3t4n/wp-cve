<?php
/**###NORMAL###**/
add_action('cg_update_to_pro_one_star','cg_update_to_pro_one_star');
if(!function_exists('cg_update_to_pro_one_star')){
    function cg_update_to_pro_one_star($galeryIDuser,$pictureID,$ratingFileData,$message){

        ?>
        <script data-cg-processing="true">

            var message = <?php echo json_encode($message);?>;
            var galeryIDuser = <?php echo json_encode($galeryIDuser);?>;
            var pictureID = <?php echo json_encode($pictureID);?>;
            var ratingFileData = <?php echo json_encode($ratingFileData);?>;

            cgJsClass.gallery.rating.setRatingOneStar(pictureID,0,false,galeryIDuser,false,false,ratingFileData);
            cgJsClass.gallery.function.message.show(message);

        </script>
        <?php


    }
}
add_action('cg_update_to_pro_five_stars','cg_update_to_pro_five_stars');
if(!function_exists('cg_update_to_pro_five_stars')){
    function cg_update_to_pro_five_stars($galeryIDuser,$pictureID,$ratingFileData,$isFromSingleView,$message){
        ?>
        <script data-cg-processing="true">

            var message = <?php echo json_encode($message);?>;
            var galeryIDuser = <?php echo json_encode($galeryIDuser);?>;
            var pictureID = <?php echo json_encode($pictureID);?>;
            var isFromSingleView = <?php echo json_encode($isFromSingleView);?>;
            var ratingFileData = <?php echo json_encode($ratingFileData);?>;

            cgJsClass.gallery.rating.setRatingFiveStar(pictureID,0,0,false,galeryIDuser,false,false,ratingFileData,isFromSingleView);
            cgJsClass.gallery.function.message.show(message);

        </script>
        <?php
    }
}
/**###NORMAL---END###**/
