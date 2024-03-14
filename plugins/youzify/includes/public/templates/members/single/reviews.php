<div id="youzify-main-reviews" class="youzify-tab youzify-tab-reviews">
<?php

$user_id = bp_displayed_user_id();

$args = array(
	'pagination' => true,
	'user_id' => $user_id,
);


// Get Reviews.
echo youzify_get_user_reviews( $args );

// Get Loading Spinner.
youzify_loading();

?>

</div>
