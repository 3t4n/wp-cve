<section class="dashboard-box">
  <p class="box-heading">Your Website’s Performance Metrics</p>
</section>
<?php if ( $fields_array ): ?>
  <?php /* Shows the symbol on the left side */ ?>
  <div class="grid-container">
    <?php foreach ( $fields_array as $field => $values ): ?>
      <div class="grid-item-left grid-item-left-fmp">
        <?php if ( $values['factors'] > 100 ): ?>
          <?php if ( $speed_kit ): ?>
            <p class="statistics-diff statistics-diff-pos">
              <i class="iqon-badge badge badge-green"><i class="iqon-badge-check"></i></i>
            </p>
          <?php else: ?>
            <p class="statistics-diff statistics-diff-neg">
              <i class="iqon-badge badge badge-red"><i class="iqon-badge-warn"></i></i>
            </p>
          <?php endif; ?>
        <?php else: ?>
          <?php if ( $speed_kit ): ?>
            <p class="statistics-diff statistics-diff-pos">
              <i class="iqon-badge badge badge-green"><i class="iqon-badge-check"></i></i>
            </p>
          <?php else: ?>
            <p class="statistics-diff statistics-diff-neutral">
              <i class="iqon-badge badge badge-grey"><i class="iqon-badge-check"></i></i>
            </p>
          <?php endif; ?>
        <?php endif; ?>
      </div>

      <div class="grid-item">
      <h4>
      <?php echo abbreviate( camel_case_to_human( $field ) ); ?>
      <?php if ( $speed_kit ): ?>
        <span class="statistics-diff statistics-diff-pos">
          <?php echo sprintf( __( '%s× faster', 'baqend' ), number_format_i18n( $values['factors'] / 100, 2 ) ); ?>
        </span>
        </h4>
        </div>
        <div class="grid-item-right">
        <span>
          <?php echo number_format_i18n( $values['speedKit'], 0 ); ?> ms
        </span>
      <?php else: ?>
        <?php if ( $values['factors'] > 100 ): ?>
          <span class="statistics-diff statistics-diff-neg">
            <?php echo sprintf( __( 'Could be <strong>%s× faster</strong>', 'baqend' ), number_format_i18n( $values['factors'] / 100, 2 ) ); ?>
          </span>
        <?php else: ?>
          <span class="statistics-diff"><?php _e( 'Optimized', 'baqend' ); ?></span>
        <?php endif; ?>
        </h4>
        </div>
        <div class="grid-item-right">
        <span>
          <?php echo number_format_i18n( $values['competitor'], 0 ); ?> ms
        </span>
      <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>
  <!-- This will be shown if there is no test result or if there is no test result and no stats available. -->
<?php else: ?>
  <section class="box box-placeholder-3">
    <p class="box-placeholder-text">
      <?php _e( 'We are currently loading performance data for your WordPress, please hold tight!', 'baqend' ) ?>
    </p>
  </section>
<?php endif; ?>

<!-- Checks, whether the free plan is exceeded or if the free trial has 0 remaining days -->
<?php if ( $stats !== null && $exceeded ): ?>
  <section class="box dashboard-box">
    <div class="box-meter">
      <meter class="baqend" min="0" max="100" optimum="0" low="10" high="99" value="100" title="App exceeded"></meter>
      <span class="statistics-max"><?php echo sprintf( __( 'Exceeded' ) ); ?></span>
    </div>
    <p><?php echo sprintf( __( 'Your free plan is expired.', 'baqend' ) ); ?></p>
  </section>
<?php elseif ( $stats !== null && $stats->is_free_trial() && $stats->remaining_days === 0 ): ?>
  <section class="box dashboard-box">
    <div class="box-meter">
      <meter class="baqend" min="0" max="100" optimum="0" low="10" high="99" value="100" title="App exceeded"></meter>
      <span
        class="statistics-max"><?php echo sprintf( __( '%s days', 'baqend' ), $stats->trial_duration - $stats->remaining_days ); ?></span>
    </div>
    <p><?php echo sprintf( __( 'Your free trial is expired.', 'baqend' ) ); ?></p>
  </section>
<?php elseif ( $stats !== null ) : ?>
  <section class="box dashboard-box">
    <section>
      <?php
      if ( $stats->is_free_trial() ) {
        echo sprintf( __( 'Used %s days of Free Trial', 'baqend' ), $stats->trial_duration - $stats->remaining_days );
      } elseif ( $stats->is_free() ) {
        _e( 'Used Volume of Your Free Plan', 'baqend' );
      } elseif ( $stats->is_unlimited() ) {
        _e( 'Used Volume of Your Plan: <small>unlimited<small>', 'baqend' );
      } else {
        _e( 'Used Volume of Your Plan', 'baqend' );
      }
      ?>
    </section>
    <div class="box-meter">
      <?php if ( $stats->is_free_trial() ): ?>
      <meter class="baqend" min="0" max="<?php echo $stats->trial_duration ?>" optimum="0"
             low="<?php echo floor($stats->trial_duration * 0.3) ?>"
             high="<?php echo floor($stats->trial_duration * 0.8) ?>"
             value="<?php echo $stats->trial_duration - $stats->remaining_days; ?>"
             title="Days left">
      <?php elseif ( ! $stats->is_unlimited() && ( $stats->traffic_percent > $stats->requests_percent ) ): ?>
        <meter class="baqend" min="0" max="<?php echo $stats->traffic_max; ?>" optimum="0"
               low="<?php echo round( $stats->traffic_max * 3 / 4 ); ?>"
               high="<?php echo $stats->traffic_max; ?>"
               value="<?php echo $stats->traffic_now; ?>"
               title="GB in %">
        </meter>
        <span class="statistics-max">
        <?php echo percentage_format( $stats->traffic_max, $stats->traffic_now ); ?>
      </span>
      <?php elseif ( ! $stats->is_unlimited() && ( $stats->traffic_percent <= $stats->requests_percent ) ) : ?>
        <meter class="baqend" min="0" max="<?php echo $stats->requests_max; ?>" optimum="0"
               low="<?php echo round( $stats->requests_max * 3 / 4 ); ?>"
               high="<?php echo $stats->requests_max; ?>"
               value="<?php echo $stats->requests_now; ?>"
               title="Requests in %">
        </meter>
        <span
          class="statistics-max"><?php echo percentage_format( $stats->requests_max, $stats->requests_now ); ?></span>
      <?php endif; ?>
    </div>

    <?php if ( $stats->is_free_trial() && $stats->has_next_plan() ): ?>
      <h4><?php echo sprintf( __( 'Current Plan: %s <small>(new plan starts on %s at %s)</small>', 'baqend' ), currency_format( $stats->price_now ), $stats->next_plan_date->format( get_option( 'date_format' ) ), $stats->next_plan_date->format( get_option( 'time_format' ) ) ); ?></h4>
    <?php elseif ( $stats->is_free() ): ?>
      <h4><?php echo sprintf( __( 'Current Plan: %s <small>(%s requests, %s traffic)</small>', 'baqend' ), currency_format( $stats->price_now ), requests_format( $stats->requests_max ), traffic_format( $stats->traffic_max ) ); ?></h4>
    <?php elseif ( ! $stats->is_unlimited() && ! $stats->is_free_trial() && ! $stats->is_free() ): ?>
      <h4><?php echo sprintf( __( 'Current Limit: %s', 'baqend' ), currency_format( $stats->price_max ) ); ?></h4>
    <?php elseif ( $stats->is_unlimited() ): ?>
    <?php else: ?>
      <h4><?php echo sprintf( __( 'Current Plan: %s', 'baqend' ), currency_format( $stats->price_max ) ); ?></h4>
    <?php endif; ?>
  </section>
<?php endif; ?>
<a href="?page=baqend" class="button button-primary">
  <span><?php _e( 'Go to Dashboard', 'baqend' ) ?></span>
</a>
