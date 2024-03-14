<?php
if(!class_exists('gclpr_prelaod_requests_meta')) {
    $gclpr_options = get_option('gclpr_options');
    $gclpr_font_posts = (isset($gclpr_options['gclpr_font_options']['font_post_types_list'])) ? $gclpr_options['gclpr_font_options']['font_post_types_list'] : array();
    $gclpr_style_posts = (isset($gclpr_options['gclpr_style_options']['style_post_types_list'])) ? $gclpr_options['gclpr_style_options']['style_post_types_list'] : array();
    $gclpr_script_posts = (isset($gclpr_options['gclpr_script_options']['script_post_types_list'])) ? $gclpr_options['gclpr_script_options']['script_post_types_list'] : array();
    $gclpr_image_posts = (isset($gclpr_options['gclpr_image_options']['image_post_types_list'])) ? $gclpr_options['gclpr_image_options']['image_post_types_list'] : array();
    $gclpr_video_posts = (isset($gclpr_options['gclpr_video_options']['video_post_types_list'])) ? $gclpr_options['gclpr_video_options']['video_post_types_list'] : array();

    class gclpr_prelaod_requests_meta
    {
        public function __construct() {
            add_action('add_meta_boxes',array($this,'preload_request_metabox'));
            add_action('save_post', array($this, 'save_post_metabox_callback'));
            add_action('wp_head', array($this, 'preload_request_head_meta'),-999);
        }

        public function preload_request_head_meta() {
            global $gclpr_options, $gclpr_font_posts, $gclpr_image_posts, $gclpr_style_posts, $gclpr_script_posts;
            $fonts_urls = $images_urls = $styles_urls = $scripts_urls = [];

            $cur_post_id = get_the_ID();
            $cur_post = get_post_type($cur_post_id);
            $preload_post_meta = get_post_meta($cur_post_id, 'gclpr_preload_meta', true);
            
            $selected_posts = array_merge($gclpr_font_posts,$gclpr_image_posts,$gclpr_style_posts,$gclpr_script_posts);

            if(is_singular($cur_post) && in_array($cur_post,$selected_posts) && isset($preload_post_meta) && !empty($preload_post_meta)) {
                $font_post_urls = (isset($preload_post_meta['gclpr_font_urls'])) ? str_replace('{{site_url}}',home_url(),$preload_post_meta['gclpr_font_urls']) : '';
                $font_post_types_list = (isset($gclpr_options) && !empty($gclpr_options['gclpr_font_options']['font_post_types_list'])) ? $gclpr_options['gclpr_font_options']['font_post_types_list'] : '';
                if(!empty($font_post_urls)) {
                    $font_post_urls   =   explode("\n", $font_post_urls);           // Break a string into an array: explode(separator,string,limit)
                }
                $fonts_urls = (isset($font_post_urls) && !empty($font_post_urls)) ? array_unique($font_post_urls) : '';
                if(isset($fonts_urls) && !empty($fonts_urls) && is_array($font_post_types_list) && in_array($cur_post,$font_post_types_list)) {
                    foreach($fonts_urls as $font_urls) {
                        $file_info = pathinfo($font_urls);
                        $file_ext = (isset($file_info['extension']) && !empty($file_info['extension'])) ? $file_info['extension'] : '';
                        if(!empty($font_urls) && !empty($file_ext)) {
                        ?><link rel="preload" href="<?php echo esc_url($font_urls); ?>" as="font" type="font/<?php esc_attr_e($file_ext); ?>" crossorigin /><?php printf("\n"); ?><?php
                        }
                    }
                }

                $style_post_urls = (isset($preload_post_meta['gclpr_style_urls'])) ? str_replace('{{site_url}}',home_url(),$preload_post_meta['gclpr_style_urls']) : '';
                $style_post_types_list = (isset($gclpr_options) && !empty($gclpr_options['gclpr_style_options']['style_post_types_list'])) ? $gclpr_options['gclpr_style_options']['style_post_types_list'] : '';
                if(!empty($style_post_urls)) {
                    $style_post_urls   =   explode("\n", $style_post_urls);           // Break a string into an array: explode(separator,string,limit)
                }

                $styles_urls = (isset($style_post_urls) && !empty($style_post_urls)) ? array_unique($style_post_urls) : '';
                if(isset($styles_urls) && !empty($styles_urls) && is_array($style_post_types_list) && in_array($cur_post,$style_post_types_list)) {
                    foreach($styles_urls as $style_urls) { 
                        if(!empty($style_urls)) {
                        ?><link rel="preload" href="<?php echo esc_url($style_urls); ?>" as="style" crossorigin><?php printf("\n"); ?><?php
                        }
                    }
                }

                $script_post_urls = (isset($preload_post_meta['gclpr_script_urls'])) ? str_replace('{{site_url}}',home_url(),$preload_post_meta['gclpr_script_urls']) : '';
                $script_post_types_list = (isset($gclpr_options) && !empty($gclpr_options['gclpr_script_options']['script_post_types_list'])) ? $gclpr_options['gclpr_script_options']['script_post_types_list'] : '';
                if(!empty($script_post_urls)) {
                    $script_post_urls   =   explode("\n", $script_post_urls);           // Break a string into an array: explode(separator,string,limit)
                }
                $scripts_urls = (isset($script_post_urls) && !empty($script_post_urls)) ? array_unique($script_post_urls) : '';                    
                if(isset($scripts_urls) && !empty($scripts_urls) && is_array($script_post_types_list) && in_array($cur_post,$script_post_types_list)) {
                    foreach($scripts_urls as $script_urls) { 
                        if(!empty($scripts_urls)) { 
                        ?><link rel="preload" href="<?php echo esc_url($script_urls); ?>" as="script" crossorigin><?php printf("\n"); ?><?php
                        }
                    }
                }

                $image_post_urls = (isset($preload_post_meta['gclpr_image_urls'])) ? str_replace('{{site_url}}',home_url(),$preload_post_meta['gclpr_image_urls']) : '';
                $image_post_types_list = (isset($gclpr_options) && !empty($gclpr_options['gclpr_image_options']['image_post_types_list'])) ? $gclpr_options['gclpr_image_options']['image_post_types_list'] : '';
                if(!empty($image_post_urls)) {
                    $image_post_urls   =   explode("\n", $image_post_urls);           // Break a string into an array: explode(separator,string,limit)
                }
                $images_urls = (isset($image_post_urls) && !empty($image_post_urls)) ? array_unique($image_post_urls) : '';
                if(isset($images_urls) && !empty($images_urls) && is_array($image_post_types_list) && in_array($cur_post,$image_post_types_list)) {
                    foreach($images_urls as $image_urls) {    
                        $file_info = pathinfo($image_urls);
                        $file_ext = (isset($file_info['extension']) && !empty($file_info['extension'])) ? $file_info['extension'] : '';
                        if(!empty($image_urls) && !empty($file_ext)) { 
                        ?><link rel="preload" href="<?php echo esc_url($image_urls); ?>" as="image" type="image/<?php esc_attr_e($file_ext); ?>" crossorigin><?php printf("\n"); ?><?php
                        }
                    }
                }

                $video_post_urls = (isset($preload_post_meta['gclpr_video_urls'])) ? str_replace('{{site_url}}',home_url(),$preload_post_meta['gclpr_video_urls']) : '';
                $video_post_types_list = (isset($gclpr_options) && !empty($gclpr_options['gclpr_video_options']['video_post_types_list'])) ? $gclpr_options['gclpr_video_options']['video_post_types_list'] : '';
                if(!empty($video_post_urls)) {
                    $video_post_urls   =   explode("\n", $video_post_urls);           // Break a string into an array: explode(separator,string,limit)
                }
                $videos_urls = (isset($video_post_urls) && !empty($video_post_urls)) ? array_unique($video_post_urls) : '';
                if(isset($videos_urls) && !empty($videos_urls) && is_array($video_post_types_list) && in_array($cur_post,$video_post_types_list)) {
                    foreach($videos_urls as $video_urls) {    
                        $file_info = pathinfo($video_urls);
                        $file_ext = (isset($file_info['extension']) && !empty($file_info['extension'])) ? $file_info['extension'] : '';
                        if(!empty($video_urls) && !empty($file_ext)) { 
                        ?><link rel="preload" href="<?php echo esc_url($video_urls); ?>" as="video" type="video/<?php esc_attr_e($file_ext); ?>" crossorigin><?php printf("\n"); ?><?php
                        }
                    }
                }
                
            }
        }

        /* add meta to single page of post types */
        public function preload_request_metabox() {
            global $gclpr_options, $gclpr_font_posts, $gclpr_image_posts, $gclpr_style_posts, $gclpr_script_posts, $gclpr_video_posts;

            if(isset($gclpr_options) && !empty($gclpr_options)) {
                $selected_posts = array_merge($gclpr_font_posts,$gclpr_image_posts,$gclpr_style_posts,$gclpr_script_posts,$gclpr_video_posts);
                
                if(isset($selected_posts) && !empty($selected_posts)) {
                    foreach ($selected_posts as $key => $selected_post) {
                        add_meta_box(
                            'preload-requests-metabox',
                            __('Preload Requests',''),
                            array($this, 'preload_request_metabox_callback'),
                            $selected_post,
                            'normal',
                            'high'
                        );
                    }
                }
            }
        } 

        public function preload_request_metabox_callback($post) {
            global $gclpr_options, $gclpr_font_posts, $gclpr_image_posts, $gclpr_style_posts, $gclpr_script_posts, $gclpr_video_posts;
            
            $post_type = get_post_type($post->ID);
            $post_type_obj = get_post_type_object($post_type);
            $post_type_label = (isset($post_type_obj->labels->name)) ? $post_type_obj->labels->name : '$post_type';
            $preload_post_meta = get_post_meta($post->ID, 'gclpr_preload_meta', true);
            
            $font_post_urls = (isset($preload_post_meta['gclpr_font_urls'])) ? $preload_post_meta['gclpr_font_urls'] : '';
            $style_post_urls = (isset($preload_post_meta['gclpr_style_urls'])) ? $preload_post_meta['gclpr_style_urls'] : '';
            $script_post_urls = (isset($preload_post_meta['gclpr_script_urls'])) ? $preload_post_meta['gclpr_script_urls'] : '';
            $image_post_urls = (isset($preload_post_meta['gclpr_image_urls'])) ? $preload_post_meta['gclpr_image_urls'] : '';
            $video_post_urls = (isset($preload_post_meta['gclpr_video_urls'])) ? $preload_post_meta['gclpr_video_urls'] : ''; ?>
                <div class="gclpr-preload-meta">
                    <div class="gclpr-description">
                        <p>Here you can preload link for single <strong><?php esc_attr_e($post_type_label); ?></strong>. Please <strong>Add one URL per line.</strong> <code>ex: http://localhost/demo-site/assets/font/demo.{file type}</code></p>
                    </div>
                    
                    <table class="form-table">
                        <tbody>
                            <?php 
                                if(isset($gclpr_font_posts) && !empty($gclpr_font_posts)) {
                                    if(in_array($post_type,$gclpr_font_posts)) { ?>
                                <tr>
                                    <th>
                                        <label><?php _e('Fonts URL') ?></label><br>
                                    </th>
                                    <td>
                                        <textarea class="large-text" rows="10" name="gclpr_font_urls" id="gclpr_font_urls"><?php _e($font_post_urls,'preload-requests'); ?></textarea><br>                                            
                                        <p><strong>Note: </strong><i>This preloaded <strong>fonts URL</strong> included in this single <strong><?php esc_attr_e($post_type_label); ?></strong>. Add <strong>font URL per line.</strong></i></p>
                                        <p>Use  <code>{{site_url}}</code> for <code><?php echo esc_url(home_url()); ?></code></p>
                                    </td>
                                </tr>
                                <?php   
                                    }
                                } 
                            ?>

                            <?php 
                                if(isset($gclpr_style_posts) && !empty($gclpr_style_posts)) {
                                    if(in_array($post_type,$gclpr_style_posts)) { ?>
                                <tr>
                                    <th>
                                        <label><?php _e('Styles URL') ?></label><br>
                                    </th>
                                    <td>
                                        <textarea class="large-text" rows="10" name="gclpr_style_urls" id="gclpr_style_urls"><?php _e($style_post_urls,'preload-requests'); ?></textarea><br>
                                        <p><strong>Note: </strong><i>This preloaded <strong>styles</strong> included in this single <strong><?php esc_attr_e($post_type_label); ?></strong>. Add <strong>style URL per line.</strong></i></p>
                                        <p>Use  <code>{{site_url}}</code> for <code><?php echo esc_url(home_url()); ?></code></p>
                                    </td>
                                </tr>
                                <?php   
                                    }
                                } 
                            ?>

                            <?php 
                                if(isset($gclpr_script_posts) && !empty($gclpr_script_posts)) {
                                    if(in_array($post_type,$gclpr_script_posts)) { ?>
                                <tr>
                                    <th>
                                        <label><?php _e('Scripts URL') ?></label><br>
                                    </th>
                                    <td>
                                        <textarea class="large-text" rows="10" name="gclpr_script_urls" id="gclpr_script_urls"><?php _e($script_post_urls,'preload-requests'); ?></textarea><br>
                                        <p><strong>Note: </strong><i>This preloaded <strong>scripts</strong> included in this single <strong><?php esc_attr_e($post_type_label); ?></strong>. Add <strong>script URL per line.</strong></i></p>
                                        <p>Use  <code>{{site_url}}</code> for <code><?php echo esc_url(home_url()); ?></code></p>
                                    </td>
                                </tr> 
                                <?php   
                                    }
                                } 
                            ?>             

                            <?php 
                                if(isset($gclpr_image_posts) && !empty($gclpr_image_posts)) {
                                    if(in_array($post_type,$gclpr_image_posts)) { ?>
                                <tr>
                                    <th>
                                        <label><?php _e('Images URL') ?></label><br>
                                    </th>
                                    <td>
                                        <textarea class="large-text" rows="10" name="gclpr_image_urls" id="gclpr_image_urls"><?php _e($image_post_urls,'preload-requests'); ?></textarea><br>
                                        <p><strong>Note: </strong><i>This preloaded <strong>images</strong> included in this single <strong><?php esc_attr_e($post_type_label); ?></strong>. Add <strong>image URL per line.</strong></i></p>
                                        <p>Use  <code>{{site_url}}</code> for <code><?php echo esc_url(home_url()); ?></code></p>
                                    </td>
                                </tr>
                                <?php   
                                    }
                                } 
                            ?>

                            <?php 
                                if(isset($gclpr_video_posts) && !empty($gclpr_video_posts)) {
                                    if(in_array($post_type,$gclpr_video_posts)) { ?>
                                <tr>
                                    <th>
                                        <label><?php _e('Videos URL') ?></label><br>
                                    </th>
                                    <td>
                                        <textarea class="large-text" rows="10" name="gclpr_video_urls" id="gclpr_video_urls"><?php _e($video_post_urls,'preload-requests'); ?></textarea><br>
                                        <p><strong>Note: </strong><i>This preloaded <strong>videos</strong> included in this single <strong><?php esc_attr_e($post_type_label); ?></strong>. Add <strong>video URL per line.</strong></i></p>
                                        <p>Use  <code>{{site_url}}</code> for <code><?php echo esc_url(home_url()); ?></code></p>
                                    </td>
                                </tr>
                                <?php   
                                    }
                                } 
                            ?>
                        </tbody>
                    </table>
                </div>
            <?php
        }

        public function save_post_metabox_callback($post_id) {
            if (!current_user_can('edit_post', $post_id))
                return;

            $gclpr_font_urls = $gclpr_style_urls = $gclpr_script_urls = $gclpr_image_urls = $gclpr_video_urls = '';
            $gclpr_font_urls = (isset($_POST['gclpr_font_urls'])) ? sanitize_textarea_field($_POST['gclpr_font_urls']) : $gclpr_font_urls;
            $gclpr_style_urls = (isset($_POST['gclpr_style_urls'])) ?  sanitize_textarea_field($_POST['gclpr_style_urls']) : $gclpr_style_urls;
            $gclpr_script_urls = (isset($_POST['gclpr_script_urls'])) ?  sanitize_textarea_field($_POST['gclpr_script_urls']) : $gclpr_script_urls;
            $gclpr_image_urls = (isset($_POST['gclpr_image_urls'])) ?  sanitize_textarea_field($_POST['gclpr_image_urls']) : $gclpr_image_urls;
            $gclpr_video_urls = (isset($_POST['gclpr_video_urls'])) ?  sanitize_textarea_field($_POST['gclpr_video_urls']) : $gclpr_video_urls;                

            $gclpr = array();
            $reg_exp = "/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/";
            if(isset($_POST['gclpr_font_urls']))     $gclpr['gclpr_font_urls'] = preg_replace($reg_exp, "\n", $gclpr_font_urls);
            if(isset($_POST['gclpr_style_urls']))    $gclpr['gclpr_style_urls'] = preg_replace($reg_exp, "\n", $gclpr_style_urls);
            if(isset($_POST['gclpr_script_urls']))   $gclpr['gclpr_script_urls'] = preg_replace($reg_exp, "\n", $gclpr_script_urls);
            if(isset($_POST['gclpr_image_urls']))    $gclpr['gclpr_image_urls'] = preg_replace($reg_exp, "\n", $gclpr_image_urls);
            if(isset($_POST['gclpr_video_urls']))    $gclpr['gclpr_video_urls'] = preg_replace($reg_exp, "\n", $gclpr_video_urls);

            if(isset($gclpr) && !empty($gclpr)) {
                update_post_meta($post_id, 'gclpr_preload_meta', $gclpr);
            }
        }
    }
    new gclpr_prelaod_requests_meta();
}