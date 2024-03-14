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

if (!class_exists('\WPFront\Notification_Bar\WPFront_Notification_Bar_Template')) {

    /**
     * Template for WPFront Notification Bar
     *
     * @author Syam Mohan <syam@wpfront.com>
     * @copyright 2013 WPFront.com
     */
    class WPFront_Notification_Bar_Template {

        protected $controller;
        protected $options;

        public function __construct($options, $controller) {
            $this->options = $options;
            $this->controller = $controller;
        }

        public function write() {
            $this->dynamic_css();
            $this->display_button_js_script();
            $this->display_bar();
        }

        protected function dynamic_css() {
            if (!$this->options->dynamic_css_use_url) {
                ?>
                <style type="text/css">
                <?php
                $template = new WPFront_Notification_Bar_Custom_CSS_Template();
                $template->write($this->controller);
                ?>
                </style>
                <?php
            }
        }

        protected function display_button_js_script() {
            if ($this->options->display_button && $this->options->button_action == 2) {
                $id_suffix = $this->controller->get_html_id_suffix();
                $id_suffix = str_replace('-', "", $id_suffix);
                $js = preg_replace('/<\/script\b[^>]*>/i', '', $this->options->button_action_javascript);
                ?>

                <script type="text/javascript">
                    function wpfront_notification_bar_button_action_script<?php echo $id_suffix ?>() {
                        try {
                <?php echo $js; ?>
                        } catch (err) {
                        }
                    }
                </script>
                <?php
            }
        }

        protected function check_empty_id($id) {
            if (!empty($id)) {
                return '-' . $id;
            }
            return $id;
        }

        /**
         * Returns bar CSS classes;
         * 
         * @SuppressWarnings(PHPMD.ElseExpression)
         *
         * @return string
         */
        protected function get_bar_css_classes() {
            $bar_css = 'wpfront-notification-bar wpfront-fixed';

            if($this->options->fixed_position) {
                $bar_css .= ' wpfront-fixed-position';
            }

            if($this->controller->display_on_page_load()) {
                $bar_css .= ' load';
            }

            if($this->options->position == 1) {
                $bar_css .= ' top';
            } else {
                $bar_css .= ' bottom';
            }

            if($this->options->display_shadow) {
                if($this->options->position == 1) {
                    $bar_css .= ' wpfront-bottom-shadow';
                } else {
                    $bar_css .= ' wpfront-top-shadow';
                }
            }

            if($this->controller->has_keep_closed_set()) {
                $bar_css .= ' keep-closed';
            }

            if($this->controller->has_max_views_reached()) {
                $bar_css .= ' max-views-reached';
            }

            $bar_css .= ' ' . $this->options->custom_class;

            return $bar_css;
        }

        protected function display_bar() {
            $id_suffix = $this->controller->get_html_id_suffix();
            $bar_css = $this->get_bar_css_classes();
            ?>
            <div id="wpfront-notification-bar-spacer<?php echo $id_suffix; ?>" class="wpfront-notification-bar-spacer <?php echo $this->options->fixed_position ? ' wpfront-fixed-position' : ''; ?> <?php echo $this->controller->display_on_page_load() ? ' ' : 'hidden'; ?>">
                <div id="wpfront-notification-bar-open-button<?php echo $id_suffix; ?>" aria-label="reopen" role="button" class="wpfront-notification-bar-open-button hidden <?php echo $this->options->position == 1 ? 'top wpfront-bottom-shadow' : 'bottom wpfront-top-shadow'; ?>"></div>
                <div id="wpfront-notification-bar<?php echo $id_suffix; ?>" class="<?php echo esc_attr($bar_css); ?>">
                    <?php if ($this->options->close_button) { ?>
                        <div aria-label="close" class="wpfront-close">X</div>
                    <?php } if (empty($this->controller->get_message_text())) {
                        ?> &nbsp; <?php
                    }
                    ?>
                    <?php
                    $table_present = apply_filters('wpfront_notification_bar_use_table_html', true);
                    if (!empty($this->options->display_button) || !empty($this->controller->get_message_text())) {
                        if ($table_present == true) {
                            ?> 
                            <table id="wpfront-notification-bar-table<?php echo $id_suffix; ?>" border="0" cellspacing="0" cellpadding="0" role="presentation">                        
                                <tr>
                                    <td>
                                    <?php } ?> 
                                    <div class="wpfront-message wpfront-div">
                                        <?php echo __($this->controller->get_message_text(true), $this->controller->get_lang_domain()); ?>
                                    </div>
                                    <?php
                                    if ($this->options->display_button) {
                                        ?>                   
                                        <div class="wpfront-div">
                                            <?php
                                            $button_text = $this->controller->get_button_text(true);
                                            ?>
                                            <?php
                                            if ($this->options->button_action == 1) {
                                                $rel = array();

                                                if ($this->options->button_action_url_nofollow) {
                                                    $rel[] = 'nofollow';
                                                }

                                                if ($this->options->button_action_url_noreferrer) {
                                                    $rel[] = 'noreferrer';
                                                }

                                                if ($this->options->button_action_new_tab && $this->options->button_action_url_noopener) {
                                                    $rel[] = 'noopener';
                                                }

                                                $rel = implode(' ', $rel);
                                                ?>
                                                <a class="wpfront-button" href="<?php echo __($this->options->button_action_url, $this->controller->get_lang_domain()); ?>"  target="<?php echo $this->options->button_action_new_tab ? '_blank' : '_self'; ?>" <?php echo empty($rel) ? '' : "rel=\"$rel\""; ?>><?php echo __($button_text, $this->controller->get_lang_domain()); ?></a>
                                                <?php
                                            }
                                            ?>
                                            <?php
                                            if ($this->options->button_action == 2) {
                                                $id_suffix = $this->controller->get_html_id_suffix();
                                                $id_suffix = str_replace('-', "", $id_suffix);
                                                ?>
                                                <a class="wpfront-button" onclick="javascript:wpfront_notification_bar_button_action_script<?php echo $id_suffix ?>();"><?php echo __($button_text, $this->controller->get_lang_domain()); ?></a>
                                            <?php } ?>
                                        </div>                                   
                                    <?php } ?>
                                    <?php if ($table_present == true) {
                                        ?>                               
                                    </td>
                                </tr>              
                            </table>
                        <?php } ?>    
                    <?php } ?>
                </div>
            </div>
            <?php
        }

    }

}
                        