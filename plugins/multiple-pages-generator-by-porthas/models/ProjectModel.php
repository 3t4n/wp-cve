<?php

if (!defined('ABSPATH')) exit;

require_once(realpath(__DIR__) . '/../helpers/Constant.php');

class MPG_ProjectModel
{

    public static function mpg_create_database_tables($blog_index)
    {
        try {

            if (isset($_POST['isAjax']) && $_POST['isAjax'] === true) {
                $blog_index = (bool) $_POST['isMultisite'];
            }

            global $wpdb;

            if (is_int($blog_index)) {
                $table_prefix = $wpdb->base_prefix . $blog_index . '_';
            } else {
                $table_prefix = $wpdb->base_prefix;
            }

            $mpg_projects_table = $table_prefix . MPG_Constant::MPG_PROJECTS_TABLE;
            $mpg_spintax_table = $table_prefix . MPG_Constant::MPG_SPINTAX_TABLE;
            $mpg_cache_table = $table_prefix . MPG_Constant::MPG_CACHE_TABLE;
            $mpg_logs_table = $table_prefix . MPG_Constant::MPG_LOGS_TABLE;

            require_once(ABSPATH . '/wp-admin/includes/upgrade.php');

            #Check to see if the table exists already, if not, then create it

            if (!$wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($mpg_projects_table))) == $mpg_projects_table) {


                $sql = "CREATE TABLE `" . $mpg_projects_table . "` ( ";

                $sql .= " `id` int(128) NOT NULL AUTO_INCREMENT, ";

                $sql .= " `name` varchar(200) NOT NULL DEFAULT 'New Template', ";
                $sql .= " `entity_type` varchar(50) DEFAULT NULL, ";
                $sql .= " `template_id` int(10) DEFAULT NULL, ";
                $sql .= " `exclude_in_robots` BOOLEAN DEFAULT FALSE,";
                $sql .= " `participate_in_search` BOOLEAN DEFAULT FALSE,";
                $sql .= " `participate_in_default_loop` BOOLEAN DEFAULT FALSE,";

                $sql .= " `source_type` text DEFAULT NULL, ";
                $sql .= " `source_path` text DEFAULT NULL,";
                $sql .= " `worksheet_id` int(20) DEFAULT NULL, ";
                $sql .= " `original_file_url`  varchar(250) DEFAULT NULL,";

                $sql .= " `headers` text DEFAULT NULL, ";
                $sql .= " `url_structure` text DEFAULT NULL, ";
                $sql .= " `urls_array` MEDIUMTEXT DEFAULT NULL, ";
                $sql .= " `space_replacer` varchar(10) NOT NULL DEFAULT '-', ";

                $sql .= " `sitemap_url` varchar(255) DEFAULT NULL, ";
                $sql .= " `sitemap_filename` varchar(255) DEFAULT NULL, ";
                $sql .= " `sitemap_max_url` int(10) DEFAULT NULL, ";
                $sql .= " `sitemap_update_frequency` varchar(200) DEFAULT NULL, ";
                $sql .= " `sitemap_add_to_robots` BOOLEAN DEFAULT NULL, ";

                $sql .= " `schedule_source_link` text DEFAULT NULL, ";
                $sql .= " `schedule_periodicity` varchar(255) DEFAULT NULL, ";
                $sql .= " `schedule_notificate_about` varchar(255) DEFAULT NULL, ";
                $sql .= " `schedule_notification_email` varchar(255) DEFAULT NULL, ";

                $sql .= " `cache_type` varchar(255) NOT NULL DEFAULT 'none', ";

                $sql .= " `created_at` int(20) DEFAULT NULL, ";
                $sql .= " `updated_at` int(20) DEFAULT NULL, ";

                $sql .= "  PRIMARY KEY (`id`) ";
                $sql .= ") ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ; ";

                dbDelta($sql);
                $sql = null;
            }

            if ( ! $wpdb->get_var( "SHOW COLUMNS FROM `$mpg_projects_table` LIKE 'sitemap_priority'" ) ) {
                $wpdb->query( "ALTER TABLE `$mpg_projects_table` ADD `sitemap_priority` float NOT NULL" );
            }
            if ( ! $wpdb->get_var( "SHOW COLUMNS FROM `$mpg_projects_table` LIKE 'participate_in_default_loop'" ) ) {
                $wpdb->query( "ALTER TABLE `$mpg_projects_table` ADD `participate_in_default_loop` BOOLEAN DEFAULT FALSE" );
            }

            if (!$wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($mpg_spintax_table))) == $mpg_spintax_table) {

                $sql  = "CREATE TABLE  `" . $mpg_spintax_table . "` ( ";
                $sql .= "`id` INT(10) NOT NULL AUTO_INCREMENT , ";
                $sql .= "`project_id` INT(10) NULL ,";
                $sql .= "`url` TEXT NOT NULL ,";
                $sql .= "`spintax_string` TEXT NULL ,";
                $sql .= "PRIMARY KEY (`id`), INDEX `url` (`url`(100))) ENGINE = MyISAM DEFAULT CHARSET=utf8mb4;";

                dbDelta($sql);
                $sql = null;
            }

            $is_block_id_column_exist = $wpdb->get_results("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND  table_name = '" . $mpg_spintax_table . "' AND column_name = 'block_id'");

            if (empty($is_block_id_column_exist)) {
                $wpdb->query("ALTER TABLE `" . $mpg_spintax_table . "` ADD `block_id` varchar(255) NOT NULL default '1' AFTER `project_id`");
            }


            if (!$wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($mpg_cache_table))) == $mpg_cache_table) {

                $sql  = "CREATE TABLE  `" . $mpg_cache_table . "` ( ";
                $sql .= "`id` INT(10) NOT NULL AUTO_INCREMENT , ";
                $sql .= "`project_id` INT(10) NOT NULL,";
                $sql .= "`url` TEXT NOT NULL ,";
                $sql .= "`cached_string` mediumtext NOT NULL,";
                $sql .= "PRIMARY KEY (`id`), INDEX `url` (`url`(500))) ENGINE = MyISAM DEFAULT CHARSET=utf8mb4;";

                dbDelta($sql);
                $sql = null;
            }

            if (!$wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($mpg_logs_table))) == $mpg_logs_table) {

                $sql  = "CREATE TABLE  `" . $mpg_logs_table . "` ( ";
                $sql .= "`id` INT(10) NOT NULL AUTO_INCREMENT , ";
                $sql .= "`project_id` INT(10) NOT NULL, ";
                $sql .= "`level` varchar(20) NOT NULL, ";
                $sql .= "`url`  varchar(250) DEFAULT NULL, ";
                $sql .= "`message` text NOT NULL, ";
                $sql .= "`datetime` date NOT NULL, ";
                $sql .= " PRIMARY KEY (`id`), INDEX `url` (`url`(250)))  ENGINE = MyISAM DEFAULT CHARSET=utf8mb4;";

                dbDelta($sql);
                $sql = null;
            }

            $is_cache_column_exist = $wpdb->get_results("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND table_name = '" . $mpg_projects_table . "' AND column_name = 'cache_type'");

            if (empty($is_cache_column_exist)) {
                $wpdb->query("ALTER TABLE `" . $mpg_projects_table . "` ADD `cache_type` varchar(255) NOT NULL default 'none' AFTER `schedule_notification_email`");
            }

            $is_url_mode_column_exist = $wpdb->get_results("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND table_name = '" . $mpg_projects_table . "' AND column_name = 'url_mode'");

            if (empty($is_url_mode_column_exist)) {
                $wpdb->query("ALTER TABLE `" . $mpg_projects_table . "` ADD `url_mode` varchar(25) NOT NULL default '" . MPG_Constant::DEFAULT_URL_MODE . "' AFTER `headers`");
            }

            $is_apply_condition_column_exist = $wpdb->get_results("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND table_name = '" . $mpg_projects_table . "' AND column_name = 'apply_condition'");

            if (empty($is_apply_condition_column_exist)) {
                $wpdb->query("ALTER TABLE `" . $mpg_projects_table . "` ADD `apply_condition` varchar(200) default null  AFTER `url_mode`");
            }

            $is_participate_in_search_column_exist =  $wpdb->get_results("SELECT *  FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND table_name = '" . $mpg_projects_table . "' AND column_name = 'participate_in_search'");
            if (empty($is_participate_in_search_column_exist)) {
                $wpdb->query("ALTER TABLE `" . $mpg_projects_table . "` ADD `participate_in_search` BOOLEAN DEFAULT FALSE  AFTER `exclude_in_robots`");
            }


            $is_logs_table_have_id_column = $wpdb->get_results("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND table_name = '" . $mpg_logs_table . "' AND column_name = 'id'");
            if (empty($is_logs_table_have_id_column)) {

                $wpdb->query("ALTER TABLE  `" . $mpg_logs_table . "`  DROP PRIMARY KEY;");
                $wpdb->query("ALTER TABLE  `" . $mpg_logs_table . "` ADD `id` INT(10) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`id`);");
            }
        } catch (Exception $e) {

            // В WprdPress ошибка вида "Wprdpress database error" - не является throwable, т.е она не прырывает ход выполнения скрипта, а просто выводится как echo, и может ломать json ответ.
            // Надо копать в сторону WP_Error
            do_action( 'themeisle_log_event', MPG_NAME, $e->getMessage(), 'debug', __FILE__, __LINE__ );

            throw new Exception($e->getMessage());
        }
    }

    public static function mpg_create_base_carcass($project_name, $entity_type, $template_id, $exclude_in_robots = false)
    {
        global  $wpdb;

        $current_time_in_unix = time();
        $wpdb->insert($wpdb->prefix . MPG_Constant::MPG_PROJECTS_TABLE, array(

            'name' => $project_name,
            'entity_type' => $entity_type,
            'template_id' => $template_id,
            'exclude_in_robots' => $exclude_in_robots,
            'created_at' => $current_time_in_unix,
            'updated_at' => $current_time_in_unix
        ));

        return $wpdb->insert_id;
    }


    // Возвращает массив названий и типов сущностей зарегистрированіх в WordPress
    public static function mpg_get_custom_types()
    {
        $storage = [];
        $args = array('public' => true);
        $output = 'objects'; // names or objects, note names is the default

        foreach (get_post_types($args, $output) as $post_type) {
            if ($post_type->name === 'attachment') {
                continue;
            }
            array_push($storage, array('name' => $post_type->name, 'label' => $post_type->label));
        }

        return $storage;
    }

    public static function mpg_get_posts_by_custom_type()
    {

        check_ajax_referer( MPG_BASENAME, 'securityNonce' );

        try {
            $custom_type_name = sanitize_text_field($_POST['custom_type_name']);

            $template_id = ! empty( $_POST['template_id'] ) ? intval( $_POST['template_id'] ) : 0;
            $args = array(
                'post_type' => $custom_type_name,
                'posts_per_page' => 10,
                'post_status' => 'publish',
                'post__in' => array( $template_id ),
                'orderby' => 'title',
                'order'   => 'ASC',
            );

            if ( isset( $_POST['q'] ) && ! empty( $_POST['q']['term'] ) ) {
                $args['s'] = sanitize_text_field( $_POST['q']['term'] );
                unset( $args['posts_per_page'] );
                unset( $args['post__in'] );
                add_filter( 'posts_where', array( 'MPG_ProjectModel', 'mpg_get_search_by_title' ), 10, 2 );
            }

            global $sitepress;
            $current_lang = '';

            if ( is_object( $sitepress ) ) {
                $current_lang = $sitepress->get_current_language();
                $sitepress->switch_lang( 'all' );
            }
            $query_object = new WP_Query( $args );
            
            if ( is_object( $sitepress ) ) {
                $sitepress->switch_lang( $current_lang );
            }
            remove_filter( 'posts_where', array( 'MPG_ProjectModel', 'mpg_get_search_by_title' ), 10 );

            // Свойство posts есть у всех типов, даж если єто page или какой-то кастом. тип.
            if (!$query_object->posts) {
                echo json_encode(array('success' => true, 'data' => []));
                wp_die();
            }

            $storage = [];
            $front_page_id = get_option('page_on_front');

            foreach ($query_object->posts as $post) {

                $entity = array(
                    'id' => $post->ID,
                    'title' => $post->post_title,
                    'is_home' => false
                );

                if ($custom_type_name === 'page') {
                    if ($post->ID == $front_page_id) {
                        $entity['is_home'] = true;
                    }
                }

                array_push($storage, $entity);
            }
            echo json_encode(array('success' => true, 'data' => $storage));
        } catch (Exception $e) {

            do_action( 'themeisle_log_event', MPG_NAME, $e->getMessage(), 'debug', __FILE__, __LINE__ );

            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        wp_die();
    }

    public static function mpg_upload_file()
    {
        try {

            if (isset($_FILES['file']['name']) && isset($_FILES['file']['tmp_name'])) {

                $project_id = (int) $_POST['projectId'];

                $filename      = sanitize_text_field($_FILES['file']['name']);
                $temp_filename = sanitize_text_field($_FILES['file']['tmp_name']);

                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                if (!in_array($ext, ['csv', 'xls', 'xlsx', 'ods'])) {
                    throw __('Unsupported file extension', 'mpg');
                }

                $destination = realpath(__DIR__ . '/../temp/') . '/unlinked_file.' . $ext;

                $move = move_uploaded_file($temp_filename, $destination);

                if ($move) {

                    MPG_ProjectModel::mpg_update_project_by_id($project_id, ['original_file_url' => $filename]);

                    echo json_encode(['success' => true, 'data' => ['path' => $destination, 'original_file_url' => $filename]]);
                    wp_die();
                } else {
                    do_action( 'themeisle_log_event', MPG_NAME, __('Error while uploading file', 'mpg'), 'debug', __FILE__, __LINE__ );
                    throw __('Error while uploading file', 'mpg');
                }
            }
        } catch (Exception $e) {

            do_action( 'themeisle_log_event', MPG_NAME, $e->getMessage(), 'debug', __FILE__, __LINE__ );

            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            wp_die();
        }
    }


    public static function mpg_generate_urls_from_dataset($dataset_path, $url_structure, $space_replacer, $return_dataset = false )
    {
        if ( false === strpos( $dataset_path, 'wp-content' ) ) {
            $dataset_path = MPG_UPLOADS_DIR . $dataset_path;
        }

        $ext = MPG_Helper::mpg_get_extension_by_path($dataset_path);

        $reader = MPG_Helper::mpg_get_spout_reader_by_extension($ext);

        $dataset_array = [];

        try {

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
            break; // читаем только первую книгу
            $reader->close();
        }
        } catch( Exception $e ) {
            return array(
                'dataset_array' => array(),
                'urls_array' => array(),
            );
        }


        // 1. Берем первый ряд, тоесть тот что содержит заголовки
        $headers = ! empty( $dataset_array[0] ) ? $dataset_array[0] : array();

        $shortcodes = [];
        foreach ($headers as $raw_header) {
            // В хедерах всегда пробелы заменяем на _, а в самих URL'ах - уже на то что выбрал пользователь ($space_replacer)
            $header = str_replace(' ', '_', $raw_header);

            if (strpos($header, 'mpg_') === 0) {
                $shortcodes[] = '{{' . strtolower($header) . '}}';
            } else {
                $shortcodes[] = '{{mpg_' . strtolower($header) . '}}';
            }
        }

        $re = '/{{(.*?)}}/';
        // 2. Проходимся по ним циклом, приводим в вид шорткода
        // "my Custom Text" => {{mpg_my_custom_text}}
        $url_structure = str_replace(' ', $space_replacer, $url_structure);
        preg_match_all($re, $url_structure, $matches, PREG_SET_ORDER, 0);

        if ( empty( $matches ) ) {
            $url_structure = $shortcodes[0];
            $url_structure = str_replace(' ', $space_replacer, $url_structure);
            preg_match_all($re, $url_structure, $matches, PREG_SET_ORDER, 0);
        }
        // Тут будут номера столбцов, которым соответсвуюш шорткоды

        $indexes = [];
        foreach ($matches as $match) {
            // Находим номер столбца по шорткоду.
            $index = array_search($match[0], $shortcodes);
            if (is_int($index)) {
                $indexes[] = $index;
            }
        }

        $urls_array = [];

        if (!empty($indexes)) {

            // Всего столбцов в датасете может быть 10, и в $shortcodes будет 10 элементов,
            // но для str_replace() первый и второй массив для замены должен содержать одинаковое колличество элементов
            // Поскольку по индексам из ряда было получено, ну, скажем 3 элемента (url состоит из 3-х пилов), то и шорткодов должно быть 3
            $needed_shortcodes = [];
            foreach ($indexes as $column_number) {
                $needed_shortcodes[] = $shortcodes[$column_number];
            }

            $dataset_with_headers = $dataset_array;

            // Удалим из массива первый элемент (заголовки)
            array_shift($dataset_array);

            foreach ($dataset_array  as $iteration => $row) {

                $line = [];
                foreach ($indexes as $index) {

                    $ceil_value = (string) $row[$index]; // (string) - для случаев если там будет null или false
                    $line[] = self::mpg_processing_special_chars($ceil_value, $space_replacer);
                }

                // Заменяем шорткоды на реальные значения из колонок
                $urls_array[] = '/' . str_replace($needed_shortcodes, $line, $url_structure) . '/';
            }
        }

        if ( $return_dataset ) {
            return array(
                'urls_array' => $urls_array,
                'dataset_array' => $dataset_with_headers,
            );
        }

        return $urls_array;
    }


    public static function mpg_get_project_by_id($project_id, $force = false)
    {

        global $wpdb;

        try {

            $key_name = wp_hash( 'project_id_' . $project_id );
            $project = get_transient( 'project_id_' . $project_id );
            if ( false === $project ) {
                $project = get_transient( $key_name );
            }
            if (!$project || $force) {
                $project = $wpdb->get_results(
                    $wpdb->prepare("SELECT * FROM {$wpdb->prefix}" .  MPG_Constant::MPG_PROJECTS_TABLE . " WHERE id=%d", $project_id)
                );
                set_transient( $key_name, $project );
            }

            return count($project) ? $project : null;
        } catch (Exception $e) {

            do_action( 'themeisle_log_event', MPG_NAME, sprintf( 'Can\'t getproject by id. Details: %s', $e->getMessage() ), 'debug', __FILE__, __LINE__ );

            throw new Exception(__('Can\'t getproject by id. Details:', 'mpg') . $e->getMessage());
        }
    }


    public static function mpg_update_project_by_id($project_id, $fields_array, $delete_dataset = false )
    {

        global $wpdb;

        try {
            if ( empty( $fields_array['worksheet_id'] ) ) {
                unset( $fields_array['worksheet_id'] );
            }
            $wpdb->update($wpdb->prefix .  MPG_Constant::MPG_PROJECTS_TABLE, $fields_array, ['id' => $project_id]);

            if ($wpdb->last_error) {
                throw new Exception($wpdb->last_error);
            }
	        delete_transient( 'project_id_' . $project_id );
            delete_transient( wp_hash( 'project_id_' . $project_id ) );
            if ( $delete_dataset ) {
                delete_transient( 'dataset_array_' . $project_id );
                delete_transient( wp_hash( 'dataset_array_' . $project_id ) );
            }
            return true;
        } catch (Exception $e) {

            do_action( 'themeisle_log_event', MPG_NAME, sprintf( 'Can\'t update project by ID. Details: %s', $e->getMessage() ), 'debug', __FILE__, __LINE__ );

            throw new Exception(__('Can\'t update project by ID. Details:', 'mpg') . $e->getMessage());
        }
    }


    public static function mpg_get_all()
    {

        try {
            global $wpdb;

            $projects = $wpdb->get_results("SELECT id, name FROM {$wpdb->prefix}" . MPG_Constant::MPG_PROJECTS_TABLE);

            echo json_encode([
                'success' => true,
                'data' => count($projects) ? $projects : null
            ]);
        } catch (Exception $e) {

            do_action( 'themeisle_log_event', MPG_NAME, sprintf( 'Can\'t get all projects Details: %s', $e->getMessage() ), 'debug', __FILE__, __LINE__ );

            throw new Exception(__('Can\'t get all projects Details:', 'mpg') . $e->getMessage());
        }

        wp_die();
    }

    public static function deleteProjectFromDb($project_id)
    {

        try {
            global $wpdb;

            //  It returns the number of rows updated, or false on error.
            return $wpdb->delete($wpdb->prefix . MPG_Constant::MPG_PROJECTS_TABLE, ['id' => $project_id], ['%d']);
        } catch (Exception $e) {

            do_action( 'themeisle_log_event', MPG_NAME, sprintf( 'Can\'t delete project. Details: %s', $e->getMessage() ), 'debug', __FILE__, __LINE__ );

            throw new Exception(__('Can\'t delete project. Details:', 'mpg') . $e->getMessage());
        }
    }

    public static function deleteFileByPath($path)
    {

        try {

            if (file_exists($path)) {

                if (!unlink($path)) {
                    throw new Exception(__('Can\'t delete file.', 'mpg'));
                }
            }

            return true;
        } catch (Exception $e) {

            do_action( 'themeisle_log_event', MPG_NAME, sprintf( 'Details: %s', $e->getMessage() ), 'debug', __FILE__, __LINE__ );

            throw new Exception(__('Details: ' . $e->getMessage()));
        }
    }

    public static function mpg_copy_dataset_file($source_path)
    {

        $ext = strtolower(pathinfo($source_path, PATHINFO_EXTENSION));

        $destination = MPG_UPLOADS_DIR . rand(1000000, 9999999) . '.' . $ext;

        copy($source_path, $destination);

        return $destination;
    }

    public static function mpg_remove_cron_task_by_project_id($project_id, $project)
    {

        try {

            if ($project[0]->schedule_source_link && $project[0]->schedule_notificate_about && $project[0]->schedule_periodicity && $project[0]->schedule_notification_email) {

                $cron_arguments = [
                    (int) $project_id,
                    $project[0]->schedule_source_link,
                    $project[0]->schedule_notificate_about,
                    $project[0]->schedule_periodicity,
                    $project[0]->schedule_notification_email
                ];

                wp_clear_scheduled_hook('mpg_schedule_execution', $cron_arguments);

                MPG_ProjectModel::mpg_update_project_by_id($project_id, [
                    'schedule_periodicity' => null,
                    'schedule_source_link' => null,
                    'schedule_notificate_about' => null,
                    'schedule_notification_email' => null
                ]);


                return true;
            } else {
                do_action( 'themeisle_log_event', MPG_NAME, __('Some of needed values is missing, please, recreate task.', 'mpg'), 'debug', __FILE__, __LINE__ );
                throw new Exception(__('Some of needed values is missing, please, recreate task.', 'mpg'));
            }
        } catch (Exception $e) {
            do_action( 'themeisle_log_event', MPG_NAME, $e->getMessage(), 'debug', __FILE__, __LINE__ );
            throw new Exception($e->getMessage());
        }
    }

    public static function mpg_processing_robots_txt($exclude_in_robots, $template_id)
    {
        $path = ABSPATH . 'robots.txt';
        $handle = false;
        if ( is_readable( $path ) ) {
            $handle = fopen($path, 'r');
        }

        // Add each line to an array
        if ($handle) {


            $template_entity_url = get_permalink($template_id);
            $template_entity_url = str_replace(get_site_url(), '', $template_entity_url);

            $robots_string = explode("\n", fread($handle, 2048));

            // Надо добавить строку-исключение. Смотрим, нет ли там ее уже (ну а вдруг)
            if ($exclude_in_robots) {

                // То есть той строки которую хотим добавить - еще нет. Добавляем.
                if (!in_array('Disallow: ' . $template_entity_url, $robots_string)) {
                    $robots_string[] = 'Disallow: ' . $template_entity_url;
                }

                // Надо удалить строку из robots.
            } else {
                // Если она там есть - удаляем, иначе нет смысла что-либо делать
                $index = array_search('Disallow: ' . $template_entity_url, $robots_string);

                if ($index !== false) {
                    unset($robots_string[$index]);
                }
            }

            $file_content = implode("\n", $robots_string);

            file_put_contents($path, $file_content);
        }
    }

    public static function mpg_remove_sitemap_from_robots($sitemap_url)
    {
        $handle = fopen(ABSPATH . '/robots.txt', 'r');

        if ($handle) {

            $robots_string = explode("\n", fread($handle, 2048));

            $index = array_search('Sitemap: ' . $sitemap_url, $robots_string);

            if ($index !== false) {
                unset($robots_string[$index]);
            }
            $file_content = implode("\n", $robots_string);

            file_put_contents(ABSPATH . '/robots.txt', $file_content);
        }
    }

    public static function mpg_processing_special_chars($ceil_value, $space_replacer)
    {

        //Обрезаем слеши только вначале и в конце строки.
        $start_end_slashes_trimed = ltrim(rtrim(strtolower($ceil_value), '/'), '/');
        // Перед удалением всех спецсимволов - заменяем пробел на строку, иначе пробелы будут удалены регуляркой ниже.
        $escaped_spaces = preg_replace(
            ['/\s+/u', '/\//', '/\./', '/\-/', '/\_/', '/\~/', '/\=/'],
            ['mpgspaceholder', 'mpgslashholder', 'mpgdotholder', 'mpgdashholder', 'mpglodashholder', 'mpgtildaholder', 'mpgequalholder'],
            $start_end_slashes_trimed
        );

        // Заменяем все спец. символы, типа ' & * @ # $ % в 
        $special_chars_trimmed =  preg_replace('/\W/mu', '', $escaped_spaces);

        // То что раньше было пробелом - заменяем на space_replacer
        $back_to_allowed_chars = str_replace(
            ['mpgspaceholder', 'mpgslashholder', 'mpgdotholder', 'mpgdashholder', 'mpglodashholder', 'mpgtildaholder', 'mpgequalholder'],
            [$space_replacer, '/', '.', '-', '_', '~', '='],
            $special_chars_trimmed
        );

        return $back_to_allowed_chars;
    }


    public static function mpg_get_lastmod_date($project_id)
    {

        $project = MPG_ProjectModel::mpg_get_project_by_id($project_id);
        $template_id = isset($project[0]) ? $project[0]->template_id : null;

        if ($template_id) {
            return get_the_modified_date('Y-m-d', $template_id);
        }

        // Если нет (по какой-то причине) даты когда был изменен шаблон, то веернем сегодняшнюю дату.
        return date('Y-m-d');
    }

    public static function mpg_get_all_templates_id()
    {

        try {
            global $wpdb;

            $ids = $wpdb->get_results("SELECT DISTINCT template_id FROM {$wpdb->prefix}" . MPG_Constant::MPG_PROJECTS_TABLE . ' WHERE `exclude_in_robots` = 1', ARRAY_A);
            $storage = [];
            if ($ids && count($ids)) {
                foreach ($ids as $id) {
                    $storage[] = (int) $id['template_id'];
                }
            }

            return $storage;
        } catch (Exception $e) {

            do_action( 'themeisle_log_event', MPG_NAME, sprintf( 'Can\'t get all projects Details: %s', $e->getMessage() ), 'debug', __FILE__, __LINE__ );

            throw new Exception(__('Can\'t get all projects Details:', 'mpg') . $e->getMessage());
        }
    }

    /**
     * Search by title only.
     *
     * @param string $where SQL where query.
     * @param object $wp_query WP Query Object.
     */
    public static function mpg_get_search_by_title( $where, $wp_query ) {
        global $wpdb;
        if ( $search_term = $wp_query->get( 's' ) ) {
            $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( $wpdb->esc_like( $search_term ) ) . '%\'';
        }
        return $where;
    }

    public static function mpg_get_project_ids_by_where( $where = '' )
    {

        try {
            global $wpdb;

            $ids = $wpdb->get_results("SELECT `id` FROM {$wpdb->prefix}" . MPG_Constant::MPG_PROJECTS_TABLE . $where, ARRAY_A);
            if ( empty( $ids ) ) {
                return array();
            }
            return array_map( 'intval', array_column( $ids, 'id' ) );
        } catch (Exception $e) {

            do_action( 'themeisle_log_event', MPG_NAME, sprintf( 'Can\'t get all projects Details: %s', $e->getMessage() ), 'debug', __FILE__, __LINE__ );

            throw new Exception(__('Can\'t get all projects Details:', 'mpg') . $e->getMessage());
        }
    }
    
}
