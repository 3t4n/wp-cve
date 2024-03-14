<?php

namespace OXI_FLIP_BOX_PLUGINS\Classes;

/**
 * Description of Admin_Ajax
 *
 * @author biplo
 */
class Admin_Ajax
{

    /**
     * Define $wpdb
     *
     * @since 3.1.0
     */
    public $wpdb;

    /**
     * Database Parent Table
     *
     * @since 3.1.0
     */
    public $parent_table;

    /**
     * Database Import Table
     *
     * @since 3.1.0
     */
    public $import_table;

    /**
     * Database Import Table
     *
     * @since 3.1.0
     */
    public $child_table;

    public static function instance()
    {
        if (self::$instance == null) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function validate_post($rawdata)
    {
        if (is_array($rawdata)) :
            $rawdata = array_map(array($this, 'allowed_html'), $rawdata);
        else :
            $rawdata = sanitize_text_field($rawdata);
        endif;
        return $rawdata;
    }


    /**
     * Admin Settings
     * @return void
     */
    public function oxi_flipbox_support_massage($data = '', $styleid = '', $itemid = '')
    {

        if (!current_user_can('manage_options')) :
            return wp_die('You do not have permission.');
        endif;
        $rawdata = json_decode(stripslashes($data), true);
        $value = sanitize_text_field($rawdata['value']);
        update_option('oxi_flipbox_support_massage', $value);
        echo '<span class="oxi-confirmation-success"></span>';
        return;
    }

    /**
     * Admin Settings
     * @return void
     */
    public function oxi_addons_pre_loader($data = '', $styleid = '', $itemid = '')
    {

        if (!current_user_can('manage_options')) :
            return wp_die('You do not have permission.');
        endif;
        $rawdata = json_decode(stripslashes($data), true);
        $value = sanitize_text_field($rawdata['value']);
        update_option('oxi_addons_pre_loader', $value);
        echo '<span class="oxi-confirmation-success"></span>';
        return;
    }

    public function deactivate_license($key)
    {
        $api_params = array(
            'edd_action' => 'deactivate_license',
            'license' => $key,
            'item_name' => urlencode('Flipbox - Image Overlay'),
            'url' => home_url()
        );
        $response = wp_remote_post('https://www.oxilab.org', array('timeout' => 15, 'sslverify' => false, 'body' => $api_params));
        if (is_wp_error($response) || 200 !== wp_remote_retrieve_response_code($response)) {

            if (is_wp_error($response)) {
                $message = $response->get_error_message();
            } else {
                $message = esc_html('An error occurred, please try again.');
            }
            return $message;
        }
        $license_data = json_decode(wp_remote_retrieve_body($response));
        if ($license_data->license == 'deactivated') {
            delete_option('oxilab_flip_box_license_status');
            delete_option('oxilab_flip_box_license_key');
        }
        return 'success';
    }

    public function addons_rearrange($data = '', $styleid = '', $itemid = '')
    {
        $user_permission = $this->check_user_permission();
        if (!current_user_can($user_permission)) :
            return wp_die('You do not have permission.');
        endif;
        $list = explode(',', $data);
        foreach ($list as $value) {
            if (!(int) $list) :
                return;
            endif;
            $data = $this->wpdb->get_row($this->wpdb->prepare("SELECT * FROM $this->child_table WHERE id = %d ", $value), ARRAY_A);
            $this->wpdb->query($this->wpdb->prepare("INSERT INTO {$this->child_table} (styleid, files, css) VALUES (%d, %s, %s)", array($data['styleid'], $data['files'], $data['css'])));
            $redirect_id = $this->wpdb->insert_id;
            if ($redirect_id == 0) {
                return;
            }
            if ($redirect_id != 0) {
                $this->wpdb->query($this->wpdb->prepare("DELETE FROM $this->child_table WHERE id = %d", $value));
            }
        }
        echo 'success';
        return;
    }

    public function create_flip($data = '', $styleid = '', $itemid = '')
    {
        $user_permission = $this->check_user_permission();
        if (!current_user_can($user_permission)) :
            return wp_die('You do not have permission.');
        endif;
        if (!empty($styleid)) :
            if (!(int) $styleid) :
                return;
            endif;
            $newdata = $this->wpdb->get_row($this->wpdb->prepare('SELECT * FROM ' . $this->parent_table . ' WHERE id = %d ', $styleid), ARRAY_A);
            $this->wpdb->query($this->wpdb->prepare("INSERT INTO {$this->parent_table} (name, type, style_name, css) VALUES ( %s, %s, %s, %s)", array($data, 'flip', $newdata['style_name'], $newdata['css'])));
            $redirect_id = $this->wpdb->insert_id;
            if ($redirect_id > 0) :
                $child = $this->wpdb->get_results($this->wpdb->prepare("SELECT * FROM $this->child_table WHERE styleid = %d ORDER by id ASC", $styleid), ARRAY_A);
                foreach ($child as $value) {
                    $this->wpdb->query($this->wpdb->prepare("INSERT INTO {$this->child_table} (styleid, type, files, css) VALUES (%d, %s, %s, %s)", array($redirect_id, 'flip', $value['files'], $value['css'])));
                }
                echo admin_url("admin.php?page=oxi-flip-box-ultimate-new&styleid=$redirect_id");
            endif;
        else :
            $params = $this->validate_post(json_decode(stripslashes($data), true));
            if (!isset($params['style']['plugin']) || $params['style']['plugin'] != 'flipbox') :
                return;
            endif;
            $newname = $params['name'];
            $rawdata = $params['style'];
            $style = $rawdata['style'];
            $child = $rawdata['child'];
            $this->wpdb->query($this->wpdb->prepare("INSERT INTO {$this->parent_table} (name, type, style_name, css) VALUES ( %s, %s, %s, %s)", array($newname, 'flip', $style['style_name'], $style['css'])));
            $redirect_id = $this->wpdb->insert_id;
            if ($redirect_id > 0) :
                foreach ($child as $value) {
                    $this->wpdb->query($this->wpdb->prepare("INSERT INTO {$this->child_table} (styleid, type, files, css) VALUES (%d, %s, %s, %s)", array($redirect_id, 'flip', $value['files'], $value['css'])));
                }
                echo admin_url("admin.php?page=oxi-flip-box-ultimate-new&styleid=$redirect_id");
            endif;
        endif;
        return;
    }
    /**
     * Admin Settings
     * @return void
     */
    public function oxi_addons_user_permission($data = '', $styleid = '', $itemid = '')
    {

        if (!current_user_can('manage_options')) :
            return wp_die('You do not have permission.');
        endif;
        $rawdata = json_decode(stripslashes($data), true);
        $value = sanitize_text_field($rawdata['value']);
        update_option('oxi_addons_user_permission', $value);
        echo '<span class="oxi-confirmation-success"></span>';
        return;
    }

    /**
     * Admin Settings
     * @return void
     */
    public function oxi_addons_font_awesome($data = '', $styleid = '', $itemid = '')
    {

        if (!current_user_can('manage_options')) :
            return wp_die('You do not have permission.');
        endif;
        $rawdata = json_decode(stripslashes($data), true);
        $value = sanitize_text_field($rawdata['value']);
        update_option('oxi_addons_font_awesome', $value);
        echo '<span class="oxi-confirmation-success"></span>';
        return;
    }

    /**
     * Admin Settings
     * @return void
     */
    public function oxi_addons_google_font($data = '', $styleid = '', $itemid = '')
    {

        if (!current_user_can('manage_options')) :
            return wp_die('You do not have permission.');
        endif;
        $rawdata = json_decode(stripslashes($data), true);
        $value = sanitize_text_field($rawdata['value']);
        update_option('oxi_addons_google_font', $value);
        echo '<span class="oxi-confirmation-success"></span>';
        return;
    }

    public function check_user_permission()
    {
        $user_role = get_option('oxi_addons_user_permission');
        $role_object = get_role($user_role);
        $first_key = '';
        if (isset($role_object->capabilities) && is_array($role_object->capabilities)) {
            reset($role_object->capabilities);
            $first_key = key($role_object->capabilities);
        } else {
            $first_key = 'manage_options';
        }
        return $first_key;
    }

    public function active_data()
    {
        $user_permission = $this->check_user_permission();
        if (!current_user_can($user_permission)) :
            return wp_die('You do not have permission.');
        endif;

        global $wpdb;
        $this->wpdb = $wpdb;
        $this->parent_table = $this->wpdb->prefix . 'oxi_div_style';
        $this->child_table = $this->wpdb->prefix . 'oxi_div_list';
        $this->import_table = $this->wpdb->prefix . 'oxi_div_import';
    }

    public function shortcode_active($data = '', $styleid = '', $itemid = '')
    {
        $user_permission = $this->check_user_permission();
        if (!current_user_can($user_permission)) :
            return wp_die('You do not have permission.');
        endif;
        parse_str($data, $params);
        $styleid = (int) $params['oxiimportstyle'];
        if ($styleid) :
            $flip = 'flip';
            $this->wpdb->query($this->wpdb->prepare("INSERT INTO {$this->import_table} (type, name) VALUES (%s, %d)", array($flip, $styleid)));
            echo admin_url("admin.php?page=oxi-flip-box-ultimate-new#Style" . $styleid);
        else :
            echo 'Silence is Golden';
        endif;
        return;
    }

    public function shortcode_delete($data = '', $styleid = '', $itemid = '')
    {
        $user_permission = $this->check_user_permission();
        if (!current_user_can($user_permission)) :
            return wp_die('You do not have permission.');
        endif;
        $styleid = (int) $styleid;
        if ($styleid) :
            $this->wpdb->query($this->wpdb->prepare("DELETE FROM {$this->parent_table} WHERE id = %d", $styleid));
            $this->wpdb->query($this->wpdb->prepare("DELETE FROM {$this->child_table} WHERE styleid = %d", $styleid));
            echo 'done';
        else :
            echo 'Silence is Golden';
        endif;
        return;
    }

    public function shortcode_deactive($data = '', $styleid = '', $itemid = '')
    {
        $user_permission = $this->check_user_permission();
        if (!current_user_can($user_permission)) :
            return wp_die('You do not have permission.');
        endif;
        parse_str($data, $params);
        $styleid = (int) $params['oxideletestyle'];
        if ($styleid) :
            $this->wpdb->query($this->wpdb->prepare("DELETE FROM {$this->import_table} WHERE name = %d", $styleid));
            echo 'done';
        else :
            echo 'Silence is Golden';
        endif;
        return;
    }

    public function get_shortcode_export($data = '', $styleid = '', $itemid = '')
    {


        $user_permission = $this->check_user_permission();
        if (!current_user_can($user_permission)) :
            return wp_die('You do not have permission.');
        endif;

        $styleid = (int) $styleid;

        if ($styleid) :
            $st = $this->wpdb->get_row($this->wpdb->prepare("SELECT * FROM $this->parent_table WHERE id = %d", $styleid), ARRAY_A);
            $c = $this->wpdb->get_results($this->wpdb->prepare("SELECT * FROM $this->child_table WHERE styleid = %d ORDER by id ASC", $styleid), ARRAY_A);
            $filename = 'flipbox-template-' . $styleid . '.json';
            $files = [
                'style' => $st,
                'child' => $c,
                'plugin' => 'flipbox'
            ];
            $finalfiles = json_encode($files);
            $this->send_file_headers($filename, strlen($finalfiles));
            @ob_end_clean();
            flush();
            echo $finalfiles;
            die;
        else :
            return 'Silence is Golden';
        endif;
    }

    /**
     * Send file headers.
     *
     *
     * @param string $file_name File name.
     * @param int    $file_size File size.
     */
    private function send_file_headers($file_name, $file_size)
    {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $file_name);
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . $file_size);
    }

    public function post_json_import($params)
    {

        if (!current_user_can('manage_options')) :
            return wp_die('You do not have permission.');
        endif;

        if (!is_array($params) || $params['style']['type'] != 'flip') {
            return new \WP_Error('file_error', 'Invalid Content In File');
        }
        $this->active_data();

        $style = $params['style'];
        $child = $params['child'];
        $this->wpdb->query($this->wpdb->prepare("INSERT INTO {$this->parent_table} (name, type, style_name, css) VALUES ( %s, %s, %s, %s)", array($style['name'], $style['type'], $style['style_name'], $style['css'])));
        $redirect_id = $this->wpdb->insert_id;
        if ($redirect_id > 0) :
            foreach ($child as $value) {
                $this->wpdb->query($this->wpdb->prepare("INSERT INTO {$this->child_table} (styleid, type, files, css) VALUES (%d, %s, %s, %s)", array($redirect_id, $value['type'], $value['files'], $value['css'])));
            }
        endif;
        $check_import = $this->wpdb->get_row($this->wpdb->prepare("SELECT * FROM  $this->import_table WHERE type = %s AND name = %s", 'flip', str_replace('style', '', $style['style_name'])), ARRAY_A);
        if (!is_array($check_import)) :
            $this->wpdb->query($this->wpdb->prepare("INSERT INTO {$this->import_table} (type, name) VALUES ( %s, %s)", array('flip', str_replace('style', '', $style['style_name']))));
        endif;
        return admin_url("admin.php?page=oxi-flip-box-ultimate-new&styleid=$redirect_id");
    }

    /**
     * Admin License
     * @return void
     */
    public function oxi_license($data = '', $styleid = '', $itemid = '')
    {
        $user_permission = $this->check_user_permission();
        if (!current_user_can($user_permission)) :
            return wp_die('You do not have permission.');
        endif;
        $rawdata = json_decode(stripslashes($data), true);
        $new = sanitize_text_field($rawdata['license']);
        $old = get_option('oxilab_flip_box_license_key');
        $status = get_option('oxilab_flip_box_license_status');
        if ($new == '') :
            if ($old != '' && $status == 'valid') :
                $this->deactivate_license($old);
            endif;
            delete_option('oxilab_flip_box_license_key');
            $data = ['massage' => '<span class="oxi-confirmation-blank"></span>', 'text' => ''];
        else :
            update_option('oxilab_flip_box_license_key', $new);
            delete_option('oxilab_flip_box_license_status');
            $r = $this->activate_license($new);
            if ($r == 'success') :
                $data = ['massage' => '<span class="oxi-confirmation-success"></span>', 'text' => 'Active'];
            else :
                $data = ['massage' => '<span class="oxi-confirmation-failed"></span>', 'text' => $r];
            endif;
        endif;

        echo json_encode($data);
        return;
    }

    public function activate_license($key)
    {
        $api_params = array(
            'edd_action' => 'activate_license',
            'license' => $key,
            'item_name' => urlencode('Flipbox - Image Overlay'),
            'url' => home_url()
        );

        $response = wp_remote_post('https://www.oxilab.org', array('timeout' => 15, 'sslverify' => false, 'body' => $api_params));

        if (is_wp_error($response) || 200 !== wp_remote_retrieve_response_code($response)) {
            if (is_wp_error($response)) {
                $message = $response->get_error_message();
            } else {
                $message = esc_html('An error occurred, please try again.');
            }
        } else {
            $license_data = json_decode(wp_remote_retrieve_body($response));

            if (false === $license_data->success) {

                switch ($license_data->error) {

                    case 'expired':

                        $message = esc_html('Your license key expired');
                        break;

                    case 'revoked':

                        $message = esc_html('Your license key has been disabled.');
                        break;

                    case 'missing':

                        $message = esc_html('Invalid license.');
                        break;

                    case 'invalid':
                    case 'site_inactive':

                        $message = esc_html('Your license is not active for this URL.');
                        break;

                    case 'item_name_mismatch':

                        $message = esc_html('This appears to be an invalid license key ');
                        break;

                    case 'no_activations_left':

                        $message = esc_html('Your license key has reached its activation limit.');
                        break;

                    default:

                        $message = esc_html('An error occurred, please try again.');
                        break;
                }
            }
        }

        if (!empty($message)) {
            return $message;
        }
        update_option('oxilab_flip_box_license_status', $license_data->license);
        return 'success';
    }

    /**
     * Constructor of plugin class
     *
     * @since 3.1.0
     */
    public function __construct($type = '', $data = '', $styleid = '', $itemid = '')
    {
        if (!empty($type) && !empty($data)) :

            $user_permission = $this->check_user_permission();
            if (!current_user_can($user_permission)) :
                return wp_die('You do not have permission.');
            endif;

            global $wpdb;
            $this->wpdb = $wpdb;
            $this->parent_table = $this->wpdb->prefix . 'oxi_div_style';
            $this->child_table = $this->wpdb->prefix . 'oxi_div_list';
            $this->import_table = $this->wpdb->prefix . 'oxi_div_import';
            $this->$type($data, $styleid, $itemid);
        endif;
    }

    public function array_replace($arr = [], $search = '', $replace = '')
    {
        array_walk($arr, function (&$v) use ($search, $replace) {
            $v = str_replace($search, $replace, $v);
        });
        return $arr;
    }

    public function allowed_html($rawdata)
    {
        $allowed_tags = array(
            'a' => array(
                'class' => array(),
                'href' => array(),
                'rel' => array(),
                'title' => array(),
            ),
            'abbr' => array(
                'title' => array(),
            ),
            'b' => array(),
            'br' => array(),
            'blockquote' => array(
                'cite' => array(),
            ),
            'cite' => array(
                'title' => array(),
            ),
            'code' => array(),
            'del' => array(
                'datetime' => array(),
                'title' => array(),
            ),
            'dd' => array(),
            'div' => array(
                'class' => array(),
                'title' => array(),
                'style' => array(),
                'id' => array(),
            ),
            'table' => array(
                'class' => array(),
                'id' => array(),
                'style' => array(),
            ),
            'button' => array(
                'class' => array(),
                'type' => array(),
                'value' => array(),
            ),
            'thead' => array(),
            'tbody' => array(),
            'tr' => array(),
            'td' => array(),
            'dt' => array(),
            'em' => array(),
            'h1' => array(),
            'h2' => array(),
            'h3' => array(),
            'h4' => array(),
            'h5' => array(),
            'h6' => array(),
            'i' => array(
                'class' => array(),
            ),
            'img' => array(
                'alt' => array(),
                'class' => array(),
                'height' => array(),
                'src' => array(),
                'width' => array(),
            ),
            'li' => array(
                'class' => array(),
            ),
            'ol' => array(
                'class' => array(),
            ),
            'p' => array(
                'class' => array(),
            ),
            'q' => array(
                'cite' => array(),
                'title' => array(),
            ),
            'span' => array(
                'class' => array(),
                'title' => array(),
                'style' => array(),
            ),
            'strike' => array(),
            'strong' => array(),
            'ul' => array(
                'class' => array(),
            ),
        );
        if (is_array($rawdata)) :
            return $rawdata = array_map(array($this, 'allowed_html'), $rawdata);
        else :
            return wp_kses($rawdata, $allowed_tags);
        endif;
    }
}
