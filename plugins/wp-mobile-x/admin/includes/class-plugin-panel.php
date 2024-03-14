<?php defined( 'ABSPATH' ) || exit;
class WPCOM_PLUGIN_PANEL_FREE{
    function __construct($info){
        global $_wpcom_free_plugins;
        if(!isset($_wpcom_free_plugins)) $_wpcom_free_plugins = array();
        $this->info = $info;
        $this->key = isset($this->info['key']) ? $this->info['key'] : '';
        add_filter('pre_option_' .  $this->key, array($this, 'options_filter'), 10, 2);
        $this->options = get_option($this->key);
        $this->version = isset($this->info['ver']) ? $this->info['ver'] : '';
        $this->basename = isset($this->info['basename']) ? $this->info['basename'] : '';
        $this->plugin_slug = isset($this->info['slug']) ? $this->info['slug'] : '';
        $_wpcom_free_plugins[$this->info['plugin_id']] = array('slug' => $this->plugin_slug, 'ver' => $this->version);
        add_action('admin_menu', array($this, 'init'));

        // 公用、仅注册1次的hook
        if(count($_wpcom_free_plugins) < 2) add_action('after_setup_theme', array( $this, 'init_metadata_filter' ));
    }

    function init(){
        $title = isset($this->info['title']) ? $this->info['title'] : '';
        $icon = isset($this->info['icon']) ? $this->info['icon'] : '';
        $parent_slug = isset($this->info['parent_slug']) ? $this->info['parent_slug'] : '';
        $position = isset($this->info['position']) ? $this->info['position'] : '85';

        if($parent_slug){
            add_submenu_page($parent_slug, $title, $title, 'manage_options', $this->plugin_slug, array(&$this, 'options'), $position);
        }else{
            add_menu_page($title, $title, 'manage_options', $this->plugin_slug, array(&$this, 'options'), $icon, $position);
        }

        if (current_user_can('manage_options' ) && isset($_GET['page']) && $_GET['page'] == $this->plugin_slug ) {
            require_once WPCOM_ADMIN_FREE_PATH . 'includes/class-utils.php';
            add_action('admin_enqueue_scripts', array('WPCOM_ADMIN_UTILS_FREE', 'panel_script'));
        }
    }

    function init_metadata_filter(){
        // 主题也有此功能，不重复
        if(!defined('FRAMEWORK_PATH') && !class_exists('WPCOM_PLUGIN_PANEL')){
            add_filter( 'get_post_metadata', array( $this, 'meta_filter' ), 20, 5 );
            add_filter( 'add_post_metadata', array( $this, 'add_metadata' ), 20, 4 );
            add_filter( 'update_post_metadata', array( $this, 'add_metadata' ), 20, 4 );

            add_filter( 'get_term_metadata', array( $this, 'meta_filter' ), 20, 5 );
            add_filter( 'add_term_metadata', array( $this, 'add_metadata' ), 20, 4 );
            add_filter( 'update_term_metadata', array( $this, 'add_metadata' ), 20, 4 );

            add_filter( 'get_user_metadata', array( $this, 'meta_filter' ), 20, 5 );
            add_filter( 'add_user_metadata', array( $this, 'add_metadata' ), 20, 4 );
            add_filter( 'update_user_metadata', array( $this, 'add_metadata' ), 20, 4 );
        }
    }

    function options(){
        require_once WPCOM_ADMIN_FREE_PATH . 'includes/class-utils.php';
        $this->settings = $this->form_options();
        $this->form_action();
        ?>
        <div class="wrap wpcom-wrap">
            <div class="wpcom-panel-head">
                <div class="wpcom-panel-copy">V<?php echo esc_html($this->version);?></div>
                <h1><?php echo esc_html($this->info['name']);?></h1>
            </div>
            <?php $this->build_form();?>
        </div>
    <?php }

    private function build_form(){
        $active = 0;
        if(isset($_COOKIE[$this->plugin_slug . '_nav']) && $_COOKIE[$this->plugin_slug . '_nav'])
            $active = $_COOKIE[$this->plugin_slug . '_nav'];
        ?>
        <div class="wpcom-panel-form" id="j-panel-form" data-slug="<?php echo esc_attr($this->plugin_slug);?>">
            <ul class="wpcom-panel-nav">
                <?php foreach ($this->settings as $i => $item) { if($item){ ?>
                    <li<?php echo $i==$active?' class="active"':''?>>
                        <?php if(isset($item['icon'])){?><i class="material-icons"><?php echo $item['icon'];?></i> <?php } ?><?php echo esc_html($item['title']);?>
                    </li>
                <?php }} ?>
            </ul>
            <div class="wpcom-panel-content">
                <form action="" method="post" id="wpcom-panel-form">
                    <?php foreach ($this->settings as $i => $item) { if($item){ ?>
                    <div class="wpcom-panel-item<?php echo $i==$active?' active':''?>">
                        <?php
                        $item['options'] = isset($item['options']) ? $item['options'] : (isset($item['option']) ? $item['option'] : '');
                        if(isset($item['options']) && $item['options']) { $x=0; foreach ($item['options'] as $input) {
                            $this->option_item($input, $x);
                            $x++;
                        }} ?>
                    </div>
                    <?php }}?>
                    <div class="wpcom-panel-submit" style="display: none;">
                        <?php wp_nonce_field( $this->key . '_options', $this->key . '_nonce', true );?>
                        <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes', 'wpcom');?>">
                    </div>
                </form>
                <?php foreach ($this->settings as $i => $item) { if($item===false){
                    $this->active_form($i);
                } } ?>
            </div>
        </div>
        <?php
    }

    private function active_form($index){
        $res = apply_filters($this->plugin_slug . '_active_form', array());
        if(isset($_POST['email'])){
            $email = trim(sanitize_text_field($_POST['email']));
            $token = trim(sanitize_text_field($_POST['token']));
        }
        $cur = 0;
        if(isset($_COOKIE[$this->plugin_slug . '_nav']) && $_COOKIE[$this->plugin_slug . '_nav'])
            $cur = $_COOKIE[$this->plugin_slug . '_nav'];
        ?>
        <form class="form-horizontal active-form wpcom-panel-item<?php echo $index==$cur?' active':''?>" id="wpcom-active-form" method="post" action="">
            <h2 class="active-title">主题激活</h2>
            <div id="wpcom-panel-main" class="clearfix">
                <div class="form-horizontal">
                    <?php if (isset($res['active'])) { ?><p class="col-xs-offset-3 col-xs-9" style="<?php echo ($res['active']->result==0||$res['active']->result==1?'color:green;':'color:#F33A3A;');?>"><?php echo wp_kses_post($res['active']->msg); ?></p><?php } ?>
                    <div class="form-group">
                        <label for="email" class="col-xs-3 control-label">登录邮箱</label>
                        <div class="col-xs-9">
                            <input type="email" name="email" class="form-control" id="email" value="<?php echo isset($email) ? esc_attr($email) : ''; ?>" placeholder="请输入WPCOM登录邮箱">
                            <?php if(isset($res['err_email'])){ ?><div class="j-msg" style="color:#F33A3A;font-size:12px;margin-top:3px;margin-left:3px;"><?php echo wp_kses_post($res['err_email']);?></div><?php } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="token" class="col-xs-3 control-label">激活码</label>
                        <div class="col-xs-9">
                            <input type="password" name="token" class="form-control" id="token" value="<?php echo isset($token) ? esc_attr($token) : '';?>" placeholder="请输入激活码" autocomplete="off">
                            <?php if(isset($res['err_token'])){ ?>
                                <div class="j-msg" style="color:#F33A3A;font-size:12px;margin-top:3px;margin-left:3px;"><?php echo wp_kses_post($res['err_token']);?></div>
                            <?php } ?>
                        </div>
                        <div class="col-xs-9">
                            <p style="margin: 10px 0;color:#666;">激活相关问题可以参考<a href="https://www.wpcom.cn/docs/themer/auth.html" target="_blank">主题激活教程</a>
                            </p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label"></label>
                        <div class="col-xs-9">
                            <input type="submit" class="button button-primary button-active" value="提 交">
                        </div>
                    </div>
                </div>
            </div><!--#wpcom-panel-main-->
        </form>
    <?php }

    private function option_item($option, $i, $repeat='-1'){
        $type = isset($option['type']) ? $option['type'] : (isset($option['t']) ? $option['t'] : 'text');
        $title = isset($option['title']) ? $option['title'] : (isset($option['l']) ? $option['l'] : '');
        $desc = isset($option['desc']) ? $option['desc'] : (isset($option['d']) ? $option['d'] : '');
        $name = isset($option['name']) ? $option['name'] : (isset($option['n']) ? $option['n'] : '');
        $id = isset($option['id']) ? $option['id'] : $name;
        $rows = isset($option['rows']) ? $option['rows'] : 3;
        $value = isset($option['std']) ? $option['std'] : (isset($option['s']) ? $option['s'] : '');
        $notice = $desc ? '<small class="input-notice">'.$desc.'</small>' : '';
        $tax = isset($option['tax']) ? $option['tax'] : 'category';
        $option['options'] = isset($option['options']) ? $option['options'] : (isset($option['o']) ?  $option['o'] : '');

        if($repeat>-1){
            $value = isset($this->options[$option['oname']]) && isset($this->options[$option['oname']][$repeat]) ? $this->options[$option['oname']][$repeat] : $value;
        }else{
            $value = isset($this->options[$name]) ? $this->options[$name] : $value;
        }

        $output = '';
        switch ($type) {
            case 'title':
            case 'tt':
                $first = $i==0?' section-hd-first':'';
                $output = '<div class="section-hd'.$first.'"><h3 class="section-title">'.$title.' <small>'.$desc.'</small></h3></div>';
                break;
            case 'text':
                $output = '<div class="form-group clearfix" '.$this->filter_attr($option).'><label for="wpcom_'.$id.'" class="form-label">'.$title.'</label><div class="form-input"><input type="text" class="form-control" id="wpcom_'.$id.'" name="'.$name.'" value="'.esc_attr($value).'">'.$notice.'</div></div>';
                break;

            case 'radio':
            case 'r':
                $html = '';
                foreach ($option['options'] as $opk => $opv) {
                    $html .= $opk==$value ? '<label class="radio-inline"><input type="radio" name="'.$name.'" checked value="'.$opk.'">'.$opv.'</label>':'<label class="radio-inline"><input type="radio" name="'.$name.'" value="'.$opk.'">'.$opv.'</label>';
                }
                $output = '<div class="form-group clearfix" '.$this->filter_attr($option).'><label for="wpcom_'.$id.'" class="form-label">'.$title.'</label><div class="form-input">'.$html . $notice.'</div></div>';
                break;

            case 'checkbox':
            case 'cb':
                $html = '';
                foreach ($option['options'] as $opk=>$opv) {
                    $checked = '';
                    if(is_array($value)){
                        foreach($value as $v){
                            if($opk==$v) $checked = ' checked';
                        }
                    }else{
                        if($opk==$value) $checked = ' checked';
                    }
                    $html .= '<label class="checkbox-inline"><input type="checkbox" name="'.$name.'[]"'.$checked.' value="'.$opk.'">'.$opv.'</label>';
                }
                $output = '<div class="form-group clearfix" '.$this->filter_attr($option).'><label for="wpcom_'.$id.'" class="form-label">'.$title.'</label><div class="form-input">'.$html . $notice.'</div></div>';
                break;

            case 'info':
            case 'i':
                $output = '<div class="form-group clearfix" '.$this->filter_attr($option).'><label class="form-label">'.$title.'</label><div class="form-input" style="padding-top:7px;">'.$value . $notice.'</div></div>';
                break;

            case 'select':
            case 's':
                $html = '';
                $need_empty = true;
                foreach ($option['options'] as $opk => $opv) {
                    if($opk === '') $need_empty = false;
                    $html .= $opk==$value ? '<option selected value="'.$opk.'">'.$opv.'</option>' : '<option value="'.$opk.'">'.$opv.'</option>';
                }
                if($need_empty){
                    $html = '<option value="">--请选择--</option>' . $html;
                }
                $output = '<div class="form-group clearfix" '.$this->filter_attr($option).'><label for="wpcom_'.$id.'" class="form-label">'.$title.'</label><div class="form-input"><select class="form-control" id="wpcom_'.$id.'" name="'.$name.'">'.$html.'</select>'.$notice.'</div></div>';
                break;

            case 'textarea':
            case 'ta':
                $output = '<div class="form-group clearfix" '.$this->filter_attr($option).'><label for="wpcom_'.$id.'" class="form-label">'.$title.'</label><div class="form-input"><textarea class="form-control" rows="'.$rows.'" id="wpcom_'.$id.'" name="'.$name.'">'.esc_textarea($value).'</textarea>'.$notice.'</div></div>';
                break;

            case 'editor':
            case 'e':
                echo '<div class="form-group clearfix" '.$this->filter_attr($option).'><label for="wpcom_'.$id.'" class="form-label">'.$title.'</label><div class="form-input">';
                wp_editor( wpautop( $value ), 'wpcom_'.$id, WPCOM_ADMIN_UTILS_FREE::editor_settings(array('textarea_name' => $name, 'textarea_rows' => $rows)) );
                echo $notice.'</div></div>';
                break;

            case 'upload':
            case 'u':
                $output = '<div class="form-group clearfix" '.$this->filter_attr($option).'><label for="wpcom_'.$id.'" class="form-label">'.$title.'</label><div class="form-input"><input type="text" class="form-control" id="wpcom_'.$id.'" name="'.$name.'" value="'.esc_attr($value).'">'.$notice.'</div><div class="form-input-btn"><button id="wpcom_'.$id.'_upload" type="button" class="button upload-btn"><i class="material-icons">&#xe3f4;</i> 上传</button></div></div>';
                break;

            case 'attachment':
            case 'at':
                $img = $value && is_numeric($value) ? wp_get_attachment_url($value) : $value;
                $html = '<div class="input-img-wrap"><div class="input-img"'.($img ? '' : ' style="display:none;"').'><img src="'.esc_url($img).'" /><i class="input-img-close dashicons dashicons-no-alt"></i></div><div class="input-img-add"></div><input type="hidden" id="wpcom_'.$id.'" name="'.$name.'" value="'.esc_attr($value).'"></div>';
                $output = '<div class="form-group clearfix" '.$this->filter_attr($option).'><label for="wpcom_'.$id.'" class="form-label">'.$title.'</label><div class="form-input">'.$html.$notice.'</div></div>';
                break;

            case 'color':
            case 'c':
                $output = '<div class="form-group clearfix" '.$this->filter_attr($option).'><label for="wpcom_'.$id.'" class="form-label">'.$title.'</label><div class="form-input"><input class="color-picker" type="text"  name="'.$name.'" value="'.esc_attr($value).'">'.$notice.'</div></div>';
                break;

            case 'page':
            case 'p':
                $html = '<option value="">--请选择--</option>';
                $pages = WPCOM_ADMIN_UTILS_FREE::get_all_pages();
                foreach ($pages as $page) {
                    $html.=$page['ID']==$value?'<option selected value="'.$page['ID'].'">'.$page['title'].'</option>':'<option value="'.$page['ID'].'">'.$page['title'].'</option>';
                }
                $output = '<div class="form-group clearfix" '.$this->filter_attr($option).'><label for="wpcom_'.$id.'" class="form-label">'.$title.'</label><div class="form-input"><select class="form-control" id="wpcom_'.$id.'" name="'.$name.'">'.$html.'</select>'.$notice.'</div></div>';
                break;

            case 'cat_single':
            case 'cs':
                $html = '<option value="">--请选择--</option>';
                $items = WPCOM_ADMIN_UTILS_FREE::category($tax);
                foreach ($items as $key => $val) {
                    $html.=$key==$value?'<option selected value="'.$key.'">'.$val.'</option>':'<option value="'.$key.'">'.$val.'</option>';
                }
                $output = '<div class="form-group clearfix" '.$this->filter_attr($option).'><label for="wpcom_'.$id.'" class="form-label">'.$title.'</label><div class="form-input"><select class="form-control" id="wpcom_'.$id.'" name="'.$name.'">'.$html.'</select>'.$notice.'</div></div>';
                break;

            case 'cat_multi':
            case 'cm':
                $html = '';
                $items = WPCOM_ADMIN_UTILS_FREE::category($tax);
                foreach ($items as $key => $val) {
                    $checked = '';
                    if(is_array($value)){
                        foreach($value as $v){
                            if($key==$v) $checked = ' checked';
                        }
                    }else{
                        if($key==$value) $checked = ' checked';
                    }
                    $html.='<label class="checkbox-inline"><input name="'.$name.'[]"'.$checked.' type="checkbox" value="'.$key.'"> '.$val.'</label>';
                }
                $output = '<div class="form-group clearfix" '.$this->filter_attr($option).'><label for="wpcom_'.$id.'" class="form-label">'.$title.'</label><div class="form-input cat-checkbox-list" data-name="'.$name.'">'.$html.$notice.'</div></div>';
                break;
            case 'cat_multi_sort':
            case 'cms':
                $html = '';
                $items = WPCOM_ADMIN_UTILS_FREE::category($tax);
                $value = $value ? $value : array();
                foreach ($value as $item) {
                    $category = get_term( $item, $tax );
                    $html.='<label class="checkbox-inline"><input name="'.$name.'[]" checked type="checkbox" value="'.$item.'"> '.$category->name.'</label>';
                }
                foreach ($items as $key => $val) {
                    if(!in_array($key, $value)){
                        $html.='<label class="checkbox-inline"><input name="'.$name.'[]" type="checkbox" value="'.$key.'"> '.$val.'</label>';
                    }
                }
                $output = '<div class="form-group clearfix" '.$this->filter_attr($option).'><label for="wpcom_'.$id.'" class="form-label">'.$title.'</label><div class="form-input"><div class="cat-checkbox-list j-cat-sort" data-name="'.$name.'">'.$html.'</div><div>'.$notice.'</div></div></div>';
                break;
            case 'toggle':
            case 't':
                $output = '<div class="form-group clearfix" '.$this->filter_attr($option).'><label for="wpcom_'.$id.'" class="form-label">'.$title.'</label><div class="form-input toggle-wrap">';
                if($value=='1'){
                    $output .= '<div class="toggle active"></div>';
                }else{
                    $output .= '<div class="toggle"></div>';
                }
                $output .= '<input type="hidden" id="wpcom_'.$id.'" name="'.$name.'" value="'.esc_attr($value).'">'.$notice.'</div></div>';
                break;
            case 'repeat':
            case 'rp':
                /*
                 * $this->options 保存的数据
                 * $this->options[$option->options[0]->name] 重复数据的第一个属性保持的值
                 * 每个属性根据添加个数会有多个，以数组形式保存
                 */
                $option['options'][0]['name'] = isset($option['options'][0]['name']) ? $option['options'][0]['name'] : (isset($option['options'][0]['n']) ? $option['options'][0]['n'] : '');
                $len = count(isset($this->options[$option['options'][0]['name']]) && $this->options[$option['options'][0]['name']] ? $this->options[$option['options'][0]['name']] : array());
                $len = $len ? $len : 1;
                $index = array();
                if(isset($this->options[$option['options'][0]['name']]) && $this->options[$option['options'][0]['name']]){
                    foreach ($this->options[$option['options'][0]['name']] as $a => $b) {
                        $index[] = $a;
                    }
                }

                $output = '<div class="form-group clearfix" '.$this->filter_attr($option).'>';
                if($title){
                    $output .= '<label for="wpcom_'.$id.'" class="form-label">'.$title.'</label><div class="form-input">';
                }

                $output .= '<div class="wpcom-panel-repeat">';
                for($i=0; $i<$len; $i++) {
                    $j = isset($index[$i]) ? $index[$i] : $i;
                    $output .= '<div class="repeat-wrap" data-id="'.$i.'">';
                    ob_start();
                    $x = 0;
                    foreach ($option['options'] as $o) {
                        $arg = array();
                        foreach($o as $k=>$v){
                            $arg[$k] = $v;
                        }
                        $o['name'] = isset($o['name']) ? $o['name'] : (isset($o['n']) ? $o['n'] : '');
                        $arg['id'] = $o['name'] . '_' . $i;
                        $arg['name'] = $o['name'] . '['.$i.']';
                        $arg['oname'] = $o['name'];
                        $this->option_item($arg, 1, $j);
                        $x++;
                    }
                    $output .= ob_get_contents();
                    ob_end_clean();
                    $output .= $i==0 ? '</div>':'<div class="repeat-action"><div class="repeat-item repeat-up j-repeat-up"><i class="dashicons dashicons-arrow-up-alt"></i></div><div class="repeat-item repeat-down j-repeat-down"><i class="dashicons dashicons-arrow-down-alt"></i></div><div class="repeat-item repeat-del j-repeat-del"><i class="dashicons dashicons-no-alt"></i></div></div></div>';
                }
                $output .= '<div class="repeat-btn-wrap"><button type="button" class="button j-repeat-add" id="wpcom_'.$name.'"><i class="dashicons dashicons-plus"></i> '.($title ? '添加'.$title : '添加选项') . '</button></div></div>';
                if($title){
                    $output .= '</div>';
                }
                $output .= '</div>';
                break;
            case 'wrapper':
            case 'w':
                $output = '<div class="wpcom-panel-wrapper" '.$this->filter_attr($option).'>';
                ob_start();
                if(isset($option['options'])) { $x=0; foreach ($option['options'] as $o) {
                    if($repeat > -1){
                        $o['oname'] = isset($o['name']) ? $o['name'] : (isset($o['n']) ? $o['n'] : '');
                        $o['id'] = $o['oname'] . '_' . $repeat;
                        $o['name'] =$o['oname'] . '['.$repeat.']';
                    }
                    $this->option_item($o, $x);
                    $x++;
                }}
                $output .= ob_get_contents();
                ob_end_clean();
                $output .= '</div>';
                break;
            case 'version':
                $output = '<div class="form-group clearfix"><label class="form-label">'.$title.'</label><div class="form-input" style="padding-top:5px;">'.(isset($option['ver']) ? $option['ver'] : $this->version).' <a class="check-version" id="j-check-version" data-action="'.(isset($option['action']) ? $option['action'] : '').'" href="javascript:;">检查更新</a>'.$notice.'</div></div>';
                break;
            default:
                break;
        }

        echo wp_kses($output, WPCOM_ADMIN_UTILS_FREE::allowed_html());
    }

    private function filter_attr($item){
        $filter = isset($item['filter']) ? $item['filter'] : (isset($item['f']) ? $item['f'] : '');
        if($filter){
            return 'filter="'.esc_attr($filter).'"';
        }
    }

    private function form_options(){
        $options = array();
        $options = apply_filters($this->plugin_slug . '_form_options', $options);
        return $options;
    }

    function form_action(){
        $nonce = isset($_POST[$this->key . '_nonce']) ? sanitize_text_field($_POST[$this->key . '_nonce']) : '';

        // Check nonce
        if ( ! $nonce || ! wp_verify_nonce( $nonce, $this->key . '_options' ) ){
            return;
        }

        $data = $_POST;
        $options = array();

        if( isset($data) && $data ) {
            unset($data[$this->key . '_nonce']);
            unset($data['_wp_http_referer']);
            unset($data['submit']);
            foreach($data as $key => $value){
                $options[$key] = $value && is_string($value) ? sanitize_text_field(stripcslashes($value)) : $value;
            }
        }

        do_action($this->plugin_slug . '_panel_form');

        $o = wp_json_encode($options, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
        $this->update_option($this->key, $o);
        $this->options = $options;
    }

    private function update_option($option_name, $value, $autoload='yes'){
        $res = update_option($option_name, $value, $autoload );
        if( !$res ){
            global $wpdb;
            $option = @$wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->options WHERE option_name = %s", $option_name ) );
            $value = maybe_serialize( $value );
            if(null !== $option) {
                $res = $wpdb->update($wpdb->options,
                    array('option_value' => $value, 'autoload' => $autoload),
                    array('option_name' => $option_name)
                );
            }else{
                $res = $wpdb->query( $wpdb->prepare( "INSERT INTO `$wpdb->options` (`option_name`, `option_value`, `autoload`) VALUES (%s, %s, %s) ON DUPLICATE KEY UPDATE `option_name` = VALUES(`option_name`), `option_value` = VALUES(`option_value`), `autoload` = VALUES(`autoload`)", $option_name, $value, $autoload ) );
            }
        }
        wp_cache_delete( $option_name, 'options' );
        return $res;
    }

    public function options_filter($pre_option, $option){
        global $wpdb;
        if(false !== $pre_option) return $pre_option;
        $alloptions = wp_load_alloptions();
        if ( isset( $alloptions[ $option ] ) ) {
            $value = $alloptions[ $option ];
        } else {
            $value = wp_cache_get( $option, 'options' );
            if ( false === $value ) {
                $row = $wpdb->get_row( $wpdb->prepare( "SELECT option_value FROM $wpdb->options WHERE option_name = %s LIMIT 1", $option ) );
                if ( is_object( $row ) ) {
                    $value = $row->option_value;
                    wp_cache_add( $option, $value, 'options' );
                }
            }
        }
        $value = maybe_unserialize( $value );
        if(is_string($value)) $value = json_decode($value, true);
        return apply_filters( "option_{$option}", $value, $option );
    }
    public function add_metadata($check, $object_id, $meta_key, $meta_value){
        global $wpdb;
        $key = preg_replace('/^wpcom_/i', '', $meta_key);
        if ( $key !== $meta_key || (('_wpcom_metas' === $meta_key || $meta_key === $wpdb->get_blog_prefix() . '_wpcom_metas') && is_array($meta_value)) ) {
            $filter = current_filter();
            $pre_key = '_wpcom_metas';
            if( $filter=='add_post_metadata' || $filter=='update_post_metadata' ){
                $meta_type = 'post';
            }else if( $filter=='add_term_metadata' || $filter=='update_term_metadata' ){
                $meta_type = 'term';
            }else{
                $pre_key = $wpdb->get_blog_prefix() . '_wpcom_metas';
                $meta_type = 'user';
            }
        }
        if ( $key !== $meta_key ) {
            $exclude = apply_filters("wpcom_exclude_{$meta_type}_metas", array());
            if(in_array($key, $exclude)) return $check;

            $metas = call_user_func("get_{$meta_type}_meta", $object_id, $pre_key, true);
            $pre_value = '';
            if( $metas ) {
                if( isset($metas[$key]) ) $pre_value = $metas[$key];
                $metas[$key] = $meta_value;
            } else {
                $metas = array(
                    $key => $meta_value
                );
            }
            if($meta_value === '') unset($metas[$key]);

            $_metas = wp_json_encode($metas, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
            $result = self::update_metadata($meta_type, $object_id, $pre_key, $_metas);

            if( $result && $meta_value != $pre_value && ($filter=='add_user_metadata' || $filter=='update_user_metadata') ) {
                do_action( 'wpcom_user_meta_updated', $object_id, $meta_key, $meta_value, $pre_value );
            }

            if($result) {
                wp_cache_delete($object_id, $meta_type . '_meta');
                return true;
            }
        }else if(('_wpcom_metas' === $meta_key || $meta_key === $wpdb->get_blog_prefix() . '_wpcom_metas') && is_array($meta_value)){
            if(self::update_metadata( $meta_type, $object_id, $pre_key, wp_json_encode($meta_value, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) )) return true;
        }
        return $check;
    }

    static function update_metadata($type, $id, $key, $value){
        global $wpdb;
        $table = _get_meta_table($type);
        $column = sanitize_key($type . '_id');
        $value = maybe_serialize($value);
        if( $wpdb->get_var( $wpdb->prepare(
            "SELECT COUNT(*) FROM $table WHERE meta_key = %s AND $column = %d",
            $key, $id ) ) ){
            $where = array( $column => $id, 'meta_key' => $key );
            $result = $wpdb->update( $table, array('meta_value' => $value), $where );
        }else{
            $result = $wpdb->insert( $table, array(
                $column => $id,
                'meta_key' => $key,
                'meta_value' => $value
            ) );
        }
        if(isset($result)) return $result;
    }

    public function meta_filter( $res, $object_id, $meta_key, $single, $meta_type){
        global $wpdb;
        $key = preg_replace('/^wpcom_/i', '', $meta_key);
        $filter = current_filter();
        if ( $key !== $meta_key ) {
            $metas_key = '_wpcom_metas';
            if( $filter === 'get_user_metadata' ) $metas_key = $wpdb->get_blog_prefix() . '_wpcom_metas';

            // 排除字段直接读取
            $exclude = apply_filters("wpcom_exclude_{$meta_type}_metas", array());
            if(in_array($key, $exclude)) {
                $meta_cache = wp_cache_get( $object_id,  $meta_type . '_meta' );
                if ( ! $meta_cache ) {
                    $meta_cache = update_meta_cache( $meta_type, array( $object_id ) );
                    $meta_cache = $meta_cache[ $object_id ];
                }
                if ( isset( $meta_cache[ $meta_key ] ) ) {
                    if ( $single ) {
                        return maybe_unserialize( $meta_cache[ $meta_key ][0] );
                    } else {
                        return array_map( 'maybe_unserialize', $meta_cache[ $meta_key ] );
                    }
                }
            }

            $metas = call_user_func("get_{$meta_type}_meta", $object_id, $metas_key, true);

            if( isset($metas) && isset($metas[$key]) ) {
                if(in_array($key, $exclude)) {
                    add_metadata($meta_type, $object_id, $meta_key, $metas[$key], $single);
                }
                if( $single && is_array($metas[$key]) )
                    return array( $metas[$key] );
                else if( !$single && empty($metas[$key]) )
                    return array();
                else
                    return array($metas[$key]);
            }
        }else if(($meta_key === '_wpcom_metas' || ($filter === 'get_user_metadata' && $meta_key === $wpdb->get_blog_prefix() . '_wpcom_metas')) && !$res){
            $meta_cache = wp_cache_get( $object_id,  $meta_type . '_meta' );
            if ( ! $meta_cache ) {
                $meta_cache = update_meta_cache( $meta_type, array( $object_id ) );
                $meta_cache = $meta_cache[ $object_id ];
            }
            if ( isset( $meta_cache[ $meta_key ] ) ) {
                $_res = maybe_unserialize( $meta_cache[ $meta_key ][0] );
                if($_res && is_string($_res)) {
                    $_res = json_decode($_res, true);
                    if(is_array($_res)) $res = array($_res);
                }
            }
        }
        return $res;
    }
}