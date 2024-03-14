<?php

// Gutenberge Editor:
// The editor seperates the alt text from the page content, so the translations for alt text will need to be manual.
// https://wordpress.org/support/topic/alt-text-and-title-not-translating-over-from-gutenberg-editor-to-media-library/
// - Doesn't really explain it, but Gutenberge editor display alt text property as "html-attr", not "core/image"

// Classic Editor:
// 

// Elementor Editor:
// 

// TODO: plan to access the WP_icl_translations / jobs databases to integrate furtger with  WPML


namespace ImageComply;

class WPML_helper {
    public function __construct() {
        $this->init();
    }
    
    public function init(){
        // Override the language used for the imagecomply image data
        add_filter('imagecomply_language_override', [$this, 'language_override'], 10, 2);
        
        // Filter the query args used to get all results for every language
        add_filter('imagecomply_generate_all_alt_text_query_args', [$this, 'suppress_language_filter'], 10);
        
        // Generate alt text for all translations
        add_action("imagecomply_pre_generate_alt_text", [$this, 'generate_alt_text_for_translations_single'], 10, 1);
        
        // Save alt text for the translation manager 
		add_action("imagecomply_post_save_alt_text", [$this, 'add_translation_to_translation_manager'] ,10 ,2 );
    }

    #region WPML Filters and actions

    /**
     * Filter the language used for the imagecomply image data
	 If this breaks it is because field `native_name` may only work for english primary sites. 
	 -- `lanugage_code` may be a better option
     * @param $language current language
     * @param $id attachement ID
     * @return $lannguage in plain english. "English", "French", etc.
     */
    public function language_override($language, $id) {
        // error_log("language_override"); 

		$wpml_language_object = $this?->get_language($id);
		// error_log(print_r($wpml_language_object, true));

		$wpml_language = $wpml_language_object ? $wpml_language_object['native_name'] : false;
		// error_log("Overide Language: ". $wpml_language);

        return $wpml_language ?? $language;
    }

    /**
     * Append the suppress_filter arg to get all results for every language
     * @param $query_args query args
     */
    public function suppress_language_filter($query_args) {
        $query_args['suppress_filters'] = true;
        return $query_args;
    }
    
    /**
     * Generate alt text for translations
     * @param $id attachement ID
     */
    public function generate_alt_text_for_translations_single($id) {
        // error_log("generate_alt_text_for_translations_single"); 
        
        $this->work_on_the_translated_posts($id, [Functions::class, 'generate_alt_text'], [true]);
    }
    
    public function add_translation_to_translation_manager($id, $alt_text) {
		error_log("add translation manager -- Alt Text: ". $alt_text ." -- Id: ". $id);
        // This updates by default, for all but the primary language. This could be a flag for the duplicated attachement files. 
        
		update_post_meta($id, 'wpml_media_processed', 1);
    }

    #endregion

    #region Private functions

    /**
     * Get the language for a given post
     * @param $id post ID
     * @return $language language object from WPML
     */
    private function get_language($id) {
        $language = apply_filters('wpml_post_language_details', NULL, $id);
        return $language;
    }

    /**
     * Get the active languages from WPML
     */
    private function get_active_languages() {
        $active_languages = apply_filters('wpml_active_languages', NULL);
        return $active_languages;
    }

    /**
     * Get the translated ID for a given object
     * @param $id original object ID
     * @param $languageObject language object from WPML
     * @return $language_attachment_id translated object ID OR false if no language exists
     */
    private function get_id_for_Language($origin_id, $languageObject) {
        // error_log("get_id_for_Language, ID: " . $origin_id . ", language: " . $languageObject['native_name']);
        $specific_id = apply_filters('wpml_object_id', $origin_id, 'attachment', FALSE, $languageObject['language_code']);

        return $specific_id;
    }

    /**
     * Uses the WPML API to get the translated ID the corresponding object in every active language
     * Then calls the provided callback function with the translated ID as the first parameter.
     * @param $id original object ID
     * @param $callback function to call with the translated ID as the first parameter
     * @param $additionalParams additional parameters to pass to the callback function
     */
    private function work_on_the_translated_posts($origin_id, $callback, $args = []) {
        if(is_callable($callback) === false) {
            error_log("Callback is not callable.");
            return;
        }

        error_log("has_translated_objects, ID: " . $origin_id);
        $active_languages = $this->get_active_languages();
        // error_log('active_languages:');
        // error_log(print_r($active_languages, true));
        

        if(!$active_languages || count($active_languages) == 1) {
            error_log("No active languages or only one active language.");
        }else{
            foreach ($active_languages as $language) {
                // error_log("language: " . $language['native_name']);
                $specific_id = $this->get_id_for_language($origin_id, $language);
                // error_log("specific_id: " . ($specific_id ?? "no id"));
                
                if(isset($specific_id) && $specific_id && $specific_id != $origin_id) {
                    $args = array_merge([$specific_id], $args);
                    // error_log("args: " . print_r($args, true));
                    call_user_func_array($callback, $args);
                    // error_log($language['native_name'] . " called callback with specific_id: " . $specific_id);
                }
            }
        }
    }
    
    # endregion
}