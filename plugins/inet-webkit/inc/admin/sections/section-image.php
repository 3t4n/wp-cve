<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Create a section
CSF::createSection( $prefix, array(
    'title'  => __( 'Hình ảnh', 'inet-webkit' ),
    'icon'   => 'fas fa-image',
    'description' => __('Thêm nhiều tính năng hơn giúp cải thiện trang WordPress của bạn.','inet-webkit'),
    'fields' => array(
        array(
            'id'     => 'inet-webkit-auto-save-image',
            'type'   => 'fieldset',
            'title' => __('Tự động lưu ảnh vào bài viết', 'inet-webkit'),
            'class' => 'inet-webkit-pd-0 inet-webkit-box-shadow-none inet-webkit-auto-save-image',
            'fields' => array(
                array(
                    'id'     => 'auto-save-image',
                    'type'   => 'switcher',
                    'class' => 'inet-webkit-flex inet-webkit-flex-column',
                    'after' => __('<i>Tự động download ảnh trong nội dung bài viết của website khác vào thư viện hình ảnh</i>', 'inet-webkit'),
                    'text_on'   =>  __('Bật', 'inet-webkit'),
                    'text_off'  =>  __('Tắt', 'inet-webkit')
                )
            )
        ),
        array(
            'id'     => 'inet-webkit-auto-save-image',
            'type'   => 'fieldset',
            'title' => __('|__ Tùy chọn lưu ảnh', 'inet-webkit'),
            'class' => 'inet-webkit-pd-0 inet-webkit-box-shadow-none inet-webkit-auto-save-image',
            'fields' => array(
                array(
                    'id'     => 'auto-save-image-type',
                    'type'   => 'select',
                    'class' => 'inet-webkit-selection inet-webkit-selection-column',
                    'after' => '',
                    'options'     => array(
                        'new-post'  => __('Chỉ tin mới', 'inet-webkit'),
                        'all-post'  => __('Tất cả', 'inet-webkit'),
                    ),
                    'default' => 'auto-save-image-new-post'
                )
            )
        ),
        array(
            'id'     => 'inet-webkit-auto-set-featured-image',
            'type'   => 'fieldset',
            'title' => __('Tự động đặt ảnh đại diện', 'inet-webkit'),
            'class' => 'inet-webkit-pd-0 inet-webkit-box-shadow-none inet-webkit-auto-set-featured-image',
            'fields' => array(
                array(
                    'id'     => 'auto-set-featured-image',
                    'type'   => 'switcher',
                    'class' => 'inet-webkit-flex inet-webkit-flex-column',
                    'after' => __('<i>Tự động tìm và lấy ảnh bài viết làm ảnh đại diện', 'inet-webkit'),
                    'text_on'   =>  __('Bật', 'inet-webkit'),
                    'text_off'  =>  __('Tắt', 'inet-webkit')
                )
            )
        ),
        array(
            'id'     => 'inet-webkit-auto-set-image-meta',
            'type'   => 'fieldset',
            'title' => __('Tối ưu SEO hình ảnh', 'inet-webkit'),
            'class' => 'inet-webkit-pd-0 inet-webkit-box-shadow-none inet-webkit-auto-set-image-meta',
            'fields' => array(
                array(
                    'id'     => 'auto-set-image-meta',
                    'type'   => 'switcher',
                    'class' => 'inet-webkit-flex inet-webkit-flex-column',
                    'after' => __('<i>Tự động thêm tiêu đề và mô tả khi upload ảnh vào bài viết.</i>', 'inet-webkit'),
                    'text_on'   =>  __('Bật', 'inet-webkit'),
                    'text_off'  =>  __('Tắt', 'inet-webkit')
                )
            )
        ),
        array(
            'id'     => 'inet-webkit-resize-image',
            'type'   => 'fieldset',
            'title' => __('Tự động resize kích thước ảnh', 'inet-webkit'),
            'class' => 'inet-webkit-pd-0 inet-webkit-box-shadow-none inet-webkit-resize-image',
            'fields' => array(
                array(
                    'id'     => 'auto-resize-image',
                    'type'   => 'switcher',
                    'class' => 'inet-webkit-flex inet-webkit-flex-column',
                    'after' => __('<i>Tự động resize kích thước ảnh khi vượt quá ngưỡng cho phép</i>', 'inet-webkit'),
                    'text_on'   =>  __('Bật', 'inet-webkit'),
                    'text_off'  =>  __('Tắt', 'inet-webkit')
                )
            )
        ),
        array(
            'id'     => 'inet-webkit-limit-image-width',
            'type'   => 'fieldset',
            'title' => __('|__ Kích thước ảnh tối đa (px)<br /> Ví dụ: <i>1920, tương ứng 1.920px</i>', 'inet-webkit'),
            'dependency' => array( 'auto-resize-image', '==', 'true' ),
            'class' => 'inet-webkit-pd-0 inet-webkit-box-shadow-none inet-webkit-limit-width-size',
            'fields' => array(
                array(
                    'id'     => 'limit-image-width',
                    'type'   => 'text',
                    'class' => 'inet-webkit-text inet-webkit-text-column',
                    'after' => 'Ảnh vượt quá sẽ tự động resize mức đặt, để trống nếu không áp dụng',
                    'default' => '1920'
                )
            )
        ),
        array(
            'id'     => 'inet-webkit-auto-compress-image',
            'type'   => 'fieldset',
            'title' => __('Tự động nén chất lượng ảnh', 'inet-webkit'),
            'class' => 'inet-webkit-pd-0 inet-webkit-box-shadow-none inet-webkit-auto-compress-image',
            'fields' => array(
                array(
                    'id'     => 'auto-compress-image',
                    'type'   => 'switcher',
                    'class' => 'inet-webkit-flex inet-webkit-flex-column',
                    'after' => __('<i>Tối ưu dung lượng thông qua giảm chất lượng ảnh tải lên</i>', 'inet-webkit'),
                    'text_on'   =>  __('Bật', 'inet-webkit'),
                    'text_off'  =>  __('Tắt', 'inet-webkit')
                )
            )
        ),
        array(
            'id'     => 'inet-webkit-auto-compress-jpeg-image',
            'type'   => 'fieldset',
            'title' => __('|__ Nén ảnh JPEG', 'inet-webkit'),
            'dependency' => array( 'auto-compress-image', '==', 'true' ),
            'class' => 'inet-webkit-pd-0 inet-webkit-box-shadow-none inet-webkit-auto-compress-jpeg-image',
            'fields' => array(
                array(
                    'id'     => 'auto-compress-jpeg-image',
                    'type'   => 'switcher',
                    'class' => 'inet-webkit-flex inet-webkit-flex-column',
                    'after' => __('<i>Giảm dung lượng thông qua giảm chất lượng ảnh JPEG</i>', 'inet-webkit'),
                    'text_on'   =>  __('Bật', 'inet-webkit'),
                    'text_off'  =>  __('Tắt', 'inet-webkit')
                )
            )
        ),
        array(
            'id'     => 'inet-webkit-compress-quality-image',
            'type'   => 'fieldset',
            'title' => __('|__ Chất lượng ảnh nén lại<br /> Mặc định: <i>90%</i>', 'inet-webkit'),
            'dependency' => array( 'auto-compress-image', '==', 'true' ),
            'class' => 'inet-webkit-pd-0 inet-webkit-box-shadow-none inet-webkit-compress-quality-image',
            'fields' => array(
                array(
                    'id'     => 'compress-image-quality',
                    'type'   => 'select',
                    'class' => 'inet-webkit-selection inet-webkit-selection-column',
                    'after' => '',
                    'options'     => array(
                        ''  => __('Không áp dụng', 'inet-webkit'),
                        '75'  => __('75%', 'inet-webkit'),
                        '80'  => __('80%', 'inet-webkit'),
                        '85'  => __('85%', 'inet-webkit'),
                        '90'  => __('90%', 'inet-webkit'),
                        '95'  => __('95%', 'inet-webkit'),
                        '100'  => __('100%', 'inet-webkit'),
                    ),
                    'default' => ''
                )
            )
        ),
        array(
            'id'     => 'inet-webkit-limit-image-size',
            'type'   => 'fieldset',
            'title' => __('|__ Dung lượng ảnh tối đa (kb)<br /> Ví dụ: <i>2048, tương ứng 2M</i>', 'inet-webkit'),
            'dependency' => array( 'auto-compress-image', '==', 'true' ),
            'class' => 'inet-webkit-pd-0 inet-webkit-box-shadow-none inet-webkit-limit-image-size',
            'fields' => array(
                array(
                    'id'     => 'limit-image-size',
                    'type'   => 'text',
                    'class' => 'inet-webkit-text inet-webkit-text-column',
                    'after' => 'Để trống tương ứng việc không áp dụng',
                    'default' => ''
                )
            )
        ),
    )
) );