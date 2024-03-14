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

if (!class_exists('\WPFront\Notification_Bar\WPFront_Notification_Bar_Custom_Css_Template')) {

    class WPFront_Notification_Bar_Custom_CSS_Template {

        protected $controller;
        protected $options;

        public function write($controller, $force = false) {
            $this->controller = $controller;
            $this->options = $controller->get_options();

            $enabled = $this->options->enabled;
            $preview = $this->options->preview_mode;

            if ($preview || $enabled || $force) {
                $this->wpfront_notification_bar_css();
                $this->div_wpfront_message_css();
                $this->a_wpfront_button_css();
                $this->open_button_css();
                $this->table_css();
                $this->div_wpfront_close_css();
                $this->div_wpfront_close_hover_css();
                $this->display_on_devices();
                $this->hide_small_window();
                $this->custom_css();
            }
        }

        protected function wpfront_notification_bar_css() {
            $id_suffix = $this->controller->get_html_id_suffix();
            echo "#wpfront-notification-bar$id_suffix, #wpfront-notification-bar-editor";
            ?>
            {
            background: <?php echo $this->options->bar_from_color; ?>;
            background: -moz-linear-gradient(top, <?php echo $this->options->bar_from_color; ?> 0%, <?php echo $this->options->bar_to_color; ?> 100%);
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,<?php echo $this->options->bar_from_color; ?>), color-stop(100%,<?php echo $this->options->bar_to_color; ?>));
            background: -webkit-linear-gradient(top, <?php echo $this->options->bar_from_color; ?> 0%,<?php echo $this->options->bar_to_color; ?> 100%);
            background: -o-linear-gradient(top, <?php echo $this->options->bar_from_color; ?> 0%,<?php echo $this->options->bar_to_color; ?> 100%);
            background: -ms-linear-gradient(top, <?php echo $this->options->bar_from_color; ?> 0%,<?php echo $this->options->bar_to_color; ?> 100%);
            background: linear-gradient(to bottom, <?php echo $this->options->bar_from_color; ?> 0%, <?php echo $this->options->bar_to_color; ?> 100%);
            filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo $this->options->bar_from_color; ?>', endColorstr='<?php echo $this->options->bar_to_color; ?>',GradientType=0 );
            background-repeat: no-repeat;
            <?php if ($this->options->set_full_width_message) {
                ?>
                flex-direction: column;
                <?php
            }
            ?>
            }
            <?php
        }

        protected function table_css() {
            $id_suffix = $this->controller->get_html_id_suffix();
            echo "#wpfront-notification-bar-table$id_suffix, .wpfront-notification-bar tbody, .wpfront-notification-bar tr";
            ?>
            {
            <?php if ($this->options->set_full_width_message) { ?>
                width: 100%
                <?php
            }
            ?>
            }
            <?php
        }

        protected function div_wpfront_message_css() {
            $id_suffix = $this->controller->get_html_id_suffix();
            echo "#wpfront-notification-bar$id_suffix div.wpfront-message, #wpfront-notification-bar-editor.wpfront-message";
            ?>
            {
            color: <?php echo $this->options->message_color; ?>;
            <?php if ($this->options->set_full_width_message) {
                ?>
                width: 100%
                <?php
            }
            ?>
            }
            <?php
        }

        protected function a_wpfront_button_css() {
            $id_suffix = $this->controller->get_html_id_suffix();
            echo "#wpfront-notification-bar$id_suffix a.wpfront-button, #wpfront-notification-bar-editor a.wpfront-button";
            ?>
            {
            background: <?php echo $this->options->button_from_color; ?>;
            background: -moz-linear-gradient(top, <?php echo $this->options->button_from_color; ?> 0%, <?php echo $this->options->button_to_color; ?> 100%);
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,<?php echo $this->options->button_from_color; ?>), color-stop(100%,<?php echo $this->options->button_to_color; ?>));
            background: -webkit-linear-gradient(top, <?php echo $this->options->button_from_color; ?> 0%,<?php echo $this->options->button_to_color; ?> 100%);
            background: -o-linear-gradient(top, <?php echo $this->options->button_from_color; ?> 0%,<?php echo $this->options->button_to_color; ?> 100%);
            background: -ms-linear-gradient(top, <?php echo $this->options->button_from_color; ?> 0%,<?php echo $this->options->button_to_color; ?> 100%);
            background: linear-gradient(to bottom, <?php echo $this->options->button_from_color; ?> 0%, <?php echo $this->options->button_to_color; ?> 100%);
            filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo $this->options->button_from_color; ?>', endColorstr='<?php echo $this->options->button_to_color; ?>',GradientType=0 );

            color: <?php echo $this->options->button_text_color; ?>;
            }
            <?php
        }

        protected function open_button_css() {
            $id_suffix = $this->controller->get_html_id_suffix();
            echo "#wpfront-notification-bar-open-button$id_suffix";
            ?>
            {
            background-color: <?php echo $this->options->open_button_color; ?>;
            right: <?php echo 10 + $this->options->reopen_button_offset; ?>px;
            <?php
            if (!empty($this->options->reopen_button_image_url)) {
                echo "background-image: url({$this->options->reopen_button_image_url});";
            }
            ?>
            }
            <?php
            if (empty($this->options->reopen_button_image_url)) {
                $url_top = plugins_url('images/arrow_down.png', $this->controller->get_plugin_file());
                $url_bottom = plugins_url('images/arrow_up.png', $this->controller->get_plugin_file());
                echo "#wpfront-notification-bar-open-button$id_suffix.top";
                ?>
                {
                background-image: url(<?php echo $url_top; ?>);
                }

                <?php
                echo "#wpfront-notification-bar-open-button$id_suffix.bottom";
                ?>
                {
                background-image: url(<?php echo $url_bottom; ?>);
                }
                <?php
            }
        }

        protected function div_wpfront_close_css() {
            $id_suffix = $this->controller->get_html_id_suffix();
            echo "#wpfront-notification-bar$id_suffix div.wpfront-close";
            ?>
            {
            border: 1px solid <?php echo $this->options->close_button_color; ?>;
            background-color: <?php echo $this->options->close_button_color; ?>;
            color: <?php echo $this->options->close_button_color_x; ?>;
            }
            <?php
        }

        protected function div_wpfront_close_hover_css() {
            $id_suffix = $this->controller->get_html_id_suffix();
            echo "#wpfront-notification-bar$id_suffix div.wpfront-close:hover";
            ?>
            {
            border: 1px solid <?php echo $this->options->close_button_color_hover; ?>;
            background-color: <?php echo $this->options->close_button_color_hover; ?>;
            }
            <?php
        }

        protected function display_on_devices() {
            switch ($this->options->hide_small_device) {
                case 'small':
                    $this->display_on_small_device();
                    break;

                case 'large':
                    $this->display_on_large_device();
                    break;

                default:
                    $this->display_on_all_device();
                    break;
            }
        }

        protected function display_on_all_device() {
            $id_suffix = $this->controller->get_html_id_suffix();
            echo " #wpfront-notification-bar-spacer$id_suffix { display:block; }";
        }

        protected function display_on_small_device() {
            $id_suffix = $this->controller->get_html_id_suffix();
            echo " #wpfront-notification-bar-spacer$id_suffix { display:none; }@media screen and (max-device-width:{$this->options->small_device_width}px) { #wpfront-notification-bar-spacer$id_suffix { display:block; } }";
        }

        protected function display_on_large_device() {
            $id_suffix = $this->controller->get_html_id_suffix();
            echo "@media screen and (max-device-width: {$this->options->small_device_width}px) { #wpfront-notification-bar-spacer$id_suffix  { display:none; } }";
        }

        protected function hide_small_window() {
            if ($this->options->hide_small_window) {
                $id_suffix = $this->controller->get_html_id_suffix();
                echo "@media screen and (max-width: {$this->options->small_window_width}px) { #wpfront-notification-bar-spacer$id_suffix { display:none; } }";
            }
        }

        protected function custom_css() {
            echo wp_strip_all_tags($this->options->custom_css, true);
        }

    }

}
