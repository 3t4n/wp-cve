<?php

/**
 * Thrive editor
 *
 * Class Wpil_Editor_Thrive
 */
class Wpil_Editor_Thrive
{
    /**
     * Gets the Thrive content for diagnostic purposes.
     * Not intended for adding links to this content!
     *
     * @param $post_id
     */
    public static function getThriveContent($post_id)
    {
        // If thrive's not active, return string
        if(empty(get_post_meta($post_id, 'tcb_editor_enabled', true))){
            return "";
        }

        $content_key = 'tve_updated_post';
        $thrive_template = get_post_meta($post_id, 'tve_landing_page', true);

        // see if this is a thrive templated page
        if(!empty($thrive_template)){
            // get the template key
            $content_key = 'tve_updated_post_' . $thrive_template;
        }

        $thrive = get_post_meta($post_id, $content_key, true);

        return !(empty($thrive)) ? $thrive: "";
    }

}
