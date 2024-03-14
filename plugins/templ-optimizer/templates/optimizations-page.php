<?php
defined('ABSPATH') or die();
global $wpdb;
?>

<div class="wrap templ-optimizer">

    <h1><?php _e('Templ Optimizer', 'templ-optimizer'); ?></h1>

    <?php settings_errors(); ?>

    <section class="optimization-group">

        <h2 class="title"><?php _e('Database optimizations', 'templ-optimizer'); ?></h2>

        <div class="optimization">
            <div class="optimization-info">
                <strong><?php _e('Database size', 'templ-optimizer'); ?></strong>
                <p><?php _e('Size', 'templ-optimizer'); ?>: <?php echo esc_html( $this->db->get_database_size() ); ?></p>
            </div>
        </div>

        <div class="optimization">
            <div class="optimization-info">
                <strong><?php _e('Trashed posts', 'templ-optimizer'); ?></strong>
                <p><?php _e('Count', 'templ-optimizer'); ?>: <?php echo esc_html( $this->db->count_trashed_posts() ); ?></p>
                <p class="optimization-description"><?php _e('Trashed posts are posts, pages and other types of posts that are trashed and waiting to be permanently deleted.', 'templ-optimizer'); ?></p>
            </div>
            <div class="optimization-actions">
                <a class="button" href="<?php echo esc_url( admin_url( $this->admin_page ) ); ?>&do=delete_trashed_posts"><?php _e('Delete trashed posts', 'templ-optimizer'); ?></a>
            </div>
        </div>

        <div class="optimization">
            <div class="optimization-info">
                <strong><?php _e('Revisions', 'templ-optimizer'); ?></strong>
                <p><?php _e('Count', 'templ-optimizer'); ?>: <?php echo esc_html( $this->db->count_revisions() ); ?></p>
                <p class="optimization-description"><?php _e('Revisions are old versions of posts and pages. Unless you know you have screwed something up and need to revert to an older version of a post, these are safe to delete.', 'templ-optimizer'); ?></p>
            </div>
            <div class="optimization-actions">
                <a class="button" href="<?php echo esc_url( admin_url( $this->admin_page ) ); ?>&do=delete_revisions"><?php _e('Delete revisions', 'templ-optimizer'); ?></a>
            </div>
        </div>

        <div class="optimization">
            <div class="optimization-info">
                <strong><?php _e('Auto-drafts', 'templ-optimizer'); ?></strong>
                <p><?php _e('Count', 'templ-optimizer'); ?>: <?php echo esc_html( $this->db->count_auto_drafts() ); ?></p>
                <p class="optimization-description"><?php _e('WordPress automatically saves drafts of posts and pages as auto-drafts when you start editing. Over time, you could have many auto-drafts that you will never publish so thos can be deleted.', 'templ-optimizer'); ?></p>
            </div>
            <div class="optimization-actions">
                <a class="button" href="<?php echo esc_url( admin_url( $this->admin_page ) ); ?>&do=delete_auto_drafts"><?php _e('Delete auto drafts', 'templ-optimizer'); ?></a>
            </div>
        </div>

        <div class="optimization">
            <div class="optimization-info">
                <strong><?php _e('Orphaned post meta', 'templ-optimizer'); ?></strong>
                <p><?php _e('Count', 'templ-optimizer'); ?>: <?php echo esc_html( $this->db->count_orphaned_postmeta() ); ?></p>
                <p class="optimization-description"><?php _e('Oprhaned post meta are data about posts that has been deleted. These are safe to delete.', 'templ-optimizer'); ?></p>
            </div>
            <div class="optimization-actions">
                <a class="button" href="<?php echo esc_url( admin_url( $this->admin_page ) ); ?>&do=delete_orphaned_postmeta"><?php _e('Delete orphaned post meta', 'templ-optimizer'); ?></a>
            </div>
        </div>

        <div class="optimization">
            <div class="optimization-info">
                <strong><?php _e('Expired transients', 'templ-optimizer'); ?></strong>
                <p><?php _e('Count', 'templ-optimizer'); ?>: <?php echo esc_html( $this->db->count_expired_transients() ); ?></p>
                <p class="optimization-description"><?php _e('Transients are temporary data stored in the database. Expired transients are no longer needed and are safe to delete.', 'templ-optimizer'); ?></p>
            </div>
            <div class="optimization-actions">
                <a class="button" href="<?php echo esc_url( admin_url( $this->admin_page ) ); ?>&do=delete_expired_transients"><?php _e('Delete expired transients', 'templ-optimizer'); ?></a>
            </div>
        </div>

        <?php if( ! is_multisite() ): // Only visible for non-multisite installations ?>
            <div class="optimization">
                <div class="optimization-info">
                    <strong><?php _e('Database tables with other prefix', 'templ-optimizer'); ?></strong>
                    <p>
                        <?php _e('Count', 'templ-optimizer'); ?>: <?php echo esc_html( $this->db->count_tables_with_different_prefix() ); ?>
                        <?php if( $this->db->count_tables_with_different_prefix() != 0 ): ?>
                            (<?php echo esc_html( $this->db->list_tables_with_different_prefix() ); ?>)
                        <?php endif; ?>
                    </p>
                    <p class="optimization-description"><?php printf( __('This WordPress installation is using the database table prefix <code>%s</code>. Sometimes tables with other prefixes, remaining from older installations, can exist in your database. Only delete these if you are sure no other active WordPress installation is using the same database.', 'templ-optimizer'), $wpdb->prefix ); ?></p>
                </div>
                <div class="optimization-actions">
                    <a class="button" href="<?php echo esc_url( admin_url( $this->admin_page ) ); ?>&do=delete_tables"><?php _e('Delete other tables', 'templ-optimizer'); ?></a>
                </div>
            </div>
        <?php endif; ?>

        <div class="optimization">
            <div class="optimization-info">
                <strong><?php _e('Convert MyISAM tables to to InnoDB', 'templ-optimizer'); ?></strong>
                <p>
                    <?php _e('Count', 'templ-optimizer'); ?>: <?php echo esc_html( $this->db->count_myisam_tables() ); ?>
                    <?php if( $this->db->count_myisam_tables() != 0 ): ?>
                        (<?php echo $this->db->list_myisam_tables(); ?>)
                    <?php endif; ?>
                </p>
                <p class="optimization-description"><?php _e('InnoDB is a faster database engine compared to MyISAM, especially when it comes to multitasking. Older sites often use MyISAM as the preferred database engine, and these can be converted to InnoDB instead. Make sure to take backup of your database before converting!', 'templ-optimizer'); ?></p>
            </div>
            <div class="optimization-actions">
                <a class="button" href="<?php echo esc_html( admin_url( $this->admin_page ) ); ?>&do=convert_to_innodb"><?php _e('Convert to InnoDB', 'templ-optimizer'); ?></a>
            </div>
        </div>

        <div class="optimization">
            <div class="optimization-info">
                <strong><?php _e('Optimize tables', 'templ-optimizer'); ?></strong>
                <p class="optimization-description"><?php _e('Reorganizes the physical storage of database data, which can reduce storage space and improve the database speed.', 'templ-optimizer'); ?></p>
            </div>
            <div class="optimization-actions">
                <a class="button" href="<?php echo esc_html( admin_url( $this->admin_page ) ); ?>&do=optimize_tables"><?php _e('Optimize tables', 'templ-optimizer'); ?></a>
            </div>
        </div>

    </section>


    <section class="optimization-group">

        <h2 class="title"><?php _e('Tweaks', 'templ-optimizer'); ?></h2>

        <div class="optimization">
            <div class="optimization-info">
                <strong><?php _e('WP Cron', 'templ-optimizer'); ?></strong>
                <p><?php _e('Status', 'templ-optimizer'); ?>: 
                <?php if( defined('DISABLE_WP_CRON') && DISABLE_WP_CRON ): ?>
                    <?php _e('Disabled', 'templ-optimizer'); ?>
                <?php else: ?>
                    <?php _e('Enabled', 'templ-optimizer'); ?>
                <?php endif; ?>
                </p>
                <p class="optimization-description"><?php _e('Cron jobs are tasks that run on on a schedule. With WP Cron enabled, these tasks run on page loads which can have a negative impact on your site\'s page speed and visitor experience. We recommend disabling WP Cron by adding <code>define(\'DISABLE_WP_CRON\', true);</code> to your wp-config.php file and setting up cron on your server instead. Contact your web host if you are unsure how to do this.', 'templ-optimizer'); ?></p>
            </div>
        </div>

        <div class="optimization">
            <div class="optimization-info">
                <strong><?php _e('Heartbeat interval', 'templ-optimizer'); ?></strong>
                <p>
                    <?php _e('Interval', 'templ-optimizer'); ?>: 
                    <?php if( $this->db->get_option('heartbeat_interval') === 'default' ): ?>
                        <?php _e('Default (15 seconds)', 'templ-optimizer'); ?>
                    <?php else: ?>
                        <?php _e('Slow (60 seconds)', 'templ-optimizer'); ?>
                    <?php endif; ?>
                </p>
                <p class="optimization-description"><?php _e('When browsing WP Admin, your browser sends heartbeats to the website at a given interval. When multiple people has several tabs of WP Admin open, a frequent heartbeat can lead to performance degrading.', 'templ-optimizer'); ?></p>
            </div>
            <div class="optimization-actions">
                <?php if( $this->db->get_option('heartbeat_interval') === 'default' ): ?>
                    <strong><?php _e('Default (15 seconds)', 'templ-optimizer'); ?></strong> | <a href="<?php echo esc_url( admin_url( $this->admin_page ) ); ?>&do=slow_heartbeat_interval"><?php _e('Slow (60 seconds)', 'templ-optimizer'); ?></a>
                <?php else: ?>
                    <a href="<?php echo esc_url( admin_url( $this->admin_page ) ); ?>&do=default_heartbeat_interval"><?php _e('Default (15 seconds)', 'templ-optimizer'); ?></a> | <strong><?php _e('Slow (60 seconds)', 'templ-optimizer'); ?></strong>
                <?php endif; ?>
            </div>
        </div>

        <div class="optimization">
            <div class="optimization-info">
                <strong><?php _e('WP Rocket preload interval', 'templ-optimizer'); ?></strong>
                <p>
                    <?php _e('Interval', 'templ-optimizer'); ?>: 
                    <?php if( $this->db->get_option('wp_rocket_preload_interval') === 'default' ): ?>
                        <?php _e('Default (0.5 seconds)', 'templ-optimizer'); ?>
                    <?php else: ?>
                        <?php _e('Slow (5 seconds)', 'templ-optimizer'); ?>
                    <?php endif; ?>
                </p>
                <p class="optimization-description"><?php _e('WP Rocket\'s preload feature crawls your site to be able to pre-generate a cached version of pages without anyone having to visit them. If you have a heavy site, the default preload interval can lead to very high resource usage and slow down your site.', 'templ-optimizer'); ?></p>
            </div>
            <div class="optimization-actions">
                <?php if( $this->db->get_option('wp_rocket_preload_interval') === 'default' ): ?>
                    <strong><?php _e('Default (0.5 seconds)', 'templ-optimizer'); ?></strong> | <a href="<?php echo esc_url( admin_url( $this->admin_page ) ); ?>&do=slow_wp_rocket_preload_interval"><?php _e('Slow (5 seconds)', 'templ-optimizer'); ?></a>
                <?php else: ?>
                    <a href="<?php echo esc_url( admin_url( $this->admin_page ) ); ?>&do=default_wp_rocket_preload_interval"><?php _e('Default (0.5 seconds)', 'templ-optimizer'); ?></a> | <strong><?php _e('Slow (5 seconds)', 'templ-optimizer'); ?></strong>
                <?php endif; ?>
            </div>
        </div>

        <div class="optimization">
            <div class="optimization-info">
                <strong><?php _e('Limit post revisions', 'templ-optimizer'); ?></strong>
                <p>
                    <?php _e('Limit', 'templ-optimizer'); ?>: 
                    <?php if( WP_POST_REVISIONS === true ): ?>
                        <?php _e('Default (unlimited)'); ?>
                    <?php elseif( WP_POST_REVISIONS === false ): ?>
                        <?php _e('Disabled'); ?>
                    <?php else: ?>
                        <?php echo sprintf( __( '%s revision(s)', 'templ-optimizer' ), WP_POST_REVISIONS ) ?>
                    <?php endif; ?>
                </p>
                <p class="optimization-description"><?php _e('Each time you edit a post, page or any other post type, WordPress stores a copy of the old version in the database. By default, WordPress stores an unlimited amount of revisions. This can be limited to keep the database light.', 'templ-optimizer'); ?></p>
            </div>
            <div class="optimization-actions">
                <?php if( WP_POST_REVISIONS === true ): ?>
                    <strong><?php _e('Default (unlimited)', 'templ-optimizer'); ?></strong> | <a href="<?php echo esc_url( admin_url( $this->admin_page ) ); ?>&do=post_revisions_5"><?php _e('5 revisions', 'templ-optimizer'); ?></a>
                <?php else: ?>
                    <a href="<?php echo esc_url( admin_url( $this->admin_page ) ); ?>&do=post_revisions_default"><?php _e('Default (unlimited)', 'templ-optimizer'); ?></a> | <strong><?php _e('5 revisions', 'templ-optimizer'); ?></strong>
                <?php endif; ?>
            </div>
        </div>

        <div class="optimization">
            <div class="optimization-info">
                <strong><?php _e('Memory limit', 'templ-optimizer'); ?></strong>
                <p>
                    <?php _e('Limit', 'templ-optimizer'); ?>: 
                    <?php echo WP_MEMORY_LIMIT; ?>
                </p>
                <p class="optimization-description"><?php _e('If WordPress reaches its default memory limit, your sight might crash and you\'ll get a fatal error message. Increasing the memory limit might prevent such errors and increase overall performance of a site. However, we encourage you to first try to find out what is causing the high memory usage first as it might indicate a problem.', 'templ-optimizer'); ?></p>
            </div>
            <div class="optimization-actions">
                <?php if ( false === wp_is_ini_value_changeable( 'memory_limit' ) ): ?>
                    <i><?php _e('Your host don\'t allow changing memory limit.', 'templ-optimizer') ?></i>
                <?php else: ?>
                    <?php if( WP_MEMORY_LIMIT === '40M' || WP_MEMORY_LIMIT === '64M' ): ?>
                        <strong><?php _e('Default (40M/64M)', 'templ-optimizer'); ?></strong> | <a href="<?php echo esc_url( admin_url( $this->admin_page ) ); ?>&do=memory_limit_128M">128M</a> | <a href="<?php echo esc_url( admin_url( $this->admin_page ) ); ?>&do=memory_limit_256M">256M</a>
                    <?php elseif( WP_MEMORY_LIMIT === '128M' ): ?>
                        <a href="<?php echo esc_url( admin_url( $this->admin_page ) ); ?>&do=memory_limit_default"><?php _e('Default (40M/64M)', 'templ-optimizer'); ?></a> | <strong>128M</strong> | <a href="<?php echo esc_url( admin_url( $this->admin_page ) ); ?>&do=memory_limit_256M">256M</a>
                    <?php elseif( WP_MEMORY_LIMIT === '256M' ): ?>
                        <a href="<?php echo esc_url( admin_url( $this->admin_page ) ); ?>&do=memory_limit_default"><?php _e('Default (40M/64M)', 'templ-optimizer'); ?></a> | <a href="<?php echo esc_url( admin_url( $this->admin_page ) ); ?>&do=memory_limit_128M">128M</a> | <strong>256M</strong>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

    </section>

</div>
