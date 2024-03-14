<?php
RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('bootstrap-reboot');
RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('bootstrap');
RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('fontawesome');
RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('global');
RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('navbar');

//Get the current page and tab and check if the option is active
$features = RKMW_Classes_ObjController::getClass('RKMW_Core_BlockFeatures')->getFeatures();
$page = str_replace(strtolower(RKMW_NAMESPACE) . '_', '', RKMW_Classes_Helpers_Tools::getValue('page'));
$tab = RKMW_Classes_Helpers_Tools::getValue('tab', 'rankings');

//Check if the menu is called with slash tab
$menu = $page . '/' . $tab;

foreach ($features as $index => $feature) {
    if ($menu <> $feature['menu']) {
        continue;
    }

    $color = 'text-info';
    $background_color = '#fff';
    $opacity = '1';
    switch (strtolower($feature['mode'])) {
        case 'free':
            $color = 'text-success';
            $background_color = '#f0fff5';
            break;
        case 'freemium':
            $color = 'text-info';
            $background_color = '#f0fff5';
            break;
        case 'pro':
        case 'business':
            $color = 'text-warning';
            $background_color = '#fff6f64d';
            break;
    }
    if (is_bool($feature['option']) && !$feature['option']) {
        $background_color = '#f5f5f5';
        $opacity = '0.5';
    } elseif (is_string($feature['option'])) {
        $background_color = '#dfdede';
    }
?>
<div id="rkmw_wrap">
    <?php RKMW_Classes_ObjController::getClass('RKMW_Core_BlockToolbar')->init(); ?>
    <?php do_action('rkmw_notices'); ?>
    <div class="d-flex flex-row my-0 bg-white">
        <div class="d-flex flex-row flex-nowrap flex-grow-1 bg-white pl-1 pr-1 mr-0">
            <div class="flex-grow-1 mr-2 rkmw_flex">

                <div class="col-sm-12 pt-3">
                    <div class="col-sm-12 text-center m-0 p-3">
                        <h5><?php echo esc_html__("The option is not yet activated in Rank My WP plugin.", RKMW_PLUGIN_NAME) ?></h5>
                        <h5><?php echo esc_html__("Activate it and load it.", RKMW_PLUGIN_NAME) ?></h5>
                    </div>

                    <div class="col d-flex justify-content-center">
                        <div class="card p-3 shadow-sm bg-light">
                            <div class="card-body m-0 p-0">
                                <div class="row px-3 pb-3">
                                    <div class="col-sm-1 p-0">
                                        <img src="<?php echo RKMW_ASSETS_URL . 'img/logos/' . $feature['logo'] ?>" style="width: 24px; vertical-align: middle;" alt="">
                                    </div>

                                    <div class="col-sm-11 p-0 pl-3">
                                        <h5> <?php echo wp_kses_post($feature['title']) ?></h5>
                                    </div>


                                </div>
                                <div class="my-2 text-black" style="min-height: 85px;"><?php echo wp_kses_post($feature['description']) ?></div>

                                <?php if (is_bool($feature['option']) && isset($feature['menu']) && $feature['menu'] && current_user_can('rkmw_manage_settings')) { ?>
                                    <?php if (!$feature['option']) { ?>
                                        <div class="col-sm-12 text-center p-0 m-0">
                                            <form method="post" class="p-0 m-0">
                                                <?php RKMW_Classes_Helpers_Tools::setNonce('rkmw_settings_feature', 'rkmw_nonce'); ?>
                                                <input type="hidden" name="action" value="rkmw_settings_feature"/>
                                                <input type="hidden" name="menu[<?php echo $feature['menu'] ?>]" value="1"/>
                                                <button type="submit" class="btn btn-success px-4">
                                                    <?php echo esc_html__("Activate Option", RKMW_PLUGIN_NAME) ?>
                                                </button>
                                            </form>
                                        </div>
                                    <?php } ?>
                                <?php } ?>

                                <div class="row my-0 px-3 pt-4 pb-0">
                                    <div class="col-sm-6 p-0 m-0 text-left">
                                        <?php if ($feature['option'] === 'na' && $feature['link']) { ?>
                                            <strong class="text-info"><a href="<?php echo esc_url($feature['link']) ?>" target="_blank"><?php echo esc_html__("Go to feature", RKMW_PLUGIN_NAME) ?></a></strong>
                                        <?php } elseif (is_bool($feature['option']) && $feature['option'] && $feature['link']) { ?>
                                            <strong class="text-info"><a href="<?php echo esc_url($feature['link']) ?>"><?php echo esc_html__("Go to feature", RKMW_PLUGIN_NAME) ?></a></strong>
                                        <?php } elseif (is_bool($feature['option']) && !$feature['option']) { ?>
                                            <span class="text-black-50"><?php echo esc_html__("Inactive", RKMW_PLUGIN_NAME) ?></span>
                                        <?php } elseif($feature['link']) { ?>
                                            <strong class="text-black-50"><a href="<?php echo esc_url($feature['link']) ?>" target="_blank"><?php echo (string)$feature['option'] ?></a></strong>
                                        <?php } else { ?>
                                            <strong class="text-black-50"><?php echo (string)$feature['option'] ?></strong>
                                        <?php } ?>
                                    </div>
                                    <div class="col-sm-6 p-0 m-0 text-right"> </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</div>
<?php }?>