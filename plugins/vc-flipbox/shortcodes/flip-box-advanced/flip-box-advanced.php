<?php
vc_map(array(
    "name" 			=> "Flip Box Advanced",
    "category" 		=> 'Flip Box',
    "description"	=> "Pro Only",
    "base" 			=> "favc_flipbox_advanced",
    "class" 		=> "",
    "icon" 			=> "asvc_flipbox_icon",
    
    "params" 	=> array(
    
    
                    array(
                        "type" => "hvc_notice",
                        "class" => "",
                        'heading' => __('<h3 class="hvc_notice" align="center">To get this addon working, please buy the pro version here <a target="_blank" href="http://codenpy.com/item/flipbox-addon-visual-composer/">Flipbox Addon for WPBakery Page Builder Pro</a> for only $8</h3>', 'hvc'),
                        "param_name" => "hvc_notice_param_1",
                        "value" => '',
                    ),    
    
    
        /* Front */
        array(
            "type"			=> "mgt_separator",
            "param_name"	=> generate_separator_name(),
            "heading"		=> "Front content",
        ),
    		array(
            "type"			=> "textfield",
            //"holder"		=> "div",
            "class" 		=> "hide_in_vc_editor",
            "admin_label" 	=> true,
            "heading"		=> "Header text",
            "description"	=> "",
            "param_name"	=> "header_text_front",
            "std"			=> "",
        ),
        array(
            "type"			=> "textarea",
            //"holder"		=> "div",
            "class" 		=> "hide_in_vc_editor",
            "admin_label" 	=> true,
            "heading"		=> "Description",
            "description"	=> "HTML tags allowed for formatting.",
            "param_name"	=> "description_text_front",
            "std"			=> "",
        ),
        array(
            "type"			=> "colorpicker",
            //"holder"		=> "div",
            "class" 		=> "hide_in_vc_editor",
            "admin_label" 	=> false,
            "heading"		=> "Header text color",
            "param_name"	=> "header_color_front",
            "std"			=> "",
            "edit_field_class" => "vc_col-xs-6",
        ),
        array(
            "type"			=> "colorpicker",
            //"holder"		=> "div",
            "class" 		=> "hide_in_vc_editor",
            "admin_label" 	=> false,
            "heading"		=> "Description text color",
            "param_name"	=> "description_color_front",
            "std"			=> "",
            "edit_field_class" => "vc_col-xs-6",
        ),
        array(
            "type"			=> "colorpicker",
            //"holder"		=> "div",
            "class" 		=> "hide_in_vc_editor",
            "admin_label" 	=> false,
            "heading"		=> "Background color",
            "param_name"	=> "background_color_front",
            "std"			=> "",
            "edit_field_class" => "vc_col-xs-6",
        ),
        array(
            "type"			=> "attach_image",
            //"holder"		=> "div",
            "class" 		=> "hide_in_vc_editor",
            "admin_label" 	=> false,
            "heading"		=> "Background image",
            "param_name"	=> "background_image_front",
            "std"			=> "",
            "edit_field_class" => "vc_col-xs-6",
        ),
        /// Block border
        array(
            'type' => 'checkbox',
            'heading' => __( 'Add block border?', 'js_composer' ),
            'param_name' => 'block_border_front',
            "description"	=> "Use this to add border to block.",
        ),
        array(
            "type"			=> "textfield",
            //"holder"		=> "div",
            "class" 		=> "hide_in_vc_editor",
            "admin_label" 	=> false,
            "heading"		=> "Block border width (in pixels)",
            "description"	=> "Add border width here, for example: 3",
            "param_name"	=> "block_border_front_width",
            "std"			=> "1",
            'dependency' => array(
                'element' => 'block_border_front',
                'value' => 'true',
            ),
            "edit_field_class" => "vc_col-xs-6",
        ),
        array(
            "type"			=> "dropdown",
            //"holder"		=> "div",
            "class" 		=> "hide_in_vc_editor",
            "admin_label" 	=> false,
            "heading"		=> "Block border style",
            "description"	=> "Select block border style.",
            "param_name"	=> "block_border_front_style",
            "value"			=> array(
                "Solid"	=> "solid",
                "Dashed"	=> "dashed",
                "Dotted"	=> "dotted",
                "Double"	=> "double",
                "Groove"	=> "groove",
                "Ridge"	=> "ridge",
                "Inset"	=> "inset",
                "Outset"	=> "outset",
            ),
            "std"			=> "solid",
            'dependency' => array(
                'element' => 'block_border_front',
                'value' => 'true',
            ),
            "edit_field_class" => "vc_col-xs-6",
        ),
        array(
            "type"			=> "colorpicker",
            //"holder"		=> "div",
            "class" 		=> "hide_in_vc_editor",
            "admin_label" 	=> false,
            "heading"		=> "Block border color",
            "param_name"	=> "block_border_front_color",
            "description"	=> "Select block border color.",
            "std"			=> "",
            'dependency' => array(
                'element' => 'block_border_front',
                'value' => 'true',
            ),
            "edit_field_class" => "vc_col-xs-6",
        ),
        /* Flipbox icon */
        array(
            'type' => 'checkbox',
            'heading' => __( 'Add icon to flipbox front side?', 'js_composer' ),
            'param_name' => 'front_icon',
        ),
        array(
            "type"			=> "colorpicker",
            //"holder"		=> "div",
            "class" 		=> "hide_in_vc_editor",
            "admin_label" 	=> false,
            "heading"		=> "Icon color",
            "param_name"	=> "front_icon_color",
            "description"	=> "",
            "std"			=> "",
            'dependency' => array(
                'element' => 'front_icon',
                'value' => 'true',
            ),
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Icon library', 'js_composer' ),
            'value' => array(
                __( 'Font Awesome', 'js_composer' ) => 'fontawesome',
                __( 'Open Iconic', 'js_composer' ) => 'openiconic',
                __( 'Typicons', 'js_composer' ) => 'typicons',
                __( 'Entypo', 'js_composer' ) => 'entypo',
                __( 'Linecons', 'js_composer' ) => 'linecons',
                __( 'Mono Social', 'js_composer' ) => 'monosocial',
                __( 'Material', 'js_composer' ) => 'material',
                __( 'Pe7 Stroke', 'js_composer' ) => 'pe7stroke',
            ),
            'dependency' => array(
                'element' => 'front_icon',
                'value' => 'true',
            ),
            'admin_label' => true,
            'param_name' => 'front_icon_type',
            'description' => __( 'Select icon library.', 'js_composer' ),
            "std"		=> "fontawesome",
        ),
        array(
            'type' => 'iconpicker',
            'heading' => __( 'Icon', 'js_composer' ),
            'param_name' => 'front_icon_fontawesome',
            'value' => 'fa fa-adjust',
            // default value to backend editor admin_label
            'settings' => array(
                'emptyIcon' => false,
                // default true, display an "EMPTY" icon?
                'iconsPerPage' => 4000,
                // default 100, how many icons per/page to display, we use (big number) to display all icons in single page
                'type' => 'fontawesome',
            ),
            'dependency' => array(
                'element' => 'front_icon_type',
                'value' => 'fontawesome',
            ),
            'description' => __( 'Select icon from library.', 'js_composer' ),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => __( 'Icon', 'js_composer' ),
            'param_name' => 'front_icon_pe7stroke',
            'value' => 'pe-7s-album',
            // default value to backend editor admin_label
            'settings' => array(
                'emptyIcon' => false,
                // default true, display an "EMPTY" icon?
                'iconsPerPage' => 4000,
                'type' => 'pe7stroke',
                // default 100, how many icons per/page to display, we use (big number) to display all icons in single page
            ),
            'dependency' => array(
                'element' => 'front_icon_type',
                'value' => 'pe7stroke',
            ),
            'description' => __( 'Select icon from library.', 'js_composer' ),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => __( 'Icon', 'js_composer' ),
            'param_name' => 'front_icon_openiconic',
            'value' => 'vc-oi vc-oi-dial',
            // default value to backend editor admin_label
            'settings' => array(
                'emptyIcon' => false,
                // default true, display an "EMPTY" icon?
                'type' => 'openiconic',
                'iconsPerPage' => 4000,
                // default 100, how many icons per/page to display
            ),
            'dependency' => array(
                'element' => 'front_icon_type',
                'value' => 'openiconic',
            ),
            'description' => __( 'Select icon from library.', 'js_composer' ),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => __( 'Icon', 'js_composer' ),
            'param_name' => 'front_icon_typicons',
            'value' => 'typcn typcn-adjust-brightness',
            // default value to backend editor admin_label
            'settings' => array(
                'emptyIcon' => false,
                // default true, display an "EMPTY" icon?
                'type' => 'typicons',
                'iconsPerPage' => 4000,
                // default 100, how many icons per/page to display
            ),
            'dependency' => array(
                'element' => 'front_icon_type',
                'value' => 'typicons',
            ),
            'description' => __( 'Select icon from library.', 'js_composer' ),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => __( 'Icon', 'js_composer' ),
            'param_name' => 'front_icon_entypo',
            'value' => 'entypo-icon entypo-icon-note',
            // default value to backend editor admin_label
            'settings' => array(
                'emptyIcon' => false,
                // default true, display an "EMPTY" icon?
                'type' => 'entypo',
                'iconsPerPage' => 4000,
                // default 100, how many icons per/page to display
            ),
            'dependency' => array(
                'element' => 'front_icon_type',
                'value' => 'entypo',
            ),
            'description' => __( 'Select icon from library.', 'js_composer' ),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => __( 'Icon', 'js_composer' ),
            'param_name' => 'front_icon_linecons',
            'value' => 'vc_li vc_li-heart',
            // default value to backend editor admin_label
            'settings' => array(
                'emptyIcon' => false,
                // default true, display an "EMPTY" icon?
                'type' => 'linecons',
                'iconsPerPage' => 4000,
                // default 100, how many icons per/page to display
            ),
            'dependency' => array(
                'element' => 'front_icon_type',
                'value' => 'linecons',
            ),
            'description' => __( 'Select icon from library.', 'js_composer' ),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => __( 'Icon', 'js_composer' ),
            'param_name' => 'front_icon_monosocial',
            'value' => 'vc-mono vc-mono-fivehundredpx',
            // default value to backend editor admin_label
            'settings' => array(
                'emptyIcon' => false,
                // default true, display an "EMPTY" icon?
                'type' => 'monosocial',
                'iconsPerPage' => 4000,
                // default 100, how many icons per/page to display
            ),
            'dependency' => array(
                'element' => 'front_icon_type',
                'value' => 'monosocial',
            ),
            'description' => __( 'Select icon from library.', 'js_composer' ),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => __( 'Icon', 'js_composer' ),
            'param_name' => 'front_icon_material',
            'value' => 'vc-material vc-material-cake',
            // default value to backend editor admin_label
            'settings' => array(
                'emptyIcon' => false,
                // default true, display an "EMPTY" icon?
                'type' => 'material',
                'iconsPerPage' => 4000,
                // default 100, how many icons per/page to display
            ),
            'dependency' => array(
                'element' => 'front_icon_type',
                'value' => 'material',
            ),
            'description' => __( 'Select icon from library.', 'js_composer' ),
        ),
        /* END Subheader icon */
        /* Back */
        array(
            "type"			=> "mgt_separator",
            "param_name"	=> generate_separator_name(),
            "heading"		=> "Back content",
        ),
    		array(
            "type"			=> "textfield",
            //"holder"		=> "div",
            "class" 		=> "hide_in_vc_editor",
            "admin_label" 	=> true,
            "heading"		=> "Header text",
            "description"	=> "",
            "param_name"	=> "header_text_back",
            "std"			=> "",
        ),
        array(
            "type"			=> "textarea",
            //"holder"		=> "div",
            "class" 		=> "hide_in_vc_editor",
            "admin_label" 	=> true,
            "heading"		=> "Description",
            "description"	=> "HTML tags allowed for formatting.",
            "param_name"	=> "description_text_back",
            "std"			=> "",
        ),
        array(
            "type"			=> "colorpicker",
            //"holder"		=> "div",
            "class" 		=> "hide_in_vc_editor",
            "admin_label" 	=> false,
            "heading"		=> "Header text color",
            "param_name"	=> "header_color_back",
            "std"			=> "",
            "edit_field_class" => "vc_col-xs-6",
        ),
        array(
            "type"			=> "colorpicker",
            //"holder"		=> "div",
            "class" 		=> "hide_in_vc_editor",
            "admin_label" 	=> false,
            "heading"		=> "Description text color",
            "param_name"	=> "description_color_back",
            "std"			=> "",
            "edit_field_class" => "vc_col-xs-6",
        ),
        array(
            "type"			=> "colorpicker",
            //"holder"		=> "div",
            "class" 		=> "hide_in_vc_editor",
            "admin_label" 	=> false,
            "heading"		=> "Background color",
            "param_name"	=> "background_color_back",
            "std"			=> "",
            "edit_field_class" => "vc_col-xs-6",
        ),
        array(
            "type"			=> "attach_image",
            //"holder"		=> "div",
            "class" 		=> "hide_in_vc_editor",
            "admin_label" 	=> false,
            "heading"		=> "Background image",
            "param_name"	=> "background_image_back",
            "std"			=> "",
            "edit_field_class" => "vc_col-xs-6",
        ),
        /// Block border
        array(
            'type' => 'checkbox',
            'heading' => __( 'Add block border?', 'js_composer' ),
            'param_name' => 'block_border_back',
            "description"	=> "Use this to add border to block.",
        ),
        array(
            "type"			=> "textfield",
            //"holder"		=> "div",
            "class" 		=> "hide_in_vc_editor",
            "admin_label" 	=> false,
            "heading"		=> "Block border width (in pixels)",
            "description"	=> "Add border width here, for example: 3",
            "param_name"	=> "block_border_back_width",
            "std"			=> "1",
            'dependency' => array(
                'element' => 'block_border_back',
                'value' => 'true',
            ),
            "edit_field_class" => "vc_col-xs-6",
        ),
        array(
            "type"			=> "dropdown",
            //"holder"		=> "div",
            "class" 		=> "hide_in_vc_editor",
            "admin_label" 	=> false,
            "heading"		=> "Block border style",
            "description"	=> "Select block border style.",
            "param_name"	=> "block_border_back_style",
            "value"			=> array(
                "Solid"	=> "solid",
                "Dashed"	=> "dashed",
                "Dotted"	=> "dotted",
                "Double"	=> "double",
                "Groove"	=> "groove",
                "Ridge"	=> "ridge",
                "Inset"	=> "inset",
                "Outset"	=> "outset",
            ),
            "std"			=> "solid",
            'dependency' => array(
                'element' => 'block_border_back',
                'value' => 'true',
            ),
            "edit_field_class" => "vc_col-xs-6",
        ),
        array(
            "type"			=> "colorpicker",
            //"holder"		=> "div",
            "class" 		=> "hide_in_vc_editor",
            "admin_label" 	=> false,
            "heading"		=> "Block border color",
            "param_name"	=> "block_border_back_color",
            "description"	=> "Select block border color.",
            "std"			=> "",
            'dependency' => array(
                'element' => 'block_border_back',
                'value' => 'true',
            ),
            "edit_field_class" => "vc_col-xs-6",
        ),
        /* Styles */
    		array(
            "type"			=> "mgt_separator",
            "param_name"	=> generate_separator_name(),
            "heading"		=> "Styles and effects settings",
        ),
        array(
            "type"			=> "dropdown",
            //"holder"		=> "div",
            "class" 		=> "hide_in_vc_editor",
            "admin_label" 	=> false,
            "heading"		=> "Flipbox animation",
            "param_name"	=> "flipbox_animation",
            "value"			=> array(
                "Horizontal"	=> "horizontal",
                "Vertical"	=> "vertical",
            ),
            "description"	=> "Change flipbox animation.",
            "std"			=> "horizontal",
            "edit_field_class" => "vc_col-xs-6",
        ),
        array(
            "type"			=> "textfield",
            //"holder"		=> "div",
            "class" 		=> "hide_in_vc_editor",
            "admin_label" 	=> false,
            "heading"		=> "Flipbox height (px)",
            "description"	=> "For example: 300px",
            "param_name"	=> "block_height",
            "edit_field_class" => "vc_col-xs-6",
            "std"			=> "",
        ),
        array(
            "type"			=> "dropdown",
            //"holder"		=> "div",
            "class" 		=> "hide_in_vc_editor",
            "admin_label" 	=> false,
            "heading"		=> "Flipbox round edges",
            "param_name"	=> "flipbox_round_edges",
            "value"			=> array(
                "Disable"	=> "disable",
                "Small"	=> "small",
                "Medium"	=> "medium",
                "Large"	=> "large",
                "Full"	=> "full"
            ),
            "description"	=> "Change flipbox border radius (round edges).",
            "std"			=> "disable",
            "edit_field_class" => "vc_col-xs-6",
        ),
        // CSS Animations
        vc_map_add_css_animation( true ),
        /* Button */
        array(
            "type"			=> "mgt_separator",
            "param_name"	=> generate_separator_name(),
            "heading"		=> "Button settings",
            "group"			=> "Button",
        ),
        array(
            "type"			=> "vc_link",
            //"holder"		=> "div",
            "class" 		=> "hide_in_vc_editor",
            "admin_label" 	=> false,
            "heading"		=> "Button text and link",
            "description"	=> "Leave empty if you don't need button. Button will be added to flipbox back side.",
            "param_name"	=> "button_url",
            "std"			=> "",
            "group"			=> "Button",
        ),
        array(
            "type"			=> "dropdown",
            //"holder"		=> "div",
            "class" 		=> "hide_in_vc_editor",
            "admin_label" 	=> false,
            "heading"		=> "Button style",
            "param_name"	=> "button_style",
            "value"			=> array(
                "Default"	=> "solid",
                "Black"	=> "solid-invert",
                "Grey"	=> "grey",
                "Bordered"	=> "bordered",
                "Bordered Black"	=> "borderedblack",
                "Bordered Grey"	=> "borderedgrey",
                "Bordered White"	=> "borderedwhite",
                "Red"	=> "red",
                "Green"	=> "green",
                "Text link dark"	=> "text",
                "Text link light (use on dark backgrounds)"	=> "textwhite"
            ),
            "description"	=> "Change button style",
            "std"			=> "solid",
            "group"			=> "Button",
            "edit_field_class" => "vc_col-xs-6",
        ),
        array(
            "type"			=> "dropdown",
            //"holder"		=> "div",
            "class" 		=> "hide_in_vc_editor",
            "admin_label" 	=> false,
            "heading"		=> "Button round edges",
            "param_name"	=> "button_round_edges",
            "value"			=> array(
                "Disable"	=> "disable",
                "Small"	=> "small",
                "Medium"	=> "medium",
                "Large"	=> "large",
                "Full"	=> "full"
            ),
            "description"	=> "Change button border radius (round edges)",
            "std"			=> "disable",
            "group"			=> "Button",
            "edit_field_class" => "vc_col-xs-6",
        ),
        array(
            "type"			=> "dropdown",
            //"holder"		=> "div",
            "class" 		=> "hide_in_vc_editor",
            "admin_label" 	=> false,
            "heading"		=> "Button size",
            "param_name"	=> "button_size",
            "value"			=> array(
                "Small"	=> "small",
                "Normal"	=> "normal",
                "Large"	=> "large"
            ),
            "description"	=> "",
            "std"			=> "normal",
            "group"			=> "Button",
        ),
        array(
            "type"			=> "dropdown",
            //"holder"		=> "div",
            "class" 		=> "hide_in_vc_editor",
            "admin_label" 	=> false,
            "heading"		=> "Button text size",
            "param_name"	=> "button_text_size",
            "value"			=> array(
                "Small"	=> "small",
                "Normal"	=> "normal",
                "Large"	=> "large"
            ),
            "description"	=> "",
            "std"			=> "normal",
            "group"			=> "Button",
            "edit_field_class" => "vc_col-xs-6",
        ),
        array(
            "type"			=> "dropdown",
            //"holder"		=> "div",
            "class" 		=> "hide_in_vc_editor",
            "admin_label" 	=> false,
            "heading"		=> "Button text transform",
            "param_name"	=> "button_text_transform",
            "value"			=> array(
                "None"	=> "none",
                "UPPERCASE"	=> "uppercase"
            ),
            "description"	=> "",
            "std"			=> "none",
            "group"			=> "Button",
            "edit_field_class" => "vc_col-xs-6",
        ),
        // Button icon
        array(
            "type"			=> "mgt_separator",
            "param_name"	=> generate_separator_name(),
            "heading"		=> "Button icon settings",
            "group"			=> "Button",
        ),
        array(
            'type' => 'checkbox',
            'heading' => __( 'Add icon?', 'js_composer' ),
            'param_name' => 'button_icon',
            "group"			=> "Button",
            "edit_field_class" => "vc_col-xs-6",
        ),
        array(
            'type' => 'checkbox',
            'heading' => __( 'Add icon separator line?', 'js_composer' ),
            'param_name' => 'button_icon_separator',
            'dependency' => array(
                'element' => 'button_icon',
                'value' => 'true',
            ),
            "group"			=> "Button",
            "edit_field_class" => "vc_col-xs-6",
        ),
        array(
            "type"			=> "dropdown",
            //"holder"		=> "div",
            "class" 		=> "hide_in_vc_editor",
            "admin_label" 	=> false,
            "heading"		=> "Button icon alignment",
            "param_name"	=> "button_icon_position",
            "value"			=> array(
                "Left"	=> "left",
                "Right"	=> "right"
            ),
            "description"	=> "",
            "std"			=> "left",
            'dependency' => array(
                'element' => 'button_icon',
                'value' => 'true',
            ),
            "group"			=> "Button",
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Icon library', 'js_composer' ),
            'value' => array(
                __( 'Font Awesome', 'js_composer' ) => 'fontawesome',
                __( 'Open Iconic', 'js_composer' ) => 'openiconic',
                __( 'Typicons', 'js_composer' ) => 'typicons',
                __( 'Entypo', 'js_composer' ) => 'entypo',
                __( 'Linecons', 'js_composer' ) => 'linecons',
                __( 'Mono Social', 'js_composer' ) => 'monosocial',
                __( 'Material', 'js_composer' ) => 'material',
                __( 'Pe7 Stroke', 'js_composer' ) => 'pe7stroke',
            ),
            'dependency' => array(
                'element' => 'button_icon',
                'value' => 'true',
            ),
            'admin_label' => true,
            'param_name' => 'icon_type',
            'description' => __( 'Select icon library.', 'js_composer' ),
            "std"		=> "fontawesome",
            "group"			=> "Button",
        ),
        array(
            'type' => 'iconpicker',
            'heading' => __( 'Icon', 'js_composer' ),
            'param_name' => 'icon_fontawesome',
            'value' => 'fa fa-adjust',
            // default value to backend editor admin_label
            'settings' => array(
                'emptyIcon' => false,
                // default true, display an "EMPTY" icon?
                'iconsPerPage' => 4000,
                // default 100, how many icons per/page to display, we use (big number) to display all icons in single page
                'type' => 'fontawesome',
            ),
            'dependency' => array(
                'element' => 'icon_type',
                'value' => 'fontawesome',
            ),
            'description' => __( 'Select icon from library.', 'js_composer' ),
            "group"			=> "Button",
        ),
        array(
            'type' => 'iconpicker',
            'heading' => __( 'Icon', 'js_composer' ),
            'param_name' => 'icon_pe7stroke',
            'value' => 'pe-7s-album',
            // default value to backend editor admin_label
            'settings' => array(
                'emptyIcon' => false,
                // default true, display an "EMPTY" icon?
                'iconsPerPage' => 4000,
                'type' => 'pe7stroke',
                // default 100, how many icons per/page to display, we use (big number) to display all icons in single page
            ),
            'dependency' => array(
                'element' => 'icon_type',
                'value' => 'pe7stroke',
            ),
            'description' => __( 'Select icon from library.', 'js_composer' ),
            "group"			=> "Button",
        ),
        array(
            'type' => 'iconpicker',
            'heading' => __( 'Icon', 'js_composer' ),
            'param_name' => 'icon_openiconic',
            'value' => 'vc-oi vc-oi-dial',
            // default value to backend editor admin_label
            'settings' => array(
                'emptyIcon' => false,
                // default true, display an "EMPTY" icon?
                'type' => 'openiconic',
                'iconsPerPage' => 4000,
                // default 100, how many icons per/page to display
            ),
            'dependency' => array(
                'element' => 'icon_type',
                'value' => 'openiconic',
            ),
            'description' => __( 'Select icon from library.', 'js_composer' ),
            "group"			=> "Button",
        ),
        array(
            'type' => 'iconpicker',
            'heading' => __( 'Icon', 'js_composer' ),
            'param_name' => 'icon_typicons',
            'value' => 'typcn typcn-adjust-brightness',
            // default value to backend editor admin_label
            'settings' => array(
                'emptyIcon' => false,
                // default true, display an "EMPTY" icon?
                'type' => 'typicons',
                'iconsPerPage' => 4000,
                // default 100, how many icons per/page to display
            ),
            'dependency' => array(
                'element' => 'icon_type',
                'value' => 'typicons',
            ),
            'description' => __( 'Select icon from library.', 'js_composer' ),
            "group"			=> "Button",
        ),
        array(
            'type' => 'iconpicker',
            'heading' => __( 'Icon', 'js_composer' ),
            'param_name' => 'icon_entypo',
            'value' => 'entypo-icon entypo-icon-note',
            // default value to backend editor admin_label
            'settings' => array(
                'emptyIcon' => false,
                // default true, display an "EMPTY" icon?
                'type' => 'entypo',
                'iconsPerPage' => 4000,
                // default 100, how many icons per/page to display
            ),
            'dependency' => array(
                'element' => 'icon_type',
                'value' => 'entypo',
            ),
            "group"			=> "Button",
        ),
        array(
            'type' => 'iconpicker',
            'heading' => __( 'Icon', 'js_composer' ),
            'param_name' => 'icon_linecons',
            'value' => 'vc_li vc_li-heart',
            // default value to backend editor admin_label
            'settings' => array(
                'emptyIcon' => false,
                // default true, display an "EMPTY" icon?
                'type' => 'linecons',
                'iconsPerPage' => 4000,
                // default 100, how many icons per/page to display
            ),
            'dependency' => array(
                'element' => 'icon_type',
                'value' => 'linecons',
            ),
            'description' => __( 'Select icon from library.', 'js_composer' ),
            "group"			=> "Button",
        ),
        array(
            'type' => 'iconpicker',
            'heading' => __( 'Icon', 'js_composer' ),
            'param_name' => 'icon_monosocial',
            'value' => 'vc-mono vc-mono-fivehundredpx',
            // default value to backend editor admin_label
            'settings' => array(
                'emptyIcon' => false,
                // default true, display an "EMPTY" icon?
                'type' => 'monosocial',
                'iconsPerPage' => 4000,
                // default 100, how many icons per/page to display
            ),
            'dependency' => array(
                'element' => 'icon_type',
                'value' => 'monosocial',
            ),
            'description' => __( 'Select icon from library.', 'js_composer' ),
            "group"			=> "Button",
        ),
        array(
            'type' => 'iconpicker',
            'heading' => __( 'Icon', 'js_composer' ),
            'param_name' => 'icon_material',
            'value' => 'vc-material vc-material-cake',
            // default value to backend editor admin_label
            'settings' => array(
                'emptyIcon' => false,
                // default true, display an "EMPTY" icon?
                'type' => 'material',
                'iconsPerPage' => 4000,
                // default 100, how many icons per/page to display
            ),
            'dependency' => array(
                'element' => 'icon_type',
                'value' => 'material',
            ),
            'description' => __( 'Select icon from library.', 'js_composer' ),
            "group"			=> "Button",
        ),
        // Button effects
        array(
            "type"			=> "mgt_separator",
            "param_name"	=> generate_separator_name(),
            "heading"		=> "Button animation and effects",
            "group"			=> "Button",
        ),
        array(
            "type"			=> "dropdown",
            //"holder"		=> "div",
            "class" 		=> "hide_in_vc_editor",
            "admin_label" 	=> false,
            "heading"		=> "Button hover effect",
            "param_name"	=> "hover_effect",
            "value"			=> array(
                "Default"	=> "default",
                "[Shape] Grow"	=> "grow",
                "[Shape] Shrink"	=> "shrink",
                "[Shape] Pulse"	=> "pulse",
                "[Shape] Pulse Grow"	=> "pulse-grow",
                "[Shape] Pulse Shrink"	=> "pulse-shrink",
                "[Shape] Push"	=> "push",
                "[Shape] Pop"	=> "pop",
                "[Shape] Bounce In"	=> "bounce-in",
                "[Shape] Bounce Out"	=> "bounce-out",
                "[Shape] Rotate"	=> "rotate",
                "[Shape] Grow Rotate"	=> "grow-rotate",
                "[Shape] Float"	=> "float",
                "[Shape] Sink"	=> "sink",
                "[Shape] Bob"	=> "bob",
                "[Shape] Hang"	=> "hang",
                "[Shape] Skew"	=> "skew",
                "[Shape] Skew Forward"	=> "skew-forward",
                "[Shape] Skew Backward"	=> "skew-backward",
                "[Shape] Wobble Horizontal"	=> "wobble-horizontal",
                "[Shape] Wobble Vertical"	=> "wobble-vertical",
                "[Shape] Wobble To Bottom Right"	=> "wobble-to-bottom-right",
                "[Shape] Wobble To Top Right"	=> "wobble-to-top-right",
                "[Shape] Wobble Top"	=> "wobble-top",
                "[Shape] Wobble Bottom"	=> "wobble-bottom",
                "[Shape] Wobble Skew"	=> "wobble-skew",
                "[Shape] Buzz"	=> "buzz",
                "[Shape] Buzz Out"	=> "buzz-out",

                "[Background] Sweep To Right"	=> "sweep-to-right",
                "[Background] Sweep To Left"	=> "sweep-to-left",
                "[Background] Sweep To Bottom"	=> "sweep-to-bottom",
                "[Background] Sweep To Top"	=> "sweep-to-top",
                "[Background] Bounce To Right"	=> "bounce-to-right",
                "[Background] Bounce To Left"	=> "bounce-to-left",
                "[Background] Bounce To Bottom"	=> "bounce-to-bottom",
                "[Background] Bounce To Top"	=> "bounce-to-top",

                "[Icon] Icon Back"	=> "icon-back",
                "[Icon] Icon Forward"	=> "icon-forward",
                "[Icon] Icon Down"	=> "icon-down",
                "[Icon] Icon Up"	=> "icon-up",
                "[Icon] Icon Spin"	=> "icon-spin",
                "[Icon] Icon Drop"	=> "icon-drop",
                "[Icon] Icon Grow"	=> "icon-grow",
                "[Icon] Icon Shrink"	=> "icon-shrink",
                "[Icon] Icon Pulse"	=> "icon-pulse",
                "[Icon] Icon Pulse Grow"	=> "icon-pulse-grow",
                "[Icon] Icon Pulse Shrink"	=> "icon-pulse-shrink",
                "[Icon] Icon Push"	=> "icon-push",
                "[Icon] Icon Pop"	=> "icon-pop",
                "[Icon] Icon Bounce"	=> "icon-bounce",
                "[Icon] Icon Rotate"	=> "icon-rotate",
                "[Icon] Icon Grow Rotate"	=> "icon-grow-rotate",
                "[Icon] Icon Float"	=> "icon-float",
                "[Icon] Icon Sink"	=> "icon-sink",
                "[Icon] Icon Bob"	=> "icon-bob",
                "[Icon] Icon Hang"	=> "icon-hang",
                "[Icon] Icon Wobble Horizontal"	=> "icon-wobble-horizontal",
                "[Icon] Icon Wobble Vertical"	=> "icon-wobble-vertical",
                "[Icon] Icon Buzz"	=> "icon-buzz",
                "[Icon] Icon Buzz Out"	=> "icon-buzz-out",
            ),
            "description"	=> "Change button hover effect (<a href='http://ianlunn.github.io/Hover/' target='_blank'>Preview effects</a>). Please note that some effects works only for regular button styles, without style overrides and round edges.",
            "std"			=> "default",
            "group"			=> "Button",
        ),
        /* Button custom styles */
        array(
            "type"			=> "mgt_separator",
            "param_name"	=> generate_separator_name(),
            "heading"		=> "Button custom styles",
            "group"			=> "Button",
        ),
        array(
            'type' => 'checkbox',
            'heading' => __( 'Override this button styles?', 'js_composer' ),
            'param_name' => 'button_override',
            "description"	=> "Click and scroll down to show advanced button styles options. Please note that custom styled buttons and gradients backgrounds does not support all Button hover effect styles.",
            "group"			=> "Button",
        ),
        array(
            "type"			=> "dropdown",
            //"holder"		=> "div",
            "class" 		=> "hide_in_vc_editor",
            "admin_label" 	=> false,
            "heading"		=> "Button font weight",
            "param_name"	=> "fontweight",
            "value"			=> array(
                "Normal"	=> "normal",
                "Bold"	=> "bold",
                "100"	=> "100",
                "200"	=> "200",
                "300"	=> "300",
                "400"	=> "400",
                "500"	=> "500",
                "600"	=> "600",
                "700"	=> "700",
                "800"	=> "800",
                "900"	=> "900"
            ),
            "group"			=> "Custom Styles",
            "description"	=> "Make sure you loaded font weight that you selected in Google Fonts settings for Header font in Theme Control Panel.",
            "std"			=> "normal",
            "group"			=> "Button",
            'dependency' => array(
                'element' => 'button_override',
                'value' => 'true',
            ),
        ),
        array(
            "type"			=> "colorpicker",
            //"holder"		=> "div",
            "class" 		=> "hide_in_vc_editor",
            "admin_label" 	=> false,
            "heading"		=> "Button background color",
            "param_name"	=> "button_color_bg",
            "description"	=> "Override button background color.",
            "group"			=> "Button",
            "std"			=> "",
            'dependency' => array(
                'element' => 'button_override',
                'value' => 'true',
            ),
        ),
        array(
            'type' => 'checkbox',
            'heading' => __( 'Add gradient to button background color?', 'js_composer' ),
            'param_name' => 'button_bg_grad',
            "description"	=> "Use this to add second gradient color for button background.",
            "group"			=> "Button",
            'dependency' => array(
                'element' => 'button_override',
                'value' => 'true',
            ),
        ),
        array(
            "type"			=> "colorpicker",
            //"holder"		=> "div",
            "class" 		=> "hide_in_vc_editor",
            "admin_label" 	=> false,
            "heading"		=> "Button background second color for gradient",
            "param_name"	=> "button_color_bggrad",
            "description"	=> "Override button background color for gradient.",
            "group"			=> "Button",
            "std"			=> "",
            'dependency' => array(
                'element' => 'button_bg_grad',
                'value' => 'true',
            ),
        ),
        array(
            "type"			=> "colorpicker",
            //"holder"		=> "div",
            "class" 		=> "hide_in_vc_editor",
            "admin_label" 	=> false,
            "heading"		=> "Button hover background color",
            "param_name"	=> "button_color_bghover",
            "description"	=> "Override button hover background color.",
            "group"			=> "Button",
            "std"			=> "",
            'dependency' => array(
                'element' => 'button_override',
                'value' => 'true',
            ),
        ),
        array(
            'type' => 'checkbox',
            'heading' => __( 'Add gradient to button hover background color?', 'js_composer' ),
            'param_name' => 'button_bghover_grad',
            "description"	=> "Use this to add second gradient color for button hover background.",
            "group"			=> "Button",
            'dependency' => array(
                'element' => 'button_override',
                'value' => 'true',
            ),
        ),
        array(
            "type"			=> "colorpicker",
            //"holder"		=> "div",
            "class" 		=> "hide_in_vc_editor",
            "admin_label" 	=> false,
            "heading"		=> "Button hover background second color for gradient",
            "param_name"	=> "button_color_bghovergrad",
            "description"	=> "Override button hover background color for gradient.",
            "group"			=> "Button",
            "std"			=> "",
            'dependency' => array(
                'element' => 'button_bghover_grad',
                'value' => 'true',
            ),
        ),
        array(
            "type"			=> "colorpicker",
            //"holder"		=> "div",
            "class" 		=> "hide_in_vc_editor",
            "admin_label" 	=> false,
            "heading"		=> "Button text color",
            "param_name"	=> "button_color_text",
            "description"	=> "Override button text color.",
            "group"			=> "Button",
            "std"			=> "",
            'dependency' => array(
                'element' => 'button_override',
                'value' => 'true',
            ),
            "edit_field_class" => "vc_col-xs-6",
        ),
        array(
            "type"			=> "colorpicker",
            //"holder"		=> "div",
            "class" 		=> "hide_in_vc_editor",
            "admin_label" 	=> false,
            "heading"		=> "Button hover text color",
            "param_name"	=> "button_color_texthover",
            "description"	=> "Override button hover text color.",
            "group"			=> "Button",
            "std"			=> "",
            'dependency' => array(
                'element' => 'button_override',
                'value' => 'true',
            ),
            "edit_field_class" => "vc_col-xs-6",
        ),
        array(
            'type' => 'checkbox',
            'heading' => __( 'Enable button border?', 'js_composer' ),
            'param_name' => 'button_border',
            "description"	=> "Use this to add solid border to button.",
            "group"			=> "Button",
            'dependency' => array(
                'element' => 'button_override',
                'value' => 'true',
            ),
        ),
        array(
            "type"			=> "textfield",
            //"holder"		=> "div",
            "class" 		=> "hide_in_vc_editor",
            "admin_label" 	=> false,
            "heading"		=> "Border width (in pixels)",
            "description"	=> "Add border width here, for example: 3",
            "param_name"	=> "button_border_width",
            "group"			=> "Button",
            "std"			=> "2",
            'dependency' => array(
                'element' => 'button_border',
                'value' => 'true',
            ),
        ),
        array(
            "type"			=> "colorpicker",
            //"holder"		=> "div",
            "class" 		=> "hide_in_vc_editor",
            "admin_label" 	=> false,
            "heading"		=> "Button border color",
            "param_name"	=> "button_color_border",
            "description"	=> "Override button border color.",
            "group"			=> "Button",
            "std"			=> "",
            'dependency' => array(
                'element' => 'button_border',
                'value' => 'true',
            ),
            "edit_field_class" => "vc_col-xs-6",
        ),
        array(
            "type"			=> "colorpicker",
            //"holder"		=> "div",
            "class" 		=> "hide_in_vc_editor",
            "admin_label" 	=> false,
            "heading"		=> "Button hover border color",
            "param_name"	=> "button_color_borderhover",
            "description"	=> "Override button hover border color.",
            "group"			=> "Button",
            "std"			=> "",
            'dependency' => array(
                'element' => 'button_border',
                'value' => 'true',
            ),
            "edit_field_class" => "vc_col-xs-6",
        ),
        /* Format options */
        array(
            "type"			=> "mgt_separator",
            "param_name"	=> generate_separator_name(),
            "heading"		=> "Override block content elements styles",
            "group"			=> "Format",
        ),
        array(
            "type"			=> "textfield",
            //"holder"		=> "div",
            "class" 		=> "hide_in_vc_editor",
            "admin_label" 	=> false,
            "heading"		=> "Content padding",
            "description"	=> "For ex.: 50px 40px",
            "param_name"	=> "format_content_padding",
            "group"			=> "Format",
            "std"			=> "",
        ),
        array(
            "type"			=> "textfield",
            //"holder"		=> "div",
            "class" 		=> "hide_in_vc_editor",
            "admin_label" 	=> false,
            "heading"		=> "Header font size",
            "description"	=> "For ex.: 25px",
            "param_name"	=> "format_header_fontsize",
            "group"			=> "Format",
            "std"			=> "",
            "edit_field_class" => "vc_col-xs-6",
        ),
        array(
            "type"			=> "textfield",
            //"holder"		=> "div",
            "class" 		=> "hide_in_vc_editor",
            "admin_label" 	=> false,
            "heading"		=> "Header padding",
            "description"	=> "For ex.: 0 0 30px 0",
            "param_name"	=> "format_header_padding",
            "group"			=> "Format",
            "std"			=> "",
            "edit_field_class" => "vc_col-xs-6",
        ),
        array(
            "type"			=> "textfield",
            //"holder"		=> "div",
            "class" 		=> "hide_in_vc_editor",
            "admin_label" 	=> false,
            "heading"		=> "Description font size",
            "description"	=> "For ex.: 20px",
            "param_name"	=> "format_description_fontsize",
            "group"			=> "Format",
            "std"			=> "",
            "edit_field_class" => "vc_col-xs-6",
        ),
        array(
            "type"			=> "textfield",
            //"holder"		=> "div",
            "class" 		=> "hide_in_vc_editor",
            "admin_label" 	=> false,
            "heading"		=> "Description padding",
            "description"	=> "For ex.: 0 0 20px 0",
            "param_name"	=> "format_description_padding",
            "group"			=> "Format",
            "std"			=> "",
            "edit_field_class" => "vc_col-xs-6",
        ),
        array(
            "type"			=> "textfield",
            //"holder"		=> "div",
            "class" 		=> "hide_in_vc_editor",
            "admin_label" 	=> false,
            "heading"		=> "Icon font size",
            "description"	=> "For ex.: 50px",
            "param_name"	=> "format_icon_fontsize",
            "group"			=> "Format",
            "std"			=> "",
            "edit_field_class" => "vc_col-xs-6",
        ),
        array(
            'type' => 'css_editor',
            'heading' => __( 'Css' ),
            'param_name' => 'css',
            'group' => __( 'Content Design options' ),
        ),
        array(
            "type" => "textfield",
            "heading" => esc_attr__("Extra class name", 'asvc'),
            "param_name" => "el_class",
            "group"			=> "Format",
            "description" => esc_attr__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'asvc')
        ),        
    )

    
));




function asvc_shortcode_flipbox_wp($atts, $sc_content = null) {
    extract(shortcode_atts(array(
        /* Front side */
        'header_text_front' => '',
        'description_text_front' => '',
        'header_color_front' => '',
        'description_color_front' => '',
        'background_color_front' => '',
        'background_image_front' => '',
        'block_border_front' => false,
        'block_border_front_width' => '',
        'block_border_front_style' => 'solid',
        'block_border_front_color' => '',
        'front_icon' => false,
        'front_icon_color' => '',
        'front_icon_type' => 'fontawesome',
        'front_icon_fontawesome' => 'fa fa-adjust',
        'front_icon_pe7stroke' => 'pe-7s-album',
        'front_icon_openiconic' => 'vc-oi vc-oi-dial',
        'front_icon_typicons' => 'typcn typcn-adjust-brightness',
        'front_icon_entypo' => 'entypo-icon entypo-icon-note',
        'front_icon_linecons' => 'vc_li vc_li-heart',
        'front_icon_monosocial' => 'vc-mono vc-mono-fivehundredpx',
        'front_icon_material' => 'vc-material vc-material-cake',
        /* Back side */
        'header_text_back' => '',
        'description_text_back' => '',
        'header_color_back' => '',
        'description_color_back' => '',
        'background_color_back' => '',
        'background_image_back' => '',
        'block_border_back' => false,
        'block_border_back_width' => '',
        'block_border_back_style' => 'solid',
        'block_border_back_color' => '',
        /* Styles and effects */
        'flipbox_animation' => 'horizontal',
        'block_height' => '',
        'flipbox_round_edges' => 'disable',
        'css_animation' => 'none',
        /* Button - back side */
        'button_url' => '',
        'button_link_lightbox' => false,
        'button_style' => 'solid',
        'button_round_edges' => 'disable',
        'button_size' => 'normal',
        'button_align' => 'center',
        'button_text_size' => 'normal',
        'button_text_transform' => 'none',
        // Icon
        'button_icon' => false,
        'button_icon_position' => 'left',
        'button_icon_separator' => false,
        'icon_type' => 'fontawesome',
        'icon_fontawesome' => 'fa fa-adjust',
        'icon_pe7stroke' => 'pe-7s-album',
        'icon_openiconic' => 'vc-oi vc-oi-dial',
        'icon_typicons' => 'typcn typcn-adjust-brightness',
        'icon_entypo' => 'entypo-icon entypo-icon-note',
        'icon_linecons' => 'vc_li vc_li-heart',
        'icon_monosocial' => 'vc-mono vc-mono-fivehundredpx',
        'icon_material' => 'vc-material vc-material-cake',
        // Animation
        'hover_effect' => 'default',
        /* Button custom styles */
        'button_override' => false,
        'fontweight' => 'normal',
        'button_color_bg' => '',
        'button_bg_grad' => false,
        'button_color_bggrad' => '',
        'button_color_bghover' => '',
        'button_bghover_grad' => false,
        'button_color_bghovergrad' => '',
        'button_color_text' => '',
        'button_color_texthover' => '',
        'button_border' => false,
        'button_border_width' => 2,
        'button_color_border' => '',
        'button_color_borderhover' => '',
        /* Format */
        'format_header_fontsize' => '',
        'format_header_padding' => '',
        'format_description_fontsize' => '',
        'format_description_padding' => '',
        'format_icon_fontsize' => '',
        'format_content_padding' => '',
        /* CSS */
        'css' => '',
        'el_class' => '',
    ), $atts));
    
    
    wp_register_style( 'flipbox-adv-css', plugins_url( '/css/flip-box-advanced.css',  __FILE__) );
    wp_enqueue_style( 'flipbox-adv-css' ); 
    wp_register_style( 'flipbox-btn-hover-css', plugins_url( '../promo-block/css/hover-min.css',  __FILE__) );
    wp_enqueue_style( 'flipbox-btn-hover-css' );    
      
    wp_register_script('flipbox-adv-js', plugins_url('/js/flip-box-advanced.js', __FILE__), array('jquery'));
    wp_enqueue_script('flipbox-adv-js');    
    
    
    
    ob_start();

    $mgt_custom_css = '';
    $add_class = '';

    $style_front = '';
    $style_back = '';

    $add_class .= ' mgt-flipbox-animation-'.$flipbox_animation;
    $add_class .= ' mgt-flipbox-round-edges-'.$flipbox_round_edges;

    // Background image for front side
    $background_image_front_data = wp_get_attachment_image_src( $background_image_front, 'full' );

    if(trim($background_image_front_data[0]) !== '') {
        $style_front .= 'background-image: url('.$background_image_front_data[0].');';
    }

    // Background image for back side
    $background_image_back_data = wp_get_attachment_image_src( $background_image_back, 'full' );

    if(trim($background_image_back_data[0]) !== '') {
        $style_back .= 'background-image: url('.$background_image_back_data[0].');';
    }

    // Preparing content
    if($header_text_front !== '') {
        $header_text_front = '<h4 class="mgt-flipbox-header">'.$header_text_front.'</h4>';
    }

    if($header_text_back !== '') {
        $header_text_back = '<h4 class="mgt-flipbox-header">'.$header_text_back.'</h4>';
    }

    if($description_text_front !== '') {
        $description_text_front = '<div class="mgt-flipbox-description">'.$description_text_front.'</div>';
    }

    if($description_text_back !== '') {
        $description_text_back = '<div class="mgt-flipbox-description">'.$description_text_back.'</div>';
    }

    // Button
    $button_data = vc_build_link($button_url);

    $button_html = '';

    if($button_data['url'] !== '') {

        $button_html = '<div class="mgt-flipbox-button">'.do_shortcode('[asvc_button button_link="'.$button_url.'" button_link_lightbox="'.$button_link_lightbox.'" button_style="'.$button_style.'" hover_effect="'.$hover_effect.'" button_round_edges="'.$button_round_edges.'" button_icon="'.$button_icon.'" button_icon_position="'.$button_icon_position.'" button_icon_separator="'.$button_icon_separator.'" icon_type="'.$icon_type.'" icon_fontawesome="'.$icon_fontawesome.'" icon_openiconic="'.$icon_openiconic.'" icon_typicons="'.$icon_typicons.'" icon_entypo="'.$icon_entypo.'" icon_linecons="'.$icon_linecons.'" icon_monosocial="'.$icon_monosocial.'" icon_material="'.$icon_material.'" icon_pe7stroke="'.$icon_pe7stroke.'" button_size="'.$button_size.'" text_size="'.$button_text_size.'" text_transform="'.$button_text_transform.'" button_align="center" button_display="newline"  button_override="'.$button_override.'" fontweight="'.$fontweight.'" button_color_bg="'.$button_color_bg.'" button_bg_grad="'.$button_bg_grad.'" button_color_bggrad="'.$button_color_bggrad.'" button_color_bghover="'.$button_color_bghover.'" button_bghover_grad="'.$button_bghover_grad.'" button_color_bghovergrad="'.$button_color_bghovergrad.'" button_color_text="'.$button_color_text.'" button_color_texthover="'.$button_color_texthover.'" button_border="'.$button_border.'" button_border_width="'.$button_border_width.'" button_color_border="'.$button_color_border.'" button_color_borderhover="'.$button_color_borderhover.'"]'.'</div>');

    }

    // Custom CSS
    $unique_block_id = rand(1000000,90000000);

    $unique_class_name = 'mgt-flipbox-'.$unique_block_id;

    // FORMAT
    // Content
    if($format_content_padding !== '') {
        	$mgt_custom_css .= "
            .$unique_class_name.mgt-flipbox .mgt-flipbox-front-inner,
            .$unique_class_name.mgt-flipbox .mgt-flipbox-back-inner {
                padding: $format_content_padding!important;
            }
        ";
        }

    // Header 
        if($format_header_fontsize !== '') {
        	$mgt_custom_css .= "
            .$unique_class_name.mgt-flipbox h4.mgt-flipbox-header {
                font-size: $format_header_fontsize!important;
                line-height: normal!important;
            }
        ";
        }
        if($format_header_padding !== '') {
        	$mgt_custom_css .= "
            .$unique_class_name.mgt-flipbox h4.mgt-flipbox-header {
                padding: $format_header_padding!important;
            }
        ";
        }

        // Description
        if($format_description_fontsize !== '') {
        	$mgt_custom_css .= "
            .$unique_class_name.mgt-flipbox .mgt-flipbox-description {
                font-size: $format_description_fontsize!important;
            }
        ";
        }

        if($format_description_padding !== '') {
        	$mgt_custom_css .= "
            .$unique_class_name.mgt-flipbox .mgt-flipbox-description {
                padding: $format_description_padding!important;
            }
        ";
        }

        // Front
        if($header_color_front !== '') {
        	$mgt_custom_css .= "
            .$unique_class_name.mgt-flipbox .mgt-flipbox-front h4.mgt-flipbox-header {
                color: $header_color_front!important;
            }
        ";
        }

        if($description_color_front !== '') {
        	$mgt_custom_css .= "
            .$unique_class_name.mgt-flipbox .mgt-flipbox-front .mgt-flipbox-description {
                color: $description_color_front!important;
            }
        ";
        }

        if( !empty($background_color_front) ) {
        	$mgt_custom_css .= "
            .$unique_class_name.mgt-flipbox .mgt-flipbox-front-inner {
                background-color: $background_color_front!important;
            }
        ";
        }
        
        if( isset($background_image_front_data) ) {
        	$mgt_custom_css .= "
            .$unique_class_name.mgt-flipbox .mgt-flipbox-front-inner {
                background-color: rgba(0, 0, 0, 0.50);
            }
        ";
        }        

        if($block_border_front) {
        	$mgt_custom_css .= "
            .$unique_class_name.mgt-flipbox .mgt-flipbox-front {
                border: ".$block_border_front_width."px $block_border_front_style $block_border_front_color!important;
            }
        ";
        }

        // Back
        if($header_color_back !== '') {
        	$mgt_custom_css .= "
            .$unique_class_name.mgt-flipbox .mgt-flipbox-back h4.mgt-flipbox-header {
                color: $header_color_back!important;
            }
        ";
        }

        if( !empty($background_color_back) ) {
        	$mgt_custom_css .= "
            .$unique_class_name.mgt-flipbox .mgt-flipbox-back-inner {
                background-color: $background_color_back!important;
            }
        ";
        }
        
        if( isset($background_image_back_data) ) {
        	$mgt_custom_css .= "
            .$unique_class_name.mgt-flipbox .mgt-flipbox-back-inner {
                background-color: rgba(0, 0, 0, 0.50);
            }
        ";
        }        

        if($description_color_back !== '') {
        	$mgt_custom_css .= "
            .$unique_class_name.mgt-flipbox .mgt-flipbox-back .mgt-flipbox-description {
                color: $description_color_back!important;
            }
        ";
        }

        if($block_border_back) {
        	$mgt_custom_css .= "
            .$unique_class_name.mgt-flipbox .mgt-flipbox-back {
                border: ".$block_border_back_width."px $block_border_back_style $block_border_back_color!important;
            }
        ";
        }

        // Icon
        if($front_icon_color !== '') {
        	$mgt_custom_css .= "
            .$unique_class_name.mgt-flipbox .mgt-flipbox-front .mgt-flipbox-icon i {
                color: $front_icon_color!important;
            }
        ";
        }

        if($format_icon_fontsize !== '') {
        	$mgt_custom_css .= "
            .$unique_class_name.mgt-flipbox .mgt-flipbox-front .mgt-flipbox-icon i {
                font-size: $format_icon_fontsize!important;
            }
        ";
        }

        // Block height
        if($block_height !== '') {
        	$mgt_custom_css .= "
            .$unique_class_name.mgt-flipbox {
                height: $block_height!important;
            }
        ";
        }

        if($mgt_custom_css !== '') {
            $mgt_custom_css = str_replace(array("\r", "\n", "  ", "	"), '', $mgt_custom_css);
            echo "<style scoped='scoped'>$mgt_custom_css</style>"; // This variable contains user Custom CSS code and can't be escaped with WordPress functions.
        }

    $add_class .= ' '.$unique_class_name;

    $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), '', $atts );

    // Subheader icon
    if($front_icon == true) {

        // Load VC icons libraries
        vc_iconpicker_editor_jscss();

        switch($front_icon_type) {
            case 'fontawesome':
                $front_icon_html = '<i class="'.$front_icon_fontawesome.'"></i>';
            break;
            case 'openiconic':
                $front_icon_html = '<i class="'.$front_icon_openiconic.'"></i>';
            break;
            case 'typicons':
                $front_icon_html = '<i class="'.$front_icon_typicons.'"></i>';
            break;
            case 'entypo':
                $front_icon_html = '<i class="'.$front_icon_entypo.'"></i>';
            break;
            case 'linecons':
                $front_icon_html = '<i class="'.$front_icon_linecons.'"></i>';
            break;
            case 'monosocial':
                $front_icon_html = '<i class="'.$front_icon_monosocial.'"></i>';
            break;
            case 'material':
                $front_icon_html = '<i class="'.$front_icon_material.'"></i>';
            break;
           case 'pe7stroke':
                $front_icon_html = '<i class="'.$front_icon_pe7stroke.'"></i>';
            break;
        }

        $front_icon_html = '<div class="mgt-flipbox-icon">'.$front_icon_html.'</div>';

    } else {
        $front_icon_html = '';
    }

    // CSS Animation
    if($css_animation !== 'none') {

        // Code from /wp-content/plugins/js_composer/include/classes/shortcodes/shortcodes.php:640, public function getCSSAnimation( $css_animation )
        $animation_css_class = ' wpb_animate_when_almost_visible wpb_'.$css_animation.' '.$css_animation;

        // Load animation JS
        wp_enqueue_script( 'waypoints' );
        wp_enqueue_style( 'animate-css' );

    } else {
        $animation_css_class = '';
    }

    // Show flipbox
    echo '<h3>This flipbox is for pro version. You can purchase pro version <a href="http://codenpy.com/item/flipbox-addon-visual-composer/">from here</a></h3>';

    $sc_content = ob_get_contents();
    ob_end_clean();
    return $sc_content;
}

add_shortcode("favc_flipbox_advanced", "asvc_shortcode_flipbox_wp");

