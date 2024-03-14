<?php


class PeepSo3_Error_Remote_Get {

    public $error = '';

    public function __construct($err_msg) {

        if (!PeepSo::get_option_new('system_enable_logging')) {
            return (FALSE);
        }

        $err_msg = maybe_serialize($err_msg);
        $message = $err_msg."\n";


        $peepso_dir = PeepSo::get_option('site_peepso_dir', WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'peepso', TRUE);
        $file = 'errors_remote_get';
        error_log ( "\n".$message, 3, $peepso_dir.'/'.$file.'.log');
        $this->error = $message;
    }


}

class PeepSo3_Helper_Remote_Get {

    public static function _($url, $args=[], $params=[]) {

        $peepso = 'peepso.com';

        $args = [
            'timeout' => $args['timeout'] ?? 10,
            'body' => $params,
        ];

        new PeepSo3_Error_Remote_Get("\n\n$url\n" . print_r($args, TRUE));

        $is_peepso = stristr($url, $peepso) ? TRUE : FALSE;
        $peepso_is_offline = !empty(PeepSo3_Mayfly::get('peepso_is_offline'));

        // SSSLVERIFY 0
        new PeepSo3_Error_Remote_Get("sslverify 0 start");
        $args['sslverify'] = FALSE;
        if($is_peepso && $peepso_is_offline) {
            $resp = new WP_Error('404','peepso_is_offline');
        } else {
            $resp = self::get($url, $args);
        }

        // SSSLVERIFY 1
        if (is_wp_error($resp)) {

            new PeepSo3_Error_Remote_Get("sslverify 0 failed " . $resp->get_error_message());
            new PeepSo3_Error_Remote_Get("\nsslverify 1 start");

            $args['sslverify'] = TRUE;
            if($is_peepso && $peepso_is_offline) {
                $resp = new WP_Error('404','peepso_is_offline');
            } else {
                $resp = self::get($url, $args);
            }

            if (is_wp_error($resp)) {
                new PeepSo3_Error_Remote_Get("sslverify 1 failed " . $resp->get_error_message());

                // Special fallback in case of PeepSo.com being down
                if ($is_peepso) {

                    // Mark PeepSo.com as offline for a week
                    PeepSo3_Mayfly::set('peepso_is_offline', 1, 7*24*3600);

                    // If PeepSoLicense.com is offline, that's the end
                    if(!empty(PeepSo3_Mayfly::get('peepsolicense_is_offline'))) {
                        return new PeepSo3_Error_Remote_Get('peepsolicense_is_offline');
                    }

                    // Attempt PeepSoLicense.com with SSL verify FALSE
                    $url = str_replace($peepso, 'peepsolicense.com', $url);
                    unset($args['sslverify']);
                    new PeepSo3_Error_Remote_Get("\n\n$url\n" . print_r($args, TRUE));

                    new PeepSo3_Error_Remote_Get("sslverify 0 start");
                    $args['sslverify'] = FALSE;
                    $resp = self::get($url, $args);

                    if (is_wp_error($resp)) {
                        // Attempt PeepSoLicense.com with SSL verify TRUE
                        $args['sslverify'] = TRUE;
                        new PeepSo3_Error_Remote_Get("sslverify 0 failed " . $resp->get_error_message());
                        new PeepSo3_Error_Remote_Get("\nsslverify 1 start");
                        $resp = self::get($url, $args);

                        // Everything failed, mark PeepSoLicense as offline for a week
                        if (is_wp_error($resp)) {
                            PeepSo3_Mayfly::set('peepsolicense_is_offline', 1, 7*24*3600);
                        }
                    }
                }
            }
        }

        if(is_wp_error($resp)){
            return new PeepSo3_Error_Remote_Get('Failure');
        } else {
            new PeepSo3_Error_Remote_Get('Success ' . $resp['body']);
            return $resp['body'];
        }
    }

    private static function get($url, $args=[]) {
        $start = microtime(TRUE);
        $resp = wp_remote_get(add_query_arg(array(), $url), $args);
        $end = microtime(TRUE);
        $elapsed = $end - $start;
        new PeepSo3_Error_Remote_Get("$elapsed ms");
        return $resp;
    }
}