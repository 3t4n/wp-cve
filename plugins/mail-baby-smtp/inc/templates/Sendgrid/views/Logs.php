<?php
$Logs = new \WPMailPlus\Logs();
$Logs->prepare_items();
?>
<div class = 'wp_mailplus_settings_notification'>
    <div class="notice notice-warning is-dismissible">
        <p><?php _e( ' Default and SMTP success mails will not be shown in this Logs', 'mail-sendgrid-plus' ); ?></p>
    </div>
</div>
<div class = 'container-fluid add-top-margin'>
    <div class = 'row'>
        <div class = 'col-md-5'>
            <h3 class = 'wp_mp_h3'>
                <span class="dashicons dashicons-clock dash-email-icon"></span>
                <span class = 'mc_settings_header'> Mail SendGrid Plus Logs </span>
            </h3>
        </div>
        <div class = 'col-md-6'>
            <input type = 'button' class = 'button button-default button-large pull-right' style = 'background-color: #D9534F; border-color: #D9534F; color: white' value = 'Clear Logs' onclick = 'wp_mailplus_clear_logs()'>
            <span class="spinner"> </span>
        </div>    
        <hr class = 'dashed'>
        <div class = 'mailplus-logs'>
            <form method = 'POST' action = '' name = 'mailplus_logs'>
                <div class = 'row'>
                    <div class = 'col-md-2'>
                        <select name = 'service' class = 'form-control'>
                            <option value = ''> Select </option>
                            <?php
                            foreach(\WPMailPlus\BaseController::$available_service as $service_key => $service_name)    {
                                $selected = '';
                                if($service_key == $_POST['service'])
                                    $selected = 'selected';

                                ?>
                                <option <?php echo esc_attr($selected); ?> value = '<?php echo esc_attr($service_key); ?>'> <?php echo esc_attr($service_name); ?> </option> <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class = 'col-md-2'>
                        <select name = 'status' class = 'form-control'>
                            <option value = ''> Select </option>
                            <option value = 'Success' <?php if($_POST['status'] == 'Success') { echo 'Selected'; } ?> > Success </option>
                            <option value = 'Failed' <?php if($_POST['status'] == 'Failed') { echo 'Selected'; } ?> > Failed </option>
                        </select>
                    </div>
                    <div class = 'col-md-2'>
                        <button type = 'submit' name = 'Filter' class = 'button button-primary button-large'> Filter </button>
                    </div>
                </div>
                <?php $Logs->display(); ?>
            </form>
        </div>
    </div>
</div>
