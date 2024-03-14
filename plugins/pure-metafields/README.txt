=== Pure Metafields ===
Contributors: themepure
Donate link: https://help.themepure.net/support/
Tags: Metabox, Metafields, Custom Post Field, Post Metafield, Post Metabox, Page Metabox, Page Metafield, Custom Field
Requires PHP: 7.4
Requires at least: 5.6
Tested up to: 6.4
Stable tag: 1.1.9
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Pure Metafields is a simple and very light weight plugin that is used to create custom metabox for wordpress any post type like page, post event your custom post can support it.

== Description ==

The Pure Metafields Plugin is a powerful tool designed to enhance the functionality and flexibility of your WordPress website. With this plugin, you can easily create and manage custom meta boxes, adding extra fields and data to your posts, pages, and custom post types.

== Key Features ==
✅ Custom Meta Boxes: Create unlimited custom meta boxes with ease. Define the title, placement, and priority of each meta box to suit your needs.

✅ Flexible Field Types: The plugin offers a wide range of field types to choose from, including text, textarea, select, checkbox, radio buttons, date picker, and more. Customize the field options and validation rules effortlessly.

✅ Repeatable Fields: Enable the ability to repeat fields, allowing users to add multiple instances of the same field dynamically. Perfect for scenarios where you need to capture multiple sets of data.

✅ Conditional Logic: Set up conditional logic to show or hide fields based on the value of other fields. This feature adds versatility and improves the user experience of your forms.

✅ Built-in Validation: Ensure data accuracy by implementing validation rules for your fields. You can define required fields, validate input formats, and customize error messages effortlessly.

✅ Save and Retrieve Meta Data: The plugin provides simple functions to save and retrieve meta data, allowing you to access and utilize the stored information in your themes or plugins.

✅ Integration with WordPress API: Seamlessly integrate with the WordPress API and extend its functionalities. Hook into actions and filters to manipulate meta data and create dynamic interactions.

[Live Docs](https://themepure.net/plugins/puremetafields/)

== Installation ==

### Automatic Install From WordPress Dashboard

1. Login to your admin panel
2. Navigate to Plugins -> Add New
3. Search **Pure Metafields**
4. Click install and activate respectively.

### Manual Install From WordPress Dashboard

If your server is not connected to the Internet, then you can use this method-

1. Download the plugin by clicking on the red button above. A ZIP file will be downloaded.
2. Login to your site's admin panel and navigate to Plugins -> Add New -> Upload.
3. Click choose file, select the plugin file and click install

### Install Using FTP

If you are unable to use any of the methods due to internet connectivity and file permission issues, then you can use this method-

1. Download the plugin by clicking on the red button above. A ZIP file will be downloaded.
2. Unzip the file.
3. Launch your favorite FTP client. Such as FileZilla, FireFTP, CyberDuck etc. If you are a more advanced user, then you can use SSH too.
4. Upload the folder to `wp-content/plugins/`
5. Log in to your WordPress dashboard.
6. Navigate to Plugins -> Installed
7. Activate the plugin

### How To Use?
Paste the code to your theme's functions.php with the add_filter hook.

### Metbox Add

`<?php
add_filter( 'tp_meta_boxes', 'themepure_metabox' );
function themepure_metabox( $meta_boxes ) {
    $meta_boxes[] = array(
        'metabox_id'       => '_your_id',
        'title'    => esc_html__( 'Your Metabox Title', 'textdomain' ),
        'post_type'=> 'post', // page, custom post type name
        'context'  => 'normal',
        'priority' => 'core',
        'fields'   => array(),
    );

    return $meta_boxes;
}

?>`

### Text Fields

`<?php
// text field for normal text
array(
    'label' => 'Text Field',
    'id'   	=> "_your_id",
    'type' 	=> 'text', // specify the type field
    'placeholder' => '',
    'default' => '', // do not remove default key
)

// textarea field for large text
array(
    'label' => 'Textarea Field',
    'id'   	=> "_your_id",
    'type' 	=> 'textarea', // specify the type field
    'placeholder' => 'Type...',
    'default' 	  => '',
    'conditional' => array()
)
?>`

### Image and Gallery Field

`<?php
array(	
    'label'     => esc_html__( 'Your Title', 'textdomain' ),
    'id'        => "_your_id",
    'type'      => 'image', // specify the type field
    'default'   => '',
    'conditional' => array()
)

// Gallery field for multiple images upload
array(
    
    'label'    => esc_html__( 'Your Title', '' ),
    'id'      => "_your_id",
    'type'    => 'gallery', // specify the type field
    'default' => '',
    'conditional' => array()
)
?>`


### Switch Field

`<?php
array(
    'label'    => esc_html__( 'Your Title', 'textdomain' ),
    'id'      => "_your_id",
    'type'    => 'switch',
    'default' => 'off',
    'conditional' => array()
)
?>`


### Checkbox

`<?php 
array(
    'label'   => esc_html__( 'Files Included', 'pure' ),
    'id'      => "{$prefix}_files_options",
    'type'    => 'checkbox',
    'default' => array(
        'wordpress',
        'react',
        'html',
        'psd',
        'vue'
    ),
    'options' => array(
        'wordpress' => 'Wordpress File Included?',
        'react'     => 'React File Included?',
        'html'		=> 'HTML File Included?',
        'psd'		=> 'PSD Included?',
        'vue'		=> 'Vue JS File Included?'
    )
)
?>`

### Group Button

`<?php
// multiple buttons group field like multiple radio buttons tabs
array(
    
    'label'   => esc_html__( 'Group Buttons Tab', '' ),
    'id'      => "_your_id",
    'desc'    => '',
    'type'    => 'tabs',
    'choices' => array(
        'button_1' => 'Button 1',
        'button_2' => 'Button 2',
        'button_3' => 'Button 3',
    ),
    'default' => 'button_1',
    'conditional' => array()
)
?>`

### Select Field

`<?php
// select field dropdown
array(
    
    'label'           => esc_html__('TP Select Field', 'textdomain'),
    'id'              => "_your_id",
    'type'            => 'select',
    'options'         => array(
        '1' => 'one',
        '2' => 'two ',
        '3' => 'three ',
        '4' => 'four ',
        '5' => 'five ',
    ),
    'placeholder'     => 'Select an item',
    'conditional' => array(),
    'default' => ''
)
?>`

### Datepicker Field

`<?php
// Datepicker for date field
array(
    'label' => 'Datepicker',
    'id'   	=> "_your_id",
    'type' 	=> 'datepicker',
    'placeholder' => '',
    'default' 	  => '',
    'conditional' => array()
)
?>`

### Colorpicker

`<?php
// Colorpicker for color field
array(
    'label' => 'Colorpicker',
    'id'   	=> "_your_id",
    'type' 	=> 'colorpicker',
    'placeholder' => '',
    'default' 	  => '',
    'conditional' => array()
)
?>`

### Select any post type posts

`<?php
// posts select dropdown field ( Lists any post types posts)
array(
    
    'label'           => esc_html__('TP Posts Select', 'textdomain'),
    'id'              => "_your_id",
    'type'            => 'select_posts',
    'post_type'       => 'post', // specify the post type you want to fetch
    'placeholder'     => 'Select a post',
    'conditional' => array(),
    'default' => ''
)
?>`

### Repeater 
In the above code example where fields array paste the repeater array inside fields array

`<?php
array(
    'label'     => esc_html__('Field Title', 'textdomain'),
    'id'        => "_your_id",
    'type'      => 'repeater', // specify the type "repeater" (case sensitive)
    'conditional'   => array(),
    'default'       => array(),
    'fields'        => array(
        array(
            'label'           => esc_html__('Your Title', 'textdomain'),
            'id'              => "_your_id",
            'type'            => 'select',
            'options'         => array(
                'footer_1' => 'Footer 1',
                'footer_2' => 'Footer 2',
                'footer_3' => 'Footer 3'
            ),
            'placeholder'     => 'Select a footer',
            'conditional' => array(),
            'default' => 'footer_1',
        ),
        array(
            'label'           => esc_html__('Select Footer Style', 'textdomain'),
            'id'              => "_footer_style_2",
            'type'            => 'select',
            'options'         => array(
                'footer_1' => 'Footer 1',
                'footer_2' => 'Footer 2',
                'footer_3' => 'Footer 3'
            ),
            'placeholder'     => 'Select a footer',
            'conditional' => array(),
            'default' => 'footer_1',
            'bind'    => "_footer_template_2" // bind the key to be control with conditions
        ),
        array(
            'label'           => esc_html__('Select Footer Template', 'textdomain'),
            'id'              => "_footer_template_2",
            'type'            => 'select_posts',
            'placeholder'     => 'Select a template',
            'post_type'       => 'tp-footer',
            'conditional' => array(
                "_footer_style_2", "==", "footer_2" // First parameter will be the id of control field and so on
            ),
            'default' => '',
        )
    )
)
?>`

### Conditional Field

`<?php
array(
    'label'     => 'Text Field',
    'id'   	    => "_your_id",
    'type' 	    => 'text',
    'placeholder'   => '',
    'default'       => '',
    'conditional'   => array(
        "_id_of_any_field", "any operator", "value_of_that_field"
    )
)

array(
    'label'     => 'Text Field',
    'id'   	    => "_your_id",
    'type' 	    => 'text',
    'placeholder'   => '',
    'default'       => '',
    'conditional'   => array(
        "_field_id", "==", "_field_value"
    )
)
?>`

### For bind with post format

`<?php
add_filter( 'tp_meta_boxes', 'themepure_metabox' );
function themepure_metabox( $meta_boxes ) {
    $meta_boxes[] = array(
        'metabox_id'       => $prefix . '_post_meta_gallery_box',
        'title'    => esc_html__( 'Post Meta Gallery', 'donafund' ),
        'post_type'=> 'post',
        'context'  => 'normal',
        'priority' => 'core',
        'fields'   => array(
            array(
                    
                'label'    => esc_html__( 'Gallery Format', 'textdomain' ),
                'id'      => "{$prefix}_gallery_5",
                'type'    => 'gallery',
                'default' => '',
                'conditional' => array(),
            ),
        ),
        'post_format' => 'gallery' // if u want to bind with post formats
    );

    $meta_boxes[] = array(
        'metabox_id'       => $prefix . '_post_meta_audio_box',
        'title'    => esc_html__( 'Post Meta Audio', 'donafund' ),
        'post_type'=> 'post',
        'context'  => 'normal',
        'priority' => 'core',
        'fields'   => array(
            array(
                'label' => esc_html__( 'Audio Format', 'donafund' ),
                'id'   	=> "{$prefix}_audio_format",
                'type' 	=> 'text',
                'placeholder' => esc_html__( 'Audio url here', 'donafund' ),
                'default' 	  => '',
                'conditional' => array()
            ),
        ),
        'post_format' => 'audio' // if u want to bind with post formats
    );
    $meta_boxes[] = array(
        'metabox_id'       => $prefix . '_post_meta_video_box',
        'title'    => esc_html__( 'Post Meta Video', 'donafund' ),
        'post_type'=> 'post',
        'context'  => 'normal',
        'priority' => 'core',
        'fields'   => array(
            array(
                'label' => esc_html__( 'Video Format', 'donafund' ),
                'id'   	=> "{$prefix}_video_format",
                'type' 	=> 'text',
                'placeholder' => esc_html__( 'Video url here', 'donafund' ),
                'default' 	  => '',
                'conditional' => array()
            ),
        ),
        'post_format' => 'video' // if u want to bind with post formats
    );
    return $meta_boxes;
}

?>`

### Get the meta value for current post

`<?php
$value = function_exists('tpmeta_field')? tpmeta_field('meta_key_id_here') : '';
?>`

### Get the meta value for specific post

`<?php
$value = function_exists('tpmeta_field')? tpmeta_field('meta_key_id_here', $post_id) : '';
?>`

### Get gallery images

`<?php
$tp_gallery_images = function_exists('tpmeta_gallery_field') ? tpmeta_gallery_field('gallery_meta_key') : ''; //tpmeta_gallery_field($string_ids, $size)

foreach($tp_gallery_images as $single_image_src){
    echo $single_image_src['url'] ."<br>";
    echo $single_image_src['alt'] ."<br>";
}
?>`

### Get single image

`<?php
$tp_image = function_exists('tpmeta_image_field') ? tpmeta_image_field('image_meta_key') : ''; // tpmeta_image_field($id, $size)

echo $tp_image['url'];
echo $tp_image['alt'];
?>`

### Get Repeater Data

`<?php
$tp_repeater = function_exists('tpmeta_field') ? tpmeta_field('repeater_meta_key') : ''; // tpmeta_field($meta_key, $post_id)

foreach($tp_repeater as $row){ // Iterate the data with loop
	echo $row['repeater_sub_field_key'] // get the subfield value by repeater inner array field key
}
?>`

### Access Single Image and Gallery Images Inside Repeater

`<?php
$tp_repeater = function_exists('tpmeta_field') ? tpmeta_field('repeater_meta_key') : ''; // tpmeta_field($meta_key, $post_id)

foreach($tp_repeater as $row){ // Iterate the data with loop

     $gallery = tpmeta_gallery_images($row['repeater_sub_field_key'], 'image_size'); 

     $image = tpmeta_image($row['repeater_sub_field_key'], 'image_size');
}
?>`


== Frequently Asked Questions ==
= What is a meta box in WordPress? =
A meta box is a user interface element in the WordPress backend that allows you to add extra fields and data to your posts, pages, or custom post types. It provides a way to extend the default functionality of WordPress and capture additional information relevant to your content.

= Why do I need a meta box plugin? =
A meta box plugin, such as the WordPress Meta Box Plugin, offers a user-friendly and efficient way to create and manage custom meta boxes. It eliminates the need for manual coding and provides a flexible interface for adding fields, defining field types, and implementing validation and conditional logic. Using a plugin saves you time and effort while enhancing the functionality and customization options of your WordPress website.

= Can I create multiple meta boxes? =
Yes, the WordPress Meta Box Plugin allows you to create unlimited custom meta boxes. You can define the title, placement, and priority of each meta box to organize and display the additional fields according to your specific requirements.

= What field types are available in the plugin? =
The plugin offers a wide range of field types to choose from, including text, textarea, select, checkbox, radio buttons, date picker, and more. You can select the most suitable field type for each meta box, depending on the type of data you want to capture.

= Can I repeat fields within a meta box? =
Yes, the plugin supports repeatable fields. This means you can enable the option to add multiple instances of the same field dynamically. It is especially useful when you need to capture multiple sets of data, such as multiple addresses or multiple related items.

= How can I apply conditional logic to my meta boxes? =
The WordPress Meta Box Plugin provides a straightforward way to set up conditional logic for your fields. You can define rules to show or hide fields based on the value of other fields. This feature allows you to create dynamic interactions and display only the relevant fields to the user.

= Can I validate the input data for my meta box fields? =
Yes, the plugin includes built-in validation features. You can define validation rules for your fields, such as required fields, input format restrictions, and customized error messages. This ensures that the data entered by users meets your specific requirements and improves the data accuracy.

= How can I access the meta data saved by the plugin? =
The WordPress Meta Box Plugin provides simple functions to save and retrieve meta data. You can easily access the stored information in your themes or plugins using the provided functions, allowing you to utilize the meta data for various purposes, such as displaying custom information or performing calculations.

= Is the plugin compatible with the WordPress API? =
Yes, the plugin seamlessly integrates with the WordPress API. You can leverage its functionalities by hooking into actions and filters to manipulate meta data and create dynamic interactions. This integration provides extensive possibilities for extending and customizing your WordPress website.

= Where can I find support or report issues? =
For support or to report any issues, please visit the plugin's GitHub repository and open a new issue. We are committed to providing assistance and resolving any problems you may encounter while using the plugin.

= Can I contribute to the plugin's development? =
Yes, contributions are welcome! If you have any ideas or improvements for the plugin, feel free to submit a pull request on the plugin's GitHub repository. We appreciate community involvement and value your suggestions in making the plugin even better.

== Screenshots ==

1. Metafields With Page
2. Metafields Repeater With Product
3. How To Code

== Changelog ==


= 1.1.9  =

* Update: No Update.

= 1.0.1  =

* Update: Docs Updated.

= 1.0.2  =

* Update: Docs Updated.

= 1.0.3  =

* Update: Final Docs Updated previous readme deleted.

= 1.0.4  =

* Update: New UI added.

= 1.0.5  =

* Update: New UI added.

= 1.0.6  =

* Update: User meta issue solved.

= 1.0.7  =

* Update: Fields Column Number Added.

= 1.0.8  =

* Update: Column Issue solved.

= 1.0.9  =

* Update: Checkbox field and multi select added.

= 1.1.0  =

* Update: “custom_settings_page” not found or invalid solved.

= 1.1.1  =

* Update: Repeater dragula js added.

= 1.1.2  =

* Update: Select field issue solved.

= 1.1.3  =

* Update: Fields issue solved.

= 1.1.4  =

* Update: Select Field Updated.
= 1.1.5 =

* Update: Select Field Updated.
= 1.1.6  =

* Update: Select Field Updated.

= 1.1.7  =
* Update: Default value key solved

= 1.1.8  =
* Update: Repeater issue solved

= 1.1.9  =
* Update: Repeater select field issue solved

== Upgrade Notice ==
= 1.0.0 =
You can use a pro version of this plugin.
