<?php


// 4 Varianten hier möglich wenn andere Einstellungen getroffen wurden als standard

// Prüfen für Rating mit einem Stern und nicht eingeloggtem User
if($generalOptions['ShowOnlyUsersVotes']==1 && $generalOptions['CheckLogin']!=1 && $generalOptions['AllowRating']==2){

    $countS = $wpdb->get_var( $wpdb->prepare(
        "
							SELECT COUNT(*) AS NumberOfRows
							FROM $tablenameIP 
							WHERE GalleryID = %d and IP = %s and RatingS = %d and pid = %d
						",
        $galeryID,$ip,1,$pictureID
    ) );

}

// Prüfen für Rating mit einem Stern und eingeloggtem User
if($generalOptions['ShowOnlyUsersVotes']==1 && $generalOptions['CheckLogin']==1 && $generalOptions['AllowRating']==2){

    if(is_user_logged_in()){

        $countS = $wpdb->get_var( $wpdb->prepare(
            "
								SELECT COUNT(*) AS NumberOfRows
								FROM $tablenameIP
								WHERE GalleryID = %d and WpUserId = %s and RatingS = %d and pid = %d
							",
            $galeryID,get_current_user_id(),1,$pictureID
        ) );

        $countVotesOfUserPerGallery = $wpdb->get_var( $wpdb->prepare(
            "
								SELECT COUNT(*) AS NumberOfRows
								FROM $tablenameIP
								WHERE GalleryID = %d and WpUserId = %s and RatingS = %d
							",
            $galeryID,get_current_user_id(),1
        ) );
    }
}

// Prüfen für Rating mit fünf Sternen und nicht eingeloggtem User. countR und rating ist hier notwendig zu wissen
if($generalOptions['ShowOnlyUsersVotes']==1 && $generalOptions['CheckLogin']!=1 && ($generalOptions['AllowRating']>=12 OR $generalOptions['AllowRating']==1)){

    $countR = $wpdb->get_var( $wpdb->prepare(
        "
							SELECT COUNT(*) AS NumberOfRows
							FROM $tablenameIP 
							WHERE GalleryID = %d and IP = %s and Rating >= %d and pid = %d
						",
        $galeryID,$ip,1,$pictureID
    ) );

    $rating = $wpdb->get_var( $wpdb->prepare(
        "
							SELECT SUM(Rating)
							FROM $tablenameIP 
							WHERE GalleryID = %d and IP = %s and Rating >= %d and pid = %d
						",
        $galeryID,$ip,1,$pictureID
    ) );

}


// Prüfen für Rating mit fünf Sternen und nicht eingeloggtem User. countR und rating ist hier notwendig zu wissen --- ENDE

// Prüfen für Rating mit fünf Sternen und eingeloggtem User. countR und rating ist hier notwendig zu wissen

if($generalOptions['ShowOnlyUsersVotes']==1 && $generalOptions['CheckLogin']==1 && ($generalOptions['AllowRating']>=12 OR $generalOptions['AllowRating']==1)){

    if(is_user_logged_in()){

        $countR = $wpdb->get_var( $wpdb->prepare(
            "
								SELECT COUNT(*) AS NumberOfRows
								FROM $tablenameIP
								WHERE GalleryID = %d and WpUserId = %s and Rating >= %d and pid = %d
							",
            $galeryID,get_current_user_id(),1,$pictureID
        ) );

        $rating = $wpdb->get_var( $wpdb->prepare(
            "
								SELECT SUM(Rating)
								FROM $tablenameIP
								WHERE GalleryID = %d and WpUserId = %s and Rating >= %d and pid = %d
							",
            $galeryID,get_current_user_id(),1,$pictureID
        ) );

    }

    $countVotesOfUserPerGallery = $wpdb->get_var( $wpdb->prepare(
        "
								SELECT COUNT(*) AS NumberOfRows
								FROM $tablenameIP
								WHERE GalleryID = %d and WpUserId = %s and Rating >= %d
							",
        $galeryID,get_current_user_id(),1
    ) );

}

// Prüfen für Rating mit fünf Sternen und eingeloggtem User. countR und rating ist hier notwendig zu wissen --- ENDE
?>