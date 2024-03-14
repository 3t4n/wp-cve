<?php
// Exit if accessed directly.
if(!defined('ABSPATH')) exit;

if(!class_exists('RTCORE_Testimonial')):

/**
 * RTCORE Testimonial Class.
 *
 * @class RTCORE_Testimonial
 * @version	1.0.0
 */
class RTCORE_Testimonial extends RTCORE_Base
{
    public function __construct()
    {
        parent::__construct();
    }

    public function init()
    {
        add_action('init', array($this, 'register_post_type'));
        add_action('add_meta_boxes', array($this, 'register_metaboxes'));
        add_action('save_post', array($this, 'save'));

        add_shortcode('testimonials', array($this, 'shortcode'));
        add_filter('widget_text', 'do_shortcode');
    }

    public function register_post_type()
    {
        $args = array(
            'labels' => array
            (
                'name' => __('Testimonials', 'realtyna-core'),
                'singular_name' => __('Testimonial', 'realtyna-core'),
                'add_new' => __('Add New', 'realtyna-core'),
                'add_new_item' => __('Add New Testimonial', 'realtyna-core'),
                'edit_item' => __('Edit Testimonial', 'realtyna-core'),
                'new_item' => __('New Testimonial', 'realtyna-core'),
                'view_item' => __('View Testimonial', 'realtyna-core'),
                'search_items' => __('Search Testimonial', 'realtyna-core'),
                'not_found' =>  __('No testimonials found!', 'realtyna-core'),
                'not_found_in_trash' => __('No testimonials found in Trash!', 'realtyna-core'),
                'parent_item_colon' => ''
            ),
            'public' => true,
            'publicly_queryable' => true,
            'exclude_from_search' => false,
            'show_ui' => true,
            'query_var' => true,
            'rewrite' => array(
                'slug' => 'testimonials'
            ),
            'capability_type' => 'post',
            'hierarchical' => false,
            'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'custom-fields'),
            'menu_icon' => 'dashicons-testimonial',
        );

        register_post_type('rtcore-testimonial', $args);
    }

    public function register_metaboxes()
    {
        add_meta_box('url-metabox', __('Link', 'realtyna-core'), array($this, 'metabox_url'), 'rtcore-testimonial', 'side', 'high');
    }

    public function metabox_url()
    {
        global $post;
        $urllink = get_post_meta($post->ID, 'urllink', true);

        // Validating!
        if(!preg_match("/http(s?):\/\//", $urllink) && $urllink != '')
        {
            $errors = "This URL isn't valid";
            $urllink = "http://";
        }

        // Error
        if(isset($errors)) echo $errors;
        ?>
        <p>
            <label for="siteurl"><?php echo __('URL', 'realtyna-core'); ?>:<br>
                <input id="siteurl" class="widefat" name="siteurl" value="<?php if(isset($urllink)) echo $urllink; ?>" type="url" />
            </label>
        </p>
        <?php
    }

    public function save($post_id)
    {
        if(isset($_POST['siteurl'])) update_post_meta($post_id, 'urllink', esc_url_raw($_POST['siteurl']));
    }

    public function get_url($post)
    {
        return get_post_meta($post->ID, 'urllink', true);
    }

    public function shortcode($att)
    {
        $args = array(
            'post_type' => 'rtcore-testimonial'
        );

        if(isset($att['rand']) && $att['rand'] == true) $args['orderby'] = 'rand';
        if(isset($att['max'])) $args['posts_per_page'] = (int) $att['max'];

        $output = '<div class="owl-testimonials owl-carousel owl-theme">';

        // Getting All Testimonials
        $posts = get_posts($args);
        foreach($posts as $post)
        {
            // Thumbnail
            $url_thumb = wp_get_attachment_thumb_url(get_post_thumbnail_id($post->ID));
            $link = $this->get_url($post);

            $output .= '<div class="item">';
            if(!empty($url_thumb)) $output .= '<img class="post_thumb" src="'.$url_thumb.'" />';

            $output .= '<h2>'.$post->post_title.'</h2>';
            $output .= '<p>';

            if(!empty($post->post_content)) $output .= $post->post_content.'<br>';
            if(!empty($link)) $output .= '<a href="'.$link.'">'.__('Visit Site', 'realtyna-core').'</a>';

            $output .= '</p>';
            $output .= '</div>';
        }

        $output .= '</div>';

        return $output;
    }
}

endif;