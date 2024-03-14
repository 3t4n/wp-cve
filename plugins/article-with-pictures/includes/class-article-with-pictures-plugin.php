<?php

/**
 * 基础类
 */
class Article_With_Pictures_Plugin
{
    // 启用插件
    public static function plugin_activation()
    {
        // 创建默认配置
        add_option('article_with_pictures_options', array(
            'list_image_text_color' => '#000000',
            'list_image_default_background_color' => '#dda0dd',
            'list_image_text_size' => 16,
            'list_image_text_multiline' => 1,
            'list_image_auto_update' => 1,
            'list_image_background_text' => 2,
            'generate_image_type' => 2,
            'list_image_auto_save' => 2,
            'type' => 0,
            'list_image_width' => 480,
            'list_image_height' => 300,
            'content_image_type' => 0,
            'background_colors' => '#5b8982|#ffffff' . PHP_EOL . '#45545f|#cec6b6' . PHP_EOL . '#d47655|#e1f8e1' . PHP_EOL . '#7379b0|#c6edec'
        ));
    }

    // 删除插件执行的代码
    public static function plugin_uninstall()
    {
        // 删除元数据
        delete_metadata('post', 0, 'article_with_pictures_attach_id', '', true);
        delete_metadata('post', 0, 'article_with_pictures_attach_id_key', '', true);
        // 删除配置
        delete_option('article_with_pictures_options');
    }

    /**
     * 表单输入框回调
     * @param array $args 这数据就是add_settings_field方法中第6个参数（$args）的数据
     */
    public static function field_callback($args)
    {
        // 表单的id或name字段
        $id = $args['label_for'];
        // 表单的名称
        $input_name = 'article_with_pictures_options[' . $id . ']';
        // 获取表单选项中的值
        global $article_with_pictures_options;
        // 表单的值
        $input_value = isset($article_with_pictures_options[$id]) ? $article_with_pictures_options[$id] : '';
        // 表单的类型
        $form_type = isset($args['form_type']) ? $args['form_type'] : 'input';
        // 输入表单说明
        $form_desc = isset($args['form_desc']) ? $args['form_desc'] : '';
        // 输入表单type
        $type = isset($args['type']) ? $args['type'] : 'text';
        // 输入表单placeholder
        $form_placeholder = isset($args['form_placeholder']) ? $args['form_placeholder'] : '';
        // 下拉框等选项值
        $form_data = isset($args['form_data']) ? $args['form_data'] : array();
        // 扩展form表单属性
        $form_extend = isset($args['form_extend']) ? $args['form_extend'] : array();
        switch ($form_type) {
            case 'input':
                self::generate_input(
                    array_merge(
                        array(
                            'id' => $id,
                            'type' => $type,
                            'placeholder' => $form_placeholder,
                            'name' => $input_name,
                            'value' => $input_value,
                            'class' => 'regular-text',
                        ),
                        $form_extend
                    ));
                break;
            case 'select':
                self::generate_select(
                    array_merge(
                        array(
                            'id' => $id,
                            'placeholder' => $form_placeholder,
                            'name' => $input_name
                        ),
                        $form_extend
                    ),
                    $form_data,
                    $input_value
                );
                break;
            case 'checkbox':
                self::generate_checkbox(
                    array_merge(
                        array(
                            'name' => $input_name . '[]'
                        ),
                        $form_extend
                    ),
                    $form_data,
                    $input_value
                );
                break;
            case 'textarea':
                self::generate_textarea(
                    array_merge(
                        array(
                            'id' => $id,
                            'placeholder' => $form_placeholder,
                            'name' => $input_name,
                            'class' => 'large-text code',
                            'rows' => 5,
                        ),
                        $form_extend
                    ),
                    $input_value
                );
                break;
        }
        if (!empty($form_desc)) {
            ?>
            <p class="description"><?php echo esc_html($form_desc); ?></p>
            <?php
        }
    }

    /**
     * 生成textarea表单
     * @param array $form_data 标签上的属性数组
     * @param string $value 默认值
     * @return void
     */
    public static function generate_textarea($form_data, $value = '')
    {
        ?><textarea <?php
        foreach ($form_data as $k => $v) {
            echo esc_attr($k); ?>="<?php echo esc_attr($v); ?>" <?php
        } ?>><?php echo esc_textarea($value); ?></textarea>
        <?php
    }

    /**
     * 生成checkbox表单
     * @param array $form_data 标签上的属性数组
     * @param array $checkboxs 下拉列表数据
     * @param string|array $value 选中值，单个选中字符串，多个选中数组
     * @return void
     */
    public static function generate_checkbox($form_data, $checkboxs, $value = '')
    {
        ?>
        <fieldset><p>
                <?php
                $len = count($checkboxs);
                foreach ($checkboxs as $k => $checkbox) {
                    $checked = '';
                    if (!empty($value)) {
                        if (is_array($value)) {
                            if (in_array($checkbox['value'], $value)) {
                                $checked = 'checked';
                            }
                        } else {
                            if ($checkbox['value'] == $value) {
                                $checked = 'checked';
                            }
                        }
                    }
                    ?>
                    <label>
                        <input type="checkbox" <?php checked($checked, 'checked'); ?><?php
                        foreach ($form_data as $k2 => $v2) {
                            echo esc_attr($k2); ?>="<?php echo esc_attr($v2); ?>" <?php
                        } ?> value="<?php echo esc_attr($checkbox['value']); ?>"
                        ><?php echo esc_html($checkbox['title']); ?>
                    </label>
                    <?php
                    if ($k < ($len - 1)) {
                        ?>
                        <br>
                        <?php
                    }
                }
                ?>
            </p></fieldset>
        <?php
    }

    /**
     * 生成input表单
     * @param array $form_data 标签上的属性数组
     * @return void
     */
    public static function generate_input($form_data)
    {
        ?><input <?php
        foreach ($form_data as $k => $v) {
            echo esc_attr($k); ?>="<?php echo esc_attr($v); ?>" <?php
        } ?>><?php
    }

    /**
     * 生成select表单
     * @param array $form_data 标签上的属性数组
     * @param array $selects 下拉列表数据
     * @param string|array $value 选中值，单个选中字符串，多个选中数组
     * @return void
     */
    public static function generate_select($form_data, $selects, $value = '')
    {
        ?><select <?php
        foreach ($form_data as $k => $v) {
            echo esc_attr($k); ?>="<?php echo esc_attr($v); ?>" <?php
        } ?>><?php
        foreach ($selects as $select) {
            $selected = '';
            if (!empty($value)) {
                if (is_array($value)) {
                    if (in_array($select['value'], $value)) {
                        $selected = 'selected';
                    }
                } else {
                    if ($select['value'] == $value) {
                        $selected = 'selected';
                    }
                }
            }
            ?>
            <option <?php selected($selected, 'selected'); ?>
                    value="<?php echo esc_attr($select['value']); ?>"><?php echo esc_html($select['title']); ?></option>
            <?php
        }
        ?>
        </select>
        <?php
    }

    // 初始化
    public static function admin_init()
    {
        // 注册设置页面
        Article_With_Pictures_Page::init_page();
    }

    // 添加菜单
    public static function admin_menu()
    {
        // 设置页面
        add_options_page(
            '文章配图',
            '文章配图',
            'manage_options',
            'article-with-pictures-setting',
            array('Article_With_Pictures_Plugin', 'show_page')
        );
    }

    // 显示设置页面
    public static function show_page()
    {
        // 检查用户权限
        if (!current_user_can('manage_options')) {
            return;
        }
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form action="options.php" method="post" enctype="multipart/form-data">
                <?php
                // 输出表单
                settings_fields('article_with_pictures_page');
                do_settings_sections('article_with_pictures_page');
                // 输出保存设置按钮
                submit_button('保存更改');
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * 添加设置链接
     * @param array $links
     * @return array
     */
    public static function link_setting($links)
    {
        $business_link = '<a href="https://www.ggdoc.cn/plugin/3.html" target="_blank">商业版</a>';
        array_unshift($links, $business_link);

        $settings_link = '<a href="options-general.php?page=article-with-pictures-setting">设置</a>';
        array_unshift($links, $settings_link);

        return $links;
    }

    /**
     * 列表页面缩略图
     * @param string $html
     * @param int $post_id
     * @param int $post_thumbnail_id
     * @param int $size
     * @param array|string $attr
     * @return string
     */
    public static function post_thumbnail_html($html, $post_id, $post_thumbnail_id, $size, $attr)
    {
        // 检查是否允许使用文章配图
        if (!self::check_term_ids()) {
            return $html;
        }
        // 不自动更新缩略图
        global $article_with_pictures_options;
        if (!empty($html) && !empty($article_with_pictures_options['list_image_auto_update']) && 2 == $article_with_pictures_options['list_image_auto_update']) {
            return $html;
        }
        if ('post' === get_post_type($post_id)) {
            $alt_text = get_the_title($post_id);
            if (!empty($alt_text)) {
                $alt_text = trim($alt_text);
            }
            if (!empty($html)) {
                if (!empty($post_thumbnail_id)) {
                    $article_with_pictures_attach_id = get_post_meta($post_id, 'article_with_pictures_attach_id', true);
                    // 非插件生成的缩略图
                    if (empty($article_with_pictures_attach_id)) {
                        return $html;
                    }
                    // 插件生成的跟目前的缩略图不一致
                    if ($article_with_pictures_attach_id != $post_thumbnail_id) {
                        return $html;
                    }
                    $article_with_pictures_attach_id_key = get_post_meta($post_id, 'article_with_pictures_attach_id_key', true);
                    // 插件生成的缩略图，但是配置没有改变
                    if (!empty($article_with_pictures_attach_id_key) && self::get_image_key($alt_text) === $article_with_pictures_attach_id_key) {
                        return $html;
                    }
                    wp_delete_attachment($post_thumbnail_id, true);
                    delete_post_thumbnail($post_id);
                } else {
                    return $html;
                }
            }
            $attachment = self::generate_thumbnail($post_id, $alt_text);
            if (empty($attachment['guid'])) {
                return $html;
            }
            // 替换缩略图地址
            if (!empty($html)) {
                return preg_replace('/src=[\'"][^\'"]+[\'"]/i', 'src="' . esc_url($attachment['guid']) . '"', $html);
            }
            // 返回插件生成的缩略图HTML
            $class = 'img-featured img-responsive';
            if (!empty($attr['class'])) {
                $class = $attr['class'];
            }
            $img_html = '<img class="' . $class . ' wp-post-image" src="' . esc_url($attachment['guid']) . '" alt="' . esc_attr($alt_text) . '"';
            if (strpos($class, 'retina') !== false) {
                $img_html .= ' data-src="' . esc_url($attachment['guid']) . '"';
            }
            if (!empty($attr['loading'])) {
                $img_html .= ' loading="lazy"';
            }
            $img_html .= ' width="' . $article_with_pictures_options['list_image_width'] . '" height="' . $article_with_pictures_options['list_image_height'] . '"';
            return $img_html . ' />';
        }
        return $html;
    }

    /**
     * 如果文章没有缩略图，则生成缩略图
     * @return void
     */
    public static function the_post()
    {
        global $post;
        if (!has_post_thumbnail($post->ID) && $post->post_status === 'publish' && self::check_term_ids()) {
            // 没有缩略图，生成缩略图
            self::generate_thumbnail($post->ID, $post->post_title);
        }
    }

    /**
     * 文章内容添加缩略图
     * @param $content
     * @return string
     */
    public static function the_content($content)
    {
        if (!empty($content) && is_single() && get_post_type() === 'post' && self::check_term_ids()) {
            if (!preg_match('/<img/i', $content)) {
                global $article_with_pictures_options;
                $post = get_post();
                if (!empty($post)) {
                    $image_html = get_the_post_thumbnail($post);
                    if (!empty($image_html) && !empty($article_with_pictures_options['content_image_type'])) {
                        if (empty($article_with_pictures_options['content_image_html'])) {
                            $image_html = '<figure class="wp-block-image size-full aligncenter">' . $image_html . '</figure>';
                        } else {
                            $image_html = str_replace('{img}', $image_html, $article_with_pictures_options['content_image_html']);
                        }
                        switch ($article_with_pictures_options['content_image_type']) {
                            case 1:
                                $content = $image_html . $content;
                                break;
                            case 3:
                                $content = $content . $image_html;
                                break;
                            default:
                                $image_start_num = 0;
                                if (preg_match_all('/<p[^>]*>/i', $content, $mat)) {
                                    $p_nums = count($mat[0]);
                                    if ($article_with_pictures_options['content_image_type'] == 2) {
                                        $image_start_num = intval(($p_nums - 1) / 2);
                                    } else {
                                        $image_start_num = mt_rand(0, $p_nums - 1);
                                    }
                                }
                                $content = preg_replace_callback('/<p[^>]*>(.*)<\/p>/i', function ($matches) use ($image_start_num, $image_html) {
                                    static $i = -1;
                                    $i++;
                                    if ($i == $image_start_num) {
                                        return $matches[0] . $image_html;
                                    }
                                    return $matches[0];
                                }, $content);
                        }
                        // 将缩略图内容永久保存到文章
                        if (!empty($article_with_pictures_options['list_image_auto_save']) && 1 == $article_with_pictures_options['list_image_auto_save']) {
                            wp_update_post(array(
                                'ID' => $post->ID,
                                'post_content' => $content,
                            ));
                        }
                    }
                }
            }
        }
        return $content;
    }

    /**
     * 从文章内容中提起图片作为缩略图
     * @param int $post_id 文章ID
     * @param string $post_title 文章标题
     * @return array|bool
     */
    public static function generate_thumbnail_content($post_id, $post_title)
    {
        $args = array(
            'post_type' => 'attachment',
            'post_mime_type' => 'image',
            'numberposts' => -1,
            'post_status' => null,
            'post_parent' => $post_id
        );
        $attached_images = get_posts($args);
        if (!empty($attached_images)) {
            // 从媒体库获取
            foreach ($attached_images as $attached_image) {
                if (set_post_thumbnail($post_id, $attached_image->ID)) {
                    return array(
                        'post_mime_type' => $attached_image->post_mime_type,
                        'post_title' => $attached_image->post_title,
                        'post_content' => '',
                        'post_status' => 'inherit',
                        'guid' => $attached_image->guid
                    );
                }
            }
        } else {
            // 从文章内容中提取
            $content = get_post_field('post_content', $post_id);
            if (empty($content)) {
                return false;
            }
            if (preg_match_all('/<img[^>]*src=[\'"]([^\'"]+)[\'"][^>]*>/iU', $content, $mat)) {
                foreach ($mat[1] as $img_src) {
                    $img_src = strtolower($img_src);
                    if (0 === stripos($img_src, 'data:image')) {
                        continue;
                    }
                    if (0 === stripos($img_src, '//')) {
                        $img_src = 'https:' . $img_src;
                    }
                    if (false === stripos($img_src, 'http')) {
                        $img_src = get_home_url() . '/' . trim($img_src, '/');
                    }
                    $img_src_info = parse_url($img_src);
                    if (empty($img_src_info) || empty($img_src_info['scheme']) || empty($img_src_info['path'])) {
                        continue;
                    }
                    if (!file_exists(ABSPATH . trim($img_src_info['path'], '/'))) {
                        continue;
                    }
                    $ext = pathinfo($img_src_info['path'], PATHINFO_EXTENSION);
                    if (empty($ext)) {
                        continue;
                    }
                    $ext = strtolower($ext);
                    $post_mime_type = 'image/png';
                    if (in_array($ext, array('png', 'bmp', 'jpeg', 'jpg', 'webp'))) {
                        $post_mime_type = 'image/' . $ext;
                    } else if (function_exists('mime_content_type')) {
                        $post_mime_type = mime_content_type(ABSPATH . trim($img_src_info['path'], '/'));
                    }
                    $attachment = array(
                        'post_mime_type' => $post_mime_type,
                        'post_title' => $post_title,
                        'post_content' => '',
                        'post_status' => 'inherit',
                        'guid' => $img_src
                    );
                    $attach_id = wp_insert_attachment($attachment, ABSPATH . trim($img_src_info['path'], '/'), $post_id);
                    require_once(ABSPATH . 'wp-admin/includes/image.php');
                    $attach_data = wp_generate_attachment_metadata($attach_id, $img_src);
                    wp_update_attachment_metadata($attach_id, $attach_data);
                    set_post_thumbnail($post_id, $attach_id);
                    return $attachment;
                }
            }
        }
        return false;
    }

    /**
     * 获取文章对应的唯一文件名
     * @param string $post_title
     * @return string
     */
    public static function get_image_key($post_title)
    {
        global $article_with_pictures_options;
        return md5('文章配图-' . $post_title . '-' . json_encode($article_with_pictures_options));
    }

    /**
     * 添加图片标签
     * @param $attr
     * @param $attachment
     * @return mixed
     */
    public static function wp_get_attachment_image_attributes($attr, $attachment = null)
    {
        if (empty($attr['alt'])) {
            $img_title = esc_attr(trim(strip_tags($attachment->post_title)));
            $attr['alt'] = $img_title;
        }
        return $attr;
    }

    /**
     * 获取支持的图片格式
     * @return array
     */
    public static function get_support_images()
    {
        $images = array();
        if (function_exists('imagepng')) {
            $images[] = 'png';
        }
        if (function_exists('imagebmp')) {
            $images[] = 'bmp';
        }
        if (function_exists('imagejpeg')) {
            $images[] = 'jpeg';
            $images[] = 'jpg';
        }
        if (function_exists('imagewebp')) {
            $images[] = 'webp';
        }
        return $images;
    }

    /**
     * 检查当前文章分类是否允许使用文章配图
     * @return bool
     */
    public static function check_term_ids()
    {
        $check_term_ids = array();
        $categorys = get_the_category();
        if (!empty($categorys)) {
            foreach ($categorys as $category) {
                $check_term_ids[] = $category->term_id;
            }
        }
        if (!empty($check_term_ids)) {
            global $article_with_pictures_options;
            // 启用分类
            $term_ids = array();
            if (!empty($article_with_pictures_options['term_ids'])) {
                $term_ids = $article_with_pictures_options['term_ids'];
            }
            if (!empty($term_ids)) {
                $result = array_intersect($check_term_ids, $term_ids);
                return !empty($result);
            }
        }
        return true;
    }

    /**
     * 生成文章缩略图
     * @param string $post_title 文章标题
     * @param int $post_id 文章ID
     * @return bool|array
     */
    public static function generate_thumbnail($post_id, $post_title)
    {
        if (get_post_type($post_id) !== 'post') {
            return false;
        }
        global $article_with_pictures_options;
        // 提取文章内容图片作为缩略图
        if (!empty($article_with_pictures_options['extract_content_image']) && 1 == $article_with_pictures_options['extract_content_image']) {
            $attachment = self::generate_thumbnail_content($post_id, $post_title);
            if (!empty($attachment)) {
                return $attachment;
            }
        }
        if (empty($article_with_pictures_options['list_image_width'])) {
            return false;
        }
        if (empty($article_with_pictures_options['list_image_height'])) {
            return false;
        }
        $post_title = trim($post_title);
        $api = new Article_With_Pictures_Api($article_with_pictures_options['list_image_width'], $article_with_pictures_options['list_image_height']);
        // 设置图片默认背景颜色
        if (!empty($article_with_pictures_options['list_image_default_background_color'])) {
            $default_background_rgb = $api->getRGB($article_with_pictures_options['list_image_default_background_color']);
            if (!empty($default_background_rgb)) {
                $api->setBackgroundRGB($default_background_rgb);
            }
        }
        // 添加缩略图文字
        if (!empty($article_with_pictures_options['list_image_background_text']) && 1 == $article_with_pictures_options['list_image_background_text']) {
            if (!empty($article_with_pictures_options['list_image_text_font'])) {
                $font_file = ARTICLE_WITH_PICTURES_PLUGIN_DIR . 'fonts/' . $article_with_pictures_options['list_image_text_font'];
                if (file_exists($font_file)) {
                    $api->setFontFile($font_file);
                    $api->setText($post_title);
                    // 每行文字限制
                    if (!empty($article_with_pictures_options['list_image_text_num'])) {
                        $api->setMaxLineTextNum($article_with_pictures_options['list_image_text_num']);
                    } else {
                        $api->setReduceLineTextNum(3);
                    }
                    // 行数限制
                    if (!empty($article_with_pictures_options['list_image_limit_line'])) {
                        $api->setMaxLineNum($article_with_pictures_options['list_image_limit_line']);
                    }
                    // 文字颜色
                    if (!empty($article_with_pictures_options['list_image_text_color'])) {
                        $text_rgb = $api->getRGB($article_with_pictures_options['list_image_text_color']);
                        if (empty($text_rgb)) {
                            return false;
                        }
                        $api->setTextRGB($text_rgb);
                    }
                    // 文字大小
                    if (!empty($article_with_pictures_options['list_image_text_size'])) {
                        $api->setFontSize($article_with_pictures_options['list_image_text_size']);
                    }
                    // 单行还是多行
                    if (!empty($article_with_pictures_options['list_image_text_multiline'])) {
                        $api->setIsMultiLine($article_with_pictures_options['list_image_text_multiline'] == '2');
                    }
                }
            }
        }
        // 背景颜色
        if (empty($article_with_pictures_options['background_colors'])) {
            return false;
        }
        // 换行转为数组
        $background_colors = explode(PHP_EOL, $article_with_pictures_options['background_colors']);
        if (empty($background_colors)) {
            return false;
        }
        // 获取有效的配置
        foreach ($background_colors as $k => $background_color) {
            if (empty($background_color) || !preg_match('/#[a-f0-9]{6}/i', $background_color)) {
                unset($background_colors[$k]);
            }
        }
        if (empty($background_colors)) {
            return false;
        }
        // 打乱数组
        shuffle($background_colors);
        // 取第一个值
        $tmp = explode('|', $background_colors[0]);
        $background_rgb = $api->getRGB(trim($tmp[0]));
        if (empty($background_rgb)) {
            return false;
        }
        $api->setBackgroundRGB($background_rgb);
        if (2 == count($tmp)) {
            $text_rgb = $api->getRGB(trim($tmp[1]));
            if (empty($text_rgb)) {
                return false;
            }
            $api->setTextRGB($text_rgb);
        }
        $upload_dir = wp_upload_dir();
        // 文件名
        if (!empty($article_with_pictures_options['image_name'])) {
            if (1 == $article_with_pictures_options['image_name']) {
                $img_filename = $post_id;
            } else if (2 == $article_with_pictures_options['image_name']) {
                $img_filename = sanitize_file_name($post_title);
            } else {
                $img_filename = self::get_image_key($post_title);
            }
        } else {
            $img_filename = self::get_image_key($post_title);
        }
        // 文件扩展
        $post_mime_type = 'image/png';
        if (!empty($article_with_pictures_options['image_ext'])) {
            $img_filename .= '.' . $article_with_pictures_options['image_ext'];
            $post_mime_type = 'image/' . $article_with_pictures_options['image_ext'];
        } else {
            $img_filename .= '.png';
        }
        $result = $api->saveImage($upload_dir['path'] . '/' . $img_filename);
        if (empty($result)) {
            return false;
        }
        if (function_exists('mime_content_type')) {
            $post_mime_type = mime_content_type($upload_dir['path'] . '/' . $img_filename);
        }
        $attachment = array(
            'post_mime_type' => $post_mime_type,
            'post_title' => $post_title,
            'post_content' => '',
            'post_status' => 'inherit',
            'guid' => $upload_dir['url'] . '/' . basename($img_filename)
        );
        $attach_id = wp_insert_attachment($attachment, $upload_dir['path'] . '/' . $img_filename, $post_id);
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attach_data = wp_generate_attachment_metadata($attach_id, $upload_dir['url'] . '/' . basename($img_filename));
        wp_update_attachment_metadata($attach_id, $attach_data);
        set_post_thumbnail($post_id, $attach_id);
        // 保存文章配图插件生成的数据
        update_post_meta($post_id, 'article_with_pictures_attach_id', $attach_id);
        update_post_meta($post_id, 'article_with_pictures_attach_id_key', self::get_image_key($post_title));
        return $attachment;
    }
}