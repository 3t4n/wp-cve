<?php global $bookingpress_common_date_format; ?>
<el-main class="bpa-main-listing-card-container bpa-default-card bpa-full-calendar-container bpa--is-page-non-scrollable-mob" id="all-page-main-container">
    <el-row type="flex" class="bpa-mlc-head-wrap">
        <el-col :xs="12" :sm="12" :md="12" :lg="12" :xl="12" class="bpa-mlc-left-heading">
            <h1 class="bpa-page-heading"><?php esc_html_e('Calendar', 'bookingpress-appointment-booking'); ?></h1>
        </el-col>
        <el-col :xs="12" :sm="12" :md="12" :lg="12" :xl="12" v-if="current_screen_size != 'mobile'">
            <div class="bpa-hw-right-btn-group">
                <el-button class="bpa-btn bpa-btn--primary" @click="openAppointmentBookingModal"> 
                    <span class="material-icons-round">add</span> 
                    <?php esc_html_e('Add Appointment', 'bookingpress-appointment-booking'); ?>
                </el-button>
            </div>
        </el-col>
    </el-row>
    <div class="bpa-back-loader-container" id="bpa-page-loading-loader">
        <div class="bpa-back-loader"></div>
    </div>
    <div id="bpa-main-container">
        <el-row>
            <el-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24">
                <div class="bpa-full-screen-calendar">
                    <div class="bpa-fsc--custom-filter-header" v-if="current_screen_size != 'mobile'">
                        <div class="bpa-cfh--wrapper">
                            <div class="bpa-cfh__left">
                                <el-button class="bpa-btn bpa-btn__medium" @click="$refs.bpavuecal.previous()">
                                    <span class="material-icons-round">arrow_back_ios</span>
                                </el-button>
                                <el-button class="bpa-btn bpa-btn__medium" @click="$refs.bpavuecal.next()">
                                    <span class="material-icons-round">arrow_forward_ios</span>
                                </el-button>
                            </div>
                            <div class="bpa-cfh__right">
                                <div class="bpa-cfh__btns">
                                    <div class="bpa-cfh__btns-wrapper">
                                    <el-button class="bpa-btn bpa-btn__medium" :class="activeView == 'month' ? 'bpa-btn--primary' : ''" @click="loadCalendar('month')"><?php esc_html_e('Month', 'bookingpress-appointment-booking'); ?></el-button>
                                    <el-button class="bpa-btn bpa-btn__medium" :class="activeView == 'week' ? 'bpa-btn--primary' : ''" @click="loadCalendar('week')"><?php esc_html_e('Week', 'bookingpress-appointment-booking'); ?></el-button>
                                    <el-button class="bpa-btn bpa-btn__medium" :class="activeView == 'day' ? 'bpa-btn--primary' : ''" @click="loadCalendar('day')"><?php esc_html_e('Day', 'bookingpress-appointment-booking'); ?></el-button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bpa-fsc--custom-mobile-filter" v-if="current_screen_size == 'mobile'">
                        <div class="bpa-cmf__left">
                            <el-date-picker class="bpa-form-control bpa-form-control--date-picker" ref="bookingpress_custom_filter_datepicker" type="date" format="<?php echo esc_html($bookingpress_common_date_format); ?>" v-model="custom_filter_date_val" name="appointment_booked_date" :editable="false" :clearable="false" 
                            @change="selected_calendar_appointment_booking_date($event)" value-format="yyyy-MM-dd"></el-date-picker>
                        </div>
                        <div class="bpa-cmf__right">
                            <el-button class="bpa-btn bpa-btn__small bpa-cmf__is-today-pill" :class="bpa_today_btn == 1 ? '__bpa-is-active' : ''" @click="today_date('<?php echo esc_html(date('Y-m-d', current_time('timestamp'))); ?>')">
                                <span class="material-icons-round">today</span> 
                                <?php esc_html_e('Today','bookingpress-appointment-booking'); ?> 
                            </el-button>
                            <el-button class="bpa-btn bpa-btn--icon-without-box" @click="bookingpress_cal_prev()">
                                <span class="material-icons-round">arrow_back_ios</span>
                            </el-button>
                            <el-button class="bpa-btn bpa-btn--icon-without-box" @click="bookingpress_cal_next()">
                                <span class="material-icons-round">arrow_forward_ios</span>
                            </el-button>
                        </div>
                    </div>
                    <el-row>
                        <el-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24">
                            <vue-cal :start-week-on-day="bookingpress_calendar_week_start" ref="bpavuecal" small :time-format="bookingpress_calendar_time_format" :selected-date="calendar_current_date" :time-from="00 * 60" :time-to="25 * 60" :disable-views="['years', 'year']" :events="calendar_events_data" :on-event-click="editEventCalendar" :showAllDayEvents="show_all_day_events"  events-on-month-view="true" hide-view-selector :active-view.sync="activeView"  :locale="site_locale" :time-cell-height="timeCellHeight" v-slot:no-event v-if="current_screen_size != 'mobile'" >
                                <template v-slot:title="{ title, view }">
                                    <span v-if="view.id === 'month'">{{moment.localeData(site_locale).months()[ view.startDate.getMonth()]}} {{ view.startDate.format('YYYY') }}</span>
                                    <span v-if="view.id === 'week'">{{moment.localeData(site_locale).months()[ view.startDate.getMonth()]}} {{ view.startDate.format('D') }} <?php esc_html_e(' - ', 'bookingpress-appointment-booking'); ?> {{ view.endDate.format('D') }}<?php esc_html_e(',', 'bookingpress-appointment-booking'); ?> {{ view.startDate.format('YYYY') }}</span>
                                    <span v-if="view.id === 'day'">{{ view.startDate.format('D') }} {{moment.localeData(site_locale).months()[ view.startDate.getMonth()]}} {{ view.startDate.format('YYYY') }}</span>
                                </template>
                                <template v-slot:arrow-prev>
                                    <span></span>
                                </template>
                                <template v-slot:arrow-next>
                                    <span></span>
                                </template>
                                <template #event="{ event, view }" >
                                    <div class="vuecal__event-title" v-html="event.title" ></div>
                                    <div class="vuecal__event-time"><span>{{ event.start.formatTime(bookingpress_calendar_time_format) }} - {{ event.end.formatTime(bookingpress_calendar_time_format) }}</span></div>
                                    <div class="vuecal__event-content" v-if="(view == 'week' || view == 'day') && event.totalEvents_onsameslot > 0"><span class="vuecal_more_event">+{{event.totalEvents_onsameslot}} <span class="vuecal_more_event_text"> More</span></span></div>
                                </template> 
        
                                <template #cell-content="{ cell, view, events, goNarrower }" v-if="activeView == 'month'">
                                    <div class="vuecal__cell-date" >{{ cell.content }}</div>
                                    <span class="vuecal__cell-events-count vuecal_more_event" @click="more_event_callback( cell, view, events, goNarrower )" v-if="events[1] && events[1].totalEvents_onsameslot > 0 && current_screen_size!='mobile'">+{{ events[1].totalEvents_onsameslot }} <span class="vuecal_more_event_text">More</span></span>
                                </template>  
                            </vue-cal>
                            <vue-cal :start-week-on-day="bookingpress_calendar_week_start" ref="bpavuecal" small :time-format="bookingpress_calendar_time_format" :selected-date="custom_filter_date_val" :time-from="00 * 60" :time-to="25 * 60" :disable-views="['years', 'year','week','month']" :events="calendar_events_data" :on-event-click="editEventCalendar" :showAllDayEvents="show_all_day_events" hide-title-bar events-on-month-view="true" hide-view-selector :locale="site_locale" :time-cell-height="timeCellHeight" v-slot:no-event v-else>
                            </vue-cal>

                        </el-col>
                    </el-row>
                </div>
            </el-col>
        </el-row>
        <el-button class="bpa-fsc__sticky-add-btn" @click="openAppointmentBookingModal" v-if="current_screen_size == 'mobile'">
            <span class="material-icons-round">add</span>
        </el-button>
    </div>
</el-main>
<el-dialog id="calendar_appointment_popover_dialog" :custom-class="bpa_calendar_dialog_custom_cls" top title="" :visible.sync="open_calendar_appointment_popover" close-on-press-escape="true" :modal="bpa_calendar_popover_mask" :show-close="false" :close-on-click-modal="bpa_calendar_popover_mask" @close="closeAppointmentBookingPopover" :fullscreen="current_screen_size == 'mobile'">  
   <div class="bpa-back-loader-container bpa-calendar-popup-loader" v-if="bpa_display_calendar_popover_loader == '1'">
		<div class="bpa-back-loader"></div>
	</div>
    <div class="bpa-dialog-body" v-else>
        <div class="bpa-fc-ipc__head-title">
            <svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <g clip-path="url(#bpa-fsc-item-popover-card)">
                    <path d="M13.3333 10.0007H10.8333C10.375 10.0007 10 10.3757 10 10.834V13.334C10 13.7923 10.375 14.1673 10.8333 14.1673H13.3333C13.7917 14.1673 14.1667 13.7923 14.1667 13.334V10.834C14.1667 10.3757 13.7917 10.0007 13.3333 10.0007ZM13.3333 1.66732V2.50065H6.66667V1.66732C6.66667 1.20898 6.29167 0.833984 5.83333 0.833984C5.375 0.833984 5 1.20898 5 1.66732V2.50065H4.16667C3.24167 2.50065 2.50833 3.25065 2.50833 4.16732L2.5 15.834C2.5 16.7507 3.24167 17.5007 4.16667 17.5007H15.8333C16.75 17.5007 17.5 16.7507 17.5 15.834V4.16732C17.5 3.25065 16.75 2.50065 15.8333 2.50065H15V1.66732C15 1.20898 14.625 0.833984 14.1667 0.833984C13.7083 0.833984 13.3333 1.20898 13.3333 1.66732ZM15 15.834H5C4.54167 15.834 4.16667 15.459 4.16667 15.0007V6.66732H15.8333V15.0007C15.8333 15.459 15.4583 15.834 15 15.834Z" />
                </g>
                <defs>
                    <clipPath id="bpa-fsc-item-popover-card">
                        <rect width="20" height="20" fill="white"/>
                    </clipPath>
                </defs>
            </svg>
            <h3 v-if="activeView === 'month' && current_screen_size != 'mobile'">{{appointment_formdata.bookingpress_appointemnt_popover_title}}</h3>
            <h3 v-if="(activeView === 'week' || activeView === 'day') && current_screen_size != 'mobile'">{{appointment_formdata.bookingpress_appointemnt_popover_timeslot_title}}</h3>
            <h3 v-if="current_screen_size == 'mobile'">{{appointment_formdata.bookingpress_appointemnt_popover_title}} {{appointment_formdata.bookingpress_appointemnt_popover_timeslot_title}}</h3>
            <span class="material-icons-round bpa-ht__close-icon" v-if="bpa_display_calendar_popover_loader != '1' && current_screen_size == 'mobile'" @click="closeAppointmentBookingModal">close</span>	        
        </div>
        <el-collapse class="bpa-fc-item-accordion" accordion="false" :value="appointment_formdata.first_expanded_collapse">
           <el-collapse-item :class="(appointment_formdata.bookingpress_total_popover_appointemnt == 1) ? 'bpa-fc-item-accordion-expanded' : ''" v-for="item in appointment_formdata.bookingpress_appointment_popover_data" :name="item.bookingpress_appointment_booking_id" :key="item.bookingpress_appointment_booking_id">
                <template slot="title">
                    <div class="bpa-fc-item-head">
                        <div class="bpa-fc-ih__service-title">
                            <h4>{{item.bookingpress_service_name}}</h4>
                        </div>
                    </div>
                    <div class="bpa-fc-item-info-row">
                        <p><?php esc_html_e('Id- #', 'bookingpress-appointment-booking'); ?>{{item.bookingpress_appointment_booking_id}}</p>
                        <p>{{item.bookingpress_appointment_time}} - {{item.bookingpress_appointment_end_time}}</p>                        
                    </div>  
                </template>
                <div class="bpa-fc-item__expand-card">
                    <div class="bpa-iec__body">
                        <div class="bpa-iec-body__customer-detail">
                            <h5>{{item.bookingpress_user_displayname}}</h5>
                            <p v-if="item.bookingpress_user_email != ''"><span class="material-icons-round">email_black</span>{{item.bookingpress_user_email}}</p>
                            <p v-if="item.bookingpress_user_phone != ''"><span class="material-icons-round">call_black</span>{{item.bookingpress_user_phone}}</p>
                        </div>
                        <div class="bpa-iec__action-btns">
                            <el-button class="bpa-btn bpa-btn__small bpa-btn--full-width bpa-btn__filled-light bpa-btn--primary" v-if="item.bookingpress_appointment_status == '2'" :disabled="bpa_is_disable_approve_btn" @click="bookingpress_calendar_approve_appointment(item.bookingpress_appointment_booking_id,1)" :class="(is_calendar_popover_approve_loader == '1') ? 'bpa-btn--is-loader' : ''" > 
                                <span class="bpa-btn__label">
                                    <span class="material-icons-round">check_circle</span>
                                    <?php esc_html_e('Approve', 'bookingpress-appointment-booking'); ?>
                                </span>
                                <div class="bpa-btn--loader__circles">                    
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                </div>
                            </el-button>
                            </el-button>
                            <el-button v-if="item.bookingpress_app_is_past==false" @click.native.prevent="editAppointmentData(item.bookingpress_appointment_booking_id,'', 'calendar_popover')" class="bpa-btn bpa-btn__small bpa-btn--full-width bpa-btn__filled-light"> 
                                <span class="material-icons-round">edit</span> 
                                <?php esc_html_e('Edit', 'bookingpress-appointment-booking'); ?>
                            </el-button>
                        </div>
                    </div>
                </div>
            </el-collapse-item>
        </el-collapse>
    </div>
</el-dialog>

<!-- Appointment Add Modal -->
<el-dialog id="calendar_appointment_modal" custom-class="bpa-dialog bpa-dialog--fullscreen bpa--is-page-non-scrollable-mob" title="" :visible.sync="open_calendar_appointment_modal" top="32px" fullscreen="true" :close-on-press-escape="close_modal_on_esc">
    <div class="bpa-dialog-heading">
        <el-row type="flex">
            <el-col :xs="12" :sm="12" :md="16" :lg="16" :xl="16">
                <h1 class="bpa-page-heading" v-if="appointment_formdata.appointment_update_id == '0'"><?php esc_html_e('Add Appointment', 'bookingpress-appointment-booking'); ?></h1>
                <h1 class="bpa-page-heading" v-else><?php esc_html_e('Edit Appointment', 'bookingpress-appointment-booking'); ?></h1>
            </el-col>
            <el-col :xs="12" :sm="12" :md="7" :lg="7" :xl="7" class="bpa-dh__btn-group-col">
                <el-button class="bpa-btn bpa-btn--primary" :class="(is_display_save_loader == '1') ? 'bpa-btn--is-loader' : ''" @click="saveAppointmentBooking('appointment_formdata')"  :disabled="is_disabled">                    
                  <span class="bpa-btn__label"><?php esc_html_e('Save', 'bookingpress-appointment-booking'); ?></span>
                  <div class="bpa-btn--loader__circles">                    
                      <div></div>
                      <div></div>
                      <div></div>
                  </div>
                </el-button>
                <el-button class="bpa-btn" @click="closeAppointmentBookingModal"><?php esc_html_e('Cancel', 'bookingpress-appointment-booking'); ?></el-button>
            </el-col>
        </el-row>
    </div>
    <div class="bpa-dialog-body">
        <div class="bpa-form-row">
            <el-row>
                <el-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24">
                    <div class="bpa-default-card bpa-db-card">
                        <el-form ref="appointment_formdata" :rules="rules" :model="appointment_formdata" label-position="top" @submit.native.prevent>
                            <div class="bpa-form-body-row">
                                <el-row :gutter="24">
                                    <el-col :xs="24" :sm="24" :md="8" :lg="8" :xl="8">
                                        <el-form-item prop="appointment_selected_customer">
                                            <template #label>
                                                <span class="bpa-form-label"><?php esc_html_e('Select Customer', 'bookingpress-appointment-booking'); ?></span>
                                            </template>                        
                                            <el-select class="bpa-form-control" name="appointment_selected_customer" v-model="appointment_formdata.appointment_selected_customer" filterable placeholder="<?php esc_html_e( 'Start typing to fetch customer', 'bookingpress-appointment-booking' ); ?>" remote reserve-keyword :remote-method="bookingpress_get_customer_list" :loading="bookingpress_loading"  popper-class="bpa-el-select--is-with-modal" v-cancel-read-only>
                                                <el-option v-for="item in appointment_customers_list" :key="item.value" :label="item.text" :value="item.value">
                                                    <span>{{ item.text }}</span>
                                                </el-option>
                                            </el-select>                               
                                        </el-form-item>
                                    </el-col>
                                    <el-col :xs="24" :sm="24" :md="8" :lg="8" :xl="8">
                                        <el-form-item prop="appointment_selected_service">
                                            <template #label>
                                                <span class="bpa-form-label"><?php esc_html_e('Select Service', 'bookingpress-appointment-booking'); ?></span>
                                            </template>
                                            <el-select class="bpa-form-control" @Change="bookingpress_appointment_change_service()" v-model="appointment_formdata.appointment_selected_service" name="appointment_selected_service" filterable 
                                            placeholder="<?php esc_html_e('Select service', 'bookingpress-appointment-booking'); ?>"
                                            popper-class="bpa-el-select--is-with-modal">
                                                <el-option-group v-for="service_cat_data in appointment_services_list" :key="service_cat_data.category_name" :label="service_cat_data.category_name">
                                                    <template v-if="service_data.service_id == 0" v-for="service_data in service_cat_data.category_services">
                                                        <el-option :key="service_data.service_id" :label="service_data.service_name" :value="''" ></el-option>
                                                    </template>
                                                    <template v-else>
                                                        <el-option :key="service_data.service_id" :label="service_data.service_name+' ('+service_data.service_price+' )'" :value="service_data.service_id"></el-option>
                                                    </template>
                                                </el-option-group>
                                            </el-select>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :xs="24" :sm="24" :md="8" :lg="8" :xl="8">
                                        <el-form-item prop="appointment_booked_date">
                                            <template #label>
                                                <span class="bpa-form-label"><?php esc_html_e('Appointment Date', 'bookingpress-appointment-booking'); ?></span>
                                            </template>
                                            <el-date-picker class="bpa-form-control bpa-form-control--date-picker" type="date" format="<?php echo esc_html($bookingpress_common_date_format); ?>" v-model="appointment_formdata.appointment_booked_date" name="appointment_booked_date" :clearable="false" :picker-options="pickerOptions" popper-class="bpa-el-select--is-with-modal bpa-el-datepicker-widget-wrapper" @change="select_appointment_booking_date($event)" value-format="yyyy-MM-dd"></el-date-picker>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :xs="24" :sm="24" :md="8" :lg="12" :xl="12">
                                        <el-form-item prop="appointment_booked_time">
                                            <template #label>
                                                <span class="bpa-form-label"><?php esc_html_e('Appointment Time', 'bookingpress-appointment-booking'); ?></span>
                                            </template>
                                            <el-select class="bpa-form-control" Placeholder="<?php esc_html_e( 'Select Time', 'bookingpress-appointment-booking' ); ?>" v-model="appointment_formdata.appointment_booked_time" filterable popper-class="bpa-el-select--is-with-modal" @Change="bookingpress_set_time($event,appointment_time_slot)">
                                                <el-option-group v-for="appointment_time_slot_data in appointment_time_slot" :key="appointment_time_slot_data.timeslot_label" :label="appointment_time_slot_data.timeslot_label">
                                                    <el-option v-for="appointment_time in appointment_time_slot_data.timeslots" :label="(appointment_time.formatted_start_time)+' to '+(appointment_time.formatted_end_time)" :value="appointment_time.store_start_time" :disabled="( appointment_time.is_disabled || appointment_time.max_capacity <= appointment_time.total_booked || appointment_time.max_capacity == 0 || appointment_time.is_booked == 1 )">
                                                    <span>{{ appointment_time.formatted_start_time  }} to {{appointment_time.formatted_end_time}}</span>
                                                    </el-option>	
                                                </el-option-group>
                                            </el-select>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :xs="24" :sm="24" :md="8" :lg="12" :xl="12">
                                        <el-form-item>
                                            <template #label>
                                                <span class="bpa-form-label"><?php esc_html_e('Select Status', 'bookingpress-appointment-booking'); ?></span>
                                            </template>
                                            <el-select class="bpa-form-control" v-model="appointment_formdata.appointment_status" popper-class="bpa-el-select--is-with-modal">
                                                <el-option v-for="status_data in appointment_status" :key="status_data.value" :label="status_data.text" :value="status_data.value">
                                                    <span>{{ status_data.text }}</span>
                                                </el-option>
                                            </el-select>
                                        </el-form-item>
                                    </el-col>
                                </el-row>
                            </div>
                            <div class="bpa-form-body-row">
                                <el-row :gutter="24">
                                    <el-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24">
                                        <el-form-item>
                                            <template #label>
                                                <span class="bpa-form-label"><?php esc_html_e('Internal note', 'bookingpress-appointment-booking'); ?></span>
                                            </template>
                                            <el-input class="bpa-form-control" type="textarea" :rows="5" v-model="appointment_formdata.appointment_internal_note"></el-input>
                                        </el-form-item>
                                    </el-col>
                                </el-row>
                            </div>
                            <div class="bpa-form-body-row">
                                <el-row :gutter="24">
                                    <el-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24">
                                        <el-form-item>
                                            <label class="bpa-form-label bpa-custom-checkbox--is-label"> <el-checkbox v-model="appointment_formdata.appointment_send_notification"></el-checkbox> <?php esc_html_e('Do Not Send Notification', 'bookingpress-appointment-booking'); ?></label>
                                        </el-form-item>
                                    </el-col>
                                </el-row>
                            </div>
                        </el-form>
                    </div>
                </el-col>
            </el-row>
        </div>
    </div>
</el-dialog>
