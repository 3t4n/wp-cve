<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Create a section
CSF::createSection( $prefix, array(
    'title'  => __( 'Kết nối SMTP', 'inet-webkit' ),
    'icon'   => 'fas fa-envelope',
    'description' => __('<p>Hệ thống sẽ tiến hành việc gửi thông báo qua email khi có đơn hàng mới, thông báo cần thiết. Các cấu hình <strong>có sẵn trên Hosting</strong> thường không <strong>cho phép người dùng gửi email</strong> hoặc admin không có sẵn <strong>tài khoản SMTP mail</strong>.</p>
<p>Tính năng <strong>Kết nối SMTP</strong> cho phép người dùng có thể <strong>tùy chỉnh cấu hình việc gửi mail</strong> với nhiều phương pháp miễn phí.</p>
<a href="https://helpdesk.inet.vn/blog/huong-dan-cai-smtp-gmail-mien-phi" target="_blank">Xem hướng dẫn cài đặt</a>', 'inet-webkit'),
    'fields' => array(
        array(
            'id'        =>  'inet-webkit-opt-smtp',
            'type'      =>  'fieldset',
            'class'     =>  'inet-webkit-opt-smtp',
            'fields'    => array(
                array(
                    'id'        =>  'inet-webkit-smtp-active',
                    'type'      =>  'switcher',
                    'title'     =>  __('Kích hoạt','inet-webkit'),
                    'class'     =>  'inet-webkit-flex-row',
                    'text_on'   =>  __('Bật','inet-webkit'),
                    'text_off'  =>  __('Tắt','inet-webkit')
                ),
                array(
                    'id'         => 'inet-webkit-smtp-setting',
                    'type'       => 'radio',
                    'title'      => __('Cài đặt SMTP (Mailer)','inet-webkit'),
                    'dependency' => array( 'inet-webkit-smtp-active', '==', 'true' ),
                    'class'     =>  'inet-webkit-opt-smtp-list',
                    'options'    => array(
                        '1' => 'Gmail SMTP',
                        '2' => 'SMTP Yandex',
                        '0' => 'SMTP Khác',
                    ),
                    'default'    => '1',
                    'inline' => true
                ),
                array(
                    'id'         => 'inet-webkit-smtp-email',
                    'type'       => 'text',
                    'title'      => __('Email gửi','inet-webkit'),
                    'placeholder'=> __('Ví dụ: support@yourdomain.com','inet-webkit'),
                    'validate'   => 'csf_validate_email',
                    'dependency' => array( 'inet-webkit-smtp-active', '==', 'true' ),
                    'after'      => __('<i>Nếu bạn sử dụng Gmail, Yandex mail hoặc SMTP khác để gửi mail cho khách hàng thì đây sẽ là email gửi của bạn.</i>','inet-webkit')
                ),
                array(
                    'id'         => 'inet-webkit-smtp-fromName',
                    'type'       => 'text',
                    'title'      => __('Tên gửi','inet-webkit'),
                    'placeholder'=> __('Tên Thương hiệu / Doanh nghiệp','inet-webkit'),
                    'dependency' => array( 'inet-webkit-smtp-active', '==', 'true'),
                    'after'      => __('<i>Tên được hiển thị cho email khi gửi.</i>','inet-webkit')
                ),
                array(
                    'id'         => 'inet-webkit-smtp-host',
                    'type'       => 'text',
                    'title'      => __('Máy chủ SMTP','inet-webkit'),
                    'placeholder'=> __('Máy chủ SMTP','inet-webkit'),
                    'dependency' => array( 'inet-webkit-smtp-active', '==', 'true' ),
                ),
                array(
                    'id'         => 'inet-webkit-smtp-security',
                    'type'       => 'radio',
                    'title'      => __('Bảo mật SMTP','inet-webkit'),
                    'placeholder'=> __('Máy chủ SMTP','inet-webkit'),
                    'dependency' => array( 'inet-webkit-smtp-active', '==', 'true'),
                    'after'      => __('<i>Bảo mật TLS là phương án được khuyên dùng. Nếu máy chủ SMTP của bạn cho phép cả 2 loại bảo mật này, chúng tôi khuyên bạn nên dùng TLS.</i>','inet-webkit'),
                    'options'    => array(
                        'none' => 'None',
                        'ssl' => 'SSL',
                        'tls' => 'TLS',
                    ),
                    'default'    => 'ssl',
                    'inline' => true
                ),
                array(
                    'id'         => 'inet-webkit-smtp-port',
                    'type'       => 'text',
                    'title'      => __('Cổng SMTP','inet-webkit'),
                    'placeholder'=> '25',
                    'validate'   => 'csf_validate_numeric',
                    'after' => __('Port 587 / 465 / 25','inet-webkit') ,
                    'dependency' => array( 'inet-webkit-smtp-active', '==', 'true' ),
                ),
                array(
                    'id'         => 'inet-webkit-smtp-user',
                    'type'       => 'text',
                    'title'      => __('Tên đăng nhập SMTP','inet-webkit'),
                    'validate'   => 'csf_validate_email',
                    'placeholder'=> __('Tên đăng nhập SMTP','inet-webkit'),
                    'dependency' => array( 'inet-webkit-smtp-active', '==', 'true' ),
                ),
                array(
                    'id'         => 'inet-webkit-smtp-password',
                    'type'       => 'text',
                    'class'      => 'mb-input-password',
                    'title'      => __('Mật khẩu SMTP','inet-webkit'),
                    'placeholder'=> __('Mật khẩu SMTP','inet-webkit'),
                    'dependency' => array( 'inet-webkit-smtp-active', '==', 'true'),
                    'after'      => '<i class="fas fa-eye"></i>',
                    'attributes'  => array(
                        'type'      => 'password',
                    ),
                )
            )
        ),
        array(
            'id'            =>  'inet-webkit-smtp-test',
            'type'          =>  'fieldset',
            'title'         =>  __( 'Gửi thử', 'inet-webkit' ),
            'class'         =>  'inet-webkit-smtp-test',
            'dependency' => array( 'inet-webkit-smtp-active', '==', 'true'),
            'description'   =>  '',
            'fields'        => array(
                array(
                    'id'         => 'inet-webkit-smtp-mail-test',
                    'type'       => 'text',
                    'class'      => 'email-smtp-test',
                    'validate'   => 'csf_validate_email',
                    'placeholder'=> __('Địa chỉ Email nhận','inet-webkit'),
                    'before'     => __('Sau khi hoàn tất việc cài đặt, để biết được việc <strong>gửi mail có thành công hay không?</strong> Bạn <strong>nhập email bất kỳ</strong> vào ô bên dưới, bấm kiểm tra <strong>và kiểm tra hộp thư đến</strong> của email đó, nếu nhận được email thì bạn <strong>đã cài đặt thành công.</strong> ','inet-webkit'),
                    'after'      => '<div class="smtp-test-result"></div>',
                    'attributes'  => array(
                        'type'      => 'email',
                    ),
                ),
                array(
                    'id'         => 'inet-webkit-smtp-btn-test',
                    'type'       => 'text',
                    'class'      => 'btn-smpt-test',
                    'before'     => '<div id="loader"></div>',
                    'value'      => __('Kiểm tra','inet-webkit'),
                    'attributes'  => array(
                        'type'    => 'button',
                    ),
                ),
            )
        )
    )
) );
