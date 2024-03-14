<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_View_Logins extends WADA_View_BaseList
{
    const VIEW_IDENTIFIER = 'wada-logins';
    const LIST_FORM_ID = 'wada-login-list-form';
    const AJAX_ACTION = '_wada_ajax_logins_list';
    const NONCE_ACTION = 'wada-ajax-logins-list-nonce';
    const NONCE_NAME = '_wada_ajax_logins_list_nonce';

    public function __construct($viewConfig = array()) {
        $this->parentHeadline = __('Audit', 'wp-admin-audit');
        $this->parentHeadlineLink = admin_url('admin.php?page=wp-admin-audit&subpage=audit');
        $this->viewHeadline =  __('Login attempts', 'wp-admin-audit');
        $this->csvExport = array('width' => 300, 'height' => 200);
        parent::__construct( array(
            'singular' => __( 'Attempt', 'wp-admin-audit' ), //singular name of the grouped records
            'plural'   => __( 'Attempts', 'wp-admin-audit' ), //plural name of the grouped records
            'ajax'     => true // does this table support ajax?
        ), $viewConfig );
        add_action('admin_footer', array($this, 'loadJavascriptActions'));
    }

    function get_columns() {
        $colArray = array(
            'ip_address'    => __( 'IP address', 'wp-admin-audit' ),
            'total_attempts' => __( '#Attempts (total)', 'wp-admin-audit' ),
            'attempts_successful_percent' => __('Successful attempts (%)', 'wp-admin-audit'),
            'attempts_w_exist_login_percent' => __('Using existing username (%)', 'wp-admin-audit'),
            'attempts_7d'    => __( '#Attempts (7 days)', 'wp-admin-audit' ),
            'attempts_30d' => __( '#Attempts (30 days)', 'wp-admin-audit' ),
            'first_attempt' => __( 'First attempt', 'wp-admin-audit' ),
            'last_attempt' => __( 'Last attempt', 'wp-admin-audit' )
        );
        $searchTerm = $this->getSearchTerm();
        if($searchTerm){
            $colArray['search_res'] = __( 'Search result', 'wp-admin-audit' );
        }
        return $colArray;
    }

    public function get_sortable_columns() {
        return array(
            'total_attempts' => array('total_attempts', 'desc'),
            'attempts_successful_percent' => array('attempts_successful_percent', 'desc'),
            'attempts_w_exist_login_percent' => array('attempts_w_exist_login_percent', 'desc'),
            'ip_address' => array('ip_address', 'asc'),
            'attempts_7d' => array('attempts_7d', 'desc'),
            'attempts_30d' => array('attempts_30d', 'desc'),
            'first_attempt' => array('first_attempt', 'desc'),
            'last_attempt' => array('last_attempt', 'desc')
        );
    }

    public function getDefaultOrder(){ // override parent to sort by two columns by default
        return array('total_attempts', 'DESC');
    }

    function markGreyIfZero($val, $displayText=null){
        if(is_null($displayText)){
            $displayText = $val;
        }
        return (intval($val) == 0) ? '<span class="wada-greyed-out">' . $displayText . '</span>' : $displayText;
    }

    function markGreenIfTrue($val, $trueText=null, $falseText=null){
        if(is_null($trueText)){
            $trueText = __('Yes', 'wp-admin-audit');
        }
        if(is_null($falseText)){
            $falseText = __('No', 'wp-admin-audit');
        }
        return $val ? '<span class="wada-green">' . $trueText . '</span>' : $falseText;
    }

    function column_attempts_successful_percent($item){
        $quota = round($item['attempts_successful_percent'], 0);
        return sprintf(__('%.0f%%', 'wp-admin-audit'), $quota);
    }

    function column_attempts_w_exist_login_percent($item){
        $quota = round($item['attempts_w_exist_login_percent'], 0);
        return sprintf(__('%.0f%%', 'wp-admin-audit'), $quota);
    }

    function column_attempts_7d($item){
        return $this->markGreyIfZero($item['attempts_7d']);
    }

    function column_attempts_30d($item){
        return $this->markGreyIfZero($item['attempts_30d']);
    }

    function column_first_attempt($item){
        return $this->column_localized_date($item['first_attempt']);
    }

    function column_last_attempt($item){
        return $this->column_localized_date($item['last_attempt']);
    }

    protected function displayBeforeList(){
        ?>
            <div class="slideOutContainer">
                <div class="slideOutHeader">
                    <span><?php _e('Background information', 'wp-admin-audit'); ?></span>
                    <span class="slideOutToggleIndicator">+</span>
                </div>
                <div class="slideOutBody" style="display: none;">
                    <h3><?php _e('What is this?', 'wp-admin-audit'); ?></h3>
                    <p><?php _e('In the below list, you will find:', 'wp-admin-audit'); ?></p>
                    <ul class="wada-ul">
                        <li><?php _e('IP addresses from which login attempts were started', 'wp-admin-audit'); ?></li>
                        <li><?php _e('How many login attempts were successful', 'wp-admin-audit'); ?></li>
                        <li><?php _e('What percentage of login attempts used an existing username', 'wp-admin-audit'); ?></li>
                        <li><?php _e('Data is on a rolling 90-day period', 'wp-admin-audit'); ?></li>
                    </ul>
                    <div>
                        <p><?php _e('The data is also collected if you turn off the sensor for logins (to keep your event log free from clutter)', 'wp-admin-audit'); ?></p>
                        <p><?php _e('Repeated login attempts are a sign of automated (brute-force) attacks against your site.', 'wp-admin-audit'); ?></p>
                        <p><?php _e('You may want to use the list to block certain IP addresses.', 'wp-admin-audit'); ?></p>
                    </div>
                </div>
            </div>
        <?php
        parent::displayBeforeList();
    }

    protected function displayAfterList(){

        /*  */

        parent::displayAfterList();
    }

    protected function getTimeframeFilterOptions(){
        return array(
            '7d' => __('one week', 'wp-admin-audit'),
            '30d' => sprintf(__('%d days', 'wp-admin-audit'), 30),
            '90d' => sprintf(__('%d days', 'wp-admin-audit'), 90)
        );
    }

    protected function getWithOrWithOutSelectionFromRequest($default = null){
        $allowedOptions = array_keys($this->getWithOrWithoutSuccessOptions());
        return $this->getStringSelectionFromRequest('w_or_wo_success', $default, $allowedOptions);
    }

    protected function getWithOrWithoutSuccessOptions(){
        return array(
            'all' => __('All', 'wp-admin-audit'),
            'with-logins' => __('With successful logins', 'wp-admin-audit'),
            'without-logins' => __('Without any logins', 'wp-admin-audit')
        );
    }

    protected function getTimeFrameSelectionFromRequest($default = null){
        $allowedOptions = array_keys($this->getTimeframeFilterOptions());
        return $this->getStringSelectionFromRequest('timef', $default, $allowedOptions, 'REQUEST');
    }

    protected function getFilterControls($filterControls = array()){
        $gridPickerStoredHtml ='
            <input name="w_or_wo_success" id="w_or_wo_success" type="hidden" value=""/>	
            <ul class="wada-grid-picker-widget">
                <li class="wada-grid-picker-item wada-grid-picker-item-selected" data-wada-grid-picker-value="all">
                    <div title="All" draggable="false" class="wada-grid-picker-item-content wada-grid-picker-noright-padding-onselect">
                        <span class="wada-grid-picker-item-label wada-grid-picker-item-label-only-text">'.__('All attempts', 'wp-admin-audit').'</span>	
                    </div></li><li class="wada-grid-picker-item" data-wada-grid-picker-value="with-logins">
                    <div draggable="false" class="wada-grid-picker-item-content" title="'.esc_attr(__('Only show IP addresses with at least one successful login', 'wp-admin-audit')).'">						
                        <span class="wada-grid-picker-item-label">'.__('With successful logins', 'wp-admin-audit').'</span>	
                    </div></li><li class="wada-grid-picker-item" data-wada-grid-picker-value="without-logins">
                    <div draggable="false" class="wada-grid-picker-item-content" title="'.esc_attr(__('Only show IP addresses without any successful logins', 'wp-admin-audit')).'">						
                        <span class="wada-grid-picker-item-label">'.__('Without any logins', 'wp-admin-audit').'</span>
                    </div></li>
            </ul>';

        $withOrWithoutSuccessPicker = new stdClass();
        $withOrWithoutSuccessPicker->type = 'raw-html';
        $withOrWithoutSuccessPicker->value = $gridPickerStoredHtml;
        $filterControls[] = $withOrWithoutSuccessPicker;

        return parent::getFilterControls($filterControls);
    }

    protected function getItemsQuery($searchTerm = null){
        global $wpdb;
        $timePeriodFilter = $this->getTimeFrameSelectionFromRequest();
        $withOrWithoutSuccessFilter = $this->getWithOrWithOutSelectionFromRequest();

        if($withOrWithoutSuccessFilter == 'with-logins'){
            $withOrWithoutSuccessFilter = 'attempts_successful > 0';
        }elseif($withOrWithoutSuccessFilter === 'without-logins'){
            $withOrWithoutSuccessFilter = 'attempts_successful <= 0';
        }else{
            $withOrWithoutSuccessFilter = null;
        }

        if($timePeriodFilter === '7d'){
            $timePeriodFilter = "IFNULL(l7d.attempts_7d,0) > 0";
        }elseif($timePeriodFilter === '30d'){
            $timePeriodFilter = "IFNULL(l30d.attempts_30d,0) > 0";
        }elseif($timePeriodFilter === '90d'){
            $timePeriodFilter = "IFNULL(l90d.attempts_90d,0) > 0";
        }else{
            $timePeriodFilter = null;
        }

        $sql= "SELECT INET6_NTOA(offenders.ip_address) as ip_address,"
            ." attempts_successful, IF(total_attempts > 0 AND attempts_successful > 0, (attempts_successful/total_attempts)*100, 0) as attempts_successful_percent,"
            ." attempts_with_existing_login, IF(total_attempts > 0 AND attempts_with_existing_login > 0, (attempts_with_existing_login/total_attempts)*100, 0) as attempts_w_exist_login_percent,"
            ." total_attempts, IFNULL(l7d.attempts_7d,0) AS attempts_7d, IFNULL(l30d.attempts_30d,0) AS attempts_30d,"
            ." IFNULL(l90d.attempts_90d,0) AS attempts_90d, first_attempt, last_attempt"
            ." FROM ("
                ." SELECT ip_address, count(*) as total_attempts, max(login_date) as last_attempt, min(login_date) as first_attempt,"
                ." sum(login_successful) as attempts_successful, sum(user_login_existing) as attempts_with_existing_login"
                ." FROM ".WADA_Database::tbl_logins()
                ." group by ip_address"
            ." ) offenders"
            ." left join ("
                ." SELECT ip_address, count(*) as attempts_7d"
                ." FROM ".WADA_Database::tbl_logins()
                ." where (login_date >= DATE(NOW() - INTERVAL 7 DAY))"
                ." group by ip_address"
            ." ) l7d ON (offenders.ip_address = l7d.ip_address)"
            ." left join ("
                ." SELECT ip_address, count(*) as attempts_30d"
                ." FROM ".WADA_Database::tbl_logins()
                ." where (login_date >= DATE(NOW() - INTERVAL 30 DAY))"
                ." group by ip_address"
            ." ) l30d ON (offenders.ip_address = l30d.ip_address)"
            ." left join ("
            ." SELECT ip_address, count(*) as attempts_90d"
            ." FROM ".WADA_Database::tbl_logins()
            ." where (login_date >= DATE(NOW() - INTERVAL 90 DAY))"
            ." group by ip_address"
            ." ) l90d ON (offenders.ip_address = l90d.ip_address)";

        if($searchTerm) {
            $fieldsToSearchIn = array('offenders.ip_address');
            $sep = " LIKE '%".$searchTerm."%') OR (";
            $whereCond = implode($sep, $fieldsToSearchIn);
            $whereCond .= " LIKE '%".$searchTerm."%') )";
            $sql .= ' WHERE ( ('.$whereCond;
            if($withOrWithoutSuccessFilter){
                $whereCond .= ' AND '.$withOrWithoutSuccessFilter;
            }
            if($timePeriodFilter){
                $whereCond .= ' AND '.$timePeriodFilter;
            }
        }else{
            $whereCond = array();
            if($withOrWithoutSuccessFilter){
                $whereCond[] = $withOrWithoutSuccessFilter;
            }
            if($timePeriodFilter){
                $whereCond[] = $timePeriodFilter;
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
                    w_or_wo_success: jQuery('#w_or_wo_success').val() || ''
                };
                return data;
            }
            function <?php $this->jsPrefix(); ?>getAdditionalParametersFromUrl(thisListRef, query){
                let data = {
                    w_or_wo_success: thisListRef.__query( query, 'w_or_wo_success' ) || ''
                };
                return data;
            }
            function <?php $this->jsPrefix(); ?>startCsvExport(thisList, csvSep, clickedCSVButton){
                let actionStr = '_wada_ajax_logins_csv_export';
                let data = thisList.getQueryVariablesFromInputs();
                jQuery.extend(data, {
                    csvsep: csvSep
                });
                doCSVAjaxRequest(actionStr, data, '<?php echo esc_js(strtolower($this->_args['plural'])); ?>');
            }
            function withOrWithoutSuccessFilterChange(selectedItem){
                let chosenItem = selectedItem.data('wada-grid-picker-value');
                let priorChosen = jQuery('#<?php $this->listFormId(); ?> .wada-grid-picker-item-selected').data('wada-grid-picker-value');
                if(chosenItem != priorChosen){
                    console.log(priorChosen + " -> " + chosenItem);
                    jQuery('#w_or_wo_success').val(chosenItem);
                    jQuery('#<?php $this->listFormId(); ?> .wada-grid-picker-item-selected > div > select').hide();
                    jQuery('#<?php $this->listFormId(); ?> .wada-grid-picker-item-selected').removeClass('wada-grid-picker-item-selected');
                    selectedItem.addClass('wada-grid-picker-item-selected');
                    jQuery('#<?php $this->listFormId(); ?> .wada-grid-picker-item-selected > div > select').show();

                    let data = lists['<?php $this->listFormId(); ?>'].getQueryVariablesFromInputs();
                    lists['<?php $this->listFormId(); ?>'].update(data);
                }
            }
            jQuery(document).ready(function() {
                jQuery('#<?php $this->listFormId(); ?> .wada-grid-picker-item').on('click', function(e){
                    withOrWithoutSuccessFilterChange(jQuery(this));
                });
                jQuery('#<?php $this->listFormId(); ?> .slideOutHeader').on("click", function (e) {
                    let slideOut = jQuery(this).parent().children('.slideOutBody');
                    if(slideOut.is(":hidden")) {
                        slideOut.slideDown("slow");
                        jQuery(this).children('.slideOutToggleIndicator').html('-');
                    }else{
                        slideOut.slideUp("slow");
                        jQuery(this).children('.slideOutToggleIndicator').html('+');
                    }
                });
            });
        </script>
        <?php
        parent::loadJavascriptActions(); // make sure base class also loads JS
    }

}