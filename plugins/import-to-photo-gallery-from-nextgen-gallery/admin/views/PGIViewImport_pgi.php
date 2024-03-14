<?php

class PGIViewImport_pgi {
  ////////////////////////////////////////////////////////////////////////////////////////
  // Events                                                                             //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constants                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Variables                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  private $model;

  ////////////////////////////////////////////////////////////////////////////////////////
  // Constructor & Destructor                                                           //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function __construct($model) {
    $this->model = $model;
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Public Methods                                                                     //
  ////////////////////////////////////////////////////////////////////////////////////////
 
  public function display() {
    if (!is_plugin_active('nextgen-gallery/nggallery.php') || !(function_exists('BWG') && class_exists('BWG'))) {
      echo $this->model->message(__('Plugin will not work without Nextgen-Gallery and Photo-gallery plugins.', 'pgi'), 'error');
    }
	  else {
      global $wpdb;
      $gallery_names = $this->model->get_results("ngg_gallery");
      $album_names = $this->model->get_results("ngg_album");
      ?>
      <div class="wrap">
        <span class="import-icon"></span>
        <h2><?php echo __('NextGen Gallery Import to Photo Gallery', 'pgi'); ?></h2>
      </div>
      <form class="wrap" id="galleries_form" method="post" action="admin.php?page=import_pgi" style="width: 99%;">
	      <?php wp_nonce_field('nonce_pgi', 'nonce_pgi'); ?>
	      <input id="task" name="task" type="hidden" value="" />
        <div id="wrapper">
          <div class="pgi_col selectors">
		   <?php 
		  if($gallery_names != array()){
		    ?>
            <p class="pgi_title"><?php _e('Galleries', 'pgi'); ?></p>
            <ul>
              <li>
                <input type="checkbox" checked="checked" id="select_all_gallery" class="select_all" onchange="pgi_selectAll(this);" />
                <label class="pgi_select" for="select_all_gallery"><?php _e('Select All', 'pgi');?></label>
              </li>
              <?php
              foreach ($gallery_names as $gallery_name) {
                ?>
              <li>
                <input type="checkbox" checked="checked" class="galleries_selected select" name="galleries_selected[]" id="<?php echo $gallery_name->title ?>" value="<?php echo $gallery_name->gid ?>" />
                <label for="<?php echo $gallery_name->title ?>" title="<?php echo $gallery_name->title ?>"><?php echo $gallery_name->title ?></label>
              </li>
                <?php
              }
              ?>
            </ul>
			<?php
          }
		  else{
		   ?>
		    <p><?php _e('There are no galleries to show.', 'pgi'); ?></p>
		    <?php
          }
          ?>
          </div>
          <div class="pgi_col selectors">
            <p class="pgi_title"><?php _e('Albums', 'pgi'); ?></p>
			<?php 
		  if($album_names != array()){
		    ?>
            <ul>
              <li>
                <input type="checkbox" checked="checked" id="select_all_album" class="select_all" onchange="pgi_selectAll(this);" />
                <label class="pgi_select" for="select_all_album"><?php _e('Select All', 'pgi');?></label>
              </li>
              <?php
              foreach ($album_names as $album_name) {
                ?>
              <li>
                <input type="checkbox" checked="checked"  class="album_selected select" name="album_selected[]" id="<?php echo $album_name->name ?>" value="<?php echo $album_name->id ?>" />
                <label for="<?php echo $album_name->name ?>"><?php echo $album_name->name ?></label>
              </li>
                <?php
              }
              ?>
            </ul>   
			<?php
          }
		  else{
		   ?>
		    <p><?php _e('There are no albums to show.', 'pgi'); ?></p>
		    <?php
          }
          ?>
          </div>
          <div class="pgi_clear">
            <table class="pgi_table">
              <?php
              if (is_plugin_active('nextgen-gallery-voting/ngg-voting.php')) {
                ?>
              <tr>
                <td>
                  <label><?php _e('Import Ratings:', 'pgi'); ?></label> 
                </td>
                <td>
                  <input type="radio" name="import_rating" id="ratting_yes" checked="checked"  value="1" />
                  <label for="ratting_yes"><?php _e('Yes', 'pgi'); ?></label>
                  <input type="radio" name="import_rating" id="ratting_no" value="0" />
                  <label for="ratting_no"><?php _e('No', 'pgi'); ?></label>
                </td>
              </tr>
                <?php 
              }
              ?>
              <tr>
                <td>
                  <label><?php _e('Import Comments:', 'pgi'); ?></label>
                </td>
                <td>
                  <input type="radio" name="import_comments" id="comments_yes" checked="checked" value="1" />
                  <label for="comments_yes"><?php _e('Yes', 'pgi'); ?></label>
                  <input type="radio" name="import_comments" id="comments_no" value="0" />
                  <label for="comments_no"><?php _e('No', 'pgi'); ?></label>
                </td>
              </tr>	
              <tr>
                <td>
                  <label><?php _e('Import Tags:', 'pgi'); ?></label>
                </td>
                <td>
                  <input type="radio" name="import_tags" checked="checked" id="tags_yes" value="1" />
                  <label for="tags_yes"><?php _e('Yes','pgi');?></label>
                  <input type="radio" name="import_tags" id="tags_no" value="0" />
                  <label for="tags_no"><?php _e('No','pgi');?></label>
                </td>
              </tr>
            </table>
          </div>
          <input type="button" class="button-primary" value="<?php _e('Import', 'pgi'); ?>" onclick="pgi_import_data(event);" />
        </div>
      </form>
      <?php
    }
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Getters & Setters                                                                  //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Private Methods                                                                    //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Listeners                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
}
?>