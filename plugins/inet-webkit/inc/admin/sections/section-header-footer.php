<?php

// Create a section
CSF::createSection($prefix, array(
    'title'  => __('Header & Footer', 'inet-webkit'),
    'icon'   => 'fas fa-code',
    'description' => __('Trong quá trình vận hành trang web, sẽ có lúc bạn <strong>cần thêm một số đoạn code vào</strong> trong <strong>trang WordPress</strong> của mình <strong>để phục vụ cho công việc</strong> như: Google Analytics, Google Search Console.Nhưng bạn là người mới đôi khi sẽ không biết làm như thế nào. <strong>Tính năng Header & Footer</strong> giúp bạn làm mọi thứđơn giản hơn, chỉ cần <strong>sao chép và dán đoạn code vào đoạn script</strong> phù hợp là hoàn tất. ', 'inet-webkit'),
    'fields' => array(
        array(
            'id'       => 'wpmb-header-code-editor',
            'type'     => 'code_editor',
            'title' => __('Mã nhúng Header', 'inet-webkit'),
            'class' => 'inet-webkit-row',
            'sanitize' => false,
            'before' => __('<p>Đoạn mã này sẽ được đặt vào <code>&lt;head&gt;</code> trang.</p>', 'inet-webkit'),
            'settings' => array(
                'theme'  => 'monokai',
                'mode'   => 'htmlmixed',
            ),
        ),
        array(
            'id'       => 'inet-webkit-body-scripts-top',
            'type'     => 'code_editor',
            'title' => __('Body Scripts - Top', 'inet-webkit'),
            'class' => 'inet-webkit-row',
            'sanitize' => false,
            'before' => __('<p>Đoạn mã này sẽ được đặt vào sau <code>&lt;body&gt;</code> trang.</p>', 'inet-webkit'),
            'settings' => array(
                'theme'  => 'monokai',
                'mode'   => 'htmlmixed',
            ),
        ),
        array(
            'id'       => 'wpmb-footer-code-editor',
            'type'     => 'code_editor',
            'title' => __('Footer Scripts', 'inet-webkit'),
            'class' => 'inet-webkit-row',
            'sanitize' => false,
            'before' => __('<p>Đoạn mã này sẽ được đặt vào <code>&lt;Footer&gt;</code> trang.</p>', 'inet-webkit'),
            'settings' => array(
                'theme'  => 'monokai',
                'mode'   => 'htmlmixed',
            ),
        )
    )
));
