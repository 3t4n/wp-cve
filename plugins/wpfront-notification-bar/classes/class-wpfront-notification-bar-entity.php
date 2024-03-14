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

namespace WPFront\Notification_Bar;

if (!defined('ABSPATH')) exit();

if (!class_exists('\WPFront\Notification_Bar\WPFront_Notification_Bar_Entity')) {

    /**
     * Option class of WPFront Notification Bar plugin
     *
     * @author Syam Mohan <syam@wpfront.com>
     * @copyright 2013 WPFront.com
     */
    class WPFront_Notification_Bar_Entity
    {

        /**
         * Notification Bar Enabled.
         *
         * @var bool
         */
        public $enabled;

        /**
         * Notification Bar Preview Mode.
         *
         * @var bool
         */
        public $preview_mode;

        /**
         * Notification Bar Debug Mode.
         *
         * @var bool
         */
        public $debug_mode;

        /**
         * Notification Bar Position.
         *
         * @var int
         */
        public $position;

        /**
         * Notification Bar Fixed Position.
         *
         * @var bool
         */
        public $fixed_position;

        /**
         * Notification Bar Theme Sticky Selector.
         *
         * @var string
         */
        public $theme_sticky_selector;

        /**
         * Notification Bar Display Scroll.
         *
         * @var bool
         */
        public $display_scroll;

        /**
         * Notification Bar Scroll Offset.
         *
         * @var int
         */
        public $display_scroll_offset;

        /**
         * Notification Bar Height.
         *
         * @var int
         */
        public $height;

        /**
         * Notification Bar Position Offset.
         *
         * @var int
         */
        public $position_offset;

        /**
         * Notification Bar Display After.
         *
         * @var int
         */
        public $display_after;

        /**
         * Notification Bar Animate Delay.
         *
         * @var float
         */
        public $animate_delay;

        /**
         * Notification Bar Close Button.
         *
         * @var bool
         */
        public $close_button;

        /**
         * Notification Bar Auto Close After.
         *
         * @var int
         */
        public $auto_close_after;

        /**
         * Notification Bar Display Shadow.
         *
         * @var bool
         */
        public $display_shadow;

        /**
         * Notification Bar Display Reopen Button.
         *
         * @var bool
         */
        public $display_open_button;

        /**
         * Notification Bar Reopen Button Image URL.
         *
         * @var string
         */
        public $reopen_button_image_url;

        /**
         * Notification Bar Reopen Button Offset.
         *
         * @var int
         */
        public $reopen_button_offset;

        /**
         * Notification Bar Keep Closed.
         *
         * @var bool
         */
        public $keep_closed;

        /**
         * Notification Bar Keep Closed For.
         *
         * @var int
         */
        public $keep_closed_for;

        /**
         * Notification Bar Keep Closed Cookie Name.
         *
         * @var string
         */
        public $keep_closed_cookie_name;

        /**
         * Notification Bar Set Max Views.
         *
         * @var bool
         */
        public $set_max_views;

        /**
         * Notification Bar Max Views.
         *
         * @var int
         */
        public $max_views;

        /**
         * Notification Bar Max Views For.
         *
         * @var int
         */
        public $max_views_for;

        /**
         * Notification Bar Max View Cookie Name.
         *
         * @var string
         */
        public $max_views_cookie_name;

        /**
         * Notification Bar Hide Small Device.
         *
         * @var string
         */
        public $hide_small_device;

        /**
         * Notification Bar Small Device Max Width.
         *
         * @var int
         */
        public $small_device_width;

        /**
         * Notification Bar Hide Small Window.
         *
         * @var bool
         */
        public $hide_small_window;

        /**
         * Notification Bar Small Window Max Width.
         *
         * @var int
         */
        public $small_window_width;

        /**
         * Notification Bar Attach on Shutdown.
         *
         * @var bool
         */
        public $attach_on_shutdown;

        /**
         * Notification Bar Full Width Message.
         *
         * @var bool
         */
        public $set_full_width_message;

        /**
         * Notification Bar Message.
         *
         * @var string
         */
        public $message;

        /**
         * Notification Bar Process Shortcode.
         *
         * @var bool
         */
        public $message_process_shortcode;

        /**
         * Notification Bar Display Button.
         *
         * @var bool
         */
        public $display_button;

        /**
         * Notification Bar Button Text.
         *
         * @var string
         */
        public $button_text;

        /**
         * Notification Bar Button Action.
         *
         * @var int
         */
        public $button_action;

        /**
         * Notification Bar Button Action URL.
         *
         * @var string
         */
        public $button_action_url;

        /**
         * Notification Bar Button Action New Tab.
         *
         * @var bool
         */
        public $button_action_new_tab;

        /**
         * Notification Bar Button Action No Follow.
         *
         * @var bool
         */
        public $button_action_url_nofollow;

        /**
         * Notification Bar Button Action No Referrer.
         *
         * @var bool
         */
        public $button_action_url_noreferrer;

        /**
         * Notification Bar Button Action No Opener.
         *
         * @var bool
         */
        public $button_action_url_noopener;

        /**
         * Notification Bar Button Action Javascript.
         *
         * @var string
         */
        public $button_action_javascript;

        /**
         * Notification Bar Close Bar on Button Click.
         *
         * @var bool
         */
        public $button_action_close_bar;

        /**
         * Notification Bar Bar from Color.
         *
         * @var string
         */
        public $bar_from_color;

        /**
         * Notification Bar Bar to Color.
         *
         * @var string
         */
        public $bar_to_color;

        /**
         * Notification Bar Message Color.
         *
         * @var string
         */
        public $message_color;

        /**
         * Notification Bar Button from Color.
         *
         * @var string
         */
        public $button_from_color;

        /**
         * Notification Bar Button to Color.
         *
         * @var string
         */
        public $button_to_color;

        /**
         * Notification Bar Button Text Color.
         *
         * @var string
         */
        public $button_text_color;

        /**
         * Notification Bar Open Button Color.
         *
         * @var string
         */
        public $open_button_color;

        /**
         * Notification Bar Close Button Color.
         *
         * @var string
         */
        public $close_button_color;

        /**
         * Notification Bar Close Button Color Hover.
         *
         * @var string
         */
        public $close_button_color_hover;

        /**
         * Notification Bar Close Button Color X.
         *
         * @var string
         */
        public $close_button_color_x;

        /**
         * Notification Bar Filter Date Type.
         * 
         * @var string
         */
        public $filter_date_type;

        /**
         * Notification Bar Start Date.
         * 
         * @var string
         */
        public $start_date;

        /**
         * Notification Bar Start Time.
         * 
         * @var string
         */
        public $start_time;

        /**
         * Notification Bar End Date.
         * 
         * @var string
         */
        public $end_date;

        /**
         * Notification Bar End Time.
         * 
         * @var string
         */
        public $end_time;

        /**
         * Notification Bar Display Pages.
         * 
         * @var int
         */
        public $display_pages;

        /**
         * Notification Bar Include Pages.
         * 
         * @var string
         */
        public $include_pages;

        /**
         * Notification Bar Exclude Pages.
         * 
         * @var string
         */
        public $exclude_pages;

        /**
         * Notification Bar Landing Page Cookie Name.
         * 
         * @var string
         */
        public $landingpage_cookie_name;

        /**
         * Notification Bar Display Roles.
         * 
         * @var int
         */
        public $display_roles;

        /**
         * Notification Bar Include Roles.
         * 
         * @var array<string>
         */
        public $include_roles;

        /**
         * Notification Bar Emember Integrartion
         * 
         * @var bool
         */
        public $wp_emember_integration;

        /**
         * Notification Bar Dynamic Css Use URL.
         *
         * @var bool
         */
        public $dynamic_css_use_url;

        /**
         * Notification Bar Custom Class.
         *
         * @var string
         */
        public $custom_class;

        /**
         * Notification Bar Custom Css.
         *
         * @var string
         */
        public $custom_css;

        /**
         * Notification Bar CSS Enqueue Footer.
         *
         * @var bool
         */
        public $css_enqueue_footer;

        /**
         * Notification Bar Last Saved
         * 
         * @var int
         */
        public $last_saved;


        public function __construct()
        {
            $this->enabled = false;
            $this->preview_mode = false;
            $this->debug_mode = false;

            $this->position = 1;
            $this->fixed_position = false;
            $this->theme_sticky_selector = '';
            $this->display_scroll = false;
            $this->display_scroll_offset = 100;
            $this->height = 0;
            $this->position_offset = 0;
            $this->display_after = 1;
            $this->animate_delay = 0.5;
            $this->close_button = false;
            $this->auto_close_after = 0;
            $this->display_shadow = false;
            $this->display_open_button = false;
            $this->reopen_button_offset = 0;
            $this->reopen_button_image_url = '';
            $this->keep_closed = false;
            $this->keep_closed_for = 0;
            $this->keep_closed_cookie_name = 'wpfront-notification-bar-keep-closed';
            $this->set_max_views = false;
            $this->max_views = 0;
            $this->max_views_for = 0;
            $this->max_views_cookie_name = 'wpfront-notification-bar-max-views';
            $this->hide_small_device = 'all';
            $this->small_device_width = 640;
            $this->hide_small_window = false;
            $this->small_window_width = 640;
            $this->attach_on_shutdown = false;
            $this->set_full_width_message = false;

            $this->set_full_width_message = false;
            $this->message = '';
            $this->message_process_shortcode = false;
            $this->display_button = false;
            $this->button_text = '';
            $this->button_action_close_bar = false;
            $this->button_action = 1;
            $this->button_action_url = '';
            $this->button_action_new_tab = false;
            $this->button_action_url_nofollow = false;
            $this->button_action_url_noreferrer = false;
            $this->button_action_url_noopener = true;
            $this->button_action_javascript = '';

            $this->filter_date_type = 'start_end';
            $this->start_date = '';
            $this->start_time = '';
            $this->end_date = '';
            $this->end_time = '';
            $this->display_pages = 1;
            $this->include_pages = '';
            $this->exclude_pages = '';
            $this->landingpage_cookie_name = 'wpfront-notification-bar-landingpage';
            $this->display_roles = 1;
            $this->include_roles = [];
            $this->wp_emember_integration = false;

            $this->bar_from_color = '#888888';
            $this->bar_to_color = '#000000';
            $this->message_color = '#ffffff';
            $this->button_from_color = '#00b7ea';
            $this->button_to_color = '#009ec3';
            $this->button_text_color = '#ffffff';
            $this->open_button_color = '#00b7ea';
            $this->close_button_color = '#555555';
            $this->close_button_color_hover = '#aaaaaa';
            $this->close_button_color_x = '#000000';

            $this->dynamic_css_use_url = false;
            $this->custom_class = '';
            $this->custom_css = '';
            $this->css_enqueue_footer = false;

            $this->last_saved = 0;
        }

        /**
         * Validates Notification Bar Option Data.
         *
         * @return void
         */
        public function validate()
        {
            $this->enabled = $this->validate_bool($this->enabled);
            $this->preview_mode = $this->validate_bool($this->preview_mode);
            $this->debug_mode = $this->validate_bool($this->debug_mode);
            $this->position = $this->validate_1or2($this->position);
            $this->fixed_position = $this->validate_bool($this->fixed_position);
            $this->display_scroll = $this->validate_bool($this->display_scroll);
            $this->display_scroll_offset = $this->validate_zero_positive($this->display_scroll_offset);
            $this->height = $this->validate_zero_positive($this->height);
            $this->position_offset = $this->validate_int($this->position_offset);
            $this->display_after = $this->validate_zero_positive($this->display_after);
            $this->animate_delay = $this->validate_float($this->animate_delay);
            $this->close_button = $this->validate_bool($this->close_button);
            $this->auto_close_after = $this->validate_zero_positive($this->auto_close_after);
            $this->display_shadow = $this->validate_bool($this->display_shadow);
            $this->display_open_button = $this->validate_bool($this->display_open_button);
            $this->reopen_button_offset = $this->validate_zero_positive($this->reopen_button_offset);
            $this->reopen_button_image_url = $this->validate_reopen_button_image_url($this->reopen_button_image_url);
            $this->keep_closed = $this->validate_bool($this->keep_closed);
            $this->keep_closed_for = $this->validate_zero_positive($this->keep_closed_for);
            $this->keep_closed_cookie_name = $this->validate_keep_closed_cookie_name($this->keep_closed_cookie_name);
            $this->set_max_views = $this->validate_bool($this->set_max_views);
            $this->max_views = $this->validate_zero_positive($this->max_views);
            $this->max_views_for = $this->validate_zero_positive($this->max_views_for);
            $this->max_views_cookie_name = $this->validate_max_views_cookie_name($this->max_views_cookie_name);
            $this->hide_small_device = $this->validate_display_on_devices($this->hide_small_device);
            $this->small_device_width = $this->validate_zero_positive($this->small_device_width);
            $this->hide_small_window = $this->validate_bool($this->hide_small_window);
            $this->small_window_width = $this->validate_zero_positive($this->small_window_width);
            $this->attach_on_shutdown = $this->validate_bool($this->attach_on_shutdown);
            $this->set_full_width_message = $this->validate_bool($this->set_full_width_message);
            $this->message_process_shortcode = $this->validate_bool($this->message_process_shortcode);
            $this->display_button = $this->validate_bool($this->display_button);
            $this->button_action = $this->validate_1or2($this->button_action);
            $this->button_action_url = $this->validate_button_action_url($this->button_action_url);
            $this->button_action_new_tab = $this->validate_bool($this->button_action_new_tab);
            $this->button_action_url_nofollow = $this->validate_bool($this->button_action_url_nofollow);
            $this->button_action_url_noreferrer = $this->validate_bool($this->button_action_url_noreferrer);
            $this->button_action_url_noopener = $this->validate_bool($this->button_action_url_noopener);
            $this->button_action_close_bar = $this->validate_bool($this->button_action_close_bar);
            $this->bar_from_color = $this->validate_color($this->bar_from_color);
            $this->bar_to_color = $this->validate_color($this->bar_to_color);
            $this->message_color = $this->validate_color($this->message_color);
            $this->button_from_color = $this->validate_color($this->button_from_color);
            $this->button_to_color = $this->validate_color($this->button_to_color);
            $this->button_text_color = $this->validate_color($this->button_text_color);
            $this->open_button_color = $this->validate_color($this->open_button_color);
            $this->close_button_color = $this->validate_color($this->close_button_color);
            $this->close_button_color_hover = $this->validate_color($this->close_button_color_hover);
            $this->close_button_color_x = $this->validate_color($this->close_button_color_x);
            $this->dynamic_css_use_url = $this->validate_bool($this->dynamic_css_use_url);
            $this->css_enqueue_footer = $this->validate_bool($this->css_enqueue_footer);
            $this->display_pages = $this->validate_display_pages($this->display_pages);
            $this->landingpage_cookie_name = $this->validate_landingpage_cookie_name($this->landingpage_cookie_name);
            $this->filter_date_type = $this->validate_filter_date_type($this->filter_date_type);
            $this->start_date = $this->validate_date_range($this->start_date);
            $this->start_time = $this->validate_date_range($this->start_time);
            $this->end_date = $this->validate_date_range($this->end_date);
            $this->end_time = $this->validate_date_range($this->end_time);
            $this->display_roles = $this->validate_display_roles($this->display_roles);
            $this->include_roles =  !is_array($this->include_roles) ? $this->validate_include_roles(wp_unslash($this->include_roles)) : $this->include_roles;
            $this->wp_emember_integration = $this->validate_bool($this->wp_emember_integration);
            $this->last_saved = (int)$this->last_saved;
            $this->message = (string)$this->message;
            $this->button_text = (string)$this->button_text;
            $this->button_action_javascript = (string)preg_replace('/<\/script\b[^>]*>/i', '', (string)$this->button_action_javascript);
            $this->include_pages = sanitize_text_field($this->include_pages);
            $this->exclude_pages = sanitize_text_field($this->exclude_pages);
            $this->custom_css = sanitize_textarea_field($this->custom_css);
            $this->custom_class = sanitize_text_field($this->custom_class);
            $this->theme_sticky_selector = sanitize_text_field($this->theme_sticky_selector);
        }

        /**
         * Return Notification Bar Option Data.
         *
         * @return WPFront_Notification_Bar_Entity $options
         */
        public function get()
        {
            $option = new WPFront_Notification_Bar_Entity();
            $data = get_option('wpfront-notification-bar-options');

            if (empty($data)) {
                $data = array();
            }

            $keys = array_keys((array)$option);

            foreach($keys as $p) {
                if(isset($data[$p])) {
                    $option->$p = $data[$p];
                }
            }

            $option->validate();

            return $option;
        }

        /**
         * Save Notification Bar Option Data.
         *
         * @return void
         */
        public function save()
        {
            $this->last_saved = time();

            $this->validate();

            $data = get_option('wpfront-notification-bar-options');
            if (empty($data)) {
                $data = array();
            }

            $values = (array)$this;

            foreach ($values as $key => $value) {
                $data[$key] = $value;
            }

            update_option('wpfront-notification-bar-options', $data);
        }


        /**
         * Set values from supplied data
         *
         * @param object $data
         * @return void
         */
        public function set_values($data)
        {
            foreach ((array)$this as $prop => $value) {
                if (isset($data->$prop)) {
                    $this->$prop = $data->$prop;
                }
            }
        }

        /**
         * Returns bool value.
         *
         * @param string|bool $arg
         * @return bool $arg
         */
        protected function validate_bool($arg)
        {
            if ($arg === 'false') {
                return false;
            }
            return (bool)$arg;
        }

        /**
         * Return integer value 1 or 2.
         *
         * @param mixed $arg
         * @return int<1, 2> $arg.
         */
        protected function validate_1or2($arg)
        {
            $arg = (int)$arg;

            if ($arg < 1) {
                return 1;
            }

            if ($arg > 2) {
                return 2;
            }

            return $arg;
        }

        /**
         * Returns zero or positive value.
         *
         * @param mixed $arg
         * @return int<0, max> $arg
         */
        protected function validate_zero_positive($arg)
        {
            $arg = (int)$arg;
            if ($arg < 0) {
                return 0;
            }
            return $arg;
        }


        /**
         * Return integer value.
         *
         * @param mixed $arg
         * @return int $arg
         */
        protected function validate_int($arg)
        {
            return (int)$arg;
        }

        /**
         * Return float value.
         *
         * @param mixed $arg
         * @return float $arg
         */
        protected function validate_float($arg)
        {
            $arg = (float)$arg;
            if ($arg < 0) {
                return 0;
            }
            return $arg;
        }

        /**
         * Returns Clean URL.
         *
         * @param string $arg
         * @return string 
         */
        protected function validate_reopen_button_image_url($arg)
        {
            $arg = (string)$arg;
            return esc_url_raw($arg);
        }

        /**
         * Returns Cookie Name.
         *
         * @param string $arg
         * @return string 
         */
        protected function validate_keep_closed_cookie_name($arg)
        {
            $arg = (string)$arg;
            $arg = sanitize_key($arg);

            if (empty($arg) || trim($arg) == '') {
                return 'wpfront-notification-bar-keep-closed';
            }

            return $arg;
        }

        /**
         * Returns Cookie Name.
         *
         * @param string $arg
         * @return string 
         */
        protected function validate_max_views_cookie_name($arg)
        {
            $arg = (string)$arg;
            $arg = sanitize_key($arg);

            if (empty($arg) || trim($arg) == '') {
                return 'wpfront-notification-bar-max-views';
            }

            return $arg;
        }

        /**
         * Returns Display Device Option.
         *
         * @param string $arg
         * @return string $arg
         */
        protected function validate_display_on_devices($arg)
        {
            $arg = (string)$arg;

            if ($arg == 'on') {
                return 'large';
            }

            if ($arg == 'small' || $arg == 'large') {
                return $arg;
            }
            return 'all';
        }

        /**
         * Return Color in Hex Format.
         *
         * @param string $arg
         * @return string
         */
        protected function validate_color($arg)
        {
            $arg = (string)$arg;
            $arg = sanitize_hex_color($arg);

            if (empty($arg)) {
                return '#ffffff';
            }

            return $arg;
        }

        /**
         * Returns Clean URL.
         *
         * @param string $arg
         * @return string 
         */
        protected function validate_button_action_url($arg)
        {
            $arg = (string)$arg;
            return esc_url_raw($arg);
        }

        /**
         * Returns Display Pages Option.
         *
         * @param mixed $arg
         * @return int $arg
         */
        protected function validate_display_pages($arg)
        {
            $arg = (int)$arg;

            if ($arg < 1) {
                return 1;
            }

            if ($arg > 4) {
                return 4;
            }

            return $arg;
        }

        /**
         * Returns Cookie Name.
         *
         * @param string $arg
         * @return string 
         */
        protected function validate_landingpage_cookie_name($arg)
        {
            $arg = (string)$arg;
            $arg = sanitize_key($arg);

            if (empty($arg) || trim($arg) == '') {
                return 'wpfront-notification-bar-landingpage';
            }

            return $arg;
        }

        /**
         * Returns Date.
         *
         * @param string $arg
         * @return string|null
         */
        protected function validate_date_range($arg)
        {
            if (empty($arg) || trim($arg) == '') {
                return NULL;
            }

            if (strtotime($arg) === false) {
                return NULL;
            }

            return $arg;
        }

        /**
         * Return integer value in range of 1 to 4.
         *
         * @param int $arg
         * @return int<1, 4> $arg.
         */
        protected function validate_display_roles($arg)
        {
            $arg = (int)$arg;

            if ($arg < 1) {
                return 1;
            }

            if ($arg > 4) {
                return 4;
            }

            return $arg;
        }

        /**
         * Returns Role Option.
         *
         * @param string $arg
         * @return array<string>
         */
        protected function validate_include_roles($arg)
        {
            $obj = json_decode($arg);
            if (!is_array($obj))
                return array();
            return $obj;
        }

         /**
         * Returns Display Device Option.
         *
         * @param string $arg
         * @return string $arg
         */
        protected function validate_filter_date_type($arg)
        {
            if ($arg == 'none' || $arg == 'start_end' || $arg == 'schedule') {
                return $arg;
            }
            return 'start_end';
        }

        /**
         * Remove data
         *
         * @return void
         */
        public static function uninstall() {
            delete_option('wpfront-notification-bar-options');
        }

    }
}
