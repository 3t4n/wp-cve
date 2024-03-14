<div id="ep-search-filters" class="ep-position-absolute ep-border ep-bg-white ep-rounded ep-p-4 ep-box-w-100 ep-shadow-sm" style="visibility: hidden" >
    <?php if( isset( $args->quick_search ) && $args->quick_search == 1 ){ ?>
        <div class="ep-box-row ep-text-small ep-mb-3 ep-py-4 ep-bg-light">
            <div class="ep-box-col-12 ep-text-small ep-mb-3"><span class="ep-text-small ep-fw-bold ep-text-uppercase"><?php esc_html_e( 'Quick searches', 'eventprime-event-calendar-management');?></span></div>
            <div class="ep-box-col-12">
                <span class="ep-px-2 ep-py-1 ep-di-inline-block ep-text-small ep-cursor ep-rounded-1 ep-mr-1 ep-mb-1 ep-bg-primary ep-bg-opacity-10 ep-filters-days" id="ep_filter_next_weekend" data-key="next_weekend"><?php esc_html_e( 'Next Weekend', 'eventprime-event-calendar-management');?></span>
                <span class="ep-px-2 ep-py-1 ep-di-inline-block ep-text-small ep-cursor ep-rounded-1 ep-mr-1 ep-mb-1 ep-bg-primary ep-bg-opacity-10 ep-filters-days" id="ep_filter_next_week" data-key="next_week"><?php esc_html_e( 'Next Week', 'eventprime-event-calendar-management');?></span>
                <span class="ep-px-2 ep-py-1 ep-di-inline-block ep-text-small ep-cursor ep-rounded-1 ep-mr-1 ep-mb-1 ep-bg-primary ep-bg-opacity-10 ep-filters-days" id="ep_filter_next_month" data-key="next_month"><?php esc_html_e( 'Next Month', 'eventprime-event-calendar-management');?></span>
                <span class="ep-px-2 ep-py-1 ep-di-inline-block ep-text-small ep-cursor ep-rounded-1 ep-mr-1 ep-mb-1 ep-bg-primary ep-bg-opacity-10 ep-filters-days" id="ep_filter_online" data-key="online"><?php esc_html_e( 'Online', 'eventprime-event-calendar-management');?></span>
            </div>
        </div>
    <?php } ?>    
    <div class="ep-box-row ep-mb-3">
        <div class="ep-box-col-12 ep-fw-bold ep-text-small"><?php esc_html_e( 'Additional Filters', 'eventprime-event-calendar-management');?></div>
    </div>

    <?php if( isset( $args->date_range ) && $args->date_range == 1 ){ ?>
        <div class="ep-box-row ep-text-small ep-mb-2">
            <div class="ep-box-col-12 ep-text-small ep-mb-2"><span class="ep-text-small ep-fw-bold ep-text-uppercase"><?php esc_html_e('Date Range', 'eventprime-event-calendar-management');?></span></div>
            <div class="ep-box-col-4 ep-text-small">
                <div class="ep-input-group ep-mb-2">
                    <input type="text" id="filter-date-from" class="ep-form-control ep-form-control-sm" placeholder="<?php _e('From', 'eventprime-event-calendar-management');?>" readonly>
                    <button class="ep-btn ep-btn-light ep-border ep-btn-sm ep-trigger-date-to ep-lh-0 ep-trigger-date-from" type="button"><span class="material-icons-outlined ep-fs-6">calendar_month</span></button>
                </div>
            </div>
            <div class="ep-box-col-4 ep-text-small">
                <div class="ep-input-group ep-mb-2">
                    <input type="text" id="filter-date-to" class="ep-form-control ep-form-control-sm" placeholder="<?php _e('To', 'eventprime-event-calendar-management');?>" readonly>
                    <button class="ep-btn ep-btn-light ep-border ep-btn-sm ep-trigger-date-to ep-lh-0" type="button" ><span class="material-icons-outlined ep-fs-6">calendar_month</span></button>
                </div>
            </div>
            <div class="ep-box-col-4 ep-text-small" id="filter-date-days-section" style="display:none;">
                <select id="filter-date-days" class="ep-form-select ep-form-select-sm" aria-label=".form-select-sm example">
                    <option selected="" value="all"><?php _e('All Days', 'eventprime-event-calendar-management');?></option>
                    <option value="weekdays"><?php _e('Weekdays', 'eventprime-event-calendar-management');?></option>
                    <option vlaue="weekends"><?php _e('Weekends', 'eventprime-event-calendar-management');?></option>
                </select>
            </div>
        </div>
    <?php } ?>

    <div class="ep-box-row ep-text-small ep-mb-3" style="display:none;">
        <div class="ep-box-col-12 ep-text-small ep-mb-2 d-none"><span class="small fw-bold text-uppercase">Location</span></div>
        <div class="ep-box-col-4 ep-text-small">
            <div class="ep-input-group">
                <input type="text" class="ep-form-control ep-form-control-sm" placeholder="Your Zip/ Pin">
                    <button class="ep-btn ep-btn-primary ep-btn-sm" type="button"><span class="material-icons-outlined ep-fs-6">my_location</span></button>
            </div>
        </div>
        <div class="ep-box-col-4">
            <select class="ep-form-select ep-form-select-sm" aria-label=".form-select-sm example">
                <option selected="">Anywhere</option>
                <option>Online Only</option>
                <option value="1" disabled="">Within 50 miles</option>
                <option value="2" disabled="">Within 100 miles</option>
                <option value="3" disabled="">Within 250 miles</option>
                <option value="3" disabled="">Within 500 miles</option>
            </select>
        </div>
        <div class="ep-box-col-12 ep-mt-2 ep-text-small">
            <div class="ep-text-small ep-text-muted">Please enter your Zip/ Pin to enable distance related search options.</div>
        </div>
    </div>

    <div class="ep-box-row ep-text-small ep-gy-3" >
        

        <div class="ep-box-col-12 ep-rounded ep-p-3 ep-my-2" style="display: none;">
            <label for="customRange1" class="ep-form-label ep-text-small ep-fw-bold">Ticket Price Range</label>
            <input type="range" class="ep-form-range" id="customRange1">
                <div class="ep-d-flex ep-justify-content-between">
                    <div class="ep-input-group ep-input-group-sm ep-box-w-25">
                        <span class="ep-input-group-text">$</span>
                        <input type="text" class="ep-form-control ep-form-control-sm" value="20">
                    </div>                        
                    <div class="ep-input-group ep-input-group-sm ep-box-w-25">
                        <span class="ep-input-group-text">$</span>
                        <input type="text" class="ep-form-control ep-form-control-sm" value="500">
                    </div>
                </div>
        </div>

        <?php if( isset( $args->event_type ) && $args->event_type == 1 ){ ?>
            <?php if(!empty($args->event_types)):?>
            <div class="ep-box-col-6 ep-text-small">
                <div class="ep-input-group">
                    <select id="ep-filter-types" class="ep-form-control ep-form-control-sm" placeholder="<?php _e('Event Type', 'eventprime-event-calendar-management');?>">
                        <option selected="true" disabled="disabled"><?php _e('Event Type', 'eventprime-event-calendar-management');?></option>
                        <?php foreach($args->event_types as $type):?>
                        <option value="<?php esc_attr_e($type['id'])?>"><?php echo esc_attr_e($type['name']);?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>
            <?php endif;?>
        <?php } ?>                

        <?php if( isset( $args->venue ) && $args->venue == 1 ){ ?>
            <?php if(!empty($args->venues)):?>
            <div class="ep-box-col-6 ep-text-small">
                <div class="ep-input-group">
                    <select id="ep-filter-venues" class="ep-form-control ep-form-control-sm" placeholder="<?php _e('Venue/ Location', 'eventprime-event-calendar-management');?>">
                        <option selected="true" disabled="disabled"><?php _e('Venue / Location', 'eventprime-event-calendar-management');?></option>
                        <?php foreach($args->venues as $venue):?>
                        <option value="<?php esc_attr_e($venue['id'])?>"><?php echo esc_attr_e($venue['name']);?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>
            <?php endif;?>
        <?php } ?>                

        <?php if( isset( $args->performer ) && $args->performer == 1 ){ ?>
            <?php if(!empty($args->performers)):?>
            <div class="ep-box-col-6 ep-text-small">
                <div class="ep-input-group">
                    <select id="ep-filter-performer" name="filter_performer[]" class="ep-form-control ep-form-control-sm" placeholder="<?php _e('Performers', 'eventprime-event-calendar-management');?>" multiple="multiple">
                    <?php foreach($args->performers as $performer):?>
                        <option value="<?php esc_attr_e($performer['id'])?>"><?php echo esc_attr_e($performer['name']);?></option>
                    <?php endforeach;?>
                    </select>
                </div>
            </div>
            <?php endif;?>
        <?php } ?>

        <?php if( isset( $args->organizer ) && $args->organizer == 1 ){ ?>
            <?php if(!empty($args->organizers)):?>
            <div class="ep-box-col-6 ep-text-small">
                <div class="ep-input-group">
                    <select id="ep-filter-org" name="filter_organizer[]" class="ep-form-control ep-form-control-sm" placeholder="<?php _e('Organizers', 'eventprime-event-calendar-management');?>" multiple="multiple">
                    <?php foreach($args->organizers as $organizer):?>
                        <option value="<?php esc_attr_e($organizer['id'])?>"><?php echo esc_attr_e($organizer['name']);?></option>
                    <?php endforeach;?>
                    </select>
                </div>
            </div>
            <?php endif;?>
        <?php } ?>
    </div>
    
</div>
<script>
  jQuery(document).ready(function() {
    jQuery(".ep-input-group").on("click", function() {
        jQuery(".select2-dropdown--below").addClass("ep-filter-alignment")
        });
  });
</script>