<?php

/**
 * Elementor editor
 *
 * Class Wpil_Editor_Elementor
 */
class Wpil_Editor_Elementor
{
    public static $link_processed;
    public static $keyword_links_count;
    public static $link_confirmed;
    public static $document;
    public static $current_id;
    public static $remove_unprocessable = true;
    public static $force_insert_link;

    /**
     * Gets the Elementor content for making suggestions
     *
     * @param int $post_id The id of the post that we're trying to get information for.
     * @param bool $editor_active Have we already checked to see if the Elementor editor is active for this post?
     */
    public static function getContent($post_id, $editor_active = false, $remove_unprocessable = true)
    {

        if( !defined('ELEMENTOR_VERSION') || // if Elementor is not active
            !class_exists('\Elementor\Plugin') || // or the plugin main class isn't active
            !isset(\Elementor\Plugin::$instance) || empty(\Elementor\Plugin::$instance) || // or we have don't have an instance
            !isset(\Elementor\Plugin::$instance->db) || empty(\Elementor\Plugin::$instance->db) || // or there's no db method
            empty($post_id)) // or somehow there isn't a post id
        {
            // or the page isn't built with elementor
            $document = self::getDocument($post_id);
            try {
                if(empty($document) || !$document->is_built_with_elementor()){
                    // exit
                    return '';
                }
            } catch (Throwable $t) {
                // also exit if we run into an error
                return  '';
            } catch (Exception $e) {
                return  '';
            }
        }

        self::$remove_unprocessable = $remove_unprocessable;
        $elementor = get_post_meta($post_id, '_elementor_data', true);
        $content = '';
        // if there's elementor data and the editor is active for this post
        if (!empty($elementor) && ($editor_active || !empty(get_post_meta($post_id, '_elementor_edit_mode', true)) ) ) {
            $elementor = (is_string($elementor)) ? json_decode($elementor) : $elementor;
            if(is_array($elementor)){
                foreach($elementor as $data){
                    self::getProcessableData($data, $content, $post_id);
                }
            }
        }

        self::$remove_unprocessable = true;
        return $content;
    }

    /**
     * Check certain text element
     *
     * @param $item
     * @param $params
     */
    public static function getProcessableData($item, &$content, $post_id)
    {
        if (!empty($item->widgetType) && (!in_array($item->widgetType, ['heading', 'button', 'call-to-action']) || $item->widgetType === 'heading' && self::canAddLinksToHeading($item) || !self::$remove_unprocessable) ) {
            if (isset($item->settings) && isset($item->settings->tabs) && !empty($item->settings->tabs)) {
                foreach ($item->settings->tabs as $key => $tab) {
                    foreach(array('tab_content', 'faq_answer', 'accordion_content') as $tab_index){
                        if( isset($item->settings->tabs[$key]->$tab_index) && 
                            !empty($item->settings->tabs[$key]->$tab_index))
                        {
                            $content .= "\n" . $item->settings->tabs[$key]->$tab_index;
                        }
                    }
                }
            }

            // look over any HBTheme repeating modules // todo abstract into a more refined form when more data is available. There will be other module packs that have items with sub content in the same way as this.
            foreach (['accordions', 'images'] as $key) {
                if (!empty($item->settings->$key)) {
                    foreach($item->settings->$key as $sub_item){
                        foreach(['desc', 'description', 'caption'] as $content_type){
                            $content .= "\n" . $sub_item->$content_type;
                        }
                    }
                }
            }

            if( in_array($item->widgetType, array('image'), true) ||
                isset($item->settings->icon_list) || // icon lists can go both ways, having HTML links or links added by property, so we should process them here
                (!self::$remove_unprocessable && ( (isset($item->settings) && isset($item->settings->link)) || in_array($item->widgetType, array('woocommerce-products'), true) ) )
            ){
                // if this is a WooCommerce item
                if(in_array($item->widgetType, array('woocommerce-products'), true) && class_exists('WooCommerce')){
                    // include the WooCommerce frontend files so we can render the item
                    $woo = WooCommerce::instance();
                    if(!empty($woo) && is_object($woo) && method_exists($woo, 'frontend_includes')){
                        $woo->frontend_includes();
                    }
                }

                $document = self::getDocument($post_id);
                if(!empty($document)){
                    try {
                        $content .= "\n" . $document->render_element( json_decode(json_encode($item), true) );
                    } catch (Throwable $t) {
                    } catch (Exception $e) {
                    }
                }

            }else{
                foreach (['editor', 'title', 'caption', 'text', 'description_text', 'testimonial_content', 'html', 'alert_title', 'alert_description', 'description', 'faq_answer', 'accordion_content', 'protected_content_text', 'blockquote_content'] as $key) {
                    if (!empty($item->settings->$key)) {
                        $content .= "\n" . $item->settings->$key;
                    }
                }
            }
        }

        if (!empty($item->elements)) {
            foreach ($item->elements as $element) {
                self::getProcessableData($element, $content, $post_id);
            }
        }
    }

    public static function getDocument($post_id){
        // if:
        if( !defined('ELEMENTOR_VERSION') || // if Elementor is not active
            !class_exists('\Elementor\Plugin') || // or the plugin main class isn't active
            !isset(\Elementor\Plugin::$instance) || empty(\Elementor\Plugin::$instance) || // or we have don't have an instance
            !isset(\Elementor\Plugin::$instance->db) || empty(\Elementor\Plugin::$instance->db) || // or there's no db method
            empty($post_id)) // or somehow there isn't a post id
        {
            // there's no such thing as a document
            return false;
        }

        if(empty(self::$document) || ($post_id !== self::$current_id)){
            self::$document = \Elementor\Plugin::$instance->documents->get($post_id);
        }
        self::$current_id = $post_id;

        return self::$document;
    }

    /**
     * Checks the given item to see if its a heading and it can have links added to it.
     * @param object $item The Elementor item that we're going to check
     * @return bool
     **/
    public static function canAddLinksToHeading($item){
        if($item->widgetType !== 'heading'){
            return true; // possibly remove this. I'm returning true in case I accidentally use this somewhere that doesn't strictly check for headings, but this could allow false positives.
        }

        // if a custom heading element has been selected, and the element is a div, span, or p
        if(isset($item->settings) && isset($item->settings->header_size) && in_array($item->settings->header_size, array('div', 'span', 'p'))){
            // return that a link can be inserted here
            return true;
        }

        return false;
    }
}