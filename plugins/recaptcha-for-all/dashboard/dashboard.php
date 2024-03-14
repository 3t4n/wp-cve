<?php
/**
 * @ Author: Bill Minozzi
 * @ Copyright: 2020 www.BillMinozzi.com
 * @ Modified time: 2021-03-03 09:07:38
 */
if (!defined('ABSPATH')) {
    die('We\'re sorry, but you can not directly access this file.');
}
    //display form
    echo '<div class="wrap-recaptcha ">' . "\n";
    echo '<h2 class="title">'.esc_attr__("reCAPTCHA and Turnstile quick start guide", "recaptcha-for-all").'</h2>' . "\n";
    echo '<p class="description">';


    esc_attr_e("This plugin can protect All (or only selected) Pages of your site against bots with invisible reCaptcha V3 (Google) or Cloudflare Turnstile.
    ", "recaptcha-for-all"); 
    
    echo '<br><br>';

    esc_attr_e("Note: This plugin requires google site key and google secret key (or Cloudflare Turnstile) to work.", "recaptcha-for-all"); 
    echo '<br>';   
    esc_attr_e("To get your required reCAPTCHA keys 3 from Google, visit:", "recaptcha-for-all"); 

    ?>


<br>
<a href="https://www.google.com/recaptcha/admin">https://www.google.com/recaptcha/admin</a>
<br>   <br> 


<b>

<?php 

esc_attr_e("To get your required Turnstile keys 3 from Cloudflare, visit:", "recaptcha-for-all"); 
?>


<br>
<a href="https://www.cloudflare.com/products/turnstile/">https://www.cloudflare.com/products/turnstile/</a>
<br>   <br> 

<?php 
esc_attr_e("Cloudflare has 3 types how widget can works (Widget Type). You can choose at same place where you get the keys.", "recaptcha-for-all"); ?>
<br>   <br> 
<?php 
esc_attr_e("How the plugin works:", "recaptcha-for-all"); ?>

</b>
<br /><br />

<?php esc_attr_e("The first time the user visit your site, will show up one box with a message and one  button.", "recaptcha-for-all"); ?>
<br />

<?php esc_attr_e("We need to show one image instead of the page content (maybe a sreenshot of your page) to avoid bots from stealing your content and to discourage new attacks.", "recaptcha-for-all"); ?>
<br />

<?php esc_attr_e("You can manage that image in Design Tab.", "recaptcha-for-all"); ?>


<br> 
<b>
    
    <?php esc_attr_e("After the user click on the button", "recaptcha-for-all"); ?>


</b>,&nbsp;

<?php esc_attr_e("the plugin will send a request to google (or Cloudflare) check that IP and Google (or Cloudflare) will send immediatly one response with a IP score (*).", "recaptcha-for-all"); ?>

<br> 
<?php esc_attr_e("Then, the plugin will allow the user with good score (the score filter is up to you) load the page or will block with a forbidden error.", "recaptcha-for-all"); ?>
<br> 
<?php esc_attr_e("(*)Turnstile Cloudflare don't have IP Score feature.", "recaptcha-for-all"); ?>


<?php esc_attr_e("The user browser needs accept cookies and keep javascript enabled.", "recaptcha-for-all"); ?>

<br>  <br> 

<?php esc_attr_e("The plugin doesn't block this bots:", "recaptcha-for-all"); ?>



<?php esc_attr_e("Google, Bing (Microsoft), Facebook, Slurp (Yahoo) and Tweeter", "recaptcha-for-all"); ?>


<br>
<?php esc_attr_e(" You can add more on Whitelist Table.", "recaptcha-for-all"); ?>


<br>  <br> 



<?php esc_attr_e("To Begin, click the tab Manage Keys and add your Keys.", "recaptcha-for-all"); ?>

<br>  <br> 
<?php esc_attr_e("After that, click the tab Manage Messages to edit your message and the button text if necessary.", "recaptcha-for-all"); ?>

<br>  <br> 
<?php esc_attr_e("Click also the tab General Settings, choose Google or Turnstile Cloudflare.", "recaptcha-for-all"); 
      echo '<br>'; 
      esc_attr_e("If you choose Google, select also the IP score desidered.", "recaptcha-for-all"); ?>

        <br> <br> 
<?php esc_attr_e("Go to Manage Pages tab and choose the pages and/or posts to enable reCAPTCHA/Turnstile.", "recaptcha-for-all"); ?>


<br> 

<?php esc_attr_e("Don't forget to manage Whitelist and fill out yours IPs and User Agents to be white listed (Manage Whitelist tab).", "recaptcha-for-all"); ?>

<br>  <br> 

<?php esc_attr_e("That is all!", "recaptcha-for-all"); ?>


<br>  <br> 
<?php esc_attr_e("To see your initial page, try to access your site from other device (different IP) and where you never logged in.", "recaptcha-for-all"); ?>

<?php 
//esc_attr_e("Or try to take a screenshot from this site:", "recaptcha-for-all"); 
// https://www.url2png.com/#testdrive
?>

<?php esc_attr_e("Or try to disable cookies of your browser.", "recaptcha-for-all"); ?>

<br><br>

<?php esc_attr_e("If you have questions, visit our FAQ page:", "recaptcha-for-all"); 

echo '<br>';


echo '<br>';

esc_attr_e( 'Visit the plugin site for more details, video, FAQ and Troubleshooting page.', 'stopbadbots' );
echo '<br>';
echo '<br>';
echo '<a href="https://recaptchaforall.com/" class="button button-primary">' . __( 'Plugin Site', 'stopbadbots' ) . '</a>';
echo '&nbsp;&nbsp;';
echo '<a href="https://recaptchaforall.com/faq/" class="button button-primary">' . __( 'Faq Page', 'stopbadbots' ) . '</a>';
echo '&nbsp;&nbsp;';
echo'<a href="https://billminozzi.com/dove/" class="button button-primary">' . __( 'Support Page', 'stopbadbots' ) . '</a>';
echo '&nbsp;&nbsp;';
echo '<a href="https://siterightaway.net/troubleshooting/" class="button button-primary">' . __( 'Troubleshooting Page', 'stopbadbots' ) . '</a>';
//echo'&nbsp;&nbsp;';
//echo '<a href="https://recaptchaforall.com/premium/" class="button button-primary">' . __( 'Go Pro', 'stopbadbots' ) . '</a>';
echo'<br>';
echo '<br>';

echo '</div>';