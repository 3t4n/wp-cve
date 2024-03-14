<?php
/**
 * View: Events Search - filters
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/events/list/serch/filters.php
 *
 */
?>

<div class="ep-event-filter-bar ep-d-inline-flex">
    <!-- Event Type Filter -->
    <div class="ep-filter-bar-filter ep-mr-2">
        <div class="ep-filter-bar-toggle-wrapper">
            <button type="button" class="ep-filter-bar-toggle-filter ep-button-text-color ep-bg-white ep-border" id="ep-event-type-filter-bar">
                <?php esc_html_e( 'Event Types', 'eventprime-event-calendar-management');?>
            </button>
            <button type="button" class="ep-filter-bar-toggle-filter-remove-button ep-transparent-bg ep-rounded ep-hide"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0z" fill="none"/><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg></button>
        </div>
        <div class="ep-filter-bar-filter-container ep-box-dropdown" id="ep-event-type-filter-bar-container" style="display: none;">
            <label class="ep-filter-bar-filter-item-label">
                <?php esc_html_e( 'Event Types', 'eventprime-event-calendar-management');?>
            </label>
            <button type="button" class="ep-filter-bar-filter-close ep-transparent-bg ep-button-text"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0z" fill="none"/><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg></button>

            <div class="ep-filter-bar-filter-fields">
                <select name="event_type" class="ep-filter-bar-field-input ep-filter-dropdown-created">
                    <?php foreach( $args->event_types as $type ) {?>
                        <option value="<?php echo esc_attr( $type['id'] );?>">
                            <?php echo esc_html( $type['name'] );?>
                        </option><?php
                    }?>
                </select>
            </div>
        </div>
    </div>

    <!-- Performers Filter -->
    <div class="ep-filter-bar-filter ep-mr-2">
        <div class="ep-filter-bar-toggle-wrapper">
            <button type="button" class="ep-filter-bar-toggle-filter ep-button-text-color ep-bg-white ep-border" id="ep-performers-filter-bar">
                <?php esc_html_e( 'Performers', 'eventprime-event-calendar-management');?>
            </button>
            <button type="button" class="ep-filter-bar-toggle-filter-remove-button ep-transparent-bg ep-hide"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0z" fill="none"/><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg></button>
        </div>
        <div class="ep-filter-bar-filter-container ep-box-dropdown" id="ep-performers-filter-bar-container" style="display: none;">
            <label class="ep-filter-bar-filter-item-label">
                <?php esc_html_e( 'Performers', 'eventprime-event-calendar-management');?>
            </label>
            <button type="button" class="ep-filter-bar-filter-close ep-transparent-bg ep-button-text"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0z" fill="none"/><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg></button>

            <div class="ep-filter-bar-filter-fields">
                <select name="event_type" class="ep-filter-bar-field-input ep-filter-dropdown-created">
                    <?php foreach( $args->performers as $performer ) {?>
                        <option value="<?php echo esc_attr( $performer['id'] );?>">
                            <?php echo esc_html( $performer['name'] );?>
                        </option><?php
                    }?>
                </select>
            </div>
        </div>
    </div>

    <!-- Organizers Filter -->
    <div class="ep-filter-bar-filter ep-mr-2">
        <div class="ep-filter-bar-toggle-wrapper">
            <button type="button" class="ep-filter-bar-toggle-filter ep-button-text-color ep-bg-white ep-border" id="ep-organizers-filter-bar">
                <?php esc_html_e( 'Organizers', 'eventprime-event-calendar-management');?>
            </button>
            <button type="button" class="ep-filter-bar-toggle-filter-remove-button ep-transparent-bg ep-hide"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0z" fill="none"/><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg></button>
        </div>
        <div class="ep-filter-bar-filter-container ep-box-dropdown" id="ep-organizers-filter-bar-container" style="display: none;">
            <label class="ep-filter-bar-filter-item-label">
                <?php esc_html_e( 'Organizers', 'eventprime-event-calendar-management');?>
            </label>
            <button type="button" class="ep-filter-bar-filter-close ep-transparent-bg ep-button-text"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0z" fill="none"/><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg></button>

            <div class="ep-filter-bar-filter-fields">
                <select name="event_type" class="ep-filter-bar-field-input ep-filter-dropdown-created">
                    <?php foreach( $args->event_types as $type ) {?>
                        <option value="<?php echo esc_attr( $type['id'] );?>">
                            <?php echo esc_html( $type['name'] );?>
                        </option><?php
                    }?>
                </select>
            </div>
        </div>
    </div>

    <!-- Cost Filter -->
    <div class="ep-filter-bar-filter ep-mr-2">
        <div class="ep-filter-bar-toggle-wrapper">
            <button type="button" class="ep-filter-bar-toggle-filter ep-button-text-color ep-bg-white ep-border" id="ep-cost-filter-bar">
                <?php esc_html_e( 'Cost', 'eventprime-event-calendar-management');?>
            </button>
            <button type="button" class="ep-filter-bar-toggle-filter-remove-button ep-transparent-bg ep-hide"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0z" fill="none"/><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg></button>
        </div>
        <div class="ep-filter-bar-filter-container ep-box-dropdown" id="ep-cost-filter-bar-container" style="display: none;">
            <label class="ep-filter-bar-filter-item-label">
                <?php esc_html_e( 'Cost', 'eventprime-event-calendar-management');?>
            </label>
            <button type="button" class="ep-filter-bar-filter-close ep-transparent-bg ep-button-text"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0z" fill="none"/><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg></button>

            <div class="ep-filter-bar-filter-fields">
                <input type="text" id="ep-cost" name="cost" readonly style="border:0; color:#f6931f; font-weight:bold;">

                <div id="ep-cost-range"></div>
            </div>
        </div>
    </div>
</div>