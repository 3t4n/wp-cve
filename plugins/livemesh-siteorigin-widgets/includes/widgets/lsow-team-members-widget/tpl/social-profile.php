<?php

?>

<div class="lsow-social-wrap">

    <div class="lsow-social-list">

        <?php

        $social_profile = $team_member['social_profile'];

        $email = $social_profile['email_address'];
        $facebook_url = $social_profile['facebook'];
        $twitter_url = $social_profile['twitter'];
        $linkedin_url = $social_profile['linkedin'];
        $dribbble_url = $social_profile['dribbble'];
        $pinterest_url = $social_profile['pinterest'];
        $googleplus_url = $social_profile['google_plus'];
        $instagram_url = $social_profile['instagram'];


        if ($email)
            echo '<div class="lsow-social-list-item"><a class="lsow-email" href="mailto:' . $email . '" title="' . __("Send an email", 'livemesh-so-widgets') . '"><i class="lsow-icon-email"></i></a></div>';
        if ($facebook_url)
            echo '<div class="lsow-social-list-item"><a class="lsow-facebook" href="' . $facebook_url . '" target="_blank" title="' . __("Follow on Facebook", 'livemesh-so-widgets') . '"><i class="lsow-icon-facebook"></i></a></div>';
        if ($twitter_url)
            echo '<div class="lsow-social-list-item"><a class="lsow-twitter" href="' . $twitter_url . '" target="_blank" title="' . __("Subscribe to Twitter Feed", 'livemesh-so-widgets') . '"><i class="lsow-icon-twitter"></i></a></div>';
        if ($linkedin_url)
            echo '<div class="lsow-social-list-item"><a class="lsow-linkedin" href="' . $linkedin_url . '" target="_blank" title="' . __("View LinkedIn Profile", 'livemesh-so-widgets') . '"><i class="lsow-icon-linkedin"></i></a></div>';
        if ($googleplus_url)
            echo '<div class="lsow-social-list-item"><a class="lsow-googleplus" href="' . $googleplus_url . '" target="_blank" title="' . __("Follow on Google Plus", 'livemesh-so-widgets') . '"><i class="lsow-icon-googleplus"></i></a></div>';
        if ($instagram_url)
            echo '<div class="lsow-social-list-item"><a class="lsow-instagram" href="' . $instagram_url . '" target="_blank" title="' . __("View Instagram Feed", 'livemesh-so-widgets') . '"><i class="lsow-icon-instagram"></i></a></div>';
        if ($pinterest_url)
            echo '<div class="lsow-social-list-item"><a class="lsow-pinterest" href="' . $pinterest_url . '" target="_blank" title="' . __("Subscribe to Pinterest Feed", 'livemesh-so-widgets') . '"><i class="lsow-icon-pinterest"></i></a></div>';
        if ($dribbble_url)
            echo '<div class="lsow-social-list-item"><a class="lsow-dribbble" href="' . $dribbble_url . '" target="_blank" title="' . __("View Dribbble Portfolio", 'livemesh-so-widgets') . '"><i class="lsow-icon-dribbble"></i></a></div>';

        ?>

    </div>

</div>
