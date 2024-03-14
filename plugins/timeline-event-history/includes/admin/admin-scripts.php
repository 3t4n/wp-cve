<?php 

wp_enqueue_media();
wp_enqueue_script('jquery');

// Backend Style 
// wp_enqueue_style( timeline_wp . '-wp-bs', TIMELINE_WP_ASSETS . 'css/timeline-wp-bs.css');

wp_enqueue_style( timeline_wp . '-fontawesome', TIMELINE_WP_ASSETS . 'css/fontawesome/css/fontawesome.min.css');


wp_enqueue_style( timeline_wp . '-sidebarpanel-css', TIMELINE_WP_ASSETS . 'css/sidebarpanel.css');
wp_enqueue_style( timeline_wp . '-main-backend',TIMELINE_WP_ASSETS . 'css/backend-main.css');
wp_enqueue_script( timeline_wp . '-bootstrap-js-backend', TIMELINE_WP_ASSETS . 'js/bootstrap.min.js', array(), '', true );		


//color-picker css n js
wp_enqueue_style('wp-color-picker');
wp_enqueue_script(timeline_wp . '-color-pic',TIMELINE_WP_ASSETS . 'js/color-picker.js', array('wp-color-picker'), false ,true);

wp_enqueue_style( timeline_wp . '-jquery-ui', TIMELINE_WP_ASSETS . '/css/jquery-ui.css');
// wp_enqueue_style( timeline_wp . '-panel-style', TIMELINE_WP_ASSETS . 'css/panel-style.css');
wp_enqueue_script( timeline_wp . '-media-uploads',TIMELINE_WP_ASSETS . 'js/media-upload-script.js',array('media-upload','thickbox','jquery'));

//new style
// wp_enqueue_style('twp_team_pro_sidebarpanel-css', TIMELINE_WP_ASSETS . 'css/sidebarpanel.css');
// wp_enqueue_style('twp_team_pro_panelstyle-css', TIMELINE_WP_ASSETS . 'css/style.css');
//font awesome css

// wp_enqueue_style('twp_team_pro_codemirror-css', TIMELINE_WP_ASSETS . 'codex/codemirror.css');			
// wp_enqueue_style('twp_team_pro_ambiance', TIMELINE_WP_ASSETS . 'codex/ambiance.css');
// wp_enqueue_style( timeline_wp . '-jquery-ui2', TIMELINE_WP_ASSETS . 'css/jquery-ui.css');
// wp_enqueue_style('twp_team-font-awesome', TIMELINE_WP_ASSETS . 'css/font-awesome/css/font-awesome.css');

// wp_enqueue_style( timeline_wp . '-bootstrap', TIMELINE_WP_ASSETS . 'css/bootstrap.css');

// settings

// wp_enqueue_script('twp_team_pro_codemirror-js',TIMELINE_WP_ASSETS . 'codex/codemirror.js',array('jquery'));	
// wp_enqueue_script( 'twp_team_pro_bootstrap-js-front', TIMELINE_WP_ASSETS . 'js/bootstrap.js', array(), '', true );		
// wp_enqueue_script('twp_team_pro_css-js',TIMELINE_WP_ASSETS . 'codex/css.js',array('jquery'));
// wp_enqueue_style('twp_team_pro_settings-css', TIMELINE_WP_ASSETS . 'css/settings.css');

//new custom js
wp_enqueue_script( timeline_wp . '-admin_custom_js', TIMELINE_WP_ASSETS . 'js/custom.js',array('jquery'));