<?php

class WShop_Reward_Fields extends Abstract_XH_WShop_Fields {

    private static $_instance = null;
    public $_post_content = false;
    public $_post_edit = false;
    public $_post_request = array();

    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * post 设置区域
     */
    protected function __construct() {
        parent::__construct();
        $this->id = "reward_view";
        $this->title = '打赏设置';
    }

    /**
     * @see Abstract_WShop_Settings::init_form_fields()
     */
    public function init_form_fields() {
        global $post;
        $this->form_fields = apply_filters('wshop_pay_per_view_fields', array(
            'contents' => array(
                'title' => '打赏',
                'type' => 'custom',
                'func' => function ($key, $api, $data) {
                    ?>
                    <tr valign="top" class="">
                        <th scope="row" class="titledesc">
                            <label>提示</label>
                        </th>
                        <td class="forminp">
                            <fieldset>
                                <legend class="screen-reader-text">
                                    <span>提示</span>
                                </legend>
                                <p class="description">
                                    须将打赏短码插入文章后才会生效!
                                    <a href="javascript:void(0);" onclick="window.wshop_post_editor.reward_code();"><code>[wshop_reward]</code></a>
                                    <a class="wshop-btn-insert" href="javascript:void(0);"
                                       onclick="window.wshop_post_editor.reward_code();"><?php echo __('Insert into post content', WSHOP) ?></a>
                                    <script type="text/javascript">
                                        jQuery(function ($) {
                                            if (!window.wshop_post_editor) {
                                                window.wshop_post_editor = {};
                                            }
                                            window.wshop_post_editor.reward_code = function () {
                                                var text = '[wshop_reward]';
                                                window.wshop_post_editor.add_content(text);
                                            };
                                        });
                                    </script>
                                </p>
                            </fieldset>
                        </td>
                    </tr>
                    <?php
                })
        ), $post);
    }

    public function get_post_types() {
        $post_types = WShop_Add_On_Reward::instance()->get_option('post_types');

        global $wp_post_types;
        $types = array();
        if ($post_types && $wp_post_types) {
            foreach ($wp_post_types as $key => $type) {
                if (!in_array($key, $post_types)) {
                    continue;
                }
                if ($type->show_ui && $type->public) {
                    $types[$type->name] = (empty($type->label) ? $type->name : $type->label) . '(' . $type->name . ')';
                }
            }
        }

        return apply_filters('wshop_reward_post_types', $types);
    }

    public function get_object($post) {
        return null;
    }
}