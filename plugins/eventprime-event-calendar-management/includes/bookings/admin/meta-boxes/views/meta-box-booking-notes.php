<?php
$notes = maybe_unserialize(get_post_meta($post->ID, 'em_notes', true));
?>
<div class="emagic">
    <div class="panel-wrap ep_event_metabox ep-box-wrap ep-p-0">
        <div class="ep-booking-notes-form">
            <textarea class="ep-notes-message" name="note" id="ep-booking-note" placeholder="<?php esc_html_e('Add note about booking','eventprime-event-calendar-management');?>"></textarea>
            <button type="button" class="ep-add-btn add_note button" id="ep-add-notes"><?php esc_html_e('Add','eventprime-event-calendar-management');?> </button>
            <span class="spinner"></span>
        </div>
        <div class="ep-note-area">
            <ul class="ep-notes" id="ep-notes-lists">
                <?php 
                if(!empty($notes)){
                    foreach( array_reverse($notes) as $note ){?>
                        <li><?php echo wp_kses_post( $note );?></li><?php
                    }
                }?> 
            </ul>
        </div>
    </div>
</div>