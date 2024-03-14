
<div class="<?php echo $data->class_text; ?>" data-workshopID="<?php echo $data->post_id; ?>">
  <?php echo $data->favorite; ?>
  <h4 class="title"><?php echo $data->title; ?></h4>
  <p class="quick_info"><?php echo $data->presenter;?><?php echo $data->location; ?></p>
  <div class="details">
    <div class="description"><?php echo $data->description; ?></div>
    <?php echo $data->image; ?>
    <div class="bio"><?php echo $data->presenter_bio; ?></div>
    <?php echo $data->files; ?>
  </div>
  <div class="data">
    <?php echo $data->session_text; ?>
    <?php echo $data->limit; ?>
    <?php echo $data->themes_html; ?>
    <?php echo $data->keywords_html; ?>
  </div>
  <?php echo $data->edit_link; ?>
</div>
