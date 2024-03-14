<?php
namespace PDFPro\PostType;

class PDFPoster{
    protected static $_instance = null;
    protected $post_type = 'pdfposter';

    /**
     * construct function
     */
    public function __construct(){
        add_action('init', [$this, 'init']);
        if ( is_admin() ) {
            add_filter( 'post_row_actions', [$this, 'pdfp_remove_row_actions'], 10, 2 );
            add_action('edit_form_after_title', [$this, 'pdfp_shortcode_area']);
            add_filter('manage_pdfposter_posts_columns', [$this, 'pdfp_columns_head_only_podcast'], 10);
            add_action('manage_pdfposter_posts_custom_column', [$this, 'pdfp_columns_content_only_podcast'], 10, 2);
            add_filter('post_updated_messages', [$this, 'pdfp_updated_messages']);

            add_action('admin_head-post.php', [$this, 'pdfp_hide_publishing_actions']);
            add_action('admin_head-post-new.php', [$this, 'pdfp_hide_publishing_actions']);	
            add_filter( 'gettext', [$this, 'pdfp_change_publish_button'], 10, 2 );
            
            add_filter( 'filter_block_editor_meta_boxes', [$this, 'remove_metabox'] );
            add_action('use_block_editor_for_post', [$this, 'forceGutenberg'], 10, 2);

            add_action( 'add_meta_boxes', [$this, 'myplugin_add_meta_box'] );
            
        }
    }

    /**
     * Create instance function
     */
    public static function instance(){
        if(self::$_instance === null){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * init
     */
    public function init(){

        register_post_type( 'pdfposter',
            array(
                'labels' => array(
                    'name' => __( 'PDF Poster', 'pdfp'),
                    'singular_name' => __( 'Pdf Poster' , 'pdfp'),
                    'add_new' => __( 'Add New Pdf' , 'pdfp'),
                    'add_new_item' => __( 'Add New Pdf', 'pdfp' ),
                    'edit_item' => __( 'Edit', 'pdfp' ),
                    'new_item' => __( 'New Pdf', 'pdfp' ),
                    'view_item' => __( 'View Pdf', 'pdfp' ),
                    'search_items'       => __( 'Search Pdf', 'pdfp'),
                    'not_found' => __( 'Sorry, we couldn\'t find the Pdf file you are looking for.', 'pdfp' )
                ),
            'public' => false,
            'show_ui' => true, 									
            // 'publicly_queryable' => true,
            // 'exclude_from_search' => true,
            'show_in_rest' => true,
            'menu_position' => 14,
            'menu_icon' =>PDFPRO_PLUGIN_DIR .'/img/icn.png',
            'has_archive' => false,
            'hierarchical' => false,
            'capability_type' => 'post',
            'rewrite' => array( 'slug' => 'pdfposter' ),
            'supports' => array( 'title', 'editor' ),
            'template' => [
                    ['pdfp/pdfposter']
                ],
            'template_lock' => 'all',
            )
		);
						
            // // 'publicly_queryable' => true,
            // // 'exclude_from_search' => true,
            // 'show_in_rest' => true,
            // 'supports' => array('title', 'editor'),
            // 'template' => [
            //     ['pdfp/podcast']
            // ],
            // 'template_lock' => 'all',
    }

    /**
     * Remove Row
     */
    function pdfp_remove_row_actions( $idtions ) {
        global $post;
        if( $post->post_type == $this->post_type ) {
            unset( $idtions['view'] );
            unset( $idtions['inline hide-if-no-js'] );
        }
        return $idtions;
    }

    function pdfp_shortcode_area(){
        global $post;	
        if($post->post_type== $this->post_type){
        ?>	
        <div class="pdfp_playlist_shortcode">
                <div class="shortcode-heading">
                    <div class="icon"><span class="dashicons dashicons-pdf"></span> <?php _e("PDF Poster", "pdfp") ?></div>
                    <div class="text"> <a href="https://bplugins.com/support/" target="_blank"><?php _e("Supports", "pdfp") ?></a></div>
                </div>
                <div class="shortcode-left">
                    <h3><?php _e("Shortcode", "pdfp") ?></h3>
                    <p><?php _e("Copy and paste this shortcode into your posts, pages and widget:", "pdfp") ?></p>
                    <div class="shortcode" selectable>[pdf id='<?php echo esc_attr($post->ID); ?>']</div>
                </div>
                <div class="shortcode-right">
                    <h3><?php _e("Template Include", "pdfp") ?></h3>
                    <p><?php _e("Copy and paste the PHP code into your template file:", "pdfp"); ?></p>
                    <div class="shortcode">&lt;?php echo do_shortcode('[pdf id="<?php echo esc_html($post->ID); ?>"]');
                    ?&gt;</div>
                </div>
            </div>
        <?php   
        }
    }
    
    // CREATE TWO FUNCTIONS TO HANDLE THE COLUMN
    function pdfp_columns_head_only_podcast($defaults) {
        unset($defaults['date']);
        $defaults['shortcode'] = 'ShortCode';
        $defaults['raw_shortCode'] = esc_html__('ShortCode For Raw PDF View', 'pdfp');
        $defaults['date'] = 'Date';
        return $defaults;
    }

    function pdfp_columns_content_only_podcast($column_name, $post_ID) {
        if ($column_name == 'shortcode') {
            echo '<div class="pdfp_front_shortcode"><input style="text-align: center; border: none; outline: none; background-color: #1e8cbe; color: #fff; padding: 4px 10px; border-radius: 3px;" value="[pdf id='. esc_attr($post_ID) . ']" ><span class="htooltip">Copy To Clipboard</span></div>';
        }
        if ($column_name == 'raw_shortCode') {
            // show content of 'directors_name' column
            echo '<div class="pdfp_front_shortcode"><input style="text-align: center; border: none; outline: none; background-color: #1e8cbe; color: #fff; padding: 4px 10px; border-radius: 3px;" value="[raw_pdf id='. esc_attr($post_ID) . ']" ><span class="htooltip">Copy To Clipboard</span></div>';
        }
    }
    
    function pdfp_updated_messages( $messages ) {
        $messages[$this->post_type][1] = __('Player updated ');
        return $messages;
    }

    public function pdfp_hide_publishing_actions(){
        global $post;
        if($post->post_type == $this->post_type){
            echo '
                <style type="text/css">
                    #misc-publishing-actions,
                    #minor-publishing-actions{
                        display:none;
                    }
                </style>
            ';
        }
    }

    function remove_metabox($metaboxs) {
        global $post;
        $screen = get_current_screen();

        if($screen->post_type === $this->post_type){
            return false;
        }
        return $metaboxs;
    }

    public function forceGutenberg($use, $post)
    {
        $option = get_option('fpdf_option', []);
        if(isset($option['pdfp_gutenberg_enable'])){
            $gutenberg = (boolean) $option['pdfp_gutenberg_enable'];
        }else {
            $gutenberg = (boolean) get_option('pdfp_gutenberg_enable', false);
        }
        $isGutenberg = (boolean) get_post_meta($post->ID, 'isGutenberg', true);
        $pluginUpdated = 1630223686;
        $publishDate = get_the_date('U', $post);
        $currentTime = current_time("U");
    
        if ($this->post_type === $post->post_type) {
            if($gutenberg){
                if($post->post_status == 'auto-draft' ){
                    update_post_meta($post->ID, 'isGutenberg', true);
                    return true;
                }else {
                    if($isGutenberg){
                        return true;
                    }else {
                        remove_post_type_support($this->post_type, 'editor');
                        return false;
                    }
                }
            }else {
                if($isGutenberg){
                    return true;
                }else {
                    remove_post_type_support($this->post_type, 'editor');
                    return false;
                }
            }
        }

        return $use;
    }

    function pdfp_change_publish_button( $translation, $text ) {
        if ( $this->post_type == get_post_type())
        if ( $text == 'Publish' )
            return 'Save';
        return $translation;
    }

    /**
     * register metabox
     */
    function myplugin_add_meta_box() {
        add_meta_box(
            'Shortcode',
            __( 'New Feature ! Quick Embed', 'ytp' ),
            [$this, 'pdfp_pro_shortcode_wid'],
            'pdfposter',
            'side',
            'default'
        );
    }

    function pdfp_pro_shortcode_wid(){
        $shortcode="[pdf_embed url='your_file_url']";
        echo 'Now you can embed pdf without listing ! just use the Embed shortCode below, and start saving your time.';
        echo '<br/><br/><input type="text" style="font-size: 12px; border: none; box-shadow: none; padding: 4px 8px; width:100%; background:#1e8cbe; color:white;"  onfocus="this.select();" readonly="readonly"  value="'.esc_attr($shortcode).'" /><br/><br/>';
        echo '<p><a class="button button-primary button-large" href="options-general.php?page=pdf_poster_settings" target="_blank">ShortCode Global Settings</a></p>';
    }

    
}
