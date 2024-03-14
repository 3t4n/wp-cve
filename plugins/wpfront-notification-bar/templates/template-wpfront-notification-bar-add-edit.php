<?php
/*
  WPFront Notification Bar Plugin
  Copyright (C) 2013, WPFront.com
  Website: wpfront.com
  Contact: syam@wpfront.com

  WPFront Notification Bar Plugin is distributed under the GNU General Public License, Version 3,
  June 2007. Copyright (C) 2007 Free Software Foundation, Inc., 51 Franklin
  St, Fifth Floor, Boston, MA 02110, USA

  THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
  ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
  WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
  DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
  ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
  (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
  LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
  ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
  (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
  SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

/**
 * Template for WPFront Notification Bar Options
 *
 * @author Syam Mohan <syam@wpfront.com>
 * @copyright 2013 WPFront.com
 */

namespace WPFront\Notification_Bar;

if (!defined('ABSPATH')) exit();

require_once(dirname(__DIR__) . "/classes/class-wpfront-notification-bar.php");

if (!class_exists('\WPFront\Notification_Bar\WPFront_Notification_Bar_Add_Edit_View')) {

    class WPFront_Notification_Bar_Add_Edit_View {

        /**
         * Controller object
         *
         * @var WPFront_Notification_Bar_Controller
         */
        protected $controller;
        /**
         * Options object
         *
         * @var WPFront_Notification_Bar_Options
         */
        protected $options;

        /**
         * Contructor
         *
         * @param WPFront_Notification_Bar_Controller $controller
         */
        public function __construct($controller) {
            $this->controller = $controller;
            $this->options = $this->controller->get_options();
        }

        public function view() {
            ?> 
            <div class="wrap notification-bar-add-edit" id="notification-bar-add-edit">
                <?php $this->title(); ?>
                <div id="wpfront-notification-bar-options" class="inside">
                    <?php
                    $action = apply_filters('wpfront_notification_bar_options_page_action', 'options.php');
                    ?>
                    <form id="wpfront-notification-bar-options-form" method="post" action="">
                        <?php
                        settings_fields(WPFront_Notification_Bar::OPTIONS_GROUP_NAME);
                        do_settings_sections('wpfront-notification-bar');

                        if ((isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true') || (isset($_GET['updated']) && $_GET['updated'] == 'true')) { //TODO: w3tc test
                            ?>
                            <div class="updated">
                                <p>
                                    <strong><?php echo __('If you have a caching plugin, clear the cache for the new settings to take effect.', 'wpfront-notification-bar'); ?></strong>
                                </p>
                            </div>
                            <?php
                        }
                        ?>
                        <?php $this->create_meta_boxes(); ?>
                        <div id="poststuff">
                            <div id="post-body" class="metabox-holder columns-2" style="display:flow-root">
                                <div id="post-body-content" style="position:relative">
                                    <?php do_meta_boxes($this->controller->get_menu_slug(), 'normal', null); ?>
                                </div>
                                <div id="postbox-container-1" class="postbox-container" style="position: sticky; top: 40px;">
                                    <?php do_meta_boxes($this->controller->get_menu_slug(), 'side', null); ?>
                                </div>
                            </div>
                        </div>      
                        <?php $this->nonce_field(); ?>               
                        <?php $this->script(); ?>
                        <input type="hidden" name="wpfront-notification-bar-options[last_saved]" value="<?php echo time(); ?>" />
                        <?php submit_button(null, 'primary', 'submit2', false); ?>
                    </form>
                </div>
            </div>
            <?php
        }

        protected function title() {
            ?>
            <h2>
                <?php echo __('WPFront Notification Bar Settings', 'wpfront-notification-bar'); ?>
            </h2>
            <?php
        }

        public function name_field() {
            
        }

        public function nonce_field() {
            
        }

        protected function get_meta_box_groups() {
            return [
                (object) [
                    'group_name' => 'Display_Settings',
                    'title' => __('Display', 'wpfront-notification-bar'),
                    'render' => 'postbox_notification_bar_display_settings'
                ],
                (object) [
                    'group_name' => 'Content_Settings',
                    'title' => __('Content', 'wpfront-notification-bar'),
                    'render' => 'postbox_notification_bar_content_settings'
                ],
                (object) [
                    'group_name' => 'Filter_Settings',
                    'title' => __('Filter', 'wpfront-notification-bar'),
                    'render' => 'postbox_notification_bar_filter_settings'
                ],
                (object) [
                    'group_name' => 'Color_Settings',
                    'title' => __('Color', 'wpfront-notification-bar'),
                    'render' => 'postbox_notification_bar_color_settings'
                ],
                (object) [
                    'group_name' => 'CSS_Settings',
                    'title' => __('CSS', 'wpfront-notification-bar'),
                    'render' => 'postbox_notification_bar_css_settings'
                ]
            ];
        }

        protected function create_meta_boxes() {
            $groups = $this->get_meta_box_groups();

            foreach ($groups as $group) {
                add_meta_box("postbox-{$group->group_name}", $group->title, array($this, $group->render), $this->controller->get_menu_slug(), 'normal', 'default', $group);
            }
            add_meta_box("postbox-side-1", __('Action', 'wpfront-notification-bar'), array($this, 'action_buttons'), $this->controller->get_menu_slug(), 'side', 'default', $group);
            $this->upgrade_to_pro_metabox($group);

            wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false);
            wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false);
        }

        public function upgrade_to_pro_metabox($group) {
            if(!$this->upgrade_to_pro_metabox_enabled()) {
                return;
            }

            add_meta_box("postbox-side-2", __('Upgrade to Pro', 'wpfront-notification-bar'), array($this, 'free_to_pro'), $this->controller->get_menu_slug(), 'side', 'default', $group);
        }

        /**
         * Whether PRO metabox is required.
         *
         * @return boolean
         */
        protected function upgrade_to_pro_metabox_enabled() {
            return true;
        }

        public function action_buttons() {
            submit_button();
        }

        public function free_to_pro() {
            ?>
            <div class="free-to-pro">
                <p>
                    <?php esc_html_e('PRO version offers the following features.', 'wpfront-notification-bar'); ?>
                </p>
                <ul>
                    <li><?php echo __('Create Multiple Bars', 'wpfront-notification-bar'); ?></li>     
                    <li><?php echo __('Advanced Editor', 'wpfront-notification-bar'); ?></li>
                    <li><?php echo __('Recurring Schedule', 'wpfront-notification-bar'); ?></li>
                    <li><?php echo __('Custom Capabilities', 'wpfront-notification-bar'); ?></li>
                    <li><?php echo __('Premium Support', 'wpfront-notification-bar'); ?></li>
                </ul>   
                <?php $this->discount_code(); ?>
                <p class="upgrade-button">
                    <a class="button button-primary" href="https://wpfront.com/notification-bar-pro/" target="_blank" rel="noopener noreferrer"><?php echo __('Upgrade', 'wpfront-notification-bar'); ?></a>
                </p>
            </div>
            <?php
        }

        private function discount_code() {
            $now = time();
            $until = strtotime("2023-01-31");
            if ($now < $until) {
                ?>
                <p class="discount-tip">
                    <?php esc_html_e('Use the following code to purchase the PRO version on a discount:', 'wpfront-notification-bar'); ?>
                </p>
                <p class="discount-code">
                    NBJANEP
                </p>
                <?php
            }
        }

        public function postbox_notification_bar_display_settings() {
            ?>
            <table class="form-table">
                <?php $this->name_field(); ?>
                <tr>
                    <th scope="row">
                        <?php echo __('Enabled', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <input type="checkbox" name="wpfront-notification-bar-options[enabled]" v-model="enabled" />
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo __('Preview Mode', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <input type="checkbox" name="wpfront-notification-bar-options[preview_mode]" v-model="preview_mode" />&#160;
                        <?php
                        if ($this->options->preview_mode) {
                            $url = $this->controller->get_preview_url();
                            ?>
                            <span class="description"><a target="_blank" rel="noopener" href="<?php echo $url; ?>"><?php echo $url; ?></a></span>
                            <?php
                        } else {
                            $description = __('You can test the notification bar without enabling it.', 'wpfront-notification-bar');
                            $this->echo_help_tooltip($description);
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo __('Debug Mode', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <input type="checkbox" name="wpfront-notification-bar-options[debug_mode]" v-model="debug_mode" />&#160;
                        <?php
                        $description = __('Enable to see logs in browser.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                        <span class="description">&#160;
                            <a target="_blank" rel="noopener" href="https://wpfront.com/wordpress-plugins/notification-bar-plugin/wpfront-notification-bar-troubleshooting/"><?php echo __('How to?', 'wpfront-notification-bar'); ?></a>
                        </span>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo __('Position', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <select name="wpfront-notification-bar-options[position]" v-model="position">&#160;
                            <option value="1" ><?php echo __('Top', 'wpfront-notification-bar'); ?></option>
                            <option value="2" ><?php echo __('Bottom', 'wpfront-notification-bar'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo __('Fixed at Position', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <input type="checkbox" name="wpfront-notification-bar-options[fixed_position]" v-model="fixed_position" />&#160;
                        <?php
                        $description = __('Sticky Bar, bar will stay at position regardless of scrolling.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo __('Theme Sticky Selector', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <input class="regular-text" type="text" name="wpfront-notification-bar-options[theme_sticky_selector]" v-model="theme_sticky_selector" />&#160;
                        <?php
                        $description = __('If your page already has a sticky bar enter the element selector here. For example, for Avada theme it will be "<b>.fusion-is-sticky .fusion-header</b>".', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo  __('Display on Scroll', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <input type="checkbox" name="wpfront-notification-bar-options[display_scroll]" v-model="display_scroll"/>&#160;
                        <?php
                        $description = __('Displays the bar on window scroll.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo __('Scroll Offset', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <input type="text" class="seconds" name="wpfront-notification-bar-options[display_scroll_offset]" v-model="display_scroll_offset" />&#160;<?php echo __('px', 'wpfront-notification-bar'); ?>&#160;
                        <?php
                        $description = __('Number of pixels to be scrolled before the bar appears.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo __('Bar Height', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <input type="text" class="seconds" name="wpfront-notification-bar-options[height]" v-model="height" />&#160;<?php echo __('px', 'wpfront-notification-bar'); ?>&#160;
                        <?php
                        $description = __('Set 0px to auto fit contents.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo __('Position Offset', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <input type="text" class="seconds" name="wpfront-notification-bar-options[position_offset]" v-model="position_offset" />&#160;<?php echo __('px', 'wpfront-notification-bar'); ?>&#160;
                        <?php
                        $description = __('(Top bar only) If you find the bar overlapping, try increasing this value. (eg. WordPress 3.8 Twenty Fourteen theme, set 48px)', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo __('Display After', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <input type="text" class="seconds" name="wpfront-notification-bar-options[display_after]" v-model="display_after" />&#160;
                        <?php echo __('second(s)', 'wpfront-notification-bar'); ?>&#160;
                        <?php
                        $description = __('Set 0 second(s) to display immediately. Does not work in "<b>Display on Scroll</b>" mode.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo __('Animation Duration', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <input type="text" class="seconds" name="wpfront-notification-bar-options[animate_delay]" v-model="animate_delay" />&#160;<?php echo __('second(s)', 'wpfront-notification-bar'); ?>&#160;
                        <?php
                        $description = __('Set 0 second(s) for no animation.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo __('Display Close Button', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <input type="checkbox" name="wpfront-notification-bar-options[close_button]" v-model="close_button"/>&#160;
                        <?php
                        $description = __('Displays a close button at the top right corner of the bar.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>           
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo __('Auto Close After', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <input type="text" class="seconds" name="wpfront-notification-bar-options[auto_close_after]" v-model="auto_close_after" />&#160;<?php echo __('second(s)', 'wpfront-notification-bar'); ?>&#160;
                        <?php
                        $description = __('Set 0 second(s) to disable auto close. Do not work in "<b>Display on Scroll</b>" mode.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo __('Display Shadow', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <input type="checkbox" name="wpfront-notification-bar-options[display_shadow]" v-model="display_shadow" />
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo __('Display Reopen Button', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <input type="checkbox" name="wpfront-notification-bar-options[display_open_button]" v-model="display_open_button" />&#160;
                        <?php
                        $description = __('A reopen button will be displayed after the bar is closed.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo __('Reopen Button Image URL', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <input type="text" id="reopen-button-image-url" class="URL" name="wpfront-notification-bar-options[reopen_button_image_url]" v-model="reopen_button_image_url"/>
                        <input type="button" id="media-library-button" class="button" value="<?php echo __('Media Library', 'wpfront-notification-bar'); ?>"  @click="mediaLibrary"/>&#160;
                        <?php
                        $description = __('Set empty value to use default images.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo __('Reopen Button Offset', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <input type="text" class="seconds" name="wpfront-notification-bar-options[reopen_button_offset]" v-model="reopen_button_offset" />&#160;<?php echo __('px', 'wpfront-notification-bar'); ?>&#160;
                        <?php
                        $description = __('Moves the button more to the left.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo __('Keep Closed', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <input type="checkbox" name="wpfront-notification-bar-options[keep_closed]" v-model="keep_closed"/>&#160;
                        <?php
                        $description = __('Once closed, bar will display closed on other pages.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo __('Keep Closed For', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <input type="text" class="seconds" name="wpfront-notification-bar-options[keep_closed_for]" v-model="keep_closed_for" />&#160;<?php echo __('day(s)', 'wpfront-notification-bar'); ?>&#160;
                        <?php
                        $description = __('Bar will be kept closed for the number of days specified from last closed date.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>                  
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo  __('Keep Closed Cookie Name', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <input class="regular-text" type="text" name="wpfront-notification-bar-options[keep_closed_cookie_name]" v-model="keep_closed_cookie_name" />&#160;
                        <?php
                        $description = __('Cookie name used to mark keep closed days. Changing this value will allow you to bypass "<b>Keep Closed For</b>" days and show the notification again.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo __('Set Max Views', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <input type="checkbox" name="wpfront-notification-bar-options[set_max_views]" v-model="set_max_views"/>&#160;
                        <?php
                        $description = __('Bar will be hidden after a certain number of views.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo __('Max Views', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <input type="text" class="seconds" name="wpfront-notification-bar-options[max_views]" v-model="max_views" />&#160;<?php echo __('time(s)', 'wpfront-notification-bar'); ?>&#160;
                        <?php
                        $description = __('Maximum number of views.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo __('Max Views For', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <input type="text" class="seconds" name="wpfront-notification-bar-options[max_views_for]" v-model="max_views_for" />&#160;<?php echo __('day(s)', 'wpfront-notification-bar'); ?>&#160;
                        <?php
                        $description = __('Bar will be kept closed for the number of days specified. Zero means current session.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo __('Max Views Cookie Name', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <input class="regular-text" type="text" name="wpfront-notification-bar-options[max_views_cookie_name]" v-model="max_views_cookie_name" />&#160;
                        <?php
                        $description = __('Cookie name used to store view count.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo __('Display On Devices', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <div>
                            <label><input type="radio" class="hide_small_device" name="wpfront-notification-bar-options[hide_small_device]" value="all" v-model="hide_small_device" /> <?php echo __('All Devices', 'wpfront-notification-bar'); ?></label>
                            <br />
                            <label><input type="radio" class="hide_small_device" name="wpfront-notification-bar-options[hide_small_device]" value="small" v-model="hide_small_device" /> <?php echo __('Small Devices', 'wpfront-notification-bar'); ?></label>
                            <br />
                            <label><input type="radio" class="hide_small_device" name="wpfront-notification-bar-options[hide_small_device]" value="large" v-model="hide_small_device" /> <?php echo __('Except Small Devices ', 'wpfront-notification-bar'); ?></label>
                        </div>

                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo __('Small Device Max Width', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <input type="text" class="pixels" name="wpfront-notification-bar-options[small_device_width]" v-model="small_device_width" />px &#160;
                        <?php
                        $description = __('Devices with width greater than the specified width will be considered as large devices.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo __('Hide on Small Window', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <input type="checkbox" name="wpfront-notification-bar-options[hide_small_window]" v-model="hide_small_window" />&#160;
                        <?php
                        $description = __('Notification bar will be hidden on broswer window when the width matches.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>                  
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo __('Small Window Max Width', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <input type="text" class="pixels" name="wpfront-notification-bar-options[small_window_width]" v-model="small_window_width" />px &#160;
                        <?php
                        $description = __('Notification bar will be hidden on browser window with lesser or equal width.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo __('Attach on Shutdown', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <input type="checkbox" name="wpfront-notification-bar-options[attach_on_shutdown]" v-model="attach_on_shutdown" />&#160;
                        <?php
                        $description = __('Enable as a last resort if the notification bar is not working. This could create compatibility issues.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
            </table>
            <?php
        }

        public function postbox_notification_bar_content_settings() {
            ?>
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <?php echo __('Set Maximum Width for Message', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <input type="checkbox" name="wpfront-notification-bar-options[set_full_width_message]" v-model="set_full_width_message" />
                    </td>
                </tr>
                <?php $this->message_field(); ?>           
                <tr>
                    <th scope="row">
                        <?php echo __('Message Text Preview', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <textarea rows="5" cols="75" readonly="true"><?php echo esc_textarea($this->controller->get_message_text()); ?></textarea>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo  __('Process Shortcode', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <input type="checkbox" name="wpfront-notification-bar-options[message_process_shortcode]" v-model="message_process_shortcode" />&#160;
                        <?php
                        $description = __('Processes shortcodes in message text.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo __('Display Button', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <input type="checkbox" name="wpfront-notification-bar-options[display_button]" v-model="display_button" />&#160;
                        <?php
                        $description = __('Displays a button next to the message.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo __('Button Text', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <input type="text" name="wpfront-notification-bar-options[button_text]" v-model="button_text" />
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo __('Button Text Preview', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <input type="text" readonly="true" value="<?php echo esc_attr($this->controller->get_button_text()); ?>" />
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo __('Button Action', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <label>
                            <input type="radio" name="wpfront-notification-bar-options[button_action]" value="1" v-model="button_action" />
                            <span><?php echo __('Open URL:', 'wpfront-notification-bar'); ?></span>
                        </label>
                        <input type="text" class="URL" name="wpfront-notification-bar-options[button_action_url]" v-model="button_action_url" />
                        <br />
                        <label>
                            <input type="checkbox" name="wpfront-notification-bar-options[button_action_new_tab]" v-model="button_action_new_tab" />
                            <span><?php echo __('Open URL in new tab/window.', 'wpfront-notification-bar'); ?></span>
                        </label>
                        <br />
                        <label>
                            <input type="checkbox" name="wpfront-notification-bar-options[button_action_url_nofollow]" v-model="button_action_url_nofollow" />
                            <span><?php echo __('No follow link.', 'wpfront-notification-bar'); ?></span>
                        </label>&#160;
                        <?php
                        $description = __('rel="<b>nofollow</b>"', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                        <br />
                        <label>
                            <input type="checkbox" name="wpfront-notification-bar-options[button_action_url_noreferrer]" v-model="button_action_url_noreferrer" />
                            <span><?php echo __('No referrer link.', 'wpfront-notification-bar'); ?></span>
                        </label>&#160;
                        <?php
                        $description = __('rel="<b>noreferrer</b>"', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                        <br />
                        <label>
                            <input id="chk_button_action_url_noopener" type="checkbox" v-model="button_action_url_noopener" />
                            <span><?php echo __('No opener link.', 'wpfront-notification-bar'); ?></span>
                        </label>&#160;
                        <?php
                        $description = __('rel="<b>noopener</b>", used when URL opens in new tab/window. Recommended value is "<b>on</b>", unless it affects your functionality.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                        <br />
                        <label>
                            <input type="radio" name="wpfront-notification-bar-options[button_action]" value="2" v-model="button_action" />
                            <span><?php echo __('Execute JavaScript', 'wpfront-notification-bar'); ?></span>
                        </label>
                        <br />
                        <textarea rows="5" cols="75" name="wpfront-notification-bar-options[button_action_javascript]">{{button_action_javascript}}</textarea>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo __('Close Bar on Button Click', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <input type="checkbox" name="wpfront-notification-bar-options[button_action_close_bar]" v-model="button_action_close_bar" />
                    </td>
                </tr>
            </table>
            <?php
        }

        public function postbox_notification_bar_filter_settings() {
            ?>
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <?php echo __('Date Type', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <div>
                            <label><input id="date-none" type="radio" class="date-type" name="wpfront-notification-bar-options[filter_date_type]" value="none" v-model="filter_date_type"/> <?php echo __('None', 'wpfront-notification-bar'); ?></label>
                            <br />
                            <label><input id="start-end-date" type="radio" class="date-type" name="wpfront-notification-bar-options[filter_date_type]" value="start_end" v-model="filter_date_type"/> <?php echo __('Start and End Date', 'wpfront-notification-bar'); ?></label>
                            <br />
                            <label><input id="schedule-date" type="radio" class="date-type" name="wpfront-notification-bar-options[filter_date_type]" value="schedule" v-model="filter_date_type" /> <?php echo __('Recurring Schedule', 'wpfront-notification-bar'); ?></label>
                            <?php if(!$this->is_scheduling_available()) { ?>
                            <label style="color: red;"><?php printf(__('(minimum version supported is PHP 7.2, your version is PHP %s)', 'wpfront-notification-bar'), PHP_VERSION); ?></label>
                            <?php } ?>
                        </div>
                    </td>
                </tr>
                <tr class="start-end-date" v-if="filter_date_type=='start_end'">
                    <th scope="row">
                        <?php echo __('Start Date & Time', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <date-picker name="wpfront-notification-bar-options[start_date]" v-model="start_date"></date-picker>&nbsp;
                        <time-picker name="wpfront-notification-bar-options[start_time]" v-model="start_time"></time-picker>&#160;
                        <?php
                        $description = __('YYYY-MM-DD hh:mm ap', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>                   
                    </td>
                </tr>
                <tr class="start-end-date" v-if="filter_date_type=='start_end'">
                    <th scope="row">
                        <?php echo __('End Date & Time', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <date-picker name="wpfront-notification-bar-options[end_date]" v-model="end_date"></date-picker>&nbsp;
                        <time-picker name="wpfront-notification-bar-options[end_time]" v-model="end_time"></time-picker>&#160;
                        <?php
                        $description = __('YYYY-MM-DD hh:mm ap', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>                   
                    </td>
                </tr>
                <?php
                $this->scheduled_date();
                ?>                      
                <tr>
                    <th scope="row">
                        <?php echo __('Display on Pages', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <label>
                            <input type="radio" name="wpfront-notification-bar-options[display_pages]" value="1" v-model="display_pages" />
                            <span><?php echo __('All pages.', 'wpfront-notification-bar'); ?></span>
                        </label>
                        <br />
                        <label>
                            <input type="radio" name="wpfront-notification-bar-options[display_pages]" value="2" v-model="display_pages"/>
                            <span><?php echo __('Only in landing page.', 'wpfront-notification-bar'); ?></span>&#160;
                            <?php
                            $description = __('The first page they visit on your website.', 'wpfront-notification-bar');
                            $this->echo_help_tooltip($description);
                            ?>                   
                        </label>
                        <br />
                        <label>
                            <input type="radio" name="wpfront-notification-bar-options[display_pages]" value="3" v-model="display_pages"/>
                            <span><?php echo __('Include in following pages.', 'wpfront-notification-bar'); ?></span>&#160;
                            <?php
                            $description = __('Use the textbox below to specify the post IDs as a comma separated list.', 'wpfront-notification-bar');
                            $this->echo_help_tooltip($description);
                            ?>                   
                        </label>
                        <br />
                        <posts-filter-selection name="wpfront-notification-bar-options[include_pages]" v-model="include_pages" ></posts-filter-selection>
                        <label>
                            <input type="radio" name="wpfront-notification-bar-options[display_pages]" value="4" v-model="display_pages"/>
                            <span><?php echo __('Exclude in following pages.', 'wpfront-notification-bar'); ?></span>&#160;
                            <?php
                            $description = __('Use the textbox below to specify the post IDs as a comma separated list.', 'wpfront-notification-bar');
                            $this->echo_help_tooltip($description);
                            ?>                   
                        </label>
                        <br />
                        <posts-filter-selection name="wpfront-notification-bar-options[exclude_pages]" v-model="exclude_pages" ></posts-filter-selection>
                        <?php
                        $description = __('Will display only top 50 posts and 50 pages to reduce load. Use the PostIDs textbox to apply this setting on other Posts, Pages, CPTs and Taxonomies. Taxonomy terms start with a "t".', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>                   
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo __('Landing Page Cookie Name', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <input class="regular-text" type="text" name="wpfront-notification-bar-options[landingpage_cookie_name]" v-model="landingpage_cookie_name" />&#160;
                        <?php
                        $description = __('Cookie name used to mark landing page. Useful when you have multiple WordPress installs under same domain.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>                   
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo __('Display for User Roles', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <label>
                            <input type="radio" name="wpfront-notification-bar-options[display_roles]" value="1" v-model="display_roles" />
                            <span><?php echo __('All users.', 'wpfront-notification-bar'); ?></span>
                        </label>
                        <br />
                        <label>
                            <input type="radio" name="wpfront-notification-bar-options[display_roles]" value="2" v-model="display_roles" />
                            <span><?php echo __('All logged in users.', 'wpfront-notification-bar'); ?></span>
                        </label>
                        <br />
                        <label>
                            <input type="radio" name="wpfront-notification-bar-options[display_roles]" value="3" v-model="display_roles" />
                            <span><?php echo __('Guest users.', 'wpfront-notification-bar'); ?></span>&#160;
                            <?php
                            $description = __('Non-logged in users', 'wpfront-notification-bar');
                            $this->echo_help_tooltip($description);
                            ?>                   
                        </label>
                        <br />
                        <label>
                            <input type="radio" name="wpfront-notification-bar-options[display_roles]" value="4" v-model="display_roles"/>
                            <span><?php echo __('For following user roles', 'wpfront-notification-bar'); ?></span>&nbsp;<span>[<a target="_blank" rel="noopener" href="https://wpfront.com/nbtoure"><?php echo __('Manage Roles', 'wpfront-notification-bar'); ?>]</a></span>
                        </label>
                        <br />
                        <display-roles-settings name="wpfront-notification-bar-options[include_roles]" v-model="include_roles"></display-roles-settings>
                        <label>
                            <input type="checkbox" name="wpfront-notification-bar-options[wp_emember_integration]" v-model="wp_emember_integration"/>
                            <span><?php echo __('Enable WP eMember integration.', 'wpfront-notification-bar'); ?></span>
                        </label>
                    </td>
                </tr>
            </table>

            <template id="posts-filter-selection">
                <div>
                    <input class="post-id-list" :name="name" type="text" :value="modelValue" @input="$emit('update:modelValue', $event.target.value)"/>
                    <div class="pages-selection">
                        <?php
                        $objects = $this->controller->get_filter_objects();
                        foreach ($objects as $key => $value) {
                        ?>
                            <div class="page-div">
                                <label>
                                    <input type="checkbox" value="<?php echo $key; ?>" v-model="selectedPosts" />
                                    <?php echo $value; ?>
                                </label>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </template>  

            <template id="display-roles-settings">
                <div class="roles-selection">
                    <input type="hidden" :name="name" :value="modelValue" />
                    <?php
                    foreach ($this->controller->get_role_objects() as $key => $value) {
                    ?>
                        <div class="role-div">
                            <label>
                                <input type="checkbox" value="<?php echo $key; ?>" v-model="selectedRoles" />
                                <?php echo $value; ?>
                            </label>
                        </div>
                    <?php
                    }
                    ?>
                    <div class="role-div">
                        <label>
                            <input type="checkbox" value="<?php echo WPFront_Notification_Bar::ROLE_NOROLE; ?>" v-model="selectedRoles" />
                            <?php echo __('[No Role]', 'wpfront-notification-bar'); ?>
                        </label>
                    </div>
                    <div class="role-div">
                        <label>
                            <input type="checkbox" value="<?php echo WPFront_Notification_Bar::ROLE_GUEST; ?>" v-model="selectedRoles" />
                            <?php echo __('[Guest]', 'wpfront-notification-bar'); ?>
                        </label>
                    </div>
                </div>
            </template>

            <template id="date-picker">
                <el-date-picker v-model="data" type="date" :name="name" placeholder="<?php echo __('Pick a day', 'wpfront-notification-bar'); ?>" format="YYYY/MM/DD" value-format="YYYY-MM-DD" @change="$emit('update:modelValue', $event)" />
            </template>

            <template id="time-picker">
                <el-time-picker v-model="data" :name="name" placeholder="<?php echo __('Optional time', 'wpfront-notification-bar'); ?>" format="h:mm a" value-format="h:mm a" @change="$emit('update:modelValue', $event)" />
            </template>

            <?php
        }

        public function postbox_notification_bar_color_settings() {
            ?>
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <?php echo __('Bar Color', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <div class="color-selector-div">
                            <color-picker name="wpfront-notification-bar-options[bar_from_color]" v-model="bar_from_color"></color-picker> 
                        </div>
                        <div class="color-selector-div">
                            <color-picker name="wpfront-notification-bar-options[bar_to_color]" v-model="bar_to_color"></color-picker>
                        </div>
                        <?php
                        $description = __('Select two different colors to create a gradient.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo __('Message Text Color', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <color-picker name="wpfront-notification-bar-options[message_color]" v-model="message_color"></color-picker>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo __('Button Color', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <div class="color-selector-div">
                            <color-picker name="wpfront-notification-bar-options[button_from_color]" v-model="button_from_color"></color-picker>
                        </div>
                        <div class="color-selector-div">
                            <color-picker name="wpfront-notification-bar-options[button_to_color]" v-model="button_to_color"></color-picker>
                        </div>
                        <?php
                        $description = __('Select two different colors to create a gradient.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>    
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo __('Button Text Color', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <color-picker name="wpfront-notification-bar-options[button_text_color]" v-model="button_text_color"></color-picker>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo __('Reopen Button Color', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                       <color-picker name="wpfront-notification-bar-options[open_button_color]" v-model="open_button_color"></color-picker>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo __('Close Button Color', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <div class="color-selector-div">
                            <color-picker name="wpfront-notification-bar-options[close_button_color]" v-model="close_button_color"></color-picker>
                        </div>
                        <div class="color-selector-div">
                            <color-picker name="wpfront-notification-bar-options[close_button_color_hover]" v-model="close_button_color_hover"></color-picker>
                        </div>
                        <div class="color-selector-div">
                            <color-picker name="wpfront-notification-bar-options[close_button_color_x]" v-model="close_button_color_x"></color-picker>
                        </div>
                        <?php
                        $description = __('Normal, Hover, X', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
            </table>
            <?php $this->elementplus_color_picker_template();?>
            <?php
        }

         /**
         * Color Picker Template
         *
         * @return void
         */
        protected function elementplus_color_picker_template() {
            ?>
             <template id="color-picker">
                <el-color-picker :predefine="[
                    '#FF0000', 
                    '#00FFFF', 
                    '#0000FF', 
                    '#00008B', 
                    '#ADD8E6', 
                    '#800080', 
                    '#FFFF00', 
                    '#00FF00', 
                    '#FF00FF', 
                    '#FFC0CB',
                    '#C0C0C0',
                    '#808080',
                    '#000000',
                    '#FFA500',
                    '#A52A2A',
                    '#800000',
                    '#008000',
                    '#808000',
                    '#7FFFD4',
                    '#FFFFFF'
                ]" :model-value="modelValue" @active-change="$emit('update:modelValue', $event)" /></el-color-picker>
                <input type="text" class="color-value" :name="name" :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" />
            </template>
            <?php
        }

        public function postbox_notification_bar_css_settings() {
            ?>
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <?php echo __('Use Dynamic CSS URL', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <input type="checkbox" name="wpfront-notification-bar-options[dynamic_css_use_url]" v-model="dynamic_css_use_url"/>
                        &#160;
                        <?php
                        $description = __('Custom and dynamic CSS will be added through a URL instead of writing to the document. Enabling this setting is recommended if there are no conflicts, so that caching can be leveraged.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo __('Custom CSS Class', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <input class="regular-text" type="text" name="wpfront-notification-bar-options[custom_class]" v-model="custom_class" />
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo __('Custom CSS', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <textarea name="wpfront-notification-bar-options[custom_css]" rows="10" cols="75">{{custom_css}}</textarea>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo __('Enqueue in Footer', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <input type="checkbox" name="wpfront-notification-bar-options[css_enqueue_footer]" v-model="css_enqueue_footer" />&#160;
                        <?php
                        $description = __('Enqueue CSS in footer.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
            </table>
            <?php
        }

        protected function message_field() {
            ?>
            <tr>
                <th scope="row">
                    <?php echo __('Message Text', 'wpfront-notification-bar'); ?>
                </th>
                <td>
                    <?php
                    $id = 'notification_bar_message_text';
                    $name = 'wpfront-notification-bar-options[message]';
                    $content = "{{message}}";
                    $settings = array(
                        'wpautop' => false,
                        'textarea_name' => $name,
                        'default_editor' => 'html'
                    );
                    wp_editor($content, $id, $settings);
                    ?> 
                    <?php
                    $description = __('Use the "<b>Preview</b>" field to see the output text.', 'wpfront-notification-bar');
                    $this->echo_help_tooltip($description);
                    ?>
                </td>
            </tr>
            <?php
        }

        protected function scheduled_date() {
            ?>
            <tr class="schedule-date" v-if="filter_date_type=='schedule'">
                <th scope="row">
                    <?php echo __('Schedule', 'wpfront-notification-bar'); ?>
                </th>
                <td style="color:red;">
                    <p><?php echo __('Scheduling is not available in free version.', 'wpfront-notification-bar') . ' ' . sprintf('<a target="_blank" href="https://wpfront.com/notification-bar-pro/">%s</a>', __('Upgrade to Pro.', 'wpfront-notification-bar')); ?></p>
                </td> 
            </tr>
            <?php
        }

        protected function echo_help_tooltip($description) {
            $description = esc_attr($description);
            ?>
            <help-icon help-text="<?php echo $description ?>" ></help-icon>                   
            <?php
        }

        protected function is_scheduling_available() {
            return true;
        }

        /**
         * Media Library Settings
         *
         * @return array<string,string>
         */
        protected function settings() {
           return [
            'choose_image' =>  __('Choose Image', 'wpfront-notification-bar'),
            'select_image' =>  __('Select Image', 'wpfront-notification-bar'),
            'x_hours' =>  __('%1$d hour(s)', 'wpfront-notification-bar'),
            'x_hours_minutes' => __('%1$d hour(s) and %2$d minute(s)', 'wpfront-notification-bar'),
           ];
        }

        /**
         * Check for PRO
         *
         * @return bool
         */
        protected function is_pro() {
            return false;
        }

        protected function script() {
        ?>
            <script type="text/javascript">
                var data = <?php echo json_encode($this->options) ?>;
                var settings = <?php echo json_encode($this->settings()) ?>;
                var is_pro =  <?php echo json_encode($this->is_pro())?>;
                (function() {
                    init_wpfront_notifiction_bar_options(data, settings, is_pro);
                })();
            </script>
        <?php
        }

    }

}
