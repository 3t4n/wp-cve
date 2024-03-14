<?php

require_once(realpath(__DIR__ . '/Constant.php'));

if (!defined('ABSPATH')) exit;

use Box\Spout\Reader\Common\Creator\ReaderFactory;
use Box\Spout\Common\Type;

class MPG_Helper
{
    public static $urls_array;

    // Подключает .mo файл перевода из указанной папки.
    public static function mpg_set_language_folder_path()
    {
        load_plugin_textdomain('mpg', false, dirname(plugin_basename(__DIR__)) . '/lang/');
    }

    // Register additional (monthly) interval for cron because WP hasn't weekly period
    public static function mpg_cron_monthly($schedules)
    {
        $schedules['monthly'] = array(
            'interval' => 60 * 60 * 24 * 30,
            'display' => __('Monthly', 'mpg')
        );

        return $schedules;
    }

    // Register additional (monthly) interval for cron because WP hasn't monthly period
    public static function mpg_cron_weekly($schedules)
    {
        $schedules['weekly'] = array(
            'interval' => 60 * 60 * 24 * 7,
            'display' => __('Weekly', 'mpg')
        );

        return $schedules;
    }

    public static function mpg_activation_events()
    {
	    $is_ajax = isset( $_POST['isAjax'] ) ? (bool) $_POST['isAjax'] : false;
        if ( $is_ajax ) {
            check_ajax_referer( MPG_BASENAME, 'securityNonce' );
        }
        try {

            if (is_multisite()) {

                // Если это мультисайт, то для каждого мультисайта создаем в БД
                foreach (get_sites() as $site) {

                    $blog_id = intval($site->blog_id);

                    // Если индекс = 1, значит это главный сайт. Его файлы ложим в корень, а для дочерних - в подпапки.
                    // Делаю так на случай того, если мультисйт переделают в обычный, чтобы остались работать пути для главного сайта
                    // (который станет единственным)

                    $blog_index = $blog_id === 1 ? '' : $blog_id;

                    $uploads_folder_path = MPG_UPLOADS_DIR . $blog_index;

                    if (!file_exists($uploads_folder_path)) {
                        mkdir($uploads_folder_path);
                    }


                    $cache_folder_path = MPG_CACHE_DIR . $blog_index;

                    if (!file_exists($cache_folder_path)) {
                        mkdir($cache_folder_path);
                    }

                    MPG_ProjectModel::mpg_create_database_tables($blog_index);
                }
            } else {
                if ( ! file_exists( WP_CONTENT_DIR . '/mpg-uploads' ) ) {
                    mkdir( WP_CONTENT_DIR . '/mpg-uploads' );
                }

                if ( ! file_exists( WP_CONTENT_DIR . '/mpg-cache' ) ) {
                    mkdir( WP_CONTENT_DIR . '/mpg-cache' );
                }

                MPG_ProjectModel::mpg_create_database_tables('');
            }

            if ($is_ajax) {
                echo json_encode(['success' =>  true]);
                wp_die();
            }
        } catch (Exception $e) {
            if ($is_ajax) {

                do_action( 'themeisle_log_event', MPG_NAME, $e->getMessage(), 'debug', __FILE__, __LINE__ );

                echo json_encode([
                    'success' => false,
                    'error' => $e->getMessage()
                ]);
                wp_die();
            }
        }
    }




    public static function mpg_send_analytics_data()
    {
      check_ajax_referer( MPG_BASENAME, 'securityNonce' );
      // nothing here.
    }

    // Remove cron task when user deactivate plugin
    public static function mpg_set_deactivation_option()
    {
        wp_clear_scheduled_hook('schedule_execution');
    }


    public static function mpg_admin_assets_enqueue($hook_suffix)
    {
        // echo $hook_suffix;

        // Include styles and scripts in MGP plugin pages only
        if (
            strpos($hook_suffix, 'toplevel_page_mpg-dataset-library') !== false ||
            strpos($hook_suffix, 'mpg_page_mpg-advanced-settings') !== false ||
            strpos($hook_suffix, 'mpg_page_mpg-search-setting') !== false ||
            ( strpos($hook_suffix, '_mpg-project-builder') !== false && ! empty( $_GET['action'] ) && in_array( $_GET['action'], array( 'edit_project', 'from_scratch' ), true ) )
        ) {

            wp_enqueue_script('mpg_listFilter',                 plugins_url('frontend/libs/jquery.listfilter.min.js', __DIR__), array('jquery'));
            wp_enqueue_script('mpg_datatable_js',               plugins_url('frontend/libs/dataTables/jquery.dataTables.min.js', __DIR__), array('jquery'));
            wp_enqueue_script('mpg_bootstrap_js',               plugins_url('frontend/libs/bootstrap/bootstrap.min.js', __DIR__), array('jquery'));
            wp_enqueue_script('mpg_datetime_picker',            plugins_url('frontend/libs/datetimepicker/jquery.datetimepicker.full.min.js', __DIR__), array('jquery'));
            wp_enqueue_script('mpg_select2_js',                 plugins_url('frontend/libs/select2/select2.full.min.js', __DIR__), array('jquery'));
            wp_enqueue_script('mpg_toast_js',                   plugins_url('frontend/libs/toast/toast.js', __DIR__), array('jquery'));

            wp_enqueue_script('mpg_popper_1_js',                 plugins_url('frontend/libs/popper/popper.min.js', __DIR__), array('jquery'));

            wp_enqueue_script('mpg_tippy_2_js',                 plugins_url('frontend/libs/popper/tippy-bundle.umd.min.js', __DIR__), array('jquery'));
            wp_enqueue_script('mpg_main_js',                    plugins_url('frontend/js/app.js', __DIR__), array('jquery'));

            wp_localize_script('mpg_main_js', 'backendData', [
                'baseUrl'           => self::mpg_get_base_url(false),
                'lang_code'         => defined( 'ICL_LANGUAGE_CODE' ) && 'en' !== ICL_LANGUAGE_CODE ? sprintf( '/%s/', ICL_LANGUAGE_CODE ) : '',
                'datasetLibraryUrl' => admin_url('admin.php?page=mpg-dataset-library'),
                'projectPage'       => admin_url('admin.php?page=mpg-project-builder'),
                'mpgAdminPageUrl'   => admin_url(),
                'mpgUploadDir'      => MPG_CACHE_URL,
                'securityNonce'     => wp_create_nonce( MPG_BASENAME ),
                'isPro'             => mpg_app()->is_premium(),
            ]);

            wp_enqueue_style('mpg_datatable',                   plugins_url('frontend/libs/dataTables/jquery.dataTables.min.css', __DIR__));
            wp_enqueue_style('mpg_bootstrap_css',               plugins_url('frontend/libs/bootstrap/bootstrap.min.css', __DIR__));
            wp_enqueue_style('mpg_datetimepicker_css',          plugins_url('frontend/libs/datetimepicker/jquery.datetimepicker.full.min.css', __DIR__));
            wp_enqueue_style('mpg_toast_css',                   plugins_url('frontend/libs/toast/toast.css', __DIR__));
            wp_enqueue_style('mpg_select2_css',                 plugins_url('frontend/libs/select2/select2.min.css',   __DIR__));

            wp_enqueue_style('mpg_font_awesome_css',            plugins_url('frontend/css/font-awesome.css',   __DIR__));

            wp_enqueue_style('mpg_main_css',                    plugins_url('frontend/css/style.css', __DIR__));

            wp_add_inline_style( 'mpg_main_css', '.condition-row {display: inline-flex;}.condition-row:not(:last-child) .add-new-condition:last-child {display:none;}.condition-row select {display: inline-flex;min-width: 170px;}.condition-row:first-child .mpg_headers_condition_value_dropdown:disabled + .btn-danger:not(.mpp-remove-action) {display: none;} .condition-container + .tooltip-circle {margin-left: 45px;}' );

            self::register_survey();
        }
    }

    public static function mpg_front_assets_enqueue()
    {

        if (is_search()) {
            wp_enqueue_script('mpg_searchpage', plugins_url('frontend/js/mpg-front-search.js', __DIR__),  array('jquery'));

            wp_localize_script('mpg_searchpage', 'backendData', [
                'ajaxurl'           => admin_url('admin-ajax.php'),
                'mpgUploadDir'      => MPG_CACHE_URL,
                'securityNonce'     => wp_create_nonce( MPG_BASENAME ),
            ]);
        }
    }


    public static function mpg_add_type_attribute($tag, $handle, $src)
    {
        // if not your script, do nothing and return original $tag
        if ('mpg_js' !== $handle) {
            return $tag;
        }
        // change the script tag by adding type="module" and return it.
        $tag = '<script type="module" src="' . esc_url($src) . '"></script>';
        return $tag;
    }

    public static function mpg_get_site_url( $skip_locale = false )
    {

        global $blog_id;

        if (is_multisite()) {
            $current_blog_details = get_blog_details(array('blog_id' => $blog_id));
            $blog_url = str_replace( $current_blog_details->path, '', trim( get_home_url( $blog_id, '/', 'relative' ) ) );
            $siteName = str_replace( self::mpg_get_domain(), '', $blog_url );
        } else {
            if ( ! $skip_locale && function_exists( 'icl_get_home_url' ) ) {
                $siteName = str_replace(self::mpg_get_domain(), '', trim(icl_get_home_url(), '/'));
                $siteName = ltrim( rtrim( $siteName, '/' ), '/' );
            } else {
                $siteName = str_replace(self::mpg_get_domain(), '', trim(home_url('/', 'relative'), '/'));
            }
        }
        return trim($siteName);
    }

    // Return site URL
    public static function mpg_get_domain()
    {
        if (defined('WP_HOME')) {
            return WP_HOME;
        } else {
            return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
        }
    }


    public static function mpg_get_base_url($for_main_site, $skip_locale = false)
    {
        $blog_id = get_current_blog_id();

        if (is_multisite()) {
            $sites =  get_sites();

            $base_url = '';

            if ($for_main_site) {
                $base_url = self::mpg_get_domain() . $sites[0]->path;
            } else {

                $site = array_filter($sites, function ($site) use ($blog_id) {
                    return (int) $site->blog_id === $blog_id;
                });

                if (!function_exists('array_key_first')) {
                    function array_key_first(array $arr)
                    {
                        foreach ($arr as $key => $unused) {
                            return $key;
                        }
                        return NULL;
                    }
                }

                $index = array_key_first($site);
                $base_url = self::mpg_get_domain() . $site[$index]->path;
            }
        } else {
            $base_url = self::mpg_get_domain() . '/' . self::mpg_get_site_url( $skip_locale );
        }

        if (substr($base_url, -1) === '/') {
            // Обрежем слеш в конце, если есть
            $base_url = substr($base_url, 0, -1);
        }
        if ( ! $skip_locale && defined( 'ICL_LANGUAGE_CODE' ) && ! in_array(ICL_LANGUAGE_CODE, [ 'en', 'all' ] ) ) {
            $base_url = $base_url . '/' . ICL_LANGUAGE_CODE;
            $base_url = rtrim( $base_url, '/' );
        }

        return $base_url;
    }

    // Return the path of URL
    public static function mpg_get_request_uri()
    {
        global $wp;
        $full_url_path = home_url($wp->request);
        $current_url = urldecode( str_ireplace( home_url(), '/', $full_url_path ) . '/' );
        $current_url = preg_replace( '/(\/+)/', '/', $current_url );
        return strtolower($current_url);
    }

    public static function mpg_get_extension_by_path($path)
    {

        $regexp = '/format=(xlsx|ods|csv)/s';

        preg_match_all($regexp, $path, $matches, PREG_SET_ORDER, 0);

        // Если это ссылка на Gooole Drive ( шареный документ, то ок), а если нет - то берем из конца строки,
        // то что после последней точки
        if ($matches) {
            return $matches[0][1];
        } else {

            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            // Если в расширении есть точка - обрезаем,
            return strpos($ext, '.') === 0 ? ltrim($ext, $ext[0]) : $ext;
        }
    }

    public static function array_flatten($array)
    {
        if (!is_array($array)) {
            return false;
        }
        $result = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, self::array_flatten($value));
            } else {
                $result = array_merge($result, array($key => $value));
            }
        }
        return $result;
    }

    public static function mpg_header_code_container()
    {

        $code = '';

        echo $code;
    }

    public static function mpg_get_direct_csv_link($raw_link, $worksheet_id = null)
    {

        // false = substring was not found in target string
        if (strpos($raw_link, 'docs.google.com') !== false or strpos($raw_link, 'drive.google.com') !== false) {

            $documentId = str_replace([
                'https://docs.google.com/spreadsheets/d/',
                'https://drive.google.com/file/d/',
                '/view?usp=sharing',
                '/edit?usp=sharing'
            ], ['', '', '', ''], $raw_link);

            $final_url = 'https://docs.google.com/spreadsheets/d/' . $documentId . '/export?format=csv&id=' . $documentId;

            if ($worksheet_id) {
                $final_url .=  '&gid=' . $worksheet_id;
            }
            return $final_url;
        }

        return $raw_link;
    }

    public static function mpg_get_spout_reader_by_extension($ext)
    {

        if ($ext === 'csv') {
            $reader = ReaderFactory::createFromType(Type::CSV); // for CSV files
        } else if ($ext === 'xlsx') {
            $reader = ReaderFactory::createFromType(Type::XLSX); // for XLSX files
        } elseif ($ext === 'ods') {
            $reader = ReaderFactory::createFromType(Type::ODS); // for ODS files
        } else {
            throw new Exception(__('Unsupported file extension:' . ' ' . $ext, 'mpg'));
        }

        return $reader;
    }

    public static function mpg_get_dataset_array( stdClass $project = null )
    {
        $project_id         = isset( $project->id ) ? $project->id : 0;
        $dataset_path       = isset( $project->source_path ) ? $project->source_path : '';
        $periodicity        = isset( $project->schedule_periodicity ) ? $project->schedule_periodicity : null;
        $source_direct_link = isset( $project->original_file_url ) ? $project->original_file_url : '';
        $worksheet_id       = isset( $project->worksheet_id ) ? $project->worksheet_id : '';
        $space_replacer     = isset( $project->space_replacer ) ? $project->space_replacer : '';
        $url_structure      = isset( $project->url_structure ) ? $project->url_structure : '';
        $source_type        = isset( $project->source_type ) ? $project->source_type : '';

        global $mpg_dataset;
        if ( ! empty( $mpg_dataset[ $project_id ] ) ) {
            if ( is_array( $mpg_dataset[ $project_id ] ) ) {
                return $mpg_dataset[ $project_id ];
            }
            return json_decode( $mpg_dataset[ $project_id ] );
        }

        $expiration = 0;
        if ( null === $periodicity ) {
            $expiration = apply_filters( 'mpg_live_data_update_interval', MINUTE_IN_SECONDS * 15 );
        }

        $key_name = wp_hash( 'dataset_array_' . $project_id );
        $dataset_array = get_transient( 'dataset_array_' . $project_id );
        if ( false === $dataset_array ) {
            $dataset_array = get_transient( $key_name );
        }

        if ( false === strpos( $dataset_path, 'wp-content' ) ) {
            $dataset_path = MPG_UPLOADS_DIR . $dataset_path;
        }

        if (!$dataset_array) {
            $dataset_array = [];
            $ext = MPG_Helper::mpg_get_extension_by_path($dataset_path);
			if ( '' !== $ext ) {
                $reader = MPG_Helper::mpg_get_spout_reader_by_extension($ext);
				if ( ! is_readable( $dataset_path ) ) {
                	$dataset_path = MPG_UPLOADS_DIR . basename( $dataset_path );
				}
                $reader->open($dataset_path);
                foreach ($reader->getSheetIterator() as $sheet) {
                    foreach ($sheet->getRowIterator() as $row) {
                        $row = $row->toArray();
                        if ($row[0] !== NULL) {
                            $dataset_array[] = $row;
                        }
                    }
                }
                set_transient( $key_name, wp_json_encode( $dataset_array, MPG_JSON_OPTIONS ), $expiration );
			}
        }
        if ( ! doing_action( 'wp_ajax_mpg_get_search_results' ) ) {
            $mpg_dataset[ $project_id ] = $dataset_array;
        }
        if ( is_array( $dataset_array ) ) {
            return $dataset_array;
        }
        return json_decode( $dataset_array );
    }

    static function mpg_string_start_with($str, $needle)
    {
        return substr($str, 0, 1) === $needle;
    }


    static function mpg_string_end_with($str, $needle)
    {
        return substr($str, -1, 1) === $needle;
    }

    public static function mpg_prepare_post_excerpt($short_codes, $strings, $post_content)
    {
        $string = preg_replace('/\[.*?\]/m', '', $post_content);
        $string = str_replace(["\r", "\n"], ['', ''], $string);
        $string = strip_tags($string);
        $excerpt_length = (int) get_option('mpg_search_settings')['mpg_ss_excerpt_length'];
        if ( ! has_shortcode( $post_content, 'mpg_spintax' ) ) {
            $string = wp_trim_words($string, $excerpt_length );
            return preg_replace($short_codes, $strings, $string);
        }
        $string = preg_replace($short_codes, $strings, $string);
        $string = MPG_SpintaxModel::mpg_generate_spintax_string($string);
        $string = wp_trim_words($string, $excerpt_length );
        return $string;
    }

    public static function mpg_unique_array_by_field_value($array, $field)
    {
        $unique_array = [];
        foreach ($array as $element) {
            $hash = $element[$field];
            $unique_array[$hash] = $element;
        }

        return array_values($unique_array);
    }

    /**
     * Live project data update.
     */
    public static function mpg_live_project_data_update( stdClass $project = null ) {
        global $mpg_urls_array;

        $project_id         = isset( $project->id ) ? $project->id : 0;
        $dataset_path       = isset( $project->source_path ) ? $project->source_path : '';
        $periodicity        = isset( $project->schedule_periodicity ) ? $project->schedule_periodicity : null;
        $source_direct_link = isset( $project->original_file_url ) ? $project->original_file_url : '';
        $worksheet_id       = isset( $project->worksheet_id ) ? $project->worksheet_id : '';
        $space_replacer     = isset( $project->space_replacer ) ? $project->space_replacer : '';
        $url_structure      = isset( $project->url_structure ) ? $project->url_structure : '';
        $source_type        = isset( $project->source_type ) ? $project->source_type : '';

        $expiration = 0;
        if ( null === $periodicity ) {
            $expiration = apply_filters( 'mpg_live_data_update_interval', MINUTE_IN_SECONDS * 15 );
        }

        if ( false === strpos( $dataset_path, 'wp-content' ) ) {
            $dataset_path = MPG_UPLOADS_DIR . $dataset_path;
        }

        $key_name = wp_hash( 'dataset_array_' . $project_id );
        $dataset_array = get_transient( 'dataset_array_' . $project_id );
        if ( false === $dataset_array ) {
            $dataset_array = get_transient( $key_name );
        }

        if ( empty( $mpg_urls_array[ $project_id ] ) && empty( $dataset_array ) && $expiration > 0 ) {
            if ( ! empty( $source_direct_link ) ) {
                if ( 'upload_file' === $source_type ) {
                    $source_direct_link = $dataset_path;
                }
                $direct_link = MPG_Helper::mpg_get_direct_csv_link( $source_direct_link, $worksheet_id );
                $ext = MPG_Helper::mpg_get_extension_by_path( $direct_link );
                $download_file = MPG_DatasetModel::download_file( $direct_link, $dataset_path );
                $urls_array = MPG_ProjectModel::mpg_generate_urls_from_dataset( $dataset_path, $url_structure, $space_replacer, true );
                $dataset_array = $urls_array['dataset_array'];
                $urls_array = $urls_array['urls_array'];

                if ( ! doing_action( 'wp_ajax_mpg_get_search_results' ) ) {
                    $mpg_urls_array[ $project_id ] = $urls_array;
                }

                $fields_array = array();
                self::$urls_array = $urls_array;
                $fields_array['urls_array'] = wp_json_encode( $urls_array );
                MPG_ProjectModel::mpg_update_project_by_id( $project_id, $fields_array, true );
                $project->urls_array = $fields_array['urls_array'];
            }
        }
        return $project;
    }

    /**
     * Filter found posts.
     *
     * @param int $found_posts WP_Post found posts.
     * @return int
     */
    public static function mpg_found_posts( $found_posts ) {
        global $mpg_default_posts;
        return $mpg_default_posts > 0 ? count( $mpg_default_posts ) + $found_posts : $found_posts;
    }

    /**
     * Handle posts results.
     *
     * @param array  $posts WP_Post array.
     * @param object $query WP_Query object.
     * @return array
     */
    public static function mpg_posts_results( $posts, $query ) {
        if ( ! is_home() && ! is_search() ) {
            return $posts;
        }
        if ( is_admin() ) {
            return $posts;
        }
        global $mpg_default_posts;
        if ( empty( $mpg_default_posts ) ) {
            return $posts;
        }
        $posts_per_page = $query->get( 'posts_per_page' );
        $posts_per_page = $posts_per_page > 0 ? $posts_per_page : get_option( 'posts_per_page' );
        $paged          = $query->get( 'paged' );
        $paged          = $paged > 1 ? $paged - 1 : 0;
        if ( empty( $posts ) ) {
            $total_publish_post = wp_count_posts();
            $total_publish_post = (int) $total_publish_post->publish;
            $posts              = range( 1, $total_publish_post );
        }
        $posts                = array_merge( $posts, $mpg_default_posts );
        $query->found_posts   = is_array( $posts ) ? count( $posts ) : $query->found_posts;
        $posts                = array_chunk( $posts, $posts_per_page );
        $query->max_num_pages = ceil( $query->found_posts / $posts_per_page );
        $query->posts         = isset( $posts[ $paged ] ) ? $posts[ $paged ] : array();
        return $query->posts;
    }

    /**
     * Handle pre get posts.
     *
     * @param object $query WP_Query object.
     * @return void
     */
    public static function mpg_pre_get_posts( $query ) {
        if ( ! is_home() && ! is_search() ) {
            return;
        }
        if ( is_admin() ) {
            return;
        }
        $where       = ' WHERE `participate_in_default_loop` = 1';
        $project_ids = MPG_ProjectModel::mpg_get_project_ids_by_where( $where );
        $project_ids = apply_filters( 'mpg_projects_participate_in_default_loop', $project_ids );

        $post_type = $query->get( 'post_type' );
        if ( ! empty( $post_type ) && ! in_array( $post_type, apply_filters( 'mpg_default_loop_post_type', array( 'post' ) ), true ) ) {
            return;
        }
        global $mpg_default_posts;
        foreach ( $project_ids as $project_id ) {
            $project       = \MPG_ProjectModel::mpg_get_project_by_id( $project_id );
            $project       = reset( $project );
            $dataset_array = MPG_Helper::mpg_get_dataset_array( $project );
            $urls_array    = $project->urls_array ? json_decode( $project->urls_array, true ) : array();

            $headers       = $project->headers;
            $headers_array = json_decode( $headers );
            $headers_array = array_map(
                function ( $raw_header ) {
                    $header = str_replace( ' ', '_', strtolower( $raw_header ) );
                    if ( strpos( $header, 'mpg_' ) !== 0 ) {
                        $header = 'mpg_' . $header;
                    }
                    return $header;
                },
                $headers_array
            );
            // Get header number by name.
            $featured_image_url = array_search( 'mpg_image', $headers_array, true );
            $template_id        = isset( $project->template_id ) ? (int) $project->template_id : 0;
            $template           = get_post( $template_id );
            $mpg_default_posts  = array();
            if ( $template instanceof \WP_Post ) {
                $template_name    = $template->post_title;
                $template_content = $template->post_content;
                $short_codes      = \MPG_CoreModel::mpg_shortcodes_composer( $headers_array );
                foreach ( $urls_array as $index => $url ) {
                    $index   = ++$index;
                    $strings = $dataset_array[ $index ];

                    // Create duplicate post array.
                    $duplicate_post                   = new \WP_Post( new stdClass() );
                    $replaced_shortcodes_string_title = preg_replace( $short_codes, $strings, $template_name );
                    $replaced_shortcodes_string       = $replaced_shortcodes_string_title;
                    // Store results.
                    $duplicate_post->ID                  = $project->template_id;
                    $duplicate_post->filter              = 'raw';
                    $duplicate_post->post_title          = $replaced_shortcodes_string;
                    $duplicate_post->post_name           = $url;
                    $duplicate_post->post_content        = preg_replace( $short_codes, $strings, $template_content );
                    $duplicate_post->post_author         = $template->post_author;
                    $duplicate_post->post_date           = $template->post_date;
                    $duplicate_post->post_featured_image = ! empty( $featured_image_url ) ? esc_url( $featured_image_url ) : null;

                    $mpg_default_posts[] = $duplicate_post;
                }
            }
        }
    }

    /**
     * Get the plan category for the product plan ID.
     *
     * @param object $license_data The license data.
     * @return int
     */
    private static function plan_category( $license_data ) {

        if ( !isset( $license_data->plan ) || ! is_numeric(  $license_data->plan ) ) {
            return 0; // Free
        }

        $plan = (int) $license_data->plan;
        $current_category = -1; // Unknown category.

        $categories = array(
            "1" => array(1, 4), // Personal
            "2" => array(2, 5), // Business
            "3" => array(3, 6), // Agency
        );

        foreach ( $categories as $category => $plans ) {
            if ( in_array( $plan, $plans, true ) ) {
                $current_category = (int) $category;
                break;
            }
        }

        return $current_category;
    }

    /**
	 * Get the data used for the survey.
	 *
	 * @return array
	 * @see survey.js
	 */
	public static function get_survey_metadata() {

		$user_id       = 'mgp_';
		$license_saved = get_option( 'multi_pages_plugin_premium_license_data', array() );

		if ( ! empty( $license_saved->key ) ) {
			$user_id .= $license_saved->key;
		} else {
			$user_id .= preg_replace( '/[^\w\d]*/', '', get_site_url() ); // Use a normalized version of the site URL as a user ID for free users.
		}

		$days_since_install = round( ( time() - get_option( 'multi_pages_plugin_install', 0 ) ) / DAY_IN_SECONDS );
		$install_category   = 0;
		if ( 0 === $days_since_install || 1 === $days_since_install ) {
			$install_category = 0;
		} elseif ( 1 < $days_since_install && 8 > $days_since_install ) {
			$install_category = 7;
		} elseif ( 8 <= $days_since_install && 31 > $days_since_install ) {
			$install_category = 30;
		} elseif ( 30 < $days_since_install && 90 > $days_since_install ) {
			$install_category = 90;
		} elseif ( 90 <= $days_since_install ) {
			$install_category = 91;
		}

        $version = get_plugin_data( MPG_BASENAME );
        if ( ! empty( $version['Version'] ) ) {
            $version = $version['Version'];
        } else {
            $version = '';
        }

		return array(
			'userId'     => $user_id,
			'attributes' => array(
				'license_status'     => ! empty( $license_saved->license ) ? $license_saved->license : 'invalid',
				'days_since_install' => $install_category,
				'version'            => $version,
                'plan'               => self::plan_category( $license_saved ),
			),
		);
	}

	/**
	 * Register the survey script.
	 */
	public static function register_survey() {

		// Register the survey script.
		$survey_handler = apply_filters( 'themeisle_sdk_dependency_script_handler', 'survey' );
		if ( empty( $survey_handler) ) {
            return;
        }
        
        do_action( 'themeisle_sdk_dependency_enqueue_script', 'survey' );
        wp_enqueue_script( 'mpg_survey', plugins_url('frontend/js/survey.js', __DIR__), array( $survey_handler ) );
        wp_localize_script( 'mpg_survey', 'mpgSurveyData', self::get_survey_metadata() );
	}
}
