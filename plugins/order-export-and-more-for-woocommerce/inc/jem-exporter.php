<?php
if (!defined('ABSPATH')) {
    die('Do not open this file directly.');
}

require_once 'functions.php';


/**
 * @property false|string page
 */
class JEMEXP_lite
{

    /**
     * @var array
     */
    private $objects = array();
    /**
     * @var string|WP_Error
     */
    private $my_errors = '';
    private $settings;

    private $message = '';

    // Hold the URL of this page
    private $thisURL = '';
    /**
     * JEMEXP_lite constructor.
     */
    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_to_menu'));

        add_action('admin_enqueue_scripts', array(&$this, 'load_scripts'));
        add_action('admin_post_export_data', array(&$this, 'export_data'));

        // handles the form post for the SETTINGS
        add_action('wp_ajax_JEMEXP_save_settings', array(&$this, 'save_settings'));

        // AJAX data loading from the admin page
        add_action('wp_ajax_get_order_data', array($this, 'order_ajax_call'));

        // create the error object
        $this->my_errors = new WP_Error();

        // Save the url
        $this->thisURL = admin_url('admin.php?page=JEMEXP_MENU') . '&tab=schedule';
    }


    /**
     * Load up the stuff we need!
     */
    public function load_scripts()
    {
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_style('jquery-ui-datepicker');
        wp_enqueue_script('jquery-ui-dialog');
        wp_enqueue_script('jquery-ui-progressbar');
    }


    public function sanitize_array(&$array)
    {

        foreach ($array as &$value) {

            if (!is_array($value))
                // sanitize if value is not an array
                $value = sanitize_text_field($value);

            else
                // go inside this function again
                $this->sanitize_array($value);
        }

        return $array;
    }

    /**
     *  Handles & routes all the ajax calls from the Order export UI
     * the POST variable 'type' contains what kind of request this is
     */
    public function order_ajax_call()
    {
        check_ajax_referer('jemexp_saving_field');
        if (!current_user_can('administrator')) {
            wp_send_json_error(__('You are not allowed to run this action.', 'order-export-and-more-for-woocommerce'));
        }

        if (isset($_REQUEST['type'])) {

            $method = sanitize_text_field($_REQUEST['type']);

            if (method_exists('JEMEXP_Data_Engine', $method)) {

                $ajax_data = stripslashes_deep($this->sanitize_array($_REQUEST));
                $ajax = new JEMEXP_Data_Engine();
                $ret  = $ajax->$method($ajax_data);

                wp_send_json($ret);
            }
        }
        die();
    }
    /**
     * This puts us on the woo menu
     */
    public function add_to_menu()
    {
        $this->page = add_submenu_page(
            'woocommerce',
            __('JEM Order Export', 'order-export-and-more-for-woocommerce'),
            __('JEM Order Export', 'order-export-and-more-for-woocommerce'),
            'manage_woocommerce',
            'JEMEXP_MENU',
            array($this, 'render_settings')
        );
    }

    static function wp_kses_wf($html)
    {
        add_filter('safe_style_css', function ($styles) {
            $styles_wf = array(
                'text-align',
                'margin',
                'color',
                'float',
                'border',
                'background',
                'background-color',
                'border-bottom',
                'border-bottom-color',
                'border-bottom-style',
                'border-bottom-width',
                'border-collapse',
                'border-color',
                'border-left',
                'border-left-color',
                'border-left-style',
                'border-left-width',
                'border-right',
                'border-right-color',
                'border-right-style',
                'border-right-width',
                'border-spacing',
                'border-style',
                'border-top',
                'border-top-color',
                'border-top-style',
                'border-top-width',
                'border-width',
                'caption-side',
                'clear',
                'cursor',
                'direction',
                'font',
                'font-family',
                'font-size',
                'font-style',
                'font-variant',
                'font-weight',
                'height',
                'letter-spacing',
                'line-height',
                'margin-bottom',
                'margin-left',
                'margin-right',
                'margin-top',
                'overflow',
                'padding',
                'padding-bottom',
                'padding-left',
                'padding-right',
                'padding-top',
                'text-decoration',
                'text-indent',
                'vertical-align',
                'width',
                'display',
            );

            foreach ($styles_wf as $style_wf) {
                $styles[] = $style_wf;
            }
            return $styles;
        });

        $allowed_tags = wp_kses_allowed_html('post');
        $allowed_tags['input'] = array(
            'type' => true,
            'style' => true,
            'class' => true,
            'id' => true,
            'checked' => true,
            'disabled' => true,
            'name' => true,
            'size' => true,
            'placeholder' => true,
            'value' => true,
            'data-*' => true,
            'size' => true,
            'disabled' => true
        );

        $allowed_tags['textarea'] = array(
            'type' => true,
            'style' => true,
            'class' => true,
            'id' => true,
            'checked' => true,
            'disabled' => true,
            'name' => true,
            'size' => true,
            'placeholder' => true,
            'value' => true,
            'data-*' => true,
            'cols' => true,
            'rows' => true,
            'disabled' => true
        );

        $allowed_tags['select'] = array(
            'type' => true,
            'style' => true,
            'class' => true,
            'id' => true,
            'checked' => true,
            'disabled' => true,
            'name' => true,
            'size' => true,
            'placeholder' => true,
            'value' => true,
            'data-*' => true,
            'multiple' => true,
            'disabled' => true
        );

        $allowed_tags['option'] = array(
            'type' => true,
            'style' => true,
            'class' => true,
            'id' => true,
            'checked' => true,
            'disabled' => true,
            'name' => true,
            'size' => true,
            'placeholder' => true,
            'value' => true,
            'selected' => true,
            'data-*' => true
        );
        $allowed_tags['optgroup'] = array(
            'type' => true,
            'style' => true,
            'class' => true,
            'id' => true,
            'checked' => true,
            'disabled' => true,
            'name' => true,
            'size' => true,
            'placeholder' => true,
            'value' => true,
            'selected' => true,
            'data-*' => true,
            'label' => true
        );

        $allowed_tags['a'] = array(
            'href' => true,
            'data-*' => true,
            'class' => true,
            'style' => true,
            'id' => true,
            'target' => true,
            'data-*' => true,
            'role' => true,
            'aria-controls' => true,
            'aria-selected' => true,
            'disabled' => true
        );

        $allowed_tags['div'] = array(
            'style' => true,
            'class' => true,
            'id' => true,
            'data-*' => true,
            'role' => true,
            'aria-labelledby' => true,
            'value' => true,
            'aria-modal' => true,
            'tabindex' => true
        );

        $allowed_tags['li'] = array(
            'style' => true,
            'class' => true,
            'id' => true,
            'data-*' => true,
            'role' => true,
            'aria-labelledby' => true,
            'value' => true,
            'aria-modal' => true,
            'tabindex' => true
        );

        $allowed_tags['span'] = array(
            'style' => true,
            'class' => true,
            'id' => true,
            'data-*' => true,
            'aria-hidden' => true
        );

        $allowed_tags['form'] = array(
            'style' => true,
            'class' => true,
            'id' => true,
            'method' => true,
            'action' => true,
            'data-*' => true
        );

        echo wp_kses($html, $allowed_tags);

        add_filter('safe_style_css', function ($styles) {

            $styles_wf = array(
                'text-align',
                'margin',
                'color',
                'float',
                'border',
                'background',
                'background-color',
                'border-bottom',
                'border-bottom-color',
                'border-bottom-style',
                'border-bottom-width',
                'border-collapse',
                'border-color',
                'border-left',
                'border-left-color',
                'border-left-style',
                'border-left-width',
                'border-right',
                'border-right-color',
                'border-right-style',
                'border-right-width',
                'border-spacing',
                'border-style',
                'border-top',
                'border-top-color',
                'border-top-style',
                'border-top-width',
                'border-width',
                'caption-side',
                'clear',
                'cursor',
                'direction',
                'font',
                'font-family',
                'font-size',
                'font-style',
                'font-variant',
                'font-weight',
                'height',
                'letter-spacing',
                'line-height',
                'margin-bottom',
                'margin-left',
                'margin-right',
                'margin-top',
                'overflow',
                'padding',
                'padding-bottom',
                'padding-left',
                'padding-right',
                'padding-top',
                'text-decoration',
                'text-indent',
                'vertical-align',
                'width'
            );

            foreach ($styles_wf as $style_wf) {
                if (($key = array_search($style_wf, $styles)) !== false) {
                    unset($styles[$key]);
                }
            }
            return $styles;
        });
    }


    /**
     * This renders the main page for the plugin - all the front-end fun happens here!!
     */
    public function render_settings()
    {
        // get the main tab
        $tab = isset($_REQUEST['tab']) ? sanitize_text_field($_REQUEST['tab']) : 'export';

        // get the sub-tab
        $subTab = isset($_REQUEST['sub-tab']) ? sanitize_text_field($_REQUEST['sub-tab']) : 'fields';

        // are we editing an entity? if not default to Order
        // TODO we should prolly take this out
        $entity = isset($_REQUEST['entity']) ? sanitize_text_field($_REQUEST['entity']) : 'Order';

        // set the active tabs to blank
        $export_active   = '';
        $settings_active = '';
        $schedule_active = '';
        $meta_active     = '';

        $theContent = '';

        // For our template
        $adminPageURL = admin_url('admin.php?page=JEMEXP_MENU');

        // get the tab data for this tab
        switch ($tab) {
            case 'settings':
                $theContent      = $this->generate_settings_tab();
                $settings_active = 'in active';
                break;

            case 'schedule':
                $theContent      = $this->generate_schedule_tab();
                $schedule_active = 'in active';
                break;

                // default to export
            default:
                $theContent = $this->generate_export_tab();
                // $theContent = "stuff<br>stuff<br>";
                $export_active = 'in active';
                break;
        }

        // Any error messages

        // Our error message
        if (isset($this->message) && $this->message != '') {
            ob_start();
            include 'templates/error-message.php';
            $errorMessage = ob_get_clean();
        } else {
            $errorMessage = '';
        }

        // the main HTML for our page
        // The basic html for our page
        ob_start();
        include 'templates/main-wrapper.php';
        $html = ob_get_clean();

        JEMEXP_lite::wp_kses_wf($html);

        return;
    }

    /**
     * This generates the screen for the export tab
     */
    function generate_export_tab()
    {
        // Get the options
        $export_data = new JEMEXP_Export_Data();
        $export_data->load_export_data_from_options();

        // Instantiate our order object
        $order = new JEMEXP_Order($export_data);

        $html = $order->generate_export_html($this->settings);

        return $html;
    }


    /**
     * This generates the screen for the settings - HORIZONTAL tabs
     */
    function generate_settings_tab()
    {
        // output buffering
        ob_start();

        include_once 'templates/tab-settings.php';

        $html = ob_get_clean();

        return $html;
    }

    /**
     * This generates the screen for the scheduled exports - HORIZONTAL tabs
     * We are either showing a LIST of the schedule jobs
     * OR editing a SPECIFIC job.
     * We can also be editing - prolly not a great idea to overload one URL but good for now
     */
    function generate_schedule_tab()
    {

        $html = $this->generate_schedule_list();
        return $html;
    }

    /**
     * Generates the HTML for the main schedule tab
     *
     * @return string
     */
    function generate_schedule_list()
    {

        // generate the HTML for the schedule overview
        ob_start();
        include_once 'templates/schedule-main.php';

        $html = ob_get_clean();

        return $html;
    }

    /**
     * This generates the screen for the META VIEWER- HORIZONTAL tabs
     */
    function generate_meta_tab()
    {
        // ok so lets get the meta data for this id
        $meta_id   = isset($_REQUEST['meta-id']) ? sanitize_text_field($_REQUEST['meta-id']) : '';
        $meta_type = isset($_REQUEST['meta-type']) ? sanitize_text_field($_REQUEST['meta-type']) : '';

        $meta_data = get_post_meta($meta_id);

        // if it's empty then set a message
        if (count($meta_data) == 0) {
            $this->message = __('No meta data found for this item', 'order-export-and-more-for-woocommerce');
        }

        $html           = '';
        $line_item_html = '';
        // *******************
        // Is it a product?
        // *******************
        if ($meta_type == 'product') {
            // loop thru and display
            $html .= '<h2>Product Meta</h2>';

            foreach ($meta_data as $meta_name => $val) {

                if (count(maybe_unserialize($val)) == 1) {
                    $val = $val[0];
                }

                $val = maybe_unserialize($val);

                // is the val an array?
                if (is_array($val)) {
                    $html .= "<TR><TD style='width: 20%;'>{$meta_name}</TD><TD></TD></TR>";

                    foreach ($val as $child_name => $child_val) {
                        $html .= "<TR><TD>{$child_name}</TD><TD></TD></TR>";
                        // get it in a nice format
                        if (is_array(maybe_unserialize($child_val)) && count(maybe_unserialize($child_val)) == 1) {
                            $child_val = $child_val[0];
                        }

                        maybe_unserialize($child_val);

                        // possible for children to be arrays as well!!!
                        if (is_array($child_val)) {
                            foreach ($child_val as $grandchild_name => $grandchild_val) {
                                $html .= "<TR><TD>---{$grandchild_name}</TD><TD>{$grandchild_val}</TD></TR>";
                            }
                        } else {
                            $html .= "<TR><TD>---{$child_name}</TD><TD>{$child_val}</TD></TR>";
                        }
                    }
                } else {
                    $html .= "<TR><TD style='width: 20%;'>{$meta_name}</TD><TD>{$val}</TD></TR>";
                }
            }
        }

        // Is it an order?
        if ($meta_type == 'order') {
            // we need to get the meta and item meta

            // first Order Meta
            $html .= '<h2>Order Meta</h2>';

            $html .= jemxp_explode_meta_to_html($meta_data);

            // now we need to iterate thru the line items
            global $wpdb;

            $line_item_html  = '<h2>Order Line Item Meta</h2>';
            $order_items_sql = $wpdb->prepare('SELECT `order_item_id` as id, `order_item_name` as name, `order_item_type` as type FROM `' . $wpdb->prefix . 'woocommerce_order_items` WHERE `order_id` = %d', $meta_id);
            if ($order_items = $wpdb->get_results($order_items_sql)) {
                foreach ($order_items as $key => $order_item) {
                    $order_itemmeta_sql        = $wpdb->prepare('SELECT `meta_key`, `meta_value` FROM `' . $wpdb->prefix . 'woocommerce_order_itemmeta` AS order_itemmeta WHERE `order_item_id` = %d ORDER BY `order_itemmeta`.`meta_key` ASC', $order_item->id);
                    $order_items[$key]->meta = $wpdb->get_results($order_itemmeta_sql);
                }

                // ok we should now have a nice set of items/meta, meta
                foreach ($order_items as $item) {
                    $line_item_html .= "<TR><TD style='width: 20%;'>{$item->name}</TD><TD>{$item->type}</TD></TR>";

                    // if we have meta for item
                    if ($item->meta) {
                        foreach ($item->meta as $meta_val) {
                            $line_item_html .= "<TR><TD>---{$meta_val->meta_key}</TD><TD>{$meta_val->meta_value}</TD></TR>";
                        }
                    }
                }
            }
        }

        // Trying out output buffering
        ob_start();

        include_once 'templates/meta.php';

        $html = ob_get_clean();

        return $html;
    }


    /**
     * Custom usort function to sort the fieldlist by sort order
     *
     * @param $a
     * @param $b
     * @return int
     */
    function sortBySortOrder($a, $b)
    {
        // set defaults if they are not set
        if (!isset($a['sortOrder'])) {
            $a['sortOrder'] = 999;
        }

        if (!isset($b['sortOrder'])) {
            $b['sortOrder'] = 999;
        }

        return $a['sortOrder'] - $b['sortOrder'];
    }

    /**
     * Helper function checks if a radio field should be checked or not
     *
     * @return string
     */
    function ischecked($field, $value)
    {
        if ($field == $value) {
            return 'checked';
        }
    }


    /**
     * Helper function checks if a option field should be checked or not
     *
     * @param string $field
     * @param string $value
     * @return string
     */
    function isselected($field, $value)
    {
        if ($field == $value) {
            return 'selected';
        }
    }


    /**
     * This generates the box with the entity list
     */
    function generate_entity_list()
    {
        // first lets build the table of entities

        // loop thru the entities & build the table rows
        $html = '';
        foreach ($this->objects as $object) {
            $id = $object->id;

            $html .= '<tr><td width="300px"><input type="radio" class="checkbox-class" id="' . $id . '" value="' . $id . '" name="datatype">';
            $html .= '<label for="' . $id . '">' . $id . '</label></td></tr>';
        }

        $table = '<table><tbody>' . $html . '</tbody></table>';

        $html = '
<div id="export-type" class="postbox">
	<h3 class="hndle">Export Type</h3>
	<div class="inside">
		<p class="instructions">' . __('Select the data type you would like to export.', 'order-export-and-more-for-woocommerce') . '</p>' . $table . '
        <p class="instructions">Would you like additional Data Types? let us now what you need here: <a href="http://jem-products.com/contact-us/" target="_blank"> CONTACT US</a></p>
	</div>

</div>
		';

        return $html;
    }

    /**
     * This handles the form post from the settings tab - we make an ajax call to this
     * Called automagically from the admin_post action
     * We create the export object and have it save itself
     */
    function save_settings()
    {
        check_ajax_referer('jemexp_saving_field');
        if (!current_user_can('administrator')) {
            wp_send_json_error(__('You are not allowed to run this action.', 'order-export-and-more-for-woocommerce'));
        }

        // We simply save the object in a transient
        $jsonFix = stripcslashes(urldecode(sanitize_text_field($_POST['settings'])));

        $data = new JEMEXP_Export_Data();

        $settings = json_decode($jsonFix, true);
        $data->load_settings_from_array($settings['order_settings']);

        $fields_to_export = $settings['order_settings']['fields_to_export'];

        $result = true;

        $msg = '';

        if (!empty($fields_to_export)) {
            $result = true;
            $msg    = 'Your settings have been saved successfully';
        } else {
            $result = false;
            $msg    = 'You have not selected any fields to be exported.';
        }
        $result = array(
            'result'  => $result,
            'message' => $msg,
        );

        if ($result) {
            // save them
            $data->save_export_data_to_options();
        }

        wp_send_json($result);

        return;
    }


    // TODO does this even get called any more?
    /**
     *
     * This handles the export of the data
     * * gets called automagically by the submit of the form
     */
    function export_data()
    {
        // code for save labels
        // lets update any of the labels!
        // first get the entity we are edting
        $ent = (isset($_POST['entity-being-edited'])) ? sanitize_text_field($_POST['entity-being-edited']) : '';

        if ($ent === '') {
            // no entity being edited
            wp_safe_redirect(urldecode(sanitize_text_field($_POST['_wp_http_referer'])));
        }

        // the name of the labels
        $nm     = $ent . '_labels';
        $labels = (isset($_POST[$nm])) ? array_filter($this->sanitize_array($_POST[$nm])) : array();

        // And update we go
        update_option(JEMEXP_DOMAIN . '_' . $ent . '_labels', $labels);

        // load settings
        $this->settings = jemxp_get_settings();

        $output_fileName = $this->settings['filename'];

        // first get the entity we are exporting
        $ent = (isset($_POST['entity-to-export'])) ? sanitize_text_field($_POST['entity-to-export']) : '';

        if ($ent === '') {
            // no entity being edited
            wp_safe_redirect(urldecode(sanitize_text_field($_POST['_wp_http_referer'])));
            return;
        }

        // update the referrer with the entity & sub-tab if approp

        // Sub tab depnds on which submit buttion was pressed!
        $subTab = (isset($_POST['filter-override'])) ? sanitize_text_field($_POST['filter-override']) : '';
        if ($subTab != 'filters') {
            $subTab = 'fields';
        }

        $url = add_query_arg(
            array(
                'tab'     => 'export',
                'sub-tab' => $subTab,
                'entity'  => $ent,
            ),
            urldecode(sanitize_text_field($_POST['_wp_http_referer']))
        );

        // if no object redirects
        if (!isset($this->objects[$ent])) {

            // hmmmmm no entity exists - something screwey happened!

            wp_safe_redirect(urldecode($url));
            return;
        }

        // get the entity
        $obj = $this->objects[$ent];

        // lets get the field list to display and put it in the entity object
        $temp = $ent . '_fields';
        if (isset($_POST[$temp])) {
            $fields_to_export = $this->sanitize_array($_POST[$temp]);
        } else {
            // No fields to export so display an error message and return

            $this->save_admin_messages(__('You have not selected any fields to export', 'order-export-and-more-for-woocommerce'), 'error');

            wp_safe_redirect(urldecode($url));
            return;
        }

        $obj->fields_to_export = $fields_to_export;

        // if we have it, add in the meta, order product and order product meta
        if (isset($_POST[$ent . '_meta'])) {
            $obj->meta = $this->sanitize_array($_POST[$ent . '_meta']);
        }

        if (isset($_POST[$ent . '_product'])) {
            $obj->product = $this->sanitize_array($_POST[$ent . '_product']);
        }
        if (isset($_POST[$ent . '_item_meta'])) {
            $obj->item_meta = $this->sanitize_array($_POST[$ent . '_item_meta']);
        }

        // if we have it, add in the custom attributes
        if (isset($_POST[$ent . '_custom'])) {
            $obj->custom = $this->sanitize_array($_POST[$ent . '_custom']);
        }

        // load the user settings into the object
        $obj->settings = $this->settings;

        // lets get the appropriate filters for this entity
        $ret = $obj->extract_filters($this->sanitize_array($_POST));

        // did we get an error?
        if ($ret != '') {

            $this->save_admin_messages($ret, 'error');

            wp_safe_redirect(urldecode($url));
            return;
        }

        // create the file name - this is the name stored on our server
        $dir      = wp_upload_dir();
        $fileName = $dir['basedir'] . '/JEM_csv_export.csv';

        $file = fopen($fileName, 'w+');

        // if we have a time limit then lets use it
        if ($this->settings['timeLimit'] != '') {
            set_time_limit($this->settings['timeLimit']);
        }

        // ok we have an object - lets execute the darn query!
        $ret = $obj->run_query($file);

        // 1.4.5 - added this to close file - was causing rare issues
        fclose($file);

        if ($ret === false) {
            $this->save_admin_messages(__('No records were found - please modify the filters and try again', 'order-export-and-more-for-woocommerce'), 'error');

            fclose($file);
            // delete it
            $r = unlink($fileName);
            if (!$r) {
                // unlink failed
                $this->save_admin_messages(__('There was a problem deleting the temporary file - please try again', 'order-export-and-more-for-woocommerce'), 'error');
            }

            wp_safe_redirect(urldecode($url));
            return;
        }

        // rename the file to the format specified
        // now download the CSV file...

        if (file_exists($fileName)) {

            $output_fileName = str_replace('{{date}}', date('Y_m_d'), $output_fileName);
            $output_fileName = str_replace('{{time}}', date('H_i_s'), $output_fileName);
            $output_fileName = str_replace('{{type}}', $obj->id, $output_fileName);

            $file     = fopen($fileName, 'r');
            $contents = fread($file, filesize($fileName));
            $r        = fclose($file);

            // delete the file
            $r = unlink(realpath($fileName));

            if (!$r) {
                // unlink failed
                $this->save_admin_messages(__('There was a problem deleting the temporary file - please try again', 'order-export-and-more-for-woocommerce'), 'error');

                wp_safe_redirect(urldecode($url));
                return;
            }

            // funky headers!
            // TODO - put this in a function - need to work out how to handle non-western characters etc
            // http://www.andrew-kirkpatrick.com/2013/08/output-csv-straight-to-browser-using-php/ with some mods
            header('Expires: 0');
            header('Pragma: no-cache');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Cache-Control: private', false);
            header('Content-type: text/csv');
            header("Content-Disposition: attachment; filename=$output_fileName.csv");

            // now write it out
            $file = @fopen('php://output', 'w');
            fwrite($file, $contents);
            fclose($file);
        }
    }


    /**
     * Saves admion messages of a specific type, currently 'updated' or 'error'
     *
     * @param unknown $message
     * @param unknown $type
     */
    function save_admin_messages($message, $type = 'updated')
    {
        // add it to the trasnient queue

        $html = '
			<div id="message" class="' . $type . '">
			<p>' . $message . '</p>
			</div>
		';

        set_transient(JEMEXP_DOMAIN . '_messages', $html, MINUTE_IN_SECONDS);
    }


    /**
     * Prints any admin messages
     */
    function print_admin_messages()
    {
        $html = get_transient(JEMEXP_DOMAIN . '_messages');
        if ($html != false) {
            delete_transient(JEMEXP_DOMAIN . '_messages');
            JEMEXP_lite::wp_kses_wf($html);
        }
    }
}
