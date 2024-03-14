<?php

namespace MailOptin\PmProConnect;

use MailOptin\Connections\Init;
use MailOptin\Core\AjaxHandler;
use MailOptin\Core\Connections\AbstractConnect;
use MailOptin\Core\Connections\ConnectionFactory;
use MailOptin\Core\OptinForms\ConversionDataBuilder;
use function MailOptin\Core\moVar;
use function MailOptin\Core\sanitize_data;

class Membership
{
    public function __construct()
    {
        add_action('pmpro_save_membership_level', [$this, 'save_level_settings']);
        add_action('pmpro_membership_level_after_other_settings', [$this, 'membership_level_settings'], 1);
    }

    public function save_level_settings($saveid)
    {
        if (isset($_POST['mo_pmpro_settings'])) {
            update_option('mo_pmpro_' . $saveid, sanitize_data($_POST['mo_pmpro_settings']));
        }
    }

    private function get_option($key, $levelId)
    {
        static $data = [];

        if (empty($data)) {
            $data = get_option('mo_pmpro_' . $levelId, []);
        }

        return moVar($data, $key);
    }

    public function membership_level_settings($level)
    {
        $level_id          = $level->id;
        $integrations      = PMPROInit::email_service_providers();
        $saved_integration = $this->get_option('integration', $level_id);

        $saved_list = $this->get_option('email_list', $level_id);
        $lists      = Init::mo_select_list_options($saved_integration);

        $upsell_url = 'https://mailoptin.io/pricing/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=pmpro_connection';
        $doc_url    = 'https://mailoptin.io/article/paid-memberships-pro-mailchimp-aweber-more/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=pmpro_connection';
        $content    = sprintf(
            __("Upgrade to %sMailOptin Premium%s to add members that subscribes to this membership to a specific email list, assign tags and custom field data to them.", 'mailoptin'),
            '<a target="_blank" href="' . $upsell_url . '">',
            '</a>'
        );
        ?>
        <h3 class="topborder"><?php _e('MailOptin Integration', 'mailoptin'); ?></h3>

        <?php if ( ! defined('MAILOPTIN_DETACH_LIBSODIUM')) : ?>
        <div class="mo-external-upsell-block">
            <p><?= $content ?></p>
            <p>
                <a href="<?= $upsell_url ?>" style="margin-right: 10px;" class="button-primary" target="_blank">
                    <?php esc_html_e('Upgrade to MailOptin Premium', 'mailoptin'); ?>
                </a>
                <a href="<?= $doc_url ?>" target="_blank">
                    <?php esc_html_e('Learn more', 'mailoptin'); ?>
                </a>
            </p>
        </div>
    <?php else : ?>

        <p class="description"><?php printf(__('For more information on how this integration work, %1$ssee our documentation%2$s.', 'mailoptin'), '<a href="https://mailoptin.io/article/paid-memberships-pro-mailchimp-aweber-more/" target="_blank">', '</a>'); ?></p>

        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row" valign="top">
                    <label><?php _e('Select Integration', 'mailoptin'); ?></label>
                </th>
                <td>
                    <select name="mo_pmpro_settings[integration]" onchange="jQuery(this).parents('form').submit();">
                        <?php foreach ($integrations as $key => $value) {
                            echo '<option value="' . esc_attr($key) . '"' . selected($key, $saved_integration) . '>' . esc_html($value) . '</option>';
                        } ?>
                    </select>
                    <p class="description"><?php _e('Select your email marketing software or CRM.', 'mailoptin'); ?></p>
                </td>
            </tr>

            <?php if ( ! empty($saved_integration) && $saved_integration != 'leadbank') : ?>
                <tr>
                    <th scope="row" valign="top">
                        <label><?php _e('Select List', 'mailoptin'); ?></label>
                    </th>
                    <td>
                        <select name="mo_pmpro_settings[email_list]" onchange="jQuery(this).parents('form').submit();">
                            <?php foreach ($lists as $key => $value) {
                                echo '<option value="' . esc_attr($key) . '"' . selected($key, $saved_list) . '>' . esc_html($value) . '</option>';
                            } ?>
                        </select>
                        <p class="description"><?php _e('Select the email list, audience or contact list to add subscribed members.', 'mailoptin'); ?></p>
                    </td>
                </tr>
            <?php endif;

            $saved_double_optin           = $this->get_option('double_optin', $level_id);

            if (in_array($saved_integration, Init::double_optin_support_connections(true))) :
                $is_double_optin = false;
                $double_optin_connections = Init::double_optin_support_connections();
                foreach ($double_optin_connections as $key => $value) {
                    if ($saved_integration === $key) {
                        $is_double_optin = $value;
                    }
                }

                $label = ($is_double_optin === false) ? __('Enable Double Optin', 'mailoptin') : __('Disable Double Optin', 'mailoptin');
                ?>

                <tr>
                    <th scope="row" valign="top">
                        <label for="mailoptin_pmpro_double_optin"><?php echo $label; ?></label>
                    </th>
                    <td>
                        <input type="hidden" name="mo_pmpro_settings[double_optin]" value="false">
                        <input type="checkbox" id="mailoptin_pmpro_double_optin" name="mo_pmpro_settings[double_optin]" value="true" <?php checked('true', $saved_double_optin) ?>>
                        <p class="description"><?php _e('Double optin requires users to confirm their email address before they are added or subscribed.', 'mailoptin'); ?></p>
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
        <?php
        if ( ! empty($saved_list)) {

            $mappable_fields = Init::merge_vars_field_map($saved_integration, $saved_list);

        if ( ! empty($mappable_fields)) {

            printf(
                '<h3 style="border-bottom: 1px solid #c3c4c7;line-height: 0.1em !important;" class="mo-line-header"><span style="background: #fff;padding-right: 10px;font-size: 16px;">%s</span></h3>',
                esc_html__('Map Fields', 'mailoptin')
            );

            ?>
            <table class="form-table">
                <tbody>
                <?php
                foreach ($mappable_fields as $key => $value) :

                    $mapped_key = rawurlencode('mapped_field_' . $key);

                    $saved_mapped_value = $this->get_option($mapped_key, $level_id);
                    ?>
                    <tr>
                        <th scope="row" valign="top">
                            <label><?php echo $value ?></label>
                        </th>
                        <td>
                            <select name="<?php echo 'mo_pmpro_settings[' . $mapped_key . ']'; ?>">
                                <?php foreach (PMPROInit::get_instance()->pmpro_fields() as $keyz => $valuez) {
                                    echo '<option value="' . esc_attr($keyz) . '"' . selected($keyz, $saved_mapped_value) . '>' . esc_html($valuez) . '</option>';
                                } ?>
                            </select>
                        </td>
                    </tr>
                <?php
                endforeach;
                }
                ?>
                </tbody>
            </table>
            <table class="form-table">
            <tbody>
        <?php

        $saved_tags = $this->get_option('tags', $level_id);

        if (in_array($saved_integration, Init::select2_tag_connections())) {
        $tags     = [];
        $instance = ConnectionFactory::make($saved_integration);
        if (is_object($instance) && method_exists($instance, 'get_tags')) {
            $tags = $instance->get_tags();
        }

        $saved_tags = ! is_array($saved_tags) ? [] : $saved_tags;

        $options = [];

        foreach ($tags as $value => $label) {
            if (empty($value)) continue;

            $options[$value] = $label;
        }

        if ( ! empty($options)) {
        ?>
            <tr>
                <th scope="row" valign="top">
                    <label><?php _e('Tags', 'mailoptin'); ?></label>
                </th>
                <td>
                    <select style="max-width:350px;width:350px;" id="mo_pmpro_tags" name="mo_pmpro_settings[tags][]" multiple>
                        <?php foreach ($options as $key => $value) {
                            echo '<option value="' . esc_attr($key) . '"' . (in_array($key, $saved_tags) ? ' selected' : '') . '>' . esc_html($value) . '</option>';
                        } ?>
                    </select>
                    <p class="description"><?php _e('Select tags to assign to subscribed members', 'mailoptin'); ?></p>
                </td>
            </tr>
            <script>
                var mo_pmpro_select2_init = function () {
                    jQuery('#mo_pmpro_tags').select2();
                };

                jQuery('window').on('load', mo_pmpro_select2_init);
                mo_pmpro_select2_init();
            </script>
        <?php
        }

        } elseif (in_array($saved_integration, Init::text_tag_connections())) {
        ?>
            <tr>
                <th scope="row" valign="top">
                    <label><?php _e('Tags', 'mailoptin'); ?></label>
                </th>
                <td>
                    <input name="mo_pmpro_settings[tags]" type="text" value="<?= $saved_tags ?>" class="regular-text">
                    <p class="description"><?php _e('Enter a comma-separated list of tags to assign to subscribed members. The membership-level name is automatically included in the list of tags.', 'mailoptin'); ?></p>
                </td>
            </tr>
            <?php
        }
        }
        ?>
        </tbody>
        </table>
    <?php endif; ?>
        <?php
    }

    public function process_submission(\MemberOrder $morder)
    {
        $level_id = $morder->membership_id;

        $connection = $this->get_option('integration', $level_id);

        if (empty($connection)) return;

        $connection_email_list = $this->get_option('email_list', $level_id);

        $field_map = [];

        foreach (Init::merge_vars_field_map($connection, $connection_email_list) as $key => $value) {
            $saved_mapped_key = $this->get_option(rawurlencode('mapped_field_' . $key), $level_id);
            if ( ! empty($saved_mapped_key)) {
                $field_map[$key] = $saved_mapped_key;
            }
        }

        $user_id = $morder->user_id;

        $user_data = get_userdata($user_id);

        $email = $user_data->user_email;

        if (empty($email)) return;

        $payload = [];

        foreach ($field_map as $key => $value) {
            $payload[$key] = PMPROInit::get_instance()->get_field_value($value, $user_id, $morder);
        }

        $double_optin = false;
        if (in_array($connection, Init::double_optin_support_connections(true))) {
            $double_optin = $this->get_option('double_optin', $level_id) == 'true';
        }

        $form_tags = '';
        if (in_array($connection, Init::text_tag_connections())) {
            $level_name         = pmpro_getLevel($morder->membership_id)->name;
            $form_tags          = $this->get_option('tags', $level_id);
            $exploded_form_tags = explode(',', $form_tags);
            array_push($exploded_form_tags, $level_name);

            $form_tags = implode(',', array_filter($exploded_form_tags));
        } elseif (in_array($connection, Init::select2_tag_connections())) {
            $form_tags = $this->get_option('tags', $level_id);
        }

        $optin_data = new ConversionDataBuilder();

        $first_name = $user_data->first_name;
        $last_name  = $user_data->last_name;
        $name       = Init::get_full_name($first_name, $last_name);

        $optin_data->optin_campaign_id   = 0; // since it's non mailoptin form, set it to zero.
        $optin_data->payload             = $payload;
        $optin_data->name                = Init::return_name($name, $first_name, $last_name);
        $optin_data->email               = $email;
        $optin_data->optin_campaign_type = 'Paid Memberships Pro';

        $optin_data->connection_service    = $connection;
        $optin_data->connection_email_list = $connection_email_list;

        $optin_data->user_agent                = esc_html($_SERVER['HTTP_USER_AGENT']);
        $optin_data->is_timestamp_check_active = false;
        $optin_data->is_double_optin           = $double_optin;

        if ( ! empty($form_tags)) {
            $optin_data->form_tags = $form_tags;
        }

        // Loop through field map.
        foreach ($field_map as $name => $value) {
            // If no field is mapped, skip it.
            if (empty($value)) {
                continue;
            }

            $field_value = PMPROInit::get_instance()->get_field_value($value, $user_id, $morder);

            if (empty($field_value)) continue;

            $optin_data->form_custom_field_mappings[$name] = $name;
        }

        $response = AjaxHandler::do_optin_conversion($optin_data);

        return AbstractConnect::is_ajax_success($response);
    }

    /**
     * @return Membership
     */
    public static function get_instance()
    {
        static $instance = null;

        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }
}