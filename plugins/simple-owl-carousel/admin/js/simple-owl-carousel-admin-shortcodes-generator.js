/*
 *  Shortcode Builder JS File
 *  v1.0.0
 */

(function ($) {
    'use strict';

    $(document).ready(function () {
        tinymce.PluginManager.add('soc_shortcodes_mce_button', function (editor, url) {
            editor.addButton('soc_shortcodes_mce_button', {
                title: 'Simple Owl Carousel',
                type: 'menubutton',
                icon: 'icon soc-icon',
                menu: [

                    /* SOC Slider */
                    {
                        text: 'SOC Slider',
                        onclick: function () {
                            editor.windowManager.open({
                                title: 'Insert SOC Slider Shortcode',
                                body: [

                                    // Post ID
                                    {
                                        type:  'textbox',
                                        name:  'id',
                                        label: 'Post ID',
                                        placeholder: 'ID of SOC Slider',
                                    },

                                     // Number of Items
                                    {
                                        type:  'textbox',
                                        name:  'items',
                                        label: 'Items',
                                        value: 3,
                                    },

                                    // Navigation
                                    {
                                        type:  'listbox',
                                        name:  'navigation',
                                        label: 'Navigation',
                                        values: [
                                            {text: 'True', value: 'true'},
                                            {text: 'False', value: 'false'},
                                        ]
                                    },

                                    // Single Item
                                    {
                                        type:  'listbox',
                                        name:  'single_item',
                                        label: 'Single Item',
                                        values: [
                                            {text: 'False', value: 'false'},
                                            {text: 'True', value: 'true'},

                                        ]
                                    },

                                     // Slide Speed
                                    {
                                        type:  'textbox',
                                        subtype: 'number',
                                        name:  'slide_speed',
                                        label: 'Smart Speed',
                                        value: 250,
                                    },

                                    // Lazy Load
                                    {
                                        type: 'listbox',
                                        name: 'lazy_load',
                                        label: 'Lazy Load',
                                        values: [
                                            {text: 'True', value: 'true'},
                                            {text: 'False', value: 'false'},
                                        ]
                                    },
                                    // Auto Height
                                    {
                                        type: 'listbox',
                                        name: 'auto_height',
                                        label: 'Auto Height for Single Item',
                                        values: [
                                            {text: 'True', value: 'true'},
                                            {text: 'False', value: 'false'},
                                        ]
                                    },
                                    // Auto Play
                                    {
                                        type: 'listbox',
                                        name: 'auto_play',
                                        label: 'Auto Play Carousel',
                                        values: [
                                            {text: 'True', value: 'true'},
                                            {text: 'False', value: 'false'},
                                        ]
                                    },
                                    // Autoplay Timeout
                                    {
                                        type: 'textbox',
                                        subtype: 'number',
                                        name: 'autoplay_timeout',
                                        label: 'Autoplay Timeout',
                                        value: 600,
                                    },
                                    // Autoplay Hover Pause
                                    {
                                        type: 'listbox',
                                        name: 'autoplay_hover_pause',
                                        label: 'Autoplay pause on hover',
                                        values: [
                                            {text: 'True', value: 'true'},
                                            {text: 'False', value: 'false'},
                                        ]
                                    },

                                ],
                                onsubmit: function (e) {

                                    // If user enter number less than 1
                                    if (e.data.id < 1 ) {

                                       // Change value with null
                                        e.data.id = '';
                                    }
                                    editor.insertContent('[soc_slider_shortcode id="' + e.data.id + '" items="' + e.data.items + '" navigation="' + e.data.navigation + '" single_item="' + e.data.single_item+ '" slide_speed="' + e.data.slide_speed+ '" lazy_load="' + e.data.lazy_load + '" auto_height="' + e.data.auto_height+ '" auto_play="' + e.data.auto_play+ '" autoplay_timeout="' + e.data.autoplay_timeout+ '" autoplay_hover_pause="' + e.data.autoplay_hover_pause + '"]');
                                }
                            });
                        }
                    }, // End soc shortcode generator
                ]
            });
        });
    });
})(jQuery);
