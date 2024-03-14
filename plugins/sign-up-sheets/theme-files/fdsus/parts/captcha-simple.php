<?php
/**
 * Template for displaying the simple captcha form fields
 *
 * This template can be overridden by copying it to yourtheme/fdsus/parts/captcha-simple.php
 *
 * @package     FetchDesigns
 * @subpackage  Sign_Up_Sheets
 * @see         https://www.fetchdesigns.com/sign-up-sheets-pro-overriding-templates-in-your-theme/
 * @since       2.2.11 (plugin version)
 * @version     1.0.0 (template file version)
 */
?>
<p>
    <label for="spam_check" class="spam_check">
        <?php esc_html_e('Answer the following: 7 + 1 = __', 'fdsus'); ?>
        <span class="dls-sus-required-icon">*</span>
    </label>
    <input type="text" id="spam_check" class="spam_check" name="spam_check" size="4"
           required aria-required="true"
           value="<?php echo esc_attr(isset($_POST['spam_check']) ? $_POST['spam_check'] : ''); ?>"/>
</p>
<?php
