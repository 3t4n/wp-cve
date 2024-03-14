<?php
/**
 * Questo file è parte del plugin WooCommerce v3.x di Fattura24
 * Autore: Fattura24.com <info@fattura24.com>
 *
 * Modulo di invio segnalazioni a Fattura24 tramite TicketHd
 * usa api/api_send_ticket.php
 */
namespace fattura24;

if (!defined('ABSPATH')) {
    exit;
}

require_once FATT_24_CODE_ROOT . 'api/api_send_ticket.php';


global $message;
$message = '';

function fatt_24_get_ticket_data() {
    global $message;
    $f24_settings = fatt_24_get_settings();

    if (isset($_POST['invia'])) {
        $order_data = array();
        $order_items = array();
        $order_id = '';
        $text = '';
        $subject = '';
        $xml = '';
        $environment = '';
        $email = $_POST['replyto'];
        $username = $_POST['username'];
        $environment = fatt_24_getInfo();
        
        if (isset($_POST['order_id']) && !empty(trim($_POST['order_id']))) {
            $order_id = sanitize_text_field($_POST['order_id']);
            $order = wc_get_order($order_id);
            if ($order) {
                $order_data = print_r($order->get_data(), true);
                $order_items = print_r($order->get_items(), true);
                $country = $order->get_billing_country();
                $f24DocType = fatt_24_get_resulting_doc_type($country);
                $xml = fatt_24_order_to_XML($order, $f24DocType); // fac-simile dell'xml inviato alle API di Fattura24
            } else {
                $wrongId = sprintf(__('Order %s not found! Please enter id in the correct format', 'fattura24'), $order_id);
                $message = fatt_24_get_result_message($wrongId, 'error');
                return;
            }
        }
        if (isset($_POST['subject']) && !empty(trim($_POST['subject']))) {
            $subject = sanitize_text_field($_POST['subject']);

        }
        if (isset($_POST['testo_richiesta']) && !empty(trim($_POST['testo_richiesta']))) {
            $text = sanitize_text_field($_POST['testo_richiesta']);
        }   
        if (empty(trim($text))) {
            $message = fatt_24_get_result_message(__('You cannot send an empty message' , 'fattura24'), 'error');
            return;
        }

        $apiKey = get_option(FATT_24_OPT_API_KEY);
        $apiRes = '';
        
        if (empty($apiKey)) {
            $account_id = 'chiave_api_non_inserita';
        } else {
            $test = fatt_24_api_call('TestKey', array('apiKey' => $apiKey), FATT_24_API_SOURCE);
            $apiRes = is_array($test)? json_encode($test) : simplexml_load_string($test);
            $account_id = is_array($test)? 'server_api_non_raggiungibile' : 'non_registrato';
            
            // l'account Id mi viene restituito anche se returnCode == -1
            if (is_object($apiRes)) {
                $account_id = $apiRes->subscription->accountId ? (int) $apiRes->subscription->accountId : 'errore_generico';
            } 
        }
     
        $content = array(
            'subject' => $subject,
            'email' => $email,
            'username' => $username,
            'f24_api_response' => $apiRes,
            'plugin_settings' => $f24_settings,
            'order_id' => $order_id,
            'account_id' => $account_id,
            'source' => FATT_24_API_SOURCE,
            'text' => preg_replace('/\\\/', '', htmlentities($text, ENT_HTML401, '')),
            'order_data' => $order_data,
            'xml' => $xml,
            'environment' => $environment
        );

        //fatt_24_trace('contenuto :', fatt_24_array2string($content));
        $response = fatt_24_send_ticket($content);
        if ($response == 'message sent') {
            $message = fatt_24_get_result_message(__('Your message was sent successfully' , 'fattura24'), 'success');
        } else {
            $responseObj = json_decode($response);
            $error = '';
            if (is_object($responseObj)) {
               $error = $responseObj->errors->http_request_failed[0];
            }
            $message_text = sprintf(__('Unable to send message because of %s' , 'fattura24'), $error);
            $message = fatt_24_get_result_message($message_text, 'error');
            fatt_24_trace('Errore in fase di invio del messaggio :', $error);
        }
    }
}

function fatt_24_show_support() 
{
    global $message;
    $current_user = \wp_get_current_user();
    $user_email = $current_user->user_email;
    $user_name = $current_user->display_name;
    ?>
    <div class='wrap'>
    <h2></h2>
    <?php fatt_24_get_link_and_logo(__('', 'fatt-24-support'));
        echo fatt_24_build_nav_bar(); ?>
    <div>
        <table width="100%">
            <tr style="vertical-align: top;">
            <div>
                <td><h2><?php echo __('Use this form below to contact Fattura24 tech service', 'fattura24') ?></h2>
                    <form method='post'>
                        <label for="oggetto"><?php echo fatt_24_strong(__('Subject', 'fattura24')); ?> </label>
                        <input style="margin-left:87px; width:470px;" type="text" name="subject" id="oggetto" placeholder="<?php _e('Subject', 'fattura24') ?>" /><br /><br />
                        <label for="replyto"><?php echo fatt_24_strong(__('Reply to', 'fattura24')); ?></label>
                        <input style="margin-left:74px; width:470px;" type="text" name="replyto" id="replyto" value="<?php echo $user_email; ?>" readonly />
                        <input type="hidden" name="username" id="username" value="<?php echo $user_name; ?>" readonly /><br /><br />
                        <label for="order_id"><?php echo fatt_24_strong(__('Order id', 'fattura24')); ?></label>
                        <input style="margin-left:84px; width:470px;" type="text" name="order_id" id="order_id" placeholder="<?php _e('Order id (optional) - Please enter only one id', 'fattura24') ?>" /><br /><br />
                        <label style="vertical-align:middle;" for="messaggio"><?php echo fatt_24_strong(__('Message text', 'fattura24')); ?></label>
                        <textarea style="vertical-align:middle; margin-left:10px;" id="messaggio" name="testo_richiesta" placeholder="<?php echo __('Write down your message here', 'fattura24'); ?>" rows="8" cols="60"></textarea><br />
                        <input style="margin-top:10px; margin-left:142px;"  onclick="this.style.display = 'none';" type="submit" name="invia" id="invia" class="button button-primary" value="<?php echo __('Submit', 'fattura24'); ?>">  
                        <?php echo $message; ?>
                    </form>
                    <p style="font-size: 120%; text-align: justify;">
                    <div style="background-color: #ffc; border: 1px solid #ccd0d4; margin: 10px; padding: 15px; border-radius: 7px; font-size: 15px;">
                        <h4 style="text-align: center; margin: 0; padding: 5px;"><?php echo __('Notice', 'fattura24')  ?></h4>
                        <hr />
                        <?php  
                            echo __('Fill in the form fields to send a support request to Fattura24 tech service.', 'fattura24') . '<br />';
                            echo __('If you need so, you may specify the id of a WooCommerce order in which you found an error', 'fattura24' ) .'<br />';
                            echo __('If you specify the id of an order, please put only the id in the field (i.e.: no symbol, no additional text)', 'fattura24') .'<br />';
                            echo __('This form will create a text file and send it to Fattura24 as an attachment', 'fattura24') . '<br />';
                            echo __('In this file we collect an email address used to send a reply, environment variables, active plugins and order data (if order id field is filled)', 'fattura24') . '<br />';
                            echo __('No data will be sent if the textarea is left empty; the text file will be deleted from your server after form submission', 'fattura24') . '<br />';
                            echo __('By clicking on submit you accept to share these data with Fattura24 for all the time needed to answer your request', 'fattura24');
                        ?>  
                    </div>
                </p>
                </td>
            </div>    
            <td rowspan="2" style="width: 20%;"><?php echo fatt_24_infobox(); ?></td>
            </tr>
        </table> 
    </div>            
<?php 
}
