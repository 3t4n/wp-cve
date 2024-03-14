<?php

namespace OXI_FLIP_BOX_PLUGINS\Page;

/**
 * Description of Public
 *
 * @author biplo
 */
class Public_Render
{

    /**
     * Current Elements id
     *
     * @since 2.0.0
     */
    public $oxiid;

    /**
     * Current Elements Style Data
     *
     * @since 2.0.0
     */
    public $style = [];

    /**
     * Current Elements Style Full
     *
     * @since 2.0.0
     */
    public $dbdata = [];

    /**
     * Current Elements multiple list data
     *
     * @since 2.0.0
     */
    public $child = [];

    /**
     * Current Elements Global CSS Data
     *
     * @since 2.0.0
     */
    public $CSSDATA = [];

    /**
     * Current Elements Global CSS Data
     *
     * @since 2.0.0
     */
    public $inline_css;
    public $inline_js;

    /**
     * Current Elements Global JS Handle
     *
     * @since 2.0.0
     */
    public $JSHANDLE = 'flipbox-addons-jquery';

    /**
     * Current Elements Global DATA WRAPPER
     *
     * @since 2.0.0
     */
    public $WRAPPER;

    /**
     * Current Elements Admin Control
     *
     * @since 2.0.0
     */
    public $admin;

    

    /**
     * old empty old render
     *
     * @since 2.0.0
     */
    public function default_render()
    {
        echo '';
    }

    public function font_familly($data = '')
    {

        $check = get_option('oxi_addons_google_font');

        $custom = [
            'Arial' => '',
            'Helvetica+Neue' => '',
            'Courier+New' => '',
            'Times+New+Roman' => '',
            'Comic+Sans+MS' => '',
            'Verdana' => '',
            'Impact' => '',
            'cursive' => '',
            'inherit' => ''
        ];
        if ($check != 'no' && !array_key_exists($data, $custom)) :
            wp_enqueue_style('' . $data . '', 'https://fonts.googleapis.com/css?family=' . $data . '');
        endif;
        $data = str_replace('+', ' ', $data);
        $data = explode(':', $data);
        return '"' . esc_attr($data[0]) . '"';
    }

    public function admin_name_validation($data)
    {
        $data = str_replace('_', ' ', $data);
        $data = str_replace('-', ' ', $data);
        $data = str_replace('+', ' ', $data);
        return ucwords($data);
    }

    public function name_converter($data)
    {
        $data = str_replace('_', ' ', $data);
        $data = str_replace('-', ' ', $data);
        $data = str_replace('+', ' ', $data);
        return ucwords($data);
    }

    public function text_render($data)
    {
        echo wp_kses_post(do_shortcode(str_replace('spTac', '&nbsp;', str_replace('spBac', '<br>', html_entity_decode($data))), $ignore_html = false));
    }

    public function font_awesome_render($data)
    {
        $fadata = get_option('oxi_addons_font_awesome');
        if ($fadata != 'no') :
            wp_enqueue_style('font-awsome.min', OXI_FLIP_BOX_URL . 'asset/frontend/css/font-awsome.min.css', false, OXI_FLIP_BOX_PLUGIN_VERSION);
        endif;
?>
        <i class="<?php echo esc_attr($data); ?> oxi-icons"></i>
        <?php
    }
    /**
     * load css and js hooks
     *
     * @since 2.0.0
     */
    public function hooks()
    {
        $this->public_loader();
        $inlinecss = $this->inline_css;

        if ($this->inline_js != '') :
            $jquery = '(function ($) {' . $this->inline_js . '})(jQuery);';
            wp_add_inline_script($this->JSHANDLE, $jquery);
        endif;

        if ($this->inline_css != '') :
            wp_add_inline_style('flip-box-addons-style', wp_kses_decode_entities(stripslashes($inlinecss)));
        endif;
    }



    /**
     * front end loader css and js
     *
     * @since 2.0.0
     */
    public function public_loader()
    {
        wp_enqueue_script("jquery");
        wp_enqueue_style('oxi-animation', OXI_FLIP_BOX_URL . 'asset/frontend/css/animation.css', false, OXI_FLIP_BOX_PLUGIN_VERSION);
        wp_enqueue_style('flip-box-addons-style', OXI_FLIP_BOX_URL . 'asset/frontend/css/style.css', false, OXI_FLIP_BOX_PLUGIN_VERSION);
        wp_enqueue_script('waypoints.min', OXI_FLIP_BOX_URL . 'asset/frontend/js/waypoints.min.js', false, OXI_FLIP_BOX_PLUGIN_VERSION);
        wp_enqueue_script('flipbox-addons-jquery', OXI_FLIP_BOX_URL . 'asset/frontend/js/jquery.js', false, OXI_FLIP_BOX_PLUGIN_VERSION);
    }

    /**
     * load current element render since 2.0.0
     *
     * @since 2.0.0
     */
    public function render()
    {
        echo '<div class="oxi-addons-container ' . esc_attr($this->WRAPPER) . '  oxi-addons-flipbox-template-' . esc_attr($this->dbdata['style_name']) . '">';
        $this->default_render($this->style, $this->child, $this->admin);
        echo '</div>';
    }

    public function admin_edit_panel($id)
    {

        if ($this->admin == 'admin') :
        ?>
            <div class="oxilab-admin-absulote">
                <div class="oxilab-style-absulate-edit">
                    <form method="post">
                        <input type="hidden" name="item-id" value="<?php echo esc_attr($id) ?>">
                        <button class="btn btn-primary" type="submit" value="edit" name="edit" title="Edit">Edit</button>
                        <?php echo wp_nonce_field("oxiflipeditdata") ?>
                    </form>
                </div>
                <div class="oxilab-style-absulate-delete">
                    <form method="post" class="oxilab-style-absulate-delete-confirmation">
                        <input type="hidden" name="item-id" value="<?php echo esc_attr($id) ?>">
                        <button class="btn btn-danger" type="submit" value="delete" name="delete" title="Delete">Delete</button>
                        <?php echo wp_nonce_field("oxiflipdeletedata") ?>
                    </form>
                </div>
            </div>
<?php
        endif;
    }

    /**
     * load constructor
     *
     * @since 2.0.0
     */
    public function __construct(array $dbdata = [], array $child = [], $admin = 'user')
    {
        if (count($dbdata) > 0) :
            $this->dbdata = $dbdata;
            $this->child = $child;
            $this->admin = $admin;
            $this->loader();
        endif;
    }

    /**
     * Current element loader
     *
     * @since 2.0.0
     */
    public function loader()
    {
        $this->oxiid = $this->dbdata['id'];
        foreach ($this->child as $key => $value) {
            $this->child[$key]['files'] = $value['files'] . '{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}{#}|{#}';
        }
        $this->render();
        $this->hooks();
    }
}
