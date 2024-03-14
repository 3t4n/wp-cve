<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (!class_exists('esigNinjaFilters')):

    class esigNinjaFilters {

        protected static $instance = null;

        private function __construct() {

            add_filter("esig_document_title_filter", array($this, "ninja_document_title_filter"), 10, 2);
            add_filter("esig_strip_shortcodes_tagnames", array($this, "tag_list_filter"), 10, 1);

            // We used this filter to replace esigninja shortcode first time to generate agreement with value. 
            add_filter("esig_document_clone_render_content",array($this,"replace_esigninja_shortcode"),10,4);

        }

        public function replace_esigninja_shortcode($newDocumentContentRender, $new_doc_id, $docType, $args){

              if(!function_exists("esig_do_unique_shortcode")) return $newDocumentContentRender;
              

              if ($docType != 'stand_alone') {
                return $newDocumentContentRender;
              }

              $isIntregration = esigget("integrationType", $args);
              if ($isIntregration != "esig-ninja") {
                return $newDocumentContentRender;
              }             

              global $esig_ninja_document_id, $esig_ninja_entry_id; 
              $esig_ninja_document_id = $new_doc_id; 

              if(is_null($esig_ninja_entry_id) && !is_numeric($esig_ninja_entry_id)) return $newDocumentContentRender;
            
              ESIG_NF_SETTING::nfSetEntryID(esigget("entryId", $args));
              $newContent=  esig_do_unique_shortcode($newDocumentContentRender,["esigninja"]);
              return $newContent;

              
        }

        public function tag_list_filter($listArray) {
            $listArray[] = "ninja_form";
            return $listArray;
        }

        public function ninja_document_title_filter($docTitle, $docId) {

            $formIntegration = WP_E_Sig()->document->getFormIntegration($docId);
            if ($formIntegration != "ninja") {
                return $docTitle;
            }
            
            preg_match_all('/{{+(.*?)}}/', $docTitle, $matchesAll);

            if (empty($matchesAll[1])) {
                return $docTitle;
            }
            if (!is_array($matchesAll[1])) {
                return $docTitle;
            }
            $formId = WP_E_Sig()->meta->get($docId, 'esig_ninja_form_id');
            
            $titleResult = $matchesAll[1];
            foreach ($titleResult as $result) {

                preg_match_all('!\d+!', $result, $matches);
                if (empty($matches[0])) {
                    continue;
                }
                $fieldId = is_array($matches) ? $matches[0][0] : false;
                if (is_numeric($fieldId)) {
                    $nfValue = ESIG_NF_SETTING::get_value($docId, $formId, $fieldId,"value","default");
                    $nfValueStriped = wp_strip_all_tags($nfValue);
                    $docTitle = str_replace("{{ninja-field-id-" . $fieldId . "}}", $nfValueStriped, $docTitle);   
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
