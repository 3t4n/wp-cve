<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @category   PHP
 * @package    Free_Comments_For_Wordpress_Vuukle
 * @subpackage Free_Comments_For_Wordpress_Vuukle/admin/partials
 * @author     Vuukle <info@vuukle.com>
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GPL-2.0+
 * @link       https://vuukle.com
 * @since      1.0.0
 */
if ( $this->check_message_existence( 'error' ) || $this->check_message_existence( 'success' ) || $this->check_message_existence( 'warning' ) ) {
	$message = $this->check_message_existence( 'error' ) ? $this->get_message( 'error' ) : ( $this->check_message_existence( 'warning' ) ? $this->get_message( 'warning' ) : $this->get_message( 'success' ) );
	?>
    <div class="notice notice-<?= $this->check_message_existence( 'error' ) ? 'error' : ( $this->check_message_existence( 'warning' ) ? 'warning' : 'success' ) ?> is-dismissible">
        <p><strong><?= $message; ?></strong></p>
    </div>
	<?php
	$this->unset_sessions();
}
?>
<div class="wrap vuukle-settings-page">
    <img style="position: absolute; right: 40px; width: 35px;"
         src="<?php echo $this->attributes['admin_dir_url']; ?>images/logo.png"/>
    <h2>Vuukle Settings</h2>
    <p>Vuukle Commenting is automatically displayed in place of WordPress default comments. You can also insert Vuukle
        Commenting system to any other part of your website by using ShortCode <code>[vuukle]</code>.</p>
    <p>We use <code>&lt;og:image&gt;</code> tag as post image, so please make sure you have them ,otherwise we will
        display default "no-image" image.</p>
    <a target="_blank" style="background-color: #EB8D40; color: white" class="button" href="https://admin.vuukle.com/">Login
        to Vuukle Admin</a>
    <p>
        <a target="_blank" href="https://admin.vuukle.com/forgot-password.html">
            <button class="button button-primary">Forgot password</button>
        </a>
    </p>
	<?php if ( empty( $app_id ) ) : ?>
        <div class="vuukle_overlay">
            <div class="vuukle_popup">
                <div class="vuukle_popup_open">
                    <div class="vuukle_popup_info">
                        <h2 style="text-align:center;"><?php _e( "To get your APIKEY please send us an email", esc_html( $this->plugin_name ) ); ?>
                            <br><br><a href="mailto:support@vuukle.com">support@vuukle.com</a></h2>
                    </div>
                </div>
                <div class="vuukle_popup_close">
                    <a class='vuukle_closer_icon'><i class="fas fa-times-circle vuukle_closer_icon"></i></a>
                </div>
            </div>
        </div>
	<?php endif; ?>
    <form method="post" action="<?= admin_url( 'admin-post.php' ); ?>" id="vuukle-settings-form">
		<?php require $this->attributes['admin_dir_path'] . 'partials/' . $this->attributes['name'] . '-save-remind-modal.php'; ?>
        <div class="nav-tab-wrapper">
            <a href="#tab1" data-tab="tab1"
               class="nav-tab <?php echo ( $tab == 'tab1' ) ? 'nav-tab-active' : ''; ?>"><?php _e( "General settings",
					esc_html( $this->plugin_name ) ); ?></a>
            <a href="#tab2" data-tab="tab2"
               class="nav-tab <?php echo ( $tab == 'tab2' ) ? 'nav-tab-active' : ''; ?>"><?php _e( "Share Bar widget settings",
					esc_html( $this->plugin_name ) ); ?></a>
            <a href="#tab4" data-tab="tab4"
               class="nav-tab <?php echo ( $tab == 'tab4' ) ? 'nav-tab-active' : ''; ?>"><?php _e( "Emote widget setting",
					esc_html( $this->plugin_name ) ); ?></a>
            <a href="#tab5" data-tab="tab5"
               class="nav-tab <?php echo ( $tab == 'tab5' ) ? 'nav-tab-active' : ''; ?>"><?php _e( "Comment widget settings",
					esc_html( $this->plugin_name ) ); ?></a>
            <a href="#tab6" data-tab="tab6"
               class="nav-tab <?php echo ( $tab == 'tab6' ) ? 'nav-tab-active' : ''; ?>"><?php _e( "Web push notifications",
                    esc_html( $this->plugin_name ) ); ?></a>
        </div>
        <div id="tab1" class="vuukle-tab-content <?php echo ( $tab == 'tab1' ) ? 'vuukle-tab-content-active' : ''; ?>">
            <table class="form-table settings-table">
                <tr>
                    <th colspan="2">
                        <h2 class="title-setting">General settings</h2>
                    </th>
                </tr>
                <tr>
                    <th scope="row">
                        API-KEY
                    </th>
                    <td>
                        <input name="AppId" type="text" value="<?php print esc_attr( $app_id ); ?>"
                               class="regular-text"/>
						<?php if ( empty( $app_id ) ) : ?>
                            <button type="button" id="quick_register" class="button button-primary">Get API-KEY</button>
						<?php endif ?>
                        <span class="api-key-loading" style="display: none">Please wait ...</span>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        Save comments (WP Database)<a class="vuukle_help" data-toggle="tooltip"
                                                      title="<?php _e( "Turn on this option and your comments will be saved in your wordpress database", esc_html( $this->plugin_name ) ); ?>">
                            <i class="fas fa-info-circle"></i>
                        </a>
                    </th>
                    <td>
                        Off
                        <input type="radio" name="save_comments" value="0" checked="checked"/>
                        On
                        <input type="radio" name="save_comments"
                               value="1" <?php checked( $settings['save_comments'], 1 ); ?> />
                    </td>
                </tr>

                <tr>
                    <th>Remove vuukle from the posts (ids, separated by comma) <a class="vuukle_help"
                                                                                  data-toggle="tooltip"
                                                                                  title="<?php _e( "Select the posts from which you want to remove vuukle", esc_html( $this->plugin_name ) ); ?>">
                            <i class="fas fa-info-circle"></i>
                        </a></th>
                    <td>
                        <input type="text" name="post_exceptions"
                               value="<?php echo esc_attr( $settings['post_exceptions'] ); ?>"/>
                    </td>
                </tr>
                <tr>
                    <th>Remove vuukle from the post types (slugs, separated by comma)<a class="vuukle_help"
                                                                                        data-toggle="tooltip"
                                                                                        title="<?php _e( " Select the post type from which you want to remove vuukle", esc_html( $this->plugin_name ) ); ?>">
                            <i class="fas fa-info-circle"></i>
                        </a></th>
                    <td>
                        <input type="text" name="post_type_exceptions"
                               value="<?php echo esc_attr( $settings['post_type_exceptions'] ); ?>"/>
                    </td>
                </tr>
                <tr>
                    <th>Remove vuukle from posts by slug (put slugs comma separated)<a class="vuukle_help"
                                                                                      data-toggle="tooltip"
                                                                                      title="<?php _e( 'Select the slug from the URL from which you want to remove vuukle.',
						                                                                  esc_html( $this->plugin_name ) ); ?>">
                            <i class="fas fa-info-circle"></i>
                        </a></th>
                    <td>
                        <input type="text" name="post_type_by_url_exceptions"
                               value="<?php echo esc_attr( $settings['post_type_by_url_exceptions'] ); ?>"/>
                    </td>
                </tr>
                <tr>
                    <th>Remove vuukle from the categories (slugs, separated by comma) <a class="vuukle_help"
                                                                                         data-toggle="tooltip"
                                                                                         title="<?php _e( "Select the categories  from which you want to remove vuukle", esc_html( $this->plugin_name ) ); ?>">
                            <i class="fas fa-info-circle"></i>
                        </a></th>
                    <td>
                        <input type="text" name="category_exceptions"
                               value="<?php echo esc_attr( $settings['category_exceptions'] ); ?>"/>
                    </td>
                </tr>
                <tr>
                    <th>Export comments <a class="vuukle_help" data-toggle="tooltip"
                                           title="<?php _e( "Select how many comments you want to export", esc_html( $this->plugin_name ) ); ?>">
                            <i class="fas fa-info-circle"></i>
                        </a></th>
                    <td>
                        <input style="width: 78px;height: 29px !important;min-height: 29px;" class="amount_comments"
                               type="number" name="amount_comments"
                               value="<?php echo esc_attr( $settings['amount_comments'] ); ?>"/>
                        <input type="button" id="export_button" data-offset="0" class="button button-primary"
                               name="export_botton" value="Download File"/>
                        <span class="loader-animation" style="display: none;"><strong>Please wait ...</strong></span>
                    </td>
                </tr>
                <tr class="embed_fields_emotes">
                    <th>Enable for AMP
                        <a class="vuukle_help" data-toggle="tooltip"
                           title="<?php esc_html_e( $settings['embed_emotes_amp'] === 'on' ? "If you don't use Google AMP ( Accelerated Mobile Pages ) uncheck the checkbox please."
							   : "If you use Google AMP ( Accelerated Mobile Pages ) check the checkbox please.", esc_html( $this->plugin_name ) ); ?>">
                            <i class="fas fa-info-circle"></i>
                        </a>
                    </th>
                    <td>
                        <input type="checkbox" name="embed_emotes_amp"
                               value="on" <?php echo checked( $settings['embed_emotes_amp'], 'on' ); ?> />
                    </td>
                </tr>
                <tr>
                    <th>Track page views on non article pages
                        <a class="vuukle_help" data-toggle="tooltip"
                           title="<?php _e( "Tracks pages' views on non article pages", esc_html( $this->plugin_name ) ); ?>">
                            <i class="fas fa-info-circle"></i>
                        </a>
                    </th>
                    <td>
                        <input type="checkbox" name="non_article_pages"
                               value="on" <?php checked( $settings['non_article_pages'], 'on' ) ?> />
                    </td>
                </tr>
            </table>
        </div>
        <div id="tab2" class="vuukle-tab-content <?php echo ( $tab == 'tab2' ) ? 'vuukle-tab-content-active' : ''; ?>">
            <table class="form-table settings-table">
                <tr>
                    <th colspan="2">
                        <h2 class="title-setting">Share Bar widget settings</h2>
                    </th>
                </tr>
                <tr>
                    <th scope="row">
                        Show Share Bar
                        <a class="vuukle_help"
                           data-toggle="tooltip"
                           title="<?php _e( "Choose to show ShareBar or not", esc_html( $this->plugin_name ) ); ?>">
                            <i class="fas fa-info-circle"></i>
                        </a>
                    </th>
                    <td>
                        Off
                        <input type="radio" name="share" value="0" checked="checked"/>
                        On
                        <input type="radio" name="share" value="1" <?php checked( $settings['share'], 1 ); ?> />
                        <img src="<?php echo $this->attributes['admin_dir_url'] . 'images/share.png'; ?>"/>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        Enable horizontal for mobile and vertical for desktop
                        <a class="vuukle_help" data-toggle="tooltip"
                           title="<?php _e( "Choose the position to be displayed (horizontal or vertical)", esc_html( $this->plugin_name ) ); ?>">
                            <i class="fas fa-info-circle"></i>
                        </a>
                    </th>
                    <td>
                        Yes
                        <input type="radio" name="enable_h_v"
                               value="yes" <?php checked( $settings['enable_h_v'], 'yes' ); ?> />
                        No
                        <input type="radio" name="enable_h_v"
                               value="no" <?php checked( $settings['enable_h_v'], 'no' ); ?> />
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        Share Bar Type
                        <a class="vuukle_help"
                           data-toggle="tooltip"
                           title="<?php _e( "Choose the position to be displayed (horizontal or vertical)", esc_html( $this->plugin_name ) ); ?>">
                            <i class="fas fa-info-circle"></i>
                        </a>
                    </th>
                    <td>
                        Horizontal
                        <input type="checkbox" name="share_type" value="horizontal"
							<?php checked( $settings['share_type'], 'horizontal' ); ?>
							<?= $settings['enable_h_v'] === "yes" ? 'disabled' : '' ?> >
                        Vertical
                        <input type="checkbox" name="share_type_vertical" value="vertical"
							<?php checked( $settings['share_type_vertical'], 'vertical' ); ?>
							<?= $settings['enable_h_v'] === "yes" ? 'disabled' : '' ?> >
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        Share Bar Position
                        <a class="vuukle_help"
                           data-toggle="tooltip"
                           title="<?php _e( "Select the location where you want to display the share bar", esc_html( $this->plugin_name ) ); ?>">
                            <i class="fas fa-info-circle"></i>
                        </a>
                    </th>
                    <td>
                        After Content Post
                        <input type="checkbox" name="share_position" value="1"
							<?php checked( $settings['share_position'], '1' ); ?>
							<?= $settings['enable_h_v'] === 'no' && $settings['share_type'] !== 'horizontal' ? 'disabled' : '' ?>>
                        Before Content Post
                        <input type="checkbox" name="share_position2" value="1"
							<?php checked( $settings['share_position2'], '1' ); ?>
							<?= $settings['enable_h_v'] === 'no' && $settings['share_type'] !== 'horizontal' ? 'disabled' : '' ?>>
                    </td>
                </tr>
                <tr>
                    <th>
                        DIV Container Class Horizontal
                        <a class="vuukle_help"
                           data-toggle="tooltip"
                           title="<?php _e( "Specify the element Class under which you want the ShareBar to be displayed", esc_html( $this->plugin_name ) ); ?>">
                            <i class="fas fa-info-circle"></i>
                        </a>
                    </th>
                    <td>
						<?php
						$disable_share_custom_divs = $settings['enable_h_v'] === 'yes' || $settings['share_type'] !== 'horizontal' || ! empty( $settings['share_position'] ) || ! empty( $settings['share_position2'] );
						?>
                        <input type="radio" id="embed_powerbar1" name="embed_powerbar" value="1"
							<?php checked( $settings['embed_powerbar'], '1' ); ?>
							<?= $disable_share_custom_divs ? 'disabled' : '' ?>>
                        <input type="text" id="div_class_powerbar1" name="div_class_powerbar"
                               value="<?= esc_attr( $settings['div_class_powerbar'] ); ?>"
							<?= $disable_share_custom_divs ? 'disabled' : '' ?>>
                    </td>
                </tr>
                <tr>
                    <th>
                        DIV Container ID Horizontal
                        <a class="vuukle_help"
                           data-toggle="tooltip"
                           title="<?php _e( "Specify the element ID under which you want the ShareBar to be displayed", esc_html( $this->plugin_name ) ); ?>">
                            <i class="fas fa-info-circle"></i>
                        </a>
                    </th>
                    <td>
                        <input type="radio" id="embed_powerbar2" name="embed_powerbar" value="2"
							<?php checked( $settings['embed_powerbar'], '2' ); ?>
							<?= $disable_share_custom_divs ? 'disabled' : '' ?>>
                        <input type="text" name="div_id_powerbar" id="div_id_powerbar"
                               value="<?= esc_attr( $settings['div_id_powerbar'] ); ?>"
							<?= $disable_share_custom_divs ? 'disabled' : '' ?>>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        Share Bar Styles (only for vertical type)
                        <a class="vuukle_help"
                           data-toggle="tooltip"
                           title="<?php _e( "Write the styles of your preference(only for vertical type)", esc_html( $this->plugin_name ) ); ?>">
                            <i class="fas fa-info-circle"></i>
                        </a>
                    </th>
                    <td>
                        <textarea style="height:120px"
                                  name="share_vertical_styles"><?php echo esc_attr( $settings['share_vertical_styles'] ); ?></textarea>
                    </td>
                </tr>
            </table>
        </div>
        <div id="tab4" class="vuukle-tab-content <?php echo ( $tab == 'tab4' ) ? 'vuukle-tab-content-active' : ''; ?>">
            <table class="form-table settings-table">
                <tr>
                    <th colspan="2">
                        <h2 class="title-setting">Emote widget settings</h2>
                    </th>
                </tr>
                <tr>
                    <th scope="row">
                        Show Emote at the end of each post
                        <a class="vuukle_help" data-toggle="tooltip"
                           title="<?php _e( "Select this option to place Reactions at the end of each post․ (To disable Reactions uncheck the box)", esc_html( $this->plugin_name ) ); ?>">
                            <i class="fas fa-info-circle"></i>
                        </a>
                        <br>
                        <span style="font-weight: normal">(To disable emoji uncheck the box)</span>
                    </th>
                    <td colspan="3">
                        Off
                        <input type="radio" name="emote" value="false" checked="checked"/>
                        On
                        <input type="radio" name="emote" value="true" <?php checked( $settings['emote'], 'true' ); ?> />

                        <img style="width: 400px"
                             src="<?php echo $this->attributes['admin_dir_url'] . 'images/emote.png'; ?>"/>
                        <br>

                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        Widget width <a class="vuukle_help" data-toggle="tooltip"
                                        title="<?php esc_html_e( "Specify the Widget width", $this->plugin_name ); ?>">
                            <i class="fas fa-info-circle"></i>
                        </a>
                        <br/>
                    </th>
                    <td>
                        <input type="number" name="emote_widget_width"
                               value="<?php echo esc_attr( $settings['emote_widget_width'] ); ?>" placeholder="600"> px
                    </td>
                </tr>
                <tr>
                    <th>Reactions embed Method <a class="vuukle_help" data-toggle="tooltip"
                                                  title="<?php esc_html_e( "Select this option if you want to install Reactions after the content", $this->plugin_name ); ?>">
                            <i class="fas fa-info-circle"></i>
                        </a></th>
                    <td>
                        <label>
                            <input type="radio" name="embed_emotes"
                                   value="0" <?php checked( $settings['embed_emotes'], '0' ); ?> />
                            Insert After the Content
                        </label>
                    </td>
                </tr>
                <tr class="embed_fields_emotes">
                    <th>DIV Container Class <a class="vuukle_help" data-toggle="tooltip"
                                               title="<?php esc_html_e( "Specify the element Class under which you want the Reactions to be displayed", $this->plugin_name ); ?>">
                            <i class="fas fa-info-circle"></i>
                        </a></th>
                    <td>
                        <input type="radio" name="embed_emotes"
                               value="1" <?php checked( $settings['embed_emotes'], '1' ); ?> />
                        <input type="text" <?php echo ( '1' === $settings['embed_emotes'] ) ? 'class="reg1"' : ''; ?>
                               name="div_class_emotes" value="<?php echo esc_attr( $settings['div_class_emotes'] ); ?>">
                    </td>
                </tr>
                <tr class="embed_fields_emotes">
                    <th>DIV Container ID <a class="vuukle_help" data-toggle="tooltip"
                                            title="<?php esc_html_e( "Specify the element ID under which you want the Reactions to be displayed", $this->plugin_name ); ?>">
                            <i class="fas fa-info-circle"></i>
                        </a></th>
                    <td>
                        <input type="radio" name="embed_emotes"
                               value="2" <?php checked( $settings['embed_emotes'], '2' ); ?> />
                        <input type="text" <?php echo ( '2' === $settings['embed_emotes'] ) ? 'class="reg1"' : ''; ?>
                               name="div_id_emotes" value="<?php echo esc_attr( $settings['div_id_emotes'] ); ?>">
                    </td>
                </tr>
            </table>
        </div>
        <div id="tab5" class="vuukle-tab-content <?php echo ( $tab == 'tab5' ) ? 'vuukle-tab-content-active' : ''; ?>">
            <table class="form-table settings-table">
                <tr>
                    <th colspan="2">
                        <h2 class="title-setting">Comment widget settings</h2>
                    </th>
                </tr>
                <tr>
                    <th>
                        Enable comments <a class="vuukle_help" data-toggle="tooltip"
                                           title="<?php esc_html_e( ! empty( $settings['enabled_comments'] ) && $settings['enabled_comments'] === "true" ? "Please choose option No if you don't want enable comments"
							                   : "Please choose option Yes if you want enable comments", $this->plugin_name ); ?>">
                            <i class="fas fa-info-circle"></i>
                        </a>
                    </th>
                    <td>
                        No
                        <input type="radio" name="enabled_comments" value="false" checked="checked"/>
                        Yes
                        <input type="radio" name="enabled_comments"
                               value="true" <?php checked( $settings['enabled_comments'], 'true' ); ?> />
                    </td>
                </tr>
                <tr>
                    <th>
                        Comments Embed Method <a class="vuukle_help" data-toggle="tooltip"
                                                 title="<?php esc_html_e( "Choose how comments are shown ․ Replace WordPress Comments  or Insert After the Content", $this->plugin_name ); ?>">
                            <i class="fas fa-info-circle"></i>
                        </a>
                    </th>
                    <td>
                        <label>
                            <input type="radio" name="embed_comments"
                                   value="1" <?php checked( $settings['embed_comments'], '1' ); ?> />
                            Replace WordPress Comments
                        </label>
                        <br><br>
                        <label>
                            <input type="radio" name="embed_comments"
                                   value="2" <?php checked( $settings['embed_comments'], '2' ); ?> />
                            Insert After the Content
                        </label>
                    </td>
                </tr>
                <tr class="embed_fields">
                    <th>DIV Container Class <a class="vuukle_help" data-toggle="tooltip"
                                               title="<?php esc_html_e( "Specify the element Class under which you want the Comments to be displayed", $this->plugin_name ); ?>">
                            <i class="fas fa-info-circle"></i>
                        </a></th>
                    <td>
                        <input type="radio" name="embed_comments"
                               value="3" <?php checked( $settings['embed_comments'], '3' ); ?> />
                        <input type="text" <?php echo ( '3' === $settings['embed_comments'] ) ? 'class="reg"' : ''; ?>
                               name="div_class" value="<?php echo esc_attr( $settings['div_class'] ); ?>">
                    </td>
                </tr>
                <tr class="embed_fields">
                    <th>DIV Container ID <a class="vuukle_help" data-toggle="tooltip"
                                            title="<?php esc_html_e( "Specify the element ID under which you want the Comments to be displayed", $this->plugin_name ); ?>">
                            <i class="fas fa-info-circle"></i>
                        </a></th>
                    <td>
                        <input type="radio" name="embed_comments"
                               value="4" <?php checked( $settings['embed_comments'], '4' ); ?> />
                        <input type="text" <?php echo ( '4' === $settings['embed_comments'] ) ? 'class="reg"' : ''; ?>
                               name="div_id" value="<?php echo esc_attr( $settings['div_id'] ); ?>">
                    </td>
                </tr>
            </table>
        </div>
        <div id="tab6" class="vuukle-tab-content <?php echo ( $tab == 'tab6' ) ? 'vuukle-tab-content-active' : ''; ?>">
            <table class="form-table settings-table">
                <tr>
                    <th>Web push notifications
                        <a class="vuukle_help" data-toggle="tooltip"
                           title="<?php _e( "Enable web push notifications", esc_html( $this->plugin_name ) ); ?>">
                        </a> <br>
                        <span style="font-size: 11px; color: grey;font-weight: normal">please enable web push notifications also from</span>
                        <a style="font-size: 11px" 
                           target="_blank"
                           href="https://dash.vuukle.com/cloud-messaging/?tab=0&host="
                           title="<?php _e( "Vuukle dashboard", $this->plugin_name ); ?>">
	                        <?php _e( "Vuukle dashboard", $this->plugin_name ); ?>
                        </a>
                    </th>
                    <td>
                        <input type="checkbox" name="web_push_notifications"
                               value="on" <?php checked( $settings['web_push_notifications'], 'on' ) ?> />
                    </td>
                </tr>
            </table>
        </div>
        
        <input name="nonce" type="hidden" value="<?php echo esc_attr( wp_create_nonce( $this->settings_name ) ); ?>"/>
        <input name="action" id="action" type="hidden" value="vuukleSaveSettings"/>
        <input type="hidden" name="tab" value="<?php echo esc_html( $tab ); ?>" id="hidden_tab">
        <input type="hidden" value="<?php echo esc_url( $url ); ?>" id="hidden_url">
		<?php wp_referer_field(); ?>
        <div class="submit" id="vis">
            <input id="save-settings" type="submit" value="Save Settings" class="button-primary"/>
            <input id="reset-settings" type="submit" value="Reset to Default" class="button-primary"/>
        </div>
    </form>
    <p>To export your comments to Vuukle please contact our support at support@vuukle.com</p>
</div>