<?php

class KcSeoOptions
{

    static function getSchemaTypes() {
        $author_url = '' ;
        $author = get_userdata( get_current_user_id() );
        if( $author && is_object( $author ) ){
            $author_url = $author->user_url;
        }
        $schemas = array(
			
            'article'             => array(
                'pro'    => false,
                'title'  => __("Article", "wp-seo-structured-data-schema"),
                'fields' => array(
                    'active'              => array(
                        'type' => 'checkbox'
                    ),
                    'headline'            => array(
                        'title'    => __('Headline', "wp-seo-structured-data-schema"),
                        'type'     => 'text',
                        'desc'     => __('Article title', "wp-seo-structured-data-schema"),
                        'required' => true
                    ),
                    'mainEntityOfPage'    => array(
                        'title'    => __('Page URL', "wp-seo-structured-data-schema"),
                        'type'     => 'url',
                        'desc'     => __('The canonical URL of the article page', "wp-seo-structured-data-schema"),
                        'required' => true
                    ),
                    'author'              => array(
                        'title'    => __('Author Name', "wp-seo-structured-data-schema"),
                        'type'     => 'text',
                        'desc'     => __('Author display name', "wp-seo-structured-data-schema"),
                        'required' => true
                    ),
                    'author_url' => array(
                        'title'    => __('Author URL', "wp-seo-structured-data-schema"),
                        'type'     => 'url', 
                        'required' => true,
                        'default' => $author_url,
                    ),
                    'image'               => array(
                        'title'    => __('Article Feature Image', "wp-seo-structured-data-schema"),
                        'type'     => 'image',
                        'required' => true,
                        'desc'     => __('Images should be at least 696 pixels wide.<br>Images should be in .jpg, .png, or. gif format.', "wp-seo-structured-data-schema")
                    ),
                    'datePublished'       => array(
                        'title'    => __('Published date', "wp-seo-structured-data-schema"),
                        'type'     => 'text',
                        'class'    => 'kcseo-date',
                        'required' => true,
                        'desc'     => __('Like this: 2021-12-25 14:20:00', "wp-seo-structured-data-schema")
                    ),
                    'dateModified'        => array(
                        'title'    => __('Modified date', "wp-seo-structured-data-schema"),
                        'type'     => 'text',
                        'class'    => 'kcseo-date',
                        'required' => true,
                        'desc'     => __('Like this: 2021-12-25 14:20:00', "wp-seo-structured-data-schema")
                    ),
                    'publisher'           => array(
                        'title'    => __('Publisher', "wp-seo-structured-data-schema"),
                        'type'     => 'text',
                        'desc'     => __('Publisher name or Organization name', "wp-seo-structured-data-schema"),
                        'required' => true
                    ),
                    'publisherImage'      => array(
                        'title'    => __('Publisher Logo', "wp-seo-structured-data-schema"),
                        'type'     => 'image',
                        'desc'     => __('Logos should have a wide aspect ratio, not a square icon.<br>Logos should be no wider than 600px, and no taller than 60px.<br>Always retain the original aspect ratio of the logo when resizing. Ideally, logos are exactly 60px tall with width <= 600px. If maintaining a height of 60px would cause the width to exceed 600px, downscale the logo to exactly 600px wide and reduce the height accordingly below 60px to maintain the original aspect ratio.<br>', "wp-seo-structured-data-schema"),
                        'required' => true
                    ),
                    'description'         => array(
                        'title' => __('Description', "wp-seo-structured-data-schema"),
                        'type'  => 'textarea',
                        'desc'  => __('Short description. New line is not supported.', "wp-seo-structured-data-schema")
                    ),
                    'articleBody'         => array(
                        'title' => __('Article body', "wp-seo-structured-data-schema"),
                        'type'  => 'textarea',
                        'desc'  => __('Article content', "wp-seo-structured-data-schema")
                    ),
                    'alternativeHeadline' => array(
                        'title' => __('Alternative headline', "wp-seo-structured-data-schema"),
                        'type'  => 'text',
                        'desc'  => __('A secondary headline for the article.', "wp-seo-structured-data-schema")
                    ),
                    'video'  => [
                        'title'     => __('Video', "wp-seo-structured-data-schema"),
                        'type'      => 'group', 
                        'fields'    => [
                            'video_heading'  => [
                                'type'  => 'heading',
                                'title' => __('Video', "wp-seo-structured-data-schema")
                            ], 
                            'name' => array( 
                                'type'     => 'text',
                                'title'    => esc_html__('Name', "wp-seo-structured-data-schema"), 
                            ),
                            'description' => array( 
                                'type'  => 'textarea',
                                'title' => esc_html__('Description', "wp-seo-structured-data-schema") 
                            ), 
                            'thumbnailUrl' => array( 
                                'type'  => 'image',
                                'title' => esc_html__('Image', "wp-seo-structured-data-schema") 
                            ),
                            'contentUrl' => array( 
                                'type'     => 'url',
                                'title'    => esc_html__('Content URL', "wp-seo-structured-data-schema") 
                            ),
                            'embedUrl' => array( 
                                'type'     => 'url',
                                'title'    => esc_html__('Embed URL', "wp-seo-structured-data-schema"),
                                'desc'        => esc_html__("A URL pointing to the actual video media file. This file should be in .mpg, .mpeg, .mp4, .m4v, .mov, .wmv, .asf, .avi, .ra, .ram, .rm, .flv, or other video file format.", 'wp-seo-structured-data-schema') 
                            ),
                            'uploadDate' => array( 
                                'type'     => 'text',
                                'title'    => esc_html__('Upload date', "wp-seo-structured-data-schema"),
                                'class' => 'rtrs-date',
                                'required'    => true,
                                'desc'  => esc_html__('Like this: 2021-08-25 14:20:00', "wp-seo-structured-data-schema") 
                            ),
                            'duration' => array( 
                                'type'     => 'text',
                                'title'    => esc_html__('Duration', "wp-seo-structured-data-schema"),
                                'desc'  => esc_html__('Runtime of the movie in ISO 8601 format (for example, "PT2H22M" (142 minutes)).', "wp-seo-structured-data-schema") 
                            ), 
                        ]
                    ],
                    'audio'  => [
                        'title'     => __('Audio', "wp-seo-structured-data-schema"),
                        'type'      => 'group', 
                        'fields'    => [
                            'audio_heading'  => [
                                'type'  => 'heading',
                                'title' => __('Audio', "wp-seo-structured-data-schema")
                            ], 
                            'name' => array( 
                                'type'     => 'text',
                                'title'    => esc_html__('Name', 'wp-seo-structured-data-schema'), 
                                'desc'     => esc_html__("The title of the audio", 'wp-seo-structured-data-schema')
                            ),
                            'description' => array( 
                                'type'     => 'textarea',
                                'title'    => esc_html__('Description', 'wp-seo-structured-data-schema'), 
                                'desc'     => esc_html__("The short description of the audio", 'wp-seo-structured-data-schema')
                            ), 
                            'duration' => array( 
                                'type'  => 'text', 
                                'title' => esc_html__('Duration', 'wp-seo-structured-data-schema'),
                                'desc'  => esc_html__("The duration of the audio in ISO 8601 format.(PT1M33S)", 'wp-seo-structured-data-schema')
                            ),
                            'contentUrl' => array( 
                                'type'        => 'url', 
                                'title'       => esc_html__('Content URL', 'wp-seo-structured-data-schema'),
                                'placeholder' => esc_html__('URL', 'wp-seo-structured-data-schema'),
                                'desc'        => esc_html__("A URL pointing to the actual audio media file. This file should be in .mp3, .wav, .mpc or other audio file format.", 'wp-seo-structured-data-schema')
                            ),
                            'encodingFormat' => array( 
                                'type'  => 'text',
                                'title' => esc_html__('Encoding Format', 'wp-seo-structured-data-schema'),
                                'desc'  => esc_html__("The encoding format of audio like: 'audio/mpeg'", 'wp-seo-structured-data-schema')                    
                            ),
                        ]
                    ], 
                )
            ),
            'TechArticle'       => array(
                'pro'    => false,
                'title'  => __('Tech Article', "wp-seo-structured-data-schema"),
                'fields' => array(
                    'active'              => array(
                        'type' => 'checkbox'
                    ),
                    'headline'            => array(
                        'title'    => __('Headline', "wp-seo-structured-data-schema"),
                        'type'     => 'text',
                        'desc'     => __('Article title', "wp-seo-structured-data-schema"),
                        'required' => true
                    ),
                    
                    'mainEntityOfPage'    => array(
                        'title'    => __('Page URL', "wp-seo-structured-data-schema"),
                        'type'     => 'url',
                        'desc'     => __('The canonical URL of the article page', "wp-seo-structured-data-schema"),
                        'required' => true
                    ),
                    'author_type'         => array(
                        'title'       => __('Author Type', "wp-seo-structured-data-schema"),
                        'type'        => 'select',
                        'recommended' => true,
                        'empty'       => __("Select one", "wp-seo-structured-data-schema"),
                        'options'     => array(
                            'Person'  => 'Person',
                            'Organization'  => 'Organization'
                        ),
                    ),
                    'author'              => array(
                        'title'    => __('Author Name', "wp-seo-structured-data-schema"),
                        'type'     => 'text',
                        'desc'     => __('Author display name', "wp-seo-structured-data-schema"),
                        'required' => true
                    ),
                    'author_url' => array(
                        'title'    => __('Author URL', "wp-seo-structured-data-schema"),
                        'type'     => 'url', 
                        'required' => true,
                        'default' => $author_url,
                    ),
                    'author_description'         => array(
                        'title' => __('Author Description', "wp-seo-structured-data-schema"),
                        'type'  => 'textarea',
                        'desc'  => __('Short description. New line is not supported.', "wp-seo-structured-data-schema")
                    ),
                    'image'               => array(
                        'title'    => __('Article Feature Image', "wp-seo-structured-data-schema"),
                        'type'     => 'image',
                        'required' => true,
                        'desc'     => __('Images should be at least 696 pixels wide.<br>Images should be in .jpg, .png, or. gif format.', "wp-seo-structured-data-schema")
                    ),
                    'datePublished'       => array(
                        'title'    => __('Published date', "wp-seo-structured-data-schema"),
                        'type'     => 'text',
                        'class'    => 'kcseo-date',
                        'required' => true,
                        'desc'     => __('Like this: 2021-12-25 14:20:00', "wp-seo-structured-data-schema")
                    ),
                    'dateModified'        => array(
                        'title'    => __('Modified date', "wp-seo-structured-data-schema"),
                        'type'     => 'text',
                        'class'    => 'kcseo-date',
                        'required' => true,
                        'desc'     => __('Like this: 2021-12-25 14:20:00', "wp-seo-structured-data-schema")
                    ),
                    'publisher'           => array(
                        'title'    => __('Publisher', "wp-seo-structured-data-schema"),
                        'type'     => 'text',
                        'desc'     => __('Publisher name or Organization name', "wp-seo-structured-data-schema"),
                        'required' => true
                    ),
                   'publisherImage'      => array(
                        'title'    => __('Publisher Logo', "wp-seo-structured-data-schema"),
                        'type'     => 'image',
                        'desc'     => __('Logos should have a wide aspect ratio, not a square icon.<br>Logos should be no wider than 600px, and no taller than 60px.<br>Always retain the original aspect ratio of the logo when resizing. Ideally, logos are exactly 60px tall with width <= 600px. If maintaining a height of 60px would cause the width to exceed 600px, downscale the logo to exactly 600px wide and reduce the height accordingly below 60px to maintain the original aspect ratio.<br>', "wp-seo-structured-data-schema"),
                        'required' => true
                    ),
                    'description'         => array(
                        'title' => __('Description', "wp-seo-structured-data-schema"),
                        'type'  => 'textarea',
                        'desc'  => __('Short description. New line is not supported.', "wp-seo-structured-data-schema")
                    ),

                    'articleBody'         => array(
                        'title' => __('Article body', "wp-seo-structured-data-schema"),
                        'type'  => 'textarea',
                        'desc'  => __('Article content', "wp-seo-structured-data-schema")
                    ),
                    'keywords'            => array(
                        'title'    => __('Keywords', "wp-seo-structured-data-schema"),
                        'type'     => 'text',
                        'desc'     => __('Article title', "wp-seo-structured-data-schema"),
                        'required' => true
                    ),
                    
                )
            ),
            'blog_posting'        => array(
                'pro'    => false,
                'title'  => __('Blog Posting', "wp-seo-structured-data-schema"),
                'fields' => array(
                    'active'           => array(
                        'type' => 'checkbox'
                    ),
                    'headline'         => array(
                        'title'    => __('Headline', "wp-seo-structured-data-schema"),
                        'type'     => 'text',
                        'desc'     => __('Blog posting title', "wp-seo-structured-data-schema"),
                        'required' => true
                    ),
                    'mainEntityOfPage' => array(
                        'title'    => __('Page URL', "wp-seo-structured-data-schema"),
                        'type'     => 'url',
                        'desc'     => __('The canonical URL of the article page', "wp-seo-structured-data-schema"),
                        'required' => true
                    ),
                    'author'           => array(
                        'title'    => __('Author name', "wp-seo-structured-data-schema"),
                        'type'     => 'text',
                        'desc'     => __('Author display name', "wp-seo-structured-data-schema"),
                        'required' => true
                    ),
                    'author_url' => array(
                        'title'    => __('Author URL', "wp-seo-structured-data-schema"),
                        'type'     => 'url', 
                        'required' => true,
                        'default' => $author_url
                    ),
                    'image'            => array(
                        'title'    => __('Feature Image', "wp-seo-structured-data-schema"),
                        'type'     => 'image',
                        'desc'     => __("The representative image of the article. Only a marked-up image that directly belongs to the article should be specified.<br> Images should be at least 696 pixels wide. <br>Images should be in .jpg, .png, or. gif format.", "wp-seo-structured-data-schema"),
                        'required' => true
                    ),
                    'datePublished'    => array(
                        'title'    => __('Published date', "wp-seo-structured-data-schema"),
                        'type'     => 'text',
                        'class'    => 'kcseo-date',
                        'desc'     => __('Like this: 2021-12-25 14:20:00', "wp-seo-structured-data-schema"),
                        'required' => true
                    ),
                    'dateModified'     => array(
                        'title'    => __('Modified date', "wp-seo-structured-data-schema"),
                        'type'     => 'text',
                        'class'    => 'kcseo-date',
                        'desc'     => __('Like this: 2021-12-25 14:20:00', "wp-seo-structured-data-schema"),
                        'required' => true
                    ),
                    'publisher'        => array(
                        'title'    => __('Publisher', "wp-seo-structured-data-schema"),
                        'type'     => 'text',
                        'desc'     => __('Publisher name or Organization name', "wp-seo-structured-data-schema"),
                        'required' => true
                    ),
                    'publisherImage'   => array(
                        'title'    => __('Publisher Logo', "wp-seo-structured-data-schema"),
                        'type'     => 'image',
                        'desc'     => __("Logos should have a wide aspect ratio, not a square icon.<br>Logos should be no wider than 600px, and no taller than 60px.<br>Always retain the original aspect ratio of the logo when resizing. Ideally, logos are exactly 60px tall with width <= 600px. If maintaining a height of 60px would cause the width to exceed 600px, downscale the logo to exactly 600px wide and reduce the height accordingly below 60px to maintain the original aspect ratio.<br>", "wp-seo-structured-data-schema"),
                        'required' => true
                    ),
                    'description'      => array(
                        'title' => __('Description', "wp-seo-structured-data-schema"),
                        'type'  => 'textarea',
                        'desc'  => __('Short description. New line is not supported.', "wp-seo-structured-data-schema")
                    ),
                    'articleBody'      => array(
                        'title' => __('Article body', "wp-seo-structured-data-schema"),
                        'type'  => 'textarea',
                        'desc'  => __('Article content', "wp-seo-structured-data-schema")
                    ),
                    'video'  => [
                        'title'     => __('Video', "wp-seo-structured-data-schema"),
                        'type'      => 'group', 
                        'fields'    => [
                            'video_heading'  => [
                                'type'  => 'heading',
                                'title' => __('Video', "wp-seo-structured-data-schema")
                            ], 
                            'name' => array( 
                                'type'     => 'text',
                                'title'    => esc_html__('Name', "wp-seo-structured-data-schema"), 
                            ),
                            'description' => array( 
                                'type'  => 'textarea',
                                'title' => esc_html__('Description', "wp-seo-structured-data-schema") 
                            ), 
                            'thumbnailUrl' => array( 
                                'type'  => 'image',
                                'title' => esc_html__('Image', "wp-seo-structured-data-schema") 
                            ),
                            'contentUrl' => array( 
                                'type'     => 'url',
                                'title'    => esc_html__('Content URL', "wp-seo-structured-data-schema") 
                            ),
                            'embedUrl' => array( 
                                'type'     => 'url',
                                'title'    => esc_html__('Embed URL', "wp-seo-structured-data-schema"),
                                'desc'        => esc_html__("A URL pointing to the actual video media file. This file should be in .mpg, .mpeg, .mp4, .m4v, .mov, .wmv, .asf, .avi, .ra, .ram, .rm, .flv, or other video file format.", 'wp-seo-structured-data-schema') 
                            ),
                            'uploadDate' => array( 
                                'type'     => 'text',
                                'title'    => esc_html__('Upload date', "wp-seo-structured-data-schema"),
                                'class' => 'rtrs-date',
                                'required'    => true,
                                'desc'  => esc_html__('Like this: 2021-08-25 14:20:00', "wp-seo-structured-data-schema") 
                            ),
                            'duration' => array( 
                                'type'     => 'text',
                                'title'    => esc_html__('Duration', "wp-seo-structured-data-schema"),
                                'desc'  => esc_html__('Runtime of the movie in ISO 8601 format (for example, "PT2H22M" (142 minutes)).', "wp-seo-structured-data-schema") 
                            ), 
                        ]
                    ],
                    'audio'  => [
                        'title'     => __('Audio', "wp-seo-structured-data-schema"),
                        'type'      => 'group', 
                        'fields'    => [
                            'audio_heading'  => [
                                'type'  => 'heading',
                                'title' => __('Audio', "wp-seo-structured-data-schema")
                            ], 
                            'name' => array( 
                                'type'     => 'text',
                                'title'    => esc_html__('Name', 'wp-seo-structured-data-schema'), 
                                'desc'     => esc_html__("The title of the audio", 'wp-seo-structured-data-schema')
                            ),
                            'description' => array( 
                                'type'     => 'textarea',
                                'title'    => esc_html__('Description', 'wp-seo-structured-data-schema'), 
                                'desc'     => esc_html__("The short description of the audio", 'wp-seo-structured-data-schema')
                            ), 
                            'duration' => array( 
                                'type'  => 'text', 
                                'title' => esc_html__('Duration', 'wp-seo-structured-data-schema'),
                                'desc'  => esc_html__("The duration of the audio in ISO 8601 format.(PT1M33S)", 'wp-seo-structured-data-schema')
                            ),
                            'contentUrl' => array( 
                                'type'        => 'url', 
                                'title'       => esc_html__('Content URL', 'wp-seo-structured-data-schema'),
                                'placeholder' => esc_html__('URL', 'wp-seo-structured-data-schema'),
                                'desc'        => esc_html__("A URL pointing to the actual audio media file. This file should be in .mp3, .wav, .mpc or other audio file format.", 'wp-seo-structured-data-schema')
                            ),
                            'encodingFormat' => array( 
                                'type'  => 'text',
                                'title' => esc_html__('Encoding Format', 'wp-seo-structured-data-schema'),
                                'desc'  => esc_html__("The encoding format of audio like: 'audio/mpeg'", 'wp-seo-structured-data-schema')                    
                            ),
                        ]
                    ],
                )
            ),
            'news_article'        => array(
                'pro'    => false,
                'title'  => __('News Article', "wp-seo-structured-data-schema"),
                'fields' => array(
                    'active'           => array(
                        'type' => 'checkbox'
                    ),
                    'headline'         => array(
                        'title'    => __('Headline', "wp-seo-structured-data-schema"),
                        'type'     => 'text',
                        'desc'     => __('Article title', "wp-seo-structured-data-schema"),
                        'required' => true
                    ),
                    'mainEntityOfPage' => array(
                        'title'    => __('Page URL', "wp-seo-structured-data-schema"),
                        'type'     => 'url',
                        'desc'     => __('The canonical URL of the article page', "wp-seo-structured-data-schema"),
                        'required' => true
                    ),
                    'author'           => array(
                        'title'    => __('Author', "wp-seo-structured-data-schema"),
                        'type'     => 'text',
                        'desc'     => __('Author display name', "wp-seo-structured-data-schema"),
                        'required' => true
                    ),
                    'author_url' => array(
                        'title'    => __('Author URL', "wp-seo-structured-data-schema"),
                        'type'     => 'url', 
                        'required' => true,
                        'default' => $author_url
                    ),
                    'image'            => array(
                        'title'    => __('Image', "wp-seo-structured-data-schema"),
                        'type'     => 'image',
                        'desc'     => __("The representative image of the article. Only a marked-up image that directly belongs to the article should be specified.<br> Images should be at least 696 pixels wide. <br>Images should be in .jpg, .png, or. gif format.", "wp-seo-structured-data-schema"),
                        'required' => true
                    ),
                    'datePublished'    => array(
                        'title'    => __('Published date', "wp-seo-structured-data-schema"),
                        'type'     => 'text',
                        'class'    => 'kcseo-date',
                        'desc'     => __('Like this: 2021-12-25 14:20:00', "wp-seo-structured-data-schema"),
                        'required' => true
                    ),
                    'dateModified'     => array(
                        'title'    => __('Modified date', "wp-seo-structured-data-schema"),
                        'type'     => 'text',
                        'class'    => 'kcseo-date',
                        'required' => true,
                        'desc'     => __('Like this: 2021-12-25 14:20:00', "wp-seo-structured-data-schema")
                    ),
                    'publisher'        => array(
                        'title'    => __('Publisher', "wp-seo-structured-data-schema"),
                        'type'     => 'text',
                        'desc'     => __('Publisher name or Organization name', "wp-seo-structured-data-schema"),
                        'required' => true
                    ),
                    'publisherImage'   => array(
                        'title'    => __('Publisher Logo', "wp-seo-structured-data-schema"),
                        'type'     => 'image',
                        'desc'     => __('Logos should have a wide aspect ratio, not a square icon.<br>Logos should be no wider than 600px, and no taller than 60px.<br>Always retain the original aspect ratio of the logo when resizing. Ideally, logos are exactly 60px tall with width <= 600px. If maintaining a height of 60px would cause the width to exceed 600px, downscale the logo to exactly 600px wide and reduce the height accordingly below 60px to maintain the original aspect ratio.<br>', "wp-seo-structured-data-schema"),
                        'required' => true
                    ),
                    'description'      => array(
                        'title' => __('Description', "wp-seo-structured-data-schema"),
                        'type'  => 'textarea',
                        'desc'  => __('Short description. New line is not supported.', "wp-seo-structured-data-schema")
                    ),
                    'articleBody'      => array(
                        'title' => __('Article body', "wp-seo-structured-data-schema"),
                        'type'  => 'textarea',
                        'desc'  => __('Article body content. New line is not supported.', "wp-seo-structured-data-schema")
                    ),
                    'video'  => [
                        'title'     => __('Video', "wp-seo-structured-data-schema"),
                        'type'      => 'group', 
                        'fields'    => [
                            'video_heading'  => [
                                'type'  => 'heading',
                                'title' => __('Video', "wp-seo-structured-data-schema")
                            ], 
                            'name' => array( 
                                'type'     => 'text',
                                'title'    => esc_html__('Name', "wp-seo-structured-data-schema"), 
                            ),
                            'description' => array( 
                                'type'  => 'textarea',
                                'title' => esc_html__('Description', "wp-seo-structured-data-schema") 
                            ), 
                            'thumbnailUrl' => array( 
                                'type'  => 'image',
                                'title' => esc_html__('Image', "wp-seo-structured-data-schema") 
                            ),
                            'contentUrl' => array( 
                                'type'     => 'url',
                                'title'    => esc_html__('Content URL', "wp-seo-structured-data-schema") 
                            ),
                            'embedUrl' => array( 
                                'type'     => 'url',
                                'title'    => esc_html__('Embed URL', "wp-seo-structured-data-schema"),
                                'desc'        => esc_html__("A URL pointing to the actual video media file. This file should be in .mpg, .mpeg, .mp4, .m4v, .mov, .wmv, .asf, .avi, .ra, .ram, .rm, .flv, or other video file format.", 'wp-seo-structured-data-schema') 
                            ),
                            'uploadDate' => array( 
                                'type'     => 'text',
                                'title'    => esc_html__('Upload date', "wp-seo-structured-data-schema"),
                                'class' => 'rtrs-date',
                                'required'    => true,
                                'desc'  => esc_html__('Like this: 2021-08-25 14:20:00', "wp-seo-structured-data-schema") 
                            ),
                            'duration' => array( 
                                'type'     => 'text',
                                'title'    => esc_html__('Duration', "wp-seo-structured-data-schema"),
                                'desc'  => esc_html__('Runtime of the movie in ISO 8601 format (for example, "PT2H22M" (142 minutes)).', "wp-seo-structured-data-schema") 
                            ), 
                        ]
                    ],
                    'audio'  => [
                        'title'     => __('Audio', "wp-seo-structured-data-schema"),
                        'type'      => 'group', 
                        'fields'    => [
                            'audio_heading'  => [
                                'type'  => 'heading',
                                'title' => __('Audio', "wp-seo-structured-data-schema")
                            ], 
                            'name' => array( 
                                'type'     => 'text',
                                'title'    => esc_html__('Name', 'wp-seo-structured-data-schema'), 
                                'desc'     => esc_html__("The title of the audio", 'wp-seo-structured-data-schema')
                            ),
                            'description' => array( 
                                'type'     => 'textarea',
                                'title'    => esc_html__('Description', 'wp-seo-structured-data-schema'), 
                                'desc'     => esc_html__("The short description of the audio", 'wp-seo-structured-data-schema')
                            ), 
                            'duration' => array( 
                                'type'  => 'text', 
                                'title' => esc_html__('Duration', 'wp-seo-structured-data-schema'),
                                'desc'  => esc_html__("The duration of the audio in ISO 8601 format.(PT1M33S)", 'wp-seo-structured-data-schema')
                            ),
                            'contentUrl' => array( 
                                'type'        => 'url', 
                                'title'       => esc_html__('Content URL', 'wp-seo-structured-data-schema'),
                                'placeholder' => esc_html__('URL', 'wp-seo-structured-data-schema'),
                                'desc'        => esc_html__("A URL pointing to the actual audio media file. This file should be in .mp3, .wav, .mpc or other audio file format.", 'wp-seo-structured-data-schema')
                            ),
                            'encodingFormat' => array( 
                                'type'  => 'text',
                                'title' => esc_html__('Encoding Format', 'wp-seo-structured-data-schema'),
                                'desc'  => esc_html__("The encoding format of audio like: 'audio/mpeg'", 'wp-seo-structured-data-schema')                    
                            ),
                        ]
                    ],
                )
            ),
            'event'               => array(
                'pro'    => false,
                'title'  => __('Event', "wp-seo-structured-data-schema"),
                'fields' => array(
                    'active'               => array(
                        'type' => 'checkbox'
                    ),
                    'name'                 => array(
                        'title'    => __('Name', "wp-seo-structured-data-schema"),
                        'type'     => 'text',
                        'required' => true,
                        'desc'     => __("The name of the event.", "wp-seo-structured-data-schema")
                    ),
                    'locationName'         => array(
                        'title'    => __('Location name', "wp-seo-structured-data-schema"),
                        'type'     => 'text',
                        'required' => true,
                        'desc'     => __("Event Location name", "wp-seo-structured-data-schema")
                    ),
                    'locationAddress'      => array(
                        'title'    => __('Location address', "wp-seo-structured-data-schema"),
                        'type'     => 'text',
                        'required' => true,
                        'desc'     => __("The location of for example where the event is happening, an organization is located, or where an action takes place.", "wp-seo-structured-data-schema")
                    ),
                    'startDate'            => array(
                        'title'    => __('Start date', "wp-seo-structured-data-schema"),
                        'type'     => 'text',
                        'class'    => 'kcseo-date',
                        'required' => true,
                        'desc'     => __("Event start date Like : 2017-10-16 4:00 AM", "wp-seo-structured-data-schema")
                    ),
                    'endDate'              => array(
                        'title'       => __('End date', "wp-seo-structured-data-schema"),
                        'type'        => 'text',
                        'recommended' => true,
                        'class'       => 'kcseo-date',
                        'desc'        => __("Event end date Like : 2017-10-16 4:00 AM", "wp-seo-structured-data-schema")
                    ),
                    'description'          => array(
                        'title'       => __('Description', "wp-seo-structured-data-schema"),
                        'type'        => 'textarea',
                        'recommended' => true,
                        'desc'        => __("Event description. New line is not supported.", "wp-seo-structured-data-schema")
                    ),
                    'organizer'        => array(
                        'title'       => __('Organizer', "wp-seo-structured-data-schema"),
                        'type'        => 'text',
                        'recommended' => true,
                        'desc'        => __("The Organizer.", "wp-seo-structured-data-schema")
                    ),
                    'organizerUrl'                => array(
                        'title'       => __('Organizer URL', "wp-seo-structured-data-schema"),
                        'type'        => 'url',
                        'recommended' => true,
                        'desc'        => __("Organizer URL", "wp-seo-structured-data-schema")
                    ),
                    'performerName'        => array(
                        'title'       => __('Performer Name', "wp-seo-structured-data-schema"),
                        'type'        => 'text',
                        'recommended' => true,
                        'desc'        => __("The performer's name.", "wp-seo-structured-data-schema")
                    ),
                    'EventAttendanceMode'         => array(
                        'title'       => 'Event Attendance Mode',
                        'type'        => 'select',
                        'recommended' => true,
                        'empty'       => "Select one",
                        'options'     => array(
                            'https://schema.org/OfflineEventAttendanceMode'  => 'Offline',
                            'https://schema.org/OnlineEventAttendanceMode'  => 'Online',
                            'https://schema.org/MixedEventAttendanceMode' => 'Mixed',
                        ),
                    ),
                    'eventStatus'         => array(
                        'title'       => 'Event Status',
                        'type'        => 'select',
                        'recommended' => true,
                        'empty'       => "Select one",
                        'options'     => array(
                            'https://schema.org/EventScheduled'  => 'EventScheduled',
                            'https://schema.org/EventCancelled'  => 'EventCancelled',
                            'https://schema.org/EventMovedOnline'  => 'EventMovedOnline',
                            'https://schema.org/EventPostponed'  => 'EventPostponed',
                            'https://schema.org/EventRescheduled'  => 'EventRescheduled',
                        ),
                    ),
                    'image'                => array(
                        'title'       => __('Image URL', "wp-seo-structured-data-schema"),
                        'type'        => 'url',
                        'recommended' => true,
                        'desc'        => __("URL of an image or logo for the event or tour", "wp-seo-structured-data-schema")
                    ),
                    'price'                => array(
                        'title'       => __('Price', "wp-seo-structured-data-schema"),
                        'type'        => 'number',
                        'recommended' => true,
                        'attr'        => 'step="any"',
                        'desc'        => __("This is highly recommended. The lowest available price, including service charges and fees, of this type of ticket. <span class='required'>Not required but (Recommended)</span>", "wp-seo-structured-data-schema")
                    ),
                    'priceCurrency'        => array(
                        'title' => __('Price currency', "wp-seo-structured-data-schema"),
                        'type'  => 'text',
                        'desc'  => __("The 3-letter currency code. (USD)", "wp-seo-structured-data-schema")
                    ),
                    'availability'         => array(
                        'title'       => 'Availability',
                        'type'        => 'select',
                        'recommended' => true,
                        'empty'       => "Select one",
                        'options'     => array(
                            'http://schema.org/InStock'  => 'InStock',
                            'http://schema.org/SoldOut'  => 'SoldOut',
                            'http://schema.org/PreOrder' => 'PreOrder',
                        ),
                    ),
                    'validFrom'            => array(
                        'title'       => __('Valid From', "wp-seo-structured-data-schema"),
                        'type'        => 'text',
                        'recommended' => true,
                        'class'       => 'kcseo-date',
                        'desc'        => __(sprintf("The date and time when tickets go on sale (only required on date-restricted offers), in <a href='%s' target='_blank'>ISO-8601 format</a>", 'https://en.wikipedia.org/wiki/ISO_8601'), "wp-seo-structured-data-schema")
                    ),
                    'url'                  => array(
                        'title'       => 'URL',
                        'recommended' => true,
                        'type'        => 'url',
                        'placeholder' => 'URL',
                        'desc'        => __("A link to the event's details page. <span class='required'>Not required but (Recommended)</span>", "wp-seo-structured-data-schema")
                    ),
                    'review_section'       => array(
                        'title' => __('Review', "wp-seo-structured-data-schema"),
                        'type'  => 'heading',
                        'desc'  => __("To add review schema for this type, complete fields below and enable, others live blank.", "wp-seo-structured-data-schema")
                    ),
                    'review_active'        => array(
                        'type' => 'checkbox'
                    ),
                    'review_author'        => array(
                        'title'    => __("Author", "wp-seo-structured-data-schema"),
                        'type'     => 'text',
                        'required' => true
                    ),
                    'review_author_sameAs' => array(
                        'title'    => __("Author Same As profile link", "wp-seo-structured-data-schema"),
                        'type'     => 'textarea',
                        'attr'     => 'placeholder="https://facebook.com/example&#10;https://twitter.com/example"',
                        'required' => true,
                        'desc'     => __('A reference page that unambiguously indicates the item\'s identity; for example, the URL of the item\'s Wikipedia page, Freebase page, or official website.<br> Enter new line for every entry', "wp-seo-structured-data-schema")
                    ),
                    'review_body'          => array(
                        'title'    => __('Review body', "wp-seo-structured-data-schema"),
                        'type'     => 'textarea',
                        'required' => true,
                        'desc'     => __("The actual body of the review.", "wp-seo-structured-data-schema")
                    ),
                    'review_datePublished' => array(
                        'title' => __('Date of Published', "wp-seo-structured-data-schema"),
                        'type'  => 'text',
                        'class' => 'kcseo-date',
                        'desc'  => __("Like this: 2021-12-25 14:20:00", "wp-seo-structured-data-schema")
                    ),
                    'review_ratingValue'   => array(
                        'title' => __('Rating value', "wp-seo-structured-data-schema"),
                        'type'  => 'number',
                        'attr'  => 'step="any"',
                        'desc'  => __("A numerical quality rating for the item.", "wp-seo-structured-data-schema")
                    ),
                    'review_bestRating'    => array(
                        'title' => __('Best rating', "wp-seo-structured-data-schema"),
                        'type'  => 'number',
                        'attr'  => 'step="any"',
                        'desc'  => __("The highest value allowed in this rating system.", "wp-seo-structured-data-schema")
                    ),
                    'review_worstRating'   => array(
                        'title' => __('Worst rating', "wp-seo-structured-data-schema"),
                        'type'  => 'number',
                        'attr'  => 'step="any"',
                        'desc'  => __("The lowest value allowed in this rating system. * Required if the rating system is not on a 5-point scale. If worstRating is omitted, 1 is assumed.", "wp-seo-structured-data-schema")
                    )
                )
            ),
            'product'             => array(
                'pro'    => false,
                'title'  => __('Product', "wp-seo-structured-data-schema"),
                'fields' => array(
                    'active'            => array(
                        'type' => 'checkbox'
                    ),
                    'name'              => array(
                        'title'    => __('Name', "wp-seo-structured-data-schema"),
                        'type'     => 'text',
                        'required' => true,
                        'desc'     => __("The name of the product.", "wp-seo-structured-data-schema")
                    ),
                    'image'             => array(
                        'title' => __('Image', "wp-seo-structured-data-schema"),
                        'type'  => 'image',
                        'desc'  => __("The URL of a product photo. Pictures clearly showing the product, e.g. against a white background, are preferred.", "wp-seo-structured-data-schema")
                    ),
                    'description'       => array(
                        'title' => __('Description', "wp-seo-structured-data-schema"),
                        'type'  => 'textarea',
                        'desc'  => __("Product description. New line is not supported.", "wp-seo-structured-data-schema")
                    ),
                    'sku'               => array(
                        'title'       => __('SKU', "wp-seo-structured-data-schema"),
                        'type'        => 'text',
                        'recommended' => true
                    ),
                    'brand'             => array(
                        'title'       => __('Brand', "wp-seo-structured-data-schema"),
                        'type'        => 'text',
                        'recommended' => true,
                        'desc'        => __("The brand of the product.", "wp-seo-structured-data-schema")
                    ),
                    'identifier_type'   => array(
                        'title'       => __('Identifier Type', "wp-seo-structured-data-schema"),
                        'type'        => 'select',
                        'recommended' => true,
                        'options'     => array(
                            'mpn'    => 'MPN',
                            'isbn'   => 'ISBN',
                            'gtin8'  => 'GTIN-8 (UPC, JAN)',
                            'gtin12' => 'GTIN-12 (UPC)',
                            'gtin13' => 'GTIN-13 (EAN,JAN)'
                        ),
                        'desc'        => __("<strong>MPN</strong><br>
                                       &#8594; MPN(Manufacturer Part Number) Used globally, Alphanumeric digits (various lengths)<br>
                                       <strong>GTIN</strong><br>
                                       &#8594; UPC(Universal Product Code) Used in primarily North America. 12 numeric digits. eg. 892685001003.<br>
                                       &#8594; EAN(European Article Number) Used primarily outside of North America. Typically 13 numeric digits (can occasionally be either eight or 14 numeric digits). eg. 4011200296908<br>
                                       &#8594; ISBN(International Standard Book Number) Used globally, ISBN-13 (recommended), 13 numeric digits 978-0747595823<br>
                                       &#8594; JAN(Japanese Article Number) Used only in Japan, 8 or 13 numeric digits.", "wp-seo-structured-data-schema")
                    ),
                    'identifier'        => array(
                        'title'       => __('Identifier', "wp-seo-structured-data-schema"),
                        'type'        => 'text',
                        'recommended' => true,
                        'desc'        => __("Enter product unique identifier", "wp-seo-structured-data-schema")
                    ),
                    'rating_section'    => array(
                        'title' => __('Product Review & Rating', "wp-seo-structured-data-schema"),
                        'type'  => 'heading',
                    ),
                    'reviewRatingValue' => array(
                        'title'       => __('Review rating value', "wp-seo-structured-data-schema"),
                        'type'        => 'number',
                        'recommended' => true,
                        'attr'        => 'step="any"',
                        'desc'        => __("Rating value. (1 , 2.5, 3, 5 etc)", "wp-seo-structured-data-schema")
                    ),
                    'reviewBestRating'  => array(
                        'title'       => __('Review Best rating', "wp-seo-structured-data-schema"),
                        'type'        => 'number',
                        'recommended' => true,
                        'attr'        => 'step="any"',
                    ),
                    'reviewWorstRating' => array(
                        'title'       => __('Review Worst rating', "wp-seo-structured-data-schema"),
                        'type'        => 'number',
                        'recommended' => true,
                        'attr'        => 'step="any"',
                    ),
                    'reviewAuthor'      => array(
                        'title' => __('Review author', "wp-seo-structured-data-schema"),
                        'type'  => 'text'
                    ),
                    'ratingValue'       => array(
                        'title' => __('Rating value', "wp-seo-structured-data-schema"),
                        'type'  => 'number',
                        'attr'  => 'step="any"',
                        'desc'  => __("Rating value. (1 , 2.5, 3, 5 etc)", "wp-seo-structured-data-schema")
                    ),
                    'reviewCount'       => array(
                        'title' => __('Total review count', "wp-seo-structured-data-schema"),
                        'type'  => 'number',
                        'attr'  => 'step="any"',
                        'desc'  => __("Review rating value. <span class='required'>This is required if (Rating value) is given</span>", "wp-seo-structured-data-schema")
                    ),
                    'pricing_section'   => array(
                        'title' => __('Product Pricing', "wp-seo-structured-data-schema"),
                        'type'  => 'heading',
                    ),
                    'price'             => array(
                        'title' => __('Price', "wp-seo-structured-data-schema"),
                        'type'  => 'number',
                        'attr'  => 'step="any"',
                        'desc'  => __("The lowest available price, including service charges and fees, of this type of ticket.", "wp-seo-structured-data-schema")
                    ),
                    'priceValidUntil'   => array(
                        'title'       => __('PriceValidUntil', "wp-seo-structured-data-schema"),
                        'type'        => 'text',
                        'recommended' => true,
                        'class'       => 'kcseo-date',
                        'desc'        => __("The date (in ISO 8601 date and time format) after which the price will no longer be available.", "wp-seo-structured-data-schema")
                    ),
                    'priceCurrency'     => array(
                        'title' => __('Price currency', "wp-seo-structured-data-schema"),
                        'type'  => 'text',
                        'desc'  => __("The 3-letter currency code.", "wp-seo-structured-data-schema")
                    ),
                    'availability'      => array(
                        'title'   => 'Availability',
                        'type'    => 'select',
                        'empty'   => "Select one",
                        'options' => array(
                            'http://schema.org/InStock'             => 'InStock',
                            'http://schema.org/InStoreOnly'         => 'InStoreOnly',
                            'http://schema.org/OutOfStock'          => 'OutOfStock',
                            'http://schema.org/SoldOut'             => 'SoldOut',
                            'http://schema.org/OnlineOnly'          => 'OnlineOnly',
                            'http://schema.org/LimitedAvailability' => 'LimitedAvailability',
                            'http://schema.org/Discontinued'        => 'Discontinued',
                            'http://schema.org/PreOrder'            => 'PreOrder',
                        ),
                        'desc'    => __("Select a availability type", "wp-seo-structured-data-schema")
                    ),
                    'itemCondition'     => array(
                        'title'   => 'Product condition',
                        'type'    => 'select',
                        'empty'   => "Select one",
                        'options' => array(
                            'http://schema.org/NewCondition'         => 'NewCondition',
                            'http://schema.org/UsedCondition'        => 'UsedCondition',
                            'http://schema.org/DamagedCondition'     => 'DamagedCondition',
                            'http://schema.org/RefurbishedCondition' => 'RefurbishedCondition',
                        ),
                        'desc'    => __("Select a condition", "wp-seo-structured-data-schema")
                    ),
                    'url'               => array(
                        'title' => __('Product URL', "wp-seo-structured-data-schema"),
                        'type'  => 'url',
                        'desc'  => __("A URL to the product web page (that includes the Offer). (Don't use offerURL for markup that appears on the product page itself.)", "wp-seo-structured-data-schema")
                    ),
                )
            ),
            'video'               => array(
                'pro'    => false,
                'title'  => __('Video', "wp-seo-structured-data-schema"),
                'fields' => array(
                    'active'           => array(
                        'type' => 'checkbox'
                    ),
                    'name'             => array(
                        'title'    => __('Name', "wp-seo-structured-data-schema"),
                        'type'     => 'text',
                        'required' => true,
                        'desc'     => __("The title of the video", "wp-seo-structured-data-schema")
                    ),
                    'description'      => array(
                        'title'    => __('Description', "wp-seo-structured-data-schema"),
                        'type'     => 'textarea',
                        'required' => true,
                        'desc'     => __("The description of the video. New line is not supported.", "wp-seo-structured-data-schema")
                    ),
                    'thumbnailUrl'     => array(
                        'title'       => 'Thumbnail URL',
                        'type'        => 'url',
                        'placeholder' => "URL",
                        'required'    => true,
                        'desc'        => __("A URL pointing to the video thumbnail image file. Images must be at least 160x90 pixels and at most 1920x1080 pixels.", "wp-seo-structured-data-schema")
                    ),
                    'uploadDate'       => array(
                        'title' => __('Updated date', "wp-seo-structured-data-schema"),
                        'type'  => 'text',
                        'class' => 'kcseo-date',
                        'required'    => true,
                        'desc'  => __('2021-02-05T08:00:00+08:00', "wp-seo-structured-data-schema")
                    ),
                    'duration'         => array(
                        'title' => __('Duration', "wp-seo-structured-data-schema"),
                        'type'  => 'text',
                        'desc'  => __("The duration of the video in ISO 8601 format.(PT1M33S)", "wp-seo-structured-data-schema")
                    ),
                    'contentUrl'       => array(
                        'title'       => 'Content URL',
                        'type'        => 'url',
                        'placeholder' => 'URL',
                        'desc'        => __("A URL pointing to the actual video media file. This file should be in .mpg, .mpeg, .mp4, .m4v, .mov, .wmv, .asf, .avi, .ra, .ram, .rm, .flv, or other video file format.", "wp-seo-structured-data-schema")
                    ),
                    'embedUrl'         => array(
                        'title'       => 'Embed URL',
                        'placeholder' => 'URL',
                        'type'        => 'url',
                        'desc'        => __("A URL pointing to a player for the specific video. Usually this is the information in the src element of an < embed> tag.Example: Dailymotion: http://www.dailymotion.com/swf/x1o2g.", "wp-seo-structured-data-schema")
                    ),
                    'interactionCount' => array(
                        'title' => __('Interaction count', "wp-seo-structured-data-schema"),
                        'type'  => 'text',
                        'desc'  => __("The number of times the video has been viewed.", "wp-seo-structured-data-schema")
                    ),
                    'expires'          => array(
                        'title' => __('Expires', "wp-seo-structured-data-schema"),
                        'type'  => 'text',
                        'class' => 'kcseo-date',
                        'desc'  => __("Like this: 2021-12-25 14:20:00", "wp-seo-structured-data-schema")
                    ),
                )
            ),
            'audio' => array(
                'pro'    => false,
                'title'  => __('Audio', "wp-seo-structured-data-schema"),
                'fields' => array(
                    'active'           => array(
                        'type' => 'checkbox'
                    ),
                    'name'  => array(
                        'title'    => __('Name', "wp-seo-structured-data-schema"),
                        'type'     => 'text',
                        'required' => true,
                        'desc'     => __("The title of the audio", "wp-seo-structured-data-schema")
                    ),
                    'description'  => array(
                        'title'    => __('Description', "wp-seo-structured-data-schema"),
                        'type'     => 'textarea', 
                        'desc'     => __("The description of the audio. New line is not supported.", "wp-seo-structured-data-schema")
                    ), 
                    'contentUrl' => array(
                        'title'       => 'Content URL',
                        'type'        => 'url',
                        'placeholder' => 'URL',
                        'required' => true,
                        'desc'        => esc_html__("A URL pointing to the actual audio media file. This file should be in .mp3, .wav, .mpc or other audio file format.", 'wp-seo-structured-data-schema')
                    ), 
                    'duration'  => array(
                        'title' => __('Duration', "wp-seo-structured-data-schema"),
                        'type'  => 'text',
                        'desc'  => __("The duration of the audio in ISO 8601 format.(PT1M33S)", "wp-seo-structured-data-schema")
                    ), 
                    'encodingFormat' => array( 
                        'type'  => 'text',
                        'title' => esc_html__('Encoding Format', 'wp-seo-structured-data-schema'),
                        'desc'  => esc_html__("The encoding format of audio like: 'audio/mpeg'", 'wp-seo-structured-data-schema')                    
                    ),
                )
            ),
            'service'             => array(
                'pro'    => false,
                'title'  => __('Service', "wp-seo-structured-data-schema"),
                'fields' => array(
                    'active'           => array(
                        'type' => 'checkbox'
                    ),
                    'name'             => array(
                        'title'    => __('Name', "wp-seo-structured-data-schema"),
                        'type'     => 'text',
                        'required' => true,
                        'desc'     => __("The name of the Service.", "wp-seo-structured-data-schema")
                    ),
                    'serviceType'      => array(
                        'title'    => __('Service type', "wp-seo-structured-data-schema"),
                        'type'     => 'text',
                        'required' => true,
                        'desc'     => __("The type of service being offered, e.g. veterans' benefits, emergency relief, etc.", "wp-seo-structured-data-schema")
                    ),
                    'additionalType'   => array(
                        'title'       => 'Additional type(URL)',
                        'type'        => 'url',
                        'placeholder' => 'URL',
                        'desc'        => __("An additional type for the service, typically used for adding more specific types from external vocabularies in microdata syntax.", "wp-seo-structured-data-schema")
                    ),
                    'award'            => array(
                        'title' => __('Award', "wp-seo-structured-data-schema"),
                        'type'  => 'text',
                        'desc'  => __("An award won by or for this service.", "wp-seo-structured-data-schema")
                    ),
                    'category'         => array(
                        'title' => __('Category', "wp-seo-structured-data-schema"),
                        'type'  => 'text',
                        'desc'  => __("A category for the service.", "wp-seo-structured-data-schema")
                    ),
                    'providerMobility' => array(
                        'title' => __('Provider mobility', "wp-seo-structured-data-schema"),
                        'type'  => 'text',
                        'desc'  => __("Indicates the mobility of a provided service (e.g. 'static', 'dynamic').", "wp-seo-structured-data-schema")
                    ),
                    'description'      => array(
                        'title'   => 'Description',
                        'type'    => 'textarea',
                        'require' => true,
                        'desc'    => __("A short description of the service. New line is not supported.", "wp-seo-structured-data-schema")
                    ),
                    'image'            => array(
                        'title'   => 'Image URL',
                        'type'    => 'url',
                        'require' => false,
                        'desc'    => __("An image of the service. This should be a URL.", "wp-seo-structured-data-schema")
                    ),
                    'mainEntityOfPage' => array(
                        'title'   => 'Main entity of page URL',
                        'type'    => 'url',
                        'require' => false,
                        'desc'    => __("Indicates a page (or other CreativeWork) for which this thing is the main entity being described.", "wp-seo-structured-data-schema")
                    ),
                    'sameAs'           => array(
                        'title'       => 'Same as URL',
                        'type'        => 'url',
                        'placeholder' => 'URL',
                        'desc'        => __("URL of a reference Web page that unambiguously indicates the service's identity. E.g. the URL of the service's Wikipedia page, Freebase page, or official website.", "wp-seo-structured-data-schema")
                    ),
                    'url'              => array(
                        'title'       => 'Url of the service',
                        'type'        => 'url',
                        'placeholder' => 'URL',
                        'desc'        => __("URL of the service.", "wp-seo-structured-data-schema")
                    ),
                    'alternateName'    => array(
                        'title' => __('Alternate name', "wp-seo-structured-data-schema"),
                        'type'  => 'text',
                        'desc'  => __('An alias for the service.', "wp-seo-structured-data-schema")
                    ),
                )
            ),
            'about'  => array(
                'pro'    => false,
                'title'  => __('About', "wp-seo-structured-data-schema"),
                'fields' => array(
                    'active' => array(
                        'type' => 'checkbox'
                    ),
                    'name' => array( 
                        'type'     => 'text',
                        'title'    => esc_html__('Name', 'wp-seo-structured-data-schema'),
                        'required' => true
                    ),  
                    'description' => array( 
                        'type'  => 'textarea',
                        'title' => esc_html__('Description', 'wp-seo-structured-data-schema'), 
                    ), 
                    'image' => array( 
                        'type'  => 'image',
                        'title' => esc_html__('Image', 'wp-seo-structured-data-schema'),
                    ),  
                    'url' => array( 
                        'type'     => 'url',
                        'title'    => esc_html__('URL', 'wp-seo-structured-data-schema'), 
                    ), 
                    'sameAs' => array( 
                        'type'     => 'textarea',
                        'title'    => esc_html__("Author Same As profile link", 'wp-seo-structured-data-schema'),
                        'placeholder' => 'https://facebook.com/example&#10;https://twitter.com/example', 
                        'desc' => wp_kses( __("A reference page that unambiguously indicates the item\'s identity; for example, the URL of the item\'s Wikipedia page, Freebase page, or official website.<br> Enter new line for every entry", 'wp-seo-structured-data-schema'), [ 'br' => [] ] )
                    ),
                )
            ),
            'contact'  => array(
                'pro'    => false,
                'title'  => __('Contact', "wp-seo-structured-data-schema"),
                'fields' => array(
                    'active' => array(
                        'type' => 'checkbox'
                    ),
                    'name' => array( 
                        'type'     => 'text',
                        'title'    => esc_html__('Name', 'wp-seo-structured-data-schema'),
                        'required' => true
                    ),  
                    'description' => array( 
                        'type'  => 'textarea',
                        'title' => esc_html__('Description', 'wp-seo-structured-data-schema'), 
                    ), 
                    'image' => array( 
                        'type'  => 'image',
                        'title' => esc_html__('Image', 'wp-seo-structured-data-schema'),
                    ),  
                    'url' => array( 
                        'type'     => 'url',
                        'title'    => esc_html__('URL', 'wp-seo-structured-data-schema'), 
                    ), 
                    'video' => array( 
                        'type'        => 'url',
                        'title'       => esc_html__('Video URL', 'wp-seo-structured-data-schema'),
                        'placeholder' => esc_html__('URL', 'wp-seo-structured-data-schema'),
                        'desc'        => esc_html__("A URL pointing to the actual video media file. This file should be in .mpg, .mpeg, .mp4, .m4v, .mov, .wmv, .asf, .avi, .ra, .ram, .rm, .flv, or other video file format.", 'wp-seo-structured-data-schema')
                    ),
                    'sameAs' => array( 
                        'type'     => 'textarea',
                        'title'    => esc_html__("Author Same As profile link", 'wp-seo-structured-data-schema'),
                        'placeholder'     => 'https://facebook.com/example&#10;https://twitter.com/example', 
                        'desc' => wp_kses( __("A reference page that unambiguously indicates the item\'s identity; for example, the URL of the item\'s Wikipedia page, Freebase page, or official website.<br> Enter new line for every entry", 'wp-seo-structured-data-schema'), [ 'br' => [] ] )
                    ),
                )
            ), 
            'review'              => array(
                'pro'    => false,
                'title'  => __('Review', "wp-seo-structured-data-schema"),
                'fields' => array(
                    'active'                => array(
                        'type' => 'checkbox'
                    ),
                    'review_notice_heading' => array(
                        'title' => sprintf('<span style="display:block;text-align:center;color: red">%s</span>', __('Notice</span>', "wp-seo-structured-data-schema")),
                        'type'  => 'heading',
                        'desc'  => self::getReviewNotice()
                    ),
                    'itemName'              => array(
                        'title'    => __('Name of the reviewed item', "wp-seo-structured-data-schema"),
                        'type'     => 'text',
                        'required' => true,
                        'desc'     => __("The item that is being reviewed.", "wp-seo-structured-data-schema")
                    ),
                    'reviewBody'            => array(
                        'title'    => __('Review body', "wp-seo-structured-data-schema"),
                        'type'     => 'textarea',
                        'required' => true,
                        'desc'     => __("The actual body of the review.", "wp-seo-structured-data-schema")
                    ),
                    'name'                  => array(
                        'title'    => __('Review name', "wp-seo-structured-data-schema"),
                        'type'     => 'text',
                        'required' => true,
                        'desc'     => __("A particular name for the review.", "wp-seo-structured-data-schema")
                    ),
                    'author'                => array(
                        'title'    => __('Author', "wp-seo-structured-data-schema"),
                        'type'     => 'text',
                        'required' => true,
                        'author'   => 'Author name',
                        'desc'     => __("The author of the review. The reviewer’s name needs to be a valid name.", "wp-seo-structured-data-schema")
                    ),
                    'datePublished'         => array(
                        'title' => __('Date of Published', "wp-seo-structured-data-schema"),
                        'type'  => 'text',
                        'class' => 'kcseo-date',
                        'desc'  => __("Like this: 2021-12-25 14:20:00", "wp-seo-structured-data-schema")
                    ),
                    'ratingValue'           => array(
                        'title' => __('Rating value', "wp-seo-structured-data-schema"),
                        'type'  => 'number',
                        'attr'  => 'step="any"',
                        'desc'  => __("A numerical quality rating for the item.", "wp-seo-structured-data-schema")
                    ),
                    'bestRating'            => array(
                        'title' => __('Best rating', "wp-seo-structured-data-schema"),
                        'type'  => 'number',
                        'attr'  => 'step="any"',
                        'desc'  => __("The highest value allowed in this rating system.", "wp-seo-structured-data-schema")
                    ),
                    'worstRating'           => array(
                        'title' => __('Worst rating', "wp-seo-structured-data-schema"),
                        'type'  => 'number',
                        'attr'  => 'step="any"',
                        'desc'  => __("The lowest value allowed in this rating system. * Required if the rating system is not on a 5-point scale. If worstRating is omitted, 1 is assumed.", "wp-seo-structured-data-schema")
                    ),
                    'publisher'             => array(
                        'title' => __('Name of the organization', "wp-seo-structured-data-schema"),
                        'type'  => 'text',
                        'desc'  => __('The publisher of the review.', "wp-seo-structured-data-schema")
                    )
                )
            ),
            'aggregate_rating'    => array(
                'pro'    => false,
                'title'  => __('Aggregate Ratings', "wp-seo-structured-data-schema"),
                'fields' => array(
                    'active'      => array(
                        'type' => 'checkbox'
                    ),
                    'schema_type' => array(
                        'title'    => __('Schema type', "wp-seo-structured-data-schema"),
                        'type'     => 'schema_type',
                        'required' => true,
                        'options'  => self::getSiteTypes(),
                        'empty'    => "Select one",
                        'desc'     => __("Use the most appropriate schema type for what is being reviewed.", "wp-seo-structured-data-schema")
                    ),
                    'name'        => array(
                        'title'    => __('Name of the item', "wp-seo-structured-data-schema"),
                        'type'     => 'text',
                        'required' => true,
                        'desc'     => __("The item that is being rated.", "wp-seo-structured-data-schema")
                    ),
                    'image'       => array(
                        'title'       => 'Image',
                        'type'        => 'image',
                        'required'    => true,
                        'holderClass' => 'kSeo-hidden aggregate-except-organization-holder'
                    ),
                    'priceRange'  => array(
                        'title'       => 'Price Range',
                        'recommended' => true,
                        'type'        => 'text',
                        'holderClass' => 'kSeo-hidden aggregate-except-organization-holder',
                        'desc'        => __("The price range of the business, for example $$$.", "wp-seo-structured-data-schema")
                    ),
                    'telephone'   => array(
                        'title'       => 'Telephone',
                        'recommended' => true,
                        'type'        => 'text',
                        'holderClass' => 'kSeo-hidden aggregate-except-organization-holder'
                    ),
                    'address'     => array(
                        'title'       => 'Address',
                        'recommended' => true,
                        'type'        => 'text',
                        'holderClass' => 'kSeo-hidden aggregate-except-organization-holder',
                    ),
                    'description' => array(
                        'title' => __('Description', "wp-seo-structured-data-schema"),
                        'type'  => 'textarea',
                        'desc'  => __("Description for thr review. New line is not supported.", "wp-seo-structured-data-schema")
                    ),
                    'ratingCount' => array(
                        'title'    => __('Rating Count', "wp-seo-structured-data-schema"),
                        'type'     => 'number',
                        'attr'     => 'step="any"',
                        'required' => true,
                        'desc'     => __("The total number of ratings for the item on your site. <span class='required'>* At least one of ratingCount or reviewCount is required.</span>", "wp-seo-structured-data-schema")
                    ),
                    'reviewCount' => array(
                        'title'    => __('Review Count', "wp-seo-structured-data-schema"),
                        'type'     => 'number',
                        'attr'     => 'step="any"',
                        'required' => true,
                        'desc'     => __("Specifies the number of people who provided a review with or without an accompanying rating. At least one of ratingCount or reviewCount is required.", "wp-seo-structured-data-schema")
                    ),
                    'ratingValue' => array(
                        'title'    => __('Rating Value', "wp-seo-structured-data-schema"),
                        'attr'     => 'step="any"',
                        'type'     => 'number',
                        'required' => true,
                        'desc'     => __("A numerical quality rating for the item.", "wp-seo-structured-data-schema")
                    ),
                    'bestRating'  => array(
                        'title'    => __('Best Rating', "wp-seo-structured-data-schema"),
                        'type'     => 'number',
                        'attr'     => 'step="any"',
                        'required' => true,
                        'desc'     => __("The highest value allowed in this rating system. <span class='required'>* Required if the rating system is not a 5-point scale.</span> If bestRating is omitted, 5 is assumed.", "wp-seo-structured-data-schema")
                    ),
                    'worstRating' => array(
                        'title'    => __('Worst Rating', "wp-seo-structured-data-schema"),
                        'type'     => 'number',
                        'attr'     => 'step="any"',
                        'required' => true,
                        'desc'     => __("The lowest value allowed in this rating system. <span class='required'>* Required if the rating system is not a 5-point scale.</span> If worstRating is omitted, 1 is assumed.", "wp-seo-structured-data-schema")
                    )
                )
            ),
            'restaurant'          => array(
                'pro'    => false,
                'title'  => __('Restaurant', "wp-seo-structured-data-schema"),
                'fields' => array(
                    'active'        => array(
                        'type' => 'checkbox'
                    ),
                    'name'          => array(
                        'title'    => __('Name of the Restaurant', "wp-seo-structured-data-schema"),
                        'type'     => 'text',
                        'required' => true
                    ),
                    'description'   => array(
                        'title' => __('Description of the Restaurant', "wp-seo-structured-data-schema"),
                        'type'  => 'textarea',
                        'desc'  => esc_html__('New line is not supported.', "wp-seo-structured-data-schema")
                    ),
                    'openingHours'  => array(
                        'title' => __('Opening Hours', "wp-seo-structured-data-schema"),
                        'type'  => 'textarea',
                        'desc'  => __('Mo,Tu,We,Th,Fr,Sa,Su 11:30-23:00', "wp-seo-structured-data-schema")
                    ),
                    'telephone'     => array(
                        'title' => __('Telephone', "wp-seo-structured-data-schema"),
                        'type'  => 'text',
                        'desc'  => __('+155501003333', "wp-seo-structured-data-schema")
                    ),
                    'menu'          => array(
                        'title' => __('Menu', "wp-seo-structured-data-schema"),
                        'type'  => 'text',
                        'desc'  => __('http://example.com/menu', "wp-seo-structured-data-schema")
                    ),
                    'image'         => array(
                        'title'    => __('Image', "wp-seo-structured-data-schema"),
                        'type'     => 'image',
                        'required' => true
                    ),
                    'address'       => array(
                        'title' => __('Address', "wp-seo-structured-data-schema"),
                        'type'  => 'textarea'
                    ),
                    'priceRange'    => array(
                        'title' => __('Price Range', "wp-seo-structured-data-schema"),
                        'type'  => 'text',
                        'desc'  => __('The price range of the business, for example $$$', "wp-seo-structured-data-schema")
                    ),
                    'servesCuisine' => array(
                        'title' => __('Serves Cuisine', "wp-seo-structured-data-schema"),
                        'type'  => 'text',
                        'desc'  => __('The cuisine of the restaurant.', "wp-seo-structured-data-schema")
                    )
                )
            ),
            'localBusiness'       => array(
                'pro'    => false,
                'title'  => __('Local Business', "wp-seo-structured-data-schema"),
                'fields' => array(
                    'active'               => array(
                        'type' => 'checkbox'
                    ),
                    'name'                 => array(
                        'title'    => __('Name', "wp-seo-structured-data-schema"),
                        'type'     => 'text',
                        'required' => true
                    ),
                    'description'          => array(
                        'title' => __('Description', "wp-seo-structured-data-schema"),
                        'type'  => 'textarea',
                        'desc'  => esc_html__('New line is not supported.', "wp-seo-structured-data-schema")
                    ),
                    'image'                => array(
                        'title'    => __('Business Logo', "wp-seo-structured-data-schema"),
                        'type'     => 'image',
                        'required' => true
                    ),
                    'priceRange'           => array(
                        'title'       => __('Price Range', "wp-seo-structured-data-schema"),
                        'type'        => 'text',
                        'recommended' => true,
                        'desc'        => __("The price range of the business, for example $$$.", "wp-seo-structured-data-schema")
                    ),
                    'addressLocality'      => array(
                        'title' => __('Address locality', "wp-seo-structured-data-schema"),
                        'type'  => 'text',
                        'desc'  => __('City (i.e Kansas city)', "wp-seo-structured-data-schema")
                    ),
                    'addressRegion'        => array(
                        'title' => __('Address region', "wp-seo-structured-data-schema"),
                        'type'  => 'text',
                        'desc'  => __('State (i.e. MO)', "wp-seo-structured-data-schema")
                    ),
                    'postalCode'           => array(
                        'title' => __('Postal code', "wp-seo-structured-data-schema"),
                        'type'  => 'text',
                    ),
                    'streetAddress'        => array(
                        'title' => __('Street address', "wp-seo-structured-data-schema"),
                        'type'  => 'text',
                    ),
                    'telephone'            => array(
                        'title'       => __('Telephone', "wp-seo-structured-data-schema"),
                        'type'        => 'text',
                        'recommended' => true,
                    ),
                    'review_section'       => array(
                        'title' => __('Review', "wp-seo-structured-data-schema"),
                        'type'  => 'heading',
                        'desc'  => __("To add review schema for this type, complete fields below and enable, others live blank.", "wp-seo-structured-data-schema")
                    ),
                    'review_active'        => array(
                        'type' => 'checkbox'
                    ),
                    'review_author'        => array(
                        'title'    => __("Author", "wp-seo-structured-data-schema"),
                        'type'     => 'text',
                        'required' => true
                    ),
                    'review_author_sameAs' => array(
                        'title'    => __("Author Same As profile link", "wp-seo-structured-data-schema"),
                        'type'     => 'textarea',
                        'attr'     => 'placeholder="https://facebook.com/example&#10;https://twitter.com/example"',
                        'required' => true,
                        'desc'     => __('A reference page that unambiguously indicates the item\'s identity; for example, the URL of the item\'s Wikipedia page, Freebase page, or official website.<br> Enter new line for every entry', "wp-seo-structured-data-schema")
                    ),
                    'review_body'          => array(
                        'title'    => __('Review body', "wp-seo-structured-data-schema"),
                        'type'     => 'textarea',
                        'required' => true,
                        'desc'     => __("The actual body of the review.", "wp-seo-structured-data-schema")
                    ),
                    'review_datePublished' => array(
                        'title' => __('Date of Published', "wp-seo-structured-data-schema"),
                        'type'  => 'text',
                        'class' => 'kcseo-date',
                        'desc'  => __("Like this: 2021-12-25 14:20:00", "wp-seo-structured-data-schema")
                    ),
                    'review_ratingValue'   => array(
                        'title' => __('Rating value', "wp-seo-structured-data-schema"),
                        'type'  => 'number',
                        'attr'  => 'step="any"',
                        'desc'  => __("A numerical quality rating for the item.", "wp-seo-structured-data-schema")
                    ),
                    'review_bestRating'    => array(
                        'title' => __('Best rating', "wp-seo-structured-data-schema"),
                        'type'  => 'number',
                        'attr'  => 'step="any"',
                        'desc'  => __("The highest value allowed in this rating system.", "wp-seo-structured-data-schema")
                    ),
                    'review_worstRating'   => array(
                        'title' => __('Worst rating', "wp-seo-structured-data-schema"),
                        'type'  => 'number',
                        'attr'  => 'step="any"',
                        'desc'  => __("The lowest value allowed in this rating system. * Required if the rating system is not on a 5-point scale. If worstRating is omitted, 1 is assumed.", "wp-seo-structured-data-schema")
                    )
                )
            ),
            'MedicalWebPage'       => array(
                'pro'    => false,
                'title'  => __('Medical WebPage', "wp-seo-structured-data-schema"),
                'fields' => array(
                    'active'              => array(
                        'type' => 'checkbox'
                    ),
                    'headline'            => array(
                        'title'    => __('Headline', "wp-seo-structured-data-schema"),
                        'type'     => 'text',
                        'desc'     => __('Medical title', "wp-seo-structured-data-schema"),
                        'required' => true
                    ),
                    'webpage_url' => array(
                        'title'    => __('Webpage url', "wp-seo-structured-data-schema"),
                        'type'     => 'url',
                        'desc'     => __('Web Page Url', "wp-seo-structured-data-schema"),
                    ),
                    'specialty_url' => array(
                        'title'    => __('Specialty url', "wp-seo-structured-data-schema"),
                        'type'     => 'url',
                        'desc'     => __('Specialty Url', "wp-seo-structured-data-schema"),
                    ),
                    'image'               => array(
                        'title'    => __('Image', "wp-seo-structured-data-schema"),
                        'type'     => 'image',
                        'required' => true,
                    ),
                    // dfasdf.
                    'datePublished'       => array(
                        'title'    => __('Published date', "wp-seo-structured-data-schema"),
                        'type'     => 'text',
                        'class'    => 'kcseo-date',
                        'desc'     => __('Like this: 2021-12-25 14:20:00', "wp-seo-structured-data-schema")
                    ),
                    'dateModified'        => array(
                        'title'    => __('Modified date', "wp-seo-structured-data-schema"),
                        'type'     => 'text',
                        'class'    => 'kcseo-date',
                        'desc'     => __('Like this: 2021-12-25 14:20:00', "wp-seo-structured-data-schema")
                    ),
                    'publisher'           => array(
                        'title'    => __('Publisher', "wp-seo-structured-data-schema"),
                        'type'     => 'text',
                        'desc'     => __('Publisher name or Organization name', "wp-seo-structured-data-schema"),
                    ),
                    'publisherImage'      => array(
                        'title'    => __('Publisher Logo', "wp-seo-structured-data-schema"),
                        'type'     => 'image',
                        'desc'     => __('Logos should have a wide aspect ratio, not a square icon.<br>Logos should be no wider than 600px, and no taller than 60px.<br>Always retain the original aspect ratio of the logo when resizing. Ideally, logos are exactly 60px tall with width <= 600px. If maintaining a height of 60px would cause the width to exceed 600px, downscale the logo to exactly 600px wide and reduce the height accordingly below 60px to maintain the original aspect ratio.<br>', "wp-seo-structured-data-schema"),
                    ),

                    'lastreviewed'        => array(
                        'title'    => __('Last Reviewed', "wp-seo-structured-data-schema"),
                        'type'     => 'text',
                        'class'    => 'kcseo-date',
                        'desc'     => __('Like this: 2021-12-25', "wp-seo-structured-data-schema")
                    ),
                    
                    'maincontentofpage'        => array(
                        'title'    => __('Main Content of Page', "wp-seo-structured-data-schema"),
                        'type'     => 'text'
                    ),
                    'about'        => array(
                        'title'    => __('About', "wp-seo-structured-data-schema"),
                        'type'     => 'text'
                    ),
                    'description'         => array(
                        'title' => __('Description', "wp-seo-structured-data-schema"),
                        'type'  => 'textarea',
                        'desc'  => __('Short description. New line is not supported.', "wp-seo-structured-data-schema")
                    ),
                    'keywords'        => array(
                        'title'    => __('Keywords', "wp-seo-structured-data-schema"),
                        'type'     => 'text',
                    ),
                    
                )
            ),
            
            'book'                => array(
                'pro'   => true,
                'title' => __("Book", "wp-seo-structured-data-schema"),
            ),
            'course'              => array(
                'pro'   => true,
                'title' => __("Course", "wp-seo-structured-data-schema"),
            ),
            'JobPosting'          => array(
                'pro'   => true,
                'title' => __("Job Posting", "wp-seo-structured-data-schema"),
            ),
            'movie'               => array(
                'pro'   => true,
                'title' => __("Movie", "wp-seo-structured-data-schema"),
            ),
            'music'               => array(
                'pro'   => true,
                'title' => __("Music", "wp-seo-structured-data-schema"),
            ),
            'recipe'              => array(
                'pro'   => true,
                'title' => __("Recipe", "wp-seo-structured-data-schema"),
            ),
            'TVEpisode'           => array(
                'pro'   => true,
                'title' => __("TVEpisode", "wp-seo-structured-data-schema"),
            ),
            'question'            => array(
                'pro'   => true,
                'title' => __("QAPage", "wp-seo-structured-data-schema"),
            ),
            'itemList'            => array(
                'pro'   => true,
                'title' => __("Item List", "wp-seo-structured-data-schema"),
            ),
            'specialAnnouncement' => [
                'title'  => __('Special Announcement', "wp-seo-structured-data-schema"),
                'fields' => [
                    'active'        => [
                        'type' => 'checkbox'
                    ],
                    'name'          => [
                        'title'    => __('Name', "wp-seo-structured-data-schema"),
                        'type'     => 'text',
                        'desc'     => __('SpecialAnnouncement.name: Name of the announcement. This text should be present on the underlying page.', "wp-seo-structured-data-schema"),
                        'required' => true
                    ],
                    'url'           => [
                        'title'    => __('Page URL', "wp-seo-structured-data-schema"),
                        'type'     => 'url',
                        'desc'     => __('SpecialAnnouncement.url: URL of the page containing the announcements. If present, this must match the URL of the page containing the information.', "wp-seo-structured-data-schema"),
                        'required' => true
                    ],
                    'datePublished' => [
                        'title'    => __('Published date', "wp-seo-structured-data-schema"),
                        'type'     => 'text',
                        'class'    => 'kcseo-date',
                        'desc'     => __('Like this: 2021-12-25 14:20:00', "wp-seo-structured-data-schema"),
                        'required' => true
                    ],
                    'expires'       => [
                        'title'       => __('Expires date', "wp-seo-structured-data-schema"),
                        'type'        => 'text',
                        'class'       => 'kcseo-date',
                        'desc'        => __('Like this: 2021-12-25 14:20:00', "wp-seo-structured-data-schema"),
                        'recommended' => true,
                    ],
                    'text'          => [
                        'title'    => __('Text', "wp-seo-structured-data-schema"),
                        'type'     => 'textarea',
                        'desc'     => __('SpecialAnnouncement.text: Text of the announcements.', "wp-seo-structured-data-schema"),
                        'required' => true
                    ],
                    'locations'     => [
                        'title'     => __('Announcement Locations', "wp-seo-structured-data-schema"),
                        'type'      => 'group',
                        'duplicate' => true,
                        'fields'    => [
                            'location_heading'  => [
                                'type'  => 'heading',
                                'title' => __('Announcement Location', "wp-seo-structured-data-schema")
                            ],
                            'type'              => [
                                'title'    => __('Type', "wp-seo-structured-data-schema"),
                                'type'     => 'select',
                                'options'  => self::announcementLocationTypes(),
                                'required' => true
                            ],
                            'name'              => [
                                'title'       => __('Name', "wp-seo-structured-data-schema"),
                                'type'        => 'text',
                                'desc'        => __("SpecialAnnouncement.announcementLocation.name: ", "wp-seo-structured-data-schema"),
                                'recommended' => true,
                            ],
                            'url'               => [
                                'title'       => __('URL', "wp-seo-structured-data-schema"),
                                'type'        => 'url',
                                'recommended' => true,
                                'desc'        => __("SpecialAnnouncement.announcementLocation.url: URL", "wp-seo-structured-data-schema")
                            ],
                            'address_street'    => [
                                'title'       => __('Address: Street', "wp-seo-structured-data-schema"),
                                'type'        => 'text',
                                'desc'        => __('SpecialAnnouncement.announcementLocation.address.streetAddress: The street address. For example, 1600 Amphitheatre Pkwy.', "wp-seo-structured-data-schema"),
                                'recommended' => true,
                            ],
                            'address_locality'  => [
                                'title'       => __('Address: Locality', "wp-seo-structured-data-schema"),
                                'type'        => 'text',
                                'desc'        => __('SpecialAnnouncement.announcementLocation.address.addressLocality: The locality in which the street address is, and which is in the region. For example, Mountain View.', "wp-seo-structured-data-schema"),
                                'recommended' => true,
                            ],
                            'address_post_code' => [
                                'title'       => __('Address: Post Code', "wp-seo-structured-data-schema"),
                                'type'        => 'text',
                                'desc'        => __('SpecialAnnouncement.announcementLocation.address.postalCode: The postal code. For example, 94043.', "wp-seo-structured-data-schema"),
                                'recommended' => true,
                            ],
                            'address_region'    => [
                                'title'       => __('Address: Region', "wp-seo-structured-data-schema"),
                                'type'        => 'text',
                                'desc'        => __('SpecialAnnouncement.announcementLocation.address.addressRegion: The region in which the locality is, and which is in the country. For example, California.', "wp-seo-structured-data-schema"),
                                'recommended' => true,
                            ],
                            'address_country'   => [
                                'title'       => __('Address: Country', "wp-seo-structured-data-schema"),
                                'type'        => 'text',
                                'desc'        => __('SpecialAnnouncement.announcementLocation.address.addressCountry: The country. For example, USA. You can also provide the two-letter ISO 3166-1 alpha-2 country code.', "wp-seo-structured-data-schema"),
                                'recommended' => true,
                            ],
                            'id'                => [
                                'title' => __('ID', "wp-seo-structured-data-schema"),
                                'type'  => 'text',
                                'desc'  => __('SpecialAnnouncement.announcementLocation.@id: An optional unique identifier so that you can reference pre-existing structured data for this location.', "wp-seo-structured-data-schema"),
                            ],
                            'image'             => array(
                                'title' => __('Image', "wp-seo-structured-data-schema"),
                                'type'  => 'image'
                            ),
                            'priceRange'        => array(
                                'title'       => 'Price Range (Recommended)',
                                'type'        => 'text',
                                'recommended' => true,
                                'desc'        => __("The price range of the business, for example $$$.", "wp-seo-structured-data-schema")
                            ),
                            'telephone'         => array(
                                'title'       => 'Telephone (Recommended)',
                                'type'        => 'text',
                                'recommended' => true
                            )
                        ]
                    ],
                ]
            ],
            'manual_schema' => [
                'title'  => __("Custom Schema", "wp-seo-structured-data-schema"),
                'pro'   => true
            ],
        );

        return apply_filters('kcseo_schema_types', $schemas);
    }

    static function getSiteTypes() {
        $siteTypes = array(
            'Organization',
            'LocalBusiness' => array(
                'AnimalShelter',
                'AutomotiveBusiness'          => array(
                    'AutoBodyShop',
                    'AutoDealer',
                    'AutoPartsStore',
                    'AutoRental',
                    'AutoRepair',
                    'AutoWash',
                    'GasStation',
                    'MotorcycleDealer',
                    'MotorcycleRepair'
                ),
                'ChildCare',
                'DryCleaningOrLaundry',
                'EmergencyService',
                'EmploymentAgency',
                'EntertainmentBusiness'       => array(
                    'AdultEntertainment',
                    'AmusementPark',
                    'ArtGallery',
                    'Casino',
                    'ComedyClub',
                    'MovieTheater',
                    'NightClub',

                ),
                'FinancialService'            => array(
                    'AccountingService',
                    'AutomatedTeller',
                    'BankOrCreditUnion',
                    'InsuranceAgency',
                ),
                'FoodEstablishment'           => array(
                    'Bakery',
                    'BarOrPub',
                    'Brewery',
                    'CafeOrCoffeeShop',
                    'FastFoodRestaurant',
                    'IceCreamShop',
                    'Restaurant',
                    'Winery',
                ),
                'GovernmentOffice',
                'HealthAndBeautyBusiness'     => array(
                    'BeautySalon',
                    'DaySpa',
                    'HairSalon',
                    'HealthClub',
                    'NailSalon',
                    'TattooParlor',
                ),
                'HomeAndConstructionBusiness' => array(
                    'Electrician',
                    'GeneralContractor',
                    'HVACBusiness',
                    'HousePainter',
                    'Locksmith',
                    'MovingCompany',
                    'Plumber',
                    'RoofingContractor',
                ),
                'InternetCafe',
                'LegalService'                => array(
                    'Attorney',
                    'Notary',
                ),
                'Library',
                'MedicalBusiness'             => array(
                    'CommunityHealth',
                    'Dentist',
                    'Dermatology',
                    'DietNutrition',
                    'Emergency',
                    'Geriatric',
                    'Gynecologic',
                    'MedicalClinic',
                    'Midwifery',
                    'Nursing',
                    'Obstetric',
                    'Oncologic',
                    'Optician',
                    'Optometric',
                    'Otolaryngologic',
                    'Pediatric',
                    'Pharmacy',
                    'Physician',
                    'Physiotherapy',
                    'PlasticSurgery',
                    'Podiatric',
                    'PrimaryCare',
                    'Psychiatric',
                    'PublicHealth',
                ),
                'LodgingBusiness'             => array(
                    'BedAndBreakfast',
                    'Campground',
                    'Hostel',
                    'Hotel',
                    'Motel',
                    'Resort',
                ),
                'ProfessionalService',
                'RadioStation',
                'RealEstateAgent',
                'RecyclingCenter',
                'SelfStorage',
                'ShoppingCenter',
                'SportsActivityLocation'      => array(
                    'BowlingAlley',
                    'ExerciseGym',
                    'GolfCourse',
                    'HealthClub',
                    'PublicSwimmingPool',
                    'SkiResort',
                    'SportsClub',
                    'StadiumOrArena',
                    'TennisComplex',
                ),
                'Store'                       => array(
                    'AutoPartsStore',
                    'BikeStore',
                    'BookStore',
                    'ClothingStore',
                    'ComputerStore',
                    'ConvenienceStore',
                    'DepartmentStore',
                    'ElectronicsStore',
                    'Florist',
                    'FurnitureStore',
                    'GardenStore',
                    'GroceryStore',
                    'HardwareStore',
                    'HobbyShop',
                    'HomeGoodsStore',
                    'JewelryStore',
                    'LiquorStore',
                    'MensClothingStore',
                    'MobilePhoneStore',
                    'MovieRentalStore',
                    'MusicStore',
                    'OfficeEquipmentStore',
                    'OutletStore',
                    'PawnShop',
                    'PetStore',
                    'ShoeStore',
                    'SportingGoodsStore',
                    'TireShop',
                    'ToyStore',
                    'WholesaleStore'
                ),
                'TelevisionStation',
                'TouristInformationCenter',
                'TravelAgency'
            ),
            'NGO' => array(),
        );

        return apply_filters('kcseo_site_types', $siteTypes);
    }

    static function getCountryList() {
        $countryList = array(
            "AF" => "Afghanistan",
            "AX" => "Aland Islands",
            "AL" => "Albania",
            "DZ" => "Algeria",
            "AS" => "American Samoa",
            "AD" => "Andorra",
            "AO" => "Angola",
            "AI" => "Anguilla",
            "AQ" => "Antarctica",
            "AG" => "Antigua and Barbuda",
            "AR" => "Argentina",
            "AM" => "Armenia",
            "AW" => "Aruba",
            "AU" => "Australia",
            "AT" => "Austria",
            "AZ" => "Azerbaijan",
            "BS" => "Bahamas",
            "BH" => "Bahrain",
            "BD" => "Bangladesh",
            "BB" => "Barbados",
            "BY" => "Belarus",
            "BE" => "Belgium",
            "BZ" => "Belize",
            "BJ" => "Benin",
            "BM" => "Bermuda",
            "BT" => "Bhutan",
            "BO" => "Bolivia, Plurinational State of",
            "BQ" => "Bonaire, Sint Eustatius and Saba",
            "BA" => "Bosnia and Herzegovina",
            "BW" => "Botswana",
            "BV" => "Bouvet Island",
            "BR" => "Brazil",
            "IO" => "British Indian Ocean Territory",
            "BN" => "Brunei Darussalam",
            "BG" => "Bulgaria",
            "BF" => "Burkina Faso",
            "BI" => "Burundi",
            "KH" => "Cambodia",
            "CM" => "Cameroon",
            "CA" => "Canada",
            "CV" => "Cape Verde",
            "KY" => "Cayman Islands",
            "CF" => "Central African Republic",
            "TD" => "Chad",
            "CL" => "Chile",
            "CN" => "China",
            "CX" => "Christmas Island",
            "CC" => "Cocos (Keeling) Islands",
            "CO" => "Colombia",
            "KM" => "Comoros",
            "CG" => "Congo",
            "CD" => "Congo, the Democratic Republic of the",
            "CK" => "Cook Islands",
            "CR" => "Costa Rica",
            "CI" => "Côte d Ivoire",
            "HR" => "Croatia",
            "CU" => "Cuba",
            "CW" => "Curaçao",
            "CY" => "Cyprus",
            "CZ" => "Czech Republic",
            "DK" => "Denmark",
            "DJ" => "Djibouti",
            "DM" => "Dominica",
            "DO" => "Dominican Republic",
            "EC" => "Ecuador",
            "EG" => "Egypt",
            "SV" => "El Salvador",
            "GQ" => "Equatorial Guinea",
            "ER" => "Eritrea",
            "EE" => "Estonia",
            "ET" => "Ethiopia",
            "FK" => "Falkland Islands (Malvinas)",
            "FO" => "Faroe Islands",
            "FJ" => "Fiji",
            "FI" => "Finland",
            "FR" => "France",
            "GF" => "French Guiana",
            "PF" => "French Polynesia",
            "TF" => "French Southern Territories",
            "GA" => "Gabon",
            "GM" => "Gambia",
            "GE" => "Georgia",
            "DE" => "Germany",
            "GH" => "Ghana",
            "GI" => "Gibraltar",
            "GR" => "Greece",
            "GL" => "Greenland",
            "GD" => "Grenada",
            "GP" => "Guadeloupe",
            "GU" => "Guam",
            "GT" => "Guatemala",
            "GG" => "Guernsey",
            "GN" => "Guinea",
            "GW" => "Guinea-Bissau",
            "GY" => "Guyana",
            "HT" => "Haiti",
            "HM" => "Heard Island and McDonald Islands",
            "VA" => "Holy See (Vatican City State)",
            "HN" => "Honduras",
            "HK" => "Hong Kong",
            "HU" => "Hungary",
            "IS" => "Iceland",
            "IN" => "India",
            "ID" => "Indonesia",
            "IR" => "Iran, Islamic Republic of",
            "IQ" => "Iraq",
            "IE" => "Ireland",
            "IM" => "Isle of Man",
            "IL" => "Israel",
            "IT" => "Italy",
            "JM" => "Jamaica",
            "JP" => "Japan",
            "JE" => "Jersey",
            "JO" => "Jordan",
            "KZ" => "Kazakhstan",
            "KE" => "Kenya",
            "KI" => "Kiribati",
            "KP" => "Korea, Democratic People's Republic of",
            "KR" => "Korea, Republic of,",
            "KW" => "Kuwait",
            "KG" => "Kyrgyzstan",
            "LA" => "Lao Peoples Democratic Republic",
            "LV" => "Latvia",
            "LB" => "Lebanon",
            "LS" => "Lesotho",
            "LR" => "Liberia",
            "LY" => "Libya",
            "LI" => "Liechtenstein",
            "LT" => "Lithuania",
            "LU" => "Luxembourg",
            "MO" => "Macao",
            "MK" => "Macedonia, the former Yugoslav Republic of",
            "MG" => "Madagascar",
            "MW" => "Malawi",
            "MY" => "Malaysia",
            "MV" => "Maldives",
            "ML" => "Mali",
            "MT" => "Malta",
            "MH" => "Marshall Islands",
            "MQ" => "Martinique",
            "MR" => "Mauritania",
            "MU" => "Mauritius",
            "YT" => "Mayotte",
            "MX" => "Mexico",
            "FM" => "Micronesia, Federated States of",
            "MD" => "Moldova, Republic of",
            "MC" => "Monaco",
            "MN" => "Mongolia",
            "ME" => "Montenegro",
            "MS" => "Montserrat",
            "MA" => "Morocco",
            "MZ" => "Mozambique",
            "MM" => "Myanmar",
            "NA" => "Namibia",
            "NR" => "Nauru",
            "NP" => "Nepal",
            "NL" => "Netherlands",
            "NC" => "New Caledonia",
            "NZ" => "New Zealand",
            "NI" => "Nicaragua",
            "NE" => "Niger",
            "NG" => "Nigeria",
            "NU" => "Niue",
            "NF" => "Norfolk Island",
            "MP" => "Northern Mariana Islands",
            "NO" => "Norway",
            "OM" => "Oman",
            "PK" => "Pakistan",
            "PW" => "Palau",
            "PS" => "Palestine, State of",
            "PA" => "Panama",
            "PG" => "Papua New Guinea",
            "PY" => "Paraguay",
            "PE" => "Peru",
            "PH" => "Philippines",
            "PN" => "Pitcairn",
            "PL" => "Poland",
            "PT" => "Portugal",
            "PR" => "Puerto Rico",
            "QA" => "Qatar",
            "RE" => "Reunion",
            "RO" => "Romania",
            "RU" => "Russian Federation",
            "RW" => "Rwanda",
            "BL" => "Saint Barthélemy",
            "SH" => "Saint Helena, Ascension and Tristan da Cunha",
            "KN" => "Saint Kitts and Nevis",
            "LC" => "Saint Lucia",
            "MF" => "Saint Martin (French part)",
            "PM" => "Saint Pierre and Miquelon",
            "VC" => "Saint Vincent and the Grenadines",
            "WS" => "Samoa",
            "SM" => "San Marino",
            "ST" => "Sao Tome and Principe",
            "SA" => "Saudi Arabia",
            "SN" => "Senegal",
            "RS" => "Serbia",
            "SC" => "Seychelles",
            "SL" => "Sierra Leone",
            "SG" => "Singapore",
            "SX" => "Sint Maarten (Dutch part)",
            "SK" => "Slovakia",
            "SI" => "Slovenia",
            "SB" => "Solomon Islands",
            "SO" => "Somalia",
            "ZA" => "South Africa",
            "GS" => "South Georgia and the South Sandwich Islands",
            "SS" => "South Sudan",
            "ES" => "Spain",
            "LK" => "Sri Lanka",
            "SD" => "Sudan",
            "SR" => "Suriname",
            "SJ" => "Svalbard and Jan Mayen",
            "SZ" => "Swaziland",
            "SE" => "Sweden",
            "CH" => "Switzerland",
            "SY" => "Syrian Arab Republic",
            "TW" => "Taiwan, Province of China",
            "TJ" => "Tajikistan",
            "TZ" => "Tanzania, United Republic of",
            "TH" => "Thailand",
            "TL" => "Timor-Leste",
            "TG" => "Togo",
            "TK" => "Tokelau",
            "TO" => "Tonga",
            "TT" => "Trinidad and Tobago",
            "TN" => "Tunisia",
            "TR" => "Turkey",
            "TM" => "Turkmenistan",
            "TC" => "Turks and Caicos Islands",
            "TV" => "Tuvalu",
            "UG" => "Uganda",
            "UA" => "Ukraine",
            "AE" => "United Arab Emirates",
            "GB" => "United Kingdom",
            "US" => "United States",
            "UM" => "United States Minor Outlying Islands",
            "UY" => "Uruguay",
            "UZ" => "Uzbekistan",
            "VU" => "Vanuatu",
            "VE" => "Venezuela, Bolivarian Republic of",
            "VN" => "Viet Nam",
            "VG" => "Virgin Islands, British",
            "VI" => "Virgin Islands, U.S.",
            "WF" => "Wallis and Futuna",
            "EH" => "Western Sahara",
            "YE" => "Yemen",
            "ZM" => "Zambia",
            "ZW" => "Zimbabwe",
        );

        return apply_filters('kcseo_country_list', $countryList);
    }

    static function getContactTypes() {
        $contact_types = array(
            "customer service",
            "customer support",
            "technical support",
            "billing support",
            "bill payment",
            "sales",
            "reservations",
            "credit card support",
            "emergency",
            "baggage tracking",
            "roadside assistance",
            "package tracking"
        );

        return apply_filters('kcseo_contact_types', $contact_types);
    }

    static function getLanguageList() {
        $language_list = array(
            "Akan",
            "Amharic",
            "Arabic",
            "Assamese",
            "Awadhi",
            "Azerbaijani",
            "Balochi",
            "Belarusian",
            "Bengali",
            "Bhojpuri",
            "Burmese",
            "Cantonese",
            "Cebuano",
            "Chewa",
            "Chhattisgarhi",
            "Chittagonian",
            "Czech",
            "Deccan",
            "Dhundhari",
            "Dutch",
            "English",
            "French",
            "Fula",
            "Gan",
            "German",
            "Greek",
            "Gujarati",
            "Haitian Creole",
            "Hakka",
            "Haryanvi",
            "Hausa",
            "Hiligaynon",
            "Hindi / Urdu",
            "Hmong",
            "Hungarian",
            "Igbo",
            "Ilokano",
            "Italian",
            "Japanese",
            "Javanese",
            "Jin",
            "Kannada",
            "Kazakh",
            "Khmer",
            "Kinyarwanda",
            "Kirundi",
            "Konkani",
            "Korean",
            "Kurdish",
            "Madurese",
            "Magahi",
            "Maithili",
            "Malagasy",
            "Malay/Indonesian",
            "Malayalam",
            "Mandarin",
            "Marathi",
            "Marwari",
            "Min Bei",
            "Min Dong",
            "Min Nan",
            "Mossi",
            "Nepali",
            "Oriya",
            "Oromo",
            "Pashto",
            "Persian",
            "Polish",
            "Portuguese",
            "Punjabi",
            "Quechua",
            "Romanian",
            "Russian",
            "Saraiki",
            "Serbo-Croatian",
            "Shona",
            "Sindhi",
            "Sinhalese",
            "Somali",
            "Spanish",
            "Sundanese",
            "Swahili",
            "Swedish",
            "Sylheti",
            "Tagalog",
            "Tamil",
            "Telugu",
            "Thai",
            "Turkish",
            "Ukrainian",
            "Uyghur",
            "Uzbek",
            "Vietnamese",
            "Wu",
            "Xhosa",
            "Xiang",
            "Yoruba",
            "Zulu",
        );

        return apply_filters('kcseo_language_list', $language_list);
    }

    static function getSocialList() {
        $socialList = array(
            'facebook'    => __('Facebook'),
            'twitter'     => __('Twitter'),
            'google-plus' => __('Google+'),
            'instagram'   => __('Instagram'),
            'youtube'     => __('Youtube'),
            'linkedin'    => __('LinkedIn'),
            'myspace'     => __('Myspace'),
            'pinterest'   => __('Pinterest'),
            'soundcloud'  => __('SoundCloud'),
            'tumblr'      => __('Tumblr'),
            'wikidata'    => __('Wikidata'),
        );

        return apply_filters('kcseo_social_list', $socialList);
    }


    static function announcementLocationTypes() {
        return apply_filters('kcseo_announcement_location_types', [
            'Airport',
            'Aquarium',
            'Beach',
            'Bridge',
            'BuddhistTemple',
            'BusStation',
            'BusStop',
            'Campground',
            'CatholicChurch',
            'Cemetery',
            'Church',
            'CivicStructure',
            'CityHall',
            'CollegeOrUniversity',
            'Courthouse',
            'CovidTestingFacility',
            'Crematorium',
            'DefenceEstablishment',
            'EducationalOrganization',
            'ElementarySchool',
            'Embassy',
            'EventVenue',
            'FireStation',
            'GovernmentBuilding',
            'HighSchool',
            'HinduTemple',
            'Hospital',
            'LegislativeBuilding',
            'MiddleSchool',
            'Mosque',
            'MovieTheater',
            'Museum',
            'MusicVenue',
            'Park',
            'ParkingFacility',
            'PerformingArtsTheater',
            'PlaceOfWorship',
            'Playground',
            'PoliceStation',
            'Preschool',
            'RVPark',
            'School',
            'StadiumOrArena',
            'SubwayStation',
            'Synagogue',
            'TaxiStand',
            'TrainStation',
            'Zoo',
        ]);
    }

    static function getReviewNotice() {
        $html = null;
        $html = '<span>As of September, Google made a major change to review snippet schema and structure data markup. Google no longer support "self-serving" independent markup tied to the general types and has narrow support to specific types.</span><br><br>
<span>You can read more about Google\'s change here:<br><a target="_blank" href="https://webmasters.googleblog.com/2019/09/making-review-rich-results-more-helpful.html">https://webmasters.googleblog.com/2019/09/making-review-rich-results-more-helpful.html</a></span><br><br>
<span style="font-weight: bold">If you are a user of our plugin prior to September 2019, you need to remove the review schema for this tab on all pages and  post where you\'ve it for reviews and add back to the supported types (such as: book, course, event, movie, product, recipe, etc):</span><br><br>
<span style="display: block;margin: 0 auto;max-width: 800px;">1. Simple uncheck the "enable" tab in this section<br>
2. Update the page or post to remove the review schema.<br>
3. Then re-add new review schema within the appropriet type tab(i.e. book, course, event, movie, product, recipe, etc)</span>
<br>To review Google\'s documentation on <a target="_blank" href="https://developers.google.com/search/docs/data-types/review-snippet">https://developers.google.com/search/docs/data-types/review-snippet</a>';

        return $html;
    }

}