<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_View_Extensions extends WADA_View_BaseListNonDB
{
    const VIEW_IDENTIFIER = 'wada-extensions';
    const LIST_FORM_ID = 'wada-extension-list-form';
    const AJAX_ACTION = '_wada_ajax_extensions_list';
    const NONCE_ACTION = 'wada-ajax-extensions-list-nonce';
    const NONCE_NAME = '_wada_ajax_extensions_list_nonce';
    public $sections;

    public function __construct($viewConfig = array()) {
        $this->viewHeadline =  __('Extensions', 'wp-admin-audit');
        parent::__construct( array(
            'singular' => __( 'Extension', 'wp-admin-audit' ), //singular name of the grouped records
            'plural'   => __( 'Extensions', 'wp-admin-audit' ), //plural name of the grouped records
            'ajax'     => true // does this table support ajax?
        ), $viewConfig);
        add_action('admin_footer', array($this, 'loadJavascriptActions'));
        $this->sections =  array(
            (object) array('name' => 'all', 'label' => __('All', 'wp-admin-audit')),
            (object) array('name' => 'ready', 'label' => __('Ready', 'wp-admin-audit')),
            (object) array('name' => 'issue', 'label' => __('Issue', 'wp-admin-audit')),
            (object) array('name' => 'installed', 'label' => __('Installed', 'wp-admin-audit'))
        );
    }

    function get_columns() {
        $colArray = array(
            'title'    => __( 'Name', 'wp-admin-audit' ),
            'last_updated'    => __( 'Last updated', 'wp-admin-audit' ),
            'current_release_number'    => __( 'Release', 'wp-admin-audit' ),
            'status'    => __( 'Status', 'wp-admin-audit' )
        );

        if(WADA_Version::getFtSetting(WADA_Version::FT_ID_EXT)){
            $colArray['actions'] = __( 'Actions', 'wp-admin-audit' );
        }

        return $colArray;
    }

    public function get_sortable_columns() {
        return array(
            'title' => array('name', 'asc'),
            'last_updated' => array('last_updated', 'desc'),
            'current_release_number' => array('current_release_number', 'asc')
        );
    }

    function column_title($item) {
        $viewDetailsLink = sprintf(
                '<a href="%s" class="thickbox open-plugin-details-modal" aria-label="%s" data-title="%s">%s</a>',
                esc_url(network_admin_url('plugin-install.php?tab=plugin-information'
                .'&plugin=' . urlencode($item['wada_slug']).'&TB_iframe=true&width=600&height=550')),
                esc_attr(sprintf(__('More information about %s'), $item['name'])),
                esc_attr($item['name']),
                __('View details')
        );
        $searchRes = $this->formatSearchResult(esc_html($item['name']), $this->getSearchTerm());
        return '<strong>'.$searchRes.'</strong>'.$viewDetailsLink;
    }

    function column_last_updated($item) {
        return $this->column_localized_date($item['last_updated']);
    }

    function column_status($item) {
        $statusText = $moreInfo = $cssClass = null;
        $secondStatusText = $secondCssClass = null;

        $isWadaVersionIssue = array_key_exists('wadaVersionIssue', $item) && $item['wadaVersionIssue'];
        $isPhpVersionIssue = array_key_exists('phpVersionIssue', $item) && $item['phpVersionIssue'];
        $isWpVersionIssue = array_key_exists('wpVersionIssue', $item) && $item['wpVersionIssue'];

        if($isWadaVersionIssue){
            $statusText = __('WP Admin Audit version incompatible', 'wp-admin-audit');
            $cssClass = 'wada-orange-highlight bigger-highlight';
            $moreInfo = sprintf(__('Plugin requires WP Admin Audit version %s', 'wp-admin-audit'), $item['requires_wada']);
        }elseif($isPhpVersionIssue){
            $statusText = __('PHP version incompatible', 'wp-admin-audit');
            $cssClass = 'wada-orange-highlight bigger-highlight';
            $moreInfo = sprintf(__('Plugin requires PHP version %s', 'wp-admin-audit'), $item['requires_php']);
        }elseif($isWpVersionIssue){
            $statusText = __('WordPress version incompatible', 'wp-admin-audit');
            $moreInfo = sprintf(__('Plugin requires WordPress version %s', 'wp-admin-audit'), $item['requires_wp']);
            $cssClass = 'wada-orange-highlight bigger-highlight';
        }elseif($item['isNew'] || $item['isUpdate']){

            if($item['isNew']){
                $statusText = __('Extension available', 'wp-admin-audit');
                $cssClass = 'wada-green-highlight bigger-highlight';
                $moreInfo = sprintf(__('Our extension %s brings you sensors for the third-party plugin %s', 'wp-admin-audit'), $item['name'], $item['plugin_folder']);
            }elseif($item['isUpdate']){
                $statusText = __('Update available', 'wp-admin-audit');
                $cssClass = 'wada-green-highlight bigger-highlight';
                $moreInfo = sprintf(__('Update to version %s', 'wp-admin-audit'), $item['current_release_number']);
            }

            if($item['isTargetInstalled'] && !$item['isTargetActive']){
                $secondStatusText = __('Third-party plugin not active', 'wp-admin-audit');
                $secondCssClass = 'wada-orange-highlight bigger-highlight';
                $moreInfo = sprintf(__('The third-party plugin %s is inactive', 'wp-admin-audit'), $item['plugin_folder']);
            }elseif(!$item['isTargetInstalled']){
                $secondStatusText = __('Third-party plugin no longer installed', 'wp-admin-audit');
                $secondCssClass = 'wada-orange-highlight bigger-highlight';
                $moreInfo = sprintf(__('The third-party plugin %s was uninstalled', 'wp-admin-audit'), $item['plugin_folder']);
            }
        }elseif($item['extensionInstalled']){
            $statusText = __('Latest version installed', 'wp-admin-audit');
            $cssClass = 'wada-green-highlight bigger-highlight';
        }
        if($statusText){
            $html = '<span class="'.$cssClass.'">' . esc_html($statusText) . '</span>';
            if($secondStatusText){
                $html .= '<br/><span class="'.$secondCssClass.'">' . esc_html($secondStatusText) . '</span>';
            }
            if($moreInfo){
                $html .= '<span class="hTip" title="'.esc_attr($moreInfo).'"><span class="dashicons dashicons-info"></span></span>';
            }
        }else{
            $html = '&nbsp;';
        }
        return $html;
    }

    protected function extButtonHtml($actionStr, $psid, $isPrimary, $label){
        $nonce = wp_create_nonce('wada_extension_action');
        $classStr = 'wada-ui-button button '.($isPrimary ? 'button-primary' : 'button-secondary');
        $link = admin_url(sprintf('admin.php?page=wp-admin-audit-settings&subpage=extension&action=%s&psid=%s&_wpnonce=%s', $actionStr, $psid, $nonce));
        return '<a href="'.$link.'" class="'.esc_attr($classStr).'">'.esc_html($label).'</a>';
    }

    function column_actions($item) {
        $buttons = array();
        $primaryTaken = false;

        $isWadaVersionIssue = array_key_exists('wadaVersionIssue', $item) && $item['wadaVersionIssue'];
        $isPhpVersionIssue = array_key_exists('phpVersionIssue', $item) && $item['phpVersionIssue'];
        $isWpVersionIssue = array_key_exists('wpVersionIssue', $item) && $item['wpVersionIssue'];

        if($isWadaVersionIssue || $isPhpVersionIssue || $isWpVersionIssue){
            $buttons = array(); // no action
        }elseif($item['isUpdate']){
            if($item['isTargetInstalled'] && $item['isTargetActive']) {
                $primaryTaken = true;
                $buttons[] = $this->extButtonHtml('update', $item['psid'], true, __('Update', 'wp-admin-audit'));
            }
        }elseif($item['isNew']){
            if($item['isTargetInstalled'] && $item['isTargetActive']) {
                $primaryTaken = true;
                $buttons[] = $this->extButtonHtml('install', $item['psid'], true, __('Install', 'wp-admin-audit'));
            }
        }
        if($item['extensionInstalled']){
            if($item['extensionActive']){
                $buttons[] = $this->extButtonHtml('deactivate', $item['psid'], !$primaryTaken, __('Deactivate', 'wp-admin-audit'));
            }else {
                $buttons[] = $this->extButtonHtml('activate', $item['psid'], !$primaryTaken, __('Activate', 'wp-admin-audit'));
                $buttons[] = $this->extButtonHtml('uninstall', $item['psid'], false, __('Uninstall', 'wp-admin-audit'));
            }
        }
        if(count($buttons) > 0){
            $html = '<ul class="wada-vertical-list"><li>'.implode('</li><li>', $buttons).'</li></ul>';
        }else{
            $html = '&nbsp;';
        }

        return $html;
    }

    public function getDefaultOrder(){ // override parent to sort by two columns by default
        return array('name', 'ASC');
    }

    protected function areFiltersActive(){
        $extStatus = $this->getExtensionStatusFilterFromRequest();
        if($extStatus !== '' && $extStatus !== 'all'){
            return true;
        }
        return false;
    }

    protected function getExtensionStatusFilterFromRequest($default = 'all'){
        $extStatus = $default;
        WADA_Log::debug('getExtensionStatusFilterFromRequest _GET: '.print_r($_GET, true));
        if(array_key_exists('extstatus', $_GET)){
            if(trim(strval($_GET['extstatus'])) === ''){
                return $default;
            }
            $extensionStatusOpts = array_map(function($o) { return $o->name; }, $this->sections);
            WADA_Log::debug('getExtensionStatusFilterFromRequest opts: '.print_r($extensionStatusOpts, true));
            if(in_array($_GET['extstatus'], $extensionStatusOpts)){
                $extStatus = $_GET['extstatus'];
            }
        }
        WADA_Log::debug('getExtensionStatusFilterFromRequest extStatus: '.$extStatus);
        return $extStatus;
    }



    protected function performAdditionalItemPreparation(){
        foreach($this->items AS $key => $item){
            $this->items[$key]['extensionActive'] = WADA_PluginUtils::isPluginActive($item['wada_plugin_path']);
            $targetSlug = array_key_exists('servedForSlug', $item) ? $item['servedForSlug'] : $item['plugin_folder'];
            $this->items[$key]['isTargetInstalled'] = WADA_PluginUtils::isPluginInstalled($targetSlug);
            $this->items[$key]['isTargetActive'] = WADA_PluginUtils::isPluginActive($targetSlug);
        }
        WADA_Log::debug('Extensions->performAdditionalItemPreparation result: '.print_r($this->items, true));
        return $this->items;
    }

    public static function filterExtensionObjArray(
            $extensions,  $searchTerm,
            $attributesToSearchIn = array('name', 'wada_plugin_path', 'plugin_folder', 'description')){
        if(!$searchTerm){
            return $extensions;
        }
        return array_filter($extensions, function($extension) use ($searchTerm, $attributesToSearchIn) {
            $found = false;
            foreach($attributesToSearchIn AS $attribute){
                if(property_exists($extension, $attribute)){
                    if(strpos($extension->$attribute, $searchTerm) !== false){
                        $found = true;
                        break;
                    }
                }
            }
            return $found;
        });
    }

    public static function filterExtensionRepo($extensionRepo, $searchTerm, $sections){
        if(!$searchTerm){
            return $extensionRepo;
        }
        foreach($sections AS $key => $section){
            if(property_exists($extensionRepo, $section->name)){
                $sectionName = $section->name;
                $extensionRepo->$sectionName = self::filterExtensionObjArray($extensionRepo->$sectionName, $searchTerm);
            }
        }
        return $extensionRepo;
    }

    protected function loadItems($searchTerm = null){
        WADA_Log::debug('Extensions->loadItems');
        $sumBackend = new WADA_BackendSum();
        $forceReload = false;
        if(array_key_exists('fLoad', $_REQUEST)){ // for dev purposes
            $forceReload = true;
        }
        $extensionRepo = $sumBackend->getExtensionRepository($forceReload);
        $extensionRepo = self::filterExtensionRepo($extensionRepo, $searchTerm, $this->sections);
        $extensionStatusFilter = $this->getExtensionStatusFilterFromRequest();

        WADA_Log::debug('Extensions->loadItems extensionRepo: '.print_r($extensionRepo, true));

        $this->lastError = null;
        if(!property_exists($extensionRepo, 'ready')
            && !property_exists($extensionRepo, 'issue')
            && !property_exists($extensionRepo, 'installed')
            && property_exists($extensionRepo, 'message')){
            if($extensionRepo->message === 'Invalid license key'){
                $key = WADA_Settings::getLicenseKey();
                if($key){
                    $this->lastError = __('License key invalid or not active for this domain', 'wp-admin-audit');
                }else{
                    $this->lastError = __('Please enter your license key in the WP Admin Audit settings.', 'wp-admin-audit');
                }
            }
        }

        $ready = (!property_exists($extensionRepo, 'ready') || is_null($extensionRepo->ready)) ? array() : $extensionRepo->ready;
        $issue = (!property_exists($extensionRepo, 'issue') || is_null($extensionRepo->issue)) ? array() : $extensionRepo->issue;
        $installed = (!property_exists($extensionRepo, 'installed') || is_null($extensionRepo->installed)) ? array() : $extensionRepo->installed;

        $res = array();
        if($extensionStatusFilter === 'ready'){
            WADA_Log::debug('Extensions->loadItems ready');
            $res = $ready;
        }elseif($extensionStatusFilter === 'issue'){
            $res = $issue;
            WADA_Log::debug('Extensions->loadItems issue');
        }elseif($extensionStatusFilter === 'installed'){
            $res = $installed;
            WADA_Log::debug('Extensions->loadItems installed');
        }else{
            WADA_Log::debug('Extensions->loadItems ALL');
            $res = array_merge($ready, $issue, $installed);
        }

        $total = 0;
        foreach($this->sections AS $key => $section){
            if(property_exists($extensionRepo, $section->name)){
                $sectionName = $section->name;
                $this->sections[$key]->count = count($extensionRepo->$sectionName);
            }else{
                $this->sections[$key]->count = 0;
            }
            $total += $this->sections[$key]->count;
        }

        $this->sections[0]->count = $total; // in index 0, the "ALL" section is expected to be

        $arrayRes = array();
        foreach($res AS $obj){
            $arrayRes[] = (array)$obj;
        }
        return $arrayRes; // other WP List table functions expect to have it as array of arrays
    }

    protected function getSubSections(){
        global $wpdb;

        if(array_key_exists('no-sections', $this->viewConfig)){
            return array(); // don't show sections if configured
        }

        $basePage = 'wp-admin-audit-settings&tab=tab-extensi';
        $extStatus = $this->getExtensionStatusFilterFromRequest();

        WADA_Log::debug('Extensions->getSubSections allItems: '.print_r($this->allItems, true));

        $sections = array();
        foreach($this->sections AS $section){
            $sObj = new stdClass();
            $sObj->page = $basePage;
            $sObj->class = $section->name;
            $sObj->param = 'extstatus';
            $sObj->paramValue = $section->name;
            if($section->name === 'all') {
                $sObj->default = true;
            }else{
                $sObj->default = false;
            }
            $sObj->current = ($extStatus == $sObj->paramValue);
            $sObj->title = $section->label;
            if(property_exists($section, 'count')){
                $sObj->count = $section->count;
            }

            $sections[] = $sObj;
        }

        return $sections;

    }

    protected function displayBeforeList()
    {
        echo '<input type="hidden" name="form-submitted" value="extensi" />';
        parent::displayBeforeList();
    }

    protected function displayAfterList(){
        echo '<div style="text-align: center;">';
        echo '<i>'.esc_html(__('Is your favourite plugin missing?', 'wp-admin-audit')).'</i>';
        echo ' <a href="'.admin_url('/admin.php?page=wp-admin-audit-info&tab=tab-supp&mode=extension').'">'.esc_html(__('Let us know!', 'wp-admin-audit')).'</a>';
        echo '</div>';
        parent::displayAfterList();
    }

    public function no_items() {
        if($this->lastError){
            echo '<span class="wada-error">'.esc_html($this->lastError).'</span>';
            return false;
        }
        if(!parent::no_items()){
            return false;
        }
        echo __('Do you think we should add that plugin?').' <a href="'.admin_url('/admin.php?page=wp-admin-audit-info&tab=tab-supp&mode=extension').'">'.esc_html(__('Let us know!', 'wp-admin-audit')).'</a>';
        return true;
    }

    function loadJavascriptActions(){ // override with extending functionality ?>
        <script type="text/javascript">
            function <?php $this->jsPrefix(); ?>getAdditionalQueryVariables(thisListRef){
                let fLoad = (thisListRef.__query(document.location.href, 'fLoad') || '');
                let data = {
                    extstatus: jQuery('#<?php $this->listFormId(); ?> .subsubsub a[aria-current="page"]').data('section-value')
                };
                if(fLoad){
                    data['fLoad'] = fLoad;
                }
                return data;
            }
            function <?php $this->jsPrefix(); ?>getAdditionalParametersFromUrl(thisListRef, query){
                let fLoad = thisListRef.__query( query, 'fLoad' ) || '';
                let data = {
                    extstatus: thisListRef.__query( query, 'extstatus' ) || ''
                };
                if(fLoad){
                    data['fLoad'] = fLoad;
                }
                return data;
            }
        </script>
        <?php
        parent::loadJavascriptActions(); // make sure base class also loads JS
    }

}
