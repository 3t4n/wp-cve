<?php if (!defined('ABSPATH')) { exit; } // Exit if accessed directly
/**
 * Simple_Owl_Carousel_Meta_Box_Slider Class
 *
 * This file is used to define add or save meta box of soc_slider. 
 *  
 * @link       http://presstigers.com
 * @since      1.0.0
 * 
 * @package    Simple_Owl_Carousel
 * @subpackage Simple_Owl_Carousel/admin/partials/meta-boxes
 * @author     PressTigers <support@presstigers.com>
 */

class Simple_Owl_Carousel_Meta_Box_Slider {

    /**
     * The ID of this plugin.
     *
     * @since   1.0.0
     * @access  protected
     * @var     array   $soc_slider_postmeta
     */
    protected $soc_slider_postmeta;

    /**
     * Initialize the class and set its properties.
     *         
     * @since   1.0.0
     */
    public function __construct() {
        global $post;

        // Creating Meta Box on Add New SOC Slider Page
        $this->soc_slider_postmeta = array(
            'id' => 'simple_owl_metabox',
            'title' => __('Slides', 'simple-owl-carousel'),
            'context' => 'normal',
            'screen' => 'soc_slider',
            'priority' => 'high',
            'context' => 'normal',
            'callback' => 'soc_slider_output',
            'show_names' => TRUE,
            'closed' => FALSE,
        );

        // Add Hook into the 'admin_menu' Action
        add_action('add_meta_boxes', array($this, 'soc_create_meta_box'));

        // Add Hook into the 'save_post()' Action
        add_action('save_post_soc_slider', array($this, 'save_soc_slider'));
    }

    /**
     * Getter of soc_slider meta box.
     *
     * @since   1.0.0
     */
    public function get_soc_slider_postmeta() {
        return $this->soc_slider_postmeta;
    }

    /**
     * Create Meta Box
     *
     * @since   1.0.0 
     */
    public function soc_create_meta_box() {
        $soc_post_meta = self::get_soc_slider_postmeta();
        add_meta_box($soc_post_meta['id'], $soc_post_meta['title'], array($this, $soc_post_meta['callback']), $soc_post_meta['screen'], $soc_post_meta['context'], $soc_post_meta['priority']);
    }

    /**
     * Meta Box Output
     *
     * @since   1.0.0 
     * 
     * @param   object  $post   Post Object
     */
    public static function soc_slider_output($post) {

        // Add a nonce field so we can check it for later.
        wp_nonce_field('soc_meta_box', 'soc_slider_meta_box_nonce');
        ?>

        <!-- Slider's slides -->
        <div id="soc-slider-slide-container">
            <ul class="soc-slides">
                <?php
                if (metadata_exists('post', $post->ID, '_soc_slider')) {
                    $soc_slider_slides = get_post_meta($post->ID, '_soc_slider', TRUE);
                } else {
                    $attachment_ids = get_posts(
                            'post_parent=' . $post->ID . '&'
                            . 'numberposts=-1&'
                            . 'post_type=attachment&'
                            . 'orderby=menu_order&'
                            . 'order=ASC&'
                            . 'post_mime_type=image&'
                            . 'fields=ids&'
                    );
                    $attachment_ids = array_diff($attachment_ids, array(get_post_thumbnail_id()));
                    $soc_slider_slides = implode(',', $attachment_ids);
                }

                $attachments = array_filter(explode(',', $soc_slider_slides));
                $update_meta = FALSE;
                $updated_gallery_ids = array();

                if (!empty($attachments)) {
                    foreach ($attachments as $attachment_id) {
                        $attachment = wp_get_attachment_image($attachment_id, 'thumbnail');
                        $alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
                        $attachment_meta = get_post($attachment_id);
                        
                       // Skip Empty Attachment
                        if (empty($attachment)) {
                            $update_meta = TRUE;
                            continue;
                        }

                        echo '<li class="slide" data-attachment_id="' . esc_attr($attachment_id) . '">
                                ' . $attachment . '<br>
                                <input type="text" name="caption_' . $attachment_id . '"  value="' . $attachment_meta->post_excerpt . '"  placeholder="Caption"> 
                                <a href="#" class="delete tips" data-tip="' . esc_attr__('Delete Slide', 'simple-owl-carousel') . '"><i class="fa fa-times" aria-hidden="true"></i>
                              </a>
                        </li>';
                        

                        // Rebuild IDs to be Saved
                        $updated_gallery_ids[] = $attachment_id;
                    }

                    // Update Soc Slider Meta to Set New Slide's IDs
                    if ($update_meta) {
                        update_post_meta($post->ID, '_soc_slider', implode(',', $updated_gallery_ids));
                    }
                }
                ?>
            </ul>
            <input type="hidden" id="soc_slider_slides" name="soc_slider_slides" value="<?php echo esc_attr($soc_slider_slides); ?>" />
        </div>
        <p class="add_slide hide-if-no-js">
            <a href="#" data-choose="<?php esc_attr_e('Add Slide to Slider', 'simple-owl-carousel'); ?>" data-update="<?php esc_attr_e('Add to Slider', 'simple-owl-carousel'); ?>" data-delete="<?php esc_attr_e('Delete Slide', 'simple-owl-carousel'); ?>" data-text="<?php esc_attr_e('Delete', 'simple-owl-carousel'); ?>"><?php _e('Add Slide to Slider', 'simple-owl-carousel'); ?></a>
        </p>
        <?php
    }

    /**
     * Save Meta Box.
     *
     * @since   1.0.0
     */
    public static function save_soc_slider() {
        global $post;

        // Check Nonce Field
        if (!isset($_POST['soc_slider_meta_box_nonce'])) {
            return;
        }

        // Verify that the nonce is valid.
        if (!wp_verify_nonce($_POST['soc_slider_meta_box_nonce'], 'soc_meta_box')) {
            return;
        }

        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check the user's permissions.
        if (isset($_POST['post_type']) && 'page' == $_POST['post_type']) {
            if (!current_user_can('edit_page', $post->ID)) {
                return;
            }
        } else {
            if (!current_user_can('edit_post', $post->ID)) {
                return;
            }
        }

        // Get Attachment's/Slide's IDs
        $attachment_ids = isset($_POST['soc_slider_slides']) ? array_filter(explode(',', $_POST['soc_slider_slides'])) : array();
        update_post_meta($post->ID, '_soc_slider', implode(',', $attachment_ids));
    
        foreach ($attachment_ids as $attachment_id) {
          
            $post_type_attachment_ = array(
                'ID' => $attachment_id,
                'post_excerpt' => $_POST['caption_'. $attachment_id],
            );

            // Update Excerpt of Post Type Attachment
            wp_update_post($post_type_attachment_);
        }
          
    }
}