<?php

$info       = $info = apply_filters('wunderauto/plugininfo/wunderautomation-pro', (object)[]);
$email      = '';
$hasLicense = isset($info->licenceCheck->status) ? true : false;
$isValid    = isset($info->licenceCheck->status) && $info->licenceCheck->status === 'ok' ? true : false;
if (isset($info->licenceCheck->email)) {
    $email = $info->licenceCheck->email;
}
?>

<?php if (empty($this->result)) :?>
    <p>
        Use this form to contact our support. Replies will be sent to the email address you specify here. Valid license
        holders are prioritized over free users.
    </p>

    <b>From</b><br>
    <input type="text" name="email" value="<?php esc_attr_e($email)?>" size="60">
    <br>
    <br>
    <input type="text" name="subject" placeholder="Subject" size="60">
    <br>
    <textarea name="message" placeholder="Message" class="" rows="10" cols="60"></textarea>
    <br>
    <input type="checkbox" id="include" name="include" checked>
    <label for="include">Include the diagnostics info (see details below)</label>

    <p>
        <input type="submit" name="submit" id="_submit" class="button button-primary" value="Submit support ticket">
    </p>

    <p>
        Having trouble submitting the form? Email your support request to <b>support@wundermatics.com</b> instead.
    </p>

    <textarea cols="80" rows="20"><?php esc_html_e($this->getDiagnostics()); //@phpstan-ignore-line?></textarea>
<?php else : ?>
    <p>
        <?php esc_html_e($this->result['error'])?>
    </p>
<?php endif ?>




<style>#submit { display: none; }</style>