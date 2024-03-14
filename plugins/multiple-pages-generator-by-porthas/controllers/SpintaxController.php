<?php


require_once(realpath(__DIR__) . '/../models/SpintaxModel.php');

class MPG_SpintaxController
{

    public static function mpg_generate_spintax()
    {

        check_ajax_referer( MPG_BASENAME, 'securityNonce' );

        $spintax_string = isset($_POST['spintaxString'])  ? $_POST['spintaxString'] : null;

        try {

            $final_string = MPG_SpintaxModel::mpg_generate_spintax_string($spintax_string);

            echo json_encode([
                'success' => true,
                'data' => $final_string
            ]);

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

    public static function get_cached_records_count($project_id)
    {
        global $wpdb;
        $query = $wpdb->get_results("SELECT  COUNT( DISTINCT `url` ) FROM {$wpdb->prefix}" .  MPG_Constant::MPG_SPINTAX_TABLE . " WHERE `project_id` = " . $project_id);
    
        return (array) $query[0]? array_values((array) $query[0])[0] : 0;
    }

    public static function mpg_spintax_shortcode($atts, $content)
    {

        try {
            global $wpdb;

            // Если есть шорткод, значит пользователь хочет использовать для этого проекта Spintax.

            $project_id = isset($atts['project_id']) ? (int) $atts['project_id']  : null;
            $block_id = isset($atts['block_id']) ? $atts['block_id'] : "1";

            if (!$project_id) {
                throw new Exception(__('"project_id" is not specified in shortcode. Please, make the shortcode like [mpg_spintax project_id="1" block_id="any-string"]...[/mpg_spintax]', 'mpg'));
            }

            // 1. Проверим, есть ли для запрашиваемого УРЛа и блока уже сгенерированная Spintax строка.

            $table_name = $wpdb->prefix .  MPG_Constant::MPG_SPINTAX_TABLE;
            $requested_url = MPG_Helper::mpg_get_request_uri();

            $spintax_string = $wpdb->get_results('SELECT `spintax_string`, `id` FROM ' . $table_name . ' WHERE `url` = "' . $requested_url . '" and `block_id` = "' . $block_id . '"');

            if ( ! empty( $spintax_string ) ) {
                $id = $spintax_string[0]->id;
                $spintax_content = $spintax_string[0]->spintax_string;
                if ( false !== strpos( $spintax_content, 'mpg_' ) ) {
                    $deleted = $wpdb->get_results( 'DELETE FROM ' . $table_name . ' WHERE `id` = ' . $id  );
                    $spintax_content = MPG_SpintaxModel::mpg_generate_spintax_string($content);
                    $requested_url = '/';
                }
                return $spintax_content;
            }

            // Делаем так, чтобы служебные УРЛы не попадали в базу.
            if ($requested_url !== '/' && strpos($requested_url, '/wp-json/wp/v2/') !== 0) {

                $spintax_string = MPG_SpintaxModel::mpg_generate_spintax_string($content);

                $wpdb->insert($table_name, array(
                    'project_id' => $project_id,
                    'block_id' => $block_id,
                    'url' => $requested_url,
                    'spintax_string' => $spintax_string
                ));

                return $spintax_string;
            }

            return;

        } catch (Exception $e) {

            do_action( 'themeisle_log_event', MPG_NAME, $e->getMessage(), 'debug', __FILE__, __LINE__ );

            return $e->getMessage();
        }
    }

    public static function mpg_flush_spintax_cache()
    {

        check_ajax_referer( MPG_BASENAME, 'securityNonce' );

        try {

            $project_id = isset($_POST['projectId']) ? (int) $_POST['projectId'] : null;

            if (!$project_id) {
                throw new Exception('Project ID is missing');
            }

            MPG_SpintaxModel::flush_cache_by_project_id($project_id);

            echo json_encode([
                'success' => true
            ]);

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
}
