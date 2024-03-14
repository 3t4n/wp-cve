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
 * Template for WPFront Scroll Top Options
 *
 * @author Syam Mohan <syam@wpfront.com>
 * @copyright 2013 WPFront.com
 */
class WPFront_Scroll_Top_Options_View
{

    /**
     * Controller
     *
     * @var WPFront_Scroll_Top
     */
    protected $controller;

    /**
     * View
     *
     * @param WPFront_Scroll_Top $controller
     * @return void
     */
    public function view($controller)
    {
        $this->controller = $controller;
        $this->display();
    }

    /**
     * Display logic
     *
     * @return void
     */
    protected function display()
    {
        add_meta_box('postbox-display-settings', __('Display', 'wpfront-scroll-top'), array($this, 'display_settings_html'), 'wpfront-scroll-top', 'normal');
        add_meta_box('postbox-location-settings', __('Location', 'wpfront-scroll-top'), array($this, 'location_settings_html'), 'wpfront-scroll-top', 'normal');
        add_meta_box('postbox-filter-settings', __('Filter', 'wpfront-scroll-top'), array($this, 'filter_settings_html'), 'wpfront-scroll-top', 'normal');
        add_meta_box('postbox-button-settings', '<span style="display:none" v-show="!loading">{{ button_style_options[data.button_style] }}</span>', array($this, 'button_settings_html'), 'wpfront-scroll-top', 'normal');
        add_meta_box("postbox-side-1", __('Action', 'wpfront-scroll-top'), array($this, 'action_buttons'), 'wpfront-scroll-top', 'side', 'default');
        wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false);
        wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false);
?>

        <div class="wrap scroll-top">
            <h2><?php echo __('WPFront Scroll Top Settings', 'wpfront-scroll-top'); ?></h2>
            <?php $this->notice(); ?>

            <div id="scroll-top-content" class="wrap">
                <form onsubmit="return false" @submit.prevent="submit">
                    <div id="poststuff">
                        <div id="post-body" class="metabox-holder columns-2">
                            <div id="post-body-content">
                                <?php do_meta_boxes('wpfront-scroll-top', 'normal', null); ?>
                            </div>
                            <div id="postbox-container-1" class="postbox-container-right">
                                <?php do_meta_boxes('wpfront-scroll-top', 'side', null); ?>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <script type="text/javascript">
                window.load_wpf_st('<?php echo WPFront_Scroll_Top::VERSION ?>');
            </script>
        </div>
        <?php add_filter('admin_footer_text', array($this, 'admin_footer_text')); ?>

    <?php
    }

    /**
     * Loading animation
     *
     * @return void
     */
    private function loading()
    {
    ?>
        <div class="loading" v-if="loading">
            <p></p>
            <p></p>
            <p></p>
            <p></p>
            <p></p>
        </div>
    <?php
    }

    /**
     * Display settings html
     *
     * @return void
     */
    public function display_settings_html()
    {
        $this->loading();
    ?>
        <display-settings v-show="!loading"></display-settings>
    <?php
    }

    /**
     * Location settings html
     *
     * @return void
     */
    public function location_settings_html()
    {
        $this->loading();
    ?>
        <location-settings v-show="!loading"></location-settings>
    <?php
    }

    /**
     * Button settings html
     *
     * @return void
     */
    public function button_settings_html()
    {
        $this->loading();
    ?>
        <image-button-settings v-show="!loading" v-if="data.button_style === 'image'"></image-button-settings>
        <text-button-settings v-show="!loading" v-if="data.button_style === 'text'"></text-button-settings>
        <font-awesome-button-settings v-show="!loading" v-if="data.button_style === 'font-awesome'"></font-awesome-button-settings>
    <?php
    }

    /**
     * Filter settings html
     *
     * @return void
     */
    public function filter_settings_html()
    {
        $this->loading();
    ?>
        <filter-settings v-show="!loading"></filter-settings>
    <?php
    }

    /**
     * Buttons html
     *
     * @return void
     */
    public function action_buttons()
    {
    ?>
        <p class="submit">
            <input type="submit" :disabled="loading" class="button" :class="{ 'button-primary': !loading }" value="<?php echo __('Save Changes', 'wpfront-scroll-top'); ?>" />
        </p>
        <div class="hidden" :class="{ 'notice': error, 'error': error }">
            <p>
                <strong>{{ error }}</strong>
            </p>
        </div>
    <?php
    }

    /**
     * Adds the help links at bottom
     *
     * @param string $text
     * @return string
     */
    public function admin_footer_text($text)
    {
        $settingsLink = 'scroll-top-plugin-settings/';

        $settingsLink = sprintf('<a href="%s" target="_blank">%s</a>', 'https://wpfront.com/' . $settingsLink, __('Settings Description', 'wpfront-scroll-top'));
        $reviewLink = sprintf('<a href="%s" target="_blank">%s</a>', 'https://wordpress.org/support/plugin/' . WPFront_Scroll_Top::PLUGIN_SLUG . '/reviews/', __('Write a Review', 'wpfront-scroll-top'));
        $donateLink = sprintf('<a href="%s" target="_blank">%s</a>', 'https://wpfront.com/donate/', __('Buy me a Beer or Coffee', 'wpfront-scroll-top'));

        return sprintf('%s | %s | %s | %s', $settingsLink, $reviewLink, $donateLink, $text);
    }

    /**
     * Display notices
     *
     * @return void
     */
    public function notice()
    {
    ?>
        <?php if (isset($_GET['settings-updated'])) { ?>
            <div class="notice notice-success is-dismissible">
                <p>
                    <strong><?php echo __('Settings Saved', 'wpfront-scroll-top') ?></strong>
                </p>
            </div>
            <div class="notice notice-success is-dismissible">
                <p>
                    <strong><?php echo __('If you have a caching plugin, clear the cache for the new settings to take effect.', 'wpfront-scroll-top'); ?></strong>
                </p>
            </div>
        <?php } ?>
<?php
    }
}
