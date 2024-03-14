<?php namespace Premmerce\Brands\Admin;

use Premmerce\SDK\V2\FileManager\FileManager;

/**
 * Class Admin
 * @package Premmerce\Brands\Admin
 */
class Admin
{
    /**
     * @var FileManager
     */
    private $fileManager;

    /**
     * Admin constructor.
     *
     * @param FileManager $fileManager
     */
    public function __construct(FileManager $fileManager)
    {
        $this->registerHooks();

        $this->fileManager = $fileManager;
    }

    /**
     * Register backend hooks
     */
    private function registerHooks()
    {
        add_action('product_brand_add_form_fields', array($this, 'addBrandsFields'));
        add_action('product_brand_edit_form_fields', array($this, 'editBrandsFields'), 10);
        add_action('created_term', array($this, 'saveBrandsFields'), 10, 3);
        add_action('edit_term', array($this, 'saveBrandsFields'), 10, 3);
        add_filter('manage_edit-product_brand_columns', array($this, 'productBrandColumns'));
        add_filter('manage_product_brand_custom_column', array($this, 'productCatColumn'), 10, 3);
        add_action('quick_edit_custom_box', array($this, 'quickEdit'), 10, 2);
        add_action('manage_product_posts_custom_column', array($this, 'renderProductColumns'));
        add_action('woocommerce_product_bulk_and_quick_edit', array($this, 'saveEditPost'));
        add_action('restrict_manage_posts', array($this, 'productFilterDropdown'), 999);

        // Bulk edit
        add_action('woocommerce_product_bulk_edit_end', array($this, 'addBrandsToBulkEdit'));
        add_action('woocommerce_product_bulk_edit_save', array($this, 'bulkEditBrandsHandler'));

        //Settings on permalink page
        add_action('admin_init', array($this, 'addBrandsSettings'));
        add_action('admin_init', array($this, 'saveBrandsSettings'));

        //duplicate product
        add_action('woocommerce_product_duplicate_before_save', array($this, 'addBrandToDuplicatedProduct'), 1, 2);
    }

    /**
     * Add brand to when product has been duplicated
     */
    public function addBrandToDuplicatedProduct($duplicate, $product)
    {
        $duplicated = $duplicate->save();

        $terms = wp_get_object_terms($product->id, 'product_brand');

        $terms = array_map(function ($item) {
            return $item->term_id;
        }, $terms);

        wp_set_object_terms($duplicated, $terms, 'product_brand');
    }

    /**
     * Add settings section in permalink page
     */
    public function addBrandsSettings()
    {
        add_settings_section(
            'brands_settings_section',
            __('Brands settings', 'premmerce-brands'),
            array($this, 'renderBrandsSection'),
            'permalink'
        );

        register_setting(
            'permalink_brands_group',
            'premmerce_brands_base'
        );

        add_settings_field(
            'premmerce_brands_base',
            __('Brands base', 'premmerce-brands'),
            array($this, 'renderBaseField'),
            'permalink',
            'brands_settings_section'
        );
    }

    /**
     * Update brands base option when trigger save button on permalink page
     */
    public function saveBrandsSettings()
    {
        global $pagenow;

        if (isset($_POST['permalink_structure']) && isset($_POST['premmerce_brands_base']) && $pagenow = 'options-permalink.php') {
            update_option('premmerce_brands_base', wc_sanitize_permalink(wp_unslash($_POST['premmerce_brands_base'])));
        }
    }

    /**
     * render brand_base field in permalink page
     */
    public function renderBaseField()
    {
        ?>
        <input type="text" name="premmerce_brands_base" class="regular-text code"
               value="<?php echo get_option('premmerce_brands_base') ?>">
        <?php
    }

    /**
     * render brand_base section description in permalink page
     */
    public function renderBrandsSection()
    {
        _e('You can customize your brand url segment', 'premmerce-brands');
    }

    /**
     * Include add_brands_fields template
     */
    public function addBrandsFields()
    {
        wp_enqueue_media();
        wp_enqueue_style(
            'premmerce-brands',
            $this->fileManager->locateAsset('admin/css/premmerce-brands.css'),
            array('woocommerce_admin_styles')
        );
        wp_enqueue_script('premmerce-brands', $this->fileManager->locateAsset('admin/js/premmerce-brands.js'));

        $this->fileManager->includeTemplate('admin/create-brands-fields.php');
    }

    /**
     * Include edit_brands_fields template
     *
     * @param \WP_Term $term
     */
    public function editBrandsFields(\WP_Term $term)
    {
        wp_enqueue_media();
        wp_enqueue_style('premmerce-brands', $this->fileManager->locateAsset('admin/css/premmerce-brands.css'));
        wp_enqueue_script('premmerce-brands', $this->fileManager->locateAsset('admin/js/premmerce-brands.js'));

        $thumbnailId = absint(get_term_meta($term->term_id, 'thumbnail_id', true));

        if ($thumbnailId) {
            $image = wp_get_attachment_thumb_url($thumbnailId);
        } else {
            $image = wc_placeholder_img_src();
        }

        $this->fileManager->includeTemplate('admin/edit-brands-fields.php', array(
            'thumbnailId' => $thumbnailId,
            'image'       => $image,
        ));
    }

    /**
     * Update custom brands fields
     *
     * @param int $termId
     * @param string $ttId
     * @param string $taxonomy
     */
    public function saveBrandsFields($termId, $ttId = '', $taxonomy = '')
    {
        if ($taxonomy === 'product_brand') {
            if (isset($_POST['brands_thumbnail_id']) && 'product_brand' === $taxonomy) {
                update_term_meta($termId, 'thumbnail_id', absint($_POST['brands_thumbnail_id']));
            }

            flush_rewrite_rules();
        }
    }

    /**
     * Add image column to brands page
     *
     * @param array $columns
     *
     * @return array
     */
    public function productBrandColumns($columns)
    {
        $newColumns = array();

        if (isset($columns['cb'])) {
            $newColumns['cb'] = $columns['cb'];
            unset($columns['cb']);
        }

        $newColumns['thumb'] = __('Image', 'premmerce-brands');

        return array_merge($newColumns, $columns);
    }

    /**
     * Display brand image
     *
     * @param array $columns
     * @param string $column
     * @param int $id
     *
     * @return string
     */
    public function productCatColumn($columns, $column, $id)
    {
        if ($column == 'thumb') {
            $thumbnailId = get_term_meta($id, 'thumbnail_id', true);

            if ($thumbnailId) {
                $image = wp_get_attachment_thumb_url($thumbnailId);
            } else {
                $image = wc_placeholder_img_src();
            }

            $columns .= '<img src="' . esc_url($image) . '" alt="' . esc_attr__(
                'Image',
                'premmerce-brands'
            ) . '" class="wp-post-image" height="48" width="48" />';
        }

        return $columns;
    }

    /**
     * Add brands radio to quick edit
     *
     * @param string $columnName
     * @param string $postType
     */
    public function quickEdit($columnName, $postType)
    {
        if ($postType == 'product' && $columnName == 'product_cat') {
            $args = array(
                'taxonomy'   => 'product_brand',
                'hide_empty' => false,
                'order'      => 'ASC',
                'order_by'   => 'name',
            );

            $brands = get_terms($args);

            $this->fileManager->includeTemplate('admin/brands-quick-edit.php', array(
                'brands' => $brands,
            ));
        }
    }

    /**
     * View hidden input for js (because no flexible WP)
     *
     * @param string $column
     */
    public function renderProductColumns($column)
    {
        wp_enqueue_script('premmerce-brands', $this->fileManager->locateAsset('admin/js/premmerce-brands.js'));
        wp_enqueue_style('premmerce-brands', $this->fileManager->locateAsset('admin/css/premmerce-brands.css'));

        if ($column == 'name') {
            global $post;

            $brands = get_the_terms($post->ID, 'product_brand');

            if (isset($brands[0])) {
                echo '<input type="hidden" data-input="product_brand" value="' . $brands[0]->slug . '">';
            }
        }
    }

    /**
     * Save brand from quick edit form
     *
     * @param int $postId
     */
    public function saveEditPost($postId)
    {
        if (isset($_POST['product_brand'])) {
            wp_set_post_terms($postId, $_POST['product_brand'], 'product_brand');
        }
    }

    /**
     * Adds a dropdown that allows filtering by brand
     */
    public function productFilterDropdown()
    {
        global $pagenow;

        $args = array(
            'taxonomy'   => 'product_brand',
            'hide_empty' => false,
            'order'      => 'ASC',
            'order_by'   => 'name',
        );

        $brands = get_terms($args);

        // If current page is edit.php and post_type param = product
        if ('edit.php' === $pagenow && isset($_GET['post_type']) && $_GET['post_type'] == 'product') {
            $this->fileManager->includeTemplate('admin/brands-filter-select.php', array('brands' => $brands));
        }
    }

    /**
     * Adds brand column to bulk edit
     */
    public function addBrandsToBulkEdit()
    {
        $args = array(
            'taxonomy'   => 'product_brand',
            'hide_empty' => false,
            'order'      => 'ASC',
            'order_by'   => 'name',
        );

        $brands = get_terms($args);

        $this->fileManager->includeTemplate('admin/brands-bulk-edit.php', array('brands' => $brands));
    }

    /**
     * Save brand when submitted for product bulk edit
     *
     * @param product $product
     */
    public function bulkEditBrandsHandler($product)
    {
        $product_id = method_exists($product, 'get_id') ? $product->get_id() : $product->id;

        if (isset($_REQUEST['product_brand']) && $_REQUEST['product_brand'] != '') {
            // Delete term if brand is < Not specified > and set term or not
            if ($_REQUEST['product_brand'] == 'not_specified') {
                wp_set_post_terms($product_id, '', 'product_brand');
            } else {
                wp_set_post_terms($product_id, $_REQUEST['product_brand'], 'product_brand');
            }
        }
    }
}
