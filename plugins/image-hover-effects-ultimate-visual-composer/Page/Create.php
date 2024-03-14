<?php

namespace OXI_FLIP_BOX_PLUGINS\Page;

/**
 * Description of Create
 *
 * @author biplo
 */
class Create
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

    public $IMPORT = [];
    public $TEMPLATE;



    public function template()
    {
    ?>
        <div class="oxi-addons-row">
            <?php
            if (count($this->IMPORT) == 0) :
                $this->IMPORT = [
                    1 => ['type' => 'flip', 'name' => 1],
                    2 => ['type' => 'flip', 'name' => 2],
                    3 => ['type' => 'flip', 'name' => 3],
                    4 => ['type' => 'flip', 'name' => 4],
                    5 => ['type' => 'flip', 'name' => 5],
                ];
                foreach ($this->IMPORT as $value) {
                    $this->wpdb->query($this->wpdb->prepare("INSERT INTO {$this->import_table} (type, name) VALUES ( %s, %d)", array($value['type'], $value['name'])));
                }
            endif;

            foreach ($this->TEMPLATE as $key => $value) {
                $id = explode('tyle', $key)[1];
                $number = rand();
                if (array_key_exists($id, $this->IMPORT)) :
                    $C = 'OXI_FLIP_BOX_PLUGINS\Public_Render\\' . $key;
            ?>
                    <div class="oxi-addons-col-1" id="<?php echo esc_attr($key); ?>">
                        <div class="oxi-addons-style-preview">
                            <div class="oxi-addons-style-preview-top oxi-addons-center">
                                <?php
                                if (class_exists($C)) :
                                    foreach ($value as $k => $v) {
                                        $REND = json_decode($v, true);
                                        echo '<div class="oxilab-flip-box-col-3">';
                                        new $C($REND['style'], $REND['child']);
                                        echo '<textarea style="display:none" id="oxistyle' . esc_attr($number) . 'data-' . esc_attr($k) . '">' . htmlentities(json_encode($REND)) . '</textarea>';
                                        echo '</div>';
                                    }
                                endif;
                                ?>
                            </div>
                            <div class="oxi-addons-style-preview-bottom">
                                <div class="oxi-addons-style-preview-bottom-left">
                                    Style <?php echo esc_html($id); ?>
                                </div>
                                <div class="oxi-addons-style-preview-bottom-right">
                                    <form method="post" style=" display: inline-block; " class="shortcode-addons-template-deactive">
                                        <input type="hidden" name="oxideletestyle" value="<?php echo esc_attr($id); ?>">
                                        <button class="btn btn-warning oxi-addons-addons-style-btn-warning" title="Delete" type="submit" value="Deactive" name="addonsstyledelete">Deactive</button>
                                    </form>
                                    <button type="button" class="btn btn-success oxi-addons-addons-template-create" data-toggle="modal" addons-data="oxistyle<?php echo esc_attr($number); ?>data">Create Style</button>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php
                endif;
            }
            ?>
        </div>
    <?php
    }

    public function Render()
    {
    ?>
        <div class="oxi-addons-row">
            <?php
            $this->Admin_header();
            $this->template();
            $this->create_new();
            ?>
        </div>
<?php
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

    public function Admin_header()
    {
        apply_filters('oxi-flip-box-support-and-comments', TRUE);
?>
        <div class="oxi-addons-wrapper">
            <div class="oxi-addons-import-layouts">
                <h1>Flipbox › Create New
                </h1>
                <p> Select Flipbox layouts, Gives your Flipbox name and create new Flipbox. </p>
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
                        <a href="<?php echo esc_url(admin_url("admin.php?page=oxi-flip-box-ultimate-import")); ?>">
                            <div class="oxilab-admin-add-new-item">
                                <span>
                                    <i class="fas fa-plus-circle oxi-icons"></i>
                                    Import Templates
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
                            <h4 class="modal-title">New Flipbox</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class=" form-group row">
                                <label for="addons-style-name" class="col-sm-6 col-form-label" oxi-addons-tooltip="Give your Shortcode Name Here">Name</label>
                                <div class="col-sm-6 addons-dtm-laptop-lock">
                                    <input class="form-control" type="text" value="" id="addons-style-name" name="addons-style-name">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="oxi-tabs-link" class="col-sm-5 col-form-label" title="Select Layouts">Layouts</label>
                                <div class="col-sm-7">
                                    <div class="btn-group" data-toggle="buttons">
                                        <label class="btn btn-secondary active">
                                            <input type="radio" name="flip-box-layouts" value="1" checked="">1st
                                        </label>
                                        <label class="btn btn-secondary">
                                            <input type="radio" name="flip-box-layouts" value="2">2nd
                                        </label>
                                        <label class="btn btn-secondary">
                                            <input type="radio" name="flip-box-layouts" value="3">3rd
                                        </label>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" id="oxistyledata" name="oxistyledata" value="">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-success" name="addonsdatasubmit" id="addonsdatasubmit" value="Save">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    <?php
    }
    public function CSSJS_load()
    {
        $this->admin_css_loader();
        $this->admin_ajax_load();
        apply_filters('oxi-flip-box-plugin/admin_menu', TRUE);
        $i = $this->wpdb->get_results($this->wpdb->prepare("SELECT * FROM  $this->import_table WHERE type = %s", 'flip'), ARRAY_A);
        foreach ($i as $value) {
            $this->IMPORT[$value['name']] = $value;
        }
        $this->TEMPLATE = include OXI_FLIP_BOX_PATH . 'Page/JSON.php';
    }

    /**
     * Admin Notice JS file loader
     * @return void
     */
    public function admin_ajax_load()
    {
        wp_enqueue_script('oxi-flip-create', OXI_FLIP_BOX_URL . 'asset/backend/js/create.js', false, OXI_FLIP_BOX_TEXTDOMAIN);
        wp_localize_script('oxi-flip-create', 'oxi_flip_box_editor', array('ajaxurl' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('oxi-flip-box-editor')));
    }
}
