<?php

class  FFWDViewThemes_ffwd {

    private $model;

    public function __construct($model) {
      $this->model = $model;
    }

    public function display() {
      if ( WD_FB_IS_FREE ) {
        WDW_FFWD_Library::topbar();
        ?>
        <div class="wrap">
          <div style="clear: both;float: right;color: #15699F; font-size: 20px; margin-top:10px; padding:8px 15px;">
            <?php _e('This is FREE version, Customizing themes is available only in the PAID version.', 'ffwd'); ?>
          </div>
        </div>
        <img src='<?php echo plugins_url('../../images/themes/thumbnail.png', __FILE__) ?>'/><br>
        <img src='<?php echo plugins_url('../../images/themes/masonry.png', __FILE__) ?>'/><br>
        <img src='<?php echo plugins_url('../../images/themes/album.png', __FILE__) ?>'/><br>
        <img src='<?php echo plugins_url('../../images/themes/blog.png', __FILE__) ?>'/><br>
        <img src='<?php echo plugins_url('../../images/themes/lightbox.png', __FILE__) ?>'/><br>
        <img src='<?php echo plugins_url('../../images/themes/pagenav.png', __FILE__) ?>'/><br>
        <?php
      }
    }
}