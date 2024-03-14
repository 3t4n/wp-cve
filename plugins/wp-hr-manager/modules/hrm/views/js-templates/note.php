<div class="status-form-wrap">
     <?php //do_action( 'wphr-hr-employee-form-basic' ); ?>
   <?php do_action( 'wphr-hr-employee-job-staus'); ?>

    <?php wp_nonce_field( 'employee_update_employment' ); ?>
    <input type="hidden" name="action" id="status-action" value="employee_update_notes">
  //  <input type="hidden" name="user_id" id="emp-id" value="{{ data.id }}">

 
</div>
