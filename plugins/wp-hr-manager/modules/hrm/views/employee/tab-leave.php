<h3><?php _e('Balances', 'wphr') ?></h3>
<?php
//$leave=get_option('wphr-emp');
$fields = get_option( 'wphr-employee-fields');
$leavesarray = array();

if(!empty($fields))
{

foreach ($fields as $key => $field)
 {
   if($field['tab']=='Leave' && $field['section']=='lbalance')
    {
   array_push($leavesarray, $field);
   }
}}


global $wpdb;
$policies = wphr_hr_leave_get_policies( array( 'number' => 999 ) );
$entitlements = wphr_hr_leave_get_entitlements(array('employee_id' => $employee->id));
$entitlements_pol = wp_list_pluck($entitlements, 'policy_id');
$balance = wphr_hr_leave_get_balance($employee->id);
$visible_only_assigned_policy = wphr_get_option( 'employee_assigned_leave_policy', 'wphr_settings_general', 1 );
if ($policies) {
    ?>

    <table class="widefat">
        <thead>
            <tr>
                <th><?php _e('Leave', 'wphr') ?></th>
                <th><?php _e('Current', 'wphr') ?></th>
                <th><?php _e('Scheduled', 'wphr') ?></th>
                <th><?php _e('Available', 'wphr') ?></th>
                <th><?php _e('Period', 'wphr') ?></th>
            </tr>
        </thead>

        <tbody>

            <?php
            $hasPolicy = false;
            foreach ($policies as $num => $policy) {

                $key = array_search($policy->id, $entitlements_pol);
                $en = false;
                $name = esc_html($policy->name);
                $current = 0;
                $scheduled = 0;
                $available = $policy->value;
                $days = 0;
                $hours = 0;
                $minutes = 0;
                $scheduled_days = $scheduled_hours = $scheduled_minutes = 0;
                $available_balance_string = $scheduled_balance_string = '-';
                if (array_key_exists($policy->id, $balance)) {
                    $current = $balance[$policy->id]['entitlement'];
                    $scheduled = $balance[$policy->id]['scheduled'];
                    if (isset($balance[$policy->id]['entitlement_hours']) && isset($balance[$policy->id]['total_approved_minutes']) && isset($balance[$policy->id]['working_hours'])) {

                        $working_hours = $balance[$policy->id]['working_hours'];
                        $total_approved_minutes = $balance[$policy->id]['total_approved_minutes'];
                        $total_minutes = $balance[$policy->id]['total_minutes'];


                        $scheduled_arr = wphr_get_balance_details_from_minutes($total_approved_minutes, $working_hours);
                        if (!empty($scheduled_arr)) {
                            $scheduled_days = $scheduled_arr['days'];
                            $scheduled_hours = $scheduled_arr['hours'];
                            $scheduled_minutes = $scheduled_arr['minutes'];
                            $scheduled_balance_string = $scheduled_arr['balance_string'];
                        } else {
                            $scheduled_days = 0;
                            $scheduled_hours = 0;
                            $scheduled_minutes = 0;
                            $scheduled_balance_string = '-';
                        }


                        $available_arr = wphr_get_balance_details_from_minutes(( $total_minutes - $total_approved_minutes), $working_hours);

                        if (!empty($available_arr)) {
                            $available = $days = $available_arr['days'];
                            $hours = $available_arr['hours'];
                            $minutes = $available_arr['minutes'];
                            $available_balance_string = $available_arr['balance_string'];
                        } else {
                            $available = $days = 0;
                            $hours = 0;
                            $minutes = 0;
                            $available_balance_string = '-';
                        }
                    }
                }
                if( !$visible_only_assigned_policy && false === $key ){
                    $key = -1;
                }
                if (false !== $key) {
                    $hasPolicy = true;
                    $en = isset( $entitlements[$key] ) ?  $entitlements[$key] : false;
                ?>

                <tr class="<?php echo $num % 2 == 0 ? 'alternate' : 'odd'; ?>">
                    <td><?php echo esc_html($policy->name); ?></td>
                    <td><?php echo $en ? sprintf(__('%d days', 'wphr'), number_format_i18n($en->days)) : '-'; ?></td>
                    <td><?php echo ( $scheduled_balance_string ) ? $scheduled_balance_string : '-'; ?></td>
                    <td>
                        <?php echo ( $available_balance_string ) ? '<span class="green">' . $available_balance_string . '</span>' : '-'; ?>
                    </td>
                    <td>
                        <?php
                        if ($en) {
                            printf('%s - %s', wphr_format_date($en->from_date), wphr_format_date($en->to_date));
                        } else {
                            _e('No Policy', 'wphr');
                        }
                        ?>
                   
            <?php } ?>
        <?php } ?>
        <?php if( !$hasPolicy ): ?>
            <tr>
                <td colspan="5"><?php _e('No Policy', 'wphr'); ?></td>
            </tr>
        <?php endif; ?>

        </tbody>
        <tbody>
           <tr">
                    <td colspan="5">
                      <ul> <?php do_action( 'wphr-hr-employee-single-leave-balance', $employee ); ?></ul></td>
                            
                    </td>
            </tr>
                </tbody>

                
   
    </table>

<?php } ?>

<h3><?php _e('History', 'wphr') ?></h3>

<?php
$cur_year = date('Y');
$requests = wphr_hr_get_leave_requests(array(
    'year' => $cur_year,
    'user_id' => $employee->id,
    'status' => 1,
    'orderby' => 'req.start_date',
    'number' => -1
        ));
$leave_years = get_user_leave_years($employee->id);
$working_hours = get_employee_working_hours($employee->id);
for ($i = $cur_year; $i > $cur_year - 5; $i--) {
    $leave_years[] = $i;
}
$leave_years = array_unique( $leave_years );
arsort( $leave_years );
?>

<form action="#" id="wphr-hr-empl-leave-history">
    <?php
    wphr_html_form_input(array(
        'name' => 'leave_policy',
        'type' => 'select',
        'options' => array('all' => __('All Policy', 'wphr')) + wphr_hr_leave_get_policies_dropdown_raw()
    ));
    ?>

    <select name="year" id="year">
        <?php
            $selected = '';
            foreach ($leave_years as $year ) { 
                $selected = ( $year == $cur_year ? 'selected' : '' );
            ?>
            <option <?php echo $selected; ?> value="<?php echo $year; ?>"><?php echo $year; ?></option>
        <?php } ?>
    </select>

    <input type="hidden" name="employee_id" value="<?php echo esc_attr($employee->id); ?>">

    <?php wp_nonce_field('wphr-hr-empl-leave-history'); ?>
    <?php submit_button(__('Filter', 'wphr'), 'secondary', 'submit', false); ?>
</form>
<div class="inside">
    <table class="widefat" id="wphr-hr-empl-leave-history">
        <thead>
            <tr>
                <th><?php _e('Date', 'wphr') ?></th>
                <th><?php _e('Policy', 'wphr') ?></th>
                <th><?php _e('Description', 'wphr') ?></th>
                <th><?php _e('Days', 'wphr') ?></th>

            </tr>
        </thead>

        <tbody>
            <?php include dirname(__FILE__) . '/tab-leave-history.php'; ?>
        </tbody>
        <tbody>
            
             </td>
                    <td class="row">
                        <ul>
                      <label> <?php do_action( 'wphr-hr-employee-single-leave-history', $employee ); ?></label>
                     </ul>
             </td>     
        </tbody>
    </table>
</div>