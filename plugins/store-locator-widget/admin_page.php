<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

// Build the settings page
function storelocatorwidget_settings_page() {

  $image    = plugins_url('images/logo.png', __FILE__);
  $styles   = plugins_url('css/storelocatorwidget_styles.css', __FILE__);
  $semantic = plugins_url('css/semantic.css', __FILE__);

  $api         = storelocatorwidget_get_storelocatorwidget_api();
  $gapi        = storelocatorwidget_get_google_api();

  wp_enqueue_style('storlocatorwidget_wp_admin_styles', $styles);
  wp_enqueue_style('storlocatorwidget_wp_admin_semantic', $semantic);

?>

<div class="wrap ui segment">

<img src="<?php echo $image ?>" width="300">
<hr>
<div class="instructions">
  <h3>Step by Step Installation Guide</h3>
  <p><b>Step 1</b> - First you'll need to register for a <a href="https://www.storelocatorwidgets.com/admin/Signup">StoreLocatorWidgets.com</a> account. If you don't have one already, you can sign up for a free 30 day trial account <a href="https://www.storelocatorwidgets.com/admin/Signup">here</a>.</p>
  <p><b>Step 2</b> - Once you've signed up, go to the <a href="https://www.storelocatorwidgets.com/admin/Details">Details</a> page and copy the <b>Store Locator API key</b> into the matching field below.</p>
  <p><b>Step 3</b> - You'll also need a <b>Google Maps API key</b>. Follow <a target="_blank" href="https://www.storelocatorwidgets.com/supportarticle?title=How%20to%20add%20a%20Google%20Maps%20API%20key%20to%20your%20store%20locator">our guide</a> (it only takes a couple of minutes!) and when you have your new key, paste it into the second field below.</p>
  <p><b>Step 4</b> - Once you have all of your <a href="https://www.storelocatorwidgets.com/admin/Stores">stores set up</a> in your StoreLocatorWidget account, you can insert your new Store Locator into any page by using the <b>[storelocatorwidget]</b> shortcode. Just add this shortcode wherever you want the locator to appear on your web page!</p>
  <p>If you run into any issues, need any help installing or uploading your store list then don't worry, we are here to help! Just use the chat function on the <a href="https://www.storelocatorwidgets.com/admin/Stores">StoreLocatorWidgets.com</a> website to get in touch with us.</p>
</div>

<?php if ( isset( $_GET[ 'settings-saved' ] ) ): ?>
<div class="updated"><p>Settings updated successfully.</p></div>
<?php endif ?>

<form method="post" action="admin-post.php">

    <input type="hidden" name="action" value="storelocatorwidget_api_keys" />
    <table class="form-table">
        <tr valign="top">
          <th scope="row" style="width: 250px">Store Locator API key</th>
          <td><input id="storelocatorwidget_api_box" type="text" name="storelocatorwidget_api_key" class="ui input" size="36" value="<?php echo $api ?>" /></td>
        </tr>

        <tr valign="top">
          <th scope="row">Google Maps API key</th>
          <td><input type="text" name="google_api_key" class="ui input" size="45" value="<?php echo $gapi ?>" /></td>
        </tr>

    </table>

    <?php submit_button(); ?>

</form>
</div>

<?php }
