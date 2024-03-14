<?php

/**
 * 基础设置
 */
class Article_With_Pictures_Page
{
    // 初始化页面
    public static function init_page()
    {
        // 注册一个新页面
        register_setting('article_with_pictures_page', 'article_with_pictures_options');

        add_settings_section(
            'article_with_pictures_page_section',
            null,
            null,
            'article_with_pictures_page'
        );

        add_settings_field(
            'type',
            '文章配图',
            array('Article_With_Pictures_Plugin', 'field_callback'),
            'article_with_pictures_page',
            'article_with_pictures_page_section',
            array(
                'label_for' => 'type',
                'form_type' => 'select',
                'form_data' => array(
                    array(
                        'title' => '关闭',
                        'value' => '0'
                    ),
                    array(
                        'title' => '背景颜色',
                        'value' => '1'
                    )
                ),
                'form_desc' => '当文章没有缩略图时，自动生成一张缩略图'
            )
        );

        add_settings_field(
            'extract_content_image',
            '提取文章内容图片作为缩略图',
            array('Article_With_Pictures_Plugin', 'field_callback'),
            'article_with_pictures_page',
            'article_with_pictures_page_section',
            array(
                'label_for' => 'extract_content_image',
                'form_type' => 'select',
                'form_data' => array(
                    array(
                        'title' => '否',
                        'value' => '0'
                    ),
                    array(
                        'title' => '是',
                        'value' => '1'
                    )
                ),
                'form_desc' => '当文章没有缩略图时，尝试提取文章内容里的图片作为缩略图'
            )
        );

        // 添加指定分类使用
        $terms = get_terms(array(
            'taxonomy' => 'category',
            'number' => 20
        ));
        if (!empty($terms)) {
            $form_data = array();
            foreach ($terms as $term) {
                $form_data[] = array(
                    'title' => $term->name,
                    'value' => $term->term_id
                );
            }
            add_settings_field(
                'term_ids',
                '分类',
                array('Article_With_Pictures_Plugin', 'field_callback'),
                'article_with_pictures_page',
                'article_with_pictures_page_section',
                array(
                    'label_for' => 'term_ids',
                    'form_type' => 'checkbox',
                    'form_data' => $form_data,
                    'form_desc' => '指定分类下的文章使用文章配图。如果不设置，则全部文章都使用文章配图'
                )
            );
        }

        add_settings_field(
            'list_image_width',
            '缩略图宽度',
            array('Article_With_Pictures_Plugin', 'field_callback'),
            'article_with_pictures_page',
            'article_with_pictures_page_section',
            array(
                'label_for' => 'list_image_width',
                'form_type' => 'input',
                'type' => 'number',
                'form_desc' => '文章列表页面中的缩略图宽度'
            )
        );

        add_settings_field(
            'list_image_height',
            '缩略图高度',
            array('Article_With_Pictures_Plugin', 'field_callback'),
            'article_with_pictures_page',
            'article_with_pictures_page_section',
            array(
                'label_for' => 'list_image_height',
                'form_type' => 'input',
                'type' => 'number',
                'form_desc' => '文章列表页面中的缩略图高度'
            )
        );

        add_settings_field(
            'image_name',
            '缩略图文件名称',
            array('Article_With_Pictures_Plugin', 'field_callback'),
            'article_with_pictures_page',
            'article_with_pictures_page_section',
            array(
                'label_for' => 'image_name',
                'form_type' => 'select',
                'form_data' => array(
                    array(
                        'title' => '32位加密字符串',
                        'value' => '0'
                    ),
                    array(
                        'title' => '文章ID',
                        'value' => '1'
                    ),
                    array(
                        'title' => '文章标题',
                        'value' => '2'
                    )
                )
            )
        );

        $form_data = array(
            array(
                'title' => '自动',
                'value' => '0'
            )
        );

        $support_images = Article_With_Pictures_Plugin::get_support_images();
        foreach ($support_images as $support_image) {
            $form_data[] = array(
                'title' => $support_image,
                'value' => $support_image
            );
        }

        add_settings_field(
            'image_ext',
            '缩略图文件格式',
            array('Article_With_Pictures_Plugin', 'field_callback'),
            'article_with_pictures_page',
            'article_with_pictures_page_section',
            array(
                'label_for' => 'image_ext',
                'form_type' => 'select',
                'form_data' => $form_data
            )
        );

        add_settings_field(
            'list_image_auto_update',
            '自动更新缩略图',
            array('Article_With_Pictures_Plugin', 'field_callback'),
            'article_with_pictures_page',
            'article_with_pictures_page_section',
            array(
                'label_for' => 'list_image_auto_update',
                'form_type' => 'select',
                'form_data' => array(
                    array(
                        'title' => '是',
                        'value' => '1'
                    ),
                    array(
                        'title' => '否',
                        'value' => '2'
                    )
                ),
                'form_desc' => '当设置变化后，将会自动重新生成缩略图'
            )
        );

        add_settings_field(
            'generate_image_type',
            '主动生成特色图片',
            array('Article_With_Pictures_Plugin', 'field_callback'),
            'article_with_pictures_page',
            'article_with_pictures_page_section',
            array(
                'label_for' => 'generate_image_type',
                'form_type' => 'select',
                'form_data' => array(
                    array(
                        'title' => '是',
                        'value' => '1'
                    ),
                    array(
                        'title' => '否',
                        'value' => '2'
                    )
                ),
                'form_desc' => '如果主题无法显示缩略图，尝试将此设置为是'
            )
        );

        add_settings_field(
            'content_image_type',
            '文章内容显示缩略图',
            array('Article_With_Pictures_Plugin', 'field_callback'),
            'article_with_pictures_page',
            'article_with_pictures_page_section',
            array(
                'label_for' => 'content_image_type',
                'form_type' => 'select',
                'form_data' => array(
                    array(
                        'title' => '不显示',
                        'value' => '0'
                    ),
                    array(
                        'title' => '开头',
                        'value' => '1'
                    ),
                    array(
                        'title' => '中间',
                        'value' => '2'
                    ),
                    array(
                        'title' => '结尾',
                        'value' => '3'
                    ),
                    array(
                        'title' => '随机',
                        'value' => '4'
                    )
                ),
                'form_desc' => '当文章内容没有图片时，才会在文章内容里显示缩略图'
            )
        );

        add_settings_field(
            'content_image_html',
            '文章内容缩略图代码模板',
            array('Article_With_Pictures_Plugin', 'field_callback'),
            'article_with_pictures_page',
            'article_with_pictures_page_section',
            array(
                'label_for' => 'content_image_html',
                'form_type' => 'textarea',
                'form_desc' => '可使用{img}替换缩略图img标签完整内容，默认为：<figure class="wp-block-image size-full aligncenter">{img}</figure>'
            )
        );

        add_settings_field(
            'list_image_auto_save',
            '将缩略图保存至文章内容',
            array('Article_With_Pictures_Plugin', 'field_callback'),
            'article_with_pictures_page',
            'article_with_pictures_page_section',
            array(
                'label_for' => 'list_image_auto_save',
                'form_type' => 'select',
                'form_data' => array(
                    array(
                        'title' => '是',
                        'value' => '1'
                    ),
                    array(
                        'title' => '否',
                        'value' => '2'
                    )
                ),
                'form_desc' => '插件生成的缩略图将会更新到文章内容里永久保存。调试阶段请设置为否'
            )
        );

        add_settings_field(
            'list_image_background_text',
            '添加缩略图文字',
            array('Article_With_Pictures_Plugin', 'field_callback'),
            'article_with_pictures_page',
            'article_with_pictures_page_section',
            array(
                'label_for' => 'list_image_background_text',
                'form_type' => 'select',
                'form_data' => array(
                    array(
                        'title' => '是',
                        'value' => '1'
                    ),
                    array(
                        'title' => '否',
                        'value' => '2'
                    )
                ),
                'form_desc' => '在缩略图上添加文章标题文字'
            )
        );

        // 查询字体文件
        $form_data = array();
        $form_data[] = array(
            'title' => '选择字体文件',
            'value' => '0'
        );
        $font_dir = ARTICLE_WITH_PICTURES_PLUGIN_DIR . 'fonts';
        $files = scandir($font_dir);
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            if (preg_match('/\.ttf$/i', $file)) {
                $form_data[] = array(
                    'title' => $file,
                    'value' => $file
                );
            }
        }
        $form_desc = '仅支持.ttf格式的字体文件，且字体文件名称不能含有特殊符号。尽量使用拼音命名，例如：ziti1.ttf';
        if (count($form_data) === 1) {
            $fonts_dir = str_replace('\\', '/', ARTICLE_WITH_PICTURES_PLUGIN_DIR) . 'fonts';
            $form_desc = '未上传中文字体文件（.ttf格式）至指定文件夹下：' . $fonts_dir;
        }
        add_settings_field(
            'list_image_text_font',
            '缩略图文字字体',
            array('Article_With_Pictures_Plugin', 'field_callback'),
            'article_with_pictures_page',
            'article_with_pictures_page_section',
            array(
                'label_for' => 'list_image_text_font',
                'form_type' => 'select',
                'form_data' => $form_data,
                'form_desc' => $form_desc
            )
        );

        add_settings_field(
            'list_image_text_color',
            '缩略图文字颜色',
            array('Article_With_Pictures_Plugin', 'field_callback'),
            'article_with_pictures_page',
            'article_with_pictures_page_section',
            array(
                'label_for' => 'list_image_text_color',
                'form_type' => 'input',
                'type' => 'text',
                'form_desc' => '缩略图文字颜色，例如：#000000'
            )
        );

        add_settings_field(
            'list_image_text_size',
            '缩略图文字大小',
            array('Article_With_Pictures_Plugin', 'field_callback'),
            'article_with_pictures_page',
            'article_with_pictures_page_section',
            array(
                'label_for' => 'list_image_text_size',
                'form_type' => 'input',
                'type' => 'number',
                'form_desc' => '缩略图文字大小，例如：16'
            )
        );

        add_settings_field(
            'list_image_text_multiline',
            '缩略图文字单行显示',
            array('Article_With_Pictures_Plugin', 'field_callback'),
            'article_with_pictures_page',
            'article_with_pictures_page_section',
            array(
                'label_for' => 'list_image_text_multiline',
                'form_type' => 'select',
                'form_data' => array(
                    array(
                        'title' => '是',
                        'value' => '1'
                    ),
                    array(
                        'title' => '否',
                        'value' => '2'
                    )
                ),
                'form_desc' => '文字是否在缩略图上显示一行，如果设置为否，则会在缩略图上显示多行文字'
            )
        );

        add_settings_field(
            'list_image_limit_line',
            '缩略图文字行数限制',
            array('Article_With_Pictures_Plugin', 'field_callback'),
            'article_with_pictures_page',
            'article_with_pictures_page_section',
            array(
                'label_for' => 'list_image_limit_line',
                'form_type' => 'input',
                'type' => 'number',
                'form_desc' => '多行文字显示时，可以限制行数，例如：3'
            )
        );

        add_settings_field(
            'list_image_text_num',
            '缩略图每行文字个数限制',
            array('Article_With_Pictures_Plugin', 'field_callback'),
            'article_with_pictures_page',
            'article_with_pictures_page_section',
            array(
                'label_for' => 'list_image_text_num',
                'form_type' => 'input',
                'type' => 'number',
                'form_desc' => '缩略图上每行最多显示的文字个数，超过设置则会截取。默认每行文字个数减3'
            )
        );

        add_settings_field(
            'list_image_default_background_color',
            '缩略图默认背景颜色',
            array('Article_With_Pictures_Plugin', 'field_callback'),
            'article_with_pictures_page',
            'article_with_pictures_page_section',
            array(
                'label_for' => 'list_image_default_background_color',
                'form_type' => 'input',
                'type' => 'text',
                'form_desc' => '缩略图默认背景颜色，例如：#dda0dd'
            )
        );

        // 背景颜色
        add_settings_section(
            'background_color_section',
            '使用背景颜色生成缩略图',
            array('Article_With_Pictures_Page', 'background_color_text'),
            'article_with_pictures_page'
        );

        add_settings_field(
            'background_colors',
            '背景颜色|文字颜色',
            array('Article_With_Pictures_Plugin', 'field_callback'),
            'article_with_pictures_page',
            'background_color_section',
            array(
                'label_for' => 'background_colors',
                'form_type' => 'textarea',
                'form_desc' => '每行一个设置，每个设置可以使用|分隔背景颜色和文字颜色。如果设置只包含一种颜色，则会使用默认的文字颜色'
            )
        );
    }

    /**
     * 使用背景颜色生成缩略图说明
     * @return void
     */
    public static function background_color_text()
    {
        ?>
        可以设置多组背景颜色来生成缩略图
        <?php
    }
}