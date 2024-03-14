<?php

class WpSaio
{
    private static $_instance = null;
    private static $saio_messaging_apps;

    public function __construct()
    {
    }
    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    public static function defaultApps()
    {
       
        $apps =  array(
            'facebook-messenger' => array(
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256.55 8C116.52 8 8 110.34 8 248.57c0 72.3 29.71 134.78 78.07 177.94 8.35 7.51 6.63 11.86 8.05 58.23A19.92 19.92 0 0 0 122 502.31c52.91-23.3 53.59-25.14 62.56-22.7C337.85 521.8 504 423.7 504 248.57 504 110.34 396.59 8 256.55 8zm149.24 185.13l-73 115.57a37.37 37.37 0 0 1-53.91 9.93l-58.08-43.47a15 15 0 0 0-18 0l-78.37 59.44c-10.46 7.93-24.16-4.6-17.11-15.67l73-115.57a37.36 37.36 0 0 1 53.91-9.93l58.06 43.46a15 15 0 0 0 18 0l78.41-59.38c10.44-7.98 24.14 4.54 17.09 15.62z"></path></svg>',
                'title' => __('Messenger', WP_SAIO_LANG_PREFIX),
                'shortcode' => 'wpsaio_facebook_messenger',
                'params' => array(
                    'url' => array(
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => __('URL', WP_SAIO_LANG_PREFIX),
                        'desc' => __('Example: http://facebook.com/ninjateam.org/', WP_SAIO_LANG_PREFIX),
                    ),
                ),
            ),
            'whatsapp' => array(
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M412.9,97.1C371,55.1,315.2,32,255.9,32c-122.4,0-222,99.6-222,222c0,39.1,10.2,77.3,29.6,111L32,480l117.7-30.9c32.4,17.7,68.9,27,106.1,27h0.1c122.3,0,224.1-99.6,224.1-222C480,194.8,454.8,139.1,412.9,97.1z M255.9,438.7c-33.2,0-65.7-8.9-94-25.7l-6.7-4l-69.8,18.3l18.6-68.1l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2c0-101.7,82.8-184.5,184.6-184.5c49.3,0,95.6,19.2,130.4,54.1s56.2,81.2,56.1,130.5C442.5,355.9,357.6,438.7,255.9,438.7z M357.1,300.5c-5.5-2.8-32.8-16.2-37.9-18c-5.1-1.9-8.8-2.8-12.5,2.8c-3.7,5.6-14.3,18-17.6,21.8c-3.2,3.7-6.5,4.2-12,1.4c-32.6-16.3-54-29.1-75.5-66c-5.7-9.8,5.7-9.1,16.3-30.3c1.8-3.7,0.9-6.9-0.5-9.7s-12.5-30.1-17.1-41.2c-4.5-10.8-9.1-9.3-12.5-9.5c-3.2-0.2-6.9-0.2-10.6-0.2c-3.7,0-9.7,1.4-14.8,6.9c-5.1,5.6-19.4,19-19.4,46.3s19.9,53.7,22.6,57.4c2.8,3.7,39.1,59.7,94.8,83.8c35.2,15.2,49,16.5,66.6,13.9c10.7-1.6,32.8-13.4,37.4-26.4s4.6-24.1,3.2-26.4C366.3,304.6,362.6,303.2,357.1,300.5z"/></svg>',
                'title' => __('WhatsApp', WP_SAIO_LANG_PREFIX),
                'shortcode' => 'wpsaio_whatsapp',
                'params' => array(
                    'phone' => array(
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => __('Phone Number', WP_SAIO_LANG_PREFIX),
                        'desc' => __('Example: +1 (800) 123-4567', WP_SAIO_LANG_PREFIX),
                    ),
                ),
            ),
            'snapchat' => array(
                'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fab" data-icon="snapchat-ghost" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-snapchat-ghost fa-w-16 fa-2x"><path fill="currentColor" d="M510.846 392.673c-5.211 12.157-27.239 21.089-67.36 27.318-2.064 2.786-3.775 14.686-6.507 23.956-1.625 5.566-5.623 8.869-12.128 8.869l-.297-.005c-9.395 0-19.203-4.323-38.852-4.323-26.521 0-35.662 6.043-56.254 20.588-21.832 15.438-42.771 28.764-74.027 27.399-31.646 2.334-58.025-16.908-72.871-27.404-20.714-14.643-29.828-20.582-56.241-20.582-18.864 0-30.736 4.72-38.852 4.72-8.073 0-11.213-4.922-12.422-9.04-2.703-9.189-4.404-21.263-6.523-24.13-20.679-3.209-67.31-11.344-68.498-32.15a10.627 10.627 0 0 1 8.877-11.069c69.583-11.455 100.924-82.901 102.227-85.934.074-.176.155-.344.237-.515 3.713-7.537 4.544-13.849 2.463-18.753-5.05-11.896-26.872-16.164-36.053-19.796-23.715-9.366-27.015-20.128-25.612-27.504 2.437-12.836 21.725-20.735 33.002-15.453 8.919 4.181 16.843 6.297 23.547 6.297 5.022 0 8.212-1.204 9.96-2.171-2.043-35.936-7.101-87.29 5.687-115.969C158.122 21.304 229.705 15.42 250.826 15.42c.944 0 9.141-.089 10.11-.089 52.148 0 102.254 26.78 126.723 81.643 12.777 28.65 7.749 79.792 5.695 116.009 1.582.872 4.357 1.942 8.599 2.139 6.397-.286 13.815-2.389 22.069-6.257 6.085-2.846 14.406-2.461 20.48.058l.029.01c9.476 3.385 15.439 10.215 15.589 17.87.184 9.747-8.522 18.165-25.878 25.018-2.118.835-4.694 1.655-7.434 2.525-9.797 3.106-24.6 7.805-28.616 17.271-2.079 4.904-1.256 11.211 2.46 18.748.087.168.166.342.239.515 1.301 3.03 32.615 74.46 102.23 85.934 6.427 1.058 11.163 7.877 7.725 15.859z"></path></svg>',
                'title' => __('Snapchat', WP_SAIO_LANG_PREFIX),
                'shortcode' => 'wpsaio_snapchat',
                'params' => array(
                    'username' => array(
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => __('Username', WP_SAIO_LANG_PREFIX),
                        'desc' => __('Example: ninjateam', WP_SAIO_LANG_PREFIX),
                    ),
                ),
            ),
            'line' => array(
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><path d="M49.8,9.3C25.7,9.3,6.1,25.2,6.1,44.7c0,17.5,15.5,32.2,36.5,35c1.4,0.3,3.4,0.9,3.9,2.2c0.4,1.1,0.3,2.8,0.1,4c0,0-0.5,3.1-0.6,3.7c-0.2,1.1-0.9,4.3,3.8,2.3c4.7-2,25.2-14.8,34.3-25.4c3.2-3.5,5.5-7,7-10.6s2.3-7.3,2.3-11.2C93.5,25.2,73.9,9.3,49.8,9.3z M34.4,55.2c0,0.5-0.4,0.8-0.9,0.8H21.3c-0.2,0-0.4-0.1-0.6-0.2c0,0,0,0,0,0l0,0c-0.2-0.2-0.2-0.4-0.2-0.6v-19c0-0.5,0.4-0.9,0.8-0.9h3.1c0.5,0,0.8,0.4,0.8,0.9v15.1h8.3c0.5,0,0.9,0.4,0.9,0.8V55.2z M41.8,55.2c0,0.5-0.4,0.8-0.8,0.8h-3.1c-0.5,0-0.8-0.4-0.8-0.8v-19c0-0.5,0.4-0.9,0.8-0.9h3.1c0.5,0,0.8,0.4,0.8,0.9V55.2z M62.9,55.2c0,0.5-0.4,0.8-0.9,0.8H59c-0.1,0-0.2,0-0.2,0c0,0,0,0,0,0c0,0,0,0-0.1,0c0,0,0,0,0,0c0,0,0,0,0,0c0,0,0,0,0,0c0,0,0,0,0,0c0,0,0,0,0,0c0,0,0,0,0,0c-0.1,0-0.1-0.1-0.2-0.2l-8.7-11.8v11.3c0,0.5-0.4,0.8-0.8,0.8h-3.1c-0.5,0-0.8-0.4-0.8-0.8v-19c0-0.5,0.4-0.9,0.8-0.9h3.1c0,0,0,0,0,0h0.1c0,0,0,0,0,0c0,0,0,0,0,0s0,0,0,0c0,0,0,0,0.1,0c0,0,0,0,0,0c0,0,0,0,0,0c0,0,0,0,0,0c0,0,0,0,0,0s0,0,0,0s0,0,0,0c0,0,0,0,0,0c0,0,0,0,0,0c0,0,0,0,0,0c0,0,0,0,0.1,0.1c0,0,0,0,0,0c0,0,0,0.1,0.1,0.1l8.7,11.8V36.1c0-0.5,0.4-0.9,0.9-0.9H62c0.5,0,0.9,0.4,0.9,0.9V55.2z M79.8,39.2c0,0.5-0.4,0.9-0.8,0.9h-8.3v3.2h8.3c0.5,0,0.8,0.4,0.8,0.9v3.1c0,0.5-0.4,0.9-0.8,0.9h-8.3v3.2h8.3c0.5,0,0.8,0.4,0.8,0.8v3.1c0,0.5-0.4,0.8-0.8,0.8H66.7c-0.2,0-0.4-0.1-0.6-0.2c0,0,0,0,0,0c0,0,0,0,0,0c-0.1-0.2-0.2-0.4-0.2-0.6v-19c0-0.2,0.1-0.4,0.2-0.6c0,0,0,0,0,0c0,0,0,0,0,0c0.2-0.1,0.4-0.2,0.6-0.2h12.2c0.5,0,0.8,0.4,0.8,0.9V39.2z"/></svg>',
                'title' => __('Line', WP_SAIO_LANG_PREFIX),
                'shortcode' => 'wpsaio_line',
                'params' => array(
                    'url' => array(
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => __('QR Code URL', WP_SAIO_LANG_PREFIX),
                        'desc' => __('Example: http://line.me/ti/p/xxx', WP_SAIO_LANG_PREFIX),
                    ),
                ),
            ),
            'viber' => array(
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M444 49.9C431.3 38.2 379.9.9 265.3.4c0 0-135.1-8.1-200.9 52.3C27.8 89.3 14.9 143 13.5 209.5c-1.4 66.5-3.1 191.1 117 224.9h.1l-.1 51.6s-.8 20.9 13 25.1c16.6 5.2 26.4-10.7 42.3-27.8 8.7-9.4 20.7-23.2 29.8-33.7 82.2 6.9 145.3-8.9 152.5-11.2 16.6-5.4 110.5-17.4 125.7-142 15.8-128.6-7.6-209.8-49.8-246.5zM457.9 287c-12.9 104-89 110.6-103 115.1-6 1.9-61.5 15.7-131.2 11.2 0 0-52 62.7-68.2 79-5.3 5.3-11.1 4.8-11-5.7 0-6.9.4-85.7.4-85.7-.1 0-.1 0 0 0-101.8-28.2-95.8-134.3-94.7-189.8 1.1-55.5 11.6-101 42.6-131.6 55.7-50.5 170.4-43 170.4-43 96.9.4 143.3 29.6 154.1 39.4 35.7 30.6 53.9 103.8 40.6 211.1zm-139-80.8c.4 8.6-12.5 9.2-12.9.6-1.1-22-11.4-32.7-32.6-33.9-8.6-.5-7.8-13.4.7-12.9 27.9 1.5 43.4 17.5 44.8 46.2zm20.3 11.3c1-42.4-25.5-75.6-75.8-79.3-8.5-.6-7.6-13.5.9-12.9 58 4.2 88.9 44.1 87.8 92.5-.1 8.6-13.1 8.2-12.9-.3zm47 13.4c.1 8.6-12.9 8.7-12.9.1-.6-81.5-54.9-125.9-120.8-126.4-8.5-.1-8.5-12.9 0-12.9 73.7.5 133 51.4 133.7 139.2zM374.9 329v.2c-10.8 19-31 40-51.8 33.3l-.2-.3c-21.1-5.9-70.8-31.5-102.2-56.5-16.2-12.8-31-27.9-42.4-42.4-10.3-12.9-20.7-28.2-30.8-46.6-21.3-38.5-26-55.7-26-55.7-6.7-20.8 14.2-41 33.3-51.8h.2c9.2-4.8 18-3.2 23.9 3.9 0 0 12.4 14.8 17.7 22.1 5 6.8 11.7 17.7 15.2 23.8 6.1 10.9 2.3 22-3.7 26.6l-12 9.6c-6.1 4.9-5.3 14-5.3 14s17.8 67.3 84.3 84.3c0 0 9.1.8 14-5.3l9.6-12c4.6-6 15.7-9.8 26.6-3.7 14.7 8.3 33.4 21.2 45.8 32.9 7 5.7 8.6 14.4 3.8 23.6z"></path></svg>',
                'title' => __('Viber', WP_SAIO_LANG_PREFIX),
                'shortcode' => 'wpsaio_viber',
                'params' => array(
                    'account' => array(
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => __('Public Account', WP_SAIO_LANG_PREFIX),
                        'desc' => __('Example: NinjaTeam', WP_SAIO_LANG_PREFIX),
                    ),
                ),
            ),
            'phone' => array(
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M444.6,338.7l-87.5-37.5c-7.7-3.3-16.6-1.1-21.9,5.4l-38.8,47.3c-60.8-28.7-109.8-77.6-138.4-138.4l47.3-38.8c6.5-5.3,8.7-14.2,5.4-21.9l-37.5-87.5c-3.6-8.3-12.6-12.9-21.5-10.9L70.5,75.2C62,77.2,56,84.8,56,93.5C56,293.9,218.4,456,418.5,456c8.7,0,16.3-6,18.3-14.5l18.8-81.3C457.5,351.3,452.9,342.3,444.6,338.7L444.6,338.7z"/></svg>',
                'title' => __('Phone', WP_SAIO_LANG_PREFIX),
                'shortcode' => 'wpsaio_phone',
                'params' => array(
                    'phone_number' => array(
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => __('Phone Number', WP_SAIO_LANG_PREFIX),
                        'desc' => __('Example: +1-800-123-4567', WP_SAIO_LANG_PREFIX),
                    ),
                ),
            ),
            'email' => array(
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M448.4,205.1c3-2.4,7.6-0.2,7.6,3.7v159.8c0,20.7-16.8,37.5-37.5,37.5h-325C72.8,406,56,389.2,56,368.5V208.8c0-3.9,4.5-6.1,7.6-3.7c17.5,13.6,40.7,30.9,120.4,88.7c16.5,12,44.3,37.3,72,37.2c27.9,0.2,56.2-25.6,72.1-37.2C407.8,236,430.9,218.7,448.4,205.1z M256,306c18.1,0.3,44.2-22.8,57.3-32.3c103.7-75.2,111.6-81.8,135.5-100.5c4.5-3.5,7.2-9,7.2-14.8v-14.8c0-20.7-16.8-37.5-37.5-37.5h-325C72.8,106,56,122.8,56,143.5v14.8c0,5.8,2.7,11.2,7.2,14.8c23.9,18.7,31.8,25.3,135.5,100.5C211.8,283.2,237.9,306.3,256,306L256,306z"/></svg>',
                'title' => __('Email', WP_SAIO_LANG_PREFIX),
                'shortcode' => 'wpsaio_mail',
                'params' => array(
                    'email' => array(
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => __('Email', WP_SAIO_LANG_PREFIX),
                        'desc' => __('Example: support@ninjateam.org', WP_SAIO_LANG_PREFIX),
                    ),
                ),
            ),
            'telegram' => array(
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 105 95"><path d="M15.5,45.4C37,36,51.3,29.9,58.5,26.9c20.5-8.5,24.7-10,27.5-10c0.6,0,2,0.1,2.9,0.9c0.7,0.6,1,1.4,1,2c0.1,0.6,0.2,1.9,0.1,2.9c-1.1,11.6-5.9,39.9-8.3,53c-1,5.5-3.1,7.4-5,7.6c-4.3,0.4-7.5-2.8-11.7-5.5c-6.5-4.2-10.1-6.9-16.4-11c-7.3-4.8-2.6-7.4,1.6-11.7C51.1,53.7,70,36.5,70.4,35c0-0.2,0.1-0.9-0.3-1.3c-0.4-0.4-1.1-0.3-1.5-0.1c-0.7,0.1-11,7-31.1,20.5c-2.9,2-5.6,3-8,3c-2.6-0.1-7.7-1.5-11.5-2.7c-4.6-1.5-8.3-2.3-8-4.8C10.2,48.1,12,46.8,15.5,45.4z"/></svg>',
                'title' => __('Telegram', WP_SAIO_LANG_PREFIX),
                'shortcode' => 'wpsaio_telegram',
                'params' => array(
                    'username' => array(
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => __('Telegram account', WP_SAIO_LANG_PREFIX),
                        'desc' => __('Example: ninjateam', WP_SAIO_LANG_PREFIX),
                    ),
                ),
            ),
            'skype' => array(

                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M456.7,299.8c2.9-14,4.7-28.9,4.7-43.8c0-113.5-91.9-205.3-205.3-205.3c-14.9,0-29.7,1.7-43.8,4.7C193.3,40.7,169.7,32,144,32C82.2,32,32,82.2,32,144c0,25.7,8.7,49.3,23.3,68.2c-2.9,14-4.7,28.9-4.7,43.8c0,113.5,91.9,205.3,205.3,205.3c14.9,0,29.7-1.7,43.8-4.7c19,14.6,42.6,23.3,68.2,23.3c61.8,0,112-50.2,112-112C480,342.3,471.3,318.7,456.7,299.8L456.7,299.8z M262.1,391.3c-65.6,0-120.5-29.2-120.5-65c0-16,9-30.6,29.5-30.6c31.2,0,34.1,44.9,88.1,44.9c25.7,0,42.3-11.4,42.3-26.3c0-18.7-16-21.6-42-28c-62.5-15.4-117.8-22-117.8-87.2c0-59.2,58.6-81.1,109.1-81.1c55.1,0,110.8,21.9,110.8,55.4c0,16.9-11.4,31.8-30.3,31.8c-28.3,0-29.2-33.5-75-33.5c-25.7,0-42,7-42,22.5c0,19.8,20.8,21.8,69.1,33c41.4,9.3,90.7,26.8,90.7,77.6C374.1,363.9,317,391.3,262.1,391.3L262.1,391.3z"/></svg>',
                'title' => __('Skype', WP_SAIO_LANG_PREFIX),
                'shortcode' => 'wpsaio_skype',
                'params' => array(
                    'username' => array(
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => __('Skype account', WP_SAIO_LANG_PREFIX),
                        'desc' => __('Example: ninjateam', WP_SAIO_LANG_PREFIX),
                    ),
                ),
            ),
            'zalo' => array (
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 26 26"><path d="M24.65,5.17c-0.87-1.65-2.17-2.94-3.82-3.82C19.18,0.47,17.32,0,14.15,0h-2.31C9.13,0,7.37,0.34,5.9,0.99
			C5.82,1.06,5.74,1.14,5.66,1.21C1.33,5.38,1,14.43,4.67,19.34c0,0.01,0.01,0.01,0.01,0.02c0.57,0.83,0.02,2.29-0.83,3.15
			c-0.14,0.13-0.09,0.21,0.12,0.23c1.21,0.13,2.73-0.21,3.8-0.73c4.67,2.58,11.97,2.46,16.39-0.37c0.17-0.26,0.33-0.52,0.48-0.8
			c0.88-1.65,1.35-3.5,1.35-6.68v-2.3C26,8.68,25.53,6.82,24.65,5.17z M10.65,14.74c0,0.24-0.2,0.44-0.44,0.44H5.43V14.9
			c0-0.34,0.08-0.48,0.19-0.64l3.47-4.3H5.57V8.87h5.07v0.15c0,0.29-0.04,0.52-0.23,0.8l-0.02,0.03c-0.04,0.05-0.13,0.16-0.18,0.22
			l-3.26,4.09h3.7V14.74z M15.87,15.18h-0.58c-0.24,0-0.44-0.2-0.44-0.43v-0.03c-0.41,0.3-0.91,0.49-1.47,0.49
			c-1.36,0-2.47-1.11-2.47-2.47c0-1.36,1.11-2.47,2.47-2.47c0.55,0,1.05,0.18,1.47,0.49v-0.35h1.02V15.18z M17.82,15.18H17.1
			c-0.21,0-0.37-0.17-0.37-0.37V8.87h1.09V15.18z M21.04,15.22c-1.37,0-2.49-1.12-2.49-2.49c0-1.37,1.12-2.49,2.49-2.49
			c1.37,0,2.49,1.12,2.49,2.49C23.53,14.1,22.41,15.22,21.04,15.22z"/></svg>',
                'title' => __('Zalo', WP_SAIO_LANG_PREFIX),
                'shortcode'=> 'wpsaio_zalo',
                'params' => array(
                    'username' => array(
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => __('Zalo account', WP_SAIO_LANG_PREFIX),
                        'desc' => __('Example: ninjateam', WP_SAIO_LANG_PREFIX),
                    ),
                ),
            ),
            'kakaotalk' => array (
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 26 26"><path d="M13,1L13,1C5.82,1,0,5.59,0,11.25c0,3.66,2.43,6.87,6.09,8.68c-0.2,0.69-1.28,4.42-1.32,4.71c0,0-0.03,0.22,0.12,0.3
		c0.14,0.08,0.31,0.02,0.31,0.02c0.41-0.06,4.74-3.1,5.49-3.63c0.75,0.11,1.52,0.16,2.31,0.16c7.18,0,13-4.59,13-10.25
		C26,5.59,20.18,1,13,1z M6.56,14.12c0,0.4-0.34,0.72-0.75,0.72c-0.41,0-0.75-0.32-0.75-0.72V9.66H3.89c-0.41,0-0.74-0.33-0.74-0.73
		c0-0.4,0.33-0.73,0.74-0.73h3.84c0.41,0,0.74,0.33,0.74,0.73c0,0.4-0.33,0.73-0.74,0.73H6.56V14.12z M12.87,14.72
		c-0.15,0.07-0.31,0.1-0.48,0.1l0,0c-0.31,0-0.55-0.13-0.62-0.33l-0.37-0.97l-2.29,0l-0.37,0.97c-0.07,0.2-0.31,0.33-0.62,0.33
		c-0.16,0-0.33-0.04-0.48-0.1c-0.21-0.1-0.41-0.36-0.18-1.07l1.79-4.72c0.13-0.36,0.51-0.73,1-0.74c0.49,0.01,0.87,0.38,1,0.74
		l1.79,4.72C13.27,14.36,13.07,14.62,12.87,14.72z M16.66,14.72h-2.41c-0.4,0-0.72-0.31-0.72-0.69V8.94c0-0.41,0.34-0.75,0.77-0.75
		c0.42,0,0.77,0.34,0.77,0.75v4.41h1.59c0.4,0,0.72,0.31,0.72,0.69C17.38,14.41,17.05,14.72,16.66,14.72z M22.53,14.16
		c-0.03,0.2-0.13,0.38-0.29,0.49c-0.13,0.1-0.29,0.15-0.45,0.15c-0.24,0-0.46-0.11-0.6-0.3l-1.76-2.33l-0.26,0.26v1.64
		c0,0.41-0.34,0.75-0.75,0.75v0c-0.41,0-0.75-0.34-0.75-0.75V8.94c0-0.41,0.34-0.75,0.75-0.75s0.75,0.34,0.75,0.75v1.61l2.09-2.09
		c0.11-0.11,0.26-0.17,0.42-0.17c0.19,0,0.38,0.08,0.52,0.22c0.13,0.13,0.21,0.3,0.22,0.48c0.01,0.18-0.05,0.34-0.17,0.46
		l-1.71,1.71l1.85,2.45C22.5,13.76,22.56,13.96,22.53,14.16z"/></svg>',
                'title' => __('Kakaotalk', WP_SAIO_LANG_PREFIX),
                'shortcode'=> 'wpsaio_kakaotalk',
                'params' => array(
                    'username' => array(
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => __('Kakaotalk account', WP_SAIO_LANG_PREFIX),
                        'desc' => __('Example: ninjateam', WP_SAIO_LANG_PREFIX),
                    ),
                ),
            ),
            'wechat' => array (
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 26 26"><path d="M23.31,21.19c1.63-1.21,2.69-2.98,2.69-4.96c0-3.61-3.54-6.52-7.86-6.52s-7.8,2.91-7.8,6.52
		s3.47,6.52,7.79,6.52c0.86,0,1.72-0.12,2.55-0.35c0.21-0.06,0.44-0.04,0.64,0.07l1.77,0.99c0.21,0.14,0.43,0,0.35-0.28l-0.43-1.54
		C23.02,21.44,23.13,21.27,23.31,21.19z M15.51,15.17c-0.09,0.02-0.18,0.03-0.27,0.03c-0.59-0.03-1.04-0.53-1-1.12
		c0.03-0.54,0.46-0.98,1-1c0.09,0,0.18,0.01,0.27,0.03c0.57,0.15,0.91,0.73,0.76,1.3C16.17,14.78,15.88,15.07,15.51,15.17z
		 M20.76,15.17c-0.09,0.02-0.18,0.03-0.27,0.03c-0.59,0.03-1.09-0.42-1.12-1c-0.03-0.59,0.42-1.09,1-1.12c0.04,0,0.08,0,0.11,0
		c0.09,0,0.18,0.01,0.27,0.03c0.57,0.15,0.91,0.73,0.76,1.3C21.41,14.78,21.13,15.07,20.76,15.17z M9.35,2.49
		C4.19,2.49,0,6.03,0,10.35c0,2.34,1.2,4.46,3.19,5.88c0.25,0.14,0.36,0.44,0.28,0.71l-0.5,1.7C2.92,18.81,3.01,19,3.18,19.06
		c0.07,0.02,0.15,0.02,0.22,0l2.12-1.2c0.23-0.15,0.51-0.21,0.78-0.14c1.17,0.35,2.39,0.5,3.61,0.43c-1.7-5.95,4.18-9.35,8.71-9.07
		C17.92,5.38,14.03,2.49,9.35,2.49L9.35,2.49z M6.23,9.08c-0.1,0.02-0.2,0.03-0.31,0.03C5.22,9.15,4.62,8.6,4.59,7.9
		C4.55,7.19,5.1,6.6,5.8,6.56c0.04,0,0.08,0,0.13,0c0.1,0,0.21,0.01,0.31,0.04c0.68,0.18,1.09,0.87,0.92,1.55
		C7.03,8.6,6.68,8.95,6.23,9.06V9.08z M12.54,9.08c-0.1,0.02-0.2,0.03-0.31,0.03c-0.7,0.03-1.3-0.51-1.34-1.21
		c-0.03-0.7,0.51-1.3,1.21-1.34c0.04,0,0.08,0,0.13,0c0.1,0,0.21,0.01,0.31,0.04c0.68,0.18,1.09,0.88,0.9,1.56
		c-0.12,0.44-0.46,0.79-0.9,0.9V9.08z"/></svg>',
                'title' => __('Wechat', WP_SAIO_LANG_PREFIX),
                'shortcode'=> 'wpsaio_wechat',
                'params' => array(
                    'email' => array(
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => __('Wechat account', WP_SAIO_LANG_PREFIX),
                        'desc' => __('Example: support@ninjateam.org', WP_SAIO_LANG_PREFIX),
                    ),
                ),
            ),
            'custom-app' => array(
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M344.476 105.328L1.004 448.799 64.205 512l343.472-343.471-63.201-63.201zm-53.882 96.464l53.882-53.882 20.619 20.619-53.882 53.882-20.619-20.619zM410.885 78.818l37.657-37.656 21.29 21.29-37.656 37.657zM405.99 274.144l21.29-21.29 38.367 38.366-21.29 21.29zM198.501 66.642l21.29-21.29 38.13 38.127-21.292 21.291zM510.735 163.868h-54.289v30.111H510.996v-30.111zM317.017.018v54.289h30.111V0z"/></svg>',
                'title' => __('Custom App', WP_SAIO_LANG_PREFIX),
                'shortcode' => 'wpsaio_custom_app',
                'params' => array(
                    'url' => array(
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => __('Enter your URL', WP_SAIO_LANG_PREFIX),
                        'desc' => __('Example: https//yourapp.com/your-username'),
                        'desc-upload-icon' => __('Your app icon/image'),
                        'custom-app-title' => '',
                        'url-icon' => '',
                        'color-icon' => '#007cc4'
                    ),
                ),
            ),
        );
        $get_default_apps = get_option('njt_wp_saio_default_apps', $apps);
        return $get_default_apps;
    }
    public static function renderForm($apps = null)
    {
        if (is_null($apps)) {
            $apps = self::getMessagingApps();
        }

        $get_list_app = get_option('njt_wp_saio', array());
        $html = array();
        foreach ($apps as $k => $app) {

            $color_icon ='';
            
            if (str_contains($k, 'custom-app-')) {
                preg_match('/\d+/', $k, $matches);
                $color_icon = isset($get_list_app[$k]['params']['color-icon']) && !empty($matches) ? $get_list_app[$k]['params']['color-icon'] : '';
            }
           
            $data = array(
                'icon' => (str_contains($k, 'custom-app-') && $app['params']['url']['url-icon'] !== '') 
                    ? '<img src="' . esc_url($app['params']['url']['url-icon']) . '" alt="Image Preview">'
                    : $app['icon'],
                'title' => str_contains($k, 'custom-app') && $app['params']['url']['custom-app-title'] !== '' ? $app['params']['url']['custom-app-title'] : $app['title'],
                'inputs' => '',
                'type' => $k,
                'state' => isset($app['state']) ? $app['state'] : '',
                'color_icon' => $color_icon
            );
            foreach ($app['params'] as $k2 => $input) {
                $data['inputs'] .= self::renderInput($k, $k2, $input);
            }
            // $data['inputs'] .= self::renderDeleteBtn();
            $html[$k] = WpSaioView::load('admin.app.add-input', $data);
        }
        return $html;
    }
    public static function renderShortcodes()
    {
        // $from_db = get_option('njt_wp_saio', null);
        $get_list_app = get_option('njt_wp_saio', array());
        $from_db = array_filter(
            $get_list_app,
            function ($e) {
                return $e['params']['state'] === '';
            }
        );
        if (is_null($from_db)) {
            return array();
        }

        $shortcodes = array();
        $apps = self::getMessagingApps();

        foreach ($apps as $k => $v) {
            if (in_array($k, array_keys($from_db))) {
                $atts = array();
                foreach ($from_db[$k]['params'] as $k2 => $v2) {
                    $atts[] = $k2 . '="' . esc_attr($v2) . '"';
                }
                $atts = implode(' ', $atts);
                $shortcodes[$k] = "[{$v['shortcode']} {$atts}]";
            }
        }
        return $shortcodes;
    }
    public static function renderInput($app, $key, $input)
    {
        $html = '';
        switch ($input['type']) {
            case 'text':
                $html = '<div class="saio-input">
                <input class="regular-text" type="text" name="data[' . $app . '][' . $key . ']" value="' . ((isset($input['value'])) ? esc_attr($input['value']) : '') . '" placeholder="' . esc_attr($input['placeholder']) . '" ' . (($input['required']) ? 'required="required"' : '') . ' data-appname="' . $app . '" data-appkey="' . $key . '"/>
                <span class="saio-input-hint">' . $input['desc'] . '</span>
                </div>
                ';
                break;
        }
        if (str_contains($app, 'custom-app')) {
            $replace_text = str_replace('-', '_', $app);
            $id = "wpsaio_button_{$replace_text}_icon";
            $html .= '<div class="saio-input saio-custom-app-upload-icon">
            <input type="text" name="wpsaio_button_icon" id="'. $id .'" value="' . ((isset($input['url-icon'])) ? esc_attr($input['url-icon']) : '') . '" class="regular-text" />
            <a href="javascript:void(0)" class="button wp_saio_choose_image_btn saio-custom-app-upload-icon-btn" data-target="#'.$id .'">Upload Icon</a>
                    <span class="saio-input-hint">' . ((isset($input['desc-upload-icon'])) ? esc_attr($input['desc-upload-icon']) : '') . '</span>
                    </div>';
            $html .= '<div><input type="text" name="wpsaio_button_color" value="' . ((isset($input['color-icon'])) ? esc_attr($input['color-icon']) : '') . '" id="wpsaio__'.$replace_text.'_button_color" class="regular-text wp_saio_colorpicker" /></div>';
        }
        return $html;
    }
    // public static function renderDeleteBtn()
    // {
    //     return WpSaioView::load('admin.app.remove-input-btn');
    // }
    public static function getMessagingApps()
    {
        self::$saio_messaging_apps = self::defaultApps();
        return apply_filters('wp-saio-messaging-apps', self::$saio_messaging_apps);
    }
    function remove_app_disabled($e)
    {
        return $e['params']['state'] === 'app_off';
    }
    public static function generatePanel()
    {
        if (!is_admin()) {
            return false;
        }
        $apps = self::getMessagingApps();
        $get_list_app = get_option('njt_wp_saio', array());
        
        $added_apps = array_keys(array_filter(
            $get_list_app,
            function ($e) {
                return (!isset($e['params']['state']) || $e['params']['state'] === '');
            }
        ));

        $remained_apps = array_keys($get_list_app);

?>
        <div class="wp_saio_panel_wrap">
            <p><?php echo __('Select app icons to add them to your list.', WP_SAIO_LANG_PREFIX) ?></p>
            <?php
            foreach ($apps as $k => $v) {
                 // Check if the custom URL icon is available and use it
                if (str_contains($k, 'custom-app-') && isset($get_list_app[$k]['params']['url-icon']) && $get_list_app[$k]['params']['url-icon'] != '') {
                    preg_match('/\d+/', $k, $matches);
                    $icon = '<img src="' . esc_url($get_list_app[$k]['params']['url-icon']) . '" alt="Custom Icon">';
                } else {
                    $icon = $v['icon'];
                }

                if (str_contains($k, 'custom-app-') && !in_array($k, $remained_apps)) {
                    continue;
                } 
               
                $bg_color = isset($get_list_app[$k]['params']['color-icon']) && !empty($matches) ? 'background:'.$get_list_app[$k]['params']['color-icon'] : '';
            ?>
                <div class="wp-saio-app" data-appname="<?php echo esc_attr($k) ?>">
                    <button style="<?php echo esc_attr($bg_color); ?>" class="saio-btn-app wp-saio-icon wp-saio-icon-<?php echo esc_attr($k); ?><?php echo ((in_array($k, $added_apps)) ? ' active' : ' disabled'); ?>" type="button">
                        <?php echo $icon; ?>
                    </button>
                </div>
            <?php
            }
            ?>
        </div>
        <div style="margin-bottom: 25px">
            <a href="javascript:void(0)" class="button" id="add-new-custom-app"><?php echo __('Add New App', WP_SAIO_LANG_PREFIX) ?></a>
        </div>
        <div class="wp_saio_fields_wrap">
            <!-- <div class="wp-saio-app_text">Select app icons to add them to your list</div> -->
            <?php self::getExistData(); ?>
        </div>
        <div class="wp_saio_panel_btn-wrap">
            <button class="wpsaio-save button button-primary button-choose-apps"><?php echo __('Save Changes', WP_SAIO_LANG_PREFIX) ?><i class="dashicons dashicons-update-alt"></i></button>
        </div>
<?php
    }
    public static function getExistData()
    {
        $from_db = get_option('njt_wp_saio', null);
        if (is_null($from_db)) {
            return '';
        }
        $keys_from_db = array_keys($from_db);
        $apps = self::getMessagingApps();

        /*
         * Unset the apps not used and set value to the apps used.
         */
        $new_apps = array();
        foreach ($from_db as $k => $app) {
            if (isset($apps[$k]['params'])) {
                foreach ($apps[$k]['params'] as $k2 => $input) {
                    $new_apps[$k] = $apps[$k];
                    $new_apps[$k]['params'][$k2]['value'] = $app['params'][$k2];
                    $new_apps[$k]['params'][$k2]['custom-app-title'] = isset($app['params']['custom-app-title']) ? $app['params']['custom-app-title'] : '';
                    $new_apps[$k]['params'][$k2]['url-icon'] = isset($app['params']['url-icon']) ? $app['params']['url-icon'] : '';
                    $new_apps[$k]['params'][$k2]['color-icon'] = isset($app['params']['color-icon']) ? $app['params']['color-icon'] : '';
                    $new_apps[$k]['state'] = isset($app['params']['state']) ? $app['params']['state'] : '';
                }
            }
        }

        $html = self::renderForm($new_apps);
        echo implode('', $html);
    }
    public static function generateFrontendButtons()
    {
        // $from_db = get_option('njt_wp_saio', array());
        $get_list_app = get_option('njt_wp_saio', array());
        $from_db = array_filter(
            $get_list_app,
            function ($e) {
                return $e['params']['state'] === '';
            }
        );
        $from_db = array_reverse($from_db);
        if (is_null($from_db)) {
            return '';
        }
        $apps = self::getMessagingApps();
        $html = '';
        foreach ($from_db as $k => $v) {
            $data = array(
                'app' => $k,
                'title' => apply_filters('wpsaio-' . $k . '-home-title', $apps[$k]['title']),
                'content' => reset($v['params']),
                'custom-app-title' => $v['params']['custom-app-title'],
                'url-icon' => $v['params']['url-icon'],
                'color-icon' => $v['params']['color-icon']
            );
            $html .= WpSaioView::load('home.button', $data);
        }
        return $html;
    }
}
