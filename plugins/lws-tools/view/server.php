    <?php
    $server_info = array(
        __("Environment", "lws-tools") => array(__('Server informations', 'lws-tools'), $_SERVER['SERVER_SOFTWARE'], 'serveur.svg'),
        __("Your IP", "lws-tools") => array(__('Your peripheral\'s IP Address', 'lws-tools'), $_SERVER['HTTP_X_REAL_IP'], 'ip.svg'),
        __("Server Web Port", "lws-tools") => array(__('', 'lws-tools'), $_SERVER['SERVER_PORT'], 'port.svg'),
        __("SSL Certificate (HTTPS)", "lws-tools") => array(__('A SSL Certificate is installed and encode the exchanged data', 'lws-tools'), $is_https, 'ssl.svg'),
        __("Server Name", "lws-tools") => array(__('', 'lws-tools'), $_SERVER['SERVER_NAME'], 'serveur.svg'),
        __("Server IP Address", "lws-tools") => array(__('', 'lws-tools'), $_SERVER['SERVER_ADDR'], 'ip.svg'),
        __("Server Protocol", "lws-tools") => array(__('HTTP Protocol version', 'lws-tools'), $_SERVER['SERVER_PROTOCOL'], 'code.svg'),
        __("PHP", "lws-tools") => array(__('PHP Version installed on your hosting server', 'lws-tools'), phpversion(), 'php.svg'),
        __("WP Debug Mode", "lws-tools") => array(__('WordPress debugging mode activation status', 'lws-tools'), $is_debug, 'code.svg'),
        __("allow_url_fopen", "lws-tools") => array(__('File access functions activation status', 'lws-tools'), $fopen, 'code.svg'),
        __("Server Timezone", "lws-tools") => array(__('', 'lws-tools'), $timezone, 'temps.svg'),
        __("Default Encoding", "lws-tools") => array(__('', 'lws-tools'), $charset, 'code.svg'),
        __("Can upload files", "lws-tools") => array(__('', 'lws-tools'), $can_file_upload, 'upload.svg'),
        __("Max PHP execution time", "lws-tools") => array(__('', 'lws-tools'), $max_exec_time . 's', 'temps.svg"'),
        __("Max files per upload", "lws-tools") => array(__('', 'lws-tools'), $max_file_upload, 'upload.svg'),
        __("Max characters per entry", "lws-tools") => array(__('Maximum amount of characters per post on the website', 'lws-tools'), $max_input_vars, 'code.svg'),
        __("Memory Limit", "lws-tools") => array(__('Max amount of usable RAM on your website', 'lws-tools'), $memory_limit, 'ram.svg'),
        __("Max post size", "lws-tools") => array(__('', 'lws-tools'), $post_max_size, 'poids.svg'),
        __("Max uploaded file size", "lws-tools") => array(__('', 'lws-tools'), $upload_max_filesize, 'poids.svg'),
        __("PHP Memory Usage", "lws-tools") => array(__('Amount of memory used by PHP', 'lws-tools'), $php_memory_usage, 'ram.svg'),
    );
?>
    <h2 class="lws_tk_title_server"><?php esc_html_e('Server informations', 'lws-tools');?>
    </h2>

    <div>
        <?php foreach($server_info as $name => $content) : ?>
        <div class="lws_tk_line_server">
            <div class="lws_tk_line_server_left">
                <img src="<?php echo esc_url(plugins_url('images/' . $content[2], __DIR__))?>"
                    class="lws_tk_line_server_left_image" width="30px" height="30px">
                <div class="lws_tk_line_server_left_text">
                    <p class="lws_tk_line_left_text"><?php echo esc_html($name); ?>
                    </p>
                    <?php if (!empty($content[0])) : ?>
                    <p class="lws_tk_line_left_text_small"><?php echo esc_html($content[0]); ?>
                    </p>
                    <?php endif ?>
                </div>
            </div>
            <div class="lws_tk_line_server_right">
                <div class="lws_tk_line_left_text_right"><?php echo esc_html($content[1]); ?>
                </div>
            </div>
        </div>
        <?php endforeach ?>
    </div>