<?php

$meta = lgcrm_get_cf7_post_settings($post->id);
$api_obj = new Wsl_Api();
$companies = $api_obj->get_companies();

wp_nonce_field( 'cf7_wsl_editor_panel', 'cf7_wsl_editor_panel_nonce' );
?>
<h2><?php echo esc_html( __( 'Lead Settings', 'contact-form-7' ) ); ?></h2>

<table class="form-table">
    <tbody>
        <tr>
            <th>Send to CRM</th>
            <td>
                <fieldset>
                    <p>
                        <label>
                            <input <?php checked($meta['send_to_crm'], 1, true); ?> type="checkbox" name="wsl_settings[send_to_crm]" value="1" /> Enable sending to CRM
                        </label>
                    </p>
                    <p class="description">Enabling 'Send to CRM' option will start sending this contact form submissions to CRM</p>
                        
                </fieldset>
            </td>
        </tr>
        <tr>
            <th>
                Company
            </th>
            <td>
                <?php include_once LGCRM_ADMIN_DIR.'/partials/send_to_company_field.php'; ?>
            </td>
        </tr>
    </tbody>
</table>