<?php

add_action('cg_copy_rating','cg_copy_rating');
if(!function_exists('cg_copy_rating')){
    function cg_copy_rating($cg_copy_start,$oldGalleryID,$nextGalleryID,$collectImageIdsArray){
        if(!empty($collectImageIdsArray)){

            global $wpdb;

            $tablename_ip = $wpdb->prefix . "contest_gal1ery_ip";

            $uploadFolder = wp_upload_dir();
            $galleryUpload = $uploadFolder['basedir'] . '/contest-gallery/gallery-id-' . $nextGalleryID . '';

            // PROCESSING EXAMPLE (EXAMPLES ARE INDEPENDENT FROM EACH OTHER)

            // First
            /*
            INSERT INTO wp_contest_gal1ery_ip
    SELECT NULL, pid, IP, 3001, Rating, RatingS, WpUserId, Tstamp, DateVote, VoteDate, OptionSet, CookieId
    FROM wp_contest_gal1ery_ip
    WHERE GalleryID IN (3000)*/

            // Then
            // UPDATE wp_contest_gal1ery_ip SET pid = CASE pid WHEN 22717 THEN 30000 WHEN 22716 THEN 30001 ELSE pid END WHERE GalleryID IN (341)


            // First
            /* Have to look like this:
            INSERT INTO wp_contest_gal1ery_ip
    SELECT NULL, pid, IP, 3001, Rating, RatingS, WpUserId, Tstamp, DateVote, VoteDate, OptionSet, CookieId
    FROM wp_contest_gal1ery_ip
    WHERE GalleryID IN (3000)*/

            if($cg_copy_start==0){
                //Have to look like this:INSERT INTO wp_contest_gal1ery_ip
                //SELECT NULL, pid, IP, 3001, Rating, RatingS, WpUserId, Tstamp, DateVote, VoteDate, OptionSet, CookieId
                //FROM wp_contest_gal1ery_ip
                //WHERE GalleryID IN (3000)

                $wpdb->query($wpdb->prepare("INSERT INTO $tablename_ip
SELECT NULL, pid, IP, $nextGalleryID, Rating, RatingS, WpUserId, VoteDate, Tstamp, OptionSet, CookieId, Category, CategoriesOn
FROM $tablename_ip
WHERE GalleryID IN (%d)",[$oldGalleryID]));

            }

            //Have to look like this:UPDATE wp_contest_gal1ery_ip SET pid = CASE pid WHEN 22717 THEN 30000 WHEN 22716 THEN 30001 ELSE pid END WHERE GalleryID IN (341)
            $whenThenString = '';
            foreach($collectImageIdsArray as $oldImageId => $newImageId){
                $whenThenString .= "WHEN $oldImageId THEN $newImageId ";
            }

            $whenThenString = substr_replace($whenThenString ,"", -1);

            //Have to look like this:UPDATE wp_contest_gal1ery_ip SET pid = CASE pid WHEN 22717 THEN 30000 WHEN 22716 THEN 30001 ELSE pid END WHERE GalleryID IN (341)
            $wpdb->query($wpdb->prepare("UPDATE $tablename_ip SET pid = CASE pid $whenThenString ELSE pid END WHERE GalleryID IN (%d)",[$nextGalleryID]));

            // Create categories

            $oldAndNextGalleryIdsCategories = json_decode(file_get_contents($galleryUpload . '/json/' . $nextGalleryID . '-collect-cat-ids-array.json'),true);

            if(!empty($oldAndNextGalleryIdsCategories)){

                //Have to look like this:UPDATE wp_contest_gal1ery_ip SET category = CASE category WHEN 22717 THEN 30000 WHEN 22716 THEN 30001 ELSE category END WHERE GalleryID IN (341)
                $whenThenString = '';
                foreach($oldAndNextGalleryIdsCategories as $oldCategoryId => $newCategoryId){
                    $whenThenString .= "WHEN $oldCategoryId THEN $newCategoryId ";
                }

                $whenThenString = substr_replace($whenThenString ,"", -1);

                //Same for categories
                // have to be done two times, CategoriesOn = 0 and CategoriesOn = 1
                $wpdb->query($wpdb->prepare("UPDATE $tablename_ip SET Category = CASE Category $whenThenString ELSE Category END WHERE CategoriesOn = 0 AND GalleryID IN (%d)",[$nextGalleryID]));

                //Same for categories
                // have to be done two times, CategoriesOn = 0 and CategoriesOn = 1
                $wpdb->query($wpdb->prepare("UPDATE $tablename_ip SET Category = CASE Category $whenThenString ELSE Category END WHERE CategoriesOn = 1 AND GalleryID IN (%d)",[$nextGalleryID]));

            }


        }



    }
}
