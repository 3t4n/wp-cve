<?php

class esigWooFilters
{
    public static function init()
    {
        // We used this filter to replace esig-woo-order-details shortcode first time to generate agreement with value. 
        add_filter("esig_document_clone_render_content", array(__CLASS__, "replace_woo_shortcode"), 10, 4);
        
    }

    public static function replace_woo_shortcode($newDocumentContentRender, $new_doc_id, $docType, $args)
    {

        if (!function_exists("esig_do_unique_shortcode")) return $newDocumentContentRender;

        global $esig_woo_document_id;
        $esig_woo_document_id = $new_doc_id;

        if (is_null($esig_woo_document_id) && !is_numeric($esig_woo_document_id)) return $newDocumentContentRender;

        $newContent =  esig_do_unique_shortcode($newDocumentContentRender, ["esig-woo-order-details"]);
        return $newContent;
    }

    
}

esigWooFilters::init();