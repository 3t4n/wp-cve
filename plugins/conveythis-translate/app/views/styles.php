<?php

wp_enqueue_style('conveythis-confetti', plugins_url('../widget/css/confetti.min.css?version=' . CONVEYTHIS_PLUGIN_VERSION, __FILE__) );
wp_enqueue_style('conveythis-dropdown', plugins_url('../widget/css/dropdown.min.css?version=' . CONVEYTHIS_PLUGIN_VERSION, __FILE__) );
wp_enqueue_style('conveythis-input', plugins_url('../widget/css/input.min.css?version=' . CONVEYTHIS_PLUGIN_VERSION, __FILE__) );
wp_enqueue_style('conveythis-transition', plugins_url('../widget/css/transition.min.css?version=' . CONVEYTHIS_PLUGIN_VERSION,__FILE__) );
wp_enqueue_style('conveythis-style', plugins_url('../widget/css/style.min.css?version=' . CONVEYTHIS_PLUGIN_VERSION,__FILE__) );
wp_enqueue_style('conveythis-bootstrap-css', '//cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css');
wp_enqueue_style('conveythis-toastr', '//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css');
wp_enqueue_style('conveythis-slider', plugins_url('../widget/css/slider.min.css?version=' . CONVEYTHIS_PLUGIN_VERSION, __FILE__));

wp_enqueue_script('conveythis-dropdown', plugins_url('../widget/js/dropdown.min.js?version=' . CONVEYTHIS_PLUGIN_VERSION,__FILE__), array(), null, true);
wp_enqueue_script('conveythis-toastr', '//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js');
wp_enqueue_script('conveythis-bootstrap-js', '//cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js');
wp_enqueue_script('conveythis-pusher', '//js.pusher.com/7.2/pusher.min.js');
wp_enqueue_script('conveythis-sweetalert', '//cdn.jsdelivr.net/npm/sweetalert2@11');
wp_enqueue_script('conveythis-transition', plugins_url('../widget/js/transition.min.js',__FILE__), array('jquery'), null, true);
wp_enqueue_script('conveythis-plugin', CONVEYTHIS_JAVASCRIPT_PLUGIN_URL."/conveythis-preview.js", [], '6.3');
wp_enqueue_script('conveythis-slider', plugins_url('../widget/js/slider.min.js?version=' . CONVEYTHIS_PLUGIN_VERSION, __FILE__));

wp_enqueue_script('conveythis-settings', plugins_url('../widget/js/settings.js?version=' . CONVEYTHIS_PLUGIN_VERSION,__FILE__), array('jquery'), null, true);

wp_enqueue_script('conveythis-loader', plugins_url('../widget/js/' . (CONVEYTHIS_LOADER? "loader" : "loader-pause") . '.js?version=' . CONVEYTHIS_PLUGIN_VERSION,__FILE__), array(), null, true);

