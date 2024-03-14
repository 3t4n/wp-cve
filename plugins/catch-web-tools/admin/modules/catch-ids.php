<?php
/**
 * @package Admin
 * @sub-package Admin Webmaster Display
 */
 ?>
<?php include ( 'header.php' ); ?>
    <div id="catch-ids" class="catchids-main cwt">
        <div class="content-wrapper">
            <div class="header">
                <h3><?php _e( 'Catch IDs', 'catch-web-tools' ); ?></h3>
            </div> <!-- .header -->
            <div class="content">
                <?php
                    if( is_plugin_active( 'catch-ids/catch-ids.php' ) ) : ?>
                    <div class="module-container">
                        <!-- Status -->
                        <div id="module-disabled" class="catch-modules full-width">
                            <div class="module-header disable">
                                <h3 class="module-title"><?php esc_html_e( 'Status : Disabled', 'catch-web-tools' ); ?>
                                </h3>
                            </div><!-- .module-header -->
                            <div class="module-content">
                                <p class="notice notice-warning">
                                    <?php _e( 'This module is currently disabled since Catch IDs standalone plugin is already active on your site. If you want to configure the Catch IDs please click on the following link.', 'catch-web-tools' );?>
                                </p>
                                <?php
                                    $settings_link = '<a style="margin-top: 10px; display: inline-block; font-size: 13px;" href="' . esc_url( admin_url( 'admin.php?page=catch-ids' ) ) . '">' . esc_html__( 'Catch IDs', 'catch-web-tools' ) . '</a>';
                                    echo $settings_link;
                                ?>
                            </div><!-- .module-content -->
                        </div><!-- .catch-modules -->
                    </div>
                <?php else: ?>
                <?php
                    $options = catchwebtools_get_options( 'catchwebtools_catchids' );
                    $post_types = catchwebtools_catchids_get_all_post_types();
                ?>
                    <!-- Status -->
                    <div id="module-<?php echo 'status'; ?>" class="catch-modules full-width">
                        <div class="module-header <?php echo $options['status'] ? 'active' : 'inactive'; ?>">
                            <h3 class="module-title"><?php esc_html_e( 'Status', 'catch-web-tools' ); ?>
                                <span class="inactive" <?php echo $options['status'] ? 'style="display: none"' : ''; ?>>
                                    <?php esc_html_e( ': Inactive', 'catch-web-tools' ); ?>
                                </span>
                                <span class="active" <?php echo $options['status'] ? '' : 'style="display: none"'; ?>>
                                    <?php esc_html_e( ': Active', 'catch-web-tools' ); ?>
                                </span>
                            </h3>
                            <div class="switch">
                                <input type="hidden" name="catchwebtools_catchids_nonce" id="catchwebtools_catchids_nonce" value="<?php echo esc_attr( wp_create_nonce( 'catchwebtools_catchids_nonce' ) ); ?>" />
                                <input type="checkbox" id="catchwebtools_catchids[status]" class="catchids-input-switch" rel="status" <?php checked( true, $options['status'] ); ?> >
                                <label for="catchwebtools_catchids[status]"></label>
                            </div>
                            <div class="loader"></div>
                        </div><!-- .module-header -->
                    </div><!-- .catch-modules -->

                <div class="module-container catch-ids-options" <?php echo ! $options['status'] ? 'style="display: none"' : ''; ?>>
                    <?php foreach ( $post_types as $key => $value ) : ?>
                    <!-- Custom Post Types -->
                    <div id="module-<?php echo $key; ?>" class="catch-modules">
                        <div class="module-header <?php echo $options[$key] ? 'active' : 'inactive'; ?>">
                            <h3 class="module-title"><?php esc_html_e( $value, 'catch-web-tools' ); ?></h3>
                            <div class="switch">
                                <input type="checkbox" id="catchwebtools_catchids[<?php echo $key; ?>]" class="catchids-input-switch" rel="<?php echo $key; ?>" <?php checked( true, $options[$key] ); ?> >
                                <label for="catchwebtools_catchids[<?php echo $key; ?>]"></label>
                            </div>
                            <div class="loader"></div>
                        </div><!-- .module-header -->
                    </div><!-- .catch-modules -->
                    <?php endforeach; ?>

                    <!-- Media -->
                    <div id="module-<?php echo 'media'; ?>" class="catch-modules">
                        <div class="module-header <?php echo $options['media'] ? 'active' : 'inactive'; ?>">
                            <h3 class="module-title"><?php esc_html_e( 'Media', 'catch-web-tools' ); ?></h3>
                            <div class="switch">
                                <input type="checkbox" id="catchwebtools_catchids[media]" class="catchids-input-switch" rel="media" <?php checked( true, $options['media'] ); ?> >
                                <label for="catchwebtools_catchids[media]"></label>
                            </div>
                            <div class="loader"></div>
                        </div><!-- .module-header -->
                    </div><!-- .catch-modules -->

                    <!-- Categories -->
                    <div id="module-<?php echo 'category'; ?>" class="catch-modules">
                        <div class="module-header <?php echo $options['category'] ? 'active' : 'inactive'; ?>">
                            <h3 class="module-title"><?php esc_html_e( 'Categories', 'catch-web-tools' ); ?></h3>
                            <div class="switch">
                                <input type="checkbox" id="catchwebtools_catchids[category]" class="catchids-input-switch" rel="category" <?php checked( true, $options['category'] ); ?> >
                                <label for="catchwebtools_catchids[category]"></label>
                            </div>
                            <div class="loader"></div>
                        </div><!-- .module-header -->
                    </div><!-- .catch-modules -->

                    <!-- Users -->
                    <div id="module-<?php echo 'user'; ?>" class="catch-modules">
                        <div class="module-header <?php echo $options['user'] ? 'active' : 'inactive'; ?>">
                            <h3 class="module-title"><?php esc_html_e( 'Users', 'catch-web-tools' ); ?></h3>
                            <div class="switch">
                                <input type="checkbox" id="catchwebtools_catchids[user]" class="catchids-input-switch" rel="user" <?php checked( true, $options['user'] ); ?> >
                                <label for="catchwebtools_catchids[user]"></label>
                            </div>
                            <div class="loader"></div>
                        </div><!-- .module-header -->
                    </div><!-- .catch-modules -->

                    <!-- Comments -->
                    <div id="module-<?php echo 'comment'; ?>" class="catch-modules">
                        <div class="module-header <?php echo $options['comment'] ? 'active' : 'inactive'; ?>">
                            <h3 class="module-title"><?php esc_html_e( 'Comments', 'catch-web-tools' ); ?></h3>
                            <div class="switch">
                                <input type="checkbox" id="catchwebtools_catchids[comment]" class="catchids-input-switch" rel="comment" <?php checked( true, $options['comment'] ); ?> >
                                <label for="catchwebtools_catchids[comment]"></label>
                            </div>
                            <div class="loader"></div>
                        </div><!-- .module-header -->
                    </div><!-- .catch-modules -->

                </div><!-- .module-container -->

                <?php endif; ?>
            </div><!-- .content -->
        </div><!-- .content-wrapper -->
    </div><!-- .catchids-main -->

<?php include ( 'main-footer.php' ); ?>
