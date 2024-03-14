<?php $sizes = get_intermediate_image_sizes();?>
<form id="coords" action="" method="post" enctype="multipart/form-data">
<input type="hidden" name="th_edit_option" id="th_edit_option" value="imageCrop">
<?php wp_nonce_field('th_edit_save_action', 'th_edit_save_action_field');?>
<table width="100%" border="0" class="ap-table">
  <tbody>
  <tr>
      <td>
      <div class="tools">
      <storng class="selected-tool"><?php _e('Tools', 'thumbnail-editor');?></storng>
      <img src="<?php echo plugins_url(THE_PLUGIN_DIR . '/images/crop.png'); ?>" class="tool-item" alt="Crop" onClick="ImageEditor('crop');" title="Crop (Drag inside the image to Crop)" id="crop-tool">
       <img src="<?php echo plugins_url(THE_PLUGIN_DIR . '/images/resize.png'); ?>" class="tool-item" alt="Resize" onClick="ImageEditor('resize');" title="Resize (Drag image from Bottom / Right Corners to Resize)" id="resize-tool">
      <i><?php _e('Select a tool to start editing', 'thumbnail-editor');?></i>
      </div>
      </td>
    </tr>
    <tr>
      <td align="center"><img src="<?php echo $full_image[0]; ?>" id="target" style="border:1px dashed #CCCCCC;"></td>
    </tr>
    <tr>
      <td align="center">
        <input type="hidden" name="att_id" value="<?php echo $att_id; ?>">
        <div class="inline-labels">
        <input type="hidden" size="4" id="x1" name="x1" />
        <input type="hidden" size="4" id="y1" name="y1" />
        <input type="hidden" size="4" id="x2" name="x2" />
        <input type="hidden" size="4" id="y2" name="y2" />
        <p>
        <label><strong><?php _e('Crop Width', 'thumbnail-editor');?></strong> <input type="text" size="4" id="w" name="w" readonly /></label>
        <label><strong><?php _e('Crop Height', 'thumbnail-editor');?></strong> <input type="text" size="4" id="h" name="h" readonly /></label>
        <label><strong><?php _e('Resize Width', 'thumbnail-editor');?></strong> <input type="text" size="4" id="rw" name="rw" readonly value="<?php echo $full_image[1]; ?>"/></label>
        <label><strong><?php _e('Resize Height', 'thumbnail-editor');?></strong> <input type="text" size="4" id="rh" name="rh" readonly value="<?php echo $full_image[2]; ?>"/></label>
        </p>
        <p id="aspr" style="display: none;">
        <label><strong><?php _e('Crop Aspect Ratio', 'thumbnail-editor');?></strong> <input type="number" step="1" min="0" size="4" id="art" name="art"/> / <input type="number" step="1" min="1" size="4" id="ars" name="ars"/></label>
        <input type="button" name="setar" value="<?php _e('Set', 'thumbnail-editor');?>" class="button" onclick="setAspectRatio()">
        </p>
        <p>
        <select name="crop_type">
            <?php
foreach ($sizes as $key => $value) {
    $s = $this->get_image_sizes($value);
    echo '<option value="' . $value . '">' . $value . ' - ' . $s['width'] . 'x' . $s['height'] . '</option>';
}
?>
        </select></p>
        <p><input type="submit" name="submit" value="<?php _e('Edit Image', 'thumbnail-editor');?>" class="button button-primary button-ap-large"></p>
        <p><?php _e('Once Image is updated you can check the updated image from the below list of thumbnails. The above image will not be effected by the changes you make.', 'thumbnail-editor');?></p>
        </div>
      </td>
    </tr>
    <tr>
    <td><hr>
    <h3><?php _e('List of Available Thumbnail Types', 'thumbnail-editor');?></h3>

    <?php
foreach ($sizes as $key => $value) {
    $s = $this->get_image_sizes($value);
    echo '<a href="#' . $value . '" class="button">' . $value . '</a> ';
}
?>

    <p><?php printf(esc_html__('If thumbnail images doesn\'t reflect the changes you made please use %s or clear browser cache. More editing options are available with %s version.', 'thumbnail-editor'), '<strong>Ctrl + F5</strong>', '<a href="https://www.aviplugins.com/thumbnail-editor-pro/" target="_blank">PRO</a>');?>
    </p>
    </td>
    </tr>
    <?php
foreach ($sizes as $key => $value) {
    $full_image = @wp_get_attachment_image_src($att_id, $value);
    ?>
        <tr>
            <td>
              <a id="<?php echo $value; ?>"></a>
            <div class="thumb-items">
                <p><h3><?php echo $value; ?></h3></p>

                <div class="thumbs"><img src="<?php echo $full_image[0]; ?>"></div>
                <p><strong>Usage</strong> <br>
                <span style="color:#0000ff;">get_thumb_image_src( "attachment id", "thumbnail type" ); </span>
                <br>
                <br>
                <strong><?php _e('Example', 'thumbnail-editor');?></strong>
                <br>
                // this will output the image src <br>
                <span style="color:#0000ff;">echo get_thumb_image_src( "<?php echo $att_id; ?>", "<?php echo $value; ?>" );</span> <br>
                <strong>Shortcode</strong> [thumb_image_src att_id="<?php echo $att_id; ?>" type="<?php echo $value; ?>"]
                <br>
                <br>
                // this will output the image <br>
                <span style="color:#0000ff;"> echo get_thumb_image( "<?php echo $att_id; ?>", "<?php echo $value; ?>" ); </span>
                <br>
                <strong>Shortcode</strong> [thumb_image att_id="<?php echo $att_id; ?>" type="<?php echo $value; ?>"] <br>

                </p>
            </div>
            </td>
        </tr>
    <?php
}
?>
  </tbody>
</table>
 </form>