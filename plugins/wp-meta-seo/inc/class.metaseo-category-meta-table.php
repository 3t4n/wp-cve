<?php
/* Prohibit direct script loading */
defined('ABSPATH') || die('No direct script access allowed!');
if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

/**
 * Class Meta seo category meta table
 *
 * Base class for displaying a list of category/product_cat in an ajaxified HTML table.
 */
class WPMSCategoryMetaTable extends WP_List_Table
{
    /**
     * Post/product taxonomy
     *
     * @var array
     */
    public $taxonomy_cat;

    /**
     * MetaSeoContentListTable constructor.
     */
    public function __construct()
    {
        parent::__construct(array(
            'singular' => 'metaseo_category',
            'plural'   => 'metaseo_categories',
            'ajax'     => true
        ));
    }

    /**
     * Custom style for meta content table
     *
     * @return void
     */
    public static function customStyles()
    {
        echo '<style type="text/css">';
        echo '.metaseo_categories .column-col_cat_title {width:30% !important;}';
        echo '</style>';
    }

    /**
     * Generate the table navigation above or below the table
     *
     * @param string $which Position of table nav
     *
     * @return void
     */
    protected function display_tablenav($which) // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- extends from WP_List_Table class
    {
        ?>
        <div class="<?php echo esc_attr('tablenav ' . $which); ?>">

            <input type="hidden" name="page" value="metaseo_content_meta"/>
            <?php // phpcs:disable WordPress.Security.NonceVerification.Recommended -- No action, nonce is not required
            ?>
            <?php if (!empty($_REQUEST['cat_status'])) : ?>
                <input type="hidden" name="post_status" value="<?php echo esc_attr($_REQUEST['cat_status']); ?>"/>
            <?php endif ?>
            <?php // phpcs:enable
            if ($which === 'top') {
                $this->extra_tablenav($which); ?>
                <div style="float:right;margin-left:8px;">
                    <label>
                        <input type="number" required
                               value="<?php echo esc_attr($this->_pagination_args['per_page']) ?>"
                               maxlength="3" name="wpms_cat_per_page" class="metaseo_imgs_per_page screen-per-page"
                               max="999" min="1" step="1">
                        <button type="submit" name="wpms_btn_perpage"
                                class="button_perpage ju-button orange-button waves-effect waves-light"
                                id="button_perpage"><?php esc_html_e('Apply', 'wp-meta-seo') ?></button>
                    </label>
                </div>
            <?php } else { ?>
                <?php $this->pagination('top'); ?>
            <?php } ?>
            <br class="clear"/>
        </div>

        <?php
    }

    /**
     * Display the pagination.
     *
     * @param string $which Possition
     *
     * @return void
     */
    protected function pagination($which)
    {
        if (empty($this->_pagination_args)) {
            return;
        }

        $total_items     = (int) $this->_pagination_args['total_items'];
        $total_pages     = (int) $this->_pagination_args['total_pages'];
        $infinite_scroll = false;
        if (isset($this->_pagination_args['infinite_scroll'])) {
            $infinite_scroll = $this->_pagination_args['infinite_scroll'];
        }

        if ('top' === $which && $total_pages > 1) {
            $this->screen->render_screen_reader_content('heading_pagination');
        }

        $output = '<span class="displaying-num">' . sprintf(_n('%s item', '%s items', $total_items, 'wp-meta-seo'), number_format_i18n($total_items)) . '</span>';

        $current              = (int) $this->get_pagenum();
        $removable_query_args = wp_removable_query_args();

        $current_url = set_url_scheme('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);

        $current_url = remove_query_arg($removable_query_args, $current_url);

        $page_links = array();

        $total_pages_before = '<span class="paging-input">';
        $total_pages_after  = '</span></span>';

        $disable_first = false;
        $disable_last = false;
        $disable_prev = false;
        $disable_next = false;

        if ($current === 1) {
            $disable_first = true;
            $disable_prev  = true;
        }
        if ($current === 2) {
            $disable_first = true;
        }

        if ($current === $total_pages) {
            $disable_last = true;
            $disable_next = true;
        }
        if ($current === $total_pages - 1) {
            $disable_last = true;
        }

        if ($disable_first) {
            $page_links[] = '<a class="wpms-number-page first-page disable"><i class="material-icons">first_page</i></a>';
        } else {
            $page_links[] = sprintf(
                "<a class='first-page' href='%s'><span class='screen-reader-text'>%s</span><i class='material-icons'>%s</i></a>",
                esc_url(remove_query_arg('paged', $current_url)),
                __('First page', 'wp-meta-seo'),
                'first_page'
            );
        }

        if ($disable_prev) {
            $page_links[] = '<a class="wpms-number-page prev-page disable"><i class="material-icons">keyboard_backspace</i></a>';
        } else {
            $page_links[] = sprintf(
                "<a class='prev-page' href='%s'><span class='screen-reader-text'>%s</span><i class='material-icons'>%s</i></a>",
                esc_url(add_query_arg('paged', max(1, $current - 1), $current_url)),
                __('Previous page', 'wp-meta-seo'),
                'keyboard_backspace'
            );
        }

        $begin = $current - 2;
        $end   = $current + 2;
        if ($begin < 1) {
            $begin = 1;
            $end   = $begin + 4;
        }
        if ($end > $total_pages) {
            $end   = $total_pages;
            $begin = $end - 4;
        }
        if ($begin < 1) {
            $begin = 1;
        }

        $custom_html = '';
        for ($i = $begin; $i <= $end; $i ++) {
            if ($i === $current) {
                $custom_html .= '<a class="wpms-number-page active" href="' . esc_url(add_query_arg('paged', $i, $current_url)) . '"><span class="screen-reader-text">' . esc_html($i) . '</span><span aria-hidden="true">' . esc_html($i) . '</span></a>';
            } else {
                $custom_html .= '<a class="wpms-number-page" href="' . esc_url(add_query_arg('paged', $i, $current_url)) . '"><span class="screen-reader-text">' . esc_html($i) . '</span><span aria-hidden="true">' . esc_html($i) . '</span></a>';
            }
        }
        $page_links[] = $total_pages_before . $custom_html . $total_pages_after;

        if ($disable_next) {
            $page_links[] = '<a class="wpms-number-page disable next-page"><i class="material-icons">trending_flat</i></a>';
        } else {
            $page_links[] = sprintf(
                "<a class='next-page' href='%s'><span class='screen-reader-text'>%s</span><i class='material-icons'>%s</i></a>",
                esc_url(add_query_arg('paged', min($total_pages, $current + 1), $current_url)),
                __('Next page', 'wp-meta-seo'),
                'trending_flat'
            );
        }

        if ($disable_last) {
            $page_links[] = '<a class="wpms-number-page last-page disable"><i class="material-icons">last_page</i></a>';
        } else {
            $page_links[] = sprintf(
                "<a class='last-page' href='%s'><span class='screen-reader-text'>%s</span><i class='material-icons'>%s</i></a>",
                esc_url(add_query_arg('paged', $total_pages, $current_url)),
                __('Last page', 'wp-meta-seo'),
                'last_page'
            );
        }

        $pagination_links_class = 'pagination-links';
        if (!empty($infinite_scroll)) {
            $pagination_links_class .= ' hide-if-js';
        }
        $output .= '<span class="' . esc_html($pagination_links_class) . '">' . join('', $page_links) . '</span>';

        if ($total_pages) {
            $page_class = $total_pages < 2 ? ' one-page' : '';
        } else {
            $page_class = ' no-pages';
        }
        $this->_pagination = '<div class="tablenav-pages' . esc_html($page_class) . '">' . $output . '</div>';

        // phpcs:ignore WordPress.Security.EscapeOutput -- Content already escaped
        echo $this->_pagination;
    }

    /**
     * Displays the search box.
     *
     * @param string $text     The 'submit' button label.
     * @param string $input_id ID attribute value for the search input field.
     *
     * @return void
     */
    public function searchBox($text, $input_id)
    {
        // phpcs:disable WordPress.Security.NonceVerification.Recommended -- No action, nonce is not required
        if (empty($_REQUEST['s']) && !$this->has_items()) {
            return;
        }
        $input_id = $input_id . '-search-input';

        if (!empty($_REQUEST['orderby'])) {
            echo '<input type="hidden" name="orderby" value="' . esc_attr($_REQUEST['orderby']) . '" />';
        }
        if (!empty($_REQUEST['order'])) {
            echo '<input type="hidden" name="order" value="' . esc_attr($_REQUEST['order']) . '" />';
        }
        // phpcs:enable
        ?>
        <p class="search-box">
            <label class="screen-reader-text" for="<?php echo esc_attr($input_id); ?>"><?php echo esc_html($text); ?>
                :</label>
            <input type="text" id="<?php echo esc_attr($input_id); ?>" class="wpms-search-input" name="s"
                   value="<?php _admin_search_query(); ?>"
                   placeholder="<?php esc_html_e('Search content', 'wp-meta-seo') ?>"/>
            <button type="submit" id="search-submit"><span class="dashicons dashicons-search"></span></button>
        </p>
        <?php
    }

    /**
     * Extra controls to be displayed between bulk actions and pagination
     *
     * @param string $which Possition of table nav
     *
     * @return void
     */
    protected function extra_tablenav($which) // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- extends from WP_List_Table class
    {
        echo '<div class="alignleft actions">';
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- No action, nonce is not required
        $selected = !empty($_REQUEST['taxonomy_filter']) ? $_REQUEST['taxonomy_filter'] : - 1;

        $options = '<option value="-1">Show All Categories</option>';

        foreach ($this->taxonomy_cat as $taxonomy) {
            $taxonomy_object = get_taxonomy($taxonomy);
            $options .= sprintf(
                '<option value="%2$s" %3$s>%1$s</option>',
                esc_html($taxonomy_object->labels->name),
                esc_attr($taxonomy),
                selected($selected, $taxonomy, false)
            );
        }

        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- No action, nonce is not required
        $selected_duplicate = !empty($_REQUEST['wpms_duplicate_cat_meta']) ? $_REQUEST['wpms_duplicate_cat_meta'] : 'none';
        $options_dups       = array(
            'none'            => esc_html__('All category meta information', 'wp-meta-seo'),
            'duplicate_cat_title' => esc_html__('Duplicated category meta titles', 'wp-meta-seo'),
            'duplicate_cat_desc'  => esc_html__('Duplicated category meta descriptions', 'wp-meta-seo')
        );
        $sl_duplicate       = '<select name="wpms_duplicate_cat_meta" class="wpms_duplicate_meta">';
        foreach ($options_dups as $key => $label) {
            if ($selected_duplicate === $key) {
                $sl_duplicate .= '<option selected value="' . esc_attr($key) . '">' . esc_html($label) . '</option>';
            } else {
                $sl_duplicate .= '<option value="' . esc_attr($key) . '">' . esc_html($label) . '</option>';
            }
        }
        $sl_duplicate .= '</select>';
        // phpcs:ignore WordPress.Security.EscapeOutput -- Content escaped in previous line (same function)
        echo sprintf('<select name="taxonomy_filter" class="metaseo-filter">%1$s</select>', $options);
        // phpcs:disable WordPress.Security.EscapeOutput -- Content escaped in previous line (same function)
        echo $sl_duplicate;
        // phpcs:enable
        if (is_plugin_active(WPMSEO_ADDON_FILENAME)
            && (is_plugin_active('sitepress-multilingual-cms/sitepress.php')
                || is_plugin_active('polylang/polylang.php'))) {
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- No action, nonce is not required
            $lang    = !empty($_REQUEST['wpms_lang_list']) ? $_REQUEST['wpms_lang_list'] : '0';
            $sl_lang = apply_filters('wpms_get_languagesList', '', $lang);
            // phpcs:ignore WordPress.Security.EscapeOutput -- Content escaped in the method MetaSeoAddonAdmin::listLanguageSelect
            echo $sl_lang;
        }

        echo '<a href="#TB_inline?width=600&height=550&inlineId=cat-meta-bulk-actions" title="' . esc_html__('Bulk Actions', 'wp-meta-seo') . '" 
         class="ju-button orange-button wpms-middle thickbox">' . esc_html__('Meta Bulk Actions', 'wp-meta-seo') . '</a>';
        echo '</div>';
    }

    /**
     * Get a list of columns. The format is:
     * 'internal-name' => 'Title'
     *
     * @return array
     */
    public function get_columns() // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- extends from WP_List_Table class
    {
        $preview = esc_html__(" This is a rendering of what this post might look
         like in Google's search results.", 'wp-meta-seo');
        $info    = sprintf('<a class="info-content"><img src=' . WPMETASEO_PLUGIN_URL . 'assets/images/info.png>'
            . '<p class="tooltip-metacontent">'
            . $preview
            . '</p></a>');

        $columns = array(
            'cb'             => '<input id="cb-select-all-1" type="checkbox" style="margin:0">',
            'col_cat_title'      => esc_html__('Title', 'wp-meta-seo'),
            'col_cat_snippet' => sprintf(esc_html__('Snippet Preview %s', 'wp-meta-seo'), $info)
        );

        return $columns;
    }

    /**
     * Get a list of sortable columns. The format is:
     * 'internal-name' => 'orderby'
     * or
     * 'internal-name' => array( 'orderby', true )
     *
     * The second format will make the initial sorting order be descending
     *
     * @return array
     */
    protected function get_sortable_columns() // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- extends from WP_List_Table class
    {
        $sortable = array(
            'col_cat_title'      => array('term_name', true)
        );

        return $sortable;
    }

    /**
     * Get taxonomy for category (category/product_cat)
     *
     * @return array
     */
    public static function getTaxonomyCat()
    {
        $taxonomy = array();
        if (taxonomy_exists('category')) {
            $taxonomy[] = 'category';
        }
        // Check if Woocommerce product categories exits
        if (taxonomy_exists('product_cat')) {
            $taxonomy[] = 'product_cat';
        }
        return $taxonomy;
    }

    /**
     * Prepares the list of items for displaying.
     *
     * @return void
     */
    public function prepare_items() // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- extends from WP_List_Table class
    {
        global $wpdb;
        $this->taxonomy_cat = $this->getTaxonomyCat();
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- No action, nonce is not required
        $taxonomy_cat = isset($_REQUEST['taxonomy_filter']) ? $_REQUEST['taxonomy_filter'] : '';
        if ($taxonomy_cat === '-1') {
            $taxonomy_cat = ''; // show all categories (category and product_cat)
        }

        if (!empty($taxonomy_cat) && !in_array($taxonomy_cat, $this->taxonomy_cat)) {
            $taxonomy_cat = 'category'; // default
        } elseif (empty($taxonomy_cat)) { // all category will filter
            $taxonomy_cat = implode("', '", $this->taxonomy_cat);
        }

        $where      = array();
        $where[]    = 'taxonomy IN (\'' . $taxonomy_cat . '\')';

        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- No action, nonce is not required
        $keyword = !empty($_GET['s']) ? $_GET['s'] : '';
        $keyword = filter_var($keyword, FILTER_UNSAFE_RAW);

        if (isset($keyword) && $keyword !== '') {
            $where[] = $wpdb->prepare('(term_name LIKE %s OR meta_title LIKE %s OR meta_desc LIKE %s)', array(
                '%' . $keyword . '%',
                '%' . $keyword . '%',
                '%' . $keyword . '%'
            ));
        }

        //Order By block
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- No action, nonce is not required
        $orderby = !empty($_GET['orderby']) ? ($_GET['orderby']) : 'term_name';
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- No action, nonce is not required
        $order = !empty($_GET['order']) ? ($_GET['order']) : 'asc';

        $sortable      = $this->get_sortable_columns();
        $orderby_array = array($orderby, true);
        if (in_array($orderby_array, $sortable)) {
            $orderStr = $orderby;
        } else {
            $orderStr = 'term_name';
        }

        if ($order === 'asc') {
            $orderStr .= ' ASC';
        } else {
            $orderStr .= ' DESC';
        }

        if (!empty($orderby) & !empty($order)) {
            $orderStr = ' ORDER BY ' . $orderStr;
        }

        // phpcs:disable WordPress.Security.NonceVerification.Recommended -- No action, nonce is not required
        if (isset($_GET['wpms_duplicate_cat_meta']) && $_GET['wpms_duplicate_cat_meta'] !== 'none') {
            if ($_GET['wpms_duplicate_cat_meta'] === 'duplicate_cat_title') {
                $where[] = 'meta_title IN (SELECT DISTINCT meta_value FROM ' . $wpdb->termmeta . ' WHERE meta_key="wpms_category_metatitle" AND meta_value != "" GROUP BY meta_value HAVING COUNT(*) >= 2)';
            } elseif ($_GET['wpms_duplicate_cat_meta'] === 'duplicate_cat_desc') {
                $where[] = 'meta_desc IN (SELECT DISTINCT meta_value FROM ' . $wpdb->termmeta . ' WHERE meta_key="wpms_category_metadesc" AND meta_value != "" GROUP BY meta_value HAVING COUNT(*) >= 2)';
            }
        }

        $metaQuery = 'SELECT t.term_id, t.name term_name, t.slug term_slug, tmt.meta_value meta_title, tmd.meta_value meta_desc  FROM ' . $wpdb->terms . ' t LEFT JOIN '
            . '(SELECT * FROM ' . $wpdb->termmeta . ' WHERE meta_key = "wpms_category_metatitle") tmt ON t.term_id = tmt.term_id'
            . ' LEFT JOIN (SELECT * FROM ' . $wpdb->termmeta . ' WHERE meta_key = "wpms_category_metadesc") tmd ON t.term_id = tmd.term_id';

        $query = 'SELECT COUNT(A.term_id) FROM ' . $wpdb->term_taxonomy. ' A INNER JOIN '
            . '(' . $metaQuery .') BC ON A.term_id = BC.term_id';
        $query .= ' WHERE ' . implode(' AND ', $where);

        // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Variable has been prepare
        $total_items = $wpdb->get_var($query);

        $query = 'SELECT A.term_id, term_name, term_slug, meta_title, meta_desc, taxonomy, description, parent  FROM ' . $wpdb->term_taxonomy. ' A INNER JOIN '
            . '(' . $metaQuery .') BC ON A.term_id = BC.term_id';

        $query .= ' WHERE ' . implode(' AND ', $where) . $orderStr;

        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- No action, nonce is not required
        if (!empty($_REQUEST['wpms_cat_per_page'])) {
            //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- No action, nonce is not required
            $_per_page = intval($_REQUEST['wpms_cat_per_page']);
        } else {
            $_per_page = 0;
        }

        $per_page = get_user_option('wpms_cat_per_page');
        if ($per_page !== false) {
            if ($_per_page && $_per_page !== $per_page) {
                $per_page = $_per_page;
                update_user_option(get_current_user_id(), 'wpms_cat_per_page', $per_page);
            }
        } else {
            if ($_per_page > 0) {
                $per_page = $_per_page;
            } else {
                $per_page = 10;
            }
            add_user_meta(get_current_user_id(), 'wpms_cat_per_page', $per_page);
        }

        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- No action, nonce is not required
        $paged = !empty($_GET['paged']) ? $_GET['paged'] : '';
        if (empty($paged) || !is_numeric($paged) || $paged <= 0) {
            $paged = 1;
        }

        $total_pages = ceil($total_items / $per_page);

        if (!empty($paged) && !empty($per_page)) {
            $offset = ($paged - 1) * $per_page;
            $query  .= $wpdb->prepare(' LIMIT %d, %d', array($offset, $per_page));
        }

        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'total_pages' => $total_pages,
            'per_page'    => $per_page
        ));

        $columns               = $this->get_columns();
        $hidden                = array();
        $sortable              = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);

        // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Variable has been prepare
        $this->items = $wpdb->get_results($query);
    }

    /**
     * Generate the table rows
     *
     * @return void
     */
    public function display_rows() // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- extends from WP_List_Table class
    {
        $records   = $this->items;
        $i         = 0;
        $alternate = '';
        $url       = preg_replace('/(http|https):\/\/([w]*[.])?/', '', network_site_url('/'));
        $settings = get_option('_metaseo_settings');
        if (!$settings) {
            $settings = array();
        }
        if (!isset($settings['metaseo_metatitle_tab'])) {
            $settings['metaseo_metatitle_tab'] = 1;
        }
        list($columns, $hidden) = $this->get_column_info();

        if (!empty($records)) {
            foreach ($records as $rec) {
                foreach ($columns as $column_name => $column_display_name) {
                    $class = sprintf('class="%1$s column-%1$s"', esc_attr($column_name));
                    $style = '';

                    if (in_array($column_name, $hidden)) {
                        $style = ' style="display:none;"';
                    }

                    $attributes = $class . $style;
                    switch ($column_name) {
                        case 'cb':
                            echo '<td scope="row" class="check-column">';
                            echo '<input id="' . esc_attr('cb-select-' . $rec->term_id) . '"
                             class="wpms_cat_cb" type="checkbox" value="' . esc_attr($rec->term_id) . '">';
                            echo '</td>';
                            break;

                        case 'col_cat_title':
                            $cat_name = stripslashes($rec->term_name);
                            if ($cat_name === '') {
                                $cat_name = esc_html__('(no category)', 'wp-meta-seo');
                            }

                            echo sprintf(
                                '<td id="col_cat_name-' . esc_attr($rec->term_id) . '" %2$s><div class="wpms-category-title">'
                                . '<strong id="' . esc_attr('post-title-' . $rec->term_id) . '" class="post-title">%1$s</strong>',
                                esc_html($cat_name),
                                $attributes // phpcs:ignore WordPress.Security.EscapeOutput -- Content escaped in previous line (same function)
                            );

                            $taxonomy_object = get_taxonomy($rec->taxonomy);
                            $can_edit_cat    = current_user_can($taxonomy_object->cap->edit_terms, $rec->term_id);
                            $can_del_cat    = current_user_can($taxonomy_object->cap->delete_terms, $rec->term_id);
                            $actions = array();
                            // Edit category
                            if ($can_edit_cat) {
                                $actions['edit'] = '<a href="' . esc_url(get_edit_term_link($rec->term_id)) . '"
                                 title="' . esc_attr__('Edit this category', 'wp-meta-seo') . '"
                                 >' . esc_html__('Edit', 'wp-meta-seo') . '</a>';
                            }
                            // Preview category
                            $actions['view'] = '<a target="_blank" href="' . esc_url(get_category_link($rec->term_id)) . '"
                                 title="' . esc_attr__('Preview this category', 'wp-meta-seo') . '"
                                 >' . esc_html__('View', 'wp-meta-seo') . '</a>';

                            // phpcs:ignore WordPress.Security.EscapeOutput -- Content escaped in previous line (same function)
                            echo $this->row_actions($actions);
                            break;
                        case 'col_cat_snippet':
                            echo '<td id="col_meta_seo-' . esc_attr($rec->term_id) . '"><div class="col-metaseo-wrapper">';
                            echo '<span class="snippet_metalink" style="padding-left: 10px;" id="' . esc_attr('snippet_metalink_' . $rec->term_id) . '">
                            ' . esc_url(get_category_link($rec->term_id)) . '</span>';
                            // <!-- Engine title -->
                            echo '<div class="custom-bulk-content-b wpms-category-title">';
                            echo '<input type="text" placeholder="Put your meta description here. Click here to edit..." class="large-text wpms_category_metatitle wpms-category-title-input wpms-cat-meta-title intro-topic-tooltip" rows="1"'
                                . ' id="wpms-cat-meta-title-'. esc_attr($rec->term_id) . '" name="wpms_meta_title['. esc_attr($rec->term_id) . ']" autocomplete="off"'
                                . ' value="'. esc_html($rec->meta_title) . '" data-tippy="This is your category meta title that should be displayed in Google Search results for this page">';
                            // title counter
                            echo sprintf(
                                '<div class="title-len wpms-cat-title-len" style="bottom: 32px" id="%1$s"></div>',
                                esc_attr('wpms-cat-title-len' . $rec->term_id)
                            );
                            echo '</div>';
                            // <!-- /Engine title -->
                            // <!-- Engine description -->
                            echo '<div class="custom-bulk-content-b wpms-category-desc">';
                            echo '<textarea placeholder="Put your meta description here. Click here to edit..." class="large-text wpms_category_metadesc wpms-category-desc-textarea wpms-cat-meta-desc intro-topic-tooltip"'
                                . ' id="wpms-cat-meta-desc-'. esc_attr($rec->term_id) . '" name="wpms_meta_desc['. esc_attr($rec->term_id) . ']" autocomplete="off" data-tippy="This is your category meta description that should be displayed in Google Search results for this page">'. esc_textarea((string)$rec->meta_desc) . '</textarea>';
                            // description counter
                            echo sprintf(
                                '<div class="desc-len wpms-cat-desc-len" style="bottom: 45px" id="%1$s"></div>',
                                esc_attr('wpms-cat-desc-len' . $rec->term_id)
                            );
                            echo '</div>'; // <!-- /Engine description -->
                            // loader and success message
                            echo '<img id="wpms-cat-imgloader-'. esc_attr($rec->term_id) . '" class="' . esc_attr('wpms_cat_loader' . $rec->term_id . ' wpms_content_loader') . '"
                             src=' . esc_url(WPMETASEO_PLUGIN_URL) . 'assets/images/update_loading.gif>';
                            echo '<span id="wpms-cat-return-msg-'. esc_attr($rec->term_id) . '"
                            style="display: inline-block; visibility: hidden"
                            class="saved-info metaseo-msg-success">test wpms msg</span>';
                            echo '</div></td>'; // <!-- /Wrapper -->
                            break;
                    }
                }

                echo '</tr>';
            }
        }
    }

    /**
     * Retrieves a modified URL query string.
     *
     * @return void
     */
    public function processAction()
    {
        $current_url = set_url_scheme('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        $redirect    = false;
        // phpcs:disable WordPress.Security.NonceVerification.Missing -- No action, nonce is not required
        if (isset($_POST['taxonomy_filter'])) {
            $current_url = add_query_arg(array('taxonomy_filter' => $_POST['taxonomy_filter']), $current_url);
            $redirect = true;
        }

        if (isset($_POST['wpms_duplicate_cat_meta'])) {
            $current_url = add_query_arg(array('wpms_duplicate_cat_meta' => $_POST['wpms_duplicate_cat_meta']), $current_url);
            $redirect = true;
        }

        if (isset($_POST['wpms_lang_list'])) {
            $current_url = add_query_arg(array('wpms_lang_list' => $_POST['wpms_lang_list']), $current_url);
            $redirect = true;
        }

        if (!empty($_POST['paged'])) {
            $current_url = add_query_arg(array('paged' => intval($_POST['paged'])), $current_url);
            $redirect    = true;
        }

        if (!empty($_POST['wpms_cat_per_page'])) {
            $current_url = add_query_arg(
                array(
                    'wpms_cat_per_page' => intval($_POST['wpms_cat_per_page'])
                ),
                $current_url
            );
            $redirect    = true;
        }

        if (isset($_POST['s'])) {
            $current_url = add_query_arg(array('s' => urlencode($_POST['s'])), $current_url);
            $redirect    = true;
        }
        // phpcs:enable
        if ($redirect) {
            wp_redirect($current_url);
            ob_end_flush();
            exit();
        }
    }

    /**
     * Update categories content field
     *
     * @return void
     */
    public static function updateCategoryContent()
    {
        if (empty($_POST['wpms_nonce'])
            || !wp_verify_nonce($_POST['wpms_nonce'], 'wpms_nonce')) {
            die();
        }
        if (!isset($_POST['termID']) || !isset($_POST['dataType']) || !isset($_POST['data'])
            || empty($_POST['termID']) || empty($_POST['dataType'])) {
            die('-1');
        }
        $termID = filter_var($_POST['termID'], FILTER_SANITIZE_NUMBER_INT);
        $dataType = filter_var($_POST['dataType'], FILTER_UNSAFE_RAW);
        $data = filter_var($_POST['data'], FILTER_UNSAFE_RAW);
        $response = array('updated'=> false, 'msg' => 'Some things went wrong');
        if (!current_user_can('manage_options')) {
            $response['msg'] = 'Permission denied';
            wp_send_json($response);
        }
        if (!empty($termID) && !empty($dataType) && isset($data)) {
            switch ($dataType) {
                case 'wpms-cat-meta-title':
                        update_term_meta($termID, 'wpms_category_metatitle', $data);
                        $response['updated'] = true;
                        $response['msg'] = 'Meta seo title was saved';
                    break;
                case 'wpms-cat-meta-desc':
                        update_term_meta($termID, 'wpms_category_metadesc', $data);
                        $response['updated'] = true;
                        $response['msg'] = 'Meta seo description was saved';
                    break;
            }
        }
        echo json_encode($response);
        wp_die();
    }

    /**
     * Delete cat category
     *
     * @return void
     */
    public static function wpmsDeleteCat()
    {
        if (empty($_POST['wpms_nonce'])
            || !wp_verify_nonce($_POST['wpms_nonce'], 'wpms_nonce')) {
            die();
        }
        if (!isset($_POST['catData'])) {
            die('Category data not found');
        }
        $catData = $_POST['catData'];
        $term_id = (int)($catData['term_id']);
        $taxonomy = filter_var($catData['taxonomy'], FILTER_UNSAFE_RAW);
        $response = array('status' => false, 'msg' => 'Something went wrong');
        if ($term_id && $taxonomy) {
            if (wp_delete_term($term_id, $taxonomy)) {
                $response['status'] = true;
                $response['msg'] = 'Delete category successfully';
            }
        }
        wp_send_json($response);
        wp_die();
    }

    /**
     * Bulk copy category meta title/description
     *
     * @return void
     */
    public static function wpmsBulkCatCopy()
    {
        if (empty($_POST['wpms_nonce'])
            || !wp_verify_nonce($_POST['wpms_nonce'], 'wpms_nonce')) {
            die();
        }
        if (!isset($_POST['catData'])) {
            die('Category data not found');
        }
        $catData = $_POST['catData'];
        $taxonomy = array('category', 'product_cat');
        if (isset($catData['sl_bulk']) && $catData['sl_bulk'] === 'all') {
            $msArgs = array(
                'taxonomy'               => $taxonomy,
                'hide_empty'             => false,
            );
        } elseif (isset($catData['sl_bulk']) && $catData['sl_bulk'] === 'only-selection' && isset($catData['msCatSelected'])) {
            $msArgs = array(
                'taxonomy'               => $taxonomy,
                'hide_empty'             => false,
                'include' => (array)$catData['msCatSelected']
            );
        }
        $msTermQuery = new WP_Term_Query($msArgs);
        $msTerms = $msTermQuery->get_terms();
        if (!isset($catData['action_name'])) {
            return;
        }
        $action = $catData['action_name'];
        if ($action === 'cat-name-to-title') {
            $meta_key = 'wpms_category_metatitle';
        } else {
            $meta_key = 'wpms_category_metadesc';
        }
        $response = array('updated' => false);
        if (!empty($msTerms)) {
            foreach ($msTerms as $term) {
                $meta_value = $term->name;
                $meta_value = sanitize_text_field($meta_value);
                update_term_meta($term->term_id, $meta_key, $meta_value);
                $response['updated'] = true;
            }
        }
        wp_send_json($response);
        wp_die();
    }
}