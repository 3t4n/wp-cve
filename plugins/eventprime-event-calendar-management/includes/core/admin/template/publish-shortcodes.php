<?php defined( 'ABSPATH' ) || exit;

$global_settings = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
$options = $global_settings->ep_get_settings();
$ext_list = ep_list_all_exts();?>
<div class="emagic ep-frontend ep-shortcode-page">
    <div class="ep-sc-wrap ep-box-wrap">
        <div class="ep-sc-blocks ep-box-row">
            <div class="ep-sc-block-row ep-box-col-12 ep-scpagetitle ep-py-4 ep-my-4 ep-text-center"> <b><?php esc_html_e('EventPrime','eventprime-event-calendar-management'); ?></b> <span class=""><?php esc_html_e('Shortcodes','eventprime-event-calendar-management'); ?></span> </div>
        </div>
        <div class="ep-escblock-wrap ep-box-row">
            <!-- All Events -->
            <div class="ep-sc-block ep-box-col-4">
                <div class="ep-escsubblock ep-bg-white ep-p-3 ep-position-relative ep-mb-4 ep-rounded-1">
                    <div class="ep-scblock ep-sctitle ep-fw-bold ep-text-center ep-pb-2"><?php esc_html_e('All Events', 'eventprime-event-calendar-management'); ?>
                        <div style="display: none" class="ep-shorcode-copied"><?php esc_html_e('Shortcode Copied', 'eventprime-event-calendar-management'); ?></div>
                        <div class="ep-scblock-menu ep-sc-menu ep-position-absolute" style="right: 5px;top: 14px;" onclick="ep_shortcode_dropdown_menu(this)">
                            <span class="material-icons ep-cursor">more_vert</span>
                            <ul class="ep-sc-dropdown" style="display: none;">
                                <li class="ep-sc-dropdown-item"><a href="javascript:void(0)" onclick="ep_copy_shortcode(document.getElementById('ep-all-event-page'))"><?php esc_html_e('Copy Shortcode', 'eventprime-event-calendar-management'); ?></a></li> 
                                <li class="ep-sc-dropdown-item"><a href="<?php echo admin_url('post.php?post='.$options->events_page.'&action=edit'); ?>"><?php esc_html_e('New Page with Shortcode', 'eventprime-event-calendar-management'); ?></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="ep-scblock ep-sc-format-row ep-d-flex ep-justify-content-between ep-py-2">
                        <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Format', 'eventprime-event-calendar-management'); ?></div> 
                        <div class="ep-sc-dec ep-box-w-65" id="ep-all-event-page">[em_events]</div>
                    </div>
                    <div class="ep-scblock ep-sc-requirements-row ep-d-flex ep-justify-content-between ep-py-2">
                        <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Requirements', 'eventprime-event-calendar-management'); ?></div> 
                        <div class="ep-sc-dec ep-box-w-65">Core</div>
                    </div>
                    <div class="ep-scblock-hide" style="display: none;">
                        <div class="ep-scblock ep-sc-example-row ep-d-flex ep-justify-content-between ep-py-2">
                            <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Example', 'eventprime-event-calendar-management'); ?></div> 
                            <div class="ep-sc-dec ep-box-w-65">[em_events view="square_grid/staggered_grid/rows/slider /month/week/listweek/day" id="{EVENT_ID}" types="1,2,&#133;" sites="1,2,&#133;" show="5" upcoming="0 or 1" disable_filter="0 or 1" filter_elements="quick_search,date_range,event_type,venue,performer,organizer" individual_events="yesterday or today or tomorrow or this month" order="asc/desc" ]</div>
                        </div>
                        <div class="ep-scblock ep-sc-parameters-row ep-d-flex ep-justify-content-between ep-py-2">
                            <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Parameters', 'eventprime-event-calendar-management'); ?></div> 
                            <div class="ep-sc-dec ep-box-w-65"><ul><li>None</li></ul></div>
                        </div>
                        <div class="ep-scblock ep-sc-description-row ep-d-flex ep-justify-content-between ep-py-2">
                            <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Description', 'eventprime-event-calendar-management'); ?></div> 
                            <div class="ep-sc-dec ep-morelink ep-box-w-65">Shows all Events on the frontend. Use 'view', 'types', 'sites', 'show' and 'upcoming' attributes to set their default values. 'types' should be Event Type IDs (comma separated). 'sites' should be Event Site IDs (comma separated). 'show' attribute will be use to list no. of events with square_grid, staggered_grid, rows view. 'upcoming' for show/hide upcoming events. 'disable_filter' for hide/show event filter. 'filter_elements' to display only given filters. 'individual_events' shows events according to the given value. 'order' sorts the events in ascending and descending order<br> <b>NOTE: If you use id and view attributes together then it will show event in view form, otherwise without view attribute it will show event details page. </b></div>
                        </div>
                    </div> 
                    <div class="ep-scblock ep-shorocode-show-more ep-d-flex ep-align-items-center ep-content-center ep-cursor">
                        <?php esc_html_e('More info', 'eventprime-event-calendar-management'); ?>
                        <span class="material-icons">keyboard_arrow_down</span>
                    </div>
                    <div class="ep-scblock ep-shorocode-show-less ep-d-flex ep-align-items-center ep-content-center ep-cursor" style="display: none;">
                        <?php esc_html_e('Less info', 'eventprime-event-calendar-management'); ?>
                        <span class="material-icons">keyboard_arrow_up</span>
                    </div>
                </div>
            </div>
            <!-- All Event Types -->
            <div class="ep-sc-block ep-box-col-4">
                <div class="ep-escsubblock ep-bg-white ep-p-3 ep-position-relative ep-mb-4 ep-rounded-1">
                    <div class="ep-scblock ep-sctitle ep-fw-bold ep-text-center ep-pb-2"><?php esc_html_e('All Event Types', 'eventprime-event-calendar-management') ?>
                        <div style="display: none" class="ep-shorcode-copied"><?php esc_html_e('Shortcode Copied', 'eventprime-event-calendar-management'); ?></div>
                        <div class="ep-scblock-menu ep-sc-menu ep-position-absolute " style="right: 5px;top: 14px;" onclick="ep_shortcode_dropdown_menu(this)">
                            <span class="material-icons ep-cursor">more_vert</span>
                            <ul class="ep-sc-dropdown" style="display: none;">
                                <li class="ep-sc-dropdown-item"><a href="javascript:void(0)" onclick="ep_copy_shortcode(document.getElementById('ep-all-event-types-page'))"><?php esc_html_e('Copy Shortcode', 'eventprime-event-calendar-management'); ?></a></li> 
                                <li class="ep-sc-dropdown-item"><a href="<?php echo admin_url('post.php?post='.$options->event_types.'&action=edit'); ?>">New Page with Shortcode</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="ep-scblock ep-sc-format-row ep-d-flex ep-justify-content-between ep-py-2">
                        <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Format', 'eventprime-event-calendar-management'); ?></div> 
                        <div class="ep-sc-dec ep-box-w-65" id="ep-all-event-types-page">[em_event_types]</div>
                    </div>
                    <div class="ep-scblock ep-sc-requirements-row ep-d-flex ep-justify-content-between ep-py-2">
                        <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Requirements', 'eventprime-event-calendar-management'); ?></div> 
                        <div class="ep-sc-dec ep-box-w-65">Core</div>
                    </div>
                    <div class="ep-scblock-hide" style="display: none;">
                        <div class="ep-scblock ep-sc-example-row ep-d-flex ep-justify-content-between ep-py-2">
                            <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Example', 'eventprime-event-calendar-management'); ?></div> 
                            <div class="ep-sc-dec ep-box-w-65">[em_event_types display_style="grid/colored_grid/rows" limit="{NUMBER}" cols="{NUMBER}" load_more="0 or 1" search="0 or 1" featured="0 or 1" popular="0 or 1"]</div>
                        </div>
                        <div class="ep-scblock ep-sc-parameters-row ep-d-flex ep-justify-content-between ep-py-2">
                            <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Parameters', 'eventprime-event-calendar-management'); ?></div> 
                            <div class="ep-sc-dec ep-box-w-65"><ul><li>None</li></ul></div>
                        </div>
                        <div class="ep-scblock ep-sc-description-row ep-d-flex ep-justify-content-between ep-py-2">
                            <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Description', 'eventprime-event-calendar-management'); ?></div> 
                            <div class="ep-sc-dec ep-morelink ep-box-w-65">Displays the all Event Types panel on the frontend where users can view the categories and the number of events that belong to them. Use 'display_style', 'limit', 'cols', 'load_more', 'search', 'featured' and 'popular' attributes to set their default values. 'limit' will be use to list no. of event types. 'cols' will be use to list no. of event types in one column. 'load_more' for show/hide load more event types. 'search' for hide/show search event types. 'featured' for hide/show featured event types. 'popular' for hide/show popular event types.</b></div>
                        </div>
                    </div> 
                    <div class="ep-scblock ep-shorocode-show-more ep-d-flex ep-align-items-center ep-content-center ep-cursor">
                        <?php esc_html_e('More info', 'eventprime-event-calendar-management'); ?>
                        <span class="material-icons">keyboard_arrow_down</span>
                    </div>
                    <div class="ep-scblock ep-shorocode-show-less ep-d-flex ep-align-items-center ep-content-center ep-cursor" style="display: none;">
                        <?php esc_html_e('Less info', 'eventprime-event-calendar-management'); ?>
                        <span class="material-icons">keyboard_arrow_up</span>
                    </div>
                </div>
            </div>
            <!-- All Sites/Locations -->
            <div class="ep-sc-block ep-box-col-4">
                <div class="ep-escsubblock ep-bg-white ep-p-3 ep-position-relative ep-mb-4 ep-rounded-1">
                    <div class="ep-scblock ep-sctitle ep-fw-bold ep-text-center ep-pb-2"><?php esc_html_e('All Sites/Locations', 'eventprime-event-calendar-management') ?>
                        <div style="display: none" class="ep-shorcode-copied"><?php esc_html_e('Shortcode Copied', 'eventprime-event-calendar-management'); ?></div>
                        <div class="ep-scblock-menu ep-sc-menu ep-position-absolute " style="right: 5px;top: 14px;" onclick="ep_shortcode_dropdown_menu(this)">
                            <span class="material-icons ep-cursor">more_vert</span>
                            <ul class="ep-sc-dropdown" style="display: none;">
                                <li class="ep-sc-dropdown-item"><a href="javascript:void(0)" onclick="ep_copy_shortcode(document.getElementById('ep-all-event-sites-page'))"><?php esc_html_e('Copy Shortcode', 'eventprime-event-calendar-management'); ?></a></li> 
                                <li class="ep-sc-dropdown-item"><a href="<?php echo admin_url('post.php?post='.$options->venues_page.'&action=edit'); ?>"><?php esc_html_e('New Page with Shortcode', 'eventprime-event-calendar-management'); ?></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="ep-scblock ep-sc-format-row ep-d-flex ep-justify-content-between ep-py-2">
                        <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Format', 'eventprime-event-calendar-management'); ?></div> 
                        <div class="ep-sc-dec ep-box-w-65" id="ep-all-event-sites-page">[em_sites]</div>
                    </div>
                    <div class="ep-scblock ep-sc-requirements-row ep-d-flex ep-justify-content-between ep-py-2">
                        <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Requirements', 'eventprime-event-calendar-management'); ?></div> 
                        <div class="ep-sc-dec ep-box-w-65">Core</div>
                    </div>
                    <div class="ep-scblock-hide" style="display: none;">
                        <div class="ep-scblock ep-sc-example-row ep-d-flex ep-justify-content-between ep-py-2">
                            <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Example', 'eventprime-event-calendar-management'); ?></div> 
                            <div class="ep-sc-dec ep-box-w-65">[em_sites display_style="grid/colored_grid/rows" limit="{NUMBER}" cols="{NUMBER}" load_more="0 or 1" search="0 or 1" featured="0 or 1" popular="0 or 1"]</div>
                        </div>
                        <div class="ep-scblock ep-sc-parameters-row ep-d-flex ep-justify-content-between ep-py-2">
                            <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Parameters', 'eventprime-event-calendar-management'); ?></div> 
                            <div class="ep-sc-dec ep-box-w-65"><ul><li>None</li></ul></div>
                        </div>
                        <div class="ep-scblock ep-sc-description-row ep-d-flex ep-justify-content-between ep-py-2">
                            <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Description', 'eventprime-event-calendar-management'); ?></div> 
                            <div class="ep-sc-dec ep-morelink ep-box-w-65">Displays the all Event Sites panel on the frontend where users can view the list of Event Sites at which events can take place. Use 'display_style', 'limit', 'cols', 'load_more', 'search', 'featured' and 'popular' attributes to set their default values. 'limit' will be use to list no. of sites/locations. 'cols' will be use to list no. of sites/locations in one column. 'load_more' for show/hide load more sites/locations. 'search' for hide/show search sites/locations. 'featured' for hide/show featured sites/locations. 'popular' for hide/show popular sites/locations.</b></div>
                        </div>
                    </div> 
                    <div class="ep-scblock ep-shorocode-show-more ep-d-flex ep-align-items-center ep-content-center ep-cursor">
                        <?php esc_html_e('More info', 'eventprime-event-calendar-management'); ?>
                        <span class="material-icons">keyboard_arrow_down</span>
                    </div>
                    <div class="ep-scblock ep-shorocode-show-less ep-d-flex ep-align-items-center ep-content-center ep-cursor" style="display: none;">
                        <?php esc_html_e('Less info', 'eventprime-event-calendar-management'); ?>
                        <span class="material-icons">keyboard_arrow_up</span>
                    </div>
                </div>
            </div>
            <!-- Event Organizers -->
            <div class="ep-sc-block ep-box-col-4">
                <div class="ep-escsubblock ep-bg-white ep-p-3 ep-position-relative ep-mb-4 ep-rounded-1">
                    <div class="ep-scblock ep-sctitle ep-fw-bold ep-text-center ep-pb-2"><?php esc_html_e('Event Organizers', 'eventprime-event-calendar-management') ?>
                        <div style="display: none" class="ep-shorcode-copied"><?php esc_html_e('Shortcode Copied', 'eventprime-event-calendar-management'); ?></div>
                        <div class="ep-scblock-menu ep-sc-menu ep-position-absolute " style="right: 5px;top: 14px;" onclick="ep_shortcode_dropdown_menu(this)">
                            <span class="material-icons ep-cursor">more_vert</span>
                            <ul class="ep-sc-dropdown" style="display: none;">
                                <li class="ep-sc-dropdown-item"><a href="javascript:void(0)" onclick="ep_copy_shortcode(document.getElementById('ep-all-event-organizers-page'))"><?php esc_html_e('Copy Shortcode', 'eventprime-event-calendar-management'); ?></a></li> 
                                <li class="ep-sc-dropdown-item"><a href="<?php echo admin_url('post.php?post='.$options->event_organizers.'&action=edit'); ?>"><?php esc_html_e('New Page with Shortcode', 'eventprime-event-calendar-management'); ?></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="ep-scblock ep-sc-format-row ep-d-flex ep-justify-content-between ep-py-2">
                        <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Format', 'eventprime-event-calendar-management'); ?></div> 
                        <div class="ep-sc-dec ep-box-w-65" id="ep-all-event-organizers-page">[em_event_organizers]</div>
                    </div>
                    <div class="ep-scblock ep-sc-requirements-row ep-d-flex ep-justify-content-between ep-py-2">
                        <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Requirements', 'eventprime-event-calendar-management'); ?></div> 
                        <div class="ep-sc-dec ep-box-w-65">Core</div>
                    </div>
                    <div class="ep-scblock-hide" style="display: none;">
                        <div class="ep-scblock ep-sc-example-row ep-d-flex ep-justify-content-between ep-py-2">
                            <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Example', 'eventprime-event-calendar-management'); ?></div> 
                            <div class="ep-sc-dec ep-box-w-65">[em_event_organizers display_style="grid/colored_grid/rows" limit="{NUMBER}" cols="{NUMBER}" load_more="0 or 1" search="0 or 1" featured="0 or 1" popular="0 or 1"]</div>
                        </div>
                        <div class="ep-scblock ep-sc-parameters-row ep-d-flex ep-justify-content-between ep-py-2">
                            <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Parameters', 'eventprime-event-calendar-management'); ?></div> 
                            <div class="ep-sc-dec ep-box-w-65"><ul><li>None</li></ul></div>
                        </div>
                        <div class="ep-scblock ep-sc-description-row ep-d-flex ep-justify-content-between ep-py-2">
                            <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Description', 'eventprime-event-calendar-management'); ?></div> 
                            <div class="ep-sc-dec ep-morelink ep-box-w-65">Displays the all Event Organizers panel on the frontend where users can view the categories and the number of events that belong to them. Use 'display_style', 'limit', 'cols', 'load_more', 'search', 'featured' and 'popular' attributes to set their default values. 'limit' will be use to list no. of event organizers. 'cols' will be use to list no. of event organizers in one column. 'load_more' for show/hide load more event organizers. 'search' for hide/show search event organizers. 'featured' for hide/show featured event organizers. 'popular' for hide/show popular event organizers.</b></div>
                        </div>
                    </div> 
                    <div class="ep-scblock ep-shorocode-show-more ep-d-flex ep-align-items-center ep-content-center ep-cursor">
                        <?php esc_html_e('More info', 'eventprime-event-calendar-management'); ?>
                        <span class="material-icons">keyboard_arrow_down</span>
                    </div>
                    <div class="ep-scblock ep-shorocode-show-less ep-d-flex ep-align-items-center ep-content-center ep-cursor" style="display: none;">
                        <?php esc_html_e('Less info', 'eventprime-event-calendar-management'); ?>
                        <span class="material-icons">keyboard_arrow_up</span>
                    </div>
                </div>
            </div>
            <!-- All Performers -->
            <div class="ep-sc-block ep-box-col-4">
                <div class="ep-escsubblock ep-bg-white ep-p-3 ep-position-relative ep-mb-4 ep-rounded-1">
                    <div class="ep-scblock ep-sctitle ep-fw-bold ep-text-center ep-pb-2"><?php esc_html_e('All Performers', 'eventprime-event-calendar-management') ?>
                        <div style="display: none" class="ep-shorcode-copied"><?php esc_html_e('Shortcode Copied', 'eventprime-event-calendar-management'); ?></div>
                        <div class="ep-scblock-menu ep-sc-menu ep-position-absolute " style="right: 5px;top: 14px;" onclick="ep_shortcode_dropdown_menu(this)">
                            <span class="material-icons ep-cursor">more_vert</span>
                            <ul class="ep-sc-dropdown" style="display: none;">
                                <li class="ep-sc-dropdown-item"><a href="javascript:void(0)" onclick="ep_copy_shortcode(document.getElementById('ep-all-event-performers-page'))"><?php esc_html_e('Copy Shortcode', 'eventprime-event-calendar-management'); ?></a></li> 
                                <li class="ep-sc-dropdown-item"><a href="<?php echo admin_url('post.php?post='.$options->performers_page.'&action=edit'); ?>"><?php esc_html_e('New Page with Shortcode', 'eventprime-event-calendar-management'); ?></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="ep-scblock ep-sc-format-row ep-d-flex ep-justify-content-between ep-py-2">
                        <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Format', 'eventprime-event-calendar-management'); ?></div> 
                        <div class="ep-sc-dec ep-box-w-65" id="ep-all-event-performers-page">[em_performers]</div>
                    </div>
                    <div class="ep-scblock ep-sc-requirements-row ep-d-flex ep-justify-content-between ep-py-2">
                        <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Requirements', 'eventprime-event-calendar-management'); ?></div> 
                        <div class="ep-sc-dec ep-box-w-65">Core</div>
                    </div>
                    <div class="ep-scblock-hide" style="display: none;">
                        <div class="ep-scblock ep-sc-example-row ep-d-flex ep-justify-content-between ep-py-2">
                            <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Example', 'eventprime-event-calendar-management'); ?></div> 
                            <div class="ep-sc-dec ep-box-w-65">[em_performers orderby="title/date/rand" display_style="grid/colored_grid/rows" limit="{NUMBER}" cols="{NUMBER}" load_more="0 or 1" search="0 or 1" featured="0 or 1" popular="0 or 1"]</div>
                        </div>
                        <div class="ep-scblock ep-sc-parameters-row ep-d-flex ep-justify-content-between ep-py-2">
                            <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Parameters', 'eventprime-event-calendar-management'); ?></div> 
                            <div class="ep-sc-dec ep-box-w-65"><ul><li>None</li></ul></div>
                        </div>
                        <div class="ep-scblock ep-sc-description-row ep-d-flex ep-justify-content-between ep-py-2">
                            <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Description', 'eventprime-event-calendar-management'); ?></div> 
                            <div class="ep-sc-dec ep-morelink ep-box-w-65">Displays the all Performers panel on the frontend where users can view the list of Performers that can perform in events. Use 'orderby', 'display_style', 'limit', 'cols', 'load_more', 'search', 'featured' and 'popular' attributes to set their default values. 'limit' will be use to list no. of performers. 'cols' will be use to list no. of performers in one column. 'load_more' for show/hide load more performers. 'search' for hide/show search performers. 'featured' for hide/show featured performers. 'popular' for hide/show popular performers. </b></div>
                        </div>
                    </div> 
                    <div class="ep-scblock ep-shorocode-show-more ep-d-flex ep-align-items-center ep-content-center ep-cursor">
                        <?php esc_html_e('More info', 'eventprime-event-calendar-management'); ?>
                        <span class="material-icons">keyboard_arrow_down</span>
                    </div>
                    <div class="ep-scblock ep-shorocode-show-less ep-d-flex ep-align-items-center ep-content-center ep-cursor" style="display: none;">
                        <?php esc_html_e('Less info', 'eventprime-event-calendar-management'); ?>
                        <span class="material-icons">keyboard_arrow_up</span>
                    </div>
                </div>
            </div>
            <?php if( in_array( "Event Sponsors", $ext_list ) ) { 
                $ext_details = em_get_more_extension_data( 'Event Sponsors' ); 
                if( $ext_details['is_activate'] ){?>
                    <!-- All Sponsors -->
                    <div class="ep-sc-block ep-box-col-4">
                        <div class="ep-escsubblock ep-bg-white ep-p-3 ep-position-relative ep-mb-4 ep-rounded-1">
                            <div class="ep-scblock ep-sctitle ep-fw-bold ep-text-center ep-pb-2"><?php esc_html_e('Event Sponsors', 'eventprime-event-calendar-management') ?>
                                <div style="display: none" class="ep-shorcode-copied"><?php esc_html_e('Shortcode Copied', 'eventprime-event-calendar-management'); ?></div>
                                <div class="ep-scblock-menu ep-sc-menu ep-position-absolute " style="right: 5px;top: 14px;" onclick="ep_shortcode_dropdown_menu(this)">
                                    <span class="material-icons ep-cursor">more_vert</span>
                                    <ul class="ep-sc-dropdown" style="display: none;">
                                        <li class="ep-sc-dropdown-item"><a href="javascript:void(0)" onclick="ep_copy_shortcode(document.getElementById('ep-all-event-sponsors-page'))"><?php esc_html_e('Copy Shortcode', 'eventprime-event-calendar-management'); ?></a></li> 
                                        <li class="ep-sc-dropdown-item"><a href="<?php echo admin_url('post.php?post='.$options->sponsor_page.'&action=edit'); ?>"><?php esc_html_e('New Page with Shortcode', 'eventprime-event-calendar-management'); ?></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="ep-scblock ep-sc-format-row ep-d-flex ep-justify-content-between ep-py-2">
                                <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Format', 'eventprime-event-calendar-management'); ?></div> 
                                <div class="ep-sc-dec ep-box-w-65" id="ep-all-event-sponsors-page">[em_sponsors]</div>
                            </div>
                            <div class="ep-scblock ep-sc-requirements-row ep-d-flex ep-justify-content-between ep-py-2">
                                <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Requirements', 'eventprime-event-calendar-management'); ?></div> 
                                <div class="ep-sc-dec ep-box-w-65">EventPrime Sponsors Extension</div>
                            </div>
                            <div class="ep-scblock-hide" style="display: none;">
                                <div class="ep-scblock ep-sc-example-row ep-d-flex ep-justify-content-between ep-py-2">
                                    <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Example', 'eventprime-event-calendar-management'); ?></div> 
                                    <div class="ep-sc-dec ep-box-w-65">[em_sponsors display_style="grid/colored_grid/rows" limit="{NUMBER}" cols="{NUMBER}" load_more="0 or 1" search="0 or 1"]</div>
                                </div>
                                <div class="ep-scblock ep-sc-parameters-row ep-d-flex ep-justify-content-between ep-py-2">
                                    <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Parameters', 'eventprime-event-calendar-management'); ?></div> 
                                    <div class="ep-sc-dec ep-box-w-65"><ul><li>None</li></ul></div>
                                </div>
                                <div class="ep-scblock ep-sc-description-row ep-d-flex ep-justify-content-between ep-py-2">
                                    <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Description', 'eventprime-event-calendar-management'); ?></div> 
                                    <div class="ep-sc-dec ep-morelink ep-box-w-65">Displays the all Event Sponsors panel on the frontend where users can view the categories and the number of events that belong to them. Use 'display_style', 'limit', 'cols', 'load_more', 'search' attributes to set their default values. 'limit' will be use to list no. of event sponsors. 'cols' will be use to list no. of event sponsors in one column. 'load_more' for show/hide load more event sponsors. 'search' for hide/show search event sponsors.</b></div>
                                </div>
                            </div> 
                            <div class="ep-scblock ep-shorocode-show-more ep-d-flex ep-align-items-center ep-content-center ep-cursor">
                                <?php esc_html_e('More info', 'eventprime-event-calendar-management'); ?>
                                <span class="material-icons">keyboard_arrow_down</span>
                            </div>
                            <div class="ep-scblock ep-shorocode-show-less ep-d-flex ep-align-items-center ep-content-center ep-cursor" style="display: none;">
                                <?php esc_html_e('Less info', 'eventprime-event-calendar-management'); ?>
                                <span class="material-icons">keyboard_arrow_up</span>
                            </div>
                        </div>
                    </div><?php 
                }
            } ?>

            <!-- Display Individual Event Type -->
            <div class="ep-sc-block ep-box-col-4">
                <div class="ep-escsubblock ep-bg-white ep-p-3 ep-position-relative ep-mb-4 ep-rounded-1">
                    <div class="ep-scblock ep-sctitle ep-fw-bold ep-text-center ep-pb-2"><?php esc_html_e('Display Individual Event Type', 'eventprime-event-calendar-management') ?>
                        <div style="display: none" class="ep-shorcode-copied"><?php esc_html_e('Shortcode Copied', 'eventprime-event-calendar-management'); ?></div>
                        <div class="ep-scblock-menu ep-sc-menu ep-position-absolute " style="right: 5px;top: 14px;" onclick="ep_shortcode_dropdown_menu(this)">
                            <span class="material-icons ep-cursor">more_vert</span>
                            <ul class="ep-sc-dropdown" style="display: none;">
                                <li class="ep-sc-dropdown-item"><a href="javascript:void(0)" onclick="ep_copy_shortcode(document.getElementById('ep-individual-event-type-page'))"><?php esc_html_e('Copy Shortcode', 'eventprime-event-calendar-management'); ?></a></li> 
                                <li class="ep-sc-dropdown-item"><a href="#"><?php esc_html_e('New Page with Shortcode', 'eventprime-event-calendar-management'); ?></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="ep-scblock ep-sc-format-row ep-d-flex ep-justify-content-between ep-py-2">
                        <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Format', 'eventprime-event-calendar-management'); ?></div> 
                        <div class="ep-sc-dec ep-box-w-65" id="ep-individual-event-type-page">[em_event_type id="{EVENT_TYPE_ID}"]</div>
                    </div>
                    <div class="ep-scblock ep-sc-requirements-row ep-d-flex ep-justify-content-between ep-py-2">
                        <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Requirements', 'eventprime-event-calendar-management'); ?></div> 
                        <div class="ep-sc-dec ep-box-w-65">Core</div>
                    </div>
                    <div class="ep-scblock-hide" style="display: none;">
                        <div class="ep-scblock ep-sc-example-row ep-d-flex ep-justify-content-between ep-py-2">
                            <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Example', 'eventprime-event-calendar-management'); ?></div> 
                            <div class="ep-sc-dec ep-box-w-65">[em_event_type id="{EVENT_TYPE_ID}" event_style="grid/rows/plain_list" event_limit="{NUMBER}" event_cols="{NUMBER}" load_more="0 or 1" hide_past_events="0 or 1"]</div>
                        </div>
                        <div class="ep-scblock ep-sc-parameters-row ep-d-flex ep-justify-content-between ep-py-2">
                            <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Parameters', 'eventprime-event-calendar-management'); ?></div> 
                            <div class="ep-sc-dec ep-box-w-65"><ul><li>None</li></ul></div>
                        </div>
                        <div class="ep-scblock ep-sc-description-row ep-d-flex ep-justify-content-between ep-py-2">
                            <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Description', 'eventprime-event-calendar-management'); ?></div> 
                            <div class="ep-sc-dec ep-morelink ep-box-w-65">Displays all details of a single Event Type. 'event_limit' will be use to list no. of events. 'event_cols' will be use to list no. of events in one column. 'load_more' for show/hide load more events. 'hide_past_event' for hide/show past events.</b></div>
                        </div>
                    </div> 
                    <div class="ep-scblock ep-shorocode-show-more ep-d-flex ep-align-items-center ep-content-center ep-cursor">
                        <?php esc_html_e('More info', 'eventprime-event-calendar-management'); ?>
                        <span class="material-icons">keyboard_arrow_down</span>
                    </div>
                    <div class="ep-scblock ep-shorocode-show-less ep-d-flex ep-align-items-center ep-content-center ep-cursor" style="display: none;">
                        <?php esc_html_e('Less info', 'eventprime-event-calendar-management'); ?>
                        <span class="material-icons">keyboard_arrow_up</span>
                    </div>
                </div>
            </div>

            <!-- Display Individual Event Site/Location -->
            <div class="ep-sc-block ep-box-col-4">
                <div class="ep-escsubblock ep-bg-white ep-p-3 ep-position-relative ep-mb-4 ep-rounded-1">
                    <div class="ep-scblock ep-sctitle ep-fw-bold ep-text-center ep-pb-2"><?php esc_html_e('Display Individual Event Site/Location', 'eventprime-event-calendar-management') ?>
                        <div style="display: none" class="ep-shorcode-copied"><?php esc_html_e('Shortcode Copied', 'eventprime-event-calendar-management'); ?></div>
                        <div class="ep-scblock-menu ep-sc-menu ep-position-absolute " style="right: 5px;top: 14px;" onclick="ep_shortcode_dropdown_menu(this)">
                            <span class="material-icons ep-cursor">more_vert</span>
                            <ul class="ep-sc-dropdown" style="display: none;">
                                <li class="ep-sc-dropdown-item"><a href="javascript:void(0)" onclick="ep_copy_shortcode(document.getElementById('ep-individual-event-site-page'))"><?php esc_html_e('Copy Shortcode', 'eventprime-event-calendar-management'); ?></a></li> 
                                <li class="ep-sc-dropdown-item"><a href="#"><?php esc_html_e('New Page with Shortcode', 'eventprime-event-calendar-management'); ?></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="ep-scblock ep-sc-format-row ep-d-flex ep-justify-content-between ep-py-2">
                        <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Format', 'eventprime-event-calendar-management'); ?></div> 
                        <div class="ep-sc-dec ep-box-w-65" id="ep-individual-event-site-page">[em_event_site id="{EVENT_SITE_ID}"]</div>
                    </div>

                    <div class="ep-scblock ep-sc-requirements-row ep-d-flex ep-justify-content-between ep-py-2">
                        <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Requirements', 'eventprime-event-calendar-management'); ?></div> 
                        <div class="ep-sc-dec ep-box-w-65">Core</div>
                    </div>

                    <div class="ep-scblock-hide" style="display: none;">
                        <div class="ep-scblock ep-sc-example-row ep-d-flex ep-justify-content-between ep-py-2">
                            <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Example', 'eventprime-event-calendar-management'); ?></div> 
                            <div class="ep-sc-dec ep-box-w-65">[em_event_site id="{EVENT_SITE_ID}" event_style="grid/rows/plain_list" event_limit="{NUMBER}" event_cols="{NUMBER}" load_more="0 or 1" hide_past_events="0 or 1"]</div>
                        </div>
                        <div class="ep-scblock ep-sc-parameters-row ep-d-flex ep-justify-content-between ep-py-2">
                            <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Parameters', 'eventprime-event-calendar-management'); ?></div> 
                            <div class="ep-sc-dec ep-box-w-65"><ul><li>None</li></ul></div>
                        </div>
                        <div class="ep-scblock ep-sc-description-row ep-d-flex ep-justify-content-between ep-py-2">
                            <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Description', 'eventprime-event-calendar-management'); ?></div> 
                            <div class="ep-sc-dec ep-morelink ep-box-w-65">Displays all details of a single Event Site. 'event_limit' will be use to list no. of events. 'event_cols' will be use to list no. of events in one column. 'load_more' for show/hide load more events. 'hide_past_event' for hide/show past events.</b></div>
                        </div>
                    </div> 
                    <div class="ep-scblock ep-shorocode-show-more ep-d-flex ep-align-items-center ep-content-center ep-cursor">
                        <?php esc_html_e('More info', 'eventprime-event-calendar-management'); ?>
                        <span class="material-icons">keyboard_arrow_down</span>
                    </div>
                    <div class="ep-scblock ep-shorocode-show-less ep-d-flex ep-align-items-center ep-content-center ep-cursor" style="display: none;">
                        <?php esc_html_e('Less info', 'eventprime-event-calendar-management'); ?>
                        <span class="material-icons">keyboard_arrow_up</span>
                    </div>
                </div>
            </div>

            <!-- Display Individual Event Organizer -->
            <div class="ep-sc-block ep-box-col-4">
                <div class="ep-escsubblock ep-bg-white ep-p-3 ep-position-relative ep-mb-4 ep-rounded-1">
                    <div class="ep-scblock ep-sctitle ep-fw-bold ep-text-center ep-pb-2"><?php esc_html_e('Display Individual Event Organizer', 'eventprime-event-calendar-management') ?>
                        <div style="display: none" class="ep-shorcode-copied"><?php esc_html_e('Shortcode Copied', 'eventprime-event-calendar-management'); ?></div>
                        <div class="ep-scblock-menu ep-sc-menu ep-position-absolute " style="right: 5px;top: 14px;" onclick="ep_shortcode_dropdown_menu(this)">
                            <span class="material-icons ep-cursor">more_vert</span>
                            <ul class="ep-sc-dropdown" style="display: none;">
                                <li class="ep-sc-dropdown-item"><a href="javascript:void(0)" onclick="ep_copy_shortcode(document.getElementById('ep-individual-event-organizer-page'))"><?php esc_html_e('Copy Shortcode', 'eventprime-event-calendar-management'); ?></a></li> 
                                <li class="ep-sc-dropdown-item"><a href="#"><?php esc_html_e('New Page with Shortcode', 'eventprime-event-calendar-management'); ?></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="ep-scblock ep-sc-format-row ep-d-flex ep-justify-content-between ep-py-2">
                        <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Format', 'eventprime-event-calendar-management'); ?></div> 
                        <div class="ep-sc-dec ep-box-w-65" id="ep-individual-event-organizer-page">[em_event_organizer id="{EVENT_ORGANIZER_ID}"]</div>
                    </div>
                    <div class="ep-scblock ep-sc-requirements-row ep-d-flex ep-justify-content-between ep-py-2">
                        <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Requirements', 'eventprime-event-calendar-management'); ?></div> 
                        <div class="ep-sc-dec ep-box-w-65">Core</div>
                    </div>
                    <div class="ep-scblock-hide" style="display: none;">
                        <div class="ep-scblock ep-sc-example-row ep-d-flex ep-justify-content-between ep-py-2">
                            <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Example', 'eventprime-event-calendar-management'); ?></div> 
                            <div class="ep-sc-dec ep-box-w-65">[em_event_organizer id="{EVENT_ORGANIZER_ID}" event_style="grid/rows/plain_list" event_limit="{NUMBER}" event_cols="{NUMBER}" load_more="0 or 1" hide_past_events="0 or 1"]</div>
                        </div>
                        <div class="ep-scblock ep-sc-parameters-row ep-d-flex ep-justify-content-between ep-py-2">
                            <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Parameters', 'eventprime-event-calendar-management'); ?></div> 
                            <div class="ep-sc-dec ep-box-w-65"><ul><li>None</li></ul></div>
                        </div>
                        <div class="ep-scblock ep-sc-description-row ep-d-flex ep-justify-content-between ep-py-2">
                            <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Description', 'eventprime-event-calendar-management'); ?></div> 
                            <div class="ep-sc-dec ep-morelink ep-box-w-65">Displays all details of a single Event Organizer. 'event_limit' will be use to list no. of events. 'event_cols' will be use to list no. of events in one column. 'load_more' for show/hide load more events. 'hide_past_event' for hide/show past events.</b></div>
                        </div>
                    </div> 
                    <div class="ep-scblock ep-shorocode-show-more ep-d-flex ep-align-items-center ep-content-center ep-cursor">
                        <?php esc_html_e('More info', 'eventprime-event-calendar-management'); ?>
                        <span class="material-icons">keyboard_arrow_down</span>
                    </div>
                    <div class="ep-scblock ep-shorocode-show-less ep-d-flex ep-align-items-center ep-content-center ep-cursor" style="display: none;">
                        <?php esc_html_e('Less info', 'eventprime-event-calendar-management'); ?>
                        <span class="material-icons">keyboard_arrow_up</span>
                    </div>
                </div>
            </div>

            <!-- Display Individual Performer -->
            <div class="ep-sc-block ep-box-col-4">
                <div class="ep-escsubblock ep-bg-white ep-p-3 ep-position-relative ep-mb-4 ep-rounded-1">
                    <div class="ep-scblock ep-sctitle ep-fw-bold ep-text-center ep-pb-2"><?php esc_html_e('Display Individual Performer', 'eventprime-event-calendar-management') ?>
                        <div style="display: none" class="ep-shorcode-copied"><?php esc_html_e('Shortcode Copied', 'eventprime-event-calendar-management'); ?></div>
                        <div class="ep-scblock-menu ep-sc-menu ep-position-absolute " style="right: 5px;top: 14px;" onclick="ep_shortcode_dropdown_menu(this)">
                            <span class="material-icons ep-cursor">more_vert</span>
                            <ul class="ep-sc-dropdown" style="display: none;">
                                <li class="ep-sc-dropdown-item"><a href="javascript:void(0)" onclick="ep_copy_shortcode(document.getElementById('ep-individual-performer-page'))"><?php esc_html_e('Copy Shortcode', 'eventprime-event-calendar-management'); ?></a></li> 
                                <li class="ep-sc-dropdown-item"><a href="#"><?php esc_html_e('New Page with Shortcode', 'eventprime-event-calendar-management'); ?></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="ep-scblock ep-sc-format-row ep-d-flex ep-justify-content-between ep-py-2">
                        <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Format', 'eventprime-event-calendar-management'); ?></div> 
                        <div class="ep-sc-dec ep-box-w-65" id="ep-individual-performer-page">[em_performer id="{PERFORMER_ID}"]</div>
                    </div>
                    <div class="ep-scblock ep-sc-requirements-row ep-d-flex ep-justify-content-between ep-py-2">
                        <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Requirements', 'eventprime-event-calendar-management'); ?></div> 
                        <div class="ep-sc-dec ep-box-w-65">Core</div>
                    </div>
                    <div class="ep-scblock-hide" style="display: none;">
                        <div class="ep-scblock ep-sc-example-row ep-d-flex ep-justify-content-between ep-py-2">
                            <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Example', 'eventprime-event-calendar-management'); ?></div> 
                            <div class="ep-sc-dec ep-box-w-65">[em_performer id="{PERFORMER_ID}" event_style="grid/rows/plain_list" event_limit="{NUMBER}" event_cols="{NUMBER}" load_more="0 or 1" hide_past_event="0 or 1"]</div>
                        </div>
                        <div class="ep-scblock ep-sc-parameters-row ep-d-flex ep-justify-content-between ep-py-2">
                            <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Parameters', 'eventprime-event-calendar-management'); ?></div> 
                            <div class="ep-sc-dec ep-box-w-65"><ul><li>None</li></ul></div>
                        </div>
                        <div class="ep-scblock ep-sc-description-row ep-d-flex ep-justify-content-between ep-py-2">
                            <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Description', 'eventprime-event-calendar-management'); ?></div> 
                            <div class="ep-sc-dec ep-morelink ep-box-w-65">Displays all details of a single Performer. 'event_limit' will be use to list no. of events. 'event_cols' will be use to list no. of events in one column. 'load_more' for show/hide load more events. 'hide_past_event' for hide/show past events.</b></div>
                        </div>
                    </div> 
                    <div class="ep-scblock ep-shorocode-show-more ep-d-flex ep-align-items-center ep-content-center ep-cursor">
                        <?php esc_html_e('More info', 'eventprime-event-calendar-management'); ?>
                        <span class="material-icons">keyboard_arrow_down</span>
                    </div>
                    <div class="ep-scblock ep-shorocode-show-less ep-d-flex ep-align-items-center ep-content-center ep-cursor" style="display: none;">
                        <?php esc_html_e('Less info', 'eventprime-event-calendar-management'); ?>
                        <span class="material-icons">keyboard_arrow_up</span>
                    </div>
                </div>
            </div>

            <?php if( in_array( "Event Sponsors", $ext_list ) ) { 
                $ext_details = em_get_more_extension_data( 'Event Sponsors' ); 
                if( $ext_details['is_activate'] ){ ?>
                    <!-- Display Individual Sponsor -->
                    <div class="ep-sc-block ep-box-col-4">
                        <div class="ep-escsubblock ep-bg-white ep-p-3 ep-position-relative ep-mb-4 ep-rounded-1">
                            <div class="ep-scblock ep-sctitle ep-fw-bold ep-text-center ep-pb-2"><?php esc_html_e('Display Individual Sponsor', 'eventprime-event-calendar-management') ?>
                                <div style="display: none" class="ep-shorcode-copied"><?php esc_html_e('Shortcode Copied', 'eventprime-event-calendar-management'); ?></div>
                                <div class="ep-scblock-menu ep-sc-menu ep-position-absolute " style="right: 5px;top: 14px;" onclick="ep_shortcode_dropdown_menu(this)">
                                    <span class="material-icons ep-cursor">more_vert</span>
                                    <ul class="ep-sc-dropdown" style="display: none;">
                                        <li class="ep-sc-dropdown-item"><a href="javascript:void(0)" onclick="ep_copy_shortcode(document.getElementById('ep-individual-sponsor-page'))"><?php esc_html_e('Copy Shortcode', 'eventprime-event-calendar-management'); ?></a></li> 
                                        <li class="ep-sc-dropdown-item"><a href="#"><?php esc_html_e('New Page with Shortcode', 'eventprime-event-calendar-management'); ?></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="ep-scblock ep-sc-format-row ep-d-flex ep-justify-content-between ep-py-2">
                                <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Format', 'eventprime-event-calendar-management'); ?></div> 
                                <div class="ep-sc-dec ep-box-w-65" id="ep-individual-sponsor-page">[em_sponsor id="{SPONSOR_ID}"]</div>
                            </div>

                            <div class="ep-scblock ep-sc-requirements-row ep-d-flex ep-justify-content-between ep-py-2">
                                <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Requirements', 'eventprime-event-calendar-management'); ?></div> 
                                <div class="ep-sc-dec ep-box-w-65">EventPrime Sponsors Extension</div>
                            </div>

                            <div class="ep-scblock-hide" style="display: none;">
                                <div class="ep-scblock ep-sc-example-row ep-d-flex ep-justify-content-between ep-py-2">
                                    <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Example', 'eventprime-event-calendar-management'); ?></div> 
                                    <div class="ep-sc-dec ep-box-w-65">[em_sponsor id="{SPONSOR_ID}" event_style="grid/rows/plain_list" event_limit="{NUMBER}" event_cols="{NUMBER}" load_more="0 or 1" hide_past_event="0 or 1"]</div>

                                </div>



                                <div class="ep-scblock ep-sc-parameters-row ep-d-flex ep-justify-content-between ep-py-2">
                                    <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Parameters', 'eventprime-event-calendar-management'); ?></div> 
                                    <div class="ep-sc-dec ep-box-w-65"><ul><li>None</li></ul></div>
                                </div>



                                <div class="ep-scblock ep-sc-description-row ep-d-flex ep-justify-content-between ep-py-2">
                                    <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Description', 'eventprime-event-calendar-management'); ?></div> 
                                    <div class="ep-sc-dec ep-morelink ep-box-w-65">Displays all details of a single Event Sponsor. 'event_limit' will be use to list no. of events. 'event_cols' will be use to list no. of events in one column. 'load_more' for show/hide load more events. 'hide_past_event' for hide/show past events.</b></div>
                                </div>

                            </div> 
                            <div class="ep-scblock ep-shorocode-show-more ep-d-flex ep-align-items-center ep-content-center ep-cursor">
                                <?php esc_html_e('More info', 'eventprime-event-calendar-management'); ?>
                                <span class="material-icons">keyboard_arrow_down</span>
                            </div>

                            <div class="ep-scblock ep-shorocode-show-less ep-d-flex ep-align-items-center ep-content-center ep-cursor" style="display: none;">
                                <?php esc_html_e('Less info', 'eventprime-event-calendar-management'); ?>
                                <span class="material-icons">keyboard_arrow_up</span>
                            </div>

                        </div>

                    </div><?php 
                }
            } ?>

            <!-- User Account Area -->
            <div class="ep-sc-block ep-box-col-4">
                <div class="ep-escsubblock ep-bg-white ep-p-3 ep-position-relative ep-mb-4 ep-rounded-1">
                    <div class="ep-scblock ep-sctitle ep-fw-bold ep-text-center ep-pb-2"><?php esc_html_e('User Account Area', 'eventprime-event-calendar-management') ?>
                        <div style="display: none" class="ep-shorcode-copied"><?php esc_html_e('Shortcode Copied', 'eventprime-event-calendar-management'); ?></div>
                        <div class="ep-scblock-menu ep-sc-menu ep-position-absolute " style="right: 5px;top: 14px;" onclick="ep_shortcode_dropdown_menu(this)">
                            <span class="material-icons ep-cursor">more_vert</span>
                            <ul class="ep-sc-dropdown" style="display: none;">
                                <li class="ep-sc-dropdown-item"><a href="javascript:void(0)" onclick="ep_copy_shortcode(document.getElementById('ep-user-profile-page'))"><?php esc_html_e('Copy Shortcode', 'eventprime-event-calendar-management'); ?></a></li> 
                                <li class="ep-sc-dropdown-item"><a href="<?php echo admin_url('post.php?post='.$options->profile_page.'&action=edit'); ?>"><?php esc_html_e('New Page with Shortcode', 'eventprime-event-calendar-management'); ?></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="ep-scblock ep-sc-format-row ep-d-flex ep-justify-content-between ep-py-2">
                        <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Format', 'eventprime-event-calendar-management'); ?></div> 
                        <div class="ep-sc-dec ep-box-w-65" id="ep-user-profile-page">[em_profile default="login or registration"]</div>
                    </div>
                    <div class="ep-scblock ep-sc-requirements-row ep-d-flex ep-justify-content-between ep-py-2">
                        <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Requirements', 'eventprime-event-calendar-management'); ?></div> 
                        <div class="ep-sc-dec ep-box-w-65">Core</div>
                    </div>
                    <div class="ep-scblock-hide" style="display: none;">
                        <div class="ep-scblock ep-sc-example-row ep-d-flex ep-justify-content-between ep-py-2">
                            <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Example', 'eventprime-event-calendar-management'); ?></div> 
                            <div class="ep-sc-dec ep-box-w-65">[em_profile default="login or registration"]</div>
                        </div>
                        <div class="ep-scblock ep-sc-parameters-row ep-d-flex ep-justify-content-between ep-py-2">
                            <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Parameters', 'eventprime-event-calendar-management'); ?></div> 
                            <div class="ep-sc-dec ep-box-w-65"><ul><li>None</li></ul></div>
                        </div>
                        <div class="ep-scblock ep-sc-description-row ep-d-flex ep-justify-content-between ep-py-2">
                            <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Description', 'eventprime-event-calendar-management'); ?></div> 
                            <div class="ep-sc-dec ep-morelink ep-box-w-65">Displays the user profile panel where a user can login and see his/her bookings for events. 'default' attribute will be use to show login or registration screens when user is not loggedin.</b></div>
                        </div>
                    </div> 
                    <div class="ep-scblock ep-shorocode-show-more ep-d-flex ep-align-items-center ep-content-center ep-cursor">
                        <?php esc_html_e('More info', 'eventprime-event-calendar-management'); ?>
                        <span class="material-icons">keyboard_arrow_down</span>
                    </div>
                    <div class="ep-scblock ep-shorocode-show-less ep-d-flex ep-align-items-center ep-content-center ep-cursor" style="display: none;">
                        <?php esc_html_e('Less info', 'eventprime-event-calendar-management'); ?>
                        <span class="material-icons">keyboard_arrow_up</span>
                    </div>
                </div>
            </div>

            <!-- Processes Event Bookings -->
            <div class="ep-sc-block ep-box-col-4">
                <div class="ep-escsubblock ep-bg-white ep-p-3 ep-position-relative ep-mb-4 ep-rounded-1">
                    <div class="ep-scblock ep-sctitle ep-fw-bold ep-text-center ep-pb-2"><?php esc_html_e('Processes Event Bookings', 'eventprime-event-calendar-management') ?>
                        <div style="display: none" class="ep-shorcode-copied"><?php esc_html_e('Shortcode Copied', 'eventprime-event-calendar-management'); ?></div>
                        <div class="ep-scblock-menu ep-sc-menu ep-position-absolute " style="right: 5px;top: 14px;" onclick="ep_shortcode_dropdown_menu(this)">
                            <span class="material-icons ep-cursor">more_vert</span>
                            <ul class="ep-sc-dropdown" style="display: none;">
                                <li class="ep-sc-dropdown-item"><a href="javascript:void(0)" onclick="ep_copy_shortcode(document.getElementById('ep-event-booking-page'))"><?php esc_html_e('Copy Shortcode', 'eventprime-event-calendar-management'); ?></a></li> 
                                <li class="ep-sc-dropdown-item"><a href="<?php echo admin_url('post.php?post='.$options->booking_page.'&action=edit'); ?>"><?php esc_html_e('New Page with Shortcode', 'eventprime-event-calendar-management'); ?></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="ep-scblock ep-sc-format-row ep-d-flex ep-justify-content-between ep-py-2">
                        <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Format', 'eventprime-event-calendar-management'); ?></div> 
                        <div class="ep-sc-dec ep-box-w-65" id="ep-event-booking-page">[em_booking]</div>
                    </div>
                    <div class="ep-scblock ep-sc-requirements-row ep-d-flex ep-justify-content-between ep-py-2">
                        <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Requirements', 'eventprime-event-calendar-management'); ?></div> 
                        <div class="ep-sc-dec ep-box-w-65">Core</div>
                    </div>
                    <div class="ep-scblock-hide" style="display: none;">
                        <div class="ep-scblock ep-sc-example-row ep-d-flex ep-justify-content-between ep-py-2">
                            <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Example', 'eventprime-event-calendar-management'); ?></div> 
                            <div class="ep-sc-dec ep-box-w-65">[em_booking]</div>
                        </div>
                        <div class="ep-scblock ep-sc-parameters-row ep-d-flex ep-justify-content-between ep-py-2">
                            <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Parameters', 'eventprime-event-calendar-management'); ?></div> 
                            <div class="ep-sc-dec ep-box-w-65"><ul><li>None</li></ul></div>
                        </div>
                        <div class="ep-scblock ep-sc-description-row ep-d-flex ep-justify-content-between ep-py-2">
                            <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Description', 'eventprime-event-calendar-management'); ?></div> 
                            <div class="ep-sc-dec ep-morelink ep-box-w-65">Add this shortcode to the page on which you want to process your event bookings.</b></div>
                        </div>
                    </div> 
                    <div class="ep-scblock ep-shorocode-show-more ep-d-flex ep-align-items-center ep-content-center ep-cursor">
                        <?php esc_html_e('More info', 'eventprime-event-calendar-management'); ?>
                        <span class="material-icons">keyboard_arrow_down</span>
                    </div>
                    <div class="ep-scblock ep-shorocode-show-less ep-d-flex ep-align-items-center ep-content-center ep-cursor" style="display: none;">
                        <?php esc_html_e('Less info', 'eventprime-event-calendar-management'); ?>
                        <span class="material-icons">keyboard_arrow_up</span>
                    </div>
                </div>
            </div>

            <!-- Display Event Submission Form -->
            <div class="ep-sc-block ep-box-col-4">
                <div class="ep-escsubblock ep-bg-white ep-p-3 ep-position-relative ep-mb-4 ep-rounded-1">
                    <div class="ep-scblock ep-sctitle ep-fw-bold ep-text-center ep-pb-2"><?php esc_html_e('Display Event Submission Form', 'eventprime-event-calendar-management') ?>
                        <div style="display: none" class="ep-shorcode-copied"><?php esc_html_e('Shortcode Copied', 'eventprime-event-calendar-management'); ?></div>
                        <div class="ep-scblock-menu ep-sc-menu ep-position-absolute " style="right: 5px;top: 14px;" onclick="ep_shortcode_dropdown_menu(this)">
                            <span class="material-icons ep-cursor">more_vert</span>
                            <ul class="ep-sc-dropdown" style="display: none;">
                                <li class="ep-sc-dropdown-item"><a href="javascript:void(0)" onclick="ep_copy_shortcode(document.getElementById('ep-event-submit-form-page'))"><?php esc_html_e('Copy Shortcode', 'eventprime-event-calendar-management'); ?></a></li> 
                                <li class="ep-sc-dropdown-item"><a href="<?php echo admin_url('post.php?post='.$options->event_submit_form.'&action=edit'); ?>"><?php esc_html_e('New Page with Shortcode', 'eventprime-event-calendar-management'); ?></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="ep-scblock ep-sc-format-row ep-d-flex ep-justify-content-between ep-py-2">
                        <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Format', 'eventprime-event-calendar-management'); ?></div> 
                        <div class="ep-sc-dec ep-box-w-65" id="ep-event-submit-form-page">[em_event_submit_form]</div>
                    </div>
                    <div class="ep-scblock ep-sc-requirements-row ep-d-flex ep-justify-content-between ep-py-2">
                        <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Requirements', 'eventprime-event-calendar-management'); ?></div> 
                        <div class="ep-sc-dec ep-box-w-65">Core</div>
                    </div>
                    <div class="ep-scblock-hide" style="display: none;">
                        <div class="ep-scblock ep-sc-example-row ep-d-flex ep-justify-content-between ep-py-2">
                            <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Example', 'eventprime-event-calendar-management'); ?></div> 
                            <div class="ep-sc-dec ep-box-w-65">[em_event_submit_form]</div>
                        </div>
                        <div class="ep-scblock ep-sc-parameters-row ep-d-flex ep-justify-content-between ep-py-2">
                            <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Parameters', 'eventprime-event-calendar-management'); ?></div> 
                            <div class="ep-sc-dec ep-box-w-65"><ul><li>None</li></ul></div>
                        </div>
                        <div class="ep-scblock ep-sc-description-row ep-d-flex ep-justify-content-between ep-py-2">
                            <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Description', 'eventprime-event-calendar-management'); ?></div> 
                            <div class="ep-sc-dec ep-morelink ep-box-w-65">Add this shortcode to the page on which you want to display the frontend event submission form.</b></div>
                        </div>
                    </div> 
                    <div class="ep-scblock ep-shorocode-show-more ep-d-flex ep-align-items-center ep-content-center ep-cursor">
                        <?php esc_html_e('More info', 'eventprime-event-calendar-management'); ?>
                        <span class="material-icons">keyboard_arrow_down</span>
                    </div>
                    <div class="ep-scblock ep-shorocode-show-less ep-d-flex ep-align-items-center ep-content-center ep-cursor" style="display: none;">
                        <?php esc_html_e('Less info', 'eventprime-event-calendar-management'); ?>
                        <span class="material-icons">keyboard_arrow_up</span>
                    </div>
                </div>
            </div>

            <!-- Display Booking Details -->
            <div class="ep-sc-block ep-box-col-4">
                <div class="ep-escsubblock ep-bg-white ep-p-3 ep-position-relative ep-mb-4 ep-rounded-1">
                    <div class="ep-scblock ep-sctitle ep-fw-bold ep-text-center ep-pb-2"><?php esc_html_e('Display Booking Details', 'eventprime-event-calendar-management') ?>
                        <div style="display: none" class="ep-shorcode-copied"><?php esc_html_e('Shortcode Copied', 'eventprime-event-calendar-management'); ?></div>
                        <div class="ep-scblock-menu ep-sc-menu ep-position-absolute " style="right: 5px;top: 14px;" onclick="ep_shortcode_dropdown_menu(this)">
                            <span class="material-icons ep-cursor">more_vert</span>
                            <ul class="ep-sc-dropdown" style="display: none;">
                                <li class="ep-sc-dropdown-item"><a href="javascript:void(0)" onclick="ep_copy_shortcode(document.getElementById('ep-booking-details'))"><?php esc_html_e('Copy Shortcode', 'eventprime-event-calendar-management'); ?></a></li> 
                                <li class="ep-sc-dropdown-item"><a href="<?php echo admin_url('post.php?post='.$options->booking_details_page.'&action=edit'); ?>"><?php esc_html_e('New Page with Shortcode', 'eventprime-event-calendar-management'); ?></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="ep-scblock ep-sc-format-row ep-d-flex ep-justify-content-between ep-py-2">
                        <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Format', 'eventprime-event-calendar-management'); ?></div> 
                        <div class="ep-sc-dec ep-box-w-65" id="ep-booking-details">[em_booking_details]</div>
                    </div>
                    <div class="ep-scblock ep-sc-requirements-row ep-d-flex ep-justify-content-between ep-py-2">
                        <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Requirements', 'eventprime-event-calendar-management'); ?></div> 
                        <div class="ep-sc-dec ep-box-w-65">Core</div>
                    </div>
                    <div class="ep-scblock-hide" style="display: none;">
                        <div class="ep-scblock ep-sc-example-row ep-d-flex ep-justify-content-between ep-py-2">
                            <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Example', 'eventprime-event-calendar-management'); ?></div> 
                            <div class="ep-sc-dec ep-box-w-65">[em_booking_details]</div>
                        </div>
                        <div class="ep-scblock ep-sc-parameters-row ep-d-flex ep-justify-content-between ep-py-2">
                            <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Parameters', 'eventprime-event-calendar-management'); ?></div> 
                            <div class="ep-sc-dec ep-box-w-65"><ul><li>None</li></ul></div>
                        </div>
                        <div class="ep-scblock ep-sc-description-row ep-d-flex ep-justify-content-between ep-py-2">
                            <div class="ep-sc-title ep-fw-bold"><?php esc_html_e('Description', 'eventprime-event-calendar-management'); ?></div> 
                            <div class="ep-sc-dec ep-morelink ep-box-w-65">Add this shortcode to the page on which you want to display the booking details.</b></div>
                        </div>
                    </div> 
                    <div class="ep-scblock ep-shorocode-show-more ep-d-flex ep-align-items-center ep-content-center ep-cursor">
                        <?php esc_html_e('More info', 'eventprime-event-calendar-management'); ?>
                        <span class="material-icons">keyboard_arrow_down</span>
                    </div>
                    <div class="ep-scblock ep-shorocode-show-less ep-d-flex ep-align-items-center ep-content-center ep-cursor" style="display: none;">
                        <?php esc_html_e('Less info', 'eventprime-event-calendar-management'); ?>
                        <span class="material-icons">keyboard_arrow_up</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.ep-scblock-menu {
    right: 5px;
    top: 14px;
}
.ep-scblock-menu{
    line-height: 0px;
    color:#2271b1
}
.ep-escsubblock .ep-sc-dropdown {
    position: absolute;
    right: -18px;
    width: 170px;
    background-color: #fff;
    box-shadow: 0px 2px 4px 0px rgba(102, 102, 102, 0.18);
    top: 32px;
    z-index: 99999;
}
.ep-escsubblock .ep-sc-dropdown .ep-sc-dropdown-item {
    font-size: 12px;
    font-weight: 400;
    text-align: left;
}
.ep-escsubblock .ep-sc-dropdown .ep-sc-dropdown-item a {
    float: left;
    width: 100%;
    padding: 10px 10px;
    color: #555d66;
    border-bottom: 1px solid rgba(204, 204, 204, 0.25);
    transition: 0.2s;
    line-height: 20px;
    text-decoration: none;
}
.ep-escsubblock .ep-sc-dropdown .ep-sc-dropdown-item a:hover {
    background-color: #2271b1;
    color: #fff;
}
.ep-shorcode-copied {
    position: absolute;
    right: 9px;
    background-color: #fff;
    padding: 0px 13px;
    border: 1px solid #f1f1f1;
}

.ep-escsubblock .ep-sc-dec{
    word-wrap: break-word;
}
</style>

<script>
    function ep_shortcode_dropdown_menu(a) {
        jQuery(a).find('.ep-sc-dropdown').slideToggle('fast');
        jQuery('.ep-scblock-menu').not(a).children(".ep-sc-dropdown").slideUp('fast');
    }
    (function ($) {
        $(document).ready(function () {
            var showChar = 50;
            var ellipsestext = "...";
            var moretext = "See More";
            var lesstext = "See Less";
            $('.ep-scblock.ep-sc-description-row .ep-sc-deccc').each(function () {
                var content = $(this).html();
                if (content.length > showChar) {
                    var show_content = content.substr(0, showChar);
                    var hide_content = content.substr(showChar, content.length - showChar);
                    var html = show_content + '<span class="moreelipses">' + ellipsestext + '</span><span class="remaining-content"><span>' + hide_content + '</span>&nbsp;&nbsp;<a href="" class="ep-morelinkcc">' + moretext + '</a></span>';
                    $(this).html(html);
                }
            });

            $(".ep-morelinkcc").click(function () {
                if ($(this).hasClass("less")) {
                    $(this).removeClass("less");
                    $(this).html(moretext);
                } else {
                    $(this).addClass("less");
                    $(this).html(lesstext);
                }
                $(this).parent().prev().toggle();
                $(this).prev().toggle();
                return false;
            });

            $('.ep-escsubblock').each(function () {
                var $dropdown = $(this);
                $(".ep-shorocode-show-more", $dropdown).click(function (e) {
                    e.preventDefault();
                    $(".ep-scblock-hide", $dropdown).show();
                    $(".ep-shorocode-show-less", $dropdown).show();
                    $(".ep-shorocode-show-more", $dropdown).hide();
                    return false;
                });
                $(".ep-shorocode-show-less", $dropdown).click(function (e) {
                    e.preventDefault();
                    $(".ep-scblock-hide", $dropdown).hide();
                    $(".ep-shorocode-show-less", $dropdown).hide();
                    $(".ep-shorocode-show-more", $dropdown).show();
                });
            });
        });
    })(jQuery);

    function ep_copy_shortcode(target) {
        var text_to_copy = jQuery(target).text();
        var tmp = jQuery("<input id='ep_shortcode_input' readonly>");
        var target_html = jQuery(target).html();
        jQuery(target).html('');
        jQuery(target).append(tmp);
        tmp.val(text_to_copy).select();
        var result = document.execCommand("copy");
        if (result != false) {
            jQuery(target).html(target_html);
            jQuery(target).parents('.ep-escsubblock').children('.ep-sctitle').children(".ep-shorcode-copied").fadeIn('slow');
            jQuery(target).parents('.ep-escsubblock').children('.ep-sctitle').children(".ep-shorcode-copied").fadeOut('slow');
        } else {
            jQuery(document).mouseup(function (e) {
                var container = jQuery("#ep_shortcode_input");
                if (!container.is(e.target) && container.has(e.target).length === 0) {
                    jQuery(target).html(target_html);
                }
            });
        }
    }
</script>