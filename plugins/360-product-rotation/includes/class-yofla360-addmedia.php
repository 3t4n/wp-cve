<?php


/**
 * Class YoFLA360Addmedia
 *
 * Constructs the Media Upload page for uploading assets for the 360 views
 *
 */
class YoFLA360Addmedia
{

    protected $plugin_url;
    protected $products_path;

    /**
     * Start up
     */
    public function __construct()
    {

        add_action('admin_menu', array($this, 'add_plugin_media_page'));
        add_action('admin_enqueue_scripts', array($this, 'load_custom_wp_admin_style'));

        //set path to plugins
        $this->plugin_url = plugins_url('360-product-rotation/', YOFLA_360_PLUGIN_PATH);

        //set path to yofla360 folder within uploads
        $wp_uploads = wp_upload_dir();
        $this->products_path = $wp_uploads['basedir'] . '/' . YOFLA_360_PRODUCTS_FOLDER . '/';
    }


    /**
     * Add sytles and scripts for plugin media page
     */
    function load_custom_wp_admin_style($hook)
    {
        if ($hook == 'media_page_yofla-360-media') {
            wp_register_style('yofla_360_add_media_css', $this->plugin_url . 'styles/add-media.css', false, '1.0.0');
            wp_enqueue_style('yofla_360_add_media_css');

            wp_register_script('yofla_360_add_media_js_add', $this->plugin_url . '/js/add-media.js', false, '1.0.0');
            wp_enqueue_script('yofla_360_add_media_js_add');

        }
    }

    /**
     * Add options page
     */
    public function add_plugin_media_page()
    {
        // This page will be under "Settings"
        add_media_page(
            '360&deg; Views',
            '360&deg; Views',
            'edit_posts',
            'yofla-360-media', //menu slug
            array($this, 'create_plugin_media_page')
        );
    }

    /**
     * Options page callback
     */
    public function create_plugin_media_page()
    {

        $y360_options = get_option('yofla_360_options'); //read options stored in WP options database

        $is_using_license_id = !empty($y360_options['license_id']) && trim($y360_options['license_id']) !== "";
        $is_using_license_key = !empty($y360_options['license_key']) && trim($y360_options['license_key']) !== "";
        $is_using_license = $is_using_license_id || $is_using_license_key;

        $is_using_rotatetooljs_url = !empty($y360_options['rotatetooljs_url']) && filter_var($y360_options['rotatetooljs_url'], FILTER_VALIDATE_URL);
        $is_using_localengine = !empty($y360_options['local_engine']);

        $link_plugin_settings = '<a href="' . admin_url('options-general.php?page=yofla-360-admin') . '">Plugin Settings</a>';
        $link_creator = ' | <a target="_blank" href="https://www.y360.at/creator/?utm_source=wordpress_site&utm_medium=plugin&utm_content=page_media">Create new 360&deg; view</a>';

        if ($is_using_license) {
            $license_info = '';
        } else {
            /*
            $license_info = '<h2 style="color: red">License info</h2>';
            $license_info .= '<p style="font-size: 16px">You are using the <strong>free version</strong> of the player. You need set up your license';
            $license_info .= ' in the <a href="' . admin_url('options-general.php?page=yofla-360-admin') . '">plugin settings</a> first. </p>';
            */
            $license_info = '';
        }

        $lk = $y360_options['license_key'];
        $firstTwoChars = substr(strtolower($lk), 0, 2);
        $isCloudLicense = $firstTwoChars == 'yc';
        $selfHostedViewsList = YoFLA360()->Utils()->get_yofla360_directories_list();

        if ($lk) {
            if ($isCloudLicense) {
                // cloud license
                $selfHostedStyles = 'display: block;';
                $cloudViesList = YoFLA360()->Utils()->get_cloud_projects_by_lk($lk);
                $cloudHostedText = $this->_formatProjectsList($cloudViesList);
            } else {
                // other license
                $cloudHostedText = 'Your license does not support cloud hosting of 360 views';
                $selfHostedStyles = '';
            }

        }

        if (!$is_using_license) {
            $selfHostedStyles = 'display: block;';
            $cloudHostedText = 'No cloud-hosted files found...';
            $cloudHostedText .= ' Please enter your license key in the <a href="' . admin_url('options-general.php?page=yofla-360-admin') . '">Plugin settings</a> first.';
        }

        $selfHostedViewsScriptContent = $this->_formatSelfHostedProjectsList($selfHostedViewsList);

        ?>
        <div class="wrap">
            <h2>360&deg; Views :: Manage & Embed</h2>

            <?php echo $link_plugin_settings; ?>
            <?php echo $link_creator; ?>

            <div id="yofla360_plugin_media_wrapper">

                <?php echo $license_info ?>

                <h1>Cloud-Hosted 360&deg; Views</h1>
                <div class="yofla360_cloud_projects_wrapper">
                    <?php echo $cloudHostedText ?>
                </div>

                <h1>Self-Hosted 360&deg; Views</h1>
                <div id="yofla360_plugin_media_upload" style="<?php echo $selfHostedStyles; ?>>">
                    <div id="yofla360_plugin_media_list">
                        <ul class="products_list">
                        </ul>
                    </div>
                </div>
            </div>


        </div>
        <script type="text/javascript">
            <?php echo $selfHostedViewsScriptContent ?>
        </script>
        <?php
    }



    // Returns a file size limit in bytes based on the PHP upload_max_filesize
    // and post_max_size
    private function _file_upload_max_size()
    {
        static $max_size = -1;

        if ($max_size < 0) {
            // Start with post_max_size.
            $max_size = $this->_parse_size(ini_get('post_max_size'));

            // If upload_max_size is less, then reduce. Except if upload_max_size is
            // zero, which indicates no limit.
            $upload_max = $this->_parse_size(ini_get('upload_max_filesize'));
            if ($upload_max > 0 && $upload_max < $max_size) {
                $max_size = $upload_max;
            }
        }
        return $max_size;
    }

    private function _parse_size($size)
    {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
        $size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
        if ($unit) {
            // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
            return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        } else {
            return round($size);
        }
    }

    private function _formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        // Uncomment one of the following alternatives
        $bytes /= pow(1024, $pow);
        // $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    private function _formatSelfHostedProjectsList($content)
    {
        if (!$content || sizeof($content) == 0) {
            return '';
        }
        return "var selfHostedProjectsListData = " . json_encode($content) . ";";
    }

    private function _formatProjectsList($items)
    {
        $out = '';
        $link_plugin_settings = '<a href="' . admin_url('options-general.php?page=yofla-360-admin') . '">Plugin Settings</a>';

        if (!is_array($items)) {
            $out = 'No valid 360 views found for your license key. Please check your license key in ' . $link_plugin_settings;
            return $out;
        }

        foreach ($items as $item) {

            if(!isset($item['name'])) continue;

            $thumbnailUrl = $item['thumbnailUrl'];
            $name = $item['name'];
            $separator = ';';
            $width = $item['playerWidth'];
            $height = $item['playerHeight'];

            $cid = $item['accountId'] . $separator . $item['projectId'] . $separator . $item['versionNumber'];
            $shortCode = "[360 src=\"$cid\" width=\"$width\" height=\"$height\" auto-height=\"true\"]";
            $shortcodeInputId = "sc_" . $item['projectId'];
            $shortcodeNoteId = $shortcodeInputId . "_note";

            $nameNew = "Test";
            $thumbnailUrlNew = "https://www.yofla.com/3d-rotate/cdn/rotatetool/360-project-logo.png";

            $out .= "<div class='yofla360_cloud_projects__item'>";
            $out .= "   <div class='yofla360_cloud_projects__item-image-wrapper'>";
            $out .= "     <img class='yofla360_cloud_projects__item-image-thumb' src='$thumbnailUrl'>";
            $out .= "   </div>";
            $out .= "  <h5 class='yofla360_cloud_projects__item-title'>";
            $out .= "$name";
            $out .= "  </h5>";
            $out .= "  <div class='yofla360_cloud_projects__item-description'>";
            $out .= "  <p>";
            $out .= "    <span class='yofla360_cloud_projects__item-date'>";
            $out .= "      Updated: " . $item['updatedAt'];
            $out .= ", rev.#" . intVal($item['versionNumber']);
            $out .= "    </span>";
            $out .= "  </p>";
            $out .= "  <input id='$shortcodeInputId' class='yofla360_cloud_projects__input' type='text' value='$shortCode'>";
            $out .= "  <button onclick='yCopyShortcodeToClipboard(\"" . $shortcodeInputId . "\")'>";
            $out .= "     Copy Shortcode";
            $out .= "  </button>";
            $out .= "  <button onclick='yPreview360View(\"" . $cid . "\"," . $width . "," . $height . ")' class='yofla360_cloud_projects__button-preview'>";
            $out .= "     Preview";
            $out .= "  </button>";
            $out .= "    <span id='$shortcodeNoteId' class='yofla360_cloud_projects__item-copiedNote'>";
            $out .= "Copied!";
            $out .= "    </span>";
            $out .= "  </div>";
            $out .= "</div>";
        }

        // link to crator to create new item at the end of the list
        $out .= "<div class='yofla360_cloud_projects__item'>";
        $out .= "<div style='text-align: center; margin-top: 40px; line-height: 25px;'>";
        $out .= " <a target='_blank' href='https://www.y360.at/creator/?utm_source=wordpress_site&utm_medium=plugin&utm_content=page_media'> Create new </a>";
        $out .= "  <div>";
        $out .= "  (opens in new window)";
        $out .= "  </div>";
        $out .= "</div>";
        $out .= "</div>";

        return $out;
    }
}
