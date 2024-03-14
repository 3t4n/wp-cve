<?php

namespace ImageComply;

class Polylang_helper {
    public function __construct() {
        $this->init();
    }
    
    public function init(){
        // Add Polylang Post's polylang to translations
        add_filter('imagecomply_language_override', [$this, 'language_override'], 10, 2);
        
        // Generate alt text for all translations
        add_action("imagecomply_pre_generate_alt_text", [$this, 'generate_alt_text_for_translations_single'], 10, 1);

        // Filter the query args used to get all results for every language
        add_filter('imagecomply_generate_all_alt_text_query_args', [$this, 'lang_filter'], 10);
        
        // Create missing translations
        add_action("imagecomply_pre_generate_all_alt_text", [$this, 'create_missing_translations'], 10, 1);
    }

    #region WPML Filters and actions

    /**
     * Filter the language used for the imagecomply image data
     * @param $language current language
     * @param $id attachement ID
     * @return $lannguage in plain english. "English", "French", etc.
     */
    public function language_override($language, $id) {
        // error_log("language_override"); 

		$language_name = $this?->get_language($id);
		// error_log(print_r($language_name, true));

		$poly_language = $language_name ?? false;
		// error_log("Overide Language: ". $poly_language);

        return $poly_language ?? $language;
    }
    
    /**
     * Generate alt text for translations
     * @param $id attachement ID
     */
    public function generate_alt_text_for_translations_single($id) {
        // error_log("generate_alt_text_for_translations_single"); 
        
        // passes true, to skip hooks that would otherwise cause an infinite loop.
        $this->work_on_the_translated_posts($id, [Functions::class, 'generate_alt_text'], [true]);
    }
    
    /**
     * Append the lang_filter arg to get all results for every language
     * By default Polylang only returns posts that match the theme's language
     */
    public function lang_filter($query_args) {
        $query_args['lang'] = '';
        return $query_args;
    }

    /**
     * Generate alt text for all translations
     * @param $attachements array of attachement IDs
     */
    public function create_missing_translations(){
        
        // error_log("create_all_translations");

        // Query All Translations
		$query_args = array(
			'post_type' => 'attachment',
			'post_status' => 'inherit',
			'post_mime_type' => IMAGECOMPLY_PLUGIN_MIME_TYPES,
			'posts_per_page' => -1,
		);
        
        // error_log(print_r($query_args, true));

		$ic_query = new \WP_Query($query_args);

		if(!$ic_query->have_posts()) {
            // error_log("Poly Lang -- No posts to create translations for");
		}
		$attachments = $ic_query->posts;
        
        // error_log(print_r($attachments, true));
            $active_language_slugs = $this->get_active_languages();

        // Loop through all attachements
        foreach($attachments as $attachment){
            // error_log("attachment: " . $attachment->ID);
            $current_translations = pll_get_post_translations($attachment->ID);
            // error_log("current_translations" . print_r($current_translations, true));
            // error_log("active_language_slugs" . print_r($active_language_slugs, true));
            
            // Loop through all active languages
            foreach($active_language_slugs as $active_language_slug){
                
                // Check for missing languages
                if(!array_key_exists($active_language_slug, $current_translations)){
                    // error_log("Creating new translation for " . $active_language_slug . " from " . $attachment->ID);
                    $this->add_new_translation($attachment->ID, $active_language_slug);
                }
            }
        }
    }

    #endregion
    

    


    
    #region Private functions


    /**
     * Get the language for a given post
     * @param $id post ID
     * @return $language language code ex: fr, en, etc.
     */
    private function get_language($id) {
        $language = pll_get_post_language($id, 'name');
        return $language;
    }

    /**
     * Get the active languages from Polylang
     * @return $active_languages array of active language slugs ex: fr, en, etc.
     */
    private function get_active_languages() {
        $active_languages = pll_languages_list(array('fields' => 'slug'));
        return $active_languages;
    }

    /**
     * Get the translated ID for a given object
     * @param $id original object ID
     * @param $language_name native language string
     * @return $language_attachment_id translated object ID 
     *  or 0 if no translation exists, 
     *  or false if passed id has no language, 
     *  or false if passeed language doesn't exist.
     */
    private function get_id_for_Language($origin_id, $language_slug) {
        $specific_id = pll_get_post($origin_id, $language_slug); // return specific ID, or Origin ID if no translation exists, or 0 if no language exists
        return $specific_id === $origin_id ? false : $specific_id;
    }

    /**
     * Uses the WPML API to get the translated ID the corresponding object in every active language
     * Then calls the provided callback function with the translated ID as the first parameter.
     * @param $id original object ID
     * @param $callback function to call with the translated ID as the first parameter
     * @param $additionalParams additional parameters to pass to the callback function
     */
    private function work_on_the_translated_posts($origin_id, $callback, $args = []) {
        // error_log("Working on all Post from ID: " . $origin_id);
        $active_language_slugs = $this->get_active_languages();
        // error_log('active_languages:');
        // error_log(print_r($active_language_slugs, true));
        
        if(!$active_language_slugs || count($active_language_slugs) == 1) {
            error_log("No active languages or only one active language.");
        }else{
            foreach ($active_language_slugs as $language_slug) {
                $specific_id = $this->get_id_for_language($origin_id, $language_slug);
                // error_log("Attempting ". $language_slug. " -- specific_id: " . ($specific_id ?? "no id"));
                
                if($specific_id === 0){
                    // error_log("Creating new post with lang ". $language_slug . " from origin_id: " . $origin_id);
                    $new_post_id = $this->add_new_translation($origin_id, $language_slug);
                    $specific_id = $new_post_id;
                }

                if(isset($specific_id) && $specific_id && $specific_id != 0 && $specific_id != $origin_id) {
                    $args = array_merge([$specific_id], $args);
                    // error_log("args: " . print_r($args, true));
                    // error_log("Lang ". $language_slug . " called imagecomply with specific_id: " . $specific_id);
                    call_user_func_array($callback, $args);
                }
            }
        }
    }
    
    /**
     * Create a post of a given language
     * @param $origin_id original post ID
     * @param $language_slug language slug ex: fr, en, etc.
     * @return $new_post_id new post ID
     */
    private function add_new_translation($origin_id, $language_slug){
        $new_post_id = $this->duplicate_media($origin_id);
        
        if(!$new_post_id){
            error_log("Failed to duplicate media");
            return false;
        }
        
        $this->join_post_translations($new_post_id, $origin_id, $language_slug);

        return $new_post_id;
    }
    
    /**
     * Duplicate a post. 
     * Note: Doesn't rely on  anything polylang, but is used by add_new_translation. Could be brought out to a helper class
     * @param $origin_id original post ID
     * @return $new_post_id new post ID, or false on failure
     */
    private function duplicate_media($origin_id) {
        // Get the attachment post to be duplicated
        $attachment = get_post($origin_id);
    
        if (!$attachment || $attachment->post_type !== 'attachment') {
            return false; // Invalid media or not an attachment
        }
    
        // Get the original file path on the server
        $original_file_path = get_attached_file($origin_id);
    
        if (!$original_file_path) {
            return false; // Unable to retrieve original file path
        }
    
        // Create a new attachment post
        $new_media_id = wp_insert_attachment(array(
            'post_title' => $attachment->post_title,
            'post_status' => $attachment->post_status,
            'post_mime_type' => $attachment->post_mime_type,
        ), $original_file_path);
        
    
        if (!is_wp_error($new_media_id)) {

            // Generate attachment metadata and update the attachment
            $attach_data = wp_generate_attachment_metadata($new_media_id, $original_file_path);
            wp_update_attachment_metadata($new_media_id, $attach_data);
    
            return $new_media_id;
        } else {
            return false; // Failed to create a new attachment
        }
    }
    
    /**
     * Join a post to the translations
     * @param $new_post_id new post ID
     * @param $origin_id original post ID
     * @param $language_slug language slug ex: fr, en, etc.
     */
    private function join_post_translations($new_post_id, $origin_id, $language_slug){
        // error_log($language_slug. " join post -- new_post_id: " . $new_post_id);

        // Set new post as langugage
        pll_set_post_language($new_post_id, $language_slug);

        // Get all translations
        $translations = pll_get_post_translations($origin_id);
        
        // Add new translation
        $translations[$language_slug] = $new_post_id;

        // Save translations
        pll_save_post_translations($translations);
    }
    
    # endregion
}