<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       catchplugins.com
 * @since      1.0.0
 *
 * @package    To_Top
 * @subpackage To_Top/admin/partials
 */
?>

<?php include ( 'header.php' ); ?>
    <div id="to_top_main">
        <div class="content-wrapper">
            <div class="header">
                <h3><?php _e( 'To Top', 'catch-web-tools' ); ?></h3>
            </div> <!-- .header -->
            <div class="content">
                <?php
                    if( is_plugin_active( 'to-top/to-top.php' ) ) : ?>
                    <div class="module-container">
                        <!-- Status -->
                        <div id="module-disabled" class="catch-modules full-width">
                            <div class="module-header disable">
                                <h3 class="module-title"><?php esc_html_e( 'Status : Disabled', 'catch-web-tools' ); ?>
                                </h3>
                            </div><!-- .module-header -->
                            <div class="module-content">
                                <p class="notice notice-warning">
                                    <?php _e( 'This module is currently disabled since To Top standalone plugin is already active on your site. If you want to configure the To Top please click on the following link.', 'catch-web-tools' );?>
                                </p>
                                <?php
                                    $settings_link = '<a style="margin-top: 10px; display: inline-block;" href="' . esc_url( admin_url( 'admin.php?page=to-top' ) ) . '">' . esc_html__( 'To Top', 'catch-web-tools' ) . '</a>';
                                    echo $settings_link;
                                ?>
                            </div><!-- .module-content -->
                        </div><!-- .catch-modules -->
                    </div>
                <?php else: ?>
                    <form method="post" action="options.php">
                        <?php settings_fields( 'to-top-settings-group' ); ?>
                        <?php $settings = catchwebtools_get_options( 'catchwebtools_to_top_options');
                        ?>
                        <div class="option-container">
                            <h3 class="option-toggle option-active"><a href="#"><?php esc_html_e( 'Basic Settings', 'catch-web-tools' ); ?></a></h3>
                            <div class="option-content inside open">
                                <table class="form-table">
                                    <tbody>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Enable to Top', 'catch-web-tools' ); ?></th>

                                            <td>
                                                <?php
                                                    echo '<input name="catchwebtools_to_top_options[status]" id="catchwebtools_to_top_options[status]" type="checkbox" value="1" class="catchwebtools_to_top_options[status]" ' . checked( 1, $settings['status'], false ) . ' />' . esc_html__( 'Check to Enable','catch-web-tools' );
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Scroll Offset (px)', 'catch-web-tools' ); ?></th>
                                            <td>
                                                <?php
                                                    echo '<input min="1" type="number" id="catchwebtools_to_top_options[scroll_offset]" name="catchwebtools_to_top_options[scroll_offset]" value="'. absint( $settings['scroll_offset'] ) .'"/>px';

                                                    echo '<p class="description">'. esc_html__( 'Number of pixels to be scrolled before the button appears', 'catch-web-tools' ) .'</p>';
                                                ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Icon Opacity (%)', 'catch-web-tools' ); ?></th>
                                            <td>
                                                <?php
                                                    echo '<input min="1" max="100" type="number" id="catchwebtools_to_top_options[icon_opacity]" name="catchwebtools_to_top_options[icon_opacity]" value="'. absint( $settings['icon_opacity'] ) .'"/>%';
                                                ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Style', 'catch-web-tools' ); ?></th>

                                            <td>
                                                <?php
                                                    echo '<select id="catchwebtools_to_top_options_style" name="catchwebtools_to_top_options[style]">';
                                                        echo '<option value="icon"' . selected( $settings['style'], 'icon', false) . '>'. esc_html__( 'Icon', 'catch-web-tools') .'</option>';
                                                        echo '<option value="genericon-icon"' . selected( $settings['style'], 'genericon-icon', false) . '>'. esc_html__( 'Icon Using Genericons', 'catch-web-tools') .'</option>';
                                                        echo '<option value="font-awesome-icon"' . selected( $settings['style'], 'font-awesome-icon', false) . '>'. esc_html__( 'Icon Using Font Awesome Icons', 'catch-web-tools') .'</option>';
                                                        echo '<option value="image"' . selected( $settings['style'], 'image', false) . '>'. esc_html__( 'Image', 'catch-web-tools') .'</option>';
                                                    echo '</select>';
                                                ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <?php submit_button( esc_html__( 'Save Changes', 'catch-web-tools' ) ); ?>
                            </div>

                            <h3 class="option-toggle"><a href="#"><?php esc_html_e( 'Icon Settings', 'catch-web-tools' ); ?></a></h3>
                            <div class="option-content inside">
                                <table class="form-table">
                                    <tbody>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Select Icon Type', 'catch-web-tools' ); ?></th>

                                            <td>
                                                <?php
                                                    echo '<input type="radio" id="catchwebtools_to_top_options_icon_type_1" name="catchwebtools_to_top_options[icon_type]" value="dashicons-arrow-up"' . checked( 'dashicons-arrow-up', $settings['icon_type'], false ) . '/>';
                                                    echo '<label class="dashicon_label" for="catchwebtools_to_top_options_icon_type_1"><span class="dashicon_to_top_admin dashicons dashicons-arrow-up"></span></label>';

                                                    echo '<input type="radio" id="catchwebtools_to_top_options_icon_type_2" name="catchwebtools_to_top_options[icon_type]" value="dashicons-arrow-up-alt"' . checked( 'dashicons-arrow-up-alt', $settings['icon_type'], false ) . '/>';
                                                    echo '<label class="dashicon_label" for="catchwebtools_to_top_options_icon_type_2"><span class="dashicon_to_top_admin dashicons dashicons-arrow-up-alt"></span></label>';

                                                    echo '<input type="radio" id="catchwebtools_to_top_options_icon_type_3" name="catchwebtools_to_top_options[icon_type]" value="dashicons-arrow-up-alt2"' . checked( 'dashicons-arrow-up-alt2', $settings['icon_type'], false ) . '/>';
                                                    echo '<label class="dashicon_label" for="catchwebtools_to_top_options_icon_type_3"><span class="dashicon_to_top_admin dashicons dashicons-arrow-up-alt2"></span></label>';
                                                ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Icon Color', 'catch-web-tools' ); ?></th>

                                            <td>
                                                <?php
                                                    echo '<input type="text" class="catchwebtools_to_top_options_icon_color" name="catchwebtools_to_top_options[icon_color]" value="'. sanitize_text_field( $settings['icon_color'] ) .'"/>';
                                                ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Icon Background Color', 'catch-web-tools' ); ?></th>

                                            <td>
                                                <?php
                                                    echo '<input type="text" class="catchwebtools_to_top_options_icon_bg_color" name="catchwebtools_to_top_options[icon_bg_color]" value="'. sanitize_text_field( $settings['icon_bg_color'] ) .'"/>';
                                                ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Icon Size (px)', 'catch-web-tools' ); ?></th>
                                            <td>
                                                <?php
                                                    echo '<input min="0" type="number" id="catchwebtools_to_top_options_icon_size" name="catchwebtools_to_top_options[icon_size]" value="'. absint( $settings['icon_size'] ) .'"/>px';
                                                ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Border Radius (%)', 'catch-web-tools' ); ?></th>
                                            <td>
                                                <?php
                                                    echo '<input min="0" max="50" type="number" id="catchwebtools_to_top_options_border_radius" name="catchwebtools_to_top_options[border_radius]" value="'. absint( $settings['border_radius'] ) .'"/>%';

                                                    echo '<p class="description">'. esc_html__( '0 will make the icon background square, 50 will make it a circle', 'catch-web-tools' ) .'</p>';
                                                ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <?php submit_button( esc_html__( 'Save Changes', 'catch-web-tools' ) ); ?>
                            </div>

                            <h3 class="option-toggle"><a href="#"><?php esc_html_e( 'Image Settings', 'catch-web-tools' ); ?></a></h3>
                            <div class="option-content inside">
                                <table class="form-table">
                                    <tbody>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Image', 'catch-web-tools' ); ?></th>
                                            <td>
                                                <input class="upload-url" size="65" type="url" name="catchwebtools_to_top_options[image]" value="<?php echo esc_url( $settings['image'] ); ?>" />
                                                <input ref="<?php esc_attr_e( 'Insert Image','catch-web-tools' );?>" class="st_upload_button button" name="wsl-image-add" type="button" value="<?php esc_attr_e( 'Change Image','catch-web-tools' );?>" />
                                            </td>
                                        </tr>

                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Image Width (px)', 'catch-web-tools' ); ?></th>
                                            <td>
                                                <?php
                                                    echo '<input min="0" max="200" type="number" size="65" id="catchwebtools_to_top_options[image_width]" name="catchwebtools_to_top_options[image_width]" value="'. absint( $settings['image_width'] ) .'"/>px';
                                                ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Image Alt', 'catch-web-tools' ); ?></th>
                                            <td>
                                                <?php
                                                    echo '<input type="text" size="65" id="catchwebtools_to_top_options[image_alt]" name="catchwebtools_to_top_options[image_alt]" value="'. sanitize_text_field( $settings['image_alt'] ) .'"/>';
                                                ?>
                                            </td>
                                        </tr>
                                     </tbody>
                                </table>
                                <?php submit_button( esc_html__( 'Save Changes', 'catch-web-tools' ) ); ?>
                            </div>

                            <h3 class="option-toggle"><a href="#"><?php esc_html_e( 'Advanced Settings', 'catch-web-tools' ); ?></a></h3>
                            <div class="option-content inside">
                                <table class="form-table">
                                    <tbody>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Location', 'catch-web-tools' ); ?></th>

                                            <td>
                                                <?php
                                                    echo '<select id="catchwebtools_to_top_options[location]" name="catchwebtools_to_top_options[location]">';
                                                        echo '<option value="bottom-right"' . selected( $settings['location'], 'bottom-right', false) . '>'. esc_html__( 'Bottom Right', 'catch-web-tools') .'</option>';
                                                        echo '<option value="bottom-left"' . selected( $settings['location'], 'bottom-left', false) . '>'. esc_html__( 'Bottom Left', 'catch-web-tools') .'</option>';
                                                        echo '<option value="top-right"' . selected( $settings['location'], 'top-right', false) . '>'. esc_html__( 'Top Right', 'catch-web-tools') .'</option>';
                                                        echo '<option value="top-left"' . selected( $settings['location'], 'top-left', false) . '>'. esc_html__( 'Top Left', 'catch-web-tools') .'</option>';
                                                    echo '</select>';
                                                ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Margin X (px)', 'catch-web-tools' ); ?></th>
                                            <td>
                                                <?php
                                                    echo '<input min="1" type="number" id="catchwebtools_to_top_options[margin_x]" name="catchwebtools_to_top_options[margin_x]" value="'. absint( $settings['margin_x'] ) .'"/>px';
                                                ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Margin Y (px)', 'catch-web-tools' ); ?></th>
                                            <td>
                                                <?php
                                                    echo '<input min="1" type="number" id="catchwebtools_to_top_options[margin_y]" name="catchwebtools_to_top_options[margin_y]" value="'. absint( $settings['margin_y'] ) .'"/>px';
                                                ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Show on WP-ADMIN?', 'catch-web-tools' ); ?></th>

                                            <td>
                                                <?php
                                                    echo '<input name="catchwebtools_to_top_options[show_on_admin]" id="catchwebtools_to_top_options[show_on_admin]" type="checkbox" value="1" class="catchwebtools_to_top_options[show_on_admin]" ' . checked( 1, $settings['show_on_admin'], false ) . ' />' . esc_html__( 'Check to Enable','catch-web-tools' );
                                                    echo '<p class="description">' . esc_html__( 'Button will be shown on admin section', 'catch-web-tools' ) .'</p>';
                                                ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Enable Auto Hide', 'catch-web-tools' ); ?></th>

                                            <td>
                                                <?php
                                                    echo '<input name="catchwebtools_to_top_options[enable_autohide]" id="catchwebtools_to_top_options[enable_autohide]" type="checkbox" value="1" class="catchwebtools_to_top_options[enable_autohide]" ' . checked( 1, $settings['enable_autohide'], false ) . ' />' . esc_html__( 'Check to Enable','catch-web-tools' );
                                                ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Auto Hide Time (secs)', 'catch-web-tools' ); ?></th>
                                            <td>
                                                <?php
                                                    echo '<input min="1" type="number" id="catchwebtools_to_top_options[autohide_time]" name="catchwebtools_to_top_options[autohide_time]" value="'. absint( $settings['autohide_time'] ) .'"/>sec(s)';

                                                    echo '<p class="description">'. esc_html__( 'Button will be auto hidden after this duration in seconds, if enabled', 'catch-web-tools' ) .'</p>';
                                                ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Hide on Small Devices?', 'catch-web-tools' ); ?></th>

                                            <td>
                                                <?php
                                                    echo '<input name="catchwebtools_to_top_options[enable_hide_small_device]" id="catchwebtools_to_top_options[enable_hide_small_device]" type="checkbox" value="1" class="catchwebtools_to_top_options[enable_hide_small_device]" ' . checked( 1, $settings['enable_hide_small_device'], false ) . ' />' . esc_html__( 'Check to Enable','catch-web-tools' );
                                                    echo '<p class="description">' . esc_html__( 'Button will be hidden on small devices when the width below matches', 'catch-web-tools' ) .'</p>';
                                                ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Small Device Max Width (px)', 'catch-web-tools' ); ?></th>
                                            <td>
                                                <?php
                                                    echo '<input min="1" type="number" id="catchwebtools_to_top_options[small_device_max_width]" name="catchwebtools_to_top_options[small_device_max_width]" value="'. absint( $settings['small_device_max_width'] ) .'"/>px';

                                                    echo '<p class="description">'. esc_html__( 'Button will be hidden on devices with lesser or equal width', 'catch-web-tools' ) .'</p>';
                                                ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <?php submit_button( esc_html__( 'Save Changes', 'catch-web-tools' ) ); ?>
                            </div>

                            <h3 class="option-toggle"><a href="#"><?php esc_html_e( 'Reset Settings', 'catch-web-tools' ); ?></a></h3>
                            <div class="option-content inside">
                                <table class="form-table">
                                    <tbody>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Reset All Settings', 'catch-web-tools' ); ?></th>

                                            <td>
                                                <?php
                                                    echo '<input name="catchwebtools_to_top_options[reset]" id="catchwebtools_to_top_options[reset]" type="checkbox" value="1" class="catchwebtools_to_top_options[reset]" />' . esc_html__( 'Check to Reset All Settings','catch-web-tools' );
                                                    echo '<p class="description">' . esc_html__( 'Caution: All data will be lost', 'catch-web-tools' ) .'</p>';
                                                ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <?php submit_button( esc_html__( 'Save Changes', 'catch-web-tools' ) ); ?>
                            </div>
                        </div>
                    </form>
                <?php endif; ?>
            </div><!-- .content -->
        </div><!-- .content-wrapper -->
    </div><!-- #customcss -->

<?php include ( 'main-footer.php' ); ?>
