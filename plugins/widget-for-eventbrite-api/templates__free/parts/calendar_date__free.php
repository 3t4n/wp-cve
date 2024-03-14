<?php
/**
 * @var mixed $data Custom data for the template.
 * phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped -- template files escaped at output
 */
if ( $data->utilities->get_element( 'date', $data->args ) ): ?>
    <div class="eaw-calendar-date">
        <?php
            $timestamp = strtotime( $data->utilities->get_event_start()->local );
        ?>
        <div class="eaw-calendar-date-month"><?php echo wp_date('M', $timestamp); ?></div>
        <div class="eaw-calendar-date-day"><?php echo wp_date('j', $timestamp); ?></div>
    </div>
<?php endif;
