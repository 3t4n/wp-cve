<?php

namespace Premmerce\Redirect\Admin;

use Premmerce\Redirect\RedirectModel;
use Premmerce\SDK\V2\FileManager\FileManager;

/**
 * Class RedirectsTable
 * @package Premmerce\Redirect\Admin
 */
class RedirectsTable extends \WP_List_Table
{
    /**
     * @var RedirectModel
     */
    private $api;

    /**
     * @var FileManager
     */
    private $fileManager;

    /**
     * RedirectsTable constructor.
     * @param RedirectModel $model
     * @param FileManager $fileManager
     */
    public function __construct(RedirectModel $model, FileManager $fileManager)
    {
        $this->api = $model;
        $this->fileManager = $fileManager;

        parent::__construct(array(
            'singular' => __('types', 'premmerce-redirect'),
            'plural'   => __('type', 'premmerce-redirect'),
            'ajax'     => false,
        ));

        $this->_column_headers = array(
            $this->get_columns()
        );
        $this->prepare_items();
    }

    /**
     * @param array $item
     * @return string
     */
    protected function column_cb($item)
    {
        return '<input type="checkbox" name="ids[]" id="cb-select-' . $item->id . '" value="' . $item->id . '">';
    }

    /**
     * @param array $item
     * @return string
     */
    protected function column_source_url($item)
    {
        return '
            <a href="' . $_SERVER['REQUEST_URI'] . '&tab=edit&id=' . $item->id . '">' . esc_url($item->old_url) . '</a>
            <div class="row-actions">
                <span class="edit">
                    <a href="' . $_SERVER['REQUEST_URI'] . '&tab=edit&id=' . $item->id . '">
                        ' . __('Edit', 'premmerce-redirect') . '
                    </a> | 
                </span>
                <span class="delete">
                    <a data-link="delete" href="' . Admin::getDeleteURL($item->id) . '">
                        ' . __('Delete', 'premmerce-redirect') . '
                    </a>
                </span>
            </div>
        ';
    }

    /**
     * @param array $item
     * @return string
     */
    protected function column_type_target($item)
    {
        return $item->redirect_type;
    }

    /**
     * @param array $item
     * @return string
     */
    protected function column_target_url($item)
    {
        return $this->getContentUrl($item->redirect_type, $item->redirect_content);
    }

    /**
     * @param array $item
     * @return string
     */
    protected function column_status_code($item)
    {
        return $item->type;
    }

    /**
     * Return array with columns titles
     *
     * @return array
     */
    public function get_columns()
    {
        return array(
            'cb'          => '<input type="checkbox">',
            'source_url'  => __('Source URL', 'premmerce-redirect'),
            'type_target' => __('Type of target URL', 'premmerce-redirect'),
            'status_code' => __('Status Code', 'premmerce-redirect'),
            'target_url'  => __('Target URL', 'premmerce-redirect'),
        );
    }

    /**
     * Set actions list for bulk
     *
     * @return array
     */
    protected function get_bulk_actions()
    {
        return array(
            'delete' => __('Delete', 'premmerce-redirect')
        );
    }

    /**
     * Set items data in table
     */
    public function prepare_items()
    {
        if (is_plugin_active('woocommerce/woocommerce.php')) {
            $data = $this->api->getRedirects();
        } else {
            $data = $this->api->getRedirects(false);
//                ['redirect_type', 'NOT IN', ['product', 'product_category']]
        }

        if (isset($_POST['s']) && $_POST['s']) {
            $data = $this->searchItems($data);
        }

        $perPage = get_option('premmerce_redirect_items_per_page') ? get_option('premmerce_redirect_items_per_page') : 10;
        $currentPage = $this->get_pagenum();
        $totalItems = count($data);

        $this->set_pagination_args(array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ));

        $data = array_slice($data, (($currentPage - 1) * $perPage), $perPage);

        $this->items = $data;
    }

    /**
     * Render redirects table
     */
    public function display()
    {
        $this->search_box(__('Search', 'premmerce-redirect'), 'search');

        parent::display();
    }

    /**
     * Displays the search box.
     *
     * @param string $text
     * @param string $inputId
     */
    public function search_box($text, $inputId)
    {
        $inputId = $inputId . '-search-input';

        $this->fileManager->includeTemplate('admin/redirects-search.php', array(
            'inputId' => $inputId,
            'text'    => $text
        ));
    }

    /**
     * Get url by content type
     *
     * @param string $contentType
     * @param string $content
     * @return string
     */
    private function getContentUrl($contentType, $content)
    {
        switch ($contentType) {
            case 'url':
                $url = '<a href="' . esc_url($content) . '" target="_blank">' . esc_url($content) . '</a>';
                break;

            case 'product':
                $url = '<a href="' . get_permalink($content) . '" target="_blank">' . get_permalink($content) . '</a>';
                break;

            case 'product_category':
                $url = '<a href="' . get_term_link((int) $content, 'product_cat') . '" target="_blank">' . get_term_link((int) $content, 'product_cat') . '</a>';
                break;

            case 'category':
                $url = '<a href="' . get_term_link((int) $content, 'category') . '" target="_blank">' . get_term_link((int) $content, 'category') . '</a>';
                break;

            case 'post':
                $url = '<a href="' . get_permalink($content) . '" target="_blank">' . get_permalink($content) . '</a>';
                break;

            case 'page':
                $url = '<a href="' . get_permalink($content) . '" target="_blank">' . get_permalink($content) . '</a>';
                break;
        }

        return $url;
    }

    /**
     * Search items for ord or new url
     *
     * @param array $items
     * @return array
     */
    private function searchItems($items)
    {
        $newItems = array();

        foreach ($items as $item) {
            if (stristr($item->old_url, $_POST['s']) !== false || stristr($this->getContentUrl($item->redirect_type, $item->redirect_content), $_POST['s']) !== false) {
                array_push($newItems, $item);
            }
        }

        return $newItems;
    }

    /**
     * Render if no items
     */
    public function no_items()
    {
        _e('No redirects found.', 'premmerce-redirect');
    }

    /**
     * Render tablenav block
     *
     * @param string $which
     */
    protected function display_tablenav($which)
    {
        if ($which === 'top') {
            wp_nonce_field('bulk-' . $this->_args['plural']);
        }

        $this->fileManager->includeTemplate('admin/redirects-bulk-actions.php', array(
            'which'  => $which,
            'table'  => $this
        ));
    }
}
