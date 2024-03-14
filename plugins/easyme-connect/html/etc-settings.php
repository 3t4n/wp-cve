<form method="post" action="<?php echo $_page['submit_url']; ?>">
  <div class="wrap">  
    <div class="postbox">

      <div class="inside">
        <h2><?php _e('Other settings', 'easyme'); ?></h2>

	<p><?php _e('Where should the javascript widget be included?', 'easyme'); ?></p>
	<input type="radio" name="widget_include_option" value="ALL" <?php echo ('ALL' == $_page['widget_include_option'] ? 'checked' : ''); ?>> <?php _e('On all pages', 'easyme'); ?><br>
	<input type="radio" name="widget_include_option" value="EZME" <?php echo ('EZME' == $_page['widget_include_option'] ? 'checked' : ''); ?>> <?php _e('On pages with ezme.io sales links', 'easyme'); ?><br>

	<p><?php _e('Note: The widget will always be included on pages using WordPress PRO access control', 'easyme'); ?></p>
	
      </div>

    </div>
  </div>

  <button type="submit" class="button button-primary"><?php _e('Save settings', 'easyme'); ?></button>
</form>
