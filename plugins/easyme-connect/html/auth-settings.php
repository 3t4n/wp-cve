<div class="wrap">  
  <div class="postbox">

    <div class="inside">
      <h2><?php _e('EasyMe Access Control', 'easyme'); ?></h2>
      <p><?php echo $_page['i18n']['PRO_DESCRIPTION']; ?></p>
      <p><?php _e('On this page you configure the messages to display to users when the are not logged in or do not have access to the content, based on their purchases.', 'easyme'); ?></p>
    </div>

  </div>
</div>

<form method="post" action="<?php echo $_page['submit_url']; ?>" id="easyme-settings-form">

  <?php 
   foreach($_page['fields'] as $f) {
   ?>

  <div class="wrap">  
    <div class="postbox">

      <div class="inside">
	
        <div class="textarea-wrap" style="margin-bottom: 20px">
          <h2><?php echo $f['label']; ?></h2>
          <ul>
<?php
   if(in_array($f['name'], ['access_blocked_not_logged_in'])) {
       echo '<li><code>https://ezme.io/wp/login</code>' .  __('will become a link to log in', 'easyme') . '</li>';
   }
   if(in_array($f['name'], ['access_blocked_no_access','access_logged_in_slug'])) {
       echo '<li><code>https://ezme.io/wp/logout</code>' . __('will become a link to log out', 'easyme') . '</li>';
       echo '<li><code>https://ezme.io/wp/user/name</code>' .  __('will become the name of the logged in user', 'easyme') . '</li>';
       echo '<li><code>https://ezme.io/wp/user/profile</code>' .  __('will open a profile dialog for the user', 'easyme') . '</li>';       
   }
   ?>
          </ul>
	  <?php wp_editor($f['content'], $f['name'], $_page['editor_options']); ?>
        </div>

      </div>
    </div>
  </div>

  <?php
   }
   ?>
  
  <button type="submit" class="button button-primary"><?php _e('Save settings', 'easyme'); ?></button>
</form>

