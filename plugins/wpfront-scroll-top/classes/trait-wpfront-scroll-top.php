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

trait WPFront_Scroll_Top_Functions
{

    /**
     * 
     *
     * @return array<string, string>
     */
    protected function get_labels_data()
    {
        return array(
            'enabled' => __('Enabled', 'wpfront-scroll-top'),
            'javascript_async' => __('Javascript Async', 'wpfront-scroll-top'),
            'scroll_offset' => __('Scroll Offset', 'wpfront-scroll-top'),
            'button_size' => __('Button Size', 'wpfront-scroll-top'),
            'button_opacity' => __('Button Opacity', 'wpfront-scroll-top'),
            'button_fade_duration' => __('Button Fade Duration', 'wpfront-scroll-top'),
            'scroll_duration' => __('Scroll Duration', 'wpfront-scroll-top'),
            'auto_hide' => __('Auto Hide', 'wpfront-scroll-top'),
            'auto_hide_after' => __('Auto Hide After', 'wpfront-scroll-top'),
            'hide_small_device' => __('Hide on Small Devices', 'wpfront-scroll-top'),
            'small_device_width' => __('Small Device Max Width', 'wpfront-scroll-top'),
            'hide_small_window' => __('Hide on Small Window', 'wpfront-scroll-top'),
            'small_window_width' => __('Small Window Max Width', 'wpfront-scroll-top'),
            'hide_wpadmin' => __('Hide on WP-ADMIN', 'wpfront-scroll-top'),
            'hide_iframe' => __('Hide on iframes', 'wpfront-scroll-top'),
            'attach_on_shutdown' => __('Attach on Shutdown', 'wpfront-scroll-top'),
            'button_style' => __('Button Style', 'wpfront-scroll-top'),
            'button_action' => __('Button Action', 'wpfront-scroll-top'),
            'location' => __('Location', 'wpfront-scroll-top'),
            'marginX' => __('Margin X', 'wpfront-scroll-top'),
            'marginY' => __('Margin Y', 'wpfront-scroll-top'),
            'custom_url' => __('Custom URL', 'wpfront-scroll-top'),
            'image_alt' => __('Image ALT', 'wpfront-scroll-top'),
            'image_title' => __('Image Title', 'wpfront-scroll-top'),
            'text_button_text' => __('Text', 'wpfront-scroll-top'),
            'text_button_text_color' => __('Text Color', 'wpfront-scroll-top'),
            'text_button_background_color' => __('Background Color', 'wpfront-scroll-top'),
            'text_button_hover_color' => __('Mouse Over Color', 'wpfront-scroll-top'),
            'text_button_css' => __('Custom CSS', 'wpfront-scroll-top'),
            'fa_button_class' => __('Icon Class', 'wpfront-scroll-top'),
            'fa_button_URL' => __('Font Awesome URL', 'wpfront-scroll-top'),
            'fa_button_exclude_URL' => __('Do not include URL', 'wpfront-scroll-top'),
            'fa_button_text_color' => __('Icon Color', 'wpfront-scroll-top'),
            'fa_button_css' => __('Custom CSS', 'wpfront-scroll-top'),
            'button_action_element_selector' => __('Element CSS Selector', 'wpfront-scroll-top'),
            'button_action_container_selector' =>  __('Scroll Container CSS Selector', 'wpfront-scroll-top'),
            'button_action_element_offset' => __('Offset', 'wpfront-scroll-top'),
            'button_action_element_how_to_link' => __('How to find CSS selector?', 'wpfront-scroll-top'),
            'button_action_page_url' => __('Page URL', 'wpfront-scroll-top'),
            'display_pages' => __('Display on Pages', 'wpfront-scroll-top'),
            'media_library_button' => __('Media Library', 'wpfront-scroll-top'),
            'media_library_title' => __('Choose Image', 'wpfront-scroll-top'),
            'media_library_text' => __('Select Image', 'wpfront-scroll-top')
        );
    }

    /**
     * 
     *
     * @return array<string, string>
     */
    protected function get_help_data()
    {
        return array(
            'enabled' => __('Enables the scroll top button.', 'wpfront-scroll-top'),
            'javascript_async' =>  __('Increases site performance. Keep it enabled, if there are no conflicts.', 'wpfront-scroll-top'),
            'scroll_offset' => __('Number of pixels to be scrolled before the button appears.', 'wpfront-scroll-top'),
            'button_size' => __('Set 0px to auto fit.', 'wpfront-scroll-top'),
            'button_opacity' => __('Set transparency of the button.', 'wpfront-scroll-top'),
            'button_fade_duration' =>  __('Button fade duration in milliseconds.', 'wpfront-scroll-top'),
            'scroll_duration' => __('Window scroll duration in milliseconds.', 'wpfront-scroll-top'),
            'auto_hide' => __('Enable to hide the button automatically.', 'wpfront-scroll-top'),
            'auto_hide_after' => __('Button will be auto hidden after this duration in seconds, if enabled.', 'wpfront-scroll-top'),
            'hide_small_device' => __('Deprecated, will be removed in a future version.', 'wpfront-scroll-top'),
            'small_device_width' => __('Deprecated, will be removed in a future version.', 'wpfront-scroll-top'),
            'hide_small_window' => __('Button will be hidden on broswer window when the width matches.', 'wpfront-scroll-top'),
            'small_window_width' => __('Button will be hidden on browser window with lesser or equal width.', 'wpfront-scroll-top'),
            'hide_wpadmin' => __('Button will be hidden on \'wp-admin\'.', 'wpfront-scroll-top'),
            'hide_iframe' => __('Button will be hidden on iframes, usually inside popups.', 'wpfront-scroll-top'),
            'attach_on_shutdown' => __('Enable as a last resort if the button is not working. This could create compatibility issues.', 'wpfront-scroll-top'),
            'button_style_options_image' => __('Built in or custom icon as button.', 'wpfront-scroll-top'),
            'button_style_options_text' => __('Text as button.', 'wpfront-scroll-top'),
            'button_style_options_font-awesome' => __('Font awesome icon as button.', 'wpfront-scroll-top'),
            'button_action_options_top' => __('Default action on WP-ADMIN pages.', 'wpfront-scroll-top'),
            'button_action_options_element' => __('Scroll to the element specified by the user.', 'wpfront-scroll-top'),
            'button_action_options_url' => __('Redirects to the URL.', 'wpfront-scroll-top'),
            'button_action_page_url' => __('URL of the page, you are trying to redirect to.', 'wpfront-scroll-top'),
            'location' => __('Sets the location of the scroll top button. Default is bottom right position.', 'wpfront-scroll-top'),
            'marginX' => __('Negative values allowed.', 'wpfront-scroll-top'),
            'marginY' => __('Negative values allowed.', 'wpfront-scroll-top'),
            'image_alt' => __('Alternative information for an image', 'wpfront-scroll-top'),
            'image_title' => __('HTML title attribute(displays as a tooltip).', 'wpfront-scroll-top'),
            'include_in_pages' => __('Use the textbox below to specify the post IDs as a comma separated list.', 'wpfront-scroll-top'),
            'exclude_in_pages' => __('Use the textbox below to specify the post IDs as a comma separated list.', 'wpfront-scroll-top'),
            'text_button_text' => __('Text to be displayed.', 'wpfront-scroll-top'),
            'text_button_text_color' => __('Hex color code.', 'wpfront-scroll-top'),
            'text_button_background_color' => __('Hex color code.', 'wpfront-scroll-top'),
            'text_button_hover_color' => __('Hex color code.', 'wpfront-scroll-top'),
            'text_button_css' => __('ex:', 'wpfront-scroll-top') . ' font-size: 1.5em; padding: 10px;',
            'fa_button_class' => __('ex:', 'wpfront-scroll-top') . ' fa fa-arrow-circle-up fa-5x',
            'fa_button_URL' => __('Leave blank to use BootstrapCDN URL by MaxCDN. Otherwise specify the URL you want to use.', 'wpfront-scroll-top'),
            'fa_button_text_color' => __('Hex color code.', 'wpfront-scroll-top'),
            'fa_button_exclude_URL' => __('Enable this setting if your site already has Font Awesome. Usually your theme includes it.', 'wpfront-scroll-top'),
            'fa_button_css' => __('ex:', 'wpfront-scroll-top') . ' #wpfront-scroll-top-container i:hover{ color: #000000; }',
            'button_action_element_selector' => __('CSS selector of the element, you are trying to scroll to. Ex: #myDivID, .myDivClass', 'wpfront-scroll-top'),
            'button_action_container_selector' =>  __('CSS selector of the element, which has the scroll bar. "html, body" works in almost all cases.', 'wpfront-scroll-top'),
            'button_action_element_offset' => __('Negative value allowed. Use this filed to precisely set scroll position. Useful when you have overlapping elements.', 'wpfront-scroll-top'),
        );
    }

    /**
     * 
     *
     * @return array<int, string>
     */
    protected function get_location_options()
    {
        return array(
            1 => __('Bottom Right', 'wpfront-scroll-top'),
            2 =>  __('Bottom Left', 'wpfront-scroll-top'),
            3 => __('Top Right', 'wpfront-scroll-top'),
            4 => __('Top Left', 'wpfront-scroll-top')
        );
    }

    /**
     * 
     *
     * @return array<string, string>
     */
    protected function get_button_style_options()
    {
        return array(
            'image' => __('Image Button', 'wpfront-scroll-top'),
            'text' =>  __('Text Button', 'wpfront-scroll-top'),
            'font-awesome' => __('Font Awesome Button', 'wpfront-scroll-top')
        );
    }

    /**
     * 
     *
     * @return array<string, string>
     */
    protected function get_button_action_options()
    {
        return array(
            'top' => __('Scroll to Top', 'wpfront-scroll-top'),
            'element' =>  __('Scroll to Element', 'wpfront-scroll-top'),
            'url' => __('Link to Page', 'wpfront-scroll-top')
        );
    }

    /**
     * 
     *
     * @return array<int, string>
     */
    protected function get_filter_options()
    {
        return array(
            1 => __('All pages', 'wpfront-scroll-top'),
            2 =>  __('Include in pages', 'wpfront-scroll-top'),
            3 => __('Exclude in pages', 'wpfront-scroll-top')
        );
    }

    /**
     * 
     *
     * @return array<string|int, string>
     */
    protected function get_filter_objects()
    {
        $objects = array();

        $objects['home'] = __('[Home Page]', 'wpfront-scroll-top');

        $pages = $this->get_pages(50);
        foreach ($pages as $page) {
            $objects[$page->ID] = __('[Page]', 'wpfront-scroll-top') . ' ' . $page->post_title;
        }

        $posts = $this->get_posts(50);
        foreach ($posts as $post) {
            $objects[$post->ID] = __('[Post]', 'wpfront-scroll-top') . ' ' . $post->post_title;
        }

        //            $categories = get_categories();
        //            foreach ($categories as $category) {
        //                $objects['3.' . $category->cat_ID] = __('[Category]', 'wpfront-scroll-top') . ' ' . $category->cat_name;
        //            }

        return $objects;
    }

    /**
     * Returns pages
     *
     * @param int $count
     * @return \WP_Post[]
     */
    protected function get_pages($count)
    {
        $pages = get_pages(array('number' => $count));
        if (is_array($pages)) {
            return $pages;
        }

        return [];
    }

    /**
     * Returns posts
     *
     * @param int $count
     * @return \WP_Post[]
     */
    protected function get_posts($count)
    {
        $posts = get_posts(array('numberposts' => $count));
        if (is_array($posts) && !empty($posts) && gettype($posts[0]) === 'object') {
            return $posts;
        }

        return [];
    }

    /**
     * 
     *
     * @return array<string, string> icon => url
     */
    protected function get_icons()
    {
        $files = [];
        $dir = dirname(__FILE__, 2) . '/images/icons';
        $icons = scandir($dir);

        if (!is_array($icons)) {
            return $files; // @codeCoverageIgnore
        }

        $url = plugin_dir_url($dir . '/1.png');
        foreach ($icons as $icon) {
            if (substr($icon, -4) !== '.png') {
                continue;
            }

            $src = $url . $icon;
            $files[$icon] = $src;
        }

        return $files;
    }
}
