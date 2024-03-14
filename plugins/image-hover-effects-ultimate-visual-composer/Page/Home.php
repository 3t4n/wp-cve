<?php

namespace OXI_FLIP_BOX_PLUGINS\Page;

/**
 * Description of Home
 *
 * @author biplo
 */
class Home
{

    /**
     * Database Parent Table
     *
     * @since 3.1.0
     */
    public $parent_table;

    /**
     * Database Import Table
     *
     * @since 3.1.0
     */
    public $child_table;

    /**
     * Database Import Table
     *
     * @since 3.1.0
     */
    public $import_table;

    /**
     * Define $wpdb
     *
     * @since 3.1.0
     */
    public $wpdb;

    use \OXI_FLIP_BOX_PLUGINS\Inc_Helper\Public_Helper;
    use \OXI_FLIP_BOX_PLUGINS\Inc_Helper\CSS_JS_Loader;



    

    public function manual_import_json()
    {
        if (!empty($_REQUEST['_wpnonce'])) {
            $nonce = $_REQUEST['_wpnonce'];
        }

        if (!empty($_POST['importdatasubmit']) && sanitize_text_field($_POST['importdatasubmit']) == 'Save') {
            if (!wp_verify_nonce($nonce, 'oxilab-flipbox-import')) {
                die('You do not have sufficient permissions to access this page.');
            } else {
                if (isset($_FILES['importoxilabflipboxfile'])) :

                    if (!current_user_can('upload_files')) :
                        wp_die(esc_html('You do not have permission to upload files.'));
                    endif;

                    $allowedMimes = array(
                        'json' => 'text/plain'
                    );

                    $fileInfo = wp_check_filetype(basename($_FILES['importoxilabflipboxfile']['name']), $allowedMimes);
                    if (empty($fileInfo['ext'])) {
                        wp_die(esc_html('You do not have permission to upload files.'));
                    }

                    $content = json_decode(file_get_contents($_FILES['importoxilabflipboxfile']['tmp_name']), true);

                    if (empty($content)) {
                        return new \WP_Error('file_error', 'Invalid File');
                    }
                    $style = $content['style'];

                    if (!is_array($style) || $style['type'] != 'flip') {
                        return new \WP_Error('file_error', 'Invalid Content In File');
                    }

                    $FlipboxApi = new \OXI_FLIP_BOX_PLUGINS\Classes\Admin_Ajax;
                    $new_slug = $FlipboxApi->post_json_import($content);

                    echo '<script type="text/javascript"> document.location.href = "' . $new_slug . '"; </script>';
                    exit;
                endif;
            }
        }
    }

    /**
     * Constructor of Oxilab tabs Home Page
     *
     * @since 2.0.0
     */
    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->parent_table = $this->wpdb->prefix . 'oxi_div_style';
        $this->child_table = $this->wpdb->prefix . 'oxi_div_list';
        $this->import_table = $this->wpdb->prefix . 'oxi_div_import';
        $this->CSSJS_load();
        $this->Render();
    }

    public function database_data()
    {
        return $this->wpdb->get_results($this->wpdb->prepare("SELECT * FROM  $this->parent_table WHERE type = %s ", 'flip'), ARRAY_A);
    }

    public function CSSJS_load()
    {
        $this->manual_import_json();
        $this->admin_css_loader();
        $this->admin_home();
        $this->admin_ajax_load();
        apply_filters('oxi-flip-box-plugin/admin_menu', TRUE);
    }
    private function create_export_link($rawdata = '', $shortcode_id = '', $child_id = '')
    {
        return add_query_arg(
            [
                'action' => 'oxi_flip_box_data',
                'functionname' => 'get_shortcode_export',
                'styleid' => $shortcode_id,
                'childid' => $child_id,
                'rawdata' => $rawdata,
                '_wpnonce' => wp_create_nonce('oxi-flip-box-editor'),
            ],
            admin_url('admin-ajax.php')
        );
    }

    public function created_shortcode()
    {
?>
        <div class="oxi-addons-row">
            <div class="oxi-addons-row table-responsive abop" style="margin-bottom: 20px; opacity: 0; height: 0px">
                <table class="table table-hover widefat oxi_addons_table_data" style="background-color: #fff; border: 1px solid #ccc">
                    <thead>
                        <tr>
                            <th style="width: 5%">ID</th>
                            <th style="width: 15%">Name</th>
                            <th style="width: 10%">Templates</th>
                            <th style="width: 30%">Shortcode</th>
                            <th style="width: 40%">Edit Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($this->database_data() as $value) {
                            $id = $value['id'];
                        ?>
                            <tr>
                                <td><?php echo esc_html($id); ?></td>
                                <td><?php echo esc_html($this->name_converter($value['name'])) ?></td>
                                <td><?php echo esc_html($this->name_converter($value['style_name'])) ?></td>
                                <td><span>Shortcode &nbsp;&nbsp;<input type="text" onclick="this.setSelectionRange(0, this.value.length)" value="[oxilab_flip_box id=&quot;<?php echo esc_attr($id); ?>&quot;]"></span> <br>
                                    <span>Php Code &nbsp;&nbsp; <input type="text" onclick="this.setSelectionRange(0, this.value.length)" value="&lt;?php echo do_shortcode(&#039;[oxilab_flip_box  id=&quot;<?php echo esc_attr($id); ?>&quot;]&#039;); ?&gt;"></span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-success oxi-addons-style-clone" style="float:left" oxiaddonsdataid="<?php echo esc_attr($id); ?>">Clone</button>
                                    <a href="<?php echo esc_url(admin_url("admin.php?page=oxi-flip-box-ultimate-new&styleid=$id")); ?>" title="Edit" class="btn btn-info" style="float:left; margin-right: 5px; margin-left: 5px;">Edit</a>
                                    <form method="post" class="oxi-addons-style-delete">
                                        <input type="hidden" name="oxideleteid" id="oxideleteid" value="<?php echo esc_attr($id); ?>">
                                        <button class="btn btn-danger" style="float:left" title="Delete" type="submit" value="delete" name="addonsdatadelete">Delete</button>
                                    </form>
                                    <a href="<?php echo esc_url($this->create_export_link('demo', $id, '')) ?>" title="Export" class="btn btn-info" style="float:left; margin-left: 5px;">Export</a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <br>
            <br>
        </div>
    <?php
    }

    /**
     * Admin Notice JS file loader
     * @return void
     */
    public function admin_ajax_load()
    {
        wp_enqueue_script('oxi-flip-box-home', OXI_FLIP_BOX_URL . 'asset/backend/js/home.js', false, OXI_FLIP_BOX_TEXTDOMAIN);
        wp_localize_script('oxi-flip-box-home', 'oxi_flip_box_editor', array('ajaxurl' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('oxi-flip-box-editor')));
    }

    /**
     * Generate safe path
     * @since v1.0.0
     */
    public function safe_path($path)
    {

        $path = str_replace(['//', '\\\\'], ['/', '\\'], $path);
        return str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
    }

    public function Render()
    {
    ?>
        <div class="oxi-addons-row">
            <?php
            $this->Admin_header();
            $this->created_shortcode();
            $this->create_new();
            ?>
        </div>
    <?php
    }

    public function Admin_header()
    {
        apply_filters('oxi-flip-box-support-and-comments', TRUE);
    ?>
        <div class="oxi-addons-wrapper">
            <div class="oxi-addons-import-layouts">
                <h1>Flipbox › Home
                </h1>
                <p> Collect Flipbox Shortcode, Edit, Delect, Clone or Export it.</p>
            </div>
        </div>
    <?php
    }

    public function create_new()
    {
    ?>
        <div class="oxi-addons-row">
            <div class="oxi-addons-col-1 oxi-import">
                <div class="oxi-addons-style-preview">
                    <div class="oxilab-admin-style-preview-top">
                        <a href="#" id="oxi-import-style">
                            <div class="oxilab-admin-add-new-item">
                                <span>
                                    <i class="fas fa-plus-circle oxi-icons"></i>
                                    Import Flipbox JSON
                                </span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="oxi-addons-style-create-modal">
            <form method="post" id="oxi-addons-style-modal-form">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">FlipBox Clone</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class=" form-group row">
                                <label for="addons-style-name" class="col-sm-6 col-form-label" oxi-addons-tooltip="Give your Shortcode Name Here">Name</label>
                                <div class="col-sm-6 addons-dtm-laptop-lock">
                                    <input class="form-control" type="text" value="" id="addons-style-name" name="addons-style-name">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" id="oxistyleid" name="oxistyleid" value="">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-success" name="addonsdatasubmit" id="addonsdatasubmit" value="Save">
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal fade" id="oxi-addons-style-import-modal">
            <form method="post" id="oxi-addons-import-modal-form" enctype="multipart/form-data">
                <div class="modal-dialog modal-sm modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Import Form</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <input class="form-control" type="file" name="importoxilabflipboxfile" accept=".json,application/json,.zip,application/octet-stream,application/zip,application/x-zip,application/x-zip-compressed">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-success" name="importdatasubmit" id="importdatasubmit" value="Save">
                        </div>
                    </div>
                </div>
                <?php wp_nonce_field("oxilab-flipbox-import") ?>
            </form>
        </div>
<?php
    }
}
