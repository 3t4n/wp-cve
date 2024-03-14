<div class="wrap">
  <div id="icon-plugins" class="icon32"><br/></div>
  <h2><?php _e('zbPlayer', 'zbplayer'); ?></h2>
  <br class="clear"/>


  <div id="poststuff" style="position: relative; margin-top:10px;">
    <div class="postbox-container">
      <div class="postbox">
        <h3 class="hndle ui-sortable-handle"><?php _e('zbPlayer Options', 'zbplayer'); ?></h3>
        <div class="inside">
          <?php echo !empty($submitMessage) ? '<div class="updated"><p><strong>' . $submitMessage . '</strong></p></div>' : ''; ?>
          <p>In most cases the way it is configured out of the box is just about right, but feel free to play with it.
            <div style="padding: 0px; float: right; margin-top: -40px;">
              <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                <input type="hidden" name="cmd" value="_s-xclick">
                <input type="hidden" name="hosted_button_id" value="P8Y47ZA6DXLLJ">
                <input type="image" style="border: none;" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
              </form>
            </div>
          </p>
          <p>zbPlayer Version: <em><?php echo get_option('zbp_version'); ?></em></p>
          <form id="zbp_options" name="zbp_options" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
            <table class="form-table">
            <tbody>
                <tr valign="top">
                <th scope="row">Autostart</th>
                  <td>
                    <input type="checkbox" name="zbp_autostart" id="zbp_autostart" <?php echo (get_option('zbp_autostart') == 'true') ? "checked" : ''; ?> />
                    <label for="zbp_autostart">Autostart the player</label>
                    <br />
                    <span call="explanatory-text">Causes the player to start playin immediately on load if enabled.</span>
                  </td>
                </tr>
                <tr valign="top">
                <th scope="row">Loop</th>
                  <td>
                    <input type="checkbox" name="zbp_loop" id="zbp_loop" <?php echo (get_option('zbp_loop') == 'true') ? "checked" : ''; ?> />
                    <label for="zbp_loop">If checked, player will play in loops</label>
                  </td>
                </tr>
                <tr valign="top">
                <th scope="row">Animation</th>
                  <td>
                    <input type="checkbox" name="zbp_animation" id="zbp_animation" <?php echo (get_option('zbp_animation') == 'true') ? "checked" : ''; ?> />
                    <label for="zbp_animation">If unchecked, player is always open</label>
                  </td>
                </tr>
                <tr valign="top">
                <th scope="row">Initial volume</th>
                  <td>
                    <input type="text" name="zbp_initialvolume" id="zbp_initialvolume" value="<?php echo get_option('zbp_initialvolume'); ?>" size="3" maxlength="3"/>
                    <span call="explanatory-text">Set here default value for volume of sound.</span>
                  </td>
                </tr>
                <tr valign="top" id="zbp_id3_row">
                <th scope="row">ID3 tags</th>
                  <td>
                    <input type="checkbox" name="zbp_id3" id="zbp_id3" <?php if (get_option('zbp_id3') == 'true') echo "checked"; ?> />
                    <label for="zbp_id3">Use ID3 information from file</label>
                    <br />
                    <span call="explanatory-text">In that case player will always try user ID3 info from file instead link name.</span>
                  </td>
                </tr>
                <tr valign="top" id="zbp_show_name_row" <?php if (get_option('zbp_collect_mp3') == 'true') echo "style='display: none;'"; ?>>
                <th scope="row">Show song name</th>
                  <td>
                    <input type="checkbox" name="zbp_show_name" id="zbp_show_name" <?php if (get_option('zbp_show_name') == 'Y') echo "checked";?>/>
                    <label for="zbp_show_name">Show song name above the player</label>
                  </td>
                </tr>
                <tr valign="top" id="zbp_download_row">
                <th scope="row">Download link</th>
                  <td>
                    <input type="checkbox" name="zbp_download" id="zbp_download" <?php if (get_option('zbp_download') == 'true') echo "checked"; ?> />
                    <label for="zbp_download">Include a static download link</label>
                    <br />
                    <span call="explanatory-text">Whether to include a link next to the flash player to download the file.</span>
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row">Multiplayer</th>
                  <td>
                    <input type="checkbox" name="zbp_collect_mp3" id="zbp_collect_mp3" <?php if (get_option('zbp_collect_mp3') == 'true') echo "checked"; ?> onchange="zbpSwitchCollectMp3()"/>
                    <label for="zbp_collect_mp3">Collect all mp3 links to one player</label>
                  </td>
                </tr>
                <tr valign="top" id="zbp_collect_row" <?php if (get_option('zbp_collect_mp3') != 'true') echo "style='display: none;'"; ?>>
                <th scope="row"></th>
                  <td>
                    <input type="text" name="zbp_collect_field" id="zbp_collect_field" value="<?php echo esc_attr(get_option('zbp_collect_field')); ?>" size="20" maxlength="20"/>
                    <br />
                    <span call="explanatory-text">Special tag in your post where to put player</span>
                  </td>
                </tr>
                <tr valign="top">
                <th scope="row">Player Width</th>
                  <td>
                    <input type="text" name="zbp_width" id="zbp_width" value="<?php echo get_option('zbp_width'); ?>" size="5" maxlength="5"/>
                    <span call="explanatory-text">Full width of player</span>
                  </td>
                </tr>
                <tr valign="top" id="zbp_native_mobile">
                <th scope="row">Native mobile player</th>
                  <td>
                    <input type="checkbox" name="zbp_native_mobile" id="zbp_native_mobile" <?php if (get_option('zbp_native_mobile') == 'true') echo "checked";?>/>
                    <label for="zbp_native_mobile">Always show native (HTML5) player on mobile devices</label>
                  </td>
                </tr>
                <tr valign="top" id="zbp_native_desktop">
                <th scope="row">Native desktop player</th>
                  <td>
                    <input type="checkbox" name="zbp_native_desktop" id="zbp_native_desktop" <?php if (get_option('zbp_native_desktop') == 'true') echo "checked";?>/>
                    <label for="zbp_native_desktop">Always show native (HTML5) player on desktop devices</label>
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row">&nbsp;</th>
                  <td>
                    <p class="submit">
                      <input type="hidden" name="action" value="update" />
                      <?php wp_nonce_field('zbp-update_options'); ?>
                      <input type="submit" name="Submit" value="Save Changes" class="button-primary" />
                    </p>
                  </td>
                </tr>
              </tbody>
            </table>
          </form>
        </div>
      </div>


      <div class="postbox" style="min-width:200px;">
        <h3 class="hndle ui-sortable-handle"><?php _e('Colour scheme options', 'zbplayer'); ?></h3>
        <div class="inside">
          <p><?php _e('All colour codes must be 6-digit HEX codes with ‘#’ in front.', 'zbplayer'); ?></p>
          <form id="zbp_color" name="zbp_color" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
            <table class="form-table">
              <?php foreach($colors as $color) : ?>
              <tr valign="top">
                <th scope="row"><?php echo $color['title'] ?></th>
                <td>
                  <input type="text" name="<?php echo $color['name'] ?>" value="<?php echo esc_attr(get_option($color['name'], $color['default'])) ?>" class="color" /><br />
                  <div class="colorpicker"></div>
                  <small><?php echo $color['desc'] ?></small>
                </td>
              </tr>
              <?php endforeach; ?>
              <tr valign="top">
                <th scope="row">&nbsp;</th>
                <td>
                  <p class="submit">
                    <input type="hidden" name="action" value="updateColor" />
                    <?php wp_nonce_field('zbp-update_options'); ?>
                    <input type="submit" name="Submit" value="Save Changes" class="button-primary" />
                  </p>
                </td>
              </tr>
            </table>
          </form>
        </div>
      </div>

    </div>

  </div>
</div>

<script>
function zbpSwitchCollectMp3()
{
  var newStatus = (document.getElementById('zbp_collect_row').style.display == 'none') ? '' : 'none';
  document.getElementById('zbp_collect_row').style.display = newStatus;

  nameStatus = newStatus == 'none' ? '' : 'none';
  document.getElementById('zbp_show_name_row').style.display = nameStatus;

  var newStatus = (nameStatus == '' && document.getElementById('zbp_show_name').checked) ? '' : 'none';
  document.getElementById('zbp_download_row').style.display = newStatus;
}
</script>
