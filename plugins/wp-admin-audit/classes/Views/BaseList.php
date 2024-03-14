<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

// Some CSV related code from Lee Willis
// Source https://github.com/leewillis77/WpListTableExportable/blob/master/src/WpListTableExportable.php
// Usage under GPL2

if(!defined('ABSPATH')){
    exit;
}
if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}
abstract class WADA_View_BaseList extends WP_List_Table
{
    use WADA_View_BaseView;
    public $parentHeadline;
    public $parentHeadlineLink;
    public $viewHeadline;
    public $viewSubHeadline;
    public $totalItems;
    public $csvExport = null;
    public $csvSeparator;
    public $viewConfig;
    public $loadingContext = null;
    const VIEW_IDENTIFIER = 'wada-base-list';
    const LIST_FORM_ID = 'wada-base-list-form';
    const AJAX_ACTION = '_wada_ajax_base_list';
    const NONCE_ACTION = 'wada-ajax-base-list-nonce';
    const NONCE_NAME = '_wada_ajax_base_list_nonce';

    public function __construct($args = array(), $viewConfig=array()){
        if(!array_key_exists('hook_suffix', $GLOBALS)){
            $GLOBALS['hook_suffix'] = ''; // to circumvent the issue of PHP Notice:  Undefined index: hook_suffix
        }
        parent::__construct($args);
        $this->viewConfig = $viewConfig;
        if($this->csvExport){
            WADA_ScriptUtils::loadCsvSepUtil();
        }
        $this->loadingContext = 'normal';
    }


    abstract protected function getItemsQuery($searchTerm = null);

    public function get_bulk_actions(){
        return array(); // develop it if needed
    }

    public function process_bulk_action(){
        // develop it if needed
    }

    public function ajax_user_can() {
        return current_user_can( 'manage_options' );
    }

    protected function getFilterControls($filterControls = array()){
        if($this->csvExport){


            $width = array_key_exists('width', $this->csvExport) ? $this->csvExport['width'] : 300;
            $height = array_key_exists('height', $this->csvExport) ? $this->csvExport['height'] : 200;

            $csvExportButton = new stdClass();
            $csvExportButton->type = 'button-link';

            $csvExportButton->href = '#';
            /*  */

            $csvExportButton->id = 'csvexport';
            $csvExportButton->name = __('CSV export', 'wp-admin-audit');
            $csvExportButton->label = __('CSV export', 'wp-admin-audit');
            $csvExportButton->iconClass = 'dashicons dashicons-database-export';

            $csvExportButton->options = array('input_class'=>'disabled', 'title'=>sprintf(__('Available in %s', 'wp-admin-audit'), WADA_Version::getMinV4Ft(WADA_Version::FT_ID_CSV_EXP)));
            /*  */

            $filterControls[] = $csvExportButton;


        }
        return $filterControls;
    }

    protected function getTopRightControls(){
        return array(); // override in subclass if needed
    }

    protected function getSubSections(){
        return array(); // override in subclass if needed
    }

    protected function areFiltersActive(){
        return false; // override in subclass if needed
    }

    protected function displayBeforeList(){
        echo '';  // override in subclass if needed
    }

    protected function displayAfterList(){
        echo '<input type="hidden" name="nr-items" class="nr-items" value="' . esc_attr($this->totalItems) . '" />';  // override in subclass if needed
    }

    public function no_items() {
        if(array_key_exists('load-only-via-ajax', $this->viewConfig)
            && $this->loadingContext !== 'ajax'){
            if(array_key_exists('load-only-after-trigger', $this->viewConfig)){
                if(array_key_exists('trigger-html', $this->viewConfig)){
                    echo $this->viewConfig['trigger-html'];
                }else{
                    echo 'load-only-after-trigger --> trigger needed';
                }
            }else{
                printf(__('Loading ...', 'wp-admin-audit'));
            }
            return false;
        }
        echo '<p>';
        if ( ! empty( $_REQUEST['s'] ) ) {
            $s = esc_html(wp_unslash(sanitize_text_field($_REQUEST['s'])));
            printf( __('No %s found for: %s', 'wp-admin-audit'), $this->_args['plural'], '<strong>' . $s . '</strong>' );
        } elseif ( $this->areFiltersActive() ) {
            printf( __('No %s found for filter selection', 'wp-admin-audit'), $this->_args['plural']);
        } else {
            printf( __('No %s yet', 'wp-admin-audit'), $this->_args['plural']);
        }
        echo '</p>';
        return true;
    }

    function column_default($item, $column_name){
        return $this->formatSearchResult(esc_html($item[$column_name]), $this->getSearchTerm());
    }

    function column_id_only($item){
        return esc_html($item['id']);
    }

    function column_localized_timestamp($timestamp){
        return $timestamp ? WADA_DateUtils::formatUTCasDatetimeForWP($timestamp) : '';
    }

    function column_localized_date($timestamp){
        return $timestamp ? WADA_DateUtils::formatUTCasDateForWP($timestamp) : '';
    }

    protected function getSearchTerm(){
        return ( isset( $_REQUEST['s'] ) ) ? sanitize_text_field($_REQUEST['s']) : null;
    }

    protected function formatSearchResult($text, $searchTerm){
        if($searchTerm){
            return preg_replace('#'. preg_quote($searchTerm) .'#i', '<span class="wada-search-highlight">\\0</span>', $text);
        }
        return $text;
    }

    protected function get_hidden_columns(){
        return array(); // override me if you like in your subclass
    }

    protected function setupColumns(){
        $this->_column_headers = array( $this->get_columns(), $this->get_hidden_columns(), $this->get_sortable_columns() );
    }

    public function getDefaultOrder(){
        $sortableColumns = $this->get_sortable_columns();
        if(count($sortableColumns)>0){
            return $sortableColumns[array_keys($sortableColumns)[0]];
        }
        return array(null, null);
    }

    public function ajax_response()
    {
        WADA_Log::debug(static::VIEW_IDENTIFIER.'->ajax_response (nonce_action: '.static::NONCE_ACTION.' / nonce_name: '.static::NONCE_NAME.')');
        check_ajax_referer(static::NONCE_ACTION, static::NONCE_NAME);

        $this->loadingContext = 'ajax';

        $this->prepare_items();

        extract( $this->_args );
        extract( $this->_pagination_args, EXTR_SKIP );

        ob_start();
        if ( ! empty( $_REQUEST['no_placeholder'] ) )
            $this->display_rows();
        else
            $this->display_rows_or_placeholder();
        $rows = ob_get_clean();

        ob_start();
        $this->print_column_headers();
        $headers = ob_get_clean();

        ob_start();
        $this->pagination('top');
        $pagination_top = ob_get_clean();

        ob_start();
        $this->pagination('bottom');
        $pagination_bottom = ob_get_clean();

        ob_start();
        $subSections = $this->getSubSections();
        if($subSections && is_array($subSections) && count($subSections) > 0){
            $this->renderSubSections($subSections);
        }
        $subSections = ob_get_clean();

        $response = array( 'rows' => $rows );
        $response['pagination']['top'] = $pagination_top;
        $response['pagination']['bottom'] = $pagination_bottom;
        $response['sub_sections'] = $subSections;
        $response['column_headers'] = $headers;

        if ( isset( $total_items ) ) {
            $response['total_items'] = intval($total_items);
            $response['total_items_i18n'] = sprintf(_n('1 item', '%s items', $total_items), number_format_i18n($total_items));
        }

        if ( isset( $total_pages ) ) {
            $response['total_pages'] = intval($total_pages);
            $response['total_pages_i18n'] = number_format_i18n( $total_pages );
        }

        //WADA_Log::debug(static::VIEW_IDENTIFIER.'->ajax_response: '.print_r($response, true));

        die( json_encode( $response ) );
    }

    protected function jsPrefix($echoIt = true){
        $prefix = str_replace('-', '', ucwords(static::LIST_FORM_ID, '-'));
        $prefix = lcfirst($prefix); // do not capitalize first character
        if($echoIt){
            echo $prefix;
        }
        return $prefix;
    }
    
    protected function listFormId($echoIt = true){
        $formId = static::LIST_FORM_ID;
        if($echoIt){
            echo $formId;
        }
        return $formId;
    }

    function loadJavascriptActions(){ ?>
        <script type="text/javascript">
            if(!window.lists){
                window.lists = [];
            }
            (function ($) {

                lists['<?php $this->listFormId(); ?>'] = {

                    /** added method display
                     * for getting first sets of data
                     **/
                    display: function() {
                        this.init();
                        if(typeof <?php $this->jsPrefix(); ?>registerAdditionalListEvents === "function"){ // make sure we are adding the events initiated in the overriding subclass (if needed)
                            <?php $this->jsPrefix(); ?>registerAdditionalListEvents(this);
                        }
                    },

                    init: function () {
                        var timer;
                        var delay = 500;

                        $(
                            '#<?php $this->listFormId(); ?> .tablenav-pages a, ' +
                            '#<?php $this->listFormId(); ?> .manage-column.sortable a, ' +
                            '#<?php $this->listFormId(); ?> .manage-column.sorted a'
                        ).on('click', {thisList: this}, function (e) {
                            e.preventDefault();
                            var query = this.search.substring(1);
                            let defaultOrderBy = '<?php echo esc_js(is_null($this->getDefaultOrder()[0]) ? 'id' : $this->getDefaultOrder()[0]); ?>';
                            let defaultOrder = '<?php echo esc_js(is_null($this->getDefaultOrder()[1]) ? 'DESC' : $this->getDefaultOrder()[1]); ?>';

                            var data = {
                                paged: e.data.thisList.__query( query, 'paged' ) || '1',
                                order: e.data.thisList.__query( query, 'order' ) || defaultOrder,
                                orderby: e.data.thisList.__query( query, 'orderby' ) || defaultOrderBy,
                                s: e.data.thisList.__query( query, 's' ) || ''
                            };
                            if(typeof <?php $this->jsPrefix(); ?>getAdditionalParametersFromUrl === "function"){
                                let moreParameters = <?php $this->jsPrefix(); ?>getAdditionalParametersFromUrl(e.data.thisList, query);
                                jQuery.extend(data, moreParameters);
                            }
                            e.data.thisList.update(data);
                        });

                        $(
                            '#<?php $this->listFormId(); ?> input[name=paged], ' +
                            '#<?php $this->listFormId(); ?> input[name=s]'
                        ).on('keyup', {thisList: this}, function (e) {
                            if (13 == e.which)
                                e.preventDefault();

                            let data = e.data.thisList.getQueryVariablesFromInputs();

                            let thisListRef = e.data.thisList;
                            window.clearTimeout(timer);
                            timer = window.setTimeout(function () {
                                thisListRef.update(data);
                            }, delay, thisListRef);
                        });

                        $('#<?php $this->listFormId(); ?>').on('submit', {thisList: this}, function(e){
                            e.preventDefault();
                            var submitButtonId = e.originalEvent.submitter.id;
                            if(typeof <?php $this->jsPrefix(); ?>validationPreSubmitIsOkay === "function"){
                                if(!<?php $this->jsPrefix(); ?>validationPreSubmitIsOkay(submitButtonId)){
                                    return; // skip rest
                                }
                            }
                            if(submitButtonId == 'search-submit'){
                                let data = e.data.thisList.getQueryVariablesFromInputs();
                                e.data.thisList.update(data);
                            }
                            if(submitButtonId == 'doaction'){
                                e.data.thisList.doAction();
                            }

                        });

                        $('#<?php $this->listFormId(); ?> .per_page').on('change', {thisList: this}, function(e){
                           let data = e.data.thisList.getQueryVariablesFromInputs();
                            e.data.thisList.update(data);
                        });

                        $('#<?php $this->listFormId(); ?> .subsubsub a').on('click', {thisList: this}, function(e){
                            e.stopPropagation();
                            e.preventDefault();

                            if (e.target instanceof HTMLAnchorElement){
                                link = jQuery(e.target);
                            }else{
                                link = jQuery(e.target).parent();
                            }

                            let param = link.data('section-param');
                            let value = link.data('section-value');

                            let searchParams = new URLSearchParams(window.location.search);
                            searchParams.set(param, value);
                            let newRelativePathQuery = window.location.pathname + '?' + searchParams.toString();
                            history.pushState(null, '', newRelativePathQuery);

                            let sel = '#<?php $this->listFormId(); ?> .subsubsub a[aria-current="page"]';
                            jQuery('#<?php $this->listFormId(); ?> .subsubsub a[aria-current="page"]').removeAttr('aria-current').removeClass('current');
                            link.attr('aria-current', 'page').addClass('current');

                            let data = e.data.thisList.getQueryVariablesFromInputs();
                            e.data.thisList.update(data);
                        });

                    },

                    doAction: function () {
                        let action = $('#<?php $this->listFormId(); ?> select[name="action"]').val();
                        if(action != -1) {
                            let data = this.getQueryVariablesFromInputs();
                            let selectedCheckboxes = $('#<?php $this->listFormId(); ?> input[name="bulk_entries[]"]:checked').map(function(){return $(this).val();}).get();

                             data = $.extend(
                                {
                                    action: action,
                                    cb: selectedCheckboxes,
                                    <?php echo static::NONCE_NAME; ?>: $('#<?php echo static::NONCE_NAME; ?>').val(),
                                },
                                    data
                            );
                            $('#<?php $this->listFormId(); ?>-wada-admin-table-spinner').addClass('is-active').show();
                            $.ajax({
                                url: ajaxurl,
                                indexValue: {thisListRef: this},
                                data: data,
                                success: function (response) {
                                    $('#<?php $this->listFormId(); ?>-wada-admin-table-spinner').removeClass('is-active').hide();
                                    this.indexValue.thisListRef.__updateListStructure($.parseJSON(response), data);
                                }

                            });
                        }
                    },

                    getQueryVariablesFromInputs: function () {
                        let defaultOrderBy = '<?php echo esc_js(is_null($this->getDefaultOrder()[0]) ? 'id' : $this->getDefaultOrder()[0]); ?>';
                        let defaultOrder = '<?php echo esc_js(is_null($this->getDefaultOrder()[1]) ? 'DESC' : $this->getDefaultOrder()[1]); ?>';
                        let data = {
                            per_page: parseInt($('#<?php $this->listFormId(); ?> select[name=per_page]').val()) || '25',
                            paged: parseInt($('#<?php $this->listFormId(); ?> input[name=paged]').val()) || '1',
                            order: $('#<?php $this->listFormId(); ?> input[name=order]').val() || defaultOrder,
                            orderby: $('#<?php $this->listFormId(); ?> input[name=orderby]').val() || defaultOrderBy,
                            s: $('#<?php $this->listFormId(); ?> input[name=s]').val() || ''
                        };
                        if(typeof <?php $this->jsPrefix(); ?>getAdditionalQueryVariables === "function"){
                            let moreVariables = <?php $this->jsPrefix(); ?>getAdditionalQueryVariables(this);
                            jQuery.extend(data, moreVariables);
                        }
                        return data;
                    },

                    getUrlParameters: function () {
                        var url = document.location.href;
                        var qs = url.substring(url.indexOf('?') + 1).split('&');
                        for(var i = 0, result = {}; i < qs.length; i++){
                            qs[i] = qs[i].split('=');
                            result[qs[i][0]] = decodeURIComponent(qs[i][1]);
                        }
                        return result;
                    },

                    /** AJAX call
                     *
                     * Send the call and replace table parts with updated version!
                     *
                     * @param    object    data The data to pass through AJAX
                     */
                    update: function (data) {
                        $('#<?php $this->listFormId(); ?>-wada-admin-table-spinner').addClass('is-active').show();
                        $.ajax({
                            url: ajaxurl,
                            indexValue: {thisListRef: this},
                            data: $.extend(
                                {
                                    <?php echo static::NONCE_NAME; ?>: $('#<?php echo static::NONCE_NAME; ?>').val(),
                                        action: '<?php echo static::AJAX_ACTION; ?>',
                                },
                                    data
                                ),
                        success: function (response) {
                            $('#<?php $this->listFormId(); ?>-wada-admin-table-spinner').removeClass('is-active').hide();
                            this.indexValue.thisListRef.__updateListStructure($.parseJSON(response), data);
                        }
                    });
                    },

                    __updateListStructure: function(response, data){
                        // Unbind previous events (will get replaced with the events on the updated DOM below)
                        $('#<?php $this->listFormId(); ?> .tablenav-pages a, #<?php $this->listFormId(); ?> .manage-column.sortable a, #<?php $this->listFormId(); ?> .manage-column.sorted a').off('click');
                        $('#<?php $this->listFormId(); ?> input[name=paged], #<?php $this->listFormId(); ?> input[name=s]').off('keyup');
                        $('#<?php $this->listFormId(); ?> .subsubsub a').off('click');
                        $('#<?php $this->listFormId(); ?>').off('submit');
                        if(typeof <?php $this->jsPrefix(); ?>unregisterAdditionalListEvents === "function"){ // unbind additional list events to have a clean slate again
                            <?php $this->jsPrefix(); ?>unregisterAdditionalListEvents(this);
                        }

                        if (response.rows.length)
                            $('#<?php $this->listFormId(); ?> table.wp-list-table tbody').html(response.rows);
                        if (response.column_headers.length)
                            $('#<?php $this->listFormId(); ?> table.wp-list-table thead tr, #<?php $this->listFormId(); ?> table.wp-list-table tfoot tr').html(response.column_headers);
                        if (response.pagination.top.length)
                            $('#<?php $this->listFormId(); ?> .tablenav.top .tablenav-pages').html($(response.pagination.top).html());
                        if (response.pagination.bottom.length)
                            $('#<?php $this->listFormId(); ?> .tablenav.bottom .tablenav-pages').html($(response.pagination.bottom).html());
                        if (response.sub_sections.length) {
                            $('#<?php $this->listFormId(); ?> ul.subsubsub').replaceWith($(response.sub_sections)[0]);
                        }

                        $('#<?php $this->listFormId(); ?> .pagination-links').show();

                        if($('#<?php $this->listFormId(); ?> input.nr-items').length){
                            $('#<?php $this->listFormId(); ?> input.nr-items').val(response.total_items);
                        }
                        if($('#<?php $this->listFormId(); ?> .csv-nr-items').length){
                            $('#<?php $this->listFormId(); ?> .csv-nr-items').html(response.total_items);
                        }

                        this.init();
                        if(typeof <?php $this->jsPrefix(); ?>registerAdditionalListEvents === "function"){ // make sure we are adding back the events initiated in the overriding subclass (if needed)
                            <?php $this->jsPrefix(); ?>registerAdditionalListEvents(this);
                        }

                        if(typeof <?php $this->jsPrefix(); ?>callAfterListUpdates === "function"){ // do more stuff if needed
                            <?php $this->jsPrefix(); ?>callAfterListUpdates(this);
                        }

                        let baseUrl = document.location.protocol +"//"+ document.location.hostname + document.location.pathname;
                        let urlParams = this.getUrlParameters();
                        let mergedParams = $.extend(urlParams, data);
                        let historyUrl = baseUrl+'?'+$.param(mergedParams);
                        history.pushState( null, null, historyUrl );
                    },

                    /**
                     * Filter the URL Query to extract variables
                     *
                     * @see http://css-tricks.com/snippets/javascript/get-url-variables/
                     *
                     * @param    string    query The URL query part containing the variables
                     * @param    string    variable Name of the variable we want to get
                     *
                     * @return   string|boolean The variable value if available, false else.
                     */
                    __query: function (query, variable) {

                        var vars = query.split("&");
                        for (var i = 0; i < vars.length; i++) {
                            var pair = vars[i].split("=");
                            if (pair[0] == variable)
                                return pair[1];
                        }
                        return false;
                    },
                }

                lists['<?php $this->listFormId(); ?>'].display();
                <?php if(  // trigger reload via Ajax after render (since empty server side as configured)
                        array_key_exists('load-only-via-ajax', $this->viewConfig)
                        && !array_key_exists('load-only-after-trigger', $this->viewConfig) // not in the context of "wait for trigger"
                ): ?>
                    let data = lists['<?php $this->listFormId(); ?>'].getQueryVariablesFromInputs();
                    lists['<?php $this->listFormId(); ?>'].update(data);
                <?php endif; ?>


                <?php if($this->csvExport): ?>
                registerCSVGoButtons(lists['<?php $this->listFormId(); ?>']);
                <?php endif; ?>


            })(jQuery);

            <?php // here in the context of "wait for trigger"
            if( array_key_exists('load-only-via-ajax', $this->viewConfig)
            && array_key_exists('load-only-after-trigger', $this->viewConfig)): ?>
            function <?php $this->jsPrefix(); ?>triggerList(){
                let data = lists['<?php $this->listFormId(); ?>'].getQueryVariablesFromInputs();
                lists['<?php $this->listFormId(); ?>'].update(data);
            }
            jQuery('<?php echo $this->viewConfig['trigger-selector']; ?>').on('click', function (e) {
                <?php $this->jsPrefix(); ?>triggerList();
            });
            <?php endif; ?>

            function registerCSVGoButtons(thisList) {
                jQuery('.<?php $this->listFormId(); ?>-wada-csv-export .csv-export-go').on('click', function (e) {
                    jQuery('.<?php $this->listFormId(); ?>-wada-csv-export .csv-export-msg').html('').removeClass('wada-error');
                    jQuery(this).children('.wada-button-text').hide();
                    jQuery(this).children('.spinner').addClass('is-active').show();
                    jQuery('.<?php $this->listFormId(); ?>-wada-csv-export .csv-export-go').prop('disabled', true);
                    let csvSep = getCsvSeparator();
                    <?php $this->jsPrefix(); ?>startCsvExport(thisList, csvSep, this);
                });
            }
            function doCSVAjaxRequest(actionStr, data, namePrefixStr){
                jQuery.ajax({
                        url: ajaxurl,
                        indexValue: {namePrefix:namePrefixStr},
                        data: jQuery.extend({
                            <?php echo static::NONCE_NAME; ?>: jQuery('#<?php echo static::NONCE_NAME; ?>').val(),
                                action: actionStr
                        }, data),
                    success: function(data) {
                        jQuery('#TB_window .<?php $this->listFormId(); ?>-wada-csv-export .csv-export-go .wada-button-text').show();
                        jQuery('#TB_window .<?php $this->listFormId(); ?>-wada-csv-export .csv-export-go .spinner').removeClass('is-active').hide();
                        jQuery('#TB_window .<?php $this->listFormId(); ?>-wada-csv-export .csv-export-go').prop('disabled', false)
                        tb_remove(); // close modal

                        // Make CSV downloadable
                        var downloadLink = document.createElement("a");
                        var fileData = ['\ufeff'+data];

                        var blobObject = new Blob(fileData,{
                            type: "text/csv;charset=utf-8;"
                        });

                        var url = URL.createObjectURL(blobObject);
                        downloadLink.href = url;
                        let currDate = new Date();
                        let downloadFileName = this.indexValue.namePrefix + "-";
                        downloadFileName += currDate.toISOString().split('T')[0] + "-";
                        downloadFileName += currDate.getHours().toString();
                        downloadFileName += currDate.getMinutes().toString();
                        downloadFileName += currDate.getSeconds().toString()+".csv";
                        downloadLink.download = downloadFileName;

                        // Actually download CSV
                        document.body.appendChild(downloadLink);
                        downloadLink.click();
                        document.body.removeChild(downloadLink);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        jQuery('#TB_window .<?php $this->listFormId(); ?>-wada-csv-export .csv-export-go .wada-button-text').show();
                        jQuery('#TB_window .<?php $this->listFormId(); ?>-wada-csv-export .csv-export-go .spinner').removeClass('is-active').hide();
                        jQuery('#TB_window .<?php $this->listFormId(); ?>-wada-csv-export .csv-export-go').prop('disabled', false)
                        jQuery('#TB_window .<?php $this->listFormId(); ?>-wada-csv-export .csv-export-msg').html('<?php echo esc_js(__('Error while exporting. Please try again later.', 'wp-admin-audit')); ?>').addClass('wada-error');
                    }
                });
            }
        </script>
        <?php
    }

    protected function performAdditionalItemPreparation(){ // override to do additional manipulations
        return $this->items;
    }

    protected function get_items_per_page( $defaultUserOption = 'edit_post_per_page', $backupDefault = 20 ) { // overriding parent method
        $userOptionName = 'per_page_'.static::VIEW_IDENTIFIER;
        $perPage = array_key_exists('per_page', $_REQUEST) ? intval($_REQUEST['per_page']) : 0; // anything in the request will be taken
        if($perPage < 1 || $perPage > 999) {
            $perPage = (int)get_user_option($userOptionName);
            if($perPage < 1 || $perPage > 999) {
                $perPage = (int)get_user_option($defaultUserOption);
                if (empty($perPage) || $perPage < 1) {
                    $perPage = $backupDefault;
                }
            }
        }
        update_user_option(get_current_user_id(), $userOptionName, $perPage);
        return intval($perPage);
    }

    public function prepare_items($returnEmpty = false) {
        WADA_Log::debug(static::VIEW_IDENTIFIER.'->prepare_items');
        $this->setupColumns();
        $this->process_bulk_action();

        $perPage = $this->get_items_per_page();
        $perPage = ($perPage > 0) ? $perPage : 1;
        if($returnEmpty){
            $this->set_pagination_args( array(
                'total_pages' => 1, // we put one, so that the pagination element gets rendered (will be needed later)
                'total_items' => 1, // we put one, so that the pagination element gets rendered (will be needed later)
                'per_page'    => $perPage
            ) );
            $this->totalItems = 0;
            $this->items = null;
        }else{
            $searchTerm = $this->getSearchTerm();
            $totalItems  = $this->getNrOfItems($searchTerm);
            $totalPages = ceil($totalItems/$perPage);

            $this->set_pagination_args( array(
                'total_pages' => $totalPages, //WE have to calculate the total number of pages
                'total_items' => $totalItems, //WE have to calculate the total number of items
                'per_page'    => $perPage //WE have to determine how many items to show on a page
            ) );

            $this->totalItems = $totalItems;
            $currentPage = $this->get_pagenum();
            $this->items = $this->getItems($perPage, $currentPage, $searchTerm);
            $this->items = $this->performAdditionalItemPreparation();
        }
    }

    protected function renderFilterControl($filterControl){
        switch($filterControl->type){
            case 'select':
                WADA_HtmlUtils::selectField($filterControl->field, $filterControl->label, $filterControl->value, $filterControl->selectOptions, array(), false, property_exists($filterControl, 'options') ? $filterControl->options : array());
                break;
            case 'bool':
                WADA_HtmlUtils::checkboxField($filterControl->field, $filterControl->label, $filterControl->value, property_exists($filterControl, 'options') ? $filterControl->options : array());
                break;
            case 'html':
                echo wp_kses_post($filterControl->value);
                break;
            case 'raw-html':
                echo $filterControl->value;
                break;
            case 'button':
                WADA_HtmlUtils::button($filterControl->name, $filterControl->label, $filterControl->iconClass, $filterControl->action, property_exists($filterControl, 'options') ? $filterControl->options : array());
                break;
            case 'hidden':
                WADA_HtmlUtils::hiddenField($filterControl->field, $filterControl->value, property_exists($filterControl, 'options') ? $filterControl->options : array());
                break;
            case 'button-link':
                WADA_HtmlUtils::buttonLink($filterControl->name, $filterControl->href, $filterControl->label, $filterControl->iconClass, property_exists($filterControl, 'options') ? $filterControl->options : array());
                break;
        }
    }

    protected function getSelectionFromRequest($dataType, $name, $default, $allowedOptions, $reqArraySelection = 'GET'){
        $reqArraySelection = strtoupper($reqArraySelection);
        $reqArray = $_GET;
        if($reqArraySelection === 'POST'){
            $reqArray = $_POST;
        }elseif($reqArraySelection === 'REQUEST'){
            $reqArray = $_REQUEST;
        }

        $returnValue = $default;
        if(array_key_exists($name, $reqArray) && !empty($reqArray[$name])){
            if($dataType === 'int'){
                $reqValue = intval($reqArray[$name]);
            }else{
                $reqValue = sanitize_text_field($reqArray[$name]);
            }
            if(in_array($reqValue, $allowedOptions)){
                $returnValue = $reqValue;
            }
        }
        return $returnValue;
    }

    protected function getStringSelectionFromRequest($name, $default, $allowedOptions, $reqArraySelection = 'GET'){
        return $this->getSelectionFromRequest('string', $name, $default, $allowedOptions, $reqArraySelection);
    }

    protected function getIntSelectionFromRequest($name, $default, $allowedOptions, $reqArraySelection = 'GET'){
        return $this->getSelectionFromRequest('int', $name, $default, $allowedOptions, $reqArraySelection);
    }

    protected function getAllEnabledDisabledSections($basePage, $active, $nrEnabled, $nrDisabled){
        $all = new stdClass();
        $all->page = $basePage;
        $all->class = 'all';
        $all->param = 'active';
        $all->paramValue = -1;
        $all->default = true;
        $all->current = ($active == $all->paramValue);
        $all->title = __('All', 'wp-admin-audit');
        $all->count = ($nrEnabled + $nrDisabled);

        $enabled = new stdClass();
        $enabled->page = $basePage;
        $enabled->class = 'enabled';
        $enabled->param = 'active';
        $enabled->paramValue = 1;
        $enabled->default = false;
        $enabled->current = ($active == $enabled->paramValue);
        $enabled->title = __('Enabled', 'wp-admin-audit');
        $enabled->count = $nrEnabled;

        $disabled = new stdClass();
        $disabled->page = $basePage;
        $disabled->class = 'disabled';
        $disabled->param = 'active';
        $disabled->paramValue = 0;
        $disabled->default = false;
        $disabled->current = ($active == $disabled->paramValue);
        $disabled->title = __('Disabled', 'wp-admin-audit');
        $disabled->count = $nrDisabled;

        $sections = array();
        $sections[] = $all;
        $sections[] = $enabled;
        $sections[] = $disabled;

        return $sections;
    }

    protected function renderSubSections($sections, $includingOuterHtml = true){
        // WADA_Log::debug('renderSubSections: '.print_r($sections, true));
        $html = '';
        if($includingOuterHtml){
            $html .= '<ul class="subsubsub">';
        }
        foreach($sections AS $key => $section){
            $html .= '<li class="'.esc_attr($section->class).' subsubsub-list-item">';
            $page = '?page='.$section->page.($section->default ? '' : '&'.$section->param.'='.sanitize_text_field($section->paramValue));
            $currentClass = ($section->current ? 'current' : '');
            $countHtml = property_exists($section, 'count') ? ' <span class="count">('.intval($section->count).')</span>' : '';
            $html .= '<a href="'.esc_url($page).'" class="'.esc_attr($currentClass).' subsubsub-link" '
                .'data-section-param="'.esc_attr($section->param).'" '
                .'data-section-value="'.esc_attr($section->paramValue).'" '
                .($section->current ? 'aria-current="page"' : '')
                .'>'.esc_html($section->title).$countHtml.'</a>';
            if($key < count($sections)-1){
                $html .= ' |';
            }
            $html .= '</li> ';
        }
        if($includingOuterHtml){
            $html .= '</ul>';
        }
        echo $html;
    }

    protected function display_tablenav( $which ) {
        if ( 'top' === $which ) {
            wp_nonce_field( 'bulk-' . $this->_args['plural'] );
            $subSections = $this->getSubSections();
            if($subSections && is_array($subSections) && count($subSections) > 0){
                $this->renderSubSections($subSections);
            }
        }
        ?>

        <div class="tablenav <?php echo esc_attr( $which ); ?>">
            <?php if ( $this->has_items() ) : ?>
                <div class="alignleft actions bulkactions">
                    <?php $this->bulk_actions( $which ); ?>
                </div>
            <?php
            endif;
            $this->extra_tablenav( $which );
            ?>
            <?php
            $this->pagination( $which );
            if ( 'bottom' === $which ) {
                $perPageOptions = array(5, 10, 15, 20, 50, 100, 250, 500);
                $perPage = $this->get_items_per_page();
                $addNewOption = false;
                $addedOption = false;
                if(!in_array($perPage, $perPageOptions)){
                    $addNewOption = true;
                }
                ?>
                <div class="wada-admin-table-per-page">
                    <select name="per_page" id="<?php $this->listFormId(); ?>-per_page" class="per_page">
                        <?php
                        foreach($perPageOptions AS $pageOpt){
                            if($addNewOption && !$addedOption && ($perPage < $pageOpt)){
                                echo '<option value="'.intval($perPage).'" selected>'.intval($perPage).'</option>';
                                $addedOption = true;
                            }
                            echo '<option value="'.intval($pageOpt).'" '.(intval($pageOpt) === intval($perPage) ? 'selected' : '').'>'.intval($pageOpt).'</option>';
                        }
                        if($addNewOption && !$addedOption){
                            echo '<option value="'.intval($perPage).'" selected>'.intval($perPage).'</option>';
                            $addedOption = true;
                        }
                        ?>
                    </select>
                    <label for="per_page"><?php esc_html_e('entries per page', 'wp-admin-audit'); ?></label>
                </div>
                <?php
            }
            ?>
            <br class="clear" />
        </div>
        <?php
    }

    protected function extra_tablenav($which){
        if($which == "top"){
            $filterControls = $this->getFilterControls();
            if($filterControls && is_array($filterControls) && count($filterControls) > 0){
            ?>
                <div class="alignleft actions bulkactions">
                    <?php
                    foreach($filterControls AS $filterControl){
                        $this->renderFilterControl($filterControl);
                    }
                    ?>
                </div>
            <?php
            }
            $topRightControls = $this->getTopRightControls();
            if($topRightControls && is_array($topRightControls) && count($topRightControls) > 0){
                ?>
                <div class="alignright actions bulkactions">
                    <?php
                    foreach($topRightControls AS $ctrl){
                        $this->renderFilterControl($ctrl);
                    }
                    ?>
                </div>
                <?php
            }
        }
        if($which == "bottom"){
            //The code that goes after the table is there
            ?><div style="display:none;"></div><?php
        }
    }

    public function displayList() {
        ?>
        <div class="wrap">
            <?php if($this->parentHeadline): ?>
                <?php if($this->parentHeadlineLink): ?><a href="<?php echo $this->parentHeadlineLink; ?>" class="wada-parent-link"><?php endif; ?><h1 class="wp-heading-inline"><?php echo esc_html($this->parentHeadline); ?></h1><?php if($this->parentHeadlineLink): ?></a><?php endif; ?>                <h1 class="wp-heading-inline wada-breadcrumb-divider">&gt;</h1>
            <?php endif; ?>
            <h1 class="wp-heading-inline"><?php echo esc_html($this->viewHeadline); ?></h1>
            <?php if(method_exists($this, 'getAddNewPageUrl')): ?>
                <a href="<?php echo $this->getAddNewPageUrl(); ?>" class="page-title-action"><?php echo esc_html_x( 'Add New', 'link' ); ?></a>
            <?php endif; ?>
            <?php if($this->viewSubHeadline): ?>
                <h2><?php echo esc_html($this->viewSubHeadline); ?></h2>
            <?php endif; ?>
            <?php if(method_exists($this, 'renderHtmlAboveList')){
               $this->renderHtmlAboveList();
            }?>
            <?php if(!array_key_exists('subview-mode', $this->viewConfig)): ?>
            <hr class="wp-header-end">
            <?php endif; ?>
            <div id="post-body" class="metabox-holder columns-2">
                <div id="post-body-content">
                    <div class="meta-box-sortables ui-sortable">
                        <form id="<?php $this->listFormId(); ?>" method="post" class="wada-admin-table">
                            <?php
                            WADA_Log::debug('BaseList->displayList()');
                            $returnEmpty = array_key_exists('load-only-via-ajax', $this->viewConfig);
                            $this->displayBeforeList();
                            $this->prepare_items($returnEmpty);
                            if(!array_key_exists('no-searchbar', $this->viewConfig)) {
                                $this->search_box(__('Search', 'wp-admin-audit'), 'search_box');
                            }
                            $this->display();
                            $this->displayAfterList();
                            wp_nonce_field(static::NONCE_ACTION, static::NONCE_NAME);
                            ?>
                        </form>
                    </div>
                </div>
            </div>
            <br class="clear">
        </div>
        <?php
    }

    public function getNrOfItems($searchTerm = null) {
        global $wpdb;
        $sql = $this->getItemsQuery($searchTerm);
        $sql = "SELECT COUNT(*) FROM (".$sql.") GT";
        $noItems = $wpdb->get_var( $sql );
        WADA_Log::debug('getNrOfItems: '.$noItems);
        return $noItems;
    }

    /** Override search box to get it to auto-search (and not have a submit button) */
    public function search_box( $text, $input_id ) {
        if ( empty( $_REQUEST['s'] ) && ! $this->has_items() && !array_key_exists('load-only-via-ajax', $this->viewConfig)) {
            return;
        }
        $input_id = $this->listFormId(false).'-'.$input_id . '-search-input';
        WADA_Log::debug('search_box input_id: '.$input_id.', text: '.$text);

        if ( ! empty( $_REQUEST['orderby'] ) ) {
            $orderBy = sanitize_text_field($_REQUEST['orderby']);
            echo '<input type="hidden" name="orderby" value="' . esc_attr($orderBy) . '" />';
        }
        if ( ! empty( $_REQUEST['order'] ) ) {
            $order = sanitize_text_field($_REQUEST['order']);
            echo '<input type="hidden" name="order" value="' . esc_attr($order) . '" />';
        }
        ?>
        <p class="search-box">
            <label class="screen-reader-text" for="<?php echo esc_attr( $input_id ); ?>"><?php echo esc_html($text); ?>:</label>
            <input type="search" id="<?php echo esc_attr( $input_id ); ?>" class="wp-filter-search" name="s" value="<?php _admin_search_query(); ?>" placeholder="<?php esc_attr_e('Search list...', 'wp-admin-audit'); ?>" />
            <?php submit_button( $text, 'hide-if-js', '', false, array( 'id' => 'search-submit' ) ); ?>
        </p>
        <span id="<?php $this->listFormId(); ?>-wada-admin-table-spinner" class="spinner" style="display: none"></span>
        <?php
    }

    public function getItems($perPage, $pageNumber, $searchTerm = null){
        global $wpdb;
        $sql = $this->getItemsQuery($searchTerm);
        $orderBy = $order = null;
        if ( ! empty( $_REQUEST['orderby'] ) ) {
            $orderBy = sanitize_sql_orderby( $_REQUEST['orderby'] );
            $order = !empty( $_REQUEST['order'] ) ? sanitize_text_field( $_REQUEST['order'] ) : 'ASC';
        }elseif(method_exists($this, 'getDefaultOrder')){
                list($orderBy, $order) = $this->getDefaultOrder();
        }
        if($orderBy) {
            if($orderBy === 'id_only'){
                $orderBy = 'id';
            }
            $order = strtoupper(trim(sanitize_text_field($order)));
            if($order !== 'ASC' AND $order !== 'DESC' AND $order !== ''){
                $order = 'ASC';
            }
            $orderClause = sanitize_sql_orderby($orderBy. ' ' . $order);
            if($orderClause) {
                $sql .= ' ORDER BY ' . sanitize_sql_orderby($orderBy . ' ' . $order);
            }
        }
        if($perPage > 0) {
            $sql .= " LIMIT $perPage";
            $sql .= ' OFFSET ' . ( $pageNumber - 1 ) * $perPage;
        }
        WADA_Log::debug('getItems sql: '.$sql);
        return $wpdb->get_results( $sql, 'ARRAY_A' );
    }

    protected function enqueueMessagesFromUrlParameters(){
        if(array_key_exists('msg', $_GET)){
            $msg = sanitize_text_field($_GET['msg']);
            $msgType = array_key_exists('mt', $_GET) ? strtolower(sanitize_text_field($_GET['mt'])) : 'info';
            $this->enqueueMessage($msg, $msgType);
        }
    }

    public function execute(){
        WADA_Log::debug($this->viewHeadline.' / execute() config: '.print_r($this->viewConfig, true));
        if(!array_key_exists('no-messages', $this->viewConfig)) {
            $this->enqueueMessagesFromUrlParameters();
            $this->displayMessages();
        }
        $this->displayList();
    }

    protected function getCSVColumns(){
        $columns = method_exists($this, 'get_csv_columns') ? $this->get_csv_columns() : $this->get_columns();
        $hiddenColumns = method_exists($this, 'get_hidden_csv_columns') ? $this->get_hidden_csv_columns() : $this->get_hidden_columns();
        $csvColumns = array();
        foreach($columns AS $colKey => $columnDisplayName){
            if(in_array($colKey, $hiddenColumns)){
                continue;
            }else{
                $csvColumns[$colKey] = $columnDisplayName;
            }
        }
        return $csvColumns;
    }

    protected function getCSVSeparator(){
        return ( isset( $_REQUEST['csvsep'] ) ) ? sanitize_text_field($_REQUEST['csvsep']) : ',';
    }

    /**
     * Clean up a string for use in CSV.
     *
     * NOTE: This isn't about escaping, but it tidies up a string that was originally targeted at
     * HTML output, and tries to make it CSV friendly.
     */
    protected function clean( $string ) {
        // Replace <br> with a space.
        $string = preg_replace( '#<br\s*/?>#i', ' ', $string );
        // Strip all other tags.
        $string = strip_tags( $string );
        // Decode any HTML entitites.
        return html_entity_decode( $string, ENT_COMPAT, 'UTF-8' );
    }

    protected function getCSVFilename(){
        return str_replace(' ', '_', $this->_args['plural']) . '-' . date_i18n( 'Y-m-d_His' ) . '.csv';
    }

    /**
     * Output an array using fputcsv to standard output.
     */
    protected function writeToCSV( $data ) {
        $out = fopen( 'php://output', 'w' );
        fputcsv( $out, $data,$this->csvSeparator);
        fclose( $out );
    }

    public function getItemsListOnly($perPage=0, $currentPage=1, $searchTerm=null){
        $this->items = $this->getItems($perPage, $currentPage, $searchTerm);
        $this->items = $this->performAdditionalItemPreparation();
        return $this->items;
    }

    protected function prepareItemsForCSV(){
        $csvSep = $this->getCSVSeparator();
        $this->csvSeparator = $csvSep;
        $this->items = $this->getItemsListOnly(0, 1, $this->getSearchTerm());
        WADA_Log::debug(static::VIEW_IDENTIFIER.'->prepareItemsForCSV #items in scope: '.count($this->items).', csvSep: '.$this->csvSeparator);
        //WADA_Log::debug(static::VIEW_IDENTIFIER.'->prepareItemsForCSV items: '.print_r($this->items, true));
    }

    protected function printCSVHeader(){
        $filename = $this->getCSVFilename();
        $csvColumns = $this->getCSVColumns();
        $displayNames = array_values($csvColumns);
        WADA_Log::debug(static::VIEW_IDENTIFIER.'->printCSVHeader filename: '.$filename);
        WADA_Log::debug(static::VIEW_IDENTIFIER.'->printCSVHeader csvColumns: '.print_r($csvColumns, true));
        WADA_Log::debug(static::VIEW_IDENTIFIER.'->printCSVHeader displayNames: '.print_r($displayNames, true));
        header( 'Content-Type: text/csv; charset=utf-8' );
        header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
        $this->writeToCSV($displayNames);
    }

    protected function printCSVBody(){
        $csvColumns = $this->getCSVColumns();
        foreach ($this->items as $item) {
            $row = array();
            foreach ($csvColumns as $colKey => $displayName) {
                $cell = null;
                if (method_exists($this, 'column_csv_' . $colKey)) {
                    //WADA_Log::debug('printCSVBody for '.$colKey.': custom csv method');
                    $cell = call_user_func(array($this, 'column_csv_' . $colKey), $item);
                } elseif (method_exists($this, 'column_' . $colKey)) {
                    //WADA_Log::debug('printCSVBody for '.$colKey.': custom regular method');
                    $cell = $this->clean(
                        call_user_func(array($this, 'column_' . $colKey), $item)
                    );
                } else {
                    //WADA_Log::debug('printCSVBody for '.$colKey.': default method');
                    $cell = $this->clean(
                        $this->column_default($item, $colKey)
                    );
                }
                //WADA_Log::debug('printCSVBody for '.$colKey.' cell content: '.$cell);
                $row[] = $cell;
            }
            $this->writeToCSV($row);
        }
    }

    public function csvDownload($dieAfterDownload = true){

        /*  */
    }

    public function csvExportAjaxResponse(){
        WADA_Log::debug('csvExportAjaxResponse '.$this->_args['plural']);
        WADA_Log::debug('csvExportAjaxResponse GET: '.print_r($_GET, true));
        check_ajax_referer(static::NONCE_ACTION, static::NONCE_NAME);
        $this->csvDownload();
    }

}