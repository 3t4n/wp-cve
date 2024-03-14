
<?php $social_icons = json_decode (urldecode( $member_social_links ) ); ?>
<div class="style6 <?php echo $team_custom_class; ?>">
    <div class="wdo-teamshowcase" style="border: 1px <?php echo $team_member_border_style; ?> #000;">
        <div class="pic">
            <img src="<?php echo $team_image_url; ?>" alt="">
            <div class="social_media_team">
                <ul class="team_social">
                    <?php 
                    if (isset( $social_icons) && is_array($social_icons) ) {
                        foreach ($social_icons as $social_link) { ?>
                           <li><a href="<?php echo ( $social_link -> social_icon_url !='' ) ? $social_link -> social_icon_url : 'javascript:void(0)'; ?>"><i class="<?php echo $social_link -> selected_team_icon; ?>"></i></a></li>
                        <?php }
                    }
                    ?>
                </ul>
            </div>
        </div>
        <div class="team-prof">
            <h3 class="post-title">
                <a href="<?php echo ( $member_profile_link != '' ) ? $member_profile_link : 'javascript:void(0)' ; ?>">
                    <?php echo $team_member_name; ?>
                </a>
                <span class="post"><?php echo $team_member_designation; ?></span>
            </h3>
            <div class="description">
                <?php echo $content; ?>
            </div>
        </div>
    </div>
</div>