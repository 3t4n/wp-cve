<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
    /**
     * timestamp.php - Outputs widget form
     * Display timestamp edit fields for IntelliWidget
     */
        global $wp_locale;

        $time_adj = current_time( 'timestamp' );
        $jj = ( $post_date ) ? mysql2date( 'd', $post_date, FALSE ) : gmdate( 'd', $time_adj );
        $mm = ( $post_date ) ? mysql2date( 'm', $post_date, FALSE ) : gmdate( 'm', $time_adj );
        $aa = ( $post_date ) ? mysql2date( 'Y', $post_date, FALSE ) : gmdate( 'Y', $time_adj );
        $hh = ( $post_date ) ? mysql2date( 'H', $post_date, FALSE ) : gmdate( 'H', $time_adj );
        $mn = ( $post_date ) ? mysql2date( 'i', $post_date, FALSE ) : gmdate( 'i', $time_adj );
        $ss = ( $post_date ) ? mysql2date( 's', $post_date, FALSE ) : gmdate( 's', $time_adj );

        $cur_jj = gmdate( 'd', $time_adj );
        $cur_mm = gmdate( 'm', $time_adj );
        $cur_aa = gmdate( 'Y', $time_adj );
        $cur_hh = gmdate( 'H', $time_adj );
        $cur_mn = gmdate( 'i', $time_adj );

        $month = '<select id="'.$field.'_mm" name="'.$field.'_mm" class="intelliwidget-mm">' ."\n";
        for ( $i = 1; $i < 13; $i = $i +1 ) {
            $monthnum = zeroise( $i, 2 );
            $month .= "            " . '<option value="' . $monthnum . '"';
            if ( $i == $mm )
                $month .= ' selected="selected"';
                /* translators: 1: month number ( 01, 02, etc. ), 2: month abbreviation */
            $month .= '>' . $wp_locale->get_month_abbrev( $wp_locale->get_month( $i ) ) . "</option>\n";
        }
        $month .= '</select>';

        $day = '<input type="text" id="'.$field.'_jj" class="intelliwidget-jj" name="'.$field.'_jj" value="' . $jj . '" size="2" maxlength="2" autocomplete="off" />';
        $year = '<input type="text" id="'.$field.'_aa" class="intelliwidget-aa" name="'.$field.'_aa" value="' . $aa . '" size="4" maxlength="4" autocomplete="off" />';
        $hour = '<input type="text" id="'.$field.'_hh" class="intelliwidget-hh" name="'.$field.'_hh" value="' . $hh . '" size="2" maxlength="2" autocomplete="off" />';
        $minute = '<input type="text" id="'.$field.'_mn" class="intelliwidget-mn" name="'.$field.'_mn" value="' . $mn . '" size="2" maxlength="2" autocomplete="off" />';

        echo '<div class="timestamp-wrap">';
        /* translators: 1: month input, 2: day input, 3: year input, 4: hour input, 5: minute input */
        printf( __( '%1$s%2$s, %3$s @ %4$s : %5$s', 'intelliwidget' ), $month, $day, $year, $hour, $minute );

        echo '</div><input type="hidden" id="'.$field.'_ss" name="'.$field.'_ss" value="' . $ss . '" />';

        echo "\n\n";
        foreach ( array( 'mm', 'jj', 'aa', 'hh', 'mn' ) as $timeunit ) {
            echo '<input type="hidden" id="'.$field.'_hidden_' . $timeunit . '" name="'.$field.'_hidden_' . $timeunit . '" value="' . ( ( $post_date ) ? $$timeunit : '' ) . '" />' . "\n";
            $cur_timeunit = 'cur_' . $timeunit;
            echo '<input type="hidden" id="'. $field . '_' . $cur_timeunit . '" name="'. $field . '_' . $cur_timeunit . '" value="' . $$cur_timeunit . '" />' . "\n";
        }
?>
<p> <a href="#edit_timestamp" id="<?php echo $field; ?>-save" class="intelliwidget-save-timestamp hide-if-no-js button">
  <?php _e( 'OK', 'intelliwidget' ); ?>
  </a> <a href="#edit_timestamp" id="<?php echo $field; ?>-clear" class="intelliwidget-clear-timestamp hide-if-no-js button">
  <?php _e( 'Clear', 'intelliwidget' ); ?>
  </a> <a href="#edit_timestamp" id="<?php echo $field; ?>-cancel" class="intelliwidget-cancel-timestamp hide-if-no-js">
  <?php _e( 'Cancel', 'intelliwidget' ); ?>
  </a> </p>
