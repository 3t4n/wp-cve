<?php


require_once(realpath(__DIR__ . '/../models/CacheModel.php'));

class MPG_CacheController
{
    public static function mpg_enable_cache()
    {
        check_ajax_referer( MPG_BASENAME, 'securityNonce' );

        try {

            $project_id = isset($_POST['projectId']) ? (int) $_POST['projectId'] : null;

            if (!$project_id) {
                throw new Exception('Project ID is missing');
            }

            $type = isset($_POST['type']) ? $_POST['type'] : null;

            if (!in_array($type, ['disk', 'database'])) {
                throw new Exception('Unsupported type of cache');
            }

            // Надо поставить (обновить) тип активного кеширования в БД
            MPG_CacheModel::mpg_set_current_caching_type($project_id, $type);

            // Теперь, когда активирован новый тип кеша, надо очистить следы от старого
            // То есть если раньше был кеш на диске, а сейчас включили БД, то надо очистить папку.

            self::mpg_flush_core($project_id, $type);

            echo json_encode([
                'success' => true,
                'data' =>  ucwords($type) . ' ' . __('cache was successfully enabled', 'mpg')
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




    public static function mpg_disable_cache()
    {
        check_ajax_referer( MPG_BASENAME, 'securityNonce' );

        try {

            $project_id = isset($_POST['projectId']) ? (int) $_POST['projectId'] : null;

            if (!$project_id) {
                throw new Exception('Project ID is missing');
            }

            $type = isset($_POST['type']) ? $_POST['type'] : null;

            if (!in_array($type, ['disk', 'database'])) {
                throw new Exception('Unsupported type of cache');
            }


            // Надо поставить (обновить) тип активного кеширования в БД.
            // Поскольку это функция отключения кеша, то ставим none
            MPG_CacheModel::mpg_set_current_caching_type($project_id, 'none');


            self::mpg_flush_core($project_id, $type);

            echo json_encode([
                'success' => true,
                'data' =>  __('Caching was successfully disabled', 'mpg')
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

    public static function mpg_flush_core($project_id, $type)
    {

        if ($type === 'disk') {

            $cache_path = MPG_CACHE_DIR . $project_id;

            MPG_CacheModel::mpg_flush_disk_cache($cache_path);
        } elseif ($type === 'database') {
            MPG_CacheModel::mpg_delete_cached_records_from_db($project_id);
        }
    }

    public static function mpg_flush_cache()
    {
        check_ajax_referer( MPG_BASENAME, 'securityNonce' );

        try {

            $project_id = isset($_POST['projectId']) ? (int) $_POST['projectId'] : null;

            if (!$project_id) {
                throw new Exception('Project ID is missing');
            }

            $type = isset($_POST['type']) ? $_POST['type'] : null;

            if (!in_array($type, ['disk', 'database'])) {
                throw new Exception('Unsupported type of cache');
            }

            self::mpg_flush_core($project_id, $type);

            echo json_encode([
                'success' => true,
                'data' =>  __('Cache was successfully flushed', 'mpg')
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


    public static function mpg_flush_cache_on_template_update($post_id)
    {

        global $wpdb;
        $projects = $wpdb->get_results("SELECT id, template_id, cache_type FROM {$wpdb->prefix}" .  MPG_Constant::MPG_PROJECTS_TABLE);

        if ($projects) {
            foreach ($projects as $project) {
                if ((int) $project->template_id === (int) $post_id) {
                    self::mpg_flush_core($project->id, $project->cache_type);
                }
            }
        }
    }

    public static function mpg_cache_statistic()
    {
        check_ajax_referer( MPG_BASENAME, 'securityNonce' );

        try {

            $project_id = isset($_POST['projectId']) ? (int) $_POST['projectId'] : null;

            if (!$project_id) {
                throw new Exception('Project ID is missing');
            }

            $type = isset($_POST['type']) ? $_POST['type'] : null;

            if (!in_array($type, ['disk', 'database'])) {
                throw new Exception('Unsupported type of cache');
            }

            $stats = [];
            if ($type === 'disk') {
                $cache_folder = MPG_CACHE_DIR . $project_id;

                $fi = null;
                $bytestotal = 0;

                if (is_dir($cache_folder)) {
                    //  Считаем фалйы
                    $fi = new FilesystemIterator($cache_folder, FilesystemIterator::SKIP_DOTS);
                    $path = realpath($cache_folder);
                    if ($path !== false && $path != '' && file_exists($path)) {
                        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)) as $object) {
                            $bytestotal += $object->getSize();
                        }
                    }
                }

                $stats = [
                    // -1 чтобы пропустить файл index.php 
                    'pagesCount' => $fi ? iterator_count($fi) - 1 : 0,
                    'pagesSize' => round($bytestotal / 1024 / 1024, 3) . __('Mb', 'mpg')
                ];
            } elseif ($type === 'database') {

                $stats = [
                    'pagesCount' => MPG_CacheModel::mpg_count_cached_pages_by_project_id($project_id)
                ];
            }


            echo json_encode([
                'success' => true,
                'data' => $stats
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
}
