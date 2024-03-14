<?php
/*
  WPFront Scroll Top Plugin
  Copyright (C) 2013, WPFront.com
  Website: wpfront.com
  Contact: syam@wpfront.com

  WPFront Scroll Top Plugin is distributed under the GNU General Public License, Version 3,
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

namespace WPFront\Scroll_Top;

/**
 * Template for WPFront Scroll Top
 *
 * @author Syam Mohan <syam@wpfront.com>
 * @copyright 2013 WPFront.com
 */
class WPFront_Scroll_Top_Template {

    /**
     * Controller
     *
     * @var WPFront_Scroll_Top
     */
    protected $controller;

    /**
     * Options
     *
     * @var WPFront_Scroll_Top_Options
     */
    protected $options;

    /**
     * Constructor
     *
     * @param WPFront_Scroll_Top $controller
     * @param WPFront_Scroll_Top_Options $options
     */
    public function __construct($controller, $options)
    {
        $this->controller = $controller;
        $this->options = $options;
    }

    /**
     * Returns Scroll top html
     *
     * @param bool $is_admin
     * @return string
     */
    public function get_html($is_admin) {
        $html = '';

        $html .= '<div id="wpfront-scroll-top-container">';
        $html .= $this->get_inner_html($is_admin);
        $html .= '</div>';

        return $html;
    }

    /**
     * Returns button html
     *
     * @param bool $is_admin
     * @return string
     */
    protected function get_inner_html($is_admin) {
        $html = '';

        switch ($this->options->button_style) {
            case 'text':
                $html = $this->get_html_button_text();
                break;

            case 'font-awesome':
                $html = $this->get_html_button_font_awesome();
                break;

            default:
                $html = $this->get_html_button_image();
                break;
        }

        if (!$is_admin && $this->options->button_action == "url") {
            $html = sprintf('<a href="%s">' . $html . '</a>', esc_attr($this->options->button_action_page_url));
        }

        return $html;
    }

    /**
     * 
     *
     * @return string
     */
    protected function get_html_button_text() {
        return sprintf('<div class="text-holder">%s</div>', esc_html($this->options->text_button_text));
    }

    /**
     * 
     *
     * @return string
     */
    protected function get_html_button_font_awesome() {
        return sprintf('<i class="%s"></i>', esc_attr($this->options->fa_button_class));
    }

    /**
     * 
     * @SuppressWarnings(PHPMD.ElseExpression)
     *
     * @return string
     */
    protected function get_html_button_image() {
        $image = $this->options->image;

        if($image == 'custom') {
            $image = $this->options->custom_url;
        } else {
            $image = plugin_dir_url(__DIR__) . 'images/icons/' . $image;
        }

        return sprintf('<img src="%s" alt="%s" title="%s" />', esc_attr($image), esc_attr($this->options->image_alt), esc_attr($this->options->image_title));
    }

    /**
     * Returns scroll top css
     *
     * @return string
     */
    public function get_css() {
        $file = dirname(__DIR__) . '/css/wpfront-scroll-top.min.css';

        ob_start();

        echo file_get_contents($file);

        $this->hide_small_window_css();
        $this->hide_small_device_css();
        $this->location_css();

        switch ($this->options->button_style) {
            case 'text':
                $this->text_button_css();
                break;

            case 'font-awesome':
                $this->font_awesome_button_css();
                break;

            default:
                $this->image_button_css();
                break;
        }

        return (string)ob_get_clean();
    }

    /**
     * Echos hide small window css
     *
     * @return void
     */
    protected function hide_small_window_css() {
        if ($this->options->hide_small_window) {
            ?>
            @media screen and (max-width: <?php echo $this->options->small_window_width . "px"; ?>) {
                #wpfront-scroll-top-container {
                    visibility: hidden;
                }
            }
            <?php
        }
    }

    /**
     * Echos hide small device css
     *
     * @return void
     */
    protected function hide_small_device_css() {
        if ($this->options->hide_small_device) {
            ?>
            @media screen and (max-device-width: <?php echo $this->options->small_device_width . "px"; ?>) {
                #wpfront-scroll-top-container {
                    visibility: hidden;
                }
            }
            <?php
        }
    }

    /**
     * Echos location css
     *
     * @return void
     */
    protected function location_css() {
        echo '#wpfront-scroll-top-container {';
        switch ($this->options->location) {
            case 1:
                echo "right: {$this->options->marginX}px;";
                echo "bottom: {$this->options->marginY}px;";
                break;
            case 2:
                echo "left: {$this->options->marginX}px;";
                echo "bottom: {$this->options->marginY}px;";
                break;
            case 3:
                echo "right: {$this->options->marginX}px;";
                echo "top: {$this->options->marginY}px;";
                break;
            case 4:
                echo "left: {$this->options->marginX}px;";
                echo "top: {$this->options->marginY}px;";
                break;
        }
        echo '}';
    }

    /**
     * Echos text button css
     *
     * @return void
     */
    protected function text_button_css() {
        ?>
        #wpfront-scroll-top-container div.text-holder {
            color: <?php echo $this->options->text_button_text_color; ?>;
            background-color: <?php echo $this->options->text_button_background_color; ?>;
            width: <?php echo $this->options->button_width == 0 ? 'auto' : $this->options->button_width . 'px'; ?>;
            height: <?php echo $this->options->button_height == 0 ? 'auto' : $this->options->button_height . 'px'; ?>;

            <?php echo wp_strip_all_tags($this->options->text_button_css, true); ?>
        }

        #wpfront-scroll-top-container div.text-holder:hover {
            background-color: <?php echo $this->options->text_button_hover_color; ?>;
        }
        <?php
    }

    /**
     * Echos FA css
     *
     * @return void
     */
    protected function font_awesome_button_css() {
        ?>
        #wpfront-scroll-top-container i {
            color: <?php echo $this->options->fa_button_text_color; ?>;
        }

        <?php echo wp_strip_all_tags($this->options->fa_button_css, true); ?>
        <?php
    }

    /**
     * Echos image button css
     *
     * @return void
     */
    public function image_button_css() {
        ?>
        #wpfront-scroll-top-container img {
            width: <?php echo $this->options->button_width == 0 ? 'auto' : $this->options->button_width . 'px'; ?>;
            height: <?php echo $this->options->button_height == 0 ? 'auto' : $this->options->button_height . 'px'; ?>;
        }
        <?php
    }

}
