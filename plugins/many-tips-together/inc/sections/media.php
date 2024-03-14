<?php
/**
 * Section Media config
 * 
 * @package Admin Tweaks
 */

defined( 'ABSPATH' ) || exit;

\Redux::set_section(
	$adtw_option,
	array(
		'title'  => esc_html__( 'Media', 'mtt' ),
        'id'     => 'media',
        'icon'   => 'el el-picture',
        'fields' => [
            array( ####### MEDIA
				'id'       => 'media-1',
				'type'     => 'section',
				'title'    => false,
				'indent'   => false, 
			),
            array( # Sanitize filename
                'id'       => 'media_sanitize_filename',
                'type'     => 'switch',
                'title'    => esc_html__( 'Sanitize filename', 'mtt' ),
                'desc' => sprintf( 
                    esc_html__( 'Removes symbols, spaces, latin and other languages characters from uploaded files and gives them "permalink" structure (clean characters, only lowercase and dahes).%s Code by: %s', 'mtt' ), 
                    '<br />',
                    ADTW()->makeTipCredit( 
                        'toscho', 
                        'https://github.com/toscho/Germanix-WordPress-Plugin'
                    ) 
                ),
                'default'  => false,
            ),
            array( # SVG upload
                'id'       => 'media_allow_svg',
                'type'     => 'switch',
                'title'    => esc_html__( 'Allow SVG file type', 'mtt' ),
                'desc' => sprintf( 
                    esc_html__( 'For a complete solution that scans the files for vulnerabilities, I highly recommend the plugin %s', 'mtt' ), 
                    ADTW()->makeTipCredit( 
                        'Safe SVG', 
                        'https://wordpress.org/plugins/safe-svg/'
                    ) 
                ),
                'default'  => false,
            ),
            array( # Big image threshold
                'id'       => 'media_big_image_disable',
                'type'     => 'switch',
                'title'    => esc_html__( 'Disable big image threshold', 'mtt' ),
                'desc' => sprintf( 
                    esc_html__( 'If an image height or width is above the default threshold of 2560px, it will be scaled down, with the threshold being used as max-height and max-width value. The scaled-down image will be used as the largest available size. See %s', 'mtt' ), 
                    ADTW()->makeTipCredit( 
                        'documentation for details', 
                        'https://make.wordpress.org/core/2019/10/09/introducing-handling-of-big-images-in-wordpress-5-3/'
                    ) 
                ),
                'default'  => false,
            ),
            array( ## RSS time
                'id'       => 'media_big_image_size',
                'type'     => 'text',
                'title'    => esc_html__( 'Change the default threshold for big images', 'mtt' ),
                'desc'     => esc_html__('Leave empty to keep the default of 2560px'),
                'validate' => 'numeric',
                'required' => array( 'media_big_image_disable', '=', false ),
            ),
            array( # [dummy]
                'id'   =>'divider_1',
                'desc' => false,
                'type' => 'divide'
            ),
            array( ####### LISTINGS
				'id'       => 'media-3',
				'type'     => 'section',
				'title'    => esc_html__('LIST MODE'),
				'subtitle'    => esc_html__('This options only work when viewing the library on List Mode', 'mtt') . "<hr>" .ADTW()->renderHintImg('media-all.jpg'),
				'indent'   => false, 
			),
            array( # Bigger Thumbs on default column
                'id'       => 'media_image_bigger_thumbs',
                'type'     => 'switch',
                'title'    => esc_html__( 'Bigger thumbnails in the default column', 'mtt' ),
                'default'  => false,
            ),
            array( # ID Column
                'id'       => 'media_image_id_column_enable',
                'type'     => 'switch',
                'title'    => esc_html__( 'ID column', 'mtt' ),
                'default'  => false,
            ),
            array( # Image Size column
                'id'       => 'media_image_size_column_enable',
                'type'     => 'switch',
                'title'    => esc_html__( 'Image size column', 'mtt' ),
                'desc' => esc_html__( 'Tip via:', 'mtt' )
                    . ' <a href="http://wordpress.stackexchange.com/q/30894/12615">WordPress Answers</a>',
                    'default'  => false,
                ),
                array( # List All Thumbs
                'id'       => 'media_image_thubms_list_column_enable',
                'type'     => 'switch',
                'title'    => esc_html__( 'Column listing all thumbnails', 'mtt' ),
                'desc' => esc_html__( 'Tip via: ', 'mtt' )
                    . ' <a href="http://wordpress.stackexchange.com/q/7757/12615">WordPress Answers</a>',                   
                'default'  => false,
            ),
            array( # EXIF
                'id'       => 'media_camera_exif',
                'type'     => 'switch',
                'title'    => esc_html__( 'Show media metadata (audio/image/video)', 'mtt' ),
                'desc' => sprintf(
                    esc_html__( 'Camera exif info, MP3 ID3 and video metadata are stored as post metadata, accessible via %s.', 'mtt' ),
                    '<code>_wp_attachment_metadata</code>'
                ),
                'default'  => false,
            ),
        ]
	)
);