<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Sensor_Menu extends WADA_Sensor_Base
{
    protected $priorMenus = null;
    protected $priorMenuItems = null;
    protected $recordedEvents = null;

    public function __construct($sensorGroup = WADA_Sensor_Base::GRP_MENU){
        parent::__construct($sensorGroup);
        $this->priorMenus = array();
        $this->priorMenuItems = array();
        $this->recordedEvents = array();
    }

    public function registerSensor(){
        add_filter('wp_create_nav_menu', array($this, 'onMenuCreation'), 10, 2);
        add_filter('edit_terms', array($this, 'beforeMenuUpdate'), 10, 2);
        add_filter('wp_update_nav_menu', array($this, 'onMenuUpdate'), 10, 2);
        add_filter('wp_get_nav_menu_object', array($this, 'beforeMenuMightGetDeleted'), 10, 2);
        add_filter('pre_delete_term', array($this, 'beforeMenuDelete'), 10, 2);
        add_filter('wp_delete_nav_menu', array($this, 'onMenuDelete'), 10, 1);
        add_filter('wp_add_nav_menu_item', array($this, 'onMenuItemAdded'), 10, 3);
        add_action('shutdown', array($this, 'onShutdownProcessOpenEvents'), 10, 0);
        //WADA_Log::debug('Active sensors for group '.$this->sensorGroup.': '.print_r($this->activeSensors, true));
    }

    /**
     * @param int $termId ID of the menu
     * @param array $menuData Data of the menu
     */
    public function onMenuCreation($termId, $menuData){
        if(!$this->isActiveSensor(self::EVT_MENU_CREATE)) return $this->skipEvent(self::EVT_MENU_CREATE);
        $menuTerm = get_term($termId);
        WADA_Log::debug('onMenuCreation termId: '.$termId.', menu data: '.print_r($menuData, true).', menuTerm: '.print_r($menuTerm, true));
        return $this->prepareMenuTermChangesAndStoreEvent(false,self::EVT_MENU_CREATE, $termId, $menuTerm);
    }

    /**
     * @param int $termId Term ID
     * @param string $taxonomy Taxonomy slug
     */
    public function beforeMenuUpdate($termId, $taxonomy){
        if($taxonomy === 'nav_menu'){
            WADA_Log::debug('beforeMenuUpdate termId: '.$termId.', taxonomy: '.$taxonomy);
            $this->priorMenus[$termId] = get_term($termId, $taxonomy);
            $this->priorMenuItems[$termId] = wp_get_nav_menu_items($termId);
        }
    }

    /**
     * @param int $termId ID of the menu
     * @param array $menuData Data of the menu
     */
    public function onMenuUpdate($termId, $menuData=array()){
        if(!$this->isActiveSensor(self::EVT_MENU_UPDATE)) return $this->skipEvent(self::EVT_MENU_UPDATE);
        $menuAfter = get_term($termId);
        $menuItemsAfter = wp_get_nav_menu_items($termId);
        $menuBefore = array_key_exists($termId, $this->priorMenus) ? $this->priorMenus[$termId] : null;
        $menuItemsBefore = array_key_exists($termId, $this->priorMenuItems) ? $this->priorMenuItems[$termId] : array();
        WADA_Log::debug('onMenuUpdate termId: '.$termId.', menu data: '.print_r($menuData, true)
            .', menuAfterUpdate: '.print_r($menuAfter, true)
            .', menuBeforeUpdate: '.print_r($menuBefore, true)
            .', menuItemsAfter: '.print_r($menuItemsAfter, true)
            .', menuItemsBefore: '.print_r($menuItemsBefore, true));

        $processOnShutdown = true; // because we have "duplicate events" happening where we want to catch the last one, so the events pile on (for same sensor/event overwriting previous ones)
        return $this->prepareMenuTermChangesAndStoreEvent($processOnShutdown, self::EVT_MENU_UPDATE, $termId, $menuAfter, $menuBefore, $menuItemsAfter, $menuItemsBefore);
    }

    /**
     * @param WP_Term|false $menuObj
     * @param int|string|WP_Term $menu
     */
    public function beforeMenuMightGetDeleted($menuObj, $menu){
        if($menuObj instanceof WP_Term){
            $objectIds = get_objects_in_term($menuObj->term_id, 'nav_menu');
            $defaults        = array(
                'order'       => 'ASC',
                'orderby'     => 'menu_order',
                'post_type'   => 'nav_menu_item',
                'post_status' => 'publish',
                'output'      => ARRAY_A,
                'output_key'  => 'menu_order',
                'nopaging'    => true,
            );
            $args = wp_parse_args(array(), $defaults );
            $args['include'] = $objectIds;
            $items = get_posts( $args );
            if(array_key_exists($menuObj->term_id, $this->priorMenuItems)){
                WADA_Log::debug('beforeMenuMightGetDeleted termId: '.$menuObj->term_id.' not updating what we already have');
            }else{
                $this->priorMenuItems[$menuObj->term_id] = $items;
                WADA_Log::debug('beforeMenuMightGetDeleted termId: '.$menuObj->term_id.', items: '.print_r($this->priorMenuItems[$menuObj->term_id], true));
            }

        }
        return $menuObj; // this is a filter after all
    }

    /**
     * @param int $termId Term ID
     * @param string $taxonomy Taxonomy slug
     */
    public function beforeMenuDelete($termId, $taxonomy){
        if($taxonomy === 'nav_menu'){
            WADA_Log::debug('beforeMenuDelete termId: '.$termId.', taxonomy: '.$taxonomy);
            $this->priorMenus[$termId] = get_term($termId, $taxonomy);
            //$this->priorMenuItems[$termId] = wp_get_nav_menu_items($termId); // already deleted at this point, but we caught them with the beforeMenuMightGetDeleted method
        }
    }

    /**
     * @param int $termId ID of the menu
     */
    public function onMenuDelete($termId){
        if(!$this->isActiveSensor(self::EVT_MENU_DELETE)) return $this->skipEvent(self::EVT_MENU_DELETE);
        $menuBeforeDeletion = array_key_exists($termId, $this->priorMenus) ? $this->priorMenus[$termId] : null;
        $menuItemsBeforeDeletion = array_key_exists($termId, $this->priorMenuItems) ? $this->priorMenuItems[$termId] : null;
        WADA_Log::debug('onMenuDelete termId: '.$termId.', menuBeforeDeletion: '.print_r($menuBeforeDeletion, true).', menuItemsBeforeDeletion: '.print_r($menuItemsBeforeDeletion, true));
        return $this->prepareMenuTermChangesAndStoreEvent(false, self::EVT_MENU_DELETE, $termId, $menuBeforeDeletion, null, $menuItemsBeforeDeletion, null);
    }

    /**
     * @param int $menuId
     * @param int $menuItemDbId
     * @param array $argArr
     */
    public function onMenuItemAdded($menuId, $menuItemDbId, $argArr){
        // this does not seem to be helpful!
        // is triggering when menus are added in UI, but before persistent storage when menu is updated
        // so take menu update for handling it
    }

    /**
     * Process everything pending
     */
    public function onShutdownProcessOpenEvents(){
        foreach($this->recordedEvents AS $recordedEvent){
            WADA_Log::debug('onShutdownProcessOpenEvents sensorID '.$recordedEvent['sensor_id'].', '
                .'targetID '.$recordedEvent['target_object_id'].', data '.print_r($recordedEvent['data'], true));
            $this->storeMenuEvent($recordedEvent['sensor_id'], $recordedEvent['data'], $recordedEvent['target_object_id']);
        }
    }


    /**
     * @param bool $processOnShutdown
     * @param int $sensorId
     * @param int $termId
     * @param WP_Term $currentTerm
     * @param WP_Term|null $priorTerm
     * @param array<WP_Post>|null $currentNavItems
     * @param array<WP_Post>|null $priorNavItems
     * @return bool
     */
    protected function prepareMenuTermChangesAndStoreEvent($processOnShutdown, $sensorId, $termId, $currentTerm, $priorTerm=null, $currentNavItems=null, $priorNavItems=null){
        if(is_null($priorTerm)){
            $priorTerm = new stdClass();
        }
        $infos = WADA_TermUtils::getTermChanges($currentTerm, $priorTerm);
        WADA_Log::debug('prepareMenuTermChangesAndStoreEvent infos: '.print_r($infos, true));
        $navItemChanges = array();
        if(!is_null($currentNavItems)){
            $navItemChanges = WADA_CompUtils::getChangedObjectIdsInArrays($priorNavItems, $currentNavItems,
                'ID', 'NAV_ITEM_POST_ID',
                true, function($id, $inCurrent, $inPrior) use ($sensorId, $currentNavItems, $priorNavItems){
                $allNavItems = array_merge($currentNavItems, is_null($priorNavItems) ? array() : $priorNavItems);
                if($sensorId == WADA_Sensor_Base::EVT_MENU_DELETE){
                    $infoKey = 'NAV_ITEM_DELETED';
                }else {
                    if ($inCurrent) {
                        $infoKey = 'NAV_ITEM_ADDED';
                    } else {
                        $infoKey = 'NAV_ITEM_REMOVED';
                    }
                }

                $infoValue = $inCurrent ? $id : null;
                $priorValue = $inPrior ? $id : null;
                foreach($allNavItems AS $navItem){
                    if($navItem instanceof WP_Post && $navItem->ID == $id){
                        $idStr = sprintf(__('ID %d', 'wp-admin-audit'), $navItem->ID);
                        $prefix = (property_exists($navItem, 'object') ? ($navItem->object.': ') : '');
                        $postTitle = (property_exists($navItem, 'title') ? $navItem->title : $navItem->post_title);
                        $title = trim($prefix.$postTitle);
                        $title = (strlen($title)>0 ? ($title.' ('.$idStr.')') : $idStr);
                        $infoValue = $inCurrent ? $title : null;
                        $priorValue = $inPrior ? $title : null;
                    }
                }

                return array(
                    'info_key' => $infoKey,
                    'info_value' => $infoValue,
                    'prior_value' => $priorValue
                );
            });
            WADA_Log::debug('prepareMenuTermChangesAndStoreEvent navItemChanges: '.print_r($navItemChanges, true));
        }
        $infos = array_merge($infos, $navItemChanges);
        if(count($infos) == 0){
            // skip, nothing to report
            WADA_Log::debug('prepareMenuTermChangesAndStoreEvent skip, no changes recorded!');
            return 0;
        }
        $eventData = array('infos' => $infos);
        if($processOnShutdown){
            $this->recordedEvents[$sensorId] = array('sensor_id' => $sensorId, 'data' => $eventData, 'target_object_id' => $termId);
            return 0;
        }else{
            return $this->storeMenuEvent($sensorId, $eventData, $termId);
        }
    }


    /**
     * @param int $userId
     * @param int $targetObjectId
     * @param string|null $targetObjectType
     * @return array
     */
    protected function getEventDefaults($userId = 0, $targetObjectId = 0, $targetObjectType = self::OBJ_TYPE_CORE_MENU){
        // change to parent function is that we default to passing in the object type of WP Menu
        return parent::getEventDefaults($userId, $targetObjectId, $targetObjectType);
    }

    /**
     * @param int $sensorId
     * @param array $menuData
     * @param int $targetMenuId
     * @return bool
     */
    protected function storeMenuEvent($sensorId, $menuData = array(), $targetMenuId = 0){
        $executingUserId = get_current_user_id();
        $event = (object)(array_merge($this->getEventDefaults($executingUserId, $targetMenuId), $menuData));
        $res = $this->storeEvent($sensorId, $event);
        if($res){
            $this->eventTracker[] = array('menu_id' => $targetMenuId, 'sensor_id' => $sensorId);
        }
        return $res;
    }

}