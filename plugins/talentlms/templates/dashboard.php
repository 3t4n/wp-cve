<div class="wrap">
    <div class="tl-admin-content">
        <div class="tl-admin-options">
            <h2><?php esc_html_e('Welcome! Let\'s integrate TalentLMS with WordPress', 'talentlms'); ?></h2>

            <br />
            <br />

            <div class="tl-admin-options-grid">
                <a href="<?php echo esc_url(admin_url('admin.php?page=talentlms-setup')); ?>">
                    <div class="tl-admin-option">
                        <i class="fa-solid fa-cog fa-3x"></i>
                        <h3><?php esc_html_e('Setup', 'talentlms'); ?></h3>
                        <p><?php esc_html_e('Connect TalentLMS with your WordPress site', 'talentlms'); ?></p>
                    </div>
                </a>

                <a href="<?php echo esc_url(admin_url('admin.php?page=talentlms-integrations')); ?>">
                    <div class="tl-admin-option">
                        <i class="fa-solid fa-square-check fa-3x"></i>
                        <h3><?php esc_html_e('Integrations', 'talentlms'); ?></h3>
                        <p><?php esc_html_e('Integrate your plugin with other WP plugins', 'talentlms'); ?></p>
                    </div>
                </a>

                <a href="<?php echo esc_url(admin_url('admin.php?page=talentlms-css')); ?>">
                    <div class="tl-admin-option">
                        <i class="fa-solid fa-square-check fa-3x"></i>
                        <h3><?php esc_html_e('CSS', 'talentlms'); ?></h3>
                        <p><?php esc_html_e('Customize the plugin appearance', 'talentlms'); ?></p>
                    </div>
                </a>

                <a href="javascript:void(0);">
                    <div class="tl-admin-option" data-toggle="modal" data-target="#shortcodesModal">
                        <i class="fa-solid fa-code fa-3x"></i>
                        <h3><?php esc_html_e('Shortcodes', 'talentlms'); ?></h3>
                        <p><?php esc_html_e('Shortcodes to use with your WordPress site.', 'talentlms'); ?></p>
                    </div>
                </a>
                <!-- snom 7-->
                <a href="<?php echo esc_url(admin_url('widgets.php')); ?>">
                    <div class="tl-admin-option">
                        <i class="fa-solid fa-cogs fa-3x"></i>
                        <h3><?php esc_html_e('Widgets', 'talentlms'); ?></h3>
                        <p><?php esc_html_e('Insert TalentLMS widget in any registered sidebar of your site.', 'talentlms'); ?></p>
                    </div>
                </a>

                <a href="javascript:void(0);">
                    <div class="tl-admin-option" data-toggle="modal" data-target="#helpModal">
                        <i class="fa-solid fa-question-circle fa-3x"></i>
                        <h3><?php esc_html_e('Help', 'talentlms'); ?></h3>
                        <p><?php esc_html_e('Instructions and best practices', 'talentlms'); ?></p>
                    </div>
                </a>
            </div>
        </div><!-- tl-admin-options -->


        <div class="tl-admin-footer">
        </div><!-- .tl-admin-footer -->

        <div class="modal" id ="shortcodesModal" aria-labelledby="modal-label" tabindex="0">
            <span id="modal-label" class="screen-reader-text"><?php esc_html_e('Press Esc to close.', 'talentlms'); ?></span>
            <a href="#" class="close" data-dismiss="modal">&times; <span class="screen-reader-text"><?php esc_html_e('Close modal window', 'talentlms'); ?></span></a>
            <div class="content-container ">
                <div class="content">
                    <h2>ShortCodes</h2>
                    <p><?php esc_html_e('Here is a list of all available shortcodes with the TalentLMS WordPress plugin. Use these shortcodes in any WordPress posts or pages', 'talentlms'); ?></p>
                    <ul>
                        <li>
                            <p><strong>[talentlms-courses]</strong>&nbsp;<?php esc_html_e('Shortcode for listing your TalentLMS courses.', 'talentlms'); ?></p>
                        </li>
                    </ul>

                </div>
            </div>
            <footer>
                <ul>
                    <li>
                        <span class="activate">
                        <a class="button-primary" href="#" data-dismiss="modal">Close</a>
                        </span>
                    </li>
                </ul>
            </footer>
        </div>

        <div class="modal" id ="helpModal" aria-labelledby="modal-label" tabindex="0">
            <span id="modal-label" class="screen-reader-text"><?php esc_html_e('Press Esc to close.', 'talentlms'); ?></span>
            <a href="#" class="close" data-dismiss="modal">&times; <span class="screen-reader-text"><?php esc_html_e('Close modal window', 'talentlms'); ?></span></a>
            <div class="content-container ">
                <div class="content">
                    <h2><?php esc_html_e('Help', 'talentlms');?></h2>
                    <p><strong>TalentLMS</strong><?php esc_html_e(' is a super-easy, cloud-based learning platform to train your people and customers. This WordPress plugin is a tool you can use to diplay your TalentLMS content in WordPress.', 'talentlms')?></p>
                    <p><?php esc_html_e('For more information', 'talentlms');?>:</p>
                    <ul>
                        <li>
                            <p><strong>TalentLMS:</strong>&nbsp;<a href="http://www.talentlms.com/" target="_blank">www.talentlms.com</a></p>
                        </li>
                        <li>
                            <p><strong>TalentLMS blog:</strong>&nbsp;<a href="http://www.talentlms.com/blog" target="_blank">blog.talentlms.com</a></p>
                        </li>
                        <li>
                            <p><strong>Support:</strong>&nbsp;<a href="http://support.talentlms.com/" target="_blank">support.talentlms.com</a></p>
                        </li>
                        <li>
                            <p><strong>Contact:</strong>&nbsp;<a href="mailto: support@talentlms.com" target="_blank">support@talentlms.com</a> or use our <a href="http://www.talentlms.com/contact" target="_blank"> contact form</a></p>
                        </li>
                    </ul>
                </div>
            </div>
            <footer>
                <ul>
                    <li>
                        <span class="activate">
                        <a class="button-primary" href="#" data-dismiss="modal">Close</a>
                        </span>
                    </li>
                </ul>
            </footer>
        </div>
    </div><!-- .tl-admin-content -->
</div><!-- .wrap -->
