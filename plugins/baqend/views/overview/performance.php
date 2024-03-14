<div class="box-wrap" id="box-performance">
    <h3>
        <i class="iqon-measure iqon-blue iqon-large"></i>
        <span class="box-heading"><?php _e('Your Website’s Performance Metrics', 'baqend') ?></span>
    </h3>

    <div class="box-row">
        <section class="box box-intro">
            <p><?php _e('<strong>Note:</strong> Tools such as Google PageSpeed Insights or Pingdom cannot provide accurate results for Speed Kit, as they do not consider Service Workers.', 'baqend') ?></p>
            <p>
                <a href="https://www.baqend.com/guide/topics/speed-kit/analyzer/" target="_blank" rel="nofollow">
                    <?php _e('Learn how we measure your WordPress’s performance.', 'baqend') ?>
                </a>
            </p>

            <?php if ( $comparison === null ): ?>
                <a href="https://test.speed-kit.com/" target="_blank" rel="nofollow" class="button">
                    <?php _e('Test with Page Speed Analyzer', 'baqend') ?>
                </a>
            <?php else: ?>
                <a href="https://test.speed-kit.com/test/<?php echo str_replace( '/db/TestOverview/', '', $comparison->getId() ) ; ?>" target="_blank" rel="nofollow" class="button">
                    <?php _e('Show in Page Speed Analyzer', 'baqend') ?>
                </a>
            <?php endif; ?>
        </section>

        <?php if ( $comparison !== null && $comparison->getFields() !== null ): ?>
            <?php foreach ( $comparison->getFields() as $field => $values ): ?>
                <section class="box">
                    <h4><? _e( camel_case_to_human( $field ), 'baqend' ); ?></h4>

                    <?php if ( ! $exceeded && $speed_kit ): ?>
                        <span class="statistics-number"><?php echo number_format_i18n( $values['speedKit'], 0 ); ?> ms</span>
                    <?php else: ?>
                        <span class="statistics-number"><?php echo number_format_i18n( $values['competitor'], 0 ); ?> ms</span>
                    <?php endif; ?>

                    <?php if ( $values['factors'] > 100 ): ?>
                        <?php if ( ! $exceeded && $speed_kit ): ?>
                            <p class="statistics-diff statistics-diff-pos">
                                <i class="iqon-badge badge badge-green"><i class="iqon-badge-check"></i></i>
                                <?php echo sprintf( __( '%s× faster through Speed Kit', 'baqend' ), number_format_i18n( $values['factors'] / 100, 2 ) ); ?>
                            </p>
                        <?php else: ?>
                            <p class="statistics-diff statistics-diff-neg">
                                <i class="iqon-badge badge badge-red"><i class="iqon-badge-warn"></i></i>
                                <?php echo sprintf( __( 'Could be <strong>%s× faster</strong> with Speed Kit enabled', 'baqend' ), number_format_i18n( $values['factors'] / 100, 2 ) ); ?>
                            </p>
                        <?php endif; ?>
                    <?php else: ?>
                        <?php if ( ! $exceeded && $speed_kit ): ?>
                            <p class="statistics-diff statistics-diff-pos">
                                <i class="iqon-badge badge badge-green"><i class="iqon-badge-check"></i></i>
                                <?php _e('Optimized', 'baqend'); ?>
                            </p>
                        <?php else: ?>
                            <p class="statistics-diff"><?php _e('Optimized', 'baqend'); ?></p>
                        <?php endif; ?>
                    <?php endif; ?>
                </section>
            <?php endforeach; ?>
        <?php else: ?>
            <section class="box box-placeholder-3">
                <p class="box-placeholder-text"><?php _e('We are currently loading performance data for your WordPress, please hold tight!', 'baqend') ?></p>
            </section>
        <?php endif; ?>
    </div>
</div>
