<?php

declare (strict_types=1);
namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Woocommerce\Form_Chain;

use Exception;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Abstract_Ilabs_Plugin;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Interfaces\Field_Ajax_Interface;
use WC_Admin_Settings;
use WC_Settings_Page;
class Wc_General_Settings_Child extends WC_Settings_Page
{
    static $prevent_duplicate = [];
    static $created = \false;
    /**
     * @var array
     */
    private $config;
    public function __construct(array $config, string $id, string $label)
    {
        $this->id = $id;
        $this->label = $label;
        $this->config = $config;
        parent::__construct();
        if (!self::$created) {
            add_filter('woocommerce_settings_tabs_array', [$this, 'add_settings_page'], 20);
            add_action('woocommerce_settings_' . $this->id, [$this, 'output']);
            add_action('woocommerce_settings_save_' . $this->id, [$this, 'save']);
        }
    }
    public function save()
    {
        $settings = $this->get_settings();
        WC_Admin_Settings::save_fields($settings);
    }
    public function output()
    {
        $settings = $this->get_settings();
        WC_Admin_Settings::output_fields($settings);
    }
    public function get_settings() : array
    {
        return $this->config;
    }
    /**
     *
     * @return array[]
     * @throws Exception
     */
    public static function integrate_simple_ajax(Field_Ajax_Interface $field) : array
    {
        $custom_type_id = Abstract_Ilabs_Plugin::$initial_instance->get_plugin_prefix() . '_basic_ajax_' . $field->get_id();
        add_action('woocommerce_admin_field_' . $custom_type_id, function (array $data) use($custom_type_id, $field) {
            $plugin_prefix = Abstract_Ilabs_Plugin::$initial_instance->get_plugin_prefix();
            $type = $data['type'];
            if (isset(self::$prevent_duplicate[$type]) && self::$prevent_duplicate[$type]) {
                return;
            }
            if ($custom_type_id === $type) {
                ?>
                    <label for="<?php 
                esc_attr_e($field->get_id());
                ?>_btn"><?php 
                esc_attr_e($field->get_desc());
                ?></label>
                    <button id="<?php 
                esc_attr_e($field->get_id());
                ?>_btn"
                            class="<?php 
                esc_attr_e($field->get_id());
                ?>">
						<?php 
                esc_attr_e($field->get_label());
                ?></button>
                    <br>
                    <span class="<?php 
                esc_attr_e($plugin_prefix);
                ?>-msg-success"
                          id="<?php 
                esc_attr_e($field->get_id());
                ?>-msg-success"></span>
                    <span class="<?php 
                esc_attr_e($plugin_prefix);
                ?>-msg-error"
                          id="<?php 
                esc_attr_e($field->get_id());
                ?>-msg-error"></span>

                    <script>


                        jQuery(document).ready(function () {
                            jQuery("#<?php 
                esc_attr_e($field->get_id());
                ?>_btn").click(function (e) {
                                e.preventDefault();

                                const data = {
                                    action: "<?php 
                esc_attr_e($field->get_id());
                ?>",
                                };

                                console.log("<?php 
                esc_attr_e($field->get_payload_group_id());
                ?>")

                                jQuery(['input', 'select', 'textarea'].map(e => e + '[name^="<?php 
                esc_attr_e($field->get_payload_group_id());
                ?>"]').join(',')).each(function () {
                                    data[jQuery(this).attr('name')] = jQuery(this).val();
                                });

                                jQuery.post(eclearSpot.ajaxurl, data, function (response) {
                                    if (response !== 0) {
                                        response = JSON.parse(response);
                                        console.log(response.status);
                                        if (response.status === 'ok') {
                                            jQuery('#<?php 
                esc_attr_e($field->get_id());
                ?>-msg-error').text("");
                                            jQuery('#<?php 
                esc_attr_e($field->get_id());
                ?>-msg-success').text(response.message);
                                        } else {
                                            jQuery('#<?php 
                esc_attr_e($field->get_id());
                ?>-msg-success').text("");
                                            jQuery('#<?php 
                esc_attr_e($field->get_id());
                ?>-msg-error').text(response.message);
                                        }

                                        return false;
                                    } else {
                                        jQuery('.eclearspot-ajax-msg-error').html('Invalid response.');
                                    }
                                });
                            });
                        });


                    </script>


					<?php 
            }
            self::$prevent_duplicate[$type] = \true;
        }, 10);
        return ['title' => $field->get_label(), 'type' => $custom_type_id, 'id' => $field->get_id(), 'description' => $field->get_desc()];
    }
}
