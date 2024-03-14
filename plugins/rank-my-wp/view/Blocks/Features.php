<a name="features"></a>
<div class="rkmw_features border my-1 py-3">
    <div class="row text-left m-0 p-4">
        <div class="col-md-4 px-2 text-center">
            <img src="<?php echo RKMW_ASSETS_URL . 'img/rkmw_logo.jpg' ?>" style="width: 100%; max-width: 230px;" alt="">
        </div>
        <div class="col-md px-2 py-0">
            <div class="col-sm-12 mx-0 mt-5 mb-3 p-0">
                <h2><?php echo sprintf(esc_html__("%sManage%s Rank My WP Features", RKMW_PLUGIN_NAME), '<span class="text-info">', '</span>') ?></h2>
            </div>
            <div class="rkmw_separator m-0 p-0"></div>
            <div class="col-sm-12 m-0 p-0">
                <div class="my-2"><?php echo esc_html__("Activate or deactivate the features you want to use in Rank My WP.", RKMW_PLUGIN_NAME) ?></div>
            </div>
        </div>
    </div>

    <div class="row row-cols-1 row-cols-md-3 px-1 mx-1">
        <?php foreach ($view->features as $index => $feature) { ?>
            <?php
            $opacity = '1';
            $color = 'text-success';
            $background_color = '#fff';

            if (is_bool($feature['option']) && !$feature['option']) {
                $background_color = '#f5f5f5';
                $opacity = '0.5';
            } elseif (is_string($feature['option'])) {
                $background_color = '#dfdede';
            }
            ?>

            <div class="col px-1 mx-0 mb-2">
                <div class="card h-100 p-0 shadow-0 rounded-0" style="background-color: <?php echo esc_attr($background_color) ?>">
                    <div class="card-body m-0 p-0">
                        <div class="row mx-3 my-4" style="opacity: <?php echo $opacity ?>;">
                            <div class="col-1 p-0">
                                <img src="<?php echo RKMW_ASSETS_URL . 'img/logos/' . $feature['logo'] ?>" style="width: 30px; vertical-align: middle;" alt="">
                            </div>

                            <div class="col-11 p-0 pl-3">
                                <h5>
                                    <?php echo wp_kses_post($feature['title']) ?>
                                </h5>
                            </div>


                        </div>
                        <div class="mx-3 my-4 text-black" style="min-height: 105px; font-size: 16px; opacity: <?php echo $opacity ?>;">
                            <?php echo wp_kses_post($feature['description']) ?>
                            <div class="my-2 text-warning"><?php echo($feature['dependency'] ? esc_html__('Dependency') . ': ' . wp_kses_post($feature['dependency']) : '') ?></div>
                        </div>

                    </div>
                    <div class="card-footer p-0">
                        <div class="row m-0">
                            <div class="col-6 p-2 m-0 align-middle text-left" style="line-height: 30px">
                                <?php if ($feature['option'] === 'na' && $feature['link']) { ?>
                                    <strong class="text-info"><a href="<?php echo esc_url($feature['link']) ?>" target="_blank"><?php echo esc_html__("Go to feature", RKMW_PLUGIN_NAME) ?></a></strong>
                                <?php } elseif (is_bool($feature['option']) && $feature['option'] && $feature['link']) { ?>
                                    <strong class="text-info"><a href="<?php echo esc_url($feature['link']) ?>"><?php echo esc_html__("Go to feature", RKMW_PLUGIN_NAME) ?></a></strong>
                                <?php } elseif (is_bool($feature['option']) && !$feature['option']) { ?>
                                <?php } elseif ($feature['link']) { ?>
                                    <strong class="text-black-50"><a href="<?php echo esc_url($feature['link']) ?>" target="_blank"><?php echo (string)$feature['option'] ?></a></strong>
                                <?php } else { ?>
                                    <strong class="text-black-50"><?php echo (string)$feature['option'] ?></strong>
                                <?php } ?>
                            </div>
                            <div class="col-6 p-2 m-0  text-right">
                                <?php if (isset($feature['menu']) && $feature['menu'] && current_user_can('rkmw_manage_settings')) { ?>
                                    <?php if ($feature['option']) { ?>
                                        <div class="col-sm-12 text-right p-0 m-0">
                                            <form method="post" class="p-0 m-0">
                                                <?php RKMW_Classes_Helpers_Tools::setNonce('rkmw_settings_feature', 'rkmw_nonce'); ?>
                                                <input type="hidden" name="action" value="rkmw_settings_feature"/>
                                                <input type="hidden" name="menu[<?php echo $feature['menu'] ?>]" value="0"/>
                                                <button type="submit" class="btn btn-sm btn-light px-3">
                                                    <?php echo esc_html__("Deactivate", RKMW_PLUGIN_NAME) ?>
                                                </button>
                                            </form>
                                        </div>
                                    <?php } else { ?>
                                        <div class="col-sm-12 text-right p-0 m-0">
                                            <form method="post" class="p-0 m-0">
                                                <?php RKMW_Classes_Helpers_Tools::setNonce('rkmw_settings_feature', 'rkmw_nonce'); ?>
                                                <input type="hidden" name="action" value="rkmw_settings_feature"/>
                                                <input type="hidden" name="menu[<?php echo $feature['menu'] ?>]" value="1"/>
                                                <button type="submit" class="btn btn-sm btn-success px-3">
                                                    <?php echo esc_html__("Activate", RKMW_PLUGIN_NAME) ?>
                                                </button>
                                            </form>
                                        </div>
                                    <?php } ?>
                                <?php } ?>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        <?php } ?>

    </div>



    <div class="text-left mx-0 mt-5 p-3">
        <h2><?php echo esc_html__("Looking for more awesome features? ", RKMW_PLUGIN_NAME) ?></h2>
        <h4 class="text-black-50 my-3"><?php echo sprintf(esc_html__("Install %sSquirrly SEO%s plugin and enable free features like", RKMW_PLUGIN_NAME), '<a href="https://wordpress.org/plugins/squirrly-seo/" target="_blank">', '</a>') ?>:</h4>
    </div>
    <a href="https://wordpress.org/plugins/squirrly-seo/" target="_blank"><img src="<?php echo RKMW_ASSETS_URL . 'img/squirrly_features.jpg' ?>" style="width: 100%; opacity: 0.5;" alt=""></a>

</div>