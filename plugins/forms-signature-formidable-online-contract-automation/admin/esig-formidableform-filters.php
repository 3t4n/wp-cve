<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (!class_exists('esigFormidableFilters')):

    class esigFormidableFilters {

        protected static $instance = null;

        private function __construct() {
            add_filter("esig_document_title_filter", array($this, "formidable_document_title_filter"), 10, 2);
            add_filter("esig_strip_shortcodes_tagnames", array($this, "tag_list_filter"), 10, 1);
            add_filter("frm_content", array($this, "paypal_return_url_filter"), -10, 3);

            add_filter("esig_document_clone_render_content", array($this, "document_content_render"), 10, 4);

            add_filter("init", array($this, "frm_init"), -10, 3);
            add_filter("esig_document_clone_render_content",array($this,"replace_esigformidable_shortcode"),10,4);
        }

        public function replace_esigformidable_shortcode($content, $new_doc_id, $docType, $args){

            if (!shortcode_exists( 'esigformidable' ) ) {
                return $content;
            }

            if(!function_exists("esig_do_unique_shortcode")) return $content;

            global $esig_formidable_document_id, $esig_formidable_entry_id; 
            $esig_formidable_document_id = $new_doc_id; 
            if(is_null($esig_formidable_entry_id) && !is_numeric($esig_formidable_entry_id)) return $content; 
            $newContent=  esig_do_unique_shortcode($content,["esigformidable"]);
            return $newContent;
        }

        public function replace_shortcode($content,$args) {

            if (false === strpos($content, '[')) {
                return $content;
            }
            $tagnames = array("esigformidable");
            /* $shortcode_tags = array("esigformidable");
              // Find all registered tag names in $content.
              preg_match_all('@\[([^<>&/\[\]\x00-\x20=]++)@', $content, $matches);
              $tags_to_replace = array_keys($shortcode_tags);
              $tagnames = array_intersect($tags_to_replace, $matches[1]); */

            $content = do_shortcodes_in_html_tags($content, true, $tagnames);
            $pattern = get_shortcode_regex($tagnames);
            ESIG_FORMIDABLEFORM_SETTING::setEntryValue($args['formidableValue']);
            $content = preg_replace_callback("/$pattern/", 'do_shortcode_tag', $content);
            // Always restore square braces so we don't break things like <!--[if IE ]>
            $content = unescape_invalid_shortcodes($content);

            return $content;
        }

        public function document_content_render($content, $new_doc_id, $documentType, $args) {

            if ($documentType != 'stand_alone') {
                return $content;
            }

            $isIntregration = esig_formidable_get("integrationType", $args);
            if ($isIntregration != "esig-formidable") {
                return $content;
            }

            $content = $this->replace_shortcode($content,$args);

            return $content;
        }

        public function frm_init() {
            $frm_agreement = isset($_GET['frm_esig_agreement']) ? esig_formidable_get('frm_esig_agreement') : false;
            if (!$frm_agreement) {
                $auth = isset($_GET['auth']) ? esig_formidable_get('auth') : false;

                if ($auth) {
                    $frm_agreement = ESIG_FORMIDABLEFORM_SETTING::getTempEntryId();
                    if ($frm_agreement) {
                        ESIG_FORMIDABLEFORM_SETTING::deleteTempEntryId();
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            }
            $inviteUrl = ESIG_FORMIDABLEFORM_SETTING::getInviteUrl($frm_agreement);
            if ($inviteUrl) {
                wp_redirect($inviteUrl);
                exit;
            }
        }

        public function paypal_return_url_filter($return_url, $forms, $entryId) {


            $returnUrl = esig_formidable_get('return_url', $forms->options);

            if ($returnUrl == $return_url) {
                $form_actions = FrmFormAction::get_action_for_form($forms->id, 'esig', 1);

                $frmAction = $form_actions->post_content;
                $afterPaypalPayment = esig_formidable_get('redirect_after_payment', $frmAction);
                $signingLogic = $frmAction['signing_logic'];
                if ($afterPaypalPayment && $signingLogic == "redirect") {
                    if (strpos($return_url, '?') !== false) {
                        $return_url = $return_url . "&frm_esig_agreement=" . $entryId;
                    } else {

                        $return_url = add_query_arg(array(
                            'frm_esig_agreement' => $entryId,
                                ), $return_url);
                    }
                }
            }
            return $return_url;
        }

        public function tag_list_filter($listArray) {
            $listArray[] = "formidable";
            return $listArray;
        }

        public function formidable_document_title_filter($docTitle, $docId) {

            $formIntegration = WP_E_Sig()->document->getFormIntegration($docId);

            if ($formIntegration != "formidable") {
                return $docTitle;
            }

            preg_match_all('/{{+(.*?)}}/', $docTitle, $matchesAll);

            if (empty($matchesAll[1])) {
                return $docTitle;
            }
            if (!is_array($matchesAll[1])) {
                return $docTitle;
            }

            $titleResult = $matchesAll[1];

            $formId = WP_E_Sig()->meta->get($docId, 'esig_formidable_form_id');

            foreach ($titleResult as $result) {

                preg_match_all('!\d+!', $result, $matches);
                if (empty($matches[0])) {
                    continue;
                }
                $fieldId = is_array($matches) ? $matches[0][0] : false;
                if (is_numeric($fieldId)) {
                    $formidableValue = wp_strip_all_tags(ESIG_FORMIDABLEFORM_SETTING::generate_value($docId, $formId, $fieldId));
                    $docTitle = str_replace("{{formidable-field-id-" . $fieldId . "}}", $formidableValue, $docTitle);
                }
            }

            return $docTitle;
        }

        /**
         * Return an instance of this class.
         * @since     0.1
         * @return    object    A single instance of this class.
         */
        public static function instance() {

            // If the single instance hasn't been set, set it now.
            if (null == self::$instance) {
                self::$instance = new self;
            }

            return self::$instance;
        }

    }

    

    

    

    

    

    

    

    

    

    

    

    

    

    

    

    

    
endif;
