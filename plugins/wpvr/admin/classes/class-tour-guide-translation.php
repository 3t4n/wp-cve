<?php

Class Tour_Guide_Translation
{
    public function get_translatable_string()
    {
        return array(
            'next_button_text' => __('Next', 'wpvr'),
            'previous_button_text' => __('Previous', 'wpvr'),
            'done_text' => __('Done', 'wpvr'),
            'end_tour' => __('End Tour', 'wpvr'),
            'tour_title' => array(
                'title' => __('Set a Title to Your Virtual Tour', 'wpvr'),
                'text' => __('Give a name to your virtual tour.', 'wpvr'),
            ),
            'scene_section' => array(
                'title' => __('Add Scene ID ', 'wpvr'),
                'text' => __('Set the Scene ID for your first panorama image. The Scene ID has to be unique for each scene and without any spaces or special characters. You can set it as Scene1, S1, or simply 01.', 'wpvr'),
            ),
            'upload_image' => array(
                'title' => __('Upload Panorama Image', 'wpvr'),
                'text' => __('Click on the Upload button to upload your panorama image.', 'wpvr'),
            ),
            'Continue_to_guide' => __('Continue to guide', 'wpvr'),
            'preview_tour_button' => array(
                'title' => __('Preview The Image in Tour Mode', 'wpvr'),
                'text' => __('Click on the Preview button to view your uploaded panorama in virtual tour mode.', 'wpvr'),
            ),
            'preview_tour_section' => array(
                'title' => __('Your Image In Tour Mode', 'wpvr'),
                'text' => __('Here is a preview of your panorama image in tour mode. You can control it and mode around to see it in 360 degree view.', 'wpvr'),
            ),
            'publish_tour' => array(
                'title' => __('Save Your Tour', 'wpvr'),
                'text' => __('Click on this Publish button to save this as a tour. You can always find it in your tour list.', 'wpvr'),
            ),
            'sence_end' => array(
                'title' => __('Publish Your Tour', 'wpvr'),
//                'text' => __('Click on this Publish button to save this as a tour. You can always find it in your tour list.', 'wpvr'),
            ),

            'hotspot_start' => array(
                'title' => __('Let\'s add a Hotspot', 'wpvr'),
                'text' => __('Add a Hotspot inside your tour to show additional information like Paragraph, Heading, Image, Video, or multiple content.', 'wpvr'),
            ),
            'hotspot_id' => array(
                'title' => __('Set Hotspot ID', 'wpvr'),
                'text' => __('Set an unique Hotspot ID to each of your Hotspots. Avoid Spaces & special characters. Set it like H1 or 01', 'wpvr'),
            ),
            'choose_previwer' => array(
                'title' => __('Choose The Spot', 'wpvr'),
                'text' => __('In this Preview, drag to your desired location and click on it, exactly where you want to set the hotspot', 'wpvr'),
            ),
            'assigin_pitch_yaw' => array(
                'title' => __('Assign Pitch & Yaw', 'wpvr'),
                'text' => __('Once you see the Pitch & Yaw value for the spot, click on this Arrow. It\'ll be set as the coordinate for your hotspot', 'wpvr'),
            ),
            'pitch_yaw_set' => array(
                'title' => __('Pitch & Yaw is Set', 'wpvr'),
                'text' => __('Here you can see the Pitch & Yaw has been set for the hotspot.', 'wpvr'),
            ),
            'pitch_yaw_set_2' => array(
                'title' => __('Pitch & Yaw is Set', 'wpvr'),
                'text' => __('Here you can see the Pitch & Yaw has been set for the hotspot.', 'wpvr'),
            ),
            'on_click_content_info' => array(
                'title' => __('Set The Content for Click Action', 'wpvr'),
                'text' => __('Here, you can set what content your viewer will see after clicking on the Hotspot. You can either set an URL or any other content using this editor. To learn more, follow this guide to set content using editor', 'wpvr'),
            ),
            'on_hover_info' => array(
                'title' => __('Set The Content for Click Action', 'wpvr'),
                'text' => __('Here, you can set what content your viewer will see after clicking on the Hotspot. You can either set an URL or any other content using this editor. To learn more, follow this guide to set content using editor', 'wpvr'),
            ),
            'preview_on_hotspot' => array(
                'title' => __('Preview Your Hotspot Content', 'wpvr'),
                'text' => __('Click on Preview to see how the content is appearing for the hotspot.', 'wpvr'),
            ),
            'save_process_hotspot' => array(
                'title' => __('Save Your Progress', 'wpvr'),
                'text' => __('Click on the Update button to save your work. And just like that, you can create an unlimited number of virtual tours and add your customization to them.', 'wpvr'),
            ),
        );
    }
}