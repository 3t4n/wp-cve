<?php
defined( 'ABSPATH' ) || exit;
$url_site = get_site_url().'/';

CSF::createSection( $prefix, array(
    'title'  => __( 'Bảo mật', 'inet-webkit' ),
    'icon'   => 'fas fa-user-shield',
    'description'=> __('<p>Tăng cường bảo mật trang web WordPress là cần thiết và rất quan trọng. Ngày nay số lượng trang web sửa dụng WordPress ngày càng nhiều điều này cũng đồng nghĩa với việc thu hút các hacker tập trung tấn công vào miếng mồi ngon này.</p><p>
Tính năng Bảo mật của chúng tôi sẽ giúp bạn gia cố thêm lớp bảo vệ cho trang web WordPress của bạn được đảm bảo an toàn hơn.</p>','inet-webkit'),
    'fields' => array(
        array(
            'id'     => 'inet-webkit-remove-xml-rpc',
            'type'   => 'switcher',
            'class'  => 'inet-webkit-flex',
            'title'  => __('Vô hiệu hóa XML-RPC','inet-webkit'),
            'after'  => __('<i>Việc tính năng này được tắt đi đồng nghĩa với việc sẽ hạn chế các cuộc tấn công như: dò mật khẩu và làm quá tải hệ thống không xử lý được yêu cầu thực sự của khách hàng.</i>','inet-webkit'),
            'text_on'   =>  __('Bật','inet-webkit'),
            'text_off'  =>  __('Tắt','inet-webkit')
        ),
        array(
            'id'     => 'inet-webkit-disable-copy-content',
            'type'   => 'switcher',
            'class'  => 'inet-webkit-flex',
            'title'  => __('Cấm sao chép nội dung','inet-webkit'),
            'after'  => __('<i>Khách hàng của bạn sẽ không thể nhấp phải vào trang để xem hoặc sao chép mã code trên trang web của bạn.</i>','inet-webkit'),
            'text_on'   =>  __('Bật','inet-webkit'),
            'text_off'  =>  __('Tắt','inet-webkit')
        ),
        array(
            'id'     => 'inet-webkit-delete-link-head',
            'type'   => 'switcher',
            'class'  => 'inet-webkit-flex',
            'title'  => __('Xóa các liên kết từ wp_head','inet-webkit'),
            'after'  => __('<p>Sử dụng tính năng này sẽ giúp xóa đi các liên kết ở <code>&lt;head&gt;</code> giúp trang web được tải nhanh hơn, giúp SEO hiệu quả hơn.</p>','inet-webkit'),
            'text_on'   =>  __('Bật','inet-webkit'),
            'text_off'  =>  __('Tắt','inet-webkit')
        ),
        array(
            'id'    => 'inet-webkit-switcher-hide-wp-version',
            'title'  => __('Ẩn phiên bản WordPress','inet-webkit'),
            'type'  => 'switcher',
            'class' => 'inet-webkit-flex',
            'after' => __('<p>Ẩn thông tin phiên bản WordPress khỏi cấu trúc DOM(HTML) của website.</p>','inet-webkit'),
            'text_on'   =>  __('Bật','inet-webkit'),
            'text_off'  =>  __('Tắt','inet-webkit')
        ),
        array(
            'id'    => 'inet-webkit-switcher-hide-menu-theme-plugin',
            'title'  => __('Ẩn menu theme / plugin','inet-webkit'),
            'type'  => 'switcher',
            'class' => 'inet-webkit-flex',
            'after' => __('<p>Ẩn menu theme / plugin, tắt chức năng chỉnh sửa theme và plugin.</p>','inet-webkit'),
            'text_on'   =>  __('Bật','inet-webkit'),
            'text_off'  =>  __('Tắt','inet-webkit')
        ),
        array(
            'id'     => 'inet-webkit-login-url',
            'type'   => 'fieldset',
            'title' => __('Thay đổi đường dẫn<br> đăng nhập <i class="fas fa-question-circle"></i>','inet-webkit'),
            'class' => 'inet-webkit-box-shadow-none inet-webkit-help inet-webkit-flex ',
            'subtitle' => __('<p><strong>Đường dẫn đăng nhập mới</strong></p>
<p>Thông thường đường dẫn mặc định để vào trang quản trị WordPress sẽ là: </p>
<p>'.$url_site.'<strong>wp-admin</strong><br>hoặc<br>'.$url_site.'<strong>wp-login</strong></p>
<p>Việc sử dụng các đường dẫn mặc định này để thao tác sẽ khiến cho trang web của bạn dễ bị tấn công. Bởi vì bất kỳ ai cũng có thể dùng các phương pháp dò mật khẩu để vào trang quản trị của bạn, gây hại đến trang web của bạn.</p><span class="close-icon"><i class="fas fa-times"></i></span>','inet-webkit'),
            'fields' => array(
                array(
                    'id'    => 'inet-webkit-switcher-change-url-login',
                    'type'  => 'switcher',
                    'class' => 'inet-webkit-login-url-switcher',
                    'after' => __('<i>Việc thay đổi này sẽ tránh các cuộc tấn công dò mật khẩu và còn giúp bạn tự tạo đường dẫn dễ nhớ và thuận tiện cho bạn hơn.</i>','inet-webkit'),
                    'text_on'   =>  __('Bật','inet-webkit'),
                    'text_off'  =>  __('Tắt','inet-webkit')
                ),
                array(
                    'id'    => 'inet-webkit-login-new-url',
                    'type'  => 'text',
                    'class' => 'inet-webkit-login-url',
                    'before'=> $url_site,
                    'placeholder' => 'Example: ad-panel',
                )
            )
        ),
    )
) );