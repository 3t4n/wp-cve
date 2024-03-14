<?php

namespace MoSharePointObjectSync\View;

use MoSharePointObjectSync\Wrappers\pluginConstants;
use MoSharePointObjectSync\Wrappers\wpWrapper;

class documentsSync {

    private static $instance;

	public static function getView() {
        if (!isset(self::$instance)) {
            $class = __CLASS__;
            self::$instance = new $class;
        }
        return self::$instance;
    }


	public function mo_sps_display__tab_details() {
        $app = wpWrapper::mo_sps_get_option(pluginConstants::APP_CONFIG);
        $selected_site = wpWrapper::mo_sps_get_option(pluginConstants::SPS_SEL_SITE);
        $selected_site = isset($selected_site) && $selected_site ? $selected_site : '';
        $selected_drive = wpWrapper::mo_sps_get_option(pluginConstants::SPS_SEL_DRIVE);
        $selected_drive = isset($selected_drive) && $selected_drive ? $selected_drive : '';
        $selected_folder = wpWrapper::mo_sps_get_option(pluginConstants::SPS_SEL_FOLDER);
        $selected_folder = isset($selected_folder) && $selected_folder ? $selected_folder : '';
        $selected_drive_name = wpWrapper::mo_sps_get_option(pluginConstants::SPS_SEL_DRIVE_NAME);
        $selected_drive_name = isset($selected_drive_name) && $selected_drive_name ? $selected_drive_name : '';
        $breadcrumbs = wpWrapper::mo_sps_get_option(pluginConstants::BREADCRUMBS);

        $app_type = isset($app['app_type']) ? $app['app_type'] : 'manual';
        $connector = get_option(pluginConstants::CLOUD_CONNECTOR);

        $document_sync_metdata = [
            'admin_ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('mo_doc_embed__nonce'),
            'tab' => isset($_REQUEST['tab']) ? $_REQUEST['tab'] : '',
            'serverRelativeURL' => isset($_REQUEST['serverRelativeURL']) ? $_REQUEST['serverRelativeURL'] : '',
            'folder_icon_url' => esc_url(plugin_dir_url(__FILE__) . '../images/folder.svg'),
            'fetch' => isset($_REQUEST['fetch']) ? $_REQUEST['fetch'] : '1',
            'folder_path' => isset($app['folder_path']) ? $app['folder_path'] : '',
            'selected_site' => $selected_site,
            'selected_drive' => $selected_drive,
            'selected_folder' => $selected_folder,
            'selected_drive_name' => $selected_drive_name,
            'breadcrumbs' => $breadcrumbs,
            'is_plugin' => 'y',
            'connector' => $connector,
            'app_type' => $app_type,
            'mime_types' => pluginConstants::MIME_TYPES,
            'loader_gif' => esc_url(plugin_dir_url(__FILE__) . '../images/loader.gif'),
            'load_icon' => esc_url(plugin_dir_url(__FILE__) . '../images/Chasing_arrows.gif'),
            'file_icon' => esc_url(plugin_dir_url(__FILE__) . '../images/file.png'),
            'worddoc_icon' => esc_url(plugin_dir_url(__FILE__) . '../images/msword_file.png'),
            'exceldoc_icon' => esc_url(plugin_dir_url(__FILE__) . '../images/msexcel_file.png'),
            'pdfdoc_icon' => esc_url(plugin_dir_url(__FILE__) . '../images/pdf_file.png'),
            'emptyFolderDrop_icon' => esc_url(plugin_dir_url(__FILE__) . '../images/empty_folder_drop.svg'),
            'download' => esc_url(plugin_dir_url(__FILE__) . '../images/download.svg'),
            'redirect' => esc_url(plugin_dir_url(__FILE__) . '../images/redirect.svg'),
            'error'=> esc_url(plugin_dir_url(__FILE__) . '../images/error.svg'),
        ];

?>
        <div class="mo-ms-tab-content" style="width:77rem;">
            <h1 style="font-weight: 600">Preview Sharepoint Folders / Files </h1>
            <div id="basic_attr_access_desc" class="mo_sps_help_desc" style="margin-bottom:20px;font-weight:500;">
                <span>Here You can access or navigate through all folders and files from your sharepoint library.
                </span>
            </div>

            <div style="width: 68%">
                <div class="mo-ms-tab-content-left-border">
                    <?php
                    $this->mo_sps_display__sites();
                    $config = [];
                    $config['height'] = "400px";
                    $config['width'] = "100%";
                    $this->mo_sps_display_sync_documents($config, false);
                    $this->mo_sps_feature();
                    $sync_js_url = plugins_url('../includes/js/ajax.js', __FILE__);
                    wp_enqueue_script('mo_sps_sync_js', $sync_js_url, array(), PLUGIN_VERSION);

                    $select2_js_url = plugins_url('../includes/js/select2.min.js', __FILE__);
                    wp_enqueue_script('mo_sps_select2_js', $select2_js_url, array(), PLUGIN_VERSION);

                    $select2_css = plugins_url('../includes/css/select2.min.css', __FILE__);
                    wp_enqueue_style('mo_sps_select2_css', $select2_css, array(), PLUGIN_VERSION);

                    wp_add_inline_script('mo_sps_sync_js', 'var doc_sync_data='.json_encode($document_sync_metdata).';','before');

                    ?>
                </div>
            </div>
        </div>
    <?php
    }

    private function mo_sps_display__sites()
    {
        $config = wpWrapper::mo_sps_get_option(pluginConstants::APP_CONFIG);
        $sites = wpWrapper::mo_sps_get_option(pluginConstants::SPS_SITES);
        $sites = isset($sites) && is_array($sites) ? $sites : [];
        $drives = wpWrapper::mo_sps_get_option(pluginConstants::SPS_DRIVES);
        $drives = isset($drives) && is_array($drives) ? $drives : [];
        $selected_site = wpWrapper::mo_sps_get_option(pluginConstants::SPS_SEL_SITE);
        $selected_site = isset($selected_site) && $selected_site ? $selected_site : '';
        $selected_drive = wpWrapper::mo_sps_get_option(pluginConstants::SPS_SEL_DRIVE);
        $selected_drive = isset($selected_drive) && $selected_drive ? $selected_drive : '';

        $disabled = ($selected_drive && $selected_site) ? '' : 'disabled';
        $pointer_events = ($selected_drive && $selected_site) ? '' : 'none';

        $upn = isset($config['upn']) ? $config['upn'] : '';
        $app_type = $config && isset($config['app_type']) ? $config['app_type'] : 'manual'; 

    ?>
        <div id="table_box" class="mo-ms-tab-content-tile" style="width:135%;">
            <div class="mo-ms-tab-content-tile-content">
                <span style="font-size: 18px;font-weight: 650;">Select Site and Drive</span>
                <div id="basic_attr_access_desc" class="mo_sps_help_desc" style="margin-bottom:20px;font-weight:500;">
                    <span>In order to sync the documents from sharepoint, first you'll need to select site and drive from which you want to fetch the documents.
                    </span>
                </div>
                <table class="mo-ms-tab-content-app-config-table">
                <?php if( "manual" === $app_type || "sharepoint" === get_option("mo_sps_cloud_connector")){ ?>
                    <tr>
                        <td class="left-div"><span style="font-weight:400;font-size:13px;">Select Site<span style="color:red;font-weight:bold;">*</span></span></td>
                        <td class="right-div">
                            <div style="align-items:center;">
                                <select id="mo_sps_site_select" style="width: 50%;">
                                    <option selected disabled value="">--select a site--</option>
                                    <?php
                                    foreach ($sites as $site) { ?>
                                        <option <?php echo (($selected_site == $site['displayName']) ? 'selected' : ''); ?> value="<?php echo $site['displayName']; ?>" data-id="<?php echo $site['id'] ?>"><?php echo $site['displayName']; ?></option>
                                    <?php }
                                    ?>
                                </select>
                                <div style="float:right;margin-top:4px;">
                                    <form method="post" id="mo_sps_site_refresh">
                                        <input type="hidden" name="option" value="mo_sps_site_refresh">
                                        <?php wp_nonce_field('mo_sps_site_refresh'); ?>
                                        <button style="width: 8rem;margin-left: 6px;display:<?php echo isset($config['app_type']) && $config['app_type'] == 'auto' ? 'block' : 'none'; ?>;" class="mo-ms-tab-content-button" name="mo_sps_site_refresh_button" onclick="document.getElementById('mo_sps_site_refresh').submit();">Refresh Now</button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr style="display:<?php echo isset($config['app_type']) && $config['app_type'] == 'auto' ? 'table-row' : 'none'; ?>">
                        <td class="left-div"></td>
                        <td class="right-div">
                            <div style="background: #eee;padding: 8px;align-items: center;">
                                <span>You can see the sites which have access to the user <?php echo $upn; ?> Once you gave permission to the user it will take approximate 5 minutes to get updated. </span>
                                <div>
                        </td>
                    </tr><?php } ?>
                    <tr>
                        <td class="left-div"><span style="font-weight:400;font-size:13px;">Select Drive<span style="color:red;font-weight:bold;">*</span></span></td>
                        <td class="right-div" id="mo_sps_drive_select_td">
                            <div id="mo_sps_select_drive_loader" style="display:none;justify-content:center;align-items:center;">
                                <span><img width="40px" height="40px" src="<?php echo esc_url(plugin_dir_url(__FILE__) . '../images/loader.gif'); ?>"></span>
                            </div>
                            <div id="mo_sps_select_drive">
                                <select id="mo_sps_drive_select" style="width: 50%;">
                                    <option selected disabled value="">--select a drive--</option>
                                    <?php
                                    foreach ($drives as $drive) { ?>
                                        <option <?php echo (($selected_drive == $drive['id']) ? 'selected' : ''); ?> value="<?php echo $drive['name']; ?>" data-id="<?php echo $drive['id'] ?>"><?php echo $drive['name']; ?></option>
                                    <?php }
                                    ?>
                                </select>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="left-div"><span style="font-weight:400;font-size:13px;">Copy Shortcode</span></td>
                        <td>
                            <div style="background-color:#eee;display:flex;align-items:center;">
                                <span style="width:99%;margin-left:1rem;" id="mo_copy_shortcode">[MO_SPS_SHAREPOINT width="100%" height="800px"]</span>
                                <div style="margin-left:3px;"><button type="button" class="mo_copy copytooltip rounded-circle float-end" style="background-color:#eee;width:40px;height:40px;margin-top:0px;border-radius:100%;border:0 solid;"><img style="width:25px;height:25px;margin-top:0px;margin-left:0px;" src="<?php echo esc_url(plugin_dir_url(__FILE__) . '../images/copy.png'); ?>" onclick="copyToClipboard(this, '#mo_copy_shortcode', '#copy_shortcode');"><span id="copy_shortcode" class="copytooltiptext">Copy to Clipboard</span></button></div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    <?php
    }

    public function mo_sps_display__tab_shortcode_details($config)
    {
        $app = wpWrapper::mo_sps_get_option(pluginConstants::APP_CONFIG);
        $selected_site = wpWrapper::mo_sps_get_option(pluginConstants::SPS_SEL_SITE);
        $selected_site = isset($selected_site) && $selected_site ? $selected_site : '';
        $selected_drive = wpWrapper::mo_sps_get_option(pluginConstants::SPS_SEL_DRIVE);
        $selected_drive = isset($selected_drive) && $selected_drive ? $selected_drive : '';
        $selected_drive_name = wpWrapper::mo_sps_get_option(pluginConstants::SPS_SEL_DRIVE_NAME);
        $selected_drive_name = isset($selected_drive_name) && $selected_drive_name ? $selected_drive_name : '';
        $app_type = isset($app['app_type']) ? $app['app_type'] : 'manual';

        $connector = get_option(pluginConstants::CLOUD_CONNECTOR);
        if($connector == 'sharepoint'){
            if($selected_site == '' || $selected_drive == '') {
                return;
        }} else if($selected_drive == '') {return;}

        $document_sync_metdata = [
            'admin_ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('mo_doc_embed__nonce'),
            'tab' => isset($_REQUEST['tab']) ? $_REQUEST['tab'] : '',
            'folder_icon_url' => esc_url(plugin_dir_url(__FILE__) . '../images/folder.svg'),
            'fetch' => isset($_REQUEST['fetch']) ? $_REQUEST['fetch'] : '1',
            'selected_site' => $selected_site,
            'selected_drive' => $selected_drive,
            'selected_drive_name' => $selected_drive_name,
            'is_plugin' => 'n',
            'connector' => $connector,
            'app_type' => $app_type,
            'mime_types' => pluginConstants::MIME_TYPES,
            'load_icon' => esc_url(plugin_dir_url(__FILE__) . '../images/Chasing_arrows.gif'),
            'file_icon' => esc_url(plugin_dir_url(__FILE__) . '../images/file.png'),
            'worddoc_icon' => esc_url(plugin_dir_url(__FILE__) . '../images/msword_file.png'),
            'exceldoc_icon' => esc_url(plugin_dir_url(__FILE__) . '../images/msexcel_file.png'),
            'pdfdoc_icon' => esc_url(plugin_dir_url(__FILE__) . '../images/pdf_file.png'),
            'emptyFolderDrop_icon' => esc_url(plugin_dir_url(__FILE__) . '../images/empty_folder_drop.svg'),
            'download' => esc_url(plugin_dir_url(__FILE__) . '../images/download.svg'),
            'redirect' => esc_url(plugin_dir_url(__FILE__) . '../images/redirect.svg'),
        ];
    ?>

        <div id="scrollto" class="mo-ms-tab-content_sc">
            <div>
                <div>
                    <?php
                    if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'elementor') {
                    
                    }
                    $this->mo_sps_display_sync_documents($config, true);
                    ?>
                    <script>
                        const element = document.getElementById("scrollto");
                        element.scrollIntoView();
                    </script>
                    <?php
                    $sync_js_url = plugins_url('../includes/js/ajax.js', __FILE__);
                    wp_enqueue_script('mo_sps_sync_ajax_js', $sync_js_url, array(), PLUGIN_VERSION,true);
                    wp_add_inline_script('mo_sps_sync_ajax_js', 'var doc_sync_data='.json_encode($document_sync_metdata).';','before');
                    ?>
                </div>
            </div>
        </div>
    <?php
    }

    public function mo_sps_feature()
    {
    ?>
        <div class="mo-ms-tab-content-tile" style="width:135%;padding: 1rem;background: #f4f4f4;border: 4px solid #A6DEE0;border-radius: 5px;margin-top:0px !important;padding-top:0px !important;">
            <div class="mo-ms-tab-content-tile-content mo-sps-prem-info" style="position:relative;">
                <span style="font-size: 18px;font-weight: 500;">
                    Access Sharepoint Documents from media library
                    <sup style="font-size: 12px;color:red;font-weight:600;">
                        [Available in <a target="_blank" href="https://plugins.miniorange.com/microsoft-sharepoint-wordpress-integration#pricing-cards" style="color:red;">Paid</a> Plugins]
                    </sup>
                </span>
                <div class="mo-sps-prem-lock" style="top:2px;right:2px;position:absolute;">
                    <img class="filter-green" src="<?php echo esc_url(plugin_dir_url(__FILE__) . '../images/lock.svg'); ?>">
                    <p class="mo-sps-prem-text">Available in <a target="_blank" href="https://plugins.miniorange.com/microsoft-sharepoint-wordpress-integration#pricing-cards" style="color:#ffeb00;">Paid</a> plugins.</p>
                </div>
                <div id="basic_attr_access_desc" class="mo_sps_help_desc">
                    <span><b>You can access all your SharePoint online files and folders from media library.</b>
                    </span>
                </div>
                <table class="mo-ms-tab-content-app-config-table">
                    <tr>
                        <td style="width:45%;word-break: break-all;"><span>
                                <h4>Enable to access SharePoint Documents to media library</h4>
                            </span></td>
                        <td class="right-div">
                            <label class="switch">
                                <input type="checkbox" disabled>
                                <span class="slider round"></span>
                            </label>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    <?php
    }

    public function mo_sps_display_sync_documents($config, $is_shortcode)
    {
        $selected_site = wpWrapper::mo_sps_get_option(pluginConstants::SPS_SEL_SITE);
        $selected_site = isset($selected_site) && $selected_site ? $selected_site : '';
        $selected_drive = wpWrapper::mo_sps_get_option(pluginConstants::SPS_SEL_DRIVE);
        $selected_drive = isset($selected_drive) && $selected_drive ? $selected_drive : '';

        $disabled = ($selected_drive && $selected_site) ? '' : 'disabled';
        $pointer_events = ($selected_drive && $selected_site) ? '' : 'none';

        wp_enqueue_style('mo_sps_doc_embed_css', plugins_url('../includes/css/doc-embed.css', __FILE__), array(), PLUGIN_VERSION);
        wp_enqueue_style('mo_sps_css_plugin', plugins_url('../includes/css/mo_sps_settings.css', __FILE__), array(), PLUGIN_VERSION);

        $doc_embed_container_css = $is_shortcode ? 'width:'.$config['width'].' !important;max-width:'.$config['width'].' !important;overflow-x:scroll;':'width:135%';

            if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'elementor_ajax') {
                ?>
                <div style="background: #27dbc1;border: 2px solid #1d9084;padding: 0.2rem;margin-bottom: 0.4rem;">
                    Shortcode might not load as expected while editing the content using Page builders like Elementor. You can verify by using the WordPress Page Preview option or by publishing.
                </div>
            <?php
            } 

            if (false !== get_option("mo_sps_refresh_token") || false !== get_option("mo_sps_test_connection_status")) {
        ?>
        <form id="wp_save_user_form" method="post" name="wp_save_user_form">
            
            <div <?php echo $disabled; ?> id="table_box" class=<?php echo ($is_shortcode ? "mo-ms-tab-content-tile_sc" : "mo-ms-tab-content-tile"); ?> style="<?php echo $doc_embed_container_css; ?>">
                <div class="mo-ms-tab-content-tile-content">
                    <div>
                        <div style="display:flex;align-items: center;justify-content: space-between;">
                            <div id="root_button" style="display:none;vertical-align:middle;"></div>
                            <div id='mo_sps_breadcrumb' class="mo_sps_doc_breadcrumbs">
                            </div>
                            <style>
                                #reports_table tbody {
                                    display: block;
                                    max-height: <?php echo esc_html($config['height']); ?>;
                                    max-width: <?php echo esc_html($config['width']); ?>;
                                    overflow-y: auto;
                                }
                                #reports_table thead,
                                #reports_table tbody tr {
                                    display: table;

                                    width: <?php echo esc_html($config['width']); ?>;
                                    table-layout: fixed;
                                }
                            </style>
                            <script>
                                function exit_file_search(id, element) {
                                    document.getElementById('file_search').value = "";
                                }
                            </script>
                            <div style="display:flex; align-items:center;">
                            <div id="mySearch">
                                <div id="mySearch_div">
                                    <div style="display: flex; align-items: center; flex-direction: row-reverse;">
                                        <button class="mo_sps_search_button" type="button" id="mo_sps_search_button">
                                            <span class="dashicons dashicons-search" id="searchIcon"></span>
                                        </button>
                                        <button style="display:none;" class="mo_sps_exit_button" type="button" id="mo_sps_exit_button">
                                            <span class="dashicons dashicons-no-alt"></span>
                                        </button>
                                        <input class="mo_sps_file_search" role="combobox" autocomplete="off" placeholder="Search this library" type="search" id="mo_sps_file_search" name="mo_file_search" style="opacity: 0; width: 0; padding: 0; transition: all 0.5s; border: 0; outline: none;">
                                    </div>
                                    <div id="mySearchDropdown" class="search_div" >
                                        <div id="searching_div" style="display:flex;align-items:center;">
                                            <div class="before_search" style="font-weight:600;font-size:1rem;width:20rem;margin-bottom:10px;">Searching...</div>
                                            <img id="mySearchLoader" src="<?php echo esc_url(plugin_dir_url(__FILE__).'../images/Chasing_arrows.gif');?>">
                                        </div>
                                        <div class="list_div">
                                            <ul id="listItems" style="padding-left:0;margin:0;">
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div <?php echo $disabled; ?> class="refresh" style="position:relative;">
                                <a style="pointer-events:<?php echo $pointer_events; ?>;display:flex;align-items:center;" id="mo_sps_doc_refresh" href=""><img style="width:20px;height:20px;" src="<?php echo esc_url(plugin_dir_url(__FILE__) . '../images/refresh.svg'); ?>"></a>
                                <p class="mo-refresh-content">Click here to refresh and fetch all current documents from sharepoint</p>
                            </div>
                            </div>
                        </div>
                        <div style="padding-bottom:20px;"></div>
                        <div id="error_occured" style="display:block;text-align:center;display:none;">
                            <h2 class="heading-3-red">No item match your search</h2>
                        </div>
                        <div id="tableTOScroll">
                            <div id="mo_sps_all_errors" style="display:none;">

                            </div>
                            <div id="mo_sps_site_drive_not_selected" class="mo_sps_table_loader_div" style="display:none">
                                <div><img style="width:35px;height:35px;display:flex;align-items:center;display: block;margin-left: auto;margin-right:auto;" src="<?php echo esc_url(plugin_dir_url(__FILE__) . '../images/error.svg'); ?>"></div>
                                &nbsp;&nbsp;
                                <div>Incomplete Configuration.</div>
                                <div>Seems like you have not selected <b>Site</b> or <b>Drive</b> yet. Please select site and drive from above dropdown.</div>
                            </div>
                            <div id="mo_sps_drive_not_selected" class="mo_sps_table_loader_div" style="display:none">
                                <div><img style="width:35px;height:35px;display:flex;align-items:center;display: block;margin-left: auto;margin-right:auto;" src="<?php echo esc_url(plugin_dir_url(__FILE__) . '../images/error.svg'); ?>"></div>
                                <div>Incomplete Configuration.</div>
                                &nbsp;&nbsp;
                                <div>Seems like you have not selected <b>Drive</b> yet. Please select the drive from above dropdown.</div>
                            </div>
                            <table id="mo_sps_doc_table" class="wp-list-table widefat fixed mo_sps_doc_table">
                                <colgroup>
                                    <col style="width:60%;text-align:left;">
                                    <col style="width:25%;text-align:center;">
                                    <col style="width:15%;text-align:center;">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <td class="mo_sps_table_thead_td">Name</td>
                                        <td class="mo_sps_table_thead_td">Last Modified</td>
                                        <td class="mo_sps_table_thead_td">Size</td>
                                    </tr>
                                </thead>
                                <tbody id="mo_sps_table_tbody" class="mo_sps_table_tbody">
                                </tbody>
                                <tbody id="mo_sps_table_tbody_loader" class="mo_sps_table_tbody_tbody" style="display:none">
                                    <tr>
                                        <td colspan="4">
                                            <div class="mo_sps_table_loader_div">
                                                <img style="width:35px;height:35px;display:flex;align-items:center;display: block;margin-left: auto;margin-right:auto;" src="<?php echo esc_url(plugin_dir_url(__FILE__) . '../images/Chasing_arrows.gif'); ?>">
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <?php
        }
    }
}
