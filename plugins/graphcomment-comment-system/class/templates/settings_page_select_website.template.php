<?php

  /*
   *  Settings page : Select Website
   *  Select the GraphComment website associated with this plugin
   *  Controller : ../controllers/gc_select_website_controller.class.php
   */


?>

<div class="gc-container">
  <?php header_template(); ?>

  <div class="gc-wrap">

    <div class="row gc-page">
      <h2>
        <?php _e('Choose Website Message', 'graphcomment-comment-system'); ?>
      </h2>

      <?php
      delete_option('gc_change_website');
      ?>

      <form id="graphcomment-create-website-form" class="col-md-12 graphcomment-options-form" method="post" action="options.php">
        <input type="hidden" name="gc_action" value="select_website"/>

        <?php foreach(GcParamsService::getInstance()->graphcommentGetWebsites() as $website): ?>
          <div class="radio">
            <label>
              <input type="radio" name="gc_website_id" id="optionsRadios1" value="<?php echo $website->public_key; ?>">
              <strong><?php echo $website->public_key; ?></strong>, created on <?php $date = new DateTime($website->created_at); echo $date->format('Y-m-d') ?>
            </label>
          </div>
        <?php endforeach; ?>

        <p>
          <?php _e('Website Not Found Label', 'graphcomment-comment-system'); ?>,
          <a id="graphcomment-create-website" ><?php _e('Create A New One', 'graphcomment-comment-system'); ?></a>.
        </p>

        <?php submit_button(__('Select Website Button Text', 'graphcomment-comment-system'), 'primary', 'gc_select_website_submit_button'); ?>

      </form>

    </div>
  </div>
</div>

<?php
