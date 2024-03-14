<?php if($this->team_onboarding_step() < 999) { ?>
  <div class="div-block-4 onboarding-incomplete">
    <h2 style="margin-bottom: 4px;">Onboarding Step: <?php echo $this->team_onboarding_step() + 1 ?> </h2>
    <a class="cta-connect-button onboarding-link" href="<?php echo $this->dashboard_url('wp-plugin-onboarding-next-step'); ?>" target="blank">
      <?php echo $this->team_onboarding_step_text(); ?>
    </a>
  </div>
<?php } else { ?>
  <div class="div-block-4 onboarding-complete">
    <h2>Onboarding Complete!</h2>
    <a class="link" href="<?php echo esc_html($this->connect_url()) ?>" target="blank">
      Click here to sync your connection
    </a>
  </div>
<?php } ?>
