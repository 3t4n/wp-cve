<?php

class MPG_CoreModel
{
    public static function mpg_get_all_tepmlates_ids()
    {
        global $wpdb;

        $key_name = wp_hash( 'get_all_tepmlates_ids' );
        $cache = get_transient( 'get_all_tepmlates_ids' );
        if ( false === $cache ) {
            $cache = get_transient( $key_name );
        }

        if ($cache) {
            return $cache;
        }

        $templates_ids = [];

        $all_projects_data = $wpdb->get_results("SELECT template_id FROM " . $wpdb->prefix . MPG_Constant::MPG_PROJECTS_TABLE);


        if ($all_projects_data) {
            foreach ($all_projects_data as $project_object) {

                if ((int) $project_object->template_id) {
                    $templates_ids[] = (int) $project_object->template_id;
                }
            }
        }

        set_transient( $key_name, $templates_ids);
        return $templates_ids;
    }

    // replace shortcodes in head section if exist
    public static function multipage_replace_data($html)
    {

        $path = MPG_Helper::mpg_get_request_uri();
        $metadata_array = self::mpg_get_redirect_rules($path);

        $template_post = get_post($metadata_array['template_id']);
        $current_post = get_post();

        if ($template_post->ID == $current_post->ID) {

            $project_id = $metadata_array['project_id'];
            return self::mpg_shortcode_replacer($html, $project_id);
        }
    }


    public static function mpg_get_redirect_rules($needed_path, $projects = array() )
    {
        global $wpdb, $pagenow, $post;

        if ( defined( 'ICL_LANGUAGE_CODE' ) && 'en' !== ICL_LANGUAGE_CODE ) {
            $needed_path = wp_sprintf( '/%s/%s/', ICL_LANGUAGE_CODE, $needed_path );
        }
        $needed_path = preg_replace( '/(\/+)/', '/', $needed_path );
        $needed_path = explode( '/', $needed_path );
        $needed_path = array_unique( array_filter( $needed_path ) );
        $needed_path = implode( '/', $needed_path );

        if ( is_admin() && false !== strpos( $needed_path, $pagenow ) ) {
            return [];
        }
        // If the requested path is empty, return an empty array.
        if ( empty( $needed_path ) ) {
            return [];
        }

        // If the requested URL is post/term then it will return an empty array.
        if ( function_exists( 'get_queried_object' ) && ! empty( get_queried_object() ) ) {
            return [];
        }

        // If the requested URL is page/post/cpt post then it will return an empty array.
        if ( ! empty( $post ) && $post->post_name === $needed_path ) {
            return [];
        }

        // If the requested URL is post/cpt single-post then it will return an empty array.
        if ( function_exists( 'is_single' ) && is_single() ) {
            return [];
        }

        // array of multi URLs
        $redirect_rules = [];
        $fetch_query = "SELECT * FROM " .  $wpdb->prefix . MPG_Constant::MPG_PROJECTS_TABLE;

        if ( empty( $projects ) && defined( 'ICL_LANGUAGE_CODE' ) ) {
            $projects = $wpdb->get_results( sprintf( '%s %s', $fetch_query, ' WHERE `apply_condition` LIKE "%' . ICL_LANGUAGE_CODE . '%"' ) );
        }
        if ( empty( $projects ) ) {
            $projects = $wpdb->get_results( $fetch_query );
        }

        foreach ($projects as $project) {
            $urls_array  = $project->urls_array ? json_decode( $project->urls_array ) : array();
            $urls_array  = is_array( $urls_array ) ? $urls_array : array();
            $request_uri = MPG_Helper::mpg_get_request_uri();
            if ( null === $project->schedule_periodicity && in_array( $request_uri, $urls_array, true ) ) {
                $updated_project_data = MPG_Helper::mpg_live_project_data_update( $project );
                if ( is_object( $updated_project_data ) ) {
                    $project = $updated_project_data;
                    if ( $project->urls_array ) {
                        $urls_array = json_decode( $project->urls_array );
                        $urls_array = is_array( $urls_array ) ? $urls_array : array();
                    }
                }
            }
            if ( ! empty( $urls_array ) ) {

                $url_match_condition = false;
                foreach ( $urls_array as $iteration => $raw_single_url) {
                    $single_url = urldecode($raw_single_url);

                    if ( defined( 'ICL_LANGUAGE_CODE' ) && 'en' !== ICL_LANGUAGE_CODE ) {
                       $single_url = wp_sprintf( '/%s%s/', ICL_LANGUAGE_CODE, $single_url );
                    }
                    $single_url = preg_replace( '/(\/+)/', '/', $single_url );
                    $single_url = explode( '/', $single_url );
                    $single_url = array_unique( array_filter( $single_url ) );
                    $single_url = implode( '/', $single_url );

                    switch ($project->url_mode) {

                        case 'with-trailing-slash':
                            if (!MPG_Helper::mpg_string_end_with($_SERVER['REQUEST_URI'], '/')) {
                                if ($single_url === $needed_path) {
                                    wp_safe_redirect($_SERVER['REQUEST_URI'] . '/', 302);
                                    break;
                                }
                            }

                            $url_match_condition = defined('AMPFORWP_VERSION') ? in_array($needed_path, [$single_url, $single_url . 'amp/']) : $single_url === $needed_path;
                            if ($url_match_condition) {
                                define('MPG_IS_AMP_PAGE', $needed_path === ($single_url . 'amp/')); // Set  true\false to constant if requested page is AMP
                            }
                            break;

                        case 'without-trailing-slash':
                            if (MPG_Helper::mpg_string_end_with($_SERVER['REQUEST_URI'], '/')) {
                                if ($single_url === $needed_path) {
                                    wp_safe_redirect(rtrim($_SERVER['REQUEST_URI'], '/'), 302);
                                    break;
                                }
                            }

                            // Вот эта часть 'amp/' - логически не очень точна, т.к. по условию, тут не надо слеша вконце, но поскольку $needed_path всегда приходит со слешем,
                            // то приходится придумывать обходной путь, но вообще это надо переделать.
                            $url_match_condition = defined('AMPFORWP_VERSION') ? in_array($needed_path, [$single_url, $single_url . 'amp/']) : $single_url === $needed_path;
                            if ($url_match_condition) {
                                // define('MPG_IS_AMP_PAGE', $needed_path === ($single_url . 'amp/'));
                            }
                            break;

                        default: //both
                            $url_match_condition = defined('AMPFORWP_VERSION') ? in_array($needed_path, [$single_url, $single_url . 'amp', $single_url . 'amp/']) : $single_url === $needed_path;
                            if ($url_match_condition) {
                                // define('MPG_IS_AMP_PAGE', in_array($needed_path, [$single_url . 'amp/', $single_url . 'amp']));
                            }
                    }

                    $lang_str = $project->apply_condition;

                    if ($url_match_condition) {

                        // it's important to check is position eqal to false, but not a 0 or any other numbers.
                        if (is_string($lang_str) &&  strpos($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], $lang_str) === false) {
                            return [];
                        }

                        if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
                            $post_language_details = apply_filters( 'wpml_post_language_details', null, $project->template_id );
                            $language_code = ! empty( $post_language_details['language_code'] ) ? $post_language_details['language_code'] : '';
                            if ( $language_code !== ICL_LANGUAGE_CODE ) {
                                return [];
                            }
                        }

                        $redirect_rules = [
                            'template_id' => $project->template_id,
                            'project_id' => $project->id
                        ];

                        global $wp_object_cache;

                        // In this way solving the problem with mess in generated pages with Redis Object caching enabled.
                        if (defined('WP_REDIS_VERSION') && method_exists($wp_object_cache, 'redis_instance')) {
                            $wp_object_cache->redis_instance()->del(str_replace('_', '', $wpdb->prefix) . ':posts:' . $project->template_id);
                        }


                        if (defined('MPG_EXPERIMENTAL_FEATURES') && MPG_EXPERIMENTAL_FEATURES === true) {

                            if (extension_loaded('memcached')) {

                                if (defined(__NAMESPACE__ . '\PLUGIN_SLUG') && __NAMESPACE__ . '\PLUGIN_SLUG' === 'sg-cachepress') {

                                    $memcache = new \Memcached();
                                    if (defined('MPG_MEMCACHED_HOST') && defined('MPG_MEMCACHED_PORT')) {
                                        $memcache->addServer(MPG_MEMCACHED_HOST, MPG_MEMCACHED_PORT);
                                    } else {
                                        $memcache->addServer('127.0.0.1', '11211');
                                    }

                                    $keys_list = $memcache->getAllKeys();
                                    if ($keys_list) {
                                        foreach ($keys_list as $index => $key) {
                                            if (strpos($key, ':posts:' . $project->template_id) !== false) {
                                                $memcache->delete($keys_list[$index]);
                                            }
                                        }
                                    }
                                }
                            }
                        }


                        break 2; // Останавливаем весь цикл. Ведь один УРЛ найден.
                    }
                }
            }
        }
        return $redirect_rules;
    }

    // Принимает html код страницы, и id преокта
    // Задача функции - заменить {{шорткоды}} на реальные значения
    public static function mpg_shortcode_replacer($content, $project_id)
    {

        global $found_strings;
        // Если во входящей строке нет шорткодов, то и нет смысла ее обрабатывать дальше.
        preg_match_all('/{{mpg_\S+}}/m', $content, $matches, PREG_SET_ORDER, 0);

        if (empty($matches)) {
            return $content;
        }

        // Заменяем [mpg ...]...[/mpg] на статическое приложение, просто как заглшука,
        // чтобы значения в шорткодах не заменялись значениями из датасета.

        $get_shortcodes_regexp = '/\[mpg.*?\[\/mpg]/s';

        preg_match_all($get_shortcodes_regexp, $content, $mpg_shortcodes, PREG_SET_ORDER, 0);

        $placeholers = [];
        foreach ($mpg_shortcodes as $index => $shortcode) {
            $placeholers[] = '(placeholder_replacer_' . $index . ')';
        }

        $mpg_shortcodes = MPG_Helper::array_flatten($mpg_shortcodes);

        $content = str_replace($mpg_shortcodes, $placeholers, $content);

        $url_path = MPG_Helper::mpg_get_request_uri();
        $project = MPG_ProjectModel::mpg_get_project_by_id($project_id);
        $project_data = MPG_Helper::mpg_live_project_data_update( reset( $project ) );
        $dataset_array = MPG_Helper::mpg_get_dataset_array( $project_data );
        // do action with short codes.
        $headers = $project[0]->headers;
        if (!$headers) {
            // Закидывать в лог
            throw new Exception(__('Headers is empty. Try to upload dataset again', 'mpg'));
        }

        // Узнаем, в каком столбце (номер) находятся URL'ы
        $url_column_index = null;
        foreach (json_decode($headers, true) as $index => $header) {
            if ($header === 'mpg_url') {
                $url_column_index = $index;
            }
        }

        $short_codes = self::mpg_shortcodes_composer(json_decode($headers));

        $urls_array = $project[0]->urls_array ? json_decode($project[0]->urls_array) : [];
        if ( empty( $urls_array ) && is_array( MPG_Helper::$urls_array ) ) {
            $urls_array = MPG_Helper::$urls_array;
        }

        $strings = null;
        // Узнаем, в каком ряду (по счету) находится тот URL, который пользователь запросил через браузер
        foreach ($urls_array as $index => $row) {

            $url_match_condition = defined('AMPFORWP_VERSION') ? in_array($url_path, [$row, $row . 'amp/']) : $row === $url_path;

            if ($url_match_condition) {
                // +1 чтобы пропустить ряд с заголовками. Да, можно сделать array_shift, но это затратная операция по CPU.
                $strings = $dataset_array[$index + 1];

                // В столбце с УРЛом - относительный адрес, типа /new-york/  и если пользователь впишет [mpg]{{mpg_url}}[/mpg]
                // то в случае, если у него wp установлен в поддерикторию (sub), адрес получится domain.com/new-york/, а не domain.com/sub/new-york
                // Поэтому, подменяем УРЛ таки образом, чтобы он был правильным. 
                // В случае, если в датасете пользователя нет столба mpg_url, то он такой шорткод и не напишет, и в этих заменах нет смысла. Т.е все логично

                if ($url_column_index !== null) {
                    $strings[$url_column_index] = MPG_CoreModel::mpg_prepare_mpg_url($project, $urls_array, $index);
                }
                // Store found string.
                $found_strings = $strings;

                break;
            }
        }

        if ( ! is_array( $strings ) ) {
            return $content;
        }
        // Dollar sign convert to html entity(&dollar;).
        $strings = array_map(
            function( $s ) {
                return str_replace( array( '$' ), array( '&dollar;' ), $s );
            },
            $strings
        );
        // Эта строка заменяет шорткоды, которые просто стоят в тексте, и не обернуты в [mpg][/mpg]
        $content = preg_replace($short_codes, $strings, $content);

        // А тут делается обратная замена - заглушек на [mpg ...] {{}} [/mpg].
        // Это все для того, чтобы работала выдача всех (а не одного) ряда, если есть условие where.

        $get_placeholders_regexp = '/\(placeholder_replacer_\d{1,3}\)/s';
        preg_match_all($get_placeholders_regexp, $content, $mpg_placeholders, PREG_SET_ORDER, 0);
        $mpg_placeholders = MPG_Helper::array_flatten($mpg_placeholders);

        return str_replace($mpg_placeholders, $mpg_shortcodes, $content);
    }
	/**
	 * Project id.
	 *
	 * @param int $project_id project id.
	 */
	public static function mpg_thumbnail_replacer( $project_id ) {
		$thumbnail_html = '';
		$url_path       = MPG_Helper::mpg_get_request_uri();
		$project        = MPG_ProjectModel::mpg_get_project_by_id( $project_id );
		$project_data   = MPG_Helper::mpg_live_project_data_update( reset( $project ) );
		$dataset_array  = MPG_Helper::mpg_get_dataset_array( $project_data );
		// do action with short codes.
		$headers = $project[0]->headers;
		if ( ! empty( $headers ) ) {
			$urls_array = $project[0]->urls_array ? json_decode( $project[0]->urls_array ) : array();
			if ( empty( $urls_array ) && is_array( MPG_Helper::$urls_array ) ) {
				$urls_array = MPG_Helper::$urls_array;
			}

			$strings = null;
			foreach ( $urls_array as $index => $row ) {
				$url_match_condition = defined( 'AMPFORWP_VERSION' ) ? in_array( $url_path, [$row, $row . 'amp/' ], true ) : $row === $url_path;
				if ( $url_match_condition ) {
					$strings = $dataset_array[ $index + 1 ];
				}
			}

			// Dollar sign convert to html entity(&dollar;).
			$strings = array_map(
				function ( $s ) {
					return str_replace( array( '$' ), array( '&dollar;' ), $s );
				},
				$strings
			);
			if ( ! empty( $headers ) && ! empty( json_decode( $headers ) ) ) {
				$image_column_key = array_search( 'image', json_decode( $headers ), true ) ? array_search( 'image', json_decode( $headers ), true ) : '';
				if ( ! empty( $image_column_key ) ) {
					if ( ! empty( $strings[ $image_column_key ] ) ) {
						$strings[ $image_column_key ] = '<img src=" ' . $strings[ $image_column_key ] . ' " />';
						$thumbnail_html               = $strings[ $image_column_key ];
					}
				}
			}
		}
		return $thumbnail_html;
	}

    public static function mpg_shortcodes_composer($headers)
    {
        $short_codes = [];
        foreach ($headers as $raw_header) {
            $short_code = '';

            if (strpos($raw_header, 'mpg_') === 0) {
                $short_code = "/{{" . str_replace('/', '\/', strtolower($raw_header)) . "}}/"; // create template for preg_replace function
            } else {
                $short_code = "/{{mpg_" . str_replace('/', '\/', strtolower($raw_header)) . "}}/"; // create template for preg_replace function
            }

            $short_code = str_replace(' ', '_', $short_code);

            array_push($short_codes, $short_code);
        }
        return $short_codes;
    }


    public static function mpg_processing_href_matches($content, $short_codes, $href_matches, $strings, $space_replacer, $placeholders, $url_column_index, $base_url)
    {
        $temp_content = $content;

        // Поскольку в href уже стоят заглушки, то меняем шорткоды на реальные значения (не боясь "повредить" то что в href)
        $temp_content =  preg_replace($short_codes, $strings, $temp_content);

        // Теперь соберем одномерный массив с тем, что изначально было в href (скорее всего - шорткоды)

        $original_href_content = array_map(function ($match) use ($base_url) {
            return str_replace('href="', 'href="' . $base_url, $match[0]);
        }, $href_matches);

        // Теперь меняем массив на массив: заглушки на шорткоды
        $temp_content = str_replace($placeholders, $original_href_content, $temp_content);

        foreach ($strings as $index => $ceil) {
            // Это для того, чтобы пропустить ячейку, в которой URL. Чтобы ее не "коробило", не резало слеши... Её выводим как есть.
            if ($index !== $url_column_index) {
                $strings[$index] =  MPG_ProjectModel::mpg_processing_special_chars($ceil, $space_replacer);
            }
        }

        return preg_replace($short_codes, $strings, $temp_content);
    }


    public static function mpg_header_handler($project_id, $path)
    {


        $current_cache_type = MPG_CacheModel::mpg_get_current_caching_type($project_id);

        switch ($current_cache_type) {
            case 'disk':

                $cache_path = MPG_CACHE_DIR . $project_id;
                $cache_file_name = ltrim(rtrim(strtolower($path), '/'), '/') . '.html';

                if (file_exists($cache_path . '/' . $cache_file_name)) {
                    $html = file_get_contents($cache_path . '/' . $cache_file_name);

                    echo MPG_CoreModel::mpg_shortcode_replacer($html, $project_id);
                    exit;
                }
                break;
            case 'database':

                $cached_string = MPG_CacheModel::mpg_get_row_from_database_cache($project_id, $path);
                if ($cached_string) {

                    echo MPG_CoreModel::mpg_shortcode_replacer($cached_string, $project_id);
                    exit;
                }
                break;
        }


        ob_start(function ($buffer) use ($project_id) {
            return MPG_CoreModel::mpg_shortcode_replacer($buffer, $project_id);
        });
    }

    public static function mpg_footer_handler($project_id, $path)
    {

        // Если пользователь залогинен, значит у него есть админ-бар, и ссылки типа "Ввойти", уже будут "Выйти".
        // Потом эта страница попадает в кеш, и будет видна обычным пользователям.

        // Disable caching for OpenGraph requests too. Task 87
        if (is_user_logged_in() || self::mpg_is_opengraph_request() !== false) {
            ob_end_flush();
        } else {

            $current_cache_type = MPG_CacheModel::mpg_get_current_caching_type($project_id);

            $html_code =  ob_get_contents();

            $current_cache_type = 'none' !== $current_cache_type ? $current_cache_type : '';

            switch ($current_cache_type) {
                case 'disk':

                    $cache_path = MPG_CACHE_DIR . $project_id;
                    $cache_file_name = ltrim(rtrim(strtolower($path), '/'), '/') . '.html';
                    $cache_file_name = str_replace('/', '-', $cache_file_name);

                    if (!is_dir($cache_path)) {
                        if (!mkdir($cache_path)) {
                            throw new Exception('Creating forler for caching is failed. Please, check permissions');
                        }

                        // Создадим пустой файл, чтобы через браузер нельзя было посмотреть что в папке.
                        fwrite(fopen($cache_path . '/index.php', 'w+'), '');
                    }

                    if (!file_exists($cache_path . '/' . $cache_file_name)) {
                        fwrite(fopen($cache_path . '/' . $cache_file_name, 'w+'), $html_code);
                    }
                    break;

                case 'database':

                    MPG_CacheModel::mpg_set_row_to_database_cache($project_id, $path, $html_code);
                    break;
                default:
                    return $html_code;
            }

            // Очищает буфер и выводит его содержимое на экран. 
            // Если вклчюен кеш - кидаем данные в него, а потом выводим содержимое буфера.
            ob_end_flush();
        }
    }

    public static function mpg_is_opengraph_request()
    {

        $user_agent = $_SERVER['HTTP_USER_AGENT'];

        return
            strpos($user_agent, 'facebookexternalhit') !== false ||
            strpos($user_agent, 'TelegramBot') !== false ||
            strpos($user_agent, 'Twitterbot') !== false;
    }

    public static function mpg_get_ceil_value_by_header($current_project, $dataset_array, $header_value)
    {

        $url_path = MPG_Helper::mpg_get_request_uri();

        $urls_array = $current_project[0]->urls_array ? json_decode($current_project[0]->urls_array) : [];

        // Узнаем, в каком ряду (по счету) находится тот URL, который пользователь запросил через браузер
        $url_index = array_search($url_path, $urls_array);

        // +1 чтобы пропустить ряд с заголовками. Да, можно сделать array_shift, но это затратная операция по CPU.
        $strings = $dataset_array[$url_index + 1];
        // Из какого по счету столбца брать значение для замены шорткода, который введен в where
        $dataset_array[0] = array_map(function ($header) {

            if (strpos($header, 'mpg_') !== 0) {
                $header = 'mpg_' . $header;
            }
            return strtolower(str_replace(' ', '_', $header));
        }, $dataset_array[0]);

        $shortcode_column_index = array_search(str_replace(['{{', '}}'], '',  $header_value), $dataset_array[0]);

        return  $strings[$shortcode_column_index];
    }

    public static function mpg_prepare_where_condition($project, $where_params, $dataset_array, $column_names, $found_strings = array() )
    {
        $where_storage = [];
        foreach ($where_params as $condition) {

            $column_value_pair = explode('=', $condition);
            $column_name = strtolower(trim($column_value_pair[0])); // column name
            $column_index = array_search($column_name, $column_names);
            $column_value = isset($column_value_pair[1]) ? $column_value_pair[1] : null;

            if (isset($column_value)) {

                preg_match_all('/{{.*?}}/m', $column_value, $matches, PREG_SET_ORDER, 0);
                // Этот блок для того, чтобы работали конструкции типа where="mpg_state_id={{mpg_state_id}};mpg_county_name=Kitsap"
                if (!empty($matches)) {

                    $url_path = MPG_Helper::mpg_get_request_uri();
                    $urls_array = $project[0]->urls_array ? json_decode($project[0]->urls_array) : [];

                    // Узнаем, в каком ряду (по счету) находится тот URL, который пользователь запросил через браузер
                    $url_index = array_search($url_path, $urls_array);

                    if (!$url_index) { //false если не найден  (надо этот случай расследовать более детально)
                        // throw new Exception(__('Current page URL was not found in project. Please, check is project-id attribue in [mpg] shortcode is correct.', 'mpg'));
                        $strings = $found_strings;
                    } else {
                        // +1 чтобы пропустить ряд с заголовками. Да, можно сделать array_shift, но это затратная операция по CPU.
                        $strings = $dataset_array[$url_index + 1];
                    }
                    // Из какого по счету столбца брать значение для замены шорткода, который введен в where
                    $shortcode_column_index = array_search(str_replace(['{{', '}}'], '',  $column_value), $dataset_array[0]);

                    if (MPG_Helper::mpg_string_start_with($column_value_pair[1], '^') && MPG_Helper::mpg_string_end_with($column_value_pair[1], '$')) {

                        $column_value = '^' . $strings[$shortcode_column_index] . '$';
                    } elseif (MPG_Helper::mpg_string_start_with($column_value_pair[1], '^') && !MPG_Helper::mpg_string_end_with($column_value_pair[1], '$')) {

                        $column_value =  '^' . $strings[$shortcode_column_index];
                    } elseif (!MPG_Helper::mpg_string_start_with($column_value_pair[1], '^')  &&  MPG_Helper::mpg_string_end_with($column_value_pair[1], '$')) {

                        $column_value = $strings[$shortcode_column_index] . '$';
                    } elseif (!MPG_Helper::mpg_string_start_with($column_value_pair[1], '^') && !MPG_Helper::mpg_string_end_with($column_value_pair[1], '$')) {

                        $column_value = $strings[$shortcode_column_index];
                    }
                }

                array_push($where_storage, [$column_index => strtolower($column_value)]); // value for search
            }
        }

        return $where_storage;
    }

    public static function mpg_order($source_data, $column_names, $direction, $order_by)
    {

        $column = [];
        $column_index = array_search($order_by, $column_names);

        if ($direction === 'asc' || $direction === 'desc') {

            foreach ($source_data as $key => $row) {

                $column[$key] = isset($row['row']) ? $row['row'][$column_index] : $row[$column_index];
            }

            array_multisort($column, $direction === 'asc' ? SORT_ASC : SORT_DESC, $source_data);
        } elseif ($direction === 'random') {
            shuffle($source_data);
        } else {
        }

        return $source_data;
    }

    public static function mpg_prepare_mpg_url($project, $urls_array, $index)
    {

        if (substr(MPG_Helper::mpg_get_site_url() . $urls_array[$index], 0, 1) === '/') {
            return MPG_Helper::mpg_get_domain() . MPG_Helper::mpg_get_site_url() .  $urls_array[$index];
        } else {
            return MPG_Helper::mpg_get_domain() . '/' . MPG_Helper::mpg_get_site_url() .  $urls_array[$index];
        }
    }
}
