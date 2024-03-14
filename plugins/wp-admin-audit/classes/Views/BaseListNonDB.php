<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
 */

if(!defined('ABSPATH')){
    exit;
}

abstract class WADA_View_BaseListNonDB extends WADA_View_BaseList
{
    public $allItems = array();
    public $lastError = null;
    abstract protected function loadItems($searchTerm = null);

    protected function getItemsQuery($searchTerm = null){ // this is not needed in Non-DB class
        return false;
    }

    protected function getPaginatedItems($perPage, $currentPage){
        WADA_Log::debug('getPaginatedItems perPage: '.$perPage.', currentPage: '.$currentPage.' VS total: '.count($this->allItems));
        return array_slice($this->allItems, ($currentPage-1)*$perPage, $perPage );
    }

    public function prepare_items($returnEmpty = false) {
        WADA_Log::debug(static::VIEW_IDENTIFIER.'->prepare_items');
        $this->setupColumns();
        $this->process_bulk_action();

        $perPage = $this->get_items_per_page();
        $perPage = ($perPage > 0) ? $perPage : 1;

        if($returnEmpty){
            WADA_Log::debug('BaseListNonDB return empty');
            $this->set_pagination_args( array(
                'total_pages' => 1, // we put one, so that the pagination element gets rendered (will be needed later)
                'total_items' => 1, // we put one, so that the pagination element gets rendered (will be needed later)
                'per_page'    => $perPage
            ) );
            $this->totalItems = 0;
            $this->items = null;
        }else{
            WADA_Log::debug('BaseListNonDB return for real!');
            $searchTerm = $this->getSearchTerm();
            $this->allItems = $this->loadItems($searchTerm);
            $this->sortItems();
            WADA_Log::debug('BaseListNonDB allItems: '.print_r($this->allItems, true));
            $totalItems = count($this->allItems);
            $totalPages = ceil($totalItems/$perPage);

            $this->set_pagination_args( array(
                'total_pages' => $totalPages, //WE have to calculate the total number of pages
                'total_items' => $totalItems, //WE have to calculate the total number of items
                'per_page'    => $perPage //WE have to determine how many items to show on a page
            ) );

            $this->totalItems = $totalItems;
            $currentPage = $this->get_pagenum();
            $this->items = $this->getPaginatedItems($perPage, $currentPage);
            WADA_Log::debug('BaseListNonDB items: '.print_r($this->items, true));
            $this->items = $this->performAdditionalItemPreparation();
            WADA_Log::debug('BaseListNonDB after: '.print_r($this->items, true));
        }

    }

    protected function sortItems(){
        if(count($this->allItems) < 1){
            return;
        }
        WADA_Log::debug('sortItems');
        $orderBy = $order = null;
        if ( ! empty( $_REQUEST['orderby'] ) ) {
            $orderBy = sanitize_sql_orderby( $_REQUEST['orderby'] );
            $order = !empty( $_REQUEST['order'] ) ? sanitize_text_field( $_REQUEST['order'] ) : 'ASC';
        }elseif(method_exists($this, 'getDefaultOrder')){
            list($orderBy, $order) = $this->getDefaultOrder();
        }
        if($orderBy === 'id_only'){
            $orderBy = 'id';
        }
        $firstObj = (object)$this->allItems[0];
        WADA_Log::debug('sortItems by '.$orderBy.', first as example: '.print_r($firstObj, true));
        if($orderBy && property_exists($firstObj, $orderBy)) {
            $order = strtoupper(trim(sanitize_text_field($order)));
            if($order !== 'ASC' AND $order !== 'DESC' AND $order !== ''){
                $order = 'ASC';
            }
            WADA_Log::debug('sortItems by '.$orderBy.' '.$order);

            $this->allItems = WADA_PHPUtils::sortObjArrayByProperty($this->allItems, $orderBy, ($order === 'ASC'));
        }
    }
}