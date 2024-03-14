<?php

// Don't access this directly, please

if (!defined('ABSPATH')) {
    exit;
} 

$wfic_datatosend_url = "https://api-v2.fattureincloud.it/c/". $company_id ."/issued_documents/". $idfattura ."/email";


$data_tosend_wfic_postfields = "{\"data\":{\"sender_email\":\"$email_mittente\",
  \"recipient_email\":\"$email_destinatario\",
  \"subject\":\"$oggetto_email\",
  \"body\":\"$email_body\",
  \"include\":{\"document\":true,
               \"delivery_note\":false,
               \"attachment\":false,
               \"accompanying_invoice\":false
              },
  \"attach_pdf\":true,
  \"send_copy\":false}
}";

error_log("invio email con oggetto => " .$oggetto_email);

##########################################################################################

include plugin_dir_path(__FILE__) . '/send_data.php';

#######################################################################################

