<div class="wrap" id="ezme-main">
  <div class="postbox">
    <div class="inside">
      <h1>EasyMe Connect</h1>

      <?php if(FALSE === $_page['connected']) { ?>

      <p>
	<?php echo _e('Click the button below to authorize Wordpress to connect to your EasyMe account', 'easyme'); ?>:
      </p>

      <p>
	<a class="button button-primary" href="<?php echo $_page['auth_url']; ?>"><?php _e('Connect to EasyMe', 'easyme'); ?></a>
      </p>

      <?php } else { ?>
      
      <div class="notice notice-success">
	<p>
	  <?php _e('You are connected to this EasyMe account', 'easyme'); ?>: <strong><?php echo $_page['site']; ?></strong>
	</p>
      </div>

      <p>
	<a class="button button-primary" href="<?php echo $_page['auth_url']; ?>"><?php _e('Connect to a different account', 'easyme'); ?></a>
      </p>
      <p>
	<em><?php _e('To disconnect, simply de-activate the EasyMe Connect plugin', 'easyme'); ?></em>
      </p>

      <h2><?php _e('How to use sales links', 'easyme'); ?></h2>
      <p>
	<?php echo sprintf(__('Now you can pull out <a href="%s" target="_blank">sales links</a> from your EasyMe account (the link pane of all products) and insert them here in Wordpress.', 'easyme'), 'https://help.easyme.dk/da/articles/1627386-direkte-links-til-dine-produkter') ; ?>
      </p>

      <p>
	<?php _e('You can also choose the color of buttons and links in the EasyMe widget:', 'easyme'); ?>
      </p>
      <form action="<?php echo $_page['submit_url']; ?>" method="post">
        <input type="text" class="ezme-color-picker" name="primary_color" value="<?php echo $_page['colors']['primary']['hex']; ?>">
	<input type="hidden" name="primary_color_hsl">
	<button type="submit" class="button button-primary"><?php _e('Save color', 'easyme'); ?></button>	
      </form>

      <h2><?php _e('How to lock down content with EasyMe', 'easyme'); ?></h2>
      <p><?php echo $_page['i18n']['PRO_DESCRIPTION']; ?></p>

      <?php } ?>
      
    </div>
  </div>
</div>
