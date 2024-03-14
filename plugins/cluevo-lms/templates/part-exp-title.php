<?php
  global $current_user;
?>
<div class="cluevo-user-widget">
  <div class="cluevo-user-name"><?php echo esc_html($current_user->display_name); ?></div>
  <div class="cluevo-level-container" data-lms-user-level="<?php echo esc_attr(cluevo_the_lms_user_level()); ?>">
    <div class="cluevo-level"><?php esc_html(cluevo_the_lms_user_level()); ?></div>
    <div class="cluevo-exp-bar-container">
      <div class="cluevo-exp-values-container">
        <p class="cluevo-level-text"><?php esc_html_e("Level", "cluevo"); ?></p>
        <p class="cluevo-exp-stats"><?php cluevo_the_lms_user_exp(); ?> / <?php cluevo_the_lms_user_exp_next(); ?> <span class="cluevo-level-pct"><?php cluevo_the_lms_user_exp_pct(); ?>%</span></p>
      </div>
      <div class="cluevo-exp-bar">
        <div class="cluevo-progress-container">
          <span class="cluevo-progress" style="width: <?php echo 100 - cluevo_get_the_lms_user_exp_pct(); ?>%;" data-value="<?php echo esc_attr(cluevo_get_the_lms_user_exp()); ?>" data-max="<?php echo esc_attr(cluevo_get_the_lms_user_exp_next()); ?>"></span>
        </div>
      </div>
        <?php
          $comps = cluevo_get_users_competences();
          $count = 0;
          foreach ($comps as $c) { if (!empty($c->score->value)) $count++; }
        ?>
      <div class="cluevo-exp-sub-container">
        <div class="cluevo-competences">
          <?php echo esc_html($count) . " " . esc_html__("Competences", "cluevo"); ?>
        </div>
        <?php if (cluevo_has_lms_user_title()) { ?>
        <div class="cluevo-title">
          <?php cluevo_the_lms_user_title(); ?>
        </div>
        <?php } ?>
      </div>
    </div>
  </div>

  <?php if ($count > 0) { ?>
    <?php $points = cluevo_get_user_competence_polygon_points(); ?>
    <?php wp_add_inline_script('polygraph-js', "const cluevoPolygraphData = JSON.parse('" . json_encode($points) . "');", 'after'); ?>
    <div id="cluevo-polygraph">
      <svg width="200" height="200">
        <cluevo-polygraph></cluevo-polygraph>
      </svg>
    </div>
  <?php } ?>

</div>
