<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.smart-variations.com
 * @since      1.0.0
 *
 * @package    Wcsvfs
 * @subpackage Wcsvfs/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wcsvfs
 * @subpackage Wcsvfs/admin
 * @author     David Rosendo <david@rosendo.pt>
 */
class Wcsvfs_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;
    /**
     * The single instance of the class
     *
     * @var Wcsvfs_Admin
     */
    protected static $instance = null;

    /**
     * Main instance
     *
     * @return Wcsvfs_Admin
     */
    public static function instance()
    {
        if (null == self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Wcsvfs_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Wcsvfs_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/wcsvfs-admin' . SMART_SCRIPT_DEBUG . '.css', array('wp-color-picker'), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        $screen = get_current_screen();

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Wcsvfs_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Wcsvfs_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        if (strpos($screen->id, 'edit-pa_') === false && strpos($screen->id, 'product') === false) {
            return;
        }

        wp_enqueue_media();

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/wcsvfs-admin' . SMART_SCRIPT_DEBUG . '.js', array('jquery', 'wp-color-picker', 'wp-util'), $this->version, false);

        wp_localize_script(
            $this->plugin_name,
            'wcsvfs',
            array(
                'i18n' => array(
                    'mediaTitle' => esc_html__('Choose an image', 'wcvs'),
                    'mediaButton' => esc_html__('Use image', 'wcvs'),
                ),
                'placeholder' => WC()->plugin_url() . '/assets/images/placeholder.png',
            )
        );
    }

    /**
     * Display notice in case of WooCommerce plugin is not activated
     */
    public function missing_wc_notice()
    {
        echo '<div class="error">';
        echo '<p>' . esc_html_e(WCVFS_NAME . ' is enabled but not effective. It requires WooCommerce in order to work.', 'wcsvfs') . '</p>';
        echo '</div>';
    }

    /**
     * Add selector for extra attribute types
     *
     * @param $taxonomy
     * @param $index
     */
    public function product_option_terms($taxonomy, $index)
    {
        if (!array_key_exists($taxonomy->attribute_type, WC_SVFS()->types)) {
            return;
        }

        $taxonomy_name = wc_attribute_taxonomy_name($taxonomy->attribute_name);
        global $thepostid;

        $product_id = isset($_POST['post_id']) ? absint($_POST['post_id']) : $thepostid;
?>
        <select multiple="multiple" data-placeholder="<?php esc_attr_e('Select terms', 'wcsvfs'); ?>" class="multiselect attribute_values wc-enhanced-select" name="attribute_values[<?php echo $index; ?>][]">
            <?php

            $all_terms = get_terms($taxonomy_name, apply_filters('woocommerce_product_attribute_terms', array('orderby' => 'name', 'hide_empty' => false)));
            if ($all_terms) {
                foreach ($all_terms as $term) {
                    echo '<option value="' . esc_attr($term->term_id) . '" ' . selected(has_term(absint($term->term_id), $taxonomy_name, $product_id), true, false) . '>' . esc_attr(apply_filters('woocommerce_product_attribute_term_name', $term->name, $term)) . '</option>';
                }
            }
            ?>
        </select>

        <button class="button plus select_all_attributes"><?php esc_html_e('Select all', 'woocommerce'); ?></button>
        <button class="button minus select_no_attributes"><?php esc_html_e('Select none', 'woocommerce'); ?></button>
        <button class="button fr plus wcsvfs_add_new_attribute" data-type="<?php echo $taxonomy->attribute_type ?>"><?php esc_html_e('Add new', 'woocommerce'); ?></button>
    <?php
    }

    /**
     * Ajax function handles adding new attribute term
     */
    public function add_new_attribute_ajax()
    {
        $nonce = isset($_POST['nonce']) ? $_POST['nonce'] : '';
        $tax = isset($_POST['taxonomy']) ? $_POST['taxonomy'] : '';
        $type = isset($_POST['type']) ? $_POST['type'] : '';
        $name = isset($_POST['name']) ? $_POST['name'] : '';
        $slug = isset($_POST['slug']) ? $_POST['slug'] : '';
        $swatch = isset($_POST['swatch']) ? $_POST['swatch'] : '';

        if (!wp_verify_nonce($nonce, '_wcsvfs_create_attribute')) {
            wp_send_json_error(esc_html__('Wrong request', 'wcsvfs'));
        }

        if (empty($name) || empty($swatch) || empty($tax) || empty($type)) {
            wp_send_json_error(esc_html__('Not enough data', 'wcsvfs'));
        }

        if (!taxonomy_exists($tax)) {
            wp_send_json_error(esc_html__('Taxonomy is not exists', 'wcsvfs'));
        }

        if (term_exists($_POST['name'], $_POST['tax'])) {
            wp_send_json_error(esc_html__('This term is exists', 'wcsvfs'));
        }

        $term = wp_insert_term($name, $tax, array('slug' => $slug));

        if (is_wp_error($term)) {
            wp_send_json_error($term->get_error_message());
        } else {
            $term = get_term_by('id', $term['term_id'], $tax);
            update_term_meta($term->term_id, $type, $swatch);
        }

        wp_send_json_success(
            array(
                'msg' => esc_html__('Added successfully', 'wcsvfs'),
                'id' => $term->term_id,
                'slug' => $term->slug,
                'name' => $term->name,
            )
        );
    }

    /**
     * Print HTML of modal at admin footer and add js templates
     */
    public function add_attribute_term_template()
    {
        global $pagenow, $post;

        if ($pagenow != 'post.php' || (isset($post) && get_post_type($post->ID) != 'product')) {
            return;
        }
    ?>

        <div id="wcsvfs-modal-container" class="wcsvfs-modal-container">
            <div class="wcsvfs-modal">
                <button type="button" class="button-link media-modal-close wcsvfs-modal-close">
                    <span class="media-modal-icon"></span></button>
                <div class="wcsvfs-modal-header">
                    <h2><?php esc_html_e('Add new term', 'wcsvfs') ?></h2>
                </div>
                <div class="wcsvfs-modal-content">
                    <p class="wcsvfs-term-name">
                        <label>
                            <?php esc_html_e('Name', 'wcsvfs') ?>
                            <input type="text" class="widefat wcsvfs-input" name="name">
                        </label>
                    </p>
                    <p class="wcsvfs-term-slug">
                        <label>
                            <?php esc_html_e('Slug', 'wcsvfs') ?>
                            <input type="text" class="widefat wcsvfs-input" name="slug">
                        </label>
                    </p>
                    <div class="wcsvfs-term-swatch">

                    </div>
                    <div class="hidden wcsvfs-term-tax"></div>

                    <input type="hidden" class="wcsvfs-input" name="nonce" value="<?php echo wp_create_nonce('_wcsvfs_create_attribute') ?>">
                </div>
                <div class="wcsvfs-modal-footer">
                    <button class="button button-secondary wcsvfs-modal-close"><?php esc_html_e('Cancel', 'wcsvfs') ?></button>
                    <button class="button button-primary wcsvfs-new-attribute-submit"><?php esc_html_e('Add New', 'wcsvfs') ?></button>
                    <span class="message"></span>
                    <span class="spinner"></span>
                </div>
            </div>
            <div class="wcsvfs-modal-backdrop media-modal-backdrop"></div>
        </div>

        <script type="text/template" id="tmpl-wcsvfs-input-color">

            <label><?php esc_html_e('Color', 'wcsvfs') ?></label><br>
			<input type="text" class="wcsvfs-input wcsvfs-input-color" name="swatch">

		</script>

        <script type="text/template" id="tmpl-wcsvfs-input-image">

            <label><?php esc_html_e('Image', 'wcsvfs') ?></label><br>
			<div class="wcsvfs-term-image-thumbnail" style="float:left;margin-right:10px;">
				<img src="<?php echo esc_url(WC()->plugin_url() . '/assets/images/placeholder.png') ?>" width="60px" height="60px" />
			</div>
			<div style="line-height:60px;">
				<input type="hidden" class="wcsvfs-input wcsvfs-input-image wcsvfs-term-image" name="swatch" value="" />
				<button type="button" class="wcsvfs-upload-image-button button"><?php esc_html_e('Upload/Add image', 'wcsvfs'); ?></button>
				<button type="button" class="wcsvfs-remove-image-button button hidden"><?php esc_html_e('Remove image', 'wcsvfs'); ?></button>
			</div>

		</script>

        <script type="text/template" id="tmpl-wcsvfs-input-label">

            <label>
				<?php esc_html_e('Label', 'wcsvfs') ?>
				<input type="text" class="widefat wcsvfs-input wcsvfs-input-label" name="swatch">
			</label>

		</script>

        <script type="text/template" id="tmpl-wcsvfs-input-tax">

            <input type="hidden" class="wcsvfs-input" name="taxonomy" value="{{data.tax}}">
			<input type="hidden" class="wcsvfs-input" name="type" value="{{data.type}}">

		</script>
        <?php
    }

    /**
     * Init hooks for adding fields to attribute screen
     * Save new term meta
     * Add thumbnail column for attribute term
     */
    public function init_attribute_hooks()
    {

        $attribute_taxonomies = wc_get_attribute_taxonomies();

        if (empty($attribute_taxonomies)) {
            return;
        }

        foreach ($attribute_taxonomies as $tax) {
            add_action('pa_' . $tax->attribute_name . '_add_form_fields', array($this, 'add_attribute_fields'));
            add_action('pa_' . $tax->attribute_name . '_edit_form_fields', array($this, 'edit_attribute_fields'), 10, 2);

            add_filter('manage_edit-pa_' . $tax->attribute_name . '_columns', array($this, 'add_attribute_columns'));
            add_filter('manage_pa_' . $tax->attribute_name . '_custom_column', array($this, 'add_attribute_column_content'), 10, 3);
        }

        add_action('created_term', array($this, 'save_term_meta'), 10, 2);
        add_action('edit_term', array($this, 'save_term_meta'), 10, 2);
    }

    /**
     * Save term meta
     *
     * @param int $term_id
     * @param int $tt_id
     */
    public function save_term_meta($term_id, $tt_id)
    {
        foreach (WC_SVFS()->types as $type => $label) {
            if (isset($_POST[$type])) {
                update_term_meta($term_id, $type, $_POST[$type]);
            }
        }
    }

    /**
     * Create hook to add fields to add attribute term screen
     *
     * @param string $taxonomy
     */
    public function add_attribute_fields($taxonomy)
    {
        $attr = WC_SVFS()->get_tax_attribute($taxonomy);

        do_action('wcsvfs_product_attribute_field', $attr->attribute_type, '', 'add');
    }

    /**
     * Create hook to fields to edit attribute term screen
     *
     * @param object $term
     * @param string $taxonomy
     */
    public function edit_attribute_fields($term, $taxonomy)
    {
        $attr = WC_SVFS()->get_tax_attribute($taxonomy);
        $value = get_term_meta($term->term_id, $attr->attribute_type, true);
        do_action('wcsvfs_product_attribute_field', $attr->attribute_type, $value, 'edit');
    }

    /**
     * Add thumbnail column to column list
     *
     * @param array $columns
     *
     * @return array
     */
    public function add_attribute_columns($columns)
    {
        if ($columns) {
            $new_columns = array();
            $new_columns['cb'] = $columns['cb'];
            $new_columns['thumb'] = '';
            unset($columns['cb']);

            return array_merge($new_columns, $columns);
        }
    }

    /**
     * Render thumbnail HTML depend on attribute type
     *
     * @param $columns
     * @param $column
     * @param $term_id
     */
    public function add_attribute_column_content($columns, $column, $term_id)
    {
        $attr = WC_SVFS()->get_tax_attribute($_REQUEST['taxonomy']);
        $value = get_term_meta($term_id, $attr->attribute_type, true);

        switch ($attr->attribute_type) {
            case 'color':
                printf('<div class="swatch-preview swatch-color" style="background-color:%s;"></div>', esc_attr($value));
                break;

            case 'image':
                $image = $value ? wp_get_attachment_image_src($value) : '';
                $image = $image ? $image[0] : WC()->plugin_url() . '/assets/images/placeholder.png';
                printf('<img class="swatch-preview swatch-image" src="%s" width="44px" height="44px">', esc_url($image));
                break;

            case 'label':
                printf('<div class="swatch-preview swatch-label">%s</div>', esc_html($value));
                break;
        }
    }

    /**
     * Print HTML of custom fields on attribute term screens
     *
     * @param $type
     * @param $value
     * @param $form
     */
    public function attribute_fields($type = null, $value = null, $form = null)
    {

        // Return if this is a default attribute type
        if (in_array($type, array('select', 'text'))) {
            return;
        }

        // Print the open tag of field container
        printf(
            '<%s class="form-field">%s<label for="term-%s">%s</label>%s',
            'edit' == $form ? 'tr' : 'div',
            'edit' == $form ? '<th>' : '',
            esc_attr($type),
            WC_SVFS()->types[$type],
            'edit' == $form ? '</th><td>' : ''
        );

        switch ($type) {
            case 'image':
                $image = $value ? wp_get_attachment_image_src($value) : '';
                $image = $image ? $image[0] : WC()->plugin_url() . '/assets/images/placeholder.png';
        ?>
                <div class="wcsvfs-term-image-thumbnail" style="float:left;margin-right:10px;">
                    <img src="<?php echo esc_url($image) ?>" width="60px" height="60px" />
                </div>
                <div style="line-height:60px;">
                    <input type="hidden" class="wcsvfs-term-image" name="image" value="<?php echo esc_attr($value) ?>" />
                    <button type="button" class="wcsvfs-upload-image-button button"><?php esc_html_e('Upload/Add image', 'wcsvfs'); ?></button>
                    <button type="button" class="wcsvfs-remove-image-button button <?php echo $value ? '' : 'hidden' ?>"><?php esc_html_e('Remove image', 'wcsvfs'); ?></button>
                </div>
            <?php
                break;

            default:
            ?>
                <input type="text" id="term-<?php echo esc_attr($type) ?>" name="<?php echo esc_attr($type) ?>" value="<?php echo esc_attr($value) ?>" />
<?php
                break;
        }

        // Print the close tag of field container
        echo 'edit' == $form ? '</td></tr>' : '</div>';
    }
}
