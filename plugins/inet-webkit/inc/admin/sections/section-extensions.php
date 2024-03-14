<?php
defined( 'ABSPATH' ) || exit;
// Create a section
CSF::createSection( $prefix, array(
    'title'  => __( 'Bài viết', 'inet-webkit' ),
    'icon'   => 'fas fa-th',
    'description' => __('Thêm nhiều tính năng hơn giúp cải thiện trang WordPress của bạn.', 'inet-webkit'),
    'fields' => array(
        array(
            'id'     => 'inet-webkit-add-html-page',
            'type'   => 'fieldset',
            'title' => __('Thêm .html vào các trang', 'inet-webkit'),
            'class' => 'inet-webkit-pd-0 inet-webkit-box-shadow-none inet-webkit-add-html-page',
            'fields' => array(
                array(
                    'id'     => 'add-html-page-active',
                    'type'   => 'switcher',
                    'class' => 'inet-webkit-flex inet-webkit-flex-column',
                    'after' => __('<i>Tự động thêm <strong>.html</strong> vào url các trang trên hệ thống.</i>', 'inet-webkit'),
                    'text_on'   =>  __('Bật', 'inet-webkit'),
                    'text_off'  =>  __('Tắt', 'inet-webkit')
                )
            )
        ),
        array(
            'id'     => 'inet-webkit-redirect-404',
            'type'   => 'fieldset',
            'title' => __('Chuyển 404 về trang chủ', 'inet-webkit'),
            'class' => 'inet-webkit-pd-0 inet-webkit-box-shadow-none inet-webkit-redirect-404',
            'fields' => array(
                array(
                    'id'     => 'redirect-404-active',
                    'type'   => 'switcher',
                    'class' => 'inet-webkit-flex inet-webkit-flex-column',
                    'after' => __('<i>Khi khách hàng gặp phải các liên kết bị lỗi, không tồn tại, hệ thống chuyển hướng các <strong>trang 404</strong> về trang chủ của bạn.</i>', 'inet-webkit'),
                    'text_on'   =>  __('Bật', 'inet-webkit'),
                    'text_off'  =>  __('Tắt', 'inet-webkit')
                )
            )
        ),
        array(
            'id'     => 'inet-webkit-classic-editor',
            'type'   => 'fieldset',
            'title' => __('Trình soạn thảo Classic', 'inet-webkit'),
            'class' => 'inet-webkit-pd-0 inet-webkit-box-shadow-none inet-webkit-classic-editor',
            'fields' => array(
                array(
                    'id'     => 'classic-editor-active',
                    'type'   => 'switcher',
                    'class' => 'inet-webkit-flex inet-webkit-flex-column',
                    'after' => __('Sử dụng trình soạn thảo <strong>Classic</strong> thay thế cho <strong>Gutenberg</strong>.', 'inet-webkit'),
                    'text_on'   =>  __('Bật', 'inet-webkit'),
                    'text_off'  =>  __('Tắt', 'inet-webkit')
                )
            )
        ),
        array(
            'id'     => 'inet-webkit-opt-duplicate',
            'type'   => 'fieldset',
            'class' => 'inet-webkit-flex inet-webkit-flex-column inet-webkit-box-shadow-none inet-webkit-opt-duplicate inet-webkit-pd-0',
            'fields' => array(
                array(
                    'id'    => 'duplicate-page-post-active',
                    'type'  => 'switcher',
                    'title' => __('Nhân bản trang/ bài viết <i class="fas fa-question-circle"></i>', 'inet-webkit'),
                    'class' => 'duplicate-page-post-active inet-webkit-help',
                    'after' => __('<i>Cho phép nhân bản trang / bài viết.</i>', 'inet-webkit'),
                    'text_on'   =>  __('Bật', 'inet-webkit'),
                    'text_off'  =>  __('Tắt', 'inet-webkit'),
                    'subtitle' => __('<p><strong>Nhân bản trang/ bài viết</strong></p>
                                    <p>Có thêm tính năng nhân bản cho các trang và bài viết của bạn.</p>
                                    <p>Mình ảnh minh họa:</p>
                                    <img src="' . INET_WK_URL . 'assets/images/admin/duplicate-article.svg"><span class="close-icon"><i class="fas fa-times"></i></span>', 'inet-webkit')
                ),
                array(
                    'id'    => 'duplicate-menu-active',
                    'type'  => 'switcher',
                    'text_on'   =>  __('Bật', 'inet-webkit'),
                    'text_off'  =>  __('Tắt', 'inet-webkit'),
                    'title' => __('Nhân bản menu <i class="fas fa-question-circle"></i>', 'inet-webkit'),
                    'class' => 'duplicate-menu-active inet-webkit-pd-y-15 inet-webkit-help',
                    'after' => __('<i>Tính năng nhân bản trang/ bài viết và menu sẽcho phép bạn có thểtạo thêm bản sao giống với nội dung đã được tạo. Việc này sẽ giúp bạn thao tác nhanh hơn trong việc tạo trang WordPress của mình.</i>', 'inet-webkit'),
                    'subtitle' => __('<p><strong>Nhân bản menu</strong></p>
                                    <p>Có thêm tính năng nhân bản cho menu của bạn.</p>
                                    <p>Mình ảnh minh họa:</p>
                                    <img src="' . INET_WK_URL . 'assets/images/admin/duplicate-menu.svg"><span class="close-icon"><i class="fas fa-times"></i></span>', 'inet-webkit')
                )
            )
        ),
        array(
            'id'     => 'inet-webkit-disable-emojis',
            'type'   => 'fieldset',
            'title' => __('Xóa biểu tượng Emojis', 'inet-webkit'),
            'class' => 'inet-webkit-pd-0 inet-webkit-box-shadow-none inet-webkit-redirect-404',
            'fields' => array(
                array(
                    'id'     => 'disable-emojis-active',
                    'type'   => 'switcher',
                    'class' => 'inet-webkit-flex inet-webkit-flex-column',
                    'after' => __('<i>Không tải tệp <strong>wp-emoji-release.min.js</strong> chứa các icon cảm xúc của WordPress.</i>', 'inet-webkit'),
                    'text_on'   =>  __('Bật', 'inet-webkit'),
                    'text_off'  =>  __('Tắt', 'inet-webkit')
                )
            )
        ),
        array(
            'id'     => 'inet-webkit-remove-query-strings',
            'type'   => 'fieldset',
            'title' => __('Remove Query Strings', 'inet-webkit'),
            'class' => 'inet-webkit-pd-0 inet-webkit-box-shadow-none inet-webkit-redirect-404',
            'fields' => array(
                array(
                    'id'     => 'switcher-remove-query-strings',
                    'type'   => 'switcher',
                    'class' => 'inet-webkit-flex inet-webkit-flex-column',
                    'after' => __('<i>Xóa chuỗi truy vấn khỏi tài nguyên tĩnh</i>.', 'inet-webkit'),
                    'text_on'   =>  __('Bật', 'inet-webkit'),
                    'text_off'  =>  __('Tắt', 'inet-webkit')
                )
            )
        ),
        array(
            'id'     => 'inet-webkit-disable-wp-embeds',
            'type'   => 'fieldset',
            'title' => __('Disable Wordpress Embeds', 'inet-webkit'),
            'class' => 'inet-webkit-pd-0 inet-webkit-box-shadow-none inet-webkit-redirect-404',
            'fields' => array(
                array(
                    'id'     => 'disable-wp-embeds-active',
                    'type'   => 'switcher',
                    'class' => 'inet-webkit-flex inet-webkit-flex-column',
                    'after' => __('<i>Tắt tính năng chèn <strong>mã nhúng oEmbeds</strong> trong WordPress</i>.', 'inet-webkit'),
                    'text_on'   =>  __('Bật', 'inet-webkit'),
                    'text_off'  =>  __('Tắt', 'inet-webkit')
                )
            )
        ),
        array(
            'id'     => 'inet-webkit-disable-google-font',
            'type'   => 'fieldset',
            'title' => __('Tắt Google Font', 'inet-webkit'),
            'class' => 'inet-webkit-pd-0 inet-webkit-box-shadow-none inet-webkit-redirect-404',
            'fields' => array(
                array(
                    'id'     => 'disable-google-font-active',
                    'type'   => 'switcher',
                    'class' => 'inet-webkit-flex inet-webkit-flex-column',
                    'after' => __('<i>Tắt không load <strong>Google font</strong> trên trang, và load font mặc định của trang</i>.', 'inet-webkit'),
                    'text_on'   =>  __('Bật', 'inet-webkit'),
                    'text_off'  =>  __('Tắt', 'inet-webkit')
                )
            )
        ),
        array(
            'id'     => 'inet-webkit-disable-dashicons',
            'type'   => 'fieldset',
            'title' => __('Tắt Dashicons', 'inet-webkit'),
            'class' => 'inet-webkit-pd-0 inet-webkit-box-shadow-none inet-webkit-redirect-404',
            'fields' => array(
                array(
                    'id'     => 'disable-dashicons-active',
                    'type'   => 'switcher',
                    'class' => 'inet-webkit-flex inet-webkit-flex-column',
                    'after' => __('<i>Tắt dashicons trên giao diện người dùng khi chưa đăng nhập.</i>', 'inet-webkit'),
                    'text_on'   =>  __('Bật', 'inet-webkit'),
                    'text_off'  =>  __('Tắt', 'inet-webkit')
                )
            )
        ),
        array(
            'id'     => 'inet-webkit-custom-login',
            'type'   => 'fieldset',
            'title' => __('Header & Footer đăng nhập', 'inet-webkit'),
            'class' => 'inet-webkit-pd-0 inet-webkit-box-shadow-none inet-webkit-custom-login',
            'fields' => array(
                array(
                    'id'    => 'custom-login-active',
                    'type'  => 'switcher',
                    'class' => 'inet-webkit-flex inet-webkit-flex-column custom-login-active',
                    'text_on'   =>  __('Bật', 'inet-webkit'),
                    'text_off'  =>  __('Tắt', 'inet-webkit'),
                    'after' => __('<i>Bạn đã có thể <strong>sử dụng logo của mình</strong> để thay cho logo mặc định <strong>wordpress</strong> sở trang đăng nhập và <strong>liên kết khi nhấp</strong> vào logo đó.</i>', 'inet-webkit')
                )
            )
        ),
        array(
            'id'    => 'inet-webkit-custom-login-logo',
            'class' => 'inet-webkit-custom-login-logo',
            'type'  => 'media',
            'title' => __('Logo', 'inet-webkit'),
            'library'      => 'image',
            'button_title' => __('Chọn ảnh', 'inet-webkit'),
            'placeholder'  => 'http://',
            'preview' => true,
            'dependency' => array( 'custom-login-active', '==', 'true'),
        ),
        array(
            'id'        => 'inet-webkit-custom-login-link',
            'class'     => 'inet-webkit-custom-login-link',
            'type'      => 'text',
            'title'     => __('Đường dẫn liên kết', 'inet-webkit'),
            'default'   => get_site_url(),
            'validate'  => 'inet_wk_customize_validate_url',
            'dependency' => array( 'custom-login-active', '==', 'true' ),
        )
    )
) );