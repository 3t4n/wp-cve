<div class="performance-tab-wrap">


    <?php $performance = $employee->get_performance( $employee->id );

    $fields = get_option( 'wphr-employee-fields');
$jobes = array();
$jobes1 = array();
$jobes2 = array();

if(!empty($fields))
{
foreach ($fields as $key => $field)
 {
   if($field['tab']=='Performance' && $field['section']=='review')
    {
   array_push($jobes, $field);
   }
   elseif ($field['tab']=='Performance' && $field['section']=='comments')
    {
    array_push($jobes1, $field);
       # code...
   }
   elseif ($field['tab']=='Performance' && $field['section']=='goal')
    {

    array_push($jobes2, $field);
   }
}

}
    ?>


    <?php $performance_rating = wphr_performance_rating(); ?>

    <h3><?php _e( 'Performance Reviews', 'wphr' ); ?></h3>
    <?php
    if ( current_user_can( 'wphr_create_review' ) ) {
        ?>
        <a href="#" id="wphr-empl-performance-reviews" class="action button" data-id="<?php echo $employee->id; ?>" data-template="wphr-employment-performance-reviews" data-title="<?php _e( 'Performance Reviews', 'wphr' ); ?>"><span class="dashicons dashicons-plus wphr-performance-dashicon"></span> <?php _e( 'Add Performance Reviews', 'wphr' ); ?></a>
        <?php
    }
    ?>


<div class="inside">
    <table class="widefat">
        <thead>
            <tr>
                <th><?php _e( 'Date', 'wphr' ) ?></th>
                <th><?php _e( 'Reporting To', 'wphr' ) ?></th>
                <th><?php _e( 'Job Knowledge', 'wphr' ) ?></th>
                <th><?php _e( 'Work Quality', 'wphr' ) ?></th>
                <th><?php _e( 'Attendance/Punctuality', 'wphr' ) ?></th>
                <th><?php _e( 'Communication/Listening', 'wphr' ) ?></th>
                <th><?php _e( 'Dependablity', 'wphr' ) ?></th>
             <?php 
foreach ($jobes as $key => $value) {
    # code...
    ?>
<th><?php echo $value['label']; ?></th>
    <?php
}

?>






                <th>&nbsp;</th>
            </tr>
        </thead>

        <tbody>
        <?php
            if ( $performance['reviews'] ) {

                foreach ( $performance['reviews'] as $num => $row ) {
                    ?>
                    <tr class="<?php echo $num % 2 == 0 ? 'alternate' : 'odd'; ?>">
                        <td>
                            <?php echo wphr_format_date( $row->performance_date ); ?>
                        </td>

                        <td>
                            <?php
                                $reporting_user = new \WPHR\HR_MANAGER\HRM\Employee( intval( $row->reporting_to ) );
                                echo $reporting_user->get_full_name();
                            ?>
                        </td>

                        <td><?php echo isset( $performance_rating[$row->job_knowledge] ) ? $performance_rating[$row->job_knowledge] : '-'; ?></td>

                        <td><?php echo isset( $performance_rating[$row->work_quality] ) ? $performance_rating[$row->work_quality] : '-'; ?></td>

                        <td><?php echo isset( $performance_rating[$row->attendance] ) ? $performance_rating[$row->attendance] : '-'; ?></td>

                        <td><?php echo isset( $performance_rating[$row->communication] ) ? $performance_rating[$row->communication] : '-'; ?></td>

                        <td><?php echo isset( $performance_rating[$row->dependablity] ) ? $performance_rating[$row->dependablity] : '-'; ?></td>

                        <?php 
                foreach ($jobes as $keyr => $valuex)
                 {
                ?>
                        <td>
                        <?php

                        $additional = null;
                        if($row->additional)
                        {
                         $additional = unserialize($row->additional);  
                                            }
                                            if($additional)
                                             {
                        foreach ($additional as $kxey => $value) 
                        {
                           if($kxey==$valuex['name'])
                           {
                            echo $value;
                           }
                        }
                    }
                    ?>

                    </td>
                        <?php
                    }

                    ?> 




                        <td class="action">
                            <?php if ( current_user_can( 'wphr_delete_review' ) ) { ?>
                                <a href="#" class="performance-remove" data-id="<?php echo $row->id; ?>"><span class="dashicons dashicons-trash"></span></a>
                            <?php } ?>
                        </td>

                    </tr>
                    <?php
                }
            } else {
                ?>
                <tr class="alternate">
                    <td colspan="8"><?php _e( 'No performance reviews found!', 'wphr' ); ?></td>
                </tr>
            <?php } ?>
        </tbody>
        <tbody>
            <tr><td><ul>
                <?php //do_action( 'wphr-hr-employee-single-performace-review', $employee ); ?></ul>
            </td></tr>
        </tbody>   
         </table>
</div>
    <h3><?php _e( 'Performance Comments', 'wphr' ); ?></h3>
    <?php if ( current_user_can( 'wphr_create_review' ) ) { ?>
        <a href="#" id="wphr-empl-performance-comments" class="action button" data-id="<?php echo $employee->id; ?>" data-template="wphr-employment-performance-comments" data-title="<?php _e( 'Performance Comments', 'wphr' ); ?>">
            <span class="dashicons dashicons-plus wphr-performance-dashicon">
                
            </span> <?php _e( 'Add Performance Comments', 'wphr' ); ?></a>
    <?php } ?>
    <div class="inside">
    <table class="widefat">
        <thead>
            <tr>
                <th><?php _e( 'Date', 'wphr' ) ?></th>
                <th><?php _e( 'Reviewer', 'wphr' ) ?></th>
                <th><?php _e( 'Comments', 'wphr' ) ?></th>
                <?php 
                foreach ($jobes1 as $key => $value) {
                    # code...
                    ?>
                <th><?php echo $value['label']; ?></th>
                    <?php
                }

                ?>

                <th>&nbsp;</th>
            </tr>
        </thead>

        <tbody>
            <?php
            if ( $performance['comments'] ) {

                foreach ( $performance['comments'] as $num => $row ) {
                    ?>
                    <tr class="<?php echo $num % 2 == 0 ? 'alternate' : 'odd'; ?>">
                        <td>
                            <?php echo wphr_format_date( $row->performance_date ); ?>
                        </td>

                        <td>
                            <?php
                                $reporting_user = new \WPHR\HR_MANAGER\HRM\Employee( intval( $row->reviewer ) );
                                echo $reporting_user->get_full_name();
                            ?>
                        </td>

                        <td><?php echo esc_textarea( $row->comments ); ?></td>
                        <?php 
                     foreach ($jobes1 as $keyr => $valuex)
                 {
                ?>
                        <td>
                        <?php

                        $additional = null;
                        if($row->additional)
                        {
                         $additional = unserialize($row->additional);  
                                            }
                                            if($additional)
                                             {
                        foreach ($additional as $kxey => $value) 
                        {
                           if($kxey==$valuex['name'])
                           {
                            echo $value;
                           }
                        }
                    }
                    ?>

                    </td>
                        <?php
                    }

                    ?> 

                        <td class="action">
                            <?php if ( current_user_can( 'wphr_delete_review' ) ) { ?>
                                <a href="#" class="performance-remove" data-id="<?php echo $row->id; ?>"><span class="dashicons dashicons-trash"></span></a>
                            <?php } ?>
                        </td>

                    </tr>
                    <?php
                }
            } else {
                ?>
                <tr class="alternate">
                    <td colspan="4"><?php _e( 'No performance comments found!', 'wphr' ); ?></td>
                </tr>
            <?php } ?>
        </tbody>
        <tbody>
            <tr><td><ul>
                 <?php //do_action( 'wphr-hr-employee-single-performance-comments', $employee ); ?></ul>
            </td></tr>
        </tbody>   
         </table>
    </table>
    </div>
    <h3><?php _e( 'Performance Goals', 'wphr' ); ?></h3>
    <?php if ( current_user_can( 'wphr_create_review' ) ) { ?>
        <a href="#" id="wphr-empl-performance-goals" class="action button" data-id="<?php echo $employee->id; ?>" data-template="wphr-employment-performance-goals" data-title="<?php _e( 'Performance Goals', 'wphr' ); ?>"><span class="dashicons dashicons-plus wphr-performance-dashicon"></span> <?php _e( 'Add Performance Goals', 'wphr' ); ?></a>
    <?php } ?>
    <div class="inside">
    <table class="widefat">
        <thead>
            <tr>
                <th><?php _e( 'Set Date', 'wphr' ) ?></th>
                <th><?php _e( 'Completion Date', 'wphr' ) ?></th>
                <th><?php _e( 'Goal Description', 'wphr' ) ?></th>
                <th><?php _e( 'Employee Assessment', 'wphr' ) ?></th>
                <th><?php _e( 'Supervisor', 'wphr' ) ?></th>
                <th><?php _e( 'Supervisor Assessment', 'wphr' ) ?></th>

                 <?php 
                foreach ($jobes2 as $key => $value) {
                    # code...
                    ?>
                <th><?php echo $value['label']; ?></th>
                    <?php
                }

                ?>
                <th>&nbsp;</th>
            </tr>
        </thead>

        <tbody>
            <?php
            if ( $performance['goals'] ) {

                foreach ( $performance['goals'] as $num => $row ) {
                    ?>
                    <tr class="<?php echo $num % 2 == 0 ? 'alternate' : 'odd'; ?>">
                        <td><?php echo wphr_format_date( $row->performance_date ); ?></td>
                        <td><?php echo wphr_format_date( $row->completion_date ); ?></td>
                        <td><?php echo esc_textarea( $row->goal_description ); ?></td>
                        <td><?php echo esc_textarea( $row->employee_assessment ); ?></td>
                        <td>
                            <?php
                                $reporting_user = new \WPHR\HR_MANAGER\HRM\Employee( intval( $row->supervisor ) );
                                echo $reporting_user->get_full_name();
                            ?>
                        </td>
                        <td><?php echo esc_textarea( $row->supervisor_assessment ); ?></td>


                           <?php 
                     foreach ($jobes2 as $keyr => $valuex)
                 {
                ?>
                        <td>
                        <?php

                        $additional = null;
                        if($row->additional)
                        {
                         $additional = unserialize($row->additional);  
                                            }
                                            if($additional)
                                             {
                        foreach ($additional as $kxey => $value) 
                        {
                           if($kxey==$valuex['name'])
                           {
                            echo $value;
                           }
                        }
                    }
                    ?>

                    </td>
                        <?php
                    }

                    ?> 

                        <td class="action">
                            <?php if ( current_user_can( 'wphr_delete_review' ) ) { ?>
                                <a href="#" class="performance-remove" data-id="<?php echo $row->id; ?>"><span class="dashicons dashicons-trash"></span></a>
                            <?php } ?>
                        </td>

                    </tr>
                    <?php
                }
            } else {
                ?>
                <tr class="alternate">
                    <td colspan="7"><?php _e( 'No performance goals found!', 'wphr' ); ?></td>
                </tr>
            <?php } ?>
        </tbody>
        <tbody>
            <tr><td><ul>
                 <?php //do_action( 'wphr-hr-employee-single-performance-goal', $employee ); ?></ul>
            </td></tr>
        </tbody>   
    </table>
    </div>
</div>
