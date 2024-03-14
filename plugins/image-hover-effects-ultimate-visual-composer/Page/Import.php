<?php

namespace OXI_FLIP_BOX_PLUGINS\Page;

/**
 * Description of Import
 *
 * @author biplo
 */
class Import
{

    use \OXI_FLIP_BOX_PLUGINS\Inc_Helper\Public_Helper;
    use \OXI_FLIP_BOX_PLUGINS\Inc_Helper\CSS_JS_Loader;

    public $IMPORT = [];
    public $wpdb;
    public $parent_table;
    public $child_table;
    public $import_table;
    public $TEMPLATE;


  

    /**
     * Admin Notice JS file loader
     * @return void
     */
    public function admin_ajax_load()
    {
        wp_enqueue_script('oxi-flip-import', OXI_FLIP_BOX_URL . 'asset/backend/js/import.js', false, OXI_FLIP_BOX_TEXTDOMAIN);
        wp_localize_script('oxi-flip-import', 'oxi_flip_box_editor', array('ajaxurl' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('oxi-flip-box-editor')));
    }
    public function template()
    {
    ?>
        <div class="oxi-addons-row">
            <?php
            foreach ($this->TEMPLATE as $k => $value) {
                $id = (int) explode('tyle', $k)[1];
                if (!array_key_exists($id, $this->IMPORT)) :
                    $C = 'OXI_FLIP_BOX_PLUGINS\Public_Render\\' . $k;
            ?>
                    <div class="oxi-addons-col-1" id="<?php echo esc_attr($k); ?>">
                        <div class="oxi-addons-style-preview">
                            <div class="oxi-addons-style-preview-top oxi-addons-center">
                                <?php
                                if (class_exists($C)) :
                                    foreach ($this->TEMPLATE[$k] as $key => $v) {
                                        $REND = json_decode($v, true);
                                        echo '<div class="oxilab-flip-box-col-3">';
                                        new $C($REND['style'], $REND['child']);
                                        echo '</div>';
                                    }
                                endif;
                                ?>

                            </div>
                            <div class="oxi-addons-style-preview-bottom">
                                <div class="oxi-addons-style-preview-bottom-left">
                                    Style <?php echo esc_attr($id); ?>
                                </div>
                                <div class="oxi-addons-style-preview-bottom-right">
                                    <?php
                                    $checking = apply_filters('oxi-flip-box-plugin/pro_version', true);
                                    if ($id > 10 && $checking == false) :
                                    ?>
                                        <form method="post" style=" display: inline-block; " class="shortcode-addons-template-pro-only">
                                            <button class="btn btn-warning oxi-addons-addons-style-btn-warning" title="Pro Only" type="submit" value="pro only" name="addonsstyleproonly">Pro Only</button>
                                        </form>
                                    <?php
                                    else :
                                    ?>
                                        <form method="post" style=" display: inline-block; " class="shortcode-addons-template-import">
                                            <input type="hidden" name="oxiimportstyle" value="<?php echo esc_attr($id); ?>">
                                            <button class="btn btn-success oxi-addons-addons-template-create" title="import" type="submit" value="Import" name="addonsstyleimport">Import</button>
                                        </form>
                                    <?php
                                    endif;
                                    ?>
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

    public function Admin_header()
    {
        apply_filters('oxi-flip-box-support-and-comments', TRUE);
?>
        <div class="oxi-addons-wrapper">
            <div class="oxi-addons-import-layouts">
                <h1>Flipbox › Import Template
                </h1>
                <p> Select Flip layouts and Import For Create Shortcode. </p>
            </div>
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
        $this->parent_table = $wpdb->prefix . 'oxi_div_style';
        $this->child_table = $wpdb->prefix . 'oxi_div_list';
        $this->import_table = $wpdb->prefix . 'oxi_div_import';
        $this->CSSJS_load();
        $this->Render();
    }

    public function Render()
    {
    ?>
        <div class="oxi-addons-row">
            <?php
            $this->Admin_header();
            $this->template();
            ?>
        </div>
    <?php
    }

    public function CSSJS_load()
    {
        $this->admin_css_loader();
        $this->admin_ajax_load();
        apply_filters('oxi-flip-box-plugin/admin_menu', TRUE);
        $import = $this->wpdb->get_results($this->wpdb->prepare("SELECT * FROM  $this->import_table WHERE type = %s ", 'flip'), ARRAY_A);
        foreach ($import as $value) {
            $this->IMPORT[$value['name']] = $value['name'];
        }
        $this->TEMPLATE = include OXI_FLIP_BOX_PATH . 'Page/JSON.php';
    }
}
