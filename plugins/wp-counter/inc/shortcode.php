<?php
//shortcode start
add_shortcode('wpcounter', 'l24bd_wpcounter_make_sortcode');
function l24bd_wpcounter_make_sortcode($atts)
{    $options = shortcode_atts(array('headline' => ''), $atts);
    ?>
    <?php $data=l24bd_wpcounter_get_visitor_data(); ?>
        <table width="100%">
            <tr>
                <td colspan="2"><strong><span class="dashicons dashicons-chart-area"></span> <?php echo $options['headline']=="" ? "Visitor Status" : $options['headline']; ?></strong></td>
            </tr>
            <tr>
                <td><span class="dashicons dashicons-calendar-alt"></span> Today</td>
                <td><?php echo $data['today'] ?></td>
            </tr>
            <tr>
                <td><span class="dashicons dashicons-calendar-alt"></span> Yesterday</td>
                <td><?php echo $data['yesterday'] ?></td>
            </tr>
            <tr>
                <td><span class="dashicons dashicons-calendar-alt"></span> This Week</td>
                <td><?php echo $data['thisWeek']; ?></td>
            </tr>
            <tr>
                <td><span class="dashicons dashicons-calendar-alt"></span> This Month</td>
                <td><?php echo $data['thisMonth'] ?></td>
            </tr>
            <tr>
                <td><span class="dashicons dashicons-calendar-alt"></span> Total</td>
                <td><?php echo $data['totalVisitor'] ?></td>
            </tr>
        </table>
<?php
}
