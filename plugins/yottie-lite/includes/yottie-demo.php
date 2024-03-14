<div class="yottie-demo">
    <form class="yottie-demo-form">
        <input class="yottie-demo-form-api-key" type="hidden" name="key" value="<?php echo $api_key; ?>">

        <div class="yottie-demo-accordion">
            <div class="yottie-demo-accordion-item-active yottie-demo-accordion-item">
                <div class="yottie-demo-accordion-item-trigger"><?php _e('Source', YOTTIE_LITE_TEXTDOMAIN); ?></div>

                <div class="yottie-demo-accordion-item-content">
                    <div class="yottie-demo-channel yottie-demo-field-group">
                        <div class="yottie-demo-field-group-name"><?php _e('YouTube channel URL', YOTTIE_LITE_TEXTDOMAIN); ?></div>

                        <div class="yottie-demo-field-group-description">
                            <?php _e('Insert URL of a YouTube channel to display its information and videos in the plugin.', YOTTIE_LITE_TEXTDOMAIN); ?>
                        </div>

                        <div class="yottie-demo-field">
                            <input class="yottie-demo-text-input" type="text" name="channel" placeholder="<?php _e('Add a channel URL', YOTTIE_LITE_TEXTDOMAIN); ?>">
                        </div>
                    </div>

                    <div class="yottie-demo-source-groups yottie-demo-field-group">
                        <input type="hidden" name="sourceGroups" value="">

                        <div class="yottie-demo-field-group-name">
                            <?php _e('Source groups', YOTTIE_LITE_TEXTDOMAIN); ?>

                            <span class="yottie-demo-tooltip">
                                <span class="yottie-demo-tooltip-trigger">?</span>

                                <span class="yottie-demo-tooltip-content">
                                    <span class="yottie-demo-tooltip-content-inner">
                                        <?php _e('Create custom video playlists from unlimited combinations of YouTube sources to display them instead of videos from the specified channel above in the plugin.', YOTTIE_LITE_TEXTDOMAIN); ?>
                                    </span>
                                </span>
                            </span>
                        </div>

                        <div class="yottie-demo-field-group-description">
                            <?php _e('Organize videos in your channel or create custom groups of videos from any combination of YouTube sources (channels, playlists, videos).', YOTTIE_LITE_TEXTDOMAIN); ?>
                        </div>

                        <div class="yottie-demo-source-groups-items"></div>

                        <button class="yottie-demo-source-groups-add">
                            <span class="yottie-demo-icon-plus-white-medium yottie-demo-icon"></span>
                            <span class="yottie-demo-source-groups-add-label"><?php _e('Add group', YOTTIE_LITE_TEXTDOMAIN); ?></span>
                        </button>

                        <a class="yottie-demo-source-groups-add-limit-message" href="<?php echo YOTTIE_LITE_PRO_URL; ?>" target="_blank">
                            <?php _e('Add more source groups by updating Yottie to Pro', YOTTIE_LITE_TEXTDOMAIN); ?>

                            <svg class="yottie-demo-svg-arrow-more">
                                <line x1="0" y1="0" x2="4" y2="4"></line>
                                <line x1="0" y1="8" x2="4" y2="4"></line>
                            </svg>
                        </a>

                        <template class="yottie-demo-template-source-group yottie-demo-template">
                            <div class="yottie-demo-source-groups-item">
                                <div class="yottie-demo-source-groups-item-name yottie-demo-field">
                                    <div class="yottie-demo-field-name">
                                        <?php _e('Group name', YOTTIE_LITE_TEXTDOMAIN); ?>

                                        <span class="yottie-demo-tooltip">
                                            <span class="yottie-demo-tooltip-trigger">?</span>

                                            <span class="yottie-demo-tooltip-content">
                                                <span class="yottie-demo-tooltip-content-inner">
                                                    <?php _e('Give a name to your custom video group. It will be displayed in tabs. If you leave it empty, "Untitled" name will be set.', YOTTIE_LITE_TEXTDOMAIN); ?>
                                                </span>
                                            </span>
                                        </span>
                                    </div>

                                    <label>
                                        <input type="text" name="sourceGroupName">
                                    </label>
                                </div>

                                <div class="yottie-demo-field">
                                    <div class="yottie-demo-field-name">
                                        <?php _e('Sources (channels, playlists, videos)', YOTTIE_LITE_TEXTDOMAIN); ?>
                                    </div>

                                    <div class="yottie-demo-source-groups-item-sources"></div>
                                </div>

                                <div class="yottie-demo-source-groups-item-remove">
                                    <span class="yottie-demo-icon-trash-white-small yottie-demo-icon"></span>
                                    <span class="yottie-demo-source-groups-item-remove-label"><?php _e('Delete group', YOTTIE_LITE_TEXTDOMAIN); ?></span>
                                </div>
                            </div>
                        </template>

                        <template class="yottie-demo-template-source-group-source yottie-demo-template">
                            <div class="yottie-demo-source-groups-item-sources-item">
                                <input type="text" name="sourceGroupSources[]" placeholder="<?php _e('Add a YouTube source URL', YOTTIE_LITE_TEXTDOMAIN); ?>">

                                <div class="yottie-demo-source-groups-item-sources-item-remove" title="<?php _e('Remove this source', YOTTIE_LITE_TEXTDOMAIN); ?>">
                                    <span class="yottie-demo-icon-remove-white-small yottie-demo-icon"></span>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="yottie-demo-field-group">
                        <div class="yottie-demo-cache-time yottie-demo-field">
                            <div class="yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Сache time', YOTTIE_LITE_TEXTDOMAIN); ?></div>

                            <span class="yottie-demo-range-container">
                                <input class="yottie-demo-range-input" type="text" name="cacheTime">
                                <span class="yottie-demo-range" data-min="0" data-step="100" data-max="86400"></span>
                            </span>

                            <span class="yottie-demo-field-hint"><?php _e('s', YOTTIE_LITE_TEXTDOMAIN); ?></span>

                            <span class="yottie-demo-tooltip">
                                <span class="yottie-demo-tooltip-trigger">?</span>

                                <span class="yottie-demo-tooltip-content">
                                    <span class="yottie-demo-tooltip-content-inner">
                                        <?php _e('It defines how long in seconds a data from YouTube will be cached in a client side database IndexedDB. Set "0" to turn the cache off.', YOTTIE_LITE_TEXTDOMAIN); ?>
                                    </span>
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="yottie-demo-accordion-item">
                <div class="yottie-demo-accordion-item-trigger"><?php _e('Sizes', YOTTIE_LITE_TEXTDOMAIN); ?></div>

                <div class="yottie-demo-accordion-item-content">
                    <div class="yottie-demo-width yottie-demo-field-group-disabled yottie-demo-field-group">
                        <img src="<?php echo plugins_url('assets/img/pro-option-disabled-width.jpg', YOTTIE_LITE_FILE); ?>">

                        <a class="yottie-demo-field-available-in-pro" href="<?php echo YOTTIE_LITE_PRO_URL; ?>" target="_blank">Available in Pro version</a>
                    </div>
                </div>
            </div>

            <div class="yottie-demo-accordion-item">
                <div class="yottie-demo-accordion-item-trigger"><?php _e('Language', YOTTIE_LITE_TEXTDOMAIN); ?></div>

                <div class="yottie-demo-accordion-item-content">
                    <div class="yottie-demo-lang yottie-demo-field-group-disabled yottie-demo-field-group">
                        <img src="<?php echo plugins_url('assets/img/pro-option-disabled-lang.jpg', YOTTIE_LITE_FILE); ?>">

                        <a class="yottie-demo-field-available-in-pro" href="<?php echo YOTTIE_LITE_PRO_URL; ?>" target="_blank">Available in Pro version</a>
                    </div>
                </div>
            </div>

            <div class="yottie-demo-accordion-item">
                <div class="yottie-demo-accordion-item-trigger"><?php _e('Header', YOTTIE_LITE_TEXTDOMAIN); ?></div>

                <div class="yottie-demo-accordion-item-content">
                    <div class="yottie-demo-header-visible yottie-demo-field-group">
                        <label>
                            <input type="hidden" name="headerVisible" value="false">
                            <input class="yottie-demo-checkbox" type="checkbox" name="headerVisible" value="true">
                            <span class="yottie-demo-checkbox-label"><?php _e('Show header', YOTTIE_LITE_TEXTDOMAIN); ?></span>
                        </label>
                    </div>

                    <div class="yottie-demo-header-pro yottie-demo-field-group-disabled yottie-demo-field-group">
                        <img src="<?php echo plugins_url('assets/img/pro-option-disabled-header.jpg', YOTTIE_LITE_FILE); ?>">

                        <a class="yottie-demo-field-available-in-pro" href="<?php echo YOTTIE_LITE_PRO_URL; ?>" target="_blank">Available in Pro version</a>
                    </div>
                </div>
            </div>

            <div class="yottie-demo-accordion-item">
                <div class="yottie-demo-accordion-item-trigger"><?php _e('Groups', YOTTIE_LITE_TEXTDOMAIN); ?></div>

                <div class="yottie-demo-accordion-item-content">
                    <div class="yottie-demo-groups-visible yottie-demo-field-group">
                        <label>
                            <input type="hidden" name="groupsVisible" value="false">
                            <input class="yottie-demo-checkbox" type="checkbox" name="groupsVisible" value="true">
                            <span class="yottie-demo-checkbox-label"><?php _e('Show groups', YOTTIE_LITE_TEXTDOMAIN); ?></span>

                            <span class="yottie-demo-tooltip">
                                <span class="yottie-demo-tooltip-trigger">?</span>

                                <span class="yottie-demo-tooltip-content">
                                    <span class="yottie-demo-tooltip-content-inner">
                                        <?php _e('Show or hide the tabs navigation in groups.', YOTTIE_LITE_TEXTDOMAIN); ?>
                                    </span>
                                </span>
                            </span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="yottie-demo-accordion-item">
                <div class="yottie-demo-accordion-item-trigger"><?php _e('Content', YOTTIE_LITE_TEXTDOMAIN); ?></div>

                <div class="yottie-demo-accordion-item-content">
                    <div class="yottie-demo-grid yottie-demo-field-group">
                        <div class="yottie-demo-field-group-name"><?php _e('Grid', YOTTIE_LITE_TEXTDOMAIN); ?></div>

                        <div class="yottie-demo-field">
                            <label class="yottie-demo-columns">
                                <span class="yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Columns', YOTTIE_LITE_TEXTDOMAIN); ?></span>

                                <div class="yottie-demo-numeric" data-min="1">
                                    <div class="yottie-demo-numeric-decrease"></div>
                                    <input type="text" name="contentColumns" autocomplete="off">
                                    <div class="yottie-demo-numeric-increase"></div>
                                </div>
                            </label>
                        </div>

                        <div class="yottie-demo-content-rows yottie-demo-field-disabled yottie-demo-field">
                            <img src="<?php echo plugins_url('assets/img/pro-option-disabled-rows-gutter.jpg', YOTTIE_LITE_FILE); ?>">

                            <a class="yottie-demo-field-available-in-pro" href="<?php echo YOTTIE_LITE_PRO_URL; ?>" target="_blank">Available in Pro version</a>
                        </div>
                    </div>

                    <div class="yottie-demo-field-group">
                        <div class="yottie-demo-controls yottie-demo-field-col-1-2">
                            <div class="yottie-demo-field">
                                <div class="yottie-demo-field-name">
                                    <?php _e('Navigation controls', YOTTIE_LITE_TEXTDOMAIN); ?>
                                </div>

                                <label class="yottie-demo-controls-item">
                                    <input type="hidden" name="contentArrowsControl" value="false">
                                    <input class="yottie-demo-checkbox" type="checkbox" name="contentArrowsControl" value="true">
                                    <span class="yottie-demo-icon-control-arrow-white yottie-demo-icon"></span>
                                    <span class="yottie-demo-icon-control-arrow-black yottie-demo-icon-active yottie-demo-icon"></span>
                                    <span class="yottie-demo-controls-item-label"><?php _e('Arrows', YOTTIE_LITE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-controls-item">
                                    <input type="hidden" name="contentDragControl" value="false">
                                    <input class="yottie-demo-checkbox" type="checkbox" name="contentDragControl" value="true">
                                    <span class="yottie-demo-icon-control-drag-white yottie-demo-icon"></span>
                                    <span class="yottie-demo-icon-control-drag-black yottie-demo-icon-active yottie-demo-icon"></span>
                                    <span class="yottie-demo-controls-item-label"><?php _e('Drag', YOTTIE_LITE_TEXTDOMAIN); ?></span>
                                </label>
                            </div>
                        </div>

                        <div class="yottie-demo-field-col-1-4">
                            <div class="yottie-demo-content-scroll-control yottie-demo-field-disabled yottie-demo-field">
                                <img src="<?php echo plugins_url('assets/img/pro-option-disabled-scroll-control.jpg', YOTTIE_LITE_FILE); ?>">
                            </div>
                        </div>

                        <div class="yottie-demo-field-col-1-4">
                            <div class="yottie-demo-free-mode yottie-demo-field-disabled yottie-demo-field">
                                <img src="<?php echo plugins_url('assets/img/pro-option-disabled-free-mode-scrollbar.jpg', YOTTIE_LITE_FILE); ?>">
                            </div>

                            <a class="yottie-demo-field-available-in-pro" href="<?php echo YOTTIE_LITE_PRO_URL; ?>" target="_blank">Available in Pro version</a>
                        </div>
                    </div>

                    <div class="yottie-demo-field-group">
                        <div class="yottie-demo-content-transition-effect yottie-demo-field">
                            <div class="yottie-demo-field-name"><?php _e('Animation effect', YOTTIE_LITE_TEXTDOMAIN); ?></div>

                            <div class="yottie-demo-multiswitch">
                                <label class="yottie-demo-multiswitch-item">
                                    <input type="radio" name="contentTransitionEffect" value="slide" checked>
                                    <span class="yottie-demo-multiswitch-item-label"><?php _e('Slide', YOTTIE_LITE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-multiswitch-item">
                                    <input type="radio" name="contentTransitionEffect" value="fade">
                                    <span class="yottie-demo-multiswitch-item-label"><?php _e('Fade', YOTTIE_LITE_TEXTDOMAIN); ?></span>
                                </label>

                                <span class="yottie-demo-content-transition-effect-pro">
                                    <img src="<?php echo plugins_url('assets/img/pro-option-disabled-transition-effect.jpg', YOTTIE_LITE_FILE); ?>">
                                </span>
                            </div>
                        </div>

                        <a class="yottie-demo-field-available-in-pro" href="<?php echo YOTTIE_LITE_PRO_URL; ?>" target="_blank">Available in Pro version</a>
                    </div>

                    <div class="yottie-demo-field-group">
                        <div class="yottie-demo-speed yottie-demo-field">
                            <div class="yottie-demo-field-name"><?php _e('Animation speed', YOTTIE_LITE_TEXTDOMAIN); ?></div>

                            <span class="yottie-demo-range-container">
                                <input class="yottie-demo-range-input" type="text" name="contentTransitionSpeed">
                                <span class="yottie-demo-range" data-min="0" data-step="100" data-max="3000"></span>
                            </span>

                            <span class="yottie-demo-field-hint"><?php _e('ms', YOTTIE_LITE_TEXTDOMAIN); ?></span>
                        </div>
                    </div>

                    <div class="yottie-demo-field-group">
                        <div class="yottie-demo-field-col-1-2">
                            <div class="yottie-demo-auto yottie-demo-field">
                                <div class="yottie-demo-field-name"><?php _e('Autorotation', YOTTIE_LITE_TEXTDOMAIN); ?></div>

                                <span class="yottie-demo-range-container">
                                    <input class="yottie-demo-range-input" type="text" name="contentAuto">
                                    <span class="yottie-demo-range" data-min="0" data-step="100" data-max="10000"></span>
                                </span>

                                <span class="yottie-demo-field-hint"><?php _e('ms', YOTTIE_LITE_TEXTDOMAIN); ?></span>
                            </div>
                        </div>

                        <div class="yottie-demo-field-col-1-2">
                            <div class="yottie-demo-auto-hover-pause yottie-demo-field">
                                <label>
                                    <input type="hidden" name="contentAutoPauseOnHover" value="false">
                                    <input class="yottie-demo-checkbox" type="checkbox" name="contentAutoPauseOnHover" value="true">
                                    <span class="yottie-demo-checkbox-label"><?php _e('Pause on hover', YOTTIE_LITE_TEXTDOMAIN); ?></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="yottie-demo-content-direction yottie-demo-field-group-disabled yottie-demo-field-group">
                        <img src="<?php echo plugins_url('assets/img/pro-option-disabled-direction.jpg', YOTTIE_LITE_FILE); ?>">

                        <a class="yottie-demo-field-available-in-pro" href="<?php echo YOTTIE_LITE_PRO_URL; ?>" target="_blank">Available in Pro version</a>
                    </div>

                    <div class="yottie-demo-content-responsive yottie-demo-field-group-disabled yottie-demo-field-group">
                        <img src="<?php echo plugins_url('assets/img/pro-option-disabled-responsive.jpg', YOTTIE_LITE_FILE); ?>">
                    </div>
                </div>
            </div>

            <div class="yottie-demo-accordion-item">
                <div class="yottie-demo-accordion-item-trigger"><?php _e('Video', YOTTIE_LITE_TEXTDOMAIN); ?></div>

                <div class="yottie-demo-accordion-item-content">
                    <div class="yottie-demo-field-group-disabled yottie-demo-field-group">
                        <img src="<?php echo plugins_url('assets/img/pro-option-disabled-video.jpg', YOTTIE_LITE_FILE); ?>">

                        <a class="yottie-demo-field-available-in-pro" href="<?php echo YOTTIE_LITE_PRO_URL; ?>" target="_blank">Available in Pro version</a>
                    </div>
                </div>
            </div>

            <div class="yottie-demo-accordion-item">
                <div class="yottie-demo-accordion-item-trigger"><?php _e('Popup', YOTTIE_LITE_TEXTDOMAIN); ?></div>

                <div class="yottie-demo-accordion-item-content">
                    <div class="yottie-demo-field-group-disabled yottie-demo-field-group">
                        <img src="<?php echo plugins_url('assets/img/pro-option-disabled-popup.jpg', YOTTIE_LITE_FILE); ?>">

                        <a class="yottie-demo-field-available-in-pro" href="<?php echo YOTTIE_LITE_PRO_URL; ?>" target="_blank">Available in Pro version</a>
                    </div>
                </div>
            </div>

            <div class="yottie-demo-accordion-item">
                <div class="yottie-demo-accordion-item-trigger"><?php _e('Colors', YOTTIE_LITE_TEXTDOMAIN); ?></div>

                <div class="yottie-demo-accordion-item-content">
                    <div class="yottie-demo-field-group-disabled yottie-demo-field-group">
                        <img src="<?php echo plugins_url('assets/img/pro-option-disabled-color.jpg', YOTTIE_LITE_FILE); ?>">

                        <a class="yottie-demo-field-available-in-pro" href="<?php echo YOTTIE_LITE_PRO_URL; ?>" target="_blank">Available in Pro version</a>
                    </div>
                </div>
            </div>

            <div class="yottie-demo-shortcode-container yottie-demo-accordion-item-detached yottie-demo-accordion-item">
                <div class="yottie-demo-accordion-item-trigger"><span class="yottie-demo-icon-shortcode yottie-demo-icon"></span><span><?php _e('Get Shortcode', YOTTIE_LITE_TEXTDOMAIN); ?></span></div>

                <div class="yottie-demo-accordion-item-content">
                    <p><?php _e('Copy this shortcode and paste it into any page or post.', YOTTIE_LITE_TEXTDOMAIN); ?></p>
                    <textarea class="yottie-demo-shortcode" spellcheck="false" readonly></textarea>
                </div>
            </div>
        </div>
    </form>

    <div class="yottie-demo-preview-container">
        <div class="yottie-demo-preview"></div>
    </div>
</div>