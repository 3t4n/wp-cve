<?php

class MPG_SearchController
{

    public static function render()
    {
        require_once(realpath(__DIR__) . '/../views/search/index.php');
    }

    public static function is_res_found($case_sensitive, $replaced_shortcodes_string, $search_string)
    {
        if ($case_sensitive) {
            return strpos($replaced_shortcodes_string, $search_string) !== false;
        } else {
            return stripos($replaced_shortcodes_string, $search_string) !== false;
        }
    }

    public static function mpg_search($search_string = null, $limit = 10, $case_sensitive = true, $featured_image_url = null, $mpg_excerpt_length = 0 )
    {

        try {
            if ($search_string) {
                $search_string = sanitize_text_field($search_string);
            } else if (isset($_GET['s'])) {
                $search_string = sanitize_text_field($_GET['s']);
            }

            if (!$search_string) {
                return []; // it's mean, that it's not a search page 
            }

            global $wpdb;
            $projects = $wpdb->get_results("SELECT id, template_id, source_path, urls_array FROM {$wpdb->prefix}" .  MPG_Constant::MPG_PROJECTS_TABLE . ' WHERE `participate_in_search` = true');

            // Params
            $search_in_links = true;
            $search_in_titles = true;
            $search_in_content = apply_filters( 'mpg_search_in_post_content', ! empty( $mpg_excerpt_length ) );
            $entities_ids = [];

            if ($projects) {
                foreach ($projects as $project) {
                    array_push($entities_ids, [
                        'template_id' => (int) $project->template_id,
                        'project_id' => (int) $project->id,
                        'urls_array' => $search_in_links ? json_decode($project->urls_array, true) : null
                    ]);
                }
            }

            $results = [];

            foreach ($entities_ids as $entity) {

                $template = get_post($entity['template_id']);
                if ($template) {
                    $template_name = $template->post_title;
                    $template_content = $template->post_content;
                    $author_email = get_the_author_meta("user_email", $template->post_author);
                    $author_nickname = get_the_author_meta("nickname", $template->post_author);
                    $author_url =  get_the_author_meta("user_url", $template->post_author); // Нормально не работает

                    if ($search_in_titles) {

                        // Если в названии поста \ страницы, которая установлена как шаблон нет шорткодов,
                        // то и нет смысла ее обрабатывать, т.к. мы точно не знаем какую ссылку на нее дать
                        // Возможно, одна из этих страниц будет поймана по ссылке, или по тексту
                        preg_match_all('/{{mpg_\S+}}/m', $template_name, $matches, PREG_SET_ORDER, 0);

                        if ( empty( $matches ) && $search_in_content ) {
                            // Search in post content.
                            preg_match_all('/{{mpg_\S+}}/m', $template_content, $matches, PREG_SET_ORDER, 0);
                        }

                        if (!empty($matches)) {

                            $project = MPG_ProjectModel::mpg_get_project_by_id($entity['project_id']);
                            $dataset_array = MPG_Helper::mpg_get_dataset_array( reset( $project ) );
                            $headers = $project[0]->headers;
                            $headers_array = json_decode($headers);
                            $headers_array = array_map(function ($raw_header) {
                                $header = str_replace(' ', '_', strtolower($raw_header));
                                if (strpos($header, 'mpg_') !== 0) {
                                    $header = 'mpg_' . $header;
                                }
                                return  $header;
                            }, $headers_array);

                            // Get header number by name
                            $featured_image_header_position = array_search($featured_image_url, $headers_array);


                            $short_codes = MPG_CoreModel::mpg_shortcodes_composer($headers_array);
                            $urls_array = $project[0]->urls_array ? json_decode($project[0]->urls_array) : [];

                            foreach ($urls_array as $index => $url) {

                                $strings = $dataset_array[$index + 1];

                                $replaced_shortcodes_string_title = preg_replace($short_codes, $strings, $template_name);
                                $replaced_shortcodes_string = $replaced_shortcodes_string_title;
                                if ( ! self::is_res_found($case_sensitive, $replaced_shortcodes_string, $search_string) && $search_in_content ) {
                                    $replaced_shortcodes_string = preg_replace($short_codes, $strings, $template_content);
                                }
                                if (self::is_res_found($case_sensitive, $replaced_shortcodes_string, $search_string)) {

                                    // Check is this record already presented in the array before adding
                                    $found = current(array_filter($results, function ($item) use ($replaced_shortcodes_string) {
                                        return isset($item['page_title']) && $item['page_title'] === $replaced_shortcodes_string;
                                    }));

                                    if (!$found) {
                                        $results[] = [
                                            'page_title' => $replaced_shortcodes_string_title,
                                            'page_url' => MPG_Helper::mpg_get_base_url(false) . $url,
                                            'page_excerpt' => MPG_Helper::mpg_prepare_post_excerpt($short_codes, $strings, $template_content),
                                            'page_author_nickname' => $author_nickname,
                                            'page_author_email' => $author_email,
                                            'page_author_url' => $author_url,
                                            'page_date' => get_the_date('', $template->ID),
                                            'page_featured_image' => $featured_image_url && $featured_image_header_position !== false ? $strings[$featured_image_header_position] : null
                                        ];

                                        if (count($results) >= $limit) {
                                            break 2;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            return [
                'total' => count($results),
                'results' => $results
            ];
        } catch (Exception $e) {

            do_action( 'themeisle_log_event', MPG_NAME, $e->getMessage(), 'debug', __FILE__, __LINE__ );

            throw new Exception($e);
        }
    }

    public static function mpg_search_shortcode($args)
    {
        if (class_exists('MPG_ProjectController')) {

            $search_string  = isset($args['s']) ? $args['s'] : null;
            $limit          = isset($args['limit']) ? (int) $args['limit'] : 10;
            $base_url       = isset($atts['base-url']) ? (string) $atts['base-url']  : MPG_Helper::mpg_get_base_url(true);
            $case_sensitive = isset( $args['case_sensitive'] ) && $args['case_sensitive'] === '1' ? true : false;


            $search = self::mpg_search($search_string, $limit, $case_sensitive);
            if (!isset($search['total']) || $search['total'] === 0) {
                return;
            }

            $response = '<div class="mpg-search-results">';
            $response .= '<span class="mpg-search-results-count">' . __('Total results:', 'mpg') . ' ' . $search['total'] . '</span>';
            foreach ($search['results'] as $index => $result) {

                $response .= '<div class="mpg-search-results-row">';
                $response .= '<h2 class="mpg-page-title"><a class="mpg-page-link" href="' . $base_url . $result['guid'] . '">' . $result['post_title'] . '</a></h2>' .
                    '<p class="mpg-page-excerpt">' . $result['post_excerpt'] . '</p>';
                $response .= '</div>';
                if ($index >= $limit - 1) {
                    break;
                }
            }

            $response .= '</div>';

            return $response;
        }
    }

    public static function mpg_search_ajax()
    {
        check_ajax_referer( MPG_BASENAME, 'securityNonce' );

        try {

            $search_query = isset($_POST['s']) ? sanitize_text_field($_POST['s']) : null;

            $mpg_search_settings = get_option( 'mpg_search_settings', array() );
            $search_limit =  $mpg_search_settings['mpg_ss_results_count'];
            $case_sensitive = filter_var($mpg_search_settings['mpg_ss_is_case_sensitive'], FILTER_VALIDATE_BOOLEAN);
            $featured_image_url = $mpg_search_settings['mpg_ss_featured_image_url'];
            $mpg_excerpt_length = isset( $mpg_search_settings['mpg_ss_excerpt_length'] ) ? $mpg_search_settings['mpg_ss_excerpt_length'] : 0;
            echo json_encode([
                'success' => true,
                'data' =>  self::mpg_search( $search_query, $search_limit, $case_sensitive, $featured_image_url, $mpg_excerpt_length ),
            ]);
        } catch (Exception $e) {

            do_action( 'themeisle_log_event', MPG_NAME, $e->getMessage(), 'debug', __FILE__, __LINE__ );

            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }

        wp_die();
    }

    public static function mpg_search_settings_upset_options()
    {
        check_ajax_referer( MPG_BASENAME, 'securityNonce' );

        try {

            $result_template = isset($_POST['mpg_search_settings_result_template']) ? $_POST['mpg_search_settings_result_template'] : null;

            $intro_content = isset($_POST['mpg_ss_intro_content']) ? $_POST['mpg_ss_intro_content'] : null;
            $results_container = isset($_POST['mpg_ss_results_container']) ? sanitize_text_field($_POST['mpg_ss_results_container']) : null;
            $excerpt_length =  isset($_POST['mpg_ss_excerpt_length']) ? (int) $_POST['mpg_ss_excerpt_length'] : null;
            $search_results_count =  isset($_POST['mpg_ss_results_count']) ? (int) $_POST['mpg_ss_results_count'] : null;
            $search_is_case_sensitive =  isset($_POST['mpg_ss_is_case_sensitive']) ? filter_var($_POST['mpg_ss_is_case_sensitive'], FILTER_VALIDATE_BOOLEAN) : false;
            $featured_image_url =  isset($_POST['mpg_ss_featured_image_url']) ? sanitize_text_field($_POST['mpg_ss_featured_image_url']) : null;

            if ($featured_image_url) {


                $featured_image_url = str_replace(' ', '_', strtolower($featured_image_url));
                if (strpos($featured_image_url, 'mpg_') !== 0) {
                    $featured_image_url = 'mpg_' . $featured_image_url;
                }
            }

            update_option('mpg_search_settings', [
                'mpg_ss_result_template' => $result_template,
                'mpg_ss_intro_content' => $intro_content,
                'mpg_ss_results_container' => $results_container,
                'mpg_ss_excerpt_length' => $excerpt_length,
                'mpg_ss_results_count' => $search_results_count,
                'mpg_ss_is_case_sensitive' => $search_is_case_sensitive,
                'mpg_ss_featured_image_url' => $featured_image_url
            ]);

            echo json_encode([
                'success' => true
            ]);
            //
            wp_die();
        } catch (Exception $e) {

            do_action( 'themeisle_log_event', MPG_NAME, $e->getMessage(), 'debug', __FILE__, __LINE__ );

            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
            wp_die();
        }
    }

    public static function mpg_search_settings_get_options()
    {
        check_ajax_referer( MPG_BASENAME, 'securityNonce' );

        try {

            $data = get_option('mpg_search_settings');
            
            echo json_encode([
                'success' => true,
                'data' => $data
            ]);

            wp_die();
            //
        } catch (Exception $e) {

            do_action( 'themeisle_log_event', MPG_NAME, $e->getMessage(), 'debug', __FILE__, __LINE__ );

            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
            wp_die();
        }
    }
}
