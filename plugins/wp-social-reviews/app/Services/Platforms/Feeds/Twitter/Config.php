<?php

namespace WPSocialReviews\App\Services\Platforms\Feeds\Twitter;

use WPSocialReviews\Framework\Support\Arr;

if (!defined('ABSPATH')) {
    exit;
}

class Config
{
    public function __construct()
    {

    }

    public function getStyleElement()
    {
        return array(
            'header' => array(
                'title' => __('Header', 'wp-social-reviews'),
                'key'  => 'header',
                'condition' => array(
	                'key' => 'header_settings.show_header',
	                'selector'  => 'true',
                ),
                array(
                    'title'     => __('Full Name', 'wp-social-reviews'),
                    'key'      => 'full_name',
                    'divider' => true,
                    'typography' => true,
                    'padding' => false,
                    'border' => false,
                    'styles' => array(
                        array(
                            'title'      => __('Text Color:', 'wp-social-reviews'),
                            'fieldKey'  => 'text_color',
                            'type'      => 'color_picker',
                            'flex'      => true,
                        )
                    )
                ),
                array(
                    'title'     => __('User Name', 'wp-social-reviews'),
                    'key'      => 'user_name',
                    'divider' => true,
                    'typography' => true,
                    'padding' => false,
                    'border' => false,
                    'styles' => array(
                        array(
                            'title'      => __('Text Color:', 'wp-social-reviews'),
                            'fieldKey'  => 'text_color',
                            'type'      => 'color_picker',
                            'flex'      => true,
                        )
                    )
                ),
                array(
                    'title'     => __('Description', 'wp-social-reviews'),
                    'key'      => 'description',
                    'divider' => true,
                    'typography' => true,
                    'padding' => false,
                    'border' => false,
                    'styles' => array(
                        array(
                            'title'      => __('Text Color:', 'wp-social-reviews'),
                            'fieldKey'  => 'text_color',
                            'type'      => 'color_picker',
                            'flex'      => true,
                        )
                    )
                ),
                array(
                    'title'     => __('Location', 'wp-social-reviews'),
                    'key'      => 'location',
                    'divider' => true,
                    'typography' => true,
                    'padding' => false,
                    'border' => false,
                    'styles' => array(
                        array(
                            'title'      => __('Text Color:', 'wp-social-reviews'),
                            'fieldKey'  => 'text_color',
                            'type'      => 'color_picker',
                            'flex'      => true,
                        )
                    )
                ),
                array(
                    'title'     => __('Statistics Label', 'wp-social-reviews'),
                    'key'      => 'statistics_label',
                    'divider' => true,
                    'typography' => true,
                    'padding' => false,
                    'border' => false,
                    'styles' => array(
                        array(
                            'title'      => __('Text Color:', 'wp-social-reviews'),
                            'fieldKey'  => 'text_color',
                            'type'      => 'color_picker',
                            'flex'      => true,
                        )
                    )
                ),
                array(
                    'title'     => __('Statistics Count', 'wp-social-reviews'),
                    'key'      => 'statistics_count',
                    'divider' => true,
                    'typography' => true,
                    'padding' => false,
                    'border' => false,
                    'styles' => array(
                        array(
                            'title'      => __('Text Color:', 'wp-social-reviews'),
                            'fieldKey'  => 'text_color',
                            'type'      => 'color_picker',
                            'flex'      => true,
                        )
                    )
                ),
                array(
                    'title'     => __('Follow Button', 'wp-social-reviews'),
                    'key'      => 'follow_button',
                    'divider' => true,
                    'typography' => true,
                    'padding' => false,
                    'border' => false,
                    'styles' => array(
                        array(
                            'title'      => __('Text Color:', 'wp-social-reviews'),
                            'fieldKey'  => 'text_color',
                            'type'      => 'color_picker',
                            'flex'      => true,
                        ),
                        array(
                            'title'      => __('Background Color:', 'wp-social-reviews'),
                            'fieldKey'  => 'background_color',
                            'type'      => 'color_picker',
                            'flex'      => true,
                        )
                    )
                ),
                array(
                    'title'     => __('Box', 'wp-social-reviews'),
                    'key'      => 'info_wrapper',
                    'divider' => false,
                    'typography' => false,
                    'padding' => true,
                    'border' => true,
                    'styles' => array(
                        array(
                            'title'      => __('Background Color:', 'wp-social-reviews'),
                            'fieldKey'  => 'background_color',
                            'type'      => 'color_picker',
                            'flex'      => true,
                        )
                    )
                ),
            ),
            'name' => array(
                'title' => __('Name', 'wp-social-reviews'),
                'key'  => 'name',
                'condition' => array(
	                'key' => 'advance_settings.author_name',
	                'selector'  => 'true',
                ),
                array(
                    'title'     => __('Author', 'wp-social-reviews'),
                    'key'      => 'author',
                    'divider' => false,
                    'typography' => true,
                    'padding' => false,
                    'border' => false,
                    'styles' => array(
                        array(
                            'title'      => __('Text Color:', 'wp-social-reviews'),
                            'fieldKey'  => 'text_color',
                            'type'      => 'color_picker',
                            'flex'      => true,
                        )
                    )
                )
            ),
            'meta' => array(
                'title' => __('Meta', 'wp-social-reviews'),
                'key'  => 'meta',
                array(
                    'title'     => __('Username', 'wp-social-reviews'),
                    'key'      => 'username',
                    'divider' => true,
                    'typography' => true,
                    'padding' => false,
                    'border' => false,
                    'styles' => array(
                        array(
                            'title'      => __('Text Color:', 'wp-social-reviews'),
                            'fieldKey'  => 'text_color',
                            'type'      => 'color_picker',
                            'flex'      => true,
                        )
                    )
                ),
                array(
                    'title'     => __('Date', 'wp-social-reviews'),
                    'key'      => 'tweet_date',
                    'divider' => true,
                    'typography' => true,
                    'padding' => false,
                    'border' => false,
                    'styles' => array(
                        array(
                            'title'      => __('Text Color:', 'wp-social-reviews'),
                            'fieldKey'  => 'text_color',
                            'type'      => 'color_picker',
                            'flex'      => true,
                        )
                    )
                ),
                array(
                    'title'     => '',
                    'key'      => 'tweet_meta',
                    'divider' => false,
                    'typography' => false,
                    'padding' => true,
                    'border' => false,
                    'styles' => []
                )
            ),
            'content' => array(
                'title' => __('Content', 'wp-social-reviews'),
                'key'  => 'content',
                'condition' => array(
	                'key' => 'advance_settings.tweet_text',
	                'selector'  => 'true',
                ),
                array(
                    'title'     => __('Text', 'wp-social-reviews'),
                    'key'      => 'tweet',
                    'divider' => true,
                    'typography' => true,
                    'padding' => true,
                    'border' => false,
                    'styles' => array(
                        array(
                            'title'      => __('Text Color:', 'wp-social-reviews'),
                            'fieldKey'  => 'text_color',
                            'type'      => 'color_picker',
                            'flex'      => true,
                        )
                    )
                ),
                array(
                    'title'     => __('Hashtag', 'wp-social-reviews'),
                    'key'      => 'hashtag',
                    'divider' => false,
                    'typography' => false,
                    'padding' => false,
                    'border' => false,
                    'styles' => array(
                        array(
                            'title'      => __('Hashtag Color:', 'wp-social-reviews'),
                            'fieldKey'  => 'text_color',
                            'type'      => 'color_picker',
                            'flex'      => true,
                        )
                    )
                )
            ),
            'action' => array(
                'title' => __('Action', 'wp-social-reviews'),
                'key'  => 'action',
                array(
                    'title'     => __('Label', 'wp-social-reviews'),
                    'key'      => 'action_text',
                    'divider' => true,
                    'typography' => true,
                    'padding' => false,
                    'border' => false,
                    'styles' => array(
                        array(
                            'title'      => __('Text Color:', 'wp-social-reviews'),
                            'fieldKey'  => 'text_color',
                            'type'      => 'color_picker',
                            'flex'      => true,
                        )
                    )
                ),
                array(
                    'title'     => __('Icon', 'wp-social-reviews'),
                    'key'      => 'action_icon',
                    'divider' => true,
                    'typography' => false,
                    'padding' => false,
                    'border' => false,
                    'styles' => array(
                        array(
                            'title'      => __('Icon Color:', 'wp-social-reviews'),
                            'fieldKey'  => 'fill_color',
                            'type'      => 'color_picker',
                            'flex'      => true,
                        )
                    )
                ),
                array(
                    'title'     => '',
                    'key'      => 'action_wrapper',
                    'divider' => false,
                    'typography' => false,
                    'padding' => true,
                    'border' => false,
                    'styles' => []
                )
            ),
            'item' => array(
	            'title' => __('Item Box', 'wp-social-reviews'),
	            'key'  => 'item_box',
	            array(
		            'key'      => 'item_box',
		            'divider' => false,
		            'typography' => false,
		            'padding' => true,
		            'border' => true,
		            'styles' => array(
			            array(
				            'title'      => __('Background Color:', 'wp-social-reviews'),
				            'fieldKey'  => 'background_color',
				            'type'      => 'color_picker',
				            'flex'      => true,
			            )
		            )
	            )
            ),
        );
    }

    public function formatStylesConfig($settings = [], $postId = null)
    {
        $prefix = '.wpsr-tw-feed-template-'.$postId;
        return [
            'styles' => array(
                'full_name' => array(
                    'selector' => $prefix .' .wpsr-twitter-feed-header .wpsr-twitter-user-info-wrapper .wpsr-twitter-user-info .wpsr-twitter-user-info-name-wrapper .wpsr-twitter-user-info-name',
                    'color'  => array(
                        'text_color' => Arr::get($settings,'styles.full_name.color.text_color', '')
                    ),
                    'typography' => array(
                        'font_size' => array(
                            'desktop' => Arr::get($settings,'styles.full_name.typography.font_size.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.full_name.typography.font_size.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.full_name.typography.font_size.mobile', ''),
                        ),
                        'letter_spacing'  => array(
                            'desktop' => Arr::get($settings,'styles.full_name.typography.letter_spacing.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.full_name.typography.letter_spacing.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.full_name.typography.letter_spacing.mobile', ''),
                        ),
                        'line_height'  => array(
                            'desktop' => Arr::get($settings,'styles.full_name.typography.line_height.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.full_name.typography.line_height.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.full_name.typography.line_height.mobile', ''),
                        ),
                        'font_weight'  => Arr::get($settings,'styles.full_name.typography.font_weight', ''),
                        'font_style'  => Arr::get($settings,'styles.full_name.typography.font_style', ''),
                        'text_transform'  => Arr::get($settings,'styles.full_name.typography.text_transform', ''),
                        'text_decoration'  => Arr::get($settings,'styles.full_name.typography.text_decoration', ''),
                    ),
                ),
                'user_name' => array(
                    'selector' => $prefix .' .wpsr-twitter-feed-header .wpsr-twitter-user-info-wrapper .wpsr-twitter-user-info .wpsr-twitter-user-info-name-wrapper .wpsr-twitter-user-info-username',
                    'color'  => array(
                        'text_color' => Arr::get($settings,'styles.user_name.color.text_color', '')
                    ),
                    'typography' => array(
                        'font_size' => array(
                            'desktop' => Arr::get($settings,'styles.user_name.typography.font_size.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.user_name.typography.font_size.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.user_name.typography.font_size.mobile', ''),
                        ),
                        'letter_spacing'  => array(
                            'desktop' => Arr::get($settings,'styles.user_name.typography.letter_spacing.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.user_name.typography.letter_spacing.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.user_name.typography.letter_spacing.mobile', ''),
                        ),
                        'line_height'  => array(
                            'desktop' => Arr::get($settings,'styles.user_name.typography.line_height.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.user_name.typography.line_height.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.user_name.typography.line_height.mobile', ''),
                        ),
                        'font_weight'  => Arr::get($settings,'styles.user_name.typography.font_weight', ''),
                        'font_style'  => Arr::get($settings,'styles.user_name.typography.font_style', ''),
                        'text_transform'  => Arr::get($settings,'styles.user_name.typography.text_transform', ''),
                        'text_decoration'  => Arr::get($settings,'styles.user_name.typography.text_decoration', ''),
                    ),
                ),
                'description' => array(
                    'selector' => $prefix .' .wpsr-twitter-feed-header .wpsr-twitter-user-info-wrapper .wpsr-twitter-user-info .wpsr-twitter-user-bio p',
                    'color'  => array(
                        'text_color' => Arr::get($settings,'styles.description.color.text_color', '')
                    ),
                    'typography' => array(
                        'font_size' => array(
                            'desktop' => Arr::get($settings,'styles.description.typography.font_size.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.description.typography.font_size.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.description.typography.font_size.mobile', ''),
                        ),
                        'letter_spacing'  => array(
                            'desktop' => Arr::get($settings,'styles.description.typography.letter_spacing.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.description.typography.letter_spacing.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.description.typography.letter_spacing.mobile', ''),
                        ),
                        'line_height'  => array(
                            'desktop' => Arr::get($settings,'styles.description.typography.line_height.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.description.typography.line_height.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.description.typography.line_height.mobile', ''),
                        ),
                        'font_weight'  => Arr::get($settings,'styles.description.typography.font_weight', ''),
                        'font_style'  => Arr::get($settings,'styles.description.typography.font_style', ''),
                        'text_transform'  => Arr::get($settings,'styles.description.typography.text_transform', ''),
                        'text_decoration'  => Arr::get($settings,'styles.description.typography.text_decoration', ''),
                    ),
                ),
                'location' => array(
                    'selector' => $prefix .' .wpsr-twitter-feed-header .wpsr-twitter-user-info-wrapper .wpsr-twitter-user-info .wpsr-twitter-user-contact span',
                    'color'  => array(
                        'text_color' => Arr::get($settings,'styles.location.color.text_color', '')
                    ),
                    'typography' => array(
                        'font_size' => array(
                            'desktop' => Arr::get($settings,'styles.location.typography.font_size.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.location.typography.font_size.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.location.typography.font_size.mobile', ''),
                        ),
                        'letter_spacing'  => array(
                            'desktop' => Arr::get($settings,'styles.location.typography.letter_spacing.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.location.typography.letter_spacing.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.location.typography.letter_spacing.mobile', ''),
                        ),
                        'line_height'  => array(
                            'desktop' => Arr::get($settings,'styles.location.typography.line_height.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.location.typography.line_height.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.location.typography.line_height.mobile', ''),
                        ),
                        'font_weight'  => Arr::get($settings,'styles.location.typography.font_weight', ''),
                        'font_style'  => Arr::get($settings,'styles.location.typography.font_style', ''),
                        'text_transform'  => Arr::get($settings,'styles.location.typography.text_transform', ''),
                        'text_decoration'  => Arr::get($settings,'styles.location.typography.text_decoration', ''),
                    ),
                ),
                'statistics_label' => array(
                    'selector' => $prefix .' .wpsr-twitter-feed-header .wpsr-twitter-user-info-wrapper .wpsr-twitter-user-info .wpsr-twitter-user-statistics .wpsr-twitter-user-statistics-item .wpsr-twitter-user-statistics-item-name',
                    'color'  => array(
                        'text_color' => Arr::get($settings,'styles.statistics_label.color.text_color', '')
                    ),
                    'typography' => array(
                        'font_size' => array(
                            'desktop' => Arr::get($settings,'styles.statistics_label.typography.font_size.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.statistics_label.typography.font_size.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.statistics_label.typography.font_size.mobile', ''),
                        ),
                        'letter_spacing'  => array(
                            'desktop' => Arr::get($settings,'styles.statistics_label.typography.letter_spacing.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.statistics_label.typography.letter_spacing.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.statistics_label.typography.letter_spacing.mobile', ''),
                        ),
                        'line_height'  => array(
                            'desktop' => Arr::get($settings,'styles.statistics_label.typography.line_height.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.statistics_label.typography.line_height.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.statistics_label.typography.line_height.mobile', ''),
                        ),
                        'font_weight'  => Arr::get($settings,'styles.statistics_label.typography.font_weight', ''),
                        'font_style'  => Arr::get($settings,'styles.statistics_label.typography.font_style', ''),
                        'text_transform'  => Arr::get($settings,'styles.statistics_label.typography.text_transform', ''),
                        'text_decoration'  => Arr::get($settings,'styles.statistics_label.typography.text_decoration', ''),
                    ),
                ),
                'statistics_count' => array(
                    'selector' => $prefix .' .wpsr-twitter-feed-header .wpsr-twitter-user-info-wrapper .wpsr-twitter-user-info .wpsr-twitter-user-statistics .wpsr-twitter-user-statistics-item .wpsr-twitter-user-statistics-item-data',
                    'color'  => array(
                        'text_color' => Arr::get($settings,'styles.statistics_count.color.text_color', '')
                    ),
                    'typography' => array(
                        'font_size' => array(
                            'desktop' => Arr::get($settings,'styles.statistics_count.typography.font_size.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.statistics_count.typography.font_size.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.statistics_count.typography.font_size.mobile', ''),
                        ),
                        'letter_spacing'  => array(
                            'desktop' => Arr::get($settings,'styles.statistics_count.typography.letter_spacing.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.statistics_count.typography.letter_spacing.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.statistics_count.typography.letter_spacing.mobile', ''),
                        ),
                        'line_height'  => array(
                            'desktop' => Arr::get($settings,'styles.statistics_count.typography.line_height.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.statistics_count.typography.line_height.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.statistics_count.typography.line_height.mobile', ''),
                        ),
                        'font_weight'  => Arr::get($settings,'styles.statistics_count.typography.font_weight', ''),
                        'font_style'  => Arr::get($settings,'styles.statistics_count.typography.font_style', ''),
                        'text_transform'  => Arr::get($settings,'styles.statistics_count.typography.text_transform', ''),
                        'text_decoration'  => Arr::get($settings,'styles.statistics_count.typography.text_decoration', ''),
                    ),
                ),
                'follow_button' => array(
                    'selector' => $prefix .' .wpsr-twitter-feed-header .wpsr-twitter-user-info-wrapper .wpsr-twitter-user-info-head .wpsr-twitter-user-follow-btn',
                    'color'  => array(
                        'text_color' => Arr::get($settings,'styles.follow_button.color.text_color', ''),
                        'background_color' => Arr::get($settings,'styles.follow_button.color.background_color', ''),
                    ),
                    'typography' => array(
                        'font_size' => array(
                            'desktop' => Arr::get($settings,'styles.follow_button.typography.font_size.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.follow_button.typography.font_size.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.follow_button.typography.font_size.mobile', ''),
                        ),
                        'letter_spacing'  => array(
                            'desktop' => Arr::get($settings,'styles.follow_button.typography.letter_spacing.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.follow_button.typography.letter_spacing.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.follow_button.typography.letter_spacing.mobile', ''),
                        ),
                        'line_height'  => array(
                            'desktop' => Arr::get($settings,'styles.follow_button.typography.line_height.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.follow_button.typography.line_height.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.follow_button.typography.line_height.mobile', ''),
                        ),
                        'font_weight'  => Arr::get($settings,'styles.follow_button.typography.font_weight', ''),
                        'font_style'  => Arr::get($settings,'styles.follow_button.typography.font_style', ''),
                        'text_transform'  => Arr::get($settings,'styles.follow_button.typography.text_transform', ''),
                        'text_decoration'  => Arr::get($settings,'styles.follow_button.typography.text_decoration', ''),
                    ),
                ),
                'info_wrapper' => array(
                    'selector' => $prefix .' .wpsr-twitter-feed-header .wpsr-twitter-user-info-wrapper',
                    'color'  => array(
                        'background_color' => Arr::get($settings,'styles.info_wrapper.color.background_color', ''),
                    ),
                    'padding' => array(
                        'top' => array(
                            'desktop' => Arr::get($settings,'styles.info_wrapper.padding.top.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.info_wrapper.padding.top.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.info_wrapper.padding.top.mobile', ''),
                        ),
                        'right' => array(
                            'desktop' => Arr::get($settings,'styles.info_wrapper.padding.right.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.info_wrapper.padding.right.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.info_wrapper.padding.right.mobile', ''),
                        ),
                        'bottom' => array(
                            'desktop' => Arr::get($settings,'styles.info_wrapper.padding.bottom.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.info_wrapper.padding.bottom.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.info_wrapper.padding.bottom.mobile', ''),
                        ),
                        'left' => array(
                            'desktop' => Arr::get($settings,'styles.info_wrapper.padding.left.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.info_wrapper.padding.left.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.info_wrapper.padding.left.mobile', ''),
                        ),
                        'linked' => Arr::get($settings,'styles.info_wrapper.padding.linked', false),
                    ),
                    'border' => array(
                        'top' => array(
                            'desktop' => Arr::get($settings,'styles.info_wrapper.border.top.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.info_wrapper.border.top.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.info_wrapper.border.top.mobile', ''),
                        ),
                        'right' => array(
                            'desktop' => Arr::get($settings,'styles.info_wrapper.border.right.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.info_wrapper.border.right.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.info_wrapper.border.right.mobile', ''),
                        ),
                        'bottom' => array(
                            'desktop' => Arr::get($settings,'styles.info_wrapper.border.bottom.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.info_wrapper.border.bottom.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.info_wrapper.border.bottom.mobile', ''),
                        ),
                        'left' => array(
                            'desktop' => Arr::get($settings,'styles.info_wrapper.border.left.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.info_wrapper.border.left.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.info_wrapper.border.left.mobile', ''),
                        ),
                        'linked' => Arr::get($settings,'styles.info_wrapper.border.linked', false),
                        'border_style' => Arr::get($settings,'styles.info_wrapper.border.border_style', ''),
                        'border_color' => Arr::get($settings,'styles.info_wrapper.border.border_color', ''),
                    ),
                ),
                'author' => array(
                    'selector' => $prefix .' .wpsr-twitter-tweet .wpsr-twitter-author-tweet .wpsr-tweet-author-info .wpsr-tweet-author-links a.wpsr-tweet-author-name',
                    'color'  => array(
                        'text_color' => Arr::get($settings,'styles.author.color.text_color', '')
                    ),
                    'typography' => array(
                        'font_size' => array(
                            'desktop' => Arr::get($settings,'styles.author.typography.font_size.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.author.typography.font_size.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.author.typography.font_size.mobile', ''),
                        ),
                        'letter_spacing'  => array(
                            'desktop' => Arr::get($settings,'styles.author.typography.letter_spacing.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.author.typography.letter_spacing.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.author.typography.letter_spacing.mobile', ''),
                        ),
                        'line_height'  => array(
                            'desktop' => Arr::get($settings,'styles.author.typography.line_height.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.author.typography.line_height.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.author.typography.line_height.mobile', ''),
                        ),
                        'font_weight'  => Arr::get($settings,'styles.author.typography.font_weight', ''),
                        'font_style'  => Arr::get($settings,'styles.author.typography.font_style', ''),
                        'text_transform'  => Arr::get($settings,'styles.author.typography.text_transform', ''),
                        'text_decoration'  => Arr::get($settings,'styles.author.typography.text_decoration', ''),
                    ),
                ),
                'username' => array(
                    'selector' => $prefix .' .wpsr-twitter-tweet .wpsr-twitter-author-tweet .wpsr-tweet-author-info .wpsr-tweet-author-links a.wpsr-tweet-user-name',
                    'color'  => array(
                        'text_color' => Arr::get($settings,'styles.username.color.text_color', '')
                    ),
                    'typography' => array(
                        'font_size' => array(
                            'desktop' => Arr::get($settings,'styles.username.typography.font_size.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.username.typography.font_size.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.username.typography.font_size.mobile', ''),
                        ),
                        'letter_spacing'  => array(
                            'desktop' => Arr::get($settings,'styles.username.typography.letter_spacing.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.username.typography.letter_spacing.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.username.typography.letter_spacing.mobile', ''),
                        ),
                        'line_height'  => array(
                            'desktop' => Arr::get($settings,'styles.username.typography.line_height.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.username.typography.line_height.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.username.typography.line_height.mobile', ''),
                        ),
                        'font_weight'  => Arr::get($settings,'styles.username.typography.font_weight', ''),
                        'font_style'  => Arr::get($settings,'styles.username.typography.font_style', ''),
                        'text_transform'  => Arr::get($settings,'styles.username.typography.text_transform', ''),
                        'text_decoration'  => Arr::get($settings,'styles.username.typography.text_decoration', ''),
                    ),
                ),
                'tweet_date' => array(
                    'selector' => $prefix .' .wpsr-twitter-tweet .wpsr-twitter-author-tweet .wpsr-tweet-author-info .wpsr-tweet-author-links a.wpsr-tweet-time',
                    'color'  => array(
                        'text_color' => Arr::get($settings,'styles.tweet_date.color.text_color', '')
                    ),
                    'typography' => array(
                        'font_size' => array(
                            'desktop' => Arr::get($settings,'styles.tweet_date.typography.font_size.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.tweet_date.typography.font_size.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.tweet_date.typography.font_size.mobile', ''),
                        ),
                        'letter_spacing'  => array(
                            'desktop' => Arr::get($settings,'styles.tweet_date.typography.letter_spacing.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.tweet_date.typography.letter_spacing.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.tweet_date.typography.letter_spacing.mobile', ''),
                        ),
                        'line_height'  => array(
                            'desktop' => Arr::get($settings,'styles.tweet_date.typography.line_height.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.tweet_date.typography.line_height.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.tweet_date.typography.line_height.mobile', ''),
                        ),
                        'font_weight'  => Arr::get($settings,'styles.tweet_date.typography.font_weight', ''),
                        'font_style'  => Arr::get($settings,'styles.tweet_date.typography.font_style', ''),
                        'text_transform'  => Arr::get($settings,'styles.tweet_date.typography.text_transform', ''),
                        'text_decoration'  => Arr::get($settings,'styles.tweet_date.typography.text_decoration', ''),
                    ),
                ),
                'tweet_meta' => array(
                    'selector' => $prefix .' .wpsr-twitter-tweet .wpsr-twitter-author-tweet .wpsr-tweet-author-info',
                    'padding' => array(
                        'top' => array(
                            'desktop' => Arr::get($settings,'styles.tweet_meta.padding.top.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.tweet_meta.padding.top.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.tweet_meta.padding.top.mobile', ''),
                        ),
                        'right' => array(
                            'desktop' => Arr::get($settings,'styles.tweet_meta.padding.right.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.tweet_meta.padding.right.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.tweet_meta.padding.right.mobile', ''),
                        ),
                        'bottom' => array(
                            'desktop' => Arr::get($settings,'styles.tweet_meta.padding.bottom.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.tweet_meta.padding.bottom.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.tweet_meta.padding.bottom.mobile', ''),
                        ),
                        'left' => array(
                            'desktop' => Arr::get($settings,'styles.tweet_meta.padding.left.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.tweet_meta.padding.left.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.tweet_meta.padding.left.mobile', ''),
                        ),
                        'linked' => Arr::get($settings,'styles.tweet_meta.padding.linked', false),
                    ),
                ),
                'tweet' => array(
                    'selector' => $prefix .' .wpsr-twitter-tweet .wpsr-twitter-author-tweet .wpsr-tweet-content p',
                    'color'  => array(
                        'text_color' => Arr::get($settings,'styles.tweet.color.text_color', ''),
                    ),
                    'typography' => array(
                        'font_size'  => array(
                            'desktop' => Arr::get($settings,'styles.tweet.typography.font_size.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.tweet.typography.font_size.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.tweet.typography.font_size.mobile', ''),
                        ),
                        'letter_spacing'  => array(
                            'desktop' => Arr::get($settings,'styles.tweet.typography.letter_spacing.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.tweet.typography.letter_spacing.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.tweet.typography.letter_spacing.mobile', ''),
                        ),
                        'line_height'  => array(
                            'desktop' => Arr::get($settings,'styles.tweet.typography.line_height.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.tweet.typography.line_height.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.tweet.typography.line_height.mobile', ''),
                        ),
                        'font_weight'  => Arr::get($settings,'styles.tweet.typography.font_weight', ''),
                        'font_style'  => Arr::get($settings,'styles.tweet.typography.font_style', ''),
                        'text_transform'  => Arr::get($settings,'styles.tweet.typography.text_transform', ''),
                        'text_decoration'  => Arr::get($settings,'styles.tweet.typography.text_decoration', ''),
                    ),
                    'padding' => array(
                        'top' => array(
                            'desktop' => Arr::get($settings,'styles.tweet.padding.top.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.tweet.padding.top.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.tweet.padding.top.mobile', ''),
                        ),
                        'right' => array(
                            'desktop' => Arr::get($settings,'styles.tweet.padding.right.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.tweet.padding.right.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.tweet.padding.right.mobile', ''),
                        ),
                        'bottom' => array(
                            'desktop' => Arr::get($settings,'styles.tweet.padding.bottom.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.tweet.padding.bottom.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.tweet.padding.bottom.mobile', ''),
                        ),
                        'left' => array(
                            'desktop' => Arr::get($settings,'styles.tweet.padding.left.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.tweet.padding.left.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.tweet.padding.left.mobile', ''),
                        ),
                        'linked' => Arr::get($settings,'styles.tweet.padding.linked', false),
                    ),
                ),
                'hashtag' => array(
                    'selector' => $prefix .' .wpsr-twitter-tweet .wpsr-twitter-author-tweet .wpsr-tweet-content p a',
                    'color'  => array(
                        'text_color' => Arr::get($settings,'styles.hashtag.color.text_color', ''),
                    ),
                ),
                'action_wrapper' => array(
                    'selector' => $prefix .' .wpsr-twitter-tweet .wpsr-twitter-author-tweet .wpsr-tweet-actions',
                    'padding' => array(
                        'top' => array(
                            'desktop' => Arr::get($settings,'styles.action_wrapper.padding.top.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.action_wrapper.padding.top.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.action_wrapper.padding.top.mobile', ''),
                        ),
                        'right' => array(
                            'desktop' => Arr::get($settings,'styles.action_wrapper.padding.right.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.action_wrapper.padding.right.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.action_wrapper.padding.right.mobile', ''),
                        ),
                        'bottom' => array(
                            'desktop' => Arr::get($settings,'styles.action_wrapper.padding.bottom.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.action_wrapper.padding.bottom.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.action_wrapper.padding.bottom.mobile', ''),
                        ),
                        'left' => array(
                            'desktop' => Arr::get($settings,'styles.action_wrapper.padding.left.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.action_wrapper.padding.left.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.action_wrapper.padding.left.mobile', ''),
                        ),
                        'linked' => Arr::get($settings,'styles.action_wrapper.padding.linked', false),
                    ),
                ),
                'action_text' => array(
                    'selector' => $prefix .' .wpsr-twitter-tweet .wpsr-twitter-author-tweet .wpsr-tweet-actions a span',
                    'color'  => array(
                        'text_color' => Arr::get($settings,'styles.action_text.color.text_color', ''),
                    ),
                    'typography' => array(
                        'font_size'  => array(
                            'desktop' => Arr::get($settings,'styles.action_text.typography.font_size.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.action_text.typography.font_size.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.action_text.typography.font_size.mobile', ''),
                        ),
                        'letter_spacing'  => array(
                            'desktop' => Arr::get($settings,'styles.action_text.typography.letter_spacing.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.action_text.typography.letter_spacing.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.action_text.typography.letter_spacing.mobile', ''),
                        ),
                        'line_height'  => array(
                            'desktop' => Arr::get($settings,'styles.action_text.typography.line_height.desktop', ''),
                            'tablet' => Arr::get($settings,'styles.action_text.typography.line_height.tablet', ''),
                            'mobile' => Arr::get($settings,'styles.action_text.typography.line_height.mobile', ''),
                        ),
                        'font_weight'  => Arr::get($settings,'styles.action_text.typography.font_weight', ''),
                        'font_style'  => Arr::get($settings,'styles.action_text.typography.font_style', ''),
                        'text_transform'  => Arr::get($settings,'styles.action_text.typography.text_transform', ''),
                        'text_decoration'  => Arr::get($settings,'styles.action_text.typography.text_decoration', ''),
                    ),

                ),
                'action_icon' => array(
                    'selector' => $prefix .' .wpsr-twitter-tweet .wpsr-twitter-author-tweet .wpsr-tweet-actions a svg',
                    'color'  => array(
                        'fill_color' => Arr::get($settings,'styles.action_icon.color.fill_color', ''),
                    ),
                ),
                'item_box' => array(
	                'selector' => $prefix.' .wpsr-twitter-tweet',
	                'color'  => array(
		                'background_color' => Arr::get($settings,'styles.item_box.color.background_color', ''),
	                ),
	                'padding' => array(
		                'top' => array(
			                'desktop' => Arr::get($settings,'styles.item_box.padding.top.desktop', ''),
			                'tablet' => Arr::get($settings,'styles.item_box.padding.top.tablet', ''),
			                'mobile' => Arr::get($settings,'styles.item_box.padding.top.mobile', ''),
		                ),
		                'right' => array(
			                'desktop' => Arr::get($settings,'styles.item_box.padding.right.desktop', ''),
			                'tablet' => Arr::get($settings,'styles.item_box.padding.right.tablet', ''),
			                'mobile' => Arr::get($settings,'styles.item_box.padding.right.mobile', ''),
		                ),
		                'bottom' => array(
			                'desktop' => Arr::get($settings,'styles.item_box.padding.bottom.desktop', ''),
			                'tablet' => Arr::get($settings,'styles.item_box.padding.bottom.tablet', ''),
			                'mobile' => Arr::get($settings,'styles.item_box.padding.bottom.mobile', ''),
		                ),
		                'left' => array(
			                'desktop' => Arr::get($settings,'styles.item_box.padding.left.desktop', ''),
			                'tablet' => Arr::get($settings,'styles.item_box.padding.left.tablet', ''),
			                'mobile' => Arr::get($settings,'styles.item_box.padding.left.mobile', ''),
		                ),
		                'linked' => Arr::get($settings,'styles.item_box.padding.linked', false),
	                ),
	                'border' => array(
		                'top' => array(
			                'desktop' => Arr::get($settings,'styles.item_box.border.top.desktop', ''),
			                'tablet' => Arr::get($settings,'styles.item_box.border.top.tablet', ''),
			                'mobile' => Arr::get($settings,'styles.item_box.border.top.mobile', ''),
		                ),
		                'right' => array(
			                'desktop' => Arr::get($settings,'styles.item_box.border.right.desktop', ''),
			                'tablet' => Arr::get($settings,'styles.item_box.border.right.tablet', ''),
			                'mobile' => Arr::get($settings,'styles.item_box.border.right.mobile', ''),
		                ),
		                'bottom' => array(
			                'desktop' => Arr::get($settings,'styles.item_box.border.bottom.desktop', ''),
			                'tablet' => Arr::get($settings,'styles.item_box.border.bottom.tablet', ''),
			                'mobile' => Arr::get($settings,'styles.item_box.border.bottom.mobile', ''),
		                ),
		                'left' => array(
			                'desktop' => Arr::get($settings,'styles.item_box.border.left.desktop', ''),
			                'tablet' => Arr::get($settings,'styles.item_box.border.left.tablet', ''),
			                'mobile' => Arr::get($settings,'styles.item_box.border.left.mobile', ''),
		                ),
		                'linked' => Arr::get($settings,'styles.item_box.border.linked', false),
		                'border_style' => Arr::get($settings,'styles.item_box.border.border_style', ''),
		                'border_color' => Arr::get($settings,'styles.item_box.border.border_color', ''),
	                ),
                ),
            ),
        ];
    }

    public function formatOldFeed($feed)
    {
        $currentFeed = [];

        $currentFeed['id'] = Arr::get($feed, 'id_str');
        $currentFeed['created_at'] = Arr::get($feed, 'created_at');
        if(Arr::get($feed, 'text')) {
            $currentFeed['text'] = Arr::get($feed, 'text');
        }

        if(Arr::get($feed, 'full_text')) {
            $currentFeed['text'] = Arr::get($feed, 'full_text');
        }

        $user = Arr::get($feed, 'user');
        $formattedUser = $this->formatHeader($user);
        $currentFeed['user'] = $formattedUser;

        $currentFeed['statistics'] = [
            'like_count'        => Arr::get($feed, 'favorite_count'),
            'retweet_count'     => Arr::get($feed, 'retweet_count'),
        ];

        $allMedia = Arr::get($feed, 'extended_entities.media', []);

        $newMedia = [];
        foreach ($allMedia as $media) {
            $newMedia[] = [
                'type'  => Arr::get($media, 'type'),
                'url'   => Arr::get($media, 'media_url_https'),
                'preview_image_url' => Arr::get($media, 'media_url_https'),
                'variants'  => Arr::get($media, 'video_info.variants', []),
            ];
        }

        $currentFeed['media'] = $newMedia;

        $currentFeed['entities']['urls']     = $this->extendUrlEntities($feed);
        $currentFeed['entities']['hashtags'] = $this->extendHashtagEntities($feed);
        $currentFeed['entities']['mentions'] = $this->extendMentionEntities($feed);
        $currentFeed['entities']['medias']   = $this->extendMediaEntities($feed);

        return $currentFeed;
    }

    public function extendMediaEntities($feed)
    {
        $allMedia = Arr::get($feed, 'extended_entities.media', []);

//        if(!empty($allMedia)) {
//            error_log(print_r($allMedia, true));
//        }

        $medias = [];
        foreach($allMedia as $media) {
            $medias[] = [
                'display_url' =>  Arr::get($media, 'display_url', ''),
                'url'   => Arr::get($media, 'url', '')
            ];
        }

        return $medias;
    }

    public function extendMentionEntities($feed)
    {
        $allMentions = Arr::get($feed, 'entities.user_mentions', []);

//        if(!empty($allMentions)) {
//            error_log(print_r($allMentions, true));
//        }

        $mentions = [];
        foreach ($allMentions as $mention) {
            $mentions[] = [
                'start'     => Arr::get($mention, 'indices.0', ''),
                'end'       => Arr::get($mention, 'indices.1', ''),
                'username'  => Arr::get($mention, 'screen_name', '')
            ];
        }

        return $mentions;
    }

    public function extendHashtagEntities($feed)
    {
        $allHashTags = Arr::get($feed, 'entities.hashtags', []);

        $hashTags = [];
        foreach ($allHashTags as $hashTag) {
            $hashTags[] = [
                'start' => Arr::get($hashTag, 'indices.0', ''),
                'end' => Arr::get($hashTag, 'indices.1', ''),
                'tag' => Arr::get($hashTag, 'text', '')
            ];
        }

        return $hashTags;
    }

    public function extendUrlEntities($feed)
    {
        $urls = [];
        $allUrls = Arr::get($feed, 'entities.urls', []);
        foreach($allUrls as $url) {
            $urls[] = [
                'start' => Arr::get($url, 'indices.0', ''),
                'end' => Arr::get($url, 'indices.1', ''),
                'expanded_url' => Arr::get($url, 'expanded_url', ''),
                'display_url'   => Arr::get($url, 'display_url', ''),
                'url'   => Arr::get($url, 'url', '')
            ];
        }

        return $urls;
    }

    public function formatAllOldFeeds($tweetData = [])
    {
        $formattedFeeds = [];
        foreach ($tweetData as $index => $feed) {
            $currentTweet = $this->formatOldFeed($feed);

            //look out quoted
            $extraTweets = [];
            if(Arr::get($feed, 'quoted_status')) {
                $extraTweet = $this->formatOldFeed($feed['quoted_status']);
                $extraTweet['type'] = 'quoted';
                array_push($extraTweets, $extraTweet);
            }

            //look out retweeted
            if(Arr::get($feed, 'retweeted')) {
                $retweet = Arr::get($feed, 'retweeted_status', []);

                if(!empty($retweet)) {
                    $retweet = $this->formatOldFeed($retweet);

                    $currentTweet['id'] = Arr::get($retweet, 'id_str');
                    if(Arr::get($retweet, 'text')) {
                        $currentTweet['text'] = Arr::get($retweet, 'text');
                    }

                    $currentTweet['entities'] = Arr::get($retweet, 'entities');
                    $currentTweet['type'] = 'retweeted';

                    $user = Arr::get($retweet, 'user');
                    $formattedUser = $this->formatHeader($user);
                    $currentTweet['user'] = $formattedUser;
                    $currentTweet['retweet_user'] = Arr::get($feed, 'user');
                }
            }

            $currentTweet['extra_tweets'] = $extraTweets;
            $formattedFeeds[$index] = $currentTweet;
        }

        $formattedFeeds['formatted'] = true;
        return $formattedFeeds;
    }

    public function formatNewFeed($tweet, $mediaArr, $referencedTweetsArr, $userArr)
    {
        /* basic tweet structure start */
        $currentTweet = [];
        $tweetId = Arr::get($tweet, 'id');
        $currentTweet['id'] = $tweetId;
        $currentTweet['text'] = Arr::get($tweet, 'text');
        $currentTweet['created_at'] = Arr::get($tweet, 'created_at');
        $currentTweet['entities'] = Arr::get($tweet, 'entities');

        //media for the posts
        $mediaKeys = Arr::get($tweet, 'attachments.media_keys', []);
        $mediaData = [];
        if(!empty($mediaArr) && !empty($mediaKeys)) {
            foreach ($mediaKeys as $mediaKey) {
                if (!empty(Arr::get($mediaArr, $mediaKey))) {
                    $mediaData[] = Arr::get($mediaArr, $mediaKey);
                }
            }
        }

        //author of this tweet
        $userId = Arr::get($tweet, 'author_id');
        $currentTweetUser = Arr::get($userArr, $userId, []);
        $currentTweet['user'] = $currentTweetUser;
        $currentTweet['media']   = $mediaData;

        //statistics for the posts
        $currentTweet['statistics'] = Arr::get($tweet, 'public_metrics', []);
        /* basic tweet structure end */

        //We will have to check reference tweets [for quoted feed]
        $referencedTweets = Arr::get($tweet, 'referenced_tweets', []);
        $tweets = [];
        foreach ($referencedTweets as $referencedTweet) {
            $type = Arr::get($referencedTweet, 'type');
            $id = Arr::get($referencedTweet, 'id');
            if (!empty(Arr::get($referencedTweetsArr, $id))) {
                $twt = Arr::get($referencedTweetsArr, $id);
                $userId = Arr::get($twt, 'author_id');
                $user = Arr::get($userArr, $userId, []);

                if($type == 'retweeted') {
                    $currentTweet['text'] = Arr::get($referencedTweetsArr, $id . '.text');
                    $currentTweet['entities'] = Arr::get($referencedTweetsArr, $id . '.entities');
                    $currentTweet['type'] = $type;
                    $currentTweet['user'] = $user;
                    $currentTweet['retweet_user'] = $currentTweetUser;
                    continue;
                }

                $twt['user'] = $user;
                $twt['type'] = $type;
                $tweets[$id] = $twt;
            }
        }

        $currentTweet['extra_tweets'] = $tweets;
        //if tweet is retweet we have to format it differently

        return $currentTweet;
    }

    public function formatAllNewFeeds($tweetsData)
    {
        $mediaArr = [];
        $medias = Arr::get($tweetsData, 'includes.media', []);
        if(!empty($medias)) {
            foreach ($medias as $media) {
                $media_key = Arr::get($media, 'media_key');
                $mediaArr[$media_key] = $media;
            }
        }

        $referencedTweetsArr = [];
        $referencedTweets = Arr::get($tweetsData, 'includes.tweets', []);
        if(!empty($referencedTweets)) {
            foreach ($referencedTweets as $referencedTweet) {
                $id = Arr::get($referencedTweet, 'id');
                $referencedTweetsArr[$id] = $referencedTweet;
            }
        }

        $userArr = [];
        $users = Arr::get($tweetsData, 'includes.users', []);
        if(!empty($users)) {
            foreach ($users as $user) {
                $id = Arr::get($user, 'id');
                $userArr[$id] = $user;
            }
        }

        $tweets = Arr::get($tweetsData, 'data', []);
        $formattedTweets = [];
        foreach ($tweets as $tweet) {
            $formattedTweets[] = $this->formatNewFeed($tweet, $mediaArr, $referencedTweetsArr, $userArr);
        }

        $formattedTweets['formatted'] = true;
        return $formattedTweets;
    }

    public function formatHeader($data)
    {
        $formattedData = [];
        if(Arr::get($data, 'name')) {
            $formattedData['name'] = Arr::get($data, 'name');
        }

        if(Arr::get($data, 'screen_name')) {
            $formattedData['username'] = Arr::get($data, 'screen_name');
        }

        if(Arr::get($data, 'username')) {
            $formattedData['username'] = Arr::get($data, 'username');
        }

        if(Arr::get($data, 'statuses_count')) {
            $formattedData['public_metrics']['tweet_count'] = Arr::get($data, 'statuses_count');
        }

        if(Arr::get($data, 'friends_count')) {
            $formattedData['public_metrics']['following_count'] = Arr::get($data, 'friends_count');
        }

        if(Arr::get($data, 'followers_count')) {
            $formattedData['public_metrics']['followers_count'] = Arr::get($data, 'followers_count');
        }

        if(Arr::get($data, 'public_metrics')) {
            $formattedData['public_metrics'] = Arr::get($data, 'public_metrics');
        }

//        if(Arr::get($data, 'favourites_count')) {
//            $formattedData['favourites_count'] = Arr::get($data, 'favourites_count');
//        }

        if(Arr::get($data, 'location')) {
            $formattedData['location'] = Arr::get($data, 'location');
        }

        if(Arr::get($data, 'description')) {
            $formattedData['description'] = Arr::get($data, 'description');
        }

        if(Arr::get($data, 'id')) {
            $formattedData['id'] = Arr::get($data, 'id');
        }

        if(Arr::get($data, 'profile_banner_url')) {
            $formattedData['profile_banner_url'] = Arr::get($data, 'profile_banner_url');
        }

        if(Arr::get($data, 'profile_image_url')) {
            $formattedData['profile_image_url'] = Arr::get($data, 'profile_image_url');
        }

        if(Arr::get($data, 'verified')) {
            $formattedData['verified'] = Arr::get($data, 'verified');
        }

        $formattedData['formatted'] = true;
        return $formattedData;
    }
}
