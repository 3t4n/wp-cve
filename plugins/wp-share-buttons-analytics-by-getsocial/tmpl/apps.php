<div class="app-grid">
    <?php $i = 0; ?>
    <?php if (false) {
        $boxes_array = $plan_class;
    } else {
        $boxes_array = $plan_categories;
    } ?>
    <?php foreach ($boxes_array as $cat) { ?>
        <?php $count = 0; ?>
        <?php
            foreach ($apps as $app => $settings) {
                if ($settings['category'] == $cat) {
                    $count++;
                }
                if ($settings['plan'] == $cat) {
                    $count++;
                }
            }
        ?>
        <div class="app-group active">
            <div class="app-group-title">
                <h2><?php echo $plan_categories_name[$i] ?> <span>(<?php echo $count; echo $count > 1 ? ' Apps' : ' App' ?>)</span></h2>
                <a class="app-group-toggle" href="javascript:void(0)">
                    <i class="fa fa-minus-square"></i><span>Open</span>
                </a>
            </div>
            <div class="app-group-body gs-clearfix">
                <?php foreach($apps as $app => $settings) { ?>
                    <?php $current_box = $settings['category']; ?>
                    <?php if($current_box == $cat) { ?>
                        <div class="app-link-wrapper filter-<?php echo $settings['plan']; ?> filter-<?php echo $settings['category']; ?>">
                            <div class="app-link">
                                <div class="app-badge-group">
                                    <?php if ($settings['new']) { ?>
                                        <div class="app-badge new">New</div>
                                    <?php } ?>
                                    <?php if ($plan_is_free && $settings['plan'] != 'zero')  { ?>
                                        <div class="app-badge plan-<?php echo $settings['plan']; ?>">
                                            <?php echo plan_name($settings['plan']); ?>
                                        </div>
                                    <?php } ?>  
                                </div>
                                <div class="app-image">
                                    <?php if ($settings['active']) { ?>
                                        <div class="installed">
                                            <span>
                                                <i class="fa fa-check-circle"></i>
                                                Installed
                                            </span>
                                        </div>
                                    <?php } ?>
                                    <img src="<?php echo plugins_url('../img/apps/'.$settings['file'].'.png', __FILE__) ?>" alt="">
                                </div>
                                <div class="app-link-info">
                                    <p class="app-title"><span><?php echo $app ?></span></p>
                                    <p><?php echo $settings['desc'] ?></p>
                                </div>
                                <div class="app-link-buttons">
                                    <div>
                                        <?php if (!$settings['only_activate'] || (isset($settings['only_activate']) && !$settings['active'])) { ?>
                                            <?php // Show modals when user is free and apps are activate only ?>
                                            <?php if (!$GS->is_pro() && $settings['only_activate']) { ?>
                                                <?php if ($app == 'Copy Paste Share Tracking') { ?>
                                                    <a id="install-copy-and-share" href="#" class="gs-button gs-primary trans border getsocial-tab only-activate">
                                                        Install App
                                                    </a>
                                                <?php } ?>
                                                <?php if ($app == 'Google Analytics' || $app == 'MailChimp') { ?>
                                                    <a id="install-<?php echo $settings['file'] ?>" href="#" class="gs-button gs-primary trans border getsocial-tab only-activate">
                                                        Install App
                                                    </a>
                                                <?php } ?>
                                            <?php } elseif ($app == 'Hello Buddy' && $settings['active'] == 'active') { ?>
                                                <a href="<?php echo $GS->gs_account(); ?>/sites/gs-wordpress?edit-hello-buddy<?php echo '&api_key=' . $GS->api_key . '&amp;source=wordpress' . $GS->utms('hello_buddies') ?>" target="_blank" class="gs-button gs-primary trans border getsocial-tab">
                                                    Edit App
                                                </a>
                                            <?php // Non Free users can install everything ?>
                                            <?php } else { ?>
                                                <?php
                                                // Prevent instalation of mailchimp app without Subscriber Bar
                                                $prevent_install = "";

                                                if ($app == 'MailChimp' && !$GS->has_subscriptions()) {
                                                  $prevent_install = 'prevent="true"';
                                                } ?>
                                                <a href="<?php echo $settings['href'] ?>" target="<?php echo ($settings['only_activate'] && $app != 'MailChimp' ? '' : '_blank') ?>" class="gs-button gs-primary trans border getsocial-tab <?php echo ($settings['only_activate'] ? 'only-activate' : ''); ?>" <?php echo $prevent_install; ?>>
                                                  <?php echo ($settings['active']) ? 'Edit App' : 'Install App' ?>
                                                </a>
                                            <?php } ?>
                                        <?php } ?>

                                        <?php if ($settings['active'] && $settings['file'] != "hello_buddy") { ?>
                                            <a href="javascript:void(0)" class="gs-button disable trans border stop deactivate" data-disable-app="<?php echo $GS->api_url('sites/disable/'.get_option('gs-api-key').'/'.$settings['file']) ?>">
                                                Deactivate
                                            </a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>

    <?php $i++; ?>
    <?php } ?>
</div>
