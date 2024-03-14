<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_View_Users extends WADA_View_BaseList
{
    const VIEW_IDENTIFIER = 'wada-users';
    const LIST_FORM_ID = 'wada-user-list-form';
    const AJAX_ACTION = '_wada_ajax_users_list';
    const NONCE_ACTION = 'wada-ajax-users-list-nonce';
    const NONCE_NAME = '_wada_ajax_users_list_nonce';

    public function __construct($viewConfig = array()) {
        $this->parentHeadline = __('Audit', 'wp-admin-audit');
        $this->parentHeadlineLink = admin_url('admin.php?page=wp-admin-audit&subpage=audit');
        $this->viewHeadline =  __('Users', 'wp-admin-audit');
        $this->csvExport = array('width' => 300, 'height' => 100);
        parent::__construct( array(
            'singular' => __( 'User', 'wp-admin-audit' ), //singular name of the grouped records
            'plural'   => __( 'Users', 'wp-admin-audit' ), //plural name of the grouped records
            'ajax'     => true // does this table support ajax?
        ), $viewConfig );
        add_action('admin_footer', array($this, 'loadJavascriptActions'));
        WADA_ScriptUtils::loadSelect2();
    }

    function get_columns() {
        $colArray = array(
            'title'    => __( 'Username', 'wp-admin-audit' ),
            'display_name' => __( 'Name', 'wp-admin-audit' ),
            'user_email'    => __( 'Email address', 'wp-admin-audit' ),
            'roles' => __( 'Roles', 'wp-admin-audit' ),
            'last_seen' => __( 'Last seen', 'wp-admin-audit' ),
            'not_seen_since' => __( 'Not seen since', 'wp-admin-audit' ),
            'user_registered'  => __( 'Registered at', 'wp-admin-audit' ),
            'id_only'    => __( 'ID', 'wp-admin-audit' )
        );
        $searchTerm = $this->getSearchTerm();
        if($searchTerm){
            $colArray['search_res'] = __( 'Search result', 'wp-admin-audit' );
        }
        return $colArray;
    }

    public function get_sortable_columns() {
        return array(
            'title' => array('user_login', 'asc'),
            'display_name' => array('display_name', 'asc'),
            'user_email' => array('user_email', 'asc'),
            'last_seen' => array('last_seen', 'asc'),
            'not_seen_since' => array('not_seen_since', 'asc'),
            'user_registered' => array('user_registered', 'asc'),
            'id_only' => array('id', 'desc'),
        );
    }

    function column_title($item) {
        $titleUrl = admin_url(sprintf(
            'admin.php?page=wp-admin-audit-users&subpage=user-details&amp;sid=%s',
            absint( $item['id'] )
        ));
        return sprintf(
            '<a href="'.$titleUrl.'"><strong>%s</strong></a>',
            $this->formatSearchResult(esc_html($item['user_login']), $this->getSearchTerm())
        );
    }

    function column_roles($item){
        $roles = $item['roles'];
        if($roles){
            $roles = esc_html($roles);
        }else{
            $roles = '<span class="wada-greyed-out wada-italic">'.esc_html(__('No role', 'wp-admin-audit')).'</span>';
        }
        return $roles;
    }

    function column_last_seen($item){
        $html = '';
        $searchForActive = ($this->getActiveInactiveSelectionFromRequest() == 'active');
        if($searchForActive){
            $html .= '<span class="wada-green-highlight bigger-highlight">';
        }
        $html .= $this->column_localized_timestamp($item['last_seen']);
        if($searchForActive){
            $html .= '</span>';
        }
        return $html;
    }

    function column_not_seen_since($item){
        $html = '';
        $searchForInactive = ($this->getActiveInactiveSelectionFromRequest() == 'inactive');
        if($searchForInactive){
            $html .= '<span class="wada-orange-highlight bigger-highlight">';
        }
        $html .= $this->column_localized_timestamp($item['not_seen_since']);
        if($searchForInactive){
            $html .= '</span>';
        }
        return $html;
    }

    protected function getWpRoles(){
        global $wp_roles;
        if ( ! isset( $wp_roles ) ) {
            $wp_roles = new WP_Roles();
        }
        return $wp_roles;
    }

    protected function getRoleOptions(){
        $options = array();
        $wpRoles = $this->getWpRoles()->role_names;
        array_unshift($wpRoles, __('All roles'));
        $wpRoles['none'] = __('No role', 'wp-admin-audit');
        return $wpRoles;
    }

    protected function getRoleFilterFromRequest($default = null){
        $role = $default;
        $allowedOptions = array_keys($this->getWpRoles()->role_names);
        if (array_key_exists('role', $_GET)) {
            $reqValue = sanitize_text_field($_GET['role']);
            if (in_array($reqValue, $allowedOptions) || $reqValue === 'none') {
                $role = $reqValue;
            }
        }
        return $role;
    }

    protected function getStatusTimeframeFilterOptions(){
        return array(
                '7d' => __('one week', 'wp-admin-audit'),
                '14d' => sprintf(__('%d days', 'wp-admin-audit'), 14),
                '30d' => sprintf(__('%d days', 'wp-admin-audit'), 30),
                '90d' => sprintf(__('%d days', 'wp-admin-audit'), 90),
                '365d' => __('one year', 'wp-admin-audit')
        );
    }

    protected function getActiveTimeFrameFilterOptions(){
        $opts = $this->getStatusTimeframeFilterOptions();
        foreach($opts AS $key => $opt){
            $opts[$key] = sprintf(__('within %s', 'wp-admin-audit'), $opt);
        }
        return $opts;
    }

    protected function getInactiveTimeFrameFilterOptions(){
        $opts = $this->getStatusTimeframeFilterOptions();
        foreach($opts AS $key => $opt){
            $opts[$key] = sprintf(__('since %s', 'wp-admin-audit'), $opt);
        }
        return $opts;
    }

    protected function getActiveInactiveSelectionFromRequest($default = null){
        $allowedOptions = array_keys($this->getActiveInactiveOptions());
        return $this->getStringSelectionFromRequest('ainactive', $default, $allowedOptions);
    }

    protected function getTimeFrameSelectionFromRequest($default = null){
        $allowedOptions = array_keys($this->getStatusTimeframeFilterOptions());
        return $this->getStringSelectionFromRequest('timef', $default, $allowedOptions);
    }

    protected function getActiveInactiveOptions(){
        return array(
            'all' => __('All', 'wp-admin-audit'),
            'active' => __('Only active', 'wp-admin-audit'),
            'inactive' => __('Only inactive', 'wp-admin-audit')
        );
    }

    protected function getFilterControls($filterControls = array()){
        $roleFilter = new stdClass();
        $roleFilter->type = 'select';
        $roleFilter->value = $this->getRoleFilterFromRequest();
        $roleFilter->field = 'role';
        $roleFilter->label = null; // no need for label
        $roleFilter->selectOptions = $this->getRoleOptions();
        $filterControls[] = $roleFilter;

        $timeFrameActiveSelect = WADA_HtmlUtils::selectField('timef_active',
            null,
            $this->getActiveInactiveSelectionFromRequest(),
            $this->getActiveTimeFrameFilterOptions(), #
            array(),
            false,
            array(
                    'return_as_str' => true,
                    'display_none' => true,
                    'input_class' => 'wada-grid-timeframe-selector'
            )
        );

        $timeFrameInactiveSelect = WADA_HtmlUtils::selectField('timef_inactive',
            null,
            $this->getActiveInactiveSelectionFromRequest(),
            $this->getInactiveTimeFrameFilterOptions(), #
            array(),
            false,
            array(
                'return_as_str' => true,
                'display_none' => true,
                'input_class' => 'wada-grid-timeframe-selector'
            )
        );

        $gridPickerStoredHtml ='
            <input name="ainactive" id="ainactive" type="hidden" value=""/>
            <input name="timef" id="timef" type="text" hidden=""/>				
            <ul class="wada-grid-picker-widget">
                <li class="wada-grid-picker-item wada-grid-picker-item-selected" data-wada-grid-picker-value="all">
                    <div title="All" draggable="false" class="wada-grid-picker-item-content">
                        <span class="wada-grid-picker-item-label wada-grid-picker-item-label-only-text">'.__('All users', 'wp-admin-audit').'</span>	
                    </div></li><li class="wada-grid-picker-item" data-wada-grid-picker-value="active">
                    <div draggable="false" class="wada-grid-picker-item-content wada-grid-picker-noright-padding-onselect" title="'.esc_attr(__('Only active', 'wp-admin-audit')).'">						
                        <span class="wada-grid-picker-item-label">'.__('Active users', 'wp-admin-audit').'</span>							
                        '.$timeFrameActiveSelect.'
                    </div></li><li class="wada-grid-picker-item" data-wada-grid-picker-value="inactive">
                    <div draggable="false" class="wada-grid-picker-item-content wada-grid-picker-noright-padding-onselect" title="'.esc_attr(__('Only inactive', 'wp-admin-audit')).'">						
                        <span class="wada-grid-picker-item-label">'.__('Inactive users', 'wp-admin-audit').'</span>
                        '.$timeFrameInactiveSelect.'
                    </div></li>
            </ul>';

        $activeInactivePicker = new stdClass();
        $activeInactivePicker->type = 'raw-html';
        $activeInactivePicker->value = $gridPickerStoredHtml;
        $filterControls[] = $activeInactivePicker;

        return parent::getFilterControls($filterControls);
    }

    protected function displayAfterList(){

        /*  */

        parent::displayAfterList();
    }

    protected function performAdditionalItemPreparation(){
        $wpRoles = $this->getWpRoles()->get_names();
        foreach($this->items AS $key => $item){
            $user_meta = get_userdata($item['id']);
            $rolesNiceArray = array();
            foreach($user_meta->roles AS $role){
                if(array($role, $wpRoles)) {
                    $rolesNiceArray[] = $wpRoles[$role];
                }else{
                    $rolesNiceArray[] = $role; // no "translation" possible
                }
            }
            $this->items[$key]['roles'] = implode(', ', $rolesNiceArray);
        }
        return $this->items;
    }

    protected function areFiltersActive(){
        $roleFilter = $this->getRoleFilterFromRequest();
        if($roleFilter){
            return true;
        }
        $activeInactiveFilter = $this->getActiveInactiveSelectionFromRequest();
        if($activeInactiveFilter !== 'all'){
            return true;
        }
        return false;
    }

    protected function getUsersInWpRole($role){
        return get_users(
            array(
                'fields' => 'ID',
                'role__in' => array($role)
            )
        );
    }

    protected function getItemsQuery($searchTerm = null){
        global $wpdb;
        $roleFilter = $this->getRoleFilterFromRequest();

        $activeInactiveFilter = $this->getActiveInactiveSelectionFromRequest();
        $timeFrameFilter = $this->getTimeFrameSelectionFromRequest();

        if((($activeInactiveFilter === 'active') || ($activeInactiveFilter === 'inactive')) && $timeFrameFilter){
            $currUtc = WADA_DateUtils::getUTCforMySQLTimestamp();
            WADA_Log::debug('Users->getItemsQuery '.$activeInactiveFilter.' '.$timeFrameFilter);
            if($activeInactiveFilter === 'active'){
                $timeFrameFilter = str_replace('d', ' DAY', $timeFrameFilter);
                $timeFrameFilter = "wada_usr.last_seen > DATE_SUB('".$currUtc."', INTERVAL ".$timeFrameFilter.")";
            }elseif($activeInactiveFilter === 'inactive'){
                $timeFrameFilter = str_replace('d', ' DAY', $timeFrameFilter);
                $timeFrameFilter = "wada_usr.not_seen_since < DATE_SUB('".$currUtc."', INTERVAL ".$timeFrameFilter.")";
            }
        }

        if($roleFilter){
            if($roleFilter === 'none'){
                $userIdsOfRole = wp_get_users_with_no_role();
            }else {
                $userIdsOfRole = $this->getUsersInWpRole($roleFilter);
            }
            if(count($userIdsOfRole)) {
                $roleFilter = "wpusr.ID IN (" . implode(',', $userIdsOfRole) . ")";
            }else{
                $roleFilter = "wpusr.ID < 0"; // impossible criteria, because no matches!
            }
        }

        $sql= "SELECT wpusr.ID as id, wpusr.user_login, wpusr.user_email, wpusr.display_name, wpusr.user_registered,"
            ." wada_usr.not_seen_since, wada_usr.last_seen, wada_usr.last_login, wada_usr.last_pw_change, wada_usr.last_pw_change_reminder, wada_usr.tracked_since"
            .($searchTerm ? ", ur_search_res.search_res as search_res " : ", NULL AS search_res ")
            ." FROM ".$wpdb->prefix . "users wpusr "
            ." LEFT JOIN (SELECT GREATEST(COALESCE(last_seen, tracked_since), COALESCE(last_login, tracked_since), COALESCE(last_pw_change, tracked_since)) AS not_seen_since, wada_users.* FROM ".WADA_Database::tbl_users() ." wada_users) wada_usr ON (wpusr.ID = wada_usr.user_id)";

        if($searchTerm) {
            $sql .=
                "LEFT JOIN ("
                . "SELECT user_id, GROUP_CONCAT(DISTINCT search_res ORDER BY search_res SEPARATOR ', ') AS search_res "
                . "FROM ( "
                . "	SELECT ID as user_id, user_url as search_res FROM ".$wpdb->prefix . "users ur1 WHERE user_url LIKE '%" . $searchTerm . "%' "
                . ") ur_res  "
                . "GROUP BY user_id "
                . ") ur_search_res ON (wpusr.ID = ur_search_res.user_id)";
            $fieldsToSearchIn = array('wpusr.user_login', 'wpusr.user_email', 'wpusr.display_name');
            $sep = " LIKE '%".$searchTerm."%') OR (";
            $whereCond = implode($sep, $fieldsToSearchIn);
            $whereCond .= " LIKE '%".$searchTerm."%') )";
            if($roleFilter){
                $whereCond .= ' AND '.$roleFilter;
            }
            if($timeFrameFilter){
                $whereCond .= ' AND '.$timeFrameFilter;
            }
            $sql .= ' WHERE ( ('.$whereCond;
        }else{
            $whereCond = array();
            if($roleFilter){
                $whereCond[] = $roleFilter;
            }
            if($timeFrameFilter){
                $whereCond[] = $timeFrameFilter;
            }
            if(count($whereCond) > 0) {
                $sql .= (' WHERE ' . implode(' AND ', $whereCond));
            }
        }
        return $sql;
    }

    function loadJavascriptActions(){ // override with extending functionality ?>
        <script type="text/javascript">
            function <?php $this->jsPrefix(); ?>getAdditionalQueryVariables(thisListRef){
                let data = {
                    role: jQuery('#<?php $this->listFormId(); ?> select[name=role]').val() || '',
                    ainactive: jQuery('#ainactive').val() || '',
                    timef: jQuery('#timef').val() || ''
                };
                return data;
            }
            function <?php $this->jsPrefix(); ?>getAdditionalParametersFromUrl(thisListRef, query){
                let data = {
                    role: thisListRef.__query( query, 'role' ) || '',
                    ainactive: thisListRef.__query( query, 'ainactive' ) || '',
                    timef: thisListRef.__query( query, 'timef' ) || ''
                };
                return data;
            }
            function <?php $this->jsPrefix(); ?>registerAdditionalListEvents(thisListRef){
                jQuery('#role').on('change', {thisList: thisListRef}, function (e) {
                    let data = e.data.thisList.getQueryVariablesFromInputs();
                    e.data.thisList.update(data);
                });
            }
            function <?php $this->jsPrefix(); ?>unregisterAdditionalListEvents(thisListRef){
                jQuery('#role').off('change');
            }
            function <?php $this->jsPrefix(); ?>startCsvExport(thisList, csvSep, clickedCSVButton){
                let actionStr = '_wada_ajax_users_csv_export';
                let data = thisList.getQueryVariablesFromInputs();
                jQuery.extend(data, {
                    csvsep: csvSep
                });
                doCSVAjaxRequest(actionStr, data, '<?php echo esc_js(strtolower($this->_args['plural'])); ?>');
            }
            function updateTimeFrame(selectedItem){
                let chosenItem = selectedItem.data('wada-grid-picker-value');
                let timeFrame;
                if(chosenItem == 'all'){
                    timeFrame = 0;
                }else{
                    timeFrame = jQuery('#timef_'+chosenItem).val();
                }
                console.log(timeFrame);
                jQuery('#timef').val(timeFrame);

                let data = lists['<?php $this->listFormId(); ?>'].getQueryVariablesFromInputs();
                lists['<?php $this->listFormId(); ?>'].update(data);
            }
            function activeInactiveFilterChange(selectedItem){
                let chosenItem = selectedItem.data('wada-grid-picker-value');
                let priorChosen = jQuery('#<?php $this->listFormId(); ?> .wada-grid-picker-item-selected').data('wada-grid-picker-value');
                if(chosenItem != priorChosen){
                    console.log(priorChosen + " -> " + chosenItem);
                    jQuery('#ainactive').val(chosenItem);
                    jQuery('#<?php $this->listFormId(); ?> .wada-grid-picker-item-selected > div > select').hide();
                    jQuery('#<?php $this->listFormId(); ?> .wada-grid-picker-item-selected').removeClass('wada-grid-picker-item-selected');
                    selectedItem.addClass('wada-grid-picker-item-selected');
                    jQuery('#<?php $this->listFormId(); ?> .wada-grid-picker-item-selected > div > select').show();

                    updateTimeFrame(selectedItem);
                }
            }
            jQuery(document).ready(function() {

                jQuery('#<?php $this->listFormId(); ?> .wada-grid-picker-item').on('click', function(e){
                    activeInactiveFilterChange(jQuery(this));
                });

                jQuery('#<?php $this->listFormId(); ?> .wada-grid-timeframe-selector').on('change', function(e){
                    let activeInactive = jQuery(this).parent().parent();
                    updateTimeFrame(activeInactive);
                });

                // initial selection based on url parameters
                let activeInactive = '<?php echo esc_js($this->getActiveInactiveSelectionFromRequest('all')); ?>';
                if(activeInactive == 'active' || activeInactive == 'inactive'){
                    let timeFrame = '<?php echo esc_js($this->getTimeFrameSelectionFromRequest('7d')); ?>';
                    jQuery('[data-wada-grid-picker-value="'+activeInactive+'"]').trigger('click');
                    jQuery('#timef_'+activeInactive).val(timeFrame);
                    console.log(activeInactive);
                    console.log(timeFrame);
                }
            });
        </script>
        <?php
        parent::loadJavascriptActions(); // make sure base class also loads JS
    }

}