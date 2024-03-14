<div class="wrap">
  <div id="poststuff">
    <div id="post-body" class="metabox-holder columns-2">
      <div id="post-body-content">
        <div class="postbox">
          <div class="inside">
            <h1>
              BotPenguin - Settings
              <a class="add-new-h2" target="_blank" href="<?php echo esc_url("https://botpenguin.com"); ?>">
                <?php _e('Read Tutorial', 'BotPenguin'); ?>
              </a> <a class="add-new-h2" target="_blank" href="<?php echo esc_url("https://BotPenguin.com"); ?>">
                <?php _e('Watch Tutorial', 'BotPenguin'); ?></a></h1>
            <h3 class="cc-labels"><?php _e('Instructions: ', 'BotPenguin'); ?></h3>

            <p>1.
              <?php _e('If you are not an existing BotPenguin user, <a href="https://app.botpenguin.com/signup" target="_blank">Click here to register</a>', 'BotPenguin'); ?>
            </p>
            <p>3.
              <?php _e('Copy the code snippet from Dashboard > Bot Builder > install and paste it here', 'BotPenguin'); ?>
            </p>
            <h3 class="cc-labels" for="script"><?php _e('BotPenguin snippet:', 'BotPenguin'); ?></h3>
            <form action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>" method="post">
              <textarea style="width:98%;" rows="5" cols="30" id="script" name="footertext">
              <?php echo $textvar; ?>
              </textarea>
              <input name="change-clicked" type="hidden" value="1" />
              <input class="button button-primary" type="submit" value="<?php _e('Save settings', 'BotPenguin'); ?>" />
            </form>
          </div>
        </div>
      </div>
      <?php require_once('sidebar.php'); ?>
    </div>
  </div>
</div>