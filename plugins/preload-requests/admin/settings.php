<?php
if (!class_exists('gclpr_preload_requests_settings')) {
    $gclpr_options = get_option('gclpr_options');
    $post_types = get_post_types(array('public' => true),'objects');
    unset($post_types['attachment']);
    class gclpr_preload_requests_settings
    {
        public function __construct(){
            add_action( 'admin_init', array($this,'register_settings_init'));
            add_action('admin_menu', array($this,'register_admin_page'));
        }

        public function register_admin_page() {
            add_menu_page(
                'Preload Requests',
                'Preload Requests',
                'manage_options',
                'preload-requests',
                array($this, 'preload_requests_admin_callback'),
                'dashicons-image-rotate'
            );
        }

        /* setting html */
        public function preload_requests_admin_callback() {

            if(! current_user_can( 'administrator' ) && !current_user_can( 'manage_options' ) ){
                wp_die( __('You do not have sufficient permissions to access this page.', 'preload-requests'));
            }

            $default_tab = null;
            $tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : $default_tab;
            ?>
                <div class="gclpr-preload-requests-form">
                    <div class="wrap">
                        <h2 class="gclpr-h2-title"><?php _e('Preload Requests','preload-requests') ?></h2>
                        <?php settings_errors(); ?>
                        <nav class="nav-tab-wrapper gclpr-nav-tab">
                            <a href="?page=preload-requests" class="nav-tab <?php if($tab == null):?>nav-tab-active<?php endif; ?>"><?php esc_html_e('Fonts','preload-requests'); ?></a>
                            <a href="?page=preload-requests&tab=css" class="nav-tab <?php if($tab == 'css'):?>nav-tab-active<?php endif; ?>"><?php esc_html_e('CSS','preload-requests'); ?></a>
                            <a href="?page=preload-requests&tab=javascript" class="nav-tab <?php if($tab == 'javascript'):?>nav-tab-active<?php endif; ?>"><?php esc_html_e('Javascript','preload-requests'); ?></a>
                            <a href="?page=preload-requests&tab=images" class="nav-tab <?php if($tab == 'images'):?>nav-tab-active<?php endif; ?>"><?php esc_html_e('Images','preload-requests'); ?></a>
                            <a href="?page=preload-requests&tab=video" class="nav-tab <?php if($tab == 'video'):?>nav-tab-active<?php endif; ?>"><?php esc_html_e('Video','preload-requests'); ?></a>
                        </nav>

                        <form method="post" action="options.php">
                            <?php settings_fields( 'gclpr-setting-options' ); ?>
                            <div class="gclpr-form">
                                <div class="gclpr-fonts-form gclpr-sec <?php if($tab == null):?>gclpr-active-sec<?php endif; ?>">
                                    <?php 
                                        do_settings_sections( 'gclpr_fonts_section' ); 
                                        submit_button( 'Save Settings' );
                                    ?>
                                </div>

                                <div class="gclpr-styles-form gclpr-sec <?php if($tab == 'css'):?>gclpr-active-sec<?php endif; ?>">
                                    <?php 
                                        do_settings_sections( 'gclpr_styles_section' ); 
                                        submit_button( 'Save Settings' );
                                    ?>
                                </div>
                                
                                <div class="gclpr-scripts-form gclpr-sec <?php if($tab == 'javascript'):?>gclpr-active-sec<?php endif; ?>">
                                    <?php 
                                        do_settings_sections( 'gclpr_scripts_section' ); 
                                        submit_button( 'Save Settings' );
                                    ?>
                                </div>

                                <div class="gclpr-images-form gclpr-sec <?php if($tab == 'images'):?>gclpr-active-sec<?php endif; ?>">
                                    <?php 
                                        do_settings_sections( 'gclpr_images_section' ); 
                                        submit_button( 'Save Settings' );
                                    ?>
                                </div>

                                <div class="gclpr-videos-form gclpr-sec <?php if($tab == 'video'):?>gclpr-active-sec<?php endif; ?>">
                                    <?php 
                                        do_settings_sections( 'gclpr_videos_section' ); 
                                        submit_button( 'Save Settings' );
                                    ?>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            <?php
        }

        /* register setting */
        public function register_settings_init() {
            register_setting( 'gclpr-setting-options', 'gclpr_options',array($this,'sanitize_settings'));

            add_settings_section(
                'gclpr_fonts_setting',
                __( 'Fonts Setting', 'preload-requests' ),
                array(),
                'gclpr_fonts_section'
            );
            add_settings_field(
                'font_urls_list',
                __('Common Font URLs', 'preload-requests'),
                array( $this, 'common_fonts_urls'),
                'gclpr_fonts_section',
                'gclpr_fonts_setting', 
                [
                    'label_for' => 'font_urls_list',
                    'class' => 'gclpr-font-urls-list',
                ]
            );
            add_settings_field(
                'font_post_types_list',
                __('Select Post Type', 'preload-requests'), 
                array( $this, 'fonts_post_types'),
                'gclpr_fonts_section',
                'gclpr_fonts_setting', 
                [
                    'label_for' => 'font_post_types_list',
                    'class' => 'gclpr-font-posttypes-list',
                ]
            );

            add_settings_section(
                'gclpr_styles_setting',
                __( 'Styles Setting', 'preload-requests' ),
                array(),
                'gclpr_styles_section'
            );
            add_settings_field(
                'style_urls_list',
                __('Common Style URLs', 'preload-requests'), 
                array( $this, 'common_styles_urls'),
                'gclpr_styles_section',
                'gclpr_styles_setting', 
                [
                    'label_for' => 'style_urls_list',
                    'class' => 'gclpr-style-urls-list',
                ]
            );
            add_settings_field(
                'style_post_types_list',
                __('Select Post Type', 'preload-requests'), 
                array( $this, 'styles_post_types'),
                'gclpr_styles_section',
                'gclpr_styles_setting', 
                [
                    'label_for' => 'style_post_types_list',
                    'class' => 'gclpr-style-posttypes-list',
                ]
            );

            add_settings_section(
                'gclpr_scripts_setting',
                __( 'Scripts Setting', 'preload-requests' ),
                array(),
                'gclpr_scripts_section'
            );
            add_settings_field(
                'script_urls_list',
                __('Common Script URLs', 'preload-requests'), 
                array( $this, 'common_script_urls'),
                'gclpr_scripts_section',
                'gclpr_scripts_setting', 
                [
                    'label_for' => 'script_urls_list',
                    'class' => 'gclpr-script-urls-list',
                ]
            );
            add_settings_field(
                'script_post_types_list',
                __('Select Post Type', 'preload-requests'), 
                array( $this, 'script_post_types'),
                'gclpr_scripts_section',
                'gclpr_scripts_setting', 
                [
                    'label_for' => 'script_post_types_list',
                    'class' => 'gclpr-script-posttypes-list',
                ]
            );

            add_settings_section(
                'gclpr_images_setting',
                __( 'Images Setting', 'preload-requests' ),
                array(),
                'gclpr_images_section'
            );
            add_settings_field(
                'image_urls_list',
                __('Common Image URLs', 'preload-requests'), 
                array( $this, 'common_images_urls'),
                'gclpr_images_section',
                'gclpr_images_setting', 
                [
                    'label_for' => 'image_urls_list',
                    'class' => 'gclpr-image-urls-list',
                ]
            );
            add_settings_field(
                'image_post_types_list',
                __('Select Post Type', 'preload-requests'), 
                array( $this, 'images_post_types'),
                'gclpr_images_section',
                'gclpr_images_setting', 
                [
                    'label_for' => 'image_post_types_list',
                    'class' => 'gclpr-image-posttypes-list',
                ]
            );

            add_settings_section(
                'gclpr_videos_setting',
                __( 'Videos Setting', 'preload-requests' ),
                array(),
                'gclpr_videos_section'
            );
            add_settings_field(
                'video_urls_list',
                __('Common Video URLs', 'preload-requests'), 
                array( $this, 'common_videos_urls'),
                'gclpr_videos_section',
                'gclpr_videos_setting', 
                [
                    'label_for' => 'video_urls_list',
                    'class' => 'gclpr-video-urls-list',
                ]
            );
            add_settings_field(
                'video_post_types_list',
                __('Select Post Type', 'preload-requests'), 
                array( $this, 'videos_post_types'),
                'gclpr_videos_section',
                'gclpr_videos_setting', 
                [
                    'label_for' => 'video_post_types_list',
                    'class' => 'gclpr-video-posttypes-list',
                ]
            );

        }

        public function sanitize_settings($input) {
            $new_input = array();
            
            if(isset($input['gclpr_font_options']['font_urls_list'])) 
                $new_input['gclpr_font_options']['font_urls_list'] = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", sanitize_textarea_field($input['gclpr_font_options']['font_urls_list']));
            if(isset($input['gclpr_style_options']['style_urls_list'])) 
                $new_input['gclpr_style_options']['style_urls_list'] = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", sanitize_textarea_field($input['gclpr_style_options']['style_urls_list']));
            if(isset($input['gclpr_script_options']['script_urls_list'])) 
                $new_input['gclpr_script_options']['script_urls_list'] = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", sanitize_textarea_field($input['gclpr_script_options']['script_urls_list']));
            if(isset($input['gclpr_image_options']['image_urls_list'])) 
                $new_input['gclpr_image_options']['image_urls_list'] = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", sanitize_textarea_field($input['gclpr_image_options']['image_urls_list']));
            if(isset($input['gclpr_video_options']['video_urls_list'])) 
                $new_input['gclpr_video_options']['video_urls_list'] = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", sanitize_textarea_field($input['gclpr_video_options']['video_urls_list']));

            if(isset($input['gclpr_font_options']['font_post_types_list'])) {
                $new_input['gclpr_font_options']['font_post_types_list'] = array_map( 'esc_attr', $input['gclpr_font_options']['font_post_types_list'] );
            }
            if(isset($input['gclpr_style_options']['style_post_types_list'])) {
                $new_input['gclpr_style_options']['style_post_types_list'] = array_map( 'esc_attr', $input['gclpr_style_options']['style_post_types_list'] );
            }
            if(isset($input['gclpr_script_options']['script_post_types_list'])) {
                $new_input['gclpr_script_options']['script_post_types_list'] = array_map( 'esc_attr', $input['gclpr_script_options']['script_post_types_list'] );
            }
            if(isset($input['gclpr_image_options']['image_post_types_list'])) {
                $new_input['gclpr_image_options']['image_post_types_list'] = array_map( 'esc_attr', $input['gclpr_image_options']['image_post_types_list'] );
            }
            if(isset($input['gclpr_video_options']['video_post_types_list'])) {
                $new_input['gclpr_video_options']['video_post_types_list'] = array_map( 'esc_attr', $input['gclpr_video_options']['video_post_types_list'] );
            }

            return $new_input;
        }

        /* setting font html */
        public function common_fonts_urls($args) {
            global $gclpr_options;
            $value = isset($gclpr_options['gclpr_font_options'][$args['label_for']]) ? $gclpr_options['gclpr_font_options'][$args['label_for']] : '';
            ?>
                <textarea class="large-text" rows="10" name="gclpr_options[gclpr_font_options][<?php esc_attr_e( $args['label_for'] ); ?>]" id="<?php esc_attr_e( $args['label_for'] ); ?>"><?php _e($value,'preload-requests'); ?></textarea><br>
                <p><strong>Note: </strong><i>This Preloaded <strong>Fonts URL</strong> can be included in <strong>whole Website</strong>. Add <strong>Font URL per Line.</strong></i></p>
                <p>Use  <code>{{site_url}}</code> for <code><?php echo esc_url(home_url()); ?></code></p>
            <?php 
        }

        public function fonts_post_types($args) {
            global $gclpr_options, $post_types;
            $value = isset($gclpr_options['gclpr_font_options'][$args['label_for']]) ? $gclpr_options['gclpr_font_options'][$args['label_for']] : array();

            foreach ($post_types as $key => $post_type) {
            ?>
                <label> 
                    <input type="checkbox" name="gclpr_options[gclpr_font_options][<?php esc_attr_e( $args['label_for'] ); ?>][<?php esc_attr_e($key);?>]" value="<?php esc_attr_e($key);?>" <?php if(isset($value[$key])){ esc_attr_e('checked'); }?>>
                    <span><?php esc_html_e($post_type->label,'preload-requests'); ?></span> <br>
                </label>
            <?php
            } ?>
                <p><strong>Note: </strong><i>Select <strong>Post Type</strong> for include <strong>Font URL</strong> Preload in <strong>Single Post</strong> Page. </i><code>ex: <?php echo esc_url(home_url()); ?>/wp-content/themes/twentytwentyone/assets/fonts/demo.{font type}</code></p>
            <?php
        }

        /* setting css html */
        public function common_styles_urls($args) {
            global $gclpr_options;
            $value = isset($gclpr_options['gclpr_style_options'][$args['label_for']]) ? $gclpr_options['gclpr_style_options'][$args['label_for']] : '';
            ?>
                <textarea class="large-text" rows="10" name="gclpr_options[gclpr_style_options][<?php esc_attr_e( $args['label_for'] ); ?>]" id="<?php esc_attr_e( $args['label_for'] ); ?>"><?php _e($value,'preload-requests'); ?></textarea><br>
                <p><strong>Note: </strong><i>This Preloaded <strong>Styles URL</strong> can be included in <strong>whole website</strong>. Add <strong>Style URL per Line.</strong></i></p>
                <p>Use  <code>{{site_url}}</code> for <code><?php echo esc_url(home_url()); ?></code></p>
            <?php
        }

        public function styles_post_types($args) {
            global $gclpr_options, $post_types;
            $value = isset($gclpr_options['gclpr_style_options'][$args['label_for']]) ? $gclpr_options['gclpr_style_options'][$args['label_for']] : array();

            foreach ($post_types as $key => $post_type) {
            ?>
                <label>
                    <input type="checkbox" name="gclpr_options[gclpr_style_options][<?php esc_attr_e( $args['label_for'] ); ?>][<?php esc_attr_e($key);?>]" value="<?php esc_attr_e($key);?>" <?php if(isset($value[$key])){ esc_attr_e('checked'); }?>>
                    <span><?php esc_html_e($post_type->label,'preload-requests'); ?></span><br>
                </label>
            <?php
            } ?>
            <p><strong>Note: </strong><i>Select <strong>Post Type</strong> for include <strong>Style URL</strong> Preload in <strong>Single Post</strong> Page. </i><code>ex: <?php echo esc_url(home_url()); ?>/wp-content/themes/twentytwentyone/assets/css/demo.css</code></p>
        <?php
        }

        /* setting javascript html */
        public function common_script_urls($args) {
            global $gclpr_options;
            $value = isset($gclpr_options['gclpr_script_options'][$args['label_for']]) ? $gclpr_options['gclpr_script_options'][$args['label_for']] : '';    
            ?>
                <textarea class="large-text" rows="10" name="gclpr_options[gclpr_script_options][<?php esc_attr_e( $args['label_for'] ); ?>]" id="<?php esc_attr_e( $args['label_for'] ); ?>"><?php _e($value,'preload-requests'); ?></textarea><br>
                <p><strong>Note: </strong><i>This Preloaded <strong>Scripts URL</strong> can be included in <strong>whole website</strong>. Add <strong>Script URL per Line.</strong></i></p>
                <p>Use  <code>{{site_url}}</code> for <code><?php echo esc_url(home_url()); ?></code></p>
            <?php
        }

        public function script_post_types($args) {
            global $gclpr_options, $post_types;
            $value = isset($gclpr_options['gclpr_script_options'][$args['label_for']]) ? $gclpr_options['gclpr_script_options'][$args['label_for']] : array();

            foreach ($post_types as $key => $post_type) {
            ?>
                <label>
                    <input type="checkbox" name="gclpr_options[gclpr_script_options][<?php esc_attr_e( $args['label_for'] ); ?>][<?php esc_attr_e($key);?>]" value="<?php esc_attr_e($key);?>" <?php if(isset($value[$key])){ esc_attr_e('checked'); }?>>
                    <span><?php esc_html_e($post_type->label,'preload-requests'); ?></span><br>
                </label>
            <?php
            } ?>
            <p><strong>Note: </strong><i>Select <strong>Post Type</strong> for include <strong>Script URL</strong> Preload in <strong>Single Post</strong> Page. </i><code>ex: <?php echo esc_url(home_url()); ?>/wp-content/themes/twentytwentyone/assets/js/demo.js</code></p>
        <?php
        }

        /* setting images html */
        public function common_images_urls($args) {
            global $gclpr_options;
            $value = isset($gclpr_options['gclpr_image_options'][$args['label_for']]) ? $gclpr_options['gclpr_image_options'][$args['label_for']] : '';
            ?>
                <textarea class="large-text" rows="10" name="gclpr_options[gclpr_image_options][<?php esc_attr_e( $args['label_for'] ); ?>]" id="<?php esc_attr_e( $args['label_for'] ); ?>"><?php _e($value,'preload-requests'); ?></textarea><br>
                <p><strong>Note: </strong><i>This Preloaded <strong>Images URL</strong> can be included in <strong>whole website</strong>. Add <strong>Image URL per Line.</strong></i></p>
                <p>Use  <code>{{site_url}}</code> for <code><?php echo esc_url(home_url()); ?></code></p>
            <?php
        }

        public function images_post_types($args) {
            global $gclpr_options, $post_types;
            $value = isset($gclpr_options['gclpr_image_options'][$args['label_for']]) ? $gclpr_options['gclpr_image_options'][$args['label_for']] : array();

            foreach ($post_types as $key => $post_type) {
            ?>
                <label>
                    <input type="checkbox" name="gclpr_options[gclpr_image_options][<?php esc_attr_e( $args['label_for'] ); ?>][<?php esc_attr_e($key);?>]" value="<?php esc_attr_e($key);?>" <?php if(isset($value[$key])){ esc_attr_e('checked'); }?>>
                    <span><?php esc_html_e($post_type->label,'preload-requests'); ?></span><br>
                </label>
            <?php
            } ?>
            <p><strong>Note: </strong><i>Select <strong>Post Type</strong> for include <strong>Image URL</strong> Preload in <strong>Single Post</strong> Page. </i><code>ex: http://localhost/demo/wp-content/uploads/2021/08/IMAGE_NAME.{image type}</code></p>
        <?php
        }

        /* setting video html */
        public function common_videos_urls($args) {
            global $gclpr_options;
            $value = isset($gclpr_options['gclpr_video_options'][$args['label_for']]) ? $gclpr_options['gclpr_video_options'][$args['label_for']] : '';
            ?>
                <textarea class="large-text" rows="10" name="gclpr_options[gclpr_video_options][<?php esc_attr_e( $args['label_for'] ); ?>]" id="<?php esc_attr_e( $args['label_for'] ); ?>"><?php _e($value,'preload-requests'); ?></textarea><br>
                <p><strong>Note: </strong><i>This preloaded <strong>Videos URL</strong> can be included in <strong>whole website</strong>. Add <strong>Video URL per line.</strong></i></p>
                <p>Use  <code>{{site_url}}</code> for <code><?php echo esc_url(home_url()); ?></code></p>
            <?php
        }

        public function videos_post_types($args) {
            global $gclpr_options, $post_types;
            $value = isset($gclpr_options['gclpr_video_options'][$args['label_for']]) ? $gclpr_options['gclpr_video_options'][$args['label_for']] : array();

            foreach ($post_types as $key => $post_type) {
            ?>
                <label>
                    <input type="checkbox" name="gclpr_options[gclpr_video_options][<?php esc_attr_e( $args['label_for'] ); ?>][<?php esc_attr_e($key);?>]" value="<?php esc_attr_e($key);?>" <?php if(isset($value[$key])){ esc_attr_e('checked'); }?>>
                    <span><?php esc_html_e($post_type->label,'preload-requests'); ?></span><br>
                </label>
            <?php
            } ?>
            <p><strong>Note: </strong><i>Select <strong>Post Type</strong> for include <strong>Video URL</strong> Preload in <strong>Single Post</strong> Page. </i><code>ex: <?php echo esc_url(home_url()); ?>/wp-content/themes/twentytwentyone/assets/video/demo.{video type}</code></p>
        <?php
        }
    }
    new gclpr_preload_requests_settings();
}