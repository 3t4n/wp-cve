<?php
if(!defined('WPINC')){ die; }

if (!class_exists('THFAQF_Admin_Settings_FAQ')):

class THFAQF_Admin_Settings_FAQ extends THFAQF_Admin_Settings{
    public function __construct(){
        parent::__construct();
        $this->add_custom_taxonomies();
        $this->create_faq_post_type();
        $this->create_faq_comment_post_type();
    }

    // create faq custom post type
    private function create_faq_post_type(){
        $faq_labels = array(
            'name'                =>   ('FAQ'),
            'singular_name'       =>   ('FAQ'),
            'add_new'             => __('Add New', 'advanced-faq-manager'),
            'all_items'           => __('All FAQ', 'advanced-faq-manager'),
            'add_new_item'        => __('Add New FAQ', 'advanced-faq-manager'),
            'edit_item'           => __('Edit FAQ', 'advanced-faq-manager'),
            'new_item'            => __('New FAQ', 'advanced-faq-manager'),
            'view_item'           => __('Vew FAQ', 'advanced-faq-manager'),
            'search_items'        => __('Search FAQ', 'advanced-faq-manager'),
            'not_found'           => __('No FAQ Found', 'advanced-faq-manager'),
            'not_found_in_trash'  => __('No FAQ Found in Trash ', 'advanced-faq-manager'),
            'parent_item_colon'   => 'parent item',
            'menu_name'           => __('FAQs', 'advanced-faq-manager'),
            'exclude_from_search' => true
        );

        $faq_args = array(
            'labels'              => $faq_labels,
            'public'              => true,
            'publicly_queryable'  => false,
            'has_archive'         => true,
            'rewrite'             => true,
            'show_in_menu'        => true,
            'query_var'           => true,
            'capability_type'     => 'post',
            'has_archive'         => false,
            'hierarchical'        => false,
            'menu_icon'           => 'dashicons-portfolio',
            'supports'            => array('title'),
            'menu_position'       => 4,
            'exclude_from_search' => false,
        );

        register_post_type('faq', $faq_args);
    }

    public function create_faq_comment_post_type(){
        $user_comment_labels =   array(
            'name'                =>   ('FAQ Comments'),
            'singular_name'       =>   ('FAQ Comments'),
            'add_new'             => __('', 'advanced-faq-manager'),
            'all_items'           => __('', 'advanced-faq-manager'),
            'add_new_item'        => __('Add New Post','advanced-faq-manager'),
            'edit_item'           => __('Edit FAQ Comment','advanced-faq-manager'),
            'new_item'            => __('New Post','advanced-faq-manager'),
            'view_item'           => __('Vew comment','advanced-faq-manager'),
            'search_items'        => __('Search comment','advanced-faq-manager'),
            'not_found'           => __('No Post comment','advanced-faq-manager'),
            'not_found_in_trash'  => __('No comment Found in Trash ','advanced-faq-manager'),
            'parent_item_colon'   => 'parent item',
            'menu_name'           => __('USER COMMENTS', 'advanced-faq-manager'),
            'exclude_from_search' => true
         );
        $user_comment_arg = array(
            'labels'              => $user_comment_labels,
            'public'              => true,
            'publicly_queryable'  => false,
            'has_archive'         => true,
            'rewrite'             => true,
            'show_in_menu'        => false,
            'query_var'           => true,
            'capability_type'     => 'post',
            'capabilities'        => array('create_posts' => false),
            'map_meta_cap'        =>  true, 
            'has_archive'         => false,
            'hierarchical'        => false,
            'supports'            => array('title','editor',),
            'menu_position'       => 6,
            'exclude_from_search' => false,
        );
        register_post_type( 'user-comment', $user_comment_arg);
    }


    public function add_custom_taxonomies() {
        register_taxonomy(
            'faq_category', 'faq', array(
            'hierarchical'      => true,
            'labels'            => array(
            'name'              => __( 'FAQ Category', 'advanced-faq-manager'),
            'singular_name'     => __( 'FAQ Category', 'advanced-faq-manager'),
            'search_items'      => __( 'Search Category','advanced-faq-manager'),
            'all_items'         => __( 'All Category','advanced-faq-manager'),
            'parent_item'       => __( 'Parent Category','advanced-faq-manager'),
            'parent_item_colon' => __( 'Parent Category:','advanced-faq-manager'),
            'edit_item'         => __( 'Edit Category','advanced-faq-manager'),
            'update_item'       => __( 'Update Category','advanced-faq-manager'),
            'add_new_item'      => __( 'Add New Category','advanced-faq-manager'),
            'new_item_name'     => __( 'New Category Name','advanced-faq-manager'),
            'menu_name'         => __( 'FAQ Category','advanced-faq-manager'),
        ),
        'rewrite'               => array(
              'slug'            => 'locations', 
              'with_front'      => false, 
            ),
        ));
    }

    public function metabox_section(){
        $this->add_meta_box_shortcode_display_faq_group();
        $this->add_meta_box_shortcode_display();
        $this->add_meta_box_color_settings();
        $this->add_meta_box_faqs();
    }

    /******* META BOX - Color Settings *******/
    /*****************************************/
    public function add_meta_box_color_settings(){
        add_meta_box('thfaq_color_settings', 'FAQ Display Settings', array($this, 'render_meta_box_color_settings'), 'faq', 'side');
    }

    public function render_meta_box_color_settings($post){
        if(!empty($post)){
            $post_id = get_the_ID();

            $override_fields = THFAQF_Utils::get_settings_override_fields();
            $global_settings = THFAQF_Utils::get_faq_settings();
            $faq_individual_settings = THFAQF_Utils::get_faq_individual_settings();
            $local_settings  = get_post_meta($post_id, THFAQF_Utils::OPTION_KEY_FAQ_SETTINGS_POST, true);
            $individual_settings = get_post_meta($post_id, THFAQF_Utils::OPTION_KEY_FAQ_INDIVIDUAL_POST,true);
            $local_settings  = $this->prepare_local_settings($local_settings, $global_settings, $override_fields);
            $override_settings = isset($local_settings['override_global_settings']) ? $local_settings['override_global_settings'] : false;
            $override_settings = ($override_settings === 'yes' || $override_settings === true) ? 1 : 0;
            $override_checked  = $override_settings ? 'checked' : '';

            $atts = array(
                'input_width' => '80px', 
                'tab_cell_width' => '125px', 
            );

            ?>
            <table class="thfaqf-override-toggle-panel">
                <tr>
                    <?php $this->render_form_field_element($faq_individual_settings['enable_disable_search_option'],$individual_settings); ?>
                </tr>
                <tr>
                    <td>Override global settings</td>
                    <td class="pl-10">
                        <label class="thpladmin-switch">
                            <input type="checkbox" id="override_global_settings" name="override_global_settings" value= yes <?php echo $override_checked; ?> onchange="thfaqfEnableDisableOverrideSettings(this)"/>
                            <span class="thpladmin-slider"></span>
                        </label>
                    </td>
                </tr>
            </table>
            <table id="thfaqf-override-settings-panel" class="thfaqf-override-settings-panel">
                <?php
                foreach($override_fields as $key => $field){
                    if($key != 'override_global_settings'){
                        ?>
                        <tr>
                            <?php $this->render_form_field_element($field, $local_settings, $atts, true, false); ?>
                        </tr>
                        <?php
                    }
                }
                ?>
            </table>
            <?php
        }
    }

    private function prepare_local_settings($local_settings, $global_settings, $override_fields){
        $local_settings = is_array($local_settings) ? $local_settings : array();
        foreach($override_fields as $key => $field){
            $value = isset($local_settings[$key]) ? $local_settings[$key] : '';
            if(!$value){
                $value = isset($global_settings[$key]) ? $global_settings[$key] : $value;
                $local_settings[$key] = $value;
            }
        }
        return $local_settings;
    }

    /******* META BOX - Shortcode Display *******/
    /********************************************/
    public function add_meta_box_shortcode_display(){
        add_meta_box('thfaq_shortcode_display', 'Shortcode', array($this, 'render_meta_box_shortcode_display'), 'faq', 'side');
    }

    public function render_meta_box_shortcode_display(){
        $post_id = get_the_ID();
        $shortcode = '[faq id="'. $post_id .'"]';

        ?>
        <p class="shortcode-copy-field">
            <input type="text" class="copy_to_clipboard" value="<?php echo esc_html($shortcode); ?>" readonly="readonly">
            <span title="Copy to clipboard" class="dashicons dashicons-clipboard" onclick="thfaqfCopyText(this)"></span>
        </p>
        <?php
    }

    public function add_meta_box_shortcode_display_faq_group(){
        add_meta_box('thfaq_shortcode_display_faq_group', 'Shortcode for FAQ Group', array($this, 'render_meta_box_shortcode_display_faq_group'), 'faq', 'normal');
    }

    public function render_meta_box_shortcode_display_faq_group(){
        $post_id = get_the_ID();
        $shortcode = '[thfaq_group category="category_1,category_2,etc.." limit="-1"]';

        ?>
        <p class="shortcode-copy-field">
            <input style="width:97%;" type="text" class="copy_to_clipboard" value="<?php echo esc_html($shortcode); ?>" readonly="readonly">
            <span title="Copy to clipboard" class="dashicons dashicons-clipboard" onclick="thfaqfCopyText(this)"></span>
        </p>
        <?php
    }

    /******* META BOX - FAQs *******/
    /*******************************/
    public function add_meta_box_faqs(){
        add_meta_box('thfaq_faq_list', 'FAQs', array($this,'render_meta_box_faqs'), 'faq', 'normal', 'high');
    }

    public function render_meta_box_faqs($post){
        if(!empty($post)){
            $faq_form_html = '';
            $faq_items = get_post_meta($post->ID, THFAQF_Utils::OPTION_KEY_FAQ_ITEMS, true);

            if(is_array($faq_items) && isset($faq_items)){
                foreach($faq_items as $key => $faq_item){
                    if(empty($faq_item)){
                        unset($faq_item[$key]);
                    }
                    $faq_title   = isset($faq_item['faq_title']) ? $faq_item['faq_title'] : '';
                    $faq_content = isset($faq_item['faq_content']) ? $faq_item['faq_content'] : '';
                    $faq_comment   = isset($faq_item['faq_comment']) ? $faq_item['faq_comment'] : '';
                    $like_user_ids = isset($faq_item['like_user_ids']) ? $faq_item['like_user_ids'] : '';
                    $dislike_user_ids = isset($faq_item['dislike_user_ids']) ? $faq_item['dislike_user_ids'] : '';
                    $random_editor_id = isset($faq_item['random_editor_id']) ? $faq_item['random_editor_id'] : 0;
                    $faq_form_html .= $this->get_single_faq_form($faq_title, $faq_content, '',$random_editor_id,$faq_comment,$like_user_ids,$dislike_user_ids);
                }
            }
           
            if(!$faq_form_html){
                $faq_form_html = $this->get_single_faq_form('', '', 'thfaqf-active',1,'','','');
            }

            ?>
            <div id="thfaqf_faq_form" class="thfaqf_faq_form">
                <?php echo $faq_form_html; ?>
            </div>

            <div id="thfaqf_new_faq_form" style="display:none;">
                <?php
                    $new_faq_form = $this->get_single_faq_form('', '', 'thfaqf-active thfaqf-append-faq',1,'','','');
                    echo $new_faq_form;
                ?>
            </div>

            <div style="text-align: right;">
                <input type="button" value="+ Add new item" class="button mt-20" onclick="thfaqfAddFaqItem(this)">
            </div>
            <?php
        }
    }

    private function get_single_faq_form($title='', $content='', $wrapper_class='',$random_editor_id=false,$faq_comment='',$like_user_ids='',$dislike_user_ids=''){    
        ob_start();
        $rand_editor_id = $random_editor_id ? $random_editor_id : rand(1,10000);
        ?>
        <div class="thfaqf-single-form-wrapper <?php echo $wrapper_class; ?>" >
            <div class="thfaqf-single-form-header">
                <span class="faq-title"><?php echo wordwrap($title); ?></span>
                <span class="faq-delete dashicons dashicons-trash" onclick="thfaqfDeleteFaqItem(this)"></span>
                <span class="faq-edit dashicons dashicons-edit" onclick="thfaqfEditFaqItem(this)"></span>
                <span class="faq-clone" onclick="thfaqClone(this)"><i class="far fa-clone"></i></span>
            </div>
            <div class="thfaqf-single-form">
                <p>
                    <label class="faq-label">Title:</label>
                    <input type="text"  name="faq_title[]" value="<?php echo $title; ?>" placeholder="FAQ title" class="faq-input-title">
                </p>

                <label class="faq-label">Content:</label>

                <button type="button" class="button faq-insert-media" onclick="thfaqfInsertMedia(this, event)">
                    <span class="thfaqf-media-button-icon"></span> Add Media
                </button>
                <textarea name="faq_content[]" id = "<?php echo 'thfaq_editor_tinymce_'.$rand_editor_id; ?>" class="faq-input-content"><?php echo $content; ?></textarea>
                <input type="hidden"  name="faq_comment[]" value="<?php echo $faq_comment; ?>"/>
                <input type="hidden"  name="like_user_ids[]" value="<?php echo $like_user_ids; ?>"/>
                <input type="hidden"  name="dislike_user_ids[]" value="<?php echo $dislike_user_ids; ?>"/>
                <input type="hidden" class="random-editor-id" name="random_editor_id[]" value="<?php echo $rand_editor_id; ?>"/>

            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    public function save_faq_postdata($post_id, $post, $update){
        $post_type = $post->post_type;
        if($post_id && $post_type === 'faq' && isset($_POST['faq_title']) && $_POST['faq_title']){
            $submitted_title   = isset($_POST['faq_title']) ? $_POST['faq_title']: array();
            $submitted_content = isset($_POST['faq_content']) ? $_POST['faq_content'] : array();
            $cleaned_title     = array_filter($submitted_title);
            $cleaned_content   = array_filter($submitted_content);
            $count_title       = count($cleaned_title) ;
            $count_content     = count($cleaned_content);
            $count = $count_content >= $count_title ? $count_content : $count_title;
            $faq_items = get_post_meta($post_id, THFAQF_Utils::OPTION_KEY_FAQ_ITEMS, true);
            $faq_array = array();
            $fields = array('faq_title' => 'f_text','faq_content' => 'f_textarea','faq_comment' => 'hidden','like_user_ids' => 'hidden','dislike_user_ids' => 'hidden','random_editor_id' => 'hidden');
            if($count > 0){
                for($i=0; $i < $count; $i++){
                    foreach ($fields as $key => $type) {
                        $faq_field_value  = isset($_POST[$key][$i]) ? $_POST[$key][$i] : array();

                        if($type == 'f_text' || $type == 'f_textarea'){
                            $faq_field_value = htmlspecialchars($faq_field_value);
                        }else {
                            
                            $faq_field_value = sanitize_text_field($faq_field_value);
                        }

                        $faq_array[$i][$key] = $faq_field_value;

                    }
                }

                if(isset($faq_array)){
                    $result1 = update_post_meta($post_id, THFAQF_Utils::OPTION_KEY_FAQ_ITEMS, $faq_array);
                    $result2 = $this->save_settings_override($post_id);
                } else {
                    delete_post_meta($post_id, THFAQF_Utils::OPTION_KEY_FAQ_ITEMS);
                }
            }
        }
    }

    public function save_settings_override($post_id){
        $override_fields = THFAQF_Utils::get_settings_override_fields();
        $faq_individual_settings = THFAQF_Utils::get_faq_individual_settings();
        $is_override = false;
        if(isset($_POST['override_global_settings'])) {
            $is_override = $_POST['override_global_settings'];
        }

        $override_settings = array();
        $is_override = filter_var( $is_override, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE );

        if($is_override === 'yes' || $is_override === true){
            $override_settings['override_global_settings'] = true; 
            foreach($override_fields as $key => $field) {
                $override_settings[$key] = THFAQF_Admin_Settings_General::sanitize_field_value($_POST, $key, $field['type']);
            }
        }else {
            $override_settings = get_post_meta($post_id, THFAQF_Utils::OPTION_KEY_FAQ_SETTINGS_POST, true);
        }

        if(is_array($override_settings)){
            $override_settings['override_global_settings'] = $is_override;
        }

        $result = update_post_meta($post_id, THFAQF_Utils::OPTION_KEY_FAQ_SETTINGS_POST, $override_settings);
        $this->save_individual_settings($faq_individual_settings,$post_id);
        return $result;
    }

    private function save_individual_settings($faq_individual_settings,$post_id) {
        $individual_settings = array();
        foreach($faq_individual_settings as $key => $field) {
            $individual_settings[$key] = THFAQF_Admin_Settings_General::sanitize_field_value($_POST, $key, $field['type']);
        }
        $result = update_post_meta($post_id, THFAQF_Utils::OPTION_KEY_FAQ_INDIVIDUAL_POST, $individual_settings);
    }

    public function add_custom_column($columns){
        $posttype = get_post_type();
        $colum_title = 'Shortcode';
        $next_column_name = 'date';
        foreach($columns as $key => $value){
            if($key == $next_column_name && $posttype == 'faq'){
                $post_columns[$colum_title] = $colum_title;
            }
            $post_columns[$key] = $value;
        }
        return $post_columns;
    }

    public function add_custom_column_data($post_columns, $post_id){
        $posttype = get_post_type();
        if($post_columns === 'Shortcode' && $posttype == 'faq'){
            $shortcode = '[FAQ id="'. $post_id .'"]'
            ?>
            <input type="text" value="<?php echo esc_html($shortcode); ?>" readonly="readonly"> 
            <?php
        }
    }

    // checking premium pugin active or not
    public function check_premium_plugn_actve(){
        if(is_plugin_active('Advanced-faq-manager-pro/Advanced-faq-manager-pro.php')){
            $data =  'active';
        }else{
            $data =  'deactivate';
        }
        return $data;
    }

    public function faq_widget_settings($params){   
        global $footer_widget_num;
        if(isset($params[0]['id'])){
            $footer_widget_num++;
            $divider = 3;    
            $class = 'class="thfaqf-widget '; 
            $params[0]['before_widget'] = str_replace('class="', $class, $params[0]['before_widget']);
        }
        return $params;
    }
}

endif;


