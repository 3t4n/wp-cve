<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_View_Sensors extends WADA_View_BaseList
{
    const VIEW_IDENTIFIER = 'wada-sensors';
    const LIST_FORM_ID = 'wada-sensor-list-form';
    const AJAX_ACTION = '_wada_ajax_sensors_list';
    const NONCE_ACTION = 'wada-ajax-sensors-list-nonce';
    const NONCE_NAME = '_wada_ajax_sensors_list_nonce';

    public function __construct($viewConfig = array()) {
        $this->viewHeadline =  __('Sensors', 'wp-admin-audit');
        parent::__construct( array(
            'singular' => __( 'Sensor', 'wp-admin-audit' ), //singular name of the grouped records
            'plural'   => __( 'Sensors', 'wp-admin-audit' ), //plural name of the grouped records
            'ajax'     => true // does this table support ajax?
        ), $viewConfig);
        add_action('admin_footer', array($this, 'loadJavascriptActions'));
    }

    function get_columns() {
        $colArray = array(
            'title'    => __( 'Name', 'wp-admin-audit' ),
            'active'    => __( 'Active', 'wp-admin-audit' ),
            'severity'    => __( 'Severity', 'wp-admin-audit' ),
            'event_group'    => __( 'Event Group', 'wp-admin-audit' ),
            'event_category'    => __( 'Event Category', 'wp-admin-audit' ),
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
            'title' => array('name', 'asc'),
            'severity' => array('severity', 'asc'),
            'active' => array('active', 'asc'),
            'event_group' => array('event_group', 'asc'),
            'event_category' => array('event_category', 'asc'),
            'id_only' => array('id', 'asc')
        );
    }

    function column_title($item) {
        $titleUrl = admin_url(sprintf(
                'admin.php?page=wp-admin-audit-settings&tab=tab-sensors&subpage=sensor&amp;sid=%s',
                absint( $item['id'] )
        ));
        return sprintf(
                '<a href="'.$titleUrl.'"><strong>%s</strong></a>',
                $this->formatSearchResult(esc_html($item['name']), $this->getSearchTerm())
        );
    }

    function column_active($item){
        $disabled = $title = '';
        if($item['extension_id'] && $item['extension_active'] == 0 && $item['active'] == 0){
            $disabled = ' disabled="disabled" ';
            $title = esc_attr__(sprintf(__('The extension %s is no longer active, therefore the sensor cannot be activated', 'wp-admin-audit'), $item['extension_name']));
        }
        return '<input type="checkbox" class="wada-ui-toggle sensor-status-toggle" '
        .'id="active'.absint($item['id']).'" name="active'.absint($item['id']).'" value="1" '
        .$disabled.($item['active'] == 1 ? 'checked' : '').' title="'.$title.'">';
    }

    function column_severity($item){
        return esc_html($item['severity'] . ' (' . $item['severity_text'].')');
    }

    function column_event_category($item){
        $eventCategories = WADA_Model_Sensor::getEventCategories();
        $eventCategoryStr = (array_key_exists($item['event_category'], $eventCategories) ? $eventCategories[$item['event_category']] : $item['event_category']);
        return $this->formatSearchResult(esc_html($eventCategoryStr), $this->getSearchTerm());
    }

    function column_event_group($item){
        return $this->formatSearchResult(esc_html($item['event_group_name']), $this->getSearchTerm());
    }

    function toggleActionAjaxResponse(){
        WADA_Log::debug(static::VIEW_IDENTIFIER.'->toggleActionAjaxResponse');
        check_ajax_referer(static::NONCE_ACTION, static::NONCE_NAME);

        $id = isset( $_REQUEST['id'] ) ? absint( $_REQUEST['id'] ) : 0;
        $active = !isset($_REQUEST['active']) || $_REQUEST['active'] == 'true';
        if($id){
            $res = WADA_Model_Sensor::setActiveStatus($id, $active);
            $response = array('success' => $res);
        }else{
            $response = array('success' => false, 'error' => 'No ID found');
        }

        WADA_Log::debug(static::VIEW_IDENTIFIER.'->toggleActionAjaxResponse: '.print_r($response, true));

        die( json_encode( $response ) );
    }

    protected function areFiltersActive(){
        $active = $this->getActiveFilterFromRequest();
        if($active >= 0){
            return true;
        }
        $eventGroup = $this->getEventGroupFilterFromRequest();
        if($eventGroup){
            return true;
        }
        return false;
    }

    protected function getActiveFilterFromRequest($default = -1){
        $active = $default;
        if(array_key_exists('active', $_GET)){
            if(trim(strval($_GET['active'])) === ''){
                return $default;
            }
            $active = intval($_GET['active']);
            if($active < -1){
                $active = -1;
            }
            if($active > 1){
                $active = 1;
            }
        }
        return $active;
    }

    protected function getEventGroupFilterFromRequest(){
        return $this->getStringSelectionFromRequest('event_group', null, array_keys($this->getEventGroupOptions()));
    }

    protected function performAdditionalItemPreparation(){
        $severityLevels = WADA_Model_Sensor::getSeverityLevels();
        if(is_array($this->items)) {
            foreach ($this->items as $key => $item) {
                $this->items[$key]['severity_text'] = $severityLevels[$item['severity']];
                $this->items[$key]['event_group_name'] = WADA_Model_Sensor::getEventGroupName($this->items[$key]['event_group']);
            }
        }
        return $this->items;
    }

    protected function getItemsQuery($searchTerm = null){
        $active = $this->getActiveFilterFromRequest();
        $eventGroup = $this->getEventGroupFilterFromRequest();
        $sql = "SELECT sen.id, sen.name, sen.description, "
            ."sen.severity, sen.active, sen.event_group, sen.event_category, "
            ."sen.extension_id, ext.active as extension_active, ext.name as extension_name, "
            ."ext.plugin_folder as extension_plugin_folder, ext.ext_api_version as extension_api_version"
            .($searchTerm ? ", sen_search_res.search_res as search_res " : ", NULL AS search_res ")
            ."FROM ".WADA_Database::tbl_sensors() . " sen "
            ."LEFT JOIN ".WADA_Database::tbl_extensions() . " ext ON (sen.extension_id = ext.id) ";
        if($searchTerm) {
            $sql .=
                "LEFT JOIN ("
                . "SELECT id, description AS search_res "
                . "FROM " . WADA_Database::tbl_sensors() . " ei1 WHERE description LIKE '%" . $searchTerm . "%' "
                . ") sen_search_res ON (sen.id = sen_search_res.id)";
            $fieldsToSearchIn = array('sen.name', 'sen.event_group', 'sen.event_category');
            $sep = " LIKE '%".$searchTerm."%') OR (";
            $whereCond = implode($sep, $fieldsToSearchIn);
            $whereCond .= " LIKE '%".$searchTerm."%') )";
            if($active >= 0){
                $whereCond .= ' AND (sen.active = ' . intval($active) .')';
            }
            if($eventGroup){
                $whereCond .= ' AND (sen.event_group = \''.$eventGroup.'\')';
            }
            $sql .= ' WHERE ( ('.$whereCond;
        }else{
            $whereCond = array();
            if($active >= 0){
                $whereCond[] = 'sen.active = ' . intval($active);
            }
            if($eventGroup){
                $whereCond[] = 'sen.event_group = \''.$eventGroup.'\'';
            }
            if(count($whereCond) > 0) {
                $sql .= (' WHERE ' . implode(' AND ', $whereCond));
            }
        }
        return $sql;
    }

    protected function getSubSections(){
        global $wpdb;
        $query = 'SELECT active AS status, count(*) AS sensor_cr FROM '.WADA_Database::tbl_sensors().' GROUP BY active';
        $sensorCounts = $wpdb->get_results($query);

        if(count($sensorCounts) == 1){
            $sensorCounts[] = (object) (array('status' => ($sensorCounts[0]->status == 1 ? 0 : 1), 'sensor_cr' => 0));
        }

        $nrEnabled = $sensorCounts[1]->sensor_cr;
        $nrDisabled = $sensorCounts[0]->sensor_cr;
        if($sensorCounts[0]->status == 1){
            $nrEnabled = $sensorCounts[0]->sensor_cr;
            $nrDisabled = $sensorCounts[1]->sensor_cr;
        }

        $basePage = 'wp-admin-audit-settings&tab=tab-sensors';
        $active = $this->getActiveFilterFromRequest();

        return $this->getAllEnabledDisabledSections($basePage, $active, $nrEnabled, $nrDisabled);
    }

    protected function getEventGroupOptions(){
        $eventGroupNames = WADA_Model_Sensor::getEventGroupNames();
        $eventGroupOptions = array();
        $eventGroupOptions[''] = __('All event groups', 'wp-admin-audit');
        $eventGroupOptions = array_merge($eventGroupOptions, $eventGroupNames);

        global $wpdb;
        $sql = 'SELECT DISTINCT event_group '
                . 'FROM '.WADA_Database::tbl_sensors().' sen '
                . 'WHERE event_group <> \'PSEUDO\' AND event_group NOT IN (\''.implode("','", array_map('addslashes', array_keys($eventGroupOptions))).'\') '
                . 'ORDER BY event_group';
        $pluginEventGroups = $wpdb->get_results($sql);
        if($pluginEventGroups && count($pluginEventGroups)){
            $pluginEventGroups = array_column($pluginEventGroups, 'event_group');
            foreach($pluginEventGroups AS $plgEventGroup){
                $eventGroupOptions[sanitize_text_field($plgEventGroup)] = sanitize_text_field($plgEventGroup);
            }
        }

        asort($eventGroupOptions); // sort ascending by string value (not key)

        return $eventGroupOptions;
    }

    protected function getFilterControls($filterControls = array()){

        $eventGroupFilter = new stdClass();
        $eventGroupFilter->type = 'select';
        $eventGroupFilter->value = $this->getEventGroupFilterFromRequest();
        $eventGroupFilter->field = 'event_group';
        $eventGroupFilter->label = null; // no need for label
        $eventGroupFilter->selectOptions = $this->getEventGroupOptions();

        $filterControls[] = $eventGroupFilter;

        return parent::getFilterControls($filterControls);
    }

    protected function displayAfterList(){
        echo '<div style="text-align: center;">';
        echo '<i>'.esc_html(__('Is an event critical to you missing?', 'wp-admin-audit')).'</i>';
        echo ' <a href="'.admin_url('/admin.php?page=wp-admin-audit-info&tab=tab-supp&mode=sensor').'">'.esc_html(__('Let us know!', 'wp-admin-audit')).'</a>';
        echo '</div>';
        parent::displayAfterList();
    }

    public function no_items() {
        if(!parent::no_items()){
            return false;
        }
        echo _e('Do you think we should add that sensor?').' <a href="'.admin_url('/admin.php?page=wp-admin-audit-info&tab=tab-supp&mode=sensor').'">'.esc_html(__('Let us know!', 'wp-admin-audit')).'</a>';
        return true;
    }

    function loadJavascriptActions(){ // override with extending functionality ?>
        <script type="text/javascript">
            function <?php $this->jsPrefix(); ?>getAdditionalQueryVariables(thisListRef){
                let data = {
                    event_group: jQuery('#<?php $this->listFormId(); ?> select[name=event_group]').val() || '',
                    active: jQuery('#<?php $this->listFormId(); ?> .subsubsub a[aria-current="page"]').data('section-value')
                };
                return data;
            }
            function <?php $this->jsPrefix(); ?>getAdditionalParametersFromUrl(thisListRef, query){
                let data = {
                    event_group: thisListRef.__query( query, 'event_group' ) || '',
                    active: thisListRef.__query( query, 'active' ) || '',
                };
                return data;
            }
            function <?php $this->jsPrefix(); ?>registerAdditionalListEvents(thisListRef){
                jQuery('#event_group').on('change', {thisList: thisListRef}, function (e) {
                    let data = e.data.thisList.getQueryVariablesFromInputs();
                    e.data.thisList.update(data);
                });

                jQuery('#<?php $this->listFormId(); ?> .sensor-status-toggle').on('change', function (e) {
                    let id = parseInt(this.name.substr(6)); // taking name and stripping 'active' prefix
                    let active = jQuery(this).prop('checked');
                    if(id > 0){
                        toggleSensorStatus({'id':id, 'active':active});
                    }
                });
            }
            function <?php $this->jsPrefix(); ?>unregisterAdditionalListEvents(thisListRef){
                jQuery('#event_group').off('change');
                jQuery('#<?php $this->listFormId(); ?> .sensor-status-toggle').off('change');
            }
            function toggleSensorStatus(data){
                jQuery.ajax({
                    url: ajaxurl,
                    data: jQuery.extend({
                        <?php echo static::NONCE_NAME; ?>: jQuery('#<?php echo static::NONCE_NAME; ?>').val(),
                        action: '_wada_ajax_sensors_status_toggle'
                    }, data),
                    success: function (response) {
                        var response = jQuery.parseJSON(response);
                        if(response && response.success){
                            console.log(data);
                        }else{
                            jQuery('#active'+data.id).prop('checked', !data.active); // something went wrong saving, revert UI
                        }
                    }
                });
            }
        </script>
        <?php
        parent::loadJavascriptActions(); // make sure base class also loads JS
    }

}
