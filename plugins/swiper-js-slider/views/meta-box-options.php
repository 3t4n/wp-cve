<?php

class Speedy_Custom_Meta_Box {

	public $meta_prefix = '_s_s_m_';
	public $post_type = 'swiper_js_slides';

    /* Constructor. */
    public function __construct() {
        if ( is_admin() ) {
            add_action( 'load-post.php',     array( $this, 'init_metabox' ) );
            add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
        }
    }
 
    /* Meta box initialization. */
    public function init_metabox() {
        add_action( 'add_meta_boxes', array( $this, 'add_metabox'  )        );
        add_action( 'save_post',      array( $this, 'save_metabox' ), 10, 2 );
    }
 
    /* Adds the meta box section. */
    public function add_metabox() {
    	add_meta_box(
            'swiper-js-slider-gallery',
            'Slide Images',
            array( $this, 'render_metabox_gallery' ),
            $this->post_type,
            'advanced',
            'high'
        );
        add_meta_box(
            'swiper-js-slider-settings',
            'Settings',
            array( $this, 'render_metabox_settings' ),
            $this->post_type,
            'advanced',
            'high'
        );
        add_meta_box(
            's-j-s-shortcode',
            'Shortcode',
            array( $this, 'render_metabox_shortcode' ),
            $this->post_type,
            'side',
            'low'
        );
    }
 
    /* Renders the meta box. */
    public function render_metabox_gallery( $post ) {
        // Add nonce for security and authentication.
        global $post;
        
        // Add nonce for security and authentication.
        wp_nonce_field( $this->meta_prefix.'nonce_action', $this->meta_prefix.'nonce' );
        
        // Use get_post_meta to retrieve an existing value from the database.
		$ids = get_post_meta($post->ID, $this->meta_prefix.'gallery_id', true);

        // Display the form, using the current value.
        ?>
	    <div class="gallery-form-table">
            <ul id="gallery-metabox-list">
                <?php if ($ids) { ?>
                    <?php foreach ($ids as $key => $value) : $image = wp_get_attachment_image_src($value); ?>
                      <li>
                        <input type="hidden" name="<?php echo $this->meta_prefix; ?>gallery_id[<?php echo $key; ?>]" value="<?php echo $value; ?>">
                        <img class="image-preview" src="<?php echo $image[0]; ?>">
                        <div class="actionButtons">
                            <a class="change-image button button-small" href="#" data-uploader-title="Change image" data-uploader-button-text="Change image">
                                <span class="dashicons dashicons-edit"></span>
                            </a>
                            <a class="remove-image button button-small" href="#">
                                <span class="dashicons dashicons-no-alt"></span>
                            </a>
                        </div>
                      </li>
                    <?php endforeach; ?>
                <?php } ?>
            </ul>
            <h4 class="noDataFound">No Images Selected.</h4>
        </div>
        
        <div class="addfooter">
            <a href="#" class="removeAll button button-secondary button-large">
                <span class="dashicons dashicons-trash"></span> 
                Empty Slider
            </a>
            <a href="#" class="saveAll button button-primary button-large">
                <span class="dashicons dashicons-admin-tools"></span>
                Save
            </a>
            <a  class="gallery-add button button-primary button-large" 
                href="#" 
                data-uploader-title="Add image(s) to gallery" 
                data-uploader-button-text="Add image(s)">
                    <span class="dashicons dashicons-plus-alt"></span> 
                    Add Images
            </a>
        </div>

        <?php
    }

    public function s_j_s_checkit($name, $array, $value){
        $result = '';
        if (is_array($array) && isset($array[$name])){ $result = $array[$name]; }
        if($result == ''){ $result = $value; }
        return $result;
    }

    public function render_metabox_settings( $post ) {

        global $post;

        wp_nonce_field( $this->meta_prefix.'nonce_action', $this->meta_prefix.'nonce' ); ?>

        <div class="searchHead">
            <ul class="dtbl">
                <li class="searchFilter"><input type="text" placeholder="Search in setting..." data-search></li>
            </ul>
        </div>
        
        <div class="swiperjsSection innerOptions">
        
            <ul class="nav-tab-side" data-update-hashbang="1">
                <li class="active"><a href="#sjs-tab-parameters">Parameters</a></li>
                <li><a href="#sjs-tab-autoplay">Autoplay</a></li>
                <li><a href="#sjs-tab-pagination">Pagination</a></li>
                <li><a href="#sjs-tab-navigation">Navigation</a></li>
                <li><a href="#sjs-tab-carousel">Carousel</a></li>
                <li><a href="#sjs-tab-breakpoints">Breakpoints</a></li>
            </ul>

            <div class="rightSide">
                <?php include_once( plugin_dir_path( __FILE__ ) . '/metabox/parameters-meta.php' ); ?>
                <?php include_once( plugin_dir_path( __FILE__ ) . '/metabox/autoplay-meta.php' ); ?>
                <?php include_once( plugin_dir_path( __FILE__ ) . '/metabox/pagination-meta.php' ); ?>
                <?php include_once( plugin_dir_path( __FILE__ ) . '/metabox/navigation-meta.php' ); ?>
                <?php include_once( plugin_dir_path( __FILE__ ) . '/metabox/carousel-meta.php' ); ?>
                <?php include_once( plugin_dir_path( __FILE__ ) . '/metabox/breakpoints-meta.php' ); ?>
            </div>

        </div>
        <div class="footerRight">
            <ul class="dtbl">
                <li class="documentation">Documentation: <a href="https://idangero.us/swiper/" target="_blank">https://idangero.us/swiper/</a></li>
                <li class="text-right">
                    <a href="#" class="saveAll button button-primary button-large">
                        <span class="dashicons dashicons-admin-tools"></span>
                        Save
                    </a>
                </li>
            </ul>
        </div>

        <?php
    }

    public function render_metabox_shortcode( $post ) {
        global $post;
        wp_nonce_field( $this->meta_prefix.'nonce_action', $this->meta_prefix.'nonce' ); ?>
            
            <div class="shortcodeWrap">
                <h4>Copy & Paste For Post & Pages.</h4>
                <input 
                    type="text" 
                    readonly="readonly" 
                    value='[swiper-js-slider id="<?php echo $post->ID ?>"]'
                    class="large-text code">
                <a href="#" class="copyData">Copy to clipboard</a>
            </div>

            <div class="shortcodeWrap">
                <h4>For Custom Template Php Files.</h4>
                <input 
                    type="text" 
                    readonly="readonly" 
                    value='&lt;?php echo do_shortcode("[swiper-js-slider id=<?php echo $post->ID ?>]"); ?&gt;'
                    class="large-text code">
                <a href="#" class="copyData">Copy to clipboard</a>
            </div>
        <?php
    }
 
    /**
     * Handles saving the meta box.
     *
     * @param int     $post_id Post ID.
     * @param WP_Post $post    Post object.
     * @return null
     */
    public function save_metabox( $post_id, $post ) {
        // Add nonce for security and authentication.
        $nonce_name   = isset( $_POST[$this->meta_prefix.'nonce'] ) ? $_POST[$this->meta_prefix.'nonce'] : '';
        $nonce_action = $this->meta_prefix.'nonce_action';
 
        // Check if nonce is valid.
        if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
            return;
        }
 
        // Check if user has permissions to save data.
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
 
        // Check if not an autosave.
        if ( wp_is_post_autosave( $post_id ) ) {
            return;
        }
 
        // Check if not a revision.
        if ( wp_is_post_revision( $post_id ) ) {
            return;
        }

        /* OK, it's safe for us to save the data now. */		

        // Update the meta field.
        $this->s_j_s_updatePostMeta($post_id, $this->meta_prefix, 'setting_general');
        $this->s_j_s_updatePostMeta($post_id, $this->meta_prefix, 'setting_autoplay');
        $this->s_j_s_updatePostMeta($post_id, $this->meta_prefix, 'setting_pagination');
        $this->s_j_s_updatePostMeta($post_id, $this->meta_prefix, 'setting_navigation');
        $this->s_j_s_updatePostMeta($post_id, $this->meta_prefix, 'setting_breakpoints');
        $this->s_j_s_updatePostMeta($post_id, $this->meta_prefix, 'gallery_id');
    }

    function s_j_s_sanitize_text_or_array_field($array_or_string) {
        if( is_string($array_or_string) ){
            $array_or_string = sanitize_text_field($array_or_string);
        }elseif( is_array($array_or_string) ){
            foreach ( $array_or_string as $key => &$value ) {
                if ( is_array( $value ) ) {
                    $value = s_j_s_sanitize_text_or_array_field($value);
                }
                else {
                    $value = sanitize_text_field( $value );
                }
            }
        }

        return $array_or_string;
    }

    function s_j_s_updatePostMeta($id, $prefix, $name){
        if(isset($_POST[$prefix.$name])) {
			$allData = $_POST[$prefix.$name];
            update_post_meta($id, $prefix.$name, $this->s_j_s_sanitize_text_or_array_field($allData));
        } else {
            delete_post_meta($id, $prefix.$name);
        }   
    }
}
 
new Speedy_Custom_Meta_Box();	