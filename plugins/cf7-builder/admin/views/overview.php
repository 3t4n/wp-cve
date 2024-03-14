<?php

class ViewOverview_cf7b {
  public function display() {
    ?>
    <div class="cf7b-overview-header">
      <img src="<?php echo CF7B_URL ?>/images/logo.jpg">
      <h1>CF7 Builder</h1>
      
    </div>

    <div class="cf7b-overview-content">

      <div class="cf7b-row-form">
        <img src="<?php echo CF7B_URL ?>/images/form.jpg">
        <div>
          <h3>Drag & Drop Form</h3>
          <p>Create your form easy with drag & drop</p>
        </div>
      </div>

      <div class="cf7b-row-theme">
        <div>
          <h3>Themes</h3>
          <p>Create your beautiful and elegant form</p>
        </div>
        <img src="<?php echo CF7B_URL ?>/images/theme.jpg">
      </div>

      <div class="cf7b-row-form">
        <img src="<?php echo CF7B_URL ?>/images/redirect.jpg">
        <div>
          <h3>Action after submit</h3>
          <p>Create your own thank you page</p>
        </div>
      </div>

      <div class="cf7b-row-theme">
        <div>
          <h3>Submissions</h3>
          <p>Follow your form submits</p>
        </div>
        <img src="<?php echo CF7B_URL ?>/images/submission.jpg">
      </div>


    </div>
    <?php
  }
}