<?php
if (!defined('ABSPATH')) {
    die;
} // Cannot access pages directly.
if (!class_exists('TTBM_Settings_pricing')) {
    class TTBM_Settings_pricing {
        public function __construct() {
            add_action('add_ttbm_settings_tab_name', [$this, 'add_tab'], 10);
            add_action('add_ttbm_settings_tab_content', [$this, 'pricing_tab_content'], 10, 1);
            add_action('ttbm_price_item', array($this, 'pricing_item'));

            add_action('ttbm_settings_save', [$this, 'save_pricing']);
        }
        public function add_tab() {
            ?>
            <li class="nav-item" data-tabs-target="#ttbm_settings_pricing">
                <i class="fas fa-hand-holding-usd"></i><?php esc_html_e(' Pricing', 'tour-booking-manager'); ?>
            </li>
            <?php
            do_action('ttbm_meta_box_tab_after_pricing');
        }
        public function pricing_tab_content($tour_id) {
            $all_types = TTBM_Function::tour_type();
            $ttbm_type = TTBM_Function::get_tour_type($tour_id);
            $display = MP_Global_Function::get_post_info($tour_id, 'ttbm_display_registration', 'on');
            $active = $display == 'off' ? '' : 'mActive';
            $checked = $display == 'off' ? '' : 'checked';
            ?>
            <div class="tabsItem ttbm_settings_pricing" data-tabs="#ttbm_settings_pricing">
                <h2 class="h4 px-0 text-primary"><?php esc_html_e('Pricing Settings', 'tour-booking-manager'); ?></h2>

                <section class="component d-flex justify-content-between align-items-center mb-2">
                    <div class="w-100 d-flex justify-content-between align-items-center">
                        <label for=""><?php esc_html_e('On/Off Registration', 'tour-booking-manager'); ?> <i class="fas fa-question-circle tool-tips"><?php TTBM_Settings::des_p('ttbm_display_registration'); ?></i></label>
                        <div class=" d-flex justify-content-end align-items-center">
                            <?php MP_Custom_Layout::switch_button('ttbm_display_registration', $checked); ?>
                        </div>
                    </div>
                </section>

                <div data-collapse="#ttbm_display_registration" class="<?php echo esc_attr($active); ?>">
                    <?php $this->ttbm_add_to_cart_form_shortcode($tour_id); ?>
                    <?php do_action('ttbm_tour_pricing_before', $tour_id); ?>
                    <section class="component d-flex justify-content-between align-items-center mb-2">
                        <div class="w-100 d-flex justify-content-between align-items-center">
                            <label for=""><?php esc_html_e('Tour Type', 'tour-booking-manager'); ?> <i class="fas fa-question-circle tool-tips"><?php TTBM_Settings::des_p('ttbm_display_registration'); ?></i></label>
                            <div class=" d-flex justify-content-end align-items-center">
                                <select class="formControl" name="ttbm_type">
                                    <?php foreach ($all_types as $key => $type) { ?>
                                        <option value="<?php echo esc_attr($key) ?>" <?php echo esc_attr($ttbm_type == $key ? 'selected' : ''); ?>><?php echo esc_html($type) ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </section>
                    <?php do_action('ttbm_hotel_pricing_before', $tour_id); ?>
                    <?php $this->ttbm_hotel_config($tour_id); ?>
                    <?php do_action('ttbm_hotel_pricing_after', $tour_id); ?>
                    <?php $this->ttbm_ticket_config($tour_id); ?>
                    <?php do_action('ttbm_tour_pricing_after', $tour_id); ?>
                    <?php $this->advertise_addon(); ?>
                </div>
            </div>
            <?php
        }

        public function ttbm_add_to_cart_form_shortcode($tour_id){
            ?>
            <section class="component d-flex justify-content-between align-items-center my-2">
                <div class="w-100 d-flex justify-content-between align-items-center">
                    <label for=""><?php esc_html_e('Add To Cart Form Shortcode : ', 'tour-booking-manager'); ?> <i class="fas fa-question-circle tool-tips"><?php TTBM_Settings::des_p('ttbm_short_code'); ?></i></label>
                    <code> [ttbm-registration ttbm_id="<?php echo esc_html($tour_id); ?>"]</code>
                </div>
            </section>
            <?php
        }

        public function ttbm_ticket_config($tour_id) {
            $ticket_type = MP_Global_Function::get_post_info($tour_id, 'ttbm_ticket_type', array());
            $tour_label = TTBM_Function::get_name();
            $ttbm_type = TTBM_Function::get_tour_type($tour_id);
            $type_class = $ttbm_type == 'general' ? '' : 'dNone';
            $display = MP_Global_Function::get_post_info($tour_id, 'ttbm_display_advance', 'off');
            $active = $display == 'off' ? '' : 'mActive';
            $checked = $display == 'off' ? '' : 'checked';
            ?>
            <section class="ttbm_ticket_config  <?php echo esc_html($type_class); ?>">
                <h2 class="h4 px-0 text-primary"><?php echo esc_html($tour_label) . ' ' . esc_html__(' Price Configuration :', 'tour-booking-manager'); ?></h2>
                <div class="component mp_settings_area ttbm_price_config">
                    <section class=" d-flex justify-content-between align-items-center mb-5">
                        <div class="w-100 d-flex justify-content-between align-items-center">
                            <label for=""><?php esc_html_e('Show advance columns', 'tour-booking-manager'); ?> <i class="fas fa-question-circle tool-tips"><?php TTBM_Settings::des_p('ttbm_display_advance'); ?></i></label>
                            <div class=" d-flex justify-content-between">
                                <?php MP_Custom_Layout::switch_button('ttbm_display_advance', $checked); ?>
                            </div>
                        </div>
                    </section>
                    <?php do_action('ttbm_ticket_type_before', $tour_id); ?>
                    <div class="ovAuto _mT">
                        <table>
                            <thead>
                            <tr>
                                <?php do_action('ttbm_ticket_type_headeing_start', $tour_id); ?>
                                <th><?php esc_html_e('Ticket Icon', 'tour-booking-manager'); ?></th>
                                <th><?php esc_html_e('Ticket Name', 'tour-booking-manager'); ?><span class="textRequired">&nbsp;*</span></th>
                                <th data-collapse="#ttbm_display_advance" class="<?php echo esc_attr($active); ?>">
                                    <?php esc_html_e('Short Description', 'tour-booking-manager'); ?>
                                </th>
                                <th><?php esc_html_e('Regular Price', 'tour-booking-manager'); ?><span class="textRequired">&nbsp;*</span></th>
                                <th data-collapse="#ttbm_display_advance" class="<?php echo esc_attr($active); ?>">
                                    <?php esc_html_e('Sale Price', 'tour-booking-manager'); ?>
                                </th>
                                <th <?php do_action('ttbm_aq_target_hook', $tour_id); ?>><?php esc_html_e('Capacity', 'tour-booking-manager'); ?><span class="textRequired">&nbsp;*</span></th>
                                <th data-collapse="#ttbm_display_advance" class="<?php echo esc_attr($active); ?>">
                                    <?php esc_html_e('Default Qty', 'tour-booking-manager'); ?>
                                </th>
                                <th data-collapse="#ttbm_display_advance" class="<?php echo esc_attr($active); ?>">
                                    <?php esc_html_e("Reserve Qty", "tour-booking-manager"); ?>
                                </th>
                                <?php do_action('ttbm_ticket_type_headeing_end', $tour_id); ?>
                                <th><?php esc_html_e('Qty Box Type', 'tour-booking-manager'); ?></th>
                                <th><?php esc_html_e('Action', 'tour-booking-manager'); ?></th>
                            </tr>
                            </thead>
                            <tbody class="mp_sortable_area mp_item_insert">
                            <?php
                            if (sizeof($ticket_type) > 0) {
                                foreach ($ticket_type as $field) {
                                    $this->pricing_item($field);
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end py-2">
                        <?php MP_Custom_Layout::add_new_button(esc_html__('Add New Ticket Type', 'tour-booking-manager')); ?>
                    </div>
                    <?php do_action('add_mp_hidden_table', 'ttbm_price_item'); ?>
                </div>

                <?php do_action('ttbm_tour_pricing_inner', $tour_id); ?>
            </section>
            <?php
        }
        public function pricing_item($field = array()) {
            $tour_id = get_the_id();
            $display = MP_Global_Function::get_post_info($tour_id, 'ttbm_display_advance', 'off');
            $active = $display == 'off' ? '' : 'mActive';
            $field = $field ?: array();
            $icon = array_key_exists('ticket_type_icon', $field) ? $field['ticket_type_icon'] : '';
            $name = array_key_exists('ticket_type_name', $field) ? $field['ticket_type_name'] : '';
            $name_text = preg_replace("/[{}()<>+ ]/", '_', $name) . '_' . $tour_id;
            $price = array_key_exists('ticket_type_price', $field) ? $field['ticket_type_price'] : '';
            $sale_price = array_key_exists('sale_price', $field) ? $field['sale_price'] : '';
            $qty = array_key_exists('ticket_type_qty', $field) ? $field['ticket_type_qty'] : '';
            $default_qty = array_key_exists('ticket_type_default_qty', $field) ? $field['ticket_type_default_qty'] : '';
            $reserve_qty = array_key_exists('ticket_type_resv_qty', $field) ? $field['ticket_type_resv_qty'] : '';
            $input_type = array_key_exists('ticket_type_qty_type', $field) ? $field['ticket_type_qty_type'] : 'inputbox';
            $description = array_key_exists('ticket_type_description', $field) ? $field['ticket_type_description'] : '';
            ?>
            <tr class="mp_remove_area">
                <?php do_action('ttbm_ticket_type_content_start', $field, $tour_id) ?>
                <td><?php do_action('mp_input_add_icon', 'ticket_type_icon[]', $icon); ?></td>
                <td>
                    <input type="hidden" name="ttbm_hidden_ticket_text[]" value="<?php echo esc_attr($name_text); ?>"/>
                    <label>
                        <input type="text" class="mp_name_validation" name="ticket_type_name[]" placeholder="Ex: Adult" value="<?php echo esc_attr($name); ?>" data-input-text="<?php echo esc_attr($name_text); ?>"/>
                    </label>
                </td>
                <td data-collapse="#ttbm_display_advance" class="<?php echo esc_attr($active); ?>">
                    <label>
                        <input type="text" class="" name="ticket_type_description[]" placeholder="Ex: description" value="<?php echo esc_attr($description); ?>"/>
                    </label>
                </td>
                <td>
                    <label>
                        <input type="text" class="small mp_price_validation" name="ticket_type_price[]" placeholder="Ex: 10" value="<?php echo esc_attr($price); ?>"/>
                    </label>
                </td>
                <td data-collapse="#ttbm_display_advance" class="<?php echo esc_attr($active); ?>">
                    <label>
                        <input type="text" class="small mp_price_validation" name="ticket_type_sale_price[]" placeholder="Ex: 10" value="<?php echo esc_attr($sale_price); ?>"/>
                    </label>
                </td>
                <td <?php do_action('ttbm_aq_target_hook', $tour_id); ?>>
                    <label>
                        <input type="number" size="4" pattern="[0-9]*" step="1" class="small mp_number_validation" data-same-input="ticket_type_qty" name="ticket_type_qty[]" placeholder="Ex: 500" value="<?php echo esc_attr($qty); ?>"/>
                    </label>
                </td>
                <td data-collapse="#ttbm_display_advance" class="<?php echo esc_attr($active); ?>">
                    <label>
                        <input type="number" size="4" pattern="[0-9]*" step="1" class="small mp_number_validation" name="ticket_type_default_qty[]" placeholder="Ex: 1" value="<?php echo esc_attr($default_qty); ?>"/>
                    </label>
                </td>
                <td data-collapse="#ttbm_display_advance" class="<?php echo esc_attr($active); ?>">
                    <label>
                        <input type="number" size="4" pattern="[0-9]*" step="1" class="small mp_number_validation" data-same-input="ticket_type_resv_qty" name="ticket_type_resv_qty[]" placeholder="Ex: 5" value="<?php echo esc_attr($reserve_qty); ?>"/>
                    </label>
                </td>
                <?php do_action('ttbm_ticket_type_content_end', $field, $tour_id) ?>
                <td>
                    <label>
                        <select name="ticket_type_qty_type[]" class='formControl'>
                            <option value="inputbox" <?php echo esc_attr($input_type == 'inputbox' ? 'selected' : ''); ?>><?php esc_html_e('Input Box', 'tour-booking-manager'); ?></option>
                            <option value="dropdown" <?php echo esc_attr($input_type == 'dropdown' ? 'selected' : ''); ?>><?php esc_html_e('Dropdown List', 'tour-booking-manager'); ?></option>
                        </select>
                    </label>
                </td>
                <td><?php MP_Custom_Layout::move_remove_button(); ?></td>
            </tr>
            <?php
        }


        public function ttbm_hotel_config($tour_id) {
            $ttbm_hotels = TTBM_Function::get_hotel_list($tour_id);
            $hotel_lists = MP_Global_Function::query_post_type('ttbm_hotel');
            $ttbm_type = TTBM_Function::get_tour_type($tour_id);
            $hotel_class = $ttbm_type == 'hotel' ? '' : 'dNone';
            ?>

            <section class="ttbm_tour_hotel_setting component mb-2 <?php echo esc_attr($hotel_class); ?>">
                <div class="w-100 d-flex justify-content-between align-items-center">
                    <label class=""><?php esc_html_e('Hotel Configuration :', 'tour-booking-manager'); ?>  <i class="fas fa-question-circle tool-tips"><span><?php esc_html_e('Select Hotel name that you want to include in this tour , Tour ticket price works based on hotel price configuration . To add new hotel  ', 'tour-booking-manager'); ?>
						<a href="post-new.php?post_type=ttbm_hotel"><?php esc_html_e('click here', 'tour-booking-manager'); ?></a></span></i></label>
                    <div class="w-60 d-flex justify-content-between align-items-center">
                        <select name="ttbm_hotels[]" multiple='multiple' class='formControl ttbm_select2' data-placeholder="<?php esc_html_e('Please Select Hotel', 'tour-booking-manager'); ?>">
                            <?php
                            foreach ($hotel_lists->posts as $hotel) {
                                $hotel_id = $hotel->ID;
                                ?>
                                <option value="<?php echo esc_attr($hotel_id) ?>" <?php echo in_array($hotel_id, $ttbm_hotels) ? 'selected' : ''; ?>><?php echo get_the_title($hotel_id); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </section>
            <?php
        }
        /*******************************/
        public function advertise_addon() {
            if (!class_exists('TTBMA_Seasonal_Pricing')) {
                ?>
                <div class="_dLayout_bgYellow_77">
                    <div class="textColor_1 alignCenter">
                        <span class="fas fa-dollar-sign fa-2x"></span> &nbsp;&nbsp;
                        <strong>
                            <?php esc_html_e('Seasonal pricing addon allow different pricing  based on  date range, time slot etc..  ', 'tour-booking-manager'); ?>&nbsp;
                            <a href="https://mage-people.com/product/seasonal-pricing-addon-for-woocommerce-tour-plugin/" target="_blank"><?php esc_html_e('Get your Seasonal price addon now', 'tour-booking-manager'); ?></a> </strong>
                    </div>
                </div>
                <?php
            }
            if (!class_exists('TTBMA_Group_Pricing')) {
                ?>
                <div class="_dLayout_bgColor_3">
                    <div class="textColor_1 alignCenter">
                        <span class="fas fa-fill-drip fa-2x"></span> &nbsp;&nbsp;
                        <strong>
                            <?php esc_html_e('Group price allow different pricing during buying based on quantity .', 'tour-booking-manager'); ?>&nbsp;
                            <a href="https://mage-people.com/product/group-pricing-or-bulk-qty-discount-addon-for-tour-plugin/" target="_blank"><?php esc_html_e('Get your Group pricing addon now', 'tour-booking-manager'); ?></a> </strong>
                    </div>
                </div>
                <?php
            }
        }
        /***********/
        public function save_pricing($tour_id) {
            if (get_post_type($tour_id) == TTBM_Function::get_cpt_name()) {
                $registration = MP_Global_Function::get_submit_info('ttbm_display_registration') ? 'on' : 'off';
                update_post_meta($tour_id, 'ttbm_display_registration', $registration);
                $advance_option = MP_Global_Function::get_submit_info('ttbm_display_advance') ? 'on' : 'off';
                update_post_meta($tour_id, 'ttbm_display_advance', $advance_option);
                //*********Tour Type**************//
                $tour_type = MP_Global_Function::get_submit_info('ttbm_type', 'general');
                update_post_meta($tour_id, 'ttbm_type', $tour_type);
                //*********Hotel Configuration**************//
                $ttbm_hotels = MP_Global_Function::get_submit_info('ttbm_hotels', array());
                update_post_meta($tour_id, 'ttbm_hotels', $ttbm_hotels);
                //*********Regular Ticket Price**************//
                $ttbm_travel_type = TTBM_Function::get_travel_type($tour_id);
                if($ttbm_travel_type == 'fixed')
                {
                    update_post_meta($tour_id, 'ttbm_travel_start_date', MP_Global_Function::get_submit_info('ttbm_travel_start_date'));
                    update_post_meta($tour_id, 'ttbm_travel_start_date_time', MP_Global_Function::get_submit_info('ttbm_travel_start_date_time'));
                    update_post_meta($tour_id, 'ttbm_travel_end_date', MP_Global_Function::get_submit_info('ttbm_travel_end_date'));
                    update_post_meta($tour_id, 'ttbm_travel_reg_end_date', MP_Global_Function::get_submit_info('ttbm_travel_reg_end_date'));
                }
                elseif ($ttbm_travel_type == 'particular') {
                    $after_day = date('Y-m-d', strtotime(' +500 day'));
                    update_post_meta($tour_id, 'ttbm_travel_reg_end_date', $after_day);
                }
                elseif ($ttbm_travel_type == 'repeated') {
                    update_post_meta($tour_id, 'ttbm_travel_reg_end_date', MP_Global_Function::get_post_info($tour_id, 'ttbm_travel_end_date'));
                }
                else
                {
                    update_post_meta($tour_id, 'ttbm_travel_reg_end_date', MP_Global_Function::get_post_info($tour_id, 'ttbm_travel_reg_end_date'));
                }
                
                //*************Regular ticket***********************//
                $new_ticket_type = array();
                $icon = MP_Global_Function::get_submit_info('ticket_type_icon', array());
                $names = MP_Global_Function::get_submit_info('ticket_type_name', array());
                $ticket_price = MP_Global_Function::get_submit_info('ticket_type_price', array());
                $sale_price = MP_Global_Function::get_submit_info('ticket_type_sale_price', array());
                $qty = MP_Global_Function::get_submit_info('ticket_type_qty', array());
                $qty = apply_filters('ttbm_ticket_type_qty', $qty, $tour_id);
                $default_qty = MP_Global_Function::get_submit_info('ticket_type_default_qty', array());
                $rsv = MP_Global_Function::get_submit_info('ticket_type_resv_qty', array());
                $rsv = apply_filters('ttbm_ticket_type_resv_qty', $rsv, $tour_id);
                $qty_type = MP_Global_Function::get_submit_info('ticket_type_qty_type', array());
                $description = MP_Global_Function::get_submit_info('ticket_type_description', array());
                $count = count($names);
                for ($i = 0; $i < $count; $i++) {
                    if ($names[$i] && $ticket_price[$i] >= 0 && $qty[$i] > 0) {
                        $new_ticket_type[$i]['ticket_type_icon'] = $icon[$i] ?? '';
                        $new_ticket_type[$i]['ticket_type_name'] = $names[$i];
                        $new_ticket_type[$i]['ticket_type_price'] = $ticket_price[$i];
                        $new_ticket_type[$i]['sale_price'] = $sale_price[$i];
                        $new_ticket_type[$i]['ticket_type_qty'] = $qty[$i];
                        $new_ticket_type[$i]['ticket_type_default_qty'] = $default_qty[$i] ?? 0;
                        $new_ticket_type[$i]['ticket_type_resv_qty'] = $rsv[$i] ?? 0;
                        $new_ticket_type[$i]['ticket_type_qty_type'] = $qty_type[$i] ?? 'inputbox';
                        $new_ticket_type[$i]['ticket_type_description'] = $description[$i] ?? '';
                    }
                }
                $ticket_type_list = apply_filters('ttbm_ticket_type_arr_save', $new_ticket_type);
                update_post_meta($tour_id, 'ttbm_ticket_type', $ticket_type_list);

            }
        }
    }
    new TTBM_Settings_pricing();
}