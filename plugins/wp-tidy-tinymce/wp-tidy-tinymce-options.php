<?php
/*
 * Copyright (c) 2012-2014 Storm Consultancy (EU) Ltd,
 * http://www.stormconsultancy.co.uk/
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */


//Add an option page
if(is_admin()){
  add_action('admin_menu', 'wp_tidy_tinymce_menu');
  add_action('admin_init', 'wp_tidy_tinymce_register_settings');
}


/*
 * Register the settings we need for each widget.
 * Callback for the admin_init action.
 */
function wp_tidy_tinymce_register_settings(){
  global $TINYMCE_BLOCK_FORMATS;
  global $TINYMCE_ADV_BUTTONS;

  foreach($TINYMCE_BLOCK_FORMATS as $k => $v){
    register_setting('tidy_tinymce_group', 'blockformat_'.$k);
  }

  foreach($TINYMCE_ADV_BUTTONS as $k => $v){
    register_setting('tidy_tinymce_group', 'adv_button_'.$k);
  }
}

/*
 * Register the menu item for the options page.
 * Callback for the admin_menu action.
 */
function wp_tidy_tinymce_menu(){
  add_options_page(
    "Tidy TinyMCE Options",
    "Tidy TinyMCE",
    'administrator',
    'wptidytinymceoptions',
    'wp_tidy_tinymce_options'
  );
}

/*
 * Create the options page.
 * Callback for the add_options_page action.
 */
function wp_tidy_tinymce_options(){
  global $TINYMCE_BLOCK_FORMATS;
  global $TINYMCE_ADV_BUTTONS;
  ?>
<div class="wrap">
  <div id="icon-options-general" class="icon32">
    <br>
  </div>

  <h2><?php echo "Tidy TinyMCE Options"; ?></h2>


  <form method="post" action="options.php">
  <?php settings_fields('tidy_tinymce_group'); ?>

    <table class="form-table">
      <tr valign="top">
        <th scope="row">Select block formats to <b>keep</b></th>
        <td>
          <?php
            foreach($TINYMCE_BLOCK_FORMATS as $k => $v){
              ?>
              <label style="display: block;">
                <input type="checkbox" name="<?php echo 'blockformat_'.$k ?>" value="1" <?php if(get_option('blockformat_'.$k) == 1){ echo 'checked="checked"'; } ?> />
                <?php echo $v; ?>
              </label>
          <?php
            }
          ?>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row">Select advanced buttons to <b>remove</b></th>
        <td>
          <?php
            foreach($TINYMCE_ADV_BUTTONS as $k => $v){
              ?>
              <label style="display: block;">
                <input type="checkbox" name="<?php echo 'adv_button_'.$k ?>" value="1" <?php if(get_option('adv_button_'.$k) == 1){ echo 'checked="checked"'; } ?> />
                <?php echo $v; ?>
              </label>
          <?php
            }
          ?>
        </td>
      </tr>
    </table>

    <p class="submit">
      <input type="submit" name="submit" class="button-primary" value="Save Options" />
    </p>

  </form>
</div>
<?php
}

?>
