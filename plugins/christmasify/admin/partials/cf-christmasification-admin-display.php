<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://cyberfoxdigital.co.uk
 * @since      1.0.0
 *
 * @package    Cf_Christmasify
 * @subpackage Cf_Christmasify/admin/partials
 */
?>
<div class="wrap">
  <div class="christmasify-header">
    <a href="https://cyberfoxdigital.co.uk?utm_source=wordpress&utm_medium=banner&utm_campaign=christmasify&utm_id=christmasify" id="cf-logo"><img src="<?php echo esc_url( plugins_url( '../img/cyber-fox-xmas-logo.gif', __FILE__ ) )?>" /></a>
    <h1><?php esc_html_e('Christmasify! Settings', 'christmasify'); ?></h1>
  </div>
<?php if(!empty($_POST)){ ?>
<div class="updated notice is-dismissible">

  <p><?php _e( 'If you like this plugin please give us a nice rating on <a href="https://wordpress.org/plugins/christmasify/" target="_blank">WordPress</a>. If you\'re feeling extra generous, you could <a href="https://www.buymeacoffee.com/CyberFox" target="_blank">buy us a coffee!</a> :)', 'christmasify' ); ?></p>

  <button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php _e( 'Dismiss this notice', 'christmasify' ); ?></span></button>

</div>
<?php } ?>

<form method="post" target="_self" novalidate="novalidate">

<h2><?php echo esc_html_e('Snowflakes', 'christmasify'); ?></h2>

<table class="form-table">
<tbody>
<tr>
<th scope="row"><?php echo esc_html_e('I want Snowflakes!', 'christmasify'); ?></th>
<td> <fieldset><legend class="screen-reader-text"><span><?php echo esc_html_e('Snowflakes', 'christmasify'); ?></span></legend><label for="snowflakes">
<select name="snowflakes" id="snowflakes">
  <option value="0"  <?php selected(get_option('cf_christmasify_snowflakes'), 0)  ?> ><?php echo esc_html_e('None', 'christmasify'); ?></option>
  <option value="10" <?php selected(get_option('cf_christmasify_snowflakes'), 10) ?> ><?php echo esc_html_e('Light', 'christmasify'); ?></option>
  <option value="25" <?php selected(get_option('cf_christmasify_snowflakes'), 25) ?> ><?php echo esc_html_e('Medium', 'christmasify'); ?></option>
  <option value="50" <?php selected(get_option('cf_christmasify_snowflakes'), 50) ?> ><?php echo esc_html_e('Heavy', 'christmasify'); ?></option>
  <option value="100" <?php selected(get_option('cf_christmasify_snowflakes'), 100) ?> ><?php echo esc_html_e('Snowstorm', 'christmasify'); ?></option>
  <option value="250" <?php selected(get_option('cf_christmasify_snowflakes'), 250) ?> ><?php echo esc_html_e('Insanity', 'christmasify'); ?></option>
  <option value="500" <?php selected(get_option('cf_christmasify_snowflakes'), 500) ?> ><?php echo esc_html_e('Beyond Insanity', 'christmasify'); ?></option>
</select>
</label>
</fieldset></td>
</tr>
<tr>
<th scope="row"><?php echo esc_html_e('Snow Speed', 'christmasify'); ?></th>
<td> <fieldset><legend class="screen-reader-text"><span><?php echo esc_html_e('Snow Speed', 'christmasify'); ?></span></legend><label for="snow_speed">
<select name="snow_speed" id="snow_speed">
  <option value="medium" <?php selected(get_option('cf_christmasify_snow_speed'), 'medium') ?> ><?php echo esc_html_e('Default', 'christmasify'); ?></option>
  <option value="slow"  <?php selected(get_option('cf_christmasify_snow_speed'), 'slow')  ?> ><?php echo esc_html_e('Slow', 'christmasify'); ?></option>  
  <option value="fast" <?php selected(get_option('cf_christmasify_snow_speed'), 'fast') ?> ><?php echo esc_html_e('Fast', 'christmasify'); ?></option>
</select>
</label>
</fieldset></td>
</tr>

<tr>
<th scope="row"><?php echo esc_html_e('I prefer my snow more classy!', 'christmasify'); ?></th>
<td> <fieldset><legend class="screen-reader-text"><span><?php echo esc_html_e('I prefer my snow more classy!', 'christmasify'); ?></span></legend><label for="classy_snow">
<input name="classy_snow" type="checkbox" id="classy_snow" value="1" <?php checked(get_option('cf_christmasify_classy_snow') , "1"); ?>></label>
</fieldset></td>
</tr>
</tbody>
</table>
<hr />
<h2><?php echo esc_html_e('Music', 'christmasify'); ?></h2>
<table class="form-table">
<tbody>
<tr>
<th scope="row"><label for="music"><?php echo esc_html_e('My visitors would love this jingle:', 'christmasify'); ?></label></th>
<td>
<select name="music" id="music">
	<option value="" <?php selected(get_option('cf_christmasify_music') , 0); ?>><?php esc_html_e('No Music', 'christmasify'); ?></option>
	<option value="deck-the-halls.mp3" <?php selected(get_option('cf_christmasify_music') , 'deck-the-halls.mp3'); ?>><?php esc_html_e('Deck the Halls', 'christmasify'); ?></option>
	<option value="jingle-bells.mp3" <?php selected(get_option('cf_christmasify_music') , 'jingle-bells.mp3'); ?>><?php esc_html_e('Jingle Bells', 'christmasify'); ?></option>
	<option value="we-wish-you.mp3" <?php selected(get_option('cf_christmasify_music') , 'we-wish-you.mp3'); ?>><?php esc_html_e('We Wish You a Merry Christmas', 'christmasify'); ?></option>
</td>
</tr>
</tbody>
</table>
<hr />
<h2><?php echo esc_html_e('Content Styling', 'christmasify'); ?></h2>
<table class="form-table">
<tbody>
<tr>
<th scope="row"><?php echo esc_html_e('Flying Santa would be awesome!', 'christmasify'); ?></th>
<td> <fieldset><legend class="screen-reader-text"><span><?php echo esc_html_e('Flying Santa would be awesome!', 'christmasify'); ?></span></legend><label for="santa">
<input name="santa" type="checkbox" id="santa" value="1" <?php checked(get_option('cf_christmasify_santa') , "1"); ?>></label>
</fieldset></td>
</tr>
<tr>
<th scope="row"><?php echo esc_html_e('Christmas decorations for images?', 'christmasify'); ?></th>
<td> <fieldset><legend class="screen-reader-text"><span><?php echo esc_html_e('Christmasy decorations for images? Okay!', 'christmasify'); ?></span></legend><label for="image_frame">
<input name="image_frame" type="checkbox" id="image_frame" value="1" <?php checked(get_option('cf_christmasify_image_frame') , "1"); ?>></label>
</fieldset><p class="description">This setting can be unrealiable on some themes, please check it works correctly and disable if you spot any problems.</p></td>
</tr>
<tr>
<th scope="row"><?php echo esc_html_e('Christmassy headings?', 'christmasify'); ?></th>
<td> <fieldset><legend class="screen-reader-text"><span><?php echo esc_html_e('Christmassy headings?', 'christmasify'); ?></span></legend><label for="font">
<input name="font" type="checkbox" id="font" value="1" <?php checked(get_option('cf_christmasify_font') , "1"); ?>></label>
</fieldset></td>
</tr>
</tbody>
</table>
<hr />
<h2><?php echo esc_html_e('Other Settings', 'christmasify'); ?></h2>
<table class="form-table">
<tbody>
<tr>
<th scope="row"><?php echo esc_html_e('Homepage Only?', 'christmasify'); ?></th>
<td> <fieldset><legend class="screen-reader-text"><span><?php echo esc_html_e('Only show Christmas effects on the homepage', 'christmasify'); ?></span></legend><label for="homepage_only">
<input name="homepage_only" type="checkbox" id="homepage_only" value="1" <?php checked(get_option('cf_christmasify_homepage_only') , "1"); ?>></label>
</fieldset></td>
</tr>
<tr>
<th scope="row"><?php echo esc_html_e('Activation Date', 'christmasify'); ?></th>
<td> <fieldset><legend class="screen-reader-text"><span><?php echo esc_html_e('Activate after this date', 'christmasify'); ?></span></legend><label for="date_from">
<input name="date_from" type="date" min="<?php echo date('Y-m-d'); ?>" id="date_from" value="<?php echo get_option('cf_christmasify_date_from' , date('Y-m-d')); ?>"></label>
</fieldset></td>
</tr>
<tr>
<th scope="row"><?php echo esc_html_e('Deactivation Date', 'christmasify'); ?></th>
<td> <fieldset><legend class="screen-reader-text"><span><?php echo esc_html_e('Deactivate after this date', 'christmasify'); ?></span></legend><label for="date_to">
<input name="date_to" type="date" min="<?php echo date('Y-m-d'); ?>" id="date_to" value="<?php echo get_option('cf_christmasify_date_to' , date('Y-m-d')); ?>"></label>
</fieldset></td>
</tr>

<tr>
<th scope="row"></th>
<td>
  <input type="submit" name="submit" id="submit" class="button button-primary button-large" style="background-color: #d63638; border-color: #d63638;" value="<?php echo esc_attr_x( 'Christmasify My Website!', 'Button Label', 'christmasify' ); ?>">
</td>
</tr>

</tbody>
</table>
</form>
<hr />
<p><?php esc_html_e( 'If you like this plugin please give us a like or share this plugin on Facebook :)', 'christmasify' ); ?></p>

<hr />

<table>
<tr>
<td><iframe src="https://www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2Fcyberfoxdigital%2F&width=450&layout=standard&action=like&size=small&show_faces=true&share=true&height=80&appId=31185768388" width="450" height="32" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe></td>
<td><a href="https://www.buymeacoffee.com/CyberFox" class="button button-large" target="_blank">Buy us a Coffee!</a></td>
</tr>
</table>

</div>
