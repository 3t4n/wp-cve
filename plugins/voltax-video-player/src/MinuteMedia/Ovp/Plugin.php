<?php

namespace MinuteMedia\Ovp;

use MinuteMedia\Ovp\Constants;

/**
 * Class Plugin
 * @package MinuteMedia\Ovp
 */
class Plugin
{

    /**
     * @var Plugin
     */
    protected static $_instance;

    /**
     * @var Sdk
     */
    protected $sdk;

    /**
     * @var bool
     */
    protected $isAuthenticated = false;

    /**
     * @var bool
     */
    protected $reAuthenticate = false;

    /**
     * @var bool
     */
    protected $isAuthenticatedPlayers = false;

    /**
     * @var bool
     */
    protected $reAuthenticatePlayers = false;

    /**
     * Plugin constructor.
     */
    protected function __construct()
    {
        $this->initLanguages();
        $this->pluginActivate();
        $this->setupMediaButtons();
        $this->initSdk();
        $this->registerSettings();
        $this->setupAdminMenu();
        $this->setupModal();
        $this->enqueueCssJs();
        $this->registerShortcodes();
        $this->registerAmp();
        if (!$this->isAuthenticated || !$this->isAuthenticatedPlayers) {
            return;
        }
        $this->registerPluginActions();
        $this->registerUploadAction();
        $this->initTranslations();
        $this->initFeaturedVideo();
    }

    /**
     * @return Plugin
     */
    public static function init()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Returns the top level directory of the plugin
     * @return false|string
     */
    public static function baseDir()
    {
        $dir = realpath(__DIR__ . "/../../../");
        return $dir;
    }

    public static function baseUrl()
    {
        $url = rtrim(WP_MM_VIDEOS_PLUGIN_URL, '/');
        return $url;
    }


    /**
     * Load all translations for our plugin from the MO file.
     */
    protected function initLanguages()
    {
        add_action('init', function () {
            load_plugin_textdomain('minute-media', false, self::baseDir() . '/languages');
        });
    }

    /**
     * Show message upon activation
     */
    protected function pluginActivate()
    {
        add_action('admin_notices', function () {
            if (Constants::PLUGIN_VERSION != get_option(Constants::OPT_PLUGIN_VERSION)) {
                add_option(Constants::OPT_PLUGIN_VERSION, Constants::PLUGIN_VERSION);
                $adminUrl = admin_url('options-general.php?page=mm-video');
                $this->emitNotice(esc_html('The Voltax Video plugin can be ') . '<a href="' . esc_url($adminUrl) . '">' . esc_html("configured here") . '</a>.',
                    'success');
            }
        });
    }

    /**
     * Fires on deactivation. Deletes the plugin version option
     */
    public static function pluginDeactivate()
    {
        // Display an error message if the option isn't properly deleted.
        if (false === delete_option(Constants::OPT_PLUGIN_VERSION)) {
            $html = '<div class="error">';
            $html .= '<p>';
            $html .= esc_html(__('There was a problem deactivating the Minute Media Videos plugin. Please try again.'),
                Constants::PLUGIN_PACKAGE_NAME);
            $html .= '</p>';
            $html .= '</div><!-- /.updated -->';
            echo $html;
        }
    }


    /**
     * Include Voltax Video buttons
     */
    protected function setupMediaButtons()
    {
        add_action('media_buttons', function ($editor_id) {
            if ($editor_id == 'content') {
                echo '<button data-target="content" id="mm-video-editor" class="button"> ' . esc_html("Add Voltax Video") . '</button>';
                echo '<button data-target="content" id="mm-playlist-editor" class="button">' . esc_html("Add Voltax Playlist") . '</button>';
            }
        });

    }

    /**
     * Include VMS Modal
     */
    protected function setupModal()
    {
        add_action('admin_footer', function () {
            echo '<div id="mm-voltax-vms-modal"></div>';
        });
    }

    protected function registerShortcodes()
    {
        add_shortcode('mm-video', function ($attributes, $content, $shortcode_tag) {
            $attributes = shortcode_atts(array(
                'id' => '',
                'playlist_id' => '',
                'player_id' => '',
                'type' => '',
                'image' => '',
            ), $attributes, $shortcode_tag);
            return Player::getEmbedCode($attributes['id'], $attributes['playlist_id'], $attributes['player_id'], $attributes['image']);
        });

        add_action('register_shortcode_ui',
            /**
             * Shortcode UI setup for the mm-video shortcode.
             *
             * It is called when the Shortcake action hook `register_shortcode_ui` is called, so if
             * shortcake is not installed, it's just dormant code
             */
            function () {
                /*
                 * Define the UI for attributes of the shortcode.
                 *
                 * Each array must include 'attr', 'type', and 'label'.
                 * * 'attr' should be the name of the attribute.
                 * * 'type' options include: text, checkbox, textarea, radio, select, email,
                 *     url, number, and date, post_select, attachment, color.
                 * * 'label' is the label text associated with that input field.
                 *
                 * Use 'meta' to add arbitrary attributes to the HTML of the field.
                 *
                 * Use 'encode' to encode attribute data. Requires customization in shortcode callback to decode.
                 *
                 * Depending on 'type', additional arguments may be available.
                 */
                $fields = array(
                    array(
                        'label' => esc_html__('Content ID', 'mm-video', 'shortcode-ui'),
                        'attr' => 'id',
                        'type' => 'text',
                        'encode' => true,
                        'meta' => array(
                            'placeholder' => esc_html__('x-xxxxxxxxxxxxx', 'mm-video', 'shortcode-ui'),
                        ),
                    ),
                    array(
                        'label' => esc_html__('Preview Image', 'mm-video', 'shortcode-ui'),
                        'attr' => 'image',
                        'type' => 'text',
                        'encode' => true,
                        'meta' => array(
                            'placeholder' => esc_html__('cover.jpg', 'mm-video', 'shortcode-ui'),
                        ),
                    ),
                    array(
                        'label' => esc_html__('Content Type', 'mm-video', 'shortcode-ui'),
                        'description' => esc_html__('Type of content to embed', 'mm-video', 'shortcode-ui'),
                        'attr' => 'type',
                        'type' => 'select',
                        'options' => array(
                            array('value' => 'video', 'label' => esc_html__('Video', 'mm-video', 'shortcode-ui')),
                            array('value' => 'playlist', 'label' => esc_html__('Playlist', 'mm-video', 'shortcode-ui')),
                        ),
                    )
                );

                /*
                 * Define the Shortcode UI arguments.
                 */
                $shortcode_ui_args = array(
                    /*
                     * How the shortcode should be labeled in the UI. Required argument.
                     */
                    'label' => esc_html__('Voltax Video', 'mm-video', 'shortcode-ui'),


                    /*
                     * Icon that is displayed in the shortcode menu
                     */
                    'listItemImage' => 'dashicons-video-alt3',

                    /*
                     * Define the UI for attributes of the shortcode. Optional.
                     *
                     * See above, to where the the assignment to the $fields variable was made.
                     */
                    'attrs' => $fields,
                );

                shortcode_ui_register_for_shortcode('mm-video', $shortcode_ui_args);
            });
    }

    /**
     * Registers AMP sanitizer to ensure compatibility with the WP AMP plugin
     */
    protected function registerAmp()
    {
        \add_filter('amp_content_sanitizers', function($sanitizerClasses, $post) {
            $sanitizerClasses = array_reverse($sanitizerClasses, true);
            $sanitizerClasses['MinuteMedia\\Ovp\\AmpSanitizer'] = [];
            $sanitizerClasses= array_reverse($sanitizerClasses, true);
            return $sanitizerClasses;
        }, 10, 2);

        \add_action('wp_enqueue_scripts', function () {
            if (!function_exists('amp_is_request')) {
                return;
            }
            if (!amp_is_request()) {
                return;
            }
            wp_register_style('mm-amp-styles', false);
            wp_enqueue_style('mm-amp-styles');
            wp_add_inline_style('mm-amp-styles', 'html[amp] amp-minute-media-player>button {background-color:transparent;}');
        });
    }

    protected static function getClientId()
    {
        return defined('MM_OPT_CLIENT_ID') ? MM_OPT_CLIENT_ID : get_option(Constants::OPT_CLIENT_ID, false);
    }

    protected static function getClientSecret()
    {
        return defined('MM_OPT_CLIENT_SECRET') ? MM_OPT_CLIENT_SECRET : get_option(Constants::OPT_CLIENT_SECRET, false);
    }

    protected static function getPropertyId()
    {
        return defined('MM_OPT_PROPERTY_ID') ? MM_OPT_PROPERTY_ID : get_option(Constants::OPT_PROPERTY_ID, false);
    }

    protected static function getTenantId()
    {
        return defined('MM_OPT_TENANT_ID') ? MM_OPT_TENANT_ID : get_option(Constants::OPT_TENANT_ID, '');
    }

    public static function getPlayerId()
    {
        return defined('MM_OPT_PLAYER_ID') ? MM_OPT_PLAYER_ID : get_option(Constants::OPT_PLAYER_ID, false);
    }

    /**
     * @return int
     */
    public static function getEnableFeaturedVideo()
    {
        $enabled = defined('MM_OPT_ENABLE_FEATURED_VIDEO') ? MM_OPT_ENABLE_FEATURED_VIDEO : get_option(Constants::OPT_ENABLE_FEATURED_VIDEO,
            0);
        return empty($enabled) ? 0 : 1;
    }

    /**
     * @return int
     */
    public static function getEnableVideoUpload()
    {
        $enabled = defined('MM_OPT_ENABLE_VIDEO_UPLOAD') ? MM_OPT_ENABLE_VIDEO_UPLOAD : get_option(Constants::OPT_ENABLE_VIDEO_UPLOAD,
            0);
        return empty($enabled) ? 0 : 1;
    }

    /**
     * Returns stored access token, if still valid, otherwise tries to authenticate and return a new one
     * @return bool|string
     */
    public function getAccessToken()
    {
        $accessToken = false;
        $clientId = self::getClientId();
        $clientSecret = self::getClientSecret();
        $propertyId = self::getPropertyId();
        $playerId = self::getPlayerId();
        $tenantId = self::getTenantId();

        if ($clientId && $clientSecret && $propertyId && $playerId) {
            $accessToken = get_transient(Constants::OPT_ACCESS_TOKEN);
            $this->sdk = new Sdk($clientId, $clientSecret, $tenantId, $propertyId);
            if (!$accessToken || $this->reAuthenticate) { // Create new token if expired or not created
                $accessToken = $this->sdk->getAccessToken();
                set_transient(Constants::OPT_ACCESS_TOKEN, $accessToken, Constants::MM_ACCESS_TOKEN_EXPIRATION);
            }
            if (!empty($accessToken)) {
                $this->isAuthenticated = true;
                $this->reAuthenticate = false;
                $this->sdk->setAccessToken($accessToken);
            }
        }

        return $accessToken;
    }

    /**
     * Returns stored players access token, if still valid, otherwise tries to authenticate and return a new one
     * @return bool|string
     */
    public function getPlayersAccessToken()
    {
        $playersAccessToken = get_transient(Constants::OPT_ACCESS_TOKEN_PLAYERS);

        if (!$playersAccessToken || $this->reAuthenticatePlayers) { // Create new token if expired or not created
            $playersAccessToken = $this->sdk->getPlayersAccessToken();
            set_transient(Constants::OPT_ACCESS_TOKEN_PLAYERS, $playersAccessToken, Constants::MM_ACCESS_TOKEN_EXPIRATION);
        }

        if (!empty($playersAccessToken)) {
            $this->isAuthenticatedPlayers = true;
            $this->reAuthenticatePlayers = false;
            $this->sdk->setPlayersAccessToken($playersAccessToken);
        }

        return $playersAccessToken;
    }

    /**
     * Initialize and authenticate Voltax OVP SDK
     */
    protected function initSdk()
    {
        $accessToken = $this->getAccessToken();

        if ($accessToken) {
            $this->getPlayersAccessToken();
        }
    }

    /**
     * Returns an array of all options to be registered with wordpress
     * @return array
     */
    public static function getPluginOptions()
    {
        return [
            Constants::OPT_PROPERTY_ID,
            Constants::OPT_TENANT_ID,
            Constants::OPT_CLIENT_ID,
            Constants::OPT_CLIENT_SECRET,
            Constants::OPT_PLAYER_ID,
            Constants::OPT_ENABLE_FEATURED_VIDEO,
            Constants::OPT_ENABLE_VIDEO_UPLOAD,
        ];
    }


    protected function registerSettings()
    {
        add_action('admin_init', function () {
            $options = self::getPluginOptions();
            foreach ($options as $opt) {
                register_setting(Constants::PLUGIN_PREFIX, $opt);
            }
        });

        // When an auth-dependent option is changed, refresh the access token and players access token
        add_action('updated_option', function ($optionName, $oldValue, $newValue) {
            $triggeringOptions = [
                Constants::OPT_CLIENT_ID,
                Constants::OPT_CLIENT_SECRET,
                Constants::OPT_TENANT_ID,
                Constants::OPT_PROPERTY_ID,
                Constants::MM_DEFAULT_PLAYER_ID
            ];
            if (in_array($optionName, $triggeringOptions) && $oldValue !== $newValue) {
                delete_transient(Constants::OPT_ACCESS_TOKEN);
                delete_transient(Constants::OPT_ACCESS_TOKEN_PLAYERS);
                $this->reAuthenticate = true;
                $this->reAuthenticatePlayers = true;
            }

        }, 10, 3);
    }

    protected function setupAdminMenu()
    {
        add_action('admin_menu', function () {
            add_submenu_page('options-general.php', 'Voltax Video Settings', 'Voltax Video', 'manage_options',
                'mm-video', function () {
                    $accessToken = $this->getAccessToken();
                    $clientId = self::getClientId();
                    $clientSecret = self::getClientSecret();
                    $propertyId = self::getPropertyId();
                    $playerId = self::getPlayerId();
                    $tenantId = self::getTenantId();
                    $enableFeaturedVideo = self::getEnableFeaturedVideo();
                    $enableVideoUpload = self::getEnableVideoUpload();

                    if (!($clientId && $clientSecret && $propertyId && $playerId)) {
                        $this->emitNotice(esc_html("A required setting is missing, please check ") . "<a href='" .
                            esc_url(admin_url('options-general.php?page=mm-video')) . "'>" . esc_html("Voltax Video settings") . "</a>", 'error');
                    }
                    if (!$accessToken) {
                        $this->emitNotice(esc_html('Please check your client ID and client secret.'), 'error');
                    }
                    ?>
                        <div class="wrap">
                            <h1><?php echo esc_html("Voltax Video Settings") ?></h1>
                            <?php
                            include(self::baseDir() . "/views/partials-admin-form-settings.php");
                            ?>
                        </div>

                    <?php

                });
        });
    }

    /**
     * Outputs a formatted notice to the page
     * @param $msg the message to emit (will be translated via the __() function)
     * @param string $type message type: error, warning, success, info (default)
     * @param bool $isDismissible if TRUE, shows a closing icon (default FALSE)
     */
    protected function emitNotice($msg, $type = null, $isDismissible = false)
    {
        if (!in_array($type, ['info', 'success', 'warning', 'error'])) {
            $type = "info";
        }
        $dismissible = ($isDismissible) ? " is-dismissible" : "";
        $classes = "notice-" . $type . $dismissible;
        printf('<div class="notice %s">%s</div>', esc_attr($classes), __($msg, Constants::PLUGIN_PACKAGE_NAME));
    }

    /**
     * Tell WP to add admin scripts and styles
     */
    protected function enqueueCssJs()
    {
        add_action('wp_enqueue_scripts', function () {
            $pluginDir = self::baseUrl();
            wp_enqueue_style('mm-styles', $pluginDir . '/styles/style.css', array(), Constants::PLUGIN_VERSION, 'all');
            wp_enqueue_script('mm-front-scripts', $pluginDir . '/scripts/front.js', array(), Constants::PLUGIN_VERSION,
                true);
            wp_enqueue_script('mm-video-data', $pluginDir . '/scripts/mm-video.js', array(), Constants::PLUGIN_VERSION );

            wp_localize_script('mm-video-data', 'mm_video_data',
                array(
                    'playerId' => self::getPlayerId(),
                    'endpointUrl' => Constants::ENDPOINT_EMBED,
                    'enableVideoUpload' => self::getEnableVideoUpload(),
                )
            );

            wp_localize_script('mm-video-data', 'mm_plugin_data', [
                'pluginDir' => self::baseUrl()
            ]);
        });
        
        add_action('admin_enqueue_scripts', function ($hook) {
            $pluginDir = self::baseUrl();
            wp_enqueue_style('mm-styles', $pluginDir . '/styles/style.css', array(), Constants::PLUGIN_VERSION, 'all');
            wp_enqueue_style('mm-material-icons', 'https://fonts.googleapis.com/icon?family=Material+Icons', array(),
            false);
            
            if ($hook === 'post-new.php' || $hook === 'post.php') {
                $dirJS = scandir(dirname(__FILE__) . '/../../../build/static/js');
                foreach ($dirJS as $file) {
                    if (pathinfo($file, PATHINFO_EXTENSION) === 'js') {      
                        $js_name = $file;
                    }
                }
    
                $dirCSS = scandir(dirname(__FILE__) . '/../../../build/static/css');
                foreach ($dirCSS as $file) {
                    if (pathinfo($file, PATHINFO_EXTENSION) === 'css') {
                        $css_name = $file;
                    }
                }
                
                wp_enqueue_script('mm-video-vms-modal_js', $pluginDir . '/build/static/js/' . $js_name, '', Constants::PLUGIN_VERSION, true);
                wp_localize_script('mm-video-vms-modal_js', 'ajax_object', ['ajax_url' => admin_url('admin-ajax.php')]);
                wp_enqueue_style('mm-video-vms-modal_css', $pluginDir . '/build/static/css/' . $css_name);
            }

            wp_enqueue_script('mm-video-data', $pluginDir . '/scripts/mm-video.js', array(), Constants::PLUGIN_VERSION );

            wp_localize_script('mm-video-data', 'mm_video_data',
                array(
                    'playerId' => self::getPlayerId(),
                    'endpointUrl' => Constants::ENDPOINT_EMBED,
                    'enableVideoUpload' => self::getEnableVideoUpload(),
                )
            );

            wp_localize_script('mm-video-data', 'mm_plugin_data', [
                'pluginDir' => self::baseUrl()
            ]);
        });
    }

    public function registerUploadAction()
    {
        $sdk = $this->sdk;

        add_action('wp_ajax_mm_upload_video', function () use ($sdk) {
            // Required
            $title = sanitize_text_field($_POST['title']);
            $content_length = (int)sanitize_text_field($_POST['content_length']);
            $content_md5 = sanitize_text_field($_POST['content_md5']);
            $file_extension = sanitize_text_field($_POST['file_extension']);

            // Optional
            $description = !empty($_POST['description']) ? sanitize_text_field($_POST['description']) : '';
            $tags = !empty($_POST['tags']) ? sanitize_text_field($_POST['tags']) : '';
            $category = !empty($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
            $video_provider = self::getPropertyId();
            $creator = !empty($_POST['creator']) ? sanitize_text_field($_POST['creator']) : '';
            $property = self::getPropertyId();
            $opt_out_publish_external = !empty($_POST['opt_out_publish_external']) && $_POST['opt_out_publish_external'] == 'on';

            $custom_params = (object)[];
            if (!empty($_POST['custom_params'])) {
                $custom_params = [];
                $customParams = json_decode(stripslashes($_POST['custom_params']));

                foreach ($customParams as $key => $value) {
                    $custom_params[sanitize_text_field($key)] = sanitize_text_field($value);
                }
            } 

            $tags = array_map(
                function($tag) { return trim($tag); },
                explode(',', $tags)
            );

            if (!empty($category)) {
                $tags[] = $category;
            }

            $results = $sdk->uploadVideo(
                $title,
                $description,
                $tags,
                $video_provider,
                $creator,
                $property,
                $custom_params,
                $content_length,
                $content_md5,
                $file_extension,
                $opt_out_publish_external
            );

            if (
                !is_array($results) ||
                !isset($results['data']) ||
                !is_array($results['data'])
            ) {
                $message = 'Unable to upload video';
                \wpp_log(
                    ['error' => $message, 'results' => $results],
                    $print_line = true
                );
                $results = [
                    'data' => [
                        'error' => $message,
                    ]
                ];
            }
            if (!isset($results['data']['error'])) {
                echo \wp_json_encode($results);
                wp_die();
            }
        });

        add_action('wp_ajax_mm_iab_categories', function () use ($sdk) {
            $cached = wp_cache_get('mm_iab_categories');
            if ($cached) {
                return $cached;
            }

            $results = $sdk->getIabCategories();

            if (
                !is_array($results) ||
                !isset($results['data']) ||
                !is_array($results['data'])
            ) {
                $message = 'Unable to retrieve IAB categories';
                \wpp_log(
                    ['error' => $message, 'results' => $results],
                    $print_line = true
                );
                $results = [
                    'data' => [
                        'error' => $message,
                    ]
                ];
            }
            if (!isset($results['data']['error'])) {
                wp_cache_set('mm_iab_categories', $results, '', Constants::MM_IAB_CATEGORIES_EXPIRATION);

                echo \wp_json_encode($results);
                wp_die();
            }
        });
    }

    /**
     * Registers handlers for the following request actions:
     *   - wp_ajax_{$action}:    get_mm_data
     *   - admin_post_{$action}: mm_videos_authenticate
     */
    public function registerPluginActions()
    {
        $sdk = $this->sdk;
        add_action('wp_ajax_get_mm_data', function () use ($sdk) {
            $query = !empty($_GET['query']) ? sanitize_text_field($_GET['query']) : '';
            $type = !empty($_GET['type']) ? sanitize_text_field($_GET['type']) : '';

            $start = microtime(true);

            switch ($type) {
                case 'playlist':
                    $results = $sdk->getPlaylists($query);
                    break;

                case 'player':
                    $results = $sdk->getPlayers();
                    break;

                case 'video':
                default:
                    $results = $sdk->getVideos($query, 30);
                    break;
            }
            $endpoint = Constants::ENDPOINT_IMAGES;

            // Normalize SDK results for processing. Allows us to assume
            // that we have a response in the form of:
            //
            //     $results === [ 'data' => [ ... ] ]
            //
            if (
                !is_array($results) ||
                !isset($results['data']) ||
                !is_array($results['data'])
            ) {
                $message = esc_html('Unable to parse search results');
                \wpp_log(
                    ['error' => $message, 'results' => $results],
                    $print_line = true
                );
                $results = [
                    'data' => [
                        'error' => $message,
                    ]
                ];
            }
            if (!isset($results['data']['error'])) {
                // Ensure that the preview image is included in response.
                $results['data'] = array_map(
                    function ($result) use ($endpoint, $type) {
                        if (!is_array($result)) {
                            return '';
                        }

                        $result['image'] = isset($result['image']) && !empty($result['image'])
                            ? $endpoint . parse_url($result['image'], PHP_URL_PATH)
                            : '';

                        if ($type === 'playlists') {
                            $result['image'] = self::baseUrl() .
                                '/images/playlist-icon.png';
                        }
                        return $result;
                    },
                    $results['data']
                );
            }

            // Generate XMLHttpRequest response.
            header('X-Speed-Test: ' . (microtime(true) - $start));
            echo \wp_json_encode($results);
            wp_die();
        });

        add_action('admin_post_mm_videos_authenticate', function () {
            delete_transient(Constants::OPT_ACCESS_TOKEN);
            $this->getAccessToken();
        });
    }

    /**
     * Sets up script translations
     */
    protected function initTranslations()
    {
        if (function_exists('wp_set_script_translations')) {
            /**
             * May be extended to wp_set_script_translations( 'my-handle', 'my-domain',
             * plugin_dir_path( MY_PLUGIN ) . 'languages' ) ). For details see
             * https://make.wordpress.org/core/2018/11/09/new-javascript-i18n-support-in-wordpress/
             */
            add_action('wp_enqueue_scripts', function () {
                wp_set_script_translations('minute-media', 'minute-media');
            });
        }
    }

    protected function initFeaturedVideo()
    {
        add_action('admin_init', function () {
            new FeaturedVideo;
        });
    }
}


