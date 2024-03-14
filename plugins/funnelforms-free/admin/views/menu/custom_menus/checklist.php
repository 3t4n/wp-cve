<div class="af2_checklist_page_wrapper">
    <div class="af2_card af2_checklist">
        <div class="af2_card_block">
            <div class="af2_card_heading">
                <div class="af2_icon_wrapper colorGreen"><i class="fas fa-list-alt"></i></div>
                <div>
                    <h4><?php _e('Your Checklist', 'funnelforms-free'); ?></h4>
                    <p><?php _e('Complete the following steps to get the most out of Funnelforms.', 'funnelforms-free'); ?></p>
                </div>
            </div>
            <div class="af2_card_content">
                <?php foreach($af2_custom_contents as $af2_custom_content) { ?>
                    <?php if(isset($af2_custom_content['url'])) { ?> <a href="<?php _e($af2_custom_content['url']); ?>"> <?php }; ?>
                        
                        <div class="af2_checklist_content">
                            <?php $checkIcon = ''; if($af2_custom_content['success']){ $checkIcon = 'succeed'; } ?>
                            <div class="af2_checklist_icon <?php _e($checkIcon); ?>"><i class="fas fa-check"></i></div>
                            <p class="af2_checklist_text"><?php
                             _e($af2_custom_content['label']);
                            ?></p>
                        </div>
                        <?php if(isset($af2_custom_content['url'])) { ?> </a> <?php }; ?>
                <?php }; ?>
            </div>
        </div>
    </div>
    <div class="af2_card af2_healthlist">
        <div class="af2_card_block">
            <div class="af2_card_heading">
                <div class="af2_icon_wrapper colorGreen"><i class="fas fa-list-alt"></i></div>
                <div>
                    <h4><?=_e('System requirements', 'funnelforms-free')?></h4>
                    <p><?=_e('Check if your WordPress and your web server are configured correctly for Funnelforms.', 'funnelforms-free')?></p>
                </div>
            </div>
            <div class="af2_card_content">
                <?php foreach($this->healthchecks as $healthcheck) { ?>
                    <?php
                        if(isset($healthcheck['check'])) {
                            echo "<script>".$healthcheck['check']."</script>";
                        }
                    ?>

                        <div class="af2_checklist_content">
                            <div <?php if(isset($healthcheck['check'])) { echo "id='".$healthcheck['id']."'"; } ?> class="af2_checklist_icon <?php if((int)$healthcheck['passed'] == 2) { echo 'warning'; } else if((int)$healthcheck['passed'] == 1) { echo 'succeed'; } else { echo 'failed'; } ?>">
                                <?php if($healthcheck['passed']) {
                                    echo '<i class="fas fa-check"></i>';
                                } else {
                                    echo '<i class="fas fa-times"></i>'; }
                                ?>
                            </div>
                            <p class="af2_checklist_text"><?= $healthcheck["label"] ?></p>
                        </div>

                <?php } ?>
            </div>
        </div>
    </div>
    <div id="video_card" class="af2_card">
        <div class="af2_card_block">
            <div class="af2_card_heading">
                <div class="af2_icon_wrapper colorPrimary"><i class="fab fa-youtube"></i></div>
                <div>
                    <h4><?php _e('Introduction', 'funnelforms-free'); ?></h4>
                    <p><?php _e('Watch the Quick Start Guide to get a better insight into the setup.', 'funnelforms-free'); ?></p>
                </div>
            </div>
            <div class="af2_card_content">
            <?php
                    $quickstartguide_url = "https://player.vimeo.com/video/782961075?badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479";
                    $locale = get_locale();
                    if($locale == 'de_DE') {
                        $quickstartguide_url = "https://player.vimeo.com/video/781786834?badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479";
                    } 
                ?>
                <iframe src="<?php echo $quickstartguide_url ?>" width="100%" height="100%" frameborder="0" allowfullscreen="allowfullscreen"></iframe>
            </div>
        </div>
    </div>
</div>