<?php $locale = get_locale(); ?>
<div class="af2_menu_sheet_sidebar af2_card">
    <div class="af2_card_block">
        <h4><?php _e('Introduction video', 'funnelforms-free'); ?></h4>
        <p><?php _e('Check out the introduction video to get a quick overview of all the features!', 'funnelforms-free'); ?></p>
        <div data-target="introduction_video" class="af2_btn af2_btn_primary af2_modal_btn mt20"><i class="fas fa-play-circle"></i><?php _e('Watch video', 'funnelforms-free'); ?></div>
    </div>
    <div class="af2_card_divider"></div>
    <div class="af2_card_block">
        <h4><?php _e('Need help?', 'funnelforms-free'); ?></h4>
        <p><?php _e('The question mark at the bottom right will take you to the Help Center!', 'funnelforms-free'); ?></p>
        <a class="af2_btn_link" href="https://help.funnelforms.io/" target="_blank">
            <div class="af2_btn af2_btn_secondary mt20"><i class="fas fa-headset"></i><?php _e('Help center', 'funnelforms-free'); ?></div>
        </a>
    </div>
    <div class="af2_card_divider"></div>
    <div class="af2_card_block">
        <h4><?php _e('Feature requests', 'funnelforms-free'); ?></h4>
        <p><?php _e('Develop Funnelforms together with us and share your ideas and wishes here!', 'funnelforms-free'); ?></p>
        <?php 
            $featureupvote_link = '';
            switch($locale) {
                case 'de_DE': {
                    $featureupvote_link = 'https://feedback.funnelforms.io/b/funnelforms-deutsch/';
                    break;
                }
                default: {
                    $featureupvote_link = 'https://feedback.funnelforms.io/b/funnelforms-english/';
                    break;
                }
            }
       ; ?>
        <a class="af2_btn_link" href="<?php _e(wp_kses_post($featureupvote_link)); ?>" target="_blank">
            <div class="af2_btn af2_btn_secondary mt20"><i class="fas fa-vote-yea"></i><?php _e('Feature voting', 'funnelforms-free'); ?></div>
        </a>
    </div>
    <div class="af2_card_divider"></div>
    <div class="af2_card_block">
        <?php 
        $sbarcheck = '';
            if(get_option('af2_dark_mode') == 1){
                $sbarcheck = 'checked';
            }
        ?>
        <div class="af2_toggle_wrapper mb10">

            <input type="checkbox" id="af2_toggle_dark_mode" class="af2_toggle" <?php  _e($sbarcheck) ; ?>>
            <label for="af2_toggle_dark_mode" class="af2_toggle_btn"></label>
            <h4 class="af2_toggle_label ml5"><?php _e('Dark Mode', 'funnelforms-free'); ?></h4>
        </div>
        <p><?php _e('Activate the dark mode within the plugin!', 'funnelforms-free'); ?></p>
    </div>
</div>


<div id="introduction_video" class="af2_modal" 
    data-heading="<?php _e('Introduction video', 'funnelforms-free'); ?>"
    data-close="<?php _e('Close', 'funnelforms-free'); ?>">
    
  <!-- Modal content -->
  <div class="af2_modal_content">
    <?php
        $quickstartguide_url = "https://player.vimeo.com/video/782961075?badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479";
        $locale = get_locale();
        if($locale == 'de_DE') {
            $quickstartguide_url = "https://player.vimeo.com/video/781786834?badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479";
        } 
    ?>
    <div style="padding:56.43% 0 0 0;position:relative;">
        <iframe src="<?php echo $quickstartguide_url ?>" width="100%" height="100%" frameborder="0" allowfullscreen="allowfullscreen" style="position:absolute;top:0;left:0;width:100%;height:100%;"></iframe>
    </div><script src="https://player.vimeo.com/api/player.js"></script>
  </div>

</div>