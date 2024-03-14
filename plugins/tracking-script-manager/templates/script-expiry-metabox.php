<div class="misc-pub-section expiration-status" style="padding-left:0px;">
    <?php    
        $expiry_date_type = $expiry_data['type'] ?: __('Never expires', TRACKING_SCRIPT_TEXTDOMAIN );
    ?>    
    <?php  
        if( $expiry_date_type == 'Schedule' ){  
            echo sprintf( __( 'Status: <strong>Scheduled %s to %s</strong>', TRACKING_SCRIPT_TEXTDOMAIN ), $expiry_data['start_date'], $expiry_data['end_date'] );          
        }else{ 
            echo sprintf( __( 'Status: <strong>%s</strong>', TRACKING_SCRIPT_TEXTDOMAIN ), __('Never expires',TRACKING_SCRIPT_TEXTDOMAIN) ); 
        } 
    ?>    
    <a href="#edit_timestamp" class="edit-timestamp hide-if-no-js" role='button'><?php esc_html_e( 'Edit', TRACKING_SCRIPT_TEXTDOMAIN ); ?></a> 

    <div class="expiry-date-fields hide-if-js">
        <legend class="screen-reader-text"><span><?php esc_html_e( 'Expiry Date', TRACKING_SCRIPT_TEXTDOMAIN ); ?></span></legend>
        <label for="expire_date_type_never">
            <input name="r8_tsm_script_expiry" type="radio" id="expire_date_type_never" data-title="<?php esc_attr_e( 'Never expires', TRACKING_SCRIPT_TEXTDOMAIN ); ?>" value="Never" <?php echo $expiry_date_type != 'Schedule' ? 'checked="checked"' : ''; ?> >
            <?php esc_html_e( 'Never expires', TRACKING_SCRIPT_TEXTDOMAIN ); ?>
        </label><br />
        <label for="expire_date_type_schedule">
            <input name="r8_tsm_script_expiry" type="radio" id="expire_date_type_schedule" value="Schedule" <?php echo $expiry_date_type == 'Schedule' ? 'checked="checked"' : ''; ?> >
            <?php esc_html_e( 'Schedule', TRACKING_SCRIPT_TEXTDOMAIN ); ?><br>
        </label> 
            <div class="schedule-row <?php echo $expiry_date_type == 'Schedule' ? '' : 'hidden'; ?>">
                <div class="col">
                    <span>Start Date:</span>
                    <input type="text" name="schedule_start" autocomplete="off" id="schedule-start" value="<?php echo $expiry_data['start_date'] ?: ''; ?>">
                </div> 
                <div class="col">
                    <span>End Date:</span>
                    <input type="text" name="schedule_end" autocomplete="off" id="schedule-end" value="<?php echo $expiry_data['end_date'] ?: ''; ?>">
                </div> 
                <span class="err-msg"></span>
            </div>
        <a href="#" class="update-timestamp hide-if-no-js button schedule">OK</a>
        <a href="#" class="cancel-timestamp hide-if-no-js button-cancel">Cancel</a>
    </div> 
</div>