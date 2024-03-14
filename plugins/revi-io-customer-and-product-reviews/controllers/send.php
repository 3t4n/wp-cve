<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

class revi_send
{
    var $REVI_API_URL;
    var $prefix;
    var $wpdb;
    var $revimodel;
    var $subscription;

    public function __construct($encoded_string)
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->prefix = $wpdb->prefix;
        $this->REVI_API_URL = REVI_API_URL;
        $this->revimodel = new revimodel();

        $this->subscription = get_option('REVI_SUBSCRIPTION');

        $this->revimodel->updateConfiguration();

        if (isset($_REQUEST['email_tester'])) {
            $this->email_tester($encoded_string);
        }

        $sync_result = $this->sendOrderMail($encoded_string);

        echo $sync_result;
    }

    public function email_tester($encoded_string)
    {
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
        $decoded_string = json_decode(base64_decode($encoded_string));
        $mail_data = $decoded_string;

        $lang = $mail_data->lang;


        if (isset($_REQUEST['lang']) && $_REQUEST['lang'])
            $lang = $_REQUEST['lang'];
        $data = array(
            'lang' => $lang,
            'tester' => true,
        );

        $mail_data = $this->revimodel->getMailData($data);


        $from = $mail_data->from;
        // Por las limitaciones de 70 characteres por línea de la función mail
        $header_from_temp = "From: " . get_option('blogname') . " <$from>\r\n";
        if (strlen($header_from_temp > 66)) {
            $header_from_temp = "From: $from\r\n";
        }
        $header_organization_temp = "Organization: " . get_option('blogname') . "\r\n";
        if (strlen($header_organization_temp > 66)) {
            $header_organization_temp = "Organization: " . substr(get_option('blogname'), 0, 50) . "\r\n";
        }

        $headers  = $header_from_temp;
        $headers .= "Reply-To: $from\r\n";
        $headers .= "Return-Path: $from\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= $header_organization_temp;
        $headers .= "X-Priority: 3\r\n";
        $headers .= "X-Mailer: PHP" . phpversion();

        if (mail($mail_data->to, $mail_data->subject, $mail_data->message, $headers)) {
            echo "Tester email enviado de " . $mail_data->from . " a " . $mail_data->to . ' con lang:' . $lang;
        } else {
            echo 'no enviado';
        }

        die();
    }

    private function sendOrderMail($encoded_string)
    {
        $mail_data = base64_decode($encoded_string);
        $mail_data = json_decode($mail_data);

        $order = $this->revimodel->getOrder($mail_data->id_order);

        if (!empty($order)) {
            $order->lang = get_post_meta($order->id_order, 'wpml_language', true);
            $order->iso_country = get_post_meta($order->id_order, '_shipping_country', true);

            if (empty($mail_data->lang)) {
                if (!empty($order->lang) && strlen($order->lang) >= 2) {
                    $mail_data->lang = substr($order->lang, 0, 2);
                } elseif (!empty($order->iso_country) && strlen($order->iso_country) >= 2) {
                    $mail_data->lang = substr($order->iso_country, 0, 2);
                } else {
                    //De momento el iso_code es el language default
                    $mail_data->lang = get_option('REVI_LANG');
                }
            }

            $mail_data->customer_name = get_post_meta($order->id_order, '_billing_first_name', true);
        }

        try {
            $data = array(
                'id_order' => $mail_data->id_order,
                'email' => $mail_data->email,
                'type' => $mail_data->type,
            );
            $email_check = $this->revimodel->reviCURL($this->REVI_API_URL . 'wsapi/emailcheck', "POST", $data);
            $email_check = json_decode($email_check);
            $email_check_data = $email_check->data;
        } catch (Exception $e) {
            echo $e;
        }


        if ($email_check_data->send_mail) {
            return $this->sendMail($mail_data, $encoded_string);
        }
    }

    private function sendMail($mail_data, $encoded_string)
    {
        $data = array(
            'id_order' => $mail_data->id_order,
            'id_shop' => $mail_data->id_shop,
            'id_store' => get_option('REVI_SELECTED_STORE'),
            'encoded_string' => $encoded_string,
            'module' => true,
        );

        $mail_send_data = $this->revimodel->getMailData($data);

        $from = $mail_send_data->from;
        // Por las limitaciones de 70 characteres por línea de la función mail
        $header_from_temp = "From: " . get_option('blogname') . " <$from>\r\n";
        if (strlen($header_from_temp > 66)) {
            $header_from_temp = "From: $from\r\n";
        }
        $header_organization_temp = "Organization: " . get_option('blogname') . "\r\n";
        if (strlen($header_organization_temp > 66)) {
            $header_organization_temp = "Organization: " . substr(get_option('blogname'), 0, 50) . "\r\n";
        }

        $headers  = $header_from_temp;
        $headers .= "Reply-To: $from\r\n";
        $headers .= "Return-Path: $from\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= $header_organization_temp;
        $headers .= "X-Priority: 3\r\n";
        $headers .= "X-Mailer: PHP" . phpversion();

        $data = array(
            'id_order' => $mail_data->id_order,
            'id_shop' => $mail_data->id_shop,
            'id_store' => $mail_data->id_store,
            'token' => $mail_data->token,
            'id_mail_log' => $mail_send_data->id_mail_log,
            'lang' => $mail_send_data->lang,
            'mail_to' => $mail_send_data->to,
        );

        if (mail($mail_send_data->to, $mail_send_data->subject, $mail_send_data->message, $headers)) {
            $data['sent'] = 1;

            $text_result = 'ID: ' . $mail_data->id_order . ') Se ha enviado correctamente el email a: ' . $mail_send_data->to . ' (' . date('Y-m-d H:i:s') . ')';
            $this->revimodel->reviCURL($this->REVI_API_URL . 'wsapi/sendemail', "POST", $data);
        } else {
            $data['sent'] = -1;

            $text_result = 'No se ha enviado el email a: ' . $mail_send_data->to;
            $this->revimodel->reviCURL($this->REVI_API_URL . 'wsapi/sendemail', "POST", $data);
        }

        return $text_result;
    }
}
