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
 * Options class of WPFront Scroll Top Plugin
 *
 * @author Syam Mohan <syam@wpfront.com>
 * @copyright 2013 WPFront.com
 */
class WPFront_Scroll_Top_Options
{

    /**
     * Scroll Top Enabled.
     *
     * @var bool
     */
    public $enabled;

    /**
     * Scroll Top Javascript Async.
     *
     * @var bool
     */
    public $javascript_async;

    /**
     * Scroll Top Scroll Offset.
     *
     * @var int
     */
    public $scroll_offset;

    /**
     * Scroll Top Button Width.
     *
     * @var int
     */
    public $button_width;

    /**
     * Scroll Top Button Height.
     *
     * @var int
     */
    public $button_height;

    /**
     * Scroll Top Button Opacity.
     *
     * @var int
     */
    public $button_opacity;

    /**
     * Scroll Top Button Fade Duration.
     *
     * @var int
     */
    public $button_fade_duration;

    /**
     * Scroll Top Button Scroll Duration.
     *
     * @var int
     */
    public $scroll_duration;

    /**
     * Scroll Top Auto Hide.
     *
     * @var bool
     */
    public $auto_hide;

    /**
     * Scroll Top Auto Hide After.
     *
     * @var int
     */
    public $auto_hide_after;

    /**
     * Scroll Top Hide Small Device.
     *
     * @var bool
     */
    public $hide_small_device;

    /**
     * Scroll Top Device Width.
     *
     * @var int
     */
    public $small_device_width;

    /**
     * Scroll Top Hide Small Window.
     *
     * @var bool
     */
    public $hide_small_window;

    /**
     * Scroll Top Window Width.
     *
     * @var int
     */
    public $small_window_width;

    /**
     * Scroll Top Hide WPAdmin.
     *
     * @var bool
     */
    public $hide_wpadmin;

    /**
     * Scroll Top Hide iframe.
     *
     * @var bool
     */
    public $hide_iframe;

    /**
     * Scroll Top Attach on Shutdown.
     *
     * @var bool
     */
    public $attach_on_shutdown;

    /**
     * Scroll Top Button Style.
     *
     * @var string
     */
    public $button_style;

    /**
     * Scroll Top Button Action.
     *
     * @var string
     */
    public $button_action;

    /**
     * Scroll Top Button Action Element Selector.
     *
     * @var string
     */
    public $button_action_element_selector;

    /**
     * Scroll Top Button Action Container Selector.
     *
     * @var string
     */
    public $button_action_container_selector;

    /**
     * Scroll Top Button Action Offset.
     *
     * @var int
     */
    public $button_action_element_offset;

    /**
     * Scroll Top Button Action Page URL.
     *
     * @var string
     */
    public $button_action_page_url;

    /**
     * Scroll Top Button Location.
     *
     * @var int
     */
    public $location;

    /**
     * Scroll Top Margin X.
     *
     * @var float
     */
    public $marginX;

    /**
     * Scroll Top Margin Y.
     *
     * @var float
     */
    public $marginY;

    /**
     * Scroll Top Display Pages.
     *
     * @var int
     */
    public $display_pages;

    /**
     * Scroll Top include Pages.
     *
     * @var string
     */
    public $include_pages;

    /**
     * Scroll Top  exclude Pages.
     *
     * @var string
     */
    public $exclude_pages;

    /**
     * Scroll Top Image.
     *
     * @var string
     */
    public $image;

    /**
     * Scroll Top Image Alt.
     * 
     * @var string
     */
    public $image_alt;

    /**
     * Scroll Top Image Title.
     * 
     * @var string
     */
    public $image_title;

    /**
     * Scroll Top Custom URL.
     *
     * @var string
     */
    public $custom_url;

    /**
     * Scroll Top Text Button Text.
     *
     * @var string
     */
    public $text_button_text;

    /**
     * Scroll Top Text Button Text Color.
     *
     * @var string
     */
    public $text_button_text_color;

    /**
     * Scroll Top Text Button Background Color.
     * 
     * @var string
     */
    public $text_button_background_color;

    /**
     * Scroll Top Text Button Mouse Over Color.
     *
     * @var string
     */
    public $text_button_hover_color;

    /**
     * Scroll Top Custom CSS for Text Button.
     *
     * @var string
     */
    public $text_button_css;

    /**
     * Scroll Top FA Button Class.
     *
     * @var string
     */
    public $fa_button_class;

    /**
     * Scroll Top FA Button Exclude URL.
     *
     * @var bool
     */
    public $fa_button_exclude_URL;

    /**
     * Scroll Top FA Button Text Color.
     *
     * @var string
     */
    public $fa_button_text_color;

    /**
     * Scroll Top Custom CSS.
     *
     * @var string
     */
    public $fa_button_css;

    /**
     * Scroll Top Font Awesome URL.
     *
     * @var string
     */
    public $fa_button_URL;

    /**
     * Last updated
     *
     * @var int
     */
    public $last_updated;

    /**
     * Return Scroll Top Option Data.
     *
     * @return WPFront_Scroll_Top_Options $options
     */
    public function get()
    {
        $options = new WPFront_Scroll_Top_Options();
        $data = get_option('wpfront-scroll-top-options');

        if (empty($data)) {
            $data = array();
        }

        $options->enabled = !empty($data['enabled']);
        $options->javascript_async = !empty($data['javascript_async']);
        $options->scroll_offset = isset($data['scroll_offset']) ? (int)$data['scroll_offset'] : 100;
        $options->button_width = isset($data['button_width']) ? (int)$data['button_width'] : 0;
        $options->button_height = isset($data['button_height']) ? (int)$data['button_height'] : 0;
        $options->button_opacity = isset($data['button_opacity']) ? (int)$data['button_opacity'] : 80;
        $options->button_fade_duration = isset($data['button_fade_duration']) ? (int)$data['button_fade_duration'] : 0;
        $options->scroll_duration = isset($data['scroll_duration']) ? (int)$data['scroll_duration'] : 400;
        $options->auto_hide = !empty($data['auto_hide']);
        $options->auto_hide_after = isset($data['auto_hide_after']) ? (int)$data['auto_hide_after'] : 2;
        $options->hide_small_device = !empty($data['hide_small_device']);
        $options->small_device_width = isset($data['small_device_width']) ? (int)$data['small_device_width'] : 640;
        $options->hide_small_window = !empty($data['hide_small_window']);
        $options->small_window_width = isset($data['small_window_width']) ? (int)$data['small_window_width'] : 640;
        $options->hide_wpadmin = !empty($data['hide_wpadmin']);
        $options->hide_iframe = !empty($data['hide_iframe']);
        $options->attach_on_shutdown = !empty($data['attach_on_shutdown']);
        $options->button_style = isset($data['button_style']) ? $data['button_style'] : 'image';
        $options->button_action = isset($data['button_action']) ? $data['button_action'] : 'top';
        $options->button_action_element_selector = isset($data['button_action_element_selector']) ? $data['button_action_element_selector'] : '';
        $options->button_action_container_selector = isset($data['button_action_container_selector']) ? $data['button_action_container_selector'] : 'html, body';
        $options->button_action_element_offset = isset($data['button_action_element_offset']) ? $data['button_action_element_offset'] : 0;
        $options->button_action_page_url = isset($data['button_action_page_url']) ? $data['button_action_page_url'] : '';

        $options->location = isset($data['location']) ? (int)$data['location'] : 1;
        $options->marginX = isset($data['marginX']) ? (int)$data['marginX'] : 20;
        $options->marginY = isset($data['marginY']) ? (int)$data['marginY'] : 20;

        $options->display_pages = isset($data['display_pages']) ? intval($data['display_pages']) : 1;
        $options->include_pages = isset($data['include_pages']) ? $data['include_pages'] : '';
        $options->exclude_pages = isset($data['exclude_pages']) ? $data['exclude_pages'] : '';

        $options->image = isset($data['image']) ? $data['image'] : '1.png';
        $options->custom_url = isset($data['custom_url']) ? $data['custom_url'] : '';
        $options->image_alt = isset($data['image_alt']) ? $data['image_alt'] : '';
        $options->image_title = isset($data['image_title']) ? $data['image_title'] : '';

        $options->text_button_text = isset($data['text_button_text']) ? $data['text_button_text'] : '';
        $options->text_button_text_color = isset($data['text_button_text_color']) ? $data['text_button_text_color'] : '#ffffff';
        $options->text_button_background_color = isset($data['text_button_background_color']) ? $data['text_button_background_color'] : '#000000';
        $options->text_button_hover_color = isset($data['text_button_hover_color']) ? $data['text_button_hover_color'] : '#000000';
        $options->text_button_css = isset($data['text_button_css']) ? $data['text_button_css'] : '';

        $options->fa_button_class = isset($data['fa_button_class']) ? $data['fa_button_class'] : '';
        $options->fa_button_exclude_URL = !empty($data['fa_button_exclude_URL']);
        $options->fa_button_text_color = isset($data['fa_button_text_color']) ? $data['fa_button_text_color'] : '#000000';
        $options->fa_button_css = isset($data['fa_button_css']) ? $data['fa_button_css'] : '';
        $options->fa_button_URL = isset($data['fa_button_URL']) ? $data['fa_button_URL'] : '';

        $options->last_updated = empty($data['last_updated']) ? 0 : intval($data['last_updated']);

        return $options;
    }

    /**
     * Save Scroll Top Option Data.
     *
     * @return void
     */
    public function save()
    {
        $data = get_option('wpfront-scroll-top-options');

        if (empty($data)) {
            $data = array();
        }

        $data['enabled'] = $this->validate_bool($this->enabled);
        $data['javascript_async'] = $this->validate_bool($this->javascript_async);
        $data['scroll_offset'] = $this->validate_zero_positive($this->scroll_offset);
        $data['button_width'] = $this->validate_zero_positive($this->button_width);
        $data['button_height'] = $this->validate_zero_positive($this->button_height);
        $data['button_opacity'] = $this->validate_range_0_100($this->button_opacity);
        $data['button_fade_duration'] = $this->validate_zero_positive($this->button_fade_duration);
        $data['scroll_duration'] = $this->validate_zero_positive($this->scroll_duration);
        $data['auto_hide'] = $this->validate_bool($this->auto_hide);
        $data['auto_hide_after'] = $this->validate_zero_positive($this->auto_hide_after);
        $data['hide_small_device'] = $this->validate_bool($this->hide_small_device);
        $data['small_device_width'] = $this->validate_zero_positive($this->small_device_width);
        $data['hide_small_window'] = $this->validate_bool($this->hide_small_window);
        $data['small_window_width'] = $this->validate_zero_positive($this->small_window_width);
        $data['hide_wpadmin'] = $this->validate_bool($this->hide_wpadmin);
        $data['hide_iframe'] = $this->validate_bool($this->hide_iframe);
        $data['attach_on_shutdown'] = $this->validate_bool($this->attach_on_shutdown);
        $data['button_style'] = $this->validate_button_style($this->button_style);
        $data['button_action'] = $this->validate_button_action($this->button_action);
        $data['button_action_element_selector'] = $this->button_action_element_selector;
        $data['button_action_container_selector'] = $this->validate_button_action_container_selector($this->button_action_container_selector);
        $data['button_action_element_offset'] = $this->validate_int($this->button_action_element_offset);
        $data['button_action_page_url'] = $this->validate_page_url($this->button_action_page_url);

        $data['location'] = $this->validate_range_1_4($this->location);
        $data['marginX'] =  $this->validate_int($this->marginX);
        $data['marginY'] = $this->validate_int($this->marginY);

        $data['display_pages'] = $this->validate_display_pages($this->display_pages);
        $data['include_pages'] = $this->include_pages;
        $data['exclude_pages'] = $this->exclude_pages;

        $data['image'] = $this->image;
        $data['image_alt'] = $this->image_alt;
        $data['image_title'] = $this->image_title;
        $data['custom_url'] = $this->validate_custom_url($this->custom_url);

        $data['text_button_text'] = $this->text_button_text;
        $data['text_button_text_color'] = $this->validate_color($this->text_button_text_color);
        $data['text_button_background_color'] = $this->validate_color($this->text_button_background_color);
        $data['text_button_hover_color'] = $this->validate_color($this->text_button_hover_color);
        $data['text_button_css'] = $this->text_button_css;

        $data['fa_button_class'] = $this->fa_button_class;
        $data['fa_button_URL'] = $this->validate_font_awesome_url($this->fa_button_URL);
        $data['fa_button_exclude_URL'] = $this->validate_bool($this->fa_button_exclude_URL);
        $data['fa_button_text_color'] = $this->validate_color($this->fa_button_text_color);
        $data['fa_button_css'] = $this->fa_button_css;

        $data['last_updated'] = time();

        update_option('wpfront-scroll-top-options', $data);
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
     * @return bool
     */
    protected function validate_bool($arg)
    {
        if ($arg === 'false') {
            return false;
        }
        return (bool)$arg;
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
     * Return integer value in range of 0 to 100.
     *
     * @param mixed $arg
     * @return int<0, 100> $arg.
     */
    protected function validate_range_0_100($arg)
    {
        $arg = (int)$arg;
        if ($arg > 100) {
            return 100;
        } elseif ($arg < 0) {
            return 0;
        }
        return $arg;
    }

    /**
     * Return integer value in range of 1 to 4.
     *
     * @param mixed $arg
     * @return int<1, 4> $arg.
     */
    protected function validate_range_1_4($arg)
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
     * Returns Button Action.
     *
     * @param string $arg
     * @return string
     */
    protected function validate_button_action($arg)
    {
        if ($arg == 'element' || $arg == 'url') {
            return $arg;
        }

        return 'top';
    }

    /**
     * Returns Clean URL.
     *
     * @param string $arg
     * @return string
     */
    protected function validate_page_url($arg)
    {
        return esc_url_raw($arg);
    }

    /**
     * Returns Selector.
     *
     * @param string $args
     * @return string
     */
    protected function validate_button_action_container_selector($args)
    {
        $args = (string)$args;

        if (trim($args) === "") {
            return "html, body";
        }

        return $args;
    }

    /**
     * Returns Clean URL.
     *
     * @param string $arg
     * @return string
     */
    protected function validate_font_awesome_url($arg)
    {
        return esc_url_raw($arg);
    }

    /**
     * Return Color in Hex Format.
     *
     * @param string $color
     * @return string $color
     */
    protected function validate_color($color)
    {
        $color = sanitize_hex_color($color);
        if (empty($color)) {
            return '#ffffff';
        }

        return $color;  //@phpstan-ignore-line
    }

    /**
     * Returns Button Style.
     *
     * @param string $arg
     * @return string $arg
     */
    protected function validate_button_style($arg)
    {
        if ($arg == 'text' || $arg == 'font-awesome') {
            return $arg;
        }

        return 'image';
    }

    /**
     * Returns Display Page Option.
     *
     * @param int $arg
     * @return int $arg
     */
    protected function validate_display_pages($arg)
    {
        $arg = intval($arg);

        if ($arg < 1) {
            return 1;
        }

        if ($arg > 3) {
            return 3;
        }

        return $arg;
    }

    /**
     * Returns Clean URL.
     *
     * @param string $arg
     * @return string
     */
    protected function validate_custom_url($arg)
    {
        return esc_url_raw($arg);
    }
}
