<?php
/*
Plugin Name: yaCAPTCHA
Plugin URI: http://www.remyroy.com/yacaptcha
Description: Yet Another CAPTCHA plugin for WordPress based on <a href="http://www.captcha.ru/en/kcaptcha/">KCAPTCHA</a>.
Version: 1.5
Author: Rémy Roy
Author URI: http://www.remyroy.com
*/
/*  Copyright 2008  Rémy Roy  (email : remyroy@remyroy.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

$yaCaptchaCharInputMsg = 'Characters in the image above';
if (version_compare($wp_version, '3', '<')) {
    $yaCaptchaCharInputMsg .= ' (required)';
}
$yaCaptchaCharNoMatchMsg = 'Characters do not match what is shown in the image. Please retry.';
$yaCaptchaCharAlternateCaptchaText = 'CAPTCHA image';

function yaCaptchaInit() {
    global $wp_version;

    add_action('comment_post', 'yaCaptchaCommentPost');

    if (version_compare($wp_version, '3', '>=')) {
        add_action('comment_form_after_fields', 'yaCaptchaCommentFormAfterFields');
    } else {
        add_action('comment_form', 'yaCaptchaCommentForm');
    }
}

function yaCaptchaCommentFormAfterFields() {
    global $userdata;
    global $yaCaptchaCharInputMsg;
    global $yaCaptchaCharAlternateCaptchaText;
    get_currentuserinfo();

    if ('' == $userdata->ID) {
        ?>
        <p class="comment-form-captcha">
            <img src="<?php echo bloginfo('url'); ?>/wp-content/plugins/yacaptcha/captcha-image.php" width="120" height="60" alt="<?php echo __($yaCaptchaCharAlternateCaptchaText); ?>" /><br />
            <label for="captcha"> <?php echo __($yaCaptchaCharInputMsg); ?> </label> <span class="required">*</span>
            <input id="captcha" name="captcha" type="text" value="" size="30" aria-required="true" />
        </p>
        <?php
    }
}

function yaCaptchaCommentForm($id) {

    global $userdata;
    global $yaCaptchaCharInputMsg;
    global $yaCaptchaCharAlternateCaptchaText;
    get_currentuserinfo();

    if ('' == $userdata->ID) {
        ?>
        <p>
            <img src="<?php echo bloginfo('url'); ?>/wp-content/plugins/yacaptcha/captcha-image.php" width="120" height="60" alt="<?php echo __($yaCaptchaCharAlternateCaptchaText); ?>" /><br />
            <input id="captcha" name="captcha" type="text" value="" />        
            <label for="captcha"><small><?php echo __($yaCaptchaCharInputMsg); ?></small></label>
        </p>
        <?php
    }
}

function yaCaptchaCommentPost($id) {

    global $userdata;
    global $yaCaptchaCharNoMatchMsg;
    global $wp_version;
    get_currentuserinfo();
    
    session_start();

    if ('' == $userdata->ID && !(isset($_SESSION['captcha_keystring']) && $_SESSION['captcha_keystring'] ==  $_POST['captcha'])) {
    
        $updated_status = 'delete';
        
        if (version_compare($wp_version, '2.9', '>=')) {
            $updated_status = 'trash';
        }
        
        wp_set_comment_status($id, $updated_status);
        
        if (function_exists('wp_die')) {
            wp_die(__($yaCaptchaCharNoMatchMsg));
        } else {
            die(__($yaCaptchaCharNoMatchMsg));
        }
    
    }
}

yaCaptchaInit();

?>