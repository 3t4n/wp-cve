<?php
/**
 * XPRO Cloud Templates initial setup
 *
 * @since 1.0.0
 * @package XPRO Cloud Templates
 */

if (!class_exists('XPRO_Cloud_Templates')) {
    /**
     * This class initializes Xpro Cloud Templates
     *
     * @class XPRO_Cloud_Templates
     */
    class XPRO_Cloud_Templates
    {
        /**
         * Holds an instance of Cloud Templates.
         *
         * @since 1.0.0
         * @var $instance instance
         */
        private static $instance;
        /**
         * Holds an cloud URL.
         *
         * @since 1.0.0
         * @var $cloud_url cloud_url
         */
        private static $cloud_url;
        /**
         * Holds an Xpro file system.
         *
         * @since 1.0.0
         * @var $xpro_filesystem Xpro filesystem
         */
        protected static $xpro_filesystem = null;

        /**
         *  Initiator
         *
         * @since 1.0.0
         */
        public static function get_instance()
        {
            if (!isset(self::$instance)) {
                self::$instance = new XPRO_Cloud_Templates();
            }
            return self::$instance;
        }

        /**
         * Constructor function that initializes required actions and hooks
         *
         * @since 1.0.0
         */
        public function __construct()
        {

            self::$cloud_url = apply_filters(
                'xpro_template_cloud_api',
                array(
                    'page-templates' => 'https://bbdemos.wpxpro.com/json-data/layouts.json',
                    'sections' => 'https://bbdemos.wpxpro.com/json-data/sections.json',
                    'presets' => 'https://bbdemos.wpxpro.com/json-data/presets.json',
                )
            );

            // AJAX actions.
            add_action('wp_ajax_xpro_cloud_dat_file', array($this, 'download_cloud_templates'));
            add_action('wp_ajax_xpro_cloud_dat_file_remove', array($this, 'remove_local_dat_file'));
            add_action('wp_ajax_xpro_cloud_dat_file_fetch', array($this, 'fetch_cloud_templates'));

            // Buttons.
            add_action('xpro_cloud_template_buttons', array($this, 'button_title'));

            // Auto process the cloud templates.
            add_action('admin_init', array($this, 'process_cloud_request'));

            self::refresh_cloud_templates();

        }

        /**
         * Process cloud request
         * If transient / option is expired.
         *
         * @param string $proceed gets the string for cloud.
         */
        public function process_cloud_request($proceed = false)
        {

            if (false === get_transient('xpro_cloud_transient')) {

                $proceed = true;

                if (5.2 < phpversion()) {
                    $transient = get_option('xpro_cloud_templates');

                    if (false !== $transient) {

                        $datetime1 = new DateTime();
                        $date_string = gmdate('Y-m-d\TH:i:s\Z', $transient);
                        $datetime2 = new DateTime($date_string);
                        $interval = $datetime1->diff($datetime2);
                        $elapsed = $interval->format('%h');

                        if (24 >= $elapsed || '24' >= $elapsed) {
                            $proceed = false;
                        }
                    }
                }

                if ($proceed) {

                    // Refresh cloud templates.
                    self::refresh_cloud_templates();

                    // Set transient & option.
                    self::set_transients();
                }
            } else {

                // Set transient & option.
                self::set_transients();
            }

            return $proceed;

        }

        /**
         * Set transient / option.
         */
        public static function set_transients()
        {
            update_option('xpro_cloud_templates', current_time('timestamp')); // phpcs:ignore WordPress.DateTime.CurrentTimeTimestamp.Requested
            set_transient('xpro_cloud_transient', true, DAY_IN_SECONDS);
        }

        /**
         * Reset Cloud Transient
         */
        public static function refresh_cloud_templates()
        {

            // get - downloaded templates.
            $cloud_templates = array();
            $downloaded_templates = get_site_option('_xpro_cloud_templats', false);

            // get - cloud templates by type.
            foreach (self::$cloud_url as $type => $url) {

                $https_url = $url;
                $ssl = wp_http_supports(array('ssl'));
                if ($ssl) {
                    $https_url = set_url_scheme($https_url, 'https');
                }

                $response = wp_remote_get(
                    $https_url,
                    array(
                        'timeout' => 30,
                    )
                );

                if ($ssl && is_wp_error($response)) {

                    $response = wp_remote_get(
                        $url,
                        array(
                            'timeout' => 30,
                        )
                    );
                }

                if (is_wp_error($response)) {
                    $type_templates = 'wp_error';
                }

                $type_templates = json_decode(wp_remote_retrieve_body($response), 1);

                /**
                 *  Has {cloud} && has {downloaded}
                 *
                 *  Then, keep latest & installed templates.
                 */
                if (
                    (is_array($type_templates) && count($type_templates) > 0) && (isset($downloaded_templates[$type]) && count($downloaded_templates[$type]) > 0)
                ) {
                    /**
                     * Handle unexpected JSON response
                     */
                    if (
                        array_key_exists('code', $type_templates) ||
                        array_key_exists('message', $type_templates) ||
                        array_key_exists('data', $type_templates)
                    ) {
                        return;
                    }

                    foreach ($downloaded_templates[$type] as $key => $template) {

                        /**
                         *  Found in template id in local templates?
                         *  then, add 'status' & 'dat_url_local' to the template by matching its template id
                         */
                        if (array_key_exists($key, $type_templates)) {

                            $type_templates[$key]['status'] = (isset($downloaded_templates[$type][$key]['status'])) ? $downloaded_templates[$type][$key]['status'] : '';
                            $type_templates[$key]['dat_url_local'] = (isset($downloaded_templates[$type][$key]['dat_url_local'])) ? $downloaded_templates[$type][$key]['dat_url_local'] : '';

                            /**
                             *  Not found local template id in new templates
                             *  then add template to new template array
                             */
                        } else {

                            /**
                             *  Only downloaded old templates are added in new templates
                             *  If old template is not downloaded recently then it'll be removed.
                             */
                            if (
                                ($downloaded_templates[$type][$key]['status'] && array_key_exists('status', $downloaded_templates[$type][$key])) &&
                                (array_key_exists('dat_url_local', $downloaded_templates[$type][$key]))
                            ) {

                                /**
                                 *  Add if 'status' == 'true' &&
                                 *  Add if not empty 'dat_url_local'
                                 */
                                if (
                                    ($downloaded_templates[$type][$key]['status'] && 'true' === $downloaded_templates[$type][$key]['status']) &&
                                    (!empty($downloaded_templates[$type][$key]['dat_url_local']))
                                ) {
                                    $type_templates[$key] = $downloaded_templates[$type][$key];
                                }
                            }
                        }
                    }

                    $cloud_templates[$type] = $type_templates;

                    /**
                     *  Has {cloud} && NOT has {downloaded}
                     *
                     *  Then, keep cloud.
                     */
                } elseif (
                    (is_array($type_templates) && count((is_array($type_templates) || is_object($type_templates)) ? $type_templates : array()) > 0)
                ) {
                    if (null === $downloaded_templates[$type] || count(is_array($downloaded_templates[$type])) === 0) {

                        $cloud_templates[$type] = $type_templates;
                    }
                    /**
                     *  NOT has {cloud} && has {downloaded}
                     *
                     *  Then, keep downloaded.
                     */
                } elseif (0 === $type_templates && count((is_array($downloaded_templates[$type]) || is_object($downloaded_templates[$type])) ? $downloaded_templates[$type] : array()) > 0) {

                    $cloud_templates[$type] = $downloaded_templates[$type];
                }
            }

            /**
             * Finally update the cloud templates
             *
             * So, used update_site_option() to update network option '_xpro_cloud_templats'
             */
            update_site_option('_xpro_cloud_templats', $cloud_templates, true);
        }

        /**
         * Get cloud templates
         * @since 1.0.0
         */
        public static function get_cloud_templates_count($type = '')
        {
            $templates = get_site_option('_xpro_cloud_templats', false);
            $templates_count = 0;

            if (is_array($templates) && count($templates) > 0) {
                switch ($type) {
                    case 'page-templates':
                    case 'presets':
                        if (array_key_exists($type, $templates)) {
                            $templates_count = count($templates[$type]);
                        }
                        break;
                    case 'sections':
                        if (array_key_exists($type, $templates)) {
                            if (is_array($templates[$type]) && count($templates[$type]) > 1) {
                                foreach ($templates[$type] as $id => $template) {
                                    $count = (isset($template['count'])) ? $template['count'] : 0;
                                    $templates_count = $templates_count + $count;
                                }
                            }
                        }
                        break;
                    default:
                        foreach (self::$cloud_url as $type => $url) {
                            $templates_count = $templates_count + count($templates[$type]);
                        }
                        break;
                }
            }

            return $templates_count;
        }

        /**
         * Get cloud templates
         * @since 1.0.0
         */
        public static function get_cloud_templates($type = '')
        {

            $templates = get_site_option('_xpro_cloud_templats', false);

            if (!empty($templates)) {

                // Return all templates.
                if (empty($type)) {
                    return $templates;

                    // Return specific templates.
                } else {

                    if (array_key_exists($type, $templates)) {
                        return $templates[$type];
                    }
                }
            } else {
                return array();
            }

        }

        /**
         * Remove local dat files
         *
         * @since 1.0.0
         */
        public function remove_local_dat_file()
        {

            check_ajax_referer('xpro_cloud_nonce', 'form_nonce');
            // Get template details.
            $dat_file_id = ($_POST['dat_file_id']) ? sanitize_text_field($_POST['dat_file_id']) : '';
            $dat_url_local = ($_POST['dat_file_url_local']) ? sanitize_text_field($_POST['dat_file_url_local']) : '';
            $dat_file_type = ($_POST['dat_file_type']) ? $this->get_right_type_key(sanitize_text_field($_POST['dat_file_type'])) : '';
            $templates = get_site_option('_xpro_cloud_templats', false);
            $updatedstatus = false;
            $removeddatfile = false;
            $msg = array();
            $ajaxresult['id'] = $dat_file_id;
            $ajaxresult['type'] = $dat_file_type;

            /**
             *  1. Update template status
             *  is [page-templates / sections / presets] exist?
             */
            if (array_key_exists($dat_file_type, $templates)) {

                // is template [ID] exist?
                if (array_key_exists($dat_file_id, $templates[$dat_file_type])) {

                    // [status] key exist?
                    if (array_key_exists('status', $templates[$dat_file_type][$dat_file_id])) {
                        $templates[$dat_file_type][$dat_file_id]['status'] = false;
                        $updatedstatus = true;
                    } else {
                        $msg[] = 'Not found [status] for ID: ' . $dat_file_id;
                    }

                    /**
                     *  2. Remove .dat file from local
                     */
                    $local_dat_file = (isset($templates[$dat_file_type][$dat_file_id]['dat_url_local'])) ? $templates[$dat_file_type][$dat_file_id]['dat_url_local'] : '';
                    $dat_name = $templates[$dat_file_type][$dat_file_id]['name'];
                    $dat_category = $templates[$dat_file_type][$dat_file_id]['category'];
                    $dat_version = $templates[$dat_file_type][$dat_file_id]['version'];
                    if (!empty($local_dat_file) && file_exists($local_dat_file)) {
                        unlink($local_dat_file);
                        $removeddatfile = true;
                    } else {
                        $msg[] = 'Not found [dat_url_local] for ID: ' . $dat_file_id;
                    }

                    if ('page-templates' === $dat_file_type) {

                        $xpro_dat_url = 'https://bbdemos.wpxpro.com/page-templates/template-' . strtolower(preg_replace('/[[:space:]]+/', '-', $dat_name)) . '.dat';

                    } elseif ('sections' === $dat_file_type) {

                        if ('gallery' === $dat_category && 'pro' === $dat_version) {

                            $xpro_dat_url = 'https://bbdemos.wpxpro.com/sections/gallery/gallery-' . strtolower(preg_replace('/[[:space:]]+/', '-', $dat_name)) . '.dat';

                        } elseif ('gallery' === $dat_category && 'lite' === $dat_version) {

                            $xpro_dat_url = 'https://bbdemos.wpxpro.com/sections/gallery/gallery-lite-' . strtolower(preg_replace('/[[:space:]]+/', '-', $dat_name)) . '.dat';

                        } elseif ('portfolio' === $dat_category && 'pro' === $dat_version) {

                            $xpro_dat_url = 'https://bbdemos.wpxpro.com/sections/portfolio/portfolio-' . strtolower(preg_replace('/[[:space:]]+/', '-', $dat_name)) . '.dat';

                        } elseif ('portfolio' === $dat_category && 'lite' === $dat_version) {

                            $xpro_dat_url = 'https://bbdemos.wpxpro.com/sections/portfolio/portfolio-lite-' . strtolower(preg_replace('/[[:space:]]+/', '-', $dat_name)) . '.dat';

                        }
                    }

                    /**
                     *  3. Setting AJAX response to initialize Download button
                     */
                    $remote_dat_file = (isset($templates[$dat_file_type][$dat_file_id]['dat_url'])) ? urlencode($templates[$dat_file_type][$dat_file_id]['dat_url']) : ''; // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.urlencode_urlencode
                    $ajaxresult['dat_url'] = $xpro_dat_url;
                    $ajaxresult['status'] = 'success';

                    /**
                     * Finally update the cloud templates
                     *
                     * So, used update_site_option() to update network option '_xpro_cloud_templats'
                     */
                    update_site_option('_xpro_cloud_templats', $templates, true);
                }
            } else {
                $ajaxresult['status'] = 'failed';
            }

            // Result.
            wp_send_json($ajaxresult);

        }

        /**
         * Fetch cloud templates
         *
         * @since 1.0.0
         */
        public function fetch_cloud_templates()
        {

            check_ajax_referer('xpro_cloud_nonce', 'form_nonce');
            self::refresh_cloud_templates();
            $ajaxresult['status'] = 'success';

            // Result.
            wp_send_json($ajaxresult);
        }

        /**
         * Function that renders dat file type
         *
         * @param file $dat_file_type gets the DAT file type.
         * @since 1.0.0
         */
        public function get_right_type_key($dat_file_type)
        {

            // Update the key.
            if ('module' === $dat_file_type) {
                $dat_file_type = 'presets';
            }
            if ('layout' === $dat_file_type) {
                $dat_file_type = 'page-templates';
            }
            if ('row' === $dat_file_type) {
                $dat_file_type = 'sections';
            }

            return $dat_file_type;
        }

        /**
         * Function that renders load filesystem
         *
         * @since 1.0.0
         */
        public static function load_filesystem()
        {
            if (null === self::$xpro_filesystem) {
                require_once ABSPATH . '/wp-admin/includes/class-wp-filesystem-base.php';
                require_once ABSPATH . '/wp-admin/includes/class-wp-filesystem-direct.php';
                self::$xpro_filesystem = new WP_Filesystem_Direct(array());
            }
        }

        /**
         * Download cloud templates
         *
         * @since 1.0.0
         */
        public function download_cloud_templates()
        {

            check_ajax_referer('xpro_cloud_nonce', 'form_nonce');
            // Check folder exist or not?
            $dir_info = $this->create_local_dir();

            // Get template details.
            $dat_file_url = $dir_info['url'] . basename($_POST['dat_file']);
            $remote_file = (isset($_POST['dat_file'])) ? esc_url_raw($_POST['dat_file']) : '';
            $local_file = trailingslashit($dir_info['path']) . basename($remote_file);
            $dat_file_id = (isset($_POST['dat_file_id'])) ? sanitize_text_field($_POST['dat_file_id']) : '';
            $dat_file_type = (isset($_POST['dat_file_type'])) ? $this->get_right_type_key(sanitize_text_field($_POST['dat_file_type'])) : '';
            $ajaxresult['id'] = $dat_file_id;
            $ajaxresult['type'] = $dat_file_type;
            $ajaxresult['dat_url_local'] = urlencode($local_file); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.urlencode_urlencode
            $timeout = 300;

            // Download file to /temp/ directory.
            $temp_file = download_url($remote_file, $timeout);

            if (!is_wp_error($temp_file)) {

                // Initialize file system.
                self::load_filesystem();

                // Copy remote .dat file.
                if (self::$xpro_filesystem->copy($temp_file, $local_file, true)) {

                    if (!empty($dat_file_id)) {

                        $templates = get_site_option('_xpro_cloud_templats', false);

                        if (!empty($dat_file_type)) {
                            foreach ($templates[$dat_file_type] as $key => $template) {
                                if ($dat_file_id === $templates[$dat_file_type][$key]['id']) {
                                    $templates[$dat_file_type][$key]['status'] = 'true';
                                    $templates[$dat_file_type][$key]['dat_url_local'] = $local_file;
                                }
                            }
                        }

                        /**
                         *  Here FLBuilderModel::update_admin_settings_option() not works!
                         *
                         * So, used update_site_option() to update network option '_xpro_cloud_templats'
                         */
                        update_site_option('_xpro_cloud_templats', $templates, true);

                        $ajaxresult['status'] = 'success';
                    }
                } else {

                    // Could not copy the file.
                    $ajaxresult['status'] = 'failed';
                }

                // Remove temporary file from /temp/ directory.
                wp_delete_file($temp_file);

                // Could not download .dat then show error message.
            } else {

                $ajaxresult['status'] = 'failed';
                $ajaxresult['msg'] = $temp_file->get_error_message();
            }

            // Result.
            wp_send_json($ajaxresult);
        }

        /**
         * Messages
         *
         * @param string $msg gets an string message.
         * @since 1.0.0
         */
        public static function message($msg)
        {
            if (!empty($msg)) {
                if ('not-found' === $msg) { ?>
                    <div class="xpro-cloud-templates-not-found">

                        <h3> <?php printf( /* translators: %s: search term */ esc_attr__('Welcome to %s Template Cloud!', 'xpro-bb-addons'), esc_attr('Xpro Addons For Beaver Builder')); ?> </h3>
                        <p> <?php printf( /* translators: %s: search term */ esc_attr__('%s Template Cloud would allow you to browse through our growing library of 150+ professionally designed templates and download the only ones that you need.', 'xpro-bb-addons'), esc_attr('Xpro Addons For Beaver Builder')); ?></p>
                        <button class="xpro-bb-btn-gradient" data-operation="fetch"><i
                                    class="dashicons dashicons-image-rotate"></i> <?php esc_html_e("Let's get started", 'xpro-bb-addons'); ?>
                            &rarr;
                        </button>

                    </div>
                    <?php
                }
            }
        }

        /**
         * Template Button
         *
         * @since 1.0.0
         */
        public function button_title()
        {
            ?>
            <span class="xpro-cloud-process" data-operation="fetch">
				<i class="dashicons dashicons-image-rotate"></i>
			</span>
            <?php
        }

        /**
         * Template HTML
         *
         * @param string $type gets the type page-templates.
         * @since 1.0.0
         */
        public static function template_html($type = 'page-templates')
        {

            if ('gallery' === $type) {

                $type = 'sections';

                $templates = self::get_cloud_templates($type);

                if (is_array($templates) && count($templates) > 0) {
                    ?>

                    <div class="xpro-templates-showcase-<?php echo esc_attr($type); ?>">

                        <div id="xpro-templates-<?php echo esc_attr($type); ?>"
                             class="xpro-templates-grid xpro-templates-<?php echo esc_attr($type); ?>">

                            <?php
                            foreach ($templates as $template_id => $single_post) {

                                $data['id'] = (isset($single_post['id'])) ? $single_post['id'] : '';
                                $data['name'] = (isset($single_post['name'])) ? $single_post['name'] : '';
                                $data['image'] = (isset($single_post['image'])) ? $single_post['image'] : '';
                                $data['type'] = (isset($single_post['type'])) ? $single_post['type'] : '';
                                $data['status'] = (isset($single_post['status'])) ? $single_post['status'] : '';
                                $data['count'] = (isset($single_post['count'])) ? $single_post['count'] : '';
                                $data['preview_url'] = (isset($single_post['preview_url'])) ? $single_post['preview_url'] : '';
                                $data['version'] = (isset($single_post['version'])) ? $single_post['version'] : '';
                                $data['category'] = (isset($single_post['category'])) ? $single_post['category'] : '';
                                $data['tags'] = (isset($single_post['tags'])) ? $single_post['tags'] : '';
                                $data['industry'] = (isset($single_post['industry'])) ? $single_post['industry'] : '';

                                $template_class = '';
                                $template_class .= ' ' . $data['tags'];
                                $template_class .= ' ' . $data['industry'];

                                if ('gallery' === $data['category']) {

                                    if ($data['version'] === 'pro') {

                                        $xpro_dat_url = 'https://bbdemos.wpxpro.com/sections/gallery/gallery-' . strtolower(preg_replace('/[[:space:]]+/', '-', $data['name'])) . '.dat';

                                    } elseif ($data['version'] === 'lite') {

                                        $xpro_dat_url = 'https://bbdemos.wpxpro.com/sections/gallery/gallery-lite-' . strtolower(preg_replace('/[[:space:]]+/', '-', $data['name'])) . '.dat';

                                    }

                                    ?>
                                    <div id="<?php echo esc_attr($data['id']); ?>"
                                         class="xpro-template-block xpro-single-<?php echo esc_attr($type); ?> <?php echo esc_attr($template_class); ?> <?php echo esc_attr($data['version']); ?>"
                                         data-is-downloaded="<?php echo esc_attr($data['status']); ?>">
                                        <div class="xpro-template">

                                            <figure class="xpro-template-screenshot lazy-load post__image"
                                                    data-template-name="<?php echo esc_attr($data['name']); ?>"
                                                    data-preview-url="<?php echo esc_url($data['preview_url']); ?>"
                                                    data-template-id='<?php echo esc_attr($data['id']); ?>'
                                                    data-template-type='<?php echo esc_attr($type); ?>'>
                                                <img data-src="<?php echo esc_url($data['image']); ?>" alt="">
                                            </figure>
                                            <div class="xpro-template-item-overlay">
												<span data-src-preview="<?php echo esc_attr($data['preview_url']); ?>"
                                                      class="xpro-template-item-preview">
													<i class="xi xi-eye"></i>
												</span>
                                            </div>

                                            <div class="xpro-template-info">
                                                <h2 class="xpro-template-name"> <?php echo esc_attr($data['name']); ?> </h2>
                                                <div class="xpro-template-actions">
                                                    <?php if ('true' === $data['status']) { ?>

                                                        <span class="xpro-cloud-process xpro-demo-item-preview" data-operation="remove">
                                                            <?php esc_html_e('Remove', 'xpro-bb-addons'); ?>
                                                            <input type="hidden" class="template-dat-meta-id"
                                                                   value='<?php echo esc_attr($data['id']); ?>'/>
                                                            <input type="hidden" class="template-dat-meta-type"
                                                                   value='<?php echo esc_attr($type); ?>'/>
                                                            <input type="hidden" class="template-dat-meta-dat_url_local"
                                                                   value='<?php echo $data['dat_url_local'];  //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>'/>
                                                        </span>

                                                        <?php
                                                    } else {
                                                        if ('pro' === $data['version']) { ?>
                                                        <span class="xpro-demo-item-preview"
                                                              data-popup-id="gallery-pro">
																<?php echo esc_html__('Download'); ?>
														</span>
                                                        <?php
                                                    } elseif ( did_action( 'xpro_gallery_for_bb_loaded' ) ) {
                                                        ?>
                                                        <span class="xpro-cloud-process xpro-demo-item-preview"
                                                              data-operation="download">
                                                            <?php echo esc_html__('Download'); ?>
                                                            <input type="hidden" class="template-dat-meta-id"
                                                                   value='<?php echo esc_attr($data['id']); ?>'/>
                                                            <input type="hidden" class="template-dat-meta-type"
                                                                   value='<?php echo esc_attr($type); ?>'/>
                                                            <input type="hidden"
                                                                   class="template-dat-meta-dat_url"
                                                                   value='<?php echo esc_url($xpro_dat_url); ?>'/>
                                                        </span>
                                                        <?php
                                                        } else {
                                                        ?>
                                                        <span class="xpro-demo-item-preview" data-popup-id="gallery-lite">
                                                            <?php echo esc_html__('Download'); ?>
                                                        </span>
                                                        <?php

                                                        }
                                                    } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                }
                            }
                            ?>
                        </div><!-- #xpro-templates-list -->

                    </div><!-- #xpro-templates -->

                    <?php

                    /**
                     * Debugging
                     */
                    if (isset($_GET['debug']) && isset($_REQUEST['xpro_setting_nonce']) && wp_verify_nonce($_REQUEST['xpro_setting_nonce'], 'xpro_setting_nonce')) {
                        if (count($templates) < 1) {
                            ?>
                            <h2> <?php esc_html_e('Templates are disabled from RestAPI.', 'xpro-bb-addons'); ?> </h2>
                            <?php
                        }
                    }
                } else {

                    // Message for no templates found.
                    self::message('not-found');
                }
            } elseif ('portfolio' === $type) {

                $type = 'sections';

                $templates = self::get_cloud_templates($type);

                if (is_array($templates) && count($templates) > 0) {
                    ?>

                    <div class="xpro-templates-showcase-<?php echo esc_attr($type); ?>">

                        <div id="xpro-templates-<?php echo esc_attr($type); ?>"
                             class="xpro-templates-grid xpro-templates-<?php echo esc_attr($type); ?>">

                            <?php
                            foreach ($templates as $template_id => $single_post) {

                                $data['id'] = (isset($single_post['id'])) ? $single_post['id'] : '';
                                $data['name'] = (isset($single_post['name'])) ? $single_post['name'] : '';
                                $data['image'] = (isset($single_post['image'])) ? $single_post['image'] : '';
                                $data['type'] = (isset($single_post['type'])) ? $single_post['type'] : '';
                                $data['status'] = (isset($single_post['status'])) ? $single_post['status'] : '';
                                $data['count'] = (isset($single_post['count'])) ? $single_post['count'] : '';
                                $data['preview_url'] = (isset($single_post['preview_url'])) ? $single_post['preview_url'] : '';
                                $data['version'] = (isset($single_post['version'])) ? $single_post['version'] : '';
                                $data['category'] = (isset($single_post['category'])) ? $single_post['category'] : '';
                                $data['tags'] = (isset($single_post['tags'])) ? $single_post['tags'] : '';
                                $data['industry'] = (isset($single_post['industry'])) ? $single_post['industry'] : '';

                                $template_class = '';
                                $template_class .= ' ' . $data['tags'];
                                $template_class .= ' ' . $data['industry'];

                                if ('portfolio' === $data['category']) {

                                    if ($data['version'] === 'pro') {

                                        $xpro_dat_url = 'https://bbdemos.wpxpro.com/sections/portfolio/portfolio-' . strtolower(preg_replace('/[[:space:]]+/', '-', $data['name'])) . '.dat';

                                    } elseif ($data['version'] === 'lite') {

                                        $xpro_dat_url = 'https://bbdemos.wpxpro.com/sections/portfolio/portfolio-lite-' . strtolower(preg_replace('/[[:space:]]+/', '-', $data['name'])) . '.dat';

                                    }

                                    ?>
                                    <div id="<?php echo esc_attr($data['id']); ?>"
                                         class="xpro-template-block xpro-single-<?php echo esc_attr($type); ?> <?php echo esc_attr($template_class); ?> <?php echo esc_attr($data['version']); ?>"
                                         data-is-downloaded="<?php echo esc_attr($data['status']); ?>">
                                        <div class="xpro-template">

                                            <figure class="xpro-template-screenshot lazy-load post__image"
                                                    data-template-name="<?php echo esc_attr($data['name']); ?>"
                                                    data-preview-url="<?php echo esc_url($data['preview_url']); ?>"
                                                    data-template-id='<?php echo esc_attr($data['id']); ?>'
                                                    data-template-type='<?php echo esc_attr($type); ?>'>
                                                <img data-src="<?php echo esc_url($data['image']); ?>" alt="">
                                            </figure>
                                            <div class="xpro-template-item-overlay">
												<span data-src-preview="<?php echo esc_attr($data['preview_url']); ?>"
                                                      class="xpro-template-item-preview">
													<i class="xi xi-eye"></i>
												</span>
                                            </div>

                                            <div class="xpro-template-info">
                                                <h2 class="xpro-template-name"> <?php echo esc_attr($data['name']); ?> </h2>
                                                <div class="xpro-template-actions">
                                                    <?php if ('true' === $data['status']) { ?>
                                                        <span class="xpro-cloud-process xpro-demo-item-preview" data-operation="remove">
                                                            <?php esc_html_e('Remove', 'xpro-bb-addons'); ?>
                                                            <input type="hidden" class="template-dat-meta-id"
                                                                   value='<?php echo esc_attr($data['id']); ?>'/>
                                                            <input type="hidden" class="template-dat-meta-type"
                                                                   value='<?php echo esc_attr($type); ?>'/>
                                                            <input type="hidden" class="template-dat-meta-dat_url_local"
                                                                   value='<?php echo $data['dat_url_local'];  //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>'/>
                                                        </span>
                                                        <?php
                                                    } else {
                                                        if ('pro' === $data['version']) { ?>
                                                            <span class="xpro-demo-item-preview"
                                                                  data-popup-id="portfolio-pro">
																<?php echo esc_html__('Download'); ?>
														</span>
                                                            <?php
                                                        } elseif ( did_action( 'xpro_portfolio_for_bb_loaded' ) ) {
                                                            ?>
                                                            <span class="xpro-cloud-process xpro-demo-item-preview"
                                                                  data-operation="download">
                                                                <?php echo esc_html__('Download'); ?>
                                                                <input type="hidden" class="template-dat-meta-id"
                                                                       value='<?php echo esc_attr($data['id']); ?>'/>
                                                                <input type="hidden" class="template-dat-meta-type"
                                                                       value='<?php echo esc_attr($type); ?>'/>
                                                                <input type="hidden"
                                                                       class="template-dat-meta-dat_url"
                                                                       value='<?php echo esc_url($xpro_dat_url); ?>'/>
                                                            </span>
                                                            <?php
                                                        }
                                                        else {
                                                            ?>
                                                            <span class="xpro-demo-item-preview" data-popup-id="portfolio-lite">
                                                                <?php echo esc_html__('Download'); ?>
                                                            </span>
                                                            <?php

                                                        }
                                                    } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                        </div><!-- #xpro-templates-list -->

                    </div><!-- #xpro-templates -->

                    <?php

                    /**
                     * Debugging
                     */
                    if (isset($_GET['debug']) && isset($_REQUEST['xpro_setting_nonce']) && wp_verify_nonce($_REQUEST['xpro_setting_nonce'], 'xpro_setting_nonce')) {
                        if (count($templates) < 1) {
                            ?>
                            <h2> <?php esc_html_e('Templates are disabled from RestAPI.', 'xpro-bb-addons'); ?> </h2>
                            <?php
                        }
                    }
                } else {

                    // Message for no templates found.
                    self::message('not-found');
                }
            } else {
                $templates = self::get_cloud_templates($type);

                if (is_array($templates) && count($templates) > 0) {
                    ?>

                    <div class="xpro-templates-showcase-<?php echo esc_attr($type); ?>">

                        <div id="xpro-templates-<?php echo esc_attr($type); ?>"
                             class="xpro-templates-grid xpro-templates-<?php echo esc_attr($type); ?>">

                            <?php
                            foreach ($templates as $template_id => $single_post) {

                                $data['id'] = (isset($single_post['id'])) ? $single_post['id'] : '';
                                $data['name'] = (isset($single_post['name'])) ? $single_post['name'] : '';
                                $data['image'] = (isset($single_post['image'])) ? $single_post['image'] : '';
                                $data['type'] = (isset($single_post['type'])) ? $single_post['type'] : '';
                                $data['status'] = (isset($single_post['status'])) ? $single_post['status'] : '';
                                $data['count'] = (isset($single_post['count'])) ? $single_post['count'] : '';
                                $data['preview_url'] = (isset($single_post['preview_url'])) ? $single_post['preview_url'] : '';
                                $data['version'] = (isset($single_post['version'])) ? $single_post['version'] : '';
                                $data['category'] = (isset($single_post['category'])) ? $single_post['category'] : '';
                                $data['tags'] = (isset($single_post['tags'])) ? $single_post['tags'] : '';
                                $data['industry'] = (isset($single_post['industry'])) ? $single_post['industry'] : '';

                                $template_class = '';
                                $template_class .= ' ' . $data['tags'];
                                $template_class .= ' ' . $data['industry'];

                                if ('gallery' !== $data['category'] && 'portfolio' !== $data['category']) {

                                    $xpro_dat_url = '';

                                    if ($type == 'page-templates' && $data['version'] === 'pro') {

                                        $xpro_dat_url = 'https://bbdemos.wpxpro.com/page-templates/template-' . strtolower(preg_replace('/[[:space:]]+/', '-', $data['name'])) . '.dat';

                                    } elseif ($type == 'page-templates' && $data['version'] === 'lite') {

                                        $xpro_dat_url = 'https://bbdemos.wpxpro.com/page-templates/template-lite-' . strtolower(preg_replace('/[[:space:]]+/', '-', $data['name'])) . '.dat';

                                    } elseif ('sections' && $data['version'] === 'pro') {

                                        $xpro_dat_url = 'https://bbdemos.wpxpro.com/sections/section-' . strtolower(preg_replace('/[[:space:]]+/', '-', $data['name'])) . '.dat';

                                    } elseif ('sections' && $data['version'] === 'lite') {
                                        $xpro_dat_url = 'https://bbdemos.wpxpro.com/sections/section-lite-' . strtolower(preg_replace('/[[:space:]]+/', '-', $data['name'])) . '.dat';

                                    }

                                    ?>
                                    <div id="<?php echo esc_attr($data['id']); ?>"
                                         class="xpro-template-block xpro-single-<?php echo esc_attr($type); ?> <?php echo esc_attr($template_class); ?> <?php echo esc_attr($data['version']); ?>"
                                         data-is-downloaded="<?php echo esc_attr($data['status']); ?>">
                                        <div class="xpro-template">

                                            <figure class="xpro-template-screenshot lazy-load post__image"
                                                    data-template-name="<?php echo esc_attr($data['name']); ?>"
                                                    data-preview-url="<?php echo esc_url($data['preview_url']); ?>"
                                                    data-template-id='<?php echo esc_attr($data['id']); ?>'
                                                    data-template-type='<?php echo esc_attr($type); ?>'>
                                                <img data-src="<?php echo esc_url($data['image']); ?>" alt="">
                                            </figure>
                                            <div class="xpro-template-item-overlay">
												<span data-src-preview="<?php echo esc_attr($data['preview_url']); ?>"
                                                      class="xpro-template-item-preview">
													<i class="xi xi-eye"></i>
												</span>
                                            </div>

                                            <div class="xpro-template-info">
                                                <h2 class="xpro-template-name"> <?php echo esc_attr($data['name']); ?> </h2>
                                                <div class="xpro-template-actions">
                                                    <?php if ('true' === $data['status']) { ?>
                                                        <span class="xpro-cloud-process xpro-demo-item-preview" data-operation="remove">
                                                                <?php esc_html_e('Remove', 'xpro-bb-addons'); ?>
                                                                <input type="hidden" class="template-dat-meta-id"
                                                                       value='<?php echo esc_attr($data['id']); ?>'/>
                                                                <input type="hidden" class="template-dat-meta-type"
                                                                       value='<?php echo esc_attr($type); ?>'/>
                                                                <input type="hidden" class="template-dat-meta-dat_url_local"
                                                                       value='<?php echo $data['dat_url_local'];  //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>'/>
                                                            </span>
                                                        <?php
                                                    } else {
                                                        if ('pro' === $data['version']) { ?>
                                                            <span class="xpro-demo-item-preview"
                                                                  data-popup-id="addons-pro">
																<?php echo esc_html__('Download'); ?>
														</span>
                                                            <?php
                                                        } elseif ( did_action( 'xpro_addons_for_bb_loaded' ) ) {
                                                            ?>
                                                            <span class="xpro-cloud-process xpro-demo-item-preview"
                                                                  data-operation="download">
                                                                <?php echo esc_html__('Download'); ?>
                                                                <input type="hidden" class="template-dat-meta-id"
                                                                       value='<?php echo esc_attr($data['id']); ?>'/>
                                                                <input type="hidden" class="template-dat-meta-type"
                                                                       value='<?php echo esc_attr($type); ?>'/>
                                                                <input type="hidden"
                                                                       class="template-dat-meta-dat_url"
                                                                       value='<?php echo esc_url($xpro_dat_url); ?>'/>
                                                            </span>
                                                            <?php
                                                        }
                                                        else {
                                                            ?>
                                                            <span class="xpro-demo-item-preview" data-popup-id="xpro-themes">
                                                                <?php echo esc_html__('Download'); ?>
                                                            </span>
                                                            <?php

                                                        }
                                                    } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                }
                            }
                            ?>

                        </div><!-- #xpro-templates-list -->

                    </div><!-- #xpro-templates -->

                    <?php

                    /**
                     * Debugging
                     */
                    if (isset($_GET['debug']) && isset($_REQUEST['xpro_setting_nonce']) && wp_verify_nonce($_REQUEST['xpro_setting_nonce'], 'xpro_setting_nonce')) {
                        if (count($templates) < 1) {
                            ?>
                            <h2> <?php esc_html_e('Templates are disabled from RestAPI.', 'xpro-bb-addons'); ?> </h2>
                            <?php
                        }
                    }
                } else {

                    // Message for no templates found.
                    self::message('not-found');
                }
            }

        }

        /**
         * Create local directory if not exist.
         *
         * @param string $dir_name verifies the dir name with xpro-addons-for-beaver-builder.
         * @since 1.0.0
         */
        public function create_local_dir($dir_name = 'xpro-addons-for-beaver-builder')
        {

            $wp_info = wp_upload_dir();

            if (function_exists('FLBuilderModel')) {
                // SSL workaround.
                if (FLBuilderModel::is_ssl()) {
                    $wp_info['baseurl'] = str_ireplace('http://', 'https://', $wp_info['baseurl']);
                }
            }

            // Build the paths.
            $dir_info = array(
                'path' => $wp_info['basedir'] . '/' . $dir_name . '/',
                'url' => $wp_info['baseurl'] . '/' . $dir_name . '/',
            );

            // Create the upload dir if it doesn't exist.
            if (!fl_builder_filesystem()->file_exists($dir_info['path'])) {

                // Create the directory.
                fl_builder_filesystem()->mkdir($dir_info['path']);

                // Add an index file for security.
                fl_builder_filesystem()->file_put_contents($dir_info['path'] . 'index.html', '');
            }

            return $dir_info;
        }

    }
}

/**
 *  Kicking this off by calling 'get_instance()' method
 */
$xpro_cloud_templates = XPRO_Cloud_Templates::get_instance();
