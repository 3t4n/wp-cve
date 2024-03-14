<?php
defined('ABSPATH') || exit;
// Create a section
CSF::createSection($prefix, array(
    'title'  => esc_html__('Kênh hỗ trợ', 'inet-webkit'),
    'icon'   => 'fas fa-id-card',
    'description' => __('Tính năng này cho phép cài đặt các popup trên trang web của bạn để khách hàng có thể tương tác trực tiếp hỏi về sản phẩm.', 'inet-webkit'),
    'fields' => array(
        array(
            'id'    => 'inet-webkit-contact-active',
            'type'  => 'switcher',
            'title' => __('Kích hoạt', 'inet-webkit'),
            'class' => 'inet-webkit-contact-active',
            'text_on'   =>  __('Bật','inet-webkit'),
            'text_off'  =>  __('Tắt','inet-webkit')
        ),
        array(
            'id'            => 'inet-webkit-opt-contact',
            'type'          => 'accordion',
            'class'         => 'inet-webkit-opt-contact',
            'before'        => __('<h4>Cài đặt</h4><p>Bạn cần phải cài đặt các tính năng trước khi đưa vào sử dụng.</p>','inet-webkit'),
            'dependency'    => array( 'inet-webkit-contact-active', '==', 'true' ),
            'accordions'    => array(
                array(
                    'title'     => __('1. Nút trò chuyện','inet-webkit'),
                    'desc'      => __('<h3>Nút trò truyện</h3><p>Khi cài đặt phần này sẽ xuất hiện nút dùng đểthao tác trò chuyện trực tiếp giữa khách hàng và nhân viên của bạn.</p>Mình ảnh minh họa khi hiển thị trên web:<img src="' . INET_WK_URL . '/assets/images/admin/livechat_button.jpg"><span class="close-icon"><i class="fas fa-times"></i></span>','inet-webkit'),
                    'icon'      => 'fas fa-plus-circle',
                    'fields'    => array(
                        array(
                            'id'     => 'contact-design',
                            'type'   => 'fieldset',
                            'class' => 'inet-webkit-flex inet-webkit-flex-column',
                            'before'          => __('Bằng việc thay <b>đổi màu sắc, nội dung</b> và <b>vị trí,</b> sẽ <b>thu hút khách hàng tốt hơn</b> dẫn đến việc tương tác nhiều hơn.','inet-webkit'),
                            'fields'=> array(
                                array(
                                    'id'    => 'inet-webkit-contact-color',
                                    'type'  => 'color',
                                    'title' => __('Chọn màu <i class="fas fa-question-circle"></i>', 'inet-webkit'),
                                    'class' => 'inet-webkit-flex inet-webkit-flex-column pl-0 inet-webkit-help',
                                    'after'=> __('<i>Chúng tôi khuyên bạn <b>nên sử dụng màu sắc thương hiệu</b> hoặc <b>màu sắc đối lập</b> để tạo sự thu hút khách hàng bấm vào.<br>Hạn chế sử dụng màu đỏ trong trường hợp này. </i>','inet-webkit'),
                                    'subtitle'=> __('<h3>Chọn mầu</h3><p>Khi đổi màu trong phần cài đặt, màu sắc của  nút trò chuyện ở trang web của bạn sẽ được thay đổi theo</p>Mình ảnh minh họa khi hiển thị trên web:<img src="' . INET_WK_URL . '/assets/images/admin/livechat_button_color.jpg"><span class="close-icon"><i class="fas fa-times"></i></span>','inet-webkit'),
                                ),
                                array(
                                    'id'    => 'contact-greeting',
                                    'type'  => 'text',
                                    'class' => 'contact-greeting inet-webkit-flex inet-webkit-flex-column pl-0 inet-webkit-help',
                                    'title' => __('Tiêu đề <i class="fas fa-question-circle"></i>', 'inet-webkit'),
                                    'placeholder' => __('Xin chào! Chúng tôi có thể giúp gì cho bạn?', 'inet-webkit'),
                                    'subtitle'=> __('<h3>Tiêu đề</h3><p>Là dòng hiển thị để kêu gọi khách hàng bấm vào để bắt đầu trò chuyện.</p>Mình ảnh minh họa khi hiển thị trên web:<img src="' . INET_WK_URL . '/assets/images/admin/livechat_button_title.jpg"><span class="close-icon"><i class="fas fa-times"></i></span>','inet-webkit'),
                                ),
                                array(
                                    'id'    => 'position-y',
                                    'type'  => 'slider',
                                    'title' => __('Độ cao hiển thị','inet-webkit'),
                                    'class' => 'position-y inet-webkit-flex inet-webkit-flex-column',
                                    'unit'    => '%',
                                    'default' => 80,
                                ),
                                array(
                                    'id'         => 'contact-position',
                                    'type'       => 'radio',
                                    'title'      => __('Vị trí hiển thị <i class="fas fa-question-circle"></i>', 'inet-webkit'),
                                    'class'      => 'contact-position inet-webkit-flex inet-webkit-flex-column pl-0 inet-webkit-help',
                                    'subtitle'   => __('<h3>Vị trí hiển thị</h3><p>Bạn có thể chọn vị trí góc trái hoặc phải màn hình để hiển thị tốt hơn và không che nội dung trang web của bạn.</p>Hình ảnh minh họa cho vị trí góc Phải<img src="' . INET_WK_URL . '/assets/images/admin/livechat_button_position_2.jpg"><span class="close-icon"><i class="fas fa-times"></i></span>','inet-webkit'),
                                    'options'    => array(
                                        'inet-webkit-ct-left' => __('Trái', 'inet-webkit'),
                                        'inet-webkit-ct-right' => __('Phải', 'inet-webkit'),
                                    ),
                                    'default'    => 'inet-webkit-ct-right',
                                    'inline' => true,
                                )
                            )
                        ),
                    )
                ),
                array(
                    'title'     => __('2. Nút gọi điện','inet-webkit'),
                    'icon'      => 'fas fa-plus-circle',
                    'desc'        => __('<h3>Nút gọi điện</h3><p>Khi cài đặt phần này sẽ xuất hiện nút dùng để thao tác gọi điện nhanh đến các nhân viên mà bạn đã cài đặt.</p>Mình ảnh minh họa khi hiển thị trên web: <img src="' . INET_WK_URL . '/assets/images/admin/callnow.jpg"><span class="close-icon"><i class="fas fa-times"></i></span>','inet-webkit'),
                    'fields'    => array(
                        array(
                            'id'     => 'contact-phone',
                            'type'   => 'fieldset',
                            'class'  => 'inet-webkit-flex inet-webkit-flex-column',
                            'before' => __('Việc hiển thị <b>nút gọi điện</b> khi bấm vào <b>là số điện thoại của tư vấn viên hoặc nhân viên hỗ trợ</b> sẽ <b>giúp khách hàng</b> của bạn có thể tự <b>liên hệ nhanh chóng</b> và hiệu quả hơn.','inet-webkit'),
                            'fields' => array(
                                array(
                                    'id'        => 'contact-phone-title',
                                    'type'      => 'text',
                                    'title'     => __('Tiêu đề <i class="fas fa-question-circle"></i>', 'inet-webkit'),
                                    'class'     => 'inet-webkit-flex inet-webkit-flex-column pl-0 inet-webkit-help',
                                    'placeholder' => __('Gọi cho chúng tôi ngay', 'inet-webkit'),
                                    'subtitle'  => __('<h3>Tiêu đề</h3><p>Câu kêu gọi ngắn gọn để khách bấm vào gọi.</p>Mình ảnh minh họa khi hiển thị trên web:<img src="' . INET_WK_URL . '/assets/images/admin/callnow_title.jpg"><span class="close-icon"><i class="fas fa-times"></i></span>','inet-webkit')
                                ),
                                array(
                                    'id'     => 'contact-repeater',
                                    'type'   => 'repeater',
                                    'title'  => __('Bạn cần thêm thông tin nhân viên tư vấn hoặc hỗ trợ <i class="fas fa-question-circle"></i>', 'inet-webkit'),
                                    'button_title' => __('Thêm nhân viên', 'inet-webkit'),
                                    'max' => 5,
                                    'class' => 'contact-repeater inet-webkit-flex inet-webkit-flex-column pl-0 inet-webkit-help',
                                    'subtitle'=> __('<h3>Thêm nhân viên</h3><p>Bạn có thể thêm nhiều nhân viên với các chức danh khác nhau như tư vấn, bán hàng và hỗ trợ kỹ thuật.</p><p>Lưu ý: không thêm quá nhiều sẽ gây rối cho khách hàng của bạn.</p>Mình ảnh minh họa khi có 3 nhân viên hiển thị trên web:<img src="' . INET_WK_URL . '/assets/images/admin/callnow_staff.jpg"><span class="close-icon"><i class="fas fa-times"></i></span>','inet-webkit'),
                                    'fields' => array(
                                        array(
                                            'id'       => 'inet-webkit-contact-avatar',
                                            'type'     => 'radio',
                                            'title'    => __('Hình đại diện', 'inet-webkit'),
                                            'inline'   => true,
                                            'class'    => 'inet-webkit-contact-avatar',
                                            'options'  => array(
                                                'contact-avata-men'     => __('Nam', 'inet-webkit'),
                                                'contact-avata-women'   => __('Nữ', 'inet-webkit'),
                                                'contact-avata-support' => __('Support 24/7', 'inet-webkit'),
                                            ),
                                            'default'    => 'contact-avata-support'
                                        ),
                                        array(
                                            'id'    => 'inet-webkit-contact-title',
                                            'type'  => 'text',
                                            'title' => __('Tên hiển thị', 'inet-webkit'),
                                            'placeholder' => __('Nhân viên kinh doanh','inet-webkit')
                                        ),
                                        array(
                                            'id'    => 'contact-phone-number',
                                            'type'  => 'text',
                                            'title' => __('Số điện thoại','inet-webkit'),
                                            'placeholder' => __('Ví dụ: 0981 xxx xxx', 'inet-webkit'),
                                            'after' => __('<i>Nhập số điện thoại tư vấn viên hoặc nhân viên hỗ trợ của bạn.</i>','inet-webkit')
                                        ),
                                    ),
                                ),
                            )
                        ),
                    )
                ),
                array(
                    'title'     => __('3. Các kênh khác','inet-webkit'),
                    'icon'      => 'fas fa-plus-circle',
                    'desc' => __('<h3>Các kênh khác</h3><p>Bạn có thể sử dụng các <b>kênh liên hệ</b> khác như <b>email,</b> các mạng xã hội như: <b>Facebook, Zalo,</b> để trao đổi và tư vấn với khách hàng dễ dàng hơn.</p><img src="' . INET_WK_URL . '/assets/images/admin/other_channels.jpg"><span class="close-icon"><i class="fas fa-times"></i></span>','inet-webkit'),
                    'fields'    => array(
                        array(
                            'id'     => 'general-contact',
                            'type'   => 'fieldset',
                            'class'  => 'inet-webkit-flex inet-webkit-flex-column',
                            'before'    => __('Bạn có thể sử dụng các kênh liên hệ khác như email, các mạng xã hội như: Facebook, Zalo, để trao đổi và tư vấn với khách hàng dễ dàng hơn.','inet-webkit'),
                            'fields' => array(
                                array(
                                    'id'    => 'contact-title',
                                    'title' => __('Tiêu đề', 'inet-webkit'),
                                    'type'  => 'text',
                                    'class' => 'inet-webkit-flex inet-webkit-flex-column pl-0',
                                    'placeholder' => __('Gọi cho chúng tôi ngay', 'inet-webkit'),
                                ),
                                array(
                                    'id'    => 'contact-email',
                                    'title' => __('Địa chỉ Email', 'inet-webkit'),
                                    'type'  => 'text',
                                    'class' => 'inet-webkit-flex inet-webkit-flex-column pl-0',
                                    'validate' => 'csf_validate_email',
                                    'placeholder' => __('Ví dụ: support@yourdomain', 'inet-webkit'),
                                ),
                                array(
                                    'id'    => 'facebook-page',
                                    'type'  => 'text',
                                    'title' => 'Facebook',
                                    'class' => 'inet-webkit-flex inet-webkit-flex-column pl-0',
                                    'validate' => 'csf_validate_url',
                                    'placeholder' => __('Ví dụ: https://www.facebook.com/your-page', 'inet-webkit'),
                                ),
                                array(
                                    'id'    => 'contact-zalo',
                                    'type'  => 'text',
                                    'title' => 'Zalo',
                                    'class' => 'inet-webkit-flex inet-webkit-flex-column pl-0',
                                    'validate' => 'csf_validate_numeric',
                                    'placeholder' => __('Ví dụ: 0981 xxx xxx', 'inet-webkit'),
                                ),
                                array(
                                    'id'    => 'contact-skype',
                                    'type'  => 'text',
                                    'title' => 'Skype',
                                    'class' => 'inet-webkit-flex inet-webkit-flex-column pl-0',
                                    'placeholder' => __('Ví dụ: webkitsk', 'inet-webkit'),
                                ),
                                array(
                                    'id'    => 'contact-telegram',
                                    'type'  => 'text',
                                    'title' => 'Telegram',
                                    'class' => 'inet-webkit-flex inet-webkit-flex-column pl-0',
                                    'placeholder' => __('Ví dụ: webkittlg', 'inet-webkit'),
                                ),
                                array(
                                    'id'    => 'contact-viber',
                                    'type'  => 'text',
                                    'title' => 'Viber',
                                    'class' => 'inet-webkit-flex inet-webkit-flex-column pl-0',
                                    'placeholder' => __('Ví dụ: webkitviber', 'inet-webkit'),
                                ),
                                array(
                                    'id'    => 'tawk-to',
                                    'class' => 'inet-webkit-flex inet-webkit-flex-column pl-0',
                                    'type'  => 'code_editor',
                                    'title' => 'Facebook // Tawk.to livechat',
                                    'placeholder' => __('Mã Tawk.to', 'inet-webkit'),
                                    'sanitize' => false,
                                    'settings' => array(
                                        'theme'  => 'monokai',
                                        'mode'   => 'javascript',
                                    ),
                                    'before' => __('Xem hướng dẫn nhận Tawk.to <a href="https://helpdesk.inet.vn/knowledgebase/huong-dan-them-chat-tawkto-vao-website-wordpress/" target="_blank">tại đây</a>', 'inet-webkit')
                                )
                            )
                        ),
                    )
                )
            )
        )
    )
));
