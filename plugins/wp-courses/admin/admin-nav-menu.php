<div id="wpc-admin-main-menu" class="wpc-nav-menu">
    <div class="wpc-menu-wrapper">
        <a href="admin.php?page=wpc_settings" id="wpc-logo-sm-wrapper">
            <img src="<?php echo esc_url(WPC_PLUGIN_URL); ?>images/wpc-logo-sm.png" id="wpc-logo-sm" style="max-width: 150px;"/>
        </a>       

        <?php if( is_plugin_active( 'wp-courses-premium/wp-courses-premium.php' ) == false ) { ?>
            <a target="_blank" href="https://wpcoursesplugin.com/cart/?add-to-cart=829" class="wpc-btn wpc-btn-solid wpc-premium-upgrade"><i class="fa fa-shopping-cart"></i><?php esc_html_e(' Upgrade to Premium', 'wp-courses'); ?></a>
        <?php } ?>

        <ul class="wpc-alt-menu">
            <li class="wpc-menu-item wpc-alt-menu-separator"><a href="admin.php?page=wpc_settings"><?php esc_html_e('Dashboard', 'wp-courses'); ?></a></li>

            <li class="wpc-menu-item"><a href="admin.php?page=wpc_help"><?php esc_html_e('Setup and Help', 'wp-courses'); ?></a></li>

            <li class="wpc-menu-item wpc-submenu-toggle wpc-alt-menu-separator">
                <span><?php esc_html_e('Courses', 'wp-courses'); ?><span class="dashicons dashicons-arrow-down"></span></span>
                <ul class="wpc-submenu" style="display: none;">
                    <li><a href="edit.php?post_type=course"><?php esc_html_e('All Courses', 'wp-courses'); ?></a></li>
                    <li><a href="admin.php?page=order_courses"><?php esc_html_e('Course Order', 'wp-courses'); ?></a></li>
                    <li><a href="edit-tags.php?taxonomy=course-category&post_type=course"><?php esc_html_e('Course Categories', 'wp-courses'); ?></a></li>
                    <li><a href="edit-tags.php?taxonomy=course-difficulty&post_type=course"><?php esc_html_e('Course Difficulties', 'wp-courses'); ?></a></li>
                </ul>
            </li>

            <li class="wpc-menu-item wpc-submenu-toggle">
                <span><?php esc_html_e('Lessons', 'wp-courses'); ?><span class="dashicons dashicons-arrow-down"></span></span>
                <ul class="wpc-submenu" style="display: none;">
                    <li><a href="edit.php?post_type=lesson"><?php esc_html_e('All Lessons', 'wp-courses'); ?></a></li>
                    <?php do_action('wpc_after_admin_nav_menu_manage_lessons'); ?>
                    <li><a href="admin.php?page=order_lessons"><?php esc_html_e('Lesson Order', 'wp-courses'); ?></a></li>
                </ul>
            </li>

            <?php if(WPCP_VERSION > 3.07 || WPCP_VERSION === false) { ?>
                <li class="wpc-menu-item"><a href="edit.php?post_type=wpc-quiz"><?php esc_html_e('All Quizzes', 'wp-courses'); ?></a></li>
            <?php } ?>
        	
            <li class="wpc-menu-item"><a href="edit.php?post_type=teacher"><?php esc_html_e('All Teachers', 'wp-courses'); ?></a></li>

            <li class="wpc-menu-item"><a href="admin.php?page=manage_students"><?php esc_html_e('All Students', 'wp-courses'); ?></a></li>

            <li class="wpc-menu-item wpc-alt-menu-separator"><a href="admin.php?page=wpc_options"><?php esc_html_e('Settings', 'wp-courses'); ?></a></li>

            <?php do_action('wpc_after_admin_nav_menu'); ?> 
        </ul>
    </div>
</div>