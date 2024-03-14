<?php
class View_cf7b {
  public function cf7b_display() {
  }

  public function cf7b_popup_revision( $revisions ) {
    ?>
    <div class="cf7b-popup-overlay cf7b-hidden">
    <div id="cf7b-revision-popup">
      <div class="cf7b-revision-popup-header">
        <h2>Revisions</h2>
        <span class="dashicons dashicons-no cf7b-popup-close"></span>
      </div>
      <div class="cf7b-revision-popup-content">
        <div class="cf7b-revision-popup-row cf7b-title">
          <div class="cf7b-revision-ind">N:</div>
          <div class="cf7b-revision-date">Date</div>
          <div class="cf7b-revision-time">Time</div>
          <div class="cf7b-revision-but"></div>
        </div>

        <?php
        $ind = 1;
        foreach ($revisions as $rev) { ?>
          <div class="cf7b-revision-popup-row">
            <div class="cf7b-revision-ind"><?php echo intval($ind) ?></div>
            <div class="cf7b-revision-date"><?php echo esc_html($rev['date']) ?></div>
            <div class="cf7b-revision-time"><?php echo esc_html($rev['time']) ?></div>
            <div class="cf7b-revision-but">
              <?php if( $rev['status'] ) { ?>
                <span>Current</span>
              <?php } else { ?>
              <button class="button cf7b-revision-btn" data-id="<?php echo intval($rev['id']); ?>">
                View
              </button>
              <?php } ?>
            </div>
            <div></div>
          </div>
        <?php
          $ind++;
        } ?>
      </div>
    </div>
    </div>
    <?php
  }
}