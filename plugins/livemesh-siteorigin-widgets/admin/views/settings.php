<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

$theme_color = lsow_get_option('lsow_theme_color', '#f94213');

$theme_hover_color = lsow_get_option('lsow_theme_hover_color', '#888888');

$autoload_widgets = lsow_get_option('lsow_autoload_widgets', false);

$debug_mode = lsow_get_option('lsow_enable_debug', false);

$custom_css = lsow_get_option('lsow_custom_css', '');

?>

<div class="lsow-settings">

    <div class="postbox">

        <!-------------------
        OPTIONS HOLDER START
        -------------------->
        <div class="lsow-menu-options settings-options">

            <div class="lsow-inner">

                <!-------------------  LI TABS -------------------->

                <ul class="lsow-tabs-wrap">
                    <li class="lsow-tab selected" data-target="general"><i
                            class="lsow-icon dashicons dashicons-admin-generic"></i><?php echo __('General', 'livemesh-so-widgets') ?>
                    </li>
                    <li class="lsow-tab" data-target="custom-css"><i
                            class="lsow-icon dashicons dashicons-editor-code"></i><?php echo __('Custom CSS', 'livemesh-so-widgets') ?>
                    </li>
                    <li class="lsow-tab" data-target="debugging"><i
                            class="lsow-icon dashicons dashicons-warning"></i><?php echo __('Debugging', 'livemesh-so-widgets') ?>
                    </li>
                    <li class="lsow-tab" data-target="premium-version"><i
                            class="lsow-icon dashicons dashicons-yes"></i><?php echo __('Premium Version', 'livemesh-so-widgets') ?>
                    </li>
                </ul>

                <!-------------------  GENERAL TAB -------------------->

                <div class="lsow-tab-content general lsow-tab-show">

                    <!---- Theme Colors -->
                    <div class="lsow-box-side">
                        <h3><?php echo __('Theme Colors', 'livemesh-so-widgets') ?></h3>
                    </div>
                    <div class="lsow-inner lsow-box-inner">
                        <div class="lsow-row lsow-field">
                            <label
                                class="lsow-label"><?php echo __('Theme Color Scheme', 'livemesh-so-widgets') ?></label>
                            <p class="lsow-desc"><?php echo __('Most themes use a single color as a major color across the site. This color is often used for links, titles, buttons, icons, highlights etc. <br> To maintain the consistent look with the theme, specify the default color used by the theme activated on your site. This color will be applied to the plugin widgets by default. <br>The hover color refers to the color set for links on mouse hover.', 'livemesh-so-widgets') ?></p>
                        </div>

                        <div class="lsow-clearfix"></div>

                        <!---- Theme color -->
                        <div class="lsow-row lsow-field lsow-type-color">
                            <label class="lsow-label"><?php echo __('Theme Color', 'livemesh-so-widgets') ?></label>
                            <p class="lsow-desc"><?php echo __('Select the default theme color.', 'livemesh-so-widgets') ?></p>
                            <div class="lsow-spacer" style="height: 5px"></div>
                            <input class="lsow-colorpicker" name="lsow_theme_color" type="text"
                                   data-default="#f94213" value="<?php echo esc_attr($theme_color) ?>"/>
                        </div>


                        <div class="lsow-spacer"></div>

                        <!---- Theme Hover color -->
                        <div class="lsow-row lsow-field lsow-type-color">
                            <label class="lsow-label"><?php echo __('Theme Hover Color', 'livemesh-so-widgets') ?></label>
                            <p class="lsow-desc"><?php echo __('Select the default hover color for your theme.', 'livemesh-so-widgets') ?></p>
                            <div class="lsow-spacer" style="height: 5px"></div>
                            <input class="lsow-colorpicker" name="lsow_theme_hover_color" type="text"
                                   data-default="#888888" value="<?php echo esc_attr($theme_hover_color) ?>"/>
                        </div>



                    </div>

                    <div class="lsow-clearfix"></div>

                    <!---- Auto activate Livemesh widgets -->
                    <div class="lsow-box-side">
                        <h3><?php echo __('Auto load', 'livemesh-so-widgets') ?></h3>
                    </div>
                    <div class="lsow-inner lsow-box-inner">
                        <div class="lsow-spacer" style="height: 15px"></div>
                        <label
                            class="lsow-label lsow-label-outside"><?php echo __('Activate all plugin widgets', 'livemesh-so-widgets') ?></label>
                        <div class="lsow-row lsow-type-checkbox lsow-field">
                            <p class="lsow-desc"><?php echo __('You can selectively activate plugin widgets in', 'livemesh-so-widgets'); ?>
                                <strong> <a href="<?php echo site_url() . '/wp-admin/plugins.php?page=so-widgets-plugins'; ?>" target="_blank"><?php echo __('Plugins->SiteOrigin Widgets.', 'livemesh-so-widgets') ?></a></strong>
                                <?php echo __('Or you can choose to auto activate all the widgets part of this plugin by checking below.', 'livemesh-so-widgets'); ?></p>
                            <div class="lsow-toggle">
                                <input type="checkbox" class="lsow-checkbox" name="lsow_autoload_widgets"
                                       id="lsow_autoload_widgets" data-default=""
                                       value="<?php echo $autoload_widgets ?>" <?php echo checked(!empty($autoload_widgets), 1, false) ?>>
                                <label for="lsow_autoload_widgets"></label>
                            </div>
                        </div>

                    </div>

                    <div class="lsow-clearfix"></div>
                    

                </div>

                <!------------------- Custom CSS TAB -------------------->

                <div class="lsow-tab-content custom-css">

                    <!---- Custom CSS -->
                    <div class="lsow-box-side">
                        <h3><?php echo __('Custom CSS', 'livemesh-so-widgets') ?></h3>
                    </div>
                    <div class="lsow-inner lsow-box-inner">
                        <div class="lsow-row lsow-field lsow-custom-css">
                            <label
                                class="lsow-label"><?php echo __('Custom CSS', 'livemesh-so-widgets') ?></label>
                            <div class="lsow-spacer" style="height: 5px"></div>
                            <p class="lsow-desc"><?php echo __('Please enter custom CSS for custom styling of widgets', 'livemesh-so-widgets') ?></p>

                            <div class="lsow-spacer" style="height: 15px"></div>

                            <textarea class="lsow-textarea" name="lsow_custom_css" id="lsow_custom_css" rows="20" cols="120"><?php echo wp_kses_post($custom_css) ?></textarea>

                        </div>
                    </div>

                    <div class="lsow-clearfix"></div>

                </div>

                <!------------------- Debugging TAB -------------------->

                <div class="lsow-tab-content debugging">

                    <!---- Enable script debugging -->
                    <div class="lsow-box-side">
                        <h3><?php echo __('Debug Mode', 'livemesh-so-widgets') ?></h3>
                    </div>
                    <div class="lsow-inner lsow-box-inner">
                        <div class="lsow-spacer" style="height: 15px"></div>
                        <label
                            class="lsow-label lsow-label-outside"><?php echo __('Enable Script Debug Mode', 'livemesh-so-widgets') ?></label>
                        <div class="lsow-row lsow-type-checkbox lsow-field">
                            <p class="lsow-desc"><?php echo __('Use unminified Javascript files instead of minified ones to help developers debug an issue', 'livemesh-so-widgets') ?></p>
                            <div class="lsow-toggle">
                                <input type="checkbox" class="lsow-checkbox" name="lsow_enable_debug" id="lsow_enable_debug"
                                       data-default="" value="<?php echo $debug_mode ?>" <?php echo checked(!empty($debug_mode), 1, false) ?>>
                                <label for="lsow_enable_debug"></label>
                            </div>
                        </div>
                    </div>

                    <div class="lsow-clearfix"></div>

                    <!---- System Info -->
                    <div class="lsow-box-side">
                        <h3><?php echo __('System Info', 'livemesh-so-widgets') ?></h3>
                    </div>
                    <div class="lsow-inner lsow-box-inner">

                        <div class="lsow-row lsow-field">
                            <label
                                class="lsow-label"><?php echo __('System Information', 'livemesh-so-widgets') ?></label>
                            <p class="lsow-desc"><?php echo __('Server setup information useful for debugging purposes.', 'livemesh-so-widgets'); ?></p>

                            <div class="lsow-spacer" style="height: 15px"></div>

                            <p class="debug-info"><?php echo nl2br(lsow_get_sysinfo()); ?></p>
                        </div>

                    </div>

                    <div class="lsow-clearfix"></div>

                </div>

                <!------------------- PREMIUM VERSION TAB -------------------->

                <div class="lsow-tab-content premium-version">

                    <!---- Premium Version Information -->
                    <div class="lsow-box-side">
                        <h3><?php echo __('Premium Version', 'livemesh-so-widgets') ?></h3>
                    </div>
                    <div class="lsow-inner lsow-box-inner">


                        <div class="lsow-row lsow-field lsow_premium_version">

                            <?php if (lsow_fs()->is_not_paying()): ?>

                                <label class="lsow-label"><?php echo __('Why upgrade to Premium Version of the plugin?!', 'livemesh-so-widgets') ?></label>

                            <?php else: ?>

                                <label class="lsow-label"><?php echo __('Thanks for upgrading to the Premium Version of the plugin!', 'livemesh-so-widgets') ?></label>

                            <?php endif; ?>
                            
                            <p>The premium version helps us to continue development of this plugin incorporating even
                                more features and enhancements along with offering more responsive support. Following are
                                some of the benefits you enjoy by upgrading to the premium version of this plugin.</p>

                            <label class="lsow-label">New Premium Widgets</label>

                            <p>Although the free version of the Livemesh for
                                SiteOrigin widgets features a large repertoire of premium quality widgets, the premium
                                version does even more.</p>

                            <ul>
                                <li><strong>Image Slider</strong> - Create a responsive slider of images with support
                                    for captions,
                                    multiple slider types like Nivo, Flex, Slick and lightweight sliders, thumbnail
                                    navigation etc.
                                </li>
                                <li><strong>Image Gallery</strong> - Create a gallery of images with options for masonry
                                    or fit rows, pagination, lazy load, lightbox support etc.
                                </li>
                                <li><strong>Video Gallery</strong> - Create a beautiful gallery of videos to help
                                    showcase a collection of YouTube/Vimeo videos on your site.
                                </li>
                                <li><strong>Image Carousel</strong> - Build a responsive carousel of images.</li>
                                <li><strong>Video Carousel</strong> - Build a responsive carousel of YouTube/Vimeo
                                    videos.
                                </li>
                                <li><strong>Countdown</strong> - Use countdown widget to display a countdown timer on
                                    your site pages
                                    such as those that feature events or under construction/coming soon pages.
                                </li>
                                <li><strong>FAQ</strong> - Create a set of Frequently Asked Questions for display in a
                                    page.
                                </li>
                                <li><strong>Features Widget</strong> for showcasing product features or services provided by an agency/business..
                                </li>
                            </ul>

                            <div class="lsow-spacer" style="height: 15px"></div>

                            <?php if (lsow_fs()->is_not_paying()): ?>

                                <a class="lsow-button purchase" href="<?php echo lsow_fs()->get_upgrade_url(); ?>"><i class="dashicons dashicons-cart"></i><?php echo __('Purchase Now', 'livemesh-so-widgets'); ?></a>

                                <div class="lsow-spacer" style="height: 25px"></div>

                            <?php endif; ?>

                            <label class="lsow-label">Additional Features</label>

                            <p>Along with incorporating many new widgets into premium version, the pro version is being
                                updated with additional features for existing widgets -</p>

                            <ul>
                                <li><strong>Lazy Load</strong> - The portfolio/post grid and image gallery widgets
                                    incorporate option to lazy load posts/images with the click of a Load More button.
                                </li>
                                <li><strong>Pagination</strong> - Create a grid of posts or custom post types with AJAX
                                    based pagination support.
                                </li>
                                <li><strong>Lightbox Support</strong> - The premium version comes with support for
                                    Lightbox for grid and carousel widgets.
                                </li>
                                <li><strong>Custom Fonts</strong> - Ability to choose custom fonts from Google Fonts
                                    library for headings in heading widget and the hero header widget.
                                </li>
                                <li><strong>Custom Animations</strong> - Choose from over <strong>40+ animations</strong> for most widgets (excludes sliders, carousels and grid). The animations display on user scrolling to the widget or when the widget becomes visible in the browser window.
                                </li>
                                <li><strong>Sample Data</strong> - Sample data that you can import into your site to get
                                    started quickly on the widgets and some sample layouts.
                                </li>
                            </ul>

                            <label class="lsow-label">Premium Support</label>

                            <p>We offer premium support for our paid customers with following benefits - </p>

                            <ul>
                                <li><strong>Dedicated Support Portal</strong> - The customers will be provided access to a
                                    dedicated support portal powered by Freshdesk.
                                </li>
                                <li><strong>Private Tickets</strong> - Private tickets help you work with us
                                    directly regarding the issues you are facing in your site by sharing the details of
                                    your site securely.
                                </li>
                                </li>
                                <li><strong>Faster turnaround</strong> - The threads opened by paid customers will be
                                    attended to within 24 hours of opening a ticket.
                                </li>
                                <li><strong>Bug fixes and Enhancements</strong> - Any fixes and enhancements made to the
                                    elements will be prioritized to arrive quicker on the premium version.
                                </li>
                                <li><strong>Proven Expertize</strong> - Having served over <strong>12,280+
                                        customers</strong> of our themes over past 3 years, the support provided by us
                                    is proven in competence and commitment.
                                </li>
                            </ul>

                            <div class="lsow-spacer" style="height: 25px"></div>

                            <?php if (lsow_fs()->is_not_paying()): ?>

                                <a class="lsow-button purchase" href="<?php echo lsow_fs()->get_upgrade_url(); ?>"><i class="dashicons dashicons-cart"></i><?php echo __('Go Premium', 'livemesh-so-widgets'); ?></a>

                            <?php else: ?>

                                <a class="lsow-button know-more" href="https://livemeshwp.com/siteorigin-widgets/"><i class="dashicons dashicons-external"></i><?php echo __('Know More', 'livemesh-so-widgets'); ?></a>

                            <?php endif; ?>
                        </div>

                    </div>

                </div>

                <!-------------------  OPTIONS HOLDER END  -------------------->
            </div>
            
        </div>

        <!------------------- BUILD PANEL SETTINGS -------------------->

    </div>

</div>
