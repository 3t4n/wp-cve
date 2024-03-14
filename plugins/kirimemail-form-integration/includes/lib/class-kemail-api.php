<?php if (!defined('ABSPATH')) {
    exit;
}

class Kemail_Api
{
    public function api_request($url, $method, $args = [])
    {
        $url = KIRIMEMAIL_API_URL . $url;

        $timestamp = time();
        $api_username = isset($args['api_username']) ? $args['api_username'] : get_option('ke_wpform_api_username');
        $api_token = isset($args['api_token']) ? $args['api_token'] : get_option('ke_wpform_api_token');

        $auth_str = rtrim(ltrim($api_username)) . "::" . rtrim(ltrim($api_token)) . "::" . $timestamp;
        $token = hash_hmac("sha256", $auth_str, rtrim(ltrim($api_token)));

        $request_args = array(
            'method' => $method,
            'sslverify' => false,
            'timeout' => 10,
            'redirection' => 5,
            'httpversion' => '1.1',
            'headers' => array(
                'Auth-Id' => $api_username,
                'Auth-Token' => $token,
                'Timestamp' => $timestamp,
                'User-Agent' => 'KirimEmail/WooCommerce',
                'Offset' => isset($args['offset']) ? $args['offset'] : 0,
                'Search' => isset($args['search']) ? $args['search'] : '',
            ),
        );

        // attach arguments (in body or URL)
        if ($method === 'POST') {
            $request_args['body'] = http_build_query($args);
        }

        $raw_response = wp_remote_request($url, $request_args);

        if (is_wp_error($raw_response)) {
            $request_args['sslverify'] = true;
            $raw_response = wp_remote_request($url, $request_args);

            if (is_wp_error($raw_response)) {
                return false;
            }
        }

        $json = wp_remote_retrieve_body($raw_response);
        $result = json_decode($json, true);

        return $result;
    }

    public function get_form($args = array())
    {
        $form_data = $this->api_request('form', 'GET', $args);

        if ($form_data && $form_data['code'] == 200) {
            if (isset($args['raw_data'])) {
                return $form_data;
            }
            $result = [];
            //echo '<option value="">-- Dont Save --</option>';
            $result[] = [
                'id' => '',
                'text' => 'Disabled'
            ];
            foreach ($form_data['data'] as $form) {
                $result[] = [
                    'id' => json_encode(array('url' => $form['url'], 'id' => $form['id'], 'name' => $form['name'])),
                    'text' => $form['name']
                ];
                //echo '<option value="' . $form['url'] . '">' . $form['name'] . '</option>';
            }
            header('Content-Type: application/json');
            die(json_encode(array(
                'results' => $result,
                'pagination' => ['more' => false],
            )));
        } else {
            return - 1;
        }
    }

    public function get_landingpage($args = array())
    {
        $form_data = $this->api_request('landingpage', 'GET', $args);

        if ($form_data && $form_data['code'] == 200) {
            if (isset($args['raw_data'])) {
                return $form_data;
            }
            $result = [];
            //echo '<option value="">-- Dont Save --</option>';

            foreach ($form_data['data'] as $form) {
                $result[] = [
                    'id' => json_encode(array('url' => $form['url'], 'id' => $form['id'], 'name' => $form['name'])),
                    'text' => $form['name']
                ];
                //echo '<option value="' . $form['url'] . '">' . $form['name'] . '</option>';
            }
            header('Content-Type: application/json');
            die(json_encode(array(
                'results' => $result,
                'pagination' => ['more' => false],
            )));
        } else {
            return - 1;
        }
    }

    public function get_form_widget($instance)
    {
        $list_data = $this->api_request('form', 'GET');

        if ($list_data && $list_data['code'] == 200) {
            echo esc_html('<option value="">-- Dont Save --</option>');

            foreach ($list_data['data'] as $list) {
                echo esc_html('<option ' . selected($instance, $list['url']) . ' value="' . $list['url'] . '">' . $list['name'] . '</option>');
            }
        }
    }

    public function get_form_detail($id)
    {
        $form_data = $this->api_request('form/' . $id, 'GET');
        return $form_data;
    }

    public function get_list()
    {
        $list_data = $this->api_request('list', 'GET');
        return $list_data;
    }

    public function create_subscriber($data)
    {
        return $this->api_request('subscriber', 'POST', $data);
    }
}
