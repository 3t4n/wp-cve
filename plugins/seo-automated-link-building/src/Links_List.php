<?php
/**
 * Internal Links Manager
 * Copyright (C) 2021 webraketen GmbH
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You can read the GNU General Public License here: <https://www.gnu.org/licenses/>.
 * For questions related to this program contact post@webraketen-media.de
 */

namespace SeoAutomatedLinkBuilding;

if(!class_exists('\WP_List_Table')) {
    require_once(__DIR__ . '/class-wp-list-table.php');
}

class Links_List extends \WP_List_Table
{
    protected $limit = 50;
    protected $offset = 0;

    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    public function prepare_items()
    {
        $this->process_bulk_action();

        $columns  = $this->get_columns();
        $hidden   = [];
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = [$columns, $hidden, $sortable];

        $currentPage = $this->get_pagenum();
        $offset = ($currentPage-1) * $this->limit;

        $search = isset($_POST['s']) ? sanitize_text_field($_POST['s']) : '';
        $active = isset($_REQUEST['active']) ? $_REQUEST['active'] === '1' : null;
        $orderBy = isset($_REQUEST['orderby']) ? sanitize_sql_orderby($_REQUEST['orderby']) : 'title';
        $order = isset($_REQUEST['order']) && $_REQUEST['order'] === 'desc' ? 'desc' : 'asc';
        $select = Link::query()->limit($this->limit)->offset($offset)->order_by($orderBy, $order);
        if(!empty($search)) {
            $select = $select->where('title', 'LIKE', '%' . Link::wpdb()->esc_like($search) . '%');
        }
        if(!is_null($active)) {
            $select = $select->where('active', $active);
        }
        $this->items = $select->get();

        $all = Link::query()->select("count(*)");
        if(!empty($search)) {
            $all = $all->where('title', 'LIKE', '%' . Link::wpdb()->esc_like($search) . '%');
        }

        $this->set_pagination_args(array(
            'total_items' => $all->get_var(),
            'per_page'    => $this->limit,
        ));
    }

    function get_sortable_columns() {
        return [
            'title' => ['title', true],
            'keywords' => ['keywords', true],
            'url' => ['url', true],
            'status' => ['status', true],
            'priority' => ['priority', true],
        ];
    }

    public function get_columns()
    {
        return [
            'cb' => '<input type="checkbox">',
            'title' => __('Title'),
            'keywords' => __('Keywords', 'seo-automated-link-building'),
            'url' => __('Page') . ' / ' . __('Url'),
            'status' => __('Status'),
            'priority' => __('Priority', 'seo-automated-link-building')
        ];
    }

    public function get_bulk_actions()
    {
        return [
            'delete' => __('Delete'),
            'activate' => __('Activate'),
            'deactivate' => __('Deactivate'),
            'export' => __('Export', 'seo-automated-link-building')
        ];
    }

    public function process_bulk_action()
    {
        $action = $this->current_action();

        $ids = isset($_REQUEST['id']) ? wp_parse_id_list($_REQUEST['id']) : null;
        if(is_null($ids) || empty($ids)) {
            return;
        }

        if($action === 'delete' && isset($_REQUEST['id'])) {
            Link::query()->delete()->where('id', 'in', [$ids])->execute();
        }

        if($action === 'deactivate' && isset($_REQUEST['id'])) {
            Link::query()->update()->where('id', 'in', [$ids])->set('active', false)->execute();
        }

        if($action === 'activate') {
            Link::query()->update()->where('id', 'in', [$ids])->set('active', true)->execute();
        }

    }

    protected function column_default($item, $column_name)
    {
        return $item->{$column_name};
    }

    protected function column_cb($item)
    {
        return sprintf('<input type="checkbox" name="id[]" value="%1$s" />', $item->id);
    }

    protected function column_title($item)
    {
        return "<strong><a href='" . admin_url( "admin.php?page=seo-automated-link-building-all-links&id=" . $item->id ) . "'>{$item->title}</a></strong>
        <div class='row-actions'>
            <span class='edit'><a href='" . admin_url( "admin.php?page=seo-automated-link-building-all-links&id=" . $item->id ) . "'>" . __('Edit') . "</a> | </span>
            <span class='activation'><a href='" . admin_url( "admin.php?page=seo-automated-link-building-all-links&action=" . ($item->active ? 'deactivate' : 'activate') . "&id=" . $item->id ) . "'>" . __($item->active ? 'Deactivate' : 'Activate') . "</a> | </span>
            <span class='trash'><a href='" . admin_url( "admin.php?page=seo-automated-link-building-all-links&action=delete&id=" . $item->id ) . "'>" . __('Delete') . "</a></span>
        </div>";
    }

    protected function column_url($item)
    {
        $title = $item->url;
        $url = $item->url;
        if($item->page_id) {
            $post = get_post($item->page_id);
            if($post) {
                $title = $post->post_title;
                $url = get_permalink($post);
            }
        }
        return "<a href='$url' target='_blank'>" . htmlentities($title) . "</a>";
    }

    protected function column_keywords($item) {
        $keywords = json_decode($item->keywords, false, 512, JSON_UNESCAPED_UNICODE);
        if(!is_array($keywords)) {
            $keywords = [];
        }
        return implode('<br>', $keywords);
    }

    protected function column_status($item)
    {
        return __($item->active ? 'Active' : 'Deactivated', 'seo-automated-link-building');
    }

    public function no_items() {
        _e( 'No links available.', 'seo-automated-link-building' );
    }

    public function display()
    {
        $this->prepare_items();
        $this->search_box(__('Search'), 'seo-automated-link-building');
        parent::display(); // TODO: Change the autogenerated stub
    }

}
