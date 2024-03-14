<?php
  require_once ABSPATH . 'wp-admin/includes/plugin.php'; // for get_plugin_data()
  require_once __DIR__ . '/constants.php';

  function trinity_curl_get($url, $error_message = '', $die = true, $async = false, $http_args = []) {
    $start = microtime(true);

    $args = [
      'timeout'     => $async ? 0.01 : TRINITY_AUDIO_MAX_HTTP_REQ_TIMEOUT,
      'blocking'    => !$async,
      'httpversion' => '1.1',
      'sslverify'   => false,
    ];

    $args = array_merge($args, $http_args);

    $response = wp_remote_get($url, $args);

    trinity_report_long_requests($start, $url);

    if ($async) {
      return;
    }
    trinity_handle_error($response, $url, '', $error_message, $die);

    return wp_remote_retrieve_body($response);
  }

  /**
   * function used to make post request, handle `long requests`
   * params description:
   * string error_message
   * boolean async - mode doesn't return anything
   * boolean die - enable die function if request failed
   * array http_args
   * boolean throw_exception - if `die` is false and there is request error throw exception *
   *
   * @param array $options options should contain 'post_data' and 'url' params, other parameters error_message, die, async, http_args, throw_exception are optional
   * @return object|void
   * @throws Exception
   */
  function trinity_curl_post(array $options) {
    $start           = microtime(true);
    $post_data       = $options['post_data'];
    $url             = $options['url'];
    $error_message   = isset($options['error_message']) ? $options['error_message'] : '';
    $die             = isset($options['die']) ? $options['die'] : true;
    $async           = isset($options['async']) ? $options['async'] : false;
    $http_args       = isset($options['http_args']) ? $options['http_args'] : [];
    $throw_exception = isset($options['throw_exception']) ? $options['throw_exception'] : false;

    $args = [
      'body'        => $post_data,
      'timeout'     => $async ? 0.01 : TRINITY_AUDIO_MAX_HTTP_REQ_TIMEOUT,
      'blocking'    => !$async,
      'httpversion' => '1.1',
      'sslverify'   => false
    ];

    $args = array_merge($args, $http_args);

    $response = wp_remote_post($url, $args);

    trinity_report_long_requests($start, $url);

    if ($async) {
      return;
    }
    $ok = trinity_handle_error($response, $url, $post_data, $error_message, $die);
    if (!$ok && $throw_exception && !$die) {
      throw new Exception('Request failed');
    }

    $body = wp_remote_retrieve_body($response);
    return json_decode($body, true);
  }

  function trinity_is_user_admin() {
    return current_user_can('manage_options');
  }

  function trinity_get_install_key() {
    return get_option(TRINITY_AUDIO_INSTALLKEY);
  }

  function trinity_get_view_key() {
    return get_option(TRINITY_AUDIO_VIEWKEY);
  }

  function trinity_get_db_plugin_version() {
    return get_option(TRINITY_AUDIO_PLUGIN_VERSION, []);
  }

  function trinity_get_plugin_migration() {
    return get_option(TRINITY_AUDIO_PLUGIN_MIGRATION, []);
  }

  function trinity_get_source_language() {
    return get_option(TRINITY_AUDIO_SOURCE_LANGUAGE);
  }

  function trinity_get_gender() {
    return get_option(TRINITY_AUDIO_GENDER_ID);
  }

  function trinity_get_new_posts_default() {
    return get_option(TRINITY_AUDIO_SOURCE_NEW_POSTS_DEFAULT);
  }

  function trinity_get_powered_by() {
    return !!get_option(TRINITY_AUDIO_POWERED_BY);
  }

  function trinity_get_preconnect() {
    return !!get_option(TRINITY_AUDIO_PRECONNECT);
  }

  function trinity_get_player_label() {
    return get_option(TRINITY_AUDIO_PLAYER_LABEL);
  }

  function trinity_get_skip_tags() {
    return get_option(TRINITY_AUDIO_SKIP_TAGS, []);
  }

  function trinity_get_allowed_shortcodes() {
    return get_option(TRINITY_AUDIO_ALLOW_SHORTCODES, []);
  }

  function trinity_get_voice_id() {
    return get_option(TRINITY_AUDIO_VOICE_ID);
  }

  function trinity_get_is_first_changes_saved() {
    return get_option(TRINITY_AUDIO_FIRST_CHANGES_SAVE);
  }

  function trinity_get_check_for_loop() {
    return get_option(TRINITY_AUDIO_CHECK_FOR_LOOP);
  }

  function trinity_get_enable_for_api() {
    return get_option(TRINITY_AUDIO_ACTIVATE_ON_API_POST_CREATION);
  }

  function trinity_set_is_account_key_linked() {
    return add_option(TRINITY_AUDIO_IS_ACCOUNT_KEY_LINKED, true);
  }

  function trinity_get_is_account_key_linked() {
    return get_option(TRINITY_AUDIO_IS_ACCOUNT_KEY_LINKED);
  }

  function trinity_is_migration_v5_failed() {
    return get_option(TRINITY_AUDIO_CONFIGURATION_V5_FAILED);
  }

  function trinity_get_first_time_install() {
    return get_transient(TRINITY_AUDIO_FIRST_TIME_INSTALL);
  }

  function trinity_is_enabled_for_post($post_id) {
    return get_post_meta($post_id, TRINITY_AUDIO_ENABLED, true);
  }

  function trinity_get_plugin_version() {
    $plugin_data = get_plugin_data(__DIR__ . '/../trinity.php');
    return $plugin_data['Version'];
  }

  function trinity_get_voices($languages_url = TRINITY_AUDIO_STANDARD_VOICES_URL) {
    $result = trinity_curl_get($languages_url, trinity_can_not_connect_error_message('Can\'t get list of supported languages.'), false);

    if (!$result) return false;

    $languages = [];

    foreach (json_decode($result) as $lang) {
      $voiceIds = [];
      foreach ($lang->voices as $gender => $voice) {
        if (isset($voice->voiceId)) $voiceIds[$gender] = $voice->voiceId;
        else if (isset($voice->providerVoiceId)) $voiceIds[$gender] = $voice->providerVoiceId;
      }

      $languageObj = (object)[
        'name'    => $lang->languageName,
        'code'    => $lang->code,
        'genders' => array_keys((array)$lang->voices),
        'voices'  => $voiceIds
      ];

      if ($lang->country) $languageObj->name .= " ($lang->country)";

      array_push($languages, $languageObj);
    }

    $languages = array_values($languages);

    return $languages;
  }

  function trinity_include_audio_player($page_content) {
    $date    = trinity_get_date();
    $post_id = $GLOBALS['post']->ID;

    $post_hash = trinity_ph_get_audio_posthash($post_id);

    $gender      = get_post_meta($post_id, TRINITY_AUDIO_GENDER_ID, true);

    $whitelist_shortcodes = trinity_get_allowed_shortcodes();
    $title                = trinity_get_clean_text(get_the_title($post_id), '', $whitelist_shortcodes);
    $content              = get_post_field('post_content', $post_id);

    // TODO: change it
    $clean_text               = trinity_get_clean_text($title, $content, $whitelist_shortcodes);
    $clean_text_without_title = trinity_get_clean_text('', $content, $whitelist_shortcodes);
    $is_no_text               = (bool)trinity_is_text_empty($clean_text);

    if ($is_no_text) {
      return false;
    }

    $viewkey = trinity_get_view_key();

    $source_language = get_post_meta($post_id, TRINITY_AUDIO_SOURCE_LANGUAGE, true);

    /*
    * Unfortunately AWS Polly and AWS Translate support different namings.
    *
    * @link https://docs.aws.amazon.com/polly/latest/dg/voicelist.html
    * @link https://docs.aws.amazon.com/translate/latest/dg/what-is.html
    */
    $language_code_map = [
      'arb'    => 'ar', // cmn-CN code is zh language (Arabic)
      'cmn-CN' => 'zh', // cmn-CN code is zh language (Chinese)
    ];

    if (isset($language_code_map[$source_language])) {
      $source_language = $language_code_map[$source_language];
    }

    $trinity_tts_wp_config = [
      'cleanText'     => $clean_text, // old approach

      'headlineText'  => $title,
      'articleText'   => $clean_text_without_title,
      'metadata'      => [
        'author' => trinity_get_author_of_post_id($post_id)
      ],
      'pluginVersion' => trinity_get_plugin_version(),
    ];

    $response = "<script nitro-exclude data-wpfc-render='false' data-cfasync='false' data-no-optimize='1' data-no-defer='1' data-no-minify='1' data-trinity-mount-date='$date' id='trinity_tts_wp_config'>var TRINITY_TTS_WP_CONFIG = " . json_encode($trinity_tts_wp_config) . ';</script>';

    $player_query_params = [
      'postHashV2'  => $post_hash,
      'language'    => $source_language,
      'voiceGender' => $gender,
      'pageURL'     => get_permalink()
    ];

    $post_voice_id = get_post_meta($post_id, TRINITY_AUDIO_VOICE_ID, true);
    if ($post_voice_id) $player_query_params['voiceId'] = $post_voice_id;

    if (trinity_is_migration_v5_failed()) {
      unset($player_query_params['postConfig']);

      $poweredby = trinity_get_powered_by();
      $poweredby = $poweredby ? $poweredby : 0;

      $player_query_params['poweredby'] = $poweredby;
    }

    // do NOT include trinityAudioPlaceholder with PB when we already have in source code, because of using shortcodes, themes, etc... otherwise it will be flashing in wrong position
    if (!strstr($page_content, 'trinityAudioPlaceholder')) {
      $response .= "<div class='trinityAudioPlaceholder' data-trinity-mount-date='$date'>
                      <div class='trinity-tts-pb' dir='ltr' style='font: 12px / 18px Verdana, Arial; height: 80px; line-height: 80px; text-align: left; margin: 0 0 0 82px;'>
                          <strong style='font-weight: 400'>Getting your <a href='//trinityaudio.ai' style='color: #4b4a4a; text-decoration: none; font-weight: 700;'>Trinity Audio</a> player ready...</strong>
                      </div>
                    </div>";
    }

    $trinity_url = TRINITY_AUDIO_STARTUP . $viewkey . '/';

    add_filter('perfmatters_delay_js_exclusions', function($exclusions) {
      $exclusions[] = '/player/trinity/';
      $exclusions[] = '/plugins/trinity/';
      $exclusions[] = 'trinity_tts_wp_config'; // inline script with ID="trinity_tts_wp_config" which injects TRINITY_TTS_WP_CONFIG

      return $exclusions;
    });
    add_filter('script_loader_tag', 'trinity_exclude_player_from_caching_plugins', 10, 2);

    wp_enqueue_script('trinity-player', add_query_arg($player_query_params, $trinity_url));

    return $response;
  }

  function trinity_get_author_of_post_id($post_id) {
    $post_author_id = get_post_field('post_author', $post_id);
    return get_the_author_meta('display_name', $post_author_id);
  }

  function trinity_exclude_player_from_caching_plugins($tag, $handle) {
    $date = trinity_get_date();

    if ($handle === 'trinity-player') {
      remove_filter('script_loader_tag', 'trinity_exclude_player_from_caching_plugins', 10, 2);
      return str_replace('<script', "<script nitro-exclude data-wpfc-render='false' data-cfasync='false' data-no-optimize='1' data-no-defer='1' data-no-minify='1' data-trinity-mount-date='$date'", $tag);
    }
    return $tag;
  }

  function trinity_save_publisher_token() {
    $data = trinity_get_env_details();

    $postData = [
      'installkey'      => trinity_get_install_key(),
      'publisher_token' => $_POST['publisher_token'],
      'top_domain'      => $data['site_domain']
    ];

    $response = trinity_curl_post(
      [
        'post_data' => $postData,
        'url'       => TRINITY_AUDIO_PUBLISHER_TOKEN_URL
      ]
    );

    if (!$response) send_response('ERROR_NETWORK');

    if ($response['code'] === 'SUCCESS' || $response['code'] === 'ALREADY_ASSIGNED_PUBLISHER_TOKEN') {
      trinity_set_is_account_key_linked();
    }

    send_response($response['code']);
  }

  function trinity_audio_ajax_contact_us() {
    header('Content-type: application/json');

    $is_include_log = isset($_POST['include_log']);

    $data = trinity_get_env_details();

    /*
      The code below uses cURL because we need to send 2 files and some data
      along with them as multipart/form-data for which there is not an option
      with wp_remote_post. The use-case of cURL in the plugin here was discussed
      with and approved by the WordPress Plugin Review Team in an email
    */
    if ($is_include_log && file_exists(TRINITY_AUDIO_LOG)) {
      $data['log'] = curl_file_create(TRINITY_AUDIO_LOG);
    }
    if ($is_include_log && file_exists(TRINITY_AUDIO_INFO_HTML)) {
      $data['info'] = curl_file_create(TRINITY_AUDIO_INFO_HTML);
    }

    $postData = array_merge($data, filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING));

    $ch = curl_init(TRINITY_AUDIO_CONTACT_US_URL);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: multipart/form-data']);

    $response     = curl_exec($ch);
    $responseData = json_decode($response);

    if (!isset($responseData->ok) || $ch === false) {
      http_response_code(500);
      echo 'Error Code: ' . curl_getinfo($ch, CURLINFO_HTTP_CODE);
      echo 'Error Body: ' . curl_error($ch);
    }

    wp_die($response);
  }

  function trinity_get_posts($offset = 0, $size = -1): array {
    $result = get_posts(
      [
        'fields'         => 'ids', // Only get post IDs
        'orderby'        => 'ID',
        'order'          => 'DESC',
        'posts_per_page' => $size,
        'offset'         => $offset,
        'post_type'      => ['post'],
      ]
    );

    return $result;
  }

  function trinity_get_all_plugins_installed(): string {
    if (is_admin()) {
      if (!function_exists('get_plugins')) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
      }

      $all_plugins = get_plugins();
      $plugins     = [];

      foreach ($all_plugins as $plugin) {
        array_push($plugins, $plugin['Name'] . '-' . $plugin['Version']);
      }

      return implode(', ', $plugins);
    }

    return 'unknown';
  }

  function trinity_get_user_email(): string {
    $current_user = wp_get_current_user();
    if (!($current_user instanceof WP_User)) {
      return 'NOT_LOGIN';
    }

    return $current_user->user_email;
  }

  function trinity_get_user_name(): string {
    $current_user = wp_get_current_user();
    if (!($current_user instanceof WP_User)) {
      return '';
    }

    return trim($current_user->user_firstname . ' ' . $current_user->user_lastname);
  }

  function trinity_get_wp_version(): ?string {
    global $wp_version;

    return $wp_version;
  }

  function trinity_get_env_details(): array {
    return [
      'wp_version'         => trinity_get_wp_version(),
      'plugin_version'     => trinity_get_plugin_version(),
      'php_version'        => phpversion(),
      'all_plugins'        => trinity_get_all_plugins_installed(),
      'installkey'         => trinity_get_install_key(),
      'site_domain'        => parse_url(get_site_url(), PHP_URL_HOST),
      'email'              => trinity_get_user_email(),
      'details_event_type' => 'not_set'
    ];
  }

  function trinity_update_details($url, $event_type = '', $die = true) {
    $data = trinity_get_env_details();
    if ($event_type) {
      $data['details_event_type'] = $event_type;
    }

    if ($event_type = 'activating' && empty($data['installkey']) && !empty($_POST['recover_installkey'])) {
      $data['installkey'] = $_POST['recover_installkey'];
    }

    if (!empty($_POST['publisher_token'])) $data['publisher_token'] = $_POST['publisher_token'];
    if (!empty($_POST['email_subscription'])) $data['email_subscription'] = $_POST['email_subscription'];

    return trinity_curl_post(
      [
        'post_data'     => $data,
        'url'           => $url,
        'error_message' => trinity_can_not_connect_error_message('Can\'t update plugin details.'),
        'die'           => $die
      ]
    );
  }

  function trinity_send_stat_update_settings() {
    $data = [
      'installkey' => trinity_get_install_key(), // need for auth
      'powered_by' => trinity_get_powered_by(),
      'voice_id'   => trinity_get_voice_id()
    ];

    trinity_curl_post(
      [
        'post_data'     => $data,
        'url'           => TRINITY_AUDIO_UPDATE_PLUGIN_CONFIG_URL,
        'error_message' => trinity_can_not_connect_error_message('Can\'t update plugin details.'),
        'die'           => false
      ]
    );
  }

  function trinity_audio_ajax_update_unit_config() {
    $data = [
      'installkey'   => trinity_get_install_key(),
      'speed'        => $_GET['speed'],
      'language'     => $_GET['language'],
      'voiceStyle'   => $_GET['voiceStyle'],
      'engine'       => $_GET['engine'],
      'themeId'      => $_GET['themeId'],
      'voice'        => $_GET['voice'],
      'fab'          => $_GET['fab'],
      'powered_by'   => $_GET['poweredBy'],
      'gender'       => $_GET['gender'],
      'showSettings' => $_GET['showSettings'],
      'shareEnabled' => $_GET['shareEnabled']
    ];

    trinity_curl_post(
      [
        'post_data'     => $data,
        'url'           => TRINITY_AUDIO_UPDATE_FULL_UNIT_CONFIG_URL,
        'error_message' => trinity_can_not_connect_error_message('Can\'t update plugin details.'),
        'die'           => false
      ]
    );
  }

  function trinity_send_stat_migrate_v5_settings() {
    $data = [
      'installkey'      => trinity_get_install_key(), // need for auth
      'source_language' => trinity_get_source_language(),
      'powered_by'      => trinity_get_powered_by(),
      'gender'          => trinity_get_gender()
    ];

    return trinity_curl_post(
      [
        'post_data'     => $data,
        'url'           => TRINITY_AUDIO_UPDATE_PLUGIN_MIGRATION_URL,
        'error_message' => trinity_can_not_connect_error_message('Can\'t migrate plugin details.'),
        'die'           => false
      ]
    );
  }

  function trinity_send_graphite_metric($metric) {
    return trinity_curl_post(
      [
        'post_data' => [
          'metric' => $metric
        ],
        'url'       => TRINITY_AUDIO_METRICS_URL,
        'die'       => false
      ]
    );
  }

  function trinity_send_stat_metrics() {
    $data = [
      'metric'         => $_POST['metric'],
      'additionalData' => $_POST['additionalData'] ?? null
    ];

    return trinity_curl_post(
      [
        'post_data' => $data,
        'url'       => TRINITY_AUDIO_METRICS_URL,
        'die'       => false
      ]
    );
  }

  function trinity_audio_ajax_remove_post_banner() {
    update_option(TRINITY_AUDIO_REMOVE_POST_BANNER, '0');
  }

  function send_response($code) {
    $env_details = trinity_get_env_details();
    $site        = $env_details['site_domain'];

    $register_response = [
      'ERROR_NETWORK' => 'Can\'t activate plugin. Please contact us ' . TRINITY_AUDIO_SUPPORT_EMAIL_LINK . " and provide your domain: <strong>{$site}</strong>",

      'ERROR' => 'Can\'t activate plugin. Please contact us ' . TRINITY_AUDIO_SUPPORT_EMAIL_LINK . " and provide your domain: <strong>{$site}</strong>",

      'ALREADY_REGISTERED' => "It seems like your site <strong>{$site}</strong> is already registered to our services. In order to protect your assets, we do not allow duplicated registrations. <br/> If you've registered before and migrated into a new database/hosting service - please use the form below to insert the <span class='bold-text'>Install Key</span> if you have one, otherwise contact us at " . TRINITY_AUDIO_SUPPORT_EMAIL_LINK . ' to help resolve this issue.',

      'WRONG_INSTALLKEY' => 'We can see that the following value has changed in your database <strong>wp_options.trinity_audio_installkey</strong>. If you know how to revert the change, please do. If not, please write to us at ' . TRINITY_AUDIO_SUPPORT_EMAIL_LINK . ' and we will respond as fast as we can.',

      'WRONG_PUBLISHER_TOKEN' => '<span class="bold-text">Account Key</span> is not found - please verify you have the correct one. If the problem persists, please write to us at ' . TRINITY_AUDIO_SUPPORT_EMAIL_LINK . ' and we will respond as fast as we can.',

      'ALREADY_ASSIGNED_PUBLISHER_TOKEN' => "It seems like your installation is already assigned to your Trinity Account. If it's not reflected on the <a href='" . trinity_add_utm_to_url(TRINITY_AUDIO_DASHBOARD_URL) . "' target='_blank'>Trinity Dashboard</a> please contact us at " . TRINITY_AUDIO_SUPPORT_EMAIL_LINK . ' to help resolve this issue.',

      'SUCCESS' => NULL
    ];

    die(json_encode(
      [
        'code'    => $code,
        'message' => $register_response[$code]
      ]));
  }

  function handle_register_response($response) {
    $response_code = 'ERROR_NETWORK'; // if there is a `response`, $response_code should be overridden

    if (!$response) {
      send_response($response_code);
    }

    $response_code = $response['code'];

    if ($response_code === 'SUCCESS') {
      $response_install_key = $response['data']['installkey'];
      $response_view_key    = $response['data']['viewkey'];

      /*
      Check if response exists, so we don't override with empty values if smth went wrong.
      User can have installkey and could want to restore viewkey.
      */
      if ($response_install_key) {
        update_option(TRINITY_AUDIO_INSTALLKEY, $response_install_key);
      }
      if ($response_view_key) {
        update_option(TRINITY_AUDIO_VIEWKEY, $response_view_key);
      }

      set_transient(TRINITY_AUDIO_FIRST_TIME_INSTALL, true, 60);

      trinity_send_stat_update_settings();

      send_response($response_code);
    }

    send_response($response_code);
  }

  function trinity_register() {
    $install_key = trinity_get_install_key();
    $view_key    = trinity_get_view_key();

    if ($install_key && $view_key) {
      trinity_update_details(TRINITY_AUDIO_UPDATE_PLUGIN_DETAILS_URL, 'reactivating', false);
      return true;
    }

    $response = trinity_update_details(TRINITY_AUDIO_KEYS_URL, 'activating', false);

    handle_register_response($response);
  }

  function trinity_is_text_empty($text) {
    $text = str_replace('.', '', $text);
    return !trim(preg_replace('/\s+/', ' ', $text));
  }

  function trinity_hook_header() {
    if (trinity_get_preconnect()) {
      echo '<link href="https://trinitymedia.ai/" rel="preconnect" crossorigin="anonymous" />' . "\n";
      echo '<link href="https://vd.trinitymedia.ai/" rel="preconnect" crossorigin="anonymous" />' . "\n";
    }
  }

  function trinity_can_not_connect_error_message($error = '') {
    return $error . ' Can\'t connect to Trinity Audio! Please check <a href="/wp-admin/admin.php?page=trinity_audio_logs">logs</a> or contact ' . TRINITY_AUDIO_SUPPORT_MESSAGE;
  }

  function trinity_get_notice_error_message($error = '') {
    return trinity_can_not_connect_error_message("<div class='notice notice-error'><p>" . $error . "<br>") . "</p><p></p></div>";
  }

  function trinity_registered() {
    $install_key = trinity_get_install_key();
    $view_key    = trinity_get_view_key();
    if (empty($install_key) || empty($view_key)) {
      return false;
    }
    return true;
  }

  function trinity_get_package_data() {
    $error_msg = trinity_get_notice_error_message("Can't get plan data.");
    $result    = trinity_curl_get(TRINITY_AUDIO_CREDITS_URL . '?installkey=' . trinity_get_install_key(), $error_msg, false);

    if (!$result) die($error_msg);

    return json_decode($result);
  }

  function trinity_get_unit_config_from_trinity() {
    $error_msg = trinity_get_notice_error_message("Can't get plugin configuration.");
    $result    = trinity_curl_get(TRINITY_AUDIO_UPDATE_PLUGIN_CONFIG_URL . '?installkey=' . trinity_get_install_key(), $error_msg, false);

    if (!$result) die($error_msg);

    return json_decode($result);
  }

  function notifications($package_data) {
    $response = trinity_curl_get(TRINITY_AUDIO_NOTIFICATIONS_URL . '/?type=general', '', false);

    if ($response) {
      $notification = json_decode($response);
      if (property_exists($notification, 'message')) {
        $notification = json_decode($notification->message);
      }

      if ($notification && property_exists($notification, 'message_html')) {
        echo htmlspecialchars_decode($notification->message_html);
      }
    }

    if (!$package_data) return;

    if ($package_data->capType === 'articles'
      && is_numeric($package_data->used)
      && is_numeric($package_data->packageLimit)
      && $package_data->used >= $package_data->packageLimit)
      echo "<div class='trinity-notification'>
              <span>
                You have a maxed out your plan usage!
                <a class='bold-text' target='_blank' href='" . trinity_add_utm_to_url(TRINITY_AUDIO_PRICING_URL) . "'>Upgrade your plan</a>        
              </span>
              <span class='trinity-notification-close'></span>
            </div>";
  }

  function trinity_add_utm_to_url($url, $utm_medium = 'wp_admin', $utm_campaign = '') {
    return add_query_arg(array(
      'utm_medium'   => urlencode($utm_medium),
      'utm_source'   => urlencode(get_site_url()),
      'utm_campaign' => urlencode($utm_campaign)
    ), $url);
  }

  function trinity_get_upgrade_url() {
    if (trinity_get_is_account_key_linked()) return TRINITY_AUDIO_UPGRADE_URL;
    return TRINITY_AUDIO_PRICING_URL;
  }

  function trinity_get_languages($is_premium = false, $is_package_known = false) {
    $cached_languages = get_transient(TRINITY_AUDIO_LANGUAGES_CACHE);
    $languages        = json_decode($cached_languages);

    if ($languages) return $languages;

    if ($is_package_known === false) {
      $package_data = trinity_get_package_data();
      $is_premium   = $package_data->package->isPremium;
    }

    $languages_url = $is_premium ? TRINITY_AUDIO_EXTENDED_VOICES_URL : TRINITY_AUDIO_STANDARD_VOICES_URL;

    $languages = trinity_get_voices($languages_url);

    set_transient(TRINITY_AUDIO_LANGUAGES_CACHE, json_encode($languages), 10800); // 3h

    return $languages;
  }

