<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class esig_hold_payment {

    protected static $instance = null;

    /**
     * Returns an instance of this class.
     *
     * @since     0.1
     *
     * @return    object    A single instance of this class.
     */
    public static function get_instance() {

        // If the single instance hasn't been set, set it now.
        if (null == self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    private function __construct() {
        //add_action( 'woocommerce_checkout_process',array($this,"pre_process_checkout") );
        add_action('add_meta_boxes', array($this, "esig_order_meta_box"));
        add_action('esig_woo_order_agreement_action', array($this, "esig_order_meta_box"));
        add_action('wp_ajax_esig_create_order_agreement', array($this, 'create_order_agreement'));
    }

    public function create_order_agreement() {

        $nonce = esigpost('esig_woo_nonce');

        if (!wp_verify_nonce($nonce, 'esig-woo-order')) {
            wp_die(-1);
        }

        $postId = esigpost('esig_woo_order');
        $order = new WC_Order($postId);
        $emailAddress = $order->get_billing_email();
        $firstName = $order->get_billing_first_name();
        $lastName = $order->get_billing_last_name();
        $signerName = apply_filters("esig_get_woo_signer_name", $firstName . " " . $lastName, $postId);

        $recipient = array();

        $docs = get_post_meta($postId, "_esig_after_checkout_doc_list", true);
        $contracts = json_decode($docs);
        foreach ($contracts as $old_doc_id => $status) {

            if ($status == "no" && is_numeric($old_doc_id)) {

                // check for duplicate if already created continue to next agreement. 
                $already_created = get_post_meta($postId, "_esig-agreement-created-" . $old_doc_id, true);

                if ($already_created) {
                    continue;
                }


                $old_doc = WP_E_Sig()->document->getDocument($old_doc_id);
                // Copy the document
                $doc_id = WP_E_Sig()->document->copy($old_doc_id);

                $old_doc_timezone = WP_E_Sig()->document->esig_get_document_timezone($old_doc_id);

                // save new doc timezone 
                WP_E_Sig()->meta->add($doc_id, 'esig-timezone-document', $old_doc_timezone);

                // Create the user
                $recipient = array(
                    "user_email" => $emailAddress,
                    "first_name" => $signerName,
                    "document_id" => $doc_id,
                    "last_name" => '',
                    "company_name" => ''
                );

                $recipient['id'] = WP_E_Sig()->user->insert($recipient);

                $newDocTitle = $old_doc->document_title . ' - ' . $signerName;

                // Update the doc title
                WP_E_Sig()->document->updateTitle($doc_id, $newDocTitle);

                $doc = WP_E_Sig()->document->getDocument($doc_id);

                // trigger an action after document save .
                do_action('esig_sad_document_after_save', array(
                    'document' => $doc,
                    'old_doc_id' => $old_doc_id,
                    'signer_id' => $recipient['id'],
                ));


                // Get Owner
                $owner = WP_E_Sig()->user->getUserByID($doc->user_id);

                // Create the invitation?
                $invitation = array(
                    "recipient_id" => $recipient['id'],
                    "recipient_email" => $emailAddress,
                    "recipient_name" => $signerName,
                    "document_id" => $doc_id,
                    "document_title" => $doc->document_title,
                    "sender_name" => $owner->first_name . ' ' . $owner->last_name,
                    "sender_email" => esig_woocommerce_get("user_email", $owner),
                    "sender_id" => 'stand alone',
                    "document_checksum" => $doc->document_checksum,
                    "sad_doc_id" => $old_doc_id,
                );

                $invite_controller = new WP_E_invitationsController;

                $mailSent = $invite_controller->saveThenSend($invitation, $doc);
                
                $invitation_id = WP_E_Sig()->invite->getInviteID_By_userID_documentID($recipient['id'], $doc_id);
                // save a meta to prevent multiple document creation. 
                update_post_meta($postId, "_esig-agreement-created-" . $old_doc_id, $invitation_id);

                WP_E_Sig()->document->updateStatus($doc_id, "awaiting");
                // link order id with document id 
                esig_woo_logic::save_document_meta($doc_id, $postId);
                //
            }
        }

        echo "success";

        wp_die();
    }

    public function esig_order_meta_box() {

        if (!function_exists('WP_E_Sig')) {
            return;
        }
        $postId = esig_woocommerce_get('post');
        if ($this->is_unsigned_docs($postId)) {
            add_meta_box('meta-box-id', __('WP E-Signature', 'esig-commerce'), array($this, "esig_order_meta_box_content"), 'shop_order');
        }
    }

    public function esig_order_meta_box_content() {

        $postId = esig_woocommerce_get('post');



        $docs = get_post_meta($postId, "_esig_after_checkout_doc_list", true);
        
        $contracts = json_decode($docs);
        $nodocument = true;
        $i = 0;
        foreach ($contracts as $docId => $status) {
            if ($status == "no") {
                
                $invitationId = get_post_meta($postId, "_esig-agreement-created-" . $docId, true);

                if ($invitationId) {

                    if ($this->is_signed_doc($invitationId)) {
                        $nodocument = true;
                        continue;
                    }
                    $nodocument = false;
                    if (class_exists('WP_E_Notice')) {
                        $esig_notice = new WP_E_Notice();
                        echo $esig_notice->esig_print_notice();
                    }
                    if ($i == 0) {
                        printf(__('<p>There is unsigned agreement for this order.</p>', 'esig'));
                        $i++;
                    }
                    ?>
<p> <?php echo $this->document_title($invitationId); ?>  - <a class="" href="<?php echo $this->get_resend_url($invitationId, $postId); ?>" > <?php _e('Resend agreement','esign')?> </a></p>
                    <?php
                } else {
                     $nodocument=false;
                    if ($i == 0) {
                        
                        printf(__('<p>There is unsigned agreement for this order.</p>', 'esig'));
                       
                    
                    ?>
                    <input id="esig_woo_order_id" name="esig_woo_order_id" type="hidden" value="<?php echo $postId; ?>">
                    <button id="esig-woo-unsigned-agreement-send" class="button primary"><?php _e('Send agreement for signature','esign')?></button>
                    <?php
                     $i++;
                        
                    }
                }
            }
        }
        if ($nodocument) {
            printf(__('<p>There is no unsigned agreement for this order.</p>', 'esig'));
        }
    }

    private function get_resend_url($invitationId, $postId) {
        $documentId = WP_E_Sig()->invite->getdocumentid_By_inviteid($invitationId);
        $returnUrl = esc_url_raw(add_query_arg(array("post" => $postId, "action" => "edit"), admin_url("post.php")));
        $resendUrl = esc_url_raw(add_query_arg(apply_filters("esig_resend_url_filter", array('page' => 'esign-resend_invite-document', 'document_id' => $documentId, 'callBackUrl' => esc_url($returnUrl))), admin_url("admin.php")));
        return apply_filters('esig_resend_url', $resendUrl);
    }
    
     private function document_title($invitationId) {
        $documentId = WP_E_Sig()->invite->getdocumentid_By_inviteid($invitationId);
        $doc = WP_E_Sig()->document->getDocument($documentId);
        return $doc->document_title ; 
    }

    private function is_signed_doc($invitationId) {
        $invite = WP_E_Sig()->invite->getInviteBy("invitation_id", $invitationId);
        if (!is_object($invite)) {
            return false;
        }
        $userId = $invite->user_id;
        $documentId = $invite->document_id;
        if (WP_E_Sig()->signature->userHasSignedDocument($userId, $documentId)) {
            return true;
        }
        return false;
    }

    private function is_unsigned_docs($postId) {
        $docs = get_post_meta($postId, "_esig_after_checkout_doc_list");
        if (is_array($docs)) {

            $ret = false;
            foreach ($docs as $doc) {
                $docs_one = json_decode($doc, true);
                if (is_array($docs_one) && in_array("no", $docs_one)) {
                    $ret = true;
                    break;
                }
            }
            return $ret;
        }
        return false;
    }

    public function pre_process_checkout() {


        $response = array(
            'result' => 'failure',
            'messages' => '<font color="color">'. __('here we go','esign').'</font>',
        );

        update_option('rupom', WC()->session);
        wp_send_json($response);
        wp_die();

        //wp_redirect( wc_get_page_permalink( 'cart' ) );
        //exit;
        // throw new Exception( __( 'This is test', 'woocommerce' ) );
    }

}
