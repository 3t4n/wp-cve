<?php
/**
 * @ Author: Bill Minozzi
 * @ Copyright: 2020 www.BillMinozzi.com
 * @ Modified time: 2024-01-17
 */
if (!defined('ABSPATH')) {
    die('We\'re sorry, but you can not directly access this file.');
}
echo '<div class="wrap-recaptcha">' . "\n";
echo '<h2 class="title">'.esc_attr__("Testing Steps:", "recaptcha-for-all").'</h2>' . "\n";



esc_attr_e("1. Clear your browser cookies. (cookie name: recaptcha_cookie).", "recaptcha-for-all"); 
esc_attr_e("Conduct a Google search on how to clear cookies if needed.", "recaptcha-for-all"); 
echo '<br>';

esc_attr_e("2. Log out of your account.", "recaptcha-for-all"); 
echo '<br>';

esc_attr_e("3. Visit your website's frontend and check for the presence of the captcha box.", "recaptcha-for-all");
echo '<br>'; 
esc_attr_e("4. Return to the plugin dashboard in the admin panel.", "recaptcha-for-all");
echo '<br>'; 
esc_attr_e('5. Navigate to the "Analytics" tab after a few minutes.', "recaptcha-for-all");

echo '<br>';
esc_attr_e("or", "recaptcha-for-all"); 

echo '<br>';
esc_attr_e("6. Access your site using a different IP address and device.", "recaptcha-for-all"); 
echo '<br>';




echo '</div>';

